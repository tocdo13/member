<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('Delete_users'):Portal::language('Users');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="form-title" width="100%">[[.portal_list.]]</td>
            <?php if(URL::get('cmd')=='delete'){?>
            <td width="1%" nowrap="nowrap"><a class="button-medium-save" href="javascript:void(0)" onclick="ListManagePortalForm.submit();">[[.Delete.]]</a></td>
            <td><a class="button-medium-back" href="<?php echo URL::build_current(array('join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:''));?>">[[.back.]]</a></td>
            <?php }else{ if(User::can_add(false,ANY_CATEGORY)){?>
            <td><a class="button-medium-add" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>">[[.Add.]]</a></td>
            <?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <td><a class="button-medium-delete" href="javascript:void(0)" onclick="ListManagePortalForm.cmd.value='delete';ListManagePortalForm.submit();">[[.Delete.]]</a></td>
            <?php }}?>
        </tr>
    </table>
	<div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<form method="post" name="SearchManagePortalForm">
					<table>
						<tr>
						  <td align="right" nowrap style="font-weight:bold">[[.username.]]</td>
						<td>:</td>
						<td nowrap>
								<input name="user_id" type="text" id="user_id" style="width:300">
						</td>
                        <td><input type="hidden" name="page_no" value="1" />
							<input type="submit" value="   [[.search.]]   ">
						</td>
						</tr>
					</table>
					</form>
					<a name="top_anchor"></a>
					<div style="border:2px solid #FFFFFF;">
					<form name="ListManagePortalForm" method="post">
					<table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
						<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
							<th width="2%" title="[[.check_all.]]"><input type="checkbox" value="1" id="ManagePortal_all_checkbox" onclick="select_all_checkbox(this.form, 'ManagePortal',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th width="14%" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='account.id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'account.id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='account.id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								[[.portal_id.]]								</a>							</th>
								<th width="13%" align="left" nowrap >[[.name.]]</th>
							<th width="15%" align="left" nowrap>
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='zone_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'zone_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='zone_id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								[[.zone_id.]]								</a>							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY)){?>
							<th width="1%" align="center" nowrap>&nbsp;</th>
							<?php }?>
							<?php if(User::can_delete(false,ANY_CATEGORY)){?>
							<th width="1%" align="center" nowrap>&nbsp;</th>
							<?php }?>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo 'white';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" id="ManagePortal_tr_[[|items.id|]]">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ManagePortal',this,'#FFFFEC','white');" id="ManagePortal_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>';">[[|items.id|]]</td>
                            <td nowrap align="left">[[|items.name_1|]]</td>
                            <td align="left" nowrap>[[|items.zone_id|]]</td>
							<?php if(User::can_edit(false,ANY_CATEGORY)){?>
							<td align="left" nowrap><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>">[[.edit.]]</a></td>
							<?php }?>
							<?php if(User::can_delete(false,ANY_CATEGORY)){?>
							<td align="left" nowrap><a href="<?php echo URL::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>">[[.delete.]]</a></td>
							<?php }?>
						</tr>
						<!--/LIST:items-->
				  </table>
				  <input type="hidden" name="cmd" value="delete"/>
					<input type="hidden" name="page_no" value="1"/>
					<!--IF:delete(URL::get('cmd')=='delete')-->
					<input type="hidden" name="confirm" value="1" />
					<!--/IF:delete-->
				</form>
				</div>
				</td>
			</tr>
			</table>
			[[|paging|]]
			<table width="100%"><tr>
			<td width="100%">
				[[.select.]]:&nbsp;
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListManagePortalForm,'ManagePortal',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListManagePortalForm,'ManagePortal',false,'#FFFFEC','white');">[[.select_none.]]</a>
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListManagePortalForm,'ManagePortal',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
			</td>
			<td>
				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
			</tr></table>
		</td>
	</tr>
	</table>	
	</div>
</div>
