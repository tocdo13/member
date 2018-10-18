<div class="room-type-supplier-bound">
<form name="ListExtraServiceInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" style="text-transform: uppercase; font-size: 20px;">[[|title|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="25%" style="text-align: right; padding-right: 50px; "><a href="<?php echo URL::build_current(array('cmd'=>'add','type'=>Url::get('type')));?>"  class="w3-btn w3-cyan w3-text-white" style="margin-right: 10px; text-transform: uppercase; text-decoration: none;">[[.Add.]]</a><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="submit" class="w3-btn w3-red" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListExtraServiceInvoiceForm.cmd.value='delete';ListExtraServiceInvoiceForm.type.value='<?php echo Url::get('type');?>';" value="[[.Delete.]]" style="text-transform: uppercase;"  /></td><?php }?>
        </tr>
    </table>        
	<div class="content"><br />
    	<fieldset>
        <legend style="text-transform: uppercase;">[[.search.]]</legend>
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td >[[.hotel.]]</td>
            <td style="margin: 0;"><select name="portal_id" id="portal_id" style="height: 24px; margin-right: 20px;"></select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            
            <td>[[.bill_number.]] <input name="bill_number" type="text" id="bill_number" size="8"style="height: 24px; margin-right: 20px;"/></td>
            <td>[[.room.]] <input name="room_name" type="text" id="room_name" size="8" style="height: 24px; margin-right: 20px;"/></td>
             <td>[[.create_time.]] <input name="time" type="text" id="time" size="8" style="height: 24px; margin-right: 20px;"/></td>
             <td>[[.from_date.]] 
             <input name="from_date" type="text" id="from_date" onchange="changevalue();" size="8" style="height: 24px; margin-right: 20px;"/></td>
             <td>[[.to_date.]]
             <input name="to_date" type="text" id="to_date" size="8" onchange="changefromday();" style="height: 24px; margin-right: 20px;"/></td>
             <td><input name="check_type" type="hidden" id="check_type" value="[[|type|]]" /><input class="w3-btn w3-gray" name="submit" type="submit" value="[[.OK.]]" style="height: 24px; padding-top: 5px;"/></td>
          </tr>
        </table>
        </fieldset><br />
        <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase; font-size: 11px;">
			  <th width="20px" align="center"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30px" align="center">[[.order_number.]]</th>
              <th width="60px" align="center">[[.code.]]</th>
              <th width="100px" align="center">[[.bill_number.]]</th>
			  <th width="80px" align="center">[[.total.]]</th>
			  <th width="130px" align="center">[[.payment.]] [[.with.]] </th>
			  <th width="170px" align="center">[[.room.]]</th>
			  <th width="200px" align="center">[[.note.]]</th>
			  <th width="100px" align="center">[[.status.]]</th>
			  <th width="80px" align="center">[[.create_time.]]</th>
			  <th width="100px" align="center">[[.create_user.]]</th>
			  <th width="80px" align="center">[[.lastest_edited_time.]]</th>
			  <th width="100px" align="center">[[.lastest_edited_user.]]</th>
			  <th width="50px">[[.view.]]</th>
              <th width="50px">[[.edit.]]</th>
		      <th width="50px">[[.delete.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td style="text-align: center;">[[|items.i|]]</td>
				<td>[[|items.bill_number|]]</td>
                <td>[[|items.code|]]</td>
				<td align="right">[[|items.total|]]</td>
				<td style="text-align: center;">[[|items.payment_type|]]</td>
				<td><strong>[[|items.room_name|]]</strong><br />
		      [[.arrival.]]: [[|items.arrival_date|]]-[[|items.departure_date|]] </td>
		        <td>[[|items.note|]]</td>
		        <td style="text-align: center;">[[|items.status|]]</td>
		        <td style="text-align: center;">[[|items.time|]]</td>
               <td style="text-align: center;">[[|items.user_id|]]</td>
			   <td style="text-align: center;">[[|items.lastest_edited_time|]]</td>
			   <td style="text-align: center;">[[|items.lastest_edited_user_id|]]</td>
               <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'view_receipt','id'=>[[=items.id=]]));?>" target="_blank" title="[[.view_receipt.]]"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></a></td>
              <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'edit','type'=>[[=items.type=]],'id'=>[[=items.id=]]));?>" target="_blank" title="[[.edit.]]"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></a></td>
			    <!--IF:cond([[=items.mice_invoice=]])--><!--<td> <a class="delete-one-item" href="<?php //echo Url::build_current(array('cmd'=>'delete','type'=>[[=items.type=]],'id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>--><!--/IF:cond-->
                <td style="text-align: center;"> <a href="<?php echo Url::build_current(array('cmd'=>'delete','type'=>[[=items.type=]],'id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a></td>
            </tr>
		  <!--/LIST:items-->			
		</table>
<br />
		<div class="paging">[[|paging|]]</div>
	</div>
	<input name="cmd" type="hidden" value="">
    <input name="type" type="hidden" value="">
</form>	
</div>
<script type="text/javascript">
    jQuery(".paging a").each(function(){
        var url='&type=<?php echo Url::get('type'); ?>';
        jQuery(this).attr('href',jQuery(this).attr('href')+url);
    });
	jQuery("#time").datepicker();
	jQuery("#from_date").datepicker();
	jQuery("#to_date").datepicker();
	jQuery("#delete_button").click(function (){
		ListExtraServiceInvoiceForm.cmd.value = 'delete';
        ListExtraServiceInvoiceForm.type.value = <?php echo '\''.Url::get('type').'\''; ?>;
		ListExtraServiceInvoiceForm.submit();

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
    
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>