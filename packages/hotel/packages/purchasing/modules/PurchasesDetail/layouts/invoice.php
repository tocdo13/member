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
                    <th style="width: 50px; background: #2ab2be; color: #ffffff;"><input name="check_all_invoice" id="check_all_invoice"  type="checkbox" onclick="fun_check_all_invoice();" style="width: 15px; height: 15px;" />[[.all_1.]]</th>
                    <th colspan="8" style="text-align: center;"><h3 style="font-size: 20px; text-transform: uppercase;">[[.create_group_invoice.]]</h3></th>
                </tr>
                <tr style="background: #09435b; color: #ffffff; height: 30px; text-align: center;">
                    <th>...</th>
                    <th style="width: 50px;">[[.stt.]]</th>
                    <th style="width: 70px;">[[.product_code.]]</th>
                    <th>[[.product_name.]]</th>
                    <th style="width: 50px;">[[.quantity.]]</th>
                    <th style="width: 80px;">[[.price.]]</th>
                    <th style="width: 80px;">[[.total.]]</th>
                    <th colspan="2" style="width: 120px;">[[.status.]]</th>
                </tr>
                <!--LIST:non_invoice-->
                <tr style="background: #ebfbff; text-align: center;">
                    <td><input name="non[[[|non_invoice.stt|]]][check_box]" id="non_[[|non_invoice.stt|]]"  type="checkbox" style="text-align: left; float: left;" onclick="fun_check_all_non([[|non_invoice.stt|]]);" /><div id="open_[[|non_invoice.stt|]]" onclick="fun_open([[|non_invoice.stt|]]);" style="padding:5px; width: 10px; height: 10px; line-height: 10px; text-align: center; border: 1px solid #eeeeee; float: left; cursor: pointer;" class="icon_button">+</div>   <div id="close_[[|non_invoice.stt|]]" onclick="fun_close([[|non_invoice.stt|]]);" style="padding:5px; width: 10px; height: 10px; line-height: 10px; text-align: center; border: 1px solid #eeeeee; float: left; cursor: pointer; display: none;" class="icon_non_button">-</div></td>
                    <td>[[|non_invoice.stt|]]</td>
                    <td>[[|non_invoice.product_code|]]</td>
                    <td>[[|non_invoice.product_name|]]</td>
                    <td><input name="non[[[|non_invoice.stt|]]][quantity]" id="non_quantity_[[|non_invoice.stt|]]" type="text" value="[[|non_invoice.quantity|]]" class="input_number" style="width: 50px; text-align: center; border: none; background: #ebfbff;" readonly="" /></td>
                    <td><input name="non[[[|non_invoice.stt|]]][price]" id="non_price_[[|non_invoice.stt|]]" type="text" value="0" class="input_number" style="width: 70px; text-align: right" onkeyup="check_price(0);" /></td>
                    <td><input name="non[[[|non_invoice.stt|]]][total]" id="non_total_[[|non_invoice.stt|]]" type="text" value="0" class="input_number" style="width: 70px; text-align: right" onkeyup="check_price(1);" /></td>
                    <td colspan="2">[[.non_invoice.]]</td>
                </tr>
                <tr class="[[|non_invoice.id|]]" style="display: none; background: #f1f1f1; height: 30px; text-align: center;">
                    <th style="border-left: 3px solid #000000;">...</th>
                    <th>link</th>
                    <th>[[.product_code.]]</th>
                    <th>[[.product_name.]]</th>
                    <th>[[.quantity.]]</th>
                    <th>[[.price.]]</th>
                    <th>[[.total.]]</th>
                    <th>[[.supplier.]]</th>
                    <th style="border-right: 3px solid #000000;">[[.note.]]</th>
                </tr>
                <!--LIST:non_invoice.child-->
                <tr class="[[|non_invoice.id|]]" style="display: none; text-align: center;">
                    <td style="border-left: 3px solid #000000; text-align: center;"><input name="non[[[|non_invoice.stt|]]][detail][[[|non_invoice.child.id|]]][id]" id="detail_[[|non_invoice.child.id|]]" type="checkbox" style="display: ;" onclick="check_non([[|non_invoice.child.id|]],[[|non_invoice.stt|]]);" value="[[|non_invoice.child.quantity|]]" /></td>
                    <td><a href="?page=purchases_detail&cmd=edit&id=[[|non_invoice.child.purchases_id|]]" target="_blank">ĐX_[[|non_invoice.child.purchases_id|]]</a></td>
                    <td>[[|non_invoice.child.product_code|]]</td>
                    <td>[[|non_invoice.child.product_name|]]</td>
                    <td><input name="non[[[|non_invoice.stt|]]][detail][[[|non_invoice.child.id|]]][quantity]" id="detail_quantity_[[|non_invoice.child.id|]]" type="text" value="[[|non_invoice.child.quantity|]]" class="input_number" style="width: 50px; text-align: center;" onkeyup="check_price(1);" /></td>
                    <td><input name="non[[[|non_invoice.stt|]]][detail][[[|non_invoice.child.id|]]][price]" id="detail_price_[[|non_invoice.child.id|]]" type="text" value="0" class="input_number" style="width: 70px; text-align: right; border: none;" readonly="" /></td>
                    <td><input name="non[[[|non_invoice.stt|]]][detail][[[|non_invoice.child.id|]]][total]" id="detail_total_[[|non_invoice.child.id|]]" type="text" value="0" class="input_number" style="width: 70px; text-align: right; border: none" readonly="" /></td>
                    <td><select  name="non[[[|non_invoice.stt|]]][detail][[[|non_invoice.child.id|]]][supplier_code]" style="width: 80px;"><option value="">----ALL----</option>[[|supplier_list|]]</select></td>
                    <td style="border-right: 3px solid #000000;">[[|non_invoice.child.note|]]</td>
                </tr>
                <!--/LIST:non_invoice.child-->
                <!--/LIST:non_invoice-->
                <tr style="height: 35px;">
                    <td colspan="9" style="text-align: right;"><span style="font-weight: bold;">[[.total_amount.]] : </span><input name="total_amount" id="total_amount" type="text" value="0" readonly="" style="text-align: right;" /></td>
                </tr>
                <tr>
                    <td colspan="9" style="text-align: right;"><input name="create_group_invoice" id="create_group_invoice" type="submit" value="[[.create_group_invoice.]]" style=" margin: 10px; padding: 15px; border: none; background: #facc39; color: #000000; font-weight: bold;" onclick="return check_checkbox();" /></td>
                </tr>
            </table>
        </fieldset>
    <?php } ?>
