<!---------REPORT----------->	
<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" style="font-size:10px; border-collapse:collapse;">
	<tr valign="middle">
	  <th width="15px" align="center"  class="report-table-header" >[[.stt.]]</th>
	  <th width="25px" align="center"  class="report-table-header" >[[.recode.]]</th>
	  <th width="70px" class="report-table-header">[[.room_number.]]</th>
      <th width="70px" class="report-table-header">[[.room_type.]]</th>
      <th width="200px" class="report-table-header">[[.guest_name.]]</th>
      <th width="200px" class="report-table-header">[[.source.]]</th>
	  <th width="70px" class="report-table-header">[[.arrival_time.]]<br />[[.departure_time.]]</th>
	  <th width="100px" nowrap="nowrap"  class="report-table-header">[[.room_total.]]</th>
	  <th width="100px" nowrap="nowrap"  class="report-table-header">[[.extra_charge_room.]]</th>
	  <th width="100px" nowrap="nowrap"  class="report-table-header">[[.total.]]</th>
	  <th width="100px" class="report-table-header">[[.note.]]</th>
  </tr>
<!--
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="70px" class="report-table-header" nowrap="nowrap">[[.room.]]</th>
		<th width="70px" class="report-table-header" nowrap="nowrap">[[.service.]]</th>
	</tr>
-->	
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="right"><b><?php echo System::display_number(round([[=last_group_function_params=]]['room_total']),1);?></b></td>
		    <td align="right"><b><?php echo System::display_number(round([[=last_group_function_params=]]['extra_service_total_with_room']),1);?></b></td>
		    <!---<td align="right"><b><?php echo System::display_number(round([[=last_group_function_params=]]['extra_service_total_with_service']),1);?></b></td>--->
		    <td align="right"><b><?php echo System::display_number(round([[=last_group_function_params=]]['room_total']+[[=last_group_function_params=]]['extra_service_total_with_room']),1);?></b></td>
            <td></td>
		</tr>
	<!--/IF:first_page-->	
	
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column">
			[[|items.stt|]]		</td>
		<td align="center" valign="top"  class="report_table_column"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]</a></td>
		<td align="center" valign="top" class="report_table_column">
            <div style="font-size:12px;"><strong>[[|items.room_name|]]</strong></div></td>
            <td align="center" valign="top" class="report_table_column">
            <div style="font-size:12px;">[[|items.brief_name|]]</div></td>
            <td align="left" valign="top" class="report_table_column">
            <div style="font-size:12px;"><strong>[[|items.customer_stay|]]</strong></div></td>
            <td align="left" valign="top" class="report_table_column">
            <div style="font-size:12px;">[[|items.customer_name|]]</div></td>
	  <td align="center" valign="top" nowrap class="report_table_column">
        <?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/');?><br /><?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/');?></td>
	  <td align="right" valign="top" nowrap class="report_table_column"> <?php echo System::display_number(round([[=items.room_total=]]),1);?></td>
		<td align="right" valign="top" nowrap="nowrap" class="report_table_column"><?php echo System::display_number(round([[=items.extra_service_total_with_room=]]),1);?></td>
		<!---<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.extra_service_total_with_service=]]),1);?></td>--->
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.room_total=]]+[[=items.room_service_total=]]+[[=items.service_other_total=]]+[[=items.extra_service_total_with_room=]]),1);?></td><!-- +[[=items.extra_service_total_with_room=]] -->
		<td align="left" class="report_table_column">
			[[|items.note|]]		</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr style="font-size:9px;"><td colspan="7" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
			<td align="right"><b><?php echo System::display_number(round([[=group_function_params=]]['room_total']),1);?></b></td>
			<td align="right"><b><?php echo System::display_number(round([[=group_function_params=]]['extra_service_total_with_room']),1);?></b></td>
			<!---<td align="right"><b><?php echo System::display_number(round([[=group_function_params=]]['extra_service_total_with_service']),1);?></b></td>--->
			<td align="right"><b><?php echo System::display_number(round([[=group_function_params=]]['room_total']+[[=group_function_params=]]['extra_service_total_with_room']),1);?></b></td><!--+[[=group_function_params=]]['service_other_total']+[[=group_function_params=]]['extra_service_total_with_service']-->
			<td colspan="1">&nbsp;</td>
		</tr>	
		<!--IF:cond([[=total_page=]]==[[=page_no=]])-->
		  <tr style="display:none;">
			<td colspan="4" class="report_sub_title" align="right"><b>[[.pay_by_cash.]]</b></td>
			<td colspan="5" align="right" class="report_table_column">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				  <?php $credit = 0;
				  $usd = [[=group_function_params=]]['room_total'];
				  ?>	
				  <!--LIST:pay_by_currency-->
				  	<!--IF:cond_credit([[=pay_by_currency.id=]]!='USD')-->
					<td><?php
						$usd -= round([[=pay_by_currency.amount=]]/[[=pay_by_currency.exchange=]],2);
						 echo System::display_number(round([[=pay_by_currency.amount=]]),1);
						?>([[|pay_by_currency.id|]])</td>
					<!--ELSE-->
					  <?php $credit = [[=pay_by_currency.amount=]];?>	
					<!--/IF:cond_credit-->
				  <!--/LIST:pay_by_currency-->
				  <td><?php echo System::display_number(round($usd-$credit),1);?>(USD)</td>
				  </tr>
				</table>			</td>
		  </tr>
		  <tr style="display:none;">
		    <td colspan="4" class="report_sub_title" align="right"><b>[[.pay_by_creditcard.]]</b></td>
		    <td colspan="5" align="left" class="report_table_column"><?php echo $credit;?> (USD)</td>
		  </tr>
		<!--/IF:cond-->	
</table>
</div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    });
</script>