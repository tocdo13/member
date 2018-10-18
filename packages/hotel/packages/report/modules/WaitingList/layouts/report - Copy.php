<!---------REPORT----------->	

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="5" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
		<th width="1%" align="center" nowrap="nowrap" class="report_table_header">[[.invoice_number.]]</th>
		<th class="report_table_header" nowrap="nowrap" >[[.customer_name.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.room.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.time_in.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.time_out.]]</th>
		<th width="120" nowrap="nowrap" class="report_table_header">[[.contact_person_name.]]</th>
		<th width="120" nowrap="nowrap" class="report_table_header">[[.contact_person_phone.]]</th>
		 <th nowrap="nowrap" class="report_table_header">[[.status.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.confirm.]]</th>
	    <th width="120" nowrap="nowrap" class="report_table_header">[[.note.]]</th>
	</tr>
    <?php $temp = '';?>    
    <!--LIST:items-->
    <?php if($temp!=[[=items.reservation_id=]]){$temp = [[=items.reservation_id=]];?>
    <tr>
        <td colspan="11">[[[.rcode.]]:  <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]]</a> | <span style="color:#0066FF;">[[[.booking_code.]]: [[|items.booking_code|]]]</span> | [[.tour.]]: [[|items.tour_name|]] | [[.customer.]]: [[|items.customer_name|]]</td>
    </tr>
    <?php }?>
	<tr bgcolor="white">
		<td width="5" align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
		<td width="1%" align="center" valign="middle" class="report_table_column">[[|items.bill_number|]]</td>
		<td nowrap align="left" class="report_table_column" width="150">
			[[|items.first_name|]]
		[[|items.last_name|]]</td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column">[[|items.room_name|]]</td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column"><?php echo date('d/m/Y h\h:i',[[=items.time_in=]]);?></td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column"><?php echo date('d/m/Y h\h:i',[[=items.time_out=]]);?></td>
		<td align="left" class="report_table_column">[[|items.contact_person_name|]] </td>
		<td align="left" class="report_table_column">[[|items.contact_person_phone|]] </td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column">[[|items.status|]]</td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column">[[|items.confirm|]]</td>		
		<td align="left" class="report_table_column">[[|items.note|]]</td>
	</tr>
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
	<!--/IF:first_page-->
</table>
