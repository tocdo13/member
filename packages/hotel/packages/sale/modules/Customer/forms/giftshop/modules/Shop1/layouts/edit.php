<script src="packages/core/includes/js/multi_items.js"></script>
<?php 
	System::set_page_title(HOTEL_NAME.' - '.Portal::language('edit_shop'));
	$can_delete = ListShopForm::check_delete(Url::get('selected_ids'));
?>
<span style="display:none">
	<span id="mi_shop_sample">
		<span id="input_group_#xxxx#">
			<input  name="mi_shop[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi_input">
					<input  name="mi_shop[#xxxx#][code]" style="width:80px;" type="text" id="code_#xxxx#" >
			</span><span class="multi_input">
					<input  name="mi_shop[#xxxx#][name]" style="width:150px;" type="text" id="name_#xxxx#" >
			</span>
            <?php if($can_delete){?>
			<span class="multi_input"><span style="width:20;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_shop','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span>
            <?php }?>
            <br>
		</span>
	</span>
</span>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.edit_shop.]]</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EFEFEF"><div style="width:10px;line-height:24px">&nbsp;</div></td>
	<td bgcolor="#EFEFEF">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="3">
	<table width="100%">
<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EFEFEF" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EFEFEF"><div style="width:10px;line-height:24px;">&nbsp;</div></td>
	<td bgcolor="#EFEFEF"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?>
	<form name="EditShopForm" method="post" >
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="edit_selected" type="hidden" value="1">
	<tr bgcolor="#EFEFEF" valign="top">
		<td bgcolor="#EFEFEF"  colspan="3">
		<fieldset>
			<legend>[[.multiple_item.]]</legend>
				<span id="mi_shop_all_elems">
					<span>
						<span class="multi-input-header"><span style="width:80px;">[[.code.]]</span></span><span class="multi-input-header"><span style="width:150;">[[.name.]]</span></span>
						<span class="multi-input-header"><span style="width:20px;"><img src="skins/default/images/spacer.gif"/></span></span>
						<br>
					</span>
				</span>
		</fieldset>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF">
		<td bgcolor="#EFEFEF" colspan="3">
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('save'),false,false,true,'EditShopForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current());?>
			</td></tr>
			</table>
		</td>
	</tr>
	</table>
	</form>
	</td>
</tr>
</table>
<script type="text/javascript">
mi_init_rows('mi_shop',
	<?php if(isset($_REQUEST['mi_shop']))
	{
		echo String::array2js($_REQUEST['mi_shop']);
	}
	else
	{
		echo '[]';
	}
	?>);
</script>
