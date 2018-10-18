<?php System::set_page_title(HOTEL_NAME);?>
<div class="room-type-supplier-bound">
<form name="ListGuestLevelForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title"><?php echo $this->map['title'];?></td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add"><?php echo Portal::language('Add');?></a></td><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListGuestLevelForm.cmd.value='delete';ListGuestLevelForm.submit();"  class="button-medium-delete"><?php echo Portal::language('Delete');?></a></td><?php }?>
        </tr>
    </table><br />
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%"><?php echo Portal::language('order_number');?></th>
              <th width="50" align="left"><?php echo Portal::language('name');?></th>
              <th width="50" align="left"><?php echo Portal::language('group');?></th>
              <th width="50" align="left"><?php echo Portal::language('online');?></th>
              <th width="1%" nowrap="nowrap" align="left"><?php echo Portal::language('position');?></th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
			  <td><?php echo $this->map['items']['current']['i'];?></td>
			  <td><?php echo $this->map['items']['current']['name'];?></td>
              <td><?php echo $this->map['items']['current']['group_name'];?></td>
              <td><?php if($this->map['items']['current']['is_online']==1) echo Portal::language('yes');?></td>  
			  <td><?php echo $this->map['items']['current']['position'];?></td>              
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
		ListGuestLevelForm.cmd.value = 'delete';
		ListGuestLevelForm.submit();
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