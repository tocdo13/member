<?php System::set_page_title(HOTEL_NAME);?>
<div class="ExtraServiceAdmin-type-supplier-bound">
<form name="ListExtraServiceAdminForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" align="right" style="padding-right: 30px;"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListExtraServiceAdminForm.cmd.value='delete';ListExtraServiceAdminForm.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.input_keyword.]]: <input name="keyword" type="text" id="keyword" /><input name="search" type="submit" value="OK" />
             <span style="color: red;">[[.service_is_used_then_disable_button_delete.]]</span>
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
			  <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30">[[.order_number.]]</th>
              <th width="150" align="left">[[.code.]]</th>
              <th width="300" align="left">[[.name.]]</th>
			  <th width="150" align="center">[[.price.]]</th>
			  <th width="100" align="center">[[.unit.]]</th>
              <th width="150" align="center">[[.type.]]</th>
              <th width="150" align="center">[[.status.]]</th>
			  <th width="50" align="center">[[.edit.]]</th>
		      <th width="50" align="center">[[.delete.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr class="[[|items.row_class|]]">
			  <td><!--IF:no_delete(([[=items.code=]]!='EXTRA_BED'  and [[=items.code=]]!='BABY_COT' and [[=items.code=]]!='EARLY_CHECKIN' and [[=items.code=]]!='LATE_CHECKOUT' and [[=items.code=]]!='LATE_CHECKIN'  and [[=items.code=]]!='VFD' and [[=items.can_delete=]]==0))-->
              <input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]">
              <!--/IF:no_delete-->
              </td>
			  <td>[[|items.i|]]</td>
				<td>[[|items.code|]]</td>
				<td>[[|items.name|]]</td>
			    <td align="right"><?php echo System::display_number([[=items.price=]]);?></td>
			    <td align="center">[[|items.unit|]]</td>
                  <td align="center">[[|items.type|]]</td>
                <td align="center">[[|items.status|]]</td>
		       <td align="center"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">
                        <img src="packages/core/skins/default/images/buttons/edit.gif" />
                    </a>
               </td>
			    <td align="center">
                <!--IF:no_delete(([[=items.code=]]!='EXTRA_BED' and [[=items.code=]]!='BABY_COT' and [[=items.code=]]!='BABY_COT' and [[=items.code=]]!='EARLY_CHECKIN' and [[=items.code=]]!='LATE_CHECKOUT' and [[=items.code=]]!='LATE_CHECKIN' and [[=items.code=]]!='VFD'))-->
                <?php if(User::can_delete(false,ANY_CATEGORY) and [[=items.can_delete=]]==0){?>
                    <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>">
                        <img src="packages/core/skins/default/images/buttons/delete.gif">
                    </a>
                <?php }?>
                <!--/IF:no_delete-->
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
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListExtraServiceAdminForm.cmd.value = 'delete';
		ListExtraServiceAdminForm.submit();
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
