<!---------REPORT----------->

<table cellpadding="5" cellspacing="0" width="300px" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:12px;">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="60px">[[.type.]]</th>
		<th class="report_table_header" width="40px">[[.quantity.]]</th>
		<th class="report_table_header" width="140px">[[.total_before_tax.]]</th>
	</tr>
     <!--class="report_table_column"-->
	<tr bgcolor="white">
        <td align="center">[[.food.]]</td>    
		<td align="center" ><?php echo [[=summary=]]['FOOD']['quantity']; ?></td>
        <td align="right" ><?php echo System::display_number([[=summary=]]['FOOD']['price']); ?></td>        
	</tr>
    
    
    <tr bgcolor="white">
        <td align="center" >[[.drink.]]</td>  
		<td align="center" ><?php echo [[=summary=]]['DRINK']['quantity']; ?></td>
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['price']); ?></td>        
	</tr>
     <tr bgcolor="white">
        <td align="center" >[[.Service.]]</td>  
		<td align="center" ><?php echo [[=summary=]]['SERVICE']['quantity']; ?></td>
        <td align="right" ><?php echo System::display_number([[=summary=]]['SERVICE']['price']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.total.]]</strong></td>  
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['price']+[[=summary=]]['FOOD']['price']+[[=summary=]]['SERVICE']['price']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.charge.]]</strong></td>  
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['charge']+[[=summary=]]['FOOD']['charge']+[[=summary=]]['SERVICE']['charge']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.tax.]]</strong></td>  
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['tax']+[[=summary=]]['FOOD']['tax']+[[=summary=]]['SERVICE']['tax']); ?></td>        
	</tr>

    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.total_after_tax.]]</strong></td>  
        <td align="right" ><?php echo System::display_number_report([[=summary=]]['DRINK']['total_after_tax']+[[=summary=]]['FOOD']['total_after_tax']+[[=summary=]]['SERVICE']['total_after_tax']); ?></td>        
	</tr>
    
</table>

<br /><br />

<table cellpadding="5" cellspacing="0" width="300px" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="20px">[[.stt.]]</th>
		<th class="report_table_header" width="160px">[[.product_name.]]</th>
        <th class="report_table_header" width="20px">[[.quantity.]]</th>
        <th class="report_table_header" width="100px">[[.total_after_tax.]]</th>
	</tr>
    
    <?php $category_name = '' ?>
	<!--LIST:summary-->
    <?php
        if($category_name != [[=summary.type=]] ) 
        {
            $category_name = [[=summary.type=]];
    ?>
	<tr bgcolor="white">
		<td align="left"  colspan="4" style="text-indent: 10px;"><em><strong>[[.category.]]: <?php echo [[=summary.type=]];?></strong></em></td>        
	</tr>
    <?php
        }
    ?>
    <?php $stt = 1; ?>
    <!--LIST:summary.child-->
	<tr bgcolor="white">
		<td align="center" ><?php echo $stt++;?></td>
        <td align="left" >[[|summary.child.product_name|]]</td>
        <td align="center" >[[|summary.child.quantity|]]</td>
        <td align="right"  ><?php echo System::display_number_report(round([[=summary.child.total_after_tax=]]));?></td>        
	</tr>
    <!--/LIST:summary.child-->
    
	<!--/LIST:summary-->
</table>
<br />