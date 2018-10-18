<?php System::set_page_title(Portal::get_setting('company_name','').' '.'[[.delete_title.]]');?><?php echo Draw::begin_round_table();?><table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.delete_selected_confirm.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>$GLOBALS['current_block']->data['module_id'],'href'=>'?'.$_SERVER['QUERY_STRING']));?>#delete_selected">
						<img src="skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
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
					<form name="DeleteSelectedTravellerForm" method="post">
						<input type="hidden" name="confirm" value="1">
						<tr valign="middle" bgcolor="#9ECA95">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.first_name.]]
							</th><th nowrap align="left">
								[[.last_name.]]
							</th><th nowrap align="left">
								[[.gender.]]
							</th><th nowrap align="left">
								[[.birth_date.]]
							</th><th nowrap align="left">
								[[.note.]]
							</th><th nowrap align="left">
								[[.tour_id.]]
							</th>
							<th bgcolor="#E2F1DF">&nbsp;</th>
							<th bgcolor="#E2F1DF">&nbsp;</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
							<td nowrap align="left">
									[[|items.first_name|]]
								</td><td nowrap align="left">
									[[|items.last_name|]]
								</td><td nowrap align="left">
									[[|items.gender|]]
								</td><td nowrap align="left">
									[[|items.birth_date|]]
								</td><td nowrap align="left">
									[[|items.note|]]
								</td><td nowrap align="left">
									[[|items.tour_id|]]
								</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button('[[.delete_selected.]]','delete_selected',false,true,'DeleteSelectedTravellerForm');?></td>
				<td>
					<?php Draw::button('[[.list.]]',Url::build_current(array('deleted')));?></td>
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>	
<?php echo Draw::end_round_table();?>