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
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $title;?></td>
		<td width="40%" align="right"><a class="w3-btn w3-orange w3-text-white" name="btnSave" id="btnSave" onclick="EditProductCategoryForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Save_and_close.]]</a>
        <a class="w3-btn w3-green" onclick="location='<?php echo URL::build_current();?>';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td>
		<?php if($action=='edit' and User::can_delete(false,ANY_CATEGORY) and [[=structure_id=]]!=ID_ROOT){?>
		<!--<td><a class="w3-btn w3-red" onclick="if(!confirm('[[.are_you_sure.]]')){return false};location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a></td>-->
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
        <form name="EditProductCategoryForm" method="post" enctype="multipart/form-data">
        <table cellspacing="0" width="100%" class="w3-light-gray">
            <tr>
                <td>
                    <div class="form_input_label">[[.parent_name.]]:</div>
                    <div class="form_input"><select name="parent_id" id="parent_id" style="width:200px; height: 24px;"></select></div>
    
                    <div class="form_input_label">[[.name.]]:</div>
                    <div class="form_input">
                        <input name="name" type="text" id="name" style="width:200px;  height: 24px;" />
                    </div>
                    <div class="form_input_label">[[.name_en.]]:</div>
                    <div class="form_input">
                        <input name="name_en" type="text" id="name_en" style="width:200px;  height: 24px;" />
                    </div>
                    <div class="form_input_label">[[.code.]]:</div>    
                    <div class="form_input">
                        <?php if(Url::get('cmd')=='add'){?>
                        <input name="code" type="text" id="code" style="width:100px;text-transform: uppercase;  height: 24px;" />
                        <?php }else{?>
                        <input name="code" type="text" id="code" style="width:100px;  height: 24px; text-transform: uppercase; readonly" readonly="readonly" />
                        <?php }?>
                    </div>    
                    <div class="form_input_label">[[.position.]]:</div>    
                    <div class="form_input">
                        <input name="position" type="text" id="position" class="input_number" style="width:100px;text-transform: uppercase;  height: 24px;" />
                    </div>    
					<!--<div class="form_input_label">[[.icon.]]:</div>
                    <div class="form_input">
						<!--IF:cond(Url::get('icon_url'))-->
						<!--<img src="<?php echo Url::get('icon_url');?>" />
						<!--/IF:cond-->
                        <!--<input name="icon_url" type="file" id="icon_url" style="width:100px;" >
                    </div>-->    
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