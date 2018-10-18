<style>
    table tr td{
        font-family: Times New Roman;
        font-size: 16px;
    
    }
    .guest-registration-card li{
        list-style:inside;
        margin-bottom:5px;
    }
    #guest_name_lbl{
        display: none;
    }
    p{
        margin:0px;
        padding:0px;
    }
    select{border: none;}
    input[type=checkbox]{
        width: 18px;
        height: 18px;
    }
    #chang_language {
        display: none;
    }
    @media print{
        #print_form_page_img {
            display: none;
        }
        #guest_name_lbl{
            display: block;
        }
        #show_price{
            display: none;
        }
    }
    
</style>
<form name="GuestRegistrationCardForm" method="post">
<div id="print" style="width: 960px; height: auto; margin: 0px auto; padding: 0px;">
    <table  cellSpacing="0" cellpadding="0" style="width: 100%; margin: 0px; padding: 0px; ">
        <tr style="">
            <td style="width: 150px;"><img src="<?php echo HOTEL_LOGO; ?>" style="height: 80px;" /></td>
            <td style="text-align: center;"><h2>REGISTRATION FORM</h2><i>PHIẾU ĐĂNG KÝ NHẬN PHÒNG</i>
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
    </table>
    <br />
    <span style="float: right;">Recode/Mã đặt: [[|reservation_id|]]</span>
    <table class="table_border" cellSpacing="0" cellpadding="5" border="1" style="width: 100%; margin: 0px; padding: 0px;">
        <tr style="line-height: 15px;">
            <td width="33%" colspan="2"> 
                <table >
                    <tr>
                        <td style="text-align: left; padding-left: 5px; width:120px;">GUEST'S NAME:</td>
                        <td>
                                <label id="guest_name_lbl">[[|full_name|]]</label>
                                <select name="guest_name" id="guest_name" onchange="check_traveller(this);"></select>
                        </td>
                    </tr>
                </table>
                <i>(Tên khách)</i><br /><br />
                BIRTHDAY: [[|birth_date|]]<br />
                <i>(Ngày sinh)</i>
            </td>
            <td colspan="2"></td>
            <td width="33%">
                PASSPORT#/ID: [[|passport|]]<br />
                <i>(Số passport)</i><br /><br />
                NATIONALITY:[[|nationality|]]<br />
                <i>(Quốc tịch)</i>
            </td>
        </tr>
        <tr style="line-height: 15px;">
            <td valign="top"  style="border-right: none;">ROOM TYPE<br /><i>(Loại phòng)</i><br />[[|room_level|]]-[[|room_type|]]</td>
            <td valign="top"  align="center" style="border-left: none;">ROOM NO.<br /><i>(Số phòng)</i><br />[[|room_name|]]</td>
            <td colspan="3">
            PHONE NUMBER: [[|phone|]]<br /><i>(Số điện thoại)</i><br /><br />
            E-MAIL ADDRESS: [[|email|]]<br /><i>(Địa chỉ E-mail)</i>
            </td>
        </tr>
        <tr>
            <td valign="top" style="border-right: none;">ARRIVAL DATE<br /><i>(Ngày đến)</i><br />[[|arrival_time|]]</td>
            <td valign="top"  align="center" style="border-left: none;">TIME<br /><i>(Giờ đến)</i><br /><?php if(!empty([[=time_in=]])){ echo date('H:i',[[=time_in=]]); } else { echo date('H:i',[[=reservation_time_in=]]); }?></td>
            <td valign="top" style="border-right: none; width: 150px;">DEPARTURE DATE<br /><i>(Ngày đi)</i><br />[[|departure_time|]]</td>
            <td valign="top" style="border-left: none;">TIME<br /><i>(Giờ đi)</i><br /><?php if(!empty([[=time_out=]])){ echo date('H:i',[[=time_out=]]); } else { echo date('H:i',[[=reservation_time_out=]]); } ?></td>
            <td  valign="top" style="border-left: none;">ROOM RATE<br /><i>(Giá phòng)</i><br /><span id="room_rate" style="font-size: 16px;"><?php echo System::display_number([[=room_rate=]]); ?></span></td>
        </tr>
        <tr><td colspan="5" valign="top" style="border-left: none;">COMPANY/ TRAVEL AGENTP: [[|customer_name|]]<br /><i>(Công ty/Hãng lữ hành)</i></td></tr>
        <tr>
            <td colspan="2">SPECIAL REQUESTS<br /><i>(Yêu cầu đặc biệt)</i></td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="2">PICK UP<i>/Đón sân bay</i></td>
            <td colspan="2">
                Date/<i>Ngày:</i><br />Time/<i>Giờ:</i>
            </td>
            <td>Flight No<i>/Chuyến bay: [[|flight_code|]]</i><br />ETA<i>/Giờ đến: <?php if([[=flight_arrival_time=]]){echo date('H:i',[[=flight_arrival_time=]]); } ?></i></td>
        </tr>
        <tr>
            <td colspan="2">DROP OFF<i>/Tiễn sân bay</i></td>
            <td colspan="2">
                Date/<i>Ngày:</i><br />Time/<i>Giờ:</i>
            </td>
            <td>Flight No<i>/Chuyến bay: [[|flight_code_departure|]]</i><br />ETD<i>/Giờ đi: <?php if([[=flight_departure_time=]]){echo date('H:i',[[=flight_departure_time=]]); } ?></i></td>
        </tr>
        <tr>
            <td colspan="3" valign="top">
                <div style="margin-left: 100px;">METHOD OF PAYMENT<br /><i>(Hình thức thanh toán)</i><br /><b>Own Account/Tài khoản khách hàng</b><br /></div>
               <center><table>
                    <tr>
                        <td><input type="checkbox"  name="payment_method_checkbox"/></td>
                        <td>Cash<i>/Tiền mặt</i></td>
                        <td><input type="checkbox" name="payment_method_checkbox" /></td>
                        <td>Transfer<i>/Chuyển khoản</i></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="payment_method_checkbox" /></td>
                        <td>Credit Card/<i>/Thẻ tín dụng</i></td>
                        <td><input type="checkbox" name="payment_method_checkbox" /></td>
                        <td>Other<i>/Khác</i></i></td>
                    </tr>
                </table></center>
                Credit Card No<i>/Số thẻ:</i><br />
                Expired Date<i>/Hạn sử dụng:</i><br />
                ---------------------------------------------------------------------------
                <br />
                <center><b>Company /TA Account – Tài khoản công ty</b><br /></center> 
                 <table style="margin-left: 33px;">
                    <tr>
                        <td><input type="checkbox"  name="payment_method_checkbox"/></td>
                        <td>Room Only/<i>/Tiền phòng</i></td>
                        <td><input type="checkbox" name="payment_method_checkbox" /></td>
                        <td>All Expenses<i>/Tất cả</i></td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <b>Please Note/ Lưu ý:</b><br />
                <ol>
                    <li>Check out time is 12:00 noon</li>
                    <li>Only registered guests are accepted in the room</li>
                    <li>Please place your valuables in the safe deposit boxes available at the room. The hotel can not accept liability for valuables left in the room.</li>
                    <li>If there are changes in the information presented above, please notify the front desk clerk.</li>
                </ol>
                <ol>
                    <li><i>Thời gian check-out là 12 giờ trưa.</i></li>
                    <li><i>Chỉ những khách đăng kí mới được vào phòng.</i></li>
                    <li><i>Xin vui lòng để lại những vật dụng có giá trị vào két sắt an toàn ở trong phòng.Khách sạn không chịu trách nhiệm đối với những vật bị mất cắp trong phòng.</i></li>
                    <li><i>Nếu quý khách có những thay đổi về thông tin trên,xin vui lòng báo ở quầy lễ tân.</i></li>
                </ol>
            </td>
        </tr>
    </table><br />
    <table width="100%">
        <tr>
            <td>I agree that my liability for this bill is not waived, and I agree to be personally liable in the event that the indicated person, company or association fails to pay for the amount of the charges.<br />
                <i>(Tôi xin cam kết chịu trách nhiệm với những phiếu chi này,và tôi xin cam kết cá nhân sẽ chịu mọi trách nhiệm trong trường hợp cá nhân được chỉ định,công ty hay tổ chức không thanh toán các khoản phí)</i>
            </td>
        </tr>
    </table>
    <br />
    <table width="100%" border="1" cellSpacing="0">
        <tr style="height: 120px;line-height: 20px;">
            <td align="center" valign="top"><b>GUEST'S SIGNATURE</b><br /><i>CHỮ KÝ KHÁCH HÀNG</i></td>
            <td align="center" valign="top"><b>CHECKED IN BY</b><br /><i>CHỮ KÝ NHÂN VIÊN</i></td>
            <td align="center" valign="top"><b>CHECKED BY</b><br /><i>CHỮ KÝ GIÁM SÁT</i></td>
        </tr>
    </table>
</div>
</form>
<img id="print_form_page_img" src="packages/core/skins/default/images/printer.png" style="width: 30px; height: auto; position: fixed; top: 50px; right: 30px;" onclick="print_form_page();" />
<script>
    jQuery('#price').attr('checked','checked');
    var room_rate='<?php echo System::display_number([[=room_rate=]]); ?>';
    jQuery('#price').click(function(){
        if (jQuery('#price').is(':checked')){
            jQuery('#room_rate').html(room_rate);
        }else{
            jQuery('#room_rate').html('');
        }
    });
    function check_traveller(obj){
         GuestRegistrationCardForm.submit();
    }
    function show_traveller(){
        jQuery("#traveller").css('display','block');
    }
    function print_form_page()
    {
        var inputs = jQuery('table input:radio:checked,table input:checkbox:checked');
        jQuery("#guest_name").css('display','none');
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
        var user ='<?php echo User::id(); ?>';
        printWebPart('printer',user);
        
    }
    
</script>
