<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_unit_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<span class="multi_edit_input">
				<span><input  type="checkbox" id="_checked_#xxxx#"></span>
			</span>
			<span class="multi_edit_input">
				<span style="width:40px;"><input  name="mi_unit[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="width:40px;text-align:right;" value="(auto)"></span>
			</span>
			<!--LIST:languages-->
			<span class="multi_edit_input">
				<input  name="mi_unit[#xxxx#][name_[[|languages.id|]]]" style="width:150px;" class="multi_edit_text_input" type="text" id="name_[[|languages.id|]]_#xxxx#"  tabindex="2">
			</span>
			<!--/LIST:languages-->
			<span class="multi_edit_input">
				<input  name="mi_unit[#xxxx#][normal_unit]" style="width:200px;" class="multi_edit_text_input" type="text" id="normal_unit_#xxxx#"  tabindex="2">
			</span>
			<span class="multi_edit_input"><span style="width:20;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_unit','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br>
		</span>
	</span>
</span>
<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('product_unit'):Portal::language('product_unit');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_unit'));?>
<div align="center">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="margin-top:3px;text-align:left;">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%"><?php echo $title;?></td>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="EditUnitForm.submit();"  class="button-medium-save">[[.save.]]</a></td><?php }?>
					<td><input type="button" value="  [[.discard.]]  " onclick="location='<?php echo URL::build_current(array());?>';"/></td>
                    <td><input type="button" value="  [[.delete.]]  " onclick="mi_delete_selected_row('mi_unit');" /></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
	<form name="EditUnitForm" method="post" >
	<td>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table width="100%" cellspacing="3">
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EFEFEF" valign="top">
	<td bgcolor="#EFEFEF"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><tr bgcolor="#EFEFEF" valign="top">
		<td>
		<div style="background-color:#EFEFEF;">
						<span id="mi_unit_all_elems">
					<span style="white-space:nowrap;">
						<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_unit',this.checked);"></span></span>
						<span class="multi_edit_input_header"><span style="width:45px;">[[.id.]]</span></span>
						<!--LIST:languages-->
						<span class="multi_edit_input_header"><span style="width:155px;"><a href="<?php echo URL::build_current(array('order_by'=>'name_'.[[=languages.id=]]));?>">[[.name.]]([[|languages.code|]])</a></span></span>
						<!--/LIST:languages-->
						<span class="multi_edit_input_header"><span style="width:205px;">[[.normal_unit.]]</span></span>
						<span class="multi_edit_input_header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
						<br>
					</span>
				</span>
		</div>
			<input type="button" value="   [[.add_item.]]   " onclick="mi_add_new_row('mi_unit');">
		[[|paging|]]
		</td>
	</tr>
	</table>
    <input name="confirm_edit" type="hidden" value="1" />
	</form>
	</td>
</tr>
</table>
<script>
mi_init_rows('mi_unit',
	<?php if(isset($_REQUEST['mi_unit']))
	{
		echo String::array2js($_REQUEST['mi_unit']);
	}
	else
	{
		echo '[]';
	}
	?>);
</script>
