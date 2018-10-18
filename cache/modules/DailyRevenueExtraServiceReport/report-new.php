<style>
/*full man hinh*/
.simple-layout-middle{width:100%;}
</style>
<?php
	$service = sizeof($this->map['num_services']);
    $total_revenue = $this->map['group_function_params']['extra_service_total'];
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
		<th rowspan="2" align="center" class="report_table_header" style="width: 30px;" ><?php echo Portal::language('stt');?></th>
		<th rowspan="2" align="center" class="report_table_header" style="width: 50px;" ><?php echo Portal::language('recode');?></th>
		<th rowspan="2" class="report_table_header"><?php echo Portal::language('room_info');?></th>
	    <th rowspan="2" class="report_table_header"><?php echo Portal::language('arrival_time');?><br /><?php echo Portal::language('departure_time');?></th>
        <th rowspan="2" class="report_table_header" nowrap="nowrap"><?php echo Portal::language('total');?></th>
        <th colspan="<?php echo ($service);?>" class="report_table_header"><?php echo Portal::language('extra_sevice');?></th>
		<th rowspan="2" class="report_table_header" nowrap="nowrap"><?php echo Portal::language('total');?></th>
	</tr>
    <tr>
    	<?php
            foreach($this->map['num_services'] as $row)
            {
                ?>
                <th width="50" class="report_table_header"><?php echo $row['name'];?></th>
                <?php 
            } 
        ?>
 		 
  		
    </tr>
    
	<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	
	<tr>
        <td colspan="4" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
	    <td align="right" class="report_table_column"><b class="change_num"><?php echo System::display_number($this->map['last_group_function_params']['extra_service_total']);?></b></td>
        <?php
                foreach($this->map['num_services'] as $row)
                {
                    ?>
                    <td align="right" valign="top" class="report_table_column"><b><?php $service_id = $row['id'];  if(isset($this->map['last_group_function_params'][$service_id])){echo System::display_number($this->map['last_group_function_params'][$service_id]);} else echo '';?></b></td>
                    <?php 
                } 
            ?>
        <td align="right" class="report_table_column"><b class="change_num"><?php echo System::display_number($this->map['last_group_function_params']['extra_service_total']);?></b></td>
	</tr>
	
				<?php
				}
				?>	
	
<!---------GROUP----------->
<!---------CELLS----------->
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column"><?php echo $i++; ?></td>
		<td align="center" valign="top"  class="report_table_column"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id']));?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
		<td align="left" valign="top" class="report_table_column"><div>
        	<div><strong><?php if(isset($this->map['items']['current']['room_name'])) { echo $this->map['items']['current']['room_name'];} else { echo 'Book not assign';}?></strong></div>
            <div><?php echo Portal::language('type_room');?>: <?php echo $this->map['items']['current']['room_type'];?></div>
            <div><?php echo Portal::language('customer_stay');?>: <?php echo $this->map['items']['current']['customer_stay'];?> | <?php echo Portal::language('customer_book');?>: <?php echo $this->map['items']['current']['customer_name'];?> | <?php echo Portal::language('tour');?>:<?php echo $this->map['items']['current']['tour_name'];?></div>  </div></td>
        <td align="center" valign="top" nowrap class="report_table_column">
			<?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['arrival_time'],'/');?>
            <br />
            <?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['departure_time'],'/');?>
        </td>
        <td align="right"><b class="change_num"><?php echo System::display_number($this->map['items']['current']['extra_service_total']);if($this->map['items']['current']['foc_all'] != 0){ echo '<br />('.Portal::language('foc_all').')';} else{echo '';}?></b></td>
        
        	<?php
                foreach($this->map['num_services'] as $row)
                {
                    ?>
                    <td align="right" valign="top" nowrap class="report_table_column change_num"><?php $service_id = $row['code'];  if(isset($this->map['items']['current'][$service_id])){echo System::display_number($this->map['items']['current'][$service_id]);} else echo '';?></td>
                    <?php 
                } 
            ?>
        
		<td align="right" valign="top" nowrap class="report_table_column change_num"><?php echo System::display_number($this->map['items']['current']['extra_service_total']);if($this->map['items']['current']['foc_all'] != 0){ echo '<br />('.Portal::language('foc_all').')';} else{echo '';}?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<!---------TOTAL GROUP FUNCTION----------->	
	<tr>
        <td colspan="4" class="report_sub_title" align="right"><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right"><b class="change_num"><?php echo System::display_number($total_revenue);?></b></td>
            <?php
                foreach($this->map['num_services'] as $row)
                {
                    ?>
                    <td align="right" class="report_table_column "><b class="change_num"><?php $service_id=$row['id']; if(isset($this->map['group_function_params'][$service_id]) && $this->map['group_function_params'][$service_id]!=0) { echo System::display_number($this->map['group_function_params'][$service_id]); } ?></b></td>
                    <?php 
                } 
            ?>
		<td align="right"><b class="change_num"><?php echo System::display_number($total_revenue);?></b></td>
	</tr>	
    <?php 
				if(($this->map['total_page']==$this->map['page_no']))
				{?>
    <tr style="display:none;">
		<td colspan="4" class="report_sub_title" align="right"><b><?php echo Portal::language('pay_by_cash');?></b></td>
		<td colspan="5" align="right" class="report_table_column">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <?php $credit = 0;
                    $usd = $this->map['group_function_params']['total'];
//$usd = $this->map['group_function_params']['room_total'];
                    ?>	
                    <?php if(isset($this->map['pay_by_currency']) and is_array($this->map['pay_by_currency'])){ foreach($this->map['pay_by_currency'] as $key2=>&$item2){if($key2!='current'){$this->map['pay_by_currency']['current'] = &$item2;?>
                    <?php 
				if(($this->map['pay_by_currency']['current']['id']!='USD'))
				{?>
                    <td class="change_num">
                        <?php
                    	$usd -= round($this->map['pay_by_currency']['current']['amount']/$this->map['pay_by_currency']['current']['exchange'],2);
                    	 echo System::display_number($this->map['pay_by_currency']['current']['amount']);
                    	?>
                        (<?php echo $this->map['pay_by_currency']['current']['id'];?>)
                    </td>
                     <?php }else{ ?>
                        <?php $credit = $this->map['pay_by_currency']['current']['amount'];?>	
                    
				<?php
				}
				?>
                    <?php }}unset($this->map['pay_by_currency']['current']);} ?>
                    <td class="change_num"><?php echo System::display_number($usd-$credit);?>(USD)</td>
                </tr>
            </table>			
        </td>
    </tr>
    <tr style="display:none;">
        <td colspan="4" class="report_sub_title" align="right"><b><?php echo Portal::language('pay_by_creditcard');?></b></td>
        <td colspan="5" align="left" class="report_table_column"><?php echo $credit;?> (USD)</td>
    </tr>
    
				<?php
				}
				?>	
</table>
</td></tr></table>
<?php
    }
    else
    {
?>
    <div style="padding:20px;">
	<h3><?php echo Portal::language('no_result_matchs');?></h3>
	<a href="<?php echo Url::build_current(array('type'));?>"><?php echo Portal::language('back');?></a>
    </div>
<?php        
    }
?>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			