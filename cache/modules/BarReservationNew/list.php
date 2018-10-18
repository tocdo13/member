<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="60%"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('bar_reservation_list');?> <?php echo $this->map['bar_name'];?> <?php echo Url::get('status');?></td>
                    <!--Luu Nguyen GIap comment remove button datban  -->
					<?php //if(User::can_add(false,ANY_CATEGORY)){?><!--<td width="1%" align="right"><a href="<?php //echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add"><?php echo Portal::language('bar_reservation');?></a></td>--><?php //}?>
                     <!--End Luu Nguyen Giap-->
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <td width="40%" style="text-align: right; padding-right: 30px;"><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};BarReservationNewListForm.cmd.value='delete';BarReservationNewListForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a></td><?php }?>                    
                </tr>
                <tr>
                    <td>
                        <?php if(Form::$current->is_error()){?><div><?php echo Form::$current->error_messages();?></div><?php }?>
                    </td>
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
                <legend class="" style="text-transform: uppercase;"><?php echo Portal::language('search_options');?></legend>
                <form method="post" name="SearchBarReservationNewForm"> 
                <table>
                    <tr>
                      <!--<td align="right" nowrap="nowrap"><?php echo Portal::language('product_code');?></td>
                      <td>:</td>
                      <td nowrap="nowrap"><input  name="product_code" id="product_code" style="width:80px;" class="date-input" / type ="text" value="<?php echo String::html_normalize(URL::get('product_code'));?>">                      </td>-->
                      <td align="right" nowrap><?php echo Portal::language('invoice_number');?> <input  name="invoice_number" id="invoice_number" class="date-input" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('invoice_number'));?>"></td>
                        <td align="right" nowrap><?php echo Portal::language('arrival_time');?></td>
                        
                        <td nowrap>
                                <input  name="from_arrival_time" id="from_arrival_time" style="width:80px; height: 24px;" onchange="changevalue();" class="date-input" type ="text" value="<?php echo String::html_normalize(URL::get('from_arrival_time'));?>">&nbsp;<?php echo Portal::language('to');?>
                                <input  name="to_arrival_time" id="to_arrival_time" style="width:80px; height: 24px;" class="date-input" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('to_arrival_time'));?>"></td>
                                
                        <td nowrap>&nbsp;</td>
                        <td><select  name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value" style="height:24px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
                        
                        <td>&nbsp;</td>
                        <td align="right" style="text-align:right;">
                                <?php echo Portal::language('bar_name');?> <select  name="bars" id="bars" onchange="updateBar();" style="height: 24px;"><?php
					if(isset($this->map['bars_list']))
					{
						foreach($this->map['bars_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))
                    echo "<script>$('bars').value = \"".addslashes(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))."\";</script>";
                    ?>
	</select> 
                                <input  name="acction" value="0" id="acction" style="height: 24px;" / type ="hidden" value="<?php echo String::html_normalize(URL::get('acction'));?>">
                                <script>
                                    var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                                    if(bar_id != ''){
                                    	$('bars').value = bar_id;	
                                    }
                                 </script>
                        </td>
                        <td nowrap><input class="w3-btn w3-gray" type="submit" value="<?php echo Portal::language('search');?>" style="height: 24px; padding-top: 5px;"/></td>
                    </tr>
                </table>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                </fieldset>
				<form name="BarReservationNewListForm" method="post">
				<p>
				<table width="100%" cellpadding="2" style="display: none;">
                    <tr>
                        <td width="1%"><input type="button" value="<?php echo Portal::language('list_title_debt');?>" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_debt'));?>'" /></td>
                        <td width="1%"><input type="button" value="<?php echo Portal::language('list_title_free');?>" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_free'));?>'" /></td>
                        <td width="1%"><input type="button" value="<?php echo Portal::language('list_title_cancel');?>" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_cancel'));?>'" /></td>
                        <td width="1%" align="right">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td align="right"><strong><?php echo Portal::language('currency');?>: <?php echo HOTEL_CURRENCY;?>&nbsp;&nbsp;</strong></td>
                    </tr>
                </table>
				</p>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th nowrap align="center">
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.time'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('time');?>/<?php echo Portal::language('time_in');?></a></th>
						
						<th align="center" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.code'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('code');?> </a>						</th>
                        <th align="center" nowrap="nowrap"> <?php echo Portal::language('mice');?></th>
						<th align="center" nowrap="nowrap"> 
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('source_customer');?> / <?php echo Portal::language('guest_name');?></a>							</th>
						<th align="center" nowrap="nowrap"><?php echo Portal::language('room_name');?></th>
						<th align="center" nowrap="nowrap"><?php echo Portal::language('table_number');?></th>
						<th align="center" nowrap="nowrap"><?php echo Portal::language('total');?></th>
						<th align="center" nowrap="nowrap"><?php echo Portal::language('time_length');?></th>
						<th align="center" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.status'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('status');?></a>						</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?><th width="1%" nowrap="nowrap">&nbsp;</th>
						<th style="text-align: center;"><?php echo Portal::language('user');?></th>
						<th style="text-align: center;"><?php echo Portal::language('edit');?></th><?php }?>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?><th width="1%"><?php echo Portal::language('delete');?></th>
						<?php }?></tr>
					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                    <?php
						if($this->map['items']['current']['status']=='CHECKIN')
						{
							$bg_color = '#FFFF99';
						}
						else if($this->map['items']['current']['status']=='CHECKOUT')
						{
							$bg_color = '#E2F1DF';
						}
						else
						{
							$bg_color = '#FFFFFF';
						}						
						?>
                    
                    <?php //Xem hoa don echo URL::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print'),'method'=>'print_direct','id'=>$this->map['items']['current']['id']));?>
					<tr style="height: 24px;" bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build('touch_bar_restaurant').'&cmd=edit';?>&id=<?php echo $this->map['items']['current']['id'];?>&bar_id=<?php echo $this->map['items']['current']['bar_id'];?>';}else{just_click=false;}" style="cursor:hand;<?php if($this->map['items']['current']['status']=='CHECKIN'){?>font-weight:bold;<?php }?>">
						<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"></td>
							<td nowrap align="left"><?php echo $this->map['items']['current']['arrival_date'];?></td>
							<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['code'];?></td>
                            <td align="left" nowrap="nowrap"><?php if($this->map['items']['current']['mice_reservation_id']!=''){ ?>MICE+<?php echo $this->map['items']['current']['mice_reservation_id'];?><?php } ?></td>
							<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['name'];?></td>
							<td align="center" nowrap="nowrap"> <?php echo $this->map['items']['current']['room_name'];?></td>
							<td align="center" nowrap="nowrap"><?php echo $this->map['items']['current']['table_name'];?></td>
							<td align="right" nowrap="nowrap"><?php echo $this->map['items']['current']['total'];?></td>
							<td align="center" nowrap="nowrap"> <?php echo $this->map['items']['current']['time_length'];?> </td>
							<td align="left" nowrap><?php echo $this->map['items']['current']['status'];?></td>
							<td nowrap><?php if(User::can_delete(false,ANY_CATEGORY) and ($this->map['items']['current']['status']!='CHECKOUT' and $this->map['items']['current']['status']!='CANCEL')) {?><a href="<?php echo Url::build_current(array('act'=>'cancel','id'=>$this->map['items']['current']['id'],'bar_id'=>$this->map['items']['current']['bar_id'])); ?>" style="color:#FF0000;text-decoration:underline;"><?php echo Portal::language('cancel_this_order');?></a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
							<td align="center" nowrap="nowrap"><?php echo $this->map['items']['current']['user_id'];?></td>
							<?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td style="text-align: center;" nowrap><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'],'bar_id'=>$this->map['items']['current']['bar_id'],'table_id'=>$this->map['items']['current']['table_id'],'bar_area_id'=>$this->map['items']['current']['bar_area_id'],'package_id'=>$this->map['items']['current']['package_id'])); ?>"><i class="fa fa-edit" style="font-size: 16px;"></i></a></td> <?php } ?>
							<?php if(User::can_admin(false,ANY_CATEGORY)) {?><td style="text-align: center;" nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>$this->map['items']['current']['id'],'bar_id'=>$this->map['items']['current']['bar_id'])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 16px;"></i></a></td>
							<?php } ?></tr>
					<?php }}unset($this->map['items']['current']);} ?>
				</table>
                </div>
                <?php echo $this->map['paging'];?>
                <p>
                <table style="display: none;"><tr>
                    <td width="1%"><input type="button" value="<?php echo Portal::language('list_title_debt');?>" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_debt'));?>'" /></td>
                    <td width="1%"><input type="button" value="<?php echo Portal::language('list_title_free');?>" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_free'));?>'" /></td>
                    <td width="1%"><input type="button" value="<?php echo Portal::language('list_title_cancel');?>" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_cancel'));?>'" /></td>
                </tr></table>
                </p>
			<input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
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
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
</script>
<script>
    function check_validate_time(){
        from_arrival_time = jQuery('#from_arrival_time').val();
        to_arrival_time = jQuery('#to_arrival_time').val();
        if(from_arrival_time>to_arrival_time){
            alert("To Date must be greater From Date");
            jQuery('#to_arrival_time').css('border','1px solid red');
            jQuery('#to_arrival_time').val(from_arrival_time);
        }
    }
</script>