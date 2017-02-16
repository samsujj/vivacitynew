<?php

require_once( ai_cascadepath( 'includes/modules/store/includes/class.cart.php' ) );
$cart = new C_cart(0);


$bill_profile->billing_addr['first_name'] = $_POST['bill_first_name'];
$bill_profile->billing_addr['last_name'] =  $_POST['bill_last_name'];
$bill_profile->billing_addr['address_line_1'] =  $_POST['bill_address_line_1'];
$bill_profile->billing_addr['address_line_2'] =  '';
$bill_profile->billing_addr['city'] =  $_POST['bill_city'];
$bill_profile->billing_addr['region'] =  $_POST['bill_region'];
$bill_profile->billing_addr['country'] =  $_POST['bill_country'];
$bill_profile->billing_addr['postal_code'] =  $_POST['bill_postal_code'];
$bill_profile->billing_addr['email'] =  $_POST['email'];
$bill_profile->billing_addr['phone'] =  $_POST['phone'];


$bill_profile->shipping_addr['first_name'] =  (!empty($_POST['ship_first_name'])?$_POST['ship_first_name']:$_POST['bill_first_name']);
$bill_profile->shipping_addr['last_name'] =  (!empty($_POST['ship_last_name'])?$_POST['ship_last_name']:$_POST['bill_last_name']);
$bill_profile->shipping_addr['address_line_1'] = (!empty($_POST['ship_address_line_1'])?$_POST['ship_address_line_1']:$_POST['bill_address_line_1']);
$bill_profile->shipping_addr['address_line_2'] = '';
$bill_profile->shipping_addr['city'] = (!empty($_POST['ship_city'])?$_POST['ship_city']:$_POST['bill_city']);
$bill_profile->shipping_addr['region'] = (!empty($_POST['ship_region'])?$_POST['ship_region']:$_POST['bill_region']);
$bill_profile->shipping_addr['country'] = (!empty($_POST['ship_country'])?$_POST['ship_country']:$_POST['bill_country']);
$bill_profile->shipping_addr['postal_code'] = (!empty($_POST['ship_postal_code'])?$_POST['ship_postal_code']:$_POST['bill_postal_code']);

$cart->remove_all();

$cart->add_item(intval($_POST['stock_item_id']),1);

$tax = $cart->get_tax($bill_profile);
$cart->remove_all();
echo $tax;


?>