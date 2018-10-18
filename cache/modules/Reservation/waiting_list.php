<style type="text/css">
.simple-layout-middle{width:100%;}
</style>
<script>
	ReservationRoom_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['reservation_id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	}
</script>

<table cellspacing="0" width="100%">
	<tr valign="top">
		<td nowrap>&nbsp;</td>
		<td width="100%">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<?php if(isset($this->map['years']) and is_array($this->map['years'])){ foreach($this->map['years'] as $key2=>&$item2){if($key2!='current'){$this->map['years']['current'] = &$item2;?>
					<td nowrap><a class="datetime_button<?php echo $this->map['years']['current']['selected'];?>" href="<?php echo URL::build_current(array('month'=>'1-12','day'=>'1-31','cmd','room_id','customer_name','status','room_level_id','occupied','resolve'));?>&year=<?php echo $this->map['years']['current']['year'];?>"><?php echo $this->map['years']['current']['year'];?></a></td>
					<?php }}unset($this->map['years']['current']);} ?>
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<?php if(isset($this->map['months']) and is_array($this->map['months'])){ foreach($this->map['months'] as $key3=>&$item3){if($key3!='current'){$this->map['months']['current'] = &$item3;?>
					<td nowrap><a class="month_button<?php echo $this->map['months']['current']['selected'];?>" href="<?php echo URL::build_current(array('year','day'=>'1-31','cmd','room_id','customer_name','status','room_level_id','occupied','resolve'));?>&month=<?php echo $this->map['months']['current']['month'];?>" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','status','object_id','room_level_id','day'=>'1-31'));?>&month=','<?php echo $this->map['month'];?>','<?php echo $this->map['months']['current']['month'];?>'); return false;}"><?php echo $this->map['months']['current']['month'];?></a></td>
					<?php }}unset($this->map['months']['current']);} ?>
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<?php if(isset($this->map['days']) and is_array($this->map['days'])){ foreach($this->map['days'] as $key4=>&$item4){if($key4!='current'){$this->map['days']['current'] = &$item4;?>
					<td nowrap><a class="day_button<?php echo $this->map['days']['current']['selected'];?>" href="<?php echo URL::build_current(array('month'=>$this->map['month'],'year'=>$this->map['year'],'cmd','room_id','customer_name','status','room_level_id','occupied','resolve'));?>&day=<?php echo $this->map['days']['current']['day'];?>" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','status','object_id','room_level_id','month'=>$this->map['month']));?>&day=','<?php echo $this->map['day'];?>','<?php echo $this->map['days']['current']['day'];?>'); return false;}"><?php echo $this->map['days']['current']['day'];?></a></td>
					<?php }}unset($this->map['days']['current']);} ?>
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%"><br />
		            <form method="post" name="SearchReservationRoomForm">
					<fieldset>
                    <legend class="w3-text-indigo" style="text-transform: uppercase; font-size: 16px;"><?php echo Portal::language('filter_by');?> <?php echo Portal::language('hotel');?> <select  name="portal_id" id="portal_id" style="font-size: 14px !important; height: 26px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></legend>
					<input type="hidden" name="page_no" value="1" />
                    <!--<input  name="status" id="status" type ="text" value="<?php echo String::html_normalize(URL::get('status'));?>"> -->
					<table>
						<tr>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('room');?></td>
						  <td></td>
						  <td nowrap="nowrap"><input  name="room_id" id="room_id" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('room_id'));?>"></td>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('customer');?> / <?php echo Portal::language('company');?> </td>
						  <td></td>
						  <td nowrap="nowrap"><input  name="customer_name" id="customer_name" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td nowrap="nowrap" align="right"><?php echo Portal::language('traveller_name');?></td>
						  <td nowrap="nowrap"></td>
						  <td align="right" nowrap="nowrap"><input  name="traveller_name" id="traveller_name" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('traveller_name'));?>"></td>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('rate');?></td>
						  <td>&nbsp;</td>
						  <td align="right"><select  name="price_operator" id="price_operator" style="width:30px;font-weight:bold; height: 24px;"><?php
					if(isset($this->map['price_operator_list']))
					{
						foreach($this->map['price_operator_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('price_operator',isset($this->map['price_operator'])?$this->map['price_operator']:''))
                    echo "<script>$('price_operator').value = \"".addslashes(URL::get('price_operator',isset($this->map['price_operator'])?$this->map['price_operator']:''))."\";</script>";
                    ?>
	</select><input  name="price" id="price" style="width:70px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('price'));?>"></td>
						  <td align="right" rowspan="2"><input type="submit" name="search" value="<?php echo Portal::language('search');?>" class="w3-btn w3-blue" style="margin-left: 30px;"/></td>
                          <td align="right" rowspan="2" nowrap><input type="submit" name="view_printable_list" value="<?php echo Portal::language('view_printable_list');?>" class="w3-btn w3-gray" style="margin-left: 10px;" /></td>
					    </tr>
						<tr>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('booking_code');?></td>
						  <td></td>
						  <td nowrap="nowrap"><input  name="booking_code" id="booking_code" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('rcode');?></td>
						  <td></td>
						  <td nowrap="nowrap"><input  name="code" id="code" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>"></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('nationality');?></td>
						  <td></td>
						  <td nowrap="nowrap"><select  name="nationality_id" id="nationality_id" style="width:100px; height: 24px;"><?php
					if(isset($this->map['nationality_id_list']))
					{
						foreach($this->map['nationality_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))
                    echo "<script>$('nationality_id').value = \"".addslashes(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))."\";</script>";
                    ?>
	
						    </select></td>
						  <td align="right" nowrap="nowrap"><?php echo Portal::language('note');?></td>
						  <td nowrap></td>
						  <td align="right" nowrap><input  name="note" id="note" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('note'));?>"></td>
						  
				      </tr>
					</table>
					<input type="submit" style="width:0px;background-color:inherit;border:0 solid white;display:none">
                    </fieldset>
					<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    <form name="ReservationRoomListForm" method="post">
                    <input  name="cmd" id="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
					<div>
					<table width="100%">
						<tr>
                        	<td width="100%">
							<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                                <tr>
                                    <td class="" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-check-square-o w3-text-orange" style="font-size: 30px;"></i> <?php echo Portal::language('booking_unassign_room');?> <?php if($this->map['id']!=''){?><a href="<?php echo Url::build('reservation',array('cmd'=>'asign_room','id'=>$this->map['id']));?>"><input  name="assign_all" value="Assign all" / type ="button" value="<?php echo String::html_normalize(URL::get('assign_all'));?>"></a><?php }?></td>
                                </tr>
                            </table>
                            </td>
					</tr></table>
					</div>
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="w3-light-gray w3-border" style="text-transform: uppercase; height: 24px;">
							<th nowrap align="center" style="width: 30px;"><?php echo Portal::language('code');?></th>
							<th align="center" style="width: 150px;"><?php echo Portal::language('room_level');?></th>
                            <th nowrap align="center" style="width: 80px;"><?php echo Portal::language('arrival_time');?></th>
                            <th nowrap align="center" style="width: 80px;"><?php echo Portal::language('departure_time');?></th>
                            <th nowrap align="center" style="width: 100px;"><?php echo Portal::language('adult');?>-<?php echo Portal::language('child');?> </th>
                            <th nowrap align="center" style="width: 250px;"><?php echo Portal::language('contact_person_name');?> - <?php echo Portal::language('contact_person_phone');?></th>
                            <th nowrap align="center" style="width: 100px;"><?php echo Portal::language('status');?></th>
							<th align="center" style="width: 150px;"> <?php echo Portal::language('create_time');?> </th>
							<th align="center" style="width: 100px;"><?php echo Portal::language('user_create');?></th>
                            <th align="center" style="width: 100px;"><?php echo Portal::language('deposit_total');?></th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><th style="width: 100px; text-align: center;"><?php echo Portal::language('deposit');?></th>
                            	<th style="width: 100px; text-align: center;"><?php echo Portal::language('assign');?></th>
							<?php
							}?>
							<?php if(User::can_add(false,ANY_CATEGORY))
							{
							?><th>&nbsp;</th>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th>&nbsp;</th>
							<?php
							}
							?></tr>
						<?php $temp = '';?>
                        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current'] = &$item5;?>
                        <?php if($temp!=$this->map['items']['current']['reservation_id']){$temp = $this->map['items']['current']['reservation_id'];?>
                        <tr>
	                        <td colspan="15" class="category-group">
							[<?php echo Portal::language('rcode');?>:  <?php echo $this->map['items']['current']['reservation_id'];?>] | <span style="color:#0066FF;">[<?php echo Portal::language('booking_code');?>: <?php echo $this->map['items']['current']['booking_code'];?>]</span> | <?php echo Portal::language('tour');?>: <?php echo $this->map['items']['current']['tour_name'];?> | <?php echo Portal::language('customer');?>: <?php echo $this->map['items']['current']['customer_name'];?>
							<?php 
				if(($this->map['items']['current']['group_note']))
				{?>
								<div class="note" style="text-transform:none;"><span class="note">*<?php echo Portal::language('note');?>: <?php echo $this->map['items']['current']['group_note'];?></span></div>
								
				<?php
				}
				?>
							</td>
                        </tr>
                        <?php }?>
						<tr valign="top" id="ReservationRoom_tr_<?php echo $this->map['items']['current']['reservation_id'];?>" <?php echo 'class="'.(($this->map['items']['current']['i']%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150" align="left">
                            	<?php echo $this->map['items']['current']['i'];?> / <?php echo Portal::language('invoice_number');?>: <?php echo $this->map['items']['current']['id'];?>
								<?php if(isset($this->map['items']['current']['travellers']) and is_array($this->map['items']['current']['travellers'])){ foreach($this->map['items']['current']['travellers'] as $key6=>&$item6){if($key6!='current'){$this->map['items']['current']['travellers']['current'] = &$item6;?>
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['travellers']['current']['id']));?>"><?php echo $this->map['items']['current']['travellers']['current']['full_name'];?></a></div>
								<?php }}unset($this->map['items']['current']['travellers']['current']);} ?></td>
							<td class="reservation-list-item"align="left">
								<div style="float:left;width:150px;font-size:11px;"><strong><?php echo $this->map['items']['current']['room_level'];?></strong><br />
							  (<?php echo $this->map['items']['current']['portal_name'];?>)</div>
							  <?php 
				if(($this->map['items']['current']['note']))
				{?>
								<div class="note"><span class="note">*<?php echo Portal::language('note');?>: <?php echo $this->map['items']['current']['note'];?></span></div>
								
				<?php
				}
				?>
							  </td>
							<td class="reservation-list-item" nowrap align="center"><?php echo date('H\h:i',$this->map['items']['current']['time_in']);?><br/><?php echo date('d/m/Y',$this->map['items']['current']['time_in']);?></td>
                            <td class="reservation-list-item" nowrap align="center"><?php echo date('H\h:i',$this->map['items']['current']['time_out']);?><br/><?php echo date('d/m/Y',$this->map['items']['current']['time_out']);?></td>
                            <td class="reservation-list-item" nowrap>
								(<?php echo $this->map['items']['current']['sum_adult'];?>)<i class="fa fa-male" style="font-size: 16px;"></i><?php 
				if(($this->map['items']['current']['sum_child']))
				{?>+ (<?php echo $this->map['items']['current']['sum_child'];?>)<i class="fa fa-child" style="font-size: 16px;"></i>
				<?php
				}
				?>
						  </td>
							<td class="reservation-list-item" nowrap align="left" title="<?php echo $this->map['items']['current']['arrival_time'];?> <?php echo $this->map['items']['current']['time_in'];?>">
                            <?php if(isset($this->map['items']['current']['contacts']) and is_array($this->map['items']['current']['contacts'])){ foreach($this->map['items']['current']['contacts'] as $key7=>&$item7){if($key7!='current'){$this->map['items']['current']['contacts']['current'] = &$item7;?>
								<?php echo $this->map['items']['current']['contacts']['current']['contact_name'];?> - <?php echo $this->map['items']['current']['contacts']['current']['contact_phone'];?><br />
                            <?php }}unset($this->map['items']['current']['contacts']['current']);} ?>
							<?php 
				if(($this->map['items']['current']['verify_dayuse']))
				{?><span style="font-weight:bold;"><?php echo $this->map['items']['current']['verify_dayuse_label'];?></span>
				<?php
				}
				?></td>
							<td class="reservation-list-item" nowrap align="center">
								<?php echo $this->map['items']['current']['status'];?>							</td>
							<td class="reservation-list-item"><?php echo date('d/m/Y H:i\'',$this->map['items']['current']['time']);?></td>
							<td align="center" nowrap="nowrap" class="reservation-list-item"><?php echo $this->map['items']['current']['booked_user_id'];?> </td>
                            <td align="center" nowrap="nowrap" class="reservation-list-item"><?php echo System::display_number($this->map['items']['current']['deposit']);?> </td>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><td class="reservation-list-item" nowrap="nowrap" valign="top" style="text-align: center;"><a href="#" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=428&cmd=deposit&id=<?php echo $this->map['items']['current']['id'];?>&type=RESERVATION&customer_id=<?php echo $this->map['items']['current']['customer_id'];?>&portal_id=<?php echo PORTAL_ID;?>',Array(<?php echo $this->map['items']['current']['id'];?>,'<?php echo Portal::language('deposit');?>','80','210','1000','500'));"><i class="fa fa-money w3-text-green" style="font-size: 30px;"></i> </a> </td>
                            <td class="reservation-list-item" nowrap="nowrap" valign="top" style="text-align: center;"><a href="<?php echo Url::build('reservation',array('cmd'=>'asign_room','id'=>$this->map['items']['current']['id']));?>"><input class="w3-button w3-gray w3-hover-orange" name="assign" type="button" value="Assign" /></a></td>
							<?php } ?>
							<td class="reservation-list-item" nowrap width="30" style="text-align: center;">
							<?php
							if( User::can_admin(false,ANY_CATEGORY)or (USER::can_add(false,ANY_CATEGORY) and ($this->map['items']['current']['status'] == 'BOOKED' ))or (User::can_edit(false,ANY_CATEGORY) and ($this->map['items']['current']['status'] == 'BOOKED' or $this->map['items']['current']['status'] == 'IN'))){?>
								&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'])); ?>&r_r_id=<?php echo $this->map['items']['current']['id'];?>"><i class="fa fa-edit w3-text-orange" style="font-size: 18px; margin-top: 5px;"></i></a>
							<?php
							}
							?></td>
							<td class="reservation-list-item" nowrap width="30" style="text-align: center;">
							<?php
							if(
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and $this->map['items']['current']['status'] == 'BOOKED')
							)
							{
							?>&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>$this->map['items']['current']['reservation_id'])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px; margin-top: 5px;"></i></a>
							<?php
							}
							?></td>
						</tr>
						<?php }}unset($this->map['items']['current']);} ?>
				  </table>
                  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
				</td>
			</tr>
			</table>
    </td>
  </tr>
</table>

<script type="text/javascript">
//luu nguyen giap add closed button
function close_window_fun(){
    location.reload();
    jQuery(".window-container").fadeOut();
    //console.log('aaaaa');
}
</script>