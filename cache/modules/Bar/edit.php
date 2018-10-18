<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_bar_sample">
		<div id="input_group_#xxxx#">
            <input   name="mi_bar[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/>
			<span class="multi-input" style="width:30px; height: 24px; padding-top: 5px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_bar[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/></span>
			<span class="multi-input"><input  name="mi_bar[#xxxx#][code]" type="text" id="code_#xxxx#"  tabindex="-1" style="width:60px; height: 24px;"/></span>
			<span class="multi-input"><input  name="mi_bar[#xxxx#][name]" style="width:100px; height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <?php 
				if((User::can_admin(false,ANY_CATEGORY)))
				{?>
            <span class="multi-input"><input  name="mi_bar[#xxxx#][privilege]" style="width:100px; height: 24px;background-color:#CCC;" class="multi-edit-text-input" type="text" id="privilege_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><select  name="mi_bar[#xxxx#][department_id]" style="width:105px; height: 24px;" id="department_id_#xxxx#"><?php echo $this->map['department_id_options'];?></select></span>    
			<span class="multi-input"><input  name="mi_bar[#xxxx#][print_kitchen_name]" style="width:100px; height: 24px;" class="multi-edit-text-input" type="text" id="print_kitchen_name_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_bar[#xxxx#][kitchen_ip_address]" style="width:100px; height: 24px;" class="multi-edit-text-input" type="text" id="kitchen_ip_address_#xxxx#"/></span>
   			<span class="multi-input"><input  name="mi_bar[#xxxx#][print_bar_name]" style="width:100px; height: 24px;" class="multi-edit-text-input" type="text" id="print_bar_name_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_bar[#xxxx#][bar_ip_address]" style="width:100px; height: 24px;" class="multi-edit-text-input" type="text" id="bar_ip_address_#xxxx#"/></span>
               <span class="multi-input">
    			<select  name="mi_bar[#xxxx#][paper]" style="width:50px; height: 24px;" class="multi-edit-text-input"  id="paper_#xxxx#">
                    <option value=""><?php echo Portal::language('select_paper');?></option>
   				    <option value="K8"><?php echo Portal::language('k8_paper');?></option>
                    <option value="A5"><?php echo Portal::language('a5_paper');?></option>
                    <option value="A4"><?php echo Portal::language('a4_paper');?></option>
    			</select>
            </span> 
               <span class="multi-input">
				<select  name="mi_bar[#xxxx#][area_id]" style="width:50px; height: 24px;" class="multi-edit-text-input"  id="area_id_#xxxx#"><option value=""></option>
					<?php echo $this->map['area_options'];?>
				</select>
            </span>                                    
            <span class="multi-input"><input  name="mi_bar[#xxxx#][full_charge]" style="width:55px; height: 24px;" class="multi-edit-text-input" type="checkbox" id="full_charge_#xxxx#" value="1" onclick="return check_tax_service_rate();"/></span>
            <span class="multi-input"><input  name="mi_bar[#xxxx#][full_rate]" style="width:82px; height: 24px;" class="multi-edit-text-input" type="checkbox" id="full_rate_#xxxx#" value="1" onclick="return check_tax_service_rate();"/></span>
            <span class="multi-input"><input  name="mi_bar[#xxxx#][discount_after_tax]" style="width:100px; height: 24px;" class="multi-edit-text-input" type="checkbox" id="discount_after_tax_#xxxx#" value="1"/></span>    
            <!--<span class="multi-input"><input  name="mi_bar[#xxxx#][add_charge]" type="text" style="width:90px;" class="input_number" onkeyup="checkPercent(this);" id="add_charge_#xxxx#" value="1"/></span> 
              <span class="multi-input"><input  type="button" id="shifts_#xxxx#" onclick="getUrlShift('#xxxx#');" value="<?php echo Portal::language('add_shift');?>"  class="button-medium-add" style="width:50px !important;" title="<?php echo Portal::language('view_order');?>"></span>-->      
            
				<?php
				}
				?>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:30px; height: 24px; text-align:center;"><a href="#" tabindex="-1" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_bar','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></span>   
			
				<?php
				}
				?>
		</div>
        <br clear="all" />
	</span>
