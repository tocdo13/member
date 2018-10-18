<form name="ChangeRoomForm" method="post">
<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td>
			<table width="100%" class="w3-border" cellspacing="0" cellpadding="0">
				<tr height="40">
					<td width="90%" style="text-transform: uppercase; font-size: 20px; padding-left: 20px;"><i class="fa fa-refresh w3-text-orange" style="font-size: 24px;"></i> <?php echo Portal::language('change_room');?></td>
					<td ><input name="save" type="submit" class="w3-btn w3-orange w3-text-white" style="margin-left: 20px; text-transform: uppercase;" value="<?php echo Portal::language('save');?>" onclick="if(checkSave()==true){if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false;}}else{return false;}"/></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	  <td><table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC"  style="border:3px solid #CCCCCC;">
        <tr>
          <td width="50%"><?php echo Portal::language('change_from_room');?><select  name="from_reservation_room_id" id="from_reservation_room_id" onchange="change()" style="height: 24px; margin-left: 5px;"><?php
					if(isset($this->map['from_reservation_room_id_list']))
					{
						foreach($this->map['from_reservation_room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('from_reservation_room_id',isset($this->map['from_reservation_room_id'])?$this->map['from_reservation_room_id']:''))
                    echo "<script>$('from_reservation_room_id').value = \"".addslashes(URL::get('from_reservation_room_id',isset($this->map['from_reservation_room_id'])?$this->map['from_reservation_room_id']:''))."\";</script>";
                    ?>
	</select></td>
          <td bgcolor="#EFEFEF"><?php echo Portal::language('to_room');?> <select  name="to_room_id" id="to_room_id" style=" height: 24px; margin-left: 3px;"><?php
					if(isset($this->map['to_room_id_list']))
					{
						foreach($this->map['to_room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('to_room_id',isset($this->map['to_room_id'])?$this->map['to_room_id']:''))
                    echo "<script>$('to_room_id').value = \"".addslashes(URL::get('to_room_id',isset($this->map['to_room_id'])?$this->map['to_room_id']:''))."\";</script>";
                    ?>
	</select></td>
        </tr>
        <tr>
            <td colspan="2">
		  	<?php echo Portal::language('change_room_reason');?><br /><textarea  name="change_room_reason" id="change_room_reason" style="width:99%;" rows="5"><?php echo String::html_normalize(URL::get('change_room_reason',''));?></textarea><br />
		  	<div class="note"><?php echo Portal::language('you_have_to_input_at_less_3_letters');?></div>
            
            <!-- Manh: an cai nay di vi da dung trong setting -->
            <input style="display: none;" name="use_old_price" type="checkbox" id="use_old_price" value="1" checked="" /> <?php echo Portal::language('use_old_price');?>
            <!-- end Manh -->
		    </td>
          </tr>
      </table></td>
  </tr>
	<tr>
	  <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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