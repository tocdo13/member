<style>
    .select_rooms
    {
        border-left: 5px solid #FF99CC !important;
        border-right: 5px solid #FF99CC !important;
    }
    .button_new
    {
        background: #FFFFFF;
        border: 1px solid #EEEEEE; 
        float: right; 
        margin-right: 10px; 
        cursor: pointer;
    }
    .button_new span 
    {
        color: #555555; 
        line-height: 25px; 
        float: left; 
        font-weight: normal;
    }
    .button_new img
    {
        width: auto; 
        height: 18px; 
        margin: 3.5px 5px; 
        cursor: pointer; 
        float: left;
    }
    .button_new:hover
    {
        background: #00B2F9;
    }
    .button_new:hover span
    {
        color: #FFFFFF;
    }
    table#area_id_list tr
    {
        border-top: 1px solid #EEEEEE;
        border-right: 1px solid #EEEEEE;
    }
    table#area_id_list tr:last-child
    {
        border-bottom: 1px solid #EEEEEE;
    }
    #area_id_list_box
    {
        position: fixed;
        height: 425px; 
        bottom: -100%; 
        right: 10px;
        padding: 10px; 
        border: 3px solid #00B2F9; 
        box-shadow: 0px 0px 20px #000000;
        background: #FFFFFF;
        transition: all 0.3s ease;
    }
    #area_id_list_box fieldset
    {
        margin: 10px auto;
        background: #FFFFFF;
    }
    #info_room_list_box
    {
        position: fixed;
        height: 425px; 
        bottom: -100%; 
        left: 10px;
        padding: 10px; 
        border: 3px solid #00B2F9; 
        box-shadow: 0px 0px 20px #000000;
        background: #FFFFFF;
        transition: all 0.3s ease;
    }
    #info_room_list_box fieldset
    {
        margin: 10px auto;
        background: #FFFFFF;
    }
    #mCSB_1_container table tr
    {
        border-top: 1px dashed #EEEEEE;
    }
    #mCSB_1_container table tr:first-child
    {
        border-top: none;
    }
    #mCSB_1_container table tr td a
    {
        
    }
    .div_items 
    {
        width: 120px; 
        height: 120px; 
        margin: 5px; 
        border: 1px solid #DDDDDD; 
        float: left; 
        overflow: hidden; 
        background: #F6F6F6; position: relative;
    }
    .div_items .div_items_title
    {
        margin: 0px; 
        padding: 0px; 
        clear: both; 
        width: 120px; 
        height: 20px; 
        line-height: 20px; 
        font-size: 11px;
        border-bottom: 1px solid #DDDDDD; 
        background: #FFFFFF; 
        overflow: hidden; 
        position: absolute; 
        top: 0px; 
        left: 0px;
    }
    .div_items .div_items_nav 
    {
        margin: 0px; 
        padding: 0px; 
        width: 20px; 
        height: 100px; 
        overflow: hidden; 
        position: absolute; 
        top: 20px; 
        right: 0px;
    }
    .div_items .div_items_content
    {
        margin: 0px; 
        padding: 0px; 
        width: 90px; 
        height: 90px; 
        overflow: hidden; 
        position: absolute; 
        top: 20px; 
        left: 0px; 
        text-align: center; 
        font-size: 11px; 
        line-height: 15px; 
        cursor: pointer;
    }
    .fixel_window_scroll
    {
        width: 100%; 
        height: 25px;
        padding: 1% 5px;
        position: fixed; 
        top:  0px; 
        left: 0px;
        background: #FFFFFF;
        box-shadow: 0px 0px 3px #EEEEEE;
        transition: all 0.3s ease;
        z-index: 5000;
    }
    .cloes_fixel_window_scroll
    {
        width: 98%; 
        margin: 0px auto; 
        clear: both; 
        height: 25px; 
        padding-top: 5px; 
        padding-bottom: 5px; 
        border: 1px solid #EEEEEE; 
        box-shadow: 0px 0px 3px #EEEEEE;
    }
    #accordian {
    	background: #ffffff;
    	width: 220px;
    	margin: 10px auto 10px auto;
    	color: white;
        border: 1px solid #cccccc;
    }
    #accordian h3 {
    	font-size: 11px;
    	line-height: 25px;
    	padding: 0 10px;
    	cursor: pointer;
    	background: #ffffff;
        color: #171717;
        border-top: 1px solid #cccccc;
    }
    #accordian ul li:first-child h3
    {
        border-top: none;
    }
    #accordian h3:hover {
    	text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);
    }
    #accordian h3 a,#accordian h3 a:active,#accordian h3 a:hover,#accordian h3 a:visited
    {
        color: #00b2f9;
        text-decoration: none;
    }
    #accordian li {
    	list-style-type: none;
    }
    #accordian ul ul li a {
    	color: #555555;
    	text-decoration: none;
    	font-size: 11px;
    	line-height: 27px;
    	display: block;
    	padding: 0 15px;
    	transition: all 0.15s;
    }
    #accordian ul ul li a:hover {
    	background: #003545;
        color: white;
    	border-left: 5px solid lightgreen;
    }
    #accordian ul ul {
    	display: none;
    }
    #accordian li.active ul {
    	display: block;
    }
