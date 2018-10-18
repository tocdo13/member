<script language="javascript">
packages = {
'':''
<?php if(isset($this->map['packages']) and is_array($this->map['packages'])){ foreach($this->map['packages'] as $key1=>&$item1){if($key1!='current'){$this->map['packages']['current'] = &$item1;?>
,<?php echo $this->map['packages']['current']['id'];?>:{
	'':''
	<?php if(isset($this->map['packages']['current']['modules']) and is_array($this->map['packages']['current']['modules'])){ foreach($this->map['packages']['current']['modules'] as $key2=>&$item2){if($key2!='current'){$this->map['packages']['current']['modules']['current'] = &$item2;?>
	,<?php echo $this->map['packages']['current']['modules']['current']['id'];?>:'<?php echo $this->map['packages']['current']['modules']['current']['name'];?>'
	<?php }}unset($this->map['packages']['current']['modules']['current']);} ?>
}
<?php }}unset($this->map['packages']['current']);} ?>
};
block_moved = false;
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="white">
<tr>
<td style="padding-left:10px;">
	<table cellpadding="5px" align="left">
	<tr>
	<td><a href="<?php echo URL::build($this->map['name']);?>&<?php echo $this->map['params'];?>"><?php echo $this->map['name'];?> - <?php echo $this->map['title'];?></a></td>
	
	<td><a target="_blank" href="<?php echo URL::build('layout');?>&cmd=edit&id=<?php echo $this->map['layout'];?>"><?php echo Portal::language('edit_layout');?></a></td>
	<td>
	  <a href="<?php echo Url::build('page',array('id','cmd'=>'refresh','href'=>'?'.$_SERVER['QUERY_STRING']));?>">Xo&#225; cache</a>
	  &nbsp;|&nbsp;<a href="<?php echo Url::build('page',array('cmd'=>'edit','id'));?>">S&#7917;a</a>
	  &nbsp;|&nbsp;<a href="<?php echo Url::build('page');?>&package_id=<?php echo $this->map['package_id'];?>">Danh s&#225;ch trang</a>
	  </td>
	</tr>
	</table>
</td>
</tr>
<tr><td style="padding-left:10px;">
<table width="100%"><tr>
	<td><b>Layout:</b></td>
	<td width="100%" align="left">
	<select  name="layout" id="layout" onchange="change_layout(this.value);"><?php
					if(isset($this->map['layout_list']))
					{
						foreach($this->map['layout_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('layout',isset($this->map['layout'])?$this->map['layout']:''))
                    echo "<script>$('layout').value = \"".addslashes(URL::get('layout',isset($this->map['layout'])?$this->map['layout']:''))."\";</script>";
                    ?>
	</select>
	<script type="text/javascript">	
		function change_layout(id)
		{
			location='<?php echo URL::build('edit_page');?>&id=<?php echo $this->map['id'];?>&cmd=change_layout&new_layout='+id;
		}
		function change_package(id)
		{
			while ($('module_id').length> 0) {
				$('module_id').remove(0);
			}
			
			if(packages[id])
			{
				for(var i in packages[id])
				{
					$('module_id').add(new Option(packages[id][i],i));
				}
			}
		}
		current_select_module = '';
	</script>
	</td>
	</tr></table>
</td></tr>
<tr>
<td style="line-height:20px;">
	Package:
	<select  name="package_id" id="package_id" onchange="change_package(this.value);"><?php
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
	&nbsp;
	<a href = "#" ondragstart="event.dataTransfer.setData('Text', '-'+$('module_id').value);event.dataTransfer.effectAllowed = 'copy';">Modules</a>
	: 
	<select  name="module_id" id="module_id"><?php
					if(isset($this->map['module_id_list']))
					{
						foreach($this->map['module_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('module_id',isset($this->map['module_id'])?$this->map['module_id']:''))
                    echo "<script>$('module_id').value = \"".addslashes(URL::get('module_id',isset($this->map['module_id'])?$this->map['module_id']:''))."\";</script>";
                    ?>
	</select>
	<script language="javascript">
	change_package(<?php echo $this->map['package_id'];?>);
	</script>
	<br />
	<?php if(isset($this->map['new_modules']) and is_array($this->map['new_modules'])){ foreach($this->map['new_modules'] as $key3=>&$item3){if($key3!='current'){$this->map['new_modules']['current'] = &$item3;?>
	&nbsp;&nbsp;<a href = "#" ondragstart="event.dataTransfer.setData('Text', '-<?php echo $this->map['new_modules']['current']['id'];?>');event.dataTransfer.effectAllowed = 'copy';"><?php echo $this->map['new_modules']['current']['name'];?></a>
	<?php }}unset($this->map['new_modules']['current']);} ?>
</td>
</tr>
<tr>
	<td align="center"><?php echo $this->map['regions'];?></td>
</tr>
<tr>
<td>
	&nbsp;Modules: 
	<?php if(isset($this->map['new_modules']) and is_array($this->map['new_modules'])){ foreach($this->map['new_modules'] as $key4=>&$item4){if($key4!='current'){$this->map['new_modules']['current'] = &$item4;?>
	&nbsp;&nbsp;<a href = "#" ondragstart="event.dataTransfer.setData('Text', '-<?php echo $this->map['new_modules']['current']['id'];?>');event.dataTransfer.effectAllowed = 'copy';"><?php echo $this->map['new_modules']['current']['name'];?></a>
	<?php }}unset($this->map['new_modules']['current']);} ?>
</td>
</tr>
</table>
