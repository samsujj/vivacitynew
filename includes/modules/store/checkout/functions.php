<?php

require_once ai_cascadepath('includes/modules/billing_profiles/class.billing_profile.php');
require_once ai_cascadepath('includes/modules/products/includes/class.product.php');
require_once ai_cascadepath('includes/modules/store/shipping/functions.php');
require_once ai_cascadepath('includes/modules/coupons/includes/class.te_coupons.php');

aimod_load_module('store'); // Sets some important constants

////////////////////////////////////////////////////////////////
// CHECKOUT BASICS BEGIN
////////////////////////////////////////////////////////////////

function get_country_select($fieldname, $affected_region_fieldname, $value = '', $default_value = 'US')
{
	global $AI;

	$ret = '';

	$value = trim($value);

	static $countries = array();
	if ( !isset($countries[0]) )
	{
		$countries = $AI->db->GetAll("
			SELECT DISTINCT country, countryAbv AS abbreviation
			FROM zones
			" . ($AI->get_setting('limit_countries') . '' != '' ? "WHERE countryAbv IN(" . $AI->get_setting('limit_countries') . ")" : '') . "
			ORDER BY country ASC
		;");
	}

	if ( $value == '' )
	{
		$value = $default_value;
	}
	else
	{
		$value = util_abbreviate($value, 'country');
	}

	$ret .= '<span id="' . h($fieldname) . '_wrapper">';
	$ret .= '<select id="' . h($fieldname) . '" class="country_select" name="' . h($fieldname) . '" rel="' . h($affected_region_fieldname) . '">';
	foreach ( $countries as $row )
	{
		$ret .= '<option value="' . h($row['abbreviation']) . '"';
		if ( $row['abbreviation'] == $value )
		{
			$ret .= ' selected="selected"';
		}
		$ret .= '>' . h($row['country']) . '</option>';
	}
	$ret .= '</select>';
	$ret .= '</span>';

	return $ret;
}

function get_region_input($country_fieldname, $country_value, $fieldname, $value = '', $default_country_value = 'US')
{
	global $AI;

	$ret = '';

	$country_value = trim($country_value);
	$value = trim($value);

	if ( $country_value == '' )
	{
		$country_value = $default_country_value;
	}
	else
	{
		$country_value = util_abbreviate($country_value, 'country');
	}

	if ( $value != '' )
	{
		$value = util_abbreviate($value, 'region');
	}

	static $regions = array();
	if ( !isset($regions[$country_value]) )
	{
		$_regions = $AI->db->GetAll("
			SELECT DISTINCT regionAbv, regionName
			FROM zones
			WHERE countryAbv = '" . db_in($country_value) . "'
			ORDER BY regionName ASC
		;");
		$regions[$country_value] = $_regions;
	}

	if ( isset($regions[$country_value][0]) )
	{
		$ret .= '<span id="' . h($fieldname) . '_wrapper">';
		$ret .= '<select id="' . h($fieldname) . '" class="span6 region_select" name="' . h($fieldname) . '" rel="' . h($country_fieldname) . '">';
		foreach ( $regions[$country_value] as $row )
		{
			$ret .= '<option value="' . h($row['regionAbv']) . '"';
			if ( $row['regionAbv'] == $value )
			{
				$ret .= ' selected="selected"';
			}
			$ret .= '>' . h($row['regionName']) . '</option>';
		}
		$ret .= '</select>';
		$ret .= '</span>';
	}
	else
	{
		echo '<p>Could not detect regions for country.</p>';
	}

	return $ret;
}

function get_cc_radio($fieldname, $value = '')
{
	global $AI;

	$ret = '';

	$value = trim($value);

	$sql = "
		SELECT *
		FROM credit_cards
		WHERE visibility = 'Show'
		ORDER BY sort_order ASC, card_id ASC
	;";
	$res = db_query($sql);
	if ( $res )
	{
		$numrows = db_num_rows($res);
		if ( 0 < $numrows )
		{
			$ret .= '<span id="' . $fieldname . '_wrapper" class="">';
			$cci = 0;
			$has_visible_payment_option = (int)db_lookup_scalar("SELECT 1 FROM credit_cards WHERE visibility = 'Show' AND value = '".db_in($value)."' ORDER BY sort_order ASC, card_id ASC");
			while ( $res && $row = db_fetch_assoc($res) )
			{

				$row = array_map('db_out', $row);
				$ret .= '<input type="radio" id="' . h($fieldname) . '_' . h($row['card_id']) . '" class="card_type_radio" name="' . h($fieldname) . '" value="' . h($row['value']) . '" data-type="' . h($row['type']) . '"';

				if ( $row['value'] == $value || (($value=='' || !$has_visible_payment_option) && $cci == 0) )
				{
					$ret .= ' checked="checked"';
				}

				$ret .= ' /><img src="images/blank.gif" class="payment-icon ' . h($row['value']) . '" alt="' . h($row['name']) . '" />&emsp;';

				$cci++;
				if ($cci > 0 && ($cci % 2 == 0) ) {
					$ret .= '<span class="split_cc"></span>';
				}

			}

			if(file_exists( ai_cascadepath(dirname(__FILE__).'/additional.cc_options.php') ))
			{
				include( ai_cascadepath(dirname(__FILE__).'/additional.cc_options.php') );
			}

			$ret .= '</span>';
		}
		else
		{
			$ret = 'Not accepting credit cards at this time.';
		}
	}
	else
	{
		$ret = 'Credit Card Module Not Enabled.';
		$err = array
			( 'sql' => $sql
			, 'err' => db_error()
			);
		$AI->silent_error('Cannot SELECT credit_cards', __FILE__, $err);
	}

	return $ret;
}

function get_cc_expire_input($fieldname_month, $fieldname_year, $month = null, $year = null)
{
	$ret = '';

	// Month
	$ret .= '<select id="' . h($fieldname_month) . '" class="span5" name="' . h($fieldname_month) . '">';
	for ( $i = 1; $i < 13; $i++ )
	{
		$_time = mktime(0, 0, 0, $i, 1);
		$ret .= '<option value="' . h(date('m', $_time)) . '"';
		if ( $i == (int) $month )
		{
			$ret .= ' selected="selected"';
		}
		$ret .= '>' . h(date('m - F', $_time)) . '</option>';
	}
	$ret .= '</select>';

	$ret .= ' / ';

	// Year
	$ret .= '<select id="' . h($fieldname_year) . '" class="span3" name="' . h($fieldname_year) . '">';
	$current_year = (int) date('Y');
	$max_year = $current_year + 10;
	for ( $i = $current_year; $i <= $max_year; $i++ )
	{
		$ret .= '<option value="' . (int) $i . '"';
		if ( $i == $year )
		{
			$ret .= ' selected="selected"';
		}
		$ret .= '>' . (int) $i . '</option>';
	}
	$ret .= '</select>';

	return $ret;
}

function get_review_purchase($country = '', $region = '', $city = '', $rate=false, $secondID = '', $coupon = '')
{
	global $AI, $cart;

	$ret = '';
	$checkout_userID = get_checkout_userID();

	if ( empty($secondID) && null !== ($iso = util_GET('iso')) )
	{
		$secondID = $iso;
	}
	if($secondID == 'undefined'){ $secondID = ''; } //fixes error where js sometimes sets this to the string "undefined"

	$cart = new C_cart($checkout_userID, true, $secondID);

	if(!is_array($country) && $country!='')
	{
		$cart->shipping_addr['country'] = $country;
	}

	//set these values so that get_tax() is performed correctly
	if(is_array($country))
	{
		$cart->shipping_addr['city'] = $country['city'];
		$cart->shipping_addr['region'] = $country['region'];
		$cart->shipping_addr['country'] = $country['country'];
		$cart->shipping_addr['address_line_1'] = $country['address_line_1'];
		$cart->shipping_addr['address_line_2'] = $country['address_line_2'];
		$cart->shipping_addr['postal_code'] = $country['postal_code'];

		// ~dustinh @ 5/30/2014
		// $country is used later on as a scalar, cannot be an array
		$region = $country['region'];
		$city = $country['city'];
		$country = $country['country'];
	}
	else if ($country != '' && $city != '' && $region != '' )
	{
		$cart->shipping_addr['city'] = $city;
		$cart->shipping_addr['region'] = $region;
		$cart->shipping_addr['country'] = $country;
	}
	else if ( $country == '' || $city == '' ) {
		$country = ($country != '' ? $country : $cart->shipping_addr['country']);
		$city = ($city != '' ? $city : $cart->shipping_addr['city']);
		$region = ($region != '' ? $region : $cart->shipping_addr['region']);

		$cart->shipping_addr['city'] = $country;
		$cart->shipping_addr['region'] = $region;
		$cart->shipping_addr['country'] = $country;

	}

	$shipping_module = null;
	shipping_instantiate_module($shipping_module);
	if(method_exists($shipping_module,'get_shipping_rate')) $cart->set_shipping_rate($shipping_module->get_shipping_rate($cart));

	$badship=false;
	if(util_mod_enabled('doba') && $rate===false) $badship=true;
	if($rate!=false) $cart->set_shipping_rate($rate);

	$stock_errors=array();

	$ret .= '<table id="cart_content_table" class="cart_content_table">';
	$running_total = 0.0;
	foreach ( $cart->contents as $stock_id => $cart_data )
	{
		$product = C_product::get_new_product_from_stock($stock_id);
		$title = $product->get_title();
		$stock = $product->get_stock($stock_id);
		$attributes = $stock->get_attribute_string();
		$unit_price = $stock->get_price($cart->disable_alt_pricing);
		$quantity = (int) $cart_data['qty'];
		$subtotal = $unit_price * $quantity;
		$running_total += $subtotal;

		$ret .= '<tbody class="cart_item_tbody">';
		$ret .= '<tr id="cart_item_' . (int) $stock_id . '" class="cart_item">';
		$ret .= '<td id="cart_title_' . (int) $stock_id . '" class="cart_title alone"><span class="cart_li_title">' . t($title) . '</span>' . ($attributes != '' ? ' <small class="cart_li_attr">' . h($attributes) . '</small>' : '') . ' <span class="cart_li_qty">(' . (int) $quantity . ')</span>'.(isset($stock_errors[$stock_id])? '<span class="stock_error">'.$stock_errors[$stock_id].'</span>':'').'</td>';
		$ret .= '<td id="cart_subtotal_' . (int) $stock_id . '" class="cart_subtotal">$' . h(number_format($subtotal, 2)) . '</td>';
		$ret .= '</tr>';
		$ret .= '</tbody>';
	}
	$ret .= '<tr class="cart_divider"><td colspan="2"><hr /></td></tr>';

	//coupon
	$cart->coupon_codes = array();
	if(!empty($coupon))
	{
		$codes = explode('|', $coupon);
		$ret .= '<tr class="cart_coupon"><td>Coupon'.(count($codes) > 1 ?'s':'');
		foreach($codes as $code)
		{
			//check to see if it's valid
			if(empty($code)){ continue; }
			$te_coupons = new C_te_coupons();
			$te_coupons->set_cart_contents($cart->contents);
			if($te_coupons->is_valid($code))
			{
				$cart->add_coupon($code); //needed by get_coupon_discount()
				$ret .= '<div class="review_coupon_code">'.h($code).'</div><div class="review_coupon_description">'.h($te_coupons->db['description']).'</div>';
			}
		}
		$ret .= '</td>';

		$coupon_discount = abs($cart->get_coupon_discount()); //need this in positive value form
		//update running total
		$running_total -= $coupon_discount;
		$ret .= '<td id="cart_coupon" class="cart_running_total">-$' . h(number_format($coupon_discount, 2)) . '</td></tr>';
	}

	$ret .= '<tr class="cart_subtotal"><td>'.t('Subtotal').'</td><td id="cart_subtotal" class="cart_running_total">$' . h(number_format($running_total, 2)) . '</td></tr>';
	if(!$badship) $shipping_rate = $cart->shipping_rate;
	if(!$badship) $running_total += $shipping_rate;

	$ret .= '<tr class="cart_shipping"><td>'.t('Shipping').'</td><td id="cart_shipping" class="cart_running_total">$' . ($badship? '&nbsp;&nbsp;&nbsp;&nbsp;??.??':number_format($shipping_rate, 2)) . '</td></tr>';
	$tax = $cart->get_tax();
	$running_total += $tax;
	if($tax > 0) {$ret .= '<tr class="cart_sales_tax"><td>'.t('Sales Tax').'</td><td id="cart_sales_tax" class="cart_running_total">$' . h(number_format($tax, 2)) . '</td></tr>';}
	$ret .= '<tr class="cart_total"><td>'.t('Total').'</td><td id="cart_running_total" class="cart_running_total">$' . ($badship? '&nbsp;&nbsp;&nbsp;&nbsp;??.??':number_format($running_total, 2)) . '</td></tr>';
	$ret .= '</table>';

	$ret.='<input type="hidden" name="form_amount" id="form_amount" value="'.(int)number_format($running_total, 2).'" />';

	return $ret;
}

function get_shipping_options($billarr,$shiparr, C_cart $cart = null)
{
	//$country = $billarr['country'];
	//$region = $billad['region'];
	global $AI;
	$ret = '';

	$checkout_userID = get_checkout_userID();

	if($cart === null)
	{
		$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL));
	}



	if(!is_array($billarr))
	{
		$cart->shipping_addr['country'] = $billarr;
	}

	if(!is_array($shiparr))
	{
		$cart->shipping_addr['region'] = $shiparr;
	}
	else
	{
		$cart->shipping_addr['country'] = $shiparr['county'];
		$cart->shipping_addr['region'] = $billarr['region'];
	}

	$total_weight = 0.0;

	$shipping_module = null;
	shipping_instantiate_module($shipping_module);

	/////////////////////////////////////////////////////////////////////////////////////////////
	// If a auto_redirect method exists, then this module calculates the price, sets it the cart
	// and should not need to display a whole form, but should output something friendly
	if( method_exists($shipping_module, 'auto_redirect') )
	{
		//echo 'Automatically Calculated';
	}
	else
	{
		@ob_start();
		$shipping_module->draw_form(@$cart->shipping_addr['country'], @$cart->shipping_addr['region'], $cart);
		$ret .= @ob_get_contents();
		@ob_end_clean();

		$AI->skin->js_onload('$("#shipping_options_wrapper select").change();');
		$AI->skin->js_onload('var shipping=document.getElementById("cart_shipping");
		var cost=shipping.innerHTML;
		if(cost=="$0.00")
		{
			$(".checkout_shipping_options").hide();
		}');
	}



	return $ret;
}

function get_billing_profile_options( $creating_account )
{
	global $AI;

	$ret = '';

	$checkout_userID = get_checkout_userID();

	// Booleans
	$creating_account = (bool) $creating_account;
	$logged_in        = (bool) $AI->user->isLoggedIn();
	$has_profile      = false;
	$has_triggers     = false;

	// Has existing billing profile
	if ( $logged_in )
	{
		$bp = new C_billing_profiles($checkout_userID, false, '');
		if ( $bp->get_profile_id() > 0 )
		{
			$has_profile = true;
		}
	}

	// Cart has product triggers
	$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL));
	$has_triggers = $cart->has_product_triggers();

	// Only present options for logged in users or potential users
	// Based on triggers and existence of profile, options and/or text will differ
	if ( $logged_in || $creating_account )
	{
		if ( !$has_triggers && !$has_profile )
		{
			$ret .= '<input type="checkbox" name="billing_profile_make_primary" value="1">&nbsp;';
			$ret .= tt('Save this information for future checkouts.');
		}
		elseif ( !$has_triggers && $has_profile )
		{
			$ret .= '<input type="checkbox" name="billing_profile_make_primary" value="1">&nbsp;';
			$ret .= tt('Update saved profile with this information for future checkouts.');
		}
		elseif ( $has_triggers && !$has_profile )
		{
			$ret .= '<input type="hidden" name="billing_profile_make_primary" value="1">';
			$ret .= '<strong><em>'.tt('This information will be saved and used for future automated purchases.').'</em></strong>';
		}
		elseif ( $has_triggers && $has_profile )
		{
			$ret .= '<input type="checkbox" name="billing_profile_make_primary" value="1" checked="checked">&nbsp;';
			$ret .= tt('Update saved profile with this information for future checkouts.');
		}

		$mod_ret = aimod_run_hook('hook_get_billing_profile_options');

		foreach($mod_ret AS $n=>$v)
		{
			$ret .= $v;
		}
	}
	return $ret;
}

