<link href="skins/default/hotel.css" rel="stylesheet" type="text/css" />
<script>
	var index = 1;
	var room_array={
		'':''
	<!--LIST:reservation_room_list-->
		,'[[|reservation_room_list.room_id|]]':{
			'id':'[[|reservation_room_list.id|]]',
			'name':'<?php echo addslashes([[=reservation_room_list.name=]])?>'
		}
	<!--/LIST:reservation_room_list-->
	}
	
	/*================ phan cua bar_table ==================*/
	var i =0;
	var table_num_people={
	<!--LIST:tables-->
		<!--IF:cond([[=tables.stt=]]==0)-->
		'[[|tables.id|]]':'[[|tables.num_people|]]'
		<!--ELSE-->
		,'[[|tables.id|]]':'[[|tables.num_people|]]'
		<!--/IF:cond-->
	<!--/LIST:tables-->
	}
	var table_code={
	<!--LIST:tables-->
		<!--IF:cond([[=tables.stt=]]==0)-->
		'[[|tables.id|]]':'[[|tables.code|]]'
		<!--ELSE-->
		,'[[|tables.id|]]':'[[|tables.code|]]'
		<!--/IF:cond-->
	<!--/LIST:tables-->
	}
	/*================ /phan cua bar_table ==================*/
	
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
			'quantity_discount':'0',
			'price':'[[|product.price|]]'
		}
		<!--/LIST:product-->
	}
	var products = <?php echo String::array2suggest([[=product=]]);?>;
	/*=================== /phan cua product ===================*/
	jQuery(function(){
		jQuery('#img_close').click(function(){
			if(jQuery('#guest_information').toggle(500))
			{
				jQuery('#img_close').attr('src','packages/core/skins/default/images/buttons/node_close.gif');
			}
			else
			{
				jQuery('#img_close').attr('src','packages/core/skins/default/images/buttons/node_open.gif');
			}
		});
	})
	
</script>
<span style="display:none">
	<span style="display:none">
	<span id="table_sample">
		<table>
			<tr>
			<td width="120">
				<select  name="table__reservation_table_id[]" onChange="update_table(this)"><option value=""></option>
				[[|select_table_options|]]
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
		<table width="100%">
		<tr>
			<td width="10%">
				<input name="product__id[]" type="text" size="6" onKeyUp="update_product(this);calculate();" onfocus="update_product(this);calculate();" />
			</td>
			<td width="23%"><input name="product__name[]" type="text" size="20" readonly="readonly" class="readonly_input" tabindex="1000"/></td>
			<td width="9%"><input name="product__unit[]" type="text" size="3" readonly="readonly" class="readonly_input" tabindex="1000"/></td>
			<td width="9%" align="right"><input name="product__price[]" type="text" size="7" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="1000"/></td>
			<td width="10%" align="center"><input name="product__quantity[]" type="text" size="2" onKeyUp="update_total(this.code_input);calculate();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/></td>
			<td width="10%" align="center"><input name="product__quantity_discount[]" type="text" size="2" onKeyUp="update_total(this.code_input);calculate();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/></td>
			<td width="10%" align="right"><input name="product__discount[]" type="text" onKeyUp="update_total(this.code_input);calculate();" style="text-align:right; width: 20px;" maxlength="2" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/>%</td>
			<td width="15%" align="right"><input name="product__total[]" type="text" size="14" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="1000"/></td>
			<td width="4%"><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="this.span.innerHTML = '';calculate();" tabindex="1000"></td>
		</tr>
		</table>
	</span> 
</span>
</span>
<span style="display:none">
	<span id="mi_payment_sample">
		<span id="input_group_#xxxx#">
			<span class="multi_input">
				<select  name="mi_payment[#xxxx#][currency_id]" style="width:140px;" type="text" id="currency_id_#xxxx#" >
                [[|currency_id_options|]]
                </select>
			</span>
			<span class="multi_input">
					<input  name="mi_payment[#xxxx#][amount]" style="width:100px;" type="text" id="amount_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">
			</span>
			<span class="multi_input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_payment','#xxxx#','');event.returnValue=false;" style="cursor:hand;">
			</span></span><br>
		</span>
	</span>
