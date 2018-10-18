<style>
    * {
        margin: 0px;
        padding: 0px;
        line-height: 18px;
        color: #171717;
    }
    .simple-layout-middle{width:100%;}
    p{
        font-weight: normal!important;
        font-size: 13px!important;
    }
    ul li {
        font-weight: normal!important;
        font-size: 13px!important;
    }
    label {
        font-weight: normal!important;
        font-size: 13px!important;
    }
    table {
        border-collapse: collapse;
    }
    h1 {
        font-size: 21px;
    }
    h3 {
        font-size: 17px;
    }
    h5 {
        font-size: 13px;
    }
    #chang_language{
        display: none;
    }
    .lbl_text {
        display: none;
    }
    @media print {
        .input_text {
            display: none!important;
        }
        .lbl_text {
            display: block!important;
        }
        #boxsave {
            display: none;
        }
        .hide {
            display: none;
        }
    }
</style>
<form name="BookingConfirm2Form" method="POST">
    <div id="boxsave" style="width: 50px; height: 50px; position: fixed; top: 110px; right: 10px;">
        <img src="packages/core/skins/default/images/printer.png" onclick="var user ='<?php User::id(); ?>';printWebPart('printer',user);" style="width: 50px; height: auto; float: right;" />
    </div>
    <div style="width: 750px; height: auto; margin: 0px auto;">
        <table style="width: 100%;" cellspacing="0" cellpadding="5">
            <tr>
                <th rowspan="4"><img src="<?php echo HOTEL_LOGO; ?>" style="height: 100px; width: auto; margin: 0px; padding: 0xp;" /></th>
                <th style="text-align: center;"><h1 style="color: #ad291e;"><?php echo HOTEL_NAME; ?></h1></th>
            </tr>
            <tr style="text-align: center;">
                <th><h5 style="color: #ad291e;"><?php echo HOTEL_ADDRESS; ?></h5></th>
            </tr>
            <tr style="text-align: center;">
                <th><h5 style="color: #ad291e;">Tel: <?php echo HOTEL_PHONE; ?> Fax: <?php echo HOTEL_FAX; ?></h5></th>
            </tr>
            <tr style="text-align: center;">
                <th><h5 style="color: #ad291e;">Web: <?php echo HOTEL_WEBSITE; ?> - E-mail: <?php echo HOTEL_EMAIL; ?></h5></th>
            </tr>
            <tr style="text-align: center;">
                <th colspan="2"><h1 style="font-size: 25px;">BOOKING CONFIRM</h1></th>
            </tr>
            <tr style="text-align: center;">
                <th colspan="2"><h3 style="color: #ad291e;">Code: [[|recode|]]</h3></th>
            </tr>
            <tr style="text-align: center;" class="hide">
                <th colspan="2"><input type="checkbox" style="width: 30px;" onclick="if(document.getElementById(this.id).checked==true){ jQuery('.hide_price').css('display','none'); }else{ jQuery('.hide_price').css('display',''); }" /><label>Hide Price</label></th>
            </tr>
        </table>
        <table style="width: 100%;" cellspacing="0" cellpadding="0" border="1" bordercolor="#cccccc">
            <tr>
                <th style="width: 50%; vertical-align: top;">
                    <label style="margin-left: 5px;">
                        <b>To: </b>[[|customer_name|]] <br />
                    </label>
                    <label style="margin-left: 5px;">
                        <b>Office: </b>[[|customer_address|]] <br />
                    </label>
                    <label style="margin-left: 5px;">
                        <b>Attention to: </b>[[|booker|]] <br />
                    </label>
                    <label style="margin-left: 5px;">
                        <b>Tel: </b>[[|phone_booker|]] <br />
                    </label>
                    <label style="margin-left: 5px;">
                        <b>Email: </b>[[|email_booker|]]
                    </label>
                </th>
                <th style=" vertical-align: top;">
                    <label style="margin-left: 5px;">
                        <b>From: </b><?php echo HOTEL_NAME; ?> 
                    </label><br />
                    <label style="margin-left: 5px;">
                        <b>Reservation Clerk: </b>[[|user_name|]] 
                    </label><br />
                    <label style="margin-left: 5px; clear: both;">
                        <b>Tel: </b>
                        <input name="tel_booking_cf" type="text" id="tel_booking_cf" placeholder="input phone" class="input_text" style="border: none; border-bottom: 1px dashed #CCCCCC;" /> 
                        <label id="lbl_tel_booking_cf" class="lbl_text"></label>
                    </label><br />
                    <label style="margin-left: 5px; clear: both;">
                        <b>Date: </b><?php echo date('d/m/Y'); ?>
                    </label>
                </th>
            </tr>
        </table>
        <table style="width: 100%; margin: 5px auto;" cellspacing="0" cellpadding="0">
            <tr style="text-align: center;">
                <td style="width: 33%;"><input type="checkbox" /><label>New Booking</label></td>
                <td><input type="checkbox" /><label>Amendment</label></td>
                <td style="width: 33%;"><input type="checkbox" /><label>Cancellation</label></td>
            </tr>
        </table>
        
        <p><b>Warmest Greeting from <?php echo HOTEL_NAME; ?> !</b></p>
        <p>Dear Mr/ Ms, [[|booker|]]</p>
        <p><label style="margin-left: 20px;">Thanh you very much for your support and interest in <?php echo HOTEL_NAME; ?> . As your request, we are delighted to <b>confirm</b> for you reservation as follows:</label></p>
        
        <p><b>I. Accommondation</b></p>
        <table style="width: 100%; margin: 5px auto;" border="1" bordercolor="#cccccc">
            <tr style="text-align: center; background: #ff9289;">
                <th rowspan="2">Category</th>
                <th rowspan="2">Quantity</th>
                <th colspan="2">Period</th>
                <th rowspan="2"style="width: 50px;">Pac (A/C)</th>
                <th rowspan="2">No.Night</th>
                <th rowspan="2">Rate</th>
                <th rowspan="2">Total (VND)</th>
            </tr>
            <tr style="text-align: center; background: #ff9289;">
                <th>Arrival</th>
                <th>Departure</th>
            </tr>
            <!--LIST:items-->
            <tr style="text-align: center; height: 25px;">
                <th>[[|items.name|]]</th>
                <th>[[|items.quantity|]]</th>
                <?php if([[=items.type=]]=='anyday'){ ?>
                <th>[[|items.start_date|]]</th>
                <th>[[|items.end_date|]]</th>
                <?php }else{ ?>
                <th colspan="2">[[|items.start_date|]]</th>
                <?php } ?>
                <?php if([[=items.level=]]=='ROOM'){ ?>
                <th>[[|items.adult|]]/[[|items.child|]]</th>
                <?php }else{ ?>
                <th></th>
                <?php } ?>
                <th>[[|items.nite|]]</th>
                <th style="text-align: right;"><?php echo System::display_number([[=items.price=]]); ?></th>
                <th style="text-align: right;"><?php echo System::display_number([[=items.total=]]); ?></th>
            </tr>
            <!--/LIST:items-->
            <tr>
                <th style="text-align: right;" colspan="7">TOTAL (VND)<br />TOTAL ROOM CHARGE (VND)</th>
                <th style="text-align: right;"><?php echo System::display_number([[=total=]]); ?></th>
            </tr>
            <tr>
                <th style="text-align: right;" colspan="7">DEPOSITED(VND)</th>
                <th style="text-align: right;"><?php echo [[=deposit=]]!=0?System::display_number([[=deposit=]]):''; ?></th>
            </tr>
        </table>
        
        <p><i>The above rates are inclusive of Daily Breakfast for 01 person or 02 person per room</i></p>
        <p><i>Checkin is at 14:00 hrs and Checkout is at 12:00 hrs</i></p>
        <p><i>The rate above is inclusive of 5% Service Charge and 10% government tax</i></p>
        
        <table style="width: 100%;" cellspacing="0" cellpadding="0" border="1" bordercolor="#cccccc">
            <tr>
                <th style="vertical-align: top; width: 130px;">Special Request:</th>
                <th style="vertical-align: top;">
                    <textarea name="special_request" id="special_request" class="input_text" style="width: 100%; height: 70px; border: 1px dashed #CCCCCC;"></textarea>
                    <label id="lbl_special_request" class="lbl_text"></label>
                    <br />
                    <br />
                    <br />
                    <br />
                </th>
            </tr>
        </table>
        
        <p><b>II. Payment method</b></p>
        <p>We require the 50% payment for reserved services in cash or by bank transfer to the following bank account before [[|cut_of_date|]]</p>
        <p><i><u>The following is our bank account:</u></i></p>
        <p style="margin-left: 100px;"><b>Beneficiary: Công ty TNHH xuất nhập khẩu Vĩnh Hoàng</b></p>
        <p style="margin-left: 100px;"><b>Bank Account No: 53110000125709 (VND)</b></p>
        <p style="margin-left: 100px;"><b>Bank Name: Ngân hàng Đầu tư & Phát triển Việt Nam, Chi nhánh Quảng Bình.</b></p>
        
        <p><b>III. Cancellation  policy</b></p>
        <p>- Should the <?php echo HOTEL_NAME; ?>  receive the cancellation of the entire group within 12 days of the group arrival, or change the arrival day a cancallation fee equal to <b>50%</b> the total revenue room will be charged</p>
        <p>- Should the <?php echo HOTEL_NAME; ?>  receive the cancellation of the entire group within 07 days of the group arrival, or change the arrival day a cancallation fee equal to <b>100%</b> the total revenue room will be charged</p>
        
        <br />
        
        <p><b>We are looking forward to receiving your reconfirmation and welcoming you soon.</b></p>
        <p><b>For any further information, please feel free to contact us at any time.</b></p>
        
        <table style="width: 100%;" cellspacing="0" cellpadding="0">
            <tr style="text-align: center; height: 50px;">
                <th style="width: 50%;">With our best regards</th>
                <th>Please sign in and send this fax back <br/> to us for final acceptance</th>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <b>Date: </b> <?php echo date('d/m/Y'); ?><br />
                    Reserved By<br />
                    <b>[[|creater_name|]]</b><br />
                    <label><b style="float: left;">Reservation - Department</b> <input name="reservation_department" type="text" id="reservation_department" class="input_text" style="border: none; border-bottom: 1px dashed #CCCCCC; width: 50%; float: left;" />
                    <label id="lbl_reservation_department" class="lbl_text" style="float: left;"></label></label>
                </td>
                <td style="vertical-align: top; text-align: center;">Accepted & Acknowledged by</td>
            </tr>
        </table>
    </div>
</form>
<script>
    jQuery(document).ready(function(){
        jQuery("input").keyup(function(){ FunParseLayout(); });
        jQuery("textarea").keyup(function(){ FunParseLayout(); });
        FunParseLayout();
    });
    function FunParseLayout()
    {
        jQuery(".input_text").each(function(){
            $id = this.id;
            jQuery('#lbl_'+$id).html(jQuery('#'+$id).val().replace(/\r?\n/g, '<br />'));
        });
        var inputs = jQuery('table input:checkbox:checked');
        for(var i=0; i<inputs.length; i++)
        { 
            var typ=document.createAttribute("checked");
            typ.nodeValue="true";
            inputs[i].attributes.setNamedItem(typ);
        }
    }
</script>
