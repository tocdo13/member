<span style="display:none">
    <span id="status_traveller_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input" style="width: 16px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="status_traveller[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#" class="w3-input w3-border" tabindex="-1" style="width:35px;background:#EFEFEF;"/></span>
            <span class="multi-input">
					<input  name="status_traveller[#xxxx#][name]" style="width:155px;font-weight:bold;color:#06F;" class="w3-input w3-border" type="text" id="name_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="status_traveller[#xxxx#][note]" style="width:155px;font-weight:bold;color:#06F;" class="w3-input w3-border" type="text" id="note_#xxxx#"/>
			</span>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:70px;text-align:center">
				<img id="delete_item_#xxxx#" tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'status_traveller','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			
				<?php
				}
				?>
		</div><br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="Editstatus_travellerForm" method="post" >
<table cellpadding="10" cellspacing="10">
	<tr height="40">
		<td class="form-title"><?php echo Portal::language('status_traveller_project');?></td>
		<td><input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-button w3-amber"></td>
		<td><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('status_traveller');" class="w3-button w3-pink"><?php echo Portal::language('Delete');?></a></td>
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
				<span id="status_traveller_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px; height: 40px; line-height: 40px; text-align: center;"><input type="checkbox" value="1" onclick="mi_select_all_row('status_traveller',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('ID');?></span>
						<span class="multi-input-header" style="width:155px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('status_traveller_name');?></span>
                        <span class="multi-input-header" style="width:155px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('note');?></span>
						<span class="multi-input-header" style="width:70px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('Delete');?></span>
					</span>
                    <br clear="all">
				</span>
			</div>
            <br clear="all">
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('status_traveller');$('name_'+input_count).focus();" class="w3-button w3-blue"><?php echo Portal::language('Add');?></a></div>
</td></tr></table></td></tr></table>
</div></div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script>
<?php 	if(isset($_REQUEST['status_traveller'])){
			echo 'var star = '.String::array2js($_REQUEST['status_traveller']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('status_traveller',star);
function Confirm(index)
{
    var status_traveller_name = $('name_'+index).value;
    return confirm('<?php echo Portal::language('Are_you_sure_delete_status_traveller');?>'+ status_traveller_name+'?');
}

function ConfirmDelete()
{
    return confirm('<?php echo Portal::language('Are_you_sure_delete_status_traveller_selected');?>');
}
jQuery("#delete_item_101").css('display','none');
<?php if(Url::get('event_delete')){ ?>
alert('Lưu ý: Những trạng Thái đã được áp dụng cho khách, không được xóa!');
<?php } ?>
</script>