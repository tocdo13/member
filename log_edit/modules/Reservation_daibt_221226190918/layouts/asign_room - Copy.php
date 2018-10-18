<span style="display:none;">
	<span id="mi_reservation_room_sample">
		<span id="input_group_#xxxx#">
		<div id="reservation_room_bound_#xxxx#" onblur="updateRoomForTraveller('#xxxx#');" onmouseover="updateRoomForTraveller('#xxxx#');">
        	<span class="multi-input" id="index_#xxxx#" style="width:54px;font-size:10px;color:#F90;"></span>
			<input  name="mi_reservation_room[#xxxx#][id]" type="text" id="id_#xxxx#" style="float:left;width:30px;font-size:10px;border:1px solid #CCCCCC;background:#EFEFEF;color:#999999;" readonly="" class="hidden">
 	 		<a name="#17"></a>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_level_name]" type="text" id="room_level_name_#xxxx#" style="width:150px;" readonly="readonly" class="readonly">
				<input  name="mi_reservation_room[#xxxx#][room_level_id]" type="hidden" id="room_level_id_#xxxx#">
			</span>
            <span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name_1]" type="text" id="room_name_1_#xxxx#"  style="width:30px;font-weight:bold;" readonly="readonly" class="readonly">
           </span>
			<span class="multi-input">
				<input  name="mi_reservation_room[#xxxx#][room_name]" type="text" id="room_name_#xxxx#"  style="width:50px;font-weight:bold;" readonly="readonly" class="readonly">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_#xxxx#" onclick="window.open('?page=room_map&cmd=select&act=asign_room&room_id='+$('room_id_#xxxx#').value+'&room_level_id='+$('room_level_id_#xxxx#').value+'&object_id=#xxxx#&input_count='+input_count,'select_room');return false;" style="cursor:pointer;display:none;">
				<input  name="mi_reservation_room[#xxxx#][room_id]" type="hidden" id="room_id_#xxxx#" style="width:60px;background:#FFCC00;">
				<input  name="mi_reservation_room[#xxxx#][room_name_old]" type="hidden" id="room_name_old_#xxxx#">
				<input  name="mi_reservation_room[#xxxx#][room_id_old]" type="hidden" id="room_id_old_#xxxx#">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][adult]" type="text" id="adult_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/adult.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][child]" type="text" id="child_#xxxx#" style="width:20px;" AUTOCOMPLETE=OFF><img src="packages/core/skins/default/images/buttons/child.png" align="top"></span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][price]" style="width:70px;" type="text" id="price_#xxxx#" onchange="count_price('#xxxx#',true);" onblur="count_price('#xxxx#',true);" class="price">
			</span>
            <span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_in]" style="width:50px;" type="text" id="time_in_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);count_price('#xxxx#',true);" maxlength="5"  readonly="readonly"></span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][time_out]" style="width:50px;" type="text" id="time_out_#xxxx#" title="00:00" onchange="count_price('#xxxx#',false);" maxlength="5"  readonly="readonly">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][arrival_time]" style="width:70px;" type="text" id="arrival_time_#xxxx#" onchange="count_price('#xxxx#',false);" class="date-select" readonly="readonly">
			</span>
			<span class="multi-input">
					<input  name="mi_reservation_room[#xxxx#][departure_time]" style="width:70px;" type="text" id="departure_time_#xxxx#" onchange="count_price('#xxxx#',false);updateRoomForTraveller('#xxxx#');" class="date-select"  readonly="readonly">
					<input  name="mi_reservation_room[#xxxx#][departure_time_old]" type="hidden" id="departure_time_old_#xxxx#"  readonly="readonly">
			</span>
            <span id="note" >
            	<span id="note_#xxxx#" style="border:none; background:#F2F4CE;color:#F00;"></span>
            </span>
            <span class="multi-input">
				<img src="skins/default/images/cmd_Tim.gif" id="select_room_level_#xxxx#" onclick="jQuery('#room_list').css({'display':'block','top':200,'left':500});jQuery('#room_select').val(#xxxx#);" style="cursor:pointer;display:none;" title="[[.select_room.]]">
			</span>
           <br clear="all">
        </div>
     </span>
  </span>
