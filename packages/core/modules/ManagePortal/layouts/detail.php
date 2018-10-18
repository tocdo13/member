<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('Delete_user'):Portal::language('User_detail');
$action = (URL::get('cmd')=='delete')?'delete':'detail';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
	<table cellpadding="0" width="100%"><tr><td  class="form_title"><img src="<?php echo Portal::template('core');?>/images/buttons/<?php echo $action;?>_button.gif" align="absmiddle"/><?php echo $title;?></td><?php if(URL::get('cmd')=='delete'){?><td class="form_title_button"><a href="javascript:void(0)" onclick="ManagePortalForm.submit();"><img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif" style="text-align:center"/><br />[[.Delete.]]</a></td><?php }else{ if(User::can_edit(false,ANY_CATEGORY)){?><td class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'));?>"><img src="<?php echo Portal::template('core');?>/images/buttons/edit.jpg" style="text-align:center"/><br />[[.Edit.]]</a></td><?php } if(User::can_delete(false,ANY_CATEGORY)){?><td class="form_title_button">
				<a href="<?php echo URL::build_current(array('cmd'=>'delete','id'));?>"><img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif"/><br />[[.Delete.]]</a></td><?php }}?>
				<td class="form_title_button"><a href="<?php echo URL::build_current(array(  
	     'join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:'', 
	));?>"><img src="<?php echo Portal::template('core');?>/images/buttons/go_back_button.gif" style="text-align:center"/><br />[[.back.]]</a></td>
				<td class="form_title_button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core');?>/images/buttons/frontpage.gif"/><br />Trang ch&#7911;</a></td></tr></table>
<div class="form_content">
<table cellspacing="0" width="100%">
  	<tr>
		<td class="form_detail_label">[[.id.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|id|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.password.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|password|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.email.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|email|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.full_name.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|full_name|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.gender.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|gender|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.active.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|is_active|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.block.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|is_block|]]		</td>
	</tr><tr>
		<td class="form_detail_label">[[.birth_day.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|birth_day|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.address.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|address|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.phone_number.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|phone_number|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.join_date.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|join_date|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.zone_id.]]</td>
		<td>:</td>
		<td class="form_detail_value">[[|zone_id|]]</td>
	</tr>
	</table>
	<?php
	if(URL::get('cmd')!='delete')
	{
	?>
			<fieldset><legend>[[.user_group.]]</legend>
		<table width="100%" class="form_multiple_item_area">
			<tr>
			<th width="300" class="form_multiple_item_label">
					[[.group_id.]]
				</th><th width="100" class="form_multiple_item_label">
					[[.join_date.]]
				</th><th width="50" class="form_multiple_item_label">
					[[.active.]]
				</th>
			</tr>
			<!--LIST:user_group_items-->
			<tr>
			<td width="300" class="form_multiple_item_value">[[|user_group_items.group_id_name|]]</td><td width="100" class="form_multiple_item_value">
					[[|user_group_items.join_date|]]
				</td>
			<td width="50" class="form_multiple_item_value">
					[[|user_group_items.is_active|]]
				</td>
			</tr>
			<!--/LIST:user_group_items-->
		</table>
		</fieldset> <fieldset><legend>[[.account_privilege.]]</legend>
		<table width="100%" class="form_multiple_item_area">
			<tr>
			<th width="300" class="form_multiple_item_label">
					[[.privilege_id.]]
				</th><th width="100" class="form_multiple_item_label">
					[[.parameters.]]
				</th>
			</tr>
			<!--LIST:user_privilege_items-->
			<tr>
			<td width="300" class="form_multiple_item_value">[[|user_privilege_items.privilege_id_name|]]</td><td width="100" class="form_multiple_item_value">
					[[|user_privilege_items.parameters|]]
				</td>
			</tr>
			<!--/LIST:user_privilege_items-->
		</table>
		</fieldset>
	<?php
	}
	?>
	<!--IF:delete(URL::get('cmd')=='delete')-->
	<form name="ManagePortalForm" method="post">
	<input type="hidden" value="1" name="confirm" id="confirm"/>
	<input type="hidden" value="delete" name="cmd" id="cmd"/>
	<input type="hidden" value="<?php echo URL::get('id');?>" name="id"/>
	</form>
	<!--/IF:delete-->
	</div>
</div>
