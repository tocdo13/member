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
					<td nowrap><a class="datetime_button[[|years.selected|]]" href="<?php echo URL::build_current(array('month'=>'1-12','day'=>'1-31','cmd','room_id','customer_name','status'));?>&year=[[|years.year|]]">[[|years.year|]]</a></td>
					<!--/LIST:years-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:months-->
					<td nowrap><a class="month_button[[|months.selected|]]" href="<?php echo URL::build_current(array('year','day'=>'1-31','cmd','room_id','customer_name','status'));?>&month=[[|months.month|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','object_id','day'=>'1-31'));?>&month=','[[|month|]]','[[|months.month|]]'); return false;}">[[|months.month|]]</a></td>
					<!--/LIST:months-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:days-->
					<td nowrap><a class="day_button[[|days.selected|]]" href="<?php echo URL::build_current(array('month'=>[[=month=]],'year'=>[[=year=]],'cmd','room_id','customer_name','status'));?>&day=[[|days.day|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','object_id','month'=>[[=month=]]));?>&day=','[[|day|]]','[[|days.day|]]'); return false;}">[[|days.day|]]</a></td>
					<!--/LIST:days-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<fieldset>
                    <legend class="title">[[.filter_by.]]</legend>
                    <form method="post" name="SearchReservationRoomForm">
					<input type="hidden" name="page_no" value="1" />
					<table>
						<tr>
						  <td align="right" nowrap="nowrap">[[.room_id.]]</td>
						  <td>:</td>
						  <td nowrap="nowrap"><input name="room_id" type="text" id="room_id" style="width:100px" /></td>
						  <td align="right" nowrap="nowrap">[[.customer.]] / [[.company.]] </td>
						  <td>:</td>
						  <td nowrap="nowrap"><input name="customer_name" type="text" id="customer_name" style="width:100px" /></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td nowrap="nowrap" align="right">[[.traveller_name.]]</td>
						  <td nowrap="nowrap">:</td>
						  <td align="right" nowrap="nowrap"><input name="traveller_name" type="text" id="traveller_name" style="width:100px" /></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td>&nbsp;</td>
						  <td align="right"><input type="submit" name="search" value="[[.search.]]" class="button-medium-search" /></td>
					    </tr>
						<tr>
						  <td align="right" nowrap="nowrap">[[.booking_code.]]</td>
						  <td>:</td>
						  <td nowrap="nowrap"><input name="booking_code" type="text" id="booking_code" style="width:100px" /></td>
						  <td align="right" nowrap="nowrap">[[.rcode.]]</td>
						  <td>:</td>
						  <td nowrap="nowrap"><input name="code" type="text" id="code" style="width:100px" /></td>
						  <td align="right" nowrap="nowrap">&nbsp;</td>
						  <td align="right" nowrap="nowrap">[[.nationality.]]</td>
						  <td>:</td>
						  <td nowrap="nowrap"><select name="nationality_id" id="nationality_id" style="width:100px">
						    </select></td>
						  <td align="right" nowrap="nowrap">[[.note.]]</td>
						  <td nowrap>:</td>
						  <td align="right" nowrap><input name="customer_name23" type="text" id="note" style="width:100px" /></td>
					  </tr>
					</table>
					<input type="submit" style="width:0px;background-color:inherit;border:0 solid white;display:none">
                    </form>
                    </fieldset>
                    <form name="ReservationRoomListForm" method="post">
                    <input name="cmd" type="hidden" id="cmd" value="" />
					<div>
					<table width="100%">
						<tr>
                        	<td width="100%">
							<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                                <tr>
                                    <td class="form-title">[[|title|]] ([[.total.]]: <?php echo sizeof([[=items=]]);?>)</td>
                                </tr>
                            </table>
                            </td>
					</tr></table>
					</div>
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="table-header">
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='traveller_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.payment_traveller.]]								</a>							</th>
							<th align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room_id.]]</a></th><th nowrap align="right">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.price' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.price'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.price') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.price.]] (<?php echo HOTEL_CURRENCY;?>)</a>
							</th><th nowrap align="center" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.adult' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.adult'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.adult') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.num_people.]]								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.arrival_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.arrival_time'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.arrival_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.arrival_time.]]								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.departure_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.departure_time'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.departure_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.departure_time.]]								</a>
							</th><th nowrap align="center" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.status'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]								</a>
							</th>
							<th align="left"> [[.create_user.]] </th>
							<th align="left"> [[.create_time.]] </th>
							<th align="left">[[.booked_user.]]</th>
							<th align="left">[[.checked_in_user.]]</th>
							<th align="left"> [[.lastest_edited_user.]] </th>
							<th align="left">
								[[.lastest_edited_time.]]							</th>
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
	                        <td colspan="15" class="category-group">[[[.rcode.]]:  [[|items.reservation_id|]]] | <span style="color:#0066FF;">[[[.booking_code.]]: [[|items.booking_code|]]]</span> | [[.tour.]]: [[|items.tour_name|]] | [[.customer.]]: [[|items.customer_name|]]</td>
                        </tr>
                        <?php }?>
						<tr id="ReservationRoom_tr_[[|items.reservation_id|]]" <?php echo 'class="'.(([[=items.rownumber=]]%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150px" align="left">
                            	[[|items.rownumber|]] / [[.invoice_number.]]: [[|items.id|]]
								<!--LIST:items.travellers-->
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>[[=items.travellers.id=]]));?>">[[|items.travellers.full_name|]]</a></div>
								<!--/LIST:items.travellers--></td>
							<td class="reservation-list-item"align="left" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">
								<div style="float:left;width:180px;font-size:11px;"><strong>[[|items.room_id|]]</strong> - [[.room_level.]] <strong>[[|items.room_level|]]</strong><br />
							  ([[|items.portal_name|]])</div></td>
							<td class="reservation-list-item" nowrap align="right" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">
								[[|items.price|]]
							</td><td class="reservation-list-item" nowrap align="center" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">
								[[|items.adult|]] [[.a.]] + [[|items.child|]] [[.c.]]
							</td>
							<td class="reservation-list-item" nowrap align="left" title="[[|items.arrival_time|]] [[|items.time_in|]]" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">
								[[|items.brief_arrival_time|]] [[|items.time_in|]]</td>
							<td  class="reservation-list-item"nowrap align="left" title="[[|items.departure_time|]] [[|items.time_out|]]" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">
								[[|items.brief_departure_time|]] [[|items.time_out|]]</td>
							<td class="reservation-list-item" nowrap align="center" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">
								[[|items.status|]]							</td>
							<td class="reservation-list-item" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">[[|items.user_id|]]</td>
							<td class="reservation-list-item" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';"><?php echo date('d/m/Y H:i\'',[[=items.time=]]);?></td>
							<td align="center" nowrap="nowrap" class="reservation-list-item" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';"> [[|items.booked_user_id|]] </td>
							<td align="center" nowrap="nowrap" class="reservation-list-item" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';"> [[|items.checked_in_user_id|]] </td>
							<td class="reservation-list-item" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';">[[|items.lastest_edited_user_id|]]</td>
							<td class="reservation-list-item" onclick="window.location='<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>';"><?php echo [[=items.lastest_edited_time=]]?date('d/m/Y H:i\'',[[=items.lastest_edited_time=]]):'';?></td>
							<td class="reservation-list-item" nowrap width="15px">
							<?php 
							if( User::can_admin(false,ANY_CATEGORY)or (USER::can_add(false,ANY_CATEGORY) and ([[=items.status=]] == 'BOOKED' ))or (User::can_edit(false,ANY_CATEGORY) and ([[=items.status=]] == 'BOOKED' or [[=items.status=]] == 'IN'))){?>
								&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" border="0"></a>
							<?php
							}
							?></td>
							<td class="reservation-list-item" nowrap width="15px">
							<?php 
							if( 
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and [[=items.status=]] == 'BOOKED')
							)
							{
							?>&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>[[=items.reservation_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" border="0"></a>							
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
			[[|paging|]]
    </td>
  </tr>
</table>