</span>
<form name="EditBarForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('manage_bar');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_bar');" class="w3-btn w3-red" style="text-decoration: none; text-transform: uppercase;"><?php echo Portal::language('Delete');?></a></td><?php }?>
        <!-- <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="button" value="<?php echo Portal::language('add_shift');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add_shift'));?>'" class="button-medium-add"/></td><?php }?>-->
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
						<span class="multi-input-header" style="float:left;width:30px;"><?php echo Portal::language('order_number');?></span>
						<span class="multi-input-header" style="float:left;width:60px;"><?php echo Portal::language('code');?></span>
						<span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('bar_name');?></span>
                        <!--IF::cond(User::can_admin(false,CATEGORY))-->
                        <span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('privilege');?></span>
                        <span class="multi-input-header" style="float:left;width:105px;"><?php echo Portal::language('department');?></span>
						<span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('print_kitchent_name');?></span>
                        <span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('kitchen_ip_address');?></span>
						<span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('print_bar_name');?></span>
                        <span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('bar_ip_address');?></span>
                        <span class="multi-input-header" style="float:left;width:50px;"><?php echo Portal::language('paper');?></span> 
                        <span class="multi-input-header" style="float:left;width:50px;"><?php echo Portal::language('area');?></span>                                                                    
                        <span class="multi-input-header" style="float:left;width:55px;"><?php echo Portal::language('full_charge');?></span>
                        <span class="multi-input-header" style="float:left;width:82px;"><?php echo Portal::language('full_rate');?></span>
                        <span class="multi-input-header" style="float:left;width:100px;"><?php echo Portal::language('discount_after_tax');?></span>
                       <!-- <span class="multi-input-header" style="float:left;width:90px;"><?php echo Portal::language('add_charge');?></span>
                         <span class="multi-input-header" style="float:left;width:80px;"><?php echo Portal::language('add_shift');?></span>-->
                        <!--/IF:cond-->
						<span class="multi-input-header" style="float:left;width:30px;text-align:center;"><?php echo Portal::language('Delete');?></span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_bar');$('name_'+input_count).focus();jQuery('#add_charge_'+input_count).ForceNumericOnly();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;"><?php echo Portal::language('Add');?></a></div>
</td></tr></table></td></tr></table><input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
<?php if(isset($_REQUEST['mi_bar'])){echo 'var bars = '.String::array2js($_REQUEST['mi_bar']).';';}else{echo 'var bars = [];';}?>
mi_init_rows('mi_bar',bars);
function Confirm(index){
	var mi_bar_name = $('name_'+ index).value;
	return confirm('<?php echo Portal::language('Are_you_sure_delete_mi_bar_name');?> '+ mi_bar_name+'?');
}
var DeleteMessage = '<?php echo Portal::language('Delete_mi_bar_which_is_used_cause_error_for_report_and_statistic');?>\n';
DeleteMessage += '<?php echo Portal::language('You_should_delete_when_you_sure_mi_bar_that_you_choose_never_in_used');?>';
function ConfirmDelete(){
	var checkok =false;
	for(var i=101;i<=input_count;i++){	
	  if($('_checked_'+i) && $('_checked_'+i).checked == true){
		  checkok =true;
		  break;
		}
	}
	if(checkok ==false){
		var messa='<?php echo Portal::language('no_items_selected');?>';
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
function check_tax_service_rate(){   
	for(var i=101;i<=input_count;i++){	
	  if($('full_charge_'+i).checked == true && $('full_rate_'+i).checked == true){
		  alert('<?php echo Portal::language('choose_only_one');?>');
          return false;
		}
	}
}
</script>
