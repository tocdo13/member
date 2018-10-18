<style>
    label.lbl_style {
        line-height: 20px;
        padding: 5px;
        background: #beecff;
        color: #171717;
        margin: 0px 0px 0px 5px;
        font-weight: bold;
    }
    .ipt_style {
        height: 25px;
        border: 1px solid #DDDDDD;
        padding: 0px 5px;
    }
</style>
<form name="EditPcOrderForm" method="POST" enctype="multipart/form-data">
    <div style="width: 1100px; height: auto; margin: 30px auto; padding: 5px; box-shadow: 0px 0px 3px #555555; border-top: 5px solid #00B2F9; border-bottom: 5px solid #00B2F9;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <img src="packages/hotel/packages/purchasing/skins/default/images/purchases_proposed/iconarchive.png" style="width: 50px; height: auto; float: left;" />
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;">[[.edit_order_cancel.]]</h3>
                </td>
                <td style="text-align: right;">
                    <input value="" name="act" id="act" type="text" style="display: none;" />
                    <input value="[[|status|]]" name="status" id="status" type="text" style="display: none;" />
                    <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                    <input name="reissue" id="reissue" type="button" onclick="jQuery('#act').val('reissue');EditPcOrderForm.submit();" value="[[.reissue.]]" style="padding: 10px;" />
                    <?php } ?>
                </td>
            </tr>
            <tr style="border-bottom: 1px dashed #EEEEEE;">
                <td colspan="2">
                    <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <?php if(isset([[=no_data=]])){ ?>
                        <div style="margin: 10px auto; width: 200px; height: 70px; line-height: 70px; text-align: center; border: 2px solid #00B2F9;">
                            <b>[[.no_request.]]</b>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        
        <?php $i=101; ?>
        <?php if(!isset([[=no_data=]])){ ?>
        <table style="width: 100%; margin: 10px auto;" cellspacing="5" cellpadding="10">
            <tr>
                <td>
                    <label class="lbl_style">[[.create_time.]]</label> : 
                    <input id="create_time" name="create_time" type="text" class="ipt_style" style="width: 80px;" value="[[|create_time|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?>  />
                </td>
                <td>
                    <label class="lbl_style">[[.order_code.]]</label> : 
                    <input id="order_code" name="order_code" type="text" class="ipt_style" style="width: 120px; font-size: 10px; text-align: center; background: #DDDDDD;" readonly="" value="[[|code|]]" />
                </td>
                <td colspan="2">
                    <label class="lbl_style">[[.order_name.]]</label> : 
                    <input id="order_name" name="order_name" type="text" class="ipt_style" style="width: 160px;" value="[[|name|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <label class="lbl_style">[[.supplier.]]</label> : 
                    <select id="pc_supplier_id" name="pc_supplier_id" class="ipt_style" style="width: 150px;" onchange="fun_select_sup(false);">[[|list_total_amount_sup_option|]]</select>
                    <input type="hidden" name="supplier_id" id="supplier_id" value="[[|pc_supplier_id|]]" /> 
                </td>
                <td colspan="3">
                    <label class="lbl_style">[[.description.]]</label><br />
                    <textarea id="description" name="description" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> style="width: 560px; height: 50px; border: 1px solid #DDDDDD;">[[|description|]]</textarea>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="width: 100%; background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
                        <tr style="text-transform: uppercase;">
                            <td style="text-align: left;">
                                <b>[[.supplier_name.]]:</b> <label id="supplier_name">[[|pc_supplier_name|]]</label><br />
                                <b>[[.supplier_address.]]:</b> <label id="supplier_address">[[|pc_supplier_address|]]</label><br />
                                <b>[[.supplier_phone.]]:</b> <label id="supplier_phone">[[|pc_supplier_mobile|]]</label><br />
                                <b>[[.supplier_tax_code.]]:</b> <label id="supplier_tax_code">[[|pc_supplier_tax_code|]]</label>
                            </td>
                            <td style="text-align: right; vertical-align: top;"><b>[[.total_order.]]:</b> <label id="total_order">[[|total|]]</label><br /><b>[[.product.]]:</b> <label id="quantity_product">[[|quantity|]]</label></td>
                        </tr>
                        <tr>
                            <td>
                                <?php if([[=status=]]>=3 AND User::can_edit('1975','1041400000000000000')){ ?>
                                    <label class="lbl_style">[[.number_contract.]]</label> : 
                                    <input id="number_contract" name="number_contract" type="text" class="ipt_style" style="width:200px" value="[[|number_contract|]]" />
                                <?php } ?>
                            </td>
                            <td colspan="2">
                                <?php if([[=status=]]>=3 AND User::can_edit('1975','1041400000000000000')){ ?>
                                    <?php if([[=file_contract=]]==''){ ?>
                                    <label class="lbl_style">[[.file_contract.]]</label> : 
                                    Choose a file to upload: <input type="file" name="file" id="file" /><br />
                                    <span  style="color: red;">([[.only_choose_doc_or_docx_or_pdf_file_type.]])</span>
                                    <?php }else{ ?>
                                    <label class="lbl_style">[[.download_file_contract.]]</label> 
                                    <a href="[[|file_contract|]]" style="padding: 5px; border: 1px solid #DDDDDD; background: #00B2F9; color: #FFFFFF; margin: 5px;">[[.download.]]</a>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <label class="lbl_style">[[.list_product_recommend.]]</label><br />
                    <table id="list_product" style="width: 100%; margin: 0px auto;" cellspacing="5" cellpadding="10" border="1" bordercolor="#DDDDDD">
                        <tr style="background: #DDDDDD; text-align: center; text-transform: uppercase;">
                            <th>[[.stt.]]</th>
                            <th>[[.product_code.]]</th>
                            <th>[[.product_name.]]</th>
                            <th>[[.unit_name.]]</th>
                            <th>[[.department_recommend.]]</th>
                            <th style="display: none;">[[.quantity_debit_warehouse.]]</th>
                            <th>[[.quantity_recommend.]]</th>
                            <th style="display: none;">[[.quantity_remain_warehoouse.]]</th>
                            <th>[[.price.]]</th>
                            <th>[[.tax_percent.]]</th>
                            <th>[[.pc_tax_amount.]]</th>
                            <th>[[.total.]]</th>
                        </tr>
                        <!--LIST:mi_list_product-->
                        <tr id="mi_list_product_<?php echo $i; ?>" style="text-align: center; font-weight: normal;">
                            <th style="font-weight: normal;">
                                [[|mi_list_product.stt|]]
                                <input class="check_box_child" id="check_<?php echo $i; ?>" name="check_<?php echo $i; ?>" type="checkbox" onclick="get_total_amount();" checked="checked" style="display: none;" />
                                <input id="id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][id]" style="display: none;" value="[[|mi_list_product.id|]]" />
                            </th>
                            <th style="font-weight: normal;"><input id="product_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][product_id]" style="display: none;" value="[[|mi_list_product.product_id|]]" />[[|mi_list_product.product_id|]]</th>
                            <th style="font-weight: normal;"><input id="product_name_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][product_name]" style="display: none;" value="[[|mi_list_product.product_name|]]" />[[|mi_list_product.product_name|]]</th>
                            <th style="font-weight: normal;">[[|mi_list_product.unit_name|]]</th>
                            <th style="font-weight: normal;"><input id="portal_department_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][portal_department_id]" style="display: none;" value="[[|mi_list_product.portal_department_id|]]" />[[|mi_list_product.department_name|]]</th>
                            <th style="font-weight: normal; display: none;"><input id="wh_remain_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][wh_remain]" class="ipt_style" style="width: 50px; text-align: center;" value="[[|mi_list_product.wh_remain|]]" readonly="" /></th>
                            <th style="font-weight: normal;"><input id="quantity_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][quantity]" class="ipt_style" style="width: 50px; text-align: center;" value="[[|mi_list_product.quantity|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> onkeyup="jQuery('#quantity_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#quantity_<?php echo $i; ?>').val())));jQuery('#wh_total_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#quantity_<?php echo $i; ?>').val())+to_numeric(jQuery('#wh_remain_<?php echo $i; ?>').val())));get_total_amount();" /></th>
                            <th style="font-weight: normal; display: none;"><input id="wh_total_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][wh_total]" class="ipt_style" style="width: 50px; text-align: center;" value="<?php echo [[=mi_list_product.quantity=]]+[[=mi_list_product.wh_remain=]]; ?>" readonly="" /></th>
                            <th style="font-weight: normal;"><input id="price_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][price]" class="ipt_style" style="width: 80px; text-align: right;" value="[[|mi_list_product.price|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> onkeyup="jQuery('#price_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#price_<?php echo $i; ?>').val())));get_total_amount();" /></th>

                            <th style="font-weight: normal;"><input id="tax_percent_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][tax_percent]" class="ipt_style" style="width: 80px; text-align: right;" value="[[|mi_list_product.tax_percent|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> onkeyup="get_total_amount();" /></th>

                            <th style="font-weight: normal;"><input id="tax_amount_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][tax_amount]" class="readonly" style="width: 80px; text-align: right;" value="[[|mi_list_product.tax_amount|]]"
                                readonly="readonly" 
                                  onkeyup="jQuery('#tax_amount_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#tax_amount_<?php echo $i; ?>').val())));get_total_amount();" /></th>

                            <th style="font-weight: normal;"><input id="total_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][total]" class="readonly" style="width: 80px; text-align: right;" value="[[|mi_list_product.total|]]" readonly="" /></th>
                        </tr>
                        <?php $i++; ?>
                        <!--/LIST:mi_list_product-->
                    </table>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
