<span style="display:none">
	<span id="villa_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="villa[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;"></span>
			<span class="multi-input">
					<input  name="villa[#xxxx#][name]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="name_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="villa[#xxxx#][type]" style="width:100px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="type_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="villa[#xxxx#][floor]" style="width:100px;" class="multi-edit-text-input" type="text" id="floor_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="villa[#xxxx#][position]" style="width:70px;" class="multi-edit-text-input" type="text" id="position_#xxxx#">
			</span>
            <span class="multi-input">
				<input  name="villa[#xxxx#][price]" style="width:100px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="price_#xxxx#">
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'villa','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div><br clear="all" />
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditVillaForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="90%" class="form-title">[[.manage_villa.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('villa');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
	</tr>
</table>
<div class="global-tab">
<div class="header">
<!--LIST:portals-->
	<a <?php echo Url::get('portal_id')==[[=portals.id=]]?'class="selected"':''?> href="<?php echo Url::build_current(array('portal_id'=>[[=portals.id=]]));?>">[[|portals.name|]]</a>
<!--/LIST:portals-->
</div>
<div class="body">
<table cellspacing="0" width="100%">
	<tr valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div>
				<span id="villa_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px;"><input type="checkbox" value="1" onclick="mi_select_all_row('villa',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px;">[[.id.]]</span>
						<span class="multi-input-header" style="width:155px;">[[.villa_name.]]</span>
						<span class="multi-input-header" style="width:100px;">[[.type.]]</span>
						<span class="multi-input-header" style="width:100px;">[[.floor.]]</span>
						<span class="multi-input-header" style="width:70px;">[[.Position.]]</span>
						<span class="multi-input-header" style="width:100px;">[[.price.]]</span>
						<span class="multi-input-header" style="width:70px;">[[.Delete.]]?</span>
					</span>
                    <br clear="all">
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('villa');$('name_'+input_count).focus();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table>
</div></div>
</form>
<script>
<?php 	if(isset($_REQUEST['villa'])){
			echo 'var villas = '.String::array2js($_REQUEST['villa']).';';
			echo 'mi_init_rows(\'villa\',villas);';
		}else{
			echo 'var villas = [];';
		}
?>
function Confirm(index){
	var villa_name = $('name_'+index).value;
	return confirm('[[.Are_you_sure_delete_villa.]] '+villa_name+'?');
}
var DeleteMessage = '[[.Delete_villa_which_is_used_cause_error_for_report_and_statistic.]]';
DeleteMessage += '[[.You_should_delete_when_you_sure_villa_that_you_choose_never_in_used.]]';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
</script>