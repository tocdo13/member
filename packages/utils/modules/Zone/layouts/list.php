<?php 
$title = Portal::language('zone_list');
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
		<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
			<tr>
				<td width="55%" class="form-title">[[.zone_list.]]</td>
				<td width="20%" align="right" nowrap="nowrap">
					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add">[[.Add.]]</a><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListZoneForm.cmd.value='delete';ListZoneForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
				</td>
			</tr>
		</table>      
		<fieldset>
			<legend class="title">[[.search.]]</legend>
		<form method="post" name="SearchZoneForm">
			[[.name.]]:
			<input name="name" type="text" id="name" style="width:100px" />&nbsp;
			<input type="hidden" name="page_no" value="1">
			<input type="submit" value="   [[.search.]]   ">
		</form>
		</fieldset><br />
		<div class="content">
		<!--IF:cond(URL::get('selected_ids'))--><div class="notice">[[.selected_list_to_delete.]]</div><br /><!--/IF:cond-->
		<form name="ListZoneForm" method="post">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
			<thead>
			<tr class="table-header">
				<td width="1%" title="[[.check_all.]]">
					<input type="checkbox" value="1" id="Zone_all_checkbox" onclick="select_all_checkbox(this.form,'Zone',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></td />
				<td nowrap align="left" >&nbsp;</td>
				<td colspan="2" align="center">[[.vietnamese.]]</td>
				<td colspan="2" align="center">[[.english.]]</td>
				<td>&nbsp;</td>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<?php }?>
			</tr>
			<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
			  <td title="[[.check_all.]]">&nbsp;</td />
			  <td nowrap align="left" >&nbsp;</td>
			  <td width="100" align="center">[[.code.]]</td>
			  <td align="center">[[.name.]]</td>
			  <td width="100" align="center">[[.code.]]</td>
			  <td align="center">[[.name.]]</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  </tr>
			</thead>
			<tbody>
			<!--LIST:items-->
			<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#FFFFFF';} else {echo '#FFFFFF';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:hand;" id="Zone_tr_[[|items.id|]]">
				<td><!--IF:cond([[=items.structure_id=]]!=ID_ROOT)--><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Zone',this,'#FFFFEC','white');" id="Zone_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>><!--/IF:cond--></td />
				<td nowrap align="left">
						[[|items.indent|]]
						[[|items.indent_image|]]</td>
			    <td>[[|items.brief_name_1|]]</td>
			    <td>[[|items.name_1|]]</td>
			    <td>[[|items.brief_name_2|]]</td>
			    <td>[[|items.name_2|]] </td>
		        <td width="24" align="center"><a href="<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]">[[.edit.]]</a></td>
	          <td width="24" align="center">[[|items.move_up|]]</td>
				<td width="24" align="center">[[|items.move_down|]]</td>
			</tr>
			<!--/LIST:items-->
			</tbody>
		</table>
		<table width="100%"><tr>
		<td width="100%">
			[[.select.]]:&nbsp;
			<a href="#a" onclick="select_all_checkbox(document.ListZoneForm,'Zone',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
			<a href="#a" onclick="select_all_checkbox(document.ListZoneForm,'Zone',false,'#FFFFEC','white');">[[.select_none.]]</a>
			<a href="#a" onclick="select_all_checkbox(document.ListZoneForm,'Zone',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
		</td>
		</tr></table>
		<input type="hidden" name="cmd" value="delete"/>
		<!--IF:delete(URL::get('cmd')=='delete')-->
		<input type="hidden" name="confirm" value="1" />
		<!--/IF:delete-->
		</form>
		</div>
</div>

