<div class="warehouse-bound">
<form name="ListWarehouseInvoiceForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type','choose_warehouse'=>1));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="[[.delete.]]" id="delete_button" class="w3-btn w3-red" style="text-transform: uppercase;"/><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
    	<div class="search-box">
        	<fieldset style="font-weight: normal !important;">
            	<legend>[[.search.]]</legend>
        		<span>[[.bill_number.]]:</span> 
        		<input name="bill_number" type="text" id="bill_number" size="4" style="height: 24px; width: 100px;"/>
        		<span>[[.description.]]:</span> 
        		<input name="note" type="text" id="note" size="10" style="height: 24px; width: 150px;"/>
				<span>[[.date_from.]]:</span> 
				<input name="create_date_from" type="text" id="create_date_from" onchange="changevalue();" size="6" style="height: 24px; width: 80px;"/>
				<span>[[.date_to.]]:</span> 
				<input name="create_date_to" type="text" id="create_date_to" size="6" onchange="changefromday();" style="height: 24px; width: 80px;"/>
				<span>[[.supplier.]]:</span> <select name="supplier_id" id="supplier_id" style="width:100px; height: 24px;"></select>
        		<span>[[.warehouse.]]:</span> <select name="warehouse_id" id="warehouse_id" style="width:150px; height: 24px;"></select>
                <!--IF:cond(Url::get('type')=='IMPORT')-->
                <span>[[.invoice_number.]]:</span> <input name="invoice_number" type="text" id="invoice_number" style="width:80px; height: 24px;"/></select>
                <!--/IF:cond-->
                <input style="height: 24px; padding-top: 3px; margin-left: 10px;" type="submit" name="search" value="[[.search.]]"/>
                 
            </fieldset>
            
        </div><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="text-transform: uppercase; height: 24px; text-align: center;">
			  <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30">[[.order_number.]]</th>
			  <th width="70" align="center">[[.create_date.]]</th>
			  <th width="110" align="center">[[.bill_number.]]</th>
              <th width="150" align="center">[[.warehouse.]]</th>
              <!--IF:cond(Url::get('type')=='EXPORT')--><th width="10%" align="center">[[.to_warehouse.]]</th><!--/IF:cond-->
			  <th width="150" align="center">[[.deliver.]]</th>
			  <th width="150" align="center">[[.receiver.]]</th>
			  <th width="250" align="center">[[.description.]]</th>
			  <!--IF:cond(Url::get('type')=='IMPORT')-->
              <th width="250" align="center">[[.supplier.]]</th>
              <th width="80" align="center">[[.invoice_number.]]</th>
              <!--/IF:cond-->
			  <th width="40" align="center">[[.view.]]</th>
			  <th width="40" align="center">[[.edit.]]</th>
		      <th width="40" align="center">[[.delete.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr <?php echo ([[=items.id=]]==Url::iget('just_edited_id'))?' bgcolor="#FFFF99"':'';?>>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td style="cursor:pointer; text-align: center;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>'">[[|items.i|]]</td>
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
				<td style="height: 24px;text-align: center;"><a target="_blank" href="<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'));?>" title="[[.view_bill.]]"><i class="fa fa-eye w3-text-indigo" style="font-size: 20px; padidng-top: 2px;"></i></a></td>
				<td style="height: 24px;text-align: center;">
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('cmd'=>'edit','type','id'=>[[=items.id=]],'warehouse_id'=>[[=items.warehouse_id=]],'edit_average_price'=>[[=items.for_edit_average_price=]]));?>"><i class="fa fa-fw fa-edit" style="color: green;"></i></a>
                    <?php }?>
                </td>
			    <td style="height: 24px;text-align: center;">
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top 2px;"></i></a>
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