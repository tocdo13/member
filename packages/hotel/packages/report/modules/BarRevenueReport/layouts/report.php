<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">[[.stt.]]</th>
		<th class="report_table_header">[[.order_id.]]</th>
		<th class="report_table_header" width="100">[[.code.]]</th>
        <th class="report_table_header" width="100">[[.date.]]</th>
        <th class="report_table_header" width="100">[[.discount.]]</th>
        <th class="report_table_header" width="100">[[.service_rate.]]</th>
        <th class="report_table_header" width="100">[[.tax_rate.]]</th>
        <th class="report_table_header" width="100">[[.total.]]</th>
        <th class="report_table_header" width="100">[[.total_bar_other.]]</th>
        <th class="report_table_header">[[.user_create.]]</th>
	</tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			
            <td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['discount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['service_amount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['tax_amount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['total']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['total_other']);?></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap align="center" class="report_table_column" width="100"> [[|items.id|]]</td>
        <td align="center" class="report_table_column" >[[|items.code|]]</td>
        <td nowrap align="center" class="report_table_column" ><?php echo date('h:i d/m/Y',[[=items.time_out=]]);?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.discount=]]);?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.service_amount=]]);?></td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.tax_amount=]]);?></td> 
        <td align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.total=]]);?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.total_other=]]);?></td>       
        <td nowrap align="center" class="report_table_column" >[[|items.user_id|]]</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
			
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['service_amount']);?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['tax_amount']);?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['total']);?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['total_other']);?></strong></td>
			<td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>
</div>
</div>
