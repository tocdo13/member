<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_title'):Portal::language('list_title');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<style>
.module_tab
{
	font-size:16px;
	background-color:#DDDDDD;
}
.module_tab_select
{
	font-size:16px;
	background-color:#EFEFEF;
}
</style>
<div class="form_bound">
<table cellpadding="10" width="100%">
	<tr>
    	<td class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td><?php 
		if(URL::get('cmd')=='delete'){?><td class="form_title_button"><a  onclick="ListModuleAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" style="text-align:center" alt=""/><br />[[.Delete.]]</a></td>
		<td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'','name'=>isset($_GET['name'])?$_GET['name']:''));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif" style="text-align:center" alt=""/><br />[[.back.]]</a></td><?php }else{ 
		if(User::can_edit()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('package_id','cmd'=>'update'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>make_cache_button.gif" style="text-align:center"/><br />[[.update.]]</a></td>
	<td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'delete_cache'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_cache_button.gif" style="text-align:center"/><br />[[.delete_module_cache.]]</a></td><?php }?><?php 
		if(User::can_add()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('type','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'','name'=>isset($_GET['name'])?$_GET['name']:'')+array('cmd'=>'add'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>add_button.gif" style="text-align:center" alt=""/><br />[[.Add.]]</a></td><?php }?><?php 
		if(User::can_delete()){?>
	<td width="1%" nowrap="nowrap" class="form_title_button"><a  onclick="ListModuleAdminForm.cmd.value='delete';ListModuleAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br />
			[[.Delete.]]</a></td>
	<?php }}?>
				<td class="form_title_button"  width="1%" nowrap="nowrap"><a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
	<div id="title_region" style="display:block"></div>
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td nowrap bgcolor="white">
			<table width="100%" cellpadding="0" cellspacing="0">
			<!--LIST:packages-->
			<tr><td nowrap>
				<a href="<?php echo URL::build_current(array('page_id','region','after','replace','type'));?>&package_id=[[|packages.id|]]" class="home-news-category-level<?php if(URL::get('package_id')==[[=packages.id=]])echo '_selected'.[[=packages.level=]];else echo [[=packages.level=]];?>">[[|packages.name|]]</a>
			</td></tr>
			<!--/LIST:packages-->
			</table>
			<a href="<?php echo Url::build('package');?>" class="category_level1">[[.packages/admin.]]</a> 
		</td>
		<td width="100%">
			<table width="100%"><tr><td style="border-bottom:3px solid #FFCC99">
			<a class="module_tab<?php if(!URL::get('type'))echo '_select';?>" href="<?php echo URL::build_current(array('package_id','name','region','page_id','after','replace','href'));?>">&nbsp;&nbsp;Normal module&nbsp;&nbsp;</a>
			<a class="module_tab<?php if(URL::get('type')=='CONTENT')echo '_select';?>" href="<?php echo URL::build_current(array('package_id','name','region','page_id','after','replace','type'=>'CONTENT','href'));?>">&nbsp;&nbsp;Content&nbsp;&nbsp;</a>
			<a class="module_tab<?php if(URL::get('type')=='HTML')echo '_select';?>" href="<?php echo URL::build_current(array('package_id','name','region','page_id','after','replace','type'=>'HTML','href'));?>">&nbsp;&nbsp;HTML&nbsp;&nbsp;</a>
			<a class="module_tab<?php if(URL::get('type')=='PLUGIN')echo '_select';?>" href="<?php echo URL::build_current(array('package_id','name','region','page_id','after','replace','type'=>'PLUGIN','href'));?>">&nbsp;&nbsp;PLUGIN&nbsp;&nbsp;</a>
			<a class="module_tab<?php if(URL::get('type')=='WRAPPER')echo '_select';?>" href="<?php echo URL::build_current(array('package_id','name','region','page_id','after','replace','type'=>'WRAPPER','href'));?>">&nbsp;&nbsp;WRAPPER&nbsp;&nbsp;</a>
			</td></tr>
			<tr><td>
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<form method="post" name="SearchModuleAdminForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					[[.name.]]: <input name="name" type="text" id="name" style="width:120"><input type="hidden" name="page_no" value="1" /><input type="submit" value="   [[.search.]]   ">
					</form>
					<a name="top_anchor"></a>
					<div style="border:2px solid #FFFFFF;">
					<form name="ListModuleAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					<table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
						<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="ModuleAdmin_all_checkbox" onclick="select_all_checkbox(this.form,'ModuleAdmin',this.checked,'#FFFFEC','white');" <?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='module.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'module.name'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='module.name') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.name.]]
								</a>
							</th><th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='package_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'package_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='package_id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.package_id.]]
								</a>
							</th><th nowrap align="left">
								<a title="[[.sort.]]" href="<?php echo URL::build_current(((URL::get('order_by')=='module.title_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'module.title_'.Portal::language()));?>" >
								<?php if(URL::get('order_by')=='MODULE.title_'.Portal::language()) echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.title.]]
								</a>
							</th>
							<?php if(User::can_edit())
							{
							?><th>&nbsp;</th><th>&nbsp;</th><?php
							}?>						</tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" id="ModuleAdmin_tr_[[|items.id|]]">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ModuleAdmin',this,'#FFFFEC','white');" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td nowrap align="left" onclick="location='[[|items.href|]]';">
									[[|items.name|]]
								</td><td nowrap align="left" onclick="location='[[|items.href|]]';">
									[[|items.package_id|]]
								</td><td align="left" onclick="location='[[|items.href|]]';">
									[[|items.title|]]
								</td>
							<?php 
							if(User::can_edit())
							{
							?><td width="24px" align="center">
								<a href="<?php echo Url::build_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 
	'name'=>isset($_GET['name'])?$_GET['name']:'',  
	  )+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit.gif" alt="[[.Edit.]]" width="12" height="12" border="0"></a></td>
							  <td width="24px" align="center">
								<a href="<?php echo Url::build('module_setting',array('module_id'=>[[=items.id=]])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>information.png" alt="[[.Setting.]]" width="12" height="12" border="0"></a></td>
							<?php
							}
							?>						</tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			</div>
			[[|paging|]]
			<table width="100%"><tr>
			<td width="100%">
				[[.select.]]:&nbsp;
				<a  onclick="select_all_checkbox(ListModuleAdminForm,'ModuleAdmin',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
				<a  onclick="select_all_checkbox(ListModuleAdminForm,'ModuleAdmin',false,'#FFFFEC','white');">[[.select_none.]]</a>
				<a  onclick="select_all_checkbox(ListModuleAdminForm,'ModuleAdmin',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
			</td>
			<td>
				<a name="bottom_anchor" javascript:void(0)><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
			</tr></table>
			<input type="hidden" name="cmd" value="delete"/>
<input type="hidden" name="page_no" value="1"/>
<!--IF:delete(URL::get('cmd')=='delete')-->
				<input type="hidden" name="confirm" value="1" />
				<!--/IF:delete-->
			</form>
		</td></tr></table>
	</td></tr></table>
</div>
