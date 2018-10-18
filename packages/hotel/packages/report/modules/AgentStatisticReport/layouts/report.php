<style>
.hover:hover{
    background: #CCCCCC;
}
</style>
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
    <td align="center">
    	<table border="0" cellSpacing="0" cellpadding="5" width="100%">
    		<tr valign="middle">
                <td width="100"><img src="<?php echo HOTEL_LOGO;?>" width="100" /></td>
                <td align="left">
                    <br />
                    <strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                    ADD: <?php echo HOTEL_ADDRESS;?>
                    <br />
                    Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?>
                    <br />
                    Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
                </td>
                <td align="right">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
                </td>
    		</tr>
            <tr><td>&nbsp;</td></tr>	
    		<tr>
    			<td colspan="3" style="text-align:center;">
                    <font class="report_title specific" >[[.agent_or_company_statistic_report.]]<br /><br /></font>
                    <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                        [[.date_from.]]&nbsp;[[|date_from|]]&nbsp;[[.date_to.]]&nbsp;[[|date_to|]]
                    </span> 
    			</td>
    		</tr>
    	</table>
    </td>
</tr>
</table>
<!--/IF:first_page-->
<!--IF:check(isset([[=items=]]))-->

<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>

<table  cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="border-collapse:collapse; font-size:11px;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="50px" class="report-table-header">[[.no.]]</th>
		<th width="300px" class="report-table-header">[[.company.]]</th>
        <th width="100px" class="report-table-header">[[.no_of_adult.]]</th>
        <th width="100px" class="report-table-header">[[.no_of_child.]]</th>
		<th width="100px" class="report-table-header">[[.no_of_room.]]</th>
		<th width="100px" class="report-table-header">[[.no_of_night.]]</th>
		<th width="100px" class="report-table-header">[[.revenue.]]</th>
		<th width="100px" class="report-table-header">[[.money_per_night.]]</th>
    </tr>
 <!--start: KID thêm đoạn này để tính tổng của trang trước chuyển sang nếu số trang khác 1
 <!--IF:first_pages([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="2" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    	<td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_adult']?System::display_number([[=last_group_function_params=]]['total_adult']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_child']?System::display_number([[=last_group_function_params=]]['total_child']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_room']?System::display_number([[=last_group_function_params=]]['total_room']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_night']?System::display_number([[=last_group_function_params=]]['total_night']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_money']?System::display_number([[=last_group_function_params=]]['total_money']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong>&nbsp;</td>
        
    </tr>
<!--/IF:first_pages-->
<!--end:KID-->   <?php $stt=1; ?>
	<!--LIST:items-->
	<tr bgcolor="white" class="hover">
		<td align="center" class="report-table-column"><?php echo $stt++; ?></td>
		<td align="left"  class="report-table-column">[[|items.customer_name|]]</td>
        <td align="right" class="report-table-column"><?php echo System::display_number([[=items.total_adult=]]);?></td>
        <td align="right" class="report-table-column"><?php echo System::display_number([[=items.total_child=]]);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number([[=items.total_room=]]);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number([[=items.total_night=]]);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number([[=items.total_money_after=]]);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number([[=items.total_per_night=]]);?></td>
	</tr>
	<!--/LIST:items-->
    <tr class="hover">
        <td colspan="2" class="report_sub_title" align="right"><b><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_adult']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_child']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_room']);?></strong></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_night']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_money_after']);?></strong></td>        
        <td align="right" class="report_table_column"><strong></strong></td>
	</tr>
</table>
<center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center>

<!--/IF:check-->


<!--IF:end_page([[=real_page_no=]]==[[=real_total_page=]])-->

<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center">[[.creator.]]</td>
			<td width="33%" align="center">[[.general_accountant.]]</td>
			<td width="33%" align="center">[[.director.]]</td>
		</tr>
		</table>
		<p>&nbsp;</p>
		<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>
