<style>
#main{
    width: 960px; 
    height: 595px;
    margin: 0 auto;
}
#main table tr td{
    line-height: 15px;
}
#mi_product tr td{
    line-height: 25px;
}
@media print{
    #hidden_print{
        display: none;
    }
    #payment_type{
        display: none;
    }
    #payment_type_lb{
        display: block;
    }
}
table{
    border-collapse: collapse;
    font-size: 12px;
}
select:hover{
    border: 1px dashed #888;
}
</style>
<div id="main">
<table width="100%">
    <tr>
        <td width="85%">&nbsp;</td>
        <td align="right" style="vertical-align: bottom;" id="hidden_print">
            <a onclick="print_pc_order();" title="In">
                <img src="packages/core/skins/default/images/printer.png" height="40" />
            </a>
        </td>
    </tr>
</table>
    <table>
        <tr>
            <td width="320px" align="center"><img src="<?php echo HOTEL_LOGO; ?>" style="width: auto; height: 70px;" /></td>
            <td width="320px" align="center" style="font-size: 20px;"><strong>ĐƠN ĐẶT HÀNG</strong><br /><span style="font-size: 12px;"><?php $date_create = explode("/",[[=create_time=]]); echo Portal::language('date')." ".$date_create[0]." ".Portal::language("month")." ".$date_create[1]." ".Portal::language('year')." ".$date_create[2]; ?></span></td>
            <td width="320px" align="center" style="font-size: 20px;"><strong><?php echo HOTEL_NAME;?><br /><span style="font-size: 12px;">Tel: <?php echo HOTEL_PHONE; ?> - MST: <?php echo HOTEL_TAXCODE; ?></strong></span><br /></strong></td>
        </tr>
    </table>
    <br />
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; font-weight: bold; width: 50%; border-bottom: hidden;">Mã đơn hàng: [[|code|]]</td>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden;"><strong>Nhà cung cấp: </strong>[[|pc_supplier_name|]]</td>
        </tr>
        <tr>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden;"><strong>Tên đơn hàng: </strong>[[|name|]]</td>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden;"><strong>Đ/C: </strong>[[|pc_supplier_address|]]</td>
        </tr>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden; border-top: hidden;"><strong>Người lập: </strong>[[|creater|]]</td>
            <td style="font-size: 12px; width: 25%; border-bottom: hidden; border-right: hidden; border-top: hidden;"><strong>Tel: </strong>[[|pc_supplier_mobile|]]</td>
            <td style="font-size: 12px; width: 25%; border-bottom: hidden; border-top: hidden;"><strong>Fax: </strong>[[|pc_supplier_fax|]]</td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 25%; border-right: hidden; border-top: hidden;"><strong>Người nhận: </strong>[[|receiver|]]</td>
            <td style="font-size: 12px; width: 25%; border-top: hidden;"><strong>Tel: </strong>[[|tel_of_receipt|]]</td>
            <td style="font-size: 12px; width: 25%; border-right: hidden; border-top: hidden;"><strong>Người liên hệ: </strong>[[|pc_supplier_person_name|]]</td>
            <td style="font-size: 12px; width: 25%; border-top: hidden;"><strong>Di động: </strong>[[|pc_supplier_person_mobile|]]</td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden; border-top: hidden;"><strong>Địa điểm nhận: </strong>[[|place_of_receipt|]]</td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 100%; border-top: hidden;"><strong>Diễn giải: </strong>[[|description|]]</td>
        </tr>
    </table>
    <br />
    <table border="1">
        <tr>
            <th width="5px" align="center">STT</th>
            <th width="100px" align="center">Mã hàng</th>
            <th width="350px" align="center">Tên hàng</th>
            <th width="50px" align="center">ĐVT</th>
            <th width="80px" align="center">Quy cách đóng gói</th>
            <th width="30px" align="center">Số lượng</th>
            <th width="100px" align="center">Đơn giá</th>
            <th width="100px" align="center">Tiền hàng</th>
            <th width="100px" align="center">Tiền thuế</th>
            <th width="100px" align="center">Thành tiền</th>
            <th width="130px" align="center">BP yêu cầu</th>
            <th width="70px" align="center">Ngày giao đề xuất</th>
            <th width="350px" align="center">Ghi chú</th>
        </tr>
        <?php $total_amount =0; ?>
        <!--LIST:mi_list_product-->
        <tr>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.stt|]]</td>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.product_id|]]</td>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.product_name|]]</td>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.unit_name|]]</td>
            <td style="text-align: center; font-size: 12px;"></td>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.quantity|]]</td>
            <td style="text-align: right; font-size: 12px;"><?php echo System::display_number([[=mi_list_product.price=]]); ?></td>
            <td style="text-align: right; font-size: 12px;"><?php echo System::display_number([[=mi_list_product.total_before_tax=]]);?></td>
            <td style="text-align: right; font-size: 12px;"><?php echo System::display_number([[=mi_list_product.service_rate=]]);?></td>
            <td style="text-align: right; font-size: 12px;"> <?php echo System::display_number([[=mi_list_product.total=]]); $total_amount += [[=mi_list_product.total=]]; ?></td>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.department_name|]]</td>
            <td style="text-align: center; font-size: 12px;">[[|mi_list_product.delivery_date|]]</td>
            <td style="text-align: left; font-size: 12px; width: 350px;">[[|mi_list_product.description_product1|]]<br />[[|mi_list_product.note|]]</td>
        </tr>
        <!--/LIST:mi_list_product-->
        <tr>
            <td colspan="5" align="right"><strong>Tổng cộng:</strong></td>
            <td align="center"><?php echo System::display_number([[=total_quantity=]]) ?></td>
            <td align="center"></td>
            <td align="right"><?php echo System::display_number([[=total_amount=]]) ?></td>
            <td align="right"><?php echo System::display_number([[=service_rate=]]) ?></td>
            <td align="right" id="total_amount"><?php echo System::display_number($total_amount) ?></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
        </tr>  
    </table>
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="text-align: left; font-size: 12px;"><strong>Số tiền bằng chữ: </strong><span id="total_final"></span></td>
        </tr>
    </table>
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="text-align: left; width: 158px; font-size: 12px;"><strong>Phương thức thanh toán: </strong></td>
            <td style="text-align: left;font-size: 12px;">
                <select name="payment_type" id="payment_type" style="height: 25px;" onchange="change_payment()"></select>
                <label id="payment_type_lb" style="display: none;"></label>
            </td>
        </tr>
    </table>
    <br /><br /><br /><br /><br />
    <table>
        <tr>
            <td width="240px" align="center" style="font-size: 12px;"><strong>Trưởng bộ phần yêu cầu</strong><br /><br /><br /><br /><br /><br /><br /></td>
            <td width="240px" align="center" style="font-size: 12px;"><strong>Trưởng phòng thu mua</strong><br /><br /><br /><br /><br /><br /><br /></td>
            <td width="240px" align="center" style="font-size: 12px"><strong>Kế toán trưởng</strong><br /><br /><br /><br /><br /><br /><br /><?php echo ([[=status=]]==2 || [[=status=]]==3 || [[=status=]]>3)?'(Đã duyệt)':''; ?></td>
            <td width="240px" align="center" style="font-size: 12px;"><strong>Giám đốc</strong><br /><br /><br /><br /><br /><br /><br /><?php echo ([[=status=]]==3 || [[=status=]]>3)?'(Đã duyệt)':''; ?></td>
        </tr>
    </table>
    <div id="footer">
        <span style="font-size: 12px; font-style: italic; position: fixed; bottom: 0;">Lưu ý: 1 bản sao được lưu tại phòng Kế toán</span>
    </div>
