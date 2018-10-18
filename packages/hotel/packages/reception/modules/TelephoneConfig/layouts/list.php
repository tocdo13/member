<table cellspacing="0">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td width="90%" class="form-title">
						[[.telephone_config.]]
					</td>
				</tr>
			</table>
		</td>
	</tr>
    <tr>
    	<td>
        	<form name="TelephoneConfig" method="post">
        	<div>
            	<fieldset>
                <legend class="title">[[.ajust_guest_name.]]</legend>
            		<div class="form-label">
                		<label for="wu_telephone_number">[[.room_name.]]: </label><select name="an_reservation_room_id" id="an_reservation_room_id"></select>
 						<input name="ajust" type="submit" value="[[.ajust_guest_name.]]" />
                    </div>
                </fieldset>
            	<fieldset>
                <legend class="title">[[.ajust_all_guest_name.]]</legend>
                    <div><input name="ajust_all" type="submit" value="[[.ajust_all_guest_name.]]" /></div>
                </fieldset>
            	<fieldset>
                    <legend class="title">[[.wake_up.]]</legend>
            		<div class="form-label">
                		<label for="wu_telephone_number">[[.room_name.]]: </label><select name="wu_telephone_number" id="wu_telephone_number"></select>
                        <label for="wu_time">[[.wake_up_time.]]: </label><input name="wu_time" type="text" id="wu_time" style="width:80px;text-align:right;" />
                   		<input name="wake_up" type="submit" value="[[.set_wake_up.]]" />	
                    </div>
                </fieldset>
            	<fieldset>
	                <legend class="title">[[.wake_up_group.]]</legend>
            		<div class="form-label">
                		<label for="wu_telephone_number">[[.group_name.]]: </label><select name="wu_group_id" id="wu_group_id"></select>                    
                        <label for="wu_time">[[.wake_up_group_time.]]: </label><input name="wu_group_time" type="text" id="wu_group_time" style="width:80px;text-align:right;" />
                   		<input name="wake_up_group" type="submit" value="[[.set_wake_up_group.]]" />	
                    </div>
                </fieldset>
                <fieldset>
                <legend class="title">[[.open_telephone_for_room.]]</legend>
            		<div class="form-label">
                		<label for="open_room_id">[[.room_name.]]: </label><select name="open_room_id" id="open_room_id"></select>
 						<input name="open_telephone" type="submit" value="[[.open_telephone.]]" />
                    </div>
                </fieldset>
                <fieldset>
                <legend class="title">[[.close_telephone_for_room.]]</legend>
            		<div class="form-label">
                		<label for="close_room_id">[[.room_name.]]: </label><select name="close_room_id" id="close_room_id"></select>
 						<input name="close_telephone" type="submit" value="[[.close_telephone.]]" />
                    </div>
                </fieldset>                
            </div>
            </form>
        </td>
    </tr>
</table>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#wu_time").mask("99:99");
	jQuery("#wu_group_time").mask("99:99");
})
</script>