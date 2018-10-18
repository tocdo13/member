<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" style="width:1%" nowrap="nowrap">[[.stt.]]</th>
		<th class="report_table_header">[[.product_code.]]</th>
		<th class="report_table_header">[[.product_name.]]</th>
		<th class="report_table_header">[[.unit.]]</th>
		<th class="report_table_header">[[.sold_quantity.]]</th>
		<th class="report_table_header">[[.discount_quantity.]]</th>
		<th class="report_table_header">[[.price.]]</th>
		<th class="report_table_header">[[.amount.]]</th>
		<th class="report_table_header">[[.discount.]]</th>
		<th class="report_table_header">[[.total.]]</th>
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="7" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['amount']);
				?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['discount']);
				?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['total']);
				?></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">
			[[|items.stt|]]
		</td>
		<td nowrap align="center" class="report_table_column" width="60">
				[[|items.product_code|]]
			</td><td nowrap align="left" class="report_table_column" width="200">
				[[|items.product_name|]]
			</td><td nowrap align="center" class="report_table_column" width="50">
				[[|items.unit|]]
			</td><td nowrap align="right" class="report_table_column" width="60">
				[[|items.quantity|]]
			</td><td nowrap align="right" class="report_table_column" width="70">
				[[|items.quantity_discount|]]
			</td><td nowrap align="right" class="report_table_column" width="70">
				[[|items.price|]]
			</td><td nowrap align="right" class="report_table_column" width="70">
				[[|items.amount|]]
			</td><td nowrap align="right" class="report_table_column" width="70">
				[[|items.discount|]]
			</td><td nowrap align="right" class="report_table_column" width="70">
				[[|items.total|]]
			</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="7" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
			<td align="right" class="report_table_column"><?php echo System::display_number_report([[=group_function_params=]]['amount']);
				?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number_report([[=group_function_params=]]['discount']);
				?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number_report([[=group_function_params=]]['total']);
				?></td>
		</tr>
		<!--IF:last_page([[=page_no=]]==[[=total_page=]])-->
		<tr class="report_table_column">
			<th colspan="9" align="right">[[.total_tax.]]:</th>
			<td align="right" nowrap="nowrap"><?php echo System::display_number_report([[=tax=]]);?></td>
		</tr>
		<tr class="report_table_column"><th colspan="9" align="right">[[.total_revenue.]]:</th>
			<td align="right" nowrap="nowrap"><?php echo System::display_number_report([[=group_function_params=]]['total']+[[=tax=]]);?></td>
		</tr>
		<!--/IF:last_page-->
</table>
</div>
</div>