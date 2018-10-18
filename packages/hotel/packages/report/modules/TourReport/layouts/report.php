<!---------REPORT----------->	

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="10px" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
		<th width="15px" align="center" nowrap="nowrap" class="report_table_header">[[.invoice_number.]]</th>
		<th width="150px" class="report_table_header" nowrap="nowrap" >[[.customer_name.]]</th>
		<th width="30px" nowrap="nowrap" class="report_table_header">[[.room.]]</th>
		<th width="50px" nowrap="nowrap" class="report_table_header">[[.time_in.]]</th>
		<th width="50px" nowrap="nowrap" class="report_table_header">[[.time_out.]]</th>
		<!---<th width="100px" nowrap="nowrap" class="report_table_header">[[.contact_person_name.]]</th>
		<th width="70px" nowrap="nowrap" class="report_table_header">[[.contact_person_phone.]]</th>--->
		 <th width="50px" nowrap="nowrap" class="report_table_header">[[.status.]]</th>
		<th width="10px" nowrap="nowrap" class="report_table_header">[[.confirm.]]</th>
	    <th width="350px" nowrap="nowrap" class="report_table_header">[[.note.]]</th>
	</tr>
    <?php $temp = '';?>    
    <!--LIST:items-->
    <?php if($temp!=[[=items.reservation_id=]]){$temp = [[=items.reservation_id=]];?>
    <tr>
        <td colspan="11" >[[[.rcode.]]:  <b><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]</a>]</b> | <span>[[[.booking_code.]]: <b style="color:#FF0000;">[[|items.booking_code|]]</b>]</span> | [[.tour.]]: <b>[[|items.tour_name|]]</b> | [[.customer.]]: <b style="color:#FF0000;">[[|items.customer_name|]]</b></td>
    </tr>
    <?php }?>
	<tr bgcolor="white">
		<td align="center" valign="middle">[[|items.stt|]]</td>
		<td align="center" valign="middle">[[|items.bill_number|]]</td>
		<td nowrap align="left">
			[[|items.first_name|]]
		[[|items.last_name|]]</td>
		<td  align="left" nowrap="nowrap">[[|items.room_name|]] - [[|items.room_level_name|]]</td>
	  <td align="center" nowrap="nowrap"><?php echo date('d/m h\h:i',[[=items.time_in=]]);?></td>
		<td align="center" nowrap="nowrap"><?php echo date('d/m h\h:i',[[=items.time_out=]]);?></td>
		<!---<td align="left">[[|items.contact_person_name|]] </td>
		<td align="left">[[|items.contact_person_phone|]] </td>--->
		<td align="center" nowrap="nowrap">[[|items.status|]]</td>
		<td align="center" nowrap="nowrap">[[|items.confirm|]]</td>		
		<td align="left" class="note">[[|items.note|]]</td>
	</tr>
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
	<!--/IF:first_page-->
</table>
