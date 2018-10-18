<!---------REPORT----------->	
<p style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></p>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;" >
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="10px" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
		<th width="20px" align="center" nowrap="nowrap" class="report_table_header">[[.invoice_number.]]</th>
		<!---<th class="report_table_header" nowrap="nowrap" >[[.customer_name.]]</th>--->
		<th width="80px;" nowrap="nowrap" class="report_table_header">[[.room_level.]]</th>
		<th width="70px" nowrap="nowrap" class="report_table_header">[[.arrival_date.]]</th>
		<th width="70px" nowrap="nowrap" class="report_table_header">[[.departure_date.]]</th>
		<!---<th width="100px" nowrap="nowrap" class="report_table_header">[[.contact.]]</th>--->
		<th width="70px" nowrap="nowrap" class="report_table_header">[[.status.]]</th>
        <th width="50px" nowrap="nowrap" class="report_table_header">[[.deposit.]]</th>
		<th width="20px" nowrap="nowrap" class="report_table_header">[[.confirm.]]</th>
	    <th width="300px" nowrap="nowrap" class="report_table_header">[[.note.]]</th>
	</tr>
    <?php $temp = '';?>    
    <!--LIST:items-->
    <?php if($temp!=[[=items.reservation_id=]]){$temp = [[=items.reservation_id=]];?>
    <tr>
        <td colspan="11">[[[.rcode.]]:  <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]]</a>  | <span style="color:#FF0000; font-size:14px"><b>[[|items.customer_name|]]</b></span> | <span style="color:#0066FF;"> [[|items.booking_code|]]]</span></td>
    </tr>
    <?php }?>
	<tr bgcolor="white">
		<td align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
		<td align="center" valign="middle" class="report_table_column">[[|items.bill_number|]]</td>
		<!---<td nowrap align="left" class="report_table_column" width="150">[[|items.customer_name|]]</td>--->
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.room_level|]]</td>
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo date('H\h:i',[[=items.time_in=]]);?><br /><?php echo date('d/m/Y',[[=items.time_in=]]);?></td>
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo date('H\h:i',[[=items.time_out=]]);?><br /><?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
		<!---<td align="left" class="report_table_column">
        <!--LIST:items.contacts-->
        [[|items.contacts.contact_name|]] -  [[|items.contacts.contact_phone|]]<br />
        <!--/LIST:items.contacts--> </td>--->
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.status|]]</td>
        <td align="center" nowrap="nowrap" class="report_table_column">[[|items.deposit|]]</td>
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.confirm|]]</td>		
		<td align="left" class="report_table_column">[[|items.note|]]</td>
	</tr>
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
	<!--/IF:first_page-->
</table>
</table>
<script>
    jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })
</script>