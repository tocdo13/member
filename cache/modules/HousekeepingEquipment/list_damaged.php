<script>
	HousekeepingEquipment_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form method="post" name="SearchHousekeepingEquipmentDamagedForm">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('list_damaged');?></td>
	</tr>
    <tr>
        <td align="right" style="padding-right: 30px;">
            <input type="button" onclick="window.location='<?php echo Url::build_current(array('room_id')); ?>'" value="<?php echo Portal::language('list_equipment');?>" style="text-transform: uppercase; margin-right: 5px;" class="w3-btn w3-green w3-text-white" />
            <input type="submit" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){ return false};" value="<?php echo Portal::language('delete');?>" class="w3-btn w3-red" style="text-transform: uppercase;" />
        </td>
    </tr>
	<tr>
		<td>
            <fieldset>
                <legend class="" style="text-transform: uppercase;"><?php echo Portal::language('search');?></legend>
        		<table>
    				<tr>    
    					<td align="right" ><?php echo Portal::language('room_id');?></td>
    					<td>:</td>
    					<td ><select  name="room_id" id="room_id" style="width:100px; height: 24px; margin-right: 20px;"><?php
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
    					<td align="right" ><?php echo Portal::language('product_code');?></td>
    					<td>:</td>
    					<td ><input  name="product_id" id="product_id" size="30" style=" height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>"></td>
                        <td>
                            <input class="w3-btn w3-gray" type="submit" value="<?php echo Portal::language('search');?>" style=" height: 24px; padding-top: 4px; margin-right: 10px;"/>
    						<input class="w3-btn w3-gray" type="button" onclick="window.location='<?php echo Url::build_current(array('cmd')); ?>'" value="<?php echo Portal::language('list_all');?>" style=" height: 24px; padding-top: 4px; text-transform: uppercase;" />
    					</td>
    				</tr>
                </table>
            </fieldset>
            <br />
            <br />
            
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
				<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#C6E2FF" style="border-collapse:collapse">
					<tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
						<th width="1%" title="<?php echo Portal::language('check_all');?>">
                            <input type="checkbox" value="1" id="HousekeepingEquipment_check_0" onclick="check_all('HousekeepingEquipment','HousekeepingEquipment_array_items','#FFFFEC',this.checked);"/>
                        </th>
						<th width="100px" align="center"><?php echo Portal::language('date');?></th>
						<th width="350px" align="center"><?php echo Portal::language('product_name');?></th>
						<th width="100px" align="center"><?php echo Portal::language('product_code');?></th>
						<th width="100px" align="center"><?php echo Portal::language('quantity');?></th>
						<th width="100px" align="center"><?php echo Portal::language('damaged_type');?></th>
                        <th width="300px" align="center"><?php echo Portal::language('note');?></th>
						<th width="100px" align="center"><?php echo Portal::language('room_id');?></th>
						<th>&nbsp;</th>
					</tr>
					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
					<!--<tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;" id="HousekeepingEquipment_tr_<?php echo $this->map['items']['current']['id'];?>">-->
                    <tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;" id="HousekeepingEquipment_tr_<?php echo $this->map['items']['current']['id'];?>">
						<td>
                            <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="tr_color = clickage('HousekeepingEquipment','<?php echo $this->map['items']['current']['i'];?>','HousekeepingEquipment_array_items','#FFFFEC');" id="HousekeepingEquipment_check_<?php echo $this->map['items']['current']['i'];?>"/>
                        </td>
						<td align="center"><?php echo $this->map['items']['current']['date'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['product_name'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['product_id'];?></td>
						<td align="center"><?php echo $this->map['items']['current']['quantity'];?></td>
						<td align="center"><?php echo $this->map['items']['current']['damaged_type'];?></td>
						<td align="left"><?php echo $this->map['items']['current']['note'];?></td>
                        <td align="center"><?php echo $this->map['items']['current']['room_name'];?></td>
						<td width="15px">
							<a  onclick="if(confirm('Ban co chac muon xoa hu hong cua TTB <?php echo $this->map['items']['current']['product_name'];?> ?')) {location='<?php echo URL::build_current(array('cmd'=>'delete_damaged'));?>&id=<?php echo $this->map['items']['current']['id'];?>';}">
                                <i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i>							
                            </a>						
                        </td>
					</tr>
					<?php }}unset($this->map['items']['current']);} ?>
				</table>
				</td>
			</tr>
			</table>
			<?php echo $this->map['paging'];?>

		</td>
	</tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			