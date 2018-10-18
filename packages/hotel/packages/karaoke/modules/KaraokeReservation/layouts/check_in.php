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
                <input name="mi_table[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			  </td> 
			<td width="100">
				<input name="mi_table[#xxxx#][code]" type="text" id="code_#xxxx#"  style="width:50px;" class="readonly_input" readonly="readonly">
			</td> 
			<td width="120"><input name="mi_table[#xxxx#][num_people]" type="text" id="num_people_#xxxx#" size="10"></td>
			<td width="120"><input name="mi_table[#xxxx#][order_person]" type="text" id="order_person_#xxxx#" size="10"></td>
			<td><input name="delete_item_table" type="button" value="[[.delete.]]" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_table','#xxxx#','table_');event.returnValue=false;"></td>
			</tr>
		</table>
		</span>
    </span>
</span>
<span style="display:none">
	<span id="mi_product_sample">
	    <span id="input_group_#xxxx#">    
			<input name="mi_product[#xxxx#][id]" type="hidden" id="id_#xxxx#" size="1">
            <table width="100%" cellpadding="2" border="1" bordercolor="#999999" style="border-top:1px solid #FFFFFF;">
                <tr>
                    <td width="90">
                    	<input name="mi_product[#xxxx#][product_id]" type="text" id="product_id_#xxxx#" class="karaoke-product-id" style="width:96%;" onKeyUp="update_product(this);calculate();" onblur="update_product(this);calculate();" AUTOCOMPLETE=OFF>
                    </td>
                    <td width="170"><input name="mi_product[#xxxx#][name]" type="text" id="name_#xxxx#" style="width:96%;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="60"><input name="mi_product[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:90%;" readonly="readonly" class="readonly_input" tabindex="-1"/></td>
                    <td width="65"><input name="mi_product[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" maxlength="4" onKeyUp="update_total_checkin($('product_id_'+this.id.replace('quantity_','')));calculate_checkin();" style="text-align:center;width:90%;" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" value="0"></td>
					<td width="65" align="center"><input name="mi_product[#xxxx#][quantity_discount]" type="text" id="quantity_discount_#xxxx#" onKeyUp="update_total_checkin($('product_id_'+this.id.replace('quantity_discount_','')));calculate_checkin();" style="text-align:center;width:90%;" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" value="0"></td>
                    <td width="70" align="right"><input name="mi_product[#xxxx#][discount]" type="text" id="discount_#xxxx#" style="width:90%;" onKeyUp="update_total_checkin($('product_id_'+this.id.replace('discount_','')));calculate_checkin();" maxlength="2" onKeyPress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=45 && event.keyCode!=44)event.returnValue=false;" value="0"></td>
					<td width="150" align="right"><input name="mi_product[#xxxx#][price]" type="text" id="price_#xxxx#" onchange="update_total_checkin($('product_id_'+this.id.replace('price_','')));calculate_checkin();this.value=number_format(to_numeric(this.value),2);" class="readonly_input" style="text-align:right;width:90%;" tabindex="-1"></td>
                    <td width="150" align="right"><input name="mi_product[#xxxx#][total]" type="text" id="total_#xxxx#" readonly="readonly" class="readonly_input" style="text-align:right;width:90%;" tabindex="-1"/></td>
					<td width="30" align="center"><input name="mi_product[#xxxx#][printed]" type="checkbox" id="printed_#xxxx#"></td>
                    <td align="center"><a onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','product_');event.returnValue=false;calculate_checkin();" tabindex="-1" title="[[.delete.]]"><img src="packages/core/skins/default/images/buttons/delete.gif" /></a></td>
                </tr>
            </table>
		</span>
	</span>
</span>

