<!---------REPORT----------->	

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="5" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
		<th width="10" align="center" nowrap="nowrap" class="report_table_header">[[.invoice_number.]]</th>
		<th width="10" nowrap="nowrap" class="report_table_header">[[.booking_code_1.]]</th>
		<th width="10" nowrap="nowrap" class="report_table_header">[[.room.]]</th>
        <th width="10" nowrap="nowrap" class="report_table_header">[[.tour.]]</th>
        <th width="10" nowrap="nowrap" class="report_table_header">[[.charge.]]</th>
		<th width="10" nowrap="nowrap" class="report_table_header" >[[.num_people.]]</th>
		<th width="50" nowrap="nowrap" class="report_table_header" >[[.status.]]</th>
		<th width="50" nowrap="nowrap" class="report_table_header">[[.time_in.]]</th>
		<th width="50" nowrap="nowrap" class="report_table_header">[[.time_out.]]</th>
	    <th width="400" nowrap="nowrap" class="report_table_header">[[.note.]]</th>
	</tr>
    <!--start: KID them doan nay den tinh tong trang truoc chuyen sang trang sau 1
    <!--IF:first_pages([[=page_no=]]!=1)-->
    <!---------LAST GROUP VALUE----------->	        
    <tr bgcolor="white">
		<td colspan="3" align="center" valign="middle" class="report_table_column"><strong>[[.last_page_summary.]]</strong></td>
		<td width="10" align="center" class="report_table_column"><strong><?php echo ([[=last_group_function_params=]]['room_count']); ?></strong></td>
        <td width="10" align="center" class="report_table_column"><strong>&nbsp;</strong></td>
        <td width="10" align="center" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['total_charge']); ?></strong></td>
		<td width="10" align="center" class="report_table_column"><strong><?php echo ([[=last_group_function_params=]]['guest_count']); ?></strong></td>
		<td colspan="4"></td>
	</tr>
    <!--/IF:first_pages-->
    <!--end:KID-->   
	<!--LIST:items-->
	<tr bgcolor="white">
		<td width="5" align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
		<td width="1%" align="center" valign="middle" class="report_table_column">[[|items.id|]]</td>
		<td align="center" class="report_table_column"><a href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]&portal=default" target="_blank">[[|items.reservation_id|]]</a></td>
		<td align="center" class="report_table_column">[[|items.room_name|]]</td>
        <td align="center" class="report_table_column">[[|items.customer_name|]]</td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.charge=]]); ?></td>
		<td align="center" class="report_table_column"> [[|items.adult|]] </td>
		<td align="center" class="report_table_column">[[|items.status|]]</td>
		<td nowrap align="center" class="report_table_column">
			<?php echo date('d/m',[[=items.time_in=]]);?> <strong><?php echo date('H\h:i',[[=items.time_in=]]);?></strong></td>
		<td nowrap align="center" class="report_table_column">
			<?php echo date('d/m',[[=items.time_out=]]);?> <strong><?php echo date('H\h:i',[[=items.time_out=]]);?></strong></td>
	    <td align="left" class="report_table_column">[[|items.note|]] </td>
	</tr>
	<!--/LIST:items-->
	
	<tr bgcolor="white">
		<td colspan="3" align="center" valign="middle" class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
		<td width="10" align="center" class="report_table_column"><strong><?php echo ([[=group_function_params=]]['room_count']); ?></strong></td>
        <td width="10" align="center" class="report_table_column"><strong>&nbsp;</strong></td>
        <td width="10" align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_charge']); ?></strong></td>
		<td width="10" align="center" class="report_table_column"><strong><?php echo ([[=group_function_params=]]['guest_count']); ?></strong></td>
		<td colspan="4"></td>
	</tr>

</table>
