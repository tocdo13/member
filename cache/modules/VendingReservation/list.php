<link href="packages/hotel/packages/vending/skins/default/css/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('vending_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" style="width: 350px;"><?php echo Portal::language('vending_reservation_list');?></td>
                    <td>
                    <?php if(isset($this->map['area']) and is_array($this->map['area'])){ foreach($this->map['area'] as $key1=>&$item1){if($key1!='current'){$this->map['area']['current'] = &$item1;?>
					<?php if(User::can_add(false,ANY_CATEGORY)){?>
                        <a href="<?php echo Url::build('automatic_vend',array('cmd'=>'add','department_id'=>$this->map['area']['current']['id'],'department_code'=>$this->map['area']['current']['code'],'arrival_time'=>date('d/m/Y')));?>" class="button-medium-add" style="float: right; margin: 3px;"><?php echo $this->map['area']['current']['name'];?></a>
                    <?php }?>
                    <?php }}unset($this->map['area']['current']);} ?>
                    </td>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};BarReservationNewListForm.cmd.value='delete';BarReservationNewListForm.submit();"  class="button-medium-delete"><?php echo Portal::language('Delete');?></a></td><?php }?>                    
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
			<td>
				<fieldset>
                <legend class="title"><?php echo Portal::language('search_options');?></legend>
                <form method="post" name="SearchBarReservationNewForm"> 
                    <table>
                        <tr>
                            <td align="right" ><?php echo Portal::language('product_code');?></td>
                            <td><input  name="product_code" id="product_code" style="width:80px;" class="date-input" / type ="text" value="<?php echo String::html_normalize(URL::get('product_code'));?>">                      </td>
                            <td align="right" ><?php echo Portal::language('invoice_number');?> <input  name="invoice_number" id="invoice_number" class="date-input" / type ="text" value="<?php echo String::html_normalize(URL::get('invoice_number'));?>"></td>
                            <td align="right" ><?php echo Portal::language('arrival_time');?></td>
                            <td>
                                <input  name="from_arrival_time" id="from_arrival_time" style="width:80px;" onchange="changevalue();" class="date-input"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_arrival_time'));?>">
                            &nbsp;<?php echo Portal::language('to');?>
                                <input  name="to_arrival_time" id="to_arrival_time" onchange="changefromday();" style="width:80px;" class="date-input"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_arrival_time'));?>">
                            </td>
                            <td><strong><?php echo Portal::language('area');?></strong></td>
                            <td><select  name="area_id" id="area_id"><?php
					if(isset($this->map['area_id_list']))
					{
						foreach($this->map['area_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('area_id',isset($this->map['area_id'])?$this->map['area_id']:''))
                    echo "<script>$('area_id').value = \"".addslashes(URL::get('area_id',isset($this->map['area_id'])?$this->map['area_id']:''))."\";</script>";
                    ?>
	</select></td>
                            <td><input type="submit" value="<?php echo Portal::language('search');?>" /></td>
                        </tr>
                    </table>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                </fieldset>
                
                <form name="BarReservationNewListForm" method="post">
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
						<th><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th align="left"><?php echo Portal::language('time');?>/<?php echo Portal::language('time_in');?></th>
                        <!--<th align="left"><?php echo Portal::language('mice');?></th>-->
                        <th align="left"><?php echo Portal::language('device_code');?></th>
                        <th align="left"><?php echo Portal::language('guest_phone_number');?></th>
						<th align="left"><?php echo Portal::language('code');?></th>
						<!--<th align="left"><?php echo Portal::language('agent_name');?></th>-->
						<th align="left"><?php echo Portal::language('total');?></th>
                        <th align="center"><?php echo Portal::language('payment_status');?></th>
                        <!--<th align="center" ><?php echo Portal::language('deposit_status');?></th>-->
						<th align="left"><?php echo Portal::language('user');?></th>
                        <th align="left"><?php echo Portal::language('lastest_edited_user_id');?></th>
                        <!--<th>&nbsp;</th>
                        <th>&nbsp;</th>-->
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
						<?php }?>
                    </tr>
					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
					<tr class="<?php echo $this->map['items']['current']['row_class'];?>" bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} ?>" valign="middle" <?php Draw::hover('#EFEEEE');?>  >
						<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>"/></td>
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['arrival_date'];?></td>
                        <!--<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php if($this->map['items']['current']['mice_reservation_id']!=''){ ?>MICE+<?php echo $this->map['items']['current']['mice_reservation_id'];?><?php } ?></td>-->
                        <td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['device_code'];?></td>
                        <td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['guest_phone_number'];?></td>
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['code'];?></td>
						<!--<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['agent_name'];?></td>-->
						<td align="right" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['total'];?></td>
                        <td align="center" style="cursor:pointer; text-align: right;">
                            <?php 
                                if($this->map['items']['current']['pay_with_room']==1) echo Portal::language('pay_with_room_amount').': ('.$this->map['items']['current']['amount_pay_with_room'].')';
                                else
                                    echo $this->map['items']['current']['payment_status'] != 0? Portal::language('paid'):Portal::language('not_paid_yet');
                            ?>
                        </td>
                        <!--
                        <td align="center" style="cursor:pointer; text-align: right;">
                            <?php  
                                echo $this->map['items']['current']['deposit'] == 0? Portal::language('not_deposit_yet'):System::display_number($this->map['items']['current']['deposit']); 
                            ?>
                        </td>
                        -->
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['user_id'];?></td>
                        <td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$this->map['items']['current']['id']))?>');"><?php echo $this->map['items']['current']['lastest_edited_user_id'];?></td>
						<!--
                        <td nowrap align="center">
                            <?php if($this->map['items']['current']['mice_reservation_id']==''){ ?>
                            <a onclick="openPayment(<?php echo $this->map['items']['current']['id'];?>,<?php echo (System::calculate_number($this->map['items']['current']['total'])); ?>,0,<?php if($this->map['items']['current']['member_code']){ echo $this->map['items']['current']['member_code']; }else{ echo 0; } ?>);" >
                                <img src="packages/core/skins/default/images/buttons/rate.jpg" alt="<?php echo Portal::language('payment');?>" title="<?php echo Portal::language('payment');?>" height="12" border="0"/>
                            </a>
                            <?php } ?>
                        </td>
                        <td nowrap align="center">
                            <?php if($this->map['items']['current']['mice_reservation_id']==''){ ?>
                            <a onclick="
                                    if(<?php echo $this->map['items']['current']['payment_status'];?> == 1) 
                                    {
                                        alert('<?php echo Portal::language('this_order_has_been_paid');?>');
                                        return false;
                                    }
                                    else
                                    openDeposit(<?php echo $this->map['items']['current']['id'];?>,0,<?php echo (System::calculate_number($this->map['items']['current']['total'])); ?>);
                                " >
                                <img src="packages/core/skins/default/images/buttons/copy.png" alt="<?php echo Portal::language('deposit');?>" title="<?php echo Portal::language('deposit');?>" height="12" border="0"/>
                            </a>
                            <?php } ?>
                        </td>
                        -->
                        <?php if(User::can_admin(false,ANY_CATEGORY)) {?> 
                        <td><a href="<?php echo Url::build('automatic_vend',array('cmd'=>'edit','id'=>$this->map['items']['current']['id'],'department_id'=>$this->map['items']['current']['department_id'],'department_code'=>$this->map['items']['current']['department_code'])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>" width="12" height="12"/></a></td>
                        <?php } ?>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                        <td><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>" height="12"/></a></td>
						<?php } ?>
                    </tr>
					<?php }}unset($this->map['items']['current']);} ?>
				</table>
                </div>
                <?php echo $this->map['paging'];?>
                <input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    function changevalue()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_arrival_time").val(jQuery("#from_arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_arrival_time").val(jQuery("#to_arrival_time").val());
        }
    }
    function openPayment(id,total_amount,pay_with_room,member_code)
    {
        //openWindowUrl('form.php?block_id=718&cmd=cancel&invoice_id='+jQuery('#id_'+index).val(),Array('cancel','cancel_for',80,210,950,500));
        if(pay_with_room == 0)
        {
            if(member_code!=0){
                openWindowUrl('form.php?block_id=428&id='+id+'&member_code='+member_code+'&type=VEND&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));
            }else{
                openWindowUrl('form.php?block_id=428&id='+id+'&type=VEND&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));
            }   
        }
        else
        {
            alert('<?php echo Portal::language('this_bill_is_moved_on_room'); ?>');
        }
    }
    //deposit
    function openDeposit(id,pay_with_room,total_amount)
    {
        if(pay_with_room == 0)
        {
            openWindowUrl('form.php?block_id=428&cmd=deposit&id='+id+'&type=VEND&total_amount='+total_amount,Array('deposit','deposit_for',80,210,950,500));   
        }
        else
        {
            alert('<?php echo Portal::language('this_bill_is_moved_on_room'); ?>');
        }
    }
    
</script>