<style>
.table_bound{
	border:1px solid #CCCCCC;
	}
#guest_name_lbl{
        display: none;
    }
	p{
		margin:0px;
		padding:0px;
	}
    @media print {
        #guest_name
        {
            display: none;
            
        }
        #guest_name_lbl
        {
            display: block;
        }
       .table_bound{border:0px;}
}
</style>
<!--LIST:guest-->
<div>
<table width="756" border="0" cellspacing="0" cellpadding="0" class="table_bound">
  <tr>
    <td align="center"><h1><img src="<?php echo HOTEL_LOGO;?>" width="70px" style="float:left;margin-bottom:10px;"> REGISTRATION FORM</h1></td>
  </tr>
  <tr>
    <td>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		  <tr>
			<td width="300" colspan="3" valign="top">
			  Guest    name: <strong><form name="GuestRegistrationCardForm" method="post">
                                            <label id="guest_name_lbl">[[|full_name|]]</label>
                                            <select name="guest_name" id="guest_name" onchange="check_traveller(this);"></select>
                                         </form></strong></td>
			<td width="200" colspan="2" valign="top">Re.code: <strong>[[|reservation_id|]]</td>
			<td width="250" colspan="3"  valign="top">Room    No: <strong>[[|room_name|]]</strong></td>
		  </tr>
		  <tr>
			<td width="350" colspan="3" valign="top">Address: <strong>[[|address|]]</strong> </td>
			<td width="350" colspan="5" valign="top">Contact details: <strong>[[|contact_details|]]</strong><p><br /></p></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Passport/ID:  <strong>[[|passport|]]</strong></td>
			<td width="250" colspan="3" valign="top">Nationality: <strong>[[|nationality|]]</strong></td>
			<td width="250" colspan="3" valign="top">Expiry    date:</td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Date    of birth:  <strong>[[|birth_date|]]</strong></td>
			<td width="500" colspan="6" valign="top">Gender: <strong><?php if([[=gender=]]!=2){echo [[=gender=]]?portal::language('male'):portal::language('female');}else{ echo portal::language('male').'/'.portal::language('female');} ?></strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Visa No.: <strong>[[|visa_number|]]</strong></td>
			<td width="250" colspan="3" valign="top">Port/date of entry: <strong>[[|port|]]  ([[|date_entry|]])</strong></td>
			<td width="250" colspan="3" valign="top">Expiry    date: <strong>[[|expire_date_of_visa|]]</strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Arrival    date: <strong>[[|arrival_time|]]</strong></td>
			<td width="250" colspan="3" valign="top">Flight    No.: <strong>[[|flight_code|]]</strong></td>
			<td width="250" colspan="3" valign="top">ETA: <strong><?php echo [[=flight_arrival_time=]]?date('d/m/Y H:i',[[=flight_arrival_time=]]):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Departure    date: <strong>[[|departure_time|]]</strong></td>
			<td width="250" colspan="3" valign="top">Flight    No.: <strong>[[|flight_code_departure|]]</strong></td>
			<td width="250" colspan="3" valign="top">ETD: <strong><?php echo [[=flight_departure_time=]]?date('d/m/Y H:i',[[=flight_departure_time=]]):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="350" colspan="4" valign="top">Room type reserved: <strong>[[|room_level|]]</strong>
				</td>
			<td width="350" colspan="4" valign="top">Room rate: <input name="room_rate" type="text" style="border:0px;width:130px;font-weight:bold;" value="<?php echo [[=show_price=]]?[[=room_rate=]]:(([[=reservation_type=]]=='Sales')?'':[[=reservation_type=]]);?>" /><?php echo [[=show_price=]]?'('.HOTEL_CURRENCY.')':'';?></td>
		  </tr>
		  <tr>
			<td width="350" colspan="4" valign="top">Company: <strong>[[|customer_name|]]</strong></td>
			<td width="350" colspan="4" valign="top">Address/Contact information: <strong>[[|customer_address|]] </strong><strong>( [[|customer_mobile|]])</strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top"><strong>Booking guaranteed by:&nbsp; </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					<strong>Guest    account</strong><br />
			  <input name="Input36" type="checkbox" value="" />Cash<br />
<input name="Input37" type="checkbox" value="" />Credit card<br />
					<strong>
					<input name="Input38" type="checkbox" value="" /></strong>Others</td><td width="250" colspan="3" valign="top">
				<strong><br />
				Company/Travel    agent</strong>
			    <br />
		        <strong>
		        <input name="Input39" type="checkbox" value="" />
		        </strong>Room charge only<br />
			    <strong>
			    <input name="Input310" type="checkbox" value="" />
			    </strong>Room &amp; breakfast<br />
			    <strong>
			    <input name="Input311" type="checkbox" value="" />
			    </strong>All expenses</td>
			<td width="250" colspan="3" valign="top"><strong><br />
		    Others</strong></td>
		  </tr>
		  <tr>
			<td width="300" colspan="3" valign="top">Credit    card number:<br />
		    <br /></td>
			<td width="300" colspan="3" valign="top">Type    of credit card</td>
			<td width="150" colspan="2" valign="top">Expiry    date/CVV</td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Guest signature:<br />
		      <br />
		      <br />
		      <br />
		    <br /></td>
            <td width="250" colspan="3" valign="top">Reception signature:<br />
		      <br />
		      <br />
		      <br />
		    <br /></td>
			<td width="250" colspan="2" valign="top">Remark: [[|note|]]</td>
		  </tr>
		</table>
		<p><i>- Guest are advised to read the notice displaced on the Reception Counter. Valuables must be placed in the safe deposit box at the Reception. The hotel will not be responsible for any losses in the rooms.<br />
		  - Check-in time: 14.00, Check-out time: 12 noon. Late check-out availability should be checked in advance with the Reception 50% of the daily room rate is charged for the extension stay up to 6.00 PM. After 6.00 PM, a full daily rate will be applied.</i>
          </p>
	</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
<!--/LIST:guest-->
<script>
function check_traveller(obj){
         GuestRegistrationCardForm.submit();
    }
    function show_traveller(){
        jQuery("#traveller").css('display','block');
    }
</script>