/**
 * Samuel Larkin 2015.8.3
 * added support for session coupons
 */
function get_coupon_input( $coupon_code = '', $init_single = false )
{
	global $AI;
	$ret = '';
	$next_index = 1;

	$te_coupons = new C_te_coupons();
	$session_codes = $te_coupons->get_session_coupons();

	$codes = explode('|', $coupon_code);
	if($session_codes !== false)
	{
		foreach($session_codes AS $ses_code)
		{
			if(!in_array($ses_code,$codes))$codes[] = $ses_code;
		}
	}

	//find out if coupon is valid
	if(!empty($codes))
	{
		$checkout_userID = get_checkout_userID();
		$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL));

		foreach($codes as $code)
		{
			if(empty($code)){ continue; }

			$te_coupons->set_cart_contents($cart->contents);
			$ret .= '<input type="text" class="coupon_code span6" name="coupon_code_'.intval($next_index).'" value="'.h($code).'" '.((@in_array($code,$session_codes))?'readonly':'').'/>';
			if($te_coupons->is_valid($code))
			{
				$ret .= ' <div class="coupon_check valid"></div><br />';
			}
			else
			{
				$ret .= ' <div class="coupon_check invalid"></div><br />';
			}
			$next_index++;
		}
	}

	if ( !$init_single || $next_index == 1 )
	{
		$ret .= '<input type="text" class="coupon_code span6" name="coupon_code_'.($next_index).'" value="" />';
	}

	return $ret;
}

