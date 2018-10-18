<style>
	.item-body{
		width:400px;	
	}
	input[type=text]{
		width:100px;
		height:25px;
		font-size:11px;
	}
	input[type=checkbox]{
		display:none;
	}
	#travellers_id,#order_ids{
		width:120px;
		height:30px;	
	}
	#save,#add_payment,#back{
		height:28px !important;
		width:65px !important;	
	}
	#title{
		background:url(packages/hotel/skins/default/images/iosstyle/item_list_bg.png) repeat-x;	
		height:30px;
	}
	.invoice tr{
		height:30px;	
	}
	#button_add{
		width:50px;
		height:30px;	
	}
	}	
</style>
<script type="text/javascript" src="packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js"></script>
<div id="post_show">
<form method="post" name="ChangeTravellerReservationForm">
<div style="text-align:center; color:#36F; font-size:16px; font-weight:bold; margin-top:10px;">[[.change_guest.]]</div>
<div></div>
<div><?php echo Form::$current->error_messages();?></div>
<table width="87%" border="1" style="margin:20px auto; border:1px solid silver;" class="invoice">
	<tr id="title">
        <td width="50%">[[.code.]]:<span id="rr_id" style="text-align:left; color:#0A3680;"> <?php echo Url::get('rr_id');?></span>_[[.room.]]_[[|room_name|]]
        <span id="reservation_id" style="text-align:left; color:#0A3680;"><?php echo Url::get('r_id');?></span></td>
        <td width="50%"><span style="text-align:left; color:#0A3680;"></span>
        </td>
    </tr>
    
    <tr>
        <td>[[.change_guest.]]
       </td>   
        <td><input name="save" type="button" id="save" value="Change" style="float:right;height:27px !important;" onclick="SubmitForm();" class="button-medium-save"/>
       </td>
    </tr>
    <tr style="height:80px !important;">
        <td style="padding-top:10px;padding-bottom:10px;">
        <table>
        	<tr>
            	<td>
                 <span id="select_title">&nbsp;&nbsp;<strong>[[.guest.]]:</strong> </span>
                </td>
                <td>
               <input name="guest_name" type="text" id="guest_name" value="[[|full_name|]]" style="width:150px;border:none; font-weight:bold;" /><input name="traveller_id" value="<?php echo Url::get('traveller_id'); ?>" type="hidden" id="traveller_id"/>
                </td>
            </tr>
            <tr>
            	<td><span id="select_title">&nbsp;&nbsp;[[.room_name.]]: </span></td>
                <td>[[|room_name|]] - HĐ: [[|id|]]</td>
            </tr>
             <tr>
            	<td><span id="select_title">&nbsp;&nbsp;[[.arrival_time.]]: </span></td>
                <td><?php echo date('d/m/Y',[[=arrival_time=]]);?></td>
            </tr>
            <tr>
            	<td><span id="select_title">&nbsp;&nbsp;[[.departure_time.]]: </span></td>
                <td><?php echo date('d/m/Y',[[=departure_time=]]);?></td>
            </tr>
        </table>
       </td>
       <td>
       		<table>
            	<tr>
                	<td>[[.select_portal.]]:</td>
                    <td><select name="portal_id" id="portal_id" style="width:200px;height:27px;" onchange="getRoom(this.value);"></select></td>
                </tr>
                <tr>
                	<td>[[.select_room.]]:</td>
                    <td><select name="room_id" id="room_id" onchange="get_reservation(this.value);" style="width:200px;height:27px;"></select></td>
                </tr>
                <tr>
                	<td>[[.note.]]:</td>
                    <td><input name="note" type="text" id="note" style="width:200px; display:block;" maxlength="300" /></td>
                </tr>
            </table>
            <input name="reservation_room_id" type="hidden" id="reservation_room_id" />
       </td>
    </tr>
</table>
<input name="action" type="hidden" id="action" />
<input name="act" type="hidden" id="act" />
</form>
</div>
<script>
	var traveller_id = '<?php echo Url::get('traveller_id')?Url::get('traveller_id'):'';?>';
	var cmd = '<?php echo Url::get('cmd')?Url::get('cmd'):'';?>';
	room_list = [[|rooms_list_js|]];
	var portal_id = '<?php echo PORTAL_ID;?>';
	getRoom(portal_id);
	function SubmitForm(act){	
		if(jQuery('#traveller_id').val()!='' && jQuery('#reservation_room_id').val()==0){	
			var conf = confirm('Bạn muốn check in phòng mới cho khách này?');
			if(conf){
				jQuery('#action').val(1);
				ChangeTravellerReservationForm.submit();	
			}
		}
		if(jQuery('#traveller_id').val()!='' && jQuery('#reservation_room_id').val()!=0){
			jQuery('#action').val(1);
			ChangeTravellerReservationForm.submit();	
		}
	}
	function getRoom(portal_id){
		jQuery('#room_id').html('<option value="0">----Select room----</option>');
		for(var i in room_list){
			if(room_list[i]['portal_id']==portal_id){
				jQuery('#room_id').append('<option value="'+room_list[i]['id']+'">'+room_list[i]['name']+'</option>');	
			}
		}
	}
	function get_reservation(room_id){
		for(var i in room_list){
			if(room_list[i]['id']==room_id){
				jQuery('#reservation_room_id').val(room_list[i]['reservation_room_id']);
			}
		}	
	}
</script>