<span style="display:none">
	<span id="mi_room_good_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<span class="multi_edit_input_header">
				<span><input  type="checkbox" id="_checked_#xxxx#"></span>
			</span>
			<span class="multi_edit_input_header">
				<input name="mi_room_good[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="text-align:right;width:154px" value="(auto)">
			</span>
			<span class="multi_edit_input">
					<input name="mi_room_good[#xxxx#][product_id]" style="width:80px;" class="multi_edit_text_input" type="text" id="product_id_#xxxx#" tabindex="2">
			</span><span class="multi_edit_input">
					<input   style="width:200px;" class="multi_edit_text_input" type="text" id="product_name_#xxxx#"  tabindex="2">
			</span>
			<span class="multi_edit_input">
					<input  name="mi_room_good[#xxxx#][price]" style="width:80px;" class="multi_edit_text_input" type="text" id="price_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2">
			</span>

			<!--IF:add(URL::get('cmd')!='add')-->
			<span class="multi_edit_input">
					<input  style="width:50px;" class="multi_edit_text_input" type="text" id="quantity_#xxxx#" readonly="readonly">
			</span>
			<!--/IF:add-->			
			<span class="multi_edit_input">
					<input  name="mi_room_good[#xxxx#][norm_quantity]" style="width:70px;" class="multi_edit_text_input" type="text" id="norm_quantity_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2">
			</span>
			<span class="multi_edit_input">
					<input   name="mi_room_good[#xxxx#][position]" style="width:50px;" class="multi_edit_text_input" type="text" id="position_#xxxx#"  tabindex="2">
			</span>
			<span class="multi_edit_input"><span style="width:20;">
				<img src="packages/core/skins/default/images/buttons/delete.png" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_room_good','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br>
		</span>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?><div class="body">
<form name="EditRoomGoodsForm" method="post" >
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>        	
			<td width="55%" class="form-title">[[.room_norm.]]</td>			
			<!--IF:add1(URL::get('cmd')=='add')-->
			<td width="1%">
			<a class="button-medium-add" href="javascript:void(0)" onclick="mi_add_new_row('mi_room_good');my_autocomplete();$('norm_quantity_'+input_count).focus();">[[.add.]]</a>
	    	</td>
			<!--/IF:add1-->
			<!--IF:add(URL::get('cmd')!='add')-->
			<td width="1%">
			<a class="button-medium-add" href="<?php echo URL::build_current(array('cmd'=>'add','room_id'));?>" >[[.add.]]</a>
			</td>
			<!--/IF:add-->
			<td width="1%"><input type="submit" value="[[.apply.]]" class="button-medium-save" name="confirm_edit" /></td>
			<td width="1%"><a href="javascript:void(0)" onclick="mi_delete_selected_row('mi_room_good');" class="button-medium-delete" >[[.delete.]]</a></td>
		</tr>
    </table>
	<!--IF:add(URL::get('cmd')!='add')-->
	&nbsp;&nbsp;&nbsp;[[.select_room_id.]]: <select  name="room_id" id="room_id" onchange="this.form.submit();"><option value=""></option>[[|room_id_options|]]</select>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current();?>'" value="[[.return.]]"/>
	&nbsp;&nbsp;&nbsp;<input type="button" onclick="location='<?php echo URL::build_current(array('room_id','cmd'=>'remove_all'));?>'" value="[[.remove.]] [[|name|]]"/>
	<!--/IF:add-->
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<!--IF:add(URL::get('cmd')=='add')-->
	[[.select_room_id.]]: <select name="room_id" id="room_id" ><option value="">[[.add_to_all.]]</option>[[|room_id_id_options|]]</select>
	<!--/IF:add-->
	<input type="button" value="[[.discard.]]" onclick="location='<?php echo URL::build_current(array('room_id'));?>';"/>
	<script>
		$('room_id').value="<?php echo URL::get('room_id');?>";
	</script>
	<div style="background-color:#ECE9D8;">
		<span id="mi_room_good_all_elems">
			<span style="white-space:nowrap;">
				<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_room_good',this.checked);"></span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:160px;">[[.id.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:90px;">[[.product_id.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:220px;">[[.product_name.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:70px;">[[.price.]]</span></span>
				<!--IF:add(URL::get('cmd')!='add')-->
				<span class="multi_edit_input_header"><span class="table-title" style="width:68px;">[[.quantity.]]</span></span>
				<!--/IF:add-->
				<span class="multi_edit_input_header"><span class="table-title" style="width:70px;">[[.norm_quantity.]]</span></span>
				<span class="multi_edit_input_header"><span class="table-title" style="width:50px;">[[.position.]]</span></span>
				<span class="multi_edit_input_header"><span style="width:20px;"><img src="packages/core/skins/default/images/spacer.gif"/></span></span>
				<br>
			</span>
		</span>
	</div>
	[[|paging|]]
	</form>
</div>
<?php echo RoomGoods::create_js_variables(); ?>
<script>
var data = <?php echo String::array2suggest($GLOBALS['js_variables']['products']);?>;
function recalculate_room_good()
{
	var columns=all_forms['mi_room_good'];
	for(var i in columns)
	{
		if(getElemValue('product_id_'+columns[i]))
		{
			$('product_name_'+columns[i]).value=
				( (products[(getElemValue('product_id_'+columns[i]))]?products[(getElemValue('product_id_'+columns[i]))].name:''));
			$('price_'+columns[i]).value=
				( (products[(getElemValue('product_id_'+columns[i]))]?products[(getElemValue('product_id_'+columns[i]))].price:''));
		}
	} 
}
function my_autocomplete()
{
	jQuery("#product_id_"+input_count).autocomplete(data,{
		minChars: 0,
		width: 280,
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
		recalculate_room_good();
	});
}
mi_init_rows('mi_room_good',
	<?php if(isset($_REQUEST['mi_room_good']))
	{		
		echo String::array2js($_REQUEST['mi_room_good']);
	}
	else
	{
		echo '[]';
	}
	?>);
recalculate_room_good();
</script>