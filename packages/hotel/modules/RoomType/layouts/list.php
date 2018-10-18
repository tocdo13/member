<?php System::set_page_title(HOTEL_NAME);?>
<div class="room-type-supplier-bound">
<form name="ListRoomTypeForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; text-decoration: none; padding-left: 15px;">[[|title|]]</td>
			<td width="45%" style="text-align: right; padding-right: 30px;"><a style="display:none;" href="<?php echo URL::build_current(array('update_to_lock'=>'1'));?>"  class="w3-btn w3-lime" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Export_to_Lock.]]</a>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListRoomTypeForm.cmd.value='delete';ListRoomTypeForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table><br />
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 30px; text-transform: uppercase;">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="1%">[[.order_number.]]</th>
              <th width="20%" align="left">[[.room_type_name.]]</th>
			  <th width="15%" align="left">[[.brief_name.]]</th>
			  <th width="1%" style="text-align: center;">[[.edit.]]</th>
		      <th width="1%" style="text-align: center;">[[.delete.]]</th>
		  </tr>
				<!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td>[[|items.name|]]</td>
				<td>[[|items.brief_name|]]</td>
		      <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px; padding-top: 2px;"></i></a></td>
			    <td style="text-align: center;"><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px; padding-top: 2px;"></i></a></td>
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
		ListRoomTypeForm.cmd.value = 'delete';
		ListRoomTypeForm.submit();
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