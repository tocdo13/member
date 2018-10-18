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
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%" align="center">
					<div class="back-button"><input type="button" value="[[.back.]]" onclick="window.location = '<?php echo Url::build_current(array('status','resolve','no_checkin','portal_id','booking_code'));?>'" /></div>
					<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
					<tr valign="top">
					  <td align="left" width="30%"><strong><?php echo HOTEL_NAME;?></strong><br />
					<?php echo HOTEL_ADDRESS;?></strong></td>
						<td width="40%" align="center" valign="middle"><h3 class="report-title">[[|title|]]</h3>
							[[.from_date.]] [[|from_date|]]
							[[.to_date.]] [[|to_date|]]<br />
							<br />
                            [[.total.]]: <?php echo ([[=total=]]);?> [[.room.]],
							<!--IF:cond(Url::get('status'))-->
								[[.status.]]:&nbsp;<?php echo Url::get('status');?>
							<!--/IF:cond-->
					  </td>
						<td width="30%" align="center" valign="middle" nowrap>
						<strong>[[.template_code.]]</strong></td>
					</tr>
					</table><br />
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='traveller_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.payment_traveller.]]								</a>							</th>
							<th align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room.]]</a></th><th nowrap align="right">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.price' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.price'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.price') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.price.]] <span style="font-size:10px;">(<?php echo HOTEL_CURRENCY;?>)</span></a>
							</th><th nowrap align="center" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.adult' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.adult'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.adult') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.num_people.]]								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.arrival_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.arrival_time'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.arrival_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.arrival_time.]]								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.departure_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.departure_time'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.departure_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.departure_time.]]								</a>
							</th>
							<th nowrap align="center" >[[.real_status.]]</th>
							<th align="left"> [[.create_user.]] </th>
							<th align="left"> [[.create_time.]] </th>
							<th align="left">[[.booked_user.]]</th>
							<th align="left">[[.checked_in_user.]]</th>
							<th align="left"> [[.lastest_edited_user.]] </th>
							<th align="left">[[.lastest_edited_time.]]</th>
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
                        <!--LIST:items-->
                        <?php if($temp!=[[=items.reservation_id=]]){$temp = [[=items.reservation_id=]];?>
                        <tr>
	                        <td colspan="13" class="category-group">
								[[[.rcode.]]:  [[|items.reservation_id|]]] | <span style="color:#0066FF;">[[[.booking_code.]]: [[|items.booking_code|]]]</span> | [[.tour.]]: [[|items.tour_name|]] | [[.customer.]]: [[|items.customer_name|]]
								<!--IF:cond([[=items.group_note=]])-->
								<div class="note" style="text-transform:none;"><span class="note">*[[.note.]]: [[|items.group_note|]]</span></div>
								<!--/IF:cond-->
							</td>
                        </tr>
                        <?php }?>
						<tr id="ReservationRoom_tr_[[|items.reservation_id|]]" <?php echo 'class="'.(([[=items.i=]]%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150" align="left">
                            	[[|items.i|]] / [[.invoice_number.]]: [[|items.id|]]
								<!--LIST:items.travellers-->
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>[[=items.travellers.id=]]));?>">[[|items.travellers.full_name|]]</a></div>
								<!--/LIST:items.travellers-->
							</td>
							<td class="reservation-list-item"align="left">
								<div style="float:left;width:150px;font-size:11px;"><strong>[[|items.room_id|]]</strong> - [[.room_level.]] <strong>[[|items.room_level|]]</strong></div>
								<!--IF:cond([[=items.note=]])-->
								<div class="note"><span class="note">*[[.note.]]: [[|items.note|]]</span></div>
								<!--/IF:cond-->
							</td>
							<td class="reservation-list-item" nowrap align="right">[[|items.price|]]</td>
							<td class="reservation-list-item" nowrap>
								([[|items.adult|]])<img src="packages/core/skins/default/images/buttons/adult.png" width="8"><!--IF:cond([[=items.child=]])-->+ ([[|items.child|]])<img src="packages/core/skins/default/images/buttons/child.png" width="8"><!--/IF:cond--></td>
							<td class="reservation-list-item" nowrap align="left" title="[[|items.arrival_time|]] [[|items.time_in|]]">
								[[|items.time_in|]]</td>
							<td  class="reservation-list-item"nowrap align="left" title="[[|items.departure_time|]] [[|items.time_out|]]">
							  [[|items.time_out|]]<!--IF:vd_cond([[=items.verify_dayuse=]])--><br />(<span style="font-weight:bold;">[[|items.verify_dayuse_label|]]</span>)<!--/IF:vd_cond--></td>
							<td class="reservation-list-item" nowrap align="center">
								[[|items.status|]]							</td>
							<td class="reservation-list-item">[[|items.user_id|]]</td>
							<td class="reservation-list-item"><?php echo date('d/m/Y H:i\'',[[=items.time=]]);?></td>
							<td align="center" nowrap="nowrap" class="reservation-list-item"> [[|items.booked_user_id|]] </td>
							<td align="center" nowrap="nowrap" class="reservation-list-item"> [[|items.checked_in_user_id|]] </td>
							<td class="reservation-list-item">[[|items.lastest_edited_user_id|]]</td>
							<td class="reservation-list-item"><?php echo [[=items.lastest_edited_time=]]?date('d/m/Y H:i\'',[[=items.lastest_edited_time=]]):'';?></td>
						</tr>
						<?php if([[=reservation_arr=]][[[=items.reservation_id=]]] == [[=items.count=]]){?>
						<tr bgcolor="#EFEFEF">
						  <td align="right" class="reservation-list-item"><strong>[[.total_room.]]</strong></td>
						  <td colspan="13" align="left" class="reservation-list-item"><strong><?php echo [[=reservation_arr=]][[[=items.reservation_id=]]];?></strong></td>
					    </tr>
					  	<?php }?>
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
<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td align="center"> </td>
	<td align="center">[[.day.]] <?php echo date('d');?>[[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr valign="top">
	<td width="33%" align="center">[[.creator.]]</td>
	<td width="33%" align="center">[[.general_accountant.]]</td>
	<td width="33%" align="center">[[.director.]]</td>
</tr>
</table>