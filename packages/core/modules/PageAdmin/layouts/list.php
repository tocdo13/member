<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_title'):Portal::language('list_title');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div id="title_region"></div>
<div class="form_bound">
<table cellpadding="10" width="100%">
	<tr>
    	<td class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td>
		<?php if(URL::get('cmd')=='delete'){?><td width="1%" nowrap="nowrap" class="form_title_button"><a  onclick="ListPageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" style="text-align:center" width="24px" height="24px" alt=""/><br />[[.Delete.]]</a></td>
        <td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 'name'=>isset($_GET['name'])?$_GET['name']:''));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif" style="text-align:center" alt=""/><br />[[.back.]]</a></td><?php }else{ 
		if(User::can_edit()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id','cmd'=>'delete_all_cache'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_cache_button.gif" style="text-align:center"/><br />[[.delete_cache.]]</a></td><?php } 
		if(User::can_add()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 'name'=>isset($_GET['name'])?$_GET['name']:'')+array('cmd'=>'add'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>add_button.gif" style="text-align:center"  width="24px" height="24px" alt=""/><br />[[.Add.]]</a></td>
        <td width="1%" nowrap="nowrap" class="form_title_button" align="center"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 'name'=>isset($_GET['name'])?$_GET['name']:'')+array('cmd'=>'add_blocks'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>add.jpg" style="text-align:center" alt="" width="24px" height="24px"/><br />[[.Add_block.]]</a></td><?php }?>
		<?php if(User::can_delete()){?><td width="1%" nowrap="nowrap" class="form_title_button">
				<a  onclick="ListPageAdminForm.cmd.value='delete';ListPageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" width="24px" height="24px" alt=""/><br />[[.Delete.]]</a></td><?php }}?>
				<td width="1%" nowrap="nowrap" class="form_title_button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
	<div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td nowrap bgcolor="#EFEFEF">
			<table width="100%" cellpadding="0" cellspacing="0" class="home-item-category-tree">
			<!--LIST:packages-->
			<tr>
			  <td nowrap>
				<a href="<?php echo URL::build_current(array('portal_id'));?>&package_id=[[|packages.id|]]" class="home-news-category-level<?php if(URL::get('package_id')==[[=packages.id=]])echo '_selected'.[[=packages.level=]];else echo [[=packages.level=]];?>">[[|packages.name|]]</a>
			</td>
			</tr>
			<!--/LIST:packages-->
			</table>
			<a target="_blank" href="<?php echo Url::build('package');?>" class="category_level1">[[.packages/admin.]]</a>  
		</td>
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<form method="post" name="SearchPageAdminForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					[[.name.]]: <input name="name" type="text" id="name" style="width:200"> [[.portal.]]: <input name="portal_id" type="text" id="portal_id" style="width:120">  <input type="submit" value="[[.search.]]">
					</form>
					<form name="ListPageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					<a name="top_anchor"></a>
					<div style="border:2px solid #FFFFFF;">
					<table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
					
						<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="PageAdmin_all_checkbox" onclick="select_all_checkbox(this.form, 'PageAdmin',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th>&nbsp;</th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='PAGE.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'PAGE.name'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='page.name') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.name.]]
								</a>
							</th>
							<th nowrap align="left">
								<a title="[[.sort.]]" href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='PAGE.TITLE_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'PAGE.TITLE_'.Portal::language()));?>" >
								<?php if(URL::get('order_by')=='PAGE.TITLE_'.Portal::language()) echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.title.]]
								</a>
							</th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='package_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'package_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='package_id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.package_id.]]
								</a>
							</th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='PARAMS' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'PARAMS'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='PARAMS') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								[[.params.]]
								</a>
							</th>
							<?php if(User::can_edit())
							{
							?><th>&nbsp;</th><th>&nbsp;</th><?php
							}?>						</tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo [[=items.is_sibling=]]?'#FFFFDF':'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" id="PageAdmin_tr_[[|items.id|]]">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'PageAdmin',this,'#FFFFEC','white');" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td><a target="_blank" href="<?php echo URL::build([[=items.name=]]);?>&[[|items.params|]]"><img src="<?php echo Portal::template('core').'/images/buttons/';?>select.jpg" /></a></td>
							<td nowrap align="left" onclick="location='[[|items.href|]]';">
									[[|items.name|]]
							</td>
							<td align="left" onclick="location='[[|items.href|]]';">
								[[|items.name|]]
							</td>
							<td nowrap align="left" onclick="location='[[|items.href|]]';">
								[[|items.package_id|]]
							</td>
							<td nowrap align="left" onclick="location='[[|items.href|]]';">
								[[|items.params|]]
							</td>
							<?php 
							if(User::can_edit())
							{
							?>							<td width="24px" align="center">
								<a href="<?php echo Url::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'',  
	'name'=>isset($_GET['name'])?$_GET['name']:'',    
	  )+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit.gif" alt="[[.Edit.]]" width="12" height="12" border="0"></a></td>
	  						<td nowrap><a href="<?php echo Url::build_current(array('portal_id','package_id', 'name')+array('cmd'=>'duplicate','id'=>[[=items.id=]])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>duplicate.gif" alt="[[.duplicate.]]" width="13" height="13" border="0"></a></td>
							<?php
							}
							?>						</tr>
						<!--/LIST:items-->
					</table>
					
					<input type="hidden" name="cmd" value="delete"/>
<input type="hidden" name="page_no" value="1"/>
<!--IF:delete(URL::get('cmd')=='delete')-->
					<input type="hidden" name="confirm" value="1" />
					<!--/IF:delete-->
					</form>
				</td>
			</tr>
			</table>
			[[|paging|]]
			<table width="100%"><tr>
			<td width="100%">
				[[.select.]]:&nbsp;
				<a  onclick="select_all_checkbox(document.ListPageAdminForm,'PageAdmin',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
				<a  onclick="select_all_checkbox(document.ListPageAdminForm,'PageAdmin',false,'#FFFFEC','white');">[[.select_none.]]</a>
				<a  onclick="select_all_checkbox(document.ListPageAdminForm,'PageAdmin',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
			</td>
			<td>
				<a name="bottom_anchor" javascript:void(0)><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
			</tr></table>
			
		</td>
</tr>
	</table>	
	</div>
</div>
