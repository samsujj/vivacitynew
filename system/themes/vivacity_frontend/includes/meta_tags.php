<?php require(ai_cascadepath('includes/meta_tags.php')); ?>

<?php
global $AI;

$url = 'https://www.vivacitygo.com/';

if($_SERVER['HTTPS'] == 'on'){
    $url = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}else{
    $url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}


if(isset($_GET['ai_q']) && trim($_GET['ai_q']) == 'balance'){
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id = 9");
    $product_desc = $product_desc[0];
    $og_title = $product_desc['title'];
    $og_url = $url;
    if(!empty($product_desc['img_url']))
        $og_image = 'https://www.vivacitygo.com/'.$product_desc['img_url'];
    else
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    $og_description =  $AI->get_defaulted_dynamic_area($product_desc['description'],'');
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_q']) && trim($_GET['ai_q']) == 'synergyinfo'){
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id = 26");
    $product_desc = $product_desc[0];
    $og_title = $product_desc['title'];
    $og_url = $url;
    if(!empty($product_desc['img_url']))
        $og_image = 'https://www.vivacitygo.com/'.$product_desc['img_url'];
    else
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    $og_description =  $AI->get_defaulted_dynamic_area($product_desc['description'],'');
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_q']) && trim($_GET['ai_q']) == 'vibrancyinfo'){
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id = 27");
    $product_desc = $product_desc[0];
    $og_title = $product_desc['title'];
    $og_url = $url;
    if(!empty($product_desc['img_url']))
        $og_image = 'https://www.vivacitygo.com/'.$product_desc['img_url'];
    else
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    $og_description =  $AI->get_defaulted_dynamic_area($product_desc['description'],'');
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_q']) && trim($_GET['ai_q']) == 'vitalityinfo'){
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id = 25");
    $product_desc = $product_desc[0];
    $og_title = $product_desc['title'];
    $og_url = $url;
    if(!empty($product_desc['img_url']))
        $og_image = 'https://www.vivacitygo.com/'.$product_desc['img_url'];
    else
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    $og_description =  $AI->get_defaulted_dynamic_area($product_desc['description'],'');
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_q']) && (trim($_GET['ai_q']) == 'programs' || trim($_GET['ai_q']) == 'vivacity-programs')){
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id IN (9,25,26,27)");
    $product_desc = $product_desc[0];
    $og_title = 'Vivacity Programs';
    $og_url = $url;

    $og_description = 'Vivacity is dedicated to bringing all points together for experiencing the vital essence of an inspired life.We are spreading our message of vibrancy and wellness to every corner of the globe. Our goal is to develop high-character, global-market leaders and to encourage individual personal-development. You can live a life full of vitality, inspiration, and health. We welcome you to join us! ';

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';

    if(count($product_desc)){
        foreach ($product_desc as $row){
            echo '<meta property="og:image" content="https://www.vivacitygo.com/'.$product_desc['img_url'].'" />';
        }
    }else{
        echo '<meta property="og:image" content="https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png" />';
    }

    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';


}else if(isset($_GET['ai_q']) && (trim($_GET['ai_q']) == 'vivacity-products-lists' || trim($_GET['ai_q']) == 'vivacity-products')){
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id IN (18,5,20,7,8,6,21,19)");
    $og_title = 'Vivacity Products';
    $og_url = $url;


    $og_description = 'Vivacity is dedicated to bringing all points together for experiencing the vital essence of an inspired life.We are spreading our message of vibrancy and wellness to every corner of the globe. Our goal is to develop high-character, global-market leaders and to encourage individual personal-development. You can live a life full of vitality, inspiration, and health. We welcome you to join us! ';

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    if(count($product_desc)){
        foreach ($product_desc as $row){
            echo '<meta property="og:image" content="https://www.vivacitygo.com/'.$product_desc['img_url'].'" />';
        }
    }else{
        echo '<meta property="og:image" content="https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png" />';
    }
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_query']) && (trim($_GET['ai_query'][0]) == 'product-details' || trim($_GET['ai_query'][0]) == 'product-info')){
    $productid =  $_GET['ai_query'][1];
    $og_type = 'website';
    $product_desc = $AI->db->GetAll("SELECT * FROM products WHERE product_id = ".$productid);
    $product_desc = $product_desc[0];
    $og_title = $product_desc['title'];
    $og_url = $url;
    if(!empty($product_desc['img_url']))
        $og_image = 'https://www.vivacitygo.com/'.$product_desc['img_url'];
    else
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    $og_description =  $AI->get_defaulted_dynamic_area($product_desc['description'],'');
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_q']) && (trim($_GET['ai_q']) == 'blogs')){
    $og_type = 'website';
    $blog_det = $AI->db->GetAll("SELECT * FROM blogmanager WHERE status=1 ORDER BY time DESC LIMIT 0,1");
    $blog_det = $blog_det[0];
    $og_title = $blog_det['title'];
    $og_url = $url;
    if(!empty($blog_det['file'])) {
        $og_image = 'https://www.vivacitygo.com/uploads/blogmanager/' . $blog_det['file'];
    }else {
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    }

    $og_description =  $blog_det['description'];
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_query']) && (trim($_GET['ai_query'][0]) == 'blogdetails')){
    $blogid =  $_GET['ai_query'][1];
    $og_type = 'website';
    $blog_det = $AI->db->GetAll("SELECT * FROM blogmanager WHERE id = ".$blogid);
    $blog_det = $blog_det[0];
    $og_title = $blog_det['title'];
    $og_url = $url;
    if(!empty($blog_det['file'])) {
        $og_image = 'https://www.vivacitygo.com/uploads/blogmanager/' . $blog_det['file'];
    }else {
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    }

    $og_description =  $blog_det['description'];
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_q']) && (trim($_GET['ai_q']) == 'videos')){
    $og_type = 'website';
    $video_det = $AI->db->GetAll("SELECT * FROM video_manager WHERE status=1 ORDER BY priority ASC");
    $video_det = $video_det[0];
    $og_title = $video_det['title'];
    $og_url = $url;
    if($video_det['type'] == 0) {
        $og_image = 'https://i.ytimg.com/vi/'.$video_det['file'].'/hqdefault.jpg';
    }else {
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    }

    $og_description =  $blog_det['description'];
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}else if(isset($_GET['ai_query']) && (trim($_GET['ai_query'][0]) == 'videodetails')){
    $videoid =  $_GET['ai_query'][1];
    $og_type = 'website';
    $video_det = $AI->db->GetAll("SELECT * FROM video_manager WHERE id=".$videoid);
    $video_det = $video_det[0];
    $og_title = $video_det['title'];
    $og_url = $url;
    if($video_det['type'] == 0) {
        $og_image = 'https://i.ytimg.com/vi/'.$video_det['file'].'/hqdefault.jpg';
    }else {
        $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    }

    $og_description =  $blog_det['description'];
    $og_description = strip_tags($og_description);
    $og_description = trim(preg_replace('/\s+/', ' ', $og_description));

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}/*else{
    $og_title = 'Vivacity';
    $og_type = 'website';
    $og_image = 'https://www.vivacitygo.com/system/themes/vivacity_frontend/images/logo-vivacity.png';
    $og_url = $url;
    $og_description = 'Vivacity is dedicated to bringing all points together for experiencing the vital essence of an inspired life.We are spreading our message of vibrancy and wellness to every corner of the globe. Our goal is to develop high-character, global-market leaders and to encourage individual personal-development. You can live a life full of vitality, inspiration, and health. We welcome you to join us! ';

    echo '<meta name="viewport" content="width=device-width">';
    echo '<meta property="og:title" content="'.$og_title.'" />';
    echo '<meta property="og:type" content="'.$og_type.'" />';
    echo '<meta property="og:image" content="'.$og_image.'" />';
    echo '<meta property="og:url" content="'.$og_url.'" />';
    echo '<meta property="og:description" content="'.$og_description.'" />';

}*/



?>


