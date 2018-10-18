<span style="display:none">
	<span id="mi_bar_area_sample">
		<div id="input_group_#xxxx#">
            <input   name="mi_bar_area[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/>
			<span class="multi-input" style="width:30px;height: 24px; text-align: center; padding-top: 3px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_bar_area[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px;height: 24px; background:#CCC; text-align: center;"/></span>
			<!--IF::cond(User::can_admin(false,ANY_CATEGORY))-->
            <span class="multi-input"><input  name="mi_bar_area[#xxxx#][name]" style="width:100px;height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <span class="multi-input">
				<select name="mi_bar_area[#xxxx#][bar_id]" style="width:150px;height: 24px;" class="multi-edit-text-input"  id="bar_id_#xxxx#"><option value=""></option>
					[[|bar_option|]]
				</select>
            </span>
            <span class="multi-input"><input  name="mi_bar_area[#xxxx#][print_automatic_bill]" style="width:100px;height: 24px;" class="multi-edit-text-input" type="text" id="print_automatic_bill_#xxxx#"/></span>
            <span class="multi-input" style="display: none;"><input  name="mi_bar_area[#xxxx#][print_kitchen_name]" style="width:100px;height: 24px;" class="multi-edit-text-input" type="text" id="print_kitchen_name_#xxxx#"/></span>
   			<span class="multi-input" style="display: none;"><input  name="mi_bar_area[#xxxx#][print_bar_name]" style="width:100px;height: 24px;" class="multi-edit-text-input" type="text" id="print_bar_name_#xxxx#"/></span>
            <!--/IF::cond-->
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:30px; text-align:center;"><a href="#" tabindex="-1" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_bar_area','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>
<form name="BarAreaForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
    		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> [[.manage_bar_area.]]</td>
    		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input type="submit" value="[[.Save.]]" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
    		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_bar_area');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table>
    <table cellspacing="0" style="margin-left: 15px;">
	   <tr bgcolor="#EEEEEE" valign="top">
            <td></td>
       </tr>
	   <tr>
            <td style="padding-bottom:30px">
        		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
        		<table border="0">
            		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
            		<tr bgcolor="#EEEEEE" valign="top">
            			<td style="">
                			<div style="background-color:#EFEFEF;">
                				<span id="mi_bar_area_all_elems">
                					<span style="white-space:nowrap; width:auto; text-transform: uppercase;">
                						<span class="multi-input-header" style="width:30px; height: 20px; text-align:center;padding-top:2px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_bar_area',this.checked);" /></span>
                						<span class="multi-input-header" style="width:30px;height: 20px; padding-top:2px;">[[.order_number.]]</span>
                						
                                        <!--IF::cond(User::can_admin(false,CATEGORY))-->
                                        <span class="multi-input-header" style="width:100px;height: 20px; padding-top:2px;">[[.bar_area_name.]]</span>
                                        <span class="multi-input-header" style="width:150px;height: 20px; padding-top:2px;">[[.bar.]]</span>
                                        <span class="multi-input-header" style="width:100px;height: 20px; padding-top:2px;">[[.print_automatic_bill.]]</span>
                                        <span class="multi-input-header" style="width:100px;height: 20px; padding-top:2px; display: none;">[[.print_kitchent_name.]]</span>
				                        <span class="multi-input-header" style="width:100px;height: 20px; padding-top:2px; display: none;">[[.print_bar_name.]]</span>
                                        <!--/IF:cond-->
                                        <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
                						<span class="multi-input-header" style="width:30px;height: 20px; padding-top:2px;text-align:center;">[[.Delete.]]</span>
                						<!--/IF:delete-->
                                        <br clear="all" />
                					</span>
                				</span>
                			</div>
                            <div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_bar_area');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;">[[.Add.]]</a></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<script>
<?php if(isset($_REQUEST['mi_bar_area'])){echo 'var bar_areas = '.String::array2js($_REQUEST['mi_bar_area']).';';}else{echo 'var bar_areas = [];';}?>
mi_init_rows('mi_bar_area',bar_areas);
function Confirm(index){
	var mi_bar_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_mi_bar_area_name.]] '+ mi_bar_name+'?');
}
var DeleteMessage = '[[.Delete_mi_bar_area_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_bar_area_that_you_choose_never_in_used.]]';
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

