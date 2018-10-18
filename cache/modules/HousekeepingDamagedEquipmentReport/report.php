<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" id="export">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  nowrap="nowrap" class="report_table_header" align="left"><?php echo Portal::language('stt');?></th>
		<th align="center"  nowrap="nowrap" class="report_table_header"><?php echo Portal::language('date');?></th>
		<th class="report_table_header"><?php echo Portal::language('product_name');?></th>
		<th class="report_table_header"><?php echo Portal::language('room_id');?></th>
		<th class="report_table_header"><?php echo Portal::language('damaged_type');?></th>
		<th class="report_table_header"><?php echo Portal::language('unit_name');?></th>
		<th class="report_table_header"><?php echo Portal::language('note');?></th>
        <th class="report_table_header"><?php echo Portal::language('quantity');?></th>
	</tr>
	
	<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="7" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
			<td align="center" class="report_table_column"><?php echo ($this->map['last_group_function_params']['total_quantity']);?></td>
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
		<td align="center" valign="top" nowrap="nowrap" class="report_table_column"><?php echo $this->map['items']['current']['date'];?></td>
		<td nowrap align="center" class="report_table_column" width="150">
				<?php echo $this->map['items']['current']['product_name'];?>	  </td>
		<td nowrap align="center" class="report_table_column" width="150">
				<?php echo $this->map['items']['current']['room_name'];?>			</td>
		<td nowrap align="center" class="report_table_column" width="100"><?php echo $this->map['items']['current']['damaged_type'];?> </td>
		<td align="center" nowrap class="report_table_column"> <?php echo $this->map['items']['current']['unit_name'];?> </td>
        <td align="center" nowrap class="report_table_column"> <?php echo $this->map['items']['current']['note'];?> </td>
		<td align="center" nowrap class="report_table_column"> <?php echo $this->map['items']['current']['quantity'];?> </td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<!---------TOTAL GROUP FUNCTION----------->				
		<tr class="report_table_column">
			<th colspan="7" align="right" style="font-size:12px;"><?php if($this->map['real_page_no']==$this->map['real_total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
			<td align="center" nowrap="nowrap" style="font-size:12px;font-weight:bold"><?php echo $this->map['group_function_params']['total_quantity'] ?></td>
		</tr>

</table>
<script>
jQuery('#export_repost').click(function(){
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>