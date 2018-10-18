<span style="display:none">
	<span id="mi_payment_sample">
		<div id="input_group_#xxxx#">
		<span class="multi-input">
				<span style="width:22px;float:left;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			</span>
			<span class="multi-input">
				<span><input  name="mi_payment[#xxxx#][id]" type="text" id="id_#xxxx#" style="width:30px;text-align:right;background-color:#EFEFEF;border:0px;" value="(auto)" tabindex="-1"></span>
			</span>
			<span class="multi-input">
				<input  name="mi_payment[#xxxx#][description]" style="width:100px;" type="text" id="description_#xxxx#" tabindex="1">
			</span>
			<span class="multi-input">
				<input  name="mi_payment[#xxxx#][time]" style="width:90px;" type="text" id="time_#xxxx#" value="<?php echo date('H:i\' d/m/Y');?>" readonly="" class="readonly" tabindex="-1">
			</span>
			<span class="multi-input">
				<select  name="mi_payment[#xxxx#][payment_method]" style="width:105px;" id="payment_method_#xxxx#" tabindex="2">[[|payment_method_options|]]</select>
			</span>
			<span class="multi-input">
				<select  name="mi_payment[#xxxx#][payment_type]" style="width:85px;" id="payment_type_#xxxx#" tabindex="3">[[|payment_type_options|]]</select>
			</span>
			<span class="multi-input">
				<input  name="mi_payment[#xxxx#][amount]" style="width:90px;text-align:right;font-weight:bold;color:#0066CC;" type="text" id="amount_#xxxx#"  tabindex="4" onchange="UpdateBalance('#xxxx#');this.value=number_format(this.value);">
			</span>
			<span class="multi-input">
				<select  name="mi_payment[#xxxx#][currency_id]" style="width:55px;" id="currency_id_#xxxx#" tabindex="3" onchange="UpdateAmount('#xxxx#');UpdateBalance('#xxxx#');">[[|currency_options|]]</select>
			</span>
			<input  name="mi_payment[#xxxx#][exchange_rate]" type="text" id="exchange_rate_#xxxx#" style="width:100px;" class="hidden"  tabindex="-1">
			<span class="multi-input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('amount_#xxxx#').value = 0;UpdateBalance(false);mi_delete_row($('input_group_#xxxx#'),'mi_payment','#xxxx#','');event.returnValue=false;" style="cursor:pointer;"/>
			</span></span><br clear="all">
		</div>
	</span>
</span>
<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('payment'));?>
<form name="EditPaymentForm" method="post" >
<div align="center" style="min-width:650px;padding:5px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x">
<table cellspacing="0" cellpadding="5" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr height="40">
                    <td class="form-title payment" width="99%">[[.payment.]] [[.for.]] [[.total.]]: <?php echo System::display_number([[=total_amount=]]).' '.HOTEL_CURRENCY;?></td>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="EditPaymentForm.submit();"  class="button-medium-save">[[.save.]]</a></td><?php }?>
                </tr>
            </table>
		</td>
	</tr>
	<tr>
	<td>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table width="100%" cellpadding="5" cellspacing="0" border="2" bordercolor="#FFCCCC">
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
						<span class="multi-input-header"><span style="width:25px;">ID</span></span>
						<span class="multi-input-header"><span style="width:100px;">[[.description.]]</span></span>
						<span class="multi-input-header"><span style="width:90px;">[[.payment_time.]]</span></span>
						<span class="multi-input-header"><span style="width:100px;">[[.payment_method.]]</span></span>
						<span class="multi-input-header"><span style="width:80px;">[[.payment_type.]]</span></span>
						<span class="multi-input-header"><span style="width:90px;">[[.amount.]]</span></span>
						<span class="multi-input-header"><span style="width:50px;">[[.currency.]]</span></span>
						<span class="multi-input-header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
					</span><br clear="all">
				</span>
				<div style="float:right;padding:5px 55px 5px 5px;font-weight:bold;">[[.balance.]]: <span id="balance" style="color:#FF0000;"></span></div><br clear="all">
		</div>
		<div style="float:right;"><input type="button" value="[[.add_payment.]]" class="button-medium-add" onclick="mi_add_new_row('mi_payment');$('exchange_rate_'+input_count).value = [[|default_exchange_rate|]]"></div>
		</td>
	</tr>
	</table>
    <input name="confirm_edit" type="hidden" value="1" />
	</td>
</tr>
</table>
</div>
</form>
<script>
var currencies = [[|currencies|]];
mi_init_rows('mi_payment',<?php if(isset($_REQUEST['mi_payment'])){echo String::array2js($_REQUEST['mi_payment']);}else{echo '[]';}?>);
function UpdateAmount(index){

	$('amount_'+index).value = 0;
	UpdateBalance(index);
	if($('currency_id_'+index).value){
		var exchangeRate = to_numeric(currencies[$('currency_id_'+index).value]['exchange']);
		var balance = to_numeric($('balance').innerHTML)*exchangeRate;
		$('amount_'+index).value = number_format(balance);
	}
}
function UpdateBalance(index){
	var total = ([[|total_amount|]]);
	for(i=101;i<=input_count;i++){
		if($('currency_id_'+i).value){
			if($('exchange_rate_'+i)){
				$('exchange_rate_'+i).value = currencies[$('currency_id_'+i).value]['exchange'];
			}
			var exchangeRate = to_numeric($('exchange_rate_'+i).value);
			if($('id_'+i) && $('id_'+i).value && $('amount_'+i).value){
				amount = roundNumber(to_numeric($('amount_'+i).value)/exchangeRate,2);
				total -= amount;
			}
		}
	}
	if(index && total<0){
		alert('[[.total_must_not_be_a_nagative_number.]]. [[.please_try_again.]]');
		$('amount_'+index).value = 0;
		UpdateBalance();
		return;
	}
	$('balance').innerHTML = number_format(total);
}
UpdateBalance(false);
</script>