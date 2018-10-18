<form name="BanquetRoomMapReportForm" method="post">
<div class="massage-daily-bound" style="margin:5px;">
	<div class="" style="text-transform: uppercase;">[[.daily_summary.]] 
    <input name="date" type="text" id="date" class="date" onchange="BanquetRoomMapReportForm.submit()" style="height: 29px;" />
    <input class="w3-btn w3-cyan w3-text-white" type="button" value="[[.add_new.]]" onclick="window.location='?page=banquet_reservation&cmd='+1+'&portal=<?php echo PORTAL_ID;?>';"/>
    </div>
	<div class="body">
		<div class="select-date">
			
			<!--trung coment link layot select dat tiec <input type="button" value="[[.add_new.]]" onclick="window.location='<?php //echo Url::build('banquet_reservation',array('cmd'=>'add'));?>&checkin_date='+$('date').value+'&room_ids='+$('room_ids').value;">-->
			
            <input name="room_ids" type="hidden" id="room_ids">
		</div>
		<div class="content"><br />
			<table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
			  <tr bgcolor="#FFCC00" style="text-transform: uppercase;">
			    <th rowspan="2" style="text-align: center;">[[.banquet_room.]]</th>
			    <th align="center" style="border-bottom:1px solid #990000;padding-top:2px;padding-bottom:2px;">[[.hour.]]</th>
		      </tr>
			  <tr>
			    <td width="<?php echo [[=aggregate_duration=]];?>" align="center">
					<?php
						$j=0;
						for($i=0;$i<=[[=aggregate_duration=]];$i++){
							if($i%60==0 and $i>0){
								echo '<span class="time-block" style=\'width:54px;\'>'.(date('H:i',[[=begin_time=]]+$j)).'</span>';
								$j=$j+60*60;
							}
						}
					?></td>
		      </tr>
			  <tr valign="top">
				<td class="room-column">
					<ul>
                    <!--LIST:floors-->
                    <div class="banquet-floor-name">[[|floors.name|]]</div>
					<!--LIST:floors.rooms-->
						<li style="cursor:pointer;" title="[[.reservation.]]"><label style="height: 24px;" for="room_[[|floors.rooms.id|]]">[[|floors.rooms.name|]]</label><input type="checkbox" id="room_[[|floors.rooms.id|]]" value="[[|floors.rooms.id|]]" onclick="updateRoomIds();" class="room-checkbox" style="display: none; height: 24px;" /></li>
					<!--/LIST:floors.rooms-->
                    <!--/LIST:floors-->
					</ul></td>
				<td class="room-column item">
					<ul>
                    <!--LIST:floors-->
                    <div class="banquet-floor-name">&nbsp;</div>
					<!--LIST:floors.rooms-->
						<li>
							<?php $i = 0;?>
							<!--LIST:floors.rooms.reservation_room-->
							<div title="[[|floors.rooms.reservation_room.tooltip|]]" class="reservation [[|floors.rooms.reservation_room.status|]]" style="z-index:<?php echo $i++;?> ;position:absolute;width:<?php echo (([[=floors.rooms.reservation_room.time_out=]]-[[=floors.rooms.reservation_room.time_in=]])*(54/60)/60+([[=floors.rooms.reservation_room.time_out=]]-[[=floors.rooms.reservation_room.time_in=]])/3600);?>px;left:<?php echo (([[=floors.rooms.reservation_room.time_in=]]-[[=begin_time=]])*(54/60)/60)+([[=floors.rooms.reservation_room.time_in=]]-[[=begin_time=]])/3600;?>px;top: 0px; font-size: 7px; text-align: center; height: 24px; overflow: hidden;">
	                        <span style="width:80px; height: 24px; overflow:hidden;">[[|floors.rooms.reservation_room.id|]]</span>
                            <a target="_blank" href="<?php echo Url::build('banquet_reservation',array('action'=>'edit','id'=>[[=floors.rooms.reservation_room.party_reservation_id=]],'cmd'=>[[=floors.rooms.reservation_room.party_type_id=]]));?>">[[.edit.]]</a> [<a target="_blank" href="<?php echo Url::build('banquet_reservation',array('cmd'=>'detail','id'=>[[=floors.rooms.reservation_room.party_reservation_id=]]));?>"><img src="packages/core/skins/default/images/cmd_Tim.gif" width="12"></a>]
                            </div>
							<!--/LIST:floors.rooms.reservation_room-->
						</li>	
					<!--/LIST:floors.rooms-->
                    <!--/LIST:floors-->
					</ul>
				</td>
			  </tr>
			</table>

		</div>
	</div>
</div>
</form>
<script>
	function updateRoomIds(){
		$('room_ids').value = '';
		jQuery(".room-checkbox").each(function (){
			if(jQuery(this).attr('checked')==true){
				$('room_ids').value += (($('room_ids').value!='')?',':'')+jQuery(this).val();
			}
		});
	}
	jQuery("#date").datepicker();
</script>