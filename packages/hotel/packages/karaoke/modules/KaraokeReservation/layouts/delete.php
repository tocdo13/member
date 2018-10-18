<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?>
<table cellpadding="15" cellspacing="0" width="980">
	<tr valign="top">
		<td align="left" colspan="3" class="form-title">[[.delete_karaoke_reservation.]]</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="5" width="980">
	<tr bgcolor="#EEEEEE" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td width="100%">&nbsp;</td>
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
	?><form name="DeleteKaraokeReservationNewForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.code.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%">
			<div class="detail_box">[[|code|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.status.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%">
			<div class="detail_box">[[|status|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.note.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%">
			<div class="detail_box">[[|note|]]</div></td>
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
		<td width="100%">
			<b>[[.confirm_question.]]?</b>		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3" align="center">
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteKaraokeReservationNewForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current(array('karaoke_reservation_receptionist_id',   
)));?>
	<input type="hidden" name="confirm" value="1" id="confirm" />
			</td></tr>
			</table>
			</p>		</td>
	</tr>
	</form>
	</table>
