<?php System::set_page_title(HOTEL_NAME);?>
<div class="product-type-supplier-bound">
<form name="ListTennisProductForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.Add.]]</a></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListTennisProductForm.cmd.value='delete';ListTennisProductForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" /><input name="search" type="submit" value="OK" />
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
              <th width="1%">[[.code.]]</th>
              <th width="20%" align="left">[[.product_name.]]</th>
			  <th width="20%" align="left">[[.product_type_name.]]</th>
			  <th width="10%" align="left">[[.price.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td>[[|items.code|]]</td>
				<td>[[|items.name|]]</td>
				<td>[[|items.category|]]</td>
			    <td>[[|items.price|]]</td>
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
		ListTennisProductForm.cmd.value = 'delete';
		ListTennisProductForm.submit();
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