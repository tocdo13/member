<form name="frmSubmit" method="post">
<!--IF:cond([[=page_no=]]<=1)-->
<div style="position: fixed;top: 140px;left: 950px;">
    <a id="back" style="float: right;" href="<?php echo Url::build_current(array('cmd'=>'list','department'=>Url::get('department')));?>"  class="button-medium-back">[[.list_printed.]]</a>
</div>
<!--/IF:cond-->
<div class="preview" <?php if([[=page_no=]]>1){?>style="margin-top:100px;"<?php }?>>
    
    <label class="day"><?php echo date('d'); ?></label>
    <label class="month"><?php echo date('m'); ?></label>
    <label class="year"><?php echo date('Y'); ?></label>
    <label class="time"><?php echo date('H:i'); ?></label>
    <label class="code"><?php echo Url::get('code'); ?></label>
    <label class="guest_name"><?php echo Url::get('guest_name'); ?></label>
    <label class="customer_name"><?php echo Url::get('customer_name'); ?></label>
    <label class="customer_address"><?php echo Url::get('customer_address'); ?></label>
    <label class="arrival_time"><?php echo Url::get('arrival_time'); ?></label>
    <label class="departure_time"><?php echo Url::get('departure_time'); ?></label>
    <label class="room_name"><?php echo Url::get('room_name'); ?></label>
    <label class="num_people"><?php echo Url::get('num_people'); ?></label>
    <label class="room_price"><?php echo ( Url::get('room_price')?Url::get('room_price').' VND':''  ); ?></label>
    <label class="service_rate"><?php echo ( Url::get('service_rate')?Url::get('service_rate').' %':'' ) ; ?></label>
    <label class="tax_code"><?php echo Url::get('tax_code'); ?></label>
    <!--IF:cond([[=is_modify=]]==1)-->
    <label class="modified">[[.modified.]]</label>
    <!--/IF:cond-->
    <label class="page">[[.page.]] [[|page_no|]]/[[|total_page|]]</label>
    <table cellpadding="1" border="0" class="content">
<?php $stt = (([[=page_no=]]-1)*Url::get('line_per_page'));?>
        <!--LIST:items-->
        <tr>
            <td style="width: 60px; font-size: 13px!important;"><?php echo ++$stt;?></td>
            <td style="width: 202px;">[[|items.description|]]</td>
            <td style="width: 110px; text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=items.price_before_tax=]]),0));?></td>
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
    <label class="total">VND [[|total|]]</label>
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

.preview * {font-size: 12px; font-weight: bold;}
.day,.month,.year,.time{
    font-style: italic;
}

<!--IF:cond([[=page_no=]]==1)-->
.preview {
    background-image: url('HD GTGT Reception.png'); 
    width: 794px ; 
    height: 970px;
    font-family: arial;
    font-size:12px;
    position: relative;
}
.day{
    position: absolute;
    top: 146px;
    left: 353px;
}
.month{
    position: absolute;
    top: 146px;
    left: 415px;
}
.year{
    position: absolute;
    top: 146px;
    left: 470px;
}
.time{
    position: absolute;
    top: 160px;
    left: 375px;
}
.code{
    position: absolute;
    top: 154px;
    left: 648px;
}
.customer_name{
    position: absolute;
    top: 277px;
    left: 170px;
}
.guest_name{
    position: absolute;
    top: 267px;
    left: 216px;
}

.room_name{
    position: absolute;
    top: 267px;
    left: 620px;
}
.num_people{
    position: absolute;
    top: 267px;
    left: 750px;
}

.customer_address{
    position: absolute;
    top: 293px;
    left: 144px;
}
.room_price{
    position: absolute;
    top: 277px;
    left: 640px;
}
.arrival_time{
    position: absolute;
    top: 314px;
    left: 173px;
}
.departure_time{
    position: absolute;
    top: 314px;
    left: 420px;
}
.service_rate{
    position: absolute;
    top: 312px;
    left: 680px;
}
.tax_code{
    position: absolute;
    top: 324px;
    left: 628px;
}
.page{
    position: absolute;
    top: 117px;
    left: 710px;
}
.modified{
    position: absolute;
    top: 97px;
    left: 640px;
}
.content{
    position: absolute;
    top: 410px;
    left: 62px;
    font-family: arial !important;
    font-size:11px !important;
}
.continue{
    position: absolute;
    top: 860px;
    left: 665px;
}
.sub-total{
    position: absolute;
    top: 867px;
    left: 343px;
}
.total{
    position: absolute;
    top: 892px;
    left: 450px;
    font-size: 13px!important;
}
.total_in_words{
    position: absolute;
    top: 920px;
    left: 180px;
}
<!--ELSE-->
.preview {
    background-image: url('HD GTGT Restaurant.png'); 
    background-position : 0 16px;
    width: 794px ; 
    height: 970px;
    font-family: arial;
    font-size:12px;
    position: relative;
}
.day{
    position: absolute;
    top: 162px;
    left: 353px;
}
.month{
    position: absolute;
    top: 162px;
    left: 415px;
}
.year{
    position: absolute;
    top: 162px;
    left: 470px;
}
.time{
    position: absolute;
    top: 177px;
    left: 375px;
}
.code{
    position: absolute;
    top: 163px;
    left: 648px;
}
.customer_name{
    position: absolute;
    top: 294px;
    left: 170px;
}
.guest_name{
    position: absolute;
    top: 283px;
    left: 216px;
}

.room_name{
    position: absolute;
    top: 283px;
    left: 620px;
}
.num_people{
    position: absolute;
    top: 283px;
    left: 750px;
}

.customer_address{
    position: absolute;
    top: 307px;
    left: 144px;
}
.room_price{
    position: absolute;
    top: 294px;
    left: 640px;
}
.arrival_time{
    position: absolute;
    top: 331px;
    left: 173px;
}
.departure_time{
    position: absolute;
    top: 331px;
    left: 420px;
}
.service_rate{
    position: absolute;
    top: 319px;
    left: 660px;
}
.tax_code{
    position: absolute;
    top: 340px;
    left: 628px;
}
.page{
    position: absolute;
    top: 123px;
    left: 710px;
}
.modified{
    position: absolute;
    top: 113px;
    left: 640px;
}
.content{
    position: absolute;
    top: 410px;
    left: 62px;
    font-family: arial !important;
    font-size:11px !important;
}
.continue{
    position: absolute;
    top: 876px;
    left: 665px;
}
.sub-total{
    position: absolute;
    top: 882px;
    left: 343px;
}
.total{
    position: absolute;
    top: 907px;
    left: 450px;
    font-size: 13px!important;
}
.total_in_words{
    position: absolute;
    top: 937px;
    left: 180px;
}
<!--/IF:cond-->
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