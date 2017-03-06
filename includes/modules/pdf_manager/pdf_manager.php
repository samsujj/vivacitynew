<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.te_pdf_manager.php' ) );

require_once ('includes/scripts/vivbackendheader.php');

//echo '<link href="includes/modules/pdf_manager/pdf_manager.css" rel="stylesheet">';
	
	global $AI;


	$dbWhere = "";

	$te_pdf_manager = new C_te_pdf_manager();

	/*$te_pdf_manager->_obFieldDefault = 'id';
	$te_pdf_manager->_obDirDefault = 'DESC';
	$te_pdf_manager->set_session( 'te_obField', $te_pdf_manager->_obFieldDefault );
	$te_pdf_manager->set_session( 'te_obDir', $te_pdf_manager->_obDirDefault );
	$te_pdf_manager->_obField = $te_pdf_manager->get_session( 'te_obField' );
	$te_pdf_manager->_obDir = $te_pdf_manager->get_session( 'te_obDir' );*/

/*	$te_pdf_manager>select($te_pdf_manager->te_key);*/

	$te_pdf_manager->run_TableEdit();

?>
