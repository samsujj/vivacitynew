<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-03-07 18:49:20 by jason
	//DB Table: quiz, Unique ID: manage_quizzes, PK Field: quiz_id
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
	function check_manage_quizzes_obj(obj, msg)
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
	function regex_manage_quizzes_obj(obj, reg, msg)
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
	function check_manage_quizzes_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_manage_quizzes_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_manage_quizzes_obj(frm.quiz_id, "Please enter a valid value for: Quiz Id"))return false;
		//if(!check_manage_quizzes_obj(frm.training_id, "Please enter a valid value for: Training Id"))return false;
		//if(!check_manage_quizzes_obj(frm.quiz_title, "Please enter a valid value for: Quiz Title"))return false;
		
		return true;
	}
	//-->
</script>

<div class="te_edit manage_quizzes_edit training_main_form">
	<form id="manage_quizzes_form" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_manage_quizzes_form( this );" >
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
			<!-- 	<dt class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<label class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" for="quiz_id">Quiz Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_id'; ?>" class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<?php $this->draw_input_field( 'quiz_id', $this->db['quiz_id'], 'edit', 'quiz_id' ); ?>
				</dd> -->
				<dt class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" >
					<label class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" for="training_id">What Lesson Does this Quiz Belong To?</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_training_id'; ?>" class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" >
					<?php $this->draw_input_field( 'training_id', $this->db['training_id'], 'edit', 'training_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" >
					<label class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" for="quiz_title">Quiz Title</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_title'; ?>" class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" >
					<?php $this->draw_input_field( 'quiz_title', $this->db['quiz_title'], 'edit', 'quiz_title' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'completion_percent' ); ?> completion_percent" >
					<label class="te <?php echo $this->get_field_type( 'completion_percent' ); ?> completion_percent" for="completion_percent">Completion Percent</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_completion_percent'; ?>" class="te <?php echo $this->get_field_type( 'completion_percent' ); ?> completion_percent" >
					<?php $this->draw_input_field( 'completion_percent', $this->db['completion_percent'], 'edit', 'completion_percent' ); ?>%
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