////////////////////////////////////////////////////////////////
// CHECKOUT BASICS END
////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////
// DELEGATED CHECKOUT BEGIN
////////////////////////////////////////////////////////////////

/**
 * Gets the current user checking out
 * In most cases this is the same as the logged in user
 * But there will be cases where it is actually somebody else (delegated checkouts)
 * To be safe always use this function instead of referencing $AI->user->userID directly
 * @param  mixed $default  Specify the default value, leaving this at NULL will use $AI->user->userID as default
 * @return int             The userID of the user checking out
 */
function get_checkout_userID( $default = null )
{
	global $AI;
	if ( $default === null )
	{
		$default = $AI->user->userID;
	}
	if ( null !== ($userID = util_GET('sf')) ) // Anonymous checkout
	{
		$userID = util_decompress($userID);
		$_SESSION['anonymous_checkout_for'] = $userID;
	}
	return (int) util_SESSION('checkout_userID', $default);
}

/**
 * Gets the full name of the current user checkout out
 * @param  mixed $default  Specify the fallback userID, leaving this at NULL will use $AI->user->userID as the fallback
 * @return string          The full name (first and last name separated by a triple space)
 */
function get_checkout_name( $default = null )
{
	$checkout_userID = get_checkout_userID($default);
	$checkout_name   = db_lookup_scalar("SELECT CONCAT_WS('   ', first_name, last_name) FROM users WHERE userID = " . (int) $checkout_userID . " LIMIT 1;");
	return $checkout_name;
}

