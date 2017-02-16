<?php
require_once ai_cascadepath('includes/modules/store/checkout/functions.php');
global $AI;
require_once( ai_cascadepath( 'includes/modules/store/includes/class.cart.php')) ;
require_once( ai_cascadepath( 'includes/core/classes/breadcrumb.php' ) );
require_once( ai_cascadepath('includes/plugins/dynamic_fields/class.dynamic_fields.php') );

require_once ai_cascadepath('includes/modules/products/includes/class.product.php');

$checkout_userID = get_checkout_userID();
$cart = new C_cart($checkout_userID, true, store_get_base_url(AI_DEFAULT_STORE_URL, 'iso'));


$keyword = '';

if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
}

?>

<div class="container-fluid hometopblock hide">
    <div class="row">
        <div class="container containerwrapper">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hometopblockleft">
                    <ul class="list-inline">
                        <li><a href="javascript:void(0)">Help</a></li>
                        <li><a href="javascript:void(0)">Contact</a></li>
                        <li><a href="javascript:void(0)">Delivery Information</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hometopblockright">
                    <ul class="list-inline text-right">
                        <li><span class="glyphicon glyphicon-earphone"></span>
                            Call Us : <a href="tel:800.928.9401">800.928.9401</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid homelogoblock">
    <div class="row">
        <div class="containerwrapper">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 homelogoblockleft">
                    <div class="logowrapper">
                       <a href="/home" target="_parent"> <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" alt="logo"></a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 homelogoblockright">
                    <ul class="list-inline">

                        <?php if($AI->user->userID == 0){ ?>

                            <li class="limargin"><a class="btn btn-default btnlogin" href="/login">LOGIN</a></li>

                            <li class="limargin">|</li>
                            <li class="limargin"><a class="btn btn-default btnregister" href="javascript:void(0);">Prequalify</a></li>

                        <?php } ?>

                        <li class="carticon limargin"><a type="button" data-toggle="dropdown" class="dropdown-toggle-shopcart"><img src="system/themes/vivacity_frontend/images/icon-cart.png"></a>
                            <a href="javascript:void(0)"> My Cart </a>( <span id="topquan"><?php echo intval($cart->count())?></span> ) : $<span id="toptotamnt"><?php echo h(number_format($cart->get_total(), 2)); ?></span>
                            <div class="dropdown-menu" id="topcartarea">
                              <h1>Shopping Bag</h1>
                                <?php if(intval($cart->count())) {

                                    foreach ( $cart->contents as $stock_id => $cart_data ) {
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

                                    ?>
                                    <div class="divcartdetailhome">
                                        <div class="divcartdetailhomeleft">
                                            <div class="divimgwrapper">
                                                <div class="divimgwrapperinner">
                                                    <a href="product-details/<?php echo $pid;?>/<?php echo $ptitle1;?>"><img class="divimgwrapperinnerimg" src="<?php echo $imgpath;?>" alt="#"></a>
                                                </div>
                                            </div>
                                            <p><?php echo $ptitle;?></p>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="divcartdetailhomeright">
                                            <div class="divcartdetailhomerightinner">
                                                <span><?php echo h(intval($cart_data['qty'])); ?></span>
                                                <span>x</span>
                                                <span><strong>$<?php echo h(number_format($stock->get_price(), 2)); ?></strong></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <?php

                                }
                                    ?>

                                    <div class="clearfix"></div>
                                    <div class="divcartdetailhomerightinnertotaldiv">
                                        <span>Total</span>
                                        <span><?php echo intval($cart->count())?> items</span>
                                        <span>$<?php echo h(number_format($cart->get_sub_total(), 2)); ?></span>
                                    </div>


                                    <div class="clearfix"></div>
                              <a class="shoppingcartbag" href="shopping-cart-1">Shopping Bag</a>
                                <?php }else{ ?>
                                    <div class="clearfix"></div>
                                    <div class="divcartdetailhomerightinnertotaldiv" style="text-align: center; display: block;">
                                        <span style="width: 100%;">YOUR CART IS EMPTY</span>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                        <li class="topcartbtn"><a class="btn btn-default btncheckout" href="/checkoutfrontend">CHECKOUT</a></li>

                        <div class="clearfix"></div>
                    </ul>
                    <div class="searchboxblock">
                        <form method="get" action="search-result">
                            <input type="search" class="searchbox" placeholder="Search" value="<?php echo $keyword;?>" name="keyword" />
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid hometopnavblock">
    <div class="row">
        <div class="containerwrapper">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Menu</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li ><a href="/">Home</a></li>
                            <li class=" shop dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Shop
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/vivacity-products">Products</a></li>
                                    <li><a href="/vivacity-programs">Programs</a></li>
                                </ul>
                            </li>
                            <li><a href="vivacity-products-lists">Products</a></li>
                            <li class=" journey dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Journey
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/aboutus">About Us</a></li>
                                    <li><a href="/cannabinoides">Science</a></li>
                                </ul>
                            </li>
                            <li><a href="/programs">Programs</a></li>

                            <li class=" life dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Life
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                   <!-- <li><a href="/social-followup">Social</a></li>-->
                                   <!-- <li><a href="/videos">Videos</a></li>
                                    <li><a href="/blogs">Blog</a></li>-->

                                    <li><a href="javascript:void(0)" data-toggle="modal" data-target="#videosModal">Videos</a></li>
                                    <li><a href="javascript:void(0)" data-toggle="modal" data-target="#blogideosModal">Blog</a></li>

                                </ul>
                            </li>

                            <li><a href="/opportunityenrollment">PREQUALIFY</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </div>
</div>



<div class="modal fade topmenumodal" id="videosModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" alt="logo" class="opportunity_logoimg">
               <h4> Coming Soon</h4>
            </div>

        </div>

    </div>
</div>



<div class="modal fade topmenumodal" id="blogideosModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">

                <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" alt="logo" class="opportunity_logoimg">
                <h4> Coming Soon</h4>
            </div>

        </div>

    </div>
</div>

