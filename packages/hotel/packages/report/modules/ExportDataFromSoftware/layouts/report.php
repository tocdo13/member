<style>
    .loader  {
      animation: rotate 1s infinite;  
      height: 50px;
      width: 50px;
      margin: 200px auto;
    }
    
    .loader:before,
    .loader:after {   
      border-radius: 50%;
      content: '';
      display: block;
      height: 20px;  
      width: 20px;
    }
    .loader:before {
      animation: ball1 1s infinite;  
      background-color: #cb2025;
      box-shadow: 30px 0 0 #f8b334;
      margin-bottom: 10px;
    }
    .loader:after {
      animation: ball2 1s infinite; 
      background-color: #00a096;
      box-shadow: 30px 0 0 #97bf0d;
    }
    
    @keyframes rotate {
      0% { 
        -webkit-transform: rotate(0deg) scale(0.8); 
        -moz-transform: rotate(0deg) scale(0.8);
      }
      50% { 
        -webkit-transform: rotate(360deg) scale(1.2); 
        -moz-transform: rotate(360deg) scale(1.2);
      }
      100% { 
        -webkit-transform: rotate(720deg) scale(0.8); 
        -moz-transform: rotate(720deg) scale(0.8);
      }
    }
    
    @keyframes ball1 {
      0% {
        box-shadow: 30px 0 0 #f8b334;
      }
      50% {
        box-shadow: 0 0 0 #f8b334;
        margin-bottom: 0;
        -webkit-transform: translate(15px,15px);
        -moz-transform: translate(15px, 15px);
      }
      100% {
        box-shadow: 30px 0 0 #f8b334;
        margin-bottom: 10px;
      }
    }
    
    @keyframes ball2 {
      0% {
        box-shadow: 30px 0 0 #97bf0d;
      }
      50% {
        box-shadow: 0 0 0 #97bf0d;
        margin-top: -20px;
        -webkit-transform: translate(15px,15px);
        -moz-transform: translate(15px, 15px);
      }
      100% {
        box-shadow: 30px 0 0 #97bf0d;
        margin-top: 0;
      }
    }
</style>
<div style="width: 100%; height: 480px; background-color: #F5F5F5;">
    <table width="100%" style="margin: 0 auto; position: relative; top: 0;">
        <tr>
            <td style="text-align: center; text-transform: uppercase; font-size: 24px; color: #FFF; background-color: #069696; height: 50px; line-height: 50px; font-weight: bold;">[[.export_excel_for_cns.]]</td>
        </tr>
        <br />
        <tr>
            <td style="padding-left: 300px;"><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?></td>
        </tr>
    </table>
    <br /><br /><br />
    <table width="300px" style="margin: 0 auto; position: relative; top: 0;">
        <tr>
            <td style="font-size: 12px; width: 80px;">[[.export_date.]]:</td>
            <td style="font-size: 12px; width: 100px;"><input name="export_date" type="text" id="export_date" style="width: 80px; height: 20px;" readonly=""/></td>
            <td style="font-size: 12px;"><input name="export_excel" type="submit" id="export_excel" value="[[.export.]]"  style="width: 80px; height: 25px;"/></td>
        </tr>
    </table>
</div>
<div style="width: 100%;">
    <table width="100%" style="margin: 0 auto; position: relative; bottom: 0;">
        <tr>
            <td style="text-align: center; text-transform: capitalize; font-size: 14px; color: #0000FE; background-color: #069696; height: 30px; line-height: 30px; font-style: italic;"><?php echo '&copy Newway Software ' . date('Y'); ?></td>
        </tr>
    </table>
</div>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 999;">
    <div class="loader"></div>
</div>
<script>
jQuery('#export_date').datepicker();
function OpenLoading()
{
    jQuery("#LoadingCentral").css('display','');
}
function CloseLoading()
{
    jQuery("#LoadingCentral").css('display','none');
}
jQuery('#export_excel').click(function(){
    var export_date = jQuery('#export_date').val();
    var cmd = '<?php echo md5('ExportDataFromSoftwareNewway@'.date('Y')).md5('n2d'); ?>';
    if(export_date == '')
    {
        alert('Bạn chưa chọn ngày. Vui lòng thử lại!');
        jQuery('#export_date').focus();
        jQuery('#export_date').css('background-color','yellow');                
        return false;
    }    
    OpenLoading();    
    jQuery.ajax({
        type: "POST",
        url: 'get_data_from_software_fast.php',
        data: {'cmd': cmd, 'export_date': export_date},
        success: function(res)
        {
            window.location.href = res;
            CloseLoading();            
        }         
    });            
})
</script>