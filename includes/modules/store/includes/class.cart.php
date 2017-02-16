<?php
//j0zf may 2005
// josephL 2008.08.19
//  ** Added save_load_cart option.  Disables saving the cart in the DB / Session.
//     Usefull for pages that created the cart on the fly (like payment cronjob)
//  ** Added support for product attributes
/**
 *Carts are now saved between logins
 *and carts are merged with cart used before login
 *Samuel Larkin 2015.7.21
 */

require_once( ai_cascadepath('includes/plugins/dynamic_fields/class.dynamic_fields.php') );
require_once( ai_cascadepath('includes/modules/campaign_coupons/class.campaign_coupons.php') );
require_once( ai_cascadepath( 'includes/modules/campaigns/class.conversions.php' ) );
require_once( ai_cascadepath( 'includes/modules/campaigns/class.campaigns.php' ) );
require_once( ai_cascadepath( 'includes/modules/campaigns/class.affiliate_tracking.php' ) );

require_once( ai_cascadepath('includes/modules/billing_profiles/class.billing_profile.php') );
require_once( ai_cascadepath('includes/modules/products/includes/class.product.php') );
require_once( ai_cascadepath('includes/modules/coupons/includes/class.te_coupons.php') );

class C_cart
{
	public $contents = array();
	public $billing_addr = array( 'first_name' => '', 'last_name' => '', 'address_line_1' => '', 'address_line_2' => '', 'city' => '', 'region' => '', 'country' => '', 'postal_code' => '', 'email' => '', 'phone' => '' );
	public $shipping_addr = array( 'first_name' => '', 'last_name' => '', 'address_line_1' => '', 'address_line_2' => '', 'city' => '', 'region' => '', 'country' => '', 'postal_code' => '', 'address_key' => '', 'ship_phone' => '' );
	public $ship_instructions = '';
	public $shipping_rate = 0.0;
	public $comments = '';
	public $userID = 0;
	public $secondID = '';
	public $lang = '';
	public $save_load_cart = true;
	public $coupon = null;
	public $coupon_codes = array();
	public $coupon_price_log = array();
	public $invalid_coupon = false;
	public $disable_alt_pricing = false;

	public $SESSION_PREFIX = 'ai_cart_';

	public function __construct( $userID, $save_load_cart = true, $secondID = '', $title = '' )
	{
		$this->userID = $userID;
		$this->save_load_cart = $save_load_cart;
		$this->secondID = strtolower(trim($secondID . ''));
		$title = preg_replace('/[^-0-9a-zA-Z_ ]/', '', $title);
		$this->title = trim($title . '');

		if( !is_numeric( $this->userID) ){ $this->userID = 0; }

		if ( $this->secondID != '' )
		{
			$this->SESSION_PREFIX .= '[' . $secondID . ']_';
		}

		if ( $this->title != '' )
		{
			$this->SESSION_PREFIX .= '[' . $title . ']_';
		}
		if($this->save_load_cart) {
			if( isset($_SESSION[ $this->SESSION_PREFIX . 'contents' ]) && $this->title == '')
			{
				//LOAD CART FROM SESSION
				//notice this will affect $this->userID
				$this->_session_load_cart();

				//debugger echo 'LOAD CART FROM SESSION!! ';

				//DECTECT IF THEY JUST LOGGED IN....  IF SO THEN MERGE THE CART'S CONTENTS
				if( $userID != $this->userID )
				{
					//debugger echo ' CART MERGED!! ';
					$this->userID = $userID;
					//$this->_db_merge_cart();
					$this->save();
				}

			}
			else
			{
				//debugger echo 'LOAD CART FROM DB!! ';

				//LOAD CART FROM DATABASE
				$this->_db_load_cart();

				//SAVE CART INTO SESSION
				$this->_session_save_cart();
			}
		}
		//$this->_alter_titles_for_new_language();
		$this->_coupon_cleanup();

		$bp = new C_billing_profiles($userID);
	}

	/* CHECK FOR ADDPROD OR SETPROD PARAMATERS
	 *  USED ON PAGES 'shopping-cart' AND 'checkout'
	 *  Similar logic can be found in class.landing_pages.php
	 *  DrewL 2016.04.29
	 */
	public function get_contents_from_url_params(){

		//
		/*Accepted formats for pid & qty
		  PID Array & QTY:
				<input type="checkbox" name="pid[]" value="123">  <input_or_select name="qty_123" value="1">
				<input type="checkbox" name="pid[]" value="124">  <input_or_select name="qty_124" value="2">
			PID CSV: 123,124
			PID CSV w/ QTY: 123,124x2
			PID CSV w/ POST QTY field:  123,124  $_POST['QTY_124']=2 (<input OR select typy="text" name="qty_124" value="2">)
		*/
		if ( ($setprod=util_REQUEST('setprod'))!==null || ($addprod=util_REQUEST('addprod'))!==null )
		{
			$pidlist = ($setprod!='')? $setprod:$addprod;
			//CLEAR THE CART FIRST?
			if($setprod!='') {
				$this->remove_all();
			}

			//ADD THE PRODUCT(S) TO THE CART
			$added_pids=array();
			if(!is_array($pidlist)) $pids = explode('_',trim($pidlist));
			foreach($pids as $pid) {
				$qty = util_REQUEST('qty')!=''? util_REQUEST('qty'):1;
				if(preg_match('/^([0-9]+)([xX\*])([0-9]+)/',$pid,$m) ) {
					$pid = $m[1];
					$qty = $m[3];
				}
				$pid=intval($pid);
				if($pid<1) continue;
				if(util_REQUEST('qty_'.$pid)!='') $qty = intval($_POST['qty_'.$pid]);
				if($qty<1) continue;

				//SETUP CUSTOM PRICE?
				//maybe later... :)
				$man_price='';
				$man_title='';

				$added_pids[]=$pid;
				$this->add_item($pid, $qty, $sub_id='', $attr_id='', $man_price, array(), $man_title);
			}
			$this->save();
		}
	}

	//////////////////////////////////////////////////
	// PUBLIC METHODS

	//Samuel Larkin 2015.7.21
	//called by hook_login
	public function on_login()
	{
		$this->_session_load_cart();
		$ses_contents = $this->contents;
		$this->_db_load_cart();
		if(is_array($ses_contents))
		{
			foreach($ses_contents as $stock_id => $value)
			{
				if(isset($this->contents[$stock_id]) && !empty($this->contents[$stock_id]))continue;
				$this->contents[$stock_id] = $value;
			}
		}

		$this->save_load_cart = true;
		$this->save();
	}

