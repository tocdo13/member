<?php System::set_page_title(HOTEL_NAME);?>
<div class="tour_type-bound">
<form name="EditSingleReservationForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('action'));?>"  class="button-medium-delete">[[.cancel.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.customer.]]:</td>
					<td><select name="COMPANY_ID" id="COMPANY_ID"></select></td>
				</tr>
                <tr>
					<td class="label">[[.name.]](*):</td>
					<td><input name="NAME" type="text" id="NAME"></td>
				</tr>
				<tr>
                  <td class="label">[[.tour_leader.]]:</td>
				  <td><input name="tour_leader" type="text" id="tour_leader" /></td>
			  </tr>
				<tr>
                  <td class="label">[[.expected_room_quantity.]]:</td>
				  <td><input name="ROOM_QUANTITY" type="text" id="ROOM_QUANTITY" /></td>
			  </tr>
				<tr>
					<td class="label">[[.expected_num_people.]]:</td>
					<td><input name="NUM_PEOPLE" type="text" id="NUM_PEOPLE"></td>
				</tr>
				<tr>
                  <td class="label">[[.note.]]:</td>
				  <td><input name="NOTE" type="text" id="NOTE"></td>
			  </tr>
				<tr>
                  <td class="label">[[.arrival_time.]](*):</td>
				  <td><input name="arrival_time" type="text" id="arrival_time" /></td>
			  </tr>
				<tr>
                  <td class="label">[[.departure_time.]](*):</td>
				  <td><input name="departure_time" type="text" id="departure_time" /></td>
			  </tr>
				<tr>
                  <td class="label">[[.expected_total_amount.]]:</td>
				  <td><input name="total_amount" type="text" id="total_amount" />
			      <span class="label"><?php echo HOTEL_CURRENCY;?></span></td>
			  </tr>
				<tr>
                  <td class="label">[[.extra_amount.]]:</td>
				  <td><input name="extra_amount" type="text" id="extra_amount" />
			      <span class="label"><?php echo HOTEL_CURRENCY;?></span></td>
			  </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript">
	jQuery("#arrival_time").datepicker();
	jQuery("#departure_time").datepicker();
	jQuery("#color").attachColorPicker();
</script>