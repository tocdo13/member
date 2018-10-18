<form name="MemberLevelDiscountForm" method="POST" style="margin: 10px auto;">
    <table cellpadding="5" cellspacing="5" border="1" bordercolor="#CCCCCC" style="margin: 10px auto;">
        <tr>
            <td colspan="3">
                <table style="width: 100%;" cellpadding="5" cellspacing="5">
                    <tr>
                        <td><h1>[[.add_discount_for_member_level.]]</h1></td>
                        <td>
                            <?php if(User::can_add(false,ANY_CATEGORY)){ ?> 
                            <input name="save" type="submit" id="save" value="[[.save.]]" onclick="return check_submit();" style="padding: 10px;" />
                            <input name="save_stay" type="submit" id="save_stay" value="[[.save_stay.]]" onclick="return check_submit();" style="padding: 10px;" />
                            <?php } ?>
                            <input name="back" type="button" id="back" value="[[.back.]]" onclick="window.location.href='?page=member_level';" style="padding: 10px;" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; width: 400px;"><h3>[[.list_member_discount.]]</h3><br />[[.pin_servie.]]: <select name="access_pin_service" id="access_pin_service" style=" padding: 10px;" onchange="select_pin_service();"></select></td>
            <td style="width: 30px; height: 30px; background: #EEEEEE;"></td>
            <td style="text-align: center; width: 400px;"><h3>[[.list_member_level_discount.]]</h3></td>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <table cellpadding="5" cellspacing="5" width="100%" border="1" bordercolor="#CCCCCC">
                    <tr style="height: 30px;">
                        <th style="text-align: center;">[[.description.]]</th>
                        <th style="width: 200px;">[[.from_date.]] [[.to_date.]]</th>
                        <th></th>
                    </tr>
                    <!--LIST:list_discount-->
                    <tr style="height: 30px;" class="department_code [[|list_discount.department_code|]]">
                        <td>
                            <b>([[|list_discount.access_pin_service_name|]] - <?php if([[=list_discount.is_parent=]]==''){ echo Portal::language('all_card'); }elseif([[=list_discount.is_parent=]]=='PARENT'){ echo Portal::language('parent_card'); }else{ echo Portal::language('son_card');} ?>)</b><br />
                            <strong><i>[[|list_discount.code|]]</i></strong>: <span style="text-transform: uppercase;">[[|list_discount.title|]]</span> <br />
                            <i>[[|list_discount.description|]]</i>
                        </td>
                        <td>
                            <?php if([[=list_discount.start_date=]]=='' AND [[=list_discount.start_date=]]!=''){ ?>
                                [[.from_date.]] [[.min.]] [[.to_date.]]: [[|list_discount.start_date|]]
                            <?php }elseif([[=list_discount.start_date=]]!='' AND [[=list_discount.start_date=]]==''){ ?>
                                [[.from_date.]]: [[|list_discount.start_date|]] [[.to_date.]] [[.max.]]
                            <?php }elseif([[=list_discount.start_date=]]=='' AND [[=list_discount.start_date=]]==''){ ?>
                                [[.not_limit_date.]]
                            <?php }else{ ?>
                                [[.from_date.]]: [[|list_discount.start_date|]] [[.to_date.]]: [[|list_discount.end_date|]]
                            <?php } ?>
                        </td>
                        <td style="text-align: center; width: 24px; cursor: pointer;"><?php if(User::can_add(false,ANY_CATEGORY)){ ?><img src="packages/hotel/skins/default/images/iosstyle/split.png" onclick="add_discount([[|list_discount.id|]]);" /><?php } ?></td>
                    </tr>
                    <!--/LIST:list_discount-->
                </table>
            </td>
            <td style="background: #EEEEEE;"></td>
            <td style="vertical-align: top;">
                <?php $input_count = 100; ?>
                <table id="list_member_level_discount" cellpadding="5" cellspacing="5" width="100%" border="1" bordercolor="#CCCCCC">
                    <tr style="height: 30px;">
                        <th>CTGG</th>
                        <th>[[.start_date.]]</th>
                        <th>[[.end_date.]]</th>
                        <th style="display: none;">[[.num_people_attach.]]</th>
                        <th>[[.delete.]]</th>
                    </tr>
                    <!--LIST:items-->
                    <?php $input_count++; ?>
                    <tr style="height: 30px;" id="tr_<?php echo $input_count; ?>">
                        <td>
                            <input name="items[<?php echo $input_count; ?>][id]" id="id_<?php echo $input_count; ?>" type="text" value="[[|items.id|]]" style="display: none;" />
                            [[|items.member_discount_code|]]: [[|items.title|]]
                            <input name="items[<?php echo $input_count; ?>][member_discount_code]" id="member_discount_code_<?php echo $input_count; ?>" type="text" value="[[|items.member_discount_code|]]" style="display: none;" />
                        </td>
                        <td><input class="date" name="items[<?php echo $input_count; ?>][start_date]" id="start_date_<?php echo $input_count; ?>" type="text" value="<?php echo [[=items.start_date=]]; ?>" onchange="check_date(<?php echo $input_count; ?>);" style="width: 80px;" /></td>
                        <td><input class="date" name="items[<?php echo $input_count; ?>][end_date]" id="end_date_<?php echo $input_count; ?>" type="text" value="<?php echo [[=items.end_date=]]; ?>" onchange="check_date(<?php echo $input_count; ?>);" style="width: 80px;" /></td>
                        <td style="display: none;"><input class="input_number" name="items[<?php echo $input_count; ?>][num_people]" id="num_people_<?php echo $input_count; ?>" type="text" value="[[|items.num_people|]]" style="width: 30px;" /></td>
                        <td style="text-align: center; width: 24px; cursor: pointer;" onclick="delete_item(<?php echo $input_count; ?>);"><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" /></td>
                    </tr>
                    <!--/LIST:items-->
                </table>
            </td>
        </tr>
    </table>
    <input name="delete_ids" type="text" id="delete_ids" style="display: none;" />
