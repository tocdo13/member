<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_selected_confirm'));?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.delete_selected_confirm.]]</b></font>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedShopForm" method="post">
						<input type="hidden" name="confirm" value="1">
						<tr valign="middle">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.code.]]
							</th><th nowrap align="left">
								[[.name.]]
							</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
							<td nowrap align="left">
									[[|items.code|]]
								</td><td nowrap align="left">
									[[|items.name|]]
								</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button(Portal::language('delete_selected'),'delete_selected',false,true,'DeleteSelectedShopForm');?></td>
				<td><?php Draw::button(Portal::language('list'),Url::build_current());?></td>
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>