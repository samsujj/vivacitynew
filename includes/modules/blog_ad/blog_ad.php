<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.te_blog_ad.php' ) );

require_once ('includes/scripts/vivbackendheader.php');

//echo '<link href="includes/modules/blog_ad/blog_ad.css" rel="stylesheet">';

	global $AI;

	if(@$_GET['te_mode'] != 'insert' && @$_GET['te_mode'] != 'update'){
		$dbWhere = " share_link_id=".@$_GET['te_share_link_id'];
		$dbWhere .= " AND type='".@$_GET['s_type']."'";
	}

	$te_blog_ad = new C_te_blog_ad(@$dbWhere);

	$te_blog_ad->_obFieldDefault = 'id';
	$te_blog_ad->_obDirDefault = 'DESC';
	$te_blog_ad->set_session( 'te_obField', $te_blog_ad->_obFieldDefault );
	$te_blog_ad->set_session( 'te_obDir', $te_blog_ad->_obDirDefault );
	$te_blog_ad->_obField = $te_blog_ad->get_session( 'te_obField' );
	$te_blog_ad->_obDir = $te_blog_ad->get_session( 'te_obDir' );
	
	$te_blog_ad->select($te_blog_ad->te_key);

	$te_blog_ad->run_TableEdit();
?>
