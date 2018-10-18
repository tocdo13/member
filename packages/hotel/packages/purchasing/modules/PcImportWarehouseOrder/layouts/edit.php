<style>
    .lbl_style {
        line-height: 20px;
        padding: 5px;
        background: #beecff;
        color: #171717;
        font-weight: bold;
        width: 300px;
    }
    .ipt_style {
        height: 25px;
        border: 1px solid #DDDDDD;
        padding: 0px 5px;
    }
</style>
<form name="EditPcImportWarehouseOrderForm" method="POST">
    <table width="100%" cellspacing="" cellpadding="5" style="border: 1px solid #EEEEEE; margin: 10px auto;">
        <tr style="border-bottom: 1px solid #CCCCCC;">
            <td colspan="2">
                <img src="packages/hotel/packages/purchasing/skins/default/images/purchases_proposed/iconarchive.png" style="width: 50px; height: auto; float: left;" />
                <h3 style="float: left; text-transform: uppercase; font-size: 21px;">
                <?php
                if(Url::get('action')=='handover') 
                {
                    ?>
                    [[.handover.]]
                    <?php 
                }
                else
                {
                    ?>
                    [[.import_wh.]]
                    <?php 
                }
                ?>
                </h3>
                
            </td>
            
            <td colspan="4" style="float:right; padding-right: 30px;"><input class="w3-btn w3-orange w3-text-white" name="save" type="submit" id="save" value="[[.save.]]" style="text-transform: uppercase; margin-right: 5px;" />
            <?php
            if(Url::get('cmd')=='add')
            {
                ?>
                <input class="w3-btn w3-indigo" name="close" type="submit" id="close" value="[[.close.]]" onclick="if(confirm('[[.are_you_close_purchasing.]]!')){ return true;}else{ return false;}" style="text-transform: uppercase;" />
                <?php 
            } 
            ?>
                
                </td>
        </tr>
        <tr>
            <td>
                <table style="width: 1100px;" cellpadding="5" cellspacing="2">
                    <tr style="border-bottom: 1px solid #EEEEEE;">
                        <td style="width: 120px;">
                            <label style="font-weight: bold;">[[.bill_number.]]:</label>
                        </td>
                        <td style="width: 150px;">
                            <input value="[[|bill_number|]]" name="bill_number" type="text" class="ipt_style" style="width: 100%;" readonly="" />
                        </td>
                        <td style="width: 80px;">
                            <label style="font-weight: bold;">[[.date.]]:</label>
                        </td>
                        <td style="width: 150px;">
                            <input value="[[|create_date|]]" name="create_date" type="text" id="create_date" class="ipt_style" style="width: 100%;" />
                        </td>
                        <td style="width: 100px;">
                            <label style="font-weight: bold;">[[.voucher_number.]]:</label> : 
                        </td>
                        <td style="width: 150px;">
                            <input value="[[|invoice_number|]]" name="invoice_number" type="text" id="invoice_number" class="ipt_style" style="width: 100%; background: #EEEEEE;" readonly="readonly" />
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #EEEEEE;">
                        <td style="width: 120px;">
                            <label style="<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?> font-weight: bold;">[[.warehouse.]]:</label>
                        </td>
                        <?php if(Url::get('warehouse_ids')){ ?>
                        <td style="width: 150px;">
                            <input value="[[|warehouse_name22|]]" name="warehouse_name" type="text" id="warehouse_name" class="ipt_style" style="width: 100%;<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>" readonly="" />
                            <input value="[[|warehouse_id11|]]" name="warehouse_id" type="text" id="warehouse_id" class="ipt_style" style="width: 100%; display: none;" />
                            <input value="[[|warehouse_code33|]]" name="warehouse_code" type="text" id="warehouse_code" class="ipt_style" style="width: 100%; display: none;" />
                        </td>
                        <?php }else {?>
                            <td style="width: 150px;">
                            <input value="[[|warehouse_name|]]" name="warehouse_name" type="text" id="warehouse_name" class="ipt_style" style="width: 100%;<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>" readonly="" />
                            <input value="[[|warehouse_id|]]" name="warehouse_id" type="text" id="warehouse_id" class="ipt_style" style="width: 100%; display: none;" />
                            <input value="[[|warehouse_code|]]" name="warehouse_code" type="text" id="warehouse_code" class="ipt_style" style="width: 100%; display: none;" />
                        </td>
                        <?php  } ?>
                        <td style="width: 80px;">
                            <label style="font-weight: bold;">[[.receiver_name.]]:</label>
                            
                        </td>
                        <td style="width: 150px;">
                            <input value="[[|receiver_name|]]" name="receiver_name" type="text" id="receiver_name" class="ipt_style" style="width: 100%;" readonly="" />
                        </td>
                        <td style="width: 100px;">
                            <label style="font-weight: bold;">[[.deliver_name.]]:</label>
                            
                        </td>
                        <td style="width: 150px;">
                            <input value="[[|deliver_name|]]" name="deliver_name" type="text" id="deliver_name" class="ipt_style" style="width: 100%;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">
                            <label style="font-weight: bold;">[[.supplier_name.]]:</label>
                        </td>
                        <td colspan="2" style="width: 230px;">
                            <input value="[[|supplier_name|]]" name="supplier_name" type="text" id="supplier_name" class="ipt_style" style="width: 100%; border: none;" readonly="" />
                            <input value="[[|supplier_id|]]" name="supplier_id" type="text" id="supplier_id" class="ipt_style" style="width: 150px; display: none;" />
                        </td>
                        <td style="width: 100px; text-align: right;">
                            <label class="lbl_style">[[.description.]]:</label>
                        </td>                
                        <td colspan="2">
                            <textarea id="description" name="description" class="ipt_style" style="width: 100%; padding: 0px;">[[|description|]]</textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td colspan="6">
                <fieldset style="border: 2px solid #beecff;">
                    <legend class="lbl_style">[[.list_product.]]</legend>
                    <?php $input_count = 101; ?>
                    <table border="1" bordercolor="#EEEEEE" cellspacing="2" cellpadding="2">
                        <tr class="w3-light-gray">
                            <th>[[.product_code.]]</th>
                            <th>[[.product_name.]]</th>
                            <th>[[.unit_name.]]</th>
                            <th>[[.category.]]</th>
                            <th>[[.type.]]</th>
                            <th style="display: none;"><span style="<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>">[[.wh_remain.]]</span></th>
                            <th>[[.quantity.]]</th>
                            
                            <?php 
                            if(Url::get('cmd')=='add')
                            {
                                ?>
                                <th>[[.quantity_import.]]</th>
                                <th>[[.quantity_remain.]]</th>
                                <?php 
                            }
                            ?>
                            <th>[[.price.]]</th>
                            <th>[[.tax_percent.]]</th>
                            <th>[[.tax_amount.]]</th>
                            <th>[[.total.]]</th>
                        </tr>
                        <!--LIST:items-->
                        <tr>
                            <td style="width: 150px;">
                                <input name="product[<?php echo $input_count; ?>][id]" type="text" value="[[|items.id|]]" style="display: none;" />
                                <input name="product[<?php echo $input_count; ?>][pc_order_detail_id]" type="text" value="[[|items.pc_order_detail_id|]]" style="display: none;" />
                                <input name="product[<?php echo $input_count; ?>][product_id]" type="text" value="[[|items.product_id|]]" style="display: none;" />
                                [[|items.product_id|]]
                            </td>
                            <td style="width: 250px;">[[|items.product_name|]]</td>
                            <td style="width: 80px;">[[|items.unit_name|]]</td>
                            <td style="width: 100px;">[[|items.product_category_name|]]</td>
                            <td style="width: 100px;">[[|items.product_type|]]</td>
                            <td style="display: none;"><input name="product[<?php echo $input_count; ?>][wh_remain]" type="hidden" value="[[|items.wh_remain|]]" id="wh_remain_<?php echo $input_count; ?>" style="width: 100%; text-align: center;<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>" readonly="" />[[|items.wh_remain|]]</td>
                            <td style="width: 80px; text-align: right;"><input name="product[<?php echo $input_count; ?>][quantity]" type="hidden" value="[[|items.quantity|]]" id="quantity_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" />[[|items.quantity|]]</td>
                            <?php 
                            if(Url::get('cmd')=='add')
                            {
                                ?>
                                <td style="width: 120px; text-align: right;"><input name="product[<?php echo $input_count; ?>][quantity_import]" type="hidden" value="[[|items.quantity_import|]]" id="quantity_import_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" />[[|items.quantity_import|]]</td>

                            <td style="width: 100px; text-align: right;">
                                <input name="product[<?php echo $input_count; ?>][quantity_remain]" type="text" value="[[|items.quantity_remain|]]" id="quantity_remain_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" onkeyup="if(to_numeric(jQuery('#quantity_remain_<?php echo $input_count; ?>').val())>to_numeric(jQuery('#quantity_remain_old_<?php echo $input_count; ?>').val())){ alert('khong duoc nhap so luong lon hon so luong da mua!'); jQuery('#quantity_remain_<?php echo $input_count; ?>').val(jQuery('#quantity_remain_old_<?php echo $input_count; ?>').val()); }else{ CountChangeTotal(<?php echo $input_count; ?>); }" />
                                <input type="hidden" value="[[|items.quantity_remain|]]" id="quantity_remain_old_<?php echo $input_count; ?>"/>
                            </td>
                                <?php 
                            }
                            ?>
                            <td style="width: 100px; text-align: right;"><input name="product[<?php echo $input_count; ?>][price]" type="<?php if(Url::get('cmd')=='add'){echo 'text';}else{echo 'hidden';} ?>" value="<?php echo System::display_number([[=items.price=]]); ?>" id="price_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" class="readonly" readonly="readonly"/><?php if(Url::get('cmd')=='edit'){echo System::display_number([[=items.price=]]);}else{echo '';} ?></td>
                            <td style="width: 60px; text-align: right;"><input name="product[<?php echo $input_count; ?>][tax_percent]" type="hidden" value="<?php echo [[=items.tax_percent=]]; ?>" id="tax_percent_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" readonly="" />
                            <?php echo [[=items.tax_percent=]]; ?></td>
                            <td style="width: 100px;"><input name="product[<?php echo $input_count; ?>][tax_amount]" type="text" value="<?php  echo System::display_number([[=items.tax_amount=]]);  ?>" id="tax_amount_<?php echo $input_count; ?>" oninput="jQuery('#tax_amount_<?php echo $input_count; ?>').ForceNumericOnly().FormatNumber();" onchange="CountChangeTotal(<?php echo $input_count; ?>);" style="width: 100%; text-align: right;" />
                           </td>

                            <td style="width: 120px;"><input name="product[<?php echo $input_count; ?>][total]" type="text" value="<?php echo System::display_number(round([[=items.total=]])); ?>" id="total_<?php echo $input_count; ?>" oninput="jQuery('#total_<?php echo $input_count; ?>').ForceNumericOnly().FormatNumber();" onkeyup="CountChangeTotal(<?php echo $input_count; ?>)" style="width: 100%; text-align: right;" /></td>
                        </tr>
                        <?php $input_count++; ?>
                        <!--/LIST:items-->
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right;">
                <label class="lbl_style">[[.total_amount.]]</label>
                <input name="total_amount" value="<?php echo System::display_number([[=total_amount=]]); ?>" id="total_amount" type="text" style="text-align: right; width: 120px; padding-right: 20px;border-style: none;font-weight: bold;" />
            </td>
        </tr>
    </table>
