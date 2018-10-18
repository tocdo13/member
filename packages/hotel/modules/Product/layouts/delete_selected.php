<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_selected_confirm'));?>
<form name="DeleteSelectedProductForm" method="post">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.delete_selected_confirm.]]</td>
					<td width="30%" align="right" style="padding-right: 30px;"><input type="submit" value="[[.delete.]]" class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px;"/>
                    <input type="button" value="[[.back.]]" onclick="window.location='<?php echo Url::build_current(array('portal_id'))?>'" class="w3-btn w3-green" style="text-transform: uppercase; margin-right: 5px;"/></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td >
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" border="1">
						<input type="hidden" name="confirm" value="1">
						<tr class="table-title">
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
							<td>
                                <?php if([[=items.check=]]==0){?>
                                <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked>
                                <?php }?>
                            </td>
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
                                <?php if([[=items.check=]]==1){?>
                                <td><span style="color: red;">[[.da_co_du_lieu_khong_the_xoa.]]</span>
                                <?php }?></td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<input type="hidden" name="cmd" value="delete_selected">
		</td>
	</tr
></table>	
</form>