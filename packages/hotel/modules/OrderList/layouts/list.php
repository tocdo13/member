<div>
	<div>
    	<h2>[[.all_invoices_in_date.]] [[|date|]]</h2>
    </div>
    <fieldset>
    	<legend class="title">[[.res_room_invoices.]]</legend>
        <table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
            <tr class="table-header">
	            <th width="80" align="left" nowrap="nowrap">[[.invoice_number.]]</th>
                <th align="left" nowrap="nowrap"> [[.room_name.]]</a></th>
                <th nowrap align="left">[[.time.]]/[[.creat_time.]]</th>
                <th align="center" nowrap="nowrap">[[.total.]]</th>
                <th align="center" nowrap="nowrap">[[.user_id.]]</th>
                <th nowrap align="left">[[.time.]]/[[.lastest_edit_time.]]</th>
            <!--LIST:res_room_items-->
            <?php if([[=res_room_items.group=]]==1){
        		$cond = ' id='.[[=res_room_items.r_id=]].'&cmd=group_invoice&customer_id='.[[=res_room_items.customer_id=]];
        	}else{
        		$cond = ' traveller_id='.[[=res_room_items.traveller_id=]];
        	}
        	?>
            <tr>
            	<td align="left" nowrap="nowrap" onclick="window.open('?page=view_traveller_folio&folio_id=[[|res_room_items.id|]]&<?php echo $cond;?>');">[[|res_room_items.id|]]</td>
                <td align="left" nowrap="nowrap">[[|res_room_items.room_name|]]</td>
                <td nowrap align="left">[[|res_room_items.creat_time|]]</td>
                <td align="center" nowrap="nowrap"> [[|res_room_items.total|]]</td>
                <td align="center" nowrap="nowrap">[[|res_room_items.user_id|]]</td>
                <td align="center" nowrap="nowrap"> [[|res_room_items.lastest_edit_time|]] </td>
             </tr>
            <!--/LIST:res_room_items-->
        </table>
    </fieldset><br />
	<fieldset>
    	<legend class="title">[[.bar_invoices.]]</legend>
        <table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
            <tr class="table-header">
	            <th width="80" align="left" nowrap="nowrap">[[.invoice_number.]]</th>
                <th align="left" nowrap="nowrap"> [[.agent_name.]]</a></th>
                <th nowrap align="left">[[.time.]]/[[.time_in.]]</th>
                <th align="center" nowrap="nowrap">[[.room_name.]]</th>
                <th align="center" nowrap="nowrap">[[.table_number.]]</th>
                <th align="center" nowrap="nowrap">[[.time_length.]]</th>
                <th align="left" nowrap>[[.status.]]</th>
                 <th width="100" align="center" nowrap="nowrap">[[.total.]]</th>
                <?php if(User::can_edit(false,ANY_CATEGORY)) {?><th width="1%" nowrap="nowrap"></th>
                <th align="center">[[.user.]]</th><th width="1%"></th><?php }?>
                <?php if(User::can_delete(false,ANY_CATEGORY)) {?><th width="1%"></th><?php }?></tr>
            <!--LIST:bar_items-->
            <tr>
            	<td align="left" nowrap="nowrap">[[|bar_items.id|]]</td>
                <td align="left" nowrap="nowrap">[[|bar_items.agent_name|]]</td>
                <td nowrap align="left">[[|bar_items.arrival_date|]]</td>
                <td align="center" nowrap="nowrap"> [[|bar_items.room_name|]]</td>
                <td align="center" nowrap="nowrap">[[|bar_items.table_name|]]</td>
                <td align="center" nowrap="nowrap"> [[|bar_items.time_length|]] </td>
                <td align="left" nowrap>[[|bar_items.status|]]</td>
                <td align="right" nowrap="nowrap">[[|bar_items.total|]]</td>
                <td nowrap><?php if(User::can_edit(false,ANY_CATEGORY) and ([[=bar_items.status=]]!='CHECKOUT' and [[=bar_items.status=]]!='CANCEL')) {?><a target="_blank" href="<?php echo Url::build('bar_reservation',array('act'=>'cancel','id'=>[[=bar_items.id=]])); ?>" style="color:#FF0000;text-decoration:underline;">[[.cancel_this_order.]]</a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
                <td align="center" nowrap="nowrap" width="100">[[|bar_items.user_id|]]</td>
                <?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('bar_reservation',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=bar_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"></a></td> <?php } ?>
                <?php if(User::can_delete(false,ANY_CATEGORY)) {?><td nowrap align="center"><a target="_blank" href="<?php echo Url::build('bar_reservation',array( 'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=bar_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]"></a></td>
                <?php } ?>
             </tr>
            <!--/LIST:bar_items-->
        </table>
    </fieldset><br />
    <fieldset>
    	<legend class="title">[[.minibar_invoices.]]</legend>
        <table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
		<tr class="table-header">
			<th width="80">[[.invoice_number.]]</th>
			<th nowrap align="left">[[.room.]]</th>
			<th nowrap align="center">[[.date.]]</th>
			<th nowrap width="200" align="left">[[.guest.]]</th>
			<th nowrap width="100" align="center">[[.total.]]</th>
			<th nowrap align="center" width="100">[[.user.]]</th>
			<th nowrap align="center" width="100">[[.modifier.]]</th>
			<?php if(User::can_edit(false,ANY_CATEGORY)){?><th></th><?php }
			if(User::can_delete(false,ANY_CATEGORY)){?><th></th><?php }?>
        </tr>
		<!--LIST:minibar_items-->
		<tr>
			<td nowrap align="left">
					[[|minibar_items.id|]]</td>
            <td nowrap align="left">
                [[|minibar_items.room_name|]] ([[.date.]]:[[|minibar_items.arrival_time|]] - [[|minibar_items.arrival_time|]] / [[.status.]]: [[|minibar_items.status|]])</td>
            <td nowrap align="center">
                [[|minibar_items.date|]]</td>
            <td nowrap align="left">
                [[|minibar_items.guest_name|]]</td>
            <td nowrap align="right">
                [[|minibar_items.total|]]
            </td>
            <td nowrap align="center">
                <?php if([[=minibar_items.user_name=]]){?><a href="?page=user&id=[[|minibar_items.user_id|]]"?>[[|minibar_items.user_id|]]</a><?php } ?></td>
            <td nowrap align="center">
                <?php if([[=minibar_items.last_modifier_name=]]){?><a href="?page=user&id=[[|minibar_items.last_modifier_id|]]"?>[[|minibar_items.last_modifier_id|]]</a><?php } ?></td>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('minibar_invoice',array('cmd'=>'edit','id'=>[[=minibar_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"></a></td>
			<?php }if(User::can_delete(false,ANY_CATEGORY)){?>
            <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('minibar_invoice',array('cmd'=>'delete','id'=>[[=minibar_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]"></a> 
			</td><?php }?>
         </tr>
		<!--/LIST:minibar_items-->
	</table>
    </fieldset><br />
	<fieldset>
    	<legend class="title">[[.laundry_invoices.]]</legend>
        <table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
		<tr class="table-header">
			<th width="80">[[.invoice_number.]]</th>
			<th nowrap align="left">[[.room.]]</th>
			<th nowrap align="center">[[.date.]]</th>
			<th nowrap width="200" align="left">[[.guest.]]</th>
			<th nowrap width="100" align="center">[[.total.]]</th>
			<th nowrap align="center" width="100">[[.user.]]</th>
			<th nowrap align="center" width="100">[[.modifier.]]</th>
			<?php if(User::can_edit(false,ANY_CATEGORY)){?><th></th><?php }
			if(User::can_delete(false,ANY_CATEGORY)){?><th></th><?php }?>
        </tr>
		<!--LIST:laundry_items-->
		<tr>
			<td nowrap align="left">
					[[|laundry_items.id|]]</td>
            <td nowrap align="left">
                [[|laundry_items.room_name|]] ([[.date.]]:[[|laundry_items.arrival_time|]] - [[|laundry_items.arrival_time|]] / [[.status.]]: [[|laundry_items.status|]])</td>
            <td nowrap align="center">
                [[|laundry_items.date|]]</td>
            <td nowrap align="left">
                [[|laundry_items.guest_name|]]</td>
            <td nowrap align="right">
                [[|laundry_items.total|]]
            </td>
            <td nowrap align="center">
                <?php if([[=laundry_items.user_name=]]){?><a href="?page=user&id=[[|laundry_items.user_id|]]"?>[[|laundry_items.user_id|]]</a><?php } ?></td>
            <td nowrap align="center">
                <?php if([[=laundry_items.last_modifier_name=]]){?><a href="?page=user&id=[[|laundry_items.last_modifier_id|]]"?>[[|laundry_items.last_modifier_id|]]</a><?php } ?></td>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('laundry_invoice',array('cmd'=>'edit','id'=>[[=laundry_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"></a></td>
			<?php }if(User::can_delete(false,ANY_CATEGORY)){?>
            <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('laundry_invoice',array('cmd'=>'delete','id'=>[[=laundry_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]"></a> 
			</td><?php }?>
         </tr>
		<!--/LIST:laundry_items-->
	</table>
    </fieldset><br />
    <fieldset>
    	<legend class="title">[[.conpensative_invoices.]]</legend>
        <table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
		<tr class="table-header">
			<th width="80">[[.invoice_number.]]</th>
			<th nowrap align="left">[[.room.]]</th>
			<th nowrap align="center">[[.date.]]</th>
			<th nowrap width="200" align="left">[[.guest.]]</th>
			<th nowrap width="100" align="center">[[.total.]]</th>
			<th nowrap align="center" width="100">[[.user.]]</th>
			<th nowrap align="center" width="100">[[.modifier.]]</th>
			<?php if(User::can_edit(false,ANY_CATEGORY)){?><th></th><?php }
			if(User::can_delete(false,ANY_CATEGORY)){?><th></th><?php }?>
        </tr>
		<!--LIST:conpensative_items-->
		<tr>
			<td nowrap align="left">
					[[|conpensative_items.id|]]</td>
            <td nowrap align="left">
                [[|conpensative_items.room_name|]] ([[.date.]]:[[|conpensative_items.arrival_time|]] - [[|conpensative_items.arrival_time|]] / [[.status.]]: [[|conpensative_items.status|]])</td>
            <td nowrap align="center">
                [[|conpensative_items.date|]]</td>
            <td nowrap align="left">
                [[|conpensative_items.guest_name|]]</td>
            <td nowrap align="right">
                [[|conpensative_items.total|]]
            </td>
            <td nowrap align="center">
                <?php if([[=conpensative_items.user_name=]]){?><a href="?page=user&id=[[|conpensative_items.user_id|]]"?>[[|conpensative_items.user_id|]]</a><?php } ?></td>
            <td nowrap align="center">
                <?php if([[=conpensative_items.last_modifier_name=]]){?><a href="?page=user&id=[[|conpensative_items.last_modifier_id|]]"?>[[|conpensative_items.last_modifier_id|]]</a><?php } ?></td>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('equipment_invoice',array('cmd'=>'edit','id'=>[[=conpensative_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"></a></td>
			<?php }if(User::can_delete(false,ANY_CATEGORY)){?>
            <td nowrap align="center"><a target="_blank" href="<?php echo Url::build('equipment_invoice',array('cmd'=>'delete','id'=>[[=conpensative_items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.png" alt="[[.delete.]]"></a> 
			</td><?php }?>
         </tr>
		<!--/LIST:conpensative_items-->
	</table>
    </fieldset><br />
    <fieldset>
    	<legend class="title">[[.extra_invoices.]]</legend>
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="table-header">
			  <th width="1%">[[.order_number.]]</th>
              <th width="10%" align="left">[[.bill_number.]]</th>
			  <th width="20%" align="left">[[.room.]]</th>
			  <th width="20%" align="left">[[.note.]]</th>
			  <th width="10%" align="left">[[.status.]]</th>
			  <th width="100" align="left">[[.create_time.]]</th>
			  <th width="100" align="left">[[.create_user.]]</th>
			  <th width="100" align="left">[[.lastest_edited_time.]]</th>
			  <th width="100" align="left">[[.lastest_edited_user.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:extra_service_items-->
			<tr>
			  <td>[[|extra_service_items.i|]]</td>
				<td>[[|extra_service_items.bill_number|]]</td>
				<td><strong>[[|extra_service_items.room_name|]]</strong><br />
		      [[.arrival.]]: [[|extra_service_items.arrival_date|]]-[[|extra_service_items.departure_date|]] </td>
		        <td>[[|extra_service_items.note|]]</td>
		        <td>[[|extra_service_items.status|]]</td>
		        <td>[[|extra_service_items.time|]]</td>
               <td>[[|extra_service_items.user_id|]]</td>
			   <td>[[|extra_service_items.lastest_edited_time|]]</td>
			   <td>[[|extra_service_items.lastest_edited_user_id|]]</td>
		      <td><a target="_blank" href="<?php echo Url::build('extra_service_invoice',array('cmd'=>'edit','id'=>[[=extra_service_items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td><a target="_blank" class="delete-one-item" href="<?php echo Url::build('extra_service_invoice',array('cmd'=>'delete','id'=>[[=extra_service_items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>
			</tr>
		  <!--/LIST:extra_service_items-->			
		</table>
    </fieldset><br />
    <fieldset>
    	<legend class="title">[[.spa_invoices.]]</legend>
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="table-header">
			  <th width="1%">[[.invoice_number.]]</th>
              <th width="10%" align="left">[[.massage_room.]]</th>
			  <th width="20%" align="left">[[.hotel_room.]]</th>
			  <th width="10%" align="left">[[.status.]]</th>
			  <th width="100" align="left">[[.create_time.]]</th>
			  <th width="100" align="left">[[.create_user.]]</th>
			  <th width="100" align="left">[[.lastest_edited_time.]]</th>
			  <th width="100" align="left">[[.lastest_edited_user.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:spa_items-->
			<tr>
				<td>[[|spa_items.reservation_room_id|]]</td>
				<td>[[|spa_items.room_name|]]</td>
		        <td>[[|spa_items.hotel_room_name|]]</td>
		        <td>[[|spa_items.status|]]</td>
		        <td>[[|spa_items.time|]]</td>
               <td>[[|spa_items.user_id|]]</td>
			   <td>[[|spa_items.lastest_edited_time|]]</td>
			   <td>[[|spa_items.lastest_edited_user_id|]]</td>
		      <td><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','room_id'=>[[=spa_items.room_id=]],'id'=>[[=spa_items.reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td><a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','room_id'=>[[=spa_items.room_id=]],'id'=>[[=spa_items.reservation_room_id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>
			</tr>
		  <!--/LIST:spa_items-->			
		</table>
    </fieldset>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><p>&nbsp;</p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> 
	<input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build('night_audit',array('cmd'=>'difference_price'));?>'">
	<p>&nbsp;</p></td>
  </tr>
</table>

</div>