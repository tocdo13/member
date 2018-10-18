<style>
    *{
        line-height: 20px;
    }
    @media print {
        #select_show_hide
        {
            display: none;
        }
    }
    /**#chang_language{ display: none;}**/
</style>

<?php $total_vnd = 0; ?>
<form name="BookingConfirmForm" method="post">
    <table width="100%">
        <tr>
        <td width="85%">&nbsp;</td>
        <td align="right" style="vertical-align: bottom;" >
            <input name="save" type="submit" value="Save" class="button-medium-save" onclick="opener.update_from_bcf(1,jQuery('#note').val(),jQuery('#cut_of_date').val());" >
            <a onclick="print(true);" title="In">
                <img src="packages/core/skins/default/images/printer.png" height="40" />
            </a>
        </td>
        </tr>
    </table>
    
<input type="text" name="total" id="total" value="<?php echo count($this->map['reservation_room_type']); ?>" style="display: none;" />

    <div id="print" style="width: 80%; margin: 10px auto;">
        <table style="width: 100%; ">
            <tr>
                <td style="text-align: left;"><img src="<?php echo HOTEL_LOGO; ?>" style="width: 150px; height: auto;" /></td>
                <td style="width: 100px;">
                    <strong>Booking No:</strong><br />
                    <strong>Date:</strong>
                </td>
                <td style="width: 70px; text-align: center;">
                    <b><?php echo $this->map['customer']['booking_code']; ?></b><br />
                    <b><?php echo date("d/m/y"); ?></b>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center;"><h1>BOOKING CONFIRM</h1></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center;"><span id="select_show_hide">Hide price: <input name="hide_price" type="checkbox" id="hide_price" value="1" onchange="show_hide(this);"/></span></td>
            </tr>
