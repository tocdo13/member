<style>
    table td {
        padding: 5px;
    }
</style>
<form method="POST" name="DebtDeductionListForm">
    <table style="width: 100%;">
        <tr  height="40">
        	<td width="80%" class="form-title"><?php echo Portal::language('debit_customer');?></td>
            <td width="20%" align="right" nowrap="nowrap">
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};DebtDeductionListForm.cmd.value='delete';DebtDeductionListForm.submit();"  class="button-medium-delete"><?php echo Portal::language('Delete');?></a><?php }?>
            </td>
        </tr>
    </table>
    
    <table cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC">
        <tr class="table-header">
            <td><input type="checkbox" id="all_item_check_box" /></td>
            <td align="center"> <?php echo Portal::language('stt');?></td>
            <td align="center"><?php echo Portal::language('date');?></td>
            <td align="center"><?php echo Portal::language('customer');?></td>
            <td align="center"><?php echo Portal::language('amount');?></td>
            <td align="center"><?php echo Portal::language('description');?></td>
            <td align="center"><?php echo Portal::language('recode');?></td>
            <td align="center"><?php echo Portal::language('folio');?></td>
            <td align="center"><?php echo Portal::language('bar_invoice');?></td>
            <td align="center"><?php echo Portal::language('create');?></td>
            <td align="center"><?php echo Portal::language('edit');?></td>
            <td align="center"><?php echo Portal::language('delete');?></td>
        </tr>
        <?php $k=1; ?>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr>
            <td><input name="item-check-box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>" /></td>
            <td align="center"><?php echo $k++ ?></td>
            <td><?php echo Date_time::convert_orc_date_to_date($this->map['items']['current']['date_in'],'/'); ?></td>
            <td><a target="_blank" href="?page=review_debit_customer&customer_id=<?php echo $this->map['items']['current']['cid'];?>"><?php echo $this->map['items']['current']['name'];?></a></td>
            <td align="right"><?php echo System::display_number($this->map['items']['current']['price']) ?></td>
            <td><?php echo $this->map['items']['current']['description'];?></td>
            <td align="center"><?php echo $this->map['items']['current']['recode'];?></td>
            <td align="center"><?php echo $this->map['items']['current']['folio_number'];?> </td>
            <td align="center"><?php echo $this->map['items']['current']['bar_reservation_code'];?> </td>
            <td align="center"><?php echo $this->map['items']['current']['name_1'];?></td>
            <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a><?php }?></td>
            <td style="text-align: center;"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" /></a><?php }?></td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
        
    </table>
    <br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
    <input type="hidden" name="cmd" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
	
	jQuery(".delete-one-item").click(function (){
		if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
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