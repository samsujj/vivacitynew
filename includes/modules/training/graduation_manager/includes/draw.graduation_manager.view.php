<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2014-01-27 18:17:34 by jason
	//DB Table: graduation_events, Unique ID: graduation_manager, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<div class="te_view graduation_manager_view training_main_view">

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
		<dt class="te <?php echo $this->get_field_type( 'upgrade_account_type' ); ?> upgrade_account_type" >Upgrade Account Type</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_upgrade_account_type' ?>" class="te <?php echo $this->get_field_type( 'upgrade_account_type' ); ?> upgrade_account_type" >
			<?php $this->draw_value_field( 'upgrade_account_type', $this->db['upgrade_account_type'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'special_message' ); ?> special_message" >Special Message</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_special_message' ?>" class="te <?php echo $this->get_field_type( 'special_message' ); ?> special_message" >
			<?php $this->draw_value_field( 'special_message', $this->db['special_message'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'certificate_name' ); ?> certificate_name" >Certificate Name</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_certificate_name' ?>" class="te <?php echo $this->get_field_type( 'certificate_name' ); ?> certificate_name" >
			<?php $this->draw_value_field( 'certificate_name', $this->db['certificate_name'].'', $this->te_key, 'view' ); ?>
		</dd>
		
		</dl>

		<div class="te_viewnav_bottom">
			<?php $this->draw_ViewNav(); ?>
		</div>

	</fieldset>

</div>
