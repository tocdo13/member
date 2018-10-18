<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">[[.stt.]]</th>
		<th class="report_table_header">[[.order_id.]]</th>
		<th class="report_table_header" width="100">[[.date.]]</th>
		<th class="report_table_header" width="200" align="left">[[.customers.]]</th>
		<th class="report_table_header" width="200" align="left">[[.total_paid.]]</th>
		<th class="report_table_header" width="100">[[.debit.]]</th>
        <th class="report_table_header" width="100">[[.total_amount.]]</th>
        <th class="report_table_header">[[.user_create.]]</th>
	</tr>
    <?php //System::debug([[=last_group_function_params=]]); ?>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
            <td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['paid']);?></b></td>
			<td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['debit']);?></b></td>
            <td align="right" class="report_table_column"><b><?php echo System::display_number_report([[=last_group_function_params=]]['total']);?></b></td>
            <td></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
    <!--IF:cond([[=items.room_name=]] == '')-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap align="center" class="report_table_column" width="100"><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>">[[|items.code|]]</a></td>
        <td nowrap align="center" class="report_table_column" ><?php echo date('h:i d/m/Y',[[=items.time_out=]]);?></td>
        <td align="left" class="report_table_column" >[[|items.customer_name|]]</td>
        <td align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.paid=]]);?></td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.debit=]]);?></td> 
         <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.total=]]);?></td>        
        <td nowrap align="center" class="report_table_column" >[[|items.receptionist_id|]]</td>
	</tr>
    <!--/IF:cond-->
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['paid']);?></strong></td>
			<td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['debit']);?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['total']);?></strong></td>
			<td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>
</div>
</div>
