<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  nowrap="nowrap" class="report_table_header" align="right" width="1%">[[.stt.]]</th>
		<th class="report_table_header" align="left" width="1%" nowrap="nowrap">[[.code.]]</th>
		<th class="report_table_header" align="left">[[.product_name.]]</th>
		<th class="report_table_header" align="left">[[.number.]]</th>
		<th class="report_table_header" align="left">[[.unit.]]</th>
  </tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" align="right" valign="middle" class="report_table_column" width="1%">[[|items.stt|]]</td>
		<td align="left" class="report_table_column" nowrap="nowrap" width="1%">[[|items.material_id|]]</td>
        <td nowrap align="left" class="report_table_column">[[|items.name|]]</td>
		<td nowrap align="left" class="report_table_column">[[|items.total|]]</td>
        <td nowrap align="left" class="report_table_column">[[|items.unit_id|]]</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<!--IF:last_page([[=page_no=]]==[[=total_page=]])-->
		<!--/IF:last_page-->
</table>
