<style>
	.checkin-today:hover{
		text-decoration:underline;
		cursor:pointer;	
	}
		.simple-layout-middle{
		width:100%;	
	}
    @media print
    {
        #room_map_left_utils{display:none}
        #full_screen_button { display: none;}
        #lt1 { display: none;}
        #lt2 { display: none;}
        #lt3 { display: none;}
        #lt4 { display: none;}
    }
    *{
        margin: 0px;
        padding: 0px;
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
    a.CLOSE, a.CLOSE:hover{
        background-color:#33CC00 !important;
    	border:4px solid #80FFFF;	
    	border-left:4px solid #80FFFF;
    	border-right:4px solid #80FFFF; 
    }
</style>
<script type="text/javascript">
	room_levels = <?php echo String::array2js([[=room_levels=]]);?>;
</script>
<form method="post" name="HotelRoomMapForm">
<div id="room_map">
<table width="1200" border="0" cellpadding="0" cellspacing="0" class="room-map-table-bound">
<tr>
<td class="calendar-bound">
	<!--IF:birth_date_cond([[=birth_date_arr=]])-->
		<marquee style="width:100%;" onMouseOut="this.start();" onMouseOver="this.stop();" scrollamount="3">
        	<!--LIST:birth_date_arr--><span style="color:#FF3300;float:left;"><!--IF:cond([[=birth_date_arr.i=]]>1)-->,<!--/IF:cond-->
            <!--IF:cond_bifth([[=birth_date_arr.count=]]==0)-->
            	<i class="fa fa-gift w3-text-red" style="font-size: 20px;"></i> [[.happy_birth_day_to.]]
            <!--ELSE-->    
                <i class="fa fa-gift w3-text-red" style="font-size: 20px;"></i> [[.one_day_left_to_birthday.]]
            <!--/IF:cond_bifth-->
             <span class="w3-text-indigo">[[|birth_date_arr.name|]] - P.[[|birth_date_arr.room_name|]] ([[|birth_date_arr.birth_date|]])</span></span><!--/LIST:birth_date_arr-->
         </marquee>
	<hr size="1" color="#9DC9FF">	
	<!--/IF:birth_date_cond-->
	<table width="100%" cellspacing="0" cellpadding="3">
	<tr valign="top">
	<td align="left">
		<div id="room_map_left_utils" style="width:200px;">
			<fieldset><legend class="title"><b>[[.date_viewing.]]</b></legend>
			<table border="0" id="check_availability_table">
            <tr>
            	<td style="width: 100px;">[[.choose_date.]]:</td>
            	<td> 
                <input name="check_submit_date" type="checkbox" id="check_submit_date" style="display: none;" checked="checked" />
                <input name="in_date" type="text" id="in_date" class="date-input" style="width: 85px; height: 25px; border: 1px solid lightgray;" onchange="jQuery('#check_submit_date').removeAttr('checked');HotelRoomMapForm.submit();"/>
                </td>
            </tr>
		  </table>
		  </fieldset><br />
          <fieldset><legend class="title"><b>[[.check_availability.]]</b></legend> 
			<table id="check_availability_table">
			  <tr>
				<td style="width: 100px;">[[.arrival_date.]]:</td>
				<td><input name="arrival_time" type="text" id="arrival_time" class="date-input"  readonly="readonly" onchange="changevalue();" style="width: 85px; height: 23px; border: 1px solid lightgray;" /></td>
			  </tr>
			  <tr>
				<td style="width: 100px;">[[.departure_date.]]:</td>
				<td><input name="departure_time" type="text" id="departure_time" class="date-input"  readonly="readonly" onchange="changefromday();" style="width: 85px; height: 23px; border: 1px solid lightgray; margin-top: 2px;" /></td>
			  </tr>
			
			  <tr>
				<td>[[.room_type.]]:</td>
				<td><select name="room_level_id" id="room_level_id" style="width: 85px; height: 23px; border: 1px solid lightgray; margin-top: 2px;"></select></td>
			  </tr>
				<tr><td colspan="2" style="padding-top: 5px; text-align: right;">
				<a class="w3-btn w3-gray" href="#" type="button" value="" onClick="CheckValidate();" style="text-decoration: none; width: 85px;">[[.search.]]</a>
			     
            </td></tr>
            
			</table>
		</fieldset> <br>  
 
          <fieldset>
			<legend class="title">[[.forcecast.]]</legend>
				<table border="0" cellpadding="2">
				  	<tr class="checkin-today" onClick="window.open('?page=arrival_list&date='+jQuery('#in_date').val());">
						<td class="w3-hover-text-red" align="left"><b>[[|arr_room|]]</b> [[.check_in_today.]]</td>
					</tr>
                    <tr class="checkin-today" onClick="window.open('?page=arrival_list&room_status=ACTUAL_CHECKIN&date='+jQuery('#in_date').val());">
						<td class="w3-hover-text-red" style="height: 19px;" align="left"><b>[[|autual_checkin_room|]]</b> [[.actual_checkin_today.]]</td>
					</tr>
                    <tr class="checkin-today" onClick="window.open('?page=arrival_list&room_status=DAYUSE&date='+jQuery('#in_date').val());">
						<td class="w3-hover-text-red" style="height: 19px;" align="left">(<b>[[|dayused_room|]]</b> [[.total_dayused.]])</td>
					</tr>
				 	<tr class="checkin-today" onClick="window.open('?page=departure_list');">
						<td class="w3-hover-text-red" style="height: 19px;" align="left"><b>[[|dep_room|]]</b> [[.check_out_today.]]</td>
					</tr>
                    <tr class="checkin-today" onClick="window.open('?page=departure_list&room_status=ACTUAL_CHECKOUT&date='+jQuery('#in_date').val());">
						<td class="w3-hover-text-red" style="height: 19px;" align="left"><b>[[|autual_checkout_room|]]</b> [[.actual_checkout_today.]]</td>
					</tr>
				 	<tr >
						<td style="height: 19px;" align="left"><b><?php echo [[=arr_room=]]+[[=occ_room=]];?></b> [[.total_occ_and_arr.]]</td>
					</tr>
                    <tr class="checkin-today" onClick="window.open('?page=occupancy_forecast_report');">
						<td class="w3-hover-text-red" style="height: 19px;" align="left"><b>[[.occupancy_forecast_report.]]</b></td>
					</tr>
			   </table>
			</fieldset><br />
			<fieldset><legend class="title"><b>[[.Extra_bed_baby_cot.]]</b></legend>
			<table border="1" bordercolor="#CCCCCC" cellpadding="3" id="extra_bed_baby_cot" style="border-collapse:collapse;">
            <tr>
            	<td>[[.service_name.]]</td>
            	<td>[[.eb_total_quantity.]]</td>                
            	<td>[[.eb_quantity.]]</td>                                
            </tr>
            <!--LIST:ebs-->
            <tr>
            	<td style="width: 150px;">[[|ebs.name|]]:</td>
                <td style="text-align: center;">[[|ebs.total_quantity|]]</td>
            	<td style="text-align: center;">[[|ebs.quantity|]]</td>
            </tr>
            <!--/LIST:ebs-->
			</table>            	
            </fieldset> <br />
		<!--IF:edit_reservation(USER::can_view(false,ANY_CATEGORY))-->
        <fieldset><legend class="title"><b>[[.search_booking.]]</b></legend> 
		<table cellpadding="2" width="100%" class="room-map-customer-search-box" style="border:0px;">
<tr>
			  <td nowrap="nowrap" align="right">[[.RE_code.]] </td>
			  <td><input name="code" type="text" id="code" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>                
<tr>
			  <td nowrap="nowrap" align="right">[[.booker.]]</td>
			  <td><input name="booker" type="text" id="booker" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
            <tr>
            <tr>
			  <td nowrap="nowrap" align="right">[[.phone_booker.]]</td>
			  <td><input name="phone_booker" type="text" id="phone_booker" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
	        </tr>
            <!-- start: Manh them truong tim kiem theo phong  -->
            <tr>
			  <td nowrap="nowrap" align="right">[[.room_number.]] </td>
			  <td><input name="number_room" type="text" id="number_room" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
            </tr>
            <!-- end: Manh them truong tim kiem theo phong  -->  
			          
			<tr>
			  <td nowrap="nowrap" align="right">[[.booking_code.]] </td>
			  <td><input name="booking_code" type="text" id="booking_code" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
			<tr><td nowrap="nowrap" align="right">[[.company_name.]] </td>
				<td width="100%">
					<input type="text" id="customer_name" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			</tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.traveller_name.]] </td>
			  <td><input name="traveller_name" type="text" id="traveller_name" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/>
              </td>
			  </tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.group_note_room.]]</td>
			  <td><input name="note" type="text" id="note" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.country.]]</td>
			  <td><input name="nationality_id" type="text" id="nationality_id" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
            <tr>
			  <td nowrap="nowrap" align="right">[[.status.]]</td>
			  <td><select  name="room_status" id="room_status" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;">
              		<option value="" selected>ALL</option>
              		<option value="CHECKIN">CHECKIN</option>
                    <option value="BOOKED">BOOKED</option>
                    <option value="CHECKOUT">CHECKOUT</option>
                    <option value="CANCEL">CANCEL</option>
              	</select>
			</tr>
            <tr>
			  <td nowrap="nowrap" align="right">[[.Start_date.]]</td>
			  <td><input name="start_date" type="text" id="start_date" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
            <tr>
            <tr>
			  <td nowrap="nowrap" align="right">[[.End_date.]]</td>
			  <td><input name="end_date" type="text" id="end_date" style="width:100px; height: 22px; margin-bottom: 3px; margin-left: 3px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
            <tr>
            <tr>
                <td colspan="2" style="text-align: right; padding-right: 0px;"><a class="w3-btn w3-gray" href="#" type="button" name="search-booking" onclick="buildReservationSearch();" value="" style="text-decoration: none; width: 100px;">[[.search.]]</a></td>
            </tr>
		</table>
        </fieldset> <br />
		<!--/IF:edit_reservation-->
		
             
		<!--IF:cond(Url::get('cmd')=='select')-->
		<fieldset>
			<legend class="title" style="font-size:12px;">[[.select_room_level.]]</legend>
			[<a href="<?php echo Url::build_current(array('cmd','object_id','input_count'));?>">[[.all.]]</a>]<br /><br />
			<!--LIST:room_levels-->
			<div class="row-even"><input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px;" title="[[.room_quantity.]]" value="1" readonly="readonly">&nbsp;<a href="#" onClick="selectRoomLevel(<?php echo Url::iget('object_id');?>,[[|room_levels.id|]],'[[|room_levels.name|]]',<?php echo Url::iget('input_count');?>)">[[|room_levels.name|]]</a> | [<a class="notice" href="<?php echo Url::build_current(array('cmd','object_id','act','input_count','room_level_id'=>[[=room_levels.id=]],'room_level_id_old'));?>">[[.filter.]]</a>]</div><br />
			<!--/LIST:room_levels-->
		</fieldset>
		<!--/IF:cond-->
		<fieldset style="border:1px solid #9DC9FF">
			<legend class="title">[[.booking_without_room.]]</legend>
            <table width="200px" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
				<tr class="w3-gray" style="height: 30px; width: 200px;">
					<th width="58%" style="text-transform: uppercase;">[[.room_level.]]</th>
					<th width="42%" style="text-transform: uppercase;">[[.num_people.]]</th>
				</tr>
				<?php $temp = '';?>
				<!--LIST:books_without_room-->
				<?php if($temp != [[=books_without_room.reservation_id=]]){$temp != [[=books_without_room.reservation_id=]];?>
				<tr>
					<td bgcolor="#EFEFEF" style="width: 120px;"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=books_without_room.reservation_id=]]));?>"><strong>[[|books_without_room.reservation_id|]] - [[|books_without_room.booking_code|]]</strong></a></td>
                    <td bgcolor="#EFEFEF"><a href="<?php echo Url::build('reservation',array('cmd'=>'asign_room','id'=>[[=books_without_room.reservation_id=]]));?>"><input class="w3-hover-gray" name="asign_room" type="button" id="asign_room" value="[[.assign.]]"  /> </a></td>
				</tr>
				<?php }?>
				<tr>
					<td> [[|books_without_room.room_level|]]</td>
					<td><span class="reservation-list-item">([[|books_without_room.adult|]]) <i class="fa fa-male" style="font-size: 16px;"></i><!--IF:cond([[=books_without_room.child=]])--> + ([[|books_without_room.child|]]) <i class="fa fa-child" style="font-size: 16px;"></i></span>
                    </td>
				</tr>
				<!--/LIST:books_without_room-->
			</table>
		</fieldset><br />
        <!-- manh them han xac nhan cut_of_day -->
            <fieldset style="border:1px solid #9DC9FF; ">
	           <legend class="title">[[.booking_cut_of_day.]]</legend>
               <div id="accordian" style="padding-bottom: 10px;">
                    <ul>
                        <!--LIST:booking_cut_of_day-->
                        <li style="height: 15px; ">
                            <h3><span></span>[[.re_code.]] <b class="w3-text-red">[[|booking_cut_of_day.recode_hard|]]</b> |  [[|booking_cut_of_day.sum|]]P | <a href="?page=reservation&cmd=edit&id=[[|booking_cut_of_day.recode_hard|]]" style="color: red;">[[.check.]]</a></h3>
                        </li>
                        <!--/LIST:booking_cut_of_day-->
                    </ul>
               </div>
            </fieldset>
        <!-- end manh -->
	</div></td>
	<td bgcolor="#FFFFFF" align="left" valign="top" id="room_map_toogle" width="1%"><img id="room_map_toogle_image" src="packages/core/skins/default/images/paging_left_arrow.gif" style="cursor:pointer;" onClick="if(jQuery.cookie('collapse')==1){jQuery.cookie('collapse','0');jQuery('#room_map_left_utils').fadeOut();this.src='packages/core/skins/default/images/paging_right_arrow.gif';}else{jQuery.cookie('collapse','1');jQuery('#room_map_left_utils').fadeIn();this.src='packages/core/skins/default/images/paging_left_arrow.gif'}"></td>
	<td bgcolor="#FFFFFF" align="left" width="99%">
		
        <div id="information_bar"></div>
		<!--IF:cond(User::can_view(false,ANY_CATEGORY))-->
		<a href="#" type="button" value="" onClick="buildReservationUrl('RFA');" class="w3-btn w3-gray w3-hover-blue" id="lt1" style="text-decoration: none; margin-right: 5px;"><i class="fa fa-pencil-square-o w3-hover-text-white" style="font-size: 14px;"></i> [[.New_reservation.]]</a>
        <a href="#" type="button" value="" onClick="if(canCheckin()) buildReservationUrl('RFW');" class="w3-btn w3-gray w3-hover-yellow" id="lt2" style="text-decoration: none; margin-right: 5px;"><i class="fa fa-pencil-square-o" style="font-size: 14px;"></i> [[.Walk_in.]]</a>
        <a class="w3-btn w3-gray w3-hover-green" type="button" href="#" onClick="buildRoommapUrl('SIMPLE');" style="text-decoration: none; text-transform: uppercase; border: 1px solid gray;"><!--IF:cond_room_map(Url::get('view_layout')=='simple')-->[[.room_map_detail.]]<!--ELSE-->[[.room_map_simple.]]<!--/IF:cond_room_map--></a>
        <a href="#" type="button" value="" onClick="switchFullScreen();" class="w3-btn w3-gray" id="lt3; full_screen_button" style="float:right; text-decoration: none;"><i class="fa fa-arrows-alt" style="font-size: 14px;"></i> [[.full_screen.]]</a>        
        <!--IF:cond_module(User::can_view(MODULE_MANAGENOTE,ANY_CATEGORY))-->
        <a href="#" type="button" value="" onClick="window.open('?page=manage_note');" class="w3-btn w3-gray w3-hover-lime" style="float:right; margin-right: 7px; text-decoration: none;" id="lt4"><i class="fa fa-commenting-o" style="font-size: 14px;"></i> [[.Reservation_note.]]</a>
        <!--/IF:cond_module-->
        <table width="100%" border="0" cellpadding="2" style="margin-top: 5px; border-top: 1px solid gray;">
			  <tr>
				<td style="padding-top: 5px; width: 100px; "><a href="#" class="room AVAILABLE w3-hover-white" style="width:25px;height:20px;margin-right:2px; color: black; ">[[|total_available_room|]]</a><div style="padding-top: 3px; ">[[.available_room.]]</div></td>				
				<td style="padding-top: 5px; width: 100px; "><a href="#" class="room BOOKED" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_booked_room|]]</a><div style="padding-top: 3px;">[[.booked.]]</div></td>								
				<td style="padding-top: 5px; width: 100px; "><a href="#" class="room OVERDUE_BOOKED" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_overdue_booked_room|]]</a><div style="padding-top: 3px;">[[.overdue_booked.]]</div></td>			
				<td style="padding-top: 5px;width: 100px; "><a href="#" class="room OCCUPIED" style="width:25px;height:20px;margin-right:2px; color: black;"><?php echo [[=total_checkin_room=]];?></a><div style="padding-top: 3px;">[[.occupied.]]</div></td>				
				<td style="padding-top: 5px;width: 100px; "><a href="#" class="room" style="width:25px;height:20px;margin-right:2px; background-color: #fde0c5; color: black; border: 2px solid #00b2f9;"><?php echo [[=total_change_room_today=]];?></a><div style="padding-top: 3px;">[[.checkin_change_today.]]</div></td>			
				<td style="padding-top: 5px;width: 120px; "><a href="#" class="room DAYUSED" style="width:25px;height:20px;margin-right:2px; color: black;"><?php echo [[=total_checkin_today=]] ;?></a><div style="padding-top: 3px;">[[.checkin_today.]]</div></td>				
				<td style="padding-top: 5px;width: 120px; "><a href="#" class="room OVERDUE" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_overdue_room|]]</a><div style="padding-top: 3px;">[[.overdue.]]</div></td>				
				<td style="padding-top: 5px;width: 100px; "><a href="#" class="room EXPECTED_CHECKOUT" style="width:25px;height:20px;margin-right:2px;color: black;">[[|total_checkout_today_room|]]</a><div style="padding-top: 3px;">[[.actual_expected_checkout.]]</div></td>				
                <td style="padding-top: 5px; "><a href="#" class="room REPAIR" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_repaire_room|]]</a><div style="padding-top: 3px;">[[.reparing.]]</div></td>
                <td style="width: 50px;"><div style="padding-top: 3px;"><i class="fa fa-circle w3-text-red"></i> [[.Dirty.]]</div></td>
                <td style="width: 50px;"><div style="padding-top: 3px;"><i class="fa fa-circle w3-text-blue"></i> [[.Clean.]]</div></td>
               </tr>				
		</table>
        <br clear="all">
		<!--/IF:cond-->
		<div class="body">
