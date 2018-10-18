<form name="ChangeRoomForm" method="post">
<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td>
			<table width="100%" border="o" cellspacing="0" cellpadding="0">
				<tr height="40">
					<td width="90%" class="form-title">[[.change_room.]]</td>
					<td><input name="save" type="submit" class="button-medium-save" value="[[.save.]]" onclick="if(!confirm('[[.are_you_sure.]]')){return false;}"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	  <td><table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC"  style="border:3px solid #CCCCCC;">
        <tr>
          <td width="50%">[[.change_from_room.]]<select name="from_reservation_room_id" id="from_reservation_room_id"></select></td>
          <td bgcolor="#EFEFEF">[[.to_room.]] <select name="to_room_id" id="to_room_id"></select></td>
        </tr>
        <tr>
            <td colspan="2">
		  	[[.change_room_reason.]]<br /><textarea name="change_room_reason" id="change_room_reason" style="width:99%;" rows="5"></textarea><br />
		  	<div class="note">[[.you_have_to_input_at_less_3_letters.]]</div>
		    </td>
          </tr>
      </table></td>
  </tr>
	<tr>
	  <td>&nbsp;</td>
  </tr>
</table>
</form>