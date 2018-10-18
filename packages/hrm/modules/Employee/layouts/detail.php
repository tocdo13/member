<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('Delete_user'):Portal::language('User_detail');
$action = (URL::get('cmd')=='delete')?'delete':'detail';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound" style="width:800px;">
<form name="EmployeeForm" method="post">
	<table cellpadding="5" width="100%"><tr><td  class="form_title"><img src="<?php echo Portal::template('core');?>/images/buttons/<?php echo $action;?>_button.gif" align="absmiddle"/><?php echo $title;?></td>
		<?php if(URL::get('cmd')=='delete'){?><td class="form_title_button"><a href="javascript:void(0)" onclick="EmployeeForm.submit();"><img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif" style="text-align:center"/><br />[[.Delete.]]</a></td><?php }else{ if(User::can_edit(false,ANY_CATEGORY)){?><td class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'));?>"><img src="<?php echo Portal::template('core');?>/images/buttons/edit.jpg" style="text-align:center"/><br />[[.Edit.]]</a></td>
		<?php } if(User::can_delete(false,ANY_CATEGORY)){?><td class="form_title_button">
		<a href="<?php echo URL::build_current(array('cmd'=>'delete','id'));?>"><img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif"/><br />[[.Delete.]]</a></td><?php }}?>
		<td class="form_title_button"><a href="<?php echo URL::build_current();?>"><img src="<?php echo Portal::template('core');?>/images/buttons/go_back_button.gif" style="text-align:center"/><br />[[.back.]]</a></td>
		<td class="form_title_button">
	</td></tr></table>
<div class="form_content">
<table cellspacing="0" width="100%">
  	<tr>
		<td class="form_detail_label">[[.id.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|id|]]		</td>
	</tr><tr>
		<td class="form_detail_label">[[.full_name.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|NAME|]]		</td>
	</tr>
	</table>
	<!--/IF:delete-->
  </div>
  <input name="confirm" type="hidden" id="confirm" value="1">
   <input name="id" type="" id="id" value="<?php echo $_REQUEST['id'];?>">
   <input name="cmd" type="hidden" id="cmd" value="delete">
</form>  
</div>
