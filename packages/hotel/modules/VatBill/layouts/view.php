<style>
    @media print {
        
        #module_547 {
            display: none;
        }
    }
</style>
<?php $date_view = explode("/",date('d/m/Y')); ?>
<div class="print" style="width: 960px; height: 1329px; margin: 0px 0px; padding: 0px 0px; position: relative;">
    <div class="content" style="width: 960px; height: 1329px; position: absolute; top: 0px; left: 0px; z-index: 99;">
        <div class="bg" style="position: absolute; top: 0px; left: 0px; background: url('packages/hotel/modules/VatBill/vpqh-vat.png') no-repeat top left; width: 960px; height: 1329px;"></div>
        <span style="position: absolute; top: 68px; left: 965px; font-size: 20px; width: 500px; height: 20px;">[[|print_date|]]</span>
        
        <span style="position: absolute; top: 260px; left: 348px; font-size: 20px; width: 400px; height: 20px;">[[|guest_name|]]</span>
        <span style="position: absolute; top: 260px; left: 935px; font-size: 20px;">[[|recode|]]</span>
        
        <span style="position: absolute; top: 295px; left: 105px; font-size: 20px; width: 580px; height: 40px; text-indent: 240px; text-transform: uppercase; line-height: 20px;">[[|customer_name|]]</span>
        <span style="position: absolute; top: 295px; left: 935px; font-size: 20px;">[[|start_date|]]</span>
        <span style="position: absolute; top: 330px; left: 945px; font-size: 20px;">[[|end_date|]]</span>
        
        <span style="position: absolute !important; left: 115px; top: 365px; font-size: 20px; width: 580px; height: 40px; text-indent: 169px;">[[|customer_address|]]</span>
        <span style="position: absolute; top: 365px; left: 925px; font-size: 20px;">[[|payment_method|]]</span>
        
        <span style="position: absolute; top: 402px; left: 717px; font-size: 20px;">[[|customer_bank_code|]]</span>
        
        <span style="position: absolute; top: 434px; left: 282px; font-size: 20px;">[[|customer_tax_code|]]</span>
        
        
        
        <table class="container_invoice" style="width: 990px; position: absolute; top: 490px; left: 50px; font-size: 20px;">
            <tr style=" height: 42px; font-size: 20px;">
                <th style=" width: 130px;"></th>
                <th style=" width: 390px;"></th>
                <th style=""></th>
            </tr>
            <?php $i=0; $count_items = sizeof([[=items=]]); $total_b_t = 0; ?>
            <!--LIST:items-->
            <?php $i++; ?>
            <tr style=" height: 30px; font-size: 20px;">
                <td width="130px" align="center" style=" font-size: 20px;"><?php echo $i; ?></td>
                <td width="39S0px" style=" font-size: 20px; margin-left:10px;">[[|items.description|]]</td>
                <?php if( $count_items==$i){ ?>
                <td style="right: 50px; text-align: right; font-size: 20px;"><?php echo System::display_number([[=total_before_tax=]] - $total_b_t); ?></td>
                <?php }else{ ?>
                <td style="right: 50px; text-align: right; font-size: 20px;"><?php echo System::display_number(round([[=items.total_before_tax=]])); ?></td>
                <?php } $total_b_t += round([[=items.total_before_tax=]]); ?>
            </tr>
            <!--/LIST:items-->
        </table>
        <span style="position: absolute; top: 1050px; width:195px; left: 350px; font-size: 20px;">10%</span>
        <span style="position: absolute; top: 1050px; width:195px; right: -60px; font-size: 20px; text-align: right;"><?php echo System::display_number([[=total_before_tax=]]); ?></span>
        <span style="position: absolute; top: 1075px;  width:195px; right: -60px; font-size: 20px; text-align: right;"><?php echo System::display_number([[=service_ammount=]]); ?></span>
        <span style="position: absolute; top: 1100px;  width:195px; right: -60px; font-size: 20px; text-align: right;"><?php echo System::display_number([[=tax_amount=]]); ?></span>
        <span id="total_amount" style="position: absolute; top: 1125px;  width:195px; right:-60px; font-size: 20px; text-align: right;"><?php echo System::display_number([[=total_amount=]]); ?></span>
        <span id="total_final" style="position: absolute; top: 1172px; left: 272px; font-size: 20px;"></span>
    </div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#total_final').html(ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").html().trim()))); 
    var count_print = 0;
    count_print = count_print + 1;
    if(count_print==1){
        //console.log(jQuery('#total_final').html());
        printWebPart('printer');
    } 
})
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