	public function add_item( $id, $qty, $sub_id = '', $attr_id = '', $man_price = '', $extra = array(), $man_title='' )
	{

		//IF ITEM IS ALREADY IN THE CART => INCREMENT THE VALUE & DON'T RESET THE PRICE :)
		/*
			if( isset( $this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ] ) )
			{
			$qty += $this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ]['qty'];
			}
			*/
		if ( isset($this->contents[$id]) )
		{
			$qty += $this->contents[$id]['qty'];
		}
		$this->update_item( $id, $qty, $sub_id, $attr_id, $man_price, $extra, $man_title );
	}

	public function update_item( $id, $qty , $sub_id = '', $attr_id = '', $man_price = '', $extra = array(), $man_title='')
	{
		$this->_update_item( $id, $qty, $sub_id, $attr_id, $man_price, $extra, $man_title );

		// Also add any items using automatic triggers here
		/*
			$sql = 'SELECT triggers FROM products WHERE product_id = '.(int)db_in($id);
			$triggers = db_lookup_scalar($sql);
			$triggers = @unserialize($triggers);
			if(is_array($triggers) && count($triggers)>0) {
			foreach ($triggers as $row_id => $t) {
			// $t['0'] = db_product_id
			// $t['1'] = qty
			// $t['2'] = attribute_id
			// $t['3'] = subscription_id
			if(((int)$t['0']) > 0) {
			$this->_update_item( trim($t['0']), trim($t['1']), trim($t['3']), trim($t['2']), '', $extra );
			}
			}
			}
			*/

		$this->save();
	}

	public function update_qty( $id, $qty, $attr_id = '' )
	{
		//YOU MUST CALL save() AFTER DONE WITH BATCH
		$this->_update_item($id, $qty, '', $attr_id, '', array());
	}

	public function get_qty( $id, $attr_id = '' )
	{
		if ( isset($this->contents[$id]['qty']) )
		{
			return (int) $this->contents[$id]['qty'];
		}
		return false;
	}

	public function set_billing_addr(  $first_name, $last_name, $address_line_1, $address_line_2, $city, $region, $country, $postal_code, $email, $phone )
	{
		$this->billing_addr['first_name'] = $first_name;
		$this->billing_addr['last_name'] = $last_name;
		$this->billing_addr['address_line_1'] = $address_line_1;
		$this->billing_addr['address_line_2'] = $address_line_2;
		$this->billing_addr['city'] = $city;
		$this->billing_addr['region'] = $region;
		$this->billing_addr['country'] = $country;
		$this->billing_addr['postal_code'] = $postal_code;
		$this->billing_addr['email'] = $email;
		$this->billing_addr['phone'] = $phone;

		$this->save();
	}

	public function set_shipping_addr(  $first_name, $last_name, $address_line_1, $address_line_2, $city, $region, $country, $postal_code, $address_key, $phone='' )
	{
		$this->shipping_addr['first_name'] = $first_name;
		$this->shipping_addr['last_name'] = $last_name;
		$this->shipping_addr['address_line_1'] = $address_line_1;
		$this->shipping_addr['address_line_2'] = $address_line_2;
		$this->shipping_addr['city'] = $city;
		$this->shipping_addr['region'] = $region;
		$this->shipping_addr['country'] = $country;
		$this->shipping_addr['postal_code'] = $postal_code;
		$this->shipping_addr['address_key'] = $address_key;
		$this->shipping_addr['ship_phone'] = $phone;

		$this->save();
	}

	public function set_ship_instructions( $ship_instructions )
	{
		$this->ship_instructions = $ship_instructions;
		$this->save();
	}

	public function set_shipping_rate( $shipping_rate )
	{
		$this->shipping_rate = (double)$shipping_rate;
		$this->save();
	}

	public function set_comments( $comments )
	{
		$this->comments = $comments;
		$this->save();
	}

	public function save()
	{
		if($this->save_load_cart) {
			$this->_session_save_cart();
			$this->_db_save_cart();
		}
	}

	public function remove_item( $id, $attr_id = '' )
	{
		//unset( $this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ] );
		unset($this->contents[$id]);

		// Remove any triggered items as well
		$sql = 'SELECT triggers FROM products WHERE product_id = '.(int)db_in($id);
		$triggers = db_lookup_scalar($sql);
		$triggers = @unserialize($triggers);
		if(is_array($triggers) && count($triggers)>0) {
			foreach ($triggers as $row_id => $t) {
				// $t['0'] = db_product_id
				// $t['1'] = qty
				// $t['2'] = attribute_id
				// $t['3'] = subscription_id
				$id = trim($t['0']);
				$attr_id = trim($t['2']);

				//unset( $this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ] );
				unset($this->contents[$id]);
			}
		}


		$this->save();
	}

	public function remove_all()
	{
		unset( $this->contents );
		$this->contents = array();
		$this->coupon_codes = array();
		$this->coupon = null;
		$this->shipping_rate = 0;
		$this->disable_alt_pricing = false;
		$this->save();
	}

	/* DrewL 150518 - get all products and their attributes */
	public function get_contents_with_attributes($dbwhere='') {
		$contents=array();
		foreach ($this->contents as $id => $cart_item) {
			$dbarr = db_lookup_assoc("SELECT p.*, ps.* FROM products p, product_stock_items ps WHERE ps.product_id=p.product_id ".($dbwhere!=''? "AND ($dbwhere)":"")." AND ps.stock_item_id=".intval($id));
			if(is_array($dbarr)) {
				$contents[$id]=$cart_item;
				$contents[$id]['db']=$dbarr;
			}
		}
		return $contents;
	}


