<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="TicketInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="form-title">[[.ticket_invoice.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)) { ?>
            <td align="left" >
				<a href="<?php echo Url::build_current(array('cmd'=>'add'));?>" class="button-medium-add">[[.Add.]]</a>
	       </td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
            <td align="left" >
				<input onclick="if( !confirm('[[.are_you_sure.]]') ) return false;" type="submit" name="delete_selected" class="button-medium-delete" value="[[.delete_selected.]]" />
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
		<td align="right" nowrap>[[.ticket_area.]]</td>
		<td>:</td>
		<td nowrap><select name="ticket_area_id" id="ticket_area_id" style="width:180px;"></select></td>
		<td align="right" nowrap>[[.ticket.]]</td>
		<td>:</td>
		<td nowrap><select name="ticket_id" id="ticket_id" style="width:180px;"></select></td>
		<td align="right" nowrap>[[.from_day.]]</td>
		<td>:</td>
		<td nowrap>
			<input name="time_start" type="text" id="time_start" size="12"/>
			[[.to.]]
			<input name="time_end" type="text" id="time_end" size="12"/>
		</td>
		<td><input name="search" type="submit" id="search" value="[[.search.]]" /></td>
	</tr>
	</table>
    </fieldset>	
	<table width="100%" cellspacing="0" cellpadding="2">
        <tr><td align="right">
			[[.price_unit.]] <?php echo HOTEL_CURRENCY; ?>
		</td></tr>
    </table>
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
		<tr class="table-header">
            <th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"/></th>
            <th style="width: 10px;" align="center">[[.order_number.]]</th>
            <th style="width: 250px;" align="left">[[.ticket_name.]]</th>
            <th style="width: 50px;" align="center">[[.SL.]]</th>
            <th style="width: 100px;" align="center">[[.price.]]</th>
            <th style="width: 100px;" align="center">[[.total.]]</th>
            <th style="width: 120px;" align="center">[[.date.]]</th>
            <th style="width: 150px;" align="center">[[.ticket_area.]]</th>
            <th style="width: 100px;" align="center">[[.user.]]</th>
			<?php if(User::can_delete(false,ANY_CATEGORY)) { ?>
            <th align="center">[[.delete.]]</th>
			<?php } ?>
        </tr>
        
		<!--LIST:items-->
        <tr <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;border-top: 1px solid #CCC; border-bottom: 1px solid #CCC;" >
            <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/></td>
            <td style="cursor:pointer; text-align: center;">[[|items.i|]]</td>
            <td style="cursor:pointer; text-align: left;">[[|items.ticket_name|]]</td>
            <td style="cursor:pointer; text-align: center;">[[|items.quantity|]]</td>
            <td style="cursor:pointer; text-align: right;">[[|items.price|]]</td>
            <td style="cursor:pointer; text-align: right;">[[|items.total|]]</td>
            <td style="cursor:pointer; text-align: center;"><?php echo  [[=items.time=]]? date('H\h:i d/m/Y',[[=items.time=]]):'' ; ?></td>
            <td style="cursor:pointer; text-align: left;">[[|items.ticket_area_name|]]</td>
            <td style="cursor:pointer; text-align: left;">[[|items.user_id|]]</td>
			<?php if(User::can_delete(false,ANY_CATEGORY)) { ?>
            <td nowrap align="center">
                <a onclick="if( !confirm('[[.are_you_sure.]]') ) return false;" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]],'ticket_area_id','ticket_id','time_start','time_end')); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]" width="12" height="12" border="0"></a> 
			</td>
			<?php } ?>
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