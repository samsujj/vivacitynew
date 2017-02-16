<?php

global $AI;
$products = array();
$p_res = db_query("SELECT `p`.`product_id`,`p`.`title`,`p`.`description`,`p`.`features`,`p`.`img_url`,`p`.`alternate_url`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p` INNER JOIN `products2folders` `pf` ON `p`.`product_id`=`pf`.`product_id` INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `pf`.`folderID`=11 GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");


while($p_res && $product = db_fetch_assoc($p_res)) {
    $product['images'] = array();

    $sql = "SELECT f.* FROM files f WHERE f.foreignID = " . (int) $product['product_id'] . " AND f.foreign_table = 'products' ORDER BY f.fileID ASC;";
    $s_images = $AI->db->GetAll($sql);
    if ( isset($s_images[0]) )
    {
        foreach ( $s_images as $i => $row )
        {
            $imgurl = 'uploads/files/' . $row['dirname'] . '/' . $row['filename'];
            if(file_exists($imgurl)){
                $product['images'][] = $imgurl;
            }
        }
    }

    if(count($product['images']) == 0){
        $product_images[]="system/themes/vivacity_frontend/images/defaultproduct.png";
    }

    $products[] = $product;
}


?>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>Programs</h1>
    </div>
</div>

<div class="container-fluid sprogblock1 hide ">


    <?php

    if(count($products)) {
        foreach ($products as $product) {
?>

            <div class="row row-eq-height sprogblock1signlelists">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sprogblock1left">
                    <img src="<?php echo $product['images'][0];?>">
                    <span class="hrline"></span>
                    <div class="ratingsecure">
                        <ul>
                            <li><img src="system/themes/vivacity_frontend/images/ratingicon.png">2 Ratings & 0 Reviews</li>
                            <li><img src="system/themes/vivacity_frontend/images/secureicon.png">Safe and Secure Payments. Easy returns. 100% Authentic products.</li>
                        </ul>
                    </div>
                    <div class="row row-eq-height featureswrapper">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <h3>Features</h3>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <!--<ul class="featurelists">
                                <li>Lorem Ipsum is simply</li>
                                <li>Lorem Ipsum</li>
                                <li>Lorem Ipsum is simply</li>
                                <li>Lorem Ipsum</li>
                                <li>Lorem Ipsum is simply</li>
                                <li>Lorem Ipsum</li>
                            </ul>-->
                            <?php echo $AI->get_defaulted_dynamic_area($product['features'],'');?>
                        </div>
                    </div>
                    <span class="hrline"></span>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12 sprogblock1middle">
                    <h2><?php echo $product['title'];?></h2>
                    <p><?php echo $AI->get_defaulted_dynamic_area($product['description'],'');?></p>
                    <div class="btnprogram">
                        <?php if(!empty($product['alternate_url'])){ ?>
                        <a class="btnmoreinfo" href="<?php echo $product['alternate_url'];?>">More Info</a>
                        <?php } ?>
                        <a class="btnaddtocart" href="javascript:void(0);" onclick="addtocart1('<?php echo $product['product_id'];?>',<?php echo $product['stock_item_id'];?>)">Add To Cart</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="programimgright">
                        <div class="row row-eq-height">

                            <?php
                                if(count($product['images'])){
                                    foreach ($product['images'] as $key=>$images){
                                        if($key > 0){
                                        ?>

                                        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4 programimgrightimg1">
                                            <div class="imgprogramwrapper">
                                                <img src="<?php echo $images;?>">
                                            </div>
                                        </div>

                                        <?php
                                        }
                                    }
                                }
                            ?>


                        </div>
                    </div>
                </div>
            </div>


            <?php
        }
    }

    ?>


</div>


