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
		<th rowspan="3" width="10px" align="center"  class="report_table_header" ><?php echo Portal::language('stt');?></th>
		<th rowspan="3" width="20px" align="center"  class="report_table_header" ><?php echo Portal::language('code');?></th>
        <th rowspan="3" width="10px" align="center"  class="report_table_header" ><?php echo Portal::language('invoice_preview');?></th>
		<th rowspan="3" class="report_table_header" width="400px"><?php echo Portal::language('room_info');?></th>
		<th rowspan="3" class="report_table_header" width="100px"><?php echo Portal::language('arrival_time');?> <br /> <?php echo Portal::language('departure_time');?></th>
		<th colspan="<?php echo (3+$cols+$this->map['cols']);?>" align="center"><?php echo Portal::language('revenue');?></th>
	</tr>
	<tr>
        <th colspan="3" class="report_table_header"></th>
        <th colspan="4" class="report_table_header"><?php echo Portal::language('housekeeping');?></th>
        <th colspan="5" class="report_table_header"></th>
    </tr>
	<tr>
		<th width="60px" class="report_table_header"><?php echo Portal::language('room_total');?></th>
        <th width="120px" class="report_table_header"><?php echo Portal::language('extra_service');?></th>		
		<?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key1=>&$item1){if($key1!='current'){$this->map['department']['current'] = &$item1;?>
        <?php if($this->map['department']['current']['res']==1){?><th width="60px" class="report_table_header"><?php echo Portal::language('restaurant_total');?></th><?php }?>
		<?php if($this->map['department']['current']['hk']==1){?>
        <th width="60px" class="report_table_header"><?php echo Portal::language('minibar_total');?></th>
		<th width="60px" class="report_table_header"><?php echo Portal::language('laundry_total');?></th>
		<th width="60px" class="report_table_header"><?php echo Portal::language('compensation_total');?></th>
		<th width="60px" class="report_table_header"><?php echo Portal::language('housekeeping_total');?></th>
        <?php }?>
        <?php if($this->map['department']['current']['massage']==1){?><th width="60px" class="report_table_header"><?php echo Portal::language('spa');?></th><?php }?>
		<?php if($this->map['department']['current']['karaoke']==1){?><th width="50px"class="report_table_header"><?php echo Portal::language('total_karaoke');?></th><?php }?>
        <?php if($this->map['department']['current']['ticket']==1){?><th width="50px"class="report_table_header"><?php echo Portal::language('ticket');?></th><?php }?>
        <?php if($this->map['department']['current']['vending']==1){?><th width="50px"class="report_table_header"><?php echo Portal::language('vending');?></th><?php }?>
        <?php }}unset($this->map['department']['current']);} ?>
		<?php 
				if((HAVE_TENNIS))
				{?><th width="50px"class="report_table_header"><?php echo Portal::language('total_tennis');?></th>
				<?php
				}
				?>
		<?php 
				if((HAVE_SWIMMING))
				{?><th width="50px"class="report_table_header"><?php echo Portal::language('swimming');?></th>
				<?php
				}
				?>
	  <th width="120px" class="report_table_header"><?php echo Portal::language('total');?></th>
	</tr>
    <!--<tr>
        <th width="60px" class="report_table_header"><?php echo Portal::language('service');?></th>
    </tr>-->
	<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	
	<tr>
		<td colspan="5" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
		<td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['room_total']);?></td>
        <!--<td align="right" class="report_table_column"><?php //echo System::display_number($this->map['last_group_function_params']['extra_service_total_with_room']);?></td>-->
        <td align="right" class="report_table_column"> <?php echo System::display_number($this->map['last_group_function_params']['extra_service_total_with_service']);?></td>
		<?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key2=>&$item2){if($key2!='current'){$this->map['department']['current'] = &$item2;?>
        <?php if($this->map['department']['current']['res']==1){?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['restaurant_total']);?></td><?php }?>
		<?php if($this->map['department']['current']['hk']==1){?>
        <td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['minibar_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['laundry_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['equip_total']);?></td>
		<td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['housekeeping_total']);?></td>
        <?php }?>
        <?php if($this->map['department']['current']['massage']==1){?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['massage_total']);?></td><?php }?>
		<?php if($this->map['department']['current']['karaoke']==1){?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['karaoke_total']);?></td><?php }?>
        <?php if($this->map['department']['current']['ticket']==1){?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['ticket_total']);?></td><?php }?>
        <?php if($this->map['department']['current']['vending']==1){?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['vending_total']);?></td><?php }?>
		<?php }}unset($this->map['department']['current']);} ?>
        <?php 
				if((HAVE_TENNIS))
				{?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['tennis_total']);?></td>
				<?php
				}
				?>
		<?php 
				if((HAVE_SWIMMING))
				{?><td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['swimming_total']);?></td>
				<?php
				}
				?>
		<td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['total']);?></td>
	</tr>
	
				<?php
				}
				?>	
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current'] = &$item3;?>
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
		<td align="center" valign="top"  class="report_table_column">
            <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['id']));?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a>
        </td>
        <td align="center" valign="top"  class="report_table_column">
            <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['id'],'view_report'=>1));?>" target="_blank"><input type="button" class="view-order-button" title="Xem H�"/></a>
        </td>
        <td align="left" valign="top" class="report_table_column">
        <div><strong><?php echo $this->map['items']['current']['room_name'];?></strong></div>
        <div style="font-size:11px;"><?php echo Portal::language('type_room');?>: <?php echo $this->map['items']['current']['room_level'];?></div>
        <div style="font-size:11px;"><?php echo Portal::language('customer_stay');?>: <?php echo $this->map['items']['current']['customer_stay'];?> | <?php echo Portal::language('customer_book');?>: <?php echo $this->map['items']['current']['customer_name'];?> <br/> <?php echo Portal::language('tour');?>:<?php echo $this->map['items']['current']['tour_name'];?></div>
        <?php 
				if(($this->map['items']['current']['note']))
				{?><div><?php echo Portal::language('note');?>: <?php echo $this->map['items']['current']['note'];?></div>
				<?php
				}
				?>      
        </td>
		<td align="center" valign="top" nowrap class="report_table_column"><?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['arrival_time'],'/');?><br />
	    <?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['departure_time'],'/');?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['room_total']));?></td>
        
        <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['extra_service_total_with_service']));?> </td>
		<?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key4=>&$item4){if($key4!='current'){$this->map['department']['current'] = &$item4;?>
        <?php if($this->map['department']['current']['res']==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['restaurant_total']));?></td><?php }?>
		<?php if($this->map['department']['current']['hk']==1){?>
        <td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['minibar_total']));?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['laundry_total']));?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['equip_total']));?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['housekeeping_total']));?></td>
        <?php }?>
        <?php if($this->map['department']['current']['massage']==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['massage_total']));?></td><?php }?>
		<?php if($this->map['department']['current']['karaoke']==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['karaoke_total']));?></td><?php }?>
        <?php if($this->map['department']['current']['ticket']==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['ticket_total']));?></td><?php }?>
        <?php if($this->map['department']['current']['vending']==1){?><td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['vending_total']));?></td><?php }?>
		<?php }}unset($this->map['department']['current']);} ?>
        <?php 
				if((HAVE_TENNIS))
				{?><td align="right" valign="top" nowrap class="report_table_column"><?php echo $this->map['items']['current']['tennis_total'];?></td>
				<?php
				}
				?>
		<?php 
				if((HAVE_SWIMMING))
				{?><td align="right" valign="top" nowrap class="report_table_column"><?php echo $this->map['items']['current']['swimming_total'];?></td>
				<?php
				}
				?>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number(round($this->map['items']['current']['total']));?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<!---------TOTAL GROUP FUNCTION----------->	
	<tr>
		<td colspan="5" class="report_sub_title" align="right">
		<b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['room_total']));?></b></td>
        
        <td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['extra_service_total_with_service']));?></b></td>
		<?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key5=>&$item5){if($key5!='current'){$this->map['department']['current'] = &$item5;?>
        <?php if($this->map['department']['current']['res']==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['restaurant_total']));?></b></td><?php }?>
		<?php if($this->map['department']['current']['hk']==1){?>
        <td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['minibar_total']));?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['laundry_total']));?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['equip_total']));?></b></td>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['housekeeping_total']));?></b></td>
        <?php }?>
        <?php if($this->map['department']['current']['massage']==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number($this->map['group_function_params']['massage_total']);?></b></td><?php }?>
		<?php if($this->map['department']['current']['karaoke']==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number($this->map['group_function_params']['karaoke_total']);?></b></td><?php }?>
        <?php if($this->map['department']['current']['ticket']==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number($this->map['group_function_params']['ticket_total']);?></b></td><?php }?>
        <?php if($this->map['department']['current']['vending']==1){?><td align="right" class="report_table_column"><b><?php echo System::display_number($this->map['group_function_params']['vending_total']);?></b></td><?php }?>
		<?php }}unset($this->map['department']['current']);} ?>
        <?php 
				if((HAVE_TENNIS))
				{?><td align="right" class="report_table_column"><b><?php echo System::display_number($this->map['group_function_params']['tennis_total']);?></b></td>
				<?php
				}
				?>
		<?php 
				if((HAVE_SWIMMING))
				{?><td align="right" class="report_table_column"><b><?php echo System::display_number($this->map['group_function_params']['swimming_total']);?></b></td>
				<?php
				}
				?>
		<td align="right" class="report_table_column"><b><?php echo System::display_number(round($this->map['group_function_params']['total']));?></b></td>
	</tr>				
