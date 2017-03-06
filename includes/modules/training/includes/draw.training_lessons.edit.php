<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-11 08:44:34 by philip
	//DB Table: training_lessons, Unique ID: training_lessons, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<script type="text/javascript" language="javascript">
	<!--
	function trim( str )
	{
	   str = str.replace(/^\s+/, '');
	   str = str.replace(/\s+$/, '');
	   return str;
	}
	//check if an object has a value
	function check_training_lessons_obj(obj, msg)
	{
		if(trim(obj.value) == "")
		{
			alert( msg );
			obj.focus();
			return false;
		}
		else
		{
			return true;
		}
	}
	//check if an objects value matches a regular expression
	function regex_training_lessons_obj(obj, reg, msg)
	{
		if( !trim(obj.value).match(reg) )
		{
			alert( msg );
			obj.focus();
			return false;
		}
		else
		{
			return true;
		}
	}
	function check_training_lessons_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_training_lessons_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_training_lessons_obj(frm.id, "Please enter a valid value for: Id"))return false;
		//if(!check_training_lessons_obj(frm.category_id, "Please enter a valid value for: Category Id"))return false;
		//if(!check_training_lessons_obj(frm.prerequisite_id, "Please enter a valid value for: Prerequisite Id"))return false;
		//if(!check_training_lessons_obj(frm.name, "Please enter a valid value for: Name"))return false;
		//if(!check_training_lessons_obj(frm.access_group, "Please enter a valid value for: Access Group"))return false;

		return true;
	}
	//-->
</script>

<style>
dd.acode_fail_msg, dd.dynamic_area_name {
	display: block;
	background-color: #EEE;
	width: 830px;
	border-radius: 10px;
	border:5px solid #DDD;
	padding-right:10px;
}
dt.acode_fail_msg, dd.acode_fail_msg {
	margin-left:30px;
}
</style>

