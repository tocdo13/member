<div class="tennis-daily-summary-bound">
	<div class="title">[[.daily_summary.]]</div>
	<div class="body">
		<div class="select-date">
			<form name="" method="post">
			[[.date.]]: <input name="date" type="text" id="date" class="date" > [[.staff.]]: <select name="staff_id" id="staff_id"></select><input type="submit" value="OK">
			</form>
		</div>
		<div class="content"><br />
			<table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
			  <tr bgcolor="#CCFF99">
			    <th rowspan="2">[[.court.]]</th>
			    <th align="center" style="border-bottom:1px solid #CCCCCC;color:#003399;padding-top:2px;padding-bottom:2px;">[[.hour.]]</th>
		      </tr>
			  <tr>
			    <td width="<?php echo [[=aggregate_duration=]];?>">
					<?php
						$j=0;
						for($i=0;$i<=[[=aggregate_duration=]];$i++){
							if($i%60==0 and $i>0){
								echo '<span class="time-block" style=\'width:59px;\'>'.(date('H:i',[[=begin_time=]]+$j)).'</span>';
								$j=$j+60*60;
							}
						}
					?></td>
		      </tr>
			  <tr valign="top">
				<td class="court-column">
					<ul>
					<!--LIST:courts-->
						<li style="cursor:pointer; <?php echo (strtoupper([[=courts.category=]])=='VIP')?'background-color:#FF0000;color:#FFFFFF;':'';?>" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'add','court_id'=>[[=courts.id=]]));?>'+'&date='+$('date').value)" title="[[.reservation.]]">[[|courts.name|]]</li>
					<!--/LIST:courts-->
					</ul></td>
				<td class="court-column item">
					<ul>
					<!--LIST:courts-->
						<li>
							<?php $i = 0;?>
							<!--LIST:courts.reservation_court-->
							<div title="[[|courts.reservation_court.tooltip|]]" class="reservation [[|courts.reservation_court.status|]]" style="z-index:<?php echo $i++;?> ;width:<?php echo (([[=courts.reservation_court.time_out=]]-[[=courts.reservation_court.time_in=]])/60)-3;?>px;left:<?php echo (([[=courts.reservation_court.time_in=]]-[[=begin_time=]])/60);?>px;top:0px;"> <a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=courts.reservation_court.id=]],'court_id'=>[[=courts.reservation_court.court_id=]]));?>"> [[|courts.reservation_court.brief_status|]]</a> - <a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'invoice','id'=>[[=courts.reservation_court.id=]],'court_id'=>[[=courts.reservation_court.court_id=]]));?>"><img src="packages/core/skins/default/images/cmd_Tim.gif" width="12"></a></div>
							<!--/LIST:courts.reservation_court-->
						</li>	
					<!--/LIST:courts-->
					</ul>
				</td>
			  </tr>
			</table>

		</div>
	</div>
</div>
<script>
	jQuery("#date").datepicker();
</script>