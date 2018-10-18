<form name="DeleteSelectedTelephoneFeeForm" method="post">
<td >
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#7F9DB9" border="1" style="border-collapse:collapse">
					
						<input type="hidden" name="confirm" value="1">
						<tr valign="middle" bgcolor="#EBEADC">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.name.]]							</th><th nowrap align="left">
								[[.prefix.]]
							</th><th nowrap align="right">
								[[.fee.]]
							</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
							<td nowrap align="left">
									[[|items.name|]]								</td><td nowrap align="left">
									[[|items.prefix|]]
								</td><td nowrap align="right">
									[[|items.fee|]]
								</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><input type="submit"  name="delete_selected" onClick="this.disable=true;" value="[[.delete.]]"></td>
				<td>
				<input type="button" onClick="location='?page=telephone_fee';" style="width:80px"  value="[[.back.]]"></td>
			</tr></table>
			</p>
		</td>
	</tr>

	</table>	
</td></tr>
</table>
	</form>
