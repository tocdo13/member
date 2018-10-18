<div style="width: 100%; height: 480px;background-color: #F5F5F5;">
    <table width="100%" style="margin: 0 auto;">
        <tr>
            <td style="text-align: center; text-transform: uppercase; font-size: 24px; color: #FFF; background-color: #069696; height: 50px; line-height: 50px; font-weight: bold;">[[.export_excel_for_cns.]]</td>
        </tr>
        <br />
        <tr>
            <td style="padding-left: 300px;"><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?></td>
        </tr>
    </table>
    <br /><br /><br />
    <form id="ExportDataFromSoftwareNewway" method="post" action="">
        <table width="300px" style="margin: 0 auto;">
            <tr>
                <td style="font-size: 12px; width: 80px;">[[.export_date.]]:</td>
                <td style="font-size: 12px; width: 100px;"><input name="export_date" type="text" id="export_date" style="width: 80px; height: 20px;" /></td>
                <td style="font-size: 12px;"><input name="export_excel" type="submit" id="export_excel" value="[[.export.]]"  style="width: 80px; height: 25px;"/></td>
            </tr>
        </table>
    </form>
</div>
<div style="width: 100%;">
    <table width="1150px" style="margin: 0 auto; position: absolute; bottom: 40px;">
        <tr>
            <td style="text-align: center; text-transform: capitalize; font-size: 14px; color: #0000FE; background-color: #069696; height: 30px; line-height: 30px; font-style: italic;"><?php echo '&copy Newway Software by ' . date('Y'); ?></td>
        </tr>
    </table>
</div>
<script>
jQuery('#export_date').datepicker();
jQuery('#export_excel').click(function(){
    var export_date = jQuery('#export_date').val();
    if(export_date == '')
    {
        alert('Bạn chưa chọn ngày. Vui lòng thử lại!');
        return false;
    }
    var url = '<?php echo Url::build_current(array('cmd'=>md5('ExportDataFromSoftwareNewway@2017')));?>'+ '&export_date=' + export_date;
    window.close();
    window.open(url);
})
</script>