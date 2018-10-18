<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_product_sample">
		<div id="input_group_#xxxx#">
			<span class="multi_input">
				<input  name="mi_product[#xxxx#][id]" style="width:50px;height: 24px;text-transform:uppercase;" type="text" id="id_#xxxx#" class="input_code"autocomplete="off"/>
			</span><?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
			<span class="multi_input">
				<input  name="mi_product[#xxxx#][name_<?php echo $this->map['languages']['current']['id'];?>]" style="width:150px;height: 24px;" type="text" id="name_<?php echo $this->map['languages']['current']['id'];?>_#xxxx#" onblur="copy_name(#xxxx#);" autocomplete="off"/>
			</span>
					<?php }}unset($this->map['languages']['current']);} ?>
            <span class="multi_input">
				<select   name="mi_product[#xxxx#][unit_id]" style="width:83px;height: 24px;"  id="unit_id_#xxxx#" class="unit_product" ><option value=""></option>
					<?php echo $this->map['unit_id_options'];?>
				</select>
			</span>
            <span class="multi_input">
                <input  name="mi_product[#xxxx#][image_url]" style="width:265px;height: 24px;" type="file" id="image_url_#xxxx#"  />
			</span>
            <span class="multi_input">
				<select   name="mi_product[#xxxx#][printer_1]" style="width:103px;height: 24px;"  id="printer_1_#xxxx#"><option value=""></option>
					<?php echo $this->map['print_id_options'];?>
				</select>
			</span>
            <span class="multi_input">
				<select   name="mi_product[#xxxx#][printer_2]" style="width:103px;height: 24px;"  id="printer_2_#xxxx#"><option value=""></option>
					<?php echo $this->map['print_id_options'];?>
				</select>
			</span>
			<span class="multi_input"><span style="width:20;height: 24px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','');event.returnValue=false;" style="cursor:hand;">
			</span></span><br/>
		</div>
	</span>
</span>
<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_product'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('add_product');?></td>
					<td width="30%" align="right"><a class="w3-btn w3-orange w3-text-white" onclick="AddProductForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Save_and_close');?></a>
                    <a class="w3-btn w3-green w3-text-white" onclick="history.go(-1)" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('back');?></a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
	<td>
	<form name="AddProductForm" method="post" enctype="multipart/form-data" >
    <!--<input  name="test" type="file" id="test"   />-->
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
    <table width="100%" cellpadding="5">
<?php if(Form::$current->is_error())
	{
	?><tr valign="top">
	<td bgcolor="#C8E1C3"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?>
	<tr valign="top">
		<td>
        <fieldset>
			<legend class="title"><?php echo Portal::language('type');?></legend>
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td><strong><?php echo Portal::language('type');?></strong>: <select   name="type" id="type" style="width:150px; height: 24px;">
				<option value="GOODS"><?php echo Portal::language('goods');?></option>
                <option value="PRODUCT"><?php echo Portal::language('product');?></option>
                <option value="DRINK"><?php echo Portal::language('drink');?></option>
                <option value="MATERIAL"><?php echo Portal::language('material');?></option>
                <option value="EQUIPMENT"><?php echo Portal::language('equipment');?></option>
                <option value="SERVICE"><?php echo Portal::language('service');?></option>
                <option value="TOOL"><?php echo Portal::language('tool');?></option>
			</select>
			<script>
				$('type').value='<?php echo URL::get('type','PRODUCT');?>';
			</script>
            <strong><?php echo Portal::language('category_id');?></strong>: <select  name="category_id" id="category_id" style="width:150px; height: 24px;"><?php
					if(isset($this->map['category_id_list']))
					{
						foreach($this->map['category_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))
                    echo "<script>$('category_id').value = \"".addslashes(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))."\";</script>";
                    ?>
	</select>
            
            <strong>Đơn vị:</strong>
            <select onchange="change_unit(this.value)" style="height: 24px;"><option value=""></option>
    					<?php echo $this->map['unit_id_options'];?>
    		</select>
			</tr>
		</table>
        </fieldset>
        <br />
       
		<fieldset>
			<legend class="title"><?php echo Portal::language('goods');?></legend>
				<span id="mi_product_all_elems">
					<span>
						<span class="multi-input-header"><span style="width:50px; height: 24px;"><?php echo Portal::language('code');?></span></span>
						<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key2=>&$item2){if($key2!='current'){$this->map['languages']['current'] = &$item2;?>
						<span class="multi-input-header"><span style="width:150px; height: 24px;"><?php echo Portal::language('name');?>(<?php echo $this->map['languages']['current']['code'];?>))</span></span>
						<?php }}unset($this->map['languages']['current']);} ?>
                        <span class="multi-input-header"><span style="width:85px; height: 24px;"><?php echo Portal::language('unit_id');?></span></span>
                        <span class="multi-input-header"><span style="width:265px; height: 24px;"><?php echo Portal::language('image_url');?></span></span>
                        <span class="multi-input-header"><span style="width:100px; height: 24px;"><?php echo Portal::language('printer');?> 1</span></span>
                        <span class="multi-input-header"><span style="width:100px; height: 24px;"><?php echo Portal::language('printer');?> 2</span></span>
                        <span class="multi-input-header"><span style="width:20px; height: 24px;"><img src="skins/default/images/spacer.gif"/></span></span>
                        <br style="clear:both;"/>
					</span>
				</span>
			<input type="button" class="w3-btn w3-cyan w3-text-white" value="<?php echo Portal::language('add_item');?>" style="text-transform: uppercase; margin-top: 5xp;" onclick="mi_add_new_row('mi_product');jQuery('#id_'+input_count).ForceCodeOnly()"/>
		</fieldset>
		</td>
	</tr>
	</table>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td></tr>
</table>
</div>
<script>
function change_unit(value_unit)
{
    var valueunit = <?php echo $this->map['unit_js'];?>;
    //console.log(value_unit);
   // jQuery('.unit_product').val(valueunit[value_unit]['name']);
    jQuery('.unit_product').val(value_unit);
}
mi_init_rows('mi_product',<?php if(isset($_REQUEST['mi_product'])){ echo String::array2js($_REQUEST['mi_product']);}else{ echo '[]';}?>);

function copy_name(id)
{
    var temp = '';
    if ( !jQuery('#name_1_'+id).val() || !jQuery('#name_2_'+id).val())
        temp = jQuery('#name_1_'+id).val()?jQuery('#name_1_'+id).val():jQuery('#name_2_'+id).val();
    if(!jQuery('#name_1_'+id).val())
        $('name_1_'+id).value = temp;
    if(!jQuery('#name_2_'+id).val())
        $('name_2_'+id).value = temp;
}

</script>