<tr>
                <td colspan="3" style="text-align: left;">Thank you very much for your kind interest in the Vinh Hoang Hotel and your information provided regarding the accommodation. We are pleased to confirm your booking as follows:</td>
            </tr>
        </table>
        <div style="border: 1px solid #171717; width: 100%;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 70px;">Attention:</td>
                    <td colspan="5">
                        <input name="attention" type="text" id="attention" style=" border: none; border-bottom: 1px dashed #999; height: 20px; width: 100%;" onkeyup="change_lbl(this);" />
                        <label id="attention_lbl" style="width: 100%; display: none;"></label>
                    </td>
                </tr>
                <tr>
                    <td>Company:</td>
                    <td colspan="5"><b><?php echo ucfirst($this->map['customer']['ctm_name']); ?></b></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td colspan="5"><b><?php echo $this->map['customer']['ctm_add']; ?></b></td>
                </tr>
                <tr>
                    <td>Tax Code:</td>
                    <td><b><?php echo $this->map['customer']['ctm_tax_code'] ?></b></td>
                    <td>Tel:</td>
                    <td><b><?php echo $this->map['customer']['ctm_phone']; ?></b></td>
                    <td>Fax:</td>
                    <td><b><?php echo $this->map['customer']['ctm_fax']; ?></b></td>
                </tr>
                <tr>
                    <td>E-Mail:</td>
                    <td colspan="5"><?php echo $this->map['customer']['ctm_email']; ?></td>
                </tr>
            </table>
        </div>
        <div style="width: 100%;">
            <table style="width: 600px; margin: 0px auto;">
                <tr>
                    <td style="text-align: right; width: 100px;"><input type="radio" name="bcf_status" id="bcf_status_1" value="1" /></td>
                    <td style="text-align: left; width: 100px;">NEW</td>
                    <td style="text-align: right; width: 100px;"><input type="radio" name="bcf_status" id="bcf_status_2" value="2" /></td>
                    <td style="text-align: left; width: 100px;">AMENDMENT</td>
                    <td style="text-align: right; width: 100px;"><input type="radio" name="bcf_status" id="bcf_status_3" value="3" /></td>
                    <td style="text-align: left; width: 100px;">CANCELLATION</td>
                </tr>
            </table>
        </div>
        <div>
            <table style="width: 100%;"  border="0" cellspacing="0">
            <tr>
            <td style="width: 100px;">
            <b>GROUP'S NAME: </b>
            </td>
            <td>
            <input name="guests_name" type="text" id="guests_name" style="border: none; border-bottom: 1px dashed #171717; width: 100%;" onkeyup="change_lbl(this);" />
            <label id="guests_name_lbl" style="width: 100%; display: none;"></label>
            </td>
            </tr>
            </table>
            <table style="width: 100%;"  border="1" cellspacing="0">
                <tr style="text-align: center;">
                    <th>GUEST'S NAME</th>
                    <th>ROOM TYPE</th>
                    <!--LIST:room_type_name-->
                    <th>[[|room_type_name.brief_name|]]</th>
                    <!--/LIST:room_type_name-->
                    <th>C/IN</th>
                    <th>C/OUT</th>
                    <th>N OF R</th>
                    <th>N OF N</th>
                    <th>RATE</th>
                    <th>FOC</th>
                    <th>TOTAL(VND)</th>
                </tr>
                <?php $u = 0; ?>
                <!--LIST:reservation_room_type-->
                <?php $u+=1; ?>
                <tr>
                    <td>[[|reservation_room_type.g_name|]]</td>
                    <td>[[|reservation_room_type.name|]]</td>
                    <?php $v = 0; ?>
                    <!--LIST:room_type_name-->
                    <?php $v+=1; ?>
                    <?php if([[=room_type_name.brief_name=]]==[[=reservation_room_type.brief_name=]]){ ?>
                        <td style="text-align: right;">
                            <input name="checkbox_[[|reservation_room_type.id|]]_[[|room_type_name.brief_name|]]" type="checkbox" id="checkbox_[[|reservation_room_type.id|]]_[[|room_type_name.brief_name|]]" style="width: 20px; height: 20px; background: #ffffff; border: none; border-radius: 50%; display: none;" onchange="test_checkbox(this);" class="checkbox_room" />
                            <img src="packages\hotel\packages\reception\skins\default\images\select.png" id="img_checkbox_[[|reservation_room_type.id|]]_[[|room_type_name.brief_name|]]" style="width: 20px; height: auto; margin: 0px auto;" onchange="test_img_checkbox(this);" />
                        </td>
                    <?php }else{ ?>
                        <td style="text-align: right;">
                            <input name="checkbox_[[|reservation_room_type.id|]]_[[|room_type_name.brief_name|]]" type="checkbox" id="checkbox_[[|reservation_room_type.id|]]_[[|room_type_name.brief_name|]]" style="width: 20px; height: 20px; background: #ffffff; border: none; border-radius: 50%;" onchange="test_checkbox(this);" class="checkbox_room" />
                            <img src="packages\hotel\packages\reception\skins\default\images\select.png" style="display: none; width: 20px; height: auto; margin: 0px auto;" id="img_checkbox_[[|reservation_room_type.id|]]_[[|room_type_name.brief_name|]]" onchange="test_img_checkbox(this);" />
                        </td>
                    <?php } ?>
                    <!--/LIST:room_type_name-->
                    <td style="text-align: right;">[[|reservation_room_type.c_in|]]</td>
                    <td style="text-align: right;">[[|reservation_room_type.c_out|]]</td>
                    <td style="text-align: right;">[[|reservation_room_type.num_room|]]</td>
                    <td style="text-align: right;">[[|reservation_room_type.num_date|]]</td>
                    <td style="text-align: right;"><span class="price_room"><?php echo System::display_number([[=reservation_room_type.rate=]]); ?></span></td>
                    <td style="text-align: right;">[[|reservation_room_type.foc|]]</td>
                    <td style="text-align: right;"><span class="price_room"><?php $total_price = ([[=reservation_room_type.rate=]]*[[=reservation_room_type.num_date=]]); $total_vnd += $total_price; echo System::display_number($total_price); ?></span></td>
                    
                </tr>
                <!--/LIST:reservation_room_type-->
                <tr>
                    <td colspan="2"></td>
                    <?php $n=sizeof([[=room_type_name=]]) +1; ?>
                    <td colspan="<?php echo $n; ?>">PICK UP: 
                    <input name="pick_up" type="text" id="pick_up" style="border: none; border-bottom: 1px dashed #999; width: 100%;" onkeyup="change_lbl(this);" />
                    <label id="pick_up_lbl" style="display: none; width: 100%;"></label>                                        
                    </td>
                    <td colspan="2" style="text-align: right;">Arrival time: 
                    <input name="flight_arrival_time" type="text" id="flight_arrival_time" onkeyup="change_lbl(this);" style="border: none; border-bottom: 1px dashed #999; width: 50px;" />
                    <label id="flight_arrival_time_lbl" style="display: none; width: 50px"></label>                
                    </td>
                    <td>Cost:</td>
                    <td style="text-align: right;">
                    <input name="flight_arr_price" type="text" id="flight_arr_price" onkeyup="change_lbl(this);" onchange="check_price();" style="border: none; text-align: right; border-bottom: 1px dashed #999;" />
                    <label id="flight_arr_price_lbl" style="display: none;"></label>                            
                    </td>
                    <td></td>
                    <td style="text-align: right;">
                    <input name="flight_arr_price_1" type="text" id="flight_arr_price_1" onkeyup="change_lbl(this);" readonly="" style="border: none; text-align: right; border-bottom: 1px dashed #999;" />
                    <label id="flight_arr_price_1_lbl" style="display: none;"></label>                            
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="<?php echo $n; ?>">SEE OFF: 
                    <input name="see_off" type="text" id="see_off" onkeyup="change_lbl(this);" style="border: none; border-bottom: 1px dashed #999; width: 100%;" />
                    <label id="see_off_lbl" style="display: none; width: 100%"></label>                                        
                    </td>
                    <td colspan="2" style="text-align: right;">Departure time: 
                    <input name="flight_departure_time" type="text" id="flight_departure_time" onkeyup="change_lbl(this);" style="border: none; border-bottom: 1px dashed #999; width: 50px;" />
                    <label id="flight_departure_time_lbl" style="display: none;"></label>                            
                    </td>
                    <td>Cost:</td>
                    <td style="text-align: right;">
                    <input name="flight_dep_price" type="text" id="flight_dep_price" onkeyup="change_lbl(this);" onchange="check_price();" style="border: none; text-align: right; border-bottom: 1px dashed #999;" />
                    <label id="flight_dep_price_lbl" style="display: none;"></label>                                
                    </td>
                    <td></td>
                    <td style="text-align: right;">
                    <input name="flight_dep_price_1" type="text" id="flight_dep_price_1" onkeyup="change_lbl(this);" readonly="" style="border: none; text-align: right; border-bottom: 1px dashed #999;" />
                    <label id="flight_dep_price_1_lbl" style="display: none;"></label>                            
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="<?php echo $n+5; ?>" style="text-align: right;">GRAND TOTAL:</td>
                    <td style="text-align: right;">
                    <span class="price_room">
                    <input name="total_vnd" type="text" id="total_vnd" onkeyup="change_lbl(this);" readonly="" style="border: none; text-align: right;" />
                    <label id="total_vnd_lbl" style="display: none;"></label>
                    </span>
                    </td>
                </tr>
            </table>
        </div>
        <div style="text-align: center;">
            <h3 style="text-transform: uppercase; text-decoration: underline; line-height: 20px;">payment</h3>
            <table style="width: 100%;"  border="1" cellspacing="0">
                <tr>
                    <th style="text-transform: uppercase;">own account</th>
                    <th style="text-transform: uppercase;">company account</th>
                    <th style="text-transform: uppercase;">deposit</th>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td>CASH</td>
                                <td><input type="checkbox" name="bcf_payment_1" id="bcf_payment_1" value="1" /></td>
                            </tr>
                            <tr>
                                <td>CREDIT CARD</td>
                                <td><input type="checkbox" name="bcf_payment_2" id="bcf_payment_2" value="2" /></td>
                            </tr>
                            <tr>
                                <td>OTHER</td>
                                <td><input type="checkbox" name="bcf_payment_3" id="bcf_payment_3" value="3" /></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td>ALL EXPENNES</td>
                                <td><input type="checkbox" name="bcf_payment_4" id="bcf_payment_4" value="4" /></td>
                            </tr>
                            <tr>
                                <td>ROOM ONLY</td>
                                <td><input type="checkbox" name="bcf_payment_5" id="bcf_payment_5" value="5" /></td>
                            </tr>
                            <tr>
                                <td>PAYMENT</td>
                                <td><input type="checkbox" name="bcf_payment_6" id="bcf_payment_6" value="6" /></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                    
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 100%;">
            <b>Special Requests:</b><br />
            <textarea name="note" id="note" style="width:100%; height:50px; border: none; display: none;" onkeyup="change_lbl(this);"><?php echo $this->map['customer']['note']; ?></textarea>
           
            <textarea name="note_cf" id="note_cf" style="width:100%; height:50px; border: none;" onkeyup="change_lbl(this);"></textarea>
            
            <label id="note_lbl" style="display: none;" onclick="show_input_text();"></label>
            <b>Deposit Money:</b>
            <table width="100%">
                <tr>
                    <th style="border: 1px solid; text-align: center;">Deposit by</th>
                    <th style="border: 1px solid; text-align: center;">Date</th>
                    <th style="border: 1px solid; text-align: center;">Amount</th>
                    <th style="border: 1px solid; text-align: center;">Payment type</th>
                    <th style="border: 1px solid; text-align: center;">Description</th>
                </tr>
                <!--LIST:deposit-->
                <tr>
                    <td style="border: 1px solid;">[[|deposit.name|]]</td>
                    <td style="border: 1px solid;">[[|deposit.time|]]</td>
                    <td style="border: 1px solid;" text-align: right;>[[|deposit.amount|]]</td>
                    <td style="border: 1px solid;">[[|deposit.payment_type|]]</td>
                    <td style="border: 1px solid;">[[|deposit.description|]]</td>
                </tr>
                <!--/LIST:deposit-->
            </table>
        </div>
        <table style="width: 100%; margin: 5px auto;"  border="1" cellspacing="0">
            <tr>
                <td colspan="2" style="text-align: center;">VAT INFORMATION</td>
            </tr>
            <tr>
                <td>COMPANY: <input name="vat_company" type="text" id="vat_company" onkeyup="change_lbl(this);" style="border: none; border-bottom: 1px dashed #999; width: 100%;" />
                    <label id="vat_company_lbl" style="display: none; width: 100%;" onclick="show_input_text();"></label>
                </td>
                <td style="width: 300px;">TAX CODE: <input name="vat_tax_code" type="text" id="vat_tax_code" onkeyup="change_lbl(this);" style="border: none; border-bottom: 1px dashed #999; width: 100%;" />
                    <label id="vat_tax_code_lbl" style="display: none; width: 100%;" onclick="show_input_text();"></label>
                </td>
            </tr>
            <tr>
                <td colspan="2">ADDRESS:<input name="vat_address" type="text" id="vat_address" onkeyup="change_lbl(this);" style="border: none; border-bottom: 1px dashed #999; width: 100%;" />
                    <label id="vat_address_lbl" style="display: none; width: 100%;" onclick="show_input_text();"></label>
                </td>
            </tr>
        </table>
        <div style="width: 100%; margin: 5px auto;">
            <b>CANCELLATION/NO-SHOW/SHORTER STAY:</br>1. CANCELLATIONS/ NO SHOW POLICY</br>1.1 For Individual: (From five (5) rooms and under)</br>Low season: </b>
            <ul style="width: 90%; margin: 0px auto; font-weight: normal;">
                <li>04 days prior to arrival date: no charge;</li>
                <li>03 – 02 days prior to arrival date: Pay 30% of room charge for each room booked;</li>
                <li>01 day prior to arrival date or No-show: Pay 50% of room charge for each room booked;</li>
            </ul>
            <b>High season:</b>
            <ul style="width: 90%; margin: 0px auto; font-weight: normal;">
                <li>11 days prior to arrival date: no charge;</li>
                <li>10 – 06 days prior to arrival date: Pay 30% of room charge for each room booked;</li>
                <li>05 days prior to arrival date or No-show: Pay 50% of room charge for each room booked;</li>
            </ul>
            <b>1.2 For Group: (More than (5) rooms)</br>Low season: </b>
            <ul style="width: 90%; margin: 0px auto; font-weight: normal;">
                <li>08 days prior to arrival date: no charge;</li>
                <li>07 – 04 days prior to arrival: Pay 50% of room charge for each room booked;</li>
                <li>03 days prior to arrival or No show: Pay 80% of room charge for each room booked;</li>
            </ul>
            <b>High season:</b>
            <ul style="width: 90%; margin: 0px auto; font-weight: normal;">
                <li>21 days prior to arrival date: no charge;</li>
                <li>20 – 11 days prior to arrival: Pay 50% of room charge for each room booked;</li>
                <li>10 days prior to arrival or No show: Pay 80% of room charge for each room booked;</li>
            </ul>
            <b>Important Note:</br>- Check-In Time is 14:00 and Check-Out Time is 12:00;</br>
