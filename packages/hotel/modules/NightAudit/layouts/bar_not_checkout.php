<style type="text/css">
#tbl_print{ display: none;}
</style>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
			<td>
				<form name="BarReservationNewListForm" method="post">
                <div style="border:2px solid #FFFFFF;">
                <div>
                  <h3>[[.bar_not_checkout.]] [[.date.]] <?php echo $_SESSION['night_audit_date'];?></h3>
                </div>
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th nowrap align="left">
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.time'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]/[[.time_in.]]</a></th>
						
						<th align="left" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.code'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]] </a>						</th>
						<th align="center" nowrap="nowrap"> 
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.agent_name.]]</a>							</th>
						<th align="center" nowrap="nowrap">[[.room_name.]]</th>
						<th align="center" nowrap="nowrap">[[.table_number.]]</th>
						<th align="center" nowrap="nowrap">[[.total.]]</th>
						<th align="center" nowrap="nowrap">[[.time_length.]]</th>
						<th align="left" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.status'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]</a>						</th>
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
                    
                    <?php //Xem hoa don echo URL::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print'),'method'=>'print_direct','id'=>[[=items.id=]]));?>
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td nowrap align="left">[[|items.arrival_date|]]</td>
							<td align="left" nowrap="nowrap">[[|items.code|]]</td>
							<td align="left" nowrap="nowrap">[[|items.agent_name|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.room_name|]]</td>
							<td align="center" nowrap="nowrap">[[|items.table_name|]]</td>
							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.time_length|]] </td>
							<td align="left" nowrap>[[|items.status|]]</td>
							<td nowrap><?php if(User::can_delete(false,ANY_CATEGORY) and ([[=items.status=]]!='CHECKOUT' and [[=items.status=]]!='CANCEL')) {?><a href="<?php echo Url::build_current(array('act'=>'cancel','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]])); ?>" style="color:#FF0000;text-decoration:underline;">[[.cancel_this_order.]]</a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
							<td align="center" nowrap="nowrap">[[|items.user_id|]]</td>
							<?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td nowrap><a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td> <?php } ?>
							<?php if(User::can_admin(false,ANY_CATEGORY)) {?><td nowrap><a target="_blank" href="<?php echo Url::build('bar_reservation',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php } ?></tr>
					<!--/LIST:items-->
                    <!--IF:cond(![[=items=]])-->
                      	<tr>
                        	<td colspan="13" class="notice" align="center"><p>&nbsp;</p>
                        	  [[.no_bar_not_checkout.]]
                       	    <p>&nbsp;</p></td>
                        </tr>
                    <!--/IF:cond-->
				</table>
                </div>
                [[|paging|]]
			<input name="cmd" type="hidden" value="">
            </form> 
		</td>
        </tr>
	</table>
    <table id="tbl_print" width="100%">
        <tr>
            <td align="center" colspan="2">[[.start_time_night_audit.]]:<?php echo Date('H:i d/m/Y',Url::get('start_time_night_audit'));?></td>
        </tr>
        <tr>
            <td align="left" width="50%">[[.print_user.]]</td>
            <td align="right" width="50%">[[.print_time.]]</td>
        </tr>
        <tr>
            <td align="left" width="50%"><?php echo Session::get('user_id');?></td>
            <td align="right" width="50%"><?php echo Date('H:i d/m/Y',time());?></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><p>&nbsp;</p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> <input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'bar_booked','start_time_night_audit'=>Url::get('start_time_night_audit')));?>'"/><p>&nbsp;</p></td>
      </tr>
    </table>
    </td>
</tr>
</table>
<style type="text/css">
@media print{
    
    input[type=button]{
        display:none;
    }
    #tbl_print{
        display: block;
    }
}
</style>    
<script>
	jQuery('#from_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
</script>