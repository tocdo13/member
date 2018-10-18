<span style="display:none">
	<span id="mi_payment_sample">
		<div id="input_group_#xxxx#">
		<span class="multi-input">
				<span style="width:22px;float:left;"><input  type="checkbox" lang="#xxxx#" id="_checked_#xxxx#" class="checked" tabindex="-1"></span>
			</span>
			<span class="multi-input" style="display:none;">
				<span><input  name="mi_payment[#xxxx#][id]" type="text" id="id_#xxxx#" style="width:30px;text-align:right;background-color:#EFEFEF;border:0px; display:none;" value="(auto)" tabindex="-1"></span>
			</span>
			<span class="multi-input">
				<input  name="mi_payment[#xxxx#][description]" style="width:107px;" lang="#xxxx#" type="text" id="description_#xxxx#" tabindex="1">
			</span>  
			<span class="multi-input">
				<input  name="mi_payment[#xxxx#][time]" style="width:90px;" type="text" lang="#xxxx#" id="time_#xxxx#" value="<?php echo date('H:i\' d/m/Y');?>" readonly="" class="readonly" tabindex="-1">   
			</span>
			<span class="multi-input">
				<select  name="mi_payment[#xxxx#][payment_type_id]" style="width:105px;" id="payment_type_id_#xxxx#" lang="#xxxx#" tabindex="3" onchange="selectPaymentType(this.value,#xxxx#,$('amount_#xxxx#').value);" class="payment_type">[[|payment_type_options|]]</select>
			</span>
             <span class="multi-input">
				<input  name="mi_payment[#xxxx#][bank_acc]" style="width:85px;" id="bank_acc_#xxxx#" lang="#xxxx#" tabindex="2" />
			</span>
             <span class="multi-input">
				<select  name="mi_payment[#xxxx#][reservation_room_id]" style="width:85px;display:none;" id="reservation_room_id_#xxxx#" lang="#xxxx#" tabindex="2">[[|reservation_room_id|]]</select>
			</span>
            <span class="multi-input">
				<select  name="mi_payment[#xxxx#][credit_card_id]" style="width:80px;" id="credit_card_id_#xxxx#" lang="#xxxx#" tabindex="2" onchange="updateBankFee(this.value,'#xxxx#');">[[|credit_card_options|]]</select>
			</span>
            <span class="multi-input">
				<select  name="mi_payment[#xxxx#][currency_id]" style="width:105px;" id="currency_id_#xxxx#" lang="#xxxx#" tabindex="3" onchange="UpdateAmount('#xxxx#');UpdateBalance('#xxxx#');">[[|currency_options|]]</select>
			</span>
			<span class="multi-input">
				<input  name="mi_payment[#xxxx#][amount]" style="width:90px;text-align:right;font-weight:bold;color:#0066CC;" type="text" id="amount_#xxxx#" lang="#xxxx#"  tabindex="4" onchange="UpdateBalance('#xxxx#');" class="input_number" onkeyup="updateBankFee($('credit_card_id_#xxxx#').value,'#xxxx#');UpdateBalance('#xxxx#');">
			</span>
            <span class="multi-input">
				<input  name="mi_payment[#xxxx#][bank_fee]" style="width:76px;text-align:right;font-weight:bold;color:#0066CC;" type="text" id="bank_fee_#xxxx#" lang="#xxxx#"  tabindex="4" class="input_number" readonly="readonly">
			</span>
            <span class="multi-input">
				<input  name="mi_payment[#xxxx#][paid]" type="checkbox" id="paid_#xxxx#" lang="#xxxx#" style="width:35px;" readonly="readonly">
			</span>
			<input  name="mi_payment[#xxxx#][exchange_rate]" type="hidden" id="exchange_rate_#xxxx#" lang="#xxxx#" style="width:100px;" class="hidden"  tabindex="-1">
			<span class="multi-input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('amount_#xxxx#').value = 0;UpdateBalance(false);mi_delete_row($('input_group_#xxxx#'),'mi_payment','#xxxx#','');event.returnValue=false;" style="cursor:pointer;"/>
			</span></span><br clear="all">
		</div>
	</span>
