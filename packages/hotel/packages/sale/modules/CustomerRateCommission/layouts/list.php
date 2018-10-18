<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-supplier-bound">
<form name="ListCustomerRatePolicyForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr  height="40">
        	<td width="40%" class="" style="font-size: 18px; padding-left: 10px;"><i class="fa fa-percent w3-text-orange" style="font-size: 24px;"></i> [[.commission_list.]]</td>
            <td width="60%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<a href="<?php echo Url::build_current(array('customer'));?>" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.customer_list.]]</a>
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('customer_id','cmd'=>'add','group_id','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Add.]]</a><?php }?>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="#"  class="w3-btn w3-orange w3-text-white" onclick="CheckEdit();" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Edit.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCustomerRatePolicyForm.cmd.value='delete';ListCustomerRatePolicyForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" style="height: 24px;" />
            [[.customer.]]: <select name="customer_id" id="customer_id" onchange="ListCustomerRatePolicyForm.submit()" style="height: 24px;"></select>
            <input name="search" type="submit" value="OK" style="height: 24px;" />
            <a href="<?php echo Url::build_current(array('cmd'=>'copy','customer_id'));?>">[[.copy_rate_policy.]]</a>
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
			<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
                <th width="1%"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="1%">[[.order_number.]]</th>
                <th width="60" align="left">[[.start_date.]]</th>
                <th width="60" align="left">[[.end_date.]]</th>
                <th width="50" align="left">[[.commission_rate.]]</th>
                <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
			<?php $temp = '';?>
            <!--LIST:items-->
			<tr <?php echo [[=items.i=]]%2==0?'class="row-even"':'class="row-odd"'?>>
			  <td valign="top"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td valign="top">[[|items.i|]]</td>
				<td valign="top">[[|items.start_date|]]</td>
				<td valign="top">[[|items.end_date|]]</td>
                <td valign="top">[[|items.commission_rate|]]</td>
	          <td valign="top"><a href="<?php echo Url::build_current(array('customer_id','cmd'=>'edit','id'=>[[=items.id=]],'group_id'));?>"><i class="fa fa-edit w3-text-orange" style="font-size: 18px;"></i></a></td>
			    <td valign="top"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('customer_id','cmd'=>'delete','id'=>[[=items.id=]],'group_id'));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a><?php }?></td>
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
		ListCustomerRatePolicyForm.cmd.value = 'delete';
		ListCustomerRatePolicyForm.submit();
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
			window.opener.document.getElementById('customer_name').value=$('name_'+id).innerHTML;
			window.opener.document.getElementById('customer_id').value=id;		
		}
	}
	function CheckEdit(){
		var flag = 0;
		var val = '';
		jQuery(".item-check-box").each(function(){
			var checkbox = this.checked;
			if(checkbox == true){
				flag = 1;
				val += this.value+',';
			}
		});	
		if(flag == 0){
			alert('[[.no_item_selected.]]');	
			return false;
		}else{
			val = val.substr(0,val.length - 1);
			window.location = '<?php echo URL::build_current(array('customer_id','cmd'=>'edit','group_id','action'));?>'+'&policy_id='+val;	
		}
	}
</script>
<?php if(Url::get('action')=='select_customer'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('customer_name'))
			{
				window.opener.document.getElementById('customer_name').value=($('name_'+id).innerText!='')?($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('address'))
			{
				window.opener.document.getElementById('address').value=($('address_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('full_name'))
			{
				window.opener.document.getElementById('full_name').value=(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""))?($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('customer_id'))
			{
				window.opener.document.getElementById('customer_id').value=id;
			}
			if(window.opener.document.getElementById('customer_code'))
			{
				window.opener.document.getElementById('customer_code').value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?><?php if(Url::get('action')=='voucher'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('liability_customer_name_'+window.opener.document.current_item))
			{
				if(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "")=='')
				{
					window.opener.document.getElementById('liability_customer_name_'+window.opener.document.current_item).value=($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				}
				else
				{
					window.opener.document.getElementById('liability_customer_name_'+window.opener.document.current_item).value=($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");			
				}
			}
			if(window.opener.document.getElementById('liability_customer_id_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_customer_id_'+window.opener.document.current_item).value=id;
			}
			if(window.opener.document.getElementById('liability_customer_code_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_customer_code_'+window.opener.document.current_item).value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?>