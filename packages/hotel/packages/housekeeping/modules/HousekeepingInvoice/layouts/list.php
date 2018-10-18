<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<font class="form_title"><b>[[.list_title.]]</b></font>
	<form method="post" name="SearchHousekeepingInvoiceForm">
	<table>
	<tr>
		<td align="right" nowrap>[[.minibar_id.]]</td>
		<td>:</td>
		<td nowrap><select name="minibar_id" id="minibar_id" style="width:180px">
		  </select></td>
		<td align="right" nowrap>[[.time.]]</td>
		<td>:</td>
		<td nowrap>
				<input name="time_start" type="text" id="time_start" size="12">
			<a href="javascript:void(0);" name="time_start_date_in" onclick="cal.select(this.input,'time_start_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" title="[[.select_date.]]"></a>
			<script>
				var inputs = document.getElementsByTagName("input");
				var anchors = document.getElementsByTagName("a");
				anchors[anchors.length-1].input = inputs[inputs.length-1];
			</script>
			[[.to.]]
			<input name="time_end" type="text" id="time_end" size="12">
			<a href="javascript:void(0);" name="time_end_date_in" onclick="cal.select(this.input,'time_end_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" title="[[.select_date.]]"></a>
			<script>
				var inputs = document.getElementsByTagName("input");
				var anchors = document.getElementsByTagName("a");
				anchors[anchors.length-1].input = inputs[inputs.length-1];
			</script>		
		</td>
		<td><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchHousekeepingInvoiceForm');?></td>
		</tr>
	</table>
	</form>
	<form name="HousekeepingInvoiceListForm" method="post">
	<table><tr>
		<?php if(User::can_add(false,ANY_CATEGORY))
		{
		?><td><?php Draw::button(Portal::language('add_new'),Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'
)));?></td>
		<?php
		}
		if(User::can_delete(false,ANY_CATEGORY))
		{
		?><td><?php Draw::button(Portal::language('delete_selected'),'delete_selected',false,true,'HousekeepingInvoiceListForm');?></td>
		<?php
		}
		?></tr></table>
	<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#7F9DB9" border="1" style="border-collapse:collapse">
		<tr valign="middle" style="line-height:20px" bgcolor="#ECE9D8">
			<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
			<th>&nbsp;</th>
			<th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room_id'));?>">
				<?php if(URL::get('order_by')=='reservation_room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.reservation_room_id.]]
				</a>
			</th><th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='minibar.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'minibar.name'));?>">
				<?php if(URL::get('order_by')=='minibar.name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.minibar_id.]]
				</a>
			</th><th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.time'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]
				</a>
			</th><th nowrap align="right">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total*housekeeping_invoice.exchange_rate' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total*housekeeping_invoice.exchange_rate'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.total*housekeeping_invoice.exchange_rate') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.total.]]
				</a>
			</th><th nowrap align="right">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.prepaid*housekeeping_invoice.exchange_rate' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.prepaid*housekeeping_invoice.exchange_rate'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.prepaid*housekeeping_invoice.exchange_rate') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.prepaid.]]
				</a>
			</th>
			<th nowrap align="right">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='(housekeeping_invoice.total-housekeeping_invoice.prepaid)*housekeeping_invoice.exchange_rate' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'(housekeeping_invoice.total-housekeeping_invoice.prepaid)*housekeeping_invoice.exchange_rate'));?>">
				<?php if(URL::get('order_by')=='(housekeeping_invoice.total-housekeeping_invoice.prepaid)*housekeeping_invoice.exchange_rate') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.remain.]]
				</a>
			</th>
			<th nowrap align="right">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='user.user_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'user.user_name'));?>">
				<?php if(URL::get('order_by')=='user.user_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.user.]]
				</a>
			</th>
			<th nowrap align="right">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='last_modifier_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'user.user_name'));?>">
				<?php if(URL::get('order_by')=='last_modifier_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.modifier.]]
				</a>
			</th>
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
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>style="cursor:hand;">
			<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
			<td><?php 
				if([[=items.status=]]=='CHECKOUT')
				{
					echo '<img src="skins/default/images/check.gif"/>';
				}
				else
				{
					echo '<a href="'.URL::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
	'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',
	'id'=>[[=items.id=]],'cmd'=>'checkout')).'" title="'.Portal::language('click_to_checkout').'"><img src="skins/default/images/down.gif"/></a>';
				}
			?></td>
			<td nowrap align="left" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					[[|items.reservation_room_id|]]
				</td><td nowrap align="left" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					[[|items.minibar|]]
				</td><td nowrap align="left" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					[[|items.date|]]
				</td><td nowrap align="right" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					[[|items.total|]]
				</td>
				<td nowrap align="right" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					[[|items.prepaid|]]
				</td>
				<td nowrap align="right" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					[[|items.remain|]]
				</td>
				<td nowrap align="right" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					<?php if([[=items.user_name=]]){?><a href="?page=user&id=[[|items.user_id|]]"?>[[|items.user_name|]]</a><?php } ?></td>
				<td nowrap align="right" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
					<?php if([[=items.last_modifier_name=]]){?><a href="?page=user&id=[[|items.last_modifier_id|]]"?>[[|items.last_modifier_name|]]</a><?php } ?></td>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><td nowrap>
				&nbsp;<a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',      
)+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td nowrap>
				&nbsp;<a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'
)+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a> 
			</td>
			<?php
			}
			?></tr>
		<!--/LIST:items-->
	</table>
	[[|paging|]]
	<table><tr>
		<?php if(User::can_add(false,ANY_CATEGORY))
		{
		?><td><?php Draw::button(Portal::language('add_new'),Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'
)));?></td>
		<?php
		}
		if(User::can_delete(false,ANY_CATEGORY))
		{
		?><td><?php Draw::button(Portal::language('delete_selected'),'delete_selected',false,true,'HousekeepingInvoiceListForm');?></td>
		<?php
		}
		?></tr></table>
	</form>
</div>