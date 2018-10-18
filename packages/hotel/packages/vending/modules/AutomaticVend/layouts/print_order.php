<style>
	#bound{
		font-size:11px;
		font-family:Arial, Helvetica, sans-serif;
	}
    @media print{
        #printer_logo{
            display: none;
        }
    }
</style>
<style type="text/css">
.price {font-size:12px; font-family: Arial, Helvetica, sans-serif}
</style>
<div id="printer_logo" style="display: none;">
<a onclick="var content = jQuery('#printer').html();printWebPart_Dau(content);printed_tmp_bill();" title="Print" style="float: right;"><img src="packages/core/skins/default/images/printer.png" height="40"></a>
</div>
<div id="bound" style="width:280px; float:left; border: 1px solid black;" >
	<div>
        <div style=" text-align:center; font-size:10px;">
          <p><span class="title_invoice" style="text-transform:uppercase; font-weight:bold;">
          <?php if(Url::get(md5('preview')))
            {
        		echo Portal::language('invoice');
    		}
            else
            {
    			echo Portal::language('invoice');	
    		}
        ?>
            </span><br />
            <span class="title_invoice"><?php echo HOTEL_ADDRESS;?></span> <br />
            <span class="title_invoice">[[.tel.]]: <?php echo HOTEL_PHONE;?></span><br />
            <span class="title_invoice">[[.website.]]: <?php echo HOTEL_WEBSITE;?></span><br />
            <span class="title_invoice">[[.Email.]]: <?php echo HOTEL_EMAIL;?></span><br />
            <br />
          </p>
        </div>
    </div>
    <div>
    	<span>[[.invoice.]]: #[[|code|]]</span><br />
    	<span>[[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span><br />
        <span>[[.cashier.]]: [[|user_id|]]</span><br />
        <span>[[.person_order.]]: [[|person_order|]]</span><br />
        <span>[[.number_guest.]]: [[|number_guest|]]</span><br />
        <span>[[.guest_name.]]: [[|guest_name|]]</span><br />
        <span>[[.device_code.]]: [[|device_code|]]</span><br />
        <span>[[.guest_phone_number.]]: [[|guest_phone_number|]]</span><br />
    </div>
    <div style="margin-top:5px;">
    	<table width="100%" class="price" style="font-size:11px;">
        	<tr bgcolor="#CCCCCC">
                <th align="left">[[.name.]]</th>
                <th  align="center" width="15">[[.quantity.]]</th>
                <th align="right">[[.price.]]<?php if([[=full_rate=]]==1){echo '<span title="Rates include taxes and charges">(*)</span>';}else if([[=full_charge=]]==1){echo '<span title="Rates include charges">(*)</span>';}?></th>
                <!--<th style="width: 30px;" align="right">&nbsp;[[.discount_l.]]&nbsp;<br />(%)</th>
                <th style="width: 30px;" align="right">&nbsp;[[.promotion_l.]]&nbsp;<br />(%)</th>
                <th align="center">&nbsp;FOC&nbsp;</th>-->
                <th align="right">[[.amount.]]</th>
            </tr>
            <!--LIST:product_items-->
            <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0 && MAP['product_items']['current']['cancel_all']==0)-->
            <tr>
            	
                <td align="left">[[|product_items.product__name|]]</td>
                <td align="center">[[|product_items.product__remain_quantity|]]</td>
                <td align="right">[[|product_items.product__price|]]</td>
                <!--<td align="right">[[|product_items.discount_rate|]]</td>
                <td align="right">[[|product_items.promotion|]]</td>
                <td align="center">N</td>-->
                <td align="right"><?php echo ([[=product_items.product__total=]]);?></td>
            </tr>
            <!--/IF:check_remain_quantity-->
            <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
            <tr>
            	
                <td align="left">[[|product_items.product__name|]]</td>
                <td align="center">[[|product_items.product__quantity_discount|]]</td>
                <td align="right">[[|product_items.product__price|]]</td>
                <!--<td align="right">0</td>
                <td align="right">0</td>
                <td align="center">Y</td>-->
                <td align="right">0.00</td>
            </tr>
            <!--/IF:check_discount_quantity-->
            <!--/LIST:product_items-->
            <tr><td colspan="4" align="center"><hr /></td></tr>
            <!--IF:cond_total([[=tax_amount=]]>0 || [[=service_amount=]]>0 || [[=total_discount=]]>0 || [[=deposit=]] > 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[.total_amount.]]</td>
                <td align="right"><?php echo [[=total_amount=]];?></td>
            </tr>
            <!--IF:check_discount(MAP['total_discount']!='0.00')-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[.total_discount.]] (%)</td>
                <td align="right"><?php echo System::display_number(System::calculate_number([[=total_discount=]]));?></td>
            </tr>
            <!--/IF:check_discount-->
            <!--IF:cond_service([[=bar_fee_rate=]]>0 && [[=full_charge=]] == 0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[|bar_fee_rate|]]% &nbsp;[[.service.]]</td>
                <td align="right"><?php echo System::display_number([[=service_amount=]]);?></td>
            </tr>
            <!--/IF:cond_service-->
            <!--IF:cond_tax([[=tax_rate=]]>0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[|tax_rate|]]% &nbsp;[[.tax.]]</td>
                <td align="right"><?php echo System::display_number([[=tax_amount=]]);?></td>
            </tr>
             <!--/IF:cond_tax-->
             <!--IF:check_deposit(MAP['deposit']!='0.00')-->
              <tr>
                <td colspan="3" style="text-transform:uppercase;">[[.deposit.]]:</td>
                <td align="right">[[|deposit|]]</td>
              </tr>
              <!--/IF:check_deposit-->
             <tr style="font-weight:bold;">
            	<td colspan="3" style="text-transform:uppercase;">[[.total_payment.]]</td>
                <td align="right">[[|sum_total|]]</td>
            </tr>
            <tr>
            	<td colspan="4" style="text-align:right; font-size:11px;"><i>
						<?php
                        if([[=tax_rate=]]>0 && [[=full_rate=]] == 1){
                            echo '(*) '.Portal::language('rates_include_taxes').' (10%)';	
                        }else if([[=bar_fee_rate=]]>0 && [[=full_charge=]]== 1){
                            echo '(*) '.Portal::language('rates_include_charges');	          
                        } ?></i>                </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; font-size:11px;">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; font-size:11px;"><div align="center"><em>&nbsp;</em></div></td>
            </tr>
        </table>
        
        <?php if(sizeof([[=payment_list=]])>0){ ?>
        <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" style="font-weight: bold; margin-top: 3px;">
            <tr>
                <th colspan="2" style="font-size: 15px;">[[.payment.]]</th>
            </tr>
           
            <?php foreach($this->map['payment_list'] as $k => $v)
            {
            ?>
            <tr>
                <td style="font-size: 15px;"><?php echo $v['payment_type_name'] ?></td>
                <td style="font-size: 15px;"><?php echo System::display_number($v['amount']); ?><?php if($v['currency_id']) echo $v['currency_id'] ?></td>
            </tr>
            <?php
            }
            ?>  
            
        </table>
        <?php } ?>
    </div>
</div>
<script>
function printWebPart_Dau(content)
{
	if(content)
	{
		var html = "";
		html = "<html><head>"+
		"<link rel=\"stylesheet\" type=\"text/css\" href=\"packages/core/skins/default/css/global.css\" media=\"print\" ></link>"+
        "<style type=\"text/css\">"+
        "*{ font-family: sans-serif, Arial, Tahoma; }"+
        "</style>"+
		"</head><body >"+
		content+
		"</body></html>";
		width = jQuery(document).width();
		height = jQuery(document).height();
		html = html.replace('packages/core/includes/js/common.js','');
		var printWP = window.open("about:blank","printWebPart_Dau");
		printWP.document.open();
		printWP.document.write(html);
        //printWP.document.body.style.zoom= "40%";
		printWP.print();
		printWP.document.close();
		printWP.close();
	}
}
var printt = '<?php echo (Url::get('act')?Url::get('act'):'');?>';
if(printt == 1){
    var content = jQuery("#printer").html();	
    window.close();
    printWebPart_Dau(content);
}
</script>