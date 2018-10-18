<style>
    *{
        margin: 0px;
        padding: 0px;
        font-weight: normal;
    }
    table tr td, table tr th
    {
        padding: 5px;
    }
    input
    {
        text-align: right;
    }
    .button_delete
    {
        width: 25px; height: 25px;
        line-height: 25px;
        text-align: center;
        font-size: 15px;
        background: #09435b;
        color: #ffffff;
        transition: all 0.5s linear;
        cursor: ;
        box-shadow: 0px 0px 2px #171717;
        border-radius: 50%;
        margin: 0px auto;
    }
    .button_delete:hover
    {
        background: #ffffff;
        color: #09435b;
        cursor: pointer;
        box-shadow: 0px 0px 7px #171717;
    }
    .input_submit
    {
        padding: 5px;
        background: #09435b;
        color: #ffffff;
        font-weight: bold;
        border: 2px solid #09435b;
        box-shadow: 0px 0px 3px #171717;
        margin: 0px 5px 0px 5px;
        transition: all 0.5s linear;
        cursor: ;
    }
    .input_submit:hover
    {
        background: #ffffff;
        color: #09435b;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0px 0px 20px #171717;
    }
    #content table tr td span
    {
        font-weight: bolder;
    }
</style>
<form name="InvoicePurchasesInvoiceForm" method="post">
<div id="container">
    <div id="content">
        <table cellpadding="5" cellspacing="5" border="0" bordercolor="#555555" style="width: 98%; margin: 1%;">
            <tr>
                <td style="width: 450px; border: 1px dashed #dddddd; text-align: center;"><h3 style="font-weight: bold; font-size: 17px; color: #09435b; text-transform: uppercase;">[[.purchases_invoice_number.]] [[|id|]]</h3></td>
                <td rowspan="5" style="text-align: right;">
                    <input name="save" type="submit" value="[[.save.]]" class="input_submit" />
                    <?php if([[=status=]]=='CONFIRMING'){ ?>
                        <input name="confirm" type="submit" value="[[.confirm.]]" class="input_submit" />
                    <?php }else{ ?>
                        <input name="view" type="submit" value="[[.view_invoice.]]" class="input_submit" />
                    <?php if([[=warehouse_invoice_id=]]==0){ ?>
                        <input name="warehouse" type="submit" value="[[.import_warehouse.]]" class="input_submit" />
                    <?php } else{ ?>
                        [[.warehoused.]]
                    <?php } } ?>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px dashed #dddddd;">[[.creater.]]: <span>[[|creater|]]</span> | [[.create_time.]]: <span>[[|create_time|]]</td>
            </tr>
            <tr>
                <td style="border-left: 1px dashed #dddddd;">[[.supplier.]]: <span>[[|supplier_name|]]</span></td>
            </tr>
            <tr>
                <td style="border-left: 1px dashed #dddddd;">[[.total_amount.]]: <span id="total"><?php echo System::display_number([[=total=]]); ?></span> </td>
            </tr>
            <tr>
                <td style="border-left: 1px dashed #dddddd; border-bottom: 1px dashed #dddddd;">[[.status.]]: <span><?php if([[=status=]]=='CONFIRMING'){ ?>[[.confirming.]]<?php }else{ if([[=warehouse_invoice_id=]]==0){ ?>[[.confirm.]]<?php } else{ ?>[[.warehoused.]]<?php } } ?></span></td>
            </tr>
        </table>
    </div>
    <div id="list_product">
        <table cellpadding="5" cellspacing="5" border="1" bordercolor="#555555" style="width: 98%; margin: 1%;">
            <tr>
                <td style="text-align: left;" colspan="13">
                    <?php if([[=warehouse_invoice_id=]]==0){ ?> 
                    <input name="delete_all" type="submit" value="[[.delete_all_select.]]" class="input_submit" onclick="return delete_all_check();" />
                    <?php } ?>
                </td>
            </tr>
            <tr style="text-align: center; text-transform: uppercase; background: #09435b; color: #ffffff;">
                <th><input name="check_all" type="checkbox" id="check_all" onclick="fun_check_all();" /></th>
                <th>[[.stt.]]</th>
                <th>[[.product_name.]]</th>
                <th>[[.unit_name.]]</th>
                <th>[[.category.]]</th>
                <th>[[.department.]]</th>
                <th>[[.quantity.]]</th>
                <th>[[.price.]]</th>
                <th>[[.total.]]</th>
                <th>[[.creater.]]</th>
                <th>[[.create_time.]]</th>
                <th>[[.proposed_code.]]</th>
                <th>[[.delete.]]</th>
            </tr>
            <!--LIST:product-->
            <tr style="text-align: center;" id="tr_[[|product.id|]]">
                <td><input type="checkbox" name="product[[[|product.id|]]][id]" id="id_[[|product.id|]]" onclick="fun_check_child();" class="check_child" value="[[|product.id|]]" /></td>
                <td>[[|product.stt|]]</td>
                <td style="text-align: left;">[[|product.product_name|]]</td>
                <td>[[|product.unit_name|]]</td>
                <td>[[|product.product_category_name|]]</td>
                <td>[[|product.department|]]</td>
                <td><input type="text" name="product[[[|product.id|]]][quantity]" id="quantity_[[|product.id|]]" value="<?php echo System::display_number([[=product.quantity=]]); ?>" <?php if([[=warehouse_invoice_id=]]!=0){ ?>readonly=""<?php } ?> onkeyup="update_total();" /></td>
                <td><input type="text" name="product[[[|product.id|]]][price]" id="price_[[|product.id|]]" value="<?php echo System::display_number([[=product.price=]]); ?>" <?php if([[=warehouse_invoice_id=]]!=0){ ?>readonly=""<?php } ?> onkeyup="update_total();" /></td>
                <td><input type="text" name="product[[[|product.id|]]][total]" id="total_[[|product.id|]]" value="<?php echo System::display_number([[=product.total=]]); ?>" readonly="" onkeyup="update_total();" /></td>
                <td>[[|product.user_name|]]</td>
                <td>[[|product.create_time|]]</td>
                <td><a href="?page=purchases_detail&cmd=edit&id=[[|product.dx_id|]]">ĐX_[[|product.dx_id|]]</a></td>
                <td>
                    <?php if([[=warehouse_invoice_id=]]==0){ ?>  
                        <div class="button_delete" onclick="func_delete([[|product.id|]]);">X</div>
                        <input type="checkbox" name="product[[[|product.id|]]][delete]" id="delete_[[|product.id|]]" style="display: none;" value="[[|product.id|]]" />
                    <?php } ?>
                </td>
            </tr>
            <!--/LIST:product-->
            <tr>
                <td style="text-align: left;" colspan="13">
                    <?php if([[=warehouse_invoice_id=]]==0){ ?> 
                    <input name="delete_all" type="submit" value="[[.delete_all_select.]]" class="input_submit" onclick="return delete_all_check();" />
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
</div>
</form>
<script>
    var product = <?php echo String::array2js([[=product=]]); ?>;
    console.log(product);
    function update_total()
    {
        var total_amount = 0;
        for(var i in product)
        {
            quantity = to_numeric(jQuery("#quantity_"+product[i]['id']).val());
            if(quantity>to_numeric(product[i]['quantity']))
            {
                alert("Không được nhập số lượng lớn hơn số lượng thực tế!");
                quantity = to_numeric(product[i]['quantity']);
            }
            console.log(quantity);
            jQuery("#quantity_"+product[i]['id']).val(number_format(quantity));
            price = to_numeric(jQuery("#price_"+product[i]['id']).val());
            console.log(price);
            jQuery("#price_"+product[i]['id']).val(number_format(price));
            total = quantity*price;
            console.log(total);
            jQuery("#total_"+product[i]['id']).val(number_format(total));
            total_amount += total; 
        }
        console.log(total_amount);
        jQuery("#total").html(number_format(total_amount));
    }
    function fun_check_all()
    {
        if(document.getElementById("check_all").checked==true)
        {
            jQuery(".check_child").attr("checked","checked");
        }
        else
        {
            jQuery(".check_child").removeAttr("checked");
        }
    }
    function fun_check_child()
    {
        var check=true;
        for(var i in product)
        {
            if(document.getElementById("id_"+i).checked==false)
            {
                check=false;
                document.getElementById("check_all").checked=false;
            }
        }
        if(check==true)
        {
            document.getElementById("check_all").checked=true;
        }
    }
    function func_delete(id)
    {
        if(confirm("Bạn có chắc chắn muốn xóa sản phẩm này?"))
        {
            document.getElementById("delete_"+id).checked=true;
            jQuery("#quantity_"+id).val(0);
            jQuery("#price_"+id).val(0);
            update_total();
            jQuery("#tr_"+id).css('display','none');
        }
    }
    function delete_all_check()
    {
        var check=false;
        if(confirm("Bạn muốn xóa tất cả lựa chọn?"))
        {
            for(var id in product)
            {
                if(document.getElementById("id_"+id).checked==true)
                {
                    check=true;
                    document.getElementById("delete_"+id).checked=true;
                    jQuery("#quantity_"+id).val(0);
                    jQuery("#price_"+id).val(0);
                    update_total();
                    jQuery("#tr_"+id).css('display','none');
                }
            }
        }
        return check;
    }
</script>