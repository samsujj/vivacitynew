<?php

require_once( ai_cascadepath( 'includes/core/classes/error_base.php' ) );
require_once( ai_cascadepath( 'includes/modules/orders/includes/class.order_email.php' ) );
require_once( ai_cascadepath( 'includes/modules/campaigns/class.affiliate_tracking.php' ) );
require_once( ai_cascadepath( 'includes/modules/campaigns/class.campaigns.php' ) );
require_once( ai_cascadepath( 'includes/modules/campaigns/class.conversions.php' ) );
//require_once( ai_cascadepath( 'includes/plugins/subscriptions/class.subscriptions.php' ) );
require_once( ai_cascadepath( 'includes/modules/billing_profiles/class.billing_profile.php' ) );
require_once( ai_cascadepath( 'includes/modules/products/includes/class.product.php' ) );
require_once( ai_cascadepath( 'includes/modules/store/includes/class.cart.php' ) );

/**
 * 2011.10.18
 *
 * - Set up the order with a status of processing
 * - process the transactions (separate class, txn system)
 *   - the txn system will be give the order # for further tracking
 * - update the order with the results of processing
 *
 * assembled and modified from old skeleton txn/functions.php and orders/functions.php -ben 2011.10.18
 *


	run_order($bp, $source = '', $scheduled_purchase_id = 0, $owner_id=0)
		create($bp, $source, $scheduled_purchase_id, $owner_id)
			INSERT ORDER AND ORDER CONTENTS
			set_order($this->order_id)
		run_hooks()
			$m = choose_merchant();
			make_transaction($m);
			fulfill();
			schedule_pruchases();
		close_order()
			finalize($order_id, $allow_conversion); //update status based on payment
*/
class C_order extends C_error_base
{
	const NO_CHARGE_TXN_ID = -999;

	private $order_id = ''; //primary key int(10)
	private $o = array(); //contents of the orders + order_details table row, if they exist
	private $cart_secondID = '';

	private $log_id=0;
	private $log_arr=0;

	public $source_type;
	public $source_name;

	private $declined_order_system_email_obj;

	public function __construct( $order_id = '', $source_type = '', $source_name = '' )
	{
		global $AI;

		if ( $order_id != '' )
		{
			$this->set_order($order_id);
			$this->o['source_type'] = $source_type;
			$this->o['source_name'] = $source_name;
		}

		$this->source_type = $source_type;
		$this->source_name = $source_name;

		$this->attack_guardian_init();

	}

	/**
	 * Set and get order declined system email
	 * @param C_system_emails $declined_order_system_email_obj The instantiation of the system_emails class with the 'order_declined' email
	 * @author  felipe
	 * @date   2016-11-08
	 */
	public function set_declined_order_system_email_obj(C_system_emails $declined_order_system_email_obj) { $this->declined_order_system_email_obj = $declined_order_system_email_obj; }
	/**
	 * Getter
	 * @return C_system_emails The C_system_emails object
 	 * @author  felipe
	 * @date   2016-11-08
	 */
	public function get_declined_order_system_email_obj() { return $this->declined_order_system_email_obj; }

	/**
	 * Send order declined email.
	 * Method validates and pulls the necessary items to submit email
	 * @param  array           $order_result      The order db results
	 * @param  C_system_emails $system_emails_obj The system_emails object with order_declined email
	 * @return boolean                            True if email send successfully
	 * @author  felipe
	 * @date    2016-11-08
	 */
	public static function send_declined_order_email(array $order_result, C_system_emails $system_emails_obj)
	{
		$orders_module = aimod_get_active_modules('orders');
		$orders_module_settings = unserialize($orders_module['settings']);

		// gauntlet
		if( ! isset($system_emails_obj) ) {return false;}
		if( ! in_array($order_result['source_type'], $orders_module_settings['send_order_decline_email_if_source_type'] ) ) {return false;}
		if( (isset($order_result['payment_status']) && $order_result['payment_status'] != "") == false ) {return false;} // variable exist
		if( ($order_result['payment_status'] == 'DECLINED') == false ) {return false;} // payment_status is not declined
		if(isset($_SESSION['orders_sent_declined_email']) &&  in_array($order_result['order_id'], $_SESSION['orders_sent_declined_email']) ) {return false;} // only send one per session and order_id

		// find any serialized value in array and unserialize it
		foreach ($order_result as $key => $value)
		{
			if(util_is_serialized($value)) {
				$order_result[$key] = unserialize($value);
			}
		}

		$vars = array('first_name'=>$order_result['billing_addr']['first_name']
						,'last_name'=>$order_result['billing_addr']['last_name']
						,'order_id'=>$order_result['order_id']
					);
		$system_emails_obj->set_vars_array($vars);

		if(!$system_emails_obj->send_cache())
		{
			trigger_error('Sending system email failed: '.implode($system_emails_obj->get_errors(), ', ') );
		}
		else
		{
			// record in session that and email was sent already regarding order_id
			if(isset($_SESSION['orders_sent_declined_email']) == false )
			{
				$_SESSION['orders_sent_declined_email'] = array();
			}
			$_SESSION['orders_sent_declined_email'][] = $order_result['order_id'];
			return true;
		}
	}

	/**
	 * Let all C_cart instantiations in this order instance use a specific cart
	 * You do not need to call this to use the default cart
	 * @param string $secondID The key of the cart (not userID)
	 */
	public function use_cart( $secondID )
	{
		$this->cart_secondID = trim($secondID . '');
	}

    // Run the entire order sequence in one method call

    public function run_order($bp, $source = '', $scheduled_purchase_id = 0, $owner_id=0, $set_assoc=null)
    {
        //SAVE THE BILLING PROFILE
        $bp->save();

        $this->olog("run_order(bp,$source,sched=$scheduled_purchase_id,owner=$owner_id)",$bp);
        // LOCK
        $lock = $this->get_lock($bp);
        if ( $lock === false )
        {
            return false;
        }


        //CREATE THE ORDER
        $successful = false;
        $order_id = $this->create($bp, $source, $scheduled_purchase_id, $owner_id, $set_assoc);
        if ( !empty($order_id) )
        {
            $successful = $this->run_hooks();
            if ( $successful )
            {
                $this->close_order();
            }
            else {
                // Still need to sync the status to the payment status
                $this->sync_status_using_payment_status($order_id);
            }

            if($this->o['payment_status'] == 'DECLINED')
            {
                aimod_run_hook('hook_orders_declined', $this->order_id, $this->source_type, $this->o, $this);
                // email recipients are added inside the hook into the declined_order_system_email_obj object
                if ( isset($this->declined_order_system_email_obj) )
                {
                    self::send_declined_order_email($this->o, $this->declined_order_system_email_obj);
                }
            }
            if($this->o['payment_status'] == 'ERROR')
            {
                aimod_run_hook('hook_orders_error', $this->order_id, $this->source_type);
            }
        }

        // This shouldn't be necessary as this will already be run via $this->run_hooks() above
        // Also, this shouldn't run unless an order was successful, which $this->run_hooks() should take care of
        // -JonJon 2015-12-15 13:51 -1000
        //aimod_run_hook('hook_orders_fulfill', $this->o);

        // UNLOCK
        $successful_lock = $successful ? $lock : null;
        $this->release_lock($successful_lock);

        return $successful;
    }


    // Run the entire order sequence in one method call(for front end testing purpose)

    public function run_order1($bp, $source = '', $scheduled_purchase_id = 0, $owner_id=0, $set_assoc=null)
    {
        //SAVE THE BILLING PROFILE
        $bp->save();

        $this->olog("run_order(bp,$source,sched=$scheduled_purchase_id,owner=$owner_id)",$bp);
        // LOCK
        $lock = $this->get_lock($bp);
        if ( $lock === false )
        {
            return false;
        }


        //CREATE THE ORDER
        $successful = true;
        $order_id = $this->create($bp, $source, $scheduled_purchase_id, $owner_id, $set_assoc);

        // This shouldn't be necessary as this will already be run via $this->run_hooks() above
        // Also, this shouldn't run unless an order was successful, which $this->run_hooks() should take care of
        // -JonJon 2015-12-15 13:51 -1000
        //aimod_run_hook('hook_orders_fulfill', $this->o);

        // UNLOCK
        $successful_lock = $successful ? $lock : null;
        $this->release_lock($successful_lock);

        return $successful;
    }

