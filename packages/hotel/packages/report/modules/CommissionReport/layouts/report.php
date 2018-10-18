<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
        <th width="100px" align="center" nowrap="nowrap" class="report_table_header">[[.recode.]] / [[.room_name.]]</th>
		<th width="100px" align="center" nowrap="nowrap" class="report_table_header">[[.price.]]</th>
		<th width="50px" nowrap="nowrap" class="report_table_header">[[.nights.]]</th>
        <th width="100px" align="center" nowrap="nowrap" class="report_table_header">[[.total_price.]]</th>
		<th width="200px" align="center" nowrap="nowrap" class="report_table_header">[[.customer.]]</th>
		<th width="100px" nowrap="nowrap" class="report_table_header">[[.commission_rate.]]</th>
		<th  width="100px" nowrap="nowrap" class="report_table_header">[[.amount.]]</th>
    </tr>
<!--start: KID them doan nay de tinh tong trang truoc chuyen sang trang sau neu so trang khac 1
<!--IF:first_pages([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="6" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    	<td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_amount']?System::display_number([[=last_group_function_params=]]['total_amount']):'';?></strong></td>
        
    </tr>
<!--/IF:first_pages-->
<!--end:KID-->   
	<!--LIST:items-->
	<tr bgcolor="white">
        <td width="100px" align="center" valign="middle" class="report_table_column"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]] / [[|items.room_name|]] </a></td>
		<td nowrap align="right" class="report_table_column" width="100px"><?php echo System::display_number_report([[=items.price=]]);?></td>
		<?php 
            if ([[=items.night=]]==0){
        ?>
        <td width="50px" align="center" nowrap="nowrap" class="report_table_column">day use</td>
        <?php }
        else
        {?>
        <td width="50px" align="center" nowrap="nowrap" class="report_table_column">[[|items.night|]]</td>
		<?php } ?>
        <td nowrap align="right" class="report_table_column" width="100px"><?php echo System::display_number_report([[=items.total_price=]]);?></td>
        <td width="200px" align="center" class="report_table_column">[[|items.customer_name|]]</td>
		<td width="100px" align="center" nowrap="nowrap" class="report_table_column">[[|items.commission_rate|]]</td>
		<td width="100px" nowrap align="right" class="report_table_column">[[|items.amount|]]</td>
	</tr>
    <input name="old_payment_[[|items.id|]]" type="hidden" id="old_payment_[[|items.id|]]" value="[[|items.paied|]]" />
	<!--/LIST:items-->
	<tr bgcolor="white">
	  <td colspan="6" align="right" nowrap class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
	  <td align="right" nowrap class="report_table_column" id="td_total_amount" style="font-weight:bold;"><?php echo System::display_number([[=group_function_params=]]['total_amount']); ?> </td>
      <td class="report_table_column" style="display:none;">&nbsp;</td>
</tr>
</table>
