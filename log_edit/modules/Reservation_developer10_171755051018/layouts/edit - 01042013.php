<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<span id="input_group_#xxxx#">
		<div id="reservation_room_bound_#xxxx#" class="reservation_room_bound">
			<span class="multi-input" id="index_#xxxx#" style="width:39px;font-size:13px;color:#F90; font-weight:bold; text-align:center;line-height:20px;"></span>
			<input  name="mi_reservation_room[#xxxx#][id]" type="hidden" id="id_#xxxx#" style="float:left;width:30px;font-size:10px;border:1px solid #CCCCCC;background:#EFEFEF;color:#999999;" readonly="" class="hidden">
 	 		<a name="#17"></a>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:70px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="hidden" id="room_level_id_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:30px;font-weight:bold;font-size:13px; color:#000FFF;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="if($('room_name_#xxxx#') && $('room_id_old_#xxxx#') && $('room_id_old_#xxxx#').value=='' && $('price_#xxxx#').value!=''){ window.open('?page=room_map&cmd=select&act=assign_room&room_id='+$('room_id_#xxxx#').value+'&room_level_id='+$('room_level_id_#xxxx#').value+'&object_id=#xxxx#&input_count='+input_count,'select_room');return false; }else{ window.open('?page=room_map&cmd=select&room_id='+$('room_id_#xxxx#').value+'&room_level_id='+$('room_level_id_#xxxx#').value+'&object_id=#xxxx#&input_count='+input_count,'select_room');return false; }" style="cursor:pointer;">
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;">
				<input  name="mi_reservation_room[#xxxx#][room_name_old]" type="hidden" id="room_name_old_#xxxx#">
				<input  name="mi_reservation_room[#xxxx#][room_id_old]" type="hidden" id="room_id_old_#xxxx#">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/adult.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/child.png" align="top"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:50px;" type="text" id="price_#xxxx#" onchange="count_price('#xxxx#',true);" onblur="count_price('#xxxx#',true);" class="price">
			</span>
			<span class="multi-input" title="[[.select_rate.]]" style="width:16px;height:5px;">
            <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate" alt="[[.select_rate.]]"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:35px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);count_price('#xxxx#',true);" class="reservation_time_in" maxlength="5">
            </span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_out]" style="width:35px;" type="text" id="time_out_#xxxx#" title="00:00" class="reservation_time_out" onchange="count_price('#xxxx#',false);" maxlength="5">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:60px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',false);" class="date-select arrival_time">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][early_arrival_time]" type="text" id="early_arrival_time_#xxxx#" style="width:16px;" class="early_arrival_time"  AUTOCOMPLETE=OFF></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:60px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',false);" class="date-select departure_time">
					<input  name="mi_reservation_room[#xxxx#][departure_time_old]" type="hidden" id="departure_time_old_#xxxx#">
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][early_checkin]" id="early_checkin_#xxxx#" class="early_checkin" title="[[.early_checkin.]]" style="width:43px;">[[|verify_dayuse_options|]]</select>
			</span>
            <span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][late_checkout]" id="late_checkout_#xxxx#" class="late_checkout" title="[[.late_checkout.]]" style="width:43px;">[[|verify_dayuse_options|]]</select>
			</span>
			<span class="multi-input">
				<select  name="mi_reservation_room[#xxxx#][status]" id="status_#xxxx#" class="reservation_status"  style="width:75px;"
					onchange="
						if(this.value=='CHECKIN' || this.value=='CHECKOUT'){
							jQuery('#invoice_#xxxx#').show();
						} else {
							jQuery('#invoice_#xxxx#').hide();
						}
						if(this.value=='CHECKOUT'){
                        	if(checkPayment(#xxxx#)){
                                $('departure_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
                                $('time_out_#xxxx#').value='<?php echo date('H:i')?>';
                                $('closed_#xxxx#').checked = true;
                                $('closed_#xxxx#').readOnly = true;
                            }
						} else {
							$('closed_#xxxx#').checked = false;
							$('closed_#xxxx#').readOnly = false;
						}
						if(this.value=='CHECKIN'){
                        	if(!$('room_id_#xxxx#').value){
								$('status_#xxxx#').value = 'BOOKED';
                            	alert('[[.miss_room_number.]]');
								return false;
                            }
                            if($('old_status_#xxxx#').value=='' || $('old_status_#xxxx#').value=='BOOKED'){
                                $('arrival_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
                                $('time_in_#xxxx#').value='<?php echo date('H:i',time())?>';
                            }
						}
						count_price('#xxxx#',false);
						">
						<option value="BOOKED">Booked</option>
						<option value="CHECKIN" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check in</option>
						<option value="CHECKOUT" <?php echo User::can_edit($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check out</option>
						<option value="CANCEL">Cancel</option>
					</select>
			</span>
            <span class="multi-input-extra">
                <input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#">
            </span>
			<span class="multi-input">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" style="width:65px;font-size:11px;" type="text" id="reservation_type_id_#xxxx#">[[|reservation_type_options|]]</select>
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][confirm]" type="checkbox" id="confirm_#xxxx#">
			</span>
			<span class="multi-input" style="width:35px;">
					&nbsp;&nbsp;&nbsp;&nbsp;<input  name="mi_reservation_room[#xxxx#][closed]" type="checkbox" id="closed_#xxxx#">
			</span>
			<span class="multi-input">
                    <input  type="button" id="view_invoice_#xxxx#" onclick="viewInvoice('#xxxx#',true);" class="view-order-button" title="[[.view_order.]]">
					<!--<input  type="button" id="print_invoice_#xxxx#" onclick="window.open('?page=vat_invoice&cmd=entry&department=RECEPTION&type=SINGLE&r_id=<?php echo Url::get('id');?>&r_r_id='+jQuery('#id_#xxxx#').val());" class="print-order-button" title="[[.print_VAT_invoice.]]"/>-->
			</span>
            <span class="multi-input">
					<input  type="button" id="add_traveller_#xxxx#" class="folio_invoice" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=add_traveller&rr_id='+$('id_#xxxx#').value+'&r_id='+<?php echo Url::get('id');?>,Array('add_traveller_'+$('id_#xxxx#').value,'[[.list_guest.]]','20','110','1100','570'));" value="[[.list_guest.]]" title="[[.list_guest.]]">
			</span>
            <span class="multi-input">
					<input  type="button" id="split_invoice_#xxxx#" class="folio_invoice" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=create_folio&rr_id='+$('id_#xxxx#').value+'&r_id='+<?php echo Url::get('id');?>,Array('folio_'+$('id_#xxxx#').value,'[[.create_folio.]]','80','210','1000','500'));" value="[[.folio.]]" title="[[.create_folio.]]" style="display:none; width:35px;">
			</span>
			<span class="multi-input" style="float:right;padding-right:5px;">
					<span style="display:none;" id="expand_#xxxx#"></span>
                    <img id="expand_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShorten('#xxxx#');">
			</span>
			<span class="multi-input" style="width:20px;text-align:center;float:right;" id="delete_reservation_room_#xxxx#">
				<?php
				if(User::can_delete(false,ANY_CATEGORY)){?>
                <img align="left" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(confirm('[[.are_you_sure.]]')){var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount-1;mi_delete_row($('input_group_#xxxx#'),'mi_reservation_room','#xxxx#','');event.returnValue=false;}" style="cursor:pointer;">
				<?php }
				?>
			</span>
			<br clear="all" />
			<span id="mi_reservation_room_sample_#xxxx#" style="display:none;">
                <div class="room-extra-info" id="room_extra_info_#xxxx#">
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
							<span class="multi-input-extra">
                        </span>
                    </div>
                    <div>
						<span class="multi-input-extra" style="display:none;">
							<span class="label">[[.vip_card_code.]]</span>
							<input name="mi_reservation_room[#xxxx#][vip_card_code]" type="text" id="vip_card_code_#xxxx#" class="reservation-vip-card-code" onchange="$('reduce_balance_#xxxx#').value = (vip_card_list[this.value]!='undefined')?vip_card_list[this.value]:'';count_price('#xxxx#',false);">
						</span>
						<span class="multi-input-extra">
                            <span class="label">[[.tax_rate.]] / [[.service_rate.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][tax_rate]" style="width:35px; background:#FCFCFC;" maxlength="3" type="text" id="tax_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" value="10" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
							<input  name="mi_reservation_room[#xxxx#][service_rate]" style="width:30px;background:#FCFCFC;" maxlength="3" type="text" id="service_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);"  value="5" readonly="readonly" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
                            <input  name="mi_reservation_room[#xxxx#][net_price]" style="width:15px;" value="1" type="checkbox" id="net_price_#xxxx#" class="net_price" align="left" title="[[.net_price.]]">
                        </span>
                        <span class="multi-input-extra" style="margin-left:40px;">
							<input  name="mi_reservation_room[#xxxx#][deposit_button]" type="button" value="[[.deposit.]]" id="deposit_button_#xxxx#" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=deposit&id='+$('id_#xxxx#').value+'&r_id=<?php echo Url::get('id');?>&type=RESERVATION&act=traveller&portal_id=<?php echo PORTAL_ID;?>',Array('deposit_'+$('id_#xxxx#').value,'[[.deposit.]]','80','210','950','500'));" style="width:60px;margin-left:50px;">
                            <input  name="mi_reservation_room[#xxxx#][deposit]" type="text" id="deposit_#xxxx#" readonly="readonly" style="width:100px; background:#CCC;">
                        </span>
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
							<?php
				if((HAVE_MASSAGE))
				{?>
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="massage_invoice_#xxxx#" checked="checked">[[.massage_invoice.]] |
				<?php
				}
				?>
							<?php
				if((HAVE_TENNIS))
				{?>
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="tennis_invoice_#xxxx#" checked="checked">[[.tennis_invoice.]] |
				<?php
				}
				?>
							<?php
				if((HAVE_SWIMMING))
				{?>
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="swimming_pool_invoice_#xxxx#" checked="checked">[[.swimming_pool_invoice.]] |
				<?php
				}
				?>
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="included_deposit_#xxxx#" checked="checked">[[.included_deposit.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="included_related_total_#xxxx#" checked="checked">[[.included_related_total.]]
							<input  type="hidden" id="url_#xxxx#" value="&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&tennis_invoice=1&swimming_pool_invoice=1">
                        </span>
                    </div>
                    <?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card'));?>&id='+$('id_#xxxx#').value)">[ [[.guest_registration_card.]] ]</a>
				<?php
				}
				?>
					<?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>1));?>&id='+$('id_#xxxx#').value)">[ [[.registration_form.]] ]</a>
				<?php
				}
				?>
<?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>3));?>&id='+$('id_#xxxx#').value)">[ [[.registration_form_vn.]] ]</a>
				<?php
				}
				?>
                 </div>
              <div id="list_traveller_#xxxx#" style=""></div>
             </span>
		</div>
		</span>
	</span>