<div class="container containerwrapper containerwrapper_opportunity ">


    <div class="opportunity_block_con">
        <div class="opp_div1">
            <img src="uploads/files/tn/kw/e5/g3/2z/9balancepackage.png">
        </div>
        <div class="opp_div2">
            <h2>BALANCE PROGRAM</h2>
            <h3>Live Well:  BALANCE Your Being.  BALANCE is the ultimate supplement formulated for amplified body-equilibrium. BALANCE works as an essential element to experiencing The Shift by assisting the necessary preparation of body and mind for experiencing lasting success in our VioPhaze and VioFit programs. That is why we established it as the foundational first-half of our 4-step total wellness philosophy!
                <br><br>
                Now you can benefit more from your body’s natural 24-hour cycle.
            </h3>
        </div>
        <div class="opp_div3">
            <h4>Benefits</h4>
            <!-- <h5>Decrease pain from*</h5>-->
            <ul>
                <!--<li>Osteoarthritis</li>
                <li>Rheumatoid Arthritis</li>
                <li>Bursitis</li>
                <li>Gout</li>
                <li>Abnormal Posture</li>
                <li>Strains and Sprains</li>
                <li>Repetitive Motion</li>-->

                <li>Blood Sugar Control</li>
                <li>Increased Energy</li>
                <li>Boost Immune System</li>
                <li>Antioxidant</li>
                <li>Improved Attention Span</li>
                <li> Promotes Hormonal Balance</li>
                <li> Reduces Free Radicals</li>
                <li> Improves Sleep</li>
                <!--<li> Increases Benefits Sleep</li>
                <li>Increases Serotonin</li>-->

            </ul>
        </div>
        <div class="opp_div4">
            <img src="system/themes/vivacity_frontend/images/ad_opportunity_img1.jpg" class="opportunity_proimg">
            <a href="balance" class="opplink_pro1 ">More Info</a>
            <a href="product-details/9/balance-program"  align="center" class="opplink_pro2 bynowoppo" id="bynowoppol191" stock_item_id="11" ptitle="BALANCE PROGRAM" price="123.75">Buy Now</a>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

        <div class="modal fade programopModablock" id="programopModal1" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">BALANCE PROGRAM</h4>
                    </div>
                    <div class="modal-body">
                        <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" class="opportunity_logoimg">

                        <h3>
                            <img src="uploads/files/tn/kw/e5/g3/2z/9balancepackage.png">

                            Live Well:  BALANCE Your Being.  BALANCE is the ultimate supplement formulated for amplified body-equilibrium. BALANCE works as an essential element to experiencing The Shift by assisting the necessary preparation of body and mind for experiencing lasting success in our VioPhaze and VioFit programs. That is why we established it as the foundational first-half of our 4-step total wellness philosophy!
                            <br><br>
                            Now you can benefit more from your body’s natural 24-hour cycle.
                        </h3>
                        <div class="clearfix"></div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="opportunity_block_con">
        <div class="opp_div1">
            <img src="uploads/files//26synergy_1_.png">
        </div>
        <div class="opp_div2">
            <h2>SYNERGY PROGRAM</h2>
            <h3>The SYNERGY program has been formulated with your mental and emotional health in mind. SYNERGY is the ultimate upgrade to BALANCE for those who wish to get the most from the <i>24-Hour Core Dynamics System</i>. Say goodbye to stress and distraction with SYNERGY! One of the biggest reliefs Americans seek today is a reprieve from the effects of stress.<br>
                <br>
                SYNERGY combines BALANCE with ACUITY and SERENITY to provide the perfect balance of body, mind and emotions. <!--SYNERGY and aids BALANCE in helping your reach homeostasis. Feel clear and relaxed with SYNERGY.--></h3>
        </div>
        <div class="opp_div3">
            <h4>Benefits</h4>
            <!--<h5>Decrease pain from*</h5>-->
            <ul>
                <!-- <li>Osteoarthritis</li>
                 <li>Rheumatoid Arthritis</li>
                 <li>Bursitis</li>
                 <li>Gout</li>
                 <li>Abnormal Posture</li>
                 <li>Strains and Sprains</li>
                 <li>Repetitive Motion</li>-->
                <li>Increases Clarity</li>
                <li>Promotes Focus</li>
                <li>Aids in Learning</li>
                <li>Increases Memory</li>
                <li>Increases Wellbeing</li>
                <li> Promotes Calm</li>
                <li>Relieves Stress</li>
                <li> Lowers Cortisol Levels</li>


            </ul>
        </div>
        <div class="opp_div4">
            <img src="system/themes/vivacity_frontend/images/ad_opportunity_img2.jpg" class="opportunity_proimg">
            <a href="synergyinfo" class="opplink_pro1 " >More Info</a>
            <a href="product-details/26/synergy-program"  class="opplink_pro2 bynowoppo" id="bynowoppo39" stock_item_id="39" ptitle="SYNERGY PROGRAM" price="248.75">buy now</a>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

        <div class="modal fade programopModablock" id="programopModal2" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">SYNERGY PROGRAM</h4>
                    </div>
                    <div class="modal-body">
                        <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" class="opportunity_logoimg">

                        <h3> <img src="uploads/files//26synergy_1_.png"> The SYNERGY program has been formulated with your mental and emotional health in mind. SYNERGY is the ultimate upgrade to BALANCE for those who wish to get the most from the <i>24-Hour Core Dynamics System</i>. Say goodbye to stress and distraction with SYNERGY! One of the biggest reliefs Americans seek today is a reprieve from the effects of stress.<br>
                            <br>
                            SYNERGY combines BALANCE with ACUITY and SERENITY to provide the perfect balance of body, mind and emotions. SYNERGY and aids BALANCE in helping your reach homeostasis. Feel clear and relaxed with SYNERGY.</h3>
                        <div class="clearfix"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="opportunity_block_con">
        <div class="opp_div1">
            <img src="uploads/files//27vibrancy2.png">
        </div>
        <div class="opp_div2">
            <h2>VIBRANCY PROGRAM</h2>
            <h3>VIBRANCY springboards you from great health to incredible vitality. Elevate your health to new heights! Uplift your wellbeing. Imagine if you could store the fountain of youth in a bottle and take it with you wherever you go!<br>
                <br>

                By combining the beneficial features of BALANCE (the foundation of our <i>24-Hour Core Dynamics System</i>), along with the powerful energy boost sparked by IGNITE EFC, and the additional benefits of REPLENISH… </h3>
        </div>
        <div class="opp_div3">
            <h4>Benefits</h4>
            <!-- <h5>Decrease pain from*</h5>-->
            <ul>
                <!--<li>Osteoarthritis</li>
                <li>Rheumatoid Arthritis</li>
                <li>Bursitis</li>
                <li>Gout</li>
                <li>Abnormal Posture</li>
                <li>Strains and Sprains</li>
                <li>Repetitive Motion</li>-->
                <li>Increases Focus</li>
                <li> Promotes Mental Clarity</li>
                <li> Provides Lasting Energy</li>
                <li> Enhances Mood</li>
                <li> Beautifies Skin</li>
                <li>Increases Total Body Health</li>
                <li>Reinvigorates Cells</li>
                <li>Promotes Cellular Healing</li>


            </ul>
        </div>
        <div class="opp_div4">
            <img src="system/themes/vivacity_frontend/images/ad_opportunity_img3.jpg" class="opportunity_proimg">
            <a href="vibrancyinfo" class="opplink_pro1 ">More Info</a>
            <a href="product-details/27/vibrancy-program"  class="opplink_pro2 bynowoppo" id="bynowoppo40" stock_item_id="40" ptitle="VIBRANCY PROGRAM" price="248.75">buy now</a>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

        <div class="modal fade programopModablock" id="programopModal3" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">VIBRANCY PROGRAM</h4>
                    </div>
                    <div class="modal-body">
                        <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" class="opportunity_logoimg">

                        <h3>  <img src="uploads/files//27vibrancy2.png"> VIBRANCY springboards you from great health to incredible vitality. Elevate your health to new heights! Uplift your wellbeing. Imagine if you could store the fountain of youth in a bottle and take it with you wherever you go!<br>
                            <br>

                            By combining the beneficial features of BALANCE (the foundation of our <i>24-Hour Core Dynamics System</i>), along with the powerful energy boost sparked by IGNITE EFC, and the additional benefits of REPLENISH…</h3>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="opportunity_block_con">
        <div class="opp_div1">
            <img src="uploads/files//25vitality_.png">
        </div>
        <div class="opp_div2">
            <h2>VITALITY PROGRAM</h2>
            <h3>VITALITY is the premier package for your body’s maintenance. When combined with BALANCE, VITALITY is fortified with <i>Vivacity’s 24-Hour Core Dynamics System</i>. Reach beyond your physical limits with VITALITY. VITALITY rids the body of sugar and removes inflammation. Upgrade to the VITALITY program and we know you will feel great! Your body will thank us for VITALITY.<br>
                <br>

                VITALITY aids in digestion, making your internal ecosystem work as it should.  Increase your performance and boost your energy with VITALITY. </h3>
        </div>
        <div class="opp_div3">
            <h4>Benefits</h4>
            <!-- <h5>Decrease pain from*</h5>-->
            <ul>
                <!-- <li>Osteoarthritis</li>
                 <li>Rheumatoid Arthritis</li>
                 <li>Bursitis</li>
                 <li>Gout</li>
                 <li>Abnormal Posture</li>
                 <li>Strains and Sprains</li>
                 <li>Repetitive Motion</li>-->

                <li>Aids in Weight Loss</li>
                <li>Balances Blood Sugar</li>
                <li> Increase Insulin Production</li>
                <li>Reduce Cravings</li>
                <li>Reduces Inflammation</li>
                <li>Decreases Swelling</li>
                <li> Neutralizes Free Radicals</li>
                <li>Reduces Pain</li>
                <!-- <li>Soothes Arthritis</li>-->
            </ul>
        </div>
        <div class="opp_div4">
            <img src="system/themes/vivacity_frontend/images/ad_opportunity_img4.jpg" class="opportunity_proimg">
            <a href="vitalityinfo" class="opplink_pro1 " );">More Info</a>
            <a href="product-details/25/vitality-program"  class="opplink_pro2 bynowoppo" id="bynowoppo38" stock_item_id="38" ptitle="VITALITY PROGRAM" price="248.75">buy now</a>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

        <div class="modal fade programopModablock" id="programopModal4" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">VITALITY PROGRAM</h4>
                    </div>
                    <div class="modal-body">

                        <img src="system/themes/vivacity_frontend/images/logo-vivacity.png" class="opportunity_logoimg">

                        <h3> <img src="uploads/files//25vitality_.png"> VITALITY is the premier package for your body’s maintenance. When combined with BALANCE, VITALITY is fortified with <i>Vivacity’s 24-Hour Core Dynamics System</i>. Reach beyond your physical limits with VITALITY. VITALITY rids the body of sugar and removes inflammation. Upgrade to the VITALITY program and we know you will feel great! Your body will thank us for VITALITY.<br>
                            <br>

                            VITALITY aids in digestion, making your internal ecosystem work as it should.  Increase your performance and boost your energy with VITALITY. </h3>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>