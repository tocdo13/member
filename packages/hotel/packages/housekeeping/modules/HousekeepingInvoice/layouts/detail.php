<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
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
                            <td width="30px" nowrap="nowrap">[[.date.]]</td>
                            <td>: [[|date|]]</td>
                          </tr>
                        </table>
				    </td>
					<td width="50%" align="center">
						<font style="font-size:20px"><b>[[.detail_title.]]</b></font>					
					</td>
					<td width="25%" align="right">
						<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#detail">
						<img src="packages/hotel/skins/default/images/scr_symQuestion.gif"/>
					</a>
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
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.reservation_room_id.]]</td>
					  <td nowrap>[[.room_id.]]</td>
					  <td nowrap="nowrap">[[.user.]]</td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="reservation_name" type="text" id="reservation_name" value="[[|customer_name|]]" size="30" class="room_input_class" readonly="readonly" /></td>
					  <td nowrap><input name="room_name" type="text" id="room_name" value="[[|room_id|]]" size="20" class="room_input_class" readonly="readonly" /></td>
					  <td><input name="user_name" type="text" id="user_name" value="[[|user_name|]]" size="24" class="room_input_class" readonly="readonly" /></td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap="nowrap">[[.total_before_tax.]]</td>
					  <td nowrap="nowrap">[[.vat_tax.]] ([[|tax_rate|]]%) </td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap="nowrap"><input name="total_before_tax" type="text" class="room_input_class" id="total_before_tax" tabindex="1000" value="[[|total_before_tax|]]" size="15" readonly="readonly"/></td>
					  <td nowrap="nowrap"><input name="tax" type="text" class="room_input_class" id="tax" tabindex="1000" value="[[|tax|]]" size="15" readonly="readonly"/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap>[[.total.]]</td>
					  <td nowrap>[[.prepaid.]]</td>
					  <td>[[.remain.]]</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="sum_total" type="text" class="room_input_class" id="sum_total" tabindex="1000" value="[[|total|]]" size="15" readonly="readonly"/></td>
					  <td nowrap><input name="prepaid" type="text" class="room_input_class" id="prepaid" tabindex="1000" value="[[|prepaid|]]" size="15" readonly="readonly"/></td>
					  <td><input name="remain" type="text" class="room_input_class" id="remain" tabindex="1000" value="[[|remain|]]" size="15" readonly="readonly"/></td>
					  <td>&nbsp;</td>
				  </tr> 
					<tr>
                      <td colspan="5">
					  <fieldset><legend>[[.housekeeping_invoice_detail.]]</legend>
						<table width="100%">
							<tr>
								<th width="100" nowrap align="left" valign="top">
									[[.code.]]</th>
								<th width="100" nowrap align="left" valign="top">
									[[.service_name.]]</th>
								<th width="100" nowrap align="left" valign="top">
									[[.service_detail.]]</th>
								<th width="100" nowrap valign="top" align="right">
									[[.price.]] ([[|currency_id|]])</th>
								<th width="50" nowrap align="left" valign="top">
									[[.quantity.]]</th>
							</tr>
							<!--LIST:housekeeping_invoice_detail_items-->
							<tr>
								<td width="100" align="left" valign="top">
									<input name="product__code[]" type="text" class="room_input_class" readonly="readonly" value="[[|housekeeping_invoice_detail_items.product_id|]]" size="20" /></td>
								<td width="100" align="left" valign="top">
									<input name="product__name[]" type="text" class="room_input_class" readonly="readonly" value="[[|housekeeping_invoice_detail_items.service_name|]]" size="30" /></td>
								<td width="100" align="left" valign="top">
									<input name="product__detail[]" type="text" class="room_input_class" readonly="readonly" value="[[|housekeeping_invoice_detail_items.detail|]]" size="50" /></td>
								<td width="100" align="right" valign="top">
									<input name="product__price[]" type="text" class="room_input_class" readonly="readonly" style="text-align:right" tabindex="1000" value="[[|housekeeping_invoice_detail_items.price|]]" size="12"/></td>
								<td width="50" align="left" valign="top">
									<input name="product__quantity[]" type="text" class="room_input_class" readonly="readonly" value="[[|housekeeping_invoice_detail_items.quantity|]]" size="5" style="text-align:center" /></td>
							</tr>
							<!--/LIST:housekeeping_invoice_detail_items-->
						</table>
					  </fieldset>					</td>
				</table>
				<table cellpadding=5 onDblClick="this.style.display='none'">
				<tr><td>
				<?php Draw::button(Portal::language('list_title'),URL::build_current(array('reservation_room_id', 'room_id', 'user_id', 
					'time_start','time_end', 'total_start','total_end',      
				)));?></td>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{
				?><td>
				<?php Draw::button(Portal::language('edit'),URL::build_current(array('reservation_room_id', 'room_id', 'user_id', 
					'time_start','time_end', 'total_start','total_end',      
				)+array('cmd'=>'edit','id'=>$_REQUEST['id'])));?></td>
				<?php
				}
				if(User::can_delete(false,ANY_CATEGORY))
				{
				?><td>
				<?php Draw::button(Portal::language('delete'),URL::build_current(array('reservation_room_id', 'housekeeping_invoice_room_id', 'user_id', 
					'time_start','time_end', 'total_start','total_end',      
				)+array('cmd'=>'delete','id'=>$_REQUEST['id'])));?></td>
				<?php
				}
				?></tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<script>full_screen();</script>