</span>
<form name="AsignReservationForm" method="post">
<div style="text-align:center;">
<div style="margin-right:auto;margin-left:auto;width:970px;">
    <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;">
        <tr valign="top">
            <td align="left">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-bound">
                    <tr height="40">
                        <td width="90%" class="form-title">[[.reservation.]]</td>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="update" type="submit" value="[[.save.]]" class="button-medium-save" onclick="jQuery('#mask').show();"></td><?php }?>
                    </tr>
              </table>
            </td>
        </tr>
        <tr valign="top">
        <td><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
		<fieldset style="background:#EFEFEF;margin-bottom:5px;">
			<legend class="legend-title">[[.general_information.]]</legend>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.booking_code.]]</td>
                        <td align="left" style="padding-left:10px;"><input  name="booking_code" id="booking_code" style="width:215px;" type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
                    <td rowspan="4" width="40%"><span class="label">[[.note_for_tour_or_group.]]</span>
                      <textarea  name="note" id="note" style="width:99%;height:40px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea>			  <a href="#" onclick="
                            if($('head_table').style.display=='none')
                            {
                                $('head_table').style.display='';
                                $('expand_r_img_#xxxx#').src='skins/default/images/up.gif';
                            }
                            else
                            {
                                $('head_table').style.display='none';
                                $('expand_r_img_#xxxx#').src='skins/default/images/down.gif';
                            }
                        "></a></td>
                  </tr>
                  <tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.tour.]] / [[.group.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="tour_name" type="text" id="tour_name" value="[[|tour_name|]]" style="width:215px;" readonly="" class="readonly">
                      <input name="tour_id" type="text" id="tour_id" value="[[|tour_id|]]" class="hidden">
                       <!--IF:cond(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"><!--/IF:cond-->
                    </td>
                  </tr><tr valign="top">
                    <td align="right" nowrap>&nbsp;</td>
                    <td align="right">[[.customer.]]</td>
                    <td align="left" style="padding-left:10px;"><input name="customer_name" value="[[|customer_name|]]" type="text" id="customer_name" style="width:215px;" readonly="readonly"  class="readonly">
                      <input name="customer_id" type="text" id="customer_id" value="[[|customer_id|]]" class="hidden">
                       <!--IF:pointer(User::can_edit(false,ANY_CATEGORY))--><a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;"><!--/IF:pointer--></td>
                    </tr>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.Re_code.]]</td>
                        <td align="left" style="padding-left:10px;"><strong>[[|id|]]</strong></td>
                        <td bgcolor="#EFEFEF"></td>
                    </tr>
              </table>
          </fieldset>
 </td></tr></table>
          <fieldset style="background:#F2F4CE;margin-bottom:5px;">
			<legend class="legend-title">[[.reservation_room.]]&nbsp;(<span id="count_number_of_room"></span> [[.room.]])</legend>
			<span id="mi_reservation_room_all_elems">
				<span>
                	<span class="multi-input-header" style="width:50px;">[[.index.]]</span>
					<span class="multi-input-header" style="width:150px;">[[.room_level.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.code.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.room.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.adult.]]</span>
                    <span class="multi-input-header" style="width:32px;">[[.child.]]</span>
                    <span class="multi-input-header" style="width:70px;">[[.price.]]</span>
                    <span class="multi-input-header" style="width:50px;">[[.time_in.]]</span>
					<span class="multi-input-header" style="width:50px;">[[.time_out.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.arrival_date.]]</span>
					<span class="multi-input-header" style="width:70px;">[[.departure_date.]]</span>
					<br clear="all">
				</span>
			</span>
      </fieldset>
 </div></div>
</form>
<?php $room_type = '';?>
<div id="room_list" class="room-list" style="display:none;">
     <div class="title-room-list">
     	<input name="room_select" type="hidden" id="room_select" />
        [[.room_list.]]&nbsp;&nbsp;
        <a onclick="$('room_list').style.display='none';"><img src="skins/default/images/close.JPG" title="[[.close.]]" style="float:right;"></a>
    </div>
	<table cellpadding="3" width="100%" id="table_room_type">
    	<!--LIST:floors-->
    	<tr>
        	<td width="50" style="border-bottom:1px solid white;">[[|floors.name|]]</td>
            <td style="border-bottom:1px solid white;">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                    	<td>
                        <!--LIST:floors.rooms-->
                        	<div class="room-bound" style="width:50px; height:50px; float:left;" onclick="var obj=jQuery('#room_select').val(); jQuery('#room_level_name_'+obj).val('[[|floors.rooms.room_level_name|]]');jQuery('#room_level_id_'+obj).val([[|floors.rooms.room_level_id|]]);jQuery('#room_id_'+obj).val([[|floors.rooms.id|]]);jQuery('#room_name_'+obj).val('[[|floors.rooms.room_name|]]');jQuery('#price_'+obj).val('[[|floors.rooms.price|]]');jQuery('#note_'+obj).html('');$('room_list').style.display='none';">
                              <a href="#" style="width:45px;height:40px; text-decoration:none;" class="room AVAILABLE" lang="[[|floors.rooms.room_level_id|]]" id="[[|floors.rooms.id|]]" >
                        <span style="font-size:9px;font-weight:bold;color:#039" >[[|floors.rooms.room_name|]]<br />[[|floors.rooms.brief_name|]]</span><br />
                   			  </a>
                        </div>
                        <!--/LIST:floors.rooms-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!--/LIST:floors-->
    </table>
</div>
<script>
	<?php if(isset($_REQUEST['mi_reservation_room']))
{
	echo 'var mi_reservation_room_arr = '.String::array2js($_REQUEST['mi_reservation_room']).';';
	echo 'mi_init_rows(\'mi_reservation_room\',mi_reservation_room_arr);';
}
else
{
	echo 'mi_add_new_row(\'mi_reservation_room\',true);';
}
?>
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
for(var i=101;i<=input_count;i++){
		if($('index_'+i)){
			$('index_'+i).innerHTML = i;
		}
		if(jQuery("#departure_time_"+i)){
			//jQuery("#departure_time_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY)});
		}
		if(jQuery("#arrival_time_"+i)){
			//jQuery("#arrival_time_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY)});
		}
		if(jQuery('#note_'+i).val()!=''){
			jQuery('#select_room_level_'+i).css('display','block');
		}
		jQuery('#select_room_level_'+i).css('display','block');
		buildRateList(i);
}
function buildRateList(index){
	if($('status_'+i) && $('customer_name').value){
		jQuery('#price_'+i).attr('readonly',true);
		$('price_'+i).className = 'readonly';
	}
	if(jQuery('#rate_list_'+index) && jQuery('#room_level_id_'+index)){
		jQuery('#rate_list_'+index).click(function(){
			var i = this.id.substr(10);
			var customerId = jQuery('#customer_id').val();
			var roomLevelId = jQuery("#room_level_id_"+i).val();
			var adult = jQuery("#adult_"+i).val();
			var child = jQuery("#child_"+i).val();
			var startDate = jQuery("#arrival_time_"+i).val();
			var endDate = jQuery("#departure_time_"+i).val();
			getRateList(jQuery(this).attr('id'),roomLevelId,i,customerId,adult,child,startDate,endDate);
		});
	}
}
</script>