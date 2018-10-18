<script src="packages/core/includes/js/multi_items.js" type="text/javascript"></script>
<script>
jQuery(function(){
	jQuery('#birth_day').datepicker();
	jQuery('#join_date').datepicker();
});
</script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('edit_portal'):Portal::language('add_portal');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div class="form_bound" style="padding:5px;width:980px;">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr>
        	<td width="100%" class="form-title"><?php echo $title;?></td>
            <td class="form_title_button"><a class="button-medium-save" href="javascript:void(0)" onclick="EditManagePortalForm.submit();"><?php echo Portal::language('save');?></a></td>
			<td class="form_title_button"><a class="button-medium-back" href="javascript:void(0)" onclick="location='<?php echo URL::build_current();?>';"><?php echo Portal::language('back');?></a></td>
			<?php if($action=='edit'){?>
            <td class="form_title_button"><a class="button-medium-delete" href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';"><?php echo Portal::language('Delete');?></a></td>
			<?php }?>
        </tr>
	</table>
	<div class="form_content">
	<?php if(Form::$current->is_error())
		{
		?>
		<?php echo Form::$current->error_messages();?><br>
		<?php
		}
		?>
		<form name="EditManagePortalForm" method="post" >
	<input type="hidden" name="privilege_deleted_ids" id="privilege_deleted_ids" value=""/>
	<input type="hidden" name="group_deleted_ids" id="group_deleted_ids" value=""/><br />
<table cellpadding="5" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
  <tr>
    <td><?php echo Portal::language('portal_id');?> (*):</td>
    <td>
     <input  name="id" id="id" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('id'));?>"><em style="color:red;">Mã portal phải bao gồm cả dấu #</em></td>
    <td><?php echo Portal::language('portal_name');?> (*):</td>
    <td><input  name="name_1" id="name_1" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('name_1'));?>">    </td>
  </tr>
  <tr>
    <td><?php echo Portal::language('zone_id');?>:</td>
    <td>
		<select  name="zone_id" id="zone_id"><?php
					if(isset($this->map['zone_id_list']))
					{
						foreach($this->map['zone_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('zone_id',isset($this->map['zone_id'])?$this->map['zone_id']:''))
                    echo "<script>$('zone_id').value = \"".addslashes(URL::get('zone_id',isset($this->map['zone_id'])?$this->map['zone_id']:''))."\";</script>";
                    ?>
	</select>	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
	</div><br />
	<fieldset style="display:none" <?php if(User::can_admin(false,ANY_CATEGORY)){echo 'style="display:block;padding:10px;"';} else{ echo 'style="display:none"';}?>>
		<legend><?php echo Portal::language('user_group');?></legend>
			<span id="mi_user_group_all_elems" style="text-align:left;">
				<span>
					<span class="multi-input-header"><span style="width:150px;"><?php echo Portal::language('group_id');?></span></span>
					<span class="multi-input-header"><span style="width:100px;"><?php echo Portal::language('join_date');?></span></span>
					<span><span style="width:20px;"><img src="<?php echo Portal::template('core');?>/images/spacer.gif" /></span></span>
					<span class="multi-input-header"><span style="width:100px;"><?php echo Portal::language('active');?></span></span>
					<span class="multi-input-header"><span style="width:20px;"><img src="<?php echo Portal::template('core');?>/images/spacer.gif"/></span></span>
					<br>
				</span>
			</span>
		<input type="button" value="   <?php echo Portal::language('add_item');?>   " onclick="mi_add_new_row('mi_user_group');">
	</fieldset> 
	<input type="hidden" value="1" name="confirm_edit" >
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
</div>
<script type="text/javascript">
mi_init_rows('mi_user_group',<?php if(isset($_REQUEST['mi_user_group'])){echo String::array2js($_REQUEST['mi_user_group']);}else{echo '{}';}?>);
mi_init_rows('mi_user_privilege',<?php if(isset($_REQUEST['mi_user_privilege'])){echo String::array2js($_REQUEST['mi_user_privilege']);}else{echo '{}';}?>);
</script>
