<script>
	HousekeepingEquipment_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	};
</script>
<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-bath w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('manage_housekeeping_equipment');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){ ?>
		<td align="right"  width="45%" style="padding-right: 30px;">
            <a href="<?php echo	Url::build_current(array('cmd'=>'add')+array('housekeeping_equipment_old_store_id')); ?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
        
		<?php } if(User::can_delete(false,ANY_CATEGORY)){?>
		
            <input type="button" onclick="HousekeepingEquipmentListForm.submit();" name="delete_selected" class="w3-btn w3-red" value="<?php echo Portal::language('delete');?>" style="text-transform: uppercase; margin-right: 5px;" />
		
		<?php }?>
        <input type="button" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_damaged')+array('room_id'));?>'" class="w3-btn w3-lime" value="<?php echo Portal::language('list_damaged');?>" style="text-transform: uppercase;" />
        </td>
	</tr>
</table>
<table cellspacing="0" width="100%">
	<tr valign="top">
        <td width="100%">
        	
            <fieldset>
        	<legend class="" style="text-transform: uppercase;;"><b><?php echo Portal::language('search_options');?></b></legend>
            <table>
                <form method="post" name="SearchHousekeepingEquipmentForm"> 
                	<tr>
                        <!--Start Luu Nguyen Giap add portal -->
                        <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                        <td nowrap="nowrap"><?php echo Portal::language('hotel');?></td>
                        <td style="margin: 0;"><select  name="portal_id" id="portal_id" style="height: 26px;"><?php
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
                		<td align="right" nowrap><?php echo Portal::language('room_id');?></td>
                		<td>:</td>
                		<td nowrap><select  name="room_id" id="room_id" style="width:100px; height: 26px;"><?php
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
	</select></td>
                		<td align="right" nowrap><?php echo Portal::language('product_code');?></td>
                		<td>:</td>
                		<td nowrap><input  name="product_id" id="product_id" size="30" style="height: 26px; margin-right: 10px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>"></td>
                		<td>
                			<?php echo Draw::button(Portal::language('search'),false,false,true,'SearchHousekeepingEquipmentForm');?></td>
                        <td>
                			<?php //echo Draw::button('Reset','?page=housekeeping_equipment');?>
                		</td>
                	</tr>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
            </table>
            </fieldset>
            
            <form name="HousekeepingEquipmentListForm" method="post">
                <table cellspacing="0" width="100%">
                    <tr>
                    	<td width="100%">
                        	<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
                        		<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
                        			<th width="15px" title="<?php echo Portal::language('check_all');?>">
                                        <input type="checkbox" value="1" id="HousekeepingEquipment_check_0" onclick="check_all('HousekeepingEquipment','HousekeepingEquipment_array_items','#FFFFEC',this.checked);"/>
                                    </th>
                        			<th width="100px" align="center"><?php echo Portal::language('date');?></th>
                        			<th width="150px" align="center"><?php echo Portal::language('product_code');?></th>
                        			<th width="600px" align="center"><?php echo Portal::language('product_name');?></th>
                        			<th width="100px" align="center"><?php echo Portal::language('start_quantity');?></th>
                                    <th width="150px" align="center"><?php echo Portal::language('damaged_quantity');?></th>
                        			<th style="width: 50px; text-align: center;"><?php echo Portal::language('delete');?></th>
                        			<th>&nbsp;</th>
                        		</tr>
                        		<?php $room_name = '';?>
                        		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
                        		<?php if($room_name != $this->map['items']['current']['room_name']){ $room_name = $this->map['items']['current']['room_name']?>
                        		<tr class="category-group" bgcolor="#FFFF99">
                                    <td colspan="8" style="padding-left:30px;"><?php echo Portal::language('room');?>: <strong><?php echo $this->map['items']['current']['room_name'];?></strong></td>
                        		</tr>
                        		<?php }?>
                                
                        		<tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#FFFFFF';} else {echo '#FFFFFF';}?>" <?php Draw::hover('#E2F1DF');?>style="cursor:pointer;" id="HousekeepingEquipment_tr_<?php echo $this->map['items']['current']['id'];?>">
                        			<td>
                                        <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="tr_color = clickage('HousekeepingEquipment','<?php echo $this->map['items']['current']['i'];?>','HousekeepingEquipment_array_items','#FFFFEC');" id="HousekeepingEquipment_check_<?php echo $this->map['items']['current']['i'];?>"/>
                                    </td>
                        			<td align="center" onclick="location='<?php echo URL::build_current();?>&cmd=add&room_id=<?php echo $this->map['items']['current']['room_id'];?>&mi_housekeeping_equipment_detail[1][product_id]=<?php echo $this->map['items']['current']['product_id'];?>';"><?php echo $this->map['items']['current']['date'];?></td>
                        			<td align="center" onclick="location='<?php echo URL::build_current();?>&cmd=add&room_id=<?php echo $this->map['items']['current']['room_id'];?>&mi_housekeeping_equipment_detail[1][product_id]=<?php echo $this->map['items']['current']['product_id'];?>';"><?php echo $this->map['items']['current']['product_id'];?></td>
                        			<td align="left"   onclick="location='<?php echo URL::build_current();?>&cmd=add&room_id=<?php echo $this->map['items']['current']['room_id'];?>&mi_housekeeping_equipment_detail[1][product_id]=<?php echo $this->map['items']['current']['product_id'];?>';"><?php echo $this->map['items']['current']['product_name'];?></td>
                        			<td align="center" onclick="location='<?php echo URL::build_current();?>&cmd=add&room_id=<?php echo $this->map['items']['current']['room_id'];?>&mi_housekeeping_equipment_detail[1][product_id]=<?php echo $this->map['items']['current']['product_id'];?>';"><?php echo $this->map['items']['current']['quantity'];?></td>
                                    <td align="center" onclick="location='<?php echo URL::build_current();?>&cmd=add&room_id=<?php echo $this->map['items']['current']['room_id'];?>&mi_housekeeping_equipment_detail[1][product_id]=<?php echo $this->map['items']['current']['product_id'];?>';"><?php echo $this->map['items']['current']['damaged_quantity'];?></td>
                        			<td align="center"> 
                        				<a onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){ return false};" href="<?php echo Url::build_current(array('room_id')+array('cmd'=>'delete','room_id'=>$this->map['items']['current']['room_id'],'product_id'=>$this->map['items']['current']['product_id'])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
                                    </td>
                        			<td>
                        				<a class="w3-hover-text-red" href="<?php echo Url::build_current(array('room_id')+array('cmd'=>'damaged','room_id'=>$this->map['items']['current']['room_id'],'product_id'=>$this->map['items']['current']['product_id'])); ?>" style="text-decoration: none;"><?php echo Portal::language('damaged');?> <i class="fa fa-bullhorn w3-text-black w3-hover-text-red" style="font-size: 18px;"></i></a>						
                                    </td>
                        		</tr>
                        		<?php }}unset($this->map['items']['current']);} ?>
                        	</table>
                    	</td>
                    </tr>
                </table>
                <?php echo $this->map['paging'];?>
                <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><div style="float:left;padding:2px 2px 2px 10px;" ><strong><?php echo Portal::language('Select');?>:</strong></div></td>
                    <td><div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('HousekeepingEquipment','HousekeepingEquipment_array_items','#FFFFEC',1);"><?php echo Portal::language('All');?></div></td>
                    <td><div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('HousekeepingEquipment','HousekeepingEquipment_array_items','#FFFFEC',0);"><?php echo Portal::language('None');?></div></td>
                    <td><div style="padding:2px 2px 2px 10px;width:100px;font-weight:400;color:blue;cursor:pointer;" onclick="select_invert('HousekeepingEquipment','HousekeepingEquipment_array_items','#FFFFEC');"><?php echo Portal::language('select_invert');?></div></td>
                </tr>
                </table>
                <input type="hidden" name="delete_selected" value="<?php echo Portal::language('delete');?>" />
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
            
        </td>
        </tr>
	</table>
</div>	