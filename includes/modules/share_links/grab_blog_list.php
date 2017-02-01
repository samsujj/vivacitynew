<?php

require_once( ai_cascadepath( 'includes/modules/blog_ad/includes/class.te_blog_ad.php' ) );

$te_share_asset = new C_te_blog_ad();

$te_share_asset->draw_grab_blog_list($_GET['te_share_link_id'],$_GET['te_type']);

?>
