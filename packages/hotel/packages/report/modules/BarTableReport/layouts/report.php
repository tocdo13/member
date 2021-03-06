<div class="report-bound" style=" page:land;">
<form name="BarTableReportForm" method="post">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">      
		<!---------REPORT----------->
		<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
			<tr valign="middle" bgcolor="#EFEFEF">
				<th class="report_table_header">[[.stt.]]</th>
				<th class="report_table_header">[[.invoice.]]</th>
				<th class="report_table_header">[[.bar_table.]]</th>
				<th class="report_table_header">[[.time_in.]]</th>
				<th class="report_table_header">[[.time_out.]]</th>
                <th class="report_table_header">[[.total.]]</th>
				<th class="report_table_header">[[.status.]]</th>
			</tr>
            
        <!--IF:first_page([[=page_no=]]!=1)-->
        <!---------LAST GROUP VALUE----------->	        
            <tr>
                <td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
            	<td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total']?System::display_number([[=last_group_function_params=]]['total']):'';?></strong></td>
                <td align="right" class="report_table_column">&nbsp;</td>
            </tr>
    	<!--/IF:first_page-->
        			
		<!--LIST:items-->
			<tr bgcolor="white">
				<td class="report_table_column" style="width: 30px; text-align: center;">[[|items.stt|]]</td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]],'table_id'=>[[=items.table_id=]],'bar_id'=>[[=items.bar_id=]])); ?>">[[|items.code|]]</a></td>
                <td class="report_table_column" style="width: 150px; text-align: left;">[[|items.table_name|]]</td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('H:i',[[=items.arrival_time=]]);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('H:i',[[=items.departure_time=]]);?></td>
				<td class="report_table_column" style="width: 100px; text-align: right;"><?php echo System::display_number([[=items.total=]]);?></td>
				<td class="report_table_column" style="width: 60px; text-align: center;">[[|items.status|]]</td>
			</tr>
		<!--/LIST:items-->
		<!---------TOTAL GROUP FUNCTION----------->	
			<tr>
                <td colspan="5" class="report_sub_title" align="right"><b><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
				<td align="right" class="report_table_column"><strong><?php echo [[=group_function_params=]]['total']?System::display_number([[=group_function_params=]]['total']):'';?></strong></td>
				<td align="right" class="report_table_column">&nbsp;</td>
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
		<table width="100%" style="font-family:'Times New Roman', Times, serif; font-size:11px;">
    		<tr>
    			<td></td>
    			<td></td>
    			<td align="center">[[.day.]] <?php echo date('d');?>[[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
    		</tr>
    		<tr valign="top">
    			<td width="33%" align="center">&nbsp;</td>
    			<td width="33%" align="center">&nbsp;</td>
    			<td width="33%" align="center">[[.creator.]]</td>
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