<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_karaoke_charge_sample">
		<div id="input_group_#xxxx#">
            <input  name="mi_karaoke_charge[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input" style="width:30px;border:1px solid #CCC;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_karaoke_charge[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/></span>
			<span class="multi-input"><input  name="mi_karaoke_charge[#xxxx#][code]" type="text" id="code_#xxxx#" readonly="readonly" tabindex="-1" style="width:60px;"/></span>
			<span class="multi-input"><input  name="mi_karaoke_charge[#xxxx#][name]" style="width:200px;" readonly="readonly" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <!--IF::cond(User::can_admin(false,ANY_CATEGORY))-->
            <span class="multi-input"><select  name="mi_karaoke_charge[#xxxx#][karaoke_id_from]" style="width:155px;" id="karaoke_id_from_#xxxx#">[[|karaoke_id_options|]]</select></span>    
            <span class="multi-input"><input  name="mi_karaoke_charge[#xxxx#][percent]" style="width:70px; text-align:right;" class="multi-edit-text-input" type="text" id="percent_#xxxx#"/></span>
            <!--/IF::cond-->
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_karaoke_charge','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>
<form name="EditKaraokeChargeForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="98%" class="form-title">[[.manage_karaoke_charge.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input name="save" type="submit" id="save" value="[[.Save.]]" class="button-medium-save"/></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_karaoke_charge');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        <?php if(User::can_add(false,ANY_CATEGORY)){?><!--<td width="1%"><input type="button" value="[[.add_shift.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add_shift'));?>'" class="button-medium-add"/></td><?php }?>-->
    </tr>
    
</table>
[[.Select_karaoke.]]:<select  name="karaoke_id" style="width:150px;" id="karaoke_id" onchange="window.location='?page=karaoke_manage_charge&karaoke_id='+this.value;">[[|karaoke_id_options|]]</select>
<input name="action" type="hidden" id="action" value="0" />
<table cellspacing="0">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="mi_karaoke_charge_all_elems">
					<span style="white-space:nowrap; width:auto;">
						<span class="multi-input-header" style="float:left;width:30px;text-align:left;padding:0px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_karaoke_charge',this.checked);"></span>
						<span class="multi-input-header" style="float:left;width:30px;">[[.order_number.]]</span>
						<span class="multi-input-header" style="float:left;width:60px;">[[.code.]]</span>
						<span class="multi-input-header" style="float:left;width:200px;">[[.karaoke_name.]]</span>
                        <!--IF::cond(User::can_admin(false,CATEGORY))-->
                        <span class="multi-input-header" style="float:left;width:150px;">[[.karaoke_other.]]</span>
						<span class="multi-input-header" style="float:left;width:70px;">[[.percent.]] %</span>
                        <!--/IF:cond-->
						<span class="multi-input-header" style="float:left;width:30px;text-align:center;">[[.Delete.]]?</span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_karaoke_charge');jQuery('#percent_'+input_count).ForceNumericOnly();AddItems(input_count);" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php if(isset($_REQUEST['mi_karaoke_charge']) && !empty($_REQUEST['mi_karaoke_charge'])){echo 'var check = 1;var karaokes = '.String::array2js($_REQUEST['mi_karaoke_charge']).';';}else{echo ' var check = 0; var karaokes = [];';}?>

var karaoke_id = '<?php echo (isset($_REQUEST['karaoke_id'])?$_REQUEST['karaoke_id']:'')?>';
karaokes_js = [[|karaoke_js|]];
if(karaoke_id!=''){
	jQuery('#karaoke_id').val(karaoke_id);	
}
if(check==1){
	mi_init_rows('mi_karaoke_charge',karaokes);	
}
AddAutomatic();
function AddAutomatic(){
	if($('karaoke_id') && jQuery('#karaoke_id').val()!=''){
		for(var j in karaokes_js){
			if(karaokes_js[j]['id'] != jQuery('#karaoke_id').val()){
				var kt = 0;
				for(var i=101; i<=input_count;i++){
					if(jQuery('#karaoke_id_from_'+i).val() == karaokes_js[j]['id']){
						kt = 1;	
					}
				}
				if(kt==0){
					mi_add_new_row('mi_karaoke_charge');
					jQuery('#percent_'+input_count).ForceNumericOnly();
					AddItems(input_count);
					jQuery('#karaoke_id_from_'+input_count).val(karaokes_js[j]['id']);
				}
			}
		}
	}
}
function AddItems(index){
	if($('name_'+index) && $('karaoke_id_from_'+index)){
		jQuery('#name_'+index).val(jQuery("#karaoke_id option:selected").text());	
		jQuery("#karaoke_id_from_"+index+" option[value=" +jQuery('#karaoke_id').val()+ "]").attr('disabled',true);
		if(karaokes_js[jQuery("#karaoke_id").val()]){
			jQuery('#code_'+index).val(karaokes_js[jQuery("#karaoke_id").val()]['code']);
		}		
	}	
}
function Confirm(index){
	var mi_karaoke_charge_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_mi_karaoke_charge_name.]] '+ mi_karaoke_charge_name+'?');
}
var DeleteMessage = '[[.Delete_mi_karaoke_charge_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_karaoke_charge_that_you_choose_never_in_used.]]';
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
function UpdateAll(){
	for(var i=101;i<=input_count;i++){
		if($('name_'+i) && $('karaoke_id_from_'+i)){
			jQuery('#name_'+i).val(jQuery("#karaoke_id option:selected").text());	
			jQuery("#karaoke_id_from_"+i+" option[value=" +jQuery('#karaoke_id').val()+ "]").Attr('disabled');
			
		}
	}
}
function checkPercent(obj){   
	if(to_numeric(obj.value)>100){
		obj.value = obj.value.substr(0,(obj.value.length)-1);		  
	}
}
</script>