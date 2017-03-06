<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 15:51:58 by philip
	//DB Table: training_completes, Unique ID: training_completes, PK Field: id
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
	function check_training_completes_obj(obj, msg)
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
	function regex_training_completes_obj(obj, reg, msg)
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
	function check_training_completes_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_training_completes_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_training_completes_obj(frm.id, "Please enter a valid value for: Id"))return false;
		//if(!check_training_completes_obj(frm.lesson_id, "Please enter a valid value for: Lesson Id"))return false;
		//if(!check_training_completes_obj(frm.userID, "Please enter a valid value for: Userid"))return false;
		
		return true;
	}
	//-->
</script>

<div class="te_edit training_completes_edit training_main_form">
	<form id="training_completes_form" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_training_completes_form( this );" >
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
				<dt class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
					<label class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" for="lesson_id">Lesson Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_lesson_id'; ?>" class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
					<?php $this->draw_input_field( 'lesson_id', $this->db['lesson_id'], 'edit', 'lesson_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<label class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" for="userID">Userid</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_userID'; ?>" class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<?php $this->draw_input_field( 'userID', $this->db['userID'], 'edit', 'userID' ); ?>
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
