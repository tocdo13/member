<span style="display:none">
	<span id="mi_payment_sample">
		<div id="input_group_#xxxx#" style="position: relative;">
            <span class="multi-input">
				<span style="width:22px;float:left;"><input  type="checkbox" lang="#xxxx#" id="_checked_#xxxx#" class="checked" tabindex="-1" onclick="fun_check_deposit('#xxxx#',1);" /></span>
			</span>
			<span class="multi-input" style="display:none;">
				<span><input  name="mi_payment[#xxxx#][id]" type="text" id="id_#xxxx#" style="width:30px;text-align:right;background-color:#EFEFEF;border:0px; display:none;" value="(auto)" tabindex="-1"></span>
			</span>
            <span class="multi-input" style="display:none;">
				<span><input  name="mi_payment[#xxxx#][type]" type="text" id="type_#xxxx#" style="width:30px;text-align:right;background-color:#EFEFEF;border:0px; display:none;" value="(auto)" tabindex="-1"></span>
			</span>
            <span class="multi-input" style="display:none;">
				<span><input  name="mi_payment[#xxxx#][type_dps]" type="text" id="type_dps_#xxxx#" style="width:30px;text-align:right;background-color:#EFEFEF;border:0px; display:none;" value="" tabindex="-1"></span>
			</span>
			<span class="multi-input">
				<input class="w3-light-gray" name="mi_payment[#xxxx#][time]" style="width:95px;" type="text" lang="#xxxx#" id="time_#xxxx#" value="<?php echo date('H:i\' d/m/Y');?>" readonly="" class="readonly" tabindex="-1">   
			</span>
            <span class="multi-input">
				<input  name="mi_payment[#xxxx#][amount]" style="width:75px;text-align:right;font-weight:bold;color:#0066CC;" type="text" id="amount_#xxxx#" lang="#xxxx#"  tabindex="4" onchange="UpdateBalance('#xxxx#');" class="input_number" onkeyup="updateBankFee($('credit_card_id_#xxxx#').value,'#xxxx#');UpdateBalance('#xxxx#');">
			</span>
            <span class="multi-input">
				<select  name="mi_payment[#xxxx#][currency_id]" style="width:85px;" id="currency_id_#xxxx#" lang="#xxxx#" tabindex="3" onchange="UpdateAmount('#xxxx#');UpdateBalance('#xxxx#');">[[|currency_options|]]</select>
			</span>
            <span class="multi-input">
				<select  name="mi_payment[#xxxx#][payment_type_id]" style="width:90px;" id="payment_type_id_#xxxx#" lang="#xxxx#" tabindex="3" onchange="selectPaymentType(this.value,#xxxx#,$('amount_#xxxx#').value);" class="payment_type">[[|payment_type_options|]]</select>
			</span>
            <span class="multi-input">
				<select  name="mi_payment[#xxxx#][credit_card_id]" style="width:75px;" id="credit_card_id_#xxxx#" class="credit_card_id" lang="#xxxx#" tabindex="4" onchange="updateBankFee(this.value,'#xxxx#');">[[|credit_card_options|]]</select>
			</span>
            <span class="multi-input">
				<input  name="mi_payment[#xxxx#][bank_acc]" id="bank_acc_#xxxx#" lang="#xxxx#" tabindex="4" style="width:120px;" />
			</span>
            <span class="multi-input">
				<input  name="mi_payment[#xxxx#][description]" style="width:200px;" lang="#xxxx#" type="text" id="description_#xxxx#" tabindex="1">
			</span>  		
            <span class="multi-input" style="display: none;">
				<input  name="mi_payment[#xxxx#][bank_fee]" style="width:76px;text-align:right;font-weight:bold;color:#0066CC;" type="text" id="bank_fee_#xxxx#" lang="#xxxx#"  tabindex="4" class="input_number amount_bank_fee" readonly="readonly">
			</span>
            <span class="multi-input">
				<input  name="mi_payment[#xxxx#][paid]" type="checkbox" id="paid_#xxxx#" lang="#xxxx#" style="width:48px;" readonly="readonly" onclick="fun_check_deposit('#xxxx#',2);" />
			</span>
            <span class="multi-input" style="display: none;">
				<input  name="mi_payment[#xxxx#][payment_point]" type="checkbox" id="payment_point_#xxxx#" lang="#xxxx#" style="width:35px;" onclick="fun_check_deposit('#xxxx#',3);fun_check_payment_point(#xxxx#);" />
			</span>
			<input  name="mi_payment[#xxxx#][exchange_rate]" type="hidden" id="exchange_rate_#xxxx#" lang="#xxxx#" style="width:100px;" class="hidden"  tabindex="-1">
			<span class="multi-input"><span style="width:20px;">
				<a href="#" onClick="$('amount_#xxxx#').value = 0;UpdateBalance(false);mi_delete_row($('input_group_#xxxx#'),'mi_payment','#xxxx#','');event.returnValue=false;" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
			</span></span><br clear="all"/>
            <div id="mask_light_#xxxx#" style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; background: rgba(0,0,0,0.3); display: none;"></div>
		</div>
	</span>
