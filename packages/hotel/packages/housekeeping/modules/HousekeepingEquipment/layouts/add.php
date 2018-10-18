<span style="display:none">
	<span id="mi_housekeeping_equipment_detail_sample">
		<div id="input_group_#xxxx#" style="display:block;">
			<span class="multi-input">
                <input name="mi_housekeeping_equipment_detail[#xxxx#][product_id]" onblur="myAutocomplete(#xxxx#); update_product(this.value,'#xxxx#');" onchange=" myAutocomplete(#xxxx#);update_product(this.value,'#xxxx#')" stt="#xxxx#" style="width:100px; height: 24px;" type="text" id="product_id_#xxxx#" autocomplete="OFF"/>
			</span>
            <span class="multi-input">
                <input  name="mi_housekeeping_equipment_detail[#xxxx#][name]" style="width:200px; height: 24px;background-color:#CCC;" type="text" id="name_#xxxx#" readonly="true" class="read-only" />
			</span>
            <span class="multi-input">
                <input  name="mi_housekeeping_equipment_detail[#xxxx#][unit_name]" style="width:100px; height: 24px;background-color:#CCC;" type="text" id="unit_name_#xxxx#" readonly="true" class="read-only" />
			</span>
            <span class="multi-input">
                <input  name="mi_housekeeping_equipment_detail[#xxxx#][price]" style="width:100px; height: 24px;background-color:#CCC;" type="text" id="price_#xxxx#" readonly="true" class="read-only" />
			</span>            
            <span class="multi-input">
                <input  name="mi_housekeeping_equipment_detail[#xxxx#][quantity]" style="width:80px; height: 24px; text-align:center" type="text" id="quantity_#xxxx#" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;"/>
			</span>
			<span class="multi-input">
                <span style="width:20px; text-align: center">
    				<a href="#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_housekeeping_equipment_detail','#xxxx#','');event.returnValue=false;" style="cursor:hand; text-align: center;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px; padding-top: 2px;"></i></a>
    			</span>
            </span>
            <br clear="all"/>
		</div>
	</span> 
</span>



<form name="AddHousekeepingEquipmentForm" method="post" >
<?php System::set_page_title(HOTEL_NAME);?>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td class="" width="60%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;;"></i> [[.add_new_equipment.]]</td>
		<td style="width: 40%; text-align: right; padding-right: 30px;">
			<input name="save" onclick="AddHousekeepingEquipmentForm.submit();" class="w3-btn w3-orange w3-text-white" value="[[.Save_and_close.]]" style="text-transform: uppercase; margin-right: 5px;"/>		
			<a href="<?php echo Url::build_current();?>" class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a>
		</td>
	</tr>
</table>
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>		<br />
<div id="main_div_class">
	<fieldset>
        <legend class="" style="text-transform: uppercase;">[[.select.]]</legend>
        <table>
                <tr>
                    <td><input name="select_type" type="radio" checked="checked" id="single" onclick="check_radio();" /></td>
                    <td><strong ><label for="single">[[.room_type_id.]] : </label></strong></td>
                    <td><select name="room_level_id" id="room_level_id" style="width:100px" ></select></td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td><input name="select_type" type="radio" id="double" onclick="check_radio();" /></td>
                    <td><strong><label for="double">[[.room_id.]] : </label></strong></td>
                    <td><select name="room_id" id="room_id" style="width:100px"></select></td>
                </tr>
        </table>
        <!--<strong>[[.room_type_id.]]: </strong><select name="room_level_id" id="room_level_id" style="width:100px" ></select>
        <strong>[[.room_id.]]: </strong><select name="room_id" id="room_id" style="width:100px"></select>-->
    </fieldset>
</div>

<br />

<fieldset>
    <legend class="" style="text-transform: uppercase;">[[.housekeeping_equipment_detail.]]</legend>
	<span id="mi_housekeeping_equipment_detail_all_elems">
		<span>
			<span class="multi-input-header" style="width:100px;">[[.code.]]</span>
			<span class="multi-input-header" style="width:200px;">[[.name.]]</span>
			<span class="multi-input-header" style="width:100px;">[[.unit_name.]]</span>
            <span class="multi-input-header" style="width:100px;">[[.price.]]</span>            
			<span class="multi-input-header" style="width:80px;">[[.quantity.]]</span>
			<span class="multi-input-header" style="width:20px;"><img src="skins/default/images/spacer.gif"/></span>
		</span>
		<br clear="all"/>
	</span>
	<input class="w3-btn w3-cyan w3-text-white" type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_housekeeping_equipment_detail');myAutocomplete(input_count);" style="margin-top: 5px; text-transform: uppercase;"/>
</fieldset> 

<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>

<input name="sselect" type="hidden" value="0"/>
</form>
<?php echo HousekeepingEquipment::create_js_variables();?>
<script>
    jQuery(document).ready(function(){
        <!--IF:add(URL::get('cmd')=='add')-->
            
            <!--IF:cond(URL::get('room_id'))-->
                jQuery("#double").attr('checked','checked');
            <!--/IF:cond-->
            
            check_radio();
        
        <!--/IF:add-->
    });
    mi_init_rows('mi_housekeeping_equipment_detail',<?php echo isset($_REQUEST['mi_housekeeping_equipment_detail'])?String::array2js($_REQUEST['mi_housekeeping_equipment_detail']):'{}';?>);
	var data = <?php echo String::array2suggest($GLOBALS['js_variables']['product']);?>;	
    
    function myAutocomplete(id)
	{
		jQuery("#product_id_"+id).autocomplete({
                url: 'get_product.php?equipment=1',
        onItemSelect: function(item) {
			update_product(jQuery("#product_id_"+id).val(),id);
		}
        });
	}
	<?php
		$data = Url::get('mi_housekeeping_equipment_detail');
		if($data and is_array($data))
		{
			foreach($data as $value)
			{
				echo 'update_product("'.$value['product_id'].'",101);';
			}
		}
	?>
	function update_product(code,index)
	{
		if (typeof(product[code])!='undefined')
		{
			$('name_'+index).value=product[code]['name'];
			$('unit_name_'+index).value=product[code]['unit_name'];
            $('price_'+index).value=product[code]['price'];
			$('quantity_'+index).value='';
		}else{
			$('name_'+index).value='';
			$('unit_name_'+index).value='';
            $('price_'+index).value='';
			$('quantity_'+index).value='';
		}
	}
	function update_quantity(code,index)
	{
		if (typeof(product[code])!='undefined')
		{
			quantity = product[code]['in_stock'];
			if(to_numeric($('quantity_'+index).value)>quantity)
			{
				alert('So luong ton kho hien tai la '+quantity+'. So luong hu hong phai nho hon so luong ton kho!')
			}
		}
	}
    function check_radio()
    {
        var id = jQuery(":checked").attr("id");
        if(id=='single')
        {
            jQuery("#room_id").attr('disabled',true);
            jQuery("#room_id").css('display','none');
            jQuery("#room_level_id").attr('disabled',false);
            jQuery("#room_level_id").css('display','block');
        }
        else
        {
            jQuery("#room_level_id").attr('disabled',true);
            jQuery("#room_level_id").css('display','none');
            jQuery("#room_id").attr('disabled',false);
            jQuery("#room_id").css('display','block');
        }
            
    }
</script>
<style>
	.input_style
	{
		width:100%;
		border:0;
		background-color:#EFEFEE;
		font-weight:bold;	
		border-bottom:1px dashed #333333;
	}
</style>