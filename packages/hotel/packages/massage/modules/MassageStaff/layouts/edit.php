<?php //System::set_page_title(HOTEL_NAME);?>
<div class="MassageStaff_type-bound">
<form name="EditMassageStaffForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
            <td width="45%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-gray" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.cancel.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2" class="w3-light-gray">
				<tr>
                  <td class="label">[[.full_name.]](*):</td>
				  <td><input name="full_name" type="text" id="full_name" style=" height: 24px;"/></td>
                  <td class="label">[[.birth_date.]]:</td>
				  <td><input name="birth_date" type="text" id="birth_date" style=" height: 24px;"/></td>
                  <td class="label">[[.gender.]]</td>
				  <td><select name="gender_id" id="gender_id" style="width: 100px; height: 24px;"></select></td>
				</tr>
				<tr>
                  <td class="label">[[.native.]]</td>
				  <td colspan="5"><textarea name="native" id="native" style="width: 625px;  height: 24px;"></textarea></td>
			  </tr>              
				<tr>
				  <td class="label">[[.address.]]</td>
				  <td colspan="5"><textarea name="address" id="address" style="width: 625px; height: 24px;"></textarea></td>
			  </tr>              
				<tr>
                  <td class="label">[[.email.]]</td>
				  <td><input name="email" type="text" id="email" style=" height: 24px;"/></td>
                  <td class="label">[[.mobile_number.]]</td>
				  <td><input name="phone" type="text" id="phone" style=" height: 24px;"/></td>
                  <td class="label">[[.marrital_status.]]</td>				 
			     <td><select name="marial_id" id="marial_id"></select></td>                                  
              </tr>
              <tr>
                  <td class="label">[[.brief.]]</td>
				  <td colspan="5"><textarea name="description" id="description" style="width: 625px; height: 24px;"></textarea></td>
			  </tr>
				<tr>
                  <td class="label">[[.date_in.]]</td>
				  <td><input name="date_in" type="text" id="date_in" style=" height: 24px;" /></td>
                  <td class="label">[[.date_out.]]</td>
				  <td><input name="date_out" type="text" id="date_out" style=" height: 24px;" /></td>
                  <td class="label">[[.status.]]</td>
				  <td><select name="status" id="status" style="width: 100px; height: 24px;"></select></td>
			  </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script>
	jQuery("#birth_date").datepicker();
	jQuery("#date_in").datepicker();
	jQuery("#date_out").datepicker();
    jQuery("#birth_date").mask("99/99/9999");
	jQuery("#date_in").mask("99/99/9999");
	jQuery("#date_out").mask("99/99/9999");
</script>