</span>
<div id="mask" class="mask">[[.Please wait.]]...</div>
<form  name="AddReservationForm" method="post" onsubmit="return checkInput();">
<input  name="deleted_ids" type="hidden"  id="deleted_ids" value="<?php echo URL::get('deleted_ids');?>">
<div style="text-align:center;">
<div style="margin-right:auto;margin-left:auto;width:1050px;">
<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;">
	<tr valign="top">
		<td align="left">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-bound">
				<tr height="40">
					<td width="90%" class="form-title">[[.reservation.]]</td>
					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="save" type="submit" value="[[.Save_and_close.]]" class="button-medium-save" onclick="checkPayment();jQuery('#mask').show();"></td><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="update" type="submit" value="[[.Save_and_stay.]]" class="button-medium-save stay" onclick="checkPayment();jQuery('#mask').show();"></td><?php }?>
					<td width="1%" nowrap="nowrap"><a href="<?php echo Url::build('room_map')?>" class="button-medium-back" onclick="if(!confirm('[[.are_you_sure_to_close.]] [[.if_you_close_all_datas_will_not_be_save.]]')){return false;}">[[.close.]]</a></td>
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
                        <td align="left" style="padding-left:10px;"><input  name="booking_code" id="booking_code" style="width:215px;" type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
                        <td rowspan="3" width="40%"><span class="label">[[.note_for_tour_or_group.]]</span>
                            <textarea  name="note" id="note" style="width:99%;height:40px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea>
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
                            "></a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.tour.]] / [[.group.]]</td>
                        <td align="left" style="padding-left:10px;">
                            <input name="tour_name" type="text" id="tour_name" style="width:215px;" readonly="" class="readonly">
                            <input  name="tour_id" type="text" id="tour_id" value="[[|tour_id|]]" class="hidden">
                            <!--IF:cond(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"><!--/IF:cond-->
                        </td>
                    </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.customer.]]</td>
                        <td align="left" style="padding-left:10px;"><input name="customer_name" type="text" id="customer_name" style="width:215px;" readonly="readonly"  class="readonly">
                        <input name="customer_id" type="text" id="customer_id" value="[[|customer_id|]]" class="hidden">
                        <!--IF:pointer(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;"><!--/IF:pointer--></td>
                    </tr>
                    <tr valign="top" style="display:none;">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right" height="20">[[.total_payment.]]</td>
                        <td align="left" style="padding-left:10px;font-weight:bold;"><span id="total_payment"></span></td>
                    </tr>
                    <tr valign="top" style="display:none;">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.payment.]]</td>
                        <td align="left" style="padding-left:10px;">
                            <input  name="payment" id="payment" style="width:215px;" type ="text" >
                            <?php echo HOTEL_CURRENCY;?>
                        </td>
                        <td bgcolor="#EFEFEF">&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.color_of_group.]]</td>
                        <td align="left" style="padding-left:10px;">
                            <input name="color" type="text" id="color" style="width:215px;" />
                            <span onclick="TCP.popup($('color'));" class="color-select-button" title="[[.select_color.]]"><img src="packages/core/skins/default/images/color_picker.gif" width="15"></span>
                            <?php if([[=color=]]){?><script type="text/javascript">jQuery('#color').css('background-color','[[|color|]]');jQuery('#color').css('color','[[|color|]]');</script><?php }?>
                        </td>
                        <td bgcolor="#EFEFEF">
                            <?php if((User::can_edit(false,ANY_CATEGORY))){?>
                            <!--<input name="print" type="button" id="print" value="[[.preview_invoice.]]" class="button-medium-add" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'print','id','r_r_id'));?>'" />-->
            				<?php } ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.Re_code.]]</td>
                        <td align="left" style="padding-left:10px;"><strong>[[|id|]]</strong></td>
                        <td bgcolor="#EFEFEF"></td>
                    </tr>
                </table>
                <br />
                <div style="border-top:1px solid #CCCCCC;margin-bottom:5px;">
                    <div style="font-size:11px;border-top:1px solid #FFFFFF;padding-top:2px;">
                        <input  name="include_booked" type="checkbox" id="include_booked"> [[.Include_booked.]] |
                        <input  type="checkbox" id="room_invoice" checked="checked">[[.room_invoice.]] |
                        <input  type="checkbox" id="hk_invoice" checked="checked">[[.housekepping_invoice.]] |
                        <input  type="checkbox" id="bar_invoice" checked="checked">[[.bar_invoice.]] |
                        <input  type="checkbox" id="other_invoice" checked="checked">[[.other_invoice.]] |
                        <input  type="checkbox" id="phone_invoice" checked="checked">[[.phone_invoice.]] |
                        <input  type="checkbox" id="extra_service_invoice" checked="checked">[[.extra_service_invoice.]] |
                        <!--IF:cond_message(HAVE_MASSAGE)-->
                        <input  type="checkbox" id="massage_invoice" checked="checked">[[.massage_invoice.]] |
                        <!--/IF:cond_message-->
                        <!--IF:cond_tennis(HAVE_TENNIS)-->
                        <input  type="checkbox" id="tennis_invoice" checked="checked">[[.tennis_invoice.]] |
                        <!--/IF:cond_tennis-->
                        <!--IF:cond_swimming(HAVE_SWIMMING)-->
                        <input  type="checkbox" id="swimming_pool_invoice" checked="checked">[[.swimming_pool_invoice.]] |
                        <!--/IF:cond_swimming-->
                        <input  type="checkbox" id="included_deposit" checked="checked">[[.included_deposit.]]
                    </div>
                </div>
                <div style="border-top:1px solid #CCCCCC;margin-top:5px;float:left;width:100%;">
                    <div style="font-size:11px;border-top:1px solid #FFFFFF;float:left;width:100%;text-align:center;padding-top:5px;">
                        <a class="view-order-button" href="#" onclick="openGroupInvoice(true,true);">[[.view_group_invoice.]]</a>
                        <a class="add-order-button" href="#" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=group_folio&id=<?php echo Url::get('id');?>&customer_id='+jQuery('#customer_id').val(),Array('group_folio_<?php echo Url::get('id');?>','[[.create_folio.]]','80','210','950','540'));" id="group_folio_a">[[.group_folio.]]</a>
                        <!--<a class="print-order-button" href="#" onclick="window.open('?page=vat_invoice&cmd=entry&department=RECEPTION&type=GROUP&r_id=<?php echo Url::get('id');?>');">[[.print_VAT_group_invoice.]]</a>-->
                        <!--<a href="#" onclick="openGroupInvoice(false);" class="view-invoice-button">[[.view_tour_invoice.]]</a> -->
                        <a class="view-order-button" target="_blank" href="<?php echo Url::build_current(array('cmd'=>'rooming_list','id'))?>">[[.rooming_list.]]</a>
                        <a class="add-order-button" href="#" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=563&cmd=group_traveller&r_id=<?php echo Url::get('id');?>&customer_id='+jQuery('#customer_id').val(),Array('group_traveller_<?php echo Url::get('id');?>','[[.add_traveller.]]','20','110','990','570'));" id="group_traveller_a">[[.add_traveller.]]</a>
                    </div>
                </div>
        </fieldset>
		<fieldset style="background:#f2f4ce;margin-bottom:5px;">
			<legend class="legend-title">[[.reservation_room.]]&nbsp;(<span id="count_number_of_room"></span> [[.room.]])</legend>
			<span id="mi_reservation_room_all_elems">
				<span>
                	<span class="multi-input-header" style="width:35px;">[[.index.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.room_level.]]</span>
					<span class="multi-input-header" style="width:48px;">[[.room.]]</span>
					<span class="multi-input-header" style="width:32px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.child.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.price.]]</span>
					<span class="multi-input-header" style="width:12px;"></span>
					<span class="multi-input-header" style="width:35px;">[[.time_in.]]</span>
					<!--<span class="multi-input-header" style="width:16px;"><input name="all_early_checkin" type="checkbox" onclick="jQuery('.early_checkin').attr('checked',jQuery(this).attr('checked'));" /></span>-->
					<span class="multi-input-header" style="width:35px;">[[.time_out.]]</span>
					<span class="multi-input-header" style="width:60px;">[[.arrival_date.]]</span>
					<span class="multi-input-header" style="width:16px;" title="[[.early_arrival_time.]]">E.A</span>
					<span class="multi-input-header" style="width:60px;">[[.departure_date.]]</span>
					<span class="multi-input-header" style="width:40px;" title="[[.early_checkin.]]">E.I</span>
                    <span class="multi-input-header" style="width:40px;" title="[[.late_checkout.]]">L.O</span>
					<span class="multi-input-header" style="width:70px;">[[.status.]]</span>
					<span class="multi-input-header" style="width:60px;">[[.reservation_type.]]</span>
					<span class="multi-input-header" style="width:20px;">[[.confirm.]]</span>
					<span class="multi-input-header" style="width:35px;">[[.closed.]]</span>
					<span class="multi-input-header" style="float:right;border:0px;background:none;">&nbsp;</span>
					<span id="expand_all_span" style="float:right;"><img id="expand_all" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandAll();"></span>
					<br clear="all">
				</span>
			</span>
			<br clear="all">
            <div style="border:1px solid #CCC;background-color:#EFEFEF;padding:2px;">
			<input type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');updateStatusList(input_count);updateInput(input_count);buildRateList(input_count);if($('index_'+input_count)){$('index_'+input_count).innerHTML = input_count;}" class="button-medium-add">
            <input name="deposit_for_group" type="button" id="deposit_for_group" value="[[.deposit_for_group.]]" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=deposit&act=group&id=<?php echo Url::get('id');?>&type=RESERVATION&customer_id=[[|customer_id|]]&portal_id=<?php echo PORTAL_ID;?>',Array('deposit_group_<?php echo Url::get('id');?>','[[.deposit.]]','80','210','950','500'));" >|
            [[.change_all_status_to.]]
            <select  name="change_all_status" id="change_all_status" onchange="changeAllStatus(this.value,input_count);checkPayment('ALL');"><option value="">[[.select_status.]]</option><option value="CHECKIN">CHECKIN</option><option value="CHECKOUT">CHECKOUT</option><option value="CANCEL">CANCEL</option></select> | [[.cut_off_day.]] <input name="cut_of_date" type="text" id="cut_of_date" style="width:65px;" class="date-input"> |
			[[.change_all_arrival_time.]] <input type="text" id="all_arrival_time" class="date-input" onchange="changeAllTime(this.value,'AT')">
			[[.change_all_departure_time.]] <input type="text" id="all_departure_time" class="date-input" onchange="changeAllTime(this.value,'DT')">
