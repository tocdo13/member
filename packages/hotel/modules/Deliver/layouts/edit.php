<script src="packages/core/includes/js/multi_items.js"></script>
<span style="display:none">
	<span id="mi_unit_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<span class="multi_edit_input">
				<span><input  type="checkbox" id="_checked_#xxxx#"></span>
			</span>
			<span class="multi_edit_input">
				<span style="width:40px;"><input  name="mi_unit[#xxxx#][id]" type="text" id="id_#xxxx#" readonly="readonly" class="multi_edit_text_input" style="width:40px;text-align:right; background:#CCC"" value="(auto)"></span>
			</span>
  	       <span class="multi_edit_input">
				<span style="width:40px;"><input  name="mi_unit[#xxxx#][code]" type="text" id="code_#xxxx#" class="multi_edit_text_input" style="width:175px;text-align:left;"></span>
			</span>
             <span class="multi_edit_input">
				<span style="width:40px;"><input  name="mi_unit[#xxxx#][name]" type="text" id="name_#xxxx#" class="multi_edit_text_input" style="width:175px;text-align:left;"></span>
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
<form name="EditDeliverForm" method="post" >
<div align="center">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="margin-top:3px;text-align:left;">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">Bộ phận</td>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="EditDeliverForm.submit();"  class="button-medium-save">[[.save.]]</a></td><?php }?>
                </tr>
            </table>
		</td>
	</tr>
	<tr>
	<td>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table width="100%">
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
						<span class="multi_edit_input_header"><span style="width:45px;">id</span></span>
						<span class="multi_edit_input_header"><span style="width:175px;">Mã</span></span>
						<span class="multi_edit_input_header"><span style="width:175px;">Tên bộ phận</span></span>
						
						<span class="multi_edit_input_header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
						<br>
					</span>
				</span>
		</div>
			<input type="button" value="   [[.add_item.]]   " onclick="mi_add_new_row('mi_unit');">
		[[|paging|]]
		<input type="button" value="  [[.delete.]]  " onclick="mi_delete_selected_row('mi_unit');" />
        </td>
	</tr>
	<tr bgcolor="#EFEFEF">
		<td bgcolor="#EFEFEF">
			<p>
			<table>
			<tr><td>
			<!--	<input type="button" value="  [[.discard.]]  " onclick="location='<?php //echo URL::build_current(array());?>';"/>-->
				
			</td></tr>
			</table>
			</p>
		</td>
	</tr>
	</table>
    <input name="confirm_edit" type="hidden" value="1" />
	</td>
</tr>
</table>
</div>
</form>
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
