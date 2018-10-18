<style type="text/css">
.simple-layout-middle{width:100%;}
.items_key_hune{
    margin-left:5px !important;
    vertical-align:top;
    height:15px;
    width:10px !important;
    background-color: transparent;
    border: 0px solid; 
    font-size: 14px !important;
}
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

<script type="text/javascript" src="packages/hotel/packages/reception/modules/includes/reservation.js"></script>
<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<span id="input_group_#xxxx#">
		<div id="reservation_room_bound_#xxxx#" class="reservation_room_bound">
            <span class="multi-input" style="width: 20px; text-align: center;">
			     <input name="check_box_#xxxx#" id="check_box_#xxxx#" type="checkbox" class="class_check_box" />		
            </span>
			<span class="multi-input" id="index_#xxxx#" style="width:34px;font-size:13px;color:#F90; font-weight:bold; text-align:center;line-height:20px;"></span>
			<input  name="mi_reservation_room[#xxxx#][id]" type="hidden" id="id_#xxxx#" style="float:left;width:30px;font-size:10px;border:1px solid #CCCCCC;background:#EFEFEF;color:#999999;" readonly="" class="hidden">
            
 	 		<a name="#17"></a>
            
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:70px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="hidden" id="room_level_id_#xxxx#" />
			</span>
			<span class="multi-input" style="width: 70px;">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:47px;font-weight:bold;font-size:13px; color:#000FFF;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="Check_Availblity(#xxxx#,input_count);" style="cursor:pointer;">
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;"/>
				<input  name="mi_reservation_room[#xxxx#][room_name_old]" type="hidden" id="room_name_old_#xxxx#"/>
				<input  name="mi_reservation_room[#xxxx#][room_id_old]" type="hidden" id="room_id_old_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" class="pp" type="text" id="adult_#xxxx#" style="width:28px;" AUTOCOMPLETE=OFF><i class="fa fa-male" style="font-size: 16px;"></i></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:28px;" AUTOCOMPLETE=OFF><i class="fa fa-child" style="font-size: 16px;"></i></span>
			<!--trung add :tr.e 5 <tuoi -->
            <span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][child_5]" type="text" id="child_5_#xxxx#" style="width:36px;" AUTOCOMPLETE=OFF><i class="fa fa-child" style="font-size: 16px;"></i></span>
            <!--trung add :tr.e 5 <tuoi -->
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:65px;" type="text" id="price_#xxxx#" class="change_price" autocomplete="off" onkeyup="count_price('#xxxx#',true); jQuery('#change_price_<?php echo date('d/m/Y');?>_#xxxx#').val(number_format(to_numeric(this.value))); SetupAllotment('#xxxx#');" oninput="jQuery('#usd_price_#xxxx#').val(number_format(to_numeric(jQuery('#price_#xxxx#').val())/to_numeric(jQuery('#exchange_rate').val())));count_price('#xxxx#',true);"> 
            </span>
            <span class="multi-input">
					<input name="mi_reservation_room[#xxxx#][usd_price]" style="width:65px;" type="text" id="usd_price_#xxxx#" class="price" oninput="jQuery('#price_#xxxx#').val(number_format(to_numeric(jQuery('#usd_price_#xxxx#').val())*to_numeric(jQuery('#exchange_rate').val())));count_price('#xxxx#',true);jQuery('#usd_price_#xxxx#').ForceNumericOnly().FormatNumber();SetupAllotment('#xxxx#');" />
			</span>
			<!---<span class="multi-input" title="[[.select_rate.]]" style="width:20px;height:5px;">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="rate_list_#xxxx#" class="select-rate" alt="[[.select_rate.]]">
            </span>--->
            <?php if(User::can_admin($this->get_module_id('ReceptionCommission'),ANY_CATEGORY)){ ?>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][commission_rate]" style="width:25px;" type="text" id="commission_rate_#xxxx#" class="cms_rate price">
			</span>
            <span class="multi-input">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="commission_list_#xxxx#" class="select-rate" />
                <!--<a id="commission_list_#xxxx#" class="select-rate" ><i class="fa fa-edit"></i></a>---> 
            </span>
            <?php }else{?>
            <span class="multi-input" style="display: none;">
					<input  name="mi_reservation_room[#xxxx#][commission_rate]" style="width:18px; display: none;" type="text" id="commission_rate_#xxxx#" class="cms_rate price"/>
			</span>
			<span class="multi-input" style="display: none;">
                <img src="packages/core/skins/default/images/buttons/rate_list.gif" id="commission_list_#xxxx#" style="display: none;" class="select-rate"/> 
            </span>
            <?php } ?> 
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:45px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);SetupAllotment('#xxxx#');" class="reservation_time_in" maxlength="5">
            </span>
            <span class="multi-input">
                    <input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:62px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',false); change_date_traveller('#xxxx#');rate_code_price('#xxxx#');SetupAllotment('#xxxx#');" readonly="readonly" class="date-select">
            </span>
            <span class="multi-input">
                    <input  name="mi_reservation_room[#xxxx#][time_out]" style="width:45px;" type="text" id="time_out_#xxxx#" title="00:00" class="reservation_time_out" onchange="count_price('#xxxx#',false);SetupAllotment('#xxxx#');" maxlength="5">
            </span>
            
            <span class="multi-input" style="display: none;">
                    <input  name="mi_reservation_room[#xxxx#][early_arrival_time]" type="text" id="early_arrival_time_#xxxx#" style="width:16px;" class="early_arrival_time" autocomplete="off" /></span>
            <span class="multi-input">
                    <input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:62px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',false);rate_code_price('#xxxx#');SetupAllotment('#xxxx#');" readonly="readonly" class="date-select">
                    <input  name="mi_reservation_room[#xxxx#][departure_time_old]" type="hidden" id="departure_time_old_#xxxx#">
            </span>
            <span class="multi-input" style="display: none;">
					<select  name="mi_reservation_room[#xxxx#][early_checkin]" id="early_checkin_#xxxx#" class="early_checkin" title="[[.early_checkin.]]" style="width:48px;">[[|verify_dayuse_options|]]</select>
                    <!-- manh them de lay gia khi tu dong late_checkin -->
                    <input  name="mi_reservation_room[#xxxx#][auto_late_checkin_price]" style="width:70px; display: none;" type="text" id="auto_late_checkin_price_#xxxx#" />
                    <!-- end manh -->
			</span>
            <span class="multi-input" style="display: none;">
					<select  name="mi_reservation_room[#xxxx#][late_checkout]" id="late_checkout_#xxxx#" class="late_checkout" title="[[.late_checkout.]]" style="width:50px;">[[|verify_dayuse_options|]]</select>
			</span>
            <span style="display: none;">
                    <input  name="mi_reservation_room[#xxxx#][note_change_room]" id="note_change_room_#xxxx#" class="note_change_room"  style="width:43px;"/>
            </span>
			<span class="multi-input">
				<select  name="mi_reservation_room[#xxxx#][status]" id="status_#xxxx#" class="reservation_status"  style="width:82px;"
					onchange=" 
						if(this.value=='CHECKIN' || this.value=='CHECKOUT')
                        {
							jQuery('#invoice_#xxxx#').show();
                            jQuery('#time_in_'+#xxxx#).attr('readonly',true);
                            jQuery('#time_in_'+#xxxx#).addClass('readonly');
                            if(this.value=='CHECKOUT')
                            {
                                jQuery('#time_out_'+#xxxx#).attr('readonly',true);
                                jQuery('#time_out_'+#xxxx#).addClass('readonly');                                
                            }
						} 
                        else 
                        {
							jQuery('#invoice_#xxxx#').hide();
						}
						if(this.value=='CHECKOUT')
                        {
                            <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ ?>
                                $('departure_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
                                $('time_out_#xxxx#').value='<?php echo date('H:i')?>';
                                $('closed_#xxxx#').checked = true;
                                $('closed_#xxxx#').readOnly = true;
                             <?php }else{ ?>
                                check_folio(#xxxx#,0);
                             <?php } ?>   
                        } 
                        else 
                        {
							$('closed_#xxxx#').checked = false;
							$('closed_#xxxx#').readOnly = false;
						}
						if(this.value=='CHECKIN')
                        {
                            checkDirty_Repair(#xxxx#);
                        	if(!$('room_id_#xxxx#').value)
                            {
								$('status_#xxxx#').value = 'BOOKED';
                            	alert('[[.miss_room_number.]]');
								return false;
                            }
                            if($('old_status_#xxxx#').value=='' || $('old_status_#xxxx#').value=='BOOKED')
                            {
                                $('arrival_time_#xxxx#').value='<?php echo date('d/m/Y')?>';
                                $('time_in_#xxxx#').value='<?php echo date('H:i',time())?>';
                            }
                            save_time(#xxxx#);
						}
                        if(this.value=='CANCEL'){
                           check_cancel_deposit(#xxxx#,0);
                        } 
                        if(this.value=='NOSHOW'){
                           jQuery('#loading-layer').fadeOut(0);
                        }
						count_price('#xxxx#',false);

						">
						<option value="BOOKED">Booked</option>
                        <option value="NOSHOW">Noshow</option>
						<option value="CHECKIN" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check in</option>
						<option value="CHECKOUT" <?php echo User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)?'':'disabled="disabled"'?>>Check out</option>
						<option value="CANCEL">Cancel</option>
					</select>
			</span>
            <span class="multi-input-extra">
                <input  name="mi_reservation_room[#xxxx#][old_status]" type="hidden" id="old_status_#xxxx#"/>
            </span>
            
            <!-- START Gán phòng_ chuy?n ngay tr?ng thái thành checkin, H? th?ng không check du?c, phòng dirty d? dua ra c?nh báo và không cho checkin-->
            <span class="multi-input-extra">
                <input name="mi_reservation_room[#xxxx#][house_status]" type="hidden" id="house_status_#xxxx#"/>
            </span>
            <!-- END Gán phòng_ chuy?n ngay tr?ng thái thành checkin, H? th?ng không check du?c, phòng dirty d? dua ra c?nh báo và không cho checkin-->
			<span class="multi-input" style="display: none;">
					<select  name="mi_reservation_room[#xxxx#][reservation_type_id]" style="width:45px;font-size:11px;" id="reservation_type_id_#xxxx#">[[|reservation_type_options|]]</select>
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][confirm]" type="checkbox" id="confirm_#xxxx#" style="width:20px;font-size:11px;"  class="checkbox"/>
			</span>
            <span class="multi-input">
                    <input  name="mi_reservation_room[#xxxx#][breakfast]" type="checkbox" id="breakfast_#xxxx#" style="width:18px;font-size:11px;"  class="cb_bf"/>
            </span>
            <!-- Manh them allotment -->
            <span class="multi-input" style="text-align: center;<?php if(!USE_ALLOTMENT){ ?> display: none; <?php } ?>">
					<input  name="mi_reservation_room[#xxxx#][allotment]" type="checkbox" id="allotment_#xxxx#" style="width:20px;font-size:11px;"  class="checkbox" onclick="SetupAllotment('#xxxx#');"/>
			</span>
            <!-- Manh them allotment -->
            <span class="multi-input" style="text-align: center;">
                 <input  name="mi_reservation_room[#xxxx#][closed]" type="checkbox" id="closed_#xxxx#" style="width: 40px;" class="class_check_close_box" />
            </span>     
            <!--giap.ln thay doi phan chon package thanh combobox -->
            <!--<span class="multi-input" style="width: 65px;">
					<input  name="mi_reservation_room[#xxxx#][package_sale_name]" type="text" id="package_sale_name_#xxxx#" onblur="autocomplete_package_sale(#xxxx#);" onfocus="autocomplete_package_sale(#xxxx#);" autocomplete="off" style="width: 65px;"/>
			</span>            
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][package_sale_id]" type="hidden" id="package_sale_id_#xxxx#"/>
			</span>-->
            <span class="multi-input">
                <select id="package_sale_id_#xxxx#" name="mi_reservation_room[#xxxx#][package_sale_id]" style="width:72px;">
                [[|package_sale_options|]]
                </select>
			</span>            
            <!--end giap.ln -->
            <!--giap.ln add key-->
            <?php
               if(User::can_admin('1929',ANY_CATEGORY))//Module_id,Be-tech:1929
               {
                if(defined('IS_KEY') && IS_KEY==1)
                {
                    
                    switch(SERVER_KEY)
                    {
                        case IS_BETECH:
                        {
                            ?>
                            <span class="multi-input" style="width:20px;">
                            <a id="btn_key_#xxxx#" title="[[.create_key_card.]]" target="_blank" onclick="window.open('?page=manager_key&cmd=create&resevation_room_id='+jQuery('#id_#xxxx#').val()+ '&portal=<?php echo PORTAL_ID; ?>')" ><i class="fa fa-tag w3-text-blue" style="font-size: 18px;"></i></a>
                            
                            </span>
                            <span class="multi-input" style="width:1px;">
                                <input class="items_key_hune" name="mi_reservation_room[#xxxx#][total_key]" type="text" id="total_key_#xxxx#" readonly="readonly"/>
                                
                            </span>
                            <?php 
                            break;
                        }
                        case IS_ADEL:
                        {
                            ?>
                            <span class="multi-input" style="width:20px;">
                                <img id="btn_key_#xxxx#" src="skins/default/images/key.gif" title="Tạo khóa từ" target="_blank" onclick="window.open('?page=manager_key_adel&cmd=create&resevation_room_id='+jQuery('#id_#xxxx#').val() + '&portal=<?php echo PORTAL_ID; ?>')"/>
                            </span>
                            <span class="multi-input" style="width:1px;">
                                <input class="items_key_hune" name="mi_reservation_room[#xxxx#][total_key]" type="text" id="total_key_#xxxx#" readonly="readonly"/>
                            </span>
                            <?php 
                            break;
                        }
                        case IS_SALTO:
                        {
                            ?>
                            <span class="multi-input" style="width:20px;">
                                <img id="btn_key_#xxxx#" src="skins/default/images/key.gif" title="Tạo khóa từ" target="_blank" onclick="window.open('?page=manager_key_salto&cmd=create&resevation_room_id='+jQuery('#id_#xxxx#').val() + '&portal=<?php echo PORTAL_ID; ?>')"/>
                            </span>
                            <span class="multi-input" style="width:1px;">
                                <input class="items_key_hune" name="mi_reservation_room[#xxxx#][total_key]" type="text" id="total_key_#xxxx#" readonly="readonly"/>
                            </span>
                            <?php 
                            break;
                        }
                        case IS_HUNERF:
                        {
                            ?>
                            <span class="multi-input" style="width:20px;">
                                <img id="btn_key_#xxxx#" src="skins/default/images/key.gif" title="Tạo khóa từ" target="_blank" onclick="window.open('?page=manager_key_hune&cmd=create&resevation_room_id='+jQuery('#id_#xxxx#').val()+ '&portal=<?php echo PORTAL_ID; ?>')" />
                            </span>
                            <span class="multi-input" style="width:1px;">
                                <input class="items_key_hune" name="mi_reservation_room[#xxxx#][total_key]" type="text" id="total_key_#xxxx#" readonly="readonly" />
                            </span>
                            <?php 
                            break;
                        }
                        case IS_ORBITA:
                        {
                            ?>
                            <span class="multi-input" style="width:20px;">
                                <img id="btn_key_#xxxx#" src="skins/default/images/key.gif" title="Tạo khóa từ" target="_blank" onclick="window.open('?page=manager_key_orbita&cmd=create&resevation_room_id='+jQuery('#id_#xxxx#').val()+ '&rr_id='+jQuery('#id_#xxxx#').val()+ '&portal=<?php echo PORTAL_ID; ?>')" />
                            </span>
                            <span class="multi-input" style="width:1px;">
                                <input class="items_key_hune" name="mi_reservation_room[#xxxx#][total_key]" type="text" id="total_key_#xxxx#" readonly="readonly" />
                            </span>
                            <?php 
                            break;
                        }
                        case IS_BQLOCK:
                        {
                            ?>
                            <span class="multi-input" style="width:20px;">
                                <img id="btn_key_#xxxx#" src="skins/default/images/key.gif" title="Tạo khóa từ" target="_blank" onclick="window.open('?page=manager_key_bqlock&cmd=create&resevation_room_id='+jQuery('#id_#xxxx#').val()+ '&rr_id='+jQuery('#id_#xxxx#').val()+ '&portal=<?php echo PORTAL_ID; ?>')" />
                            </span>
                            <span class="multi-input" style="width:1px;">
                                <input class="items_key_hune" name="mi_reservation_room[#xxxx#][total_key]" type="text" id="total_key_#xxxx#" readonly="readonly" />
                            </span>
                            <?php 
                            break;
                        }
                        default:
                            break;
                    } 
                }
                }
                
            ?>
            <!--end giap.ln-->
            <!---<span  class="multi-input">
                    <input  type="button" id="view_invoice_#xxxx#" onclick="viewInvoice('#xxxx#',true,false);" class="view-order-button" title="[[.view_order.]]"/>
            </span>--->
                                
            <span class="">            
					<a href="#" class="w3-button w3-cyan w3-hover-cyan w3-text-white" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=add_traveller&rr_id='+$('id_#xxxx#').value+'&r_id='+<?php echo Url::get('id');?>,Array('add_traveller_'+$('id_#xxxx#').value,'[[.add_guest.]]','20','110','1100','570'));" title="[[.add_guest.]]" style="width:100px; height: 20px; text-align: left; text-decoration: none; padding-top: 3px;">[[.add_guest.]]</a>
			</span>                                
            <span class="multi-input trrrr" <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ ?> style="display: none;" <?php } ?> >
                    <?php if(User::can_view($this->get_module_id('CreateTravellerFolio'),ANY_CATEGORY)){ ?>
					<input  type="button" id="split_invoice_#xxxx#" class="w3-button w3-lime w3-hover-lime w3-text-white" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=create_folio&rr_id='+$('id_#xxxx#').value+'&r_id='+<?php echo Url::get('id');?>,Array('folio_'+$('id_#xxxx#').value,'[[.create_folio.]]','80','210','1030','500'));" value="[[.folio.]]" title="[[.create_folio.]]" style="display:none; width:50px; height: 20px; margin-right: 5px; padding-top: 4px;"/>
                    <?php } ?>
			</span>
			<span class="multi-input" style="float:right;padding-right:5px;">
					<span style="display:none;" id="expand_#xxxx#"></span>
                    <img id="expand_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShorten('#xxxx#');"/>
			</span>
			<span class="multi-input" style="width:20px;text-align:center;float:right;" id="delete_reservation_room_#xxxx#">
				<?php
				if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a onclick="confirm_delete(#xxxx#);">
                    <i class="fa fa-times-circle w3-text-red" style="font-size: 21px;"></i> 
                    </a>
				<?php }
				?>
			</span>
			<br clear="all" />
			<span id="mi_reservation_room_sample_#xxxx#" style="display:none;">
                <div class="room-extra-info" id="room_extra_info_#xxxx#">
                    <div>
                         <span class="multi-input-extra">
                            
                            <span style="float: left; padding-right: 3px; padding-left: 5px;">[[.foc_room_charge.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][foc]" type="text" id="foc_#xxxx#" style="width:90px; margin-right: 5px;"/>
                            
                            <span style="float: left; height: 21px; border-left: 1px solid gray; margin-right: 5px;"></span>
                            
                            <span style="float: left; padding-right: 3px;">[[.foc_all.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][foc_all]" style="width:15px; margin-right: 5px;" value="1" type="checkbox" id="foc_all_#xxxx#" title="[[.foc_all_services.]]"/>
                            
                            <span style="float: left; height: 21px; border-left: 1px solid gray; margin-right: 5px;"></span>
                            
                            <span style="float: left; padding-right: 3px;">[[.discount_room_charge.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][discount_after_tax]" style="width:15px;display:none; " value="1" type="checkbox" id="discount_after_tax_#xxxx#" title="[[.discount_percent_after_tax.]]"/>
                            <input  name="mi_reservation_room[#xxxx#][reduce_balance]" type="text" id="reduce_balance_#xxxx#" style="width:20px;" maxlength="3" onchange="count_price('#xxxx#',false);" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;"/>
                            <input  name="mi_reservation_room[#xxxx#][reduce_amount]" type="text" id="reduce_amount_#xxxx#" style="width:40px; margin-right: 5px;display: none;"  class="input_number" onkeyup="this.value = number_format(this.value);"/>
                            
                            <span style="float: left; height: 21px; border-left: 1px solid gray; margin-right: 5px;"></span>
                            
                            <span style="float: left; padding-right: 3px;">[[.tax_rate_vat.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][tax_rate]" maxlength="3" style="width: 20px;" type="text" id="tax_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" <?php if(CAN_EDIT_CHARGE==0){ echo 'readonly="readonly"'; echo 'style="width:35px;background:#FCFCFC;"';}else{  echo 'style="width:35px;"';}?> onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;"/>
                            <span style="float: left; padding-right: 3px;">[[.service_rate.]](%)</span>
                            <input  name="mi_reservation_room[#xxxx#][service_rate]" maxlength="3" style="width: 20px;margin-right: 5px;" type="text" id="service_rate_#xxxx#" onkeyup="count_price('#xxxx#',false);" <?php if(CAN_EDIT_CHARGE==0){ echo 'readonly="readonly"'; echo 'style="width:35px;background:#FCFCFC;"';}else{  echo 'style="width:35px;"';}?> onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;"/>
                            
                            <span style="float: left; height: 21px; border-left: 1px solid gray; margin-right: 5px;"></span>
                            
                            <span style="float: left; padding-right: 3px;">[[.net_price.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][net_price]" class="net_price" style="width:15px; margin-right: 5px;" value="1" type="checkbox" id="net_price_#xxxx#" title="[[.net_price.]]"/>
                            
                            <span style="float: left; height: 21px; border-left: 1px solid gray; margin-right: 5px;"></span>
                            
                            <input class=" w3-gray w3-hover-orange" name="mi_reservation_room[#xxxx#][deposit_button]" type="button" value="[[.deposit.]]" id="deposit_button_#xxxx#" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=deposit&id='+$('id_#xxxx#').value+'&r_id=<?php echo Url::get('id');?>&type=RESERVATION&act=traveller&portal_id=<?php echo PORTAL_ID;?>',Array('deposit_'+$('id_#xxxx#').value,'[[.deposit.]]','80','210','1000','500'));" style="width:70px; height: 23px; text-align: center !important;"/>
                            <input  name="mi_reservation_room[#xxxx#][deposit]" type="text" id="deposit_#xxxx#" readonly="readonly" style="width:70px; background:#CCC; margin-right: 5px; height: 23px;"/>
                            
                            <span style="float: left; height: 21px; border-left: 1px solid gray; margin-right: 5px;"></span>
                            <input class=" w3-gray w3-hover-orange" name="mi_reservation_room[#xxxx#][extra_service_button]" type="button" value="[[.extra_char_room.]]" id="extra_service_button_#xxxx#" onclick="getUrlExtraChargeRoom('#xxxx#');" style="width:100px; height: 23px;"/>
                            <input class=" w3-gray w3-hover-orange" name="mi_reservation_room[#xxxx#][extra_service_button]" type="button" value="[[.extra_service.]]" id="extra_service_button_#xxxx#" onclick="getUrlExtraService('#xxxx#');" style="width:85px; height: 23px;"/>
                        </span>
                    </div>
                    <div id="extra-service" style="padding:5px 0px 5px 0px;clear:both;">
                    	<fieldset style="margin-left:20px;">
                        <legend class="title">[[.Extra_service_and_room_charge.]]</legend>
	                    <span class="multi-input-extra">
                           <span class="label">[[.extra_service.]]:</span>
                                <span id="extra_service_#xxxx#">
                                
                                </span>
                           <br clear="all" />
                           <span class="label">[[.extra_room_charge.]]:</span>
                           <span id="room_charge_#xxxx#">
                                
                           </span>
                        </span>
                        <br clear="all" />
                        </fieldset>
                     </div>
		     <div id="box_do_not_move_#xxxx#" style="clear: both; display: none;">
                        <span class="multi-input-extra">
                            <!-- manh them chuc nang do not move -->
                            <span style="float: left;">[[.do_not_move.]]</span>
                            <input  name="mi_reservation_room[#xxxx#][do_not_move]" class="do_not_move" style="width:15px; margin-right: 5px;" value="1" type="checkbox" id="do_not_move_#xxxx#" title="[[.do_not_move.]]"/>
                            <span style="float: left;">[[.user_do_not_move.]]: </span>
                            <input  name="mi_reservation_room[#xxxx#][user_do_not_move]" style="width: 150px; border: none; background: #ffffff;" type="text" id="user_do_not_move_#xxxx#" readonly="readonly" />
                            <span style="float: left;">[[.note_do_not_move.]]: </span>
                            <input  name="mi_reservation_room[#xxxx#][note_do_not_move]" style="width: 300px; padding: 3px;" type="text" id="note_do_not_move_#xxxx#" />
                            <!-- end Manh -->
                        </span>
                    </div>
                    <div id="extra_bed" style="padding:5px 0px 5px 0px;clear:both;display: none;">
                    	<fieldset style="margin-left:20px;">
                        <legend class="title">[[.Extra_bed_baby_cot.]]</legend>
	                    <span class="multi-input-extra">
                           <span class="label">[[.extra_bed.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed]" type="checkbox" id="extra_bed_#xxxx#" style="width:15px;" value="1" onclick="count_price('#xxxx#',false);" />
                           <span class="label">[[.extra_bed_from_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_from_date]" type="text" id="extra_bed_from_date_#xxxx#" class="extra_bed_from_date" onchange="count_price('#xxxx#',false);" />
                           <span class="label">[[.extra_bed_to_date.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_to_date]" type="text" id="extra_bed_to_date_#xxxx#" class="extra_bed_to_date" onchange="count_price('#xxxx#',false);" />
                           <span class="label">[[.extra_bed_rate.]]:</span>
                           <input name="mi_reservation_room[#xxxx#][extra_bed_rate]" type="text" id="extra_bed_rate_#xxxx#" class="extra_bed_rate" style="text-align:right" onkeyup="count_price('#xxxx#',false);"/>
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
					<div id="price_schedule_bound_#xxxx#" style="display:none;float:left;width:100%;padding:5px 0px 5px 0px;margin:5px 0px 5px 0px;">
						<span class="multi-input-extra">
							 <span><strong>&nbsp;&nbsp;[[.price_schedule.]]</strong></span><br />
							 <span id="price_schedule_#xxxx#"></span>
						</span>
					</div><br clear="all"/>
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
                    <br clear="all" />
                    <div style="display:none;">&nbsp;&nbsp;&nbsp;&nbsp;
                    	<span id="invoice_#xxxx#" style="display:none;" class="invoice-option">
                           	<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="room_invoice_#xxxx#" checked="checked"/>[[.room_invoice.]] |
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="hk_invoice_#xxxx#" checked="checked"/>[[.housekepping_invoice.]] |
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="bar_invoice_#xxxx#" checked="checked"/>[[.bar_invoice.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="other_invoice_#xxxx#" checked="checked"/>[[.other_invoice.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="karaoke_invoice_#xxxx#" checked="checked"/>[[.karaoke_invoice.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="vend_invoice_#xxxx#" checked="checked"/>[[.vend_invoice.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="phone_invoice_#xxxx#" checked="checked"/>[[.phone_invoice.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="extra_service_invoice_#xxxx#" checked="checked"/>[[.extra_service_invoice.]] |
                            <?php
                if((1))//HAVE_MASSAGE
                {?>
                            <input onchange="updateUrl('#xxxx#');" type="checkbox" id="massage_invoice_#xxxx#" checked="checked"/>[[.massage_invoice.]] |
                <?php
                }
                ?>
                            <?php
                if((HAVE_TENNIS))
                {?>
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="tennis_invoice_#xxxx#" checked="checked"/>[[.tennis_invoice.]] |
				<?php
				}
				?>
							<?php
				if((HAVE_SWIMMING))
				{?>
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="swimming_pool_invoice_#xxxx#" checked="checked"/>[[.swimming_pool_invoice.]] |
				<?php
				}
				?>
							<input  onchange="updateUrl('#xxxx#');" type="checkbox" id="included_deposit_#xxxx#" checked="checked"/>[[.included_deposit.]] |
                            <input  onchange="updateUrl('#xxxx#');" type="checkbox" id="included_related_total_#xxxx#" checked="checked"/>[[.included_related_total.]]
							<input  type="hidden" id="url_#xxxx#" value="&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&tennis_invoice=1&swimming_pool_invoice=1">
                        </span>
                    </div>
                    <?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><!--<span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card'));?>&id='+$('id_#xxxx#').value)">[ [[.guest_registration_card.]] ]</a>-->
				<?php
				}
				?>
<?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card_new'));?>&id='+$('id_#xxxx#').value)" style="text-decoration: none; margin-right: 20px;"><i class="fa fa-pencil-square" style="font-size: 18px;"></i> [[.reg_card.]]</a>
				<?php
				}
				?>
                <?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>4));?>&id='+$('id_#xxxx#').value)" style="text-decoration: none; margin-right: 20px;"><i class="fa fa-envelope-open" style="font-size: 18px;"></i> [[.guest_welcome_card.]]</a>
				<?php
				}
				?>

                <?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>5));?>&id='+$('id_#xxxx#').value)" style="text-decoration: none; margin-right: 20px;"><i class="fa fa-envelope-open-o" style="font-size: 18px;"></i> [[.guest_welcome_card_en.]]</a>
				<?php
				}
				?>
					<?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><!--<span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>1));?>&id='+$('id_#xxxx#').value)">[ [[.registration_form.]] ]</a>-->
				<?php
				}
				?>
                <?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><!--<span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>3));?>&id='+$('id_#xxxx#').value)">[ [[.registration_form_vn.]] ]</a>-->
				<?php
				}
				?>
                <?php
				if((User::can_view(false,ANY_CATEGORY)))
				{?><!--<span style="float:left;width:10px;height:20px;"></span><a class="view-registration-form" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>6));?>&id='+$('id_#xxxx#').value)">[ [[.guest_registration_form.]] ]</a>-->
				<?php
				}
				?>
                <?php
                if((User::can_view(false,ANY_CATEGORY)))
				{
                ?>
                <span style="float:left;width:10px;height:20px;"></span><a class="" id="titket_breakfast_#xxxx#" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>8));?>&r_id='+$('room_id_#xxxx#').value+'&id='+$('id_#xxxx#').value)" style="text-decoration: none; margin-right: 20px;"><i class="fa fa-coffee" style="font-size: 18px;"></i> [[.titket_breakfast_form.]]</a>
                <?php
				}
				?>
                <?php if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'thank_letter', 'type'=>'en'));?>&id='+$('id_#xxxx#').value)" style="text-decoration: none; margin-right: 20px;"><i class="fa fa-envelope-o" style="font-size: 18px;"></i> [[.thank_letter_en.]]</a>
				<?php
				}
				?>
                <?php if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="float:left;width:10px;height:20px;"></span><a class="" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'thank_letter', 'type'=>'vn'));?>&id='+$('id_#xxxx#').value)" style="text-decoration: none; margin-right: 20px;"><i class="fa fa fa-envelope" style="font-size: 18px;"></i> [[.thank_letter_vn.]]</a>
				<?php
				}
				?>
                <span class="w3-margin-right w3-text-blue">[[.Remarks_for_room.]] <input name="mi_reservation_room[#xxxx#][note]" type="text" id="note_#xxxx#" style="width:35%; margin-right: 5px;"/></span>
                            
                 </div>
              <div id="list_traveller_#xxxx#" style=""></div>
             </span>
             <br clear="all" />
		</div>
		</span>        
	</span>
