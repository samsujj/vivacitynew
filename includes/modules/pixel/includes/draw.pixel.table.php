<?php

global $AI;

// Merge Codes
$sub_domain = $AI->user->username;

?>

<script language="javascript" type="text/javascript">
<!--
	function share_links_update_sort_index(table, row)
	{
		// Fix Zebra Stripping
		//$("table.te_main_table tr:even").removeClass("te_odd_row").addClass("te_even_row");
		//$("table.te_main_table tr:odd").removeClass("te_even_row").addClass("te_odd_row");

		var post_str = $(table).tableDnDSerialize();
		//$('#saving').css('display', 'inline');

		// Create a post request
		ajax_post_request('<?= $this->ajax_url('update_sort_index', '') ?>', post_str, ajax_handler_default);
	}
-->
</script>

<?php

/*if ( @$this->te_permit['insert_share_link'] )
{*/
echo'<div class="sharelinkmainwrapper">';
	echo '<button onclick="document.location = \'' . h($this->url('te_mode=insert&te_share_link_id='.@$_GET['te_share_link_id'])) . '\'; return false;">New</button>';
	echo '<button onclick="document.location = \'share_links\'; return false;">Back</button>';
/*}*/

$share_arr = $AI->db->getAll("SELECT * FROM `share_links` WHERE id = " . (int) db_in($_GET['te_share_link_id']));

$share_link_name = @$share_arr[0]['name'];


echo "<h2>Pixel Manager :: ".$share_link_name."</h2>";
echo '<p>&nbsp;</p><!--spacer-->';

$lead_id = (int) db_lookup_value('users', 'userID', (int) $AI->user->userID, 'lead_id');

echo '<table class="te_main_table pixel_main_table" id="pixel_main_table">';


echo "<tr>";
echo "<th>Name</th>";
echo "<th>Pixel</th>";
echo "<th>Added On</th>";
echo "<th>Type</th>";
echo "<th>Status</th>";
echo "<th>Action</th>";
echo "</tr>";


//var_dump($table_result);

$table_row = db_fetch_assoc($table_result);

for ( $table_i = 0; $table_i < $this->_pgSize && $table_row; $table_i++ )
{
	//var_dump($table_row['pixel_value']);
	/*foreach ( $table_row as $n => $v )
	{
		$this->db[$n] = db_out($v);
	}
	if ( $this->db['requires_success_line'] == '1' && !$this->te_permit['insert_share_link'] )
	{
		$is_in_success_line = aimod_run_hook_module('success_line', 'hook_is_in_success_line', $lead_id);
		if ( !$is_in_success_line )
		{
			continue;
		}
	}*/
	
	/*
	DrewL 20150415 - whoever wrote this 'Marketing System Sign Up' hack needs to be kicked
	  IT'S NEVER OK TO HARDCODE SOMETHING LIKE THIS
	  	Instead add a 'locked' setting to the module or something similar
	// Hide the "Marketing System Sign Up" entries from non admin
	//if (!preg_match("/Marketing System Sign Up/i", $this->db['name']) || $this->te_permit['insert_share_link']) {
	*/



	if (true) {

		$ai_sid_key = ai_sid_keygen();
		$ai_sid = ai_sid_save_sessionid( $ai_sid_key );
		$core_set = (isset($_SESSION['using_ai_core']) && $_SESSION['using_ai_core']!='default')? '&ai_core='.$_SESSION['using_ai_core']:'';
		
		echo '<tr class="te_data_row ' . ( $table_i % 2 == 1 ? 'te_even_row' : 'te_odd_row' ) . '" id="'.$this->db[$this->_keyFieldName].'" data-row-i="' . $this->_row_i . '">';
		
		echo "<td>";
			$this->draw_value_field('name', $table_row['name'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td>";
			$this->draw_value_field('pixel_value', $table_row['pixel_value'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('added_on', $table_row['added_on'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('type', $table_row['type'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('status', $table_row['status'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center' class='addbtn'>";

		echo '<button class="icon_button_16 share_link_buttons editbtn" onclick="document.location = \'' . h($this->url('te_mode=update&te_key=' . $table_row['id'])) . '&te_share_link_id='.@$_GET['te_share_link_id']. '&te_row=' . $this->_row_i.'\'; return false;">';
		echo '<img src="images/dynamic_edit.14.transparent.png">';
		echo '<span>Edit</span>';
		echo '</button>';

		echo '<button class="icon_button_16 share_link_buttons deletebtn" onclick="document.location = \'' . h($this->url('te_mode=delete&te_key=' . $table_row['id'])) . '&te_share_link_id='.@$_GET['te_share_link_id']. '&te_row=' . $this->_row_i.'\'; return false;">';
		echo '<img src="images/drop.png">';
		echo '<span>Delete</span>';
		echo '</button>';

		echo "</td>";
		echo "</tr>";
	}
	//--
	$this->_row_i++;
	$table_row = db_fetch_assoc($table_result);
}

echo '</table>';
echo'</div>';

?>
