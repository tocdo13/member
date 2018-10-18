<script>
	TelephoneNumber_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table width="100%" cellpadding="0" cellspacing="0" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr height="40">
					<td width="90%" class="form-title"><?php echo Portal::language('list_title_telephone_number');?></td>
					<td width="1%"><a href="javascript:void(0)" onclick="EditTelephoneNumberForm.submit();" class="button-medium-save"><?php echo Portal::language('save');?></a></td>
					<td width="1%"><a href="javascript:void(0)" onclick="window.history.go(-1)" class="button-medium-back"><?php echo Portal::language('back');?></a></td>
				</tr>
			</table>
		</td>
	</tr><form name="TelephoneNumberListForm" method="post">
	<tr valign="top">
		<td width="100%">
		<table cellspacing="0" width="100%">
		<tr valign=top>
		<td width="50%">
		<div id="_list_region" style="overflow:auto;height:400px;width:500px">
			<table cellspacing="0" width="100%">
			<tr>
				<td><div style="width:10px;">&nbsp;</div></td>
				<td width="100%" bgcolor="#EFEEEE">
					<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" border="1">
						<tr valign="middle">
							<th width="1%" title="<?php echo Portal::language('check_all');?>"><input type="checkbox" value="1" id="TelephoneNumber_check_0" onclick="check_all('TelephoneNumber','TelephoneNumber_array_items','#FFFFEC',this.checked);"></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_number.number' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_number.number'));?>" style="color:#000000;font-weight:700" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='telephone_number.number') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('phone_number');?>
								</a>
							</th><th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" style="color:#000000;font-weight:700" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('room_id');?>
								</a>
							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><th bgcolor="#EFEEEE">&nbsp;</th>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th bgcolor="#EFEEEE">&nbsp;</th>
							<?php
							}
							?></tr>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" <?php Draw::hover('#EFEEEE');?>style="cursor:pointer;" id="TelephoneNumber_tr_<?php echo $this->map['items']['current']['id'];?>">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="tr_color = clickage('TelephoneNumber','<?php echo $this->map['items']['current']['i'];?>','TelephoneNumber_array_items','#FFFFEC');" id="TelephoneNumber_check_<?php echo $this->map['items']['current']['i'];?>"></td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>';"><?php echo $this->map['items']['current']['phone_number'];?></td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>';"><?php echo $this->map['items']['current']['room_id'];?></td>
							<?php 
							if(User::can_edit(false,ANY_CATEGORY))
							{
							?><td nowrap bgcolor="#EFEEEE" width="15px"><a href="<?php echo Url::build_current(array()+array('edit_selected'=>true,'selected_ids'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>" width="12" height="12" border="0"></a></td>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><td nowrap bgcolor="#EFEEEE" width="15px"><a href="<?php echo Url::build_current(array()+array('cmd'=>'delete','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>" width="12" height="12" border="0"></a> 
							</td>
							<?php
							}
							?></tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
				</td>
			</tr>
			</table>
			<table width="100%"><tr>
				<?php				
				if(User::can_delete(false,ANY_CATEGORY))
				{
					echo '<td align="left"  width="100px">';
					?>
					<input type="submit" title="&quot;" name="delete_selected" onClick="this.disable=true;" style="width:80px"  value="<?php echo Portal::language('delete');?>">
					<?php 
					echo '</td>';
				}
				?></td>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
			</tr></table>
		</div>
		<div><?php echo $this->map['paging'];?></div>
		</td>
