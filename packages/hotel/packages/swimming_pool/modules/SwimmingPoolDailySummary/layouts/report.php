<div class="swimming_pool-daily-summary-bound">
	<div class="title">[[.daily_summary.]]</div>
	<div class="body">
		<div class="select-date">
			<form name="" method="post">
			[[.date.]]: <input name="date" type="text" id="date" class="date" > [[.staff.]]: <select name="staff_id" id="staff_id"></select><input type="submit" value="OK">
			</form>
		</div>
		<div class="content"><br />
			<table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
			  <tr bgcolor="#A2D0FF">
			    <th rowspan="2">[[.swimming_pool.]]</th>
			    <th align="center" style="border-bottom:1px solid #0066CC;color:#003399;padding-top:2px;padding-bottom:2px;">[[.hour.]]</th>
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
				<td class="swimming_pool-column">
					<ul>
					<!--LIST:swimming_pools-->
						<li style="cursor:pointer; <?php echo (strtoupper([[=swimming_pools.category=]])=='VIP')?'background-color:#FF0000;color:#FFFFFF;':'';?>" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'add','swimming_pool_id'=>[[=swimming_pools.id=]]));?>'+'&date='+$('date').value)" title="[[.reservation.]]">[[|swimming_pools.name|]]<!--IF:cond([[=swimming_pools.people_number=]])--><br>([[|swimming_pools.people_number|]] [[.guest.]])<!--/IF:cond--></li>
					<!--/LIST:swimming_pools-->
					</ul></td>
				<td class="swimming_pool-column item">
					<ul>
					<!--LIST:swimming_pools-->
						<li><?php $i = 0;?><?php $j = 0;?>
							<!--LIST:swimming_pools.reservation_swimming_pool-->
							<?php $j += 15;?><div title="[[|swimming_pools.reservation_swimming_pool.tooltip|]]" class="reservation [[|swimming_pools.reservation_swimming_pool.status|]]" style="z-index:<?php echo $i++;?> ;width:<?php echo (([[=swimming_pools.reservation_swimming_pool.time_out=]]-[[=swimming_pools.reservation_swimming_pool.time_in=]])/60)-3;?>px;left:<?php echo (([[=swimming_pools.reservation_swimming_pool.time_in=]]-[[=begin_time=]])/60);?>px;top:<?php echo $j;?>px;"> <a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=swimming_pools.reservation_swimming_pool.id=]],'swimming_pool_id'=>[[=swimming_pools.reservation_swimming_pool.swimming_pool_id=]]));?>"> [[|swimming_pools.reservation_swimming_pool.people_number|]]</a> - <a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'invoice','id'=>[[=swimming_pools.reservation_swimming_pool.id=]],'swimming_pool_id'=>[[=swimming_pools.reservation_swimming_pool.swimming_pool_id=]]));?>"><img src="packages/core/skins/default/images/cmd_Tim.gif" width="12"></a></div>
							<!--/LIST:swimming_pools.reservation_swimming_pool-->
						</li>	
					<!--/LIST:swimming_pools-->
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