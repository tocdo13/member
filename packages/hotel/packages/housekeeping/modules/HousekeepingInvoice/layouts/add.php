<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<script> 
	var j =0;
	var room_array={
		'':''
	<!--LIST:rooms-->
		,'[[|rooms.id|]]':{
			'name':'[[|rooms.name|]]',
			'agent_name':'[[|rooms.agent_name|]]'
		}
	<!--/LIST:rooms-->
	};
	var exchange_rates = {
		'':''
	<!--LIST:currencies-->
		,'[[|currencies.id|]]':[[|currencies.exchange|]]
	<!--/LIST:currencies-->
	};
	old_currency = '[[|currency_id|]]';
</script>
<span style="display:none">
	<span id="mi_housekeeping_invoice_detail_sample">
		<span id="input_group_#xxxx#">
			<input  name="mi_housekeeping_invoice_detail[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input">
				<select  name="mi_housekeeping_invoice_detail[#xxxx#][product_id]" style="width:80px;" onchange="
					if(typeof(services[this.value])!='undefined')
					{
						$('service_name_#xxxx#').value = services[this.value].name;
						$('price_#xxxx#').value=to_vnnumeric((services[this.value].price*exchange_rates[services[this.value].currency_id])/(exchange_rates[$('currency_id').value]?exchange_rates[$('currency_id').value]:1));
					}
					recalculate_housekeeping_invoice_detail();" id="product_id_#xxxx#"><option value=""></option>
					[[|product_id_options|]]
				</select>
			</span><span class="multi-input">
					<input  style="width:150px;" type="text" id="service_name_#xxxx#">
			</span><span class="multi-input">
					<input  name="mi_housekeeping_invoice_detail[#xxxx#][detail]" style="width:180px;" type="text" id="detail_#xxxx#">
			</span><span class="multi-input">
					<input  name="mi_housekeeping_invoice_detail[#xxxx#][price]" style="width:100px;" type="text" id="price_#xxxx#" onKeyUp="recalculate_housekeeping_invoice_detail()" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44)event.returnValue=false;">
			</span><span class="multi-input">
					<input  name="mi_housekeeping_invoice_detail[#xxxx#][quantity]" value="1" style="width:50px;" type="text" id="quantity_#xxxx#" onKeyUp="recalculate_housekeeping_invoice_detail()" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44)event.returnValue=false;">
			</span><span class="multi-input_calculated">
				<span style="width:100px;float:left;" id="total_#xxxx#">&nbsp;</span>
			</span>
			<span class="multi-input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_housekeeping_invoice_detail','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br clear="all">
		</span>
	</span> 