/**
 * Sets the user that will be checking out
 * If you leave the parameter blank, this resets it to the current logged in user
 * @param  int $userID  The userID
 * @return void
 */
function set_checkout_userID( $userID = null )
{
	global $AI;
	if ( $userID === null )
	{
		$userID = (int) $AI->user->userID;
	}
	$_SESSION['checkout_userID'] = (int) $userID;
	unset($_SESSION['anonymous_checkout_for']);
}

/**
 * Alias for set_checkout_userID(null), i.e. set the checkout userID to the current logged in user
 * @return void
 */
function reset_checkout_userID()
{
	set_checkout_userID(null);
}

/**
 * Returns whether the current checkout is a delegated one, i.e. current user is checking out for another user
 * @return bool
 */
function is_delegated_checkout()
{
	global $AI;
	$checkout_userID = get_checkout_userID(null);
	return $AI->user->isLoggedIn() && $checkout_userID != $AI->user->userID;
}

/**
 * Returns whether the current checkout is an ANONYMOUS delegated one, i.e. current user is checking out for a new user
 * @return bool
 */
function is_delegated_anonymous_checkout()
{
	$userID = util_SESSION('anonymous_checkout_for');
	return !empty($userID);
}

/**
 * Draws the Delegated Checkout warning
 * @param  string $intro_text           A text to replace the default "You are currently shopping for:"
 * @param  bool   $draw_account_return  Draws the form to return to ones own account, default=TRUE
 * @param  bool   $return_output        Set to TRUE to return the output instead of echoing it
 * @return void|string
 */
