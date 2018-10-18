<style>
    *{
        margin: 0 auto;
        padding: 0;
    }
    @media print {
        #select_show_hide
        {
            display: none;
        }
        input[type="checkbox"]
          {
            visibility: hidden;
          }
        
        
          input[type="checkbox"][checked]
          {
            visibility: visible;
          }
    }
</style>
<form name="BookingConfirmForm" method="post">
    <table width="100%">
        <tr>
            <td width="85%">&nbsp;</td>
            <td align="right" style="vertical-align: bottom;" >
                <a onclick="print_booking_confirm();" title="In">
                    <img src="packages/core/skins/default/images/printer.png" height="40" />
                </a>
            </td>
        </tr>
    </table>
    <div id="print" style="width: 85%; margin: 0px auto;">
        <table style="width: width: 100%; margin: 0px auto;">
            <tr style="width: 40%;"> 
                <td rowspan="3" style="text-align: left;"><img src="<?php echo HOTEL_LOGO; ?>" style="width: auto; height: 70px;" /></td>
            </tr>
            <tr>
                <td style="width: 50%;text-align: center;">
                    <span style="font-size: 15pt; color: #ba1c00;" ><b><?php echo HOTEL_NAME; ?></b></span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-size: 9pt; text-align: center;">
                    <span style="color: #85200c;"><strong>Add: <?php echo HOTEL_ADDRESS; ?></strong></span><br /><span style="color: #85200c;"><strong>Tel: <?php echo HOTEL_PHONE; ?> - Fax: <?php echo HOTEL_FAX; ?></strong></span><br /><span style="color: #85200c;"><strong>Web: <?php echo HOTEL_WEBSITE; ?> - Email: <?php echo HOTEL_EMAIL; ?></strong></span>
                </td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; margin: 0px auto;">
            <tr>
                <td style="text-align: right;font-size: 19px; width: 44%;"><b>BOOKING CONFIRM</b></td>
                <td style="text-align: right; width: 30%;"><b style="padding-right: 30px; font-size: 19px; color: #ff0000;">RECODE: [[|recode|]]</b></td>
            </tr>
            <tr>
                <td style="text-align: right;font-size: 19px; width: 44%;"></td>
                <td style="text-align: right; width: 30%;"><b style="padding-right: 45px; font-size: 16px; color: #000;">Total room: [[|total_room|]]</b></td>
            </tr>
            <tr id="tr_hide_price"> 
                <td colspan="3" style="text-align: center;"><span id="select_show_hide"><input name="hide_price" type="checkbox" id="hide_price" value="1" onchange="show_hide(this);"/></span>Hide price</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto; border: 1px solid #333;">
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>To: <?php echo [[=customer_name=]]; ?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b>From: <?php echo HOTEL_NAME;?></b></td>
            </tr>
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Office: <?php echo [[=customer_address=]]; ?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b>Reservation Clerk: <?php echo [[=user_name=]];?></b></td>
            </tr>
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Attention to: <?php echo [[=booker=]];?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b style="float: left;">Tel: <input type="text" name="tel_dn" id="tel_dn" value="[[|tel_booking_cf|]]" style="border: hidden; font-weight: bold;" placeholder="Enter Telephone" onkeyup="change_value(this);"/></b><label id="tel_dn_lb" style="float: left;"></label></td>
            </tr>
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Tel: <?php if([[=phone_booker=]] != ''){ echo [[=phone_booker=]];}else{ echo '.............................................';}?></b>  <b>Email: <?php if([[=email_booker=]]){echo [[=email_booker=]];}else{ echo '.............................................';}?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b>Date: <?php echo date('d/m/Y', [[=create_booking=]]); ?></b></td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr>
                <td style="text-align: right; width: 100px;"><input type="checkbox" name="bcf_status" id="bcf_status_1" value="1" /></td>
                <td style="text-align: left; width: 100px;">NEWBOOKING</td>
                <td style="text-align: right; width: 100px;"><input type="checkbox" name="bcf_status" id="bcf_status_2" value="2" /></td>
                <td style="text-align: left; width: 100px;">AMENDMENT</td>
                <td style="text-align: right; width: 100px;"><input type="checkbox" name="bcf_status" id="bcf_status_3" value="3" /></td>
                <td style="text-align: left; width: 100px;">CANCELLATION</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr style="font-size:12px;">
                <td><strong>Dear value Parner!</strong></td>
            </tr>
            <tr style="font-size:12px;">
                <td>Thank you for selecting <?php echo HOTEL_NAME; ?> as your preferred accommodation in <?php echo CITY_NAME; ?>. We are pleased to confirm your booking request in detail as below:</td>
            </tr>
        </table>
        <table style="width: 100%;"  border="1" cellspacing="0">
            <tr style="text-align: center;font-size:12px;">
                <th rowspan="2">Category</th>
                <th rowspan="2">Quantity</th>
                <th colspan="2">Period</th>
                <th rowspan="2" style="width: 20px;">Pax (A/C)</th>
                <th rowspan="2">No.Night</th>
                <th rowspan="2" class="hidden_price_n2d">Rate</th>
                <th rowspan="2" class="hidden_price_n2d">Total (VND)</th>
            </tr>
            <tr style="text-align: center;font-size:12px;">
                <th>Arrival</th>
                <th>Departure</th>
            </tr>
            <!--LIST:items-->
            <tr style="font-size:12px;">
                <td style="padding-left: 10px;" ><b>[[|items.name|]]</b></td>
                <td style="padding-left: 10px;text-align: center;">[[|items.quantity|]]</td>
                <?php if([[=items.type=]] == 'EXTRA' and ([[=items.from_date=]]==[[=items.to_date=]])){ ?>
                <td style="text-align: center;" colspan="2">[[|items.from_date|]]</td>
                <?php }else{ ?>
                <td style="text-align: center;">[[|items.from_date|]]</td>
                <td style="text-align: center;">[[|items.to_date|]]</td>
                <?php } ?>
                <?php if([[=items.type=]]=='ROOM'){?>
                <td style="text-align: center;">[[|items.adult|]]/[[|items.child|]]</td>
                <?php }else{?>                                                
                <td style="text-align: center;"></td>
                <?php } ?>
                <td style="text-align: center;">[[|items.nights|]]</td>
                <td style="text-align: right;padding-right: 10px;" class="hidden_price_n2d"><?php echo System::display_number([[=items.price=]]);?></td>
                <td style="text-align: right;padding-right: 10px;" class="hidden_price_n2d"><?php echo System::display_number([[=items.total=]]);?></td>
            </tr>
            <!--/LIST:items-->
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td colspan="7" style="text-align: right;padding-right: 10px;"><b>GRAND TOTAL (VND)</b></td>
                <td colspan="1" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=total=]]);?></b></td>
            </tr>
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td style="text-align: right;padding-right: 10px;"><b>Deposit Group(VND)</b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=deposit_group=]]);?></b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;">Due date</td>
                <td style="text-align: right;padding-right: 10px;" onclick="return due_date_dp.focus();"><span contenteditable="true" id="due_date_dp" onkeyup="change_value(this);"><?php echo [[=due_date_dp=]]; ?></span><input name="due_date_dp_ip" type="hidden" id="due_date_dp_ip" value="" style="border: hidden;"/></td>
            </tr>
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td style="text-align: right;padding-right: 10px;"><b>Deposit Room(VND)</b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=total_deopsit=]]);?></b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;">Due date</td>
                <td style="text-align: right;padding-right: 10px;" onclick="return due_date_dpr.focus();"><span contenteditable="true" id="due_date_dpr" onkeyup="change_value(this);"><?php echo [[=due_date_dpr=]]; ?></span><input name="due_date_dpr_ip" type="hidden" id="due_date_dpr_ip" value="" style="border: hidden;"/></td>
            </tr>
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td style="text-align: right;padding-right: 10px;"><b>Balance(VND)</b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=balance=]]);?></b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;">Due date</td>
                <td style="text-align: right;padding-right: 10px;" onclick="return due_date_bl.focus();"><span contenteditable="true" id="due_date_bl" onkeyup="change_value(this);"><?php echo [[=due_date_bl=]]; ?></span><input name="due_date_bl_ip" type="hidden" id="due_date_bl_ip" value="" style="border: hidden;"/></td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr style="font-size:12px;">
                <td style="width: 108px;"><strong>Number of guest :</strong></td>
                <td style="width: 50px;" onclick="return total_adult.focus();"><span contenteditable="true" id="total_adult" onkeyup="change_value(this);"><?php echo [[=adult_bc=]]?[[=adult_bc=]]:[[=total_adult=]]; ?></span><input name="total_adult_ip" type="hidden" id="total_adult_ip" value="" style="border: hidden;" placeholder="Typing date"/> Adult/</td>
                <td onclick="return total_children.focus();"><span contenteditable="true" id="total_children" onkeyup="change_value(this);"><?php echo [[=child_bc=]]?[[=child_bc=]]:[[=total_child=]]; ?></span><input name="total_children_ip" type="hidden" id="total_children_ip" value="" style="border: hidden;" placeholder="Typing date"/> Children </td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr style="font-size:12px;">
                <td><strong>Rate Inclusions</strong></td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Rate is <strong>VND net/ room/ night</strong> (inclusive of 10% VAT and 5% service charge)</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Daily Breakfast for up to 2 people.</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- In-room internet access both WiFi and LAN</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Access to outdoor swimming pool</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Complimentary bottle of drinking water (1 bottle per person), tea and coffee per day in all guest rooms.</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto; height: 40px;"  border="1" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td colspan="4" style="border-right: 1px solid #333; width: 20%;"><strong>Special Request:</strong></td>
                <td>
                    <label id="special_request_lb" style="width: 99%;"></label>                 
                    <textarea name="special_request" id="special_request" style="width: 99%; height: 40px; border: none;" onkeyup="change_value(this);" placeholder="Typing special request">[[|special_request|]]</textarea>             
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Cancellation:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_1" type="text" id="cancel_line_1" value="<?php echo [[=cancel_line_1=]]?[[=cancel_line_1=]]:'More than 07 days prior to check-in day: No charge'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_1_lb"></label></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_2" type="text" id="cancel_line_2" value="<?php echo [[=cancel_line_2=]]?[[=cancel_line_2=]]:'Within 05-07 days prior to check-in day: Room charge of the first nights'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_2_lb"></label></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_3" type="text" id="cancel_line_3" value="<?php echo [[=cancel_line_3=]]?[[=cancel_line_3=]]:'Within 03-07 days prior to check-in day: 50% total room charge'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_3_lb"></label></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_4" type="text" id="cancel_line_4" value="<?php echo [[=cancel_line_4=]]?[[=cancel_line_4=]]:'Within 02 days including no show: 100% total room charge'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_4_lb"></label></td>
            </tr>
            <!--<tr style="text-align: left;font-size:12px;">
                <td><strong>Check-In / Check-Out:</strong> <i>Early check-in and late check-out will be confirmed upon request at special surcharge as per policy of the hotel.</i></td>
            </tr>-->
        </table>
        <!--<table style="width: 100%; margin: 0px auto;" border="1" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">Sur-charge for early check-in</td>
                <td style="padding-left: 10px;">Sur-charge for late check-out</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">Before 06:00 AM: 100% of 01night</td>
                <td style="padding-left: 10px;">After 12:00 - 03:00 PM: 500.000 VND/room</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">From 06:00 - 09:00 AM: 900.000/room</td>
                <td style="padding-left: 10px;">After 12:00 - 03:00 PM: 900.000 VND/room</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">From 09:00 AM - 13:00 PM: 500.000/room</td>
                <td style="padding-left: 10px;">After 06:00 PM: 100% of 01night</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Children policy:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>Maximum 02 kids under 10 years old sharing existing beds and room with parents.</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>Surcharge for breakfast:</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>Under 5 years old: free</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>From 5-10 years old: 125,000VNĐ/ Kid</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>From 11 years old: 250,000VNĐ/ Kid</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Extra bed:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="width: 25%;">Adult:</td>
                <td style="width: 25%;">675,000 VND net/night</td>
                <td style="width: 25%;">Children:</td>
                <td style="width: 25%;">675,000 VND net/night</td>
            </tr>
        </table>-->
        <table style="width: 100%; margin: 0px auto; display:none;" cellspacing="0">
            <!--<tr style="text-align: left;font-size:12px;">
                <td><strong>Additional Charge:</strong> The above rate is applied for registered guest(s) only. We may apply surcharge for extra guests or require guest to book more room(s) for additional guest(s) (if any) based on Hotel’s policy.</td>
            </tr>-->
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Payment:</strong> <i>please settle full payment no later than 07 days prior to check-in day.</i></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Bank account info:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 113px;"><strong>Tên TK/ Bank account:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 113px;"><strong>Số TK VND – VND Account number:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 113px;"><strong>Số TK USD – USD Account number:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 113px;"><strong>CIF:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 113px;"><strong>Bank:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 113px;"><strong>Address:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>If you need further assistance, please feel free to contact us.</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>&nbsp;</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="font-size:12px;">
                <td style="width: 70%;"><strong>By Confirm</strong></td>
                <td style="text-align: left;"><strong>Best regards</strong></td>
            </tr>
            <tr style="font-size:12px;">
            <td style="width: 70%;"><strong><input type="text" name="person_send_book_confirm" id="person_send_book_confirm" value="<?php echo[[=person_send_book_confirm=]]; ?>" style="border: none; font-weight: bold;" placeholder="Enter person send booking confirm" onkeyup="change_value(this);"/></strong><label id="person_send_book_confirm_lb" style="float: left;"></label></strong></td>
                <td style="text-align: left; padding-left: 30px;"><strong>Sales & Marketing Department</strong></td>
            </tr>
        </table>
        <br /><br /><br />
        <hr />
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr>
                <td style="text-align: center;"><em style="font-size: 14px;">Date: <?php echo date('H:i d/m/Y', time()) . ' - Printed by: ' . [[=person_send_book_confirm=]]; ?></em></td>
            </tr>
        </table>
    </div>
