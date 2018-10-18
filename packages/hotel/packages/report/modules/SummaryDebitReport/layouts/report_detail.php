<form name="SummaryDebitReportForm" method="POST">
    <input type="hidden" name="act" id="act" />
    <table cellpadding="5" style="width: 100%;">
        <tr>
            <td style="width: 100px;">
                <img src="<?php echo HOTEL_LOGO;?>" style="width: 100px;"/>
            </td>
            <td>
                <b><?php echo HOTEL_NAME;?></b><br />
				ADD: <?php echo HOTEL_ADDRESS;?><br />
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
            </td>
            <td style="text-align: right;">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?><br />
                [[.user_print.]]:<?php echo User::id();?>
            </td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;"><h1 style="text-transform: uppercase;">[[.debit_detail_report.]]</h1></th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;">[[.customer.]]: [[|customer_name|]]</th>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <!--<label>[[.customer.]]</label>
                <select name="customer_id" id="customer_id" style="padding: 5px; max-width: 350px;"></select>-->
                <label>[[.from_date.]]</label>
                <input name="from_date" type="text" id="from_date" style="padding: 5px;" />
                <label>[[.to_date.]]</label>
                <input name="to_date" type="text" id="to_date" style="padding: 5px;" />
                <label>[[.filter.]]</label>
                <select name="find" id="find" style="padding: 5px;" onchange="find_status();"></select>
                <input value="[[.search.]]" type="submit" onclick="return CheckSubmit('SEARCH');" style="padding: 5px;" />
            </td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right; text-transform: uppercase;">[[.beginning_outstanding_debt.]]: <?php echo System::display_number([[=debit_last_period_before=]]); ?></th>
        </tr>
        <tr>
            <th colspan="2">
                <div style="width: 20px; height: 20px; background: #ffa2a2;float: left; margin: 0px; padding: 0px; margin-right: 3px;"></div><p style="float: left; line-height: 20px; margin: 0px; padding: 0px;"> Khoản phát sinh tăng đã được giảm trừ hết</p>
                <div style="width: 20px; height: 20px; background: #99e6ff;float: left; margin: 0px; padding: 0px; margin-right: 3px; margin-left: 20px;"></div><p style="float: left; line-height: 20px; margin: 0px; padding: 0px;"> Khoản phát sinh giảm</p>
            </th>
            <th style="text-align: right; text-transform: uppercase;"><input id="submit" value="[[.save.]]" type="submit" onclick="return CheckSubmit('DEDUCTIBLE');" style="padding: 10px;" /></th>
        </tr>
    </table>
    <table cellpadding="5" border="1" bordercolor="#CCCCCC" style="width: 100%; margin: 10px auto;">
        <tr style="text-align: center; background: #EEEEEE;">
            <th><input type="checkbox" id="check_all" /></th>
            <th>[[.date.]]</th>
            <th>[[.description.]]</th>
            <th>[[.recode.]]</th>
            <th>[[.folio.]]</th>
            <th>[[.bar_reservation_code.]]</th>
            <th>[[.ve_reservation_code.]]</th>
            <th>[[.mice.]]</th>
            <th>[[.mice_invoice_code.]]</th>
            <th>[[.ps_up.]]</th>
            <th>[[.ps_down.]]</th>
            <th style="background: #fafbcb;">[[.ps_up_balance.]]</th>
            <th>[[.user_deductible.]]</th>
            <!--<th>[[.outstanding_debt.]]</th>-->
            <th>[[.deductible.]]</th>
            <th>[[.delete.]]</th>
        </tr>
        <?php $stt=1; $ps_up=0; $ps_down=0; $up_balance = 0; $outstanding_debt_last = [[=debit_last_period_before=]]; ?>
        <!--LIST:items-->
        <tr id="tr_[[|items.id|]]" style="text-align: center; <!--IF:cond([[=items.edit=]]==0 && [[=items.up=]]>0 && [[=items.down=]]==0)-->  background: #ffa2a2; <!--ELSE--> <!--IF:conds([[=items.down=]]>0 && [[=items.up=]]==0)--> background: #99e6ff; <!--/IF:conds--> <!--/IF:cond-->" class="<!--IF:conds([[=items.down=]]>0 && [[=items.up=]]==0)--> PSGitems <!--ELSE--> PSTitems <!--/IF:conds-->">
            <td>
                <!--IF:cond([[=items.edit=]]==1 && [[=items.down=]]==0 && [[=items.up=]]>0)-->
                    <input type="checkbox" class="check_items" name="up[[[|items.id|]]][id]" id="id_[[|items.id|]]" value="[[|items.id|]]" lang="[[|items.id|]]" onclick="CheckDeductible(this);" /> 
                <!--ELSE-->
                    <!--IF:cond2([[=items.edit=]]==1 && [[=items.down=]]>0 && [[=items.up=]]==0)-->
                    <input type="checkbox" class="check_itemsdown" name="down[[[|items.id|]]][id]" id="downid_[[|items.id|]]" value="[[|items.id|]]" lang="[[|items.id|]]" onclick="CheckDownDeductible(this);" />
                    <!--/IF:cond2-->
                <!--/IF:cond-->
            </td>
            <td>[[|items.in_date|]]</td>
            <td>[[|items.description|]]</td>
            <td><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]">[[|items.reservation_id|]]</a></td>
            <td><a target="_blank" href="[[|items.link_folio|]]">[[|items.folio_number|]]</a></td>
            <td><a target="_blank" href="[[|items.link_bar|]]">[[|items.bar_reservation_code|]]</a></td>
            <td><a target="_blank" href="[[|items.link_vend|]]">[[|items.ve_reservation_code|]]</a></td>
            <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.mice_reservation_id|]]">[[|items.mice_reservation_id|]]</a></td>
            <td><a target="_blank" href="[[|items.link_mice|]]">[[|items.mice_invoice_code|]]</a></td>
            <td style="text-align: right;"><?php echo System::display_number([[=items.up=]]); $ps_up+=[[=items.up=]]; $outstanding_debt_last+=[[=items.up=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number([[=items.down=]]); $ps_down+=[[=items.down=]]; $outstanding_debt_last-=[[=items.down=]]; ?></td>
            <td style="text-align: right; background: #fafbcb;"><?php echo System::display_number([[=items.up_balance=]]); $up_balance+=[[=items.up_balance=]];  ?></td>
            <td>[[|items.user_id|]]</td>
            <!--<td style="text-align: right;"><?php echo System::display_number($outstanding_debt_last); ?></td>-->
            <td>
                <!--IF:cond([[=items.edit=]]==1 && [[=items.down=]]==0 && [[=items.up=]]>0)--> 
                    <input type="hidden" id="up_balance_[[|items.id|]]" value="<?php echo System::display_number([[=items.up_balance=]]); ?>" />
                    <input type="hidden" name="up[[[|items.id|]]][invoice_date]" id="invoice_date_[[|items.id|]]" value="[[|items.in_date|]]" />
                    <input type="hidden" name="up[[[|items.id|]]][folio_id]" id="folio_id_[[|items.id|]]" value="[[|items.folio_id|]]" />
                    <input type="hidden" name="up[[[|items.id|]]][bar_reservation_id]" id="bar_reservation_id_[[|items.id|]]" value="[[|items.bar_reservation_id|]]" />
                    <input type="hidden" name="up[[[|items.id|]]][ve_reservation_id]" id="ve_reservation_id_[[|items.id|]]" value="[[|items.ve_reservation_id|]]" />
                    <input type="hidden" name="up[[[|items.id|]]][mice_invoice_id]" id="mice_invoice_id_[[|items.id|]]" value="[[|items.mice_invoice_id|]]" />  
                    
                    <input type="text" name="up[[[|items.id|]]][description]" id="description_[[|items.id|]]" value="" style="display: none; width: 100%; padding: 3px;" placeholder="[[.description.]]" />
                    <select id="payment_type_id_[[|items.id|]]" name="up[[[|items.id|]]][payment_type_id]" style="display: none; padding: 3px;">[[|payment_type_option|]]</select>
                    <input type="text" name="up[[[|items.id|]]][price]" id="price_[[|items.id|]]" value="<?php echo System::display_number([[=items.up_balance=]]); ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));if(to_numeric(jQuery(this).val())>[[|items.up_balance|]]){alert('Không được nhập số tiền lớn hơn số tiền PST chưa giảm trừ!');jQuery(this).val(number_format([[|items.up_balance|]]));}if(to_numeric(jQuery(this).val())<=0){alert('Không được nhập số tiền nhỏ hơn hoặc bằng 0!');jQuery(this).val(number_format([[|items.up_balance|]]));}" style="display: none; width: 100px; padding: 3px; text-align: right;" />
                <!--ELSE-->
                    <!--IF:cond2([[=items.edit=]]==1 && [[=items.down=]]>0 && [[=items.up=]]==0)-->
                        <input type="hidden" id="down_balance_[[|items.id|]]" value="<?php echo System::display_number([[=items.down=]]); ?>" />
                        <input type="hidden" name="down[[[|items.id|]]][invoice_date]" id="downinvoice_date_[[|items.id|]]" value="[[|items.in_date|]]" />
                        <input type="hidden" name="down[[[|items.id|]]][folio_id]" id="downfolio_id_[[|items.id|]]" value="[[|items.folio_id|]]" />
                        <input type="hidden" name="down[[[|items.id|]]][bar_reservation_id]" id="downbar_reservation_id_[[|items.id|]]" value="[[|items.bar_reservation_id|]]" />
                        <input type="hidden" name="down[[[|items.id|]]][ve_reservation_id]" id="downve_reservation_id_[[|items.id|]]" value="[[|items.ve_reservation_id|]]" />
                        <input type="hidden" name="down[[[|items.id|]]][mice_invoice_id]" id="downmice_invoice_id_[[|items.id|]]" value="[[|items.mice_invoice_id|]]" />  
                        
                        <input type="text" name="down[[[|items.id|]]][description]" id="downdescription_[[|items.id|]]" value="[[|items.description|]]" style="display: none; width: 100%; padding: 3px;" placeholder="[[.description.]]" />
                        <select id="downpayment_type_id_[[|items.id|]]" name="down[[[|items.id|]]][payment_type_id]" style="display: none; padding: 3px;">[[|payment_type_option|]]</select>
                        <script>
                            jQuery("#downpayment_type_id_[[|items.id|]]").val('[[|items.payment_type_id|]]');
                        </script>
                        <input type="text" name="down[[[|items.id|]]][price]" id="downprice_[[|items.id|]]" value="<?php echo System::display_number([[=items.down=]]); ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));if(to_numeric(jQuery(this).val())<=0){alert('Không được nhập số tiền nhỏ hơn hoặc bằng 0!');jQuery(this).val(number_format([[|items.down|]]));}" style="display: none; width: 100px; padding: 3px; text-align: right;" />
                    <!--/IF:cond2-->
                <!--/IF:cond-->
            </td>
            <td>
                <!--IF:cond2([[=items.edit=]]==1 && [[=items.down=]]>0 && [[=items.up=]]==0)-->
                    <input type="checkbox" name="down[[[|items.id|]]][delete]" id="downdelete_[[|items.id|]]" value="[[|items.id|]]" style="display: none;"/>
                    <input type="button" onclick="if(confirm('bạn chắc chắn muốn xóa khoản giảm trừ?')){DeleteDownDeductible('[[|items.id|]]');}else{return false;}" value="[[.delete.]]" style="padding: 10px;" />
                <!--/IF:cond2-->
            </td>
        </tr>
        <!--/LIST:items-->
        <tr style="text-align: right; background: #EEEEEE;">
            <th colspan="9">[[.total.]]</th>
            <th><?php echo System::display_number($ps_up); ?></th>
            <th><?php echo System::display_number($ps_down); ?></th>
            <th style="background: #fafbcb;"><?php echo System::display_number($up_balance); ?></th>
            <th></th>
            <!--<th><?php echo System::display_number($outstanding_debt_last); ?></th>-->
            <td colspan="2"></td>
        </tr>
        <table cellpadding="5" style="width: 100%;">
            <tr style="text-align: right;">
                <th style="text-align: right; text-transform: uppercase;">[[.outstanding_debt_last.]]: <?php echo System::display_number($outstanding_debt_last); ?></th>
            </tr>
        </table>
    </table>
</form>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    find_status();
    jQuery("#check_all").click(function() {
        jQuery(".check_items").attr('checked',false);
        jQuery(".check_itemsdown").attr('checked',false);
        if(document.getElementById("check_all").checked==true){
            jQuery(".check_items").attr('checked',true);
            jQuery(".check_itemsdown").attr('checked',true);
        }
        jQuery(".check_items").each(function(){
            CheckDeductible(this);
        });
        jQuery(".check_itemsdown").each(function(){
            CheckDownDeductible(this);
        });
    });
    function find_status() {
        if(jQuery("#find").val()!='') {
            if(jQuery("#find").val()=='PST') {
                jQuery(".PSGitems").css('display','none');
                jQuery(".PSTitems").css('display','');
                jQuery("#check_all").css('display','');
            }else {
                jQuery(".PSTitems").css('display','none');
                jQuery("#check_all").css('display','none');
                jQuery(".PSGitems").css('display','');
            }
        }else {
            jQuery(".PSGitems").css('display','');
            jQuery(".PSTitems").css('display','');
            jQuery("#check_all").css('display','');
        }
    }
    function CheckDeductible(obj) {
        var id = obj.id;
        var key = obj.value;
        if(document.getElementById(id).checked==true){
            jQuery("#payment_type_id_"+key).css('display','');
            jQuery("#price_"+key).css('display','');
            jQuery("#description_"+key).css('display','');
        }else {
            jQuery("#payment_type_id_"+key).css('display','none');
            jQuery("#price_"+key).css('display','none');
            jQuery("#description_"+key).css('display','none');
        }
    }
    function CheckDownDeductible(obj) {
        var id = obj.id;
        var key = obj.value;
        if(document.getElementById(id).checked==true){
            jQuery("#downpayment_type_id_"+key).css('display','');
            jQuery("#downprice_"+key).css('display','');
            jQuery("#downdescription_"+key).css('display','');
        }else {
            jQuery("#downpayment_type_id_"+key).css('display','none');
            jQuery("#downprice_"+key).css('display','none');
            jQuery("#downdescription_"+key).css('display','none');
        }
    }
    function DeleteDownDeductible($id) {
        jQuery("#downdelete_"+$id).attr('checked',true);
        jQuery("#downid_"+$id).attr('checked',true);
        jQuery("#tr_"+$id).css('display','none');
        
    }
    function CheckSubmit(act) {
        jQuery("#act").val(act);
        jQuery('#submit').hide();
        SummaryDebitReportForm.submit();
    }
</script>