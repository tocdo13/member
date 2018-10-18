<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_bar_sample">
		<div id="input_group_#xxxx#">
            <input   name="mi_bar[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input" style="width:30px;border:1px solid #CCC;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_bar[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/></span>
			<span class="multi-input"><input  name="mi_bar[#xxxx#][code]" type="text" id="code_#xxxx#"  tabindex="-1" style="width:60px;"/></span>
			<span class="multi-input"><input  name="mi_bar[#xxxx#][name]" style="width:100px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <!--IF::cond(User::can_admin(false,ANY_CATEGORY))-->
            <span class="multi-input"><input  name="mi_bar[#xxxx#][privilege]" style="width:100px;background-color:#CCC;" class="multi-edit-text-input" type="text" id="privilege_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><select  name="mi_bar[#xxxx#][department_id]" style="width:105px;" id="department_id_#xxxx#">[[|department_id_options|]]</select></span>    
			<span class="multi-input"><input  name="mi_bar[#xxxx#][print_kitchen_name]" style="width:150px;" class="multi-edit-text-input" type="text" id="print_kitchen_name_#xxxx#"/></span>
   			<span class="multi-input"><input  name="mi_bar[#xxxx#][print_bar_name]" style="width:150px;" class="multi-edit-text-input" type="text" id="print_bar_name_#xxxx#"/></span>                                    
            <span class="multi-input"><input  name="mi_bar[#xxxx#][full_charge]" style="width:45px;" class="multi-edit-text-input" type="checkbox" id="full_charge_#xxxx#" value="1"/></span>
            <span class="multi-input"><input  name="mi_bar[#xxxx#][full_rate]" style="width:45px;" class="multi-edit-text-input" type="checkbox" id="full_rate_#xxxx#" value="1"/></span>  
            <span class="multi-input"><input  name="mi_bar[#xxxx#][add_charge]" type="text" style="width:50px;" class="input_number" onkeyup="checkPercent(this);" id="add_charge_#xxxx#" value="1"/></span>  
             <span class="multi-input"><input  type="button" id="shifts_#xxxx#" onclick="getUrlShift('#xxxx#');" value="[[.add_shift.]]"  class="button-medium-add" style="width:50px !important;" title="[[.view_order.]]"></span>      
            <!--/IF::cond-->
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_bar','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>
<form name="EditBarForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="98%" class="form-title">[[.manage_bar.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"/></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_bar');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="button" value="[[.add_shift.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add_shift'));?>'" class="button-medium-add"/></td><?php }?>
    </tr>
    
</table>
<table cellspacing="0">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="mi_bar_all_elems">
					<span style="white-space:nowrap; width:auto;">
						<span class="multi-input-header" style="float:left;width:30px;text-align:left;padding:0px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_bar',this.checked);"></span>
						<span class="multi-input-header" style="float:left;width:30px;">[[.order_number.]]</span>
						<span class="multi-input-header" style="float:left;width:60px;">[[.code.]]</span>
						<span class="multi-input-header" style="float:left;width:100px;">[[.bar_name.]]</span>
                        <!--IF::cond(User::can_admin(false,CATEGORY))-->
                        <span class="multi-input-header" style="float:left;width:100px;">[[.privilege.]]</span>
                        <span class="multi-input-header" style="float:left;width:100px;">[[.department.]]</span>
						<span class="multi-input-header" style="float:left;width:150px;">[[.print_kitchent_name.]]</span>
						<span class="multi-input-header" style="float:left;width:150px;">[[.print_bar_name.]]</span>                                                                  
                        <span class="multi-input-header" style="float:left;width:50px;">[[.full_charge.]]</span>
                        <span class="multi-input-header" style="float:left;width:50px;">[[.full_rate.]]</span>
                        <span class="multi-input-header" style="float:left;width:50px;">[[.add_charge.]]</span>
                         <span class="multi-input-header" style="float:left;width:80px;">[[.add_shift.]]</span>
                        <!--/IF:cond-->
						<span class="multi-input-header" style="float:left;width:30px;text-align:center;">[[.Delete.]]?</span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_bar');$('name_'+input_count).focus();jQuery('#add_charge_'+input_count).ForceNumericOnly();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php if(isset($_REQUEST['mi_bar'])){echo 'var bars = '.String::array2js($_REQUEST['mi_bar']).';';}else{echo 'var bars = [];';}?>
mi_init_rows('mi_bar',bars);
function Confirm(index){
	var mi_bar_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_mi_bar_name.]] '+ mi_bar_name+'?');
}
var DeleteMessage = '[[.Delete_mi_bar_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_bar_that_you_choose_never_in_used.]]';
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
function getUrlShift(index){
	if($('id_'+index).value!='')
	{
		var url = '?page=restaurant_bar';
		url += '&bar_id='+to_numeric($('id_'+index).value);
		url += '&cmd=add_shift';
		window.open(url);
	}	
}
function checkPercent(obj){   
	if(to_numeric(obj.value)>100){
		obj.value = obj.value.substr(0,(obj.value.length)-1);		  
	}
}
</script>