</table>
<br/><br />
<?php 
				if(($this->map['total_page']==$this->map['page_no']))
				{?>

				<?php
				}
				?>
<br/>
<br/>
<table cellpadding="5" cellspacing="0" width="50%" border="1" bordercolor="#CCCCCC" class="table-bound" style="display:none;">
  <?php 
				if(($this->map['total_page']==$this->map['page_no']))
				{?>
		  <tr>
			<td colspan="7" class="report_sub_title" align="right"><b><?php echo Portal::language('pay_by_cash');?></b></td>
			<td colspan="13" align="right" class="report_table_column">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				  <?php $credit = 0;
				  $usd = $total;
				  ?>	
				  <?php if(isset($this->map['pay_by_currency']) and is_array($this->map['pay_by_currency'])){ foreach($this->map['pay_by_currency'] as $key6=>&$item6){if($key6!='current'){$this->map['pay_by_currency']['current'] = &$item6;?>
				  	<?php 
				if(($this->map['pay_by_currency']['current']['id']!='USD'))
				{?>
					<td><?php
						$usd -= round($this->map['pay_by_currency']['current']['amount']/$this->map['pay_by_currency']['current']['exchange'],2);
						 echo System::display_number($this->map['pay_by_currency']['current']['amount']);
						?>(<?php echo $this->map['pay_by_currency']['current']['id'];?>)</td>
					 <?php }else{ ?>
					  <?php $credit = $this->map['pay_by_currency']['current']['amount'];?>	
					
				<?php
				}
				?>
				  <?php }}unset($this->map['pay_by_currency']['current']);} ?>
				  <td><?php echo System::display_number($usd-$credit);?>(USD)</td>
				  </tr>
				</table>			
			</td>
		  </tr>
		  <tr>
		    <td colspan="7" class="report_sub_title" align="right"><b><?php echo Portal::language('pay_by_creditcard');?></b></td>
		    <td colspan="13" align="left" class="report_table_column"><?php echo $credit;?> (USD)</td>
		  </tr>
		
				<?php
				}
				?>	
</table>
</div>
</div>
