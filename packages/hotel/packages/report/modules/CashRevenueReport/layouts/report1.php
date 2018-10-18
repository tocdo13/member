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
<div align="right"><em>&#272;&#417;n v&#7883; t&iacute;nh: <?php echo HOTEL_CURRENCY;?></em>&nbsp;</div>
<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th rowspan="2" width="1%" align="center"  class="report_table_header" >[[.stt.]]</th>
		<th rowspan="2" width="1%" align="center"  class="report_table_header" >[[.code.]]</th>
		<th rowspan="2" class="report_table_header" width="15%">[[.room_info.]]</th>
		<th rowspan="2" width="10%" class="report_table_header">[[.arrival_time.]] <br /> 
	    [[.departure_time.]]</th>
		<th  colspan="<?php echo (7+$cols);?>" align="center">[[.revenue.]]</th>
        <th rowspan="2" width="7%"  class="report_table_header">[[.total.]]</th>
        <th rowspan="2" width="7%"  class="report_table_header">[[.note.]]</th>
	</tr>
	<tr>
		<th width="7%" class="report_table_header" >[[.room_total.]]</th>		
		<th width="7%" class="report_table_header">[[.restaurant_total.]]</th>
		<th width="7%" class="report_table_header">[[.minibar_total.]]</th>
		<th width="7%" class="report_table_header">[[.laundry_total.]]</th>
		<th width="7%" class="report_table_header">[[.compensation_total.]]</th>
		<!--IF:cond(HAVE_KARAOKE)--><th width="7%"class="report_table_header">[[.total_karaoke.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><th width="7%"class="report_table_header">[[.total_massage.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><th width="7%"class="report_table_header">[[.total_tennis.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><th width="7%"class="report_table_header">[[.swimming.]]</th><!--/IF:cond-->
		<th width="7%" class="report_table_header">[[.other_service.]]</th>
	  	<th width="7%" class="report_table_header">[[.extra_service.]]</th>		
	</tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
	<tr>
		<td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['room_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['restaurant_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['minibar_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['laundry_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['equip_total']);?></td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['karaoke_total']);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['massage_total']);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['tennis_total']);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['swimming_total']);?></td><!--/IF:cond-->
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['service_other_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['extra_service_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['total']);?></td>
        <td></td>
	</tr>
	<!--/IF:first_page-->	
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column">[[|items.stt|]]</td>
		<td align="center" valign="top"  class="report_table_column">[[|items.id|]]</td>
		<td align="left" valign="top" class="report_table_column"><div><strong>[[|items.room_name|]]</strong></div>
		  <div>[[.customer_stay.]]: [[|items.customer_stay|]]</div>
          <!--IF:cond_minibar([[=items.minibar_product=]])--><div><strong>[[.Minibar.]] :</strong><br />
          	<!--LIST:items.minibar_product-->
            <div>[[|items.minibar_product.name|]] (<strong>[[|items.minibar_product.quantity|]]</strong>)</div>
          	<!--/LIST:items.minibar_product-->            
          </div><!--/IF:cond_minibar-->
          <!--IF:cond_laundry([[=items.laundry_product=]])--><div><strong>[[.Laundry.]] :</strong><br />
          	<!--LIST:items.laundry_product-->
            <div>[[|items.laundry_product.name|]] (<strong>[[|items.laundry_product.quantity|]]</strong>)</div>
          	<!--/LIST:items.laundry_product-->
          </div><!--/IF:cond_laundry-->
          </td>
		<td align="center" valign="top" nowrap class="report_table_column"><?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/');?><br />
	    <?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/');?></td>
		<td align="right" valign="top"  class="report_table_column"><?php echo System::display_number(([[=items.room_total=]]-[[=items.extra_service_total=]]));?></td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|items.restaurant_total|]]</td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|items.minibar_total|]]</td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|items.laundry_total|]]</td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|items.equip_total|]]</td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" valign="top" nowrap class="report_table_column">[[|items.karaoke_total|]]</td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" valign="top" nowrap class="report_table_column">[[|items.massage_total|]] </td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" valign="top" nowrap class="report_table_column">[[|items.tennis_total|]]</td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" valign="top" nowrap class="report_table_column">[[|items.swimming_total|]]</td><!--/IF:cond-->
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.service_other_total=]]);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.extra_service_total=]]);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.total=]]);?></td>
        <td align="right" valign="top" nowrap class="report_table_column"><!--IF:cond([[=items.note=]])--><div>[[.note.]]: [[|items.note|]]</div><!--/IF:cond--></td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
	<tr>
		<td colspan="4" class="report_sub_title" align="right">
		<b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['room_total']);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['restaurant_total']);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['minibar_total']);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['laundry_total']);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['equip_total']);?></b></td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['karaoke_total']);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['massage_total']);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['tennis_total']);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['swimming_total']);?></b></td><!--/IF:cond-->
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['service_other_total']);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['extra_service_total']);?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['total']);?></b></td>
        <td></td>
	</tr>				
</table>
<br><br>
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