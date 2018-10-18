<span style="display:none">
	<span id="mi_material_product_sample">
		<div id="input_group_#xxxx#" style="text-align:left;display:block;">
            <input name="mi_material_product[#xxxx#][price_id]" type="hidden" id="price_id_#xxxx#" />
			<span class="multi-input" style="width:25px;"><input  type="checkbox" id="_checked_#xxxx#"></span>
			<span class="multi-input"><input name="mi_material_product[#xxxx#][id]" type="text" readonly="readonly" id="id_#xxxx#" style="text-align:right;width:50px;background-color:#CCC;" value="(auto)"></span>
			<span class="multi-input"><input name="mi_material_product[#xxxx#][material_id]" type="text" id="material_id_#xxxx#" style="width:150px;" onkeyup="recalculate_product_limit(this,input_count);" tabindex="2"></span>
            <span class="multi-input"><input name="mi_material_product[#xxxx#][product_name]" type="text" id="product_name_#xxxx#" style="width:200px;background-color:#CCC;"  tabindex="2" readonly="readonly"></span>
			<span class="multi-input"><input name="mi_material_product[#xxxx#][quantity]" style="width:100px;" type="text" id="quantity_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2"></span>
			<span class="multi-input"><input name="mi_material_product[#xxxx#][unit_id]" style="width:100px;background-color:#CCC;" type="text" id="unit_id_#xxxx#" readonly="readonly"></span>            
			<span class="multi-input">
				<img src="packages/cms/skins/default/images/admin/Icon/delete2.png" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_minibar_product','#xxxx#','');event.returnValue=false;" style="cursor:pointer;"/>
			</span><br clear="all">
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div class="body">
	<form name="EditProductLimitForm" method="post" >
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="80%" class="form-title">[[.product_limit.]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('type'));?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<!--IF:add(URL::get('cmd')!='add')-->
    <div style="clear:both;">
	[[.product_id.]]: <select name="product_price_id" id="product_price_id" onchange="this.form.submit();" style="width:200px;"></select>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current();?>'" value="[[.return.]]" />
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current(array('product_id','cmd'=>'remove_all'));?>'" value="[[.remove.]] [[|name|]]"/>
    </div>
	<!--/IF:add-->
    <br clear="all" />    
    <div>
        <input  name="confirm_edit" type="hidden" value="1">
        <input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
        <input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
        <?php Form::$current->error_messages();?>
        <!--IF:add(URL::get('cmd')=='add')-->
        <div>        
        [[.product_id.]]: <select name="product_price_id" id="product_price_id" style="width:200px;"><option value="">[[.add_to_all.]]</option>[[|product_id_options|]]</select>
        </div>
        <!--/IF:add-->
        <script>
            $('product_id').value="<?php echo URL::get('product_id');?>";
        </script>
        <span id="mi_material_product_all_elems">
            <span class="multi-input-header"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_material_product',this.checked);"></span>
            <span class="multi-input-header" style="width:50px;">[[.id.]]</span>
            <span class="multi-input-header" style="width:150px;">[[.material_id.]]</span>
            <span class="multi-input-header" style="width:200px; ">[[.material_name.]]</span>
            <span class="multi-input-header" style="width:100px;">[[.norm_quantity.]]</span>
            <span class="multi-input-header" style="width:100px;">[[.unit.]]</span>
            <span class="multi-input-header" style="width:20px;"><img src="packages/core/skins/default/images/spacer.gif"/></span>
            <br clear="all">
        </span>
        <!--IF:add(URL::get('cmd')=='add')-->
            <input type="button" value="   [[.add_item.]]   " onclick="mi_add_new_row('mi_material_product');$('material_id_'+input_count).focus();autocompleteProduct(input_count);">
        <!--ELSE-->
        [[|paging|]]
        <!--/IF:add-->
        </form>
    </div>
</div>
<script>
function autocompleteProduct(id)
{
	jQuery("#material_id_"+id).autocomplete({
		url: 'get_product.php?material=1',
	 	onItemSelect: function(item) {
			if (item.data) {
				str = new String(item.data);
				id_price = str.match(/\[[0-9]+\]/);
				id_price = id_price.toString();
				id_price = id_price.replace('[','');
				id_price = id_price.replace(']','');
				//------------------
				$('price_id_'+id).value = id_price;
                recalculate_product_limit($('price_id_'+id),id);
			}
		}            
	});
}

mi_init_rows('mi_material_product',
	<?php if(isset($_REQUEST['mi_material_product']))
	{
		echo String::array2js($_REQUEST['mi_material_product']);
	}
	else
	{
		echo '[]';
	}
	?>);
function recalculate_product_limit(obj,input_count)
{
	var columns=all_forms['mi_material_product'];  
	//for(var i in columns)
	{ 
		if(1 && getElemValue(obj))
		{
			$('product_name_'+input_count).value=
				( (product_array[(getElemValue(obj))]?product_array[(getElemValue(obj))].name:''));
			$('unit_id_'+input_count).value=
				( (product_array[(getElemValue(obj))]?product_array[(getElemValue(obj))].unit:''));
		}
	}
	
}
//recalculate_product_limit();
</script>