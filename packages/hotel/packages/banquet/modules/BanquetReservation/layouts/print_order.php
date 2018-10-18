<style>

@media print{
    table tr td{
        border-collapse: collapse;
    }
}
</style>

<!--?php echo"tet13";?-->
<div style="width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center">
				<table cellpadding="0" width="100%" border="0">
				<!--<tr>
                <td colspan="3">Code: #00[[|id|]]</td>
				</tr>-->
                <tr>
                    <td style="width: 10%;">
                       <img src="resources/interfaces/images/default/logo.png" style="width: auto; height: 70px;">
                    </td>
					<td align="center" style="padding-right: 130px;">
                        <br /><br />
						<font style="font-size:20px;text-transform:uppercase;text-align: center;"><b>[[.product_info.]]</b></font><br /><br />
                        <font style="font-size:14px;color: #636367 ; "><?php echo HOTEL_ADDRESS;?></font><br/>
                        <font style="font-size:14px;color: #636367;">[[.tel.]]: <?php echo HOTEL_PHONE; ?>&nbsp;&nbsp; fax: <?php echo HOTEL_FAX ?> </font><br />
                        <font style="font-size:14px;color: #636367;">Email: <?php echo HOTEL_EMAIL; ?>&nbsp;&nbsp; Website: <?php echo HOTEL_WEBSITE ?> </font>				
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr bgcolor="#F4F4F4">
			<td bgcolor="#FFFFFF">
				<table width="100%" cellpadding="5">
                    <tr>
                    	<td></td>
                    </tr>
					<tr>
                        <td width="100px" ><b>[[.traverler.]]</b> :</td>
                        <!--LIST:traveler-->
                        <td>[[|traveler.full_name|]]</td>
                        <!--/LIST:traveler-->
					  	<td width="10%" class="form-label"><b>[[.invoice.]]</b> :</td>
					  	<td style=" width: 20%;">#00[[|id|]]</td>
				  	</tr>
                    <tr>
                        <td><b>[[.address.]]</b> :</td>
                         <!--LIST:traveler-->
                        <td>[[|traveler.address|]]</td>
                        <!--/LIST:traveler-->
                        <td  class="form-label"><b>[[.cashier.]] :</b></td>
					 	<td ><a>[[|user_name|]]</a></td>
                    </tr>
					<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
					  	<td  class="form-label" ><b>[[.print_date.]]</b> :</td>
                      	
					  	<td > <?php echo date('d/m/Y H:i\'');?></td>
				  	</tr>                    
				
				</table>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<br/>
<div style="width:980px;margin-left:auto;margin-right:auto;margin-top:3px;text-align:center;">
<table width="100%" class="price" border="1px" style="border-collapse: collapse;">
	<tr  style="border: 1px solid silver;">
        <td width="5%" style="text-align: center;" ><b> STT</b></td>
        <td style="text-align: center;" width="25%"><b>[[.name.]]</b></td>
    	<td style="text-align: center;" width="10%"><b>[[.quantity.]]</b></td>
        <td style="text-align: center;"><b>[[.unit.]]</b></td>
        <td style="text-align: center;"><b>[[.price.]]</b></td>
        <td style="text-align: center;"><b>[[.amount.]]</b></td>
    </tr>
    <?php $i = 1; ?>
    <!--LIST:items-->
    <tr style="border: 1px solid silver;">
        <td style="text-align: center;"><?php echo $i++; ?></td>
        <td style="text-align: left;">[[|items.product_name|]]</td> 
    	<td style="text-align: center;">[[|items.quantity|]]</td>       
        <td style="text-align: center;">[[|items.product_unit|]]</td>
        <td style="text-align: right;"><?php echo System::display_number([[=items.price=]]);  ?></td>
        <td style="text-align: right;"><?php echo System::display_number([[=items.quantity=]] *[[=items.price=]]); ?></td>
    </tr>
    <!--/LIST:items-->
    <!--LIST:rooms-->
    <tr style="border: 1px solid silver;">
        <td style="text-align: center;"><?php echo $i++; ?></td>
        <td style="text-align: left;">[[|rooms.name|]]</td> 
    	<td style="text-align: center;">1</td>       
        <td style="text-align: center;">phong</td>
        <td style="text-align: right;"><?php echo System::display_number([[=rooms.price=]]) ?></td>
        <td style="text-align: right;" ><?php echo System::display_number([[=rooms.price=]]) ?></td>
        <!--<td text-align="right">[[|total_room_price|]]</td>-->
    </tr>
    <!--/LIST:rooms-->
    </table>
    <table width="100%" class="price">	
    <tr><td  align="right">&nbsp;</td></tr>
    <tr>
    	<td align="right">[[.total_amount.]] :</td>
        <td  align="right" width="30%"><?php echo  System::display_number([[=total_amount=]]);?></td>
        
    </tr>
    <tr>
    	<td align="right">[[.discount_am.]] :</td>
        <td  align="right" width="30%">0</td>
        
    </tr>
    <tr>
        <td align="right">[[.extra_service.]] :</td>
        <td align="right"><?php echo  System::display_number([[=total_extra=]]) ?> </td>
    </tr>
     <tr>
    	<td  align="right">[[.VAT.]] :</td>
        <td  align="right" width="30%"><?php echo System::display_number([[=total_vat=]]);?></td>
    </tr>
    <tr>
    	<td align="right">[[.total_amount_ch.]] :</td>
        <td  align="right" width="30%"><?php echo System::display_number([[=total_amount_1=]]);?></td>
    </tr>
    <tr>
        <td align="right">[[.deposit.]] :</td>
        <td align="right"><?php echo System::display_number([[=total_deposit=]]) ?> </td>
    </tr>
    <tr>
    	<td  align="right"><b>[[.total.]] :</b></td>
        <td  align="right" width="30%"><b id="total_inword1"><?php echo System::display_number([[=total=]]);?></b></td>
        
    </tr>
    <tr>
      <td  style="text-align:right;">&nbsp;</td>
    </tr>
    <tr >
      <td  align="left"><div  ><em id="total_inword">[[.amount_write.]]: </em></div></td>
    </tr>
    <tr >
      <td  style="text-align:left ;"><div align="" ><em>[[.payment_type_name.]]:<?php if(isset([[=payment_type_name=]])){echo [[=payment_type_name=]];}else{ echo '';}?></em></div></td>
    </tr>
    <tr >
      <td colspan="3" style="text-align:left ;"><div  ><em>&nbsp;</em></div></td>
    </tr>
    <tr >
      <td  style="text-align:left ;"><div  ><em>&nbsp;</em></div></td>
    </tr>
    <tr >
      <td style="padding-right: 200px ;"  ><div align="center"><b>[[.singnature_cashier.]]</b></div></td>
      <td   ><div align="center" ><b>[[.singnature_traverler.]]</b></div></td>
    </tr>
    <tr >
      <td  style="text-align:left ;"><div  ><em>&nbsp;</em></div></td>
    </tr>
    <tr >
      <td  style="text-align:left ;"><div  ><em>&nbsp;</em></div></td>
    </tr>
</table>
</div>
<script>
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
                    if(0==r)return t[0] +" đồng";
                    var n="",a="";
                    do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";
                    while(r>0);
                    return n.substring(1,2).toUpperCase()+n.substring(2).trim()+ " đồng"
                    }
                }
}();
jQuery('#total_inword').html('Tổng tiền bằng chữ :'+ConvertNumberToCharacter.show(to_numeric(jQuery("#total_inword1").html().trim())));

</script>