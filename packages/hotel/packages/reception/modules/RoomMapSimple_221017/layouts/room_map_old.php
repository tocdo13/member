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
            	[[.happy_birth_day_to.]]
            <!--ELSE-->    
                [[.one_day_left_to_birthday.]]
            <!--/IF:cond_bifth-->
             [[|birth_date_arr.name|]] - P.[[|birth_date_arr.room_name|]] ([[|birth_date_arr.birth_date|]])</span><!--/LIST:birth_date_arr-->
         </marquee>
	<hr size="1" color="#9DC9FF">	
	<!--/IF:birth_date_cond-->
	<table width="100%" cellspacing="0" cellpadding="3">
	<tr valign="top">
	<td width="1%" align="left">
		<div id="room_map_left_utils">
			<fieldset><legend class="title"><b>[[.select_date.]]</b></legend>
			<table border="0" id="check_availability_table">
            <tr>
            	<td width="100"><b>[[.viewing_date.]]:</b></td>
            	<td> <input name="in_date" type="text" id="in_date" class="date-input" onChange="HotelRoomMapForm.submit()"></td>
            </tr>
		  </table>
		  </fieldset><br />
          <fieldset>
			<legend class="title">[[.forcecast.]]</legend>
				<table border="0" cellpadding="2">
				  	<tr class="checkin-today" onClick="window.open('?page=arrival_list');">
						<td align="left"><?php echo ([[=total_checkin_today_room=]]+ [[=total_books_without_room=]] + [[=total_change_room1=]] );//echo ([[=total_checkin_today_room=]]+ [[=total_books_without_room=]]);//echo ( [[=total_checkin_today_room=]] + [[=total_books_without_room=]]);?> [[.check_in_today.]]</td>
					</tr>
                    <tr class="checkin-today" onClick="window.open('?page=arrival_list');">
						<td align="left">(<?php echo ([[=total_dayuse_today=]]);?> [[.total_dayused.]])</td>
					</tr>
				 	<tr class="checkin-today" onClick="window.open('?page=departure_list');">
						<td align="left">[[|total_checkout_today_room|]] [[.check_out_today.]]</td>
					</tr>
				 	<tr class="checkin-today" onClick="window.open('?page=room_status_report');">
						<td align="left"><?php echo([[=total_books_without_room=]]+[[=total_occupied_today=]]+[[=total_checkin_today_room=]] + [[=total_change_room=]]);//echo([[=total_books_without_room=]] + [[=total_dayuse_today=]] + [[=total_occupied_today=]] + [[=total_booked_room=]]+ [[=total_overdue_booked_room=]] );//echo([[=total_books_without_room=]] + [[=total_dayused_room=]] + [[=total_occupied_today=]]+[[=total_checkin_today_room=]] )//[[=total_booked_room=]]echo([[=total_occupied_today=]]+ [[=total_books_without_room=]] + [[=total_checkin_today_room=]] - [[=total_dayuse_today=]]) //echo [[=total_checkin_room=]];echo [[=total_checkin_room=]]; ?> [[.total_occ_and_arr.]]</td>
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
            	<td width="100"><b>[[|ebs.name|]]:</b></td>
                <td>[[|ebs.total_quantity|]]</td>
            	<td>[[|ebs.quantity|]]</td>
            </tr>
            <!--/LIST:ebs-->
			</table>            	
            </fieldset> <br />
		<!--IF:edit_reservation(USER::can_view(false,ANY_CATEGORY))-->
        <fieldset><legend class="title"><b>[[.search_booking.]]</b></legend> 
		<table cellpadding="2" width="100%" class="room-map-customer-search-box" style="border:0px;">
            <!-- start: Manh them truong tim kiem theo phong  -->
            <tr>
			  <td nowrap="nowrap" align="right">[[.room.]] </td>
			  <td><input name="number_room" type="text" id="number_room" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
            </tr>
            <!-- end: Manh them truong tim kiem theo phong  -->  
			<tr>
			  <td nowrap="nowrap" align="right">[[.RE_code.]] </td>
			  <td><input name="code" type="text" id="code" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>              
			<tr>
			  <td nowrap="nowrap" align="right">[[.booking_code.]] </td>
			  <td><input name="booking_code" type="text" id="booking_code" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			  </tr>
			<tr><td nowrap="nowrap" align="right">[[.company_name.]] </td>
				<td width="100%">
					<input type="text" id="customer_name" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/></td>
			</tr>
			<!--<tr><td nowrap="nowrap" align="right">[[.company_name.]]: </td>
				<td width="100%">
					<input type="text" id="company_name" style="width:100px;" onKeyPress="if(event.keyCode==13)window.open('?page=reservation&booking_resource='+this.value)"/>				</td>
			</tr>-->
			<tr>
			  <td nowrap="nowrap" align="right">[[.traveller_name.]] </td>
			  <td><input name="traveller_name" type="text" id="traveller_name" style="width:100px;" onKeyPress="if(event.keyCode==13){buildReservationSearch();}"/>
              </td>
			  </tr>
			<tr>
			  <td nowrap="nowrap" align="right">[[.group_note_room.]]</td>
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
            <tr>
                <td></td>
                <td><input type="button" name="search-booking" onclick="buildReservationSearch();" value="OK" /></td>
            </tr>
		</table>
        </fieldset> <br />
		<!--/IF:edit_reservation-->
		
        <fieldset><legend class="title"><b>[[.check_availability.]]</b></legend> 
			<table id="check_availability_table">
			  <tr>
				<td width="100">[[.arrival_time.]]:</td>
				<td><input name="arrival_time" type="text" id="arrival_time" class="date-input"  readonly="readonly" onchange="changevalue();" /></td>
			  </tr>
			  <tr>
				<td>[[.departure_time.]]:</td>
				<td><input name="departure_time" type="text" id="departure_time" class="date-input"  readonly="readonly" onchange="changefromday();" /></td>
			  </tr>
			
			  <tr>
				<td>[[.room_type.]]:</td>
				<td><select name="room_level_id" id="room_level_id" style="width:100px;"></select></td>
			  </tr>
				<tr><td></td><td>
				<input type="button" value="[[.Go.]]" onClick="CheckValidate();">
			     
            </td></tr>
            
			</table>
		</fieldset> <br>       
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
                	<a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=books_without_room.reservation_id=]]));?>" style="color:#F00;">[[.cut_off_day_cancel.]] [[|books_without_room.customer_name|]] - [[|books_without_room.room_level|]] ([[|books_without_room.arrival|]])</a><br />
                <!--/IF::cond_cut_of_date-->
            <!--/LIST:books_without_room-->
         	</marquee> 
		</fieldset><br />
		<fieldset style="border:1px solid #9DC9FF">
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
                <tr>
				<td><div class="room_arround"><a href="#" class="room OVERDUE_BOOKED" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td>[[.overdue_booked.]] ([[|total_overdue_booked_room|]])</td>
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
				<td style="border-top:1px solid #CCC;background:#EFEFEF;"><div class="room_arround"><a href="#" class="room" style="width:16px;height:16px;margin-right:2px; border: 4px solid #00b2f9; background-color: #fde0c5;">&nbsp;</a></div></td>
				<td style="border-top:1px solid #CCC;background:#EFEFEF;">[[.checkin_change_today.]] (<?php echo [[=total_change_room_today=]];?>)</td>
				</tr>
			  <tr>  
			  <tr>
				<td style="border-top:1px solid #CCC;background:#EFEFEF;"><div class="room_arround"><a href="#" class="room DAYUSED" style="width:16px;height:16px;margin-right:2px;">&nbsp;</a></div></td>
				<td style="border-top:1px solid #CCC;background:#EFEFEF;">[[.checkin_today.]] (<?php echo [[=total_dayused_room=]] +[[=total_checkin_today=]] ;?>)</td>
				</tr>
			  <tr>
				<td style="border-bottom:1px solid #CCC;background:#EFEFEF;"><div class="room_arround"><a href="#" class="room OVERDUE" style="width:16px;height:16px;margin-right:2px;"></a></div></td>
				<td style="border-bottom:1px solid #CCC;background:#EFEFEF;">[[.overdue.]] ([[|total_overdue_room|]])</td>
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
			<table cellpadding="0" width="100%" class="room-map-tour-list">
			<tr>
				<!---<td class="title">[[.tour_list.]]</td>--->
			</tr>
			<tr>
				<td align="left">
					<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#9DC9FF">
					  <!---<tr valign="top" bgcolor="#EFEFEF">
						<th>[[.no.]]</th>
						<th>[[.tour_name.]]</th>
						<th style="font-size:11px;" bgcolor="#71AAFF">[[.b.]]</th>
						<th style="font-size:11px;" bgcolor="#FFCC33">[[.in.]]</th>
						<th style="font-size:11px;" bgcolor="#FF66FF">[[.out.]]</th>
					  </tr>--->
					  <!--LIST:tours-->
					  <!---<tr>
						<td style="font-size:11px;">[[|tours.i|]]</td>
						<td style="font-size:11px;"><?php if([[=tours.name=]] !=''){echo [[=tours.name=]];}else if([[=tours.customer_name=]]!=''){echo [[=tours.customer_name=]];}else {echo [[=tours.tour_name=]];}?><a title="[[.view_reservation.]]" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=tours.reservation_id=]]));?>" class="room-map-view-reservation"><img src="packages/core/skins/default/images/cmd_Tim.gif" /></a><BR><span style="font-size:10px;">([[.arrival_time.]]: [[|tours.arrival_time|]])</span></td>
						<td style="font-size:11px;" bgcolor="#71AAFF">[[|tours.room_booked|]]</td>
						<td style="font-size:11px;" bgcolor="#FFCC33">[[|tours.room_checkin|]]</td>
						<td style="font-size:11px;" bgcolor="#FF66FF">[[|tours.room_checkout|]]</td>
					  </tr>--->
					  <!--/LIST:tours-->
					</table></td>			
			</tr>
		</table>
	</div></td>
	<td bgcolor="#FFFFFF" align="left" valign="top" id="room_map_toogle" width="1%"><img id="room_map_toogle_image" src="packages/core/skins/default/images/paging_left_arrow.gif" style="cursor:pointer;" onClick="if(jQuery.cookie('collapse')==1){jQuery.cookie('collapse','0');jQuery('#room_map_left_utils').fadeOut();this.src='packages/core/skins/default/images/paging_right_arrow.gif';}else{jQuery.cookie('collapse','1');jQuery('#room_map_left_utils').fadeIn();this.src='packages/core/skins/default/images/paging_left_arrow.gif'}"></td>
	<td bgcolor="#FFFFFF" align="left" width="99%">
		<div id="information_bar"></div>
		<!--IF:cond(User::can_view(false,ANY_CATEGORY))-->
		<input type="button" value="[[.New_reservation.]]" onClick="buildReservationUrl('RFA');" class="button-medium booked" id="lt1"/>
        <input type="button" value="[[.Walk_in.]]" onClick="if(canCheckin()) buildReservationUrl('RFW');" class="button-medium booked" id="lt2"/>
        <input type="button" value="[[.full_screen.]]" onClick="switchFullScreen();" class="button-medium fullscreen" id="lt3; full_screen_button"/>
        
        <!--IF:cond_module(User::can_view(MODULE_MANAGENOTE,ANY_CATEGORY))-->
        <input type="button" value="[[.Reservation_note.]]" onClick="window.open('?page=manage_note');" class="button-medium booked" style="float:right;" id="lt4"/>
        <!--/IF:cond_module-->
        <br clear="all"><br />
		<!--/IF:cond-->
		<div class="body">
		<table width="100%" border="1" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#CCCCCC">
			<!--LIST:floors-->	
			<!-- onmouseover="this.bgColor='#B7D8FF'" onmouseout="this.bgColor='#FFFFFF'" -->	
			<tr valign="middle">
				<td width="40px" style="text-transform:uppercase;color:#FFFFFF;<?php if(substr([[=floors.name=]],0,1)=='A'){echo 'background:#82BAFF;';}else{ echo 'background:#5b90e7;';}?>" nowrap="nowrap"><b>[[|floors.name|]]</b></td>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>
                    <?php //if(User::id()=='developer05') System::debug([[=floors.rooms=]]); ?>
						<!--LIST:floors.rooms-->
							  <div title="<?php echo addslashes([[=floors.rooms.note=]]);?>" class="room-bound">
							  	<span class="room-name"><strong><span style="font-size:14px;color:#0000FF;">[[|floors.rooms.name|]]</span>-[[|floors.rooms.type_name|]]</strong><br /></span><br clear="all">
                                <input type="hidden" value="[[|floors.rooms.id|]]" id="[[|floors.rooms.id|]]"/>
								<a href="#" id="room_[[|floors.rooms.id|]]" class="room <?php echo ([[=floors.rooms.house_status=]]=='REPAIR' || [[=floors.rooms.status=]] =='CHECKOUT')?[[=floors.rooms.house_status=]]:[[=floors.rooms.status=]];?>"  
                                
                                <?php
                                /** Mạnh fix trường hợp đổi phòng đã ở qua đêm **/
                                if(([[=floors.rooms.status=]]!='CHECKOUT') AND ([[=floors.rooms.status=]]!='BOOKED')){
                                    foreach([[=floors.rooms.reservations=]] as $m_key=>$m_value){
        							    if(($_REQUEST['in_date']==$m_value['arrival_time']) AND ($m_value['change_room_from_rr']!='') AND ($m_value['status']=='DAYUSED')){
        							        echo 'style="border: 4px solid #00b2f9; background-color: #fde0c5;"';
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
										echo 'onclick="select_room('.[[=floors.rooms.id=]].', document.HotelRoomMapForm);update_room_info();return false;"';
									}
									?>>
									<!--IF:room_level(Url::get('cmd')=='select' and ([[=floors.rooms.status=]]=='AVAILABLE' OR [[=floors.rooms.status=]]=='CHECKOUT'))-->
                                        <span title="[[.select_room.]]"><img src="packages/core/skins/default/images/active.gif" width="12" height="12"></span>
                                    <!--/IF:room_level-->
                                    <!--IF:time(isset([[=floors.rooms.time_in=]]) and [[=floors.rooms.time_in=]] and [[=floors.rooms.status=]] != 'CHECKOUT')-->
									   <span style="font-size:11px;text-decoration:underline;color:#003399;font-weight:bold;"><?php echo (date('d/m/Y',[[=floors.rooms.time_in=]])==date('d/m/Y',[[=floors.rooms.time_out=]]))?date('H:i',[[=floors.rooms.time_in=]]):date('d/m',[[=floors.rooms.time_in=]]);?> - <?php echo (date('d/m/Y',[[=floors.rooms.time_in=]])==date('d/m/Y',[[=floors.rooms.time_out=]]))?date('H:i',[[=floors.rooms.time_out=]]):date('d/m',[[=floors.rooms.time_out=]]);?></span><br />
									<!--/IF:time-->
                                    <!--IF:time2([[=floors.rooms.house_status=]] == 'HOUSEUSE')-->
									   <!--LIST:floors.rooms.reservations-->
                                            <!--IF:time3(isset([[=floors.rooms.reservations.start_date=]]) and isset([[=floors.rooms.reservations.end_date=]]))-->
                                                <span style="font-size:9px;text-decoration:underline;color:#003399;font-weight:bold;"><?php echo substr([[=floors.rooms.reservations.start_date=]],0,5);?> - <?php echo substr([[=floors.rooms.reservations.end_date=]],0,5);?></span><br />
                                            <!--IF:time3-->
                                       <?php break;?>
									   <!--/LIST:floors.rooms.reservations-->
                                    <!--/IF:time2-->
									<span id="house_status_[[|floors.rooms.id|]]" style="font-size:9px;font-weight:normal;color:red;">[[|floors.rooms.house_status|]]</span>
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
										echo '<span style="font-size:9px;background-color:'.[[=floors.rooms.color=]].';" title="'.[[=floors.rooms.tour_name=]].'">'.[[=floors.rooms.tour_name=]].'</span><br>';
									}
									?>
									
									
                                    
                                    <?php if(isset([[=floors.rooms.customer_name=]]) and [[=floors.rooms.customer_name=]] and [[=floors.rooms.status=]] != 'CHECKOUT')
									{
										echo '<span style="font-size:9px;background-color:'.[[=floors.rooms.color=]].';" title="'.[[=floors.rooms.customer_name=]].'">'.[[=floors.rooms.customer_name=]].'</span><br>';
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
							</div>	
					<!--/LIST:floors.rooms-->
					</td>
				  </tr>
				</table></td>
			</tr>
			<!--/LIST:floors-->	
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
<script type="text/javascript">
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
								<!--IF:edit_reservation(USER::can_edit($this->get_module_id('Reservation'),ANY_CATEGORY))--> 
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
			//information += '[[.house_status.]]: <select  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option><option value="HOUSEUSE">HOUSEUSE</option></select><input type="submit" value="Change" class="hk-status-button"/>';
			//KimTan: dau chon repair neu phong dang co cac trang thai
            if((room_reservations)){
                var tan_check = 0;
                for(var tan in room_reservations){
                    //console.log(room_reservations[tan]);
                    if(room_reservations[tan]['reservation_status']!='CHECKOUT'){
                        tan_check = 1; break;
                    }
                }
                 if(tan_check==1)
                 {
                    information += '[[.house_status.]]: <select  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="DIRTY">DIRTY</option><option value="HOUSEUSE">HOUSEUSE</option></select><input type="submit" value="Change" class="hk-status-button" />';
                 } 
                else{
                    information += '[[.house_status.]]: <select  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option><option value="HOUSEUSE">HOUSEUSE</option></select><input type="submit" value="Change"  class="hk-status-button" />';
                }
                
            }else{
                information += '[[.house_status.]]: <select  name="house_status" id="house_status" onclick=" jQuery(\'#div_date_repair\').css(\'display\',\'\');jQuery(\'#repair_to\').datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: \'-100:+4\'}); jQuery(\'#ui-datepicker-div\').css(\'z-index\',\'3000\');"><option value="">READY</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option><option value="HOUSEUSE">HOUSEUSE</option></select><input type="submit" value="Change"  class="hk-status-button" />';
            }
            // end KimTan
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
			jQuery(".room-bound").click(function(e)
            {
				if(e.ctrlKey==false && e.shiftKey==false)
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
						footerContent: '<a style="color:#333333;font-size:11px;" onclick="buildReservationUrl(\'RFA\');">[[.reserve_for_agent.]]<a> | <a style="color:#333333;font-size:11px;" onclick="if(canCheckin()) buildReservationUrl(\'RFW\');">[[.Walk_in.]]<a>',
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
			query_string += rooms[i]+','+'[[|day|]]/[[|month|]]/[[|year|]]'+','+'[[|end_day|]]/[[|end_month|]]/[[|year|]]';
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
            alert('Phòng đang DIRTY');
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
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN','reservation_type_id'=>2));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		} 
        else 
        {
			window.location='<?php echo Url::build('reservation',array('cmd'=>'add','status'=>'CHECKIN'));?>&time_in=<?php echo CHECK_IN_TIME;?>&time_out=<?php echo CHECK_OUT_TIME;?>&rooms='+get_query_string();
		}
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
            alert('Dữ liệu thời gian nhập không chính xác!');
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

    function changevalue(){
        var myfromdate = jQuery("#arrival_time").val().split("/");
        var mytodate = jQuery("#departure_time").val().split("/");
        var myfromdate_arr = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var mytodate_arr = new Date(mytodate[2],mytodate[1],mytodate[0]);
        //console.log(mytodate_arr+"--")
        if(mytodate==''){
            jQuery("#departure_time").val(jQuery("#arrival_time").val());
        }else{
            if(mytodate_arr<myfromdate_arr){
                jQuery("#departure_time").val(jQuery("#arrival_time").val());
            }
        }
        /*if(myfromdate[2] > mytodate[2]){
            $('departure_time').value =$('arrival_time').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('departure_time').value =$('arrival_time').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('departure_time').value =$('arrival_time').value;
                }
            }
        }*/
    }
    function changefromday(){
        var myfromdate = jQuery("#arrival_time").val().split("/");
        var mytodate = jQuery("#departure_time").val().split("/");
        var myfromdate_arr = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var mytodate_arr = new Date(mytodate[2],mytodate[1],mytodate[0]);
        //console.log(myfromdate_arr);
        //console.log(mytodate_arr);
        if(myfromdate==''){
            jQuery("#arrival_time").val(jQuery("#departure_time").val());
        }else{
            if(mytodate_arr<myfromdate_arr){
                jQuery("#arrival_time").val(jQuery("#departure_time").val());
            }
        }
        /*if(myfromdate[2] > mytodate[2]){
            $('arrival_time').value= $('departure_time').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('arrival_time').value = $('departure_time').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('arrival_time').value =$('departure_time').value;
                }
            }
        }*/
    }
         
	
  </script>