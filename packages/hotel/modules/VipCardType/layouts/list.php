<?php System::set_page_title(HOTEL_NAME);?>
<form name="ListVipCardTypeForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="55%" class="form-title">[[|title|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.Add.]]</a></td><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="ListVipCardTypeForm.cmd.value='delete';ListVipCardTypeForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table><br />    
    <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
        <tr class="table-header">
          <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
          <th width="1%">[[.order_number.]]</th>
          <th width="20%" align="left">[[.vip_card_type_name.]]</th>
          <th width="20%" align="left">[[.discount_percent.]]</th>
          <th width="1%">&nbsp;</th>
          <th width="1%">&nbsp;</th>
      </tr>
     <?php $i=1;?><!--LIST:items-->
        <tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}$i++;?>>
          <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
          <td>[[|items.i|]]</td>
            <td>[[|items.name|]]</td>
            <td>[[|items.discount_percent|]]</td>
          <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
            <td><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
        </tr>
      <!--/LIST:items-->			
    </table>
	<div class="paging">[[|paging|]]</div>
	</div>
	<input name="cmd" type="hidden" value="">
</form>	
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListVipCardTypeForm.cmd.value = 'delete';
		ListVipCardTypeForm.submit();
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