	/**
	 * Add together the cost over ever product in the cart, as well as any subscription fees on the products
	 */
	public function get_sub_total()
	{
		$total = 0;

		foreach( $this->contents as $id => $v ) {
			$total += $this->get_product_price($id, $v);
		}

		$total += $this->get_total_activation_fee();
		return $total;
	}
	/* DrewL 150508 - function to obtain subtotal of products (only those without free_shipping) */
	//same as above but doesn't include free_ship products
	public function get_ship_summary()
	{
		$inf['subtotal']=0.00;
		$inf['ship_subtotal']=0.00;
		$inf['cnt']=0;
		$inf['ship_cnt_free']=0;
		$inf['ship_cnt_paid']=0;
		$inf['items']=array();
		$inf['ship_items']=array();
		$inf['ship_weight']=0.0;

		$total = 0;
		$cart_prods_w_attr = $this->get_contents_with_attributes();
		foreach( $cart_prods_w_attr as $id => $parr ) {
			$p_db = $parr['db'];
			$product = C_product::get_new_product_from_stock($id);
			$weight = (float)$product->get_stock($id)->get_weight();
			$qty = (int)$parr['qty'];

			$prod_total = $this->get_product_price($id, $parr);
			$inf['items'][$id]=$parr;
			$inf['cnt']++;
			if(@$p_db['free_ship']==1) {
				$inf['ship_cnt_free']++;
				$inf['subtotal']+=$prod_total;
			}
			else {
				$inf['ship_cnt_paid']++;
				$inf['ship_subtotal']+=$prod_total;
				$inf['ship_items'][$id]=$parr;
				$inf['ship_weight'] += ($weight * $qty);
			}
		}

		$inf['subtotal'] += $this->get_total_activation_fee();
		return $inf;
	}

	/**
	 * Get the price of a product, while taking into account
	 * @param: $id the raw card ID (with attr ID embeeded)
	 * @param: $contents, the raw array created by the cart that keeps track of
	 *                    products attrs, subscriptions & manual price
	 */
	public function get_product_price($id, $contents = array())
	{
		if ( isset($contents['man_price']) && 0 < (double) $contents['man_price'] )
		{
			return (double) $contents['man_price'] * (int) $contents['qty'];
		}
		$stock = C_product::get_new_stock($id);
		if ( !is_object($stock) )
		{
			echo "\nStock is not an object! Invalid stock ID in function get_product_price(PSID)!\n";
			return 0.0; // If we allow $stock->get_price(), we will get a fatal error, so just return 0.0 (but the bad object should not be tolerable!) -JonJon 2014-12-05 09:08:36 -1000
		}
		return $stock->get_price($this->disable_alt_pricing) * (int) $contents['qty'];
	}
	public function get_product_price_v1($id, $contents = array())
	{
		$p = db_lookup_assoc("SELECT subscription_rates, subscription_delay, price, attributes, special_price FROM products WHERE product_id = " . (int)$id . " LIMIT 1");
		$rates = unserialize($p['subscription_rates']);
		$delay = (int)$p['subscription_delay'];



		$id = $this->remove_attr_id($id);
		$attr_price = 0;

		if(!$contents['is_dynamic']) {
			if($contents == array()){ $contents = array('attr_id' => '', 'qty' => 1, 'sub_id' => '');  };

			if($contents['attr_id'] != '') {
				// // We have an attribute that could possiable reflect the price
				// $attrs = db_lookup_scalar("SELECT attributes FROM products WHERE product_id = " . (int)$id . " LIMIT 1" );
				// if($attrs != '') {
				// 	$attrs = unserialize($attrs);
				//
				// 	foreach ($attrs as $row_num => $row) {
				// 		if($row['0'] == $contents['attr_id']) {
				// 			$attr_price = (double)$row['2'];
				// 		}
				// 	}
				// }

				// Multiple Attribute Support
				$raw_attrs = @unserialize($p['attributes']);

				if(!empty($raw_attrs)) {
					$attrs = array();

					foreach ($raw_attrs as $n => $v) {
						$attrs[strtolower($v['3'])][$v['0']] = array('price' => (float)$v['2'], 'name' => $v['1']);
					}

					$clean_attrs = array();
					$selected_attrs = explode(',', $contents['attr_id']);

					foreach ($selected_attrs as $k) {
						$tmp = explode('|', $k);
						$group_name = strtolower($tmp['0']);
						$aid = @$tmp['1'];
						if(isset($attrs[$group_name]))
						{
							$attr_price += (float)$attrs[$group_name][$aid]['price'];
						}
						else //try replacing underscores with spaces
						{
							$group_name = strtolower(str_replace('_', ' ', $tmp['0']));
							$attr_price += (float)@$attrs[$group_name][$aid]['price'];
						}
					}
				}
			}

			if( isset($contents['man_price']) && (float)$contents['man_price'] > 0 ) {
				// use the manual price instead of any other prices
				// $price = '( '.(float)$contents['man_price'].' ) * '.(int)$contents['qty'];
				// $sql .= $price;
				// No need to use the DB to lookup the price, manual price
				return ( ((double)$contents['man_price'] + (double)$attr_price ) * (int)$contents['qty']);
			} elseif($contents['sub_id'] == '') {
				// Not a subscription

				if($p['special_price'] > 0) {
					$p['price'] = $p['special_price'];
				}

				$price = ( $p['price'] + $attr_price ) * (int)$contents['qty'];
			} else {
				// we have a subscription.. find the price from the serialized for

				// Subscription payment ignore the delay
				if(isset($contents['db_sub_id']) && $contents['db_sub_id'] > 0) {
					$delay = 0;
				}

				if($delay > 0 ) {
					// use standard price @ checkout because of delay
					return (double)$p['price'];
				} else {
					foreach ($rates as $num => $rate) {
						if($rate['0'] == $contents['sub_id']) {
							// $price = '('.$rate['1'].' + '.$attr_price.')*'.$contents['qty'];
							return ((double)$rate['1'] + (double)$attr_price ) * (int)$contents['qty'];
						}
					}
				}
			}

			return $price;
		}
		else {
			// This is a dynamic product
			return (double)$contents['price'];
		}
	}

