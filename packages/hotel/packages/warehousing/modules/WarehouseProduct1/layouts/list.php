<?php System::set_page_title(HOTEL_NAME);?>
<div class="wh_product-type-supplier-bound" style="width:980px;">
<form name="ListWarehouseProductForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add">[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListWarehouseProductForm.cmd.value='delete';ListWarehouseProductForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
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
              <th width="50" align="left">[[.code.]]</th>
              <th width="200" align="left">[[.name.]]</th>
			  <th width="200" align="left">[[.category.]]</th>
			  <th width="60" align="left">[[.type.]]</th>
			  <th width="60" align="center">[[.unit.]]</th>
			  <th width="100" align="left">[[.start_term_quantity.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td><span id="name_[[|items.id|]]">[[|items.id|]]</span></td>
				<td><span id="name_[[|items.id|]]">[[|items.name|]]</span></td>
				<td><span id="customer_name_[[|items.id|]]">[[|items.category_name|]]</span></td>
			    <td>[[|items.type|]]</td>
                <td align="center">[[|items.unit_name|]]</td>
                <td>[[|items.start_term_quantity|]]</td>
              <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="../../WarehouseProduct/layouts/packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="../../WarehouseProduct/layouts/packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
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
		ListWarehouseProductForm.cmd.value = 'delete';
		ListWarehouseProductForm.submit();
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