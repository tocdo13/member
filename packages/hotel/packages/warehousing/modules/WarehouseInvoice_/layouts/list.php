<div class="warehouse-bound">
<form name="ListWarehouseInvoiceForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="80%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type','choose_warehouse'=>1));?>'" class="button-medium-add"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="[[.delete.]]" id="delete_button" class="button-medium-delete"/><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
    	<div class="search-box">
        	<fieldset>
            	<legend><strong>[[.search.]]</strong></legend>
        		<span>[[.bill_number.]]:</span> 
        		<input name="bill_number" type="text" id="bill_number" size="4"/>
        		<span>[[.description.]]:</span> 
        		<input name="note" type="text" id="note" size="10"/>
				<span>[[.date_from.]]:</span> 
				<input name="create_date_from" type="text" id="create_date_from" onchange="changevalue();" size="6"/>
				<span>[[.date_to.]]:</span> 
				<input name="create_date_to" type="text" id="create_date_to" size="6" onchange="changefromday();" />
				| <span>[[.supplier.]]:</span> <select name="supplier_id" id="supplier_id" style="width:80px;"></select>
        		| <span>[[.warehouse.]]:</span> <select name="warehouse_id" id="warehouse_id" style="width:80px;"></select>
                <!--IF:cond(Url::get('type')=='IMPORT')-->
                | <span>[[.invoice_number.]]:</span> <input name="invoice_number" type="text" id="invoice_number" style="width:30px;"/></select>
                <!--/IF:cond-->
                <input type="submit" name="search" value="[[.search.]]"/>
                 
            </fieldset>
            
        </div><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class=table-header>
			  <th width="1%"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="1%">[[.order_number.]]</th>
			  <th width="5%" align="left">[[.create_date.]]</th>
			  <th width="12%" align="left">[[.bill_number.]]</th>
              <th width="10%" align="left">[[.warehouse.]]</th>
              <!--IF:cond(Url::get('type')=='EXPORT')--><th width="10%" align="left">[[.to_warehouse.]]</th><!--/IF:cond-->
			  <th width="8%" align="left">[[.deliver.]]</th>
			  <th width="8%" align="left">[[.receiver.]]</th>
			  <th width="28%" align="left">[[.description.]]</th>
			  <!--IF:cond(Url::get('type')=='IMPORT')-->
              <th width="25%" align="left">[[.supplier.]]</th>
              <th width="25%" align="left">[[.invoice_number.]]</th>
              <!--/IF:cond-->
			  <th width="1%">&nbsp;</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr <?php echo ([[=items.id=]]==Url::iget('just_edited_id'))?' bgcolor="#FFFF99"':'';?>>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.i|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.create_date|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.bill_number|]]</td>
                <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.warehouse_name|]]</td>
                <!--IF:cond(Url::get('type')=='EXPORT')--><td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.to_warehouse_name|]]</td><!--/IF:cond-->
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.deliver_name|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.receiver_name|]]</td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.note|]]</td>
				<!--IF:cond(Url::get('type')=='IMPORT')-->
                <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.supplier_name|]]</td>
                <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.invoice_number|]]</td>
                <!--/IF:cond-->
				<td><a target="_blank" href="<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>" title="[[.view_bill.]]"><img src="packages/core/skins/default/images/buttons/select.jpg"/></a></td>
				<td>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('cmd'=>'edit','type','id'=>[[=items.id=]],'warehouse_id'=>[[=items.warehouse_id=]],'edit_average_price'=>[[=items.for_edit_average_price=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                    <?php }?>
                </td>
			    <td>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"/></a>
                    <?php }?>
                </td>
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
	jQuery("#create_date_from").mask("99/99/9999");
	jQuery("#create_date_to").mask("99/99/9999");
	jQuery("#create_date_from").datepicker();
	jQuery("#create_date_to").datepicker();
    function changevalue()
    {
        var myfromdate = $('create_date_from').value.split("/");
        var mytodate = $('create_date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#create_date_to").val(jQuery("#create_date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('create_date_from').value.split("/");
        var mytodate = $('create_date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#create_date_from").val(jQuery("#create_date_to").val());
        }
    }
	jQuery("#delete_button").click(function (){
        if(confirm('[[.are_you_sure.]]')){
    		ListWarehouseInvoiceForm.cmd.value = 'delete';
    		ListWarehouseInvoiceForm.submit();
        };

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