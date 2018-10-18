<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<span style="display:none">
</span>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('edit_title'):Portal::language('add_title');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title);?><div class="form_bound">
<table cellpadding="10" width="100%">
	<tr>
    	<td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td>
        <td class="form_title_button" width="1%" nowrap="nowrap"><a href="javascript:void(0)" onclick="EditPageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>save_button.gif" style="text-align:center"/><br />[[.save.]]</a></td>
		<td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('portal_id','package_id'));?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif"/><br />[[.back.]]</a></td>
		<?php if($action=='edit'){?><td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('portal_id','package_id','cmd'=>'delete','id'));?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br />[[.Delete.]]</a></td><?php }?>
		<td class="form_title_button" width="1%" nowrap="nowrap">
			<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
	<div class="form_content">
<?php if(Form::$current->is_error())
		{
		?>		<strong>B&#225;o l&#7895;i</strong><br>
		<?php echo Form::$current->error_messages();?><br>
		<?php
		}
		?>		
	<form name="EditPageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<table cellspacing="0" width="100%"><tr><td>
				<div class="tab-pane-1" id="tab-pane-page">
				<!--LIST:languages-->
				<div class="tab-page" id="tab-page-page-[[|languages.id|]]">
					<h2 class="tab">[[|languages.name|]]</h2>
					<div class="form_input_label">[[.title.]]:</div>
					<div class="form_input">
							<input name="title_[[|languages.id|]]" type="text" id="title_[[|languages.id|]]" style="width:400">
					</div><div class="form_input_label">[[.description.]]:</div>
					<div class="form_input">
							<textarea name="description_[[|languages.id|]]" id="description_[[|languages.id|]]" style="width:100%" rows="10"></textarea><br />
					</div>
				</div>
				<!--/LIST:languages-->
				</div>
				</td></tr></table>
		<div class="form_input_label">[[.name.]]:</div>
		<div class="form_input">
			<input name="name" type="text" id="name" style="width:300">
		</div><div class="form_input_label">[[.params.]]:</div>
		<div class="form_input">
			<input name="params" type="text" id="params" style="width:300">
		</div><div class="form_input_label">[[.package_id.]]:</div>
		<div class="form_input">
			<select name="package_id" id="package_id"></select>
		</div><div class="form_input_label">[[.layout.]]:</div>
		<div class="form_input">
			<select name="layout" id="layout"></select>
		</div><div class="form_input_label">[[.type.]]:</div>
		<div class="form_input">
			<select name="type" id="type"></select>
		</div>
        <div class="form_input_label">[[.cacheable.]]:</div>
		<div class="form_input">
			<input name="cacheable" id="cacheable" type="checkbox" value="1" <?php echo (URL::get('cacheable')?'checked':'');?>>
		</div>
        <div class="form_input_label">[[.is_use_sapi.]]:</div>
		<div class="form_input">
			<input name="is_use_sapi" id="is_use_sapi" type="checkbox" value="1" <?php echo (URL::get('is_use_sapi')?'checked':'');?>>
		</div>
		<div class="form_input_label">[[.cache_param.]]:</div>
		<div class="form_input">
			<input name="cache_param" type="text" id="cache_param" style="width:300">
		</div>
		<div class="form_input_label">[[.condition.]]:</div>
		<div class="form_input">
			<textarea name="condition" id="condition" style="width:300px;height:100px"></textarea>
		</div>
		<input type="hidden" value="1" name="confirm_edit"/>
		<input name="save" type="submit" value="[[.Save.]]" style="width:200px;height:30px"> 
	</form>
	</div>
</div>
