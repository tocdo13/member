<script src="packages/core/includes/js/multi_items.js" type="text/javascript"></script>
<script src="packages/core/includes/js/aarray.js" type="text/javascript"></script>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	var product_arr = <?php echo String::array2js([[=products=]]);?>;
	var products = <?php echo String::array2suggest([[=products=]]);?>;	
</script>
<span style="display:none">
	<span id="mi_product_group_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][product_id]" style="width:100px;" type="text" id="product_id_#xxxx#" onblur="getProductFromCode('#xxxx#',this.value);" AUTOCOMPLETE=OFF></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][name]" style="width:200px;" type="text" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][num]" style="width:50px;" type="text" id="num_#xxxx#" onchange="updatePaymentPrice('#xxxx#');"></span>
            <span class="multi-input"><input  name="mi_product_group[#xxxx#][unit]" style="width:50px;" type="text" id="unit_#xxxx#" readonly="readonly" tabindex="-1"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][price]" style="width:100px;text-align:right;<?php if(Url::get('type')=='EXPORT'){ echo 'display:none;';}?>" type="text" id="price_#xxxx#" onchange="updatePaymentPrice('#xxxx#');"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][payment_price]" style="width:100px;text-align:right;<?php if(Url::get('type')=='EXPORT'){ echo 'display:none;';}?>" type="text" id="payment_price_#xxxx#" readonly="readonly" class="readonly"  tabindex="-1"></span>			
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','group_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/></span></span><br>
		</span>
	</span> 
</span>
<div class="product-bill-bound">
<form name="EditHKWarehouseInvoiceForm" method="post">
	<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('type'));?>"  class="button-medium-delete">[[.cancel.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
		<fieldset>
			<legend class="title">[[.general_information.]]</legend>
			<table width="700" border="0" cellspacing="0" cellpadding="2">
				<tr>
                  <td class="label">[[.date.]] (*):</td>
				  <td><input name="create_date" type="text" id="create_date"></td>
			      <td align="right"><span class="label">[[.bill_number.]] (*):</span></td>
			      <td align="right"><input name="bill_number" type="text" id="bill_number" readonly="readonly" /></td>
			  </tr>
				<tr>
				  <td class="label">[[.deliver.]]:</td>
				  <td><input name="deliver_name" type="text" id="deliver_name"></td>
			      <td align="right"><span class="label">[[.receiver.]]:</span></td>
			      <td align="right"><input name="receiver_name" type="text" id="receiver_name" /></td>
			  </tr>
				<tr>
                  <td class="label">Di&#7877;n gi&#7843;i:</td>
				  <td colspan="3"><textarea name="note" id="note" style="width:100%"></textarea></td>
		      </tr>
			</table>
	  </fieldset>
		<fieldset>
			<legend class="title">[[.products.]]</legend>
				<span id="mi_product_group_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header" style="width:105px;float:left;">[[.code.]]</span>
						<span class="multi-input-header" style="width:210px;float:left;">[[.name.]]</span>
						<span class="multi-input-header" style="width:60px;float:left;">[[.number.]]</span>
                        <span class="multi-input-header" style="width:55px;float:left;">[[.unit.]]</span>
						<span class="multi-input-header" style="width:100px;float:left;<?php if(Url::get('type')=='EXPORT'){ echo 'display:none;';}?>">[[.price.]]</span>
						<span class="multi-input-header" style="width:100px;float:left;<?php if(Url::get('type')=='EXPORT'){ echo 'display:none;';}?>">[[.payment_price.]]</span>
						<br clear="all">
					</span>
				</span>
				<input type="button" value="[[.add_product.]]" onclick="mi_add_new_row('mi_product_group');myAutocomplete();" style="width:auto;">
		</fieldset>	<br />
        <fieldset>
        	<div style="width:610px;text-align:right;float:left;<?php if(Url::get('type')=='EXPORT'){ echo 'display:none;';}?>">
            	<strong>[[.total_payment.]]:</strong><br />
            </div>
            <div style="float:left;padding-left:5px;text-align:right;width:100px;<?php if(Url::get('type')=='EXPORT'){ echo 'display:none;';}?>"><input name="total_amount" type="text" id="total_amount" readonly="true" style="width:100px;text-align:right;border:0px;border-bottom:1px solid #CCCCCC;font-weight:bold;color:#000000;"></div>
        </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript">
	jQuery("#create_date").mask("99/99/9999");
	function updatePaymentPrice(prefix){
		$('num_'+prefix).value = number_format($('num_'+prefix).value);
		$('price_'+prefix).value = number_format($('price_'+prefix).value);
		var discount =  0;
		$('payment_price_'+prefix).value =  to_numeric($('price_'+prefix).value)*to_numeric($('num_'+prefix).value);
		$('payment_price_'+prefix).value = number_format($('payment_price_'+prefix).value);
		if($('payment_price_'+prefix).value == 'NaN'){
			$('payment_price_'+prefix).value = 0;
		}
		updateTotalPayment();
	}
	
	function updateTotalPayment(){
		var total_payment = 0;
		for(var i=101;i<=input_count;i++){
			if(typeof(jQuery("#payment_price_"+i).val())!='undefined'){
				total_payment += stringToNumber(jQuery("#payment_price_"+i).val());
				
			}
		}
		jQuery("#total_amount").val((total_payment!='NaN')?number_format(total_payment):'0');
	}
	function getProductFromCode(id,value){
		if(typeof(product_arr[value])=='object'){
			$('name_'+id).value = product_arr[value]['name'];
			$('unit_'+id).value = product_arr[value]['unit'];
			$('name_'+id).className = '';
		}else{
			$('name_'+id).className = 'notice';
			if(value){
				$('name_'+id).value = '[[.products_does_not_exist.]]';
			}else{
				$('name_'+id).value = '';
			}
			$('unit_'+id).value = '';
		}
	}
	var data = Array();
	for(var i in product_arr)
	{
		data.push(i);
	}
	function myAutocomplete()
	{
		jQuery("#product_id_"+input_count).autocomplete({
				url: 'get_product.php?wh_invoice=1',
				minChars: 0,
				width: 310,
				matchContains: true,
				autoFill: false,
				formatItem: function(row, i, max) {
					return row.name;
				}  
		});
	
	}
	/*function myAutocomplete()
	{
		//jQuery("#product_id_"+input_count).autocomplete(data).result(function(){});
		jQuery("#product_id_"+input_count).autocomplete(products, {
			minChars: 0,
			width: 310,
			matchContains: true,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.name;
			},
			formatMatch: function(row, i, max) {
				return row.name;
			},
			formatResult: function(row) {
				return row.to;
			}
		}).result(function(){
			getProductFromCode(input_count,this.value);
			updatePaymentPrice(input_count);
		});		
	}*/
	mi_init_rows('mi_product_group',<?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>);
</script>
