<form name="frmSubmit" method="post">
<!--IF:cond([[=page_no=]]<=1)-->
<div style="position: fixed;top: 140px;left: 950px;">
    <a id="back" style="float: right;" href="<?php echo Url::build_current(array('cmd'=>'list','department'=>Url::get('department')));?>"  class="button-medium-back">[[.list_printed.]]</a>
</div>
<!--/IF:cond-->
<div class="preview">
    
    <label class="day"><?php echo date('d'); ?></label>
    <label class="month"><?php echo date('m'); ?></label>
    <label class="year"><?php echo date('Y'); ?></label>
    <label class="time"><?php echo date('H:i'); ?></label>
    <label class="customer_name"><?php echo Url::get('customer_name'); ?></label>
    <label class="customer_address"><?php echo Url::get('customer_address'); ?></label>
    <label class="arrival_time"><?php echo Url::get('arrival_time'); ?></label>
    <label class="departure_time"><?php echo Url::get('departure_time'); ?></label>
    <label class="room_name"><?php echo Url::get('room_name'); ?></label>
    <label class="room_price"><?php echo ( Url::get('room_price')?Url::get('room_price').'(VND)':''  ); ?></label>
    <label class="service_rate"><?php echo ( Url::get('service_rate')?Url::get('service_rate').'(%)':'' ) ; ?></label>
    <label class="tax_code"><?php echo Url::get('tax_code'); ?></label>
    <!--IF:cond([[=is_modify=]]==1)-->
    <label class="modified">[[.modified.]]</label>
    <!--/IF:cond-->
    <label class="page">[[.page.]] [[|page_no|]]/[[|total_page|]]</label>
    <table cellpadding="1" border="0" class="content">
        <!--LIST:items-->
        <tr>
            <td style="width: 60px;">[[|items.stt|]]</td>
            <td style="width: 202px;">[[|items.description|]]</td>
            <td style="width: 110px; text-align: right;">[[|items.price_before_tax|]]</td>
            <td style="width: 88px; text-align: right;">[[|items.service_fee|]]</td>
            <td style="width: 115px; text-align: center;">[[|items.tax_rate|]]</td>
            <td style="width: 65px; text-align: right;">[[|items.tax_fee|]]</td>
        </tr>
        <!--/LIST:items-->
    </table>
    <!--IF:cond([[=page_no=]]!=[[=total_page=]])-->
    <label class="continue">[[.continue.]]...</label>
    <!--/IF:cond-->
    <!--IF:cond([[=page_no=]]==[[=total_page=]])-->
    <table cellpadding="0" border="0" class="sub-total">
        <tr>
            <td style="width: 97px; text-align: right;">[[|total_price_before_tax|]]</td>
            <td style="width: 88px; text-align: right;">[[|total_service_fee|]]</td>
            <td style="width: 186px; text-align: right;">[[|total_tax_fee|]]</td>
        </tr>
    </table>
    <label class="total">[[|total|]]</label>
    <label class="total_in_words">[[|total_in_words|]]</label>
    <!--/IF:cond-->
    <!--
    <table border="0" cellpadding="3" class="content">
    <tr>
        <td style="width: 35px;vertical-align: top;">1</td>
        <td style="width: 290px;">
            <div id="content" style="width: 280px; text-align:left; font-size: 16px; font-family: arial;line-height: 20px; display: none;"></div>
            <div id="wrapper">
                <textarea id="manual_text" style="width: 280px; height:80px; text-align:center; font-size: 16px; font-family: arial;">
                </textarea>
                <input id="ok" type="button" value="OK" style="width: 80px;"/>
            </div>
            
        </td>
        <td style="width: 280px"></td>
        <td align="right" style="vertical-align: top;"><?php echo ''; ?></td>
    </tr>
    </table>
    -->
</div>
</form>
<hr />
<script>
    jQuery(document).ready(function(){
        jQuery("#ok").click(function(){
            jQuery("#content").html(jQuery("#manual_text").val());
            jQuery("#content").css('display','inline');
            jQuery("#wrapper").css('display','none');
        });
        jQuery("#content").click(function(){
            jQuery("#manual_text").html(jQuery("#content").val());
            jQuery("#content").css('display','none');
            jQuery("#wrapper").css('display','block');
        });
        
    })
    jQuery("#content").html('aaaaa');
</script>
<style type="text/css">
.preview {
    background-image: url('HD GTGT Reception.png'); 
    width: 794px ; 
    height: 970px;
    font-family: arial;
    font-size:12px;
    position: relative;
    }
.preview * {font-size: 12px; font-weight: bold;}
.day,.month,.year,.time{
    font-style: italic;
}
.day{
    position: absolute;
    top: 133px;
    left: 333px;
}
.month{
    position: absolute;
    top: 133px;
    left: 395px;
}
.year{
    position: absolute;
    top: 133px;
    left: 450px;
}
.time{
    position: absolute;
    top: 148px;
    left: 350px;
}
.customer_name{
    position: absolute;
    top: 260px;
    left: 150px;
}

.room_name{
    position: absolute;
    top: 245px;
    left: 589px;
}
.customer_address{
    position: absolute;
    top: 276px;
    left: 124px;
}
.room_price{
    position: absolute;
    top: 260px;
    left: 608px;
}
.arrival_time{
    position: absolute;
    top: 291px;
    left: 153px;
}
.departure_time{
    position: absolute;
    top: 291px;
    left: 400px;
}
.service_rate{
    position: absolute;
    top: 290px;
    left: 645px;
}
.tax_code{
    position: absolute;
    top: 306px;
    left: 603px;
}
.page{
    position: absolute;
    top: 90px;
    left: 700px;
}
.modified{
    position: absolute;
    top: 90px;
    left: 630px;
}
.content{
    position: absolute;
    top: 370px;
    left: 52px;
    font-family: arial !important;
    font-size:11px !important;
}
.continue{
    position: absolute;
    top: 860px;
    left: 655px;
}
.sub-total{
    position: absolute;
    top: 825px;
    left: 333px;
}
.total{
    position: absolute;
    top: 843px;
    left: 220px;
}
.total_in_words{
    position: absolute;
    top: 870px;
    left: 160px;
}
@media print
{
    #back{display:none}
}
</style>
<script type="text/javascript">
//chi cho nhap so
function keypress(e)
{
    var keypressed = null;
    if (window.event)
    {
        keypressed = window.event.keyCode;
    }
    else
    { 
        keypressed = e.which;
    } 
    if (keypressed < 48 || keypressed > 57)
    {
        if (keypressed == 8 || keypressed == 127)
        {
            return;
        }
        return false;
    }
} 
</script>