	/**
	 * Create a file-level lock for a specific order submission
	 * @param  C_billing_profiles $bp An instance of a billing profile
	 * @return string                 A hash of the current Billing Profile instance, Cart instance (derived), and PHP Session ID
	 */
	protected function create_lock_key( C_billing_profiles $bp )
	{

		// Fetch the C_cart instance
		$userID = $bp->get_account_id();
		if ( !empty($_SESSION['fulfillment_override_order_id']) && !empty($_SESSION['fulfillment_override_userID']) )
		{
			$userID = (int) $_SESSION['fulfillment_override_userID'];
		}
		$cart = new C_cart($userID, true, $this->cart_secondID);

		// Create a hash of the Billing Profile, Cart, and Session combined
		$bp_ser   = serialize($bp);
		$cart_ser = serialize($cart);
		$sess_id  = session_id();

		return sha1($bp_ser . $cart_ser . $sess_id);
	}

	/**
	 * Attempts to lock down the ordering process for the current session
	 * @param  C_billing_profiles $bp An instance of a billing profile (for hashing)
	 * @return string|bool            FALSE for a failed attempt (previously locked), or a string of the current key/hash
	 */
	protected function get_lock( C_billing_profiles $bp )
	{
		global $AI;

		$sess_id  = session_id();
		$lock_key = $this->create_lock_key($bp);
		$expiry   = '5 minutes';
		$this->olog("get_lock($lock_key)");

		// Critical Error: File Lock on a recently successful order
		$cache = get_cache('orders/successful', $sess_id, $expiry);
		if ( $cache == $lock_key )
		{
			$bp_copy = clone $bp;
			$bp_copy->cc['card_number'] = 'REMOVED'; // Stay PCI-compliant in our error logs
			$bp_copy->cc['card_cvv']    = 'REMOVED'; // Stay PCI-compliant in our error logs
			$AI->error('Duplicate order blocked by file lock', __FILE__, array
				( 'C_billing_profiles' => $bp_copy
				, 'session_id'         => $sess_id
				, 'hash'               => $lock_key
				));
			$this->olog("Duplicate order blocked by file lock!");
			return false;
		}

		// Ignorable Error: File Lock on a pending, yet to be successful order
		$cache = get_cache('orders/processing', $sess_id, $expiry);
		if ( $cache == $lock_key )
		{
			$this->olog("Ignorable Error: File Lock on a pending, yet to be successful order");
			return false;
		}

		// Lock Now
		set_cache('orders/processing', $sess_id, $lock_key);
		return $lock_key;
	}

	/**
	 * Releases the current lock
	 * @param  string $successful_lock_key (Optional) Set this with the key returned by get_lock() if the current order was a success
	 * @return void
	 */
	protected function release_lock( $successful_lock_key = null )
	{
		$this->olog("release_lock($successful_lock_key)");
		$sess_id  = session_id();
		delete_cache('orders/processing', $sess_id);
		if ( $successful_lock_key !== null )
		{
			set_cache('orders/successful', $sess_id, $successful_lock_key);
		}
	}

	// Get the order ID (now has private access)
	public function get_order_id()
	{
		if ( !empty($this->order_id) )
		{
			return (int) $this->order_id;
		}
		return false;
	}

	public function get_id() { return $this->get_order_id(); }

	// load an existing order from the database
	public function set_order($order_id)
	{
		global $AI;


		/*
		$this->o = db_lookup_assoc("SELECT * FROM orders NATURAL JOIN order_details WHERE order_id = '" . db_in($order_id) . "' LIMIT 1;");

		if(empty($this->o))
		{
			//could not find order
			$this->order_id = 0;
			$AI->error('Order Lookup Fail', __FILE__, array('order_id' => $order_id));
			return false;
		}
		else
		{
			$this->order_id = $order_id;
			$this->o['contents'] = unserialize($this->o['contents']);
			$this->o['billing_addr'] = unserialize($this->o['billing_addr']);
			$this->o['shipping_addr'] = unserialize($this->o['shipping_addr']);
			$this->o['card_information'] = unserialize($this->o['card_information']);
			return true;
		}
		*/


		$this->o = db_lookup_assoc("SELECT * FROM orders WHERE order_id = " . (int) db_in($order_id) . " LIMIT 1;");
		if ( !isset($this->o['order_id']) )
		{
			$this->order_id = 0;
			$this->error('Order Lookup Fail (' . $order_id . '): ' . db_error());
			$this->olog('Order Lookup Fail (' . $order_id . '): ' . db_error());
			return false;
		}

		$this->order_id = (int) $this->o['order_id'];
		$this->olog("set_order($order_id) - FOUND");

		$this->o['billing_addr'] = unserialize($this->o['billing_addr']);
		$this->o['shipping_addr'] = unserialize($this->o['shipping_addr']);
		$this->o['card_information'] = unserialize(db_decrypt($this->o['card_information']));
		$this->o['contents'] = array();

		$sql = "
			SELECT *
			FROM order_details
			WHERE order_id = " . (int) db_in($order_id) . "
			ORDER BY id ASC
		;";
		$res = db_query($sql);
		if ( $res )
		{
			$numrows = db_num_rows($res);
			if ( 0 < $numrows )
			{
				$this->olog("set_order:contents($numrows)");
				while ( $res && $row = db_fetch_assoc($res) )
				{
					$row = array_map('db_out', $row);
					$this->o['contents'][] = $row;
				}
			}
		}
		return true;
	}

	public function get($name)
	{
		if ( isset($this->o[$name]) )
		{
			return $this->o[$name];
		}
		return false;
	}

	public function get_order_array() { return $this->o; }

	public function get_contents()
	{
		if ( empty($this->order_id) )
		{
			return false;
		}
		return $this->o['contents'];
	}

	/**
	 * Grabs address information unserialized or grab specific data from the address
	 * Example Usage: get_address('billing', 'first_name')
	 *
	 * Alternatively pass in a serialized array as $type with a TRUE $key to unserialize the data in $type
	 * Alternate Usage: get_address($serialized_data, true);
	 *
	 * @param  string $type One of 'billing' or 'shipping'
	 * @param  string $key  Any of the individual fields in 'billing' or 'shipping'
	 * @return array|string The unserialized array or single string value; NULL if $type or $key don't resolve to anything or an order isn't open
	 */
	public function get_address( $type, $key = null )
	{
		// Nothing to do if we have no order open
		if ( empty($this->order_id) && $key !== true ) { return null; }

		// Forgive undocumented arguments
		if ( $type == 'bill' ) { $type = 'billing' ; }
		if ( $type == 'ship' ) { $type = 'shipping'; }

		// Choose the correct serialized data
		if ( $key === true )
		{
			$address = @unserialize($type);
		}
		elseif ( $type == 'billing' )
		{
			$address = $this->o['billing_addr'];
		}
		elseif ( $type == 'shipping' )
		{
			$address = $this->o['shipping_addr'];
		}
		else
		{
			return null;
		}

		// Check readability
		if ( !is_array($address) )
		{
			return null;
		}

		// Chain-hook-based modification of data
		$address = aimod_run_hook_chain('hook_chain_orders_get_address', $address);

		// Return
		if ( $key !== null && $key !== true )
		{
			return uv($address, $key, null);
		}
		return $address;
	}