</span>
<!--start:KID them de chuyen doi gia-->
<input type="hidden" id="exchange_rate" value="[[|exchange_rate|]]" />
<!--end:KID them de chuyen doi gia-->
<div id="mask" class="mask">[[.Please wait.]]...</div>
<form  name="AddReservationForm" method="post">
<input name="is_change_room_status" type="hidden" id="is_change_room_status"/>
<input  name="deleted_ids" type="hidden"  id="deleted_ids" value="<?php echo URL::get('deleted_ids');?>"/>
<div style="text-align:center;">
<div style="margin-right:auto;margin-left:auto;width:95%;"><!-- giap.ln cho rong khoang cach  cac o dat phong: old:1050px total -->
<!---<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;">--->
<table cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
	<tr valign="top">
		<td align="left">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-bound">
				<tr height="40">
					<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-blue" style="font-size: 30px;"></i> [[.reservation_detail.]]: <strong style="font-size: 30px; color: red;">[[|id|]]</strong></td>
                    <?php if([[=close_mice=]]==0){ ?>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;"><input name="asign" type="button" value="[[.assign.]]" class="w3-button w3-yellow"   onclick="check_room_asign();"/></td><?php }?>
    					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;"><input name="save_button" type="button" value="[[.Save_and_close.]]" class="w3-button w3-orange w3-hover-orange" onclick="checkDirtyAll('save');" style="text-transform: uppercase;" /><input class="" name="save" id="save" type="checkbox" style="display: none;"/></td><?php }?>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;"><input name="update_button" type="button" value="[[.Save_and_stay.]]" class="w3-button w3-blue w3-hover-blue" onclick="checkDirtyAll('update');"  style="text-transform: uppercase; "/><input name="update" id="update" type="checkbox" value="[[.Save_and_stay.]]" style="display: none;"/></td><?php }?>
    					<td width="1%" nowrap="nowrap" style="padding-right: 10px;"><a href="<?php echo Url::build('room_map')?>" class="w3-button w3-gray w3-hover-gray" onclick="if(!confirm('[[.are_you_sure_to_close.]] [[.if_you_close_all_datas_will_not_be_save.]]')){return false;}"  style="text-decoration: none; text-transform: uppercase;">[[.close.]]</a></td>
                        <?php if(User::can_admin(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap" style="padding-right: 10px;"><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'));?>" class="w3-button w3-red w3-hover-red" style="text-transform: uppercase; text-decoration: none;">[[.delete.]]</a></td><?php }?>
                    <?php } ?>
				</tr>
		  </table>
		</td>
	</tr>
	<tr valign="top">
	<td><?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
	<table width="100%">
	<tr><td align="left">
			<fieldset style="background:#EFEFEF;margin-bottom:5px;">
			<legend class="w3-white" style="width: 190px; height: 26px; padding: 5px 5px 0px 5px; border: 1px solid orange;">[[.general_information.]]</legend>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">                    
                <tr valign="top">                 
                 <td align="right" nowrap>&nbsp;</td>
                 <td align="right">[[.booking_code.]]</td>
                 <td align="left" style="padding-left:10px;"><input  name="booking_code" id="booking_code" style="width:170px;margin-bottom: 5px;" type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>                     
                 <td align="right">[[.booker.]]</td>                 
                 <td style="padding-left:10px;">
                 <input name="booker" type="text" id="booker" style="margin-bottom: 5px;width:170px; text-transform: uppercase; font-size: 13px;"/>
                 </td>                 
                 <td align="right" nowrap>&nbsp;</td>
                 <td rowspan="4" width="40%"><span class="label">[[.note_for_tour_or_group.]]</span>
                            <textarea  name="note" id="note"  style="width:99%;height:85px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea>
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
                                }"></a>
                 </td>
                 </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.customer.]]</td>
                        <td align="left" style="padding-left:10px;">
                        <div style=" float: left;">
                        <input name="customer_name" type="text" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off" style="width:170px;margin-bottom: 5px;" /><!--Giap comment readonly="readonly"  class="readonly"-->
                        <input name="customer_id" type="text" id="customer_id" value="[[|customer_id|]]" class="hidden" />
                        <!--IF:pointer(User::can_edit(false,ANY_CATEGORY))--><a class="w3-text-blue" href="#" onclick="window.open('?page=customer&amp;action=select_customer&customer_id_of_kid='+jQuery('#customer_id').val(),'customer')" style="text-decoration: none;"> <i class="fa fa-plus-square" style="font-size: 18px;"></i> [[.add_now.]]</a> &nbsp; <!---<a class="w3-text-blue" href="#" onClick="$('customer_name').value='';$('customer_id').value='';" style="cursor:hand;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>---><!--/IF:pointer-->
                        </div>                       
                        </td>
                        <td align="right">[[.phone_booker.]]</td>
                        <td style="padding-left:10px;">
                        <input name="phone_booker" type="text" id="phone_booker" style="margin-bottom: 5px;width:170px;"/>
                        </td>
                         
                        <td align="right" class="label">&nbsp;</td>
                    </tr>
                    <!--Giap comment 14/5/2014-->
                    <tr valign="top" style="display: none;">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.tour.]] / [[.group.]]</td>
                        <td align="left" style="padding-left:10px;">
                            <input name="tour_name" type="text" id="tour_name" style="width:170px;margin-bottom: 5px;" readonly="" class="readonly"/>
                            <input  name="tour_id" type="text" id="tour_id" value="[[|tour_id|]]" class="hidden"/>
                            <!--IF:cond(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><i class="fa fa-search" style="font-size: 18px;"></i></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"/><!--/IF:cond-->
                        </td>
                    </tr>
                    <!--Giap end-->
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        
                        
                        <?php if(RATE_CODE==1 && User::can_admin($this->get_module_id('RateCodeAdmin'),ANY_CATEGORY)){
                        ?>
                      <!--giap.ln them truong hop cho chon rate code -->
                      <td align="right">
                      <label class="w3-text-blue" for="is_rate_code" style="margin-left: 10px;">[[.use_rate_code.]]</label>
                      </td>
                      <td>
                      <input name="is_rate_code" type="checkbox" id="is_rate_code" onclick="using_rate_code(this);" style="margin-left: 10px;" /> 
                      <!---<span id="MaskHideRateCode" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none;"></span>--->
                      <!--end giap.ln -->
                      </td>
                      <?php 
                      }
                      else
                      {
                      ?>
                          <td align="right" style="display: none;">
                          <label class="w3-text-blue" for="is_rate_code" style="margin-left: 10px;">[[.use_rate_code.]]</label>
                          </td>
                          <td style="display: none;">
                          <input name="is_rate_code" type="checkbox" id="is_rate_code" onclick="using_rate_code(this);" style="margin-left: 10px;" /> 
                          </td>
                      <?php  
                      }
                      ?>
                        <td align="right">[[.email.]]</td>
                        <td style="padding-left:10px;">
                        <input name="email_booker" type="email" id="email_booker" style="margin-bottom: 5px;width:170px;"/>
                        </td>                        
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
                            <input  name="payment" id="payment" style="width:170px;margin-bottom: 5px;" type ="text" />
                            <?php echo HOTEL_CURRENCY;?>
                        </td>
                        <td bgcolor="#EFEFEF">&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.payment_type1.]]</td>
                        <td align="left" style="padding-left:10px;">
                            <select name="payment_type1" id="payment_type1" style="width:170px;margin-bottom: 5px; height: 20px;"></select> 
                        </td>
                        <td align="right">[[.color_of_group.]]</td>
                        <td align="left" style="padding-left:10px;">
                            <input name="color" type="text" id="color" style="width:148px;margin-bottom: 5px;" />
                            <span onclick="TCP.popup($('color'));" class="color-select-button" title="[[.select_color.]]"><img src="packages/core/skins/default/images/color_picker.gif" width="18"></span>
                            <?php if([[=color=]]){?><script type="text/javascript">jQuery('#color').css('background-color','[[|color|]]');jQuery('#color').css('color','[[|color|]]');</script><?php }?>
                        </td>
                        
                        <td bgcolor="#EFEFEF">
                            <?php if((User::can_edit(false,ANY_CATEGORY))){?>
                            <!--<input name="print" type="button" id="print" value="[[.preview_invoice.]]" class="button-medium-add" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'print','id','r_r_id'));?>'" />-->
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                  <td>&nbsp;</td>
                  
                  
                  <td></td>
                  </tr>
                    
                    <tr valign="top" style="line-height: 22px;">
                        <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ ?>
                            <td align="right" nowrap>&nbsp;</td>
                            <td align="right" style="color: red; font-weight: bold;">[[.mice.]]</td>
                            <td align="left" style="padding-left:10px;">
                                <a href="?page=mice_reservation&cmd=edit&id=[[|mice_reservation_id|]]" style="line-height: 22px; font-weight: bold; color: red;">[[|mice_reservation_id|]]</a>
                                <input value="[[.split.]] MICE" type="button" onclick="FunctionSplitMice('[[|mice_reservation_id|]]','REC','[[|id|]]');" style="font-weight: bold; padding: 3px 10px; margin-left: 20px;" />
                            </td>
                            <td align="center" colspan="2" style="color: blue;">[[.personnel_booking.]]: [[|user_id|]] - [[|user_name|]]</td>
                            <td align="right" nowrap>&nbsp;</td>
                        <?php }else{ ?>
                            <td></td>
                            <td align="right"><input class="w3-button w3-gray w3-hover-lime" value="[[.add_mice.]]" type="button" onclick="FunctionAddMice('REC','[[|id|]]');" style="padding: 3px 10px;" /></td>
                            <td align="left" style="padding-left:10px;"><input class="w3-button w3-gray w3-hover-lime" value="[[.select.]] MICE" type="button" onclick="FunctionSelectMice('REC','[[|id|]]');" style="padding: 3px 10px;" /></td>
                            <td align="center" colspan="2" style="color: blue;">[[.personnel_booking.]]: [[|user_id|]] - [[|user_name|]]</td>
                        <?php } ?>                                        
                            <td class="w3-margin-right" style="text-align: right;" colspan="3"><a class="w3-button w3-teal w3-hover-teal" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" href="javascript:void(0);" onclick="window.open('?page=general_statement&id=<?php echo $_GET['id']; ?>')">[[.invoice_information.]]</a>
                            <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ }else{ 
                            if(User::can_view($this->get_module_id('CreateTravellerFolio'),ANY_CATEGORY)){
                        ?> 
                        <a class="w3-button w3-indigo w3-hover-indigo" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" href="#" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=group_folio&id=<?php echo Url::get('id');?>&customer_id='+jQuery('#customer_id').val(),Array('group_folio_<?php echo Url::get('id');?>','[[.create_folio.]]','80','210','950','540'));" id="group_folio_a">[[.group_folio.]]</a>
                         <?php } } ?></td>
                    </tr>
                    
                </table>
                <br />
                <div style="border-top:1px solid #CCCCCC;margin-bottom:5px;display:none">
                    <div style="font-size:11px;border-top:1px solid #FFFFFF;padding-top:2px;">
                        <input  name="include_booked" type="checkbox" id="include_booked"/> [[.Include_booked.]] |
                        <input  type="checkbox" id="room_invoice" checked="checked"/>[[.room_invoice.]] |
                        <input  type="checkbox" id="hk_invoice" checked="checked"/>[[.housekepping_invoice.]] |
                        <input  type="checkbox" id="bar_invoice" checked="checked"/>[[.bar_invoice.]] |
                        <input  type="checkbox" id="karaoke_invoice" checked="checked"/>[[.karaoke_invoice.]] |
                        <input  type="checkbox" id="vend_invoice" checked="checked"/>[[.vend_invoice.]] |
                        <input  type="checkbox" id="other_invoice" checked="checked"/>[[.other_invoice.]] |
                        <input  type="checkbox" id="phone_invoice" checked="checked"/>[[.phone_invoice.]] |
                        <input  type="checkbox" id="extra_service_invoice" checked="checked"/>[[.extra_service_invoice.]] |
                        <!--HAVE_MASSAGE-->
                        <!--IF:cond_message(1)-->
                        <input  type="checkbox" id="massage_invoice" checked="checked"/>[[.massage_invoice.]] |
                        <!--/IF:cond_message-->
                        <!--IF:cond_tennis(HAVE_TENNIS)-->
                        <input  type="checkbox" id="tennis_invoice" checked="checked"/>[[.tennis_invoice.]] |
                        <!--/IF:cond_tennis-->
                        <!--IF:cond_swimming(HAVE_SWIMMING)-->
                        <input  type="checkbox" id="swimming_pool_invoice" checked="checked"/>[[.swimming_pool_invoice.]] |
                        <!--/IF:cond_swimming-->
                        <input  type="checkbox" id="included_deposit" checked="checked"/>[[.included_deposit.]]
                    </div>
                </div>
                <input name="confirm" id="confirm" type="hidden"  value="" />
                <div style="border-top:1px solid #CCCCCC;margin-top:5px;float:left;width:100%;">
                    <div style="font-size:11px;border-top:1px solid #FFFFFF;float:left;width:100%;text-align:center;padding-top:5px;">
                        <!---<a class="view-order-button" href="javascript:void(0);" onclick="window.open('?page=general_statement&id=<?php echo $_GET['id']; ?>')">[[.invoice_information.]]</a>
                        <a class="view-order-button" href="javascript:void(0);" onclick="window.open('?page=reservation&cmd=consolidated_invoice&id=<?php echo $_GET['id']; ?>&portal=<?php echo PORTAL_ID; ?>')">[[.view_group_invoice.]]</a>
                        <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ }else{ 
                            if(User::can_view($this->get_module_id('CreateTravellerFolio'),ANY_CATEGORY)){
                        ?> 
                        <a class="add-order-button" href="#" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=group_folio&id=<?php echo Url::get('id');?>&customer_id='+jQuery('#customer_id').val(),Array('group_folio_<?php echo Url::get('id');?>','[[.create_folio.]]','80','210','950','540'));" id="group_folio_a">[[.group_folio.]]</a>
                         <?php } } ?>--->
                        <!--<a class="print-order-button" href="#" onclick="window.open('?page=vat_invoice&cmd=entry&department=RECEPTION&type=GROUP&r_id=<?php //echo Url::get('id');?>');">[[.print_VAT_group_invoice.]]</a>-->
                        <!--<a href="#" onclick="openGroupInvoice(false);" class="view-invoice-button">[[.view_tour_invoice.]]</a> -->
                        <a class="w3-hover-text-red" target="_blank" href="<?php echo Url::build_current(array('cmd'=>'rooming_list','id'))?>" style="text-decoration: none; padding-right: 15px;"><i class="fa fa-address-book-o" style="font-size: 18px;"></i> [[.rooming_list.]]</a>
                        <a class="w3-text-black w3-hover-text-red" href="#" onClick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=group_traveller&r_id=<?php echo Url::get('id');?>&customer_id='+jQuery('#customer_id').val(),Array('group_traveller_<?php echo Url::get('id');?>','[[.add_traveller.]]','20','110','1100','570'));" id="group_traveller_a" style="text-decoration: none; padding-right: 15px;"><i class="fa fa-address-card-o" style="font-size: 18px;"></i> [[.add_traveller.]]</a>
                        <?php if(User::id()=='developer05'){ ?><a class="view-order-button" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'booking_confirm_1','id'=>Url::get('id')));?>')"><i class=""></i>Booking confirm 1</a><?php } ?>
                        <a class="w3-hover-text-red" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'booking_confirm','id'=>Url::get('id')));?>')" style="text-decoration: none; padding-right: 15px;"><i class="fa fa-file-text" style="font-size: 18px;"></i> Booking confirm</a>
                        <a class="w3-hover-text-red" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'booking_confirm','id'=>Url::get('id'),'type'=>'booking_confirm_2'));?>')" style="text-decoration: none; padding-right: 15px;"><i class="fa fa-file-text-o" style="font-size: 18px;"></i> Xác nhận đặt phòng</a>
                        <a class="w3-hover-text-red" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'group_registration','id'=>Url::get('id')));?>')" style="text-decoration: none; padding-right: 15px;"><i class="fa fa-pencil-square-o" style="font-size: 18px;"></i> [[.group_registration_card.]]</a>
                        <a class="w3-hover-text-red" target="_blank" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'guest_registration_card','form'=>8));?>&re_id=<?php echo $_GET['id']; ?>')" style="text-decoration: none; padding-right: 15px;"><i class="fa fa-coffee" style="font-size: 18px;"></i> [[.titket_breakfast_form.]]</a>
                        <a class="w3-hover-text-red" target="_blank" onclick="window.open('<?php echo '?page=history_log&recode='.Url::get('id');?>')" style="text-decoration: none; "><i class="fa fa-spinner fa-spin" style="font-size: 18px;"></i> [[.log_recode.]]</a>
                        </div>
                </div>
                
        </fieldset>
		<fieldset style="background:#E5E5E4;margin-bottom:5px;">
			<legend class="w3-white w3-text-black" style="width: 190px; height: 26px; padding: 5px 5px 0px 5px; border: 1px solid orange;">[[.reservation_room.]]&nbsp;(<span style="color: red; font-size: 20px;" id="count_number_of_room"></span> [[.room.]])</legend>
			<span id="mi_reservation_room_all_elems">
				<span>
                    <span class="multi-input-header" style="width:20px;"><input name="check_box_all" id="check_box_all" type="checkbox" onclick="fun_check_box_all();"  /></span>
                	<span class="multi-input-header" style="width:34px;">[[.index.]]</span>
                    
					<span class="multi-input-header" style="width:70px;">[[.room_level.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.room.]]</span>
					<span class="multi-input-header" style="width:38px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:38px;">[[.child.]]</span>
                    <span class="multi-input-header" style="width:48px;">[[.child_5.]]</span> <!-- trung add: tr.e<5 tuoi -->
					<span class="multi-input-header" style="width:65px;">[[.room_rate.]]</span>
                    <span class="multi-input-header" style="width:65px;">[[.usd_price.]]</span>
					<!---<span class="multi-input-header"  style="width:12px;background-image: url(packages/core/skins/default/images/buttons/rate.jpg)"></span>--->
                    <?php if(User::can_admin($this->get_module_id('ReceptionCommission'),ANY_CATEGORY)){ ?>
                    <span class="multi-input-header" style="width:42px;">[[.cms.]]</span>
					<?php } ?>
					<span class="multi-input-header" style="width:45px;">[[.time_in.]]</span>
                    <span class="multi-input-header" style="width:62px;">[[.arrival_date.]]</span>
					<!--<span class="multi-input-header" style="width:16px;"><input name="all_early_checkin" type="checkbox" onclick="jQuery('.early_checkin').attr('checked',jQuery(this).attr('checked'));" /></span>-->
					<span class="multi-input-header" style="width:45px;">[[.time_out.]]</span>					
                    <span class="multi-input-header" style="width:16px; display: none;" title="[[.early_arrival_time.]]">E.A</span>
					<span class="multi-input-header" style="width:62px;">[[.departure_date.]]</span>
					<span class="multi-input-header" style="width:45px; display: none;" title="[[.early_checkin.]]">E.I</span>
                    <span class="multi-input-header" style="width:45px; display: none;" title="[[.late_checkout.]]">L.O</span>
					<span class="multi-input-header" style="width:80px;">[[.status.]]</span>
					<span class="multi-input-header" style="width:40px; display: none;">[[.reservation_type.]]</span>
					<span class="multi-input-header" style="width:20px;" title="[[.confirm.]]">[[.CF.]]</span>
                    <span class="multi-input-header" style="width:20px;" title="[[.breakfast.]]">[[.BF.]]</span>
                    <span class="multi-input-header" style="width:20px;<?php if(!USE_ALLOTMENT){ ?> display: none; <?php } ?>" title="[[.allotment.]]">[[.ALM.]]</span>
					<span class="multi-input-header" style="width:40px;" title="[[.closed.]]"><i class="fa fa-user-times w3-text-red" style="font-size: 15px;"></i>
                    <!-- tieubinh add-->
                       <input name="check_box_close_all" id="check_box_close_all" type="checkbox" onclick="fun_check_box_close_all();" />
                    <!-- end -->
                    </span>
                    <span class="multi-input-header" style="width:72px;">Package</span>
                    <?php
                        if(User::can_admin('1929',ANY_CATEGORY))//Module_id,Be-tech:1929
                        {
                            if(defined('IS_KEY') && IS_KEY==1)
                            {
                            ?>
                            <!--giap.ln add tao nhom khoa-->
                            <span class="multi-input-header" style="width:20px;">
                                <?php
                                    switch(SERVER_KEY)
                                    {
                                        case IS_BETECH:
                                        {
                                            ?>
                                            <img src="skins/default/images/key.gif" title="Tạo nhóm khóa" target="_blank" onclick="window.open('?page=manager_key&cmd=create_group&reservation_id='+'<?php echo Url::get('id'); ?>&portal=<?php echo PORTAL_ID; ?>')" />
                                            <?php
                                            break; 
                                        }
                                        case IS_ADEL:
                                        {
                                            ?>
                                            <img src="skins/default/images/key.gif" title="Tạo nhóm khóa" target="_blank" onclick="window.open('?page=manager_key_adel&cmd=create_group&reservation_id='+'<?php echo Url::get('id'); ?>&portal=<?php echo PORTAL_ID; ?>')" />
                                            <?php 
                                            break;
                                        }
                                        case IS_SALTO:
                                        {
                                            ?>
                                            <img src="skins/default/images/key.gif" title="Tạo nhóm khóa" target="_blank" onclick="window.open('?page=manager_key_salto&cmd=create_group&reservation_id='+'<?php echo Url::get('id'); ?>&portal=<?php echo PORTAL_ID; ?>')"/>
                                            <?php 
                                            break;
                                        }
                                        case IS_HUNERF:
                                        {
                                            ?>
                                            <img src="skins/default/images/key.gif" title="Tạo nhóm khóa" target="_blank" onclick="window.open('?page=manager_key_hune&cmd=create_group&reservation_id='+'<?php echo Url::get('id'); ?>&portal=<?php echo PORTAL_ID; ?>')" />
                                            <span id="total_key_group"></span>
                                            <?php
                                            break; 
                                        }
                                        case IS_ORBITA:
                                        {
                                            ?>
                                            <img src="skins/default/images/key.gif" title="Tạo nhóm khóa" target="_blank" onclick="window.open('?page=manager_key_orbita&cmd=create_group&reservation_id='+'<?php echo Url::get('id'); ?>&portal=<?php echo PORTAL_ID; ?>')" />
                                            <?php
                                            break; 
                                        }
                                        case IS_BQLOCK:
                                        {
                                            ?>
                                            <img src="skins/default/images/key.gif" title="Tạo nhóm khóa" target="_blank" onclick="window.open('?page=manager_key_bqlock&cmd=create_group&reservation_id='+'<?php echo Url::get('id'); ?>&portal=<?php echo PORTAL_ID; ?>')" />
                                            <?php
                                            break; 
                                        }
                                        default:
                                            break;
                                    } 
                                ?>
                                                                
                            </span>
                            <!--giap.ln end-->
                            <?php 
                            }
                        }
                    
                    ?>
					<span class="multi-input-header" style="float:right;border:0px;background:none;">&nbsp;</span>
					<span id="expand_all_span" style="float:right;"><img id="expand_all" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandAll();"></span>
                    <!--Oanh add -->
                    <span id="hidden_room_cancel_span" style="display: none;" ><input type="checkbox" name="hidden_room_cancel" id="hidden_room_cancel" checked="checked" onclick="hiddenRoomCancel();"/>[[.hidden_room_cancel.]]</span>   
                    <!--End Oanh -->
					<br clear="all"/>
				</span>
			</span>
            <br clear="all"/>
           	<input type="button" value="[[.add_room.]]" onclick="var roomCount = to_numeric($('count_number_of_room').innerHTML);$('count_number_of_room').innerHTML=roomCount+1;mi_add_new_row('mi_reservation_room');updateStatusList(input_count);updateInput(input_count);AddInput(input_count);buildRateList(input_count);buildCommissionList(input_count);if($('index_'+input_count)){$('index_'+input_count).innerHTML = input_count;}" class="w3-button w3-gray w3-hover-cyan w3-hover-text-white"/>
            <br clear="all"/>
            <br clear="all"/>
