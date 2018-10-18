<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<script src="packages/core/includes/js/multi_items.js" type="text/javascript"></script>
<span style="display:none">
	<span id="mi_module_table_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_module_table[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi_input">
					<input  name="mi_module_table[#xxxx#][table]" style="width:200px;" type="text" id="table_#xxxx#" >
			</span>
			<span class="multi_input"><span style="width:20;">
				<img src="<?php echo Portal::template('core').'/images/buttons/';?>delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_module_table','#xxxx#','');if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
			</span></span><br>
		</span>
	</span>
	
</span>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('edit_title'):Portal::language('add_title');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title); if(isset([[=type=]])){echo [[=type=]];}?>
<div class="form_bound">
<table cellpadding="10" width="100%">
	<tr>
    	<td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td>
        <td class="form_title_button" width="1%" nowrap="nowrap"><a href="javascript:void(0)" onclick="EditModuleAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>save_button.gif" style="text-align:center"/><br />[[.save.]]</a></td><?php if($action=='edit'){?><td class="form_title_button"><a href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('cmd'=>([[=type=]]=='CONTENT')?'edit_content':(([[=type=]]=='HTML')?'edit_html':'edit_code'),'id'));?>';"><img width="30px" src="<?php echo Portal::template('core').'/images/buttons/';?>edit_button.gif" style="text-align:center"/><br />[[.edit_code.]]</a></td><?php }?>
		<td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)" onclick="location='<?php echo URL::build_current();?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif"/><br />[[.back.]]</a></td>
		<?php if($action=='edit'){?><td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)") onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br />[[.Delete.]]</a></td><?php }?>
		<td class="form_title_button" width="1%" nowrap="nowrap">
			<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::$current->data['module_id'],'href'=>'?'.$_SERVER['QUERY_STRING']));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>

	<div class="form_content">
<?php
 if(Form::$current->is_error())
		{
		?>		<strong>B&#225;o l&#7895;i</strong><br>
		<?php echo Form::$current->error_messages();?><br>
		<?php
		}
		?>		<form name="EditModuleAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>" enctype="multipart/form-data">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table cellspacing="0" width="100%"><tr><td>
				<div class="tab-pane-1" id="tab-pane-module">
				<!--LIST:languages-->
				<div class="tab-page" id="tab-page-module-[[|languages.id|]]">
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
			<input name="name" type="text" id="name" style="width:150">
		</div>
		<div class="form_input_label">[[.image_url.]]:<!--IF:cond(Url::get('id') and ([[=image_url=]]))-->
			<br><img src="[[|image_url|]]">
			<!--/IF:cond--></div>
		<div class="form_input">
			<input name="image_url" type="file" id="image_url">
		</div>
		<div class="form_input_label">[[.package_id.]]:</div>
		<div class="form_input">
				<select name="package_id" id="package_id"></select>
		</div>
		<div class="form_input_label">[[.type.]]:</div>
		<div class="form_input">
				<select name="type" id="type" onchange="if(this.value=='PLUGIN' || this.value=='WRAPPER')$('action_info').style.display='';else $('action_info').style.display='none';if(this.value=='PLUGIN')$('plugin_action_info').style.display='';else $('plugin_action_info').style.display='none';"></select>
		</div>
		<div id="action_info" <?php if(URL::get('type')!='PLUGIN' or URL::get('type')!='WRAPPER')echo 'style="display:none"';?>>
			<div id="plugin_action_info" <?php if(URL::get('type')!='PLUGIN')echo 'style="display:none"';?>>
				<div class="form_input_label">[[.action.]]:</div>
			
				<select name="action" id="action"></select> </div>
			[[.on.]]<select name="action_module_id" id="action_module_id"></select>
		
		</div>
		<div class="form_input_label">[[.use_dblclick.]]:</div>
		<div class="form_input">
			<input name="use_dblclick" id="use_dblclick" type="checkbox" value="1" <?php echo (URL::get('use_dblclick')?'checked':'');?>>
		</div>
		<div class="form_input_label">[[.Update_setting_code.]]:</div>
		<div class="form_input">
			<textarea name="update_setting_code" id="update_setting_code" cols="80" rows="10"></textarea>
		</div>
		<div class="form_input_label">[[.Create_block_code.]]:</div>
		<div class="form_input">
			<textarea name="create_block_code" id="create_block_code" cols="80" rows="10"></textarea>
		</div>
		<div class="form_input_label">[[.Destroy_block_code.]]:</div>
		<div class="form_input">
			<textarea name="destroy_block_code" id="destroy_block_code" cols="80" rows="10"></textarea>
		</div>
		<fieldset><legend>[[.module_table.]]</legend>
				<span id="mi_module_table_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header"><span style="width:200;">[[.table.]]</span></span>
						<span class="multi-input-header"><span style="width:20;"><img src="<?php echo Portal::template('core');?>/images/spacer.gif"/></span></span>
						<br>
					</span>
				</span>
			<input type="button" value="   [[.Add.]]   " onclick="mi_add_new_row('mi_module_table');">
		</fieldset>
		
		
	<input type="hidden" value="1" name="confirm_edit"/>
	</form>
	</div>
	<h2>Use in these pages</h2>
	<ul>
	<!--LIST:using_pages-->
	<li><a target="_blank" href="?page=[[|using_pages.name|]]">[[|using_pages.name|]]</a> [<a target="_blank" href="<?php echo URL::build_current(array('cmd'=>'delete_block','block_id'=>[[=using_pages.id=]]));?>">delete block</a>]</li>
	<!--/LIST:using_pages-->
	</ul>
</div>
<script type="text/javascript">
mi_init_rows('mi_module_table',
	<?php if(isset($_REQUEST['mi_module_table']))
	{
		echo String::array2js($_REQUEST['mi_module_table']);
	}
	else
	{
		echo '{}';
	}
	?>); 

</script>