	public function get_coupon_discount()
	{
		$discount = 0;

		if(!empty($this->coupon_codes))
		{
			// a coupon was added and passed the valid test.
			// this might be calling either the "coupons" module or the "campaign_coupons" module,
			// depending on what was loaded into $this->coupon in add_coupon().
			$subtotal = $this->get_sub_total();

			//sort the coupon types
			$product_coupons = array();
			$subtotal_coupons = array();
			$this->coupon->set_disable_alt_pricing($this->disable_alt_pricing);
			$this->coupon->set_cart_contents($this->contents);
			foreach($this->coupon_codes as $coupon_code)
			{
				$this->coupon->load($coupon_code);
				$this->coupon_price_log[$coupon_code]['type'] = $this->coupon->get_coupon_use_type();
				if($this->coupon_price_log[$coupon_code]['type'] == 'product')
				{
					$product_coupons[] = $coupon_code;
				}
				else
				{
					$subtotal_coupons[] = $coupon_code;
				}
			}
			//product coupons must be calculated first
			foreach($product_coupons as $coupon_code)
			{
				$this->coupon->load($coupon_code);
				$this->coupon_price_log[$coupon_code]['amount'] = $this->coupon->calculate_discount($subtotal);
				$discount += $this->coupon_price_log[$coupon_code]['amount'];
			}
			//subtract the product discounts from the subtotal, then use that new subtotal for any global coupons
			$subtotal -= $discount;
			foreach($subtotal_coupons as $coupon_code)
			{
				$this->coupon->load($coupon_code);
				$this->coupon_price_log[$coupon_code]['amount'] = $this->coupon->calculate_discount($subtotal);
				$discount += $this->coupon_price_log[$coupon_code]['amount'];
			}
		}

		return $discount * -1; // needs to be a negative, given as a positive
	}

	/**
	 * total price of everything (shipping, total, tax & discount)
	 */
	public function get_total()
	{
		return $this->shipping_rate + $this->get_sub_total() + $this->get_tax() + $this->get_coupon_discount();
	}

	/**
	 * returns the pure DB ID from a unique content id
	 */
	public function remove_attr_id($id)
	{
		// Parse out attribute id if it is prsent
		if(preg_match('/[aid].*[\/aid]/', $id)) {
			$id = substr($id, 0, strpos($id, '[aid]'));
		}
		return trim($id);
	}

	/**
	 * returns the attribute ID form a unique content id
	 */
	public function get_attr_from_id($id)
	{
		// Parse out attribute id if it is prsent
		if(preg_match('/[aid].*[\/aid]/', $id, $matches)) {
			// $id = substr($id, 0, strpos($id, '[aid]'));
			return $matches['0'];
		}
		return false;
	}

	public function get_activation_fee($id)
	{
		return false; // Not yet implemented in backbone -JonJon 2012-04-04 08:43:03 -1000
		/*
			$id = $this->remove_attr_id($id);

			$fee = db_lookup_scalar('SELECT activation_fee FROM products WHERE product_id = '.$id.' LIMIT 1');
			if($fee == '0')
			{
			return false;
			}
			else
			{
			return $fee;
			}
			*/
	}

	public function get_total_activation_fee()
	{
		$total = 0;
		foreach( $this->contents as $id => $v )
		{
			$fee = $this->get_activation_fee($id);
			if($fee)
			{
				$total += $fee * $v['qty'];
			}
		}
		return $total;
	}

	public function get_num_items()
	{
		$nItems = 0;
		if(!is_array($this->contents))return 0;
		foreach( $this->contents as $n => $v )
		{
			$nItems += (int)$v['qty'];
		}

		return $nItems;
	}
	public function count() { return $this->get_num_items(); }
	public function has_contents() { return $this->get_num_items() > 0; }

	/**
	 * returns a %, the total amount discounted based off the sub total
	 *
	 */
	public function get_discount_percent()
	{
		if(!$this->has_coupon()) {
			return 0;
		}
		$sub_total = $this->get_sub_total();

		return $this->coupon->get_discount_percent($sub_total);
	}

	/**
	 * returns the total tax for all taxable items in the cart
	 * TODO: Record tax rate id when creating an order, and record the exact shipping module that the tax was derived from
	 */
public function get_tax($bp=null)
	{
		if($bp!=null && @$bp->shipping_addr['region']!='') {
			$city = strtolower( trim( $bp->shipping_addr['city'] ) );
			$region = $bp->shipping_addr['region'];
			$country = util_abbreviate($bp->shipping_addr['country']);
		}
		else {
			$city = strtolower( trim( $this->shipping_addr['city'] ) );
			$region = $this->shipping_addr['region'];
			$country = util_abbreviate($this->shipping_addr['country']);
		}


		//Adding functionality to do lookup on city by zipcode will default to current city if none are found jason 5/18/2015 START
		if($bp!=null && @$bp->shipping_addr['region']!='') {
			$zip_city = db_lookup_scalar("SELECT city FROM zips WHERE zip = '".db_in($bp->shipping_addr['postal_code'])."' AND country = '".db_in($country)."' LIMIT 1");
		}
		else{
			$zip_city = db_lookup_scalar("SELECT city FROM zips WHERE zip = '".db_in($this->shipping_addr['postal_code'])."'  AND country = '".db_in($country)."' LIMIT 1");
		}

		if($zip_city !==false)
		{
			$city = $zip_city;
		}

		//Adding functionality to do lookup on city by zipcode will default to current city if none are found jason 5/18/2015 END
		$tax_total = false;
		$module_tax = 0;
		$tax_array = aimod_run_hook('hook_calculate_taxes', $this, $bp);
		foreach($tax_array AS $n=>$v)
		{
			if(is_numeric($v))
			{
				if($tax_total == false)
				{
					$tax_total = $v;
				}
				else
				{
					$tax_total += $v;
				}
			}
		}

		if($tax_total==false){
			//ALSO UPDATE SIMILAR LOGIC IN QUICKBOOKS/FUNCTIONS.PHP

			$tax_row = C_cart::get_tax_row($country,$region,$city);
			if ($tax_row)
			{
				if ($tax_row['type'] == 'Percentage')
				{
					// Get the taxable total for the entire cart
					$taxable_total = 0;
					foreach ($this->contents as $id => $cont) {
						$db_id = $this->remove_attr_id($id);
						//Tax Statuses  1 - Retail, 2 - Exempt
						$tax_statuses = array('Retail'=>'1', 'Exempt'=>'2'); //When Creating database do not have primary key as an auto increment value for tax statuses
						if(db_lookup_scalar('SELECT taxable FROM products p, product_stock_items ps WHERE ps.tax_status<>'.(int)$tax_statuses["Exempt"].' AND ps.product_id=p.product_id AND ps.stock_item_id= '.(int)$db_id) == 'Yes') {
							$taxable_total += $this->get_product_price($id, $cont);
						}
					}
					if($tax_row['tax_shipping']=='Yes')
					{
						$taxable_total += $this->shipping_rate;
					}

					//START taxes_zip
					// if we have a table taxes zip and we can get a perfect zip match for taxes we use that tax percentage instead
					if($bp!=null && @$bp->shipping_addr['region']!='') {
							$zip_percent = db_lookup_scalar("SELECT tax_percent FROM taxes_zip WHERE country='".db_in($country)."' AND zip='".db_in($bp->shipping_addr['postal_code'])."' ");
					}
					else
					{
						$zip_percent = db_lookup_scalar("SELECT tax_percent FROM taxes_zip WHERE country='".db_in($country)."' AND zip='".db_in($this->shipping_addr['postal_code'])."' ");
					}

					if($zip_percent !==false && $zip_percent != '')
					{
						return (double)$zip_percent * (double)$taxable_total;
					}

					//END taxes Zip Lookup
					return (double)$tax_row['amount'] / 100.00 * (double)$taxable_total;

				}
				elseif ($tax_row['type'] == 'Fixed')
				{
					return (double)$tax_row['amount'];
				}
				return 0.0;
			}

			return 0.0;
		}

		return $tax_total;
	}
	public function get_tax_row($country,$region,$city){
		$city  = strtolower( trim( $city ) );
		if($country=='') $country='US';
		$tax_row = db_lookup_assoc("SELECT * FROM taxes WHERE country='".db_in($country)."' AND region = '" . db_in($region) . "' AND city = '" . db_in($city) . "' LIMIT 1;");
		if(!$tax_row) $tax_row = db_lookup_assoc("SELECT * FROM taxes WHERE country='".db_in($country)."' AND region = '" . db_in($region) . "' AND city = '' LIMIT 1;");
		if(!$tax_row) $tax_row = db_lookup_assoc("SELECT * FROM taxes WHERE country='".db_in($country)."' AND region = '' AND city='' LIMIT 1;");
		return $tax_row;
	}

