<?php //System::set_page_title(HOTEL_NAME);?>
<div class="MassageStaff_type-bound">
<form name="EditMassageStaffForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
            <td width="45%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-gray" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('cancel');?></a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2" class="w3-light-gray">
				<tr>
                  <td class="label"><?php echo Portal::language('full_name');?>(*):</td>
				  <td><input  name="full_name" id="full_name" style=" height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                  <td class="label"><?php echo Portal::language('birth_date');?>:</td>
				  <td><input  name="birth_date" id="birth_date" style=" height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('birth_date'));?>"></td>
                  <td class="label"><?php echo Portal::language('gender');?></td>
				  <td><select  name="gender_id" id="gender_id" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['gender_id_list']))
					{
						foreach($this->map['gender_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender_id',isset($this->map['gender_id'])?$this->map['gender_id']:''))
                    echo "<script>$('gender_id').value = \"".addslashes(URL::get('gender_id',isset($this->map['gender_id'])?$this->map['gender_id']:''))."\";</script>";
                    ?>
	</select></td>
				</tr>
				<tr>
                  <td class="label"><?php echo Portal::language('native');?></td>
				  <td colspan="5"><textarea  name="native" id="native" style="width: 625px;  height: 24px;"><?php echo String::html_normalize(URL::get('native',''));?></textarea></td>
			  </tr>              
				<tr>
				  <td class="label"><?php echo Portal::language('address');?></td>
				  <td colspan="5"><textarea  name="address" id="address" style="width: 625px; height: 24px;"><?php echo String::html_normalize(URL::get('address',''));?></textarea></td>
			  </tr>              
				<tr>
                  <td class="label"><?php echo Portal::language('email');?></td>
				  <td><input  name="email" id="email" style=" height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
                  <td class="label"><?php echo Portal::language('mobile_number');?></td>
				  <td><input  name="phone" id="phone" style=" height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>"></td>
                  <td class="label"><?php echo Portal::language('marrital_status');?></td>				 
			     <td><select  name="marial_id" id="marial_id"><?php
					if(isset($this->map['marial_id_list']))
					{
						foreach($this->map['marial_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('marial_id',isset($this->map['marial_id'])?$this->map['marial_id']:''))
                    echo "<script>$('marial_id').value = \"".addslashes(URL::get('marial_id',isset($this->map['marial_id'])?$this->map['marial_id']:''))."\";</script>";
                    ?>
	</select></td>                                  
              </tr>
              <tr>
                  <td class="label"><?php echo Portal::language('brief');?></td>
				  <td colspan="5"><textarea  name="description" id="description" style="width: 625px; height: 24px;"><?php echo String::html_normalize(URL::get('description',''));?></textarea></td>
			  </tr>
				<tr>
                  <td class="label"><?php echo Portal::language('date_in');?></td>
				  <td><input  name="date_in" id="date_in" style=" height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date_in'));?>"></td>
                  <td class="label"><?php echo Portal::language('date_out');?></td>
				  <td><input  name="date_out" id="date_out" style=" height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date_out'));?>"></td>
                  <td class="label"><?php echo Portal::language('status');?></td>
				  <td><select  name="status" id="status" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
			  </tr>
			</table>
	  </fieldset>	
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script>
	jQuery("#birth_date").datepicker();
	jQuery("#date_in").datepicker();
	jQuery("#date_out").datepicker();
    jQuery("#birth_date").mask("99/99/9999");
	jQuery("#date_in").mask("99/99/9999");
	jQuery("#date_out").mask("99/99/9999");
</script>