				
                <?php echo Url::get('floor');?>
                <table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
					<tr valign="middle" bgcolor="#EFEFEF">
						<th width="60" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('room');?></th>
						<th width="120" nowrap class="report_table_header"><?php echo Portal::language('room_level');?></th>
						<th width="100" class="report_table_header"><?php echo Portal::language('status');?></th>
						<th width="180" nowrap class="report_table_header"><?php echo Portal::language('customer_name');?></th>
						<th width="60" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('gender');?></th>
						<th width="11%" nowrap class="report_table_header"><?php echo Portal::language('nationality');?></th>
						<th width="10%" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('arrival_time');?></th>
						<th width="10%" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('departure_time');?></th>
						<th width="100" class="report_table_header"><?php echo Portal::language('note');?></th>
					</tr>
					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                    
                    <?php 
                    if(Url::get('status_select') == 'ALL_STATUS'){?>
					<tr bgcolor="white">
						<td align="center" nowrap><?php echo $this->map['items']['current']['name'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['room_level'];?></td>
						<td nowrap align="center"><?php echo $this->map['items']['current']['status'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['customer_name'];?></td>	
					    <td nowrap align="left"><?php echo $this->map['items']['current']['gender'];?></td>
						<td nowrap align="center"><?php echo $this->map['items']['current']['nationality'];?></td>
						<td nowrap align="center"><?php echo $this->map['items']['current']['time_in']?date('h\h:i d/m/y',$this->map['items']['current']['time_in']):'';?></td>
						<td nowrap align="right"><?php echo $this->map['items']['current']['time_out']?date('h\h:i d/m/y',$this->map['items']['current']['time_out']):'';?></td>
						<td align="left"><?php echo $this->map['items']['current']['note'];?></td>
					</tr>
                    <?php }
                    else{
                        if($this->map['items']['current']['status'] == Url::get('status_select'))
                        {
                        ?>
                        	<tr bgcolor="white">
						<td align="center" nowrap><?php echo $this->map['items']['current']['name'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['room_level'];?></td>
						<td nowrap align="center"><?php echo $this->map['items']['current']['status'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['customer_name'];?></td>	
					    <td nowrap align="left"><?php echo $this->map['items']['current']['gender'];?></td>
						<td nowrap align="center"><?php echo $this->map['items']['current']['nationality'];?></td>
						<td nowrap align="center"><?php echo $this->map['items']['current']['time_in']?date('h\h:i d/m/y',$this->map['items']['current']['time_in']):'';?></td>
						<td nowrap align="right"><?php echo $this->map['items']['current']['time_out']?date('h\h:i d/m/y',$this->map['items']['current']['time_out']):'';?></td>
						<td align="left"><?php echo $this->map['items']['current']['note'];?></td>
					</tr>
                    <?php }}?>
                    <?php }}unset($this->map['items']['current']);} ?>
                    
				</table>
