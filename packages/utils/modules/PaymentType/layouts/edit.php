<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('Edit'):Portal::language('Add');
$action = (URL::get('cmd')=='edit')?'edit':'add';?>
<form name="EditCategoryForm" method="post">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC"  class="table-bound">
	<tr>
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $title;?></td>
		<td width="40%" align="right"><a class="w3-btn w3-orange w3-text-white" onclick="EditCategoryForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.save.]]</a>
        <a class="w3-green w3-btn" onclick="location='<?php echo URL::build_current();?>';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a>
		<?php if($action=='edit' and User::can_delete(false,ANY_CATEGORY)){?>
		<a class="w3-btn w3-red" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td>
		<?php }?>
	</tr>
</table>
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">	
	<tr>
	<td style="width:100%" valign="top">
	<?php if(Form::$current->is_error())
	{
	?>
	<strong>B&#225;o l&#7895;i</strong><br>
	<?php echo Form::$current->error_messages();?><br>
	<?php
	}
	?>
	<input type="hidden" name="confirm_edit" value="1" />
	<table cellspacing="0" width="100%">
	  <tr>
	    <td>[[.def_code.]]:<br /><input name="def_code" type="text" id="def_code"></td>
	    </tr>
	  <tr><td>
	<div class="tab-pane-1" id="tab-pane-payment_type">
	<!--LIST:languages-->
	<div class="tab-page" id="tab-page-payment_type-[[|languages.id|]]">
	<h2 class="tab">[[|languages.name|]]</h2>
	<div class="form_input_label">[[.name.]]:</div>
	<div class="form_input">
	<input name="name_[[|languages.id|]]" type="text" id="name_[[|languages.id|]]" style="width:400" >
	</div>
	</div>
	<!--/LIST:languages-->
	</div>
	</td></tr></table>
	<div class="form_input_label">[[.parent_name.]]:</div>
	<div class="form_input">
	<select name="parent_id" id="parent_id"></select></div>
	</td>
	<td valign="top">
	</td>
	</tr>
</table>
</form>
