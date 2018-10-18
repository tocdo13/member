<style>
.simple-layout-middle{width:100%;}
#change_for_group_div
{
    opacity: 0.7;
}
#change_for_group_div:hover
{
    opacity: 1;
}
#change_for_group_div table tr td input, #change_for_group_div table tr td select{
    background: #ffffff;
}
#change_for_group_div table tr td span
{
}
#change_for_group_div table tr td
{
    padding-top: 5px;
    padding-bottom: 5px;
}
.booker{padding-left:12%;padding: 1% 12%;}
</style>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
var nationalities = <?php echo String::array2js([[=nationalities=]]);?>;
var vip_card_list = <?php echo String::array2js([[=vip_card_list=]]);?>;
</script>
<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<div id="input_group_#xxxx#"  onblur="updateRoomForTraveller('#xxxx#');" onmouseover="updateRoomForTraveller('#xxxx#');">
            <span class="multi-input" style="width:40px; text-align: center;">
			     <input name="check_box_#xxxx#" id="check_box_#xxxx#" type="checkbox" class="class_check_box" />		
            </span>
			<span class="multi-input" id="index_#xxxx#" style="width:45px;font-size:10px;color:#F90;"></span>
            <input  name="mi_reservation_room[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <input  name="mi_reservation_room[#xxxx#][house_status]" type="hidden" id="house_status_#xxxx#"/>
			<span class="multi-input" style="width:77px;">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:77px;" readonly="readonly" class="readonly"/>
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="text" id="room_level_id_#xxxx#" style="display:none;"/>
			</span>
			<span class="multi-input" style="width:68px;">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:48px;font-weight:bold;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="Check_Availblity(#xxxx#,input_count);" style="cursor:pointer;"/>
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:68px;background:#FFCC00;"/>
				<input  name="mi_reservation_room[#xxxx#][room_name_old]" type="hidden" id="room_name_old_#xxxx#"/>
				<input  name="mi_reservation_room[#xxxx#][room_id_old]" type="hidden" id="room_id_old_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:29px;" AUTOCOMPLETE=OFF /><i class="fa fa-male" style="font-size: 16px;"></i></span>
            <span class="multi-input" style="display: none;">
					<input  name="mi_reservation_room[#xxxx#][note]" type="text" id="note_#xxxx#" style="width:26px;" AUTOCOMPLETE=OFF /></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:48px;" AUTOCOMPLETE=OFF /><i class="fa fa-child" style="font-size: 16px;"></i></span>
            <!--trung add:tr.e<5-->
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child_5]" type="text" id="child_#xxxx#" style="width:33px;" AUTOCOMPLETE=OFF /><i class="fa fa-child" style="font-size: 16px;"></i></span>        
			<!--trung add:tr.e<5-->
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:62px;" type="text" id="price_#xxxx#" class="change_price price" onkeyup="count_price('#xxxx#',true);SetupAllotment('#xxxx#');" oninput="jQuery('#usd_price_#xxxx#').val(number_format(to_numeric(jQuery('#price_#xxxx#').val())/to_numeric(jQuery('#exchange_rate').val())));count_price('#xxxx#',true); jQuery('#price_#xxxx#').ForceNumericOnly().FormatNumber(); " />
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][usd_price]" style="width:62px;" type="text" id="usd_price_#xxxx#" class="price" oninput="jQuery('#price_#xxxx#').val(number_format(to_numeric(jQuery('#usd_price_#xxxx#').val())*to_numeric(jQuery('#exchange_rate').val())));count_price('#xxxx#',true);jQuery('#usd_price_#xxxx#').ForceNumericOnly().FormatNumber();" />
			</span>
			<!---<span class="multi-input">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate"> 
            </span>--->
            <?php if(User::can_admin($this->get_module_id('ReceptionCommission'),ANY_CATEGORY)){?>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][commission_rate]" style="width:38px;" type="text" id="commission_rate_#xxxx#" class="cms_rate price"/>
			</span>
			<span class="multi-input">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="commission_list_#xxxx#" class="select-rate" style="width: 20px;"/> 
            </span>
            <?php }else{?>
            <span class="multi-input" style="display: none;">
					<input  name="mi_reservation_room[#xxxx#][commission_rate]" style="width:38px; display: none;" type="text" id="commission_rate_#xxxx#" class="cms_rate price"/>
			</span>
			<span class="multi-input" style="display: none;">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="commission_list_#xxxx#" style="display: none;" class="select-rate"/> 
            </span>
            <?php } ?> 
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:45px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',true);SetupAllotment('#xxxx#');" maxlength="5" />
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:70px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',true);rate_code_price('#xxxx#');SetupAllotment('#xxxx#');" readonly="readonly" class="date-select"/>
			</span>
			<span class="multi-input" style="display: none;">
					<input  name="mi_reservation_room[#xxxx#][early_checkin]" type="checkbox" id="early_checkin_#xxxx#" title="[[.show_in_early_check_in_report.]]"/>
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_out]" style="width:45px;" type="text" id="time_out_#xxxx#" title="00:00" onchange="count_price('#xxxx#',true);SetupAllotment('#xxxx#');" maxlength="5" >
			</span>
			
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:70px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',true);updateRoomForTraveller('#xxxx#');rate_code_price('#xxxx#');SetupAllotment('#xxxx#');" readonly="readonly" class="date-select"/>
					<input  name="mi_reservation_room[#xxxx#][departure_time_old]" type="hidden" id="departure_time_old_#xxxx#">
			</span>
            <span class="multi-input" style="display: none;">
					<select  name="mi_reservation_room[#xxxx#][early_checkin]" id="early_checkin_#xxxx#" class="early_checkin" title="[[.early_checkin.]]" style="width:50px;">[[|verify_dayuse_options|]]</select>
                    <!-- manh them de lay gia khi tu dong late_checkin -->
                    <input  name="mi_reservation_room[#xxxx#][auto_late_checkin_price]" style="width:70px; display: none;" type="text" id="auto_late_checkin_price_#xxxx#" />
                    <!-- end manh -->
			</span>
            <span class="multi-input" style="display: none;">
					<select  name="mi_reservation_room[#xxxx#][late_checkout]" id="late_checkout_#xxxx#" class="late_checkout" title="[[.late_checkout.]]" style="width:50px;">[[|verify_dayuse_options|]]</select>
			</span>
			<span class="multi-input" style="width:70px;">
			<select  name="mi_reservation_room[#xxxx#][status]" id="status_#xxxx#"  style="width:69px;" class="reservation_status"
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
							//kimtan cmt
                            //if(!$('room_id_#xxxx#').value){
								//$('status_#xxxx#').value = 'BOOKED';
								//alert('Miss room number');
								//return false;
							//}
                            //end kimtan cmt
							$('arrival_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
							//if($('time_in_#xxxx#').value=='')
							{
								$('time_in_#xxxx#').value='<?php echo date('H:i',time())?>';
							}
							//$('time_out_#xxxx#').value='<?php echo date('H:i',time())?>';
						}
						count_price('#xxxx#',true);
						">
						<option value="BOOKED">Booked</option>
						<option value="CHECKIN" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'; if(Url::get('status')=='CHECKIN'){echo 'selected';}?>>Check in</option>
						<option value="CHECKOUT" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check out</optio
						>   <option value="CANCEL">Cancel</option>
					</select>
                    <input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#"/>
			</span>
            <span class="multi-input" style="display: none;">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" id="reservation_type_id_#xxxx#" style="width:58px;font-size:11px;">[[|reservation_type_options|]]</select>
			</span>
			<span class="multi-input" style="width: 25px; text-align: center;">
					<input name="mi_reservation_room[#xxxx#][confirm]" type="checkbox" id="confirm_#xxxx#" checked="checked"/>
			</span>
            <span class="multi-input" style="float:right;padding-right:5px;">
					<span style="display:none;" id="expand_#xxxx#"></span>
                    <img id="expand_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShorten('#xxxx#');">
			</span>
            <span class="multi-input" style="width: 23px; text-align: center;">
					<input  name="mi_reservation_room[#xxxx#][breakfast]" type="checkbox" id="breakfast_#xxxx#" checked="checked"/>
			</span>
            <!-- manh add allotment -->
            <span class="multi-input" style="width: 25px; text-align: center; <?php if(!USE_ALLOTMENT){ ?> display: none; <?php } ?>">
					<input name="mi_reservation_room[#xxxx#][allotment]" type="checkbox" id="allotment_#xxxx#" onclick="SetupAllotment('#xxxx#');" />
			</span>
            <!-- mand add allotment -->
            <!--giap.ln thay doi phan chon package thanh combobox -->
            <!--<span class="multi-input" style="width: 65px;">
					<input  name="mi_reservation_room[#xxxx#][package_sale_name]" type="text" id="package_sale_name_#xxxx#" onblur="autocomplete_package_sale(#xxxx#);" onfocus="autocomplete_package_sale(#xxxx#);" autocomplete="off" style="width: 65px;"/>
			</span>
            
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][package_sale_id]" type="hidden" id="package_sale_id_#xxxx#"/>
			</span>-->
            <span class="multi-input">
                <select id="package_sale_id_#xxxx#" name="mi_reservation_room[#xxxx#][package_sale_id]" style="width:80px;">
                [[|package_sale_options|]]
                </select>
			</span>
            
            <!--end giap.ln -->
            
            
			<span class="multi-input" style="width:30px;text-align:center;float:right;" id="delete_reservation_room_#xxxx#">
				<!--IF:right_cond(User::can_delete(false,ANY_CATEGORY))-->
                    <a href="#" align="left"  
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
                        " style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 21px;"></i></a>
                <!--/IF:right_cond-->
			</span>
			<br clear="all"/>
			<span id="mi_reservation_room_sample_#xxxx#" style="display:none;">
                <div class="room-extra-info" id="room_extra_info_#xxxx#" >
                    <div>
                        <span class="multi-input-extra">
                            <span class="label">[[.foc_room_charge.]]</span>
							<input  name="mi_reservation_room[#xxxx#][foc]" type="text" id="foc_#xxxx#" style="width:100px;">
                            <span class="label">[[.foc_all.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][foc_all]" style="width:15px;" value="1" type="checkbox" id="foc_all_#xxxx#" align="left" title="[[.foc_all_services.]]">
                        </span>
                        <span class="multi-input-extra">
                            <span class="label" style="width: 150px;">[[.discount_room_charge.]](%) <!---(% / <?php echo HOTEL_CURRENCY;?>)---></span>
                             <input  name="mi_reservation_room[#xxxx#][discount_after_tax]" style="width:15px;display:none;" value="1" type="checkbox" id="discount_after_tax_#xxxx#" align="left" title="[[.discount_percent_after_tax.]]">
                            <input  name="mi_reservation_room[#xxxx#][reduce_balance]" type="text" id="reduce_balance_#xxxx#" style="width:20px;" maxlength="3" onchange="count_price('#xxxx#',false);" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][reduce_amount]" type="text" id="reduce_amount_#xxxx#" style="width:68px;display: none;"  class="input_number" onkeyup="this.value = number_format(this.value);">
                        </span>
                       <span class="multi-input-extra">
                            <span class="label">[[.tax_rate_vat.]](%)</span>
                            <input style="width: 20px;" name="mi_reservation_room[#xxxx#][tax_rate]" maxlength="2" type="text" id="tax_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" value="<?php echo RECEPTION_TAX_RATE;?>"  <?php if(!User::can_delete($this->get_module_id('CheckIn'),ANY_CATEGORY) or !User::can_admin()){ echo 'readonly="readonly"'; echo 'style="width:35px;background:#FCFCFC;"';}else{  echo 'style="width:35px;"';}?> onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<span class="label">[[.service_rate.]](%)</span>
                            <input style="width: 20px;" name="mi_reservation_room[#xxxx#][service_rate]" maxlength="2" type="text" id="service_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);"  value="<?php echo RECEPTION_SERVICE_CHARGE;?>" <?php if(CAN_EDIT_CHARGE==0){ echo 'readonly="readonly"'; echo 'style="width:35px;background:#FCFCFC;"';}else{  echo 'style="width:35px;"';}?> onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                            <span class="label">[[.net_price.]]</span><input name="mi_reservation_room[#xxxx#][net_price]" style="width:15px;" value="1" type="checkbox" id="net_price_#xxxx#" align="left" title="[[.net_price.]]" <?php if(NET_PRICE==1){ echo 'checked';}?>>
                        </span>
						<span class="multi-input-extra" style="display:none;">
                            <span class="label">[[.deposit_amount.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][deposit]" type="text" id="deposit_#xxxx#" onchange="if(this.value){$('deposit_date_#xxxx#').value='<?php echo date('d/m/Y');?>';}else{$('deposit_date_#xxxx#').value='';} count_price('#xxxx#',false);"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                        </span>
                        
                    </div><br clear="all"/>
                    <div>
						<span class="multi-input-extra" style="display:none;">
							<span class="label">[[.vip_card_code.]]</span>
							<input name="mi_reservation_room[#xxxx#][vip_card_code]" type="text" id="vip_card_code_#xxxx#" class="reservation-vip-card-code" onchange="$('reduce_balance_#xxxx#').value = (vip_card_list[this.value]!='undefined')?vip_card_list[this.value]:'';count_price('#xxxx#',false);">
						</span>
                        <span class="multi-input-extra" style="display:none;">
                            <span class="label">[[.invoice_number.]]</span>
							<input  name="mi_reservation_room[#xxxx#][deposit_invoice_number]" type="text" id="deposit_invoice_number_#xxxx#">
                            <input  name="mi_reservation_room[#xxxx#][total_amount]" type="hidden" id="total_amount_#xxxx#" readonly="">
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
                    <div style="clear:both; display: none;">
                        <span class="multi-input-extra">
                            <!-- manh them chuc nang do not move -->
                            <span style="float: left;">[[.do_not_move.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][do_not_move]" class="do_not_move" style="width:15px; margin-right: 5px;" value="1" type="checkbox" id="do_not_move_#xxxx#" title="[[.do_not_move.]]"/>
                            <!-- end Manh -->
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
                    <div id="extra_bed" style="padding:5px 0px 5px 0px;clear:both;display: none;">
                    	<fieldset style="margin-left:20px;">
                        <legend class="title">[[.Extra_bed_baby_cot.]]</legend>
	                    <span class="multi-input-extra">
                           <span class="label">[[.extra_bed.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed]" type="checkbox" id="extra_bed_#xxxx#" style="width:15px;" value="1" onclick="count_price('#xxxx#',true);" />
                           <span class="label">[[.extra_bed_from_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_from_date]" type="text" id="extra_bed_from_date_#xxxx#" class="extra_bed_from_date" onchange="count_price('#xxxx#',true);" />
                           <span class="label">[[.extra_bed_to_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_to_date]" type="text" id="extra_bed_to_date_#xxxx#" class="extra_bed_to_date" onchange="count_price('#xxxx#',true);" />
                           <span class="label">[[.extra_bed_rate.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_rate]" type="text" id="extra_bed_rate_#xxxx#" class="extra_bed_rate" style="text-align:right" onkeyup="count_price('#xxxx#',true);" />
                        </span>
                        <br clear="all" />
                        <!-- Manh them luoc do gia cho extra-bed -->
                        <div id="price_schedule_bound_extra_bed_#xxxx#" style="float:left;width:100%;padding:5px 0px 5px 0px;margin:5px 0px 5px 0px;display:none;">
                        <span class="multi-input-extra">
                            <span>[[.price_schedule.]] [[.extra_bed.]]</span><br />
                            <span id="price_schedule_extra_bed_#xxxx#">                                
                            </span>
                        </span>
                        </div>
                        <br clear="all" />
                        <!-- end Manh -->
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
                    <div id="Remarks" style="padding:5px 0px 5px 0px;clear:both">
                        <span class="multi-input-extra">
                        		<span class="label">[[.Remarks_for_room.]]</span>
                                <textarea name="mi_reservation_room[#xxxx#][note]" id="note_#xxxx#" style="width: 80%;"></textarea>
                        </span>
                    </div> 
                    <br clear="all">
                    <div style="display:none;">&nbsp;&nbsp;&nbsp;&nbsp;
                    	<span id="invoice_#xxxx#" style="display:none;">
                            <a href="javascript:;" onclick="if($('id_#xxxx#').value!=''){window.open('?page=reservation&cmd=invoice&total_amount='+$('total_amount_#xxxx#').value+'&price='+$('price_#xxxx#').value+'&deposit='+$('deposit_#xxxx#').value+'&reduce_balance='+$('reduce_balance_#xxxx#').value+'&reduce_amount='+$('reduce_amount_#xxxx#').value+'&time_in='+$('time_in_#xxxx#').value+'&time_out='+$('time_out_#xxxx#').value+'&departure_time='+$('departure_time_#xxxx#').value+'&tax_rate='+$('tax_rate_#xxxx#').value+'&service_rate='+$('service_rate_#xxxx#').value+'&id='+$('id_#xxxx#').value+'');}"><img src="skins/default/images/edit.png" width="15"> [[.preview_invoice.]]</a> |
                            <a href="javascript:;" onclick="if($('id_#xxxx#').value!=''){window.open('?page=reservation&cmd=invoice&type=ROOM&total_amount='+$('total_amount_#xxxx#').value+'&price='+$('price_#xxxx#').value+'&deposit='+$('deposit_#xxxx#').value+'&reduce_balance='+$('reduce_balance_#xxxx#').value+'&reduce_amount='+$('reduce_amount_#xxxx#').value+'&time_in='+$('time_in_#xxxx#').value+'&time_out='+$('time_out_#xxxx#').value+'&departure_time='+$('departure_time_#xxxx#').value+'&tax_rate='+$('tax_rate_#xxxx#').value+'&service_rate='+$('service_rate_#xxxx#').value+'&id='+$('id_#xxxx#').value+'');}"><img src="skins/default/images/edit.png" width="15"> [[.preview_room_invoice.]]</a> |
                            <a href="javascript:;" onclick="if($('id_#xxxx#').value!=''){window.open('?page=reservation&cmd=invoice&type=SERVICE&total_amount='+$('total_amount_#xxxx#').value+'&price='+$('price_#xxxx#').value+'&deposit='+$('deposit_#xxxx#').value+'&reduce_balance='+$('reduce_balance_#xxxx#').value+'&reduce_amount='+$('reduce_amount_#xxxx#').value+'&time_in='+$('time_in_#xxxx#').value+'&time_out='+$('time_out_#xxxx#').value+'&departure_time='+$('departure_time_#xxxx#').value+'&tax_rate='+$('tax_rate_#xxxx#').value+'&service_rate='+$('service_rate_#xxxx#').value+'&id='+$('id_#xxxx#').value+'');}"><img src="skins/default/images/edit.png" width="15"> [[.preview_service_invoice.]]</a>
							<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="update" type="submit" value="[[.update.]]" class="update-room-info"> <a href="#" title="&#272;&#7875; &#273;&#7843;m b&#7843;o th&#244;ng tin ch&#237;nh x&#225;c b&#7841;n h&#227;y nh&#7845;n n&#250;t c&#7853;p nh&#7853;t tr&#432;&#7899;c khi xem h&#243;a &#273;&#417;n"><img src="packages/core/skins/default/images/buttons/question.gif" /></a><?php }?>
                        </span>
                    </div>
                 </div>
                <hr size="1" color="#CCCCCC">
             </span>
		</div>
	</span>
</span>
<!--start:KID them de chuyen doi gia-->
<input type="hidden" id="exchange_rate" value="[[|exchange_rate|]]" />
<!--end:KID them de chuyen doi gia-->
<div id="mask" class="mask">[[.Please wait.]]...</div>
<form name="AddReservationForm" method="post" onsubmit="">
<div style="text-align:center">
<div style="width:95%;margin-right:auto;margin-left:auto;">
<!---<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;" align="center">--->
<table cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;" align="center">
	<tr valign="top">
		<td align="left">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr height="40">
					<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-blue" style="font-size: 30px;"></i> [[.make_reservation.]]</td>
				  	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;" class="hidden_btn"><input name="save_button" type="button" readonly="true" value="[[.Save.]]" class="w3-button w3-orange w3-hover-orange" onclick="checkDirtyAll('save');" style="text-transform: uppercase;" /><input name="save" id="save" type="checkbox" style="display: none;"/></td><?php }?><!--AddReservationForm.submit();-->
					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;" class="hidden_btn"><input name="update_button" type="button" readonly="true" value="[[.Save_and_stay.]]" class="w3-button w3-blue w3-hover-blue" onclick="checkDirtyAll('update');" style="text-transform: uppercase;" /><input name="update" id="update" type="checkbox" value="[[.Save_and_stay.]]" style="display: none;"/></td><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;"><a href="<?php echo Url::build_current();?>"  class="w3-button w3-green w3-hover-green"  style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a></td><?php }?>
				</tr>
		  </table>
		</td>
	</tr>
	<tr valign="top">
	<td><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?>
	<table width="100%">
	<tr><td align="left">
			<fieldset style="background:#EFEFEF;margin-bottom:4px;">
			<legend class="w3-white" style="width: 190px; height: 24px; padding: 5px 5px 0px 5px; border: 1px solid orange;">[[.general_information.]]</legend>
                <div id="head_table">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">                
                 <tr valign="top">                 
                 <td align="right" nowrap>&nbsp;</td>
                 <td align="right">[[.booking_code.]]</td>
                 <td align="left" style="padding-left:10px;"><input name="booking_code" type="text" id="booking_code" style="width:170px;margin-bottom: 5px;"></td>
                 <td align="right">[[.booker.]]</td>                 
                 <td style="padding-left:10px;">
                 <input name="booker" type="text" id="booker" style="margin-bottom: 5px;width:170px; text-transform: uppercase; font-size: 13px;"/>
                 </td>
                 
                 <td align="right" nowrap>&nbsp;</td>
                 <td rowspan="8" width="40%">[[.note_for_tour_or_group.]]
                     <textarea name="note" id="note" style="width:99%;height:90px;"></textarea>
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
                   <td align="right">[[.customer.]] <i style="color: red;">(*)</i></td>
                    <td align="left" style="padding-left:10px;margin-bottom: 5px;"><input name="customer_name" type="text" id="customer_name" onfocus="Autocomplete();" autocomplete="off" style="width:170px;margin-bottom: 5px;">
                      <input name="customer_id" type="text" id="customer_id" class="hidden" />
                      <a class="w3-text-blue" href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')" style="text-decoration: none;">
                      <i class="fa fa-plus-square" style="font-size: 18px;"></i> [[.add_now.]]
                      </a> 
                      <!---<a class="w3-text-red" href="#" onClick="$('customer_name').value='';$('customer_id').value=0;jQuery('.price').attr('readonly',false);jQuery('.price').attr('class','price');" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>--->
                    </td>
                   <td align="right">[[.phone_booker.]]</td>
                    <td style="padding-left:10px;">
                    <input name="phone_booker" type="text" id="phone_booker" style="margin-bottom: 5px;width:170px;"/>
                    </td> 
                   
                   <td align="right" class="label">&nbsp;</td>
                    <td align="right" class="label">&nbsp;</td>
                 </tr>
                 <tr valign="top" style="display: none;">
                    
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.tour.]] / [[.group.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="tour_name" type="text" id="tour_name" style="width:170px;margin-bottom: 5px;" readonly="" class="readonly">
                      <input name="tour_id" type="text" id="tour_id" class="hidden" />
                      <a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"></td>
                    <td align="right" width="10%" class="label">&nbsp;</td>
                 </tr>
                 <tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    
                    <?php if(RATE_CODE==1 && User::can_admin($this->get_module_id('RateCodeAdmin'),ANY_CATEGORY)){
                        ?>
                      <!--giap.ln them truong hop cho chon rate code -->
                      <td align="right"><label class="w3-text-blue" for="is_rate_code" style="margin-left: 10px;">[[.use_rate_code.]]</label></td>
                      <td>
                      <input name="is_rate_code" type="checkbox" id="is_rate_code" onclick="using_rate_code(this);" style="margin-left: 10px;" />
                      </td> 
                      <!--end giap.ln -->
                      <?php 
                      }
                      else
                      {
                      ?>
                         <td align="right" style="display: none;"><label class="w3-text-blue" for="is_rate_code" style="margin-left: 10px;">[[.use_rate_code.]]</label></td>
                          <td  style="display: none;">
                          <input name="is_rate_code" type="checkbox" id="is_rate_code" onclick="using_rate_code(this);" style="margin-left: 10px;" />
                          </td> 
                      <?php  
                      }
                      ?>
                                        
                    <td align="right">[[.email.]]</td>
                    <td style="padding-left:10px;">
                    <input name="email_booker" type="email" id="email_booker" style="width:170px;"/>
                    </td>
                    
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
					<td align="left" style="padding-left:10px;"><input name="payment" type="text" id="payment" style="width:170px;margin-bottom: 5px;">
					  USD</td>
					<td align="right"></td>
					<td align="left"></td>
                  </tr>
                  <tr valign="top">
                    <td align="right" nowrap></td>                    
                    <td align="right">&nbsp;</td>
                    <td align="left"></td>
                  </tr>
                  <tr valign="top">
                    <td>&nbsp;</td>
                    <td align="right">[[.payment_type1.]]</td>
                    <td align="left" style="padding-left:10px;">
                       <select name="payment_type1" id="payment_type1" style="width:170px; height: 20px;"></select> 
                    </td>
                    <td align="right">[[.color_of_group.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="color" type="text" id="color" style="width:148px;margin-bottom: 5px;"><span onclick="TCP.popup($('color'));" class="color-select-button" title="[[.select_color.]]"><img src="packages/core/skins/default/images/color_picker.gif" width="18" style="margin-left: 4px;"/></span></td>
                  </tr>
            </table>
            </div>
        </fieldset>
		<fieldset style="background:#E5E5E4" id="reservation_room">
			<legend class="w3-white"style="width: 190px; height: 24px; padding: 5px 5px 0px 5px; border: 1px solid orange;">[[.reservation_room.]]&nbsp;(<span style="color: red; font-size: 20px;" id="count_number_of_room">1</span> [[.room.]])</legend>
			<span id="mi_reservation_room_all_elems">
				<span>
                    <span class="multi-input-header" style="width:40px;"><input name="check_box_all" id="check_box_all" type="checkbox" onclick="fun_check_box_all();"  />All</span>
					<span class="multi-input-header" style="width:78px;">[[.room_level.]]</span>
					<span class="multi-input-header" style="width:68px;">[[.room_id.]]</span>
                    <span class="multi-input-header" style="width:40px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:32px; display: none;">[[.note.]]</span>
                    <span class="multi-input-header" style="width:58px;">[[.child.]]</span>
                    <span class="multi-input-header" style="width:45px;">[[.child_5.]]</span>
					<span class="multi-input-header" style="width:62px;">[[.room_rate.]]</span>
                    <span class="multi-input-header" style="width:62px;">[[.usd_price.]]</span>
					<!---<span class="multi-input-header"  style="width:12px;background-image: url(packages/core/skins/default/images/buttons/rate.jpg)"></span>--->
                    <?php if(User::can_admin($this->get_module_id('ReceptionCommission'),ANY_CATEGORY)){ ?>
                    <span class="multi-input-header" style="width:59px;">[[.cms.]]</span>
                    
					<?php } ?>
					<span class="multi-input-header" style="width:45px;">[[.time_in.]]</span>
                    <span class="multi-input-header" style="width:70px;">[[.arrival_time.]]</span>
					<span class="multi-input-header" style="width:16px; display: none;">
					<input name="all_early_checkin" type="checkbox" id="all_early_checkin" onclick="check_early_checkin();" /></span>
					<span class="multi-input-header" style="width:45px;">[[.time_out.]]</span>					
					<span class="multi-input-header" style="width:70px;">[[.departure_time.]]</span>
                    <span class="multi-input-header" style="width:46px; display: none;" title="[[.early_checkin.]]">E.I</span>
                    <span class="multi-input-header" style="width:46px; display: none;" title="[[.late_checkout.]]">L.O</span>
					<span class="multi-input-header" style="width:68px;">[[.status.]]</span>
					<span class="multi-input-header" style="width:50px; display: none;">[[.reservation_type.]]</span>
					<span class="multi-input-header" style="width:25px;" title="[[.confirm.]]">[[.CF.]]</span>
                    <span class="multi-input-header" style="width:25px;" title="[[.breakfast.]]">[[.BF.]]</span>
                    <span class="multi-input-header" style="width:25px; <?php if(!USE_ALLOTMENT){ ?> display: none; <?php } ?>" title="[[.allotment.]]">[[.ALM.]]</span>
                    <span class="multi-input-header" style="width:80px;">Package</span>
					<span class="multi-input-header" style="float:right;border:0px;background:none;">&nbsp;</span>
					<span id="expand_all_span" style="float:right;"><img id="expand_all" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandAll();"></span>
					<br clear="all"/>
				</span>
			</span>
			<br clear="all"/>
			<input class="w3-button w3-gray w3-hover-cyan w3-hover-text-white" type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');updateInput(input_count);AddInput(input_count);buildRateList(input_count);buildCommissionList(input_count); "/>
            <br clear="all"/>
            <br clear="all"/>
<!---THAY DOI CHO GROUP--->
            <hr style="margin: 20px auto 0px auto; padding: 0px;" /><hr style="margin: 0px auto 5px auto; padding: 0px;" />
            <fieldset id="change_for_group_div" style="width: 99%; margin: 0px auto; background: #F2F4CE; border: 1px dashed #171717;">
                <legend style="font-weight: bold; text-transform: uppercase;">[[.change_for_group.]]</legend>
                <table style="width: 100%;">
                    <tr style="background: #fac73e;">
                        <td style="width: 175px; padding-left: 25px;"><span style="width: 70px; ">[[.room_type.]]</span></td>
                        <td style="width: 125px;"><span style="width: 40px;">[[.adult.]]</span></td>
                        <td style="width: 65px; "><span style="width: 70px; ">[[.price.]]</span></td>
                        <td style="width: 65px; "><span style="width: 70px; ">[[.usd_price.]]</span></td>
                        <td style="width: 55px; "><span style="width: 55px; "></span></td>
                        <!-- Oanh add  -->
                        <td style="width: 38px; "><span style="width: 45px; ">[[.time_in.]]</span></td>
                        <td style="width: 76px; "><span style="width: 70px; ">[[.arrival_time.]]</span></td>
                        <td style="width: 38px; "><span style="width: 50px; ">[[.time_out.]]</span></td>
                        <!-- End Oanh add-->                        
                        <td style="width: 76px; "><span style="width: 70px; ">[[.departure_time.]]</span></td>
                        <!---<td style="width: 50px; "><span style="width: 50px; display: none;">E.I</span></td>
                        <td style="width: 50px; "><span style="width: 50px; display: none;">L.O</span></td>--->
                        <td><span>[[.status.]]</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid gray;">
                        <td style="cursor: pointer; padding-left: 25px;" onclick="fun_Check_Availblity(input_count,'<?php echo Url::get('cmd');?>');"><i class="fa fa-refresh w3-text-blue" style="font-size: 18px;"></i> [[.choose_room_type.]]</td>
                        <td style=""><input type="text" id="add_all" class="addall" onkeyup="changeAdultFunc();" style="text-align:right; width: 30px;" /></td>
                        <td style=""><input type="text" id="change_all_price" class="input_number" autocomplete="OFF" onkeyup="changePriceFunc();" style="width:60px; text-align:right;" /></td>
                        <td style=""><input type="text" id="change_all_usd_price" class="input_number" autocomplete="OFF" onkeyup="changeUsdPriceFunc();" style="width:60px; text-align:right;" /></td>
                        <td style="width: 55px; "><span style="width: 55px; "></span></td>
                        <!-- Oanh add-->
                        <td style=""><input type="text" style="width: 45px;" id="all_time_in" title="00:00"  class="hour-input" onkeyup="changeTime(this.value,'TI');"/></td> 
                        <td style="width: 70px;"><input type="text" style="width: 70px; height: 21px;" id="all_arrival_time" class="date-input" onchange="changeAllTime(this.value,'AT');" /></td>
                        <td style=""><input type="text" style="width: 50px;" id="all_time_out" title="00:00" class="hour-input" onkeyup="changeTime(this.value,'TO');" /></td>
                        <!-- End Oanh add-->                        
                        <td style=""><input type="text" style="width: 70px; height: 21px;" id="all_departure_time" class="date-input" readonly="readonly" onchange="changeAllTime(this.value,'DT')" /></td>
                        <!---<td style=""><select name="all_early_checkin" style="width: 50px; display: none;" id="all_early_checkin"  onchange="fun_change_all_ei(this.value);">[[|verify_dayuse_options|]]</select></td>
                        <td style=""><select name="all_late_checkout" style="width: 50px; display: none;" id="all_late_checkout" onchange="fun_change_all_lo(this.value);">[[|verify_dayuse_options|]]</select></td>--->
                        <td><select  name="change_all_status" id="change_all_status" onchange="changeAllStatus(this.value,input_count);" style="width: 80px; height: 20px;"><option value="">[[.status.]]</option><option value="CHECKIN">CHECKIN</option><option value="CHECKOUT">CHECKOUT</option></select></td>
                    </tr>
                    <tr>
                        <td colspan="14" style="text-align: center;">
                        [[.cut_off_day.]]: <input name="cut_of_date" type="text" id="cut_of_date" style="width:65px;" class="date-input" /> 
                        | 
                        [[.net_price.]]: <input name="change_all_net_price" type="checkbox" id="change_all_net_price" onclick="fun_change_all_net();" /> 
                        | 
                        [[.discount_for_group.]]: <input type="text" id="discount_for_group" class="input_number" onkeyup="changeAllDiscount(this.value);" style="width:30px;"/>(%)
                        </td>
                    </tr>
                    <!-- Manh them doi gia theo ngay cho toan doan -->
                    <tr>
                        <td colspan="14" style="text-align: center;">
                            <fieldset style="width: 100%; text-align: center;">
                                <legend style="text-transform: uppercase;">[[.change_price_in_date_on_group.]]</legend>
                                [[.select_room_level.]]:
                                <select onchange="func_change_price_in_date();" id="room_level_group" name="room_level_group">[[|room_level_options|]]</select>
                                [[.select_date.]]:
                                <input onchange="func_change_price_in_date();" style="width: 80px;" id="date_group" name="date_group" type="text" />
                                [[.input_price.]]:
                                <input onkeyup="func_change_price_in_date();" style="width: 80px;"id="price_group" name="price_group" type="text" />
                            </fieldset>
                        </td>
                    </tr>
                    <!-- end manh -->
                </table>
            </fieldset>
			<!--|[[.change_all.]] [[.reservation_type.]] <select  name="change_all_reservation_type" id="change_all_reservation_type" onchange="changeAllReservationType(this.value);">[[|reservation_type_options|]]</select>-->
            
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
<span style="display: none;">[[.commission.]](%)</span>
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
<br/>
<br/>
<br/>
<br/>
<br/>
<script>

jQuery("#date_group").datepicker();
var d = '<?php echo date('d/m/Y')?>';
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;


var readOnly = 'readonly="readonly"';
jQuery("#all_arrival_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#cut_of_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#all_departure_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
//Oanh add 
jQuery(".hour-input").mask("99:99");
//End Oanh


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
jQuery(document).ready(function(){
    for(var i=101;i<=input_count;i++)
    {
        jQuery('#usd_price_'+i).val(number_format(to_numeric(jQuery('#price_'+i).val())/to_numeric(jQuery('#exchange_rate').val())));
    }
    
})
//jQuery('#change_all_price').ForceNumericOnly().FormatNumber();
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
//document.onkeydown= handleKeyPress;
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
function updateInput(input_count)
{
    
	jQuery('#price_'+input_count).ForceNumericOnly().FormatNumber();
	if(CAN_CHANGE_PRICE || CAN_ADMIN ||(!CAN_ADMIN && jQuery("#status_"+i).val() !='CHECKIN')){
	    jQuery('.date-select').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' }); 
	}
    jQuery('#adult_'+input_count).val('1');
    jQuery('#arrival_time_'+input_count).val('<?php echo date('d/m/Y');?>');
    jQuery('#departure_time_'+input_count).val('<?php echo date('d/m/Y',time()+86400);?>');
    
	if(CAN_CHANGE_PRICE || CAN_ADMIN ){
	    //jQuery('.date-select').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });   
		jQuery('#price_'+input_count).attr('readonly',false);
		jQuery('#price_'+input_count).removeClass('readonly');
	}else{
		<?php //if(!User::can_admin(false,ANY_CATEGORY)){?>
		jQuery('#price_'+input_count).attr('readonly',true);
		jQuery('#price_'+input_count).addClass('readonly');
		<?php //}?>
	}
}
function AddInput(input_count)
{
    <?php if(User::can_admin(false,ANY_CATEGORY)){?>
	jQuery('#arrival_time_'+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
	
	jQuery('#time_in_'+input_count).mask('99:99');
	<?php } ?>
    jQuery('#departure_time_'+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
    jQuery('#time_out_'+input_count).mask('99:99');
    jQuery('#net_price_'+input_count).attr('checked',<?php if(NET_PRICE==1){echo 'true';}else{ echo 'false';}?>);
	jQuery('#tax_rate_'+input_count).val(<?php echo RECEPTION_TAX_RATE;?>);
	jQuery('#service_rate_'+input_count).val(<?php echo RECEPTION_SERVICE_CHARGE;?>);
	jQuery('#reservation_type_id_'+input_count).val(1);
//    console.log(input_count);
    jQuery('.reservation_status').each(function(){
    
    <?php if(!User::can_admin(false,ANY_CATEGORY)){?>
       
    		  if(jQuery(this).val() == 'CHECKIN'){
    			jQuery('#time_in_'+input_count).attr('readonly',true);
    			jQuery('#time_in_'+input_count).className = 'readonly';
    			jQuery('#arrival_time_'+input_count).attr('readonly',true);
    			jQuery('#arrival_time_'+input_count).className = 'readonly';
     		    
            }

        
    <?php } ?>
   
});    
}
var roomCount = to_numeric($('count_number_of_room').innerHTML);

jQuery('.reservation_status').each(function(){
    <?php if(!User::can_admin(false,ANY_CATEGORY)){?>
        for(var i=101;i<=input_count;i++){
    		  if(jQuery(this).val() == 'CHECKIN'){
    			jQuery('#time_in_'+i).attr('readonly',true);
    			jQuery('#time_in_'+i).className = 'readonly';
    			jQuery('#arrival_time_'+i).attr('readonly',true);
    			jQuery('#arrival_time_'+i).className = 'readonly';
     		    
            }
	    }
        
    <?php } ?>
   
});

for(var i=101;i<=input_count;i++)
{
	if(jQuery("#birth_date_"+i)){
		jQuery("#birth_date_"+i).mask("99/99/9999");
	}
	//updateInput(i);
    
	if(jQuery("#arrival_time_"+i))
    {
		roomCount++;
        if(CAN_CHANGE_PRICE || CAN_ADMIN ||(!CAN_ADMIN && jQuery("#status_"+i).val() !='CHECKIN')){ // 
            //jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
        }
        if(!CAN_ADMIN && jQuery("#status_"+i).val() !='')
        {
            jQuery("#arrival_time_"+i).datepicker({minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4'});
        }
        else
        {
            jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
        }
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
      //jQuery("#price_"+i).val(0);  // Gan gia phong = 0 khi bat dau vao dat phong
		/**for(var k in room_level){
			if(jQuery("#room_level_id_"+i).val()==k){
				jQuery("#price_"+i).val(number_format(room_level[k]));
			}
		}**/
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
    /** manh them allotment **/
    <?php if(USE_ALLOTMENT){ ?>
    SetupAllotment(i);
    <?php }?>
    /** manh them allotment **/
}
/** manh them allotment **/
function SetupAllotment(index){
    <?php if(USE_ALLOTMENT){ ?>
    count_price(index,true);
    if(document.getElementById('allotment_'+index).checked){
        if(jQuery("#room_level_id_"+index).val().trim()=='' || jQuery("#customer_id").val().trim()==''){
            document.getElementById('allotment_'+index).checked = false;
            return false;
        }
        $arrival_time = jQuery("#arrival_time_"+index).val();
        $departure_time = jQuery("#departure_time_"+index).val();
        $room_level_id = jQuery("#room_level_id_"+index).val();
        $customer_id = jQuery("#customer_id").val();
        jQuery.ajax({
            async: false,
    		url:"form.php?block_id="+<?php echo Module::block_id();?>,
    		type:"POST",
    		data:{status:'SETUP_ALLOTMENT',room_level_id:$room_level_id,departure_time:$departure_time,arrival_time:$arrival_time,customer_id:$customer_id},
    		success:function(html)
            {
                data = jQuery.parseJSON(html);
                for(var $j in data){
                    if( document.getElementById("change_price_"+$j+"_"+index) ){
                        document.getElementById("change_price_"+$j+"_"+index).value = number_format(data[$j]['rate']);
                    }
                }
    		}
        });       
    }
    <?php }else{ ?>
        document.getElementById('allotment_'+index).checked = false;
        return false;
    <?php }?>
}
/** manh them allotment **/
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
function isEmail(){
	var isValid = false;
	var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(regex.test(jQuery('#email_booker').val())) {
		isValid = true;
	}
	return isValid;
}
function checkDirtyAll(key)
{
    if(jQuery('#customer_id').val()=='')
    {
        alert("chưa chọn nguồn khách vui lòng kiểm tra lại");
        return false;
    }
    /**start kieu check chon nguon khĂ¡ch  **/
    if(jQuery('#customer_id').val()==''){
        alert('[[.select_customer_please.]]');
        return false;
    }
    /**start kieu check chon nguon khĂ¡ch  **/
    /**start kieu check email hop le **/
    if(jQuery('#email_booker').val()!=''&&isEmail()==false){
        alert('email khï¿½ng dï¿½ng d?nh d?ng');
        return false;
    }
    /** end kieu check email hop le **/
    var $Rooms = '';
    for(var index=101; index<=input_count;index++)
    {
        if($('id_'+index) && $('id_'+index).value!=undefined)
        {
            var id_check = $('id_'+index).value;
            var room_id_check = $('room_id_'+index).value;
            var room_name_check = $('room_name_'+index).value;
            var status_check = $('status_'+index).value;
            var in_date_check = $('arrival_time_'+index).value
            
            if(status_check=='CHECKIN' && room_id_check!='' && ( (!mi_reservation_room_arr) || (mi_reservation_room_arr && mi_reservation_room_arr[id_check]==undefined) || (mi_reservation_room_arr && mi_reservation_room_arr[id_check]!= undefined && mi_reservation_room_arr[id_check]['status']=='BOOKED') ))
            {
                if($Rooms=='')
                {
                    $Rooms = index+","+room_id_check+","+in_date_check+","+id_check;
                }
                else
                {
                    $Rooms += "|"+index+","+room_id_check+","+in_date_check+","+id_check;
                }
            }
        }
    }
    
    //console.log($Rooms);
    
    if($Rooms=='')
    {
        if(key=='save')
        {
            checkSave('save');
        }
        else
        {
             checkSave('update'); 
        }
        return true;
    }
    else
    {
        if (window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                //console.log(otbjs);
                var $mess = '';
                for(var i in otbjs)
                {
                    if(to_numeric(otbjs[i]['status'])==404)
                    {
                        $mess += '[[.room.]] '+$('room_name_'+otbjs[i]['index']).value+' [[.dirting.]]! \n';
                        $('status_'+otbjs[i]['index']).value = 'BOOKED';
                    }
                }
                
                if($mess!='')
                {
                    alert($mess);
                    return false;
                }
                else
                {
                    //console.log(111);
                    if(key=='save')
                    {
                        checkSave('save');
                    }
                    else
                    {
                         checkSave('update'); 
                    }
                    return true;
                }
            }
        }
        xmlhttp.open("GET","check_dirty.php?data=check_dirty_all&rooms="+$Rooms,true);
        xmlhttp.send();
    }
}
function checkDirty(index,messeger)
{
    // neu khong ton tai messeger thi cho thong bao bang alert luon va tra ve true | flase
    // neu ton tai thi chi tra ve true | false
    
    var id_check = $('id_'+index).value;
    var room_id_check = $('room_id_'+index).value;
    var room_name_check = $('room_name_'+index).value;
    var status_check = $('status_'+index).value;
    var in_date_check = $('arrival_time_'+index).value
    
    if(status_check=='CHECKIN' && room_id_check!='' && ( (!mi_reservation_room_arr) || (mi_reservation_room_arr && mi_reservation_room_arr[id_check]==undefined) || (mi_reservation_room_arr && mi_reservation_room_arr[id_check]!= undefined && mi_reservation_room_arr[id_check]['status']=='BOOKED') ))
    {
        if (window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                if(to_numeric(text_reponse)==404)
                {
                    if(!messeger)
                        alert('[[.room.]] '+room_name_check+' [[.dirting.]]!');
                    
                    $('status_'+index).value = 'BOOKED';
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
        
        xmlhttp.open("GET","check_dirty.php?data=check_dirty&room_id="+room_id_check+"&in_date="+in_date_check+"&rr_id="+id_check,true);
        xmlhttp.send();
        
    }
}
function changePriceFunc()
{ 
        /*
        var change_class = '.change_price_<?php echo date('d_m_Y');?>';
        jQuery(change_class.toString()).val(number_format(to_numeric(jQuery('#change_all_price').val())));
        */
        for(var i=101;i<=input_count;i++){
            if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
            {
                
                jQuery("#usd_price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val()/jQuery('#exchange_rate').val())));    
                jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val()))); 
                count_price(i,true);
            }           
        }
	jQuery("#change_all_usd_price").val(number_format(to_numeric(jQuery('#change_all_price').val()/jQuery('#exchange_rate').val())));
}
//start:KID them ham doi gia USD cho tat ca cac phong
function changeUsdPriceFunc()
{
       
    for(var i=101;i<=input_count;i++){
    if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
    {
        jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val()*jQuery('#exchange_rate').val())));     
        jQuery('#usd_price_'+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val())));
        count_price(i,true);
    }    
    }
    jQuery("#change_all_price").val(number_format(to_numeric(jQuery('#change_all_usd_price').val()*jQuery('#exchange_rate').val())));
}
//end:KID them ham doi gia USD cho tat ca cac phong
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
function update_traveller_id(index)
{
    prev = index-1;
    reservation_type = jQuery("#reservation_type_id_"+prev).val();
    jQuery("#reservation_type_id_"+index).val(reservation_type);
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
        alert('Ngay` d?n ph?i l?n hon ho?c b?ng nï¿½y hi?n t?i...!');
        $('arrival_time_'+index).value = d;
    }
}

//Giap add 14-05-2014
function Autocomplete()
{
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item){
            document.getElementById('customer_id').value = item.data[0];
var is_rate_code = document.getElementById('is_rate_code');
                    if(is_rate_code.checked)
                    {
                        
                        get_price_rate_code(item.data[0],101);
                        /**for(var i=101;i<=input_count;i++)
                        {
                            var room_level_id = document.getElementById('room_level_id_'+ i).value;
                            //lay ra arrival_time & departure_time 
                            var arrival_time = document.getElementById('arrival_time_' + i).value;
                            var departure_time = document.getElementById('departure_time_' + i).value;
                            
                            get_price_rate_code(room_level_id,item.data[0],arrival_time,departure_time,i);
                            
                        }**/
                    }
        }
    }) ;
}

function autocomplete_package_sale(index)
{
    var arrival_time = jQuery("#arrival_time_" + index).val();
    jQuery("#package_sale_name_" + index).autocomplete({
         url: 'get_package_sale.php?arrival_time='+ arrival_time,
         onItemSelect: function(item) {
            document.getElementById('package_sale_id_' + index).value = item.data[0];
        }
    }) ;
}
//end add
function fun_check_box_all()
{
    if(document.getElementById("check_box_all").checked==true)
    {
        jQuery(".class_check_box").attr("checked","checked");
    }
    else
    {
        jQuery(".class_check_box").removeAttr("checked");
    }
}
function changeAdultFunc()
{
    for(var i=101;i<=input_count;i++){
    if(document.getElementById("check_box_"+i).checked==true  || chec_box_tick(input_count)==true)
    {
        jQuery('#adult_'+i).val(to_numeric(jQuery('#add_all').val()));  
    }
        
    }
        //jQuery('.pp').val(to_numeric(jQuery('#add_all').val()));        
}

var checksave=0; 
function checkSave(key)
{
    jQuery("#"+key).attr('checked','checked');
    jQuery('.hidden_btn').css('display', 'none');
    AddReservationForm.submit();
    
}
function func_change_price_in_date()
{
    
    if(jQuery("#date_group").val()!='' && jQuery("#price_group").val()!='')
    {
        if(jQuery("#room_level_group").val()=='ALL')
        {
            for(var i=101;i<input_count;i++)
            {
                if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                {
                    document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(to_numeric(jQuery("#price_group").val()));
                }
                    
            }
        }
        else
        {
            for(var i=101;i<input_count;i++)
            {
                if(jQuery("#room_level_name_"+i).val()==jQuery("#room_level_group").val())
                {
                    if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                    {
                        document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(to_numeric(jQuery("#price_group").val()));
                    }
                        
                }
                else
                {
                    if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                    {
                        document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(mi_reservation_room_arr[jQuery("#id_"+i).val()]['change_price_arr'][jQuery("#date_group").val()]);
                    }
                }
            }
        }
    }
}
/**giap.ln lay ra gia theo nguon khach**/
function get_price_rate_code(customer_id,index)
{

    var room_level_id = document.getElementById('room_level_id_'+ index).value;
    //lay ra arrival_time & departure_time 
    var arrival_time = document.getElementById('arrival_time_' + index).value;
    var departure_time = document.getElementById('departure_time_' + index).value;
    //console.log(customer_id + '--' + room_level_id + '--' + arrival_time + '--' + departure_time);
    
    var is_rate_code = document.getElementById('is_rate_code');
    var is_rate = 0;
    if(is_rate_code.checked)
    {
        is_rate = 1;
    }
    jQuery.ajax({
     async : false,
     url:"get_customer_search_fast.php?",
     type:"POST",
     data:{rate_code:'1',room_level_id:room_level_id,customer_id:customer_id,arrival_time:arrival_time,departure_time:departure_time,is_rate:is_rate},
     success:function(data)
         {
                //var text_reponse = xmlhttp.responseText;
                
                var items = JSON.parse(data);
                var price_common = items['price_common'];
                var exchange_rate = jQuery('#exchange_rate').val();
                if(document.getElementById('status_' + index).value!='CHECKOUT') {
                    document.getElementById('price_' + index).value=number_format(price_common);
                    document.getElementById('usd_price_' + index).value = number_format(price_common/exchange_rate);
                    
                    count_price(index,false);
                    change_price_rate_code(items,index,false);
                }
    
                if(index<input_count)
                {
                    index++;
                    get_price_rate_code(customer_id,index);
                }
         }
     });
}
function rate_code_price(i)
{
    var is_rate_code = document.getElementById('is_rate_code');
    if(is_rate_code.checked)
    {
        var customer_id = document.getElementById('customer_id').value;

        if(customer_id!='')
        {
            get_price_rate_code(customer_id,101);
        }
    }
    
    
}

function using_rate_code(obj)
{    
    if(obj.checked)
    {
        //su dung rate code 
        var customer_id = document.getElementById('customer_id').value;
        //console.log(input_count);
        if(customer_id!='')
            get_price_rate_code(customer_id,101);
            
         /** manh them allotment **/
         for(var i=101;i<=input_count;i++){
            if(jQuery('#id_'+i).val()==''){
                SetupAllotment(i);
            }
         }
         /** manh them allotment **/
        /**for(var i=101;i<=input_count;i++)
        {
            var room_level_id = document.getElementById('room_level_id_'+ i).value;
            //lay ra arrival_time & departure_time 
            var arrival_time = document.getElementById('arrival_time_' + i).value;
            var departure_time = document.getElementById('departure_time_' + i).value;
            get_price_rate_code(room_level_id,customer_id,arrival_time,departure_time,i);
        }**/
    }
    else
    {
        //update_old_price();
        //khong su dung rate code se khong thay doi gia da khai bao 
        for(var i=101;i<=input_count;i++)
        {
            if(jQuery('#id_'+i).val()==''){
                update_price(i);
                /** manh them allotment **/
                 SetupAllotment(i);
                 /** manh them allotment **/
                //document.getElementById('price_' + i).value=0;
                //document.getElementById('usd_price_' + i).value =0;
                /**
                var arrival_time = document.getElementById('arrival_time_' + i).value;
                var departure_time = document.getElementById('departure_time_' + i).value;
                var arr_arrival = arrival_time.split('/');
                var arr_departure = departure_time.split('/');
                var arrival_date = new Date();
                arrival_date.setFullYear(arr_arrival[2],arr_arrival[1]-1,arr_arrival[0]);
                arrival_date.setHours(0,0,0,0);
    
                var departure_date = new Date(); 
                departure_date.setFullYear(arr_departure[2],arr_departure[1]-1,arr_departure[0]);
                departure_date.setHours(0,0,0,0);
                var d_time = departure_date.getTime()/1000;
                var a_temp = arrival_date;
                var a_time = a_temp.getTime()/1000;
                
                while(a_time<d_time)
                {
                    var str_day = a_temp.getDate() + '/' + (a_temp.getMonth()+1) + '/' + a_temp.getFullYear();
                    
                    document.getElementById('change_price_'+str_day+'_'+i).value =0;
                    a_time +=86400;
                    a_temp.setTime(a_time*1000);
                    
                }**/
            }
        }
    }
}
/***giap.ln thay doi chinh sach gia hang phong theo rate code tung nguon khach 
***/
/** start kieu update gia khi khong su dung rate code **/
  var key_price=101;
    for(var key in mi_reservation_room_arr){
      mi_reservation_room_arr[key]['key_price']=key_price;
      key_price++; 
    }
 function update_old_price(){
    for(var key in mi_reservation_room_arr){
            jQuery('#price_'+mi_reservation_room_arr[key]['key_price']).val(mi_reservation_room_arr[key]['price']);
            jQuery('#usd_price_'+mi_reservation_room_arr[key]['key_price']).val(mi_reservation_room_arr[key]['usd_price']);
            count_price(mi_reservation_room_arr[key]['key_price'],true);
     }
 }   
 function update_price(index){
     var room_level_id = document.getElementById('room_level_id_'+ index).value;
     jQuery.ajax({
     async: false,
     url:"get_customer_search_fast.php?",
     type:"POST",
     data:{cmd:'get_room_level_price',room_level_id:room_level_id,get_room_level_price:1},
     success:function(html)
     {
         var exchange_rate = jQuery('#exchange_rate').val();
         document.getElementById('price_' + index).value=number_format(html);
         document.getElementById('usd_price_' + index).value = number_format(html/exchange_rate);
         count_price(index,true); 
         setTimeout(function(){
         jQuery("#mice_loading").css('display','none');
         }, 1000);
     }
     });
 }
/** end kieu update gia khi khong su dung rate code **/

function change_price_rate_code(items,index,flag)
{
    for(var i in items)
    {
        //console.log(i);
        if(i!='price_common' && i!='is_rate_code')
        {
            if(flag)
                window.opener.document.getElementById('change_price_'+i+'_'+index).value = number_format(items[i]['price']);
            else
            {
                if(document.getElementById('change_price_'+i+'_'+index))
                document.getElementById('change_price_'+i+'_'+index).value = number_format(items[i]['price']);
            }
        }
    }
}

//end giap.ln 
</script>
<!-- [[. time_in_is_more_than_current_time.]] -->