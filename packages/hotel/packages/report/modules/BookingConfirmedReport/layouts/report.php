<style>
.simple-layout-middle{
    width: 100%;
}
#SearchForm{
    width: 850px;
}
input:hover{
    border: 1px dashed #333;
}
select:hover{
    border: 1px dashed #333;    
}
table{
    border-collapse: collapse;  
}
#content tr td tr th{
    line-height: 20px;
    text-align: center;
    font-size: 12px;
}
#content tr td tr td{
    line-height: 25px;
    font-size: 12px;
}
.overSelect,.overSelect_group_customer,.overSelect_customer {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}
.selectBox,.selectBox_group_customer,.selectBox_customer {
  position: relative;
}
#checkboxes,#checkboxes_group_customer,#checkboxes_customer {
  display: none;
  border: 1px #1e90ff solid;
  overflow: auto;    
  padding: 2px 15px 2px 5px;
  position: absolute;
  background: white;  
}
#checkboxes_customer{
    height: 300px;
}
#checkboxes label,#checkboxes_group_customer label,#checkboxes_customer label {
  display: block;
}

#checkboxes label:hover,#checkboxes_group_customer label:hover,#checkboxes_customer label:hover {
  background-color: #1e90ff;
}
@media print{
    #SearchForm{
        display: none;
    }
}
</style>
<table cellspacing="0" width="90%" style="margin: 0 auto;">
    <tr style="font-size:12px; font-weight:normal">
        <td align="left" width="80%">
            <strong><?php echo HOTEL_NAME;?></strong>
            <br />
            <strong><?php echo HOTEL_ADDRESS;?></strong>
            <br />
            <strong>Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></strong>
            <br />
            <strong>Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></strong>
        </td>
        <td align="right" style="padding-right:10px;" >
            <strong>[[.template_code.]]</strong>
            <br />
            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
        </td>
    </tr>
</table>
<table width="90%" style="margin: 0 auto;">
    <tr>
        <td>
            <h2 style="text-align: center;text-transform: uppercase;">[[.booking_confirmed_report.]]</h2>
            <form id="SearchForm" method="post">
                <fieldset style="border: 1px solid #333;">
                    <legend>[[.search.]]</legend>
                    <table style="margin: 0 auto;">
                        <tr>
                            <td>[[.recode.]]</td>
                            <td><input name="reservation_id" type="text" id="reservation_id" style="width: 50px; height: 20px;" /></td>
                            <td>[[.customer_new.]]</td>
                            <td>
                                <div class="multiselect_customer">
                                    <div style="width: 80px;" class="selectBox_customer" onclick="showCheckboxes('customer');">
                                      <select style="width: 80px;">
                                        <option></option>
                                      </select>
                                      <div class="overSelect_customer"></div>
                                    </div> 
                                    [[|list_customer|]]
                                    <input name="customer_ids" type="hidden" id="customer_ids" />
                                    <input name="customer_id_" type="hidden" id="customer_id_" />
                                </div>     
                            </td>   
                            <td>[[.from_date.]]</td>
                            <td><input name="from_date" type="text" id="from_date" style="width: 70px; height: 20px"/></td>
                            <td>[[.to_date.]]</td>
                            <td><input name="to_date" type="text" id="to_date" style="width: 70px; height: 20px"/></td>
                            <td>[[.user.]]</td>
                            <td><input name="user_id" type="text" id="user_id" style="width: 100px; height: 20px;" /></td>
                            <td><input name="seach" type="submit" id="search" value="[[.view_report.]]" style="width: 87px; height: 23px;"/></td>
                            <td><input name="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 70px; height: 23px"/></td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </td>
    </tr>
