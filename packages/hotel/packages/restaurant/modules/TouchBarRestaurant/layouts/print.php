<form name="frmSubmit" method="post">
<a id="back" style="float: right;" href="<?php echo Url::build_current(array('cmd'=>'edit','id'));?>"  class="button-medium-back">[[.back.]]</a>
<div class="preview">
    <?php
    //System::debug($this->map['header']);
    //System::debug($this->map['content']);
    //System::debug($this->map['print_id']);   
    ?>
    
    <label class="day"><?php echo date('d'); ?></label>
    <label class="month"><?php echo date('m'); ?></label>
    <label class="year"><?php echo date('Y'); ?></label>
    
    <!--</a><label class="representative"><?php //echo $this->map['header']['representative']; ?></label>-->
    <label class="customer_name"><?php echo $this->map['item']['name']; ?></label>
    <label class="address"><?php //echo $this->map['item']['note']?$this->map['item']['customer_address']:$this->map['item']['traveller_address']; ?></label>
    <label class="tax_code"><?php //echo $this->map['item']['note']?$this->map['item']['tax_code']:''; ?></label>
    
    <label class="vat_rate">10</label>
    
    <table cellpadding="7" border="0" class="total">
        <tr>
            <td><?php echo System::display_number($this->map['item']['fee_charge']); ?></td>
        </tr>
        <tr>
            <td><?php echo System::display_number($this->map['item']['fee_tax']); ?></td>
        </tr>
        <tr>
            <td><?php echo System::display_number($this->map['item']['total']); ?></td>
        </tr>
    </table>
    
    <label class="total_in_words"><?php echo $this->map['item']['total_in_words']; ?></label>
    
    
    
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
        <td align="right" style="vertical-align: top;"><?php echo System::display_number($this->map['item']['total_before_rate']); ?></td>
    </tr>
    </table>
</div>

<hr />
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
.preview {
    background-image: url('HOA DON GTGT.jpg'); 
    width: 762px ; 
    height: 1110px;
    font-family: arial;
    font-size:16px;
    position: relative;
    }
    
.preview * {font-size: 16px;}
.day,.month,.year{
    font-style: italic;
}
.day{
    position: absolute;
    top: 108px;
    left: 293px;
}
.month{
    position: absolute;
    top: 108px;
    left: 369px;
}
.year{
    position: absolute;
    top: 108px;
    left: 438px;
}
.representative{
    position: absolute;
    top: 272px;
    left: 320px;
}
.customer_name{
    position: absolute;
    top: 297px;
    left: 206px;
}
.address{
    position: absolute;
    top: 322px;
    left: 150px;
}
.tax_code{
    position: absolute;
    top: 347px;
    left: 550px;
}
.vat_rate{
    position: absolute;
    top: 812px;
    left: 210px;
}
.total_in_words{
    position: absolute;
    top: 876px;
    left: 266px;
}
.content{
    position: absolute;
    top: 491px;
    left: 11px;
    font-family: arial !important;
    font-size:16px !important;
}
.content tr td{
    font-size: 16px;
}
.total{
    position: absolute;
    top: 774px;
    left: 640px;
    text-align: right;
    font-family: arial;
    font-size:16px;
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