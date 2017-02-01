<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id

	require_once( ai_cascadepath( 'includes/core/classes/tableedit_base.php' ) );

	class C_te_google_ad extends C_tableedit_base
	{
		//(configure) parameters
		var $_dbTableName = 'google_ad';
		var $_keyFieldName = 'id';
		var $_numeric_key = true;
		var $unique_id = 'google_ad';
		var $_tableTitle = ''; //default in constructor
		var $draw_qsearch = false;
		var $use_te_fields_system = false; // need to remove DB & DESC array definitions. Consider defaulting all draw files to the default tableedit/ draw files
		var $draw_table_menu_not_buttons = false; //displays TE_field options, multidelete, etc under one button
		var $draw_enum_as_select = true;	//don't draw radio buttons
		var $init_backbone_js = true;
		//(configure) Draw Code
		var $view_include_file = 'includes/modules/google_ad/includes/draw.google_ad.view.php';
		var $edit_include_file = 'includes/modules/google_ad/includes/draw.google_ad.edit.php';
		var $table_include_file = 'includes/modules/google_ad/includes/draw.google_ad.table.php';
		var $qsearch_include_file = 'includes/modules/google_ad/includes/draw.google_ad.qsearch.php';
		var $asearch_include_file = 'includes/modules/google_ad/includes/draw.google_ad.asearch.php';
		var $viewnav_include_file = 'includes/modules/google_ad/includes/draw.google_ad.viewnav.php';
		var $noresults_include_file = 'includes/modules/google_ad/includes/draw.google_ad.noresults.php';
		var $ajax_include_file = 'includes/modules/google_ad/includes/handler.google_ad.ajax.php';

		//(configure) ob stands for "order by" members
		var $_obFieldDefault = ''; //default in constructor
		var $_obDirDefault = "ASC";
		var $_pgSizeDefault = 20;
		var $_te_modeDefault = 'table';
		var $_default_mode_after_save = 'table';
		var $_draw_paging_for_more_than_n_results = 2;
		var $_max_results_2_select_pg_num = 200; //0 to disable
		var $_paging_size_options = array( 5, 10, 20, 50, 100, 200 ); //empty to disable
		var $_unit_label = 'Results';
		var $_table_controls_side = 'left'; // ( left, right )

		// Drag-n-Drop jQuery Sorting
		var $sort_index_field = 'last_modified_on';  // If not blank, sorting is enabled using this field as the index
		var $sort_drag_handle_class = ''; // Optional class name of an element to use as handle for sorting (rather than entire row)

		function C_te_google_ad( $param_dbWhere = '' )
		{
			$this->dbWhere = $param_dbWhere;

			//INITIALIZE DATABASE VARS
			//$this->db;

			//INITIALIZE SEARCH VARS
			//these should NOT conflict with database fields above
			$this->search_vars['example_of_a_special_search_var'] = '';

			//INITIALIZE DATABASE DESCRIPTION
			//$this->desc;

			//CALL PARENT CLASS CONSTRUCTOR ( creates permissions "$this->perm", etc... )
			parent::C_tableedit_base();

			//SPECIFY MODES ALLOWED FOR INLINE-EDITIBLE FIELDS
			//the value may be 'all', 'table', 'view', or 'none'
			$this->inline_edit_db_field['id'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['user_id'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['share_link_id'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['name'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['type'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['code'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['added_on'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['last_modified_on'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['status'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );

			//Don't inline edit the primary key
			$this->inline_edit_db_field[ $this->_keyFieldName ] = 'none';


			$this->te_permit['insert_google_ad'] = $this->perm->get('insert_google_ad');
		}

		function validate_write()
		{
			global $AI;

			//OCCURS BEFORE DATABASE INSERT OR UPDATE ( te_modes : insert, copy, update, ajax )

			//write errors occur if $this->write_error_msg != '' ( this will allow the user to modify their input )
			$this->write_error_msg = '';

			if( $this->_numeric_key || ( $this->te_mode != 'update' && $this->te_mode != 'insert' ) )
			{
				$this->writable_db_field[ $this->_keyFieldName ] = false;  //don't allow the primary key to be overwritten
			}
			else
			{
				$this->writable_db_field[ $this->_keyFieldName ] = true;
			}

			if($this->te_mode == 'insert') {
				$this->writable_db_field['added_on'] = true;
				$this->db['added_on'] = date('Y-m-d H:i:s');
				$this->writable_db_field['user_id'] = true;
				$this->db['user_id'] = $AI->user->userID;
			}

			if($this->te_mode == 'update') {
				$this->writable_db_field['last_modified_on'] = true;
				$this->db['last_modified_on'] = date('Y-m-d H:i:s');
				$this->writable_db_field['user_id'] = true;
				$this->db['user_id'] = $AI->user->userID;
			}

			$this->writable_db_field['status'] = true;
			$this->db['status'] = 1;


			// Automatically generate the sorting index
			if( $this->te_mode == 'insert' || $this->te_mode == 'copy' ) {
				if(trim($this->sort_index_field) != '') {
					$this->writable_db_field[$this->sort_index_field] = true;
					$this->db[$this->sort_index_field] = floor($this->get_max_sort_index()) + 1;
				}
			}
		}

		function finalize_write()
		{

			//OCCURS AFTER SUCCESSFUL DATABASE INSERT OR UPDATE ( te_modes : insert, copy, update, ajax )

			util_redirect('google-ad-manager?te_share_link_id='.$this->db['share_link_id']);

		}

		function validate_delete( $delKey )
		{
			//BEFORE DELETE -- return false to abort delete
			return true;
		}

		function finalize_delete( $delKey )
		{
			//AFTER DELETE
			util_redirect('google-ad-manager?te_share_link_id='.$this->db['share_link_id']);
		}

		function calcSqlQuery_ASearch()
		{
			$asearch_sql = '';

			//ADD SEARCHES FOR DB FIELDS
			if( $this->search_vars['id'] != '' ){ $asearch_sql .= "AND this.id = '" . db_in( $this->search_vars['id'] ) . "' "; }
			if( $this->search_vars['user_id'] != '' ){ $asearch_sql .= "AND this.user_id = '" . db_in( $this->search_vars['user_id'] ) . "' "; }
			if( $this->search_vars['share_link_id'] != '' ){ $asearch_sql .= "AND this.share_link_id = '" . db_in( $this->search_vars['share_link_id'] ) . "' "; }
			if( $this->search_vars['name'] != '' ){ $asearch_sql .= "AND this.name LIKE '%" . db_in( $this->search_vars['name'] ) . "%' "; }
			if( $this->search_vars['code'] != '' ){ $asearch_sql .= "AND this.code LIKE '%" . db_in( $this->search_vars['code'] ) . "%' "; }
			if( $this->search_vars['added_on'] != '' ){ $asearch_sql .= "AND this.added_on = '" . db_in( $this->search_vars['added_on'] ) . "' "; }
			if( $this->search_vars['last_modified_on'] != '' ){ $asearch_sql .= "AND this.last_modified_on = '" . db_in( $this->search_vars['last_modified_on'] ) . "' "; }
			if( $this->search_vars['status'] != '' ){ $asearch_sql .= "AND this.status = '" . db_in( $this->search_vars['status'] ) . "' "; }

			//example: if( $this->search_vars['example_of_a_special_search_var'] != '' ){ $asearch_sql .= "AND example_of_a_special_search_var LIKE '%" . db_in( $this->search_vars['example_of_a_special_search_var'] ) . "%' "; }

			return $asearch_sql;
		}

		/**
		 * DRAW INPUT FIELDS
		 * $mode : asearch, edit, inline
		 * $element_id : this will default to $fieldname if left blank
		 */
		function draw_input_field( $fieldname, $value, $mode, $element_id = '' )
		{
			if( $element_id == '' ){ $element_id = $fieldname; }

			switch( $fieldname )
			{
				//DRAW THE INPUT FIELD BASED UPON THE DATABASE'S DESCRIBE RESULTS
				default: { $this->draw_input_field_by_desc( $fieldname, $value, $mode, $this->desc[ $fieldname ], $element_id ); } break;
			}

		}

		/**
		 * DRAW VALUE FIELDS
		 */
		function draw_value_field( $fieldname, $value, $key, $mode )
		{
			//IF THEY CAN "INLINE-EDIT" THEN SET IT UP
			if( $this->perm->get('ajax') && ( $this->inline_edit_db_field[ $fieldname ] == $mode || $this->inline_edit_db_field[ $fieldname ] == 'all' ) )
			{
				echo '<div class="te_inline_edit_cell" onclick="javascript:ajax_get_request( \'' . htmlspecialchars($this->ajax_url( 'inline_edit', 'te_key=' . $key . '&fieldname=' . $fieldname . '&view_mode=' . $mode )) . '\', ajax_handler_default );" >';
			}

			//DRAW THE VALUES
			if( $mode == 'table' )
			{
				
				switch( $fieldname )
				{
					
					case 'user_id': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
					case 'share_link_id': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
					case 'name': {echo '<h4>'. htmlspecialchars( $value ). '</h4>'; } break;
					case 'code': { echo htmlspecialchars( $value ) . '</br></br>'; } break;
					case 'code1': { echo htmlspecialchars( $value ) . '</br></br>'; } break;
					case 'url': {
						echo '<a href="'.$value.'" target="_blank">' . $value .'</a></br></br>';
					} break;
					case 'added_on': {
						echo date('m/d/Y',strtotime($value));
					} break;
					case 'status': {
						if($value){
							echo 'Active';
						}else{
							echo 'Inactive';
						}
					} break;

					default: { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
				}
			}
			elseif( $mode == 'view' )
			{
				//echo 888;
				switch( $fieldname )
				{
					case 'user_id': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'share_link_id': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'name': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'code': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;

					default: { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
				}
			}
			else
			{
				echo 'Error: Invalid view mode specified.';
			}

			//IF THEY CAN "INLINE-EDIT" THEN FINISH IT UP
			if( $this->perm->get('ajax') && ( $this->inline_edit_db_field[ $fieldname ] == $mode || $this->inline_edit_db_field[ $fieldname ] == 'all' ) )
			{
				echo '</div>';
			}
		}


		function show_google_ad_value($share_link_id=0,$user_id=0){
			global $AI;

			$google_ad_arr = $AI->db->getAll("SELECT * FROM `google_ad` WHERE user_id = ". (int) db_in($user_id) ." AND share_link_id = " . (int) db_in($share_link_id) . " AND status=1 AND type='Confirmation';");

			if(count($google_ad_arr)){
				foreach($google_ad_arr as $p) {
					echo '<meta name="description" content="'.$p['code'].' '.$p['code1'].'">';
					echo '<meta name="keywords" content="'.$p['name'].'">';
					echo '<meta name="author" content="'.$p['url'].'">';
				}
			}
		}

		function show_google_ad_value_traffic($share_link_id=0,$user_id=0){
			global $AI;

			$google_ad_arr = $AI->db->getAll("SELECT * FROM `google_ad` WHERE user_id = ". (int) db_in($user_id) ." AND share_link_id = " . (int) db_in($share_link_id) . " AND status=1 AND type='Traffic';");

			if(count($google_ad_arr)){
				foreach($google_ad_arr as $p) {
					echo '<meta name="description" content="'.$p['code'].' '.$p['code1'].'">';
					echo '<meta name="keywords" content="'.$p['name'].'">';
					echo '<meta name="author" content="'.$p['url'].'">';
				}
			}
		}


		function get_page_link(){
			$pageid=  @$_GET['te_share_link_id'];

			$link2 = "";

			global $AI;

			$page_arr = $AI->db->getAll("SELECT * FROM `share_links` WHERE id = ". (int) db_in($pageid));

			if(count($page_arr)){
				foreach($page_arr as $p) {
					$value = self::interpret_url($p['url']);
					$link = nl2br(h(util_tracking_url($value, util_cleanup_keystr('share_link_'.$p["name"]), $AI->user->userID, null, util_pretty('share_link_'.$this->db["name"]))));

					$link2 = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
				}
			}

			echo $link2;

		}

		static public function interpret_url( $url_with_merge_codes, $prospect_lead_id = null )
		{
			global $AI;

			$interpreted_url = trim($url_with_merge_codes . '');
			if ( strpos($interpreted_url, '[sub_domain]') !== false )
			{
				$sub_domain = db_lookup_value('multiple_domains_sub', 'owner_id', $AI->user->userID, 'sub_domain');
				if ( empty($sub_domain) )
				{
					$sub_domain = $AI->user->username;
				}
				$interpreted_url = str_replace('[sub_domain]', $sub_domain, $interpreted_url);
			}
			$lead_id = (int) ($prospect_lead_id === null ? db_lookup_value('users', 'userID', $AI->user->userID, 'lead_id') : $prospect_lead_id);
			if ( strpos($interpreted_url, '[lead_id]') !== false )
			{
				if ( empty($lead_id) )
				{
					$lead_id = '';
				}
				$interpreted_url = str_replace('[lead_id]', $lead_id, $interpreted_url);
			}
			if ( strpos($interpreted_url, '[tour_id]') !== false )
			{
				$tour_id = db_lookup_value('success_line', 'lead_id', (int) $lead_id, 'url');
				$interpreted_url = str_replace('[tour_id]', $tour_id, $interpreted_url);
			}
			return $interpreted_url;
		}

		function draw_grab_google_ad($id=0){
			global $AI;

			//echo '<div style="padding:25px 0 0 0; background:#fff; width:100%"><h2 style="width: 100%; text-align: center; margin:0; padding: 5px 0px; font-size: 26px; color: #01A1FC; font-weight: normal;" >Google Adwords</h2></div><div style="width: 100px; height:2px; background: #01A1FC; margin: 2px auto;"></div>';

			$page_arr = $AI->db->getAll("SELECT * FROM `share_links` WHERE id = ". (int) db_in($id));

			$link = '';

			if(count($page_arr)){
				foreach($page_arr as $p) {
					$value = self::interpret_url($p['url']);
					$link = nl2br(h(util_tracking_url($value, util_cleanup_keystr('share_link_'.$p["name"]), $AI->user->userID, null, util_pretty('share_link_'.$this->db["name"]))));
				}
			}

			$google_ad_arr = $AI->db->getAll("SELECT * FROM `google_ad` WHERE share_link_id = " . (int) db_in($id) . " AND status=1;");

			if(count($google_ad_arr)){

				echo '<div style="width: 60%; margin: 25px auto; margin-bottom: 30px; border-bottom: none; background: #fff; font-family: arial, helvetica, sans-serif; text-align: center; ">';

				foreach($google_ad_arr as $p) {
					echo '<div style="border-bottom: solid 1px #ccc; padding-bottom: 10px;">';
					echo '<h4 style="text-decoration: none; font-weight: normal; font-size: 24px; color: #004C92; padding: 15px 0 0 0; margin-bottom: 4px;">'.$p['name'].'</h4>';
					echo '<p style="display: block;  font-size: 14px; line-height: 22px; text-align: center; margin-bottom: 4px;">'.$p['code'].'</p>';
					if(!empty($link))
						echo '<a href="'.$link.'" target="_blank" style="color:#5BA525;">'.$link.'</a>';
					echo '</div>';
				}


				echo '</div>';

			}else{
				echo '<div style="width: 80%; margin: 0px auto; padding: 40px 0; text-align: center; font-size: 24px; border: solid 1px #ccc; color:#ff0000;    background: #fff; ">No Result Found</div>';
			}


		}



	}//~class C_te_share_links extends C_tableedit_base
?>