	// create a new order in preparation for a transaction
	public function create($bill_profile, $source = '', $scheduled_purchase_id = 0, $owner_id=0, $set_assoc=null)
	{
		global $AI, $cart;
		$this->olog("create(bp,source=$source, sched=$scheduled_purchase_id, owner=$owner_id)",$bill_profile);

		//$userID = $bill_profile->account_id; //  Use billing proile as account_id! ~ 2009.10.10
		$userID = $bill_profile->get_account_id();
		if ( !empty($_SESSION['fulfillment_override_order_id']) && !empty($_SESSION['fulfillment_override_userID']) ) {
			$userID = (int) $_SESSION['fulfillment_override_userID'];
		}

		//$order_snapshot = get_order_snapshop_from_cart($userID);
		//$order_snapshot = $this->get_snapshot_from_cart($userID);
		$order_snapshot = null;
		$cart = new C_cart($userID, true, $this->cart_secondID);
		$cart->set_billing_addr(  $bill_profile->billing_addr['first_name'], $bill_profile->billing_addr['last_name'], $bill_profile->billing_addr['address_line_1'], $bill_profile->billing_addr['address_line_2'], $bill_profile->billing_addr['city'], $bill_profile->billing_addr['region'], $bill_profile->billing_addr['country'], $bill_profile->billing_addr['postal_code'], $bill_profile->billing_addr['email'], $bill_profile->billing_addr['phone'] );
		$cart->set_shipping_addr(  $bill_profile->shipping_addr['first_name'], $bill_profile->shipping_addr['last_name'], $bill_profile->shipping_addr['address_line_1'], $bill_profile->shipping_addr['address_line_2'], $bill_profile->shipping_addr['city'], $bill_profile->shipping_addr['region'], $bill_profile->shipping_addr['country'], $bill_profile->shipping_addr['postal_code'], $bill_profile->shipping_addr['address_key'] );
		$cc = array();
		// Save censored information in the order
		foreach ($bill_profile->cc as $key => $value) {
			switch ($key) {
				case 'card_number':
				case 'card_num':
					// $value = '************'.substr($value, -4);
					$value = util_mask_str($value, '*', 0, -4);
					break;

				case 'cvv':
				case 'card_cvv':
					// $value = '***';
					$value = util_mask_str($value, '*');
					break;
			}
			$cc[$key]=$value;
		}

		if ( isset($cart->handling_fee) ) {
			$shipping_handling = $cart->shipping_rate + $cart->handling_fee;
		}
		else {
			$shipping_handling = $cart->shipping_rate;
		}

		$sql_now = date('Y-m-d H:i:s');



		// Write order to the db
		/*
		$set_sql = "
			SET userID = '" . $userID . "'
			, date_added = '" . db_in($sql_now) . "'
			, contents = '" . db_in( util_serialize( $order_snapshot ) ) . "'
			, billing_addr = '" . db_in( util_serialize( $bill_profile->billing_addr ) ) . "'
			, shipping_addr = '" . db_in( util_serialize( $bill_profile->shipping_addr ) ) . "'
			, card_information = '" . db_in( util_serialize( $cc ) ) . "'
			, shipping = " . (double)db_in($shipping_handling) . "
			, tax = " . (double)db_in($cart->get_tax()) . "
			, total = " . (double)db_in($cart->get_total());
		*/

		$set_sql = "
			SET userID = '" . $userID . "'
			, date_added = '" . db_in($sql_now) . "'
			, source_type = '" . db_in($this->source_type) . "'
			, source_name = '" . db_in($this->source_name) . "'
			, billing_profile_id = " . (int) db_in($bill_profile->get_profile_id()) . "
			, billing_addr = '" . db_in(util_serialize($bill_profile->billing_addr)) . "'
			, shipping_addr = '" . db_in(util_serialize($bill_profile->shipping_addr)) . "'
			, card_information = '" . db_in(db_encrypt(util_serialize($cc))) . "'
			, shipping = " . (double) db_in($shipping_handling) . "
			, tax = " . (double) db_in($cart->get_tax($bill_profile)) . "
			, total = " . (double) db_in($cart->get_total()) . "
			, owner_id = " . (int)$owner_id . "
			, scheduled_purchase_id = " . (int)$scheduled_purchase_id . "
			, ip = '" . db_in(@$_SERVER['REMOTE_ADDR'] . '') . "'
		";

		$set_sql .= ', billing_text="'.implode(' ',array_map('db_in',$bill_profile->billing_addr)).'"';
		$set_sql .= ', shipping_text="'.implode(' ',array_map('db_in',$bill_profile->shipping_addr)).'"';

		if(is_array($set_assoc) && count($set_assoc)>0) {
			foreach($set_assoc as $n=>$value) {
				$set_sql .= ", $n=";
				if(is_array($value)) { $value='Array()'; }
				if($value=='now()' || $value=='null') $set_sql .= "$value";
				else $set_sql .= '\'' . db_in($value) . '\'';
			}
		}

		// start - log shipping module
		$store_mod = aimod_get_module('store');
		$shipping_method_table_map = array(
			'shipping_method_name'=>'table_name'
			,'ship_doba' => 'shipping_doba'
			,'ship_fedex' => 'shipping_fedex'
			,'ship_shipping_base' => 'shipping_shipping_base'
			,'ship_per_product' => 'shipping_per_product'
			,'ship_by_flatrate' => 'shipping_by_flatrate'
			,'ship_by_price' => 'shipping_by_price'
			,'ship_by_weight' => 'shipping_by_weight'
			);

		$sql = 'SELECT * FROM '.$shipping_method_table_map[$store_mod->shipping_module].'';
		$shipping_manager_results = $AI->db->GetAll($sql);
		$this->olog('','',
			array
			(
			'shipping_module'=>$store_mod->shipping_module
			,'shipping_manager'=>serialize($shipping_manager_results)
			)
		);
		// end - log shipping module

		// Save the orderID to use
		$sql = "SELECT 1;"; // Do nothing important
		$mode='insert';
		if($this->order_id>0 && $this->cart_secondID=='scheduled_purchases') {
			$sql = "UPDATE orders " . $set_sql . " WHERE order_id = " . intval($this->order_id) . " LIMIT 1;";
			$mode='update';
		}
		else {
			$campaign_id = util_GET_and_cache('aitrk');
			$campaign_sub_id = util_GET_and_cache('aitsub');
			if ( !empty($campaign_id) )
			{
				$set_sql .= ", campaign_id = " . (int) $campaign_id . ", campaign_sub_id = '" . db_in($campaign_sub_id) . "' ";
			}
			$sql = "INSERT INTO orders " . $set_sql . ";";
			$this->order_id = 0;
		}

		if ( db_query($sql) )
		{
			if($this->order_id==0) $this->order_id = db_insert_id();
			$_SESSION['non_finalized_checkout'][$source] = $this->order_id;
			$this->olog("Order insert success!");
			//else { $this->order_id = $_SESSION['non_finalized_checkout'][$source]; }
		}
		else
		{
			//ERROR: email error to admin then die
			$db_error = db_error();
			$msg = "ERROR: UNABLE TO SAVE ORDER DATA  ". "\n" . $db_error . "\n" . $sql;
			$AI->error($msg, __FILE__, array('sql'=>$sql,'db_error'=>$db_error));
			$this->olog("ORDER DB SAVE FAILED!");
			die("ERROR: UNABLE TO SAVE ORDER DATA <!-- (" . $db_error . ") [" . $sql . "]-->");
		}

		$contents_saved = 0;
		$all_product_info = array();
		foreach ( $cart->contents as $stock_item_id => $content )
		{
			$product = C_product::get_new_product_from_stock($stock_item_id);
			$stock = $product->get_stock($stock_item_id);
			$all_product_info[] = $stock;

			$cost = $stock->get_cost();
			$price = ($stock->get_man_price_range()!='' && isset($content['man_price']))? $content['man_price']:$stock->get_price($cart->disable_alt_pricing);
			$title = (@$content['title']!='')? $content['title']:$product->get_title();
			$set_sql = "
				SET order_id = " . (int) $this->order_id . "
				, product_id = " . (int) $product->get_id() . "
				, url_name = '" . db_in($product->get_url()) . "'
				, title = '" . db_in($title) . "'
				, stock_item_id = " . (int) $stock_item_id . "
				, attributes = '" . db_in($stock->get_attribute_string(true)) . "'
				, product_code = '" . db_in($stock->get_product_code()) . "'
				, cost = " . (double) $cost . "
				, price = " . (double) $price . "
				, qty = " . (int) $content['qty'] . "
			";
			if($mode=='update') $sql = "UPDATE order_details " . $set_sql . " WHERE order_id=".intval($this->order_id)." AND stock_item_id=".intval($stock_item_id).";";
			else $sql = "INSERT INTO order_details " . $set_sql . ";";
			//else { $sql = "UPDATE order_details " . $set_sql . " WHERE order_id = " . (int) $_SESSION['non_finalized_checkout'][$source] . ";"; }
			if( !db_query($sql) )
			{
				// email error to admin then die
				$db_error = db_error();
				$msg = "ERROR: UNABLE TO SAVE ORDER DETAILS DATA  ". "\n" . $db_error . "\n" . $sql;
				$AI->error($msg, __FILE__, array('sql' => $sql, 'db_error' => $db_error));
				$this->olog($msg);
				die("ERROR: UNABLE TO SAVE ORDER DETAILS DATA <!-- (" . $db_error . ") [" . $sql . "]-->");
			}
			else
			{
				$contents_saved++;
			}
		}

		$this->olog('Saved ' . $contents_saved . ' contents to DB','',array('product_information'=>serialize($all_product_info) ));


		/* // not yet implemented in backbone
		// Insert each item ordered into the histoy table.  Good for creating graphs w/ stats, reports etc.
		foreach ($order_snapshot as $product_id => $order) {
			$db_product_id = $cart->remove_attr_id($product_id);
			if((int)$db_product_id > 0) { // Will not run this for invalid products & coupons
				$sql = 'INSERT INTO products_order_history SET product_id = '.(int)$db_product_id.', sub_id = "'.@$order['number_of_months'].'", attr_id = "'.@$order['attr_id'].'", paid = '.(double)$order['price'] * $order['qty'].', qty = '.(int)$order['qty'].', order_id = '.(int)$order_id.', date="'.date('Y-m-d H:i:s').'"';
				if(!db_query($sql)) {
					$AI->error('UNABLE to update products_order_history table', __FILE__, array('sql'=>$sql, 'db_error'=>db_error()));
				}
			}
		}
		*/
		$this->set_order($this->order_id);
		return $this->order_id;
	}