<!---THAY DOI CHO GROUP--->
            <hr style="margin: 20px auto 0px auto; padding: 0px;" /><hr style="margin: 0px auto 5px auto; padding: 0px;" />
            <fieldset id="change_for_group_div" style="width: 99%; margin: 0px auto; background: #F2F4CE; border: 1px dashed #171717;">
                <legend style="font-weight: bold; text-transform: uppercase;">[[.change_for_group.]]</legend>
                <table style="width: 100%;">
                    <tr style="background: #fac73e;">
                        <td style="width: 180px; padding-left: 40px;">[[.room_type.]]</td>
                        <td style="width: 125px;">[[.adult.]]</td>
                        <td style="width: 70px;">[[.price.]]</td>
                        <td style="width: 65px;">[[.usd_price.]]</td>
                        <td style="width: 40px;"><?php if(User::can_admin($this->get_module_id('ReceptionCommission'),ANY_CATEGORY)) {?>[[.cms.]]<?php }?></td>
                        <td style="width: 45px;">[[.time_in.]]</td>
                        <td style="width: 65px;">[[.arrival_time.]]</td>
                        <td style="width: 50px;">[[.time_out.]]</td>
                        <td style="width: 65px;">[[.departure_time.]]</td>
                        <td style="width: 30px; display: none;">E.I</td>
                        <td style="width: 30px; display: none;">L.O</td>
                        <td style="width: 77px;">[[.status.]]</td>
                        <td style="width: 20px;">[[.CF.]]</td>
                        <td>[[.BF.]]</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #171717;">
                        <td style="width: 180px; padding-left: 40px; cursor: pointer;" onclick="fun_Check_Availblity(input_count,'<?php echo Url::get('cmd');?>');"><i class="fa fa-refresh w3-text-blue" style="font-size: 18px;"></i> [[.choose_room_type.]]</td>
                        <td style="width: 30px;"><input type="text" id="add_all" class="addall" onkeyup="changeAdultFunc();" style="text-align:right; width: 30px;" /></td>
                        <td style="width: 70px;"><input type="text" id="change_all_price" class="input_number" autocomplete="OFF" onkeyup="changePriceFunc();" style="width:70px; text-align:right;" /></td>
                        <td style="width: 65px;"><input type="text" id="change_all_usd_price" class="input_number" autocomplete="OFF" onkeyup="changeUsdPriceFunc();" style="width:65px; text-align:right;" /></td>
                        <td style="width: 40px;"><?php if(User::can_admin($this->get_module_id('ReceptionCommission'),ANY_CATEGORY)) {?><select  name="change_all_commission_rate" id="change_all_commission_rate" onchange="changeAllCommissionRate(this.value);">[[|commission_rate_options|]]</select><?php }?></td>
                        <!-- Oanh add thay sua gio cho ca doan -->
                        <td style="width: 45px;"><input type="text" style="width: 45px;" id="all_time_in" title="00:00" class="hour-input" onkeyup="changeTime(this.value,'TI');" /></td>
                        <td style="width: 65px;"><input type="text" style="width: 65px; height: 21px;" id="all_arrival_time"  class="date-input" onchange="changeAllTime(this.value,'AT');" /></td>
                        <td style="width: 50px;"><input type="text" style="width: 45px;" id="all_time_out" title="00:00" class="hour-input" onkeyup="changeTime(this.value,'TO');" /></td>
                        <!-- End Oanh add thay sua gio cho ca doan -->                        
                        <td style="width: 65px;"><input type="text" style="width: 65px; height: 21px;" id="all_departure_time"  class="date-input" readonly="readonly" onchange="changeAllTime(this.value,'DT');" /></td>
                        <td style="width: 30px; display: none;"><select name="all_early_checkin" style="width: 50px;" id="all_early_checkin"  onchange="fun_change_all_ei(this.value);">[[|verify_dayuse_options|]]</select></td>
                        <td style="width: 30px; display: none;"><select name="all_late_checkout" style="width: 50px;" id="all_late_checkout" onchange="fun_change_all_lo(this.value);">[[|verify_dayuse_options|]]</select></td>
                        <td style="width: 77px;"><select  name="change_all_status" id="change_all_status" onchange="changeAllStatus(this.value,input_count);if(this.value=='CHECKOUT'){ <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ }else{ ?> check_folio(0,1); <?php } ?> };if(this.value=='CANCEL') { check_cancel_deposit(0,1);}" style="width: 80px; height: 21px;"><option value="">[[.status.]]</option><option value="CHECKIN">CHECKIN</option><option value="CHECKOUT">CHECKOUT</option><option value="CANCEL">CANCEL</option><option value="BOOKED">BOOKED</option></select></td>
                        <td style="width: 25px; text-align: center;"><input type="checkbox" class="checkbox" id="all_confirm" value="" onclick="CheckAll('cf');"/></td>
                        <td style=""><input type="checkbox" class="cb_bf" id="all_bf" value="" onclick="CheckAll('bf');"/></td>
                    </tr>
                    <tr>
                        <td colspan="14" style="text-align: center; ">
                            <input class="w3-cyan w3-hover-cyan w3-text-white" name="deposit_for_group" type="button" id="deposit_for_group" style="border: 1px solid #dddddd; height: 28px; <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ echo "display: none;"; } ?>" value="[[.deposit_for_group.]]" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=deposit&act=group&id=<?php echo Url::get('id');?>&type=RESERVATION&customer_id=[[|customer_id|]]&portal_id=<?php echo PORTAL_ID;?>',Array('deposit_group_<?php echo Url::get('id');?>','[[.deposit.]]','80','210','1000','500'));" />
                            <!-- manh -->
                            <?php if([[=deposit=]]>0){ ?>
                            <span>[[.deposit_monney.]]: <?php echo System::display_number([[=deposit=]]);?> |</span> 
                            <?php }else{ echo ""; } ?>
                            <!-- end manh -->
                            |
                            [[.deposit_cut_of_date.]] : <input name="deposit_cut_of_date" type="text" id="deposit_cut_of_date" style="width:65px;" /> 
                            |
                            [[.cut_off_day.]]: <input name="cut_of_date" type="text" id="cut_of_date" style="width:65px;" class="date-input" /> 
                            | 
                            [[.net_price.]]: <input name="change_all_net_price" type="checkbox" id="change_all_net_price" onclick="fun_change_all_net();" /> 
                            |
                            [[.foc_group.]]: <input name="foc_group" type="checkbox" id="foc_group" onclick="func_chang_foc_group();" />
                            |
                            [[.foc_all_group.]]: <input name="foc_all_group" type="checkbox" id="foc_all_group" onclick="func_chang_foc_all_group();" />
                            | 
                            [[.discount_for_group.]]: <input type="text" id="discount_for_group" class="input_number" onkeyup="changeAllDiscount(this.value);" style="width:30px;"/>(%)
                            | 
                            [[.copy_room.]] <input  name="room_indexs" id="room_indexs" value="[[.index.]]1,[[.index.]]2" onclick="if(this.value=='[[.index.]]1,[[.index.]]2'){this.value='';}" onblur="if(this.value==''){this.value='[[.index.]]1,[[.index.]]2';}" type ="text" value="<?php echo String::html_normalize(URL::get('room_indexs'));?>" style="width:50px;" />  [[.quantity.]] 
                            <input  name="room_quantity" id="room_quantity" style="width:20px;" size="100" maxlength="3" type ="text" />
                            <input type="button" value="[[.copy.]]" onclick="copyRoom($('room_indexs').value,$('room_quantity').value,input_count)"/>
                        </td>
                    </tr>
                    <!-- Manh them doi gia theo ngay cho toan doan -->
                    <tr>
                        <td colspan="14" style="text-align: center;">
                            <fieldset style="width: 100%; text-align: center;">
                                <legend style="text-transform: uppercase;">[[.change_price_in_date_on_group.]]</legend>
                                [[.select_room_level.]]:
                                <select onchange="func_change_price_in_date();" id="room_level_group" name="room_level_group" style="height: 20px;">[[|room_level_options|]]</select>
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
        <a onclick="$('rate_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]"/></a>
    </div>
    <ul id="rate_list_result">
    </ul>
