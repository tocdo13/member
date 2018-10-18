<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css"/>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?>
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td class="form-title" width="100%">[[.delete_equipment_from_room.]]</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td bgcolor="#EEEEEE">&nbsp;</td>
	</tr>
	<?php Form::$current->error_messages(); ?>
	<form name="DeleteHousekeepingEquipmentForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"/>
    <input type="hidden" name="cmd" value="delete"/>
	<tr><td>
	<table width="100%">
		<tr bgcolor="#EEEEEE" height="24">
          <td align="right" width="15%"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
		  <td align="center">:</td>
		  <td>[[|room_name|]]</td>
		  </tr>
		<tr bgcolor="#EEEEEE" height="24">
          <td align="right"><strong>[[.product_name.]]</strong></td>
		  <td align="center">:</td>
		  <td>[[|product_id|]] - [[|room_name|]]</td>
		  </tr>
		<tr bgcolor="#EEEEEE" height="24">
          <td align="right"><strong>[[.quantity.]]</strong></td>
		  <td align="center">:</td>
		  <td>[[|quantity|]]</td>
		  </tr>
		<tr bgcolor="#EEEEEE" valign="top">
			<td align="right">&nbsp;</td>
			<td width="10"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
			<td width="496"><b>[[.confirm_question.]]</b></td>
		</tr>
	</table>
	</td></tr>
	<tr bgcolor="#EEEEEE">
		<td>
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),'confirm',false,true,'DeleteHousekeepingEquipmentForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current(array('housekeeping_equipment_old_store_id',)));?>
			</td></tr>
			</table>
			</p>
		</td>
	</tr>
	</form>
	</table>