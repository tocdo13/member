<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_shop'));?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="3">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.delete_shop.]]</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EFEFEF"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td bgcolor="#EFEFEF" width="100%">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EFEFEF" valign="top">
	<td align="right" nowrap="nowrap">B&#225;o l&#7895;i</td>
	<td bgcolor="#EFEFEF"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td bgcolor="#EFEFEF"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><form name="DeleteShopForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
		<tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.code.]]</strong></span></td>
			<td bgcolor="#EFEFEF" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEF">
				<div class="detail_box">[[|code|]]</div>
			</td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.name.]]</strong></span></td>
			<td bgcolor="#EFEFEF" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEF">
				<div class="detail_box">[[|name|]]</div>
			</td>
		</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#EFEFEF"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496" bgcolor="#EFEFEF">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF">
		<td bgcolor="#EFEFEF" colspan="3">
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteShopForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current());?>
			</td></tr>
			</table>
			</p>
		</td>
	</tr>
	</form>
	</table>