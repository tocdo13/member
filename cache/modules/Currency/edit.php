<script src="packages/core/includes/js/multi_items.js"></script>
<script type="text/javascript">
	var j =0;
	var currency_array={
		'':''
	<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key1=>&$item1){if($key1!='current'){$this->map['currencies']['current'] = &$item1;?>
		,'<?php echo $this->map['currencies']['current']['id'];?>':'<?php echo $this->map['currencies']['current']['exchange'];?>'
	<?php }}unset($this->map['currencies']['current']);} ?>
	}
</script>
<span style="display:none">
	<span id="mi_currency_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap; font-weight: normal !important;">
			<span class="multi_edit_input_header">
				<span><input  type="checkbox" id="_checked_#xxxx#"/></span>
			</span>
			<span class="multi_edit_input_header">
				<span><input  name="mi_currency[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="width:55px;text-align:right;"/></span>
			</span>
			<span class="multi_edit_input">
					<input  name="mi_currency[#xxxx#][name]" style="width:180px;" class="multi_edit_text_input" type="text" id="name_#xxxx#"  tabindex="2"/>
			</span><span class="multi_edit_input">
					<input  name="mi_currency[#xxxx#][symbol]" style="width:80px;" class="multi_edit_text_input" type="text" id="symbol_#xxxx#"  tabindex="2"/>
			</span><span class="multi_edit_input">
					<input  name="mi_currency[#xxxx#][exchange]" style="width:100px;text-align:right" class="multi_edit_text_input" type="text" id="exchange_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2">
			</span>
			<span class="multi_edit_input">
					<input  name="mi_currency[#xxxx#][allow_payment]" class="multi_edit_text_input" type="checkbox" id="allow_payment_#xxxx#"  tabindex="2" style="width: 100px;"/>
			</span>            
			<span class="multi_edit_input"><span style="width:20px;">
				<a href="#" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_currency','#xxxx#','');event.returnValue=false;" style="cursor:hand;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
			</span></span><br/>
		</span>
	</span>
</span>
	<form name="EditCurrencyForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    <tr>
        <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px" width="60%"><i class="fa fa-dollar w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('currency');?></td>
		<td  width="40%" align="right">
				<?php if(USER::can_edit(false,ANY_CATEGORY)) {?><input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-btn w3-blue" name="confirm_edit" style="margin-right: 5px;" />
					<input type="button" value="<?php echo Portal::language('delete');?>" class="w3-btn w3-red" onclick="mi_delete_selected_row('mi_currency');" />
				<?php }?>
		</td>
    </tr>
</table>
<div class="body" style="padding:5px;">
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<div><?php echo Form::$current->error_messages();?></div>
	<div style="background-color:#EFEFEF;">
		<span id="mi_currency_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_currency',this.checked);"></span></span>
				<span class="multi_edit_input_header"><span style="width:55px;"><?php echo Portal::language('id');?></span></span>
				<span class="multi_edit_input_header"><span style="width:180px;"><?php echo Portal::language('name');?></span></span>
                <span class="multi_edit_input_header"><span style="width:80px;"><?php echo Portal::language('symbol');?></span></span>
                <span class="multi_edit_input_header"><span style="width:105px;"><?php echo Portal::language('exchange_rate');?></span></span>
                <span class="multi_edit_input_header"><span style="width:100px; text-align: center;"><?php echo Portal::language('use');?></span></span>
				<span class="multi_edit_input_header"><span style="width:20px; text-align: center;"><?php echo Portal::language('delete');?></span></span>
				<br/>
			</span>
		</span>
	</div>
	<?php 
	if(USER::can_add(false,ANY_CATEGORY)){?><input class="w3-btn w3-cyan w3-text-white" style="margin-top: 5px;" type="button" value="<?php echo Portal::language('add_currency');?>" onclick="mi_add_new_row('mi_currency');$('name_'+input_count).focus();"/><?php }?><?php echo $this->map['paging'];?>
</div>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
mi_init_rows('mi_currency',<?php if(isset($_REQUEST['mi_currency'])){echo String::array2js($_REQUEST['mi_currency']);}else{echo '[]';}?>);
function convert_currency(){
	var x= to_numeric($('query').value) / to_numeric(currency_array[$('currency_query_id').value])*to_numeric(currency_array[$('currency_result_id').value]);
	$('result').value = number_format(x);
}
</script>