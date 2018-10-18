<script src="packages/core/includes/js/multi_items.js"></script>
<script type="text/javascript">
	var j =0;
	var credit_card_array={
		'':''
	<!--LIST:credit_cards-->
		,'[[|credit_cards.id|]]':'[[|credit_cards.exchange|]]'
	<!--/LIST:credit_cards-->
	}
</script>
<span style="display:none">
	<span id="mi_credit_card_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<span class="multi_edit_input_header">
				<span><input  type="checkbox" id="_checked_#xxxx#"></span>
			</span>
			<span class="multi_edit_input_header">
				<span><input  name="mi_credit_card[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="width:50px;text-align:right;"></span>
			</span>
			<span class="multi_edit_input">
					<input  name="mi_credit_card[#xxxx#][name]" style="width:150px;" class="multi_edit_text_input" type="text" id="name_#xxxx#"  tabindex="2">
			</span><span class="multi_edit_input">
					<input  name="mi_credit_card[#xxxx#][code]" style="width:60px;" class="multi_edit_text_input" type="text" id="code_#xxxx#"  tabindex="2">
			</span><span class="multi_edit_input">
					<input  name="mi_credit_card[#xxxx#][bank_fee]" style="width:60px;" class="multi_edit_text_input" type="text" id="bank_fee_#xxxx#"  tabindex="2">
			</span><span class="multi_edit_input">
					<input  name="mi_currency[#xxxx#][allow_payment]" class="multi_edit_text_input" type="checkbox" id="allow_payment_#xxxx#"  tabindex="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</span>            
			<span class="multi_edit_input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_credit_card','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br>
		</span>
	</span>
</span>
	<form name="EditCreditCardForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    <tr>
        <td class="form-title" width="60%">[[.credit_card.]]</td>
		<td  width="40%" align="right">
				<?php if(USER::can_edit(false,ANY_CATEGORY)) {?><input type="submit" value="  [[.apply.]]  " name="confirm_edit" />
					<input type="button" value="  [[.delete.]]  " onclick="mi_delete_selected_row('mi_credit_card');" />
				<?php }?>
		</td>
    </tr>
</table>
<div class="body" style="padding:5px;">
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<div><?php echo Form::$current->error_messages();?></div>
	<div style="background-color:#EFEFEF;">
		<span id="mi_credit_card_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_credit_card',this.checked);"></span></span>
				<span class="multi_edit_input_header"><span style="width:55px;">[[.id.]]</span></span>
				<span class="multi_edit_input_header"><span style="width:155px;">[[.name.]]</span></span>
                <span class="multi_edit_input_header"><span style="width:65px;">[[.code.]]</span></span>
                <span class="multi_edit_input_header"><span style="width:65px;">[[.bank_fee.]]</span></span>
                <span class="multi_edit_input_header"><span style="width:150px;">[[.allow_payment.]]</span></span>
				<span class="multi_edit_input_header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
				<br>
			</span>
		</span>
	</div>
	<?php 
	if(USER::can_add(false,ANY_CATEGORY)){?><input type="button" value="   [[.add_credit_card.]]   " onclick="mi_add_new_row('mi_credit_card');$('name_'+input_count).focus();"><?php }?>[[|paging|]]
</div>
	</form>
<script>
mi_init_rows('mi_credit_card',<?php if(isset($_REQUEST['mi_credit_card'])){echo String::array2js($_REQUEST['mi_credit_card']);}else{echo '[]';}?>);
function convert_credit_card(){
	var x= to_numeric($('query').value) / to_numeric(currency_array[$('currency_query_id').value])*to_numeric(currency_array[$('currency_result_id').value]);
	$('result').value = number_format(x);
}
</script>