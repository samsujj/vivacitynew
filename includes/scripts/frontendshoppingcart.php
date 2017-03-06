<?php
require_once ai_cascadepath('includes/modules/store/checkout/functions.php');
global $AI;
require_once( ai_cascadepath( 'includes/modules/store/includes/class.cart.php')) ;
require_once( ai_cascadepath( 'includes/core/classes/breadcrumb.php' ) );
require_once( ai_cascadepath('includes/plugins/dynamic_fields/class.dynamic_fields.php') );

require_once ai_cascadepath('includes/modules/products/includes/class.product.php');


$checkout_userID = get_checkout_userID();
$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));


$cart_products = array();

foreach ( $cart->contents as $stock_id => $cart_data )
{
    $cart_products[] = $stock_id;
}

//$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE (`pf`.`folderID`=12 OR `pf`.`folderID`=11) AND `ps`.`stock_item_id` NOT IN (".implode(',',$cart_products).") GROUP BY `ps`.`product_id`");

$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE (`pf`.`folderID`=12 OR `pf`.`folderID`=11) GROUP BY `ps`.`product_id`");

$p_count = db_num_rows($p_res);
$noofslider = ($p_count/4);
$noofsliderint = intval($noofslider);

if($noofslider > $noofsliderint){
    $noofsliderint = $noofsliderint+1;
}


?>

<style>
    .multi-item-carousel{
    .carousel-inner{
    > .item{
          transition: 500ms ease-in-out left;
      }
    .active{
    &.left{
         left:-33%;
     }
    &.right{
         left:33%;
     }
    }
    .next{
        left: 33%;
    }
    .prev{
        left: -33%;
    }
    @media all and (transform-3d), (-webkit-transform-3d) {
    > .item{
    // use your favourite prefixer here
    transition: 500ms ease-in-out left;
        transition: 500ms ease-in-out all;
        backface-visibility: visible;
        transform: none!important;
    }
    }
    }
    .carouse-control{
    &.left, &.right{
                 background-image: none;
             }
    }
    }


</style>

<script>
    $(function () {
        // Instantiate the Bootstrap carousel
        $('.multi-item-carousel').carousel({
            interval: false
        });

        showmulticarousel();




    });

    function showmulticarousel(){
        var winwidth = $(window).width();

        if(winwidth > 1240){
            $('.multi-item-carousel .item').each(function(){

                var next = $(this).next('.item');

                if (!next.length) {
                    next = $(this).siblings(':first');
                }

                next.children(':first-child').clone().appendTo($(this));

                if (next.next('.item').length>0) {
                    next.next().children(':first-child').clone().appendTo($(this));

                    if (next.next('.item').next('.item').length>0) {
                        next.next().next().children(':first-child').clone().appendTo($(this));
                    } else {
                        $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
                    }

                } else {
                    $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
                    $(this).siblings(':first').children(':first-child').next().clone().appendTo($(this));
                }

            });
        }else if(winwidth > 992){
            $('.multi-item-carousel .item').each(function(){
                var next = $(this).next('.item');
                if (!next.length) {
                    next = $(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));

                if (next.next('.item').length>0) {
                    next.next().children(':first-child').clone().appendTo($(this));
                } else {
                    $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
                }
            });
        }else if(winwidth > 640){
            $('.multi-item-carousel .item').each(function(){
                var next = $(this).next('.item');
                if (!next.length) {
                    next = $(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));
            });
        }

    }

</script>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>shopping cart</h1>
    </div>
