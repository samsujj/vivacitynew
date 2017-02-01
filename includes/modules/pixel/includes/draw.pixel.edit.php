<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
	global $AI;

$landingpagearray[25]=array(21,118,119,122,123,124,125,126);
$landingpagearray[15]=array(21,118,119,122,123,124,125,126);
$landingpagearray[19]=array(59,127,128,129,130,131,132,133);
$landingpagearray[18]=array(134,135,136,137,138,139,140,141);
$res = db_query("SELECT p.*,psi.* FROM products AS p   JOIN product_stock_items AS psi ON psi.product_id = p.product_id ");
//var_dump($res);
//$result=db_fetch_assoc($res);
$results_array = array();
while($row = db_fetch_assoc($res)) {
	echo "<pre>";
	print_r($row);
	echo "</pre>";

    /*if(in_array($row['stock_item_id'],$landingpagearray[$_GET['te_share_link_id']])) {
        $results_array[] = $row;
    }*/
 }
$sel='';
$sel .='<select onclick="get_product()" style="height:100%" id="stock_idd" name="stock_idd" multiple="multiple">';

  if(count($results_array)>0) {
      foreach($results_array as $val){

          $sel .='<option value="'.$val['stock_item_id'].'">'.$val['title'].'</option> ';

      }
  }

$sel .='</select>';


//echo '<pre>';
//print_r($results_array);
//echo '</pre>';

?>
<script type="text/javascript" language="javascript">

	<!--

	$(document).ready(function(){
		optionval=$('#product_option').val();
		var dataarray=optionval.split(",");
		$('#stock_idd').val(dataarray);
		$('.pro_op').hide();
		$('.pro_op1').hide();

		type=$('#type').val();

		if(type=='Confirmation'){
			$('.choo_type').show();
			$('.choo_type1').show();
		}
		else{
			$('.choo_type').hide();
			$('.choo_type1').hide();

		}
		$('#type').change(function(){
			type=$('#type').val();
			if(type=='Confirmation'){

				$('.choo_type').show();
				$('.choo_type1').show();
			}
			else{

				$('.choo_type').hide();
				$('.choo_type1').hide();

			}

		})


	})
    function get_product(){
      product_stock=$('#stock_idd').val();
        $('#product_option').val(product_stock);
    }

	function trim( str )
	{
	   str = str.replace(/^\s+/, '');
	   str = str.replace(/\s+$/, '');
	   return str;
	}
	//check if an object has a value
	function check_share_links_obj(obj, msg)
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
	function regex_share_links_obj(obj, reg, msg)
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
	function check_share_links_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_share_links_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_share_links_obj(frm.id, "Please enter a valid value for: Id"))return false;
		//if(!check_share_links_obj(frm.name, "Please enter a valid value for: Name"))return false;
		//if(!check_share_links_obj(frm.description, "Please enter a valid value for: Description"))return false;
		//if(!check_share_links_obj(frm.url, "Please enter a valid value for: Url"))return false;
		//if(!check_share_links_obj(frm.img_url, "Please enter a valid value for: Img Url"))return false;

		return true;
	}
	//-->
</script>

<div class="te_edit share_links_edit">
	<form id="pixel_form" enctype="multipart/form-data" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_share_links_form( this );" >
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

			<?php if( $this->write_error_msg != '' ){ ?><div class="error"><?php echo htmlspecialchars( $this->write_error_msg ); ?></div><?php }

			if($this->te_mode == 'insert'){
				echo '<input type="hidden" name="share_link_id" value="'.@$_GET['te_share_link_id'].'" />';
			}

			?>

			<dl class="te">
				<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<label class="te <?php echo $this->get_field_type( 'id' ); ?> id" for="id">ID</label>
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
				<dt class="te <?php echo $this->get_field_type( 'type' ); ?> type" >
					<label class="te <?php echo $this->get_field_type( 'type' ); ?> type" for="type">Type</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_name'; ?>" class="te <?php echo $this->get_field_type( 'type' ); ?> type" >
					<?php $this->draw_input_field( 'type', $this->db['type'], 'edit', 'type' ); ?>

				</dd>

                <dt class="te <?php echo $this->get_field_type( 'type' ); ?> type choo_type" >
                    <label class="te <?php echo $this->get_field_type( 'type' ); ?> type" for="type">Choose Type</label>
                </dt>
                <dd id="<?php echo 'value_field_container_' . $this->te_key . '_name'; ?>" class="te <?php echo $this->get_field_type( 'type' ); ?> type choo_type1" >
                    <?php  echo $sel;?>

                </dd>



                <dt class="te <?php echo $this->get_field_type( 'pixel_value' ); ?> pixel_value" >
					<label class="te <?php echo $this->get_field_type( 'pixel_value' ); ?> pixel_value" for="pixel_value">Pixel</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_pixel_value'; ?>" class="te <?php echo $this->get_field_type( 'pixel_value' ); ?> pixel_value" >
					<?php $this->draw_input_field( 'pixel_value', $this->db['pixel_value'], 'edit', 'pixel_value' ); ?>
				</dd>

				<dt class="te <?php echo $this->get_field_type( 'status' ); ?> status" >
					<label class="te <?php echo $this->get_field_type( 'status' ); ?> status" for="status">Check To Make Pixel Active
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_status'; ?>" class="te <?php echo $this->get_field_type( 'status' ); ?> status" >
					<?php $this->draw_input_field( 'status', $this->db['status'], 'edit', 'status' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'product_option' ); ?> Product Option pro_op" >
					<label class="te <?php echo $this->get_field_type( 'product_option' ); ?> Product Option" for="Product Option">Product Option
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_product_option'; ?>" class="te <?php echo $this->get_field_type( 'product_option' ); ?> Product Option  pro_op1" >
					<?php $this->draw_input_field( 'product_option', $this->db['product_option'], 'edit', 'product_option' ); ?>
				</dd>

			</dl>

			<div class="te_buttons">
			<input class="te te_buttons save_button" type="submit" name="btnSave" value="Save" />
			<?php
				if( $this->is_valid_key( $this->te_key ) && $this->_default_mode_after_save != '' && $this->te_permit[ $this->_default_mode_after_save ] )
				{
					?><input class="te te_buttons cancle_button" type="button" name="btnCancel" value="Cancel" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=' . $this->_default_mode_after_save . '&te_key=' . $this->te_key.'&te_share_link_id='. $this->db['share_link_id'] )); ?>';" /><?php
				}
				elseif( $this->te_permit[ $this->_te_modeDefault ] )

				{
					?><input class="te te_buttons cancle_button" type="button" name="btnCancel" value="Cancel" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=' . $this->_te_modeDefault.'&te_share_link_id='. @$_GET['te_share_link_id'] )); ?>';" /><?php
				}
			?>
			</div>

		</fieldset>
	</form>
</div>