<!--THONG TIN PHONG-->        
		<table width="1100" border="1" cellpadding="2" cellspacing="0" bgcolor="#EAE9E9" bordercolor="#CCCCCC">
			<!--LIST:floors-->	
			<!-- onmouseover="this.bgColor='#B7D8FF'" onmouseout="this.bgColor='#FFFFFF'" -->	
			<tr valign="middle">
				<td width="40px" align="center" style="text-transform:uppercase;color:#b00000;<?php if(substr([[=floors.name=]],0,1)=='A'){echo 'background:#82BAFF;';}else{ echo 'background:#d9d7d7;';}?>" nowrap="nowrap"><b>[[|floors.name|]]</b></td>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>
						<!--LIST:floors.rooms-->
							  <div title="<?php echo addslashes([[=floors.rooms.note=]]);?>" class="room-bound">
							  	<span class="room-name"><strong><!--IF:cond_room_map(Url::get('view_layout')=='simple')--><!--ELSE--><span style="font-size:14px;color:black;">[[|floors.rooms.name|]]</span><!--/IF:cond_room_map--></strong> [[|floors.rooms.type_name|]]<br /></span><br clear="all"/>
                                <input type="hidden" value="[[|floors.rooms.id|]]" id="[[|floors.rooms.id|]]"/>
                                
								<a href="#" id="room_[[|floors.rooms.id|]]" class="room <?php if(isset([[=floors.rooms.status_color=]])){ echo [[=floors.rooms.status_color=]]; } else {echo ([[=floors.rooms.house_status=]]=='REPAIR' || [[=floors.rooms.status=]] =='CHECKOUT')?[[=floors.rooms.house_status=]]:[[=floors.rooms.status=]];}?>"  
                                
                                <?php
                                /** Minh fix truong hop doi phong da o qua dem **/
                                if(([[=floors.rooms.status=]]!='CHECKOUT') AND ([[=floors.rooms.status=]]!='BOOKED'))
                                {
                                    $in_date_check = '';
                                    $arrival_time_check = '';
                                    $change_room_from_rr_check = '';
                                    foreach([[=floors.rooms.reservations=]] as $m_key=>$m_value)
                                    {
        							    $in_date_check = $_REQUEST['in_date'];
                                        $arrival_time_check = $m_value['arrival_time'];
                                        $change_room_from_rr_check = isset($m_value['change_room_from_rr'])?$m_value['change_room_from_rr']:'';
        							}
                                    if($in_date_check==$arrival_time_check and $change_room_from_rr_check!='')
                                    {
                                        echo 'style="border: 4px solid #00b2f9; background-color: #fde0c5;"';
                                    }
                                }
                                if([[=floors.rooms.status=]]=='BOOKED' OR [[=floors.rooms.status=]]=='OVERDUE_BOOKED')
                                {
                                    foreach([[=floors.rooms.reservations=]] as $m_key=>$m_value)
                                    {
        							    if($m_value['do_not_move']==1){
        							        echo 'style="border: 4px solid #000000;"';
        							    }
        							}
                                }
                                /** end manh **/
                                 ?>
									<?php
									if(URL::get('cmd')=='select')
									{
										echo 'onclick=" opener.document.getElementById(\'room_id_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.id=]].'\';
														opener.document.getElementById(\'room_name_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.name=]].'\';
														opener.document.getElementById(\'room_level_name_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.room_level_name=]].'\';
														opener.document.getElementById(\'room_level_id_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.room_level_id=]].'\';
														opener.document.getElementById(\'time_in_'.URL::get('object_id').'\').value=\''.CHECK_IN_TIME.'\';
														opener.document.getElementById(\'time_out_'.URL::get('object_id').'\').value=\''.CHECK_OUT_TIME.'\';
														if(!opener.document.getElementById(\'arrival_time_'.URL::get('object_id').'\').value)
															opener.document.getElementById(\'arrival_time_'.URL::get('object_id').'\').value = \''.[[=day=]].'/'.[[=month=]].'/'.[[=year=]].'\';
														if(!opener.document.getElementById(\'departure_time_'.URL::get('object_id').'\').value)
															opener.document.getElementById(\'departure_time_'.URL::get('object_id').'\').value = \''.[[=end_day=]].'/'.[[=end_month=]].'/'.[[=end_year=]].'\';
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
										echo 'onclick="select_room('.[[=floors.rooms.id=]].', document.HotelRoomMapForm);update_room_info();return false;"';
									}
									?>>
                                    <!--IF:cond_room_map(Url::get('view_layout')=='simple')--><!--ELSE-->
                                    
                                        <div style="height: 80%;">
    									<!--IF:room_level(Url::get('cmd')=='select' and ([[=floors.rooms.status=]]=='AVAILABLE' OR [[=floors.rooms.status=]]=='CHECKOUT'))-->
                                            <span title="[[.select_room.]]"><img src="packages/core/skins/default/images/active.gif" width="12" height="12"></span>
                                        <!--/IF:room_level-->
                                        <!--IF:time(isset([[=floors.rooms.time_in=]]) and [[=floors.rooms.time_in=]] and [[=floors.rooms.status=]] != 'CHECKOUT' or [[=floors.rooms.house_status=]]=='REPAIR')-->
    									   <span style="font-size:11px;color:#003399;font-weight:bold;"><?php echo (date('d/m/Y',[[=floors.rooms.time_in=]])==date('d/m/Y',[[=floors.rooms.time_out=]]))?date('H:i',[[=floors.rooms.time_in=]]):date('d/m',[[=floors.rooms.time_in=]]);?> - <?php echo (date('d/m/Y',[[=floors.rooms.time_in=]])==date('d/m/Y',[[=floors.rooms.time_out=]]))?date('H:i',[[=floors.rooms.time_out=]]):date('d/m',[[=floors.rooms.time_out=]]);?></span><br />
    									<!--/IF:time-->
                                        <!--IF:time2([[=floors.rooms.house_status=]] == 'HOUSEUSE')-->
    									   <!--LIST:floors.rooms.reservations-->
                                                <!--IF:time3(isset([[=floors.rooms.reservations.start_date=]]) and isset([[=floors.rooms.reservations.end_date=]]))-->
                                                    <span style="font-size:9px;color:#003399;font-weight:bold;"><?php echo substr([[=floors.rooms.reservations.start_date=]],0,5);?> - <?php echo substr([[=floors.rooms.reservations.end_date=]],0,5);?></span><br />
                                                <!--IF:time3-->
                                           <?php break;?>
    									   <!--/LIST:floors.rooms.reservations-->
                                        <!--/IF:time2-->									
                                         <?php $r_r = '';?>
    									 <?php if(isset([[=floors.rooms.foc_all=]]) and [[=floors.rooms.foc_all=]]==1 and [[=floors.rooms.status=]] != 'CHECKOUT')
    										{ 
    											echo '<span class="room-foc" title="'.[[=floors.rooms.foc=]].'FOC ALL">FOC ALL</span><br>';
    										}else if(isset([[=floors.rooms.foc=]]) and [[=floors.rooms.foc=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
    										{ 
    											echo '<span class="room-foc" title="'.[[=floors.rooms.foc=]].'">FOC</span><br>';
    										}
    									?>
    									<!--LIST:floors.rooms.travellers-->
                                        <?php $r_r = [[=floors.rooms.travellers.reservation_room_id=]];                                  
                                            	if(isset($f[$r_r])){
    												$f[$r_r]++;
    											}else{	
    												$f[$r_r]=1;
    											}
    											if($f[$r_r]==1){?>
    												<span style="font-size:10px;color:#004080;">[[|floors.rooms.travellers.customer_name|]]
    											<?php }?>
    									<!--/LIST:floors.rooms.travellers-->
                                       <?php if(isset($f[$r_r]) && $f[$r_r]>1){
                                        	echo '('.$f[$r_r].')</span>';
                                        }?>                                    
                                        
    									<?php if(isset([[=floors.rooms.tour_id=]]) and [[=floors.rooms.tour_id=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
    									{
    										echo '<span style="font-size:9px; height: 10px !important; background-color:'.[[=floors.rooms.color=]].';" title="'.[[=floors.rooms.tour_name=]].'">'.[[=floors.rooms.tour_name=]].'</span><br>';
    									}
    									?>
    																		                                    
                                        <?php if(isset([[=floors.rooms.customer_name=]]) and [[=floors.rooms.customer_name=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
    									{
    										echo '<span style="font-size:9px; height: 10px !important; background-color:'.[[=floors.rooms.color=]].';" title="'.[[=floors.rooms.customer_name=]].'">'.[[=floors.rooms.customer_name=]].'</span><br>';
    									}
    									?>
                                        <!--/IF:cond_room_map-->
                                        
    									<?php if([[=floors.rooms.out_of_service=]] and ([[=floors.rooms.status=]] != 'CHECKOUT'))
    									{
    										echo '<span style="color:red;font-size:8px">oos</span>';
    									}
    									?>
    									<?php if([[=floors.rooms.note=]] and ([[=floors.rooms.status=]] != 'CHECKOUT' or ([[=floors.rooms.status=]] == 'CHECKOUT' and [[=floors.rooms.house_status=]] == 'REPAIR')))
    									{
    									   if(([[=floors.rooms.status=]] == 'CHECKOUT' and [[=floors.rooms.house_status=]] == 'REPAIR'))
                                           {
                                              echo '</br>';
                                           }
    										echo '<span style="font-size:10px;color:blue;font-weight:bold"><i class="fa fa-bell"></i></span>';
    									}
    									?>
                                        <!--IF:cond_room_map(Url::get('view_layout')=='simple')--><!--ELSE-->
                                        </div>
                                        <div style="height: 15%;">
                                        <!--/IF:cond_room_map-->
<!--ROOM STATUS ON ROOM MAP-->
                                    <!--IF:cond_room_map(Url::get('view_layout')=='simple')-->
                                    <span id="house_status_[[|floors.rooms.id|]]" ><b style="font-size: 15px; color: black !important; ">[[|floors.rooms.name|]]</b><br/>
                                    <?php 
                                    if([[=floors.rooms.house_status=]]=='DIRTY')
                                    {
                                        echo '<span style="font-size:9px; padding: 11px 2px 0px 0px;" class="w3-right" title="Dirty"><i class="fa fa-circle w3-text-red"></i></span>';
                                    }
                                        elseif([[=floors.rooms.house_status=]]=='CLEAN')
                                        {
                                            echo '<span style="font-size:9px; padding: 11px 2px 0px 0px;" class="w3-right" title="Clean"><i class="fa fa-circle w3-text-blue"></i></span>';
                                        }
                                        else
                                        {
                                            echo '</br>';
                                        }
                                    ?>
                                    </span>
                                    <!--ELSE-->
                                    <span id="house_status_[[|floors.rooms.id|]]" style="font-size:9px;font-weight:normal;color:red;">
                                    <?php 
                                        if([[=floors.rooms.house_status=]]=='DIRTY')
                                        {
                                            echo '<span style="font-size:12px; padding: 2px 2px 0px 0px;" class="w3-right" title="Dirty"><i class="fa fa-circle w3-text-red"></i></span>';
                                        }
                                        elseif([[=floors.rooms.house_status=]]=='CLEAN')
                                        {
                                            echo '<span style="font-size:12px; padding: 2px 2px 0px 0px;" class="w3-right" title="Clean"><i class="fa fa-circle w3-text-blue"></i></span>';
                                        }
                                        else
                                        {
                                            echo '</br>';
                                        }
                                    ?>                                    
                                    </span>
                                    </div>
                                    <!--/IF:cond_room_map-->
								</a>
								<div class="room-item-bound">
								<!--LIST:floors.rooms.old_reservations-->
									<a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=floors.rooms.old_reservations.reservation_id=]]));?>" title="[[.code.]]: [[|floors.rooms.old_reservations.id|]], [[.status.]]: [[|floors.rooms.old_reservations.status|]], [[.price.]]: [[|floors.rooms.old_reservations.price|]] <?php echo HOTEL_CURRENCY;?>" class="item_room [[|floors.rooms.old_reservations.status|]]"></a>
								<!--/LIST:floors.rooms.old_reservations-->
								</div>	
							</div>	
					<!--/LIST:floors.rooms-->
					</td>
				  </tr>
				</table></td>
			</tr>
			<!--/LIST:floors-->	
		</table>
		<br />
        <table width="100%" border="0" cellpadding="2" style="margin-top: 5px; border-top: 1px solid gray;">
			  <tr>
				<td style="padding-top: 5px; width: 100px; "><a href="#" class="room AVAILABLE w3-hover-white" style="width:25px;height:20px;margin-right:2px; color: black; ">[[|total_available_room|]]</a><div style="padding-top: 3px; ">[[.available_room.]]</div></td>				
				<td style="padding-top: 5px; width: 100px; "><a href="#" class="room BOOKED" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_booked_room|]]</a><div style="padding-top: 3px;">[[.booked.]]</div></td>								
				<td style="padding-top: 5px; width: 100px; "><a href="#" class="room OVERDUE_BOOKED" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_overdue_booked_room|]]</a><div style="padding-top: 3px;">[[.overdue_booked.]]</div></td>			
				<td style="padding-top: 5px;width: 100px; "><a href="#" class="room OCCUPIED" style="width:25px;height:20px;margin-right:2px; color: black;"><?php echo [[=total_checkin_room=]];?></a><div style="padding-top: 3px;">[[.occupied.]]</div></td>				
				<td style="padding-top: 5px;width: 100px; "><a href="#" class="room" style="width:25px;height:20px;margin-right:2px; background-color: #fde0c5; color: black; border: 2px solid #00b2f9;"><?php echo [[=total_change_room_today=]];?></a><div style="padding-top: 3px;">[[.checkin_change_today.]]</div></td>			
				<td style="padding-top: 5px;width: 120px; "><a href="#" class="room DAYUSED" style="width:25px;height:20px;margin-right:2px; color: black;"><?php echo [[=total_checkin_today=]] ;?></a><div style="padding-top: 3px;">[[.checkin_today.]]</div></td>				
				<td style="padding-top: 5px;width: 120px; "><a href="#" class="room OVERDUE" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_overdue_room|]]</a><div style="padding-top: 3px;">[[.overdue.]]</div></td>				
				<td style="padding-top: 5px;width: 100px; "><a href="#" class="room EXPECTED_CHECKOUT" style="width:25px;height:20px;margin-right:2px;color: black;">[[|total_checkout_today_room|]]</a><div style="padding-top: 3px;">[[.actual_expected_checkout.]]</div></td>				
                <td style="padding-top: 5px; "><a href="#" class="room REPAIR" style="width:25px;height:20px;margin-right:2px; color: black;">[[|total_repaire_room|]]</a><div style="padding-top: 3px;">[[.reparing.]]</div></td>
                <td style="width: 50px;"><div style="padding-top: 3px;"><i class="fa fa-circle w3-text-red"></i> [[.Dirty.]]</div></td>
                <td style="width: 50px;"><div style="padding-top: 3px;"><i class="fa fa-circle w3-text-blue"></i> [[.Clean.]]</div></td>
               </tr>					
		</table>
		<p></p></td></tr>
	</table>
	<input type="hidden" name="command" id="command"/>
	<br/>
</td></tr>
</table>
<input type="hidden" name="room_ids" id="room_ids"/>
</div> 
</form>
<script type="text/javascript">
    jQuery('#start_date').datepicker();
    jQuery('#end_date').datepicker();
jQuery("#accordian h3").click(function(){
		//slide up all the link lists
		jQuery("#accordian ul ul").slideUp();
		//slide down the link list below the h3 clicked - only if its closed
		if(!jQuery(this).next().is(":visible"))
		{
			jQuery(this).next().slideDown();
		}
	});
//luu nguyen giap check booked during change room status repair
    function check_booked_change_repair(is_booked,start_booked,end_date)
    {
        //neu trang thai phong la repair moi xet
         var house_status = document.getElementById('house_status').value;
         if(house_status=='REPAIR')
         {
             //neu thoi gian thoa thuoc khoang hoac lon hon end_booked thi khong thoa man
             var time_repair = document.getElementById('repair_to').value;
             var repair=0;
             if(time_repair=='')
             {
                 var now = new Date();
                 time_repair =new Date(now.getFullYear(), now.getMonth(), now.getDate());
                 //console.log(now.getMonth());
                 repair = time_repair.getTime();
             }
             else
             {
                 var str = time_repair.split('/');
                 //new date getTime 
                 time_repair = new Date(str[2],str[1]-1,str[0]);
                 repair = time_repair.getTime();
             }
             //neu booked = 1 thi so sanh thoi gian 
             if(is_booked==1)
             {
                 //lay ra thoi gian start_booked
                 var start_str = start_booked.split('/');
                 var start_obj = new Date(start_str[2],start_str[1]-1,start_str[0]);
                 var start = start_obj.getTime();
                 
                 if(start<=repair)  //neu repair > start_booked thi hien thi thong bao
                 {
                       alert('[[.room_booked_from.]]:'+start_booked + ' [[.to_date.]] :' + end_date);
                       return false;
                 }
                 
             }
         } 
        return true;
    }
    //end luu nguyen giap
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
	jQuery('#arrival_time').datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#departure_time').datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#in_date').datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
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
		//console.log('aa');
        var functions = '';
		var actions = get_actions();
		for(var i in actions)
		{
			functions += '<a href="'+actions[i].url+'" class="room map">'+actions[i].text+'</a>';
		}
		if(document.HotelRoomMapForm.room_ids.value != '')
		{	
			var rooms = document.HotelRoomMapForm.room_ids.value.split(',');			
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
//THONG TIN CLICK XEM CHI TIET PHONG            
			if(rooms.length==1)
			{
				room_reservations = room['reservations'];//.reverse 
				information += '<tr><td class="label" style="padding-left: 5px; padding-top: 10px;">[[.room_name.]]:<b style="font-size: 20px;"> '+room.name+'</b></td><td width="1%"></td><td class="value"></td></tr>';
				if(typeof(room_reservations)=='undefined' || (typeof(room_reservations)!='undefined' && room.status=='AVAILABLE'))
				{
					information += '<tr><?php if(User::can_admin(false,ANY_CATEGORY)){ ?><td class="label">[[.price.]]</td><td width="1%">:</td><td class="value">'+room.price+room.tax_rate+room.service_rate+' <?php echo HOTEL_CURRENCY;?></td><?php }?></tr>';
				}
				//else
				{
					var last_reservation = 0;
                    //console.log(room_reservations);
					for(var j in room_reservations)
					{
						if(last_reservation != room_reservations[j]['reservation_id'])
						{
							last_reservation = room_reservations[j]['reservation_id'];
							
							if(last_reservation && last_reservation!=0 && last_reservation!='')
							{
								<!--IF:edit_reservation(USER::can_edit($this->get_module_id('Reservation'),ANY_CATEGORY))--> 
								information += '<tr><td class="room-map-bill-number" style="padding-left: 5px;">[[.bill_number.]]: '+room_reservations[j]['reservation_room_id']+'</td><td width="1%"></td><td class="value"><a href="?page=reservation&cmd=edit&id='+last_reservation+'&r_r_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"><i class="fa fa-folder-open w3-text-orange" style="font-size: 18px;"></i> [[.view_detail.]]</a></td></tr>';
								<!--IF:add_traveller(USER::can_add($this->get_module_id('MasterReservation'),ANY_CATEGORY))-->
                                information += '<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=master_reservation&reservation_room_id='+room_reservations[j]['reservation_room_id']+'&form_site=ROOM_MAP&autoload=1" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-dollar " style="font-size: 16px;"></i> [[.view_detail_fast.]]</a></td></tr>';
                                <!--/IF:add_traveller-->
                                <!--IF:add_traveller(USER::can_add($this->get_module_id('Reservation'),ANY_CATEGORY))-->
                                information += '<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=reservation&cmd=import_traveller&r_id='+room_reservations[j]['reservation_id']+'&rr_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-file-excel-o w3-text-green" style="font-size: 16px;"></i> [[.add_traveler.]]</a></td></tr>';
                                <!--/IF:add_traveller-->
								<!--IF:add_extra_service_invoice(USER::can_add($this->get_module_id('ExtraServiceInvoice'),ANY_CATEGORY))-->
                                information += '<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=extra_service_invoice&type=SERVICE&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-hashtag " style="font-size: 16px;"></i> [[.add_extra_service_invoice.]]</a></td></tr>';
                                <!--/IF:add_extra_service_invoice-->
                                 <!--IF:add_extra_service_invoice(USER::can_add($this->get_module_id('ExtraServiceInvoice'),ANY_CATEGORY))-->
                                information += '<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=extra_service_invoice&type=ROOM&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-bed " style="font-size: 16px;"></i> [[.extra_char_room.]]</a></td></tr>';
                                <!--/IF:add_extra_service_invoice-->
                                <!--ELSE-->
								<!--IF:view_reservation(User::can_view($this->get_module_id('CheckIn'),ANY_CATEGORY))-->
								information += '<tr><td class="room-map-bill-number">[[.bill_number.]]: '+room_reservations[j]['reservation_room_id']+'</td><td width="1%">:</td><td class="value"><a href="?page=reservation&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&extra_service_invoice&id='+room_reservations[j]['reservation_room_id']+'&cmd=invoice" class="room-map-edit-link"><i class="fa fa-folder-open w3-text-orange" style="font-size: 18px;"></i> [[.view_detail.]]</a></td></tr>';
								
                                <!--ELSE-->
								information += '<tr><td class="room-map-bill-number">[[.bill_number.]]: '+room_reservations[j]['reservation_id']+'</td><td width="1%">:</td><td class="value"></td></tr>';
								<!--/IF:view_reservation-->
								<!--/IF:edit_reservation-->
                                //console.log(room_reservations[j]['status']);
								<!--IF:add_traveller(USER::can_add($this->get_module_id('UpdateTraveller'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED' || room_reservations[j]['status']=='BOOKED' || room_reservations[j]['status']=='OVERDUE_BOOKED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="#" onClick="openWindowUrl(\'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=add_traveller&rr_id='+room_reservations[j]['reservation_room_id']+'&r_id='+room_reservations[j]['reservation_id']+'\',Array(\'add_traveller_'+room_reservations[j]['reservation_room_id']+'\',\'[[.list_traveller.]]\',\'20\',\'110\',\'1100\',\'570\'));closeAllWindows();" class="room-map-edit-link" style="text-decoration:none;"><i class="fa fa-user-plus w3-text-indigo" style="font-size: 16px;"></i> [[.add_guest.]]</a></td></tr>':'';
								<!--/IF:add_traveller-->
								<!--IF:add_minibar_invoice(USER::can_add($this->get_module_id('MinibarInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=minibar_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-coffee" style="font-size: 16px;"></i> [[.add_minibar_invoice.]]</a></td></tr>':'';
								<!--/IF:add_minibar_invoice-->
								<!--IF:add_minibar_invoice(USER::can_add($this->get_module_id('LaundryInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=laundry_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-female" style="font-size: 16px;"></i> [[.add_laundry_invoice.]]</a></td></tr>':'';
								<!--/IF:add_minibar_invoice-->
                                /*
								<!--IF:add_extra_service_invoice(USER::can_add($this->get_module_id('ExtraServiceInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="?page=extra_service_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link"> [[.add_extra_service_invoice.]]</a></td></tr>':'';
								<!--/IF:add_extra_service_invoice-->
                                */
								<!--IF:add_equipment_invoice(USER::can_add($this->get_module_id('EquipmentInvoice'),ANY_CATEGORY))-->
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED')?'<tr><td></td><td width="1%"></td><td class="value"><a target="_blank" href="?page=equipment_invoice&cmd=add&reservation_room_id='+room_reservations[j]['reservation_room_id']+'" class="room-map-edit-link" style="text-decoration: none;"><i class="fa fa-frown-o" style="font-size: 16px;"></i> [[.add_compensation_invoice.]]</a></td></tr>':'';
								<!--/IF:add_equipment_invoice-->
								information += '<tr><td class="label" style="padding-left: 5px;">[[.create_user.]]</td><td>:</td><td class="value">'+room_reservations[j]['user_id']+'</td></tr>';
								information += '<tr><td class="label" style="padding-left: 5px;">[[.reservation_status.]]</td><td>:</td><td class="value">'+room_reservations[j]['status']+' ('+room_reservations[j]['adult']+' [[.adult.]])</td></tr>';
								if(room_reservations[j]['net_price']==1){
									information += '<tr><td class="label" style="padding-left: 5px;">[[.price.]]</td><td>:</td><?php if(User::can_admin(false,ANY_CATEGORY)){ ?><td class="value w3-text-red">'+room_reservations[j]['price']+' <?php echo HOTEL_CURRENCY;?></td><?php }?></tr>';
								}else{
									information += '<tr><td class="label" style="padding-left: 5px;">[[.price.]]</td><td>:</td><?php if(User::can_admin(false,ANY_CATEGORY)){?><td class="value w3-text-red">'+room_reservations[j]['price']+room_reservations[j]['tax_rate']+room_reservations[j]['service_rate']+' <?php echo HOTEL_CURRENCY;?></td><?php } ?></tr>';
								}
								if(room_reservations[j]['company_name'])
									information += '<tr><td class="label" style="padding-left: 5px;">[[.company.]]</td><td>:</td><td class="value"><b>'+room_reservations[j]['company_name']+'</b></td></tr>';
								    information += '<tr><td class="w3-text-indigo" colspan="3" align="left" style="padding-left: 5px;"><i class="fa fa-calendar w3-text-indigo" style="font-size: 18px;"></i>&nbsp;'+room_reservations[j]['arrival_time']+room_reservations[j]['time_in']+' - '+room_reservations[j]['departure_time']+room_reservations[j]['time_out']+' ('+room_reservations[j]['duration']+')</td></tr>';
								if(room_reservations[j]['travellers'])
								{
									information += '<tr><td colspan="3" style="padding-left: 5px; padding-top: 5px;"><table><th nowrap width="100%" align="left" style="text-transform: uppercase;">[[.guest_name.]]</th></tr>';
									for(var k in room_reservations[j]['travellers'])
									{
										information += '<tr title="[[.date_of_birth.]]: '+
											room_reservations[j]['travellers'][k]['age']+'\n[[.country.]]: '+
											room_reservations[j]['travellers'][k]['country_name']+'"><td class="value"><a target="_blank" href="?page=traveller&id='+room_reservations[j]['travellers'][k]['traveller_id']+'">'+room_reservations[j]['travellers'][k]['customer_name']+': '+room_reservations[j]['travellers'][k]['date_in']+' ('+room_reservations[j]['travellers'][k]['time_in']+') - '+room_reservations[j]['travellers'][k]['date_out']+' ('+room_reservations[j]['travellers'][k]['time_out']+')</a></td>';
										information += '<td class="value"></td></tr>';
									}
									information += '</table></td></tr>';
								}
								information += '<tr><td colspan="3" class="w3-text-red" style="padding-left: 5px;">[[.group_note.]]:\
						<div  id="group_note_'+room_reservations[j]['reservation_id']+'" style="width:375px; border:none;" readonly>'+room_reservations[j]['group_note']+'</div> ';
								<!--IF:room_note(User::can_edit(false,ANY_CATEGORY))-->
								information += '<tr"><td colspan="3" class="w3-text-red" style="padding-left: 5px;">[[.note.]]:\ <textarea  name="room_note_'+room_reservations[j]['reservation_id']+'" style="width:375px;" class="valid_note" onkeyup="validText();" rows="3">'+room_reservations[j]['room_note']+'</textarea>';
                        <?php if(User::can_edit($this->get_module_id('GrandModeratorChangeRoomStatus'),ANY_CATEGORY)){ ?>
                        information += '<input class="w3-btn w3-gray" style="margin-top: 3px;" type="submit" value="Save" name="change_room_note_'+room.id+'"/>';
                        <?php } ?>
						information += '<hr></td></tr>';
								<!--/IF:room_note-->
							}
						}
					}
				}
			}
			<!--IF:housekeeping(USER::can_view(false,ANY_CATEGORY))-->
			information += '<tr><td colspan="3" style="padding-left: 5px;"><h4>[[.for_housekeeping.]]:</h4>';
			<!--IF:minibar(User::can_view($this->get_module_id('GrandModeratorChangeRoomStatus'),ANY_CATEGORY))-->
			information += '[[.note.]]:<br/><textarea  name="note" id="note_dn" style="width:375px;" rows="3">'+((rooms.length==1)?room.hk_note:'')+'</textarea><br/>';
			<!--/IF:minibar-->
			//information += '[[.house_status.]]: <select  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option><option value="HOUSEUSE">HOUSEUSE</option></select><input type="submit" value="Change" class="hk-status-button"/>';
			//KimTan: dau chon repair neu phong dang co cac trang thai
            if((room_reservations)){
                var tan_check = 0;
                for(var tan in room_reservations){
                    //console.log(room_reservations[tan]);
                    if(room_reservations[tan]['reservation_status']!='CHECKOUT' && room_reservations[tan]['reservation_status']){
                        tan_check = 1; break;
                    }
                }
                 if(tan_check==1)
                 {
                    <?php if(User::can_edit($this->get_module_id('GrandModeratorChangeRoomStatus'),ANY_CATEGORY)){ ?>
                    information += '<div style="width: 380px; ">[[.house_status.]]: <select style="height: 30px; margin-top: 3px; width:90px;"  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="CLEAN">CLEAN</option><option value="DIRTY">DIRTY</option></select><input class="w3-btn w3-gray" type="submit" value="Change" style="margin-left: 3px;" /></div>';
                    <?php } ?>
                 } 
                else{
                    <?php if(User::can_edit($this->get_module_id('GrandModeratorChangeRoomStatus'),ANY_CATEGORY)){ ?>
                    information += '<div style="width: 380px; ">[[.house_status.]]: <select style="height: 30px; margin-top: 3px; width:90px;" name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="CLEAN">CLEAN</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option></select><input class="w3-btn w3-gray" type="button" onclick="check_repair_sb();" value="Change"  style="margin-left: 3px;" /></div>';
                    <?php } ?>
                }
                
            }else{
                <?php if(User::can_edit($this->get_module_id('GrandModeratorChangeRoomStatus'),ANY_CATEGORY)){ ?>
                information += '<div style="width: 380px;">[[.house_status.]]: <select style="height: 30px; margin-top: 3px; width:90px;" name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="CLEAN">CLEAN</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option></select><input class="w3-btn w3-gray" type="button" onclick="check_repair_sb();" value="Change"  style="margin-left: 3px;" /></div>';
                <?php } ?>
            }
            // end KimTan
            information += '<div id="div_date_repair" style="width: 380px;">[[.select_date_to.]]: <input  name="repair_to" type="text" id="repair_to" class="date-input" style="width:90px; height: 30px; margin-top: 3px; margin-bottom:20px;" ></div></td></tr>';
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
			jQuery(".room-bound").click(function(e)
            {
				if(e.ctrlKey==false && e.shiftKey==false && e.metaKey==false)
                {
                    var room_id = jQuery(this).children('input').val();
                    var house_status = jQuery('#house_status_'+room_id).html();
                    if(house_status == 'DIRTY')
                        var can_in = false;
                    else can_in = true;
					pageY = e.pageY - 100;
					pageX = e.pageX - 400;
					jQuery('#room_map').window({
						draggable: false,
						resizable:true,
						title: "[[.rooms_info.]]",
						content: information,
						footerContent: '<div class="w3-light-gray" style="text-align: center;"><a class="w3-btn w3-blue" style="color:#333333;font-size:11px; text-decoration: none; margin-top: 5px; margin-right: 5px;" onclick="buildReservationUrl(\'RFA\');">[[.reserve_for_agent.]]<a>  <a  class="w3-btn w3-orange w3-text-white" style="font-size:11px; text-decoration: none; margin-top: 5px;" onclick="if(canCheckin()) buildReservationUrl(\'RFW\');">[[.Walk_in.]]<a></div>',
						frameClass: 'room-info-content',
						footerClass:'room-info-content',
						showRoundCorner:true,
						resizable: false,
						maximizable: false,
						x:pageX,
						y:pageY,
						width: 400,
						height:350,
						draggable: true,
						onOpen: closeAllWindows()
					});
				}
				 jQuery('#repair_to').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4'});
				 jQuery('#ui-datepicker-div').css('z-index','3000');
			     /** start: KID them ham kiem tra repair phong co booking tuong lai**/
                jQuery("#repair_to").change(function()
                {  
                    var room_id = jQuery('#room_ids').val();              
                    var repair_to = jQuery('#repair_to').val();
                    var in_date = jQuery('#in_date').val();
                    var house_status = jQuery('#house_status').val();
                    <?php echo 'var block_id = '.Module::block_id().';';?>
                    jQuery.ajax({
                        url:"form.php?block_id="+block_id,
                        type:"POST",
                        data:{room_id:room_id,repair_to:repair_to,in_date:in_date,house_status:house_status},
                        success:function(html)
                        {
                            if(html=='false')
                            {
                                alert('[[.the_room_has_future_reservation_change_room_before_repair.]]');
                                closeAllWindows();
                                return false;
                            }
                                
                        }
                    });        	
    			});
                jQuery("#house_status").change(function()
                {  
                    var room_id = jQuery('#room_ids').val();              
                    var repair_to = jQuery('#repair_to').val();
                    var in_date = jQuery('#in_date').val();
                    var house_status = jQuery('#house_status').val();
                    <?php echo 'var block_id = '.Module::block_id().';';?>
                    jQuery.ajax({
                        url:"form.php?block_id="+block_id,
                        type:"POST",
                        data:{room_id:room_id,repair_to:repair_to,in_date:in_date,house_status:house_status},
                        success:function(html)
                        {
                            if(html=='false')
                            {
                                alert('[[.the_room_has_future_reservation_change_room_before_repair.]]');
                                closeAllWindows();
                                return false;
                            }
                                
                        }
                    });        	
    			});
                /** end: KID them ham kiem tra repair phong co booking tuong lai **/
            });
            
		}
		//$('information_bar').innerHTML += functions;
	}
    function check_repair_sb()
    {
        var room_id = jQuery('#room_ids').val(); 
        var room_id = jQuery('#room_ids').val();              
        var repair_to = jQuery('#repair_to').val();
        var in_date = jQuery('#in_date').val();
        var house_status = jQuery('#house_status').val();
        if(house_status=='REPAIR')
        {
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
                url:"form.php?block_id="+block_id,
                type:"POST",
                data:{room_id:room_id,repair_to:repair_to,in_date:in_date,house_status:house_status},
                success:function(html)
                {
                    if(html=='false')
                    {
                        alert('[[.the_room_has_future_reservation_change_room_before_repair.]]');
                        closeAllWindows();
                        return false;
                    }
                    else
                    {
                        HotelRoomMapForm.submit();
                    }  
                }
            });
        } 
        else
        {
            HotelRoomMapForm.submit();
        }    
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
		var time_parameters = '&arrival_time=[[|day|]]/[[|month|]]/[[|year|]]&departure_time=[[|end_day|]]/[[|end_month|]]/[[|end_year|]]';
		var date_parameters = '&year=[[|year|]]&month=[[|month|]]&day=[[|day|]]';
		var changed_rooms = '';
		
		var reservation_status = 'AVAILABLE';
		var rooms_status = 'AVAILABLE';
		var reservation_id = 0;
		var rooms = [];
		if(document.HotelRoomMapForm.room_ids.value != '')
		{
			rooms = document.HotelRoomMapForm.room_ids.value.split(',');
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
			
			'reservation':{'text':'[[.reservation.]]','url':'?page=reservation&cmd=add&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string(),
				'privileges':['BOOKED'],'statuses':['AVAILABLE','SHORT_TERM','BOOKED','OCCUPIED','CHECKOUT','RESOVER','OVERDUE']},
			'edit_reservation':{'text':'[[.edit_reservation.]]','url':'?page=reservation&cmd=edit&id='+reservation_id,
				'privileges':['BOOKED'],'statuses':['BOOKED','OCCUPIED','CHECKOUT'],'reservation_statuses':['BOOKED','CHECKIN','CHECKOUT','CANCEL']}
		}
		if(document.HotelRoomMapForm.room_ids.value != '')
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
		if(document.HotelRoomMapForm.room_ids.value != '')
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
		if(document.HotelRoomMapForm.room_ids.value!='')
		{
			var rooms = document.HotelRoomMapForm.room_ids.value.split(',');
            
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
		<!--LIST:room_levels-->
		query_string += '&room_prices['+[[|room_levels.id|]]+']=[[|room_levels.price|]]';
		<!--/LIST:room_levels-->
		return query_string;
	}
    function canCheckin()
    {
        if(document.HotelRoomMapForm.room_ids.value!='')
        {
			var rooms = document.HotelRoomMapForm.room_ids.value.split(',');
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
                //jQuery('#room_'+rooms[i]).parent().children('span').html();
                break; 
            }	
		}
        if(!can_in)
        {
            alert('ROOM DIRTY');
            return false;
        }
        else 
            return true;
    }
	function buildReservationUrl(type)
    {
		if(type=='RFA')
        {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add'));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		} 
        else if(type=='RFW')
        {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN','reservation_type_id'=>4));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		} 
        else 
        {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN'));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		}
	}
    function buildRoommapUrl(type)
    {
        <?php if(Url::get('view_layout')=='simple'){ ?>
            window.location='<?php echo Url::build('room_map');?>';
        <?php }else{ ?>
            window.location='<?php echo Url::build('room_map',array('view_layout'=>'simple'));?>';
        <?php } ?>
    }
	function selectRoomLevel(index,roomLevelId,roomLevelName,inputCount){
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
			opener.document.getElementById('departure_time_'+index).value = '<?php echo [[=end_day=]].'/'.[[=end_month=]].'/'.[[=end_year=]]?>';
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
        if(jQuery('#phone_booker').val()!='')
		{
			url+='&phone_booker='+jQuery('#phone_booker').val();
		}
        if(jQuery('#start_date').val()!="" && jQuery('#end_date').val()!="")
        {
            var start_date = jQuery('#start_date').val();
            var start_date_timestamp = new Date(start_date.split("/").reverse().join("/")).getTime();
            var end_date = jQuery('#end_date').val();
            var end_date_timestamp = new Date(end_date.split("/").reverse().join("/")).getTime();
            if(start_date_timestamp>end_date_timestamp)
            {
                alert("Thời gian đi phải nhỏ hơn thời gian đến");
                return false;
            }
        }

        if(jQuery('#start_date').val()!='')
		{
			url+='&start_date='+jQuery('#start_date').val();
		}
        if(jQuery('#end_date').val()!='')
		{
			url+='&end_date='+jQuery('#end_date').val();
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
	function check_from_date(){
	  
        var from_date = $('arrival_time').value.split("/");
       // from_date = from_date[1]+"/"+from_date[0]+"/"+from_date[2]; 
      //  var from_time = Date.parse(from_date.toString());
        //Cong 1 tuan le (ms nen * 1000)
       // var to_time = to_numeric(from_time);
       // var to_date = new Date(to_time);
      //  to_date = ((to_date.getDate()+1)<10?"0" + (to_date.getDate()+1):(to_date.getDate()+1)) + "/" +
		//((to_date.getMonth()+1)<10?"0"+ (to_date.getMonth()+1) :(to_date.getMonth()+1)) + "/" +
		//to_date.getFullYear();
        
        //Luu Nguyen Giap add to_date
        var day_in_month = Date.getDaysInMonth(from_date[2], from_date[1]-1);//getDaysInMonth(year,month-1)
        var to_date =  day_in_month + "/" + from_date[1] + "/" + from_date[2];
        //End Luu Nguyen Giap
        $('departure_time').value = to_date;
	}
    
    function check_validate_time()
    {
        var xdeparture_time = jQuery('#departure_time').val();
        var xarrival_time   = jQuery('#arrival_time').val();
        if(xdeparture_time<xarrival_time){
            alert('D? li?u th?i gian nh?p khï¿½ng chï¿½nh xï¿½c!');
        }
    }
     
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
    
    function close_window_fun()
    {
        location.reload();
        jQuery(".window-container").fadeOut();
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
         
	
    function validText() 
    {
        var chaos = new Array ("'","~","@","#","$","%","^","&","*",";","/","\\","|");
        var sum = chaos.length;
        for (var i in chaos) {if (!Array.prototype[i]) {sum += jQuery('.valid_note').val().lastIndexOf(chaos[i])}}
        if (sum) 
        {
            alert("Lï¿½u ? khï¿½ng nh?p k? t? ï¿½?t bi?t !@# % $ trong ghi chï¿½: @ # $ % ^ * ~ ");
            jQuery('.valid_note').val(jQuery('.valid_note').val().substr(0,jQuery('.valid_note').val().length-1));	
            jQuery('.valid_note').focus();
            return false;
        }
        return true;
    }
  </script>
