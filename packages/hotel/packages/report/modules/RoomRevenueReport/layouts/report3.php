<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
-->
</style>
<?php
$cols = 0;
if(HAVE_KARAOKE)
{
	$cols++;
}
if(HAVE_MASSAGE)
{
	$cols++;
}
if(HAVE_TENNIS)
{
	$cols++;
}
if(HAVE_SWIMMING)
{
	$cols++;
}
?>
<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" width="98%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:10px;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th rowspan="2" width="20px" align="center"  class="report_table_header" >[[.stt.]]</th>
		<th rowspan="2" width="20px" align="center"  class="report_table_header" >[[.code.]]</th>
		<th rowspan="2" class="report_table_header" width="250px">[[.room_info.]]</th>
		<th rowspan="2"  class="report_table_header" width="40px">[[.arrival_time.]] <br /> 
	    [[.departure_time.]]</th>
		<th colspan="<?php echo (9+$cols+1);?>" align="center" width="500px">[[.revenue.]]</th>
	</tr>
	<tr>
		<th width="70px" class="report_table_header">[[.room_total.]]</th>
        <th width="65px" class="report_table_header">[[.extra_service.]]</th>		
		<th width="65px" class="report_table_header">[[.restaurant_total.]]</th>
		<th width="65px" class="report_table_header">[[.minibar_total.]]</th>
		<th width="65px" class="report_table_header">[[.laundry_total.]]</th>
		<th width="65px" class="report_table_header">[[.compensation_total.]]</th>
		<th width="65px" class="report_table_header">[[.housekeeping_total.]]</th>
		<!--IF:cond(HAVE_KARAOKE)--><th width="65"class="report_table_header">[[.total_karaoke.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><th width="65"class="report_table_header">[[.total_massage.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><th width="65"class="report_table_header">[[.total_tennis.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><th width="40"class="report_table_header">[[.swimming.]]</th><!--/IF:cond-->
	  <th class="report_table_header">[[.total.]]</th>
	</tr>
    <!--
    <tr>
    	<th width="50px" class="report_table_header">[[.room.]]</th>
        <th width="50px" class="report_table_header">[[.service.]]</th>
    </tr>
    -->
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
	<tr>
		<td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['room_total']),1);?></td>
        <!--<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['extra_service_total_with_room']),1);?></td>-->
        <td align="right" class="report_table_column"> <?php echo System::display_number(round([[=last_group_function_params=]]['extra_service_total_with_service']),1);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['restaurant_total']),1);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['minibar_total']),1);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['laundry_total']),1);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['equip_total']),1);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['housekeeping_total']),1);?></td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['karaoke_total']),1);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['massage_total']),1);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['tennis_total']),1);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['swimming_total']),1);?></td><!--/IF:cond-->
		<td align="right" class="report_table_column"><?php echo System::display_number(round([[=last_group_function_params=]]['total']),1);?></td>
	</tr>
	<!--/IF:first_page-->	
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column">[[|items.stt|]]</td>
		<td align="center" valign="top"  class="report_table_column">
            <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.id=]]));?>" target="_blank">[[|items.id|]]</a>
        </td>
		<td align="left" valign="top" class="report_table_column" nowrap="nowrap" style="white-space: normal; text-align: left;"><div><strong>[[|items.room_name|]]</strong>
		    &nbsp;[[|items.brief_name|]]</div>
		  <div style="font-size:11px;">[[.customer_stay.]]: <strong>[[|items.customer_stay|]]</strong> | [[.customer_book.]]: <strong>[[|items.customer_name|]]</strong> - <strong>[[|items.tour_name|]]</strong></div>
          <!--IF:cond([[=items.note=]])--><div>[[.note.]]: [[|items.note|]]</div><!--/IF:cond-->      </td>
		<td align="center" valign="top" nowrap class="report_table_column"><?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/');?><br />
	    <?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/');?></td>
		<td align="right" valign="top"  class="report_table_column"><?php echo System::display_number(round([[=items.room_total=]]),1);?></td>
        <!--<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.extra_service_total_with_room=]]),1);?></td>-->
        <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.extra_service_total_with_service=]]),1);?> </td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.restaurant_total=]]),1);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.minibar_total=]]),1);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.laundry_total=]]),1);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.equip_total=]]),1);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.housekeeping_total=]]),1);?></td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.karaoke_total=]]),1);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.massage_total=]]),1);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.tennis_total=]]),1);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.swimming_total=]]),1);?></td><!--/IF:cond-->
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.total=]]),1);?></td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
	<tr>
		<td colspan="4" class="report_sub_title" align="right">
		<b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['room_total']),1);?></b></td>
        <!--<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['extra_service_total_with_room']),1);?></b></td>-->
        <td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['extra_service_total_with_service']),1);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['restaurant_total']),1);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['minibar_total']),1);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['laundry_total']),1);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['equip_total']),1);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['housekeeping_total']),1);?></b></td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['karaoke_total']),1);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['massage_total']),1);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['tennis_total']),1);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['swimming_total']),1);?></b></td><!--/IF:cond-->
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['total']),1);?></b></td>
	</tr>				
</table>
<br>
<!--IF:cond([[=total_page=]]==[[=page_no=]])-->
<!--/IF:cond-->
<br>
<table cellpadding="5" cellspacing="0" width="50%" border="1" bordercolor="#CCCCCC" class="table-bound" style="display:none;">
  <!--IF:cond([[=total_page=]]==[[=page_no=]])-->
		  <tr>
			<td colspan="7" class="report_sub_title" align="right"><b>[[.pay_by_cash.]]</b></td>
			<td colspan="13" align="right" class="report_table_column">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				  <?php $credit = 0;
				  $usd = $total;
				  ?>	
				  <!--LIST:pay_by_currency-->
				  	<!--IF:cond_credit([[=pay_by_currency.id=]]!='USD')-->
					<td><?php
						$usd -= round([[=pay_by_currency.amount=]]/[[=pay_by_currency.exchange=]],2);
						 echo System::display_number([[=pay_by_currency.amount=]]);
						?>([[|pay_by_currency.id|]])</td>
					<!--ELSE-->
					  <?php $credit = [[=pay_by_currency.amount=]];?>	
					<!--/IF:cond_credit-->
				  <!--/LIST:pay_by_currency-->
				  <td><?php echo System::display_number($usd-$credit);?>(USD)</td>
				  </tr>
				</table>			
			</td>
		  </tr>
		  <tr>
		    <td colspan="7" class="report_sub_title" align="right"><b>[[.pay_by_creditcard.]]</b></td>
		    <td colspan="13" align="left" class="report_table_column"><?php echo $credit;?> (USD)</td>
		  </tr>
		<!--/IF:cond-->	
</table>
</div>
</div>