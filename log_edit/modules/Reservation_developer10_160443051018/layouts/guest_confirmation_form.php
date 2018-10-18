55<style>
.table_bound{
	border:1px solid #CCCCCC; 
margin-top:25px;
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
<!--LIST:guest--><table width="756" border="0" cellspacing="0" cellpadding="0" class="table_bound">
  <tr>
    <td align="center"><h1><img src="<?php echo HOTEL_LOGO;?>" width="70px" style="float:left;margin-bottom:10px;">CONFIRMATION FORM</h1></td>
  </tr>
  <tr>
    <td>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		  <tr>
			<td width="375" colspan="4" valign="top">Company: <strong>[[|customer_name|]]</strong></td>
			<td width="375" colspan="4" valign="top">Address: <strong>[[|customer_address|]] </strong></td>
		  </tr>
          <tr>
			<td width="200" colspan="2" valign="top">
			 Tel: <strong>[[|customer_phone|]]</strong></td>
			<td width="200" colspan="2" valign="top">Fax: <strong>[[|customer_fax|]]</td>
			<td width="200" colspan="2"  valign="top">Email: <strong>[[|customer_email|]]</strong></td>
            <td width="150" colspan="2"  valign="top">Re.code: <strong>[[|reservation_id|]]</strong></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">Thank you for your interest in First Hotel, we are please confirm the following:</td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top"><strong>NEW
			  <input name="Input312" type="checkbox" value="" />
			</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<strong>AMENDMENT
			<input name="Input313" type="checkbox" value="" />
			</strong>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CANCELLATION
			<input name="Input314" type="checkbox" value="" />
			</strong></td>
		  </tr>
          <tr>
			<td width="350" colspan="4" valign="top">
			  Guest    name: <strong><form name="GuestRegistrationCardForm" method="post">
                                            <label id="guest_name_lbl">[[|full_name|]]</label>
                                            <select name="guest_name" id="guest_name" onchange="check_traveller(this);"></select>
                                         </form></strong></td>
			<td width="350" colspan="4" valign="top">Gender: <strong><?php if([[=gender=]]!=2){echo [[=gender=]]?portal::language('male'):portal::language('female');}else{ echo portal::language('male').'/'.portal::language('female');} ?></strong></td>
			</tr>
		  <tr>
			<td width="375" colspan="4" valign="top">Arrival    date: <strong>[[|arrival_time|]]</strong></td>
			<td width="375" colspan="4" valign="top">Departure date: <strong>[[|departure_time|]]</strong></td>
			</tr>
          <tr>
          <tr>
			<td width="250" colspan="3" valign="top">Room type: <strong>[[|room_level|]]</strong></td>
			<td width="250" colspan="2" valign="top">No of room: <strong>[[|room_name|]]</strong></td>
            <td width="250" colspan="3" valign="top" align="left">Rate: <input name="room_rate" type="text" style="border:0px;width:130px;font-weight:bold;" value="<?php echo [[=show_price=]]?[[=room_rate=]]:[[=reservation_type=]];?>" /><?php echo [[=show_price=]]?'('.HOTEL_CURRENCY.')':'';?></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">- ++ <i>Rate is subject to 10% VAT and 5% service charge</i><br />
            - <i>Room rate is inclusive of buffet breakfast, use of swimming pool, fitness room and wifi (with the guest's own laptop)</i></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">Transfer request: <strong>
			  <input name="Input312" type="checkbox" value="" />
			</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transfer fee: .............................................</td>
		  </tr>
          <tr>
			<td width="375" colspan="4" valign="top">Flight    No.: <strong>[[|flight_code|]]</strong></td>
			<td width="375" colspan="4" valign="top">ETA: <strong><?php echo [[=flight_arrival_time=]]?date('d/m/Y H:i',[[=flight_arrival_time=]]):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="300" colspan="4" valign="top">Flight    No.: <strong>[[|flight_code_departure|]]</strong></td>
			<td width="450" colspan="4" valign="top">ETD: <strong><?php echo [[=flight_departure_time=]]?date('d/m/Y H:i',[[=flight_departure_time=]]):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="375" colspan="3"  valign="top"><strong>Payment instruction:&nbsp; </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					<strong>Guest    account</strong>
			  
			  <input name="Input36" type="checkbox" value="" /></td>
			<td width="375" colspan="5" valign="top">
				<strong><br />
				Company/Travel    agent</strong>
			    <br />
		        <strong>
		        <input name="Input39" type="checkbox" value="" />
		        </strong>Room charge only &nbsp;&nbsp;
			    <strong>
			    <input name="Input310" type="checkbox" value="" />
			    </strong>All expenses &nbsp;&nbsp;
			    <strong>
			    <input name="Input311" type="checkbox" value="" />
			    </strong>Others ...............</td>
			
		  </tr>
		  <tr>
			<td width="756" colspan="8" valign="top">Special request: Before Check Out</td>
		  </tr>
		  <tr>
			<td width="756" colspan="8" valign="top"><strong>Please note:</strong><br /><i>
            1. Check-in time: 14.00. Check-out time: 12.00. For early check-in from 6.00 to 12.00 or late check-out from 12.00 to 18.00, 50% of the room rate will be charged. For early check-in before 6.00 or late check-out after 18.00, 100% of the room rate will be collected.<br />
            2. All reservations, unless guaranteed, will be held until 4.00 PM of the expected arrival date. In the high seasons, only the guaranteed reservations are accepted.<br />
            3. The reservations may be guaranteed by cash or credit card with the amount equivalent to the room rate x the number of the reserved room. If the credit card is used, please provide us with the copy of the card holder passport/ID card, both sides of the credit card and the card detail as follows:</i></td>
		  </tr>
          <tr>
			<td width="375" colspan="4" valign="top"><i>- Card holder name:</i></td>
			<td width="375" colspan="4" valign="top"><i>- Type of card:</i></td>
		  </tr>
          <tr>
			<td width="375" colspan="4" valign="top"><i>- Card number:</i></td>
			<td width="375" colspan="4" valign="top"><i>- Expiry date:</i></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top"><i>
            4. Amendment or cancellation must be made with prior notice outside of 72 hours before 12.00 PM of the arrival date. Cancellation, amendment with less than 72 hours advance notice or no show, or not present your ID card or Passport, will be charged a fee equivalent to one night room rent x the number of the reserved rooms.<br />
            5. Should we not hear from you by ................................................, we will consider that you no longer require the room and will release the reservation automatically.</i></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top"><strong>Please sign for the acceptance of the above detail and send back to us</strong></td>
		  </tr>
		  <tr>
			<td width="375" colspan="4" valign="top">Confirmed by:<br />
		      <br />
		      <br /></td>
            <td width="375" colspan="4" valign="top" align="right">Approved by (Card holder):<br />
		      <br />
		      <br /></td>
			
		  </tr>
		</table>
		<p align="center">18 Hoang Viet St., Tan Binh Dist., Hochiminh City&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel:&nbsp;<strong>(848).3844.1199</strong>&nbsp;&nbsp;&nbsp;&nbsp; Fax:&nbsp;<strong>(848).3844.4282</strong>
        <br />
        Email: reception@firsthotel.com.vn&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Website:&nbsp;<strong>http://www.firsthotel.com.vn</strong></p>
	
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
<!--/LIST:guest-->
<script>
	//$('male').checked = ([[|gender|]]=='1')?true:false;
	//$('female').checked = ([[|gender|]]=='10')?true:false;
    
    function check_traveller(obj){
         GuestRegistrationCardForm.submit();
    }
    function show_traveller(){
        jQuery("#traveller").css('display','block');
    }
    //console.log(list_items);
    
</script>