- Non-guaranteed reservation will be hold as hotel’s policy;</br>
- Maximum 04 guests (no more than 03 adults) for 01 room, surcharge from the 3rd person as hotel’s policy;</br>
- Early check-in or late check-out is subject to room availability and surcharge as below:</b>
            <ul style="width: 90%; margin: 0px auto; font-weight: normal;">
                <li>Late check-out from 12:00 noon to 15:00 - will charge 20% for one night room rate;</li>
                <li>Late check-out from 15:00 to 18:00 - will charge 40% for one night room rate;</li>
                <li>Early check-in from 6:00 to 14:00 - will charge 50% for one night room rate;</li>
                <li>Early check in before 6:00 or Late check out after 18:00 - will charge 100% for one night room rate;</li>
            </ul>
            <p style="font-weight: normal;">Kindly confirm your acceptance by counter – signing and scanning then sending back  my e-mail, including any requires or advices;</p>
            <b>CHILDREN POLICY</b>
            <p style="width: 90%; margin: 0px auto; font-weight: normal;">a. Children under 06, sharing bed with parents will be complimentary breakfast, maximum two (02) children per room.</br>
b. Children from 06 years old to 12 years old  sharing the room with parents and not using extra bed: breakfast will be charge 125,000 per night, including breakfast</br>
c. Children from 13 years old and above will be considered as an adult. Extra adult surcharge or Extra bed fee is   500,000vnd/ night, including breakfast.</p>
            <p style="font-weight: normal;">For any further information or assistance, please do not hesitate to contact us.</br>
