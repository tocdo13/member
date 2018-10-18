<style>
	.back-button{float:right;}
	@media print{
		.back-button{
			display:none;
		}
	}
</style>
<script>
function handleKeyPress(evt) {
	var nbr;
	var nbr = (window.event)?event.keyCode:evt.which;
	if(nbr==27){
		window.location = '<?php echo Url::build_current(array('status','resolve','no_checkin','portal_id','booking_code'));?>';
	}
	return true;
}
document.onkeydown= handleKeyPress;
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
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%" align="center">
					<div class="back-button"><input type="button" value="<?php echo Portal::language('back');?>" onclick="window.location = '<?php echo Url::build_current(array('status','resolve','no_checkin','portal_id','booking_code'));?>'" /></div>
					<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
					<tr valign="top">
					  <td align="left" width="30%"><strong><?php echo HOTEL_NAME;?></strong><br />
					<?php echo HOTEL_ADDRESS;?></strong></td>
						<td width="40%" align="center" valign="middle"><h3 class="report-title"><?php echo $this->map['title'];?></h3>
							<?php echo Portal::language('from_date');?> <?php echo $this->map['from_date'];?>
							<?php echo Portal::language('to_date');?> <?php echo $this->map['to_date'];?><br />
							<br />
                            <?php echo Portal::language('total');?>: <?php echo ($this->map['total']);?> <?php echo Portal::language('room');?>,
							<?php 
				if((Url::get('status')))
				{?>
								<?php echo Portal::language('status');?>:&nbsp;<?php echo Url::get('status');?>
							
				<?php
				}
				?>
					  </td>
						<td width="30%" align="center" valign="middle" nowrap>
						<strong><?php echo Portal::language('template_code');?></strong></td>
					</tr>
					</table><br />
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='traveller_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('payment_traveller');?>								</a>							</th>
							<th align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('room');?></a></th><th nowrap align="right">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.price' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.price'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.price') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('price');?> <span style="font-size:10px;">(<?php echo HOTEL_CURRENCY;?>)</span></a>
							</th><th nowrap align="center" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.adult' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.adult'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.adult') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('num_people');?>								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.arrival_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.arrival_time'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.arrival_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('arrival_time');?>								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.departure_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.departure_time'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.departure_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('departure_time');?>								</a>
							</th>
							<th nowrap align="center" ><?php echo Portal::language('real_status');?></th>
							<th align="left"> <?php echo Portal::language('create_user');?> </th>
							<th align="left"> <?php echo Portal::language('create_time');?> </th>
							<th align="left"><?php echo Portal::language('booked_user');?></th>
							<th align="left"><?php echo Portal::language('checked_in_user');?></th>
							<th align="left"> <?php echo Portal::language('lastest_edited_user');?> </th>
							<th align="left"><?php echo Portal::language('lastest_edited_time');?></th>
							<?php if(User::can_add(false,ANY_CATEGORY))
							{
							?><?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><?php
							}
							?></tr>
						<?php $temp = '';?>
                        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
                        <?php if($temp!=$this->map['items']['current']['reservation_id']){$temp = $this->map['items']['current']['reservation_id'];?>
                        <tr>
	                        <td colspan="13" class="category-group">
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
						<tr id="ReservationRoom_tr_<?php echo $this->map['items']['current']['reservation_id'];?>" <?php echo 'class="'.(($this->map['items']['current']['i']%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150" align="left">
                            	<?php echo $this->map['items']['current']['i'];?> / <?php echo Portal::language('invoice_number');?>: <?php echo $this->map['items']['current']['id'];?>
								<?php if(isset($this->map['items']['current']['travellers']) and is_array($this->map['items']['current']['travellers'])){ foreach($this->map['items']['current']['travellers'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['travellers']['current'] = &$item3;?>
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['travellers']['current']['id']));?>"><?php echo $this->map['items']['current']['travellers']['current']['full_name'];?></a></div>
								<?php }}unset($this->map['items']['current']['travellers']['current']);} ?>
							</td>
							<td class="reservation-list-item"align="left">
								<div style="float:left;width:150px;font-size:11px;"><strong><?php echo $this->map['items']['current']['room_id'];?></strong> - <?php echo Portal::language('room_level');?> <strong><?php echo $this->map['items']['current']['room_level'];?></strong></div>
								<?php 
				if(($this->map['items']['current']['note']))
				{?>
								<div class="note"><span class="note">*<?php echo Portal::language('note');?>: <?php echo $this->map['items']['current']['note'];?></span></div>
								
				<?php
				}
				?>
							</td>
							<td class="reservation-list-item" nowrap align="right"><?php echo $this->map['items']['current']['price'];?></td>
							<td class="reservation-list-item" nowrap>
								(<?php echo $this->map['items']['current']['adult'];?>)<img src="packages/core/skins/default/images/buttons/adult.png" width="8"><?php 
				if(($this->map['items']['current']['child']))
				{?>+ (<?php echo $this->map['items']['current']['child'];?>)<img src="packages/core/skins/default/images/buttons/child.png" width="8">
				<?php
				}
				?></td>
							<td class="reservation-list-item" nowrap align="left" title="<?php echo $this->map['items']['current']['arrival_time'];?> <?php echo $this->map['items']['current']['time_in'];?>">
								<?php echo $this->map['items']['current']['time_in'];?></td>
							<td  class="reservation-list-item"nowrap align="left" title="<?php echo $this->map['items']['current']['departure_time'];?> <?php echo $this->map['items']['current']['time_out'];?>">
							  <?php echo $this->map['items']['current']['time_out'];?><?php 
				if(($this->map['items']['current']['verify_dayuse']))
				{?><br />(<span style="font-weight:bold;"><?php echo $this->map['items']['current']['verify_dayuse_label'];?></span>)
				<?php
				}
				?></td>
							<td class="reservation-list-item" nowrap align="center">
								<?php echo $this->map['items']['current']['status'];?>							</td>
							<td class="reservation-list-item"><?php echo $this->map['items']['current']['user_id'];?></td>
							<td class="reservation-list-item"><?php echo date('d/m/Y H:i\'',$this->map['items']['current']['time']);?></td>
							<td align="center" nowrap="nowrap" class="reservation-list-item"> <?php echo $this->map['items']['current']['booked_user_id'];?> </td>
							<td align="center" nowrap="nowrap" class="reservation-list-item"> <?php echo $this->map['items']['current']['checked_in_user_id'];?> </td>
							<td class="reservation-list-item"><?php echo $this->map['items']['current']['lastest_edited_user_id'];?></td>
							<td class="reservation-list-item"><?php echo $this->map['items']['current']['lastest_edited_time']?date('d/m/Y H:i\'',$this->map['items']['current']['lastest_edited_time']):'';?></td>
						</tr>
						<?php if($this->map['reservation_arr'][$this->map['items']['current']['reservation_id']] == $this->map['items']['current']['count']){?>
						<tr bgcolor="#EFEFEF">
						  <td align="right" class="reservation-list-item"><strong><?php echo Portal::language('total_room');?></strong></td>
						  <td colspan="13" align="left" class="reservation-list-item"><strong><?php echo $this->map['reservation_arr'][$this->map['items']['current']['reservation_id']];?></strong></td>
					    </tr>
					  	<?php }?>
						<?php }}unset($this->map['items']['current']);} ?>
				  </table>
                  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
				</td>
			</tr>
			</table>
			<?php echo $this->map['paging'];?>
    </td>
  </tr>
</table>
<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td align="center"> </td>
	<td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?><?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr valign="top">
	<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
	<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
	<td width="33%" align="center"><?php echo Portal::language('director');?></td>
</tr>
</table>