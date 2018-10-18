<?php 
$title = (URL::get('cmd')=='delete')?'Delete':'view';
$action = (URL::get('cmd')=='delete')?'delete':'detail';
?>
<div class="form_bound">
	<table cellpadding="15" width="100%"><tr><td  class="form-title"><?php echo $title;?></td><?php 
			if(URL::get('cmd')=='delete'){?><td class="form-title-button"><a href="javascript:void(0)" onclick="CategoryForm.submit();"><img alt="" src="<?php echo Portal::template('core');?>/images/buttons/delete.jpg" style="text-align:center"/><br /><?php echo Portal::language('Delete');?></a></td><?php 
			}else{ 
				if(User::can_edit(false,ANY_CATEGORY)){?><td class="form-title-button"><a href="<?php echo URL::build_current(array('cmd'=>'edit','id'));?>"><img alt="" src="<?php echo Portal::template('core');?>/images/buttons/edit.jpg" style="text-align:center"/><br /><?php echo Portal::language('Edit');?></a></td><?php } 
				if(User::can_delete(false,ANY_CATEGORY)){?><td class="form-title-button"><a href="<?php echo URL::build_current(array('cmd'=>'delete','id'));?>"><img alt="" src="<?php echo Portal::template('core');?>/images/buttons/delete.jpg"/><br /><?php echo Portal::language('Delete');?></a></td><?php }
			}?>
			<td class="form-title-button"><a href="<?php echo URL::build_current(Module::$current->redirect_parameters);?>"><img alt="" src="<?php echo Portal::template('core');?>/images/buttons/back.jpg" style="text-align:center"/><br /><?php echo Portal::language('back');?></a></td></tr></table>
	</script>
<div class="form_content">
<table cellspacing="0" cellpadding="10" width="100%">
  <tr valign="top" >
  <td rowspan="5" align="center" valign="top">
	<?php 
				if(($this->map['icon_url']))
				{?>
				<a target="_blank" href="<?php echo $this->map['icon_url'];?>"><img alt="" src="<?php echo $this->map['icon_url'];?>" height="100"></a><br />
				<?php echo Portal::language('icon_url');?><br />
				
				<?php
				}
				?>
	</td>
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
		<td class="form_detail_label"><?php echo Portal::language('description');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['description'];?>
		</td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('type');?></td>
		<td>:</td>
		<td class="form_detail_value"><?php echo $this->map['type'];?></td>
	</tr><tr>
		<td class="form_detail_label"><?php echo Portal::language('is_visible');?></td>
		<td>:</td>
		<td class="form_detail_value">
			<?php echo $this->map['is_visible'];?>
		</td>
	</tr>
	</table>
	<?php 
				if((URL::get('cmd')=='delete'))
				{?>
	<form name="CategoryForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<input type="hidden" value="<?php echo URL::get('id');?>" name="selected_ids[]"/>
	<input type="hidden" value="1" name="confirm"/>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	
				<?php
				}
				?>
	</div>
</div>