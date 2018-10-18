<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="EquipmentInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('compensation_invoice');?></td>
			<?php if(User::can_add(false,ANY_CATEGORY)) { ?>
            <td align="right"  width="25%" style="padding-right: 30px;">
				<a href="<?php echo Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id','housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'));?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>	       
			<?php }
			if(User::can_delete(false,ANY_CATEGORY)) {?>
				<input type="submit" name="delete_selected" class="w3-btn w3-red" value="<?php echo Portal::language('delete_selected');?>" style="text-transform: uppercase;" />
			</td>
			<?php } ?>
        </tr>
    </table>
    <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
    <fieldset>
        <legend class="title"><?php echo Portal::language('search');?></legend>
        <table>
    	<tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td nowrap="nowrap"><?php echo Portal::language('hotel');?></td>
            <td style="margin: 0;"><select  name="portal_id" id="portal_id" style=" height: 24px; margin-right: 20px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            
    		<td align="right" ><?php echo Portal::language('room');?></td>
    		<td ><select  name="room_id" id="room_id" style="width:180px; height: 24px; margin-right: 20px;"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select>	</td>
    		<td align="right" >
                <?php echo Portal::language('date_from');?> :
                <input  name="time_start" id="time_start" size="8" onchange="changevalue();" style=" height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_start'));?>">
    			<?php echo Portal::language('date_to');?> :
                
    			<input  name="time_end" id="time_end" size="8" onchange="changefromday();" style=" height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('time_end'));?>">	
                
    		</td>
    		<td><input class="w3-btn w3-gray" type="submit" value="<?php echo Portal::language('search');?>" style=" height: 24px; padding-top: 5px; margin-left: 20px;" /></td>
    	</tr>
    	</table>
    </fieldset>
    
		
	<table width="100%"><tr><td align="right"><?php echo Portal::language('price_unit');?> <?php echo HOTEL_CURRENCY; ?></td></tr></table>
    
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#C6E2FF" border="1">
		<tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
			<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
			<th align="center" width="30px"><?php echo Portal::language('no');?></th>
            <th align="center" width="100px"><?php echo Portal::language('code');?></th>
            <th align="center" width="100px"><?php echo Portal::language('code_hand');?></th>
			<th align="center" width="200px"><?php echo Portal::language('guest_name');?></th>
			<th align="center" width="100px"><?php echo Portal::language('room_id');?></th>
			<th align="center" width="100px"><?php echo Portal::language('date');?></th>
            <th align="center" width="100px"><?php echo Portal::language('total');?></th>
            <th align="center" width="250px"><?php echo Portal::language('note');?></th>
			<th align="center" width="150px"><?php echo Portal::language('user');?></th>
			<th align="center" width="150px"><?php echo Portal::language('modifier');?></th>
			<?php if(User::can_edit(false,ANY_CATEGORY)) { ?>
            <th align="center" width="50px"><?php echo Portal::language('edit');?></th>
			<?php }
			if(User::can_delete(false,ANY_CATEGORY)){?>
            <th align="center" width="50px"><?php echo Portal::language('delete');?></th>
			<?php } ?>
        </tr>
		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:hand;">
            <td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"/></td>
			<td align="center"><?php echo $this->map['items']['current']['rownumber'];?></td>
            <td align="center"><strong>EQ_<?php echo $this->map['items']['current']['position'];?></strong></td>
            <td align="left"><?php echo $this->map['items']['current']['code'];?></td>
			<td align="left"><?php echo $this->map['items']['current']['reservation_room_id'];?></td>
            <td align="center"><?php echo $this->map['items']['current']['minibar'];?></td>
            <td align="center"><?php echo $this->map['items']['current']['date'];?></td>
            <td align="right"><?php echo $this->map['items']['current']['total'];?></td>
            <td><?php echo $this->map['items']['current']['note'];?></td>
			<td align="center">
				<?php if($this->map['items']['current']['user_name']){?>
                <a href="?page=user&id=<?php echo $this->map['items']['current']['user_id'];?>"><?php echo $this->map['items']['current']['user_name'];?></a>
                <?php } ?>
            </td>
			<td align="center" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}">
				<?php if($this->map['items']['current']['last_modifier_name']){?>
                <a href="?page=user&id=<?php echo $this->map['items']['current']['last_modifier_id'];?>"><?php echo $this->map['items']['current']['last_modifier_name'];?></a>
                <?php } ?>
            </td>
			<?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td align="center">
            <?php if(User::can_admin(false,ANY_CATEGORY) || $this->map['items']['current']['status'] !='CHECKOUT'){?>
                <a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',)+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
            <?php }?>
            </td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY)){
			?>
            <td align="center">
                <a href="<?php echo Url::build_current(array('reservation_room_id', 'room_id', 'employee_id', 'start','time_end', 'total_start','total_end', 'currency')+array('cmd'=>'delete','id'=>$this->map['items']['current']['id'])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a> 
			</td>
			<?php } ?>
        </tr>
		<?php }}unset($this->map['items']['current']);} ?>
	</table>
	<?php echo $this->map['paging'];?>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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