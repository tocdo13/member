<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">[[.stt.]]</th>
		<th class="report_table_header" width="200" align="left">[[.product_code.]]</th>
		<th class="report_table_header" width="200" align="left">[[.product_name.]]</th>
        <th class="report_table_header" width="100">[[.price.]]</th>
		<th class="report_table_header" width="100">[[.quantity.]]</th>
        <th class="report_table_header" width="100">[[.promotion.]]</th>
        <th class="report_table_header" width="100">[[.discount.]]</th>
        <th class="report_table_header" width="100">[[.total.]]</th>
        <th class="report_table_header">[[.user_create.]]</th>
	</tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></td>
            <td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['promotion']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['discount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['total']);?></td>
            <td align="right" class="report_table_column">&nbsp;</td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_id|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number([[=items.price=]]);?></td> 
        <td align="center" class="report_table_column" >[[|items.quantity|]]</td>
        <td align="center" class="report_table_column" >[[|items.promotion|]]</td>
        <td align="right" class="report_table_column" >
        <!--IF:cond_discount([[=items.discount=]]>0)-->
		<?php echo System::display_number([[=items.discount=]]);?>
        <!--/IF:cond_discount-->
        </td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number([[=items.total=]]);?></td>        
        <td nowrap align="center" class="report_table_column" >[[|items.user_id|]]</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
		<td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['promotion']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['discount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total']);?></strong></td>
		<td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>
</div>
</div>
