<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.banquet_order_list.]] <?php echo Url::get('status')?Portal::language(Url::get('status')):'';?></td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.party_reservation.]]</a></td><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};BanquetOrderListForm.cmd.value='delete_selected';BanquetOrderListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>                    
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
			<td>
                <form method="post" name="SearchBanquetOrderForm"> 
				<table width="100%" cellpadding="0" cellspacing="5">
                    <tr>
                        <td width="100%">
                        	<fieldset>
                            <legend>[[.search.]]</legend>
                            <table width="100%">
                                <tr>
                                    <td align="right" nowrap>[[.guest_name.]]</td>
                                    <td>:</td>
                                    <td nowrap>
                                            <input name="full_name" type="text" id="full_name" style="width:80px;" class="input">
									</td>
                                    <td align="right" nowrap>[[.arrival_time.]]</td>
                                    <td>:</td>
                                    <td nowrap>
                                            <input name="from_arrival_time" type="text" id="from_arrival_time" style="width:80px;" class="date-input">&nbsp;[[.to.]]
                                        <input name="to_arrival_time" type="text" id="to_arrival_time" style="width:80px;" class="date-input"></td>
                                    <td nowrap>&nbsp;</td>
									<td><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value"></select></td>
                                    <td nowrap><input type="submit" value="[[.search.]]" /></td>
                                </tr>
                            </table>
                            </fieldset>
                        </td>
                    </tr>
				</table>
				</form>                
				<form name="BanquetOrderListForm" method="post">
				<p>
				<table width="100%" cellpadding="2">
                    <tr>
                        <td width="1%" align="right">&nbsp;</td>
                        <td align="right"></td>
                        <td align="right"><strong>[[.currency.]]: <?php echo HOTEL_CURRENCY;?>&nbsp;&nbsp;</strong></td>
                    </tr>
                </table>
				</p>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th nowrap align="left" width="10%">
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.time'));?>">
							<?php if(URL::get('order_by')=='party_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]/[[.time_in.]]</a></th>
						<th align="left" nowrap="nowrap"> 
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.agent_name.]]</a></th>
						<th align="left" nowrap="nowrap">[[.party_type.]]</th>
						<th align="right" nowrap="nowrap">[[.party_num_people.]]</th>
                        <th align="right" nowrap="nowrap">[[.deposit.]]([[.cashier.]])</th>
						<th align="right" nowrap="nowrap">[[.total.]]</th>
						<th align="left" nowrap><a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.status'));?>"><?php if(URL::get('order_by')=='bar_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]</a></th>
						<?php if(User::can_edit(false,ANY_CATEGORY)) {?>
                        <th width="1%" nowrap="nowrap">&nbsp;</th>
						<th>[[.user.]]</th>
                        <th>&nbsp;</th>
						<th>&nbsp;</th>
                        <th>&nbsp;</th>
						<?php }?>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
                        <th width="1%">&nbsp;</th>
						<?php }?>
                    </tr>
					<!--LIST:items-->
                    <?php
						if([[=items.status=]]=='CHECKIN')
						{
							$bg_color = '#FFFF99';
						}
						else if([[=items.status=]]=='CHECKOUT')
						{
							$bg_color = '#E2F1DF';
						}
						else
						{
							$bg_color = '#FFFFFF';
						}						
						?>
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?> style="cursor:hand;<?php if([[=items.status=]]=='CHECKIN' || [[=items.status=]]=='BOOKED' || [[=items.status=]]=='RESERVATION'){?>font-weight:bold;<?php }?>">
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td nowrap align="left">[[|items.arrival_date|]]</td>
							<td align="left" nowrap="nowrap">[[|items.full_name|]]</td>
							<td align="left" nowrap="nowrap">[[|items.party_name|]]</td>
                            <td align="right" nowrap="nowrap">
                                <?php 
                                    if([[=items.party_id=]] == 3)
                                        echo [[=items.meeting_num_people=]].' ';
                                    else
                                        echo [[=items.num_people=]].' ';
                                ?>[[.person.]]
                            </td>
                            <td align="right" nowrap="nowrap"><?php echo System::display_number([[=items.deposit_1=]] + [[=items.deposit_2=]] + [[=items.deposit_3=]] + [[=items.deposit_4=]]).' ';?></td>
							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
							<td align="left" nowrap>[[|items.status|]]</td>
							<td nowrap><?php if(User::can_edit(false,ANY_CATEGORY) and ([[=items.status=]]!='CHECKOUT' and [[=items.status=]]!='CANCEL')) {?><a href="<?php echo Url::build_current(array('cmd'=>'cancel','id'=>[[=items.id=]])); ?>" style="color:#FF0000;text-decoration:underline;">[[.cancel_this_order.]]</a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
							<td align="center" nowrap="nowrap">[[|items.user_id|]]</td>
                            <!--
                            <td nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'view_contact','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/order.png" alt="[[.view_contact.]]" title="[[.view_contact.]]" width="12" height="12" border="0"/></a></td>
                            -->
                            <td nowrap><a onclick="openWindowUrl('form.php?block_id=428&cmd=payment&id=<?php echo [[=items.id=]];?>&type=BANQUET&total_amount=<?php echo System::calculate_number([[=items.total=]]);?>',Array('payment','payment_for',80,210,950,500));">
                                <img src="packages/core/skins/default/images/buttons/order.png" alt="[[.payment.]]" title="[[.payment.]]" width="12" height="12" border="0"/></a>
                            </td>
							<?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap align="center"><a href="<?php echo Url::build_current(array('cmd'=>'detail','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/printer.png" alt="[[.edit.]]" width="15" height="15" border="0"></a></td> <?php } ?>
							<?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>[[=items.party_id=]],'action'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td> <?php } ?>
							<?php if(User::can_delete(false,ANY_CATEGORY)) {?><td nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php } ?></tr>
					<!--/LIST:items-->
				</table>
                </div>
                <div>[[|paging|]]</div>
			<input name="cmd" type="hidden" value="">
            </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker();
	jQuery('#to_arrival_time').datepicker();
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBanquetOrderForm.submit();
	//jQuery('#acction').val(0);
	}
</script>