</form>
<script>
    jQuery("#create_time").datepicker();
    <?php if(!isset([[=no_data=]])){ ?>
    var input_count = to_numeric('<?php echo $i-1; ?>');
    var list_supplier_js = [[|list_supplier_js|]];
    var list_sup_price_js = [[|list_sup_price_js|]];
    console.log(list_sup_price_js);
    <?php } ?>
    
    function get_total_amount()
    {
        var total_amount = 0;
        var quantity_prd = 0;
        var arr_product = new Array();
        var tax_percent =0;
        var tax_amount =0;
        var price =0;
        var quantity = 0;
        for(var i=101; i<=input_count; i++)
        {
            tax_percent = to_numeric(jQuery("#tax_percent_"+i).val());
            price = to_numeric(jQuery("#price_"+i).val());
            quantity = to_numeric(jQuery("#quantity_"+i).val());
            tax_amount = price*tax_percent*0.01*quantity;
            
            total = price*quantity;//tong tien truoc thue 
            total +=tax_amount;
            jQuery("#total_"+i).val(number_format(total));
            jQuery("#tax_amount_"+i).val(number_format(tax_amount));

            if(document.getElementById('check_'+i).checked==true)
            {
                total_amount += total;
                var product_id = jQuery("#product_id_" + i).val();
                
                if(arr_product.indexOf(product_id)<0)
                {
                    arr_product.push(product_id);
                }
                
                //quantity_prd +=1;
            }
        }
        quantity_prd = arr_product.length;
        jQuery("#quantity_product").html(number_format(quantity_prd));
        jQuery("#total_order").html(number_format(total_amount));
    }
    
    function fun_check_all(id)
    {
        if(document.getElementById("check_all").checked==true)
        {
            jQuery(".check_box_child").attr('checked','checked');
        }
        else
        {
            jQuery(".check_box_child").removeAttr('checked');
        }
        get_total_amount();
    }
    
    function fun_select_sup()
    {
        if(jQuery("#pc_supplier_id").val()=='')
        {
            jQuery("#supplier_name").html('');
        }
        else
        {
            jQuery("#supplier_name").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['name']);
            jQuery("#supplier_address").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['address']);
            jQuery("#supplier_phone").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['mobile']);
            jQuery("#supplier_tax_code").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['tax_code']);
            if(jQuery("#pc_supplier_id").val()!=jQuery("#supplier_id").val())
            {
                for(var i=101; i<=input_count; i++)
                {
                    if(list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+jQuery("#pc_supplier_id").val()] != undefined)
                        price = list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+jQuery("#pc_supplier_id").val()]['price'];
                    else
                        price = 0;
                    jQuery("#price_"+i).val(number_format(to_numeric(price)));
                    total = to_numeric(price) * to_numeric(jQuery("#quantity_"+i).val());
                    jQuery("#total_"+i).val(number_format(total));
                }
                get_total_amount();
            }
            else
            {
                //duyet cac san pham cua nha cung cap 
                var supplier_id = jQuery("#supplier_id").val();
                var list_product = <?php echo String::array2js([[=mi_list_product=]]);?>;
                
                for(var i=101; i<=input_count; i++)
                {
                    for(var k in list_product)
                    {
                        if(jQuery("#product_id_"+i).val()==list_product[k]['product_id'])
                        {
                            //Thuc hien gan du lieu 
                            jQuery("#wh_remain_"+i).val(list_product[k]['wh_remain']);
                            jQuery("#quantity_"+i).val(list_product[k]['quantity']);
                            
                            jQuery("#wh_total_"+i).val(list_product[k]['quantity'] + list_product[k]['wh_remain']);
                            jQuery("#price_"+i).val(list_product[k]['price']);
                            jQuery("#tax_percent_"+i).val(list_product[k]['tax_percent']);
                            jQuery("#tax_amount_"+i).val(list_product[k]['tax_amount']);
                            jQuery("#total_"+i).val(list_product[k]['total']);
                            break;
                        }
                    }
                }
                get_total_amount();
            }
        }
    }
    
    function fun_checksubmit(key)
    {
        var check_select = false;
        for(var i=101; i<=input_count; i++)
        {
            if(document.getElementById('check_'+i).checked==true)
                check_select = true;
        }
        if(jQuery("#pc_supplier_id").val()=='')
        {
            alert('[[.are_you_dont_select_supplier.]]');
            return false;
        }
        else
        {
            if(check_select==false)
            {
                alert('[[.are_you_dont_select_product.]]');
                return false;
            }
            else
            {
                for(var i=101; i<=input_count; i++)
                {
                    if(document.getElementById('check_'+i).checked==false)
                        jQuery("#mi_list_product_"+i).remove();
                }
                jQuery("#act").val(key);
                EditPcOrderForm.submit();
            } 
        }
    }
</script>