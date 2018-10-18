<?php 
$title = Portal::language('function_help_list');
System::set_page_title(HOTEL_NAME);?>
<form method="post" name="Searchhelp_listForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="35%" class="form-title"><?php echo $title.' : <b>'.HOTEL_NAME.'</b>';?></td>
		<td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'export_cache'));?>" class="button-medium-export">[[.exportal_cache.]]</a></td>
		<?php 
		if(URL::get('cmd')=='delete'){?>
		<td width="1%"><a onclick="$('cmd').cmd='delete';Listhelp_listForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td>
		<td width="1%"><a href="<?php echo URL::build_current(Module::$current->redirect_parameters);?>"  class="button-medium-back">[[.back.]]</a></td>
		<?php 
		}else{ 
		if(User::can_add(false,ANY_CATEGORY)){?>
		<td width="1%"><a href="<?php echo URL::build_current(Module::$current->redirect_parameters+array('cmd'=>'add'));?>"  class="button-medium-add">[[.Add.]]</a></td>
		<?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?>
		<td width="1%"><a href="javascript:void(0)" onclick="Listhelp_listForm.cmd.value='delete';Listhelp_listForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td>
		<?php }
		}?>
	</tr>
</table>
</form>	
<form name="Listhelp_listForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<a name="top_anchor"></a>		
	<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="<?php echo Portal::get_setting('crud_list_item_frame_color','#C3C3C3');?>">
		<thead>
			<tr valign="middle" bgcolor="<?php echo Portal::get_setting('crud_list_item_bgcolor','#E6E6E6');?>" style="line-height:20px">
				<th width="1%" title="[[.check_all.]]">
				<input type="checkbox" value="1" id="help_list_all_checkbox" onclick="select_all_checkbox(this.form,'help_list',this.checked,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th />
				<th nowrap align="left">
				<a title="[[.sort.]]" href="<?php echo URL::build_current(((URL::get('order_by')=='help_list.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'help_list.name_'.Portal::language()));?>" >
				<?php if(URL::get('order_by')=='help_list.name_'.Portal::language()) echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				[[.name.]]					</a>				</th><th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='help_list.type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'help_list.type'));?>" title="[[.sort.]]">
				<?php if(URL::get('order_by')=='help_list.type') echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				[[.type.]]					</a>
				</th>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;?>
			<!--LIST:items-->
			<?php $onclick = 'location=\''.URL::build_current().'&cmd=edit&id='.urlencode([[=items.id=]]).'\';"';?>
			<tr bgcolor="<?php if(true){ echo Portal::get_setting('crud_just_edited_item_bgcolor','#F7F7F7');} else {echo Portal::get_setting('crud_item_bgcolor','white');}?>" valign="middle" <?php Draw::hover(Portal::get_setting('crud_item_hover_bgcolor','#F7F7F7'));?> style="cursor:hand;" id="help_list_tr_[[|items.id|]]">
				<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'help_list',this,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');" id="help_list_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td />
				<td nowrap align="left" onclick="window.location='<?php echo Url::build_current().'&cmd=edit&id='.[[=items.id=]];?>'">
				[[|items.indent|]]
				[[|items.indent_image|]]
				<span class="page_indent">&nbsp;</span>
				[[|items.name|]]</td>
				<td nowrap align="left" <?php echo $onclick;?>>
				[[|items.type|]]
				</td>
				<td width="24px" align="center">[[|items.move_up|]]</td>
				<td width="24px" align="center">[[|items.move_down|]]</td>
			</tr>
			<!--/LIST:items-->
		</tbody>
	</table>
	<table width="100%">
		<tr>
			<td width="100%">
			[[.select.]]:&nbsp;
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.Listhelp_listForm,'help_list',true,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_all.]]</a>&nbsp;
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.Listhelp_listForm,'help_list',false,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_none.]]</a>
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.Listhelp_listForm,'help_list',-1,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_invert.]]</a>
			</td>
			<td>
			<a name="bottom_anchor" href="#top_anchor"><img alt="" src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
		</tr>
	</table>		
	<input type="hidden" name="cmd" value="" id="cmd"/>
	<!--IF:delete(URL::get('cmd')=='delete')-->
	<input type="hidden" name="confirm" value="1" />
	<!--/IF:delete-->
</form>
