<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 16:38:17 by philip
	//DB Table: training_categories, Unique ID: training_categories, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<div class="te_view training_categories_view training_main_view">

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
		<dt class="te <?php echo $this->get_field_type( 'name' ); ?> name" >Name</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_name' ?>" class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
			<?php $this->draw_value_field( 'name', $this->db['name'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'thumbnail' ); ?> thumbnail" >Thumbnail</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_thumbnail' ?>" class="te <?php echo $this->get_field_type( 'thumbnail' ); ?> thumbnail" >
			<?php $this->draw_value_field( 'thumbnail', $this->db['thumbnail'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >Access Group</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_access_group' ?>" class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >
			<?php $this->draw_value_field( 'access_group', $this->db['access_group'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >Sort Order</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_sort_order' ?>" class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >
			<?php $this->draw_value_field( 'sort_order', $this->db['sort_order'].'', $this->te_key, 'view' ); ?>
		</dd>
		
		</dl>

		<div class="te_viewnav_bottom">
			<?php $this->draw_ViewNav(); ?>
		</div>

	</fieldset>

</div>
