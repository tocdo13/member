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
            <td width="320px" align="center" style="font-size: 20px;"><strong>ĐƠN ĐẶT HÀNG</strong><br /><span style="font-size: 12px;"><?php $date_create = explode("/",$this->map['create_time']); echo Portal::language('date')." ".$date_create[0]." ".Portal::language("month")." ".$date_create[1]." ".Portal::language('year')." ".$date_create[2]; ?></span></td>
            <td width="320px" align="center" style="font-size: 20px;"><strong><?php echo HOTEL_NAME;?><br /><span style="font-size: 12px;">Tel: <?php echo HOTEL_PHONE; ?> - MST: <?php echo HOTEL_TAXCODE; ?></strong></span><br /></strong></td>
        </tr>
    </table>
    <br />
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; font-weight: bold; width: 50%; border-bottom: hidden;">Mã đơn hàng: <?php echo $this->map['code'];?></td>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden;"><strong>Nhà cung cấp: </strong><?php echo $this->map['pc_supplier_name'];?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden;"><strong>Tên đơn hàng: </strong><?php echo $this->map['name'];?></td>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden;"><strong>Đ/C: </strong><?php echo $this->map['pc_supplier_address'];?></td>
        </tr>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden; border-top: hidden;"><strong>Người lập: </strong><?php echo $this->map['creater'];?></td>
            <td style="font-size: 12px; width: 25%; border-bottom: hidden; border-right: hidden; border-top: hidden;"><strong>Tel: </strong><?php echo $this->map['pc_supplier_mobile'];?></td>
            <td style="font-size: 12px; width: 25%; border-bottom: hidden; border-top: hidden;"><strong>Fax: </strong><?php echo $this->map['pc_supplier_fax'];?></td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 25%; border-right: hidden; border-top: hidden;"><strong>Người nhận: </strong><?php echo $this->map['receiver'];?></td>
            <td style="font-size: 12px; width: 25%; border-top: hidden;"><strong>Tel: </strong><?php echo $this->map['tel_of_receipt'];?></td>
            <td style="font-size: 12px; width: 25%; border-right: hidden; border-top: hidden;"><strong>Người liên hệ: </strong><?php echo $this->map['pc_supplier_person_name'];?></td>
            <td style="font-size: 12px; width: 25%; border-top: hidden;"><strong>Di động: </strong><?php echo $this->map['pc_supplier_person_mobile'];?></td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 50%; border-bottom: hidden; border-top: hidden;"><strong>Địa điểm nhận: </strong><?php echo $this->map['place_of_receipt'];?></td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; width: 100%; border-top: hidden;"><strong>Diễn giải: </strong><?php echo $this->map['description'];?></td>
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
        <?php if(isset($this->map['mi_list_product']) and is_array($this->map['mi_list_product'])){ foreach($this->map['mi_list_product'] as $key1=>&$item1){if($key1!='current'){$this->map['mi_list_product']['current'] = &$item1;?>
        <tr>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['stt'];?></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['product_id'];?></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['product_name'];?></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['unit_name'];?></td>
            <td style="text-align: center; font-size: 12px;"></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['quantity'];?></td>
            <td style="text-align: right; font-size: 12px;"><?php echo System::display_number($this->map['mi_list_product']['current']['price']); ?></td>
            <td style="text-align: right; font-size: 12px;"><?php echo System::display_number($this->map['mi_list_product']['current']['total_before_tax']);?></td>
            <td style="text-align: right; font-size: 12px;"><?php echo System::display_number($this->map['mi_list_product']['current']['service_rate']);?></td>
            <td style="text-align: right; font-size: 12px;"> <?php echo System::display_number($this->map['mi_list_product']['current']['total']); $total_amount += $this->map['mi_list_product']['current']['total']; ?></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['department_name'];?></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $this->map['mi_list_product']['current']['delivery_date'];?></td>
            <td style="text-align: left; font-size: 12px; width: 350px;"><?php echo $this->map['mi_list_product']['current']['description_product1'];?><br /><?php echo $this->map['mi_list_product']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['mi_list_product']['current']);} ?>
        <tr>
            <td colspan="5" align="right"><strong>Tổng cộng:</strong></td>
            <td align="center"><?php echo System::display_number($this->map['total_quantity']) ?></td>
            <td align="center"></td>
            <td align="right"><?php echo System::display_number($this->map['total_amount']) ?></td>
            <td align="right"><?php echo System::display_number($this->map['service_rate']) ?></td>
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
                <select  name="payment_type" id="payment_type" style="height: 25px;" onchange="change_payment()"><?php
					if(isset($this->map['payment_type_list']))
					{
						foreach($this->map['payment_type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_type',isset($this->map['payment_type'])?$this->map['payment_type']:''))
                    echo "<script>$('payment_type').value = \"".addslashes(URL::get('payment_type',isset($this->map['payment_type'])?$this->map['payment_type']:''))."\";</script>";
                    ?>
	</select>
                <label id="payment_type_lb" style="display: none;"></label>
            </td>
        </tr>
    </table>
    <br /><br /><br /><br /><br />
    <table>
        <tr>
            <td width="240px" align="center" style="font-size: 12px;"><strong>Trưởng bộ phần yêu cầu</strong><br /><br /><br /><br /><br /><br /><br /></td>
            <td width="240px" align="center" style="font-size: 12px;"><strong>Trưởng phòng thu mua</strong><br /><br /><br /><br /><br /><br /><br /></td>
            <td width="240px" align="center" style="font-size: 12px"><strong>Kế toán trưởng</strong><br /><br /><br /><br /><br /><br /><br /><?php echo ($this->map['status']==2 || $this->map['status']==3 || $this->map['status']>3)?'(Đã duyệt)':''; ?></td>
            <td width="240px" align="center" style="font-size: 12px;"><strong>Giám đốc</strong><br /><br /><br /><br /><br /><br /><br /><?php echo ($this->map['status']==3 || $this->map['status']>3)?'(Đã duyệt)':''; ?></td>
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
