<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 15:51:58 by philip
	//DB Table: training_completes, Unique ID: training_completes, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<div class="te_view training_completes_view training_main_view">

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
		<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >Id</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_id' ?>" class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
			<?php $this->draw_value_field( 'id', $this->db['id'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >Lesson Id</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_lesson_id' ?>" class="te <?php echo $this->get_field_type( 'lesson_id' ); ?> lesson_id" >
			<?php $this->draw_value_field( 'lesson_id', $this->db['lesson_id'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >Userid</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_userID' ?>" class="te <?php echo $this->get_field_type( 'userID' ); ?> userID" >
			<?php $this->draw_value_field( 'userID', $this->db['userID'].'', $this->te_key, 'view' ); ?>
		</dd>
		
		</dl>

		<div class="te_viewnav_bottom">
			<?php $this->draw_ViewNav(); ?>
		</div>

	</fieldset>

</div>
