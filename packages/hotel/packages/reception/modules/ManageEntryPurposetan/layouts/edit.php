<span style="display:none">
	<span id="entry_purposes_sample">
		<div id="input_group_#xxxx#" style="display:block;width:100%;">
			<span class="multi-input" style="width:27px;padding-left:2px;"><span>
            <input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span></span>
			<span class="multi-input">
		            <input  name="entry_purposes[#xxxx#][id]" type="hidden" id="id_#xxxx#">
					<input  name="entry_purposes[#xxxx#][code]" style="width:35px;" type="text" id="code_#xxxx#">
			</span>	
			<span class="multi-input">
					<input  name="entry_purposes[#xxxx#][code_n]" style="width:45px;" type="text" id="code_n_#xxxx#">
			</span>
            <span class="multi-input">
					<input  name="entry_purposes[#xxxx#][name]" style="width:200px;" type="text" id="name_#xxxx#">
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input"><span style="width:20px;">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'entry_purposes','#xxxx#','');event.returnValue=false; }" style="cursor:hand;"/>
			</span></span>
			<!--/IF:delete-->
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditMinibarForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="form-title">[[.manage_entry_purposes.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('entry_purposes');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
	</tr>
</table>
<table cellspacing="0">
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div >
				<span id="entry_purposes_all_elems">
                    <span class="multi-input-header" style="width:25px;"><input type="checkbox" value="1" onclick="mi_select_all_row('entry_purposes',this.checked);"></span>
                    <span class="multi-input-header" style="width:35px;">[[.code.]]</span>
                    <span class="multi-input-header" style="width:45px;">[[.code_n.]]</span>
                    <span class="multi-input-header" style="width:200px;">[[.entry_purposes_name.]]</span>
                    <span class="multi-input-header" style="width:20px;"></span><br clear="all">
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('entry_purposes');$('name_'+input_count).focus();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php 	if(isset($_REQUEST['entry_purposes'])){
			echo 'var minibars = '.String::array2js($_REQUEST['entry_purposes']).';';
		}else{
			echo 'var minibars = [];';
		}
?>
function warning(obj){
	if(jQuery('#id_'+jQuery(obj).attr('index')).val() && window.minibars[jQuery('#id_'+jQuery(obj).attr('index')).val()]){
		var obj = window.minibars[jQuery('#id_'+jQuery(obj).attr('index')).val()];
		if(obj['no_delete']=='true'){
			alert('[[.you_can_delete_this_minibar_because_it_cause_damaged_for_system.]]');
			return false;
		}
	}
	return true;
}
mi_init_rows('entry_purposes',minibars);
function Confirm(index){
	var entry_purposes_name = $('name_'+index).value;
	return confirm('[[.Are_you_sure_delete_entry_purposes.]] '+entry_purposes_name+'?');
}
var DeleteMessage = '[[.Delete_entry_purposes_which_is_used_cause_error_for_report_and_statistic.]]';
DeleteMessage += '[[.You_should_delete_when_you_sure_entry_purposes_that_you_choose_never_in_used.]]';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
</script>