	// Runs hooks and returns a boolean on the success of all required parts together
	// @param bool $return_all_success Returns the success after fulfillment instead of after transaction
	public function run_hooks($return_all_success = false)
	{
		$this->olog("run_hooks($return_all_success)");
		$ret = false;
		$validated = $this->validate_hooks();
		if ( !$validated )
		{
			$this->olog("R - validate_hooks() returned false!");
			return false;
		}

		$merchant_id = $this->choose_merchant();
		$this->olog("R - Chose merchant '$merchant_id'");
		if ( $merchant_id !== false )
		{
			$transaction_id = $this->make_transaction($merchant_id);
			$this->olog("R - Made transaction '$transaction_id'");
			if ( $transaction_id !== false )
			{
				if ( !$return_all_success ) // Check if only need success of transaction
				{
					$ret = true;
				}
				$shipping_id = $this->fulfill();
				$this->olog("R - fulfillment ship_id '$shipping_id'");
				if ( $shipping_id !== false )
				{
					$ret = true;
				}

				$scheduled_purchase_id = $this->schedule_pruchases();
				$this->olog("R - scheduled_purchase_id '$scheduled_purchase_id'");
				if ( $scheduled_purchase_id !== false )
				{
					$ret = true;
				}
				$this->olog("R - call finalize_hooks()");
				$this->finalize_hooks(); // Other external hooks to finalize the order
				$this->olog("R - call send_email()");
				$this->send_email();
			}

		}
		return $ret;
	}

	// Closes/finalizes an order
	public function close_order($order_id = '', $allow_conversion = false) { $this->finalize($order_id, $allow_conversion); }

	public function get_snapshot_from_cart($userID)
	{
		global $cart, $AI;
		$cart = new C_cart($userID, true, $this->cart_secondID);

		//CREATE SNAPSHOT (i.e. RECORD CURRENT PRICES FOR ARCHIVES)
		$order_snapshot = array();
		if(method_exists($cart, 'calculate_handling_fee'))
		{
			$cart->calculate_handling_fee();
		}
		$df = new C_dynamic_fields('Title', 'Description', 'Ingredients', 'Language');
		foreach ($cart->contents as $product_id => $cart_data)
		{
			// Strip the sub ID if present
			$db_product_id = $cart->remove_attr_id($product_id);

			if(!$cart_data['is_dynamic']) {
				$product = db_lookup_assoc("SELECT * FROM products WHERE product_id = " . (int)$product_id . " LIMIT 1;");
				$product_details = $df->get_single_row_by_condition($product['description'], 'Language', $AI->get_lang());
			} else {
				$product = $cart_data;
				$product_details = $cart_data;
			}
			// Sync up cart data for finailze_order to use (later if this is an auth only order.)
			// $order_snapshot[$product_id] = $cart_data;

			// Special Price Override main price ~ JosephL 2009.02.23
			if( isset($product['special_price']) && (float)$product['special_price'] > 0 ) {
				$product['price'] = (float)$product['special_price'];
			}

			//Manual Price should be handled in the cart
			// Override product price if manual price is set ~ JosephL 2008.12.7
			//if( $product['include_manual_price'] == 'Yes' && isset($cart_data['man_price']) && $cart_data['man_price'] != '' ) {
			//	$product['price'] = (float)$cart_data['man_price'];
			//}

			if($product['subscription'] == 'Yes')
			{
				$sub_df = new C_dynamic_fields('num_months', 'price');

				$subscription = $sub_df->get_single_row_by_condition($product['subscription_rates'], 'num_months', $cart_data['sub_id']);

				// Recurring payments ignore the delay
				if(isset($cart_data['db_sub_id']) && $cart_data['db_sub_id'] > 0) {
					$product['subscription_delay'] = 0;
				}

				if( (int)$product['subscription_delay'] > 0 ) {
					$delayed_price = $subscription['price'];
					// Price charged was the standard price not the subscription price
					$subscription['price'] = (double)$product['price'];
				}

				$order_snapshot[$product_id] = array
				(	'code' => $product['product_code']
				,	'title' => $product_details['Title']
				,	'qty' => (int)$cart_data['qty']
				,	'price' => (double)$subscription['price']
				,  'number_of_months' => (int)$subscription['num_months']
				,  'sub_id' => $cart_data['sub_id']
				,  'is_dynamic' => false
				);

				// Include infromation for snapshot to save and order prevew to draw so it does not rely on live DB info (incase it changes) ~ JosephL 2009.01.08
				// ' (Subscription'.(((int)$product['subscription_delay'] > 0) ? ' Delay '.(int)$product['subscription_delay'].' Days' : '' ).')' // Include a note that this is a subscription
				if( (int)$product['subscription_delay'] > 0 ) {
					$order_snapshot[$product_id]['subscription_delay'] = $product['subscription_delay'];
				}

				if(isset($delayed_price)) {
					$order_snapshot[$product_id]['subscription_delayed_price'] = $delayed_price;
				}

				$product['price'] = $subscription['price'];
			}
			else
			{
				$order_snapshot[$product_id] = array
				(	'code' => $product['product_code']
				,	'title' => $product_details['Title']
				,	'qty' => (int)$cart_data['qty']
				,	'price' => (double)$product['price']
				,  'is_dynamic' => false
				);
			}

			if(isset($cart_data['attr_id']) && $cart_data['attr_id'] != '') {
				// // This product has an attribute that could possiable affect the price
				// $attr_df = new C_dynamic_fields('Sub ID', 'Name', 'Price');
				// $attr_row = $attr_df->get_single_row_by_condition($product['attributes'], 'Sub ID', $cart_data['attr_id']);
				//
				// // Re-Do the price including attribute's price
				// $order_snapshot[$product_id]['price'] = (double)$product['price'] + (double)$attr_row['Price'];
				// $order_snapshot[$product_id]['attr_name'] = $attr_row['Name'];
				// $order_snapshot[$product_id]['attr_id'] = $attr_row['Sub ID'];
				// $order_snapshot[$product_id]['attr_title'] = $product['attribute_name'];

				// New multiple attributes
				$raw_attrs = @unserialize($product['attributes']);
				$attr_price = 0;
				if(!empty($raw_attrs)) {
					// Process Attributes to be in a $a['group']['key'] = array('name', 'price')
					$attrs = array();

					foreach ($raw_attrs as $n => $v) {
						$attrs[strtolower($v['3'])][$v['0']] = array('price' => (float)$v['2'], 'name' => $v['1']);
					}

					$selected_attrs = explode(',', $cart_data['attr_id']);
					$attr_array = array();
					foreach ($selected_attrs as $k) {
						$tmp = explode('|', $k);
						$group_name = strtolower($tmp['0']);
						$aid = $tmp['1'];

						$attr_array[$group_name] = array('attr_id' => $aid, 'name' =>$attrs[$group_name][$aid]['name'], 'price' => (float)$attrs[$group_name][$aid]['price']);
						$attr_price += (float)$attrs[$group_name][$aid]['price'];
						// echo '<span class="attr">'.($group_name != '' ? ucfirst($group_name) : 'Attributes').': '.htmlspecialchars(ucfirst($attrs[$group_name][$id]['name'])).'</span><br>';
					}


					$order_snapshot[$product_id]['price'] = (double)$product['price'] + (double)$attr_price;
					$order_snapshot[$product_id]['attributes'] = $attr_array; // Save nicely formated multi-attribute array for reference.
				}
			}

			// Use manual price over anything if it is present
			if(isset($cart_data['man_price']) && (double)$cart_data['man_price'] > 0) {
				$order_snapshot[$product_id]['price'] = (double)$cart_data['man_price'];
				$order_snapshot[$product_id]['man_price'] = (double)$cart_data['man_price'];
			}

			// Port any cart data left over from the cart, includes an extra fields added that may not be standard.
			foreach ($cart_data as $key => $value) {
				if( !isset($order_snapshot[$product_id][$key]) ) {
					$order_snapshot[$product_id][$key] = $value;
				}
			}

			// Include an activation fee if present
			if((double)$product['activation_fee'] > 0) {
				$order_snapshot[$product_id.'-fee']['price'] = (double)$product['activation_fee'];
				$order_snapshot[$product_id.'-fee']['title'] = 'Activation Fee';
				$order_snapshot[$product_id.'-fee']['qty'] = $cart_data['qty'];
				$order_snapshot[$product_id.'-fee']['code'] = $product['product_code'].'-fee';
			}
		}

		// Coupon support
		if(!empty($cart->coupon_codes)) {
			// We have a coupon, add it to the orders log
			foreach($cart->coupon_codes as $coupon_code)
			{
				$order_snapshot['coupon'][] = array('price' => $cart->get_coupon_discount(), 'title' => $cart->coupon->get_coupon_title(), 'qty' => 1, 'code' => $coupon_code);
			}
		}

		return $order_snapshot;
	}

