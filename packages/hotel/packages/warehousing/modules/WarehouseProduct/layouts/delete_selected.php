<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_selected_confirm'));?>
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="70%">[[.delete_selected_confirm.]]</td>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};DeleteSelectedProductForm.cmd.value='delete_selected';DeleteSelectedProductForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
                    <td><input type="button" value="[[.back.]]" onclick="window.location='<?php echo Url::build_current(array('portal_id'))?>'" class="button-medium-back"></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td >
			<form name="DeleteSelectedProductForm" method="post">      
			<table cellspacing="0" width="100%">
			<tr >
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0"border="1" bordercolor="C6E2FF" style="border-collapse:collapse">
						<input type="hidden" name="confirm" value="1">
						<tr class="table-header">
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
						<tr valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked></td>
							<td nowrap align="left">
									[[|items.code|]]
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
            </form>            
			</p>
		</td>
	</tr>
	</form>
	</table>	