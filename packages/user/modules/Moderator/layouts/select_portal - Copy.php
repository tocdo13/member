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
	jQuery('form input:checkbox').each(function(e){
		if(jQuery(this).attr('name').match(name))
		{
			jQuery(this).attr('checked',check);
		}	
	});	
}
function select_all_column(name)
{
	var checked = jQuery('#select_all_'+name+'_').attr('checked');
	jQuery('form input:checkbox').each(function(e){
		if(jQuery(this).attr('name')!= '#select_all_'+name+'_' && jQuery(this).attr('name').match('_'+name+'_'))
		{
			jQuery(this).attr('checked',checked);
		}	
	});	
}
</script>
<fieldset id="toolbar" style="margin-top:2px;width:97%">
<a name="top_anchor"></a>
<div id="bound_content"></div>
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>	
<form name="GrantModeratorForm" method="post">
<table cellpadding="4" cellspacing="0" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#E7E7E7" align="center">
	<tr bgcolor="#EFEFEF" valign="top">		
        <th width="20%" align="left"><a>[[.account_id.]]</a></th>
        <th width="80%" align="right"><input name="save" type="submit"  value="[[.save.]]" /><input type="button"  value="[[.user_list.]]" onclick="window.location = '<?php echo Url::build('manage_user');?>';"></th>
    </tr>
