<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id

	require_once( ai_cascadepath( 'includes/core/classes/tableedit_base.php' ) );

	class C_te_pdf_manager extends C_tableedit_base
	{

		var $upload_dir = 'uploads/pdf_manager/';

		//(configure) parameters
		var $_dbTableName = 'pdf_manager';
		var $_keyFieldName = 'id';
		var $_numeric_key = true;
		var $unique_id = 'pdf_manager';
		var $_tableTitle = ''; //default in constructor
		var $draw_qsearch = false;
		var $use_te_fields_system = false; // need to remove DB & DESC array definitions. Consider defaulting all draw files to the default tableedit/ draw files
		var $draw_table_menu_not_buttons = false; //displays TE_field options, multidelete, etc under one button
		var $draw_enum_as_select = true;	//don't draw radio buttons
		var $init_backbone_js = true;
		//(configure) Draw Code
		var $view_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.view.php';
		var $edit_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.edit.php';
		var $table_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.table.php';
		var $qsearch_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.qsearch.php';
		var $asearch_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.asearch.php';
		var $viewnav_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.viewnav.php';
		var $noresults_include_file = 'includes/modules/pdf_manager/includes/draw.pdf_manager.noresults.php';
		var $ajax_include_file = 'includes/modules/pdf_manager/includes/handler.pdf_manager.ajax.php';

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
		var $sort_index_field = 'id';  // If not blank, sorting is enabled using this field as the index
		var $sort_drag_handle_class = ''; // Optional class name of an element to use as handle for sorting (rather than entire row)

		function C_te_pdf_manager( $param_dbWhere = '' )
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
			$this->inline_edit_db_field['name'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['url'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['status'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );

			//Don't inline edit the primary key
			$this->inline_edit_db_field[ $this->_keyFieldName ] = 'none';


			$this->te_permit['insert_pdf_manager'] = $this->perm->get('insert_pdf_manager');
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

			/*if($this->te_mode == 'insert') {
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
			}*/


			require_once( ai_cascadepath('includes/core/classes/file.php') );
			$f = new C_file();

			$rand = rand()."_".time();


			if( isset( $_FILES['file_upload'] ) && $f->receive('file_upload') )
			{
				$f_parts = pathinfo($f->name);

				if(strtolower($f_parts['extension']) == 'pdf'){
					$fname = util_cleanup_string( $f->name, '_', 'abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.' );

					$fname = $rand."_".$fname;
					// chmod( $this->upload_dir . $fname, 0644 );

					if($f->make_dir( $this->upload_dir ) && $f->save(  $this->upload_dir.$fname )) {
						$AI->db->Update('pdf_manager', array( 'url' => $fname ), "id=" . (int)db_in( $this->te_key ) );
					}
					else {
						$this->write_error_msg = "File not saved. " . $f->errorText;
					}

				}else{
					$this->write_error_msg = "Upload Only PDF File";
				}

			}

			if( isset( $_FILES['file_upload']['name'] ) && trim( $_FILES['file_upload']['name'] ) != '' )
			{
				$fname = util_cleanup_string( stripslashes($_FILES['file_upload']['name']), '_', 'abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.' );
				$fname = $rand."_".$fname;
				if( file_exists( $this->upload_dir.$fname ) )
				{
					$this->db['url'] =  $fname;
					//$this->write_error_msg = 'That filename already exists, please rename your file before uploading.';
				}
			}

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

			//util_redirect('pdf-manager');

		}

		function validate_delete( $delKey )
		{
			//BEFORE DELETE -- return false to abort delete
			return true;
		}

		function finalize_delete( $delKey )
		{
				//util_redirect('share_asset?te_share_link_id='.$this->db['share_link_id'].'&type='.$type);
		}

		function calcSqlQuery_ASearch()
		{
			$asearch_sql = '';

			//ADD SEARCHES FOR DB FIELDS
			if( $this->search_vars['id'] != '' ){ $asearch_sql .= "AND this.id = '" . db_in( $this->search_vars['id'] ) . "' "; }
			if( $this->search_vars['name'] != '' ){ $asearch_sql .= "AND this.name = '" . db_in( $this->search_vars['name'] ) . "' "; }

			return $asearch_sql;
		}

		/**
		 * DRAW INPUT FIELDS
		 * $mode : asearch, edit, inline
		 * $element_id : this will default to $fieldname if left blank
		 */
		function draw_input_field( $fieldname, $value, $mode, $element_id = '' )
		{
			global $AI;
			if( $element_id == '' ){ $element_id = $fieldname; }

			switch( $fieldname )
			{
				//DRAW THE INPUT FIELD BASED UPON THE DATABASE'S DESCRIBE RESULTS
				case 'url': {
					$AI->skin->js('includes/modules/pdf_manager/pdf_manager.js');
					echo '<input type="hidden" name="url" id="url" value="' . htmlspecialchars( $value ) . '">';
					if( $value != '' )
					{
						echo 'Keep this file: ' . htmlspecialchars($value) . '?<br>Or select new file: ';
					}
					echo '<input type="file" name="file_upload" id="file_upload" value="">';
					//echo '<p style="font-weight:bold">The images should be a square aspect ratio and will be shrunk to 250x250</p>';
				} break;
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
					
					/*case 'user_id': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
					case 'share_link_id': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;*/
					case 'name': {echo '<h4>'. htmlspecialchars( $value ). '</h4>'; } break;
					case 'url': {echo '<h4><a href="'.$this->upload_dir. $value . '" target="_blank">Download PDF</a></h4>'; } break;
					case 'status': {echo ($value)?'Active':'Inactive'; } break;

					default: { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
				}
			}
			elseif( $mode == 'view' )
			{
				//echo 888;
				switch( $fieldname )
				{
					/*case 'user_id': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'share_link_id': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'name': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'pixel_value': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;*/

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


		function draw_image_list($id=0,$type=""){
			global $AI;

			if (util_rep_id() == 0) {
				$rep_name = "top";
			}elsE{
				$rep_data = db_lookup_assoc("SELECT username FROM users WHERE userID = " . (int) util_rep_id() . " LIMIT 1;");
				if(count($rep_data)>0) {
					$rep_name = $rep_data['username'];
				}else{
					$rep_name = "top";
				}
			}

			if($type == 2){
				$s_type = 'Display Ads';
			}else if($type == 3){
				$s_type = 'Mobile Ads';
			}else{
				$s_type = 'Banner Ads';
			}

			$share_arr = $AI->db->getAll("SELECT * FROM `share_links` WHERE id = " . (int) db_in($id));

			$share_link_url = $share_arr[0]['url'];

			$share_link_url = str_replace('[sub_domain]', $rep_name, $share_link_url);

			$share_link_url = nl2br(h(util_tracking_url($share_link_url, util_cleanup_keystr('share_link_'.$share_arr[0]['name']), $AI->user->userID, null, util_pretty('share_link_'.$share_arr[0]['name']))));

			

			$pixel_arr = $AI->db->getAll("SELECT * FROM `share_asset` WHERE share_link_id = " . (int) db_in($id) . " AND status=1 AND type='".$s_type."';");

			echo '<div style="padding:25px 0; background:#fff; width:100%">
<h2 style="width: 100%; text-align: center; margin:0; padding: 5px 0px; font-size: 26px; color: #333;" >Banners</h2>
<div style="width: 180px; height:2px; background: #333; margin:0 auto;"></div>
</div>';

			if(count($pixel_arr)){




				echo '<div style="width: 100%; margin: 0px auto; margin-bottom: 30px; border-bottom: none; background: #fff; ">';

				foreach($pixel_arr as $p) {

					$img_url = (file_exists($p['url']))?'<img src="http://www.epiclyfe.com/image?imgurl='.$p['url'].'&w='.$p['width'].'&h='.$p['height'].'&ar=1&e=0&cr=0" style="border: solid 1px #ccc; display:block; margin:0 auto; max-width:100%" />':'';

					echo '<div style="width: 96%; padding:20px  2% 5px; border-bottom: solid 1px #ccc;">';
					echo $img_url;
					echo '<textarea style="width:90%; box-shadow: none; display: block;  min-height: 20px; border: solid 1px #ccc; resize: none; margin: 5px auto; max-width: 430px; height: 110px;"><a href="'.$share_link_url.'" target="_blank"><img src="http://www.epiclyfe.com/image?imgurl='.$p['url'].'&w='.$p['width'].'&h='.$p['height'].'&ar=1&e=0&cr=0" alt="'.$p['name'].'" border="0" /></a></textarea>';
					//echo '<button class="info clip_button" style="height:30px; padding-top: 5px; display: block; margin: 0 auto;" href_txt="cbv cvb">Grab Copy</button>';
					echo '</div>';


				}


			}else{
				echo '<div style="width: 80%; margin: 0px auto; padding: 40px 0; text-align: center; font-size: 24px; border: solid 1px #ccc; color:#ff0000;    background: #fff; ">No Banner Found</div>';
			}

			echo "</div>";
		}

		function draw_grab_pdf(){

			global $AI;

			//echo '<div style="padding:25px 0; background:#fff; width:100%"><h2 style="width: 100%; text-align: center; margin:0; padding: 5px 0px; font-size: 26px; color: #333; text-decoration: underline;" >Grab PDF</h2></div>';

			$pdf_manager_arr = $AI->db->getAll("SELECT * FROM `pdf_manager` WHERE status=1");

			if(count($pdf_manager_arr)){




				echo '<div style="width: 100%; margin: 0px auto; margin-bottom: 30px; border-bottom: none; background: #fff; ">';

				foreach($pdf_manager_arr as $p) {

					echo '<div style="width: 96%; margin: 0 auto; padding:10px  2%; border-bottom: solid 1px #ccc; text-align: center;">';
					//echo '<h1>'.$p['title'].'</h1>';

					echo '<h4 style="text-decoration: none; font-weight: normal; font-size: 24px; color: #004C92; padding: 15px 0 0 0; margin: 4px 0 0 0;">'.$p['name'].'</h4>';
					echo '<p style="width:90%; box-shadow: none; display: block;  resize: none; margin: 2px auto; max-width: 60%; height: auto;">'.$p['description'].'</p>';
					echo '<p style="width:90%; box-shadow: none; display: block;  resize: none; margin: 2px auto; max-width: 60%; height: auto;"><a href="'.$this->upload_dir. $p['url'] . '" target="_blank">Download PDF</a></p>';
					//echo '<button class="info clip_button" style="height:30px; padding-top: 5px; display: block; margin: 0 auto;" href_txt="cbv cvb">Grab Copy</button>';
					echo '</div>';


				}


			}else{
				echo '<div style="width: 80%; margin: 0px auto; padding: 40px 0; text-align: center; font-size: 24px; border: solid 1px #ccc; color:#ff0000;    background: #fff; ">No Result Found</div>';
			}

		}


	}//~class C_te_share_links extends C_tableedit_base
?>
