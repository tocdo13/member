<span style="display:none">
	<span id="party_type_sample">
		<div id="input_group_#xxxx#">
            <input   name="party_type[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; padding-top: 4px; background:#CCC">
			<span class="multi-input" style="width:30px; height: 24px; padding-top: 4px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input   name="party_type[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; padding-top: 4px; background:#CCC"></span>
			<span class="multi-input"><input   name="party_type[#xxxx#][code]" type="text" id="code_#xxxx#"  tabindex="-1" readonly="readonly" style="width:30px; height: 24px; padding-top: 4px;background:#CCC"></span>
			<span class="multi-input"><input  name="party_type[#xxxx#][name]" style="width:150px; height: 24px; padding-top: 4px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"></span>
           	<span class="multi-input"><input  name="party_type[#xxxx#][note]" style="width:350px; height: 24px; padding-top: 4px;" class="multi-edit-text-input" type="text" id="note_#xxxx#"></span>
           	<span class="multi-input" style="width:70px; text-align:center; display: none;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/generate_button.png" style="cursor:pointer;" onclick="window.location='?page=banquet_type&cmd=config&id='+$('code_#xxxx#').value" /></a></span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px; height: 24px; text-align:center;"><a href="" tabindex="-1" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'party_type','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></span>
			<!--/IF:delete-->
            <br clear="all" />
		</div>        
	</span>
</span>
<form name="EditParty_Type" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.manage_banquet_type.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input type="submit" value="[[.Save.]]" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('party_type');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
	</tr>
</table>
<table cellspacing="0" style="margin-left: 15px;">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="party_type_all_elems">
					<span style="white-space:nowrap; width:auto; text-transform: uppercase; text-align: center;">
						<span class="multi-input-header" style="width:30px; height: 24px; padding-top: 4px; text-align:center;"><input type="checkbox" value="1" onclick="mi_select_all_row('party_type',this.checked);"></span>
						<span class="multi-input-header" style="width:30px; height: 24px; padding-top: 4px;">[[.order_number.]]</span>
						<span class="multi-input-header" style="width:30px; height: 24px; padding-top: 4px;">[[.code.]]</span>
						<span class="multi-input-header" style="width:150px; height: 24px; padding-top: 4px;">[[.banquet_type_name.]]</span>
                        <span class="multi-input-header" style="width:350px; height: 24px; padding-top: 4px;">[[.note.]]</span>
						<span class="multi-input-header" style="width:70px;text-align:center; display: none;">[[.config_banqet.]]?</span>
						<span class="multi-input-header" style="width:70px; height: 24px; padding-top: 4px;text-align:center;">[[.Delete.]]?</span>
						<br clear="all"/>
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('party_type');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php
  if(isset($_REQUEST['party_type']))
  {
	  echo 'var party_type = '.String::array2js($_REQUEST['party_type']).';';
  }
  else
  {
	  echo 'var party_type = [];';
  }
?>
mi_init_rows('party_type',party_type);
function Confirm(index){
	var party_type_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_party_type_name.]] '+ party_type_name+'?');
}
var DeleteMessage = '[[.Delete_party_type_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_party_type_that_you_choose_never_in_used.]]';
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