</form>
<script>
    jQuery("#create_date").datepicker();
    var input_count = to_numeric(<?php echo $input_count; ?>);
    function get_total()
    {
        var total_amount = 0;
        for(var i=101; i<input_count; i++)
        {
            var quantity_remain = jQuery("#quantity_remain_"+i).val();
            //jQuery("#quantity_remain"+i).val(to_numeric(jQuery("#quantity_"+i).val()));
            /*var price =  to_numeric(jQuery("#price_"+i).val());
            var tax_percent =  jQuery("#tax_percent_"+i).val();
            var tax_amount = price*tax_percent*0.01;
            tax_amount = tax_amount*quantity_remain;
            total = quantity_remain* price;
            total +=tax_amount;
            jQuery("#total_"+i).val(number_format(Math.round(total)));
            jQuery("#tax_amount_"+i).val(number_format(Math.round(tax_amount)));
            total_amount += total;*/
            total_amount += to_numeric(jQuery('#total_'+i).val());
        }
        jQuery("#total_amount").val(number_format(Math.round(total_amount)));
    }
    
    function CountChangeTotal(xxxx)
    {
        var total = to_numeric(jQuery("#total_"+xxxx).val());
        var quantity = to_numeric(jQuery("#quantity_remain_"+xxxx).val());
        var tax = to_numeric(jQuery('#tax_percent_'+xxxx).val()); 
        var tax_amount = to_numeric(jQuery('#tax_amount_'+xxxx).val()); 
        if(tax != 0)
        {
            if(tax_amount == 0)
            {
                jQuery('#tax_percent_'+xxxx).val(0); 
            }            
        }
        if(quantity != 0)
        {
            price_before_tax = (total-tax_amount)/quantity;
        }else
        {
            price_before_tax = 0;
                    
        }
        jQuery('#price_'+xxxx).val(number_format(price_before_tax));
        get_total();  
    }
    
    function change_price(obj,price)
    {
        console.log(price);
        price_new = jQuery('#price_'+obj).val();
        if(to_numeric(price_new) > to_numeric(price))
        {
            jQuery('#price_'+obj).val(price);
            get_total(); 
            alert('Không được nhập giá lơn hơn giá đề xuất');
            return false;
        }
        get_total();
    }
</script>