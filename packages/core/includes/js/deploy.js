function switch_display(obj)
{
	obj = $(obj);
	if(obj.style.display=='none')
	{
		obj.style.display='';
	}
	else
	{
		obj.style.display='none';
	}
}
function make_module_title(id, name, type, block_id, region_name,page_id,timer,container_id)
{
	if(moduleAllowDoubleClick){
		document.write('\
			<div ondblclick="switch_display(\'module_title_'+block_id+'\')">\
			<div id="module_title_'+block_id+'" class="admin-tasks" style="display:none;" title="Timer:'+timer+'">\
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC" style="border:0px solid #3771B0;">\
			<tr>\
				<td width="100%" align="left" style="line-height:24px;" nowrap>&nbsp;'+name+'</td>\
				<td><a class="module-btn module-language-btn" target="_blank" href="?portal='+PORTAL_ID+'&page=package_word&amp;block_id='+block_id+'&amp;module_id='+id+'" title="Thay &#273;&#7893;i ng&#244;n ng&#7919;"></a></td>\
				<td><a class="module-btn module-close-btn"  onclick="switch_display(document.getElementById(\'module_title_'+block_id+'\'));" title="&#272;&#243;ng thanh c&#244;ng c&#7909;"></a></td>\
			</tr>\
			</table></div>');
	}
}