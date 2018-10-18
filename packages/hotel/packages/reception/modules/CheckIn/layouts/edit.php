<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<span id="input_group_#xxxx#">
		<div id="reservation_room_bound_#xxxx#">
			<input  name="mi_reservation_room[#xxxx#][id]" type="text" id="id_#xxxx#" style="width:50px;border:1px solid #CCCCCC;background:#EFEFEF;color:#999999;display:none;" readonly="">
			<span class="multi-input">
				<input  type="text" id="room_level_name_#xxxx#" style="width:70px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="hidden" id="room_level_id_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:47px;font-weight:bold;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="window.open('?page=room_map&cmd=select&room_id='+$('room_id_#xxxx#').value+'&room_level_id='+$('room_level_id_#xxxx#').value+'&object_id=#xxxx#','select_room');return false;" style="cursor:pointer;">
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:20px;" onchange="$('price_#xxxx#').value=0;"><img src="packages/core/skins/default/images/buttons/adult.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:20px;" onchange="$('price_#xxxx#').value=0;"><img src="packages/core/skins/default/images/buttons/child.png" align="top"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:35px;" type="text" id="price_#xxxx#" onkeyup="count_price('#xxxx#',false);" class="price">
			</span>
			<span class="multi-input" title="[[.select_rate.]]" style="width:16px;height:5px;">
            <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate" alt="[[.select_rate.]]"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:50px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_out]" style="width:50px;" type="text" id="time_out_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:60px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',false);" class="date-select">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:60px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',false);updateRoomForTraveller('#xxxx#');" class="date-select">
			</span>
			<span class="multi-input" style="width:80px;">
			<select  name="mi_reservation_room[#xxxx#][status]" id="status_#xxxx#"  style="width:80px;"
					onchange="
						if(this.value=='CHECKIN' || this.value=='CHECKOUT'){
							jQuery('#invoice_#xxxx#').show();
						} else {
							jQuery('#invoice_#xxxx#').hide();
						}
						if(this.value=='CHECKOUT'){
							$('departure_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
							$('time_out_#xxxx#').value='<?php echo date('H:i')?>';
							$('closed_#xxxx#').checked = true;
							$('closed_#xxxx#').readOnly = true;
						} else {
							$('closed_#xxxx#').checked = false;
							$('closed_#xxxx#').readOnly = false;
						}
						if(this.value=='CHECKIN'){
                        	if(!$('room_id_#xxxx#').value){
                            	alert('[[.miss_room_number.]]');
                            }
							$('arrival_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
							$('time_in_#xxxx#').value='<?php echo date('H:i',time())?>';
						}
						count_price('#xxxx#',false);
						">
						<option value="BOOKED">Booked</option>	
						<option value="CHECKIN">Check in</option>
						<option value="CHECKOUT">Check out</option>	
						<option value="CANCEL">Cancel</option>	
					</select>
			</span>
			<span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" style="width:65px;" type="text" id="reservation_type_id_#xxxx#">[[|reservation_type_options|]]</select>
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][confirm]" type="checkbox" id="confirm_#xxxx#">
			</span>
			<span class="multi-input" style="width:55px;">
					&nbsp;&nbsp;&nbsp;&nbsp;<input  name="mi_reservation_room[#xxxx#][closed]" type="checkbox" id="closed_#xxxx#">
			</span>
			<span class="multi-input">
					<input  type="button" id="view_invoice_#xxxx#" onclick="viewInvoice('#xxxx#');" value="[[.print_order.]]" class="view-order-button">
			</span>
			<span class="multi-input" style="float:right;padding-right:5px;">
					<span style="display:none;" id="expand_#xxxx#"></span>
                    <img id="expand_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShorten('#xxxx#');">
			</span>
			<span class="multi-input" style="width:30px;text-align:center;float:right;" id="delete_reservation_room_#xxxx#">
				<!--IF:right_cond(User::can_delete(false,ANY_CATEGORY))--><img align="left" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(confirm('[[.are_you_sure.]]')){var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount-1;mi_delete_row($('input_group_#xxxx#'),'mi_reservation_room','#xxxx#','');event.returnValue=false;}" style="cursor:pointer;"><!--/IF:right_cond-->
			</span>
			<br clear="all" />
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
                    </div>
                    <div>
						<span class="multi-input-extra">
                            <span class="label">[[.invoice_number.]]</span>
							<input  name="mi_reservation_room[#xxxx#][deposit_invoice_number]" type="text" id="deposit_invoice_number_#xxxx#">
                            <input  name="mi_reservation_room[#xxxx#][total_amount]" type="hidden" id="total_amount_#xxxx#" readonly="">
                        </span>
						<span class="multi-input-extra" style="display:none;">
							<span class="label">[[.vip_card_code.]]</span> 
							<input name="mi_reservation_room[#xxxx#][vip_card_code]" type="text" id="vip_card_code_#xxxx#" class="reservation-vip-card-code" onchange="$('reduce_balance_#xxxx#').value = (vip_card_list[this.value]!='undefined')?vip_card_list[this.value]:'';count_price('#xxxx#',false);">
						</span>
                        <span class="multi-input-extra">
                        		<span class="label">[[.Remarks.]]</span>
                                <input  name="mi_reservation_room[#xxxx#][note]" type="text" id="note_#xxxx#">
                        </span>
                        <span class="multi-input-extra">
							<input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#">
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
                            <input  name="mi_reservation_room[#xxxx#][deposit_date]" type="text" id="deposit_date_#xxxx#" value="<?php echo date('d/m/Y');?>" readonly="readonly">
                        </span>
                    </div>
					<div class="service-list-bound">
					<div id="service_#xxxx#" class="service-list">
						<span class="multi-input-extra">
							 <span><strong>[[.other_charges.]]:</strong></span><br />
							 <!--LIST:services-->
								<span>[[|services.name|]]<br /><input name="mi_reservation_room[#xxxx#][service_[[|services.id|]]]" type="text" id="service_[[|services.id|]]_#xxxx#"> <?php echo HOTEL_CURRENCY;?>&nbsp;</span>
							 <!--/LIST:services-->
						</span><br clear="all">
					</div>
					</div>
					<div id="price_schedule_bound_#xxxx#" style="display:none;float:left;width:100%;padding:5px 0px 5px 0px;margin:5px 0px 5px 0px;">
						<span class="multi-input-extra">
							 <span><strong>&nbsp;&nbsp;[[.price_schedule.]]</strong></span><br />
							 <span id="price_schedule_#xxxx#"></span>
						</span>
					</div><br clear="all">
					<div id="payment_type_bound_#xxxx#" class="payment-type-bound" style="display:none;">
						<span class="multi-input-extra">
                              <span><strong>[[.payment_type.]]:</strong> 
							 <select  name="mi_reservation_room[#xxxx#][def_code]" id="def_code_#xxxx#" style="width:auto;" 
                            onchange="
                                if(this.value=='CASH' || this.value=='CREDIT_CARD'){
                                    //jQuery('#currencies_#xxxx#').show();
									//AddReservationForm.update.submit();
									jQuery('#related_rr_#xxxx#').hide();
                                } else {
                                    jQuery('#currencies_#xxxx#').hide();
									jQuery('#related_rr_#xxxx#').show();
                                }
								">
                                <option value="CASH">[[.pay_now.]]</option>
                                <option value="DEBIT">[[.debit.]]</option>
								<option value="CREDIT_CARD">[[.credit_card.]]</option>
                            </select>
							<span id="related_rr_#xxxx#" style="float:right;width:340px;">[[.pay_with_room.]] ([[.input_invoice_id.]]): <input  name="mi_reservation_room[#xxxx#][related_rr_id]" type="text" id="related_rr_id_#xxxx#" size="8" maxlength="11" style="float:right;font-size:11px;font-weight:bold;color:#FF0000;"></span>
							</span>
							 <div id="currencies_#xxxx#" class="currency-list" style="display:none;"><span>&nbsp;</span>
							 	<?php $i=0;?>
							 	<!--LIST:currencies-->
								<span><br /><?php echo ($i>0)?'+':'';$i++;?>&nbsp;</span>
								<span>
							 	[[|currencies.name|]]<br /><input name="mi_reservation_room[#xxxx#][currency_[[|currencies.id|]]]" type="text" id="currency_[[|currencies.id|]]_#xxxx#" style="width:65px;" onclick="this.value=number_format(to_numeric($('total_payment_#xxxx#').value)*[[|currencies.exchange|]]);">
								</span>
								<!--/LIST:currencies-->
								<span><br />&nbsp;&nbsp;</span><span>[[.total_payment.]]<br /><strong><span><input name="mi_reservation_room[#xxxx#][total_payment]" type="text" id="total_payment_#xxxx#" readonly="readonly"  style="width:200px;border:0px;font-weight:bold;"></span></strong></span>
							 </div>
						</span>
					</div>
                    <br clear="all">
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;
                    	<span id="invoice_#xxxx#" style="display:none;" class="invoice-option">
                           	<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="room_invoice_#xxxx#" checked="checked">[[.room_invoice.]] |
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="hk_invoice_#xxxx#" checked="checked">[[.housekepping_invoice.]] |
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="bar_invoice_#xxxx#" checked="checked">[[.bar_invoice.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="other_invoice_#xxxx#" checked="checked">[[.other_invoice.]] |
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="phone_invoice_#xxxx#" checked="checked">[[.phone_invoice.]] |
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="extra_service_invoice_#xxxx#" checked="checked">[[.extra_service_invoice.]] |
							<!--IF:cond_message(HAVE_MASSAGE)-->
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="massage_invoice_#xxxx#" checked="checked">[[.massage_invoice.]] |
							<!--/IF:cond_message-->
							<!--IF:cond_tennis(HAVE_TENNIS)-->
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="tennis_invoice_#xxxx#" checked="checked">[[.tennis_invoice.]] |
							<!--/IF:cond_tennis-->
							<!--IF:cond_swimming(HAVE_SWIMMING)-->
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="swimming_pool_invoice_#xxxx#" checked="checked">[[.swimming_pool_invoice.]] |
							<!--/IF:cond_swimming-->
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="included_deposit_#xxxx#" checked="checked">[[.included_deposit.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="included_related_total_#xxxx#" checked="checked">[[.included_related_total.]]
							<input  type="hidden" id="url_#xxxx#" value="&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&tennis_invoice=1&swimming_pool_invoice=1">
                        </span>
                    </div>
                    <!--IF:cond__(User::can_view(false,ANY_CATEGORY))--><span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card'));?>&id='+$('id_#xxxx#').value)">[ [[.guest_registration_card.]] ]</a><!--/IF:cond__-->
					<!--IF:cond__(User::can_view(false,ANY_CATEGORY))--><span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>1));?>&id='+$('id_#xxxx#').value)">[ [[.registration_form.]] ]</a><!--/IF:cond__-->
                 </div>
                <br clear="all">
                <hr size="1" color="#CCCCCC">
             </span>   
		</div>	 
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
</span></span>
<form  name="AddReservationForm" method="post">
<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
<div style="text-align:center;">
<div style="width:940px;margin-right:auto;margin-left:auto;">
<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;">
	<tr valign="top">
		<td align="left">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-bound">
				<tr height="40">
					<td width="90%" class="form-title">[[.reservation.]]</td>
					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="save" type="button" value="[[.Save_and_close.]]" class="button-medium-save" onclick="AddReservationForm.submit();this.disabled=true;"></td><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="update" type="submit" value="[[.Save_and_stay.]]" class="button-medium-save"></td><?php }?>
                    <?php if(User::can_admin(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'));?>" class="button-medium-delete">[[.delete.]]</a></td><?php }?>
				</tr>
		  </table>
		</td>
	</tr>
	<tr valign="top">
	<td><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
	<table width="100%">
	<tr><td align="left">
			<fieldset style="background:#EFEFEF;margin-bottom:5px;">
			<legend class="legend-title">[[.general_information.]]</legend>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.booking_code.]]</td>
                        <td align="left" style="padding-left:10px;"><input name="booking_code" type="text" id="booking_code" style="width:215px;"></td>
                    <td rowspan="4" width="40%"><span class="label">[[.note_for_tour_or_group.]]</span>
                      <textarea name="note" id="note" style="width:99%;height:40px;"></textarea>			  <a href="#" onclick="
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
                      <input  name="tour_id" type="hidden" id="tour_id" value="[[|tour_id|]]">
                      <!--IF:cond(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"><!--/IF:cond--></td>
                  </tr><tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.customer.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="customer_name" type="text" id="customer_name" style="width:215px;" readonly="readonly"  class="readonly">
                      <input name="customer_id" type="text" id="customer_id" value="[[|customer_id|]]" style="display:none;">
                       <!--IF:cond(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;"><!--/IF:cond--></td>
                    </tr>
                 <tr valign="top" style="display:none;">
                      <td align="right" nowrap>&nbsp;</td>
                      <td align="right" height="20">[[.Total_payment.]]</td>
                      <td align="left" style="padding-left:10px;font-weight:bold;"><span id="total_payment"></span></td>
                    </tr>
				<tr valign="top" style="display:none;">
					<td align="right" nowrap>&nbsp;</td>
					<td align="right">[[.payment.]]</td>
					<td align="left" style="padding-left:10px;"><input name="payment" type="text" id="payment" style="width:215px;" />
					<?php echo HOTEL_CURRENCY;?></td>
					<td bgcolor="#EFEFEF">&nbsp;</td>
                </tr>
                  <tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.color_of_group.]]</td>
                    <td align="left" style="padding-left:10px;"> <input name="color" type="text" id="color" style="width:215px;"><span onclick="TCP.popup($('color'));" class="color-select-button" title="[[.select_color.]]"><img src="packages/core/skins/default/images/color_picker.gif" width="15"></span></td>
                    <td bgcolor="#EFEFEF"></td>
                  </tr>
            </table>
			<div>
			    <hr>
					[[.Include_booked.]]
					<input  name="include_booked" type="checkbox" id="include_booked">
					<a class="view-invoice-button" href="#" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'tour_invoice','id'=>[[=id=]]));?>'+(($('include_booked').checked==true)?'&include_booked=1':''))">[[.tour_invoice.]]-[[.total.]]</a>
					<a class="view-invoice-button" href="#" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'tour_invoice','id'=>[[=id=]],'room'=>1));?>'+(($('include_booked').checked==true)?'&include_booked=1':''))">[[.tour_invoice.]]-[[.room_invoice.]]</a>
					<a class="view-invoice-button" href="#" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'tour_invoice','id'=>[[=id=]],'service'=>1));?>'+(($('include_booked').checked==true)?'&include_booked=1':''))">[[.tour_invoice.]]-[[.service_invoice.]]</a>
                    <a class="view-invoice-button" href="#" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'rooming_list','id'=>[[=id=]]));?>');">[[.rooming_list.]]</a>
            </div>
        </fieldset>    
		<fieldset style="background:#F2F4CE;margin-bottom:5px;">
			<legend class="legend-title">[[.reservation_room.]]&nbsp;(<span id="count_number_of_room"></span> [[.room.]])</legend>
			<span id="mi_reservation_room_all_elems">
				<span>
					<span class="multi-input-header" style="width:70px;">[[.room_level.]]</span>
					<span class="multi-input-header" style="width:65px;">[[.room.]]</span>
					<span class="multi-input-header" style="width:32px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.child.]]</span>
					<span class="multi-input-header" style="width:35px;">[[.price.]]</span>
					<span class="multi-input-header" style="width:12px;"></span>
					<span class="multi-input-header" style="width:50px;">[[.time_in.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.time_out.]]</span>
					<span class="multi-input-header" style="width:60px;">[[.arrival_date.]]</span>
					<span class="multi-input-header" style="width:60px;">[[.departure_date.]]</span>
					<span class="multi-input-header" style="width:75px;">[[.status.]]</span>					
					<span class="multi-input-header" style="width:60px;">[[.reservation_type.]]</span>
					<span class="multi-input-header" style="width:20px;">[[.confirm.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.closed.]]</span>
					<span class="multi-input-header" style="float:right;border:0px;background:none;">&nbsp;</span>
					<span id="expand_all_span" style="float:right;"><img id="expand_all" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandAll();"></span>
					
					<br clear="all">
				</span>
			</span>
			<br clear="all">
			<input type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');updateStatusList(input_count);jQuery('#arrival_time_'+input_count).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });jQuery('#departure_time_'+input_count).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });buildRateList(input_count);">
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
			<br />
			<span id="mi_traveller_all_elems">
			</span>
			<input type="button" value="[[.add_traveller.]]" onclick="mi_add_new_row('mi_traveller');myAutocomplete(input_count);jQuery('#birth_date_'+(input_count)).mask('99/99/9999');">
		</fieldset> 
	</td>
	</tr>
	</table>
	</td></tr>
