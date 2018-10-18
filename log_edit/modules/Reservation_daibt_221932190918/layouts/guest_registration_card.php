<style>
 #welcome-letter-content{
    width: 650px;margin: 0px auto;
 }
 #date-letter,#guest-letter{
    margin-top: 30px;
 }
 #content-letter{
    margin-top: 20px;width: 100%;
 }
 td{
    font-size: 15px;
 }
     #guest_name_lbl{
        display: none;
    }
 select{border: none;}
 @media print{
    select{
        /*for firefox*/ -moz-appearance: none; /*for chrome*/ -webkit-appearance:none; 
    }
    #guest_name
        {
            display: none;
            
        }
        #guest_name_lbl
        {
            display: block;
        }
 }
</style>
<form name="GuestRegistrationCardForm" method="post">
<div id="welcome-letter-content" >
    <center><img src="<?php echo HOTEL_LOGO; ?>" height="100px" /></center>
    <?php if(Url::get('form')==5){ ?>
    <p id="date-letter"><?php echo date('d'); ?><sup>th</sup> <?php echo date('M'); ?> <?php echo date('Y'); ?></p>
    <table>
        <tr>
            <td style="text-align: left; padding-left: 5px; width:60px; height: 35px;">Dear <?php if([[=gender=]]==1){echo 'Mr';}else{echo 'Ms';} ?></td>
            <td style="text-align: left; height: 35px;">
                    <label id="guest_name_lbl">[[|full_name|]]</label>
                    <select name="guest_name" id="guest_name" onchange="check_traveller(this);"></select>
            </td>
        </tr>
    </table> 
    <table id="content-letter">
        <tr style="line-height: 50px;"><td>Welcome to <?php echo HOTEL_PLACE;?>!</td></tr>
        <tr>
            <td>
                We are delighted that you have chosen our hotel as the perfect place to explore <?php echo CITY_NAME;?>. During your stay, we would like to receive your all constructive feedback on the room and the services you encounter.
            </td>
        </tr>
        <tr style="line-height: 40px;"><td>To assist you during your stay:</td></tr>
        <tr>
            <td style="padding-left: 30px;">
                <ul>
                <li>Our team will be available 24/7. If you would like to ask further information or have <br /> any request, please do not hesitate to contact us by dialling number “ 0 “ on the room phone.<br /></li>
                <li>Breakfast will be served at Ocean Restaurant - 4B floor from 6:30am to 10:00am Once again welcome to <?php echo HOTEL_PLACE;?>. We wish you have a wonderful stay in our hotel!</li>
                </ul>
            </td>    
        </tr>
    </table>
    <?php }else{?>
        <table>
            <tr>
                <td style="text-align: left; padding-left: 5px; width:90px; height: 35px;">Kính gửi <?php if([[=gender=]]==1){echo 'Mr';}else{echo 'Ms';} ?></td>
                <td style="text-align: left; height: 35px;">
                        <label id="guest_name_lbl">[[|full_name|]]</label>
                        <select name="guest_name" id="guest_name" onchange="check_traveller(this);"></select>
                </td>
            </tr>
        </table> 
        <table id="content-letter">
            <tr style="line-height: 30px;"><td style="padding-left: 50px;">Xin gửi lời chào trân trọng và nồng nhiệt nhất đến quý khách  từ  <?php echo HOTEL_PLACE;?> !</td></tr>
            <tr><td style=" padding-top: 10px; text-indent: 50px;">Chúng tôi chân thành cảm ơn quý khách đã tin tưởng chọn <?php echo HOTEL_PLACE;?> là điểm nghỉ chân trong hành trình tham quan <?php echo CITY;?> của mình. Trong suốt thời gian ở tại <?php echo HOTEL_PLACE;?>, chúng tôi mong quý khách sẽ có những phản hồi mang tính xây dựng về phòng ở cũng như dịch vụ mà quý khách sử dụng.</td></tr>
            <tr><td style="padding-left: 50px;">Cùng với quý khách trong thời gian nghỉ tại <?php echo HOTEL_PLACE;?>l:</td></tr>
            <tr>
                <td style="padding-left: 80px;">
                    <li>Nhân viên của chúng tôi sẽ làm việc 24/7. Quý khách có thể liên hệ với chúng tôi bằng cách nhấn  phím 0 trên điện thoại để gặp nhân viên tiền sảnh khi có bất kì yêu cầu gì</li><br />
                    <li>Bữa ăn sáng sẽ được phục vụ tại nhà hàng Ocean tầng 4B từ 6:30 đến 10:00 sáng.</li>
                </td>
            </tr>
            <tr><td style="text-indent: 50px;">Một lần nữa xin hân hạnh được chào đón quý khách đến với <?php echo HOTEL_PLACE;?> và chúng tôi xin chúc quý khách có một kỳ nghỉ tuyệt vời tại đây.</td></tr>
            
            <tr style="line-height: 50px;"><td>Trân trọng,</td></tr>
        </table>
    <?php }?>
</div>
</form>
<script>
    jQuery('#guest_name').change(function(){
    });
    jQuery('.cancel').click(function(e){
    alert('ffff');
    });
    function check_traveller(obj){
         GuestRegistrationCardForm.submit();
    }
</script>