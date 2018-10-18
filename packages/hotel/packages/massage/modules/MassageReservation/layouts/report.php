<style>
tr td{
    padding-top: 5px;
    padding-bottom: 5px;
}
</style>
<form name="MassageReservationReportForm" method="post">
<div class="massage-daily-summary-bound">
	<table cellpadding="15" cellspacing="0" border="0" bordercolor="#CCCCCC" class="table-bound" style="width: 1200px; margin: 0px auto;">
		<tr>
        	<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.massage_reservation.]]</td>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="20%" align="right" style="padding-right: 30px;"><a class="w3-btn w3-red" onclick="if(confirm('[[.are_you_sure.]]?')){MassageReservationReportForm.submit();}else{return false;}" style="text-transform: uppercase; text-decoration: none;">[[.delete.]]</a></td><?php }?>
        </tr>
    </table>  
	<div class="body">
		<br />
		<div class="select-date">
        <fieldset style="width: 1200px; margin: 0px auto;">
        <legend>[[.search.]]</legend>
			[[.date.]]: <input name="date" type="text" id="date" class="date" style="height: 24px; margin-right: 10px;" />
			[[.massage_room.]]: <select name="room_id" id="room_id" style="height: 24px;margin-right: 10px;"></select> 
			[[.staff.]]: 
			<select name="staff_id" id="staff_id" style="height: 24px;margin-right: 10px;"></select><input type="submit" value="OK" style="height: 24px; width: 50px;"/>
        </fieldset>
		</div><br />
		<div class="content">
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style=" width: 1200px; margin: 0px auto;">
                <tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
                    <th width="10px"><input id="check_all" type="checkbox" onclick="checkAll();"/></th>
                    <th width="100px">[[.invoice_code.]]</th>
                    <th align="center" width="100px">[[.recode.]]</th>
                    <th align="center" width="150px">[[.massage_room.]]</th>
                    
                    <th align="center" width="120px">[[.time_in.]]</th>
                    <th align="center" width="120px">[[.time_out.]]</th>
                    <th align="center" width="150px">[[.hotel_room.]]</th>
                    <th align="center" width="200px">[[.traveller_name.]]</th>
                    <th align="center" width="150px">[[.total_amount.]]</th>
                    <!--<th align="right" style="d">[[.payment.]]</th>-->
                    <th align="center" width="150px">[[.status.]]</th>
                    <th align="center" width="50px">[[.view.]]</th>
                    <th align="center" width="50px">[[.edit.]]</th>
                </tr>
                <!--LIST:items-->
                <tr>
                    <!--IF:cond([[=items.check_payment=]]==0)-->
                        <td><input name="item_check[]" type="checkbox" class="item-check" value="[[|items.reservation_room_id|]]"/></td>
                    <!--ELSE-->
                        <td></td>
                    <!--/IF:cond-->
                    <!--IF:cond1([[=items.first_items=]]==1)-->
                    <td rowspan="<?php echo [[=items.count_items=]]; ?>">[[|items.reservation_room_id|]]</td>
                    <td rowspan="<?php echo [[=items.count_items=]]; ?>"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.mrr_id|]]">[[|items.mrr_id|]]</a></td>
                    <!--/IF:cond1-->
                    <td>[[|items.room_name|]]</td>
                    <td><?php echo date('d/m/Y H:i\'',[[=items.time_in=]]);?></td>
                    <td><?php echo date('d/m/Y H:i\'',[[=items.time_out=]]);?></td>
                    <!--IF:cond1([[=items.check_ht_room_same=]]==1)-->
                    <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center">[[|items.hotel_room_name|]]</td> 
                    <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="left">[[|items.traveller_name|]]</td>
                    <!--/IF:cond1-->
                    <!--IF:cond1([[=items.first_items=]]==1)-->
                    <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="right"> <?php echo System::display_number([[=items.total_amount_massage=]]); ?></td>
                    <!--/IF:cond1-->
                    <td align="center" class="[[|items.class|]]" style="width:100px;border:0px;">[[|items.status|]]</td>                                       
                    
                    
                    <?php 
                          if(User::can_admin(false,ANY_CATEGORY) && [[=items.reservation_room_status=]] !='CHECKOUT' or [[=items.status=]] !='CHECKOUT') 
                          {
                                if([[=items.package_id=]]!='')
                                {
                                    ?>
                                    <!--IF:cond1([[=items.first_items=]]==1)-->
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=items.reservation_room_id=]],'package_id'=>[[=items.package_id=]],'rr_id'=>[[=items.ht_reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center">
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','room_id'=>[[=items.room_id=]],'id'=>[[=items.reservation_room_id=]],'package_id'=>[[=items.package_id=]],'rr_id'=>[[=items.ht_reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                                        </td>
                                    <!--/IF:cond1-->
                                    
                                    <?php 
                                }
                                else
                                {
                                    ?>
                                    <!--IF:cond1([[=items.first_items=]]==1)-->
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=items.reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center">
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','room_id'=>[[=items.room_id=]],'id'=>[[=items.reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                                        </td>
                                    <!--/IF:cond1-->
                                    
                                    <?php 
                                }
                                
                          }
                          else {
                            if([[=items.package_id=]]!='')
                                {
                                    ?>
                                    <!--IF:cond1([[=items.first_items=]]==1)-->
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=items.reservation_room_id=]],'package_id'=>[[=items.package_id=]],'rr_id'=>[[=items.ht_reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center">&nbsp;</td>
                                    <!--/IF:cond1-->
                                    
                                    <?php 
                                }
                                else
                                {
                                    ?>
                                     <!--IF:cond1([[=items.first_items=]]==1)-->
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center"><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=items.reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/list_button.gif" /></a></td>
                                        <td rowspan="<?php echo [[=items.count_items=]]; ?>" align="center">&nbsp;</td>
                                    <!--/IF:cond1-->
                                    
                                    <?php 
                                }
                          }
                          
                    ?>
                    
                </tr>
                <!--/LIST:items-->                    
            </table>
			<div class="paging">[[|paging|]]</div>
		</div>
	</div>
</div>
</form>
<script>
	jQuery("#check_all").click(function (){
		var check  = this.checked;
		jQuery(".item-check").each(function(){
			this.checked = check;
		});
	});
    function openPayment(id,total_amount,pay_with_room)
    {
        //openWindowUrl('form.php?block_id=718&cmd=cancel&invoice_id='+jQuery('#id_'+index).val(),Array('cancel','cancel_for',80,210,950,500));
        if(pay_with_room === 0)
        {
            openWindowUrl('form.php?block_id=428&id='+id+'&type=SPA&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));   
        }
        else
        {
            alert('<?php echo Portal::language('this_bill_is_moved_on_room'); ?>');
        }
    }
	jQuery("#date").datepicker();
</script>
