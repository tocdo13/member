<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('karaoke_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr style="float: right;">
                    <!---<td class="form-title" width="100%">[[.karaoke_reservation_list.]] ([[|karaoke_name|]]) <?php echo Url::get('status');?></td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.karaoke_reservation.]]</a></td><?php }?>--->
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};KaraokeReservationNewListForm.cmd.value='delete';KaraokeReservationNewListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>                    
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
                <form method="post" name="SearchKaraokeReservationNewForm"> 
                <table>
                    <tr>
                      <td align="right" nowrap>[[.invoice_number.]] <input name="invoice_number" type="text" id="invoice_number" class="date-input" /></td>
                        <td align="right" nowrap>[[.arrival_time.]]</td>
                        <td>:</td>
                        <td nowrap>
                            <input name="from_arrival_time" type="text" id="from_arrival_time" style="width:80px;" onchange="changevalue();" class="date-input">&nbsp;[[.to.]]
                            <input name="to_arrival_time" type="text" id="to_arrival_time" style="width:80px;" onchange="changefromday();" class="date-input"></td>
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
                        <td nowrap>&nbsp;</td>
                        <td><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value"></select></td>
                        <td nowrap><input type="submit" value="[[.search.]]" /></td>
                        <td>&nbsp;</td>
                        <td align="right" style="text-align:right;">
                                [[.karaoke_name.]]: <select name="karaokes" id="karaokes" onchange="updateKaraoke();"></select> 
                                <input name="acction" type="hidden" value="0" id="acction" />
                                <script>
                                    var karaoke_id = '<?php if(Url::get('karaoke_id')){ echo Url::get('karaoke_id');} else { echo '';}?>';
                                    if(karaoke_id != ''){
                                    	$('karaokes').value = karaoke_id;	
                                    }
                                 </script>
                        </td>
                    </tr>
                </table>
                </form>
                </fieldset>
				<form name="KaraokeReservationNewListForm" method="post">
				<p>
				<table width="100%" cellpadding="2">
                    <tr>
                        <td width="1%" align="right">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td align="right"><strong>[[.currency.]]: <?php echo HOTEL_CURRENCY;?>&nbsp;&nbsp;</strong></td>
                    </tr>
                </table>
				</p>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th nowrap align="left">
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.time'));?>">
							<?php if(URL::get('order_by')=='karaoke_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]/[[.time_in.]]</a></th>
						
						<th align="left" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.code'));?>">
							<?php if(URL::get('order_by')=='karaoke_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]] </a>						</th>
						<th align="center" nowrap="nowrap"> 
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.agent_name'));?>">
							<?php if(URL::get('order_by')=='karaoke_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.agent_name.]]</a>							</th>
						<th align="center" nowrap="nowrap">[[.room_name.]]</th>
						<th align="center" nowrap="nowrap">[[.table_number.]]</th>
						<th align="center" nowrap="nowrap">[[.total.]]</th>
						<th align="center" nowrap="nowrap">[[.time_length.]]</th>
						<th align="left" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.status'));?>">
							<?php if(URL::get('order_by')=='karaoke_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]</a>						</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?><th width="1%" nowrap="nowrap">&nbsp;</th>
						<th>[[.user.]]</th>
						<th>&nbsp;</th><?php }?>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?><th width="1%">&nbsp;</th>
						<?php }?></tr>
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
                    
                    <?php //Xem hoa don echo URL::build('karaoke_touch',array('cmd'=>'detail',md5('act')=>md5('print'),'method'=>'print_direct','id'=>[[=items.id=]]));?>
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build('karaoke_touch').'&cmd=edit';?>&id=[[|items.id|]]&karaoke_id=[[|items.karaoke_id|]]';}else{just_click=false;}" style="cursor:hand;<?php if([[=items.status=]]=='CHECKIN'){?>font-weight:bold;<?php }?>">
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td nowrap align="left">[[|items.arrival_date|]]</td>
							<td align="left" nowrap="nowrap">[[|items.code|]]</td>
							<td align="left" nowrap="nowrap">[[|items.agent_name|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.room_name|]]</td>
							<td align="center" nowrap="nowrap">[[|items.table_name|]]</td>
							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.time_length|]] </td>
							<td align="left" nowrap>[[|items.status|]]</td>
							<td nowrap><?php if(User::can_delete(false,ANY_CATEGORY) and ([[=items.status=]]!='CHECKOUT' and [[=items.status=]]!='CANCEL')) {?><a href="<?php echo Url::build_current(array('act'=>'cancel','id'=>[[=items.id=]],'karaoke_id'=>[[=items.karaoke_id=]])); ?>" style="color:#FF0000;text-decoration:underline;">[[.cancel_this_order.]]</a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
							<td align="center" nowrap="nowrap">[[|items.user_id|]]</td>
							<?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td nowrap><a href="<?php echo Url::build('karaoke_touch',array(  'karaoke_reservation_karaoke_id', 'karaoke_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]],'karaoke_id'=>[[=items.karaoke_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td> <?php } ?>
							<?php if(User::can_admin(false,ANY_CATEGORY)) {?><td nowrap><a href="<?php echo Url::build_current(array(  'karaoke_reservation_karaoke_id', 'karaoke_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]],'karaoke_id'=>[[=items.karaoke_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php } ?></tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
                <p>
                </p>
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
	function updateKaraoke(){
		jQuery('#acction').val(1);
		//jQuery('#karaoke').val(jQuery('#karaoke').val());
		SearchKaraokeReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
</script>