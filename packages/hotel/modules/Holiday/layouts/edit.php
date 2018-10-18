<span style="display:none">
	<span id="holiday_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input" style="width:30px;text-align:center"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="holiday[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:36px;background:#EFEFEF;border:1px solid #CCCCCC;"></span>
			<span class="multi-input">
					<input  name="holiday[#xxxx#][name]" style="width:155px;" type="text" id="name_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="holiday[#xxxx#][in_date]" style="width:75px;" type="text" id="in_date_#xxxx#">
			</span>
			<span class="multi-input">
				<input  name="holiday[#xxxx#][charge]" style="width:100px;" type="text" id="charge_#xxxx#">
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:50px;text-align:center;">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'holiday','#xxxx#','');event.returnValue=false; }" style="cursor:hand;"/>
			</span>
			<!--/IF:delete-->
		</div><br clear="all">
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditMinibarForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="90%" class="form-title">[[.manage_holiday.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('holiday');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
	</tr>
</table><br />
<table cellspacing="0">
	<tr valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div>
				<span id="holiday_all_elems">
					<span style="white-space:nowrap; width:auto;">
						<span class="multi-input-header" style="width:25px">&nbsp;<input type="checkbox" value="1" onclick="mi_select_all_row('holiday',this.checked);"></span>
						<span class="multi-input-header" style="width:35px">[[.ID.]]</span>
						<span class="multi-input-header" style="width:155px">[[.Holiday_name.]]</span>
						<span class="multi-input-header" style="width:75px">[[.date.]]</span>
						<span class="multi-input-header" style="width:100px">[[.extra_charge.]]</span>
						<span class="multi-input-header" style="width:50px">[[.Delete.]]?</span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><br /><a href="javascript:void(0);" onclick="mi_add_new_row('holiday');$('name_'+input_count).focus();jQuery('#in_date_'+input_count).datepicker();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php 	if(isset($_REQUEST['holiday'])){
			echo 'var holiday = '.String::array2js($_REQUEST['holiday']).';';
		}else{
			echo 'var holiday = [];';
		}
?>
mi_init_rows('holiday',holiday);
for(var i=101;i<=input_count;i++){
	if(jQuery("#in_date_"+i)){
		jQuery("#in_date_"+i).datepicker();
	}
}
function Confirm(index){
	var holiday_name = $('name_'+index).value;
	return confirm('[[.Are_you_sure_delete_holiday.]] '+holiday_name+'?');
}
var DeleteMessage = '[[.Delete_holiday_which_is_used_cause_error_for_report_and_statistic.]]';
DeleteMessage += '[[.You_should_delete_when_you_sure_holiday_that_you_choose_never_in_used.]]';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
</script>