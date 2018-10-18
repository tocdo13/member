<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_selected_confirm'));?>
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.delete_selected_confirm.]]</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td >
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#8BB4A4" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedRestaurantProductForm" method="post">
						<input type="hidden" name="confirm" value="1">
						<tr valign="middle" bgcolor="#9ECA95">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.code.]]
							</th><th nowrap align="left">
								[[.type.]]
							</th><th nowrap align="left">
								[[.name.]]
							</th><th nowrap align="left">
								[[.category_id.]]
							</th><th nowrap align="left">
								[[.unit_id.]]
							</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
							<td nowrap align="left">
									[[|items.id|]]
								</td><td nowrap align="left">
									[[|items.type|]]
								</td><td nowrap align="left">
									[[|items.name|]]
								</td><td nowrap align="left">
									[[|items.category_id|]]
								</td><td nowrap align="left">
									[[|items.unit_id|]]
								</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<input type="hidden" name="cmd" value="delete_selected">
			<table><tr>
				<td><input type="submit" value="[[.delete_selected.]]" name="confirm"/></td>
				<td>
					<?php Draw::button(Portal::language('list'),Url::build_current());?></td>
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>	