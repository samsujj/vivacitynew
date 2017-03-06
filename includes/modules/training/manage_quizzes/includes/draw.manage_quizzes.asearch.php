<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-03-07 18:49:20 by jason
	//DB Table: quiz, Unique ID: manage_quizzes, PK Field: quiz_id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>

<div class="te_asearch manage_quizzes_asearch training_main_form">
	<form id="manage_quizzes_asearch_form" class="te" method="get" action="<?php echo htmlspecialchars($this->url( '' )); ?>">
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

				<dt class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<label class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" for="quiz_id">Quiz Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_id'; ?>" class="te <?php echo $this->get_field_type( 'quiz_id' ); ?> quiz_id" >
					<?php $this->draw_input_field( 'quiz_id', $this->search_vars['quiz_id'], 'asearch', 'quiz_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" >
					<label class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" for="training_id">Training Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_training_id'; ?>" class="te <?php echo $this->get_field_type( 'training_id' ); ?> training_id" >
					<?php $this->draw_input_field( 'training_id', $this->search_vars['training_id'], 'asearch', 'training_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" >
					<label class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" for="quiz_title">Quiz Title</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_quiz_title'; ?>" class="te <?php echo $this->get_field_type( 'quiz_title' ); ?> quiz_title" >
					<?php $this->draw_input_field( 'quiz_title', $this->search_vars['quiz_title'], 'asearch', 'quiz_title' ); ?>
				</dd>
				
			</dl>

			<div class="te_buttons">
				<input class="te te_buttons search_button" type="submit" name="btnSearch" value="Search" />
				<input class="te te_buttons show_all_button" type="button" name="btnResetSearch" value="Show All" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=table&te_resetsearch=true' )); ?>';" />
			</div>

		</fieldset>
	</form>
</div>
