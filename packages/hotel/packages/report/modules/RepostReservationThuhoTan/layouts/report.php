<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="80%%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">STT</th>
		
		<th class="report_table_header" width="200" align="left">[[.goods.]]</th>
        <th class="report_table_header" width="100">ĐVT</th>
        <th class="report_table_header" width="100">Giá</th>
		<th class="report_table_header" width="100">[[.quantity.]]</th>
        <th class="report_table_header" width="100">Tổng Tiền</th>
      
	</tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></td>
            
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['total']);?></td>
		</tr>
	<!--/IF:first_page-->
    <?php $room_name = '' ?>
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
    <?php
        if($room_name != [[=items.room_name=]] ) 
        {
            $room_name = [[=items.room_name=]];
             $kk = 0;
    ?>
	<tr bgcolor="white">
		<td align="left" class="report_table_column" ></td>
        <td align="left"  style="text-indent: 10px;"><em><strong>[[.room.]]: [[|items.room_name|]]</strong></em></td>  
        
        <td align="center" colspan="3" class="report_table_column" >&nbsp;</td>
        
        <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number([[=items_commons=]][$room_name]['total']);?></strong></em></td>        
              
	</tr>
    <?php
        }
        $kk += 1; 
    ?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column"><?php echo $kk?></td>
        
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td align="center" class="report_table_column" >[[|items.unit_name|]]</td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.price=]]);?></td> 
        <td align="center" class="report_table_column" >[[|items.quantity|]]</td>
        
        
         <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.total=]]);?></td>        
        
	
    </tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
		
           
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['total']);?></strong></td>
			
		</tr>
</table>
</div>
</div>