<div class="te_edit training_lessons_edit training_main_form">
	<form id="training_lessons_form" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_training_lessons_form( this );" >
		<fieldset class="te">
			<legend class="te">
				<a class="te" href="<?php echo ( $this->te_permit['table'] ? htmlspecialchars($this->url('te_mode=table')) :'#'); ?>"><?php echo htmlspecialchars( $this->_tableTitle ); ?></a>
				:
				<?php
					switch( $this->te_mode )
					{
						case 'copy': echo 'Copy'; break;
						case 'insert': echo 'New'; break;
						default: echo 'Edit'; break;
					}
				?>
			</legend>

			<?php if( $this->write_error_msg != '' ){ ?><div class="error"><?php echo htmlspecialchars( $this->write_error_msg ); ?></div><?php } ?>

			<dl class="te">
				<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<label class="te <?php echo $this->get_field_type( 'id' ); ?> id" for="id">Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_id'; ?>" class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<?php $this->draw_input_field( 'id', $this->db['id'], 'edit', 'id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'category_id' ); ?> category_id" >
					<label class="te <?php echo $this->get_field_type( 'category_id' ); ?> category_id" for="category_id">Category</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_category_id'; ?>" class="te <?php echo $this->get_field_type( 'category_id' ); ?> category_id" >
					<?php $this->draw_input_field( 'category_id', $this->db['category_id'], 'edit', 'category_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'prerequisite_id' ); ?> prerequisite_id" >
					<label class="te <?php echo $this->get_field_type( 'prerequisite_id' ); ?> prerequisite_id" for="prerequisite_id">Prerequisite Lesson</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_prerequisite_id'; ?>" class="te <?php echo $this->get_field_type( 'prerequisite_id' ); ?> prerequisite_id" >
					<?php $this->draw_input_field( 'prerequisite_id', $this->db['prerequisite_id'], 'edit', 'prerequisite_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
					<label class="te <?php echo $this->get_field_type( 'name' ); ?> name" for="name">Name</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_name'; ?>" class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
					<?php $this->draw_input_field( 'name', $this->db['name'], 'edit', 'name' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >
					<label class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" for="access_group">Access Group</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_access_group'; ?>" class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >
					<?php $this->draw_input_field( 'access_group', $this->db['access_group'], 'edit', 'access_group' ); ?>
				</dd>

				<?php $this->acode_js_id = 'acode_field_'.util_rand_string( 10, '0123456789abcdefghijklmnopqrstuvwxyz'); ?>
				<dt class="te <?php echo $this->get_field_type( 'acode' ); ?> acode" >
					<label class="te <?php echo $this->get_field_type( 'acode' ); ?> acode" for="acode">Access Code</label>
					<span style="font-weight:normal;">- If provided, users will only have access to this and future training modules if they hold the specified permission</span>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_acode'; ?>" class="te <?php echo $this->get_field_type( 'acode' ); ?> acode" >
					<?php $this->draw_input_field( 'acode', $this->db['acode'], 'edit', 'acode' ); ?>
				</dd>

				<?php $disp_none = (trim($this->db['acode'])=='')? 'style="display:none;"':''; ?>
				<dt class="te <?php echo $this->get_field_type( 'acode_fail_msg' ); ?> acode_fail_msg <?=$this->acode_js_id?>" <?=$disp_none?>>
					<label class="te <?php echo $this->get_field_type( 'acode_fail_msg' ); ?> acode_fail_msg" for="acode_fail_msg">Access Code Fail Message</label>
					<span style="font-weight:normal;">- The message users will receive when they do not have access</span>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_acode_fail_msg'; ?>" class="te <?php echo $this->get_field_type( 'acode_fail_msg' ); ?> acode_fail_msg <?=$this->acode_js_id?>" <?=$disp_none?>>
					<?php $this->draw_input_field( 'acode_fail_msg', $this->db['acode_fail_msg'], 'edit', 'acode_fail_msg' ); ?>
				</dd>

				<dt class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order " >
					<label class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" for="sort_order">Sort Order</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_sort_order'; ?>" class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >

					<input id="sort_order" name="sort_order" size="11" maxlength="11" value="<?php echo $this->db['sort_order']; ?>" type="number" value="0">
				</dd>
				<dt>
					<label>WYSIWYG Editor, <small>Can be edited here or on <a href="training">training module page</a></small></label>
				</dt>
				<dd class="dynamic_area_name">
					<?php
						if(empty($this->db['id'])) {
							//KLUDGE Should not be using auto increment because it will give incorrect data during a race condition scenario
							// TODO: When we have time we should get the table edit to just create the table edit entry intitially and start in edit mode
							// Nice To Have: Will need to do the bookkeeping in case we cancel the insert mode
							$table_status = db_lookup_assoc("SHOW TABLE STATUS LIKE '".db_in($this->_dbTableName)."'");
							$max = $table_status['Auto_increment'];
						} else {
							$max = $this->db['id'];
						}
						$da_name = 'training_lessons_' . $max;
						echo '<input type="hidden" name="dynamic_area_name" value="'.h($da_name).'" />';
						echo $AI->get_dynamic_area_for(true,$da_name,'name','',true,true,845);
					?>
				</dd>

			</dl>

			<div class="te_buttons">
			<input class="te te_buttons save_button" type="submit" name="btnSave" value="Save" />
			<?php
				if( $this->is_valid_key( $this->te_key ) && $this->_default_mode_after_save != '' && $this->te_permit[ $this->_default_mode_after_save ] )
				{
					?><input class="te te_buttons cancle_button" type="button" name="btnCancel" value="Cancel" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=' . $this->_default_mode_after_save . '&te_key=' . $this->te_key )); ?>';" /><?php
				}
				elseif( $this->te_permit[ $this->_te_modeDefault ] )

				{
					?><input class="te te_buttons cancle_button" type="button" name="btnCancel" value="Cancel" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=' . $this->_te_modeDefault )); ?>';" /><?php
				}
			?>
			</div>

		</fieldset>
	</form>
</div>
