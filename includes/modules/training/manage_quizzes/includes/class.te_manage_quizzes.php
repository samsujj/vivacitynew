<?php
//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
//Generated: 2012-03-07 18:49:20 by jason
//DB Table: quiz, Unique ID: manage_quizzes, PK Field: quiz_id

require_once( ai_cascadepath( 'includes/core/classes/tableedit_base.php' ) );

class C_te_manage_quizzes extends C_tableedit_base
{
	//(configure) parameters
	var $_dbTableName = 'quiz';
	var $_keyFieldName = 'quiz_id';
	var $_numeric_key = true;
	var $unique_id = 'manage_quizzes';
	var $_tableTitle = ''; //default in constructor
	var $draw_qsearch = true;
	var $use_te_fields_system = false; // need to remove DB & DESC array definitions. Consider defaulting all draw files to the default tableedit/ draw files
	var $draw_table_menu_not_buttons = false; //displays TE_field options, multidelete, etc under one button
	var $draw_enum_as_select = true;	//don't draw radio buttons

	//(configure) Draw Code
	var $view_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.view.php';
	var $edit_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.edit.php';
	var $table_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.table.php';
	var $qsearch_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.qsearch.php';
	var $asearch_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.asearch.php';
	var $viewnav_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.viewnav.php';
	var $noresults_include_file = 'includes/modules/training/manage_quizzes/includes/draw.manage_quizzes.noresults.php';
	var $ajax_include_file = 'includes/modules/training/manage_quizzes/includes/handler.manage_quizzes.ajax.php';

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

