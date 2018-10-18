<?php //System::debug([[=items=]]);
//System::debug($this->map);
 ?>
<form name="frmSubmit" method="post">

<div class="preview" id="preview_<?php echo [[=index=]]; ?>">
    <label class="ticket_group_name">[[|ticket_group_name|]]</label>
    <label class="sub_ticket_group_name">[[|ticket_group_name|]]</label>
    <label class="ticket_name">[[|ticket_name|]]<br />
    <span style="font-size: 11px;">[[|ticket_name_2|]]</span>
    </label>
    </label>
    <br />
    <label></label>
    <label class="sub_ticket_name">
    [[|ticket_name|]]
    <br />
    <span style="font-size: 10px;">[[|ticket_name_2|]]</span>
    </label>
    <label class="ticket_price"><strong>Giá vé</strong>/ <em>price:</em><br /><span style="font-weight: bold;">[[|ticket_price|]] VND</span></label>
    <br />
    <label class="ticket_price_gtgt">
        <span style="font-size: 10px; font-weight: bold;">(Đã bao gồm thuế GTGT)</span>
    </label>
    <label class="sub_ticket_price">
        <strong style="font-size: 12px;">Giá vé</strong>/ <em style="font-size: 12px;">price:</em><br />
        <span style="font-weight: bold; font-size: 12px;">[[|ticket_price|]] VND</span><br/>
        <span style="font-size: 10px; font-weight: bold;">(Đã bao gồm thuế GTGT)</span>
    </label>
    
    <div class="info">
        Mẫu: [[|form|]]<br />
        Ký hiệu: [[|denoted|]]<br />
        Số: [[|code_print|]]<br />
        Ngày: [[|date|]]<br />
        <i style="font-size: 11px;">Liên 2: Giao cho khách hàng</i>
    </div>
    
    <div class="sub_info">
        Mẫu: [[|form|]]<br />
        Ký hiệu: [[|denoted|]]<br />
        Số: [[|code_print|]]<br />
        Ngày: [[|date|]]<br />
        <i style="font-size: 11px;">Liên 1: Lưu</i>
    </div>
    
    <table class="detail" cellpadding="1" border="0" id="detail_[[|index|]]" >
    <?php 
        //System::debug(([[=service=]]));
        //echo count([[=service=]]);
        $num_ser = 0;
        foreach([[=service=]] as $ser)
        {
            $num_ser += 1;
        }
    ?>
    <!--LIST:service-->
    <tr>
        <?php 
        //if (strtoupper([[=service.name_2=]]) == 'ENTRANCE' && [[=is_entrance=]] )
        if($num_ser <= 1) 
        { 
        ?>
        <td style="width: 240px;" align="center"><img src="images/ticket/entrance_black.jpg" /></td>
        <?php 
        } 
        else 
        {   
        ?>
        <td style="width: 240px;">[[|service.name_1|]]/ <em>[[|service.name_2|]]</em></td>
        <td><img src="images/ticket/check_ticket_black.jpg" style="width: 15px; height: 15px;" /></td>
        <?php 
        } 
        ?>
    </tr>
    <!--/LIST:service-->
    </table>
</div>
<?php 
    if([[=index=]] < [[=quantity=]]-1)
    {
?>
<div style="page-break-before:always;page-break-after:always;"></div>
<?php 
    }
?>
</form>
<style type="text/css">
@media print
{
    /*.link{display:none}*/
    a {text-decoration: none;}
}
a:visited{color:#003399;text-decoration: none;}
a {text-decoration: none;}
.preview * {font-size: 14px;}
@media print
{
    #back{display:none}
}

.preview {
    background-image: url('images/ticket/ticket Thanh Tan.jpg'); 
    width: 445px ; 
    height: 235px;
    font-family: arial;
    font-size:12px;
    position: relative;
    top: -5px;
    font-weight: bold;
}

.detail * {
    font-size: 11px;
    font-weight: bold;
}
</style>

<?php 
    if([[=index=]] == [[=quantity=]] or [[=index=]] == 1)
    {
?>
<style>
    #preview_[[|index|]] 
    {
        top: 10px;  
    }
</style>
<?php 
    }
?>
<?php 
    if([[=index=]] == 1)
    {
?>
<style>
    #preview_[[|index|]] 
    {
        top: 2px;  
    }
</style>
<?php 
    }
?>
<script type="text/javascript">

jQuery(document).ready(function(){
    jQuery(".ticket_group_name").css( { "position":"absolute", "top":"15px", "left":"240px", "font-size":"23px","color":"rgb(77, 73, 73)","font-weight":"bold"});
    jQuery(".sub_ticket_group_name").css( { "position":"absolute", "top":"130px", "left":"5px", "font-size":"19px","color":"rgb(77, 73, 73)","font-weight":"bold"});
    jQuery(".ticket_name").css( { "position":"absolute", "top":"40px","width":"200px", "right":"13px", "text-align":"right","font-weight":"bold", "font-size":"15px","font-style":"italic"});
    jQuery(".sub_ticket_name").css( { "position":"absolute","text-align":"center", "top":"152px","width":"110px", "left":"8px","font-weight":"bold", "font-size":"12px","font-style":"italic"});
    jQuery(".ticket_price").css( { "position":"absolute","text-align":"right", "top":"71px","font-weight":"bold", "right":"13px"});
    jQuery(".sub_ticket_price").css( { "position":"absolute","text-align":"center", "top":"185px", "left":"8px","font-weight":"bold","font-size":"12px"});
    jQuery(".info").css( { "position":"absolute", "top":"55px", "left":"145px","font-size":"12px","font-weight":"bold","line-height":"13px"});
    jQuery(".sub_info").css( { "position":"absolute", "top":"55px", "left":"8px","font-size":"9px","font-weight":"bold","line-height":"13px"});
    jQuery(".detail").css( { "position":"absolute","font-weight":"bold", "left":"150px"});
    jQuery(".ticket_price_gtgt").css( { "position":"absolute", "top":"105px","font-weight":"bold", "text-align":"right","right":"13px"});
    
})

</script>
<?php 
    if([[=index=]] == 1 and $num_ser <= 1)
    {
?>
<style>
    #detail_[[|index|]] 
    {
        top: 135px;  
    }
</style>
<?php 
    }
    else
    {
?>
<style>
    #detail_[[|index|]] 
    {
        top: 145px;  
    }
</style>
<?php
    }
?>
