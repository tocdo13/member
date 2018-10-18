<style>
.simple-layout-middle{width:100%;}
</style>
<form name="BanquetRoomMapMonthReportForm" method="post">
<div class="massage-daily-bound" style="margin:5px;">
	<div class="title">[[.monthy_summary.]]: <?php echo Url::get('month')?Url::get('month').'/'.Url::get('year'):date('m/Y');?></div>
	<div class="body">
		<div class="select-date">
			<!-- [[.month.]]: <select name="month" id="month" onchange="BanquetRoomMapMonthReportForm.submit();"></select> [[.year.]] <select name="year" id="year" onchange="BanquetRoomMapMonthReportForm.submit();"></select>-->
            <!-- trung:them nut search de load lai trang -->
            [[.month.]]: <select name="month" id="month" ></select> [[.year.]] <select name="year" id="year" ></select>
            <!-- trung: end them nut search de load lai trang -->
            <input name='btn_sub' type="submit" id="btn_sub" value="search"/>
			<!--<input type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build('banquet_order',array('cmd'=>'add'));?>&checkin_date='+$('date').value+'&room_ids='+$('room_ids').value;"/>-->
			<input name="room_ids" type="hidden" id="room_ids"/>
		</div>
		<div class="content"><br />
			<table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" width="100%">
			  <tr bgcolor="#FFCC00">
			    <th rowspan="3" width="1%" nowrap="nowrap" style="text-align: center;">[[.banquet_room.]]</th>
			    <th align="center" style="border-bottom:1px solid #990000;padding-top:2px;padding-bottom:2px;">[[.day.]]</th>
		      </tr>
              <tr>
              </tr>
			  <tr>
			    <td width="<?php echo [[=aggregate_duration=]];?>" align="center">
                	<table cellpadding="0" bgcolor="#F1F1F1" cellspacing="0" width="100%" border="1" bordercolor="#666666" style="border-collapse:collapse;">
                    <tr>
					<!--LIST:days-->
						<td colspan="2" align="center" width="36px"><strong>[[|days.id|]]</strong></td>
					<!--/LIST:days-->	
                    </tr>
                    <tr>
                        <!--LIST:day_status-->
						<td colspan="2" align="center" width="36px"><strong style="font-size: 10px;">[[|day_status.time2|]]</strong></td>
					<!--/LIST:day_status-->
                    </tr>
                    <tr>
					<!--LIST:room_status-->
						<td align="center" width="1%"><?php echo  (date('H',[[=room_status.time=]])=='00')?'S':'C';?></td>
					<!--/LIST:room_status-->	
                    </tr>
                    </table>
                </td>
		      </tr>
			  <tr valign="top">
				<td class="room-column">
					<ul>
                    <!--LIST:floors-->
					<!--LIST:floors.rooms-->
						<li style="cursor:pointer;" title="[[.reservation.]]"><label for="room_[[|floors.rooms.id|]]">[[|floors.rooms.name|]]</label><input type="checkbox" id="room_[[|floors.rooms.id|]]" value="[[|floors.rooms.id|]]" onclick="updateRoomIds();" class="room-checkbox"></li>
					<!--/LIST:floors.rooms-->
                    <!--/LIST:floors-->
					</ul></td>
				<td class="room-column item">
					<ul>
                    <!--LIST:floors-->
					<!--LIST:floors.rooms-->
                    <li>
                	<table cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#666666" style="border-collapse:collapse;height:20px;">
                    <tr>
					<!--LIST:room_status-->
						<td align="center" width="1%" bgcolor="<?php if( [[=room_status.D=]]=='Sun'  ){echo '#d5fca3';}else if([[=room_status.D=]]=='Sat'){echo '#a2fffe';}else {echo  (date('H',[[=room_status.time=]])=='00')?'#eeeeee':'';} ?>">
                        <!--LIST:room_status.rooms--><!--IF:cond([[=floors.rooms.id=]]==[[=room_status.rooms.party_room_id=]])-->
                        <?php if(User::can_view(false,ANY_CATEGORY)) {?><a 
                            class="[[|room_status.rooms.status|]]" 
                            title="[[|room_status.rooms.party_name|]] - [[|room_status.rooms.full_name|]]: <?php echo date('H:i',[[=room_status.rooms.time_in=]]).' - '.date('H:i',[[=room_status.rooms.time_out=]]);?>" 
                            href="<?php echo Url::build('banquet_reservation',array('cmd'=>'detail','id'=>[[=room_status.rooms.party_reservation_id=]]));?>" target="_blank">
                            <strong><?php echo [[=room_status.rooms.brief_status=]];?></strong>
                        </a><?php } ?><!--/IF:cond--><!--/LIST:room_status.rooms-->
                        </td>
					<!--/LIST:room_status-->	
                    </tr>
                    </table>
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