We look forward to receiving your reconfirmation and welcoming you to the Vinh Hoang Hotel soon.</p>
        </div>
        <table style="width: 90%; margin: 0px auto;">
            <tr>
                <td><b>Thank you and best regards.</b></td>
                <td style="text-align: center;"><b>Please sign and fax back to us within today for the acceptance</br>Customer  Signature</b></td>
            </tr>
            <tr>
                <td colspan="2" style="height: 50px;"></td>
            </tr>
            <tr>
                <td>Reservation Dept</br>Name:<input name="user" type="text" id="user" style="border: none;" /></td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td>Phone:<input name="phone" type="text" id="phone" style="border: none;" /></br>Position:<input name="position" type="text" id="position" style="border: none;" /></td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td colspan="2" style=" text-align: center;">
                <b>
                <?php echo HOTEL_ADDRESS; ?> - Tel: <?php echo HOTEL_PHONE; ?> Fax: <?php echo HOTEL_FAX; ?><br />
                E-mail: <?php echo HOTEL_EMAIL; ?><br />
                Website: <?php echo HOTEL_WEBSITE; ?>
                </b>
                </td>
            </tr>
        </table>
    </div><!-- end print -->
    <input name="room_type_unasign" type="text" id="room_type_unasign"  style="display: none;"/>
    <div id="mail" style="width: 200px; height: 30px; background: #fff; position: fixed; bottom: 30px; right: 0px; transition: all .5s ease-out; border: 1px solid #555; overflow: hidden;margin-bottom: 15px;">
        <div onclick="hide_mail();" style="width: 100%; height: 30px; background: #333333; cursor: pointer;">
            <p style="float: left; color: #fff;">Email</p>
            <input type="checkbox" name="check_hide" id="check_hide" style="display: none;" />
            
        </div>
        <div style="width: 100%; height: 250px; background: #fff;">
            <table style="margin-left: -32px;">
                <tr>
                    <td><input name="to_email_address" type="text" style="width: 150px; height:20px;" placeholder="To" /></td>
                </tr>
                <tr>
                     <td><input name="title_mail" type="text" style="width: 300px;height:20px;" placeholder="Subject"/></td>
                </tr>
                <tr>
                    <td>[[.content.]]:</td>
                </tr>
                <tr>
                    <td>
                        <textarea name="content_mail" id="content_mail" style="width: 300px; height: 150px;"></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <br />
        <input name="send_mail" onclick="show_content();" type="button" id="send_mail" value="Send" style="background: #4d90fe; color:#fff; border:none; width: 60px; height: 25px;"  />
        <br />
    </div>
        <input id="content_1" name="content_1" type="text" style="display: none;" />
