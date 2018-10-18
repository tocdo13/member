<!---------REPORT----------->
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF" >
		<th  nowrap="nowrap" align="left">[[.stt.]]</th>
		<th align="center"  nowrap="nowrap">[[.date.]]</th>
		<th>[[.product_name.]]</th>
		<th>[[.room_id.]]</th>
		<th>[[.unit_name.]]</th>
		<th>[[.quantity.]]</th>
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">
			[[|items.stt|]]		</td>
		<td align="center" valign="top" nowrap="nowrap" class="report_table_column">[[|items.date|]]</td>
		<td nowrap align="center" class="report_table_column" width="150">
				[[|items.product_name|]]	  </td>
		<td align="center" nowrap class="report_table_column"> [[|items.room_name|]]</td>
		<td align="center" nowrap class="report_table_column"> [[|items.unit_name|]] </td>
		<td align="right" nowrap class="report_table_column"> [[|items.quantity|]] </td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->
		<!--IF:last_page([[=page_no=]]==[[=total_page=]])-->
		<tr class="report_table_column">
			<th colspan="5" align="right" style="font-size:12px;">[[.grant_total.]]:</th>
			<td align="right" nowrap="nowrap"><?php echo System::display_number([[=grant_total=]]);?></td>
		</tr>
		<!--/IF:last_page-->
</table>