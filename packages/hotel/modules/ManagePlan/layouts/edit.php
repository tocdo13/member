<script type="text/javascript">
	var name_arr = <?php echo [[=name_list_to_js=]];?>;
</script>
<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_plan_sample">
		<div id="input_group_#xxxx#">
            <input  name="mi_plan[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			
            <span class="multi-input" style="width:30px;border:1px solid #CCC;">
                <input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/>
            </span>
			<span class="multi-input">
                <select  name="mi_plan[#xxxx#][code]" style="width:155px;" id="code_#xxxx#" onchange="fill_name(this.value,#xxxx#);">[[|code_list|]]</select>
            </span>
			<span class="multi-input">
                <input  name="mi_plan[#xxxx#][name_1]" style="width:150px;background-color: #CCC;" type="text" id="name_1_#xxxx#" readonly="readonly"/>
            </span>
            <span class="multi-input">
                <input  name="mi_plan[#xxxx#][name_2]" style="width:150px;background-color: #CCC;" type="text" id="name_2_#xxxx#" readonly="readonly"/>
            </span>
            <span class="multi-input">
                <input  name="mi_plan[#xxxx#][value]" style="width:100px;text-align: right;" type="text" id="value_#xxxx#"/>
            </span>
            <span class="multi-input">
                <select  name="mi_plan[#xxxx#][currency_id]" style="width:120px;" id="currency_id_#xxxx#">[[|currency_id_list|]]</select>
            </span>
            <span class="multi-input">
                <input  name="mi_plan[#xxxx#][position]" style="width:50px;text-align: right;" type="text" id="position_#xxxx#" class="input_number"/>
            </span>      
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px; text-align:center;">
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
		<td width="100%" class="form-title" style="text-indent: 50px;">[[.manage_plan.]]</td>
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
    					<span style="white-space:nowrap; width:auto;">
    						<span class="multi-input-header" style="float:left;width:30px;text-align:left;padding:0px;">
                                <input type="checkbox" value="1" onclick="mi_select_all_row('mi_plan',this.checked);"/>
                            </span>
    						<span class="multi-input-header" style="float:left;width:150px;">[[.code.]]</span>
    						<span class="multi-input-header" style="float:left;width:150px;">[[.name.]](VN)</span>
                            <span class="multi-input-header" style="float:left;width:150px;">[[.name.]](ENG)</span>
                            <span class="multi-input-header" style="float:left;width:100px;">[[.plan.]]</span>
                            <span class="multi-input-header" style="float:left;width:120px;">[[.type.]]</span>
                            <span class="multi-input-header" style="float:left;width:50px;">[[.position.]]</span>
    						<span class="multi-input-header" style="float:left;width:70px;text-align:center;">[[.Delete.]]</span>
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