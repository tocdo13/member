<span style="display:none">
	<span id="mi_minibar_product_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input">
				<input type="checkbox" id="_checked_#xxxx#"/>
			</span>
			<span class="multi-input">
				<input name="mi_minibar_product[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="text-align:right;width:55px" value="(auto)" readonly="readonly"/>
			</span>
			<span class="multi-input">
					<input name="mi_minibar_product[#xxxx#][product_id]" style="width:80px;" class="multi_edit_text_input" type="text" id="product_id_#xxxx#" tabindex="2" onblur="recalculate_minibar_product();"/>
			</span>
            <span class="multi-input">
					<input style="width:200px;" class="multi_edit_text_input" type="text" id="product_name_#xxxx#"  tabindex="2"/>
			</span>
			<span class="multi-input">
					<input  name="mi_minibar_product[#xxxx#][price]" style="width:80px;" class="multi_edit_text_input readonly" type="text" id="price_#xxxx#" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2"/>
			</span>

			<!--IF:add(URL::get('cmd')!='add')-->
			<span class="multi-input">
					<input  style="width:60px;" class="multi_edit_text_input" type="text" id="quantity_#xxxx#" readonly="readonly"/>
			</span>
			<!--/IF:add-->			
			<span class="multi-input">
					<input  name="mi_minibar_product[#xxxx#][norm_quantity]" style="width:70px;" class="multi_edit_text_input" type="text" id="norm_quantity_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2"/>
			</span>
			<span class="multi-input">
					<input   name="mi_minibar_product[#xxxx#][position]" style="width:50px;" class="multi_edit_text_input" type="text" id="position_#xxxx#"  tabindex="2"/>
			</span>
			<span class="multi-input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.png" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_minibar_product','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br clear="all"/>
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('minibars_norm'));?><div class="body">
<form name="EditMinibarProductForm" method="post" >
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>        	
			<td width="55%" class="form-title">[[.minibars_norm.]]</td>			
			<!--IF:add1(URL::get('cmd')=='add')-->
			<td width="1%">
		 <a class="button-medium-add" href="javascript:void(0)" onclick="mi_add_new_row('mi_minibar_product');my_autocomplete();$('norm_quantity_'+input_count).focus();">[[.add.]]</a>
	    	</td>
			<!--/IF:add1-->
			<!--IF:add(URL::get('cmd')!='add')-->
			<td width="1%">
			<a class="button-medium-add" href="<?php echo URL::build_current(array('cmd'=>'add','minibar_id'));?>" >[[.add.]]</a>
			</td>
			<!--/IF:add-->
			<td width="1%"><input type="submit" value="[[.apply.]]" class="button-medium-save" name="confirm_edit" /></td>
			<td width="1%"><a href="javascript:void(0)" onclick="mi_delete_selected_row('mi_minibar_product');" class="button-medium-delete" >[[.delete.]]</a></td>
		</tr>
    </table>
	<!--IF:add(URL::get('cmd')!='add')-->
	&nbsp;&nbsp;&nbsp;[[.select_minibar_id.]]: <select  name="minibar_id" id="minibar_id" onchange="this.form.submit();"><option value=""></option>[[|minibar_id_options|]]</select>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current();?>'" value="[[.return.]]"/>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current(array('minibar_id','cmd'=>'remove_all'));?>'" value="[[.remove.]] [[|name|]]"/>
	<!--/IF:add-->
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>"/>
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<!--IF:add(URL::get('cmd')=='add')-->
	[[.select_minibar_id.]]: <select name="minibar_id" id="minibar_id" ><option value="">[[.add_to_all.]]</option>[[|minibar_id_options|]]</select>
	<!--/IF:add-->
	<input type="button" value="[[.discard.]]" onclick="location='<?php echo URL::build_current(array('minibar_id'));?>';"/>
	<script>
		$('minibar_id').value="<?php echo URL::get('minibar_id');?>";
	</script>
	<div>
		<span id="mi_minibar_product_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi-input-header"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_minibar_product',this.checked);"/></span>
				<span class="multi-input-header" style="width:50px;">[[.id.]]</span>
				<span class="multi-input-header" style="width:80px;">[[.product_id.]]</span>
				<span class="multi-input-header" style="width:200px;">[[.product_name.]]</span>
				<span class="multi-input-header" style="width:80px;">[[.price.]]</span>
				<!--IF:add(URL::get('cmd')!='add')-->
				<span class="multi-input-header" style="width:60px;">[[.remaining.]]</span>
				<!--/IF:add-->
				<span class="multi-input-header" style="width:70px;">[[.norm_quantity.]]</span>
				<span class="multi-input-header" style="width:50px;">[[.position.]]</span>
				<span class="multi-input-header" style="width:20px;"><img src="packages/core/skins/default/images/spacer.gif"/></span>
				<br clear="all"/>
			</span>
		</span>
	</div>
	[[|paging|]]
	</form>
</div>
<?php //echo MinibarProduct::create_js_variables(); ?>
<script>
//var data = <?php //echo String::array2suggest($GLOBALS['js_variables']['products']);?>;
function recalculate_minibar_product()
{
	var columns=all_forms['mi_minibar_product'];
	/*
    for(var i in columns)
	{
		if(getElemValue('product_id_'+columns[i]))
		{
			$('product_name_'+columns[i]).value=
				( (products[(getElemValue('product_id_'+columns[i]))]?products[(getElemValue('product_id_'+columns[i]))].name:''));
			$('price_'+columns[i]).value=
				( (products[(getElemValue('product_id_'+columns[i]))]?products[(getElemValue('product_id_'+columns[i]))].price:''));
		}
	} 
    */
    
    
    for(var i in columns)
	{
        for(var j in product_array)
        {
    		if(getElemValue('product_id_'+columns[i]) == product_array[j]['product_id'])
    		{
    			$('product_name_'+columns[i]).value = product_array[j]['name'];
    			$('price_'+columns[i]).value=product_array[j]['price'];
                break;
    		}
            else
            {
                $('product_name_'+columns[i]).value = '';
    			$('price_'+columns[i]).value= '';
            }
            
        }
    }
    
}
function my_autocomplete()
{
	jQuery("#product_id_"+input_count).autocomplete({
		url: 'get_product.php?minibar=1',
        onItemSelect: function(item) {
			recalculate_minibar_product();
		},
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}
	});
}
mi_init_rows('mi_minibar_product',
	<?php if(isset($_REQUEST['mi_minibar_product']))
	{		
		echo String::array2js($_REQUEST['mi_minibar_product']);
	}
	else
	{
		echo '[]';
	}
	?>);
recalculate_minibar_product();
</script>