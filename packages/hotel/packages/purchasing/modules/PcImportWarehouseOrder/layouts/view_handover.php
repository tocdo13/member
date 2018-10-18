<div style="width:720px;padding:10px;text-align:center;font-size:14px;float:left;">	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<td align="right">
			[[.no1.]]: [[|bill_number|]]<br />
			[[.day.]]:&nbsp;[[|day|]]/[[|month|]]/[[|year|]]
		</td>
	</tr>
</table>


</div>
<br clear="all"/>

<div style="text-align:left;">
<div style="width:720px;padding:2px 2px 2px 2px;text-align:center;font-size:14px;">
    <div style="padding:2px 2px 2px 2px;">
        <div style="text-indent:0px;vertical-align:top;font-size:16px;text-transform:uppercase;font-weight:bold;">[[.bill_handover.]]</div>
        <div>
        	<table width="100%">
                <tr valign="top">
                    <td width="70%" style="font-size:12px;text-align:left">
                        
                        [[.department.]]: [[|department_name|]]  <br/>
                        
                    </td>
            
                </tr>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.supplier_name.]]: 
                    <em>[[|supplier_name|]]</em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.supplier_address.]]: 
                    <em>[[|supplier_address|]]</em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
        		<tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.description.]]: 
                    <em>[[|note|]]</em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
        	</table>
        </div>
        <div style="padding:2px 2px 2px 2px;text-align:left;">&nbsp;</div>
        <div style="text-align:left;">
            <table width="100%" border="1" cellspacing="0" cellpadding="2" style="border-collapse:collapse" bordercolor="#000000">
                <tr>
                    <th width="4%" scope="col">[[.no.]]</th>
                    <th width="25%" align="center" scope="col">[[.product_name.]] <br /></th>
                    <th width="11%" align="center" scope="col">[[.code.]]</th>
                    <th width="10%" scope="col" align="center">[[.unit.]]</th>
                    <!--<th width="15%" align="center" scope="col">Kho</th>-->
                    <th width="10%" scope="col" align="center">[[.quantity.]]  </th>
                    <th width="100" scope="col" align="center">[[.price.]] </th>
                    <th width="160" scope="col" align="center">[[.amount.]] </th>
                </tr>
                <tr>
                    <td align="center">A</td>
                    <td align="center">B</td>
                    <td align="center">C</td>
                    <td align="center">D</td>
                    <td align="center">1</td>
                    <td width="150" align="center">2</td>
                    <td align="right" nowrap="nowrap">3=(1x2)</td>
                </tr>
                <!--LIST:products-->
                <tr>
                    <td align="center">[[|products.i|]]</td>
                    <td align="left" style="padding:0 0 0 10px;">[[|products.name|]]</td>
                    <td align="center" nowrap="nowrap">[[|products.product_id|]]</td>
                    <td align="center">[[|products.unit_name|]]</td>
                    
                    <td align="right">[[|products.number|]]</td>
                    <td width="150" align="right">[[|products.price|]]</td>
                    
                     <td width="150" align="right">[[|products.payment_amount|]]</td>
                    
                </tr>
                <!--/LIST:products-->
                
               
                
                <tr>
            		<td>&nbsp;</td>
            		<td align="center" colspan="5">
                        <p style="text-align: right; margin-right:5px;">[[.total_amount.]]</p>
                    </td>
    				<td align="right">
                        <p id="total_amount"><?php echo [[=total_amount=]]; ?></p>
                    </td>
                </tr>
            </table>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td colspan="4" align="right">
                    [[.amount_in_words.]]:&nbsp;<em id="total_final"></em><br />
                </td>
            </tr>
            
            <tr>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td colspan="2" align="right"><em>[[.day.]] [[|day|]]/[[|month|]]/[[|year|]]&nbsp;</em></td>
            </tr>
        	<tr>
                <td align="center" width="25%">
                    [[.leader.]]
                </td>
                <td width="25%" align="center">
                    [[.shipper.]]
                </td>
                <td width="25%" align="center">
                    <span style="width:25%;text-align:center;">[[.warehouseman.]]</span>
                </td>
                <td width="25%" align="center">
                    <span style="width:25%;text-align:center;">[[.receiver.]]<br /></span>
                </td>
        	</tr>
        </table>
    </div>
</div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#total_final').html(ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").html().trim()))); 
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