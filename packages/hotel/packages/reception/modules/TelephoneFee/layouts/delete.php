<td width="50%">
	<table cellspacing="0" width="100%">
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EEEEEE" valign="top">
	<td><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><form name="DeleteTelephoneFeeForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
	<tr><td>
	<table>
		<tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.name.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|name|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.prefix.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|prefix|]]</div>
			</td>
		</tr><tr bgcolor="#EEEEEE" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.fee.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|fee|]]</div>
			</td>
		</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	</table>
	</td></tr>
	<tr bgcolor="#EEEEEE">
		<td>
			<p>
			<table>
			<tr><td>
				<input type="submit"  name="confirm" onClick="this.disable=true;" style="width:80px"  value="[[.delete.]]"></td><td>
				<input type="button" onClick="location='?page=telephone_fee';" value="[[.back.]]"></td></tr>
			</table>
			</p>
		</td>
	</tr>
	</form>
	</table>
</td></tr></table>
</td></tr></table>
<?php echo Draw::end_round_table();?>