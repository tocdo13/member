<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<style>
body,p,table,span,div,tr,td
{
font-size:14px;
}
</style>
<?php echo Draw::begin_round_table();?><table cellspacing="0" width="100%" cellpadding="5px">
	<tr valign="top">
		<td bgcolor="#FFFFFF">&nbsp;</td>
		<td colspan="2" align="left" bgcolor="#FFFFFF"><font class="form_title"><b>[[.detail_title.]]</b></font></td>
	</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.user_id.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|user_id|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.title.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|title|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.description.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|description|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.time.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|time|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.type.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|type|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.module_id.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|module_id|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.parameter.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|parameter|]]</div></td>
		</tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right" nowrap bgcolor="#FFFFFF"><span ><strong>[[.note.]]</strong></span></td>
			<td bgcolor="#FFFFFF" align="center"><div style="width:10px;">:</div></td>
		  <td bgcolor="#FFFFFF" width="100%"><div>&nbsp;[[|note|]]</div></td>
		</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td bgcolor="#FFFFFF">&nbsp;</td>
		<td bgcolor="#FFFFFF">&nbsp;</td>
		<td bgcolor="#FFFFFF">
			<table cellpadding=5>
			<tr><td>
			<?php Draw::button('[[.list.]]',URL::build_current());?></td><td>
			<?php Draw::button('[[.edit.]]',URL::build_current(array('cmd'=>'edit','id')));?></td><td>
			<?php Draw::button('[[.delete.]]',URL::build_current(array('cmd'=>'delete','id')));?></td></tr>
			</table>
		</p>
	  </td>
	</tr>
	</table>
<?php echo Draw::end_round_table();?>