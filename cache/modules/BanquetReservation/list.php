<!--?php echo"tet8";?-->
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('banquet_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px;" width="60%"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php if(!isset($_REQUEST['status']) AND Url::get('cmd')=='list_cancel'){ echo Portal::language('banquet_reservation_list_cancel'); }elseif(Url::get('status')=='CHECKOUT'){ echo Portal::language('banquet_reservation_list_checkout'); }elseif(Url::get('status')=='CHECKIN'){ echo Portal::language('banquet_reservation_list_checkin'); }else{ echo Portal::language('banquet_reservation_list'); } ?></td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" align="right" style="padding-right: 30px;">
                    <!--trung cmt den link select type banquet <a href="<?php //echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add"><?php echo Portal::language('banquet_reservation');?></a>-->
                    <a href="<?php echo Url::build('banquet_reservation',array('cmd'=>'1'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('banquet_reservation');?></a>
                    </td><?php }?>
                    <!--
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};BanquetReservationListForm.cmd.value='delete';BanquetReservationListForm.submit();"  class="button-medium-delete"><?php echo Portal::language('Delete');?></a></td><?php }?>
                    -->                    
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
                <form method="post" name="SearchBanquetReservationForm"> 
                <table>
                    <tr>
                      <td align="right" nowrap="nowrap"><?php echo Portal::language('guest_name');?></td>
                      <td>:</td>
                      <td nowrap="nowrap"><input  name="full_name" id="full_name" style="width:150px; height: 24px;" class="date-input" / type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                        <td align="right" nowrap><?php echo Portal::language('arrival_time');?></td>
                        <td nowrap>
                                <input  name="from_arrival_time" id="from_arrival_time" onchange="changevalue();" style="width:80px; height: 24px;" class="date-input" type ="text" value="<?php echo String::html_normalize(URL::get('from_arrival_time'));?>">&nbsp;<?php echo Portal::language('to');?>
                            <input  name="to_arrival_time" id="to_arrival_time" onchange="changefromday();" style="width:80px; height: 24px;" class="date-input" type ="text" value="<?php echo String::html_normalize(URL::get('to_arrival_time'));?>"></td>
                        <td nowrap>&nbsp;</td>
                        <td><select  name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value" style=" height: 24px;"><?php
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
                        <td><select  name="party_name" id="party_name" style=" height: 24px;"><?php
					if(isset($this->map['party_name_list']))
					{
						foreach($this->map['party_name_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('party_name',isset($this->map['party_name'])?$this->map['party_name']:''))
                    echo "<script>$('party_name').value = \"".addslashes(URL::get('party_name',isset($this->map['party_name'])?$this->map['party_name']:''))."\";</script>";
                    ?>
	
                            <?php echo '<option>'.$this->map['party_name'].'</option>'; ?>
                        </select></td>
                        <td nowrap><input class="w3-btn w3-gray" type="submit" value="<?php echo Portal::language('search');?>" style=" height: 24px; padding-top: 5px;" /></td>
                        <td>&nbsp;</td></td>
                    </tr>
                    <script>
                        function changevalue(){
                            var myfromdate = $('from_arrival_time').value.split("/");
                            var mytodate = $('to_arrival_time').value.split("/");
                            if(myfromdate[2] > mytodate[2]){
                                $('to_arrival_time').value =$('from_arrival_time').value;
                            }else{
                                if(myfromdate[1] > mytodate[1]){
                                    $('to_arrival_time').value =$('from_arrival_time').value;
                                }else{
                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                        $('to_arrival_time').value =$('from_arrival_time').value;
                                    }
                                }
                            }
                        }
                        function changefromday(){
                            var myfromdate = $('from_arrival_time').value.split("/");
                            var mytodate = $('to_arrival_time').value.split("/");
                            if(myfromdate[2] > mytodate[2]){
                                $('from_arrival_time').value= $('to_arrival_time').value;
                            }else{
                                if(myfromdate[1] > mytodate[1]){
                                    $('from_arrival_time').value = $('to_arrival_time').value;
                                }else{
                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                        $('from_arrival_time').value =$('to_arrival_time').value;
                                    }
                                }
                            }
                        }
                    </script>
                </table>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                </fieldset>
				<form name="BanquetReservationListForm" method="post">
				
                <div style="border:2px solid #FFFFFF;">
    				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
    					<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
    						<!--<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>-->
    						<th nowrap align="center" width="120px">
    							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.time'));?>">
    							<?php if(URL::get('order_by')=='party_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('time');?>/<?php echo Portal::language('time_in');?></a></th>
    						<th style="width: 70px; text-align: center;">
                                <?php echo Portal::language('mice');?>
                            </th>
                            <th style="width: 200px;" align="center" nowrap="nowrap"> 
    							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.full_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.full_name'));?>">
    							<?php if(URL::get('order_by')=='party_reservation.full_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('full_name');?></a></th>
    						<th style="width: 100px;" align="center" nowrap="nowrap"><?php echo Portal::language('party_type');?></th>
    						<th style="width: 90px;" align="center" nowrap="nowrap"><?php echo Portal::language('party_num_people');?></th>
                            <th style="width: 100px;" align="center" nowrap="nowrap"><?php echo Portal::language('deposit');?></th>
    						<th style="width: 120px;" align="center" nowrap="nowrap"><?php echo Portal::language('total');?></th>
    						<th style="width: 100px;" align="center" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.status'));?>">
							<?php if(URL::get('order_by')=='party_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('status');?></a>						</th>
                            <?php if(User::can_delete(false,ANY_CATEGORY)) {?><th width="1%" nowrap="nowrap">&nbsp;</th>
    						<th style="width: 100px;" style="text-align:center;"><?php echo Portal::language('creater');?></th>
                            <th style="text-align:center;"><?php echo Portal::language('note');?></th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
    						<th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
    						<?php }?>
    						<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
                            <th width="1%">&nbsp;</th>
    						<?php }?>
                        </tr>
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
    					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?> style="cursor:hand;<?php if($this->map['items']['current']['status']=='CHECKIN' || $this->map['items']['current']['status']=='BOOKED' || $this->map['items']['current']['status']=='RESERVATION'){?>font-weight:bold;<?php }?>">
    						<!--<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"></td>-->
    							<td nowrap align="left"><?php echo $this->map['items']['current']['arrival_date'];?></td>
                                <td style="text-align: center;"><?php if($this->map['items']['current']['mice_reservation_id']!=0){ ?><a href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['items']['current']['mice_reservation_id'];?>">MICE+<?php echo $this->map['items']['current']['mice_reservation_id'];?></a><?php } ?></td>
    							<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['full_name'];?></td>
    							<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['party_name'];?></td>
                                <td align="right" nowrap="nowrap">
                                    <?php 
                                        if($this->map['items']['current']['party_id'] == 3)
                                            echo $this->map['items']['current']['meeting_num_people'].' ';
                                        else
                                            echo $this->map['items']['current']['num_people'].' ';
                                    ?><?php echo Portal::language('person');?>
                                </td>
                                <td align="right" nowrap="nowrap"><?php echo System::display_number($this->map['items']['current']['deposit_1'] + $this->map['items']['current']['deposit_2'] + $this->map['items']['current']['deposit_3'] + $this->map['items']['current']['deposit_4']).' ';?></td>
    							<td align="right" nowrap="nowrap"><?php echo $this->map['items']['current']['total'];?></td>
    							<td align="left" nowrap><?php echo $this->map['items']['current']['status'];?></td>
   								<td nowrap="nowrap"><?php if(User::can_delete(false,ANY_CATEGORY) and ($this->map['items']['current']['status']!='CHECKOUT' and $this->map['items']['current']['status']!='CANCEL')) {?><a href="<?php echo Url::build_current(array('cmd'=>'cancel','id'=>$this->map['items']['current']['id'])); ?>" style="color:#FF0000;text-decoration:underline;"><?php echo Portal::language('cancel_this_order');?></a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
    							<td align="center" nowrap="nowrap"><?php echo $this->map['items']['current']['user_id'];?></td>
                                
                                
                                <!--<td nowrap="nowrap"><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>'view_contact','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/order.png" alt="<?php echo Portal::language('view_contact');?>" title="<?php echo Portal::language('view_contact');?>" width="12" height="12" border="0"/></a></td>-->
                                <?php if(User::can_edit(false,ANY_CATEGORY) && $this->map['items']['current']['status']!='CANCEL') {?> <td nowrap><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>$this->map['items']['current']['party_id'],'action'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><img align=center src="packages/hotel/skins/default/images/iosstyle/icon-note-no.png" title="<?php echo $this->map['items']['current']['note'];?>" width="12" height="12" border="0"></a></td><?php }else{?><td nowrap="nowrap">&nbsp;</td><?php }?>
                                
                                <td nowrap>
                                    <?php if($this->map['items']['current']['mice_reservation_id']==0){ ?>
                                    <a onclick="openWindowUrl('form.php?block_id=428&cmd=payment&id=<?php echo $this->map['items']['current']['id'];?>&type=BANQUET&total_amount=<?php echo System::calculate_number($this->map['items']['current']['total']);?>',Array('payment','payment_for',80,210,950,500));">
                                    <img src="packages/core/skins/default/images/buttons/order.png" alt="<?php echo Portal::language('payment');?>" title="<?php echo Portal::language('payment');?>" width="12" height="12" border="0"/></a>
                                    <?php } ?>
                                </td>
                                <?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap="nowrap" align="center"><a href="<?php echo Url::build_current(array('cmd'=>'view_contact','id'=>$this->map['items']['current']['id'], 'party_type'=>$this->map['items']['current']['party_id'])); ?>"><img src="packages/core/skins/default/images/buttons/copy.png" alt="<?php echo Portal::language('contract');?>" title="<?php echo Portal::language('contract');?>" width="15" height="15" border="0"></a></td> <?php } ?>
                                <?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap="nowrap" align="center"><a href="<?php echo Url::build_current(array('cmd'=>'detail','id'=>$this->map['items']['current']['id'], 'party_type'=>$this->map['items']['current']['party_id'])); ?>"><img src="packages/core/skins/default/images/buttons/information.png" alt="<?php echo Portal::language('information');?>" title="<?php echo Portal::language('information');?>" width="15" height="15" border="0"></a></td> <?php } ?>
    							<?php if(User::can_edit(false,ANY_CATEGORY) && $this->map['items']['current']['status']!='CANCEL') {?> <td nowrap><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>$this->map['items']['current']['party_id'],'action'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>" width="12" height="12" border="0"></a></td><?php }else{?><td nowrap="nowrap">&nbsp;</td><?php }?>
    							<?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap="nowrap" align="center"><a href="<?php echo Url::build_current(array('cmd'=>'print_order','id'=>$this->map['items']['current']['id'],'party_type'=>$this->map['items']['current']['party_id'])); ?>"><img src="packages/core/skins/default/images/printer.png" alt="<?php echo Portal::language('edit');?>" title="<?php echo Portal::language('edit');?>" width="15" height="15" border="0"></a></td> <?php } ?>
                                <?php if(User::can_delete(false,ANY_CATEGORY)) {?><td nowrap="nowrap"><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>'delete','id'=>$this->map['items']['current']['id'], 'party_type'=>$this->map['items']['current']['party_id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>" width="12" height="12" border="0"></a></td>
    							<?php } ?></tr>
    					<?php }}unset($this->map['items']['current']);} ?>
    				</table>
                </div>
                <?php echo $this->map['paging'];?>
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
</script>