<link href="skins/default/hotel.css" rel="stylesheet" type="text/css" />
<script>
	var index = 1;
	var room_array={
		'':''
	<!--LIST:reservation_room_list-->
		,'[[|reservation_room_list.id|]]':{
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
			'category_id':'[[|product.category_id|]]',
			'name':'<?php echo addslashes([[=product.name=]])?>',
			'unit':'[[|product.unit_name|]]',
			'quantity':'1',
			'quantity_discount':'0',
			'price':'[[|product.price|]]'
		}
		<!--/LIST:product-->
	}
	var product_items={
		'':''
		<!--LIST:product_items-->
		,'[[|product_items.id|]]':{
			'id':'[[|product_items.id|]]',
			'product_id':'[[|product_items.product_id|]]',			
			'category_id':'[[|product_items.category_id|]]',
			'name':'<?php echo addslashes([[=product_items.name=]])?>',
			'unit':'[[|product_items.unit_name|]]',
			'quantity':'1',
			'quantity_discount':'0',
			'price':'[[|product_items.price|]]'
		}
		<!--/LIST:product_items-->
	}	
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
				<input name="mi_table[#xxxx#][code]" type="text" id="code_#xxxx#" style="width:110px;" class="readonly_input" readonly="readonly">
			</td> 
			<td width="120"><input name="mi_table[#xxxx#][num_people]" type="text" id="num_people_#xxxx#" style="width:110px;"></td>
			<td width="120"><input name="mi_table[#xxxx#][order_person]" type="text" id="order_person_#xxxx#" style="width:110px;"></td>
			<td><input name="delete_item_table" type="button" value="[[.delete.]]" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_table','#xxxx#','');event.returnValue=false;"></td>
			</tr>
		</table>
		</span>
    </span>
