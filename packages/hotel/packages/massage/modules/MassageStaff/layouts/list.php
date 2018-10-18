<?php System::set_page_title(HOTEL_NAME);?>
<div class="MassageStaff-type-supplier-bound">
<form name="ListMassageStaffForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-user w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
            <td width="35%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','group_id'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListMassageStaffForm.cmd.value='delete';ListMassageStaffForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" style="height: 24px; margin-right: 10px;" /><input name="search" type="submit" value="[[.search.]]" style="height: 24px; margin-right: 10px;" />
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase;">
			  <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30" align="center">[[.order_number.]]</th>
			  <th width="100" align="center">[[.code.]]</th>
              <th width="200" align="center">[[.name.]]</th>
			  <th width="80" align="center">[[.gender.]]</th>
			  <th width="100" align="center">[[.birth_date.]]</th>
			  <th width="130" align="center">[[.mobile_number.]]</th>
			  <th width="350" align="center">[[.address.]]</th>
			  <th width="150" align="center">[[.native.]]</th>
  			  <th width="150" align="center">[[.status.]]</th>
  			  <th width="40">[[.edit.]]</th>
		      <th width="40">[[.delete.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
                <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/></td>
                <td align="center">[[|items.i|]]</td>
                <td align="center"><span id="code_[[|items.id|]]">[[|items.id|]]</span></td>
                <td ><span id="name_[[|items.id|]]">[[|items.full_name|]]</span> <?php if(Url::get('action')=='select_MassageStaff' or Url::get('action')=='voucher'){?><a class="select-item" href="#" onclick="pick_value([[|items.id|]]);window.close();">[[.select.]]</a><?php }?></td>
                <td align="center">[[|items.gender|]]</td>
                <td align="center">[[|items.birth_date|]]</td>
                <td align="center"><span id="address_[[|items.id|]]">[[|items.phone|]]</span></td>
                <td><span id="address_[[|items.id|]]">[[|items.address|]]</span></td>
                <td><span id="full_name_[[|items.id|]]">[[|items.native|]]</span></td>
                <td align="center"><?php echo Portal::language([[=items.status=]]) ?></td>
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
		ListMassageStaffForm.cmd.value = 'delete';
		ListMassageStaffForm.submit();
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