	//run transaction from this order
	public function process()
	{
	}

	// If the order has been paid for, then finalize the order and active any subscriptions
	public function finalize($order_id = '', $allow_conversion = false)
	{
		$this->olog("finalize($order_id,$allow_conversion)");
		global $AI, $cart;
		$log = array();

		if($order_id != '')
		{
			$this->set_order($order_id);
		}

		$cart = new C_cart($this->o['userID'], true, $this->cart_secondID); // Only going use the remove_attr_id function

		// Only finalize transaction if the order allows for it
		if($this->o['payment_status'] == 'CAPTURE' || $this->o['payment_status'] == 'AUTH_CAPTURE' || $this->o['payment_status'] == 'OVERRIDE') { // The funds have been offically recived!
			$this->olog("F - Payment OK (".$this->o['payment_status'].")");

			// All checkouts from this source will be new now (if checking out multiple times from the same source, highly unlickley but possible).
			if(isset($_SESSION['non_finalized_checkout'][$this->o['source']])) {
				unset($_SESSION['non_finalized_checkout'][$this->o['source']]);
			}

			$scheduled_purchases = array();
			foreach ($this->o['contents'] as $product_id => $data)
			{
				$contents_product_id = $product_id;
				// Strip the sub ID if present
				$product_id = $cart->remove_attr_id($product_id);

				if($data['is_dynamic'] !== true) { // No Dynamic Subscriptions!
					$product = db_lookup_assoc("SELECT * FROM products WHERE product_id = " . (int)$product_id . " LIMIT 1;");

					// Per Product Access Group Control
					if($product['on_purchase_permission_group'] != '' && $product['on_purchase_permission_group'] != 'Everyone') {
						$AI->grant_access_group_perm( $product['on_purchase_permission_group'], $this->o['userID'], 'user' );
						$log[] = 'Added User To Access Group "'.$product['on_purchase_permission_group'].'"';
					}

					// No More Subscriptions!!!! ~ JosephL 2011.03.02
					// Schedled Products Purchase
					// Check to see if this project triggers the auto purchase of another product @ a future date
					// Only create a new scheduled product purchase IF
					// - This is an inital order AND the product allows for auto creation
					// - This is a recurring order created by a shceduled purchase, always create new schdeduled purchases in this case
					if( (int)$product['scheduled_purchase_delay'] > 0 && ($product['scheduled_purchase_auto_create'] == 'Yes' || (int)$this->o['scheduled_purchase_id'] > 0)) {
						// We have a delay..and it is allowed to be created automatically!
						// Determine what product to purchase... If the purchase product is 0, we will purchase the same product (infinate looping of purchases)
						$product_id = ((int)$product['scheduled_purchase_product'] == 0 ? (int)$product['product_id'] : (int)$product['scheduled_purchase_product']);
						if(defined('AI_FINALIZE_DATE_RUN_TIME_OVERRIDE')) { // For development only.. allows devs to see how the system will schedule if the cron is run on a specific date ~ JosephL 2011.03.03
							$new_bill_date = date('Y-m-d', strtotime('+'.(int)$product['scheduled_purchase_delay'].' days', strtotime(AI_FINALIZE_DATE_RUN_TIME_OVERRIDE)));
						}
						else {
							$new_bill_date = date('Y-m-d', strtotime('+'.(int)$product['scheduled_purchase_delay'].' days'));
						}
						if(!isset($scheduled_purchases[$new_bill_date])) {
							$scheduled_purchases[$new_bill_date] = array();
						}

						$scheduled_purchases[$new_bill_date][] = array('pid' => $product['product_id'], 'qty' => $data['qty']);
					}
				}
			}

			// sort by the key (bill date) so earliest purchaes are first
			/*
			ksort($scheduled_purchases);
			foreach ($scheduled_purchases as $purchase_date => $products) {
				// Does a purchase already exist for this user / billing profile that is a valid payment ( status == 1 )
				$sql = 'SELECT purchase_id FROM scheduled_purchases WHERE user_id = '.(int)$this->o['userID'].' AND billing_profile_id = "'.(int)$this->o['billing_profile_id'].'" AND purchase_date = "'.$purchase_date.'" AND status = 1';
				$existing_id = (int)db_lookup_scalar($sql);
				if($existing_id > 0) {
					$log[] = 'Found existing scheduled purchase #'.$existing_id;
					$scheduled_purchase_id = $existing_id;
				}
				else {
					// create the purchase
					$sql  = 'INSERT INTO scheduled_purchases SET ';
					$sql .= '  purchase_date = "'.$purchase_date.'"';
					$sql .= ', user_id = '.(int)$this->o['userID'];
					$sql .= ', billing_profile_id = '.(int)$this->o['billing_profile_id'];
					$sql .= ', status = 1'; // All valid pending payments are status 1
					// Lookup the user payment # this falls under
					$max_user_purchase_count = (int)db_lookup_scalar('SELECT max(user_purchased_count) FROM scheduled_purchases WHERE user_id = '.(int)$this->o['userID']);
					$sql .= ', user_purchased_count = '.($max_user_purchase_count + 1);
					$sql .= ', date_added = "'.date('Y-m-d H:i:s').'"';
					$sql .= ', date_last_modified = "'.date('Y-m-d H:i:s').'"';
					if((int)$this->o['scheduled_purchase_id'] > 0) {
						// this scheduled purchase is being triggered from the purchase of another scheduled purchase
						$sql .= ', originating_purchase_id = '.(int)$this->o['scheduled_purchase_id'];

						// Determine the sequence # this is in a chain of purchases
						$max_chain_count = (int)db_lookup_scalar('SELECT purchase_chain_count FROM scheduled_purchases WHERE purchase_id = '.(int)$this->o['scheduled_purchase_id']);
						$sql .= ', purchase_chain_count = '.($max_chain_count + 1);
					}

					if( db_query($sql) ) {
						$scheduled_purchase_id = db_insert_id();
						$log[] = 'Created Scheduled Product Purchase #'.$scheduled_purchase_id.' for '.$purchase_date;
					}
					else {
						$log[] = 'ERROR: Scheduled Product Purchase Insert Failed '.db_error();
						util_vardump($log, 'log');die('DIED');
					}
				}

				if($scheduled_purchase_id > 0) {
					// Valid puchase... link any products to this purchase
					foreach ($products as $prod) {
						$sql = 'INSERT INTO scheduled_purchase_products SET purchase_id = '.(int)$scheduled_purchase_id.', product_id = '.(int)$prod['pid'].', qty = '.(int)$prod['qty'].', originating_order = '.$this->order_id.', date_added = "'.date('Y-m-d H:i:s').'"';
						if( db_query($sql) ) {
							$log[] = 'Added Product '.$prod['pid'].'x'.$prod['qty'].' to Purchase '.$scheduled_purchase_id;
						}
						else {
							$log[] = 'ERROR: Addition of  Product '.$prod['pid'].'x'.$prod['qty'].' to Purchase '.$scheduled_purchase_id.' error: '.db_error();;
						}
					}
				}
			}
			*/
		}
		else {
			// A decliend or error status, no payment received so remove from a permisison group
			$this->olog("F - Payment NOT OK (".$this->o['payment_status'].")");
			foreach ($this->o['contents'] as $product_id => $data)
			{
				// Strip the sub ID if present
				$product_id = $cart->remove_attr_id($product_id);

				/*
				if($data['is_dynamic'] !== true) { // No Dynamic Subscriptions!
					$product = db_lookup_assoc("SELECT * FROM products WHERE product_id = " . (int)$product_id . " LIMIT 1;");

					if($product['on_purchase_permission_group'] != '' && $product['on_purchase_permission_group'] != 'Everyone') {
						$AI->deny_access_group_perm( $product['on_purchase_permission_group'], $this->o['userID'], 'user' );
						$log[] = 'Removed User From Access Group "'.$product['on_purchase_permission_group'].'"';
					}

				}
				*/
			}
		}

		// Conversion for any order that has a valid payment (non valid payment does not get a conversion because they did not pay)
		if( $allow_conversion && ( $this->o['payment_status'] ==  'AUTH' || $this->o['payment_status'] == 'AUTH_CAPTURE' || $this->o['payment_status'] == 'OVERRIDE' )) {
			$this->olog("F - Allowing Conversion");
			///////////////////////////////////////////////////////
			//AFFILIATE TRACKING / CAMPAIGNS //////////////////////
			//j0zf 2008.4.19
			// josephl 2008.08.22, added payments for conversions, moved to this location!
			// josephl 2009.02.04, moved again to prevent multiple conversions per order
			//                     added better protection against such a case.

			$trak = new C_affiliate_tracking();
			$camp = new C_campaigns();
			$conv = new C_conversions();
			$fraud_flag = false;
			$qualifier_flag = true;

			// Load billing profile used for this order (to retrieve user info)
			$bp = new C_billing_profiles($this->o['userID']);
			$bp->load($this->o['billing_profile_id']);

			$first_name = $bp->billing_addr['first_name'];
			$last_name = $bp->billing_addr['last_name'];
			$confirmation_email = $bp->billing_addr['email'];

			// make sure a conversion has not already occured for this order, and there has already been an impression
			if($trak->get('conversion_id', 0) > 0 && $trak->get('campaign_code', '') != '') {
				if( $conv->is_conversion_for_order($this->order_id) ) {
					// This order already recived a conversion in the same seaction.. set the fraud flag...
					$fraud_flag = true;
					$this->olog("F - Fraud flag (order already recived a conversion in the same section)");
				}
				else {
					// The initial impression has already been had a conversion, but this is not the same order.
					// Therefore force a new impression so this order will create a valid conversion.  Supports multi-conversions per same tracking
					// (rare case this would happen in live site, mostly for development)
					$this->olog("F - Force new impression");
					$trak->force_new_impression();
				}
			}

			$conversion_id = $conv->insert( $first_name, $last_name, $confirmation_email, $this->order_id, $trak->get('campaign_id', 0), $cart, $trak->get('sub_id', '') );

			//TRACK THE CONVERSION
			$trak->conversion( 0, $conversion_id, $fraud_flag, $qualifier_flag );
			$trak->save();

			if(!isset($_SESSION['fire_tracking_pixel'])) $_SESSION['fire_tracking_pixel'] = array();

			$_SESSION['fire_tracking_pixel'][$this->order_id] = true; // Tells checkout page to fire the tracker pixel for this order
		}

		// Add this log to the order
		if( count($log) ) util_order_add_sys_log($this->order_id, $log);

		// Always sync the payment status and status
		$this->sync_status_using_payment_status($this->order_id);
		return true;
	}

