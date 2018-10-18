<script>
	HousekeepingEquipment_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form method="post" name="SearchHousekeepingEquipmentDamagedForm">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.list_damaged.]]</td>
	</tr>
    <tr>
        <td align="right" style="padding-right: 30px;">
            <input type="button" onclick="window.location='<?php echo Url::build_current(array('room_id')); ?>'" value="[[.list_equipment.]]" style="text-transform: uppercase; margin-right: 5px;" class="w3-btn w3-green w3-text-white" />
            <input type="submit" onclick="if(!confirm('[[.are_you_sure.]]')){ return false};" value="[[.delete.]]" class="w3-btn w3-red" style="text-transform: uppercase;" />
        </td>
    </tr>
	<tr>
		<td>
            <fieldset>
                <legend class="" style="text-transform: uppercase;">[[.search.]]</legend>
        		<table>
    				<tr>    
    					<td align="right" >[[.room_id.]]</td>
    					<td>:</td>
    					<td ><select name="room_id" id="room_id" style="width:100px; height: 24px; margin-right: 20px;"></select></td>
    					<td align="right" >[[.product_code.]]</td>
    					<td>:</td>
    					<td ><input name="product_id" type="text" id="product_id" size="30" style=" height: 24px; margin-right: 20px;"/></td>
                        <td>
                            <input class="w3-btn w3-gray" type="submit" value="[[.search.]]" style=" height: 24px; padding-top: 4px; margin-right: 10px;"/>
    						<input class="w3-btn w3-gray" type="button" onclick="window.location='<?php echo Url::build_current(array('cmd')); ?>'" value="[[.list_all.]]" style=" height: 24px; padding-top: 4px; text-transform: uppercase;" />
    					</td>
    				</tr>
                </table>
            </fieldset>
            <br />
            <br />
            
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
				<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#C6E2FF" style="border-collapse:collapse">
					<tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
						<th width="1%" title="[[.check_all.]]">
                            <input type="checkbox" value="1" id="HousekeepingEquipment_check_0" onclick="check_all('HousekeepingEquipment','HousekeepingEquipment_array_items','#FFFFEC',this.checked);"/>
                        </th>
						<th width="100px" align="center">[[.date.]]</th>
						<th width="350px" align="center">[[.product_name.]]</th>
						<th width="100px" align="center">[[.product_code.]]</th>
						<th width="100px" align="center">[[.quantity.]]</th>
						<th width="100px" align="center">[[.damaged_type.]]</th>
                        <th width="300px" align="center">[[.note.]]</th>
						<th width="100px" align="center">[[.room_id.]]</th>
						<th>&nbsp;</th>
					</tr>
					<!--LIST:items-->
					<!--<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;" id="HousekeepingEquipment_tr_[[|items.id|]]">-->
                    <tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;" id="HousekeepingEquipment_tr_[[|items.id|]]">
						<td>
                            <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('HousekeepingEquipment','[[|items.i|]]','HousekeepingEquipment_array_items','#FFFFEC');" id="HousekeepingEquipment_check_[[|items.i|]]"/>
                        </td>
						<td align="center">[[|items.date|]]</td>
						<td align="left">[[|items.product_name|]]</td>
						<td align="left">[[|items.product_id|]]</td>
						<td align="center">[[|items.quantity|]]</td>
						<td align="center">[[|items.damaged_type|]]</td>
						<td align="left">[[|items.note|]]</td>
                        <td align="center">[[|items.room_name|]]</td>
						<td width="15px">
							<a  onclick="if(confirm('Ban co chac muon xoa hu hong cua TTB [[|items.product_name|]] ?')) {location='<?php echo URL::build_current(array('cmd'=>'delete_damaged'));?>&id=[[|items.id|]]';}">
                                <i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i>							
                            </a>						
                        </td>
					</tr>
					<!--/LIST:items-->
				</table>
				</td>
			</tr>
			</table>
			[[|paging|]]

		</td>
	</tr>
</table>
</form>