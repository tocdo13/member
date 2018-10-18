<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="MinibarInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="form-title">[[.amenities_invoice.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY))
			{
			?>
            <td align="left" >
				<a href="<?php echo Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id','housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'));?>" class="button-medium-add">[[.Add.]]</a>
	       </td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td align="left" >
				<input type="submit" name="delete_selected" class="button-medium-delete" value="[[.delete_selected.]]" />
			</td>
			<?php
			}
			?>
        </tr>
    </table>
	<fieldset>
    <legend class="title">[[.search_options.]]</legend>    
	<table cellspacing="0" cellpadding="2">
	<tr>
		<td align="right" nowrap>[[.room.]]</td>
		<td>:</td>
		<td nowrap><select name="room_id" id="room_id" style="width:180px;"></select></td>
		<td align="right" nowrap>[[.from_day.]]</td>
		<td>:</td>
		<td nowrap>
			<input name="time_start" type="text" id="time_start" size="12"/>
			[[.to.]]
			<input name="time_end" type="text" id="time_end" size="12"/>
		</td>
		<td><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchMinibarInvoiceForm');?></td>
		</tr>
	</table>
    </fieldset>	
	<table width="100%" cellspacing="0" cellpadding="2">
        <tr><td align="right">
			[[.price_unit.]] <?php echo HOTEL_CURRENCY; ?>
		</td></tr></table>
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
		<tr class="table-header">
			<th width="10px" align="center"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"/></th>
			<th width="10px" align="center">[[.id.]]</th>
            <th width="50px" align="center">[[.bill_number.]]</th>
			<th width="300px" align="center">[[.room.]]</th>
            <th width="100px" align="center">[[.note.]]</th>
			<th width="90px" align="center">[[.date.]]</th>
			<th width="80px" align="center">[[.reservation_room_id.]]</th>
			<th width="80px" align="center">[[.total.]]</th>
			<th width="80px" align="center">[[.user.]]</th>
			<th width="80px" align="center">[[.modifier.]]</th>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><th width="40px" align="center">[[.edit.]]</th>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><th width="40px" align="center">[[.delete.]]</th>
			<?php
			}
			?></tr>
		<!--LIST:items-->
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
            <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/></td>
			<td align="left" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">[[|items.id|]]</td>
            <td align="left" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">[[|items.code|]]</td>
            <td align="left" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">[[|items.room_name|]] ([[.date.]]:[[|items.arrival_time|]] - [[|items.departure_time|]] / [[.status.]]: [[|items.status|]])</td>
            <td align="center" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">[[|items.note|]]</td>
			<td align="center"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">[[|items.date|]]</td>
            <td align="left"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">[[|items.reservation_room_id|]]</td>
			<td align="right" title="[[|items.act_total|]]">[[|items.total|]][[|items.fix|]]</td>
			<td align="center"><?php if([[=items.user_name=]]){?><a href="?page=user&id=[[|items.user_id|]]">[[|items.user_id|]]</a><?php } ?></td>
			<td align="center" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}"><?php if([[=items.last_modifier_name=]]){?><a href="?page=user&id=[[|items.last_modifier_id|]]">[[|items.last_modifier_id|]]</a><?php } ?></td>
            <?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?>
            <td align="center">
				&nbsp;<a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id','housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',)+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"/></a>
            </td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
            <td nowrap align="center">
				&nbsp;<a href="<?php echo Url::build_current(array('reservation_room_id', 'room_id', 'employee_id','start','time_end', 'total_start','total_end', 'currency')+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]" width="12" height="12" border="0"></a> 
			</td>
			<?php
			}
			?>
        </tr>
		<!--/LIST:items-->
	</table>
	[[|paging|]]
	</form>
</div>
<script>
	jQuery('#time_start').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#time_end').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
</script>