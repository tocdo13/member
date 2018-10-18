<style>
tr td{
    padding-top: 5px;
    padding-bottom: 5px;
}
</style>
<form name="MassageReservationReportForm" method="post">
<div class="massage-daily-summary-bound">
	<table cellpadding="15" cellspacing="0" border="0" bordercolor="#CCCCCC" class="table-bound" style="width: 1200px; margin: 0px auto;">
		<tr>
        	<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('massage_reservation');?></td>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="20%" align="right" style="padding-right: 30px;"><a class="w3-btn w3-red" onclick="if(confirm('<?php echo Portal::language('are_you_sure');?>?')){MassageReservationReportForm.submit();}else{return false;}" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('delete');?></a></td><?php }?>
        </tr>
    </table>  
	<div class="body">
		<br />
		<div class="select-date">
        <fieldset style="width: 1200px; margin: 0px auto;">
        <legend><?php echo Portal::language('search');?></legend>
			<?php echo Portal::language('date');?>: <input  name="date" id="date" class="date" style="height: 24px; margin-right: 10px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>">
			<?php echo Portal::language('massage_room');?>: <select  name="room_id" id="room_id" style="height: 24px;margin-right: 10px;"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select> 
			<?php echo Portal::language('staff');?>: 
			<select  name="staff_id" id="staff_id" style="height: 24px;margin-right: 10px;"><?php
					if(isset($this->map['staff_id_list']))
					{
						foreach($this->map['staff_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('staff_id',isset($this->map['staff_id'])?$this->map['staff_id']:''))
                    echo "<script>$('staff_id').value = \"".addslashes(URL::get('staff_id',isset($this->map['staff_id'])?$this->map['staff_id']:''))."\";</script>";
                    ?>
	</select><input type="submit" value="OK" style="height: 24px; width: 50px;"/>
        </fieldset>
		</div><br />
		<div class="content">
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style=" width: 1200px; margin: 0px auto;">
                <tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
                    <th width="10px"><input id="check_all" type="checkbox" onclick="checkAll();"/></th>
                    <th width="100px"><?php echo Portal::language('invoice_code');?></th>
                    <th align="center" width="100px"><?php echo Portal::language('recode');?></th>
                    <th align="center" width="150px"><?php echo Portal::language('massage_room');?></th>
                    
                    <th align="center" width="120px"><?php echo Portal::language('time_in');?></th>
                    <th align="center" width="120px"><?php echo Portal::language('time_out');?></th>
                    <th align="center" width="150px"><?php echo Portal::language('hotel_room');?></th>
                    <th align="center" width="200px"><?php echo Portal::language('traveller_name');?></th>
                    <th align="center" width="150px"><?php echo Portal::language('total_amount');?></th>
                    <!--<th align="right" style="d"><?php echo Portal::language('payment');?></th>-->
                    <th align="center" width="150px"><?php echo Portal::language('status');?></th>
                    <th align="center" width="50px"><?php echo Portal::language('view');?></th>
                    <th align="center" width="50px"><?php echo Portal::language('edit');?></th>
                </tr>
                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                <tr>
                    <?php 
				if(($this->map['items']['current']['check_payment']==0))
				{?>
                        <td><input name="item_check[]" type="checkbox" class="item-check" value="<?php echo $this->map['items']['current']['reservation_room_id'];?>"/></td>
                     <?php }else{ ?>
                        <td></td>
                    
				<?php
				}
				?>
                    <?php 
				if(($this->map['items']['current']['first_items']==1))
				{?>
                    <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>"><?php echo $this->map['items']['current']['reservation_room_id'];?></td>
                    <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['mrr_id'];?>"><?php echo $this->map['items']['current']['mrr_id'];?></a></td>
                    
				<?php
				}
				?>
                    <td><?php echo $this->map['items']['current']['room_name'];?></td>
                    <td><?php echo date('d/m/Y H:i\'',$this->map['items']['current']['time_in']);?></td>
                    <td><?php echo date('d/m/Y H:i\'',$this->map['items']['current']['time_out']);?></td>
                    <?php 
				if(($this->map['items']['current']['check_ht_room_same']==1))
				{?>
                    <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center"><?php echo $this->map['items']['current']['hotel_room_name'];?></td> 
                    <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="left"><?php echo $this->map['items']['current']['traveller_name'];?></td>
                    
				<?php
				}
				?>
                    <?php 
				if(($this->map['items']['current']['first_items']==1))
				{?>
                    <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="right"> <?php echo System::display_number($this->map['items']['current']['total_amount_massage']); ?></td>
                    
				<?php
				}
				?>
                    <td align="center" class="<?php echo $this->map['items']['current']['class'];?>" style="width:100px;border:0px;"><?php echo $this->map['items']['current']['status'];?></td>                                       
                    
                    
                    <?php 
                          if(User::can_admin(false,ANY_CATEGORY) && $this->map['items']['current']['reservation_room_status'] !='CHECKOUT' or $this->map['items']['current']['status'] !='CHECKOUT') 
                          {
                                if($this->map['items']['current']['package_id']!='')
                                {
                                    ?>
                                    <?php 
				if(($this->map['items']['current']['first_items']==1))
				{?>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['items']['current']['reservation_room_id'],'package_id'=>$this->map['items']['current']['package_id'],'rr_id'=>$this->map['items']['current']['ht_reservation_room_id']));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center">
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','room_id'=>$this->map['items']['current']['room_id'],'id'=>$this->map['items']['current']['reservation_room_id'],'package_id'=>$this->map['items']['current']['package_id'],'rr_id'=>$this->map['items']['current']['ht_reservation_room_id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                                        </td>
                                    
				<?php
				}
				?>
                                    
                                    <?php 
                                }
                                else
                                {
                                    ?>
                                    <?php 
				if(($this->map['items']['current']['first_items']==1))
				{?>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['items']['current']['reservation_room_id']));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center">
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','room_id'=>$this->map['items']['current']['room_id'],'id'=>$this->map['items']['current']['reservation_room_id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                                        </td>
                                    
				<?php
				}
				?>
                                    
                                    <?php 
                                }
                                
                          }
                          else {
                            if($this->map['items']['current']['package_id']!='')
                                {
                                    ?>
                                    <?php 
				if(($this->map['items']['current']['first_items']==1))
				{?>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['items']['current']['reservation_room_id'],'package_id'=>$this->map['items']['current']['package_id'],'rr_id'=>$this->map['items']['current']['ht_reservation_room_id']));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center">&nbsp;</td>
                                    
				<?php
				}
				?>
                                    
                                    <?php 
                                }
                                else
                                {
                                    ?>
                                     <?php 
				if(($this->map['items']['current']['first_items']==1))
				{?>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['items']['current']['reservation_room_id']));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['count_items']; ?>" align="center">&nbsp;</td>
                                    
				<?php
				}
				?>
                                    
                                    <?php 
                                }
                          }
                          
                    ?>
                    
                </tr>
                <?php }}unset($this->map['items']['current']);} ?>                    
            </table>
			<div class="paging"><?php echo $this->map['paging'];?></div>
		</div>
	</div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
	jQuery("#check_all").click(function (){
		var check  = this.checked;
		jQuery(".item-check").each(function(){
			this.checked = check;
		});
	});
    function openPayment(id,total_amount,pay_with_room)
    {
        //openWindowUrl('form.php?block_id=718&cmd=cancel&invoice_id='+jQuery('#id_'+index).val(),Array('cancel','cancel_for',80,210,950,500));
        if(pay_with_room === 0)
        {
            openWindowUrl('form.php?block_id=428&id='+id+'&type=SPA&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));   
        }
        else
        {
            alert('<?php echo Portal::language('this_bill_is_moved_on_room'); ?>');
        }
    }
	jQuery("#date").datepicker();
</script>
