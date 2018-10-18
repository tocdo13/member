<!---------REPORT----------->	

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th rowspan="2" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header" >[[.guest_name.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.room.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header" >[[.adult.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header" >[[.num_child.]]</th>      
	  <th colspan="2" nowrap="nowrap" class="report_table_header" >[[.has_breakfast.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.arrival_date.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.departure_date.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.note.]]</th>
  </tr>
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="60" nowrap="nowrap" class="report_table_header" >[[.yes.]]</th>
		<th width="60"  nowrap="nowrap" class="report_table_header">[[.No.]]</th>
		<!--IF:status(!URL::get('status'))-->
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
	  <!--/IF:price-->
	</tr>
    <?php $child = 0;?>
	<!--LIST:items-->
	<tr bgcolor="white">
		<td align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
		<td nowrap align="left" class="report_table_column" width="200">
			[[|items.first_name|]]
		[[|items.last_name|]]</td>
		<td width="1%" align="center" nowrap="nowrap" class="report_table_column">[[|items.room_name|]]</td>
		<td align="center"  nowrap="nowrap" class="report_table_column">[[|items.num_people|]]</td>
		<td align="center"  nowrap="nowrap" class="report_table_column">[[|items.num_child|]]<?php $child += [[=items.num_child=]];?></td>        
		<td  nowrap align="center" class="report_table_column">&nbsp;</td>
		<td nowrap align="center" class="report_table_column">&nbsp;</td>
		<!--IF:status(!URL::get('status'))-->
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
		<!--/IF:price-->		
		<td width="1%" nowrap align="center" class="report_table_column">
			<?php echo date('d/m/Y h\h:i',[[=items.time_in=]]);?></td>
		<td width="1%" nowrap align="center" class="report_table_column">
			<?php echo date('d/m/Y h\h:i',[[=items.time_out=]]);?></td>
	    <td align="left" class="report_table_column">[[|items.note|]] </td>
	</tr>
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
	<tr bgcolor="white">
		<td align="center" valign="middle" class="report_table_column">&nbsp;</td>
		<td nowrap align="right" class="report_table_column" width="200"><strong>[[.total.]]</strong></td>
		<td align="center"><strong>[[|room_count|]]</strong></td>
		<td align="center"><strong>[[|guest_count|]]</strong></td>
        <td align="center"><strong><?php echo $child;?></strong></td>
		<td colspan="5" nowrap align="center" class="report_table_column">&nbsp;</td>
	</tr>
	<!--/IF:first_page-->
</table>