</table>
<br />
<?php  if(empty($this->map['items'])){ ?>
    <table style="margin: 0 auto;">
        <tr>
            <td style="text-align: center;"><strong>[[.no_record.]]</strong></td>
        </tr>
    </table>
<?php }else{ ?>
<table id="content" width="95%" style="margin: 0 auto;">
    <tr>
        <td><h2 id="title_page" style="text-align: center; display: none;">BÁO CÁO ĐẶT PHÒNG ĐẾN HẠN XÁC NHẬN</h2></td>
    </tr>
    <tr>
        <td>
            <table border="1" width="100%">
            <tr>
                <th rowspan="3" width="5px">[[.stt.]]</th>
                <th rowspan="3" width="30px">[[.recode.]]</th>
                <th rowspan="3" width="120px">[[.customer_name.]]</th>
                <th rowspan="3" width="20px">CI</th>
                <th rowspan="3" width="60px">CO</th>
                <th rowspan="3" width="60px">[[.room_quantity.]]</th>
                <th rowspan="3" width="5px">[[.total_amount1.]]</th>
                <th rowspan="3" width="100px">[[.dating_deposit.]]<br />([[.cut_of_date.]])</th>
                <th colspan="6" width="120px">[[.deposit.]]</th>
                <th rowspan="3" width="120px">[[.deposit_date.]]</th>
                <th rowspan="3" width="120px">[[.deposit_total.]]</th>
                <th rowspan="3" width="120px">[[.remain1.]]</th>
                <th rowspan="3" width="120px">[[.note.]]</th>
                <th rowspan="3" width="120px">[[.user.]]</th>
            </tr>
            <tr>
                <th colspan="2">[[.cash.]]</th>
                <th colspan="2">[[.credit_card.]]</th>
                <th colspan="2">[[.transfer.]]</th>
            </tr>
            <tr>
                <th>VND</th>
                <th>USD</th>
                <th>VND</th>
                <th>USD</th>
                <th>VND</th>
                <th>USD</th>
            </tr>
            <?php 
                $i =1;
                $total_amount = 0;
                $total_cash_vnd = 0;
                $total_cash_usd = 0;
                $total_credit_card_vnd = 0;
                $total_credit_card_usd = 0;
                $total_bank_vnd = 0;
                $total_bank_usd = 0;
                $total_deposit = 0;
                $total_remain = 0;
            ?>
            <!--LIST:items-->
            <?php $k =1;$j=1?>
            <tr>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: center;"><?php echo $i++; ?></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: center;"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.id|]]">[[|items.id|]]</a></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: left;">[[|items.customer_name|]]</td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: center;"><?php echo date('d/m/Y',[[=items.time_in=]]); ?></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: center;"><?php echo date('d/m/Y',[[=items.time_out=]]); ?></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: center;"><?php echo sizeof([[=items.rr_array=]]); ?></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: right;"><?php echo System::display_number([[=items.total_amount=]]);$total_amount+=[[=items.total_amount=]];?></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: center;">[[|items.cut_of_date|]]</td>
                <?php if(isset([[=items.child_deposit=]])){?>
                    <!--LIST:items.child_deposit-->
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CASH' and [[=items.child_deposit.currency_id=]]=='VND'){?><?php echo System::display_number([[=items.child_deposit.amount=]]);$total_cash_vnd += [[=items.child_deposit.amount=]]; ?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CASH' and [[=items.child_deposit.currency_id=]]=='USD'){?><?php echo [[=items.child_deposit.amount=]];$total_cash_usd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CREDIT_CARD' and [[=items.child_deposit.currency_id=]]=='VND'){?><?php echo System::display_number([[=items.child_deposit.amount=]]);$total_credit_card_vnd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CREDIT_CARD' and [[=items.child_deposit.currency_id=]]=='USD'){?><?php echo [[=items.child_deposit.amount=]];$total_credit_card_usd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='BANK' and [[=items.child_deposit.currency_id=]]=='VND'){?><?php echo System::display_number([[=items.child_deposit.amount=]]);$total_bank_vnd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='BANK' and [[=items.child_deposit.currency_id=]]=='USD'){?><?php echo [[=items.child_deposit.amount=]];$total_bank_usd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: center;">[[|items.child_deposit.date_deposit|]]</td>
                        <?php break;?>
                    <!--/LIST:items.child_deposit-->
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo System::display_number([[=items.total_deposit=]]);$total_deposit+=[[=items.total_deposit=]];?></td>
                <td rowspan="<?php echo (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo System::display_number([[=items.total_amount=]]-[[=items.total_deposit=]]);$total_remain += [[=items.total_amount=]]-[[=items.total_deposit=]];?></td>
                    <!--LIST:items.child_deposit-->
                        <td style="text-align: left;">[[|items.child_deposit.description|]]</td>
                        <td style="text-align: center;">[[|items.child_deposit.user_id|]]</td>
                        </tr>
                        <?php break;?>
                    <!--/LIST:items.child_deposit-->
                    <!--LIST:items.child_deposit-->
                        <?php if($k==1){$k++;}else{?>
                        <tr>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CASH' and [[=items.child_deposit.currency_id=]]=='VND'){?><?php echo System::display_number([[=items.child_deposit.amount=]]);$total_cash_vnd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CASH' and [[=items.child_deposit.currency_id=]]=='USD'){?><?php echo [[=items.child_deposit.amount=]];$total_cash_usd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CREDIT_CARD' and [[=items.child_deposit.currency_id=]]=='VND'){?><?php echo System::display_number([[=items.child_deposit.amount=]]);$total_credit_card_vnd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='CREDIT_CARD' and [[=items.child_deposit.currency_id=]]=='USD'){?><?php echo [[=items.child_deposit.amount=]];$total_credit_card_usd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='BANK' and [[=items.child_deposit.currency_id=]]=='VND'){?><?php echo System::display_number([[=items.child_deposit.amount=]]);$total_bank_vnd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if([[=items.child_deposit.payment_type_id=]]=='BANK' and [[=items.child_deposit.currency_id=]]=='USD'){?><?php echo [[=items.child_deposit.amount=]];$total_bank_usd += [[=items.child_deposit.amount=]];?><?php }?></td>
                        <td style="text-align: center;">[[|items.child_deposit.date_deposit|]]</td>
                        <td style="text-align: left;">[[|items.child_deposit.description|]]</td>
                        <td style="text-align: center;">[[|items.child_deposit.user_id|]]</td>
                        </tr>
                        <?php }?>
                        <!--/LIST:items.child_deposit--> 
                <?php }else{?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td rowspan="<?php (isset([[=items.child_deposit=]]))?sizeof([[=items.child_deposit=]]):1;?>" style="text-align: right;"><?php echo System::display_number([[=items.total_amount=]]);$total_remain += [[=items.total_amount=]];?></td>
                <td></td>
                <td></td>
                <?php }?>
            <!--/LIST:items-->
            <tr style="font-weight: bold; font-size: 12px;">
                <td colspan="6" style="text-align: right;"><b>Tổng: </b></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_amount!=0)?System::display_number($total_amount):'';?></td>
                <td></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_cash_vnd!=0)?System::display_number($total_cash_vnd):'';?></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_cash_usd!=0)?System::display_number($total_cash_usd):'';?></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_credit_card_vnd!=0)?System::display_number($total_credit_card_vnd):'';?></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_credit_card_usd!=0)?System::display_number($total_credit_card_usd):'';?></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_bank_vnd!=0)?System::display_number($total_bank_vnd):'';?></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_bank_usd!=0)?System::display_number($total_bank_usd):'';?></td>
                <td></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_deposit!=0)?System::display_number($total_deposit):'';?></td>
                <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo ($total_remain!=0)?System::display_number($total_remain):'';?></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br />
        <br />
        </td>
    </tr>
