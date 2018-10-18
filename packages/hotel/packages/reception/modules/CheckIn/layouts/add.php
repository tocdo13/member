<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<span id="input_group_#xxxx#">
			<input  name="mi_reservation_room[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:70px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="text" id="room_level_id_#xxxx#" style="display:none;">
			</span>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:47px;font-weight:bold;" readonly="readonly" class="readonly">
				<a href="#" onclick="window.open('?page=room_map&cmd=select&object_id=#xxxx#','select_room');return false;"><img src="skins/default/images/cmd_Tim.gif"></a>
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:20px;" onchange="$('price_#xxxx#').value=0;"><img src="packages/core/skins/default/images/buttons/adult.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:20px;" onchange="$('price_#xxxx#').value=0;"><img src="packages/core/skins/default/images/buttons/child.png" align="top"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:35px;" type="text" id="price_#xxxx#" onkeyup="count_price('#xxxx#',false);" class="price">
			</span>
			<span class="multi-input">
            <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate"> </span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:50px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_out]" style="width:50px;" type="text" id="time_out_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:70px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',false);" class="date-select">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:70px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',false);updateRoomForTraveller('#xxxx#');" class="date-select">
			</span>
			<span class="multi-input" style="width:80px;">
			<select  name="mi_reservation_room[#xxxx#][status]" id="status_#xxxx#"  style="width:80px;"
					onchange="
						if(this.value=='CHECKIN' || this.value=='CHECKOUT')
						{
							jQuery('#invoice_#xxxx#').show();
						}
						else
						{
							jQuery('#invoice_#xxxx#').hide();
						}
						if(this.value=='CHECKOUT')
						{
							$('departure_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
							$('time_out_#xxxx#').value='<?php echo date('H:i')?>';
						}
						if(this.value=='CHECKIN')
						{
							$('arrival_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
							//if($('time_in_#xxxx#').value=='')
							{
								$('time_in_#xxxx#').value='<?php echo date('H:i',time())?>';
							}
							//$('time_out_#xxxx#').value='<?php echo date('H:i',time())?>';
						}
						count_price('#xxxx#',false);
						">
						<option value="BOOKED">Booked</option>	
						<option value="CHECKIN">Check in</option>
						<option value="CHECKOUT">Check out</option>	
						<option value="CANCEL">Cancel</option>	
					</select>
                    <input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#">
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" style="width:85px;" type="text" id="reservation_type_id_#xxxx#">[[|reservation_type_options|]]</select>
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][confirm]" type="checkbox" id="confirm_#xxxx#">
			</span>
            <span class="multi-input" style="float:right;padding-right:5px;">
					<span style="display:none;" id="expand_#xxxx#"></span>
                    <img id="expand_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShorten('#xxxx#');">
			</span>
			<span class="multi-input" style="width:30px;text-align:center;float:right;" id="delete_reservation_room_#xxxx#">
				<!--IF:right_cond(User::can_delete(false,ANY_CATEGORY))--><img align="left" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(confirm('[[.are_you_sure.]]')){var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount-1;mi_delete_row($('input_group_#xxxx#'),'mi_reservation_room','#xxxx#','');event.returnValue=false;}" style="cursor:pointer;"><!--/IF:right_cond-->
			</span>
			<br clear="all">
			<span id="mi_reservation_room_sample_#xxxx#" style="display:none;">
                <div class="room-extra-info" id="room_extra_info_#xxxx#">
                    <div>
                        <span class="multi-input-extra">
                            <span class="label">[[.foc.]]</span>
							<input  name="mi_reservation_room[#xxxx#][foc]" type="text" id="foc_#xxxx#" style="width:74px;"><input  name="mi_reservation_room[#xxxx#][foc_all]" style="width:15px;" value="1" type="checkbox" id="foc_all_#xxxx#" align="left" title="[[.foc_all_services.]]">
                        </span>
                        <span class="multi-input-extra">
                            <span class="label">[[.discount.]] (% / <?php echo HOTEL_CURRENCY;?>)</span>
                            <input  name="mi_reservation_room[#xxxx#][reduce_balance]" type="text" id="reduce_balance_#xxxx#" style="width:20px;" maxlength="3" onchange="count_price('#xxxx#',false);" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][reduce_amount]" type="text" id="reduce_amount_#xxxx#" style="width:68px;"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" onchange="count_price('#xxxx#',false);">
							<span class="multi-input-extra">
                        </span>    
						<span class="multi-input-extra">
                            <span class="label">[[.deposit_amount.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][deposit]" type="text" id="deposit_#xxxx#" onchange="if(this.value){$('deposit_date_#xxxx#').value='<?php echo date('d/m/Y');?>';}else{$('deposit_date_#xxxx#').value='';} count_price('#xxxx#',false);"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                        </span>
						<span class="multi-input-extra">
                            <span class="label">[[.invoice_number.]]</span>
							<input  name="mi_reservation_room[#xxxx#][deposit_invoice_number]" type="text" id="deposit_invoice_number_#xxxx#">
                            <input  name="mi_reservation_room[#xxxx#][total_amount]" type="hidden" id="total_amount_#xxxx#" readonly="">
                        </span>
                    </div><br clear="all">
                    <div>
						<span class="multi-input-extra" style="display:none;">
							<span class="label">[[.vip_card_code.]]</span> 
							<input name="mi_reservation_room[#xxxx#][vip_card_code]" type="text" id="vip_card_code_#xxxx#" class="reservation-vip-card-code" onchange="$('reduce_balance_#xxxx#').value = (vip_card_list[this.value]!='undefined')?vip_card_list[this.value]:'';count_price('#xxxx#',false);">
						</span>
                        <span class="multi-input-extra">
                        		<span class="label">[[.Remarks.]]</span>
                                <input  name="mi_reservation_room[#xxxx#][note]" type="text" id="note_#xxxx#">
                        </span>
						<span class="multi-input-extra">
                            <span class="label">[[.tax_rate.]] / [[.service_rate.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][tax_rate]" style="width:45px;" maxlength="3" type="text" id="tax_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][service_rate]" style="width:43px;" maxlength="3" type="text" id="service_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                        </span>
						 <span class="multi-input-extra">
                            <span class="label">[[.deposit_type.]]</span>
							<select  name="mi_reservation_room[#xxxx#][deposit_type]" id="deposit_type_#xxxx#"  style="width:105px;float:left;">
								<option value="CASH">[[.cash.]]</option>	
								<option value="CREDIT_CARD">[[.credit.]]</option>	
							</select>
                        </span>
						<span class="multi-input-extra">
                            <span class="label">[[.deposit_date.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][deposit_date]" type="text" id="deposit_date_#xxxx#" readonly="readonly">
                        </span>
                    </div>
					<div id="price_schedule_bound_#xxxx#" style="float:left;width:100%;padding:5px 0px 5px 0px;margin:5px 0px 5px 0px;display:none;">
						<span class="multi-input-extra">
							 <span>[[.price_schedule.]]</span><br />
							 <span id="price_schedule_#xxxx#"></span>
						</span>
					</div>
                    <br clear="all">
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;
                    	<span id="invoice_#xxxx#" style="display:none;">
                            <a href="javascript:;" onclick="if($('id_#xxxx#').value!=''){window.open('?page=reservation&cmd=invoice&total_amount='+$('total_amount_#xxxx#').value+'&price='+$('price_#xxxx#').value+'&deposit='+$('deposit_#xxxx#').value+'&reduce_balance='+$('reduce_balance_#xxxx#').value+'&reduce_amount='+$('reduce_amount_#xxxx#').value+'&time_in='+$('time_in_#xxxx#').value+'&time_out='+$('time_out_#xxxx#').value+'&departure_time='+$('departure_time_#xxxx#').value+'&tax_rate='+$('tax_rate_#xxxx#').value+'&service_rate='+$('service_rate_#xxxx#').value+'&id='+$('id_#xxxx#').value+'');}"><img src="skins/default/images/edit.png" width="15"> [[.preview_invoice.]]</a> |
                            <a href="javascript:;" onclick="if($('id_#xxxx#').value!=''){window.open('?page=reservation&cmd=invoice&type=ROOM&total_amount='+$('total_amount_#xxxx#').value+'&price='+$('price_#xxxx#').value+'&deposit='+$('deposit_#xxxx#').value+'&reduce_balance='+$('reduce_balance_#xxxx#').value+'&reduce_amount='+$('reduce_amount_#xxxx#').value+'&time_in='+$('time_in_#xxxx#').value+'&time_out='+$('time_out_#xxxx#').value+'&departure_time='+$('departure_time_#xxxx#').value+'&tax_rate='+$('tax_rate_#xxxx#').value+'&service_rate='+$('service_rate_#xxxx#').value+'&id='+$('id_#xxxx#').value+'');}"><img src="skins/default/images/edit.png" width="15"> [[.preview_room_invoice.]]</a> |
                            <a href="javascript:;" onclick="if($('id_#xxxx#').value!=''){window.open('?page=reservation&cmd=invoice&type=SERVICE&total_amount='+$('total_amount_#xxxx#').value+'&price='+$('price_#xxxx#').value+'&deposit='+$('deposit_#xxxx#').value+'&reduce_balance='+$('reduce_balance_#xxxx#').value+'&reduce_amount='+$('reduce_amount_#xxxx#').value+'&time_in='+$('time_in_#xxxx#').value+'&time_out='+$('time_out_#xxxx#').value+'&departure_time='+$('departure_time_#xxxx#').value+'&tax_rate='+$('tax_rate_#xxxx#').value+'&service_rate='+$('service_rate_#xxxx#').value+'&id='+$('id_#xxxx#').value+'');}"><img src="skins/default/images/edit.png" width="15"> [[.preview_service_invoice.]]</a>
							<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="update" type="submit" value="[[.update.]]" class="update-room-info"> <a href="#" title="&#272;&#7875; &#273;&#7843;m b&#7843;o th&#244;ng tin ch&#237;nh x&#225;c b&#7841;n h&#227;y nh&#7845;n n&#250;t c&#7853;p nh&#7853;t tr&#432;&#7899;c khi xem h&#243;a &#273;&#417;n"><img src="packages/core/skins/default/images/buttons/question.gif" /></a><?php }?>
                        </span>
                    </div>
                 </div>
                <hr size="1" color="#CCCCCC"> 
                <br clear="all">
             </span>   
		</span>	
	</span>	 
	<span id="mi_traveller_sample">	
		<span id="input_group_#xxxx#">
			<input  name="mi_traveller[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<input  name="mi_traveller[#xxxx#][traveller_id_]" type="hidden" id="traveller_id__#xxxx#">
			<table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
			  <tr>
				<td class="multi-input-header" width="55">[[.room.]] (*)</td>
				<td class="multi-input-header" width="120">[[.first_name.]] (*)</td>
				<td class="multi-input-header" width="80">[[.last_name.]] (*)</td>
				<td class="multi-input-header" width="80">[[.passport.]] </td>
				<td class="multi-input-header" width="60">[[.gender.]]</td>
				<td class="multi-input-header" width="60">[[.birth_date.]]</td>
				<td class="multi-input-header" width="120">[[.nationality.]]</td>
                <td class="multi-input-header" width="50">[[.time_in.]]</td>
                <td class="multi-input-header" width="50">[[.time_out.]]</td>
                <td class="multi-input-header" width="60">[[.arrival_date.]]</td>
				<td class="multi-input-header" width="60">[[.departure_date.]]</td>
                <td class="multi-input-header" style="border:0px;background:none;"></td>
			  </tr>
			  <tr>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][mi_traveller_room_name]" type="text" id="mi_traveller_room_name_#xxxx#" readonly="readonly" class="select-room" style="width:55px;" onclick="display_room_table(this,'#xxxx#');">
				  <input  name="mi_traveller[#xxxx#][traveller_room_id]" type="hidden" id="traveller_room_id_#xxxx#"></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][first_name]" style="width:120px;" type="text" id="first_name_#xxxx#"></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][last_name]" style="width:80px;" type="text" id="last_name_#xxxx#"></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][passport]" style="width:80px;" type="text" id="passport_#xxxx#" onchange="get_traveller('#xxxx#');"></td>
				<td class="multi-input"><select  name="mi_traveller[#xxxx#][gender]" id="gender_#xxxx#" style="width:65px;" >
                    <option value="1" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['gender']) and $_REQUEST['mi_traveller']['#xxxx#']['gender']==1)?'selected="selected"':''?>>[[.male.]]</option>
                    <option value="0" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['gender']) and $_REQUEST['mi_traveller']['#xxxx#']['gender']==0)?'selected="selected"':''?>>[[.female.]]</option>
                </select></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][birth_date]" style="width:60px;" type="text" id="birth_date_#xxxx#" ></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][nationality_id]" style="width:120px;"  id="nationality_id_#xxxx#" AUTOCOMPLETE=OFF></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][time_in]" style="width:50px;" type="text" id="time_in_#xxxx#" ></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][time_out]" style="width:50px;" type="text" id="time_out_#xxxx#" ></td>                
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][arrival_date]" style="width:60px;" type="text" id="arrival_date_#xxxx#"></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][departure_date]" style="width:60px;" type="text" id="departure_date_#xxxx#" ></td>
				<td class="multi-input"><img src="packages/core/skins/default/images/buttons/delete.gif" onclick="if(confirm('[[.are_you_sure.]]')){mi_delete_row($('input_group_#xxxx#'),'mi_traveller','#xxxx#','');event.returnValue=false;}" style="cursor:pointer;"/></td>
			  </tr>
			  <tr>
                <td class="multi-input" colspan="12">
                	[[.phone.]]: <input  name="mi_traveller[#xxxx#][phone]" style="width:70px;" type="text" id="phone_#xxxx#" >
                	[[.email.]]: <input  name="mi_traveller[#xxxx#][email]" style="width:160px;"  id="email_#xxxx#">
			    	[[.address.]]: <input  name="mi_traveller[#xxxx#][address]" style="width:160px;"  id="address_#xxxx#">
			    	[[.note.]]: <input  name="mi_traveller[#xxxx#][note]" style="width:160px;"  id="note_#xxxx#">
                	[[.payment.]]: <input  name="mi_traveller[#xxxx#][traveller_id]" type="checkbox" id="traveller_id_#xxxx#"></td>
			  </tr>
			</table>
			<span id="detail_link_#xxxx#"></span>
			<br>
		</span>
	</span>