| [[.discount_for_group.]](%) <input type="text" id="discount_for_group" class="input_number" onkeyup="changeAllDiscount(this.value);" style="width:30px;">
			[[.change_all.]] [[.reservation_type.]] <select  name="change_all_reservation_type" id="change_all_reservation_type" onchange="changeAllReservationType(this.value);">[[|reservation_type_options|]]</select>
            | [[.change_all.]] E.I <select name="all_early_checkin" id="all_early_checkin" onChange="jQuery('.early_checkin').val(this.value);">[[|verify_dayuse_options|]]</select>| [[.change_all.]] L.O <select name="all_late_checkout" id="all_late_checkout" onChange="jQuery('.late_checkout').val(this.value);">[[|verify_dayuse_options|]]</select>
            | [[.net_price.]] <input name="change_all_net_price" type="checkbox" id="change_all_net_price" onclick="jQuery('.net_price').attr('checked',jQuery(this).attr('checked'));" />
            [[.copy_room.]] <input  name="room_indexs" id="room_indexs" value="index1,index2,index3" onclick="if(this.value=='index1,index2,index3'){this.value='';}" onblur="if(this.value==''){this.value='index1,index2,index3';}" type ="text" value="<?php echo String::html_normalize(URL::get('room_indexs'));?>" > [[.quantity.]] <input  name="room_quantity" id="room_quantity" style="width:30px;" size="100" maxlength="3" type ="text" ><input type="button" value="[[.copy.]]" onclick="copyRoom($('room_indexs').value,$('room_quantity').value)">
            </div>
		</fieldset>
		</td></tr>
		<tr><td>
	</td>
	</tr>
	</table>
	</td></tr>
