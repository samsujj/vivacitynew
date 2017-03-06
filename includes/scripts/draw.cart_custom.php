<?php
	require_once ai_cascadepath('includes/modules/products/includes/class.product.php');
    global $AI;
    require_once( ai_cascadepath( 'includes/modules/store/includes/class.cart.php')) ;
    $checkout_userID = get_checkout_userID();
    $cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));

    $html = '';
    $html2 = '';

    $html .= '<h1>Shopping Bag</h1>';

	if ( $cart->get_num_items() < 1 )
	{
        $html .= '<div class="clearfix"></div><div class="divcartdetailhomerightinnertotaldiv" style="text-align: center; display: block;"><span style="width: 100%;">YOUR CART IS EMPTY</span></div>';
	}else{

        foreach ( $cart->contents as $stock_id => $cart_data )
        {
            $product = C_product::get_new_product_from_stock($stock_id);
            $stock = $product->get_stock($stock_id);

            $pid =  $product->db->product['product_id'];
            $ptitle =  $product->db->product['title'];
            $ptitle1 = strtolower($ptitle);
            $ptitle1 = preg_replace("/[^a-z0-9_\s-]/", "", $ptitle1);
            $ptitle1 = preg_replace("/[\s-]+/", " ", $ptitle1);
            $ptitle1 = preg_replace("/[\s_]/", "-", $ptitle1);
            $imgpath = $product->db->product['img_url'];

            if (!file_exists($imgpath)) {
                $imgpath = 'system/themes/vivacity_frontend/images/defaultproduct.png';
            }

            $html .= '<div class="divcartdetailhome">';
            $html .= '<div class="divcartdetailhomeleft">';
            $html .= '<div class="divimgwrapper">';
            $html .= '<div class="divimgwrapperinner">';
            $html .= '<a href="product-details/'.$pid.'/'.$ptitle1.'"><img class="divimgwrapperinnerimg" src="'.$imgpath.'" alt="#"></a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<p>'.$ptitle.'</p>';
            $html .= '<div class="clear"></div>';
            $html .= '</div>';
            $html .= '<div class="divcartdetailhomeright">';
            $html .= '<div class="divcartdetailhomerightinner">';
            $html .= '<span>'. h(intval($cart_data['qty'])).'</span>';
            $html .= '<span>x</span>';
            $html .= '<span><strong>$'. h(number_format($stock->get_price(), 2)).'</strong></span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="clear"></div>';
            $html .= '</div>';



            $html2 .= '<div class="row row-eq-height shopcartsinglelist">';
            $html2 .= '<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 shopcartsinglelistleft">';
            $html2 .= '<div class="spcartimgwarpper">';
            $html2 .= '<a href="product-details/'.$pid.'/'.$ptitle1.'"><img src="'.$imgpath.'"></a>';
            $html2 .= '</div>';
            $html2 .= '</div>';
            $html2 .= '<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 shopcartsinglelistright">';
            $html2 .= '<div class="spblock1prodcartcontent">';
            $html2 .= '<h1><a href="product-details/'.$pid.'/'.$ptitle1.'">'. h($product->get_title()).'</a></h1>';
            $html2 .= '<span class="btnpink">Price : $ '.h(number_format($stock->get_price(), 2)).'</span>';
            $html2 .= '<h4>';
            $html2 .= '<span>Quantity</span>';
            $html2 .= '<div class="plusminusdiv">';
            $html2 .= '<a href="javascript:void(0)" onclick="quanDec('.$stock_id.')"><span>-</span></a> <input type="text" class="form-control" value="'.h(intval($cart_data['qty'])).'" id="quanvalue'.$stock_id.'"> <a href="javascript:void(0)" onclick="quanInc('.$stock_id.')"><span>+</span></a>';
            $html2 .= '</div>';
            $html2 .= '<div class="instockdiv">IN Stock</div>';
            $html2 .= '</h4>';
            $html2 .= '<h5>Subtotal : <span>$ '. h(number_format($stock->get_price() * $cart_data['qty'], 2)).'</span> <a href="javascript:void(0)" onclick="delconfirm('. $stock_id.')"><div class="deletediv"><img src="system/themes/vivacity_frontend/images/icon-deleteshopcart.png">Delete</div></a></h5>';
            $html2 .= '</div>';
            $html2 .= '</div>';
            $html2 .= '</div>';
        }

        $html .= '<div class="clearfix"></div>';
        $html .= '<div class="divcartdetailhomerightinnertotaldiv">';
        $html .= '<span>Total</span>';
        $html .= '<span>'.intval($cart->count()).' items</span>';
        $html .= '<span>$'. h(number_format($cart->get_sub_total(), 2)) .'</span>';
        $html .= '</div>';

        $html .= '<div class="clearfix"></div>';
        $html .= '<a class="shoppingcartbag" href="shopping-cart-1">Shopping Bag</a>';
    }


    echo json_encode(array('htmlcontent' => $html,'htmlcontent2'=>$html2, 'totalquan' => intval($cart->count()), 'producttotal'=>number_format($cart->get_sub_total(), 2), 'totalamnt'=>number_format($cart->get_total(), 2)));

?>