<style>
    @media print {
        
        #module_547 {
            display: none;
        }
        #export {
            display: none;
        }
        #print_invoice {
            display: none;
        }
    }
</style>
    <input type="button" id="export" value="[[.export_excel.]]" style="padding: 5px;" />
    <input type="button" id="print_invoice" value="[[.print.]]" style="padding: 5px;" />
    <table id="tblExport" style="width: 960px; margin: 0px auto;">
        <tr>
            <th style="width: 150px; vertical-align: top;"><img src="<?php echo HOTEL_LOGO;  ?>" style="width: 150px; height: auto;" /></th>
            
            <th style="text-align: center; vertical-align: top;">
                <h2 style="line-height: 20px; font-size: 15px;">BẢNG KÊ CHI TIẾT DOANH THU THEO HÓA ĐƠN VAT</h2>
                <p style="font-size: 11px; color: #555555;"><?php echo HOTEL_ADDRESS; ?>
                <br/>Tel: <?php echo HOTEL_PHONE; ?> | Fax: <?php echo HOTEL_FAX; ?>
                <br/>Email: <?php echo HOTEL_EMAIL; ?> | Website: <?php echo HOTEL_WEBSITE; ?></p>
            </th>
            
            <th style="text-align: right; width: 150px; vertical-align: top;">Số hóa đơn: [[|vat_code|]]<br />Mã đặt phòng: [[|recode|]]</th>
        </tr>
        <tr>
            <td colspan="3">
            <hr />
            <br />
            <br />
                <table style="width: 100%;">
                    <tr>
                        <td>Tên khách hàng: [[|guest_name|]]</td>
                        <td></td>
                        <td>Ngày HĐ: [[|print_date|]]</td>
                    </tr>
                    <tr>
                        <td>Đơn vị mua hàng: <span style="text-transform: uppercase;">[[|customer_name|]]</span></td>
                        <td></td>
                        <td>Mã số thuế: [[|customer_tax_code|]]</td>
                    </tr>
                    <tr>
                        <td>Ngày đến: [[|start_date|]]</td>
                        <td></td>
                        <td>Ngày in: <?php echo date('d/m/Y'); ?></td>
                    </tr>
                    <tr>
                        <td>Ngày đi: [[|end_date|]]</td>
                        <td></td>
                        <td>Người in: [[|user_name|]]</td>
                    </tr>
                </table>
            <br />
            <br />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <hr />
                <br /><br />
                <table style="width: 100%;">
                    <tr style="text-align: center;">
                        <th style="width: 70px;">STT</th>
                        <th style="text-align: left;">Nhóm doanh thu</th>
                        <th style="text-align: right;">Số tiền</th>
                    </tr>
                    <?php $i=0; $count_items = sizeof([[=items=]]); $total_b_t = 0; ?>
                    <!--LIST:items-->
                    <?php $i++; ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i; ?></td>
                        <td>[[|items.description|]]</td>
                        <?php if( $count_items==$i){ ?>
                        <td style="text-align: right;" class="col_num"><?php echo System::display_number([[=total_before_tax=]] - $total_b_t); ?></td>
                        <?php }else{ ?>
                        <td style="text-align: right;" class="col_num"><?php echo System::display_number(round([[=items.total_before_tax=]])); ?></td>
                        <?php } $total_b_t += round([[=items.total_before_tax=]]); ?>
                    </tr>
                    <!--/LIST:items-->
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <hr />
                <br /><br />
                <table style="width: 100%;">
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Cộng tiền: </td>
                        <td style="width: 200px;" class="col_num"><?php echo System::display_number([[=total_before_tax=]]); ?></td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Phí dịch vụ: </td>
                        <td style="width: 200px;" class="col_num"><?php echo System::display_number([[=service_ammount=]]); ?></td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Thuế GTGT: </td>
                        <td style="width: 200px;" class="col_num"><?php echo System::display_number([[=tax_amount=]]); ?></td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Tổng cộng tiền thanh toán: </td>
                        <td style="width: 200px;" class="col_num"><?php echo System::display_number([[=total_amount=]]); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<script>
jQuery(document).ready(function(){
    jQuery("#print_invoice").click(function () {
            var user_id = '<?php echo User::id(); ?>';
            printWebPart('printer',user_id);
        });
    jQuery("#export").click(function () {
            jQuery('.col_num').each(function(){
                jQuery(this).html(to_numeric(jQuery(this).html()));
            });
            jQuery('img').remove();
            jQuery('br').remove();
            jQuery('hr').remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
        }); 
});
var ConvertNumberToCharacter = function(){
    var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){
        var o="",a=Math.floor(r/10),e=r%10;
        return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" bốn":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},
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