</table>
</div>
</div>
</form>
<div id="selected_room" onmouseover="$('selected_room').style.display='';" onmouseout="$('selected_room').style.display='none';" style="display:none;float:left;position:absolute;top:0px;left:0px;width:180px;background-color:#FFCC00;border:2px solid #0099CC;vertical-align:top;">
    <div id="rooms" style="width:99%;background-color:#FFFFFF;float:left;"></div>
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
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
var all_net_price = <?php if(Url::get('change_all_net_price')==1){ echo 1;}else{ echo 0;}?>;
if(all_net_price ==1){
	jQuery('#change_all_net_price').attr('checked',true);
}
var readOnly = 'readonly="readonly"';
<!--IF:readOnly_cond(User::can_edit(false, ANY_CATEGORY))-->
	readOnly = '';
<!--/IF:readOnly_cond-->
var nationalities = <?php echo String::array2js($this->map['nationalities'])?>;
jQuery("#all_arrival_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#all_departure_time").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#cut_of_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#rate_list").mouseover(function(){
	jQuery(this).show();
});
jQuery("#rate_list").mouseout(function(){
	jQuery(this).hide();
});
var currentHour = '<?php echo date('H:i');?>';
var currentDate = '<?php echo date('d/m/Y');?>';
<?php if(User::can_admin(false,ANY_CATEGORY)){
	echo 'var can_admin = true;'; }else{ echo 'var can_admin = false;';} ?>
