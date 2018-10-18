<form name="EditTravellerForm"  method="post">
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left">
			<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td width="50%" class="form-title"><?php echo Portal::language('edit_traveller_info');?></td>
					<td width="50%" align="right" nowrap="nowrap">
                        <?php //if(User::can_add(false,ANY_CATEGORY)){?><!-- <input name="save_stay" type="submit" id="save_stay" value="<?php echo Portal::language('Save_back_list');?>" class="button-medium-save"/> --><?php //}?>
						<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" id="save" type="submit" value="<?php echo Portal::language('Save');?>" class="button-medium-save" onclick="check_click();" /><?php }?>
                        <script>
                            var count_click=0;
                            function check_click(){
                                count_click = count_click + 1;
                                if(count_click=1){
                                    jQuery("#save").css('display','none');
                                }
                            }
                        </script>
						<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('action'));?>"  class="button-medium-back"><?php echo Portal::language('back');?></a><?php }?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	<td align="right">
	<?php 
				if((Url::get('reservation_id')))
				{?>
	<div style="padding:5px;"><strong><?php echo Portal::language('guest');?> <?php echo Portal::language('of');?> <?php echo Portal::language('group');?></strong>: <select  name="id" id="id" onchange="window.location = '<?php echo Url::build_current(array('cmd','reservation_id'))?>&id='+this.value;"><?php
					if(isset($this->map['id_list']))
					{
						foreach($this->map['id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('id',isset($this->map['id'])?$this->map['id']:''))
                    echo "<script>$('id').value = \"".addslashes(URL::get('id',isset($this->map['id'])?$this->map['id']:''))."\";</script>";
                    ?>
	</select></div>
	
				<?php
				}
				?>
	</td>
	</tr>
	<tr>
	<td  bgcolor="#EEEEEE">
	<table width="100%" border="2" bordercolor="#FFFFFF">
	<?php if(Form::$current->is_error()){?><tr valign="top"><td><?php echo Form::$current->error_messages();?></td>
	  <td>&nbsp;</td>
	</tr><?php }?>
	<tr valign="top">
	<td><table width="400" cellpadding="2" cellspacing="0">
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('first_name');?> <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input  name="first_name" id="first_name"  style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('first_name'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('last_name');?> <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input  name="last_name" id="last_name"  style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('last_name'));?>">        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('gender');?> <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><select   name="gender" id="gender">
            <option value="1">1 - <?php echo Portal::language('male');?></option>
            <option value="0">0 - <?php echo Portal::language('female');?></option>
          </select>
            <script>
					selects = document.getElementsByTagName('select');
					selects[selects.length-1].value = '<?php echo URL::get('gender');?>';
				</script>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('birth_date');?> <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input  name="birth_date" id="birth_date" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('birth_date'));?>">
            <select   name="birth_date_correct" id="birth_date_correct">
              <option value="D">D - <?php echo Portal::language('date_correct');?></option>
              <option value="M">M - <?php echo Portal::language('month_correct');?> </option>
              <option value="Y">Y - <?php echo Portal::language('year_correct');?></option>
            </select>
            <script>
					$('birth_date_correct').value = '<?php echo URL::get('birth_date_correct');?>';
				</script>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('passport');?> <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input  name="passport" id="passport" style="width:200px" onchange="get_traveller();" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>">        </td>
      </tr>
      
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('is_vietnamese');?></td>
        <td align="center">:</td>
        <td ><select  name="is_vn" id="is_vn"><?php
					if(isset($this->map['is_vn_list']))
					{
						foreach($this->map['is_vn_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('is_vn',isset($this->map['is_vn'])?$this->map['is_vn']:''))
                    echo "<script>$('is_vn').value = \"".addslashes(URL::get('is_vn',isset($this->map['is_vn'])?$this->map['is_vn']:''))."\";</script>";
                    ?>
	
          </select>
            <script>
			//$('is_vn').value = '<?php //echo URL::get('is_vn');?>';
        </script>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('nationality');?> <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><select  name="nationality_id" id="nationality_id" style="width:200px"><?php
					if(isset($this->map['nationality_id_list']))
					{
						foreach($this->map['nationality_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))
                    echo "<script>$('nationality_id').value = \"".addslashes(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))."\";</script>";
                    ?>
	
          </select>        </td>
      </tr>
      
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('provice');?></td>
        <td align="center">:</td>
        <td ><select  name="province_id" id="province_id" style="width:200px"><?php
					if(isset($this->map['province_id_list']))
					{
						foreach($this->map['province_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('province_id',isset($this->map['province_id'])?$this->map['province_id']:''))
                    echo "<script>$('province_id').value = \"".addslashes(URL::get('province_id',isset($this->map['province_id'])?$this->map['province_id']:''))."\";</script>";
                    ?>
	
          </select>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('entry_date');?></td>
        <td align="center">:</td>
        <td><input  name="entry_date" id="entry_date" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('entry_date'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('port_of_entry');?></td>
        <td align="center">:</td>
        <td ><select  name="port_of_entry" id="port_of_entry" style="width:200px" ><?php
					if(isset($this->map['port_of_entry_list']))
					{
						foreach($this->map['port_of_entry_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('port_of_entry',isset($this->map['port_of_entry'])?$this->map['port_of_entry']:''))
                    echo "<script>$('port_of_entry').value = \"".addslashes(URL::get('port_of_entry',isset($this->map['port_of_entry'])?$this->map['port_of_entry']:''))."\";</script>";
                    ?>
	
        </select></td>
      </tr>

      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('back_date');?></td>
        <td align="center">:</td>
        <td ><input  name="back_date" id="back_date" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('back_date'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('arrival_date');?></td>
        <td align="center">:</td>
        <td ><input  name="time_in" id="time_in" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('time_in'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('departure_date');?></td>
        <td align="center">:</td>
        <td ><input  name="time_out" id="time_out" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('time_out'));?>"></td>
      </tr>

      <tr valign="top">
        <td align="right" nowrap="nowrap" width="200"><?php echo Portal::language('target_of_entry');?> (<?php echo Portal::language('foreigner');?>) / <?php echo Portal::language('target_of_provisional_staying');?> (<?php echo Portal::language('vietnam');?>) </td>
        <td align="center">:</td>
        <td ><select  name="entry_target" id="entry_target" style="width:200px" ><?php
					if(isset($this->map['entry_target_list']))
					{
						foreach($this->map['entry_target_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('entry_target',isset($this->map['entry_target'])?$this->map['entry_target']:''))
                    echo "<script>$('entry_target').value = \"".addslashes(URL::get('entry_target',isset($this->map['entry_target'])?$this->map['entry_target']:''))."\";</script>";
                    ?>
	
        </select></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('go_to_office');?></td>
        <td align="center">:</td>
        <td ><input  name="go_to_office" id="go_to_office" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('go_to_office'));?>">        </td>
      </tr>
      <tr valign="top" style="display:none;">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('provisional_residence');?></td>
        <td align="center">:</td>
        <td ><input  name="provisional_residence" id="provisional_residence" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('provisional_residence'));?>">        </td>
      </tr>
      <tr valign="top" style="display:none;">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('hotel_name');?></td>
        <td align="center">:</td>
        <td ><input  name="hotel_name" id="hotel_name" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('hotel_name'));?>">        </td>
      </tr>
      <tr valign="top" style="display:none;">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('distrisct');?></td>
        <td align="center">:</td>
        <td ><input  name="distrisct" id="distrisct" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('distrisct'));?>">        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('come_from_country');?></td>
        <td align="center">:</td>
        <td ><select  name="come_from" id="come_from" style="width:200px;"><?php
					if(isset($this->map['come_from_list']))
					{
						foreach($this->map['come_from_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('come_from',isset($this->map['come_from'])?$this->map['come_from']:''))
                    echo "<script>$('come_from').value = \"".addslashes(URL::get('come_from',isset($this->map['come_from'])?$this->map['come_from']:''))."\";</script>";
                    ?>
	
        </select>        </td>
      </tr>
      <!--<tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('input_staff');?></td>
        <td align="center">:</td>
        <td ><input  name="input_staff" id="input_staff" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('input_staff'));?>">        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('input_date');?></td>
        <td align="center">:</td>
        <td ><input  name="input_date" id="input_date" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('input_date'));?>"></td>
      </tr> -->
    </table></td>
	<td>
    <table width="450" cellpadding="2" cellspacing="0">

      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('room');?></td>
        <td align="center">:</td>
        <td><select  name="reservation_room_id" id="reservation_room_id"><?php
					if(isset($this->map['reservation_room_id_list']))
					{
						foreach($this->map['reservation_room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_room_id',isset($this->map['reservation_room_id'])?$this->map['reservation_room_id']:''))
                    echo "<script>$('reservation_room_id').value = \"".addslashes(URL::get('reservation_room_id',isset($this->map['reservation_room_id'])?$this->map['reservation_room_id']:''))."\";</script>";
                    ?>
	</select> <span class="notetice"><?php echo Portal::language('only_room_arrival_today');?></span></td>
      </tr>
      <tr valign="top">
        <td width="100" align="right" nowrap="nowrap"><?php echo Portal::language('address');?></td>
        <td align="center">:</td>
        <td ><textarea  name="address" id="address" style="width:300px" rows="3"><?php echo String::html_normalize(URL::get('address',''));?></textarea></td>
      </tr>
	  <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('email');?></td>
	    <td align="center">:</td>
	    <td ><input  name="email" id="email" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>">        </td>
	    </tr>
	  <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('phone');?></td>
	    <td align="center">:</td>
	    <td ><input  name="phone" id="phone" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>">        </td>
	    </tr>
	  <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('fax');?></td>
	    <td align="center">:</td>
	    <td ><input  name="fax" id="fax" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('fax'));?>">        </td>
	    </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('note');?></td>
        <td align="center">:</td>
        <td ><textarea  name="note" id="note" style="width:300px;" rows="3"><?php echo String::html_normalize(URL::get('note',''));?></textarea>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('visa_number');?></td>
        <td align="center">:</td>
        <td><input  name="visa_number" id="visa_number" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('visa_number'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('expire_date_of_visa');?></td>
        <td align="center">:</td>
        <td ><input  name="expire_date_of_visa" id="expire_date_of_visa" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('expire_date_of_visa'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('entry_form_number');?></td>
        <td align="center">:</td>
        <td ><input  name="entry_form_number" id="entry_form_number" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('entry_form_number'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('occupation');?></td>
        <td align="center">:</td>
        <td><input  name="occupation" id="occupation" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('occupation'));?>"></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap"><?php echo Portal::language('member_code');?></td>
        <td align="center">:</td>
        <td><?php if($this->map['member_code']!=0){?><input  name="member_code" id="member_code" style="width:200px" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('member_code'));?>"><?php }else{ ?><span style="color: red;"><?php echo Portal::language('no_code');?></span><br /><input type="checkbox" name="create_member_code" id="create_member_code" onchange="fun_check_email();" /><?php echo Portal::language('create_member_code');?><?php } ?></td>
      </tr>
    </table>
	</table>
	</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
    <td style="text-align: center;"><span style="color: red;">(*)</span> là trường bắt buộc nhập</td>
</tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    
    function fun_check_email(){
        var email = jQuery("#email").val();
        if(document.getElementById("create_member_code").checked==true){
            if(email==''){
                alert('bạn chưa nhập mail! Nhập địa chỉ mail để sử dụng tính năng này.');
                document.getElementById("create_member_code").checked=false;
            }
        }
    }
	jQuery("#time_out").mask("99/99/9999");
	jQuery('#first_name').focus();
	//jQuery("#birth_date").datepicker();
	jQuery("#birth_date").mask("99/99/9999");
	
	//jQuery("#entry_date").datepicker();
	jQuery("#entry_date").mask("99/99/9999");
	
	//jQuery("#back_date").datepicker();
	jQuery("#back_date").mask("99/99/9999");
	
	//jQuery("#expire_date_of_visa").datepicker();
	jQuery("#expire_date_of_visa").mask("99/99/9999");
	
	jQuery('textarea').focus(function (){
		jQuery(this).css('background','#FFFFCC');
		jQuery(this).css('border','1px solid #000');
		jQuery(this).css('padding','2px 2px 2px 0px');
	});
	jQuery('textarea').blur(function (){
		jQuery(this).css('background','');
		jQuery(this).css('border','');
		jQuery(this).css('padding','');
	});
	jQuery('input').focus(function (){
		jQuery(this).css('background','#FFFFCC');
		jQuery(this).css('border','1px solid #000');
		jQuery(this).css('padding','2px 2px 2px 0px');
	});
	jQuery('input').blur(function (){
		jQuery(this).css('background','');
		jQuery(this).css('border','');
		jQuery(this).css('padding','');
	});
	jQuery('select').focus(function (){
		jQuery(this).css('background','#FFFFCC');
	});
	jQuery('select').blur(function (){
		jQuery(this).css('background','');
	});
	function get_traveller()
	{
		if($('passport').value!='' || ($('first_name').value!='' && $('last_name').value!=''))
		{
			ajax.get_text('r_get_traveller.php?edit_directly=1&passport='+$('passport').value, set_traveller);
		}
	}
	function set_traveller(text)
    {
		if(text!=''){
			eval(text);
			$('first_name').value = traveller.first_name;
			$('last_name').value = traveller.last_name;
			$('gender').value = traveller.gender;
			$('birth_date').value = traveller.birth_date;
			$('birth_date_correct').value = traveller.birth_date_correct;
			$('nationality_id').value = traveller.nationality_id;
			$('province_id').value = traveller.province_id;
            $('phone').value = traveller.phone;
			$('note').value = traveller.note;
			
			$('is_vn').value = traveller.is_vn;
			$('entry_date').value = traveller.entry_date;
			$('port_of_entry').value = traveller.port_of_entry;
			$('back_date').value = traveller.back_date;
			//$('note').value = traveller.note;
			//$('note').value = traveller.note;
		}
	}
</script>