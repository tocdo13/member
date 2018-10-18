<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_product_sample">
		<div id="input_group_#xxxx#">
			<span class="multi_input">
					<input  name="mi_product[#xxxx#][id]" style="width:45px; height: 24px;" type="text" id="id_#xxxx#" readonly="readonly">
                    <input  name="mi_product[#xxxx#][check]" style="width:45px; height: 24px; display:none;" type="text" id="check_#xxxx#" >
			</span><!--LIST:languages-->
			<span class="multi_input">
				<input  name="mi_product[#xxxx#][name_[[|languages.id|]]]" style="width:150px; height: 24px;" type="text" id="name_[[|languages.id|]]_#xxxx#" onblur="copy_name(#xxxx#)" />
			</span>
					<!--/LIST:languages-->
			<span class="multi_input">
				<select  name="mi_product[#xxxx#][category_id]" id="category_id_#xxxx#" class="cate_product" style="width:150px; height: 24px;">[[|category_id_options|]]</select>
			</span>
			<span class="multi_input">
				<select  name="mi_product[#xxxx#][type]" id="type_#xxxx#"  style="width:100px; height: 24px;" class="type_product">
				<option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="DRINK">[[.drink.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
				</select>
			</span>
			<span class="multi_input">
				<select  name="mi_product[#xxxx#][unit_id]" style="width:60px; height: 24px;"  id="unit_id_#xxxx#" class="unit_product"><option value=""></option>
					[[|unit_id_options|]]
				</select>
			</span>
			<span class="multi_input">
					<input  name="mi_product[#xxxx#][image_url]" style="width:30px; height: 24px;" type="hidden" id="image_url_#xxxx#" readonly="readonly" />
                    <input  name="mi_product[#xxxx#][image_name]" style="width:100px; height: 24px;border:none;cursor: pointer;" type="text" id="image_name_#xxxx#" readonly="readonly" onmouseover="show_img(#xxxx#);" />
                    <input  name="mi_product[#xxxx#][new_image_url]" style="width: 104px; height: 24px;" type="file" id="new_image_url_#xxxx#"  />
			</span>            
            <!--IF:edit(User::can_edit(false,ANY_CATEGORY))-->
			<span class="multi_edit_input">
				<input type="hidden" name="mi_product[#xxxx#][status]" id="status_#xxxx#" />
			</span>
			<!--/IF:edit-->
           	<span class="multi_input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span>
            <br>
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('edit_product'));?>

<div align="center">

<form name="EditProductForm" method="post" enctype="multipart/form-data">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr height="40">
                    <td class="" width="60%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.edit_product.]]</td>
					<td width="40%" align="right" style="padding-right: 30px;"><input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                    <input name="back" type="button" value="[[.back.]]" class="w3-btn w3-green" onclick="window.history.go(-1);" style="text-transform: uppercase; margin-right: 5px;"/></td>
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
	<?php if(Form::$current->is_error()){?><tr valign="top"><td><?php echo Form::$current->error_messages();?></td></tr><?php }?>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<input  name="cmd" type="hidden" value="edit_selected">
    <tr>
        <td>
            <b style="color:#FF6633;">Thay đổi cho sản phẩm</b>
       
        <strong>[[.category_id.]]</strong>: <select style="width:150px; height: 24px;" onchange="change_cate(this.value);" >[[|category_id_options|]]</select>
        <strong>[[.type.]]: </strong><select  name="type" id="type" style="width:150px; height: 24px;" onchange="change_type(this.value);">
        	<option value=""></option>
            <option value="GOODS">[[.goods.]]</option>
            <option value="PRODUCT">[[.product.]]</option>
            <option value="DRINK">[[.drink.]]</option>
            <option value="MATERIAL">[[.material.]]</option>
            <option value="EQUIPMENT">[[.equipment.]]</option>
            <option value="SERVICE">[[.service.]]</option>
            <option value="TOOL">[[.tool.]]</option>
        </select>
         <strong>[[.unit_id.]]</strong><select  name="unit_id" style="width:83px;  height: 24px;"  id="unit_id" onchange="change_unit(this.value);"><option value=""></option>
        					[[|unit_id_options|]]
        </select>
        <br />
        </td>
    </tr>
	<tr valign="top">
    
		<td>
		<fieldset>
            <legend class="title">[[.products.]]</legend>
             <span id="mi_product_all_elems">
                <span>
                    <span class="multi-input-header"><span style="width:45px;">[[.code.]]</span></span>
                    <!--LIST:languages-->
                    <span class="multi-input-header"><span style="width:150px;">[[.name.]]([[|languages.code|]]))</span></span>
                    <!--/LIST:languages-->
                    <span class="multi-input-header"><span style="width:150px;">[[.category_id.]]</span></span>
                    <span class="multi-input-header"><span style="width:100px;">[[.type.]]</span></span>
                    <span class="multi-input-header"><span style="width:60px;">[[.unit_id.]]</span></span>
                    <span class="multi-input-header"><span style="width:200px;">[[.image.]]</span></span>                    
                    <span class="multi-input-header"><span style="width:20px;">&nbsp;</span></span>
                    <br style="clear:both;">
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
    
    function change_type(valuetyepe)
    {
        jQuery('.type_product').val(valuetyepe);
    }
    function change_cate(valuecategory)
    {
        jQuery('.cate_product').val(valuecategory);
    }
    function change_unit(valueunit)
    {
        jQuery('.unit_product').val(valueunit);
    } 
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

jQuery(document).ready(function(){    
		jQuery('#image_name_101').hover
        (
            function(){
            }
            ,
			function()
			{
				jQuery('img.tooltip').remove();	
			}
        
        );
		jQuery(document).mousemove(function(e){
			var x = e.pageX;
			var y = e.pageY;
			jQuery(this).find('img.tooltip').css('top',y+20);
			jQuery(this).find('img.tooltip').css('left',x+20);
			
		}); 
    
});

function show_img(index)
{
    var link_img = jQuery("#image_url_"+index).val();
    if(link_img != '')
    {
    	var img = document.createElement('img');
    	jQuery(img).css('position','absolute');
    	jQuery(img).css('width','120px');
    	jQuery(img).css('height','120px');
    	jQuery(img).css('z-index','9999');
    	jQuery(img).attr('src',link_img);
    	jQuery(img).addClass('tooltip');
    	jQuery("body").append(img);
    	jQuery(img).fadeIn();
        
    }
}

</script>
