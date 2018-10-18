<form method="post" name="sendmail">
    <table>
        <tr  height="40">
        	<td width="80%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.list_send_mail.]]</td>
            <td width="20%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" >[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};sendmail.cmd.value='delete';sendmail.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>
    
    <table cellpadding="0" cellspacing="0" width="800" border="1" bordercolor="#CCCCCC">
        <tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
            <td><input type="checkbox" id="all_item_check_box" /></td>
            <td>[[.stt.]]</td>
            <td>[[.title.]]</td>
            <td>[[.type.]]</td>
            <td>[[.date_send.]]</td>
            <td>[[.edit.]]</td>
            <td>[[.delete.]]</td>
        </tr>
        <?php $k=1; ?>
        <!--LIST:list_email-->
        <tr>
            <td><input name="item-check-box[]" type="checkbox" class="item-check-box" value="[[|list_email.id|]]" /></td>
            <td><?php echo $k++ ?></td>
            <td>[[|list_email.title|]]</td>
            <td>[[|list_email.name|]]</td>
            <td>[[|list_email.date_send|]]</td>
            <td><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=list_email.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a><?php }?></td>
            <td style="text-align: center;"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=list_email.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" /></a><?php }?></td>
        </tr>
        <!--/LIST:list_email-->
        
    </table>
    <input type="hidden" name="cmd" />
</form>
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		sendmail.cmd.value = 'delete';
		sendmail.submit();
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