<td width="50%">
	<table cellspacing="0" width="100%">
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#E2F0DF" valign="top">
	<td align="right" nowrap="nowrap">B&#225;o l&#7895;i</td>
	<td bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td bgcolor="#EFEFEE"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><form name="DeleteTelephoneNumberForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
		<tr bgcolor="#E2F0DF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.phone_number.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE">
				<div class="detail_box">[[|phone_number|]]</div>
			</td>
		</tr><tr bgcolor="#E2F0DF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="90%"><div class="detail_box">&nbsp;[[|room_id|]]</div></td>
		</tr>
	<tr bgcolor="#E2F0DF" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496" bgcolor="#EFEFEE">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	<tr bgcolor="#E2F0DF">
		<td colspan="3">
			<p>
			<table>
			<tr><td>
				<input type="button" onClick="history.go(-1);" value="[[.back.]]"></td><td>
				<input type="submit"  name="confirm" onClick="this.disable=true;" style="width:80px"  value="[[.delete.]]"></td></tr>
			</table>
			</p>
		</td>
	</tr>
	</form>
	</table>
</td></tr></table>
</td></tr></table>
