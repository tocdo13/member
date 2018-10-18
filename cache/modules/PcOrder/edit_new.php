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
    <div class="w3-light-gray" style="width: 100%; height: auto; ">
        <table style="width: 100%;">
            <tr>
                <td>
                    <img src="packages/hotel/packages/purchasing/skins/default/images/purchases_proposed/iconarchive.png" style="width: 50px; height: auto; float: left;" />
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;"><?php echo Portal::language('edit_order');?></h3>
                </td>
                <td style="text-align: right;">
                    <input value="" name="act" id="act" type="text" style="display: none;" />
                    <input value="<?php echo $this->map['status'];?>" name="status" id="status" type="text" style="display: none;" />
                    <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                    <input class="w3-btn w3-orange" name="save" id="save" type="button" onclick="jQuery('#act').val('save');fun_checksubmit('creater');" value="<?php echo Portal::language('save');?>"  />
                    <?php } ?>
                    <?php if(User::can_edit(Portal::get_module_id('PrivilegeDepartmentChiefAccountant'),ANY_CATEGORY) AND $this->map['status']==1){ ?>
                    <input class="w3-btn w3-indigo" name="confirm" id="confirm" type="button" onclick="jQuery('#act').val('confirm');EditPcOrderForm.submit();" value="<?php echo Portal::language('confirm');?>"  />
                    <input name="person_confirm" type="hidden" id="person_confirm" value="<?php echo $this->map['person_confirm'];?>" />
                    <?php } ?>
                    <?php if(User::can_edit(Portal::get_module_id('PrivilegeDepartmentManager'),ANY_CATEGORY) AND $this->map['status']==2){ ?>
                    <input class="w3-btn w3-indigo" name="succefull" id="succefull" type="button" onclick="jQuery('#act').val('succefull');EditPcOrderForm.submit();" value="<?php echo Portal::language('confirm');?>"  />
                    <input name="person_confirm" type="hidden" id="person_confirm" value="<?php echo $this->map['person_confirm'];?>" />
                    <?php } ?>
                    <?php if(User::can_edit(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY) AND $this->map['status']==3){ ?>
                    <input class="w3-btn w3-lime" name="finish" id="finish" type="button" onclick="jQuery('#act').val('finish');EditPcOrderForm.submit();" value="<?php echo Portal::language('finish_order');?>"  />
                    <a class="w3-btn w3-green" href="?page=pc_order&cmd=print_order_payment&id=<?php echo Url::get('id'); ?>" style=" border: 1px solid #CCCCCC ; color: #000000; text-decoration: none;"><?php echo Portal::language('print_pc_order_payment');?></a>
                    <?php } ?>
                    <?php if(User::can_delete(false,ANY_CATEGORY) AND $this->map['status']!=0 AND User::can_edit(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY)){ ?>
                    <input class="w3-btn w3-gray" name="cancel" id="cancel" type="button" onclick="confirm_cancel();" value="<?php echo Portal::language('cancel');?>"  />
		              <input type="hidden" name="note_cancel" id="note_cancel" />
                    <?php } ?>
                    <?php if(User::can_view(false,ANY_CATEGORY) AND Url::get('id')!='' AND $this->map['status']!=4){ ?>
                    <a class="w3-btn w3-blue" href="?page=pc_order&cmd=print_order&id=<?php echo Url::get('id'); ?>" style=" border: 1px solid #CCCCCC ; background-color: #DDDDDD; color: #000000; text-decoration: none;"><?php echo Portal::language('print_pc_order');?></a>
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
                    <?php if(isset($this->map['no_data'])){ ?>
                        <div style="margin: 10px auto; width: 200px; height: 70px; line-height: 70px; text-align: center; border: 2px solid #00B2F9;">
                            <b><?php echo Portal::language('no_request');?></b>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <?php $i=101; ?>
        <?php if(!isset($this->map['no_data'])){ ?>
        <table style="width: 100%; margin: 10px auto;" cellspacing="5" cellpadding="2">
            <tr style="border-top: 1px solid lightgray;">
                <td style="width: 150px;">
                    <label ><?php echo Portal::language('create_time');?></label> : 
                    <input id="create_time" name="create_time" type="text" class="ipt_style" style="width: 80px;" value="<?php echo $this->map['create_time'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?>  />
                </td>
                <td style="width: 200px;">
                    <label ><?php echo Portal::language('order_code');?></label> : 
                    <input id="order_code" name="order_code" type="text" class="ipt_style" style="width: 100px; font-size: 10px; text-align: center; background: #DDDDDD;" readonly="" value="<?php echo $this->map['code'];?>" />
                </td>
                <td style="width: 250px;">
                    <label ><?php echo Portal::language('order_name');?></label> : 
                    <input id="order_name" name="order_name" type="text" class="ipt_style" style="width: 150px;" value="<?php echo $this->map['name'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?> />
                    
                </td>
                <td style="width: 80px;"><?php echo Portal::language('description');?></td>
                <td style="width: 350px;">
                    <textarea id="description" name="description" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?> style="width: 350px; height: 24px; border: 1px solid #DDDDDD;"><?php echo $this->map['description'];?></textarea>
                </td>
                <td style="width: 200px;">
                    <?php
                    if($this->map['status']==3)
                    {
                        ?>
                        <label ><?php echo Portal::language('payment_type');?></label> : 
                        <select  name="payment_type_id" id="payment_type_id" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['payment_type_id_list']))
					{
						foreach($this->map['payment_type_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_type_id',isset($this->map['payment_type_id'])?$this->map['payment_type_id']:''))
                    echo "<script>$('payment_type_id').value = \"".addslashes(URL::get('payment_type_id',isset($this->map['payment_type_id'])?$this->map['payment_type_id']:''))."\";</script>";
                    ?>
	<?php echo $this->map['option_payment_types'];?> </select>
                        <?php 
                    } 
                    ?>
                </td>
            </tr>
           <tr>
                <td>&nbsp;</td>
           </tr>
            <tr style="border-bottom: 1px solid lightgray;">
                <td colspan="6">
                    <table style="width: 100%;">
                        <tr style="text-transform: uppercase;">
                            <td style="text-align: left; width: 500px;">
                                <b><?php echo Portal::language('supplier_name');?>:</b> <label id="supplier_name"><?php echo $this->map['pc_supplier_name'];?></label><br />
                                <b><?php echo Portal::language('supplier_address');?>:</b> <label id="supplier_address"><?php echo $this->map['pc_supplier_address'];?></label>
                                
                            </td>
                            <td style="width: 250px;">
                                <b><?php echo Portal::language('supplier_tax_code');?>:</b> <label id="supplier_tax_code"><?php echo $this->map['pc_supplier_tax_code'];?></label><br />
                                <b><?php echo Portal::language('supplier_phone');?>:</b> <label id="supplier_phone"><?php echo $this->map['pc_supplier_mobile'];?></label>
                                
                            </td>
                            <td style="text-align: left;">
                                <b><?php echo Portal::language('total_order');?>:</b> <label id="total_order"><?php echo $this->map['total'];?></label><br />
                                <b><?php echo Portal::language('product');?>:</b> <label id="quantity_product"><?php echo $this->map['quantity'];?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php if($this->map['status']>=3 AND User::can_edit(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY)){ ?>
                                    <label class="lbl_style"><?php echo Portal::language('number_contract');?></label> : 
                                    <input id="number_contract" name="number_contract" type="text" class="ipt_style" style="width:200px" value="<?php echo $this->map['number_contract'];?>" />
                                <?php } ?>
                            </td>
                            <td colspan="2">
                                <?php if($this->map['status']>=3 AND User::can_edit(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY)){ ?>
                                    <?php if($this->map['file_contract']==''){ ?>
                                    <label class="lbl_style"><?php echo Portal::language('file_contract');?></label> : 
                                    Choose a file to upload: <input type="file" name="file" id="file" onchange="check_file(this);" /><br />
                                    <span  style="color: red;">(<?php echo Portal::language('only_choose_doc_or_docx_or_pdf_file_type');?>)</span>
                                    <?php }else{ ?>
                                    <label class="lbl_style"><?php echo Portal::language('download_file_contract');?></label> 
                                    <a href="<?php echo $this->map['file_contract'];?>" style="padding: 5px; border: 1px solid #DDDDDD; background: #00B2F9; color: #FFFFFF; margin: 5px;"><?php echo Portal::language('download');?></a>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <label class="lbl_style"><?php echo Portal::language('list_product_recommend');?></label><br />
                    <table id="list_product" style="width: 100%; margin: 0px auto;" cellspacing="2" cellpadding="2" border="1" bordercolor="#DDDDDD">
                        <tr style="background: #DDDDDD; text-align: center;">
                            <th style="width: 20px;"><?php echo Portal::language('stt');?></th>
                            <th style="width: 80px;"><?php echo Portal::language('product_code');?></th>
                            <th style="width: 200px;"><?php echo Portal::language('product_name');?></th>
                            <th style="width: 70px;"><?php echo Portal::language('unit_name');?></th>
                            <th style="width: 100px;"><?php echo Portal::language('department_recommend');?></th>
                            <th style="display: none;"><?php echo Portal::language('quantity_debit_warehouse');?></th>
                            <th style="width: 60px;"><?php echo Portal::language('quantity_recommend');?></th>
                            <th style="display: none;"><?php echo Portal::language('quantity_remain_warehoouse');?></th>
                            <th style="width: 80px;"><?php echo Portal::language('price');?></th>
                            <th style="width: 50px;"><?php echo Portal::language('tax_percent');?></th>
                            <th style="width: 80px;"><?php echo Portal::language('pc_tax_amount');?></th>
                            <th style="width: 100px;"><?php echo Portal::language('total');?></th>
                            <th style="width: 80px;"><?php echo Portal::language('delivery_date');?></th>
                            <th style="width: 200px;"><?php echo Portal::language('description');?></th>
                            <th style="width: 100px;"><?php echo Portal::language('note');?></th>
                            <th style="width: 40px;"><?php echo Portal::language('delete');?></th>
                        </tr>
                        <?php if(isset($this->map['mi_list_product']) and is_array($this->map['mi_list_product'])){ foreach($this->map['mi_list_product'] as $key1=>&$item1){if($key1!='current'){$this->map['mi_list_product']['current'] = &$item1;?>
                        <tr id="mi_list_product_<?php echo $i; ?>" style="text-align: center; font-weight: normal;">
                            <th style="font-weight: normal;width:20px;">
                                <?php echo $this->map['mi_list_product']['current']['stt'];?>
                                <input class="check_box_child" id="check_<?php echo $i; ?>" name="check_<?php echo $i; ?>" type="checkbox" onclick="get_total_amount();" checked="checked" style="display: none;" />
                                <input id="id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][id]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['id'];?>" />
                            </th>
                            <th style="font-weight: normal;text-align: left;width: 80px;"><input id="product_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][product_id]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['product_id'];?>" /><?php echo $this->map['mi_list_product']['current']['product_id'];?></th>
                            <th style="font-weight: normal;text-align: left;width: 200px;"><input id="product_name_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][product_name]" style="display: none; " value="<?php echo $this->map['mi_list_product']['current']['product_name'];?>" /><?php echo $this->map['mi_list_product']['current']['product_name'];?></th>
                            <th style="font-weight: normal;text-align: center;width: 70px;"><?php echo $this->map['mi_list_product']['current']['unit_name'];?></th>
                            <th style="font-weight: normal;width: 100px;"><input id="portal_department_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][portal_department_id]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['portal_department_id'];?>" /><?php echo $this->map['mi_list_product']['current']['department_name'];?></th>
                            <th style="font-weight: normal; display: none;"><input id="wh_remain_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][wh_remain]"   value="<?php echo $this->map['mi_list_product']['current']['wh_remain'];?>"/></th>
                            <th style="font-weight: normal;width: 60px !important;"><input id="quantity_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][quantity]" style="width: 100%; text-align: right;"  value="<?php echo $this->map['mi_list_product']['current']['quantity'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?> onkeyup="jQuery('#quantity_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#quantity_<?php echo $i; ?>').val())));jQuery('#wh_total_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#quantity_<?php echo $i; ?>').val())+to_numeric(jQuery('#wh_remain_<?php echo $i; ?>').val())));get_total_amount();CountPrice(<?php echo $i; ?>);" /></th>
                            <th style="font-weight: normal; display: none;"><input id="wh_total_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][wh_total]"  style="width: 100%; text-align: right;" value="<?php echo $this->map['mi_list_product']['current']['quantity']+$this->map['mi_list_product']['current']['wh_remain']; ?>" readonly="" /></th>
                            <th style="font-weight: normal;width: 80px;"><input id="price_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][price]" style="width: 100%; text-align: right;"  value="<?php echo $this->map['mi_list_product']['current']['price'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?>  readonly="" class="readonly"/></th>
                            <th style="font-weight: normal;width: 50px;"><input id="tax_percent_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][tax_percent]" style="width: 100%; text-align: right;"  value="<?php echo $this->map['mi_list_product']['current']['tax_percent'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?> /></th>
                            <th style="font-weight: normal;width: 80px;"><input id="tax_amount_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][tax_amount]" style="width: 100%; text-align: right;" value="<?php echo $this->map['mi_list_product']['current']['tax_amount'];?>" oninput="jQuery('#tax_amount_<?php echo $i; ?>').ForceNumericOnly().FormatNumber();" onchange="CountChangeTotal(<?php echo $i; ?>);" /></th>
                            <th style="font-weight: normal;width: 100px;"><input id="total_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][total]" style="width: 100%; text-align: right;" value="<?php echo $this->map['mi_list_product']['current']['total'];?>" oninput="jQuery('#total_<?php echo $i; ?>').ForceNumericOnly().FormatNumber();" onchange="CountChangeTotal(<?php echo $i; ?>);" /></th>
                            <th style="font-weight: normal;text-align: center;width: 80px;"><?php echo $this->map['mi_list_product']['current']['delivery_date'];?></th>
                            <th style="width: 200px;"><textarea id="description_product" name="description_product" style="width: 100%; height: 24px;"><?php echo $this->map['mi_list_product']['current']['description_product1'];?></textarea></th>
                            <th style="font-weight: normal;text-align:left;width: 100px;"><?php echo $this->map['mi_list_product']['current']['note'];?></th>
                            <th style="font-weight: normal;text-align:center;">
                            <?php if(User::can_delete(false,ANY_CATEGORY) AND User::can_delete(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY)){ ?>
                            <a class="delete-one-item" id="get_total_del"><img class="data_img" src="packages/hotel/packages/purchasing/skins/default/images/delete.png" style="height: auto; width: 20px; text-align: center;" onclick="check_delete(<?php echo $i; ?>)" /></a>
                            <?php } ?>
                            </th>
                        </tr>
                        <?php $i++; ?>
                        <?php }}unset($this->map['mi_list_product']['current']);} ?>
                    </table>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery('select[name=payment_type_id]').val('BANK');
    jQuery("#create_time").datepicker();
    <?php if(!isset($this->map['no_data'])){ ?>
    var input_count = to_numeric('<?php echo $i-1; ?>');
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
            price_after_tax = to_numeric(jQuery("#price_after_tax_"+i).val());
            total = to_numeric(jQuery("#total_"+i).val());//tong tien sau thue 
            //total += tax_amount;
            //jQuery("#total_"+i).val(number_format(Math.round(total)));
            //jQuery("#tax_amount_"+i).val(number_format(Math.round(tax_amount)));
            //jQuery("#price_after_tax_"+i).val(number_format(Math.round(price_after_tax)));
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
        jQuery("#quantity_product").html(number_format(Math.round(quantity_prd)));
        jQuery("#total_order").html(number_format(Math.round(total_amount)));
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
    }
    
    function fun_checksubmit(key)
    {
        var check_select = false;
        for(var i=101; i<=input_count; i++)
        {
            if(document.getElementById('check_'+i).checked==true)
                check_select = true;
            if(jQuery('#quantity_'+i).val() == 0)
            {
                alert("Số lượng không thể = 0");
                jQuery('#quantity_'+i).focus();
                jQuery('#quantity_'+i).css('background-color','yellow');
                return false;                        
            }
        }
        if(jQuery("#pc_supplier_id").val()=='')
        {
            alert('<?php echo Portal::language('are_you_dont_select_supplier');?>');
            return false;
        }
        else
        {
            if(check_select==false)
            {
                alert('<?php echo Portal::language('are_you_dont_select_product');?>');
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
    function confirm_cancel()
    {
        var note_cancel = prompt("Nhap ly do huy don hang!", "");
    
        if (note_cancel != null) {
            jQuery('#note_cancel').val(note_cancel);
            jQuery('#act').val('cancel');
            EditPcOrderForm.submit();
        }
        
    }
    /** Daund check file upload */
    function check_file(obj)
    {
        var ext = jQuery(obj).val().split('.').pop().toLowerCase();
        if(jQuery.inArray(ext, ['doc','docx','pdf']) == -1)
        {
            alert('File không đúng định dạng! Chỉ chấp nhận file có định dạng .doc, .docx, .pdf');
            jQuery(obj).val('');
        }
    }
    /** Daund check file upload */
    function check_delete(xxxx)
    {
        if(!confirm('<?php echo Portal::language('are_you_sure');?>'))
        {
 			return false;
  		}else
        {
            var block_id = <?php echo Module::block_id(); ?>;
            var total_del = to_numeric(jQuery('#total_'+xxxx).val());
            var p_id  = jQuery('#id_'+xxxx).val();
            var pc_order_id = <?php echo Url::get('id'); ?>;
            jQuery('#loading-layer').fadeIn(100);
            jQuery.ajax({
                type: "POST",
                url: "form.php?block_id="+block_id,
                data: {p_id: p_id, pc_order_id: pc_order_id, total_del: total_del , action : 'delete'},
                success: function(res)
                {
                    alert('<?php echo Portal::language('delete_success');?>');
                    jQuery('#loading-layer').fadeOut(100);
                    location.reload(true);            
                }
            });                        
        }        
    }
    
    function CountPrice(xxxx)
    {
        var quantity = to_numeric(jQuery("#quantity_"+xxxx).val());
        var price = to_numeric(jQuery('#price_'+xxxx).val());
        var tax = to_numeric(jQuery('#tax_percent_'+xxxx).val());
        if(price == '')
        {
            price = 0;
            jQuery('#price_'+xxxx).val(0);        
        }
        if(tax == '')
        {
            tax = 0;
            jQuery('#tax_percent_'+xxxx).val(0)
        }
        price_after_tax = to_numeric(price)*(1+tax/100);
        jQuery('#tax_amount_'+xxxx).val(number_format(to_numeric(Math.round(to_numeric(price)*(tax/100)*quantity))));  
        jQuery('#price_after_tax_'+xxxx).val(number_format(Math.round(price_after_tax)));
        jQuery('#total_'+xxxx).val(number_format(to_numeric(Math.round(price_after_tax*quantity))));
        get_total_amount();  
    }
    
    function CountChangeTotal(xxxx)
    {
        var total = to_numeric(jQuery("#total_"+xxxx).val());
        var quantity = to_numeric(jQuery("#quantity_"+xxxx).val());
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
            price_after_tax = total/quantity; 
            price_before_tax = (total-tax_amount)/quantity;
        }else
        {
            price_after_tax = 0; 
            price_before_tax = 0;
        }
        jQuery('#price_'+xxxx).val(number_format(price_before_tax));
        get_total_amount();  
    }
</script>