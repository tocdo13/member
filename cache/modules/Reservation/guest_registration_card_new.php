<style>
.table_bound{
	border:1px solid #CCCCCC;
	}
    .table_bound tr td{line-height:18px}
@media print
{
    .table_bound{
    border:0px !important;
    width: auto !important;
    height: auto !important;
    margin: 0 !important;
    padding: 0 !important;}
    
    .table_bound tr td{width: auto !important;
    height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    line-height:16px !important}
    
    input .room_rate_id_class{
        display: none;
    }
    #show_price{
            display: none;
    }
}
td{
    margin: 0px;
    margin-left: 3px;
    padding: 0px;
}
</style>
<script type="text/javascript">
var flag = false;
</script>
<form name="registration_form" method="post">
<!--<div id="printer_div" style="float: right;">
    <img src="packages/core/skins/default/images/printer.png" height="40" onclick="print_registration_form();" />
</div>-->
<div id="print" style="width:100%;text-align:left;">
<?php if(isset($this->map['guest']) and is_array($this->map['guest'])){ foreach($this->map['guest'] as $key1=>&$item1){if($key1!='current'){$this->map['guest']['current'] = &$item1;?>
<div style="width:520px;text-align:left; ">

<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0" class="table_bound">
    <tr>
        <td align="center">
            <img src="<?php echo HOTEL_LOGO;?>" width="130px" style="margin-top:0px; margin-bottom: 10px;">
        </td>
    </tr>
  <tr>
    <td align="center" ><h3 style="margin: 5px 0;">REGISTRATION CARD</h3>
    <br /> 
            <center><table id="show_price">
                <tr>
                    <td>
                    <input type="checkbox" id="price" name="show price" />
                    </td>
                    <td>show price</td>
                </tr>
            </table></center>
    </td>
  </tr>
  <tr>
    <td align="left">
        <h5 style="margin-top: -10px; margin-bottom: 0px;"> RECODE: &nbsp;&nbsp;<strong><?php echo $this->map['reservation_id'];?></strong></h5>
    </td>
  </tr>
  <tr>
    <td>
		<table cellspacing="0" cellpadding="2" width="100%" border="1px" style="border-collapse:collapse; line-height:20px; font-size:12px;">
		  <tr>
                <td>Guest name:<br /> Tên khách</td>
                
                <td colspan="2"> <strong><?php echo $this->map['guest']['current']['full_name'];?></strong></td>
                
                <td style="border-right: none;">Arrival: <br />Ngày đến</td>
                <td colspan="2"><strong><?php echo $this->map['arrival_time'];?></strong></td>
                
                <!--<td>Room No: <strong><?php echo $this->map['room_name'];?></strong><br>Re.code: <strong><?php echo $this->map['reservation_id'];?>-->
            </td>
  		  </tr>
          <tr>
            <td width="90px">Company:<br />Tên công ty</td>
            <td colspan="2"><strong><?php echo $this->map['customer_name'];?></strong></td>
            
            <td style="border-right: none;">Departure:<br />Ngày đi</td>
            <td colspan="2"><strong><?php echo $this->map['departure_time'];?></strong></td>
            
  		  </tr>
          <?php
            $address ='';
            $check ='';
            $checked_cus ='';
            if($this->map['guest']['current']['address']!='')
            {
                $check =' checked="checked"';
                $address = $this->map['guest']['current']['address'];
            } 
            elseif($this->map['guest']['current']['customer_address']!='')
            {
                $address = $this->map['guest']['current']['customer_address'];
                $checked_cus =' checked="checked"';
            }
          ?>
          <tr>
            <td>Địa chỉ:<br />Address</td>
            <td>Company:<br />Công ty<input name="address[]" type="checkbox" value="company" <?php echo $checked_cus;?> /></td>
            <td>Home:<br />Địa chỉ<input name="address[]" type="checkbox" value="address" <?php echo $check; ?> /></td>
            
            <td colspan="2" rowspan="2">Room rate per night<br />Giá phòng mỗi đêm</td>
            <td colspan="2" rowspan="2" style="border-left: none;">Room rate:<br />
                <span id="room_rate" style="font-size: 12px;"><strong><?php echo System::display_number($this->map['price']); ?></strong></span>
                <span id="room_rate"><?php echo '('.HOTEL_CURRENCY.')';?></span>
            </td>
  		  </tr>
          <tr>
                <td colspan="3" height="40"><span style="font-size: 12px; margin-left: 10px;"><?php echo $address;?></span></td>
          </tr>
          <tr>
                <td style="border-right:none;">Date of birth<br />Ngày sinh</td>
                <td colspan="2" style="border-left: none;"><strong><?php echo $this->map['guest']['current']['birth_date'];?></strong></td>
                <td style="border-right:none;">Telephone:<br />Điện thoại</td>
                <td colspan="2" style="border-left: none;"><strong><?php echo $this->map['guest']['current']['phone'];?></strong></td>  
          </tr>
          <tr>
                <td style="border-right:none;">Room No:<br />Số phòng</td>
                <td colspan="2" style="border-left: none;"><strong><?php echo $this->map['room_name'];?></strong></td>
                <td style="border-right:none;" colspan="2">Room type:<br />Loại phòng</td>
                <td style="border-left: none;"><strong><?php echo $this->map['room_level'];?></strong></td>
          </tr>
          
          <tr>
                <td colspan="6"  style="border-right:none;">
                    Email : <strong><?php echo $this->map['guest']['current']['email'];?></strong> <br />
                    Website : 
                </td>
                
          </tr>
          
          <tr>
                <td colspan="2" style="border-right:none;">Passport No/ID No<br />Hộ chiếu/số CMND</td>
                <td colspan="2" style="border-left: none;"><strong><?php echo $this->map['guest']['current']['passport'];?></strong></td>
                <td style="border-right:none;">Nationality:<br />Quốc gia</td>
                <td style="border-left:none;"><strong><?php echo $this->map['guest']['current']['nationality'];?></strong></td>
          </tr>
          <tr>
                <td style="border-right:none;">Visa No:<br />Số thị thực</td>
                <td colspan="2" style="border-left: none;"><strong><?php echo trim($this->map['guest']['current']['visa_number']);?></strong></td>
                <td colspan="2" style="border-right:none;">Expiry date:<br />Ngày hết hạn</td>
                <td style="border-left: none;"><strong><?php echo trim($this->map['guest']['current']['expire_date_of_visa']);?></strong></td>
          </tr>
          <tr>
                <td>Note:<br />Ghi chú</td>
                <td colspan="5"><strong><?php echo $this->map['guest']['current']['note'];?></strong></td>
          </tr>
          <?php
            $payment_type='';
            if(isset($this->map['guest']['current']['payment_type_id']) && $this->map['guest']['current']['payment_type_id']==2)
                $payment_type= ' checked="checked"';
          ?>
          <tr>
                <td colspan="2">Method of payment:<br />Phương thức thanh toán</td>
                <td colspan="4">
                <label for="cash">Cash</label><input name="payment_type[]" type="checkbox" />
                <label for="visa">Visa</label><input name="payment_type[]" type="checkbox"  />
                <label for="master_card">Master Card</label><input name="payment_type[]" type="checkbox"  />
                <label for="amex">Amex</label><input name="payment_type[]" type="checkbox"  />
                <label for="jcb">JCB</label><input name="payment_type[]" type="checkbox" />
                <label for="dinner">Dinner</label><input name="payment_type[]" type="checkbox" />
                </td>
          </tr>
          
          <!--<tr>
            <td align="center" colspan="6"> 
            <label for="cash">Cash</label><input name="payment_type[]" type="checkbox" <?php echo $payment_type;?> />
            <label for="visa">Visa</label><input name="payment_type[]" type="checkbox"  />
            <label for="master_card">Master Card</label><input name="payment_type[]" type="checkbox"  />
            <label for="amex">Amex</label><input name="payment_type[]" type="checkbox"  />
            <label for="jcb">JCB</label><input name="payment_type[]" type="checkbox" />
            <label for="dinner">Dinner</label><input name="payment_type[]" type="checkbox" />
          </tr>-->
          </table>
          
          
          <p style="font-size:11px; margin-left: 5px; margin-top: 1px; margin-bottom: 1px;"><i>- <?php echo HOTEL_NAME?> accepts no reponsibility for loss or damage of any nature,
          including as a result of any negligent act or ommission to property belonging to guests,
          whether placed in the guest room safe or not, or from any physical injureis suffered by the guest. <br />
		  - Regardless of charge intruction, I agree to be held personally responsible for any and all charges incurred during my stay, and further agree to settle all, or part of my account on demand<br />
          - Upon signature of this registration card, I acknowledge that i have read, understood and accepted the conditions of residence</i>
          </p>
          <table cellspacing="0" cellpadding="2" width="100%" border="1px" style="border-collapse:collapse; line-height:20px; font-size:12px;">
          <tr>
            <td colspan="2"><strong>Checkin by</strong><div style="height: 100px;"></div></td>
            <td colspan="2"><strong>Special Request</strong><div style="height: 100px;"></div>
            </td>
            <td colspan="2"><strong>Guest's Signature</strong><div style="height: 100px;"></div>
            </td>
  		  </tr>
          <!--end giapln-->
          <!---<tr>
			<td width="300" colspan="3" valign="top">
			  Guest    name: <strong><?php echo $this->map['guest']['current']['full_name'];?></strong></td>
			<td width="200" colspan="2" valign="top">Re.code: <strong><?php echo $this->map['reservation_id'];?></td>
			<td width="250" colspan="3"  valign="top">Room    No: <strong><?php echo $this->map['room_name'];?></strong></td>
		  </tr>
		  <tr>
			<td width="350" colspan="3" valign="top">Address: <strong><?php echo $this->map['address'];?></strong> </td>
			<td width="350" colspan="5" valign="top">Contact details: <strong><?php echo $this->map['contact_details'];?></strong><p><br /></p></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Passport/ID:  <strong><?php echo $this->map['passport'];?></strong></td>
			<td width="250" colspan="3" valign="top">Nationality: <strong><?php echo $this->map['nationality'];?></strong></td>
			<td width="250" colspan="3" valign="top">Expiry    date:</td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Date    of birth:  <strong><?php echo $this->map['birth_date'];?></strong></td>
			<td width="500" colspan="6" valign="top">Gender: <strong><?php if($this->map['gender']!=2){echo $this->map['gender']?portal::language('male'):portal::language('female');}else{ echo portal::language('male').'/'.portal::language('female');} ?></strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Visa No.: <strong><?php echo $this->map['visa_number'];?></strong></td>
			<td width="250" colspan="3" valign="top">Port/date of entry: <strong><?php echo $this->map['port'];?>  (<?php echo $this->map['date_entry'];?>)</strong></td>
			<td width="250" colspan="3" valign="top">Expiry    date: <strong><?php echo $this->map['expire_date_of_visa'];?></strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Arrival    date: <strong><?php echo $this->map['arrival_time'];?></strong></td>
			<td width="250" colspan="3" valign="top">Flight    No.: <strong><?php echo $this->map['flight_code'];?></strong></td>
			<td width="250" colspan="3" valign="top">ETA: <strong><?php echo $this->map['flight_arrival_time']?date('d/m/Y H:i',$this->map['flight_arrival_time']):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top">Departure    date: <strong><?php echo $this->map['departure_time'];?></strong></td>
			<td width="250" colspan="3" valign="top">Flight    No.: <strong><?php echo $this->map['flight_code_departure'];?></strong></td>
			<td width="250" colspan="3" valign="top">ETD: <strong><?php echo $this->map['flight_departure_time']?date('d/m/Y H:i',$this->map['flight_departure_time']):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="350" colspan="4" valign="top">Room type reserved: <strong><?php echo $this->map['room_level'];?></strong>
				</td>
			<td width="350" colspan="4" valign="top">Room rate: <input name="room_rate" type="text" style="border:0px;width:130px;font-weight:bold;" value="<?php echo $this->map['show_price']?$this->map['price']:(($this->map['reservation_type']=='Sales')?'':$this->map['reservation_type']);?>" /><?php echo $this->map['show_price']?'('.HOTEL_CURRENCY.')':'';?></td>
		  </tr>
		  <tr>
			<td width="350" colspan="4" valign="top">Company: <strong><?php echo $this->map['customer_name'];?></strong></td>
			<td width="350" colspan="4" valign="top">Address/Contact information: <strong><?php echo $this->map['customer_address'];?> </strong><strong>( <?php echo $this->map['customer_mobile'];?>)</strong></td>
		  </tr>
		  <tr>
			<td width="250" colspan="2" valign="top"><strong>Booking guaranteed by:&nbsp; </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					<strong>Guest    account</strong><br />
			  <input  name="Input36" value="" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('Input36'));?>">Cash<br />
<input  name="Input37" value="" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('Input37'));?>">Credit card<br />
					<strong>
					<input  name="Input38" value="" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('Input38'));?>"></strong>Others</td><td width="250" colspan="3" valign="top">
				<strong><br />
				Company/Travel    agent</strong>
			    <br />
		        <strong>
		        <input  name="Input39" value="" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('Input39'));?>">
		        </strong>Room charge only<br />
			    <strong>
			    <input  name="Input310" value="" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('Input310'));?>">
			    </strong>Room &amp; breakfast<br />
			    <strong>
			    <input  name="Input311" value="" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('Input311'));?>">
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
			<td width="250" colspan="2" valign="top">Remark: <?php echo $this->map['note'];?></td>
		  </tr>--->
		</table>
	</td>
  </tr>