	public function get_preview($order_id = '', $draw_product_checkbox = false)
	{
		if($order_id != '')
		{
			$this->set_order($order_id);
		}

		$col = 0;
		$desc = '<table border="1" cellspacing="0" cellpadding="3" style="width:100%;">';
		$desc.= '<tr style="background-color:#CCCCCC;">';
		if($draw_product_checkbox) {
			$desc .= '<th></th>';
			$col = 1;
		}
		$desc.= '<th> Product Code </th><th> Product </th><th> QTY </th><th>Unit<br>Price</th><th>Total</th></tr>';
		$col += 5;
		$sub_total = 0;

		$all_sub_ids = '';
		$handling_fees = 0;
		foreach ($this->o['contents'] as $pid => $p) {
			$handling_fees += db_lookup_scalar("SELECT handling_fee FROM products WHERE product_id=".$pid);
			$desc.='<tr>';
			if($draw_product_checkbox) {
				// Also include a checkbox with the price + tax.
				$paid_price = (float)$p['price'] * (int)$p['qty'];
				$paid_tax = 0;
				if($tax > 0) {
					// The product is taxable, figure out tax for price paid
					$paid_tax = util_calc_tax($paid_price, $this->o['shipping_addr']['region'], $this->o['shipping_addr']['country'], $this->o['shipping_addr']['city']);
				}
				$pid = str_replace(' ','', $pid); // Cart puts space, does not work nicely with html input names
				$desc.= '<td>';
				$desc.= '<input type="hidden" name="product_'.$pid.'_price" value="'.$paid_price.'" id="product_'.$pid.'_price">';
				$desc.= '<input type="hidden" name="product_'.$pid.'_tax" value="'.$paid_tax.'" id="product_'.$pid.'_tax">';
				$desc.= '<input type="hidden" name="product_'.$pid.'_total" value="'.($paid_price + $paid_tax).'" id="product_'.$pid.'_total">';
				$desc.= '<input type="hidden" name="product_'.$pid.'_name" value="'.$p['title'].'" id="product_'.$pid.'_name">';
				$desc.= '<input type="hidden" name="product_'.$pid.'_aty" value="'.$p['qty'].'" id="product_'.$pid.'_qty">';
				$desc.= '<input type="checkbox" name="product_'.$pid.'" value="product_'.$pid.'" id="product_'.$pid.'"/>';
				if(isset($p['db_sub_id'])) {
					$desc.= '<input type="hidden" name="product_'.$pid.'_subid" value="'.$p['db_sub_id'].'" id="product_'.$pid.'_subid"/>';
					$all_sub_ids = $p['sub_id'].',';
				}
				$desc.= '</td>';
			}
			$desc.='<td>'.( ($p['code'] != '') ? $p['code'] : '' ).'</td>';
			$desc.='<td>'.$p['title'];
			if(isset($p['sub_id']) && $p['sub_id'] != '') {
				// See if the subscription is still active
				$active ='N';
				if(isset($p['db_sub_id'])) {
					$active = db_lookup_scalar('SELECT is_active FROM subscriptions WHERE sub_id = '.db_in($p['db_sub_id']));
				}
				// Use live DB info if its was not saved with the order. (Should always be saved,just backwards compatiable)
				if(!isset($p['subscription_delay'])) {
					$prod = db_lookup_assoc('SELECT subscription_delay, subscription_rates FROM products WHERE product_id = '.$pid);

					$rates = @unserialize($prod['subscription_rates']);
					$rate = 0;
					if(is_array($rates)) {
						foreach ($rates as $row_id => $r) {
							if($r['0'] == $p['sub_id']) {
								$rate = $r['1'];
							}
						}
					}
					$delay = $prod['subscription_delay'];

				}
				else {
					$delay = $p['subscription_delay'];
					$rate = $p['subscription_delayed_price'];
				}

				// Ignore the delay on recurring billing
				if($order['recurring'] == 'Yes') {
					$delay = 0;
				}

				if((int)$delay > 0) {
					// Charge standard amount now
					$desc .= '<br><small>'.(($p['price'] == 0 ) ? 'FREE' : '$'.number_format($p['price'], 2)).' for the first '.(int)$delay.' days then $'.number_format($rate, 2).' Every '.$p['sub_id'].' Month(s).</small>';
				}
				$desc .= '<br><small><b>Subscription</b> '.$p['sub_id'].' Months ('.$p['sub_id'].') - '.(($active == 'Y') ? 'Active [Sub ID '.$p['db_sub_id'].']' : 'Inactive '.((isset($p['db_sub_id']) && $p['db_sub_id'] != '' ? '[Sub Id '.$p['db_sub_id'].']' : ''))).' </small>';
			}

			// Display multiple attributes
			if(isset($p['attributes']) && !empty($p['attributes'])) {
				foreach ($p['attributes'] as $group_name => $info) {
					$desc .= '<br><small><b>'.ucfirst($group_name).'</b> '.ucfirst($info['name']).' ('.$info['attr_id'].')</small>';
				}
			} // Keep support for old single attribute (for old orders in a pre-existing sytem)
			elseif(isset($p['attr_id']) && $p['attr_id'] != '') {
				$desc .= '<br><small><b>Attribute '.$p['attr_title'].'</b> '.$p['attr_name'].' ('.$p['attr_id'].')</small>';
			}

			if(isset($p['is_dynamic']) && $p['is_dynamic']) {
				$desc .= '<br><small><i>Dynamically Generated Product</i></small>';
			}
			if(isset($p['man_price']) && (float)$p['man_price'] > 0) {
				$desc .= '<br><small><i>Manuall Price</i></small>';
			}
			if(isset($p['product_shipping_price']) && (float)$p['product_shipping_price'] > 0) {
				$desc .= '<br><small><b>Shipping Price</b> $'.number_format((float)$p['product_shipping_price'], 2);
				if((int)$p['qty'] > 1) {
					$desc .= ' * '.(int)$p['qty'].' Qty = $'.number_format(((float)$p['product_shipping_price'] * (int)$p['qty']), 2).' Total';
				}
				$desc .= '</small>';
			}
			$desc.= '</td>';
			$desc.='<td align="center">'.number_format((float)$p['qty'], 0).'</td>';
			$desc.='<td align="right">$'.number_format((float)$p['price'],2).'</td>';
			$desc.='<td align="right">$'.number_format((float)$p['price']*(int)$p['qty'], 2).'</td>';
			//$desc.='<b>'.( ($p['code'] != '') ? '('.$p['code'].') ' : '' ).$p['title'].':</b>&nbsp;&nbsp; '.number_format((float)$p['price'], 2).' x '.(int)$p['qty'].' = '.(float)$p['price']*(int)$p['qty'].'<br>';
			$sub_total += (float)$p['price']*(int)$p['qty'];
			$desc.='</tr>';
		}


		//$desc.='<b> Sub Total </b>&nbsp;&nbsp;$'.number_format((float)$total, 2).'<br>';
		$desc.='<tr><td colspan="'.($col-1).'" style="text-align:right;font-weight:bold;">Subtotal</td><td align="right">$'.number_format((float)$sub_total, 2).'</td></tr>';
		//$desc.='<b> Shipping </b>&nbsp;&nbsp;'.db_lookup_scalar('SELECT name FROM shipping_rates_special WHERE rate_id = '.(int)$shipping_id).' $'.number_format((float)$shipping, 2).'<br>';
		$desc.='<tr><td colspan="'.($col-1).'" style="text-align:right;font-weight:bold;">Shipping &amp; Handling'.@db_lookup_scalar('SELECT name FROM shipping_rates_special WHERE rate_id = '.(int)$shipping_id).'</td><td align="right">$'.number_format((float)$shipping, 2).'</td></tr>';
		$desc.='<tr><td colspan="'.($col-1).'" style="text-align:right;font-weight:bold;">Tax </td><td align="right">$'.number_format((float)$tax, 2).'</td></tr>';
		$desc.='<tr><td colspan="'.($col-1).'" style="text-align:right;font-weight:bold;">Total</td><td align="right" style="font-weight:bold;">$'.number_format((float)$total, 2).'</td></tr>';

		//$desc.='<b> Total </b>&nbsp;&nbsp;$'.number_format((float)$total + (float)$shipping, 2).'<br>';
		$desc.= '</table>';

		if($draw_product_checkbox) {
			$desc.='<input type="hidden" name="all_sub_ids" value="'.rtrim($all_sub_ids,',').'" id="all_sub_ids">';
		}
		return $desc;
	}