</table>
</form>
</div>
</div>
<div id="selected_room" style="display:none;position:absolute;top:0px;left:0px;width:150px;background-color:#FFCC00;border:1px solid #FF9900;padding:2px;vertical-align:top;">
		<div id="rooms" style="width:100%;background-color:#FFFFFF;">
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
<script>
	<!--IF:cond(User::can_admin())-->
	can_admin = true;
	<!--/IF:cond-->
	function handleKeyPress(evt) {  
		var nbr;  
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==13){
			if(!confirm('[[.Are_you_sure_to_update_reservation.]]?')){
				return false;
			}
		}
		if(nbr==116){
			if(!confirm('[[.Are_you_sure_to_refresh.]]?')){
				return false;
			}
		}
		return true;
	}
	document.onkeydown= handleKeyPress;

vip_card_list = <?php echo String::array2js([[=vip_card_list=]]);?>;
var holidays = [[|holidays|]];
var saturday_charge = <?php echo EXTRA_CHARGE_ON_SATURDAY;?>;
var sunday_charge = <?php echo EXTRA_CHARGE_ON_SUNDAY;?>;
function display_room_table(obj,current_id)
{
	//if($('selected_room').style.display=="none")
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
var currency_arr = {};
<?php if(isset($_REQUEST['mi_reservation_room']))
{
	echo 'var mi_reservation_room_arr = '.String::array2js($_REQUEST['mi_reservation_room']).';';
	echo 'mi_init_rows(\'mi_reservation_room\',mi_reservation_room_arr);';
}
else
{
	echo 'mi_add_new_row(\'mi_reservation_room\',true);';
}
?><?php if(isset($_REQUEST['mi_traveller']))
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
	if($('expand_'+id)){
		if($('expand_'+id).innHTML=='')
		{
			$('expand_'+id).innHTML='+';
			jQuery('#mi_reservation_room_sample_'+id).hide();
			$('expand_img_'+id).src='packages/core/skins/default/images/buttons/node_close.gif';
		}
		else
		{
			$('expand_'+id).innHTML='';
			jQuery('#mi_reservation_room_sample_'+id).slideDown(1000);
			$('expand_img_'+id).src='packages/core/skins/default/images/buttons/node_open.gif';
		}
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
<?php if(isset($_REQUEST['mi_reservation_room']))
{
	foreach($_REQUEST['mi_reservation_room'] as $key=>$value){
		if(isset($value['currency_arr']) and $value['currency_arr']){
			echo 'updateCurrenciesValue('.String::array2js($value['currency_arr']).');';
		}	
		if(isset($value['service_arr']) and $value['service_arr']){
			echo 'updateServicesValue('.String::array2js($value['service_arr']).');';
		}	
	}
}
?>
function updateCurrenciesValue(arr){
	for(var i=101;i<=input_count;i++){
		for(var j in arr){
			if($('currency_'+j+'_'+i) && arr[j]['bill_id']==$('id_'+i).value){
				$('currency_'+j+'_'+i).value = arr[j]['amount'];
			}
			
		}
	}
}
function updateServicesValue(arr){
	for(var i=101;i<=input_count;i++){
		for(j in arr){
			if($('service_'+j+'_'+i) && arr[j]['reservation_room_id']==$('id_'+i).value){
				$('service_'+j+'_'+i).value = arr[j]['amount'];
			}
			
		}
	}
}
var roomCount = to_numeric($('count_number_of_room').innerHTML);
<!--IF:cond(Url::get('r_r_id'))-->
var r_r_id = <?php echo Url::get('r_r_id');?>;
<!--ELSE-->
var r_r_id = 0;
<!--/IF:cond-->
for(var i=101;i<=input_count;i++){
	if(jQuery("#departure_time_"+i)){
		jQuery("#departure_time_"+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	}
	updateStatusList(i);
	myAutocomplete(i);
	if(jQuery("#birth_date_"+i)){
		jQuery("#birth_date_"+i).mask("99/99/9999");
	}
	if($('expand_img_'+i)){
		if($('status_'+i).value!='CANCEL'){
			roomCount++;
		}
		$('expand_img_'+i).src='packages/core/skins/default/images/buttons/node_close.gif';
	}
	jQuery('#mi_reservation_room_sample_'+i).hide();
	// Loai bo nut chon phong khi da check in
	if($('status_'+i) && $('status_'+i).value != 'BOOKED'){
		if(jQuery('#select_room_'+i)){
			jQuery('#select_room_'+i).css({'height':'0px','width':'15px'});
		}
	}
	var datePickerForArrival = true;
<?php if(!User::can_admin(false,ANY_CATEGORY)){?>
	if($('status_'+i) && $('status_'+i).value != 'BOOKED'){// Loai bo nut chon gia khi da check in
		if(jQuery('#rate_list_'+i)){
			jQuery('#rate_list_'+i).css({'display':'none'});
		}
	}
	if($('status_'+i) && $('status_'+i).value == 'CHECKOUT'){
		jQuery('#reservation_room_bound_'+i+' :input').attr('disabled',true);
		jQuery('#view_invoice_'+i).attr('disabled',false);
	}
	if($('id_'+i) && $('id_'+i).value){
		if($('deposit_'+i) && to_numeric($('deposit_'+i).value)){
			jQuery('#deposit_'+i).attr('readonly',true);
			jQuery('#deposit_date_'+i).attr('disabled',true);
			jQuery('#deposit_type_'+i).attr('disabled',true);
			jQuery('#deposit_invoice_number_'+i).attr('readonly',true);
		}
		if($('status_'+i) && $('status_'+i).value == 'CHECKIN'){
			jQuery('#time_in_'+i).attr('readonly',true);
			$('time_in_'+i).className = 'readonly';
			jQuery('#arrival_time_'+i).attr('readonly',true);
			$('arrival_time_'+i).className = 'readonly';
			jQuery('#price_'+i).attr('readonly',true);
			$('price_'+i).className = 'readonly';
			datePickerForArrival=false	
		}
	}
	if($('status_'+i) && $('status_'+i).value == 'BOOKED' && $('customer_name').value){
		jQuery('#price_'+i).attr('readonly',true);
		$('price_'+i).className = 'readonly';
	}
<?php }else{?>
	if(jQuery("#deposit_date_"+i)){
		jQuery("#deposit_date_"+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	}
<?php }?>
	if($('id_'+i) && $('id_'+i).value){
		if($('id_'+i).value == r_r_id){
			jQuery('#mi_reservation_room_sample_'+i).show();
			if($('expand_'+i)){
				$('expand_'+i).innHTML='';
				$('expand_img_'+i).src='packages/core/skins/default/images/buttons/node_open.gif';
				$('expand_all_span').innHTML='';
				$('expand_all').src='packages/core/skins/default/images/buttons/node_open.gif';
			}
		}
	}
	if(datePickerForArrival==true){
		if(jQuery("#arrival_time_"+i)){
			jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
		}
	}
	if($('status_'+i) && ($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CANCEL')){
		$('payment_type_bound_'+i).style.display = 'none';
	}else{
		if($('payment_type_bound_'+i)){
			$('payment_type_bound_'+i).style.display = '';
		}
	}
	if($("def_code_"+i)){
		if($("def_code_"+i).value=='CASH' || $("def_code_"+i).value=='CREDIT_CARD'){
			jQuery("#related_rr_"+i).hide();
			//jQuery("#currencies_"+i).show();
		}else{
			//jQuery("#currencies_"+i).hide();
			jQuery("#related_rr_"+i).show();
		}
	}
	if(jQuery("#traveller_id__"+i)){
		jQuery("#arrival_date_"+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
		jQuery("#departure_date_"+i).datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	}
	jQuery("#time_in_"+i).mask("99:99");
	jQuery("#time_out_"+i).mask("99:99");
	buildRateList(i);
}
function buildRateList(index){
	if($('status_'+i) && $('customer_name').value){
		jQuery('#price_'+i).attr('readonly',true);
		$('price_'+i).className = 'readonly';
	}
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
$('count_number_of_room').innerHTML = roomCount;
var payment_type_data = <?php echo String::array2suggest([[=payment_types=]]);?>;
function myAutocomplete(index)
{
	if($("nationality_id_"+index)!=null){
		jQuery("#nationality_id_"+index).autocomplete({
			url:'r_get_countries.php',
			minChars: 0,
			width: 280,
			matchContains: true,
			autoFill: false,
			formatItem: function(row, i, max) {
				return ' <span> ' + row.name + '</span>';
			},
			formatMatch: function(row, i, max) {
				return row.name;
			},
			formatResult: function(row) {			
				return row.name;
			}
		});
	}
}
function updateUrl(index){
	var url = '';
	if($('room_invoice_'+index).checked == true){
		url += '&room_invoice=1';
	}
	if($('hk_invoice_'+index).checked == true){
		url += '&hk_invoice=1';
	}		
	if($('bar_invoice_'+index).checked == true){
		url += '&bar_invoice=1';
	}		
	if($('other_invoice_'+index).checked == true){
		url += '&other_invoice=1';
	}
	if($('phone_invoice_'+index).checked == true){
		url += '&phone_invoice=1';
	}
	if($('extra_service_invoice_'+index).checked == true){
		url += '&extra_service_invoice=1';
	}
	<!--IF:cond_massage(HAVE_MASSAGE)-->
	if($('massage_invoice_'+index).checked == true){
		url += '&massage_invoice=1';
	}
	<!--/IF:cond_massage-->
	<!--IF:cond_swimming(HAVE_SWIMMING)-->
	if($('swimming_pool_invoice_'+index).checked == true){
		url += '&swimming_pool_invoice=1';
	}
	<!--/IF:cond_swimming-->
	<!--IF:cond_tennis(HAVE_TENNIS)-->
	if($('tennis_invoice_'+index).checked == true){
		url += '&tennis_invoice=1';
	}
	<!--/IF:cond_tennis-->
	if($('included_deposit_'+index).checked == true){
		url += '&included_deposit=1';
	}
	if($('included_related_total_'+index).checked == true){
		url += '&included_related_total=1';
	}
	$('url_'+index).value = url;
}
function viewInvoice(index){
	updateUrl(index);
	if($('id_'+index).value!='')
	{
		var url = '?page=reservation';
		url += $('url_'+index).value;
		url += '&total_amount='+to_numeric($('total_amount_'+index).value);
		url += '&price='+to_numeric($('price_'+index).value)+'&deposit='+to_numeric($('deposit_'+index).value);
		url += '&reduce_balance='+to_numeric($('reduce_balance_'+index).value)+'&reduce_amount='+to_numeric($('reduce_amount_'+index).value);
		url += '&time_in='+$('time_in_'+index).value+'&time_out='+$('time_out_'+index).value+'&departure_time='+$('departure_time_'+index).value+'&arrival_time='+$('arrival_time_'+index).value;
		url += '&tax_rate='+$('tax_rate_'+index).value+'&service_rate='+$('service_rate_'+index).value;
		url += '&id='+$('id_'+index).value;
		url += '&cmd=invoice';
		if($('def_code_'+index).value=='CREDIT_CARD'){
			url += '&def_code=CREDIT_CARD';
		}
		window.open(url);
	}
}
</script>