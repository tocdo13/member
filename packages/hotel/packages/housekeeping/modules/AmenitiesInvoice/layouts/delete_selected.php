<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
	<tr >
		<td align="left" colspan="2">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.delete_selected_confirm.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#delete_selected">
						<img src="skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" >
		<td width="100%">
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#7F9DB9" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedMinibarInvoiceForm" method="post">
						<input type="hidden" name="confirm" value="1"/>
						<tr >
							<th width="1%"><input type="checkbox" value="1" checked="checked" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">[[.time.]]</th>
                            <th nowrap align="right">[[.total.]]</th>
                            <th nowrap align="right">[[.prepaid.]]</th>
                            <th nowrap align="right">[[.remain.]]</th>
                            <th nowrap align="left">[[.reservation_room_id.]]</th>
                            <th nowrap align="left">[[.room_id.]]</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="white"  <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:pointer;">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;" checked="checked" /></td>
							<td nowrap align="left">[[|items.time|]]</td>
                            <td nowrap align="right">[[|items.total|]]</td>
                            <td nowrap align="right">[[|items.prepaid|]]</td>
                            <td nowrap align="right">[[|items.remain|]]</td>
                            <td nowrap align="left">[[|items.reservation_room_id|]]</td>
                            <td nowrap align="left">[[|items.room_id|]]</td>
						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button(Portal::language('delete_selected'),'',false,true,'DeleteSelectedAmenitiesInvoiceForm');?></td>
				<td><?php Draw::button(Portal::language('list'),Url::build_current());?></td>
			</tr></table>
			</p>
		</td>
	</tr>
	</form>
	</table>