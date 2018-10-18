<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="MinibarInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" class="table-bound">
		<tr>
        	<td width="75%" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 30px;"></i> <?php echo Portal::language('pland_of_month');?></td>
			<?php if(User::can_add(false,ANY_CATEGORY))
			{
			?>
            <td style="text-align: right; padding-right: 50px;">
				<a href="<?php echo Url::build_current(array('cmd'=>'add'));?>" style="width: 70px; margin-right: 10px; text-transform: uppercase;text-decoration: none;" class="w3-btn w3-cyan w3-text-white"><?php echo Portal::language('Add');?></a>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
				<input type="submit" name="delete_selected" class="w3-btn w3-red" value="<?php echo Portal::language('delete_selected');?>" style="width: 70px; text-transform: uppercase;" />
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
			<th style="width: 40px; text-align: center;"><?php echo Portal::language('year');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('units_built');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('room_repair');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('rooms_available_for_sale');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('rooms_sold');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('complimentary_rooms');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('total_rooms_occupied');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('house_use_rooms');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('no_of_guests');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('room_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('bar_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('telephone_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('laundry_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('minibar_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('transport_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('spa_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('others_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('vending_revenue');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('total_hotel_revenue');?></th>
            <th style="width: 50px; text-align: center;"><?php echo Portal::language('edit');?></th>
			</tr>
		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
            <td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"/></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo $this->map['items']['current']['year'];?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['units_built']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['room_repair']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['rooms_available_for_sale']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['rooms_sold']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['complimentary_rooms']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['total_rooms_occupied']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['house_use_rooms']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['no_of_guests']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['room_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['bar_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['telephone_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['laundry_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['minibar_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['transport_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['spa_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['others_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['vending_revenue']);?></td>
            <td style="text-align: right;padding-right: 3px;"><?php echo System::display_number($this->map['items']['current']['room_revenue']
                                                                                                    +$this->map['items']['current']['bar_revenue']
                                                                                                    +$this->map['items']['current']['telephone_revenue']
                                                                                                    +$this->map['items']['current']['laundry_revenue']
                                                                                                    +$this->map['items']['current']['minibar_revenue']
                                                                                                    +$this->map['items']['current']['transport_revenue']
                                                                                                    +$this->map['items']['current']['spa_revenue']
                                                                                                    +$this->map['items']['current']['others_revenue']
                                                                                                    +$this->map['items']['current']['vending_revenue']
                                                                                                    );?></td>
        <td style="text-align: center;">
            <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
        </td>    
        </tr>
		<?php }}unset($this->map['items']['current']);} ?>
	</table>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
	function submit()
    {
        MinibarInvoiceListForm.submit();
    }
</script>
