<style>
    table td {
        padding: 5px;
    }
</style>
<form method="POST" name="DebtDeductionListForm">
    <table>
        <tr  height="40">
        	<td width="80%" class="form-title">[[.debit_customer.]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add button_style" >[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};DebtDeductionListForm.cmd.value='delete';DebtDeductionListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>
    
    <table cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC">
        <tr class="table-header">
            <td><input type="checkbox" id="all_item_check_box" /></td>
            <td align="center"> [[.stt.]]</td>
            <td align="center">[[.date.]]</td>
            <td align="center">[[.customer.]]</td>
            <td align="center">[[.amount.]]</td>
            <td align="center">[[.description.]]</td>
            <td align="center">[[.recode.]]</td>
            <td align="center">[[.folio.]]</td>
            <td align="center">[[.create.]]</td>
            <td align="center">[[.edit.]]</td>
            <td align="center">[[.delete.]]</td>
        </tr>
        <?php $k=1; ?>
        <!--LIST:items-->
        <tr>
            <td><input name="item-check-box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]" /></td>
            <td align="center"><?php echo $k++ ?></td>
            <td><?php echo Date_time::convert_orc_date_to_date([[=items.date_in=]],'/'); ?></td>
            <td><a target="_blank" href="?page=review_debit_customer&customer_id=[[|items.cid|]]">[[|items.name|]]</a></td>
            <td align="right"><?php echo System::display_number([[=items.price=]]) ?></td>
            <td>[[|items.description|]]</td>
            <td align="center">[[|items.recode|]]</td>
            <td align="center"><?php echo ([[=items.folio_number=]])?> </td>
            <td align="center">[[|items.name_1|]]</td>
            <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a><?php }?></td>
            <td style="text-align: center;"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" /></a><?php }?></td>
        </tr>
        <!--/LIST:items-->
        
    </table>
    <br />
		<div class="paging">[[|paging|]]</div>
    <input type="hidden" name="cmd" />
</form>
<script type="text/javascript">
	
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