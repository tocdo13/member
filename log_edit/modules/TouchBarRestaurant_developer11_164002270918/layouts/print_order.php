<style>
	#bound{
		font-size:11px;
		font-family:Arial, Helvetica, sans-serif;
	}
    .des{display:none;}
</style>
<style type="text/css">
.price {font-size:12px; font-family: Arial, Helvetica, sans-serif}
</style>
<div id="bound" style="width:250px; float:left;">
	<div>
    	<span style="float:left;">Table No: [[|tables_name|]]</span><br /><br />
        <div style=" text-align:center; font-size:10px;">
          <p><span class="title_invoice" style="text-transform:uppercase; font-weight:bold;">
          <?php if(Url::get(md5('preview'))){
        		echo Portal::language('provisional_invoice');
			}else{
				echo Portal::language('invoice');	
			}
        ?>
            </span><br />
            <span class="title_invoice"><?php echo HOTEL_ADDRESS;?></span> <br />
            <span class="title_invoice">[[.tel.]]: <?php echo HOTEL_PHONE;?></span><br />
            <br />
          </p>
        </div>
    </div>
    <div>
    	<span>[[.invoice.]]: #[[|code|]]</span><br />
    	<span>[[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span><br />
        <span>[[.cashier.]]: [[|user_name|]]</span><br />
    </div>
    <div style="margin-top:5px;">
    	<table width="100%" class="price">
        	<tr bgcolor="#CCCCCC">
            	<td  align="center" width="15">[[.quantity.]]</td>
                <td align="center">[[.name.]]</td>
                <td align="center">[[.price.]]<?php if([[=full_rate=]]==1){echo '<span title="Rates include taxes and charges">(*)</span>';}else if([[=full_charge=]]==1){echo '<span title="Rates include charges">(*)</span>';}?></td>
                <td align="center">[[.amount.]]</td>
            </tr>
            <!--LIST:product_items-->
            <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0 && MAP['product_items']['current']['cancel_all']==0)-->
            <tr>
            	<td align="center">[[|product_items.product__remain_quantity|]]</td>
                <td align="left">[[|product_items.product__name|]]</td>
                <td align="right">[[|product_items.product__price|]]</td>
                <td align="right"><?php echo System::display_number([[=product_items.product__quantity=]] *System::calculate_number([[=product_items.product__price=]]));?></td>
            </tr>
            <!--/IF:check_remain_quantity-->
            <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
            <tr>
            	<td>[[|product_items.product__quantity_discount|]]</td>
                <td>[[|product_items.product__name|]] <strong>([[.promotion.]])</strong></td>
                <td align="right">[[|product_items.product__price|]]</td>
                <td align="right">0</td>
            </tr>
            <!--/IF:check_discount_quantity-->
            <!--/LIST:product_items-->
            <tr><td colspan="4" align="center">------------------------------------------------------------</td></tr>
            <!--IF:cond_total([[=tax_amount=]]>0 || [[=service_amount=]]>0 || [[=total_discount=]]>0 || [[=deposit=]] > 0)-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[.total_amount.]]</td>
                <td colspan="2" align="right"><?php echo [[=total_amount=]];?></td>
            </tr>
            <!--IF:check_discount(MAP['total_discount']!='0.00')-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[.total_discount.]]</td>
                <td colspan="2" align="right"><?php echo System::display_number(System::calculate_number([[=total_discount=]]));?></td>
            </tr>
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[.total_after_discount.]]</td>
                <td colspan="2" align="right"><?php echo System::display_number(System::calculate_number([[=total_after_discount=]]));?></td>
            </tr>			
            <!--/IF:check_discount-->
            <!--IF:cond_service([[=bar_fee_rate=]]>0 && [[=full_charge=]] == 0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[|bar_fee_rate|]]% &nbsp;[[.service.]]</td>
                <td colspan="2" align="right"><?php echo System::display_number([[=service_amount=]]);?></td>
            </tr>
            <!--/IF:cond_service-->
            <!--IF:cond_tax([[=tax_rate=]]>0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[|tax_rate|]]% &nbsp;[[.tax.]]</td>
                <td colspan="2" align="right"><?php echo System::display_number([[=tax_amount=]]);?></td>
            </tr>
             <!--/IF:cond_tax-->
              <tr>
                <td colspan="2" style="text-transform:uppercase;">[[.total.]]:</td>
                <td colspan="2" align="right">[[|total_before_deposit|]]</td>
              </tr>
             <!--IF:check_deposit(MAP['deposit']!='0.00')-->
			 <tr>
                <td colspan="2" style="text-transform:uppercase;">[[.deposit.]]:</td>
                <td colspan="2" align="right">[[|deposit|]]</td>
              </tr>
             <tr style="font-weight:bold;">
            	<td colspan="2" style="text-transform:uppercase;"><!--IF:cond_sum(System::calculate_number([[=sum_total=]])>0)-->[[.must_payment.]]<!--ELSE-->[[.return_cash.]]<!--/IF:cond_sum--></td>
                <td colspan="2" align="right">[[|sum_total|]]</td>
            </tr>
              <!--/IF:check_deposit-->			
            <tr>
            	<td colspan="4" style="text-align:right; font-size:11px;"><i>
						<?php
                        if([[=tax_rate=]]>0 && [[=full_rate=]] == 1){
                            echo '(*) '.Portal::language('rates_include_taxes_and_charges');	
                        }else if([[=bar_fee_rate=]]>0 && [[=full_charge=]]== 1){
                            echo '(*) '.Portal::language('rates_include_charges');	          
                        } ?></i>                </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; font-size:11px;">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; font-size:11px;"><div align="center"><em>Thank you  </em></div></td>
            </tr>
        </table>
    </div>
</div>
<div style=" float: right; padding-right: 30px;">
<input style="width: 70px; height: 50px;"  type="button" name="print" id="print" value="Print" onclick="window.close();printWebPart('printer');"/>
<input style="width: 70px; height: 50px;"  type="reset" name="reset" id="exit" value="Thoát" onclick="window.close();"/>
</div>
<style type="text/css">
    @media print
    {
        #exit{display:none}
        #print{display:none}
    }
</style>