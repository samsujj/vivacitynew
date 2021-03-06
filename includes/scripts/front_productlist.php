<?php

$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price`,`ps`.`alt_prices` FROM `products` `p` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id` IN (SELECT `product_id` FROM `products2folders` WHERE `folderID` =12) AND `p`.`product_id` NOT IN (SELECT `product_id` FROM `products2folders` WHERE `folderID` =15) GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
if($_SERVER['SERVER_NAME'] == 'www.vivacitygo.net'){
    $p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price`,`ps`.`alt_prices` FROM `products` `p` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id` IN (SELECT `product_id` FROM `products2folders` WHERE `folderID` =12) GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
}




?>


<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>Shop Products</h1>
    </div>
</div>

<div class="container-fluid spblock1">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ribbonblock">
            <div class="ribbon">
                <!--<div class="ribbon-content"><h1><b>Heal - Renew - protect</b></h1></div>-->
                <div class="ribbon-content"><h1><b>Energize - Enhance - Inspire</b></h1></div>
            </div>
            <h2>Set Your Intention, Choose a Product, Live The Results,<br>Upgrade to Your new vital life now!</h2>
          <!--  <h2>All our products are always responsibly sourced, ethically<br>created and never tested on animals</h2>-->
            <!--products block-->
            <div class="spblock1prodcontent">

                <!--single products block-->

                <!--end single products block-->

                <?php

                while($p_res && $product = db_fetch_assoc($p_res)) {
                    //echo '<pre>';
                    $altprice=unserialize($product['alt_prices']);

                   // echo '</pre>';
                    $productprice=$product['price'];
                    if(($AI->user->account_type == 'Distributor' || $AI->user->account_type == 'Promoter') && $altprice[0]['price']!=0){
                        $productprice= $altprice[0]['price'];
                    }
                    $img_path = $product['img_url'];

                    if(!file_exists($img_path)){
                        $img_path="system/themes/vivacity_frontend/images/defaultproduct.png";
                    }

                    $id = $product['product_id'];
                    $title = $product['title'];
                    $url_title = strtolower($title);
                    $url_title = preg_replace("/[^a-z0-9_\s-]/", "", $url_title);
                    $url_title = preg_replace("/[\s-]+/", " ", $url_title);
                    $url_title = preg_replace("/[\s_]/", "-", $url_title);

                    ?>

                    <div class="spblock1singleprod">
                        <div class="spblock1prodwrapper">
                            <a href="/product-details/<?php echo $id;?>/<?php echo $url_title;?>">
                                <img src="<?php echo $img_path;?>">
                            </a>
                        </div>
                        <a href="/product-details/<?php echo $id;?>/<?php echo $url_title;?>"><h1><?php echo $title; ?></h1></a>
                        <span class="hr"></span>
                        <h3>$ <?php echo number_format($productprice,2,'.','')?></h3>
                    </div>

                <?php

                }

                ?>

            </div>
            <!--end products block-->
        </div>
    </div>
</div>