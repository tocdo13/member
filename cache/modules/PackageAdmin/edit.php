<script language="javascript" type="text/javascript" src="packages/core/includes/js/init_tinyMCE.js"></script>
<script language="javascript" type="text/javascript">
	init_simple_rich_editor("<?php echo String::language_field_list('description');?>");
</script>
<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<span style="display:none">
</span>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('edit_title'):Portal::language('add_title');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(Portal::get_setting('company_name','').' '.$title);?><div class="form_bound">
<table cellpadding="10" width="100%"><tr><td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td>
	<td width="1%" nowrap="nowrap" class="form_title_button"><a href="javascript:void(0)" onclick="EditPackageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>save_button.gif" style="text-align:center"/><br /><?php echo Portal::language('save');?></a></td>
		<td class="form_title_button" width="1%" nowrap="nowrap">
			<a href="javascript:void(0)" onclick="location='<?php echo URL::build_current();?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif"/><br /><?php echo Portal::language('back');?></a></td>
		<?php if($action=='edit'){?><td width="1%" nowrap="nowrap" class="form_title_button">
			<a href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br /><?php echo Portal::language('Delete');?></a></td><?php }?>
		<td width="1%" nowrap="nowrap" class="form_title_button">
			<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
	<div class="form_content">
<?php if(Form::$current->is_error())
		{
		?>		<strong>B&#225;o l&#7895;i</strong><br>
		<?php echo Form::$current->error_messages();?><br>
		<?php
		}
		?>		<form name="EditPackageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<table cellspacing="0" width="100%"><tr><td>
				<div class="tab-pane-1" id="tab-pane-package">
				<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
				<div class="tab-page" id="tab-page-package-<?php echo $this->map['languages']['current']['id'];?>">
					<h2 class="tab"><?php echo $this->map['languages']['current']['name'];?></h2>
					<div class="form_input_label"><?php echo Portal::language('title');?>:</div>
					<div class="form_input">
							<input  name="title_<?php echo $this->map['languages']['current']['id'];?>" id="title_<?php echo $this->map['languages']['current']['id'];?>" style="width:400" type ="text" value="<?php echo String::html_normalize(URL::get('title_'.$this->map['languages']['current']['id']));?>">
					</div>
					<div class="form_input_label"><?php echo Portal::language('type');?>:</div>
					<div class="form_input"><select   name="type" id="type"><?php
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
	</select></div>
					<div class="form_input_label"><?php echo Portal::language('description');?>:</div>
					<div class="form_input">
							<textarea  name="description_<?php echo $this->map['languages']['current']['id'];?>" id="description_<?php echo $this->map['languages']['current']['id'];?>" style="width:100%" rows="10"><?php echo String::html_normalize(URL::get('description_'.$this->map['languages']['current']['id'],''));?></textarea><br />
					</div>
				</div>
				<?php }}unset($this->map['languages']['current']);} ?>
				</div>
				</td></tr></table>
		<div class="form_input_label"><?php echo Portal::language('parent_name');?>:</div>
		<div class="form_input"><select  name="parent_id"><?php
					if(isset($this->map['parent_id_list']))
					{
						foreach($this->map['parent_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))
                    echo "<script>$('parent_id').value = \"".addslashes(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))."\";</script>";
                    ?>
	</select></div>
		<div class="form_input_label"><?php echo Portal::language('name');?>:</div>
		<div class="form_input">
			<input  name="name" id="name" style="width:200" type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">
		</div>
	<input type="hidden" value="1" name="confirm_edit"/>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
</div>