	public function change_language($lang = '')
	{
		global $AI;
		if ($lang == '') { $lang = $AI->get_setting('default_language'); }
		$this->_alter_titles_for_new_language($lang);
	}

	public function use_billing_profile($profile_id)
	{
		global $AI;
		$profile = $AI->db->GetAll('SELECT billing_addr, shipping_addr FROM billing_profiles WHERE id = '.(int)$profile_id.' LIMIT 1');
		$profile = $profile['0'];

		$bp_id = $profile_id;
		$key = $profile_id.AI_SITE_BLURB_KEY;
		$this->shipping_addr = unserialize(db_out(db_decrypt($profile['shipping_addr'],$key)));
		$this->billing_addr = unserialize(db_out(db_decrypt($profile['billing_addr'],$key)));
	}

	public function get_total_weight()
	{
		$weight = 0;
		foreach ($this->contents as $product_id => $cdata)
		{
			$product_id = $this->remove_attr_id($product_id);

			$db_product = db_lookup_assoc("SELECT * FROM products WHERE product_id = " . (int)db_in($product_id) . " LIMIT 1;");
			$weight += (double)($db_product['weight'] * (int)$cdata['qty']);
		}

		return $weight;
	}

	public function add_coupon($code)
	{
		if(empty($code) || in_array($code, $this->coupon_codes))
		{
			//coupon already registered
			//but do not exit function here because $this->coupon may still need to be initialized, ie. called from _initilize_coupon()
		}
		else
		{
			$this->coupon_codes[] = $code;
		}

		//main coupon module section
		// if given coupon code is found in the "coupons" table, then it will override any
		// identical coupon codes in the campaign system
		if(util_mod_enabled('coupons'))
		{
			$this->coupon = new C_te_coupons();
			$this->coupon->set_cart_contents($this->contents);
			foreach($this->coupon_codes as $i => $coupon_code)
			{
				if(!$this->coupon->is_valid($coupon_code)) //is_valid() also populates the C_te_coupons object's db array
				{
					unset($this->coupon_codes[$i]); //remove if not valid
				}
			}

			if(empty($this->coupon_codes))
			{
				//if no valid coupons found, reset coupon object so that we will look in the campaign coupons system
				$this->coupon_codes = array();
				$this->coupon = NULL;
				$this->invalid_coupon = true; // does not save, only for reference when adding the coupon
			}

			$this->save();
		}
		//campaign_coupons section
		if(util_mod_enabled('campaign_coupons') && empty($this->coupon))
		{
			$this->coupon = new C_campaign_coupons($code);
			if(!$this->coupon->is_valid_coupon()) {
				// Not a valid coupon
				$this->coupon_codes = array();
				$this->coupon = null;
				$this->invalid_coupon = true; // does not save, only for reference when adding the coupon
			}

			$this->save();

			// if the coupon is tied to a campaign, make an impression
			if( (int)$this->coupon->campaign_id > 0 && $this->coupon->campaign_code.'' != '' )
			{
				$campaign_code = $this->coupon->campaign_code;
				$sub_id = 'c-'.reset($this->coupon->coupon_codes); //reset() returns the first element in an array

				$trak = new C_affiliate_tracking();
				// Do not add this impression if their is alreay tracking occuring
				if($trak->get('campaign_code', '') != '') {
					$trak->impression( 0, 0, trim( @$campaign_code.'' ), trim( @$sub_id.'' ) );
					$trak->save();
				}
			}
		}
	}

	//can be called after get_coupon_discount() to get details on each coupon's contributions
	public function get_coupon_price_log()
	{
		return $this->coupon_price_log;
	}

	/**
	 * Add a coupon assigned to a scheduled purchase
	 * @param  int $purchase_id The key of the schedule purchase row
	 * @return bool             Success in finding a coupon, NULL if the coupons module is not enabled
	 */
	public function add_purchase_coupon( $purchase_id )
	{
		// Check that we can work with coupons
		if ( !util_mod_enabled('coupons') )
		{
			return null;
		}

		// Assume invalid until proven valid
		$this->coupon = new C_te_coupons();
		$this->coupon_codes = null;
		$this->invalid_coupon = true;

		// Find the last inserted coupon for the purchase_id
		$res = $this->coupon->select_rows_where("use_purchase_id = " . (int) $purchase_id);
		$highest_key = 0;
		while ( $res && $row = db_fetch_assoc($res) )
		{
			$row = array_map('db_out', $row);
			$key = (int) $row[$this->coupon->_keyFieldName];
			if ( $highest_key < $key )
			{
				$highest_key = $key;
			}
		}

		// Finding a valid key, make the class members reflect that
		if ( $highest_key > 0 )
		{
			$this->coupon->select($highest_key);
			$this->coupon_codes[] = $this->coupon->db['code'];
			$this->invalid_coupon = false;

		}

		// Finalize
		$this->save();
		return !$this->invalid_coupon;
	}

