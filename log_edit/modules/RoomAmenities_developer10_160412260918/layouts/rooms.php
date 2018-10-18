<style>
.room-button{
    width: 60px;height: 60px;
    border: 1px solid #008B8B;
    float: left;
    margin-left: 3px;
    margin-top: 3px;
    text-align: center;
    background-color: #E7E6F1;
    cursor: pointer;
    line-height: 25px;
}
.room-button-select{
    background-color: #5F9EA0;
    color: white;
    width: 60px;height: 60px;
    border: 1px solid #008B8B;
    float: left;
    margin-left: 3px;
    margin-top: 3px;
    text-align: center;
    cursor: pointer;
    line-height: 25px;
}
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div class="body">
<form name="ListForm" method="post">
	<table cellpadding="0" cellspacing="0" width="50%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;width: 500px;"  nowrap>[[.declare_amenities.]] </td>
			<td align="right" nowrap="nowrap" rowspan="2" style="line-height: ;">
				<input type="button" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px;" onclick="get_room_checked('add')" value="[[.asc_amenities.]]"/>
                <input type="button" class="w3-btn w3-pink w3-text-white" style="text-transform: uppercase; margin-right: 5px;" onclick="get_room_checked('delete')" value="[[.desc_amenities.]]"/>
                <?php if(User::is_admin()){ ?> <input type="submit" class="w3-btn w3-pink w3-text-white" style="text-transform: uppercase; margin-right: 5px;" onclick="delete_all()" value="[[.delete_all.]]"/> <?php } ?>
                <input name="act" type="hidden" id="act" />
			</td>
        </tr>
        <tr>
            <td style="width: 50px;" align="left"><input type="checkbox" title="[[.select_all.]]"  id="selectall" onclick="select_all_room();" style="width: 20px;height: 20px;margin-left: 20px;margin-top: 10px;" /></td>
        </tr>
    </table><br />
	<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
		<!--LIST:floors-->		
		<tr>
			<td width="60px" nowrap  align="center">
            <strong>[[|floors.name|]]</strong>
            <input id="[[|floors.class_room_floor|]]" type="checkbox" onclick="check_floor('[[|floors.class_room_floor|]]');" style="width: 20px;height: 20px;" />
            </td>
            <?php $i=1;?>
			<td>
			<!--LIST:floors.rooms-->
                <div id="[[|floors.rooms.id|]]" class="room room-button room_level_brief_name_[[|floors.rooms.room_level_brief_name|]] floor-[[|floors.class_room_floor|]]">[[|floors.rooms.room_name|]]<br />[[|floors.rooms.room_level_brief_name|]]</div>
			<!--/LIST:floors.rooms-->
          	</td>
		</tr>		
		<!--/LIST:floors-->	
	</table>
  <input type="hidden" name="room-selected" id="room-selected" />
  <input type="hidden" name="deleteall" id="deleteall" />
  <input type="hidden" name="type" id="type" />
</form>	

</div>
<script>
jQuery( ".room" ).each(function(){
    jQuery(this).click(function(){
        if(jQuery(this).hasClass('room-button')){
            jQuery(this).removeClass('room-button');
            jQuery(this).addClass('room-button-select');
        }else{
            jQuery(this).removeClass('room-button-select');
            jQuery(this).addClass('room-button');
        }
    });
});
function check_floor(floor_name)
{
   if(jQuery('#'+floor_name).attr('checked')=='checked'){
      jQuery('.floor-'+floor_name).removeClass('room-button');
      jQuery('.floor-'+floor_name).addClass('room-button-select');
   }else{
      jQuery('.floor-'+floor_name).removeClass('room-button-select');
      jQuery('.floor-'+floor_name).addClass('room-button');
   }
}
function select_all_room(){

    if(jQuery('#selectall').attr('checked')=='checked'){
      jQuery('.room').removeClass('room-button');
      jQuery('.room').addClass('room-button-select');
   }else{
      jQuery('.room').removeClass('room-button-select');
      jQuery('.room').addClass('room-button');
   }
}
function get_room_checked(type){
    if(jQuery('.room-button-select').length>=1){
        var ids='';
        jQuery('.room-button-select').each(function(){
          if(ids==''){
            ids=jQuery(this).attr('id');
          }else{
            ids+=','+jQuery(this).attr('id');
          }
        });
        jQuery('#room-selected').val(ids);
        jQuery('#type').val(type);
        window.location='?page=room_amenities&cmd='+type+'&rooms='+ids+'';
    }else{
        alert('[[.select_room.]]');
    }
}
function delete_all(){
    if(confirm([['are_you_sure_delete_all']])){
        jQuery('#deleteall').val(1);
        ListForm.submit();
    }
}
</script>