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
                    <?php echo Portal::language('handover');?>
                    <?php 
                }
                else
                {
                    ?>
                    <?php echo Portal::language('import_wh');?>
                    <?php 
                }
                ?>
                </h3>
                
            </td>
            
            <td colspan="4" style="float:right; padding-right: 30px;"><input class="w3-btn w3-orange w3-text-white" name="save" type="submit" id="save" value="<?php echo Portal::language('save');?>" style="text-transform: uppercase; margin-right: 5px;" />
            <?php
            if(Url::get('cmd')=='add')
            {
                ?>
                <input class="w3-btn w3-indigo" name="close" type="submit" id="close" value="<?php echo Portal::language('close');?>" onclick="if(confirm('<?php echo Portal::language('are_you_close_purchasing');?>!')){ return true;}else{ return false;}" style="text-transform: uppercase;" />
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
                            <label style="font-weight: bold;"><?php echo Portal::language('bill_number');?>:</label>
                        </td>
                        <td style="width: 150px;">
                            <input value="<?php echo $this->map['bill_number'];?>" name="bill_number" type="text" class="ipt_style" style="width: 100%;" readonly="" />
                        </td>
                        <td style="width: 80px;">
                            <label style="font-weight: bold;"><?php echo Portal::language('date');?>:</label>
                        </td>
                        <td style="width: 150px;">
                            <input value="<?php echo $this->map['create_date'];?>" name="create_date" type="text" id="create_date" class="ipt_style" style="width: 100%;" />
                        </td>
                        <td style="width: 100px;">
                            <label style="font-weight: bold;"><?php echo Portal::language('voucher_number');?>:</label> : 
                        </td>
                        <td style="width: 150px;">
                            <input value="<?php echo $this->map['invoice_number'];?>" name="invoice_number" type="text" id="invoice_number" class="ipt_style" style="width: 100%; background: #EEEEEE;" readonly="readonly" />
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #EEEEEE;">
                        <td style="width: 120px;">
                            <label style="<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?> font-weight: bold;"><?php echo Portal::language('warehouse');?>:</label>
                        </td>
                        <?php if(Url::get('warehouse_ids')){ ?>
                        <td style="width: 150px;">
                            <input value="<?php echo $this->map['warehouse_name22'];?>" name="warehouse_name" type="text" id="warehouse_name" class="ipt_style" style="width: 100%;<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>" readonly="" />
                            <input value="<?php echo $this->map['warehouse_id11'];?>" name="warehouse_id" type="text" id="warehouse_id" class="ipt_style" style="width: 100%; display: none;" />
                            <input value="<?php echo $this->map['warehouse_code33'];?>" name="warehouse_code" type="text" id="warehouse_code" class="ipt_style" style="width: 100%; display: none;" />
                        </td>
                        <?php }else {?>
                            <td style="width: 150px;">
                            <input value="<?php echo $this->map['warehouse_name'];?>" name="warehouse_name" type="text" id="warehouse_name" class="ipt_style" style="width: 100%;<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>" readonly="" />
                            <input value="<?php echo $this->map['warehouse_id'];?>" name="warehouse_id" type="text" id="warehouse_id" class="ipt_style" style="width: 100%; display: none;" />
                            <input value="<?php echo $this->map['warehouse_code'];?>" name="warehouse_code" type="text" id="warehouse_code" class="ipt_style" style="width: 100%; display: none;" />
                        </td>
                        <?php  } ?>
                        <td style="width: 80px;">
                            <label style="font-weight: bold;"><?php echo Portal::language('receiver_name');?>:</label>
                            
                        </td>
                        <td style="width: 150px;">
                            <input value="<?php echo $this->map['receiver_name'];?>" name="receiver_name" type="text" id="receiver_name" class="ipt_style" style="width: 100%;" readonly="" />
                        </td>
                        <td style="width: 100px;">
                            <label style="font-weight: bold;"><?php echo Portal::language('deliver_name');?>:</label>
                            
                        </td>
                        <td style="width: 150px;">
                            <input value="<?php echo $this->map['deliver_name'];?>" name="deliver_name" type="text" id="deliver_name" class="ipt_style" style="width: 100%;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">
                            <label style="font-weight: bold;"><?php echo Portal::language('supplier_name');?>:</label>
                        </td>
                        <td colspan="2" style="width: 230px;">
                            <input value="<?php echo $this->map['supplier_name'];?>" name="supplier_name" type="text" id="supplier_name" class="ipt_style" style="width: 100%; border: none;" readonly="" />
                            <input value="<?php echo $this->map['supplier_id'];?>" name="supplier_id" type="text" id="supplier_id" class="ipt_style" style="width: 150px; display: none;" />
                        </td>
                        <td style="width: 100px; text-align: right;">
                            <label class="lbl_style"><?php echo Portal::language('description');?>:</label>
                        </td>                
                        <td colspan="2">
                            <textarea id="description" name="description" class="ipt_style" style="width: 100%; padding: 0px;"><?php echo $this->map['description'];?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td colspan="6">
                <fieldset style="border: 2px solid #beecff;">
                    <legend class="lbl_style"><?php echo Portal::language('list_product');?></legend>
                    <?php $input_count = 101; ?>
                    <table border="1" bordercolor="#EEEEEE" cellspacing="2" cellpadding="2">
                        <tr class="w3-light-gray">
                            <th><?php echo Portal::language('product_code');?></th>
                            <th><?php echo Portal::language('product_name');?></th>
                            <th><?php echo Portal::language('unit_name');?></th>
                            <th><?php echo Portal::language('category');?></th>
                            <th><?php echo Portal::language('type');?></th>
                            <th style="display: none;"><span style="<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>"><?php echo Portal::language('wh_remain');?></span></th>
                            <th><?php echo Portal::language('quantity');?></th>
                            
                            <?php 
                            if(Url::get('cmd')=='add')
                            {
                                ?>
                                <th><?php echo Portal::language('quantity_import');?></th>
                                <th><?php echo Portal::language('quantity_remain');?></th>
                                <?php 
                            }
                            ?>
                            <th><?php echo Portal::language('price');?></th>
                            <th><?php echo Portal::language('tax_percent');?></th>
                            <th><?php echo Portal::language('tax_amount');?></th>
                            <th><?php echo Portal::language('total');?></th>
                        </tr>
                        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                        <tr>
                            <td style="width: 150px;">
                                <input name="product[<?php echo $input_count; ?>][id]" type="text" value="<?php echo $this->map['items']['current']['id'];?>" style="display: none;" />
                                <input name="product[<?php echo $input_count; ?>][pc_order_detail_id]" type="text" value="<?php echo $this->map['items']['current']['pc_order_detail_id'];?>" style="display: none;" />
                                <input name="product[<?php echo $input_count; ?>][product_id]" type="text" value="<?php echo $this->map['items']['current']['product_id'];?>" style="display: none;" />
                                <?php echo $this->map['items']['current']['product_id'];?>
                            </td>
                            <td style="width: 250px;"><?php echo $this->map['items']['current']['product_name'];?></td>
                            <td style="width: 80px;"><?php echo $this->map['items']['current']['unit_name'];?></td>
                            <td style="width: 100px;"><?php echo $this->map['items']['current']['product_category_name'];?></td>
                            <td style="width: 100px;"><?php echo $this->map['items']['current']['product_type'];?></td>
                            <td style="display: none;"><input name="product[<?php echo $input_count; ?>][wh_remain]" type="hidden" value="<?php echo $this->map['items']['current']['wh_remain'];?>" id="wh_remain_<?php echo $input_count; ?>" style="width: 100%; text-align: center;<?php if(Url::get('action')=='handover'){ echo 'display: none;'; } ?>" readonly="" /><?php echo $this->map['items']['current']['wh_remain'];?></td>
                            <td style="width: 80px; text-align: right;"><input name="product[<?php echo $input_count; ?>][quantity]" type="hidden" value="<?php echo $this->map['items']['current']['quantity'];?>" id="quantity_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" /><?php echo $this->map['items']['current']['quantity'];?></td>
                            <?php 
                            if(Url::get('cmd')=='add')
                            {
                                ?>
                                <td style="width: 120px; text-align: right;"><input name="product[<?php echo $input_count; ?>][quantity_import]" type="hidden" value="<?php echo $this->map['items']['current']['quantity_import'];?>" id="quantity_import_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" /><?php echo $this->map['items']['current']['quantity_import'];?></td>

                            <td style="width: 100px; text-align: right;">
                                <input name="product[<?php echo $input_count; ?>][quantity_remain]" type="text" value="<?php echo $this->map['items']['current']['quantity_remain'];?>" id="quantity_remain_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" onkeyup="if(to_numeric(jQuery('#quantity_remain_<?php echo $input_count; ?>').val())>to_numeric(jQuery('#quantity_remain_old_<?php echo $input_count; ?>').val())){ alert('khong duoc nhap so luong lon hon so luong da mua!'); jQuery('#quantity_remain_<?php echo $input_count; ?>').val(jQuery('#quantity_remain_old_<?php echo $input_count; ?>').val()); }else{ CountChangeTotal(<?php echo $input_count; ?>); }" />
                                <input type="hidden" value="<?php echo $this->map['items']['current']['quantity_remain'];?>" id="quantity_remain_old_<?php echo $input_count; ?>"/>
                            </td>
                                <?php 
                            }
                            ?>
                            <td style="width: 100px; text-align: right;"><input name="product[<?php echo $input_count; ?>][price]" type="<?php if(Url::get('cmd')=='add'){echo 'text';}else{echo 'hidden';} ?>" value="<?php echo System::display_number($this->map['items']['current']['price']); ?>" id="price_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" class="readonly" readonly="readonly"/><?php if(Url::get('cmd')=='edit'){echo System::display_number($this->map['items']['current']['price']);}else{echo '';} ?></td>
                            <td style="width: 60px; text-align: right;"><input name="product[<?php echo $input_count; ?>][tax_percent]" type="hidden" value="<?php echo $this->map['items']['current']['tax_percent']; ?>" id="tax_percent_<?php echo $input_count; ?>" style="width: 100%; text-align: right;" readonly="" />
                            <?php echo $this->map['items']['current']['tax_percent']; ?></td>
                            <td style="width: 100px;"><input name="product[<?php echo $input_count; ?>][tax_amount]" type="text" value="<?php  echo System::display_number($this->map['items']['current']['tax_amount']);  ?>" id="tax_amount_<?php echo $input_count; ?>" oninput="jQuery('#tax_amount_<?php echo $input_count; ?>').ForceNumericOnly().FormatNumber();" onchange="CountChangeTotal(<?php echo $input_count; ?>);" style="width: 100%; text-align: right;" />
                           </td>

                            <td style="width: 120px;"><input name="product[<?php echo $input_count; ?>][total]" type="text" value="<?php echo System::display_number(round($this->map['items']['current']['total'])); ?>" id="total_<?php echo $input_count; ?>" oninput="jQuery('#total_<?php echo $input_count; ?>').ForceNumericOnly().FormatNumber();" onkeyup="CountChangeTotal(<?php echo $input_count; ?>)" style="width: 100%; text-align: right;" /></td>
                        </tr>
                        <?php $input_count++; ?>
                        <?php }}unset($this->map['items']['current']);} ?>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right;">
                <label class="lbl_style"><?php echo Portal::language('total_amount');?></label>
                <input name="total_amount" value="<?php echo System::display_number($this->map['total_amount']); ?>" id="total_amount" type="text" style="text-align: right; width: 120px; padding-right: 20px;border-style: none;font-weight: bold;" />
            </td>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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