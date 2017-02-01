<?php
global $AI;
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$product_id = $uri_segments[2];

$p_res = db_query("SELECT * FROM `products` WHERE `product_id`=".$product_id);

$product_details = db_fetch_assoc($p_res);

$title = $product_details['title'];
$pro_url_title = strtolower($title);
$pro_url_title = preg_replace("/[^a-z0-9_\s-]/", "", $pro_url_title);
$pro_url_title = preg_replace("/[\s-]+/", " ", $pro_url_title);
$pro_url_title = preg_replace("/[\s_]/", "-", $pro_url_title);

if(file_exists($product_details['img_url'])){
    $product_image=$product_details['img_url'];
}else{
    $product_image="system/themes/vivacity_frontend/images/defaultproduct.png";
}

$products_arr=array(5,6,7,8,18,19,20,21,22);
$products_arr=array_diff($products_arr,array($product_id));

$product_cls1_arr = array(5=>'infocls1',6=>'infocls2',7=>'infocls3',8=>'infocls4',18=>'infocls5',19=>'infocls6',20=>'infocls7',21=>'infocls8',22=>'infocls9');
$product_cls2_arr = array(5=>'infohead1',6=>'infohead2',7=>'infohead3',8=>'infohead4',18=>'infohead5',19=>'infohead6',20=>'infohead7',21=>'infohead8',22=>'infohead9');

$o_res = db_query("SELECT `p`.`product_id`,`p`.`title` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` WHERE `pf`.`folderID`=12 AND `p`.`product_id` IN (".implode(',',$products_arr).") ORDER BY `p`.`title`");



?>


<div class="container-fluid innerpageprodtitleblock text-center">
    <div class="innerpageprodtitleblockwrapper">
        <div class="innerpageprodcontentboxwrapper">
            <img src="<?php echo $product_image;?>" alt="">
            <h1><?php echo $product_details['title'];?></h1>
        </div>
    </div>
</div>

<div class="container-fluid productinfoblock1 text-center">
    <div class="innerpageprodcontentboxwrapper">
        <h2>Vivacity Products</h2>
        <div class="prodinfohrline"></div>
        <div class="viproductlists">
            <ul class="list-inline">
                <?php


                while($o_res && $oproduct = db_fetch_assoc($o_res)) {
                    $id = $oproduct['product_id'];
                    $title = $oproduct['title'];
                    $url_title = strtolower($title);
                    $url_title = preg_replace("/[^a-z0-9_\s-]/", "", $url_title);
                    $url_title = preg_replace("/[\s-]+/", " ", $url_title);
                    $url_title = preg_replace("/[\s_]/", "-", $url_title);

                ?>
                <li class="<?php echo $product_cls1_arr[$id];?>"><a href="/product-info/<?php echo $id;?>/<?php echo $url_title;?>" class="vivprodlist"><?php echo $oproduct['title'];?></a></li>

                    <?php

                }

                ?>
            </ul>
        </div>
        <div class="viproductlistsinfowrapper">
         <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-sm-12">
                <div class="viproductlistsinfo">
                    <h3 class="<?php echo $product_cls2_arr[$product_id];?>"><?php echo $product_details['title'];?></h3>
                    <p><?php echo $AI->get_defaulted_dynamic_area($product_details['description']);?></p>
                    <p class="viproductlistsinfoinner">
                        <span class="titlespan <?php echo $product_cls2_arr[$product_id];?>">Product Ingredients:</span>

                        <?php echo $AI->get_defaulted_dynamic_area($product_details['ingredients']);?>
                    </p>
                    <p><a class="btn btn-default btnbuynowlink" href="/product-details/<?php echo $product_details['product_id'];?>/<?php echo $pro_url_title;?>">Buy Now</a></p>
                    <p class="titlespaninfosmltxt">*These statements have not been evaluated by the Food & Drug Administration. This product is not intended to diagnose, treat,, cure or prevent any disease. </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-sm-12">
                <div class="viproductlistsinfo">
                    <h3 class="<?php echo $product_cls2_arr[$product_id];?>">Benefits</h3>
                    <?php echo $AI->get_defaulted_dynamic_area($product_details['benefits']);?>
                    <div class="clearfix"></div>
                    <div class="videoblock1rightblock3">
                        <ul class="list-inline">
                            <li>
                                <a href="javascript:void(0)">
                                    <img src="system/themes/vivacity_frontend/images/iconfbvideo.png">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <img src="system/themes/vivacity_frontend/images/iconpintvideo.png">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <img src="system/themes/vivacity_frontend/images/icontwvideo.png">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <img src="system/themes/vivacity_frontend/images/icongplusvideo.png">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <img src="system/themes/vivacity_frontend/images/iconblogvideo.png">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</div>