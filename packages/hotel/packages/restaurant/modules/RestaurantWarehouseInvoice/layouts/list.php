<div class="product-supplier-bound">
<form name="ListRestaurantWarehouseInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
				<?php if(User::can_add(false,ANY_CATEGORY) and Url::get('type')=='EXPORT'){?><input type="button" value="[[.warehouse_export.]]" id="add_button" class="button-medium-add" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type'=>'EXPORT'))?>'"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="[[.delete.]]" id="delete_button" class="button-medium-delete"><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
    	<div class="search-box">
        	<fieldset>
            	<legend>[[.search.]]</legend>
        		<span>Di&ecirc;n gi&#7843;i:</span> 
        		<input name="note" type="text" id="note"> <span>[[.date.]]:</span> <input name="create_date" type="text" id="create_date"><span>[[.warehouse.]]:</span> 
        		<select name="warehouse_id" id="warehouse_id"></select><!--/IF:cond-->
                <input type="submit" name="search" value="[[.search.]]">
            </fieldset>
        </div>
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
			  <th width="10%" align="left">[[.create_date.]]</th>
			  <th width="10%" align="left">[[.bill_number.]]</th>
			  <th width="10%" align="left">Di&#7877;n gi&#7843;i </th>
			  <th width="25%" align="left">[[.warehouse.]]</th>
			  <th width="1%">&nbsp;</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.i|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.create_date|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.bill_number|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.note|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.warehouse_name|]]</td>
				<td><a target="_blank" href="<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>" title="[[.view_bill.]]"><img src="packages/core/skins/default/images/buttons/select.jpg"></a></td>
                <td><?php if(User::can_edit() and Url::get('type')=='EXPORT'){?><a target="_blank" href="<?php echo Url::build('restaurant_warehouse_invoice',array('cmd'=>'edit','id'=>[[=items.id=]],'type'));?>" title="[[.edit_bill.]]"><img src="packages/core/skins/default/images/buttons/edit.jpg"></a><?php }?></td>
			    <td><?php if(User::can_delete()){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
			</tr>
		  <!--/LIST:items-->			
		</table>
		<br />
		<div class="paging">[[|paging|]]</div>
	</div>
	<input name="cmd" type="hidden" value="">
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#create_date").mask("99/99/9999");
	jQuery("#delete_button").click(function (){
		ListRestaurantWarehouseInvoiceForm.cmd.value = 'delete';
		ListRestaurantWarehouseInvoiceForm.submit();
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
</script>