function draw_delegated_checkout_warning( $intro_text = null, $draw_account_return = true, $return_output = false )
{
	global $AI;

	$AI->skin->css('includes/modules/store/checkout/checkout_delegation.css');
	$AI->skin->js('includes/modules/store/checkout/checkout_delegation.js');

	$intro_text = $intro_text === null ? 'You are currently shopping for:' : trim($intro_text) . '';

	$output = '';
	if ( is_delegated_checkout() )
	{
		require_once ai_cascadepath('includes/plugins/user_profiles/includes/functions.social.php');

		$checkout_userID = get_checkout_userID();
		$checkout_name   = get_checkout_name();

		$profile_pic = social_draw_profile_picture_to_size($checkout_userID, 48, 48, false, true);
		if ( preg_match('/comUserDefaultImage/', $profile_pic) )
		{
			$profile_pic = '<img src="images/no-profile-pic.svg" alt="" style="width:48px;height:48px;">';
		}
		$output  = '<div class="delegated_checkout_warning">';
		$output .= '<div class="delegated_intro">' . h($intro_text) . '</div>';
		$output .= '<div class="delegated_info">';
		$output .= '	<span class="delegated_name">' . h($checkout_name) . '</span>';
		$output .= '	<span class="delegated_userID">User #' . (int) $checkout_userID . '</span>';
		//$output .= '	<span class="delegated_rank">Rank Name</span>';
		//$output .= '	<span class="delegated_email">tata@example.com</span>';
		$output .= '</div>';
		$output .= '<div class="delegated_image">';
		$output .= '	' . $profile_pic;
		$output .= '</div>';

		if ( $draw_account_return )
		{
			$output .= '<form class="shop_for_form" action="shop-for" method="post"><input type="hidden" name="userID" value="' . (int) $AI->user->userID . '">';
			$output .= '<input class="shop_for_form_submit" type="submit" value="Click here to return to your account">';
			$output .= '</form>'; //.shop_for_form
		}

		$output .= '</div>'; // #delegated_checkout_warning
	}
	if ( $return_output )
	{
		return $output;
	}
	echo $output;
}

