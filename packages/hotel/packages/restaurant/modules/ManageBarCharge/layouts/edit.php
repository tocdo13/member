<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_bar_charge_sample">
		<div id="input_group_#xxxx#">
            <input  name="mi_bar_charge[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input" style="width:30px; height: 24px; padding-top: 5px; text-align: center;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_bar_charge[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/></span>
			<span class="multi-input"><input  name="mi_bar_charge[#xxxx#][code]" type="text" id="code_#xxxx#" readonly="readonly" tabindex="-1" style="width:60px;height: 24px;"/></span>
			<span class="multi-input"><input  name="mi_bar_charge[#xxxx#][name]" style="width:200px;height: 24px;" readonly="readonly" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <!--IF::cond(User::can_admin(false,ANY_CATEGORY))-->
            <span class="multi-input"><select  name="mi_bar_charge[#xxxx#][bar_id_from]" style="width:155px;height: 24px;" id="bar_id_from_#xxxx#">[[|bar_id_options|]]</select></span>    
            <!-- Oanhbtk them value="0" , gan gia tri percent = 0% de luu csdl do trong forms/edit lay dieu kien $record['percent']!=''-->
            <span class="multi-input"><input  name="mi_bar_charge[#xxxx#][percent]" style="width:70px;height: 24px; text-align:right;" class="multi-edit-text-input" type="text" id="percent_#xxxx#" value="0"/></span> 
            <!-- End Oanhbtk -->
            <!--/IF::cond-->
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px;height: 24px; text-align:center;"><a href="#" tabindex="-1" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_bar_charge','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px; padding-top: 3px;"></i></a></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>
<form name="EditBarChargeForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound" style="margin-bottom: 20px;">
	<tr height="40">
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.manage_bar_charge.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input name="save" type="submit" id="save" value="[[.Save.]]" class="w3-btn w3-orange" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_bar_charge');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
        <?php //if(User::can_add(false,ANY_CATEGORY)){?><!--</a><td width="1%"><input type="button" value="[[.add_shift.]]" onclick="window.location='<?php //echo Url::build_current(array('cmd'=>'add_shift'));?>'" class="button-medium-add"/></td><?php //}?>-->
    </tr>
    
</table>
<span style="margin-left: 15px;">[[.Select_bar.]]:<select  name="bar_id" style="width:150px; height: 24px;" id="bar_id" onchange="window.location='?page=manage_bar_charge&bar_id='+this.value;">[[|bar_id_options|]]</select></span>
<input name="action" type="hidden" id="action" value="0" />
<table cellspacing="0" style="margin-left: 15px;">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
		<table border="0" >
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?><br />
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="mi_bar_charge_all_elems">
					<span style="white-space:nowrap; width:auto;">
						<span class="multi-input-header" style="float:left;width:30px; height: 24px;text-align:center;padding-top:5px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_bar_charge',this.checked);"></span>
						<span class="multi-input-header" style="float:left;width:30px; height: 24px;">[[.order_number.]]</span>
						<span class="multi-input-header" style="float:left;width:60px; height: 24px;">[[.code.]]</span>
						<span class="multi-input-header" style="float:left;width:200px; height: 24px;">[[.bar_name.]]</span>
                        <!--IF::cond(User::can_admin(false,CATEGORY))-->
                        <span class="multi-input-header" style="float:left;width:155px; height: 24px;">[[.bar_other.]]</span>
						<span class="multi-input-header" style="float:left;width:70px; height: 24px;">[[.percent.]] %</span>
                        <!--/IF:cond-->
						<span class="multi-input-header" style="float:left;width:30px; height: 24px;text-align:center;">[[.Delete.]]?</span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_bar_charge');jQuery('#percent_'+input_count).ForceNumericOnly();AddItems(input_count);" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px; text-decoration: none;">[[.Add.]]</a></div>
</td></tr></table></td></tr></table></form>
<script>
<?php if(isset($_REQUEST['mi_bar_charge']) && !empty($_REQUEST['mi_bar_charge'])){echo 'var check = 1;var bars = '.String::array2js($_REQUEST['mi_bar_charge']).';';}else{echo ' var check = 0; var bars = [];';}?>

var bar_id = '<?php echo (isset($_REQUEST['bar_id'])?$_REQUEST['bar_id']:'')?>';
bars_js = [[|bar_js|]];
if(bar_id!=''){
	jQuery('#bar_id').val(bar_id);	
}
if(check==1){
	mi_init_rows('mi_bar_charge',bars);	
}
AddAutomatic();
function AddAutomatic(){
	if($('bar_id') && jQuery('#bar_id').val()!=''){
		for(var j in bars_js){
			if(bars_js[j]['id'] != jQuery('#bar_id').val()){
				var kt = 0;
				for(var i=101; i<=input_count;i++){
					if(jQuery('#bar_id_from_'+i).val() == bars_js[j]['id']){
						kt = 1;	
					}
				}
				if(kt==0){
					mi_add_new_row('mi_bar_charge');
					jQuery('#percent_'+input_count).ForceNumericOnly();
					AddItems(input_count);
					jQuery('#bar_id_from_'+input_count).val(bars_js[j]['id']);
				}
			}
		}
	}
}
function AddItems(index){
	if($('name_'+index) && $('bar_id_from_'+index)){
		jQuery('#name_'+index).val(jQuery("#bar_id option:selected").text());	
		jQuery("#bar_id_from_"+index+" option[value=" +jQuery('#bar_id').val()+ "]").attr('disabled',true);
		if(bars_js[jQuery("#bar_id").val()]){
			jQuery('#code_'+index).val(bars_js[jQuery("#bar_id").val()]['code']);
		}		
	}	
}
function Confirm(index){
	var mi_bar_charge_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_mi_bar_charge_name.]] '+ mi_bar_charge_name+'?');
}
var DeleteMessage = '[[.Delete_mi_bar_charge_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_bar_charge_that_you_choose_never_in_used.]]';
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
		if($('name_'+i) && $('bar_id_from_'+i)){
			jQuery('#name_'+i).val(jQuery("#bar_id option:selected").text());	
			jQuery("#bar_id_from_"+i+" option[value=" +jQuery('#bar_id').val()+ "]").Attr('disabled');
			
		}
	}
}
function checkPercent(obj){   
	if(to_numeric(obj.value)>100){
		obj.value = obj.value.substr(0,(obj.value.length)-1);		  
	}
}
</script>