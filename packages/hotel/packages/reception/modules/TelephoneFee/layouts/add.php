<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_telephone_fee_sample">
		<div id="input_group_#xxxx#">
			<input  name="mi_telephone_fee[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi_input">
					<input  name="mi_telephone_fee[#xxxx#][name]" style="width:100px;" type="text" id="name_#xxxx#" >
			</span><span class="multi_input">
					<input  name="mi_telephone_fee[#xxxx#][prefix]" style="width:50px;" type="text" id="prefix_#xxxx#" >
			</span><span class="multi_input">
					<input  name="mi_telephone_fee[#xxxx#][start_fee]" style="width:100px;" type="text" id="start_fee_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44)event.returnValue=false;">
			</span><span class="multi_input">
					<input  name="mi_telephone_fee[#xxxx#][fee]" style="width:60px;" type="text" id="fee_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44)event.returnValue=false;">
			</span>
			<span class="multi_input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_telephone_fee','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br>
		</div>
	</span>
</span>
	<td>
	<?php if(Form::$current->is_error()){?><div><?php echo Form::$current->error_messages();?></div><?php }?>
	<form name="EditTelephoneFeeForm" method="post">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table>
	<tr valign="top">
		<td>
		<fieldset>
			<legend class="title">[[.multiple_item.]]</legend>
				<span id="mi_telephone_fee_all_elems">
					<span>
						<span class="multi-input-header" style="width:100px;">[[.name.]]</span>
						<span class="multi-input-header" style="width:50px;">[[.prefix.]]</span>
						<span class="multi-input-header" style="width:100px;">[[.start_fee.]]</span>
						<span class="multi-input-header" style="width:60px;">[[.fee.]]</span>
						<span class="multi-input-header" style="width:20px;"><img src="skins/default/images/spacer.gif"/></span>
						<br>
					</span>
				</span>
			<input type="button" value="[[.add.]]" onclick="mi_add_new_row('mi_telephone_fee');">
		</fieldset>
		</td>
	</tr>
	</table>
	</td>
	</form>
	</tr>
</table>
</td></tr>
</table>
<script>
mi_init_rows('mi_telephone_fee',<?php if(isset($_REQUEST['mi_telephone_fee'])){echo String::array2js($_REQUEST['mi_telephone_fee']);}else{echo '[]';}?>);
</script>
