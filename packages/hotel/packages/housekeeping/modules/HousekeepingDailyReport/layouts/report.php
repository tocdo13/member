				
                <?php echo Url::get('floor');?>
                <table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
					<tr valign="middle" bgcolor="#EFEFEF">
						<th width="60" nowrap="nowrap" class="report_table_header">[[.room.]]</th>
						<th width="120" nowrap class="report_table_header">[[.room_level.]]</th>
						<th width="100" class="report_table_header">[[.status.]]</th>
						<th width="180" nowrap class="report_table_header">[[.customer_name.]]</th>
						<th width="60" nowrap="nowrap" class="report_table_header">[[.gender.]]</th>
						<th width="11%" nowrap class="report_table_header">[[.nationality.]]</th>
						<th width="10%" nowrap="nowrap" class="report_table_header">[[.arrival_time.]]</th>
						<th width="10%" nowrap="nowrap" class="report_table_header">[[.departure_time.]]</th>
						<th width="100" class="report_table_header">[[.note.]]</th>
					</tr>
					<!--LIST:items-->
                    
                    <?php 
                    if(Url::get('status_select') == 'ALL_STATUS'){?>
					<tr bgcolor="white">
						<td align="center" nowrap>[[|items.name|]]</td>
						<td align="left">[[|items.room_level|]]</td>
						<td nowrap align="center">[[|items.status|]]</td>
						<td align="left">[[|items.customer_name|]]</td>	
					    <td nowrap align="left">[[|items.gender|]]</td>
						<td nowrap align="center">[[|items.nationality|]]</td>
						<td nowrap align="center"><?php echo [[=items.time_in=]]?date('h\h:i d/m/y',[[=items.time_in=]]):'';?></td>
						<td nowrap align="right"><?php echo [[=items.time_out=]]?date('h\h:i d/m/y',[[=items.time_out=]]):'';?></td>
						<td align="left">[[|items.note|]]</td>
					</tr>
                    <?php }
                    else{
                        if([[=items.status=]] == Url::get('status_select'))
                        {
                        ?>
                        	<tr bgcolor="white">
						<td align="center" nowrap>[[|items.name|]]</td>
						<td align="left">[[|items.room_level|]]</td>
						<td nowrap align="center">[[|items.status|]]</td>
						<td align="left">[[|items.customer_name|]]</td>	
					    <td nowrap align="left">[[|items.gender|]]</td>
						<td nowrap align="center">[[|items.nationality|]]</td>
						<td nowrap align="center"><?php echo [[=items.time_in=]]?date('h\h:i d/m/y',[[=items.time_in=]]):'';?></td>
						<td nowrap align="right"><?php echo [[=items.time_out=]]?date('h\h:i d/m/y',[[=items.time_out=]]):'';?></td>
						<td align="left">[[|items.note|]]</td>
					</tr>
                    <?php }}?>
                    <!--/LIST:items-->
                    
				</table>