</form>
<script>
    var non_invoice = [[|non_invoice_list|]];
    console.log(non_invoice);
    function check_non(id,key)
    {
        console.log(2);
        if(document.getElementById("detail_"+id).checked==true)
        {
            console.log(key);
            document.getElementById("non_"+key).checked=true;
        }
        else
        {
            check=false;
            for(var i in non_invoice)
            {
                if(to_numeric(non_invoice[i]['stt'])==key)
                {
                    for(var j in non_invoice[i]['child'])
                    {
                        if(document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked==true)
                        {
                            check=true;
                        }
                    }
                }
            }
            if(check==true)
            {
                document.getElementById("non_"+key).checked=true;
            }
            else
            {
                document.getElementById("non_"+key).checked=false;
            }
        }
    }
    function check_price(key)
    {
        var total_amount = 0;
        console.log(111);
        for(var i in non_invoice)
        {
            console.log(333);
            if(document.getElementById("non_"+non_invoice[i]['stt']).checked==true)
            {
                quantity = 0;
                check=false;
                for(var j in non_invoice[i]['child'])
                {
                    if(document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked==true)
                    {
                        check=true;
                        quantity += to_numeric(jQuery("#detail_quantity_"+non_invoice[i]['child'][j]['id']).val());
                    }
                }
                console.log(quantity);
                if(check==true)
                {
                    jQuery("#non_quantity_"+non_invoice[i]['stt']).val(number_format(quantity));
                    if(key==1)
                    {
                        total = to_numeric(jQuery("#non_total_"+non_invoice[i]['stt']).val());
                        price = total/quantity;
                        jQuery("#non_price_"+non_invoice[i]['stt']).val(number_format(price));
                        jQuery("#non_total_"+non_invoice[i]['stt']).val(number_format(total));
                    }
                    else
                    {
                        price = to_numeric(jQuery("#non_price_"+non_invoice[i]['stt']).val());
                        total = price*quantity;
                        jQuery("#non_total_"+non_invoice[i]['stt']).val(number_format(total));
                        jQuery("#non_price_"+non_invoice[i]['stt']).val(number_format(price));
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
                    jQuery("#non_price_"+non_invoice[i]['stt']).val(0);
                    jQuery("#non_total_"+non_invoice[i]['stt']).val(0);
                }
            }
            else
            {
                jQuery("#non_price_"+non_invoice[i]['stt']).val(0);
                jQuery("#non_total_"+non_invoice[i]['stt']).val(0);
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
                document.getElementById("non_"+non_invoice[i]['stt']).checked=true;
                fun_check_all_non(non_invoice[i]['stt']);
            }
        }
        else
        {
            for(var i in non_invoice)
            {
                document.getElementById("non_"+non_invoice[i]['stt']).checked=false;
                fun_check_all_non(non_invoice[i]['stt']);
            }
        }
        check_price(1);
    }
    function fun_check_all_non(id)
    {
        
        if(document.getElementById("non_"+id).checked==true)
        {
            for(var i in non_invoice)
            {
                if(non_invoice[i]['stt']==id)
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
                if(non_invoice[i]['stt']==id)
                {
                    for(var j in non_invoice[i]['child'])
                    {
                        document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked=false;
                    }
                }
            }
        }
        check_price(1);
    }
    function check_checkbox()
    {
        var check=false;
        for(var i in non_invoice)
        {
            if(document.getElementById("non_"+non_invoice[i]['stt']).checked==true)
            {
                for(var j in non_invoice[i]['child'])
                {
                    if(document.getElementById("detail_"+non_invoice[i]['child'][j]['id']).checked==true)
                    {
                        check=true;
                    }
                }
            }
        }
        if(check==false)
        {
            alert('Bạn chưa chọn đề xuất nào để tạo hóa đơn tổng, hãy chọn một hoặc nhiều đề xuất!');
            return false;
        }
        else
        {
            return true;
        }
    }
</script>