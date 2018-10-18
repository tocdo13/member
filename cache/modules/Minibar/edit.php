<span style="display:none">
	<span id="mi_minibar_sample">
        <div id="input_group_#xxxx#">
            <span class="multi-edit-input_header">
				<span><input  type="checkbox" id="_checked_#xxxx#"/></span>
			</span>
			<span class="multi-edit-input">
				<select  name="mi_minibar[#xxxx#][room_id]" style="width:150px;" class="multi-edit-text-input"  id="room_id_#xxxx#" tabindex="2">
                    <option value=""></option>
					<?php echo $this->map['room_id_options'];?>
				</select>
			</span>
            <span class="multi-edit-input">
                <input  name="mi_minibar[#xxxx#][id]" readonly="true" style="width:150px;" class="multi-edit-text-input" type="text" id="id_#xxxx#"  tabindex="2"/>
			</span>
            <span class="multi-edit-input">
				<input  name="mi_minibar[#xxxx#][name]" style="width:150px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"  tabindex="2"/>
			</span>
			<?php 
				if((User::can_edit(false,ANY_CATEGORY)))
				{?>
			<span class="multi-edit-input">
				<input type="hidden" name="mi_minibar[#xxxx#][status]" id="status_#xxxx#" value="AVAIABLE" />
				<img id="img_change_status_#xxxx#" style="width:18px" src="packages/core/skins/default/images/buttons/update_button.png" onclick="hide_row('#xxxx#',this);event.returnValue=false;" />
			</span>
			
				<?php
				}
				?>
		</div>
	</span>
	<br clear="all"/>
</span>

<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('manage_minibar')); ?>
<form name="EditMinibarForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('manage_minibar');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%"><input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('mi_minibar');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none"><?php echo Portal::language('Delete');?></a></td><?php }?>
	</tr>
</table>

<table cellspacing="0">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr>
        <td>
    		<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>"/>
    		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    		
            <table border="0">
        		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
        		<tr bgcolor="#EEEEEE" valign="top">
        			<td style=" text-align:center;">
            			<div style="background-color:#EFEFEF;">
            				<span id="mi_minibar_all_elems">
            					<span style="white-space:nowrap; width:auto;float:left;">
            						<span class="multi-edit-input_header">
                                        <span class="table-title" style="float:left;width:25px;text-align:left;padding:0px;">
                                            <input type="checkbox" value="1" onclick="mi_select_all_row('mi_minibar',this.checked);"/>
                                        </span>
                                    </span>
            						<span class="multi-edit-input_header">
                                        <span class="table-title" style="float:left;width:155px;text-align:left;padding-left:2px;"><?php echo Portal::language('room_id');?></span>
                                    </span>
            						<span class="multi-edit-input_header">
                                        <span class="table-title" style="float:left;width:155px;text-align:left;padding-left:2px;"><?php echo Portal::language('code');?></span>
                                    </span>
            						<span class="multi-edit-input_header">
                                        <span class="table-title" style="float:left;width:155px;text-align:left;padding-left:2px;"><?php echo Portal::language('name');?></span>
                                    </span>
            					</span>
            				</span>
               				<br clear="all"/>
            			</div>		
            			<?php echo $this->map['paging'];?>
        			</td>
        		</tr>
    		</table>	
    	</td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="application/javascript">
<?php 	if(isset($_REQUEST['mi_minibar']))
		{
			echo 'var minibars = '.String::array2js($_REQUEST['mi_minibar']).';';
		}else{
			echo 'var minibars = [];';
		}
?>

function warning(obj)
{
	if(jQuery('#id_'+jQuery(obj).attr('index')).val() && window.minibars[jQuery('#id_'+jQuery(obj).attr('index')).val()])
	{
		var obj = window.minibars[jQuery('#id_'+jQuery(obj).attr('index')).val()];
		if(obj['no_delete']=='true')
		{
			alert('<?php echo Portal::language('you_can_delete_this_minibar_because_it_cause_damaged_for_system');?>');
			return false;
			//return confirm('<?php echo Portal::language('warning_delete_minibar');?>');
		}
	}
	return true;
}
mi_init_rows('mi_minibar',minibars);
function hide_row(index,obj)
{
	if($('status_'+index).value=='NO_USE')
	{
		$('status_'+index).value = 'avaiable';
		obj.src = 'packages/core/skins/default/images/buttons/update_button.png';
		obj.title = '<?php echo Portal::language('in_use');?>';
	}else
	{
		$('status_'+index).value='NO_USE';
		obj.src = 'packages/cms/skins/default/images/admin/404/icon.png';
		obj.title = '<?php echo Portal::language('no_use');?>';
	}
}
function Confirm(index)
{
	var product = $('product_id_'+index).value;
	return confirm('<?php echo Portal::language('Are_you_sure_delete_minibar');?> '+product+'?');
}

var DeleteMessage = '<?php echo Portal::language('Delete_minibar_which_is_used_cause_error_for_report_and_statistic');?>';
DeleteMessage += '<?php echo Portal::language('You_should_delete_when_you_sure_minibars_that_you_choose_never_in_used');?>';
DeleteMessage += '<?php echo Portal::language('You_can_change_status_');?>';
function ConfirmDelete()
{
	return confirm(DeleteMessage);
}
</script>