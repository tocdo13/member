<?php 
$title = (URL::get('cmd')=='edit')?'S&#7917;a':'Th&#234;m m&#7899;i';
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
	<script type="text/javascript">
		$('title_region').style.display='';
		$('title_region').innerHTML='<table cellpadding="15" width="100%" ><tr><td class="" style="text-transfrom: uppercase; font-size: 18px; padding-left: 15px;" width="70%"><?php echo $title;?><\/td>\
		<td width="30%" align="right" ><a class="w3-btn w3-orange w3-text-white" href="#a" onclick="EditCountryForm.submit();" style="text-transfrom: uppercase; margin-right: 5px; text-decoration: none;">[[.save.]]<\/a>\
		<a class="w3-btn w3-green w3-text-white" href="#a" onclick="location=\'<?php echo URL::build_current();?>\';" style="text-transfrom: uppercase; margin-right: 5px; text-decoration: none;">[[.back.]]<\/a>\
		<a class="w3-btn w3-red w3-text-white" href="#a" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'delete','id'));?>\';" style="text-transfrom: uppercase; margin-right: 5px; text-decoration: none;">[[.delete.]]<\/a>\
		<\/td><\/tr><\/table>';
	</script>
	<div class="form_content">
		<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
		<form name="EditCountryForm" method="post" >
		<table border="0" cellspacing="4" cellpadding="4">
			<tr>
              <td><div class="form_input_label">[[.code_2.]]:</div></td>
			  <td><div class="form_input">
                  <input name="code_2" type="text" id="code_2" style="width:100px" />
              </div></td>
			  <td>&nbsp;</td>
			  <td><div class="form_input_label">[[.name_2.]]:</div></td>
			  <td><div class="form_input">
                  <input name="name_2" type="text" id="name_2" style="width:200px" />
              </div></td>
		    </tr>
			<tr>
			  <td><div class="form_input_label">[[.code_1.]]:</div></td>
			  <td><div class="form_input">
                  <input name="code_1" type="text" id="code_1" style="width:100px" />
              </div></td>
			  <td>&nbsp;</td>
			  <td><div class="form_input_label">[[.name_1.]]:</div></td>
			  <td><div class="form_input">
			    <input name="name_1" type="text" id="name_1" style="width:200px" />
			    </div></td>
		  </tr>
          <tr>
            <td><div class="form_input_label">[[.area.]]:</div></td>
            <td>
              <select  name="continents_id" id="continents_id">
                <option value="">[[.select_continents.]]</option>
                <option value="1">[[.europe.]]</option>
                <option value="2">[[.asian.]]</option>
                <option value="3">[[.the_other.]]</option>
              </select> 
            </td>
          </tr>
		</table>
		<input type="hidden" value="1" name="confirm_edit"/>
		</form>
	</div>
</div>
<script>
jQuery('#continents_id').val(<?php echo $_REQUEST['continents_id']; ?>)
</script>
