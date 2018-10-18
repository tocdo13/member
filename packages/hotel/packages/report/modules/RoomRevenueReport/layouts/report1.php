<style type="text/css">
a:visited{color:#003399}
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
.currency{ margin-right: 11px;}
-->
</style>
<?php
$cols = 0;
if(HAVE_TENNIS)
{
	$cols++;
}
if(HAVE_SWIMMING)
{
	$cols++;
}
?>
<div align="right" class="currency"><em>&#272;&#417;n v&#7883; t&iacute;nh: <?php echo HOTEL_CURRENCY;?></em>&nbsp;</div>
<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" width="98%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th rowspan="3" width="10px" align="center"  class="report_table_header" >[[.stt.]]</th>
		<th rowspan="3" width="20px" align="center"  class="report_table_header" >[[.code.]]</th>
        <th rowspan="3" width="10px" align="center"  class="report_table_header" >[[.invoice_preview.]]</th>
		<th rowspan="3" class="report_table_header" width="400px">[[.room_info.]]</th>
		<th rowspan="3" class="report_table_header" width="100px">[[.arrival_time.]] <br /> [[.departure_time.]]</th>
		<th colspan="<?php echo (3+$cols+[[=cols=]]);?>" align="center">[[.revenue.]]</th>
	</tr>
	<tr>
        <th colspan="3" class="report_table_header"></th>
        <th colspan="4" class="report_table_header">[[.housekeeping.]]</th>
        <th colspan="5" class="report_table_header"></th>
    </tr>
	<tr>
		<th width="60px" class="report_table_header">[[.room_total.]]</th>
        <th width="120px" class="report_table_header">[[.extra_service.]]</th>		
		<!--LIST:department-->
        <?php if([[=department.res=]]==1){?><th width="60px" class="report_table_header">[[.restaurant_total.]]</th><?php }?>
		<?php if([[=department.hk=]]==1){?>
        <th width="60px" class="report_table_header">[[.minibar_total.]]</th>
		<th width="60px" class="report_table_header">[[.laundry_total.]]</th>
		<th width="60px" class="report_table_header">[[.compensation_total.]]</th>
		<th width="60px" class="report_table_header">[[.housekeeping_total.]]</th>
        <?php }?>
        <?php if([[=department.massage=]]==1){?><th width="60px" class="report_table_header">[[.spa.]]</th><?php }?>
		<?php if([[=department.karaoke=]]==1){?><th width="50px"class="report_table_header">[[.total_karaoke.]]</th><?php }?>
        <?php if([[=department.ticket=]]==1){?><th width="50px"class="report_table_header">[[.ticket.]]</th><?php }?>
        <?php if([[=department.vending=]]==1){?><th width="50px"class="report_table_header">[[.vending.]]</th><?php }?>
        <!--/LIST:department-->
		<!--IF:cond(HAVE_TENNIS)--><th width="50px"class="report_table_header">[[.total_tennis.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><th width="50px"class="report_table_header">[[.swimming.]]</th><!--/IF:cond-->
	  <th width="120px" class="report_table_header">[[.total.]]</th>
	</tr>
    <!--<tr>
        <th width="60px" class="report_table_header">[[.service.]]</th>
    </tr>-->
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
	<tr>
		<td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['room_total']);?></td>
        <!--<td align="right" class="report_table_column"><?php //echo System::display_number([[=last_group_function_params=]]['extra_service_total_with_room']);?></td>-->
        <td align="right" class="report_table_column"> <?php echo System::display_number([[=last_group_function_params=]]['extra_service_total_with_service']);?></td>
		<!--LIST:department-->
        <?php if([[=department.res=]]==1){?><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['restaurant_total']);?></td><?php }?>
		<?php if([[=department.hk=]]==1){?>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['minibar_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['laundry_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['equip_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['housekeeping_total']);?></td>
        <?php }?>
        <?php if([[=department.massage=]]==1){?><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['massage_total']);?></td><?php }?>
		<?php if([[=department.karaoke=]]==1){?><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['karaoke_total']);?></td><?php }?>
        <?php if([[=department.ticket=]]==1){?><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['ticket_total']);?></td><?php }?>
        <?php if([[=department.vending=]]==1){?><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['vending_total']);?></td><?php }?>
		<!--/LIST:department-->
        <!--IF:cond(HAVE_TENNIS)--><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['tennis_total']);?></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['swimming_total']);?></td><!--/IF:cond-->
		<td align="right" class="report_table_column"><?php echo System::display_number([[=last_group_function_params=]]['total']);?></td>
	</tr>
	<!--/IF:first_page-->	
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column">[[|items.stt|]]</td>
		<td align="center" valign="top"  class="report_table_column">
            <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.id=]]));?>" target="_blank">[[|items.reservation_id|]]</a>
        </td>
        <td align="center" valign="top"  class="report_table_column">
            <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.id=]],'view_report'=>1));?>" target="_blank"><input type="button" class="view-order-button" title="Xem H�"/></a>
        </td>
        <td align="left" valign="top" class="report_table_column">
        <div><strong>[[|items.room_name|]]</strong></div>
        <div style="font-size:11px;">[[.type_room.]]: [[|items.room_level|]]</div>
        <div style="font-size:11px;">[[.customer_stay.]]: [[|items.customer_stay|]] | [[.customer_book.]]: [[|items.customer_name|]] <br/> [[.tour.]]:[[|items.tour_name|]]</div>
        <!--IF:cond([[=items.note=]])--><div>[[.note.]]: [[|items.note|]]</div><!--/IF:cond-->      
        </td>
		<td align="center" valign="top" nowrap class="report_table_column"><?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/');?><br />
	    <?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/');?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.room_total=]]));?></td>
        
        <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.extra_service_total_with_service=]]));?> </td>
		<!--LIST:department-->
        <?php if([[=department.res=]]==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.restaurant_total=]]));?></td><?php }?>
		<?php if([[=department.hk=]]==1){?>
        <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.minibar_total=]]));?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.laundry_total=]]));?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.equip_total=]]));?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.housekeeping_total=]]));?></td>
        <?php }?>
        <?php if([[=department.massage=]]==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.massage_total=]]));?></td><?php }?>
		<?php if([[=department.karaoke=]]==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.karaoke_total=]]));?></td><?php }?>
        <?php if([[=department.ticket=]]==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.ticket_total=]]));?></td><?php }?>
        <?php if([[=department.vending=]]==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.vending_total=]]));?></td><?php }?>
		<!--/LIST:department-->
        <!--IF:cond(HAVE_TENNIS)--><td align="right" valign="top" nowrap class="report_table_column">[[|items.tennis_total|]]</td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" valign="top" nowrap class="report_table_column">[[|items.swimming_total|]]</td><!--/IF:cond-->
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round([[=items.total=]]));?></td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
	<tr>
		<td colspan="5" class="report_sub_title" align="right">
		<b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['room_total']));?></b></td>
        
        <td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['extra_service_total_with_service']));?></b></td>
		<!--LIST:department-->
        <?php if([[=department.res=]]==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['restaurant_total']));?></b></td><?php }?>
		<?php if([[=department.hk=]]==1){?>
        <td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['minibar_total']));?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['laundry_total']));?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['equip_total']));?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['housekeeping_total']));?></b></td>
        <?php }?>
        <?php if([[=department.massage=]]==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['massage_total']);?></b></td><?php }?>
		<?php if([[=department.karaoke=]]==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['karaoke_total']);?></b></td><?php }?>
        <?php if([[=department.ticket=]]==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['ticket_total']);?></b></td><?php }?>
        <?php if([[=department.vending=]]==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['vending_total']);?></b></td><?php }?>
		<!--/LIST:department-->
        <!--IF:cond(HAVE_TENNIS)--><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['tennis_total']);?></b></td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" class="report_table_column"><b><?php echo System::display_number([[=group_function_params=]]['swimming_total']);?></b></td><!--/IF:cond-->
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round([[=group_function_params=]]['total']));?></b></td>
	</tr>				
</table>
<br/><br />
<!--IF:cond([[=total_page=]]==[[=page_no=]])-->
<!--/IF:cond-->
<br/>
<br/>
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
