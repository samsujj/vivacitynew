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

$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`ps`.`stock_item_id`,`ps`.`price` ,`ps`.`alt_prices` FROM `products` `p` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id`=".$product_id);


/*$p_res2 = db_query("SELECT *
FROM  `products2folders` 
WHERE   product_id=".$product_id);


$folderarr=(db_fetch_assoc($p_res2));
//echo $folderarr['folderID'];
//print_r($folderarr);
print_r($folderarr['folderID']);*/
if($product_id==9 || $product_id==25 || $product_id==26 || $product_id==27) $type='Program';
else $type='Product';



$product_details = db_fetch_assoc($p_res);
$altprice=unserialize($product_details['alt_prices']);

$productprice=$product_details['price'];
if(($AI->user->account_type == 'Distributor' || $AI->user->account_type == 'Promoter') && $altprice[0]['price']!=0){
    $productprice= $altprice[0]['price'];
}


$product_images = array();

if(!$product_details){
    util_redirect('/vivacity-products');
}


if(file_exists($product_details['img_url'])){
    $sql = "SELECT f.* FROM files f WHERE f.foreignID = " . (int) $product_id . " AND f.foreign_table = 'products' ORDER BY f.fileID ASC;";

    $s_images = $AI->db->GetAll($sql);

    if ( isset($s_images[0]) )
    {

        foreach ( $s_images as $i => $row )
        {
            $imgurl = 'uploads/files/' . $row['dirname'] . '/' . $row['filename'];
            if(file_exists($imgurl)){
                $product_images[] = $imgurl;
            }
        }
    }else{
        $product_images[]= $product_details['img_url'];
    }

}

if(count($product_images) == 0){
    $product_images[]="system/themes/vivacity_frontend/images/defaultproduct.png";

}
 $name=getpropername($product_details['title']);
 $id=$product_details['product_id'];
$file="system/themes/vivacity_frontend/images/defaultproduct.png";
if($product_details['img_url']!=''){
    $file=$product_details['img_url'];
}

 $description=strip_tags($AI->get_defaulted_dynamic_area($product_details['description'],''));

$url11="https://plus.google.com/share?url=".urlencode($repUrl.'/product-details/'.$product_details['product_id'].'/'.getpropername($product_details['title']))."?ai_bypass=true";
//$url11="https://plus.google.com/share?url=https://www.vivacity.com/?ai_bypass=true";
//echo db_num_rows($resblogorderbypriority);


$pinteresturl22= "http://pinterest.com/pin/create/button/?url=".$repUrl."/product-details/".$product_details['product_id']."/".getpropername($product_details['title'])."&media=http://www.vivacitygo.com/".$file."&description=";
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

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1><?php echo $type; ?> DETAILS</h1>
    </div>
</div>
<div class="container-fluid spblock1">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ribbonblock">
            <div class="ribbon">
                <div class="ribbon-content"><h1><b>ENERGIZE – ENHANCE – INSPIRE</b></h1></div>
            </div>
            <h2>Set your intention. Choose a <?php echo $type; ?>. Live the results.<br>Upgrade to your new, vital life now!</h2>
            <!--products detail block-->
            <div class="spblock1prodcontent spblock1proddetails">
                <div class="row row-eq-height">
                    <!--products detail left block-->
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 spblock1prodcontentleft prodetimgblock">
                        <div class="spblock1prodcontentleftinner">
                            <!--<div class="iconsearch">
                                <img src="system/themes/vivacity_frontend/images/icon-search-productdetail.png">
                            </div>-->
                            <img id="prodetmainimg" src="<?php echo $product_images[0];?>">
                        </div>
                        <div class="spblock1prodimglist">
                            <ul class="spblock1prodgallery">
                                <?php
                                
                                foreach($product_images as $image){
                                    ?>

                                    <li><div class="thumb proimgthumb" style="cursor: pointer;"><img src="<?php echo $image;?>"></div></li>

                                    <?php
                                }
                                
                                ?>
                            </ul>
                        </div><!--/inner-->
                    </div>
                    <!--end products detail left block-->
                    <!--products detail right block-->
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 spblock1prodcontentright">
                        <div class="spblock1prodcontentrightinner">
                            <h1>Vivacity <?php echo $type; ?> - <?php echo $product_details['title'];?></h1>
                            <h4>Quantity
                                <div class="plusminusdiv productquanbloc">
                                    <a href="javascript:void(0)" class="quandec"><span>-</span></a> <input type="text" id="quanvalue" class="form-control" value="1"> <a href="javascript:void(0)" class="quaninc"><span>+</span></a>
                                </div>
                            </h4>
                            <h5>Subtotal : <span>$ <span unitp="<?php echo number_format($productprice,2,'.','')?>" id="subtotalp"><?php echo number_format($productprice,2,'.','')?></span></span></h5>


                            <a class="btn btn-default btnpink" onclick="addtocart('<?php echo $product_details['product_id'];?>',<?php echo $product_details['stock_item_id'];?>)">Add to cart</a>




                            <div class="righthr"></div>
                            <h3><?php echo $type; ?> Info</h3>
                            <div class="productdetaildesc" style="clear: both; text-align: left;"><?php echo $AI->get_defaulted_dynamic_area($product_details['description'],'');?></div>
                            <!--<div class="productattribute">
                                <ul class="list-inline">
                                    <li>NO Sugar</li>
                                    <li>NO Artificial Sweeteners</li>
                                    <li>NO GMOs</li>
                                    <li>NO Artificial Flavors</li>
                                    <li>NO Artificial Colors</li>
                                    <li>NO Preservatives</li>
                                    <li>NO Corn Syrup</li>
                                    <li>NO Yeast, Dairy, Eggs</li>
                                </ul>
                            </div>-->

                        </div>
                        <div class="socialmedialinks">
                            <ul class="list-inline">
                                <li>
                                    <a postid="<?php echo $id ?>" posttitle="<?php echo $name ?>" postdescription="<?php echo $description ?>" postimage="<?php echo $file ?>" repurl="<?php echo $repUrl;?>" onclick="facebookshareproduct(this)" href="javascript:void(0)" >
                                        <img src="system/themes/vivacity_frontend/images/icon-sm-fb.png">
                                    </a></li>
                                <li>
                                    <a target="_blank" href="<?php echo $pinteresturl22 ?>">
                                        <img src="system/themes/vivacity_frontend/images/icon-sm-pint.png">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?original_referer=<?php echo $repUrl;?>/product-details/<?php echo $product_details['product_id']?>/<?php echo getpropername($product_details['title'])?>&text=<?php echo strip_tags($product_details['title'])?>&tw_p=tweetbutton&url=<?php echo $repUrl;?>/product-details/<?php echo $product_details['product_id']?>/<?php echo getpropername($product_details['title'])?>&via=vivacitygo
" target="_blank">
                                        <img src="system/themes/vivacity_frontend/images/icon-sm-tweet.png">
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="<?php echo $url11?>">
                                        <img src="system/themes/vivacity_frontend/images/icon-sm-gplus.png">
                                    </a>
                                </li>
                                <!--<li><a><img src="system/themes/vivacity_frontend/images/icon-sm-blog.png"></a></li>-->
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end products detail right block-->
            </div>
            <!--end products detail block-->
        </div>
    </div>
</div>
