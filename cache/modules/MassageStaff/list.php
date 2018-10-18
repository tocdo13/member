<?php System::set_page_title(HOTEL_NAME);?>
<div class="MassageStaff-type-supplier-bound">
<form name="ListMassageStaffForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-user w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="35%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','group_id'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('Add');?></a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListMassageStaffForm.cmd.value='delete';ListMassageStaffForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('Delete');?></a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<?php echo Portal::language('input_keyword');?>: <input  name="keyword" id="keyword" style="height: 24px; margin-right: 10px;" / type ="text" value="<?php echo String::html_normalize(URL::get('keyword'));?>"><input name="search" type="submit" value="<?php echo Portal::language('search');?>" style="height: 24px; margin-right: 10px;" />
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase;">
			  <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30" align="center"><?php echo Portal::language('order_number');?></th>
			  <th width="100" align="center"><?php echo Portal::language('code');?></th>
              <th width="200" align="center"><?php echo Portal::language('name');?></th>
			  <th width="80" align="center"><?php echo Portal::language('gender');?></th>
			  <th width="100" align="center"><?php echo Portal::language('birth_date');?></th>
			  <th width="130" align="center"><?php echo Portal::language('mobile_number');?></th>
			  <th width="350" align="center"><?php echo Portal::language('address');?></th>
			  <th width="150" align="center"><?php echo Portal::language('native');?></th>
  			  <th width="150" align="center"><?php echo Portal::language('status');?></th>
  			  <th width="40"><?php echo Portal::language('edit');?></th>
		      <th width="40"><?php echo Portal::language('delete');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr>
                <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"/></td>
                <td align="center"><?php echo $this->map['items']['current']['i'];?></td>
                <td align="center"><span id="code_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['id'];?></span></td>
                <td ><span id="name_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['full_name'];?></span> <?php if(Url::get('action')=='select_MassageStaff' or Url::get('action')=='voucher'){?><a class="select-item" href="#" onclick="pick_value(<?php echo $this->map['items']['current']['id'];?>);window.close();"><?php echo Portal::language('select');?></a><?php }?></td>
                <td align="center"><?php echo $this->map['items']['current']['gender'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['birth_date'];?></td>
                <td align="center"><span id="address_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['phone'];?></span></td>
                <td><span id="address_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['address'];?></span></td>
                <td><span id="full_name_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['native'];?></span></td>
                <td align="center"><?php echo Portal::language($this->map['items']['current']['status']) ?></td>
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
		ListMassageStaffForm.cmd.value = 'delete';
		ListMassageStaffForm.submit();
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
			window.opener.document.getElementById('MassageStaff_name').value=$('name_'+id).innerHTML;
			window.opener.document.getElementById('MassageStaff_id').value=id;		
		}
	}
</script>
<?php if(Url::get('action')=='select_MassageStaff'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('MassageStaff_name'))
			{
				window.opener.document.getElementById('MassageStaff_name').value=($('name_'+id).innerText!='')?($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('address'))
			{
				window.opener.document.getElementById('address').value=($('address_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('full_name'))
			{
				window.opener.document.getElementById('full_name').value=(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""))?($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('MassageStaff_id'))
			{
				window.opener.document.getElementById('MassageStaff_id').value=id;
			}
			if(window.opener.document.getElementById('MassageStaff_code'))
			{
				window.opener.document.getElementById('MassageStaff_code').value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?><?php if(Url::get('action')=='voucher'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('liability_MassageStaff_name_'+window.opener.document.current_item))
			{
				if(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "")=='')
				{
					window.opener.document.getElementById('liability_MassageStaff_name_'+window.opener.document.current_item).value=($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				}
				else
				{
					window.opener.document.getElementById('liability_MassageStaff_name_'+window.opener.document.current_item).value=($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");			
				}
			}
			if(window.opener.document.getElementById('liability_MassageStaff_id_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_MassageStaff_id_'+window.opener.document.current_item).value=id;
			}
			if(window.opener.document.getElementById('liability_MassageStaff_code_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_MassageStaff_code_'+window.opener.document.current_item).value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?>