</div>
<div id="commission_list" class="room-rate-list" style="display:none;">
    <div>
        [[.commission_list.]]&nbsp;&nbsp;
        <a onclick="$('commission_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]"/></a>
    </div>
    <ul id="commission_list_result">
    </ul>
</div>
<br/>
<br/>
<br/>
<br/>
<br/>
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
<script>
var mice_reservation_id = '[[|mice_reservation_id|]]';
jQuery("#date_group").datepicker();
<?php
    if(Url::get('adddd_guest') and Url::get('adddd_guest') == 'yes')
    {
?>
    openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=group_traveller&r_id=<?php echo Url::get('id');?>&customer_id='+jQuery('#customer_id').val(),Array('group_traveller_<?php echo Url::get('id');?>','[[.add_traveller.]]','20','110','990','570'));
<?php 
    } 
?>
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
//alert CURRENT_MONTH;
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
//Oanh add thoi gian thay doi cho ca Doan
jQuery(".hour-input").mask("99:99");
//End Oanh
jQuery("#cut_of_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
jQuery("#rate_list").mouseover(function(){
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
var currentHour = '<?php echo date('H:i');?>';
var currentDate = '<?php echo date('d/m/Y');?>';
<?php if(User::can_admin(false,ANY_CATEGORY)){
	echo 'var can_admin = true;'; }else{ echo 'var can_admin = false;';} ?>
<?php if(User::can_delete(false,ANY_CATEGORY)){
	echo 'var can_delete = true;'; }else{ echo 'var can_delete = false;';} ?>
	function handleKeyPress(evt) {
		var nbr;
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==13)
        {
			if(!confirm('[[.Are_you_sure_to_update_reservation.]]?'))
            {
				return false;
			}
		}
		if(nbr==116)
        {
			if(!confirm('[[.Are_you_sure_to_refresh.]]?'))
            {
				return false;
			}
		}
		return true;
	}
	//document.onkeydown= handleKeyPress;
vip_card_list = <?php echo String::array2js([[=vip_card_list=]]);?>;
var holidays = [[|holidays|]];
var saturday_charge = <?php echo EXTRA_CHARGE_ON_SATURDAY;?>;
var sunday_charge = <?php echo EXTRA_CHARGE_ON_SUNDAY;?>;
var currency_arr = {};

<?php if(isset($_REQUEST['mi_reservation_room']))
{
    echo 'var mi_reservation_room_arr = '.String::array2js($_REQUEST['mi_reservation_room']).';';
       
?>
<?php          
    echo 'mi_init_rows(\'mi_reservation_room\',mi_reservation_room_arr);';
}
else
{
    echo 'mi_add_new_row(\'mi_reservation_room\',true);';
}
?>
<?php if(User::can_admin($this->get_module_id('PrivilegeDoNotMove'),ANY_CATEGORY)){ ?> 
for(var i=101;i<=input_count;i++)
{
    if(jQuery("#status_"+i).val()=='BOOKED' && jQuery("#room_id_"+i).val()!='')
    {
        jQuery("#box_do_not_move_"+i).css('display','');
    }
}
<?php } ?>
/**
console.log(mi_reservation_room_arr);
for(var key in mi_reservation_room_arr)
{
    mi_reservation_room_arr[mi_reservation_room_arr[key]['id']] = mi_reservation_room_arr[key];
    delete mi_reservation_room_arr[key];
}
console.log(mi_reservation_room_arr);
**/

function sortNumber(a,b) 
{
     if(isNormalInteger(a) && isNormalInteger(b)){
        return a - b;
     }
     else
     {
           if(isNormalInteger(a))
           {
                return 0;
           }
           else if(isNormalInteger(b)){
                return 1;
           }
           else
           {
                return a>b;
           }
     }
}
function isNormalInteger(str) {
     return /^\+?(0|[1-9]\d*)$/.test(str);
}


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
var roomCount = to_numeric($('count_number_of_room').innerHTML);
<!--IF:cond(Url::get('r_r_id'))-->
var r_r_id = <?php echo Url::get('r_r_id');?>;
<!--ELSE-->
var r_r_id = 0;
<!--/IF:cond-->

function updateInput(input_count){
	jQuery('#price_'+input_count).ForceNumericOnly().FormatNumber();
	//jQuery('.date-select').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	jQuery('#time_in_'+input_count).mask('99:99');jQuery('#time_out_'+input_count).mask('99:99');
    //jQuery('#arrival_time_'+input_count).val('<?php //echo date('d/m/Y');?>');
    //jQuery('#departure_time_'+input_count).val('<?php //echo date('d/m/Y',time()+86400);?>');
	
    /**Kimtan phan quyen 2 o gia va gia usd ngay 01/10/15 cua odeon
    -phong book thi phai co quyen add hoac edit hoac admin cua nut Booked_Checkin_CheckOut_editPrice (554) moi sua dc gia
    -phong ci thi phai co quyen edit hoac admin cua nut Booked_Checkin_CheckOut_editPrice (554) moi sua dc gia
    -phong co thi phai co quyen admin cua nut Booked_Checkin_CheckOut_editPrice (554) moi sua dc gia
    - co 1 phan phan quyen tương tu quy tac nay cho luoc do gia o reservation.js
    **/
    if(CAN_ADMIN_PRICE==true) // quyen admin cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
	{	
	    jQuery('#price_'+input_count).attr('readonly',false);
		jQuery('#price_'+input_count).removeClass('readonly');
        
        jQuery('#usd_price_'+input_count).attr('readonly',false);
        jQuery('#usd_price_'+input_count).removeClass('readonly');
    }else{
        if(CAN_EDIT_PRICE==true){ // quyen edit cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
            if(jQuery('#status_'+input_count).val()!='CHECKOUT')
            {
                //gia
                jQuery('#price_'+input_count).attr('readonly',false);
                jQuery('#price_'+input_count).removeClass('readonly'); 
                //gia usd
                jQuery('#usd_price_'+input_count).attr('readonly',false);
                jQuery('#usd_price_'+input_count).removeClass('readonly');
            }
            else{
                jQuery('#price_'+input_count).attr('readonly',true);
                jQuery('#price_'+input_count).addClass('readonly');
                
                jQuery('#usd_price_'+input_count).attr('readonly',true);
                jQuery('#usd_price_'+input_count).addClass('readonly');
                
                document.getElementById("net_price_"+input_count).disabled = true;
            }
        }
        else
        {
            if(CAN_ADD_PRICE==true){ // quyen add cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
                //console.log('111111111111');
                if(jQuery('#status_'+input_count).val()=='BOOKED')
                {
                    //gia
                    jQuery('#price_'+input_count).attr('readonly',false);
                    jQuery('#price_'+input_count).removeClass('readonly'); 
                    //gia usd
                    jQuery('#usd_price_'+input_count).attr('readonly',false);
                    jQuery('#usd_price_'+input_count).removeClass('readonly');
                }
                else{
                    jQuery('#price_'+input_count).attr('readonly',true);
                    jQuery('#price_'+input_count).addClass('readonly');
                    
                    jQuery('#usd_price_'+input_count).attr('readonly',true);
                    jQuery('#usd_price_'+input_count).addClass('readonly');
                    
                    document.getElementById("net_price_"+input_count).disabled = true;
                }
            }
            else
            {
                jQuery('#price_'+input_count).attr('readonly',true);
                jQuery('#price_'+input_count).addClass('readonly');
                
                jQuery('#usd_price_'+input_count).attr('readonly',true);
                jQuery('#usd_price_'+input_count).addClass('readonly');
                
                document.getElementById("net_price_"+input_count).disabled = true;
            }
        }
    }
    ///**END Kimtan phan quyen 2 o gia va gia usd ngay 01/10/15 cua odeon
}
function AddInput(input_count){
	jQuery('#net_price_'+input_count).attr('checked',<?php if(NET_PRICE==1){echo 'true';}else{ echo 'false';}?>);
	jQuery('#tax_rate_'+input_count).val(<?php echo RECEPTION_TAX_RATE;?>);
	jQuery('#service_rate_'+input_count).val(<?php echo RECEPTION_SERVICE_CHARGE;?>);
    /** oanh add tu dong tich an sang cho them phong moi **/
    jQuery('#breakfast_'+input_count).attr('checked',true);
    /** End oanh add tu dong tich an sang cho them phong moi **/
}
jQuery(document).ready(function(){
    var start = 101;
    /** Son Le Thanh them de an thanh toan Folio **/
    if(mice_reservation_id != ''){
        
        jQuery('.hidden_foilo').css('display','none');    
    }
     /** Son Le Thanh them de an thanh toan Folio **/
    for(var key in mi_reservation_room_arr)   //(cÃ¡i nÃ y Ä‘á»ƒ giáº¥u lÃºt folio trong trÆ°á»ng há»£p Ä‘á»•i phÃ²ng)
    {
       //console.log(mi_reservation_room_arr[key]);
       var change_room_to_rr = mi_reservation_room_arr[key]['change_room_to_rr'];
       var room_status = mi_reservation_room_arr[key]['status'];
       var traveller = mi_reservation_room_arr[key]['traveller_id'];
       if (change_room_to_rr != '' && room_status=='CHECKOUT' && traveller != 0)
       {
          //console.log('doiphong');
          jQuery('#split_invoice_'+start).css('display','');
          <?php //if(!User::can_admin(ANY_CATEGORY)) {?>
          //jQuery('#split_invoice_'+start).css('visibility','hidden'); bo de theo quy trinh moi an folio phong co va hien folio phong duoc doi den dang ci
          <?php //}?>
       }
       start++;
  }
    //láº¥y status cá»§a cÃ¡c phÃ²ng trong cÃ¹ng 1 reservation
    //Kimtan: an nut tao hoa don nhom neu tat ca cac phong checkout doi voi tk thuong
    var s_check = true;
    var asign_room_stt = 1;
    for(var i=101;i<=input_count;i++)//(cÃ¡i nÃ y giáº¥u nÃºt táº¡o hÃ³a Ä‘Æ¡n nhÃ³m phÃ²ng Ä‘Ã£ check out Ä‘á»‘i vá»›i tÃ i khoáº£n thÆ°á»ng)
    {
        if(jQuery('#status_'+i).val()!='CHECKOUT' && jQuery('#status_'+i).val()!='CANCEL'){
            s_check = false;
        }
        if(jQuery('#room_name_'+i).val()==""){
            jQuery('#room_name_'+i).val('#'+asign_room_stt);
            asign_room_stt ++;
        }
    }
    /** Manh: khong cho tich rate code khi tat ca cac phong da out **/
    if(s_check){
        jQuery("#MaskHideRateCode").css('display','');
    }
    /** end Manh **/
    /*if(s_check==true){
        <?php
        //if(!User::can_admin($this->get_module_id('CanAdminFolio'),ANY_CATEGORY))//1943 la id ,1020301000000000000 la strac id cua module phan quyen nut folio
        //{
          ?>
          console.log('hhh');
          jQuery('#group_folio_a').css('display','none'); 
          <?php  
        //}
        ?>
    }*/
    
    //end Kimtan: an nut tao hoa don nhom neu tat ca cac phong checkout doi voi tk thuong
    // oanh them
    var check_room_cancel = false;
    for(var i=101;i<=input_count;i++)//(cÃ¡i nÃ y giáº¥u nÃºt táº¡o hÃ³a Ä‘Æ¡n nhÃ³m phÃ²ng Ä‘Ã£ check out Ä‘á»‘i vá»›i tÃ i khoáº£n thÆ°á»ng)
    {
        if(jQuery('#status_'+i).val()=='CANCEL'){
            check_room_cancel = true;
        }
        if(jQuery('#status_'+i).val()=='CANCEL')
        {
            document.getElementById("reservation_room_bound_"+i).style.display='none';
        }
    }
    if(check_room_cancel==true){
        jQuery('#hidden_room_cancel_span').css('display','');
    }
    // end oanh them
    jQuery('.early_arrival_time').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, (CURRENT_DAY-1)) });
    jQuery(".reservation_time_in").mask("99:99");
    jQuery(".reservation_time_out").mask("99:99");
    jQuery(".extra_bed_rate").ForceNumericOnly().FormatNumber();
    jQuery(".baby_cot_rate").ForceNumericOnly().FormatNumber();
    jQuery('.reservation_status').each(function(){
        var id = jQuery(this).attr('id');
       // console.log(id);
		var i = id.substr((id.lastIndexOf('_')+1),id.length);
		if(i != '#xxxx#' && is_numeric(i)){
			if($('index_'+i)){
				$('index_'+i).innerHTML = i;
			}
			updateInput(i);
			count_price(i,false);
			updateStatusList(i);
			buildRateList(i);
            buildCommissionList(i);
			jQuery('#mi_reservation_room_sample_'+i).hide();
			if(jQuery(this).val()!='CANCEL' && jQuery(this).val()!='NOSHOW'){
				roomCount++;
			}
			//Kimtan: an nut folio neu phong checkout voi tk thuong
            if(jQuery('#list_traveller_'+i).html()!=''){
				if(jQuery(this).val()=='CHECKOUT')
                {
                    <?php 
                        if(User::can_admin($this->get_module_id('CanAdminFolio'),ANY_CATEGORY))
                        {
                    ?>   
                        jQuery('#split_invoice_'+i).css('display','');
                    <?php 
                        }
                    ?>
                }
                else
                {
                    jQuery('#split_invoice_'+i).css('display','');
                }
			}
            //Kimtan: an nut folio neu phong checkout voi tk thuong
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
            //Giap change status check out => check in , check in=>book cho tai khoan le tan 
            <?php 
                if(User::is_admin())
                {}else{
            ?> 
				if(jQuery(this).val() == 'CHECKIN' || jQuery(this).val()=='CHECKOUT'){
					jQuery('#time_in_'+i).attr('readonly',true);
					jQuery('#time_in_'+i).className = 'readonly';
					jQuery('#arrival_time_'+i).attr('readonly',true);
					jQuery('#arrival_time_'+i).className = 'readonly';
                    if(jQuery(this).val()=='CHECKOUT')
                    {
                        jQuery('#time_out_'+i).attr('readonly',true);
                        jQuery('#time_out_'+i).addClass('readonly');
                        jQuery('#departure_time_'+i).attr('readonly',true);  
                        jQuery('#departure_time_'+i).addClass('readonly');
                        jQuery('#departure_time_'+i).datepicker('disable');                                
                    }
					datePickerForArrival=false;
                    
                    if(!this.selected){
                        this.options[0].disabled=true;
                        }
				}
                <?php }?>
				<?php 
                if(!User::can_admin(false,ANY_CATEGORY))//KimTan:nếu ko là admin
                {
                    if($_REQUEST['is_change_room_status']==0)
                    {
                ?>
				if(jQuery(this).val() == 'CHECKOUT' || (CAN_CHECKIN == false && jQuery(this).val()=='CHECKIN')){
                    <?php 
                        if(User::can_admin($this->get_module_id('CanAdminFolio'),ANY_CATEGORY))
                        {
                    ?>   
                    //console.log('bbbbbbb');
                    jQuery('#split_invoice_'+i).css('display','');
                    <?php 
                        }
                    ?>
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
              // console.log('khong la admin');
                <?php }else{ 
                    if(User::can_admin($this->get_module_id('CanAdminFolio'),ANY_CATEGORY))
                    {
                ?>
                    jQuery('#split_invoice_'+i).css('display','');
                <?php }}?>
                <?php 
                //neu khong duoc cap quyen tu checin=>book thi disable option book
                if($_REQUEST['is_change_checkin_book']==0)
                {
                ?>
                <?php } ?>
				if(jQuery(this).val() == 'BOOKED' && $('customer_name').value){
					jQuery('#price_'+i).attr('readonly',false);
					$('price_'+i).className = 'readonly';
				}else if(jQuery(this).val() != 'BOOKED'){
					//start: KID cmt dong nay de cai chinh sach gia no hien thi ra
					//jQuery('#rate_list_'+i).css({'display':'none'});
                    //end
				}
			<?php } 
            ?>
			if(jQuery('#id_'+i).val() == r_r_id && jQuery('#status_'+i).val()!='CANCEL'){
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
/** manh them de xem hoa don tam tinh tu bao cao **/
var reservation_room_id_view = to_numeric(<?php echo Url::get('r_r_id'); ?>);
<?php if(Url::get('view_report')!=''){ ?>
var view_report = to_numeric(<?php echo Url::get('view_report'); ?>);
<?php }else{ ?>
var view_report = 0;
<?php } ?>
for(var ij=101;ij<=input_count;ij++)
{
    if(to_numeric(jQuery("#id_"+ij).val())==reservation_room_id_view)
    {
        if(view_report==1){
            viewInvoice(ij,true,true);
        }
    }
    
}
/** end manh **/

function SetupAllotment(index){
    <?php if(USE_ALLOTMENT){ ?>
    //count_price(index,false);
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

function updateAll(){
	for(var i=101;i<=input_count;i++){
		if($('index_'+i)){
			$('index_'+i).innerHTML = i;
		}
		count_price(i,false);
		updateStatusList(i);
		buildRateList(i);
        buildCommissionList(i);
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
            //console.log(child);
           // var child = jQuery("#child_"+i+'_1').val();
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
    if($('karaoke_invoice_'+index).checked == true){
        url += '&karaoke_invoice=1';
    }
    if($('vend_invoice_'+index).checked == true){
        url += '&vend_invoice=1';
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
    //HAVE_MASSAGE
	<!--IF:cond_massage(1)-->
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


function viewInvoice(index,preview,report){
	updateUrl(index);
	if($('id_'+index).value!='')
	{
		var url = '?page=reservation';
		if(preview==false){
			url += '&<?php echo md5('print')?>=<?php echo md5('1')?>';
		}else{
			url += '&<?php echo md5('print')?>=<?php echo md5('0')?>';
		}
		url += $('url_'+index).value+'&ticket_invoice=1';
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
		if(report==false)
        {
		  window.open(url);
        }
        else
        {
            location.href = url;
        }
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
        url += '&bar_invoice=1&ticket_invoice=1';
    }
    if($('karaoke_invoice').checked){
        url += '&karaoke_invoice=1';
    }
    if($('vend_invoice').checked){
        url += '&vend_invoice=1';
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
    //HAVE_MASSAGE
	<!--IF:cond_massage(1)-->
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

function isEmail(){
	var isValid = false;
	var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(regex.test(jQuery('#email_booker').val())) {
		isValid = true;
	}
	return isValid;
}
var last_time = [[|last_time|]];
function checkDirtyAll(key)
{
    <?php echo 'var reservation_id = '.Url::get("id").';';?> 
    <?php echo 'var block_id = '.Module::block_id().';';?>
    jQuery.ajax({
				url:"form.php?block_id="+block_id,
				type:"POST",
                dataType: "json",
				data:{check_last_time:1,reservation_id:reservation_id,last_time:last_time},
				success:function(html)
                {
                    if(html['status']=='error')
                    {
                        alert('RealTime:\n Lưu ý, ReCode '+reservation_id+' đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !');
                        return false;
                    }
                    else
                    {
                        /**start kieu check email hop le **/
                            if(jQuery('#email_booker').val()!=''&&isEmail()==false){
                                alert('email không đúng định dạng');
                                return false;
                            }
                        /** end kieu check email hop le **/
                        jQuery("input").each(function(){
                            jQuery(this).removeAttr('disabled');
                        });
                        var $Rooms = '';
                        for(var index=101; index<=input_count;index++)
                        {
                            if($('id_'+index) && $('id_'+index).value!=undefined)
                            {
                                var id_check = $('id_'+index).value;
                                var room_id_check = $('room_id_'+index).value;
                                var room_name_check = $('room_name_'+index).value;
                                var status_check = $('status_'+index).value;
                                var in_date_check = $('arrival_time_'+index).value;
                                
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
                        if($Rooms=='')
                        {
                            if(key=='save')
                            {
                                checkSave('save'); 
                                jQuery('#mask').show();
                                optionAddGuest();
                            }
                            else
                            {
                                 checkSave('update'); 
                                 jQuery('#mask').show();
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
                                        if(key=='save')
                                        {
                                            checkSave('save'); 
                                            jQuery('#mask').show();
                                            optionAddGuest();
                                        }
                                        else
                                        {
                                             checkSave('update'); 
                                             jQuery('#mask').show();
                                        }
                                        return true;
                                    }
                                }
                            }
                            xmlhttp.open("GET","check_dirty.php?data=check_dirty_all&rooms="+$Rooms,true);
                            xmlhttp.send();
                        }
                    }
				}
	});
    
}

function checkDirty_Repair(index,messeger)
{
    // neu khong ton tai messeger thi cho thong bao bang alert luon va tra ve true | flase
    // neu ton tai thi chi tra ve true | false
    var id_check = $('id_'+index).value;
    var room_id_check = $('room_id_'+index).value;
    var room_name_check = $('room_name_'+index).value;
    var status_check = $('status_'+index).value;
    var in_date_check = $('arrival_time_'+index).value;
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
        for(var i=101;i<=input_count;i++)
        {
            //giap.ln dieu kien nhung checkbox duoc chon moi duoc sua o gia
            if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
            {
                if(CAN_ADMIN_PRICE==true)
                 {
                    jQuery("#usd_price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val()/jQuery('#exchange_rate').val())));
                    jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val())));
                    count_price(i,true);
                 }
                 else
                 { 
                //console.log(jQuery('#status_'+i).val());
                    if(CAN_EDIT_PRICE==true)
                    {
                        if(jQuery('#status_'+i).val()!='CHECKOUT')
                        {
                            jQuery("#usd_price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val()/jQuery('#exchange_rate').val())));
                            jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val())));
                            count_price(i,true);
                        }
                    }
                    else
                    {
                        if(CAN_ADD_PRICE==true)
                        {
                            if(jQuery('#status_'+i).val()=='BOOKED')
                            {
                                jQuery("#usd_price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val()/jQuery('#exchange_rate').val())));
                                jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_price').val())));
                                count_price(i,true);
                            }
                        }
                    } 
                } 
            }
        }

        jQuery("#change_all_usd_price").val(number_format(to_numeric(jQuery('#change_all_price').val()/jQuery('#exchange_rate').val())));
}
//start:KID them ham doi gia USD cho tat ca cac phong
function changeUsdPriceFunc()
{
    for(var i=101;i<=input_count;i++)
    {
        //giap.ln nhung checkbox duoc chon moi dua sua o gia
        if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
        {
            if(CAN_ADMIN_PRICE==true)
            {
                jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val()*jQuery('#exchange_rate').val())));     
                jQuery('#usd_price_'+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val())));
                count_price(i,true);
            }
            else
            {
                if(CAN_EDIT_PRICE==true)
                {
                    if(jQuery('#status_'+i).val()!='CHECKOUT')
                    {
                        jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val()*jQuery('#exchange_rate').val())));     
                        jQuery('#usd_price_'+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val())));
                        count_price(i,true);
                    }
                }
                else
                {
                    if(CAN_ADD_PRICE==true)
                    {
                        if(jQuery('#status_'+i).val()=='BOOKED')
                        {
                            jQuery("#price_"+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val()*jQuery('#exchange_rate').val())));     
                            jQuery('#usd_price_'+i).val(number_format(to_numeric(jQuery('#change_all_usd_price').val())));
                            count_price(i,true);
                        }
                    }
                }
            }
        }
             
    }
    jQuery("#change_all_price").val(number_format(to_numeric(jQuery('#change_all_usd_price').val()*jQuery('#exchange_rate').val())));
}
//end:KID them ham doi gia USD cho tat ca cac phong