</div>
<div class="container-fluid spblock1 spcartblock1">
    <div class="row">
        <div class="container shopcartwrapper">

            <?php if(intval($cart->count())) { ?>
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 spblock1prodcartleft">
                        <div class="shopcartlist" id="cartproductlistt">
                            <!--                       <div class="row row-eq-height shopcartsinglelist">
                                                       <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 shopcartsinglelistleft">
                                                           <div class="spcartimgwarpper">
                                                               <img src="system/themes/vivacity_frontend/images/imgcartproduct1.png">
                                                           </div>
                                                       </div>
                                                       <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 shopcartsinglelistright">
                                                           <div class="spblock1prodcartcontent">
                                                               <h1>Vivacity Program - Balance</h1>
                                                               <span class="btnpink">Price : $ 230.00 </span>
                                                               <h4>
                                                                   <span>Quantity</span>
                                                                   <div class="plusminusdiv">
                                                                       <a href="javascript:void(0)"><span>+</span></a> <input type="text" class="form-control" placeholder="1"> <a href="javascript:void(0)"><span>-</span></a>
                                                                   </div>
                                                                   <div class="instockdiv">IN Stock</div>
                                                               </h4>
                                                               <h5>Subtotal : <span>$ 230.00</span> <a href="javascript:void(0)"><div class="deletediv"><img src="system/themes/vivacity_frontend/images/icon-deleteshopcart.png">Delete</div></a></h5>
                                                           </div>
                                                       </div>
                                                   </div>
                           -->

                            <?php
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

                                if(!file_exists($imgpath)){
                                    $imgpath = 'system/themes/vivacity_frontend/images/defaultproduct.png';
                                }

                                ?>

                                <div class="row row-eq-height shopcartsinglelist">
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 shopcartsinglelistleft">
                                        <div class="spcartimgwarpper">
                                            <a href="product-details/<?php echo $pid;?>/<?php echo $ptitle1;?>"><img src="<?php echo $imgpath?>"></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 shopcartsinglelistright">
                                        <div class="spblock1prodcartcontent">
                                            <h1><a href="product-details/<?php echo $pid;?>/<?php echo $ptitle1;?>"><?php echo h($product->get_title());?></a></h1>
                                            <span class="btnpink">Price : $ <?php echo h(number_format($stock->get_price(), 2)); ?></span>
                                            <h4>
                                                <span>Quantity</span>
                                                <div class="plusminusdiv">
                                                    <a href="javascript:void(0)" onclick="quanDec(<?php echo $stock_id;?>)"><span>-</span></a> <input type="text" class="form-control" value="<?php echo h(intval($cart_data['qty'])); ?>" id="quanvalue<?php echo $stock_id;?>"> <a href="javascript:void(0)" onclick="quanInc(<?php echo $stock_id;?>)"><span>+</span></a>
                                                </div>
                                                <div class="instockdiv">IN Stock</div>
                                            </h4>
                                            <h5>Subtotal : <span>$ <?php echo h(number_format($stock->get_price() * $cart_data['qty'], 2)); ?></span> <a href="javascript:void(0)" onclick="delconfirm(<?php echo $stock_id;?>)"><div class="deletediv"><img src="system/themes/vivacity_frontend/images/icon-deleteshopcart.png">Delete</div></a></h5>
                                        </div>
                                    </div>
                                </div>

                                <?php


                            }

                            ?>

                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 spblock1prodcartright">
                        <ul class="list-group">
                            <li class="list-group-item shopcartheader">
                                Shopping Summary
                            </li>
                            <li class="list-group-item">
                                <span class="qtyprice" id="cartquan"><?php echo intval($cart->count())?></span>
                                Quantity
                            </li>
                            <li class="list-group-item">
                                <span class="qtyprice" id="cartsubtotal">$ <?php echo h(number_format($cart->get_sub_total(), 2)); ?></span>
                                Product Total
                            </li>
                            <!--<li class="list-group-item">
                                <span class="qtyprice">$ <?php //echo h(number_format($cart->shipping_rate, 2)); ?></span>
                                Shipping
                            </li>
                            <li class="list-group-item">
                                <span class="qtyprice"><?php //echo h(number_format($cart->get_tax(), 2)); ?></span>
                                Sales Tax
                            </li>
                            <li class="list-group-item">
                                <span class="qtyprice"  id="carttotaltotal">$ <?php //echo h(number_format($cart->get_total(), 2)); ?></span>
                                Total
                            </li>-->
                            <li class="list-group-item">
                                <a class="btn btn-default btngreensc" href="/checkoutfrontend">Checkout</a> <a class="btn btn-default btngraysc pull-right" href="/vivacity-products">Continue Shopping</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php }else{?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="emptyshoppingwrapper">
                            <div class="emptyshoppingimgwrapper">
                                <img src="system/themes/vivacity_frontend/images/iconemptyshoppingcart.png">
                            </div>
                            <p>
                                Your cart is empty
                            </p>
                            <a href="/vivacity-products" class="btngreen">Continue shopping</a>
                        </div>
                    </div>
                </div>
            <?php }?>

        </div>
    </div>
</div>

<!--
<div class="container-fluid spcartblock2">
    <div class="shopcartwrapper">
            <div class="row">
                <div class="titleheader text-center">
                    <h1>YOU MAY Also like</h1>
                    <span class="footertitlebottomline"></span>
                </div>
            </div>
            <div class="prodcartwrapper">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Indicators --><!--
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                    </ol>

                    <!-- Wrapper for slides --><!--
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"> <!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"> <!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"> <!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"> <!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgproduct1.png">
                                        </div>
                                        <h1>Recover</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                                <li class="item1"><!--single products block--><!--
                                    <div class="spblock2singleprod">
                                        <div class="spblock1prodwrapper">
                                            <img src="system/themes/vivacity_frontend/images/imgviewproductgallery2.png">
                                        </div>
                                        <h1>Awaken</h1>
                                        <span class="hr"></span>
                                        <h3>$ 170.00</h3>
                                        <a class="btn btn-default btngreencart">Add to Cart</a>
                                    </div>
                                    <!--end single products block--><!--
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
-->

