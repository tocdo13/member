<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
</script>
<div id="mask" class="mask">[[.Please wait.]]...</div>
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
			<fieldset>
            <legend class="title"><b>[[.filter_by_date.]]</b></legend>
			 <table>
                 <tr>
                     <td style="width: 100px;">[[.from_date.]]</td>
                     <td>
                     <input  name="from_time" style=" display:none;width:30px; font-size:10px;" type="text" id="from_time" title="00:00" maxlength="5" /> 
                     <input name="from_date" type="text" id="from_date" class="date-input" onchange="check_value_date(true);" style="height: 25px; width: 90px;" />
                     </td>
                 </tr>
                 <tr>
                 <td>[[.to_date.]]</td>
                 <td>
                 <input  name="to_time" style=" display:none;width:30px; font-size:10px;" type="text" id="to_time" title="00:00" maxlength="5" />  
                 <input name="to_date" type="text" id="to_date" class="date-input" onchange="check_value_date(false);" style="height: 25px; width: 90px;"/>
                 </td>
                 </tr>
                 <tr>
                    <td colspan="2" align="right"><input name="search_date" type="button" value="[[.search.]]" style="width:90px; height: 25px;" onclick="HotelRoomAvailabilityForm.submit();"></td>
                 </tr>
             </table> 	  
		  </fieldset><br />
