<?php

require_once( ai_cascadepath( 'includes/modules/google_ad/includes/class.te_google_ad.php' ) );

$te_share_asset = new C_te_google_ad();

$te_share_asset->draw_grab_google_ad($_GET['te_share_link_id']);

?>
