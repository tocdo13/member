<style>
.center{text-align:center;}
</style>
<div class="room-type-supplier-bound">
<form name="ListExtraServiceInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="100%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
			
        </tr>
    </table>
    
    <!--<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			Bộ phận: <select  name="department_id" id="department_id" style="width: 100px;"><?php
					if(isset($this->map['department_id_list']))
					{
						foreach($this->map['department_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('department_id',isset($this->map['department_id'])?$this->map['department_id']:''))
                    echo "<script>$('department_id').value = \"".addslashes(URL::get('department_id',isset($this->map['department_id'])?$this->map['department_id']:''))."\";</script>";
                    ?>
	</select>
            <input name="search" type="submit" id="search" value="<?php echo Portal::language('search');?>" />
		</fieldset><br />-->
            
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
            <th width="5%" class="center"><?php echo Portal::language('stt');?></th>
			  <th width="10%" class="center"><?php echo Portal::language('arrive_date');?></th>
			  <th width="5%" class="center">Recode</th>
              <th width="5%" class="center"><?php echo Portal::language('room_name');?></th>
              <th width="8%" class="center"><?php echo Portal::language('status');?></th>
              <th width="15%" class="center"><?php echo Portal::language('service');?></th>
              <th width="5%" class="center"><?php echo Portal::language('quantity');?></th>
              <th width="5%" class="center"><?php echo Portal::language('unit');?></th>
              <th width="10%" class="center"><?php echo Portal::language('used');?></th>
			  <th width="8%" class="center"><?php echo Portal::language('price');?></th>
			  <th width="8%" class="center"><?php echo Portal::language('money');?></th>
			  <th width="10%" class="center"><?php echo Portal::language('note');?></th>
              <th width="15%" class="center"><?php echo Portal::language('bar_reservation');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
          <?php
            if($this->map['items']['current']['status_reservation']=='CHECKOUT')
            {
                $style ='style="background-color: yellow;"';
            } 
            else
            {
                $style='';
            }
          ?>
			<tr style="height: 24px;" <?php echo $style;?>>
                <td class="center"><?php echo $this->map['items']['current']['index'];?></td>
                <td class="center"><?php echo $this->map['items']['current']['in_date'];?></td>
                <td class="center"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id']));?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
                <td class="center"><?php echo $this->map['items']['current']['room_name'];?></td>
                <td class="center"><?php echo $this->map['items']['current']['status_reservation'];?></td>
                <td class="center"><?php echo $this->map['items']['current']['name'];?></td>
                <td class="center"><?php echo $this->map['items']['current']['quantity'];?></td>
		<td class="center"><?php echo $this->map['items']['current']['unit'];?></td>
                <td class="center"><?php echo $this->map['items']['current']['quantity_used'];?>
                <?php
                    switch(Url::get('cmd'))
                    {
                        case 'res':
                        {
                            if(count($this->map['items']['current']['bars'])>0)
                            {
                                $count = count($this->map['items']['current']['bars'])-1;
                                $i= 0;
                                echo '(';
                                ?>
                                <?php if(isset($this->map['items']['current']['bars']) and is_array($this->map['items']['current']['bars'])){ foreach($this->map['items']['current']['bars'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['bars']['current'] = &$item2;?>
                                <?php
                                    
                                    if($i==$count)
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$this->map['items']['current']['bars']['current']['id'],'package_id'=>$this->map['items']['current']['bars']['current']['package_id'],'rr_id'=>$this->map['items']['current']['bars']['current']['reservation_room_id'],'table_id'=>$this->map['items']['current']['bars']['current']['table_id'],'bar_area_id'=>$this->map['items']['current']['bars']['current']['bar_area_id'],'bar_id'=>$this->map['items']['current']['bars']['current']['bar_id']));?>" target="_blank"><?php echo $this->map['items']['current']['bars']['current']['id'];?></a>)
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$this->map['items']['current']['bars']['current']['id'],'package_id'=>$this->map['items']['current']['bars']['current']['package_id'],'rr_id'=>$this->map['items']['current']['bars']['current']['reservation_room_id'],'table_id'=>$this->map['items']['current']['bars']['current']['table_id'],'bar_area_id'=>$this->map['items']['current']['bars']['current']['bar_area_id'],'bar_id'=>$this->map['items']['current']['bars']['current']['bar_id']));?>" target="_blank"><?php echo $this->map['items']['current']['bars']['current']['id'];?></a>,
                                        <?php 
                                    }
                                    $i++;
                                ?>
                                
                                <?php }}unset($this->map['items']['current']['bars']['current']);} ?>
                                <?php 
                            } 
                            break;
                        }
                        case 'spa':
                        {
                            if(count($this->map['items']['current']['massages'])>0)
                            {
                                $count = count($this->map['items']['current']['massages'])-1;
                                $i= 0;
                                echo '(';
                                ?>
                                <?php if(isset($this->map['items']['current']['massages']) and is_array($this->map['items']['current']['massages'])){ foreach($this->map['items']['current']['massages'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['massages']['current'] = &$item3;?>
                                <?php
                                    
                                    if($i==$count)
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','id'=>$this->map['items']['current']['massages']['current']['id'],'package_id'=>$this->map['items']['current']['massages']['current']['package_id'],'rr_id'=>$this->map['items']['current']['massages']['current']['hotel_reservation_room_id']));?>" target="_blank"><?php echo $this->map['items']['current']['massages']['current']['id'];?></a>)
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','id'=>$this->map['items']['current']['massages']['current']['id'],'package_id'=>$this->map['items']['current']['massages']['current']['package_id'],'rr_id'=>$this->map['items']['current']['massages']['current']['hotel_reservation_room_id']));?>" target="_blank"><?php echo $this->map['items']['current']['massages']['current']['id'];?></a>,
                                        <?php 
                                    }
                                    $i++;
                                ?>
                                
                                <?php }}unset($this->map['items']['current']['massages']['current']);} ?>
                                <?php 
                            }
                            break;
                        }
                        default:
                        {
                            break;
                        }
                    }
                    
                ?>
                
                </td>
                <td align="right"><?php echo $this->map['items']['current']['price'];?></td>
                <td align="right"><?php echo $this->map['items']['current']['total_amount'];?></td>
                <td align="left"><?php echo $this->map['items']['current']['note'];?></td>
                <?php
                switch(Url::get('cmd'))
                {
                    case 'res':
                    {
                        if($this->map['items']['current']['status_reservation']=='CHECKIN' ||$this->map['items']['current']['status_reservation']=='BOOKED')
                        {
                            if($this->map['items']['current']['quantity_used']<$this->map['items']['current']['quantity'] || $this->map['items']['current']['quantity_used']=='')
                            {
                                ?>
                                <td class="center"><a href="<?php echo Url::build('table_map',array('package_id'=>$this->map['items']['current']['package_sale_detail_id'],'reservation_room_id'=>$this->map['items']['current']['reservation_room_id'])); ?>" target="_blank"  style="text-align: center;">Đặt bàn</a></td>
                                <?php 
                            }
                            else
                            {
                                ?>
                                <td class="center"></td>
                                <?php 
                            }
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                    case 'kar':
                    {
                        if($this->map['items']['current']['status_reservation']=='CHECKIN' ||$this->map['items']['current']['status_reservation']=='BOOKED')
                        {
                        ?>
                        <td class="center"><a href="<?php echo Url::build('karaoke_table_map',array('package_id'=>$this->map['items']['current']['package_sale_detail_id'],'reservation_room_id'=>$this->map['items']['current']['reservation_room_id'])); ?>" target="_blank"  style="text-align: center;">Đặt phòng</a></td>
                        <?php 
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                    case 'spa':
                    {
                        if($this->map['items']['current']['status_reservation']=='CHECKIN' ||$this->map['items']['current']['status_reservation']=='BOOKED')
                        {
                            if($this->map['items']['current']['quantity_used']<$this->map['items']['current']['quantity'] || $this->map['items']['current']['quantity_used']=='')
                            {
                                ?>
                                <td class="center"><a href="<?php echo Url::build('massage_daily_summary',array('package_id'=>$this->map['items']['current']['package_sale_detail_id'],'reservation_room_id'=>$this->map['items']['current']['reservation_room_id'])); ?>" target="_blank"  style="text-align: center;">Đặt phòng spa</a></td>
                                <?php 
                            }
                            else
                            {
                                ?>
                                <td class="center"></td>
                                <?php 
                            }
                        
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                    case 'vend':
                    {
                        if($this->map['items']['current']['status_reservation']=='CHECKIN')
                        {
                        ?>
                        <td class="center"><a href="<?php echo Url::build('vending_reservation',array('package_id'=>$this->map['items']['current']['id'])); ?>" target="_blank"  style="text-align: center;">Bán hàng</a></td>
                        <?php 
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                } 
                ?>
                
                
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>		
		</table>
<br />
	</div>
	<input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>


<script type="text/javascript">
    jQuery("#delete_button").click(function () {
    ListRoomTypeForm.cmd.value = 'delete';
    ListRoomTypeForm.submit();
});
jQuery(".delete-one-item").click(function () {
    if (!confirm('<?php echo Portal::language('are_you_sure');?>')) {
        return false;
    }
});
jQuery("#all_item_check_box").click(function () {
    var check = this.checked;
    jQuery(".item-check-box").each(function () {
        this.checked = check;
    });
});

function select_delete_all() {

    var delete_ids = [];
    jQuery.each(jQuery("input[name='item_check_box']:checked"), function () {
        delete_ids.push(jQuery(this).val());
    });
    delete_ids.join(",");
    if (delete_ids == '') {
        alert('Bạn chưa chọn khách hàng');
        return false;
    } else {
        var answer = confirm("Bạn có chắc chắn không?");
        if (answer)
            window.location.href = '?page=package_service_two&cmd=delete&delete_ids=' + delete_ids;
        else
            return false;    
    }
}
function get_id_delete(id) {
    var answer = confirm("Bạn có chắc chắn không?");
    if (answer)
        window.location.href = '?page=package_service_two&cmd=delete&delete_ids='+id;
    else
        return false;
}

</script>
