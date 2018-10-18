<form name="InvoiceMiceReservationForm" method="POST">
    <input name="act" id="act" type="text" style="display: none;" />
   <div style="width: 950px; height: auto; margin: 10px auto; padding: 5px; background: #FFFFFF; border: 1px solid #DDDDDD;">
        <table style="width: 100%;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
            <tr style="display: none;">
                <td colspan="2" style="text-align: center; text-transform: uppercase;">
                    <h1 style="line-height: 30px; padding: 0px; margin: 0px auto; font-weight: normal; color: #656565;">
                        [[.invoice.]] MICE <?php echo Url::get('id'); ?>
                        <?php if(isset($_REQUEST['invoice_id'])){ echo ' ('.$_REQUEST['invoice_id'].')'; } ?>
                    </h1>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <select onclick="OpenMiceInvoice(this.value);" style="padding: 5px; margin-right: 5px; border: 1px solid #ff6600; color: #ff6600; font-weight: bold;">
                        <option value="" style="color: #ff6600; font-weight: bold;">[[.bill_folio.]]</option>
                        <!--LIST:invoice_mice_other-->
                        <?php if([[=invoice_mice_other.payment_time=]]!=''){ ?>
                            <option value="[[|invoice_mice_other.id|]]" style="color: #ff6600; font-weight: bold;"> - [[|invoice_mice_other.bill_id|]]</option>
                        <?php }?>
                        <!--/LIST:invoice_mice_other-->
                    </select>
                    <select onclick="OpenMiceInvoice(this.value);" style="padding: 5px; margin-right: 5px; border: 1px solid #89d0ff; color: #89d0ff; font-weight: bold;">
                        <option value="" style="color: #89d0ff; font-weight: bold;">[[.preview_order.]]</option>
                        <!--LIST:invoice_mice_other-->
                        <?php if([[=invoice_mice_other.payment_time=]]==''){ ?>
                            <option value="[[|invoice_mice_other.id|]]" style="color: #89d0ff; font-weight: bold;"> - [[|invoice_mice_other.bill_id|]]</option>
                        <?php } ?>
                        <!--/LIST:invoice_mice_other-->
                    </select>
                </td>
                <td style="text-align: right;">
                    <!--LIST:invoice_mice_other-->
                    <?php if(Url::get('invoice_id')==[[=invoice_mice_other.id=]]){ ?>
                        <span style="float: left; line-height: 30px; color: #ff5656; font-weight: bold;">[[|invoice_mice_other.bill_id|]]</span>
                    <?php } ?>
                    <!--/LIST:invoice_mice_other-->
                    <select name="reservation_traveller_id" id="reservation_traveller_id" style="padding: 5px; width: 150px;">
                        
                    </select>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                        <div id="save" class="DivButton" onclick="jQuery('#act').val('save'); InvoiceMiceReservationForm.submit();" style="display: none;">
                            <label> <span><i class="fa fa-save fa-fw"></i> [[.save.]]</span></label>
                        </div>
                    <?php } ?>
                    <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation&cmd=invoice&id=<?php echo Url::get('id'); ?>';">
                            <label> <span><i class="fa fa-plus fa-fw"></i> [[.create_mice_invoice.]]</span></label>
                        </div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="display: none;">
                    <ul class="tag-button green">
                        <!--LIST:items-->
                            <?php if([[=items.count_item=]]>0){ ?>
                            <li><i>[[|items.name|]] <span>[[|items.count_item|]]</span></i></li>
                            <?php } ?>
                        <!--/LIST:items-->
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table id="Items" style="width: 100%;" cellpadding="5" border="1" bordercolor="#CCCCCC">
                        <tr style="height: 35px;">
                            <td colspan="3" style="text-transform: uppercase;"></td>
                            <td style="width: 20px; text-align: center;"><i onclick="moveitem('ALL','ALL');" class="fa fa-fw fa-2x fa-arrow-circle-o-right iconasome"></i></td>
                        </tr>
                        <!--LIST:items-->
                            <?php if([[=items.count_item=]]>0){ ?>
                            <tr style="background: #F8F8F8;">
                                <td colspan="3">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 20px; text-align: center; font-weight: bold;"><i id="items_[[|items.id|]]" class="fa fa-fw fa-plus-square-o iconasome" onclick="toogleshowhide('items_[[|items.id|]]');"></i><input id="class_items_[[|items.id|]]" type="checkbox" checked="checked" style="display: none;" /></td>
                                            <td style="font-weight: bold;">[[|items.id|]]: <i>[[|items.name|]]</i></td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo System::display_number([[=items.total_amount=]]); ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 20px; text-align: center;"><i onclick="moveitem('[[|items.id|]]','ALL');" class="fa fa-fw fa-2x fa-arrow-circle-o-right iconasome"></i></td>
                            </tr>
                            <!--LIST:items.item-->
                                <tr class="class_items_[[|items.id|]]" id="mice_items_[[|items.id|]]_[[|items.item.id|]]" style="display: none; <?php if([[=items.item.status=]]==1){ ?> background: #DDDDDD; <?php } ?>">
                                    <td colspan="3">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 40px; text-align: center;"><?php echo substr([[=items.item.date=]],0,-5); ?></td>
                                                <td>[[|items.item.description|]] [[|items.item.description_|]]</td>
                                                <td style="text-align: right;"><a target="_blank" href="[[|items.item.link|]]" style="color: #15885b;">[[|items.item.bill_number|]]</a></td>
                                                <td style="text-align: right; width: 120px;">
                                                    [[|items.item.total_amount|]]
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width: 20px; text-align: center;"><i onclick="moveitem('[[|items.id|]]','[[|items.item.id|]]');" class="fa fa-fw fa-2x fa-arrow-circle-o-right iconasomechild"></i></td>
                                </tr>
                            <!--/LIST:items.item-->
                            <?php } ?>
                        <!--/LIST:items-->
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table id="TableSplit" style="width: 100%;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
                        <tr style="text-transform: uppercase; height: 35px;">
                            <th>[[.description.]] [[.detail.]]</th>
                            <th>%</th>
                            <th style="width: 120px;">[[.amount.]]</th>
                            <th style="width: 20px; text-align: center;"></th>
                        </tr>
                    </table>
                    <table id="PaymentDescription" style="width: 100%; display: none;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><b><i>[[.amount.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;"><input name="total_before_tax" type="text" id="total_before_tax" readonly="" style="border: none; text-align: right; width: 120px;" /></td>
                            <td style="width: 20px; text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><b><i>[[.service_amount.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;"><input name="service_amount" type="text" id="service_amount" readonly="" style="border: none; text-align: right; width: 120px;" /></td>
                            <td style="width: 20px; text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><b><i>[[.tax_amount.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;"><input name="tax_amount" type="text" id="tax_amount" readonly="" style="border: none; text-align: right; width: 120px;" /></td>
                            <td style="width: 20px; text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><b><i>[[.extra_amount.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;"><input name="extra_amount" type="text" id="extra_amount" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" class="input_number" style="border: none; text-align: right; width: 120px;" /></td>
                            <td style="width: 20px; text-align: center;"></td>
                            <td style="text-align: right;"><b><i>[[.extra_vat.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;"><input name="extra_vat" type="text" id="extra_vat" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); gettotalamount();" class="input_number" style="border: none; text-align: right; width: 120px;" /></td>
                            <td style="width: 20px; text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><b><i>[[.total_amount.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;"><input name="total_amount" type="text" id="total_amount" readonly="" style="border: none; text-align: right; width: 120px;" /></td>
                            <td style="width: 20px; text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><b><i>[[.balance.]]: </i></b></td>
                            <td style="width: 120px; text-align: right;">
                                <input value="[[|payment|]]" name="payment" type="text" id="payment" readonly="" style="border: none; text-align: right; width: 120px; display: none;" />
                                <input name="balance" type="text" id="balance" readonly="" style="border: none; text-align: right; width: 120px;" />
                            </td>
                            <td style="width: 20px; text-align: center;"></td>
                        </tr>
                    </table>
                    <table id="PaymentAction" style="width: 100%; display: none;" cellpadding="5" border="1" bordercolor="#EEEEEEE">
                        <tr>
                            <td style="text-align: right;">
                                <?php if(isset($_REQUEST['invoice_id'])){ ?>
                                    <div class="DivButton" onclick="window.open('?page=mice_reservation&cmd=bill_new&invoice_id=<?php echo $_REQUEST['invoice_id']; ?>')">
                                        <label> <span><i class="fa fa-file fa-fw"></i> [[.view_invoice.]]</span></label>
                                    </div>
                                <?php } ?>
                                <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                                    <div id="payment_button" class="DivButton" onclick="jQuery('#act').val('payment'); InvoiceMiceReservationForm.submit();" style="display: none;">
                                        <label> <span><i class="fa fa-usd fa-fw"></i> [[.save_and_payment.]]</span></label>
                                    </div>
                                <?php } ?>
                                <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                                    <div id="refunr_button" class="DivButton" onclick="jQuery('#act').val('payment'); InvoiceMiceReservationForm.submit();" style="display: none;">
                                        <label> <span><i class="fa fa-usd fa-fw"></i> [[.save_and_refunr.]]</span></label>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--
            <tr style="height: 50px;">
                <td colspan="2">
                    <i class="fa fa-fw fa-bookmark" style="color: #ff5656; float: left; font-size: 13px;"></i><span style="color: #656565; float: left;">[[.note.]]:</span> 
                    <div style="width: 15px;  height: 15px; background: #89d0ff; float: left; margin-left: 20px;"></div>
                    <span style="margin-left: 5px; color: #656565; float: left;">[[.preview_order.]]</span> 
                    <div style="width: 15px; height: 15px; background: #ff6600; float: left; margin-left: 20px;"></div>
                    <span style="margin-left: 5px; color: #656565; float: left;">[[.bill_folio.]]</span>
                    <div style="width: 15px; height: 15px; background: #ff5656; float: left; margin-left: 20px;"></div>
                    <span style="margin-left: 5px; color: #656565; float: left;">[[.invoice_active.]]</span>
                </td>
            </tr>
            -->
        </table>
   </div>
</form>
<table id="item_teamplate" style="display: none;">
    <tr class="class_group_X######X" id="invoice_Y######Y" style="display: none; height: 35px;">
        <td>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <input id="invoice_id_Y######Y" type="text" name="invoice[Y######Y][id]" style="display: none;" />
                        <input id="invoice_type_Y######Y" type="text" name="invoice[Y######Y][type]" style="display: none;" />
                        <input id="invoice_key_Y######Y" type="text" name="invoice[Y######Y][key]" style="display: none;" />
                        <input id="invoice_invoice_id_Y######Y" type="text" name="invoice[Y######Y][invoice_id]" style="display: none;" />
                        <input id="invoice_max_amount_Y######Y" type="text" name="invoice[Y######Y][max_amount]" style="display: none;" />
                        <input id="invoice_max_percent_Y######Y" type="text" name="invoice[Y######Y][max_percent]" style="display: none;" />
                        <input id="invoice_service_amount_Y######Y" type="text" name="invoice[Y######Y][service_amount]" class="service_amount_payment" style="display: none;" />
                        <input id="invoice_tax_amount_Y######Y" type="text" name="invoice[Y######Y][tax_amount]" class="tax_amount_payment" style="display: none;" />
                        <input id="invoice_service_rate_Y######Y" type="text" name="invoice[Y######Y][service_rate]" style="display: none;" />
                        <input id="invoice_tax_rate_Y######Y" type="text" name="invoice[Y######Y][tax_rate]" style="display: none;" />
                        <input id="invoice_date_use_Y######Y" type="text" name="invoice[Y######Y][date_use]" style="display: none;" />
                        <label id="invoice_in_date_Y######Y"></label>
                    </td>
                    <td>
                        <input id="invoice_description_Y######Y" type="text" name="invoice[Y######Y][description]" style="width: 100%; border: none; float: left;" />
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 30px;">
            <input id="invoice_percent_Y######Y" type="text" name="invoice[Y######Y][percent]" class="input_number" onkeyup="ChangePrice('percent','Y######Y');" style="width: 100%; border: none; text-align: center;" />
        </td>
        <td style="width: 120px; text-align: right;">
            <input id="invoice_total_amount_Y######Y" type="text" name="invoice[Y######Y][total_amount]" class="input_number amount_payment" onkeyup="ChangePrice('amount','Y######Y');" style="width: 120px; border: none; text-align: right;" />
        </td>
        <td style="width: 120px; text-align: right; display: none;">
            <input id="invoice_amount_Y######Y" type="text" name="invoice[Y######Y][amount]" class="input_number" onkeyup="ChangePrice('amount','Y######Y');" style="width: 120px; border: none; text-align: right;" />
        </td>
        <td style="width: 20px; text-align: center;">
            <i class="fa fa-fw fa-remove iconasomechild" onclick="removeitem('X######X','Y######Y'); "></i>
        </td>
    </tr>
</table>
<table id="group_template" style="display: none;">
    <tr style="background: #f7f7f7; height: 35px;">
        <td colspan="3">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20px; text-align: center; font-weight: bold;"><i id="group_X######X" class="fa fa-fw fa-plus-square-o iconasome" onclick="toogleshowhide('group_X######X');"></i><input id="class_group_X######X" type="checkbox" checked="checked" style="display: none;" /></td>
                    <td style="font-weight: bold;"><label id="group_id_X######X"></label></td>
                    <td style="text-align: right; font-weight: bold;"><label id="group_amount_X######X"></label></td>
                </tr>
            </table>
        </td>
        <td style="width: 20px; text-align: center;"><i class="fa fa-fw fa-remove iconasome" onclick="removeitem('X######X','ALL');"></i></td>
    </tr>
</table>
<script>
    var items_js = <?php echo String::array2js([[=items=]]); ?>;
    var invoice_detail = new Array();
    var Input_count_group = 100;
    var Input_count_item = 101;
    <?php if(isset($_REQUEST['invoice_id'])){ ?>
        invoice_detail = <?php echo String::array2js([[=invoice_detail=]]); ?>;
        Input_count_group = [[|input_count|]];
        Input_count_item = [[|input_count_detail|]];
        jQuery("#extra_amount").val(number_format(to_numeric(<?php echo [[=extra_amount=]]==''?0:[[=extra_amount=]]; ?>)));
        jQuery("#extra_vat").val(number_format(to_numeric(<?php echo [[=extra_vat=]]==''?0:[[=extra_vat=]]; ?>)));
        parselayout();
    <?php } ?>
    jQuery(document).ready(function(){
        CloseMenu();
    });
    
    function CloseMenu()
    {
        jQuery('#testRibbon').css('display','none');
        jQuery("#sign-in").css('display','none');
        jQuery("#chang_language").css('display','none');
    }
    function OpenMenu()
    {
        jQuery('#testRibbon').css('display','');
        jQuery("#sign-in").css('display','');
        jQuery("#chang_language").css('display','');
    }
    function toogleshowhide(key)
    {
        if(document.getElementById("class_"+key).checked==true)
        {
            jQuery("#"+key).removeClass('fa-plus-square-o');
            jQuery("#"+key).addClass('fa-minus-square-o');
            document.getElementById("class_"+key).checked = false;
            jQuery(".class_"+key).css('display','');
        }
        else
        {
            jQuery("#"+key).removeClass('fa-minus-square-o');
            jQuery("#"+key).addClass('fa-plus-square-o');
            document.getElementById("class_"+key).checked = true;
            jQuery(".class_"+key).css('display','none');
        }
    }
    function moveitem(items_id,item_id)
    {
        for(var i in items_js)
        {
            if(items_id != 'ALL')
            {
                if(items_js[i]['id'] == items_id)
                {
                    item = items_js[i]['item'];
                    for(var j in item)
                    {
                        if(item_id!='ALL')
                        {
                            if(item[j]['id']==item_id && to_numeric(item[j]['status'])!=1)
                            {
                                $check = true;
                                if(invoice_detail[item[j]['type']] == undefined)
                                {
                                    Input_count_group++;
                                    invoice_detail[item[j]['type']] = new Array();
                                    invoice_detail[item[j]['type']]['id'] = item[j]['type'];
                                    invoice_detail[item[j]['type']]['input_count'] = Input_count_group;
                                    invoice_detail[item[j]['type']]['child'] = new Array();
                                }
                                else
                                {
                                    for(var T in invoice_detail[item[j]['type']]['child'])
                                    {
                                        $A_check = invoice_detail[item[j]['type']]['child'][T];
                                        if($A_check['type']==item[j]['type'] && $A_check['invoice_id']==item[j]['id'])
                                        {
                                            $check = false;
                                        }
                                    }
                                }
                                if($check == true)
                                {
                                    //console.log(item[j]);
                                    Input_count_item++;
                                    invoice_detail[item[j]['type']]['child'][Input_count_item] = new Array();
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['id'] = '';
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['type'] = item[j]['type'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['key'] = items_js[i]['id'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['invoice_id'] = item[j]['id'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['amount'] = number_format(to_numeric(item[j]['net_amount']));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['max_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['percent'] = to_numeric(item[j]['percent']);
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['max_percent'] = to_numeric(item[j]['percent']);
                                    $service_rate = to_numeric(item[j]['service_rate']);
                                    $tax_rate = to_numeric(item[j]['tax_rate']);
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['service_amount'] = number_format(to_numeric(item[j]['net_amount'])*($service_rate/100));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['service_rate'] = $service_rate;
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_amount'] = number_format((to_numeric(item[j]['net_amount'])+(to_numeric(item[j]['net_amount'])*($service_rate/100)))*($tax_rate/100));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_rate'] = $tax_rate;
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['date_use'] = item[j]['date'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['total_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['description'] = item[j]['description'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['input_count'] = Input_count_item;
                                    jQuery("#mice_items_"+items_js[i]['id']+"_"+item[j]['id']).css('background','#DDDDDD');
                                    break;
                                }
                            }
                        }
                        else
                        {
                            if(to_numeric(item[j]['status'])!=1)
                            {
                                $check = true;
                                
                                if(invoice_detail[item[j]['type']] == undefined)
                                {
                                    Input_count_group++;
                                    invoice_detail[item[j]['type']] = new Array();
                                    invoice_detail[item[j]['type']]['id'] = item[j]['type'];
                                    invoice_detail[item[j]['type']]['input_count'] = Input_count_group;
                                    invoice_detail[item[j]['type']]['child'] = new Array();
                                }
                                else
                                {
                                    for(var T in invoice_detail[item[j]['type']]['child'])
                                    {
                                        $A_check = invoice_detail[item[j]['type']]['child'][T];
                                        if($A_check['type']==item[j]['type'] && $A_check['invoice_id']==item[j]['id'])
                                        {
                                            $check = false;
                                        }
                                    }
                                }
                                if($check == true)
                                {
                                    Input_count_item++;
                                    invoice_detail[item[j]['type']]['child'][Input_count_item] = new Array();
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['id'] = '';
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['type'] = item[j]['type'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['key'] = items_js[i]['id'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['invoice_id'] = item[j]['id'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['amount'] = number_format(to_numeric(item[j]['net_amount']));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['max_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['percent'] = to_numeric(item[j]['percent']);
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['max_percent'] = to_numeric(item[j]['percent']);
                                    $service_rate = to_numeric(item[j]['service_rate']);
                                    $tax_rate = to_numeric(item[j]['tax_rate']);
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['service_amount'] = number_format(to_numeric(item[j]['net_amount'])*($service_rate/100));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['service_rate'] = $service_rate;
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_amount'] = number_format((to_numeric(item[j]['net_amount'])+(to_numeric(item[j]['net_amount'])*($service_rate/100)))*($tax_rate/100));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_rate'] = $tax_rate;
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['date_use'] = item[j]['date'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['total_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['description'] = item[j]['description'];
                                    invoice_detail[item[j]['type']]['child'][Input_count_item]['input_count'] = Input_count_item;
                                    jQuery("#mice_items_"+items_js[i]['id']+"_"+item[j]['id']).css('background','#DDDDDD');
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                item = items_js[i]['item'];
                for(var j in item)
                {
                    if(item_id!='ALL')
                    {
                        if(item[j]['id']==item_id && to_numeric(item[j]['status'])!=1)
                        {
                            $check = true;
                            
                            if(invoice_detail[item[j]['type']] == undefined)
                            {
                                Input_count_group++;
                                invoice_detail[item[j]['type']] = new Array();
                                invoice_detail[item[j]['type']]['id'] = item[j]['type'];
                                invoice_detail[item[j]['type']]['input_count'] = Input_count_group;
                                invoice_detail[item[j]['type']]['child'] = new Array();
                            }
                            else
                            {
                                for(var T in invoice_detail[item[j]['type']]['child'])
                                {
                                    $A_check = invoice_detail[item[j]['type']]['child'][T];
                                    if($A_check['type']==item[j]['type'] && $A_check['invoice_id']==item[j]['id'])
                                    {
                                        $check = false;
                                    }
                                }
                            }
                            if($check == true)
                            {
                                Input_count_item++;
                                invoice_detail[item[j]['type']]['child'][Input_count_item] = new Array();
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['id'] = '';
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['type'] = item[j]['type'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['key'] = items_js[i]['id'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['invoice_id'] = item[j]['id'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['amount'] = number_format(to_numeric(item[j]['net_amount']));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['max_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['percent'] = to_numeric(item[j]['percent']);
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['max_percent'] = to_numeric(item[j]['percent']);
                                $service_rate = to_numeric(item[j]['service_rate']);
                                $tax_rate = to_numeric(item[j]['tax_rate']);
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['service_amount'] = number_format(to_numeric(item[j]['net_amount'])*($service_rate/100));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['service_rate'] = $service_rate;
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_amount'] = number_format((to_numeric(item[j]['net_amount'])+(to_numeric(item[j]['net_amount'])*($service_rate/100)))*($tax_rate/100));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_rate'] = $tax_rate;
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['date_use'] = item[j]['date'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['total_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['description'] = item[j]['description'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['input_count'] = Input_count_item;
                                jQuery("#mice_items_"+items_js[i]['id']+"_"+item[j]['id']).css('background','#DDDDDD');
                                break;
                            }
                        }
                    }
                    else
                    {
                        if(to_numeric(item[j]['status'])!=1)
                        {
                            $check = true;
                            if(invoice_detail[item[j]['type']] == undefined)
                            {
                                Input_count_group++;
                                invoice_detail[item[j]['type']] = new Array();
                                invoice_detail[item[j]['type']]['id'] = item[j]['type'];
                                invoice_detail[item[j]['type']]['input_count'] = Input_count_group;
                                invoice_detail[item[j]['type']]['child'] = new Array();
                            }
                            else
                            {
                                for(var T in invoice_detail[item[j]['type']]['child'])
                                {
                                    $A_check = invoice_detail[item[j]['type']]['child'][T];
                                    if($A_check['type']==item[j]['type'] && $A_check['invoice_id']==item[j]['id'])
                                    {
                                        $check = false;
                                    }
                                }
                            }
                            if($check == true)
                            {
                                Input_count_item++;
                                invoice_detail[item[j]['type']]['child'][Input_count_item] = new Array();
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['id'] = '';
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['type'] = item[j]['type'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['key'] = items_js[i]['id'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['invoice_id'] = item[j]['id'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['amount'] = number_format(to_numeric(item[j]['net_amount']));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['max_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['percent'] = to_numeric(item[j]['percent']);
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['max_percent'] = to_numeric(item[j]['percent']);
                                $service_rate = to_numeric(item[j]['service_rate']);
                                $tax_rate = to_numeric(item[j]['tax_rate']);
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['service_amount'] = number_format(to_numeric(item[j]['net_amount'])*($service_rate/100));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['service_rate'] = $service_rate;
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_amount'] = number_format((to_numeric(item[j]['net_amount'])+(to_numeric(item[j]['net_amount'])*($service_rate/100)))*($tax_rate/100));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['tax_rate'] = $tax_rate;
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['date_use'] = item[j]['date'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['total_amount'] = number_format(to_numeric(item[j]['total_amount']));
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['description'] = item[j]['description'];
                                invoice_detail[item[j]['type']]['child'][Input_count_item]['input_count'] = Input_count_item;
                                jQuery("#mice_items_"+items_js[i]['id']+"_"+item[j]['id']).css('background','#DDDDDD');
                            }
                        }
                    }
                }
            }
        }
        //console.log(invoice_detail);
        parselayout();
        return true;
    }
    
    function parselayout()
    {
        var content = '';
        var content_item = '';
        content = '<tr style="text-transform: uppercase; height: 35px;">';
                    content += '<th>[[.description.]] [[.detail.]]</th>';
                    content += '<th>%</th>';
                    content += '<th style="width: 120px;">[[.amount.]]</th>';
                    content += '<th style="width: 20px; text-align: center;"></th>';
                content += '</tr>';
        jQuery("#TableSplit").html(content);
        $check=false;
        for(var i in invoice_detail)
        {
            $check=true;
            group_input_count = invoice_detail[i]['input_count'];
            content = jQuery("#group_template").html().replace(/X######X/g,group_input_count);
            jQuery("#TableSplit").append(content);
            jQuery("#group_id_"+group_input_count).html(invoice_detail[i]['id']);
            var $child = invoice_detail[i]['child'];
            for(var j in $child)
            {
                input_count = $child[j]['input_count'];
                content_item = jQuery("#item_teamplate").html().replace(/X######X/g,group_input_count).replace(/Y######Y/g,input_count);
                jQuery("#TableSplit").append(content_item);
                jQuery("#invoice_in_date_"+input_count).html($child[j]['date_use']);
                jQuery("#invoice_"+input_count+" input").each(function(){
                    var id = this.id.replace('_'+input_count,'').replace('invoice_','');
                    if(jQuery("#"+id) != undefined && $child[j][id] != undefined)
                    {
                        jQuery("#invoice_"+id+"_"+input_count).val($child[j][id]);
                    }
                });
                
            }
        }
        jQuery("#save").css('display','');
        if($check==true)
        {
            jQuery("#PaymentDescription").css('display','');
            jQuery("#PaymentAction").css('display','');
        }
        else
        {
            jQuery("#PaymentDescription").css('display','none');
            jQuery("#PaymentAction").css('display','none');
        }
        gettotalamount();
        return true;
    }
    
    function gettotalamount()
    {
        amount = 0;
        service_amount = 0;
        tax_amount = 0;
        payment = to_numeric(jQuery("#payment").val());
        //console.log(payment);
        balance = 0;
        for(var i in invoice_detail)
        {
            total = 0;
            jQuery(".class_group_"+invoice_detail[i]['input_count']).each(function(){
                jQuery("#"+this.id+" input.amount_payment").each(function(){
                    if(invoice_detail[i]['id']=='DEPOSIT' || invoice_detail[i]['id']=='DEPOSIT_MICE' || invoice_detail[i]['id']=='DISCOUNT')
                    {
                        amount -= to_numeric(this.value);
                    }
                    else
                    {
                        amount += to_numeric(this.value);
                    }
                    total += to_numeric(this.value);
                });
                jQuery("#"+this.id+" input.service_amount_payment").each(function(){
                    if(invoice_detail[i]['id']=='DEPOSIT' || invoice_detail[i]['id']=='DEPOSIT_MICE' || invoice_detail[i]['id']=='DISCOUNT'){
                        //service_amount -= to_numeric(this.value);
                    }
                    else{
                        //console.log(to_numeric(this.value));
                        service_amount += to_numeric(this.value);
                    }
                        
                });
                jQuery("#"+this.id+" input.tax_amount_payment").each(function(){
                    if(invoice_detail[i]['id']=='DEPOSIT' || invoice_detail[i]['id']=='DEPOSIT_MICE' || invoice_detail[i]['id']=='DISCOUNT')
                    {    
                        //tax_amount -= to_numeric(this.value);
                    }
                    else{
                       tax_amount += to_numeric(this.value); 
                    }
                        
                });
            });
            console.log(total);
            jQuery("#group_amount_"+invoice_detail[i]['input_count']).html(number_format(total));
        }
        jQuery("#total_before_tax").val(number_format(amount));
        jQuery("#service_amount").val(number_format(service_amount));
        jQuery("#tax_amount").val(number_format(tax_amount));
        var $extra_vat = to_numeric(jQuery('#extra_vat').val());
        jQuery("#total_amount").val(number_format(amount+$extra_vat));
        balance = amount+$extra_vat-payment;
        jQuery("#balance").val(number_format(balance));
        if(balance>0)
        {
            jQuery("#refunr_button").css('display','none');
            jQuery("#payment_button").css('display','');
        }
        else if(balance<0)
        {
            jQuery("#refunr_button").css('display','');
            jQuery("#payment_button").css('display','none');
        }
        else
        {
            jQuery("#refunr_button").css('display','none');
            jQuery("#payment_button").css('display','');
        }
    }
    
    function removeitem(group_index,item_index)
    {
        invoice_detail_teamplate = new Array();
        for(var i in invoice_detail)
        {
            if(invoice_detail[i]['input_count']==group_index)
            {
                if(item_index!='ALL')
                {
                    $check = false;
                    for(var j in invoice_detail[i]['child'])
                    {
                        if(invoice_detail[i]['child'][j]['input_count']!=item_index)
                        {
                            $check = true;
                        }
                    }
                    if($check==true)
                    {
                        invoice_detail_teamplate[i] = new Array();
                        invoice_detail_teamplate[i]['child'] = new Array();
                        invoice_detail_teamplate[i]['id'] = invoice_detail[i]['id'];
                        invoice_detail_teamplate[i]['input_count'] = invoice_detail[i]['input_count'];
                        for(var j in invoice_detail[i]['child'])
                        {
                            if(invoice_detail[i]['child'][j]['input_count']!=item_index)
                            {
                                invoice_detail_teamplate[i]['child'][j] = new Array();
                                invoice_detail_teamplate[i]['child'][j] = invoice_detail[i]['child'][j];
                            }
                        }
                    }
                }
            }
            else
            {
                invoice_detail_teamplate[i] = new Array();
                invoice_detail_teamplate[i] = invoice_detail[i];
            }
        }
        invoice_detail = invoice_detail_teamplate;
        //console.log(invoice_detail);
        parselayout()
    }
    function ChangePrice(key,index)
    {
        $in_key = jQuery("#invoice_key_"+index).val();
        $type = jQuery("#invoice_type_"+index).val();
        $invoice_id = jQuery("#invoice_invoice_id_"+index).val();
        
        $max_percent = to_numeric(items_js[$in_key]["item"][$type+"_"+$invoice_id]["percent"]);
        //$max_percent = 100;
        $max_amount = to_numeric(items_js[$in_key]["item"][$type+"_"+$invoice_id]["real_amount"]);
        $percent = to_numeric(jQuery("#invoice_percent_"+index).val());
        $amount = to_numeric(jQuery("#invoice_amount_"+index).val());
        //$max_amount = to_numeric(jQuery("#invoice_max_amount_"+index).val());
        
        //console.log($max_percent);
        if( ($percent<0) || ($percent>$max_percent) || ($amount<0) || ($amount>to_numeric(jQuery("#invoice_max_amount_"+index).val())) )
        {
            alert('+ khong duoc nhap % nho hon 0 hoac lon hon '+$max_percent+'\n+ khong duoc nhap so tien nho hon 0 hoac lon hon '+jQuery("#invoice_max_amount_"+index).val());
            if($percent<0)
                $percent = 0;
            else if($percent>$max_percent)
                $percent = $max_percent;
            if($amount<0)
                $amount = 0;
            else if($amount>to_numeric(jQuery("#invoice_max_amount_"+index).val()))
                $amount = to_numeric(jQuery("#invoice_max_amount_"+index).val());
        }
        $service_rate = to_numeric(items_js[$in_key]["item"][$type+"_"+$invoice_id]["service_rate"]);
        $tax_rate = to_numeric(items_js[$in_key]["item"][$type+"_"+$invoice_id]["tax_rate"]);
        //console.log($max_percent);
        if(key=='percent')
        {
            $amount = ($percent * $max_amount)/100;
            jQuery("#invoice_percent_"+index).val(to_numeric(number_format($percent)));
            jQuery("#invoice_amount_"+index).val(number_format($amount));
            $service_amount = $amount*$service_rate/100;
            jQuery("#invoice_service_amount_"+index).val(number_format($service_amount));
            $tax_amount = ($amount+($amount*$service_rate/100))*$tax_rate/100;
            jQuery("#invoice_tax_amount_"+index).val(number_format($tax_amount));
            jQuery("#invoice_total_amount_"+index).val(number_format(($amount*($service_rate+100)/100)*(100+$tax_rate)/100));
        }
        else
        {
            jQuery("#invoice_amount_"+index).val(number_format($amount));
            $percent = ($amount*100)/$max_amount;
            jQuery("#invoice_percent_"+index).val(to_numeric(number_format($percent)));
        }
        gettotalamount()
    }
    function OpenMiceInvoice($value){
        if($value!=''){
            window.location.href='?page=mice_reservation&cmd=invoice&id=<?php echo Url::get('id') ?>&invoice_id='+$value;
        }
    }
</script>
