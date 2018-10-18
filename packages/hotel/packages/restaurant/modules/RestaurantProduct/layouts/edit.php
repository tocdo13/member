<?php 
$can_delete = ListRestaurantProductForm::check_product_delete(Url::get('selected_ids'));
?>
<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_product_sample">
		<span id="input_group_#xxxx#">
			<span class="multi_input">
					<input  name="mi_product[#xxxx#][id]" type="hidden" id="id_#xxxx#" />
					<input  name="mi_product[#xxxx#][code]" style="width:50px;" type="text" id="code_#xxxx#">
			</span><!--LIST:languages-->
			<span class="multi_input">
				<input  name="mi_product[#xxxx#][name_[[|languages.id|]]]" style="width:150px;" type="text" id="name_[[|languages.id|]]_#xxxx#" >
			</span>
					<!--/LIST:languages-->
			<span class="multi_input">
				<select  name="mi_product[#xxxx#][category_id]" id="category_id_#xxxx#" style="width:150px;"><option value=""></option>
					[[|category_id_options|]]</select>
			</span>
			<span class="multi_input">
				<select  name="mi_product[#xxxx#][type]" id="type_#xxxx#" style="width:100px;">
				<option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
				</select>
			</span>
			<span class="multi_input">
				<select  name="mi_product[#xxxx#][unit_id]" style="width:60px;"  id="unit_id_#xxxx#"><option value=""></option>
					[[|unit_id_options|]]
				</select>
			</span>
			<?php
			if(URL::get('type')!='MATERIAL' and URL::get('type')!='EQUIPMENT' and URL::get('type')!='TOOL')
			{
			?>
			<span class="multi_input">
					<input  name="mi_product[#xxxx#][price]" style="width:100px;" type="text" id="price_#xxxx#" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
			</span>
			<?php
			}
			?>
            <?php if(!$can_delete and User::can_delete('false',ANY_CATEGORY)){?><span class="multi_input"><span style="width:20;">
            
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span>
            <?php }?>
            <br>
		</span>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('edit_product'));?>
<div align="center">
<form name="EditRestaurantProductForm" method="post">
<table cellspacing="0" width="100%" border="0"style="margin-top:3px;">
    <tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.edit_product.]]</td>
				  <td width="" align="right"><a class="button-medium-save" onclick="EditRestaurantProductForm.submit();">[[.save.]]</a></td>
					<td width="" align="right"><a class="button-medium-back" onclick="history.go(-1);">[[.back.]]</a></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>
	<table width="100%">
	<?php if(Form::$current->is_error())
	{
	?><tr valign="top">
	<td ><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<input  name="cmd" type="hidden" value="edit_selected">
	<tr valign="top">
		<td>
		<fieldset>
					<legend class="title">[[.Edit_product.]]</legend>
						<span id="mi_product_all_elems">
					<span>
						<span class="multi-input-header"><span style="width:50px;">[[.code.]]</span></span>
						<!--LIST:languages-->
						<span class="multi-input-header"><span style="width:150px;">[[.name.]]([[|languages.code|]]))</span></span>
						<!--/LIST:languages-->
						<span class="multi-input-header"><span style="width:150px;">[[.category_id.]]</span></span>
						<span class="multi-input-header"><span style="width:100px;">[[.type.]]</span></span>
						<span class="multi-input-header"><span style="width:60px;">[[.unit_id.]]</span></span>
						<?php
						if(URL::get('type')!='MATERIAL' and URL::get('type')!='EQUIPMENT' and URL::get('type')!='TOOL')
						{
						?>
						<span class="multi-input-header"><span style="width:100px;">[[.price.]]</span></span>
						<?php
						}
						?><span class="multi-input-header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
						<br clear="all">
					</span>
					</span>
		</fieldset>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
</form>
<script>
mi_init_rows('mi_product',
	<?php if(isset($_REQUEST['mi_product']))
	{
		echo String::array2js($_REQUEST['mi_product']);
	}
	else
	{
		echo '[]';
	}
	?>);
function hide_row(index,obj)
{
	if($('status_'+index).value=='NO_USE')
	{
		$('status_'+index).value = 'avaiable';
		obj.src = 'packages/cms/skins/default/images/avaiable.jpg';
		obj.title = '[[.in_use.]]';
	}else
	{
		$('status_'+index).value='NO_USE';
		obj.src = 'packages/cms/skins/default/images/admin/404/icon.png';
		obj.title = '[[.no_use.]]';
	}
}
</script>
