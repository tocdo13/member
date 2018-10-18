<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td class="" width="100%" style="text-transform: uppercase; font-size: 18px;"><i class="fa fa-times-circle w3-text-red" style="font-size: 24px;"></i> [[.confirm_delete.]]</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td >
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#8BB4A4" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedHousekeepingEquipmentForm" method="post">
						<input type="hidden" name="confirm" value="1"/>
						<tr valign="middle" class="w3-light-gray" style="text-transform: uppercase;">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th >[[.product_name.]]</th>
							<th align="center">[[.quantity.]]</th>
							<th align="center">[[.room_id.]]</th>
							<th align="center">[[.time_import.]]</th>
						</tr>
						<!--LIST:items-->
						<!--<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">-->
                        <tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked="checked" /></td>
							<td nowrap>[[|items.product_name|]]</td>
							<td nowrap align="center">[[|items.quantity|]]</td>
							<td nowrap align="center">[[|items.room_name|]]</td>
							<td nowrap align="center">[[|items.time|]]</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button(Portal::language('delete_selected'),'delete_selected',false,true,'DeleteSelectedHousekeepingEquipmentForm');?></td>
				<td><?php Draw::button(Portal::language('return_list'),Url::build_current());?></td>
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>