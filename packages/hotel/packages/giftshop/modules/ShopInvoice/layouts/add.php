<link href="skins/default/hotel.css" rel="stylesheet" type="text/css" />
<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<script>
	//window.onLoad = $('agent_name').focus();
	var index = 1;
	/*=================== phan cua product ====================*/
	var j =0;
	var product_array={
		'':''
	<!--LIST:product-->
		,'[[|product.id|]]':{
			'id':'[[|product.id|]]',
			'name':'<?php echo addslashes([[=product.name=]])?>',
			'unit':'[[|product.unit_name|]]',
			'quantity':'1',
			'price':'[[|product.price|]]'
		}
	<!--/LIST:product-->
	}
	var products = <?php echo String::array2suggest([[=product=]]);?>;
	/*=================== /phan cua product ===================*/
</script>
<script src="packages/core/includes/js/calendar.js" type="text/javascript">
</script>
<script type="text/javascript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</script>
<span style="display:none">
	<span style="display:none">
	<span id="table_sample">
		<table width="100%">
			<tr>
			<td width="120">
				<select name="table__reservation_table_id[]" onChange="update_table(this);">
				<option value=""></option>
				[[|table_id_options|]]
				</select>
			  </td> 
			<td width="100">
				<input name="table__code[]" type="text" id="table__code" size="10" class="readonly_input" readonly="readonly">
			</td> 
			<td width="120"><input name="table__num_people[]" type="text" id="table__num_people" size="10" class="readonly_input" readonly="readonly"></td>
			<td><input name="delete_item_table" type="button" value="[[.delete.]]" onClick="this.span.innerHTML = '';"></td>
			</tr>
		</table>
	</span> 
</span>
</span>
<span style="display:none">
	<span style="display:none">
	<span id="product_sample">
		<table width="100%" cellpadding="2">
		<tr>
			<td width="15%">
				<input name="product__id[]" type="text" size="8" onKeyUp="update_product(this);calculate();" />
			</td>
			<td width="23%"><input name="product__name[]" type="text" size="28" readonly="readonly" class="readonly_input" tabindex="1000"/></td>
			<td width="10%"><input name="product__unit[]" type="text" size="5" readonly="readonly" class="readonly_input" tabindex="1000"/></td>
			<td width="15%" align="right"><input name="product__price[]" type="text" size="10" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="1000"/></td>
			<td width="10%" align="center"><input name="product__quantity[]" type="text" size="4" maxlength="3" onKeyUp="update_total(this.code_input);calculate();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" /></td>
			<td width="10%" align="center"><input name="product__quantity_discount[]" type="text" size="4" maxlength="3" onKeyUp="update_total(this.code_input);calculate();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" /></td>
			<td width="10%" align="center"><input name="product__discount_rate[]" type="text" size="4" maxlength="3" onKeyUp="update_total(this.code_input);calculate();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" />%</td>
			<td width="20%" align="right"><input name="product__total[]" type="text" size="15" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="1000"/></td>
			<td width="5%"><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="this.span.innerHTML = '';calculate();" tabindex="1000"></td>
		</tr>
		</table>
	</span> 
