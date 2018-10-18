<?php 
$title = (URL::get('cmd')=='edit')?'S&#7917;a':'Th&#234;m m&#7899;i';
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
	<script type="text/javascript">
		$('title_region').style.display='';
		$('title_region').innerHTML='<table cellpadding="15" width="100%" class="table-bound"><tr><td class="" width="80%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> <?php echo $title;?><\/td>\
		<td class="form_title_button"><a href="#" class="w3-btn w3-orange w3-text-white" onclick="EditCustomerGroupForm.submit();" style="text-transform: uppercase; text-decoration:none; margin-right: 5px;">[[.Save_and_close.]]<\/a>\
		<a href="#" class="w3-btn w3-green w3-text-white" onclick="location=\'<?php echo URL::build_current();?>\';" style="text-transform: uppercase; text-decoration:none; margin-right: 5px;">[[.back.]]<\/a><\/td>\
		<?php if($action=='edit' and [[=structure_id=]]!=ID_ROOT){?><!---<a class="w3-btn w3-red w3-text-white" href="#" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'delete','id'));?>\';" style="text-transform: uppercase; text-decoration:none;">[[.delete.]]<\/a>---><?php }?>\
		<\/td><\/tr><\/table>';
	</script>
	<div class="form_content">
		<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
		<form name="EditCustomerGroupForm" method="post" >
		<table cellspacing="0" cellpadding="5">
			<tr>
              <td><div class="form_input_label">[[.code.]]:</div></td>
			  <td><div class="form_input">
                  <input name="id" type="text" id="id" style="width:200px" />
              </div></td>
		  
			  <td><div class="form_input_label">[[.name.]]:</div></td>
			  <td><div class="form_input">
			    <input name="name" type="text" id="name" style="width:200px" />
			    </div></td>
		  
			
				<td><div class="form_input_label">[[.parent_name.]]:</div></td>
				<td><div class="form_input"><select name="parent_id" id="parent_id"></select></div></td>
			
				<td><div class="form_input_label">[[.show_price.]]:</div></td>
				<td><div class="form_input"><input name="show_price" type="checkbox" id="show_price" <?php if(isset([[=show_price=]]) && [[=show_price=]]==1){ echo 'checked="checked"'; } ?> /></div></td>
			</tr>
		</table>
		<input type="hidden" value="1" name="confirm_edit" />
		</form>
	</div>
</div>
	
