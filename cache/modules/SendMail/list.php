<form method="post" name="sendmail">
    <table>
        <tr  height="40">
        	<td width="80%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('list_send_mail');?></td>
            <td width="20%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" ><?php echo Portal::language('Add');?></a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};sendmail.cmd.value='delete';sendmail.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a><?php }?>
            </td>
        </tr>
    </table>
    
    <table cellpadding="0" cellspacing="0" width="800" border="1" bordercolor="#CCCCCC">
        <tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
            <td><input type="checkbox" id="all_item_check_box" /></td>
            <td><?php echo Portal::language('stt');?></td>
            <td><?php echo Portal::language('title');?></td>
            <td><?php echo Portal::language('type');?></td>
            <td><?php echo Portal::language('date_send');?></td>
            <td><?php echo Portal::language('edit');?></td>
            <td><?php echo Portal::language('delete');?></td>
        </tr>
        <?php $k=1; ?>
        <?php if(isset($this->map['list_email']) and is_array($this->map['list_email'])){ foreach($this->map['list_email'] as $key1=>&$item1){if($key1!='current'){$this->map['list_email']['current'] = &$item1;?>
        <tr>
            <td><input name="item-check-box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['list_email']['current']['id'];?>" /></td>
            <td><?php echo $k++ ?></td>
            <td><?php echo $this->map['list_email']['current']['title'];?></td>
            <td><?php echo $this->map['list_email']['current']['name'];?></td>
            <td><?php echo $this->map['list_email']['current']['date_send'];?></td>
            <td><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['list_email']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a><?php }?></td>
            <td style="text-align: center;"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['list_email']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" /></a><?php }?></td>
        </tr>
        <?php }}unset($this->map['list_email']['current']);} ?>
        
    </table>
    <input type="hidden" name="cmd" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		sendmail.cmd.value = 'delete';
		sendmail.submit();
	});
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