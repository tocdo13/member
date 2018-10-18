<span style="display:none">
    <span id="member_level_policies_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="member_level_policies[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;"/></span>
            <span class="multi-input">
					<select name="member_level_policies[#xxxx#][location_code]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" id="location_code_#xxxx#">[[|location_code_options|]]</select>
			</span>
            <span class="multi-input">
					<select name="member_level_policies[#xxxx#][is_parent]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" id="is_parent_#xxxx#" >[[|is_parent_options|]]</select>
			</span>
            <span class="multi-input">
					<input name="member_level_policies[#xxxx#][num_people]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="num_people_#xxxx#" />
			</span>
            <span class="multi-input">
					<input name="member_level_policies[#xxxx#][start_date]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="start_date_#xxxx#" />
			</span>
            <span class="multi-input">
					<input name="member_level_policies[#xxxx#][end_date]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="end_date_#xxxx#" />
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'member_level_policies','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div><br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="Editmember_level_policiesForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="90%" class="form-title">[[.member_level_policies_project.]]</td>
		<td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td>
		<td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('member_level_policies');" class="button-medium-delete">[[.Delete.]]</a></td>
	</tr>
</table>
<div class="global-tab">
<div class="header">
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
				<span id="member_level_policies_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px;"><input type="checkbox" value="1" onclick="mi_select_all_row('member_level_policies',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px;">[[.ID.]]</span>
						<span class="multi-input-header" style="width:155px;">[[.location_code.]]</span>
                        <span class="multi-input-header" style="width:155px;">[[.is_parent.]]</span>
						<span class="multi-input-header" style="width:155px;">[[.num_people.]]</span>
                        <span class="multi-input-header" style="width:155px;">[[.start_date.]]</span>
                        <span class="multi-input-header" style="width:155px;">[[.end_date.]]</span>
                        <span class="multi-input-header" style="width:70px;">[[.delete.]]</span>
					</span>
                    <br clear="all"/>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('member_level_policies');jQuery('#start_date_'+input_count).datepicker();jQuery('#end_date_'+input_count).datepicker();$('location_code_'+input_count).focus();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table>
</div></div>
</form>

<script>
<?php 	if(isset($_REQUEST['member_level_policies'])){
			echo 'var star = '.String::array2js($_REQUEST['member_level_policies']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('member_level_policies',star);
function Confirm(index)
{
    return confirm('[[.Are_you_sure_delete_member_level_policies.]] ?');
}
for(var i=101;i<=input_count;i++)
{
    jQuery("#start_date_"+i).datepicker();
    jQuery("#end_date_"+i).datepicker();
}
function ConfirmDelete()
{
    return confirm('[[.Are_you_sure_delete_member_level_policies_selected.]]');
}
</script>