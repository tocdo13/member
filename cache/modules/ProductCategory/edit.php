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
		<td width="40%" align="right"><a class="w3-btn w3-orange w3-text-white" name="btnSave" id="btnSave" onclick="EditProductCategoryForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Save_and_close');?></a>
        <a class="w3-btn w3-green" onclick="location='<?php echo URL::build_current();?>';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('back');?></a></td>
		<?php if($action=='edit' and User::can_delete(false,ANY_CATEGORY) and $this->map['structure_id']!=ID_ROOT){?>
		<!--<td><a class="w3-btn w3-red" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a></td>-->
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
                    <div class="form_input_label"><?php echo Portal::language('parent_name');?>:</div>
                    <div class="form_input"><select  name="parent_id" id="parent_id" style="width:200px; height: 24px;"><?php
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
	</select></div>
    
                    <div class="form_input_label"><?php echo Portal::language('name');?>:</div>
                    <div class="form_input">
                        <input  name="name" id="name" style="width:200px;  height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">
                    </div>
                    <div class="form_input_label"><?php echo Portal::language('name_en');?>:</div>
                    <div class="form_input">
                        <input  name="name_en" id="name_en" style="width:200px;  height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('name_en'));?>">
                    </div>
                    <div class="form_input_label"><?php echo Portal::language('code');?>:</div>    
                    <div class="form_input">
                        <?php if(Url::get('cmd')=='add'){?>
                        <input  name="code" id="code" style="width:100px;text-transform: uppercase;  height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                        <?php }else{?>
                        <input  name="code" id="code" style="width:100px;  height: 24px; text-transform: uppercase; readonly" readonly="readonly" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                        <?php }?>
                    </div>    
                    <div class="form_input_label"><?php echo Portal::language('position');?>:</div>    
                    <div class="form_input">
                        <input  name="position" id="position" class="input_number" style="width:100px;text-transform: uppercase;  height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('position'));?>">
                    </div>    
					<!--<div class="form_input_label"><?php echo Portal::language('icon');?>:</div>
                    <div class="form_input">
						<?php 
				if((Url::get('icon_url')))
				{?>
						<!--<img src="<?php echo Url::get('icon_url');?>" />
						
				<?php
				}
				?>
                        <!--<input  name="icon_url" id="icon_url" style="width:100px;"  type ="file" value="<?php echo String::html_normalize(URL::get('icon_url'));?>">
                    </div>-->    
                </td>
            </tr>
        </table>
        <input type="hidden" name="confirm_edit" value="1" />
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        </td>
	</tr>
</table>
</div>
</div>