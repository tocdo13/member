<script>
	LaundryInvoice_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form name="LaundryInvoiceListForm" method="post">
<div class="body">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
        	<td width="75%" style="text-transform: uppercase; font-size: 20px;" ><i class="fa fa-folder-open w3-text-orange" style="font-size: 30px;"></i> <?php echo Portal::language('Laundry_invoices');?></td>
        	<?php if(User::can_add(false,ANY_CATEGORY)){ ?>
        	<td align="right" style="padding-right: 50px;">
        		<a href="<?php echo	Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id'));?>" class="w3-btn w3-cyan w3-text-white" style="width: 70px; text-transform: uppercase; margin-right: 10px; text-decoration: none;"><?php echo Portal::language('Add');?></a>        	
            <?php 
        	}
        	if(User::can_delete(false,ANY_CATEGORY))
        	{ ?>
                <input type="submit" name="delete_selected" class="w3-btn w3-red" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')) return false;" value="<?php echo Portal::language('delete');?>" style="width: 70px; text-transform: uppercase; text-decoration: none;" />
    		</td>
        	<?php }?>
        </tr>
    </table>
    
    <fieldset>
    <legend class="w3-text-indigo"><?php echo Portal::language('search');?></legend>
	<table>
		<tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td nowrap="nowrap" style="padding-left: 30px;"><?php echo Portal::language('hotel');?></td>
            <td style="margin: 0;"><select  name="portal_id" id="portal_id" onchange="submit();" style="height: 30px; margin-right: 20px;"><?php
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
            
			<td align="right"><?php echo Portal::language('room_id');?></td>
			<td></td>
			<td>
                <select  name="room_id" id="room_id" style="width:80px; height: 30px; margin-right: 20px;"><?php
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
	</select>
			</td>
			<td align="right" ><?php echo Portal::language('from_day');?></td>
            <td></td>
            <td>
                <input  name="time_start" id="time_start" size="12" onchange="changevalue();" style="height: 30px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_start'));?>">
                <?php echo Portal::language('to_date');?>
                <input  name="time_end" id="time_end" size="12" onchange="changefromday();" style="height: 30px; margin-right: 20px;" / type ="text" value="<?php echo String::html_normalize(URL::get('time_end'));?>">
            </td>
            <td>
                <input class="w3-btn w3-gray" type="submit" value="<?php echo Portal::language('OK');?>" />
            </td>
            
		</tr>
	</table>
    </fieldset>
    <?php if(Form::$current->is_error()){?><div><?php echo Form::$current->error_messages();?></div><?php }?><br />
	<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">	
		<tr class="w3-light-gray" style="height: 25px; text-transform: uppercase;">
			<th width="20px" title="<?php echo Portal::language('check_all');?>">
                <input type="checkbox" value="1" id="LaundryInvoice_check_0" onclick="check_all('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC',this.checked);"/>
            </th>            
			<th width="40px" style="text-align: center;"><?php echo Portal::language('code');?></th>            
            <th width="100px" style="text-align: center;"><?php echo Portal::language('code_hand');?></th>            
			<th width="350px"style="text-align: center;"><?php echo Portal::language('room_name');?></th>            
			<th width="100px" align="center"><?php echo Portal::language('date');?></th>            
			<th width="150px" style="text-align: center;"><?php echo Portal::language('guest_name');?></th>           
			<th width="150px" align="center"><?php echo Portal::language('amount');?></th>           
			<th style="text-align: center; width: 100px;"><?php echo Portal::language('user');?></th>
			<th align="center" style="width: 100px;"><?php echo Portal::language('modifier');?></th>            
			<th style="width: 50px; text-align: center;"><?php echo Portal::language('view');?></th>            
			<?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <th style="width: 50px; text-align: center;"><?php echo Portal::language('edit');?></th>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?>
            <th style="width: 50px; text-align: center;"><?php echo Portal::language('delete');?></th>
			<?php } ?>
        </tr>
        
		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
		<tr bgcolor="<?php if((Url::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:pointer;" id="LaundryInvoice_tr_<?php echo $this->map['items']['current']['id'];?>">
			<td>
                <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="tr_color = clickage('LaundryInvoice','<?php echo $this->map['items']['current']['i'];?>','LaundryInvoice_array_items','#FFFFEC');" id="LaundryInvoice_check_<?php echo $this->map['items']['current']['i'];?>"/>
            </td>
            
			<td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');">LD_<?php echo $this->map['items']['current']['position'];?></td>
            <td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');"><?php echo $this->map['items']['current']['code'];?></td>
			
            <td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');"><?php echo $this->map['items']['current']['room_name'];?> (<?php echo Portal::language('date');?>:<?php echo $this->map['items']['current']['arrival_time'];?> - <?php echo $this->map['items']['current']['departure_time'];?> / <?php echo Portal::language('status');?>: <?php echo $this->map['items']['current']['status'];?>)</td>
            
			<td align="center" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');"><?php echo $this->map['items']['current']['time'];?></td>	
            		
			<td onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');"><?php echo $this->map['items']['current']['name'];?></td>
            
			<td align="right" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');" style="padding-right:10px;"><?php echo $this->map['items']['current']['total'];?></td>
			
            <td align="center" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');">
                <?php if($this->map['items']['current']['user_name']){?><?php echo $this->map['items']['current']['user_id'];?></a><?php } ?>
            </td>
            
			<td align="center" onclick="window.open('<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>');">
				<?php if($this->map['items']['current']['last_modifier_name']){?><?php echo $this->map['items']['current']['last_modifier_id'];?><?php } ?>
            </td>
            
			<td style="text-align: center;">
				<a target="_blank" href="<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>" style="text-align: center; text-decoration: none;">
                    <i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i>
                </a>			
            </td>
			<?php 
			if(User::can_edit(false,ANY_CATEGORY)){?>
            <td style="text-align: center;">
            	<?php 
			if(User::can_admin(false,ANY_CATEGORY)|| $this->map['items']['current']['status'] !='CHECKOUT'){?>
				<a href="<?php echo Url::build_current(array('reservation_room_id')+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>" style="text-align: center; text-decoration: none;">
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
				<a onclick="if(confirm('Bạn có chắc muốn xóa hóa đơn <?php echo $this->map['items']['current']['name'];?>?')) {location='<?php echo Url::build_current(array('act'=>'delete','invoice_id'=>$this->map['items']['current']['id'],'laundry_code'=>$this->map['items']['current']['position']));?>';}" style="text-align: center; text-decoration: none;">
                    <i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i>
                </a> 
			</td>
			<?php }?>
        </tr>
		<?php }}unset($this->map['items']['current']);} ?>
        
	</table>

	<?php echo $this->map['paging'];?>
    
	<div>
		<div style="float:left;padding:2px 2px 2px 10px;" ><strong><?php echo Portal::language('Select');?>:</strong></div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC',1);"><?php echo Portal::language('All');?></div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC',0);"><?php echo Portal::language('None');?></div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="select_invert('LaundryInvoice','LaundryInvoice_array_items','#FFFFEC');"><?php echo Portal::language('select_invert');?></div>
	</div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
