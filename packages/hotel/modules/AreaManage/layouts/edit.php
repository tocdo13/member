<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_area_sample">
		<div id="input_group_#xxxx#">
            <input   name="mi_area[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input" style="width:30px;border:1px solid #CCC;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_area[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/></span>
			<span class="multi-input"><input  name="mi_area[#xxxx#][code]" style="width:200px;" class="multi-edit-text-input" type="text" id="code_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_area[#xxxx#][name_1]" style="width:200px;" class="multi-edit-text-input" type="text" id="name_1_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_area[#xxxx#][name_2]" style="width:200px;" class="multi-edit-text-input" type="text" id="name_2_#xxxx#"/></span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_area','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/></span>   
			<!--/IF:delete-->
            <br clear="all" />
        </div>
	</span>
</span>
<form name="EditBarForm" method="post" >
<table cellpadding="15" cellspacing="0" width="70%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="100%" class="form-title">[[.manage_area_group.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"/></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_area');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
    </tr>
    
</table>
<table cellspacing="0">
	<tr><td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="mi_area_all_elems">
					<span style="white-space:nowrap; width:auto;">
						<span class="multi-input-header" style="float:left;width:30px;text-align:left;padding:0px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_area',this.checked);"></span>
						<span class="multi-input-header" style="float:left;width:30px;">[[.order_number.]]</span>
						<span class="multi-input-header" style="float:left;width:200px;">[[.code.]]</span>
                        <span class="multi-input-header" style="float:left;width:200px;">[[.name_1.]]</span>
                        <span class="multi-input-header" style="float:left;width:200px;">[[.name_2.]]</span>
						<span class="multi-input-header" style="float:left;width:30px;text-align:center;">[[.Delete.]]?</span>
						<br clear="all"/>
					</span>
				</span>
			</div>
            <br />
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_area');$('name_'+input_count).focus();jQuery('#price_'+input_count).ForceNumericOnly();jQuery('#price_'+input_count).FormatNumber();" class="button-medium-add">[[.Add.]]</a></div>
            </td>   
        </tr>
        </table>
    </td></tr>
</table>
</form>
<script>
<?php if(isset($_REQUEST['mi_area'])){echo 'var areas = '.String::array2js($_REQUEST['mi_area']).';';}else{echo 'var areas = [];';}?>
mi_init_rows('mi_area',areas);
function Confirm(index){
	var mi_area_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_mi_area_name.]] '+ mi_area_name+'?');
}
var DeleteMessage = '[[.Delete_mi_area_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_area_that_you_choose_never_in_used.]]';
function ConfirmDelete(){
	var checkok =false;
	for(var i=101;i<=input_count;i++){	
	  if($('_checked_'+i) && $('_checked_'+i).checked == true){
		  checkok =true;
		  break;
		}
	}
	if(checkok ==false){
		var messa='[[.no_items_selected.]]';
		return alert(messa);
	}else{
	  return confirm(DeleteMessage);}
}
</script>