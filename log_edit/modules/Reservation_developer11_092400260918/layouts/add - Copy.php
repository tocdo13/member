<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
var nationalities = <?php echo String::array2js([[=nationalities=]]);?>;
var vip_card_list = <?php echo String::array2js([[=vip_card_list=]]);?>;
</script>
<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<div id="input_group_#xxxx#"  onblur="updateRoomForTraveller('#xxxx#');" onmouseover="updateRoomForTraveller('#xxxx#');">
			<span class="multi-input" id="index_#xxxx#" style="width:39px;font-size:10px;color:#F90;"></span>
            <input  name="mi_reservation_room[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <input  name="mi_reservation_room[#xxxx#][house_status]" type="hidden" id="house_status_#xxxx#"/>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:70px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="text" id="room_level_id_#xxxx#" style="display:none;">
			</span>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:30px;font-weight:bold;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="Check_Availblity(#xxxx#,input_count);" style="cursor:pointer;">
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;">
				<input  name="mi_reservation_room[#xxxx#][room_name_old]" type="hidden" id="room_name_old_#xxxx#">
				<input  name="mi_reservation_room[#xxxx#][room_id_old]" type="hidden" id="room_id_old_#xxxx#">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF ><img src="packages/core/skins/default/images/buttons/adult.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/child.png" align="top"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:50px;" type="text" id="price_#xxxx#" class="change_price price" onkeyup="count_price('#xxxx#',true);" oninput="count_price('#xxxx#',true);" >
			</span>
			<span class="multi-input">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate"> 
            </span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][commission_rate]" style="width:30px;" type="text" id="commission_rate_#xxxx#" class="cms_rate price">
			</span>
			<span class="multi-input">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="commission_list_#xxxx#" class="select-rate"> 
            </span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:50px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][early_checkin]" type="checkbox" id="early_checkin_#xxxx#" title="[[.show_in_early_check_in_report.]]">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_out]" style="width:50px;" type="text" id="time_out_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:70px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',false);" class="date-select">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:70px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',false);updateRoomForTraveller('#xxxx#');" class="date-select">
					<input  name="mi_reservation_room[#xxxx#][departure_time_old]" type="hidden" id="departure_time_old_#xxxx#">
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][early_checkin]" id="early_checkin_#xxxx#" class="early_checkin" title="[[.early_checkin.]]" style="width:43px;">[[|verify_dayuse_options|]]</select>
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][late_checkout]" id="late_checkout_#xxxx#" class="late_checkout" title="[[.late_checkout.]]" style="width:43px;">[[|verify_dayuse_options|]]</select>
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
						    checkDirty(#xxxx#);
							if(!$('room_id_#xxxx#').value){
								$('status_#xxxx#').value = 'BOOKED';
								alert('Miss room number');
								return false;
							}
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
						<option value="CHECKIN" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check in</option>
						<option value="CHECKOUT" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check out</optio
						>   <option value="CANCEL">Cancel</option>
					</select>
                    <input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#"/>
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" style="width:85px;font-size:11px;" type="text" id="reservation_type_id_#xxxx#">[[|reservation_type_options|]]</select>
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][confirm]" type="checkbox" id="confirm_#xxxx#"/>
			</span>
            <span class="multi-input" style="float:right;padding-right:5px;">
					<span style="display:none;" id="expand_#xxxx#"></span>
                    <img id="expand_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShorten('#xxxx#');">
			</span>
			<span class="multi-input" style="width:30px;text-align:center;float:right;" id="delete_reservation_room_#xxxx#">
				<!--IF:right_cond(User::can_delete(false,ANY_CATEGORY))-->
                    <img align="left" src="packages/core/skins/default/images/buttons/delete.gif" 
                        onClick="
                            var roomCount = to_numeric($('count_number_of_room').innerHTML);
                            if(roomCount-1 > 0)
                            {
                                if(confirm('[[.are_you_sure.]]'))
                                {
                                    $('count_number_of_room').innerHTML=roomCount-1;
                                    mi_delete_row($('input_group_#xxxx#'),'mi_reservation_room','#xxxx#','');
                                    event.returnValue=false;
                                }
                            }
                            else
                            {
                                alert('Can\'t delete reservation room! Number reservation room must > 0!');
                                event.returnValue=false;
                            }
                        " style="cursor:pointer;">
                <!--/IF:right_cond-->
			</span>
			<br clear="all">
			<span id="mi_reservation_room_sample_#xxxx#" style="display:none;">
                <div class="room-extra-info" id="room_extra_info_#xxxx#" >
                    <div>
                        <span class="multi-input-extra">
                            <span class="label">[[.foc_room_charge.]]</span>
							<input  name="mi_reservation_room[#xxxx#][foc]" type="text" id="foc_#xxxx#" style="width:81px;"><span class="label">[[.foc_all.]]</span><input  name="mi_reservation_room[#xxxx#][foc_all]" style="width:15px;" value="1" type="checkbox" id="foc_all_#xxxx#" align="left" title="[[.foc_all_services.]]">
                        </span>
                        <span class="multi-input-extra">
                            <span class="label">[[.discount_room_charge.]] (% / <?php echo HOTEL_CURRENCY;?>)</span>
                             <input  name="mi_reservation_room[#xxxx#][discount_after_tax]" style="width:15px;display:none;" value="1" type="checkbox" id="discount_after_tax_#xxxx#" align="left" title="[[.discount_percent_after_tax.]]">
                            <input  name="mi_reservation_room[#xxxx#][reduce_balance]" type="text" id="reduce_balance_#xxxx#" style="width:20px;" maxlength="3" onchange="count_price('#xxxx#',false);" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][reduce_amount]" type="text" id="reduce_amount_#xxxx#" style="width:68px;"  class="input_number" onkeyup="this.value = number_format(this.value);">
                        </span>
                       <span class="multi-input-extra">
                            <span class="label">[[.tax_rate.]] / [[.service_rate.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][tax_rate]" maxlength="3" type="text" id="tax_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" value="<?php echo RECEPTION_TAX_RATE;?>"  <?php if(!User::can_delete($this->get_module_id('CheckIn'),ANY_CATEGORY) or !User::can_admin()){ echo 'readonly="readonly"'; echo 'style="width:35px;background:#FCFCFC;"';}else{  echo 'style="width:35px;"';}?> onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][service_rate]" maxlength="3" type="text" id="service_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);"  value="<?php echo RECEPTION_SERVICE_CHARGE;?>" <?php if(CAN_EDIT_CHARGE==0){ echo 'readonly="readonly"'; echo 'style="width:35px;background:#FCFCFC;"';}else{  echo 'style="width:35px;"';}?> onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                            <span class="label">[[.net_price.]]</span><input  name="mi_reservation_room[#xxxx#][net_price]" style="width:15px;" value="1" type="checkbox" id="net_price_#xxxx#" align="left" title="[[.net_price.]]" <?php if(NET_PRICE==1){ echo 'checked';}?>>
                        </span>
						<span class="multi-input-extra" style="display:none;">
                            <span class="label">[[.deposit_amount.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][deposit]" type="text" id="deposit_#xxxx#" onchange="if(this.value){$('deposit_date_#xxxx#').value='<?php echo date('d/m/Y');?>';}else{$('deposit_date_#xxxx#').value='';} count_price('#xxxx#',false);"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                        </span>
						<span class="multi-input-extra" style="display:none;">
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
                        <!---<span class="multi-input-extra">
                        		<span class="label">[[.Remarks.]]</span>
                                <input  name="mi_reservation_room[#xxxx#][note]" type="text" id="note_#xxxx#">
                        </span>--->
						 <span class="multi-input-extra" style="display:none;">
                            <span class="label">[[.deposit_type.]]</span>
							<select  name="mi_reservation_room[#xxxx#][deposit_type]" id="deposit_type_#xxxx#"  style="width:110px;float:left;">
								<option value="CASH">[[.cash.]]</option>
								<option value="CREDIT_CARD">[[.credit.]]</option>
							</select>
                        </span>
						<span class="multi-input-extra" style="display:none;">
                            <span class="label">[[.deposit_date.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][deposit_date]" type="text" id="deposit_date_#xxxx#" readonly="readonly">
                        </span>
                    </div>
                    <div id="extra_bed" style="padding:5px 0px 5px 0px;clear:both; display:none;">
                    	<fieldset style="margin-left:20px;">
                        <legend class="title">[[.Extra_room_charge.]]</legend>
	                    <span class="multi-input-extra">
                           <span class="label">[[.Early_checkin.]]:</span>
                           <select  name="mi_reservation_room[#xxxx#][early_checkin]" id="early_checkin_#xxxx#" title="[[.early_checkin.]]" style="width:60px;" onchange="update_verify_dayuse(this.value,#xxxx#,'early_checkin');">[[|verify_dayuse_options|]]</select>
                           <span class="label">[[.early_checkin_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][early_checkin_date]" type="text" id="early_checkin_date_#xxxx#" class="extra_bed_from_date" />
                           <span class="label">[[.early_checkin_rate.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][early_checkin_amount]" type="text" id="early_checkin_amount_#xxxx#"/>
                        </span>
                        <br clear="all" />
		                <span class="multi-input-extra">
                           <span class="label">[[.late_checkout.]]:</span>
                           <select  name="mi_reservation_room[#xxxx#][late_checkout]" id="late_checkout_#xxxx#" title="[[.late_checkout.]]" style="width:60px;" onchange="update_verify_dayuse(this.value,#xxxx#,'late_checkout');">[[|verify_dayuse_options|]]</select>
                           <span class="label">[[.late_checkout_price.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][late_checkout_date]" type="text" id="late_checkout_date_#xxxx#" />
                           <span class="label">[[.late_checkout_rate.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][late_checkout_amount]" type="text" id="late_checkout_amount_#xxxx#"/>
                        </span>
                        </fieldset>
                    </div>
                    <div id="extra_bed" style="padding:5px 0px 5px 0px;clear:both">
                    	<fieldset style="margin-left:20px;">
                        <legend class="title">[[.Extra_bed_baby_cot.]]</legend>
	                    <span class="multi-input-extra">
                           <span class="label">[[.extra_bed.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed]" type="checkbox" id="extra_bed_#xxxx#" style="width:15px;" value="1" />
                           <span class="label">[[.extra_bed_from_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_from_date]" type="text" id="extra_bed_from_date_#xxxx#" class="extra_bed_from_date" />
                           <span class="label">[[.extra_bed_to_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_to_date]" type="text" id="extra_bed_to_date_#xxxx#" class="extra_bed_to_date" />
                           <span class="label">[[.extra_bed_rate.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_rate]" type="text" id="extra_bed_rate_#xxxx#" class="extra_bed_rate" style="text-align:right"/>
                        </span>
                        <br clear="all" />
		                <span class="multi-input-extra">
                           <span class="label">[[.baby_cot.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][baby_cot]" type="checkbox" id="baby_cot_#xxxx#" style="width:15px;" value="1" />
                           <span class="label">[[.baby_cot_from_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][baby_cot_from_date]" type="text" id="baby_cot_from_date_#xxxx#" class="baby_cot_from_date" />
                           <span class="label">[[.baby_cot_to_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][baby_cot_to_date]" type="text" id="baby_cot_to_date_#xxxx#" class="baby_cot_to_date" />
                           <span class="label">[[.baby_cot_rate.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][baby_cot_rate]" type="text" id="baby_cot_rate_#xxxx#" class="baby_cot_rate" style="text-align:right" />
                        </span>
                        </fieldset>
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
		</div>
	</span>
</span>
<div id="mask" class="mask">[[.Please wait.]]...</div>
<form name="AddReservationForm" method="post" onsubmit="if(!checkRepair(true)) return false;">
<div style="text-align:center">
<div style="width:970px;margin-right:auto;margin-left:auto;">
<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;" align="center">
	<tr valign="top">
		<td align="left">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr height="40">
					<td width="90%" nowrap  class="form-title">[[.make_reservation.]]</td>
				  	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="save" type="submit" readonly="true" value="[[.Save.]]" class="button-medium-save" onclick="if(checkRepair(false)){jQuery('#mask').show();}"></td><?php }?><!--AddReservationForm.submit();-->
					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="update" type="submit" readonly="true" value="[[.Save_and_stay.]]" class="button-medium-save stay" onclick="if(checkRepair(false)) jQuery('#mask').show();"></td><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a></td><?php }?>
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
                     <textarea name="note" id="note" style="width:99%;height:80px;"></textarea>
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
                    <td align="right">[[.tour.]] / [[.group.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="tour_name" type="text" id="tour_name" style="width:215px;" readonly="" class="readonly">
                      <input name="tour_id" type="text" id="tour_id" class="hidden" />
                      <a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"></td>
                    <td align="right" width="10%" class="label">&nbsp;</td>
                    </tr><tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.customer.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="customer_name" type="text" id="customer_name" style="width:215px;"  readonly="" class="readonly">
                      <input name="customer_id" type="text" id="customer_id" class="hidden" />
                      <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> 
                        <img src="skins/default/images/cmd_Tim.gif" />
                      </a> 
                      <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;jQuery('.price').attr('readonly',false);jQuery('.price').attr('class','price');" style="cursor:pointer;"/>
                    </td>
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
					<span class="multi-input-header" style="width:50px;">[[.room_id.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.child.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.room_rate.]]</span>
					<span class="multi-input-header"  style="width:12px;background-image: url(packages/core/skins/default/images/buttons/rate.jpg)"></span>
                    <span class="multi-input-header" style="width:30px;">[[.cms.]]</span>
                    <span class="multi-input-header"  style="width:12px; background-image: url(packages/core/skins/default/images/buttons/rose.jpg);"></span>
					<span class="multi-input-header" style="width:50px;">[[.time_in.]]</span>
					<span class="multi-input-header" style="width:16px;">
					<input name="all_early_checkin" type="checkbox" id="all_early_checkin" onclick="check_early_checkin();" /></span>
					<span class="multi-input-header" style="width:50px;">[[.time_out.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.arrival_time.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.departure_time.]]</span>
                    <span class="multi-input-header" style="width:40px;" title="[[.early_checkin.]]">E.I</span>
                    <span class="multi-input-header" style="width:40px;" title="[[.late_checkout.]]">L.O</span>
					<span class="multi-input-header" style="width:75px;">[[.status.]]</span>
					<span class="multi-input-header" style="width:80px;">[[.reservation_type.]]</span>
					<span class="multi-input-header" style="width:20px;">[[.confirm.]]</span>
					<span class="multi-input-header" style="float:right;border:0px;background:none;">&nbsp;</span>
					<span id="expand_all_span" style="float:right;"><img id="expand_all" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandAll();"></span>
					<br clear="all" />
				</span>
			</span>
			<br clear="all">
			<input class="button-medium-add" type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');updateInput(input_count);AddInput(input_count);buildRateList(input_count);buildCommissionList(input_count)">
            <br clear="all">
            <br clear="all">
            <b style="color:#FF6633;">[[.change_for_group.]]<br/></b>
           	[[.cut_off_day.]] <input name="cut_of_date" type="text" id="cut_of_date" style="width:65px;" class="date-input"> |
            [[.change_all_status_to.]]<select  name="change_all_status" id="change_all_status" onchange="changeAllStatus(this.value,input_count);"><option value="">[[.select_status.]]</option><option value="CHECKIN">CHECKIN</option><option value="CHECKOUT">CHECKOUT</option></select> |
			[[.change_all_arrival_time.]] <input type="text" id="all_arrival_time" class="date-input" onchange="changeAllTime(this.value,'AT')">
			|[[.change_all_departure_time.]] <input type="text" id="all_departure_time" class="date-input" onchange="changeAllTime(this.value,'DT')">
			|[[.change_all.]] [[.reservation_type.]] <select  name="change_all_reservation_type" id="change_all_reservation_type" onchange="changeAllReservationType(this.value);">[[|reservation_type_options|]]</select>
             | [[.change_all.]] E.I <select name="all_early_checkin" id="all_early_checkin" onChange="jQuery('.early_checkin').val(this.value);">[[|verify_dayuse_options|]]</select>| [[.change_all.]] L.O <select name="all_late_checkout" id="all_late_checkout" onChange="jQuery('.late_checkout').val(this.value);">[[|verify_dayuse_options|]]</select>|
            |[[.change_price.]] <input type="text" id="change_all_price" class="input_number" onKeyUp="changePriceFunc();" style="width:60px; text-align:right;">
            |[[.net_price.]] <input name="change_all_net_price" type="checkbox" id="change_all_net_price" onclick="jQuery('.net_price').attr('checked',jQuery(this).attr('checked'));" />
            |[[.discount_for_group.]](%) <input type="text" id="discount_for_group" class="input_number" onkeyup="changeAllDiscount(this.value);" style="width:30px;"/>
            |[[.commission.]](%)
		</fieldset>
		</td></tr>
		<tr><td>
	</td>
	</tr>
	</table>
	</td></tr>
</table><p>&nbsp;</p>
</div>
</div>
<div id="selected_room" onmouseover="$('selected_room').style.display='';" onmouseout="$('selected_room').style.display='none';" style="display:none;float:left;position:absolute;top:0px;left:0px;width:180px;background-color:#FFCC00;border:2px solid #0099CC;vertical-align:top;">
    <div id="rooms" style="width:99%;background-color:#FFFFFF;">
        <div style="height:20px;background-color:#FF9900;border:1px solid #CCCCCC;text-align:center;margin:2px;color:#FFFFFF;font-weight:bold;text-transform:uppercase;padding:2px 0px 2px 0px;">
            [[.rooms.]]
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
<div id="commission_list" class="room-rate-list" style="display:none;">
    <div>
        [[.commission_list.]]&nbsp;&nbsp;
        <a onclick="$('commission_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]"></a>
    </div>
    <ul id="commission_list_result">
    </ul>
</div>
</form>
<script>
var d = '<?php echo date('d/m/Y')?>';
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
var readOnly = 'readonly="readonly"';
jQuery("#all_arrival_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#cut_of_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#all_departure_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#rate_list").mouseover(function()
{
	jQuery(this).show();
});
jQuery("#rate_list").mouseout(function(){
	jQuery(this).hide();
});
jQuery("#commission_list").mouseover(function(){
	jQuery('#commission_list').show();
});
jQuery("#commission_list").mouseout(function()
{
	jQuery('#commission_list').hide();
});

jQuery('#change_all_price').ForceNumericOnly().FormatNumber();
var currentHour = '<?php echo date('H:i');?>';
var currentDate = '<?php echo date('d/m/Y');?>';
var ttDate = '<?php echo date('d/m/Y',time()+86400);?>';
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
function updateInput(input_count){
	jQuery('#price_'+input_count).ForceNumericOnly().FormatNumber();
	jQuery('.date-select').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	jQuery('#adult_'+input_count).val('1');
    jQuery('#arrival_time_'+input_count).val(<?php echo date('d/m/Y');?>);
    jQuery('#departure_time_'+input_count).val(<?php echo date('d/m/Y',time()+86400);?>);
	if(CAN_CHANGE_PRICE || CAN_ADMIN){
		jQuery('#price_'+input_count).attr('readonly',false);
		jQuery('#price_'+input_count).removeClass('readonly');
	}else{
		<?php //if(!User::can_admin(false,ANY_CATEGORY)){?>
		jQuery('#price_'+input_count).attr('readonly',true);
		jQuery('#price_'+input_count).addClass('readonly');
		<?php //}?>
	}
}
function AddInput(input_count){
	jQuery('#arrival_time_'+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
	jQuery('#departure_time_'+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
	jQuery('#time_in_'+input_count).mask('99:99');jQuery('#time_out_'+input_count).mask('99:99');
	jQuery('#net_price_'+input_count).attr('checked',<?php if(NET_PRICE==1){echo 'true';}else{ echo 'false';}?>);
	jQuery('#tax_rate_'+input_count).val(<?php echo RECEPTION_TAX_RATE;?>);
	jQuery('#service_rate_'+input_count).val(<?php echo RECEPTION_SERVICE_CHARGE;?>);
	jQuery('#reservation_type_id_'+input_count).val(1);
}
var roomCount = to_numeric($('count_number_of_room').innerHTML);
for(var i=101;i<=input_count;i++){
	if(jQuery("#birth_date_"+i)){
		jQuery("#birth_date_"+i).mask("99/99/9999");
	}
	//updateInput(i);
	if(jQuery("#arrival_time_"+i)){
		roomCount++;
		jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	}
	if(jQuery("#departure_time_"+i)){
		jQuery("#departure_time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	}
    if(jQuery('#arrival_time_'+i).val() && jQuery('#departure_time_'+i).val())
	{
		checkin_date = jQuery('#arrival_time_'+i).val();
		var min_date = checkin_date.split("/");
		checkout_date = jQuery('#departure_time_'+i).val();
		var max_date = checkout_date.split("/");
		if(jQuery("#extra_bed_from_date_"+i)){
			jQuery("#extra_bed_from_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
		}
		if(jQuery("#baby_cot_from_date_"+i)){
			jQuery("#baby_cot_from_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
		}
		jQuery("#departure_time_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4' });
		if(jQuery("#extra_bed_to_date_"+i)){
			jQuery("#extra_bed_to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
		}
		if(jQuery("#baby_cot_to_date_"+i)){
			jQuery("#baby_cot_to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
		}
    }
        room_level = {};
	<?php if(Url::get('room_prices')){?>
		room_level = <?php echo String::array2js(Url::get('room_prices'));?>;
		for(var k in room_level){
			if(jQuery("#room_level_id_"+i).val()==k){
				jQuery("#price_"+i).val(number_format(room_level[k]));
			}
		}
	<?php }?>
	<?php if(User::can_admin(false,ANY_CATEGORY)){?>
	if(jQuery("#deposit_date_"+i)){
		jQuery("#deposit_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	}
	<?php }else{?>
	<?php }?>
	buildRateList(i);
    buildCommissionList(i);
	if(jQuery("#traveller_id_"+i)){
		jQuery("#arrival_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
		jQuery("#departure_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4'});
	}
	jQuery("#time_in_"+i).mask("99:99");
	jQuery("#time_out_"+i).mask("99:99");
    count_price(i,true);
	<?php if(Url::get('status')=='CHECKIN'){?>
	//count_price(i,true);
	<?php }?>
}
function buildRateList(index)
{
	if(jQuery('#rate_list_'+index) && jQuery('#room_level_id_'+index)){
		jQuery('#rate_list_'+index).click(function()
        {
			var i = this.id.substr(10);
			var customerId = jQuery('#customer_id').val();
			var roomLevelId = jQuery("#room_level_id_"+i).val();
			var adult = jQuery("#adult_"+i).val();
			var child = jQuery("#child_"+i).val();
			var startDate = jQuery("#arrival_time_"+i).val();
			var endDate = jQuery("#departure_time_"+i).val();
			getRateList(jQuery(this).attr('id'),roomLevelId,i,customerId,adult,child,startDate,endDate);
		});
	}
}
function buildCommissionList(index)
{
    if(jQuery('#commission_list_'+index))
        {
    		jQuery('#commission_list_'+index).click(function(){
    			var i = this.id.substr(10);
                var customerId = jQuery('#customer_id').val();
    			var startDate = jQuery("#arrival_time_"+index).val();
    			var endDate = jQuery("#departure_time_"+index).val();
    			getCommissionList(jQuery(this).attr('id'),index,customerId,startDate,endDate);
    		});
    	}
}
$('count_number_of_room').innerHTML = roomCount-1;
function myAutocomplete()
{
	jQuery("#nationality_id_"+input_count).autocomplete({
		url:'r_get_countries.php',
        	onItemSelect: function(item) {
			updateNationality(input_count);
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
function init_traveller_action(traveller_input_count)
{
	myAutocomplete(traveller_input_count);
	get_traveler_sugges(traveller_input_count);
	jQuery('#birth_date_'+(traveller_input_count)).datepicker();
	jQuery('#birth_date_'+(traveller_input_count)).mask('99/99/9999');
	if(jQuery('#expire_date_of_visa_'+traveller_input_count)){
		jQuery("#expire_date_of_visa_"+traveller_input_count).datepicker();
	}
	if(jQuery('#arrival_hour_'+traveller_input_count)){
		jQuery("#arrival_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#departure_hour_'+traveller_input_count)){
		jQuery("#departure_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#traveller_arrival_date_'+traveller_input_count)){
		jQuery("#traveller_arrival_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#traveller_departure_date_'+traveller_input_count)){
		jQuery("#traveller_departure_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#flight_arrival_hour_'+traveller_input_count)){
		jQuery("#flight_arrival_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#flight_arrival_date_'+traveller_input_count)){
		jQuery("#flight_arrival_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#flight_departure_hour_'+traveller_input_count)){
		jQuery("#flight_departure_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#flight_departure_date_'+traveller_input_count)){
		jQuery("#flight_departure_date_"+traveller_input_count).datepicker();
	}
}
function checkDirty(index)
{
    var id = $('id_'+index).value;
    //if(mi_reservation_room_arr[id] && mi_reservation_room_arr[id]['house_status'] == 'dirty' && $('status_'+index).value!='BOOKED')
    if($('house_status_'+index).value == 'dirty')
    {
        alert('Phòng: '+$('room_name_'+index).value+' đang DIRTY');
        $('status_'+index).value = 'BOOKED';
        return false;   
    }
}

function changePriceFunc()
{
        jQuery('.change_price').val(number_format(to_numeric(jQuery('#change_all_price').val()))); 
        /*
        var change_class = '.change_price_<?php echo date('d_m_Y');?>';
        jQuery(change_class.toString()).val(number_format(to_numeric(jQuery('#change_all_price').val())));
        */
        for(var i=101;i<=input_count;i++){
            count_price(i,true);
        }
}

function checkRepair(showAlert)
{
    for(var index = 101; index <= input_count; index++)
    {
        var id = $('id_'+index).value;
        //if(mi_reservation_room_arr[id] && mi_reservation_room_arr[id]['house_status'] == 'dirty' && $('status_'+index).value!='BOOKED')
        if($('house_status_'+index).value == 'repair')
        {
            if(showAlert)
                alert('Phòng: '+$('room_name_'+index).value+' đang REPAIR');
            return false;   
        }
    }
    
    return true;
}
function get_traveler_sugges(traveller_input_count){
	jQuery("#passport_"+traveller_input_count).autocomplete({
		url:'get_traveller.php',
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
		},
		onItemSelect: function(item) {
			get_traveller(traveller_input_count);
		}
	});
}
function change_all_traveller_level()
{
	for(var i=101;i<=input_count;i++){
		if(jQuery("#traveller_id_"+i)){
			jQuery('#traveller_level_id_'+i).val(jQuery('#change_all_traveller_id').val());
		}
	}
}
function check_early_checkin(){
	for(var i=101;i<=input_count;i++){
		if(jQuery("#early_checkin_"+i) && jQuery('#early_checkin_'+i).attr('checked')==true){
			jQuery('#early_checkin_'+i).attr('checked',false);
		}else{
			jQuery('#early_checkin_'+i).attr('checked',true);
		}
	}
}
function update_verify_dayuse(quantity,index,name){
    if(quantity>0){
        jQuery('#'+name+'_amount_'+index).val(to_numeric($('price_'+index).value) * to_numeric(quantity) * 0.1);
        if($('arrival_time_'+index) && name=='early_checkin'){
            jQuery('#'+name+'_date_'+index).val($('arrival_time_'+index).value);
        }
		if($('departure_time_'+index) && name=='late_checkout'){
            jQuery('#'+name+'_date_'+index).val($('departure_time_'+index).value);
        }
    }
}
function go(event)
{
    alert(event.keyCode);
}
function not_booking(index)
{
    if(($('status_'+index).value == 'BOOKED')  && ($('arrival_time_'+index).value < d))
    {
        alert('Ngay` đến phải lớn hơn hoặc bằng này hiện tại...!');
        $('arrival_time_'+index).value = d;
    }
}
</script>
<!-- [[. time_in_is_more_than_current_time.]] -->