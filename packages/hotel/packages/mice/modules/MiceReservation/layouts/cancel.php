<style>
</style>
<div style="width: 600px; height: 400px; margin: 0px auto; background: #EEEEEE; border: 1px solid #DDDDDD; box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.7), 0 1px 2px rgba(0, 0, 0, 0.05);">
    <h3 style="text-align: center; line-height: 30px; letter-spacing: 10px; text-transform: uppercase; font-size: 15px; border-bottom: 1px solid #DDDDDD;"> [[.cancel.]] MICE</h3>
    <table style="width: 100%;" cellpadding="10">
        <tr>
            <td style="text-align: center;">[[.are_you_cancel_mice.]] MICE+<?php echo Url::get('id'); ?></td>
        </tr>
        <tr>
            <td><label>[[.note_cancel_mice.]] :</label></td>
        </tr>
        <tr>
            <td><textarea name="note_cancel" id="note_cancel" style="width: 100%; min-height: 100px;"></textarea></td>
        </tr>
        <tr>
            <td><label>[[.amount_cancel.]] :</label></td>
        </tr>
        <tr>
            <td><input name="amount_cancel_fee" type="text" id="amount_cancel_fee" style="width: 100%; height: 35px; text-align: center;" /></td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <input name="cancel" type="submit" value="[[.ok.]]" onclick="return check_submit();" style="padding: 10px 30px;" />
            </td>
        </tr>
    </table>
</div>
<script>
    jQuery(document).ready(function(){
        CloseMenu();
    });
    
    function CloseMenu()
    {
        jQuery('#testRibbon').css('display','none');
        jQuery("#sign-in").css('display','none');
        jQuery("#chang_language").css('display','none');
    }
    function OpenMenu()
    {
        jQuery('#testRibbon').css('display','');
        jQuery("#sign-in").css('display','');
        jQuery("#chang_language").css('display','');
    }
    function check_submit()
    {
        var note = jQuery("note_cancel").val().trim();
        if(note)
    }
</script>