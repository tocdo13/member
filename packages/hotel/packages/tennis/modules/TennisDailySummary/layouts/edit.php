<script src="packages/core/includes/js/multi_items.js" type="text/javascript"></script>
<script src="packages/core/includes/js/aarray.js" type="text/javascript"></script>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	var staff_arr = <?php echo String::array2js([[=staffs=]]);?>;
	var product_arr = <?php echo String::array2js([[=products=]]);?>;
</script>
<span style="display:none">
	<span id="mi_staff_group_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_staff_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input"><input  name="mi_staff_group[#xxxx#][staff_id]" style="width:100px;" type="text" id="staff_id_#xxxx#" onblur="getStaffFromCode('#xxxx#',this.value);"></span>
			<span class="multi-input"><input  name="mi_staff_group[#xxxx#][full_name]" style="width:200px;" type="text" readonly="readonly" class="readonly" id="full_name_#xxxx#" tabindex="-1"></span>
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_staff_group','#xxxx#','staff_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/></span></span><br>
		</span>
	</span> 
</span>
<span style="display:none">
	<span id="mi_product_group_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<input  name="mi_product_group[#xxxx#][product_id]" type="hidden" id="product_id_#xxxx#">
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][code]" style="width:100px;" type="text" id="code_#xxxx#" onblur="getProductFromCode('#xxxx#',this.value);"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][name]" style="width:150px;" type="text" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][price]" style="width:150px;" type="text" id="price_#xxxx#" onchange="countProductAmount('#xxxx#');updateTotalPayment();"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][quantity]" style="width:50px;" type="text"  id="quantity_#xxxx#" onchange="countProductAmount('#xxxx#');updateTotalPayment();"></span>
			<span class="multi-input"><input  name="mi_product_group[#xxxx#][amount]" style="width:150px;" type="text" readonly="readonly" class="readonly" id="amount_#xxxx#" tabindex="-1"></span>
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','product_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/></span></span><br>
		</span>
	</span> 