</form>
<script>
    jQuery(document).ready(function(){
        jQuery('#special_request_lb').html(jQuery('#special_request').val().replace(/\r?\n/g, '<br />'));
        jQuery('#person_send_book_confirm_lb').html(jQuery('#person_send_book_confirm').val());
        jQuery('#reservation_department_lb').html(jQuery('#reservation_department').val());
        jQuery('#cancel_line_1_lb').html(jQuery('#cancel_line_1').val());
        jQuery('#cancel_line_2_lb').html(jQuery('#cancel_line_2').val());
        jQuery('#cancel_line_3_lb').html(jQuery('#cancel_line_3').val());
        jQuery('#cancel_line_4_lb').html(jQuery('#cancel_line_4').val());
        jQuery('#due_date_dp_ip').val(jQuery('#due_date_dp').html());
        jQuery('#due_date_bl_ip').val(jQuery('#due_date_bl').html());
        jQuery('#total_adult_ip').val(jQuery('#total_adult').html());
        jQuery('#total_children_ip').val(jQuery('#total_children').html());
        jQuery('#special_request_lb').css('display','none');
        jQuery('#reservation_department_lb').css('display','none');
        jQuery('#cancel_line_1_lb').css('display', 'none');
        jQuery('#cancel_line_2_lb').css('display', 'none');
        jQuery('#cancel_line_3_lb').css('display', 'none');
        jQuery('#cancel_line_4_lb').css('display', 'none');
        jQuery('#person_send_book_confirm_lb').css('display', 'none');    
    })
    
    jQuery("#chang_language").css('display','none');
    function show_hide(obj)
    {
        if(jQuery("#hide_price").is(':checked'))
        {
            jQuery(".hidden_price_n2d").css('display','none');
        }
        else
        {
            jQuery(".hidden_price_n2d").css('display','');
        }
    }
    function change_value(obj)
    {
        var id = jQuery(obj).attr('id');
        jQuery('#'+id+"_lb").html(jQuery(obj).val().replace(/\r?\n/g, '<br />'));
        jQuery('#'+id+"_ip").val(jQuery(obj).html());
        jQuery('#'+id+"_lb").css('display', 'none');
    }
    function printTagId(tag_id){
        var printContents = document.getElementById(tag_id).innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
            
    }
    function print_booking_confirm()
    {
        jQuery('#tr_hide_price').css('display','none');
        var inputs = jQuery('table input:checkbox:checked');
        for(var i=0; i<inputs.length; i++)
        { 
            var typ=document.createAttribute("checked");
            typ.nodeValue="true";
            inputs[i].attributes.setNamedItem(typ);
        }
        var user ='<?php echo User::id(); ?>';
        jQuery('#special_request_lb').css('display','block');
        jQuery('#tel_dn_lb').css('display','block');
        jQuery('#reservation_department_lb').css('display','block');
        jQuery('#cancel_line_1_lb').css('display', 'block');
        jQuery('#cancel_line_2_lb').css('display', 'block');
        jQuery('#cancel_line_3_lb').css('display', 'block');
        jQuery('#cancel_line_4_lb').css('display', 'block');
        jQuery('#special_request').css('display','none');
        jQuery('#tel_dn').css('display','none');
        jQuery('#reservation_department').css('display','none');
        jQuery('#cancel_line_1').css('display', 'none');
        jQuery('#cancel_line_2').css('display', 'none');
        jQuery('#cancel_line_3').css('display', 'none');
        jQuery('#cancel_line_4').css('display', 'none');
        printWebPart('print',user);
        BookingConfirmForm.submit();
    }
</script>