</form>
<script>
    function show_content()
    {
        
        var inputs = jQuery('table input:radio:checked,table input:checkbox:checked');
        
        for (var i=0;i<inputs.length;i++)
        { 
            var typ=document.createAttribute("checked");
            typ.nodeValue="true";
            inputs[i].attributes.setNamedItem(typ);
        }
        var inputs = jQuery('table input:text');
        inputs.css('border','none');
        for (var i=0;i<inputs.length;i++)
        { 
            var typ=document.createAttribute("value");
            if(inputs[i].attributes.id)
            {
                typ.value=jQuery('#'+inputs[i].attributes.id.value).val();
                inputs[i].attributes.setNamedItem(typ);
            }
        }
        print(false);
        var content = document.getElementById('print').innerHTML;
        jQuery("#content_1").val(content);
        BookingConfirmForm.submit();
    }
    function test_checkbox(obj){
        var text_room_type = '';
        var id = obj.id.split("_");
        console.log(id);
        var input_type = jQuery("#room_type_unasign").val();
        if(input_type == ''){
            jQuery("#room_type_unasign").val(","+obj.id);
        }else{
            input_type_arr = jQuery("#room_type_unasign").val().split(",");
            for(n in input_type_arr){
                if(n>0){
                    id_test = input_type_arr[n].split("_");
                    if(id_test[1]!=id[1]){
                        text_room_type = text_room_type+","+input_type_arr[n];
                    }
                }
            }
            text_room_type = text_room_type+","+obj.id;
            jQuery("#room_type_unasign").val(text_room_type);
        }
         jQuery("#img_checkbox_"+id[1]+"_"+id[2]).css('display','block');
         jQuery("#checkbox_"+id[1]+"_"+id[2]).css('display','none');
        var reservation_room_type = [[|reservation_room_type_js|]];
        var room_type_name = [[|room_type_name_js|]];
        //console.log(reservation_room_type);
        console.log(room_type_name);
        var k = '';
        for(i in reservation_room_type){
            if(i==id[1]){
                for(j in room_type_name){
                    if(room_type_name[j]['brief_name']!=id[2]){
                        k=room_type_name[j]['brief_name'];
                        document.getElementById("checkbox_"+i+"_"+k).checked=false;
                        jQuery("#checkbox_"+i+"_"+k).css('display','block');
                        jQuery("#img_checkbox_"+i+"_"+k).css('display','none');
                    } 
                }//end for room_type_name
            }
        }//end for reservation_room_type
        
    }
    function show_hide(obj){
    if(jQuery("#hide_price").is(':checked'))
        {
            jQuery(".price_room").css('display','none');
        }
        else
        {
            jQuery(".price_room").css('display','');
        }
    }
