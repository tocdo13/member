<?php System::set_page_title(HOTEL_NAME);?>
<div class="VipCard-type-supplier-bound">
<form name="ListVipCardForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="55%" class="form-title">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.Add.]]</a></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListVipCardForm.cmd.value='delete';ListVipCardForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" /> [[.join_date.]]: <input name="join_date" type="text" id="join_date" style="width:80px;"><input name="search" type="submit" value="OK" />
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#C6E2FF">
			<tr class="table-header">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
              <th width="10%" align="left">[[.code.]]</th>
              <th width="20%" align="left">[[.card_holder.]]</th>
			  <th width="20%" align="left">[[.card_type.]]</th>
			  <th width="10%" align="left">[[.discount_percent.]]</th>
			  <th width="15%" align="left">[[.discount_amount.]]</th>
			  <th width="15%" align="left">[[.join_date.]]</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <?php $i=1;?><!--LIST:items-->
			<tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}$i++;?>>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td>[[|items.code|]]</td>
				<td>[[|items.card_holder|]]</td>
				<td>[[|items.card_type|]]</td>
			    <td>[[|items.discount_percent|]]</td>
                <td>[[|items.discount_amount|]]</td>
               <td>[[|items.join_date|]]</td>
			   <td><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
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
		ListVipCardForm.cmd.value = 'delete';
		ListVipCardForm.submit();
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