</span>
<span style="display:none">
	<span id="mi_hired_product_group_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_hired_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<input  name="mi_hired_product_group[#xxxx#][product_id]" type="hidden" id="product_id_#xxxx#">
			<span class="multi-input"><input  name="mi_hired_product_group[#xxxx#][code]" style="width:100px;" type="text" id="code_#xxxx#" onblur="getProductFromCode('#xxxx#',this.value);"></span>
			<span class="multi-input"><input  name="mi_hired_product_group[#xxxx#][name]" style="width:150px;" type="text" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="mi_hired_product_group[#xxxx#][price]" style="width:150px;" type="text" id="price_#xxxx#" onchange="countHiredProductAmount('#xxxx#');updateTotalPayment();"></span>
			<span class="multi-input"><input  name="mi_hired_product_group[#xxxx#][quantity]" style="width:50px;" type="text"  id="quantity_#xxxx#" onchange="countHiredProductAmount('#xxxx#');updateTotalPayment();"></span>
			<span class="multi-input"><input  name="mi_hired_product_group[#xxxx#][amount]" style="width:150px;" type="text" readonly="readonly" class="readonly" id="amount_#xxxx#" tabindex="-1"></span>
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_hired_product_group','#xxxx#','hired_product_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/></span></span><br>
		</span>
	</span> 
</span>
<div class="tennis-daily-summary-bound">
<form name="TennisEditForm" method="post">
<input  name="staff_deleted_ids" id="staff_deleted_ids" type="hidden" value="<?php echo URL::get('staff_deleted_ids');?>">
<input  name="product_deleted_ids" id="product_deleted_ids" type="hidden" value="<?php echo URL::get('product_deleted_ids');?>">
<input  name="hired_product_deleted_ids" id="hired_product_deleted_ids" type="hidden" value="<?php echo URL::get('hired_product_deleted_ids');?>">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a onclick="if(!confirm('[[.are_you_sure.]]'))return false;" href="<?php echo Url::build_current(array('cmd'=>'delete','id'));?>" class="button-medium-delete">[[.delete.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
		<fieldset>
			<legend class="title">[[.general_information.]]</legend>
			<table width="700" border="0" cellspacing="0" cellpadding="2">
				<tr>
				  <td align="right" class="label">[[.pay_with_room.]]:</td>
				  <td><select name="hotel_reservation_room_id" id="hotel_reservation_room_id">
				    </select>
				  </td>
				  <td align="right">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr>
				  <td align="right" class="label">[[.tennis_court.]]:</td>
				  <td><strong>[[|court_number|]]</strong></td>
				  <td align="right">[[.status.]]:</td>
				  <td align="left"><select name="status" id="status"></select></td>
			  </tr>
				<tr>
				  <td align="right" class="label">[[.guest_code.]]:</td>
				  <td><input name="guest_id" type="text" id="guest_id" style="display:none;"><input name="code" type="text" id="code" onclick="window"><a href="#" onclick="window.open('?page=tennis_guest&amp;action=select_guest','guest')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('full_name').value='';$('guest_id').value=0;$('code').value=''" style="cursor:pointer;"></td>
				  <td align="right">[[.guest_name.]]:</td>
				  <td align="left"><input name="full_name" type="text" id="full_name"></td>
			  </tr>
				<tr>
                  <td align="right" class="label">[[.time_in.]] (*):</td>
				  <td><input name="time_in_hour" type="text" id="time_in_hour" class="hour" onchange="updateTotalPayment();"><input name="time_in_date" type="text" id="time_in_date" readonly="readonly"  class="date" tabindex="-1"></td>
			      <td align="right"><span class="label">[[.time_out.]] (*):</span></td>
			      <td align="left"><input name="time_out_hour" type="text" id="time_out_hour" class="hour" onchange="updateTotalPayment();"><input name="time_out_date" type="text" id="time_out_date" readonly="readonly"  class="date" tabindex="-1"></td>
			  </tr>
				<tr>
                  <td align="right" class="label">[[.price.]](*):</td>
				  <td><input name="price" type="text" id="price" onchange="updateTotalPayment();"> 
			      <?php echo HOTEL_CURRENCY;?>/[[.hour.]]</td>
				  <td align="right"><span class="label">[[.discount.]]:</span></td>
				  <td align="left"><input name="discount" type="text" id="discount" onchange="updateTotalPayment();">
			      %</td>
			  </tr>
				<tr>
				  <td align="right" class="label">[[.tax.]]:</td>
				  <td><input name="tax" type="text" id="tax"  onchange="updateTotalPayment();">
			      %</td>
			      <td align="right"><span class="label">[[.guest_number.]](*):</span></td>
			      <td align="left"><input name="people_number" type="text" id="people_number" /></td>
			  </tr>
				<tr>
                  <td align="right" class="label">[[.note.]]:</td>
				  <td colspan="3"><textarea name="note" id="note" style="width:90%"></textarea></td>
		      </tr>
			</table>
	  </fieldset>
		<fieldset>
			<legend class="title">[[.staffs.]]</legend>
				<span id="mi_staff_group_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header" style="width:105px;float:left;">[[.code.]]</span>
						<span class="multi-input-header" style="width:210px;float:left;">[[.name.]]</span>
						<br clear="all">
					</span>
				</span>
				<input type="button" value="[[.add_staff.]]" onclick="mi_add_new_row('mi_staff_group');staffAutoComplete();" style="width:auto;">
		</fieldset>	
		<fieldset>
			<legend class="title">[[.used_products.]]</legend>
				<span id="mi_product_group_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header" style="width:105px;float:left;">[[.code.]]</span>
						<span class="multi-input-header" style="width:150px;float:left;">[[.name.]]</span>
						<span class="multi-input-header" style="width:160px;float:left;">[[.price.]]</span>
						<span class="multi-input-header" style="width:55px;float:left;">[[.quantity.]]</span>
						<span class="multi-input-header" style="width:150px;float:left;">[[.amount.]]</span>
						<br clear="all">
					</span>
				</span>
				<input type="button" value="[[.add_product.]]" onclick="mi_add_new_row('mi_product_group');productAutoComplete();" style="width:auto;">
		</fieldset>	
		<fieldset>
			<legend class="title">[[.hired_products.]]</legend>
				<span id="mi_hired_product_group_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header" style="width:105px;float:left;">[[.code.]]</span>
						<span class="multi-input-header" style="width:150px;float:left;">[[.name.]]</span>
						<span class="multi-input-header" style="width:160px;float:left;">[[.price.]]</span>
						<span class="multi-input-header" style="width:55px;float:left;">[[.quantity.]]</span>
						<span class="multi-input-header" style="width:150px;float:left;">[[.amount.]]</span>
						<br clear="all">
					</span>
				</span>
				<input type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_hired_product_group');hiredProductAutoComplete();" style="width:auto;">
		</fieldset><br />
        <fieldset>
        	<div style="width:550px;text-align:right;float:left;">
            	<strong>[[.total_payment.]]:</strong><br />
            </div>
            <div style="float:left;padding-left:5px;text-align:right;width:100px"><input name="total_amount" type="text" id="total_amount" readonly="true" style="width:100px;text-align:right;border:0px;border-bottom:1px solid #CCCCCC;font-weight:bold;color:#000000;"></div>
        </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript">
	jQuery("#time_in_hour").mask("99:99");
	jQuery("#time_out_hour").mask("99:99");	
	function updatePaymentPrice(prefix){
		
	}
	
	function updateTotalPayment(){
		var total_payment = 0;
		var price = 0;
		if(jQuery("#price").val()){
			price += stringToNumber(jQuery("#price").val());
			if(jQuery("#tax").val()){
				total_payment += price + price*(jQuery("#tax").val()/100);
			}
			if(jQuery("#discount").val()){
				total_payment = total_payment - total_payment*(jQuery("#discount").val()/100);
			}
		}
		//update product quantity and amount
		for(var i=101;i<=input_count;i++){
			if(typeof(jQuery("#amount_"+i).val())!='undefined'){
				total_payment += stringToNumber(jQuery("#amount_"+i).val());
				
			}
		}
		jQuery("#total_amount").val((total_payment!='NaN')?number_format(total_payment):'0');
	}
	function countProductAmount(id){
		$('amount_'+id).value = stringToNumber($('price_'+id).value)*$('quantity_'+id).value;
	}
	function countHiredProductAmount(id){
		$('amount_'+id).value = stringToNumber($('price_'+id).value)*$('quantity_'+id).value;
	}
	function getStaffFromCode(id,value){
		if(typeof(staff_arr[value])=='object'){
			$('full_name_'+id).value = staff_arr[value]['full_name'];
			$('full_name_'+id).className = '';
		}else{
			if(value){
				$('full_name_'+id).className = 'notice';
				$('full_name_'+id).value = '[[.staffs_does_not_exist.]]';
			}else{
				$('full_name_'+id).value = '';
			}
		}
	}
	function getProductFromCode(id,value){
		if(typeof(product_arr[value])=='object'){
			$('product_id_'+id).value = product_arr[value]['product_id'];
			$('name_'+id).value = product_arr[value]['name'];
			$('price_'+id).value = product_arr[value]['price'];
			$('name_'+id).className = '';
		}else{
			$('product_id_'+id).value = '';
			$('price_'+id).value = '';
			$('quantity_'+id).value = '';
			$('amount_'+id).value = '';
			if(value){
				$('name_'+id).className = 'notice';			
				$('name_'+id).value = '[[.product_does_not_exist.]]';
			}else{
				$('name_'+id).value = '';
			}
		}
		updateTotalPayment();
	}
	var data = Array();
	for(var i in staff_arr)
	{
		data.push(i);
	}
	function staffAutoComplete()
	{
		jQuery("#staff_id_"+input_count).autocomplete(data).result(function(){});
	}
	var product_data = Array();
	for(var i in product_arr)
	{
		product_data.push(i);
	}
	function productAutoComplete()
	{
		jQuery("#code_"+input_count).autocomplete(product_data).result(function(){});
	}
	function hiredProductAutoComplete()
	{
		jQuery("#code_"+input_count).autocomplete(product_data).result(function(){});
	}
	mi_init_rows('mi_staff_group',<?php echo isset($_REQUEST['mi_staff_group'])?String::array2js($_REQUEST['mi_staff_group']):'{}';?>);
	mi_init_rows('mi_product_group',<?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>);
	mi_init_rows('mi_hired_product_group',<?php echo isset($_REQUEST['mi_hired_product_group'])?String::array2js($_REQUEST['mi_hired_product_group']):'{}';?>);
</script>
