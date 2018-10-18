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
                    <td class="form-title" width="70%">[[.setting_manage.]]</td>
					<td align="right"><input name="submit" type="submit" value="[[.Save.]]" /></td>
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
				<div class="setting-notice">[[.update_success.]]</div>
				<script>jQuery('.setting-notice').fadeOut(2000);</script>
				<?php }?>
				</div>
				<div class="setting-error"><?php Form::$current->error_messages();?></div>
				<div id="setting_tab" class="tab-container">
					<ul>
						<li class="tab"><a href="#general_info">[[.general_info.]]</a></li>
						<li class="tab"><a href="#payment">[[.payment.]]</a></li>
						<li class="tab"><a href="#reception">[[.reception.]]</a></li>
						<li class="tab"><a href="#minibar">[[.minibar.]]</a></li>
						<li class="tab"><a href="#laundry">[[.laundry.]]</a></li>
						<li class="tab"><a href="#restaurant">[[.restaurant.]]</a></li>
                        <li class="tab"><a href="#karaoke">[[.karaoke.]]</a></li>
						<li class="tab"><a href="#extra_service">[[.extra_service.]]</a></li>
						<li class="tab"><a href="#telephone">[[.telephone.]]</a></li>
						<li class="tab"><a href="#vending">[[.vending.]]</a></li>
                        <li class="tab"><a href="#spa">[[.spa.]]</a></li>
						<li class="tab"><a href="#warehouse">[[.warehouse.]]</a></li>
						<li class="tab"><a href="#pa18">[[.PA18.]]</a></li>
						<li class="tab"><a href="#skins">[[.skins.]]</a></li>
                        <li class="tab"><a href="#change_point">[[.change_point.]]</a></li>
                        <li class="tab"><a href="#sync_cns">[[.sync_cns.]]</a></li>
                        <li class="tab"><a href="#siteminder">[[.siteminder.]]</a></li>
                        <li class="tab"><a href="#book_confirm">[[.book_confirm.]]</a></li>
					</ul>
					<div id="general_info" class="tab-content">
						<div class="setting-field">
							<label for="hotel_name">[[.hotel_name.]]</label>
							<input name="hotel_name" type="text" id="hotel_name" />
						</div>
                                                <div class="setting-field">
							<label for="hotel_palce">[[.hotel_place.]]</label>
							<input name="hotel_place" type="text" id="hotel_place" />
						</div>
						<div class="setting-field">
							<label for="hotel_address">[[.hotel_address.]]</label>
							<textarea name="hotel_address" id="hotel_address"style="width:250px;"></textarea>
						</div>
						<div class="setting-field">
							<label for="hotel_phone">[[.hotel_phone.]]</label>
							<input name="hotel_phone" type="text" id="hotel_phone"   />
						</div>
						<div class="setting-field">
							<label for="hotel_fax">[[.hotel_fax.]]</label>
							<input name="hotel_fax" type="text" id="hotel_fax" />
						</div>
						<div class="setting-field">
							<label for="hotel_taxcode">[[.hotel_taxcode.]]</label>
							<input name="hotel_taxcode" type="text" id="hotel_taxcode" />
						</div>
						<div class="setting-field">
							<label for="hotel_currency">[[.hotel_currency.]]</label>
							<select name="hotel_currency" id="hotel_currency" ></select>
						</div>
						<div class="setting-field">
							<label for="hotel_email">[[.hotel_email.]]</label>
							<input name="hotel_email" type="text" id="hotel_email" />
						</div>
						<div class="setting-field">
							<label for="hotel_website">[[.hotel_website.]]</label>
							<input name="hotel_website" type="text" id="hotel_website" />
						</div>
						<div class="setting-field">
							<label for="block_second">[[.block_second.]]</label>
							<input name="block_second" type="text" id="block_second" /> <span class="note">([[.telephone.]])</span></div>
						<div class="setting-field" style="display:none;">
							<label for="lock_db_file">[[.lock_db_file.]]</label>
							<input name="lock_db_file" type="text" id="lock_db_file" />
						<span class="note" style="display:none;">(Only for Mangetic Lock)</span></div>
						<div class="setting-field">
							<label for="round_precision">[[.round_precision.]]</label>
							<input name="round_precision" type="text" id="round_precision" /> 
							<span class="note">([[.comma.]])</span></div>
							<div class="setting-field">
							<label for="night_audit_time">[[.default_night_audit_time.]]</label>
							<input name="night_audit_time" type="text" id="night_audit_time" /></div>                
						<div class="setting-field">
							<label for="use_night_audit">[[.use_night_audit.]]</label>
							<input name="use_night_audit" type="checkbox" id="use_night_audit"></div>	
						<div class="setting-field">
							<label for="night_audit_confirmation">[[.night_audit_confirmation.]]</label>
							<textarea name="night_audit_confirmation" id="night_audit_confirmation" style="width:250px;height:50px;"></textarea></div>
						<div class="setting-field">
							<label for="extra_bed_quantity">[[.extra_bed_quantity.]]</label>
							<input name="extra_bed_quantity" type="text" id="extra_bed_quantity" /></div>                    
						<div class="setting-field">
							<label for="baby_cot_quantity">[[.baby_cot_quantity.]]</label>
							<input name="baby_cot_quantity" type="text" id="baby_cot_quantity" /></div>
						<div class="setting-field">
							<label for="breakfast_price">[[.breakfast_from_time.]]</label>
							<input name="breakfast_from_time" type="text" id="breakfast_from_time" /></div>
                        <div class="setting-field">
							<label for="breakfast_price">[[.breakfast_to_time.]]</label>
							<input name="breakfast_to_time" type="text" id="breakfast_to_time" /></div>
                        <div class="setting-field">
							<label for="breakfast_price">[[.breakfast_price.]]</label>
							<input name="breakfast_price" type="text" id="breakfast_price" /></div>
                        <div class="setting-field">
							<label for="breakfast_child_price">[[.breakfast_child_price.]]</label>
							<input name="breakfast_child_price" type="text" id="breakfast_child_price" /></div>
                        <div class="setting-field">
							<label for="breakfast_net_price">[[.breakfast_net_price.]]</label>
							<input  name="breakfast_net_price" type="checkbox" id="breakfast_net_price" <?php if(Url::get('breakfast_net_price')){echo 'checked';}?> value="1"/></div>
                        <div class="setting-field">
							<label for="breakfast_split_price">[[.breakfast_split_price.]]</label>
							<input  name="breakfast_split_price" type="checkbox" id="breakfast_split_price" <?php if(Url::get('breakfast_split_price')){echo 'checked';}?> value="1"/></div>                                                       
						<div class="setting-field">
							<label for="pickup_price">[[.picup_see_off_price.]]</label>
							<input name="pickup_price" type="text" id="pickup_price" />
						</div>
						<div class="setting-field">
							<label for="check_browser">[[.check_browser.]]:</label>
							<input name="check_browser" type="checkbox" id="check_browser" />
						</div>
                        <div class="setting-field">
							<label for="rate_code">[[.rate_code.]]:</label>
							<input name="rate_code" type="checkbox" id="rate_code" />
						</div>
                        <div class="setting-field">
							<label for="use_hls">Hotel Link Solution:</label>
							<input name="use_hls" type="checkbox" id="use_hls" />
						</div>
                        <div class="setting-field">
							<label for="use_allotment">Allotment:</label>
							<input name="use_allotment" type="checkbox" id="use_allotment" />
						</div>			
                        <div class="setting-field">
							<label for="using_chat">[[.Chat.]]</label>
							<input name="using_chat" type="checkbox" id="using_chat" />
						</div>					
				</div>
				<div id="payment" class="tab-content">
					<div>
					<div class="setting-field">
						<label for="allow_credit_card_type">[[.allow_credit_card_type.]]</label>
						<input  name="allow_credit_card_type" type="checkbox" id="allow_credit_card_type">
					</div>
					</div>
				</div>
				<div id="reception" class="tab-content">
					<div>
                    <!-- oanh add auto LI -->
                        <div class="setting-field">
							<label for="auto_li_start_time">[[.auto_LI_time.]]</label>
							<input name="auto_li_start_time" type="text" id="auto_li_start_time" style="width: 118px !important;" onblur="checkTimeFormat(this);" /> - <input name="auto_li_end_time" type="text" id="auto_li_end_time" style="width: 118px !important;" onblur="checkTimeFormat(this);" />
						</div>
                    <!-- End oanh -->
						<div class="setting-field">
							<label for="check_in_time">[[.check_in_time.]]</label>
							<input name="check_in_time" type="text" id="check_in_time" />
						</div>
						<div class="setting-field">
							<label for="check_out_time">[[.check_out_time.]]</label>
							<input name="check_out_time" type="text" id="check_out_time" />
						</div>					
						<div class="setting-field">
							<label for="extra_charge_on_saturday">[[.extra_charge_on_friday.]]</label>
							<input name="extra_charge_on_saturday" type="text" id="extra_charge_on_saturday" /> <?php echo HOTEL_CURRENCY;?>
						</div>
						<div class="setting-field">
							<label for="extra_charge_on_sunday">[[.extra_charge_on_saturday.]]</label>
							<input name="extra_charge_on_sunday" type="text" id="extra_charge_on_sunday" /> <?php echo HOTEL_CURRENCY;?>
						</div>					
						<div class="setting-field">
							<label for="reception_service_charge">[[.reception_service_charge.]]</label>
							<input name="reception_service_charge" type="text" id="reception_service_charge"/>
						</div>
						<div class="setting-field">
							<label for="reception_tax_rate">[[.reception_tax_rate.]]</label>
							<input name="reception_tax_rate" type="text" id="reception_tax_rate" />
						</div>
                        <div class="setting-field">
							<label for="time_book_overdue">[[.time_book_overdue.]]</label>
							<input name="time_book_overdue" type="text" id="time_book_overdue" /> <em>[[.minute.]]</em>
						</div>
                        <div class="setting-field">
							<label for="display_book_overdue">[[.display_book_overdue.]]</label>
							<input  name="display_book_overdue" type="checkbox" id="display_book_overdue" <?php if(Url::get('display_book_overdue')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="auto_cancel_booking_expired">[[.auto_cancel_booking_expired.]]</label>
							<input  name="auto_cancel_booking_expired" type="checkbox" id="auto_cancel_booking_expired" <?php if(Url::get('auto_cancel_booking_expired')){echo 'checked';}?> value="1"/>
						</div>  
						<div class="setting-field">
							<label for="late_checkin_auto">[[.late_checkin_auto.]]</label>
							<input  name="late_checkin_auto" type="checkbox" id="late_checkin_auto" <?php if(Url::get('late_checkin_auto')){echo 'checked';}?> value="1"/>
						</div>                    
						<div class="setting-field">
							<label for="net_price">[[.net_price.]]</label>
							<input  name="net_price" type="checkbox" id="net_price" <?php if(Url::get('net_price')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="over_book">[[.over_book.]]</label>
							<input  name="over_book" type="checkbox" id="over_book" <?php if(Url::get('over_book')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="vat_option">[[.vat_option.]]</label>
							<input  name="vat_option" type="checkbox" id="vat_option" <?php if(Url::get('vat_option')){echo 'checked';}?> value="1"/>
						</div>
						<div class="setting-field">
							<label for="can_edit_charge">[[.can_edit_charge.]]</label>
							<input  name="can_edit_charge" type="checkbox" id="can_edit_charge" <?php if(Url::get('can_edit_charge')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="IS_KEY">[[.IS_KEY.]]</label>
							<input  name="IS_KEY" type="checkbox" id="IS_KEY" <?php if(Url::get('IS_KEY')){echo 'checked';} ?> value="1"/>
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">Be-tech</span>
                            <input name="SERVER_KEY" type="radio" id="be_tech" value="1" style="width: 25px !important;" />
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top; ">Adel</span>
                            <input name="SERVER_KEY" type="radio" id="adel" value="2" style="width: 25px !important;" />
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top; ">Salto</span>
                            <input name="SERVER_KEY" type="radio" id="salto" value="3" style="width: 25px !important;" />
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">HuneRF</span>
                            <input name="SERVER_KEY" type="radio" id="hunerf" value="4" style="width: 25px !important;" />
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">Orbita</span>
                            <input name="SERVER_KEY" type="radio" id="orbita" value="5" style="width: 25px !important;" />
                            
                            <span style="width:30px; color: #0066CC; text-align: left; vertical-align:top;">BQLock</span>
                            <input name="SERVER_KEY" type="radio" id="bqlock" value="6" style="width: 25px !important;" />
						</div>
                        <div class="setting-field">
							<label>[[.setting_price_when_change_room.]] BOOKED</label>
                            <input type="checkbox" checked="checked" style="opacity: 0;" />
                            <span style="color: #0066CC; text-align: left; vertical-align:top;">[[.keep_the_price.]]</span>
                            <input name="CHANGE_ROOM_BOOKED" type="radio" id="booked_keep_the_price" value="KEEP" style="width: 25px !important;" />
                            
                            <span style=" color: #0066CC; text-align: left; vertical-align:top; ">[[.same_level.]] [[.keep_the_price.]]</span>
                            <input name="CHANGE_ROOM_BOOKED" type="radio" id="booked_same_level_keep_the_price" value="SAME" style="width: 25px !important;" />
                            
                            <span style="color: #0066CC; text-align: left; vertical-align:top; ">[[.always_change_the_price.]]</span>
                            <input name="CHANGE_ROOM_BOOKED" type="radio" id="booked_always_change_the_price" value="ALWAY" style="width: 25px !important;" />
						</div>
                        <div class="setting-field">
							<label>[[.setting_price_when_change_room.]] CHECKIN</label>
                            <input type="checkbox" checked="checked" style="opacity: 0;" />
                            <span style="color: #0066CC; text-align: left; vertical-align:top;">[[.keep_the_price.]]</span>
                            <input name="CHANGE_ROOM_CHECKIN" type="radio" id="checkin_keep_the_price" value="KEEP" style="width: 25px !important;" />
                            
                            <span style="color: #0066CC; text-align: left; vertical-align:top; ">[[.same_level.]] [[.keep_the_price.]]</span>
                            <input name="CHANGE_ROOM_CHECKIN" type="radio" id="checkin_same_level_keep_the_price" value="SAME" style="width: 25px !important;" />
                            
                            <span style="color: #0066CC; text-align: left; vertical-align:top; ">[[.always_change_the_price.]]</span>
                            <input name="CHANGE_ROOM_CHECKIN" type="radio" id="checkin_always_change_the_price" value="ALWAY" style="width: 25px !important;" />
						</div>
                        <div class="setting-field">
							<label for="using_passport">[[.using_passport.]]</label>
							<input  name="using_passport" type="checkbox" id="using_passport" <?php if(Url::get('using_passport')){echo 'checked';}?> value="1"/>
						</div>
					</div>
				</div><!-- END SETTING reception   -->
				<div id="minibar" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="minibar_import_unlimit">[[.minibar_import_unlimit.]]</label>
							<input  name="minibar_import_unlimit" type="checkbox" id="minibar_import_unlimit"/>
						</div>
						<div class="setting-field">
							<label for="minibar_service_charge">[[.minibar_service_charge.]]</label>
							<input name="minibar_service_charge" type="text" id="minibar_service_charge"/>
						</div>
						<div class="setting-field">
							<label for="minibar_tax_rate">[[.minibar_tax_rate.]]</label>
							<input name="minibar_tax_rate" type="text" id="minibar_tax_rate" />
						</div>
                        <div class="setting-field">
							<label for="net_price_minibar">[[.net_price_minibar.]]</label>
							<input  name="net_price_minibar" type="checkbox" id="net_price_minibar" <?php if(Url::get('net_price_minibar')){echo 'checked';}?> value="1"/>
						</div>
					</div>
				</div>
				<div id="laundry" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="laundry_tax_rate">[[.laundry_tax_rate.]]</label>
							<input name="laundry_tax_rate" type="text" id="laundry_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="laundry_service_charge">[[.laundry_service_charge.]]</label>
							<input name="laundry_service_charge" type="text" id="laundry_service_charge"/>
						</div>
						<div class="setting-field">
							<label for="laundry_express_rate">[[.laundry_express_rate.]]</label>
							<input name="laundry_express_rate" type="text" id="laundry_express_rate" />
						</div>
                        <div class="setting-field">
							<label for="net_price_laundry">[[.net_price_laundry.]]</label>
							<input  name="net_price_laundry" type="checkbox" id="net_price_laundry" <?php if(Url::get('net_price_laundry')){echo 'checked';}?> value="1"/>
						</div>
					</div>             
				</div>
				<div id="restaurant" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="res_tax_rate">[[.res_tax_rate.]]</label>
							<input name="res_tax_rate" type="text" id="res_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="res_service_charge">[[.res_service_charge.]]</label>
							<input name="res_service_charge" type="text" id="res_service_charge"/>
						</div>
						<div class="setting-field">
							<label for="res_exchange_rate">[[.restaurant_exchange_rate.]]</label>
							<input name="res_exchange_rate" type="text" id="res_exchange_rate"/>
						</div>
						<div class="setting-field">
							<label for="res_edit_product_price">[[.res_edit_product_price.]]</label>
							<input  name="res_edit_product_price" type="checkbox" id="res_edit_product_price" <?php if(Url::get('res_edit_product_price')){echo 'checked';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="use_display">[[.use_display.]]</label>
							<input  name="use_display" type="checkbox" id="use_display" <?php if(Url::get('use_display')){echo 'checked';}?> value="1" onclick="appearElement(this);"/>
						</div>
                        <div class="setting-field use_display" style="display:  <?php if(!Url::get('use_display')){echo 'none';}?>;">
							<label for="ip_nodejs_server">[[.ip_nodejs_server.]]</label>
							<input name="ip_nodejs_server" type="text" id="ip_nodejs_server"/>
						</div>
                        <div class="setting-field use_display" style="display: <?php if(!Url::get('use_display')){echo 'none';}?>;">
							<label for="port_nodejs_server">[[.port_nodejs_server.]]</label>
							<input name="port_nodejs_server" type="text" id="port_nodejs_server"/>
						</div>
					</div>             
				</div>
                <div id="karaoke" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="karaoke_tax_rate">[[.karaoke_tax_rate.]]</label>
							<input name="karaoke_tax_rate" type="text" id="karaoke_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="karaoke_service_charge">[[.karaoke_service_charge.]]</label>
							<input name="karaoke_service_charge" type="text" id="karaoke_service_charge"/>
						</div>
						<div class="setting-field">
							<label for="karaoke_exchange_rate">[[.karaoke_exchange_rate.]]</label>
							<input name="karaoke_exchange_rate" type="text" id="karaoke_exchange_rate"/>
						</div>
						<div class="setting-field">
							<label for="karaoke_edit_product_price">[[.karaoke_edit_product_price.]]</label>
							<input  name="karaoke_edit_product_price" type="checkbox" id="karaoke_edit_product_price" <?php if(Url::get('karaoke_edit_product_price')){echo 'checked';}?> value="1"/>
						</div>
					</div>             
				</div>
				<div id="extra_service" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="extra_service_tax_rate">[[.extra_service_tax_rate.]]</label>
							<input name="extra_service_tax_rate" type="text" id="extra_service_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="extra_service_service_charge">[[.extra_service_service_charge.]]</label>
							<input name="extra_service_service_charge" type="text" id="extra_service_service_charge"/>
						</div>
     	                <div class="setting-field">
							<label for="net_price_service">[[.net_price_service.]]</label>
							<input  name="net_price_service" type="checkbox" id="net_price_service" <?php if(Url::get('net_price_service')){echo 'checked';}?> value="1"/>
						</div>
					</div>             
				</div>
				<div id="telephone" class="tab-content">
					<div>
        				<div class="setting-field">
        					<label for="telephone_config">[[.telephone_config.]]</label>
        					<input name="telephone_config" type="checkbox" id="telephone_config"/>
        				</div>                    
						<div class="setting-field">
							<label for="telephone_tax_rate">[[.telephone_tax_rate.]]</label>
							<input name="telephone_tax_rate" type="text" id="telephone_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="telephone_service_charge">[[.telephone_service_charge.]]</label>
							<input name="telephone_service_charge" type="text" id="telephone_service_charge"/>
						</div>
					</div>             
				</div>
				<div id="vending" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="vending_tax_rate">[[.vending_tax_rate.]]</label>
							<input name="vending_tax_rate" type="text" id="vending_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="vending_service_charge">[[.vending_service_charge.]]</label>
							<input name="vending_service_charge" type="text" id="vending_service_charge"/>
						</div>
						<div class="setting-field">
							<label for="vending_full_rate">[[.vending_full_rate.]]</label>
							<input name="vending_full_rate" type="checkbox" id="vending_full_rate"/>
						</div>
						<div class="setting-field">
							<label for="vending_full_charge">[[.vending_full_charge.]]</label>
							<input name="vending_full_charge" type="checkbox" id="vending_full_charge"/>
						</div>
					</div>             
				</div>
                <div id="spa" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="spa_tax_rate">[[.spa_tax_rate.]]</label>
							<input name="spa_tax_rate" type="text" id="spa_tax_rate"/>
						</div>
						<div class="setting-field">
							<label for="spa_service_rate">[[.spa_service_rate.]]</label>
							<input name="spa_service_rate" type="text" id="spa_service_rate"/>
						</div>
					    <div class="setting-field">
							<label for="net_price_spa">[[.net_price_spa.]]</label>
							<input  name="net_price_spa" type="checkbox" id="net_price_spa" <?php if(Url::get('net_price_spa')){echo 'checked';}?> value="1"/>
						</div>
                        <?php if(User::can_admin(false,ANY_CATEGORY)){?>
                        <div class="setting-field">
                            <label for="discount_before_tax">[[.discount_before_tax.]]</label>
							<input  name="discount_before_tax" type="checkbox" id="discount_before_tax" <?php if(Url::get('discount_before_tax')){echo 'checked';}?> value="1"/>
                        </div>
                        <?php }?>   
					</div>             
				</div><!-- END SETTING spa - SPA -->
				<div id="warehouse" class="tab-content">
					<div class="setting-body">
						<div class="setting-field">
							<label class="field">[[.allow_over_export.]]:</label>
							<input name="allow_over_export" type="checkbox" id="allow_over_export" />
						</div>
					</div>
				</div> 
				<div id="pa18" class="tab-content">
					<div>
						<div class="setting-field">
							<label for="PA18_HOTEL_CODE">[[.PA18_HOTEL_CODE.]]</label>
							<input name="PA18_HOTEL_CODE" type="text" id="PA18_HOTEL_CODE"/>
						</div>
						<div class="setting-field">
							<label for="PA18_HOTEL_NAME">[[.PA18_HOTEL_NAME.]]</label>
							<input name="PA18_HOTEL_NAME" type="text" id="PA18_HOTEL_NAME"/>
						</div>
						<div class="setting-field">
							<label for="PA18_HOTEL_ADDRESS">[[.PA18_HOTEL_ADDRESS.]]</label>
							<input name="PA18_HOTEL_ADDRESS" type="text" id="PA18_HOTEL_ADDRESS"/>
						</div>
						<div class="setting-field">
							<label for="PA18_HOTEL_USER">[[.PA18_HOTEL_USER.]]</label>
							<input name="PA18_HOTEL_USER" type="text" id="PA18_HOTEL_USER"/>
						</div>
						<div class="setting-field">
							<label for="PA18_DISTRICT_CODE">[[.PA18_DISTRICT_CODE.]]</label>
							<input name="PA18_DISTRICT_CODE" type="text" id="PA18_DISTRICT_CODE"/>
						</div>
						<div class="setting-field">
							<label for="PA18_DISTRICT_NAME">[[.PA18_DISTRICT_NAME.]]</label>
							<input name="PA18_DISTRICT_NAME" type="text" id="PA18_DISTRICT_NAME"/>
						</div>
						<div class="setting-field">
							<label for="PA18_PROVINCE_CODE">[[.PA18_PROVINCE_CODE.]]</label>
							<input name="PA18_PROVINCE_CODE" type="text" id="PA18_PROVINCE_CODE"/>
						</div>
						<div class="setting-field">
							<label for="PA18_PROVINCE_NAME">[[.PA18_PROVINCE_NAME.]]</label>
							<input name="PA18_PROVINCE_NAME" type="text" id="PA18_PROVINCE_NAME"/>
						</div>
					</div>             
				</div>
				<div id="skins" class="tab-content">
					<div class="setting-body 1" id="setting_skin">
						<img src="<?php echo HOTEL_LOGO;?>" width="100" style="margin-left:155px;margin-top:15px;border:1px solid #CCCCCC;" />
						<div class="setting-field">
							<label class="field">Ch&#7885;n logo cho ph&#7847;n m&#7873;m:</label>
							<input name="logo" type="file" id="logo"/>
							<span style="color:#FF0000; margin-left:10px;"><?php if(isset($error)) echo $error; ?></span>
						</div><br />
						<div <?php echo User::is_admin()?'':'style="display:none;"';?>>
							<img src="<?php echo HOTEL_BANNER;?>" height="100px" style="margin-left:155px;margin-top:15px;border:1px solid #CCCCCC;" />
							<div class="setting-field">
								<label class="field">Ch&#7885;n banner cho ph&#7847;n m&#7873;m:</label>
								<input name="banner" type="file" id="banner"/>
								<span style="color:#FF0000; margin-left:10px;"><?php if(isset($error)) echo $error; ?></span>
							</div><br />
						</div>
						<img src="<?php echo BACKGROUND_URL;?>" height="300px" style="margin-left:155px;margin-top:15px;border:1px solid #CCCCCC;" />
						<div class="setting-field">
							<label class="field">Ch&#7885;n &#7843;nh n&#7873;n cho ph&#7847;n m&#7873;m:</label>
							<input name="background_url" type="file" id="background_url"/>
							<span style="color:#FF0000; margin-left:10px;"><?php if(isset($error)) echo $error; ?></span>
						</div>  	
					</div>
				</div><!-- END SETTING skins - B?A -->
                <div id="change_point" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="CHANGE_PRICE_TO_POINT">[[.CHANGE_PRICE_TO_POINT.]]</label>
							<input name="CHANGE_PRICE_TO_POINT" type="text" id="CHANGE_PRICE_TO_POINT" onkeyup="check_is_price(1);" />
                            <span> = 1 [[.point.]]</span>
						</div>
                        <div class="setting-field">
							<label for="CHANGE_POINT_TO_PRICE">[[.CHANGE_POINT_TO_PRICE.]]</label>
							<input name="CHANGE_POINT_TO_PRICE" type="text" id="CHANGE_POINT_TO_PRICE" onkeyup="check_is_price(2);" />
						</div>
                        <div class="setting-field">
							<label for="SETTING_POINT">[[.SETTING_POINT.]]</label>
							<input  name="SETTING_POINT" type="checkbox" id="SETTING_POINT" <?php if(Url::get('SETTING_POINT')){echo 'checked';}?> value="1"/>
						</div>
                    </div>
                </div>
                <div id="sync_cns" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="SYNC_CNS">[[.SYNC_CNS.]]</label>
							<input  name="SYNC_CNS" type="checkbox" id="SYNC_CNS" <?php if(Url::get('SYNC_CNS')){echo 'checked="checked"';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="DATE_SYNC_CNS">[[.DATE_SYNC_CNS.]]</label>
							<input name="DATE_SYNC_CNS" type="text" id="DATE_SYNC_CNS" />
						</div>
                        <div class="setting-field">
							<label for="LINK_SYNC_CNS">[[.LINK_SYNC_CNS.]]</label>
							<input name="LINK_SYNC_CNS" type="text" id="LINK_SYNC_CNS" />
						</div>
                        <div class="setting-field">
							<label for="BRANCH_CODE_SYNC_CNS">[[.BRANCH_CODE_SYNC_CNS.]]</label>
							<input name="BRANCH_CODE_SYNC_CNS" type="text" id="BRANCH_CODE_SYNC_CNS" />
						</div>
                    </div>
                </div>
                <div id="siteminder" class="tab-content">
                    <div>
                        <div class="setting-field">
							<label for="SITEMINDER"><i class="fa fa-fw fa-check"></i>[[.use.]] SiteMinder</label>
							<input onclick="SiteminderConnection('SITEMINDER');"  name="SITEMINDER" type="checkbox" id="SITEMINDER" <?php if(Url::get('SITEMINDER')){echo 'checked="checked"';}?> value="1"/>
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_URI">pmsXchange Service Url</label>
							<input name="SITEMINDER_URI" type="text" id="SITEMINDER_URI" />
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_REQUESTOR_ID">Requestor ID</label>
							<input name="SITEMINDER_REQUESTOR_ID" type="text" id="SITEMINDER_REQUESTOR_ID" />
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_USERNAME">UserName</label>
							<input name="SITEMINDER_USERNAME" type="text" id="SITEMINDER_USERNAME" />
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_PASSWORD">PassWord</label>
							<input name="SITEMINDER_PASSWORD" type="text" id="SITEMINDER_PASSWORD" />
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_HOTELCODE">HotelCode</label>
							<input name="SITEMINDER_HOTELCODE" type="text" id="SITEMINDER_HOTELCODE" />
						</div>
                        <div class="setting-field">
							<label for="SITEMINDER_HOTELCURRENCY">Hotel Currency (VND or USD)</label>
							<input name="SITEMINDER_HOTELCURRENCY" type="text" id="SITEMINDER_HOTELCURRENCY" />
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
							<label for="city">[[.city.]]</label>
							<input name="city" type="text" id="city" />
						</div>
                        <div class="setting-field">
							<label for="city">[[.city_name.]]</label>
							<input name="city_name" type="text" id="city_name" />
						</div>
                    </div>
                </div>
                <!-- Booking confirm-->
			</div>
        </div>
        </td>
    </tr>
</table>
</form>
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
