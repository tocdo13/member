<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?>
<table cellpadding="15" cellspacing="0" width="980">
	<tr valign="top">
		<td align="left" class="form-title">[[.delete_selected_confirm.]]</td>
	</tr>
</table>
<table cellspacing="0" width="100%">
	<tr bgcolor="#EEEEEE" valign="top">
		<td width="100%">
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#8BB4A4" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedKaraokeReservationNewForm" method="post">
						<input type="hidden" name="confirm" value="1">
						<tr valign="middle">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.reservation_time.]]
							</th>
							<th nowrap align="left">
								[[.time_length.]]
							</th>
							<th nowrap align="left">
								[[.code.]]
							</th>
							<th nowrap align="left">
								[[.status.]]
							</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
								<td nowrap align="left">
									[[|items.arrival_date|]]
								</td>
								<td nowrap align="left">
									[[|items.time_length|]]
								</td>
								<td nowrap align="left">
									[[|items.code|]]
								</td>
								<td nowrap align="left">
									[[|items.status|]]
								</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button(Portal::language('delete_selected'),'',false,true,'DeleteSelectedKaraokeReservationNewForm');?></td>
				<td><?php Draw::button(Portal::language('list'),Url::build_current());?></td>
                <input type="hidden" name="confirm" value="1" id="confirm" />
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>	