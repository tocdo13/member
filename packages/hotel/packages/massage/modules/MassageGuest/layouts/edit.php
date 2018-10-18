<?php System::set_page_title(HOTEL_NAME);?>
<div class="MassageGuest_type-bound">
<form name="EditMassageGuestForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.group.]]:</td>
					<td><select name="category" id="category"></select></td>
				</tr>
				<tr>
                  <td class="label">[[.code.]](*):</td>
				  <td ><input name="code" type="text" id="code" style="text-transform: uppercase;"  /></td>
			  </tr>
				<tr>
                  <td class="label">[[.full_name.]](*):</td>
				  <td><input name="full_name" type="text" id="full_name" /></td>
				</tr>
				<tr>
				  <td class="label">[[.gender.]]</td>
				  <td><select name="gender" id="gender">
				    </select>				  </td>
			  </tr>
				<tr>
                  <td class="label">[[.phone.]]</td>
				  <td><input name="phone" type="text" id="phone" /></td>
			  </tr>
				<tr>
				  <td class="label">[[.address.]]</td>
				  <td><textarea name="address" id="address"></textarea></td>
			  </tr>
				<tr>
                  <td class="label">[[.email.]]</td>
				  <td><input name="email" type="text" id="email" /></td>
			  </tr>
				<tr>
                  <td class="label">[[.note.]]</td>
				  <td><textarea name="note" id="note"></textarea></td>
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
</script>