</span>
<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('payment'));?>
<form name="EditPaymentForm" method="post" >
<div align="center" style="min-width:800px;padding:5px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x">
<table cellspacing="0" cellpadding="5" width="99%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr height="40">
                    <td class="form-title payment" width="99%"><?php if(Url::get('cmd')=='deposit'){?>[[.deposit_for.]] [[|obj_payment|]]<?php }else{?>[[.payment.]] [[.for.]]: <?php echo System::display_number([[=total_amount=]]).' '.HOTEL_CURRENCY; }?></td>
                    <?php 
						if(Url::get('reservation'))
                        {
							$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=reservation';
							$her .= ((Url::get('act')=='group_folio')?'&cmd=group_folio':'&cmd=create_folio');
							$her .= ((Url::get('act')=='create_folio')?('&rr_id='.Url::get('id').'&id='.Url::get('id')):('&id='.Url::get('id'))); 
							//$her .= ((Url::get('folio_id'))?('&folio_id='.Url::get('folio_id')):'');
							$her .= ((Url::get('traveller_id'))?('&traveller_id='.Url::get('traveller_id')):''); 
							$her .= ((Url::get('customer_id'))?('&customer_id='.Url::get('customer_id')):'');
						}
                        else
                        {
							$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id='.BLOCK_CREATE_FOLIO;
							$her .= ((Url::get('act')=='group_folio')?'&cmd=group_folio':'&cmd=create_folio');
							$her .= ((Url::get('act')=='create_folio')?('&rr_id='.Url::get('id')):('&id='.Url::get('id'))); 
							//$her .= ((Url::get('folio_id'))?('&folio_id='.Url::get('folio_id')):'');
							$her .= ((Url::get('traveller_id'))?('&traveller_id='.Url::get('traveller_id')):''); 
							$her .= ((Url::get('customer_id'))?('&customer_id='.Url::get('customer_id')):''); 
						}
					?>
                    <?php //echo $her;?>
                    <?php if(User::can_add(false,ANY_CATEGORY) && (Url::get('act')=='create_folio' || Url::get('act')=='group_folio')){?>
                    <td width="1%"><a href="<?php echo $her;?>&portal_id=<?php echo PORTAL_ID;?>" style="margin-right: 5px; text-transform: uppercase; text-decoration: none;" class="w3-btn w3-green">[[.back.]]</a></td>
                    <?php }?>
                    <?php if(User::can_add(false,ANY_CATEGORY) && Url::get('cmd')=='deposit'){?><td width="1%"><a href="javascript:void(0)" onclick="checkPrint();" class="w3-btn w3-lime" style="margin-right: 5px; text-transform: uppercase; text-decoration: none;">[[.print.]]</a></td><?php }?>
					<?php if(!isset($_SESSION['check_payment'])){ ?>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="checkSubmit('save');"  class="w3-btn w3-blue" style="margin-right: 5px; text-transform: uppercase; text-decoration: none;">[[.Save_and_stay.]]</a></td><?php }?>
                    <?php if(User::can_add(false,ANY_CATEGORY) and !Url::get('fast')){ ?><td width="1%"><a href="javascript:void(0)" onclick="checkSubmit('save_stay');"  class="w3-btn w3-orange" style="margin-right: 5px; text-transform: uppercase; text-decoration: none;">[[.Save_and_close.]]</a></td><?php }?>
                    <?php if((Url::get('type')=='RESERVATION' or Url::get('type')=='BILL_MICE') and Url::get('cmd')=='payment'){ if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="checkSubmit('save_and_view_folio');"  class="w3-btn w3-gray" style="margin-right: 5px; text-transform: uppercase; text-decoration: none;">[[.save_view.]]</a></td><?php }?>
                    <?php }} ?>
                </tr>
            </table>
            <div>
            <?php
            if(isset($_SESSION['check_payment'])){
             ?>
             <div style="text-align: center;color:red;font-size:15px;padding:5px;">[[.show_check_payment.]]</div>
             <?php   
            }
             ?>
            </div>
		</td>
	</tr>  
	<tr>
	<td>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table width="98%" cellpadding="5" cellspacing="0" border="2" bordercolor="#FFCCCC" style="margin:auto;">
    <tr style="display: none;">
        <td><?php if(SETTING_POINT==1){ ?> [[.member_code.]]: <?php } ?><input name="member_code" type="text" id="member_code" style="border: none;" readonly="" /></td>
    </tr>
	<?php if(Form::$current->is_error())
	{
	?><tr valign="top">
	<td><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><tr valign="top">
		<td align="center">
		<div>
				<span id="mi_payment_all_elems">
					<span style="white-space:nowrap; float: left;">
						<span><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_payment',this.checked);"/></span></span>
						<span style="display:none;"><span style="width:20px; display:none;">ID</span></span>
						<span style="padding-right:47px; padding-left: 5px;"><span style="width:95px;">[[.pmt_time.]]</span></span>
                        <span style="padding-right:35px;"><span style="width:75px;">[[.amount_paid.]]</span></span>
                        <span style="padding-right:40px;"><span style="width:85px;">[[.currency.]]</span></span>
                        <span style="padding-right:10px;"><span style="width:90px;">[[.payment_type.]]</span></span>
                        <span style="padding-right:30px;" id="header_credit_type"><span style="width:75px;" >[[.credit_type.]]</span></span>
                        <span style="padding-right:20px;"><span style="width:120px;" id="bank_title">[[.bank_account.]]/[[.card_number.]]</span></span>
                        <span style="padding-right:155px;"><span style="width:25px;">[[.description.]]</span></span>                        
                        <span style="padding-right:10px;"><span style="width:50px;">[[.paid.]]</span></span>
                        <span style="display: none;"><span style="width:50px;">[[.point.]]</span></span>
						<span><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
					</span><br clear="all"/>
				</span>
				<div style="float:left;padding:5px 0px 5px 85px;font-weight:bold;" id="balance_div">[[.balance.]]: <span id="balance" style="color:#FF0000;"><?php echo System::display_number([[=total_amount=]]-[[=total_paid=]]);?></span><?php echo ' '.HOTEL_CURRENCY;?></div><br clear="all">
		</div>
		<div style="float:left; padding-left: 6px;"><input type="button" value="[[.add.]]" class="w3-btn w3-cyan w3-text-white" style="text-decoration: none; text-transform: uppercase;" onclick="mi_add_new_row('mi_payment');jQuery('#amount_'+input_count).ForceNumericOnly();jQuery('#amount_'+input_count).FormatNumber();$('exchange_rate_'+input_count).value = [[|default_exchange_rate|]];UpdatePaymentType(jQuery('#payment_type_id_'+input_count).val(),input_count);"></div>
		</td>
	</tr>
	</table>
    <input name="action" id="action" value="" type="hidden" />
    <input name="confirm_edit" type="hidden" value="1" />
    <input name="total_amount" type="hidden" id="total_amount" value="[[|total_amount|]]">
	</td>
</tr>
<tr>
	<td style="font-size:12px;"><b>[[.payment_note.]]:</b><br/>- [[.payment_note_1.]]<br/>- [[.payment_note_2.]]<br/>- [[.payment_note_3.]]
    	
    </td>
</tr>
</table>
</div>
</form>
<script>
var count_click = 0;
var hotel_currency = '<?php echo HOTEL_CURRENCY?>';
var used_credit_type = <?php echo ALLOW_CREDIT_CARD_TYPE;?>;
if(used_credit_type==0){
	//jQuery('.credit_card_id').css('display','none');
	//jQuery('#header_credit_type,#total_with_bank_fee').css('display','none');
	jQuery('.amount_bank_fee').css('display','none');	
}
var cmd = '<?php echo (Url::get('cmd')?Url::get('cmd'):'');?>';
if(cmd=='deposit'){
	jQuery('#balance_div').css('display','none');	
}
var currencies = [[|currencies|]];
/*mi_init_rows('mi_payment',<?php //if(isset($_REQUEST['mi_payment'])){echo String::array2js($_REQUEST['mi_payment']);}else{echo '[]';}?>);*/
<?php if(isset($_REQUEST['mi_payment']))
{
	echo 'var mi_payment_arr = '.String::array2js($_REQUEST['mi_payment']).';';
	echo 'mi_init_rows(\'mi_payment\',mi_payment_arr);';
}
else
{
	//echo 'mi_add_new_row(\'mi_traveller\',true);';
}
echo 'var cmd=\''.Url::get('cmd').'\';';
?>
//console.log(mi_payment_arr);
for(var j=101;j<=input_count;j++)
{
	jQuery('#amount_'+j).FormatNumber();
    
    if(jQuery('#payment_type_id_'+j).val()=='BANK')
        jQuery("#payment_type_id_"+j+" option:disabled").removeAttr('disabled');
    else{
        jQuery("#payment_type_id_"+j+" option:disabled").removeAttr('disabled');
        jQuery("#payment_type_id_"+j+" option").each(function(){
            <?php if(!User::can_admin($this->get_module_id('PrivilegePaymentBank'),ANY_CATEGORY)){ ?>
            if(this.value=='BANK'){
                jQuery(this).attr('disabled','disabled');
            }
            <?php } ?>
        })
    }
        //jQuery("#payment_type_id_"+j+" option:disabled").removeAttr('disabled');
	UpdatePaymentType(jQuery('#payment_type_id_'+j).val(),j);
    if(jQuery('#type_'+j).val()=='BAR' && jQuery('#type_dps_'+j).val()=='BAR' && cmd=='payment')
    {
        jQuery('#description_'+j).attr('readonly','readonly');
        jQuery('#time_'+j).css('background','#dddddd');
        jQuery('#description_'+j).css('background','#dddddd');
        jQuery('#bank_acc_'+j).css('background','#dddddd');
        
        //console.log(jQuery('#payment_type_id_'+j).val());
        jQuery('#credit_card_id_'+j).css('background','#dddddd');
        //jQuery('#credit_card_id_'+j+" option").css('display','none');
        jQuery('#payment_type_id_'+j+" option").css('display','none');
        jQuery('#payment_type_id_'+j).css('background','#dddddd');
        jQuery('#currency_id_'+j).css('background','#dddddd');
        jQuery('#currency_id_'+j+" option").css('display','none');
        
        jQuery('#amount_'+j).attr('readonly','readonly');
        jQuery('#amount_'+j).css('background','#dddddd');
        jQuery('#bank_fee_'+j).attr('readonly','readonly');
        jQuery('#bank_fee_'+j).css('background','#dddddd');
    }
    <?php if(!User::can_admin($this->get_module_id('PrivilegePaymentBank'),ANY_CATEGORY)){ ?>
    if(document.getElementById('paid_'+j).checked==true && jQuery('#payment_type_id_'+j).val()=='BANK')
        jQuery("#mask_light_"+j).css('display','');
    <?php } ?>        
}
for(var i in mi_payment_arr){
	if(mi_payment_arr[i]['paid']==true){
		for(var j=101;j<=input_count;j++){
			jQuery('#paid_'+j).css('checked','checked');
            jQuery("#mask_light_"+j).css('display','none');
		}		
	}
}
function fun_check_deposit(j,key)
{
    if(jQuery('#type_'+j).val()=='BAR' && jQuery('#type_dps_'+j).val()=='BAR' && cmd=='payment')
    {
        if(key==1)
        {
            if(document.getElementById("_checked_"+j).checked==true)
            {
                document.getElementById("_checked_"+j).checked=false;
            }
            else
            {
                document.getElementById("_checked_"+j).checked=true;
            }
        }
        else if(key==2)
        {
            if(document.getElementById("paid_"+j).checked==true)
            {
                document.getElementById("paid_"+j).checked=false;
            }
            else
            {
                document.getElementById("paid_"+j).checked=true;
            }
        }
        else if(key==3)
        {
            if(document.getElementById("payment_point_"+j).checked==true)
            {
                document.getElementById("payment_point_"+j).checked=false;
            }
            else
            {
                document.getElementById("payment_point_"+j).checked=true;
            }
        }
    }
}
function checkPrint(){
    var index='';
	for(var j=101;j<=input_count;j++){
		if(document.getElementById("_checked_"+j).checked==true){
			if(index==''){        
				index += jQuery('#id_'+j).val();
			}
            else
            {
				index += ','+jQuery('#id_'+j).val();
			}
		}
    }
	if(index==''){
		alert('[[.no_item_selected.]]');
		return false;	
	}else{
		<?php $con = '';
			if(Url::get('traveller_id')){
				$con = '&traveller_id='.Url::get('traveller_id');
			}else if(Url::get('customer_id')){
				$con = '&customer_id='.Url::get('customer_id');
			}
			if(Url::get('folio_id')){
				$con .= '&folio_id='.Url::get('folio_id');
			}
			if(Url::get('id')){
				$con .= '&id='.Url::get('id');
			}
			if(Url::get('type')){
				$con .= '&type='.Url::get('type');
			}
		?>
		var trave = '<?php echo $con;?>';
		window.open('http://<?php echo $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'';?>?page=payment&cmd=print<?php echo $con;?>&index='+index);	   
	}
}
function UpdateAmount(index)
{
	if(cmd=='deposit')
    {
		
	}
    else
    {
		$('amount_'+index).value = 0;
		UpdateBalance(index);
		if($('currency_id_'+index) && $('currency_id_'+index).value){
			var exchangeRate = to_numeric(currencies[$('currency_id_'+index).value]['exchange']);
			if(hotel_currency=='USD'){
				if(roundNumber(to_numeric($('balance').innerHTML)*exchangeRate,3)>=0)
                {
                    var balance = roundNumber(to_numeric($('balance').innerHTML)*exchangeRate,3);
                }
                else
                {
                    var balance = -roundNumber(to_numeric($('balance').innerHTML)*exchangeRate,3);
                }
			}else{
			     if(roundNumber(to_numeric($('balance').innerHTML)/exchangeRate,3)>=0)
                 {
                    var balance = roundNumber(to_numeric($('balance').innerHTML)/exchangeRate,3);
                 }
				else
                {
                    var balance = -roundNumber(to_numeric($('balance').innerHTML)/exchangeRate,3);
                }
			}
			$('amount_'+index).value = number_format(balance);
			if($('credit_card_id_'+index) && $('credit_card_id_'+index).value){
				$('credit_card_id_'+index).value = 1;
				updateBankFee($('credit_card_id_'+index).value,index);
			}else{
				$('bank_fee_'+index).value = number_format(balance);
			}
		}
	}
}
function UpdateBalance(index){
    //console.log(index);
	if(cmd=='deposit'){
	   
		for(i=101;i<=input_count;i++){
			if($('currency_id_'+i) && $('currency_id_'+i).value){
				if($('exchange_rate_'+i)){
					$('exchange_rate_'+i).value = currencies[$('currency_id_'+i).value]['exchange'];
					//alert($('exchange_rate_'+i).value);
				}  
			}
		}
	}else{
		var total = [[|total_amount|]];
		for(i=101;i<=input_count;i++){
			if($('currency_id_'+i) && $('currency_id_'+i).value){
				if($('exchange_rate_'+i) && currencies[$('currency_id_'+i).value]){
					$('exchange_rate_'+i).value = currencies[$('currency_id_'+i).value]['exchange'];
				}
                
				var exchangeRate = to_numeric($('exchange_rate_'+i).value);
				if($('id_'+i) && $('id_'+i).value && $('amount_'+i) && $('amount_'+i).value){
				    amount = ($('payment_type_id_'+i).value!='REFUND')?to_numeric($('amount_'+i).value):(-to_numeric($('amount_'+i).value));
                    if(hotel_currency == 'USD'){
						amount = roundNumber(amount/exchangeRate,3);
					}else{
						amount = roundNumber(amount*exchangeRate,3);
					}
                    total -= amount;
				}
			}
			if($('payment_type_id_'+i) && $('payment_type_id_'+i).value=='CREDIT_CARD'){
				jQuery('#credit_card_id_'+i).attr('disabled','');	
				//jQuery('#currency_id_'+i).attr('disabled','disabled');
				//jQuery('#bank_acc_'+i).attr('disabled','disabled');
                jQuery('#bank_acc_'+i).attr('disabled','');
			}else{
				jQuery('#credit_card_id_'+i).attr('disabled','disabled');	
				jQuery('#currency_id_'+i).attr('disabled','');
				jQuery('#bank_acc_'+i).attr('disabled','disabled');
				if($('payment_type_id_'+i) && $('payment_type_id_'+i).value=='BANK'){
					jQuery('#bank_acc_'+i).attr('disabled','');	
					jQuery('#credit_card_id_'+i).attr('disabled','disabled');
				}
			}
		}
		if(index && total<0){
			//alert('[[.total_must_not_be_a_nagative_number.]]. [[.please_try_again.]]');
			//$('amount_'+index).value = 0;
			UpdateBalance();
			return;
		}
		$('balance').innerHTML = number_format(roundNumber(total,2));
	}
}
if(cmd=='deposit'){
	jQuery('#balance_div').css('display','none');
}else{
	UpdateBalance(false);
}
function UpdatePaymentType(pmt,index){
	if(pmt!='CREDIT_CARD'){
		jQuery('#credit_card_id_'+index).val('');
		jQuery('#credit_card_id_'+index).attr('disabled','disabled');
	}
	if(pmt=='BANK'){
		jQuery('#bank_acc_'+index).attr('disabled','');		
	}else if(pmt=='CREDIT_CARD'){
        //alert(1);
		jQuery('#bank_acc_'+index).attr('disabled','');
        //jQuery('#bank_acc_'+index).removeAttr('disabled');
		jQuery('#credit_card_id_'+index).attr('disabled','');	
		//jQuery('#currency_id_'+index).val('VND');	
		//jQuery('#bank_acc_'+index).val('');
	}else if(pmt=='ROOM CHARGE'){
		jQuery('#reservation_room_id_'+index).css('display','block');
		jQuery('#bank_acc_'+index).css('display','none');
	}else{
		jQuery('#bank_acc_'+index).attr('disabled','disabled');
		jQuery('#bank_acc_'+index).val('');	
		jQuery('#currency_id_'+index).attr('disabled','');
	}
	//if(pmt!='CREDIT_CARD'){
		jQuery('#bank_fee_'+index).val(jQuery('#amount_'+index).val());
	//}
	/*if(pmt=='DEBIT' || pmt=='FOC'){
		jQuery('#currency_id_'+index).val('VND');
		for(var j in currencies){
			if(j !='VND'){
				jQuery('#currency_id_'+index+' option[value='+j+']').attr('disabled','disabled');
			}
		}
	}*/
}
function selectPaymentType(pmt,index,amount_old){
	UpdatePaymentType(pmt,index);
   
	if(cmd=='deposit'){
		
	}else{
		if(is_numeric(to_numeric(jQuery('#id_'+index).val()))){
			//$('amount_'+index).value = number_format(roundNumber(to_numeric($('balance').innerHTML)+(to_numeric($('amount_'+index).value)*to_numeric($('exchange_rate_'+index).value))/to_numeric($('exchange_rate_'+index).value),2));
			if(hotel_currency=='USD'){
				$('amount_'+index).value = number_format(roundNumber(to_numeric($('balance').innerHTML)+(to_numeric($('amount_'+index).value)/to_numeric($('exchange_rate_'+index).value))*to_numeric($('exchange_rate_'+index).value),2));
			}else{
				$('amount_'+index).value = number_format(roundNumber(to_numeric($('balance').innerHTML)+(to_numeric($('amount_'+index).value)*to_numeric($('exchange_rate_'+index).value))/to_numeric($('exchange_rate_'+index).value),2));
			}
			//if(pmt!='CREDIT_CARD'){
				$('bank_fee_'+index).value = $('amount_'+index).value;
			//}
		}else{
			$('amount_'+index).value = number_format(to_numeric($('amount_'+index).value) + to_numeric($('balance').innerHTML));
			//if(pmt!='CREDIT_CARD'){
				$('bank_fee_'+index).value = $('amount_'+index).value;
			//}
			if(hotel_currency=='USD'){
				jQuery('#currency_id_'+index).val('USD');
			}else{
				jQuery('#currency_id_'+index).val('VND');	
			}
		}
		UpdateBalance(index);
	}
}
function checkSubmit(act)
{
    /** THANH add phần check hình thức thanh toán miễn phí trong form thanh toán **/
    var check = true;
    var count_payment = 0;
    jQuery("div[id^=input_group_]").each(function(){
        var payment_type = jQuery(this).find("select[id^=payment_type_id_]").val();
        if(payment_type=='FOC')
        {
            check = false;  
            count_payment++;  
        }
    });
    if(check==false && (jQuery("div[id^=input_group_]").length-1)!=count_payment)
    {
        alert("Khi có hình thức thanh toán là miễn phí thì sẽ không được thanh toán bằng hình thức khác. Xin vui lòng thao tác lại!");
        return false;
    }
    /** END - THANH add phần check hình thức thanh toán miễn phí trong form thanh toán **/
    count_click = count_click + 1;
    var check_zezo = false;
    if(count_click==1)
    {
    	var total = [[|total_amount|]];
    	var paid = 0; var total_amount=0;
    	for(var i=101;i<=input_count;i++)
        {
            if(/** Ninh bo phan check =  0 $('amount_'+i) && to_numeric($('amount_'+i).value)==0 **/ 1==2){
                check_zezo = true;
            }
    		if($('credit_card_id_'+i) && $('payment_type_id_'+i).value=='CREDIT_CARD' && $('credit_card_id_'+i).value=='')
            {
    			$('credit_card_id_'+i).value=1;
    		}
    		if($('currency_id_'+i))
            {
    			if($('exchange_rate_'+i) && currencies[$('currency_id_'+i).value])
                {
    				$('exchange_rate_'+i).value = currencies[$('currency_id_'+i).value]['exchange'];
    			}
    			var exchangeRate = to_numeric($('exchange_rate_'+i).value);
    			if($('id_'+i) && $('id_'+i).value && $('amount_'+i) && $('amount_'+i).value)
                {
    				if(hotel_currency=='USD')
                    {
    					amount = roundNumber(to_numeric($('amount_'+i).value)/exchangeRate,3);
    				}
                    else
                    {
    					amount = roundNumber(to_numeric($('amount_'+i).value)*exchangeRate,3);
    				}
    				total -= amount;
    				total_amount += to_numeric(amount);
    			}
    		}	
    	}
        if(check_zezo){
            alert('[[.Do_not_enter_an_amount_equal_to_zero.]] !');
            count_click = 0;
            return false;
        }
    	if(cmd=='deposit' && total_amount>1000000000)
        {
            count_click = 0;
    		alert('Total deposit is very large. You need to check it');	
    		return false;
            
    	}
        else
        {
    		if(total>100 && cmd != 'deposit')
            {
    			var firm = confirm('Chưa thanh toán hết, bạn có muốn ghi nợ không?');	
    			if(firm)
                {
    				mi_add_new_row('mi_payment');
    				jQuery('#exchange_rate_'+input_count).val([[|default_exchange_rate|]]);
    				jQuery('#bank_acc_'+input_count).attr('disabled','disabled');
    				jQuery('#credit_card_id_'+input_count).attr('disabled','disabled');	
    				jQuery('#payment_type_id_'+input_count).val('DEBIT');
    				jQuery('#currency_id_'+input_count).val('VND');
    				jQuery('#amount_'+input_count).val(number_format(total));
    				$('balance').innerHTML = 0;
    				jQuery('#action').val(act);
    				EditPaymentForm.submit();
    			}
                else
                {
                    alert('[[.event_not_submit.]]!');
                    count_click = 0;
    				//jQuery('#action').val(act);
//    				EditPaymentForm.submit();	
    			}
    		}
            else
            {
    			jQuery('#action').val(act);
    			EditPaymentForm.submit();	
    		}
    	}
     }
}
credit_cards = [[|credit_cards_js|]];
function updateBankFee(credit_card_id,index){
	for(var j in credit_cards){
		if(credit_cards[j]['id'] == credit_card_id){
			jQuery('#bank_fee_'+index).val(number_format(roundNumber(to_numeric(jQuery('#amount_'+index).val()) + to_numeric(jQuery('#amount_'+index).val())*credit_cards[j]['bank_fee']*0.01,2)));
		}else if(credit_card_id == ''){
			jQuery('#bank_fee_'+index).val(number_format(to_numeric(jQuery('#amount_'+index).val())));
		}
	}
}
function fun_check_payment_point(id){
    
}


</script>
