<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_shift_sample">
		<div id="input_group_#xxxx#">
            <input  name="mi_shift[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input">
                <input  name="mi_shift[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
            </span>
			<span class="multi-input">
                <input  name="mi_shift[#xxxx#][name]" style="width:100px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/>
            </span>
            <span class="multi-input">
                <input  name="mi_shift[#xxxx#][start_time]" style="width:100px;" class="multi-edit-text-input" type="text" id="start_time_#xxxx#" onblur="validate_time(this,this.value)"/>
            </span>
            <span class="multi-input">
                <input  name="mi_shift[#xxxx#][end_time]" style="width:100px;" class="multi-edit-text-input" type="text" id="end_time_#xxxx#" onblur="validate_time(this,this.value)"/>
            </span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:50px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_shift','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/></span>
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>

<script>
function validate_time(obj,value)
{
    if(value != "__:__")
    {
        var arr = value.split(":")
        var h = arr[0];
        var m = arr[1];
        if(is_numeric(h.toString()))
        {
            if(h>23)
            {
                alert('[[.invalid_time.]]');
                jQuery(obj).val('');
                return false;    
            }
            
        }
        if(is_numeric(m.toString()))
        {
            if(m>59)
            {
                alert('[[.invalid_time.]]');
                jQuery(obj).val('');
                return false;    
            }
            
        }
               
    }
}
</script>
<form name="EditShiftForm" method="post" >

<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="100%" class="form-title">[[.manage_shift.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"/></td><?php }?>
        <td><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a></td>
    </tr>
</table>

<fieldset>
	<legend class="title">[[.select.]]</legend>
    <span>[[.bar.]]:</span> 
	<select name="portal_id" id="portal_id" onchange="EditShiftForm.submit();"></select>
</fieldset>

<br />
<br />
<table cellspacing="0">
	<tr>
        <td style="padding-bottom:30px">
    		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    		<table border="0">
    		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
    		<tr bgcolor="#EEEEEE" valign="top">
    			<td>
        			<div style="background-color:#EFEFEF;">
        				<span id="mi_shift_all_elems">
        					<span style="white-space:nowrap; width:auto;">
        						<span class="multi-input-header" style="float:left;width:30px;">[[.order_number.]]</span>
        						<span class="multi-input-header" style="float:left;width:100px;">[[.name.]]</span>
        						<span class="multi-input-header" style="float:left;width:100px;">[[.start_time.]]</span>
                                <span class="multi-input-header" style="float:left;width:100px;">[[.end_time.]]</span>
        						<span class="multi-input-header" style="float:left;width:50px;text-align:center;">[[.Delete.]]</span>
        						<br clear="all"/>
        					</span>
        				</span>
        			</div>
        			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_shift');$('name_'+input_count).focus();create_mask(input_count);" class="button-medium-add">[[.Add.]]</a></div>
                </td>
            </tr>
            </table>
        </td>
    </tr>
</table>
</form>
<script>
mi_init_rows('mi_shift',<?php echo isset($_REQUEST['mi_shift'])?String::array2js($_REQUEST['mi_shift']):'{}';?>);
for(var i=101; i<=input_count; i++)
{
    create_mask(i);
}

function create_mask(id)
{
    jQuery('#start_time_'+id).mask("99:99")
    jQuery('#end_time_'+id).mask("99:99")		
}

function Confirm(index)
{
	var mi_shift_name = $('name_'+ index).value;
	return confirm('[[.Are_you_sure_delete_mi_shift_name.]] '+ mi_shift_name+'?');
}
var DeleteMessage = '[[.Delete_mi_shift_which_is_used_cause_error_for_report_and_statistic.]]\n';
DeleteMessage += '[[.You_should_delete_when_you_sure_mi_shift_that_you_choose_never_in_used.]]';

function ConfirmDelete()
{
	var checkok =false;
	for(var i=101;i<=input_count;i++)
    {
        jQuery("#start_time_"+i).mask("99/99/9999");
        jQuery("#start_time_"+i).datepicker();
        
        if($('_checked_'+i) && $('_checked_'+i).checked == true)
        {
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