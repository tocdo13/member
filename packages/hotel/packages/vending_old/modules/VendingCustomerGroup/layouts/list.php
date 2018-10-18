<?php 
$title = Portal::language('customer_group_list');
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
		<table cellpadding="0" cellspacing="0" width="100%" class="table-bound">
			<tr height="40">
				<td width="75%" class="form-title">[[.customer_group_list.]]</td>
				<td align="right" nowrap="nowrap">
					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add">[[.Add.]]</a><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCustomerGroupForm.cmd.value='delete';ListCustomerGroupForm.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
				</td>
			</tr>
		</table>      
		<fieldset>
		<legend class="title">[[.search.]]</legend>
		<form method="post" name="SearchCustomerGroupForm">
			[[.name.]]:
			<input name="name" type="text" id="name" style="width:100px" />&nbsp;
			<input type="hidden" name="page_no" value="1">
			<input type="submit" value="   [[.search.]]   ">
		</form>
		</fieldset><br />
		<div class="content">
		<!--IF:cond(URL::get('selected_ids'))--><div class="notice">[[.selected_list_to_delete.]]</div><br /><!--/IF:cond-->
		<form name="ListCustomerGroupForm" method="post">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
			<thead>
			<tr class="table-header">
				<td width="1%" title="[[.check_all.]]">
					<input type="checkbox" value="1" id="CustomerGroup_all_checkbox" onclick="select_all_checkbox(this.form,'CustomerGroup',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></td />
				<td nowrap align="left" >
					<a href="<?php echo URL::build_current(((URL::get('order_by')=='customer_group.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'customer_group.name'));?>" title="[[.sort.]]">
					<?php if(URL::get('order_by')=='customer_group.name') echo '<img alt="" src="skins/default/images/icon/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
					[[.name.]]
					</a>
				</td>
				<td>&nbsp;</td>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
				<!--<td>&nbsp;</td>
				<td>&nbsp;</td>-->
				<?php }?>
			</tr>
			</thead>
			<tbody>
			<?php $i=1;?><!--LIST:items-->
       		 <tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}$i++;?>>
				<td><!--IF:cond([[=items.structure_id=]]!=ID_ROOT)--><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'CustomerGroup',this,'#FFFFEC','white');" id="CustomerGroup_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>><!--/IF:cond--></td />
				<td nowrap align="left">[[|items.indent|]] [[|items.indent_image|]]<span class="page_indent">&nbsp;</span>[[|items.name|]]</td>
				<td width="24px" align="center"><a href="#" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';"><img src="packages/core/skins/default/images/buttons/edit.gif" width="20" height="20" /></a></td>
				<!--<td width="24px" align="center">[[|items.move_up|]]</td>
				<td width="24px" align="center">[[|items.move_down|]]</td>-->
			</tr>
			<!--/LIST:items-->
			</tbody>
		</table>
		<input type="hidden" name="cmd" value="delete"/>
		<!--IF:delete(URL::get('cmd')=='delete')-->
		<input type="hidden" name="confirm" value="1" />
		<!--/IF:delete-->
		</form>
		</div>
</div>

