<span style="display:none">
	<span id="ticket_card_sales_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="ticket_card_sales[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;"></span>
			<span class="multi-input">
					<input  name="ticket_card_sales[#xxxx#][code]" style="width:120px;font-weight:bold;color:#06F;" class="multi-edit-text-input readonly" type="text" id="code_#xxxx#" readonly="">
			</span>
            <span class="multi-input">
                <input  name="ticket_card_sales[#xxxx#][name]" style="width:250px;" class="multi-edit-text-input" type="text" id="name_#xxxx#">
            </span>
			<span class="multi-input"><input style="width:70px;" name="ticket_card_sales[#xxxx#][hide]" type="checkbox" id="hide_#xxxx#" value="1"/></span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'ticket_card_sales','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/>
			</span>
            <span class="multi-input"><input name="ticket_card_area[#xxxx#][can_delete]" type="hidden" id="can_delete_#xxxx#"/></span>
			<!--/IF:delete-->
		</div><br clear="all" />
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditMinibarForm" method="post" onsubmit="return checkForm();" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="90%" class="form-title">[[.manage_ticket_card_sales.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('ticket_card_sales');" class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
	</tr>
</table>
<div class="global-tab">
<div class="header">
<!--LIST:portals-->
	<a <?php echo Url::get('portal_id')==[[=portals.id=]]?'class="selected"':''?> href="<?php echo Url::build_current(array('portal_id'=>[[=portals.id=]]));?>">[[|portals.name|]]</a>
<!--/LIST:portals-->
</div>
<div class="body">
<table cellspacing="0" width="100%">
	<tr valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden"/>
		<input  name="deleted_ids" id="deleted_ids" type="hidden"/>
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div>
				<span id="ticket_card_sales_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px;"><input type="checkbox" value="1" onclick="mi_select_all_row('ticket_card_area',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px;">[[.ID.]]</span>
						<span class="multi-input-header" style="width:120px;">[[.code.]]</span>
						<span class="multi-input-header" style="width:250px;">[[.sales_name.]]</span>
						<span class="multi-input-header" style="width:70px;">[[.hide.]]</span>
						<span class="multi-input-header" style="width:70px;">[[.Delete.]]</span>
					</span>
                    <br clear="all">
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('ticket_card_sales');$('code_'+input_count).focus(); remove_readonly();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table>
</div></div>
</form>
<script>
<?php 	if(isset($_REQUEST['ticket_card_sales'])){
			echo 'var ticket_card_sales = '.String::array2js($_REQUEST['ticket_card_sales']).';';
		}else{
			echo 'var ticket_card_sales = [];';
		}
?>
mi_init_rows('ticket_card_sales',ticket_card_sales);
function Confirm(index){
	var room_name = $('name_'+index).value;
    var can_delete = $('can_delete_'+index).value;
    if(can_delete==1){
	return confirm('[[.Are_you_sure_delete_area.]] '+room_name+'?');
    }
    else{
        alert("[[.This_wicket_has_been_used_to_sell_tickets.]]");
        return false;
    }
}
    var DeleteMessage = '[[.Delete_room_which_is_used_cause_error_for_report_and_statistic.]]';
    DeleteMessage += '[[.You_should_delete_when_you_sure_room_that_you_choose_never_in_used.]]';
    function ConfirmDelete(){
    	var check_delete = true;
        jQuery("input[id^=_checked_]:checked").each(function(){
            var can_delete = jQuery(this).parent().parent().find("input[id^=can_delete_").val();
            if(can_delete==0)
            {
                check_delete = false;
            }
        });
        if(check_delete)
        {
          return confirm(DeleteMessage);  
        }
    	else{
    	   alert("[[.This_list_have_contain_one_or_more_wicket_has_been_used_to_sell_tickets.]]");
           return false;
    	}
    }
    
    function checkForm()
    {
        var checkCode = true;
        var checkName = true;
        var i = 1;
        var j = 1;
        jQuery("input[id^=code]").each(function(){
            if(i != 1){
            var current_code = jQuery(this).val().trim();
            if(current_code==""){
                alert("Mã không được để trống!");
                jQuery(this).focus();
                checkCode = false;
                return false;
            }
            if(checkCode == false)
            {
                return false;
            }
            jQuery("input[id^=code]").not(this).each(function(){
                if(current_code==jQuery(this).val().trim()){
                    alert("Mã không được trùng!");
                    jQuery(this).focus();
                    checkCode = false;
                    return false;
                }
            });
            if(checkCode == false)
            {
                return false;
            }
           }
           else{
            i++;
           } 
        });
        if(checkCode == false) return false;
        jQuery("input[id^=name]").each(function(){
            if(j != 1){ 
                var current_name = jQuery(this).val().trim();
                if(current_name==""){
                    alert("Tên không được để trống!");
                    jQuery(this).focus();
                    return false;
                }
                jQuery("input[id^=name]").not(this).each(function(){
                    if(current_name==jQuery(this).val().trim()){
                        alert("Tên không được trùng!");
                        jQuery(this).focus();
                        checkName =false;
                        return false;
                    }
                });
                if(checkName == false)
                {
                    return false;
                }
            }
            else{
                j++;
            }
        });
        if(checkName == false) return false;
        //return false;
    }
    function remove_readonly()
    {
        jQuery("input[id^=code_]").each(function(){
            if(jQuery(this).val()=="")
            {
                jQuery(this).removeAttr("readonly");
                jQuery(this).removeClass("readonly");
            }
        });
    }
</script>