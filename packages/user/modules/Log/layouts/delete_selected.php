<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
			<font class="form_title"><b>[[.delete_selected_confirm.]]</b></font>		
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td width="100%">
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#8BB4A4" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedLogForm" method="post">
						<input type="hidden" name="confirm" value="1">
						<tr valign="middle">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.user_id.]]
							</th>
							<th nowrap align="left">
								[[.title.]]
							</th>
							<th nowrap align="left">
								[[.time.]]
							</th>
							<th nowrap align="left">
								[[.type.]]
							</th>
							<th nowrap align="left">
								[[.module_id.]]
							</th>
							<th nowrap align="left">
								[[.note.]]
							</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
								<td nowrap align="left">
									[[|items.user_id|]]
								</td>
								<td nowrap align="left">
									[[|items.title|]]
								</td>
								<td nowrap align="left">
									[[|items.time|]]
								</td>
								<td nowrap align="left">
									[[|items.type|]]
								</td>
								<td nowrap align="left">
									[[|items.module_id|]]
								</td>
								<td nowrap align="left">
									[[|items.note|]]
								</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><input type="submit"  onclick="this.disable=true;" value="[[.delete_selected.]]"></td>
				<td><input type="button" onClick="location='<?php echo Url::build_current();?>';" value="[[.list.]]"></td>
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>	