//Start: KID them ham nay de khi nhap so nguoi vao thi toan bo so nguoi tren tung phong thay doi luon

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
//End: KID

function checkPayment(index)
{
	if(index=='ALL')
    {
		var room_name = '';
		for(var j=101; j<=input_count;j++)
        {
			if($('id_'+j) && $('id_'+j).value !='')
            {
				var id = $('id_'+j).value;
				if(mi_reservation_room_arr[id] && mi_reservation_room_arr[id]['check_payment'] == 0 && $('status_'+j).value!='CHECKIN')
                {
					$('status_'+j).value = 'CHECKIN';
					if(room_name!='')
                    {
						room_name += ',';
					}
					room_name += mi_reservation_room_arr[id]['room_name'];
				}
			}
		}
		if(room_name =='')
        {
			return true;
		}
        else
        {
			alert('Chưa thanh toán cho phòng: '+room_name);
			return false;
		}
	}
    else if($('id_'+index) && $('id_'+index).value !='')
    {
		var id = $('id_'+index).value;
		if(mi_reservation_room_arr[id] && mi_reservation_room_arr[id]['check_payment'] == 0)
        {
			alert('Chưa thanh toán cho phòng');
			$('status_'+index).value = 'CHECKIN';
			return false;
		}
		return true;
	}
}