</form>
<script>
    var list_pin_service = [[|list_pin_service|]];
    console.log(list_pin_service);
    jQuery(".date").datepicker();
    var list_discount_js = [[|list_discount_js|]];
    var input_count = to_numeric(<?php echo $input_count; ?>);
    function select_pin_service()
    {
        var code = jQuery("#access_pin_service").val();
        console.log(code)
        if(code=='')
        {
            jQuery(".department_code").css('display','');
        }
        else
        {
            jQuery(".department_code").css('display','none');
            for(var i in list_pin_service)
            {
                jQuery("."+code).css('display','');
            }
        }
    }
    function add_discount(id)
    {
        input_count ++;
        var content = '<tr style="height: 30px;" id="tr_'+input_count+'">';
        content += '<td>';
        content += '<input name="items['+input_count+'][id]" id="id_'+input_count+'" type="text" value="(auto)" style="display: none;" />';
        content += ''+list_discount_js[id]["code"]+': '+list_discount_js[id]["title"];
        content += '<input name="items['+input_count+'][member_discount_code]" id="member_discount_code_'+input_count+'" type="text" value="'+list_discount_js[id]["code"]+'" style="display: none;" />';
        content += '</td>';
        content += '<td><input class="date" name="items['+input_count+'][start_date]" id="start_date_'+input_count+'" type="text" value="'+list_discount_js[id]["start_date"]+'" onchange="check_date('+input_count+');" style="width: 80px;" /></td>';
        content += '<td><input class="date" name="items['+input_count+'][end_date]" id="end_date_'+input_count+'" type="text" value="'+list_discount_js[id]["end_date"]+'" onchange="check_date('+input_count+');" style="width: 80px;" /></td>';
        content += '<td style="display: none;"><input class="input_number" name="items['+input_count+'][num_people]" id="num_people_'+input_count+'" type="text" value="" style="width: 30px;" /></td>'
        content += '<td style="text-align: center; width: 24px; cursor: pointer;" onclick="delete_item('+input_count+');"><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" /></td>'
        content += '</tr>';
        jQuery("#list_member_level_discount").append(content);
        jQuery('#start_date_'+input_count+'').datepicker();
        jQuery('#end_date_'+input_count+'').datepicker();
    }
    
    function delete_item(id)
    {
        if(jQuery("#id_"+id).val()!='(auto)')
        {
            if(jQuery("#delete_ids").val()=='')
            {
                jQuery("#delete_ids").val(jQuery("#id_"+id).val());
            }
            else
            {
                var id_arr = jQuery("#delete_ids").val().split(",");
                for(var i in id_arr)
                {
                    if(to_numeric(id_arr[i])!=to_numeric(jQuery("#id_"+id).val()))
                    {
                        var id_delete = jQuery("#delete_ids").val()+","+jQuery("#id_"+id).val();
                        jQuery("#delete_ids").val(id_delete);
                    }
                }
            }
        }
        jQuery("#tr_"+id).remove();
    }
    function check_submit()
    {
        var check=true;
        var $cond = ''
        for(var i=101;i<=input_count;i++)
        {
            if(jQuery("#id_"+i).val()!= undefined)
            {
                for(j=i+1;j<=input_count+1;j++)
                {
                    if(jQuery("#id_"+j).val()!= undefined)
                    {
                        if(jQuery("#member_discount_code_"+i).val()==jQuery("#member_discount_code_"+j).val())
                        {
                            if( jQuery("#start_date_"+i).val()=='' ||  jQuery("#end_date_"+i).val()=='' || jQuery("#start_date_"+j).val()=='' ||  jQuery("#end_date_"+j).val()=='' || ( count_date(jQuery("#start_date_"+i).val(),jQuery("#end_date_"+j).val())>=0 && count_date(jQuery("#start_date_"+j).val(),jQuery("#end_date_"+i).val())>=0 ) )
                            {
                                check = false;
                            }
                        }
                    }
                }
            }
        }
        if(check==false)
            alert("Chương trình áp dụng không được trùng thơi gian với nhau!");
        return check;
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var std_day=std[0];
    	var std_month=std[1];
    	var std_year=std[2];
    //----------------------------
    	var ed =end_day.split("/");
    	var ed_day=ed[0];
    	var ed_month=ed[1];
    	var ed_year=ed[2];
    //----------------------------
    	var startDAY=std_month+"/"+std_day+"/"+std_year;
    	var endDAY=ed_month+"/"+ed_day+"/"+ed_year;
    	var std_second=Date.parse(startDAY);
    	var ed_second=Date.parse(endDAY);
    	return (ed_second-std_second)/86400000;
    }
    
    function check_date(id)
    {
        if(jQuery("#start_date_"+id).val()!='' && jQuery("#end_date_"+id).val()!='')
        {
            var from_date = jQuery("#start_date_"+id).val().split("/");
            var to_date = jQuery("#end_date_"+id).val().split("/");
            var arr_from_date = new Date(from_date[2],from_date[1],from_date[0]);
            var arr_to_date = new Date(to_date[2],to_date[1],to_date[0]);
            if(arr_from_date>arr_to_date)
            {
                alert('start_date_not_greater_than_end_date');
                jQuery("#end_date_"+id).val('');
            }
        }
    }
</script>
