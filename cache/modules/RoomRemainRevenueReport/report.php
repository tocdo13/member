<!--REPORT-->
    <!---TITLE--->
    <tr>
        <td>
        <table cellpadding="5" cellspacing="0" width="100%" border="1"  bordercolor="#CCCCCC" style="background-color: #FFFFFF;" class="table-bound">
       	    <tr valign="middle" bgcolor="#EFEFEF">
                <th width="10px" class="report-table-header"><?php echo Portal::language('stt');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('recode');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('customer_name');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('room');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('guest_name');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('arrival_time');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('departure_time');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('number_room_night');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('price');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('price_usd');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('room_price_total');?></th>
                <?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key1=>&$item1){if($key1!='current'){$this->map['department']['current'] = &$item1;?>
                <?php if($this->map['department']['current']['res']==1){?>
                <th width="10px" class="report-table-header"><?php echo Portal::language('rest');?></th>
                <?php }?>
                <!---<?php if($this->map['department']['current']['karaoke']==1){?>
                <th width="10px" class="report-table-header"><?php echo Portal::language('karaoke');?></th>
                <?php }?>--->
                <?php if($this->map['department']['current']['hk']==1){?>
                <th width="50px" class="report-table-header"><?php echo Portal::language('minibar');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('laundry');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('equipment');?></th>
                <?php }?>
                <th width="50px" class="report-table-header"><?php echo Portal::language('telephone');?></th>
                <?php if($this->map['department']['current']['massage']==1){?>                
                <th width="50px" class="report-table-header"><?php echo Portal::language('spa');?></th> 
                <?php }?>               
                <th width="50px" class="report-table-header">Tour</th>
                <!---<?php if($this->map['department']['current']['vending']==1){?>  
                <th width="50px" class="report-table-header"><?php echo Portal::language('vending');?></th>
                <?php }?>
                <?php if($this->map['department']['current']['ticket']==1){?>  
                <th width="50px" class="report-table-header"><?php echo Portal::language('ticket');?></th>
                <?php }?>--->
                <?php }}unset($this->map['department']['current']);} ?>
                <th width="50px" class="report-table-header"><?php echo Portal::language('other');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('total');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('discount');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('deposit');?></th>
                <th width="50px" class="report-table-header"><?php echo Portal::language('group_deposit');?></th>
        	</tr>
            <!---/TITLE--->
            
            <!---GROUP ABOVE--->
            <?php 
				if(($this->map['page_no']>1))
				{?>
            <tr>
                <th colspan="10"><?php echo Portal::language('last_page_summary');?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_room']?System::display_number($this->map['last_group_function_params']['total_remain_room']):0; ?></th>
                <?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key2=>&$item2){if($key2!='current'){$this->map['department']['current'] = &$item2;?>
                <?php if($this->map['department']['current']['res']==1){?>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_bar']?System::display_number($this->map['last_group_function_params']['total_remain_bar']):0; ?></th>
                <?php }?>
                <!---<?php if($this->map['department']['current']['karaoke']==1){?>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_karaoke']?System::display_number($this->map['last_group_function_params']['total_remain_karaoke']):0; ?></th>
                 <?php }?>--->
                <?php if($this->map['department']['current']['hk']==1){?>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_minibar']?System::display_number($this->map['last_group_function_params']['total_remain_minibar']):0; ?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_laundry']?System::display_number($this->map['last_group_function_params']['total_remain_laundry']):0; ?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_equip']?System::display_number($this->map['last_group_function_params']['total_remain_equip']):0; ?></th>
                <?php }?>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_telephone']?System::display_number($this->map['last_group_function_params']['total_remain_telephone']):0; ?></th>
                <?php if($this->map['department']['current']['massage']==1){?> 
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_massage']?System::display_number($this->map['last_group_function_params']['total_remain_massage']):0; ?></th>
                <?php }?>            
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_tour']?System::display_number($this->map['last_group_function_params']['total_remain_tour']):0; ?></th>
                <!---<?php if($this->map['department']['current']['vending']==1){?> 
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_vending']?System::display_number($this->map['last_group_function_params']['total_remain_vending']):0; ?></th>
                <?php }?>
                <?php if($this->map['department']['current']['ticket']==1){?> 
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_ticket']?System::display_number($this->map['last_group_function_params']['total_remain_ticket']):0; ?></th>
                <?php }?>--->
                <?php }}unset($this->map['department']['current']);} ?>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_other']?System::display_number($this->map['last_group_function_params']['total_remain_other']):0; ?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain']?System::display_number($this->map['last_group_function_params']['total_remain']):0; ?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_discount']?System::display_number($this->map['last_group_function_params']['total_remain_discount']):0; ?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_deposit']?System::display_number($this->map['last_group_function_params']['total_remain_deposit']):0; ?></th>
                <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_group_deposit']?System::display_number($this->map['last_group_function_params']['total_remain_group_deposit']):0; ?></th>
            </tr>
            
				<?php
				}
				?>
            <!---/GROUP ABOVE--->
                
            <!---CELL--->
            <?php $reservation_id = ''; ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current'] = &$item3;?>
            <tr>
                <?php if(isset($this->map['items']['current']['reservation_rooms']) and is_array($this->map['items']['current']['reservation_rooms'])){ foreach($this->map['items']['current']['reservation_rooms'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['reservation_rooms']['current'] = &$item4;?>
                <?php if($reservation_id != $this->map['items']['current']['recode']){ ?>
                <td align="center" rowspan="<?php echo count($this->map['items']['current']['reservation_rooms'])-1; ?>"><?php echo $this->map['items']['current']['stt'];?></td>
                <td align="center" rowspan="<?php echo count($this->map['items']['current']['reservation_rooms'])-1; ?>"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['recode']));?>" ><?php echo $this->map['items']['current']['recode'];?></a></td>
                <td align="left" rowspan="<?php echo count($this->map['items']['current']['reservation_rooms'])-1; ?>"><?php echo $this->map['items']['current']['customer_name'];?></td>
                <?php } ?>
                <td align="center"><?php echo $this->map['items']['current']['reservation_rooms']['current']['room_name'];?></td>
                <td align="left"><?php echo $this->map['items']['current']['reservation_rooms']['current']['guest_name'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['reservation_rooms']['current']['arrival_time'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['reservation_rooms']['current']['departure_time'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['reservation_rooms']['current']['dem'];?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['price_vn']?System::display_number(round($this->map['items']['current']['reservation_rooms']['current']['price_vn'])):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['price_usd']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['price_usd']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_room']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_room']):0; ?></td>
                <?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key5=>&$item5){if($key5!='current'){$this->map['department']['current'] = &$item5;?>
                <?php if($this->map['department']['current']['res']==1){?>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_bar']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_bar']):0; ?></td>
                <?php }?>
                <!---<?php if($this->map['department']['current']['karaoke']==1){?>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_karaoke']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_karaoke']):0; ?></td>
                <?php }?>--->
                <?php if($this->map['department']['current']['hk']==1){?>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_minibar']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_minibar']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_laundry']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_laundry']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_equip']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_equip']):0; ?></td>
                <?php }?>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_telephone']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_telephone']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_massage']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_massage']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_tour']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_tour']):0; ?></td>
                <!---<?php if($this->map['department']['current']['vending']==1){?> 
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_vending']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_vending']):0; ?></td>
                <?php }?>
                <?php if($this->map['department']['current']['ticket']==1){?> 
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_ticket']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_ticket']):0; ?></td>
                <?php }?>--->
                <?php }}unset($this->map['department']['current']);} ?>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_other']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_other']):0; ?></td>
                <td align="right" style="font-weight: bold;"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_total']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_total']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_discount']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_discount']):0; ?></td>
                <td align="right"><?php echo $this->map['items']['current']['reservation_rooms']['current']['remain_deposit']?System::display_number($this->map['items']['current']['reservation_rooms']['current']['remain_deposit']):0; ?></td>
                <?php if($reservation_id != $this->map['items']['current']['recode']){ ?>
                <td align="right" rowspan="<?php echo count($this->map['items']['current']['reservation_rooms'])-1; ?>"><?php echo $this->map['items']['current']['remain_group_deposit']?System::display_number($this->map['items']['current']['remain_group_deposit']):0; ?></td>
                <?php $reservation_id = $this->map['items']['current']['recode'];} ?>
            </tr>
            <?php }}unset($this->map['items']['current']['reservation_rooms']['current']);} ?>
            <?php }}unset($this->map['items']['current']);} ?>
            <!---CELL--->
            
            <!--GROUP TOTAL-->
            <tr>
                <th colspan="10"><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_room']?System::display_number($this->map['group_function_params']['total_remain_room']):0; ?></th>
                <?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key6=>&$item6){if($key6!='current'){$this->map['department']['current'] = &$item6;?>
                <?php if($this->map['department']['current']['res']==1){?>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_bar']?System::display_number($this->map['group_function_params']['total_remain_bar']):0; ?></th>
                <?php }?>
                <!---<?php if($this->map['department']['current']['karaoke']==1){?>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_karaoke']?System::display_number($this->map['group_function_params']['total_remain_karaoke']):0; ?></th>
                <?php }?>--->
                <?php if($this->map['department']['current']['hk']==1){?>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_minibar']?System::display_number($this->map['group_function_params']['total_remain_minibar']):0; ?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_laundry']?System::display_number($this->map['group_function_params']['total_remain_laundry']):0; ?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_equip']?System::display_number($this->map['group_function_params']['total_remain_equip']):0; ?></th>
                <?php }?>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_telephone']?System::display_number($this->map['group_function_params']['total_remain_telephone']):0; ?></th>
                <?php if($this->map['department']['current']['massage']==1){?>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_massage']?System::display_number($this->map['group_function_params']['total_remain_massage']):0; ?></th>
                <?php }?> 
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_tour']?System::display_number($this->map['group_function_params']['total_remain_tour']):0; ?></th>
                <!---<?php if($this->map['department']['current']['vending']==1){?> 
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_vending']?System::display_number($this->map['group_function_params']['total_remain_vending']):0; ?></th>
                <?php }?>
                <?php if($this->map['department']['current']['ticket']==1){?> 
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_ticket']?System::display_number($this->map['group_function_params']['total_remain_ticket']):0; ?></th>
                <?php }?>--->
                <?php }}unset($this->map['department']['current']);} ?>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_other']?System::display_number($this->map['group_function_params']['total_remain_other']):0; ?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain']?System::display_number($this->map['group_function_params']['total_remain']):0; ?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_discount']?System::display_number($this->map['group_function_params']['total_remain_discount']):0; ?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_deposit']?System::display_number($this->map['group_function_params']['total_remain_deposit']):0; ?></th>
                <th align="right"><?php echo $this->map['group_function_params']['total_remain_group_deposit']?System::display_number($this->map['group_function_params']['total_remain_group_deposit']):0; ?></th>
            </tr>
            <!--/GROUP TOTAL-->
        </table>
        </td>
    </tr>
    <?php 
				if(($this->map['page_no']))
				{?>
    <tr>
        <td>
            <center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
            <?php 
				if(($this->map['page_no']<$this->map['total_page']))
				{?>
            <br />
            <br />
            
				<?php
				}
				?>
        </td>
    </tr>
    
				<?php
				}
				?>
<!--/REPORT-->
<script type="text/javascript">
    
</script>    
