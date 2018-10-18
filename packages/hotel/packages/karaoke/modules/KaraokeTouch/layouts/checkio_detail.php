<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td align="center">
			<table cellSpacing=0 cellPadding=5 border=0 width="980" style="border-collapse:collapse;text-align:left;margin-top:3px;border:1px solid #CCCCCC;" bordercolor="#97ADC5">
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
						<font style="font-size:20px;text-transform:uppercase;"><b>
							<!--IF:check_status([[=status=]]=='CHECKIN')-->
							[[.karaoke_check_in.]]
							<!--ELSE-->
							[[.karaoke_check_out.]]
							<!--/IF:check_status-->
						</b></font>					
					</td>
					<td width="25%" align="right">
						<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.currency.]]&nbsp;&nbsp;</td>
                            <td>: 
							<?php echo HOTEL_CURRENCY;?>
                            <input type="hidden" name="currency" value="<?php echo HOTEL_CURRENCY;?>" />
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
					  <td nowrap>[[.agent_fax.]]</td>
					  <td nowrap>[[.receiver_name.]]</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_fax" type="text" id="agent_fax" value="[[|agent_fax|]]" class="room_input_class" size="15" readonly="readonly" /></td>
					  <td nowrap><input name="receiver_name" type="text" id="receiver_name" size="27" value="[[|receiver_name|]]" class="room_input_class" readonly="readonly" /></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><table>
                        <tr>
                          <td width="100">[[.time_in.]]</td>
                          <td width="80">[[.time_out.]]</td>
                        </tr>
                      </table></td>
					  <td><!--IF:check_num_table(MAP['num_table']!=0)-->[[.num_table.]]<!--/IF:check_num_table--></td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><table>
                        <tr>
                          <td width="100"><input name="time_in1" type="text" style="width:19px" value="[[|time_in_hour|]]" class="room_input_class" readonly="readonly" />
                              <input name="time_in2" type="text" style="width:7px" value=":" class="room_input_class" readonly="readonly" />
                              <input name="time_in3" type="text" style="width:19px" value="[[|time_in_munite|]]" class="room_input_class" readonly="readonly" />                          </td>
                          <td width="80">
						  	  <!--IF:check_status(MAP['status']=='CHECKOUT')-->
							  <input name="time_in1" type="text" style="width:19px" value="[[|time_out_hour|]]" class="room_input_class" readonly="readonly" />
                              <input name="time_in2" type="text" style="width:7px" value=":" class="room_input_class" readonly="readonly" />
                              <input name="time_in3" type="text" style="width:19px" value="[[|time_out_munite|]]" class="room_input_class" readonly="readonly" />
							  <!--/IF:check_status-->						  </td>
                        </tr>
                      </table></td>
					  <td><!--IF:check_num_table(MAP['num_table']!=0)--><input name="num_table" type="text" id="num_table" value="[[|num_table|]]" size="7" class="room_input_class" readonly="readonly" /><!--/IF:check_num_table--></td>
					  <td width="1%">&nbsp;</td>
				    </tr>
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
					  <td nowrap><input name="reservation_name" type="text" id="reservation_name" value="[[|reservation_name|]]" size="37" class="room_input_class" readonly="readonly" /></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr> 
					<!--IF:check_listtable(MAP['tables_num']!=0)-->
					<tr>
						<td colspan="5">
							<fieldset>
							<legend>[[.reservation_table.]]</legend>
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
							<table cellpadding="2">
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
					<!--/IF:check_listtable-->
					<tr>
                      <td colspan="5">
						<!--IF:check_listproduct(MAP['product_num']!=0)-->
						<fieldset>
                        <legend>[[.reservation_product.]]</legend>
                        <span style="padding:5px;">
                        <table width="100%" border="0">
                          <tr>
                            <td width="10%"> [[.product_code.]] </td>
                            <td width="24%">[[.product_name.]]</td>
                            <td width="8%" nowrap="nowrap">[[.product_unit.]]</td>
							<td width="10%" align="right" nowrap="nowrap">[[.product_price.]]</td>
                            <td width="12%" align="center" nowrap="nowrap">[[.product_quantity.]]</td>
							<td width="8%" nowrap="nowrap">[[.product_quantity_discount.]]</td>
                            <td width="10%" align="right" nowrap="nowrap">[[.product_discount.]]</td>
                            <td width="15%" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="6%">&nbsp;</td>
                          </tr>
                        </table>
						<!--LIST:product_items-->
						<span id="span_product_[[|product_items.product__id|]]">
						<table width="100%">
						  <tr>
							<td width="10%"><input name="product__id[]" type="text" class="room_input_class" value="[[|product_items.product__id|]]" size="6" /></td>
							<td width="25%"><input name="product__name[]" type="text" class="room_input_class" tabindex="1000" value="[[|product_items.product__name|]]" size="25" readonly="readonly"/></td>
							<td width="9%"><input name="product__unit[]" type="text" class="room_input_class" tabindex="1000" value="[[|product_items.product__unit|]]" size="5" readonly="readonly"/></td>
							<td width="9%" align="right"><input name="product__price[]" type="text" class="room_input_class" style="text-align:right" tabindex="1000" value="[[|product_items.product__price|]]" size="15" readonly="readonly"/></td>
							<td width="10%" align="center"><input name="product__quantity[]" type="text" class="room_input_class" value="[[|product_items.product__quantity|]]" size="2" style="text-align:center" /></td>
							<td width="10%" align="center"><input name="product__quantity_discount[]" type="text" class="room_input_class" value="[[|product_items.product__quantity_discount|]]" size="2" style="text-align:center" /></td>
							<td width="8%" align="right"><input name="product__discount[]" type="text" class="room_input_class" value="[[|product_items.product__discount|]]" size="5" style="text-align:right" /></td>
							<td width="25%" align="right"><input name="product__total[]" type="text" class="room_input_class" style="text-align:right" tabindex="1000" value="[[|product_items.product__total|]]" size="20" readonly="readonly"/></td>
							<td width="4%"></td>
						  </tr>
						</table>
						</span>
						<!--/LIST:product_items-->
						</span>
	                    </fieldset>
						<!--/IF:check_listproduct-->  
						  <table width="100%" border="0" cellpadding="3">
                           <tr>
                            <td width="16%"></td>
                            <td width="16%"></td>
                            <td width="2%" nowrap="nowrap"></td>
							<td width="9%" align="right" nowrap="nowrap"></td>
                            <td width="5%" nowrap="nowrap"></td>
							<td width="12%" nowrap="nowrap"></td>
                            <td width="15%" align="right" nowrap="nowrap">[[.discount.]]</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.summary.]]</td>
							<td width="5%">&nbsp;</td>
                          </tr>
                           <tr>
                             <td></td>
                             <td></td>
                             <td nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"></td>
                             <td nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"><span id="total" class="room_input_class" style="height:20px; width:70px; letter-spacing:1px;">[[|total_discount|]]</span></td>
                             <td align="right" nowrap="nowrap">
							 	<span id="total" class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;">[[|amount|]]</span>							 </td>
                             <td>&nbsp;</td>
                           </tr>
                          <!--IF:cond_fee(String::to_number([[=karaoke_fee=]]))-->
						  <tr>
						    <td align="right" nowrap="nowrap"></td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.karaoke_fee.]] (5%)</td>
                            <td align="right"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;">[[|karaoke_fee|]]</span></td>
                            <td></td>
                          </tr>
						  <tr>
						    <td align="right"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"></span></td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td align="right" nowrap="nowrap">[[.total_before_tax.]]</td>
						    <td align="right" nowrap="nowrap"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;">[[|total_before_tax|]]</span></td>
						    <td></td>
						    </tr>
                          <!--/IF:cond_fee-->
						  <tr>
						    <td width="16%" align="right">[[.deposit.]]</td>
						    <td width="16%" align="right">[[.remain_paid.]]</td>
						    <td>&nbsp;</td>
                            <td rowspan="2"><fieldset style="height:50px; text-align:center; width:250px">
								<legend>[[.payment_result.]]</legend>
								&nbsp;&nbsp;&nbsp;&nbsp;[[|payment_kind|]]&nbsp;&nbsp;&nbsp;&nbsp;
								</fieldset>							
							</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.vat_tax.]] [[|tax_rate|]]%</td>
                            <td align="right" nowrap="nowrap"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;">[[|tax|]]</span> </td>
                            <td></td>
                          </tr>
						  <tr>
						    <td align="right"><span class="room_input_class" id="remain_paid" style="height:20px;letter-spacing:1px; width:100px"><strong>[[|prepaid|]]</strong></span> </td>
						    <td align="right"><span class="room_input_class" id="remain_paid" style="height:20px;letter-spacing:1px; width:100px"><strong>[[|remain_prepaid|]]</strong></span> </td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td align="right">[[.sum_total.]]</td>
						    <td align="right" nowrap="nowrap"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"><strong>[[|sum_total|]]</strong></span></td>
						    <td></td>
						    </tr>
                        </table>					</td>
				</table>
				<table cellpadding=5 onDblClick="this.style.display='none'">
					<tr>
						<td><?php Draw::button(Portal::language('list_title'),URL::build_current());?></td>
						<!--IF:check_status(MAP['status']=='CHECKIN' and MAP['payment_result'])-->
							<td><?php Draw::button(Portal::language('check_out'),URL::build_current(array('cmd'=>'detail','id','act'=>'checkout')));?></td>
						<!--/IF:check_status-->
						<!--IF:check_printedit((MAP['pkind']==0 and MAP['status']=='CHECKIN') or (MAP['pkind']!=0 and User::can_edit(false,ANY_CATEGORY)))-->	
							<td><?php Draw::button(Portal::language('print'),URL::build_current(array('cmd'=>'detail','id','act'=>'print','curr')));?></td>
							<td><?php Draw::button(Portal::language('print_kitchen'),URL::build_current(array('cmd'=>'detail','id','act'=>'print_kitchen','curr')));?></td>                            
							<td><?php Draw::button(Portal::language('edit'),URL::build_current(array('cmd'=>'check_in','id'=>Url::get('id'))));?></td>
						<!--/IF:check_printedit-->	
						<!--IF:check_delete(User::can_delete(false,ANY_CATEGORY))-->	
						<td><?php Draw::button(Portal::language('delete'),URL::build_current(array('cmd'=>'delete','id'=>Url::get('id'))));?></td>
						<!--/IF:check_delete-->	
					</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