////////////////////////////////////////////////////////////////
// DELEGATED CHECKOUT END
////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////
// MULTI-STORE (a.k.a. ISOLATED STORES) BEGIN
////////////////////////////////////////////////////////////////

/**
 * Returns the base store folderID and accommodates for isolated stores
 * @return int
 */
function store_get_base_folderID()
{
	global $AI;

	static $base_folderID = null;

	if ( $base_folderID === null )
	{
		$base_folderID = 2;
		if ( defined('AI_STORE_FOLDER_ID') )
		{
			$base_folderID = AI_STORE_FOLDER_ID;
		}
		$sos_folder = $AI->get_setting('sales_of_service_folderID');
		if ( intval($sos_folder) &&  !empty($_SESSION['store_lead_ids']) && count($_SESSION['store_lead_ids']) )
		{
			$base_folderID = $sos_folder;
		}
		// Isolated stores from multi-store setup
		if ( null !== ($iso_folderID = $AI->get_setting('iso_store_folderID')) )
		{
			$base_folderID = (int) $iso_folderID;
		}
	}

	return (int) $base_folderID;
}


function get_product_folders($base_folderID, array $products = array(), $folder_tree = '')
{
	global $AI;
	$folder_children = array();
	//hide categories?
	aimod_load_module('store');
	if($AI->MODS['store']->hide_categories!='Yes') {

		if($base_folderID != NULL) {
			$base_folderID = intval($base_folderID);
			$sql = 'SELECT * FROM product_folders WHERE parentID = '.$base_folderID.' AND visible=1 ORDER BY sort_order ASC';

			$res = db_query($sql);
			while ($res && $row = db_fetch_assoc($res)) {
				foreach ($row as $name => $value) {
					$folder_children[db_out($row['folderID'])][db_out($name)] = db_out($value);
				}
			}
		}
		else
		{
			foreach($products AS $n=>$v)
			{
				$folder_query = "SELECT pf.* FROM products2folders AS p2f JOIN product_folders AS pf ON pf.folderID = p2f.folderID WHERE p2f.product_id = ".(int)$v['product_id']. " AND pf.folderID IN (".implode(',', $folder_tree).")";
				$res2 = db_query($folder_query);
				while($res2 && $folder_info=db_fetch_assoc($res2))
				{
					$folder_info = array_map('db_out', $folder_info);
					foreach($folder_info AS $name=>$value)
					{
						if(!isset($folder_children[$folder_info['folderID']][$name]))
						{
							$folder_children[$folder_info['folderID']][$name] = db_out($value);
						}
					}
				}
			}
		}
	}

	// If no folders don't need to do any filters

	if(!empty($folder_children))
	{
		//Start Hook Filters
		$hook_results = aimod_run_hook('hook_product_folders_filter', $folder_children, get_checkout_userID());
		$intersect_arr = $folder_children;
		foreach($hook_results AS $n => $v)
		{
				$intersect_arr = array_intersect_key($intersect_arr,$v);
		}

		$folder_children = $intersect_arr;
		//End Hook Filters
	}





	return $folder_children;
}