</table>
</div>
<div style="page-break-before:always;"></div>
<?php }}unset($this->map['guest']['current']);} ?>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
jQuery('#price').attr('checked','checked');
    var room_rate='<?php echo System::display_number($this->map['price']); ?>';
    jQuery('#price').click(function(){
        if (jQuery('#price').is(':checked')){
            jQuery('#room_rate').html(room_rate);
        }else{
            jQuery('#room_rate').html('');
        }
    });
/** START - an button in cua menu**/
    var txt=document.getElementById("chang_language").innerHTML;
    txt = txt.replace('<a onclick="printWebPart(\'printer\');" title="Print"><img src="packages/core/skins/default/images/printer.png" height="40"></a> |', "");
    txt = txt.replace('<a onclick="printWebPart(\'printer\');" title="In"><img src="packages/core/skins/default/images/printer.png" height="40"></a> |', "");
    document.getElementById("chang_language").innerHTML = txt;
/** END - an button in cua menu**/
function print_registration_form()
{
    var inputs = jQuery('table input:radio:checked,table input:checkbox:checked');
    jQuery(".table_bound").css({'padding':'0','margin':'0','line-height':'16px'});
    jQuery("td").css({'line-height':'15px'});
    jQuery(".room_rate_lb").css('display','');
    jQuery(".room_rate_id_class").css('display','none');
    for (var i=0;i<inputs.length;i++)
    { 
        var typ=document.createAttribute("checked");
        typ.value="true";
        inputs[i].attributes.setNamedItem(typ);
    }
   /* var inputs = jQuery('table input:text');
    inputs.css('border','none');
    for (var i=0;i<inputs.length;i++)
    { 
        var typ=document.createAttribute("value");
        if(inputs[i].attributes.id)
        {
            typ.value=jQuery('#'+inputs[i].attributes.id.value).val();
            inputs[i].attributes.setNamedItem(typ);
        }
    }*/  
    var user ="<?php echo User::id();?>";
    printWebPart('print',user);
    
}
function hide_price(guest_id)
{
  var obj = document.getElementById('room_rate_id_'+guest_id);
  obj.style.visibility="hidden";
}
</script>
