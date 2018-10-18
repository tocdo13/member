<style>
/*full man hinh*/
.simple-layout-middle{width:100%;}
</style>
<?php
	$service = sizeof([[=num_services=]]);
    $total_revenue = [[=group_function_params=]]['extra_service_total'];
?>
<form name="DailyRevenueReportForm" method="post" >
<?php 
    if($total_revenue!=0)
    {
?>
<?php $i=1;?>
<div align="right"><em>&#272;&#417;n v&#7883; t&iacute;nh: <?php echo HOTEL_CURRENCY;?></em>&nbsp;</div>
<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" >
	<tr valign="middle" bgcolor="#EFEFEF">
		<th rowspan="2" align="center" class="report_table_header" style="width: 30px;" >[[.stt.]]</th>
		<th rowspan="2" align="center" class="report_table_header" style="width: 50px;" >[[.recode.]]</th>
		<th rowspan="2" class="report_table_header">[[.room_info.]]</th>
	    <th rowspan="2" class="report_table_header">[[.arrival_time.]]<br />[[.departure_time.]]</th>
        <th rowspan="2" class="report_table_header" nowrap="nowrap">[[.total.]]</th>
        <th colspan="<?php echo ($service);?>" class="report_table_header">[[.extra_sevice.]]</th>
		<th rowspan="2" class="report_table_header" nowrap="nowrap">[[.total.]]</th>
	</tr>
    <tr>
    	<?php
            foreach([[=num_services=]] as $row)
            {
                ?>
                <th width="50" class="report_table_header"><?php echo $row['name'];?></th>
                <?php 
            } 
        ?>
 		 
  		
    </tr>
    
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
	<tr>
        <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
	    <td align="right" class="report_table_column"><b class="change_num"><?php echo System::display_number([[=last_group_function_params=]]['extra_service_total']);?></b></td>
        <?php
                foreach([[=num_services=]] as $row)
                {
                    ?>
                    <td align="right" valign="top" class="report_table_column"><b><?php $service_id = $row['id'];  if(isset([[=last_group_function_params=]][$service_id])){echo System::display_number([[=last_group_function_params=]][$service_id]);} else echo '';?></b></td>
                    <?php 
                } 
            ?>
        <td align="right" class="report_table_column"><b class="change_num"><?php echo System::display_number([[=last_group_function_params=]]['extra_service_total']);?></b></td>
	</tr>
	<!--/IF:first_page-->	
	
<!---------GROUP----------->
<!---------CELLS----------->
<!--LIST:items-->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column"><?php echo $i++; ?></td>
		<td align="center" valign="top"  class="report_table_column"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]</a></td>
		<td align="left" valign="top" class="report_table_column"><div>
        	<div><strong><?php if(isset([[=items.room_name=]])) { echo [[=items.room_name=]];} else { echo 'Book not assign';}?></strong></div>
            <div>[[.type_room.]]: [[|items.room_type|]]</div>
            <div>[[.customer_stay.]]: [[|items.customer_stay|]] | [[.customer_book.]]: [[|items.customer_name|]] | [[.tour.]]:[[|items.tour_name|]]</div>  </div></td>
        <td align="center" valign="top" nowrap class="report_table_column">
			<?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/');?>
            <br />
            <?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/');?>
        </td>
        <td align="right"><b class="change_num"><?php echo System::display_number([[=items.extra_service_total=]]);if([[=items.foc_all=]] != 0){ echo '<br />('.Portal::language('foc_all').')';} else{echo '';}?></b></td>
        
        	<?php
                foreach([[=num_services=]] as $row)
                {
                    ?>
                    <td align="right" valign="top" nowrap class="report_table_column change_num"><?php $service_id = $row['code'];  if(isset($this->map['items']['current'][$service_id])){echo System::display_number($this->map['items']['current'][$service_id]);} else echo '';?></td>
                    <?php 
                } 
            ?>
        
		<td align="right" valign="top" nowrap class="report_table_column change_num"><?php echo System::display_number([[=items.extra_service_total=]]);if([[=items.foc_all=]] != 0){ echo '<br />('.Portal::language('foc_all').')';} else{echo '';}?></td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
	<tr>
        <td colspan="4" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right"><b class="change_num"><?php echo System::display_number($total_revenue);?></b></td>
            <?php
                foreach([[=num_services=]] as $row)
                {
                    ?>
                    <td align="right" class="report_table_column "><b class="change_num"><?php $service_id=$row['id']; if(isset([[=group_function_params=]][$service_id]) && [[=group_function_params=]][$service_id]!=0) { echo System::display_number([[=group_function_params=]][$service_id]); } ?></b></td>
                    <?php 
                } 
            ?>
		<td align="right"><b class="change_num"><?php echo System::display_number($total_revenue);?></b></td>
	</tr>	
    <!--IF:cond([[=total_page=]]==[[=page_no=]])-->
    <tr style="display:none;">
		<td colspan="4" class="report_sub_title" align="right"><b>[[.pay_by_cash.]]</b></td>
		<td colspan="5" align="right" class="report_table_column">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <?php $credit = 0;
                    $usd = [[=group_function_params=]]['total'];
//$usd = [[=group_function_params=]]['room_total'];
                    ?>	
                    <!--LIST:pay_by_currency-->
                    <!--IF:cond_credit([[=pay_by_currency.id=]]!='USD')-->
                    <td class="change_num">
                        <?php
                    	$usd -= round([[=pay_by_currency.amount=]]/[[=pay_by_currency.exchange=]],2);
                    	 echo System::display_number([[=pay_by_currency.amount=]]);
                    	?>
                        ([[|pay_by_currency.id|]])
                    </td>
                    <!--ELSE-->
                        <?php $credit = [[=pay_by_currency.amount=]];?>	
                    <!--/IF:cond_credit-->
                    <!--/LIST:pay_by_currency-->
                    <td class="change_num"><?php echo System::display_number($usd-$credit);?>(USD)</td>
                </tr>
            </table>			
        </td>
    </tr>
    <tr style="display:none;">
        <td colspan="4" class="report_sub_title" align="right"><b>[[.pay_by_creditcard.]]</b></td>
        <td colspan="5" align="left" class="report_table_column"><?php echo $credit;?> (USD)</td>
    </tr>
    <!--/IF:cond-->	
</table>
</td></tr></table>
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