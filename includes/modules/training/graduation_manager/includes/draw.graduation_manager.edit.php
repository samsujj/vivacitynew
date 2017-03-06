<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2014-01-27 18:17:34 by jason
	//DB Table: graduation_events, Unique ID: graduation_manager, PK Field: id
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
	function check_graduation_manager_obj(obj, msg)
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
	function regex_graduation_manager_obj(obj, reg, msg)
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
	function check_graduation_manager_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_graduation_manager_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_graduation_manager_obj(frm.id, "Please enter a valid value for: Id"))return false;
		//if(!check_graduation_manager_obj(frm.lesson_id, "Please enter a valid value for: Lesson Id"))return false;
		//if(!check_graduation_manager_obj(frm.upgrade_account_type, "Please enter a valid value for: Upgrade Account Type"))return false;
		//if(!check_graduation_manager_obj(frm.special_message, "Please enter a valid value for: Special Message"))return false;
		
		return true;
	}
	//-->
</script>

<div class="te_edit graduation_manager_edit training_main_form">
	<form id="graduation_manager_form" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_graduation_manager_form( this );" >
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
				
				<dt class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
					<label class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" for="lesson_id">Lesson Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_lesson_id'; ?>" class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
					<?php $this->draw_input_field( 'lesson_id', $this->db['lesson_id'], 'edit', 'lesson_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'upgrade_account_type' ); ?> upgrade_account_type" >
					<label class="te <?php echo $this->get_field_type( 'upgrade_account_type' ); ?> upgrade_account_type" for="upgrade_account_type">Upgrade Account Type</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_upgrade_account_type'; ?>" class="te <?php echo $this->get_field_type( 'upgrade_account_type' ); ?> upgrade_account_type" >
					<?php $this->draw_input_field( 'upgrade_account_type', $this->db['upgrade_account_type'], 'edit', 'upgrade_account_type' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'special_message' ); ?> special_message" >
					<label class="te <?php echo $this->get_field_type( 'special_message' ); ?> special_message" for="special_message">Special Message</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_special_message'; ?>" class="te <?php echo $this->get_field_type( 'special_message' ); ?> special_message" >
					<?php $this->draw_input_field( 'special_message', $this->db['special_message'], 'edit', 'special_message' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'certifiate_name' ); ?> certificate_name" >
					<label class="te <?php echo $this->get_field_type( 'certificate_name' ); ?> certificate_name" for="certificate_name">Certificate Name</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_certificate_name'; ?>" class="te <?php echo $this->get_field_type( 'certificate_name' ); ?> certificate_name" >
					<?php $this->draw_input_field( 'certificate_name', $this->db['certificate_name'], 'edit', 'certificate_name' ); ?>
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
