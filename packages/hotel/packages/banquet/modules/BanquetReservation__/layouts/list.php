<style>
select{
    width:150px;
}
</style>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('banquet_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%"><?php if(!isset($_REQUEST['status']) AND Url::get('cmd')=='list_cancel'){ echo Portal::language('banquet_reservation_list_cancel'); }elseif(Url::get('status')=='CHECKOUT'){ echo Portal::language('banquet_reservation_list_checkout'); }elseif(Url::get('status')=='CHECKIN'){ echo Portal::language('banquet_reservation_list_checkin'); }else{ echo Portal::language('banquet_reservation_list'); } ?></td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.banquet_reservation.]]</a></td><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};BanquetReservationListForm.cmd.value='delete';BanquetReservationListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>                    
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
                <legend class="title">[[.search_options.]]</legend>
                <form method="post" name="SearchBanquetReservationForm"> 
                <table>
                    <tr>
                      <td align="right" nowrap="nowrap">[[.guest_name.]]</td>
                      <td>:</td>
                      <td nowrap="nowrap"><input name="full_name" type="text" id="full_name" style="width:80px;" class="date-input" /></td>
                        <td align="right" nowrap>[[.arrival_time.]]</td>
                        <td>:</td>
                        <td nowrap>
                                <input name="from_arrival_time" type="text" id="from_arrival_time" onchange="changevalue();" style="width:80px;" class="date-input">&nbsp;[[.to.]] [[.date.]]
                            <input name="to_arrival_time" type="text" id="to_arrival_time" onchange="changefromday();" style="width:80px;" class="date-input"></td>
                        <td nowrap> [[.status.]] </td>
                        <td><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value"></select></td>
                        <td nowrap> [[.party_type.]] </td>
                        <td><select name="party_name" id="party_name">
                            <option value="0">[[.all.]]</option>
                            <?php echo '<option>'.[[=party_name=]].'</option>'; ?>
                        </select></td>
                        <td nowrap> [[.position_party.]] </td>
                        <td><select id="party_room" name="party_room">
                            <option value="0">[[.all.]]</option>
                           [[|room_party|]]
                        </select></td>
                        <td nowrap><input type="submit" value="[[.search.]]" /></td>
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
                </form>
                </fieldset>
				<form name="BanquetReservationListForm" method="post">
				<p>
				<table width="100%" cellpadding="2">
                    <tr>
                        <td width="1%"><input type="button" value="[[.list_title_debt.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_debt'));?>'" /></td>
                        <td width="1%"><input type="button" value="[[.list_title_free.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_free'));?>'" /></td>
                        <td width="1%"><input type="button" value="[[.list_title_cancel.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_cancel'));?>'" /></td>
                        <td width="1%" align="right">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td align="right"><strong>[[.currency.]]: <?php echo HOTEL_CURRENCY;?>&nbsp;&nbsp;</strong></td>
                    </tr>
                </table>
				</p>
                <div style="border:2px solid #FFFFFF;">
    				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
    					<tr class="table-header">
    						<th rowspan="2" width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
                            <th rowspan="2" align="left" nowrap="nowrap">[[.position_party.]]</th>
    						<th rowspan="2" nowrap align="left" width="10%">
    							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.time'));?>">
    							<?php if(URL::get('order_by')=='party_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]/[[.time_in.]]</a></th>
    						<th rowspan="2" align="left" nowrap="nowrap"> 
    							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.full_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.full_name'));?>">
    							<?php if(URL::get('order_by')=='party_reservation.full_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.full_name.]]</a></th>
    						<th rowspan="2" align="left" nowrap="nowrap">[[.party_type.]]</th>
    						<th rowspan="2" align="right" nowrap="nowrap">[[.party_num_people.]]</th>
                            <th rowspan="2" align="right" nowrap="nowrap">[[.advance_payment.]]</th>
                            <th rowspan="2" align="right" nowrap="nowrap">[[.deposit.]]</th>
    						<th rowspan="2" align="right" nowrap="nowrap">[[.total.]]</th>
                            <th colspan="2" align="right" nowrap="nowrap">[[.cancel_charge.]]</th>
    						<th rowspan="2" align="left" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='party_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'party_reservation.status'));?>">
							<?php if(URL::get('order_by')=='party_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]</a>						</th>
                            <?php if(User::can_delete(false,ANY_CATEGORY)) {?><th rowspan="2" width="1%" nowrap="nowrap">&nbsp;</th>
    						<th rowspan="2">[[.user.]]</th>
                            <th rowspan="2">[[.note.]]</th>
                            <th rowspan="2">&nbsp;</th>
                            <th rowspan="2">&nbsp;</th>
    						<th rowspan="2">&nbsp;</th>
                            <th rowspan="2">&nbsp;</th>
                            <th rowspan="2">&nbsp;</th>
    						<?php }?>
    						<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
                            <th rowspan="2" width="1%">&nbsp;</th>
    						<?php }?>
                        </tr>
                        <tr>
                            <th>(%)</th>
                            <th>[[.amount.]]</th>
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
    						    <?php if([[=items.night_audit_date=]] ==''){?>
                                <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/>
                                </td>
                                <?php } else{?>
                                <td></td>
                                <?php }?>
                                <td align="left" nowrap="nowrap"> [[|items.group_name|]]-[[|items.room_name|]]</td>
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
                                <td align="right" nowrap="nowrap"><?php echo System::display_number([[=items.advance_payment=]]).' ';?></td>
                                <td align="right" nowrap="nowrap"><?php echo System::display_number([[=items.deposit_1=]] + [[=items.deposit_2=]] + [[=items.deposit_3=]] + [[=items.deposit_4=]]).' ';?></td>
    							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
                                <td align="right" nowrap="nowrap">[[|items.cancel_charge_percent|]]</td>
                                <td align="right" nowrap="nowrap">[[|items.cancel_charge_amount|]]</td>
    							<td align="left" nowrap>[[|items.status|]]</td>
   								<td nowrap="nowrap"><?php if(User::can_delete(false,ANY_CATEGORY) and ([[=items.status=]]!='CHECKOUT' and [[=items.status=]]!='CANCEL')) {?><a onclick="fun_cancel(<?php echo [[=items.party_id=]];?>,<?php echo [[=items.id=]]; ?>);" style="color:#FF0000;text-decoration:underline;">[[.cancel_this_order.]]</a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
    							<td align="center" nowrap="nowrap">[[|items.user_id|]]</td>
                                
                                
                                <!--<td nowrap="nowrap"><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>'view_contact','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/order.png" alt="[[.view_contact.]]" title="[[.view_contact.]]" width="12" height="12" border="0"/></a></td>-->
                                <?php if(User::can_edit(false,ANY_CATEGORY) && [[=items.status=]]!='CANCEL') {?> <td nowrap><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>[[=items.party_id=]],'action'=>'edit','id'=>[[=items.id=]])); ?>"><img align=center src="packages/hotel/skins/default/images/iosstyle/icon-note-no.png" title="[[|items.note|]]" width="12" height="12" border="0"></a></td><?php }else{?><td nowrap="nowrap">&nbsp;</td><?php }?>
                                <td nowrap>
                                    <?php if([[=items.mice_reservation_id=]]==''){ ?>
                                    <a onclick="openWindowUrl('form.php?block_id=428&cmd=payment&id=<?php echo [[=items.id=]];?>&type=BANQUET&total_amount=<?php echo System::calculate_number([[=items.total=]]);?>',Array('payment','payment_for',80,210,950,500));">
                                    <img src="packages/core/skins/default/images/buttons/order.png" alt="[[.payment.]]" title="[[.payment.]]" width="12" height="12" border="0"/>
                                    </a>
                                    <?php } ?>
                                </td>
                                <?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap="nowrap" align="center"><a href="<?php echo Url::build_current(array('cmd'=>'view_contact','id'=>[[=items.id=]], 'party_type'=>[[=items.party_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/copy.png" alt="[[.contract.]]" title="[[.contract.]]" width="15" height="15" border="0"></a></td> <?php } ?>
                                <?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap="nowrap" align="center"><a href="<?php echo Url::build_current(array('cmd'=>'detail','id'=>[[=items.id=]], 'party_type'=>[[=items.party_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/information.png" alt="[[.information.]]" title="[[.information.]]" width="15" height="15" border="0"></a></td> <?php } ?>
    							<?php if(User::can_edit(false,ANY_CATEGORY) and [[=items.night_audit_date=]] =='') {?> 
                                <td nowrap><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>[[=items.party_id=]],'action'=>'edit','id'=>[[=items.id=]])); ?>">
                                    <img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"/></a>
                                </td>
                                <?php }else{?><td nowrap="nowrap">&nbsp;</td><?php }?>
    							<?php if(User::can_view(false,ANY_CATEGORY)) {?> <td nowrap="nowrap" align="center"><a href="<?php echo Url::build_current(array('cmd'=>'print_order','id'=>[[=items.id=]],'party_type'=>[[=items.party_id=]])); ?>"><img src="packages/core/skins/default/images/printer.png" alt="[[.edit.]]" title="[[.edit.]]" width="15" height="15" border="0"></a></td> <?php } ?>
                                <?php if(User::can_delete(false,ANY_CATEGORY) and [[=items.night_audit_date=]] =='') {?>
                                <td nowrap="nowrap"><a href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>'delete','id'=>[[=items.id=]], 'party_type'=>[[=items.party_id=]])); ?>">
                                    <img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a>
                                </td>
    							<?php } else{?>
                                <td></td>
                                <?php }?>
                                </tr>
    					<!--/LIST:items-->
    				</table>
                </div>
                [[|paging|]]
                
			<input name="cmd" type="hidden" value="">
            </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    <?php
        if(isset($_REQUEST['party_room_select'])){
            echo 'var party_room_select='.$_REQUEST['party_room_select'].';';
    ?>
    jQuery('#party_room').val(party_room_select);
    <?php }?>
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
    function fun_cancel(party_id,id)
    {
        var cf = confirm('[[.huy_tiec_co_phi_khong.]]');
        if(cf==true)
        {
            window.location='?page=banquet_reservation&cmd='+party_id+'&status=CANCEL&action=edit&id='+id+'&portal=<?php echo PORTAL_ID;?>';
            
        }
        else
        {
            window.location='?page=banquet_reservation&cmd=cancel&id='+id+'&portal=<?php echo PORTAL_ID;?>';
        }
    }
</script>