<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td>&nbsp;</td>
		<td align="left" colspan="2"><font class="form_title"><b>[[.delete_title.]]</b></font></td>
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
	?><form name="DeleteLogForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.time.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|time|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.type.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|type|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.description.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|description|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.parameter.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|parameter|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.note.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|note|]]</div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.title.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|title|]]</div></td>
	</tr> 
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.user_id.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|user_id|]]</div>
		</td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong>[[.module_id.]]</strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<div class="detail_box">[[|module_id|]]</div>
		</td>
	</tr> 
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496" bgcolor="#C8E1C3">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3">
			<p>
			<table>
			<tr><td>
				<input type="submit"  onclick="this.disable=true;" style="width:80px"  value=" [[.delete.]]  "></td><td>
				<input type="button" onClick="location='?page=log';" value="[[.list.]]">
			</td></tr>
			</table>
			</p>
		</td>
	</tr>
	</form>
	</table>
