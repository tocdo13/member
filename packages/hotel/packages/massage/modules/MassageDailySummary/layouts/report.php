<form name="MassageDailySummaryReportForm" method="post">
<div class="massage-daily-bound" style="margin:5px;">
    <div class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px; margin-bottom: 20px; font-weight: normal !important;">[[.daily_summary.]]</div>
    <div class="body">
        <div class="select-date">
            [[.date.]]: <input name="date" type="text" id="date" class="date" style="height: 24px; width: 100px; margin-right: 5px;"/> [[.staff.]]: <select name="staff_id" id="staff_id" style="height: 24px; width: 150px; margin-right: 5px;"></select><input type="submit" value="[[.search.]]" style="height: 24px; width: 100px; margin-right: 5px;"/>
            <?php
                if(Url::get('package_id'))
                {
                    ?>
                    <input style="height: 24px;" type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','package_id'=>Url::get('package_id'),'rr_id'=>Url::get('reservation_room_id')));?>&date='+$('date').value+'&room_ids='+$('room_ids').value;"/>
                    <?php 
                } 
                else
                {
                    ?>
                    <input style="height: 24px;" type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add'));?>&date='+$('date').value+'&room_ids='+$('room_ids').value;"/>
                    <?php 
                }
            ?>
            
            <input name="room_ids" type="hidden" id="room_ids"/>
        </div>
		<div class="content"><br />
            <table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="width: 1200px;">
                <tr bgcolor="#FFCC00">
                    <th rowspan="2" align="center">[[.room.]]</th>
                    <th align="center" style="border-bottom:1px solid #990000;padding-top:2px;padding-bottom:2px;">[[.hour.]]</th>
                </tr>
                <tr>
                    <td width="<?php echo [[=aggregate_duration=]];?>" align="center">
                    	<?php
                    		$j=0;
                    		for($i=0;$i<=[[=aggregate_duration=]];$i++){
                    			if($i%60==0 and $i>0){
                    				echo '<span class="time-block" style=\'width:44px;\'>'.(date('H:i',[[=begin_time=]]+$j)).'</span>';
                    				$j=$j+60*60;
                    			}
                    		}
                    	?>
                    </td>
                </tr>
                <tr valign="top">
                    <td class="room-column">
                    	<ul>
                    	<!--LIST:rooms-->
                    		<li style="width: 120px; height: 24px; cursor:pointer; <?php echo (strtoupper([[=rooms.category=]])=='VIP')?'background-color:#FF0000;color:#FFFFFF;':'';?>" title="[[.reservation.]]">[[|rooms.name|]] <input type="checkbox" id="room_[[|rooms.id|]]" value="[[|rooms.id|]]" onclick="updateRoomIds();" class="room-checkbox"/></li>
                    	<!--/LIST:rooms-->
                    	</ul>
                    </td>
                    <td class="room-column item">
                    	<ul>
                    	<!--LIST:rooms-->
                    		<li>
                    			<?php $i = 0;?>
                    			<!--LIST:rooms.reservation_room-->
                    			<!--daund chinh lech cot<div title="[[|rooms.reservation_room.tooltip|]]" class="reservation [[|rooms.reservation_room.status|]]" style="z-index:<?php //echo $i++;?> ;position:absolute;width:<?php //echo (([[=rooms.reservation_room.time_out=]]-[[=rooms.reservation_room.time_in=]])/60)-8;?>px;left:<?php //echo (([[=rooms.reservation_room.time_in=]]-[[=begin_time=]])/60)-10;?>px;top:0px;">-->
                                <!--Start daund edit-->
                                <?php 
                                    $number_hour = ([[=rooms.reservation_room.time_out=]]-[[=rooms.reservation_room.time_in=]])/3600;
                                    $width = $number_hour * 44;
                                    $past_hour = ([[=rooms.reservation_room.time_in=]]-[[=begin_time=]])/3600;
                                    $left = ($past_hour*44)+$past_hour;
                                ?>
                                <div title="[[|rooms.reservation_room.tooltip|]]" id="reservation_[[|rooms.reservation_room.status|]]" class="reservation [[|rooms.reservation_room.status|]]" style="position: absolute; z-index: <?php echo $i++;?>;width:<?php echo $width;?>px;left:<?php echo $left;?>px; top: 0px;font-size: 7px; text-align: center; font-size: 3px; overflow: hidden;">
                                <!--End daund edit-->
                                <?php
                                    if([[=rooms.reservation_room.package_id=]]!='')
                                    {
                                        ?>
                                        <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=rooms.reservation_room.reservation_room_id=]],'package_id'=>[[=rooms.reservation_room.package_id=]],'rr_id'=>[[=rooms.reservation_room.ht_reservation_room_id=]],'room_id'=>[[=rooms.reservation_room.room_id=]]));?>" style="margin-right: 2px;" title="[[.edit.]]"><i class="fa fa-pencil w3-text-red" style="font-size: 18px;"></i></a>
                                        [<a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'invoice','id'=>[[=rooms.reservation_room.reservation_room_id=]],'package_id'=>[[=rooms.reservation_room.package_id=]],'rr_id'=>[[=rooms.reservation_room.ht_reservation_room_id=]]));?>" title="[[.view_invoice.]]"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>]
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=rooms.reservation_room.reservation_room_id=]],'room_id'=>[[=rooms.reservation_room.room_id=]]));?>" style="margin-right: 2px;" title="[[.edit.]]"><i class="fa fa-pencil w3-text-red" style="font-size: 18px;"></i></a>
                                        [<a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'invoice','id'=>[[=rooms.reservation_room.reservation_room_id=]]));?>" title="[[.view_invoice.]]"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>]
                                        <?php 
                                    }
                                ?>
                                
                                </div>
                    			<!--/LIST:rooms.reservation_room-->
                    		</li>	
                    	<!--/LIST:rooms-->
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
	    var room_ids = '';
		jQuery(".room-checkbox").each(function (){
		    if(jQuery(this).attr('checked')=='checked'){
                room_ids += ((room_ids!='')?',':'')+jQuery(this).val();
                
			}
		});
        jQuery('#room_ids').val(room_ids);
	}
	jQuery("#date").datepicker();
</script>
