<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-03-07 18:47:27 by jason
	//DB Table: quiz_submissions, Unique ID: quiz_history, PK Field: submission_id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>

<div class="te_asearch quiz_history_asearch training_main_form">
	<form id="quiz_history_asearch_form" class="te" method="get" action="<?php echo htmlspecialchars($this->url( '' )); ?>">
	<input type="hidden" name="te_class" value="<?php echo $this->unique_id; ?>" />
	<input type="hidden" name="te_mode" value="table" />
	<input type="hidden" name="te_asearch" value="true" />
	<input type="hidden" name="te_qsearch" value="true" />
		<fieldset class="te">
			<legend class="te">
				<a class="te" href="<?php echo ( $this->te_permit['table'] ? htmlspecialchars($this->url('te_mode=table')) :'#'); ?>"><?php echo htmlspecialchars( $this->_tableTitle ); ?></a>
				:
				Advanced Search
			</legend>

			<div class="te_caption">Search by one or several selections</div>

			<dl class="te">
				<dt class="te">
					<label class="te te_qkeywords" for="te_qkeywords">Keywords</label>
				</dt>
				<dd>
					<input id="te_qkeywords" class="te te_qkewords" type="text" name="te_qkeywords" value="<?php echo htmlspecialchars($this->qkeywords);?>" />
					<input class="te te_radio" type="radio" name="te_qsearchMode" value="all"   <?php if( $this->qsearchMode == 'all' || $this->qsearchMode == '' ){ echo 'checked="checked"';} ?> />All Words
					<input class="te te_radio" type="radio" name="te_qsearchMode" value="any"  <?php if( $this->qsearchMode == 'any'){ echo 'checked="checked"';} ?> />Any Word
					<input class="te te_radio" type="radio" name="te_qsearchMode" value="exact"  <?php if( $this->qsearchMode == 'exact'){ echo 'checked="checked"';} ?> />Exact Phrase
				</dd>

				<dt class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" >
					<label class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" for="submission_id">Submission Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_submission_id'; ?>" class="te <?php echo $this->get_field_type( 'submission_id' ); ?> submission_id" >
					<?php $this->draw_input_field( 'submission_id', $this->search_vars['submission_id'], 'asearch', 'submission_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<label class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" for="quiz_id">Quiz Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_id'; ?>" class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<?php $this->draw_input_field( 'quiz_id', $this->search_vars['quiz_id'], 'asearch', 'quiz_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<label class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" for="userID">Userid</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_userID'; ?>" class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<?php $this->draw_input_field( 'userID', $this->search_vars['userID'], 'asearch', 'userID' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" >
					<label class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" for="date_started">Date Started</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_date_started'; ?>" class="te <?php echo $this->get_field_type( 'date_started' ); ?> date_started" >
					<?php $this->draw_input_field( 'date_started', $this->search_vars['date_started'], 'asearch', 'date_started' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" >
					<label class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" for="date_ended">Date Ended</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_date_ended'; ?>" class="te <?php echo $this->get_field_type( 'date_ended' ); ?> date_ended" >
					<?php $this->draw_input_field( 'date_ended', $this->search_vars['date_ended'], 'asearch', 'date_ended' ); ?>
				</dd>
				
			</dl>

			<div class="te_buttons">
				<input class="te te_buttons search_button" type="submit" name="btnSearch" value="Search" />
				<input class="te te_buttons show_all_button" type="button" name="btnResetSearch" value="Show All" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=table&te_resetsearch=true' )); ?>';" />
			</div>

		</fieldset>
	</form>
</div>
