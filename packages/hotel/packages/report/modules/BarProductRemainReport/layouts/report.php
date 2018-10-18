<div class="report-bound" style=" page:land;">
<form name="BarTableReportForm" method="post">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">      
		<!---------REPORT----------->
		<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
			<tr valign="middle" style="background:#DFDFDF !important;">
				<th class="report_table_header">[[.stt.]]</th>
				<th class="report_table_header">[[.order_id.]]</th>
				<th class="report_table_header">[[.time_in.]]</th>
				<th class="report_table_header">[[.time_out.]]</th>
				<th class="report_table_header">[[.date.]]</th>
                <th class="report_table_header">[[.product_code.]]</th>
				<th class="report_table_header">[[.product_name.]]</th>
                <th class="report_table_header">[[.quantity.]]</th>
                <th class="report_table_header">[[.price.]]</th>
                <th class="report_table_header">[[.total.]]</th>
			</tr>
            
        <!--IF:first_page([[=page_no=]]!=1)-->
        <!---------LAST GROUP VALUE----------->	        
            <tr>
                <td colspan="7" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
            	<td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['quantity']?System::display_number([[=last_group_function_params=]]['quantity']):'';?></strong></td>
                <td align="right" class="report_table_column">&nbsp;</td>
                <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total']?System::display_number([[=last_group_function_params=]]['total']):'';?></strong></td>
            </tr>
    	<!--/IF:first_page-->
        <?php $total = 0;?>			
		<!--LIST:items-->
			<tr bgcolor="white">
				<td class="report_table_column" style="width: 30px; text-align: center;">[[|items.stt|]]</td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.br_id=]])); ?>">[[|items.code|]]</a></td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('H:i',[[=items.time_in=]]);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('H:i',[[=items.time_out=]]);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
				<td class="report_table_column" style="width: 100px; text-align: right;">[[|items.product_id|]]</td>
				<td class="report_table_column" style="width: 60px; text-align: center;">[[|items.name|]]</td>
                <td class="report_table_column" style="width: 60px; text-align: center;">[[|items.quantity|]]</td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo System::display_number([[=items.price=]]);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php $total+= [[=items.price=]] * [[=items.quantity=]];echo System::display_number([[=items.price=]] * [[=items.quantity=]]);?></td>
			</tr>
		<!--/LIST:items-->
		<!---------TOTAL GROUP FUNCTION----------->	
			<tr>
                <td colspan="7" class="report_sub_title" align="right"><b><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
				<td align="center" class="report_table_column"><strong><?php echo [[=group_function_params=]]['quantity']?System::display_number([[=group_function_params=]]['quantity']):'';?></strong></td>
				<td align="right" class="report_table_column">&nbsp;</td>
                <td align="center" class="report_table_column"><strong><?php echo System::display_number($total);?></strong></td>
			</tr>
		</table>
		<br />
        <br />
		<table>
            <tr>
                <td>[[.page.]]</td>
                <td>[[|page_no|]]/[[|total_page|]]</td>
            </tr>
        </table>
        <!--IF:last_page([[=page_no=]]==[[=total_page=]])-->
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
    		<tr>
    			<td></td>
    			<td></td>
    			<td align="center">[[.day.]] <?php echo date('d');?>[[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
    		</tr>
    		<tr valign="top">
    			<td width="33%" align="center">[[.creator.]]</td>
    			<td width="33%" align="center">[[.general_accountant.]]</td>
    			<td width="33%" align="center">[[.director.]]</td>
    		</tr>
    	</table>
		
        <!--/IF:last_page-->
        <p>&nbsp;</p>
    </td>
</tr>
</table>
</form>
<div style="page-break-before:always;page-break-after:always;"></div>
</div>
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
</style>