<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	room_levels = <?php echo String::array2js([[=room_levels=]]);?>;
</script>
<form method="post" name="HotelRoomAvailabilityForm">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="room-map-table-bound">
<tr>
<td class="calendar-bound">
	<table width="100%" cellspacing="0" cellpadding="3">
	   <tr valign="top">
	       <td width="1%" align="left">
		      <div id="room_map_left_utils" style="width:250px;">
    			<fieldset>
                    <legend class="title"><b>[[.view_date.]]</b></legend>
        			 <table>
                         <tr>
                             <td>[[.from_date.]]</td>
                             <td>
                                 <input  name="from_time" style=" display:none;width:30px; font-size:10px;" type="text" id="from_time" title="00:00" maxlength="5" /> 
                                 <input name="from_date" type="text" id="from_date" class="date-input" onchange="check_value_date(true);" />
                             </td>
                         </tr>
                         <tr>
                            <td>[[.to_date.]]</td>
                            <td>
                                <input  name="to_time" style=" display:none;width:30px; font-size:10px;" type="text" id="to_time" title="00:00" maxlength="5" />  
                                <input name="to_date" type="text" id="to_date" class="date-input" onchange="check_value_date(false);" />
                            </td>
                         </tr>
                         <tr>
                            <td colspan="2" align="center"><input name="search_date" type="button" value="[[.search.]]" style="width:80px; margin:auto;" onclick="HotelRoomAvailabilityForm.submit();"></td>
                         </tr>
                     </table> 	  
                </fieldset>
                
                <br />
                       
            	<!--IF:cond(Url::get('cmd')=='select')-->
                	<fieldset>
                		<legend class="title" style="font-size:12px;">[[.select_room_level.]]</legend>
                		[<a href="<?php echo Url::build_current(array('cmd','object_id','act','from_date','r_r_id','to_date','input_count'));?>"><b>[[.all.]]</b></a>]<br /><br />
                		<!--LIST:room_levels-->
                            <!--IF:cond_room_level([[=room_levels.vacant_room=]]>0 or [[=over_book=]])-->
                    		<div class="row-even">
                                <input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly">
                                &nbsp;
                                <a href="#" onclick="selectRoomLevel(<?php echo Url::iget('object_id');?>,'[[|room_levels.name|]]',[[|room_levels.id|]],<?php echo Url::iget('input_count');?>,<?php echo Url::iget('max');?>)">
                                [[|room_levels.name|]] 
                                    <b>([[|room_levels.vacant_room|]])</b>
                                </a> |
                                [<a class="notice" href="<?php echo Url::build_current(array('cmd','object_id','act','input_count','from_date','to_date','r_r_id','room_level_id'=>[[=room_levels.id=]],'room_level_name'=>Url::get('room_level_name'),'room_level_id_old'));?>">[[.filter.]]</a>]
                                
                            </div>
                            <br />
                            <!--ELSE-->
                            <div class="row-even"><input  name="room_level_[[|room_levels.id|]]" type="text" id="room_leel_[[|room_levels.id|]]" style="width:20px; display:none;" title="[[.room_quantity.]]" value="1" readonly="readonly">&nbsp;[[|room_levels.name|]] <b>(0)</b>|<span style="color:red;"> [[[.room_zero.]]]</span></div><br />
                            <!--/IF:cond_room_level-->
                		<!--/LIST:room_levels-->
                	</fieldset>
            	<!--/IF:cond-->
             </div>
        </td>
        <td bgcolor="#FFFFFF" align="left" valign="top" id="room_map_toogle" width="1%">
            <img id="room_map_toogle_image" src="packages/core/skins/default/images/paging_left_arrow.gif" style="cursor:pointer;" onClick="if(jQuery.cookie('collapse')==1){jQuery.cookie('collapse','0');jQuery('#room_map_left_utils').fadeOut();this.src='packages/core/skins/default/images/paging_right_arrow.gif';}else{jQuery.cookie('collapse','1');jQuery('#room_map_left_utils').fadeIn();this.src='packages/core/skins/default/images/paging_left_arrow.gif'}"/>
        </td>
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
            							     if(isset($_REQUEST['room_level_name']) and trim($_REQUEST['room_level_name'])!='' ){
            							         if(CHANGE_ROOM_BOOKED=='KEEP'){
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
            												    ';
            							         }elseif(CHANGE_ROOM_BOOKED=='SAME'){
            							             if( trim($_REQUEST['room_level_name'])==trim([[=floors.rooms.room_level_name=]]) ){
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
                												    ';
            							             }else{
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
                												    opener.document.getElementById(\'price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.price=]].'\';
                                                                    opener.document.getElementById(\'usd_price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.usd_price=]].'\';
                    												';
            							             }
            							         }else{
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
            												    opener.document.getElementById(\'price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.price=]].'\';
                                                                opener.document.getElementById(\'usd_price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.usd_price=]].'\';
                												';
            							         }             
            							     }else{
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
        												    opener.document.getElementById(\'price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.price=]].'\';
                                                            opener.document.getElementById(\'usd_price_'.URL::get('object_id').'\').value = \''.[[=floors.rooms.usd_price=]].'\';
            												';
            							     }
            								echo 'opener.count_price('.URL::get('object_id').',true);';
            								echo 'opener.updateRoomForTraveller('.URL::get('object_id').');window.close();"';
            							}
            							?>>
            							<!--IF:room_level(Url::get('cmd')=='select' and ([[=floors.rooms.status=]]=='AVAILABLE' OR [[=floors.rooms.status=]]=='CHECKOUT' OR [[=floors.rooms.status=]]=='EXPECTED_CHECKOUT'))-->
                                            <span title="[[.select_room.]]"><img src="packages/core/skins/default/images/active.gif" width="12" height="12"></span>
                                        <!--/IF:room_level-->
            							<span id="house_status_[[|floors.rooms.id|]]" style="font-size:9px;font-weight:normal;color:red;">[[|floors.rooms.house_status|]]</span>
                                         
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
                <?php if($count == 0){echo '<strong> <span id="message_room" style="font-size:16px; color:red;">Loại phòng này đã hết!</span></strong>';}?>
            </table>
            <br/>
        </td>
      </tr>
	</table>
	<input type="hidden" name="command" id="command"/>
	<br/>
</td>
</tr>
</table>
<input type="hidden" name="room_ids" id="room_ids"/>
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
	function selectRoomLevel(index,roomLevelName,roomLevelId, inputCount,max)
    { 
        $room_level_name = '';
        <?php if(isset($_REQUEST['room_level_name'])){ ?>
        $room_level_name = '<?php echo $_REQUEST['room_level_name']; ?>';
        <?php } ?>
		oldRoomLevelId = <?php echo Url::get('room_level_id_old')?Url::get('room_level_id_old'):0;?>;
		opener.document.getElementById('room_id_'+index).value = '';
        //Kimtan comment: opener.document.getElementById('room_name_'+index).value = '#' + index; 
        max = max+1;
        opener.document.getElementById('room_name_'+index).value = '#' + max;
        //end Kimtan
        
        <?php if(isset($_REQUEST['room_level_name']) AND trim($_REQUEST['room_level_name'])!='' ){ ?>
            <?php if(CHANGE_ROOM_BOOKED=='SAME'){ ?>
                if($room_level_name!=room_levels[roomLevelId]['brief_name']) {
                    opener.document.getElementById('price_'+index).value = number_format(room_levels[roomLevelId]['price'],2);
                    opener.document.getElementById('usd_price_'+index).value = number_format(room_levels[roomLevelId]['usd_price'],2);
                }
            <?php }elseif(CHANGE_ROOM_BOOKED=='ALWAY'){ ?>
                opener.document.getElementById('price_'+index).value = number_format(room_levels[roomLevelId]['price'],2);
                opener.document.getElementById('usd_price_'+index).value = number_format(room_levels[roomLevelId]['usd_price'],2);
            <?php } ?>
        <?php }else{ ?>
            opener.document.getElementById('price_'+index).value = number_format(room_levels[roomLevelId]['price'],2);
            opener.document.getElementById('usd_price_'+index).value = number_format(room_levels[roomLevelId]['usd_price'],2);
        <?php } ?>
        
		opener.document.getElementById('room_level_name_'+index).value = roomLevelName;
		opener.document.getElementById('room_level_id_'+index).value = roomLevelId;
		opener.document.getElementById('time_in_'+index).value = '<?php echo CHECK_IN_TIME;?>';
		opener.document.getElementById('time_out_'+index).value = '<?php echo CHECK_OUT_TIME;?>';
		opener.document.getElementById('arrival_time_'+index).value = '<?php echo [[=day=]].'/'.[[=month=]].'/'.[[=year=]];?>';
		opener.document.getElementById('departure_time_'+index).value = '<?php echo [[=end_day=]].'/'.[[=end_month=]].'/'.[[=end_year=]]?>';
        
        
		opener.count_price(index,true);
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
        }else{
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
