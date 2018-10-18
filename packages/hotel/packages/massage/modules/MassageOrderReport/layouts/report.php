<!---------REPORT----------->	
<div align="right"></div>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="5%" rowspan="2" class="report_table_header">[[.stt.]]</th>
	  <th width="15%" rowspan="2" class="report_table_header">[[.code.]]</th>
	  <th width="15%" rowspan="2" class="report_table_header">[[.date.]]</th>
	  <th colspan="2" class="report_table_header">[[.total_amount.]]</th>
	  <th rowspan="2" class="report_table_header" width="15%">[[.full_name.]]</th>
	  <th rowspan="2" class="report_table_header" width="15%">[[.note.]]</th>
  </tr>
	<tr valign="middle" bgcolor="#EFEFEF">
		<th class="report_table_header" width="15%">VND</th>
		<th class="report_table_header" width="15%">USD</th>
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" width="20" valign="top" align="center" class="report_table_column">[[|items.stt|]]</td>
			<td nowrap align="center" class="report_table_column">[[|items.id|]]</td>
			<td nowrap align="center" class="report_table_column">[[|items.time|]] </td>
			<td nowrap align="right" class="report_table_column">
				[[|items.total_amount_vnd|]]			</td><td nowrap align="right" class="report_table_column">
				[[|items.total_amount|]]
			</td>
                <td align="right" nowrap="nowrap" class="report_table_column"> [[|items.full_name|]] </td>
      <td nowrap align="right" class="report_table_column">
				[[|items.note|]]			</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->
	<tr bgcolor="white">
		<td colspan="3" align="right" valign="top" nowrap="nowrap" class="report_table_column"><strong>[[.total.]]:</strong></td>
			<td nowrap align="right" class="report_table_column">
				<strong><?php echo System::display_number_report([[=group_function_params=]]['total_amount_vnd']);?></strong></td>
			<td nowrap align="right" class="report_table_column">
				<b><?php echo System::display_number_report([[=group_function_params=]]['total_amount']);?></b>			</td>
	        <td align="right" nowrap="nowrap" class="report_table_column">&nbsp;</td>
      <td nowrap align="right" class="report_table_column">&nbsp;</td>
	</tr>
		<!--IF:last_page([[=page_no=]]==[[=total_page=]])-->		
		<!--/IF:last_page-->
</table>
</div>
</div>
