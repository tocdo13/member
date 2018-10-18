<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="MinibarInvoiceListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" class="table-bound">
		<tr>
        	<td width="75%" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 30px;"></i> <?php echo Portal::language('minibars_invoice');?></td>
			<?php if(User::can_add(false,ANY_CATEGORY))
			{
			?>
            <td style="text-align: right; padding-right: 50px;">
				<a href="<?php echo Url::build_current(array('cmd'=>'add')+array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id','housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end', 'currency'));?>" style="width: 70px; margin-right: 10px; text-transform: uppercase;" class="w3-btn w3-cyan w3-text-white"><?php echo Portal::language('Add');?></a>
	        
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
	<fieldset>
    <legend class="w3-text-indigo"><?php echo Portal::language('search_options');?></legend>    
	<table cellspacing="0" cellpadding="2">
	<tr>
        <!--Start Luu Nguyen Giap add portal -->
        <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
        <td style="padding-left: 30px;"><?php echo Portal::language('hotel');?></td>
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
		<td align="right"><?php echo Portal::language('minibar_id');?></td>
		<td>:</td>
		<td><select  name="minibar_id" id="minibar_id" style="width:180px; height: 30px; margin-right: 20px;"><?php
					if(isset($this->map['minibar_id_list']))
					{
						foreach($this->map['minibar_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('minibar_id',isset($this->map['minibar_id'])?$this->map['minibar_id']:''))
                    echo "<script>$('minibar_id').value = \"".addslashes(URL::get('minibar_id',isset($this->map['minibar_id'])?$this->map['minibar_id']:''))."\";</script>";
                    ?>
	
		  </select>	</td>
		<td align="right"><?php echo Portal::language('from_day');?></td>
		<td>:</td>
		<td>
				<input  name="time_start" id="time_start" size="12" onchange="changevalue();" style="height: 30px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_start'));?>">
			<?php echo Portal::language('to');?>
			<input  name="time_end" id="time_end" size="12" onchange="changefromday();" style="height: 30px; margin-right: 20px;" / type ="text" value="<?php echo String::html_normalize(URL::get('time_end'));?>">
            	
		</td>
		<td><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchMinibarInvoiceForm');?></td>
		</tr>
	</table>
    </fieldset>	
	<table width="100%" cellspacing="0" cellpadding="2">
        <tr>
    		<td align="right">
    			<?php echo Portal::language('price_unit');?> <?php echo HOTEL_CURRENCY; ?>
    		</td>
        </tr>
    </table>
	<table width="100%" cellpadding="2" cellspacing="0" bordercolor="lightgray" border="1">
		<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
			<th style="width: 20px;"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
			<th style="width: 40px; text-align: center;"><?php echo Portal::language('id');?></th>
            <th style="width: 70px; text-align: center;"><?php echo Portal::language('code_hand');?></th>
			<th style="width: 250px; text-align: center;">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>">
				<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('room');?></a>
			</th>
            <th style="width: 130px; text-align: center;"><?php echo Portal::language('note');?></th>
			<th style="width: 70px; text-align: center;">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.time'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('date');?></a>
			</th>
			<th style="width: 150px; text-align: center;">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room_id'));?>">
				<?php if(URL::get('order_by')=='reservation_room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('guest_name');?>				</a>			</th>
			<th style="width: 70px; text-align: center;">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total'));?>">				</a>
                <a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total'));?>">
				<?php if(URL::get('order_by')=='housekeeping_invoice.total') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				</a><a href="<?php echo URL::build_current(((URL::get('order_by')=='housekeeping_invoice.total' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'housekeeping_invoice.total'));?>"><?php echo Portal::language('total');?>				</a>
			</th>
			<th style="width: 80px; text-align: center;">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='user' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'user'));?>">
				<?php if(URL::get('order_by')=='user') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('user');?>				</a>			</th>
			<th style="width: 80px; text-align: center;">
				<?php echo Portal::language('modifier');?>			</th>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><th style="width: 50px; text-align: center;"><?php echo Portal::language('edit');?></th>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><th style="width: 50px; text-align: center;"><?php echo Portal::language('delete');?></th>
			<?php
			}
			?></tr>
		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		<tr bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
            <td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"/></td>
			<td onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:pointer;">MN_<?php echo $this->map['items']['current']['position'];?></td>
            <td onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:pointer;"><?php echo $this->map['items']['current']['code'];?></td>
            <td onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:pointer;">
			  <?php echo $this->map['items']['current']['room_name'];?> (<?php echo Portal::language('date');?>:<?php echo $this->map['items']['current']['arrival_time'];?> - <?php echo $this->map['items']['current']['departure_time'];?> / <?php echo Portal::language('status');?>: <?php echo $this->map['items']['current']['status'];?>)</td>
              <td align="center" onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}"><?php echo $this->map['items']['current']['note'];?></td>
				<td align="center"onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:pointer;">
					<?php echo $this->map['items']['current']['date'];?></td>
                <td onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current(array('reservation_room_id')+array('cmd'=>'detail','id'=>$this->map['items']['current']['id']));?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:pointer;">
					<?php echo $this->map['items']['current']['reservation_room_id'];?></td>
				<td align="right" title="<?php echo $this->map['items']['current']['act_total'];?>">
					<?php echo $this->map['items']['current']['total'];?><?php echo $this->map['items']['current']['fix'];?>
				</td>
				<td align="center">
					<?php if($this->map['items']['current']['user_name']){?><a style="text-decoration: none;"><?php echo $this->map['items']['current']['user_id'];?></a><?php } ?></td>
				<td align="center">
					<?php if($this->map['items']['current']['last_modifier_name']){?><a style="text-decoration: none;"><?php echo $this->map['items']['current']['last_modifier_id'];?></a><?php } ?></td>
			<?php if(User::can_edit(false,ANY_CATEGORY) )
			{
			?><td align="center">
            	<?php if($this->map['items']['current']['status'] !='CHECKOUT' || User::can_admin(false,ANY_CATEGORY) )
			    { ?>
				&nbsp;<a href="<?php echo Url::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_room_id', 'housekeeping_invoice_employee_id', 
'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',      
)+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
                <?php }?>
                </td>
			
            <?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td align="center">
				&nbsp;<a href="<?php echo Url::build_current(array('reservation_room_id', 'room_id', 'employee_id', 
'start','time_end', 'total_start','total_end', 'currency'
)+array('cmd'=>'delete','id'=>$this->map['items']['current']['id'],'minibar_code'=>$this->map['items']['current']['position'])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a> 
			</td>
			<?php
			}
			?>
        </tr>
		<?php }}unset($this->map['items']['current']);} ?>
	</table>
	<?php echo $this->map['paging'];?>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
	jQuery('#time_start').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#time_end').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
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
        MinibarInvoiceListForm.submit();
    }
</script>
