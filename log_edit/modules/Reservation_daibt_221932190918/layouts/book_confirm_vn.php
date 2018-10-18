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
    }
</style>
<form name="BookingConfirmForm" method="post">
    <table width="100%">
        <tr>
            <td width="85%">&nbsp;</td>
            <td align="right" style="vertical-align: bottom;" >
                <a onclick="printTagId('print');" title="In">
                    <img src="packages/core/skins/default/images/printer.png" height="40" />
                </a>
            </td>
        </tr>
    </table>
    <div id="print" style="width: 85%; margin: 0px auto;">
        <table style="width: 100%; margin: 0px auto;">
            <tr style="width: 40%;"> 
                <td rowspan="3" style="text-align: center;"><img src="<?php echo HOTEL_LOGO; ?>" style="width: auto; height: 70px;" /></td>
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
                <td style="text-align: right;font-size: 19px; width: 48%;"><b>XÁC NHẬN ĐẶT PHÒNG </b></td>
                <td style="text-align: right; width: 30%;"><b style="padding-right: 30px; font-size: 19px; color: #ff0000;">RECODE: [[|recode|]]</b></td>
            </tr>
            <tr>
                <td style="text-align: right;font-size: 19px; width: 44%;"></td>
                <td style="text-align: right; width: 30%;"><b style="padding-right: 22px; font-size: 16px; color: #000;">Tổng số phòng: [[|total_room|]]</b></td>
            </tr>
            <tr id="tr_hide_price"> 
                <td colspan="3" style="text-align: center;"><span id="select_show_hide"><input name="hide_price" type="checkbox" id="hide_price" value="1" onchange="show_hide(this);"/></span>Hide price</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto; border: 1px solid #333;">
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Kính gửi: <?php echo [[=customer_name=]]; ?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b>Từ: <?php echo HOTEL_NAME;?></b></td>
            </tr>
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Địa chỉ: <?php echo [[=customer_address=]]; ?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b>Nhân viên đặt phòng: <?php echo [[=user_name=]];?></b></td>
            </tr>
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Người đặt: <?php echo [[=booker=]];?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b style="float: left;">Tel: <input type="text" name="tel_dn" id="tel_dn" value="[[|tel_booking_cf|]]" style="border: hidden; font-weight: bold;" placeholder="Enter Telephone" onkeyup="change_value(this);"/></b><label id="tel_dn_lb" style="float: left;"></label></td>
            </tr>
            <tr style="font-size:12px;">
                <td style="width: 60%;padding-left: 10px; border-right: 1px solid #888;"> <b>Tel: <?php if([[=phone_booker=]] != ''){ echo [[=phone_booker=]];}else{ echo '.............................................';}?></b>  <b>Email: <?php if([[=email_booker=]]){echo [[=email_booker=]];}else{ echo '.............................................';}?></b></td>
                <td style="width: 40%;padding-left: 10px;"> <b>Ngày tạo: <?php echo date('d/m/Y', [[=create_booking=]]); ?></b></td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr>
                <td style="text-align: right; width: 100px;"><input type="checkbox" name="bcf_status" id="bcf_status_1" value="1" /></td>
                <td style="text-align: left; width: 100px;">Mới</td>
                <td style="text-align: right; width: 100px;"><input type="checkbox" name="bcf_status" id="bcf_status_2" value="2" /></td>
                <td style="text-align: left; width: 100px;">Sửa</td>
                <td style="text-align: right; width: 100px;"><input type="checkbox" name="bcf_status" id="bcf_status_3" value="3" /></td>
                <td style="text-align: left; width: 100px;">Hủy</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr style="font-size:12px;">
                <td><strong>Kính gửi Quý khách!</strong></td>
            </tr>
            <tr style="font-size:12px;">
                <td>Xin Cảm ơn Quý khách đã lựa chọn <?php echo HOTEL_NAME; ?> cho địa điểm lưu trú và nghỉ dưỡng tại <?php echo CITY; ?>, chúng tôi xin gửi quý khách chi tiết xác nhận đặt phòng như sau:</td>
            </tr>
        </table>
        <table style="width: 100%;"  border="1" cellspacing="0">
            <tr style="text-align: center;font-size:12px;">
                <th rowspan="2">Loại phòng</th>
                <th rowspan="2">S.Lượng</th>
                <th colspan="2">Thời gian</th>
                <th rowspan="2" style="width: 20px;">S.L người (A/C)</th>
                <th rowspan="2">Số đêm</th>
                <th rowspan="2" class="hidden_price_n2d">Giá phòng<br />VND net/ phòng/ đêm</th>
                <th rowspan="2" class="hidden_price_n2d">Tổng (VND)</th>
            </tr>
            <tr style="text-align: center;ont-size:12px;">
                <th>Đến</th>
                <th>Đi</th>
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
                <td colspan="7" style="text-align: right;padding-right: 10px;"><b>TỔNG CỘNG (VND)</b></td>
                <td colspan="1" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=total=]]);?></b></td>
            </tr>
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td style="text-align: right;padding-right: 10px;"><b>Đặt cọc nhóm(VND)</b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=deposit_group=]]);?></b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;">Hạn thanh toán</td>
                <td style="text-align: right;padding-right: 10px;" onclick="return due_date_dp.focus();"><span contenteditable="true" id="due_date_dp" onkeyup="change_value(this);"><?php echo [[=due_date_dp=]]; ?></span><input name="due_date_dp_ip" type="hidden" id="due_date_dp_ip" value="" style="border: hidden;"/></td>
            </tr>
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td style="text-align: right;padding-right: 10px;"><b>Đặt cọc phòng(VND)</b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=total_deopsit=]]);?></b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;">Hạn thanh toán</td>
                <td style="text-align: right;padding-right: 10px;" onclick="return due_date_dpr.focus();"><span contenteditable="true" id="due_date_dpr" onkeyup="change_value(this);"><?php echo [[=due_date_dpr=]]; ?></span><input name="due_date_dpr_ip" type="hidden" id="due_date_dpr_ip" value="" style="border: hidden;"/></td>
            </tr>
            <tr style="font-size:12px;" class="hidden_price_n2d">
                <td style="text-align: right;padding-right: 10px;"><b>Còn lại(VND)</b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;"><b><?php echo System::display_number([[=balance=]]);?></b></td>
                <td colspan="3" style="text-align: right;padding-right: 10px;">Hạn thanh toán</td>
                <td style="text-align: right;padding-right: 10px;" onclick="return due_date_bl.focus();"><span contenteditable="true" id="due_date_bl" onkeyup="change_value(this);"><?php echo [[=due_date_bl=]]; ?></span><input name="due_date_bl_ip" type="hidden" id="due_date_bl_ip" value="" style="border: hidden;" placeholder="Typing date"/></td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr style="font-size:12px;">
                <td style="width: 108px;"><strong>Số lượng khách:</strong></td>
                <td style="width: 80px;" onclick="return total_adult.focus();"><span contenteditable="true" id="total_adult" onkeyup="change_value(this);"><?php echo [[=adult_bc=]]?[[=adult_bc=]]:[[=total_adult=]]; ?></span><input name="total_adult_ip" type="hidden" id="total_adult_ip" value="" style="border: hidden;" placeholder="Typing date"/> Người lớn/</td>
                <td onclick="return total_children.focus();"><span contenteditable="true" id="total_children" onkeyup="change_value(this);"><?php echo [[=child_bc=]]?[[=child_bc=]]:[[=total_child=]]; ?></span><input name="total_children_ip" type="hidden" id="total_children_ip" value="" style="border: hidden;" placeholder="Typing date"/> Trẻ em</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;">
            <tr style="font-size:12px;">
                <td><strong>Giá phòng đã bao gồm những dịch vụ và tiện ích sau:</strong></td>
            </tr>
            <tr style="font-size:12px;">
                <td>- <strong>VND Net/ phòng/ đêm</strong> (Đã bao gồm 10% VAT và 5% phí dịch vụ)</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Ăn sáng cho 02 khách tại nhà hàng.</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Truy cập internet tốc độ cao miễn phí Wifi/Lan.</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Sử dụng hồ bơi ngoài trời.</td>
            </tr>
            <tr style="font-size:12px;">
                <td>- Miễn phí nước uống (1 chai/một khách), trà, café mỗi ngày cho tất cả các phòng.</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto; height: 40px;"  border="1" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td colspan="4" style="border-right: 1px solid #333; width: 20%;"><strong>Ghi chú:</strong></td>
                <td>
                    <label id="special_request_lb" style="width: 99%;"></label>                 
                    <textarea name="special_request" id="special_request" style="width: 99%; height: 40px; border: none;" onkeyup="change_value(this);" placeholder="Nhập ghi chú">[[|special_request|]]</textarea>             
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Hủy đặt phòng:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_1" type="text" id="cancel_line_1" value="<?php echo [[=cancel_line_1=]]?[[=cancel_line_1=]]:'Hủy phòng trước 07 ngày trước ngày nhận phòng: Không phạt'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_1_lb"></label></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_2" type="text" id="cancel_line_2" value="<?php echo [[=cancel_line_2=]]?[[=cancel_line_2=]]:'Hủy phòng trong vòng 05-07 ngày trước ngày nhận phòng: Phạt 01 đêm đầu tiên'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_2_lb"></label></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_3" type="text" id="cancel_line_3" value="<?php echo [[=cancel_line_3=]]?[[=cancel_line_3=]]:'Hủy phòng trong vòng 03-04 ngày trước ngày nhận phòng: Phạt 50% tổng tiền phòng'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_3_lb"></label></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td><input name="cancel_line_4" type="text" id="cancel_line_4" value="<?php echo [[=cancel_line_4=]]?[[=cancel_line_4=]]:'Hủy phòng trong vòng 02 ngày trước ngày nhận phòng: Phạt 100% tổng tiền phòng'; ?>" onchange="change_value(this)" style="border: hidden; width: 100%;"/><label id="cancel_line_4_lb"></label></td>
            </tr>
            <!--<tr style="text-align: left;font-size:12px;">
                <td><strong>Trả phòng trễ/ Nhận phòng sớm:</strong> <i>Yêu cầu nhận phòng sớm và trả phòng trễ sẽ được xác nhận tùy vào trạng phòng thực tế và sẽ được phụ thu theo chính sách của NALOD.</i></td>
            </tr>-->
        </table>
        <!--<table style="width: 100%; margin: 0px auto;" border="1" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">Phụ thu nhận phòng sớm:</td>
                <td style="padding-left: 10px;">Phụ thu trả phòng trễ:</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">Trước 06:00 giờ: 100% tiền phòng/đêm</td>
                <td style="padding-left: 10px;">Sau 12:00 giờ đến15:00 giờ: 500.000 VND/phòng</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">Từ 06:00 giờ đến 09:00 giờ: 900,000 VND/phòng</td>
                <td style="padding-left: 10px;">Từ 15:00 giờ đến 18:00 giờ: 900.000 VND/phòng</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 10px;">Từ 09:00 giờ đến 13:00 giờ: 500.000 VND/phòng</td>
                <td style="padding-left: 10px;">Sau 18:00 giờ: 100% tiền phòng/đêm</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Chính sách trẻ em:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>Số lượng: tối đa 02 trẻ dưới 10 tuổi được ở cùng bố mẹ, sử dụng chung giường và phòng.</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>Phụ thu ăn sáng:</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 100px;">- Trẻ dưới 5 tuổi: Miễn phí</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 100px;">- Trẻ từ 5 đến 10 tuổi: Phụ thu 125,000VNĐ/ trẻ</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 100px;">- Trẻ từ 11 tuổi trở lên: Phụ thu 250,000VNĐ/ trẻ </td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Giường phụ:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="width: 25%;">Người lớn:</td>
                <td style="width: 25%;">675,000 VND net/đêm</td>
                <td style="width: 25%;">Trẻ em:</td>
                <td style="width: 25%;">675,000 VND net/đêm</td>
            </tr>
        </table>-->
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <!--<tr style="text-align: left;font-size:12px;">
                <td><strong>Phụ thu:</strong> Giá trên áp dụng cho khách được đăng ký trước, trong trường hợp phát sinh thêm khách ở, khách sạn áp dụng phụ phí cho khách ở thêm hoặc yêu cầu khách đặt thêm phòng tùy thuộc vào chính sách của khách sạn. </td>
            </tr>-->
            <tr style="text-align: left;font-size:12px;">
                <td><strong>Thanh toán:</strong> Quý khách vui lòng thanh toán toàn bộ chi phí chậm nhất là 07 ngày trước ngày nhận phòng,</td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>T<strong>hông tin tài khoản - Bank account info:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 212px;"><strong>Tên TK/ Bank account:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 212px;"><strong>Số TK VND – VND Account number:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 212px;"><strong>Số TK USD – USD Account number:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 212px;"><strong>CIF:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 212px;"><strong>Tên ngân hàng/ Bank:</strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td style="padding-left: 212px;"><strong>Địa chỉ/ Address: </strong></td>
            </tr>
            <tr style="text-align: left;font-size:12px;">
                <td>Trong trường hợp cần được tư vấn hoặc hỗ trợ thêm, vui lòng liên lạc khách sạn để được trợ giúp.</td>
            </tr>
        </table>
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr style="font-size:12px;">
                <td style="width: 70%;"><strong>By Confirm</strong></td>
                <td style="text-align: left;"><strong>Trân trọng,</strong></td>
            </tr>
            <tr style="font-size:12px;">
            <td style="width: 70%;"><strong><input type="text" name="person_send_book_confirm" id="person_send_book_confirm" value="<?php echo[[=person_send_book_confirm=]]; ?>" style="border: none; font-weight: bold;" placeholder="Enter person send booking confirm" onkeyup="change_value(this);"/></strong><label id="person_send_book_confirm_lb" style="float: left;"></label></strong></td>
                <td style="padding-left: 30px;"><strong>Sales & Marketing Department</strong></td>
            </tr>
        </table>
        <br /><br /><br />
        <hr />
        <table style="width: 100%; margin: 0px auto;" cellspacing="0">
            <tr>
                <td style="text-align: center;"><em style="font-size: 14px;">Ngày in: <?php echo date('H:i d/m/Y', time()) . ' - Người in: ' . [[=person_send_book_confirm=]]; ?></em></td>
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
        jQuery('#'+tag_id+' input:checkbox:checked').attr('checked','checked');
        var printContents = document.getElementById(tag_id).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        location.reload();    
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