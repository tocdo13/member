<div id="download" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: url(packages/core/skins/default/images/buttons/sale/bk_body.jpg) repeat top left;">
    <form name="DownloadCustomerForm" method="post" style="display: none;">
        <input name="file_name" id="file_name" type="text"  value="<?php echo $this->map['file']['file_name']; ?>"/>
    </form>
    <div style="width: 600px; height: 400px; margin: 50px auto; background: #ffffff; border-radius: 10px;  box-shadow: 0px 0px 5px #000000; border: 3px solid #555555;">
        <div style="width: 100%; height: 30px; border-radius: 10px; text-align: center; background: #36d0ff; border-bottom: 2px solid #c2f1ff; box-shadow: 0px 0px 5px #cccccc;"><h3 style="font-weight: bold; line-height: 30px; text-transform: uppercase; color: #fff;">[[.download_file_manager.]]</h3></div>
        <table style="width: 95%; margin: 10px auto; color: #ffffff; border-bottom: 3px dashed #00b9f2;">
            <tr>
                <td><div style="width: 100px; height: 100px; overflow: hidden; text-align: center; border: 3px solid #ffffff; box-shadow: 0px 0px 5px #999999;border-radius: 50%;"><a href="?page=home" style="margin: 20px auto;"><img src="<?php echo HOTEL_LOGO; ?>"  style="width: 100px; height: auto; margin: 20px auto;" /></a></div></td>
                <td style="text-transform: uppercase; color: #00b9f2;">
                    <span>[[.hotel_name.]]: </span><b><?php echo HOTEL_NAME; ?></b><br />
                    <span>[[.customer.]]: </span><b><?php if($this->map['customer']['name']){echo $this->map['customer']['name'];} ?></b><br />
                    <span>[[.group_name.]]: </span><b><?php if($this->map['customer']['name']){echo $this->map['customer']['g_name'];} ?></b><br />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-transform: uppercase; color: #00b9f2; text-align: center;">
                    <span>[[.file_name.]]:</span><b><?php if($this->map['file']['id']){echo $this->map['file']['file_name'];} ?></b><br />
                    <span>[[.file_type.]]:</span><b><?php if($this->map['file']['id']){echo $this->map['file']['file_type'];} ?></b> <span style="font-weight: bold;">-</span>
                    <span>[[.file_size.]]:</span><b><?php if($this->map['file']['id']){echo $this->map['file']['file_size']."Kb";} ?></b> 
                </td>
            </tr>
        </table> 
        <div style="padding: 10px; margin: 5px auto; background: #ffffff; border: 1px solid #00b9f2; width: 250px;">
            <table style="width: 100%;">
                <tr style="text-align: center;">
                    <td id="button_link" colspan="2">
                    <a href="<?php echo $this->map['file']['file_link']; ?>">DOWNLOAD</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php
$code_captra = time();
//echo $code_captra;
//$code_captra = md5($code_captra);
$code_captra = substr($code_captra,-6);
?>
<script>
    var code = <?php echo $code_captra; ?>;
    //console.log(code);
    jQuery("#code_captra_lbl").html(code);
    var num = 1;
    function download(){
        var cap1 = Number(jQuery("#code_captra").val());
        var cap2 = Number(jQuery("#code_captra_lbl").html());
        if(num==1){
            if(cap1==cap2){
                alert("");
            }
            num = num+1;
        }
    }
</script>
