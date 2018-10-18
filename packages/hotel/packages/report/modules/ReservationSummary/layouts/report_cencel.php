<!---------REPORT----------->	

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="0%" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
		<th width="1%" align="center" nowrap="nowrap" class="report_table_header">[[.code.]]</th>
		<th class="report_table_header" nowrap="nowrap" >[[.customer_name.]]</th>
		<th class="report_table_header" nowrap="nowrap" >[[.status.]]</th>
		<th class="report_table_header" nowrap="nowrap" >[[.gender.]]</th>
		<th class="report_table_header" nowrap="nowrap" >[[.birth_date.]]</th>
		<th class="report_table_header"  nowrap="nowrap">[[.nationality.]]</th>
		<th class="report_table_header"  nowrap="nowrap">[[.passport.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.room.]]</th>
		<!--IF:status(!URL::get('status'))-->
		<th nowrap="nowrap" class="report_table_header">[[.status.]]</th>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
	  <th class="report_table_header">[[.price.]]<br/></th>
		<!--/IF:price-->
		<th nowrap="nowrap" class="report_table_header">[[.time_in.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.time_out.]]</th>
	    <th nowrap="nowrap" class="report_table_header">[[.note.]]</th>
	</tr>
	<!--LIST:items-->
	<tr bgcolor="white">
		<td width="5" align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
		<td width="5" align="center" valign="middle" class="report_table_column">[[|items.id|]]</td>
		<td nowrap align="left" class="report_table_column" width="150">
			[[|items.first_name|]]
		[[|items.last_name|]]</td>
		<td nowrap align="center" class="report_table_column" width="30">[[|items.status|]] </td>
		<td nowrap align="center" class="report_table_column" width="30">
			[[|items.gender|]]		</td>
		<td  nowrap align="center" class="report_table_column">[[|items.birth_date|]]</td>
		<td nowrap align="center" class="report_table_column">
			[[|items.nationality|]]		</td>
		<td width="1%" align="center" class="report_table_column">[[|items.passport|]]</td>
		<td width="1%" nowrap align="center" class="report_table_column">[[|items.room_name|]]</td>
		<!--IF:status(!URL::get('status'))-->
		<td width="1%" nowrap align="center" class="report_table_column">[[|items.status|]]</td>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
		<td width="1%" nowrap align="center" class="report_table_column">
		<!--IF:cond([[=items.price=]])-->
			[[|items.price|]] 
		<!--/IF:cond-->		</td>
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
		<td colspan="2" align="center" valign="middle" class="report_table_column"><strong>[[.total.]]</strong></td>
		<td nowrap align="center" class="report_table_column" width="150"><strong>[[|guest_count|]]</strong></td>
		<td colspan="5" nowrap align="center" class="report_table_column" width="30">&nbsp;</td>
		<td align="center" class="report_table_column" width="50"><strong>[[|room_count|]]</strong></td>
		<!--IF:status(!URL::get('status'))-->
		<td>&nbsp;</td>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
		<td width="1%" nowrap align="center" class="report_table_column">
			<strong><?php echo System::display_number([[=total_price=]]);?></strong>		</td>
		<!--/IF:price-->		
		<td colspan="3"></td>
	</tr>
	<!--/IF:first_page-->
</table>