<div style="text-align:center;">
<div style="width:100%;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="text-align:left;">
	<tr>
		<td width="100%">
			<form name="CheckInKaraokeForm" method="post">
			<input  name="table_deleted_ids" id="table_deleted_ids" type="hidden" value="<?php echo URL::get('table_deleted_ids');?>">
            <input  name="product_deleted_ids" id="product_deleted_ids" type="hidden" value="<?php echo URL::get('product_deleted_ids');?>">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%">
			<tr height="25">
				<td align="center">
				<table width="100%" border="0">
				<tr>
					<td width="25%"><table>
                          <tr>
                            <td>[[.create_date.]]</td>
                            <td>[[|date|]]</td>
                          </tr>
                          <tr>
                            <td>[[.time_in.]]</td>
                            <td width="100"><input name="time_in1" type="text" style="width:17px" value="[[|time_in_hour|]]" class="readonly_input" readonly="readonly" />
                                <input name="time_in2" type="text" style="width:7px" value=":" class="readonly_input" readonly="readonly" />
                                <input name="time_in3" type="text" style="width:17px" value="[[|time_in_munite|]]" class="readonly_input" readonly="readonly" />                            </td>
                          </tr>
                          <tr>
                            <td width="100">[[.time_out.]]</td>
                            <td width="100"><!--IF:cond_time_out(isset([[=time_out_hour=]]))-->
                                <input name="time_in1" type="text" style="width:17px" value="[[|time_out_hour|]]" class="readonly_input" readonly="readonly" />
                                <input name="time_in2" type="text" style="width:7px" value=":" class="readonly_input" readonly="readonly" />
                                <input name="time_in3" type="text" style="width:17px" value="[[|time_out_munite|]]" class="readonly_input" readonly="readonly" />
                                <!--/IF:cond_time_out-->                            </td>
                          </tr>
                      </table></td>
					<td width="50%" align="center">
						<font style="font-size:20px;text-transform:uppercase;"><b>[[.karaoke_check_in.]]</b></font>
					</td>
					<td width="25%" align="right">
						<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>[[.currency.]]&nbsp;&nbsp;</td>
                            <td>: 
								<?php echo HOTEL_CURRENCY;?>							</td>
                          </tr>
                          <tr>
                          	<td>[[.status.]]&nbsp;&nbsp;</td>
                            <td>:  <strong>[[|status|]]</strong></td>
                          </tr>
						  <!--IF:cond([[=banquet_order_type=]])-->
                          <tr>
                            <td>[[.banquet_order_type.]]</td>
                            <td><strong>[[|banquet_order_type|]]</strong></td>
                          </tr><!--/IF:cond-->
                        </table>
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr>
			<td>
				<div><?php echo Form::$current->error_messages();?></div>
                <fieldset style="background-color:#EFEFEF;">
                <legend class="title">[[.guest_information.]]</legend>
            	<div id="guest_information">
				<table width="100%">
					<tr>
					  <td nowrap="nowrap">[[.room.]]</td>
					  <td nowrap>[[.customer.]]</td>
					  <td nowrap>[[.receiver_name.]]</td>
					  <td rowspan="6" width="30%">
                          <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC;">
                              <tr bgcolor="#EBE6A6" height="20px">
                                <th align="left"><img src="skins/default/images/b-chi.gif">&nbsp;[[.note.]]</th>
                              </tr>
                              <tr>
                                <td align="center"><textarea name="note" id="note" rows="3" style="width:250px;border:1px solid #FFFFFF;font-style:italic;font-size:11px;">[[|note|]]</textarea></td>
                              </tr>
                          </table>                       </td>
				  </tr>
					<tr>
					  <td nowrap="nowrap"><select name="room_id" id="room_id" onChange="update_room(this);"></select></td>
					  <td nowrap><input name="customer_name" type="text" id="customer_name" style="width:150px;"  readonly="" class="readonly">
                      <input name="customer_id" type="text" id="customer_id" class="hidden" />
                      <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:pointer;"></td>
					  <td nowrap><input name="receiver_name" type="text" id="receiver_name" style="width:150px;" /></td>
					</tr>
					<tr>
					  <td nowrap="nowrap">[[.guest.]]</td>
					  <td>[[.num_table.]]</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td nowrap="nowrap"><select name="reservation_id" id="reservation_id">
                      </select></td>
					  <td><input name="num_table" type="text" id="num_table" style="width:150px;" /></td>
					  <td>&nbsp;</td>
					</tr>
					</table>
                  </div>
                    </fieldset>
                    <table cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<td colspan="5">
							<fieldset>
							<legend class="title">[[.reservation_table.]]</legend>
							<span style="padding:5px;" id="mi_table_all_elems">
							<table width="100%" border="0">
								<tr>
								<td width="120">[[.table_id.]]</td> 
								<td width="100">[[.table_code.]]</td>
								<td width="150">[[.table_num_people.]]</td> 
								<td width="120px">[[.order_person.]]</td> 
								<td>&nbsp;</td>
								</tr>
							</table>
							</span>
							<input type="button" value="   [[.add_table.]]   " onclick="mi_add_new_row('mi_table');">
							</fieldset>			</td>
					</tr>
					<tr>
                      <td colspan="5">
                     <fieldset style="background-color:#D7EBFF;">
                        <legend class="title">[[.reservation_product.]]</legend>
                        <span id="mi_product_all_elems">
                         <table width="100%" border="1" cellpadding="2" bordercolor="#999999">
                          <tr bgcolor="#EFEFEF">
                            <td width="90"> [[.product_code.]] </td>
                            <td width="170">[[.product_name.]]</td>
                            <td width="60">[[.product_unit.]]</td>
                            <td width="65" nowrap="nowrap">[[.product_quantity.]]</td>
							<td width="65" nowrap="nowrap">[[.product_quantity_discount.]]</td>
                            <td width="70" align="right" nowrap="nowrap">[[.product_discount.]]%</td>
							<td width="150" align="right" nowrap="nowrap">[[.price.]]</td>
                            <td width="150" align="right" nowrap="nowrap">[[.total.]]</td>
							<td width="30">[[.printed.]]</td>
							<td></td>
                          </tr>
                        </table>
					  </span>
					   </fieldset>
					  <hr size="1" color="#CCCCCC">
					  <div style="float:left;"><input type="button" value="   [[.add_item.]]   " onfocus="jQuery(this).css('font-weight','bold');" onkeydown="jQuery(this).css('font-weight','normal');" onclick="mi_add_new_row('mi_product');autocomplete1('product_id_'+input_count);"></div>
					  <div style="float:right;">
					  	<label for="reservation_type" style="float:left;margin-top:5px;">[[.reservation_type.]]</label><select name="reservation_type" id="reservation_type" style="float:left;"></select>
					  	<!--IF:cond_status(MAP['status']=='CHECKIN')-->
                        <input type="submit" name="save" value=" [[.save.]] " id="save" class="button-medium-save">
						<input type="submit" name="check_out" onclick="if(pass == false){alert('Bạn chưa chọn phương thức thanh toán...');$('cash').focus();return false;}" value=" [[.print.]] [[.and.]] [[.check_out.]] " tabindex="-1" class="button-medium-save">
                       	<!--ELSE-->
                        <!--IF:cond_admin(User::can_admin(false, ANY_CATEGORY))-->
                      	 <input type="button" value=" [[.save.]] " id="save" tabindex="-1" class="button-medium-save">
                          <div id="password_box" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:10px;text-align:center;background:#FFFF99;">
                                [[.password.]]: <input  name="password" type="password" id="password" size="8">
                                <input type="submit" name="save" value="[[.OK.]] " tabindex="-1" style="color:#000066;font-weight:bold;">
                                <a class="close" onclick="jQuery('#password_box').hide();$('password').value='';">[[.close.]]</a>
                          </div>
                        <!--/IF:cond_admin-->
						<!--/IF:cond_status-->
						<input type="button" name="preview" value=" [[.view_order.]]" tabindex="-1" class="button-medium" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'))?>');">
						<input type="button" name="preview" value=" [[.food_order.]] " onclick="window.open('<?php echo Url::build_current(array('cmd'=>'detail','act'=>'print_kitchen','id'))?>')" tabindex="-1" class="button-medium">
						<input type="button" value="[[.print_banquet_event_order.]]" onClick="window.open('<?php echo Url::build_current(array('cmd'=>'detail','act'=>'print_b_e_order','id'));?>');" tabindex="-1" class="button-medium">
						<input type="button" value=" [[.close.]] " onClick="window.location='<?php echo Url::build('table_map');?>';" tabindex="-1" style="color:#FF0000;" class="button-medium">
						</div>
						<table width="100%" border="0" cellpadding="2">
						  <tr valign="top">
							<td colspan="2" rowspan="4" bgcolor="#EFEFEF"><fieldset>
							  <legend>[[.product_discount_type.]]</legend>
							  <table cellpadding="1" cellspacing="0">
								<!--LIST:category_items-->
								<tr>
								  <td align="right"><label for="discount_value_[[|category_items.id|]]">[[|category_items.name|]]</label></td>
								  <td align="left"><input name="category_discount_value"  type="text" id="discount_value_[[|category_items.id|]]" value="[[|category_items.discount_rate|]]" style="text-align:right; width: 20px;font-size:10px;height:15px;" maxlength="2" onchange="update_discount_rate('[[|category_items.id|]]')" />%</td>
								</tr>
								<!--/LIST:category_items-->
							  </table>
							</fieldset></td>
									<td nowrap="nowrap" width="1%"></td>
									<td width="35%" colspan="2" rowspan="9" align="left" nowrap="nowrap" bgcolor="#EFEFEF" style="border-bottom:1px 999999 #000000"><table width="100%" border="0" cellpadding="2">
									  <tr>
										<td width="16%" align="right" nowrap="nowrap" bgcolor="#EFEFEF">Gi&#7843;m gi&aacute;</td>
										<td width="12%" align="right" nowrap="nowrap" bgcolor="#EFEFEF">[[.total.]]</td>
									  </tr>
									  <tr>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="discount" type="text" id="discount" style="text-align:right" value="[[|discount|]]" size="10" class="readonly_class" readonly="readonly" /></td>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><span id="summary" class="readonly_class" style="height:20px; width:100px; letter-spacing:1px;">[[|summary|]]</span></td>
									  </tr>
									  <tr>
									    <td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="checkbox"  type="checkbox" id="cb_karaokefee" onclick="check_karaokefee(<?php echo RES_SERVICE_CHARGE;?>); calculate_checkin();" <?php echo [[=karaoke_fee_rate=]]!=0?' checked':'';?> />
									      [[.karaoke_fee.]]<input name="karaoke_fee_rate" type="text" id="karaoke_fee_rate" style="text-align:right;width:28px" value="[[|karaoke_fee_rate|]]"  onkeyup="calculate_checkin();" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;">%</td>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="karaoke_fee" type="text" id="karaoke_fee" size="12" style="text-align:right" value="[[|karaoke_fee|]]" class="readonly_class"></td>
									  </tr>
									  <tr>
									    <td align="right" nowrap="nowrap" bgcolor="#EFEFEF">[[.total_before_tax.]]</td>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="total_before_tax" id="total_before_tax" type="text" class="readonly_class" style="text-align:right" tabindex="1000" value="[[|total_before_tax|]]" size="12" readonly="readonly" /></td>
									  </tr>
									  <tr>
									    <td width="16%" align="right" bgcolor="#EFEFEF">[[.vat_tax.]]
									      <input name="tax_rate" type="text" id="tax_rate" maxlength="2" onkeyup="calculate_checkin();" style="text-align:right;width:28px" value="[[|tax_rate|]]" onkeypress="if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=44)event.returnValue=false;" />
									      %</td>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="tax" type="text" id="tax" style="text-align:right" value="[[|tax|]]" size="12"/ class="readonly_class" readonly="readonly" /></td>
									  </tr>
									  <tr>
									    <td align="right" bgcolor="#EFEFEF">[[.sum_total.]]</td>
										<td width="12%" align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="sum_total" type="text" id="sum_total" class="readonly_class" style="text-align:right" tabindex="1000" value="[[|sum_total|]]" size="12" readonly="readonly" /></td>
									  </tr>
									  <tr>
									    <td align="right" bgcolor="#EFEFEF">[[.deposit.]]</td>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><input name="prepaid" type="text" id="prepaid" style="text-align:right" value="[[|prepaid|]]" size="10" onkeyup="calculate_checkin();"/></td>
									  </tr>
									  <tr>
										<td align="right" bgcolor="#EFEFEF">[[.remain_paid.]]</td>
										<td align="right" nowrap="nowrap" bgcolor="#EFEFEF"><span id="remain_paid" class="readonly_class" style="height:20px;letter-spacing:1px; width:100px">[[|remain_prepaid|]]</span></td>
									  </tr>
									</table></td>
								  </tr>
								  <tr>
									<td nowrap="nowrap"></td>
								  </tr>
								  <tr>
									<td nowrap="nowrap"></td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
									<td align="right" bgcolor="#EFEFEF">&nbsp;</td>
									<td bgcolor="#EFEFEF">&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
									<td width="16%" align="right" bgcolor="#EFEFEF">&nbsp;</td>
									<td width="16%" align="right" bgcolor="#EFEFEF">&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
									<td align="right" bgcolor="#EFEFEF">&nbsp;</td>
									<td align="right" bgcolor="#EFEFEF">&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
								    <td bgcolor="#EFEFEF"><strong>[[.payment_info.]]</strong></td>
									<td align="right" bgcolor="#EFEFEF">&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
								    <td colspan="2" valign="top" bgcolor="#EFEFEF">[[|payment_info|]]</td>
									<td>&nbsp;</td>
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
                                            <input type="radio" name="payment_result" id="cash" value="CASH" <?php if([[=payment_result=]]=='CASH'){?>checked="checked"<?php }?> onclick="$('payment_detail').style.display='';$('VND').checked = true;$('room_id').value=0;$('reservation_id').value=0;"> 
                                            <label for="cash">[[.pay_now.]]</label><br>
                                            <input type="radio" name="payment_result" id="room" value="ROOM" <?php if([[=payment_result=]]=='ROOM'){?>checked="checked"<?php }?> onclick="$('payment_detail').style.display='none';$('group_payment_bound').style.display='';">
                                            <label for="room">[[.pay_by_room.]]</label><br> 
                                            <div id="group_payment_bound" style="<?php if([[=payment_result=]]=='ROOM'){ echo 'display:block;';}else{ echo 'display:none;';}?>"><input name="group_payment" type="checkbox" id="group_payment" value="1" />[[.group_payment.]]</div>
                                            </td>
                                            <td valign="top">
                                            <input type="radio" name="payment_result" id="debt" value="DEBT" <?php if([[=payment_result=]]=='DEBT'){?>checked="checked"<?php }?> onclick="$('group_payment_bound').style.display='none';$('payment_detail').style.display='none';$('room_id').value=0;$('reservation_id').value=0;">
                                            <label for="debt">[[.pay_by_debt.]]</label><br>
                                            <input type="radio" name="payment_result" id="free" value="FREE" <?php if([[=payment_result=]]=='FREE'){?>checked="checked"<?php }?> onclick="$('group_payment_bound').style.display='none';$('payment_detail').style.display='none';$('room_id').value=0;$('reservation_id').value=0;">
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
                              	<div id="payment_detail" style=" <?php if([[=payment_result=]]!='CASH'){?>display:none;<?php }?>">
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
                                            <td><input name="currency_selecteds[]"  type="checkbox" id="[[|pay_by_currency.id|]]" value="[[|pay_by_currency.id|]]" onclick="if(this.checked){<?php if([[=pay_by_currency.id=]]=='VND'){?>$('value_[[|pay_by_currency.id|]]').value = number_format(to_numeric($('remain_paid').innerHTML)*[[|pay_by_currency.exchange|]]);<?php }else{?>$('value_[[|pay_by_currency.id|]]').value = to_numeric($('remain_paid').innerHTML);<?php }?>}else{$('value_[[|pay_by_currency.id|]]').value=0;$('value_[[|pay_by_currency.id|]]').readOnly=''};calculate_rate(this.value);" <?php if(isset([[=pay_by_currency.value=]]) and [[=pay_by_currency.value=]]){?> checked="checked"<?php }?> /></td>
                                            <td>[[|pay_by_currency.id|]]</td>
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
			</td>
			</tr>
			</table>
			<input  name="status" type="hidden" id="status" value="[[|status|]]" />
			</form>
		</td>
	</tr>
