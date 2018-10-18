<style>

#traveller_hidden{
    display: none;
}
@media print
{
    #traveller_dn{
        display: none;
    }
    #traveller_hidden{
        display: block;
    }
}
</style>
<div id="container" style="width: 595px; margin: 0 auto;">
    <table style="width: 100%; margin: 0 auto;">
        <tr style="text-align: center;">
            <td><img src="<?php echo HOTEL_LOGO; ?>" width="200" alt="Logo" /></td>
        </tr>
    </table>
    <div id="content" style="width: 100%; margin: 0 auto;">
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;" id="traveller_dn">Kính gởi: <select name="traveller" id="traveller" onchange="change_traveller(this);" style="border: hidden;">[[|traveller_option|]]</select></p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;" id="traveller_hidden">Kính gởi: <span id="traveller_sp"></span>,</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Thay mặt cho toàn thể nhân viên của khách sạn <?php echo HOTEL_PLACE;?>, chúng tôi chân thành cảm ơn quý khách đã lưu trú tại khách sạn trong thời gian qua.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Sự hiện diện của quý khách không chỉ là món quà vô giá mà còn là cơ hội để chúng tôi hoàn thiện những thiếu sót của mình nhằm đem đến cho khách hàng dịch vụ tốt nhất.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Vì vậy, chúng tôi mong muốn sẽ nhận được những phản hồi của quý khách về tất cả các dịch vụ tại khách sạn nhằm phục vụ quý khách tốt hơn.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Chân thành cảm ơn quý khách</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Chúng tôi hi vọng sẽ lại được phục vụ quý khách tại <?php echo HOTEL_PLACE;?> trong thời gian sớm nhất</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Trân trọng,</p>
    </div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#traveller_sp').html(jQuery('#traveller').val());
})
function change_traveller(obj)
{
    value = jQuery(obj).val();
    jQuery('#traveller_sp').html(value);
}
jQuery('#chang_language').click(function(){
    var url = '<?php echo $_SERVER['REQUEST_URI']; ?>';
    location.reload(url);
})
</script>