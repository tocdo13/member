<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="3">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.Delete_invoice.]]</td>
                </tr>
			</table>        
        </td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
        <td align="right">&nbsp;</td>
        <td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
        <td bgcolor="#C8E1C3" width="100%">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EEEEEE" valign="top">
	<td align="right" nowrap="nowrap">B&#225;o l&#7895;i</td>
	<td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td bgcolor="#EEEEEE"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><form name="DeleteShopInvoiceForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.code.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%" bgcolor="#C8E1C3">
			<div class="detail_box">[[|id|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.agent_name.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%" bgcolor="#C8E1C3">
			<div class="detail_box">[[|agent_name|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.agent_address.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%" bgcolor="#C8E1C3">
			<div class="detail_box">[[|agent_address|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.time.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%" bgcolor="#C8E1C3">
			<div class="detail_box"><?php echo date('d/m/Y',[[=time=]]);?></div></td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	  <td nowrap align="right">&nbsp;</td>
	  <td bgcolor="#EEEEEE">&nbsp;</td>
	  <td bgcolor="#EEEEEE">
	  	<fieldset>
			<legend>[[.canceler_info.]]</legend>
			<textarea name="cancel_note" style="width:80%" rows="7"></textarea>
		</fieldset>	  </td>
	  </tr> 
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="100%" bgcolor="#C8E1C3">
			<b>[[.confirm_question.]]</b>		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3">
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteShopInvoiceForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id',   
)));?>
	<input type="hidden" name="confirm" value="1" id="confirm" />
			</td></tr>
			</table>
			</p>		</td>
	</tr>
	</form>
	</table>
