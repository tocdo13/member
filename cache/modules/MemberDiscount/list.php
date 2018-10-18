<form name="MemberDiscountForm" method="POST">
    <table cellpadding="15" cellspacing="15">
        <tr>
            <td><h1><?php echo Portal::language('list_member_discount');?></h1></td>
            <td style="text-align: right;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="export_file_excel" type="submit" id="export_file_excel" value="<?php echo Portal::language('export_file_excel');?>" style="padding: 10px;" /><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('import_from_excel');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" style="padding: 10px;" /><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){ ?> <input type="button" value="<?php echo Portal::language('add');?>" onclick="window.location.href='?page=member_discount&cmd=add';" style="padding: 10px;" /> <?php } ?>
             </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellpadding="15" cellspacing="15" width="100%" border="1" bordercolor="#CCCCCC">
                    <tr style="text-align: center;">
                        <th><?php echo Portal::language('stt');?></th>
                        <th><?php echo Portal::language('code');?></th>
                        <th><?php echo Portal::language('card_type');?></th>
                        <th><?php echo Portal::language('pin_service');?></th>
                        <th><?php echo Portal::language('title');?></th>
                        <th><?php echo Portal::language('start_date');?></th>
                        <th><?php echo Portal::language('end_date');?></th>
                        <th><?php echo Portal::language('number_people');?></th>
                        <th>%<?php echo Portal::language('percent');?></th>
                        <th><?php echo Portal::language('description');?></th>
                        <th><?php echo Portal::language('edit');?></th>
                    </tr>
                    <?php $stt = 1; ?>
                    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                    <tr id="tr_<?php echo $this->map['items']['current']['id'];?>">
                        <td style="text-align: center;"><?php echo $stt++; ?></td>
                        <td style="text-align: center;">
                            <span id="code_discount_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['code'];?></span>
                            <a class="select-item" href="#" style="display: none;" onclick="pick_value(<?php echo $this->map['items']['current']['id'];?>);window.close();"><?php echo Portal::language('select');?></a>
                        </td>
                        <td style="text-align: center;"><?php if($this->map['items']['current']['is_parent']=='PARENT'){ ?><?php echo Portal::language('parent_card');?><?php }elseif($this->map['items']['current']['is_parent']=='SON'){ ?><?php echo Portal::language('son_card');?> <?php }else{ ?><?php echo Portal::language('all');?><?php } ?></td>
                        <td style="text-align: center;"><?php echo ($this->map['items']['current']['access_pin_service_name']=='')?Portal::language('all'):$this->map['items']['current']['access_pin_service_name']; ?></td>
                        <td style="text-align: left;"><?php echo $this->map['items']['current']['title'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['start_date'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['end_date'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['number_people'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['percent'];?></td>
                        <td style="text-align: left;"><?php echo $this->map['items']['current']['description'];?></td>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){ ?><td style="text-align: center; cursor: pointer;"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_edit.png" onclick="window.location.href='?page=member_discount&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>';" style="cursor: pointer; width: 20px; height: auto;" /></td><?php } ?>
                    </tr>
                    <?php }}unset($this->map['items']['current']);} ?>
                </table>
            </td>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    //tieubinh add
    function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('member_discount'))
			{
				window.opener.document.getElementById('member_discount').value=jQuery('#code_discount_'+id).text();
   	            //window.opener.document.getElementById('hi_staff_id').value= id;
            }	
		}
	}
    
//end tieubinh
    var items_js = <?php echo $this->map['items_js'];?>;
    jQuery(document).ready(function(){
        jQuery("#check_box_all").click(function(){
            if(this.checked==true)
            {
                jQuery(".check_list").removeAttr("checked");
                jQuery(".check_list").attr('checked','checked');
            }
            else
                jQuery(".check_list").removeAttr("checked");
        });
    });
    function delete_item(id)
    {
        console.log(222);
        if(confirm('<?php echo Portal::language('are_you_delete_item');?>'))
        {
            jQuery(".check_list").removeAttr("checked");
            jQuery("#delete_ids_"+id).attr('checked','checked');
            MemberDiscountForm.submit();
        }
    }
    function delete_all_select()
    {
        if(confirm('<?php echo Portal::language('are_you_delete_selected');?>'))
        {
            var check = false;
            for(var id in items_js)
            {
                if(document.getElementById("delete_ids_"+items_js[id]['id']).checked==true)
                    check = true;
            }
            return check;
        }
        else
            return false;
    }
</script>