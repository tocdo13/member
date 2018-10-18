<span style="display:none">
    <span id="group_traveller_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input" style="width: 16px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="group_traveller[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;" class="w3-input w3-border"/></span>
            <span class="multi-input">
					<input  name="group_traveller[#xxxx#][name]" style="width:155px;font-weight:bold;color:#06F;" class="w3-input w3-border" type="text" id="name_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="group_traveller[#xxxx#][note]" style="width:155px;font-weight:bold;color:#06F;" class="w3-input w3-border" type="text" id="note_#xxxx#"/>
			</span>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" id="is_delete_#xxxx#" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'group_traveller','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			
				<?php
				}
				?>
		</div><br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="Editgroup_travellerForm" method="post" >
<table cellpadding="10" cellspacing="10">
	<tr height="40">
		<td class="form-title"><?php echo Portal::language('group_traveller_project');?></td>
		<td><input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-button w3-amber" /></td>
		<td><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('group_traveller');" class="w3-button w3-pink"><?php echo Portal::language('Delete');?></a></td>
	</tr>
</table>
<div class="global-tab">
<div class="header">
</div>
<div class="body">
<table cellspacing="0" width="100%">
	<tr valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div>
				<span id="group_traveller_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px; height: 45px; line-height: 45px; text-align: center;"><input type="checkbox" value="1" onclick="mi_select_all_row('group_traveller',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px; height: 45px; line-height: 45px; text-align: center;"><?php echo Portal::language('ID');?></span>
						<span class="multi-input-header" style="width:155px; height: 45px; line-height: 45px; text-align: center;"><?php echo Portal::language('name');?></span>
                        <span class="multi-input-header" style="width:155px; height: 45px; line-height: 45px; text-align: center;"><?php echo Portal::language('note');?></span>
						<span class="multi-input-header" style="width:70px; height: 45px; line-height: 45px; text-align: center;"><?php echo Portal::language('Delete');?></span>
					</span>
                    <br clear="all">
				</span>
			</div>
            <br clear="all">
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('group_traveller');$('name_'+input_count).focus();" class="w3-button w3-blue"><?php echo Portal::language('Add');?></a></div>
</td></tr></table></td></tr></table>
</div></div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script>
<?php 	if(isset($_REQUEST['group_traveller'])){
			echo 'var star = '.String::array2js($_REQUEST['group_traveller']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('group_traveller',star);
for(var i=101;i<=input_count;i++)
{
    if(jQuery("#id_"+i).val()!=undefined && jQuery("#id_"+i).val()!='')
    {
        if(star[jQuery("#id_"+i).val()]!=undefined && to_numeric(star[jQuery("#id_"+i).val()]['is_delete'])==0)
        {
            jQuery("#is_delete_"+i).css('display','none');
            jQuery("#_checked_"+i).attr('disabled','disabled');
        }
    }
}
function Confirm(index)
{
    var group_traveller_name = $('name_'+index).value;
    return confirm('<?php echo Portal::language('Are_you_sure_delete_group_traveller');?>'+ group_traveller_name+'?');
}

function ConfirmDelete()
{
    return confirm('<?php echo Portal::language('Are_you_sure_delete_group_traveller_selected');?>');
}
</script>