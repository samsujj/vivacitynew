<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-03-07 18:49:20 by jason
	//DB Table: quiz, Unique ID: manage_quizzes, PK Field: quiz_id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<div class="te_view manage_quizzes_view training_main_view">

	<fieldset class="te">
		<legend class="te">
			<a class="te" href="<?php echo ( $this->te_permit['table'] ? htmlspecialchars($this->url('te_mode=table')) :'#'); ?>"><?php echo htmlspecialchars( $this->_tableTitle ); ?></a>
			:
			<?php
				switch( $this->te_mode )
				{
					case 'delete': echo 'Confirm Delete'; break;
					default: echo 'View'; break;
				}
			?>
		</legend>

		<div class="te_viewnav_top">
			<?php $this->draw_ViewNav(); ?>
		</div>

		<dl class="te">
		<!-- <dt class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >Quiz Id</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_id' ?>" class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
			<?php $this->draw_value_field( 'quiz_id', $this->db['quiz_id'].'', $this->te_key, 'view' ); ?>
		</dd> -->
		<dt class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" >Lesson Name</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_training_id' ?>" class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" >
			<?php $this->draw_value_field( 'training_id', $this->db['training_id'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" >Quiz Title</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_title' ?>" class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" >
			<?php $this->draw_value_field( 'quiz_title', $this->db['quiz_title'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'completion_percent' ); ?> completion_percent" >Completion Percent</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_completion_percent' ?>" class="te <?php echo $this->get_field_type( 'completion_percent' ); ?> completion_percent" >
			<?php $this->draw_value_field( 'completion_percent', $this->db['completion_percent'].'', $this->te_key, 'view' ); ?>%
		</dd>
		</dl>

		<div class="te_viewnav_bottom">
			<?php $this->draw_ViewNav(); ?>
		</div>

	</fieldset>

</div>
<?php 
require_once( ai_cascadepath('includes/modules/training/quiz_question_manager/includes/class.te_quiz_question_manager.php' ) );	
	
	$te_quiz_question_manager = new C_te_quiz_question_manager('quiz_id='.$this->te_key);
	$te_quiz_question_manager->quiz_id=$this->te_key;
	$te_quiz_question_manager->run_TableEdit();	
?>