<?php /*
if($p_count > 0){
?>
    <div class="container-fluid spcartblock2">
        <div class="shopcartwrapper">
            <div class="row">
                <div class="titleheader text-center">
                    <h1>YOU MAY Also like</h1>
                    <span class="footertitlebottomline"></span>
                </div>
            </div>

            <div class="prodcartwrapper">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php for($i=0;$i<$noofsliderint;$i++){

                            $clsStr = '';
                            if($i == 0){
                                $clsStr = 'class="active"';
                            }

                            ?>
                            <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i;?>" <?php echo $clsStr;?>></li>
                        <?php } ?>
                    </ol>

                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <ul id="shopcartcontainer">
                    <?php
                        $i=1;
                        while($p_res && $product = db_fetch_assoc($p_res)) {

                            $img_path = $product['img_url'];

                            if(!file_exists($img_path)){
                                $img_path="system/themes/vivacity_frontend/images/defaultproduct.png";
                            }

                            ?>
                            <li class="item1"><!--single products block-->
                                <div class="spblock2singleprod">
                                    <div class="spblock1prodwrapper">
                                        <img src="<?php echo $img_path;?>">
                                    </div>
                                    <h1><?php echo $product['title'];?></h1>
                                    <span class="hr"></span>
                                    <h3>$ <?php echo number_format($product['price'],2,'.','')?></h3>
                                    <a class="btn btn-default btngreencart"  onclick="addtocart1('<?php echo $product['product_id'];?>',<?php echo $product['stock_item_id'];?>)">Add to Cart</a>
                                </div>
                                <!--end single products block-->
                            </li>
                    <?php

                            if($i%4 == 0){
                        ?>
                            </ul>
                        </div>
                        <div class="item">
                            <ul id="shopcartcontainer">

                                <?php
                            }

                            $i++; } ?>
                                </ul>
                    </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

<?php
}
*/
?>




<?php
if($p_count > 0) {
    ?>
    <div class="container-fluid spcartblock2">
        <div class="shopcartwrapper">
            <div class="row">
                <div class="titleheader text-center">
                    <h1>YOU MAY Also like</h1>
                    <span class="footertitlebottomline"></span>
                </div>
            </div>

            <div class="prodcartwrapper">
                <div class="carousel slide multi-item-carousel" id="theCarousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#theCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#theCarousel" data-slide-to="1"></li>
                        <li data-target="#theCarousel" data-slide-to="2"></li>
                        <li data-target="#theCarousel" data-slide-to="3"></li>
                    </ol>

                    <div class="carousel-inner">
                        <?php
                        $i=1;
                        while($p_res && $product = db_fetch_assoc($p_res)) {

                        $img_path = $product['img_url'];

                        if(!file_exists($img_path)){
                            $img_path="system/themes/vivacity_frontend/images/defaultproduct.png";
                        }

                        $firstcls = '';
                            if($i == 1){
                                $firstcls = 'active';
                            }


                            $ptitle =  $product['title'];
                            $ptitle1 = strtolower($ptitle);
                            $ptitle1 = preg_replace("/[^a-z0-9_\s-]/", "", $ptitle1);
                            $ptitle1 = preg_replace("/[\s-]+/", " ", $ptitle1);
                            $ptitle1 = preg_replace("/[\s_]/", "-", $ptitle1);

                        ?>
                            <div class="item <?php echo $firstcls?>">
                                <div class="spblock2singleprod">
                                    <div class="spblock1prodwrapper">
                                        <a href="product-details/<?php echo $product['product_id'];?>/<?php echo $ptitle1;?>"><img src="<?php echo $img_path;?>"></a>
                                    </div>
                                    <h1> <a href="product-details/<?php echo $product['product_id'];?>/<?php echo $ptitle1;?>"><?php echo $product['title'];?></a></h1>
                                    <span class="hr"></span>
                                    <h3>$ <?php echo number_format($product['price'],2,'.','')?></h3>
                                    <a class="btn btn-default btngreencart"  onclick="addtocart1('<?php echo $product['product_id'];?>',<?php echo $product['stock_item_id'];?>)">Add to Cart</a>
                                </div>
                            </div>
                        <?php

                            $i++;


                        }   ?>
                        <!--<a class="left carousel-control" href="#theCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
                        <a class="right carousel-control" href="#theCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>-->
                    </div>
                </div>
                </div>
            </div>

        </div>
    </div>
    <?php
}
?>
