<span style="display:none">
    <span id="bank_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="bank[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;"></span>
            <span class="multi-input">
					<input  name="bank[#xxxx#][name]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="name_#xxxx#">
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'bank','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div><br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditBankForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.bank_project.]]</td>
		<td width="30%"><input type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
		<a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('bank');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td>
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
				<span id="bank_all_elems">
					<span>
						<span class="multi-input-header" style="width:13px;"><input type="checkbox" value="1" onclick="mi_select_all_row('bank',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px;">[[.ID.]]</span>
						<span class="multi-input-header" style="width:155px;">[[.bank_name.]]</span>
						<span class="multi-input-header" style="width:70px; text-align: center;">[[.Delete.]]</span>
					</span>
                    <br clear="all">
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('bank');$('name_'+input_count).focus();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;">[[.Add.]]</a></div>
</td></tr></table></td></tr></table>
</div></div>
</form>

<script>
<?php 	if(isset($_REQUEST['bank'])){
			echo 'var star = '.String::array2js($_REQUEST['bank']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('bank',star);
function Confirm(index)
{
    var bank_name = $('name_'+index).value;
    return confirm('[[.Are_you_sure_delete_bank.]]'+ bank_name+'?');
}

function ConfirmDelete()
{
    return confirm('[[.Are_you_sure_delete_bank_selected.]]');
}
</script>