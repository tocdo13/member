<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="TicketInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="form-title">[[.ticket_invoice.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)) { ?>
            <td align="left" >
				<a href="<?php echo Url::build_current(array('cmd'=>'choose_area'));?>" class="button-medium-add">[[.Add.]]</a>
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
            <tr>
            <td>[[.from.]]<input name="time_start" type="text" id="time_start" style="width: 80px;"/>
            [[.to.]]<input name="time_end" type="text" id="time_end" style="width: 80px;"/>
            <input type="submit" name="do_search" value=" [[.search.]] "/></td>
        </tr>
        </tr>
    </table>
    
	<table width="100%" cellspacing="0" cellpadding="2">
        <tr><td align="right">
			[[.price_unit.]] <?php echo HOTEL_CURRENCY; ?>
		</td></tr>
    </table>
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
		
        
        <tr class="table-header">
            <th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"/></th>
            <th style="width: 10px;" align="center">[[.order_number.]]</th>
            <th style="width: 120px;" align="left">[[.customer_name.]]</th>
            <th style="width: 100px;" align="center">[[.total.]]</th>
            <th style="width: 20px;" align="center">[[.bill_number.]]</th>
            <th style="width: 20px;" align="center">[[.num_cancel.]]</th>
            <th style="width: 30px;" align="center">[[.payment_status.]]</th>
            <th style="width: 10px;" align="center" >[[.deposit_status.]]</th>
            <th style="width: 120px;" align="center">[[.time.]]</th>
            <th style="width: 100px;" align="center">[[.user.]]</th>
            <th style="width: 10px;" align="center" >[[.order.]]</th>
            <th style="width: 10px;" align="center" >[[.payment.]]</th>
            <th style="width: 10px;" align="center" >[[.deposit.]]</th>
			<?php if(User::can_delete(false,ANY_CATEGORY)) { ?>
            <th style="width: 10px;" align="center">[[.edit.]]</th>
            <th style="width: 10px;" align="center">[[.delete.]]</th>
			<?php } ?>
        </tr>
        
		<!--LIST:items-->
        <tr <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;border-top: 1px solid #CCC; border-bottom: 1px solid #CCC;" >
            <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/></td>
            <td align="center" style="cursor:pointer; text-align: center; width: 10px; ">[[|items.i|]]</td>
            <td align="center" style="cursor:pointer; text-align: left; width: 170px;">[[|items.customer_name|]]</td>
            <td align="center" style="cursor:pointer; text-align: right; width: 100px;">[[|items.total_all|]]</td>
            
            <td align="center" style="cursor:pointer; text-align: right; width: 100px;">[[|items.id|]]</td>
            <td align="center" style="cursor:pointer; text-align: right; width: 100px;">[[|items.num_cancel|]]</td>
            <td align="center" style="cursor:pointer; text-align: right; width: 120px;"><?php if([[=items.reservation_room_id=]] == 0) echo [[=items.payment_status=]] != 0? Portal::language('paid'):Portal::language('not_paid_yet'); else echo Portal::language('moved_on_room').' '.[[=items.room_name=]];?></td>
            <td align="center" style="cursor:pointer; text-align: right; width: 120px;"><?php  echo [[=items.deposit=]] == 0? Portal::language('not_deposit_yet'):System::display_number([[=items.deposit=]]); ?></td>
            
            <td align="center" style="cursor:pointer; text-align: right; width: 100px;"><?php echo  [[=items.time=]]? date('H\h:i d/m/Y',[[=items.time=]]):'' ; ?></td>
            <td style="cursor:pointer; text-align: left; width: 100px;">[[|items.user_id|]]</td>
            <td nowrap align="center" style="width: 10px;">
                <input  class="hidden" type="text" value="[[|items.id|]]" />
                <a href="<?php echo Url::build_current(array('cmd'=>'bill','id'=>[[=items.id=]]));?>">
                    <img src="packages/core/skins/default/images/buttons/order.png" alt="[[.bill.]]" title="[[.bill.]]" width="12" height="12" border="0"/>
                </a>
            </td>
            <td nowrap align="center" style="width: 10px;">
                <a onclick="openPayment([[|items.id|]],<?php echo (System::calculate_number([[=items.total_all=]])); ?>,<?php if([[=items.member_code=]]){ echo [[=items.member_code=]]; }else{ echo 0; } ?>);" >
                    <img src="packages/core/skins/default/images/buttons/rate.jpg" alt="[[.payment.]]" title="[[.payment.]]" width="12" height="12" border="0"/>
                </a>
            </td>
            <td nowrap align="center" style="width: 10px;">
                <a onclick="openDeposit([[|items.id|]],<?php echo (System::calculate_number([[=items.total_all=]])); ?>);" >
                    <img src="packages/core/skins/default/images/buttons/copy.png" alt="[[.deposit.]]" title="[[.deposit.]]" width="12" height="12" border="0"/>
                </a>
            </td>
			<?php if(User::can_delete(false,ANY_CATEGORY)) { ?>
            <td>   
                <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">
                    <img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" height="15"/>
                </a>
                </td>
                <td>
                <a onclick="if( !confirm('[[.are_you_sure.]]') ) return false;" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]],'ticket_reservation.id','time_start','time_end')); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]" width="12" height="12" border="0" /></a>
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
    function openPayment(id,total_amount,member_code)
    {
        if(member_code!=0){
            openWindowUrl('form.php?block_id=428&id='+id+'&member_code='+member_code+'&type=TICKET&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));   
        }else{
            openWindowUrl('form.php?block_id=428&id='+id+'&type=TICKET&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500)); 
        }
        
    }
    //deposit
    function openDeposit(id,total_amount)
    {
        openWindowUrl('form.php?block_id=428&cmd=deposit&id='+id+'&type=TICKET&total_amount='+total_amount,Array('deposit','deposit_for',80,210,950,500));
        
    }
</script>