function windowOpenUrlTraveller(rr_id,r_id,rt_id){
	openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_CREATE_FOLIO;?>&cmd=create_folio&rr_id='+rr_id+'&r_id='+r_id+'&traveller_id='+rt_id+'',Array('folio_traveller_'+rt_id+'','[[.create_folio.]]','80','210','950','500'));
}
function getUrlExtraService(index){
	if($('id_'+index).value!='' && $('status_'+index).value!='CHECKOUT')
	{
		var url = '?page=extra_service_invoice&type=SERVICE&cmd=add&reservation_room_id='+$('id_'+index).value;
		//url += '&bar_id='+to_numeric($('id_'+index).value);
		//url += '&cmd=add_shift';
		window.open(url);
	}	
}
function CheckAll(name)
{
    if(name=='cf')
    {
        if(document.getElementById("all_confirm").checked==true)
        {
            for(var i=101;i<=input_count;i++){
        	   if(document.getElementById("check_box_"+i).checked==true  || chec_box_tick(input_count)==true)
               {
                    document.getElementById("confirm_"+i).checked=true;
               }
        	}
        }
        else
        {
            for(var i=101;i<=input_count;i++){
        	   if(document.getElementById("check_box_"+i).checked==true  || chec_box_tick(input_count)==true)
               {
                    document.getElementById("confirm_"+i).checked=false;
               }
        	}
        }
    }
    else
    {
        if(document.getElementById("all_bf").checked==true)
        {
            for(var i=101;i<=input_count;i++){
        	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
               {
                    document.getElementById("breakfast_"+i).checked=true;
               }
        	}
        }
        else
        {
            for(var i=101;i<=input_count;i++){
        	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
               {
                    document.getElementById("breakfast_"+i).checked=false;
               }
        	}
        }
    }
    
}
function confirm_delete(index)
{
    var room_name = jQuery('#room_name_'+index).val();
    var roomCount = to_numeric($('count_number_of_room').innerHTML);
    if(roomCount-1 > 0)
    {
        <?php if(User::can_delete(false,ANY_CATEGORY)){ ?>
        var con_ = confirm('[[.are_you_sure_to_delete_room.]]: '+room_name);
        if(con_)
        {
            $('count_number_of_room').innerHTML=roomCount-1;
            mi_delete_row($('input_group_'+index),'mi_reservation_room',index,'');
            event.returnValue=false;
        }
        <?php } else{ ?>
        alert('Can\'t delete reservation room!');
        event.returnValue=false;
        <?php } ?>
    }
    else
    {
        alert('Can\'t delete reservation room! Number reservation room must > 0!');
        event.returnValue=false;
    }
}
/*function update_from_bcf(updatenote, note, cut_of_date)
{
//    console.log(note+"-"+cut_of_date);
    if(updatenote != 0)
        jQuery('#note').val(note);
    jQuery('#cut_of_date').val(cut_of_date);
}
*/
function validText() {
    var value = jQuery('#note').val()
var chaos = new Array ("'","~","@","#","$","%","^","&","*",";","/","\\","|");
var sum = chaos.length;
for (var i in chaos) {if (!Array.prototype[i]) {sum += value.lastIndexOf(chaos[i])}}
if (sum) {
alert("Lưu ý không nhập ký tự đặt biệt !@# % $ trong ghi chú: @ # $ % ^ * ~ ");
return false;
}
return true;
}
function check_number(){
    var value = jQuery('.change-price-input').val();
var arr = new Array();
arr = value.split(",");
n_arr = arr.length;
value = "";
for(var i_arr=0;i_arr<n_arr;i_arr++){
value = value + arr[i_arr];
}
    if(value>=10000000000){
        alert("Báº N NHáº¬P Sá» QUÃ Lá»šN VÃ€O Ã” LÆ¯á»¢C Äá»’ GIÃ, KIá»‚M TRA Láº I Dá»® LIá»†U NHáº¬P");
        jQuery('.change-price-input').val("");
        jQuery('.change-price-input').css('background','red');
    }else{
        jQuery('.change-price-input').css('background','#ffffff');
    }
    var chaos = new Array ("'","~","@","#","$","%","^","&","*",";","/","\\","|",":","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","x","y","z","w","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","X","Y","Z","W");
    var sum = chaos.length;
    for (var i in chaos) {if (!Array.prototype[i]) {sum += value.lastIndexOf(chaos[i])}}
    if (sum) {
    alert("Lưu ý không nhập ký tự đặt biệt !@# % $ trong ghi chú: @ # $ % ^ * ~ ");
    jQuery('.change-price-input').val("");
    jQuery('.change-price-input').css('background','red');
    }
    else{
        jQuery('.change-price-input').css('background','#ffffff');
    }
}
function save_time(index)
{
    rr_id_trevaller = jQuery('#id_'+index).val();
    arrival_time = jQuery('#time_in_'+index).val();
    arrival_date = jQuery('#arrival_time_'+index).val();
    departure_time = jQuery('#time_out_'+index).val();
    departure_date = jQuery('#departure_time_'+index).val();
    <?php echo 'var block_id = '.Module::block_id().';';?> 
    var status_change = jQuery('#status_'+index).val();
    if(status_change == 'CHECKIN')
    {
        var r = confirm('Bạn có đổi ngày đến đi của khách bằng ngày đến đi của phòng không?');
        if(r==true)
        {
            jQuery('#loading-layer').fadeIn(100);


            jQuery.ajax({
    						url:"form.php?block_id="+block_id,
    						type:"POST",
    						data:{rr_id_trevaller:rr_id_trevaller,arrival_date:arrival_date,departure_date:departure_date,arrival_time:arrival_time,departure_time:departure_time},
    						success:function(html)
                            {
                                //alert(html);                                
                               // if(html == 'unsucess')
//                                {
//                                    returnOldPosition();
//                                    alert(html);
//                                }
//                                else 
//                                {
//                                    
//                                }
    							jQuery('#loading-layer').fadeOut(0);
    							//HideDialog('dialog');
    							//window.open(location.reload(true));
                                //location.reload(true);
    						}
    			});
        }
        else
        {  
           
        }
        
    }
}
function change_date_traveller(index)
{
    res_r_id = jQuery('#id_'+index).val();
    time_in = jQuery('#time_in_'+index).val();
    arrival_time = jQuery('#arrival_time_'+index).val();
    status = jQuery('#status_'+index).val();
    if(status=='BOOKED')
    {
        if (window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            }
        }
        xmlhttp.open("GET","update_time_traveller.php?data=update_traveller&res_r_id="+res_r_id+"&time_in="+time_in+"&arrival_time="+arrival_time,true);
        xmlhttp.send();
    }
}
function change_all_date_traveller()
{
    for(var i=101;i<=input_count;i++){
		if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
        {
            change_date_traveller(i);
        }
    }
}

