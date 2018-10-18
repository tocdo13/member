<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center" bgcolor="#EFEFEF">
				<table cellpadding="0" width="100%" border="0">
				<tr>
					<td width="25%">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.date.]]</td>
                            <td>: <font style="font-size:14px;"><strong>[[|date|]]</strong></font></td>
                          </tr>
                          <tr>
                            <td>[[.code.]]</td>
                            <td>: <font style="font-size:14px;"><strong>[[|order_id|]]</strong></font></td>
                          </tr>
                        </table>
				    </td>
					<td width="50%" align="center">
						<font style="font-size:20px;text-transform:uppercase;"><b>[[.BAR_RESERVATION.]]</b></font>					
					</td>
					<td width="25%">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right">[[.status.]]</td>
                          </tr>
                          <tr>
                            <td align="right"><font style="font-size:14px;"><strong>[[|status|]]</strong></font></td>
                          </tr>
                        </table>
				    </td>	
				</tr>
				</table>
			  </td>
			</tr>
			<tr bgcolor="#F4F4F4">
			<td bgcolor="#FFFFFF">
				<table width="100%">
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td>&nbsp;</td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>[[.agent_name.]]</td>
					  <td nowrap>[[.agent_address.]]</td>
					  <td>[[.agent_phone.]]</td>
					  <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_name" type="text" id="agent_name" size="27" value="[[|agent_name|]]" class="room_input_class" readonly="readonly" />
				      &nbsp;</td>
					  <td nowrap><input name="agent_address" type="text" id="agent_address" value="[[|agent_address|]]" class="room_input_class" size="58" readonly="readonly" /></td>
					  <td><input name="agent_phone" type="text" id="agent_phone" value="[[|agent_phone|]]" class="room_input_class" size="15" readonly="readonly" /></td>
					  <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td>[[.agent_fax.]]</td>
					  <td nowrap="nowrap">[[.receiver_name.]]</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td><input name="agent_fax" type="text" id="agent_fax" value="[[|agent_fax|]]" class="room_input_class" size="15" readonly="readonly" /></td>
					  <td nowrap="nowrap"><input name="receiver_name" type="text" id="receiver_name" size="27" value="[[|receiver_name|]]" class="room_input_class" readonly="readonly" />
  &nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>
					  	<table>
						<tr>
							<td width="140">[[.arrival_time.]]</td>
							<td width="120">[[.arrival_time_in.]]</td>
							<td width="120">[[.arrival_time_out.]]</td>
						</tr>
						</table>					  </td>
					  <td><!--IF:check_num_table(MAP['num_table']!=0)-->[[.num_table.]]<!--/IF:check_num_table--></td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>
					  <table>
						<tr>
							<td width="140">
								<input name="arrival_date" type="text" id="arrival_time" size="12" value="[[|arrival_date|]]" class="room_input_class" readonly="readonly"/>							
							</td>
							<td width="120">
								<input name="time_in1" type="text" style="width:17px" value="[[|time_in_hour|]]" class="room_input_class" readonly="readonly" />
								<input name="time_in2" type="text" style="width:7px" value=":" class="room_input_class" readonly="readonly"/>
								<input name="time_in3" type="text" style="width:17px" value="[[|time_in_munite|]]" class="room_input_class" readonly="readonly" />							
							</td>
							<td width="120">
								<input name="time_out1" type="text" style="width:17px" value="[[|time_out_hour|]]" class="room_input_class" readonly="readonly" />
								<input name="time_out2" type="text" style="width:7px" value=":" class="room_input_class" readonly="readonly"/>
								<input name="time_out3" type="text" style="width:17px" value="[[|time_out_munite|]]" class="room_input_class" readonly="readonly" />							
							</td>
						</tr>
					  </table>					  </td>
					  <td><!--IF:check_num_table(MAP['num_table']!=0)--><input name="num_table" type="text" id="num_table" value="[[|num_table|]]" size="7" class="room_input_class" readonly="readonly" /><!--/IF:check_num_table--></td>
					  <td width="1%">&nbsp;</td>
				    </tr>
					<!--IF:check_right(defined('HOTEL_STAFF'))-->
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.room_id.]]</td>
					  <td nowrap>[[.reservation_id.]]</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="room_name" type="text" id="room_name" value="[[|room_name|]]" size="20" class="room_input_class" readonly="readonly" /></td>
					  <td nowrap><input name="reservation_name" type="text" id="reservation_name" value="[[|reservation_name|]]" size="30" class="room_input_class" readonly="readonly" /></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr> 
					<!--/IF:check_right-->
					<tr>
						<td colspan="5">
							<fieldset>
							<legend class="title">[[.reservation_table.]]</legend>
							<span style="padding:5px;">
							<table border="0">
								<tr>
								<td width="105">[[.table_name.]]</td> 
								<td width="100">[[.table_code.]]</td>
								<td width="120" nowrap="nowrap">[[.table_num_people.]]</td> 
								</tr>
							</table>
							<!--LIST:table_items-->
							<span id="span_table_[[|table_items.id|]]">
							<table cellpadding="3">
								<tr>
									<td width="100"><input name="table__name[]" type="text" style="width:100%" value="[[|table_items.name|]]" class="room_input_class" readonly="readonly" /></td>
									<td width="100"><input name="table__code[]" type="text" style="width:100%" value="[[|table_items.code|]]" class="room_input_class" readonly="readonly" /></td>
									<td width="120"><input name="table__num_people[]" type="text" size="10" value="[[|table_items.num_people|]]" class="room_input_class" readonly="readonly"/></td>
								</tr>
							</table>
							</span>
							<!--/LIST:table_items-->
							</span>
							</fieldset>						</td>
					</tr>
					<tr>
                      <td colspan="5"><fieldset>
                        <legend class="title">[[.reservation_product.]]</legend>
                        <span style="padding:5px;">
                        <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td width="13%">[[.product_code.]] </td>
                            <td width="22%">[[.product_name.]]</td>
                            <td width="9%">[[.product_unit.]]</td>
                            <td width="7%" nowrap="nowrap">[[.product_quantity.]]</td>
                            <td width="9%" align="right" nowrap="nowrap">[[.product_price.]]</td>
                            <td width="17%" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="5%"></td>
                          </tr>
                        </table>
						<!--LIST:product_items-->
						<span id="span_product_[[|product_items.product__id|]]">
						<table width="100%">
						  <tr>
							<td width="15%"><input name="product__id[]" type="text" class="room_input_class" readonly="readonly" value="[[|product_items.product__id|]]" size="7" /></td>
							<td width="28%"><input name="product__name[]" type="text" class="room_input_class" tabindex="1000" value="[[|product_items.product__name|]]" size="28" readonly="readonly"/></td>
							<td width="10%"><input name="product__unit[]" type="text" class="room_input_class" tabindex="1000" value="[[|product_items.product__unit|]]" size="6" readonly="readonly"/></td>
							<td width="10%"><input name="product__quantity[]" type="text" class="room_input_class" readonly="readonly" value="[[|product_items.product__quantity|]]" size="5" style="text-align:center" /></td>
							<td width="15%"><input name="product__price[]" type="text" class="room_input_class" readonly="readonly" style="text-align:right" tabindex="1000" value="[[|product_items.price|]]" size="15"/></td>
							<td width="20%" align="right" style="padding-right: 20px "><input name="product__total[]" type="text" class="room_input_class" readonly="readonly" style="text-align:right" tabindex="1000" value="[[|product_items.product__total|]]" size="18"/></td>
							<td width="5%"></td>
						  </tr>
						</table>
						</span>
						<!--/LIST:product_items-->
						  <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td width="15%">&nbsp;</td>
                            <td width="28%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="7%">&nbsp;</td>
                            <td width="15%" align="right">[[.summary.]]</td>
							<td width="20%" align="right" nowrap="nowrap"><input name="summary" type="text" class="room_input_class" id="summary" style="text-align:right" tabindex="1000" value="[[|summary|]]" size="17" readonly="readonly"/></td>
							<td width="5%"></td>
                          </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right">[[.bar_fee.]] (5%) </td>
							<td align="right" nowrap="nowrap"><input name="bar_fee" type="text" size="17" style="text-align:right" value="[[|bar_fee|]]" class="room_input_class" readonly="readonly"/></td>
							<td width="5%"></td>
						  </tr>
						  <tr>
                            <td>[[.currency.]]</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.deposit.]]</td>
                            <td align="right" nowrap="nowrap">[[.sum_total.]]</td>
							<td width="5%"></td>
                          </tr>
						  <tr>
                            <td><font style="font-size:13px; font-weight:bold"><?php echo HOTEL_CURRENCY;?></font></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right"><input name="deposit" type="text" id="deposit" style="text-align:right" value="[[|deposit|]]" readonly="readonly" class="room_input_class" size="15"/></td>
                            <td align="right" nowrap="nowrap">
								<input name="sum_total" type="text" class="room_input_class" id="sum_total" style="text-align:right" tabindex="1000" value="[[|sum_total|]]" size="17" readonly="readonly"/>							</td>
							<td width="5%"></td>
                          </tr>
                        </table>
                        </span>
                      </fieldset></td>
				  </tr>
					<tr bgcolor="#EFEFEF">
                      <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
				  </tr> 
					<tr bgcolor="#EFEFEF">
					  <td colspan="5" bgcolor="#FFFFFF">[[.note.]]</td>
				  </tr>
					<tr bgcolor="#EFEFEF">
					  <td colspan="5" bgcolor="#FFFFFF">
					  <div style="width:100%;vertical-align:middle;">
						<textarea name="note" id="note" style="width:100%" rows="4" readonly="readonly">[[|note|]]</textarea>
					  </div>					  </td>
				  </tr>
				</table>
				<table cellpadding=5 onDblClick="this.style.display='none'">
					<tr>
						<td><?php Draw::button(Portal::language('list_title'),URL::build_current(),'button-medium');?></td>
						<!--IF:check_status(MAP['status']=='RESERVATION')-->
							<td><?php Draw::button(Portal::language('check_in'),URL::build_current(array('cmd'=>'check_in','id','act'=>'checkin')),'button-medium-save');?></td>
						<!--/IF:check_status-->
						<!--IF:check_status(MAP['status']=='CHECKIN')-->
							<td><?php Draw::button(Portal::language('check_out'),URL::build_current(array('cmd'=>'detail','id','act'=>'checkout')),'button-medium-save');?></td>
							<td><?php Draw::button(Portal::language('edit'),URL::build_current(array('cmd'=>'check_in','id'=>Url::get('id'))),'button-medium-edit');?></td>
						<!--ELSE-->
							<!--IF:check_status1(MAP['status']=='CANCEL')-->
							<td><?php Draw::button(Portal::language('set_reservation'),URL::build_current(array('cmd'=>'recover','id'=>Url::get('id'))));?></td>
							<!--ELSE-->
							<td><?php Draw::button(Portal::language('cancel'),URL::build_current(array('cmd'=>'detail','id'=>Url::get('id'),'act'=>'cancel')),'button-medium-delete');?></td>
							<!--/IF:check_status1-->
							<td><?php Draw::button(Portal::language('edit'),URL::build_current(array('cmd'=>'edit','id'=>Url::get('id'))),'button-medium-edit');?></td>
						<!--/IF:check_status-->
						<td><?php Draw::button(Portal::language('print'),URL::build_current(array('cmd'=>'detail','id','act'=>'print','curr')),'button-medium');?></td>
						<td><?php Draw::button(Portal::language('delete'),URL::build_current(array('cmd'=>'delete','id'=>Url::get('id'))),'button-medium-delete');?></td>
					</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
</div>