	// automatically change the status of an order to reflect the payment status automatically.
	// relationship defined by payment_status field in order_status table
	public function sync_status_using_payment_status( $order_id = '' )
	{
		$this->olog("sync_status_using_payment_status($order_id)");

		global $AI;

		if ( $order_id != '' )
		{
			$this->set_order($order_id);
		}

		if ( $this->o['payment_status'] !== '' )
		{
			$new_status = db_lookup_scalar("SELECT status_id FROM order_status WHERE payment_status LIKE '%".serialize($this->o['payment_status'])."%' ORDER BY sort_order LIMIT 1");
			if ( (int) $new_status > 0 && (int) $this->o['status'] != (int) $new_status )
			{
				$sql = "UPDATE orders SET status = " . (int) $new_status . " WHERE order_id = " . (int) $this->order_id . " LIMIT 1";

				if ( !db_query($sql) )
				{
					$AI->error('Order Update Status Change Fail', __FILE__, array('sql' => $sql, 'db_error' => db_error()));
					return false;
				}
				else
				{
					$original_status_name = db_lookup_scalar("SELECT name FROM order_status WHERE status_id = " . (int) $this->o['status']);
					$new_status_name = db_lookup_scalar("SELECT name FROM order_status WHERE status_id = " . (int) $new_status);
					$log = 'Changed order status from "' . $original_status_name . '" to "' . $new_status_name . '" automatically due to payment status "' . serialize($this->o['payment_status']) . '".';
					aimod_run_hook('hook_order_add_sys_log', $order_id, $log);
					return true;
				}
			}
		}
	}

	public function is_status( $status, $strict = false )
	{
		if ( !empty($this->order_id) )
		{
			$order_payment_status = db_lookup_value('orders', 'order_id', (int) $this->order_id, 'payment_status');

			$row = db_lookup_assoc("SELECT status_id, payment_status FROM order_status WHERE name = '" . db_in($status) . "' LIMIT 1;");
			if ( isset($row['status_id']) )
			{
				$payment_status_serialized = $row['payment_status'];
				if ( $payment_status_serialized == '' )
				{
					return !$strict;
				}
				elseif ( false !== ($payment_status = @unserialize($payment_status_serialized)) )
				{
					if ( in_array($order_payment_status, $payment_status) )
					{
						return true;
					}
				}
			}
		}
		return false;
	}

	////////////////////////////////////////////////////////////////
	// HOOKS

	private function validate_hooks()
	{
		$this->olog("validate_hooks() : ".$this->order_id);
		if ( !empty($this->order_id) )
		{
			$results = aimod_run_hook('hook_orders_validate', $this->order_id);
			if ( is_array($results) && count($results) > 0 )
			{
				$ret = true;
				foreach ( $results as $h=>$result )
				{
					if ( $result !== true )
					{
						$this->olog("V - hook result not true ($h : $result)");
						$ret = false;
						if ( is_string($result) )
						{
							$this->warning($result);
						}
					}
				}
				return $ret;
			}
			return true;
		}
		return false;
	}

	private function choose_merchant()
	{
		if ( !empty($this->order_id) )
		{
			$merchant_id=0;


			//LOCK MERCHANT FOR CERTAIN REBILLS (SCHEDULED_PURCHASES)
			if(@$this->o['scheduled_purchase_id']>0){
				$merch = db_lookup_assoc("SELECT lock_rebill, o.merchant_id FROM merchants m, orders o, scheduled_purchases s WHERE m.merchant_id=o.merchant_id AND o.order_id=s.order_id AND s.purchase_id=".intval($this->o['scheduled_purchase_id']));
				if($merch['lock_rebill']=='Yes') {
					db_perform('orders', array('merchant_id' => $merch['merchant_id']), 'update', "order_id = " . (int) $this->order_id);
					return $merch['merchant_id'];
				}
			}

			//SELECT MERCHANT BASED ON OWNERSHIP
			$owner_id = db_lookup_assoc("SELECT owner_id FROM orders WHERE order_id=".intval($this->order_id));
			if ( $owner_id > 0 )
			{
				$hook_ret = aimod_run_hook('hook_orders_choose_merchant_by_owner', $this->order_id);
				$merchant_id = reset($hook_ret);
			}
			//SELECT MERCHANT
			if ( $merchant_id < 1 )
			{
				$hook_ret = aimod_run_hook('hook_orders_choose_merchant', $this->order_id);
				$merchant_id = reset($hook_ret);
			}

			if ( $merchant_id > 0 )
			{
				$this->o['merchant_id'] = $merchant_id;
				return $merchant_id;
			}
			else $this->warning('Could not choose a <a href="merchants">merchant</a>');
		}
		return false;
	}

	private function make_transaction($merchant_id = null)
	{
		$this->olog("make_transaction()");
		if ( !empty($this->order_id) )
		{
			$is_threat = $this->attack_guardian_detect();
			if ( $is_threat )
			{
				$this->warning('Your IP address has been blocked for multiple failed attempts. Please contact administration for support.');
				return false;
			}
			aimod_run_hook('hook_orders_make_transaction', $this->order_id, 'AUTH_CAPTURE', $merchant_id);
			$transaction_id = (int) db_lookup_value('orders', 'order_id', (int) $this->order_id, 'ai_txn_id');
			if ( $transaction_id > 0 || $transaction_id == self::NO_CHARGE_TXN_ID )
			{
				if ( $this->is_status('Received', true) || $this->is_status('Pending', true))
				{
					$this->o['ai_txn_id'] = $transaction_id;
					return $transaction_id;
				}
			}

			$this->olog("T - Could not complete transaction:");
			$this->warning('Could not complete transaction');
			if ( isset($_SESSION['ai_checkout_errors_transaction']) && is_array($_SESSION['ai_checkout_errors_transaction']) )
			{
				$this->olog(print_r($_SESSION['ai_checkout_errors_transaction'],true));
				foreach ( $_SESSION['ai_checkout_errors_transaction'] as $error_msg )
				{
					$this->warning($error_msg);
				}
			}
		}
		return false;
	}

	private function fulfill()
	{
		$this->olog("fulfill()");
		if ( !empty($this->order_id) )
		{
			aimod_run_hook('hook_orders_fulfill', $this->order_id);
			$shipping_id = (int) db_lookup_value('orders', 'order_id', (int) $this->order_id, 'shipping_id');
			$this->o['shipping_id'] = $shipping_id;
			return $shipping_id;
		}
		return false;
	}

