<?php
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.cart.php' ) );
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.wishlist.php' ) );
	require_once( ai_cascadepath('includes/plugins/dynamic_fields/class.dynamic_fields.php') );
	//require_once( ai_cascadepath('includes/plugins/subscriptions/class.subscriptions.php') );
	require_once( ai_cascadepath('includes/modules/store/checkout/functions.php') );

	global $AI;

	if (!empty($_GET['cmd']))
	{
		// THE AJAX CONTAINER
		global $cart, $wishlist;
		$checkout_userID = get_checkout_userID();
		$cart = new C_cart($checkout_userID, true, util_POST('second_id', ''));
		$wishlist = new C_wishlist($checkout_userID);

		switch ($_GET['cmd'])
		{
			// WISHLIST
			case 'add_wishlist':
			{
				echo 'shopping_cart_container|';
				if (!empty($_POST['product_id']) && !empty($_POST['qty']))
				{
					$sub_id = '';
					if(isset($_POST['sub_id']) && !empty($_POST['sub_id']))
					{
						$sub_id = $_POST['sub_id'];
					}

					$attr_id = '';
					if(isset($_POST['attr']) && !empty($_POST['attr'])) {
						$attr_id = $_POST['attr'];
					}

					$wishlist->add_item(strval($_POST['product_id']), (int)$_POST['qty'], $sub_id, $attr_id);
				}
			}
			break;

			case 'remove_from_wishlist':
				echo 'shopping_cart_container|';
				if (!empty($_POST['product_id'])) {
					$wishlist->remove_item_by_id($_POST['product_id']);
				}
				break;

			// CART ITEMS
			case 'load':
				echo 'shopping_cart_container|';
				break;

			case 'add':
			{
				echo 'shopping_cart_container|';
				if (!empty($_POST['product_id']) && !empty($_POST['qty']))
				{
					$sub_id = '';
					if(isset($_POST['sub_id']) && !empty($_POST['sub_id']))
					{
						$sub_id = $_POST['sub_id'];
					}

					$attr_id = '';
					if(isset($_POST['attr']) && !empty($_POST['attr'])) {
						$attr_id = $_POST['attr'];
					}

					$man_price = '';
					if(isset($_POST['man_price']) && !empty($_POST['man_price'])) {
						$man_price = (float)$_POST['man_price'];
					}
					$cart->add_item(strval($_POST['product_id']), (int)$_POST['qty'], $sub_id, $attr_id, $man_price);
				}
			}
			break;

			// Add a stock item instead of a product
			case 'add_item':
			{
				try
				{
					// Initial Fetch and check
					$stock_item_id = (int) util_POST('stock_item', 0);
					$quantity = (int) util_POST('quantity', 0);
					if ( empty($stock_item_id) )
					{
						throw new Exception('The inventory item is empty.');
					}
					if ( empty($quantity) )
					{
						throw new Exception('There was no quantity.');
					}
					// Module-based checks
					/**
					 * Event Trigger: hook_cart_add_item
					 * @param  $stock_item int The product_stock_items.stock_item_id
					 * @param  $quantitye  int The quantity
					 * @return string          A non-empty string is considered an error and will interrupt the item-adding process
					 */
					$hook_rets = aimod_run_hook('hook_cart_add_item', $stock_item_id, $quantity);
					foreach ( $hook_rets as $hook_name => $return )
					{
						if ( is_string($return) && !empty($return) ) // found error message
						{
							throw new Exception($return);
						}
					}
					// All is good, add to cart
					echo 'shopping_cart_container|';
					$cart->add_item($stock_item_id, $quantity);
				}
				catch ( Exception $e )
				{
					header('HTTP/1.0 400 Bad Request');
					header('Content-Type: text/plain; charset=utf-8');
					echo $e->getMessage();
					return;
				}
			}
			break;

            case 'remove':
			{
				echo 'shopping_cart_container|';
				if (!empty($_POST['product_id']))
				{
					$cart->remove_item(strval($_POST['product_id']), strval(@$_POST['attr']));
				}
			}
			break;

			case 'add_coupon' :
			{
				echo 'shopping_cart_container|';
				if(isset($_POST['coupon_code'])) {
					$cart->add_coupon($_POST['coupon_code']);
				}
			}
			break;

			// Orders TE only, gets the form for a single product using the "classic" them
			case 'get_add_product_form':
			require_once( ai_cascadepath( 'includes/modules/store/themes/classic/functions.php' ) );

			if(!isset($_POST['product_id'])) {
				echo 'ajax_error|Product ID is not specified.';
				return;
			}

			$product_id = (int)$_POST['product_id'];

			$product = db_lookup_assoc('SELECT * FROM products WHERE product_id = '.$product_id);

			if(!is_array($product)) {
				echo 'ajax_error|Product not found in database.';
				return;
			}

			$subscriptions = new C_subscriptions('', $_POST['userID']);
			global $already_subscribed;
			$already_subscribed = array();
			foreach ($subscriptions->existing_subs as $id => $sub) {
				if(strtotime($sub['end_date']) > time() && $sub['is_active'] == 'Y') {
					$already_subscribed[$id] = $sub['product_id'];
				}
			}
			global $low_stock_level;
			$low_stock_level = $AI->get_setting('low_stock_level');
			echo 'add_product_form|';
			draw_product_add_to_card_form($product_id, $product, false);
			return;
			break;

			case 'modify_shipping_price':
				if(!isset($_POST['shipping_price']) || (float)$_POST['shipping_price'] <= 0) {
					echo 'ajax_error|shipping price not defined';
				}
				echo 'shopping_cart_container|';
				$cart->shipping_rate = (float)$_POST['shipping_price'];
				$cart->save();

				break;

			default; exit(); break;
		}
	}
	else
	{
		return;
	}

	$theme = isset($_GET['theme']) ? $_GET['theme'] : (isset($_SESSION['ai_store_theme']) ? $_SESSION['ai_store_theme'] : $AI->get_setting('store_theme') );

	// store the theme for future use
	if($theme != 'te_orders') { // Block this theme from being saved in session, used on a per call basis for orders ONLY (mainly TE)
		$_SESSION['ai_store_theme'] = $theme;
	}

	if(!$theme) { $theme = 'classic'; }

	$cart_file = 'includes/modules/store/themes/'.$theme.'/draw.cart.php';

	if(file_exists(ai_cascadepath($cart_file))) {
            include( ai_cascadepath($cart_file) );
    } else {
		echo '<h3 style="color:red;"> Error: Select Shopping Cart Draw File "'.$theme.'" Does not Exist</h3>';
	}
	if(!empty($_SESSION['store_lead_ids'])){
            //require_once(ai_cascadepath('includes/plugins/tags/class.tags.php'));
            //require_once(ai_cascadepath('includes/plugins/tags/class.draw_tag_cloud.php'));

            echo 'You have <a href="javascript:jonbox_open_div(\'name_jonbox\')">'.count($_SESSION['store_lead_ids']).' leads</a> selected.';
            if($cart->get_num_items() <> count($_SESSION['store_lead_ids'])){
				echo '<br>Please adjust the quantity of items in your cart.<br><strong>You have '.$cart->get_num_items().' item'.($cart->get_num_items()== 1 ? '':'s').' for '.count($_SESSION['store_lead_ids']).' leads</strong>';
			}
			$content='<a href="lead_management?clear_sos_leads=true">Remove Leads from cart</a><br />';
            foreach($_SESSION['store_lead_ids'] as $k => $v){
                if(intval($v)){
                    $s = "SELECT CONCAT_WS(' ', first_name, last_name) FROM `lead_management`  WHERE id = '" . intval($v) . "' LIMIT 1;";
                    $name = db_lookup_scalar($s);
                    $content .= $name.' ('.intval($v).')<br />';
                }
            }
            echo'<div style="display:none" id="name_jonbox">'. $content.'</div>';
        }
?>