</span>
<div style="text-align:center;">
<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<div class="bar-id">
    <label for="bar_id">[[.Bar_name.]]: </label>
    <span>[[|bar_name|]]</span>
</div>
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="text-align:left;">
	<tr>
		<td width="100%">
			<form name="CheckInBarForm" method="post" >
			<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center" bgcolor="#AAD5FF">
				<table width="100%" border="0">
				<tr>
					<td width="25%">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.date.]]</td>
                            <td>: [[|date|]]</td>
                          </tr>
                          <tr>
                            <td>[[.code.]]</td>
                            <td>: <input name="code" type="text" id="code" value="[[|code|]]" size="10" /></td>
                          </tr>
                        </table>
					</td>
					<td width="50%" align="center">
						<font style="font-size:20px;text-transform:uppercase;"><b>[[.bar_check_in.]]</b></font>
					</td>
					<td width="25%" align="right">
						<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.currency.]]&nbsp;&nbsp;</td>
                            <td>: 
								<?php echo HOTEL_CURRENCY;?>
							</td>
                          </tr>
                        </table>
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr>
			<td>
                <fieldset>
                <legend>[[.guest_information.]]</legend>
                <div align="right"><img src="packages/core/skins/default/images/buttons/node_close.gif" width="20px" style="cursor:pointer" id="img_close" /></div>

            	<div id="guest_information" style="display:none;">
				<?php echo Form::$current->error_messages();?><input type="hidden" id="select_bar" name="select_bar" value="0">
				<table width="100%">
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td width="20%" nowrap>&nbsp;</td>
					  <td width="40%"  nowrap>&nbsp;</td>
					  <td >&nbsp;</td>
				      <td width="20%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>[[.agent_name.]]</td>
					  <td nowrap>[[.agent_address.]]</td>
					  <td>[[.agent_phone.]]</td>
					  <td rowspan="6" width="20%">
                          <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC;">
                              <tr bgcolor="#EBE6A6" height="20px">
                                <th align="left"><img src="skins/default/images/b-chi.gif">&nbsp;[[.note.]]</th>
                              </tr>
                              <tr>
                                <td align="center"><textarea name="note" id="note" rows="5" style="width:100%;border:1px solid #FFFFFF;font-style:italic;font-size:11px;">[[|note|]]</textarea></td>
                              </tr>
                            </table>
                       </td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_name" type="text" id="agent_name" size="20" value="[[|agent_name|]]" />					    
				      &nbsp;</td>
					  <td nowrap><input name="agent_address" type="text" id="agent_address" value="[[|agent_address|]]" size="58" /></td>
					  <td><input name="agent_phone" type="text" id="agent_phone" value="[[|agent_phone|]]" size="15" /></td>
					  <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>[[.bar_id.]]</td>
					  <td nowrap>
					  	<table>
						<tr>
							<td width="100">[[.time_in.]]</td>
							<td width="80">[[.time_out.]]</td>
						</tr>
						</table>
					  </td>
					  <td>[[.num_table.]]</td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><input name="bar_name" type="text" size="20" class="readonly_input" readonly="readonly" value="[[|bar_name|]]" /></td>
					  <td nowrap>
					  <table>
						<tr>
							<td width="100">
								<input name="time_in1" type="text" style="width:17px" value="[[|time_in_hour|]]" class="readonly_input" readonly="readonly" />
								<input name="time_in2" type="text" style="width:7px" value=":" class="readonly_input" readonly="readonly" />
								<input name="time_in3" type="text" style="width:17px" value="[[|time_in_munite|]]" class="readonly_input" readonly="readonly" />
							</td>
							<td width="80"></td>
						</tr>
					  </table>
					  </td>
					  <td><input name="num_table" type="text" id="num_table" value="[[|num_table|]]" size="7" /></td>
					  <td width="1%">&nbsp;</td>
				    </tr>
					<!--IF:check_right(User::can_admin(false,ANY_CATEGORY))-->
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.room_id.]]</td>
					  <td nowrap>[[.reservation_id.]]</td>
					  <td>[[.server_id.]]</td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><select name="room_id" id="room_id" onChange="update_room(this);">
                                            </select></td>
					  <td nowrap><select name="reservation_id" id="reservation_id">
                                            </select></td>
					  <td><select name="server_id" id="server_id">
                      </select></td>
					  <td>&nbsp;</td>
				    </tr> 
					<!--ELSE-->
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.server_id.]]</td>
					  <td nowrap>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><select name="server_id" id="server_id">
                      </select></td>
					  <td nowrap>&nbsp;</td>
					  <td>&nbsp;s</td>
					  <td>&nbsp;</td>
				    </tr> 
					<!--/IF:check_right-->
					</table>
                    </div>
                    </fieldset>
                    <table cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<td colspan="5">
							<fieldset>
							<legend>[[.reservation_table.]]</legend>
							<span style="padding:5px;">
							<table width="100%" border="0">
								<tr>
								<td width="120">[[.table_id.]]</td> 
								<td width="100">[[.table_code.]]</td>
								<td width="120">[[.table_num_people.]]</td> 
								<td>&nbsp;</td>
								</tr>
							</table>
							<!--LIST:table_items-->
							<span id="span_table_[[|table_items.id|]]">
							<table width="100%">
								<tr>
									<td width="120">
										<select  name="table__reservation_table_id[]" onChange="update_table(this)">
											<option value=""></option>
											[[|table_items.table_id_options|]] 
										</select>
									</td>
									<td width="100"><input name="table__code[]" type="text" size="10" value="[[|table_items.code|]]" class="readonly_input" readonly="readonly" /></td>
									<td width="120">
										<input name="table__num_people[]" type="text" size="10" value="[[|table_items.num_people|]]" class="readonly_input" readonly="readonly" /></td>
										<script>
											num_people_inputs = document.getElementsByName('table__num_people[]');
											code_inputs = document.getElementsByName('table__code[]');
											selects = document.getElementsByName('table__reservation_table_id[]');
											selects[selects.length-1].value = [[|table_items.reservation_table_id|]];
											selects[selects.length-1].num_people_input = num_people_inputs[num_people_inputs.length-1];
											selects[selects.length-1].code_input = code_inputs[code_inputs.length-1];
										</script>
									<td><input name="delete_item_table" type="button" value="[[.delete.]]" onClick="$('span_table_[[|table_items.id|]]').innerHTML = '';calculate();"></td>
								</tr>
							</table>
							</span>
							<!--/LIST:table_items-->
							<input type="button" value="   [[.add_item.]]   " onClick="
								var new_element = document.createElement( 'span' );
								new_element.innerHTML = $('table_sample').innerHTML;
								node = this.parentNode.insertBefore(new_element, this);
								inputs = document.getElementsByName('delete_item_table');
								inputs[inputs.length-1].span = node;
								num_people_inputs = document.getElementsByName('table__num_people[]');
								code_inputs = document.getElementsByName('table__code[]');
								selects = document.getElementsByName('table__reservation_table_id[]');
								selects[selects.length-1].num_people_input = num_people_inputs[num_people_inputs.length-1];
								selects[selects.length-1].code_input = code_inputs[code_inputs.length-1];
							">
							</span>
							</fieldset>			</td>
					</tr>
					<tr>
                      <td colspan="5"><fieldset>
                        <legend>[[.reservation_product.]]</legend>
                        <span style="padding:5px;">
                        <table width="100%" border="0">
                          <tr>
                            <td width="10%"> [[.product_code.]] </td>
                            <td width="24%">[[.product_name.]]</td>
                            <td width="8%" nowrap="nowrap">[[.product_unit.]]</td>
							<td width="10%" align="right" nowrap="nowrap">[[.product_price.]]</td>
                            <td width="10%" align="center" nowrap="nowrap">[[.product_quantity.]]</td>
							<td width="10%" nowrap="nowrap">[[.product_quantity_discount.]]</td>
                            <td width="8%" align="right" nowrap="nowrap">[[.product_discount.]]</td>
                            <td width="15%" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="6%">&nbsp;</td>
                          </tr>
                        </table>
						<!--LIST:product_items-->
						<span id="span_product_[[|product_items.product__id|]]">
						<table width="100%">
						  <tr>
							<td width="10%">
								<input name="product__id[]" type="text" onChange="update_product(this);calculate();" value="[[|product_items.product__id|]]" size="6" />
							</td>
							<td width="23%"><input name="product__name[]" type="text" class="readonly_input" tabindex="1000" value="[[|product_items.product__name|]]" size="20" readonly="readonly"/></td>
							<td width="9%"><input name="product__unit[]" type="text" class="readonly_input" tabindex="1000" value="[[|product_items.product__unit|]]" size="3" readonly="readonly"/></td>
							<td width="9%" align="right"><input name="product__price[]" type="text" class="readonly_input" style="text-align:right" tabindex="1000" value="[[|product_items.product__price|]]" size="7" readonly="readonly"/></td>
							<td width="10%" align="center"><input name="product__quantity[]" type="text" onKeyUp="update_total(this.code_input);calculate();" value="[[|product_items.product__quantity|]]" size="2" style="text-align:center"  onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/></td>
							<td width="10%" align="center"><input name="product__quantity_discount[]" type="text" onKeyUp="update_total(this.code_input);calculate();" value="[[|product_items.product__quantity_discount|]]" size="2" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" /></td>
							<td width="10%" align="right"><input name="product__discount[]" type="text" onKeyUp="update_total(this.code_input);calculate();" value="[[|product_items.product__discount|]]" style="text-align:right; width: 20px" maxlength="2" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" />%</td>
							<td width="15%" align="right"><input name="product__total[]" type="text" class="readonly_input_price" style="text-align:right" tabindex="1000" value="[[|product_items.product__total|]]" size="14" readonly="readonly"/></td>
							<td width="4%"><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="$('span_product_[[|product_items.product__id|]]').innerHTML = '';calculate();" tabindex="1000"></td>
						  </tr>
						<script>
							id_inputs = document.getElementsByName('product__id[]');
							name_inputs = document.getElementsByName('product__name[]');
							unit_inputs = document.getElementsByName('product__unit[]');
							quantity_inputs = document.getElementsByName('product__quantity[]');
							quantity_discount_inputs = document.getElementsByName('product__quantity_discount[]');
							discount_inputs = document.getElementsByName('product__discount[]');
							price_inputs = document.getElementsByName('product__price[]');
							total_inputs = document.getElementsByName('product__total[]');
							
							product_codes = document.getElementsByName('product__id[]');
							product_codes[product_codes.length-1].id_input = id_inputs[id_inputs.length-1];
							product_codes[product_codes.length-1].name_input = name_inputs[name_inputs.length-1];
							product_codes[product_codes.length-1].unit_input = unit_inputs[unit_inputs.length-1];
							product_codes[product_codes.length-1].price_input = price_inputs[price_inputs.length-1];
							product_codes[product_codes.length-1].total_input = total_inputs[total_inputs.length-1];
							product_codes[product_codes.length-1].quantity_input = quantity_inputs[quantity_inputs.length-1];
							product_codes[product_codes.length-1].quantity_discount_input = quantity_discount_inputs[quantity_discount_inputs.length-1];
							product_codes[product_codes.length-1].discount_input = discount_inputs[discount_inputs.length-1];
							
							quantity_inputs[quantity_inputs.length-1].code_input = product_codes[product_codes.length-1];
							quantity_discount_inputs[quantity_discount_inputs.length-1].code_input = product_codes[product_codes.length-1];
							discount_inputs[discount_inputs.length-1].code_input = product_codes[product_codes.length-1];
							product_codes[product_codes.length-1].focus();
						</script>
						</table>
						</span>
						<!--/LIST:product_items-->
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
								discount_inputs = document.getElementsByName('product__discount[]');
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
					  </span>
	                      </fieldset>
						  <table width="100%" border="0" cellpadding="2">
                           <tr>
                            <td width="16%"></td>
                            <td width="16%"></td>
                            <td width="2%" nowrap="nowrap"></td>
							<td width="9%" align="right" nowrap="nowrap"></td>
                            <td width="5%" nowrap="nowrap"></td>
							<td width="13%" nowrap="nowrap"></td>
                            <td width="20%" align="right" nowrap="nowrap">Gi&#7843;m gi&aacute;</td>
                            <td width="12%" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="5%">&nbsp;</td>
                          </tr>
                           <tr>
                             <td></td>
                             <td></td>
                             <td nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"></td>
                             <td nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap">[[.sumary.]] </td>
                             <td align="right" nowrap="nowrap"><input name="discount" type="text" id="discount" style="text-align:right" value="[[|discount|]]" size="10" class="readonly_class" readonly="readonly" /></td>
                             <td align="right" nowrap="nowrap">
							 	<span id="sumary" class="readonly_class" style="height:20px; width:100px; letter-spacing:1px;">[[|sumary|]]</span></td>
                             <td>&nbsp;</td>
                           </tr>
						  <tr>
						    <td width="16%" align="right">&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><input type="checkbox" id="cb_barfee" onclick="check_barfee(); calculate();" <?php echo [[=bar_fee_rate=]]!=0?' checked':'';?>>[[.bar_fee.]]</td>
                            <td align="right" nowrap="nowrap">
								<input name="bar_fee" type="text" id="bar_fee" size="12" style="text-align:right" value="[[|bar_fee|]]" class="readonly_class">
								<input name="bar_fee_rate" type="hidden"  id="bar_fee_rate" value="[[|bar_fee_rate|]]">
							</td>
							<td></td>
                          </tr>
						  <tr>
						    <td align="right">&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td align="right" nowrap="nowrap">[[.total_before_tax.]]</td>
						    <td align="right" nowrap="nowrap"><input name="total_before_tax" id="total_before_tax" type="text" class="readonly_class" style="text-align:right" tabindex="1000" value="[[|total_before_tax|]]" size="12" readonly="readonly" /></td>
						    <td></td>
						    </tr>
						  <tr>
						    <td width="16%" align="right">[[.deposit.]]</td>
						    <td width="16%" align="right">[[.remain_paid.]]</td>
						    <td>&nbsp;</td>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.vat_tax.]]
                              <input name="tax_rate" type="text" id="tax_rate" maxlength="2" onkeyup="calculate();" style="text-align:right;width:28px" value="[[|tax_rate|]]" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" />
                              %</td>
                            <td align="right" nowrap="nowrap"><input name="tax" type="text" id="tax" style="text-align:right" value="[[|tax|]]" size="12"/ class="readonly_class" readonly="readonly" /></td>
                            <td></td>
                          </tr>
						  <tr>
						    <td align="right"><input name="prepaid" type="text" id="prepaid" style="text-align:right" value="[[|prepaid|]]" size="10" onkeyup="calculate();"/></td>
						    <td align="right"><span id="remain_paid" class="readonly_class" style="height:20px;letter-spacing:1px; width:100px">[[|remain_prepaid|]]<br /></span><br />
                            	(<em><span id="remain_paid_vnd"><?php echo System::display_number_report([[=remain_prepaid=]]*[[=exchange_rate=]]);?></span> VND</em> )</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td width="20%" align="right">[[.sum_total.]]</td>
						    <td width="12%" align="right" nowrap="nowrap"><input name="sum_total" type="text" id="sum_total" class="readonly_class" style="text-align:right" tabindex="1000" value="[[|sum_total|]]" size="12" readonly="readonly" /></td>
						    <td></td>
					      </tr>
                        </table>
                      </td>
				  </tr>
				  <tr id="payment_type">
				  	  <td colspan="5" valign="top">
                      <table cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                              <td valign="top">
                                  <table width="100%" border="0">
                                  <tr>
                                    <td style="padding-right:44px">
                                    <fieldset>
                                    <legend>[[.payment_result.]]</legend>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td>
                                            <input type="radio" name="payment_result" id="cash" value="CASH" <?php if([[=payment_result=]]=='CASH'){?>checked="checked"<?php }?> onclick="$('payment_detail').style.display=''"> 
                                            <label for="cash">[[.pay_now.]]</label><br>
                                            <input type="radio" name="payment_result" id="room" value="ROOM" <?php if([[=payment_result=]]=='ROOM'){?>checked="checked"<?php }?> onclick="$('payment_detail').style.display='none'">
                                            <label for="room">[[.pay_by_room.]]</label>
                                            </td>
                                            <td valign="top">
                                            <input type="radio" name="payment_result" id="debt" value="DEBT" <?php if([[=payment_result=]]=='DEBT'){?>checked="checked"<?php }?> onclick="$('payment_detail').style.display='none'">
                                            <label for="debt">[[.pay_by_debt.]]</label><br>
                                            <input type="radio" name="payment_result" id="free" value="FREE" <?php if([[=payment_result=]]=='FREE'){?>checked="checked"<?php }?> onclick="$('payment_detail').style.display='none'">
                                            <label for="free">[[.pay_by_free.]]</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <hr color="#CCCCCC" width="100%"/>
                                                <input type="radio" name="payment_result" value="" <?php if(![[=payment_result=]]){?>checked="checked" <?php }?>> [[.no_select.]]
                                            </td>
                                        </tr>
                                        </table>
                                    </fieldset>
                                    </td>
                                  </tr>
                            	</table>
	                          </td>
                              <td width="50%" valign="top">
                              	<div id="payment_detail">
                                    <fieldset>
                                    <legend>[[.pay_by_currency.]]</legend>                              
                                    <table cellpadding="3" cellspacing="0" width="100%" border="1" style="border-collapse:collapse;" bordercolor="#CCCCCC">
                                        <tr style="background-color:#FFFFCC">
                                            <td width="1%" nowrap="nowrap"><input name="select_all" type="checkbox" id="select_all" /></td>
                                            <td>[[.currency_id.]]</td>
                                            <td>[[.value.]]</td>                                        
                                        </tr>
                                        <!--LIST:pay_by_currency-->
                                        <tr>
                                            <td><input name="currency_selecteds[]"  type="checkbox" id="[[|pay_by_currency.id|]]" value="[[|pay_by_currency.id|]]" onclick="if(this.checked){<?php if([[=pay_by_currency.id=]]=='VND'){?>$('value_[[|pay_by_currency.id|]]').value = $('remain_paid_vnd').innerHTML;<?php }?>$('value_[[|pay_by_currency.id|]]').readOnly='readOnly';}else{$('value_[[|pay_by_currency.id|]]').value=0;$('value_[[|pay_by_currency.id|]]').readOnly=''};calculate_rate(this.value);" <?php if(isset([[=pay_by_currency.value=]]) and [[=pay_by_currency.value=]]){?> checked="checked"<?php }?> /></td>
                                            <td><?php if([[=pay_by_currency.id=]]=='USD'){?>[[.Credit_card.]]<?php }else{?>[[|pay_by_currency.id|]]<?php }?></td>
                                            <td><input name="value_[[|pay_by_currency.id|]]"  type="text" id="value_[[|pay_by_currency.id|]]" <?php if(isset([[=pay_by_currency.value=]]) and [[=pay_by_currency.value=]]){?> value="<?php echo System::display_number_report([[=pay_by_currency.value=]]);?>" readonly="readonly" <?php }else{?>value="0"<?php }?> /></td>
                                        </tr>
                                        <!--/LIST:pay_by_currency-->
                                    </table>
                                </fieldset>
                                </div>
                                </td>
                                            
                            </tr>
                        </table>
                      </td>
				  </tr>
				</table>
				<div align="center" style="margin-top:10px;"><input type="submit" name="save" value="    [[.save.]]    ">&nbsp;&nbsp;&nbsp;
                <!--IF:cond_status(MAP['status']=='CHECKIN')-->
                <input type="submit" name="check_out" value="    [[.check_out.]]    ">&nbsp;&nbsp;&nbsp;
                <!--/IF:cond_status-->
	            <input type="button" name="preview" value="    [[.Preview.]]    " onclick="window.open('<?php echo Url::build_current(array('cmd'=>'detail','act'=>'print','id'))?>')">&nbsp;&nbsp;&nbsp;
                <input type="button" value="  [[.back.]]    " onClick="history.go(-1);">&nbsp;&nbsp;&nbsp;<?php Draw::button(Portal::language('list_title'),URL::build_current());?></div>
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
	});

}
function update_table(obj)
{
	if(obj.value)
	{
		obj.num_people_input.value = table_num_people[obj.value];
		obj.code_input.value = table_code[obj.value];
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
	discount_input = document.getElementsByName('product__discount[]');
	price_input = document.getElementsByName('product__price[]');
	quantity_input = document.getElementsByName('product__quantity[]');
	quantity_discount_input = document.getElementsByName('product__quantity_discount[]');
	var discount=0;
	for(var i=1; i<discount_input.length; i++)
	{
		var total = (to_numeric(quantity_input[i].value)-to_numeric(quantity_discount_input[i].value))*to_numeric(price_input[i].value);
		var discnt = total*to_numeric(discount_input[i].value)/100;
		discount = discount+discnt;
	}

	$('discount').value = discount==0?'0,00':number_format(discount);
	
	total_input = document.getElementsByName('product__total[]');
	var total=0;
	for(var i=1; i<total_input.length; i++)
	{
		total = total+to_numeric(total_input[i].value);
	}
	total_before_service = total;
	$('sumary').innerHTML = number_format(total);
	bar_fee = roundNumber(total*$('bar_fee_rate').value/100,2);
	$('bar_fee').value = number_format(bar_fee);
	total = parseFloat(total) + roundNumber(parseFloat(total)*$('bar_fee_rate').value/100,2);
	$('total_before_tax').value = number_format(roundNumber(total,2));
	tax_rate = parseFloat($('tax_rate').value);
	tax = roundNumber(parseFloat(total_before_service)*tax_rate/100,2);
	$('tax').value= number_format(tax);
	total = parseFloat(total) + tax;
	$('sum_total').value = number_format(roundNumber(total,2));
	
	$('remain_paid').innerHTML = number_format(roundNumber(total - to_numeric($('prepaid').value),2));
	$('remain_paid_vnd').innerHTML = number_format(roundNumber((total - to_numeric($('prepaid').value))*<?php echo [[=exchange_rate=]];?>,2));
	if($('value_VND'))
	{
		$('value_VND').value = number_format(roundNumber((total - to_numeric($('prepaid').value))*<?php echo [[=exchange_rate=]];?>,2));
	}
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
function show_payment_type(value)
{
	if(value==2)
	{
		$('payment_type').style.display='';
	}
	else
	{
		$('payment_type').style.display='none';
	}
}
function calculate_rate(value)
{
	
}
</script>
