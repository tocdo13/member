<link href="skins/default/hotel.css" rel="stylesheet" type="text/css" />
<script>
	var index = 1;
	var exchange_rate = [[|exchange_rate|]];
	var room_array={
		'':''
	<!--LIST:reservation_room_list-->
		,'[[|reservation_room_list.room_id|]]':{
			'id':'[[|reservation_room_list.id|]]',
			'name':'<?php echo addslashes([[=reservation_room_list.name=]])?>'
		}
	<!--/LIST:reservation_room_list-->
	}
	
	/*================ phan cua karaoke_table ==================*/
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
	/*================ /phan cua karaoke_table ==================*/
	
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
	<span id="mi_table_sample">
	    <span id="input_group_#xxxx#">
		<table width="100%">
			<tr>
			<td width="120">
				<select  name="mi_table[#xxxx#][table_id]" id="table_id_#xxxx#" onChange="update_table(this);">
				<option value=""></option>
				[[|select_table_options|]]
				</select>
			  </td> 
			<td width="100">
				<input name="mi_table[#xxxx#][code]" type="text" id="code_#xxxx#" size="10" class="readonly_input" readonly="readonly">
			</td> 
			<td width="120"><input name="mi_table[#xxxx#][num_people]" type="text" id="num_people_#xxxx#" size="10" class="readonly_input"></td>
			<td><input name="delete_item_table" type="button" value="[[.delete.]]" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_table','#xxxx#','');event.returnValue=false;"></td>
			</tr>
		</table>
		</span>
    </span>
</span>
<span style="display:none">
	<span id="mi_product_sample">
	    <span id="input_group_#xxxx#">    
            <table width="100%" cellpadding="2">
                <tr>
                    <td width="100">
                    	<input name="mi_product[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" size="8" onblur="update_product_checkin(this);calculate_checkin();" AUTOCOMPLETE=OFF>
                    </td>
                    <td width="150"><input name="mi_product[#xxxx#][name]" type="text" id="name_#xxxx#" size="20" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="9%"><input name="mi_product[#xxxx#][unit]" type="text" id="unit_#xxxx#" size="5" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="9%" align="right"><input name="mi_product[#xxxx#][price]" type="text" id="price_#xxxx#" size="10" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="-1"/></td>
                    <td width="10%"><input name="mi_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" size="4" maxlength="3" onKeyUp="update_total_checkin($('product_id_'+this.id.replace('quantity_','')));calculate_checkin();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" /></td>
                    <td width="10%" align="center"><input name="mi_product[#xxxx#][quantity_discount]" type="text" id="quantity_discount_#xxxx#" size="2" onKeyUp="update_total_checkin($('product_id_'+this.id.replace('quantity_discount_','')));calculate_checkin();" style="text-align:center" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/></td>
                    <td width="10%" align="right"><input name="mi_product[#xxxx#][discount]" type="text" id="discount_#xxxx#" onKeyUp="update_total_checkin($('product_id_'+this.id.replace('discount_','')));calculate_checkin();" style="text-align:right; width: 20px;" maxlength="2" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;"/>%</td>                    
                    <td width="15%" align="right"><input name="mi_product[#xxxx#][total]" type="text" id="total_#xxxx#" size="15" readonly="readonly" class="readonly_input" style="text-align:right" tabindex="-1"/></td>
                    <td width="4%"><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','');event.returnValue=false;calculate_checkin();" tabindex="-1"></td>
                </tr>
            </table>
		</span>
	</span>
</span>

