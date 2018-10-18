<div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></div>
<?php $total_amount = 0;$ta=0; ?>
<?php 
				if(($this->map['page_no']==1))
				{?><br /><br />
<label style="margin-left: 15px;"><?php echo Portal::language('total_to_reception');?></label>:<span class="change_numTr"><?php echo System::display_number($this->map['amount_with_room']) ?>VND  </span>   

				<?php
				}
				?>
<table border=1 cellspacing=0 cellpadding=5 bordercolor=#cccccc style="width: 98%; text-align: center; margin: 5px auto;" id="export">
    <tr>
        <th><?php echo Portal::language('name');?></th>
        <th><?php echo Portal::language('unit');?></th>
        <th><?php echo Portal::language('price');?></th>
        <th><?php echo Portal::language('quantity');?></th>
        <th><?php echo Portal::language('discount_product');?>(%)</th>
        <th><?php echo Portal::language('discount_invoice');?>(%)</th>
        <th><?php echo Portal::language('deposit');?></th>
        <th><?php echo Portal::language('total');?></th>
    </tr>
    <?php 
				if(($this->map['page_no']!=1))
				{?>
    <!---------LAST GROUP VALUE----------->	        
    <tr style="font-weight: bold;">
        <td colspan="3" align="right" nowrap class="report_table_column"><strong><?php echo Portal::language('last_page_summary');?></strong></td>
        <td style="text-align: center;"><?php echo $this->map['last_group_function_params']['quantity']; ?></td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;"  class="change_numTr"><?php echo System::display_number($this->map['last_group_function_params']['total']);?></td>
    </tr>
    
				<?php
				}
				?>
    <?php  $b_r =''; $i = 0;?> 
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <?php 
    if($b_r != $this->map['items']['current']['name'])
    {
        $i =0;
    }   
    if($b_r == $this->map['items']['current']['name'])
    {
        $i++;
    }
    ?>
    <?php }}unset($this->map['items']['current']);} ?>
    <?php $bar_order = '';?>	
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
        <?php if($bar_order != $this->map['items']['current']['name']){ $bar_order=$this->map['items']['current']['name'];?> 
            <tr style="background: #f5f5f5; font-weight: bold; font-size: 16px;">
            <td colspan="7" style="text-align: left; margin-left: 20px; font-weight: bold;">
                <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['reservation_room_id']));?>"><?php echo Portal::language('room');?> <?php echo $this->map['items']['current']['room_name'];?> <?php if(!empty($this->map['items']['current']['booking_code'])){ ?>, <?php echo Portal::language('Booking_code');?> : <?php echo $this->map['items']['current']['booking_code'];?> <?php } ?></a> <a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>$this->map['items']['current']['order_id'],'bar_id'=>$this->map['items']['current']['bar_id'])); ?>"> - <?php echo Portal::language('bar_reservation');?>: <?php echo $this->map['items']['current']['name'];?></a>
                <span class="change_numTr"><?php if($this->map['items']['current']['pay_with_room']==1 and $this->map['items']['current']['amount_pay_with_room']!=0) echo '('.Portal::language('pay_with_room').' '.System::display_number($this->map['items']['current']['amount_pay_with_room']).')' ?></span>
            </td>
            <?php if(isset($this->map['total_items']) and is_array($this->map['total_items'])){ foreach($this->map['total_items'] as $key3=>&$item3){if($key3!='current'){$this->map['total_items']['current'] = &$item3;?>
            
             <?php if($this->map['items']['current']['name'] == $this->map['total_items']['current']['id']){?> 
                <?php $key_id = $this->map['items']['current']['id']; ?>
                <td style="text-align: right; font-weight: bold; " class="change_numTr"><?php
				$ta= $ta+($this->map['total_items']['current']['total']-$this->map['total_items']['current']['deposit']);
				echo System::display_number($this->map['total_items']['current']['total']-$this->map['total_items']['current']['deposit']);
				
				?></td>   
             <?php } ?>
            <?php }}unset($this->map['total_items']['current']);} ?>
        	</tr>
    	<?php }?>
            <?php if($key_id==$this->map['items']['current']['id']){ ?>
            <tr>
                <td style="text-align: left;"><?php echo $this->map['items']['current']['product_name'];?></td>
                <td><?php echo $this->map['items']['current']['name_1'];?></td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
                <td><?php echo $this->map['items']['current']['quantity'];?></td>
                <td><?php echo $this->map['items']['current']['discount_rate'];?>(%)</td>
                <td><?php echo $this->map['items']['current']['order_discount_percent'];?>(%)</td>
                <td rowspan="<?php echo $this->map['total_items'][$this->map['items']['current']['name']]['child']; ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['deposit']); ?></td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['product_total']); ?></td>
            </tr>
            <?php }else{ ?>
            <tr>
                <td style="text-align: left;"><?php echo $this->map['items']['current']['product_name'];?></td>
                <td><?php echo $this->map['items']['current']['name_1'];?></td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
                <td><?php echo $this->map['items']['current']['quantity'];?></td>
                <td><?php echo $this->map['items']['current']['discount_rate'];?>(%)</td>
                <td><?php echo $this->map['items']['current']['order_discount_percent'];?>(%)</td>
                
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['product_total']); ?></td>
            </tr>
            <?php } ?>
        
    <?php }}unset($this->map['items']['current']);} ?>
    <tr style="font-weight: bold;">
        <td colspan="3" align="right" nowrap class="report_table_column"><strong><?php if($this->map['real_page_no']==$this->map['real_total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
        <td style="text-align: center;"><?php echo $this->map['group_function_params']['quantity']; ?></td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($ta);
//System::display_number($this->map['group_function_params']['total']);
?></td>
    </tr>
</table>
<script>
jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>