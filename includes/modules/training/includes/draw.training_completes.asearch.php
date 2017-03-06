<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 15:51:58 by philip
	//DB Table: training_completes, Unique ID: training_completes, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>

<div class="te_asearch training_completes_asearch training_main_form">
	<form id="training_completes_asearch_form" class="te" method="get" action="<?php echo htmlspecialchars($this->url( '' )); ?>">
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

				<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<label class="te <?php echo $this->get_field_type( 'id' ); ?> id" for="id">Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_id'; ?>" class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<?php $this->draw_input_field( 'id', $this->search_vars['id'], 'asearch', 'id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
					<label class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" for="lesson_id">Lesson Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_lesson_id'; ?>" class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
					<?php $this->draw_input_field( 'lesson_id', $this->search_vars['lesson_id'], 'asearch', 'lesson_id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<label class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" for="userID">Userid</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_userID'; ?>" class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
					<?php $this->draw_input_field( 'userID', $this->search_vars['userID'], 'asearch', 'userID' ); ?>
				</dd>
				
			</dl>

			<div class="te_buttons">
				<input class="te te_buttons search_button" type="submit" name="btnSearch" value="Search" />
				<input class="te te_buttons show_all_button" type="button" name="btnResetSearch" value="Show All" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=table&te_resetsearch=true' )); ?>';" />
			</div>

		</fieldset>
	</form>
</div>
