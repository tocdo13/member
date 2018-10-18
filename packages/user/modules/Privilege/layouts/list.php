<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_privileges'):Portal::language('privileges');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<form name="ListPrivilegeForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
<div class="form-bound">
	<table cellpadding="15" width="100%">
        <tr>
            <td  class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $title;?></td><?php 
        		if(URL::get('cmd')=='delete'){?><td ><a class="w3-btn w3-red" href="javascript:void(0)" onclick="ListPrivilegeForm.submit();"><img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif" style="text-align:center" width="20"><br />[[.Delete.]]</a></td>
        		<td style="text-align: right;"><a class="w3-btn w3-green" href="<?php echo URL::build_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:''));?>"><img src="<?php echo Portal::template('core');?>/images/buttons/go_back_button.gif" style="text-align:center"><br />[[.back.]]</a></td><?php }else{ 
        		if(User::can_admin(MODULE_MANAGEUSER,ANY_CATEGORY)){?><!---<td ><a class="w3-btn w3-indigo" href="<?php echo URL::build('user');?>">[[.user.]]</a></td>---><?php }
        		if(User::can_edit(false,ANY_CATEGORY)){?><td ><a class="w3-btn w3-indigo" href="<?php echo URL::build_current(array('cmd'=>'make_cache'));?>" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.make_cache.]]</a><?php }
        		if(User::can_add(false,ANY_CATEGORY)){?><a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'')+array('cmd'=>'add'));?>" style="text-transform: uppercase; text-decoration: none;  margin-right: 2px;">[[.Add.]]</a><?php }?><?php 
        		if(User::can_delete(false,ANY_CATEGORY)){?>
				<a class="w3-btn w3-red" href="javascript:void(0)" onclick="ListPrivilegeForm.cmd.value='delete';ListPrivilegeForm.submit();" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }}?>
				<!---<td class="form-title-button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core');?>/images/buttons/frontpage.gif"><br />Trang ch&#7911;</a></td>--->
        </tr>
    </table>
	<div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<a name="top_anchor"></a>
					<div style="border:2px solid #FFFFFF;">
					<table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
						<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="Privilege_all_checkbox" onclick="select_all_checkbox(this.form, 'Privilege',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='privilege.title' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'privilege.title'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='privilege.title') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>
								[[.title.]]
								</a>
							</th><th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='package_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'package_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='package_id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>
								[[.package_id.]]
								</a>
							</th>
                            <th nowrap="nowrap" align="left">
                            	[[.home_page.]]
                            </th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><th nowrap="nowrap">[[.Edit.]]</th><th nowrap="nowrap">[[.grant.]]</th><?php
							}?>
						</tr>
						<!--LIST:items-->
						<?php 
						$action = User::can_edit(false,ANY_CATEGORY)?' onclick="location=\''.URL::build_current().'&cmd=edit&id='.[[=items.id=]].'\';"':'';?>
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" id="Privilege_tr_[[|items.id|]]">
							<td style="height: 24px;"><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Privilege',this,'#FFFFEC','white'\);" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td nowrap align="left" <?php echo $action;?>>
									[[|items.name|]]
							</td><td nowrap align="left" <?php echo $action;?>>
								[[|items.package_name|]]
							</td>
                            <td nowrap="nowrap" align="left">
                            	[[|items.home_page|]]
                            </td>
							<?php 
							if(User::can_edit(false,ANY_CATEGORY))
							{
							?>
							<td align="center" width="1%">
								<a href="<?php echo Url::build_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 
	)+array('cmd'=>'edit','id'=>[[=items.id=]],'portal_id'=>[[=items.portal_id=]])); ?>"><img src="<?php echo Portal::template('core');?>/images/buttons/edit.gif" alt="[[.Edit.]]" width="16" height="16" border="0"></a></td>
							<td align="center" width="1%">
								<a href="<?php echo Url::build_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'')+array('cmd'=>'grant','id'=>[[=items.id=]],'portal_id'=>[[=items.portal_id=]])); ?>"><img src="<?php echo Portal::template('core');?>/images/buttons/generate_button.png" alt="[[.Edit.]]" width="16" height="16" border="0"></a></td>
							<?php
							}
							?>
						</tr>
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
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListPrivilegeForm,'Privilege',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListPrivilegeForm,'Privilege',false,'#FFFFEC','white');">[[.select_none.]]</a>
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListPrivilegeForm,'Privilege',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
			</td>
			<td>
				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
			</tr></table>
			<input type="hidden" name="cmd" value="delete"/>
<input type="hidden" name="page_no" value="1"/>
<!--IF:delete(URL::get('cmd')=='delete')-->
				<input type="hidden" name="confirm" value="1" />
				<!--/IF:delete-->
		</td>
</tr>
	</table>	
	</div>
</div>
</form>