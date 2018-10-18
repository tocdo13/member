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
        <td align="center"  colspan="2" ><strong>[[.total.]]</strong></td>  
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['price']+[[=summary=]]['FOOD']['price']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.charge.]]</strong></td>  
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['charge']+[[=summary=]]['FOOD']['charge']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.tax.]]</strong></td>  
        <td align="right" ><?php echo System::display_number([[=summary=]]['DRINK']['tax']+[[=summary=]]['FOOD']['tax']); ?></td>        
	</tr>

    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong>[[.total_after_tax.]]</strong></td>  
        <td align="right" ><?php echo System::display_number_report([[=summary=]]['DRINK']['total_after_tax']+[[=summary=]]['FOOD']['total_after_tax']); ?></td>        
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
	<!--LIST:product-->
    
    <?php
        if($category_name != [[=product.category_name=]] ) 
        {
            $category_name = [[=product.category_name=]];
    ?>
	<tr bgcolor="white">
		<td align="left"  colspan="4" style="text-indent: 10px;"><em><strong>[[.category.]]: [[|product.category_name|]]</strong></em></td>        
	</tr>
    <?php
        }
    ?>
    
	<tr bgcolor="white">
		<td align="center" >[[|product.stt|]]</td>
        <td align="left" >[[|product.product_name|]]</td>
        <td align="center" >[[|product.quantity|]]</td>
        <td align="right"  ><?php echo System::display_number_report(round([[=product.total_after_tax=]]));?></td>        
	</tr>
	<!--/LIST:product-->
</table>
<br />