<table cellspacing="0">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td width="90%" class="form-title">
						[[.salto_config.]]
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
                <legend class="title">[[.checkin_card.]]</legend>
            		<div class="form-label">
                		<label for="wu_telephone_number">[[.room_name.]]: </label><select name="an_reservation_room_id" id="an_reservation_room_id"></select>
 						<input name="checkin" type="submit" value="[[.checkin_card.]]" />
                    </div>
                </fieldset>
                <legend class="title">[[.copy_card.]]</legend>
            		<div class="form-label">
                		<label for="wu_telephone_number">[[.room_name.]]: </label><select name="an_reservation_room_id" id="an_reservation_room_id"></select>
 						<input name="copy" type="submit" value="[[.copy_card.]]" />
                    </div>
                </fieldset>
            	<fieldset>
                <legend class="title">[[.checkout_card.]]</legend>
                    <div><input name="checkout" type="submit" value="[[.checkout_card.]]" /></div>
                </fieldset>
                <fieldset>
                <legend class="title">[[.delete_card.]]</legend>
            		<div class="form-label">
                		<label for="open_room_id">[[.room_name.]]: </label><select name="open_room_id" id="open_room_id"></select>
 						<input name="delete_card" type="submit" value="[[.delete_card.]]" />
                    </div>
                </fieldset>
                <fieldset>
                <legend class="title">[[.edit_card.]]</legend>
            		<div class="form-label">
                		<label for="close_room_id">[[.room_name.]]: </label><select name="close_room_id" id="close_room_id"></select>
 						<input name="edit_card" type="submit" value="[[.edit_card.]]" />
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