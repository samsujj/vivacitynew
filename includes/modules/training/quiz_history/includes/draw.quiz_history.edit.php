<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-03-07 18:47:27 by jason
	//DB Table: quiz_submissions, Unique ID: quiz_history, PK Field: submission_id
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
	function check_quiz_history_obj(obj, msg)
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
	function regex_quiz_history_obj(obj, reg, msg)
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
	function check_quiz_history_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_quiz_history_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_quiz_history_obj(frm.submission_id, "Please enter a valid value for: Submission Id"))return false;
		//if(!check_quiz_history_obj(frm.quiz_id, "Please enter a valid value for: Quiz Id"))return false;
		//if(!check_quiz_history_obj(frm.userID, "Please enter a valid value for: Userid"))return false;
		//if(!check_quiz_history_obj(frm.date_started, "Please enter a valid value for: Date Started"))return false;
		//if(!check_quiz_history_obj(frm.date_ended, "Please enter a valid value for: Date Ended"))return false;
		
		return true;
	}
	//-->
</script>

<div class="te_edit quiz_history_edit training_main_form">
	<form id="quiz_history_form" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_quiz_history_form( this );" >
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
				<dt class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" >
					<label class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" for="submission_id">Submission Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_submission_id'; ?>" class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" >
					<?php $this->draw_input_field( 'submission_id', $this->db['submission_id'], 'edit', 'submission_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<label class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" for="quiz_id">Quiz Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_id'; ?>" class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<?php $this->draw_input_field( 'quiz_id', $this->db['quiz_id'], 'edit', 'quiz_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<label class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" for="userID">Userid</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_userID'; ?>" class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<?php $this->draw_input_field( 'userID', $this->db['userID'], 'edit', 'userID' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" >
					<label class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" for="date_started">Date Started</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_date_started'; ?>" class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" >
					<?php $this->draw_input_field( 'date_started', $this->db['date_started'], 'edit', 'date_started' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" >
					<label class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" for="date_ended">Date Ended</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_date_ended'; ?>" class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" >
					<?php $this->draw_input_field( 'date_ended', $this->db['date_ended'], 'edit', 'date_ended' ); ?>
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
