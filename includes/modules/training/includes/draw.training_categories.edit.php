<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2011-11-04 16:38:17 by philip
	//DB Table: training_categories, Unique ID: training_categories, PK Field: id
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
	function check_training_categories_obj(obj, msg)
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
	function regex_training_categories_obj(obj, reg, msg)
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
	function check_training_categories_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_training_categories_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_training_categories_obj(frm.id, "Please enter a valid value for: Id"))return false;
		//if(!check_training_categories_obj(frm.name, "Please enter a valid value for: Name"))return false;
		//if(!check_training_categories_obj(frm.thumbnail, "Please enter a valid value for: Thumbnail"))return false;
		//if(!check_training_categories_obj(frm.access_group, "Please enter a valid value for: Access Group"))return false;
		//if(!check_training_categories_obj(frm.sort_order, "Please enter a valid value for: Sort Order"))return false;
		
		return true;
	}
	//-->
</script>

<div class="te_edit training_categories_edit training_main_form">
	<form id="training_categories_form" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_training_categories_form( this );" >
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
				<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<label class="te <?php echo $this->get_field_type( 'id' ); ?> id" for="id">Id</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_id'; ?>" class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<?php $this->draw_input_field( 'id', $this->db['id'], 'edit', 'id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
					<label class="te <?php echo $this->get_field_type( 'name' ); ?> name" for="name">Name</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_name'; ?>" class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
					<?php $this->draw_input_field( 'name', $this->db['name'], 'edit', 'name' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'thumbnail' ); ?> thumbnail" >
					<label class="te <?php echo $this->get_field_type( 'thumbnail' ); ?> thumbnail" for="thumbnail">Thumbnail</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_thumbnail'; ?>" class="te <?php echo $this->get_field_type( 'thumbnail' ); ?> thumbnail" >
					<?php $this->draw_input_field( 'thumbnail', $this->db['thumbnail'], 'edit', 'thumbnail' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >
					<label class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" for="access_group">Access Group</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_access_group'; ?>" class="te <?php echo $this->get_field_type( 'access_group' ); ?> access_group" >
					<?php $this->draw_input_field( 'access_group', $this->db['access_group'], 'edit', 'access_group' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >
					<label class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" for="sort_order">Sort Order</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_sort_order'; ?>" class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >
					<?php $this->draw_input_field( 'sort_order', $this->db['sort_order'], 'edit', 'sort_order' ); ?>
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
