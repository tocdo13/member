<span style="display:none">
	<span id="mi_minibar_product_sample">
		<div id="input_group_#xxxx#">
            <input name="mi_minibar_product[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <span class="multi-input">
				<input name="mi_minibar_product[#xxxx#][product_id]" style="width:80px;" class="multi_edit_text_input" type="text" id="product_id_#xxxx#" tabindex="2" onblur="recalculate_minibar_product();"/>
			</span>
            <span class="multi-input">
				<input style="width:200px;" class="multi_edit_text_input readonly" readonly="readonly" type="text" id="product_name_#xxxx#"  tabindex="2"/>
			</span>
			<span class="multi-input">
				<input  name="mi_minibar_product[#xxxx#][price]" style="width:80px;" class="multi_edit_text_input readonly format_number input_number" type="text" id="price_#xxxx#" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2"/>
			</span>
			<span class="multi-input">
                <input  name="mi_minibar_product[#xxxx#][quantity]" style="width:80px;" class="multi_edit_text_input readonly" type="text" id="quantity_#xxxx#" readonly="readonly" tabindex="2"/>
			</span>
			<span class="multi-input">
				<input  name="mi_minibar_product[#xxxx#][norm_quantity]" style="width:80px;" class="multi_edit_text_input input_number" type="text" id="norm_quantity_#xxxx#" tabindex="2"/>
			</span>
			<span class="multi-input">
				<input   name="mi_minibar_product[#xxxx#][position]" style="width:50px;" class="multi_edit_text_input input_number" type="text" id="position_#xxxx#"  tabindex="2" class="input_number"/>
			</span>
			<span class="multi-input">
                <span style="width:20px;">
    				<img src="packages/core/skins/default/images/buttons/delete.png" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_minibar_product','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
    			</span>
            </span>
            <br clear="all"/>
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('minibars_norm'));?><div class="body">
<form name="EditMinibarProductForm" method="post" >
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>        	
			<td width="55%" class="form-title">[[.minibars_norm.]]</td>			
			<td width="1%"><input type="submit" value="[[.apply.]]" class="button-medium-save" name="confirm_edit" /></td>
			<td width="1%">
                <input type="button" name="list" class="button-medium-back" onclick="window.location='<?php echo Url::build_current();?>'" value="[[.back.]]" />                
            </td>
		</tr>
    </table>
    
    <div><?php if(Form::$current->is_error()){ echo Form::$current->error_messages(); }?></div>
    
	<fieldset>
        <table>
                <!--IF:add(URL::get('cmd')!='add')-->
                <tr>
                    <td><strong>[[.select_minibar_id.]]: </strong></td>
                    <td><select  name="minibar_id" id="minibar_id" onchange="submit_form(this.value);">[[|minibar_id_options|]]</select></td>
                </tr>
                <!--/IF:add-->
                

                <!--IF:add(URL::get('cmd')=='add')-->
                <tr>
                    <td><input name="select_type" type="radio" checked="checked" id="single" onclick="check_radio();" /></td>
                    <td><strong ><label for="single">[[.select_minibar_id.]] : </label></strong></td>
                    <td><select  name="minibar_id" id="minibar_id" style="width:150px"><option value="">[[.add_to_all.]]</option></select></td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td><input name="select_type" type="radio" id="double" onclick="check_radio();" /></td>
                    <td><strong><label for="double">[[.minibar_type.]] : </label></strong></td>
                    <td><select name="room_level_id" id="room_level_id" style="width:150px" ></select></td>
                </tr>
                <!--/IF:add-->
        </table>
    </fieldset>
    <br /><br />
        
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>"/>
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    
	<script>
		$('minibar_id').value="<?php echo URL::get('minibar_id');?>";
	</script>
	<div>
		<span id="mi_minibar_product_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi-input-header" style="width:80px;">[[.product_id.]]</span>
				<span class="multi-input-header" style="width:200px;">[[.product_name.]]</span>
				<span class="multi-input-header" style="width:80px;">[[.price.]]</span>
				<span class="multi-input-header" style="width:80px;">[[.used_quantity.]]</span>
				<span class="multi-input-header" style="width:80px;">[[.norm_quantity.]]</span>
				<span class="multi-input-header" style="width:50px;">[[.position.]]</span>
				<span class="multi-input-header" style="width:20px;"><img src="packages/core/skins/default/images/spacer.gif"/></span>
				<br clear="all"/>
			</span>            
		</span>
        <?php if(Url::get('cmd')!='remove_all'){?>
		<input type="button" value="[[.add_product.]]" onclick="mi_add_new_row('mi_minibar_product');my_autocomplete();jQuery('#position_'+input_count).ForceNumericOnly();jQuery('#norm_quantity_'+input_count).ForceNumericOnly();" style="width:auto;"/>
        <?php }?>        
	</div>
	[[|paging|]]
	</form>
</div>

<script>

jQuery(document).ready(function(){
    <!--IF:add(URL::get('cmd')=='add')-->
    check_radio();
    <!--/IF:add-->
});
function recalculate_minibar_product()
{
	var columns=all_forms['mi_minibar_product'];
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

function submit_form(minibar_id)
{
	if(minibar_id!='')
	{
		var url = '?page=minibar_product';
		url += '&minibar_id='+minibar_id;
		window.location = url.replace('#','%23');
	}
}

function check_radio()
{
    var id = jQuery(":checked").attr("id");
    if(id=='single')
    {
        jQuery("#room_level_id").attr('disabled',true);
        jQuery("#room_level_id").css('display','none');
        jQuery("#minibar_id").attr('disabled',false);
        jQuery("#minibar_id").css('display','block');
    }
    else
    {
        jQuery("#minibar_id").attr('disabled',true);
        jQuery("#minibar_id").css('display','none');
        jQuery("#room_level_id").attr('disabled',false);
        jQuery("#room_level_id").css('display','block');
    }
        
}


</script>