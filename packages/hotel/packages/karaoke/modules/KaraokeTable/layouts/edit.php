<span style="display:none">
	<span id="karaoke_table_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-edit-input_header"><span><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span></span>
			<span class="multi-edit-input"><span><input  name="karaoke_table[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px;background:#EFEFEF;border:1px solid #CCCCCC;"></span></span>
			<span class="multi-edit-input">
					<input  name="karaoke_table[#xxxx#][code]" style="width:150px;" class="multi-edit-text-input" type="text" id="code_#xxxx#">
			</span>
			<span class="multi-edit-input">
					<input  name="karaoke_table[#xxxx#][name]" style="width:150px;" class="multi-edit-text-input" type="text" id="name_#xxxx#">
			</span>
			<span class="multi-edit-input">
				<input  name="karaoke_table[#xxxx#][num_people]" style="width:70px;" class="multi-edit-text-input" type="text" id="num_people_#xxxx#">
			</span>
            <span class="multi-edit-input">
				<input  name="karaoke_table[#xxxx#][price]" style="width:70px; text-align: right;" class="multi-edit-text-input" type="text" id="price_#xxxx#">
			</span>
            <span class="multi-edit-input">
				<input  name="karaoke_table[#xxxx#][table_group]" style="width:70px;" class="multi-edit-text-input" type="text" id="table_group_#xxxx#">
			</span>
			<span class="multi-edit-input">
				<select  name="karaoke_table[#xxxx#][karaoke_id]" style="width:150px;" class="multi-edit-text-input" id="karaoke_id_#xxxx#">[[|karaoke_options|]]</select>
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi_edit_input"><span style="width:20px;">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'karaoke_table','#xxxx#','');event.returnValue=false; }" style="cursor:hand;"/>
			</span></span>
			<!--/IF:delete-->
			<br clear="all">
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>

<form name="EditMinikaraokeForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="form-title">[[.manage_karaoke_table.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('karaoke_table');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
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
				<span id="karaoke_table_all_elems">
					<span style="white-space:nowrap; width:auto;">
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:25px;text-align:left;padding:0px;"><input type="checkbox" value="1" onclick="mi_select_all_row('karaoke_table',this.checked);"></span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:35px;text-align:left;padding-left:2px;">[[.ID.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:155px;text-align:left;padding-left:2px;">[[.code.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:155px;text-align:left;padding-left:2px;">[[.name.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:75px;text-align:left;padding-left:2px;">[[.no_of_people.]]</span></span>
                        <span class="multi-edit-input_header"><span class="table-title" style="float:left;width:75px;text-align:left;padding-left:2px;">[[.price.]]</span></span>
                        <span class="multi-edit-input_header"><span class="table-title" style="float:left;width:75px;text-align:left;padding-left:2px;">[[.group.]]</span></span>
						<span class="multi-edit-input_header"><span class="table-title" style="float:left;width:150px;text-align:left;padding-left:2px;">[[.karaoke.]]</span></span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a onclick="mi_add_new_row('karaoke_table');$('name_'+input_count).focus();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>

<script>
<?php 	if(isset($_REQUEST['karaoke_table'])){
			echo 'var karaoke_table = '.String::array2js($_REQUEST['karaoke_table']).';';
		}else{
			echo 'var karaoke_table = [];';
		}
?>
mi_init_rows('karaoke_table',karaoke_table);
function Confirm(index){
	var karaoke_table_name = $('name_'+index).value;
	return confirm('[[.Are_you_sure_delete_karaoke_table.]] '+karaoke_table_name+'?');
}
var DeleteMessage = '[[.Delete_karaoke_table_which_is_used_cause_error_for_report_and_statistic.]]';
DeleteMessage += '[[.You_should_delete_when_you_sure_karaoke_table_that_you_choose_never_in_used.]]';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
</script>