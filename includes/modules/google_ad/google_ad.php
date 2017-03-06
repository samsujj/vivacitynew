<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.te_google_ad.php' ) );

require_once ('includes/scripts/vivbackendheader.php');

//echo '<link href="includes/modules/google_ad/google_ad.css" rel="stylesheet">';
	
	global $AI;

	$dbWhere ='';
	if(@$_GET['te_mode'] != 'insert' && @$_GET['te_mode'] != 'update'){
		$dbWhere .= "  share_link_id=".@$_GET['te_share_link_id'];
	}

	$te_google_ad = new C_te_google_ad(@$dbWhere);

	$te_google_ad->_obFieldDefault = 'last_modified_on';
	$te_google_ad->_obDirDefault = 'DESC';
	$te_google_ad->set_session( 'te_obField', $te_google_ad->_obFieldDefault );
	$te_google_ad->set_session( 'te_obDir', $te_google_ad->_obDirDefault );
	$te_google_ad->_obField = $te_google_ad->get_session( 'te_obField' );
	$te_google_ad->_obDir = $te_google_ad->get_session( 'te_obDir' );
	
	$te_google_ad->select($te_google_ad->te_key);

	$te_google_ad->run_TableEdit();
?>