</span>
<span style="display:none">
	<span id="mi_product_sample">
	    <span id="input_group_#xxxx#">    
             <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td width="100">
                    	<input name="mi_product[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" class="bar-product-id" style="width:90px;" onblur="update_product(this);calculate();" AUTOCOMPLETE=OFF>
                    </td>
                    <td width="180"><input name="mi_product[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:170px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="60"><input name="mi_product[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:50px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="70"><input name="mi_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="text-align:center;width:60px;" maxlength="4" onKeyUp="update_total($('product_id_'+this.id.replace('quantity_','')));calculate();" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" /></td>
					<td width="190" align="right"><input name="mi_product[#xxxx#][price]" type="text" id="price_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="190" align="right"><input name="mi_product[#xxxx#][total]" type="text" id="total_#xxxx#" style="text-align:right;width:180px;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td><input name="delete_item_product" type="button" value="[[.delete.]]" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','');event.returnValue=false;calculate();" tabindex="-1"></td>
                </tr>
            </table>
		</span>
	</span>
</span><div style="text-align:center;">
<div style="width:100%;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td width="100%">
			<form name="AddBarReservationNewForm" method="post">
			<table cellSpacing=0 cellPadding=0 border=0 width="100%" bordercolor="#97ADC5">
			<tr height="25" bgcolor="#FFFFFF">
				<td align="left">
				<table width="100%" border="0">
				<tr>
					<td width="20%">
						<table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td>[[.create_date.]]</td>
                            <td>: [[|date|]]</td>
                          </tr>
                        </table>
				    </td>
					<td width="60%" align="center" style="padding:0 0 0 0" nowrap="nowrap">&nbsp;&nbsp;<font style="font-size:20px; text-transform:uppercase;">[[.bar_reservation.]]</font></td>
					<td width="20%" align="right" nowrap>
						<table border="0" cellspacing="0" cellpadding="3" width="100%">
                          <tr>
                            <td align="right">[[.currency.]]: 
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
            <fieldset>
            <legend class="title">[[.guest_information.]]</legend>
            <div id="guest_information">
                <input type="hidden" id="select_bar" name="select_bar" value="0">
				<input type="hidden" name="delete_table" value="">
				<input type="hidden" name="delete_id" value="">
				<table width="100%" style="text-align:left;">
					<tr>
					  <td width="22%" nowrap="nowrap">[[.room_id.]]<br />
					    <select name="room_id" id="room_id" onchange="update_room(this);" style="width:130px;"></select></td>
					  <td width="50%" nowrap="nowrap"><table>
                          <tr>
                            <td>[[.arrival_time.]]</td>
                            <td>[[.arrival_time_in.]]</td>
                            <td nowrap="nowrap">[[.arrival_time_out.]]</td>
                            <td nowrap="nowrap">[[.banquet_order_type.]]</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td width="130"><input name="arrival_date" type="text" id="arrival_date" size="10" value="[[|arrival_date|]]"/></td>
                            <td width="130"><table>
                              <tr>
                                  <td><select  name="arrival_time_in_hour">
                                      <?php for($i=0;$i<24;$i++) 
									{
										if($this->map{'time_in_hour'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'h</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'h</option>';
										}
									} ?>
                                  </select>                                  </td>

                                <td><select  name="arrival_time_in_munite">
                                      <?php for($i=0;$i<60;$i=$i+5) 
									{
										if($this->map{'time_in_munite'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									} ?>
                                </select>                                  </td>
                              </tr>
                            </table></td>
                            <td width="80"><table>
                              <tr>
                                  <td><select  name="arrival_time_out_hour">
                                      <?php for($i=0;$i<24;$i++) 
									{
										if($this->map{'time_out_hour'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'h</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'h</option>';
										}
									} ?>
                                  </select>                                  </td>
                                <td><select  name="arrival_time_out_munite">
                                      <?php for($i=0;$i<60;$i=$i+5)  
									{
										if($this->map{'time_out_munite'}==$i)
										{
											echo '<option value="'.$i.'" selected>'.$i.'</option>';
										}
										else
										{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									} ?>
                                </select>                                  </td>
                              </tr>
                            </table></td>
                            <td width="80"><input name="banquet_order_type" type="text" id="banquet_order_type" style="width:100px;" /></td>
                            <td>&nbsp;</td>
                          </tr>
                      </table></td>
					  <td width="50%" rowspan="3" nowrap="nowrap"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC;">
                        <tr bgcolor="#EBE6A6" height="20px">
                          <th align="left"> <img src="skins/default/images/b-chi.gif" /> &nbsp;
                            [[.note.]] </th>
                        </tr>
                        <tr>
                          <td align="center"><textarea name="note" id="note" rows="5" style="width:200px;border:1px solid #FFFFFF;font-style:italic;font-size:11px;"></textarea></td>
                        </tr>
                      </table></td>
					</tr>
					<tr>
					  <td nowrap="nowrap">[[.reservation_id.]]</td>
					  <td nowrap="nowrap"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="left" width="200">[[.customer.]]</td>
                          <td align="left" width="100">[[.receiver_name.]]</td>
                          <td align="right">[[.num_table.]]</td>
                        </tr>
                      </table></td>
					</tr>
					<tr>
					  <td nowrap="nowrap"><select name="reservation_id" id="reservation_id" style="width:99%;"></select></td>
					  <td nowrap="nowrap"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <th align="left" width="200"> <input name="customer_name" type="text" id="customer_name" style="width:150px;"  readonly="" class="readonly" />
                              <input name="customer_id" type="text" id="customer_id" class="hidden" />
                              <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onclick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:pointer;" /></th>
                          <th align="left" width="100"><input name="receiver_name" type="text" id="receiver_name" style="width:150px;" /></th>
                          <th align="right"><input name="num_table" type="text" id="num_table" style="text-align:right;width:100px;" value="1" /></th>
                        </tr>
                      </table></td>
					</tr> 
					</table>
                  </div></fieldset>
                    <table width="100%" cellpadding="3" cellspacing="0">
					<tr>
						<td colspan="6">
							<fieldset style="text-align:left;">
							<legend class="title">[[.reservation_table.]]</legend>
                                <span id="mi_table_all_elems">
                                <!--IF:cond([[=select_table_options=]])-->
                               <table border="0">
                                    <tr>
                                    <td width="120px">[[.table_id.]]</td>
                                    <td width="120px">[[.table_code.]]</td>
                                    <td width="120px">[[.table_num_people.]]</td> 
									<td width="120px">[[.order_person.]]</td> 
                                    </tr>
                                </table>
                                <!--ELSE-->
                                <div class="notice">[[.Empty_table.]]!</div>
                                <!--/IF:cond-->                            
                                </span>
                                <input type="button" value="   [[.add_table.]]   " onclick="mi_add_new_row('mi_table');">
							</fieldset></td>
					</tr>
					<tr>
                      <td colspan="6">
                      <fieldset style="text-align:left;">
                        <legend class="title">[[.reservation_product.]]</legend>
                        <span id="mi_product_all_elems" style="padding:5px;">
                        <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                          <tr bgcolor="#EFEFEF">
                            <td width="100"> [[.product_code.]] </td>
                            <td width="180">[[.product_name.]]</td>
                            <td width="60">[[.product_unit.]]</td>
                            <td width="70" nowrap="nowrap">[[.product_quantity.]]</td>
							<td width="190" align="right" nowrap="nowrap">[[.price.]]</td>
                            <td width="190" align="right" nowrap="nowrap">[[.total.]]</td>
							<td></td>
                          </tr>
                        </table>
                        </span>
                        <input type="button" value="   [[.add_product.]]   " onclick="mi_add_new_row('mi_product');autocomplete1('product_id_'+input_count);">
                        <table width="100%" border="0" cellpadding="2">
                          <tr>
                            <td width="8%">&nbsp;</td>
                            <td width="8%">&nbsp;</td>
                            <td width="64%" align="right">&nbsp;</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.summary.]]</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right"></td>
                            <td align="right" nowrap="nowrap"><input name="summary" type="text" id="summary" readonly="readonly" style="text-align:right;" class="readonly_class" /></td>
                          </tr>
                          <tr>
                            <td width="8%">&nbsp;</td>
                            <td width="8%">&nbsp;</td>
                            <td width="64%" align="right">&nbsp;</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.tax.]]</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td align="right" nowrap="nowrap"><select name="tax_rate" id="tax_rate" onchange="calculate();"></select><input name="tax_total" type="text" id="tax_total"  readonly="readonly" style="text-align:right;" class="readonly_class"></td>
                          </tr>
                          <tr> </tr>
                          <tr>
                            <td width="8%">&nbsp;</td>
                            <td width="8%">&nbsp;</td>
                            <td width="64%" align="right">&nbsp;</td>
                            <td width="20%" align="right" nowrap="nowrap">[[.service_rate.]]</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td align="right" nowrap="nowrap"><select name="service_rate" id="service_rate" onchange="calculate();"></select>
                                <input name="service_total" type="text" id="service_total"  readonly="readonly" style="text-align:right;" class="readonly_class"></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.deposit.]]</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap"><input name="deposit" type="text" id="deposit" style="text-align:right" value="0" size="15" maxlength="11" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=45 &amp;&amp; event.keyCode!=44)event.returnValue=false;"/></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.deposit_date.]]</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><input name="deposit_date" type="text" id="deposit_date" style="text-align:right" size="15" maxlength="11" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=45 &amp;&amp; event.keyCode!=44)event.returnValue=false;"/></td>
                          </tr>
                          <tr>
                            <td>[[.payment_info.]]</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td align="right" nowrap="nowrap">[[.sum_total.]]</td>
                          </tr>
                          <tr>
                            <td><input name="payment_info" type="text" id="payment_info" size="30" /></td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap"></td>
                            <td align="right" nowrap="nowrap"><input name="sum_total" type="text" id="sum_total" style="text-align:right" readonly="readonly" class="readonly_class" tabindex="-1"/></td>
                          </tr>
                        </table>
                      </fieldset></td>
				  </tr>
					<tr bgcolor="#EFEFEF">
					  <td colspan="6" bgcolor="#EEEEEE" align="center"><label for="reservation_type" style="float:left;margin-top:3px;">[[.reservation_type.]]</label> <select name="reservation_type" id="reservation_type" style="float:left;"></select><input type="submit" name="save" value="    [[.save.]]    " tabindex="-1" class="button-medium-save">&nbsp;&nbsp;&nbsp;<input type="submit" name="check_in" value="    [[.check_in.]]    " tabindex="-1" class="button-medium-add">&nbsp;&nbsp;&nbsp;<input type="button" value="  [[.back.]]    " onClick="history.go(-1);" tabindex="-1" class="button-medium-back">&nbsp;&nbsp;&nbsp;<input type="button" value="  [[.List.]]    " onClick="window.location='<?php echo Url::build_current();?>'" tabindex="-1" class="button-medium-back"><br clear="all" /></td>
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
	jQuery("#arrival_date").datepicker();
	jQuery("#deposit_date").datepicker();
	function autocomplete1(id)
	{
		jQuery("#"+id).autocomplete({
                url: 'get_product.php?bar=1',
				selectFirst:true,
				matchContains: true
        }).result(function(){
			update_product(this.value);
			calculate();
		});
	}
</script>
<script type="text/javascript">
mi_init_rows('mi_table',<?php if(isset($_REQUEST['mi_table'])){echo String::array2js($_REQUEST['mi_table']);}else{echo '[]';}?>);
mi_init_rows('mi_product',<?php if(isset($_REQUEST['mi_product'])){echo String::array2js($_REQUEST['mi_product']);}else{echo '[]';}?>);
autocomplete1('product_id_'+input_count);
calculate();
</script>