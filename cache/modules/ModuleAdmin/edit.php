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
System::set_page_title(HOTEL_NAME.' - '.$title); if(isset($this->map['type'])){echo $this->map['type'];}?>
<div class="form_bound">
<table cellpadding="10" width="100%">
	<tr>
    	<td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td>
        <td class="form_title_button" width="1%" nowrap="nowrap"><a href="javascript:void(0)" onclick="EditModuleAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>save_button.gif" style="text-align:center"/><br /><?php echo Portal::language('save');?></a></td><?php if($action=='edit'){?><td class="form_title_button"><a href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('cmd'=>($this->map['type']=='CONTENT')?'edit_content':(($this->map['type']=='HTML')?'edit_html':'edit_code'),'id'));?>';"><img width="30px" src="<?php echo Portal::template('core').'/images/buttons/';?>edit_button.gif" style="text-align:center"/><br /><?php echo Portal::language('edit_code');?></a></td><?php }?>
		<td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)" onclick="location='<?php echo URL::build_current();?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif"/><br /><?php echo Portal::language('back');?></a></td>
		<?php if($action=='edit'){?><td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)") onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br /><?php echo Portal::language('Delete');?></a></td><?php }?>
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
				<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
				<div class="tab-page" id="tab-page-module-<?php echo $this->map['languages']['current']['id'];?>">
					<h2 class="tab"><?php echo $this->map['languages']['current']['name'];?></h2>
					<div class="form_input_label"><?php echo Portal::language('title');?>:</div>
					<div class="form_input">
							<input  name="title_<?php echo $this->map['languages']['current']['id'];?>" id="title_<?php echo $this->map['languages']['current']['id'];?>" style="width:400" type ="text" value="<?php echo String::html_normalize(URL::get('title_'.$this->map['languages']['current']['id']));?>">
					</div><div class="form_input_label"><?php echo Portal::language('description');?>:</div>
					<div class="form_input">
							<textarea  name="description_<?php echo $this->map['languages']['current']['id'];?>" id="description_<?php echo $this->map['languages']['current']['id'];?>" style="width:100%" rows="10"><?php echo String::html_normalize(URL::get('description_'.$this->map['languages']['current']['id'],''));?></textarea><br />
					</div>
				</div>
				<?php }}unset($this->map['languages']['current']);} ?>
				</div>
				</td></tr></table>
		<div class="form_input_label"><?php echo Portal::language('name');?>:</div>
		<div class="form_input">
			<input  name="name" id="name" style="width:150" type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">
		</div>
		<div class="form_input_label"><?php echo Portal::language('image_url');?>:<?php 
				if((Url::get('id') and ($this->map['image_url'])))
				{?>
			<br><img src="<?php echo $this->map['image_url'];?>">
			
				<?php
				}
				?></div>
		<div class="form_input">
			<input  name="image_url" id="image_url" type ="file" value="<?php echo String::html_normalize(URL::get('image_url'));?>">
		</div>
		<div class="form_input_label"><?php echo Portal::language('package_id');?>:</div>
		<div class="form_input">
				<select  name="package_id" id="package_id"><?php
					if(isset($this->map['package_id_list']))
					{
						foreach($this->map['package_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('package_id',isset($this->map['package_id'])?$this->map['package_id']:''))
                    echo "<script>$('package_id').value = \"".addslashes(URL::get('package_id',isset($this->map['package_id'])?$this->map['package_id']:''))."\";</script>";
                    ?>
	</select>
		</div>
		<div class="form_input_label"><?php echo Portal::language('type');?>:</div>
		<div class="form_input">
				<select  name="type" id="type" onchange="if(this.value=='PLUGIN' || this.value=='WRAPPER')$('action_info').style.display='';else $('action_info').style.display='none';if(this.value=='PLUGIN')$('plugin_action_info').style.display='';else $('plugin_action_info').style.display='none';"><?php
					if(isset($this->map['type_list']))
					{
						foreach($this->map['type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('type',isset($this->map['type'])?$this->map['type']:''))
                    echo "<script>$('type').value = \"".addslashes(URL::get('type',isset($this->map['type'])?$this->map['type']:''))."\";</script>";
                    ?>
	</select>
		</div>
		<div id="action_info" <?php if(URL::get('type')!='PLUGIN' or URL::get('type')!='WRAPPER')echo 'style="display:none"';?>>
			<div id="plugin_action_info" <?php if(URL::get('type')!='PLUGIN')echo 'style="display:none"';?>>
				<div class="form_input_label"><?php echo Portal::language('action');?>:</div>
			
				<select  name="action" id="action"><?php
					if(isset($this->map['action_list']))
					{
						foreach($this->map['action_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('action',isset($this->map['action'])?$this->map['action']:''))
                    echo "<script>$('action').value = \"".addslashes(URL::get('action',isset($this->map['action'])?$this->map['action']:''))."\";</script>";
                    ?>
	</select> </div>
			<?php echo Portal::language('on');?><select  name="action_module_id" id="action_module_id"><?php
					if(isset($this->map['action_module_id_list']))
					{
						foreach($this->map['action_module_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('action_module_id',isset($this->map['action_module_id'])?$this->map['action_module_id']:''))
                    echo "<script>$('action_module_id').value = \"".addslashes(URL::get('action_module_id',isset($this->map['action_module_id'])?$this->map['action_module_id']:''))."\";</script>";
                    ?>
	</select>
		
		</div>
		<div class="form_input_label"><?php echo Portal::language('use_dblclick');?>:</div>
		<div class="form_input">
			<input name="use_dblclick" id="use_dblclick" type="checkbox" value="1" <?php echo (URL::get('use_dblclick')?'checked':'');?>>
		</div>
		<div class="form_input_label"><?php echo Portal::language('Update_setting_code');?>:</div>
		<div class="form_input">
			<textarea  name="update_setting_code" id="update_setting_code" cols="80" rows="10"><?php echo String::html_normalize(URL::get('update_setting_code',''));?></textarea>
		</div>
		<div class="form_input_label"><?php echo Portal::language('Create_block_code');?>:</div>
		<div class="form_input">
			<textarea  name="create_block_code" id="create_block_code" cols="80" rows="10"><?php echo String::html_normalize(URL::get('create_block_code',''));?></textarea>
		</div>
		<div class="form_input_label"><?php echo Portal::language('Destroy_block_code');?>:</div>
		<div class="form_input">
			<textarea  name="destroy_block_code" id="destroy_block_code" cols="80" rows="10"><?php echo String::html_normalize(URL::get('destroy_block_code',''));?></textarea>
		</div>
		<fieldset><legend><?php echo Portal::language('module_table');?></legend>
				<span id="mi_module_table_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header"><span style="width:200;"><?php echo Portal::language('table');?></span></span>
						<span class="multi-input-header"><span style="width:20;"><img src="<?php echo Portal::template('core');?>/images/spacer.gif"/></span></span>
						<br>
					</span>
				</span>
			<input type="button" value="   <?php echo Portal::language('Add');?>   " onclick="mi_add_new_row('mi_module_table');">
		</fieldset>
		
		
	<input type="hidden" value="1" name="confirm_edit"/>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
	<h2>Use in these pages</h2>
	<ul>
	<?php if(isset($this->map['using_pages']) and is_array($this->map['using_pages'])){ foreach($this->map['using_pages'] as $key2=>&$item2){if($key2!='current'){$this->map['using_pages']['current'] = &$item2;?>
	<li><a target="_blank" href="?page=<?php echo $this->map['using_pages']['current']['name'];?>"><?php echo $this->map['using_pages']['current']['name'];?></a> [<a target="_blank" href="<?php echo URL::build_current(array('cmd'=>'delete_block','block_id'=>$this->map['using_pages']['current']['id']));?>">delete block</a>]</li>
	<?php }}unset($this->map['using_pages']['current']);} ?>
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
