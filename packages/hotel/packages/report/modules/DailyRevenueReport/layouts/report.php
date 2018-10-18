<style>
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>
<?php
	$service = sizeof([[=num_services=]]);
    //System::debug($this->map);
    $total_revenue = [[=group_function_params=]]['room_total']+[[=group_function_params=]]['service_other_total']+[[=group_function_params=]]['extra_service_total'] +[[=group_function_params=]]['minibar_total']+[[=group_function_params=]]['laundry_total']+[[=group_function_params=]]['equip_total']+[[=group_function_params=]]['restaurant_total'];
?>
<form name="DailyRevenueReportForm" method="post" >
<?php 
    if($total_revenue!=0)
    {
?>
<div align="right"><em>&#272;&#417;n v&#7883; t&iacute;nh: <?php echo HOTEL_CURRENCY;?></em>&nbsp;</div>
<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" style="font-size:11px; border-collapse:collapse;">
  <tr valign="middle" bgcolor="#EFEFEF">
    <th rowspan="1" width="10px" align="center"  class="report_table_header" >[[.stt.]]</th>
    <th rowspan="1" width="15px" align="center"  class="report_table_header" >[[.code.]]</th>
    <th rowspan="1" width="300px" class="report_table_header">[[.room_info.]]</th>
    <th rowspan="1" class="report_table_header" width="50px">[[.arrival_time.]]<br />
      [[.departure_time.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="70px">[[.room_total.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="50px">[[.minibar.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="50px">[[.laundry.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="50px">[[.compensation.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="50px">[[.restaurant.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="50px">[[.massage.]]</th>
    <th rowspan="1" nowrap="nowrap"  class="report_table_header" width="50px">[[.extra_sevice.]]</th>
    <th rowspan="1" class="report_table_header" nowrap="nowrap" width="80px">[[.total.]]</th>
  </tr>
  <!--IF:first_page([[=page_no=]]!=1)-->
  <!---------LAST GROUP VALUE----------->
  <tr>
    <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['room_total']);?></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['minibar_total']);?></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['laundry_total']);?></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['equip_total']);?></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['restaurant_total']);?></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['massage_total']);?></td>    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['extra_service_total']);?></td>
    <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['room_total']+[[=last_group_function_params=]]['service_other_total']+[[=last_group_function_params=]]['extra_service_total']);?></td>

  </tr>
  <!--/IF:first_page-->
  <!--LIST:items-->
  <!---------GROUP----------->
  <!---------CELLS----------->
  <!--IF:cond(([[=items.room_total=]]+[[=items.service_other_total=]]+[[=items.extra_service_total=]]+[[=items.minibar_total=]]+[[=items.laundry_total=]]+[[=items.equip_total=]]+[[=items.restaurant_total=]])>0)-->
  <tr bgcolor="white">
    <td align="center" valign="top"  class="report_table_column">[[|items.stt|]]</td>
    <td align="center" valign="top"  class="report_table_column"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.id|]]</a></td>
    <td align="left" valign="top" class="report_table_column">
      <div><strong>[[|items.room_name|]]</strong>[[|items.room_level|]]</div>
      <div>[[.customer_stay.]]: <strong>[[|items.customer_stay|]]</strong> | [[.customer_book.]]: <strong>[[|items.customer_name|]]</strong> | [[|items.tour_name|]]<br /><i>[[|items.note|]]</i></div>
    </td>
    <td align="center" valign="top" nowrap class="report_table_column"><?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/');?> <br />
        <?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/');?> </td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.room_total=]]); if([[=items.foc=]] != '' && [[=items.foc_all=]] == 0 ){ echo '<br />('.Portal::language('foc').')';} else{ echo '';}?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.minibar_total=]]); if( [[=items.foc_all=]] != 0 ){ echo '<br />('.Portal::language('foc_all').')';} else{ echo '';}?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.laundry_total=]]);  if( [[=items.foc_all=]] != 0 ){ echo '<br />('.Portal::language('foc_all').')';} else{ echo '';}?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.equip_total=]]);  if( [[=items.foc_all=]] != 0 ){ echo '<br />('.Portal::language('foc_all').')';} else{ echo '';}?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.restaurant_total=]]);  if( [[=items.foc_all=]] != 0 ){ echo '<br />('.Portal::language('foc_all').')';} else{ echo '';}?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.massage_total=]]);  if( [[=items.foc_all=]] != 0 ){ echo '<br />('.Portal::language('foc_all').')';} else{ echo '';}?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.extra_service_total=]]);?></td>
    <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=items.room_total=]]+[[=items.service_other_total=]]+[[=items.extra_service_total=]]+[[=items.minibar_total=]]+[[=items.laundry_total=]]+[[=items.equip_total=]]+[[=items.restaurant_total=]]);if([[=items.foc_all=]] != 0){ echo '<br />('.Portal::language('foc_all').')';} else{echo '';}?></td>
  </tr>
  <!--/IF:cond-->
  <!--/LIST:items-->
  <!---------TOTAL GROUP FUNCTION----------->
  <tr>
    <td colspan="4" class="report_sub_title" align="right"><b>
      <?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?>
    </b></td>
    <td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['room_total']);?></b></td>
    <td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['minibar_total']);?></b></td>
    <td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['laundry_total']);?></b></td>
    <td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['equip_total']);?></b></td>
    <td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['restaurant_total']);?></b></td>
    <td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['massage_total']);?></b></td>
    <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['extra_service_total']);?></strong></td>
    <td align="right"><b><?php echo System::display_number($total_revenue);?></b></td>

  </tr>
  <!--IF:cond([[=total_page=]]==[[=page_no=]])-->
  <tr style="display:none;">
    <td colspan="4" class="report_sub_title" align="right"><b>[[.pay_by_cash.]]</b></td>
    <td colspan="5" align="right" class="report_table_column"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <?php $credit = 0;
                    $usd = [[=group_function_params=]]['room_total'];
                    ?>
        <!--LIST:pay_by_currency-->
        <!--IF:cond_credit([[=pay_by_currency.id=]]!='USD')-->
        <td><?php
                    	$usd -= round([[=pay_by_currency.amount=]]/[[=pay_by_currency.exchange=]],2);
                    	 echo System::display_number([[=pay_by_currency.amount=]]);
                    	?>
          ([[|pay_by_currency.id|]]) </td>
        <!--ELSE-->
        <?php $credit = [[=pay_by_currency.amount=]];?>
        <!--/IF:cond_credit-->
        <!--/LIST:pay_by_currency-->
        <td><?php echo System::display_number($usd-$credit);?>(USD)</td>
      </tr>
    </table></td>
  </tr>
  <tr style="display:none;">
    <td colspan="4" class="report_sub_title" align="right"><b>[[.pay_by_creditcard.]]</b></td>
    <td colspan="5" align="left" class="report_table_column"><?php echo $credit;?> (USD)</td>
  </tr>
  <!--/IF:cond-->
</table>
<?php
    }
    else
    {
?>
<div style="padding:20px;">
	<h3>[[.no_result_matchs.]]</h3>
	<a href="<?php echo Url::build_current(array('type'));?>">[[.back.]]</a>
</div>
<?php        
    }
?>
</form>