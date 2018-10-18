<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-supplier-bound">
<form name="CopyCustomerRatePolicyForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr  height="40">
        	<td width="90%" class="form-title">[[|title|]]</td>
            <td width="1%" align="right" nowrap="nowrap"><input name="copy" type="button" value="[[.copy.]]" class="button-medium-save" onclick="checkSelectItems();"></td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[|customer_name|]] - [[.copy_to_customer.]]</legend>
            <select name="copy_customer_id[]" multiple="multiple" class="copy_customer_id">[[|customer_options|]]</select>
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
			<tr class="table-header">
                <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
                <th width="150" align="left">[[.name.]]</th>
                <th width="60" align="left">[[.start_date.]]</th>
                <th width="60" align="left">[[.end_date.]]</th>
                <th width="50" align="left">[[.rate_commission.]]</th>
          </tr>
			<?php $temp = '';?>
            <!--LIST:items-->
            <?php if($temp!=[[=items.customer_name=]]){$temp = [[=items.customer_name=]];?>
            <tr>
          	  <td colspan="13" class="category-group">[[.customer.]] <strong>[[|items.customer_name|]]</strong></td>
            </tr>
            <?php }?>
			<tr <?php echo [[=items.i=]]%2==0?'class="row-even"':'class="row-odd"'?>>
			  <td valign="top"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td valign="top">[[|items.i|]]</td>
				<td valign="top">[[|items.start_date|]]</td>
				<td valign="top">[[|items.end_date|]]</td>
                <td valign="top">[[|items.commission_rate|]]</td>
            </tr>
		  <!--/LIST:items-->			
		</table>
<br />
		<div class="paging">[[|paging|]]</div>
	</div>
</form>	
</div>
<script type="text/javascript">
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
	function checkSelectItems(){
		var flag = 0;
		var val = '';
		jQuery(".item-check-box").each(function(){
			var checkbox = this.checked;
			if(checkbox == true){
				flag = 1;
			}
		});	
		if(flag == 0){
			alert('[[.please_select_at_least_one_rate_policy_item.]]');	
			return false;
		}else{
			jQuery(".copy_customer_id").each(function(){
				var checkbox = this.value;
				if(checkbox != ''){
					flag = 0;
				}
			});
			if(flag == 1){
				alert('[[.has_no_customer_to_copy.]]');	
				return false;
			}else{
				CopyCustomerRatePolicyForm.submit();
			}
		}
	}
</script>