<form method="post" name="laundry">
<div>
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
</div>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td class="" style="text-transform: uppercase; font-size: 18px;" width="60%"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.laundry_services.]]</td>
		<td width="40%" style="text-align: right; padding-right: 30px;"><a href="javascript:void(0);" class="w3-btn w3-cyan w3-text-white" onclick="mi_add_new_row('mi_laundry_product','true'); set_action(input_count);" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.add_item.]]</a>
		<input type="submit" class="w3-btn w3-orange w3-text-white" name="cmd" value="[[.Save_and_close.]]"  style="text-transform: uppercase; margin-right: 5px;"/>
        <input type="submit" class="w3-btn w3-red" name="delete" onclick="if(!confirm('[[.are_you_sure.]]'))  return false;" value="[[.delete.]]"  style="text-transform: uppercase;" /></td>
	</tr>
</table>
<span style="display:none">
	<span id="mi_laundry_product_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<input type="hidden" name="mi_laundry_product[#xxxx#][id]" id="id_#xxxx#" />
			<span class="multi_edit_input_header">
				<span><input name="selected_ids[]" type="checkbox" id="_checked_#xxxx#" onclick="change_value(#xxxx#)"/></span>
			</span>
			<span class="multi_edit_input">
				<input name="mi_laundry_product[#xxxx#][product_id]" style="width:85px; text-transform: uppercase; font-weight: normal; height: 24px; margin-bottom: 1px;" class="multi_edit_text_input new_style" type="text" id="product_id_#xxxx#"/>
                <input  name="mi_laundry_product[#xxxx#][action]" type="hidden" id="action_#xxxx#" value="edit"/>
			</span>
			<span class="multi_edit_input">
				<input style="width:210px; font-weight: normal; height: 24px;" name="mi_laundry_product[#xxxx#][name_1]" class="multi_edit_text_input new_style" type="text" id="name_1_#xxxx#"/>
			</span>
			<span class="multi_edit_input">
				<input style="width:210px; font-weight: normal; height: 24px;" name="mi_laundry_product[#xxxx#][name_2]" class="multi_edit_text_input new_style" type="text" id="name_2_#xxxx#"/>
			</span>
			<!--LIST:categories-->
			<span class="multi_edit_input">
                <input style="width:96px; text-align:right; font-weight: normal; height: 24px;" class="multi_edit_text_input new_style input_number" name="mi_laundry_product[#xxxx#][price_[[|categories.code|]]]" type="text" id="price_[[|categories.code|]]_#xxxx#" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" />
			</span>
            <span class="multi_edit_input">
                <input style="width:96px; text-align:right; font-weight: normal; height: 24px;" class="multi_edit_text_input new_style input_number" name="mi_laundry_product[#xxxx#][original_price_[[|categories.code|]]]" type="text" id="original_price_[[|categories.code|]]_#xxxx#" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" />
			</span>
			<!--/LIST:categories-->
			<span class="multi_edit_input">
				<select style="width: 60px; font-weight: normal; height: 24px;"  name="mi_laundry_product[#xxxx#][unit_id]"   id="unit_#xxxx#" class="new_style">[[|unit_id_options|]]</select>
			</span>
			<span class="multi_edit_input">
				<input style="width:40px; height: 24px;" class="multi_edit_text_input new_style" name="mi_laundry_product[#xxxx#][order_number]" type="text" id="order_number_#xxxx#" />
			</span>			
			<!--IF:edit(User::can_edit(false,ANY_CATEGORY))-->
			<span class="multi_edit_input">
				<input type="hidden" name="mi_laundry_product[#xxxx#][status]" id="status_#xxxx#" value="avaiable"  />
				<a href="#" id="img_change_status_#xxxx#" style="width:20px" onclick="hide_row('#xxxx#',this);event.returnValue=false;"><i class="fa fa-check-circle" aria-hidden="true" id="change_icon_#xxxx#" style="color:green; font-size: 20px;"></i></a>
			</span>
			<!--/IF:edit-->
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi_edit_input"><span style="width:20;">
				<a href="#" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_laundry','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"><i class="fa fa-times" aria-hidden="true" style="color:red; font-size: 20px;"></i></a>
			</span></span>
			<!--/IF:delete-->
			<br/>
		</span>
	</span>
</span>
<input type="hidden" name="deleted_ids" id="deleted_ids" />
<input type="hidden" name="hide_ids" id="hide_ids" value="[[|hides|]]" />
<fieldset>
<div class="w3-light-gray" >
	<span id="mi_laundry_product_all_elems">
		<span style="white-space:nowrap;">
			<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_laundry_product',this.checked); change_value_all();"/></span></span>
			<span class="multi_edit_input_header"><span class="table-title" style="width:85px; font-weight: normal;">[[.product_id.]]</span></span>
			<span class="multi_edit_input_header"><span class="table-title" style="width:210px; font-weight: normal;">[[.product_name_vn.]]</span></span>
			<span class="multi_edit_input_header"><span class="table-title" style="width:210px; font-weight: normal;">[[.product_name_en.]]</span></span>
			<!--LIST:categories-->
			<span class="multi_edit_input_header"><span class="table-title" style="width:96px; font-weight: normal;">[[.price.]]([[|categories.name|]])</span></span>
            <span class="multi_edit_input_header"><span class="table-title" style="width:96px; font-weight: normal;">[[.original_price.]]([[|categories.name|]])</span></span>
			<!--/LIST:categories-->
			<span class="multi_edit_input_header"><span class="table-title" style="width:60px; font-weight: normal;">[[.product_unit.]]</span></span>
			<span class="multi_edit_input_header"><span class="table-title" style="width:40px; text-align: right; font-weight: normal;">[[.order_number.]]</span></span>
			<br/>
		</span>
		<br />
	</span>
</div>
<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" id="add_new" type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_laundry_product');set_action(input_count);"/>
</fieldset>
<!--IF:cond(User::can_edit(false,ANY_CATEGORY))-->
<div style="margin-top:20px; text-align:center">
<!--
<input type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_laundry_product','true');">
<input type="submit" name="cmd" value="[[.save.]]" />
-->
</div>
<!--/IF:cond-->
</form>

<script>
mi_init_rows('mi_laundry_product',
	<?php if(isset($_REQUEST['mi_laundry_product']))
	{
		echo String::array2js($_REQUEST['mi_laundry_product']);
	}
	else
	{
		echo '[]';
	}
	?>);//,'product_id'
jQuery(document).ready(function(){
    for(var i = 100; i <= input_count; i++)
    {
        if(jQuery('#status_'+i).val()=='NO_USE')
        {
            jQuery('#change_icon_'+i).removeClass("fa fa-check-circle");
            jQuery('#change_icon_'+i).addClass("fa fa-ban");
            jQuery('#change_icon_'+i).css("color", 'red');
        }
    }
})
function hide_row(index,obj)
{
    console.log($('status_'+index).value);
	if($('status_'+index).value=='NO_USE')
	{
		$('status_'+index).value = 'avaiable';
        jQuery('#change_icon_'+index).removeClass("fa fa-ban");
		jQuery('#change_icon_'+index).addClass("fa fa-check-circle");
        jQuery('#change_icon_'+index).css("color", 'green');
		obj.title = '[[.in_use.]]';
	}else
	{
		$('status_'+index).value='NO_USE';
		jQuery('#change_icon_'+index).addClass("fa fa-ban");
        jQuery('#change_icon_'+index).css("color", 'red');
		obj.title = '[[.no_use.]]';
	}
}
function Confirm(index)
{
	var product = $('product_id_'+index).value;
	return confirm('[[.Are_you_sure_delete_product.]] '+product+'?[[.Delete_this_product_maybe_affect_all_invoice.]],[[.You_can_change_status_Notuse_if_you_dont_use_it.]]');
}

function set_action(input_count)
{
    $('action_'+input_count).value='add';
}
function change_value(input_count)
{
    if(jQuery('#_checked_'+input_count).is(':checked'))
        jQuery('#_checked_'+input_count).val(jQuery('#product_id_'+input_count).val());
    else
        jQuery('#_checked_'+input_count).val('');
}

function change_value_all()
{
    var start = 101;
    for(start; start<=input_count; start++)
    {
        change_value(start);
    }
    
}

</script>
<style>
.new_style
{
    /*width: 200px;*/
    border: 1px solid gray;
    padding: 1px;
    border-radius: 3px;
    box-shadow: 0px 0px 1px #555 inset;
    /*color: #505050;*/
}
</style>