	public function has_coupon()
	{
		return (empty($this->coupon_codes) ? false : true);
	}


	public function _coupon_cleanup()
	{
		$found = false;
		$coupon_code_in_post = false;
		foreach($_POST AS $n=>$v)
		{
			if( preg_match('/coupon_code/', $n))
			{
				$coupon_code_in_post = true;
				if($v!= '')
				{
					$found = true;
				}
			}

		}

		if(!$found && $coupon_code_in_post )
		{
			$this->coupon_codes = array();
			$this->coupon = null;
		}
	}

	/**
	 * checks to see if the entered price is valid, if not, change it to the highest or lowest (no real errors system in the cart as of now)
	 * return true if price is OK
	 */
	public function is_valid_man_price($man_price, $pid)
	{
		return true;
		$sql = 'SELECT manual_price_range FROM products WHERE product_id = '.db_in($pid).' LIMIT 1';
		$range = db_lookup_scalar($sql);
		$range = explode('-', $range);
		$min = $range['0'];
		$max = $range['1'];

		if($max >= $man_price && $man_price >= $min) {
			return true;
		}

		if($man_price < $min) {
			return $min;
		} else {
			// it must be larger than the max
			return $max;
		}
	}


	// DYNAMIC PRODUCTS
	public function add_dynamic_product($price, $qty, $code, $title, $commission_group = 0)
	{
		$dynamic_count = 1;
		foreach ($this->contents as $id => $value) {
			if(preg_match('/^dynamic_product_/', $id)){
				$dynamic_count++;
			}
		}

		$this->contents['dynamic_product_'.$dynamic_count] = array(
				'is_dynamic'=>true,
				'qty' => (int)$qty,
				'sub_id'=>'',
				'attr_id'=>'',
				'man_price'=>0,
				'product_code'=>$code, // Matches DB products code
				'Title'=>$title, // Matched DB products table to get title
				'price'=>(double)$price,
				'activation_fee'=>0,
				'subscription' => 'No',
				'include_attributes' => 'No',
				'product_id' => 0,
				'commission_group' => $commission_group
		);
	}

	/**
	 * Calculate the flat shipping price based on the contents.
	 *
	 * @param:$type_id, optional shipping type row ID (from shipping_rates_special table).
	 */
	public function calculate_flat_shipping_rate($type_id = 'default', $set_rate = true)
	{
		$calculated_price = 0;

		foreach ($this->contents as $pid => $d) {
			$db_pid = $this->remove_attr_id($pid);
			$shipping_rates = db_lookup_scalar('SELECT shipping_prices FROM products WHERE product_id = '.$db_pid);
			$shipping_rates = @unserialize($shipping_rates);

			$product_shipping = 0;
			if(is_array($shipping_rates)) {
				if(!isset($shipping_rates[$type_id]) || (float)$shipping_rates[$type_id] <= 0) {
					// use the default price (if available)
					if(isset($shipping_rates['default']) && (float)$shipping_rates['default'] > 0) {
						$product_shipping = (float)$shipping_rates['default'];
					}
				}
				else {
					$product_shipping = (float)$shipping_rates[$type_id];
				}
			}

			// Add calculate note to cart (do not multiple by quantity)
			$this->contents[$pid]['product_shipping_price'] = $product_shipping;
			$calculated_price += $product_shipping * (int)$d['qty'];
		}

		if($set_rate) {
			$this->shipping_rate = $calculated_price;
		}

		return $calculated_price;
	}