// Start: KID cmt doan nay vi bo button add so nguoi lon nen khong can doan nay nua 

/*jQuery(document).ready(function(){ 
  jQuery("#add").click(function(){
    jQuery('.pp').val(jQuery('.addall').val());
  });
});*/

// End: KID

//start: KID them function kiem tra folio truoc khi checkout

function check_folio(index,is_reservation)
{
    /** mang index, reservation_room_id **/
    //console.log(is_reservation);
    var index_ids = [];
    for(var i=101;i<=input_count;i++){
		index_ids[$('id_'+i).value] = i;
    }
    
    if(!is_reservation)
        var id_check = jQuery('#id_'+index).val();
    else
        <?php echo 'var id_check = '.Url::get("id").';';?>
        
    <?php echo 'var block_id = '.Module::block_id().';';?>
    
    jQuery('#loading-layer').fadeIn(100);
    jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{status_edit:'CHECKOUT',id_check:id_check,is_reservation:is_reservation},
					success:function(html)
                    {
                        if(html.trim()!='')
                        {
                            if(html.trim()=='NOT_CHECKOUT')
                            {
                                var num_room = 0;
                                for(var i=101;i<=input_count;i++)
                                {
                                    if(($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CHECKIN'))
                                    {
                                        num_room++;
                                    }
                                }
                                console.log(num_room);
                                if(num_room==0)
                                {
                                   if(!is_reservation)
                                    {
                                        $('status_'+index).value = 'CHECKIN';
                                    }
                                    else
                                    {
                                        for(var i=101;i<=input_count;i++){
                                    		if($('old_status_'+i).value=='CHECKIN' && $('status_'+i).value == 'CHECKOUT')
                                            {
                                                $('status_'+i).value = 'CHECKIN';
                                            }
                                        }
                                    }
                                   new_contentt ="Vẫn tồn tại khoản đặt cọc nhóm chưa tạo hóa đơn!\n"; 
                                   alert(new_contentt);
                                }
                                else if(!is_reservation)
                                {
                                    $('departure_time_'+index).value='<?php echo date('d/m/Y')?>';
                                    $('time_out_'+index).value='<?php echo date('H:i')?>';
                                    $('closed_'+index).checked = true;
                                    $('closed_'+index).readOnly = true;
                                }
                            }
                            else
                            {
                                var otbjs = jQuery.parseJSON(html);
                                new_contentt = 'Chênh lệch số tiền trên Folio hoặc chưa chọn hình thức thanh toán. Anh/chị vui lòng kiểm tra lại !!!!!\n'
                                for(var otbj in otbjs)
                                {
                                    $('status_'+index_ids[otbj]).value = 'CHECKIN';
                                    new_contentt +="   Phòng "+otbjs[otbj]['room_name']+': '+(otbjs[otbj]['not_create_folio']?'Chưa tạo hết hóa đơn\n':"\n");
                                    if(otbjs[otbj]['folios_not_paid'])
                                    {
                                        for(var folio in otbjs[otbj]['folios_not_paid'])
                                        {
                                            if(otbjs[otbj]['folios_not_paid'][folio]['payment'])
                                            {
                                                new_contentt +='    + folio '+otbjs[otbj]['folios_not_paid'][folio]['id']+" chưa thanh toán hết\n";
                                            }
                                            else
                                            {
                                                new_contentt +='    + folio '+otbjs[otbj]['folios_not_paid'][folio]['id']+" chưa thanh toán\n";
                                            }
                                        }
                                    }
                                }
                                alert(new_contentt);
                            }
                        }
                        else if(!is_reservation)
                        {
                            $('departure_time_'+index).value='<?php echo date('d/m/Y')?>';
                            $('time_out_'+index).value='<?php echo date('H:i')?>';
                            $('closed_'+index).checked = true;
                            $('closed_'+index).readOnly = true;
                           <?php if(!User::can_admin(false,ANY_CATEGORY)){
                            ?>
                            setReadonly(index);
                            <?php
                           } ?>
                            
                        }
                        
						jQuery('#loading-layer').fadeOut(0);
						//HideDialog('dialog');
					}
		});
     
}
function check_cancel_deposit(index,is_reservation)
{
    /** mang index, reservation_room_id **/
    
    var index_ids = [];
    for(var i=101;i<=input_count;i++){
		index_ids[$('id_'+i).value] = i;
    }
    
    if(!is_reservation)
        var id_check = jQuery('#id_'+index).val();
    else
        <?php echo 'var id_check = '.Url::get("id").';';?>
        
    <?php echo 'var block_id = '.Module::block_id().';';?>
    //console.log(id_check);
    jQuery('#loading-layer').fadeIn(100);
    jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{status_edit:'CANCEL',id_check:id_check,is_reservation:is_reservation},
					success:function(html)
                    {
                        //console.log(html);
                        if(html)
                        {
                            if(html.trim()=='NOT_CANCEL')
                            {
                                var num_room = 0;
                                for(var i=101;i<=input_count;i++){
                                    
                                    if(($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CHECKIN'))
                                    {
                                        num_room++;
                                    }
                                }
                                if(num_room==0)
                                {
                                    if(!is_reservation)
                                    {
                                        $('status_'+index).value = 'BOOKED';
                                    }
                                    else
                                    {
                                        for(var i=101;i<=input_count;i++){
                                    		if($('old_status_'+i).value=='BOOKED' && $('status_'+i).value == 'CANCEL')
                                            {
                                                $('status_'+i).value = 'BOOKED';
                                                updateStatusList(i);
                                            }
                                        }
                                    }
                                    new_contentt = 'Vẫn tồn tại khoản đặt cọc nhóm chưa tạo hóa đơn! !!!!!\n';
                                    alert(new_contentt);
                                }
                                else
                                {
                                    if(!is_reservation)
                                    {
                                        check_cancel('CANCEL',index);
                                        //getValue('cancel_charge_'+index,'0');
                                        //jQuery('div#wrapper').fadeIn('fast');jQuery('div#cancel_extra').fadeIn('fast'); 
                                    }
                                    else if(is_reservation)
                                    {
                                        check_cancel_group('CANCEL');
                                        //getValue('0','cancel_fee_group');
                                    }
                                }
                                //return false;
                            }
                            else
                            {
                                if(!is_reservation)
                                {
                                    check_cancel('CANCEL',index);
                                    //getValue('cancel_charge_'+index,'0');
                                    //jQuery('div#wrapper').fadeIn('fast');jQuery('div#cancel_extra').fadeIn('fast'); 
                                }
                                else if(is_reservation)
                                {
                                    check_cancel_group('CANCEL');
                                    //getValue('0','cancel_fee_group');
                                }
                            }
                        }
						jQuery('#loading-layer').fadeOut(0);
						//HideDialog('dialog');
					}
		});
     
}
function check_cancel(value,target){
        if(value=='CANCEL'){
            jQuery("#"+target).css({'display':'block','visibility': 'visible'});
            jQuery("span select[cancel_id]").not("select[cancel_id="+target+"]").each(function(){
                    if(jQuery(this).val()!=value){
                       var cancel_id = jQuery(this).attr("cancel_id"); 
                      jQuery("span#"+cancel_id).css({'display':'block','visibility': 'hidden'});  
                    }
            });
            jQuery("#cancel_charge").css('display','block');
        }
        else{
            jQuery("#"+target).css({'display':'block','visibility': 'hidden'});
            var boolen = false;
            jQuery(".reservation_status").each(function(){
                if(jQuery(this).val()=="CANCEL"){
                    boolen = true;
                }
                else{
                    var cancel_id = jQuery(this).attr('cancel_id');
                    jQuery("#"+cancel_id).css({'display':'block','visibility': 'hidden'});
                }
            });
            if(!boolen){
                jQuery("#cancel_charge").css('display','none');
                jQuery(".reservation_status").each(function(){
                    var cancel_id = jQuery(this).attr('cancel_id');
                    jQuery("#"+cancel_id).css('display','none');
               });
            }
        }
    }
    function check_cancel_group(value){
        if(value=="CANCEL"){
            var boolen = true;
            jQuery("select[cancel_id]").each(function(){
                //alert(jQuery(this).val());
                if(jQuery(this).val()=="CHECKIN"){
                    boolen = false;
                    return false;
                }
            });
            if(!boolen){
                alert("CÃ¯Â¿Â½ phÃ¯Â¿Â½ng hi?n dang CHECKIN. Xin vui lÃ¯Â¿Â½ng ki?m tra l?i !");
                return false;
            }
            jQuery("#cancel_charge").css('display','block');
            jQuery("span[cancel_span_target]").each(function(){
                jQuery(this).css({'display':'block','visibility': 'visible'});
            });
            jQuery('div#wrapper').fadeIn('fast');jQuery('div#cancel_extra').fadeIn('fast');
            jQuery(".cancel_group_id_span").each(function(){
                jQuery(this).css("display","");
            });           
        }
        else{
            jQuery("#cancel_charge").css('display','none');
            jQuery("span[cancel_span_target]").each(function(){
                jQuery(this).css({'display':'none','visibility': 'visible'});
            });
            jQuery("select[cancel_id] option").each(function(){
                var id_arr = jQuery(this).attr("id");
                    id_arr = id_arr.split("_");
                var id = id_arr[1];
                if(jQuery("input#check_box_"+id).is(":checked") && chec_box_tick(input_count)){
                    value_group = jQuery(this).attr("value");
                  if(value_group==value){
                    jQuery(this).attr("selected","selected");
                  }
                }       
            });
        }
        
        
        
    }
