<form name="ChangeRoomForm" method="post">
<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td>
			<table width="100%" class="w3-border" cellspacing="0" cellpadding="0">
				<tr height="40">
					<td width="90%" style="text-transform: uppercase; font-size: 20px; padding-left: 20px;"><i class="fa fa-refresh w3-text-orange" style="font-size: 24px;"></i> [[.change_room.]]</td>
					<td ><input name="save" type="submit" class="w3-btn w3-orange w3-text-white" style="margin-left: 20px; text-transform: uppercase;" value="[[.save.]]" onclick="if(checkSave()==true){if(!confirm('[[.are_you_sure.]]')){return false;}}else{return false;}"/></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	  <td><table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC"  style="border:3px solid #CCCCCC;">
        <tr>
          <td width="50%">[[.change_from_room.]]<select name="from_reservation_room_id" id="from_reservation_room_id" onchange="change()" style="height: 24px; margin-left: 5px;"></select></td>
          <td bgcolor="#EFEFEF">[[.to_room.]] <select name="to_room_id" id="to_room_id" style=" height: 24px; margin-left: 3px;"></select></td>
        </tr>
        <tr>
            <td colspan="2">
		  	[[.change_room_reason.]]<br /><textarea name="change_room_reason" id="change_room_reason" style="width:99%;" rows="5"></textarea><br />
		  	<div class="note">[[.you_have_to_input_at_less_3_letters.]]</div>
            
            <!-- Manh: an cai nay di vi da dung trong setting -->
            <input style="display: none;" name="use_old_price" type="checkbox" id="use_old_price" value="1" checked="" /> [[.use_old_price.]]
            <!-- end Manh -->
		    </td>
          </tr>
      </table></td>
  </tr>
	<tr>
	  <td>&nbsp;</td>
  </tr>
</table>
</form>
<script>
var checksave=0;
function change()
{
    $room_id = jQuery('select[name=from_reservation_room_id]').val();
    console.log($room_id);
    $act = 'LoadRoom';
    jQuery('#to_room_id option').remove();
    jQuery.ajax({
        url:"search_room.php",
		type:"POST",
		data:{act:$act,room_id:$room_id},
		success:function(data)
        {
            res = jQuery.parseJSON(data);
            jQuery.each(res, function(i, obj){
                jQuery('#to_room_id').append(jQuery('<option>',{value: obj.id,text : obj.name}));
            });
        }
    });
}
function checkSave()
{
    checksave += 1;
    if(checksave==1)
        return true;
    else
        return false; 
}
</script>