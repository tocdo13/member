<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<script src="packages/core/includes/js/calendar.js">
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<table cellspacing="0" width="100%">
	<tr>
    	<td>
        <div class="bar-id">
            <label for="bar_id">[[.Bar_name.]]: </label>
            <?php if(User::can_admin(MODULE_RESTAURANTPRODUCT,ANY_CATEGORY)){?>
            <select name="bar_id" onchange="window.location='<?php echo Url::build('bar_reservation',array('cmd'))?>'+'&bar_id='+this.value"></select>
            <?php }else{?>
            <span>[[|bar_name|]]</span>
            <?php }?>
        </div>        
        </td>
    </tr>	
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.bar_reservation_list.]] - [[|bar_name|]]</td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.bar_reservation.]]</a></td><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};BarReservationListForm.cmd.value='delete';BarReservationListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>                    
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="5">
                    <tr>
                        <td width="100%">
                            <b>[[.search.]]</b>
                            <form method="post" name="SearchBarReservationForm"> 
                            <table>
                                <tr>
                                    <td align="right" nowrap>[[.arrival_time.]]</td>
                                    <td>:</td>
                                    <td nowrap>
                                            <input name="from_arrival_time" type="text" id="from_arrival_time" size="12">
                                            <a href="#" name="arrival_time_start_date_in" onclick="cal.select(this.input,'arrival_time_start_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" title="[[.select_date.]]"></a>
                                        <script>
                                            var inputs = document.getElementsByTagName("input");
                                            var anchors = document.getElementsByTagName("a");
                                            anchors[anchors.length-1].input = inputs[inputs.length-1];
                                        </script>
                                        &nbsp;&nbsp;[[.to.]]
                                        <input name="to_arrival_time" type="text" id="to_arrival_time" size="12">
                                        <a href="#" name="arrival_time_end_date_in" onclick="cal.select(this.input,'arrival_time_end_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" title="[[.select_date.]]"></a>
                                        <script>
                                            var inputs = document.getElementsByTagName("input");
                                            var anchors = document.getElementsByTagName("a");
                                            anchors[anchors.length-1].input = inputs[inputs.length-1];
                                        </script>
                                    </td>
                                </tr> 
                                <tr>    
                                    <td align="right" nowrap>[[.agent_name.]]</td>
                                    <td>:</td>
                                    <td nowrap>
                                            <input name="agent_name" type="text" id="agent_name" size="45">
                                    </td>
                                </tr>
                                <tr>
                                	<td align="right">[[.total_from.]]</td>
                                    <td>:</td>
                                    <td>
                                    	<input name="total_from" type="text" id="total_from" size="15" />&nbsp;[[.to.]]:&nbsp;<input name="total_to" type="text" id="total_to" size="15" />&nbsp;<strong><?php echo HOTEL_CURRENCY?></strong>
                                    </td>
                                </tr>
                                <tr>
                                	<td>&nbsp;</td>
                                	<td>&nbsp;</td>
                                    <td>
                                        <?php echo Draw::button(Portal::language('search'),false,false,true,'SearchBarReservationForm');?><?php echo Draw::button('Reset','?page=bar_reservation');?></td>
                                </tr>
                            </table>
                            </form>
                        </td>
                    </tr>
				</table>
				<form name="BarReservationListForm" method="post">
				<p>
				<table width="100%" cellpadding="2">
                    <tr>
                        <td width="1%"><?php Draw::button(Portal::language('list_title_debt'),Url::build_current(array('cmd'=>'list_debt')+array( 'bar_reservation_bar_id', 'bar_reservation_receptionist_id')));?></td>
                        <td width="1%"><?php Draw::button(Portal::language('list_title_free'),Url::build_current(array('cmd'=>'list_free')+array( 'bar_reservation_bar_id', 'bar_reservation_receptionist_id')));?></td>
                        <td width="1%"><?php Draw::button(Portal::language('list_title_cancel'),Url::build_current(array('cmd'=>'list_cancel')));?></td>
                        <td width="1%" align="right">[[.status.]]: </td>
                        <td width="1%"><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&status='+this.value"></select></td>
                        <td align="right"><strong>[[.currency.]]: <?php echo HOTEL_CURRENCY;?>&nbsp;&nbsp;</strong></td>
                    </tr>
                </table>
				</p>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr valign="middle" style="line-height:20px;">
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
						<th align="center" nowrap="nowrap">[[.bar_name.]]</th>
						<th align="center" nowrap="nowrap">[[.table_number.]]</th>
						<th align="center" nowrap="nowrap">[[.total.]]</th>
						<th align="center" nowrap="nowrap">[[.time_length.]]</th>
						<th align="left" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.status'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]</a>						</th>
						<?php if(User::can_edit(false,ANY_CATEGORY)) {?><th>&nbsp;</th><?php }?><th>&nbsp;</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?><th>&nbsp;</th>
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
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current().(([[=items.status=]]=='CHECKIN')?'&cmd=check_in':'');?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;<?php if([[=items.status=]]=='CHECKIN'){?>font-weight:bold;<?php }?>">
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td nowrap align="left">[[|items.arrival_date|]]</td>
							<td align="left" nowrap="nowrap">[[|items.order_id|]]</td>
							<td align="left" nowrap="nowrap">[[|items.agent_name|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.room_name|]]</td>
							<td align="center" nowrap="nowrap">[[|items.bar_name|]]</td>
							<td align="center" nowrap="nowrap">[[|items.table_name|]]</td>
							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.time_length|]] </td>
							<td align="left" nowrap>[[|items.status|]]</td>
							<td nowrap><?php if(User::can_edit(false,ANY_CATEGORY) and ([[=items.status=]]!='CHECKOUT' and [[=items.status=]]!='CANCEL')) {?><a href="<?php echo Url::build_current(array('act'=>'cancel','id'=>[[=items.id=]])); ?>">Hu&#7927;</a><?php } ?></td>
							<?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td> <?php } ?>
							<?php if(User::can_delete(false,ANY_CATEGORY)) {?><td nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php } ?></tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
                <p>
                <table><tr>
                    <td><?php Draw::button(Portal::language('list_title_debt'),Url::build_current(array('cmd'=>'list_debt')+array( 'bar_reservation_bar_id', 'bar_reservation_receptionist_id')));?></td>
                    <td><?php Draw::button(Portal::language('list_title_free'),Url::build_current(array('cmd'=>'list_free')+array( 'bar_reservation_bar_id', 'bar_reservation_receptionist_id')));?></td>
                    <td><?php Draw::button(Portal::language('list_title_cancel'),Url::build_current(array('cmd'=>'list_cancel')));?></td>
                </tr></table>
                </p>
			<input name="cmd" type="hidden" value="">
            </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    