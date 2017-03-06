<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.te_pixel.php' ) );
require_once ('includes/scripts/vivbackendheader.php');

//echo '<link href="includes/modules/pixel/pixel.css" rel="stylesheet">';
	
	global $AI;

	$dbWhere = " user_id=".$AI->user->userID;
	if(@$_GET['te_mode'] != 'insert' && @$_GET['te_mode'] != 'update'){
		$dbWhere .= " AND share_link_id=".@$_GET['te_share_link_id'];
	}

	$te_pixel = new C_te_pixel(@$dbWhere);

	$te_pixel->_obFieldDefault = 'last_modified_on';
	$te_pixel->_obDirDefault = 'DESC';
	$te_pixel->set_session( 'te_obField', $te_pixel->_obFieldDefault );
	$te_pixel->set_session( 'te_obDir', $te_pixel->_obDirDefault );
	$te_pixel->_obField = $te_pixel->get_session( 'te_obField' );
	$te_pixel->_obDir = $te_pixel->get_session( 'te_obDir' );
	
	$te_pixel->select($te_pixel->te_key);

	$te_pixel->run_TableEdit();
?>