</script>
<script>
    jQuery(document).ready(function()
    {
        
        jQuery('#guests_name').focus();
        jQuery('#attention_lbl').html(jQuery('#attention').val());
        jQuery('#guests_name_lbl').html(jQuery('#guests_name').val());
        
        jQuery('#pick_up_lbl').html(jQuery('#pick_up').val());
        jQuery('#flight_arrival_time_lbl').html(jQuery('#flight_arrival_time').val());
        jQuery('#flight_arr_price_lbl').html(jQuery('#flight_arr_price').val());
        jQuery('#flight_arr_price_1').val(jQuery('#flight_arr_price').val());
        jQuery('#flight_arr_price_1_lbl').html(jQuery('#flight_arr_price').val());
        
        jQuery('#see_off_lbl').html(jQuery('#see_off').val());
        jQuery('#flight_departure_time_lbl').html(jQuery('#flight_departure_time').val());
        jQuery('#flight_dep_price_lbl').html(jQuery('#flight_dep_price').val());
        jQuery('#flight_dep_price_1').val(jQuery('#flight_dep_price').val());
        jQuery('#flight_dep_price_1_lbl').html(jQuery('#flight_dep_price').val());
        
        jQuery('#total_vnd_lbl').html(jQuery('#total_vnd').val());
        jQuery('#note_lbl').html(jQuery('#note_cf').val());
        
        jQuery('#vat_company_lbl').html(jQuery('#vat_company').val());
        jQuery('#vat_tax_code_lbl').html(jQuery('#vat_tax_code').val());
        jQuery('#vat_address_lbl').html(jQuery('#vat_address').val());
        
        
        //-----------
        var bcf_status = '<?php echo $this->map['customer']['bcf_status']; ?>';
        var bcf_payment = '<?php echo $this->map['customer']['bcf_payment']; ?>';
        var strValn = bcf_payment.replace(/,/g,"");
        
        if(bcf_status != '' && bcf_status != '0')
            document.getElementById("bcf_status_"+bcf_status).checked=true;
        
        var i;  
        for(i = 0; i < strValn.length; i++)
        {
            document.getElementById("bcf_payment_"+strValn[i]).checked=true;
        }
        //jQuery("#cut_of_date").datepicker();
        });
