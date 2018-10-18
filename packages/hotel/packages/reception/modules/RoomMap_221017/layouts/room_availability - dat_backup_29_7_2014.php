<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
var nationalities = <?php echo String::array2js([[=nationalities=]])?>;
var vip_card_list = <?php echo String::array2js([[=vip_card_list=]]);?>;
</script>
<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<div id="input_group_#xxxx#"  onblur="updateRoomForTraveller('#xxxx#');" onmouseover="updateRoomForTraveller('#xxxx#');">
			<span class="multi-input" id="index_#xxxx#" style="width:39px;font-size:10px;color:#F90;"></span>
            <input  name="mi_reservation_room[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:70px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="text" id="room_level_id_#xxxx#" style="display:none;">
			</span>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:47px;font-weight:bold;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="if($('room_id_#xxxx#') && ($('room_id_#xxxx#').value=='') && $('room_name_#xxxx#') && ($('room_name_#xxxx#').value!='')){window.open('?page=room_map&cmd=select&act=without_room&object_id=#xxxx#&input_count='+input_count,'select_room');return false;}else{window.open('?page=room_map&cmd=select&object_id=#xxxx#&input_count='+input_count,'select_room');return false;}" style="cursor:pointer;">
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;">
				<input  name="mi_reservation_room[#xxxx#][room_name_old]" type="hidden" id="room_name_old_#xxxx#">
				<input  name="mi_reservation_room[#xxxx#][room_id_old]" type="hidden" id="room_id_old_#xxxx#">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/adult.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/child.png" align="top"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:50px;" type="text" id="price_#xxxx#" onkeyup="count_price('#xxxx#',true);" class="price">
			</span>
			<span class="multi-input">
            <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate"> </span>
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
						><option value="CANCEL">Cancel</option>
					</select>
                    <input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#">
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" style="width:85px;font-size:11px;" type="text" id="reservation_type_id_#xxxx#">[[|reservation_type_options|]]</select>
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
                <div class="room-extra-info" id="room_extra_info_#xxxx#" >
                    <div>
                        <span class="multi-input-extra" style="display:none;">
                            <span class="label">[[.foc.]]</span>
							<input  name="mi_reservation_room[#xxxx#][foc]" type="text" id="foc_#xxxx#" style="width:81px;"><input  name="mi_reservation_room[#xxxx#][foc_all]" style="width:15px;" value="1" type="checkbox" id="foc_all_#xxxx#" align="left" title="[[.foc_all_services.]]">
                        </span>
                        <span class="multi-input-extra">
                            <span class="label">[[.discount.]] (% / <?php echo HOTEL_CURRENCY;?>)</span>
                             <input  name="mi_reservation_room[#xxxx#][discount_after_tax]" style="width:15px;display:none;" value="1" type="checkbox" id="discount_after_tax_#xxxx#" align="left" title="[[.discount_percent_after_tax.]]">
                            <input  name="mi_reservation_room[#xxxx#][reduce_balance]" type="text" id="reduce_balance_#xxxx#" style="width:20px;" maxlength="3" onchange="count_price('#xxxx#',false);" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][reduce_amount]" type="text" id="reduce_amount_#xxxx#" style="width:68px;"  class="input_number" onkeyup="this.value = number_format(this.value);">
                        </span>
                        <span class="multi-input-extra">
                            <span class="label">[[.tax_rate.]] / [[.service_rate.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][tax_rate]" style="width:35px; background:#FCFCFC;" maxlength="3" type="text" id="tax_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" value="10" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][service_rate]" style="width:30px;background:#FCFCFC;" maxlength="3" type="text" id="service_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);"  value="5" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                            <input  name="mi_reservation_room[#xxxx#][net_price]" style="width:15px;" value="1" type="checkbox" id="net_price_#xxxx#" align="left" title="[[.net_price.]]">
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
                        <span class="multi-input-extra" style="display:none;">
                        		<span class="label">[[.Remarks.]]</span>
                                <input  name="mi_reservation_room[#xxxx#][note]" type="text" id="note_#xxxx#">
                        </span>
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
<form name="AddReservationForm" method="post">
<div style="text-align:center">
<div style="width:970px;margin-right:auto;margin-left:auto;">
<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;" align="center">
	<tr valign="top">
		<td align="left">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr height="40">
					<td width="90%" nowrap  class="form-title">[[.make_reservation.]]</td>
				  	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save" onclick="AddReservationForm.submit();jQuery('#mask').show();"></td><?php }?>
					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="update" type="submit" value="[[.Save_and_stay.]]" class="button-medium-save stay" onclick="jQuery('#mask').show();"></td><?php }?>
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
                      <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;jQuery('.price').attr('readonly',false);jQuery('.price').attr('class','price');" style="cursor:pointer;"></td>
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
					<span class="multi-input-header" style="width:50px;">[[.price.]]</span>
					<span class="multi-input-header"  style="width:12px;"></span>
					<span class="multi-input-header" style="width:50px;">[[.time_in.]]</span>
					<span class="multi-input-header" style="width:16px;"><input name="all_early_checkin" type="checkbox" id="all_early_checkin" onclick="check_early_checkin();" /></span>
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
			<input class="button-medium-add" type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');updateInput(input_count);buildRateList(input_count);">
           	[[.cut_off_day.]] <input name="cut_of_date" type="text" id="cut_of_date" style="width:65px;" class="date-input"> |
            [[.change_all_status_to.]]<select  name="change_all_status" id="change_all_status" onchange="changeAllStatus(this.value,input_count);"><option value="">[[.select_status.]]</option><option value="CHECKIN">CHECKIN</option><option value="CHECKOUT">CHECKOUT</option></select> |
			[[.change_all_arrival_time.]] <input type="text" id="all_arrival_time" class="date-input" onchange="changeAllTime(this.value,'AT')">
			[[.change_all_departure_time.]] <input type="text" id="all_departure_time" class="date-input" onchange="changeAllTime(this.value,'DT')">
			[[.change_all.]] [[.reservation_type.]] <select  name="change_all_reservation_type" id="change_all_reservation_type" onchange="changeAllReservationType(this.value);">[[|reservation_type_options|]]</select>
             | [[.change_all.]] E.I <select name="all_early_checkin" id="all_early_checkin" onChange="jQuery('.early_checkin').val(this.value);">[[|verify_dayuse_options|]]</select>| [[.change_all.]] L.O <select name="all_late_checkout" id="all_late_checkout" onChange="jQuery('.late_checkout').val(this.value);">[[|verify_dayuse_options|]]</select>|
            [[.net_price.]] <input name="change_all_net_price" type="checkbox" id="change_all_net_price" onclick="jQuery('.net_price').attr('checked',jQuery(this).attr('checked'));" />
                         |[[.discount_for_group.]](%) <input type="text" id="discount_for_group" class="input_number" onkeyup="changeAllDiscount(this.value);" style="width:30px;">
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
</form>
<script>
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
var readOnly = 'readonly="readonly"';
jQuery("#all_arrival_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#cut_of_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#all_departure_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#rate_list").mouseover(function(){
	jQuery(this).show();
});
jQuery("#rate_list").mouseout(function(){
	jQuery(this).hide();
});
var currentHour = '<?php echo date('H:i');?>';
var currentDate = '<?php echo date('d/m/Y');?>';
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
	jQuery('#time_in_'+input_count).mask('99:99');jQuery('#time_out_'+input_count).mask('99:99');
}
var roomCount = to_numeric($('count_number_of_room').innerHTML);
for(var i=101;i<=input_count;i++){
	if(jQuery("#birth_date_"+i)){
		jQuery("#birth_date_"+i).mask("99/99/9999");
	}
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
	if(jQuery("#traveller_id_"+i)){
		jQuery("#arrival_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
		jQuery("#departure_date_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4'});
	}
	jQuery("#time_in_"+i).mask("99:99");
	jQuery("#time_out_"+i).mask("99:99");
	<?php if(Url::get('status')=='CHECKIN'){?>
	count_price(i,true);
	<?php }?>
}
function buildRateList(index){
	if(jQuery('#rate_list_'+index) && jQuery('#room_level_id_'+index)){
		jQuery('#rate_list_'+index).click(function(){
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
</script>
<style>
	.checkin-today:hover{
		text-decoration:underline;
		cursor:pointer;	
	}
</style>
<script type="text/javascript">
	room_levels = <?php echo String::array2js([[=room_levels=]]);?>;
 </script>
<form method="post" name="HotelRoomAvailabilityForm">
<div id="room_map">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="room-map-table-bound">
<tr>
<td class="calendar-bound">
	<table width="100%" cellspacing="0" cellpadding="3">
	<tr valign="top">
	<td width="1%" align="left">
		<div id="room_map_left_utils" style="width:200px;">
			<fieldset><legend class="title"><b>[[.view_date.]]</b></legend>
			  <input name="from_date" type="text" id="from_date" class="date-input" onChange="HotelRoomAvailabilityForm.submit()">
			  <table border="0" id="check_availability_table">
            <tr>
            	<td width="50">[[.from.]]</td>
            	<td><input  name="from_time" style=" display:none;width:30px; font-size:10px;" type="text" id="from_time" title="00:00" maxlength="5"></td>
            </tr>
             <tr>
            	<td width="50">[[.to.]]</td>
            	<td><input  name="to_time" style=" display:none;width:30px; font-size:10px;" type="text" id="to_time" title="00:00" maxlength="5">  <input name="to_date" type="text" id="to_date" class="date-input" onChange="HotelRoomAvailabilityForm.submit()"></td>
            </tr>
             <tr>
            	<td colspan="2"><input name="search_date" type="button" value="[[.search.]]" style="width:80px; margin:auto;" onclick="HotelRoomAvailabilityForm.submit();"></td>
            </tr>
		  </table>
		  </fieldset><br />
<fieldset style="border:1px solid #9DC9FF">
			<legend>[[.forcecast.]]</legend>
				<table border="0" cellpadding="2">
				  	<tr class="checkin-today" onClick="window.open('?page=arrival_list');">
						<td align="left"><?php echo ([[=total_checkin_today_room=]] + [[=total_books_without_room=]]);?> [[.check_in_today.]]</td>
					</tr>
                    <tr class="checkin-today" onClick="window.open('?page=arrival_list');">
						<td align="left"> <?php echo ([[=total_dayuse_today=]]);?> ([[.total_dayused.]])</td>
					</tr>
				 	<tr class="checkin-today" onClick="window.open('?page=departure_list');">
						<td align="left">[[|total_checkout_today_room|]] [[.check_out_today.]]</td>
					</tr>
				 	<tr class="checkin-today" onClick="window.open('?page=room_status_report');">
						<td align="left"><?php echo([[=total_occupied_today=]]+ [[=total_books_without_room=]] + [[=total_checkin_today_room=]]);?> [[.total_occ_and_arr.]]</td>
					</tr>
			   </table>
			</fieldset><br />
			<fieldset style="display:none;"><legend class="title"><b>[[.Extra_bed_baby_cot.]]</b></legend>
			<table border="1" bordercolor="#CCCCCC" cellpadding="3" id="extra_bed_baby_cot" style="border-collapse:collapse;">
            <tr>
            	<td>[[.service_name.]]</td>
            	<td>[[.eb_total_quantity.]]</td>                
            	<td>[[.eb_quantity.]]</td>                                
            </tr>
            <!--LIST:ebs-->
            <tr>
            	<td width="100"><b>[[|ebs.name|]]:</b></td>
                <td>[[|ebs.total_quantity|]]</td>
            	<td>[[|ebs.quantity|]]</td>
            </tr>
            <!--/LIST:ebs-->
			</table>            	
            </fieldset> <br />
		<!--IF:edit_reservation(USER::can_view(false,ANY_CATEGORY))--> 
		<table cellpadding="2" width="100%" class="room-map-customer-search-box" style="border:0px; display:none;">
			<tr>
			  <td colspan="2" class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;[[.search.]]</td>
			  </tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.RE_code.]]: </td>
			  <td><input name="code" type="text" id="code" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>              
			<tr>
			  <td nowrap="nowrap" align="right">[[.booking_code.]]: </td>
			  <td><input name="booking_code" type="text" id="booking_code" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
			<tr><td nowrap="nowrap" align="right">[[.customer_name.]]: </td>
				<td width="100%">
					<input type="text" id="customer_name" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			</tr>
			<!--<tr><td nowrap="nowrap" align="right">[[.company_name.]]: </td>
				<td width="100%">
					<input type="text" id="company_name" style="width:100px;" onKeyPress="if(event.keyCode==13)window.open('?page=reservation&booking_resource='+this.value)"/>				</td>
			</tr>-->
			<tr>
			  <td nowrap="nowrap" align="right">[[.traveller_name.]]: </td>
			  <td><input name="traveller_name" type="text" id="traveller_name" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/>
              </td>
			  </tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.note.]]</td>
			  <td><input name="note" type="text" id="note" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.country.]]</td>
			  <td><input name="nationality_id" type="text" id="nationality_id" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
            <tr>
			  <td nowrap="nowrap" align="right">[[.status.]]</td>
			  <td><select  name="room_status" id="room_status" style="width:100px;">
              		<option value="" selected>ALL</option>
              		<option value="CHECKIN">CHECKIN</option>
                    <option value="BOOKED">BOOKED</option>
                    <option value="CHECKOUT">CHECKOUT</option>
                    <option value="CANCEL">CANCEL</option>
              	</select>
			</tr>
		</table>
		<!--/IF:edit_reservation-->
		<fieldset style="border:1px solid #9DC9FF;margin-top:0px;display:none;">
			<legend class="check-availability-title"><b>[[.check_availability.]]</b></legend>
			<table id="check_availability_table">
			  <tr>
				<td width="100">[[.arrival_time.]]:</td>
				<td><input name="arrival_time" type="text" id="arrival_time" class="date-input"  readonly="readonly"></td>
			  </tr>
			  <tr>
				<td>[[.departure_time.]]:</td>
				<td><input name="departure_time" type="text" id="departure_time" class="date-input"  readonly="readonly"></td>
			  </tr>
			
			  <tr>
				<td>[[.room_type.]]:</td>
				<td><select name="room_level_id" id="room_level_id" style="width:100px;"></select></td>
			  </tr>
				<tr><td></td><td>
				<input type="button" value="[[.Go.]]" onClick="if($('arrival_time').value==''){alert('[[.you_have_to_input_arrival_time.]]');return false;}if($('departure_time').value==''){alert('[[.you_have_to_input_departure_time.]]');return false;};window.location='<?php echo Url::build('reservation',array('cmd'=>'check_availability'));?>&arrival_time='+$('arrival_time').value+'&departure_time='+$('departure_time').value+'&room_level_id='+$('room_level_id').value;">
			</td></tr>
			</table>
		</fieldset>        
		<!--IF:cond(Url::get('cmd')=='select')-->
		<fieldset>
			<legend class="title" style="font-size:12px;">[[.select_room_level.]]</legend>
			[<a href="<?php echo Url::build_current(array('cmd','object_id','act','from_date','r_r_id','to_date','input_count'));?>"><b>[[.all.]]</b></a>]<br /><br />
			<!--LIST:room_levels-->
            <!--IF:cond_room_level([[=room_levels.vacant_room=]]>0 or [[=over_book=]])-->
			<div class="row-even">
                <input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly">
                &nbsp;
                <a href="#" onClick="selectRoomLevel(<?php echo Url::iget('object_id');?>,'[[|room_levels.name|]]',[[|room_levels.id|]],<?php echo Url::iget('input_count');?>)">
                [[|room_levels.name|]] 
                    <b>([[|room_levels.vacant_room|]])</b>
                </a> |
                [<a class="notice" href="<?php echo Url::build_current(array('cmd','object_id','act','input_count','from_date','to_date','r_r_id','room_level_id'=>[[=room_levels.id=]],'room_level_id_old'));?>">[[.filter.]]</a>]
                
            </div><br />
            
            <!--ELSE-->
            <div class="row-even"><input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly">&nbsp;[[|room_levels.name|]] <b>(0)</b>|<span style="color:red;"> [ H?t phòng]</span></div><br />
            <!--/IF:cond_room_level-->
			<!--/LIST:room_levels-->
		</fieldset>
		<!--/IF:cond-->
		<fieldset style="border:1px solid #9DC9FF">
			<legend class="title">[[.booking_without_room.]]</legend>
            <marquee style="width:100%;color:#F00;" onMouseOut="this.start();" onMouseOver="this.stop();" scrollamount="3">
            <!--LIST:books_without_room-->
            	<!--IF::cond_wait([[=books_without_room.arrival=]]==date('d/m/Y'))-->
                	<a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=books_without_room.reservation_id=]]));?>" style="color:#F00;">[[.expired_booking.]] [[|books_without_room.customer_name|]] - [[|books_without_room.tour_name|]] ([[|books_without_room.arrival|]])</a><br />
                <!--/IF::cond_wait-->
            <!--/LIST:books_without_room-->
         	</marquee>
			<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
				<tr class="table-header">
					<th width="60%">[[.room_level.]]</th>
					<th width="40%">[[.num_people.]]</th>
				</tr>
				<?php $temp = '';?>
				<!--LIST:books_without_room-->
				<?php if($temp != [[=books_without_room.reservation_id=]]){$temp != [[=books_without_room.reservation_id=]];?>
				<tr>
					<td bgcolor="#EFEFEF"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=books_without_room.reservation_id=]]));?>"><strong>[[|books_without_room.reservation_id|]] - [[|books_without_room.booking_code|]]</strong></a></td>
                    <td bgcolor="#EFEFEF"><a href="<?php echo Url::build('reservation',array('cmd'=>'asign_room','id'=>[[=books_without_room.reservation_id=]]));?>"><input name="asign_room" type="button" id="asign_room" value="[[.assign.]]"  /> </a></td>
				</tr>
				<?php }?>
				<tr>
					<td> [[|books_without_room.room_level|]]</td>
					<td><span class="reservation-list-item">([[|books_without_room.adult|]])<img src="packages/core/skins/default/images/buttons/adult.png" width="6"><!--IF:cond([[=books_without_room.child=]])-->+([[|books_without_room.child|]])<img src="packages/core/skins/default/images/buttons/child.png" width="6" /></span>
                    </td>
				</tr>
				<!--/LIST:books_without_room-->
			</table>
            <marquee style="width:100%;color:#F00;" scrollamount="3" onMouseOut="this.start();" onMouseOver="this.stop();">
            <!--LIST:books_without_room-->
            	<!--IF::cond_cut_of_date([[=books_without_room.cut_of_date=]]==date('d/m/Y'))-->
                	<a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=books_without_room.reservation_id=]]));?>" style="color:#F00;">[[.cut_off_day.]] [[|books_without_room.customer_name|]] - [[|books_without_room.room_level|]] ([[|books_without_room.arrival|]])</a><br />
                <!--/IF::cond_cut_of_date-->
            <!--/LIST:books_without_room-->
         	</marquee> 
		</fieldset><br />
		<fieldset style="border:1px solid #9DC9FF; display:none;">
			<legend class="title">[[.explanation.]]</legend>
			<table width="99%" border="0" cellpadding="2">
			  <tr>
				<td><div class="room_arround"><a href="#" class="room AVAILABLE" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td>[[.available_room.]] ([[|total_available_room|]]) </td>
				</tr>
			  <tr>
				<td><div class="room_arround"><a href="#" class="room BOOKED" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td>[[.booked.]] ([[|total_booked_room|]])</td>
				</tr>
			<tr style="display:none;">
				<td><div class="room_arround"  style="display:none;"><a href="#" class="room RESOVER" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td align="left">[[.resover.]] ([[|total_resover_room|]])</td>
				</tr>
			  <tr>
				<td><div class="room_arround"><a href="#" class="room OCCUPIED" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td>[[.occupied.]] (<?php echo [[=total_checkin_room=]];?>)</td>
				</tr>
			  <tr>
				<td style="border-top:1px solid #CCC;background:#EFEFEF;"><div class="room_arround"><a href="#" class="room DAYUSED" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td style="border-top:1px solid #CCC;background:#EFEFEF;">[[.checkin_today.]] (<?php echo [[=total_dayused_room=]];?>)</td>
				</tr>
			  <tr>
				<td style="border-bottom:1px solid #CCC;background:#EFEFEF;"><div class="room_arround"><a href="#" class="room OVERDUE" style="width:16px;height:16px;margin-right:2px;"></a></div></td>
				<td style="border-bottom:1px solid #CCC;background:#EFEFEF;">[[.overdue.]]  ([[|total_overdue_room|]])</td>
				</tr>
               <tr>
				<td><div class="room_arround" ><a href="#" class="room EXPECTED_CHECKOUT" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td align="left">[[.expected_checkout.]] ([[|total_checkout_today_room|]])</td>
				</tr>
			  <tr style="display:none;">
				<td><div class="room_arround" style="display:none;"><a href="#" class="room CHECKOUT" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td align="left">[[.checked_out.]] ([[|total_checkout_room|]])</td>
				</tr>
			  <tr>
				<td><div class="room_arround"><a href="#" class="room REPAIR" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td>[[.reparing.]]  ([[|total_repaire_room|]])</td>
				</tr>
			</table>
		</fieldset>
			<table cellpadding="0" width="100%" class="room-map-tour-list" style=" display:none;">
			<tr>
				<td class="title">[[.tour_list.]]</td>
			</tr>
			<tr>
				<td align="left">
					<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#9DC9FF">
					  <tr valign="top" bgcolor="#EFEFEF">
						<th>[[.no.]]</th>
						<th>[[.tour_name.]]</th>
						<th style="font-size:11px;" bgcolor="#71AAFF">[[.b.]]</th>
						<th style="font-size:11px;" bgcolor="#FFCC33">[[.in.]]</th>
						<th style="font-size:11px;" bgcolor="#FF66FF">[[.out.]]</th>
					  </tr>
					  <!--LIST:tours-->
					  <tr>
						<td style="font-size:11px;">[[|tours.i|]]</td>
						<td style="font-size:11px;"><?php if([[=tours.name=]] !=''){echo [[=tours.name=]];}else if([[=tours.customer_name=]]!=''){echo [[=tours.customer_name=]];}else {echo [[=tours.tour_name=]];}?><a title="[[.view_reservation.]]" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=tours.reservation_id=]]));?>" class="room-map-view-reservation"><img src="packages/core/skins/default/images/cmd_Tim.gif" /></a><BR><span style="font-size:10px;">([[.arrival_time.]]: [[|tours.arrival_time|]])</span></td>
						<td style="font-size:11px;" bgcolor="#71AAFF">[[|tours.room_booked|]]</td>
						<td style="font-size:11px;" bgcolor="#FFCC33">[[|tours.room_checkin|]]</td>
						<td style="font-size:11px;" bgcolor="#FF66FF">[[|tours.room_checkout|]]</td>
					  </tr>
					  <!--/LIST:tours-->
					</table></td>			
			</tr>
		</table>
	</div></td>
	<td bgcolor="#FFFFFF" align="left" valign="top" id="room_map_toogle" width="1%"><img id="room_map_toogle_image" src="packages/core/skins/default/images/paging_left_arrow.gif" style="cursor:pointer;" onClick="if(jQuery.cookie('collapse')==1){jQuery.cookie('collapse','0');jQuery('#room_map_left_utils').fadeOut();this.src='packages/core/skins/default/images/paging_right_arrow.gif';}else{jQuery.cookie('collapse','1');jQuery('#room_map_left_utils').fadeIn();this.src='packages/core/skins/default/images/paging_left_arrow.gif'}"></td>
	<td bgcolor="#FFFFFF" align="left" width="99%">
		<div id="information_bar"></div>
		<!--IF:cond(User::can_view(false,ANY_CATEGORY))-->
		<input type="button" value="[[.New_reservation.]]" onClick="buildReservationUrl('RFA');" class="button-medium booked">
        <input type="button" value="[[.Walk_in.]]" onClick="buildReservationUrl('RFW');" class="button-medium booked">
        <input type="button" value="[[.full_screen.]]" id="full_screen_button" onClick="switchFullScreen();" class="button-medium fullscreen">
        <!--IF:cond_module(User::can_view(MODULE_MANAGENOTE,ANY_CATEGORY))-->
        <input type="button" value="[[.Reservation_note.]]" onClick="window.open('?page=manage_note');" class="button-medium booked" style="float:right;">
        <!--/IF:cond_module-->
        <br clear="all"><br />
		<!--/IF:cond-->
		<div class="body">
		<table width="100%" border="1" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#CCCCCC">
        <?php $count=0;?>
			<!--LIST:floors-->	
			<!-- onmouseover="this.bgColor='#B7D8FF'" onmouseout="this.bgColor='#FFFFFF'" -->	
			<tr valign="middle" id="bound_floor_[[|floors.id|]]">
				<td width="40px" style="text-transform:uppercase;color:#FFFFFF;<?php if(substr([[=floors.name=]],0,1)=='A'){echo 'background:#82BAFF;';}else{ echo 'background:#5b90e7;';}?>" nowrap="nowrap"><b>[[|floors.name|]]</b></td>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="td_room_bound" id="floor_[[|floors.id|]]">
                    <!--LIST:floors.rooms-->
                    <?php 
                        $room_level_id = isset([[=floors.rooms.room_level_id=]])?[[=floors.rooms.room_level_id=]]:0;
                        /** START hang phong am thi khi click ch?n l?c theo h?ng phong o phan check avaiable k hi?n thi phong v?i truong h?p cho phép am phong **/
                        //if(isset($this->map['room_levels'][$room_level_id]) && $this->map['room_levels'][$room_level_id]['vacant_room']>=0)
                        if(isset($this->map['room_levels'][$room_level_id]))
                        /** END hang phong am thi khi click ch?n l?c theo h?ng phong o phan check avaiable k hi?n thi phong v?i truong h?p cho phép am phong **/
                        {
                            if((Url::get('room_level_id') && $room_level_id==Url::get('room_level_id')) || !Url::get('room_level_id') )
                            { 
                                $count++;
                    ?>
                            <div title="<?php echo addslashes([[=floors.rooms.note=]]);?>" class="room-bound">
							  	<span class="room-name"><strong><span style="font-size:14px;color:#0000FF;">[[|floors.rooms.name|]]</span>-[[|floors.rooms.type_name|]]</strong><br /></span><br clear="all">
								<a href="#" id="room_[[|floors.rooms.id|]]" class="room <?php echo ([[=floors.rooms.house_status=]]=='REPAIR' || [[=floors.rooms.status=]] =='CHECKOUT')?[[=floors.rooms.house_status=]]:[[=floors.rooms.status=]];?>"
									<?php
									if(URL::get('cmd')=='select')
									{
										echo 'onclick=" opener.document.getElementById(\'room_id_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.id=]].'\';
														opener.document.getElementById(\'room_name_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.name=]].'\';
														opener.document.getElementById(\'room_level_name_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.room_level_name=]].'\';
														opener.document.getElementById(\'room_level_id_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.room_level_id=]].'\';
														opener.document.getElementById(\'time_in_'.URL::get('object_id').'\').value=\''.CHECK_IN_TIME.'\';
														opener.document.getElementById(\'time_out_'.URL::get('object_id').'\').value=\''.CHECK_OUT_TIME.'\';
                                                        opener.document.getElementById(\'house_status_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.house_status=]]/** START Gán phòng_ chuy?n ngay tr?ng thái thành checkin, H? th?ng không check du?c, phòng dirty d? dua ra c?nh báo và không cho checkin **/.'\';
														if(!opener.document.getElementById(\'arrival_time_'.URL::get('object_id').'\').value)
															opener.document.getElementById(\'arrival_time_'.URL::get('object_id').'\').value = \''.[[=day=]].'/'.[[=month=]].'/'.[[=year=]].'\';
														if(!opener.document.getElementById(\'departure_time_'.URL::get('object_id').'\').value)
															opener.document.getElementById(\'departure_time_'.URL::get('object_id').'\').value = \''.[[=end_day=]].'/'.[[=end_month=]].'/'.[[=year=]].'\';
														oldRoomLevelId = '.(Url::get('room_level_id_old')?Url::get('room_level_id_old'):0).';
														if(!opener.document.getElementById(\'id_'.URL::get('object_id').'\').value)
															opener.document.getElementById(\'price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.price=]].'\';
														';
														
														if(Url::get('price') && Url::get('price')<[[=floors.rooms.price=]]){
															//echo 'opener.document.getElementById(\'price_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.price=]].'\';';	
														}
										echo 'opener.count_price('.URL::get('object_id').');';
										echo 'opener.updateRoomForTraveller('.URL::get('object_id').');window.close();"';
									}
									else
									{
										echo 'onclick="select_room('.[[=floors.rooms.id=]].', document.HotelRoomAvailabilityForm);update_room_info();return false;"';
									}
									?>>
									<!--IF:room_level(Url::get('cmd')=='select' and ([[=floors.rooms.status=]]=='AVAILABLE' OR [[=floors.rooms.status=]]=='CHECKOUT' OR [[=floors.rooms.status=]]=='EXPECTED_CHECKOUT'))--><span title="[[.select_room.]]"><img src="packages/core/skins/default/images/active.gif" width="12" height="12"></span><!--/IF:room_level--><!--IF:time(isset([[=floors.rooms.time_in=]]) and [[=floors.rooms.time_in=]] and [[=floors.rooms.status=]] != 'CHECKOUT')-->
									<span style="font-size:9px;text-decoration:underline;color:#003399;font-weight:bold;"><?php echo (date('d/m/Y',[[=floors.rooms.time_in=]])==date('d/m/Y',[[=floors.rooms.time_out=]]))?date('H:i',[[=floors.rooms.time_in=]]):date('d/m',[[=floors.rooms.time_in=]]);?> - <?php echo (date('d/m/Y',[[=floors.rooms.time_in=]])==date('d/m/Y',[[=floors.rooms.time_out=]]))?date('H:i',[[=floors.rooms.time_out=]]):date('d/m',[[=floors.rooms.time_out=]]);?></span><br />
									<!--/IF:time-->
									<span id="house_status_[[|floors.rooms.id|]]" style="font-size:9px;font-weight:normal;color:red;">[[|floors.rooms.house_status|]]</span>
                                     <?php $r_r = '';?>
									<!--LIST:floors.rooms.travellers-->
                                    <?php $r_r = [[=floors.rooms.travellers.reservation_room_id=]];                                  
                                        	if(isset($f[$r_r])){
												$f[$r_r]++;
											}else{	
												$f[$r_r]=1;
											}
											if($f[$r_r]==1){?>
												<span style="font-size:10px;color:#009999;">[[|floors.rooms.travellers.customer_name|]]
											<?php }?>
									<!--/LIST:floors.rooms.travellers-->
                                   <?php if(isset($f[$r_r]) && $f[$r_r]>1){
                                    	echo '('.$f[$r_r].')</span>';
                                    }?>
									<?php if(isset([[=floors.rooms.tour_id=]]) and [[=floors.rooms.tour_id=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
									{
										echo '<span style="font-size:9px;background-color:'.[[=floors.rooms.color=]].';" title="'.[[=floors.rooms.tour_name=]].'">'.[[=floors.rooms.tour_name=]].'</span><br>';
									}
									?>
									<?php if(isset([[=floors.rooms.customer_name=]]) and [[=floors.rooms.customer_name=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
									{
										echo '<span style="font-size:9px;background-color:'.[[=floors.rooms.color=]].';" title="'.[[=floors.rooms.customer_name=]].'">'.[[=floors.rooms.customer_name=]].'</span><br>';
									}
									?>
									<?php if(isset([[=floors.rooms.foc_all=]]) and [[=floors.rooms.foc_all=]]==1 and [[=floors.rooms.status=]] != 'CHECKOUT')
										{ 
											echo '<span class="room-foc" title="'.[[=floors.rooms.foc=]].'FOC ALL">FOC ALL</span><br>';
										}else if(isset([[=floors.rooms.foc=]]) and [[=floors.rooms.foc=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
										{ 
											echo '<span class="room-foc" title="'.[[=floors.rooms.foc=]].'">FOC</span><br>';
										}
									?>
									<?php if([[=floors.rooms.out_of_service=]] and ([[=floors.rooms.status=]] != 'CHECKOUT'))
									{
										echo '<span style="color:red;font-size:8px">oos</span>';
									}
									?>
									<?php if([[=floors.rooms.note=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
									{
										echo '<span style="font-size:14px;color:#FF0000;font-weight:bold">*</span>';
									}
									?>
								</a>
								<div class="room-item-bound">
								<!--LIST:floors.rooms.old_reservations-->
									<a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=floors.rooms.old_reservations.reservation_id=]]));?>" title="[[.code.]]: [[|floors.rooms.old_reservations.id|]], [[.status.]]: [[|floors.rooms.old_reservations.status|]], [[.price.]]: [[|floors.rooms.old_reservations.price|]] <?php echo HOTEL_CURRENCY;?>" class="item_room [[|floors.rooms.old_reservations.status|]]"></a>
								<!--/LIST:floors.rooms.old_reservations-->
								</div>	
							</div><?php }}?><!--/LIST:floors.rooms--></td>
				  </tr>
				</table></td>
			</tr>
			<!--/LIST:floors-->	
            <?php if($count == 0){echo '<strong> <span id="message_room" style="font-size:16px; color:red;">Lo?i phòng này dã h?t!</span></strong>';}?>
		</table>
		<br />
		<p></p></td></tr>
	</table>
	<input type="hidden" name="command" id="command">
	<br>
</td></tr>
</table>
<input type="hidden" name="room_ids" id="room_ids"/>
</div>
</form>
<script>
jQuery(document).ready(function(){
	jQuery('.td_room_bound').each(function(){
		if(jQuery(this).html() == '' || jQuery(this).html() == '" "'){
			var id= jQuery(this).attr('id');	
			jQuery('#bound_'+id).css('display','none');
		}
	});
});
	function FullScreen(){
		jQuery("#room_map").attr('class','full_screen');
		jQuery("#full_screen_button").attr('value','[[.exit_full_screen.]]');
	}
	function switchFullScreen(){
		if(jQuery.cookie('fullScreen')==1){
			jQuery("#room_map").attr('class','');
			jQuery("#full_screen_button").attr('value','[[.full_screen.]]');
			jQuery.cookie('fullScreen',0);
		}else{
			FullScreen();
			jQuery.cookie('fullScreen',1);
		}
	}
	if(jQuery.cookie('fullScreen')==1){
		FullScreen();
	}
	var CURRENT_YEAR = <?php echo date('Y')?>;
	var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
	var CURRENT_DAY = <?php echo date('d')?>;
	<?php if(URL::get('cmd')=='select'){?>FullScreen();<?php }?>
	jQuery('#arrival_time').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#departure_time').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#from_date').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#to_date').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	function handleKeyPress(evt) {  
		var nbr;  
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==27){
			closeAllWindows();
			return false;
		}
		return true;
	}
	document.onkeydown= handleKeyPress;
	if(jQuery.cookie('collapse')==null){
		jQuery.cookie('collapse',1);
		$('room_map_toogle_image').title='[[.Collapse.]]';
	}
	if(jQuery.cookie('collapse')==0){
		$("room_map_left_utils").style.display='none';
		$('room_map_toogle_image').src='packages/core/skins/default/images/paging_right_arrow.gif'
		$('room_map_toogle_image').title='[[.expand.]]';
	}
	function update_room_info()
	{
		var functions = '';
		var actions = get_actions();
		for(var i in actions)
		{
			functions += '<a href="'+actions[i].url+'" class="room map">'+actions[i].text+'</a>';
		}
		if(document.HotelRoomAvailabilityForm.room_ids.value != '')
		{	
			var rooms = document.HotelRoomAvailabilityForm.room_ids.value.split(',');			
			var information = '';
			var rooms_status = 'AVAILABLE';
			if(rooms.length==1)
			{
				var room = rooms_info[rooms[0]];
				rooms_status = room.status;
				information = '<table width="98%" cellspacing="1" border=0 bordercolor="#CCCCCC" bgcolor="#FFFFFF">';
			}
			else
			{
				var rooms_name = '';
				
				var house_status = rooms_info[rooms[0]].house_status;
				var note = rooms_info[rooms[0]].note;
				for(var i=1;i<rooms.length;i++)
				{
					if(rooms_info[rooms[i]].status != 'AVAILABLE')
					{
						if(rooms_status!='BUSY' || rooms_info[rooms[i]].status != 'BUSY')
						{
							if(rooms_status!= rooms_info[rooms[i]].status)
							{
								rooms_status = 'MIXED';
							}
						}
						if(house_status!=rooms_info[rooms[i]].house_status)
						{
							house_status='';
						}
					}
				}
				information = '<table width="100%" cellspacing="1" border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">';
			}
			if(rooms.length==1)
			{
				room_reservations = room['reservations'];//.reverse 
				information += '<tr><td class="label">[[.room_name.]]</td><td width="1%">:</td><td class="value">'+room.name+'</td></tr>';
				if(typeof(room_reservations)=='undefined' || (typeof(room_reservations)!='undefined' && room.status=='AVAILABLE'))
				{
					information += '<tr><td class="label">[[.price.]]</td><td width="1%">:</td><td class="value">'+room.price+room.tax_rate+room.service_rate+' <?php echo HOTEL_CURRENCY;?></td></tr>';
				}
				//else
				{
					var last_reservation = 0;
					for(var j in room_reservations)
					{
						if(last_reservation != room_reservations[j]['reservation_id'])
						{
							last_reservation = room_reservations[j]['reservation_id'];
							
							if(last_reservation && last_reservation!=0 && last_reservation!='')
							{
								<!--IF:edit_reservation(USER::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY))--> 
								information += '<tr><td class="room-map-bill-number">[[.bill_number.]]: '+room_reservations[j]['reservation_room_id']+'</td><td width="1%">:</td><td class="value"><a href="?page=reservation&cmd=edit&id='+last_reservation+'&r_r_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"><img src="packages/core/skins/default/images/buttons/edit.gif" /> [[.view_detail.]]</a></td></tr>';
								<!--ELSE-->
								<!--IF:view_reservation(User::can_view($this->get_module_id('CheckIn'),ANY_CATEGORY))-->
								information += '<tr><td class="room-map-bill-number">[[.bill_number.]]: '+room_reservations[j]['reservation_room_id']+'</td><td width="1%">:</td><td class="value"><a href="?page=reservation&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&extra_service_invoice&id='+room_reservations[j]['reservation_room_id']+'&cmd=invoice" class="room-map-edit-link"><img src="packages/core/skins/default/images/buttons/edit.gif" />[[.view_detail.]]</a></td></tr>';

								<!--ELSE-->
								information += '<tr><td class="room-map-bill-number">[[.bill_number.]]: '+room_reservations[j]['reservation_id']+'</td><td width="1%">:</td><td class="value"></td></tr>';
								<!--/IF:view_reservation-->
								<!--/IF:edit_reservation-->
								<!--IF:add_traveller(USER::can_add($this->get_module_id('UpdateTraveller'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED' || room_reservations[j]['status']=='BOOKED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="#" onClick="openWindowUrl(\'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=add_traveller&rr_id='+room_reservations[j]['reservation_room_id']+'&r_id='+room_reservations[j]['reservation_id']+'\',Array(\'add_traveller_'+room_reservations[j]['reservation_room_id']+'\',\'[[.list_traveller.]]\',\'20\',\'110\',\'1100\',\'570\'));closeAllWindows();" class="room-map-edit-link"> [[.list_guest.]]</a></td></tr>':'';
								<!--/IF:add_traveller-->
								<!--IF:add_minibar_invoice(USER::can_add($this->get_module_id('MinibarInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="?page=minibar_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"> [[.add_minibar_invoice.]]</a></td></tr>':'';
								<!--/IF:add_minibar_invoice-->
								<!--IF:add_minibar_invoice(USER::can_add($this->get_module_id('LaundryInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="?page=laundry_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"> [[.add_laundry_invoice.]]</a></td></tr>':'';
								<!--/IF:add_minibar_invoice-->
								<!--IF:add_extra_service_invoice(USER::can_add($this->get_module_id('ExtraServiceInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="?page=extra_service_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"> [[.add_extra_service_invoice.]]</a></td></tr>':'';
								<!--/IF:add_extra_service_invoice-->
								<!--IF:add_equipment_invoice(USER::can_add($this->get_module_id('EquipmentInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="?page=equipment_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"> [[.add_compensation_invoice.]]</a></td></tr>':'';
								<!--/IF:add_equipment_invoice-->
								information += '<tr><td class="label">[[.create_user.]]</td><td>:</td><td class="value">'+room_reservations[j]['user_id']+'</td></tr>';
								information += '<tr><td class="label">[[.reservation_status.]]</td><td>:</td><td class="value">'+room_reservations[j]['status']+' ('+room_reservations[j]['adult']+' [[.adult.]])</td></tr>';
								if(room_reservations[j]['net_price']==1){
									information += '<tr><td class="label">[[.price.]]</td><td>:</td><td class="value">'+room_reservations[j]['price']+' <?php echo HOTEL_CURRENCY;?></td></tr>';
								}else{
									information += '<tr><td class="label">[[.price.]]</td><td>:</td><td class="value">'+room_reservations[j]['price']+room_reservations[j]['tax_rate']+room_reservations[j]['service_rate']+' <?php echo HOTEL_CURRENCY;?></td></tr>';
								}
								if(room_reservations[j]['company_name'])
									information += '<tr><td class="label">[[.company.]]</td><td>:</td><td class="value">'+room_reservations[j]['company_name']+'</td></tr>';
								information += '<tr><td colspan="3" align="left"><img src="packages/core/skins/default/images/calen.gif" width="20px" align="center">&nbsp;'+room_reservations[j]['arrival_time']+room_reservations[j]['time_in']+' - '+room_reservations[j]['departure_time']+room_reservations[j]['time_out']+' ('+room_reservations[j]['duration']+')</td></tr>';
								if(room_reservations[j]['travellers'])
								{
									information += '<tr><td colspan="3"><table><th nowrap width="100%" align="left">[[.customer_name.]]</th></tr>';
									for(var k in room_reservations[j]['travellers'])
									{
										information += '<tr title="[[.date_of_birth.]]: '+
											room_reservations[j]['travellers'][k]['age']+'\n[[.country.]]: '+
											room_reservations[j]['travellers'][k]['country_name']+'"><td class="value"><a target="_blank" href="?page=traveller&id='+room_reservations[j]['travellers'][k]['traveller_id']+'">'+room_reservations[j]['travellers'][k]['customer_name']+': '+room_reservations[j]['travellers'][k]['date_in']+' ('+room_reservations[j]['travellers'][k]['time_in']+') - '+room_reservations[j]['travellers'][k]['date_out']+' ('+room_reservations[j]['travellers'][k]['time_out']+')</a></td>';
										information += '<td class="value"></td></tr>';
									}
									information += '</table></td></tr>';
								}
								information += '<tr><td colspan="3">[[.group_note.]]:\
						<div  id="group_note_'+room_reservations[j]['reservation_id']+'" style="width:325px; border:none;" readonly>'+room_reservations[j]['group_note']+'</div> ';
								<!--IF:room_note(User::can_edit(false,ANY_CATEGORY))-->
								information += '<tr><td colspan="3">[[.note.]]:\
						<textarea  name="room_note_'+room_reservations[j]['reservation_id']+'" style="width:325px" rows="3">'+room_reservations[j]['room_note']+'</textarea>\
						<input  type="submit" value="Change" name="change_room_note_'+room.id+'"/>\
						<br><hr></br>\
					</td></tr>';
								<!--/IF:room_note-->
							}
						}
					}
				}
			}
			<!--IF:housekeeping(USER::can_view(false,ANY_CATEGORY))-->
			information += '<tr><td colspan="3"><h3>[[.for_housekeeping.]]:</h3><br>';
			<!--IF:minibar(User::can_view($this->get_module_id('MinibarInvoice'),ANY_CATEGORY))-->
			information += '[[.note.]]:<br><textarea  name="note" style="width:325px" rows="3">'+((rooms.length==1)?room.hk_note:'')+'</textarea><br>';
			<!--/IF:minibar-->
			information += '[[.house_status.]]: <select  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option><option value="HOUSEUSE">HOUSEUSE</option></select><input type="submit" value="Change" class="hk-status-button"/>';
			information += '<div id="div_date_repair">[[.select_date.]] [[.to.]] <input  name="repair_to" type="text" id="repair_to" class="date-input" style="width:90px;" ></div></td></tr>';
			<!--ELSE-->
			if(rooms.length==1)
			{
				if(room.note!='')
				{
					information += '<tr><td colspan="3">[[.note.]]</td></tr>';
				}
			}
			jQuery('#repair_to').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4'});
			<!--/IF:housekeeping-->
			/*information += '</table>';
			$('information_bar').innerHTML = '<div class="room-info-content">'+information+'</div>';
			*/
			pageX = 200;
			pageY = 200;
			jQuery(".room-bound").click(function(e){
				if(e.ctrlKey==false && e.shiftKey==false){
					pageY = e.pageY - 100;
					pageX = e.pageX - 400;
					jQuery('#room_map').window({
						draggable: false,
						resizable:true,
						title: "[[.rooms_info.]]",
						content: information,
						footerContent: '<a style="color:#333333;font-size:11px;" onclick="buildReservationUrl(\'RFA\');">[[.reserve_for_agent.]]<a> | <a style="color:#333333;font-size:11px;" onclick="buildReservationUrl(\'RFW\');">[[.Walk_in.]]<a>',




						frameClass: 'room-info-content',
						footerClass:'room-info-content',
						showRoundCorner:true,
						resizable: false,
						maximizable: false,
						x:pageX,
						y:pageY,
						width: 350,
						height:274,
						draggable: true,
						onOpen: closeAllWindows()
					});
				}
				 jQuery('#repair_to').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4'});
				 jQuery('#ui-datepicker-div').css('z-index','3000');
			});
		}
		//$('information_bar').innerHTML += functions;
	}
	function closeAllWindows(){
		jQuery.window.closeAll(true);
	}
	function get_reservation_id(room)
	{
		for(var i in room_reservations)
		{
			if(room_reservations[i].reservation_id)
			{
				return room_reservations[i].reservation_id;
			}
		}
		return 0;
	}
	function get_actions()
	{
		var time_parameters = '&arrival_time=[[|day|]]/[[|month|]]/[[|year|]]&departure_time=[[|end_day|]]/[[|end_month|]]/[[|year|]]';
		var date_parameters = '&year=[[|year|]]&month=[[|month|]]&day=[[|day|]]';
		var changed_rooms = '';
		
		var reservation_status = 'AVAILABLE';
		var rooms_status = 'AVAILABLE';
		var reservation_id = 0;
		var rooms = [];
		if(document.HotelRoomAvailabilityForm.room_ids.value != '')
		{
			rooms = document.HotelRoomAvailabilityForm.room_ids.value.split(',');
			var rooms_status = rooms_info[rooms[0]]['status'];
			if(rooms_info[rooms[0]]['reservations'])
			{
				var reservation_id = rooms_info[rooms[0]]['reservations'][0].reservation_id;
			}
			else
			{
				var reservation_id = 0;
			}
			for(var i in rooms)
			{
				changed_rooms += '&mi_changed_room['+i+'][from_room]='+rooms[i];
			}
		}
		else
		{
			var rooms = [0];
			var rooms_status = 'unknow';
			var reservation_id = 0;
		}
		var actions = {
			
			'reservation':{'text':'[[.reservation.]]','url':'?page=reservation&cmd=add&time_in=<?php echo CHECK_IN_TIME;?>&time_out=12:00&rooms='+get_query_string(),
				'privileges':['BOOKED'],'statuses':['AVAILABLE','SHORT_TERM','BOOKED','OCCUPIED','CHECKOUT','RESOVER','OVERDUE']},
			'edit_reservation':{'text':'[[.edit_reservation.]]','url':'?page=reservation&cmd=edit&id='+reservation_id,
				'privileges':['BOOKED'],'statuses':['BOOKED','OCCUPIED','CHECKOUT'],'reservation_statuses':['BOOKED','CHECKIN','CHECKOUT','CANCEL']}
		}
		if(document.HotelRoomAvailabilityForm.room_ids.value != '')
		{
			actions['forgot_object'] = {'text':'[[.forgot_object.]]','url':'?page=forgot_object&cmd=add&room_id='+rooms[0],
				'privileges':['housekeeping'],'statuses':['OVERDUE','OCCUPIED','AVAILABLE','UNAVAILABLE','BOOKED']};
			actions['house_equipment'] = {'text':'[[.house_equipment.]]','url':'?page=housekeeping_equipment&cmd=add&room_id='+rooms[0],
				'privileges':['housekeeping'],'statuses':['OVERDUE','OCCUPIED','AVAILABLE','UNAVAILABLE','BOOKED']};
		}
		else
		{
			actions['forgot_object'] = {'text':'[[.forgot_object.]]','url':'?page=forgot_object',
				'privileges':['housekeeping'],'statuses':[]};
			actions['house_equipment'] = {'text':'[[.house_equipment.]]','url':'?page=housekeeping_equipment',
				'privileges':['housekeeping'],'statuses':[]};
		}
		var privileges = ['a'
			<!--IF:privilege(USER::can_view(false,ANY_CATEGORY))-->
			,'housekeeping'
			<!--/IF:privilege-->
			<!--IF:privilege(USER::is_admin())-->
			,'admin'
			<!--/IF:privilege-->
			<!--IF:privilege(USER::can_add(false,ANY_CATEGORY))-->
			,'BOOKED'
			<!--/IF:privilege-->
			<!--IF:privilege(USER::can_edit(false,ANY_CATEGORY))-->
			,'CHECKIN'
			<!--/IF:privilege-->
			<!--IF:privilege(USER::can_edit(false,ANY_CATEGORY))-->
			,'CHECKOUT'
			<!--/IF:privilege-->
			<!--IF:privilege(USER::can_add(false,ANY_CATEGORY))-->
			,'BAR_BOOKED'
			<!--/IF:privilege-->
		];
		
		var accept_actions = {};
		var max_departure_time = 0;
		if(document.HotelRoomAvailabilityForm.room_ids.value != '')
		{
			reservation_status = 'AVAILABLE';
			rooms_status = 'AVAILABLE';
			
			for(var i=0;i<rooms.length;i++)
			{
				if(rooms_info[rooms[i]]['reservations'])
				{
					for(var j in rooms_info[rooms[i]]['reservations'])
					{
						if(rooms_info[rooms[i]]['reservations'][j]['end_time']>max_departure_time)
						{
							max_departure_time=rooms_info[rooms[i]]['reservations'][j]['end_time'];
						}
					}
				}
				if(rooms_info[rooms[i]].status != 'AVAILABLE')
				{
					if(rooms_info[rooms[i]].status != 'BOOKED')
					{
						if(rooms_status!='BUSY' || rooms_info[rooms[i]].status != 'BUSY')
						{
							if(rooms_status != rooms_info[rooms[i]].status)
							{
								if(rooms_status == 'AVAILABLE')
								{
									rooms_status = rooms_info[rooms[i]].status;
								}
								else
								{
									rooms_status = 'MIXED';
								}
							}
						}
					}
				}
				if(rooms_info[rooms[i]]['reservations'])
				{
					if(rooms_info[rooms[i]]['reservations'][0].status != reservation_status)
					{
						if(reservation_status == 'AVAILABLE')
						{
							reservation_status = rooms_info[rooms[i]]['reservations'][0].reservation_status;
						}
						else
						{
							reservation_status = 'MIXED';
						}
					}
				}
			}
			
			for(var j in actions)
			{
				for(var i=1;i<privileges.length;i++)
				{
					if(typeof(accept_actions[j]) == 'undefined')
					{
						for(var k in actions[j].privileges)
						{
							if(actions[j].privileges[k] == privileges[i])
							{
								if((j=='BOOKED' || j=='reservation_tour' || j=='new_checkin') && (rooms_status=='BOOKED'))
								{
									accept_actions[j]=actions[j];
								}
								else
								for(var m in actions[j].statuses)
								{
									if(actions[j].statuses[m] == rooms_status)
									{
										if(typeof(actions[j].reservation_statuses)!='undefined')
										{
											for(var n in actions[j].reservation_statuses)
											{

												if(actions[j].reservation_statuses[n] == reservation_status)
												{
													accept_actions[j]=actions[j];
													break;
												}
											}
										}
										else
										{
											if(j=='reservation' && rooms_status!='AVAILABLE' && max_departure_time>=<?php echo strtotime([[=month=]].'/'.[[=day=]].'/'.[[=year=]])+24*3600;?>)
											{
												
											}
											else
											{
												accept_actions[j]=actions[j];
											}
										}
										break;
									}
								}
								break;
							}
						}
					}
				}
			}
			
		}
		for(var j in actions)
		{
			for(var i=1;i<privileges.length;i++)
			{
				if(typeof(accept_actions[j]) == 'undefined')
				{
					for(var k in actions[j].privileges)
					{
						if(actions[j].privileges[k] == privileges[i])
						{
							if(actions[j].statuses.length == 0)
							{
								accept_actions[j]=actions[j];
							}
							break;
						}
					}
				}
			}
		}
		return accept_actions;
	}
	rooms_info = [[|rooms_info|]];
	update_room_info();
   
   function get_query_string()
	{
		var query_string = '';
		if(document.HotelRoomAvailabilityForm.room_ids.value!='')
		{
			var rooms = document.HotelRoomAvailabilityForm.room_ids.value.split(',');
		}
		else
		{
			var rooms = [];
		}
		for(var i in rooms)
		{
			if(query_string!='')
			{
				query_string += '|';
			}
			query_string += rooms[i]+','+'[[|day|]]/[[|month|]]/[[|year|]]'+','+'[[|end_day|]]/[[|end_month|]]/[[|year|]]';
		}
		<!--LIST:room_levels-->
		query_string += '&room_prices['+[[|room_levels.id|]]+']=[[|room_levels.price|]]';
		<!--/LIST:room_levels-->
		return query_string;
	}
	function buildReservationUrl(type){
		if(type=='RFA'){
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add'));?>&time_in=13:00&time_out=12:00&rooms='+get_query_string();
		} else if(type=='RFW'){
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN','reservation_type_id'=>2));?>&time_in=13:00&time_out=12:00&rooms='+get_query_string();
		} else {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN'));?>&time_in=13:00&time_out=12:00&rooms='+get_query_string();
		}
	}
	function selectRoomLevel(index,roomLevelName,roomLevelId, inputCount)
    {
		oldRoomLevelId = <?php echo Url::get('room_level_id_old')?Url::get('room_level_id_old'):0;?>;
		opener.document.getElementById('room_id_'+index).value = '';
		opener.document.getElementById('room_name_'+index).value = '#' + index; 
		opener.document.getElementById('room_level_name_'+index).value = roomLevelName;
		opener.document.getElementById('room_level_id_'+index).value = roomLevelId;
		opener.document.getElementById('time_in_'+index).value = '<?php echo CHECK_IN_TIME;?>';
		opener.document.getElementById('time_out_'+index).value = '<?php echo CHECK_OUT_TIME;?>';
		if(!opener.document.getElementById('arrival_time_'+index).value)
			opener.document.getElementById('arrival_time_'+index).value = '<?php echo [[=day=]].'/'.[[=month=]].'/'.[[=year=]];?>';
		if(!opener.document.getElementById('departure_time_'+index).value)
			opener.document.getElementById('departure_time_'+index).value = '<?php echo [[=end_day=]].'/'.[[=end_month=]].'/'.[[=year=]]?>';
		if(room_levels[oldRoomLevelId] && room_levels[roomLevelId]['id']!=room_levels[oldRoomLevelId]['id'])
		{
			opener.document.getElementById('price_'+index).value = number_format(room_levels[roomLevelId]['price'],2);
		}
		opener.updateRoomForTraveller(<?php echo URL::get('object_id');?>);
		window.close();
	}
	//jQuery(".room-bound").draggable();
	function buildReservationSearch()
	{
		if(jQuery('#code').val()!='')
		{
			url = '?page=reservation';
			url+='&code='+jQuery('#code').val();
		}else{
			url = '?page=guest_history';	
		}
		if(jQuery('#booking_code').val()!='')
		{
			url+='&booking_code='+jQuery('#booking_code').val();
		}
		if(jQuery('#customer_name').val()!='')
		{
			url+='&customer_name='+jQuery('#customer_name').val();
		}
		if(jQuery('#traveller_name').val()!='')
		{
			url+='&traveller_name='+jQuery('#traveller_name').val();
		}
		if(jQuery('#note').val()!='')
		{
			url+='&note='+jQuery('#note').val();
		}
		if(jQuery('#nationality_id').val()!='')
		{
			url+='&nationality_id='+jQuery('#nationality_id').val();
		}
		if(jQuery('#room_status').val()!='')
		{
			url+='&status='+jQuery('#room_status').val();
		}	
		window.open(url)
	}
	function myAutocomplete()
	{
		jQuery("#nationality_id").autocomplete({
			url:'r_get_countries.php',
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
	myAutocomplete();
	function setDateRepair(){
		
	}
  </script>
</script>
<!-- [[. time_in_is_more_than_current_time.]] -->