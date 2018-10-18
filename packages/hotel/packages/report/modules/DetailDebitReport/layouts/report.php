<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:12px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="15px" align="center" nowrap="nowrap" class="report_table_header">[[.no.]]</th>
        <th width="20px" align="center" nowrap="nowrap" class="report_table_header">[[.recode.]]</th>
		<th width="30px" class="report_table_header" align="left" nowrap="nowrap" >[[.Booking_code.]]</th>
		<th width="40px" align="center" nowrap="nowrap" class="report_table_header">[[.num_people.]] A/C</th>
		<th width="50px" nowrap="nowrap" class="report_table_header">[[.room_name.]]</th>
		<th width="15px" align="center" nowrap="nowrap" class="report_table_header">[[.no_of_room.]]</th>
		<th width="20px" align="center" nowrap="nowrap" class="report_table_header">[[.nights.]]</th>
		<th width="50px" nowrap="nowrap" class="report_table_header">[[.check_in_date.]]</th>
		<th width="50px" nowrap="nowrap" class="report_table_header">[[.check_out_date.]]</th>
		<th width="100px" nowrap="nowrap" class="report_table_header">[[.total.]]</th>
       
    </tr>
   <!--start: KID them doan nay den tinh tong trang truoc chuyen sang trang sau 1
 <!--IF:first_pages([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="9" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    	<td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_debit']?System::display_number([[=last_group_function_params=]]['total_debit']):'';?></strong></td>
        
    </tr>
<!--/IF:first_pages-->
<!--end:KID-->   
	<!--LIST:items-->
	<tr bgcolor="white">
		<td align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
        <td align="center" valign="middle" class="report_table_column"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]</a></td>
		<td nowrap align="left" class="report_table_column">
		[[|items.booking_code|]]</td>
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.num_people|]]/<?php echo ([[=items.num_child=]]==''?0:[[=items.num_child=]]);?></td>
		<td align="right" class="report_table_column">[[|items.rooms_stay|]]</td>
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.num_room|]]</td>
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.night|]]</td>
		<td nowrap align="right" class="report_table_column">[[|items.arrival_time|]]</td>
		<td nowrap align="right" class="report_table_column">[[|items.departure_time|]]</td>
		<td align="right" nowrap class="report_table_column"><?php echo System::display_number([[=items.debit=]]); ?></td>
        
	</tr>
    <input name="old_payment_[[|items.id|]]" type="hidden" id="old_payment_[[|items.id|]]" value="[[|items.paied|]]" />
	<!--/LIST:items-->	
	<tr bgcolor="white">
  <td align="center" valign="middle" class="report_table_column">&nbsp;</td>
	  <td colspan="8" align="right" nowrap class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
	  <td align="right" nowrap class="report_table_column" id="td_total_amount" style="font-weight:bold;"><?php echo System::display_number([[=group_function_params=]]['total_debit']); ?></td>
      
</tr>
</table>
