<?php 
$title = (URL::get('cmd')=='edit')?'S&#7917;a':'Th&#234;m m&#7899;i';
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
	<script type="text/javascript">
		$('title_region').style.display='';
		$('title_region').innerHTML='<table cellpadding="15" width="100%" class="table-bound"><tr><td class="form-title" width="80%"><?php echo $title;?><\/td>\
		<td class="form_title_button"><a href="#a" onclick="EditZoneForm.submit();"><img alt="" src="packages/core/skins/default/images/buttons/save_button.gif" style="text-align:center"/><br />[[.save.]]<\/a><\/td>\
		<td class="form_title_button"><a href="#a" onclick="location=\'<?php echo URL::build_current();?>\';"><img alt="" src="packages/core/skins/default/images/buttons/go_back_button.gif"/><br />[[.back.]]<\/a><\/td>\
		<?php if($action=='edit' and [[=structure_id=]]!=ID_ROOT){?><td class="form_title_button"><a href="#a" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'delete','id'));?>\';"><img alt="" src="packages/core/skins/default/images/buttons/delete_button.gif"/><br />[[.delete.]]<\/a><\/td><?php }?>\
		<\/td><\/tr><\/table>';
	</script>
	<div class="form_content">
		<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
		<form name="EditZoneForm" method="post" >
		<table border="0" cellspacing="4" cellpadding="4">
			<tr>
			  <td><div class="form_input_label">[[.code_1.]]:</div></td>
			  <td><div class="form_input">
                  <input name="brief_name_1" type="text" id="brief_name_1" style="width:100px" />
              </div></td>
			  <td>&nbsp;</td>
			  <td><div class="form_input_label">[[.name_1.]]:</div></td>
			  <td><div class="form_input">
			    <input name="name_1" type="text" id="name_1" style="width:200px" />
			    </div></td>
		  </tr>
			<tr>
			  <td><div class="form_input_label">[[.code_2.]]:</div></td>
			  <td><div class="form_input">
			    <input name="brief_name_2" type="text" id="brief_name_2" style="width:100px" />
			  </div></td>
			  <td>&nbsp;</td>
              <td><div class="form_input_label">[[.name_2.]]:</div></td>
			  <td><div class="form_input">
                  <input name="name_2" type="text" id="name_2" style="width:200px" />
              </div></td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
				<td><div class="form_input_label">[[.parent_name.]]:</div></td>
				<td><div class="form_input"><select name="parent_id" id="parent_id"></select></div></td>
			</tr>
		</table>
		<input type="hidden" value="1" name="confirm_edit">
		</form>
	</div>
</div>
	
