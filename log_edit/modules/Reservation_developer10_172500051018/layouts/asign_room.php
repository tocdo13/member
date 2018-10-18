<style>
	#table_rr{
		width:100%;
	}
	#table_rr tr td{
		border:1px solid silver;
	}
	.r_bound{
		background:#FFF392;
		border:1px solid #CCCCCC;
	}
	.rr_bound .input_text{
		border:none;
		text-align:center;
	}
	.bound_customer{
		background:#fff;
		color:#4FB6EE;
		border:2px solid #4FB6EE;
	}
	.reservation{
		background:#f1f1f1;
		border:2px solid #ABADB3;
	}
	.reservation div{
		color:#386DB1;
		text-align:left;
		font-weight:bold;
	}
    #room_list {
        width: 720px;
        height: calc( 100% - 70px );
        position: fixed;
        bottom: 35px;
        left: calc( ( 100% - 720px ) / 2  );
        overflow: auto;
    }
</style>
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
                 	<?php if(!strpos([[=re_code=]],',')){?>
                  <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.booking_code.]]</td>
                        <td align="left" style="padding-left:10px;"><input  name="booking_code" id="booking_code" style="width:215px;" type ="text" value="[[|booking_code|]]"></td>
                    <td rowspan="4" width="40%" style="display:none;"><span class="label">[[.note_for_tour_or_group.]]</span>
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
                    <?php }?>
                    <tr valign="top">
                        <td align="right" nowrap>&nbsp;</td>
                        <td align="right">[[.Re_code.]]:</td>
                        <td align="left" style="padding-left:10px;"><strong>[[|re_code|]]</strong></td>
                        <td bgcolor="#EFEFEF"></td>
                    </tr>
              </table>
          </fieldset>
 </td></tr></table>
 			<table cellpadding="3" cellspacing="3" width="100%" id="table_rr">
          <!--LIST:items-->
          		<tr>
                    <td colspan="12" class="bound_customer">
                        <div class="reservation" style="height:50px;">
                            <div style="margin:5px 20px;">[[.re_code.]]: <span>[[|items.id|]]</span></div>
                            <div style="margin:5px 20px;">[[.customer.]]: [[|items.customer_name|]]</div>
                        </div>
                    </td>
                </tr>
                <tr class="r_bound">
                	<td><span style="width:30px;">[[.rr_id.]]</span></td>
                    <td><span style="width:150px;">[[.room_level_name.]]</span></td>
                    <td><span style="width:50px;">[[.room_name.]]</span></td>
                    <td><span style="width:25px;">[[.adult.]]</span></td>
                    <td><span style="width:25px;">[[.child.]]</span></td>
                    <td><span style="width:50px;">[[.price.]]</span></td>
                    <td><span style="width:25px;">[[.time_in.]]</span></td>
                    <td><span style="width:70px;">[[.arrival_time.]]</span></td>
                    <td><span style="width:25px;">[[.time_out.]]</span></td>
                    <td><span style="width:70px;">[[.departure_time.]]</span></td>
                   	<td id="note">&nbsp;</td>
                 </tr>
          	<!--LIST:items.reservation_rooms-->
            	<tr class="rr_bound">
                	<td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][id]" type="text" class="input_text" id="id_[[|items.reservation_rooms.id|]]" value="[[|items.reservation_rooms.id|]]" style="width:30px;" readonly="" /></td>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_level_name]" type="text" id="room_level_name_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_level_name|]]" readonly="" style="width:150px;"/></td>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_level_name_old]" type="text" id="room_level_name_old_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_level_name|]]"  style="display:none;"/>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_level_id]" type="text" id="room_level_id_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_level_id|]]"  style="display:none;"/>
                     <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_level_id_old]" type="text" id="room_level_id_old_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_level_id|]]"  style="display:none;"/>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][reservation_id]" type="text" id="reservation_id_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.reservation_id|]]"  style="display:none;"/>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_id]" type="text" id="room_id_[[|items.reservation_rooms.id|]]" class="room_id" value="[[|items.reservation_rooms.room_id|]]" lang="[[|items.reservation_rooms.id|]]"  style="display:none;"/>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_id_old]" type="text" id="room_id_old_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_id|]]" lang="[[|items.reservation_rooms.id|]]"  style="display:none;"/>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_name]" type="text" id="room_name_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_name|]]" readonly="" style="width:60px;"/></td>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][room_name_old]" type="text" id="room_name_old_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.room_name|]]" style="display:none;"/>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][adult]" type="text" id="adult_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.adult|]]" style="width:35px;" readonly="" /></td>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][child]" type="text" id="child_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.child|]]"  style="width:35px;" readonly="" /></td>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][price]" type="text" id="price_[[|items.reservation_rooms.id|]]" class="input_number" value="[[|items.reservation_rooms.price|]]"  style="width:70px; border:none" readonly="" /></td>
                    <input name="reservation_rooms[[[|items.reservation_rooms.id|]]][price_old]" type="text" id="price_old_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.price|]]"  style="width:70px; display:none;" readonly="" />
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][time_in]" type="text" id="time_in_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.time_in|]]"  style="width:50px;" readonly="" /></td>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][arrival_time]" type="text" id="arrival_time_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.arrival_time|]]"  style="width:100px;" readonly="" /></td>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][time_out]" type="text" id="time_out_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.time_out|]]"  style="width:50px;" readonly="" />  </td>
                    <td><input name="reservation_rooms[[[|items.reservation_rooms.id|]]][departure_time]" type="text" id="departure_time_[[|items.reservation_rooms.id|]]" class="input_text" value="[[|items.reservation_rooms.departure_time|]]"  style="width:100px;" readonly="" /></td>
                   <td><span id="note_[[|items.reservation_rooms.id|]]">[[|items.reservation_rooms.note|]]</span>
                   <img src="skins/default/images/cmd_Tim.gif" id="select_room_level_#xxxx#" onclick="jQuery('#room_list').css('display','');jQuery('#room_select').val([[|items.reservation_rooms.id|]]);" style="cursor:pointer; float:right;" title="[[.select_room.]]" />
                   </td>
                </tr>
          	<!--/LIST:items.reservation_rooms-->
          <!--/LIST:items-->
          </table>
 </div></div>
 <div style="height:50px;"></div>
