<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<td width="50%">
	<table cellspacing="0" width="100%">
		<tr bgcolor="#E2F0DF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.phone_number.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE">
				<div class="detail_box">[[|number|]]</div>
			</td>
		</tr><tr bgcolor="#E2F0DF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="90%"><div class="detail_box">&nbsp;[[|room_id|]]</div></td>
		</tr>
	<tr bgcolor="#E2F0DF" valign="top">
		<td colspan="3">
			<table cellpadding=5>
			<tr><td>
			<?php Draw::button('[[.back.]]',URL::build_current());?></td>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button('[[.edit.]]',URL::build_current(array()+array('cmd'=>'edit','edit_selected'=>1,'selected_ids'=>$_REQUEST['id'])));?></td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button('[[.delete.]]',URL::build_current(array()+array('cmd'=>'delete','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			?></tr>
			</table>
		</td>
	</tr>
	</table>
</td></tr></table>
	</td></tr></table>
<?php echo Draw::end_round_table();?>