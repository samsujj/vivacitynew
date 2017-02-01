<?php

global $AI;
/*
print_r($AI->user->account_type);
echo 2;
print_r($AI->get_access_group_perm('Administrators'));
print_r($AI->get_access_group_perm('Website Developers'));
*/
// Merge Codes
$sub_domain = $AI->user->username;
echo '<link href="includes/modules/share_links/bootstrap.min.css" rel="stylesheet">';
echo '<script type="text/javascript" src="includes/modules/share_links/bootstrap.min.js"></script>';

?>
<link href="includes/modules/share_links/share_links.css" rel="stylesheet">
<script src="includes/modules/share_links/clipboard.min.js"></script>
<script language="javascript" type="text/javascript">

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

	function show_affiliate_m(id,type){

		//alert(type+'and id ='+id);
		if(type == 2)
			var te_type = 2;
		else if(type == 3)
			var te_type = 3;
		else
			var te_type = 1;


		$.ajax({
			url:  'asset-list',
			type: 'GET',
			data: {te_share_link_id: id,te_type: te_type},
			success: function(html){
				$('#myshareModal').find('.modal-body').html(html);
				$('#myshareModal').modal('show');

				setTimeout(function(){
					var clipboard = new Clipboard('.clip1btn');
					var clipboard = new Clipboard('.clip1btn');
				},2000);
			}
		});
	}

	function show_affiliate_b(id,type){
		console.log(222);

		$.ajax({
			url:  'grab-blog-list',
			type: 'GET',
			data: {te_share_link_id: id,te_type: (type)},
			success: function(html){
				$('#myshareModal1').find('.modal-content').html(html);
				$('#myshareModal1').modal('show');

				setTimeout(function(){
					var clipboard = new Clipboard('.clip1btn');
					var clipboard = new Clipboard('.clip1btn');
				},2000);
			}
		});
	}

	function show_affiliate_g(id){

		$.ajax({
			url:  'grab-google-ad',
			type: 'GET',
			data: {te_share_link_id: id},
			success: function(html){
				$('#myshareModal2').find('.modal-body').html(html);
				$('#myshareModal2').modal('show');

			}
		});
	}

	function show_affiliate_pdf(){

		$.ajax({
			url:  'grab-pdf',
			type: 'GET',
			data: {},
			success: function(html){
				$('#myshareModal3').find('.modal-body').html(html);
				$('#myshareModal3').modal('show');

			}
		});
	}

	function show_link_image(id){
		//jQuery.facebox({ajax:l_url+'&mode=facebox'});
		$("#myModal"+id).modal('show');
	}

</script>
<?php


echo $AI->get_dynamic_area('my-urls-header');
// echo '<p>&nbsp;</p><!--spacer-->';

$lead_id = (int) db_lookup_value('users', 'userID', (int) $AI->user->userID, 'lead_id');

if ( @$this->te_permit['insert_share_link'] )
{
	//echo '<button onclick="document.location = \'' . h($this->url('te_mode=insert_old')) . '\'; return false;">New</button>';
}

if ( @$this->te_permit['insert_share_link'] )
{
	//echo '<div style="    margin: 18px auto;  width: 1170px;    background: #fff;"><button onclick="document.location = \'' . h($this->url('te_mode=insert_old')) . '\'; return false;" style="background: #004c92!important; display: block!important;   width: 144px!important;  height: 36px!important;  border: none!important;  cursor: pointer !importantr;   border-radius: 0px!important;   box-shadow: none!important;   background-image: none!important;   font-size: 14px!important;   color: #ffffff!important;   text-align: center!important;   line-height: 36px!important;   font-weight: 600px;">New</button></div>';
}

echo '<div class="sharelinks_list_wrapper">';

if ( @$this->te_permit['insert_share_link'] )
{
	echo '<div class="icon_button1_div"><button class="icon_button1" onclick="document.location = \'' . h($this->url('te_mode=insert_old')) . '\'; return false;">New Share Link</button></div>';
}



