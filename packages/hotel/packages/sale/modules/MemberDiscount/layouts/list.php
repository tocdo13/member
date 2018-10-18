<form name="MemberDiscountForm" method="POST">
    <table cellpadding="15" cellspacing="15">
        <tr>
            <td><h1>[[.list_member_discount.]]</h1></td>
            <td style="text-align: right;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="export_file_excel" type="submit" id="export_file_excel" value="[[.export_file_excel.]]" style="padding: 10px;" /><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.import_from_excel.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" style="padding: 10px;" /><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){ ?> <input type="button" value="[[.add.]]" onclick="window.location.href='?page=member_discount&cmd=add';" style="padding: 10px;" /> <?php } ?>
             </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellpadding="15" cellspacing="15" width="100%" border="1" bordercolor="#CCCCCC">
                    <tr style="text-align: center;">
                        <th>[[.stt.]]</th>
                        <th>[[.code.]]</th>
                        <th>[[.card_type.]]</th>
                        <th>[[.pin_service.]]</th>
                        <th>[[.title.]]</th>
                        <th>[[.start_date.]]</th>
                        <th>[[.end_date.]]</th>
                        <th>[[.number_people.]]</th>
                        <th>%[[.percent.]]</th>
                        <th>[[.description.]]</th>
                        <th>[[.edit.]]</th>
                    </tr>
                    <?php $stt = 1; ?>
                    <!--LIST:items-->
                    <tr id="tr_[[|items.id|]]">
                        <td style="text-align: center;"><?php echo $stt++; ?></td>
                        <td style="text-align: center;">
                            <span id="code_discount_[[|items.id|]]">[[|items.code|]]</span>
                            <a class="select-item" href="#" style="display: none;" onclick="pick_value([[|items.id|]]);window.close();">[[.select.]]</a>
                        </td>
                        <td style="text-align: center;"><?php if([[=items.is_parent=]]=='PARENT'){ ?>[[.parent_card.]]<?php }elseif([[=items.is_parent=]]=='SON'){ ?>[[.son_card.]] <?php }else{ ?>[[.all.]]<?php } ?></td>
                        <td style="text-align: center;"><?php echo ([[=items.access_pin_service_name=]]=='')?Portal::language('all'):[[=items.access_pin_service_name=]]; ?></td>
                        <td style="text-align: left;">[[|items.title|]]</td>
                        <td style="text-align: center;">[[|items.start_date|]]</td>
                        <td style="text-align: center;">[[|items.end_date|]]</td>
                        <td style="text-align: center;">[[|items.number_people|]]</td>
                        <td style="text-align: center;">[[|items.percent|]]</td>
                        <td style="text-align: left;">[[|items.description|]]</td>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){ ?><td style="text-align: center; cursor: pointer;"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_edit.png" onclick="window.location.href='?page=member_discount&cmd=edit&id=[[|items.id|]]';" style="cursor: pointer; width: 20px; height: auto;" /></td><?php } ?>
                    </tr>
                    <!--/LIST:items-->
                </table>
            </td>
        </tr>
    </table>
</form>
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
    var items_js = [[|items_js|]];
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
        if(confirm('[[.are_you_delete_item.]]'))
        {
            jQuery(".check_list").removeAttr("checked");
            jQuery("#delete_ids_"+id).attr('checked','checked');
            MemberDiscountForm.submit();
        }
    }
    function delete_all_select()
    {
        if(confirm('[[.are_you_delete_selected.]]'))
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