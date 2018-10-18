<style>
.setting-body{padding:10px}
.etabs { margin: 0px; padding: 0; }
.tab { display: inline-block; zoom:1; *display:inline; background: #eee; border: solid 1px #999; border-bottom: none; -moz-border-radius: 4px 4px 0 0; -webkit-border-radius: 4px 4px 0 0; }
.tab a { font-size: 14px; line-height: 2em; display: block; padding: 0 10px; outline: none; }
.tab a:hover { text-decoration: none; }
.tab.active { background: #fff; padding-top: 6px; position: relative; top: 1px; border-color: #666; }
.tab a.active { color:#0000FF;}
.tab a.active:hover{text-decoration:none;}
.tab-container{}
.tab-content{padding:10px;min-height:700px;}
.tab-container div.active { border:1px solid #666; -moz-border-radius: 0px 4px 4px 4px; -webkit-border-radius: 0px 4px 4px 4px; }
.tab-container .panel-container { background: #fff; border: solid #666 1px; padding: 10px; -moz-border-radius: 0 4px 4px 4px; -webkit-border-radius: 0 4px 4px 4px; }
</style>
<form name="SettingForm" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td>
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="70%"><?php echo Portal::language('setting_manage');?></td>
					<td align="right"><input name="submit" type="submit" value="<?php echo Portal::language('Save');?>" /></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td>
		<div>
			<div class="setting-body 0" id="setting_info">
				<div class="setting-notice-bound">
				<?php if(Url::get('act')=='succ'){?>
				<div class="setting-notice"><?php echo Portal::language('update_success');?></div>
				<script>jQuery('.setting-notice').fadeOut(2000);</script>
				<?php }?>
				</div>
				<div class="setting-error"><?php Form::$current->error_messages();?></div>
				<div id="setting_tab" class="tab-container">
					<ul>
						<li class="tab"><a href="#general_info"><?php echo Portal::language('general_info');?></a></li>
						<li class="tab"><a href="#payment"><?php echo Portal::language('payment');?></a></li>
						<li class="tab"><a href="#reception"><?php echo Portal::language('reception');?></a></li>
						<li class="tab"><a href="#minibar"><?php echo Portal::language('minibar');?></a></li>
						<li class="tab"><a href="#laundry"><?php echo Portal::language('laundry');?></a></li>
						<li class="tab"><a href="#restaurant"><?php echo Portal::language('restaurant');?></a></li>
                        <li class="tab"><a href="#karaoke"><?php echo Portal::language('karaoke');?></a></li>
						<li class="tab"><a href="#extra_service"><?php echo Portal::language('extra_service');?></a></li>
						<li class="tab"><a href="#telephone"><?php echo Portal::language('telephone');?></a></li>
						<li class="tab"><a href="#vending"><?php echo Portal::language('vending');?></a></li>
                        <li class="tab"><a href="#spa"><?php echo Portal::language('spa');?></a></li>
						<li class="tab"><a href="#warehouse"><?php echo Portal::language('warehouse');?></a></li>
						<li class="tab"><a href="#pa18"><?php echo Portal::language('PA18');?></a></li>
						<li class="tab"><a href="#skins"><?php echo Portal::language('skins');?></a></li>
                        <li class="tab"><a href="#change_point"><?php echo Portal::language('change_point');?></a></li>
                        <li class="tab"><a href="#sync_cns"><?php echo Portal::language('sync_cns');?></a></li>
                        <li class="tab"><a href="#siteminder"><?php echo Portal::language('siteminder');?></a></li>
                        <li class="tab"><a href="#book_confirm"><?php echo Portal::language('book_confirm');?></a></li>
					</ul>
					<div id="general_info" class="tab-content">
						<div class="setting-field">
							<label for="hotel_name"><?php echo Portal::language('hotel_name');?></label>
							<input  name="hotel_name" id="hotel_name" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_name'));?>">
						</div>
                                                <div class="setting-field">
							<label for="hotel_palce"><?php echo Portal::language('hotel_place');?></label>
							<input  name="hotel_place" id="hotel_place" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_place'));?>">
						</div>
						<div class="setting-field">
							<label for="hotel_address"><?php echo Portal::language('hotel_address');?></label>
							<textarea  name="hotel_address" id="hotel_address"style="width:250px;"><?php echo String::html_normalize(URL::get('hotel_address',''));?></textarea>
						</div>
						<div class="setting-field">
							<label for="hotel_phone"><?php echo Portal::language('hotel_phone');?></label>
							<input  name="hotel_phone" id="hotel_phone"   / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_phone'));?>">
						</div>
						<div class="setting-field">
							<label for="hotel_fax"><?php echo Portal::language('hotel_fax');?></label>
							<input  name="hotel_fax" id="hotel_fax" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_fax'));?>">
						</div>
						<div class="setting-field">
							<label for="hotel_taxcode"><?php echo Portal::language('hotel_taxcode');?></label>
							<input  name="hotel_taxcode" id="hotel_taxcode" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_taxcode'));?>">
						</div>
						<div class="setting-field">
							<label for="hotel_currency"><?php echo Portal::language('hotel_currency');?></label>
							<select  name="hotel_currency" id="hotel_currency" ><?php
					if(isset($this->map['hotel_currency_list']))
					{
						foreach($this->map['hotel_currency_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('hotel_currency',isset($this->map['hotel_currency'])?$this->map['hotel_currency']:''))
                    echo "<script>$('hotel_currency').value = \"".addslashes(URL::get('hotel_currency',isset($this->map['hotel_currency'])?$this->map['hotel_currency']:''))."\";</script>";
                    ?>
	</select>
						</div>
						<div class="setting-field">
							<label for="hotel_email"><?php echo Portal::language('hotel_email');?></label>
							<input  name="hotel_email" id="hotel_email" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_email'));?>">
						</div>
						<div class="setting-field">
							<label for="hotel_website"><?php echo Portal::language('hotel_website');?></label>
							<input  name="hotel_website" id="hotel_website" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_website'));?>">
						</div>
						<div class="setting-field">
							<label for="block_second"><?php echo Portal::language('block_second');?></label>
							<input  name="block_second" id="block_second" / type ="text" value="<?php echo String::html_normalize(URL::get('block_second'));?>"> <span class="note">(<?php echo Portal::language('telephone');?>)</span></div>
						<div class="setting-field" style="display:none;">
							<label for="lock_db_file"><?php echo Portal::language('lock_db_file');?></label>
							<input  name="lock_db_file" id="lock_db_file" / type ="text" value="<?php echo String::html_normalize(URL::get('lock_db_file'));?>">
						<span class="note" style="display:none;">(Only for Mangetic Lock)</span></div>
						<div class="setting-field">
							<label for="round_precision"><?php echo Portal::language('round_precision');?></label>
							<input  name="round_precision" id="round_precision" / type ="text" value="<?php echo String::html_normalize(URL::get('round_precision'));?>"> 
							<span class="note">(<?php echo Portal::language('comma');?>)</span></div>
							<div class="setting-field">
							<label for="night_audit_time"><?php echo Portal::language('default_night_audit_time');?></label>
							<input  name="night_audit_time" id="night_audit_time" / type ="text" value="<?php echo String::html_normalize(URL::get('night_audit_time'));?>"></div>                
						<div class="setting-field">
							<label for="use_night_audit"><?php echo Portal::language('use_night_audit');?></label>
							<input  name="use_night_audit" id="use_night_audit" type ="checkbox" value="<?php echo String::html_normalize(URL::get('use_night_audit'));?>"></div>	
						<div class="setting-field">
							<label for="night_audit_confirmation"><?php echo Portal::language('night_audit_confirmation');?></label>
							<textarea  name="night_audit_confirmation" id="night_audit_confirmation" style="width:250px;height:50px;"><?php echo String::html_normalize(URL::get('night_audit_confirmation',''));?></textarea></div>
						<div class="setting-field">
							<label for="extra_bed_quantity"><?php echo Portal::language('extra_bed_quantity');?></label>
							<input  name="extra_bed_quantity" id="extra_bed_quantity" / type ="text" value="<?php echo String::html_normalize(URL::get('extra_bed_quantity'));?>"></div>                    
						<div class="setting-field">
							<label for="baby_cot_quantity"><?php echo Portal::language('baby_cot_quantity');?></label>
							<input  name="baby_cot_quantity" id="baby_cot_quantity" / type ="text" value="<?php echo String::html_normalize(URL::get('baby_cot_quantity'));?>"></div>
						<div class="setting-field">
							<label for="breakfast_price"><?php echo Portal::language('breakfast_from_time');?></label>
							<input  name="breakfast_from_time" id="breakfast_from_time" / type ="text" value="<?php echo String::html_normalize(URL::get('breakfast_from_time'));?>"></div>
                        <div class="setting-field">
							<label for="breakfast_price"><?php echo Portal::language('breakfast_to_time');?></label>
							<input  name="breakfast_to_time" id="breakfast_to_time" / type ="text" value="<?php echo String::html_normalize(URL::get('breakfast_to_time'));?>"></div>
                        <div class="setting-field">
							<label for="breakfast_price"><?php echo Portal::language('breakfast_price');?></label>
							<input  name="breakfast_price" id="breakfast_price" / type ="text" value="<?php echo String::html_normalize(URL::get('breakfast_price'));?>"></div>
                        <div class="setting-field">
							<label for="breakfast_child_price"><?php echo Portal::language('breakfast_child_price');?></label>
							<input  name="breakfast_child_price" id="breakfast_child_price" / type ="text" value="<?php echo String::html_normalize(URL::get('breakfast_child_price'));?>"></div>
                        <div class="setting-field">
							<label for="breakfast_net_price"><?php echo Portal::language('breakfast_net_price');?></label>
							<input  name="breakfast_net_price" type="checkbox" id="breakfast_net_price" <?php if(Url::get('breakfast_net_price')){echo 'checked';}?> value="1"/></div>
                        <div class="setting-field">
							<label for="breakfast_split_price"><?php echo Portal::language('breakfast_split_price');?></label>
							<input  name="breakfast_split_price" type="checkbox" id="breakfast_split_price" <?php if(Url::get('breakfast_split_price')){echo 'checked';}?> value="1"/></div>                                                       
						<div class="setting-field">
							<label for="pickup_price"><?php echo Portal::language('picup_see_off_price');?></label>
							<input  name="pickup_price" id="pickup_price" / type ="text" value="<?php echo String::html_normalize(URL::get('pickup_price'));?>">
						</div>
						<div class="setting-field">
							<label for="check_browser"><?php echo Portal::language('check_browser');?>:</label>
							<input  name="check_browser" id="check_browser" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('check_browser'));?>">
						</div>
                        <div class="setting-field">
							<label for="rate_code"><?php echo Portal::language('rate_code');?>:</label>
							<input  name="rate_code" id="rate_code" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('rate_code'));?>">
						</div>
                        <div class="setting-field">
							<label for="use_hls">Hotel Link Solution:</label>
							<input  name="use_hls" id="use_hls" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('use_hls'));?>">
						</div>
                        <div class="setting-field">
							<label for="use_allotment">Allotment:</label>
							<input  name="use_allotment" id="use_allotment" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('use_allotment'));?>">
						</div>			
                        <div class="setting-field">
							<label for="using_chat"><?php echo Portal::language('Chat');?></label>
							<input  name="using_chat" id="using_chat" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('using_chat'));?>">
						</div>					
				</div>
				<div id="payment" class="tab-content">
					<div>
					<div class="setting-field">
						<label for="allow_credit_card_type"><?php echo Portal::language('allow_credit_card_type');?></label>
						<input  name="allow_credit_card_type" type="checkbox" id="allow_credit_card_type">
					</div>
					</div>
				</div>
				<div id="reception" class="tab-content">
					<div>
                    <!-- oanh add auto LI -->
                        <div class="setting-field">
							<label for="auto_li_start_time"><?php echo Portal::language('auto_LI_time');?></label>
							<input  name="auto_li_start_time" id="auto_li_start_time" style="width: 118px !important;" onblur="checkTimeFormat(this);" / type ="text" value="<?php echo String::html_normalize(URL::get('auto_li_start_time'));?>"> - <input  name="auto_li_end_time" id="auto_li_end_time" style="width: 118px !important;" onblur="checkTimeFormat(this);" / type ="text" value="<?php echo String::html_normalize(URL::get('auto_li_end_time'));?>">
						</div>
                    <!-- End oanh -->
						<div class="setting-field">
							<label for="check_in_time"><?php echo Portal::language('check_in_time');?></label>
							<input  name="check_in_time" id="check_in_time" / type ="text" value="<?php echo String::html_normalize(URL::get('check_in_time'));?>">
						</div>
						<div class="setting-field">
							<label for="check_out_time"><?php echo Portal::language('check_out_time');?></label>
							<input  name="check_out_time" id="check_out_time" / type ="text" value="<?php echo String::html_normalize(URL::get('check_out_time'));?>">
						</div>					
						<div class="setting-field">
							<label for="extra_charge_on_saturday"><?php echo Portal::language('extra_charge_on_friday');?></label>
							<input  name="extra_charge_on_saturday" id="extra_charge_on_saturday" / type ="text" value="<?php echo String::html_normalize(URL::get('extra_charge_on_saturday'));?>"> <?php echo HOTEL_CURRENCY;?>
						</div>
						<div class="setting-field">
							<label for="extra_charge_on_sunday"><?php echo Portal::language('extra_charge_on_saturday');?></label>
							<input  name="extra_charge_on_sunday" id="extra_charge_on_sunday" / type ="text" value="<?php echo String::html_normalize(URL::get('extra_charge_on_sunday'));?>"> <?php echo HOTEL_CURRENCY;?>
						</div>					
						<div class="setting-field">
							<label for="reception_service_charge"><?php echo Portal::language('reception_service_charge');?></label>
							<input  name="reception_service_charge" id="reception_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('reception_service_charge'));?>">
						</div>
						<div class="setting-field">
							<label for="reception_tax_rate"><?php echo Portal::language('reception_tax_rate');?></label>
							<input  name="reception_tax_rate" id="reception_tax_rate" / type ="text" value="<?php echo String::html_normalize(URL::get('reception_tax_rate'));?>">
						</div>
                        <div class="setting-field">
							<label for="time_book_overdue"><?php echo Portal::language('time_book_overdue');?></label>
							<input  name="time_book_overdue" id="time_book_overdue" / type ="text" value="<?php echo String::html_normalize(URL::get('time_book_overdue'));?>"> <em><?php echo Portal::language('minute');?></em>
						</div>
                        <div class="setting-field">
							<label for="display_book_overdue"><?php echo Portal::language('display_book_overdue');?></label>
							<input  name="display_book_overdue" type="checkbox" id="display_book_overdue" <?php if(Url::get('display_book_overdue')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="auto_cancel_booking_expired"><?php echo Portal::language('auto_cancel_booking_expired');?></label>
							<input  name="auto_cancel_booking_expired" type="checkbox" id="auto_cancel_booking_expired" <?php if(Url::get('auto_cancel_booking_expired')){echo 'checked';}?> value="1"/>
						</div>  
						<div class="setting-field">
							<label for="late_checkin_auto"><?php echo Portal::language('late_checkin_auto');?></label>
							<input  name="late_checkin_auto" type="checkbox" id="late_checkin_auto" <?php if(Url::get('late_checkin_auto')){echo 'checked';}?> value="1"/>
						</div>                    
						<div class="setting-field">
							<label for="net_price"><?php echo Portal::language('net_price');?></label>
							<input  name="net_price" type="checkbox" id="net_price" <?php if(Url::get('net_price')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="over_book"><?php echo Portal::language('over_book');?></label>
							<input  name="over_book" type="checkbox" id="over_book" <?php if(Url::get('over_book')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="vat_option"><?php echo Portal::language('vat_option');?></label>
							<input  name="vat_option" type="checkbox" id="vat_option" <?php if(Url::get('vat_option')){echo 'checked';}?> value="1"/>
						</div>
						<div class="setting-field">
							<label for="can_edit_charge"><?php echo Portal::language('can_edit_charge');?></label>
							<input  name="can_edit_charge" type="checkbox" id="can_edit_charge" <?php if(Url::get('can_edit_charge')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="IS_KEY"><?php echo Portal::language('IS_KEY');?></label>
							<input  name="IS_KEY" type="checkbox" id="IS_KEY" <?php if(Url::get('IS_KEY')){echo 'checked';} ?> value="1"/>
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">Be-tech</span>
                            <input  name="SERVER_KEY" id="be_tech" value="1" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('SERVER_KEY'));?>">
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top; ">Adel</span>
                            <input  name="SERVER_KEY" id="adel" value="2" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('SERVER_KEY'));?>">
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top; ">Salto</span>
                            <input  name="SERVER_KEY" id="salto" value="3" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('SERVER_KEY'));?>">
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">HuneRF</span>
                            <input  name="SERVER_KEY" id="hunerf" value="4" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('SERVER_KEY'));?>">
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">Orbita</span>
                            <input  name="SERVER_KEY" id="orbita" value="5" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('SERVER_KEY'));?>">
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">BQLock</span>
                            <input  name="SERVER_KEY" id="bqlock" value="6" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('SERVER_KEY'));?>">
						</div>
                        <div class="setting-field">
							<label><?php echo Portal::language('setting_price_when_change_room');?> BOOKED</label>
                            <input type="checkbox" checked="checked" style="opacity: 0;" />
                            <span style="color: #0066CC; text-align: left; vertical-align:top;"><?php echo Portal::language('keep_the_price');?></span>
                            <input  name="CHANGE_ROOM_BOOKED" id="booked_keep_the_price" value="KEEP" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('CHANGE_ROOM_BOOKED'));?>">
                            
                            <span style=" color: #0066CC; text-align: left; vertical-align:top; "><?php echo Portal::language('same_level');?> <?php echo Portal::language('keep_the_price');?></span>
                            <input  name="CHANGE_ROOM_BOOKED" id="booked_same_level_keep_the_price" value="SAME" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('CHANGE_ROOM_BOOKED'));?>">
                            
                            <span style="color: #0066CC; text-align: left; vertical-align:top; "><?php echo Portal::language('always_change_the_price');?></span>
                            <input  name="CHANGE_ROOM_BOOKED" id="booked_always_change_the_price" value="ALWAY" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('CHANGE_ROOM_BOOKED'));?>">
						</div>
                        <div class="setting-field">
							<label><?php echo Portal::language('setting_price_when_change_room');?> CHECKIN</label>
                            <input type="checkbox" checked="checked" style="opacity: 0;" />
                            <span style="color: #0066CC; text-align: left; vertical-align:top;"><?php echo Portal::language('keep_the_price');?></span>
                            <input  name="CHANGE_ROOM_CHECKIN" id="checkin_keep_the_price" value="KEEP" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('CHANGE_ROOM_CHECKIN'));?>">
                            
                            <span style="color: #0066CC; text-align: left; vertical-align:top; "><?php echo Portal::language('same_level');?> <?php echo Portal::language('keep_the_price');?></span>
                            <input  name="CHANGE_ROOM_CHECKIN" id="checkin_same_level_keep_the_price" value="SAME" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('CHANGE_ROOM_CHECKIN'));?>">
                            
                            <span style="color: #0066CC; text-align: left; vertical-align:top; "><?php echo Portal::language('always_change_the_price');?></span>
                            <input  name="CHANGE_ROOM_CHECKIN" id="checkin_always_change_the_price" value="ALWAY" style="width: 25px !important;" / type ="radio" value="<?php echo String::html_normalize(URL::get('CHANGE_ROOM_CHECKIN'));?>">
						</div>
                        <div class="setting-field">
							<label for="using_passport"><?php echo Portal::language('using_passport');?></label>
							<input  name="using_passport" type="checkbox" id="using_passport" <?php if(Url::get('using_passport')){echo 'checked';}?> value="1"/>
						</div>
					</div>
				</div><!-- END SETTING reception   -->
				<div id="minibar" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="minibar_import_unlimit"><?php echo Portal::language('minibar_import_unlimit');?></label>
							<input  name="minibar_import_unlimit" type="checkbox" id="minibar_import_unlimit"/>
						</div>
						<div class="setting-field">
							<label for="minibar_service_charge"><?php echo Portal::language('minibar_service_charge');?></label>
							<input  name="minibar_service_charge" id="minibar_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('minibar_service_charge'));?>">
						</div>
						<div class="setting-field">
							<label for="minibar_tax_rate"><?php echo Portal::language('minibar_tax_rate');?></label>
							<input  name="minibar_tax_rate" id="minibar_tax_rate" / type ="text" value="<?php echo String::html_normalize(URL::get('minibar_tax_rate'));?>">
						</div>
                        <div class="setting-field">
							<label for="net_price_minibar"><?php echo Portal::language('net_price_minibar');?></label>
							<input  name="net_price_minibar" type="checkbox" id="net_price_minibar" <?php if(Url::get('net_price_minibar')){echo 'checked';}?> value="1"/>
						</div>
					</div>
				</div>
				<div id="laundry" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="laundry_tax_rate"><?php echo Portal::language('laundry_tax_rate');?></label>
							<input  name="laundry_tax_rate" id="laundry_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('laundry_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="laundry_service_charge"><?php echo Portal::language('laundry_service_charge');?></label>
							<input  name="laundry_service_charge" id="laundry_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('laundry_service_charge'));?>">
						</div>
						<div class="setting-field">
							<label for="laundry_express_rate"><?php echo Portal::language('laundry_express_rate');?></label>
							<input  name="laundry_express_rate" id="laundry_express_rate" / type ="text" value="<?php echo String::html_normalize(URL::get('laundry_express_rate'));?>">
						</div>
                        <div class="setting-field">
							<label for="net_price_laundry"><?php echo Portal::language('net_price_laundry');?></label>
							<input  name="net_price_laundry" type="checkbox" id="net_price_laundry" <?php if(Url::get('net_price_laundry')){echo 'checked';}?> value="1"/>
						</div>
					</div>             
				</div>
				<div id="restaurant" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="res_tax_rate"><?php echo Portal::language('res_tax_rate');?></label>
							<input  name="res_tax_rate" id="res_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('res_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="res_service_charge"><?php echo Portal::language('res_service_charge');?></label>
							<input  name="res_service_charge" id="res_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('res_service_charge'));?>">
						</div>
						<div class="setting-field">
							<label for="res_exchange_rate"><?php echo Portal::language('restaurant_exchange_rate');?></label>
							<input  name="res_exchange_rate" id="res_exchange_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('res_exchange_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="res_edit_product_price"><?php echo Portal::language('res_edit_product_price');?></label>
							<input  name="res_edit_product_price" type="checkbox" id="res_edit_product_price" <?php if(Url::get('res_edit_product_price')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="use_display"><?php echo Portal::language('use_display');?></label>
							<input  name="use_display" type="checkbox" id="use_display" <?php if(Url::get('use_display')){echo 'checked';}?> value="1" onclick="appearElement(this);"/>
						</div>
                        <div class="setting-field use_display" style="display:  <?php if(!Url::get('use_display')){echo 'none';}?>;">
							<label for="ip_nodejs_server"><?php echo Portal::language('ip_nodejs_server');?></label>
							<input  name="ip_nodejs_server" id="ip_nodejs_server"/ type ="text" value="<?php echo String::html_normalize(URL::get('ip_nodejs_server'));?>">
						</div>
                        <div class="setting-field use_display" style="display: <?php if(!Url::get('use_display')){echo 'none';}?>;">
							<label for="port_nodejs_server"><?php echo Portal::language('port_nodejs_server');?></label>
							<input  name="port_nodejs_server" id="port_nodejs_server"/ type ="text" value="<?php echo String::html_normalize(URL::get('port_nodejs_server'));?>">
						</div>
					</div>             
				</div>
                <div id="karaoke" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="karaoke_tax_rate"><?php echo Portal::language('karaoke_tax_rate');?></label>
							<input  name="karaoke_tax_rate" id="karaoke_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('karaoke_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="karaoke_service_charge"><?php echo Portal::language('karaoke_service_charge');?></label>
							<input  name="karaoke_service_charge" id="karaoke_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('karaoke_service_charge'));?>">
						</div>
						<div class="setting-field">
							<label for="karaoke_exchange_rate"><?php echo Portal::language('karaoke_exchange_rate');?></label>
							<input  name="karaoke_exchange_rate" id="karaoke_exchange_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('karaoke_exchange_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="karaoke_edit_product_price"><?php echo Portal::language('karaoke_edit_product_price');?></label>
							<input  name="karaoke_edit_product_price" type="checkbox" id="karaoke_edit_product_price" <?php if(Url::get('karaoke_edit_product_price')){echo 'checked';}?> value="1"/>
						</div>
					</div>             
				</div>
				<div id="extra_service" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="extra_service_tax_rate"><?php echo Portal::language('extra_service_tax_rate');?></label>
							<input  name="extra_service_tax_rate" id="extra_service_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('extra_service_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="extra_service_service_charge"><?php echo Portal::language('extra_service_service_charge');?></label>
							<input  name="extra_service_service_charge" id="extra_service_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('extra_service_service_charge'));?>">
						</div>
     	                <div class="setting-field">
							<label for="net_price_service"><?php echo Portal::language('net_price_service');?></label>
							<input  name="net_price_service" type="checkbox" id="net_price_service" <?php if(Url::get('net_price_service')){echo 'checked';}?> value="1"/>
						</div>
					</div>             
				</div>
				<div id="telephone" class="tab-content">
					<div>
        				<div class="setting-field">
        					<label for="telephone_config"><?php echo Portal::language('telephone_config');?></label>
        					<input  name="telephone_config" id="telephone_config"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('telephone_config'));?>">
        				</div>                    
						<div class="setting-field">
							<label for="telephone_tax_rate"><?php echo Portal::language('telephone_tax_rate');?></label>
							<input  name="telephone_tax_rate" id="telephone_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('telephone_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="telephone_service_charge"><?php echo Portal::language('telephone_service_charge');?></label>
							<input  name="telephone_service_charge" id="telephone_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('telephone_service_charge'));?>">
						</div>
					</div>             
				</div>
				<div id="vending" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="vending_tax_rate"><?php echo Portal::language('vending_tax_rate');?></label>
							<input  name="vending_tax_rate" id="vending_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('vending_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="vending_service_charge"><?php echo Portal::language('vending_service_charge');?></label>
							<input  name="vending_service_charge" id="vending_service_charge"/ type ="text" value="<?php echo String::html_normalize(URL::get('vending_service_charge'));?>">
						</div>
						<div class="setting-field">
							<label for="vending_full_rate"><?php echo Portal::language('vending_full_rate');?></label>
							<input  name="vending_full_rate" id="vending_full_rate"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('vending_full_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="vending_full_charge"><?php echo Portal::language('vending_full_charge');?></label>
							<input  name="vending_full_charge" id="vending_full_charge"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('vending_full_charge'));?>">
						</div>
					</div>             
				</div>
                <div id="spa" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="spa_tax_rate"><?php echo Portal::language('spa_tax_rate');?></label>
							<input  name="spa_tax_rate" id="spa_tax_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('spa_tax_rate'));?>">
						</div>
						<div class="setting-field">
							<label for="spa_service_rate"><?php echo Portal::language('spa_service_rate');?></label>
							<input  name="spa_service_rate" id="spa_service_rate"/ type ="text" value="<?php echo String::html_normalize(URL::get('spa_service_rate'));?>">
						</div>
					    <div class="setting-field">
							<label for="net_price_spa"><?php echo Portal::language('net_price_spa');?></label>
							<input  name="net_price_spa" type="checkbox" id="net_price_spa" <?php if(Url::get('net_price_spa')){echo 'checked';}?> value="1"/>
						</div>
                        <?php if(User::can_admin(false,ANY_CATEGORY)){?>
                        <div class="setting-field">
                            <label for="discount_before_tax"><?php echo Portal::language('discount_before_tax');?></label>
							<input  name="discount_before_tax" type="checkbox" id="discount_before_tax" <?php if(Url::get('discount_before_tax')){echo 'checked';}?> value="1"/>
                        </div>
                        <?php }?>   
					</div>             
				</div><!-- END SETTING spa - SPA -->
				<div id="warehouse" class="tab-content">
					<div class="setting-body">
						<div class="setting-field">
							<label class="field"><?php echo Portal::language('allow_over_export');?>:</label>
							<input  name="allow_over_export" id="allow_over_export" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('allow_over_export'));?>">
						</div>
					</div>
				</div> 
				<div id="pa18" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="PA18_HOTEL_CODE"><?php echo Portal::language('PA18_HOTEL_CODE');?></label>
							<input  name="PA18_HOTEL_CODE" id="PA18_HOTEL_CODE"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_HOTEL_CODE'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_HOTEL_NAME"><?php echo Portal::language('PA18_HOTEL_NAME');?></label>
							<input  name="PA18_HOTEL_NAME" id="PA18_HOTEL_NAME"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_HOTEL_NAME'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_HOTEL_ADDRESS"><?php echo Portal::language('PA18_HOTEL_ADDRESS');?></label>
							<input  name="PA18_HOTEL_ADDRESS" id="PA18_HOTEL_ADDRESS"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_HOTEL_ADDRESS'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_HOTEL_USER"><?php echo Portal::language('PA18_HOTEL_USER');?></label>
							<input  name="PA18_HOTEL_USER" id="PA18_HOTEL_USER"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_HOTEL_USER'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_DISTRICT_CODE"><?php echo Portal::language('PA18_DISTRICT_CODE');?></label>
							<input  name="PA18_DISTRICT_CODE" id="PA18_DISTRICT_CODE"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_DISTRICT_CODE'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_DISTRICT_NAME"><?php echo Portal::language('PA18_DISTRICT_NAME');?></label>
							<input  name="PA18_DISTRICT_NAME" id="PA18_DISTRICT_NAME"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_DISTRICT_NAME'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_PROVINCE_CODE"><?php echo Portal::language('PA18_PROVINCE_CODE');?></label>
							<input  name="PA18_PROVINCE_CODE" id="PA18_PROVINCE_CODE"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_PROVINCE_CODE'));?>">
						</div>
						<div class="setting-field">
							<label for="PA18_PROVINCE_NAME"><?php echo Portal::language('PA18_PROVINCE_NAME');?></label>
							<input  name="PA18_PROVINCE_NAME" id="PA18_PROVINCE_NAME"/ type ="text" value="<?php echo String::html_normalize(URL::get('PA18_PROVINCE_NAME'));?>">
						</div>
					</div>             
				</div>
				<div id="skins" class="tab-content">
					<div class="setting-body 1" id="setting_skin">
						<img src="<?php echo HOTEL_LOGO;?>" width="100" style="margin-left:155px;margin-top:15px;border:1px solid #CCCCCC;" />
						<div class="setting-field">
							<label class="field">Ch&#7885;n logo cho ph&#7847;n m&#7873;m:</label>
							<input  name="logo" id="logo"/ type ="file" value="<?php echo String::html_normalize(URL::get('logo'));?>">
							<span style="color:#FF0000; margin-left:10px;"><?php if(isset($error)) echo $error; ?></span>
						</div><br />
						<div <?php echo User::is_admin()?'':'style="display:none;"';?>>
							<img src="<?php echo HOTEL_BANNER;?>" height="100px" style="margin-left:155px;margin-top:15px;border:1px solid #CCCCCC;" />
							<div class="setting-field">
								<label class="field">Ch&#7885;n banner cho ph&#7847;n m&#7873;m:</label>
								<input  name="banner" id="banner"/ type ="file" value="<?php echo String::html_normalize(URL::get('banner'));?>">
								<span style="color:#FF0000; margin-left:10px;"><?php if(isset($error)) echo $error; ?></span>
							</div><br />
						</div>
						<img src="<?php echo BACKGROUND_URL;?>" height="300px" style="margin-left:155px;margin-top:15px;border:1px solid #CCCCCC;" />
						<div class="setting-field">
							<label class="field">Ch&#7885;n &#7843;nh n&#7873;n cho ph&#7847;n m&#7873;m:</label>
							<input  name="background_url" id="background_url"/ type ="file" value="<?php echo String::html_normalize(URL::get('background_url'));?>">
							<span style="color:#FF0000; margin-left:10px;"><?php if(isset($error)) echo $error; ?></span>
						</div>  	
					</div>
				</div><!-- END SETTING skins - B?A -->
                <div id="change_point" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="CHANGE_PRICE_TO_POINT"><?php echo Portal::language('CHANGE_PRICE_TO_POINT');?></label>
							<input  name="CHANGE_PRICE_TO_POINT" id="CHANGE_PRICE_TO_POINT" onkeyup="check_is_price(1);" / type ="text" value="<?php echo String::html_normalize(URL::get('CHANGE_PRICE_TO_POINT'));?>">
                            <span> = 1 <?php echo Portal::language('point');?></span>
						</div>
                        <div class="setting-field">
							<label for="CHANGE_POINT_TO_PRICE"><?php echo Portal::language('CHANGE_POINT_TO_PRICE');?></label>
							<input  name="CHANGE_POINT_TO_PRICE" id="CHANGE_POINT_TO_PRICE" onkeyup="check_is_price(2);" / type ="text" value="<?php echo String::html_normalize(URL::get('CHANGE_POINT_TO_PRICE'));?>">
						</div>
                        <div class="setting-field">
							<label for="SETTING_POINT"><?php echo Portal::language('SETTING_POINT');?></label>
							<input  name="SETTING_POINT" type="checkbox" id="SETTING_POINT" <?php if(Url::get('SETTING_POINT')){echo 'checked';}?> value="1"/>
						</div>
                    </div>
                </div>
                <div id="sync_cns" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="SYNC_CNS"><?php echo Portal::language('SYNC_CNS');?></label>
							<input  name="SYNC_CNS" type="checkbox" id="SYNC_CNS" <?php if(Url::get('SYNC_CNS')){echo 'checked="checked"';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="DATE_SYNC_CNS"><?php echo Portal::language('DATE_SYNC_CNS');?></label>
							<input  name="DATE_SYNC_CNS" id="DATE_SYNC_CNS" / type ="text" value="<?php echo String::html_normalize(URL::get('DATE_SYNC_CNS'));?>">
						</div>
                        <div class="setting-field">
							<label for="LINK_SYNC_CNS"><?php echo Portal::language('LINK_SYNC_CNS');?></label>
							<input  name="LINK_SYNC_CNS" id="LINK_SYNC_CNS" / type ="text" value="<?php echo String::html_normalize(URL::get('LINK_SYNC_CNS'));?>">
						</div>
                        <div class="setting-field">
							<label for="BRANCH_CODE_SYNC_CNS"><?php echo Portal::language('BRANCH_CODE_SYNC_CNS');?></label>
							<input  name="BRANCH_CODE_SYNC_CNS" id="BRANCH_CODE_SYNC_CNS" / type ="text" value="<?php echo String::html_normalize(URL::get('BRANCH_CODE_SYNC_CNS'));?>">
						</div>
                    </div>
                </div>
                <div id="siteminder" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="SITEMINDER"><i class="fa fa-fw fa-check"></i><?php echo Portal::language('use');?> SiteMinder</label>
							<input onclick="SiteminderConnection('SITEMINDER');"  name="SITEMINDER" type="checkbox" id="SITEMINDER" <?php if(Url::get('SITEMINDER')){echo 'checked="checked"';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_URI">pmsXchange Service Url</label>
							<input  name="SITEMINDER_URI" id="SITEMINDER_URI" / type ="text" value="<?php echo String::html_normalize(URL::get('SITEMINDER_URI'));?>">
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_REQUESTOR_ID">Requestor ID</label>
							<input  name="SITEMINDER_REQUESTOR_ID" id="SITEMINDER_REQUESTOR_ID" / type ="text" value="<?php echo String::html_normalize(URL::get('SITEMINDER_REQUESTOR_ID'));?>">
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_USERNAME">UserName</label>
							<input  name="SITEMINDER_USERNAME" id="SITEMINDER_USERNAME" / type ="text" value="<?php echo String::html_normalize(URL::get('SITEMINDER_USERNAME'));?>">
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_PASSWORD">PassWord</label>
							<input  name="SITEMINDER_PASSWORD" id="SITEMINDER_PASSWORD" / type ="text" value="<?php echo String::html_normalize(URL::get('SITEMINDER_PASSWORD'));?>">
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_HOTELCODE">HotelCode</label>
							<input  name="SITEMINDER_HOTELCODE" id="SITEMINDER_HOTELCODE" / type ="text" value="<?php echo String::html_normalize(URL::get('SITEMINDER_HOTELCODE'));?>">
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_HOTELCURRENCY">Hotel Currency (VND or USD)</label>
							<input  name="SITEMINDER_HOTELCURRENCY" id="SITEMINDER_HOTELCURRENCY" / type ="text" value="<?php echo String::html_normalize(URL::get('SITEMINDER_HOTELCURRENCY'));?>">
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_ONE_WAY"><i class="fa fa-fw fa-check"></i>1-way connection</label>
							<input onclick="SiteminderConnection('SITEMINDER_ONE_WAY');"  name="SITEMINDER_ONE_WAY" type="checkbox" id="SITEMINDER_ONE_WAY" <?php if(Url::get('SITEMINDER_ONE_WAY')){echo 'checked="checked"';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_TWO_WAY"><i class="fa fa-fw fa-check"></i>2-way connection</label>
							<input onclick="SiteminderConnection('SITEMINDER_TWO_WAY');"  name="SITEMINDER_TWO_WAY" type="checkbox" id="SITEMINDER_TWO_WAY" <?php if(Url::get('SITEMINDER_TWO_WAY')){echo 'checked="checked"';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_HOTELCODE">GetSecretKey</label>
							<div class="w3-button w3-teal" onclick="location.href='?page=siteminder_api_secret_key';">GetKey</div>
						</div>
                    </div>
                </div>
                 <!-- Booking confirm-->
                <div id="book_confirm" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="city"><?php echo Portal::language('city');?></label>
							<input  name="city" id="city" / type ="text" value="<?php echo String::html_normalize(URL::get('city'));?>">
						</div>
                        <div class="setting-field">
							<label for="city"><?php echo Portal::language('city_name');?></label>
							<input  name="city_name" id="city_name" / type ="text" value="<?php echo String::html_normalize(URL::get('city_name'));?>">
						</div>
                    </div>
                </div>
                <!-- Booking confirm-->
			</div>
        </div>
        </td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    
    jQuery("#DATE_SYNC_CNS").datepicker();
	jQuery(document).ready(function(){
	   
		jQuery('#setting_tab').easytabs({animationSpeed:0});
        var server_key =<?php echo $_REQUEST['SERVER_KEY'];?>;	
        switch(server_key)
        {
            case 1:
            {
                document.getElementById('be_tech').checked = true;
                break;
            }
            case 2:
            {
                document.getElementById('adel').checked = true;
                break;
            }
            case 3:
            {
                document.getElementById('salto').checked = true;
                break;
            }
            case 4:
            {
                document.getElementById('hunerf').checked = true;
                break;
            }
            case 5:
            {
                document.getElementById('orbita').checked = true;
                break;
            }
            case 6:
            {
                document.getElementById('bqlock').checked = true;
                break;
            }
        }
        var setting_price_when_change_room_booked ='<?php echo $_REQUEST['CHANGE_ROOM_BOOKED'];?>';	
        switch(setting_price_when_change_room_booked)
        {
            case 'KEEP':
            {
                document.getElementById('booked_keep_the_price').checked = true;
                break;
            }
            case 'SAME':
            {
                document.getElementById('booked_same_level_keep_the_price').checked = true;
                break;
            }
            case 'ALWAY':
            {
                document.getElementById('booked_always_change_the_price').checked = true;
                break;
            }
        }
        var setting_price_when_change_room_checkin ='<?php echo $_REQUEST['CHANGE_ROOM_CHECKIN'];?>';	
        switch(setting_price_when_change_room_checkin)
        {
            case 'KEEP':
            {
                document.getElementById('checkin_keep_the_price').checked = true;
                break;
            }
            case 'SAME':
            {
                document.getElementById('checkin_same_level_keep_the_price').checked = true;
                break;
            }
            case 'ALWAY':
            {
                document.getElementById('checkin_always_change_the_price').checked = true;
                break;
            }
        }
	})
	$('use_night_audit').checked = <?php echo Url::get('use_night_audit')?'true':'false';?>;
	$('allow_credit_card_type').checked = <?php echo Url::get('allow_credit_card_type')?'true':'false';?>;
	$('minibar_import_unlimit').checked = <?php echo Url::get('minibar_import_unlimit')?'true':'false';?>;
	$('check_browser').checked = <?php echo Url::get('check_browser')?'true':'false';?>;
    $('rate_code').checked = <?php echo Url::get('rate_code')?'true':'false';?>;
    $('use_hls').checked = <?php echo Url::get('use_hls')?'true':'false';?>;
    $('use_allotment').checked = <?php echo Url::get('use_allotment')?'true':'false';?>;
    $('allow_over_export').checked = <?php echo Url::get('allow_over_export')?'true':'false';?>;
    $('vending_full_rate').checked = <?php echo Url::get('vending_full_rate')?'true':'false';?>;
    $('vending_full_charge').checked = <?php echo Url::get('vending_full_charge')?'true':'false';?>;
    $('telephone_config').checked = <?php echo Url::get('telephone_config')?'true':'false';?>;
    $('using_chat').checked = <?php echo Url::get('using_chat')?'true':'false';?>;
    $('#breakfast_from_time').mask("99:99");
    $('#breakfast_to_time').mask("99:99");
    // oanh add
        jQuery('#auto_li_start_time').mask("99:99");
        jQuery('#auto_li_end_time').mask("99:99");
      // End Oanh
    function check_is_price(obj){
        if(obj==1){
            var CHANGE_PRICE_TO_POINT = to_numeric(jQuery("#CHANGE_PRICE_TO_POINT").val());
            check_is_number = isNaN(CHANGE_PRICE_TO_POINT);
            if(check_is_number == true){
                alert("khong phai dinh dang so - is not number");
                jQuery("#CHANGE_PRICE_TO_POINT").val("");
            }
        }else{
            var CHANGE_POINT_TO_PRICE = to_numeric(jQuery("#CHANGE_POINT_TO_PRICE").val());
            check_is_number = isNaN(CHANGE_POINT_TO_PRICE);
            if(check_is_number == true){
                alert("khong phai dinh dang so - is not number");
                jQuery("#CHANGE_POINT_TO_PRICE").val("");
            }
        }
    }
    
    // Oanh add dinh dang gio auto LI
    function checkTimeFormat(obj){
        var value = jQuery(obj).val();
        var arr = value.split(":");
        if(arr[0]<0 || arr[0]>23){
            alert("Không đúng định dạng giờ");
            jQuery(obj).focus();
            return false;
        }
        if(arr[1]<0 || arr[1]>59){
            alert("Không đúng định dạng giờ");
            jQuery(obj).focus();
            return false;
        }
    }
    function appearElement(obj){
        if(jQuery(obj).is(":checked")){
            jQuery("div.use_display").each(function(){
                jQuery(this).css('display','');
            });
        }
        else{
            jQuery("div.use_display").each(function(){
                jQuery(this).css('display','none');
            });
        }
    }
    function SiteminderConnection($key){
        if($key=='SITEMINDER'){
            if(document.getElementById('SITEMINDER').checked == false){
                document.getElementById('SITEMINDER_ONE_WAY').checked = false;
                document.getElementById('SITEMINDER_TWO_WAY').checked = false;
            }else{
                if(document.getElementById('SITEMINDER_TWO_WAY').checked==true)
                    document.getElementById('SITEMINDER_ONE_WAY').checked = false;
                else
                    document.getElementById('SITEMINDER_ONE_WAY').checked = true;
            }
        }else if($key=='SITEMINDER_ONE_WAY'){
            if(document.getElementById('SITEMINDER').checked == false){
                document.getElementById('SITEMINDER_ONE_WAY').checked = false;
                document.getElementById('SITEMINDER_TWO_WAY').checked = false;
            }else{
                if(document.getElementById('SITEMINDER_ONE_WAY').checked==true)
                    document.getElementById('SITEMINDER_TWO_WAY').checked = false;
                else
                    document.getElementById('SITEMINDER_TWO_WAY').checked = true;
            }
        }else if($key=='SITEMINDER_TWO_WAY'){
            if(document.getElementById('SITEMINDER').checked == false){
                document.getElementById('SITEMINDER_ONE_WAY').checked = false;
                document.getElementById('SITEMINDER_TWO_WAY').checked = false;
            }else{
                if(document.getElementById('SITEMINDER_TWO_WAY').checked==true)
                    document.getElementById('SITEMINDER_ONE_WAY').checked = false;
                else
                    document.getElementById('SITEMINDER_ONE_WAY').checked = true;
            }
        }
    }
</script>
