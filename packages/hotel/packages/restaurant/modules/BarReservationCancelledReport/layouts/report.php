<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  nowrap="nowrap" class="report_table_header" align="right">[[.stt.]]</th>
		<th class="report_table_header" align="left">[[.code.]]</th>
		<th class="report_table_header" align="left">[[.reservation_date.]]</th>
		<th class="report_table_header" align="left">[[.cancel_date.]]</th>
		<th class="report_table_header" align="left">[[.customer_name.]]</th>
        <th class="report_table_header" align="left">[[.total_cancel.]]</th>
        <th class="report_table_header" align="left">[[.note.]]</th>
		<th class="report_table_header">[[.user_name.]]</th>
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
            <td class="report_sub_title" align="right"><b><?php echo System::display_number([[=last_group_function_params=]]['total']);?></b></td>
            <td colspan="2"></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">
			[[|items.stt|]]		</td>
		<td align="left" class="report_table_column" nowrap="nowrap">[[|items.code|]]</td>
		<td align="left" class="report_table_column" nowrap="nowrap">[[|items.reservation_date|]]</td>
		<td nowrap align="left" class="report_table_column" width="200">
				[[|items.cancel_date|]]	  </td>
				<td nowrap align="left" class="report_table_column" width="200">[[|items.customer_name|]] </td>
                <td nowrap align="right" class="report_table_column" width="100">[[|items.total|]] </td>
                <td nowrap align="right" class="report_table_column" width="100">[[|items.note|]] </td>
			<td align="center" nowrap class="report_table_column"> [[|items.lastest_edited_user_id|]] </td>
  </tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->
		<tr>
            <td colspan="5" align="right" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <td  align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total']);?></strong></td>
            <td colspan="2"></td>
		</tr>
</table>
