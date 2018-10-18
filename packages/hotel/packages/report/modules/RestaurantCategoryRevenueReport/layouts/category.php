<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">[[.stt.]]</th>
		<th class="report_table_header">[[.product_code.]]</th>
		<th class="report_table_header">[[.product_name.]]</th>
		<th class="report_table_header">[[.SL.]]</th>
		<th class="report_table_header">[[.KM.]]</th>
		<th class="report_table_header">[[.price.]]</th>
		<th class="report_table_header">[[.amount.]]</th>
		<th class="report_table_header">[[.discount.]]</th>
		<th class="report_table_header">[[.direct_payment.]]</th>
		<th class="report_table_header">[[.pay_by_room.]]</th>
		<th class="report_table_header">[[.free.]]</th>
		<th class="report_table_header">[[.debt.]]</th>
		<th class="report_table_header">[[.total.]]</th>
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="6" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['amount']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['discount']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['pay_by_cash']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['pay_by_room']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['free']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['debt']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['total']);?></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap class="report_table_column" width="100px">[[|items.id|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td nowrap align="right" class="report_table_column" width="20px">[[|items.quantity|]]</td>
        <td nowrap align="right" class="report_table_column" width="20px">[[|items.quantity_discount|]]</td>
        <td nowrap align="right" class="report_table_column"width="70px" >[[|items.price|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px">[[|items.amount|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px">[[|items.discount|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px">[[|items.pay_by_cash|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px">[[|items.pay_by_room|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px" >[[|items.free|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px">[[|items.debt|]]</td>
        <td nowrap align="right" class="report_table_column" width="70px">[[|items.total|]]</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="6" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['amount']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['discount']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['pay_by_cash']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['pay_by_room']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['free']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['debt']);?></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=group_function_params=]]['total']);?></td>
		</tr>
		<!--IF:last_page([[=page_no=]]==[[=total_page=]])-->
		<tr class="report_table_column">
			<th colspan="8" align="right">[[.total_fee.]]:</th>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=pay_by_cash_fee=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=pay_by_room_fee=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=free_fee=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=debt_fee=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=fee=]]);?></td>
		</tr>
		<tr class="report_table_column">
			<th colspan="8" align="right">[[.total_tax.]]:</th>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=pay_by_cash_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=pay_by_room_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=free_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=debt_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=tax=]]);?></td>
		</tr>
		<tr class="report_table_column"><th colspan="8" align="right">[[.total.]]:</th>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=group_function_params=]]['pay_by_cash']+[[=pay_by_cash_fee=]]+[[=pay_by_cash_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=group_function_params=]]['pay_by_room']+[[=pay_by_room_fee=]]+[[=pay_by_room_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=group_function_params=]]['free']+[[=free_fee=]]+[[=free_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=group_function_params=]]['debt']+[[=debt_fee=]]+[[=debt_tax=]]);?></td>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=group_function_params=]]['total']+[[=fee=]]+[[=tax=]]);?></td>
		</tr>
		<!--/IF:last_page-->
</table>
</div>
</div>