</span>
<form name="AddReservationForm" method="post">
<div style="text-align:center">
<div style="width:940px;margin-right:auto;margin-left:auto;">
<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;" align="center">

	<tr valign="top">
		<td align="left">
			<table width="100%" cellpadding="15" cellspacing="0" class="table-bound">
				<tr><td width="700" nowrap  class="form-title">[[.reservation.]]</td>
                  <td nowrap="nowrap" width="250" align="right">
				  	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save" onclick="AddReservationForm.submit();this.disabled=true;">
				  	<?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a><?php }?></td>
				</tr>
		  </table>
		</td>
	</tr>
	<tr valign="top">
	<td><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?>
	<table width="100%">
	<tr><td align="left">
			<fieldset style="background:#EFEFEF;margin-bottom:4px;">
			<legend class="legend-title">[[.general_information.]]</legend>
                <div id="head_table">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                 <tr valign="top">
                   <td align="right" nowrap>&nbsp;</td>
                   <td align="right">[[.booking_code.]]</td>
                   <td align="left" style="padding-left:10px;"><input name="booking_code" type="text" id="booking_code" style="width:215px;"></td>
                   <td align="right" class="label">&nbsp;</td>
                   <td rowspan="4" width="40%">[[.note_for_tour_or_group.]]
                     <textarea name="note" id="note" style="width:99%;height:40px;"></textarea>			  
                     <a href="#" onclick="
                            if($('head_table').style.display=='none')
                            {
                                $('head_table').style.display='';
                                $('expand_r_img_#xxxx#').src='skins/default/images/up.gif';
                            }
                            else
                            {
                                $('head_table').style.display='none';
                                $('expand_r_img_#xxxx#').src='skins/default/images/down.gif';
                            }
                        "></a></td>
                 </tr>
                 <tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.tour_id.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="tour_name" type="text" id="tour_name" style="width:215px;" readonly="" class="readonly">
                      <input name="tour_id" type="hidden" id="tour_id" value="0" />
                      <a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"></td>
                    <td align="right" width="10%" class="label">&nbsp;</td>
                    </tr><tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.customer.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="customer_name" type="text" id="customer_name" style="width:215px;"  readonly="" class="readonly">
                      <input name="customer_id" type="text" id="customer_id" style="display:none;">
                      <a href="#" onclick="window.open('?page=customer&amp;action=select_customer&group_id=TOURISM','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;"></td>
                    <td >&nbsp;</td>
                 </tr>
                    <tr valign="top" style="display:none;">
                      <td align="right" nowrap>&nbsp;</td>
                      <td align="right" height="20">[[.Room_total_amount.]]</td>
                      <td align="left" style="padding-left:10px;font-weight:bold;"><span id="total_payment"></span></td>
                      <td >&nbsp;</td>
                    </tr>
                  <tr valign="top" style="display:none;">
					<td align="right" nowrap>&nbsp;</td>
					<td align="right">[[.payment.]]</td>
					<td align="left" style="padding-left:10px;"><input name="payment" type="text" id="payment" style="width:215px;">
					  USD</td>
					<td align="right"></td>
					<td align="left"></td>
                </tr>
                  <tr valign="top">
                    <td align="right" nowrap></td>
                    <td align="right">[[.color_of_group.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="color" type="text" id="color" style="width:215px;"><span onclick="TCP.popup($('color'));" class="color-select-button" title="[[.select_color.]]"><img src="packages/core/skins/default/images/color_picker.gif" width="15"></span></td>
                    <td align="right">&nbsp;</td>
                    <td align="left"></td>
                  </tr>
            </table>
            </div>
        </fieldset>
		<fieldset style="background:#F2F4CE" id="reservation_room">
			<legend class="legend-title">[[.reservation_room.]]&nbsp;(<span id="count_number_of_room">1</span> [[.room.]])</legend>
			<span id="mi_reservation_room_all_elems">
				<span>
					<span class="multi-input-header" style="width:70px;">[[.room_level.]]</span>
					<span class="multi-input-header" style="width:65px;">[[.room_id.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.child.]]</span>
					<span class="multi-input-header" style="width:35px;">[[.price.]]</span>
					<span class="multi-input-header"  style="width:12px;"></span>
					<span class="multi-input-header" style="width:50px;">[[.time_in.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.time_out.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.arrival_time.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.departure_time.]]</span>
					<span class="multi-input-header" style="width:75px;">[[.status.]]</span>
					<span class="multi-input-header" style="width:80px;">[[.reservation_type.]]</span>
					<span class="multi-input-header" style="width:20px;">[[.confirm.]]</span>					
					<span class="multi-input-header" style="float:right;border:0px;background:none;">&nbsp;</span>
					<span id="expand_all_span" style="float:right;"><img id="expand_all" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandAll();"></span>
					<br clear="all" />
				</span>
			</span>
			<br clear="all">
			<input type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');jQuery('.date-select').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) });jQuery('#time_in_'+input_count).mask('99:99');jQuery('#time_out_'+input_count).mask('99:99');buildRateList(input_count);">
		</fieldset> 
		</td></tr>
		<tr><td>
		<fieldset style="background:#DFEFFF;">
			<legend class="legend-title">[[.traveller.]]</legend><br />
			<!--IF:notice_cond(Portal::language()==1)-->	
			<div class="notice">B&#7841;n nh&#7853;p s&#7889; h&#7897; chi&#7871;u/CMTND &#273;&#7875; bi&#7871;t kh&aacute;ch &#273;&atilde; &#7903; hay ch&#432;a. N&#7871;u kh&aacute;ch &#273;&atilde; t&#7915;ng &#7903; th&igrave; khi nh&#7853;p xong s&#7889; h&#7897; chi&#7871;u/CMTND th&ocirc;ng tin c&#7911;a kh&aacute;ch &#7903; s&#7869; t&#7921;ng &#273;&#7897;ng c&#7853;p nh&#7853;t. </div>
			<!--ELSE-->
			<div class="notice">Enter the passport / ID card number to check the guest history. If he is a return guest, his information will be updated automatically on screen.</div>
			<!--/IF:notice_cond-->
			<span id="mi_traveller_all_elems">
			</span>
			<input type="button" value="[[.add_traveller.]]" onclick="mi_add_new_row('mi_traveller');myAutocomplete();jQuery('#birth_date_'+input_count).mask('99/99/9999');jQuery('#time_in_'+input_count).mask('99:99');jQuery('#time_out_'+input_count).mask('99:99');jQuery('#arrival_date_'+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });jQuery('#departure_date_'+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });">
		</fieldset> 
	</td>
	</tr>
	</table>
	</td></tr>
</table><p>&nbsp;</p>
</div>
</div>
<div id="selected_room" style="display:none;position:absolute;top:0px;left:0px;width:150px;background-color:#FFCC00;border:1px solid #FF9900;padding:2px;vertical-align:top;">
    <div id="rooms" style="width:100%;background-color:#FFFFFF;">
        <div style="height:20px;background-color:#FF9900;border:1px solid #000000;text-align:center;margin:2px;color:#FFFFFF;font-weight:bold;text-transform:uppercase;padding:2px 0px 2px 0px;">
            [[.rooms.]]&nbsp;&nbsp;
            <a onclick="$('selected_room').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]"></a>
        </div>
    </div>
</div>
<div id="rate_list" class="room-rate-list" style="display:none;">
    <div>
        [[.rate_list.]]&nbsp;&nbsp;
        <a onclick="$('rate_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]"></a>
    </div>
    <ul id="rate_list_result">
    </ul>
</div>

</form>
<script>
vip_card_list = <?php echo String::array2js([[=vip_card_list=]]);?>;
var holidays = [[|holidays|]];
var saturday_charge = <?php echo EXTRA_CHARGE_ON_SATURDAY;?>;
var sunday_charge = <?php echo EXTRA_CHARGE_ON_SUNDAY;?>;
function handleKeyPress(evt) {  
	var nbr;  
	var nbr = (window.event)?event.keyCode:evt.which;
	if(nbr==13){
		if(!confirm('[[.Are_you_sure_to_add_reservation.]]?')){
			return false;
		}
	}
	return true;
}
document.onkeydown= handleKeyPress;
function display_room_table(obj,current_id)
{
	if($('selected_room').style.display=="none")
	{
		$('selected_room').style.display="";
		$('selected_room').style.top=obj.offsetTop-20+'px';
		$('selected_room').style.left=obj.offsetLeft+100+'px';
		$('selected_room').className=current_id;
	}
	asign_room();
}
</script>
<script>
<?php if(isset($_REQUEST['mi_reservation_room']))
{
	echo 'var mi_reservation_room_arr = '.String::array2js($_REQUEST['mi_reservation_room']).';';
	echo 'mi_init_rows(\'mi_reservation_room\',mi_reservation_room_arr);';
}
else
{
	echo 'mi_add_new_row(\'mi_reservation_room\',true);';
}
?>
<?php if(isset($_REQUEST['mi_traveller']))
{
	echo 'mi_init_rows(\'mi_traveller\','.String::array2js($_REQUEST['mi_traveller']).');';
}
else
{
	//echo 'mi_add_new_row(\'mi_traveller\',true);';
}
?>
contruct_elements();
//contruct_currency();
function expandShorten(id)
{
	if($('expand_'+id).innHTML=='')
	{
		$('expand_'+id).innHTML='+';
		$('mi_reservation_room_sample_'+id).style.display='none';
		$('expand_img_'+id).src='packages/core/skins/default/images/buttons/node_close.gif';
	}
	else
	{
		$('expand_'+id).innHTML='';
		$('mi_reservation_room_sample_'+id).style.display='';
		$('expand_img_'+id).src='packages/core/skins/default/images/buttons/node_open.gif';
	}
}
function expandAll(){
	
	if($('expand_all_span').innHTML=='')
	{
		$('expand_all_span').innHTML='+';
		$('expand_all').src='packages/core/skins/default/images/buttons/node_close.gif';
		for(var i=101;i<=input_count;i++){
			if($('expand_img_'+i)){
				expandShorten(i);
			}
		}
	}
	else
	{
		$('expand_all_span').innHTML='';
		$('expand_all').src='packages/core/skins/default/images/buttons/node_open.gif';
		for(var i=101;i<=input_count;i++){
			if($('expand_img_'+i)){
				expandShorten(i);
			}
		}	
	}
}
var columns=all_forms['mi_reservation_room'];
for(var i in columns)
{	
	count_price(columns[i],false);
}
var roomCount = to_numeric($('count_number_of_room').innerHTML);
for(var i=101;i<=input_count;i++){
	if(jQuery("#birth_date_"+i)){
		jQuery("#birth_date_"+i).mask("99/99/9999");
	}
	if(jQuery("#arrival_time_"+i)){
		roomCount++;
		jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) });
	}
	if(jQuery("#departure_time_"+i)){
		jQuery("#departure_time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) });
	}
	<?php if(User::can_admin(false,ANY_CATEGORY)){?>
	if(jQuery("#deposit_date_"+i)){
		jQuery("#deposit_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) });
	}
	<?php }else{?>
	<?php }?>
	buildRateList(i);
	if(jQuery("#traveller_id_"+i)){
		jQuery("#arrival_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) });
		jQuery("#departure_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) });
	}
	jQuery("#time_in_"+i).mask("99:99");
	jQuery("#time_out_"+i).mask("99:99");
}
function buildRateList(index){
	if(jQuery('#rate_list_'+index) && jQuery('#room_level_id_'+index)){
		jQuery('#rate_list_'+index).click(function(){
			var i = this.id.substr(10);
			var customerId = jQuery('#customer_id').val();
			var roomLevelId = jQuery("#room_level_id_"+i).val();
			var adult = jQuery("#adult_"+i).val();
			var child = jQuery("#child_"+i).val();
			getRateList(jQuery(this).attr('id'),roomLevelId,i,customerId,adult,child);
		});
	}
}
$('count_number_of_room').innerHTML = roomCount-1;
function myAutocomplete()
{
	jQuery("#nationality_id_"+input_count).autocomplete({
		url:'r_get_countries.php',
		minChars: 0,
		width: 280,
		matchContains: true,
		autoFill: false
	});
}
</script>
<!-- [[. time_in_is_more_than_current_time.]] -->