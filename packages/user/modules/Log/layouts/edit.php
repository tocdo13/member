<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<script src="package_system/library/calendar.js">
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<span style="display:none;">
	<span style="display:none">
</span>
</span>
<table cellspacing="0" cellpadding="1" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse"  width="100%">
	<tr valign="top">
		<td>&nbsp;</td>
		<td align="left" colspan="2"><font class="form_title"><b>[[.edit_title.]]</b></font></td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EEEEEE"><div style="width:10px;line-height:24px">&nbsp;</div></td>
	<td bgcolor="#EEEEEE">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EEEEEE" valign="top">
	<td align="right">B&#225;o l&#7895;i</td>
	<td bgcolor="#EEEEEE"><div style="width:10px;line-height:24px;">&nbsp;</div></td>
	<td bgcolor="#EEEEEE"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><form name="dsadas" id="ds" method="post" >
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.user_id.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><span style="line-height:24px;">:</span></td>
			<td bgcolor="#EEEEEE">
				<select name="user_id" id="user_id"></select>			</td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.title.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;line-height:24px;">:</div></td>
			<td bgcolor="#EEEEEE">
			<input name="title" type="text" id="title" value="[[|title|]]" size="50">			</td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.description.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;line-height:24px;">:</div></td>
			<td bgcolor="#EEEEEE">
			<textarea name="description" id="description" cols="50" rows="">[[|description|]]</textarea>			</td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.type.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;line-height:24px;">:</div></td>
			<td bgcolor="#EEEEEE">
			<input name="type" type="text" id="type" value="[[|type|]]" size="50">			</td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.module_id.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><span style="line-height:24px;">:</span></td>
			<td bgcolor="#EEEEEE">
				<select name="module_id" id="module_id"></select>			</td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.parameter.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;line-height:24px;">:</div></td>
			<td bgcolor="#EEEEEE">
			<input name="parameter" type="text" id="parameter" value="[[|parameter|]]" size="50">			</td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap><strong>[[.note.]]</strong></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;line-height:24px;">:</div></td>
			<td bgcolor="#EEEEEE">
			<textarea name="note" id="note" cols="50" rows="5">[[|note|]]</textarea><script>editor_generate("note");</script>			</td>
		</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3">
			<p>
			<table>
			<tr><td>
				<input type="submit" onClick="location='?page=log&cmd=edit&id=20632';" style="width:80px"  value=" [[.save.]]">
				</td><td>
				<input type="button" onClick="location='?page=log';" value="[[.list.]]">
			</td></tr>
			</table>
			</p>		</td>
	</tr>
	</form>
	</table>