</script>
<script>
    /*
    function check_price(){
        var total = Number(<?php echo $total_vnd; ?>);
        var total_pick_up = Number(jQuery('#flight_arr_price').val());
        var total_see_off = Number(jQuery('#flight_dep_price').val());
        total = total + total_pick_up + total_see_off;
        total = number_format(total);
        jQuery('#total_vnd').val(total);
        jQuery('#total_vnd_lbl').html(total);
        jQuery('#flight_arr_price_1').val(number_format(total_pick_up));
        jQuery('#flight_arr_price_1_lbl').html(number_format(total_pick_up));
        jQuery('#flight_dep_price_1').val(number_format(total_see_off));
        jQuery('#flight_dep_price_1_lbl').html(number_format(total_see_off));
    }
    */
    function change_lbl(ojb){
        
        jQuery('#attention_lbl').html(jQuery('#attention').val());
        jQuery('#guests_name_lbl').html(jQuery('#guests_name').val());
        
        jQuery('#pick_up_lbl').html(jQuery('#pick_up').val());
        jQuery('#flight_arrival_time_lbl').html(jQuery('#flight_arrival_time').val());
        jQuery('#flight_arr_price_lbl').html(jQuery('#flight_arr_price').val());
        jQuery('#flight_arr_price_1').val(jQuery('#flight_arr_price').val());
        jQuery('#flight_arr_price_1_lbl').html(jQuery('#flight_arr_price').val());
        
        jQuery('#see_off_lbl').html(jQuery('#see_off').val());
        jQuery('#flight_departure_time_lbl').html(jQuery('#flight_departure_time').val());
        jQuery('#flight_dep_price_lbl').html(jQuery('#flight_dep_price').val());
        jQuery('#flight_dep_price_1').val(jQuery('#flight_dep_price').val());
        jQuery('#flight_dep_price_1_lbl').html(jQuery('#flight_dep_price').val());
        
        var total = Number(<?php echo $total_vnd; ?>);
        var total_pick_up = Number(jQuery('#flight_arr_price').val());
        var total_see_off = Number(jQuery('#flight_dep_price').val());
        total = total + total_pick_up + total_see_off;
        total = number_format(total);
        jQuery('#total_vnd').val(total);
        jQuery('#total_vnd_lbl').html(total);
        jQuery('#note_lbl').html(jQuery('#note_cf').val());
        
        jQuery('#vat_company_lbl').html(jQuery('#vat_company').val());
        jQuery('#vat_tax_code_lbl').html(jQuery('#vat_tax_code').val());
        jQuery('#vat_address_lbl').html(jQuery('#vat_address').val());
        
    }
    function print(key)
    {
        var inputs = jQuery('table input:radio:checked,table input:checkbox:checked');
        for (var i=0;i<inputs.length;i++)
        { 
            var typ=document.createAttribute("checked");
            typ.nodeValue="true";
            inputs[i].attributes.setNamedItem(typ);
        }
            jQuery('#attention_lbl').css('display','block');
            jQuery('#guests_name_lbl').css('display','block');
            
            jQuery('#pick_up_lbl').css('display','block');
            jQuery('#flight_arrival_time_lbl').css('display','block');
            jQuery('#flight_arr_price_lbl').css('display','block');
            jQuery('#flight_arr_price_1_lbl').css('display','block');
            
            jQuery('#see_off_lbl').css('display','block');
            jQuery('#flight_departure_time_lbl').css('display','block');
            jQuery('#flight_dep_price_lbl').css('display','block');
            jQuery('#flight_dep_price_1_lbl').css('display','block');
            
            jQuery('#total_vnd_lbl').css('display','block');
            jQuery('#note_lbl').css('display','block');
            
            jQuery('#vat_company_lbl').css('display','block');
            jQuery('#vat_tax_code_lbl').css('display','block');
            jQuery('#vat_address_lbl').css('display','block');
            //--------------
            jQuery('#attention').css('display','none');
            jQuery('#guests_name').css('display','none');
            
            jQuery('#pick_up').css('display','none');
            jQuery('#flight_arrival_time').css('display','none');
            jQuery('#flight_arr_price').css('display','none');
            jQuery('#flight_arr_price_1').css('display','none');
            
            jQuery('#see_off').css('display','none');
            jQuery('#flight_departure_time').css('display','none');
            jQuery('#flight_dep_price').css('display','none');
            jQuery('#flight_dep_price_1').css('display','none');
            
            jQuery('#total_vnd').css('display','none');
            jQuery('#note_cf').css('display','none');
            
            jQuery('#vat_company').css('display','none');
            jQuery('#vat_tax_code').css('display','none');
            jQuery('#vat_address').css('display','none');
            
            jQuery('.checkbox_room').css('display','none');
        if(key)
        {
            var user ='<?php echo User::id(); ?>';
            BookingConfirmForm.submit();
            jQuery("#select_show_hide").css('display','none');
            printWebPart('print',user);
            location.load();
        }
    }
function hide_mail()
{
    if(document.getElementById("check_hide").checked==false)
    {
        document.getElementById("check_hide").checked=true;
        jQuery("#mail").css('width','400px');
        jQuery("#mail").css('height','325px');
    }
    else
    {
        document.getElementById("check_hide").checked=false;
        jQuery("#mail").css('width','200px');
        jQuery("#mail").css('height','30px');
    }
}
</script>
<script>
jQuery('#flight_arrival_time').mask("99:99");
jQuery('#flight_departure_time').mask("99:99");
</script>