<div style="text-align:center;">
<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="text-align:left;">
	<tr>
		<td width="100%">
			<form name="CheckInKaraokeForm" method="post" >
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
						<font style="font-size:20px;text-transform:uppercase;"><b>[[.karaoke_reservation_edit.]]</b></font>
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
				<?php echo Form::$current->error_messages();?><input type="hidden" id="select_karaoke" name="select_karaoke" value="0">
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
					  <td nowrap></td>
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
					  <td nowrap></td>
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
							<span style="padding:5px;" id="mi_table_all_elems">
							<table width="100%" border="0">
								<tr>
								<td width="120">[[.table_id.]]</td> 
								<td width="100">[[.table_code.]]</td>
								<td width="120">[[.table_num_people.]]</td> 
								<td>&nbsp;</td>
								</tr>
							</table>
							</span>
							<input type="button" value="   [[.add_table.]]   " onclick="mi_add_new_row('mi_table');">
							</fieldset>			</td>
					</tr>
					<tr>
                      <td colspan="5"><fieldset>
                        <legend>[[.reservation_product.]]</legend>
                        <span style="padding:5px;" id="mi_product_all_elems">
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
					  </span>
                      <input type="button" value="   [[.add_item.]]   " onclick="mi_add_new_row('mi_product');autocomplete('product_id_'+input_count);">                      
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
                             <td align="right" nowrap="nowrap">[[.summary.]] </td>
                             <td align="right" nowrap="nowrap"><input name="discount" type="text" id="discount" style="text-align:right" value="[[|discount|]]" size="10" class="readonly_class" readonly="readonly" /></td>
                             <td align="right" nowrap="nowrap">
							 	<span id="summary" class="readonly_class" style="height:20px; width:100px; letter-spacing:1px;">[[|summary|]]</span></td>
                             <td>&nbsp;</td>
                           </tr>
						  <tr>
						    <td width="16%" align="right">&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><input type="checkbox" id="cb_karaokefee" onclick="check_karaokefee(); calculate_checkin();" <?php echo [[=karaoke_fee_rate=]]!=0?' checked':'';?>>[[.karaoke_fee.]]</td>
                            <td align="right" nowrap="nowrap">
								<input name="karaoke_fee" type="text" id="karaoke_fee" size="12" style="text-align:right" value="[[|karaoke_fee|]]" class="readonly_class">
								<input name="karaoke_fee_rate" type="hidden"  id="karaoke_fee_rate" value="[[|karaoke_fee_rate|]]">
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
						    <td align="right" nowrap="nowrap"><input name="total_before_tax" id="total_before_tax" type="text" class="readonly_class" style="text-align:right" tabindex="-1" value="[[|total_before_tax|]]" size="12" readonly="readonly" /></td>
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
                              <input name="tax_rate" type="text" id="tax_rate" maxlength="2" onkeyup="calculate_checkin();" style="text-align:right;width:28px" value="[[|tax_rate|]]" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" />
                              %</td>
                            <td align="right" nowrap="nowrap"><input name="tax" type="text" id="tax" style="text-align:right" value="[[|tax|]]" size="12"/ class="readonly_class" readonly="readonly" /></td>
                            <td></td>
                          </tr>
						  <tr>
						    <td align="right"><input name="prepaid" type="text" id="prepaid" style="text-align:right" value="[[|prepaid|]]" size="10" onkeyup="calculate_checkin();"/></td>
						    <td align="right"><span id="remain_paid" class="readonly_class" style="height:20px;letter-spacing:1px; width:100px">[[|remain_prepaid|]]<br /></span><br />
                            	(<em><span id="remain_paid_vnd"><?php echo System::display_number_report([[=remain_prepaid=]]*[[=exchange_rate=]]);?></span> VND</em> )</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td width="20%" align="right">[[.sum_total.]]</td>
						    <td width="12%" align="right" nowrap="nowrap"><input name="sum_total" type="text" id="sum_total" class="readonly_class" style="text-align:right" tabindex="-1" value="[[|sum_total|]]" size="12" readonly="readonly" /></td>
						    <td></td>
					      </tr>
                        </table>
                      </td>
				  </tr>
				</table>
				<div align="center" style="margin-top:10px;"><input type="submit" name="save" value="    [[.save.]]    ">&nbsp;&nbsp;&nbsp;
                <input type="submit" name="check_in" value="    [[.check_in.]]    ">&nbsp;&nbsp;&nbsp;
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
	jQuery("#"+id).autocomplete({
			url: 'get_product.php?karaoke=1',
			minChars: 0,
			width: 310,
			matchContains: true,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.name;
			}  
	});

}
mi_init_rows('mi_table',
	<?php if(isset($_REQUEST['mi_table']))
	{
		echo String::array2js($_REQUEST['mi_table']);
	}
	else
	{
		echo '[]';
	}
	?>);

mi_init_rows('mi_product',
	<?php if(isset($_REQUEST['mi_product']))
	{
		echo String::array2js($_REQUEST['mi_product']);
	}
	else
	{
		echo '[]';
	}
	?>);
autocomplete('product_id_'+input_count);
</script>
