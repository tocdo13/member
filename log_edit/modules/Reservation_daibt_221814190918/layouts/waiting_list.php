<style type="text/css">
.simple-layout-middle{width:100%;}
</style>
<script>
	ReservationRoom_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.reservation_id|]]'
<!--/LIST:items-->
	}
</script>

<table cellspacing="0" width="100%">
	<tr valign="top">
		<td nowrap>&nbsp;</td>
		<td width="100%">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:years-->
					<td nowrap><a class="datetime_button[[|years.selected|]]" href="<?php echo URL::build_current(array('month'=>'1-12','day'=>'1-31','cmd','room_id','customer_name','status','room_level_id','occupied','resolve'));?>&year=[[|years.year|]]">[[|years.year|]]</a></td>
					<!--/LIST:years-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:months-->
					<td nowrap><a class="month_button[[|months.selected|]]" href="<?php echo URL::build_current(array('year','day'=>'1-31','cmd','room_id','customer_name','status','room_level_id','occupied','resolve'));?>&month=[[|months.month|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','status','object_id','room_level_id','day'=>'1-31'));?>&month=','[[|month|]]','[[|months.month|]]'); return false;}">[[|months.month|]]</a></td>
					<!--/LIST:months-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:days-->
					<td nowrap><a class="day_button[[|days.selected|]]" href="<?php echo URL::build_current(array('month'=>[[=month=]],'year'=>[[=year=]],'cmd','room_id','customer_name','status','room_level_id','occupied','resolve'));?>&day=[[|days.day|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','status','object_id','room_level_id','month'=>[[=month=]]));?>&day=','[[|day|]]','[[|days.day|]]'); return false;}">[[|days.day|]]</a></td>
					<!--/LIST:days-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%"><br />
		            <form method="post" name="SearchReservationRoomForm">
					<fieldset>
                    <legend class="w3-text-indigo" style="text-transform: uppercase; font-size: 16px;">[[.filter_by.]] [[.hotel.]] <select name="portal_id" id="portal_id" style="font-size: 14px !important; height: 26px;"></select></legend>
					<input type="hidden" name="page_no" value="1" />
                    <!--<input name="status" type="text" id="status"> -->
					<table>
						<tr>
						  <td align="right" nowrap="nowrap">[[.room.]]</td>
						  <td></td>
						  <td nowrap="nowrap"><input name="room_id" type="text" id="room_id" style="width:100px; height: 24px;" /></td>
						  <td align="right" nowrap="nowrap">[[.customer.]] / [[.company.]] </td>
						  <td></td>
						  <td nowrap="nowrap"><input name="customer_name" type="text" id="customer_name" style="width:100px; height: 24px;" /></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td nowrap="nowrap" align="right">[[.traveller_name.]]</td>
						  <td nowrap="nowrap"></td>
						  <td align="right" nowrap="nowrap"><input name="traveller_name" type="text" id="traveller_name" style="width:100px; height: 24px;" /></td>
						  <td align="right" nowrap="nowrap">[[.rate.]]</td>
						  <td>&nbsp;</td>
						  <td align="right"><select name="price_operator" id="price_operator" style="width:30px;font-weight:bold; height: 24px;"></select><input name="price" type="text" id="price" style="width:70px; height: 24px;" /></td>
						  <td align="right" rowspan="2"><input type="submit" name="search" value="[[.search.]]" class="w3-btn w3-blue" style="margin-left: 30px;"/></td>
                          <td align="right" rowspan="2" nowrap><input type="submit" name="view_printable_list" value="[[.view_printable_list.]]" class="w3-btn w3-gray" style="margin-left: 10px;" /></td>
					    </tr>
						<tr>
						  <td align="right" nowrap="nowrap">[[.booking_code.]]</td>
						  <td></td>
						  <td nowrap="nowrap"><input name="booking_code" type="text" id="booking_code" style="width:100px; height: 24px;" /></td>
						  <td align="right" nowrap="nowrap">[[.rcode.]]</td>
						  <td></td>
						  <td nowrap="nowrap"><input name="code" type="text" id="code" style="width:100px; height: 24px;" /></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td align="right" nowrap="nowrap">[[.nationality.]]</td>
						  <td></td>
						  <td nowrap="nowrap"><select name="nationality_id" id="nationality_id" style="width:100px; height: 24px;">
						    </select></td>
						  <td align="right" nowrap="nowrap">[[.note.]]</td>
						  <td nowrap></td>
						  <td align="right" nowrap><input name="note" type="text" id="note" style="width:100px; height: 24px;" /></td>
						  
				      </tr>
					</table>
					<input type="submit" style="width:0px;background-color:inherit;border:0 solid white;display:none">
                    </fieldset>
					</form>
                    <form name="ReservationRoomListForm" method="post">
                    <input name="cmd" type="hidden" id="cmd" value="" />
					<div>
					<table width="100%">
						<tr>
                        	<td width="100%">
							<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                                <tr>
                                    <td class="" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-check-square-o w3-text-orange" style="font-size: 30px;"></i> [[.booking_unassign_room.]] <?php if([[=id=]]!=''){?><a href="<?php echo Url::build('reservation',array('cmd'=>'asign_room','id'=>[[=id=]]));?>"><input name="assign_all" type="button" value="Assign all" /></a><?php }?></td>
                                </tr>
                            </table>
                            </td>
					</tr></table>
					</div>
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="w3-light-gray w3-border" style="text-transform: uppercase; height: 24px;">
							<th nowrap align="center" style="width: 30px;">[[.code.]]</th>
							<th align="center" style="width: 150px;">[[.room_level.]]</th>
                            <th nowrap align="center" style="width: 80px;">[[.arrival_time.]]</th>
                            <th nowrap align="center" style="width: 80px;">[[.departure_time.]]</th>
                            <th nowrap align="center" style="width: 100px;">[[.adult.]]-[[.child.]] </th>
                            <th nowrap align="center" style="width: 250px;">[[.contact_person_name.]] - [[.contact_person_phone.]]</th>
                            <th nowrap align="center" style="width: 100px;">[[.status.]]</th>
							<th align="center" style="width: 150px;"> [[.create_time.]] </th>
							<th align="center" style="width: 100px;">[[.user_create.]]</th>
                            <th align="center" style="width: 100px;">[[.deposit_total.]]</th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><th style="width: 100px; text-align: center;">[[.deposit.]]</th>
                            	<th style="width: 100px; text-align: center;">[[.assign.]]</th>
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
                        <!--LIST:items-->
                        <?php if($temp!=[[=items.reservation_id=]]){$temp = [[=items.reservation_id=]];?>
                        <tr>
	                        <td colspan="15" class="category-group">
							[[[.rcode.]]:  [[|items.reservation_id|]]] | <span style="color:#0066FF;">[[[.booking_code.]]: [[|items.booking_code|]]]</span> | [[.tour.]]: [[|items.tour_name|]] | [[.customer.]]: [[|items.customer_name|]]
							<!--IF:cond([[=items.group_note=]])-->
								<div class="note" style="text-transform:none;"><span class="note">*[[.note.]]: [[|items.group_note|]]</span></div>
								<!--/IF:cond-->
							</td>
                        </tr>
                        <?php }?>
						<tr valign="top" id="ReservationRoom_tr_[[|items.reservation_id|]]" <?php echo 'class="'.(([[=items.i=]]%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150" align="left">
                            	[[|items.i|]] / [[.invoice_number.]]: [[|items.id|]]
								<!--LIST:items.travellers-->
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>[[=items.travellers.id=]]));?>">[[|items.travellers.full_name|]]</a></div>
								<!--/LIST:items.travellers--></td>
							<td class="reservation-list-item"align="left">
								<div style="float:left;width:150px;font-size:11px;"><strong>[[|items.room_level|]]</strong><br />
							  ([[|items.portal_name|]])</div>
							  <!--IF:cond([[=items.note=]])-->
								<div class="note"><span class="note">*[[.note.]]: [[|items.note|]]</span></div>
								<!--/IF:cond-->
							  </td>
							<td class="reservation-list-item" nowrap align="center"><?php echo date('H\h:i',[[=items.time_in=]]);?><br/><?php echo date('d/m/Y',[[=items.time_in=]]);?></td>
                            <td class="reservation-list-item" nowrap align="center"><?php echo date('H\h:i',[[=items.time_out=]]);?><br/><?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
                            <td class="reservation-list-item" nowrap>
								([[|items.sum_adult|]])<i class="fa fa-male" style="font-size: 16px;"></i><!--IF:cond([[=items.sum_child=]])-->+ ([[|items.sum_child|]])<i class="fa fa-child" style="font-size: 16px;"></i><!--/IF:cond-->
						  </td>
							<td class="reservation-list-item" nowrap align="left" title="[[|items.arrival_time|]] [[|items.time_in|]]">
                            <!--LIST:items.contacts-->
								[[|items.contacts.contact_name|]] - [[|items.contacts.contact_phone|]]<br />
                            <!--/LIST:items.contacts-->
							<!--IF:vd_cond([[=items.verify_dayuse=]])--><span style="font-weight:bold;">[[|items.verify_dayuse_label|]]</span><!--/IF:vd_cond--></td>
							<td class="reservation-list-item" nowrap align="center">
								[[|items.status|]]							</td>
							<td class="reservation-list-item"><?php echo date('d/m/Y H:i\'',[[=items.time=]]);?></td>
							<td align="center" nowrap="nowrap" class="reservation-list-item">[[|items.booked_user_id|]] </td>
                            <td align="center" nowrap="nowrap" class="reservation-list-item"><?php echo System::display_number([[=items.deposit=]]);?> </td>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><td class="reservation-list-item" nowrap="nowrap" valign="top" style="text-align: center;"><a href="#" onclick="openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=428&cmd=deposit&id=[[|items.id|]]&type=RESERVATION&customer_id=[[|items.customer_id|]]&portal_id=<?php echo PORTAL_ID;?>',Array([[|items.id|]],'[[.deposit.]]','80','210','1000','500'));"><i class="fa fa-money w3-text-green" style="font-size: 30px;"></i> </a> </td>
                            <td class="reservation-list-item" nowrap="nowrap" valign="top" style="text-align: center;"><a href="<?php echo Url::build('reservation',array('cmd'=>'asign_room','id'=>[[=items.id=]]));?>"><input class="w3-button w3-gray w3-hover-orange" name="assign" type="button" value="Assign" /></a></td>
							<?php } ?>
							<td class="reservation-list-item" nowrap width="30" style="text-align: center;">
							<?php
							if( User::can_admin(false,ANY_CATEGORY)or (USER::can_add(false,ANY_CATEGORY) and ([[=items.status=]] == 'BOOKED' ))or (User::can_edit(false,ANY_CATEGORY) and ([[=items.status=]] == 'BOOKED' or [[=items.status=]] == 'IN'))){?>
								&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>&r_r_id=[[|items.id|]]"><i class="fa fa-edit w3-text-orange" style="font-size: 18px; margin-top: 5px;"></i></a>
							<?php
							}
							?></td>
							<td class="reservation-list-item" nowrap width="30" style="text-align: center;">
							<?php
							if(
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and [[=items.status=]] == 'BOOKED')
							)
							{
							?>&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>[[=items.reservation_id=]])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px; margin-top: 5px;"></i></a>
							<?php
							}
							?></td>
						</tr>
						<!--/LIST:items-->
				  </table>
                  </form>
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