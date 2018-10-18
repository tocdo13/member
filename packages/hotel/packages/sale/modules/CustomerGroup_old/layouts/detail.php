<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('are_you_sure'):Portal::language('detail');
$action = (URL::get('cmd')=='delete')?'delete':'detail';
System::set_page_title(Portal::get_setting('company_name','').' '.$title);?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div id="title_region"></div>
<div class="form_bound">
	<script type="text/javascript" >
		$('title_region').style.display='';
		$('title_region').innerHTML='<table cellpadding="0" width="100%" class="table-bound"><tr height="40"><td  class="form-title" width="70%"><?php echo $title;?></td><?php 
			if(URL::get('cmd')=='delete'){?><td class="form_title_button"><a href="#a" onclick="CustomerGroupForm.submit();"><img alt="" src="packages/core/skins/default/images/buttons/delete_button.gif" style="text-align:center"/><br />[[.do_action.]]<\/a></td><?php 
			}else{ 
				if(User::can_edit()){?><td class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'));?>"><img alt="" src="packages/core/skins/default/images/buttons/edit.jpg" style="text-align:center"/><br />[[.edit.]]<\/a></td><?php } 
				if(User::can_delete()){?><td class="form_title_button"><a href="<?php echo URL::build_current(array('cmd'=>'delete','id'));?>"><img alt="" src="packages/core/skins/default/images/buttons/delete_button.gif"/><br />[[.delete.]]<\/a></td><?php }
			}?>\
			<td class="form_title_button"><a href="<?php echo URL::build_current(array(
	 'name'=>isset($_GET['name'])?$_GET['name']:'', 
	));?>"><img alt="" src="packages/core/skins/default/images/buttons/go_back_button.gif" style="text-align:center"/><br />[[.back.]]<\/a></td>\
			</td><\/tr><\/table>';
	</script>
<div class="form_content">
<table cellspacing="0" cellpadding="5">
  <tr valign="top" >
    <td>[[.id.]]</td>
    <td width="1">:</td>
    <td>[[|id|]]</td>
	<td rowspan="3" valign="top">&nbsp;</td>
  </tr>
  	<tr>
		<td>[[.name.]]</td>
		<td>:</td>
		<td><strong>[[|name|]]</strong></td>
	</tr>
	</table>
	<?php
	if(URL::get('cmd')!='delete')
	{
	?>
	<?php
	}
	?>
	<!--IF:delete(URL::get('cmd')=='delete')-->
	<form name="CustomerGroupForm" method="post">
	<input type="hidden" value="1" name="confirm"/>
	<input type="hidden" value="delete" name="cmd"/>
	<input type="hidden" value="<?php echo URL::get('id');?>" name="id"/>
	</form>
	<!--/IF:delete-->
	</div>
</div>

