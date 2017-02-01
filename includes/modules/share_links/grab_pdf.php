<?php

require_once( ai_cascadepath( 'includes/modules/pdf_manager/includes/class.te_pdf_manager.php' ) );

$te_pdf_manager = new C_te_pdf_manager();

$te_pdf_manager->draw_grab_pdf();

?>
