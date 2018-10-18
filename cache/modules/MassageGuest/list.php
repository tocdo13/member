<?php System::set_page_title(HOTEL_NAME);?>
<div class="MassageGuest-type-supplier-bound">
<form name="ListMassageGuestForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title"><?php echo $this->map['title'];?></td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','group_id'));?>"  class="button-medium-add"><?php echo Portal::language('Add');?></a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListMassageGuestForm.cmd.value='delete';ListMassageGuestForm.submit();"  class="button-medium-delete"><?php echo Portal::language('Delete');?></a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<?php echo Portal::language('input_keyword');?>: <input  name="keyword" id="keyword" / type ="text" value="<?php echo String::html_normalize(URL::get('keyword'));?>"><input  name="search" value="OK" / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>">
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="1%" align="center"><?php echo Portal::language('order_number');?></th>
			  <th width="8%" align="center"><?php echo Portal::language('group');?></th>
			  <th width="8%" align="center"><?php echo Portal::language('code');?></th>
              <th width="20%" align="center"><?php echo Portal::language('name');?></th>
			  <th width="20%" align="center"><?php echo Portal::language('phone');?></th>
			  <th width="20%" align="center"><?php echo Portal::language('email');?></th>
			  <th width="20%" align="center"><?php echo Portal::language('address');?></th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr>
                <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
                <td align="center"><?php echo $this->map['items']['current']['i'];?></td>
                <td align="center"><span id="category_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['category'];?></span></td>
                <td align="center"><span id="code_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['code'];?></span></td>
                <td><span id="full_name_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['full_name'];?></span> <?php if(Url::get('action')=='select_guest'){?><a class="select-item" href="#" onclick="pick_value(<?php echo $this->map['items']['current']['id'];?>);window.close();"><?php echo Portal::language('select');?></a><?php }?></td>
                <td><span id="phone_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['phone'];?></span></td>
                <td><span id="email_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['email'];?></span></td>
                <td><span id="address_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['address'];?></span></td>
                <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
                <td><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
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
		ListMassageGuestForm.cmd.value = 'delete';
		ListMassageGuestForm.submit();
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
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			window.opener.document.getElementById('full_name').value=$('full_name_'+id).innerHTML;
			window.opener.document.getElementById('guest_id').value=id;	
			window.opener.document.getElementById('code').value=$('code_'+id).innerHTML;
		}
	}
</script>