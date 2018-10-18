<br />
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse;">
	<tr>
    	<th class="report_table_header">[[.Content.]]</th>
    	<th class="report_table_header">[[.total_before_tax.]]</th>
    	<th class="report_table_header">[[.VAT.]]</th>
    	<th class="report_table_header">[[.total_amount.]]</th>
    </tr>
    <!--LIST:items-->
    <tr>
    	<td>[[|items.name|]]</td>
    	<td width="25%" align="right"><?php echo System::display_number([[=items.total_amount_before_tax=]]);?></td>
    	<td width="25%" align="right"><?php echo System::display_number([[=items.total_tax=]]);?></td>
    	<td width="25%" align="right"><?php echo System::display_number([[=items.total_amount=]]);?></td>                        
    </tr>
    <!--/LIST:items-->
    <tr>
    	<td>[[.Total.]]</td>
        <td align="right"><strong><?php echo System::display_number([[=total_amount_before_tax=]]);?></strong></td>
        <td align="right"><strong><?php echo System::display_number([[=total_amount=]]-[[=total_amount_before_tax=]]);?></strong></td>
        <td align="right"><strong><?php echo System::display_number([[=total_amount=]]);?></strong></td>                
    </tr>
</table>