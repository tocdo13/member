<link href="packages/hotel/packages/vending/skins/default/css/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('vending_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.vending_reservation_list.]]</td>
                    <!--LIST:area-->
					<?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <td width="1%" align="right">
                        <a href="<?php echo Url::build('automatic_vend',array('cmd'=>'add','department_id'=>[[=area.id=]],'department_code'=>[[=area.code=]],'arrival_time'=>date('d/m/Y')));?>" class="button-medium-add">[[|area.name|]]</a>
                    </td>
                    <?php }?>
                    <!--/LIST:area-->
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};BarReservationNewListForm.cmd.value='delete';BarReservationNewListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>                    
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
                <form method="post" name="SearchBarReservationNewForm"> 
                    <table>
                        <tr>
                            <td align="right" >[[.product_code.]]</td>
                            <td><input name="product_code" type="text" id="product_code" style="width:80px;" class="date-input" />                      </td>
                            <td align="right" >[[.invoice_number.]] <input name="invoice_number" type="text" id="invoice_number" class="date-input" /></td>
                            <td align="right" >[[.arrival_time.]]</td>
                            <td>
                                <input name="from_arrival_time" type="text" id="from_arrival_time" style="width:80px;" onchange="changevalue();" class="date-input"/>
                            &nbsp;[[.to.]]
                                <input name="to_arrival_time" type="text" id="to_arrival_time" onchange="changefromday();" style="width:80px;" class="date-input"/>
                            </td>
                            <td><strong>[[.area.]]</strong></td>
                            <td><select name="area_id" id="area_id"></select></td>
                            <td><input type="submit" value="[[.search.]]" /></td>
                        </tr>
                    </table>
                </form>
                </fieldset>
                
                <form name="BarReservationNewListForm" method="post">
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th align="left" width="150px">[[.time.]]/[[.time_in.]]</th>
						<th align="left" width="100px">[[.code.]]</th>
						<th align="left" width="100px">[[.agent_name.]]</th>
						<th align="left" width="80px">[[.total.]]</th>
                        <th style="width: 150px;" align="center">[[.payment_status.]]</th>
                        <th style="width: 50px;" align="center" >[[.deposit_status.]]</th>
						<th align="left" width="100px">[[.user.]]</th>
                        <th align="left" width="100px">[[.lastest_edited_user_id.]]</th>
                        <th width="3%">&nbsp;</th>
                        <th width="3%">&nbsp;</th>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                        <th width="3%">&nbsp;</th>
                        <th width="3%">&nbsp;</th>
						<?php }?>
                    </tr>
					<!--LIST:items-->
					<tr class="[[|items.row_class|]]" bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} ?>" valign="middle" <?php Draw::hover('#EFEEEE');?>  >
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]"/></td>
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]))?>');">[[|items.arrival_date|]]</td>
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]))?>');">[[|items.code|]]</td>
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]))?>');">[[|items.agent_name|]]</td>
						<td align="right" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]))?>');">[[|items.total|]]</td>
                        <td align="center" style="cursor:pointer; text-align: right; width: 200px;">
                            <?php 
                                if([[=items.pay_with_room=]]==1) echo Portal::language('pay_with_room_amount').': ('.[[=items.amount_pay_with_room=]].')';
                                else
                                    echo [[=items.payment_status=]] != 0? Portal::language('paid'):Portal::language('not_paid_yet');
                            ?>
                        </td>
                        <td align="center" style="cursor:pointer; text-align: right; width: 100px;">
                            <?php  
                                echo [[=items.deposit=]] == 0? Portal::language('not_deposit_yet'):System::display_number([[=items.deposit=]]); 
                            ?>
                        </td>
						<td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]))?>');">[[|items.user_id|]]</td>
                        <td align="left" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]))?>');">[[|items.lastest_edited_user_id|]]</td>
						<td nowrap align="center" style="width: 10px;">
                            <a onclick="openPayment([[|items.id|]],<?php echo (System::calculate_number([[=items.total=]])); ?>,0,<?php if([[=items.member_code=]]){ echo [[=items.member_code=]]; }else{ echo 0; } ?>);" >
                                <img src="packages/core/skins/default/images/buttons/rate.jpg" alt="[[.payment.]]" title="[[.payment.]]" width="12" height="12" border="0"/>
                            </a>
                        </td>
                        <td nowrap align="center" style="width: 10px;">
                            <a onclick="
                                    if([[|items.payment_status|]] == 1) 
                                    {
                                        alert('[[.this_order_has_been_paid.]]');
                                        return false;
                                    }
                                    else
                                    openDeposit([[|items.id|]],0,<?php echo (System::calculate_number([[=items.total=]])); ?>);
                                " >
                                <img src="packages/core/skins/default/images/buttons/copy.png" alt="[[.deposit.]]" title="[[.deposit.]]" width="12" height="12" border="0"/>
                            </a>
                        </td>
                        <?php if(User::can_admin(false,ANY_CATEGORY)) {?> 
                        <td><a href="<?php echo Url::build('automatic_vend',array('cmd'=>'edit','id'=>[[=items.id=]],'department_id'=>[[=items.department_id=]],'department_code'=>[[=items.department_code=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12"/></a></td>
                        <?php } ?>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                        <td><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12"/></a></td>
						<?php } ?>
                    </tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
                <input name="cmd" type="hidden" value=""/>
                </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    function changevalue()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_arrival_time").val(jQuery("#from_arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_arrival_time").val(jQuery("#to_arrival_time").val());
        }
    }
    function openPayment(id,total_amount,pay_with_room,member_code)
    {
        //openWindowUrl('form.php?block_id=718&cmd=cancel&invoice_id='+jQuery('#id_'+index).val(),Array('cancel','cancel_for',80,210,950,500));
        if(pay_with_room == 0)
        {
            if(member_code!=0){
                openWindowUrl('form.php?block_id=428&id='+id+'&member_code='+member_code+'&type=VEND&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));
            }else{
                openWindowUrl('form.php?block_id=428&id='+id+'&type=VEND&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));
            }   
        }
        else
        {
            alert('<?php echo Portal::language('this_bill_is_moved_on_room'); ?>');
        }
    }
    //deposit
    function openDeposit(id,pay_with_room,total_amount)
    {
        if(pay_with_room == 0)
        {
            openWindowUrl('form.php?block_id=428&cmd=deposit&id='+id+'&type=VEND&total_amount='+total_amount,Array('deposit','deposit_for',80,210,950,500));   
        }
        else
        {
            alert('<?php echo Portal::language('this_bill_is_moved_on_room'); ?>');
        }
    }
    
</script>