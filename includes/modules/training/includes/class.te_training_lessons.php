<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-11 08:44:34 by philip
	//DB Table: training_lessons, Unique ID: training_lessons, PK Field: id
   
	require_once( ai_cascadepath( 'includes/core/classes/tableedit_base.php' ) );

	class C_te_training_lessons extends C_tableedit_base
	{
		//(configure) parameters
		var $_dbTableName = 'training_lessons';
		var $_keyFieldName = 'id';
		var $_numeric_key = true;
		var $unique_id = 'training_lessons';
		var $_tableTitle = ''; //default in constructor
		var $draw_qsearch = true;

		//(configure) Draw Code
		var $view_include_file = 'includes/modules/training/includes/draw.training_lessons.view.php';
		var $edit_include_file = 'includes/modules/training/includes/draw.training_lessons.edit.php';
		var $table_include_file = 'includes/modules/training/includes/draw.training_lessons.table.php';
		var $qsearch_include_file = 'includes/modules/training/includes/draw.training_lessons.qsearch.php';
		var $asearch_include_file = 'includes/modules/training/includes/draw.training_lessons.asearch.php';
		var $viewnav_include_file = 'includes/modules/training/includes/draw.training_lessons.viewnav.php';
		var $noresults_include_file = 'includes/modules/training/includes/draw.training_lessons.noresults.php';
		var $ajax_include_file = 'includes/modules/training/includes/handler.training_lessons.ajax.php';

		//(configure) ob stands for "order by" members
		var $_obFieldDefault = ''; //default in constructor
		var $_obDirDefault = "ASC";
		var $_pgSizeDefault = 20;
		var $_te_modeDefault = 'table';
		var $_default_mode_after_save = 'view';
		var $_draw_paging_for_more_than_n_results = 2;
		var $_max_results_2_select_pg_num = 200; //0 to disable
		var $_paging_size_options = array( 5, 10, 20, 50, 100, 200 ); //empty to disable
		var $_unit_label = 'Results';
		var $_table_controls_side = 'left'; // ( left, right )

		// Drag-n-Drop jQuery Sorting
		var $sort_index_field = '';  // If not blank, sorting is enabled using this field as the index
		var $sort_drag_handle_class = ''; // Optional class name of an element to use as handle for sorting (rather than entire row)

		function C_te_training_lessons( $param_dbWhere = '' )
		{
			$this->dbWhere = $param_dbWhere;

			//INITIALIZE DATABASE VARS
			$this->db['id'] = '';
			$this->db['category_id'] = '';
			$this->db['prerequisite_id'] = '';
			$this->db['name'] = '';
			$this->db['access_group'] = '';
			$this->db['acode'] = '';
			$this->db['acode_fail_msg'] = '';
			$this->db['sort_order'] = '';
			

			//INITIALIZE SEARCH VARS
			//these should NOT conflict with database fields above
			$this->search_vars['example_of_a_special_search_var'] = '';

			//INITIALIZE DATABASE DESCRIPTION
			$this->desc['id'] = array( 'Field' => 'id', 'Type' => 'int(11)', 'Null' => '', 'Key' => 'PRI', 'Default' => '', 'Extra' => 'auto_increment' );
			$this->desc['category_id'] = array( 'Field' => 'category_id', 'Type' => 'int(11)', 'Null' => '', 'Key' => '', 'Default' => '0', 'Extra' => '' );
			$this->desc['prerequisite_id'] = array( 'Field' => 'prerequisite_id', 'Type' => 'int(11)', 'Null' => '', 'Key' => '', 'Default' => '0', 'Extra' => '' );
			$this->desc['name'] = array( 'Field' => 'name', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['access_group'] = array( 'Field' => 'access_group', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['acode'] = array( 'Field' => 'acode', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['acode_fail_msg'] = array( 'Field' => 'acode_fail_msg', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['sort_order'] = array( 'Field' => 'sort_order', 'Type' => 'int(11)', 'Null' => '', 'Key' => '', 'Default' => '0', 'Extra' => '' );
			

			//CALL PARENT CLASS CONSTRUCTOR ( creates permissions "$this->perm", etc... )
			parent::C_tableedit_base();

			//SPECIFY MODES ALLOWED FOR INLINE-EDITIBLE FIELDS
			//the value may be 'all', 'table', 'view', or 'none'
			$this->inline_edit_db_field['id'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['category_id'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['prerequisite_id'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['name'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['access_group'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['acode'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['acode_fail_msg'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['sort_order'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			

			//Don't inline edit the primary key
			$this->inline_edit_db_field[ $this->_keyFieldName ] = 'none';
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

			// Automatically generate the sorting index
			if( $this->te_mode == 'insert' || $this->te_mode == 'copy' ) {
				if(trim($this->sort_index_field) != '') {
					$this->writable_db_field[$this->sort_index_field] = true;
					$this->db[$this->sort_index_field] = floor($this->get_max_sort_index()) + 1;
				}
			}
			
			if(isset($_POST['dynamic_area_name'])) {
				$AI->dynamic_areas->inline_save($_POST['dynamic_area_name'], $_POST);
			}

			if(isset($_POST['acode_fail_msg'])) {
				//if it's left empty then delete it (so we can check for empty and display default message)
				
				$content = strip_tags( $AI->dynamic_areas->inline_save($this->db['acode_fail_msg'], $_POST) );
				$content = preg_replace(array('/&.{3,4};/','/[^a-zA-Z]/'),'',$content);
				if($content=='') { $AI->dynamic_areas->delete($AI->dynamic_areas->te_key); $this->db['acode_fail_msg']=''; }
			}

		}

		function finalize_write()
		{
			//OCCURS AFTER SUCCESSFUL DATABASE INSERT OR UPDATE ( te_modes : insert, copy, update, ajax )
		}

		function validate_delete( $delKey )
		{
			//BEFORE DELETE -- return false to abort delete
			return true;
		}

		function finalize_delete( $delKey )
		{
			//AFTER DELETE
		}

		function calcSqlQuery_ASearch()
		{
			$asearch_sql = '';

			//ADD SEARCHES FOR DB FIELDS
			if( $this->search_vars['id'] != '' ){ $asearch_sql .= "AND this.id LIKE '%" . db_in( $this->search_vars['id'] ) . "%' "; }
			if( $this->search_vars['category_id'] != '' ){ $asearch_sql .= "AND this.category_id LIKE '%" . db_in( $this->search_vars['category_id'] ) . "%' "; }
			if( $this->search_vars['prerequisite_id'] != '' ){ $asearch_sql .= "AND this.prerequisite_id LIKE '%" . db_in( $this->search_vars['prerequisite_id'] ) . "%' "; }
			if( $this->search_vars['name'] != '' ){ $asearch_sql .= "AND this.name LIKE '%" . db_in( $this->search_vars['name'] ) . "%' "; }
			if( $this->search_vars['access_group'] != '' ){ $asearch_sql .= "AND this.access_group LIKE '%" . db_in( $this->search_vars['access_group'] ) . "%' "; }
			if( $this->search_vars['acode'] != '' ){ $asearch_sql .= "AND this.acode LIKE '%" . db_in( $this->search_vars['acode'] ) . "%' "; }
			if( $this->search_vars['sort_order'] != '' ){ $asearch_sql .= "AND this.sort_order LIKE '%" . db_in( $this->search_vars['sort_order'] ) . "%' "; }
			

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
			global $AI;
			
			//$AI->dynamic_areas->inline_save($_POST['dynamic_area_name'], $_POST);
			
			if( $element_id == '' ){ $element_id = $fieldname; }

			switch( $fieldname )
			{
				case 'category_id':
				{
					$categories = array();
					$rows = $AI->db->GetAll("SELECT id, name FROM training_categories ORDER BY sort_order, name;");
					if ( isset($rows[0]) )
					{
						foreach ( $rows as $row )
						{
							$categories[$row['id']] = $row['name'];
						}
						echo $this->get_select_from_array($categories, true, $fieldname, $value, $element_id, "Please select a category");
					}
				}
				break;

				case 'prerequisite_id':
				{
					$prerequisites = array();
					$rows = $AI->db->GetAll("SELECT id, name FROM training_lessons WHERE id <> '" . $this->te_key . "' ORDER BY name;");
					if ( isset($rows[0]) )
					{
						foreach ( $rows as $row )
						{
							$prerequisites[$row['id']] = $row['name'];
						}
						echo $this->get_select_from_array($prerequisites, true, $fieldname, $value, $element_id, "Please select a prerequisite");
					} else {
						echo "<i style='color: red'>No Lessons available</i>";
					}
					
				}
				break;

				case 'access_group': {
					$AI->draw_access_groups_select($fieldname,$value, false);
          echo ' <input id="inline_'.$this->te_key.'_access_group"  size="100" maxlength="255" value="" class="" type="hidden">';
				} break;
				
				/* 
				case 'acode':
					echo '<input id="acode" value="'.addslashes($value).'" name="acode" acode_field_id="'.$this->acode_js_id.'" size="20" maxlength="255" type="text" value="" class="acode_field">';
        */  
					?>
          
					<script>
          /*
						$('.acode_field').change(function(){
							val = $(this).val().trim();
							id = $(this).attr('acode_field_id');
							if(val=='') { $('.'+id).slideUp(300); } else { $('.'+id).slideDown(300); }
						});
          */  
					</script>
					<?php
        /* partha : commented  
				break;
        */
				case 'acode_fail_msg':
					if($value==''){ $value = 'training_acode_fail_msg_' . util_rand_string( 10, '0123456789abcdefghijklmnopqrstuvwxyz'); }
					echo '<input type="hidden" name="acode_fail_msg" value="'.h($value).'" />';
					global $AI;
					echo $AI->get_dynamic_area_for(true,$value,'name','',true,true,845,200, true, "You don't have access to this Lesson!");
				break;

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
					case 'id': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
					case 'category_id': {
						$name = db_lookup_scalar("SELECT name FROM training_categories WHERE id = '" . $value . "' ");
						echo $name;
						 // " (#" . $value . ")";
					} break;

					case 'prerequisite_id': {
						$name = db_lookup_scalar("SELECT name FROM training_lessons WHERE id = '" . $value . "' ");
						if(empty($name)) {
							echo "- N/A -";
						} else {
							echo $name;
							//" (#" . $value . ")";
						}
					} break;

					case 'name': { echo util_trim_string( htmlspecialchars( $value ), 35, '..' ) . '&nbsp;'; } break;
					case 'access_group': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
					
					default: { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
				}
			}
			elseif( $mode == 'view' )
			{
        
				switch( $fieldname )
				{
					case 'id': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'category_id': {
						$name = db_lookup_scalar("SELECT name FROM training_categories WHERE id = '" . $value . "' ");
						echo $name;
						// " (#" . $value . ")";
					} break;

					case 'prerequisite_id': {
						$name = db_lookup_scalar("SELECT name FROM training_lessons WHERE id = '" . $value . "' ");
						if(empty($name)) {
							echo "- N/A -";
						} else {
							echo $name;
							// " (#" . $value . ")";
						}
					} break;
					case 'name': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					case 'access_group': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					
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
		
		function get_select_from_array($array, $associative, $fieldname, $value, $element_id = '', $blank_value = null)
		{
			$ret = false;
			if ( count($array) > 0 )
			{
				if ( $element_id == '' )
				{
					$element_id = $fieldname;
				}
				$ret = '<select id="' . $element_id . '" name="' . $fieldname . '">' . "\n";
				if ( $blank_value !== null )
				{
					$ret .= "\t" . '<option value="">' . htmlspecialchars($blank_value) . '</option>' . "\n";
				}
				foreach ( $array as $n => $v )
				{
					$ret .= "\t" . '<option value="' . htmlspecialchars($associative ? $n : $v) . '"';
					if ( $associative && $n == $value || !$associative && $v == $value )
					{
						$ret .= ' selected="selected"';
					}
					$ret .= '>' . htmlspecialchars($v) . '</option>' . "\n";

				}
				$ret .= '</select>' . "\n";
			}
			elseif ( $blank_value !== null )
			{
				$ret = '<select id="' . $element_id . '" name="' . $fieldname . '">' . "\n";
				$ret .= "\t" . '<option value="">' . htmlspecialchars($blank_value) . '</option>' . "\n";
				$ret .= '</select>' . "\n";
			}
			return $ret;
		}




	}//~class C_te_training_lessons extends C_tableedit_base
?>