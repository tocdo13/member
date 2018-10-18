<span style="display:none">
	<span id="mi_room_product_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<span class="multi_edit_input_header">
				<span><input  type="checkbox" id="_checked_#xxxx#"></span>
			</span>
			<span class="multi_edit_input_header">
				<span><input  name="mi_room_product[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="text-align:right;" value="(auto)"></span>
			</span>
			<span class="multi_edit_input">
					<input  name="mi_room_product[#xxxx#][product_id]" style="width:80px;" class="multi_edit_text_input" type="text" id="product_id_#xxxx#" tabindex="2">
			</span><span class="multi_edit_input">
					<input   style="width:200px;" readonly="readonly" class="multi_edit_text_input" type="text" id="product_name_#xxxx#"  tabindex="2">
			</span>
			<span class="multi_edit_input">
					<input  name="mi_room_product[#xxxx#][norm_quantity]" style="width:70px;" class="multi_edit_text_input" type="text" id="norm_quantity_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2">
			</span>
			<span class="multi_edit_input">
					<!--<select   name="mi_room_product[#xxxx#][auto]" style="width:120px;" class="multi_edit_text_input" type="text" id="auto_#xxxx#">
						<option value="0">[[.no.]]</option>
						<option value="1">[[.yes.]]</option>
					</select>-->
					<input type="checkbox" style="width:120px" name="mi_room_product[#xxxx#][auto]" id="auto_#xxxx#"  />
			</span>
			<span class="multi_edit_input"><span style="width:20;">
				<img src="packages/core/skins/default/images/buttons/delete.png" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_room_product','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br>
		</span>
	</span>
</span>

<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?><div class="body">
	<table cellspacing="0" width="100%">
		<tr valign="top">
			<td align="left" colspan="2">
				<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
					<tr>
						<td class="form-title" width="100%">[[.rooms_norm.]]</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!--IF:add(URL::get('cmd')!='add')-->
	<form name="SearchRoomProductForm" method="post" >
	[[.room_id.]]: <select  name="room_id" id="room_id" onchange="this.form.submit();"><option value=""></option>[[|room_id_options|]]</select>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current();?>'" value="[[.return.]]"/>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current(array('room_id','cmd'=>'remove_all'));?>'" value="[[.remove.]] [[|name|]]"/>
	
	</form>
	<!--/IF:add-->
	<form name="EditRoomProductForm" method="post" >
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<!--IF:add(URL::get('cmd')=='add')-->
	[[.room_id.]]: <select name="room_id" id="room_id" ><option value="">[[.add_to_all.]]</option>[[|room_id_options|]]</select>
	<!--/IF:add-->
	<script>
		$('room_id').value="<?php echo URL::get('room_id');?>";
	</script>
	<div style="background-color:#ECE9D8;">
		<span id="mi_room_product_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_room_product',this.checked);"></span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:160px;">[[.id.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:90px;">[[.product_id.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:210px;">[[.product_name.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:80px;">[[.norm_quantity.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:120px;">[[.auto.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:50px;">[[.delete.]]</span></span>
				<span class="multi_edit_input_header"><span style="width:20;"><img src="packages/core/skins/default/images/spacer.gif"/></span></span>
				<br>
			</span>
		</span>
	</div>
	<!--IF:add(URL::get('cmd')=='add')-->
		<input type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_room_product');my_autocomplete();$('norm_quantity_'+input_count).focus();">
	<!--ELSE-->
	[[|paging|]]
	<!--/IF:add-->
	<table>
	<tr><td>
		<!--IF:add(URL::get('cmd')!='add')-->
		<input type="button" value="  [[.add.]]  " onclick="location='<?php echo URL::build_current(array('cmd'=>'add','room_id'));?>';" />
		<!--/IF:add-->
		<input type="submit" value="  [[.apply.]]  " name="confirm_edit" />
		<input type="button" value="  [[.discard.]]  " onclick="location='<?php echo URL::build_current(array('room_id'));?>';"/>
		<input type="button" value="  [[.delete.]]  " onclick="mi_delete_selected_row('mi_room_product');my_autocomplete()" />
	</td></tr>
	</table>
	</form>
</div>
<?php echo RoomProduct::create_js_variables(); ?>

<script>
var data = <?php echo String::array2suggest($GLOBALS['js_variables']['products']);?>;

mi_init_rows('mi_room_product',
	<?php if(isset($_REQUEST['mi_room_product']))
	{
		echo String::array2js($_REQUEST['mi_room_product']);
	}
	else
	{
		echo '[]';
	}
	?>);
	
function my_autocomplete()
{
	jQuery("#product_id_"+input_count).autocomplete(data,{
		minChars: 0,
		width: 310,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}
	}).result(function(){
		recalculate_room_product();
	});
}
function recalculate_room_product()
{
	var columns=all_forms['mi_room_product'];
	for(var i in columns)
	{
		if(getElemValue('product_id_'+columns[i]))
		{
			$('product_name_'+columns[i]).value=
				( (products[(getElemValue('product_id_'+columns[i]))]?products[(getElemValue('product_id_'+columns[i]))].name:''));
		}
	} 
}
</script>