</span>
<script>
function update_currency()
{
	var columns=all_forms['mi_housekeeping_invoice_detail'];
	for(var i in columns)
	{
		if(is_numeric(getElemValue('price_'+columns[i])))
		{
			$('price_'+columns[i]).value=
				number_format(to_numeric(getElemValue('price_'+columns[i]))*exchange_rates[old_currency]/(exchange_rates[$('currency_id').value]?exchange_rates[$('currency_id').value]:1));
		}
	}
	old_currency = $('currency_id').value;
	recalculate_housekeeping_invoice_detail();
}
function recalculate_housekeeping_invoice_detail(discount_percent)
{
	var columns=all_forms['mi_housekeeping_invoice_detail'];
	for(var i in columns)
	{
		if(1 && is_numeric(getElemValue('price_'+columns[i])) && is_numeric(getElemValue('quantity_'+columns[i])))
		{
			$('total_'+columns[i]).innerHTML=
				number_format(to_numeric(getElemValue('price_'+columns[i]))*to_numeric(getElemValue('quantity_'+columns[i])));
		}
	}
	var total = 0;
	for(var i in columns)
	{
		if(1 && is_numeric(getElemValue('price_'+columns[i])) && is_numeric(getElemValue('quantity_'+columns[i])))
		{
			var value=to_numeric(getElemValue('price_'+columns[i]))*to_numeric(getElemValue('quantity_'+columns[i]));
			total=(total*1)+(value*1);
		}
	}
	$('housekeeping_invoice_detail_total_total').innerHTML = number_format(total); 
	$('total_fee').value=5*parseFloat(total)/100;
	$('total_before_tax').value = parseFloat(total)+parseFloat($('total_fee').value);
	tax_rate = to_numeric(getElemValue('tax_rate'));
	$('tax').value = number_format(parseFloat($('total_before_tax').value)*tax_rate/100);
	total = parseFloat($('total_before_tax').value)*(1 + tax_rate/100);
	if(discount_percent)
	{
		$('discount').value=(total*discount_percent)/100;
	}
	$('total').value = number_format(total-(is_numeric(to_numeric(getElemValue('discount')))?to_numeric(getElemValue('discount')):0));
	$('remain').innerHTML = number_format(total-(is_numeric(to_numeric(getElemValue('prepaid')))?to_numeric(getElemValue('prepaid')):0));
}
</script> 
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%">
			<form name="AddHousekeepingInvoiceForm" method="post" >
			<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25" bgcolor="#EFEFEF">
				<td align="center" bgcolor="#97D88B">
				<table width="100%" border="0">
				<tr>
					<td width="25%">
						<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.date.]]</td>
                            <td>: [[|date|]]</td>
                          </tr>
                        </table>
				    </td>
					<td width="50%" align="center">
						<font style="font-size:20px"><b>[[.add_title.]]</b></font>
						&nbsp;<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#add">
						<img src="packages/hotel/skins/default/images/scr_symQuestion.gif"/>
					</a>
				    </td>
					<td width="25%" align="right">
						<!--<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.currency.]]&nbsp;&nbsp;</td>
                            <td>: <select name="currency_id" id="currency_id" onchange="update_currency();"></select></td>
                          </tr>
                        </table>-->
						<input  type="hidden" name="currency_id" id="currency_id" value="VND">
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr bgcolor="#F4F4F4">
			<td bgcolor="#EEEEEE">
				<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
				<input type="hidden" id="select_bar" name="select_bar" value="0">
				<table width="100%">
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td width="22%" nowrap>&nbsp;</td>
					  <td width="41%" nowrap>&nbsp;</td>
					  <td width="35%">&nbsp;</td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td colspan="2" nowrap>[[.minibar_id.]]</td>
					  <td nowrap>[[.customer_name.]]</td>
					  <td></td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td colspan="2" nowrap><select name="reservation_room_id" id="reservation_room_id" onChange="update_room(this);">
					    </select>
					  </td>
					  <td nowrap><span id="customer_name">&nbsp;</span></td>
					  <td></td>
					  <td>&nbsp;</td>
				    </tr> 
					<tr>
                      <td colspan="5">
                        <fieldset><legend>[[.housekeeping_invoice_detail.]]</legend>
						<span id="mi_housekeeping_invoice_detail_all_elems">
						<span>
                            <span class="multi-input_header"><span style="width:85px;float:left;">[[.code.]]</span></span>
                            <span class="multi-input_header"><span style="width:160px;float:left;">[[.service_name.]]</span></span>
                            <span class="multi-input_header"><span style="width:190px;float:left;">[[.detail.]]</span></span>
                            <span class="multi-input_header"><span style="width:105px;float:left;">[[.price.]]</span></span>
                            <span class="multi-input_header"><span style="width:80px;float:left;">[[.quantity.]]</span></span>
                            <span class="multi-input_header"><span style="width:100px;float:left;">[[.total.]]</span></span>
                            <span class="multi-input_header"><span style="width:20px;float:left;"><img src="skins/default/images/spacer.gif"/></span></span>
                            <br clear="all">
						</span>						
						</span>
						<span>
							<span class="multi-input-total-blank"><span style="width:230px;text-align:right;"><input type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_housekeeping_invoice_detail');"></span></span>
							<span class="multi-input_total_blank"><span style="width:180px;text-align:right;">&nbsp;</span></span>
							<span class="multi-input_total_blank"><span style="width:150px;text-align:right;">[[.service_total.]]</span></span>
							<span><span class="multi-input_total"><span style="width:100px;" id="housekeeping_invoice_detail_total_total">&nbsp;</span></span>
							<span class="multi-input_total_blank"><span style="width:20px;">&nbsp;</span></span><br>
						</span>
						  <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap"><span style="width:115;">[[.total_fee.]]
                              <input name="fee_rate" type="text" id="fee_rate" style="width:24px;" maxlength="2" value="5" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" onkeyup="recalculate_housekeeping_invoice_detail();" />
                            %</span></td>
                            <td align="right" nowrap="nowrap"><input name="total_fee" type="text" id="total_fee" size="10" maxlength="8" style="text-align:right" class="readonly_class" readonly="readonly" /></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.total_before_tax.]]</td>
                            <td align="right" nowrap="nowrap"><input name="total_before_tax" type="text" id="total_before_tax" size="10" maxlength="8" style="text-align:right" class="readonly_class" readonly="readonly" /></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td width="15%">&nbsp;</td>
                            <td width="25%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><span style="width:115;"> [[.vat_tax.]]
                              <input name="tax_rate" type="text" id="tax_rate" style="width:24px;" maxlength="2" value="10" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" onkeyup="recalculate_housekeeping_invoice_detail();" />
                            </span>%</td>
                            <td align="right" nowrap="nowrap">
								<input name="tax" type="text" id="tax" size="10" maxlength="8" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" onkeyup="recalculate_housekeeping_invoice_detail();" style="text-align:right" /></td>
                            <td width="5%"></td>
                          </tr>
						  <!--<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.discount_by_percent.]]</td>
							<td align="right" nowrap="nowrap"><input name="discount_percent" type="text" id="discount_percent" size="10" class="readonly_class" style="text-align:right" onkeyup="recalculate_housekeeping_invoice_detail(this.value);"/></td>
							<td></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.discount.]]</td>
							<td align="right" nowrap="nowrap"><input name="discount" type="text" id="discount" size="10" class="readonly_class" style="text-align:right" onkeyup="recalculate_housekeeping_invoice_detail();"/></td>
							<td></td>
                          </tr>-->
						  <input name="discount_percent" type="text" id="discount_percent" />
						  <input name="discount" type="hidden" id="discount"/>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.total.]]</td>
							<td align="right" nowrap="nowrap"><input name="total" type="text" id="total" size="10" class="readonly_class" style="text-align:right" readonly="readonly" /></td>
							<td></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right">[[.prepaid.]]</td>
                            <td align="right" nowrap="nowrap">[[.remain.]]</td>
                            <td></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right"><input name="prepaid" type="text" id="prepaid" size="10" maxlength="8" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" onkeyup="recalculate_housekeeping_invoice_detail();" style="text-align:right"/></td>
                            <td align="right" nowrap="nowrap"><span id="remain" class="multi-input_total_blank">&nbsp;</span></td>
                            <td ></td>
                          </tr>
                        </table>
                        </span>
                      </fieldset></td>
				  </tr>
					<tr bgcolor="#EFEFEF">
                      <td colspan="5" bgcolor="#EEEEEE"><table>
                        <tr>
                          <td><?php Draw::button(Portal::language('save'),false,false,true,'AddHousekeepingInvoiceForm');?></td>
                          <td><p>
                            <?php Draw::button(Portal::language('list_title'),URL::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_minibar_id', 'housekeeping_invoice_employee_id', 
							'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',      
							)));?></p></td>
                        </tr>
                      </table></td>
				  </tr> 
					<tr bgcolor="#EFEFEF">
					  <td colspan="5" bgcolor="#EEEEEE">&nbsp;</td>
				  </tr>
					<tr bgcolor="#EFEFEF">
					  <td colspan="5" bgcolor="#EEEEEE">&nbsp;</td>
				  </tr>
				</table>
			</td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<?php HousekeepingInvoice::create_js_variables();?><script>
mi_init_rows('mi_housekeeping_invoice_detail',
	<?php if(isset($_REQUEST['mi_housekeeping_invoice_detail']))
	{
		echo String::array2js($_REQUEST['mi_housekeeping_invoice_detail']);
	}
	else
	{
		echo '[]';
	}
	?>);
recalculate_housekeeping_invoice_detail(); 
function get_room(minibar_id)
{
	if(typeof (room_array[minibar_id])!= 'undefined')
	{
		return room_array[minibar_id];
	}
	return false;
}
function update_room(obj)
{
	var room = get_room(obj.value);
	if(room)
	{
		$('customer_name').innerText = room.agent_name;
	}
	else
	{
		document.getElementById('customer_name').innerText = '';
	}
}
update_room($('reservation_room_id'));
</script>