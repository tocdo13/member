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
            <strong><?php echo Portal::language('template_code');?></strong>
            <br />
            <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
        </td>
    </tr>
</table>
<table width="90%" style="margin: 0 auto;">
    <tr>
        <td>
            <h2 style="text-align: center;text-transform: uppercase;"><?php echo Portal::language('booking_confirmed_report');?></h2>
            <form id="SearchForm" method="post">
                <fieldset style="border: 1px solid #333;">
                    <legend><?php echo Portal::language('search');?></legend>
                    <table style="margin: 0 auto;">
                        <tr>
                            <td><?php echo Portal::language('recode');?></td>
                            <td><input  name="reservation_id" id="reservation_id" style="width: 50px; height: 20px;" / type ="text" value="<?php echo String::html_normalize(URL::get('reservation_id'));?>"></td>
                            <td><?php echo Portal::language('customer_new');?></td>
                            <td>
                                <div class="multiselect_customer">
                                    <div style="width: 80px;" class="selectBox_customer" onclick="showCheckboxes('customer');">
                                      <select style="width: 80px;">
                                        <option></option>
                                      </select>
                                      <div class="overSelect_customer"></div>
                                    </div> 
                                    <?php echo $this->map['list_customer'];?>
                                    <input  name="customer_ids" id="customer_ids" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_ids'));?>">
                                    <input  name="customer_id_" id="customer_id_" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id_'));?>">
                                </div>     
                            </td>   
                            <td><?php echo Portal::language('from_date');?></td>
                            <td><input  name="from_date" id="from_date" style="width: 70px; height: 20px"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                            <td><?php echo Portal::language('to_date');?></td>
                            <td><input  name="to_date" id="to_date" style="width: 70px; height: 20px"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                            <td><?php echo Portal::language('user');?></td>
                            <td><input  name="user_id" id="user_id" style="width: 100px; height: 20px;" / type ="text" value="<?php echo String::html_normalize(URL::get('user_id'));?>"></td>
                            <td><input name="seach" type="submit" id="search" value="<?php echo Portal::language('view_report');?>" style="width: 87px; height: 23px;"/></td>
                            <td><input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 70px; height: 23px"/></td>
                        </tr>
                    </table>
                </fieldset>
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        </td>
    </tr>
