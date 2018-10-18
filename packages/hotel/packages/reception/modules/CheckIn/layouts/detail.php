<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(Portal::get_setting('company_name','').' '.'[[.detail_title.]]');?><?php echo Draw::begin_round_table();?><table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.detail_title.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>$GLOBALS['current_block']->data['module_id'],'href'=>'?'.$_SERVER['QUERY_STRING']));?>#detail">
						<img src="skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>		
		</td>
	</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.code.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|code|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.note.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|note|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.customer_id.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td width="90%"><div class="detail_box">&nbsp;[[|customer_name|]]</div></td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.tour_id.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td width="90%"><div class="detail_box">&nbsp;[[|tour_id|]]</div></td>
		</tr>
			<tr bgcolor="#E2F0DF">
			<td>&nbsp;</td>
			<td bgcolor="#B7DAB0">&nbsp;</td>
			<td bgcolor="#C8E1C3">
				<fieldset><legend>[[.reservation_room.]]</legend>
				<table width="100%">
					<tr>
					<th width="100" nowrap align="left" valign="top">
							[[.room_level_id.]]						</th><th width="100" nowrap align="left" valign="top">
							[[.room_id.]]
						</th><th width="80" nowrap align="left" valign="top">
							[[.price.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.currency_id.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.num_people.]]
						</th><th width="80" nowrap align="left" valign="top">
							[[.time_in.]]
						</th><th width="80" nowrap align="left" valign="top">
							[[.time_out.]]
						</th><th width="80" nowrap align="left" valign="top">
							[[.arrival_time.]]
						</th><th width="80" nowrap align="left" valign="top">
							[[.departure_time.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.confirm.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.note.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.total_amount.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.reduce_balance.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.deposit.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.reason.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.credit_limit.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.tax_rate.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.service_rate.]]
						</th><th width="150" nowrap align="left" valign="top">
							[[.payment_type_id.]]
						</th>
					</tr>
					<!--LIST:reservation_room_items-->
					<tr>
					<td width="100" align="left" valign="top">[[|reservation_room_items.room_level_id_name|]]</td><td width="100" align="left" valign="top">[[|reservation_room_items.room_id_name|]]</td><td width="80" align="left" valign="top">
							[[|reservation_room_items.price|]]
						</td><td width="50" align="left" valign="top">[[|reservation_room_items.currency_id_name|]]</td><td width="50" align="left" valign="top">
							[[|reservation_room_items.adult|]] [[.adult.]] + [[|reservation_room_items.child|]] [[.child.]]
						</td><td width="80" align="left" valign="top">
							[[|reservation_room_items.time_in|]]
						</td><td width="80" align="left" valign="top">
							[[|reservation_room_items.time_out|]]
						</td><td width="80" align="left" valign="top">
							[[|reservation_room_items.arrival_time|]]
						</td><td width="80" align="left" valign="top">
							[[|reservation_room_items.departure_time|]]
						</td><td width="50" align="left" valign="top">
							[[|reservation_room_items.confirm|]]
						</td><td width="100" align="left" valign="top">
							[[|reservation_room_items.note|]]
						</td><td width="100" align="left" valign="top">
							[[|reservation_room_items.total_amount|]]
						</td><td width="100" align="left" valign="top">
							[[|reservation_room_items.reduce_balance|]]
						</td><td width="100" align="left" valign="top">
							[[|reservation_room_items.deposit|]]
						</td><td width="100" align="left" valign="top">
							[[|reservation_room_items.reason|]]
						</td><td width="100" align="left" valign="top">
							[[|reservation_room_items.credit_limit|]]
						</td><td width="50" align="left" valign="top">
							[[|reservation_room_items.tax_rate|]]
						</td><td width="50" align="left" valign="top">
							[[|reservation_room_items.service_rate|]]
						</td><td width="150" align="left" valign="top">[[|reservation_room_items.payment_type_id_name|]]</td>
					</tr>
					<!--/LIST:reservation_room_items-->
				</table>
				</fieldset>
			</td>
		</tr> <tr bgcolor="#E2F0DF">
			<td>&nbsp;</td>
			<td bgcolor="#B7DAB0">&nbsp;</td>
			<td bgcolor="#C8E1C3">
				<fieldset><legend>[[.traveller.]]</legend>
				<table width="100%">
					<tr>
					<th width="100" nowrap align="left" valign="top">
							[[.room_id.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.first_name.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.last_name.]]
						</th><th width="50" nowrap align="left" valign="top">
							[[.gender.]]
						</th><th width="80" nowrap align="left" valign="top">
							[[.birth_date.]]
						</th><th width="150" nowrap align="left" valign="top">
							[[.passport.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.visa.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.note.]]
						</th><th width="150" nowrap align="left" valign="top">
							[[.phone.]]
						</th><th width="100" nowrap align="left" valign="top">
							[[.fax.]]
						</th><th width="250" nowrap align="left" valign="top">
							[[.address.]]
						</th><th width="150" nowrap align="left" valign="top">
							[[.email.]]
						</th><th width="150" nowrap align="left" valign="top">
							[[.nationality_id.]]
						</th>
					</tr>
					<!--LIST:traveller_items-->
					<tr>
					<td width="100" align="left" valign="top">[[|traveller_items.room_id_name|]]</td><td width="100" align="left" valign="top">
							[[|traveller_items.first_name|]]
						</td><td width="50" align="left" valign="top">
							[[|traveller_items.last_name|]]
						</td><td width="50" align="left" valign="top">
							[[|traveller_items.gender|]]
						</td><td width="80" align="left" valign="top">
							[[|traveller_items.birth_date|]]
						</td><td width="150" align="left" valign="top">
							[[|traveller_items.passport|]]
						</td><td width="100" align="left" valign="top">
							[[|traveller_items.visa|]]
						</td><td width="100" align="left" valign="top">
							[[|traveller_items.note|]]
						</td><td width="150" align="left" valign="top">
							[[|traveller_items.phone|]]
						</td><td width="100" align="left" valign="top">
							[[|traveller_items.fax|]]
						</td><td width="250" align="left" valign="top">
							[[|traveller_items.address|]]
						</td><td width="150" align="left" valign="top">
							[[|traveller_items.email|]]
						</td><td width="150" align="left" valign="top">[[|traveller_items.nationality_id_name|]]</td>
					</tr>
					<!--/LIST:traveller_items-->
				</table>
				</fieldset>
			</td>
		</tr> 
	<tr bgcolor="#EEEEEE" valign="top">
		<td colspan="3">
			<table cellpadding=5>
			<tr><td>
			<?php Draw::button('[[.list.]]',URL::build_current(array('reservation_customer_id',  
	)));?></td>
			<?php if(USER::can_edit(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button('[[.edit.]]',URL::build_current(array('reservation_customer_id',  
	)+array('cmd'=>'edit','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			if(USER::can_delete(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button('[[.delete.]]',URL::build_current(array('reservation_customer_id',  
	)+array('cmd'=>'delete','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			?></tr>
			</table>
		</td>
	</tr>
	</table>
<?php echo Draw::end_round_table();?>