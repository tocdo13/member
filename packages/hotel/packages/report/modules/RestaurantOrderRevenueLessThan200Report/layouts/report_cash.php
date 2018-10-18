<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
        <th width="10px"  class="report-table-header">[[.stt.]]</th>
        <th width="100px"  class="report-table-header">[[.product_name.]]</th>
        <th width="20px" class="report-table-header">DVT</th>
        <th width="20px"  class="report-table-header">SL</th>  
        <th width="20px"  class="report-table-header">DG</th>
        <th width="100px"  class="report-table-header">[[.total.]]</th>
        <th width="100px"  class="report-table-header">[[.tax_rate.]]</th>     
        <th width="100px"  class="report-table-header">[[.tax_fee.]]</th>
        <th width="100px"  class="report-table-header">[[.amount.]]</th>
        <th width="100px" class="report-table-header">[[.note.]]</th>
	</tr>

	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
        	<td align="right" class="report_table_column"><strong><?php echo (!([[=last_group_function_params=]]['total_after_fee'])?'':System::display_number([[=last_group_function_params=]]['total_after_fee']));?></strong></td>
        	<td align="right"  class="report_table_column" >&nbsp;</td>
            <td align="right" class="report_table_column"><strong><?php echo (!([[=last_group_function_params=]]['tax_fee'])?'':System::display_number([[=last_group_function_params=]]['tax_fee']));?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo (!([[=last_group_function_params=]]['total'])?'':System::display_number([[=last_group_function_params=]]['total']));?></strong></td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr <?php Draw::hover('#FFFF33')?> bgcolor="white">
		<td align="center" class="report_table_column">[[|items.stt|]]</td>
        <td align="center" class="report_table_column">[[|items.description|]]</td>
        <td align="center" class="report_table_column"></td>
        <td align="center" class="report_table_column"></td>
        <td align="center" class="report_table_column"></td>
        <td align="right" class="report_table_column"><?php echo [[=items.total_after_fee=]]?System::display_number([[=items.total_after_fee=]]):'';?></td>
        <td align="center" class="report_table_column">[[|items.tax_rate|]]%</td>
        <td align="right" class="report_table_column"><?php echo [[=items.tax_fee=]]?System::display_number([[=items.tax_fee=]]):'';?></td>
        <td align="right" class="report_table_column"><?php echo [[=items.total=]]?System::display_number([[=items.total=]]):'';?></td>
        <td align="center" class="report_table_column" onclick="window.open('[[|items.link|]]');"><a>[[|items.code|]]</a></td>  
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="5" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="right" class="report_table_column"><strong><?php echo (!([[=group_function_params=]]['total_after_fee'])?'':System::display_number([[=group_function_params=]]['total_after_fee']));?></strong></td>
        <td align="right"  class="report_table_column" >&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo (!([[=group_function_params=]]['tax_fee'])?'':System::display_number([[=group_function_params=]]['tax_fee']));?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo (!([[=group_function_params=]]['total'])?'':System::display_number([[=group_function_params=]]['total']));?></strong></td>
        <td align="right"  class="report_table_column" >&nbsp;</td>
    </tr>
</table>
</div>
</div>