</style>
<form name="RoomMapSmartForm" method="POST">
    <!--HEADER-->
        <div id="header_room_map" class="cloes_fixel_window_scroll">
            <div class="button_new" style="height: 25px; width: 100px; background: #00B2F9; float: left;" onclick="toogle_info_room();">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/list.png"/>
                <span style="color: #FFFFFF;">[[.overview.]] </span>
            </div>
            <span style="color: #00B2F9; line-height: 25px; float: left; margin-left: 15px; font-weight: bold; text-transform: uppercase;">[[.viewing_date.]] </span>
            <input name="check_submit_date" type="checkbox" id="check_submit_date" style="display: none;" checked="checked" />
            <input name="in_date" type="text" id="in_date" onchange="jQuery('#check_submit_date').removeAttr('checked');RoomMapSmartForm.submit();" style="height: 20px; width: 80px; color: #00B2F9; font-weight: bold; padding-left: 3px; float: left; margin-left: 3px; border: 1px solid #00B2F9; text-align: center;" autocomplete="OFF" />
            
            <div style="height: 25px; width: 300px; float: left; margin-left: 15px; cursor: pointer;">
                <?php if(sizeof([[=birth_date_arr=]])>0){ ?>
                    <marquee style="width:100%; line-height: 25px; font-weight: normal; color: #555555;" onMouseOut="this.start();" onMouseOver="this.stop();" scrollamount="3">
                        <!--LIST:birth_date_arr-->
                            ***** [[.happy_birth_day_nth.]] [[|birth_date_arr.age|]] [[.of.]] [[|birth_date_arr.traveller_name|]] - [[|birth_date_arr.birth_date|]]  (P.[[|birth_date_arr.name|]]) *****
                        <!--/LIST:birth_date_arr-->
                    </marquee>
                <?php } ?>
            </div>
            <!--IF:cond(User::can_view(false,ANY_CATEGORY))-->
            <div class="button_new" style="height: 25px; width: 110px; background: #00B2F9;" onclick="toogle_list_area();">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/hotel.png"/>
                <span style="color: #FFFFFF;">[[.filter_room.]] </span>
            </div>
            <!--IF:cond_module(User::can_view(MODULE_MANAGENOTE,ANY_CATEGORY))-->
            <div class="button_new" style="height: 25px; width: 140px;" onclick="window.open('?page=manage_note');">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/note.png"/>
                <span>[[.Reservation_note.]] </span>
            </div>
            <!--/IF:cond_module-->
            <div class="button_new" style="height: 25px; width: 170px;" onclick="full_screen_room_map();">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/full_screen.png"/>
                <span>[[.full_screen.]] </span>
            </div>
            <div class="button_new" style="height: 25px; width: 90px;" onclick="if(canCheckin()) buildReservationUrl('RFW');">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/checkin.png"/>
                <span>[[.Walk_in.]] </span>
            </div>
            <div class="button_new" style="height: 25px; width: 120px;" onclick="buildReservationUrl('RFA');">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/booking.png"/>
                <span>[[.New_reservation.]] </span>
            </div>
            <!--/IF:cond-->
        </div>
    <!--/HEADER-->
    <!--CONTAINER-->
    <div id="room_map_container">
        <div id="area_key_[[|area_key|]]" style="width: 98%; margin: 10px auto; clear: both; height: auto; padding-top: 0px; padding-bottom: 5px; border: 1px solid #EEEEEE; box-shadow: 0px 0px 3px #EEEEEE;">
            <div style="line-height: 25px; text-align: center; background: #EEEEEE; color: #171717; padding: 5px 5px 5px 30px; margin: 0px auto;">[[|area_id|]]</div>
            <table style="width: 99%; margin: 0.5% auto;" border="1" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE">
            <?php $tab_index_room = 1; ?>
            <!--LIST:items-->
                <tr>
                    <td style="width: 55px; text-align: center; font-weight: bold; text-transform: uppercase; color: #555555; background: #E1E1E1;">[[|items.name|]]</td>
                    <td>
                        <!--LIST:items.child-->
                            <div class="div_items status_[[|items.child.status|]]">
                                <div class="div_items_title">
                                    <span style="color: #00B2F9; font-size: 11px; font-weight: bold;">[[|items.child.name|]]</span> - <i style=" font-size: 11px;">[[|items.child.type_name|]]</i>
                                </div>
                                <input type="hidden" value="[[|items.child.id|]]" id="[[|items.child.id|]]" />
                                <input type="text" id="status_room_[[|items.child.id|]]" value="[[|items.child.status|]]" style="display: none;" />
                                <div class="div_items_nav">
                                    <?php if(sizeof([[=items.child.active=]])>0){ ?>
                                        <!--LIST:items.child.active-->
                                            <?php if([[=items.child.active.key=]]!=[[=items.child.is_active=]]){ ?>
                                                <a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.child.active.reservation_id|]]&r_r_id=[[|items.child.active.reservation_room_id|]]" title="[[.code.]]: [[|items.child.active.reservation_id|]] [[.status.]]: [[|items.child.active.reservation_room_status|]] [[.price.]]: <?php echo System::display_number([[=items.child.active.change_price=]]); ?>" style="width: 10px; height: 10px; float: left; margin: 2.5px 5px; background: <?php if([[=items.child.active.reservation_room_status=]]=='CHECKOUT'){ echo '#FF66FF';}elseif([[=items.child.active.reservation_room_status=]]=='BOOKED' AND [[=items.child.active.time_in=]]>time()){echo '#00A855';}elseif([[=items.child.active.reservation_room_status=]]=='BOOKED' AND [[=items.child.active.time_in=]]<=time()){echo '#0054a2';} ?>;" ></a>
                                            <?php } ?>
                                        <!--/LIST:items.child.active-->
                                    <?php } ?>
                                </div>
                                <div id="room_[[|items.child.id|]]" onclick="select_rooms([[|items.child.id|]],document.RoomMapSmartForm);show_room_infomation_main('[[|area_key|]]','[[|items.id|]]','[[|items.child.id|]]');" tabindex="<?php echo $tab_index_room++; ?>" class="div_items_content [[|items.child.status|]]" title="<?php if( isset([[=items.child.active=]][[[=items.child.is_active=]]]) AND [[=items.child.active=]][[[=items.child.is_active=]]]['reservation_room_note']!=''){ echo Portal::language('room_note').': '.[[=items.child.active=]][[[=items.child.is_active=]]]['reservation_room_note']; } if([[=items.child.hk_note=]]!=''){ echo Portal::language('hk_note').': '.[[=items.child.hk_note=]]; } ?>">
                                    <?php 
                                        if([[=items.child.house_status=]]=='REPAIR' OR [[=items.child.house_status=]]=='HOUSEUSE')
                                        {
                                            if(date('d/m/Y',[[=items.child.start_time=]])==date('d/m/Y',[[=items.child.end_time=]]))
                                            {
                                                echo '<span style="color: #003399; font-weight: bold; font-size: 11px;"><u>'.date('H:i',[[=items.child.start_time=]]).'-'.date('H:i',[[=items.child.end_time=]]).'</u></span><br/>';
                                            }
                                            else
                                            {
                                                echo '<span style="color: #003399; font-weight: bold; font-size: 11px;"><u>'.date('d/m',[[=items.child.start_time=]]).'-'.date('d/m',[[=items.child.end_time=]]).'</u></span><br/>';
                                            }
                                        } 
                                        if(isset([[=items.child.active=]][[[=items.child.is_active=]]])>0)
                                        {
                                            echo '<span style="color: #003399; font-weight: bold; font-size: 11px;"><u>'.date('d/m',[[=items.child.active=]][[[=items.child.is_active=]]]['time_in']).'-'.date('d/m',[[=items.child.active=]][[[=items.child.is_active=]]]['time_out']).'</u></span><br/>';
                                        } 
                                    ?>
                                    <span id="house_status_[[|items.child.id|]]" style="color: red; font-size: 11px;">[[|items.child.house_status|]]</span><?php if([[=items.child.house_status=]]!=''){ echo '<br />'; } ?>
                                    <?php
                                        if(isset([[=items.child.active=]][[[=items.child.is_active=]]]))
                                        {
                                            echo '<span style="font-size: 11px; background: '.[[=items.child.active=]][[[=items.child.is_active=]]]['reservation_color'].'"><i>'.[[=items.child.active=]][[[=items.child.is_active=]]]['customer_name'].'</i></span><br/>';
                                            echo '<span style="font-size: 11px;">'.[[=items.child.active=]][[[=items.child.is_active=]]]['traveller_name'].'</span><br/>';
                                            if([[=items.child.active=]][[[=items.child.is_active=]]]['reservation_room_note']!='')
                                            {
                                                echo '<span style="color: red;font-size: 11px;font-weight: bold;" title="'.Portal::language('room_note').': '.[[=items.child.active=]][[[=items.child.is_active=]]]['reservation_room_note'].'">*</span><br/>';
                                            }
                                        }
                                        if([[=items.child.hk_note=]]!='')
                                        {
                                            echo '<span style="color: red;font-size: 11px;font-weight: bold;" title="'.Portal::language('hk_note').': '.[[=items.child.hk_note=]].'">*</span><br/>';
                                        }
                                    ?>
                                </div>
                                <input name="room[[[|items.child.id|]]][id]" type="checkbox" id="room_id_[[|items.child.id|]]" value="[[|items.child.id|]]" style="display: none;" />
                                
                            </div>
                        <!--/LIST:items.child-->
                    </td>
                </tr>
            <!--/LIST:items-->
            </table>
            <input name="room_ids" type="text" id="room_ids" value="" style="display: none;" />
            <input name="room_id_tab" type="text" id="room_id_tab" value="" style="display: none;" />
        </div>
    </div>
    <!--/CONTAINER-->
    <!--WINDOW INFO ROOM dragclass-->
    <div id="room_infomation" class="dragclass" style="width: 350px; height: 300px; background: #FFFFFF; overflow: hidden; border: 5px solid #00B2F9; box-shadow: 0px 0px 15px #000000; position: absolute; top: 100px; left: 100px; z-index: 2000; display: none;">
        <div style="width: 100%; height: 30px; background: #00B2F9; margin: 0px; padding: 0px; clear: both;">
            <span style="color: #FFFFFF; line-height: 30px; float: left; text-transform: uppercase;">[[.room_info.]]</span>
            <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/close.png" onclick="toogle_room_infomation('close',-500,-500);" style="width: 20px; height: auto; margin: 4px; float: right; border: 1px solid #EEEEEE; cursor: pointer"/>
            <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/maximun.png" onclick="toogle_room_infomation('show',100,100);" style="width: 20px; height: auto; margin: 4px; float: right; border: 1px solid #EEEEEE; cursor: pointer"/>
            <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/min.png" onclick="toogle_room_infomation('min',0,0);" style="width: 20px; height: auto; margin: 4px; float: right; border: 1px solid #EEEEEE; cursor: pointer"/>
        </div>
        <div id="room_infomation_main" style="width: 100%; height: 224px; margin: 0px auto; padding-top: 5px; padding-bottom: 5px; overflow: auto;">
            <!-- INSERT CONTENT -->
        </div>
        <div style="width: 100%; height: 25px; padding-top: 5px; padding-bottom: 5px; border-top: 1px solid #00B2F9;">
            <div class="button_new" style="height: 25px; width: 90px;" onclick="if(canCheckin()) buildReservationUrl('RFW');">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/checkin.png"/>
                <span>[[.Walk_in.]] </span>
            </div>
            <div class="button_new" style="height: 25px; width: 120px;" onclick="buildReservationUrl('RFA');">
                <img src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/booking.png"/>
                <span>[[.New_reservation.]] </span>
            </div>
        </div>
    </div>
    <!--/WINDOW INFO ROOM-->
