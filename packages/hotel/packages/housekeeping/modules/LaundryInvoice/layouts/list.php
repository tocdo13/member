<script>
	LaundryInvoice_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form name="LaundryInvoiceListForm" method="post">
<div class="body">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
        	<td width="75%" style="text-transform: uppercase; font-size: 20px;" ><i class="fa fa-folder-open w3-text-orange" style="font-size: 30px;"></i> [[.Laundry_invoices.]]</td>
        	<?php if(User::can_add(false,ANY_CATEGORY)){ ?>
        	<td align="right" style="padding-right: 50px;">
        		<a href="<?php echo	Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id'));?>" class="w3-btn w3-cyan w3-text-white" style="width: 70px; text-transform: uppercase; margin-right: 10px; text-decoration: none;">[[.Add.]]</a>        	
            <?php 
        	}
        	if(User::can_delete(false,ANY_CATEGORY))
        	{ ?>
                <input type="submit" name="delete_selected" class="w3-btn w3-red" onclick="if(!confirm('[[.are_you_sure.]]')) return false;" value="[[.delete.]]" style="width: 70px; text-transform: uppercase; text-decoration: none;" />
    		</td>
        	<?php }?>
        </tr>
    </table>
    
    <fieldset>
    <legend class="w3-text-indigo">[[.search.]]</legend>
	<table>
		<tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td nowrap="nowrap" style="padding-left: 30px;">[[.hotel.]]</td>
            <td style="margin: 0;"><select name="portal_id" id="portal_id" onchange="submit();" style="height: 30px; margin-right: 20px;"></select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            
			<td align="right">[[.room_id.]]</td>
			<td></td>
			<td>
                <select name="room_id" id="room_id" style="width:80px; height: 30px; margin-right: 20px;"></select>
			</td>
			<td align="right" >[[.from_day.]]</td>
            <td></td>
            <td>
                <input name="time_start" type="text" id="time_start" size="12" onchange="changevalue();" style="height: 30px; margin-right: 20px;"/>
                [[.to_date.]]
                <input name="time_end" type="text" id="time_end" size="12" onchange="changefromday();" style="height: 30px; margin-right: 20px;" />
            </td>
            <td>
                <input class="w3-btn w3-gray" type="submit" value="[[.OK.]]" />
            </td>
            
		</tr>
	</table>
    </fieldset>
    <?php if(Form::$current->is_error()){?><div><?php echo Form::$current->error_messages();?></div><?php }?><br />
	<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">	
		<tr class="w3-light-gray" style="height: 25px; text-transform: uppercase;">
			<th width="20px" title="[[.check_all.]]">
                <input type="checkbox" value="1" id="LaundryInvoice_check_0" onclick="check_all('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC',this.checked);"/>
            </th>            
			<th width="40px" style="text-align: center;">[[.code.]]</th>            
            <th width="100px" style="text-align: center;">[[.code_hand.]]</th>            
			<th width="350px"style="text-align: center;">[[.room_name.]]</th>            
			<th width="100px" align="center">[[.date.]]</th>            
			<th width="150px" style="text-align: center;">[[.guest_name.]]</th>           
			<th width="150px" align="center">[[.amount.]]</th>           
			<th style="text-align: center; width: 100px;">[[.user.]]</th>
			<th align="center" style="width: 100px;">[[.modifier.]]</th>            
			<th style="width: 50px; text-align: center;">[[.view.]]</th>            
			<?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <th style="width: 50px; text-align: center;">[[.edit.]]</th>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
            <th style="width: 50px; text-align: center;">[[.delete.]]</th>
			<?php } ?>
        </tr>
        
		<!--LIST:items-->
		<tr bgcolor="<?php if((Url::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:pointer;" id="LaundryInvoice_tr_[[|items.id|]]">
			<td>
                <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('LaundryInvoice','[[|items.i|]]','LaundryInvoice_array_items','#FFFFEC');" id="LaundryInvoice_check_[[|items.i|]]"/>
            </td>
            
			<td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">LD_[[|items.position|]]</td>
            <td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">[[|items.code|]]</td>
			
            <td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">[[|items.room_name|]] ([[.date.]]:[[|items.arrival_time|]] - [[|items.departure_time|]] / [[.status.]]: [[|items.status|]])</td>
            
			<td align="center" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">[[|items.time|]]</td>	
            		
			<td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">[[|items.name|]]</td>
            
			<td align="right" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');" style="padding-right:10px;">[[|items.total|]]</td>
			
            <td align="center" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">
                <?php if([[=items.user_name=]]){?>[[|items.user_id|]]</a><?php } ?>
            </td>
            
			<td align="center" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]');">
				<?php if([[=items.last_modifier_name=]]){?>[[|items.last_modifier_id|]]<?php } ?>
            </td>
            
			<td style="text-align: center;">
				<a target="_blank" href="<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>[[=items.id=]]));?>&id=[[|items.id|]]" style="text-align: center; text-decoration: none;">
                    <i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i>
                </a>			
            </td>
			<?php 
			if(User::can_edit(false,ANY_CATEGORY)){?>
            <td style="text-align: center;">
            	<?php 
			if(User::can_admin(false,ANY_CATEGORY)|| [[=items.status=]] !='CHECKOUT'){?>
				<a href="<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>" style="text-align: center; text-decoration: none;">
                    <i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i>
                </a>
             <?php } ?>   
            </td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
            <td style="text-align: center;">
				<a onclick="if(confirm('Bạn có chắc muốn xóa hóa đơn [[|items.name|]]?')) {location='<?php echo Url::build_current(array('act'=>'delete','invoice_id'=>[[=items.id=]],'laundry_code'=>[[=items.position=]]));?>';}" style="text-align: center; text-decoration: none;">
                    <i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i>
                </a> 
			</td>
			<?php }?>
        </tr>
		<!--/LIST:items-->
        
	</table>

	[[|paging|]]
    
	<div>
		<div style="float:left;padding:2px 2px 2px 10px;" ><strong>[[.Select.]]:</strong></div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC',1);">[[.All.]]</div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC',0);">[[.None.]]</div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="select_invert('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC');">[[.select_invert.]]</div>
	</div>
</div>
</form>
<script>
	jQuery('#time_start').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });;
	jQuery('#time_end').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });;
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
    function submit()
    {
        LaundryInvoiceListForm.submit();
    }
</script>
