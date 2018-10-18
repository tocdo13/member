<!---------REPORT----------->	
<tr>
<td>
<style>
	.report_table_column{
		width:65px;	
	}
	.simple-layout-middle{
		width:100%;	
	}
</style>
<table  cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="20px" align="center" class="report_table_header"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header"><?php echo Portal::language('company_name');?></th>
        <th class="report_table_header" width="15px"><?php echo Portal::language('booking_code');?></th>
        <th class="report_table_header" width="15px"><?php echo Portal::language('re_code');?></th>
		<th class="report_table_header"><?php echo Portal::language('traveller_name');?></th>
		<th class="report_table_header"><?php echo Portal::language('status');?></th>
		<th class="report_table_header"><?php echo Portal::language('gender');?></th>
		<th class="report_table_header"><?php echo Portal::language('birth_date');?></th>
		<th class="report_table_header"><?php echo Portal::language('nationality');?></th>
		<th class="report_table_header"><?php echo Portal::language('passport');?></th>
		<th class="report_table_header"><?php echo Portal::language('room');?></th>
        <th class="report_table_header"><?php echo Portal::language('num_traveller');?></th>
        <th class="report_table_header"><?php echo Portal::language('room_price');?></th>
		<?php 
				if((!URL::get('status')))
				{?>
		<th class="report_table_header"><?php echo Portal::language('status');?></th>
		
				<?php
				}
				?>
		<?php 
				if(($this->map['price']==1))
				{?>
	  <th class="report_table_header"><?php echo Portal::language('price');?><br/></th>
		
				<?php
				}
				?>
        <th class="report_table_header"><?php echo Portal::language('hour_in');?></th>
		<th class="report_table_header"><?php echo Portal::language('arrival_date');?></th>
        <th class="report_table_header"><?php echo Portal::language('hour_out');?></th>
		<th class="report_table_header"><?php echo Portal::language('departure_date');?></th>
    </tr>
<!--start: KID  1
<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="4" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
		<td align="center" class="report_table_column" width="130"><strong><?php echo System::display_number($this->map['last_group_function_params']['guest_count']);?></strong></td>
		<td colspan="5" align="center" class="report_table_column" width="30">&nbsp;</td>
		<td align="center" class="report_table_column" width="1%"><strong><?php echo System::display_number($this->map['last_group_function_params']['room_count']);?></strong></td>
        <td colspan="2" align="center" class="report_table_column" width="30">&nbsp;</td>
		<?php 
				if((!URL::get('status')))
				{?>
		<td>&nbsp;</td>
		
				<?php
				}
				?>
		<?php 
				if(($this->map['price']==1))
				{?>
		<td align="center" class="report_table_column">
			<strong><?php echo System::display_number($this->map['last_group_function_params']['total_price']);?></strong>		</td>
		
				<?php
				}
				?>		
		<td colspan="4"></td>
        
    </tr>

				<?php
				}
				?>
<!--end:KID--> 
    <?php 
        $r_id = '';
        $rr_id = '';
        $i = 1;
        $total_traveller=0;
     ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
	<tr bgcolor="white">
        
        <?php if($r_id!=$this->map['items']['current']['reservation_id'])
        { 
            $r_id=$this->map['items']['current']['reservation_id']; 
            $rowspan = $this->map['count'][$r_id]['count'];
            //echo $rowspan;
        ?>
		<td align="center" valign="middle" class="report_table_column" rowspan="<?php echo $rowspan; ?>"><?php echo $i++;?></td>
		<td align="left" valign="middle" class="report_table_column" rowspan="<?php echo $rowspan; ?>"><div style="float:left;width:80px;"><?php echo $this->map['items']['current']['customer_name'];?></div></td>
        <td align="left" class="report_table_column" style="width:15px !important;" rowspan="<?php echo $rowspan; ?>"><?php echo $this->map['items']['current']['booking_code'];?></td>
        <td align="left" class="report_table_column" style="width:15px !important;" rowspan="<?php echo $rowspan; ?>"><a href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id'];?>&r_r_id=<?php echo $this->map['items']['current']['reservation_room_id'];?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
        <?php } ?>
        <td align="left" class="report_table_column" width="130">
			<?php echo $this->map['items']['current']['first_name'];?>
		<?php echo $this->map['items']['current']['last_name'];?></td>
		<td align="center" class="report_table_column" width="30"><?php echo $this->map['items']['current']['status'];?> </td>
		<td align="center" class="report_table_column" width="30">
			<?php echo $this->map['items']['current']['gender'];?>		</td>
		<td  align="center" class="report_table_column"><?php echo $this->map['items']['current']['birth_date'];?></td>
		<td align="center" class="report_table_column" title="<?php echo $this->map['items']['current']['nationality_name'];?>">
			<?php echo $this->map['items']['current']['nationality_code'];?></td>
		<td align="center" class="report_table_column"><?php echo $this->map['items']['current']['passport'];?></td>
		<?php 
        if($this->map['items']['current']['reservation_id'].'-'.$this->map['items']['current']['reservation_room_id']!=$r_id.'-'.$rr_id)
        {
            $rr_id = $this->map['items']['current']['reservation_room_id'];
            $rowspan_1 = $this->map['count'][$r_id][$rr_id];
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $rowspan_1; ?>"><?php echo $this->map['items']['current']['room_name'];?></td>
        <td align="right" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>"><?php echo System::display_number($this->map['items']['current']['num_traveller']);$total_traveller+=$this->map['items']['current']['num_traveller']; ?></td>
        <td align="right" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>"><?php echo System::display_number(round($this->map['items']['current']['room_price']));?></td>
		<?php 
				if((!URL::get('status')))
				{?>
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>"><?php echo $this->map['items']['current']['status'];?></td>
		
				<?php
				}
				?>
		<?php 
				if(($this->map['price']==1))
				{?>
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
		<?php 
				if(($this->map['items']['current']['price']))
				{?>
			<?php echo $this->map['items']['current']['price'];?> 
		
				<?php
				}
				?>		</td>
		
				<?php
				}
				?>	
        <td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('H:i',$this->map['items']['current']['time_in']);?></td>	
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('d/m/Y',$this->map['items']['current']['time_in']);?></td>
        <td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('H:i',$this->map['items']['current']['time_out']);?></td>    
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('d/m/Y',$this->map['items']['current']['time_out']);?></td>
   <?php }?>
    </tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<tr bgcolor="white">
		<td colspan="4" align="center" valign="middle" class="report_table_column"><strong><?php if($this->map['real_page_no']==$this->map['real_total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
		<td align="center" class="report_table_column" width="130"><strong><?php echo System::display_number($this->map['group_function_params']['guest_count']);?></strong></td>
		<td colspan="5" align="center" class="report_table_column" width="30">&nbsp;</td>
		<td align="center" class="report_table_column" width="1%"><strong><?php echo System::display_number($this->map['group_function_params']['room_count']);?></strong></td>
        <td align="right" class="report_table_column" width="30"><?php echo $total_traveller;?></td>
        <td colspan="2" align="center" class="report_table_column" width="30">&nbsp;</td>
		<?php 
				if((!URL::get('status')))
				{?>
		<td>&nbsp;</td>
		
				<?php
				}
				?>
		<?php 
				if(($this->map['price']==1))
				{?>
		<td align="center" class="report_table_column">
			<strong><?php echo System::display_number($this->map['group_function_params']['total_price']);?></strong>		</td>
		
				<?php
				}
				?>		
		<td colspan="3"></td>
	</tr>
</table>
</td>
</tr>