</form>
<!--AREA LIST-->
    <div id="area_id_list_box">
        <input type="checkbox" name="check_show_list_area" id="check_show_list_area" style="display: none;" />
        <fieldset>
            <legend class="title"><b>[[.filter_area.]]</b></legend>
            <table cellpadding="5" cellspacing="0" style="width: 100%;">
                <tr>
                    <td><input name="view_one_area" type="checkbox" id="view_one_area" checked="checked" />[[.view_one_area.]]</td>
                </tr>
            </table>
            <table id="area_id_list" cellpadding="5" cellspacing="0" style="width: 100%;">
                <!--LIST:area_id_list-->
                    <tr style="cursor: pointer;" onclick="load_area('[[|area_id_list.id|]]');">
                        <td style="width: 1px; background: #00B2F9;"></td>
                        <td><span style="font-weight: bold;">[[|area_id_list.code|]]</span></td>
                        <td style="width: 22px;"><input type="checkbox" id="check_box_area_[[|area_id_list.id|]]" style="display: none;" <?php if([[=area_id_list.code=]]==[[=area_id=]]){ echo 'checked="checked"'; } ?> /><img id="img_[[|area_id_list.id|]]" src="packages/hotel/packages/reception/modules/RoomMapSmart/icon/check.png" style="<?php if([[=area_id_list.code=]]!=[[=area_id=]]){ echo 'display: none;'; } ?>"/></td>
                    </tr>
                <!--/LIST:area_id_list-->
            </table>
        </fieldset>
        <fieldset>
            <legend class="title"><b>[[.filter_status.]]</b></legend>
            <table cellpadding="5" cellspacing="0">
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer; border: 5px dashed #555555;" onclick="fun_filter_status('ALL');"></div></td>
                    <td>[[.all.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="AVAILABLE" onclick="fun_filter_status('AVAILABLE');"></div></td>
                    <td>[[.available.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="BOOKED" onclick="fun_filter_status('BOOKED');"></div></td>
                    <td>[[.booked.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="OVERDUE_BOOKED" onclick="fun_filter_status('OVERDUE_BOOKED');"></div></td>
                    <td>[[.overdue_booked.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="OCCUPIED" onclick="fun_filter_status('OCCUPIED');"></div></td>
                    <td>[[.occupied.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="CHANGE_IN_DATE" onclick="fun_filter_status('CHANGE_IN_DATE');"></div></td>
                    <td>[[.change_in_date.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="DAYUSED" onclick="fun_filter_status('DAYUSED');"></div></td>
                    <td>[[.day_used.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="OVERDUE" onclick="fun_filter_status('OVERDUE');"></div></td>
                    <td>[[.overdue.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="EXPECTED_CHECKOUT" onclick="fun_filter_status('EXPECTED_CHECKOUT');"></div></td>
                    <td>[[.expected_checkout.]]</td>
                </tr>
                <tr>
                    <td style="text-align: center;"><div style="padding: 10px; cursor: pointer;" class="REPAIR" onclick="fun_filter_status('REPAIR');"></div></td>
                    <td>[[.repair.]]</td>
                </tr>
            </table>
        </fieldset>
    </div>
<!--/AREA LIST-->
<!--INFO ROOM MAP LIST -->
    <div id="info_room_list_box">
        <input type="checkbox" name="check_show_toogle_info_room" id="check_show_toogle_info_room" style="display: none;" />
        <fieldset>
            <legend class="title"><b>[[.forcecast.]]</b></legend>
            <table id="forcecast_list">
                
            </table>
        </fieldset>
        <fieldset>
            <legend class="title"><b>[[.Extra_bed_baby_cot.]]</b></legend>
            <table id="extra_bed_baby_cot" cellpadding="5" cellspacing="0" style="width: 100%;" border="1" bordercolor="#EEEEEE">
                <tr>
                	<td>[[.service_name.]]</td>
                	<td>[[.eb_total_quantity.]]</td>                
                	<td>[[.eb_quantity.]]</td>                                
                </tr>
            </table>
        </fieldset>
        <!--IF:edit_reservation(USER::can_view(false,ANY_CATEGORY))-->
        <fieldset>
            <legend class="title"><b>[[.search_booking.]]</b></legend> 
    		<table cellpadding="5" cellspacing="0">
                <!-- start: Manh them truong tim kiem theo phong  -->
                <tr>
    			  <td>[[.room.]]</td>
    			  <td><input name="number_room" type="text" id="number_room" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
                </tr>
                <!-- end: Manh them truong tim kiem theo phong  -->  
    			<tr>
    			  <td>[[.RE_code.]] </td>
    			  <td><input name="code" type="text" id="code" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
                </tr>              
    			<tr>
    			  <td>[[.booking_code.]] </td>
    			  <td><input name="booking_code" type="text" id="booking_code" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
                </tr>
    			<tr>
                    <td>[[.company_name.]] </td>
    				<td><input type="text" id="customer_name" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
    			</tr>
    			<tr>
                    <td>[[.traveller_name.]] </td>
                    <td><input name="traveller_name" type="text" id="traveller_name" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
                </tr>
                <tr>
    			  <td>[[.name_booker.]] </td>
    			  <td><input name="booker" style="width:100px;" id="booker" onfocus="customerNameAutocomplete();" autocomplete="off" type="text" value="" class="acInput"/></td>
                </tr>  
    			<tr>
    			  <td>[[.group_note_room.]]</td>
    			  <td><input name="note" type="text" id="note" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
                </tr>
    			<tr>
    			  <td>[[.country.]]</td>
    			  <td><input name="nationality_id" type="text" id="nationality_id" style="width:100px;" onkeypress="if(event.keyCode==13){buildReservationSearch();}"/></td>
                </tr>
                <tr>
    			  <td>[[.status.]]</td>
    			  <td>
                    <select  name="room_status" id="room_status" style="width:100px;">
                    	<option value="" selected="selected">ALL</option>
                    	<option value="CHECKIN">CHECKIN</option>
                        <option value="BOOKED">BOOKED</option>
                        <option value="CHECKOUT">CHECKOUT</option>
                        <option value="CANCEL">CANCEL</option>
                    </select>
                    </td>
    			</tr>
                <tr>
                    <td></td>
                    <td><input type="button" name="search-booking" onclick="buildReservationSearch();" value="OK" /></td>
                </tr>
    		</table>
        </fieldset>
		<!--/IF:edit_reservation-->
        <fieldset>
            <legend class="title"><b>[[.check_availability.]]</b></legend> 
			<table cellpadding="5" cellspacing="0">
			  <tr>
				<td>[[.arrival_time.]]:</td>
				<td><input name="arrival_time" type="text" id="arrival_time"  style="width:100px;" readonly="readonly" onchange="changevalue();" /></td>
			  </tr>
			  <tr>
				<td>[[.departure_time.]]:</td>
				<td><input name="departure_time" type="text" id="departure_time"  style="width:100px;" readonly="readonly" onchange="changefromday();" /></td>
			  </tr>
			  <tr>
				<td>[[.room_type.]]:</td>
				<td><select name="room_level_id" id="room_level_id" style="width:100px;"></select></td>
			  </tr>
				<tr><td></td><td>
				<input type="button" value="[[.Go.]]" onclick="CheckValidate();"/>
            </td></tr>
			</table>
		</fieldset>
        <fieldset>
            <legend class="title"><b>[[.booking_without_room.]]</b></legend>
            <table id="booking_without_room" cellpadding="5" cellspacing="0" style="width: 100%;" border="1" bordercolor="#EEEEEE">
                <tr>
					<th>[[.room_level.]]</th>
					<th>[[.num_people.]]</th>
				</tr>
            </table>
        </fieldset>
        <fieldset>
            <legend class="title"><b>[[.booking_cut_of_day.]]</b></legend>
            <div id="accordian">
                <ul id="booking_cut_of_day">
                    
                </ul>
            </div>
        </fieldset>
        <fieldset>
            <legend class="title"><b>[[.description.]]</b></legend>
            <table id="description_status" cellpadding="5" cellspacing="0">
                
            </table>
        </fieldset>
    </div>
<!--/INFO ROOM MAP LIST -->
<script>
    jQuery(document).ready(function(){
        jQuery(window).scroll(function(){
           var top = jQuery(window).scrollTop();
           if(to_numeric(top)>=100)
           {
                jQuery("#header_room_map").addClass('fixel_window_scroll');
                jQuery("#header_room_map").removeClass('cloes_fixel_window_scroll');
                jQuery("#sign-in").css('display','none');
                jQuery("#area_id_list_box").css('height',(window.innerHeight-150)+'px');
                jQuery("#info_room_list_box").css('height',(window.innerHeight-150)+'px');
           }
           else
           {
                jQuery("#header_room_map").addClass('cloes_fixel_window_scroll');
                jQuery("#header_room_map").removeClass('fixel_window_scroll');
                jQuery("#sign-in").css('display','');
                jQuery("#area_id_list_box").css('height',(window.innerHeight-250)+'px');
                jQuery("#info_room_list_box").css('height',(window.innerHeight-250)+'px');
           }
        });
        jQuery("#accordian h3").click(function()
        {
    		jQuery("#accordian ul#booking_cut_of_day ul").slideUp();
            console.log(111);
    		if(!jQuery(this).next().is(":visible"))
    		{
    			jQuery(this).next().slideDown();
    		}
    	});
        jQuery("#room_map_container").click(function(){
            jQuery("#check_show_toogle_info_room").removeAttr('checked');
            jQuery("#info_room_list_box").css('bottom','-100%');
            jQuery("#check_show_list_area").removeAttr('checked');
            jQuery("#area_id_list_box").css('bottom','-100%');
            
        });
    });
    jQuery("#in_date").datepicker();
    jQuery("#arrival_time").datepicker();
    jQuery("#departure_time").datepicker();
    jQuery("#repair_to").datepicker();
    var CURRENT_YEAR = <?php echo date('Y')?>;
	var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
	var CURRENT_DAY = <?php echo date('d')?>;
    var items_js = new Array();
    var room_information_js = new Array();
    var count_full_screen = 0;
    var count_toogle_info_room = 0;
    var rooms_info = new Array();
    rooms_info = [[|rooms_info|]];
    var room_levels = <?php echo String::array2js([[=room_levels=]]);?>;
    var area_id_list_js = <?php echo String::array2js([[=area_id_list=]]);?>;
    var room_infomation_top = 100;
    var room_infomation_left = 100;
    jQuery.mCustomScrollbar.defaults.scrollButtons.enable=true;
    jQuery.mCustomScrollbar.defaults.axis="y";
    jQuery("#room_infomation_main").mCustomScrollbar({theme:"dark"});
    jQuery("#area_id_list_box").mCustomScrollbar({theme:"dark"});
    jQuery("#info_room_list_box").mCustomScrollbar({theme:"dark"});
    items_js[[[|area_key|]]] = [[|items_js|]];
    jQuery("#area_id_list_box").css('height',(window.innerHeight-250)+'px');
    jQuery("#info_room_list_box").css('height',(window.innerHeight-250)+'px');
    function toogle_info_room()
    {
        console.log(111);
        if(jQuery("#check_show_toogle_info_room").attr('checked')=='checked')
        {
            jQuery("#check_show_toogle_info_room").removeAttr('checked');
            jQuery("#info_room_list_box").css('bottom','-100%');
        }
        else
        {
            jQuery("#check_show_toogle_info_room").attr('checked','checked');
            jQuery("#info_room_list_box").css('bottom','35px');
        }
        if(count_toogle_info_room==0)
        {
            console.log(111);
            count_toogle_info_room = 1;
            var in_date = jQuery("#in_date").val();
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
                    var text_reponse = xmlhttp.responseText;
                    var objs = jQuery.parseJSON(text_reponse);
                    //console.log(text_reponse);
                    //console.log(objs);
                    //forcecast_list
                    var forcecast_list_html = '';
                    forcecast_list_html += '<tr onclick="window.open(\'?page=arrival_list&date='+jQuery('#in_date').val()+'\')"><td>'+objs['forcecast_list']['arrival_today']+'</td><td>[[.arrival_today.]]</td></tr>';
                    forcecast_list_html += '<tr onclick="window.open(\'?page=checkin_list&date='+jQuery('#in_date').val()+'\')"><td>'+objs['forcecast_list']['checkin_today']+'</td><td>[[.checkin_today.]]</td></tr>';
                    forcecast_list_html += '<tr onclick="window.open(\'?page=arrival_list&date='+jQuery('#in_date').val()+'\')"><td>'+objs['forcecast_list']['dayused_today']+'</td><td>[[.dayused_today.]]</td></tr>';
                    forcecast_list_html += '<tr onclick="window.open(\'?page=departure_list&date='+jQuery('#in_date').val()+'\')"><td>'+objs['forcecast_list']['departure_today']+'</td><td>[[.departure_today.]]</td></tr>';
                    forcecast_list_html += '<tr onclick="window.open(\'?page=checkout_list&date='+jQuery('#in_date').val()+'\')"><td>'+objs['forcecast_list']['checkout_today']+'</td><td>[[.checkout_today.]]</td></tr>';
                    forcecast_list_html += '<tr onclick="window.open(\'?page=room_status_report&in_date='+jQuery('#in_date').val()+'\')"><td>'+objs['forcecast_list']['occ_and_arr_today']+'</td><td>[[.occ_and_arr_today.]]</td></tr>';
                    jQuery("#forcecast_list").append(forcecast_list_html);
                    
                    //extra_bed_baby_cot
                    var extra_bed_baby_cot_html = '';
                    for(var e_b in objs['extra_bed_baby_cot'])
                    {
                        extra_bed_baby_cot_html += '<tr>';
                        extra_bed_baby_cot_html += '<td>'+objs['extra_bed_baby_cot'][e_b]['name']+'</td>';
                        extra_bed_baby_cot_html += '<td>'+objs['extra_bed_baby_cot'][e_b]['total_quantity']+'</td>';
                        extra_bed_baby_cot_html += '<td>'+objs['extra_bed_baby_cot'][e_b]['quantity']+'</td>';
                        extra_bed_baby_cot_html += '</tr>';
                    }
                    jQuery("#extra_bed_baby_cot").append(extra_bed_baby_cot_html);
                    
                    //booking_without_room
                    var books_without_room_html = '';
                    var temp = '';
                    for(var b_r in objs['books_without_room'])
                    {
                        if(temp!=objs['books_without_room'][b_r]['reservation_id'])
                        {
                            temp=objs['books_without_room'][b_r]['reservation_id'];
                            books_without_room_html += '<tr>';
                            books_without_room_html += '<td><a href="?page=reservation&cmd=edit&id='+temp+'"><strong>'+temp+' - '+objs['books_without_room'][b_r]['booking_code']+'</strong></a></td>';
                            books_without_room_html += '<td><a href="?page=reservation&cmd=asign_room&id='+temp+'"><input name="asign_room" type="button" id="asign_room" value="[[.assign.]]"  /> </a></td>';
                            books_without_room_html += '</tr>';
                        }
                        books_without_room_html += '<tr>';
                        books_without_room_html += '<td>'+objs['books_without_room'][b_r]['room_level']+'</td>';
                        books_without_room_html += '<td><span class="reservation-list-item">('+objs['books_without_room'][b_r]['adult']+')<img src="packages/core/skins/default/images/buttons/adult.png" width="6"></span></td>';
                        books_without_room_html += '</tr>';
                    }
                    jQuery("#booking_without_room").append(books_without_room_html);
                    
                    //booking_cut_of_day
                    var booking_cut_of_day_html = '';
                    for(var b_c in objs['booking_cut_of_day'])
                    {
                        booking_cut_of_day_html += '<li>';
                        booking_cut_of_day_html += '<h3><span></span>[[.re_code.]] '+objs['booking_cut_of_day'][b_c]['reservation_id']+' | [[.room.]]: '+objs['booking_cut_of_day'][b_c]['room']+' | <a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+'">[[.check.]]</a></h3>';
                			booking_cut_of_day_html += '<ul>';
                                booking_cut_of_day_html += '<li><a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+'">[[.room_level.]]: '+objs['booking_cut_of_day'][b_c]['room_level']+'</a></li>';
                				booking_cut_of_day_html += '<li><a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+'">[[.booking_code.]]: '+objs['booking_cut_of_day'][b_c]['booking_code']+'</a></li>';
                				booking_cut_of_day_html += '<li><a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+']">[[.customer_name.]]: '+objs['booking_cut_of_day'][b_c]['customer_name']+'</a></li>';
                				booking_cut_of_day_html += '<li><a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+'">[[.time_in.]]: '+objs['booking_cut_of_day'][b_c]['time_in']+'</a></li>';
                				booking_cut_of_day_html += '<li><a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+'">[[.time_out.]]: '+objs['booking_cut_of_day'][b_c]['time_out']+'</a></li>';
                				booking_cut_of_day_html += '<li><a href="?page=reservation&cmd=edit&id='+objs['booking_cut_of_day'][b_c]['reservation_id']+'&r_r_id='+objs['booking_cut_of_day'][b_c]['id']+'">[[.creater.]]: '+objs['booking_cut_of_day'][b_c]['full_name']+'</a></li>';
                			booking_cut_of_day_html += '</ul>';
                        booking_cut_of_day_html += '</li>';
                    }
                    jQuery("#booking_cut_of_day").append(booking_cut_of_day_html);
                    
                    //description_status
                    var total_room = to_numeric([[|count_rooms_info|]]);
                    objs['description_status']['available'] = to_numeric(objs['description_status']['available']) + total_room - to_numeric(objs['description_status']['total'])
                    var description_status_html = '';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="AVAILABLE" onclick="fun_filter_status(\'AVAILABLE\');"></td><td>[[.available.]]</td><td>'+objs['description_status']['available']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="BOOKED" onclick="fun_filter_status(\'BOOKED\');"></td><td>[[.booked.]]</td><td>'+objs['description_status']['booked']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="OVERDUE_BOOKED" onclick="fun_filter_status(\'OVERDUE_BOOKED\');"></td><td>[[.overdure_booked.]]</td><td>'+objs['description_status']['overdure_booked']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="OCCUPIED" onclick="fun_filter_status(\'OCCUPIED\');"></td><td>[[.occupied.]]</td><td>'+objs['description_status']['occupied']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="CHANGE_IN_DATE" onclick="fun_filter_status(\'CHANGE_IN_DATE\');"></td><td>[[.change_in_date.]]</td><td>'+objs['description_status']['change_in_date']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="DAYUSED" onclick="fun_filter_status(\'DAYUSED\');"></td><td>[[.dayused.]]</td><td>'+objs['description_status']['dayused']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="OVERDUE" onclick="fun_filter_status(\'OVERDUE\');"></td><td>[[.overdue.]]</td><td>'+objs['description_status']['overdue']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="EXPECTED_CHECKOUT" onclick="fun_filter_status(\'EXPECTED_CHECKOUT\');"></td><td>[[.expected_checkout.]]</td><td>'+objs['description_status']['expected_checkout']+'</td></tr>';
                    description_status_html += '<tr><td><div style="padding: 10px; cursor: pointer;" class="REPAIR" onclick="fun_filter_status(\'REPAIR\');"></td><td>[[.repair.]]</td><td>'+objs['description_status']['repair']+'</td></tr>';
                    jQuery("#description_status").append(description_status_html);
                }
                
            }
            xmlhttp.open("GET","db_room_map.php?data=load_info_room&in_date="+in_date,true);
            xmlhttp.send();
        }
    }
    function fun_filter_status(status)
    {
        if(status=='ALL')
            jQuery(".div_items").css("display","");
        else
        {
            jQuery(".div_items").css("display","none");
            jQuery(".status_"+status).css("display","");
        }
    }
    function load_area(area_id)
    {
        if(jQuery("#check_box_area_"+area_id).attr('checked')=='checked')
        {
            
        }
        else
        {
            var in_date = jQuery("#in_date").val();
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
                    var text_reponse = xmlhttp.responseText;
                    var objs = jQuery.parseJSON(text_reponse);
                    //console.log(objs);
                    var items_js_aj = objs['items'];
                    items_js[area_id] = objs['items']; 
                    var f = '';
                    f += '<div id="area_key_'+area_id+'" style="width: 98%; margin: 10px auto; clear: both; height: auto; padding-top: 0px; padding-bottom: 5px; border: 1px solid #EEEEEE; box-shadow: 0px 0px 3px #EEEEEE;">';
                    f += '<div style="line-height: 25px; text-align: center; background: #EEEEEE; color: #171717; padding: 5px 5px 5px 30px; margin: 0px auto;">'+area_id_list_js[area_id]['code']+'</div>';
                    f += '<table style="width: 99%; margin: 0.5% auto;" border="1" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE">';
                    for(var i in items_js_aj)
                    {
                        f += '<tr>';
                        f += '<td style="width: 55px; text-align: center; font-weight: bold; text-transform: uppercase; color: #555555; background: #E1E1E1;">'+items_js_aj[i]['name']+'</td>'
                        f += '<td>';
                        var child_items_js = items_js_aj[i]['child'];
                        for(var j in child_items_js)
                        {
                            f += '<div class="div_items status_'+child_items_js[j]['status']+'">';
                            f += '<div class="div_items_title">';
                            f += '<span style="color: #00B2F9; font-size: 11px; font-weight: bold;">'+child_items_js[j]['name']+'</span> - <i style=" font-size: 11px;">'+child_items_js[j]['type_name']+'</i>';
                            f += '</div>';
                            f += '<input type="hidden" value="'+child_items_js[j]['id']+'" id="'+child_items_js[j]['id']+'" />';
                            f += '<div class="div_items_nav">';
                            
                            if(child_items_js[j]['active'].length>0)
                            {
                                var active_child_items_js = child_items_js[j]['active'];
                                for(var k in active_child_items_js)
                                {
                                    if(active_child_items_js[k]['key']!=child_items_js[j]['is_active'])
                                    {
                                        var color = '';
                                        var time_to_day = to_numeric(<?php echo time(); ?>);
                                        if(active_child_items_js[k]['reservation_room_status']=='CHECKOUT')
                                            color = '#FF66FF';
                                        else if(active_child_items_js[k]['reservation_room_status']=='BOOKED' && to_numeric(active_child_items_js[k]['time_in'])>time_to_day)
                                            color = '#00A855';
                                        else if(active_child_items_js[k]['reservation_room_status']=='BOOKED' && to_numeric(active_child_items_js[k]['time_in'])<=time_to_day)
                                            color = '#0054a2';
                                        f += '<a target="_blank" href="?page=reservation&cmd=edit&id='+active_child_items_js[k]['reservation_id']+'&r_r_id='+active_child_items_js[k]['reservation_room_id']+'" title="[[.code.]]: '+active_child_items_js[k]['reservation_id']+' [[.status.]]: '+active_child_items_js[k]['reservation_room_status']+' [[.price.]]: '+number_format(active_child_items_js[k]['change_price'])+'" style="width: 10px; height: 10px; float: left; margin: 2.5px 5px; background: '+color+';" ></a>';
                                    }
                                }
                            }
                            
                            f += '</div>';
                            
                            var note = '';
                            
                            if(child_items_js[j]['active'][child_items_js[j]['is_active']]!=undefined && child_items_js[j]['active'][child_items_js[j]['is_active']]['reservation_room_note']!=null)
                                note = '[[.room_note.]]: '+child_items_js[j]['active'][child_items_js[j]['is_active']]['reservation_room_note']+'';
                            
                            if(child_items_js[j]['hk_note']!=null)
                                note = '[[.hk_note.]]: '+child_items_js[j]['hk_note']+'';
                            
                            f += '<div id="room_'+child_items_js[j]['id']+'" onclick="select_rooms('+child_items_js[j]['id']+',document.RoomMapSmartForm);show_room_infomation_main(\''+area_id+'\',\''+child_items_js[j]['floor']+'\',\''+child_items_js[j]['id']+'\');" tabindex="<?php echo $tab_index_room++; ?>" class="div_items_content '+child_items_js[j]['status']+'" title="'+note+'">';
                            
                            if(child_items_js[j]['house_status']=='REPAIR' || child_items_js[j]['house_status']=='HOUSEUSE')
                            {
                                if(child_items_js[j]['start_date'].split(' ')[1]==child_items_js[j]['end_date'].split(' ')[1])
                                    f += '<span style="color: #003399; font-weight: bold; font-size: 11px;"><u>'+child_items_js[j]['start_date'].split(' ')[0]+'-'+child_items_js[j]['end_date'].split(' ')[0]+'</u></span><br/>';
                                else
                                    f += '<span style="color: #003399; font-weight: bold; font-size: 11px;"><u>'+child_items_js[j]['start_date'].split(' ')[1].substr(0,5)+'-'+child_items_js[j]['end_date'].split(' ')[1].substr(0,5)+'</u></span><br/>';
                            }
                            
                            if(child_items_js[j]['active'][child_items_js[j]['is_active']]!=undefined)
                                f += '<span style="color: #003399; font-weight: bold; font-size: 11px;"><u>'+child_items_js[j]['active'][child_items_js[j]['is_active']]['arrival_time'].substr(0,5)+'-'+child_items_js[j]['active'][child_items_js[j]['is_active']]['departure_time'].substr(0,5)+'</u></span><br/>';
                            
                            if(child_items_js[j]['house_status']!=null)
                            {
                                f += '<span id="house_status_'+child_items_js[j]['id']+'" style="color: red; font-size: 11px;">'+child_items_js[j]['house_status']+'</span>';
                                f += '<br />';
                            }
                            
                            if(child_items_js[j]['active'][child_items_js[j]['is_active']]!=undefined)
                            {
                                f += '<span style="font-size: 11px; background: '+child_items_js[j]['active'][child_items_js[j]['is_active']]['reservation_color']+'"><i>'+child_items_js[j]['active'][child_items_js[j]['is_active']]['customer_name']+'</i></span><br/>';
                                f += '<span style="font-size: 11px;">'+child_items_js[j]['active'][child_items_js[j]['is_active']]['traveller_name']+'</span><br/>';
                                
                                if(child_items_js[j]['active'][child_items_js[j]['is_active']]['reservation_room_note']!=null)
                                    f += '<span style="color: red;font-size: 11px;font-weight: bold;" title="[[.room_note.]]: '+child_items_js[j]['active'][child_items_js[j]['is_active']]['reservation_room_note']+'">*</span><br/>';
                            }
                            if(child_items_js[j]['hk_note']!=null)
                            {
                                f += '<span style="color: red;font-size: 11px;font-weight: bold;" title="[[.hk_note.]]: '+child_items_js[j]['hk_note']+'">*</span><br/>'
                            }
                            f += '</div>';
                            f += '<input name="room['+child_items_js[j]['id']+'][id]" type="checkbox" id="room_id_'+child_items_js[j]['id']+'" value="'+child_items_js[j]['id']+'" style="display: none;" />';
                            f += '</div>';
                        }
                        f += '</td>';
                        f += '</tr>';
                    }
                    f += '</table>';
                    f += '</div>';
                    jQuery("#room_map_container").append(f);
                    jQuery("#check_box_area_"+area_id).attr('checked','checked');
                }
            }
            xmlhttp.open("GET","db_room_map.php?data=load_area_room&area_id="+area_id+"&in_date="+in_date,true);
            xmlhttp.send();
        }
        if(jQuery("#view_one_area").attr('checked')=='checked')
        {
            for(var a_l in area_id_list_js)
            {
                if(area_id_list_js[a_l]['id']!=area_id)
                {
                    jQuery("#area_key_"+area_id_list_js[a_l]['id']).css('display','none');
                    jQuery("#img_"+area_id_list_js[a_l]['id']).css('display','none');
                }
            }
            jQuery("#area_key_"+area_id).css('display','');
            jQuery("#img_"+area_id).css('display','');
        }
        else
        {
            jQuery("#area_key_"+area_id).css('display','');
            jQuery("#img_"+area_id).css('display','');
        }
        return false;
    }
    function show_room_infomation_main(area,floor,room_id)
    {
        console.log(area+","+floor+","+room_id);
        console.log(items_js);
        jQuery("#mCSB_1_container").html('');
        var item = items_js[area][floor];
        var room = item['child'][room_id];
        if(room_information_js[room_id]==undefined)
        {
            room_information_js[room_id] = new Array();
            room_information_js[room_id]['id'] = room_id;
            room_information_js[room_id]['content'] = '<table cellpadding="5" cellspacing="0">';
            room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.room_name.]]: </td><td style="vertical-align: top;">'+room['name']+'</td></tr>';
            room_information_js[room_id]['content'] += '';
            if(room['active'][room['is_active']]!=undefined && (room['status']=='BOOKED' || room['status']=='OVERDUE_BOOKED' || room['status']=='OCCUPIED' || room['status']=='CHANGE_IN_DATE' || room['status']=='DAYUSED' || room['status']=='OVERDUE' || room['status']=='EXPECTED_CHECKOUT'))
            {
                <?php if(User::can_view($this->get_module_id('CheckIn'),ANY_CATEGORY)){ ?>
                //xem chi tiet
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.bill_number.]]: '+room['active'][room['is_active']]['reservation_room_id']+'</td><td style="vertical-align: top;"><a href="?page=reservation&cmd=edit&id='+room['active'][room['is_active']]['reservation_id']+'&r_r_id='+room['active'][room['is_active']]['reservation_room_id']+'">[[.view_detail.]]</a></td></tr>';
                <?php }else{ ?>
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.bill_number.]]: '+room['active'][room['is_active']]['reservation_id']+'</td><td style="vertical-align: top;"></td></tr>';
                <?php } ?>
                <?php if(USER::can_add($this->get_module_id('Reservation'),ANY_CATEGORY)){ ?>
                 //them khach bang excel
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;"></td><td style="vertical-align: top;"><a target="_blank" href="?page=reservation&cmd=import_traveller&r_id='+room['active'][room['is_active']]['reservation_id']+'&r_r_id='+room['active'][room['is_active']]['reservation_room_id']+'">[[.add_traveler.]]</a></td></tr>';
                <?php } ?>
                <?php if(USER::can_add($this->get_module_id('ExtraServiceInvoice'),ANY_CATEGORY)){ ?>
                 //them dich vu
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;"></td><td style="vertical-align: top;"><a target="_blank" href="?page=extra_service_invoice&cmd=add&reservation_room_id='+room['active'][room['is_active']]['reservation_room_id']+'">[[.add_extra_service_invoice.]]</a></td></tr>';
                <?php } ?>
                <?php if(USER::can_add($this->get_module_id('UpdateTraveller'),ANY_CATEGORY)){ ?>
                 //them khach
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;"></td><td style="vertical-align: top;"><a href="#" onClick="openWindowUrl(\'http:<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=add_traveller&rr_id='+room['active'][room['is_active']]['reservation_room_id']+'&r_id='+room['active'][room['is_active']]['reservation_id']+'\',Array(\'add_traveller_'+room['active'][room['is_active']]['reservation_room_id']+'\',\'[[.list_traveller.]]\',\'20\',\'110\',\'1100\',\'570\'));closeAllWindows();"> [[.list_guest.]]</a></td></tr>';
                <?php } ?>
                if(room['status']!='BOOKED' && room['status']!='OVERDUE_BOOKED')
                {
                    <?php if(USER::can_add($this->get_module_id('MinibarInvoice'),ANY_CATEGORY)){ ?>
                     //minibar
                    room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;"></td><td style="vertical-align: top;"><a target="_blank" href="?page=?page=minibar_invoice&cmd=add&reservation_room_id='+room['active'][room['is_active']]['reservation_room_id']+'">[[.add_minibar_invoice.]]</a></td></tr>';
                    <?php } ?>
                    <?php if(USER::can_add($this->get_module_id('LaundryInvoice'),ANY_CATEGORY)){ ?>
                     //giat la
                    room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;"></td><td style="vertical-align: top;"><a target="_blank" href="?page=laundry_invoice&cmd=add&reservation_room_id='+room['active'][room['is_active']]['reservation_room_id']+'">[[.add_laundry_invoice.]]</a></td></tr>';
                    <?php } ?>
                    <?php if(USER::can_add($this->get_module_id('EquipmentInvoice'),ANY_CATEGORY)){ ?>
                     //den bu
                    room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;"></td><td style="vertical-align: top;"><a target="_blank" href="?page=equipment_invoice&cmd=add&reservation_room_id='+room['active'][room['is_active']]['reservation_room_id']+'">[[.add_compensation_invoice.]]</a></td></tr>';
                    <?php } ?>
                }
                 //gia
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.creater.]]: </td><td style="vertical-align: top;">'+room['active'][room['is_active']]['room_user_id']+'</td></tr>';
                 //tinh trang
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.status.]]: </td><td style="vertical-align: top;">'+room['active'][room['is_active']]['status']+' ('+room['active'][room['is_active']]['adult']+' [[.adult.]])</td></tr>';
                 //gia
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.price.]]: </td><td style="vertical-align: top;">'+room['active'][room['is_active']]['price']+'</td></tr>';
                 //cong ty
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.customer_name.]]: </td><td style="vertical-align: top;">'+room['active'][room['is_active']]['customer_name']+'</td></tr>';
                 //time
                room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">'+room['active'][room['is_active']]['arr_time_in']+' - '+room['active'][room['is_active']]['arr_time_out']+'</td></tr>';
                 //list traveller
                room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">[[.list_traveller.]]</td></tr>';
                for(var l_t in room['active'][room['is_active']]['list_traveller'])
                {
                    room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">'+room['active'][room['is_active']]['list_traveller'][l_t]['traveller_name']+': '+room['active'][room['is_active']]['list_traveller'][l_t]['arr_time_in']+' - '+room['active'][room['is_active']]['list_traveller'][l_t]['arr_time_out']+'</td></tr>';
                }
                 //ghi chu doan
                if(room['active'][room['is_active']]['reservation_note']!=null) 
                    room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">[[.group_note.]]: '+room['active'][room['is_active']]['reservation_note']+'</td></tr>';
                else
                    room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">[[.group_note.]]: </td></tr>';
                 //ghi chu phong 
                <?php if(User::can_edit(false,ANY_CATEGORY)){ ?> 
                if(room['active'][room['is_active']]['reservation_room_note']!=null) 
                    room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">[[.room_note.]]: <input name="room_note_'+room['active'][room['is_active']]['reservation_id']+'" type="text" style="width: 300px;" rows="3" value="'+room['active'][room['is_active']]['reservation_room_note']+'" /><input type="submit" value="Change" name="change_room_note_'+room['id']+'"></td></tr>';
                else
                    room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">[[.room_note.]]: <input name="room_note_'+room['active'][room['is_active']]['reservation_id']+'" type="text" style="width: 300px;" rows="3" value="" /><input type="submit" value="Change" name="change_room_note_'+room['id']+'"></td></tr>';
                <?php } ?>
                select_option = '<option value="">READY</option><option value="SHOWROOM">SHOWROOM</option><option value="CLEAN">CLEAN</option><option value="DIRTY">DIRTY</option><option value="HOUSEUSE">HOUSEUSE</option>';
            }
            else
            {
                room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.price.]]: </td><td style="vertical-align: top;">'+room['price']+'</td></tr>';
                
                select_option = '<option value="">READY</option><option value="SHOWROOM">SHOWROOM</option><option value="CLEAN">CLEAN</option><option value="DIRTY">DIRTY</option><option value="HOUSEUSE">HOUSEUSE</option><option value="REPAIR">REPAIR</option>';
            }
            room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.for_housekeeping.]]</td><td style="vertical-align: top;"></td></tr>'
            room_information_js[room_id]['content'] += '<tr><td colspan="2" style="vertical-align: top;">[[.note.]]<br /><textarea name="note" style="width: 300px;" rows="3"></textarea></td></tr>';
            <?php if(User::can_edit('1982','1010303000000000000')){ ?>
            room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.house_status.]]</td><td style="vertical-align: top;"><select id="house_status" name="house_status" onclick="">'+select_option+'</select><input type="submit" value="Change" class="hk-status-button" onclick="return check_house_status_rooms();" /></td></tr>';
            room_information_js[room_id]['content'] += '<tr><td style="vertical-align: top;">[[.select_date.]] [[.to.]]</td><td style="vertical-align: top;"><input  name="repair_to" type="text" id="repair_to" class="date-input" style="width:90px;" /></td></tr>';
            <?php } ?>
            room_information_js[room_id]['content'] += '</table>';
            
        }
        jQuery("#mCSB_1_container").html(room_information_js[room_id]['content']);
        
        var element_offset = jQuery("#room_"+room_id).offset();
        room_infomation_top = element_offset.top + 50;
        room_infomation_left = element_offset.left + 100;
        if((window.innerWidth-room_infomation_left)<400)
        {
            room_infomation_left  = room_infomation_left - (120+350);
        }
        toogle_room_infomation('show',room_infomation_top,room_infomation_left);
        jQuery('#repair_to').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4'});
        jQuery("#ui-datepicker-div").css('z-index','3000');
        return false;
    }
    
    function check_house_status_rooms()
    {
        var check=true;
        if(jQuery("#house_status").val()=='REPAIR')
        {
            var arr_room = jQuery("#room_ids").val().split(",");
            for(var i in arr_room)
            {
                status_room = jQuery("#status_room_"+arr_room[i]).val();
                if(status_room!='AVAILABLE')
                    check=false;
            }
        }
        if(check==false)
            alert('[[.not_select_repair_list_room.]]');
        
        return check;
    }
    
    function toogle_room_infomation(event_box,top,left)
    {
        if(event_box=='min')
        {
            jQuery("#room_infomation").css('height','30px');
            jQuery("#room_infomation").css('position','fixed');
            jQuery("#room_infomation").css('top',(window.innerHeight - 85)+'px');
            jQuery("#room_infomation").css('left',left+'px');
        }
        else 
        {
            if(event_box=='close')
            {
                jQuery("#room_infomation").css('display','none');
            }
            else
            {
                jQuery("#room_infomation").css('display','');
                jQuery("#room_infomation").css('height','300px');
                jQuery("#room_infomation").css('position','absolute');
                jQuery("#room_infomation").css('top',room_infomation_top+'px');
                jQuery("#room_infomation").css('left',room_infomation_left+'px');
            }
        }
    }
    function full_screen_room_map()
    {
        if(count_full_screen==0)
        {
            count_full_screen = 1;
            jQuery("#testRibbon").css('display','none');
            jQuery("#sign-in").css('display','none');
            jQuery("#chang_language").css('display','none');
        }
        else
        {
            count_full_screen = 0;
            jQuery("#testRibbon").css('display','');
            jQuery("#sign-in").css('display','');
            jQuery("#chang_language").css('display','');
        }
    }
    
    room_classes = {};
    function select_rooms(id,form)
    {
    	var rooms = Array();
    	if(form.room_ids.value != '')
    	{
    		rooms = form.room_ids.value.split(',');
    	}
    	var e = (window.event) ? window.event : evt;
    	if(e.shiftKey && rooms.length>0)
    	{
    	   console.log(rooms.length);
    		var i = 0, j=0;
    		for(i in rooms_info)
    		{
    			if(rooms_info[i].id==rooms[rooms.length-1])
    			{
    				break;
    			}
    		}
    		for(j in rooms_info)
    		{
    			if(rooms_info[j].id==id)
    			{
    				break;
    			}
    		}
    		var start = Math.min(i,j);
    		var end = Math.max(i,j);
    		for(i = start;i<=end;i++)
    		{
    			if(typeof(rooms_info[i])!='undefined')
    			{
    				var k = rooms_info[i].id;
    				var new_selection = true;
    				for(j in rooms)
    				{
    					if(rooms[j]==k)
    					{
    						new_selection = false;
    					}
    				}
    				if(new_selection && typeof($('room_'+k))!='undefined' && $('room_'+k)!=null)
    				{
    					room_classes[k] = $('room_'+k).className;
    					$('room_'+k).className += " select_rooms";
    					rooms.push(k);
    				}
    			}
    		}
    	}
    	else
    	{
    		if(!e.ctrlKey)
    		{
    			for(var i in rooms)
    			{
    				if(document.getElementById('room_'+rooms[i]))
    				{
    					document.getElementById('room_'+rooms[i]).className = room_classes[rooms[i]];
    				}
    			}
    			rooms = Array();
    		}
    		var changed = false;
    		for(var i in rooms)
    		{
    			if(rooms[i]==id)
    			{
    				document.getElementById('room_'+id).className = room_classes[id];
    				rooms.splice(i,1);
    				changed = true;
    				break;
    			}
    		}
    		if(!changed)
    		{
    			room_classes[id] = document.getElementById('room_'+id).className;
    			document.getElementById('room_'+id).className += " select_rooms";
    			rooms.push(id);
    		}
    	}
    	form.room_ids.value = rooms.join(',');
    }
    function buildReservationUrl(type)
    {
		if(type=='RFA')
        {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add'));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		} 
        else if(type=='RFW')
        {
            console.log('checkin');
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN','reservation_type_id'=>4));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		} 
        else 
        {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN'));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		}
	}
    function get_query_string()
	{
		var query_string = '';
		if(document.RoomMapSmartForm.room_ids.value!='')
		{
			var rooms = document.RoomMapSmartForm.room_ids.value.split(',');
            
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
			query_string += rooms[i]+','+'[[|day|]]/[[|month|]]/[[|year|]]'+','+'[[|end_day|]]/[[|end_month|]]/[[|end_year|]]';
		}
		for(var j in room_levels)
        {
		  query_string += '&room_prices['+room_levels[j]['id']+']='+room_levels[j]['price'];
		}
		return query_string;
	}
    function canCheckin()
    {
        if(document.RoomMapSmartForm.room_ids.value!='')
		{
			var rooms = document.RoomMapSmartForm.room_ids.value.split(',');
		}
        else
		{
			var rooms = [];
		}
        var can_in = true;
        var dirty_rooms = '';
        for(var i in rooms)
		{
		    if(jQuery('#house_status_'+rooms[i]).html() == 'DIRTY')
            {
                can_in = false;
                break; 
            }	
		}
        if(!can_in)
        {
            alert('[[.room_is_dirty.]]');
            return false;
        }
        else
            return true;
    }
    function toogle_list_area()
    {
        if(jQuery("#check_show_list_area").attr('checked')=='checked')
        {
            jQuery("#check_show_list_area").removeAttr('checked');
            jQuery("#area_id_list_box").css('bottom','-100%');
        }
        else
        {
            jQuery("#check_show_list_area").attr('checked','checked');
            jQuery("#area_id_list_box").css('bottom','35px');
        }
    }
    /** di chuyen phan tu co Class=dragclass **/
    if(document.getElementById)
    {
        (function(){
            if (window.opera)
            {
                document.write("<input type='hidden' id='Q' value=' '>");
            }
            var n = 500;
            var dragok = false;
            var y,x,d,dy,dx;
            function move(e)
            {
                if (!e) e = window.event;
                if (dragok)
                {
                    d.style.left = dx + e.clientX - x + "px";
                    d.style.top  = dy + e.clientY - y + "px";
                    return false;
                }
            }
            
            function down(e)
            {
                if (!e) e = window.event;
                var temp = (typeof e.target != "undefined")?e.target:e.srcElement;
                if (temp.tagName != "HTML"|"BODY" && temp.className != "dragclass")
                {
                    temp = (typeof temp.parentNode != "undefined")?temp.parentNode:temp.parentElement;
                }
                if (temp.className == "dragclass")
                {
                    if (window.opera)
                    {
                        document.getElementById("Q").focus();
                    }
                    dragok = true;
                    temp.style.zIndex = n++;
                    d = temp;
                    dx = parseInt(temp.style.left+500);
                    dy = parseInt(temp.style.top+100);
                    x = e.clientX;
                    y = e.clientY;
                    document.onmousemove = move;
                    return false;
                }
            }
            
            function up()
            {
                dragok = false;
                document.onmousemove = null;
            }
            document.onmousedown = down;
            document.onmouseup = up;
        })();
        jQuery("#repair_to").datepicker();
        jQuery("#ui-datepicker-div").css('z-index','3000');
    }
    /** end Class dragclass **/
    
    function CheckValidate()
    {
        if($('arrival_time').value=='')
        {
            alert('[[.you_have_to_input_arrival_time.]]');
            return false;
        }
        if($('departure_time').value=='')
        {
            alert('[[.you_have_to_input_departure_time.]]');
            return false;
        }
        
        window.location='<?php echo Url::build('reservation',array('cmd'=>'check_availability'));?>&arrival_time='+$('arrival_time').value+'&departure_time='+$('departure_time').value+'&room_level_id='+$('room_level_id').value;
    }
    function changevalue()
    {
        var startDate = parseDate($('arrival_time').value).getTime();
        var endDate = parseDate($('arrival_time').value).getTime();
        if (startDate > endDate){
            $('arrival_time').value = $('departure_time').value;
        }   
    }
    function changefromday()
    {
        var startDate = parseDate($('arrival_time').value).getTime();
        var endDate = parseDate($('arrival_time').value).getTime();
        if (startDate > endDate){
            $('arrival_time').value = $('departure_time').value;
        }
        
    }
    function parseDate(str) 
    {
        var mdy = str.split('/');
        return new Date(mdy[2], mdy[1], mdy[0]);
    }
    function buildReservationSearch()
	{
		if(jQuery('#code').val()!='')
		{
			url = '?page=reservation';
			url+='&code='+jQuery('#code').val();
		}else{
			url = '?page=reservation';	
		}
        if(jQuery('#number_room').val()!='')
		{
			url+='&room_id='+jQuery('#number_room').val();
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
        if(jQuery('#booker').val()!='')
		{
			url+='&booker='+jQuery('#booker').val();
		}	
		window.open(url)
	}
</script>