<fieldset style="border:1px solid #9DC9FF; display: none;">
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
                [<a class="notice" href="<?php echo Url::build_current(array('cmd','object_id','act','input_count','from_date','to_date','r_r_id','room_level_id'=>[[=room_levels.id=]],'room_level_name_current'=>Url::get('room_level_name'),'room_level_id_old'));?>">[[.filter.]]</a>]
                
            </div><br />
            
            <!--ELSE-->
            <div class="row-even"><input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly">&nbsp;[[|room_levels.name|]] <b>(0)</b>|<span style="color:red;"> [ Hết phòng]</span></div><br />
            <!--/IF:cond_room_level-->
			<!--/LIST:room_levels-->
		</fieldset>
		<!--/IF:cond-->
			
	</div></td>
	<td bgcolor="#FFFFFF" align="left" valign="top" id="room_map_toogle" width="1%"><img id="room_map_toogle_image" src="packages/core/skins/default/images/paging_left_arrow.gif" style="cursor:pointer;" onClick="if(jQuery.cookie('collapse')==1){jQuery.cookie('collapse','0');jQuery('#room_map_left_utils').fadeOut();this.src='packages/core/skins/default/images/paging_right_arrow.gif';}else{jQuery.cookie('collapse','1');jQuery('#room_map_left_utils').fadeIn();this.src='packages/core/skins/default/images/paging_left_arrow.gif'}"></td>
	<td bgcolor="#FFFFFF" align="left" width="99%">
		<div id="information_bar"></div>
		<!--IF:cond(User::can_view(false,ANY_CATEGORY))-->
		<input type="button" value="[[.New_reservation.]]" onClick="buildReservationUrl('RFA');" class="button-medium booked" style="display: none;" />
        <input type="button" value="[[.Walk_in.]]" onClick="buildReservationUrl('RFW');" class="button-medium booked" style="display: none;" />
        <input type="button" value="[[.full_screen.]]" id="full_screen_button" onClick="switchFullScreen();" class="button-medium fullscreen" style="display: none;"/>
        <!--IF:cond_module(User::can_view(MODULE_MANAGENOTE,ANY_CATEGORY))-->
        <input type="button" value="[[.Reservation_note.]]" onClick="window.open('?page=manage_note');" class="button-medium booked" style="float:right; display: none;"/>
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
														opener.document.getElementById(\'arrival_time_'.URL::get('object_id').'\').value = \''.[[=day=]].'/'.[[=month=]].'/'.[[=year=]].'\';
														opener.document.getElementById(\'departure_time_'.URL::get('object_id').'\').value = \''.[[=end_day=]].'/'.[[=end_month=]].'/'.[[=end_year=]].'\';
														oldRoomLevelId = '.(Url::get('room_level_id_old')?Url::get('room_level_id_old'):0).';
														if(!opener.document.getElementById(\'id_'.URL::get('object_id').'\').value)
															opener.document.getElementById(\'price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.price=]].'\';
														';
                                                        
                                                        //giap.ln comment hien thi lai arrival time & departure time 
														//if(!opener.document.getElementById(\'departure_time_'.URL::get('object_id').'\').value)
                                                        //if(!opener.document.getElementById(\'arrival_time_'.URL::get('object_id').'\').value)
                                                        //end giap.ln
														if(Url::get('price') && Url::get('price')<[[=floors.rooms.price=]]){
															//echo 'opener.document.getElementById(\'price_'.URL::get('object_id').'\').value=\''.[[=floors.rooms.price=]].'\';';	
														}
                                        if(CHANGE_ROOM_BOOKED=='ALWAY' || Url::get('room_level_name')==''){               
										echo 'opener.count_price('.URL::get('object_id').',true); customer_id_temp = opener.document.getElementById(\'customer_id\').value; opener.get_price_rate_code(customer_id_temp,\''.URL::get('object_id').'\');';
										}
                                        else if(( Url::get('room_level_name_current') && CHANGE_ROOM_BOOKED=='SAME' && [[=floors.rooms.room_level_name=]] != Url::get('room_level_name_current')) || ( Url::get('room_level_name') && CHANGE_ROOM_BOOKED=='SAME' && [[=floors.rooms.room_level_name=]] != Url::get('room_level_name')))
                                        {
                                            echo 'opener.count_price('.URL::get('object_id').',true); customer_id_temp = opener.document.getElementById(\'customer_id\').value; opener.get_price_rate_code(customer_id_temp,\''.URL::get('object_id').'\');';
                                        }
                                        //else if(CHANGE_ROOM_BOOKED=='SAME' && )
                                        echo 'opener.updateRoomForTraveller('.URL::get('object_id').');window.close();"';
									}
									else
									{
										echo 'onclick="select_room('.[[=floors.rooms.id=]].', document.HotelRoomAvailabilityForm);update_room_info();return false;"';
									}
									?>>
									<!--IF:room_level(Url::get('cmd')=='select' and ([[=floors.rooms.status=]]=='AVAILABLE' OR [[=floors.rooms.status=]]=='CHECKOUT' OR [[=floors.rooms.status=]]=='EXPECTED_CHECKOUT'))--><!--/IF:room_level--><!--IF:time(isset([[=floors.rooms.time_in=]]) and [[=floors.rooms.time_in=]] and [[=floors.rooms.status=]] != 'CHECKOUT')-->
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
            <?php if($count == 0){echo '<strong> <span id="message_room" style="font-size:16px; color:red;">Lo?i ph�ng n�y d� h?t!</span></strong>';}?>
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
	jQuery('#arrival_time').datepicker();
	jQuery('#departure_time').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#from_date').datepicker();//{ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
	jQuery('#to_date').datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
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
								information += (room_reservations[j]['status']=='OVERDUE' || room_reservations[j]['status']=='OCCUPIED' || room_reservations[j]['status']=='PREPARE_FOR_CHECKOUT' || room_reservations[j]['status']=='EXPECTED_CHECKOUT' || room_reservations[j]['status']=='DAYUSED' || room_reservations[j]['status']=='BOOKED')?'<tr><td></td><td width="1%"></td><td class="value"><a href="#" onClick="openWindowUrl(\'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>/form.php?block_id=<?php echo BLOCK_UPDATE_TRAVELLER;?>&cmd=add_traveller&rr_id='+room_reservations[j]['reservation_room_id']+'&r_id='+room_reservations[j]['reservation_id']+'\',Array(\'add_traveller_'+room_reservations[j]['reservation_room_id']+'\',\'[[.list_traveller.]]\',\'20\',\'110\',\'1100\',\'570\'));closeAllWindows();" class="room-map-edit-link"> [[.list_guest.]]</a></td></tr>':'';
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
        //giap.ln comment hien thi lai arrival time & departure time 
		//if(!opener.document.getElementById('arrival_time_'+index).value)
		opener.document.getElementById('arrival_time_'+index).value = '<?php echo [[=day=]].'/'.[[=month=]].'/'.[[=year=]];?>';
		//if(!opener.document.getElementById('departure_time_'+index).value)
		opener.document.getElementById('departure_time_'+index).value = '<?php echo [[=end_day=]].'/'.[[=end_month=]].'/'.[[=end_year=]]?>';
        //end giap.ln
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
    //giap.luunguyen add check from_date and to_date search
    function check_value_date(flag)
    {
        var from_date = jQuery("#from_date").val().split("/");
        var to_date = jQuery("#to_date").val().split("/");
        var from_date_arr = new Date(from_date[2],from_date[1],from_date[0]);
        var to_date_arr = new Date(to_date[2],to_date[1],to_date[0]);
        if(to_date_arr==''){
            if(flag)
            {
                //tu ngay => tinh den ngay = tu ngay + 1
                 var from_date = document.getElementById('from_date').value;
                 var from_date = Date.parseExact(from_date,"dd/MM/yyyy");
                 var to_date = from_date.add(1).day();
                 var str_to_date = to_date.toString('dd/MM/yyyy');
                 document.getElementById('to_date').value = str_to_date;
            }
            else
            {
                //den ngay => tu ngay = den ngay -1
                var to_date = document.getElementById('to_date').value;
                var to_date = Date.parseExact(to_date,"dd/MM/yyyy");
                var from_date = to_date.add(-1).day();
                var str_from_date = from_date.toString('dd/MM/yyyy');
                document.getElementById('from_date').value = str_from_date;
            }                               
        }
        else
        {
            if(to_date_arr<=from_date_arr){
                if(flag)
                {
                    //tu ngay => tinh den ngay = tu ngay + 1
                     var from_date = document.getElementById('from_date').value;
                     var from_date = Date.parseExact(from_date,"dd/MM/yyyy");
                     var to_date = from_date.add(1).day();
                     var str_to_date = to_date.toString('dd/MM/yyyy');
                     document.getElementById('to_date').value = str_to_date;
                }
                else
                {
                    //den ngay => tu ngay = den ngay -1
                    var to_date = document.getElementById('to_date').value;
                    var to_date = Date.parseExact(to_date,"dd/MM/yyyy");
                    var from_date = to_date.add(-1).day();
                    var str_from_date = from_date.toString('dd/MM/yyyy');
                    document.getElementById('from_date').value = str_from_date;
                } 
            }
        }
    }
    //end giap.luunguyen
    
  </script>
