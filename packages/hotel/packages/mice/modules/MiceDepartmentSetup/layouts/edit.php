<span style="display:none">
    <span id="mice_department_setup_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1" style="height: 24px;"/></span>
			<span class="multi-input"><input  name="mice_department_setup[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;text-align: center; height: 24px;background:#EFEFEF;"/></span>
            <span class="multi-input"><input  name="mice_department_setup[#xxxx#][name]" style="width:155px;height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <span class="multi-input"><input  name="mice_department_setup[#xxxx#][position]" style="width:50px;height: 24px;text-align: center;" class="multi-edit-text-input" type="text" id="position_#xxxx#"/></span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:30px;text-align:center">
				<a href="#" tabindex="-1" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mice_department_setup','#xxxx#',''); }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a>
			</span>
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="MiceDepartmentSetupForm" method="post" >
    <table width="100%" cellpadding="5" cellspacing="5">
    	<tr style="width: 100%;">
    		<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 5px; width: 60%;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 24px;"></i> [[.mice_department_setup_project.]]</td>
    		<td style="width: 40%; text-align: right; padding-right: 30px;"><input id="btnsave" type="button" onclick="checksubmit();" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
    		<a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mice_department_setup');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td>
    	</tr>
    </table>
    <div class="body">
    <table cellspacing="5">
    	<tr>
            <td style="padding-bottom:30px">
        		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>"/>
        		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
        		<table>
                    <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
                    <tr>
            			<td style="">
                			<div>
                				<span id="mice_department_setup_all_elems">
                					<span style="text-transform: uppercase; text-align: center; height: 24px;">
                						<span class="multi-input-header" style="width:13px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mice_department_setup',this.checked);">
                						</span>
                						<span class="multi-input-header" style="width:35px;">[[.ID.]]</span>
                						<span class="multi-input-header" style="width:155px;">[[.name.]]</span>
                                        <span class="multi-input-header" style="width:50px;">[[.position.]]</span>
                						<span class="multi-input-header" style="width:30px;">[[.Delete.]]</span>
                					</span>
                                    <br clear="all"/>
                				</span>
                			</div>
                            <div>
                                <a href="javascript:void(0);" onclick="mi_add_new_row('mice_department_setup');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-left: 5px; margin-top: 5px;">[[.Add.]]</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </div>
</form>

<script>
<?php 	if(isset($_REQUEST['mice_department_setup'])){
			echo 'var star = '.String::array2js($_REQUEST['mice_department_setup']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('mice_department_setup',star);
function Confirm(index)
{
    var mice_department_setup_name = $('name_'+index).value;
    return confirm('[[.Are_you_sure_delete_mice_department_setup.]]'+ mice_department_setup_name+'?');
}
function checksubmit(){
    //console.log('11111111');
    jQuery('#btnsave').css('display','none');
    MiceDepartmentSetupForm.submit();
}
function ConfirmDelete()
{
    return confirm('[[.Are_you_sure_delete_mice_department_setup_selected.]]');
}
</script>