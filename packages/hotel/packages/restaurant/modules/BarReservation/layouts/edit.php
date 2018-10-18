<link href="skins/default/hotel.css" rel="stylesheet" type="text/css" />
<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<script>
	var index =1;
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
		[[|tables.id|]]:[[|tables.num_people|]]
		<!--ELSE-->
		,[[|tables.id|]]:[[|tables.num_people|]]
		<!--/IF:cond-->
	<!--/LIST:tables-->
	}
	var table_code={
	<!--LIST:tables-->
		<!--IF:cond([[=tables.stt=]]==0)-->
		[[|tables.id|]]:'[[|tables.code|]]'
		<!--ELSE-->
		,[[|tables.id|]]:'[[|tables.code|]]'
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
			'price':'[[|product.price|]]'
		}
	<!--/LIST:product-->
	}
	/*=================== /phan cua product ===================*/
</script>
<script src="packages/core/includes/js/calendar.js">
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
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
			<td width="25%"><input name="product__name[]" type="text" size="28" readonly="readonly" class="readonly_input" tabindex="1000"/></td>
			<td width="10%"><input name="product__unit[]" type="text" size="5" readonly="readonly" class="readonly_input" tabindex="1000"/></td>
			<td width="10%"><input name="product__quantity[]" type="text" size="4" onKeyUp="update_total(this.code_input);calculate();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/></td>
			<td width="15%" align="right"><input name="product__price[]" type="text" size="10" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="1000"/></td>
			<td width="20%" align="right"><input name="product__total[]" type="text" size="15" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="1000"/></td>
			<td width="5%"><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="this.span.innerHTML = '';calculate();" tabindex="1000"></td>
		</tr>
		</table>
	</span> 
