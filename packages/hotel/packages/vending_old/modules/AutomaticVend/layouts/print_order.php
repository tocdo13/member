<style>
	#bound{
		font-size:11px;
		font-family:Arial, Helvetica, sans-serif;
	}
</style>
<style type="text/css">
.price {font-size:12px; font-family: Arial, Helvetica, sans-serif}
</style>
<div id="bound" style="width:450px; float:left; border: 1px solid black;" >
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
            <br />
          </p>
        </div>
    </div>
    <div>
    	<span>[[.invoice.]]: #[[|code|]]</span><br />
    	<span>[[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span><br />
        <span>[[.cashier.]]: [[|user_id|]]</span><br />
        <span>[[.guest_name.]]: [[|guest_name|]]</span><br />
    </div>
    <div style="margin-top:5px;">
    	<table width="100%" class="price" style="font-size:11px;">
        	<tr bgcolor="#CCCCCC">
            	<th  align="center" width="15">[[.quantity.]]</th>
                <th align="left">[[.name.]]</th>
                <th align="right">[[.price.]]<?php if([[=full_rate=]]==1){echo '<span title="Rates include taxes and charges">(*)</span>';}else if([[=full_charge=]]==1){echo '<span title="Rates include charges">(*)</span>';}?></th>
                <th style="width: 30px;" align="right">&nbsp;[[.discount_l.]]&nbsp;<br />(%)</th>
                <th style="width: 30px;" align="right">&nbsp;[[.promotion_l.]]&nbsp;<br />(%)</th>
                <th align="center">&nbsp;FOC&nbsp;</th>
                <th style="width: 90px;" align="right">[[.amount.]]</th>
            </tr>
            <!--LIST:product_items-->
            <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0 && MAP['product_items']['current']['cancel_all']==0)-->
            <tr>
            	<td align="center">[[|product_items.product__remain_quantity|]]</td>
                <td align="left">[[|product_items.product__name|]]</td>
                <td align="right">[[|product_items.product__price|]]</td>
                <td align="right">[[|product_items.discount_rate|]]</td>
                <td align="right">[[|product_items.promotion|]]</td>
                <td align="center">N</td>
                <td align="right"><?php echo ([[=product_items.product__total=]]);?></td>
            </tr>
            <!--/IF:check_remain_quantity-->
            <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
            <tr>
            	<td align="center">[[|product_items.product__quantity_discount|]]</td>
                <td align="left">[[|product_items.product__name|]]</td>
                <td align="right">[[|product_items.product__price|]]</td>
                <td align="right">0</td>
                <td align="right">0</td>
                <td align="center">Y</td>
                <td align="right">0.00</td>
            </tr>
            <!--/IF:check_discount_quantity-->
            <!--/LIST:product_items-->
            <tr><td colspan="7" align="center"><hr /></td></tr>
            <!--IF:cond_total([[=tax_amount=]]>0 || [[=service_amount=]]>0 || [[=total_discount=]]>0 || [[=deposit=]] > 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[.total_amount.]]</td>
                <td colspan="4" align="right"><?php echo [[=total_amount=]];?></td>
            </tr>
            <!--IF:check_discount(MAP['total_discount']!='0.00')-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[.total_discount.]] (%)</td>
                <td colspan="4" align="right"><?php echo System::display_number(System::calculate_number([[=total_discount=]]));?></td>
            </tr>
            <!--/IF:check_discount-->
            <!--IF:cond_service([[=bar_fee_rate=]]>0 && [[=full_charge=]] == 0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[|bar_fee_rate|]]% &nbsp;[[.service.]]</td>
                <td colspan="4" align="right"><?php echo System::display_number([[=service_amount=]]);?></td>
            </tr>
            <!--/IF:cond_service-->
            <!--IF:cond_tax([[=tax_rate=]]>0 && [[=full_rate=]] == 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;">[[|tax_rate|]]% &nbsp;[[.tax.]]</td>
                <td colspan="4" align="right"><?php echo System::display_number([[=tax_amount=]]);?></td>
            </tr>
             <!--/IF:cond_tax-->
             <!--IF:check_deposit(MAP['deposit']!='0.00')-->
              <tr>
                <td colspan="3" style="text-transform:uppercase;">[[.deposit.]]:</td>
                <td colspan="4" align="right">[[|deposit|]]</td>
              </tr>
              <!--/IF:check_deposit-->
             <tr style="font-weight:bold;">
            	<td colspan="3" style="text-transform:uppercase;">[[.total_payment.]]</td>
                <td colspan="4" align="right">[[|sum_total|]]</td>
            </tr>
            <tr>
            	<td colspan="7" style="text-align:right; font-size:11px;"><i>
						<?php
                        if([[=tax_rate=]]>0 && [[=full_rate=]] == 1){
                            echo '(*) '.Portal::language('rates_include_taxes').' (10%)';	
                        }else if([[=bar_fee_rate=]]>0 && [[=full_charge=]]== 1){
                            echo '(*) '.Portal::language('rates_include_charges');	          
                        } ?></i>                </td>
            </tr>
            <tr>
              <td colspan="7" style="text-align:right; font-size:11px;">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="7" style="text-align:right; font-size:11px;"><div align="center"><em>&nbsp;</em></div></td>
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