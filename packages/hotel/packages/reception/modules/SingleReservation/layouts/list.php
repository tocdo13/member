<?php System::set_page_title(HOTEL_NAME);?>
<div class="tour-type-supplier-bound">
<form name="ListSingleReservationForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add">[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListSingleReservationForm.cmd.value='delete';ListSingleReservationForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" /><input name="search" type="submit" value="OK" />
		</fieldset><br />
		<table border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
              <th width="200" align="left">[[.name.]]</th>
			  <th width="200" align="left">[[.company_name.]]</th>
			  <th width="60" align="left">[[.room_quantity.]]</th>
			  <th width="60" align="left">[[.num_people.]]</th>
			  <th width="60" align="left">[[.arrival_time.]]</th>
			  <th width="60" align="left">[[.departure_time.]]</th>
			  <th width="150" align="left">[[.tour_leader.]]</th>
			  <th width="150" align="right">[[.expected_total_amount.]](<?php echo HOTEL_CURRENCY;?>)</th>
			  <th width="150" align="right">[[.extra_amount.]](<?php echo HOTEL_CURRENCY;?>)</th>
			  <th width="100" align="left">[[.note.]]</th>
			  <th width="100" align="left">[[.create_user.]]</th>
			  <th width="100" align="left">[[.modified_user.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td><span id="name_[[|items.id|]]">[[|items.name|]]</span> <?php if([[=items.selected=]]){?><a class="select-item" href="#" onclick="pick_value([[|items.id|]]);window.close();">[[.select.]]</a><?php }?></td>
				<td><span id="customer_name_[[|items.id|]]">[[|items.company_name|]]</span><input type="hidden" id="customer_id_[[|items.id|]]" value="[[|items.company_id|]]"></td>
			    <td>[[|items.room_quantity|]]</td>
			    <td>[[|items.num_people|]]</td>
                <td width="60">[[|items.arrival_time|]]</td>
                <td width="60">[[|items.departure_time|]]</td>
                <td>[[|items.tour_leader|]]</td>
                <td align="right">[[|items.total_amount|]]</td>
                <td align="right">[[|items.extra_amount|]]</td>
               <td>[[|items.note|]]</td>
			   <td>[[|items.user_id|]]</td>
			   <td>[[|items.last_modified_user_id|]]</td>
		      <td><a href="<?php echo Url::build_current(array('action','cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
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
		ListSingleReservationForm.cmd.value = 'delete';
		ListSingleReservationForm.submit();
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
			window.opener.document.AddReservationForm.tour_name.value=$('name_'+id).innerHTML;
			window.opener.document.AddReservationForm.tour_id.value=id;		
			window.opener.document.AddReservationForm.customer_name.value=$('customer_name_'+id).innerHTML;
			window.opener.document.AddReservationForm.customer_id.value=$('customer_id_'+id).value;
		}
	}
</script>