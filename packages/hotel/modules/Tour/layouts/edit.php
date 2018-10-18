<?php System::set_page_title(HOTEL_NAME);?>
<div class="tour_type-bound">
<form name="EditTourForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="90%" class="form-title">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right" nowrap="nowrap"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%" align="right" nowrap="nowrap"><a href="<?php echo Url::build_current(array('action'));?>"  class="button-medium-back">[[.back.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<legend class="title">[[.general_info.]]</legend>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td align="right" width="200">[[.customer.]]</td>
					<td>:</td>
					<td><select name="COMPANY_ID" id="COMPANY_ID"></select></td>
				</tr>
                                <tr>
					<td align="right">[[.name.]](*)</td>
					<td>:</td>
					<td><input name="name" type="text" id="name"></td>
				</tr>
                                <tr>
					<td align="right">Name set</td>
					<td>:</td>
					<td><input name="name_set" type="text" id="name_set"></td>
				</tr>
                                <tr>
					<td align="right">Phone set</td>
					<td>:</td>
					<td><input name="phone_set" type="text" id="phone_set"></td>
				</tr>
				<tr>
                  <td align="right">[[.tour_leader.]]</td>
				  <td>:</td>
				  <td><input name="tour_leader" type="text" id="tour_leader" /></td>
			  </tr>
				<tr>
                  <td align="right">[[.expected_room_quantity.]]</td>
				  <td>:</td>
				  <td><input name="ROOM_QUANTITY" type="text" id="ROOM_QUANTITY" /></td>
			  </tr>
				<tr>
					<td align="right">[[.expected_num_people.]]</td>
					<td>:</td>
					<td><input name="NUM_PEOPLE" type="text" id="NUM_PEOPLE"></td>
				</tr>
				<tr>
                  <td align="right">[[.note.]]</td>
				  <td>:</td>
				  <td><input name="NOTE" type="text" id="NOTE"></td>
			  </tr>
				<tr>
                  <td align="right">[[.arrival_time.]]</td>
				  <td>:</td>
				  <td><input name="arrival_time" type="text" id="arrival_time" /></td>
			  </tr>
				<tr>
                  <td align="right">[[.departure_time.]]</td>
				  <td>:</td>
				  <td><input name="departure_time" type="text" id="departure_time" /></td>
			  </tr>
				<tr style="display:none;">
                  <td align="right">[[.expected_total_amount.]]</td>
				  <td>:</td>
				  <td><input name="total_amount" type="text" id="total_amount" />
			      <span align="right"><?php echo HOTEL_CURRENCY;?></span></td>
			  </tr>
				<tr style="display:none;">
                  <td align="right">[[.extra_amount.]]</td>
				  <td>:</td>
				  <td><input name="extra_amount" type="text" id="extra_amount" />
			      <span align="right"><?php echo HOTEL_CURRENCY;?></span></td>
			  </tr>
			</table>
	  </fieldset><br />
	  <fieldset>
			<legend class="title">[[.PA18_template.]]</legend>
            <table width="400" cellpadding="2" cellspacing="0">

              <tr valign="top">
                <td align="right" nowrap="nowrap">[[.is_vietnamese.]]</td>
                <td align="center">:</td>
                <td ><select  name="is_vn" id="is_vn">
                    <option value="0">0 - [[.Alien.]]</option>
                    <option value="1">1 - [[.Overseas_Vietnamese.]]</option>
                    <option value="2">2 - [[.Viet_nam.]]</option>
                  </select>
                    <script>
			$('is_vn').value = '<?php echo URL::get('is_vn');?>';
              </script>                </td>
              </tr>
              <tr valign="top">
                <td align="right" nowrap="nowrap">[[.entry_date.]]</td>
                <td align="center">:</td>
                <td ><input name="entry_date" type="text" id="entry_date" style="width:70px" /></td>
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
                <td align="right" nowrap="nowrap" width="200">[[.target_of_entry.]] ([[.foreigner.]]) / [[.target_of_provisional_staying.]] ([[.vietnam.]])</td>
                <td align="center">:</td>
                <td ><select name="entry_target" id="entry_target" style="width:200px" >
                </select></td>
              </tr>
              <tr valign="top">
                <td align="right" nowrap="nowrap">[[.go_to_office.]]</td>
                <td align="center">:</td>
                <td ><input name="go_to_office" type="text" id="go_to_office" style="width:200px" />                </td>
              </tr>
              <tr valign="top">
                <td align="right" nowrap="nowrap">[[.come_from_country.]]</td>
                <td align="center">:</td>
                <td ><select name="come_from" id="come_from" style="width:200px;">
                  </select>                </td>
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
            </table>
	  </fieldset>		
	</div>
</form>	
</div>
<script type="text/javascript">
	jQuery("#arrival_time").datepicker();
	jQuery("#departure_time").datepicker();
	
	jQuery("#entry_date").datepicker();
	jQuery("#entry_date").mask("99/99/9999");
	
	jQuery("#back_date").datepicker();
	jQuery("#back_date").mask("99/99/9999");
	
	jQuery("#expire_date_of_visa").datepicker();
	jQuery("#expire_date_of_visa").mask("99/99/9999");
</script>