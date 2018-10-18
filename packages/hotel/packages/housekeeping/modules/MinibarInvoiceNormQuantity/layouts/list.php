<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="MinibarInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="form-title">[[.minibars_invoice.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY))
			{
			?><td align="left"  width="100px">
				<a href="<?php echo Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
	'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'
	));?>" class="button-medium-add">[[.Add.]]</a>
	</td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td align="left"  width="100px">
				<input type="submit" name="delete_selected" class="button-medium-delete" value="[[.delete_selected.]]" />
			</td>
			<?php
			}
			?>
        </tr>
    </table>
	<table cellspacing="0" cellpadding="2">
	<tr>
		<td align="right" nowrap>[[.minibar_id.]]</td>
		<td>:</td>
		<td nowrap><select name="minibar_id" id="minibar_id" style="width:180px;">
		  </select>	</td>
		<td align="right" nowrap>[[.from_day.]]</td>
		<td>:</td>
		<td nowrap>
				<input name="time_start" type="text" id="time_start" size="12">
			[[.to.]]
			<input name="time_end" type="text" id="time_end" size="12">
		</td>
		<td><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchMinibarInvoiceForm');?></td>
		</tr>
	</table>	
	<table width="100%" cellspacing="0" cellpadding="2"><tr>
		<td align="right">
			[[.price_unit.]] <?php echo HOTEL_CURRENCY; ?>
		</td></tr></table>
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#7F9DB9" border="1" style="border-collapse:collapse">
		<tr class="table-header">
			<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
			<th width="1%">[[.code.]]</th>
			<th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>">
				<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room.]]</a>
			</th>
			<th nowrap align="center">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.time'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.date.]]</a>
			</th>
			<th nowrap width="200" align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room_id'));?>">
				<?php if(URL::get('order_by')=='reservation_room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.reservation_room_id.]]				</a>			</th>
			<th nowrap width="100" align="center">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total'));?>">				</a><a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.total') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				</a><a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total'));?>">[[.total.]]				</a>
			</th>
			<th nowrap align="center">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='user' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'user'));?>">
				<?php if(URL::get('order_by')=='user') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.user.]]				</a>			</th>
			<th nowrap align="center">
				[[.modifier.]]			</th>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><th>[[.edit.]]</th>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><th>[[.delete.]]</th>
			<?php
			}
			?></tr>
		<!--LIST:items-->
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
			<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
			<td nowrap align="left"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">
					[[|items.id|]]</td>
				<td nowrap align="left"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">
					[[|items.room_name|]] ([[.date.]]:[[|items.arrival_time|]] - [[|items.arrival_time|]] / [[.status.]]: [[|items.status|]])</td>
				<td nowrap align="center"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">
					[[|items.date|]]</td>
<td nowrap align="left"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">
					[[|items.reservation_room_id|]]</td>
				<td nowrap align="right" title="[[|items.act_total|]]">
					[[|items.total|]][[|items.fix|]]
				</td>
				<td nowrap align="center">
					<?php if([[=items.user_name=]]){?><a href="?page=user&id=[[|items.user_id|]]"?>[[|items.user_id|]]</a><?php } ?></td>
				<td nowrap align="center" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					<?php if([[=items.last_modifier_name=]]){?><a href="?page=user&id=[[|items.last_modifier_id|]]"?>[[|items.last_modifier_id|]]</a><?php } ?></td>
			    <?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><td nowrap align="center">
				&nbsp;<a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',      
)+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/cms/skins/default/images/admin/Icon/edit2.png" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td nowrap align="center">
				&nbsp;<a href="<?php echo Url::build_current(array('reservation_room_id', 'room_id', 'employee_id', 
'start','time_end', 'total_start','total_end', 'currency'
)+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]" width="12" height="12" border="0"></a> 
			</td>
			<?php
			}
			?></tr>
		<!--/LIST:items-->
	</table>
	[[|paging|]]
	</form>
</div>
<script>
	jQuery('#time_start').datepicker();
	jQuery('#time_end').datepicker();
</script>