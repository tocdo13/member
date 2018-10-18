<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  nowrap="nowrap" class="report_table_header" align="right"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" align="left"><?php echo Portal::language('code');?></th>
		<th class="report_table_header" align="left"><?php echo Portal::language('reservation_date');?></th>
		<th class="report_table_header" align="left"><?php echo Portal::language('cancel_date');?></th>
		<th class="report_table_header" align="left"><?php echo Portal::language('customer_name');?></th>
        <th class="report_table_header" align="left"><?php echo Portal::language('total_cancel');?></th>
        <th class="report_table_header" align="left"><?php echo Portal::language('note');?></th>
		<th class="report_table_header"><?php echo Portal::language('user_name');?></th>
	</tr>
	<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="5" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
            <td class="report_sub_title" align="right"><b><?php echo System::display_number($this->map['last_group_function_params']['total']);?></b></td>
            <td colspan="2"></td>
		</tr>
	
				<?php
				}
				?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">
			<?php echo $this->map['items']['current']['stt'];?>		</td>
		<td align="left" class="report_table_column" nowrap="nowrap"><?php echo $this->map['items']['current']['code'];?></td>
		<td align="left" class="report_table_column" nowrap="nowrap"><?php echo $this->map['items']['current']['reservation_date'];?></td>
		<td nowrap align="left" class="report_table_column" width="200">
				<?php echo $this->map['items']['current']['cancel_date'];?>	  </td>
				<td nowrap align="left" class="report_table_column" width="200"><?php echo $this->map['items']['current']['customer_name'];?> </td>
                <td nowrap align="right" class="report_table_column" width="100"><?php echo $this->map['items']['current']['total'];?> </td>
                <td nowrap align="right" class="report_table_column" width="100"><?php echo $this->map['items']['current']['note'];?> </td>
			<td align="center" nowrap class="report_table_column"> <?php echo $this->map['items']['current']['lastest_edited_user_id'];?> </td>
  </tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<!---------TOTAL GROUP FUNCTION----------->
		<tr>
            <td colspan="5" align="right" class="report_sub_title" align="right"><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <td  align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total']);?></strong></td>
            <td colspan="2"></td>
		</tr>
</table>
