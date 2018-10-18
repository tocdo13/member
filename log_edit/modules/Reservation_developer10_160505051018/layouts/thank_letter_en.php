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
<div id="container" style="width: 594px; margin: 0 auto;">
    <table style="width: 100%; margin: 0 auto;">
        <tr style="text-align: center;">
            <td><img src="<?php echo HOTEL_LOGO; ?>" width="200" alt="Logo" /></td>
        </tr>
    </table>
    <div id="content" style="width: 100%; margin: 0 auto;">
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;" id="traveller_dn">Dear <select name="traveller" id="traveller" onchange="change_traveller(this);" style="border: hidden;">[[|traveller_option|]]</select></p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;" id="traveller_hidden">Dear <span id="traveller_sp"></span>,</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">On behalf of the entire team at <?php echo HOTEL_PLACE;?>, I sincerely would like to thank you for staying with us.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Your presence is not only a valuable gift but also a chance for us to improve our hospitality.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Therefore, in order for us to welcome you in a better way in the future, it would be a privilege to receive your comments on any aspect of our services and facilities.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Thank you very much and I hope to welcome you again in the near future.</p>
        <p style="text-align: justify; font-style: italic; font-weight: normal; font-size: 12px;">Best regards!</p>
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