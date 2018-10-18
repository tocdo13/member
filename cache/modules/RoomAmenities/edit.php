<span style="display:none">
	<span id="mi_room_amenities_sample">
		<div id="input_group_#xxxx#">
            <span class="multi-input">
				<input name="mi_room_amenities[#xxxx#][product_id]" style="width:100px;" class="multi_edit_text_input" type="text" id="product_id_#xxxx#" tabindex="2" onblur="recalculate_minibar_product();"/>
			</span>
            
            <span class="multi-input">
				<input style="width:200px;" class="multi_edit_text_input readonly" readonly="readonly" type="text" id="product_name_#xxxx#"  tabindex="2"/>
			</span>
  	       
  	         <span class="multi-input">
				<input  name="mi_room_amenities[#xxxx#][norm_quantity]" style="width:150px;" class="multi_edit_text_input input_number" type="text" id="norm_quantity_#xxxx#" tabindex="2"/>
			</span>
            <!--
			<span class="multi-input">
				<input  name="mi_room_amenities[#xxxx#][price]" style="width:80px;" class="multi_edit_text_input readonly format_number input_number" type="text" id="price_#xxxx#" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2"/>
			</span>
            
			<span class="multi-input">
                <input  name="mi_room_amenities[#xxxx#][quantity]" style="width:80px;" class="multi_edit_text_input readonly" type="text" id="quantity_#xxxx#" readonly="readonly" tabindex="2"/>
			</span>
            		
			<span class="multi-input">
				<input  name="mi_room_amenities[#xxxx#][norm_quantity]" style="width:80px;" class="multi_edit_text_input input_number" type="text" id="norm_quantity_#xxxx#" tabindex="2"/>
			</span>
            -->
			<span class="multi-input">
				<input   name="mi_room_amenities[#xxxx#][position]" style="width:50px;" class="multi_edit_text_input input_number" type="text" id="position_#xxxx#"  tabindex="2" class="input_number"/>
			</span>
            
			<span class="multi-input">
                <span style="width:20px;">
    				<img src="packages/core/skins/default/images/buttons/delete.png" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_room_amenities','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
    			</span>
            </span>
            <br clear="all"/>
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('minibars_norm'));?>
<div class="body">
<form name="EditMinibarProductForm" method="post" >
	<table cellpadding="15" cellspacing="0" width="50%" border="0" bordercolor="#CCCCCC" class="table-bound" style="margin-left: 20px;">
		<tr>        	
			<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('minibars_norm');?> (<?php if(Url::get('cmd')=='add'){echo Portal::language('asc_amenities');}else{echo Portal::language('desc_amenities');} ?>)</td>			
			<td width="45%"><input type="button" value="<?php echo Portal::language('apply');?>" onclick="checkClick();" class="w3-btn w3-orange w3-text-white" name="confirm_edit" style="text-transform: uppercase; margin-right: 5px;" />
                <input type="button" name="list" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current();?>'" value="<?php echo Portal::language('back');?>" style="text-transform: uppercase; margin-right: 5px;"/>                
            </td>
		</tr>
    </table>      
	<input type="hidden" name="rooms" id="rooms" value="<?php echo Url::get('rooms');?>" />
    <input type="hidden" name="cmd" id="cmd" value="<?php echo Url::get('cmd');?>" />
	<div style="margin-left: 20px;">
		<span id="mi_room_amenities_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi-input-header" style="width:100px;height: 25px;"><?php echo Portal::language('product_id');?></span>
				<span class="multi-input-header" style="width:200px;height: 25px;"><?php echo Portal::language('product_name');?></span>
                	
                    <span class="multi-input-header" style="width:150px;height: 25px;"><?php echo Portal::language('norm_quantity');?> <?php if(Url::get('cmd')=='add'){echo 'Tăng';}else{echo 'Giảm';} ?></span>
				<!--<span class="multi-input-header" style="width:80px;"><?php echo Portal::language('price');?></span>
				<span class="multi-input-header" style="width:80px;"><?php echo Portal::language('used_quantity');?></span>
				<span class="multi-input-header" style="width:80px;"><?php echo Portal::language('norm_quantity');?></span>-->
				<span class="multi-input-header" style="width:50px;height: 25px;"><?php echo Portal::language('position');?></span>
				<span class="multi-input-header" style="width:20px;height: 25px;"><img src="packages/core/skins/default/images/spacer.gif"/></span>
				<br clear="all"/>
			</span>            
		</span>
        <?php if(Url::get('cmd')!='remove_all'){?>
		<input type="button" value="<?php echo Portal::language('add_product');?>" onclick="mi_add_new_row('mi_room_amenities');my_autocomplete();jQuery('#position_'+input_count).ForceNumericOnly();jQuery('#norm_quantity_'+input_count).ForceNumericOnly();" style="text-transform: uppercase; margin-top: 5px;" class="w3-btn w3-cyan w3-text-white"/>
        <?php }?>        
	</div>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>

<script>
var check=0;
jQuery(document).ready(function(){

});
function recalculate_minibar_product()
{
	var columns=all_forms['mi_room_amenities'];
    for(var i in columns)
	{
        for(var j in product_array)
        {
    		if(getElemValue('product_id_'+columns[i]) == product_array[j]['product_id'])
    		{
    			$('product_name_'+columns[i]).value = product_array[j]['name'];
    			//$('price_'+columns[i]).value=product_array[j]['price'];
                break;
    		}
            else
            {
                $('product_name_'+columns[i]).value = '';
    			//$('price_'+columns[i]).value= '';
            }
            
        }
    }
}
function my_autocomplete()
{
	jQuery("#product_id_"+input_count).autocomplete({
		url: 'get_product.php?amenities=1',
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
mi_init_rows('mi_room_amenities',
	<?php if(isset($_REQUEST['mi_room_amenities']))
	{		
		echo String::array2js($_REQUEST['mi_room_amenities']);
	}
	else
	{
		echo '[]';
	}
	?>);
recalculate_minibar_product();

function submit_form(room_id)
{
	if(room_id!='')
	{
		var url = '?page=room_amenities';
		url += '&room_id='+room_id;
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
        jQuery("#room_id").attr('disabled',false);
        jQuery("#room_id").css('display','block');
    }
    else
    {
        jQuery("#room_id").attr('disabled',true);
        jQuery("#room_id").css('display','none');
        jQuery("#room_level_id").attr('disabled',false);
        jQuery("#room_level_id").css('display','block');
    }
        
}
function checkClick(){
    check+=1;
    if(check==1){
        EditMinibarProductForm.submit();
    }else{
        check=0;
    }
}

</script>