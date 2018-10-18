<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_title'):Portal::language('list_title');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div id="title_region"></div>
<div class="form_bound">
<table cellpadding="10" width="100%">
	<tr>
    	<td class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td>
		<?php if(URL::get('cmd')=='delete'){?><td width="1%" nowrap="nowrap" class="form_title_button"><a  onclick="ListPageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" style="text-align:center" width="24px" height="24px" alt=""/><br /><?php echo Portal::language('Delete');?></a></td>
        <td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 'name'=>isset($_GET['name'])?$_GET['name']:''));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif" style="text-align:center" alt=""/><br /><?php echo Portal::language('back');?></a></td><?php }else{ 
		if(User::can_edit()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id','cmd'=>'delete_all_cache'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_cache_button.gif" style="text-align:center"/><br /><?php echo Portal::language('delete_cache');?></a></td><?php } 
		if(User::can_add()){?><td width="1%" nowrap="nowrap" class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 'name'=>isset($_GET['name'])?$_GET['name']:'')+array('cmd'=>'add'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>add_button.gif" style="text-align:center"  width="24px" height="24px" alt=""/><br /><?php echo Portal::language('Add');?></a></td>
        <td width="1%" nowrap="nowrap" class="form_title_button" align="center"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 'name'=>isset($_GET['name'])?$_GET['name']:'')+array('cmd'=>'add_blocks'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>add.jpg" style="text-align:center" alt="" width="24px" height="24px"/><br /><?php echo Portal::language('Add_block');?></a></td><?php }?>
		<?php if(User::can_delete()){?><td width="1%" nowrap="nowrap" class="form_title_button">
				<a  onclick="ListPageAdminForm.cmd.value='delete';ListPageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" width="24px" height="24px" alt=""/><br /><?php echo Portal::language('Delete');?></a></td><?php }}?>
				<td width="1%" nowrap="nowrap" class="form_title_button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
	<div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td nowrap bgcolor="#EFEFEF">
			<table width="100%" cellpadding="0" cellspacing="0" class="home-item-category-tree">
			<?php if(isset($this->map['packages']) and is_array($this->map['packages'])){ foreach($this->map['packages'] as $key1=>&$item1){if($key1!='current'){$this->map['packages']['current'] = &$item1;?>
			<tr>
			  <td nowrap>
				<a href="<?php echo URL::build_current(array('portal_id'));?>&package_id=<?php echo $this->map['packages']['current']['id'];?>" class="home-news-category-level<?php if(URL::get('package_id')==$this->map['packages']['current']['id'])echo '_selected'.$this->map['packages']['current']['level'];else echo $this->map['packages']['current']['level'];?>"><?php echo $this->map['packages']['current']['name'];?></a>
			</td>
			</tr>
			<?php }}unset($this->map['packages']['current']);} ?>
			</table>
			<a target="_blank" href="<?php echo Url::build('package');?>" class="category_level1"><?php echo Portal::language('packages/admin');?></a>  
		</td>
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<form method="post" name="SearchPageAdminForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					<?php echo Portal::language('name');?>: <input  name="name" id="name" style="width:200" type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>"> <?php echo Portal::language('portal');?>: <input  name="portal_id" id="portal_id" style="width:120" type ="text" value="<?php echo String::html_normalize(URL::get('portal_id'));?>">  <input type="submit" value="<?php echo Portal::language('search');?>">
					<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
					<form name="ListPageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
					<a name="top_anchor"></a>
					<div style="border:2px solid #FFFFFF;">
					<table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
					
						<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
							<th width="1%" title="<?php echo Portal::language('check_all');?>"><input type="checkbox" value="1" id="PageAdmin_all_checkbox" onclick="select_all_checkbox(this.form, 'PageAdmin',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th>&nbsp;</th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='PAGE.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'PAGE.name'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='page.name') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								<?php echo Portal::language('name');?>
								</a>
							</th>
							<th nowrap align="left">
								<a title="<?php echo Portal::language('sort');?>" href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='PAGE.TITLE_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'PAGE.TITLE_'.Portal::language()));?>" >
								<?php if(URL::get('order_by')=='PAGE.TITLE_'.Portal::language()) echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								<?php echo Portal::language('title');?>
								</a>
							</th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='package_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'package_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='package_id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								<?php echo Portal::language('package_id');?>
								</a>
							</th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(array('portal_id')+((URL::get('order_by')=='PARAMS' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'PARAMS'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='PARAMS') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif" alt="">';?>								<?php echo Portal::language('params');?>
								</a>
							</th>
							<?php if(User::can_edit())
							{
							?><th>&nbsp;</th><th>&nbsp;</th><?php
							}?>						</tr>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#EFFFDF';} else {echo $this->map['items']['current']['is_sibling']?'#FFFFDF':'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;" id="PageAdmin_tr_<?php echo $this->map['items']['current']['id'];?>">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'PageAdmin',this,'#FFFFEC','white');" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td><a target="_blank" href="<?php echo URL::build($this->map['items']['current']['name']);?>&<?php echo $this->map['items']['current']['params'];?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>select.jpg" /></a></td>
							<td nowrap align="left" onclick="location='<?php echo $this->map['items']['current']['href'];?>';">
									<?php echo $this->map['items']['current']['name'];?>
							</td>
							<td align="left" onclick="location='<?php echo $this->map['items']['current']['href'];?>';">
								<?php echo $this->map['items']['current']['name'];?>
							</td>
							<td nowrap align="left" onclick="location='<?php echo $this->map['items']['current']['href'];?>';">
								<?php echo $this->map['items']['current']['package_id'];?>
							</td>
							<td nowrap align="left" onclick="location='<?php echo $this->map['items']['current']['href'];?>';">
								<?php echo $this->map['items']['current']['params'];?>
							</td>
							<?php 
							if(User::can_edit())
							{
							?>							<td width="24px" align="center">
								<a href="<?php echo Url::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'',  
	'name'=>isset($_GET['name'])?$_GET['name']:'',    
	  )+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit.gif" alt="<?php echo Portal::language('Edit');?>" width="12" height="12" border="0"></a></td>
	  						<td nowrap><a href="<?php echo Url::build_current(array('portal_id','package_id', 'name')+array('cmd'=>'duplicate','id'=>$this->map['items']['current']['id'])); ?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>duplicate.gif" alt="<?php echo Portal::language('duplicate');?>" width="13" height="13" border="0"></a></td>
							<?php
							}
							?>						</tr>
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
			<?php echo $this->map['paging'];?>
			<table width="100%"><tr>
			<td width="100%">
				<?php echo Portal::language('select');?>:&nbsp;
				<a  onclick="select_all_checkbox(document.ListPageAdminForm,'PageAdmin',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
				<a  onclick="select_all_checkbox(document.ListPageAdminForm,'PageAdmin',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
				<a  onclick="select_all_checkbox(document.ListPageAdminForm,'PageAdmin',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
			</td>
			<td>
				<a name="bottom_anchor" javascript:void(0)><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"></a>
			</td>
			</tr></table>
			
		</td>
</tr>
	</table>	
	</div>
</div>
