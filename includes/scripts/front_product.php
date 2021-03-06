<?php

$products = array();
$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price`,`ps`.`alt_prices` FROM `products` `p` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id` IN (SELECT `product_id` FROM `products2folders` WHERE `folderID` =12) AND `p`.`product_id` NOT IN (SELECT `product_id` FROM `products2folders` WHERE `folderID` =15) GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
if($_SERVER['SERVER_NAME'] == 'www.vivacitygo.net'){
    $p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price`,`ps`.`alt_prices` FROM `products` `p` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id` IN (SELECT `product_id` FROM `products2folders` WHERE `folderID` =12) GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
}

while($p_res && $product = db_fetch_assoc($p_res)) {
    $products[] = $product;
}


?>


<div class="container-fluid spblock1">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <!--products block-->
            <div class="spblock1productcontent">
                <!--single products block-->

                <!--end single products block-->

                <?php

                if(count($products)){
                    foreach($products as $product){
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

                        <div class="spblock1singleproduct">
                            <div class="spblock1productwrapper">
                                <a href="/product-info/<?php echo $id;?>/<?php echo $url_title;?>"><img src="<?php echo $img_path;?>"></a>
                            </div>
                        </div>

                        <?php
                    }
                }

                ?>

            </div>
            <!--end products block-->
        </div>
    </div>
</div>

<div class="container-fluid productcontentblock">
    <div class="spblock1prodcontent">

        <?php

        if(count($products)){
        foreach($products as $product){
        $img_path = $product['img_url'];
            $id = $product['product_id'];
            $title = $product['title'];
            $url_title = strtolower($title);
            $url_title = preg_replace("/[^a-z0-9_\s-]/", "", $url_title);
            $url_title = preg_replace("/[\s-]+/", " ", $url_title);
            $url_title = preg_replace("/[\s_]/", "-", $url_title);

            $desc= $AI->get_defaulted_dynamic_area($product['description'],'');

            $desc=strip_tags($desc);

            if(strlen($desc) > 300)
            {
                $desc=substr($desc,0,300) . '...';
            }

        if(!file_exists($img_path)){
            $img_path="system/themes/vivacity_frontend/images/defaultproduct.png";
        }
        ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 spsingleproductcontentblock">
                <h3><?php echo $title;?></h3>
                <span class="hrlinesml"></span>
                <p><?php echo $desc;?></p>
                <div class="text-center"><a class="btn btn-default btnredmore" href="/product-info/<?php echo $id;?>/<?php echo $url_title;?>">Read More</a></div>
            </div>

            <?php
        }
        }

        ?>
    </div>
</div>