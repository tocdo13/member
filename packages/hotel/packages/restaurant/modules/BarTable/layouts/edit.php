<span style="display:none">
	<span id="bar_table_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-edit-input_header"><span><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1" style="height: 16;"/></span></span>
			<span class="multi-edit-input"><span><input  name="bar_table[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px;height: 24px;background:#EFEFEF;border:1px solid #CCCCCC;"></span></span>
			<span class="multi-edit-input">
					<input  name="bar_table[#xxxx#][code]" style="width:100px;height: 24px;" class="multi-edit-text-input" type="text" id="code_#xxxx#">
			</span>
			<span class="multi-edit-input">
					<input  name="bar_table[#xxxx#][name]" style="width:150px;height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#">
			</span>
			<span class="multi-edit-input">
				<input  name="bar_table[#xxxx#][num_people]" style="width:70px;height: 24px;" class="multi-edit-text-input" type="text" id="num_people_#xxxx#" />
			</span>
            <span class="multi-edit-input">
				<input  name="bar_table[#xxxx#][table_group]" style="width:100px;height: 24px; display: ;" class="multi-edit-text-input" type="text" id="table_group_#xxxx#" />
			</span>
			<span class="multi-edit-input">
				<select  name="bar_table[#xxxx#][bar_id]" style="width:150px;height: 24px;" class="multi-edit-text-input" id="bar_id_#xxxx#">[[|bar_options|]]</select>
			</span>
            <span class="multi-edit-input">
				<select  name="bar_table[#xxxx#][bar_area_id]" style="width:150px;height: 24px;" class="multi-edit-text-input" id="bar_area_id_#xxxx#" onchange="fun_get_group(this);">[[|bar_area_options|]]</select>
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi_edit_input"><span style="width:20px;">
				<a href="#" tabindex="-1" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'bar_table','#xxxx#','');event.returnValue=false; }" style="cursor:hand;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px;"></i></a>
			</span></span>
			<!--/IF:delete-->
			<br clear="all">
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>

<form name="EditMinibarForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> [[.manage_bar_table.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%" style="text-align: right; padding-right: 30px;"><input type="submit" value="[[.Save.]]" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('bar_table');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
	</tr>
</table>
<table cellspacing="0">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="bar_table_all_elems">
					<span style="white-space:nowrap; width:auto; text-transform: uppercase;">
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:25px;height: 24px;text-align:left;padding:0px;"><input type="checkbox" value="1" onclick="mi_select_all_row('bar_table',this.checked);"></span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:35px;height: 24px;text-align:left;padding-left:2px;">[[.ID.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:100px;height: 24px;text-align:left;padding-left:2px;">[[.code.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:155px;height: 24px;text-align:left;padding-left:2px;">[[.name.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:75px;height: 24px;text-align:left;padding-left:2px;">[[.no_of_people.]]</span></span>
                        <span class="multi-edit-input_header"><span class="table-title" style="float:left;width:100px;height: 24px;text-align:left;padding-left:2px; display: ;">[[.group.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:150px;height: 24px;text-align:left;padding-left:2px;">[[.bar.]]</span></span>
                        <span class="multi-edit-input_header"><span class="table-title" style="float:left;width:150px;height: 24px;text-align:left;padding-left:2px;">[[.bar_area.]]</span></span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a onclick="mi_add_new_row('bar_table');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="margin-top: 5px; text-transform: uppercase;">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>

<script>
<?php 	if(isset($_REQUEST['bar_table'])){
			echo 'var bar_table = '.String::array2js($_REQUEST['bar_table']).';';
		}else{
			echo 'var bar_table = [];';
		}
?>
mi_init_rows('bar_table',bar_table);
function Confirm(index){
	var bar_table_name = $('name_'+index).value;
	return confirm('[[.Are_you_sure_delete_bar_table.]] '+bar_table_name+'?');
}
var DeleteMessage = '[[.Delete_bar_table_which_is_used_cause_error_for_report_and_statistic.]]';
DeleteMessage += '[[.You_should_delete_when_you_sure_bar_table_that_you_choose_never_in_used.]]';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
function fun_get_group(obj)
{
    var id=obj.id.split('_');
    var value = obj.val;
    jQuery("#table_group_"+id).val(value);
}
</script>