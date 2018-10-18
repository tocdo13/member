<form name="EditTravellerForm"  method="post">
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left">
			<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td width="50%" class="" style="font-size: 18px; padding-left: 15px;"><i class ="fa fa-file-text w3-text-orange" style="font-size: 22px;"></i> [[.edit_traveller_info.]]</td>
					<td width="50%" align="right" nowrap="nowrap">
                        <?php //if(User::can_add(false,ANY_CATEGORY)){?><!-- <input name="save_stay" type="submit" id="save_stay" value="[[.Save_back_list.]]" class="button-medium-save"/> --><?php //}?>
						<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" id="save" type="submit" value="[[.Save.]]" class="w3-btn w3-blue" onclick="check_click();" /><?php }?>
                        <script>
                            var count_click=0;
                            function check_click(){
                                count_click = count_click + 1;
                                if(count_click=1){
                                    jQuery("#save").css('display','none');
                                }
                            }
                        </script>
						<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('action'));?>"  class="w3-btn w3-green">[[.back.]]</a><?php }?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	<td align="right">
	<!--IF:cond(Url::get('reservation_id'))-->
	<div style="padding:5px;"><strong>[[.guest.]] [[.of.]] [[.group.]]</strong>: <select name="id" id="id" onchange="window.location = '<?php echo Url::build_current(array('cmd','reservation_id'))?>&id='+this.value;"></select></div>
	<!--/IF:cond-->
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
        <td align="right" nowrap="nowrap">[[.first_name.]] <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input name="first_name" type="text" id="first_name"  style="width:200px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.last_name.]] <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input name="last_name" type="text" id="last_name"  style="width:200px" />        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.gender.]] <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><select  name="gender" id="gender">
            <option value="1">1 - [[.male.]]</option>
            <option value="0">0 - [[.female.]]</option>
          </select>
            <script>
					selects = document.getElementsByTagName('select');
					selects[selects.length-1].value = '<?php echo URL::get('gender');?>';
				</script>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.birth_date.]] <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><input name="birth_date" type="text" id="birth_date" style="width:70px; " />
            <select  name="birth_date_correct" id="birth_date_correct" style="height: 20px;">
              <option value="D">D - [[.date_correct.]]</option>
              <option value="M">M - [[.month_correct.]] </option>
              <option value="Y">Y - [[.year_correct.]]</option>
            </select>
            <script>
					$('birth_date_correct').value = '<?php echo URL::get('birth_date_correct');?>';
				</script>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.passport.]] </td>
        <td align="center">:</td>
        <td ><input name="passport" type="text" id="passport" style="width:200px" onchange="get_traveller();" />        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.supply_passport_day.]]</td>
        <td align="center">:</td>
        <td ><input name="supply_passport_day" type="text" id="supply_passport_day" style="width:70px" />        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.place_issuance_passport.]]</td>
        <td align="center">:</td>
        <td ><input name="place_issuance_passport" type="text" id="place_issuance_passport" style="width:70px" />        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.is_vietnamese.]]</td>
        <td align="center">:</td>
        <td ><select name="is_vn" id="is_vn">
          </select>
            <script>
			//$('is_vn').value = '<?php //echo URL::get('is_vn');?>';
        </script>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.nationality.]] <span style="color: red;">(*)</span></td>
        <td align="center">:</td>
        <td ><select name="nationality_id" id="nationality_id" style="width:200px">
          </select>        </td>
      </tr>
      
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.provice.]]</td>
        <td align="center">:</td>
        <td ><select name="province_id" id="province_id" style="width:200px">
          </select>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.entry_date.]]</td>
        <td align="center">:</td>
        <td><input name="entry_date" type="text" id="entry_date" style="width:70px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.port_of_entry.]]</td>
        <td align="center">:</td>
        <td ><select name="port_of_entry" id="port_of_entry" style="width:200px" >
        </select></td>
      </tr>

      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.back_date.]]</td>
        <td align="center">:</td>
        <td ><input name="back_date" type="text" id="back_date" style="width:70px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.arrival_date.]]</td>
        <td align="center">:</td>
        <td ><input name="time_in" type="text" id="time_in" style="width:70px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.departure_date.]]</td>
        <td align="center">:</td>
        <td ><input name="time_out" type="text" id="time_out" style="width:70px" /></td>
      </tr>

      <tr valign="top">
        <td align="right" nowrap="nowrap" width="200">[[.target_of_entry.]] ([[.foreigner.]]) / [[.target_of_provisional_staying.]] ([[.vietnam.]]) </td>
        <td align="center">:</td>
        <td ><select name="entry_target" id="entry_target" style="width:200px" >
        </select></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.go_to_office.]]</td>
        <td align="center">:</td>
        <td ><input name="go_to_office" type="text" id="go_to_office" style="width:200px" />        </td>
      </tr>
      <tr valign="top" style="display:none;">
        <td align="right" nowrap="nowrap">[[.provisional_residence.]]</td>
        <td align="center">:</td>
        <td ><input name="provisional_residence" type="text" id="provisional_residence" style="width:200px" />        </td>
      </tr>
      <tr valign="top" style="display:none;">
        <td align="right" nowrap="nowrap">[[.hotel_name.]]</td>
        <td align="center">:</td>
        <td ><input name="hotel_name" type="text" id="hotel_name" style="width:200px" />        </td>
      </tr>
      <tr valign="top" style="display:none;">
        <td align="right" nowrap="nowrap">[[.distrisct.]]</td>
        <td align="center">:</td>
        <td ><input name="distrisct" type="text" id="distrisct" style="width:200px" />        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.come_from_country.]]</td>
        <td align="center">:</td>
        <td ><select name="come_from" id="come_from" style="width:200px;">
        </select>        </td>
      </tr>
      <!--<tr valign="top">
        <td align="right" nowrap="nowrap">[[.input_staff.]]</td>
        <td align="center">:</td>
        <td ><input name="input_staff" type="text" id="input_staff" style="width:200px" />        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.input_date.]]</td>
        <td align="center">:</td>
        <td ><input name="input_date" type="text" id="input_date" style="width:70px" /></td>
      </tr> -->
    </table></td>
	<td>
    <table width="450" cellpadding="2" cellspacing="0">

      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.room.]]</td>
        <td align="center">:</td>
        <td><select name="reservation_room_id" id="reservation_room_id"></select> <span class="notetice">[[.only_room_arrival_today.]]</span></td>
      </tr>
      <tr valign="top">
        <td width="100" align="right" nowrap="nowrap">[[.address.]]</td>
        <td align="center">:</td>
        <td ><textarea name="address" id="address" style="width:300px" rows="3"></textarea></td>
      </tr>
	  <tr valign="top">
        <td align="right" nowrap="nowrap">[[.email.]]</td>
	    <td align="center">:</td>
	    <td ><input name="email" type="text" id="email" style="width:200px" />        </td>
	    </tr>
	  <tr valign="top">
        <td align="right" nowrap="nowrap">[[.phone.]]</td>
	    <td align="center">:</td>
	    <td ><input name="phone" type="text" id="phone" style="width:200px" />        </td>
	    </tr>
	  <tr valign="top">
        <td align="right" nowrap="nowrap">[[.fax.]]</td>
	    <td align="center">:</td>
	    <td ><input name="fax" type="text" id="fax" style="width:200px" />        </td>
	    </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.note.]]</td>
        <td align="center">:</td>
        <td ><textarea name="note" id="note" style="width:300px;" rows="3"></textarea>        </td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.visa_number.]]</td>
        <td align="center">:</td>
        <td><input name="visa_number" type="text" id="visa_number" style="width:200px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.expire_date_of_visa.]]</td>
        <td align="center">:</td>
        <td ><input name="expire_date_of_visa" type="text" id="expire_date_of_visa" style="width:70px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.entry_form_number.]]</td>
        <td align="center">:</td>
        <td ><input name="entry_form_number" type="text" id="entry_form_number" style="width:200px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.occupation.]]</td>
        <td align="center">:</td>
        <td><input name="occupation" type="text" id="occupation" style="width:200px" /></td>
      </tr>
      <tr valign="top">
        <td align="right" nowrap="nowrap">[[.member_code.]]</td>
        <td align="center">:</td>
        <td><?php if($this->map['member_code']!=0){?><input name="member_code" type="text" id="member_code" style="width:200px" readonly="" /><?php }else{ ?><span style="color: red;">[[.no_code.]]</span><br /><input type="checkbox" name="create_member_code" id="create_member_code" onchange="fun_check_email();" />[[.create_member_code.]]<?php } ?></td>
      </tr>
    </table>
	</table>
	</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
    <td style="text-align: center;"><span style="color: red;">(*)</span> [[.is_required.]]</td>
</tr>
</table>
</form>
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
    
    jQuery("#supply_passport_day").mask("99/99/9999");
	
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
 $('supply_passport_day').value = traveller.supply_passport_day;
			$('place_issuance_passport').value = traveller.place_issuance_passport;
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