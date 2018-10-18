<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_product_sample">
		<div id="input_group_#xxxx#">
			<span class="multi_input">
				<input  name="mi_product[#xxxx#][id]" style="width:50px;height: 24px;text-transform:uppercase;" type="text" id="id_#xxxx#" class="input_code"autocomplete="off"/>
			</span><!--LIST:languages-->
			<span class="multi_input">
				<input  name="mi_product[#xxxx#][name_[[|languages.id|]]]" style="width:150px;height: 24px;" type="text" id="name_[[|languages.id|]]_#xxxx#" onblur="copy_name(#xxxx#);" autocomplete="off"/>
			</span>
					<!--/LIST:languages-->
            <span class="multi_input">
				<select  name="mi_product[#xxxx#][unit_id]" style="width:83px;height: 24px;"  id="unit_id_#xxxx#" class="unit_product" ><option value=""></option>
					[[|unit_id_options|]]
				</select>
			</span>
            <span class="multi_input">
                <input  name="mi_product[#xxxx#][image_url]" style="width:265px;height: 24px;" type="file" id="image_url_#xxxx#"  />
			</span>
            <span class="multi_input">
				<select  name="mi_product[#xxxx#][printer_1]" style="width:103px;height: 24px;"  id="printer_1_#xxxx#"><option value=""></option>
					[[|print_id_options|]]
				</select>
			</span>
            <span class="multi_input">
				<select  name="mi_product[#xxxx#][printer_2]" style="width:103px;height: 24px;"  id="printer_2_#xxxx#"><option value=""></option>
					[[|print_id_options|]]
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
                    <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.add_product.]]</td>
					<td width="30%" align="right"><a class="w3-btn w3-orange w3-text-white" onclick="AddProductForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Save_and_close.]]</a>
                    <a class="w3-btn w3-green w3-text-white" onclick="history.go(-1)" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td>                    
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
			<legend class="title">[[.type.]]</legend>
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td><strong>[[.type.]]</strong>: <select  name="type" id="type" style="width:150px; height: 24px;">
				<option value="GOODS">[[.goods.]]</option>
                <option value="PRODUCT">[[.product.]]</option>
                <option value="DRINK">[[.drink.]]</option>
                <option value="MATERIAL">[[.material.]]</option>
                <option value="EQUIPMENT">[[.equipment.]]</option>
                <option value="SERVICE">[[.service.]]</option>
                <option value="TOOL">[[.tool.]]</option>
			</select>
			<script>
				$('type').value='<?php echo URL::get('type','PRODUCT');?>';
			</script>
            <strong>[[.category_id.]]</strong>: <select name="category_id" id="category_id" style="width:150px; height: 24px;"></select>
            
            <strong>Đơn vị:</strong>
            <select onchange="change_unit(this.value)" style="height: 24px;"><option value=""></option>
    					[[|unit_id_options|]]
    		</select>
			</tr>
		</table>
        </fieldset>
        <br />
       
		<fieldset>
			<legend class="title">[[.goods.]]</legend>
				<span id="mi_product_all_elems">
					<span>
						<span class="multi-input-header"><span style="width:50px; height: 24px;">[[.code.]]</span></span>
						<!--LIST:languages-->
						<span class="multi-input-header"><span style="width:150px; height: 24px;">[[.name.]]([[|languages.code|]]))</span></span>
						<!--/LIST:languages-->
                        <span class="multi-input-header"><span style="width:85px; height: 24px;">[[.unit_id.]]</span></span>
                        <span class="multi-input-header"><span style="width:265px; height: 24px;">[[.image_url.]]</span></span>
                        <span class="multi-input-header"><span style="width:100px; height: 24px;">[[.printer.]] 1</span></span>
                        <span class="multi-input-header"><span style="width:100px; height: 24px;">[[.printer.]] 2</span></span>
                        <span class="multi-input-header"><span style="width:20px; height: 24px;"><img src="skins/default/images/spacer.gif"/></span></span>
                        <br style="clear:both;"/>
					</span>
				</span>
			<input type="button" class="w3-btn w3-cyan w3-text-white" value="[[.add_item.]]" style="text-transform: uppercase; margin-top: 5xp;" onclick="mi_add_new_row('mi_product');jQuery('#id_'+input_count).ForceCodeOnly()"/>
		</fieldset>
		</td>
	</tr>
	</table>
	</form>
	</td></tr>
</table>
</div>
<script>
function change_unit(value_unit)
{
    var valueunit = [[|unit_js|]];
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
