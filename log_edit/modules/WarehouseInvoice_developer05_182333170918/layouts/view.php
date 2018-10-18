<style>

@media print{
    .check_hidden{
        display: none;
    }
}
</style>
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
			[[.day.]]:&nbsp;[[|day|]]/[[|month|]]/[[|year|]]<br />
            <?php 
                if([[=type=]]=='IMPORT' and [[=invoice_number=]]!='')
                echo 'HD: '.[[=invoice_number=]];
            ?>
		</td>
	</tr>
</table>


</div>
<br clear="all"/>

<div style="text-align:left;">
<div style="width:720px;padding:2px 2px 2px 2px;text-align:center;font-size:14px;">
    <div style="padding:2px 2px 2px 2px;">
        <div style="text-indent:0px;vertical-align:top;font-size:16px;text-transform:uppercase;font-weight:bold;">[[|title|]]</div>
        <div>
        	<table width="100%">
                <tr valign="top">
                    <td width="70%" style="font-size:12px;text-align:left">
                        <!--&#272;&#417;n v&#7883;:
                        [[|supplier_name|]]<br/-->
                         <!--ELSE-->
                        [[|warehouse_name|]] <br/>
                        <!--/IF:cond-->
                        <!--Ng&#432;&#7901;i giao: [[|deliver_name|]] <br />
                        Ng&#432;&#7901;i nh&#7853;n: [[|receiver_name|]-->
                    </td>
                    <!--td width="30%" align="right" nowrap="nowrap" style="font-size:12px;">
                        Nh&acirc;n vi&ecirc;n: [[|staff_name|]]<br />
                        <strong>[[|warehouse_name|]]</strong><br />
                    </td-->
                </tr>
                <?php if([[=supplier_name=]]!=''){?>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.supplier.]]: 
                    <em>[[|supplier_name|]]</em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
                <?php }?>
                <?php if([[=wh_receiver_name=]]!=''){?>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.to_deception.]]: 
                    <em>[[|wh_receiver_name|]]</em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
                <?php }?>
        		<tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.description.]]: 
                    <em>[[|note|]]</em> &nbsp; [[|get_back_supplier_name|]]
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
                    <!--<td align="center">E</td>-->
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
                    <!--<td align="left">[[|products.warehouse|]]</td>-->
                    <td align="right" >[[|products.number|]]</td>
                    <td width="150" align="right" class="amount"><?php echo System::display_number(round([[=products.price=]],2));?></td>
                    <!--IF:cond1([[=products.num=]]>0)-->
                        <td width="150" align="right" class="amount"><?php echo System::display_number(round([[=products.payment_amount=]],2));?></td>
                    <!--ELSE-->
                        <td width="150" align="right"  class="amount"><?php echo System::display_number(round([[=products.money_add=]],2));?></td>
                    <!--/IF:cond1-->
                </tr>
                <!--/LIST:products-->
                <?php //for($i=0;$i<=20;$i++){?>
               <!-- <tr>
                    <td>&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                </tr>-->
                <?php 
                /*if($i==1)
                {
                echo '<div style="display:none;page-break-after:always;">';
                }
                } */?> 
                <tr>
            		<td>&nbsp;</td>
            		<td align="center" colspan="5">
                        <p style="text-align: right;margin-right:5px;">[[.total.]]</p>
                        <p style="text-align: right;margin-right:5px;">[[.tax.]]</p>
                        <p style="text-align: right; margin-right:5px;">[[.total_amount.]]</p>
                    </td>
    				<td align="right">
                        <p class="amount"><?php echo System::display_number(round([[=total=]]));?></p>
                        <p class="amount"><?php echo System::display_number(round([[=tax=]]));?></p>
                        <p class="amount"><?php echo System::display_number(round([[=total_amount=]]));?></p>
                    </td>
                </tr>
            </table>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td colspan="5" align="right">
                    [[.amount_in_words.]]:&nbsp;<em><?php
                    require_once 'packages/core/includes/utils/currency.php';
                    echo currency_to_text(round([[=total_amount=]]));
                    ?></em><br />
                </td>
            </tr>
            
            <tr>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td colspan="3" align="right"><em>[[|day|]]/[[|month|]]/[[|year|]]&nbsp;</em></td>
            </tr>
        	<tr>
                <!-- trung :an muc nay
                <td width="20%" align="center">
                    <?php if(Url::get('type')=='IMPORT'){?>[[.shipper.]]<?php }else{?>[[.receiver.]]<?php }?>
                </td>
                -->
                <td align="center" width="40%">
                    [[.accountant_shift.]]
                </td>
                <!-- trung :an muc nay
                <td align="center" width="20%">
                    [[.Chef.]]
                </td>
                -->
                <td width="15%">
                
                </td>
                <td width="45%" align="center">
                    <span style="width:25%;text-align:center;">[[.warehouseman.]]</span>
                </td>
                <!--
                <td width="20%" align="center">
                    <span style="width:25%;text-align:center;">[[.accounting_department.]]<br /></span>
                </td>
                -->                
        	</tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <!--
                <td>
                    <span style="width:100%;text-align:center; display: block;">[[|user_name|]]<br /></span>
                </td>
                -->
            </tr>
        </table>
    </div>
</div>
</div>
<script>
    //Number.prototype.formatMoney = function(c, d, t){
//    var n = this, 
//        c = isNaN(c = Math.abs(c)) ? 2 : c, 
//        d = d == undefined ? "." : d, 
//        t = t == undefined ? "," : t, 
//        s = n < 0 ? "-" : "", 
//        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
//        j = (j = i.length) > 3 ? j % 3 : 0;
//       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
//     };
//        jQuery(".price").each(function(){
//            var str = jQuery(this).html();
//            jQuery(this).html(parseFloat(str).formatMoney(2,',','.'));
//        });
//        jQuery(".amount").each(function(){
//            var str = jQuery(this).html();
//            jQuery(this).html(parseFloat(str).formatMoney(2,',','.'));
//        });
</script>
