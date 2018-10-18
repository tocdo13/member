<script src="packages/core/includes/js/init_tinyMCE.js"></script>
<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('Edit'):Portal::language('Add');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(Portal::get_setting('company_name','').' '.$title);?>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC"  class="table-bound">
	<tr>
		<td width="100%" class="form-title"><?php echo $title;?></td>
		<td width="" align="right"><a class="button-medium-save" onclick="Edithelp_listForm.submit();">[[.save.]]</a></td>
        <td><a class="button-medium-back" onclick="location='<?php echo URL::build_current();?>';">[[.back.]]</a></td>
		<?php if($action=='edit' and User::can_delete(false,ANY_CATEGORY)){?>
		<td><a class="button-medium-delete" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';">[[.Delete.]]</a></td>
		<?php }?>
	</tr>
</table>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">	
	<tr>
	<td style="width:100%" valign="top">
	<?php if(Form::$current->is_error())
	{
	?>
	<strong>B&#225;o l&#7895;i</strong><br>
	<?php echo Form::$current->error_messages();?><br>
	<?php
	}
	?>
	<form name="Edithelp_listForm" method="post" enctype="multipart/form-data" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<input type="hidden" name="confirm_edit" value="1" />
	<table cellspacing="0" width="100%"><tr><td>
	<div class="tab-pane-1" id="tab-pane-help_list">
	<!--LIST:languages-->
	<div class="tab-page" id="tab-page-help_list-[[|languages.id|]]">
	<h2 class="tab">[[|languages.name|]]</h2>
	<div class="form_input_label">[[.name.]]:</div>
	<div class="form_input">
	<input name="name_[[|languages.id|]]" type="text" id="name_[[|languages.id|]]" style="width:400" >
	</div>	
	<div class="form_input_label">[[.description.]]:</div>
	<div class="form_input">
	<textarea name="description_[[|languages.id|]]" id="description_[[|languages.id|]]" style="width:100%" rows="20" ></textarea><br />
	<script>
		init_simple_rich_editor('description_[[|languages.id|]]');
	</script>
	</div>
	</div>
	<!--/LIST:languages-->
	</div>
	</td></tr></table>
	<div class="form_input_label">[[.parent_name.]]:</div>
	<div class="form_input">
	<select name="parent_id" id="parent_id"></select></div>
	<div class="form_input_label">[[.type.]]:</div>
	<div class="form_input">
	<select name="type" id="type" ></select>
	</div>
	<div class="form_input_label">[[.url.]]:</div>
	<div class="form_input">
	<input name="url" type="text" id="url" style="width:400">
	</div>
	<div class="form_input_label">[[.url_popup.]]:</div>
	<div class="form_input">
	<input name="url_popup"  type="checkbox" id="url_popup" <?php if(Url::get('url_popup')){?>checked="checked"<?php }?>>
	</div>
	<div class="form_input_label">[[.group_name.]]:</div>
	<div class="form_input">
	<input name="group_name" type="text" id="group_name">
	</div>
	<div class="form_input_label">[[.status.]]:</div>
	<div class="form_input">
	<select name="status" id="status"></select>
	</div><div class="form_input_label">[[.icon_url.]]:</div>
	<div class="form_input">
	<img style="padding:0 0 0 0" id="img_icon_url" src="<?php echo URL::get('icon_url')?URL::get('icon_url'):Portal::template('cms').'/images/spacer.gif';?>" height="100" width="120" border="0">
	<input name="icon_url" type="hidden" id="icon_url">
	<input name="file_icon_url" style="width:30px;border:0px solid white;" type="file" id="file_icon_url" onchange="$('img_icon_url').src='file:///'+this.value;" >
	<input type="image" src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="$('icon_url').value = '';$('file_icon_url').value='';$('img_icon_url').src='<?php echo Portal::template('cms');?>/images/spacer.gif';event.returnValue=false;">
	</div>
	</form>
	</td>
	<td valign="top">
	</td>
	</tr>
</table>