</span>
</span>
<div style="text-align:center;">
<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
	<tr>
		<td width="100%">
			<form name="EditBarReservationForm" method="post">
			<table cellSpacing=0 cellPadding=0 border=0 width="100%" style="border-collapse:collapse;text-align:left;" bordercolor="#97ADC5">
			<tr height="25" bgcolor="#FFFFFF">
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
                            <td>: <input name="code" type="text" id="code" size="10" value="[[|code|]]" style="border:0px;background:inherit;font-size:12px;height:15px;font-weight:bold;"></td>
                          </tr>
                        </table>
				    </td>
					<td width="70%" align="center" style="padding:0 0 0 0" nowrap="nowrap">&nbsp;&nbsp;<font style="font-size:20px; text-transform:uppercase;">[[.bar_reservation_edit.]]</font></td>
					<td width="25%" align="right" nowrap>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
                          <tr>
                            <td nowrap>[[.currency.]]&nbsp;</td>
                            <td>
								<?php echo HOTEL_CURRENCY;?><input  type="hidden" name="curr" value="<?php echo HOTEL_CURRENCY;?>">
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
				<?php echo Form::$current->error_messages();?><input type="hidden" id="select_bar" name="select_bar" value="0">
				<input type="hidden" name="delete_table" value="">
				<input type="hidden" name="delete_id" value="">
				<table width="100%">
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
					  <td nowrap>[[.agent_name.]]</td>
					  <td nowrap>[[.agent_address.]]</td>
					  <td>&nbsp;</td>
					  <td colspan="2" rowspan="8" align="left" valign="top">
					  	<table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC;">
						  <tr bgcolor="#EBE6A6" height="20px">
							<th align="left">
								<img src="skins/default/images/b-chi.gif">
								&nbsp;
								[[.note.]]							</th>
						  </tr>
						  <tr>
							<td align="center">
					         <textarea name="note" id="note" rows="11" style="width:100%;border:1px solid #FFFFFF;font-style:italic;font-size:11px;">[[|note|]]</textarea>							 </tr>
						</table>					  </td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_name" type="text" id="agent_name" onKeyUp="clone_input();" style="width:130px;" value="[[|agent_name|]]">
				      &nbsp;</td>
					  <td nowrap><input name="agent_address" type="text" id="agent_address" style="width:100%;" value="[[|agent_address|]]"></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.agent_fax.]]</td>
					  <td nowrap>
					  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td align="left" width="50%">[[.agent_phone.]]</td>
								<td align="left">[[.receiver_name.]]</td>
							  </tr>
							</table>
					  </td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_fax" type="text" id="agent_fax" style="width:130px;" value="[[|agent_fax|]]"></td>
					  <td nowrap>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<th align="left"><input name="agent_phone" type="text" id="agent_phone" style="width:99%" value="[[|agent_phone|]]"></th>
							<th align="right" style="padding-left:10px;"><input name="receiver_name" type="text" id="receiver_name" style="width:99%" value="[[|receiver_name|]]"></th>
						  </tr>
						  </table>					  
					  </td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>[[.bar_id.]]</td>
					  <td nowrap>
					  	<table>
						<tr>
							<td width="130">[[.arrival_time.]]</td>
							<td width="130">[[.arrival_time_in.]]</td>
							<td width="130">[[.arrival_time_out.]]</td>
							<td>&nbsp;</td>
						</tr>
						</table>					  </td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><select name="bar_id" id="bar_id" style="width:130px;"></select></td>
					  <td nowrap>
					  <table>
						<tr>
							<td width="130">
								<input name="arrival_date" type="text" id="arrival_date" size="10" value="[[|arrival_date1|]]"/>
								<a href="#" name="arrival_time_date_in" id="arrival_time_date_in" onClick="cal.select(this.input,'arrival_time_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" /></a>
								<script>
									var inputs = document.getElementsByTagName('input');
									var anchors = document.getElementsByTagName('a');
									anchors[anchors.length-1].input = inputs[inputs.length-1];
								</script>							</td>
							<td width="130">
								<table><tr>
								<td>
								<select  name="arrival_time_in_hour">
									<?php for($i=0;$i<24;$i++) 
									{
										if($this->map{'time_in_hour1'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'h</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'h</option>';
										}
									} ?></select>								</td>
								<td>
								<select  name="arrival_time_in_munite">
									<?php for($i=0;$i<60;$i=$i+5) 
									{
										if($this->map{'time_in_munite1'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									} ?></select>
                                    </td>
								</tr>
                                </table>
                            </td>
							<td width="80">
								<table><tr>
								<td>
								<select  name="arrival_time_out_hour">
									<?php for($i=0;$i<24;$i++) 
									{
										if($this->map{'time_out_hour1'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'h</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'h</option>';
										}
									} ?></select>
                                </td>
								<td>
								<select  name="arrival_time_out_munite">
									<?php for($i=0;$i<60;$i=$i+5)  
									{
										if($this->map{'time_out_munite1'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									} ?></select>                                
								</td>
								</tr>
                                </table>
                                </td>
							<td><input type="button" name="go" value=" OK " onClick="this.form.select_bar.value=1;this.form.submit();"></td>
						</tr>
					  </table>					  </td>
					  <td>&nbsp;</td>
				    </tr>
					<!--IF:check_right(User::is_admin())-->
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.room_id.]]</td>
					  <td nowrap><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" width="50%">[[.reservation_id.]]</td>
                            <td align="right">[[.num_table.]]</td>
                          </tr>
                        </table></td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><select name="room_id" id="room_id" onChange="update_room(this);" style="width:130px;">
                                            </select></td>
					  <td nowrap>
					  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<th align="left" width="50%"><select name="reservation_id" id="reservation_id" style="width:99%;"></select></th>
							<th align="right"><input name="num_table" type="text" id="num_table" style="text-align:right" size="10" value="[[|num_table|]]"></th>
						  </tr>
						</table>
					  </td>
					  <td>&nbsp;</td>
				    </tr>
					<!--ELSE-->
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.num_table.]]</td>
					  <td nowrap>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="num_table" type="text" id="num_table" style="text-align:right" size="10" value="[[|num_table|]]"></td>
					  <td nowrap>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr>
					<!--/IF:check_right-->
					<tr>
						<td colspan="6">
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
										</select>									</td>
									<td width="100"><input name="table__code[]" type="text" size="10" value="[[|table_items.code|]]" class="readonly_input" readonly="readonly" /></td>
									<td width="120">
										<input name="table__num_people[]" type="text" size="10" value="[[|table_items.num_people|]]" class="readonly_input" readonly="readonly" /></td>
										<script>
											num_people_inputs = document.getElementsByName('table__num_people[]');
											code_inputs = document.getElementsByName('table__code[]');
											selects = document.getElementsByTagName('select');
											selects[selects.length-1].value = [[|table_items.reservation_table_id|]];
											selects[selects.length-1].num_people_input = num_people_inputs[num_people_inputs.length-1];
											selects[selects.length-1].code_input = code_inputs[code_inputs.length-1];
										</script>
									<td><input name="delete_item_table" type="button" value="[[.delete.]]" onClick="document.getElementById('span_table_[[|table_items.id|]]').innerHTML = '';"></td>
								</tr>
							</table>
							</span>
							<!--/LIST:table_items-->
							<input type="button" value="   [[.add_item.]]   " onClick="
								var new_element = document.createElement( 'span' );
								new_element.innerHTML = document.getElementById('table_sample').innerHTML;
								node = this.parentNode.insertBefore(new_element, this);
								inputs = document.getElementsByName('delete_item_table');
								inputs[inputs.length-1].span = node;
								num_people_inputs = document.getElementsByName('table__num_people[]');
								code_inputs = document.getElementsByName('table__code[]');
								selects = document.getElementsByTagName('select');
								selects[selects.length-1].num_people_input = num_people_inputs[num_people_inputs.length-1];
								selects[selects.length-1].code_input = code_inputs[code_inputs.length-1];
							">
							</span>
							</fieldset>			</td>
					</tr>
					<tr>
                      <td colspan="6"><fieldset>
                        <legend>[[.reservation_product.]]</legend>
                        <span style="padding:5px;">
                        <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td width="15%"> [[.product_code.]] </td>
                            <td width="28%">[[.product_name.]]</td>
                            <td width="10%">[[.product_unit.]]</td>
                            <td width="7%" nowrap="nowrap">[[.product_quantity.]]</td>
                            <td width="15%" align="right" nowrap="nowrap">[[.product_price.]]</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="5%"></td>
                          </tr>
                        </table>
						<!--LIST:product_items-->
						<span id="span_product_[[|product_items.product__id|]]">
						<table width="100%" cellpadding="2">
						  <tr>
							<td width="15%">
								<input name="product__id[]" type="text" onChange="update_product(this);calculate();" value="[[|product_items.product__id|]]" size="8" />
							</td>
							<td width="25%"><input name="product__name[]" type="text" class="readonly_input" tabindex="1000" value="[[|product_items.product__name|]]" size="28" readonly="readonly"/></td>
							<td width="10%"><input name="product__unit[]" type="text" class="readonly_input" tabindex="1000" value="[[|product_items.product__unit|]]" size="5" readonly="readonly"/></td>
							<td width="10%"><input name="product__quantity[]" type="text" onKeyUp="update_total(this.code_input);calculate();" value="[[|product_items.product__quantity|]]" size="4" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" /></td>
							<td width="15%" align="right"><input name="product__price[]" type="text" class="readonly_input" style="text-align:right" tabindex="1000" value="[[|product_items.product__price|]]" size="10" readonly="readonly"/></td>
							<td width="20%" align="right"><input name="product__total[]" type="text" class="readonly_input" style="text-align:right" tabindex="1000" value="[[|product_items.product__total|]]" size="15" readonly="readonly"/></td>
							<td width="5%"><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="document.getElementById('span_product_[[|product_items.product__id|]]').innerHTML = '';calculate();" tabindex="1000"></td>
						  </tr>
						<script>
							id_inputs = document.getElementsByName('product__id[]');
							name_inputs = document.getElementsByName('product__name[]');
							unit_inputs = document.getElementsByName('product__unit[]');
							quantity_inputs = document.getElementsByName('product__quantity[]');
							price_inputs = document.getElementsByName('product__price[]');
							total_inputs = document.getElementsByName('product__total[]');
							
							product_codes = document.getElementsByName('product__id[]');
							product_codes[product_codes.length-1].id_input = id_inputs[id_inputs.length-1];
							product_codes[product_codes.length-1].name_input = name_inputs[name_inputs.length-1];
							product_codes[product_codes.length-1].unit_input = unit_inputs[unit_inputs.length-1];
							product_codes[product_codes.length-1].price_input = price_inputs[price_inputs.length-1];
							product_codes[product_codes.length-1].total_input = total_inputs[total_inputs.length-1];
							product_codes[product_codes.length-1].quantity_input = quantity_inputs[quantity_inputs.length-1];
							
							quantity_inputs[quantity_inputs.length-1].code_input = product_codes[product_codes.length-1];
							product_codes[product_codes.length-1].focus();
						</script>
						</table>
						</span>
						<!--/LIST:product_items-->
                          <input type="button" value="   [[.add_item.]]   " onClick="
								var new_element = document.createElement( 'span' );
								new_element.innerHTML = document.getElementById('product_sample').innerHTML;
								node = this.parentNode.insertBefore(new_element, this);
								inputs = document.getElementsByName('delete_item_product');
								inputs[inputs.length-1].span = node;
								id_inputs = document.getElementsByName('product__id[]');
								name_inputs = document.getElementsByName('product__name[]');
								unit_inputs = document.getElementsByName('product__unit[]');
								quantity_inputs = document.getElementsByName('product__quantity[]');
								price_inputs = document.getElementsByName('product__price[]');
								total_inputs = document.getElementsByName('product__total[]');
								
								product_codes = document.getElementsByName('product__id[]');
								product_codes[product_codes.length-1].id_input = id_inputs[id_inputs.length-1];
								product_codes[product_codes.length-1].name_input = name_inputs[name_inputs.length-1];
								product_codes[product_codes.length-1].unit_input = unit_inputs[unit_inputs.length-1];
								product_codes[product_codes.length-1].price_input = price_inputs[price_inputs.length-1];
								product_codes[product_codes.length-1].total_input = total_inputs[total_inputs.length-1];
								product_codes[product_codes.length-1].quantity_input = quantity_inputs[quantity_inputs.length-1];
								
								quantity_inputs[quantity_inputs.length-1].code_input = product_codes[product_codes.length-1];
								product_codes[product_codes.length-1].focus();
							" />
						  <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.sumary.]]</td>
                            <td></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><span id="sumary" class="readonly_class" style="height:20px; width:100px; letter-spacing:1px;">[[|sumary|]]</span></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td width="15%">&nbsp;</td>
                            <td width="25%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.bar_fee_rate.]]</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.bar_fee.]]</td>
                            <td width="5%"></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap"><span id="bar_fee_rate" class="span_input" style="text-align:right; padding-right: 2px; width:20%">5%</span> </td>
                            <td align="right" nowrap="nowrap"><span id="bar_fee" class="readonly_class" style="height:20px; width:100px; letter-spacing:1px;">[[|bar_fee|]]</span></td>
                            <td width="4%"></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right">[[.deposit.]]</td>
                            <td align="right" nowrap="nowrap">[[.sum_total.]]</td>
							<td></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right"><input name="deposit" type="text" id="deposit" style="text-align:right" value="[[|deposit|]]" size="10" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/></td>
                            <td align="right" nowrap="nowrap"><input name="sum_total" type="text" class="readonly_class" style="text-align:right" tabindex="1000" value="[[|sum_total|]]" size="14" readonly="readonly" /></td>
							<td></td>
                          </tr>
                        </table>
                        </span>
                      </fieldset></td>
				  </tr>
					
				</table>
			</td>
			</tr>
			</table>
            <div align="center">					  <input type="submit" name="save" value="    [[.save.]]    ">
											&nbsp;&nbsp;&nbsp;
						<input type="button" value="  [[.back.]]    " onClick="history.go(-1);">
						&nbsp;&nbsp;&nbsp;
						<?php Draw::button(Portal::language('list_title'),URL::build_current());?></div>
			</form>
		</td>
	</tr>
</table>
</div>
</div>