	private function schedule_pruchases()
	{
		$this->olog("schedule_pruchases()");
		if ( !empty($this->order_id) )
		{
			aimod_run_hook('hook_orders_schedule_purchases', $this->order_id);
			$scheduled_purchase_id = (int) db_lookup_value('orders', 'order_id', (int) $this->order_id, 'scheduled_purchase_id');
			$this->o['scheduled_purchase_id'] = $scheduled_purchase_id;
			return $scheduled_purchase_id;
		}
		return false;
	}

	private function finalize_hooks()
	{
		$this->olog("finalize_hooks()");
		if ( !empty($this->order_id) )
		{
			aimod_run_hook('hook_orders_finalize', $this->order_id, $this->cart_secondID);
			aimod_run_hook('hook_orders_finalize2', $this->order_id, $this->cart_secondID);
		}
	}

	public function send_email(){
		$this->olog("send_email()");
		global $AI;

		$do_send=true;

		$is_scheduled_purchase = ($this->o['scheduled_purchase_id'] > 0);
		if($is_scheduled_purchase) $do_send = ($AI->get_setting('send_order_email_for_scheduled_purchases')=='Yes');
		else $do_send = ($AI->get_setting('send_order_email')=='Yes');

		$this->olog( 'E - '.($is_scheduled_purchase? "is_scheduled":'is_regular_order').' '.($do_send? "do_send":'no_send') );

		if($do_send) {
			$oe = new C_order_email($this->order_id, $this);
			$oe->send_email();
		}
	}

	public function get_user_verify_hash()
	{
		return hash('crc32b', $this->get('total').'X8*n'.$this->order_id);
	}

	////////////////////////////////////////////////////////////////
	// ATTACK GUARDIAN - Safeguard against multiple requests
	////////////////////////////////////////////////////////////////

	/**
	 * Initializes the Attack Guardaian, typically called in the CONSTRUCTOR
	 */
	private function attack_guardian_init()
	{
		$this->tracked_ip = @$_SERVER['REMOTE_ADDR'] . '';
		if ( $this->tracked_ip == '0.0.0.0' || $this->tracked_ip == '127.0.0.1' || $this->tracked_ip = '68.56.174.170' /* Philip */ ) // shouldn't happen, but treat these as internal (no remote address)
		{
			$this->tracked_ip = '';
		}
		// Settings from Orders module
		$orders_module = aimod_get_module('orders');
		$this->attack_guardian_threshold = $orders_module->attack_guardian_threshold;
		$this->attack_guardian_minute_range = $orders_module->attack_guardian_time_range;
	}

	/**
	 * A PRE-transaction check, returning FALSE as a "safe", TRUE if an attack has been detected
	 * @return bool
	 */
	private function attack_guardian_detect()
	{
		$this->olog("attack_guardian_detect()");
		if ( $this->tracked_ip != '' ) // not shell or cron
		{
			// If this IP has been previously blocked, return TRUE, indicating an attack was detected
			if ( $this->attack_guardian_is_ip_blocked() )
			{
				$this->olog("G - Is Blocked!");
				return true;
			}
			// Otherwise, continue normal algorithm
			else
			{
				// Find the furthest time in the past we should look at
				$lookback_cutoff = strtotime('-' . $this->attack_guardian_minute_range . ' minutes');
				// Retrieve the threshold
				$threshold = $this->attack_guardian_threshold;
				// Get fail order count of current IP within time (lookback) range
				$sql = "
					SELECT COUNT(*)
					FROM orders
					WHERE ip = '" . db_in($this->tracked_ip) . "'
					AND payment_status IN ('ERROR', 'DECLINED')
					AND date_added >= '" . db_in(date('Y-m-d H:i:s', $lookback_cutoff)) . "'
				;";
				$fail_count = (int) db_lookup_scalar($sql);
				if ( $fail_count >= $threshold )
				{
					$this->olog("G - Blocking...");
					$this->attack_guardian_block_ip();
					return true;
				}
			}
		}
		// Attack NOT detected, return FALSE for "safe"
		$this->olog("G - OK");
		return false;
	}

	/**
	 * Blocks and IP
	 * @param string $ip
	 */
	private function attack_guardian_block_ip( $ip = null )
	{
		global $AI;

		if ( $ip === null )
		{
			$ip = $this->tracked_ip;
		}
		if ( $ip != '' ) // not shell or cron
		{
			$sql_now = date('Y-m-d H:i:s'); // in case PHP and MySQL times are different

			//INSERT INTO RED IPLIST
			$rs = db_query("INSERT INTO iplist SET ip='" . db_in($ip) . "'
				, list = 'red'
				, userID = ".intval($AI->user->userID)."
				, date_added = '" . db_in($sql_now) . "'");

			//INSERT INTO orders_ip_blacklist
			/*$sql = "
				INSERT INTO orders_ip_blacklist
				SET ip = '" . db_in($ip) . "'
				, userID = '" . db_in($AI->user->userID) . "'
				, order_id = '" . db_in($this->order_id) . "'
				, pardoned = 0
				, date_modified = '" . db_in($sql_now) . "'
				, date_added = '" . db_in($sql_now) . "'
			;";*/
			return $rs;
		}
		return true;
	}

	/**
	 * Check if an IP is already blocked
	 * @param string $ip
	 * @return bool
	 */
	private function attack_guardian_is_ip_blocked( $ip = null )
	{
		if ( $ip === null )
		{
			$ip = $this->tracked_ip;
		}
		if ( $ip != '' ) // not shell or cron
		{
			$is_blocked=false;
			$rs = db_query("SELECT * FROM iplist WHERE ip = '".db_in($ip)."'");
			while($rs && ($arr=db_fetch_assoc($rs))!==false) {
				if($arr['list']=='white') return false;
				else $is_blocked=true;
			}
			return $is_blocked;
			/*$sql = "
				SELECT id
				FROM orders_ip_blacklist
				WHERE ip = '" . db_in($ip) . "'
				AND pardoned = 0
			;";
			$existing_id = (int) db_lookup_scalar($sql);
			return $existing_id > 0;*/
		}
		return false;
	}

	//PER-INSTANCE ORDER LOG (WRITES TO ORDERS_LOG TABLE)
	public function olog($txt,$bp=null,$up = array())
	{
		global $AI;
		global $order_page_request_str;
		$is_new=($order_page_request_str==null);
		if($order_page_request_str==null) $order_page_request_str=util_rand_string(10);

		if($this->log_id<1) {
			//check for existing log for this order
			if($this->order_id>0) {
				//use existing
				$this->log_arr = db_lookup_assoc("SELECT * FROM orders_log WHERE oid=".intval($this->order_id));
				if(@$this->log_arr['id']>0) $this->log_id = $this->log_arr['id'];
			}
			if($this->log_id<1)
			{
				//INSERT - create new log
				$larr['request_id'] = $order_page_request_str;
				$larr['userID'] = $AI->user->userID;
				$larr['source_name'] = $this->source_name;
				$larr['source_type'] = $this->source_type;
				$post = $_POST;
				if(isset($post['card_cvv'])) $post['card_cvv'] = db_encrypt($post['card_cvv']);
				if(isset($post['card_number'])) $post['card_number'] = db_encrypt($post['card_number']);
				$larr['post'] = db_encrypt(serialize($post));
				$larr['ip'] = $_SERVER['REMOTE_ADDR'];
				$larr['server'] = serialize($_SERVER);
				$larr['oid'] = $this->order_id;
				$larr['logtime'] = date('Y-m-d H:i:s');
				db_perform('orders_log',$larr,'insert');
				$this->log_id = db_insert_id();
				$larr['id']=$this->log_id;
				$this->log_arr=$larr;
			}
		}

		if($AI->user->userID>0 && $this->log_arr['userID']<1) $up['userID']=$AI->user->userID;
		if($this->order_id>0 && $this->log_arr['oid']<1) $up['oid']=$this->order_id;
		if($bp!=null) {
			if(!isset($this->log_arr['bp_id']) || $this->log_arr['bp_id']==0) $up['bp_id']=$bp->get_profile_id();
			if(!isset($this->log_arr['first_name']) || $this->log_arr['first_name']=='') $up['first_name']=$bp->billing_addr['first_name'];
			if(!isset($this->log_arr['last_name']) || $this->log_arr['last_name']=='') $up['last_name']=$bp->billing_addr['last_name'];
			if(!isset($this->log_arr['email']) || $this->log_arr['email']=='') $up['email']=$bp->billing_addr['email'];
		}
		if(count($up)>0) {db_perform('orders_log',$up,'update','id='.intval($this->log_id));}

		if($txt!='') {
			db_query("UPDATE orders_log SET log=CONCAT(log,'\n[".date('H:i:s')."] ".db_in(trim($txt))."') WHERE id=".intval($this->log_id));
		}
	}

};//END C_order Class