<?php if(User::can_delete(false,ANY_CATEGORY)){
	echo 'var can_delete = true;'; }else{ echo 'var can_delete = false;';} ?>
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
</script>
<script>
var currency_arr = {};
//var mi_traveller_arr='';
<?php if(isset($_REQUEST['mi_reservation_room']))
{
	//echo 'var mi_reservation_room_arr = '.String::array2js($_REQUEST['mi_reservation_room']).';';
	echo 'mi_init_rows(\'mi_reservation_room\',mi_reservation_room_arr);';
}
else
{
	echo 'mi_add_new_row(\'mi_reservation_room\',true);';
}
?>
//contruct_elements();
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
<?php /*if(isset($_REQUEST['mi_reservation_room']))
{
	foreach($_REQUEST['mi_reservation_room'] as $key=>$value){
		if(isset($value['currency_arr']) and $value['currency_arr']){
			echo 'updateCurrenciesValue('.String::array2js($value['currency_arr']).');';
		}
		if(isset($value['service_arr']) and $value['service_arr']){
			//echo 'updateServicesValue('.String::array2js($value['service_arr']).');';
		}
	}
}*/
?>
var roomCount = to_numeric($('count_number_of_room').innerHTML);
<!--IF:cond(Url::get('r_r_id'))-->
var r_r_id = <?php echo Url::get('r_r_id');?>;
<!--ELSE-->
var r_r_id = 0;
<!--/IF:cond-->
var can_checkin = false;
<?php if(User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)){?>
	can_checkin = true;
<?php }?>
function updateInput(input_count){
	jQuery('#price_'+input_count).ForceNumericOnly().FormatNumber();
	jQuery('#arrival_time_'+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
	jQuery('#departure_time_'+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
}
jQuery(document).ready(function(){
	jQuery('.early_arrival_time').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, (CURRENT_DAY-1)) });
	jQuery(".reservation_time_in").mask("99:99");
	jQuery(".reservation_time_out").mask("99:99");
	jQuery(".extra_bed_rate").ForceNumericOnly().FormatNumber();
	jQuery(".baby_cot_rate").ForceNumericOnly().FormatNumber();
	jQuery('.reservation_status').each(function(){
		var id = jQuery(this).attr('id');
		var i = id.substr((id.lastIndexOf('_')+1),id.length);
		if(i != '#xxxx#' && is_numeric(i)){
			if($('index_'+i)){
				$('index_'+i).innerHTML = i;
			}
			updateInput(i);
			count_price(i,false);
			updateStatusList(i);
			buildRateList(i);
			jQuery('#mi_reservation_room_sample_'+i).hide();
			if(jQuery(this).val()!='CANCEL'){
				roomCount++;
			}
			if(jQuery('#list_traveller_'+i).html()!=''){
				jQuery('#split_invoice_'+i).css('display','');
			}
			jQuery('expand_img_'+i).attr('src','packages/core/skins/default/images/buttons/node_close.gif');
			if(jQuery(this).val() != 'BOOKED'){
				if(jQuery('#select_room_'+i)){
					jQuery('#select_room_'+i).css({'height':'0px','width':'15px'});
				}
			}
			if($('old_status_'+i) && $('old_status_'+i).value == 'CHECKOUT'){
				jQuery('#status_'+i).css('background','#FF80FF');
			}
			var datePickerForArrival = true;
			var datePickerForDeparture = true;
			<?php if(!User::can_admin(false,ANY_CATEGORY)){?>
				if(jQuery(this).val() == 'CHECKOUT' || (can_checkin == false && jQuery(this).val()=='CHECKIN')){
					jQuery('#reservation_room_bound_'+i+' :input').attr('readOnly',true);
					jQuery('#reservation_room_bound_'+i+' :input').attr('class','readonly');
					jQuery('#reservation_room_bound_'+i+' :input').click(function(){return false});
					jQuery('#reservation_room_bound_'+i+' option').each(function(){
						if(!this.selected){
							jQuery(this).attr('disabled',true);
						}
					});
					datePickerForDeparture=false;
					//jQuery('#view_invoice_'+i).attr('readOnly',false);
				}
				if(jQuery(this).val() == 'CHECKIN'){
					jQuery('#time_in_'+i).attr('readonly',true);
					jQuery('#time_in_'+i).className = 'readonly';
					jQuery('#arrival_time_'+i).attr('readonly',true);
					jQuery('#arrival_time_'+i).className = 'readonly';
					//jQuery('#price_'+i).attr('readonly',true);
					//jQuery('#price_'+i).className = 'readonly';
					datePickerForArrival=false;
				}
				if(jQuery(this).val() == 'BOOKED' && $('customer_name').value){
					//jQuery('#price_'+i).attr('readonly',true);
					//$('price_'+i).className = 'readonly';
				}else if(jQuery(this).val() != 'BOOKED'){
					jQuery('#rate_list_'+i).css({'display':'none'});
				}
			<?php }?>
			if(jQuery('#id_'+i).val() == r_r_id){
				jQuery('#mi_reservation_room_sample_'+i).show();
				if($('expand_'+i)){
					$('expand_'+i).innHTML='';
					$('expand_img_'+i).src='packages/core/skins/default/images/buttons/node_open.gif';
					$('expand_all_span').innHTML='';
					$('expand_all').src='packages/core/skins/default/images/buttons/node_open.gif';
				}
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
				if(datePickerForDeparture){
					jQuery("#departure_time_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4' });
					if(jQuery("#extra_bed_to_date_"+i)){
						jQuery("#extra_bed_to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
					}
					if(jQuery("#baby_cot_to_date_"+i)){
						jQuery("#baby_cot_to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
					}
				}
				if(datePickerForArrival){
					jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4' });
				}
			}
			if(!$('customer_id') || jQuery('#customer_id').val()==''){
				jQuery('#group_folio_a').css('display','none');
				jQuery('#deposit_for_group').css('display','none');
			}
		}
	});
	$('count_number_of_room').innerHTML = roomCount;
});
function updateAll(){
	for(var i=101;i<=input_count;i++){
		if($('index_'+i)){
			$('index_'+i).innerHTML = i;
		}
		count_price(i,false);
		updateStatusList(i);
		buildRateList(i);
	}
}
//updateAll();
function checkRoomOut(room_id){
	$return = false;
	if(room_id){
		temp_arr = room_id.split('-');
		for(var i=101;i<=input_count;i++){
			if($('id_'+i) && $('id_'+i).value && $('room_id_'+i) && $('room_id_'+i).value == temp_arr[0] && $('departure_time_'+i).value == temp_arr[1]){
				if($('status_'+i) && $('status_'+i).value == 'CHECKOUT'){
					$return = true;
				}
			}
		}
	}
	return $return;
}
function buildRateList(i){
	if($('status_'+i) && $('customer_name').value){
		//jQuery('#price_'+i).attr('readonly',true);
		//$('price_'+i).className = 'readonly';
	}
	if(jQuery('#rate_list_'+i) && jQuery('#room_level_id_'+i)){
		jQuery('#rate_list_'+i).click(function(){
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
function updateCheckoutStatus(index){
	$('departure_time_'+index).value = '<?php echo date('d/m/Y')?>';
	$('time_out_'+index).value = '<?php echo date('H:i')?>';
	$('closed_'+index).checked = true;
	$('closed_'+index).readOnly = true;
	count_price(index,false);
	$('status_'+index).value = 'CHECKOUT';
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
function viewInvoice(index,preview){
	updateUrl(index);
	if($('id_'+index).value!='')
	{
		var url = '?page=reservation';
		if(preview==false){
			url += '&<?php echo md5('print')?>=<?php echo md5('1')?>';
		}else{
			url += '&<?php echo md5('print')?>=<?php echo md5('0')?>';
		}
		url += $('url_'+index).value;
		//url += '&total_amount='+to_numeric($('total_amount_'+index).value);
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
function openGroupInvoice(group,preview){
	if(group==true){
		url = '<?php echo Url::build_current(array('cmd'=>'group_invoice','id'));?>';
	}else{
		url = '<?php echo Url::build_current(array('cmd'=>'tour_invoice','id'));?>';
	}
	if(preview==false){
		url += '&<?php echo md5('print')?>=<?php echo md5('1')?>';
	}else{
		url += '&<?php echo md5('print')?>=<?php echo md5('0')?>';
	}
	if($('include_booked').checked){
		url += '&include_booked=1';
	}
	if($('room_invoice').checked){
		url += '&room_invoice=1';
	}
	if($('hk_invoice').checked){
		url += '&hk_invoice=1';
	}
	if($('bar_invoice').checked){
		url += '&bar_invoice=1';
	}
	if($('other_invoice').checked){
		url += '&other_invoice=1';
	}
	if($('phone_invoice').checked){
		url += '&phone_invoice=1';
	}
	if($('extra_service_invoice').checked){
		url += '&extra_service_invoice=1';
	}
	<!--IF:cond_massage(HAVE_MASSAGE)-->
	if($('massage_invoice').checked){
		url += '&massage_invoice=1';
	}
	<!--/IF:cond_massage-->
	<!--IF:cond_tennis(HAVE_TENNIS)-->
	if($('tennis_invoice').checked){
		url += '&tennis_invoice=1';
	}
	<!--/IF:cond_tennis-->
	<!--IF:cond_swimming(HAVE_SWIMMING)-->
	if($('swimming_pool_invoice').checked){
		url += '&swimming_pool_invoice=1';
	}
	<!--/IF:cond_swimming-->
	if($('included_deposit').checked){
		url += '&included_deposit=1';
	}
	window.open(url);
}
function checkPayment(index){
	if(index=='ALL'){
		var room_name = '';
		for(var j=101; j<=input_count;j++){
			if($('id_'+j) && $('id_'+j).value !=''){
				var id = $('id_'+j).value;
				if(mi_reservation_room_arr[id] && mi_reservation_room_arr[id]['check_payment'] == 0){
					$('status_'+j).value = 'CHECKIN';
					if(room_name!=''){
						room_name += ',';
					}
					room_name += mi_reservation_room_arr[id]['room_name'];
				}
			}
		}
		if(room_name ==''){
			return true;
		}else{
			alert('Chưa thanh toán hết cho phòng: '+room_name);
			return false;
		}
	}else if($('id_'+index) && $('id_'+index).value !=''){
		var id = $('id_'+index).value;
		if(mi_reservation_room_arr[id] && mi_reservation_room_arr[id]['check_payment'] == 0){
			alert('Chưa thanh toán hết cho phòng');
			$('status_'+index).value = 'CHECKIN';
			return false;
		}
		return true;
	}
}
/*function check_early_checkin(){
	for(var i=101;i<=input_count;i++){
		if(jQuery("#early_checkin_"+i) && jQuery('#early_checkin_'+i).attr('checked')==true){
			jQuery('#early_checkin_'+i).attr('checked',false);
		}else{
			jQuery('#early_checkin_'+i).attr('checked',true);
		}
	}
}*/
function windowOpenUrlTraveller(rr_id,r_id,rt_id){
	openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=create_folio&rr_id='+rr_id+'&r_id='+r_id+'&traveller_id='+rt_id+'',Array('folio_traveller_'+rt_id+'','[[.create_folio.]]','80','210','950','500'));
}
</script>