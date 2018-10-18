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
                            <td><?php echo Portal::language('date');?></td>
                            <td>: <font style="font-size:14px;"><strong><?php echo $this->map['date'];?></strong></font></td>
                          </tr>
                          <tr>
                            <td><?php echo Portal::language('code');?></td>
                            <td>: <font style="font-size:14px;"><strong><?php echo $this->map['order_id'];?></strong></font></td>
                          </tr>
                        </table>
				    </td>
					<td width="50%" align="center">
						<font style="font-size:20px;text-transform:uppercase;"><b>
							<?php 
				if(($this->map['status']=='CHECKIN'))
				{?>
							<?php echo Portal::language('bar_check_in');?>
							 <?php }else{ ?>
							<?php echo Portal::language('bar_check_out');?>
							
				<?php
				}
				?>
						</b></font>					
					</td>
					<td width="25%" align="right">
						<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><?php echo Portal::language('currency');?>&nbsp;&nbsp;</td>
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
					  <td nowrap><?php echo Portal::language('agent_name');?></td>
					  <td nowrap><?php echo Portal::language('agent_address');?></td>
					  <td><?php echo Portal::language('agent_phone');?></td>
					  <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_name" type="text" id="agent_name" size="27" value="<?php echo $this->map['agent_name'];?>" class="room_input_class" readonly="readonly" />
				      &nbsp;</td>
					  <td nowrap><input name="agent_address" type="text" id="agent_address" value="<?php echo $this->map['agent_address'];?>" class="room_input_class" size="58" readonly="readonly" /></td>
					  <td><input name="agent_phone" type="text" id="agent_phone" value="<?php echo $this->map['agent_phone'];?>" class="room_input_class" size="15" readonly="readonly" /></td>
					  <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><?php echo Portal::language('agent_fax');?></td>
					  <td nowrap><?php echo Portal::language('receiver_name');?></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="agent_fax" type="text" id="agent_fax" value="<?php echo $this->map['agent_fax'];?>" class="room_input_class" size="15" readonly="readonly" /></td>
					  <td nowrap><input name="receiver_name" type="text" id="receiver_name" size="27" value="<?php echo $this->map['receiver_name'];?>" class="room_input_class" readonly="readonly" /></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><table>
                        <tr>
                          <td width="100"><?php echo Portal::language('time_in');?></td>
                          <td width="80"><?php echo Portal::language('time_out');?></td>
                        </tr>
                      </table></td>
					  <td><?php 
				if(($this->map['num_table']!=0))
				{?><?php echo Portal::language('num_table');?>
				<?php
				}
				?></td>
				      <td width="1%">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="1%" nowrap>&nbsp;</td>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><table>
                        <tr>
                          <td width="100"><input name="time_in1" type="text" style="width:19px" value="<?php echo $this->map['time_in_hour'];?>" class="room_input_class" readonly="readonly" />
                              <input  name="time_in2" style="width:7px" value=":" class="room_input_class" readonly="readonly" / type ="text" value="<?php echo String::html_normalize(URL::get('time_in2'));?>">
                              <input name="time_in3" type="text" style="width:19px" value="<?php echo $this->map['time_in_munite'];?>" class="room_input_class" readonly="readonly" />                          </td>
                          <td width="80">
						  	  <?php 
				if(($this->map['status']=='CHECKOUT'))
				{?>
							  <input name="time_in1" type="text" style="width:19px" value="<?php echo $this->map['time_out_hour'];?>" class="room_input_class" readonly="readonly" />
                              <input  name="time_in2" style="width:7px" value=":" class="room_input_class" readonly="readonly" / type ="text" value="<?php echo String::html_normalize(URL::get('time_in2'));?>">
                              <input name="time_in3" type="text" style="width:19px" value="<?php echo $this->map['time_out_munite'];?>" class="room_input_class" readonly="readonly" />
							  
				<?php
				}
				?>						  </td>
                        </tr>
                      </table></td>
					  <td><?php 
				if(($this->map['num_table']!=0))
				{?><input name="num_table" type="text" id="num_table" value="<?php echo $this->map['num_table'];?>" size="7" class="room_input_class" readonly="readonly" />
				<?php
				}
				?></td>
					  <td width="1%">&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><?php echo Portal::language('room_id');?></td>
					  <td nowrap><?php echo Portal::language('reservation_id');?></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td nowrap>&nbsp;</td>
					  <td nowrap><input name="room_name" type="text" id="room_name" value="<?php echo $this->map['room_name'];?>" size="20" class="room_input_class" readonly="readonly" /></td>
					  <td nowrap><input name="reservation_name" type="text" id="reservation_name" value="<?php echo $this->map['reservation_name'];?>" size="37" class="room_input_class" readonly="readonly" /></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				    </tr> 
					<?php 
				if(($this->map['tables_num']!=0))
				{?>
					<tr>
						<td colspan="5">
							<fieldset>
							<legend><?php echo Portal::language('reservation_table');?></legend>
							<span style="padding:5px;">
							<table border="0">
								<tr>
								<td width="105"><?php echo Portal::language('table_name');?></td> 
								<td width="100"><?php echo Portal::language('table_code');?></td>
								<td width="120" nowrap="nowrap"><?php echo Portal::language('table_num_people');?></td> 
								</tr>
							</table>
							<?php if(isset($this->map['table_items']) and is_array($this->map['table_items'])){ foreach($this->map['table_items'] as $key1=>&$item1){if($key1!='current'){$this->map['table_items']['current'] = &$item1;?>
							<span id="span_table_<?php echo $this->map['table_items']['current']['id'];?>">
							<table cellpadding="2">
								<tr>
									<td width="100"><input name="table__name[]" type="text" style="width:100%" value="<?php echo $this->map['table_items']['current']['name'];?>" class="room_input_class" readonly="readonly" /></td>
									<td width="100"><input name="table__code[]" type="text" style="width:100%" value="<?php echo $this->map['table_items']['current']['code'];?>" class="room_input_class" readonly="readonly" /></td>
									<td width="120"><input name="table__num_people[]" type="text" size="10" value="<?php echo $this->map['table_items']['current']['num_people'];?>" class="room_input_class" readonly="readonly"/></td>
								</tr>
							</table>
							</span>
							<?php }}unset($this->map['table_items']['current']);} ?>
							</span>
							</fieldset>						</td>
					</tr>
					
				<?php
				}
				?>
					<tr>
                      <td colspan="5">
						<?php 
				if(($this->map['product_num']!=0))
				{?>
						<fieldset>
                        <legend><?php echo Portal::language('reservation_product');?></legend>
                        <span style="padding:5px;">
                        <table width="100%" border="0">
                          <tr>
                            <td width="10%"> <?php echo Portal::language('product_code');?> </td>
                            <td width="24%"><?php echo Portal::language('product_name');?></td>
                            <td width="8%" nowrap="nowrap"><?php echo Portal::language('product_unit');?></td>
							<td width="10%" align="right" nowrap="nowrap"><?php echo Portal::language('product_price');?></td>
                            <td width="12%" align="center" nowrap="nowrap"><?php echo Portal::language('product_quantity');?></td>
							<td width="8%" nowrap="nowrap"><?php echo Portal::language('product_quantity_discount');?></td>
                            <td width="10%" align="right" nowrap="nowrap"><?php echo Portal::language('product_discount');?></td>
                            <td width="15%" align="right" nowrap="nowrap"><?php echo Portal::language('total');?></td>
							<td width="6%">&nbsp;</td>
                          </tr>
                        </table>
						<?php if(isset($this->map['product_items']) and is_array($this->map['product_items'])){ foreach($this->map['product_items'] as $key2=>&$item2){if($key2!='current'){$this->map['product_items']['current'] = &$item2;?>
						<span id="span_product_<?php echo $this->map['product_items']['current']['product__id'];?>">
						<table width="100%">
						  <tr>
							<td width="10%"><input name="product__id[]" type="text" class="room_input_class" value="<?php echo $this->map['product_items']['current']['product__id'];?>" size="6" /></td>
							<td width="25%"><input name="product__name[]" type="text" class="room_input_class" tabindex="1000" value="<?php echo $this->map['product_items']['current']['product__name'];?>" size="25" readonly="readonly"/></td>
							<td width="9%"><input name="product__unit[]" type="text" class="room_input_class" tabindex="1000" value="<?php echo $this->map['product_items']['current']['product__unit'];?>" size="5" readonly="readonly"/></td>
							<td width="9%" align="right"><input name="product__price[]" type="text" class="room_input_class" style="text-align:right" tabindex="1000" value="<?php echo $this->map['product_items']['current']['product__price'];?>" size="15" readonly="readonly"/></td>
							<td width="10%" align="center"><input name="product__quantity[]" type="text" class="room_input_class" value="<?php echo $this->map['product_items']['current']['product__quantity'];?>" size="2" style="text-align:center" /></td>
							<td width="10%" align="center"><input name="product__quantity_discount[]" type="text" class="room_input_class" value="<?php echo $this->map['product_items']['current']['product__quantity_discount'];?>" size="2" style="text-align:center" /></td>
							<td width="8%" align="right"><input name="product__discount[]" type="text" class="room_input_class" value="<?php echo $this->map['product_items']['current']['product__discount'];?>" size="5" style="text-align:right" /></td>
							<td width="25%" align="right"><input name="product__total[]" type="text" class="room_input_class" style="text-align:right" tabindex="1000" value="<?php echo $this->map['product_items']['current']['product__total'];?>" size="20" readonly="readonly"/></td>
							<td width="4%"></td>
						  </tr>
						</table>
						</span>
						<?php }}unset($this->map['product_items']['current']);} ?>
						</span>
	                    </fieldset>
						
				<?php
				}
				?>  
						  <table width="100%" border="0" cellpadding="3">
                           <tr>
                            <td width="16%"></td>
                            <td width="16%"></td>
                            <td width="2%" nowrap="nowrap"></td>
							<td width="9%" align="right" nowrap="nowrap"></td>
                            <td width="5%" nowrap="nowrap"></td>
							<td width="12%" nowrap="nowrap"></td>
                            <td width="15%" align="right" nowrap="nowrap"><?php echo Portal::language('discount');?></td>
                            <td width="20%" align="right" nowrap="nowrap"><?php echo Portal::language('summary');?></td>
							<td width="5%">&nbsp;</td>
                          </tr>
                           <tr>
                             <td></td>
                             <td></td>
                             <td nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"></td>
                             <td nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"></td>
                             <td align="right" nowrap="nowrap"><span id="total" class="room_input_class" style="height:20px; width:70px; letter-spacing:1px;"><?php echo $this->map['total_discount'];?></span></td>
                             <td align="right" nowrap="nowrap">
							 	<span id="total" class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"><?php echo $this->map['amount'];?></span>							 </td>
                             <td>&nbsp;</td>
                           </tr>
                          <?php 
				if((String::to_number($this->map['bar_fee'])))
				{?>
						  <tr>
						    <td align="right" nowrap="nowrap"></td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><?php echo Portal::language('bar_fee');?> (5%)</td>
                            <td align="right"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"><?php echo $this->map['bar_fee'];?></span></td>
                            <td></td>
                          </tr>
						  <tr>
						    <td align="right"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"></span></td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td align="right" nowrap="nowrap"><?php echo Portal::language('total_before_tax');?></td>
						    <td align="right" nowrap="nowrap"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"><?php echo $this->map['total_before_tax'];?></span></td>
						    <td></td>
						    </tr>
                          
				<?php
				}
				?>
						  <tr>
						    <td width="16%" align="right"><?php echo Portal::language('deposit');?></td>
						    <td width="16%" align="right"><?php echo Portal::language('remain_paid');?></td>
						    <td>&nbsp;</td>
                            <td rowspan="2"><fieldset style="height:50px; text-align:center; width:250px">
								<legend><?php echo Portal::language('payment_result');?></legend>
								&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->map['payment_kind'];?>&nbsp;&nbsp;&nbsp;&nbsp;
								</fieldset>							
							</td>
							<td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right" nowrap="nowrap"><?php echo Portal::language('vat_tax');?> <?php echo $this->map['tax_rate'];?>%</td>
                            <td align="right" nowrap="nowrap"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"><?php echo $this->map['tax'];?></span> </td>
                            <td></td>
                          </tr>
						  <tr>
						    <td align="right"><span class="room_input_class" id="remain_paid" style="height:20px;letter-spacing:1px; width:100px"><strong><?php echo $this->map['prepaid'];?></strong></span> </td>
						    <td align="right"><span class="room_input_class" id="remain_paid" style="height:20px;letter-spacing:1px; width:100px"><strong><?php echo $this->map['remain_prepaid'];?></strong></span> </td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td align="right">&nbsp;</td>
						    <td align="right"><?php echo Portal::language('sum_total');?></td>
						    <td align="right" nowrap="nowrap"><span class="room_input_class" style="height:20px; width:120px; letter-spacing:1px;"><strong><?php echo $this->map['sum_total'];?></strong></span></td>
						    <td></td>
						    </tr>
                        </table>					</td>
				</table>
				<table cellpadding=5 onDblClick="this.style.display='none'">
					<tr>
						<td><?php Draw::button(Portal::language('list_title'),URL::build_current());?></td>
						<?php 
				if(($this->map['status']=='CHECKIN' and $this->map['payment_result']))
				{?>
							<td><?php Draw::button(Portal::language('check_out'),URL::build_current(array('cmd'=>'detail','id','act'=>'checkout')));?></td>
						
				<?php
				}
				?>
						<?php 
				if((($this->map['pkind']==0 and $this->map['status']=='CHECKIN') or ($this->map['pkind']!=0 and User::can_edit(false,ANY_CATEGORY))))
				{?>	
							<td><?php Draw::button(Portal::language('print'),URL::build_current(array('cmd'=>'detail','id','act'=>'print','curr')));?></td>
							<td><?php Draw::button(Portal::language('print_kitchen'),URL::build_current(array('cmd'=>'detail','id','act'=>'print_kitchen','curr')));?></td>                            
							<td><?php Draw::button(Portal::language('edit'),URL::build_current(array('cmd'=>'check_in','id'=>Url::get('id'))));?></td>
						
				<?php
				}
				?>	
						<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>	
						<td><?php Draw::button(Portal::language('delete'),URL::build_current(array('cmd'=>'delete','id'=>Url::get('id'))));?></td>
						
				<?php
				}
				?>	
					</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