</table>
<?php }?>
<script>
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();
var customer = [[|customer_js|]]; 
    jQuery(document).ready(
        function()
        {
            for(var i in customer){                
                jQuery('.customer').each(function(){                    
                    if(jQuery('#'+this.id).attr('flag') == customer[i])
                    {
                        jQuery('#'+this.id).attr('checked', true);                                                
                    }
                })
            }
        }
    );
var expanded_customer = false;    
function showCheckboxes(value) { 
      if(value =='customer'){
        var checkboxes_customer = document.getElementById("checkboxes_customer");
          if (!expanded_customer) {
            checkboxes_customer.style.display = "block";
            expanded_customer = true;
          } else {
            checkboxes_customer.style.display = "none";        
            expanded_customer = false;
          }
      }          
    }
jQuery(document).on('click', function(e) {
      var $clicked = jQuery(e.target);
     if (!$clicked.parents().hasClass("multiselect_customer")) jQuery('#checkboxes_customer').hide();
    });
function get_ids(value)
    {           
        var strids = "";
        var str_customer = "";
        var customer_id = ""; 
        if(value=='customer'){
            var inputs = jQuery('.customer:checkbox:checked');            
            for (var i=0;i<inputs.length;i++)
            {  
                if(inputs[i].id.indexOf('customer_')==0)
                {
                    str_customer +=","+"'"+inputs[i].id.replace("customer_","")+"'";
                    customer_id +=","+inputs[i].id.replace("customer_","");                
                }
            }                
            str_customer = str_customer.replace(",","");
            customer_id = customer_id.replace(",","");             
            jQuery('#customer_ids').val(str_customer);
            jQuery('#customer_id_').val(customer_id);             
        }                
    }
jQuery("#export_excel").click(function () {
    jQuery('.change_numTr').each(function(){
           if(jQuery(this).html()!='')
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
    jQuery('#title_page').css('display', 'block');
    jQuery("#content").battatech_excelexport({
        containerid: "content"
       , datatype: 'table'
    });
});
</script>