	/**
	 * Ported form natures euphoria to the core
	 */
	public function calculate_weight_based_shipping_rate()
	{
		$calculated_weight = 0;

		foreach ($this->contents as $pid => $d){
			$db_pid = $this->remove_attr_id($pid);
			$weight = db_lookup_scalar("SELECT weight FROM products WHERE product_id=".$db_pid);
			if($weight != 0)
			{
				$calculated_weight += $weight * $d['qty'];

			}
			else
			{
				return false;
			}
		}

		$sql_rate="SELECT * FROM shipping_rates WHERE   weight_min <= ".$calculated_weight." AND  weight_max  >= ".$calculated_weight."";
		$request_rate = db_query($sql_rate);
		if(db_num_rows($request_rate) >0)
		{
			$rate_info = db_fetch_assoc($request_rate);
			$this->shipping_rate = (double)$rate_info['rate'];

		}
		else
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks if any of the cart contents have a product triggered upon its purchase
	 * @return bool
	 */
	public function has_product_triggers()
	{
		$product_triggers = $this->get_product_triggers();
		return count($product_triggers) > 0;
	}

	/**
	 * Returns all cart contents that have a product triggered upon its purchase
	 * @return array
	 */
	public function get_product_triggers()
	{
		global $AI;

		$stock_ids = array_keys($this->contents);
		if ( !isset($stock_ids[0]) )
		{
			return array();
		}

		$sql = "
				SELECT stock_id
				FROM scheduled_purchase_triggers
				WHERE stock_id IN (" . implode(',', array_map('intval', $stock_ids)) . ")
				AND triggered_stock_id > 0
				ORDER BY id ASC
			;";
		$ret = $AI->db->GetAll($sql, 'stock_id');
		return empty($ret) ? array() : $ret;
	}

	//////////////////////////////////////////////////
	// PRIVATE METHODS

	protected function _session_save_cart()
	{
		global $AI;
		$_SESSION[ $this->SESSION_PREFIX . 'contents' ] = serialize( $this->contents );
		$_SESSION[ $this->SESSION_PREFIX . 'billing_addr' ] = serialize( $this->billing_addr );
		$_SESSION[ $this->SESSION_PREFIX . 'shipping_addr' ] = serialize( $this->shipping_addr );
		$_SESSION[ $this->SESSION_PREFIX . 'ship_instructions' ] = $this->ship_instructions;
		$_SESSION[ $this->SESSION_PREFIX . 'shipping_rate' ] = $this->shipping_rate;
		$_SESSION[ $this->SESSION_PREFIX . 'comments' ] = $this->comments;
		$_SESSION[ $this->SESSION_PREFIX . 'userID' ] = $this->userID;
		$_SESSION[ $this->SESSION_PREFIX . 'lang' ] = $AI->get_lang();
		$_SESSION[ $this->SESSION_PREFIX . 'coupon_codes' ] = serialize( $this->coupon_codes );
		$_SESSION[ $this->SESSION_PREFIX . 'disable_alt_pricing' ] = $this->disable_alt_pricing;
	}

	protected function _session_load_cart()
	{
		$session_contents = ( isset($_SESSION[ $this->SESSION_PREFIX . 'contents' ]) ? unserialize($_SESSION[ $this->SESSION_PREFIX . 'contents' ]) : array() );
		$this->contents = ( is_array($session_contents) ? $session_contents : array() );

		$this->billing_addr = @unserialize( $_SESSION[ $this->SESSION_PREFIX . 'billing_addr' ] );
		$this->shipping_addr = @unserialize( $_SESSION[ $this->SESSION_PREFIX . 'shipping_addr' ] );
		$this->ship_instructions = @$_SESSION[ $this->SESSION_PREFIX . 'ship_instructions' ];
		$this->shipping_rate = @$_SESSION[ $this->SESSION_PREFIX . 'shipping_rate' ];
		$this->comments = @$_SESSION[ $this->SESSION_PREFIX . 'comments' ];
		if(isset($_SESSION[ $this->SESSION_PREFIX . 'userID' ]) && intval($_SESSION[ $this->SESSION_PREFIX . 'userID' ]) != 0) $this->userID = @$_SESSION[ $this->SESSION_PREFIX . 'userID' ];
		$this->lang = @$_SESSION[ $this->SESSION_PREFIX . 'lang' ];
		$this->coupon_codes = unserialize( @$_SESSION[ $this->SESSION_PREFIX . 'coupon_codes' ] );
		$this->disable_alt_pricing = @$_SESSION[ $this->SESSION_PREFIX . 'disable_alt_pricing' ];
		@$this->_check_contents_exist();
		$this->_initilize_coupon();
	}

	protected function _db_save_cart()
	{
		if( $this->userID > 0 )
		{
			$sql = 	"
					UPDATE cart
					SET contents = '" . db_in( serialize( $this->contents ) ) . "'
					, billing_addr = '" . db_in( serialize( $this->billing_addr ) ) . "'
					, shipping_addr = '" . db_in( serialize( $this->shipping_addr ) ) . "'
					, ship_instructions = '" . db_in( $this->ship_instructions ) . "'
					, coupon_code = '" . db_in( serialize( $this->coupon_codes ) ) . "'
					, comments = '" . db_in( $this->comments ) . "'
					WHERE userID = " . (int)db_in( $this->userID ) . "
					AND secondID = '" . db_in( $this->secondID ) . "'
					LIMIT 1;
				;";
			$rs = db_query($sql);
			if(!$rs){ echo '<!--MySql Error: ' . db_error() . ' -->'; }
		}
	}

	protected function _db_load_cart()
	{


		if( $this->userID > 0 )
		{

			$cartID = $this->_db_get_cartID();

			if( $cartID < 1 )
			{
				$cartID = $this->_db_new_cart();
			}
			if( $cartID > 0 )
			{
				$rs = db_query("SELECT * FROM cart WHERE cartID = " . $cartID . " ORDER BY cartID;");

				if($rs)
				{
					$row = db_fetch_assoc( $rs );
					if( $row )
					{
						$this->contents = unserialize( db_out( $row['contents'] ) );
						$this->billing_addr = unserialize( db_out( $row['billing_addr'] ) );
						$this->shipping_addr = unserialize( db_out( $row['shipping_addr'] ) );
						$this->ship_instructions = db_out( $row['ship_instructions'] );
						$this->shipping_rate = 0.0;
						$this->comments = db_out( $row['comments'] );
						$this->coupon_codes = unserialize( db_out( $row['coupon_code'] ) );
						$this->_check_contents_exist();
						$this->_initilize_coupon();
					}
				}
				else { echo '<!--MySql Error: ' . db_error() . ' -->'; }
			}
		}
	}

	protected function _check_contents_exist() {
		if(!empty($this->contents))
		{
			foreach ( $this->contents as $stock_id => $cart_data )
			{

				$product = C_product::get_new_product_from_stock($stock_id);


				$stock = $product->get_stock($stock_id);
				if(!is_object($stock))
				{
				 $this->remove_item($stock_id);

				 continue;
				}

			}
		}
	}

	private function _db_merge_cart()
	{
		//SIMILAR TO _db_load_cart()
		//this is used for situations where you let the user add items to their cart
		//without being logged in... then when they login, merge the cart with the
		//cart that's in the database.
		if( $this->userID > 0 )
		{
			$db_contents = array();

			$cartID = $this->_db_get_cartID();
			if( $cartID < 1 )
			{
				$cartID = $this->_db_new_cart();
			}

			if( $cartID > 0 )
			{
				$rs = db_query("SELECT contents, billing_addr, shipping_addr, ship_instructions, comments FROM cart WHERE cartID = " . $cartID . " ORDER BY cartID;");

				if($rs)
				{
					$row = db_fetch_assoc( $rs );
					if( $row )
					{
						$db_contents = unserialize( db_out( $row['contents'] ) );

						$this->contents = array_merge( $db_contents, $this->contents);

						$this->billing_addr = unserialize( db_out( $row['billing_addr'] ) );
						$this->shipping_addr = unserialize( db_out( $row['shipping_addr'] ) );

						$this->ship_instructions = db_out( $row['ship_instructions'] );
						$this->shipping_rate = 0.0;
						$this->comments = db_out( $row['comments'] );

					}
				}
				else {echo '<!--MySql Error: ' . db_error() . ' -->';}
			}
		}
	}

	protected function _db_new_cart()
	{
		$cartID = 0;
		if( $this->userID > 0 )
		{
			$rs = db_query("
					INSERT INTO cart
					SET userID = " . (int)db_in( $this->userID ) . "
						, secondID = '" . db_in( $this->secondID ) . "'
						, contents = '" . db_in( serialize( $this->contents ) ) . "'
						, billing_addr = '" . db_in( serialize( $this->billing_addr ) ) . "'
						, shipping_addr = '" . db_in( serialize( $this->shipping_addr ) ) . "'
						, ship_instructions = '" . db_in( $this->ship_instructions ) . "'
						, comments = '" . db_in( $this->comments ) . "'
						, time_created = '".date('Y-m-d H:i:s')."'
						, title = '" . db_in( $this->title ) . "'
				;");

			if($rs)
			{
				$cartID = db_insert_id();
			}
			else { echo '<!--MySql Error: ' . db_error() . ' -->'; }
		}

		return $cartID;
	}

	protected function _db_get_cartID()
	{
		$cartID = 0;

		if( $this->userID > 0 )
		{
			$rs = db_query("SELECT cartID FROM cart WHERE userID = " . (int)db_in( $this->userID ) . " AND secondID = '" . db_in($this->secondID) . "' AND  title = '".db_in($this->title)."' ORDER BY cartID;");

			if($rs)
			{
				$row = db_fetch_assoc( $rs );
				if($row)
				{
					$cartID = $row['cartID'];
				}
			}
			else { echo '<!--MySql Error: ' . db_error() . ' -->'; }
		}

		return $cartID;
	}

	protected function _update_item( $id, $qty, $sub_id, $attr_id, $man_price, $extra, $man_title='' )
	{

		// Look up limit_qty in the DB
		$product_id = (int) db_lookup_value('product_stock_items', 'stock_item_id', (int) $id, 'product_id');
		$limit_qty = (int) db_lookup_value('products', 'product_id', (int) $product_id, 'limit_qty');

		$current_stock = PHP_INT_MAX;
		$stock = db_lookup_assoc("
				SELECT psi.stock, psi.track_stock_level
				FROM product_stock_items AS psi
				JOIN products AS p ON psi.product_id = p.product_id
				WHERE psi.stock_item_id = " . (int) $id . "
				LIMIT 1
			;");


		if ( !isset($stock['stock']) ) // Database row is missing
		{
			$current_stock = 0;
		}
		elseif ( $stock['track_stock_level'] == 'Y' )
		{
			$current_stock = (int) $stock['stock'];
		}

		if((double)$man_price > 0) {
			// check to make sure this is a valid price
			$tmp_price = $this->is_valid_man_price($man_price, $id);
			if($tmp_price !== true) {
				$man_price = $tmp_price;
			}
		}
		if ( $limit_qty == 0 )
		{
			if( !is_numeric($qty) ){ $qty = 0; }
			if ( $qty > $current_stock )
			{
				$qty = $current_stock;
			}

			if( $qty <= 0 )
			{
				$this->remove_item( $id, $attr_id );
			}
			else
			{
				//$this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ] = array(
				$this->contents[$id] = array(
						 'is_dynamic'=>false,
						 'qty' => $qty,
						 'sub_id' => $sub_id,
						 'attr_id' => $attr_id,
						 'man_price' => (double)$man_price
				);
			}
		}
		else
		{
			if( !is_numeric($qty) ){ $qty = 0; }


			if($limit_qty < $qty)
			{
				$qty = $limit_qty;
			}
			if ( $qty > $current_stock )
			{
				$qty = $current_stock;
			}
			if( $qty <= 0 )
			{
				$this->remove_item( $id, $attr_id );
			}
			else
			{
				//$this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ] = array(
				$this->contents[$id] = array(
						 'is_dynamic'=>false,
						 'qty' => $qty,
						 'sub_id' => $sub_id,
						 'attr_id' => $attr_id,
						 'man_price' => (double)$man_price
				);
			}
		}

		//manual title?
		if($man_title!='') $this->contents[$id]['title']=$man_title;

		// Append extra data
		if(is_array($extra) && count($extra) > 0) {
			foreach ($extra as $key => $value) {
				// DO NOT OVERRIDE ANYTHING SET BY THE CART!!!!
				/*if(!isset($this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ][$key])) {
				 $this->contents[ $id.' '.($attr_id != '' ? '[aid]'.$attr_id.'[/aid]' : '') ][$key] = $value;
					}*/
				if(!isset($this->contents[$id][$key])) {
					$this->contents[$id][$key] = $value;
				}
			}
		}
	}

	private function _alter_titles_for_new_language($current_lang = '')
	{
		global $AI;

		if ($current_lang == '')
		{
			$current_lang = $AI->get_lang();
		}
		if ($this->lang == $current_lang)
		{
			return;
		}
		$this->lang = $current_lang;

		foreach ($this->contents as $product_id => $content)
		{
			preg_match('/^([0-9])+_([0-9])+$/', $product_id, $ids);
			$real_product_id = $ids[1];
			$attribute_id = $ids[2];

			$product_row = db_lookup_assoc("SELECT description, product_code FROM products WHERE product_id = " . (int)$real_product_id . " LIMIT 1;");
			$prod_dynafields = new C_dynamic_fields('Title', 'Description', 'Language');
			$product_title = $prod_dynafields->get_single_value_by_condition($product_row['description'], 'Title', 'Language', $this->lang);

			if ((int)$attribute_id > 0)
			{
				$attrb_desc = db_lookup_scalar("SELECT description FROM product_attributes WHERE attribute_id = " . (int)$attribute_id . " LIMIT 1;");
				$attrb_dynafields = new C_dynamic_fields('Subtitle', 'Language');
				$attribute_title = $attrb_dynafields->get_single_value_by_condition($attrb_desc, 'Subtitle', 'Language', $this->lang);
				$product_title .= ' (' . $attribute_title . ')';
			}

			$this->contents[$product_id]['desc'] = $product_row['product_code'] . ' : ' . $product_title;
		}
	}

	protected function _initilize_coupon()
	{
		if(!empty($this->coupon_codes))
		{
			foreach($this->coupon_codes as $coupon_code)
			{
				$this->add_coupon($coupon_code); //will also initialize $this->coupon
			}
		}
	}

	/* cart_errors()
	 This function will either:
	 - return a primary cart error
	 - return per-stock_item cart errors (array)
	 - return true
		*/
	function get_cart_errors() {
		//currently, this is only used by Doba module
		if(util_mod_enabled('doba')) {
			require_once(ai_cascadepath('includes/modules/doba/class.doba.php'));
			$D = new C_doba();
			return $D->check_cart_inventory($this);
			//$D->orderLookup($cart,'745 Hutchinson','American Falls','ID','83211','US');
			//die('end');
		}

		return true;
	}

}
?>
