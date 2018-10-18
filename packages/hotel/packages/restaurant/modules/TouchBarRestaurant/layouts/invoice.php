<style>
    *{
        font-size: 13px;
        outline: none!important;
    }
    *{
      -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
         -khtml-user-select: none; /* Konqueror HTML */
           -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
                user-select: none; /* Non-prefixed version, currently
                                      supported by Chrome and Opera */
    }
    #banner_url{display:none;}
    #sign-in,#testRibbon{display: none;}
    #chang_language a:first-child{display: none;}
    #chang_language a:last-child{background: #FFF; color: red!important; padding: 5px;box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-decoration: none; font-weight: bold;}
    .simple-layout-content{border: 5px solid #FFF!important;box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); margin: 0px; padding: 0px;}
    .simple-layout-bound, html, .simple-layout-center, .simple-layout-banner, .simple-layout-content{background: rgba(232, 240, 254,0.5)!important; border: none!important; box-shadow: none!important;}
</style>

<form name="InvoiceBarForm" method="POST">
    <div class="" style="width: 1000px; padding: 5px; margin: 0px auto;">
        <table style="width: 98%; margin: 20px auto; border-collapse: collapse; background: #FFF;" cellpadding="5" cellspacing="5" border="1" bordercolor="#EEE">
            <tr>
                <th style="width: 50%; vertical-align: top;"></th>
                <th style="vertical-align: top;">
                    <div onclick="location.href='?page=touch_bar_restaurant&cmd=edit&id=<?php echo Url::get('id'); ?>&table_id=<?php echo Url::get('table_id'); ?>&bar_area_id=<?php echo Url::get('bar_area_id'); ?>&bar_id=<?php echo Url::get('bar_id'); ?>';" class="w3-button w3-white w3-hover-white w3-right w3-border" style="font-weight: normal; margin-right: 3px;">
                        <i class="fa fa-fw fa-save"></i> [[.back.]]
                    </div>
                    <div onclick="location.href='?page=touch_bar_restaurant&cmd=invoice&id=<?php echo Url::get('id'); ?>&table_id=<?php echo Url::get('table_id'); ?>&bar_area_id=<?php echo Url::get('bar_area_id'); ?>&bar_id=<?php echo Url::get('bar_id'); ?>';" class="w3-button w3-white w3-hover-white w3-right w3-border" style="font-weight: normal; color: #f27173!important; margin-right: 3px;">
                        <i class="fa fa-fw fa-plus"></i> [[.create_bill.]]
                    </div>
                    <div onclick="SaveInvoice();" class="w3-button w3-blue w3-hover-blue w3-right w3-border" style="font-weight: normal; margin-right: 3px;">
                        <i class="fa fa-fw fa-save"></i> [[.save.]]
                    </div>
                </th>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <table id="ProductItems" style="width: 98%; margin: 1% auto;" cellpadding="5" cellspacing="5" border="1" bordercolor="#EEE">
                        <tr style="height: 35px;">
                            <td class="w3-text-dark-gray">Description</td>
                            <td class="w3-text-dark-gray" style="width: 60px; text-align: center;">Quantity</td>
                            <td class="w3-text-dark-gray" style="text-align: right; width: 100px;">Total</td>
                            <td onclick="MoveItems('ALL');" style="width: 42px; cursor: pointer;" title="Move All"><i class="fa fa-fw fa-2x fa-arrow-circle-o-right w3-text-dark-gray"></i></td>
                        </tr>
                        <!--LIST:items-->
                        <tr class="ProductItems" id="ProductItems_[[|items.id|]]" lang="[[|items.id|]]" style="height: 35px; font-weight: bold;">
                            <td>
                                <span id="lbl_name_[[|items.id|]]">[[|items.name|]]</span>
                                <input value="[[|items.name|]]" type="hidden" id="product_name_[[|items.id|]]" />
                                <input value="[[|items.invoice_id|]]" type="hidden" id="product_invoice_id_[[|items.id|]]" />
                                <input value="[[|items.type|]]" type="hidden" id="product_type_[[|items.id|]]" />
                                <input value="[[|items.quantity_old|]]" type="hidden" id="product_quantity_old_[[|items.id|]]" />
                                <input value="[[|items.quantity|]]" type="hidden" id="product_quantity_[[|items.id|]]" />
                                <input value="[[|items.amount_before_tax|]]" type="hidden" id="product_amount_before_tax_[[|items.id|]]" />
                                <input value="[[|items.service_amount|]]" type="hidden" id="product_service_amount_[[|items.id|]]" />
                                <input value="[[|items.tax_amount|]]" type="hidden" id="product_tax_amount_[[|items.id|]]" />
                                <input value="[[|items.amount|]]" type="hidden" id="product_amount_[[|items.id|]]" />
                                <input value="[[|items.total|]]" type="hidden" id="product_total_[[|items.id|]]" />
                            </td>
                            <td style="text-align: center; width: 80px;"><span id="lbl_quantity_[[|items.id|]]" <!--IF:cond([[=items.type=]]=='DEPOSIT' OR [[=items.type=]]=='DISCOUNT')-->style="display: none;"<!--/IF:cond-->>[[|items.quantity|]]</span></td>
                            <td style="text-align: right; width: 100px;"><span id="lbl_total_[[|items.id|]]"><?php echo System::display_number([[=items.total=]]); ?></span></td>
                            <td onclick="MoveItems('[[|items.id|]]');" style="cursor: pointer; width: 42px;" title="Move All"><i class="fa fa-fw fa-2x fa-arrow-circle-o-right w3-text-dark-gray"></i></td>
                        </tr>
                        <!--/LIST:items-->
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table id="InvoiceItems" style="width: 98%; margin: 1% auto; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15);" cellpadding="5" cellspacing="5" border="1" bordercolor="#EEE">
                        <tr style="height: 35px;">
                            <td class="w3-text-dark-gray">Description</td>
                            <td class="w3-text-dark-gray" style="width: 80px; text-align: center;">Quantity</td>
                            <td class="w3-text-dark-gray" style="text-align: right; width: 100px;">Total</td>
                            <td style="width: 42px;"></td>
                        </tr>
                        
                    </table>
                    <table id="InvoiceItems" style="width: 98%; margin: 1% auto;" cellpadding="5" cellspacing="5">
                        <tr style="height: 42px;">
                            <td style="text-align: right;">
                                [[.total_amount.]]
                                <input name="total_before_tax" type="hidden" id="total_before_tax" />
                                <input name="tax_amount" type="hidden" id="tax_amount" />
                                <input name="service_amount" type="hidden" id="service_amount" />
                            </td>
                            <td style="text-align: right; width: 100px;"><input name="total_amount" type="text" id="total_amount" class="w3-input" style="border: none; text-align: right;" readonly="" /></td>
                            <td style="width: 42px;"></td>
                        </tr>
                        <tr style="height: 42px;">
                            <td style="text-align: right;">
                                [[.paid.]]
                            </td>
                            <td style="text-align: right; width: 100px;"><label id="lbl_payment"></label></td>
                            <td style="width: 42px;"></td>
                        </tr>
                        <tr style="height: 42px;">
                            <td style="text-align: right;">
                                [[.remain.]]
                            </td>
                            <td style="text-align: right; width: 100px;"><label id="lbl_remain"></label></td>
                            <td style="width: 42px;"></td>
                        </tr>
                        <tr style="height: 42px;">
                            <td colspan="3" style="text-align: right;">
                                <!--<div onclick="" class="w3-button w3-white w3-hover-white w3-right w3-border" style="font-weight: normal; color: #f27173!important; margin-right: 3px;">
                                    <i class="fa fa-fw fa-plus"></i> [[.payment.]]
                                </div>-->
                                <?php if(Url::get('invoice_id')){ ?>
                                <div onclick="location.href='?page=touch_bar_restaurant&cmd=view_invoice&invoice_id=<?php echo Url::get('invoice_id'); ?>';" class="w3-button w3-white w3-hover-white w3-right w3-border" style="font-weight: normal; color: #f27173!important; margin-right: 3px;">
                                    <i class="fa fa-fw fa-save"></i> [[.view_invoice.]]
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="w3-row">
                        <table id="payment" style="width: 100%;" class="w3-table-all w3-hoverable">
                            <tr style="text-align: center;" class=" w3-light-grey">
                                <th style="text-align: center;">[[.description.]]</th>
                                <th style="text-align: center;">[[.amount_paid.]]</th>
                                <th style="text-align: center; display: none;">[[.time.]]</th>
                                <th style="text-align: center;">[[.payment_type.]]</th>
                                <th style="text-align: center;">[[.credit_card.]]</th>
                                <th style="text-align: center;">[[.bank_acc.]]</th>
                                <th style="width: 70px; text-align: center; display: none;">[[.paid.]]</th>
                                <th style="width: 50px; text-align: center;">[[.delete.]]</th>
                            </tr>
                        </table>
                        <div class="w3-button w3-blue w3-right" onclick="AddPayment();" style="margin: 5px;">
                            <i class="fa fa-plus fa-fw"></i> [[.add.]] [[.payment.]] 
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
</form>
<table id="InvoiceItemsTemplate" style="display: none;">
    <tr class="InvoiceItems" id="InvoiceItems_X######X" lang="X######X" style="height: 35px; font-weight: bold;">
        <td>
            <input type="text" name="invoice[X######X][name]" id="invoice_name_X######X" class="w3-input" style="border: none;" readonly="" />
            <input type="hidden" name="invoice[X######X][id]" id="invoice_id_X######X" />
            <input type="hidden" name="invoice[X######X][invoice_id]" id="invoice_invoice_id_X######X" />
            <input type="hidden" name="invoice[X######X][type]" id="invoice_type_X######X" />
            <input type="hidden" name="invoice[X######X][amount_before_tax]" id="invoice_amount_before_tax_X######X" />
            <input type="hidden" name="invoice[X######X][service_amount]" id="invoice_service_amount_X######X" />
            <input type="hidden" name="invoice[X######X][tax_amount]" id="invoice_tax_amount_X######X" />
            <input type="hidden" name="invoice[X######X][amount]" id="invoice_amount_X######X" />
            <input type="hidden" name="invoice[X######X][quantity_old]" id="invoice_quantity_old_X######X" />
        </td>
        <td style="text-align: center; width: 80px;"><input type="number" name="invoice[X######X][quantity]" id="invoice_quantity_X######X" onkeyup="ChangeQuantity('X######X');" onchange="ChangeQuantity('X######X');" class="w3-input" style="width: 60px; border: none; text-align: center;" /></td>
        <td style="text-align: right; width: 100px;"><input type="text" name="invoice[X######X][total]" id="invoice_total_X######X" class="w3-input" style="border: none; text-align: right;" readonly="" /></td>
        <td onclick="DeleteInvoiceItems('X######X');" style="cursor: pointer; width: 42px;"><i class="fa fa-fw fa-2x fa-remove w3-text-pink"></i></td>
    </tr>
</table>
<table id="payment_teamplate" style="display: none;">
    <tr id="payment_#xxxx#" class="PaymentItems" lang="#xxxx#">
        <td style="position: relative;">
            <input type="hidden" name="payment[#xxxx#][id]" id="payment_id_#xxxx#" />
            <input type="text" name="payment[#xxxx#][description]" id="payment_description_#xxxx#" class="w3-input w3-border" />
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative;">
            <input type="text" name="payment[#xxxx#][amount]" id="payment_amount_#xxxx#" class="input_number w3-input w3-border" style="text-align: right;" onkeyup="if(jQuery(this).val()!=''){jQuery(this).val(number_format(to_numeric(jQuery(this).val())));} GetTotalInvoice();" />
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative; display: none;">
            <input type="text" name="payment[#xxxx#][time]" id="payment_time_#xxxx#" class="w3-input w3-border" readonly="" />
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative;">
            <select id="payment_payment_type_id_#xxxx#" name="payment[#xxxx#][payment_type_id]" class="w3-select w3-border" style="height: 33px;" onchange="ShowHideBank(#xxxx#,'PAYMENT');">[[|payment_type_option|]]</select>
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative;">
            <select id="payment_credit_card_id_#xxxx#" name="payment[#xxxx#][credit_card_id]" class="w3-select w3-border" style="height: 33px;">[[|credit_card_option|]]</select>
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative;">
            <input type="text" name="payment[#xxxx#][bank_acc]" id="payment_bank_acc_#xxxx#" class="w3-input w3-border" />
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative; text-align: center; display: none;">
            <input type="checkbox" name="payment[#xxxx#][paid]" id="payment_paid_#xxxx#" class="w3-check w3-border" disabled="disabled" />
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
        <td style="position: relative;">
            <input id="payment_delete_#xxxx#" class="w3-button w3-red" type="button" value="[[.delete.]]" onclick="DeletePayment('#xxxx#');" />
            <span class="payment_lock_#xxxx#" style="width: 100%; height: 100%; background: rgba(255,255,255,0.4); position: absolute; top: 0px; left: 0px; z-index: 999; display: none;"></span>
        </td>
    </tr>
</table>
<script>
    var full_charge = to_numeric([[|full_charge|]]);
    var full_rate = to_numeric([[|full_rate|]]);
    var bar_fee_rate = to_numeric([[|bar_fee_rate|]]);
    var tax_rate = to_numeric([[|tax_rate|]]);
    var discount_after_tax = to_numeric([[|discount_after_tax|]]);
    var input_count_payment=100;
    var payment = <?php echo isset($_REQUEST['payment'])?String::array2js($_REQUEST['payment']):String::array2js(array()); ?>;
    for(var i in payment){
        AddPayment(payment[i]);
    }
    function AddPayment($row) {
        input_count_payment++;
        var content = jQuery("#payment_teamplate").html().replace(/#xxxx#/g,input_count_payment);
        jQuery("#payment").append(content);
        if($row){
            jQuery("#payment_id_"+input_count_payment).val($row['id']);
            jQuery("#payment_description_"+input_count_payment).val($row['description']);
            jQuery("#payment_amount_"+input_count_payment).val(number_format(to_numeric($row['amount'])));
            jQuery("#payment_time_"+input_count_payment).val($row['time']);
            jQuery("#payment_payment_type_id_"+input_count_payment).val($row['payment_type_id']);
            jQuery("#payment_credit_card_id_"+input_count_payment).val($row['credit_card_id']);
            jQuery("#payment_bank_acc_"+input_count_payment).val($row['bank_acc']);
            jQuery("#payment_paid_"+input_count_payment).attr('checked','checked');
            if($row['payment_type_id']=='CREDIT_CARD'){
                jQuery("#payment_credit_card_id_"+input_count_payment).removeAttr('disabled');
                jQuery("#payment_bank_acc_"+input_count_payment).removeAttr('readonly');
            }else if($row['payment_type_id']=='BANK'){
                jQuery("#payment_credit_card_id_"+input_count_payment).attr('disabled','disabled');
                jQuery("#payment_bank_acc_"+input_count_payment).removeAttr('readonly');
                jQuery("#payment_credit_card_id_"+input_count_payment).val('');
            }else{
                jQuery("#payment_credit_card_id_"+input_count_payment).attr('disabled','disabled');
                jQuery("#payment_bank_acc_"+input_count_payment).attr('readonly','readonly');
                jQuery("#payment_credit_card_id_"+input_count_payment).val('');
                jQuery("#payment_bank_acc_"+input_count_payment).val('');
            }
            jQuery(".payment_lock_"+input_count_payment).css('display','');
            jQuery(".payment_lock_"+input_count_payment).click(function(){ alert('[[.paid.]] [[.not_edited.]]') });
        }else{
            jQuery("#payment_credit_card_id_"+input_count_payment).attr('disabled','disabled');
            jQuery("#payment_bank_acc_"+input_count_payment).attr('readonly','readonly');
            jQuery("#payment_credit_card_id_"+input_count_payment).val('');
            jQuery("#payment_bank_acc_"+input_count_payment).val('');
            if(to_numeric(jQuery('#lbl_remain').html())>=0){
                jQuery("#payment_amount_"+input_count_payment).val(number_format(to_numeric(jQuery('#lbl_remain').html())));
                jQuery("#payment_payment_type_id_"+input_count_payment).val('CASH');
            }else{
                jQuery("#payment_amount_"+input_count_payment).val(number_format(to_numeric(jQuery('#lbl_remain').html())));
                jQuery("#payment_payment_type_id_"+input_count_payment).val('REFUND');
            }
        }
        jQuery('#payment_amount_'+input_count_payment).FormatNumber();
        GetTotalInvoice();
    }
    function DeletePayment($input_count){
        jQuery("#payment_"+$input_count).remove();
        GetTotalInvoice();
    }
    function MoveItems(key){
        if(key=='ALL'){
            jQuery('.ProductItems').each(function(){
                CreateInvoice(this.lang);
                jQuery("#product_total_"+this.lang).val(0);
                jQuery("#product_quantity_"+this.lang).val(0);
                jQuery("#lbl_quantity_"+this.lang).html(0);
                jQuery("#lbl_total_"+this.lang).html(0);
            });
        }else{
            CreateInvoice(key);
            jQuery("#product_total_"+key).val(0);
            jQuery("#product_quantity_"+key).val(0);
            jQuery("#lbl_quantity_"+key).html(0);
            jQuery("#lbl_total_"+key).html(0);
        }
    }
    function CreateInvoice($index){
        if(!jQuery('#InvoiceItems_'+$index).html()){ // w3-light-grey
            AddElementInvoice($index);
            AddDataInvoice(GetRowProduct($index),$index);
            jQuery('#ProductItems_'+$index).removeClass('w3-light-grey');
            jQuery('#ProductItems_'+$index).addClass('w3-light-grey');
        }else{
            AddDataInvoice(GetRowProduct($index),$index);
            jQuery('#ProductItems_'+$index).removeClass('w3-light-grey');
            jQuery('#ProductItems_'+$index).addClass('w3-light-grey');
        }
    }
    function GetRowProduct($index){
        $row = {};
        $row = {
                id : (!jQuery('#invoice_id_'+$index))?'':jQuery('#invoice_id_'+$index).val(),
                name : jQuery("#product_name_"+$index).val(),
                invoice_id : jQuery("#product_invoice_id_"+$index).val(),
                type : jQuery("#product_type_"+$index).val(),
                quantity : jQuery("#product_quantity_"+$index).val(),
                amount_before_tax : jQuery("#product_amount_before_tax_"+$index).val(),
                service_amount : jQuery("#product_service_amount_"+$index).val(),
                tax_amount : jQuery("#product_tax_amount_"+$index).val(),
                amount : jQuery("#product_amount_"+$index).val(),
                total : jQuery("#product_total_"+$index).val()
            };
        return $row;
    }
    function AddElementInvoice($index){
        var content = jQuery("#InvoiceItemsTemplate").html().replace(/X######X/g,$index);
        jQuery("#InvoiceItems").append(content);
    }
    function AddDataInvoice($row,$index){
        jQuery('#invoice_total_'+$index).val(number_format( to_numeric(jQuery('#invoice_total_'+$index).val())+to_numeric($row.total) ));
        jQuery('#invoice_id_'+$index).val($row.id);
        jQuery('#invoice_name_'+$index).val($row.name);
        jQuery('#invoice_invoice_id_'+$index).val($row.invoice_id);
        jQuery('#invoice_type_'+$index).val($row.type);
        jQuery('#invoice_quantity_old_'+$index).val(to_numeric(jQuery('#invoice_quantity_'+$index).val())+to_numeric($row.quantity));
        jQuery('#invoice_quantity_'+$index).val(to_numeric(jQuery('#invoice_quantity_'+$index).val())+to_numeric($row.quantity));
        jQuery('#invoice_amount_before_tax_'+$index).val($row.amount_before_tax);
        jQuery('#invoice_service_amount_'+$index).val($row.service_amount);
        jQuery('#invoice_tax_amount_'+$index).val($row.tax_amount);
        jQuery('#invoice_amount_'+$index).val($row.amount);
        if($row.type=='DEPOSIT' || $row.type=='DISCOUNT'){
            jQuery('#invoice_quantity_'+$index).attr('readonly','readonly');
            jQuery('#invoice_quantity_'+$index).css('display','none');
        }
        GetTotalInvoice();
    }
    function ChangeQuantity($index){
        if(to_numeric(jQuery('#invoice_quantity_'+$index).val())<0){
            alert('số lượng phải lớn hơn hoặc bằng 0');
            jQuery('#invoice_quantity_'+$index).val(jQuery('#invoice_quantity_old_'+$index).val());
        }else if(to_numeric(jQuery('#invoice_quantity_'+$index).val())>jQuery('#invoice_quantity_old_'+$index).val()){
            alert('số lượng không được lớn hơn '+jQuery('#invoice_quantity_old_'+$index).val());
            jQuery('#invoice_quantity_'+$index).val(jQuery('#invoice_quantity_old_'+$index).val());
        }
        jQuery('#invoice_total_'+$index).val(number_format(to_numeric(jQuery('#invoice_quantity_'+$index).val())*to_numeric(jQuery('#invoice_amount_'+$index).val())));
        
        GetTotalInvoice();
    }
    function DeleteInvoiceItems($index){
        if(jQuery("#product_quantity_"+$index).val()){
            jQuery("#product_quantity_"+$index).val( jQuery('#product_quantity_old_'+$index).val() );
            jQuery("#lbl_quantity_"+$index).html(jQuery("#product_quantity_"+$index).val());
            
            jQuery('#product_total_'+$index).val(number_format( (to_numeric(jQuery('#product_quantity_'+$index).val())*to_numeric(jQuery("#product_amount_"+$index).val())) ));
            jQuery("#lbl_total_"+$index).html(number_format(to_numeric(jQuery('#product_total_'+$index).val())));
        }
        jQuery("#InvoiceItems_"+$index).remove();
        jQuery('#ProductItems_'+$index).removeClass('w3-light-grey');
        GetTotalInvoice();
    }
    function GetTotalInvoice(){
        var is_discount = false;
        var discount = 0;
        var deposit = 0;
        var total_amount = 0;
        jQuery('.InvoiceItems').each(function(){
            $index = this.lang;
            if($index!='X######X'){
                if(jQuery('#invoice_type_'+$index).val()=='DEPOSIT' || jQuery('#invoice_type_'+$index).val()=='DISCOUNT'){
                    if(jQuery('#invoice_type_'+$index).val()=='DEPOSIT'){
                        deposit = to_numeric(jQuery('#invoice_total_'+$index).val());
                    }else if(jQuery('#invoice_type_'+$index).val()=='DISCOUNT'){
                        discount = to_numeric(jQuery('#invoice_total_'+$index).val());
                        is_discount = true;
                    }
                }else{
                    total_amount += to_numeric(jQuery('#invoice_total_'+$index).val());
                }
            }
        });
        total_before_tax = total_amount / ( (1+bar_fee_rate/100)*(1+tax_rate/100) );
        tax_amount = total_amount - (total_before_tax + (total_before_tax*bar_fee_rate)/100);
        service_amount = total_amount - (tax_amount+total_before_tax);
        if(is_discount){
            if(discount_after_tax==0){
                total_before_tax -= discount;
                total_amount = total_before_tax * ( (1+bar_fee_rate/100)*(1+tax_rate/100) );
                tax_amount = total_amount - (total_before_tax + (total_before_tax*bar_fee_rate)/100);
                service_amount = total_amount - (tax_amount+total_before_tax);
            }else{
                total_amount -= discount;
            }
        }
        total_amount -= deposit;
        
        var paid = GetTotalPayment();
        var remain = total_amount - paid;
        jQuery("#total_amount").val(number_format(total_amount));
        jQuery("#total_before_tax").val((total_before_tax));
        jQuery("#tax_amount").val((tax_amount));
        jQuery("#service_amount").val((service_amount));
        jQuery("#lbl_payment").html(number_format(paid));
        jQuery("#lbl_remain").html(number_format(remain));
    }
    function GetTotalPayment(){
        var $payment = 0;
        jQuery('.PaymentItems').each(function(){
            $index = this.lang;
            if($index!='#xxxx#'){
                if(jQuery("#payment_payment_type_id_"+$index).val()!='REFUND'){
                    $payment += to_numeric(jQuery("#payment_amount_"+$index).val());
                }else{
                    $payment -= to_numeric(jQuery("#payment_amount_"+$index).val());
                }
            }
        });
        return $payment;
    }
    function ShowHideBank($input_count,$type){
        $a = 'payment';
        if(jQuery("#"+$a+"_payment_type_id_"+$input_count).val()=='CREDIT_CARD'){
            jQuery("#"+$a+"_credit_card_id_"+$input_count).removeAttr('disabled');
            jQuery("#"+$a+"_bank_acc_"+$input_count).removeAttr('readonly');
        }else if(jQuery("#"+$a+"_payment_type_id_"+$input_count).val()=='BANK'){
            jQuery("#"+$a+"_credit_card_id_"+$input_count).attr('disabled','disabled');
            jQuery("#"+$a+"_bank_acc_"+$input_count).removeAttr('readonly');
            jQuery("#"+$a+"_credit_card_id_"+$input_count).val('');
        }else{
            jQuery("#"+$a+"_credit_card_id_"+$input_count).attr('disabled','disabled');
            jQuery("#"+$a+"_bank_acc_"+$input_count).attr('readonly','readonly');
            jQuery("#"+$a+"_credit_card_id_"+$input_count).val('');
            jQuery("#"+$a+"_bank_acc_"+$input_count).val('');
        }
        GetTotalInvoice();
    }
    function SaveInvoice(){
        var $check = true;
        var $messenge = '';
        var is_payment = false;
        for(var i=101;i<=input_count_payment;i++){
            if(jQuery("#payment_id_"+i).val()!=undefined){
                if(jQuery('#payment_amount_'+i).val()==''){
                    $messenge += 'Số tiền thanh toán không được để trống \n';
                    $check = false;
                }
                if(jQuery('#payment_payment_type_id_'+i).val()==''){
                    $messenge += 'Loại thanh toán không được để trống \n';
                    $check = false;
                }
                is_payment = true;
            }
        }
        if(is_payment){
            if(to_numeric(jQuery('#lbl_remain').html())!=0){
                $messenge += 'Bạn chưa thanh toán hết \n';
                $check = false;
            }
        }
        
        if(!$check){
            alert($messenge);
        }else{
            InvoiceBarForm.submit();
        }
        return;
    }
</script>