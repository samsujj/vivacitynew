<?php

require_once( ai_cascadepath( 'includes/modules/share_asset/includes/class.te_share_asset.php' ) );

$te_share_asset = new C_te_share_asset();

$te_share_asset->draw_image_list($_GET['te_share_link_id'],$_GET['te_type']);

?>
