<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	color: #FF0000;
}
.style2 {
	font-size: 24;
	color: #FF0000;
}
-->
</style>
<div id="title_region"></div>
<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_title'):Portal::language('detail_title');
$action = (URL::get('cmd')=='delete')?'delete':'detail';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div class="form_bound">
<table cellpadding="0" width="100%"><tr><td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td><?php if(URL::get('cmd')=='delete'){?><td class="form_title_button"><a  onclick="PageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" style="text-align:center" alt=""/><br /><?php echo Portal::language('Delete');?></a></td><?php }else{ if(User::can_edit()){?><td class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit.jpg" style="text-align:center" alt=""/><br /><?php echo Portal::language('Edit');?></a></td><?php } if(User::can_delete()){?><td class="form_title_button">
				<a href="<?php echo URL::build_current(array('portal_id','cmd'=>'delete','id'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br /><?php echo Portal::language('Delete');?></a></td><?php }}?>
				<td class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'',  
	'name'=>isset($_GET['name'])?$_GET['name']:'',    
	  ));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif" style="text-align:center" alt=""/><br /><?php echo Portal::language('back');?></a></td>
				<td class="form_title_button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
<div class="form_content">
<table cellspacing="0" width="100%">
  <tr valign="top" >
    <td class="form_detail_label"><?php echo Portal::language('id');?></td>
    <td width="1">:</td>
    <td class="form_detail_value"><?php echo $this->map['id'];?></td>
  </tr>
  	<tr>
		<td class="form_detail_label"><?php echo Portal::language('name');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['name'];?>
		</td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('title');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['title'];?>
		</td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('params');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['params'];?>
		</td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('description');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['description'];?>
		</td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('package_id');?></td>
		<td>:</td>
		<td class="form_detail_value"><?php echo $this->map['package_id'];?></td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('cachable');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['cachable'];?>
		</td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('cache_param');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['cache_param'];?>
		</td>
	</tr>
	</table>
	<?php
	if(URL::get('cmd')!='delete')
	{
	?>	<?php 
				if(($this->map['module_related_fields']))
				{?>
	<div class="form_related_fields_area">
		<div class="form_related_fields_title"><?php echo Portal::language('module_related');?></div>
		<?php if(isset($this->map['module_related_fields']) and is_array($this->map['module_related_fields'])){ foreach($this->map['module_related_fields'] as $key1=>&$item1){if($key1!='current'){$this->map['module_related_fields']['current'] = &$item1;?>
		<div class="form_related_field"><a href="<?php echo URL::build('module');?>&id=<?php echo $this->map['module_related_fields']['current']['id'];?>"><?php echo $this->map['module_related_fields']['current']['name'];?></a></div>
		<?php }}unset($this->map['module_related_fields']['current']);} ?>
	</div>
	
				<?php
				}
				?> 
	<?php
	}
	?>	<?php 
				if((URL::get('cmd')=='delete'))
				{?>
	<form name="PageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<input type="hidden" value="1" name="confirm"/>
	<input type="hidden" value="delete" name="cmd"/>
	<input type="hidden" value="<?php echo URL::get('id');?>" name="id"/>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	
				<?php
				}
				?>
	</div>
</div>
<div>
	<p><span class="style1">Ch&uacute; &yacute;</span>:  H&#7879; th&#7889;ng s&#7869; x&oacute;a <span class="style2">h&ograve;an to&agrave;n</span> nh&#7919;ng d&#7919; li&#7879;u sau:</p>
	    <li>T&#7845;t c&#7843; block_setting c&#7911;a nh&#7919;ng block thu&#7897;c trang n&agrave;y.</li>
	    <li>T&#7845;t c&#7843; block thu&#7897;c trang n&agrave;y. </li>
</div>
