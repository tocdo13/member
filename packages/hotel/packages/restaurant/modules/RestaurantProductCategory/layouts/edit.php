<script src="packages/core/includes/js/init_tinyMCE.js"></script>
<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('Edit'):Portal::language('Add');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(Portal::get_setting('company_name','').' '.$title);?>
<div align="center">
<div style="width:980px;text-align:left;border:1px solid #CCCCCC;margin-top:3px;">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC"  class="table-bound">
	<tr>
		<td width="100%" class="form-title"><?php echo $title;?></td>
		<td width="" align="right"><a class="button-medium-save" onclick="EditRestaurantProductCategoryForm.submit();">[[.save.]]</a></td>
        <td><a class="button-medium-back" onclick="location='<?php echo URL::build_current();?>';">[[.back.]]</a></td>
		<?php if($action=='edit' and User::can_delete(false,ANY_CATEGORY) and [[=structure_id=]]!=ID_ROOT){?>
		<td><a class="button-medium-delete" onclick="if(!confirm('[[.are_you_sure.]]')){return false};location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';">[[.Delete.]]</a></td>
		<?php }?>
	</tr>
</table>
<table cellpadding="15" cellspacing="0"  border="0" bordercolor="#CCCCCC" class="table-bound">	
	<tr>
        <td style="width:100%" valign="top">
        <?php if(Form::$current->is_error())
        {
        ?>
        <?php echo Form::$current->error_messages();?><br>
        <?php
        }
        ?>
        <form name="EditRestaurantProductCategoryForm" method="post">
        <table cellspacing="0" width="100%">
            <tr>
                <td>
                    <div class="form_input_label">[[.parent_name.]]:</div>
                    <div class="form_input"><select name="parent_id" id="parent_id" style="width:210px;"></select></div>
    
                    <div class="form_input_label">[[.name.]]:</div>
                    <div class="form_input">
                        <input name="name" type="text" id="name" style="width:200px;" >
                    </div>
                    <div class="form_input_label">[[.code.]]:</div>    
                    <div class="form_input">
                        <input name="code" type="text" id="code" style="width:100px;" >
                    </div>    
                </td>
            </tr>
        </table>
        <input type="hidden" name="confirm_edit" value="1" />
        </form>
        </td>
	</tr>
</table>
</div>
</div>
