<?php
global $AI;

$keyword = '';
$showAll = 0;

if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
}

if(isset($_GET['showAll'])){
    $showAll = 1;
}

$product_list = array();
$program_list = array();

if(!empty($keyword)){
    $product_list = $AI->db->GetAll("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `pf`.`folderID`=12 AND `p`.`title` LIKE '%".$keyword."%' GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
    $program_list = $AI->db->GetAll("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `pf`.`folderID`=11 AND `p`.`title` LIKE '%".$keyword."%' GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
}

if($showAll == 1){
    $product_list = $AI->db->GetAll("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `pf`.`folderID`=12 GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
    $program_list = $AI->db->GetAll("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `pf`.`folderID`=11 GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");
}

?>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>Search</h1>
    </div>
</div>


<div class="container-fluid spblock1 searchresultblock">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ribbonblock">

            <?php
            if(empty($keyword) && $showAll == 0){
            ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="emptyshoppingwrapper">
                            <p>
                                No search keyword.
                            </p>
                        </div>
                    </div>
                </div>
                <a class="btnshowall" href="search-result?showAll=">Show All</a>
            <?php
            }elseif(count($product_list) == 0 && count($program_list) == 0 && $showAll == 0){
            ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="emptyshoppingwrapper">
                            <p>
                                No result found.
                            </p>
                        </div>
                    </div>
                </div>
                <a class="btnshowall" href="search-result?showAll=">Show All</a>
                <?php
            }else{
            ?>
            <?php
            if($showAll == 0){
                ?>
                <h2>Results For Keywords: <span><?php echo $keyword; ?></span></h2>
                <a class="btnshowall" href="search-result?showAll=">Show All</a>
                <?php } ?>
                <?php if(count($product_list)){

                    ?>
                    <h3>Product List</h3>
                    <span class="footertitlebottomline"></span>
                    <?php foreach($product_list as $row){

                        $img_path = $row['img_url'];

                        if(!file_exists($img_path)){
                            $img_path="system/themes/vivacity_frontend/images/defaultproduct.png";
                        }

                        $id = $row['product_id'];
                        $title = $row['title'];
                        $url_title = strtolower($title);
                        $url_title = preg_replace("/[^a-z0-9_\s-]/", "", $url_title);
                        $url_title = preg_replace("/[\s-]+/", " ", $url_title);
                        $url_title = preg_replace("/[\s_]/", "-", $url_title);

                        ?>
                    <div class="spblock1prodcontent">
                        <div class="spblock1singleprod">
                            <div class="spblock1prodwrapper">
                                <a href="product-details/<?php echo $id;?>/<?php echo $url_title;?>">
                                    <img src="<?php echo $img_path;?>">
                                </a>
                            </div>
                            <a href="product-details/<?php echo $id;?>/<?php echo $url_title;?>"><h1><?php echo $title; ?></h1></a>
                            <span class="hr"></span>
                            <h3>$ <?php echo number_format($row['price'],2,'.','')?></h3>
                        </div>
                    </div>
                <?php }} ?>
                <div style="clear: both;"></div>
                <?php if(count($program_list)){

                    ?>
                    <h3>Program List</h3>
                    <?php foreach($program_list as $row){

                        $img_path = $row['img_url'];

                        if(!file_exists($img_path)){
                            $img_path="system/themes/vivacity_frontend/images/defaultproduct.png";
                        }

                        $id = $row['product_id'];
                        $title = $row['title'];
                        $url_title = strtolower($title);
                        $url_title = preg_replace("/[^a-z0-9_\s-]/", "", $url_title);
                        $url_title = preg_replace("/[\s-]+/", " ", $url_title);
                        $url_title = preg_replace("/[\s_]/", "-", $url_title);

                        ?>
                    <div class="spblock1prodcontent">
                        <div class="spblock1singleprod">
                            <div class="spblock1prodwrapper">
                                <a href="product-details/<?php echo $id;?>/<?php echo $url_title;?>">
                                    <img src="<?php echo $img_path;?>">
                                </a>
                            </div>
                            <a href="product-details/<?php echo $id;?>/<?php echo $url_title;?>"><h1><?php echo $title; ?></h1></a>
                            <span class="hr"></span>
                            <h3>$ <?php echo number_format($row['price'],2,'.','')?></h3>
                        </div>
                    </div>
                <?php }} ?>

            <?php
            }
            ?>



        </div>
    </div>
</div>