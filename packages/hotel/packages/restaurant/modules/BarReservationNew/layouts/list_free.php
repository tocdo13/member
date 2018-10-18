<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form name="BarReservationNewListForm" method="post">
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.bar_reservation_list_free.]]  ([[|bar_name|]])</td>
					<?php
                        if(User::can_delete(false,ANY_CATEGORY))
                        {
                        ?><td><input type="submit" name="delete" value="[[.delete.]]" class="button-medium-delete" /></td>
                        <?php
                        }
                        ?>
                    <td><input type="button" name="list" value="[[.back.]]" class="button-medium-back" onclick="window.location='<?php echo Url::build_current();?>'" /></td>                    
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<?php echo Draw::begin_round_table();?><b>[[.search.]]</b>
					<table>
                      <tr>
                        <td align="right" nowrap="nowrap">[[.arrival_time.]]</td>
                        <td>:</td>
                        <td nowrap="nowrap"><input name="from_arrival_time" type="text" id="from_arrival_time" size="8" />
                          [[.to.]]
                          <input name="to_arrival_time" type="text" id="to_arrival_time" size="8" />
                        </td>
                        <td nowrap="nowrap">&nbsp;</td>
                        <td nowrap="nowrap">[[.agent_name.]]</td>
                        <td nowrap="nowrap"><input name="agent_name" type="text" id="agent_name" /></td>
                        <td nowrap="nowrap"><input name="submit" type="submit" value="[[.search.]]" /></td>
                      </tr>
                    </table>
					<div style="border:2px solid #FFFFFF;">
                    <table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
						<tr class="table-header">
							<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.time'));?>">
								<?php if(URL::get('order_by')=='bar_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]</a>							</th>
							<th align="left" nowrap="nowrap"> [[.time_length.]] </th>
							<th align="left" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.code'));?>">
                              <?php if(URL::get('order_by')=='bar_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]] </a> </th>
							<th align="left" nowrap="nowrap"> 
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
								<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.agent_name.]]</a>							</th>
							<th align="left" nowrap="nowrap">[[.table_number.]]</th>
							<th align="right" nowrap>[[.free_total.]]</th>
							<th align="left">[[.user.]]</th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
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
						<!--LIST:items-->
						<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
								<td nowrap align="left">
									[[|items.arrival_date|]]								</td>
								<td align="left" nowrap="nowrap"> [[|items.time_length|]] </td>
								<td align="left" nowrap="nowrap"> [[|items.order_id|]] </td>
								<td align="left" nowrap="nowrap"> [[|items.agent_name|]] </td>
								<td align="left" nowrap="nowrap">[[|items.table_name|]]</td>
								<td align="right" nowrap>[[|items.free_total|]] <?php echo HOTEL_CURRENCY;?></td>
							    <td align="left" nowrap="nowrap">[[|items.user_id|]]</td>
							    <?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><td nowrap>
								&nbsp;<a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a>							</td>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><td nowrap>
								&nbsp;<a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a>							</td>
							<?php
							}
							?></tr>
						<!--/LIST:items-->
					</table>
                    [[|paging|]]
                    </div>
			  </td>
			</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<script>
	jQuery('#from_arrival_time').datepicker();
	jQuery('#to_arrival_time').datepicker();
</script>