</table>
</div>
</div>
<script type="text/javascript">
if($('room_id').value != 0 && $('room_id').value){
	$('room').checked = true;
	jQuery('#group_payment_bound').show();
}else{
	<!--IF:cond(![[=payment_result=]])-->
	$('cash').checked = true;
	$('payment_detail').style.display='';
	$('<?php echo HOTEL_CURRENCY;?>').checked = true;
	$('value_<?php echo HOTEL_CURRENCY;?>').value = $('remain_paid').innerHTML;
	$('room_id').value=0;
	$('reservation_id').value=0;
	<!--/IF:cond-->
}
<!--IF:cond([[=group_payment=]])-->
	$('group_payment').checked = true;
<!--/IF:cond-->
<!--IF:cond(User::can_admin(false, ANY_CATEGORY) and [[=status=]]=='CHECKOUT')-->
jQuery("#save").click(function(){
	jQuery("#password_box").show();
	return false;
});
<!--/IF:cond-->
var pass = false;
jQuery(function(){
	pass = false;
	jQuery('[name^=payment_result]').each(function(){
		if(jQuery(this).attr('checked') && jQuery(this).val()!=""){
			pass= true;
		}
	});
	jQuery('[name^=payment_result]').click(function(){
		if(jQuery(this).val()!=""){
			pass= true;
		}else{
			pass= false;
		}
	});
});
function autocomplete1(id)
{
	jQuery("#"+id).autocomplete({
			url: 'get_product.php?karaoke=1',
			minChars: 0,
			width: 310,
			matchContains: true,
			autoFill: true,
			formatItem: function(row, i, max) {
				return row.name;
			}  
	});

}

mi_init_rows('mi_table',<?php if(isset($_REQUEST['mi_table'])){echo String::array2js($_REQUEST['mi_table']);}else{echo '[]';}?>);
mi_init_rows('mi_product',<?php if(isset($_REQUEST['mi_product'])){echo String::array2js($_REQUEST['mi_product']);}else{echo '[]';}?>);
autocomplete1('product_id_'+input_count);
</script>