/**
 * Returns the base store URL and accommodates for isolated stores
 * @param  string $empty_on_match  If you provide a string and the return matches this parameter (case-sensitive), the return will be an empty string instead
 * @param  string $get_key         A key in GET vars that will hint us to an isolated store
 * @return string
 */
function store_get_base_url( $empty_on_match = null, $get_key = null )
{
	global $AI;

	static $base_url = null;

	if ( $base_url === null )
	{
		$base_url = AI_DEFAULT_STORE_URL;
		if ( $get_key !== null && null !== ($url_in_get = util_GET($get_key)) )
		{
			$base_url = $url_in_get;
		}
		elseif ( null !== ($iso_url = $AI->get_setting('iso_store_url')) )
		{
			$base_url = $iso_url;
		}
	}

	if ( $empty_on_match !== null && $empty_on_match == $base_url )
	{
		return '';
	}

	return $base_url;
}

////////////////////////////////////////////////////////////////
// MULTI-STORE (a.k.a. ISOLATED STORES) END
////////////////////////////////////////////////////////////////

/**
 * Used in process.php, redirects the user to a different site to complete the payment process.
 * Build the argument list here too
 * Any errors will be added to the order, which will trigger the error output should the redirect fail
 *
 * currently only supports PayPal
 *
 * @param  string $card_type		which payment service was selected
 * @param  obj $order						the order
 * @param  obj $bp 							billing profile
 * @param  int $doba_order_id   the doba order_id
 * @return void
 */
