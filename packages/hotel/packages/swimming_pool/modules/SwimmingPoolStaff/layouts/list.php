<?php System::set_page_title(HOTEL_NAME);?>
<div class="SwimmingPoolStaff-type-supplier-bound">
<form name="ListSwimmingPoolStaffForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','group_id'));?>"  class="button-medium-add">[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListSwimmingPoolStaffForm.cmd.value='delete';ListSwimmingPoolStaffForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" /><input name="search" type="submit" value="OK" />
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
			  <th width="5%" align="left">[[.code.]]</th>
              <th width="20%" align="left">[[.name.]]</th>
			  <th width="10%" align="left">[[.gender.]]</th>
			  <th width="10%" align="left">[[.birth_date.]]</th>
			  <th width="20%" align="left">[[.phone.]]</th>
			  <th width="20%" align="left">[[.address.]]</th>
			  <th width="20%" align="left">[[.native.]]</th>
  			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
			  <td><span id="code_[[|items.id|]]">[[|items.id|]]</span></td>
				<td><span id="name_[[|items.id|]]">[[|items.full_name|]]</span> <?php if(Url::get('action')=='select_SwimmingPoolStaff' or Url::get('action')=='voucher'){?><a class="select-item" href="#" onclick="pick_value([[|items.id|]]);window.close();">[[.select.]]</a><?php }?></td>
				<td>[[|items.gender|]]</td>
				<td>[[|items.birth_date|]]</td>
				<td><span id="address_[[|items.id|]]">[[|items.phone|]]</span></td>
				<td><span id="address_[[|items.id|]]">[[|items.address|]]</span></td>
		       <td><span id="full_name_[[|items.id|]]">[[|items.native|]]</span></td>
		      <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
			</tr>
		  <!--/LIST:items-->			
		</table>
		<br />
		<div class="paging">[[|paging|]]</div>
	</div>
	<input name="cmd" type="hidden" value="">
</form>	
</div>
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListSwimmingPoolStaffForm.cmd.value = 'delete';
		ListSwimmingPoolStaffForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('[[.are_you_sure.]]')){
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
			window.opener.document.getElementById('SwimmingPoolStaff_name').value=$('name_'+id).innerHTML;
			window.opener.document.getElementById('SwimmingPoolStaff_id').value=id;		
		}
	}
</script>
<?php if(Url::get('action')=='select_SwimmingPoolStaff'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('SwimmingPoolStaff_name'))
			{
				window.opener.document.getElementById('SwimmingPoolStaff_name').value=($('name_'+id).innerText!='')?($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('address'))
			{
				window.opener.document.getElementById('address').value=($('address_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('full_name'))
			{
				window.opener.document.getElementById('full_name').value=(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""))?($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('SwimmingPoolStaff_id'))
			{
				window.opener.document.getElementById('SwimmingPoolStaff_id').value=id;
			}
			if(window.opener.document.getElementById('SwimmingPoolStaff_code'))
			{
				window.opener.document.getElementById('SwimmingPoolStaff_code').value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?><?php if(Url::get('action')=='voucher'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('liability_SwimmingPoolStaff_name_'+window.opener.document.current_item))
			{
				if(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "")=='')
				{
					window.opener.document.getElementById('liability_SwimmingPoolStaff_name_'+window.opener.document.current_item).value=($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				}
				else
				{
					window.opener.document.getElementById('liability_SwimmingPoolStaff_name_'+window.opener.document.current_item).value=($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");			
				}
			}
			if(window.opener.document.getElementById('liability_SwimmingPoolStaff_id_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_SwimmingPoolStaff_id_'+window.opener.document.current_item).value=id;
			}
			if(window.opener.document.getElementById('liability_SwimmingPoolStaff_code_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_SwimmingPoolStaff_code_'+window.opener.document.current_item).value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?>