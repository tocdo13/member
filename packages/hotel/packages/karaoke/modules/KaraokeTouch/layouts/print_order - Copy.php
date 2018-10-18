<style>
	#bound{
		font-size:13px;
	}
</style>
<div id="bound" style="width:320px; float:left;">
	<div>
    	<span style="float:left;">Table No: [[|tables_name|]]</span><br /><br />
        <div style=" text-align:center; font-size:13px;">
        <span class="title_invoice" style="text-transform:uppercase; font-weight:bold;">
        <?php if(Url::get(md5('preview'))){
        		echo Portal::language('provisional_invoice');
			}else{
				echo Portal::language('invoice');	
			}
        ?></span><br />
        <span class="title_invoice"><?php echo HOTEL_ADDRESS;?></span> <br />
        <span class="title_invoice">[[.tel.]]: <?php echo HOTEL_PHONE;?></span><br /><br />
        </div>
    </div>
    <div>
    	<span>[[.invoice.]]: #[[|order_id|]]</span><br />
    	<span>[[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span><br />
        <span>[[.cashier.]]: <?php $user_data = Session::get('user_data'); echo $user_data['full_name'];?></span><br />
    </div>
    <div style="margin-top:5px;">
    	<table width="100%">
        	<tr bgcolor="#CCCCCC">
            	<td width="15">[[.quantity.]]</td>
                <td>[[.name.]]</td>
                <td align="right">[[.price.]]<?php if([[=full_rate=]]==1){echo '<span title="Rates include taxes and charges">(*)</span>';}else if([[=full_charge=]]==1){echo '<span title="Rates include charges">(*)</span>';}?></td>
                <td align="right">[[.amount.]]</td>
            </tr>
            <!--LIST:product_items-->
            <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0 && MAP['product_items']['current']['cancel_all']==0)-->
            <tr>
            	<td>[[|product_items.product__remain_quantity|]]</td>
                <td>[[|product_items.product__name|]]</td>
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
            <tr><td colspan="4" align="center">--------------------------------------------------------------------------------</td></tr>
            <!--IF:cond_total((([[=karaoke_fee_rate=]]>0 && [[=full_charge=]]==0)) && ([[=tax_rate=]]>0 && [[=full_rate=]] == 0))-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[.subtotal.]]</td>
                <td colspan="2" align="right">[[|amount|]]</td>
            </tr>
            <!--/IF:cond_total-->
             <!--IF:cond_total(([[=karaoke_fee_rate=]]>0 && [[=full_charge=]]==1) && ([[=tax_rate=]]>0 && [[=full_rate=]] == 0))-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[.subtotal.]]</td>
                <td colspan="2" align="right"><?php echo System::display_number(System::calculate_number([[=amount=]]) + System::calculate_number([[=karaoke_fee=]]) );?></td>
            </tr>
            <!--/IF:cond_total-->
            <!--IF:check_discount(MAP['total_discount']!='0.00' || MAP['order_discount']!='0.00')-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[.total_discount.]]</td>
                <td colspan="2" align="right"><?php echo System::display_number(System::calculate_number([[=total_discount=]])+System::calculate_number([[=order_discount=]]));?></td>
            </tr>
            <!--/IF:check_discount-->
            <!--IF:cond_service([[=karaoke_fee_rate=]]>0 && [[=full_charge=]] == 0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[|karaoke_fee_rate|]]% &nbsp;[[.service.]]</td>
                <td colspan="2" align="right">[[|karaoke_fee|]]</td>
            </tr>
            <!--/IF:cond_service-->
            <!--IF:cond_tax([[=tax_rate=]]>0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="2" style="text-transform:uppercase;">[[|tax_rate|]]% &nbsp;[[.tax.]]</td>
                <td colspan="2" align="right">[[|tax|]]</td>
            </tr>
             <!--/IF:cond_tax-->
             <!--IF:check_deposit(MAP['deposit']!='0.00')-->
              <tr>
                <td colspan="2" style="text-transform:uppercase;">[[.deposit.]]:</td>
                <td colspan="2" align="right">[[|deposit|]]</td>
              </tr>
              <!--/IF:check_deposit-->
             <tr style="font-weight:bold;">
            	<td colspan="2" style="text-transform:uppercase;">[[.grand_total.]]</td>
                <td colspan="2" align="right">[[|sum_total|]]</td>
            </tr>
            <tr>
            	<td colspan="4" style="text-align:right; font-size:11px;"><i>
						<?php
                        if([[=tax_rate=]]>0 && [[=full_rate=]] == 1){
                            echo '(*) '.Portal::language('rates_include_taxes_and_charges');	
                        }else if([[=karaoke_fee_rate=]]>0 && [[=full_charge=]]== 1){
                            echo '(*) '.Portal::language('rates_include_charges');	          
                        } ?></i>
                </td>
            </tr>
        </table>
    </div>
</div>