</table>
<table cellpadding="4" cellspacing="0" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#E7E7E7" align="center">
  <tr>
    <td width="17%" valign="top">
		<input name="account_id" type="text" id="account_id" size="30" style="font-weight:bold;font-size:14px;color:#003399" AUTOCOMPLETE=OFF readonly="">
		<!--IF:cond(Url::get('cmd')=='edit' && Url::get('account_id'))--><script>jQuery('#account_id').attr('readonly',true);</script>
		<!--/IF:cond-->
		[[.portal_name.]] 
		<select name="portal_id" id="portal_id" onchange="GrantModeratorForm.submit();"></select>
	</td>    
    </tr>
	 <tr style="padding:10px">
    <td valign="top">
		<table cellpadding="2" cellspacing="0" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#C7C7C7" align="center">
		<tr class="table-header">
		  <th width="1%" rowspan="2"><a>[[.No.]]</a></th>
		  <th width="51" rowspan="2" align="left"><a>[[.category_name.]]</a></th>
		  <th colspan="10" align="center">[[.privileges.]]</th>
		  </tr>
		<tr bgcolor="#FFFFBF" align="center">
			<th width="5%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('show');">[[.run.]]</a><br /><br /><input name="select_all" type="checkbox" id="show" onclick="select_all_module('show');"></th>
			<th width="5%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('view');">[[.view.]]</a><br /><br /><input name="select_all" type="checkbox" id="view" onclick="select_all_module('view');"></th>
			<th width="5%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('add');">[[.add.]]</a><br /><br /><input name="select_all" type="checkbox" id="add" onclick="select_all_module('add');"></th>
			<th width="5%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('edit');">[[.edit.]]</a><br /><br /><input name="select_all" type="checkbox" id="edit" onclick="select_all_module('edit');"></th>
			<th width="5%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('delete');">[[.delete.]]</a><br /><br /><input name="select_all" type="checkbox" id="delete" onclick="select_all_module('delete');"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('reserve');">[[.reserve.]]</a><br /><span class="note">(V&#7899;i QL Reservation)</span><br />
			<input name="select_all" type="checkbox" id="reserve" onclick="select_all_module('reserve');"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('special');">[[.special.]]</a><br /><span class="note">(QL tr&ecirc;n nhi&#7873;u portal)</span><br />
			  <input name="select_all" type="checkbox" id="special" onclick="select_all_module('special');"></th>
			<th width="3%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('admin');">[[.admin.]]</a><br /><br /><input name="select_all" type="checkbox" id="admin" onclick="select_all_module('admin');"></th>
			<th width="3%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('select_all');">[[.select_all.]]</a><br /><br /><input name="select_all" type="checkbox" id="select_all" onclick="select_all_module('select_all');"></th>
		</tr>
		  <?php $i=1;?>
		  <!--LIST:items-->
		  <tr bgcolor="#C6E2FF">	
		  	<td align="center"><input name="module_[[|items.id|]]_" type="hidden" value="[[|items.module_id|]]"></td>
			<td align="left"><strong>[[|items.name|]]</strong> ([[|items.id|]])
			<?php if(isset($_REQUEST['privilege_id_'.[[=items.id=]]])){?>
			<input name="privilege_id_[[|items.id|]]_"  value="<?php echo Url::get('privilege_id_'.[[=items.id=]].'_');?>" type="hidden">
			<?php }?></td>
			<td align="center"><input name="show_[[|items.id|]]_" <?php if(Url::get('show_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="show_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="view_[[|items.id|]]_" <?php if(Url::get('view_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="view_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="add_[[|items.id|]]_" <?php if(Url::get('add_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="add_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="edit_[[|items.id|]]_" <?php if(Url::get('edit_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="edit_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="delete_[[|items.id|]]_" <?php if(Url::get('delete_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="delete_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="reserve_[[|items.id|]]_" <?php if(Url::get('reserve_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="reserve_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="special_[[|items.id|]]_" <?php if(Url::get('special_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="special_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="admin_[[|items.id|]]_" <?php if(Url::get('admin_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="admin_[[|items.id|]]_" class="action-button-[[|items.id|]]"></td>
			<td align="center"><input name="select_all_[[|items.id|]]_" <?php if(Url::get('select_all_'.[[=items.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="select_all_[[|items.id|]]_" onclick="select_all_column('[[|items.id|]]')"></td>
		  </tr>
		  <!--IF:cond1([[=items.child=]])-->
		  <!--LIST:items.child-->
		   <tr <?php Draw::hover('#EFEFEF');?>>	
		  	<td align="center"><input name="module_[[|items.child.id|]]_" type="hidden" value="[[|items.child.module_id|]]"></td>
			<td align="left">  &mdash;   <!--IF:cond2([[=items.child.child=]])--><strong>[[|items.child.name|]]</strong><!--ELSE-->[[|items.child.name|]]<!--/IF:cond2--> ([[|items.child.id|]])
			<?php if(isset($_REQUEST['privilege_id_'.[[=items.child.id=]]])){?>
			<input name="privilege_id_[[|items.child.id|]]_"  value="<?php echo Url::get('privilege_id_'.[[=items.child.id=]].'_');?>" type="hidden">
			<?php }?>			</td>
			<td align="center"><input name="show_[[|items.child.id|]]_" <?php if(Url::get('show_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="show_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="view_[[|items.child.id|]]_" <?php if(Url::get('view_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="view_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="add_[[|items.child.id|]]_" <?php if(Url::get('add_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="add_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="edit_[[|items.child.id|]]_" <?php if(Url::get('edit_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="edit_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="delete_[[|items.child.id|]]_" <?php if(Url::get('delete_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="delete_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="reserve_[[|items.child.id|]]_" <?php if(Url::get('reserve_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="reserve_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="special_[[|items.child.id|]]_" <?php if(Url::get('special_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="special_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="admin_[[|items.child.id|]]_" <?php if(Url::get('admin_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="admin_[[|items.child.id|]]_" class="action-button-[[|items.child.id|]]"></td>
			<td align="center"><input name="select_all_[[|items.child.id|]]_" <?php if(Url::get('select_all_'.[[=items.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="select_all_[[|items.child.id|]]_" onclick="select_all_column('[[|items.child.id|]]')"></td>
		  </tr>
		  <!--IF:cond3([[=items.child.child=]])-->
		  <!--LIST:items.child.child-->
		   <tr <?php Draw::hover('#D5FFFF');?>>	
		  	<td align="center"><input name="module_[[|items.child.child.id|]]_" type="hidden" value="[[|items.child.child.module_id|]]"></td>
			<td align="left" style="font-style:italic;">  &mdash;  &mdash;    <!--IF:cond4([[=items.child.child.child=]])--><strong>[[|items.child.child.name|]]</strong><!--ELSE-->[[|items.child.child.name|]]<!--/IF:cond4--> ([[|items.child.child.id|]])
			<?php if(isset($_REQUEST['privilege_id_'.[[=items.child.child.id=]]])){?>
			<input name="privilege_id_[[|items.child.child.id|]]_"  value="<?php echo Url::get('privilege_id_'.[[=items.child.child.id=]].'_');?>" type="hidden">
			<?php }?>			</td>
			<td align="center"><input name="show_[[|items.child.child.id|]]_" <?php if(Url::get('show_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="show_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="view_[[|items.child.child.id|]]_" <?php if(Url::get('view_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="view_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="add_[[|items.child.child.id|]]_" <?php if(Url::get('add_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="add_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="edit_[[|items.child.child.id|]]_" <?php if(Url::get('edit_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="edit_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="delete_[[|items.child.child.id|]]_" <?php if(Url::get('delete_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="delete_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="reserve_[[|items.child.child.id|]]_" <?php if(Url::get('reserve_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="reserve_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="special_[[|items.child.child.id|]]_" <?php if(Url::get('special_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="special_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="admin_[[|items.child.child.id|]]_" <?php if(Url::get('admin_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="admin_[[|items.child.child.id|]]_" class="action-button-[[|items.child.child.id|]]"></td>
			<td align="center"><input name="select_all_[[|items.child.child.id|]]_" <?php if(Url::get('select_all_'.[[=items.child.child.id=]].'_')){echo 'checked="checked"';}?> type="checkbox" id="select_all_[[|items.child.child.id|]]_" onclick="select_all_column('[[|items.child.child.id|]]')"></td>
		  </tr>
		  <!--/LIST:items.child.child-->
		  <!--/IF:cond3([[=items.child.child=]])-->
		  <!--/LIST:items.child-->
		  <!--/IF:cond1([[=items.child=]])-->
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
<script>
	<!--LIST:items-->
	jQuery("#show_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#show_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#show_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#show_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#show_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#view_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#view_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#view_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#view_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#view_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#add_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#add_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#add_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#add_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#add_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#edit_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#edit_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#edit_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#edit_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#edit_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#delete_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#delete_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#delete_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#delete_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#delete_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#admin_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#admin_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#admin_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#admin_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#admin_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#reserve_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#reserve_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#reserve_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#reserve_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#reserve_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	jQuery("#special_[[|items.id|]]_").click(function(){
		<!--LIST:items.child-->
		jQuery('#special_[[|items.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--LIST:items.child.child-->
		jQuery('#special_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		jQuery("#special_[[|items.child.id|]]_").click(function(){
		<!--LIST:items.child.child-->
		jQuery('#special_[[|items.child.child.id|]]_').attr('checked',jQuery(this).attr('checked'));
		<!--/LIST:items.child.child-->
		});
		<!--/LIST:items.child-->
	});
	<!--/LIST:items-->
</script>