</span>
<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('payment'));?>
<form name="EditPaymentForm" method="post" >
<div align="center" style="min-width:750px;padding:5px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x">
<table cellspacing="0" cellpadding="5" width="97%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="95%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr height="40">
                    <td class="form-title payment" width="90%"><?php if(Url::get('cmd')=='deposit'){?>[[.deposit_for.]] [[|obj_payment|]]<?php }else{?>[[.payment.]] [[.for.]] [[.total_amount.]]: <?php echo System::display_number([[=total_amount=]]).' '.HOTEL_CURRENCY; }?></td>
                    <?php 
						if(Url::get('reservation')){
							$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=reservation';
							$her .= ((Url::get('act')=='group_folio')?'&cmd=group_folio':'&cmd=create_folio');
							$her .= ((Url::get('act')=='create_folio')?('&rr_id='.Url::get('id').'&id='.Url::get('id')):('&id='.Url::get('id'))); 
							$her .= ((Url::get('folio_id'))?('&folio_id='.Url::get('folio_id')):'');
							$her .= ((Url::get('traveller_id'))?('&traveller_id='.Url::get('traveller_id')):''); 
							$her .= ((Url::get('customer_id'))?('&customer_id='.Url::get('customer_id')):'');
						}else{
							$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429';
							$her .= ((Url::get('act')=='group_folio')?'&cmd=group_folio':'&cmd=create_folio');
							$her .= ((Url::get('act')=='create_folio')?('&rr_id='.Url::get('id')):('&id='.Url::get('id'))); 
							$her .= ((Url::get('folio_id'))?('&folio_id='.Url::get('folio_id')):'');
							$her .= ((Url::get('traveller_id'))?('&traveller_id='.Url::get('traveller_id')):''); 
							$her .= ((Url::get('customer_id'))?('&customer_id='.Url::get('customer_id')):''); 
						}
					?>
                    <?php if(User::can_add(false,ANY_CATEGORY) && (Url::get('act')=='create_folio' || Url::get('act')=='group_folio')){?>
                    <td width="1%"><a href="<?php echo $her;?>&portal_id=<?php echo PORTAL_ID;?>" class="button-medium-back">[[.back.]]</a></td>
                    <?php }?>
                    <?php if(User::can_add(false,ANY_CATEGORY) && Url::get('cmd')=='deposit'){?><td width="1%"><a href="javascript:void(0)" onclick="checkPrint();"  class="button-medium-save">[[.print.]]</a></td><?php }?>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="checkSubmit('save');"  class="button-medium-save">[[.save.]]</a></td><?php }?>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="checkSubmit('save_stay');"  class="button-medium-save">[[.save_and_close.]]</a></td><?php }?>
                </tr>
            </table>
		</td>
	</tr>  
	<tr>
	<td>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table width="95%" cellpadding="5" cellspacing="0" border="2" bordercolor="#FFCCCC" style="margin:auto;">
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
					<span style="white-space:nowrap;">
						<span class="multi-input-header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_payment',this.checked);"></span></span>
						<span class="multi-input-header"  style="display:none;"><span style="width:25px; display:none;">ID</span></span>
						<span class="multi-input-header"><span style="width:100px;">[[.description.]]</span></span>
						<span class="multi-input-header"><span style="width:90px;">[[.pmt_time.]]</span></span>
						<span class="multi-input-header"><span style="width:100px;">[[.payment_type.]]</span></span>
                        <span class="multi-input-header"><span style="width:80px;" id="bank_title">[[.bank_account.]]</span></span>
                        <span class="multi-input-header"><span style="width:75px;">[[.credit_card_type.]]</span></span>
                        <span class="multi-input-header"><span style="width:100px;">[[.currency.]]</span></span>
						<span class="multi-input-header"><span style="width:90px;">[[.amount.]]</span></span>
                        
                        <span class="multi-input-header"><span style="width:35px;">[[.paid.]]</span></span>
						<span class="multi-input-header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
					</span><br clear="all">
				</span>
				<div style="float:left;padding:5px 55px 5px 5px;font-weight:bold;" id="balance_div">[[.balance.]]: <span id="balance" style="color:#FF0000;"><?php echo System::display_number([[=total_amount=]]-[[=total_paid=]]);?></span><?php echo ' '.HOTEL_CURRENCY;?></div><br clear="all">
		</div>
		<div style="float:left;"><input type="button" value="[[.add.]]" class="button-medium-add" onclick="mi_add_new_row('mi_payment');jQuery('#amount_'+input_count).ForceNumericOnly();jQuery('#amount_'+input_count).FormatNumber();$('exchange_rate_'+input_count).value = [[|default_exchange_rate|]]"></div>
		</td>
	</tr>
	</table>
    <input name="action" id="action" value="" type="hidden" />
    <input name="confirm_edit" type="hidden" value="1" />
    <input name="total_amount" type="hidden" id="total_amount" value="[[|total_amount|]]">
	</td>