function run_offsite_checkout( $card_type, &$order, $bp, $doba_order_id = 0 )
{
	global $AI;

	//save the billing profile
	$bp->save();
	//create the order. do not run_order(), as that would attempt to run a transaction itself, and would finalize the order.
	//set the payment status to auth to indicate that the payment process has begun, but has not completed.
	//payment status will be updated to CAPTURE when we learn that the txn is complete
	$order_id = $order->create($bp, '', 0, 0, array('payment_status' => 'AUTH'));

	if($doba_order_id > 0) {
		db_query("UPDATE orders SET extID=".intval($doba_order_id).", extStatus='Created' WHERE order_id=".intval($order_id));
	}

	//sync the status so that orders_status will read "Received". the payment will read "Authorized" at this stage
	$order->sync_status_using_payment_status();

	//make a txn entry for this order
	$txn_mod = aimod_get_module('txn');
	$txn_id = $txn_mod->reserve_transaction($order_id, 'AUTH');
	$txn_mod->update_order_with_id($order_id, $txn_id);

	//prepare the data, formatted based on which service is being used
	$form_name = 'ai_offsite_frm';
	$merchant_id = 0;

	$item_name = $AI->get_setting('siteName').' - Order '.$order_id; //description that appears for the item on paypal during checkout
	$user_verify_hash = $order->get_user_verify_hash(); //allows user to see their receipt when they return


	$merchant_id = (int)db_lookup_scalar("SELECT merchant_id FROM merchants WHERE name = '".db_in($card_type)."' AND enabled = 'Yes'"); // REMOVED by jason 7/25/2016 - not looking for enabled modules (int) db_lookup_value('merchants', 'name', $card_type, 'merchant_id', false);

	$card_type_module = aimod_run_hook_module('txn', 'hook_get_txn_module_by_merchant_id', $merchant_id);

	$module = aimod_get_module($card_type);

	switch($card_type)
	{
		case 'paypal_ipn':

			if(empty($module->paypal_id))
			{
				$order->warning('PayPal receiver account not specified. Please set in the paypal_ipn module settings.');
			}
		break;

		case 'solid_trust_pay':
		case 'stp':
			if(empty($module->merchantAccount))
			{
				$order->warning('STP receiver account not specified. Please set in the stp module settings.');
			}
		break;

		default:
			$order->warning('Card Type not recognized. Please select a different payment option.');
		break;
	}

	$form = $card_type_module->generate_external_request_form($form_name,$module,$item_name,$order,$order_id,$user_verify_hash,$bp);

	//log the data that will be sent
	$sql = "
		UPDATE transactions
		SET
		raw_sent = '" . db_in($form) . "'
		, userID = " . (int) $bp->get_account_id() . "
		, amount = " . (double) $order->get('total') . "
		WHERE txn_id = " . (int) $txn_id . "
		LIMIT 1
	;";
	if(!db_query($sql))
	{
		$order->warning('Failed to setup transaction entry.');
	}
	//set order merchant id
	$sql = "
		UPDATE orders
		SET
		merchant_id = ". (int) $merchant_id ."
		WHERE order_id = " . (int) $order_id . "
		LIMIT 1
	;";
	if(!db_query($sql))
	{
		$order->warning('Failed to log merchant id.');
	}

	if(!$order->has_warnings())
	{
		//clear out their cart. we will not have an opportunity to do it later
		$cart = new C_cart(get_checkout_userID(), true, store_get_base_url(AI_DEFAULT_STORE_URL));
		$cart->remove_all();

		$_SESSION['return_order_id'] = $order_id; // This needs to be stamped before heading out

		//do the redirect
		echo '<img src="images/loading.gif" alt="Processing..." /> Redirecting to complete your payment option...'; //loading icon
		echo $form;
		echo '<script language="JavaScript" type="text/javascript">document.'.$form_name.'.submit();</script>';
		@flush();
		die; //without this we get an infinite redirect loop trying to reach checkout/shopping-cart
	}
	//if we do have errors, then process.php will display them
}


function refill_post_data_in_array($post, &$array, $type='')
{
	if($type=='cc')
	{
		$type='';
	}
	if($type!='')
	{
		$type = $type.'_';
	}

	foreach($array AS $n=>$v)
	{

		if((isset($post[$type.$n]) && $post[$type.$n]!= $v) )
		{
			/*if($n=='first_name')
			{
				$first_name_space_pos = strpos($post[$type.$n], ' ');
				$real_first_name = substr( $post[$type.$n], 0, $first_name_space_pos);
				$real_last_name = substr( $post[$type.$n], $first_name_space_pos + 1);
				$array['first_name'] = $real_first_name;
				$array['last_name'] = $real_last_name;
			}
			else
			{
				$array[$n] = $post[$type.$n];
			}*/

            $array[$n] = $post[$type.$n];
		}


	}
}


?>