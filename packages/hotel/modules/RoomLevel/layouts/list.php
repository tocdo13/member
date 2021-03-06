<?php System::set_page_title(HOTEL_NAME);?>
<div class="room-type-supplier-bound">
<form name="ListRoomLevelForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="80%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
			<td width="20%" style="text-align: right; padding-right: 30px;"><a style="display:none;" href="<?php echo URL::build_current(array('update_to_lock'=>'1'));?>"  class="w3-btn w3-lime" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Export_to_Lock.]]</a>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListRoomLevelForm.cmd.value='delete';ListRoomLevelForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table><br />
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 30px; text-transform: uppercase;">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="1%">[[.order_number.]]</th>
              <th width="20%" align="left">[[.room_level_name.]]</th>
			  <th width="15%" align="left">[[.brief_name.]]</th>
			  <th width="10%" align="center">[[.num_people.]]</th>
			  <th width="10%" align="right">[[.price.]]</th>
                          <th width="10%" align="center">[[.order_by.]]</th>
			  <th width="10%" align="center">[[.display_color.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  	<?php $temp = '';?>
				<!--LIST:items-->
				<?php if($temp!=[[=items.portal_name=]]){$temp = [[=items.portal_name=]];?>
				<tr>
				  <td colspan="9" class="category-group">[[|items.portal_name|]]</td>
			  </tr>
			  <?php }?>
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td>[[|items.name|]]</td>
				<td>[[|items.brief_name|]]</td>
		      <td align="center">[[|items.num_people|]]</td>
                      <td align="right">[[|items.price|]]</td>
                      <td align="center">[[|items.position|]]</td>
		      <td align="center" bgcolor="[[|items.color|]]">[[|items.color|]]</td>
		      <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>
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
		ListRoomLevelForm.cmd.value = 'delete';
		ListRoomLevelForm.submit();
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