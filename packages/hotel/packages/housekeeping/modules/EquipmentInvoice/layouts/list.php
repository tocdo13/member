<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="EquipmentInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.compensation_invoice.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)) { ?>
            <td align="right"  width="25%" style="padding-right: 30px;">
				<a href="<?php echo Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id','housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'));?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a>	       
			<?php }
			if(User::can_delete(false,ANY_CATEGORY)) {?>
				<input type="submit" name="delete_selected" class="w3-btn w3-red" value="[[.delete_selected.]]" style="text-transform: uppercase;" />
			</td>
			<?php } ?>
        </tr>
    </table>
    <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
    <fieldset>
        <legend class="title">[[.search.]]</legend>
        <table>
    	<tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td nowrap="nowrap">[[.hotel.]]</td>
            <td style="margin: 0;"><select name="portal_id" id="portal_id" style=" height: 24px; margin-right: 20px;"></select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            
    		<td align="right" >[[.room.]]</td>
    		<td ><select name="room_id" id="room_id" style="width:180px; height: 24px; margin-right: 20px;"></select>	</td>
    		<td align="right" >
                [[.date_from.]] :
                <input name="time_start" type="text" id="time_start" size="8" onchange="changevalue();" style=" height: 24px; margin-right: 20px;"/>
    			[[.date_to.]] :
                
    			<input name="time_end" type="text" id="time_end" size="8" onchange="changefromday();" style=" height: 24px;" />	
                
    		</td>
    		<td><input class="w3-btn w3-gray" type="submit" value="[[.search.]]" style=" height: 24px; padding-top: 5px; margin-left: 20px;" /></td>
    	</tr>
    	</table>
    </fieldset>
    
		
	<table width="100%"><tr><td align="right">[[.price_unit.]] <?php echo HOTEL_CURRENCY; ?></td></tr></table>
    
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1">
		<tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
			<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
			<th align="center" width="30px">[[.no.]]</th>
            <th align="center" width="100px">[[.code.]]</th>
            <th align="center" width="100px">[[.code_hand.]]</th>
			<th align="center" width="200px">[[.guest_name.]]</th>
			<th align="center" width="100px">[[.room_id.]]</th>
			<th align="center" width="100px">[[.date.]]</th>
            <th align="center" width="100px">[[.total.]]</th>
            <th align="center" width="250px">[[.note.]]</th>
			<th align="center" width="150px">[[.user.]]</th>
			<th align="center" width="150px">[[.modifier.]]</th>
			<?php if(User::can_edit(false,ANY_CATEGORY)) { ?>
            <th align="center" width="50px">[[.edit.]]</th>
			<?php }
			if(User::can_delete(false,ANY_CATEGORY)){?>
            <th align="center" width="50px">[[.delete.]]</th>
			<?php } ?>
        </tr>
		<!--LIST:items-->
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
            <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/></td>
			<td align="center">[[|items.rownumber|]]</td>
            <td align="center"><strong>EQ_[[|items.position|]]</strong></td>
            <td align="left">[[|items.code|]]</td>
			<td align="left">[[|items.reservation_room_id|]]</td>
            <td align="center">[[|items.minibar|]]</td>
            <td align="center">[[|items.date|]]</td>
            <td align="right">[[|items.total|]]</td>
            <td>[[|items.note|]]</td>
			<td align="center">
				<?php if([[=items.user_name=]]){?>
                <a href="?page=user&id=[[|items.user_id|]]">[[|items.user_name|]]</a>
                <?php } ?>
            </td>
			<td align="center" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}">
				<?php if([[=items.last_modifier_name=]]){?>
                <a href="?page=user&id=[[|items.last_modifier_id|]]">[[|items.last_modifier_name|]]</a>
                <?php } ?>
            </td>
			<?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td align="center">
            <?php if(User::can_admin(false,ANY_CATEGORY) || [[=items.status=]] !='CHECKOUT'){?>
                <a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',)+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
            <?php }?>
            </td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY)){
			?>
            <td align="center">
                <a href="<?php echo Url::build_current(array('reservation_room_id', 'room_id', 'employee_id', 'start','time_end', 'total_start','total_end', 'currency')+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a> 
			</td>
			<?php } ?>
        </tr>
		<!--/LIST:items-->
	</table>
	[[|paging|]]
	</form>
</div>
<script>
	jQuery("#time_start").datepicker();
	jQuery("#time_end").datepicker();
    function changevalue()
    {
        var myfromdate = $('time_start').value.split("/");
        var mytodate = $('time_end').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#time_end").val(jQuery("#time_start").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('time_start').value.split("/");
        var mytodate = $('time_end').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#time_start").val(jQuery("#time_end").val());
        }
    }
</script>