</div>
<script>
jQuery("#chang_language").css('display','none');
jQuery(document).ready(function(){
    jQuery('select[name=payment_type]').val('Chuyển khoản');
    var payment_type = jQuery('select[name=payment_type]').val();
    jQuery('#payment_type_lb').html(payment_type);
    jQuery('#total_final').html(ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").html().trim())));
})
function change_payment()
{
    var payment_type = jQuery('select[name=payment_type]').val();
    jQuery('#payment_type_lb').html(payment_type);
}
function print_pc_order()
{
    var user ='<?php echo User::id(); ?>';
    jQuery('#payment_type_lb').css('display','block');
    printWebPart('printer',user);
    window.location.reload();
}
var ConvertNumberToCharacter = function(){
    var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){
        var o="",a=Math.floor(r/10),e=r%10;
        return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},
        n=function(n,o){
            var a="",e=Math.floor(n/100),n=n%100;
            return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},
            o=function(t,r){
                var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);
                var e=Math.floor(t/1e3),t=t%1e3;
                return e>0&&(o+=n(e,r)+" nghìn",r=!0),t>0&&(o+=n(t,r)),o};
                return{show:function(r){
                    if(0==r)return t[0];
                    var n="",a="";
                    do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";
                    while(r>0);
                    return n.substring(1,2).toUpperCase()+n.substring(2).trim()+ " đồng"
                    }
                }
}();
</script>
