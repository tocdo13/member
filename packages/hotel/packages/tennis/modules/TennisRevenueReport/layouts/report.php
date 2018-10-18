<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th class="report_table_header" width="20">[[.stt.]]</th>
		<th class="report_table_header" width="100">[[.tennis_name.]]</th>
		<th class="report_table_header" width="70">[[.tennis_type.]]</th>
		<th class="report_table_header" width="30">[[.people_number.]]</th>
		<th class="report_table_header">[[.staff.]]</th>
		<th class="report_table_header">[[.guest_name.]]</th>
		<th class="report_table_header">[[.time_in.]]</th>
		<th class="report_table_header">[[.time_out.]]</th>
		<th class="report_table_header" align="right" width="70">[[.tip_amount.]]</th>
		<th class="report_table_header" align="right" width="70">[[.discount.]]</th>
		<th class="report_table_header" align="right" width="70">[[.tax.]]</th>
		<th class="report_table_header" align="right" width="70">[[.total_amount.]]</th>
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="8" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['tip_amount']);
				?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['discount']);
				?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['tax']);
				?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['total_amount']);
				?></b></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" width="20" valign="top" align="left" class="report_table_column">[[|items.stt|]][[|items.id|]]</td>
			<td nowrap align="left" class="report_table_column" width="100">
				[[|items.name|]]				</td>
			<td nowrap align="center" class="report_table_column">
				[[|items.category|]]			</td><td nowrap align="right" class="report_table_column">
				[[|items.people_number|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.staff_name|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.guest_name|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.time_in|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.time_out|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.tip_amount|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.discount|]]
			</td><td nowrap align="right" class="report_table_column">
				[[|items.tax|]]
			</td><td align="right" class="report_table_column">
				[[|items.total_amount|]]
			</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="8" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=group_function_params=]]['tip_amount']);
				?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=group_function_params=]]['discount']);
				?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=group_function_params=]]['tax']);
				?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=group_function_params=]]['total_amount']);
				?></b></td>
		</tr>
		<!--IF:last_page([[=page_no=]]==[[=total_page=]])-->		
		<!--/IF:last_page-->
</table>
</div>
</div>