//end
function close_window_fun(){
    var at_path = window.parent.location.toString();
    if(at_path.indexOf('&adddd_guest=yes')>-1)
    {
        var ts_path = at_path.replace('&adddd_guest=yes','');
        window.parent.location.replace(ts_path);
    }
    else
    {
          location.reload();
          jQuery(".window-container").fadeOut();
    }
}
//Giap add new 14-05-2014
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
jQuery(document).ready(function(){
    for(var i=101;i<input_count;i++)
    {
        document.getElementById("total_key_" + i).style.verticalAlign = "top";
        document.getElementById("total_key_" + i).style.height = "15px";
        document.getElementById("total_key_" + i).style.backgroundColor = "transparent";
        document.getElementById("total_key_" + i).style.border = "0px solid";
        document.getElementById("total_key_" + i).style.fontSize = "14px !important";
    }
    var foc = 'FOC';
    for(var index = 101; index <= input_count; index++)
    {
        var foc_dn = jQuery('#foc_'+index).val();
        if(foc_dn != '')
        {
            foc += '_' + index;            
        }else
        {
            foc += '_NOTFOC';
        }
    }
    var arr_foc = foc.split('_');
    var k =0;
    for(var index = 101; index<=input_count; index++)
    {
        k++;
        check_foc = arr_foc[k];
        if(check_foc == 'NOTFOC')
        {
            jQuery('#foc_group').attr('checked', false);
            return false;
        }else
        {
            jQuery('#foc_group').attr('checked', true);            
        }
    }
});
jQuery(document).ready(function(){
    var foc_all = 'FOCALL';
    for(var index = 101; index <= input_count; index++)
    {
        if(document.getElementById("foc_all_"+index).checked==true)
        {
            foc_all += '_' + index;            
        }else
        {
            foc_all += '_NOTFOCALL';
        }
    }
    var arr_foc_all = foc_all.split('_');
    var j =0;
    for(var index = 101; index <= input_count; index++)
    {
        j++;
        check_foc_all = arr_foc_all[j];
        if(check_foc_all == 'NOTFOCALL')
        {
            jQuery('#foc_all_group').attr('checked', false);
            return false;         
        }else
        {
            jQuery('#foc_all_group').attr('checked', true);            
        }
    }
});
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
//Giap add end
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
var checksave=0; 
function checkSave(key)
{
    jQuery("#"+key).attr('checked','checked');
    AddReservationForm.submit();
}
function func_change_price_in_date()
{
    
    if(jQuery("#date_group").val()!='' && jQuery("#price_group").val()!='')
    {
        if(jQuery("#room_level_group").val()=='ALL')
        {
            for(var i=101;i<=input_count;i++)
            {
                //console.log();/** Daund check quyen sua gia o day */
                if(document.getElementById("status_"+i).value =='BOOKED')
                {
                    element = document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i);
                    if (element != null) {
                        if(element.value!=undefined)
                        {
                            element.value=number_format(to_numeric(jQuery("#price_group").val()));
                        }
                    }
                    /*if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                    {
                        document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(to_numeric(jQuery("#price_group").val()));
                    }*/                    
                }else if(CAN_ADMIN_PRICE==true)/** END Daund check quyen sua gia o day */
                    element = document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i);
                    if (element != null) {
                        if(element.value!=undefined)
                        {
                            element.value=number_format(to_numeric(jQuery("#price_group").val()));
                        }
                    }
                    /*if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                    {
                        document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(to_numeric(jQuery("#price_group").val()));
                    }*/  
            }
        }
        else
        {
            for(var i=101;i<=input_count;i++)
            {
                if(jQuery("#room_level_name_"+i).val()==jQuery("#room_level_group").val())
                {
                    /** Daund check quyen sua gia o day */
                    if(document.getElementById("status_"+i).value =='BOOKED')
                    {
                        element = document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i);
                        if (element != null) {
                            if(element.value!=undefined)
                            {
                                element.value=number_format(to_numeric(jQuery("#price_group").val()));
                            }
                        }
                        /*if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                        {
                            document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(to_numeric(jQuery("#price_group").val()));
                        }*/                    
                    }else if(CAN_ADMIN_PRICE==true)/** Daund check quyen sua gia o day */
                        element = document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i);
                        if (element != null) {
                            if(element.value!=undefined)
                            {
                                element.value=number_format(to_numeric(jQuery("#price_group").val()));
                            }
                        }
                        /*if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                        {
                            document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(to_numeric(jQuery("#price_group").val()));
                        }*/  
                }
                else
                {
                    element = document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i);
                    if (element != null) {
                        if(element.value!=undefined)
                        {
                            element.value=number_format(mi_reservation_room_arr[jQuery("#id_"+i).val()]['change_price_arr'][jQuery("#date_group").val()]);
                        }
                    }
                    /*if(document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value!=undefined)
                    {
                        document.getElementById("change_price_"+jQuery("#date_group").val()+"_"+i).value=number_format(mi_reservation_room_arr[jQuery("#id_"+i).val()]['change_price_arr'][jQuery("#date_group").val()]);
                    }*/
                }
            }
        }
    }
}

//start: KID them function gan phong cho phong book chua asign

function check_room_asign()
{
    <?php echo 'var reservation_id = '.Url::get("id").';';?> 
    <?php echo 'var block_id = '.Module::block_id().';';?>
    jQuery('#loading-layer').fadeIn(100);
    jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{reservation_id:reservation_id},
					success:function(html)
                    {
                        if(html=='error')
                        {
                            alert('[[.this_room_type_has_expired.]]');
                            return false;
                        }
                        else
                        {
                            jQuery('#loading-layer').fadeOut(0);
                            location.reload();
                        }
						//HideDialog('dialog');
					}
		});
     
}
//end: KID them function gan phong cho phong book chua asign

function fun_check_box_close_all()
{
    if(document.getElementById("check_box_close_all").checked==true)
    {
        jQuery(".class_check_close_box").attr("checked","checked");
    }
    else
    {
        jQuery(".class_check_close_box").removeAttr("checked");
    }
}

/**giap.ln lay ra gia theo nguon khach**/
<?php
    if($_REQUEST['is_rate_code']==1)
    {
        ?>
        document.getElementById('is_rate_code').checked = true;
        <?php 
    } 
?>
function get_price_rate_code(customer_id,index)
{
    //console.log(document.getElementById('room_level_id_101').value);   
     var room_level_id = document.getElementById('room_level_id_'+ index).value;
    //lay ra arrival_time & departure_time 
    var arrival_time = document.getElementById('arrival_time_' + index).value;
    var departure_time = document.getElementById('departure_time_' + index).value;
    //console.log(customer_id + '--' + room_level_id + '--' + arrival_time + '--' + departure_time);
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    var is_rate_code = document.getElementById('is_rate_code');
    var is_rate = 0;
    if(is_rate_code.checked)
    {
        is_rate = 1;
    }
    jQuery.ajax({
     url:"get_customer_search_fast.php?",
     type:"POST",
     data:{rate_code:'1',room_level_id:room_level_id,customer_id:customer_id,arrival_time:arrival_time,departure_time:departure_time,is_rate:is_rate},
     success:function(data)
         {
                //var text_reponse = xmlhttp.responseText;
                
                data = JSON.parse(data);
                if(typeof data === 'object')
                {
                    var items = data;
                    var price_common = items['price_common'];
                    var exchange_rate = jQuery('#exchange_rate').val();
                    if(document.getElementById('status_' + index).value!='CHECKOUT') {
                    document.getElementById('price_' + index).value=number_format(price_common);
                    document.getElementById('usd_price_' + index).value = number_format(price_common/exchange_rate);
                    
                    count_price(index,false);
                    change_price_rate_code(items,index,false);
                    }
                }
                else
                {
                    var price_common = data;
                    var exchange_rate = jQuery('#exchange_rate').val();
                    document.getElementById('price_' + index).value=number_format(price_common);
                    document.getElementById('usd_price_' + index).value = number_format(price_common/exchange_rate);
                    count_price(index,true); 
                }
    
                if(index<input_count)
                {
                    index++;
                    get_price_rate_code(customer_id,index);
                }
         }
     });
}
function change_price_rate_code(items,index,flag)
{
    for(var i in items)
    {
        if(i!='price_common' && i!='is_rate_code')
        {
                if(document.getElementById('change_price_'+i+'_'+index))
                {
                    if(flag)
                        document.getElementById('change_price_'+i+'_'+index).value = number_format(items[i]['price']);
                    else if(document.getElementById('change_price_'+i+'_'+index)){
                        document.getElementById('change_price_'+i+'_'+index).value = number_format(items[i]['price']);
                    }
                }
        }
    }
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
        //if(customer_id!='')
            //get_price_rate_code(customer_id,101);
        for(var i=101;i<=input_count;i++)
        {
            var room_level_id = document.getElementById('room_level_id_'+ i).value;
            //lay ra arrival_time & departure_time 
            var arrival_time = document.getElementById('arrival_time_' + i).value;
            var departure_time = document.getElementById('departure_time_' + i).value;
            var cmd = '<?php echo Url::get('cmd');?>';
            get_price_rate_code(customer_id,i);   
            SetupAllotment(i);                  
        }
            jQuery('input[id^=price_]').each(function(){
                jQuery(this).attr('readonly','readonly');
                jQuery(this).addClass('readonly');
            });
            jQuery('input[id^=usd_price_]').each(function(){
                jQuery(this).attr('readonly','readonly');
                jQuery(this).addClass('readonly');
            });
            jQuery(document).ready(function(){
                jQuery("input.change-price-input").each(function(){
                jQuery(this).attr('readonly','readonly');
                jQuery(this).addClass('readonly');
                });
            });
            
            jQuery("input#change_all_price").val("");
            jQuery("input#change_all_usd_price").val("");
            jQuery("input#change_all_price").attr('readonly','readonly');
            jQuery("input#change_all_price").addClass('readonly');
            jQuery("input#change_all_usd_price").attr('readonly','readonly');
            jQuery("input#change_all_usd_price").addClass('readonly');
    }
    else
    {
        //update_old_price();
        //khong su dung rate code se khong thay doi gia da khai bao 
        for(var i=101;i<=input_count;i++)
        {
            update_price(i);
            SetupAllotment(i);
//            //var customer_id = document.getElementById('customer_id').value;
//            //get_price_rate_code(customer_id,101);
//            //document.getElementById('price_' + i).value=0;
//            //document.getElementById('usd_price_' + i).value =0;
//            
//            var arrival_time = document.getElementById('arrival_time_' + i).value;
//            var departure_time = document.getElementById('departure_time_' + i).value;
//            var arr_arrival = arrival_time.split('/');
//            var arr_departure = departure_time.split('/');
//            var arrival_date = new Date();
//            arrival_date.setFullYear(arr_arrival[2],arr_arrival[1]-1,arr_arrival[0]);
//            arrival_date.setHours(0,0,0,0);
//
//            var departure_date = new Date(); 
//            departure_date.setFullYear(arr_departure[2],arr_departure[1]-1,arr_departure[0]);
//            departure_date.setHours(0,0,0,0);
//            var d_time = departure_date.getTime()/1000;
//            var a_temp = arrival_date;
//            var a_time = a_temp.getTime()/1000;
//            
//            while(a_time<d_time)
//            {
//                var str_day = a_temp.getDate() + '/' + (a_temp.getMonth()+1) + '/' + a_temp.getFullYear();
//                
//                document.getElementById('change_price_'+str_day+'_'+i).value =0;
//                a_time +=86400;
//                a_temp.setTime(a_time*1000);
//                
//            }
        }
            jQuery('input[id^=price_]').each(function(){
                jQuery(this).removeAttr('readonly');
                jQuery(this).removeClass('readonly');
            });
            jQuery('input[id^=usd_price_]').each(function(){
                jQuery(this).removeAttr('readonly');
                jQuery(this).removeClass('readonly');
            });
            jQuery("input.change-price-input").each(function(){
                jQuery(this).removeAttr('readonly');
                jQuery(this).removeClass('readonly');
            });
            jQuery("input#change_all_price").removeAttr('readonly');
            jQuery("input#change_all_price").removeClass('readonly');
            jQuery("input#change_all_usd_price").removeAttr('readonly');
            jQuery("input#change_all_usd_price").removeClass('readonly');
    }
}
//end giap.ln 
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

//end giap.ln 

/** Thanh them phan readonly cho input sau khi checkout  **/


function setReadonly(target){
        if(target!=0){
        jQuery("span#input_group_"+target+ " input").not('input[type=button]').not('input[type=checkbox]').each(function(){
            jQuery(this).addClass("readonly");
            jQuery(this).attr('readonly','');
            jQuery(this).unbind();
        });
        jQuery("span#input_group_"+target+ " select[id!=status_"+target+"]").each(function(){
            jQuery(this).addClass("readonly");
            jQuery(this).attr('disabled','disabled');
        });
        jQuery("span#input_group_"+target+ " input[type=checkbox]").each(function(){
            jQuery(this).addClass("readonly");
            jQuery(this).attr('disabled','disabled');
        });
        }
}

/** end - Thanh **/
/** oanh add **/
function hiddenRoomCancel()
{
    for(var i=101;i<=input_count;i++)
    {
        if(jQuery('#status_'+i).val()=='CANCEL')
        {
            if (jQuery("#hidden_room_cancel").is(":checked")){
                document.getElementById("reservation_room_bound_"+i).style.display='none';
            }
            else
            {
                 document.getElementById("reservation_room_bound_"+i).style.display='block';
            }
        }
	} 
}
function getUrlExtraChargeRoom(index)
{
	if($('id_'+index).value!='' && $('status_'+index).value!='CHECKOUT')
	{
		var url = '?page=extra_service_invoice&type=ROOM&cmd=add&reservation_room_id='+$('id_'+index).value+'';
		//url += '&bar_id='+to_numeric($('id_'+index).value);
		//url += '&cmd=add_shift';
		window.open(url);
	}	
}
/** Start: Daund them readonly cho timein, timeout khi chuyen trang thai */
jQuery('#change_all_status').change(function(){
    var e = document.getElementById("change_all_status");
    var strStt = e.options[e.selectedIndex].value;
    for(var i=101;i<=input_count;i++)
    {
		if(strStt == 'CHECKIN' || strStt == 'CHECKOUT')
        {
            jQuery('#time_in_'+i).attr('readonly',true);
            jQuery('#time_in_'+i).addClass('readonly');
            if(strStt == 'CHECKOUT')
            {
                jQuery('#time_out_'+i).attr('readonly',true);
                jQuery('#time_out_'+i).addClass('readonly');
            }
        }
    }
})
/** End: Daund them readonly cho timein, timeout khi chuyen trang thai */
    /** start kieu them DVMR va tien phong **/
        for(i=101;i<=input_count;i++){
            for(var j in mi_reservation_room_arr){
                if(jQuery('#id_'+i).val()==mi_reservation_room_arr[j]['id']){
                    if(mi_reservation_room_arr[j]['service']){
                        if(mi_reservation_room_arr[j]['service']['ROOM']){
                            var link='';
                            for(var k in mi_reservation_room_arr[j]['service']['ROOM']){
                                var invoice_id=mi_reservation_room_arr[j]['service']['ROOM'][k]['invoice_id'];
                                var name=mi_reservation_room_arr[j]['service']['ROOM'][k]['name'];
                                link+='<a target="blank" href="?page=extra_service_invoice&type=ROOM&cmd=edit&id='+invoice_id+'">'+name+'</a>&nbsp;&nbsp;&nbsp;&nbsp;'
                            }    
                            $('room_charge_'+i).innerHTML =link;
                        }
                        if(mi_reservation_room_arr[j]['service']['SERVICE']){
                            var link='';
                            for(var k in mi_reservation_room_arr[j]['service']['SERVICE']){
                                var invoice_id=mi_reservation_room_arr[j]['service']['SERVICE'][k]['invoice_id'];
                                var name=mi_reservation_room_arr[j]['service']['SERVICE'][k]['name'];
                                link+='<a target="blank" href="?page=extra_service_invoice&type=SERVICE&cmd=edit&id='+invoice_id+'">'+name+'</a>&nbsp;&nbsp;&nbsp;&nbsp;'
                            }    
                            $('extra_service_'+i).innerHTML =link;
                        }
                    }
                }
            }
        }
    /** end kieu them DVMR va tien phong **/  
/** THANH add phần thêm tổng số khóa cho nhóm đoàn **/
    <?php
    if(defined('IS_KEY') && IS_KEY==1)
    {
?>
    jQuery(document).ready(function(){
        var total = 0;
        jQuery("input[id^=total_key_").each(function(){
            total+=to_numeric(jQuery(this).val());
        });
        jQuery("#total_key_group").html(total);
    });
<?php        
    }    
    ?>
    
/** Start: Daund them readonly cho lược đấu giá quá khứ */
var today_dn = '<?php echo date('d/m/Y', time()); ?>'
for(var i = 101; i<= input_count; i++)
{
    for(var j in mi_reservation_room_arr)
    {
        for(var k in mi_reservation_room_arr[j]['change_price_arr'])
        {
            if(!k.charAt(10))
            {
                var in_date_old = k
                in_date_old_arr = in_date_old.split('/');
                in_date_old = new Date(in_date_old_arr[1]+'/'+in_date_old_arr[0]+'/'+in_date_old_arr[2]);  
                
                var in_date_old = Date.parse(in_date_old); 
                
                today_arr = today_dn.split('/');
                today_parse = new Date(today_arr[1]+'/'+today_arr[0]+'/'+today_arr[2]);  
                
                var today_parse = Date.parse(today_parse);
                
                if(to_numeric(in_date_old) < to_numeric(today_parse))
                {
                    //$('change_price_'+k+'_'+i).readOnly = true;
                }
            }
        }
    }
}
/** END: Daund them readonly cho lược đấu giá quá khứ */
</script>
