<style>
.back_title{background:#cdcdcd;}
</style>

<!---------REPORT----------->
<!--IF:check_product(isset([[=items=]]))-->
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
    <tr>
        <td colspan="4" class="back_title"><span style="  margin-right: 15px;">[[.danh_muc.]]:</span>[[|items.id|]]</td>
        <td style="text-align: center;" class="back_title"> [[|items.quantity_all|]]</td>
        <td colspan="3" style="text-align: right;" class="back_title"><?php echo System::display_number([[=items.total_all=]]);?></td>
    </tr>
    <!--LIST:items.child-->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.child.stt|]]</td>
        <td align="left" class="report_table_column" >[[|items.child.product_id|]]</td>
        <td align="left" class="report_table_column" >[[|items.child.product_name|]]</td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number([[=items.child.price=]]);?></td> 
        <td align="center" class="report_table_column" >[[|items.child.quantity|]]</td>
        <td align="center" class="report_table_column" >[[|items.child.quantity_discount|]]</td>
        <td align="right" class="report_table_column" >
        <!--IF:cond_discount([[=items.child.discount=]]>0)-->
		<?php echo System::display_number([[=items.child.discount=]]);?>
        <!--/IF:cond_discount-->
        </td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number([[=items.child.total=]]);?></td>        
       
	</tr>
    <!--/LIST:items.child-->
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
		<td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity_discount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['discount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total']);?></strong></td>
	
		</tr>
</table>

</div>
</div>
<!--IF:page_no([[=page_no=]])--><center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center><!--/IF:page_no--><br>
<!--ELSE-->
<strong>[[.no_data.]]</strong><br>
<a href="<?php echo Url::build_current();?>">[[.back.]]</a>
<!--/IF:check_product-->