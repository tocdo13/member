<style>
    a
    {
        text-decoration: none;
        color: #00b2f9;
        font-weight: bold;
    }
    a:hover
    {
        text-decoration: none;
        color: #00b2f9;
        font-weight: bold;
    }
    a:active
    {
        text-decoration: none;
        color: #00b2f9;
        font-weight: bold;
    }
    a:visited
    {
        text-decoration: none;
        color: #00b2f9;
        font-weight: bold;
    }
    .icon_button
    {
        background: #2ab2be;
        transition: all 0.5s ease-out;
        color: #ffffff;
    }
    .icon_button:hover
    {
        background: #f04d4e;
    }
    .icon_non_button
    {
        background: #f04d4e;
        transition: all 0.5s ease-out;
        color: #ffffff;
    }
    .icon_non_button:hover
    {
        background: #2ab2be;
    }
</style>
<form name="ListPurchasesProposedForm" method="post">
    <?php if(sizeof([[=non_invoice=]])>0){ ?>
        <fieldset style="width: 820px; margin: 20px auto; box-shadow: 0px 0px 5px #555555; border: 2px solid #2ab2be;">
            <legend style="width: 40px; background: #ffffff; height: 40px; overflow: hidden; text-align: center; border-radius: 50%; border: 3px solid #2ab2be; box-shadow: 0px 0px 5px #09435b;"><img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\iconarchive.png" style="width: 40px; height: auto;" /></legend>
            <table cellpadding="5" cellspacing="5" border="1" style="width: 98%; margin: 1% auto;">
                <tr>
                    <td colspan="10" style="text-align: center;"><h3 style="font-size: 20px; text-transform: uppercase;">[[.edit_group_invoice.]]</h3></td>
                </tr>
                <tr style="background: #09435b; color: #ffffff; height: 30px; text-align: center;">
                    <th>...</th>
                    <th style="width: 50px;">[[.id.]]</th>
                    <th style="width: 70px;">[[.product_code.]]</th>
                    <th>[[.product_name.]]</th>
                    <th style="width: 50px;">[[.quantity.]]</th>
                    <th style="width: 80px;">[[.price.]]</th>
                    <th style="width: 80px;">[[.total.]]</th>
                    <th style="width: 120px;">[[.status.]]</th>
                    <th colspan="2">[[.confirm_1.]]</th>
                </tr>
                <!--LIST:non_invoice-->
                <tr style="background: #ebfbff; text-align: center;">
                    <td><input name="non[[[|non_invoice.id|]]][id]" id="non_[[|non_invoice.id|]]"  type="checkbox" checked="checked" value="[[|non_invoice.id|]]" style="text-align: left; float: left; display: none;" onclick="fun_check_all_non([[|non_invoice.id|]]);" /><div id="open_[[|non_invoice.id|]]" onclick="fun_open([[|non_invoice.id|]]);" style="padding:5px; width: 10px; height: 10px; line-height: 10px; text-align: center; border: 1px solid #eeeeee; float: left; cursor: pointer;" class="icon_button">+</div>   <div id="close_[[|non_invoice.id|]]" onclick="fun_close([[|non_invoice.id|]]);" style="padding:5px; width: 10px; height: 10px; line-height: 10px; text-align: center; border: 1px solid #eeeeee; float: left; cursor: pointer; display: none;" class="icon_non_button">-</div></td>
                    <td>[[|non_invoice.id|]]</td>
                    <td>[[|non_invoice.product_code|]]</td>
                    <td>[[|non_invoice.product_name|]]</td>
                    <td><input name="non[[[|non_invoice.id|]]][quantity]" id="non_quantity_[[|non_invoice.id|]]" type="text" value="[[|non_invoice.quantity|]]" class="input_number" style="width: 50px; text-align: center; border: none; background: #ebfbff;" readonly="" /></td>
                    <td><input name="non[[[|non_invoice.id|]]][price]" id="non_price_[[|non_invoice.id|]]" type="text" value="<?php echo System::display_number([[=non_invoice.price=]]); ?>" class="input_number" style="width: 70px; text-align: right" onkeyup="check_price(0);" /></td>
                    <td><input name="non[[[|non_invoice.id|]]][total]" id="non_total_[[|non_invoice.id|]]" type="text" value="<?php echo System::display_number([[=non_invoice.total=]]); ?>" class="input_number" style="width: 70px; text-align: right" onkeyup="check_price(1);" /></td>
                    <td><?php if([[=non_invoice.status=]]=='CONFIRMING'){ ?> [[.confirming.]] <?php }else{ ?> [[.confirm.]] <?php } ?></td>
                    <td colspan="2"><input name="non[[[|non_invoice.id|]]][confirm]" id="non_confirm_[[|non_invoice.id|]]" <?php if([[=non_invoice.status=]]!='CONFIRMING'){ ?> checked="checked" <?php } ?> type="checkbox" style="text-align: left; float: left; display: ;" /></td>
                </tr>
                <tr class="non_[[|non_invoice.id|]]" style="display: none; background: #f1f1f1; height: 30px; text-align: center;">
                    <th style="">...</th>
                    <th>link</th>
                    <th>[[.product_code.]]</th>
                    <th>[[.product_name.]]</th>
                    <th>[[.quantity.]]</th>
                    <th>[[.price.]]</th>
                    <th>[[.total.]]</th>
                    <th>[[.supplier.]]</th>
                    <th style="">[[.note.]]</th>
                    <th>[[.delete.]]</th>
                </tr>
                <!--LIST:non_invoice.child-->
                <tr class="non_[[|non_invoice.id|]]" id="child_[[|non_invoice.child.id|]]" style="display: none; text-align: center;">
                    <td style="text-align: center;"><input name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][id]" id="detail_[[|non_invoice.child.id|]]" type="checkbox" checked="checked" style="display: none;" value="[[|non_invoice.child.id|]]" /><input name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][key]" id="detail_key_[[|non_invoice.child.id|]]" type="checkbox" checked="checked" style="display: none;" value="[[|non_invoice.child.id|]]" /></td>
                    <td><a href="?page=purchases_detail&cmd=edit&id=[[|non_invoice.child.purchases_id|]]" target="_blank">ĐX_[[|non_invoice.child.purchases_id|]]</a></td>
                    <td>[[|non_invoice.child.product_code|]]</td>
                    <td>[[|non_invoice.child.product_name|]]</td>
                    <td><input name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][quantity]" id="detail_quantity_[[|non_invoice.child.id|]]" type="text" value="[[|non_invoice.child.quantity|]]" class="input_number" style="width: 50px; text-align: center;" onkeyup="check_total(1);" /></td>
                    <td><input name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][price]" id="detail_price_[[|non_invoice.child.id|]]" type="text" value="<?php echo System::display_number([[=non_invoice.child.price=]]); ?>" class="input_number" style="width: 70px; text-align: right;" onkeyup="check_total(1);" /></td>
                    <td><input name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][total]" id="detail_total_[[|non_invoice.child.id|]]" type="text" value="<?php echo System::display_number([[=non_invoice.child.total=]]); ?>" class="input_number" style="width: 70px; text-align: right; border: navajowhite;" readonly="" /></td>
                    <td><select  name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][supplier_code]" id="detail_supplier_code_[[|non_invoice.child.id|]]" style="width: 80px;"><option value="">----ALL----</option>[[|supplier_list|]]</select></td>
                    <td style=""><input name="non[[[|non_invoice.id|]]][detail][[[|non_invoice.child.id|]]][note]" id="detail_note_[[|non_invoice.child.id|]]" type="text" value="[[|non_invoice.child.note|]]" style="width: 90px;" /></td>
                    <td><div id="delete_[[|non_invoice.id|]]" onclick="fun_delete([[|non_invoice.child.id|]],[[|non_invoice.id|]]);" style="padding:5px; width: 10px; height: 10px; line-height: 10px; text-align: center; border: 1px solid #eeeeee; float: left; cursor: pointer;" class="icon_non_button">X</div></td>
                </tr>
                <!--/LIST:non_invoice.child-->
                <!--/LIST:non_invoice-->
                <tr>
                    <td colspan="10" style="text-align: right; height: 35px;"><span style="font-weight: bold;">[[.total_amount.]]:</span> <input name="total_amount" id="total_amount" type="text" value="<?php echo System::display_number([[=total=]]) ?>" readonly="" style="text-align: right;" /></td>
                </tr>
                <tr style="text-align: center;">
                    <td colspan="10" style="text-align: center;">
                        <input name="save" id="save" type="submit" value="[[.save.]]" style=" margin: 10px; padding: 15px; border: none; background: #facc39; color: #000000; font-weight: bold;" />
                        <?php if(User::can_admin(false,ANY_CATEGORY)){ ?> <input name="confirm" id="confirm" type="submit" value="[[.confirm_all.]]" style=" margin: 10px; padding: 15px; border: none; background: #e1671a; color: #000000; font-weight: bold;" /> <?php } ?>
                        <input name="back" id="back" type="submit" value="[[.back.]]" style=" margin: 10px; padding: 15px; border: none; background: #18bebc; color: #000000; font-weight: bold;" />
                        <input name="cancel" id="cancel" value="[[.cancel_confirm.]]" type="submit" style=" margin: 10px; padding: 15px; border: none; background: #0f796f; color: #000000; font-weight: bold;" />
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } ?>
</form>
<script>
    var non_invoice = [[|non_invoice_list|]];
    console.log(non_invoice);
    for(var i in non_invoice)
    {
        for(var j in non_invoice[i]['child'])
        {
            jQuery("#detail_supplier_code_"+non_invoice[i]['child'][j]['id']).val(non_invoice[i]['child'][j]['supplier_code']);
        }
    }
    function fun_delete(id,key)
    {
        if(confirm('BẠN CHẮC CHẮN MUỐN XÓA ĐỀ XUẤT NÀY!'))
        {
            jQuery("#child_"+id).css('display','none');
            document.getElementById("detail_"+id).checked=false;
            jQuery("#child_"+id).removeClass("non_"+key);
            check_total(1);
        }
    }
    function check_price(key)
    {
        var total_amount = 0;
        console.log(111);
        for(var i in non_invoice)
        {
            console.log(333);
            if(document.getElementById("non_"+non_invoice[i]['id']).checked==true)
            {
                quantity = 0;
                check=false;
                for(var j in non_invoice[i]['child'])
                {
                    if(document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked==true)
                    {
                        console.log(non_invoice[i]['child'][j]['id']);
                        check=true;
                        quantity += to_numeric(jQuery("#detail_quantity_"+non_invoice[i]['child'][j]['id']).val());
                    }
                }
                console.log(quantity);
                if(check==true)
                {
                    jQuery("#non_quantity_"+non_invoice[i]['id']).val(quantity);
                    if(key==1)
                    {
                        total = to_numeric(jQuery("#non_total_"+non_invoice[i]['id']).val());
                        price = total/quantity;
                        jQuery("#non_price_"+non_invoice[i]['id']).val(number_format(price));
                        jQuery("#non_total_"+non_invoice[i]['id']).val(number_format(total));
                    }
                    else
                    {
                        price = to_numeric(jQuery("#non_price_"+non_invoice[i]['id']).val());
                        total = price*quantity;
                        jQuery("#non_total_"+non_invoice[i]['id']).val(number_format(total));
                        jQuery("#non_price_"+non_invoice[i]['id']).val(number_format(price));
                    }
                    for(var j in non_invoice[i]['child'])
                    {
                        if(document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked==true)
                        {
                            jQuery("#detail_total_"+non_invoice[i]['child'][j]['id']).val(number_format(price*to_numeric(jQuery("#detail_quantity_"+non_invoice[i]['child'][j]['id']).val())));
                            jQuery("#detail_price_"+non_invoice[i]['child'][j]['id']).val(number_format(price));
                        }
                    }
                    total_amount += total
                }
                else
                {
                    jQuery("#non_price_"+non_invoice[i]['id']).val(0);
                    jQuery("#non_total_"+non_invoice[i]['id']).val(0);
                }
            }
            else
            {
                jQuery("#non_price_"+non_invoice[i]['id']).val(0);
                jQuery("#non_total_"+non_invoice[i]['id']).val(0);
            }
        }
        jQuery("#total_amount").val(number_format(total_amount));
    }
    function check_total(key)
    {
        var total_amount = 0;
        for(var i in non_invoice)
        {
            if(document.getElementById("non_"+non_invoice[i]['id']).checked==true)
            {
                quantity = 0;
                total = 0;
                price = 0;
                check=false;
                for(var j in non_invoice[i]['child'])
                {
                    if(document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked==true)
                    {
                        check=true;
                        quantity += to_numeric(jQuery("#detail_quantity_"+non_invoice[i]['child'][j]['id']).val());
                        price = to_numeric(jQuery("#detail_price_"+non_invoice[i]['child'][j]['id']).val());
                        total += to_numeric(jQuery("#detail_quantity_"+non_invoice[i]['child'][j]['id']).val()) * to_numeric(jQuery("#detail_price_"+non_invoice[i]['child'][j]['id']).val());
                        jQuery("#detail_total_"+non_invoice[i]['child'][j]['id']).val(number_format(to_numeric(jQuery("#detail_quantity_"+non_invoice[i]['child'][j]['id']).val()) * to_numeric(jQuery("#detail_price_"+non_invoice[i]['child'][j]['id']).val())));
                    }
                }
                if(check==true)
                {
                    jQuery("#non_quantity_"+non_invoice[i]['id']).val(number_format(quantity));
                    jQuery("#non_total_"+non_invoice[i]['id']).val(number_format(total));
                    jQuery("#non_price_"+non_invoice[i]['id']).val(number_format(total/quantity));
                    total_amount += total
                }
                else
                {
                    jQuery("#non_price_"+non_invoice[i]['id']).val(0);
                    jQuery("#non_total_"+non_invoice[i]['id']).val(0);
                }
            }
            else
            {
                jQuery("#non_price_"+non_invoice[i]['id']).val(0);
                jQuery("#non_total_"+non_invoice[i]['id']).val(0);
            }
        }
        jQuery("#total_amount").val(number_format(total_amount));
    }
    function fun_open(id)
    {
        jQuery(".non_"+id).css('display','');
        jQuery("#open_"+id).css('display','none');
        jQuery("#close_"+id).css('display','');
    }
    function fun_close(id)
    {
        jQuery(".non_"+id).css('display','none');
        jQuery("#open_"+id).css('display','');
        jQuery("#close_"+id).css('display','none');
    }
    function fun_check_all_invoice()
    {
        if(document.getElementById("check_all_invoice").checked==true)
        {
            for(var i in non_invoice)
            {
                document.getElementById("non_"+non_invoice[i]['id']).checked=true;
                fun_check_all_non(non_invoice[i]['id']);
            }
        }
        else
        {
            for(var i in non_invoice)
            {
                document.getElementById("non_"+non_invoice[i]['id']).checked=false;
                fun_check_all_non(non_invoice[i]['id']);
            }
        }
    }
    function fun_check_all_non(id)
    {
        
        if(document.getElementById("non_"+id).checked==true)
        {
            for(var i in non_invoice)
            {
                if(non_invoice[i]['id']==id)
                {
                    for(var j in non_invoice[i]['child'])
                    {
                        document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked=true;
                    }
                }
            }
        }
        else
        {
            for(var i in non_invoice)
            {
                if(non_invoice[i]['id']==id)
                {
                    for(var j in non_invoice[i]['child'])
                    {
                        document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked=false;
                    }
                }
            }
        }
    }
</script>