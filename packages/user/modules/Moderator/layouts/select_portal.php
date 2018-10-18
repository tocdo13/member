<script>
var data = <?php echo String::array2autosuggest([[=users=]]);?>;	
jQuery(document).ready(function(){
	jQuery("#account_id").autocomplete(data,{
		minChars: 0,
		width: 305,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return '<span style="color:#993300"> ' + row.name + '</span>';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}
	});
});
function select_all_module(name)
{
	var check = $(name).checked;
	jQuery('form .column_'+name).attr('checked',check);
}
function select_all_column(name)
{
	var checked = jQuery('#select_all_'+name+'_').attr('checked');
	jQuery('form .row_'+name).attr('checked',checked);
}
function select_all_child(name,action)
{
	var checked = jQuery('.parent_'+action+'_'+name).attr('checked');
	jQuery('form .child_'+action+'_'+name).attr('checked',checked);
}
</script>
<fieldset id="toolbar" style="margin-top:2px;width:97%">
<a name="top_anchor"></a>
<div id="bound_content"></div>
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>	
<form name="GrantModeratorForm" method="post">
<table cellpadding="4" cellspacing="0" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#E7E7E7" align="center">
	<tr bgcolor="#EFEFEF" valign="top">		
        <th width="90%" align="left"><a>[[.account_id.]]</a></th>
        <th width="1%" align="right"><input name="save" type="submit"  value="[[.save.]]" class="button-medium-save"></th>
        <th width="1%"><input type="button"  value="[[.user_list.]]" onclick="window.location='<?php echo Url::build('manage_user');?>'" class="button-medium-back"></th>
    </tr>
