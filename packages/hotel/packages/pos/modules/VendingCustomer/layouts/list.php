<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-supplier-bound">
<form name="ListCustomerForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr  height="40">
        	<td width="80%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="button-medium-add">[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCustomerForm.cmd.value='delete';ListCustomerForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" />
			[[.customer_source.]]: 
			<select name="group_id" id="group_id"></select>
<input name="search" type="submit" value="OK" />
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
			<tr class="table-header">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
			  <th width="5%" align="left">[[.code.]]</th>
              <th width="25%" align="left">[[.customers.]]</th>
			  <!--IF:cond(!Url::get('group_id'))-->
			  <th width="10%" align="left">[[.customer_source.]]</th>
			  <!--/IF:cond-->
              <th width="10%" align="left">[[.tax_code.]]</th>
			  <th width="20%" align="left">[[.address.]]</th>
			  <th width="40%" align="left">[[.contact_person_info.]]</th>
  			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
            <tr <?php echo [[=items.i=]]%2==0?'class="row-even"':'class="row-odd"'?>>
    		  <td valign="top"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/></td>
    		  <td valign="top">[[|items.rownumber|]]</td>
    		  <td valign="top"><span id="code_[[|items.id|]]">[[|items.code|]]</span></td>
                <td valign="top"><img src="packages/core/skins/default/images/customer_icon.jpg" width="15" align="top"/> <?php if(Url::get('action')=='select_customer' or Url::get('action')=='voucher'){?><a class="select-item" href="#" onclick="pick_value([[|items.id|]]);window.close();">[[.select.]]</a> <?php }?><span id="name_[[|items.id|]]"><strong>[[|items.name|]]</strong></span> </td>
				<!--IF:cond(!Url::get('group_id'))-->
				<td valign="top">[[|items.group_name|]]</td>
				<!--/IF:cond-->
                <td valign="top"><span id="tax_code_[[|items.id|]]">[[|items.tax_code|]]</span></td>
				<td valign="top"><span id="address_[[|items.id|]]">[[|items.address|]]</span></td>
		       <td>
               	<?php $stt = 1;?>
               		<!--LIST:items.contacts-->
                    <?php if($stt>1){?>
                    <hr />
                    <?php }?>
			   		&loz; <span class="note">[[.full_name.]]:</span> [[|items.contacts.contact_name|]]<br />
		       		&loz; <span class="note">[[.phone.]]:</span> [[|items.contacts.contact_phone|]]<br />
		       		&loz; <span class="note">[[.mobile.]]:</span> [[|items.contacts.contact_mobile|]]<br />
		       		&loz; <span class="note">[[.email.]]:</span> [[|items.contacts.contact_email|]]                    
                    <?php $stt++;?>
               		<!--/LIST:items.contacts-->                    
                    </td>
	          <td valign="top"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]],'group_id'));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td valign="top"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]],'group_id'));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
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
		ListCustomerForm.cmd.value = 'delete';
		ListCustomerForm.submit();
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
            if(window.opener.document.getElementById('customer_address'))
			{
				window.opener.document.getElementById('customer_address').value=($('address_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
            if(window.opener.document.getElementById('tax_code'))
			{
				window.opener.document.getElementById('tax_code').value=($('tax_code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
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
			if(window.opener.jQuery('#customer_name').val()){
				var inputCount = window.opener.input_count;
				for(var i=101;i<=inputCount;i++){
					if(window.opener.jQuery('#price_'+i) && window.opener.jQuery('#old_status_'+i) && window.opener.jQuery('#old_status_'+i).val()==''){
					}
				}	
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