</span>
</span>
<div align="center">
<div style="border:1px solid #CCCCCC;width:980px;text-align:center;">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td width="100%">
			<form name="AddShopInvoiceForm" method="post">
			<table cellSpacing=0 cellPadding=0 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25" bgcolor="#FFFFFF">
				<td align="left" bgcolor="#AAD5FF">
				<table width="100%" border="0">
				<tr>
					<td width="25%">
						<table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td>[[.date.]]</td>
                            <td>: [[|date|]]</td>
                          </tr>
                          <tr>
                            <td>[[.code.]]</td>
                            <td>: <input name="code" type="text" id="code" size="10" value="[[|current_code|]]" style="border:0px;background:inherit;font-size:12px;height:15px;font-weight:bold;"></td>
                          </tr>
                        </table>
				    </td>
					<td width="70%" align="center" style="padding:0 0 0 0" nowrap="nowrap">&nbsp;&nbsp;<font style="font-size:20px; text-transform:uppercase;">[[.Shop_invoice.]]</font></td>
					<td width="25%" align="right" nowrap>
						<table border="0" cellspacing="0" cellpadding="3" width="100%">
                          <tr>
                            <td nowrap>[[.currency.]]&nbsp;</td>
                            <td>
								<?php echo HOTEL_CURRENCY;?>
								<input  type="hidden" name="curr" value="<?php echo HOTEL_CURRENCY;?>">
							</td>
                          </tr>
                        </table>
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr bgcolor="#F4F4F4">
			<td bgcolor="#FFFFFF">
				<?php echo Form::$current->error_messages();?>
                <input type="hidden" id="select_bar" name="select_bar" value="0">
				<input type="hidden" name="delete_table" value="">
				<input type="hidden" name="delete_id" value="">
				<table width="100%" style="text-align:left;">
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td width="22%" nowrap>&nbsp;</td>
					  <td width="50%" nowrap>&nbsp;</td>
					  <td width="2%">&nbsp;</td>
					  <td width="26%">&nbsp;</td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>[[.customer.]]</td>
					  <td nowrap>[[.customer_address.]]</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_name" type="text" id="agent_name" style="width:130px;">
				      &nbsp;</td>
					  <td nowrap><input name="agent_address" type="text" id="agent_address" style="width:100%;"></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>[[.shop_id.]]</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><select name="shop_id" id="shop_id" style="width:130px;">
				              </select></td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
                      <td colspan="6">
                      <fieldset style="text-align:left;">
                        <legend>[[.gift_list.]]</legend>
                        <span style="padding:5px;">
                        <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td width="14%">[[.gift_code.]] </td>
                            <td width="23%">[[.gift_name.]]</td>
                            <td width="10%">[[.gift_unit.]]</td>
                            <td width="11%" align="right" nowrap="nowrap">[[.gift_price.]]</td>
                            <td width="9%" nowrap="nowrap" align="center">[[.gift_quantity.]]</td>
                            <td width="9%" nowrap="nowrap" align="center">[[.gift_quantity_discount.]]</td>
                            <td width="10%" nowrap="nowrap" align="center">[[.gift_discount.]]</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="5%"></td>
                          </tr>
                        </table>
                          <input type="button" value="   [[.add_item.]]   " onClick="
								var new_element = document.createElement( 'span' );
								new_element.innerHTML = $('product_sample').innerHTML;
								node = this.parentNode.insertBefore(new_element, this);
								inputs = document.getElementsByName('delete_item_product');
								inputs[inputs.length-1].span = node;
								id_inputs = document.getElementsByName('product__id[]');
								name_inputs = document.getElementsByName('product__name[]');
								unit_inputs = document.getElementsByName('product__unit[]');
								quantity_inputs = document.getElementsByName('product__quantity[]');
								quantity_discount_inputs = document.getElementsByName('product__quantity_discount[]');
								discount_inputs = document.getElementsByName('product__discount_rate[]');
								price_inputs = document.getElementsByName('product__price[]');
								total_inputs = document.getElementsByName('product__total[]');
								
								product_codes = document.getElementsByName('product__id[]');
								product_codes[product_codes.length-1].id = index;
								product_codes[product_codes.length-1].id_input = id_inputs[id_inputs.length-1];
								product_codes[product_codes.length-1].name_input = name_inputs[name_inputs.length-1];
								product_codes[product_codes.length-1].unit_input = unit_inputs[unit_inputs.length-1];
								product_codes[product_codes.length-1].price_input = price_inputs[price_inputs.length-1];
								product_codes[product_codes.length-1].total_input = total_inputs[total_inputs.length-1];
								product_codes[product_codes.length-1].quantity_input = quantity_inputs[quantity_inputs.length-1];
								product_codes[product_codes.length-1].quantity_discount_input = quantity_discount_inputs[quantity_discount_inputs.length-1];
								product_codes[product_codes.length-1].discount_input = discount_inputs[discount_inputs.length-1];
								
								quantity_discount_inputs[quantity_discount_inputs.length-1].code_input = product_codes[product_codes.length-1];
								quantity_inputs[quantity_inputs.length-1].code_input = product_codes[product_codes.length-1];
								discount_inputs[discount_inputs.length-1].code_input = product_codes[product_codes.length-1];
								product_codes[product_codes.length-1].focus();autocomplete(index);index++;
							" />
						  <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.sumary.]]</td>
                            <td align="right"><input name="sumary" type="text" id="sumary" class="readonly_class" readonly size="12" style="text-align:right;"/></td>
                          </tr>
                          <tr>
                            <td width="15%">&nbsp;</td>
                            <td width="25%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="20%" align="right" nowrap="nowrap"></td>
                            <td width="20%" align="right" nowrap="nowrap">[[.tax.]] <input name="tax_rate" type="text" id="tax_rate" maxlength="2" onkeyup="calculate();" style="text-align:right;width:28px" value="5" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" />%</td>
							<td width="5%" align="right"><input name="tax" type="text" id="tax"  class="readonly_class" readonly size="12" style="text-align:right;" /></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.sum_total.]]</td>
							<td><input name="sum_total" type="text" size="12" style="text-align:right" readonly="readonly" class="readonly_class" tabindex="1000"/></td>
                          </tr>
                        </table>
                        </span>
                      </fieldset>
                      </td>
				  </tr>
					
					<tr bgcolor="#EFEFEF">
					  <td colspan="6" bgcolor="#EEEEEE" align="center">
					  <input type="submit" name="save" value="    [[.save.]]    ">
											&nbsp;&nbsp;&nbsp;
						<input type="button" value="  [[.back.]]    " onClick="history.go(-1);">
						&nbsp;&nbsp;&nbsp;
						<?php Draw::button(Portal::language('list_title'),URL::build_current());?></td>
				  </tr>
				</table>
			</td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</div>
