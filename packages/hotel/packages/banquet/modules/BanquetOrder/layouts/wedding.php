<script type="text/javascript">
	banquet_room_arr = <?php echo String::array2js([[=banquet_rooms=]])?>;
</script>
<link href="skins/default/hotel.css" rel="stylesheet" type="text/css" />
<style>
.input{
	height:22px;
	line-height:22px;
}
</style>
<span style="display:none">
	<span id="mi_product_sample">
	    <span id="input_group_#xxxx#">    
			<input name="mi_product[#xxxx#][id]" type="hidden" id="id_#xxxx#" size="1"/>
            <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td style="width: 100px;">
                       	<input name="mi_product[#xxxx#][price_id]" type="hidden" id="price_id_#xxxx#" />
                        <input name="mi_product[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" style="width:96px;" class="bar-product-id" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/>
                    </td>
                    <td style="width: 180px;"><input style="width:172px;" name="mi_product[#xxxx#][product_name]" type="text" id="product_name_#xxxx#" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
					<td style="width: 60px;">
                        <input  style="width:72px;" name="mi_product[#xxxx#][unit]" type="text" id="unit_#xxxx#"  readonly="readonly" class="readonly_input" tabindex="-1"/>
                    </td>
                    <td style="width:70px;"><input name="mi_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:66px;" class="input_number format_number" maxlength="10" onkeyup="update_total($('quantity_#xxxx#').value,#xxxx#);calculate();" /></td>
                    <td style="width: 120px;"><input name="mi_used_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:116px;" class="input_number format_number" maxlength="10" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_product[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:97px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td style="width: 155px;" align="right"><input name="mi_product[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:147px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="[[.delete.]]" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','product_');event.returnValue=false;calculate();" tabindex="-1"></td>
                </tr>
            </table>
		</span>
	</span>
</span>
<span style="display:none">
	<span id="mi_eating_product_sample">
	    <span id="input_group_#xxxx#">    
			<input name="mi_eating_product[#xxxx#][id]" type="hidden" id="id_#xxxx#" size="1"/>
            <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td width="100">
                       	<input name="mi_eating_product[#xxxx#][price_id]" type="hidden" id="price_id_#xxxx#" />
                        <input name="mi_eating_product[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" class="bar-product-id" style="width:90px;" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/>
                    </td>
                    <td width="180"><input name="mi_eating_product[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
					<td width="60">
                        <input name="mi_eating_product[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:72px;" readonly="readonly" class="readonly_input" tabindex="-1"/>
                    </td>
                    <td width="70"><input name="mi_eating_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:60px;" class="input_number format_number" maxlength="10" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_eating_product[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:186px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td width="190" align="right"><input name="mi_eating_product[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="[[.delete.]]" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_eating_product','#xxxx#','eating_product_');event.returnValue=false;calculate();" tabindex="-1"></td>
                </tr>
            </table>
		</span>
	</span>
</span>
<span style="display:none">
	<span id="mi_vegetarian_sample">
	    <span id="input_group_#xxxx#">    
			<input name="mi_vegetarian[#xxxx#][id]" type="hidden" id="id_#xxxx#" size="1"/>
            <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td width="100">
                       	<input name="mi_vegetarian[#xxxx#][price_id]" type="hidden" id="price_id_#xxxx#" />
                        <input name="mi_vegetarian[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" class="bar-product-id" style="width:90px;" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/>
                    </td>
                    <td width="180"><input name="mi_vegetarian[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
					<td width="60">
                        <input name="mi_vegetarian[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:72px;" readonly="readonly" class="readonly_input" tabindex="-1"/>
                    </td>
                    <td width="70"><input name="mi_vegetarian[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:60px;" class="input_number format_number" maxlength="10" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_vegetarian[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:186px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td width="190" align="right"><input name="mi_vegetarian[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="[[.delete.]]" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_vegetarian','#xxxx#','eating_product_');event.returnValue=false;calculate();" tabindex="-1"></td>
                </tr>
            </table>
		</span>
	</span>
</span>
<span style="display:none">
	<span id="mi_service_sample">
	    <span id="input_group_#xxxx#">    
			<input name="mi_service[#xxxx#][id]" type="hidden" id="id_#xxxx#" size="1"/>
            <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td width="100">
                       	<input name="mi_service[#xxxx#][price_id]" type="hidden" id="price_id_#xxxx#" />
                        <input name="mi_service[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" class="bar-product-id" style="width:90px;" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/>
                    </td>
                    <td width="180"><input name="mi_service[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
					<td width="60">
                        <input name="mi_service[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:72px;" readonly="readonly" class="readonly_input" tabindex="-1"/>
                    </td>
                    <td width="70"><input name="mi_service[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:60px;" class="input_number format_number" maxlength="10" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_service[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:186px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td width="190" align="right"><input name="mi_service[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="[[.delete.]]" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_service','#xxxx#','service_');event.returnValue=false;calculate();" tabindex="-1"></td>
                </tr>
            </table>
		</span>
	</span>
</span>
<span style="display:none">
	<span id="mi_banquet_room_sample">
	    <span id="input_group_#xxxx#">    
			<input name="mi_banquet_room[#xxxx#][id]" type="hidden" id="id_#xxxx#" size="1"/>
            <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td width="180px">
                        <select  name="mi_banquet_room[#xxxx#][banquet_room_id]" id="banquet_room_id_#xxxx#" style="width: 180px;" onchange="update_banquet_room(this.value,#xxxx#);calculate();">[[|banquet_room_options|]]</select>
                    </td>
                    <td width="180px">
                        <input name="mi_banquet_room[#xxxx#][group_name]" type="text" id="group_name_#xxxx#" readonly="readonly" class="readonly_input" tabindex="-1"/>
                    </td>
					<td width="100px" class="room_price"  <?php echo Url::get('party_category')=='FULL_PRICE'?'style="display:none;"':'';?> align="right">
                        <input name="mi_banquet_room[#xxxx#][total]" type="text" id="total_#xxxx#" class="input_number format_number" style="width:104px;text-align:right;" tabindex="-1" onkeyup="calculate(1);" group="ROOM"/>
                    </td>
                    <td width="250px">
                        <input name="mi_banquet_room[#xxxx#][address]" type="text" style="width:246px;" id="address_#xxxx#"tabindex="-1"/>
                    </td>
                    <td width="150px">
                        <input name="mi_banquet_room[#xxxx#][note]" type="text" style="width:146px;" id="note_#xxxx#"tabindex="-1"/>
                    </td>
                    <!--IF:cond(User::can_delete(false,ANY_CATEGORY))-->
                    <td><input name="delete_item_product" type="button" value="[[.delete.]]" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_banquet_room','#xxxx#','banquet_room_');event.returnValue=false;calculate();" tabindex="-1"></td>
                    <!--/IF:cond-->
                </tr>
            </table>
		</span>
	</span>
</span>
<div style="text-align:center;">
<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">


<table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr><td width="100%">
		<form name="AddBanquetOrderForm" method="post" enctype="multipart/form-data">
        <input  name="product_deleted_ids" id="product_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
        <input  name="eating_product_deleted_ids" id="eating_product_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
        <input  name="service_deleted_ids" id="service_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
        <input  name="banquet_room_deleted_ids" id="banquet_room_deleted_ids" type="hidden"/>

        <!--Thông tin ngày tháng, tiêu đề, trạng thái-->
		<table cellspacing="0" cellpadding="5" border="0" width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
		<tr height="25" bgcolor="#FFFFFF">
			<td align="left" bgcolor="#AAD5FF">
			<table width="100%" border="0">
			<tr>
				<td width="25%">
                    [[.create_date.]]: [[|date|]]
			    </td>
				<td width="50%" align="center" style="padding:0px" nowrap="nowrap">
                    <font style="font-size:20px; text-transform:uppercase;">[[.wedding_party.]]</font>
                </td>
				<td width="25%" align="right" nowrap>
                    [[.currency.]]: <?php echo HOTEL_CURRENCY;?>
					<input  type="hidden" name="curr" value="<?php echo HOTEL_CURRENCY;?>"/>
				</td>
			</tr>
			</table>
            </td>
		</tr>
		<tr bgcolor="#F4F4F4">
    		<td bgcolor="#FFFFFF">
        		<div><?php echo Form::$current->error_messages();?></div>
                <div align="left">
                    <table cellpadding="3" cellspacing="0" width="100%">
                        <tr>            
                            <td>
                            	[[.time_type.]]: <select name="time_type" id="time_type" onchange="calculate();"></select>
                            </td>
                            <td align="right">[[.status.]] <em style="color:red;">(*)</em>: <select name="status" id="status" onchange="handle_time();"></select></td>
                        </tr>
                    </table>
                </div>
    		</td>
		</tr>
		</table>
        <!--//Thông tin ngày tháng, tiêu đề, trạng thái-->
        
        <!--Thông tin khách đặt-->
        <fieldset>
            <legend class="title">[[.guest_information.]]</legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div id="guest_information">
                <table width="100%" style="text-align:left;" cellpadding="5" border="0" bordercolor="#CCCCCC">
                    <tr>
                        <td>Số hợp đồng</td>
                        <td><input name="contract_code" type="text" id="contract_code" class="input"/></td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>[[.agent_name.]] <em style="color:red;">(*)</em></td>
                        <td colspan="3">[[.agent_address.]]</td>
                        <td rowspan="8" align="left" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC;">
                                <tr bgcolor="#EBE6A6" height="20px">
                                    <th align="left"><img src="skins/default/images/b-chi.gif"/>&nbsp;[[.note.]]</th>
                                </tr>
                                <tr>
                                    <td align="center"><textarea name="note" id="note" rows="11" style="width:100%;border:1px solid #FFFFFF;font-style:italic;font-size:11px;"></textarea></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        
                        <td><input name="full_name" type="text" id="full_name" class="input"/></td>
                        <td colspan="3"><input name="address" type="text" id="address" style="width:100%;" class="input"/></td>
                    </tr>
                    <tr>
                        <td>[[.cmt.]]</td>
                        <td>[[.agent_phone.]]</td>
                        <td colspan="3">[[.email.]]</td>
                    </tr>
                    <tr>
                        <td><input name="identity_number" type="text" id="identity_number" class="input"/></td>
                        <td><input name="home_phone" type="text" id="home_phone" class="input"/></td>
                        <td colspan="2"><input name="email" type="text" id="email" style="width:100%;" class="input"/></td>
                    </tr>                  
                    <!--if WEDDING-->
                    <tr class="wedding">
                        <td>[[.groom_name.]]</td>
                        <td>[[.bride_name.]]</td>
                        <td>[[.type_banquet.]]</td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr class="wedding">
                        <td><input name="groom_name" type="text" id="groom_name"/></td>
                        <td><input name="bride_name" type="text" id="bride_name"/></td>
                        <td><input name="type_banquet" type="text" id="type_banquet"/></td>
                        <td colspan="1">&nbsp;</td>
                    </tr> 
                    <tr class="wedding">
                        <td>[[.representative_name.]]</td>
                        <td>[[.agent_phone.]]</td>
                        <td colspan="2">[[.address.]]</td>
                    </tr>
                    <tr class="wedding">
                        <td><input name="representative_name" type="text" id="representative_name"/></td>
                        <td><input name="representative_phone" type="text" id="representative_phone"/></td>
                        <td colspan="2"><input style="width: 100%;" name="representative_address" type="text" id="representative_address"/></td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <!--//Thông tin khách đặt-->
         <fieldset>
            <legend class="title">thông tin khách sạn</legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
                <table>
                    <tr>
                            <td align="right">Nguời đại diện Ông/bà :</td>
                            <td><input name="representative_hotel" id="representative_hotel" type="text" class="input" value="[[|representative_hotel|]]" /></td>
                            <td align="right">Chức vụ</td>
                            <td><input name="position_hotel" id="position_hotel" class="input" value="[[|position_hotel|]]" /></td>
                        </tr>
                </table>
            </div>   
        </fieldset>
        <!--Thông tin đặt phòng tiệc-->
        <fieldset style="text-align:left;" class="banquet_room">
            <legend class="title">[[.banquet_info.]]</legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
            <table width="100%">
                <tr>
                    
                    <td>[[.date.]] [[.banquet.]] <em style="color:red;">(*)</em></td>
                    <td>[[.start_time.]] <em style="color:red;">(*)</em></td>
                    <td>[[.end_time.]] <em style="color:red;">(*)</em></td>
                </tr>
                <tr>
                    <td><input name="checkin_date" type="text" id="checkin_date" value="[[|checkin_date|]]" style="text-align: right; width: 70px;"/></td>
                    <td><input name="checkin_hour" type="text" id="checkin_hour"  style="text-align: right; width: 60px;"/></td>
                    <td><input name="checkout_hour" type="text" id="checkout_hour" style="text-align: right; width: 60px;"/></td>
                </tr>
                <tr>
                    <td>[[.banquet_num_people.]] </td>
                    <td>[[.num_table.]] </td>
                    <td>[[.table_reserve.]] </td>
                </tr>
                <tr>
                    <td><input name="num_people" type="text" id="num_people" style="text-align: right; width: 70px;" onkeyup="calculate();" maxlength="4"/></td>
                    <td><input name="num_table" type="text" id="num_table" style="text-align: right; width: 60px;"/></td>
                    <td><input name="table_reserve" type="text" id="table_reserve" style="text-align: right; width: 60px;"/></td>
                </tr>
            </table>
            <br />
            <br />
            <span id="mi_banquet_room_all_elems" style="padding:0px;">
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="180px">[[.room_name.]]</td>
                        <td width="180px">[[.group_name.]]</td>
                        <td width="108px" class="room_price" <?php echo Url::get('party_category')=='FULL_PRICE'?'style="display:none;"':'';?>>[[.price.]]</td>
                        <td width="250px">[[.banquet_room_address.]]</td>
                        <td width="150px">[[.banquet_room_note.]]</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </span>
            <input type="button" value="[[.add_room.]]" onclick="mi_add_new_row('mi_banquet_room');"/>
            </div> 
        </fieldset>
        <!--//Thông tin đặt phòng tiệc-->
        
        <!--Thông tin menu-->
        <fieldset style="text-align:left;" class="banquet_menu">
            <legend class="title">[[.banquet_menu.]]</legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
            <span id="mi_eating_product_all_elems" style="padding:0px;">
            <!--Phục vụ đồ ăn--> 
            <p><b>[[.eating_menu.]]</b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="100"> [[.product_code.]] </td>
                        <td width="180">[[.product_name.]]</td>
                        <td width="80">[[.product_unit.]]</td>
                        <td width="70" nowrap="nowrap">[[.product_quantity.]]</td>
                        <td width="190" align="right" nowrap="nowrap">[[.price.]]</td>
                        <td width="190" align="right" nowrap="nowrap">[[.total.]]</td>
                        <td></td>
                    </tr>
                </table>
                </span>
                <input type="button" value="   [[.add_product.]]   " onclick="mi_add_new_row('mi_eating_product');MyAutocomplete(input_count,'PRODUCT');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
                <!--//Phục vụ đồ ăn--> 
                <br />
                <br />
                <!--Phục vụ đồ chay--> 
                <span id="mi_vegetarian_all_elems" style="padding:0px;">
            <p><b>[[.vegetarian.]]</b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="100"> [[.product_code.]] </td>
                        <td width="180">[[.product_name.]]</td>
                        <td width="80">[[.product_unit.]]</td>
                        <td width="70" nowrap="nowrap">[[.product_quantity.]]</td>
                        <td width="190" align="right" nowrap="nowrap">[[.price.]]</td>
                        <td width="190" align="right" nowrap="nowrap">[[.total.]]</td>
                        <td></td>
                    </tr>
                </table>
                </span>
                <input type="button" value="   [[.add_product.]]   " onclick="mi_add_new_row('mi_vegetarian');MyAutocomplete(input_count,'VEGETARIAN');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
                <!--//Phục vụ đồ chay--> 
                
                <br />
                <br />
                <!--Phục vụ đồ uống-->  
            <span id="mi_product_all_elems" style="padding:0px;">             
                <p><b>[[.drinking_menu.]]</b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td style="width: 100px;"> [[.product_code.]] </td>
                        <td style="width: 180px;">[[.product_name.]]</td>
                        <td style="width: 80px;">[[.product_unit.]]</td>
                        <td style="width: 70px;" nowrap="nowrap">[[.product_quantity.]]</td>
                        <td style="width: 120px;" nowrap="nowrap" >[[.product_quantity_used.]]</td>
                        <td style="width: 105px;" align="right" nowrap="nowrap">[[.price.]]</td>
                        <td style="width: 155px;" align="right" nowrap="nowrap">[[.total.]]</td>
                        <td></td>
                    </tr>
                </table>
                <!--//Phục vụ đồ uống--> 
            </span>
            <input type="button" value="   [[.add_product.]]   " onclick="mi_add_new_row('mi_product');MyAutocomplete(input_count,'DRINK');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
            <br />
            <br />
            <!--Dịch vụ-->
             <span id="mi_service_all_elems" style="padding:0px;">
            <p><b>[[.service_menu.]]</b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="100"> [[.product_code.]] </td>
                        <td width="180">[[.product_name.]]</td>
                        <td width="80">[[.product_unit.]]</td>
                        <td width="70" nowrap="nowrap">[[.product_quantity.]]</td>
                        <td width="190" align="right" nowrap="nowrap">[[.price.]]</td>
                        <td width="190" align="right" nowrap="nowrap">[[.total.]]</td>
                        <td></td>
                    </tr>
                </table>
                </span>
                <input type="button" value="[[.add_service.]]" onclick="mi_add_new_row('mi_service');MyAutocomplete(input_count,'SERVICE');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
            <!--//Dịch vụ-->
            </div>
        </fieldset>
        <!--//Thông tin menu-->
         <!--Thông tin khuyến mại-->
        <fieldset style="text-align:left;" class="banquet_room">
            <legend class="title">[[.banquet_promotions_info.]]</legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
				<table  border="1" cellpadding="2" bordercolor="#999999">
                    <tr>
                        <th></th>
                        <th style="width:350px;">[[.promotions_name.]]</th>
                        <th style="width:200px;">[[.note.]]</th>
                    </tr>
                    <!--LIST:promotions-->
                        <tr>
                            <td><input name="check_list[]" type="checkbox" id="chkBox_[[|promotions.id|]]" value = "[[|promotions.id|]]" 
                                    <?php 
                                            if(isset($_REQUEST['promotions']))
                                            {
                                                $promotions_arr = explode(' ',$_REQUEST['promotions']); 
                                                foreach($promotions_arr as $key=>$value)
                                                {
                                                    if ([[=promotions.id=]]==$value)
                                                        echo 'checked="checked"';    
                                                }
                                            }
                                        ?>/></td>
                            <td style="width:350px;"><label for="chkBox_[[|promotions.id|]]">[[|promotions.name|]]</label></td>
                            <td style="width:200px;">[[|promotions.note|]]</td>
                        </tr>
                    <!--/LIST:promotions-->
                </table>
			</div>
        </fieldset>
        <!--//Thông tin khuyến mại-->
        
        <fieldset style="text-align:left;">
        <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
        <div>
        <table width="100%" border="0" cellpadding="2">
            <tr>
                <td width="8%">&nbsp;</td>
                <td width="8%">&nbsp;</td>
                <td width="64%" align="right">&nbsp;</td>
                <td width="20%" align="right" nowrap="nowrap">[[.summary.]]</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right"></td>
                <td align="right" nowrap="nowrap"><input name="summary" type="text" id="summary" readonly="readonly" style="text-align:right;" class="readonly_class" /></td>
            </tr>
            <tr>
                <td width="8%">&nbsp;</td>
                <td width="8%">&nbsp;</td>
                <td width="64%" align="right">&nbsp;</td>
                <td width="20%" align="right" nowrap="nowrap">[[.service_rate.]]</td>
            </tr>                          
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td align="right" nowrap="nowrap"><select name="service_rate" id="service_rate" onchange="calculate();"></select> <input name="service_total" type="text" id="service_total"  readonly="readonly" style="text-align:right;" class="readonly_class"   /></td>
            </tr>
            <tr>
                <td width="8%">&nbsp;</td>
                <td width="8%">&nbsp;</td>
                <td width="64%" align="right">&nbsp;</td>
                <td width="20%" align="right" nowrap="nowrap">[[.tax.]]</td>
            </tr>                          
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
                <td align="right" nowrap="nowrap"><select name="vat" id="vat" onchange="calculate();"></select> <input name="tax_total" type="text" id="tax_total"  readonly="readonly" style="text-align:right;" class="readonly_class"   /></td>
            </tr>
              <tr>                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;">[[.deposit_1.]]</td>
                <td nowrap="nowrap">[[.cashier.]] 1</td>
                <td align="right" nowrap="nowrap">[[.deposit_1_date.]]</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input name="deposit_1" type="text" id="deposit_1" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier1();"/></td>
                <td nowrap="nowrap"><input name="cashier_1" type="text" id="cashier_1"  /></td>
                <td align="right" nowrap="nowrap"><input name="deposit_1_date" type="text" id="deposit_1_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/></td>
            </tr>
            <tr>                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;">[[.deposit_2.]]</td>
                <td nowrap="nowrap">[[.cashier.]] 2</td>
                <td align="right" nowrap="nowrap">[[.deposit_2_date.]]</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input name="deposit_2" type="text" id="deposit_2" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier2();"/></td>
                <td nowrap="nowrap"><input name="cashier_2" type="text" id="cashier_2"  /></td>
                <td align="right" nowrap="nowrap"><input name="deposit_2_date" type="text" id="deposit_2_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/></td>
            </tr>
            <tr>                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;">[[.deposit_3.]]</td>
                <td nowrap="nowrap">[[.cashier.]] 3</td>
                <td align="right" nowrap="nowrap">[[.deposit_3_date.]]</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input name="deposit_3" type="text" id="deposit_3" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier3();"/></td>
                <td nowrap="nowrap"><input name="cashier_3" type="text" id="cashier_3"  /></td>
                <td align="right" nowrap="nowrap"><input name="deposit_3_date" type="text" id="deposit_3_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/></td>
            </tr>
            <tr>                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;">[[.deposit_4.]]</td>
                <td nowrap="nowrap">[[.cashier.]] 4</td>
                <td align="right" nowrap="nowrap">[[.deposit_4_date.]]</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input name="deposit_4" type="text" id="deposit_4" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier4();"/></td>
                <td nowrap="nowrap"><input name="cashier_4" type="text" id="cashier_4"  /></td>
                <td align="right" nowrap="nowrap"><input name="deposit_4_date" type="text" id="deposit_4_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/></td>
            </tr>
            <tr>
                <td>[[.payment_info.]]</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap">&nbsp;</td>
                <td align="right" nowrap="nowrap">[[.sum_total.]]</td>
            </tr>
            <tr>
                <td><input name="payment_info" type="text" id="payment_info" size="30"/></td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"></td>
                <td align="right" nowrap="nowrap"><input name="sum_total" type="text" id="sum_total" style="text-align:right" readonly="readonly" class="readonly_class" tabindex="-1"/></td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="4" bgcolor="#EEEEEE" align="center">
                    <input type="submit" name="save" value="    [[.save.]]    "  onclick="refreshPage()"/>
                    
                    <input type="submit" name="update" value="[[.Save_and_stay.]]" />
                    <input type="button" value="  [[.back.]]    " onclick="history.go(-1);" tabindex="-1"/>
                    <input type="button" value="[[.view_contact.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'view_contact','id'=>Url::iget('id'))); ?>'"/>
                </td>
			</tr>
            
        </table>
        </div>
        </fieldset>
        
        </form>
	</td></tr>
</table>
</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){   
		jQuery('#price_per_pepole').FormatNumber();
		jQuery('#checkin_date').datepicker();
		jQuery('#checkin_hour').mask("99:99");
		jQuery('#checkout_hour').mask("99:99");
		jQuery('#full_name').focus();
        //jQuery('.banquet_room').css('display','block');
        //jQuery('.banquet_menu').css('display','none');
		jQuery('.img_close').click(function(){
			jQuery(this).parent().next('div').slideToggle(500,function(){
				if(jQuery(this).prev('div').children('.img_close').attr('src')=='packages/core/skins/default/images/buttons/node_close.gif')
				{
					jQuery(this).prev('div').children('.img_close').attr('src','packages/core/skins/default/images/buttons/node_open.gif')
				}
				else
				{
					jQuery(this).prev('div').children('.img_close').attr('src','packages/core/skins/default/images/buttons/node_close.gif')
				}
			});
			
		});
	})	
	jQuery("#arrival_date").datepicker();
	jQuery("#deposit_1_date").datepicker();
    jQuery("#deposit_2_date").datepicker();
    jQuery("#deposit_3_date").datepicker();
    jQuery("#deposit_4_date").datepicker();
	function MyAutocomplete(id,product_type)
	{
		jQuery("#product_id_"+id).autocomplete({
                url: 'get_product2.php?banquet=1&product_type='+product_type,
				selectFirst:false,
			 	onItemSelect: function(item) {
					update_product(item.value,id);
                    calculate();
        		}
        });
	}
	function autocomplete_vip(id)
	{
		jQuery("#"+id).autocomplete({
                url: 'get_vipcard.php',
				selectFirst:false
        });
	}
	autocomplete_vip('vip_card');	
    
mi_init_rows('mi_product',<?php echo isset($_REQUEST['mi_product'])?String::array2js($_REQUEST['mi_product']):'{}';?>);
mi_init_rows('mi_vegetarian',<?php echo isset($_REQUEST['mi_vegetarian'])?String::array2js($_REQUEST['mi_vegetarian']):'{}';?>);
mi_init_rows('mi_service',<?php echo isset($_REQUEST['mi_service'])?String::array2js($_REQUEST['mi_service']):'{}';?>);
mi_init_rows('mi_eating_product',<?php echo isset($_REQUEST['mi_eating_product'])?String::array2js($_REQUEST['mi_eating_product']):'{}';?>);
mi_init_rows('mi_banquet_room', <?php if(isset($_REQUEST['mi_banquet_room'])){ echo String::array2js($_REQUEST['mi_banquet_room']); } else { echo '{}'; } ?>);
calculate();	
MyAutocomplete(input_count);
function updateBar(){
	jQuery('#acction').val(1);
	AddBanquetOrderForm.submit();
	//jQuery('#acction').val(0);
}
function UpdateUnit(obj,units){
	units.value = obj.value;
}
function UpdatePriceOutSide(obj,product,quantity,total){
	var deposit
	if(product.value == 'OUTSIDE'){
		if(is_numeric(quantity.value) && is_numeric(obj.value)){
			total.value =to_numeric(to_numeric(quantity.value) * to_numeric(obj.value));
			//jQuery('#summary').val(to_numeric(to_numeric(jQuery('#summary').val())+to_numeric(total.value)));
			//jQuery('#sum_total').val(to_numeric(to_numeric(jQuery('#sum_total').val())+to_numeric(total.value) - to_numeric(jQuery('#deposit').value)));
			calculate();
		}else{
			alert('ChÆ°a Ä‘Ãºng Ä‘á»‹nh dáº¡ng sá»‘');	
			return false;
		}
	}
}

var checkin_date = jQuery("#checkin_date").val();
var checkin_hour = jQuery("#checkin_hour").val();
var checkout_hour = jQuery("#checkout_hour").val();
function handle_time()
{
    var status = jQuery("#status").val();
    if(status == "CHECKIN")
    {
        jQuery("#checkin_hour").val('<?php echo date('H:i'); ?>');
        jQuery("#checkin_date").val('<?php echo date('d/m/Y'); ?>');
        jQuery("#checkout_hour").val(checkout_hour);
    }
    else
        if(status == "CHECKOUT")
        {
            jQuery("#checkout_hour").val('<?php echo date('H:i'); ?>');
            jQuery("#checkin_date").val('<?php echo date('d/m/Y'); ?>');
            jQuery("#checkin_hour").val(checkin_hour);
        }
        else
        {
            jQuery("#checkin_date").val(checkin_date);
            jQuery("#checkin_hour").val(checkin_hour);
            jQuery("#checkout_hour").val(checkout_hour);
        }
}

</script>