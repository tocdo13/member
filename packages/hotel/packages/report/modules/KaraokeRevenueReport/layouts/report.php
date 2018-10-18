<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="1%">[[.stt.]]</th>
		<th class="report_table_header" width="200" align="left">[[.product_code.]]</th>
		<th class="report_table_header" width="200" align="left">[[.product_name.]]</th>
        <!--<th class="report_table_header" width="100">[[.price.]]</th>-->
		<th class="report_table_header" width="100">[[.quantity.]]</th>
        <th class="report_table_header" width="100">[[.promotion.]]</th>
        <th class="report_table_header" width="100">[[.discount.]]</th>
        <th class="report_table_header" width="100">[[.total_before_tax.]]</th>
        <!--<th class="report_table_header">[[.user_create.]]</th>-->
	</tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="3" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></td>
            <td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['promotion']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['discount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number_report([[=last_group_function_params=]]['total']);?></td>
		</tr>
	<!--/IF:first_page-->
    <?php $category_name = '' ?>
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
    <?php
        if($category_name != [[=items.category_name=]] ) 
        {
            $category_name = [[=items.category_name=]];
    ?>
	<tr bgcolor="white">
		<td align="left"  colspan="3" style="text-indent: 10px;"><em><strong>[[.category.]]: [[|items.category_name|]]</strong></em></td>  
        <td align="center" class="report_table_column" ><em><strong><?php echo System::display_number([[=items_commons=]][$category_name]['quantity']);?></strong></em></td>
        <td align="center" class="report_table_column" ></td>
        <td align="right" class="report_table_column" ></td>
        <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number([[=items_commons=]][$category_name]['total']);?></strong></em></td>        
        <!--<td nowrap align="center" class="report_table_column" ></td>    -->  
	</tr>
    <?php
        }
    ?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_id|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <!--<td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.price=]]);?></td> -->
        <td align="center" class="report_table_column" >[[|items.quantity|]]</td>
        <td align="center" class="report_table_column" >[[|items.promotion|]]</td>
        <td align="right" class="report_table_column" ><!--IF:cond_discount([[=items.discount=]]>0)-->
        												<?php echo System::display_number_report([[=items.discount=]]);?>
                                                        <!--/IF:cond_discount--></td>
         <td nowrap align="right" class="report_table_column" ><?php echo System::display_number_report([[=items.total=]]);?></td>        
        <!--<td nowrap align="center" class="report_table_column" >[[|items.user_id|]]</td>-->
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr>
            <td colspan="3" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
			<td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['promotion']);?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['discount']);?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number_report([[=group_function_params=]]['total']);?></strong></td>
			<!--<td align="right" class="report_table_column">&nbsp;</td>-->
		</tr>
</table>
</div>
</div>
