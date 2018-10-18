<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_title'):Portal::language('list_title');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(Portal::get_setting('company_name','').' '.$title);?>
<div class="form_bound">
<table cellpadding="10" width="100%">
	<tr valign="bottom">
    	<td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td><?php 
	if(URL::get('cmd')=='delete'){?><td width="1%" nowrap="nowrap" class="form_title_button"><a  onclick="ListPackageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" style="text-align:center" alt=""/><br /><?php echo Portal::language('Delete');?></a></td>
	<td class="form_title_button" width="1%" nowrap="nowrap"><a href="<?php echo URL::build_current(array('name'=>isset($_GET['name'])?$_GET['name']:''));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif" style="text-align:center" alt=""/><br /><?php echo Portal::language('back');?></a></td><?php }
	else{ if(User::can_add()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array(	'name'=>isset($_GET['name'])?$_GET['name']:'')+array('cmd'=>'add'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>add_button.gif" style="text-align:center" alt=""/><br /><?php echo Portal::language('Add');?></a></td><?php }?><?php 
	if(User::can_edit()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'make_library_cache'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>make_cache_button.gif" style="text-align:center"/><br /><?php echo Portal::language('make_cache');?></a></td>
	<td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'delete_cache'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_cache_button.gif" style="text-align:center"/><br /><?php echo Portal::language('delete_module_cache');?></a></td><?php }?><?php 
	if(User::can_delete()){?><td width="1%" nowrap="nowrap" class="form_title_button">
				<a  onclick="ListPackageAdminForm.cmd.value='delete';ListPackageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br /><?php echo Portal::language('Delete');?></a></td><?php }}?>
				<td width="1%" nowrap="nowrap" class="form_title_button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
		
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<form method="post" name="SearchPackageAdminForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					<table>
						<tr><td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('name');?></td>
						<td>:</td>
						<td nowrap>
							<input type="hidden" name="page_no" value="1" />
							<input  name="name" id="name" style="width:200" type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>"><input type="submit" value="   <?php echo Portal::language('search');?>   ">
						</td></tr>
					</table>
					<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
					<form name="ListPackageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					<a name="top_anchor"></a>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
					
						<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
							<th width="1%" title="<?php echo Portal::language('check_all');?>"><input type="checkbox" value="1" id="PackageAdmin_all_checkbox" onclick="select_all_checkbox(this.form, 'PackageAdmin',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='package.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'package.name'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='package.name') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								<?php echo Portal::language('name');?>
								</a>
							</th><th nowrap align="left">
								<a title="<?php echo Portal::language('sort');?>" href="<?php echo URL::build_current(((URL::get('order_by')=='package.title_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'package.title_'.Portal::language()));?>" >
								<?php if(URL::get('order_by')=='package.title_'.Portal::language()) echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								<?php echo Portal::language('title');?>
								</a>
							</th>
							<th>&nbsp;</th>
							<?php if(User::can_edit())
							{
							?><th>&nbsp;</th><?php
							}?>							<?php if(User::can_edit())
							{?>							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<?php }?>						</tr>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" id="PackageAdmin_tr_<?php echo $this->map['items']['current']['id'];?>">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'PackageAdmin',this,'#FFFFEC','white');" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>';">
									<?php echo $this->map['items']['current']['indent'];?>
									<?php echo $this->map['items']['current']['indent_image'];?>
									<span class="page_indent">&nbsp;</span>
									<?php echo $this->map['items']['current']['name'];?>
								</td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>';">
									<?php echo $this->map['items']['current']['title'];?>
								</td>
								<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>';">
									<?php echo $this->map['items']['current']['type'];?>
								</td>
							    <?php 
							if(User::can_edit())
							{
							?>	<td width="24px" align="center">
								<a href=" <?php echo Url::build_current(array(
	'name'=>isset($_GET['name'])?$_GET['name']:'', 
	  )+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit.gif" alt="<?php echo Portal::language('Edit');?>" width="12" height="12" border="0"></a></td>
							<?php
							}
							?>							<td width="24px" align="center"><?php echo $this->map['items']['current']['move_up'];?></td>
							<td width="24px" align="center"><?php echo $this->map['items']['current']['move_down'];?></td>
						</tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
					
					<input type="hidden" name="cmd" value="delete"/>
<input type="hidden" name="page_no" value="1"/>
<?php 
				if((URL::get('cmd')=='delete'))
				{?>
					<input type="hidden" name="confirm" value="1" />
					
				<?php
				}
				?>
					<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
				</td>
			</tr>
			</table>
			<table width="100%"><tr>
			<td width="100%">
				<?php echo Portal::language('select');?>:&nbsp;
				<a  onclick="select_all_checkbox(document.ListPackageAdminForm,'PackageAdmin',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
				<a  onclick="select_all_checkbox(document.ListPackageAdminForm,'PackageAdmin',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
				<a  onclick="select_all_checkbox(document.ListPackageAdminForm,'PackageAdmin',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
			</td>
			<td>
				<a name="bottom_anchor" javascript:void(0)><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"></a>
			</td>
			</tr></table>
			
		</td>
</tr>
	</table>	
</div>