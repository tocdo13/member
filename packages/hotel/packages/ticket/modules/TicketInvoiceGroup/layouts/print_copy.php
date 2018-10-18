<?php //System::debug([[=items=]]); ?>
<form name="frmSubmit" method="post">

<div class="preview">
    <label class="ticket_group_name">[[|ticket_group_name|]]</label>
    <label class="sub_ticket_group_name">[[|ticket_group_name|]]</label>
    <label class="ticket_name">[[|ticket_name|]]</label>
    <label class="sub_ticket_name">[[|ticket_name|]]</label>
    <label class="ticket_price">
        <strong>Giá vé</strong>/ <em>price:</em>
        <br />
        <span style="font-weight: normal;">[[|ticket_price|]] VND</span>
    </label>
    <br />
    <label class="ticket_price_gtgt">
        <span style="font-size: 11px; font-weight: normal;">(Giá đã bao gồm thuế GTGT)</span>
    </label>
    <label class="sub_ticket_price"><strong style="font-size: 12px;">Giá vé</strong>/ <em style="font-size: 12px;">price:</em><br /><span style="font-weight: normal; font-size: 12px;">[[|ticket_price|]] VND</span></label>
    
    <div class="info">
        Mẫu: 01/[[|form|]]<br />
        Ký hiệu: [[|denoted|]]<br />
        Số: [[|code_print|]]<br />
        Ngày: [[|date|]]<br />
        <i style="font-size: 11px;">Liên 2: Giao cho khách hàng</i>
    </div>
    
    <div class="sub_info">
        Mẫu:01/[[|form|]]<br />
        Ký hiệu: [[|denoted|]]<br />
        Số: [[|code_print|]]<br />
        Ngày: [[|date|]]<br />
        <i style="font-size: 11px;">Liên 1: Lưu</i>
    </div>
    
    <table class="detail" cellpadding="1" border="0" >
    <!--LIST:service-->
    <tr>
        <?php if (strtoupper([[=service.name_2=]]) == 'ENTRANCE' && [[=is_entrance=]] ) { ?>
        <td style="width: 234px;" align="center"><img src="images/ticket/entrance.jpg" /></td>
        <?php } else { ?>
        <td style="width: 234px;">[[|service.name_1|]]/ <em>[[|service.name_2|]]</em></td>
        <td><img src="images/ticket/check_ticket.jpg" style="width: 15px; height: 15px;" /></td>
        <?php } ?>
    </tr>
    <!--/LIST:service-->
    </table>
</div>
<div style="page-break-before:always;page-break-after:always;"></div>

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
    font-family: time new roman;
    font-size:12px;
    position: relative;
    top: -5px;
}

.detail * {
    font-size: 11px;
    font-weight: normal;
}
</style>

<script type="text/javascript">

jQuery(document).ready(function(){
    jQuery(".ticket_group_name").css( { "position":"absolute", "top":"25px", "left":"290px", "font-size":"23px","color":"#bfbfbf","font-weight":"normal"});
    jQuery(".sub_ticket_group_name").css( { "position":"absolute", "top":"123px", "left":"0px", "font-size":"21px","color":"#bfbfbf","font-weight":"normal"});
    jQuery(".ticket_name").css( { "position":"absolute", "top":"60px", "left":"280px", "font-size":"15px","font-style":"italic"});
    jQuery(".sub_ticket_name").css( { "position":"absolute", "top":"160px", "left":"25px", "font-size":"12px","font-style":"italic"});
    jQuery(".ticket_price").css( { "position":"absolute", "top":"97px", "left":"342px"});
    jQuery(".ticket_price_gtgt").css( { "position":"absolute", "top":"128px", "left":"300px"});
    jQuery(".sub_ticket_price").css( { "position":"absolute", "top":"189px", "left":"25px","font-size":"12px"});
    jQuery(".info").css( { "position":"absolute", "top":"54px", "left":"140px","font-size":"12px","font-weight":"normal","line-height":"13px"});
    jQuery(".sub_info").css( { "position":"absolute", "top":"55px", "left":"8px","font-size":"11px","font-weight":"normal","line-height":"13px"});
    jQuery(".detail").css( { "position":"absolute", "top":"132px", "left":"150px"});
    
})

</script>
