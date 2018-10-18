<script language="javascript">
packages = {
'':''
<!--LIST:packages-->
,[[|packages.id|]]:{
	'':''
	<!--LIST:packages.modules-->
	,[[|packages.modules.id|]]:'[[|packages.modules.name|]]'
	<!--/LIST:packages.modules-->
}
<!--/LIST:packages-->
};
block_moved = false;
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="white">
<tr>
<td style="padding-left:10px;">
	<table cellpadding="5px" align="left">
	<tr>
	<td><a href="<?php echo URL::build([[=name=]]);?>&[[|params|]]">[[|name|]] - [[|title|]]</a></td>
	
	<td><a target="_blank" href="<?php echo URL::build('layout');?>&cmd=edit&id=[[|layout|]]">[[.edit_layout.]]</a></td>
	<td>
	  <a href="<?php echo Url::build('page',array('id','cmd'=>'refresh','href'=>'?'.$_SERVER['QUERY_STRING']));?>">Xo&#225; cache</a>
	  &nbsp;|&nbsp;<a href="<?php echo Url::build('page',array('cmd'=>'edit','id'));?>">S&#7917;a</a>
	  &nbsp;|&nbsp;<a href="<?php echo Url::build('page');?>&package_id=[[|package_id|]]">Danh s&#225;ch trang</a>
	  </td>
	</tr>
	</table>
</td>
</tr>
<tr><td style="padding-left:10px;">
<table width="100%"><tr>
	<td><b>Layout:</b></td>
	<td width="100%" align="left">
	<select name="layout" id="layout" onchange="change_layout(this.value);"></select>
	<script type="text/javascript">	
		function change_layout(id)
		{
			location='<?php echo URL::build('edit_page');?>&id=[[|id|]]&cmd=change_layout&new_layout='+id;
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
	<select name="package_id" id="package_id" onchange="change_package(this.value);"></select>
	&nbsp;
	<a href = "#" ondragstart="event.dataTransfer.setData('Text', '-'+$('module_id').value);event.dataTransfer.effectAllowed = 'copy';">Modules</a>
	: 
	<select name="module_id" id="module_id"></select>
	<script language="javascript">
	change_package([[|package_id|]]);
	</script>
	<br />
	<!--LIST:new_modules-->
	&nbsp;&nbsp;<a href = "#" ondragstart="event.dataTransfer.setData('Text', '-[[|new_modules.id|]]');event.dataTransfer.effectAllowed = 'copy';">[[|new_modules.name|]]</a>
	<!--/LIST:new_modules-->
</td>
</tr>
<tr>
	<td align="center">[[|regions|]]</td>
</tr>
<tr>
<td>
	&nbsp;Modules: 
	<!--LIST:new_modules-->
	&nbsp;&nbsp;<a href = "#" ondragstart="event.dataTransfer.setData('Text', '-[[|new_modules.id|]]');event.dataTransfer.effectAllowed = 'copy';">[[|new_modules.name|]]</a>
	<!--/LIST:new_modules-->
</td>
</tr>
</table>
