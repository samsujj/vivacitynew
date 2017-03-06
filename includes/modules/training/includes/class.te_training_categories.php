<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 16:38:17 by philip
	//DB Table: training_categories, Unique ID: training_categories, PK Field: id

	require_once( ai_cascadepath( 'includes/core/classes/tableedit_base.php' ) );

	class C_te_training_categories extends C_tableedit_base
	{
		//(configure) parameters
		var $_dbTableName = 'training_categories';
		var $_keyFieldName = 'id';
		var $_numeric_key = true;
		var $unique_id = 'training_categories';
		var $_tableTitle = ''; //default in constructor
		var $draw_qsearch = true;

		//(configure) Draw Code
		var $view_include_file = 'includes/modules/training/includes/draw.training_categories.view.php';
		var $edit_include_file = 'includes/modules/training/includes/draw.training_categories.edit.php';
		var $table_include_file = 'includes/modules/training/includes/draw.training_categories.table.php';
		var $qsearch_include_file = 'includes/modules/training/includes/draw.training_categories.qsearch.php';
		var $asearch_include_file = 'includes/modules/training/includes/draw.training_categories.asearch.php';
		var $viewnav_include_file = 'includes/modules/training/includes/draw.training_categories.viewnav.php';
		var $noresults_include_file = 'includes/modules/training/includes/draw.training_categories.noresults.php';
		var $ajax_include_file = 'includes/modules/training/includes/handler.training_categories.ajax.php';

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

		function C_te_training_categories( $param_dbWhere = '' )
		{
			$this->dbWhere = $param_dbWhere;

			//INITIALIZE DATABASE VARS
			$this->db['id'] = '';
			$this->db['name'] = '';
			$this->db['thumbnail'] = '';
			$this->db['access_group'] = '';
			$this->db['sort_order'] = '';


			//INITIALIZE SEARCH VARS
			//these should NOT conflict with database fields above
			$this->search_vars['example_of_a_special_search_var'] = '';

			//INITIALIZE DATABASE DESCRIPTION
			$this->desc['id'] = array( 'Field' => 'id', 'Type' => 'int(11)', 'Null' => '', 'Key' => 'PRI', 'Default' => '', 'Extra' => 'auto_increment' );
			$this->desc['name'] = array( 'Field' => 'name', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['thumbnail'] = array( 'Field' => 'thumbnail', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['access_group'] = array( 'Field' => 'access_group', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
			$this->desc['sort_order'] = array( 'Field' => 'sort_order', 'Type' => 'int(11)', 'Null' => '', 'Key' => '', 'Default' => '0', 'Extra' => '' );


			//CALL PARENT CLASS CONSTRUCTOR ( creates permissions "$this->perm", etc... )
			parent::C_tableedit_base();

			//SPECIFY MODES ALLOWED FOR INLINE-EDITIBLE FIELDS
			//the value may be 'all', 'table', 'view', or 'none'
			$this->inline_edit_db_field['id'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['name'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['thumbnail'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['access_group'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['sort_order'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );


			//Don't inline edit the primary key
			$this->inline_edit_db_field[ $this->_keyFieldName ] = 'none';
		}

		function validate_write()
		{
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
			if( $this->search_vars['name'] != '' ){ $asearch_sql .= "AND this.name LIKE '%" . db_in( $this->search_vars['name'] ) . "%' "; }
			if( $this->search_vars['thumbnail'] != '' ){ $asearch_sql .= "AND this.thumbnail LIKE '%" . db_in( $this->search_vars['thumbnail'] ) . "%' "; }
			if( $this->search_vars['access_group'] != '' ){ $asearch_sql .= "AND this.access_group LIKE '%" . db_in( $this->search_vars['access_group'] ) . "%' "; }
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

			if( $element_id == '' ){ $element_id = $fieldname; }

			switch( $fieldname )
			{
				case 'thumbnail': {
					$this->display_file_upload($this->te_class,$fieldname,$value);
					//$this->draw_input_field_by_desc( $fieldname, $value, $mode, $this->desc[ $fieldname ], $element_id );
				} break;
				case 'access_group': {
					$AI->draw_access_groups_select($fieldname,$value, false);
				} break;

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
					case 'thumbnail':
					{
						if ( empty($value) )
						{
							$value = 'images/spacer.gif';
						}
						echo '<img src="' . h($value) . '" alt="" style="width:48px; height:48px;" />&nbsp;';
					}
					break;

					default: { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
				}
			}
			elseif( $mode == 'view' )
			{
				switch( $fieldname )
				{
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



	}//~class C_te_training_categories extends C_tableedit_base
?>