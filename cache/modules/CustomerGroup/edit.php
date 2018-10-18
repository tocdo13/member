<?php 
$title = (URL::get('cmd')=='edit')?'S&#7917;a':'Th&#234;m m&#7899;i';
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
	<script type="text/javascript">
		$('title_region').style.display='';
		$('title_region').innerHTML='<table cellpadding="15" width="100%" class="table-bound"><tr><td class="" width="80%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> <?php echo $title;?><\/td>\
		<td class="form_title_button"><a href="#" class="w3-btn w3-orange w3-text-white" onclick="EditCustomerGroupForm.submit();" style="text-transform: uppercase; text-decoration:none; margin-right: 5px;"><?php echo Portal::language('Save_and_close');?><\/a>\
		<a href="#" class="w3-btn w3-green w3-text-white" onclick="location=\'<?php echo URL::build_current();?>\';" style="text-transform: uppercase; text-decoration:none; margin-right: 5px;"><?php echo Portal::language('back');?><\/a><\/td>\
		<?php if($action=='edit' and $this->map['structure_id']!=ID_ROOT){?><!---<a class="w3-btn w3-red w3-text-white" href="#" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'delete','id'));?>\';" style="text-transform: uppercase; text-decoration:none;"><?php echo Portal::language('delete');?><\/a>---><?php }?>\
		<\/td><\/tr><\/table>';
	</script>
	<div class="form_content">
		<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
		<form name="EditCustomerGroupForm" method="post" >
		<table cellspacing="0" cellpadding="5">
			<tr>
              <td><div class="form_input_label"><?php echo Portal::language('code');?>:</div></td>
			  <td><div class="form_input">
                  <input  name="id" id="id" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('id'));?>">
              </div></td>
		  
			  <td><div class="form_input_label"><?php echo Portal::language('name');?>:</div></td>
			  <td><div class="form_input">
			    <input  name="name" id="name" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">
			    </div></td>
		  
			
				<td><div class="form_input_label"><?php echo Portal::language('parent_name');?>:</div></td>
				<td><div class="form_input"><select  name="parent_id" id="parent_id"><?php
					if(isset($this->map['parent_id_list']))
					{
						foreach($this->map['parent_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))
                    echo "<script>$('parent_id').value = \"".addslashes(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))."\";</script>";
                    ?>
	</select></div></td>
			
				<td><div class="form_input_label"><?php echo Portal::language('show_price');?>:</div></td>
				<td><div class="form_input"><input name="show_price" type="checkbox" id="show_price" <?php if(isset($this->map['show_price']) && $this->map['show_price']==1){ echo 'checked="checked"'; } ?> /></div></td>
			</tr>
		</table>
		<input type="hidden" value="1" name="confirm_edit" />
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
</div>
	
