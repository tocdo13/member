<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('Edit'):Portal::language('Add');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(Portal::get_setting('company_name','').' '.$title);?>
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC"  class="table-bound">
	<tr height="40">
		<td width="90" class="form-title"><?php echo $title;?></td>
		<td width="1%" align="right"><a class="button-medium-save" onclick="EditCategoryForm.submit();">[[.save.]]</a></td>
        <td width="1%"><a class="button-medium-back" onclick="location='<?php echo URL::build_current();?>';">[[.back.]]</a></td>
		<?php if($action=='edit' and User::can_delete(false,ANY_CATEGORY)){?>
		<td width="1%"><a class="button-medium-delete" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';">[[.Delete.]]</a></td>
		<?php }?>
	</tr>
</table>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">	
	<tr>
	<td style="width:100%" valign="top">
	<?php if(Form::$current->is_error()){?><?php echo Form::$current->error_messages();?><br><?php }?>
	<form name="EditCategoryForm" method="post" enctype="multipart/form-data" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<input type="hidden" name="confirm_edit" value="1" />
	<table cellspacing="0" width="100%"><tr><td>
	<div class="tab-pane-1" id="tab-pane-category">
	<!--LIST:languages-->
	<div class="tab-page" id="tab-page-category-[[|languages.id|]]">
	<h2 class="tab">[[|languages.name|]]</h2>
	<div class="form-input-label">[[.name.]]:</div>
	<div class="form-input">
	<input name="name_[[|languages.id|]]" type="text" id="name_[[|languages.id|]]" style="width:400px;">
	</div>
	<div class="form-input-label">[[.description.]]:</div>
	<div class="form-input">
	<textarea name="description_[[|languages.id|]]" id="description_[[|languages.id|]]" style="width:99%;" rows="10" ></textarea><br />
     <script>simple_mce('description_[[|languages.id|]]');</script>
	</div>
	</div>
	<!--/LIST:languages-->
	</div>
	</td></tr></table>
	
	<div class="form-input-label"><strong>[[.parent_name.]]:</strong></div>
	<div class="form-input">
	<select name="parent_id" id="parent_id"></select></div>
	<div class="form-input-label"><strong>[[.status.]]:</strong></div>
	<div class="form-input">
	<select name="status" id="status"></select>
	</div>
	<div class="form-input-label"><strong>[[.attachment_url.]]:</strong></div>
	<div class="form-input">
    <!--IF:cond_attachment(isset([[=attachment_file=]]) and [[=attachment_file=]])-->
    <img src="skins/default/images/icon/308.gif" width="50px" id="img_attachment_file" title="<?php $pathinfo = pathinfo([[=attachment_file=]]); echo $pathinfo['basename'];?>" />
    <br />
    File attach: <strong><?php echo $pathinfo['basename'];?></strong><br />
    <!--/IF:cond_attachment-->
	<input name="attachment_file" type="hidden" id="attachment_file">
	<input name="file_attachment_file" type="file" id="file_attachment_file">
	<input type="image" src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="$('attachment_file').value = '';$('file_attachment_file').value='';$('img_attachment_file').src='<?php echo Portal::template('cms');?>/images/spacer.gif';event.returnValue=false;$('delete_file').value=1;">
	</div>
    <input name="delete_file" type="hidden" id="delete_file" value="0" />
	</form>
	</td>
	<td valign="top">
	</td>
	</tr>
</table>
