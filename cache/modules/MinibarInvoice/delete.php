<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css"/>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
	<tr >		
		<td align="left" colspan="3">
			<table cellpadding="15" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td class="form-title" width="100%"><?php echo Portal::language('delete_minibar_invoice');?></td>
				</tr>
			</table>		</td>
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
	<form name="DeleteMinibarInvoiceForm" method="post">
	<input type="hidden" name="id" value="<?php echo $this->map['id'];?>"/><input type="hidden" name="cmd" value="delete"/>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><div style="line-height:24px;"><strong><?php echo Portal::language('reservation_room_id');?></strong></div></td>
			<td bgcolor="#ECE9D8" align="center">:</td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;<?php echo $this->map['reservation_room_id'];?></div></td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong><?php echo Portal::language('minibar_id');?></strong></span></td>
			<td bgcolor="#ECE9D8" align="center">:</td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;<?php echo $this->map['minibar_id'];?></div></td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong><?php echo Portal::language('employee_id');?></strong></span></td>
			<td bgcolor="#ECE9D8" align="center">:</td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;<?php echo $this->map['employee_id'];?></div></td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong><?php echo Portal::language('time');?></strong></span></td>
			<td bgcolor="#ECE9D8" align="center">:</td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box"><?php echo $this->map['time'];?></div>			</td>
		</tr>
		<tr bgcolor="#EEEEEE" >
			<td nowrap align="right"><span style="line-height:24px;"><strong><?php echo Portal::language('total');?></strong></span></td>
			<td bgcolor="#ECE9D8" align="center">:</td>
			<td bgcolor="#EEEEEE">
				<div class="detail_box"><?php echo $this->map['total'];?></div>			</td>
		</tr>
	<tr bgcolor="#EEEEEE" >
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="100%" bgcolor="#EFEFEE">
			<b><?php echo Portal::language('confirm_question');?></b>		</td>
	</tr>
	<tr bgcolor="#EEEEEE" >
		<td nowrap align="right"><span style="line-height:24px;"><strong><?php echo Portal::language('note');?></strong></span></td>
		<td bgcolor="#ECE9D8" align="center">:</td>
		<td class="warning-invoice-messeage"><?php echo Portal::language('warning_invoice_messeage');?></td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3">
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteMinibarInvoiceForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_minibar_id', 'housekeeping_invoice_employee_id', 'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',)));?>
			</td></tr>
			</table>
			</p>		
        </td>
	</tr>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</table>
