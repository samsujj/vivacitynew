<?php
global $AI;
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$product_id = $uri_segments[2];

$repUrl = 'http://vivacitygo.com';

if(util_rep_id()){
    $repId = util_rep_id();
    $repArr = util_get_uarr($repId);
    $repUrl = 'http://'.$repArr['username'].'.vivacitygo.com';
}


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

if($_SERVER['SERVER_NAME'] == 'www.vivacitygo.net'){
    $products_arr=array(5,6,7,8,18,19,20,21,22);
    $products_arr=array_diff($products_arr,array($product_id));
}else{
    $products_arr=array(5,6,7,8,18,19,20,21);
    $products_arr=array_diff($products_arr,array($product_id));
}

$product_cls1_arr = array(5=>'infocls1',6=>'infocls2',7=>'infocls3',8=>'infocls4',18=>'infocls5',19=>'infocls6',20=>'infocls7',21=>'infocls8',22=>'infocls9');
$product_cls2_arr = array(5=>'infohead1',6=>'infohead2',7=>'infohead3',8=>'infohead4',18=>'infohead5',19=>'infohead6',20=>'infohead7',21=>'infohead8',22=>'infohead9');



$o_res = db_query("SELECT `p`.`product_id`,`p`.`title` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` WHERE `pf`.`folderID`=12 AND `p`.`product_id` IN (".implode(',',$products_arr).") ORDER BY `p`.`title`");





$name=getpropername($product_details['title']);
$id=$product_details['product_id'];
$file="system/themes/vivacity_frontend/images/defaultproduct.png";
if($product_details['img_url']!=''){
    $file=$product_details['img_url'];
}

//$description=$AI->get_defaulted_dynamic_area($product_details['description'],'');
$description=strip_tags($AI->get_defaulted_dynamic_area($product_details['description'],''));

$url11="https://plus.google.com/share?url=".urlencode($repUrl.'/product-info/'.$product_details['product_id'].'/'.getpropername($product_details['title']))."?ai_bypass=true";
//$url11="https://plus.google.com/share?url=https://www.vivacity.com/?ai_bypass=true";
//echo db_num_rows($resblogorderbypriority);


$pinteresturl22= "http://pinterest.com/pin/create/button/?url=".$repUrl."/product-info/".$product_details['product_id']."/".getpropername($product_details['title'])."&media=http://www.vivacitygo.com/".$file."&description=";
function getpropername($title=''){
    $pro_url_title = strtolower(trim($title));
    $pro_url_title = preg_replace("/[^a-z0-9_\s-]/", "", $pro_url_title);
    $pro_url_title = preg_replace("/[\s-]+/", " ", $pro_url_title);
    $pro_url_title = preg_replace("/[\s_]/", "-", $pro_url_title);
    return $pro_url_title;
}
?>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : 207221293015249,
            xfbml      : true,
            version    : 'v2.8'
        });
        FB.AppEvents.logPageView();
    };</script>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>


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
                    <p><?php echo $AI->get_defaulted_dynamic_area($product_details['description'],'');?></p>
                    <p class="viproductlistsinfoinner">
                        <span class="titlespan <?php echo $product_cls2_arr[$product_id];?>">Product Ingredients:</span>

                        <?php echo $AI->get_defaulted_dynamic_area($product_details['ingredients'],'');?>
                    </p>
                    <p><a class="btn btn-default btnbuynowlink" href="/product-details/<?php echo $product_details['product_id'];?>/<?php echo $pro_url_title;?>">Buy Now</a></p>
                    <p class="titlespaninfosmltxt">*These statements have not been evaluated by the Food & Drug Administration. This product is not intended to diagnose, treat,, cure or prevent any disease. </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-sm-12">
                <div class="viproductlistsinfo">
                    <h3 class="<?php echo $product_cls2_arr[$product_id];?>">Benefits</h3>
                    <?php echo $AI->get_defaulted_dynamic_area($product_details['benefits'],'');?>
                    <div class="clearfix"></div>
                    <div class="videoblock1rightblock3">
                        <ul class="list-inline">
                            <li>
                                <a postid="<?php echo $product_details['product_id'] ?>" posttitle="<?php echo $name ?>" postdescription="<?php echo $description ?>" postimage="<?php echo $file ?>" repurl="<?php echo $repUrl;?>" onclick="facebookshareproductinfo(this)" href="javascript:void(0)" >
                                    <img src="system/themes/vivacity_frontend/images/iconfbvideo.png">
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="<?php echo $pinteresturl22 ?>">
                                    <img src="system/themes/vivacity_frontend/images/iconpintvideo.png">
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/intent/tweet?original_referer=<?php echo $repUrl;?>/product-info/<?php echo $product_details['product_id']?>/<?php echo getpropername($product_details['title'])?>&text=<?php echo strip_tags($product_details['title'])?>&tw_p=tweetbutton&url=http://<?php echo strtolower($AI->user->username);?>.vivacitygo.com/product-info/<?php echo $product_details['product_id']?>/<?php echo getpropername($product_details['title'])?>&via=vivacitygo
" target="_blank">
                                    <img src="system/themes/vivacity_frontend/images/icontwvideo.png">
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="<?php echo $url11?>">
                                    <img src="system/themes/vivacity_frontend/images/icongplusvideo.png">
                                </a>
                            </li>
                           <!-- <li>
                                <a href="javascript:void(0)">
                                    <img src="system/themes/vivacity_frontend/images/iconblogvideo.png">
                                </a>
                            </li>-->
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</div>