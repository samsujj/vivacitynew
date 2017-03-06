<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 16:38:17 by philip
	//DB Table: training_categories, Unique ID: training_categories, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );

?>
<script language="javascript" type="text/javascript">
<!--
	function training_categories_delete_selected()
	{
		var frm = document.getElementById('training_categories_table_form');

		if( confirm('Delete Selected Items?') )
		{
			frm.action = '<?php echo $this->url( 'te_mode=multidelete' ); ?>';
			frm.submit();
		}
	}

	function training_categories_process_multiselect_checkbox( selectall_checkbox )
	{
		var frm = document.getElementById('training_categories_table_form');

		for( var i=0; i < frm.elements.length; i++ )
		{
			if( frm.elements[i].type == 'checkbox' && frm.elements[i].name == 'te_multiselect[]' )
			{
				frm.elements[i].checked = selectall_checkbox.checked;
			}

		}
	}

	function training_categories_update_sort_index(table, row)
	{
		// Fix Zebra Stripping
		$("table.te_main_table tr:even").removeClass("te_odd_row").addClass("te_even_row");
		$("table.te_main_table tr:odd").removeClass("te_even_row").addClass("te_odd_row");

		var post_str = $(table).tableDnDSerialize();

		$('#saving').css('display', 'inline');

		// Create a post request
		ajax_post_request('<?= $this->ajax_url('update_sort_index', '') ?>', post_str, ajax_handler_default);
	}
//-->
</script>

<div class="te_table training_categories_table">
	<fieldset class="te">
		<legend class="te">
			<?php echo htmlspecialchars( $this->_tableTitle ); ?>
		</legend>

		<form id="training_categories_table_form" class="te_table_form" name="training_categories_table_form" method="post" action="<?php echo htmlspecialchars($this->url( '' )); ?>">
			<table class="te_main_table training_categories_main_table" id="training_categories_main_table">
				<thead>
					<tr class="te_fieldname_row">
						<?php
							if( $this->_table_controls_side != 'right' ){ echo '<td class="te_button_cell">'; $this->draw_table_contol_buttons(); echo '</td>'; }

							//DRAW LABEL CELLS ( SORT ORDER )
							$this->draw_FieldName('name', 'Name');
							$this->draw_FieldName('thumbnail', 'Thumbnail');
							$this->draw_FieldName('access_group', 'Access Group');
							$this->draw_FieldName('sort_order', 'Sort Order');
							

							if( $this->_table_controls_side == 'right' ){ echo '<td class="te_button_cell">'; $this->draw_table_contol_buttons(); echo '</td>'; }
						?>
					</tr>
				</thead>
				<tbody>
					<?php

						//DRAW DATA ROWS
						$table_row = db_fetch_assoc($table_result);
						for($table_i=0; $table_i < $this->_pgSize && $table_row; $table_i++)
						{
							foreach( $table_row as $n => $v )
							{
								$this->db[$n] = db_out($v);
							}

							echo "\n".'<tr class="te_data_row ' . ( $table_i % 2 == 1 ? 'te_even_row' : 'te_odd_row' ) . '" id="'.htmlspecialchars($this->db[ $this->_keyFieldName ]).'">';

							//DRAW BUTTON COLUMN - FOR THIS ROW
							if( $this->_table_controls_side != 'right' ){ echo "\n".'<td class="te_button_cell">'; $this->draw_row_control_buttons(); echo '</td>'; }

							//DRAW DATA COLUMNS - FOR THIS ROW
							echo "\n".'<!-- Name --> <td class="te_data_cell ' . ( $table_i % 2 == 1 ? 'te_even_cell' : 'te_odd_cell' ) . '" id="value_field_container_' . $this->db[ $this->_keyFieldName ] . '_name">'; $this->draw_value_field( 'name', $this->db['name'].'', $this->db[ $this->_keyFieldName ], 'table' ); echo '</td>';
							echo "\n".'<!-- Thumbnail --> <td class="te_data_cell ' . ( $table_i % 2 == 1 ? 'te_even_cell' : 'te_odd_cell' ) . '" id="value_field_container_' . $this->db[ $this->_keyFieldName ] . '_thumbnail">'; $this->draw_value_field( 'thumbnail', $this->db['thumbnail'].'', $this->db[ $this->_keyFieldName ], 'table' ); echo '</td>';
							echo "\n".'<!-- Access Group --> <td class="te_data_cell ' . ( $table_i % 2 == 1 ? 'te_even_cell' : 'te_odd_cell' ) . '" id="value_field_container_' . $this->db[ $this->_keyFieldName ] . '_access_group">'; $this->draw_value_field( 'access_group', $this->db['access_group'].'', $this->db[ $this->_keyFieldName ], 'table' ); echo '</td>';
							echo "\n".'<!-- Sort Order --> <td class="te_data_cell ' . ( $table_i % 2 == 1 ? 'te_even_cell' : 'te_odd_cell' ) . '" id="value_field_container_' . $this->db[ $this->_keyFieldName ] . '_sort_order">'; $this->draw_value_field( 'sort_order', $this->db['sort_order'].'', $this->db[ $this->_keyFieldName ], 'table' ); echo '</td>';
							

							if( $this->_table_controls_side == 'right' ){ echo "\n".'<td class="te_button_cell">'; $this->draw_row_control_buttons(); echo '</td>'; }

							echo '</tr>';
							$table_row = db_fetch_assoc($table_result);
						}

					?>
				</tbody>
			</table>
		</form>
		<?php
			//DRAW PAGING
			if( $this->_nRows > $this->_draw_paging_for_more_than_n_results )
			{
				echo '<div class="te_paging">';
				$this->draw_Paging();
				echo '</div>';
			}
		?>
	</fieldset>
</div>
