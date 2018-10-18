<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="MinibarInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" class="table-bound">
		<tr>
        	<td width="75%" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 30px;"></i> [[.pland_of_month.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY))
			{
			?>
            <td style="text-align: right; padding-right: 50px;">
				<a href="<?php echo Url::build_current(array('cmd'=>'add'));?>" style="width: 70px; margin-right: 10px; text-transform: uppercase;text-decoration: none;" class="w3-btn w3-cyan w3-text-white">[[.Add.]]</a>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
				<input type="submit" name="delete_selected" class="w3-btn w3-red" value="[[.delete_selected.]]" style="width: 70px; text-transform: uppercase;" />
			</td>
			<?php
			}
			?>
        </tr>
    </table>
    <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="lightgray" border="1">
		<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
			<th style="width: 20px;"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
			<th style="width: 40px; text-align: center;">[[.year.]]</th>
            <th style="width: 70px; text-align: center;">[[.units_built.]]</th>
            <th style="width: 70px; text-align: center;">[[.room_repair.]]</th>
            <th style="width: 70px; text-align: center;">[[.rooms_available_for_sale.]]</th>
            <th style="width: 70px; text-align: center;">[[.rooms_sold.]]</th>
            <th style="width: 70px; text-align: center;">[[.complimentary_rooms.]]</th>
            <th style="width: 70px; text-align: center;">[[.total_rooms_occupied.]]</th>
            <th style="width: 70px; text-align: center;">[[.house_use_rooms.]]</th>
            <th style="width: 70px; text-align: center;">[[.no_of_guests.]]</th>
            <th style="width: 70px; text-align: center;">[[.room_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.bar_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.telephone_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.laundry_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.minibar_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.transport_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.spa_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.others_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.vending_revenue.]]</th>
            <th style="width: 70px; text-align: center;">[[.total_hotel_revenue.]]</th>
            <th style="width: 50px; text-align: center;">[[.edit.]]</th>
			</tr>
		<!--LIST:items-->
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
            <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo [[=items.year=]];?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.units_built=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.room_repair=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.rooms_available_for_sale=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.rooms_sold=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.complimentary_rooms=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.total_rooms_occupied=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.house_use_rooms=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.no_of_guests=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.room_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.bar_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.telephone_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.laundry_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.minibar_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.transport_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.spa_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.others_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.vending_revenue=]]);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number([[=items.room_revenue=]]
                                                                                                    +[[=items.bar_revenue=]]
                                                                                                    +[[=items.telephone_revenue=]]
                                                                                                    +[[=items.laundry_revenue=]]
                                                                                                    +[[=items.minibar_revenue=]]
                                                                                                    +[[=items.transport_revenue=]]
                                                                                                    +[[=items.spa_revenue=]]
                                                                                                    +[[=items.others_revenue=]]
                                                                                                    +[[=items.vending_revenue=]]
                                                                                                    );?></td>
        <td style="text-align: center;">
            <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
        </td>    
        </tr>
		<!--/LIST:items-->
	</table>
	</form>
</div>
<script>
	function submit()
    {
        MinibarInvoiceListForm.submit();
    }
</script>
