<span style="display:none">
	<span id="party_promotions_sample">
		<div id="input_group_#xxxx#">
            <input   name="party_promotions[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px;height: 24px; background:#CCC"/>
			<span class="multi-input" style="width:30px;height: 24px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input   name="party_promotions[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px;height: 24px; background:#CCC"/></span>
			<span class="multi-input"><input   name="party_promotions[#xxxx#][code]" type="text" id="code_#xxxx#"  tabindex="-1" readonly="readonly" style="width:30px;height: 24px;background:#CCC"/></span>
            <span class="multi-input"><select name="party_promotions[#xxxx#][party_type_id]" id="party_type_id_#xxxx#" style="width:151px;height: 24px;" class="multi-edit-text-input">[[|promotion_type|]]</select></span>
			<span class="multi-input"><input  name="party_promotions[#xxxx#][name]" style="width:350px;height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
           	<span class="multi-input"><input  name="party_promotions[#xxxx#][note]" style="width:200px;height: 24px;" class="multi-edit-text-input" type="text" id="note_#xxxx#"/></span>
               <span class="multi-input"><input  name="party_promotions[#xxxx#][exist]" style="width:200px;height: 24px;" class="multi-edit-text-input" type="hidden" id="exist_#xxxx#"/></span>            
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;height: 24px; text-align:center;">
            <a id="delete_#xxxx#" href="#" tabindex="-1" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'party_type','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;">            
            <i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i>            
            </a>
            </span>
			<!--/IF:delete-->
            <br clear="all" />
		</div>        
	</span>
</span>
<form name="Editparty_promotions" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.manage_banquet_promotions.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input type="submit"  value="[[.Save.]]" id="check_submit" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;" onclick="checksubmit()"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('party_promotions');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
	   
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
				<span id="party_promotions_all_elems">
					<span style="white-space:nowrap; width:auto; text-transform: uppercase; text-align: center;">
						<span class="multi-input-header" style="width:30px; height: 24px; padding-top: 4px; text-align:center;"><input type="checkbox" value="1" onclick="mi_select_all_row('party_promotions',this.checked);"/></span>
						<span class="multi-input-header" style="width:30px; height: 24px; padding-top: 4px;">[[.order_number.]]</span>
						<span class="multi-input-header" style="width:31px; height: 24px; padding-top: 4px;">[[.code.]]</span>
                        <span class="multi-input-header" style="width: 150px; height: 24px; padding-top: 4px;">[[.promotions_type.]]</span>
						<span class="multi-input-header" style="width:350px; height: 24px; padding-top: 4px;">[[.banquet_promotions_name.]]</span>
                        <span class="multi-input-header" style="width:200px; height: 24px; padding-top: 4px;">[[.note.]]</span>
						<span class="multi-input-header" style="width:70px; height: 24px; padding-top: 4px;text-align:center;">[[.Delete.]]?</span>
						<br clear="all"/>
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('party_promotions');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php
  if(isset($_REQUEST['party_promotions']))
  {
	  echo 'var party_promotions = '.String::array2js($_REQUEST['party_promotions']).';';
  }
  else
  {
	  echo 'var party_promotions = [];';
  }
?>
mi_init_rows('party_promotions',party_promotions);
for(var i=101;i<=input_count;i++){    
    if(jQuery('#exist_'+i).val() == 1)
    {
        jQuery('#delete_'+i).hide();
        jQuery('#_checked_'+i).attr('disabled',true);
        jQuery('#_checked_'+i).hide();
    }
}
function Confirm(index){
	var party_promotions_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_party_promotions_name.]] '+ party_promotions_name+'?');
}
var DeleteMessage = '[[.Delete_party_promotions_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_party_promotions_that_you_choose_never_in_used.]]';
function checksubmit(){
    jQuery("#check_submit").css('display','none')
}
function ConfirmDelete(){
	var checkok =false;
	for(var i=101;i<=input_count;i++){
	  if($('_checked_'+i) && $('_checked_'+i).checked == true && jQuery('#_checked_'+i).attr('disabled') != 'disabled'){
		  
          checkok =true;
		  break;
		}else{
		  jQuery('#_checked_'+i).attr('checked',false);
		}        
	}
	if(checkok ==false){
		var messa='[[.no_items_selected.]]';
		return alert(messa);
	}else{
	  return confirm(DeleteMessage);}
}
var clicked = 0;
jQuery('.button-medium-save').click(function(event){
    clicked++;
    if(clicked >1)
    {
        event.preventDefault();    
    }
})
</script>