$table_row = db_fetch_assoc($table_result);
for ( $table_i = 0; $table_i < $this->_pgSize && $table_row; $table_i++ )
{

	foreach ( $table_row as $n => $v )
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
	}
	
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
		
		//echo '<tr class="te_data_row ' . ( $table_i % 2 == 1 ? 'te_even_row' : 'te_odd_row' ) . '" id="'.$this->db[$this->_keyFieldName].'" data-row-i="' . $this->_row_i . '"><td>';
		
		echo '<div class="list_block" id="'.$this->db[$this->_keyFieldName].'" data-row-i="' . $this->_row_i . '">';

		echo $this->draw_value_field('img_url', $this->db['img_url'], $this->db[$this->_keyFieldName], 'table');

		echo '<div class="contain_block">';

		echo '<div class="link_div">';



		if ( $this->te_permit['insert_share_link'] && empty($this->db['template_id']))
		{
			echo '<a href="' . h($this->url('te_mode=update&te_key=' . $this->db[$this->_keyFieldName])) . '&te_row=' . $this->_row_i . '" class="Edit_link">Edit</a>';

		}
			if ( $this->te_permit['delete'] && $this->db['owner_id'] == $AI->user->userID || $AI->get_access_group_perm('Website Developers'))
			{
				echo '<a href="' . h($this->url('te_mode=delete&te_key=' . $this->db[$this->_keyFieldName])) . '&te_row=' . $this->_row_i . '" class="Delete_link">Delete</a>';
			}

		echo '<div class="clear"></div></div><div class="clear"></div>';

		$this->draw_value_field('name', $this->db['name'], $this->db[$this->_keyFieldName], 'table');

		$this->draw_value_field('url', $this->db['url'], $this->db[$this->_keyFieldName], 'table');


		if($this->db['is_pixel'] == 1 && isset($AI->MODS_INDEX['pixel'])){
			echo '<button class="share_btn" onclick="document.location = \'' . h('/pixel_manager?te_share_link_id='.$this->db['id']) . '\'; return false;">';
			echo 'Manage Pixel';
			echo '</button>';
		}

		if($this->db['is_google_ad'] == 1 && isset($AI->MODS_INDEX['google_ad']) && ($AI->get_access_group_perm('Website Developers') || $AI->get_access_group_perm('Administrators') || $AI->user->account_type=='Administrator')){
			echo '<button class="share_btn" onclick="document.location = \'' . h('/google-ad-manager?te_share_link_id='.$this->db['id']) . '\'; return false;">';
			echo 'Google Adwords';
			echo '</button>';
		}

		if(isset($AI->MODS_INDEX['blog_ad']) && ($AI->get_access_group_perm('Website Developers') || $AI->get_access_group_perm('Administrators') || $AI->user->account_type=='Administrator')){

			//print_r($AI);
			echo '<button class="share_btn" onclick="document.location = \'' . h('/blog_ad?te_share_link_id='.$this->db['id'].'&s_type=Email Subject Line') . '\'; return false;">';
			echo 'Email Subject Lines';
			echo '</button>';
		}

		if(isset($AI->MODS_INDEX['share_asset']) && ($AI->get_access_group_perm('Website Developers') || $AI->get_access_group_perm('Administrators') || $AI->user->account_type=='Administrator')){
			//echo '<button class="share_btn" onclick="document.location = \'' . h('/share_asset?te_share_link_id='.$this->db['id']) . '\'; return false;">';
			//echo 'Share Asset';
			//echo '</button>';

			echo '<button class="share_btn" onclick="document.location = \'' . h('/share_asset?te_share_link_id='.$this->db['id'].'&type=1') . '\'; return false;">';
			echo 'Banner Ads';
			echo '</button>';

			echo '<button class="share_btn" onclick="document.location = \'' . h('/share_asset?te_share_link_id='.$this->db['id'].'&type=2') . '\'; return false;">';
			echo 'Display Ads';
			echo '</button>';
			
echo '<button class="share_btn" onclick="document.location = \'' . h('/share_asset?te_share_link_id='.$this->db['id'].'&type=3') . '\'; return false;">';
			echo 'Mobile Ads';
			echo '</button>';
	}

		if(isset($AI->MODS_INDEX['pdf_manager']) && ($AI->get_access_group_perm('Website Developers') || $AI->get_access_group_perm('Administrators') || $AI->user->account_type=='Administrator')){
			echo '<button class="share_btn" onclick="document.location = \'' . h('/pdf-manager') . '\'; return false;">';
			echo 'PDF Manager';
			echo '</button>';
		}

		/*if(isset($AI->MODS_INDEX['blog_ad']) && ($AI->get_access_group_perm('Website Developers') || $AI->get_access_group_perm('Administrators') || $AI->user->account_type=='Administrator')){
			echo '<button class="share_btn" onclick="document.location = \'' . h('/blog_ad?te_share_link_id='.$this->db['id'].'&s_type=Featured Article') . '\'; return false;">';
			echo 'Featured Articles';
			echo '</button>';
		}

		if(isset($AI->MODS_INDEX['blog_ad']) && ($AI->get_access_group_perm('Website Developers') || $AI->get_access_group_perm('Administrators') || $AI->user->account_type=='Administrator')){
			echo '<button class="share_btn" onclick="document.location = \'' . h('/blog_ad?te_share_link_id='.$this->db['id'].'&s_type=Blogs') . '\'; return false;">';
			echo 'Blogs';
			echo '</button>';
		}*/

		/*if ((($this->te_permit['view'] && $this->db['owner_id'] == $AI->user->userID) || $AI->get_access_group_perm('Website Developers')) && !empty($this->db['template_id']))
		{
			echo '<button class="share_btn" onclick="document.location = \'' . h($this->interpret_url($this->db['url'])) . '?ai_sid='.$ai_sid.'&ai_sid_key='.$ai_sid_key.$core_set . '\'; return false;">';
			echo 'Reporting';
			echo '</button>';
		}
		else if ( $this->te_permit['view'] && empty($this->db['template_id']))
		{
			echo '<button class="share_btn" onclick="document.location = \'' . h($this->url('te_mode=view&te_key=' . $this->db[$this->_keyFieldName])) . '&te_row=' . $this->_row_i . '\'; return false;">';
			echo 'Reporting';
			echo '</button>';
		}*/

		echo '<div class="clear"></div>';
		echo '</div>';

		echo '</div>';

		echo '<div class="clear"></div></div>';
	}

	//--
	$this->_row_i++;
	$table_row = db_fetch_assoc($table_result);
}


if(@$this->settings['enable_landing_page_manager'] != "No" && @$this->te_permit['landing_page_manager'] == 1) {
	echo '<button class="icon_button"  onclick="document.location = \'' . h($this->url('te_mode=insert')) . '\'; return false;"> Add Landing Page</button>';
}


echo '</div>';


$te_key = util_GET('te_key');
if ( !empty($te_key) )
{
	$AI->skin->js_onload('lead_management_table.scrollTo(' . (int) $te_key . ');');
}
?>



<div class="modal fade grabmodal " id="myshareModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Banner</h4>
			</div>
			<div class="modal-body">

			</div>
		</div>

	</div>
</div>
<div class="modal fade grabmodal " id="myshareModal1" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">


		</div>

	</div>
</div>


<div class="modal fade grabmodal " id="myshareModal2" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Google Adwords</h4>
			</div>
			<div class="modal-body">

			</div>
		</div>

	</div>
</div>

<div class="modal fade grabmodal " id="myshareModal3" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Grab PDF</h4>
			</div>
			<div class="modal-body">

			</div>
		</div>

	</div>
</div>