</table>
<table cellpadding="4" cellspacing="0" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#E7E7E7" align="center">
  <tr>
    <td width="17%" valign="top">
    	<input name="account_id" type="text" id="account_id" size="40">
        <!--IF:cond(Url::get('cmd')=='edit' && Url::get('account_id'))--><script>jQuery('#account_id').attr('readonly',true);</script><!--/IF:cond-->
        <!--IF:cond(Url::get('cmd')=='edit' && Url::get('account_id'))--><script>jQuery('#account_id').attr('readonly',true);</script>
		<!--/IF:cond-->
		[[.portal_name.]] 
		<select name="portal_id" id="portal_id" onchange="GrantModeratorForm.submit();"></select>
       </td>
    </tr>
	 <tr style="padding:10px">
    <td width="17%" valign="top">
		<table cellpadding="3" cellspacing="2" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#C7C7C7" align="center">
		<tr bgcolor="#EFEFEF">
		  <th width="1%" rowspan="2"><a>[[.No.]]</a></th>
		  <th width="51" rowspan="2" align="left"><a>[[.category_name.]]</a></th>
		  <th colspan="10" align="center"><a style="text-transform:uppercase">[[.grant_privilege.]]</a></th>
		  </tr>
		<tr bgcolor="#EFEFEF" align="center">
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('show');">[[.run.]]</a><br /><input name="select_all" type="checkbox" id="show" onclick="select_all_module('show');"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('view');">[[.view.]]</a><br /><input name="select_all" type="checkbox" id="view" onclick="select_all_module('view');"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('add');">[[.add.]]</a><br /><input name="select_all" type="checkbox" id="add" onclick="select_all_module('add');"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('edit');">[[.edit.]]</a><br /><input name="select_all" type="checkbox" id="edit" onclick="select_all_module('edit');"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('delete');">[[.delete.]]</a><br /><input name="select_all" type="checkbox" id="delete" onclick="select_all_module('delete');"></th>
			<th width="3%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('admin');">[[.admin.]]</a><br /><input name="select_all" type="checkbox" id="admin" onclick="select_all_module('admin');"></th>
			<th width="3%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('select_all');">[[.select_all.]]</a><br /><input name="select_all" type="checkbox" id="select_all" onclick="select_all_module('select_all');"></th>
		</tr>
		  <?php $i=1;?>
		  <!--LIST:items-->
		  <tr  style="cursor:hand;<?php if(!($i%2)){echo 'background-color:'.'#EFEFEF';}?>"  <?php Draw::hover('#EFEFEF');?>>	
		  	<td align="center"><?php echo $i++;?><input name="module_[[|items.id|]]_" type="hidden" value="[[|items.module_id|]]"></td>
			<td align="left">[[|items.indent|]][[|items.indent_image|]] [[|items.name|]] ([[|items.id|]])
    			<?php 
                if(isset($_REQUEST['privilege_id_'.[[=items.id=]].'_']))
                { 
                ?>
    			 <input name="privilege_id_[[|items.id|]]_"  value="<?php echo Url::get('privilege_id_'.[[=items.id=]].'_');?>" type="hidden"/>
    			<?php 
                }?>
            </td>
			<td align="center"><input name="show_[[|items.id|]]_" <?php if(Url::get('show_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="show_[[|items.id|]]_" class="column_show row_[[|items.id|]] child_show_[[|items.parent|]] <?php if([[=items.have_child=]]){?>parent_show_[[|items.id|]]<?php }?>" <?php if([[=items.have_child=]]){?>onclick="select_all_child([[|items.id|]],'show')"<?php }?>></td>
			<td align="center"><input name="view_[[|items.id|]]_" <?php if(Url::get('view_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="view_[[|items.id|]]_" class="column_view row_[[|items.id|]] child_view_[[|items.parent|]] <?php if([[=items.have_child=]]){?>parent_view_[[|items.id|]]<?php }?>" <?php if([[=items.have_child=]]){?>onclick="select_all_child([[|items.id|]],'view')"<?php }?>></td>
			<td align="center"><input name="add_[[|items.id|]]_" <?php if(Url::get('add_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="add_[[|items.id|]]_" class="column_add row_[[|items.id|]] child_add_[[|items.parent|]] <?php if([[=items.have_child=]]){?>parent_add_[[|items.id|]]<?php }?>" <?php if([[=items.have_child=]]){?>onclick="select_all_child([[|items.id|]],'add')"<?php }?>></td>
			<td align="center"><input name="edit_[[|items.id|]]_" <?php if(Url::get('edit_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="edit_[[|items.id|]]_" class="column_edit row_[[|items.id|]] child_edit_[[|items.parent|]] <?php if([[=items.have_child=]]){?>parent_edit_[[|items.id|]]<?php }?>" <?php if([[=items.have_child=]]){?>onclick="select_all_child([[|items.id|]],'edit')"<?php }?>></td>
			<td align="center"><input name="delete_[[|items.id|]]_" <?php if(Url::get('delete_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="delete_[[|items.id|]]_" class="column_delete row_[[|items.id|]] child_delete_[[|items.parent|]] <?php if([[=items.have_child=]]){?>parent_delete_[[|items.id|]]<?php }?>" <?php if([[=items.have_child=]]){?>onclick="select_all_child([[|items.id|]],'delete')"<?php }?>></td>
			<td align="center"><input name="admin_[[|items.id|]]_" <?php if(Url::get('admin_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="admin_[[|items.id|]]_" class="column_admin row_[[|items.id|]] child_admin_[[|items.parent|]] <?php if([[=items.have_child=]]){?>parent_admin_[[|items.id|]]<?php }?>" <?php if([[=items.have_child=]]){?>onclick="select_all_child([[|items.id|]],'admin')"<?php }?></td>
			<td align="center"><input name="select_all_[[|items.id|]]_" <?php if(Url::get('select_all_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="select_all_[[|items.id|]]_" onclick="select_all_column('[[|items.id|]]')"></td>
		  </tr>
  		  <!--/LIST:items-->
		</table>
	</td>
    </tr>
</table>
</form>
</fieldset>
<style>
	.quick-menu-item.add,
	.quick-menu-item.delete,
	.quick-menu-item.edit,
	.quick-menu-item.update,
	.quick-menu-item.check_in,
	.quick-menu-item.check_out,
	.quick-menu-item.print,
	.quick-menu-item.move,
	.quick-menu-item.cache
	{
		display:none;
	}
</style>