<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
	<tr >		
		<td align="left" colspan="3">
			<table cellpadding="15" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td class="form-title" width="100%">[[.delete_damage_equipment_invoice.]]</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" >
		<td align="right">&nbsp;</td>
		<td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td bgcolor="#EFEFEE" width="100%">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<form name="DeleteEquipmentInvoiceForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><div style="line-height:24px;"><strong>[[.reservation_room_id.]]</strong></div></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|reservation_room_id|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|minibar_id|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.employee_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|employee_id|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.time.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|time|]]</div>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.tax.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|tax_rate|]]</div>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.discount.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|discount|]]</div>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.total.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|total|]]</div>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.prepaid.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|prepaid|]]</div>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.remain.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box">[[|remain|]]</div>
			</td>
		</tr>
	<tr bgcolor="#EEEEEE" >
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496" bgcolor="#EFEFEE">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" >
		<td nowrap align="right"><span style="line-height:24px;"><strong>[[.note.]]</strong></span></td>
		<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
		<td class="warning-invoice-messeage">[[.warning_invoice_messeage.]]</td>
	</tr>
	<tr bgcolor="#EEEEEE">

		<td bgcolor="#EEEEEE" align="right">
            <input type="submit" value="[[.delete.]]"/>
		</td>
        <td></td>
        <td bgcolor="#EEEEEE">
            <input type="button" value="[[.list.]]" onclick="window.location='<?php echo Url::build_current(); ?>'"/>
		</td>
	</tr>
	</form>
	</table>