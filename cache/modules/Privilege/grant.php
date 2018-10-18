<script>
var data = <?php echo String::array2autosuggest($this->map['users']);?>;	
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
    if(name=='select_all')
    {
        jQuery('form .column_show').attr('checked',check);
        jQuery('form .column_view').attr('checked',check); 
        jQuery('form .column_add').attr('checked',check); 
        jQuery('form .column_edit').attr('checked',check); 
        jQuery('form .column_delete').attr('checked',check); 
        jQuery('form .column_admin').attr('checked',check);
        
        //select_all_check_column
        jQuery('form .select_all_check_column').attr('checked',check);
        
    }
}
function select_all_column(name)
{
   // console.log('bbbb');
    var check = document.getElementById('select_all_'+name+'_').checked;
    if(check)
    {
        jQuery('form .row_'+name).attr('checked',true);
        //check tat ca nhung doi tuong con lai 
        
        console.log('aaaa');
              
    }
    else
    {
        jQuery('form .row_'+name).attr('checked',false);
        jQuery('form .column_'+name).attr('checked',false); 
    }
    
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
        <th width="90%" align="left"><a><?php echo Portal::language('account_id');?></a></th>
        <th width="1%" align="right"><input name="save" type="submit"  value="<?php echo Portal::language('save');?>" class="button-medium-save"></th>
        <th width="1%"><input type="button"  value="<?php echo Portal::language('privilege_list');?>" onclick="window.location='<?php echo Url::build('privilege');?>'" class="button-medium-back"></th>
    </tr>
</table>
<table cellpadding="4" cellspacing="0" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#E7E7E7" align="center">
  <tr>
    <td width="17%" valign="top">
    	<input  name="privilege_id" id="privilege_id" size="40" type ="hidden" value="<?php echo String::html_normalize(URL::get('privilege_id'));?>">
        <?php echo Portal::language('Privilege_name');?> : <strong><?php echo $this->map['privilege_name'];?></strong> -- 
		<?php echo Portal::language('portal_name');?>:
		<input  name="portal_id" id="portal_id" / type ="hidden" value="<?php echo String::html_normalize(URL::get('portal_id'));?>"><strong><?php echo $this->map['portal_name'];?></strong>
       </td>
    </tr>
	 <tr style="padding:10px">
    <td width="17%" valign="top">
		<table cellpadding="3" cellspacing="2" width="100%" style="#width:99%;margin-top:2px;" border="1" bordercolor="#C7C7C7" align="center">
		<tr bgcolor="#EFEFEF">
		  <th width="1%" rowspan="2"><a><?php echo Portal::language('No');?></a></th>
		  <th width="51" rowspan="2" align="left"><a><?php echo Portal::language('category_name');?></a></th>
		  <th colspan="10" align="center"><a style="text-transform:uppercase"><?php echo Portal::language('grant_privilege');?></a></th>
		  </tr>
		<tr bgcolor="#EFEFEF" align="center">
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('show');"><?php echo Portal::language('view');?></a><br /><input name="select_all"  type="checkbox" id="show" class="column_select_all" onclick="select_all_module('show');"></th>
			<!--<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('view');"><?php echo Portal::language('view');?></a><br /><input  name="select_all" id="view" class="column_select_all" onclick="select_all_module('view');" type ="checkbox" value="<?php echo String::html_normalize(URL::get('select_all'));?>"></th>-->
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('add');"><?php echo Portal::language('add');?></a><br /><input  name="select_all" id="add" class="column_select_all" onclick="select_all_module('add');" type ="checkbox" value="<?php echo String::html_normalize(URL::get('select_all'));?>"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('edit');"><?php echo Portal::language('edit');?></a><br /><input  name="select_all" id="edit" class="column_select_all" onclick="select_all_module('edit');" type ="checkbox" value="<?php echo String::html_normalize(URL::get('select_all'));?>"></th>
			<th width="6%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('delete');"><?php echo Portal::language('delete');?></a><br /><input  name="select_all" id="delete" class="column_select_all" onclick="select_all_module('delete');" type ="checkbox" value="<?php echo String::html_normalize(URL::get('select_all'));?>"></th>
			<th width="3%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('admin');"><?php echo Portal::language('admin');?></a><br /><input  name="select_all" id="admin" class="column_select_all" onclick="select_all_module('admin');" type ="checkbox" value="<?php echo String::html_normalize(URL::get('select_all'));?>"></th>
			<th width="3%" nowrap="nowrap"><a href="javascript:void(0)" onclick="select_all_module('select_all');"><?php echo Portal::language('select_all');?></a><br /><input  name="select_all" id="select_all" onclick="select_all_module('select_all');" type ="checkbox" value="<?php echo String::html_normalize(URL::get('select_all'));?>"></th>
		</tr>
		  <?php $i=1;?>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		  <tr  style="cursor:hand;<?php if(!($i%2)){echo 'background-color:'.'#EFEFEF';}?>"  <?php Draw::hover('#EFEFEF');?>>	
		  	<td align="center"><?php echo $i++;?><input name="module_<?php echo $this->map['items']['current']['id'];?>_" type="hidden" value="<?php echo $this->map['items']['current']['module_id'];?>"></td>
			<td align="left"><?php echo $this->map['items']['current']['indent'];?><?php echo $this->map['items']['current']['indent_image'];?> <?php echo $this->map['items']['current']['name'];?> (<?php echo $this->map['items']['current']['id'];?>)
			<?php if(isset($_REQUEST['privilege_group_id_'.$this->map['items']['current']['id'].'_'])){?>
			<input name="privilege_group_id_<?php echo $this->map['items']['current']['id'];?>_"  value="<?php echo Url::get('privilege_group_id_'.$this->map['items']['current']['id'].'_');?>" type="hidden">
			<?php }?>			</td>
			<td align="center"><input name="show_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('show_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="show_<?php echo $this->map['items']['current']['id'];?>_" class="column_show row_<?php echo $this->map['items']['current']['id'];?> child_show_<?php echo $this->map['items']['current']['parent'];?> <?php if($this->map['items']['current']['have_child']){?>parent_show_<?php echo $this->map['items']['current']['id'];?><?php }?>" <?php if($this->map['items']['current']['have_child']){?>onclick="select_all_child(<?php echo $this->map['items']['current']['id'];?>,'show')"<?php }?>></td>
			<!--<td align="center"><input name="view_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('view_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="view_<?php echo $this->map['items']['current']['id'];?>_" class="column_view row_<?php echo $this->map['items']['current']['id'];?> child_view_<?php echo $this->map['items']['current']['parent'];?> <?php if($this->map['items']['current']['have_child']){?>parent_view_<?php echo $this->map['items']['current']['id'];?><?php }?>" <?php if($this->map['items']['current']['have_child']){?>onclick="select_all_child(<?php echo $this->map['items']['current']['id'];?>,'view')"<?php }?>></td>-->
			<td align="center"><input name="add_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('add_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="add_<?php echo $this->map['items']['current']['id'];?>_" class="column_add row_<?php echo $this->map['items']['current']['id'];?> child_add_<?php echo $this->map['items']['current']['parent'];?> <?php if($this->map['items']['current']['have_child']){?>parent_add_<?php echo $this->map['items']['current']['id'];?><?php }?>" <?php if($this->map['items']['current']['have_child']){?>onclick="select_all_child(<?php echo $this->map['items']['current']['id'];?>,'add')"<?php }?>></td>
			<td align="center"><input name="edit_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('edit_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="edit_<?php echo $this->map['items']['current']['id'];?>_" class="column_edit row_<?php echo $this->map['items']['current']['id'];?> child_edit_<?php echo $this->map['items']['current']['parent'];?> <?php if($this->map['items']['current']['have_child']){?>parent_edit_<?php echo $this->map['items']['current']['id'];?><?php }?>" <?php if($this->map['items']['current']['have_child']){?>onclick="select_all_child(<?php echo $this->map['items']['current']['id'];?>,'edit')"<?php }?>></td>
			<td align="center"><input name="delete_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('delete_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="delete_<?php echo $this->map['items']['current']['id'];?>_" class="column_delete row_<?php echo $this->map['items']['current']['id'];?> child_delete_<?php echo $this->map['items']['current']['parent'];?> <?php if($this->map['items']['current']['have_child']){?>parent_delete_<?php echo $this->map['items']['current']['id'];?><?php }?>" <?php if($this->map['items']['current']['have_child']){?>onclick="select_all_child(<?php echo $this->map['items']['current']['id'];?>,'delete')"<?php }?>></td>
			<td align="center"><input name="admin_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('admin_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="admin_<?php echo $this->map['items']['current']['id'];?>_" class="column_admin row_<?php echo $this->map['items']['current']['id'];?> child_admin_<?php echo $this->map['items']['current']['parent'];?> <?php if($this->map['items']['current']['have_child']){?>parent_admin_<?php echo $this->map['items']['current']['id'];?><?php }?>" <?php if($this->map['items']['current']['have_child']){?>onclick="select_all_child(<?php echo $this->map['items']['current']['id'];?>,'admin')"<?php }?></td>
			<td align="center"><input name="select_all_<?php echo $this->map['items']['current']['id'];?>_" <?php if(Url::get('select_all_'.$this->map['items']['current']['id'].'_')){echo 'checked="checked"';}?> type="checkbox" id="select_all_<?php echo $this->map['items']['current']['id'];?>_" class="select_all_check_column" onclick="select_all_column('<?php echo $this->map['items']['current']['id'];?>')"></td>
		  </tr>
  		  <?php }}unset($this->map['items']['current']);} ?>
		</table>
	</td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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