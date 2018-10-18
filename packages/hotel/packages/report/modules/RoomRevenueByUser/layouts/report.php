<!---------REPORT----------->
<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th class="report-table-header" width="10px">[[.stt.]]</th>
	  <th class="report-table-header" width="150px">[[.user.]]</th>
      <th class="report-table-header" width="80px">[[.total_amount.]]</th>
      <th class="report-table-header" width="150px">[[.room.]]</th>
      <th class="report-table-header" width="120px">[[.arrival_time.]]</th>
      <th class="report-table-header" width="120px">[[.departure_time.]]</th>
	  <th class="report-table-header" width="100px">[[.total_before_tax.]]</th>
  </tr> 
    <!--LIST:items-->
	<tr bgcolor="white">
		<td align="center" class="report_table_column" rowspan="[[|items.rowspan|]]">[[|items.stt|]]</td>
        <td align="center" class="report_table_column" rowspan="[[|items.rowspan|]]">[[|items.full_name|]]</td>
        <td align="right" class="report_table_column" rowspan="[[|items.rowspan|]]">[[|items.total_amount|]]</td>
	</tr>
    
        <!--LIST:items.revenue-->
        <tr>
    		<td align="center" class="report_table_column" >
                <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.revenue.reservation_id=]],'r_r_id'=>[[=items.revenue.reservation_room_id=]]));?>" >[[|items.revenue.room_name|]]</a>
                [[.re_code.]] : <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.revenue.reservation_id=]],));?>" >[[|items.revenue.reservation_id|]]</a>
            </td>
            <td align="center" class="report_table_column">[[|items.revenue.arrival_time|]]</td>
            <td align="center" class="report_table_column">[[|items.revenue.departure_time|]]</td>
            <td align="right" class="report_table_column">[[|items.revenue.total_before_tax|]]</td>
       	</tr>
        <!--/LIST:items.revenue-->
    
   	<!--/LIST:items-->
    <tr>
    	<td colspan="2" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_before_tax']);?></strong></td>
        <td colspan="3">&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_before_tax']);?></strong></td>
    </tr>
</table>
</div>
</div>
<style type="text/css">
a:visited{color:#003399}
</style>