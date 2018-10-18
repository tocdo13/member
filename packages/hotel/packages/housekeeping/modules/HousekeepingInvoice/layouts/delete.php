<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
	<tr valign="top">
		<td>&nbsp;</td>
		<td align="left" colspan="2">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.delete_title.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#delete">
						<img src="packages/hotel/skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td bgcolor="#EFEFEE" width="100%">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<form name="DeleteHousekeepingInvoiceForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
		<tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.customer_name.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|customer_name|]]</div></td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|room_id|]]</div></td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.user.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|user_name|]]</div></td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.time.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|time|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.tax.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|tax_rate|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.discount.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|discount|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.total.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|total|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.prepaid.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|prepaid|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.remain.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|remain|]]</div>
			</td>
		</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496" bgcolor="#EFEFEE">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3">
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteHousekeepingInvoiceForm');?></td><td>
				<p><?php Draw::button(Portal::language('list'),URL::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_minibar_id', 'housekeeping_invoice_employee_id', 
	'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',      
	)));?></p>
			</td></tr>
			</table>
			</p>
		</td>
	</tr>
	</form>
	</table>