	function C_te_manage_quizzes( $param_dbWhere = '' )
	{
		$this->dbWhere = $param_dbWhere;

		//INITIALIZE DATABASE VARS
		$this->db['quiz_id'] = '';
		$this->db['training_id'] = '';
		$this->db['quiz_title'] = '';
		$this->db['completion_percent'] = '';
		

		//INITIALIZE SEARCH VARS
		//these should NOT conflict with database fields above
		$this->search_vars['example_of_a_special_search_var'] = '';

		//INITIALIZE DATABASE DESCRIPTION
		$this->desc['quiz_id'] = array( 'Field' => 'quiz_id', 'Type' => 'int(11)', 'Null' => '', 'Key' => 'PRI', 'Default' => '', 'Extra' => 'auto_increment' );
		$this->desc['training_id'] = array( 'Field' => 'training_id', 'Type' => 'int(11)', 'Null' => '', 'Key' => '', 'Default' => '0', 'Extra' => '' );
		$this->desc['quiz_title'] = array( 'Field' => 'quiz_title', 'Type' => 'varchar(255)', 'Null' => '', 'Key' => '', 'Default' => '', 'Extra' => '' );
		$this->desc['completion_percent'] = array( 'Field' => 'completion_percent', 'Type' => 'int(3)', 'Null' => '', 'Key' => '', 'Default' => '100', 'Extra' => '' );
		

		//CALL PARENT CLASS CONSTRUCTOR ( creates permissions "$this->perm", etc... )
		parent::C_tableedit_base();

		//SPECIFY MODES ALLOWED FOR INLINE-EDITIBLE FIELDS
		//the value may be 'all', 'table', 'view', or 'none'
		$this->inline_edit_db_field['quiz_id'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
		$this->inline_edit_db_field['training_id'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
		$this->inline_edit_db_field['quiz_title'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
		$this->inline_edit_db_field['completion_percent'] = ( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
		

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
		if( $this->search_vars['quiz_id'] != '' ){ $asearch_sql .= "AND this.quiz_id LIKE '%" . db_in( $this->search_vars['quiz_id'] ) . "%' "; }
		if( $this->search_vars['training_id'] != '' ){ $asearch_sql .= "AND this.training_id LIKE '%" . db_in( $this->search_vars['training_id'] ) . "%' "; }
		if( $this->search_vars['quiz_title'] != '' ){ $asearch_sql .= "AND this.quiz_title LIKE '%" . db_in( $this->search_vars['quiz_title'] ) . "%' "; }
			
		if( $this->search_vars['completion_percent'] != '' ){ $asearch_sql .= "AND this.completion_percent LIKE '%" . db_in( $this->search_vars['completion_percent'] ) . "%' "; }
		
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
			case 'training_id':
				{
					$sql = "SELECT tl.name, tl.id, tc.name AS category_name FROM training_lessons AS tl
					JOIN training_categories AS tc ON tc.id=tl.category_id ";
					$response = db_query($sql);
					echo '<select name="training_id" id="training_id">';
					while($response && $row=db_fetch_assoc($response))
					{
						echo '<option ';
							
						echo  ' value="'.$row["id"].'"';
						if($value==$row['id'])
						{
							echo 'selected=selected';
						}
						echo '>';
						echo $row['name'].' ('. $row['category_name']. ')';
						echo '</option>';
							
					}
					echo '</select>';
					break;
				}
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
				case 'quiz_id': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
				case 'training_id':
					{
						$training_name = db_lookup_scalar("SELECT name FROM training_lessons WHERE id = $value");
						echo util_trim_string( htmlspecialchars( $training_name ), 25, '..' ) . '&nbsp;';

						break;
					}
				case 'quiz_title': { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
					
				default: { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
			}
		}
		elseif( $mode == 'view' )
		{
			switch( $fieldname )
			{
				case 'quiz_id': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
				case 'training_id':
					{
						$training_name = db_lookup_scalar("SELECT name FROM training_lessons WHERE id = $value");
						echo util_trim_string( htmlspecialchars( $training_name ), 25, '..' ) . '&nbsp;';

						break;
					}
				case 'quiz_title': { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
					
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

    function draw_table_contol_buttons()
    {
        if($this->draw_table_menu_not_buttons!==true)
        {

            if ( $this->te_permit['export'] )
            {
                echo '<a class="te_button te_export_button button" href="' . h($this->url('ai_skin=full_page&te_mode=export')) . '" title="Export Current Search Results"><span class="te_button te_export_button">'.t('Export').'</span></a>';
            }
            if( $this->te_permit['multidelete'] ) {
                ?><a class="te_button te_multidelete_button" href="#" onclick="javascript:<?php echo $this->unique_id; ?>_delete_selected(this); return false;" title="Delete Selected Items?"><span class="te_button te_multidelete_button"><?=t('Delete');?></span></a><?php
                ?><input class="te_button te_selectall_checkbox" type="checkbox" name="te_selectall" value="" onchange="javascript:<?php echo $this->unique_id; ?>_process_multiselect_checkbox(this);" onclick="javascript:<?php echo $this->unique_id; ?>_process_multiselect_checkbox(this);" /><?php
            }
            if( aimod_exists($this->te_module) ) { echo '<a class="te_button te_new_button" href="module_manager?open_module_config[]='.$this->te_module.'#'.$this->te_module.'_switch" target="_blank" title="settings"><span class="te_button">'.tt('Module Settings').'</span></a>'; }

            echo '<div id="saving"><p><img src="images/loading.gif" alt="" />'.t('Saving').'...</p></div>';
        }
        else //NEW DRAW MODE
        {
            $multi_select = ($this->te_permit['multidelete'] || @$this->perm->get('multimove'));
            global $AI;

            $AI->skin->css('includes/core/tableedit/table_menu/table_menu.css');
            $AI->skin->js('includes/core/tableedit/table_menu/table_menu.js');

            $str='';
            if($multi_select)
            {
                echo '<input name="te_selectall" id="'.$this->unique_id.'_te_selectall" type="checkbox" style="display:none;">';
                $str.= '<li><img src="images/te/checkbox_on.png" width="15"> <a id="select:1">'.t('Select All').'</a></li>'."\n"
                    . '<li><img src="images/te/checkbox_off.png" width="15"> <a id="select:0">'.t('Select None').'</a></li>'."\n";
                if($this->te_permit['multidelete']) $str .= '<li><img src="images/te/trash_full_ok_16.gif" width="15"> <a id="delete">'.t('Delete Selected').'</a></li>';
                //if($this->perm->get('multimove')) ...
            }
            if($this->te_permit['insert']) {
                if($str!='') $str.= '<li></li>'."\n";
                $str.= '<li><img src="images/te/pencil_add_16.gif" width="15"> <a id="insert">'.t('Add New').'</a></li>'."\n";
            }
            if($this->perm->get('te_fields_update_table') || $this->perm->get('te_fields_update_edit') || $this->perm->get('te_fields_update_view')) {
                if($str!='') $str.= '<li></li>';
                $str.= '<li><img src="images/te/table_fields_48.png" width="15"> '.t('Field Settings').'&nbsp; '
                    . '<ul> ';
                if($this->perm->get('te_fields_update_table')) $str.="\n".'<li><a id="tefields:table">'.t('Table').'</a></li>';
                if($this->perm->get('te_fields_update_view')) $str.="\n".'<li><a id="tefields:view">'.t('View').'</a></li>';
                if($this->perm->get('te_fields_update_edit')) $str.="\n".'<li><a id="tefields:edit">'.t('Edit').'</a></li>';
                $str .= "\n".'</ul>';
            }

            if($str!='')
            { ?>
                <div id="<?=$this->unique_id?>_te_table_xmenu" class="te_table_xmenu" style="width:20px; height:20px; display:none; background:transparent;"><img src="images/te/table_menu_out.png" height="20px">
                    <ul><?=str_replace("\n","\t\n",$str)?></ul>
                </div>

                <script type="text/javascript">
                    <!--
                    $(document).ready(function()
                    {
                        //Table-XMenu Config & Instansiation
                        var options = {minWidth: 120, arrowSrc: 'images/te/arrow_right.gif', onClick: function(e, xmenuItem){
                            action = $(this).find('a[id!=\'\']').attr('id');
                            if(action==null) { return false; }
                            acts=action.split(':');
                            act=acts[0]; val=acts[1];
                            if(act=='insert') window.location='<?=str_replace("'",'\'',$this->url( 'te_mode=insert' ))?>';
                            else if(act=='delete') <?=$this->unique_id?>_delete_selected();
                            else if(act=='select') { chkbx = document.getElementById('<?=$this->unique_id?>_te_selectall'); chkbx.checked=((val=='1')? true:false); <?=$this->unique_id?>_process_multiselect_checkbox(chkbx); }
                            else if(act=='tefields') {
                                //open_jonbox('te_fields.php?ai_skin=full_page&te_class=te_fields&te_mode=table&te_asearch=true&te_class_name=<?=$this->unique_id?>&te_mode_name='+val+'&btnSearch=Search');
                                window.open('te_fields.php?ai_skin=full_page&te_class=te_fields&te_mode=table&te_asearch=true&te_class_name=<?=$this->unique_id?>&te_mode_name='+val+'&btnSearch=Search','te_fields',"menubar=0,resizable=1,scrollbars=1,width=1200,height=600");
                            }
                            $.XMenu.closeAll();
                        }};
                        //initiate it
                        $('#<?=$this->unique_id?>_te_table_xmenu').xmenu(options);
                        $('#<?=$this->unique_id?>_te_table_xmenu').show();
                    });
                    -->
                </script>
                <?php
            }
        }
    }


}//~class C_te_manage_quizzes extends C_tableedit_base
?>