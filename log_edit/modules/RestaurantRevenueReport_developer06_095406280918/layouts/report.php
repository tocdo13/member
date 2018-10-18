<!---------REPORT----------->
<button id="export">[[.export.]]</button>
<table  id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="10">[[.stt.]]</th>
		<th class="report_table_header" width="50" align="left">[[.product_code.]]</th>
        <th class="report_table_header" width="50" align="left">[[.unit.]]</th>
		<th class="report_table_header" width="300" align="left">[[.product_name.]]</th>
        <th class="report_table_header" width="70">[[.price.]]</th>
		<th class="report_table_header" width="30">[[.quantity.]]</th>
        <th class="report_table_header" width="30">[[.promotion.]]</th>
        <th class="report_table_header" width="70">[[.discount.]]</th>
        <th class="report_table_header" width="100">[[.total_before_tax.]]</th>
	</tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></td>
            <td align="center" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['promotion']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['discount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['total']));?></td>
		</tr>
	<!--/IF:first_page-->
    <?php $category_name = '';
    $category_parent_name = false;
     ?>
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
    <?php
        if($category_parent_name !=[[=items.category_parent_name=]])
        {
            $category_parent_name = [[=items.category_parent_name=]];
            ?>
            <tr style="background-color: #6AA84F;">
		      <td align="left"  colspan="8" style="text-indent: 10px;"><em><strong><?php echo mb_strtoupper([[=items.category_parent_name=]],'UTF-8');?></strong></em></td>
              <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number(round([[=items_commons=]][$category_parent_name]['total_parent']));?></strong></em></td>  
	       </tr>
            <?php 
        }
        
        if($category_name != [[=items.category_name=]] ) 
        {
            $category_name = [[=items.category_name=]];
    ?>
	<tr bgcolor="white">
		<td align="left"  colspan="5" style="text-indent: 10px;"><em><strong>[[.category.]]: [[|items.category_name|]]</strong></em></td>  
        <td align="center" class="report_table_column" ><em><strong><?php echo System::display_number(round([[=items_commons=]][$category_name]['quantity']));?></strong></em></td>
        <td align="center" class="report_table_column" ></td>
        <td align="right" class="report_table_column" ></td>
        <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number(round([[=items_commons=]][$category_name]['total']));?></strong></em></td>        
            
	</tr>
    <?php
        }
    ?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_id|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_unit|]]</td>
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number(round([[=items.price=]]));?></td> 
        <td align="center" class="report_table_column" >[[|items.quantity|]]</td>
        <td align="center" class="report_table_column" >[[|items.promotion|]]</td>
        <td align="right" class="report_table_column" ><!--IF:cond_discount([[=items.discount=]]>0)-->
        												<?php echo System::display_number(round([[=items.discount=]]));?>
                                                        <!--/IF:cond_discount--></td>
         <td nowrap align="right" class="report_table_column" ><?php echo System::display_number(round([[=items.total=]]));?></td>        
        
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="5" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['quantity']));?></strong></td>
			<td align="center" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['promotion']));?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['discount']));?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total']));?></strong></td>
			
		</tr>
        <!--/** START : DAT-1343 **/-->
        <tr>
            <td colspan="9" align="right" class="report_table_column"><strong>[[.total_discount_amount_invoice.]] : <?php echo System::display_number_report([[=total_discount=]]); ?> [[.change_to_after_tax.]] : <?php echo System::display_number(round([[=total_discount_after_tax=]])); ?></strong></td>
        </tr>
        <tr>
            <td colspan="9" align="right" class="report_table_column"><strong>[[.total.]] : <?php echo System::display_number(round([[=group_function_params=]]['total'] - [[=total_discount_after_tax=]])); ?></strong></td>
        </tr>
        <!--/** END : DAT-1343 **/-->
</table>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    });
</script>