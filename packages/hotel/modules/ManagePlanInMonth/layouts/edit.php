<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>

<span style="display:none">
	<span id="mi_plan_sample">
		<div id="input_group_#xxxx#">
            <input  name="mi_plan[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			
            <span class="multi-input" style="width:30px;border:1px solid #CCC;">
                <input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/>
            </span>
			<span class="multi-input">
                <select  name="mi_plan[#xxxx#][name]" style="width:140px;" id="name_#xxxx#" class="plan_room" >[[|name_list|]]</select>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][room_type_id]" style="width:80px;" id="room_type_id_#xxxx#" class="plan_room" >[[|room_type_list|]]</select>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][bar]" style="width:155px;" id="bar_#xxxx#" class="plan_bar" >[[|bar_list|]]</select>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][bar_index]" style="width:155px;" id="bar_index_#xxxx#" class="plan_bar" >[[|bar_index|]]</select>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][total]" style="width:190px;" id="total_#xxxx#" class="plan_total" >[[|total|]]</select>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][month]" style="width:80px;" id="month_#xxxx#">[[|month_list|]]</select>
            </span>
            <span class="multi-input">
                <input  name="mi_plan[#xxxx#][value]" style="width:60px;text-align: right;" type="text" id="value_#xxxx#"/>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][currency_id]" style="width:112px;" id="currency_id_#xxxx#">[[|currency_id_list|]]</select>
            </span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:55px; text-align:center;">
                <img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_plan','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/>
            </span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>

<form name="EditPlanForm" method="post" >

<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="100%" class="form-title" style="text-indent: 50px;">[[.manage_plan_in_month.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" name="save" class="button-medium-save"/></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) {mi_delete_selected_row('mi_plan');}" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
    </tr>
</table>

<div class="search-box">	
    <fieldset>
    	<legend class="title">[[.select.]]</legend>
        
        <table border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td align="right" nowrap style="font-weight:bold">[[.year.]]</td>
					<td>:</td>
					<td>
						<select  name="year" id="year" style="width:80px;" onchange="EditPlanForm.submit()">
                        <?php
                            for($i=date('Y')+1;$i>=BEGINNING_YEAR;$i--)
                            {
                            echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
                            }
                        ?>
						</select>
					</td>
                    <td>
                        <select  name="type_plan" id="select_plan" style="width:150px;" onchange="EditPlanForm.submit()">
                            <!-- 
                            <option id="plan_room" value="1" <?php if(($_POST['type_plan'])==1){echo 'selected';}else {echo '';} ?>>[[.plan.]] [[.room.]]</option>
                            <option id="plan_bar" value="2" <?php if(($_POST['type_plan'])==2){echo 'selected';}else {echo '';}  ?> >[[.plan.]] [[.bar.]]</option>
                            <option id="plan_total" value="3" <?php if(($_POST['type_plan'])==3){echo 'selected';}else {echo '';}  ?> >[[.plan.]] [[.total.]]</option>
						    -->
                            <?php $selectOption = $_POST['type_plan']; ?>
                            <option value="">---Chọn---</option>
                            <option id="plan_room" value="1" <?php if($selectOption==1)echo "selected"  ?>>[[.plan.]] [[.room.]]</option>
                            <option id="plan_bar" value="2" <?php if($selectOption==2)echo "selected"  ?> >[[.plan.]] [[.bar.]]</option>
                            <option id="plan_total" value="3" <?php if($selectOption==3)echo "selected"  ?>>[[.plan.]] [[.total.]]</option>  
                        </select>
                    </td>
				</tr>
		</table>
    </fieldset>
</div>
<br />
<br />
<table cellspacing="0">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr>
        <td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
		<table border="0">
    		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
    	<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
    			<div style="background-color:#EFEFEF;">
    				<span id="mi_plan_all_elems">
    					<span style="white-space:nowrap; width:100%;">
    						<span class="multi-input-header" style="float:left;width:30px;text-align:left;padding:0px;">
                                <input tyle="float:left;width:30px;text-align:left;padding:0px;" type="checkbox" value="1" onclick="mi_select_all_row('mi_plan',this.checked);"/>
                            </span>
    						<span class="multi-input-header" style="float:left;width:136px;">[[.plan.]] [[.room.]]</span>
                            <span class="multi-input-header" style="float:left;width:76px;">[[.room_type.]]</span>
                            <span class="multi-input-header" style="float:left;width:151px;">[[.plan.]] [[.bar.]]</span>
                            <span class="multi-input-header" style="float:left;width:150px;">[[.plan.]] [[.bar.]] [[.service.]]</span>
                            <span class="multi-input-header" style="float:left;width:186px;">[[.plan.]][[.total.]]</span>
                            <span class="multi-input-header" style="float:left;width:77px;">[[.month.]]</span>
                            <span class="multi-input-header" style="float:left;width:57px;">[[.plan.]]</span>
                            <span class="multi-input-header" style="float:left;width:108px;">[[.type.]]</span>
                            
    						<span class="multi-input-header" style="float:left;width:50px;text-align:center;">[[.Delete.]]</span>
    						<br clear="all"/>
    					</span>
    				</span>
    			</div>
    			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_plan');jQuery('#value_'+input_count).FormatNumber();jQuery('#price_'+input_count).ForceNumericOnly();jQuery('#position_'+input_count).ForceNumericOnly();" class="button-medium-add">[[.Add.]]</a></div>
            </td>
        </tr>
        </table>
        </td>
    </tr>
</table>
</form>
<script>

mi_init_rows('mi_plan',<?php echo isset($_REQUEST['mi_plan'])?String::array2js($_REQUEST['mi_plan']):'{}';?>);
function fill_name(value,index)
{
	if(typeof(name_arr[value])=='object')
    {
		$('name_1_'+index).value = name_arr[value]['name_1'];
        $('name_2_'+index).value = name_arr[value]['name_2'];
	}
    else
    {
        $('name_1_'+index).value = '';
        $('name_2_'+index).value = '';
    }
}

function Confirm(index)
{
	var mi_plan_name = $('name_2_'+index).value;
	return confirm('[[.Are_you_sure_delete_mi_plan_name.]] '+ mi_plan_name+'?');
}

var DeleteMessage = '[[.Delete_mi_plan_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_plan_that_you_choose_never_in_used.]]';
function ConfirmDelete()
{
	var checkok =false;
	for(var i=101;i<=input_count;i++)
    {	
        if($('_checked_'+i) && $('_checked_'+i).checked == true)
        {
            checkok =true;
            break;
        }
	}
    
	if(checkok ==false)
    {
		var messa='[[.no_items_selected.]]';
		return alert(messa);
	}
    else
    {
        return confirm(DeleteMessage);
    }
}

</script>