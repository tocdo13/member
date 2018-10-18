<span style="display:none">
    <span id="mice_location_setup_sample">
		<div style="" id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1" style="height: 24px;"/></span>
			<span class="multi-input"><input  name="mice_location_setup[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px; text-align: center; background:#EFEFEF; height: 24px;"/></span>
            <span class="multi-input"><input  name="mice_location_setup[#xxxx#][name]" style="width:155px; height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>
            <span class="multi-input"><input  name="mice_location_setup[#xxxx#][position]" style="width:50px; text-align: center; height: 24px;" class="multi-edit-text-input" type="text" id="position_#xxxx#"/></span>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:30px;text-align:center; " >
				<a id="delete_#xxxx#" href="javascript:void(0);" tabindex="-1" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mice_location_setup','#xxxx#',''); }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a>
			</span>
			
				<?php
				}
				?>
            <input type="hidden" id="check_delete_#xxxx#"/>
		</div>
        <br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="MiceDepartmentSetupForm" method="post" >
    <table width="100%" cellpadding="5" cellspacing="5">
    	<tr style="width: 100%;">
    		<td class="" style="text-transform: uppercase; font-size: 18px; width: 60%;" ><i class="fa fa-plus-square w3-text-orange" style="font-size: 24px;"></i> <?php echo Portal::language('mice_location_setup_project');?></td>
    		<td style="width: 40%; text-align: right; padding-right: 30px;"><input id="btnsave" type="submit" value="<?php echo Portal::language('Save_and_close');?>" onclick="checksubmit();" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
    		<a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mice_location_setup');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a></td>
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
                    <tr class="w3-light-gray" style="height: 24px;">
            			<td >
                			<div>
                				<span id="mice_location_setup_all_elems">
                					<span style="height: 24px; text-transform: uppercase;">
                						<span class="multi-input-header" style="width:13px;"><input type="checkbox" value="1" onclick="mi_select_all_row('mice_location_setup',this.checked);">
                						</span>
                						<span class="multi-input-header" style="width:35px; text-align: center;"><?php echo Portal::language('ID');?></span>
                						<span class="multi-input-header" style="width:155px; text-align: center;"><?php echo Portal::language('name');?></span>
                                        <span class="multi-input-header" style="width:50px; text-align: center;"><?php echo Portal::language('position');?></span>
                						<span class="multi-input-header" style="width:30px; text-align: center;"><?php echo Portal::language('Delete');?></span>
                					</span>
                                    <br clear="all"/>
                				</span>
                			</div>
                            <div>
                                <a href="javascript:void(0);" onclick="mi_add_new_row('mice_location_setup');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-left: 10px; margin-top: 5px;"><?php echo Portal::language('Add');?></a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script>
<?php 	if(isset($_REQUEST['mice_location_setup'])){
			echo 'var star = '.String::array2js($_REQUEST['mice_location_setup']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('mice_location_setup',star);
for(var i=101;i<=input_count;i++){
    if(jQuery("#id_"+i) && jQuery("#id_"+i).val()!=''){
        if(to_numeric(jQuery("#check_delete_"+i).val())==1){
            jQuery("#delete_"+i).css('display','none');
        }
    }
}
function Confirm(index)
{
    var mice_location_setup_name = $('name_'+index).value;
    return confirm('<?php echo Portal::language('Are_you_sure_delete_mice_location_setup');?>'+ mice_location_setup_name+'?');
}
function checksubmit(){
    jQuery('#btnsave').css('display','none');
    MiceDepartmentSetupForm.submit();
}
function ConfirmDelete()
{
    return confirm('<?php echo Portal::language('Are_you_sure_delete_mice_location_setup_selected');?>');
}
</script>