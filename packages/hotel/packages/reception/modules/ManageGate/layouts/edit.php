<span style="display:none">
	<span id="gate_sample">
		<div id="input_group_#xxxx#" style="display:block;width:100%;">
			<span class="multi-input" style="width:27px;padding-left:2px;"><span><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span></span>
			<input  name="gate[#xxxx#][id]" type="hidden" id="id_#xxxx#">
            <span class="multi-input">
					<input  name="gate[#xxxx#][code]" style="width:100px;" type="text" id="code_#xxxx#">
			</span>	
            <span class="multi-input">
					<input  name="gate[#xxxx#][name]" style="width:200px;" type="text" id="name_#xxxx#">
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input"><span style="width:20px;">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'room','#xxxx#','');event.returnValue=false; }" style="cursor:hand;"/>
			</span></span>
			<!--/IF:delete-->
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditMinibarForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="" style="text-transform: uppercase; padding-left: 15px; font-size: 18px;">[[.manage_gate.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%" style="padding-right: 30px; text-align: right;"><input type="submit" value="[[.Save.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('gate');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a><?php }?>
		<input type="button" class="w3-btn w3-indigo" style="text-transform: uppercase; margin-right: 5px;" value="[[.discard.]]" onclick="location='<?php echo URL::build_current();?>';"/></td>
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
				<span id="gate_all_elems">
                    <span class="multi-input-header" style="width:25px;"><input type="checkbox" value="1" onclick="mi_select_all_row('gate',this.checked);"/></span>
                    <span class="multi-input-header" style="width:100px;">[[.code.]]</span>
                    <span class="multi-input-header" style="width:200px;">[[.gate_name.]]</span>
                    <span class="multi-input-header" style="width:20px;"></span><br clear="all">
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('gate');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php 	if(isset($_REQUEST['gate'])){
			echo 'var minibars = '.String::array2js($_REQUEST['gate']).';';
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
mi_init_rows('gate',minibars);
function Confirm(index){
	var room_name = $('name_'+index).value;
	return confirm('[[.Are_you_sure_delete_gate.]] '+room_name+'?');
}
var DeleteMessage = '[[.Are_you_sure_delete_gate.]]';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
</script>