</tr>
</table>
</div>
</form>
<script>
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
?>
for(var j=101;j<=input_count;j++){
	jQuery('#amount_'+j).FormatNumber();
	UpdatePaymentType(jQuery('#payment_type_id_'+j).val(),j);			
}
for(var i in mi_payment_arr){
	if(mi_payment_arr[i]['paid']==true){
		for(var j=101;j<=input_count;j++){
			jQuery('#paid_'+j).css('checked','checked');	
		}		
	}
}
var index='';
function checkPrint(){
	for(var j=101;j<=input_count;j++){
		if($('_checked_'+j)){
			if(jQuery('#_checked_'+j).attr('checked')==true){
				if(index==''){        
					index += jQuery('#id_'+j).val();
				}else{
					index += ','+jQuery('#id_'+j).val();
				}
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
function UpdateAmount(index){
	if(cmd=='deposit'){
		
	}else{
		$('amount_'+index).value = 0;
		UpdateBalance(index);
		if($('currency_id_'+index) && $('currency_id_'+index).value){
			var exchangeRate = to_numeric(currencies[$('currency_id_'+index).value]['exchange']);
			var balance = roundNumber(to_numeric($('balance').innerHTML)/exchangeRate,3);
			$('amount_'+index).value = number_format(balance);
			if($('credit_card_id_'+index) && $('credit_card_id_'+index).value!=''){
				updateBankFee($('credit_card_id_'+index).value,index);
			}else{
				$('bank_fee_'+index).value = number_format(balance);
			}
		}
	}
}
function UpdateBalance(index){
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
					amount = roundNumber(to_numeric($('amount_'+i).value)*exchangeRate,3);
					total -= amount;
				}
			}
			if($('payment_type_id_'+i) && $('payment_type_id_'+i).value=='CREDIT_CARD'){
				jQuery('#credit_card_id_'+i).attr('disabled','');	
				//jQuery('#currency_id_'+i).attr('disabled','disabled');
				jQuery('#bank_acc_'+i).attr('disabled','disabled');
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
		jQuery('#bank_acc_'+index).attr('disabled','disabled');
		jQuery('#credit_card_id_'+index).attr('disabled','');	
		//jQuery('#currency_id_'+index).val('VND');	
		jQuery('#bank_acc_'+index).val('');
	}else if(pmt=='ROOM CHARGE'){
		jQuery('#reservation_room_id_'+index).css('display','block');
		jQuery('#bank_acc_'+index).css('display','none');
	}else{
		jQuery('#bank_acc_'+index).attr('disabled','disabled');
		jQuery('#bank_acc_'+index).val('');	
		jQuery('#currency_id_'+index).attr('disabled','');
	}
	if(pmt!='CREDIT_CARD'){
		jQuery('#bank_fee_'+index).val(jQuery('#amount_'+index).val());
	}
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
			$('amount_'+index).value = number_format(roundNumber(to_numeric($('balance').innerHTML)+(to_numeric($('amount_'+index).value)*to_numeric($('exchange_rate_'+index).value))/to_numeric($('exchange_rate_'+index).value),2));
			if(pmt!='CREDIT_CARD'){
				$('bank_fee_'+index).value = $('amount_'+index).value;
			}
		}else{
			$('amount_'+index).value = number_format(to_numeric($('amount_'+index).value) + to_numeric($('balance').innerHTML));
			if(pmt!='CREDIT_CARD'){
				$('bank_fee_'+index).value = $('amount_'+index).value;
			}
			jQuery('#currency_id_'+index).val('VND');
		}
		UpdateBalance(index);
	}
}
function checkSubmit(act){
	var total = [[|total_amount|]];
	var paid = 0; var total_amount=0;
	for(var i=101;i<=input_count;i++){
		if($('credit_card_id_'+i) && $('payment_type_id_'+i).value=='CREDIT_CARD' && $('credit_card_id_'+i).value==''){
			alert('Bạn phải chọn loại thẻ');
			return false;	
		}
		/*if($('payment_type_id_'+i).value=='BANK' && $('bank_acc_'+i).value==''){
			alert('Bạn phải chọn tài khoản ngân hàng');
			return false;	
		}*/
		if($('currency_id_'+i)){
			if($('exchange_rate_'+i) && currencies[$('currency_id_'+i).value]){
				$('exchange_rate_'+i).value = currencies[$('currency_id_'+i).value]['exchange'];
			}
			var exchangeRate = to_numeric($('exchange_rate_'+i).value);
			if($('id_'+i) && $('id_'+i).value && $('amount_'+i) && $('amount_'+i).value){
				amount = roundNumber(to_numeric($('amount_'+i).value)*exchangeRate,3);
				total -= amount;
				total_amount += to_numeric(amount);
			}
		}	
	}
	if(cmd=='deposit' && total_amount>1000000000){
		alert('Total deposit is very large. You need to check it');	
		return false;
	}else{
		if(total>100){
			var firm = confirm('Chưa Thanh toán hết. Bạn có muốn ghi nợ');	
			if(firm){
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
		}else{
			jQuery('#action').val(act);
			EditPaymentForm.submit();	
		}
	}
}
credit_cards = [[|credit_cards_js|]];
function updateBankFee(credit_card_id,index){
	for(var j in credit_cards){
		if(credit_cards[j]['id'] == credit_card_id){
			jQuery('#bank_fee_'+index).val(number_format(roundNumber(to_numeric(jQuery('#amount_'+index).val()) + to_numeric(jQuery('#amount_'+index).val())*credit_cards[j]['bank_fee']*0.01,2)));
		}else if(credit_card_id == ''){
			jQuery('#bank_fee_'+index).val(jQuery('#amount_'+index).val());
		}
	}
}
</script>