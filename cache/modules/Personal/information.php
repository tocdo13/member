<script type="text/javascript">
jQuery(function(){
	jQuery('#birth_date').datepicker();
});
</script>
<div class="personal-bound">
	<div class="personal-content" style="width:980px;margin-left:auto;margin-right:auto;">
        <div class="setting-tab">
            <div class="bound selected"><div>
              <div><a href="<?php echo Url::build_current()?>"><?php echo Portal::language('Account_information');?></a></div>
            </div></div>
            <div class="bound normal"><div>
              <div><a href="<?php echo Url::build_current(array('cmd'=>'change_pass'))?>"><?php echo Portal::language('Change_password');?></a></div>
            </div></div>
        </div>
        <div class="setting-bound">
        <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>	
        <form name="EditUser" method="post" id="EditUser" enctype="multipart/form-data">
            <table cellpadding="4" cellspacing="0" width="100%" align="center">
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('Account_id');?></td>
                      <td width="68%"><input  name="account_id" id="account_id" class="input-huge" style="width:200px;background-color:#FFFFCC;" readonly="readonly" type ="text" value="<?php echo String::html_normalize(URL::get('account_id'));?>"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('full_name');?></td>
                      <td width="68%"><input  name="full_name" id="full_name" class="input-huge" style="width:300px;" type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('address');?></td>
                        <td width="68%"><input  name="address" id="address" class="input-huge" style="width:300px;" type ="text" value="<?php echo String::html_normalize(URL::get('address'));?>"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('zone');?></td>
                        <td width="68%"><select  name="zone_id" class="select-large" id="zone_id"><?php
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
	</select></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('gender');?></td>
                        <td width="68%"><select  name="gender" class="select" id="gender"><?php
					if(isset($this->map['gender_list']))
					{
						foreach($this->map['gender_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))
                    echo "<script>$('gender').value = \"".addslashes(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))."\";</script>";
                    ?>
	</select></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('birth_date');?></td>
                        <td width="68%"><input  name="birth_date" id="birth_date" class="input"  style="width:200px;" type ="text" value="<?php echo String::html_normalize(URL::get('birth_date'));?>"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('phone_number');?></td>
                        <td width="68%"><input  name="phone" id="phone" class="input-huge" style="width:200px;" type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right"><?php echo Portal::language('email');?></td>
                        <td width="68%"><input  name="email" id="email" class="input-huge" style="width:200px;" type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
                    </tr>
            </table>
            <div class="personal-button" align="center"><input name="submit" type="submit" value="<?php echo Portal::language('Save');?>" />&nbsp;&nbsp;&nbsp;<input name="reset" type="reset" value="<?php echo Portal::language('Reset');?>" /></div>
          <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
        </div>
	</div>
</div>