</form>
<?php $room_type = '';?>
<div id="room_list" class="room-list" style="display: none;">
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
                        	<div class="room-bound" style="width:50px; height:50px; float:left;" onclick="var obj=jQuery('#room_select').val(); jQuery('#room_level_name_'+obj).val('[[|floors.rooms.room_level_name|]]');jQuery('#room_level_id_'+obj).val([[|floors.rooms.room_level_id|]]);jQuery('#room_id_'+obj).val([[|floors.rooms.id|]]);jQuery('#room_name_'+obj).val('[[|floors.rooms.room_name|]]');$('room_list').style.display='none';checkRoom([[|floors.rooms.id|]],obj);">
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
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
items = [[|items_js|]];
for(var i in items){
	for(var j in items[i]['reservation_rooms']){
		itm = items[i]['reservation_rooms'];
		if(jQuery('#note_'+j).val()!=''){
			jQuery('#select_room_level_'+j).css('display','block');
		}
		jQuery('#select_room_level_'+j).css('display','block');
		buildRateList(j);
	}
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
function checkRoom(room_id,key){
	jQuery('.room_id').each(function(){
		if(this.lang!=key && this.value==room_id){
			alert('[[.room_id_duplicated.]]');
			jQuery('#room_id_'+key).val(jQuery('#room_id_old_'+key).val());
			jQuery('#room_name_'+key).val(jQuery('#room_name_old_'+key).val());
			jQuery('#room_level_id_'+key).val(jQuery('#room_level_id_old_'+key).val());
			jQuery('#room_level_name_'+key).val(jQuery('#room_level_name_old_'+key).val());
			jQuery('#price_'+key).val(jQuery('#price_old_'+key).val());
		}
	});
}
</script>