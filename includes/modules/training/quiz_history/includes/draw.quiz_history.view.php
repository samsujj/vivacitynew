<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-03-07 18:47:27 by jason
	//DB Table: quiz_submissions, Unique ID: quiz_history, PK Field: submission_id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<div class="te_view quiz_history_view training_main_view">

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
			<dt class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" >Submission Id</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_submission_id' ?>" class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" >
			<?php $this->draw_value_field( 'submission_id', $this->db['submission_id'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >Quiz Name</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_id' ?>" class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
			<?php $this->draw_value_field( 'quiz_id', $this->db['quiz_id'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >Userid</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_userID' ?>" class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
			<?php $this->draw_value_field( 'userID', $this->db['userID'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" >Date Started</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_date_started' ?>" class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" >
			<?php $this->draw_value_field( 'date_started', $this->db['date_started'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" >Date Ended</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_date_ended' ?>" class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" >
			<?php $this->draw_value_field( 'date_ended', $this->db['date_ended'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'number_correct' ); ?> number_correct" >Number Correct</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_number_correct' ?>" class="te <?php echo $this->get_field_type( 'number_correct' ); ?> number_correct" >
			<?php $this->draw_value_field( 'number_correct', $this->db['number_correct'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'total_questions' ); ?> total_questions" >Total Questions</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_total_questions' ?>" class="te <?php echo $this->get_field_type( 'total_questions' ); ?> total_questions" >
			<?php $this->draw_value_field( 'total_questions', $this->db['total_questions'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'perc_required' ); ?> perc_required" >Percent Required</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_perc_required' ?>" class="te <?php echo $this->get_field_type( 'perc_required' ); ?> perc_required" >
			<?php $this->draw_value_field( 'perc_required', $this->db['perc_required'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt>
			Quiz Content
		</dt> 
		<dd>
			<?php 
				$sql = "SELECT  question_id, question_content, quiz_number FROM quiz_questions WHERE quiz_id=".$this->db['quiz_id'];
				$response = db_query($sql);
				while($response && $row=db_fetch_assoc($response))
				{
					$html = $AI->get_dynamic_area( /*$name_or_id*/$row['question_content'], /*$type =*/ 'name', /*$lang =*/ $AI->get_lang(), /*$edit =*/ false);
					// Assure there are spaces between tags (make plain text readable)
					$html = str_replace('><', '> <', $html);
					$html = preg_replace('#<br\\s*/?>#i', ' ', $html);
					// Now remove tags and print the preview
					$text = strip_tags($html);
					echo '<div class="question_results">';
					echo '<div class="question_title">';
					echo 'Question '.$row["quiz_number"].' :';
					echo '</div>';
					echo '<div class="question_text">';
					echo h($text);
					echo '</div>';
					$user_answer = db_lookup_scalar("SELECT qac.answer_text 
					FROM `quiz_answers` AS qa, `quiz_answer_choices` AS qac 
					WHERE qac.answer_choice_id = qa.answer 
					AND qa.submission_id = ".$this->db['submission_id']." 
					AND qa.question_id =".$row['question_id'] );
					echo '<div class="answer_title">Your Answer: </div>';
					echo '<div class="answer_text">';
					echo $user_answer;
					echo '</div>';
					echo '</div>';
					
				}
			?>
		</dd>
		</dl>

		<div class="te_viewnav_bottom">
			<?php $this->draw_ViewNav(); ?>
		</div>

	</fieldset>

</div>
