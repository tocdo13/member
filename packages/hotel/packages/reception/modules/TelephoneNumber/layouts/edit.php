<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_telephone_number_sample">
		<div id="input_group_#xxxx#">
			<input  name="mi_telephone_number[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input">
					<input  name="mi_telephone_number[#xxxx#][phone_number]" style="width:170px;" type="text" id="phone_number_#xxxx#" >
			</span><span class="multi-input">
				<select  name="mi_telephone_number[#xxxx#][room_id]" style="width:75px;"  id="room_id_#xxxx#"><option value=""></option>
					[[|room_id_options|]]
				</select>
			</span>
			<span class="multi-input">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_telephone_number','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span>
		</div><br clear="all">
	</span>
</span>
	<td>
	<form name="EditTelephoneNumberForm" method="post">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="edit_selected" type="hidden" value="1">
	<div><?php if(Form::$current->is_error()){?><?php echo Form::$current->error_messages();?><?php }?></div>
	<table width="100%">
	<tr valign="top">
		<td>&nbsp;</td>
		<td ><div style="line-height:24px;">&nbsp;</div></td>
		<td bgcolor="#EFEFEE">
		<fieldset>
					<legend>[[.multiple_item.]]</legend>
						<span id="mi_telephone_number_all_elems">
					<span>
						<span class="multi-input-header" style="width:170px;">[[.phone_number.]]</span>
						<span class="multi-input-header" style="width:70px;">[[.room_id.]]</span>
						<span class="multi-input-header" style="width:20;"><img src="skins/default/images/spacer.gif"/></span>
					</span><br clear="all">
				</span><br clear="all">
		<input type="button" value="[[.add.]] " onClick="mi_add_new_row('mi_telephone_number');">	
		</fieldset>
		</td>
	</tr>
	</table>
	</form>
	</td>
</tr>
</table>
</td></tr>
</table>
<script>
mi_init_rows('mi_telephone_number',
	<?php if(isset($_REQUEST['mi_telephone_number']))
	{
		echo String::array2js($_REQUEST['mi_telephone_number']);
	}
	else
	{
		echo '[]';
	}
	?>);
</script>
