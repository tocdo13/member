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
<table cellpadding="0" width="100%"><tr><td  class="form_title"><img src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle" alt=""/><?php echo $title;?></td><?php if(URL::get('cmd')=='delete'){?><td class="form_title_button"><a  onclick="PageAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" style="text-align:center" alt=""/><br />[[.Delete.]]</a></td><?php }else{ if(User::can_edit()){?><td class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit.jpg" style="text-align:center" alt=""/><br />[[.Edit.]]</a></td><?php } if(User::can_delete()){?><td class="form_title_button">
				<a href="<?php echo URL::build_current(array('portal_id','cmd'=>'delete','id'));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" alt=""/><br />[[.Delete.]]</a></td><?php }}?>
				<td class="form_title_button"><a href="<?php echo URL::build_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'',  
	'name'=>isset($_GET['name'])?$_GET['name']:'',    
	  ));?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif" style="text-align:center" alt=""/><br />[[.back.]]</a></td>
				<td class="form_title_button">
				<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
<div class="form_content">
<table cellspacing="0" width="100%">
  <tr valign="top" >
    <td class="form_detail_label">[[.id.]]</td>
    <td width="1">:</td>
    <td class="form_detail_value">[[|id|]]</td>
  </tr>
  	<tr>
		<td class="form_detail_label">[[.name.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|name|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.title.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|title|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.params.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|params|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.description.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|description|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.package_id.]]</td>
		<td>:</td>
		<td class="form_detail_value">[[|package_id|]]</td>
	</tr><tr>
		<td class="form_detail_label">[[.cachable.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|cachable|]]
		</td>
	</tr><tr>
		<td class="form_detail_label">[[.cache_param.]]</td>
		<td>:</td>
		<td class="form_detail_value">
			[[|cache_param|]]
		</td>
	</tr>
	</table>
	<?php
	if(URL::get('cmd')!='delete')
	{
	?>	<!--IF:related([[=module_related_fields=]])-->
	<div class="form_related_fields_area">
		<div class="form_related_fields_title">[[.module_related.]]</div>
		<!--LIST:module_related_fields-->
		<div class="form_related_field"><a href="<?php echo URL::build('module');?>&id=[[|module_related_fields.id|]]">[[|module_related_fields.name|]]</a></div>
		<!--/LIST:module_related_fields-->
	</div>
	<!--/IF:related--> 
	<?php
	}
	?>	<!--IF:delete(URL::get('cmd')=='delete')-->
	<form name="PageAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<input type="hidden" value="1" name="confirm"/>
	<input type="hidden" value="delete" name="cmd"/>
	<input type="hidden" value="<?php echo URL::get('id');?>" name="id"/>
	</form>
	<!--/IF:delete-->
	</div>
</div>
<div>
	<p><span class="style1">Ch&uacute; &yacute;</span>:  H&#7879; th&#7889;ng s&#7869; x&oacute;a <span class="style2">h&ograve;an to&agrave;n</span> nh&#7919;ng d&#7919; li&#7879;u sau:</p>
	    <li>T&#7845;t c&#7843; block_setting c&#7911;a nh&#7919;ng block thu&#7897;c trang n&agrave;y.</li>
	    <li>T&#7845;t c&#7843; block thu&#7897;c trang n&agrave;y. </li>
</div>