</div>
<script type="text/javascript">
	function autocomplete(id)
	{
		jQuery("#"+id).autocomplete(products, {
			minChars: 0,
			width: 200,
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
			update_product(this);
			calculate();
		});
	}
function update_table(obj)
{
	if(obj.value)
	{
		obj.num_people_input.value=table_num_people[obj.value];
		obj.code_input.value=table_code[obj.value];
	}
}
function get_product(code)
{
	if(typeof product_array[code]!= 'undefined')
	{
		return product_array[code];
	}
	return false;
}
function update_product(obj)
{
	var product = get_product(obj.value);
	if(product)
	{
		obj.id_input.value = product.id;
		obj.name_input.value = product.name;
		obj.unit_input.value = product.unit;
		obj.quantity_input.value = 1;
		obj.quantity_discount_input.value = 0;
		obj.price_input.value = number_format(product.price);
		obj.discount_input.value = "0";
		update_total(obj);
	}
}

function update_total(obj)
{
	var product = get_product(obj.value);
	var total = 0;
	var discount =0;
	if(product && is_numeric(obj.quantity_input.value) && is_numeric(obj.quantity_discount_input.value) && is_numeric(obj.discount_input.value))
	{
		total = (to_numeric(obj.quantity_input.value)-to_numeric(obj.quantity_discount_input.value))*to_numeric(obj.price_input.value);
		discount = total*to_numeric(obj.discount_input.value)/100;
		obj.total_input.value = number_format(total-discount);		
	}
}
function exchange(c)
{
	c1=c.split(".");
	return parseInt(c1[0])*1000+parseInt(c1[1]);
}
function calculate()
{
	function exchange(c)
	{
		c1=c.split(".");
		/*c2=c1[0].split(",");*/
		return parseInt(c1[0])*1000+parseInt(c1[1]);
	}
	
	total_input = document.getElementsByName('product__total[]');
	
	var total=0;
	for(var i=1; i<total_input.length; i++)
	{
		total = roundNumber(total + to_numeric(total_input[i].value),2);
	}
	$('sumary').value = number_format(roundNumber(total,2));
	if($('tax_rate').value)
	{
		tax_rate = parseFloat($('tax_rate').value);
	}
	else
	{
		tax_rate = 0;
	}
	if(tax_rate)
	{
		$('tax').value = number_format(roundNumber(total*tax_rate/100,2));
	}
	else
	{
		$('tax').value = 0;
	}
	total = total + roundNumber(total*tax_rate/100,2);
	$('sum_total').value = number_format(roundNumber(total,2));
}

function get_room(room_id)
{
	if(typeof room_array[room_id]!= 'undefined')
	{
		return room_array[room_id];
	}
	return false;
}
function update_room(obj)
{
	var room = get_room(obj.value);
	if(room)
	{
		$('reservation_id').value = room.id;
	}
	else
	{
		$('reservation_id').value = '';
	}
}
function check_barfee()
{
	if($('cb_barfee').checked)
	{
		$('bar_fee_rate').value=5;
	} 
	else
	{
		$('bar_fee_rate').value=0;	
	}
}
	
</script>