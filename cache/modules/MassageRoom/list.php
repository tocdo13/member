<?php System::set_page_title(HOTEL_NAME);?>
<div class="room-type-supplier-bound">
<form name="ListMassageRoomForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%" align="right" style="padding-right: 30px;"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px;"><?php echo Portal::language('Add');?></a><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListMassageRoomForm.cmd.value='delete';ListMassageRoomForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px;"><?php echo Portal::language('Delete');?></a></td><?php }?>
        </tr>
    </table>        
	<div class="content">
		<table width="800" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1" style="text-transform: uppercase;">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%"><?php echo Portal::language('order_number');?></th>
              <th width="20%" align="left"><?php echo Portal::language('room_name');?></th>
			  <th width="20%" align="left"><?php echo Portal::language('room_level_name');?></th>
			  <th width="10%" align="left"><?php echo Portal::language('position');?></th>
              <th width="10%" align="left"><?php echo Portal::language('area');?></th>
              <th width="1%"><?php echo Portal::language('edit');?></th>
		      <th width="1%"><?php echo Portal::language('delete');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr>
                <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
                <td align="center"><?php echo $this->map['items']['current']['i'];?></td>
                <td><?php echo $this->map['items']['current']['name'];?></td>
                <td><?php echo $this->map['items']['current']['category'];?></td>
                <td><?php echo $this->map['items']['current']['position'];?></td>
                <td><?php echo $this->map['items']['current']['area'];?></td>
                <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
                <td><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>
			</tr>
		  <?php }}unset($this->map['items']['current']);} ?>			
		</table>
		<br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListMassageRoomForm.cmd.value = 'delete';
		ListMassageRoomForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
</script>