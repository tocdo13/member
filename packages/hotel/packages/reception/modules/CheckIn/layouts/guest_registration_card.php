<style>
	.guest-registration-card li{
		list-style:inside;
		margin-bottom:5px;
	}
	p{
		margin:0px;
		padding:0px;
	}
</style>
	<table width="800" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #000000;">
  <tr>
    <td colspan="2" align="center"><h2>GUEST REGISTRATION CARD</h2></td>
  </tr>
  <tr>
    <td width="10%" valign="top"><br /><img src="<?php echo HOTEL_LOGO;?>" width="100"></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="50%"></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td>Booking Code:<strong> [[|id|]]</strong></td>
          </tr>
          <tr>
            <td>Room No:<strong> [[|room_name|]]</strong></td>
          </tr>
          <tr>
            <td>Room Rate:<strong>  [[|room_rate|]] <?php echo HOTEL_CURRENCY;?></strong></td>
          </tr>
          <tr>
            <td>No. of Guest: </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5" rules="rows" frame="hsides">
          <tr>
            <td width="50%">Family name: <strong>[[|full_name|]]</strong></td>
            <td>Gender: 
              <input id="male" type="checkbox" value=""><label> Male</label>
			<input id="female" type="checkbox" value=""><label> Female.</label><label></label></td>
          </tr>
          <tr>
            <td>DOB: <strong>[[|birth_date|]]</strong></td>
            <td>Nationality: <strong>[[|nationality|]]</strong></td>
          </tr>
          <tr>
            <td>Personal Document No: <strong>[[|passport|]]</strong></td>
            <td>Contact No.: <strong>[[|phone|]]</strong></td>
          </tr>
          <tr>
            <td>Arrival: <strong>[[|arrival_time|]]</strong></td>
            <td>Departure: <strong>[[|departure_time|]]</strong></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="150">Method of Payment: </td>
                <td><input name="Input3" type="checkbox" value="" />
                  <label> Cash</label></td>
                <td><input name="Input4" type="checkbox" value="" />
                  <label>Company Account </label></td>
                <td><input name="Input" type="checkbox" value="" />
                  <label>Bank Transfer </label></td>
              </tr>
              <tr>
                <td>Credit card: </td>
                <td><input name="Input3" type="checkbox" value="" />
                    <label> Visa </label></td>
                <td><input name="Input4" type="checkbox" value="" />
                    <label>Master</label></td>
                <td><input name="Input" type="checkbox" value="" />
                    <label>JCB</label></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="Input32" type="checkbox" value="" />
                  <label> Others </label></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
            </tr>
          
          <tr>
            <td colspan="2">Special Request: 
              <input name="Input2" type="checkbox" value="" />
              <label> Flowers</label>
              <input name="Input2" type="checkbox" value="" />
              <label> Canap&eacute;s</label>
              <input name="Input2" type="checkbox" value="" />
              <label>Fruits</label>
			   <input name="wines" type="checkbox" value="" />
              <label>Wines</label>
			   <input name="Input2" type="checkbox" value="" />
              <label>Others</label></td>
            </tr>
        </table></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><p>Remarks:
	<p>
		<textarea name="note" style="width:98%;">[[|note|]]</textarea>
	</p>
	</td>
  </tr>
</table>
<div style="padding:10px;width:800px;" class="guest-registration-card">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td align="center" style="border-top:1px solid #000000;"><strong> Receptionist&rsquo;s Signature</strong></td>
		<td width="300"></td>
		<td align="center" style="border-top:1px solid #000000;"><strong>Guest's Signature</strong></td>
	  </tr>
	</table>
  </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p align="center">&nbsp;</p>
</div>
<script>
	$('male').checked = ([[|gender|]]=='1')?true:false;
	$('female').checked = ([[|gender|]]=='10')?true:false;
</script>