</table>
<br />
<?php  if(empty($this->map['items'])){ ?>
    <table style="margin: 0 auto;">
        <tr>
            <td style="text-align: center;"><strong><?php echo Portal::language('no_record');?></strong></td>
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
                <th rowspan="3" width="5px"><?php echo Portal::language('stt');?></th>
                <th rowspan="3" width="30px"><?php echo Portal::language('recode');?></th>
                <th rowspan="3" width="120px"><?php echo Portal::language('customer_name');?></th>
                <th rowspan="3" width="20px">CI</th>
                <th rowspan="3" width="60px">CO</th>
                <th rowspan="3" width="60px"><?php echo Portal::language('room_quantity');?></th>
                <th rowspan="3" width="5px"><?php echo Portal::language('total_amount1');?></th>
                <th rowspan="3" width="100px"><?php echo Portal::language('dating_deposit');?><br />(<?php echo Portal::language('cut_of_date');?>)</th>
                <th colspan="6" width="120px"><?php echo Portal::language('deposit');?></th>
                <th rowspan="3" width="120px"><?php echo Portal::language('deposit_date');?></th>
                <th rowspan="3" width="120px"><?php echo Portal::language('deposit_total');?></th>
                <th rowspan="3" width="120px"><?php echo Portal::language('remain1');?></th>
                <th rowspan="3" width="120px"><?php echo Portal::language('note');?></th>
                <th rowspan="3" width="120px"><?php echo Portal::language('user');?></th>
            </tr>
            <tr>
                <th colspan="2"><?php echo Portal::language('cash');?></th>
                <th colspan="2"><?php echo Portal::language('credit_card');?></th>
                <th colspan="2"><?php echo Portal::language('transfer');?></th>
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
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <?php $k =1;$j=1?>
            <tr>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: center;"><?php echo $i++; ?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: center;"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['id'];?></a></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: left;"><?php echo $this->map['items']['current']['customer_name'];?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: center;"><?php echo date('d/m/Y',$this->map['items']['current']['time_in']); ?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: center;"><?php echo date('d/m/Y',$this->map['items']['current']['time_out']); ?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: center;"><?php echo sizeof($this->map['items']['current']['rr_array']); ?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total_amount']);$total_amount+=$this->map['items']['current']['total_amount'];?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: center;"><?php echo $this->map['items']['current']['cut_of_date'];?></td>
                <?php if(isset($this->map['items']['current']['child_deposit'])){?>
                    <?php if(isset($this->map['items']['current']['child_deposit']) and is_array($this->map['items']['current']['child_deposit'])){ foreach($this->map['items']['current']['child_deposit'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child_deposit']['current'] = &$item2;?>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CASH' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='VND'){?><?php echo System::display_number($this->map['items']['current']['child_deposit']['current']['amount']);$total_cash_vnd += $this->map['items']['current']['child_deposit']['current']['amount']; ?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CASH' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='USD'){?><?php echo $this->map['items']['current']['child_deposit']['current']['amount'];$total_cash_usd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CREDIT_CARD' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='VND'){?><?php echo System::display_number($this->map['items']['current']['child_deposit']['current']['amount']);$total_credit_card_vnd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CREDIT_CARD' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='USD'){?><?php echo $this->map['items']['current']['child_deposit']['current']['amount'];$total_credit_card_usd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='BANK' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='VND'){?><?php echo System::display_number($this->map['items']['current']['child_deposit']['current']['amount']);$total_bank_vnd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;" class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='BANK' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='USD'){?><?php echo $this->map['items']['current']['child_deposit']['current']['amount'];$total_bank_usd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['child_deposit']['current']['date_deposit'];?></td>
                        <?php break;?>
                    <?php }}unset($this->map['items']['current']['child_deposit']['current']);} ?>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['total_deposit']);$total_deposit+=$this->map['items']['current']['total_deposit'];?></td>
                <td rowspan="<?php echo (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: right;padding-right: 3px;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['total_amount']-$this->map['items']['current']['total_deposit']);$total_remain += $this->map['items']['current']['total_amount']-$this->map['items']['current']['total_deposit'];?></td>
                    <?php if(isset($this->map['items']['current']['child_deposit']) and is_array($this->map['items']['current']['child_deposit'])){ foreach($this->map['items']['current']['child_deposit'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child_deposit']['current'] = &$item3;?>
                        <td style="text-align: left;"><?php echo $this->map['items']['current']['child_deposit']['current']['description'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['child_deposit']['current']['user_id'];?></td>
                        </tr>
                        <?php break;?>
                    <?php }}unset($this->map['items']['current']['child_deposit']['current']);} ?>
                    <?php if(isset($this->map['items']['current']['child_deposit']) and is_array($this->map['items']['current']['child_deposit'])){ foreach($this->map['items']['current']['child_deposit'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['child_deposit']['current'] = &$item4;?>
                        <?php if($k==1){$k++;}else{?>
                        <tr>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CASH' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='VND'){?><?php echo System::display_number($this->map['items']['current']['child_deposit']['current']['amount']);$total_cash_vnd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CASH' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='USD'){?><?php echo $this->map['items']['current']['child_deposit']['current']['amount'];$total_cash_usd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CREDIT_CARD' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='VND'){?><?php echo System::display_number($this->map['items']['current']['child_deposit']['current']['amount']);$total_credit_card_vnd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='CREDIT_CARD' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='USD'){?><?php echo $this->map['items']['current']['child_deposit']['current']['amount'];$total_credit_card_usd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='BANK' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='VND'){?><?php echo System::display_number($this->map['items']['current']['child_deposit']['current']['amount']);$total_bank_vnd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: right;padding-right: 3px;"class="change_numTr"><?php if($this->map['items']['current']['child_deposit']['current']['payment_type_id']=='BANK' and $this->map['items']['current']['child_deposit']['current']['currency_id']=='USD'){?><?php echo $this->map['items']['current']['child_deposit']['current']['amount'];$total_bank_usd += $this->map['items']['current']['child_deposit']['current']['amount'];?><?php }?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['child_deposit']['current']['date_deposit'];?></td>
                        <td style="text-align: left;"><?php echo $this->map['items']['current']['child_deposit']['current']['description'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['child_deposit']['current']['user_id'];?></td>
                        </tr>
                        <?php }?>
                        <?php }}unset($this->map['items']['current']['child_deposit']['current']);} ?> 
                <?php }else{?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td rowspan="<?php (isset($this->map['items']['current']['child_deposit']))?sizeof($this->map['items']['current']['child_deposit']):1;?>" style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total_amount']);$total_remain += $this->map['items']['current']['total_amount'];?></td>
                <td></td>
                <td></td>
                <?php }?>
            <?php }}unset($this->map['items']['current']);} ?>
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
var customer = <?php echo $this->map['customer_js'];?>; 
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