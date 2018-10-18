<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#C3C3C3">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  nowrap="nowrap" class="report_table_header" align="left">[[.stt.]]</th>
		<th align="center"  nowrap="nowrap" class="report_table_header">[[.date.]]</th>
		<th class="report_table_header">[[.object_code.]]</th>
        <th class="report_table_header">[[.name.]]</th>
		<th class="report_table_header">[[.type.]]</th>
		<th class="report_table_header">[[.room_name.]]</th>
        <th class="report_table_header">[[.position.]]</th>
        <th class="report_table_header">[[.reason.]]</th>
		<th class="report_table_header">[[.employee_name.]]</th>
		<th class="report_table_header">[[.status.]]</th>
	</tr>
	<!--LIST:items-->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">
			[[|items.stt|]]		
		</td>
		<td align="center" valign="top" nowrap="nowrap" class="report_table_column">[[|items.date|]]</td>
        <td align="center" valign="top" nowrap="nowrap" class="report_table_column">[[|items.object_code|]]</td>
		<td nowrap align="center" class="report_table_column" width="150">
				[[|items.name|]]	  </td>
		<td nowrap align="center" class="report_table_column" width="150">
				[[|items.object_type|]]			</td>
		<td nowrap align="center" class="report_table_column" width="100">[[|items.room_name|]] </td>
        <td nowrap align="center" class="report_table_column" width="100">[[|items.position|]] </td>
        <td nowrap align="center" class="report_table_column" width="100">[[|items.reason|]] </td>
		<td align="center" nowrap class="report_table_column"> [[|items.employee_name|]] </td>
		<td align="center" nowrap class="report_table_column"> 
		<!--IF:status([[=items.status=]]==1)-->
			[[.paid.]]
		<!--ELSE-->
			[[.notpay.]]
		<!--/IF:status-->
		</td>
	</tr>
	<!--/LIST:items-->
</table>
