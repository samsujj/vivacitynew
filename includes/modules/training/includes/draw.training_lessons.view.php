<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-11 08:44:34 by philip
	//DB Table: training_lessons, Unique ID: training_lessons, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>
<script>
function access_group_function(){
   //alert(document.getElementById('access_group').value);
   document.getElementById('inline_' + <?php echo $this->te_key ; ?> + '_access_group').value=document.getElementById('access_group').value;
}
</script>

<div class="te_view training_lessons_view training_main_view">

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
		<dt class="te <?php echo $this->get_field_type( 'category_id' ); ?> category_id" >Category Id</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_category_id' ?>" class="te <?php echo $this->get_field_type( 'category_id' ); ?> category_id" >
			<?php $this->draw_value_field( 'category_id', $this->db['category_id'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'name' ); ?> name" >Name</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_name' ?>" class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
			<?php $this->draw_value_field( 'name', $this->db['name'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt class="te <?php echo $this->get_field_type( 'prerequisite_id' ); ?> prerequisite_id" >Prerequisite Id</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_prerequisite_id' ?>" class="te <?php echo $this->get_field_type( 'prerequisite_id' ); ?> prerequisite_id" >
			<?php $this->draw_value_field( 'prerequisite_id', $this->db['prerequisite_id'].'', $this->te_key, 'view' ); ?>
		</dd>

		<dt class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >Access Group</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_access_group' ?>" class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >
			<?php $this->draw_value_field( 'access_group', $this->db['access_group'].'', $this->te_key, 'view' ); ?>
		</dd>

		<dt class="te <?php echo $this->get_field_type( 'acode' ); ?> acode" >Access Code</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_acode' ?>" class="te <?php echo $this->get_field_type( 'acode' ); ?> acode" >
			<?php $this->draw_value_field( 'acode', $this->db['acode'].'', $this->te_key, 'view' ); ?>
		</dd>

		<dt class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >Sort Order</dt>
		<dd id="<?php echo 'value_field_container_' . $this->te_key . '_sort_order' ?>" class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >
			<?php $this->draw_value_field( 'sort_order', $this->db['sort_order'].'', $this->te_key, 'view' ); ?>
		</dd>
		<dt>WYSIWYG Editor</dt>
		<dd>
			<?php echo $AI->get_dynamic_area('training_lessons_' . $this->db['id'],'name','',false,false,845); ?>
		</dd>
		
		</dl>

		<div class="te_viewnav_bottom">
			<?php $this->draw_ViewNav(); ?>
		</div>

	</fieldset>

</div>
