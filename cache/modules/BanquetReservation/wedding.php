<script type="text/javascript">
	banquet_room_arr = <?php echo String::array2js($this->map['banquet_rooms'])?>;
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
                    <td style="width: 180px;"><input style="width:172px;" name="mi_product[#xxxx#][name]" type="text" id="name_#xxxx#" class="readonly_input" tabindex="-1" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/></td>
					<td style="width: 60px;">
                        <input  style="width:80px;" name="mi_product[#xxxx#][unit]" type="text" id="unit_#xxxx#"  readonly="readonly"  class="readonly_input" tabindex="-1"/>
                    </td>
                    <td style="width:70px;"><input name="mi_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" value="1" style="text-align:center;width:66px;" class="input_number format_number" maxlength="10" onchange="checkquantity('quantity_#xxxx#'); update_total($('quantity_#xxxx#').value,#xxxx#);calculate();" onkeyup="update_total($('quantity_#xxxx#').value,#xxxx#);calculate();" /></td>
                    <td style="width: 120px;"><input name="mi_used_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:116px;" class="input_number format_number" maxlength="10" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_product[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:97px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td style="width: 155px;" align="right"><input name="mi_product[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:147px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="<?php echo Portal::language('delete');?>" class="delete_item_product_#xxxx#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','product_');event.returnValue=false;calculate();" tabindex="-1"></td>
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
                    <td width="180"><input name="mi_vegetarian[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;" class="readonly_input" tabindex="-1" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/></td>
					<td width="60">
                        <input name="mi_vegetarian[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:80px;"  readonly="readonly"  class="readonly_input" tabindex="-1"/>
                    </td>
                    <td width="70"><input name="mi_vegetarian[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" value="1" style="text-align:center;width:60px;" class="input_number format_number" maxlength="10" onchange="checkquantity('quantity_#xxxx#'); update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_vegetarian[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:190px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td width="190" align="right"><input name="mi_vegetarian[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="<?php echo Portal::language('delete');?>" class="delete_item_product_#xxxx#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_vegetarian','#xxxx#','eating_product_');event.returnValue=false;calculate();" tabindex="-1"></td>
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
                    <td width="180"><input name="mi_eating_product[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;"  class="readonly_input" tabindex="-1" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/></td>
					<td width="60">
                        <input name="mi_eating_product[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:80px;"  readonly="readonly"  class="readonly_input" tabindex="-1"/>
                    </td>
                    <td width="70"><input name="mi_eating_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" value="1" style="text-align:center;width:60px;" class="input_number format_number" maxlength="10" onchange="checkquantity('quantity_#xxxx#'); update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_eating_product[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:190px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td width="190" align="right"><input name="mi_eating_product[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="<?php echo Portal::language('delete');?>" class="delete_item_product_#xxxx#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_eating_product','#xxxx#','eating_product_');event.returnValue=false;calculate();" tabindex="-1"></td>
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
                    <td width="180"><input name="mi_service[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;"  class="readonly_input" tabindex="-1" autocomplete="off" onkeyup="update_total(this.value,#xxxx#);"/></td>
					<td width="60">
                        <input name="mi_service[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:80px;"  readonly="readonly"  class="readonly_input" tabindex="-1"/>
                    </td>
                    <td width="70"><input name="mi_service[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" value="1" style="text-align:center;width:60px;" class="input_number format_number" maxlength="10" onchange="checkquantity('quantity_#xxxx#'); update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" onkeyup="update_total($('product_id_#xxxx#').value,#xxxx#);calculate();" /></td>
					<td style="width: 105px;" align="right"><input name="mi_service[#xxxx#][price]" type="text" id="price_#xxxx#" 
                        onkeyup="update_total($('price_#xxxx#').value,#xxxx#);calculate();" class="input_number format_number"
                        style="text-align:right;width:190px;" tabindex="-1" onchange="UpdatePriceOutSide(this,$('product_id_#xxxx#'),$('quantity_#xxxx#'),$(total_#xxxx#));"/></td>
                    <td width="190" align="right"><input name="mi_service[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1" group="MENU"/></td>
                    <td><input name="delete_item_product" type="button" value="<?php echo Portal::language('delete');?>" class="delete_item_product_#xxxx#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_service','#xxxx#','service_');event.returnValue=false;calculate();" tabindex="-1"></td>
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
                        <select   name="mi_banquet_room[#xxxx#][banquet_room_id]" id="banquet_room_id_#xxxx#" style="width: 180px;" onchange="update_banquet_room(this.value,#xxxx#);calculate();"><?php echo $this->map['banquet_room_options'];?></select>
                    </td>
                    <td width="180px">
                        <input name="mi_banquet_room[#xxxx#][group_name]" type="text" id="group_name_#xxxx#" readonly="readonly" class="readonly_input" tabindex="-1"/>
                    </td>
					<td width="100px" class="room_price"  <?php echo Url::get('party_category')=='FULL_PRICE'?'style="display:none;"':'';?> align="right">
                        <input name="mi_banquet_room[#xxxx#][total]" type="text" id="total_#xxxx#" class="input_number format_number" style="width:108px;text-align:right;" tabindex="-1" onkeyup="calculate(1);" group="ROOM"/>
                    </td>
                    <td width="250px">
                        <input name="mi_banquet_room[#xxxx#][address]" type="text" style="width:246px;" id="address_#xxxx#"tabindex="-1"/>
                    </td>
                    <td width="150px">
                        <input name="mi_banquet_room[#xxxx#][note]" type="text" style="width:146px;" id="note_#xxxx#"tabindex="-1"/>
                    </td>
                    <?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
                    <td><input name="delete_item_product" type="button" value="<?php echo Portal::language('delete');?>" class="delete_item_product_#xxxx#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_banquet_room','#xxxx#','banquet_room_');event.returnValue=false;calculate();" tabindex="-1"></td>
                    
				<?php
				}
				?>
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
                    <?php echo Portal::language('create_date');?>: <?php echo $this->map['date'];?>
			    </td>
				<td width="50%" align="center" style="padding:0px" nowrap="nowrap">
                    <font style="font-size:20px; text-transform:uppercase;"><?php echo Portal::language('wedding_party');?></font>
                </td>
				<td width="25%" align="right" nowrap>
                    <?php echo Portal::language('currency');?>: <?php echo HOTEL_CURRENCY;?>
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
                            	<?php echo Portal::language('time_type');?>: <select  name="time_type" id="time_type" onchange="calculate();"><?php
					if(isset($this->map['time_type_list']))
					{
						foreach($this->map['time_type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('time_type',isset($this->map['time_type'])?$this->map['time_type']:''))
                    echo "<script>$('time_type').value = \"".addslashes(URL::get('time_type',isset($this->map['time_type'])?$this->map['time_type']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            <td align="right"><?php echo Portal::language('status');?> <em style="color:red;">(*)</em>: <select  name="status" id="status" onchange="handle_time();"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
                        </tr>
                        <tr>
                            <td>
                                <?php if(Url::get('action')=='edit' and $this->map['use_mice']==1){ ?>
				<table>
		                    <?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=0 AND $this->map['mice_reservation_id']!=''){ ?>
		                            <td align="right"></td>
		                            <td align="right" style="color: red; font-weight: bold;"><?php echo Portal::language('mice');?></td>
		                            <td align="left" style="padding-left:10px;">
		                                <a href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['mice_reservation_id'];?>" style="line-height: 22px; font-weight: bold; color: red;"><?php echo $this->map['mice_reservation_id'];?></a>
		                                <input value="<?php echo Portal::language('split');?> MICE" type="button" onclick="FunctionSplitMice('<?php echo $this->map['mice_reservation_id'];?>','BANQUET','<?php echo Url::get('id'); ?>');" style="font-weight: bold; padding: 3px 10px; margin-left: 20px;" />
		                            </td>
		                            <td></td>
		                        <?php }else{ ?>
		                            <td></td>
		                            <td align="right"><input value="<?php echo Portal::language('add_mice');?>" type="button" onclick="FunctionAddMice('BANQUET','<?php echo Url::get('id'); ?>');" style="font-weight: bold; padding: 3px 10px;" /></td>
		                            <td align="left" style="padding-left:10px;"><input value="<?php echo Portal::language('select');?> MICE" type="button" onclick="FunctionSelectMice('BANQUET','<?php echo Url::get('id'); ?>');" style="font-weight: bold; padding: 3px 10px;" /></td>
		                            <td></td>
		                        <?php } ?>
		                </table>
				<?php } ?>                            
			    </td>
                            <td align="right">
                            </td>
			    
                        </tr>
                    </table>
                </div>
    		</td>
		</tr>
		</table>
        <!--//Thông tin ngày tháng, tiêu đề, trạng thái-->
        
        <!--Thông tin khách đặt-->
        <fieldset>
            <legend class="title"><?php echo Portal::language('guest_information');?></legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div id="guest_information">
                <table width="100%" style="text-align:left;" cellpadding="5" border="0" bordercolor="#CCCCCC">
                    <tr>
                        <td>Số hợp đồng</td>
                        <td><input  name="contract_code" id="contract_code" class="input"/ type ="text" value="<?php echo String::html_normalize(URL::get('contract_code'));?>"></td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo Portal::language('agent_name');?> <em style="color:red;">(*)</em></td>
                        <td colspan="3"><?php echo Portal::language('agent_address');?></td>
                        <td rowspan="8" align="left" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC;">
                                <tr bgcolor="#EBE6A6" height="20px">
                                    <th align="left"><img src="skins/default/images/b-chi.gif"/>&nbsp;<?php echo Portal::language('note');?></th>
                                </tr>
                                <tr>
                                    <td align="center"><textarea  name="note" id="note" rows="11" style="width:100%;border:1px solid #FFFFFF;font-style:italic;font-size:11px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><input  name="full_name" id="full_name" class="input"/ type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                        <td colspan="3"><input  name="address" id="address" style="width:100%;" class="input"/ type ="text" value="<?php echo String::html_normalize(URL::get('address'));?>"></td>
                    </tr>
                    <tr>
                        <td><?php echo Portal::language('cmt');?></td>
                        <td><?php echo Portal::language('agent_phone');?></td>
                        <td colspan="3"><?php echo Portal::language('email');?></td>
                    </tr>
                    <tr>
                        <td><input  name="identity_number" id="identity_number" class="input"/ type ="text" value="<?php echo String::html_normalize(URL::get('identity_number'));?>"></td>
                        <td><input  name="home_phone" id="home_phone" class="input_number no-copy-paste"/ type ="text" value="<?php echo String::html_normalize(URL::get('home_phone'));?>"></td>
                        <td colspan="2"><input  name="email" id="email" style="width:100%;" class="input"/ type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
                    </tr>                  
                    <!--if WEDDING-->
                    <tr class="wedding">
                        <td><?php echo Portal::language('groom_name');?></td>
                        <td><?php echo Portal::language('bride_name');?></td>
                        <td><?php echo Portal::language('type_banquet');?></td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr class="wedding">
                        <td><input  name="groom_name" id="groom_name"/ type ="text" value="<?php echo String::html_normalize(URL::get('groom_name'));?>"></td>
                        <td><input  name="bride_name" id="bride_name"/ type ="text" value="<?php echo String::html_normalize(URL::get('bride_name'));?>"></td>
                        <td><input  name="type_banquet" id="type_banquet"/ type ="text" value="<?php echo String::html_normalize(URL::get('type_banquet'));?>"></td>
                        <td colspan="2">&nbsp;</td>
                    </tr> 
                    <tr class="wedding">
                        <td><?php echo Portal::language('representative_name');?></td>
                        <td><?php echo Portal::language('agent_phone');?></td>
                        <td colspan="2"><?php echo Portal::language('address');?></td>
                    </tr>
                    <tr class="wedding">
                        <td><input  name="representative_name" id="representative_name"/ type ="text" value="<?php echo String::html_normalize(URL::get('representative_name'));?>"></td>
                        <td><input  name="representative_phone" id="representative_phone" class="input_number no-copy-paste"/ type ="text" value="<?php echo String::html_normalize(URL::get('representative_phone'));?>"></td>
                        <td colspan="2"><input  name="representative_address" id="representative_address"/ type ="text" value="<?php echo String::html_normalize(URL::get('representative_address'));?>"></td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <!--//Thông tin khách đặt-->
        <!--thông tin khách sạn-->
        <fieldset>
            <legend class="title">thông tin khách sạn</legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
                <table>
                    <tr>
                            <td align="right">Nguời đại diện Ông/bà :</td>
                            <td><input name="representative_hotel" id="representative_hotel" type="text" class="input" value="<?php echo $this->map['representative_hotel'];?>" /></td>
                            <td align="right">Chức vụ</td>
                            <td><input name="position_hotel" id="position_hotel" class="input" value="<?php echo $this->map['position_hotel'];?>" /></td>
                        </tr>
                </table>
            </div>   
        </fieldset>
        <!--//thông tin khách sạn-->
        <!--Thông tin đặt phòng tiệc-->
        <fieldset style="text-align:left;" class="banquet_room">
            <legend class="title"><?php echo Portal::language('banquet_info');?></legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
            <table width="100%">
                
                <tr>
                    
                    <td><?php echo Portal::language('date');?> <?php echo Portal::language('banquet');?> <em style="color:red;">(*)</em></td>
                    <td><?php echo Portal::language('start_time');?> <em style="color:red;">(*)</em></td>
                    <td><?php echo Portal::language('end_time');?> <em style="color:red;">(*)</em></td>
                </tr>
                <tr>
                    <td><input  name="checkin_date" id="checkin_date"  style="text-align: right; width: 70px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('checkin_date'));?>"></td>
                    <td><input  name="checkin_hour" id="checkin_hour"  style="text-align: right; width: 60px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('checkin_hour'));?>"></td>
                    <td><input  name="checkout_hour" id="checkout_hour" style="text-align: right; width: 60px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('checkout_hour'));?>"></td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('banquet_num_people');?> </td>
                    <td><?php echo Portal::language('num_table');?> </td>
                    <td><?php echo Portal::language('table_reserve');?> </td>
                </tr>
                <tr>
                    <td><input  name="num_people" id="num_people" style="text-align: right; width: 70px;" onkeyup="calculate();" maxlength="4"/ type ="text" value="<?php echo String::html_normalize(URL::get('num_people'));?>"></td>
                    <td><input  name="num_table" id="num_table"  style="text-align: right; width: 60px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('num_table'));?>"></td>
                    <td><input  name="table_reserve" id="table_reserve" style="text-align: right; width: 60px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('table_reserve'));?>"></td>
                </tr>
            </table>
            <br />
            <br />
            <span id="mi_banquet_room_all_elems" style="padding:0px;">
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="180px"><?php echo Portal::language('room_name');?></td>
                        <td width="180px"><?php echo Portal::language('group_name');?></td>
                        <td width="108px" class="room_price" <?php echo Url::get('party_category')=='FULL_PRICE'?'style="display:none;"':'';?>><?php echo Portal::language('price');?></td>
                        <td width="250px"><?php echo Portal::language('banquet_room_address');?></td>
                        <td width="150px"><?php echo Portal::language('banquet_room_note');?></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </span>
            <input type="button" value="<?php echo Portal::language('add_room');?>" onclick="mi_add_new_row('mi_banquet_room');"/>
            </div> 
        </fieldset>
        <!--//Thông tin đặt phòng tiệc-->
        
        <!--Thông tin menu-->
        <fieldset style="text-align:left;" class="banquet_menu">
            <legend class="title"><?php echo Portal::language('banquet_menu');?></legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
            <span id="mi_eating_product_all_elems" style="padding:0px;">
            <!--Phục vụ đồ ăn--> 
            <p><b><?php echo Portal::language('eating_menu');?></b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="100"> <?php echo Portal::language('product_code');?> </td>
                        <td width="180"><?php echo Portal::language('product_name');?></td>
                        <td width="80"><?php echo Portal::language('product_unit');?></td>
                        <td width="70" nowrap="nowrap"><?php echo Portal::language('product_quantity');?></td>
                        <td width="190" align="right" nowrap="nowrap"><?php echo Portal::language('price');?></td>
                        <td width="190" align="right" nowrap="nowrap"><?php echo Portal::language('total');?></td>
                        <td></td>
                    </tr>
                </table>
                </span>
                <input type="button" value="   <?php echo Portal::language('add_product');?>   " onclick="mi_add_new_row('mi_eating_product');MyAutocomplete(input_count,'PRODUCT');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
                <!--//Phục vụ đồ ăn--> 
                <br />
                <br />
             <!--Phục vụ đồ chay--> 
                <span id="mi_vegetarian_all_elems" style="padding:0px;">
                <p><b><?php echo Portal::language('vegetarian');?></b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="100"> <?php echo Portal::language('product_code');?> </td>
                        <td width="180"><?php echo Portal::language('product_name');?></td>
                        <td width="80"><?php echo Portal::language('product_unit');?></td>
                        <td width="70" nowrap="nowrap"><?php echo Portal::language('product_quantity');?></td>
                        <td width="190" align="right" nowrap="nowrap"><?php echo Portal::language('price');?></td>
                        <td width="190" align="right" nowrap="nowrap"><?php echo Portal::language('total');?></td>
                        <td></td>
                    </tr>
                </table>
                </span>
                <input type="button" value="   <?php echo Portal::language('add_product');?>   " onclick="mi_add_new_row('mi_vegetarian');MyAutocomplete(input_count,'VEGETARIAN');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
                <!--//Phục vụ đồ chay--> 
                <br /> 
                
                <br />
                <br />
                <!--Phục vụ đồ uống-->  
            <span id="mi_product_all_elems" style="padding:0px;">             
                <p><b><?php echo Portal::language('drinking_menu');?></b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td style="width: 100px;"> <?php echo Portal::language('product_code');?> </td>
                        <td style="width: 180px;"><?php echo Portal::language('product_name');?></td>
                        <td style="width: 80px;"><?php echo Portal::language('product_unit');?></td>
                        <td style="width: 70px;" nowrap="nowrap"><?php echo Portal::language('product_quantity');?></td>
                        <!--<td style="width: 120px;" nowrap="nowrap" ><?php echo Portal::language('product_quantity_used');?></td> -->
                        <td style="width: 195px;" align="right" nowrap="nowrap"><?php echo Portal::language('price');?></td>
                        <td style="width: 155px;" align="right" nowrap="nowrap"><?php echo Portal::language('total');?></td>
                        <td></td>
                    </tr>
                </table>
                <!--//Phục vụ đồ uống--> 
            </span>
            <input type="button" value="   <?php echo Portal::language('add_product');?>   " onclick="mi_add_new_row('mi_product');MyAutocomplete(input_count,'DRINK');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
            <br />
            <br />
            <!--Dịch vụ-->
             <span id="mi_service_all_elems" style="padding:0px;">
            <p><b><?php echo Portal::language('service_menu');?></b></p>
                <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                    <tr bgcolor="#EFEFEF">
                        <td width="100"> <?php echo Portal::language('product_code');?> </td>
                        <td width="180"><?php echo Portal::language('product_name');?></td>
                        <td width="80"><?php echo Portal::language('product_unit');?></td>
                        <td width="70" nowrap="nowrap"><?php echo Portal::language('product_quantity');?></td>
                        <td width="190" align="right" nowrap="nowrap"><?php echo Portal::language('price');?></td>
                        <td width="190" align="right" nowrap="nowrap"><?php echo Portal::language('total');?></td>
                        <td></td>
                    </tr>
                </table>
                </span>
                <input type="button" value="<?php echo Portal::language('add_service');?>" onclick="mi_add_new_row('mi_service');MyAutocomplete(input_count,'SERVICE');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();"/>
            <!--//Dịch vụ-->
            </div>
        </fieldset>
        <!--//Thông tin menu-->
         <!--Thông tin khuyến mại-->
        <fieldset style="text-align:left;" class="banquet_room">
            <legend class="title"><?php echo Portal::language('banquet_promotions_info');?></legend>
            <div align="right"><img class="img_close" src="packages/core/skins/default/images/buttons/node_open.gif" width="15px" style="cursor:pointer" id="img_close" /></div>
            <div>
				<table  border="1" cellpadding="2" bordercolor="#999999">
                    <tr>
                        <th></th>
                        <th style="width:350px;"><?php echo Portal::language('promotions_name');?></th>
                        <th style="width:200px;"><?php echo Portal::language('note');?></th>
                    </tr>
                    <?php if(isset($this->map['promotions']) and is_array($this->map['promotions'])){ foreach($this->map['promotions'] as $key1=>&$item1){if($key1!='current'){$this->map['promotions']['current'] = &$item1;?>
                        <tr>
                            <td><input name="check_list[]" type="checkbox" id="chkBox_<?php echo $this->map['promotions']['current']['id'];?>" value = "<?php echo $this->map['promotions']['current']['id'];?>" 
                                    <?php 
                                            if(isset($_REQUEST['promotions']))
                                            {
                                                $promotions_arr = explode(' ',$_REQUEST['promotions']); 
                                                foreach($promotions_arr as $key=>$value)
                                                {
                                                    if ($this->map['promotions']['current']['id']==$value)
                                                        echo 'checked="checked"';    
                                                }
                                            }
                                        ?>/></td>
                            <td style="width:350px;"><label for="chkBox_<?php echo $this->map['promotions']['current']['id'];?>"><?php echo $this->map['promotions']['current']['name'];?></label></td>
                            <td style="width:200px;"><?php echo $this->map['promotions']['current']['note'];?></td>
                        </tr>
                    <?php }}unset($this->map['promotions']['current']);} ?>
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
                <td width="20%" align="right" nowrap="nowrap"><?php echo Portal::language('summary');?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right"></td>
                <td align="right" nowrap="nowrap"><input  name="summary" id="summary" readonly="readonly" style="text-align:right;" class="readonly_class" / type ="text" value="<?php echo String::html_normalize(URL::get('summary'));?>"></td>
            </tr>
            <tr>
                <td width="8%">&nbsp;</td>
                <td width="8%">&nbsp;</td>
                <td width="64%" align="right">&nbsp;</td>
                <td width="20%" align="right" nowrap="nowrap"><?php echo Portal::language('service_rate');?></td>
            </tr>                          
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td align="right" nowrap="nowrap"><select  name="service_rate" id="service_rate" onchange="calculate();"><?php
					if(isset($this->map['service_rate_list']))
					{
						foreach($this->map['service_rate_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('service_rate',isset($this->map['service_rate'])?$this->map['service_rate']:''))
                    echo "<script>$('service_rate').value = \"".addslashes(URL::get('service_rate',isset($this->map['service_rate'])?$this->map['service_rate']:''))."\";</script>";
                    ?>
	</select> <input  name="service_total" id="service_total"  readonly="readonly" style="text-align:right;" class="readonly_class"   / type ="text" value="<?php echo String::html_normalize(URL::get('service_total'));?>"></td>
            </tr>
            <tr>
                <td width="8%">&nbsp;</td>
                <td width="8%">&nbsp;</td>
                <td width="64%" align="right">&nbsp;</td>
                <td width="20%" align="right" nowrap="nowrap"><?php echo Portal::language('tax');?></td>
            </tr>                          
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
                <td align="right" nowrap="nowrap"><select  name="vat" id="vat" onchange="calculate();"><?php
					if(isset($this->map['vat_list']))
					{
						foreach($this->map['vat_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('vat',isset($this->map['vat'])?$this->map['vat']:''))
                    echo "<script>$('vat').value = \"".addslashes(URL::get('vat',isset($this->map['vat'])?$this->map['vat']:''))."\";</script>";
                    ?>
	</select> <input  name="tax_total" id="tax_total"  readonly="readonly" style="text-align:right;" class="readonly_class"   / type ="text" value="<?php echo String::html_normalize(URL::get('tax_total'));?>"></td>
            </tr>
              <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;"><?php echo Portal::language('deposit_1');?></td>
                <td nowrap="nowrap"><?php echo Portal::language('cashier');?> 1</td>
                <td align="right" nowrap="nowrap"><?php echo Portal::language('deposit_1_date');?></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input  name="deposit_1" id="deposit_1" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier1();"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_1'));?>"></td>
                <td nowrap="nowrap"><input  name="cashier_1" id="cashier_1"  / type ="text" value="<?php echo String::html_normalize(URL::get('cashier_1'));?>"></td>
                <td align="right" nowrap="nowrap"><input  name="deposit_1_date" id="deposit_1_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_1_date'));?>"></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;"><?php echo Portal::language('deposit_2');?></td>
                <td nowrap="nowrap"><?php echo Portal::language('cashier');?> 2</td>
                <td align="right" nowrap="nowrap"><?php echo Portal::language('deposit_2_date');?></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input  name="deposit_2" id="deposit_2" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier2();"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_2'));?>"></td>
                <td nowrap="nowrap"><input  name="cashier_2" id="cashier_2"  / type ="text" value="<?php echo String::html_normalize(URL::get('cashier_2'));?>"></td>
                <td align="right" nowrap="nowrap"><input  name="deposit_2_date" id="deposit_2_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_2_date'));?>"></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;"><?php echo Portal::language('deposit_3');?></td>
                <td nowrap="nowrap"><?php echo Portal::language('cashier');?> 3</td>
                <td align="right" nowrap="nowrap"><?php echo Portal::language('deposit_3_date');?></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input  name="deposit_3" id="deposit_3" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier3();"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_3'));?>"></td>
                <td nowrap="nowrap"><input  name="cashier_3" id="cashier_3"  / type ="text" value="<?php echo String::html_normalize(URL::get('cashier_3'));?>"></td>
                <td align="right" nowrap="nowrap"><input  name="deposit_3_date" id="deposit_3_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_3_date'));?>"></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">                        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap" style="padding-right: 30px;"><?php echo Portal::language('deposit_4');?></td>
                <td nowrap="nowrap"><?php echo Portal::language('cashier');?> 4</td>
                <td align="right" nowrap="nowrap"><?php echo Portal::language('deposit_4_date');?></td>
            </tr>
            <tr style="<?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=''){ echo "display: none;"; } ?>">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"><input  name="deposit_4" id="deposit_4" style="text-align:right" size="15" maxlength="11" class="input_number format_number" onkeyup="calculate();getCashier4();"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_4'));?>"></td>
                <td nowrap="nowrap"><input  name="cashier_4" id="cashier_4"  / type ="text" value="<?php echo String::html_normalize(URL::get('cashier_4'));?>"></td>
                <td align="right" nowrap="nowrap"><input  name="deposit_4_date" id="deposit_4_date" style="text-align:right" size="15" maxlength="11" class="deposit_date"/ type ="text" value="<?php echo String::html_normalize(URL::get('deposit_4_date'));?>"></td>
            </tr>
            <tr>
                <td><?php echo Portal::language('payment_info');?></td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap">&nbsp;</td>
                <td align="right" nowrap="nowrap"><?php echo Portal::language('sum_total');?></td>
            </tr>
            <tr>
                <td><input  name="payment_info" id="payment_info" size="30"/ type ="text" value="<?php echo String::html_normalize(URL::get('payment_info'));?>"></td>
                <td>&nbsp;</td>
                <td align="right" nowrap="nowrap"></td>
                <td align="right" nowrap="nowrap"><input  name="sum_total" id="sum_total" style="text-align:right" readonly="readonly" class="readonly_class" tabindex="-1"/ type ="text" value="<?php echo String::html_normalize(URL::get('sum_total'));?>"></td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="4" bgcolor="#EEEEEE" align="center">
		    <?php if($this->map['close_mice']==0){ ?>
                    <input type="submit" name="save" value="<?php echo Portal::language('save');?>" tabindex="-1" id="save" style="padding: 5px;" onclick="return check_value();"/>
		    <?php }?>	
                    <input type="button" value="<?php echo Portal::language('back');?>" onclick="history.go(-1);" tabindex="-1" style="padding: 5px;"/>
                </td>
			</tr>
            
        </table>
        </div>
        </fieldset>
        
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td></tr>
</table>
</div>
</div>
<div id="mice_loading" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; text-align: center; background: rgba(255,255,255,0.95); display: none;">
    <img src="packages/hotel/packages/mice/skins/img/loading.gif" style="margin: 100px auto; height: 100px; width: auto;" />
</div>
<div id="mice_light_box" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; text-align: center; background: rgba(0,0,0,0.8); display: none;">
    <div style="width: 720px; height: 450px; background: #FFFFFF; padding: 10px; margin: 50px auto; position: relative; box-shadow: 0px 0px 3px #171717;">
        <div onclick="jQuery('#mice_light_box').css('display','none');" style="width: 20px; height: 20px; border: 2px solid #000000; color: #171717; text-transform: uppercase; line-height: 20px; text-align: center; position: absolute; top: 10px; right: 10px; cursor: pointer;">X</div>
        <div style="width: 500px; height: 22px; color: #171717; text-transform: uppercase; line-height: 22px; position: absolute; text-align: left; top: 10px; left: 10px; cursor: pointer;">Light Box MICE</div>
        <div id="mice_light_box_content" style="width: 700px; height: 400px; overflow: auto; margin: 40px auto 0px; border: 1px solid #EEEEEE;">
            
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){  
	   var ctrlDown = false,
            ctrlKey = 17,
            cmdKey = 91,
            vKey = 86,
            cKey = 67;
    
        jQuery(document).keydown(function(e) {
            if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
        }).keyup(function(e) {
            if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = false;
        });
    
        jQuery(".no-copy-paste").keydown(function(e) {
            if (ctrlDown && (e.keyCode == vKey || e.keyCode == cKey)) return false;
        });
   //oanh add
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    //oanh add

		jQuery('#price_per_pepole').FormatNumber();
		jQuery('#checkin_date').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });  //oanh add
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
        <?php if(Url::get('action') == 'edit'){ ?>
            var bill_mice = <?php echo $this->map['count'];?>;
            for(var a=101; a<=input_count; a++)
            {
                if(bill_mice > 0)
                {
                    jQuery('#total_'+a).attr('readonly', true);
                    jQuery('#product_id_'+a).attr('readonly', true);
                    jQuery('#banquet_room_id_'+a).attr('readonly', true);
                    jQuery('#quantity_'+a).attr('readonly', true);
                    jQuery('#price_'+a).attr('readonly', true);
                    jQuery('.delete_item_product_'+a).css('display','none');
                }  
            }
        <?php }?> 
	})	
	jQuery("#arrival_date").datepicker();
	jQuery("#deposit_1_date").datepicker();
    jQuery("#deposit_2_date").datepicker();
    jQuery("#deposit_3_date").datepicker();
    jQuery("#deposit_4_date").datepicker();
	function MyAutocomplete(id,product_type)
	{
	    //console.log(product_type);
		jQuery("#product_id_"+id).autocomplete({
                url: 'get_product.php?banquet=1&product_type='+product_type,
				selectFirst:false,
			 	onItemSelect: function(item) {
			 	   //console.log(item);
					update_product(item.value,id);
                    calculate();
        		}
        });
        var name = 'name';
        jQuery("#name_"+id).autocomplete({
                url: 'get_product.php?banquet=1&product_type='+product_type+ '&name='+name,
				selectFirst:false,
			 	onItemSelect: function(item) {
					update_product_name(item.value,id);
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
mi_init_rows('mi_service',<?php echo isset($_REQUEST['mi_service'])?String::array2js($_REQUEST['mi_service']):'{}';?>);
mi_init_rows('mi_vegetarian',<?php echo isset($_REQUEST['mi_vegetarian'])?String::array2js($_REQUEST['mi_vegetarian']):'{}';?>);
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
	if(product.value == 'OUTSIDE')
    {
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
changestatus();
function changestatus()
{
    var status = jQuery("#status").val();
    var $option = '';
    if(status == "CHECKIN")
    {
        $option = '<option value="BOOKED"><?php echo Portal::language('booked');?></option> <option value="CHECKIN"><?php echo Portal::language('checkin');?></option> <option value="CHECKOUT"><?php echo Portal::language('checkout');?></option>';
        jQuery("#status").html($option);
        jQuery("#status").val(status);
    }
    else if(status == "CHECKOUT")
    {
        $option = '<option value="CHECKIN"><?php echo Portal::language('checkin');?></option> <option value="CHECKOUT"><?php echo Portal::language('checkout');?></option>';
        jQuery("#status").html($option);
        jQuery("#status").val(status);
    }
    else
    {
        $option = '<option value="BOOKED"><?php echo Portal::language('booked');?></option> <option value="CHECKIN"><?php echo Portal::language('checkin');?></option>';
        jQuery("#status").html($option);
        jQuery("#status").val(status);
    }
}
function handle_time()
{
    var status = jQuery("#status").val();
    var d = new Date();
    if(status == "CHECKIN")
    {
        jQuery("#checkin_date").val(convertstring(d.getDate(),2)+'/'+convertstring((d.getMonth()+1),2)+'/'+d.getFullYear());
        jQuery("#checkin_hour").val(convertstring(d.getHours(),2)+':'+convertstring(d.getMinutes(),2));
    }
    else if(status == "CHECKOUT")
    {
        jQuery("#checkout_hour").val(convertstring(d.getHours(),2)+':'+convertstring(d.getMinutes(),2));
    }
    else
    {
        //jQuery("#checkin_date").val(checkin_date);
        //jQuery("#checkin_hour").val(checkin_hour);
        //jQuery("#checkout_hour").val(checkout_hour);
    }
}
function convertstring($str,$num)
{
    $str = $str+'';
    $count = $str.length;
    if($count<$num)
    {
        for($i=1;$i<=($num-$count);$i++)
            $str = '0'+$str;
    }
    return $str;
}
function getCashier1()
{
    var a = document.getElementById('deposit_1');
    var a1 = document.getElementById('cashier_1');
    a1.value="<?php $user = Session::get('user_data'); echo $user['full_name'];?>"
}
function getCashier2()
{
    var b = document.getElementById('deposit_2');
    var b1 = document.getElementById('cashier_2');
    b1.value="<?php $user = Session::get('user_data'); echo $user['full_name'];?>"
}
function getCashier3()
{
    var c = document.getElementById('deposit_3');
    var c1 = document.getElementById('cashier_3');
    c1.value="<?php $user = Session::get('user_data'); echo $user['full_name'];?>"
}
function getCashier4()
{
    var d = document.getElementById('deposit_4');
    var d1 = document.getElementById('cashier_4');
    d1.value="<?php $user = Session::get('user_data'); echo $user['full_name'];?>"
}

function checkquantity($id)
{
    console.log(to_numeric(jQuery("#"+$id).val()));
    if(to_numeric(jQuery("#"+$id).val())<=0)
    {
        alert('Số lượng món không được nhỏ hơn hoặc bằng 0');
        jQuery("#"+$id).val(1);
    }
}

// Start Oanh
function check_value()
{
    //1.  lay ra ngay gio hien tai & check 
    var check_in_date = document.getElementById('checkin_date').value;
    var hour_checkin = document.getElementById('checkin_hour').value;
    var check_in_str = check_in_date.split('/');
    var hour_str = hour_checkin.split(':');
    var da = new Date();
    da.setFullYear(check_in_str[2],check_in_str[1]-1,check_in_str[0]);
    
    da.setHours(hour_str[0],hour_str[1],60);
    
    var real = new Date();
    var real_time = parseInt(da.getTime()/1000);
    var now_time = parseInt(real.getTime()/1000);
    //+ neu chon thoi gian trong qua khu thi thong bao hien thi
    var $action = '<?php isset($_REQUEST['action'])?$_REQUEST['action']:''; ?>';
    if(real_time<now_time && $action=='add')
    {
        alert('<?php echo Portal::language('khong_chon_thoi_gian_trong_qua_khu');?>');
        document.getElementById('checkin_hour').focus();
        document.getElementById('checkin_hour').style.backgroundColor ='yellow';
        return false;
    }
    else
    {
        //+ kiem tra gio ket thuc lon hon gio bat dau
        var checkout_hour = document.getElementById('checkout_hour').value;
        var checkout_hour_str = checkout_hour.split(':');
        var num_check_in = hour_str[0]*60 + hour_str[1];
        var num_checkout =checkout_hour_str[0] * 60 + checkout_hour_str[1];
        if(to_numeric(num_checkout)<=to_numeric(num_check_in))
        {
            alert('<?php echo Portal::language('thoigian_ketthuc_lonhon_batdau');?>');
            document.getElementById('checkout_hour').focus();
            document.getElementById('checkout_hour').style.backgroundColor='yellow';
            return false
        }
    }
    return true;   
} 
// End Oanh 
</script>