<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">[[.stt.]]</th>
		<th class="report_table_header" width="200" align="left">[[.product_name.]]</th>
		<th class="report_table_header" width="200" align="left">[[.unit.]]</th>
        <th class="report_table_header" width="100">[[.price.]]</th>
		<th class="report_table_header" width="100">[[.quantity.]]</th>
        <th class="report_table_header" width="100">[[.total_money.]]</th>
        
	</tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></td>    
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['total']);?></td>
		</tr>
	<!--/IF:first_page-->
    <?php $name = '' ?>
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
    <?php
        if($name != [[=items.name=]] ) 
        {
            $name = [[=items.name=]];
            $kk = 0;
    ?>
	<tr bgcolor="white">
        
		<td align="left"  colspan="4" style="text-indent: 10px;">
            <em>
                <strong>
                    [[.room.]] - [[.karaoke_reservation.]] : [[|items.name|]]
                </strong>
                <?php echo [[=items.pay_with_room=]]==1?Portal::language('pay_with_room').' ('.System::display_number([[=items.amount_pay_with_room=]]).') ':0 ?>
            </em>
        </td>  
        <td></td>
        
        <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number([[=items_commons=]][$name]['total']);?></strong></em></td>        
             
	</tr>
    <?php
        }
        $kk += 1;
    ?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column"><?php echo $kk?></td>
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td align="left" class="report_table_column" >[[|items.name_1|]]</td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.price=]]);?></td> 
        <td align="center" class="report_table_column" >[[|items.quantity|]]</td>
        
         <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.total=]]);?></td>        
        
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr>
        <td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['total']);?></strong></td>
			
		</tr>
</table>
</div>
</div>

<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
            <th>Tổng Tiền Bằng Chữ</th>
            
            <td><?php echo currency_to_text([[=group_function_params=]]['total']);  ?></td>
        </tr>
        <tr>
			
		</tr>
		<tr valign="top">
            <td width="33%" align="center">[[.general_accountant.]]</td>
			<td width="33%" align="center">[[.accountancy.]]</td>
			<td width="33%" align="center">[[.kitchen.]]</td>
			<td width="33%" align="center">[[.cashier.]]</td>
		</tr>
		</table>
