<script>full_screen();</script>
<div >
<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<table width="450" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="5%"></td>
		<td width="95%">
			<table cellSpacing=0 cellPadding=5 border=1 width="100%" style="border-collapse:collapse;margin-top:3px" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center">
					<table cellpadding="0" width="100%" border="0">
					<tr>
					  <td colspan="2" align="center">
						<p style=" padding-bottom:10px;">
						  	<span style="font-size: 16px; font-weight:bold;">PHI&#7870;U THANH TO&Aacute;N / PROFOMA INVOICE </span>
						</p>
					  </td>
					</tr>
					<tr>
						<td align="left">
							<div id="main_div_class">
								<div id="sub_div_class" style="width:130px;float:left;">Gi&#7901; b&#7855;t &#273;&#7847;u/Time in: </div>
								<div id="sub_div_class">[[|time_begin|]] </div>
							</div>
							<div id="main_div_class">
								<div id="sub_div_class" style="width:130px;float:left;">Gi&#7901; k&#7871;t th&#250;c/Time out: </div>
								<div id="sub_div_class">[[|time_end|]] </div>
							</div>
						</td>
						<td align="right" valign="bottom" nowrap="nowrap">
							<font style="font-size: 14px">No: [[|order_id|]]</font>
						</td>
					</tr>
					</table>
					<hr size="1" color="#000000">
					<table cellpadding="0" width="100%" height="40px">
					<tr>
					  <td width="50%" valign="top" align="left">
						  <!--D&#7883;ch v&#7909;/Service:<br />-->
						  B&agrave;n s&#7889;/Table No:<br />
						  T&#234;n kh&#225;ch h&#224;ng/Agent name:<br />                          
						  Nh&acirc;n vi&ecirc;n ph&#7909;c v&#7909;/Server:
					  </td>
					  <td width="50%" style="padding-left:10px;" valign="top" align="right">
						  <!--[[|bar_name|]]<br />-->
						  [[|tables_name|]]<br />
						  [[|agent_name|]]<br />                          
						  [[|server_id|]]
					  </td>
					</tr>
					</table>
					<hr size="1" color="#000000">
					<table width="100%" cellpadding="0" cellspacing="0">
					  <tr>
					  	<td width="15%" align="center">S&#7889; l&#432;&#7907;ng<br />Quantity</td>
						<td width="25%" align="center">&#272;&#417;n gi&#225; <br />Price</td>
						<td width="40%" align="center">Di&#7877;n gi&#7843;i<br />Description </td>
						<td width="20%" align="center" nowrap="nowrap">S&#7889; ti&#7873;n<br />Amount </td>
					  </tr>
					  <tr>
					  	<td colspan="4"><hr size="1" color="#000000"></td>
					  </tr>
					  <!--LIST:product_items-->
					  <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0)-->
					  <tr>
					  	<td style="border-bottom: 1px solid #CCCCCC" align="center">[[|product_items.product__remain_quantity|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="center">$[[|product_items.product__price|]]/[[|product_items.product__unit|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC; text-align:left">[[|product_items.product__name|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="right">[[|product_items.product__total|]]</td>
					  </tr>
					   <!--/IF:check_remain_quantity-->
					  <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
					  <tr>
					  	<td style="border-bottom: 1px solid #CCCCCC" align="center">[[|product_items.product__quantity_discount|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="center">$[[|product_items.product__price|]]/[[|product_items.product__unit|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC;" align="center">[[|product_items.product__name|]] ([[.promotion.]])</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="right">0.00</td>
					  </tr>
					  <!--/IF:check_discount_quantity-->
					  <!--/LIST:product_items-->
					  </table>
					  <table cellpadding="2" cellspacing="0" width="100%" border="0" style="margin-top:4px;">
					  <tr>
					  	<td width="20%">&nbsp;</td>
						<td width="45%" align="right"><strong><em>[[.sumary.]]/Amount</em></strong></td>
						<td width="35%" align="right">[[|amount|]]</td>
					  </tr>
					  <!--IF:check_discount(MAP['total_discount']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><em><strong>[[.discounted.]]/Discounted  </strong></em></td>
						<td align="right">[[|total_discount|]]</td>
					  </tr>
					  <!--/IF:check_discount-->
					  <!--IF:check_servicechrg(MAP['bar_fee']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right" nowrap="nowrap"><strong><em> (5%) [[.service_chrg.]]/Service charge </em></strong></td>
						<td align="right">[[|bar_fee|]]</td>
					  </tr>
					  <!--/IF:check_servicechrg-->
					  <!--IF:check_tax(MAP['tax']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong><em>([[|tax_rate|]]%) [[.tax_rate.]]/Tax</em></strong></td>
						<td align="right">[[|tax|]]</td>
					  </tr>
					  <!--/IF:check_tax-->
					  	<!--IF:check_prepaid([[=prepaid=]]=='0.00')-->
					  <tr>
						<td colspan="2" align="right" nowrap="nowrap"><strong><em>[[.sum_total.]]/Grant Total</em></strong></td>
						<td align="right"><em><strong><?php echo HOTEL_CURRENCY?> [[|sum_total|]]</strong></em></td>
					  </tr>
					  	<!--ELSE-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong><em>[[.prepaid.]]/Deposit</em></strong></td>
						<td align="right"><?php echo HOTEL_CURRENCY;?> [[|prepaid|]]</td>
					  </tr>
					  <tr>
						<td nowrap="nowrap" colspan="2" align="right"><strong><em>[[.remain_paid.]]/ Remain </em></strong></td>
						<td align="right">
							<em><strong><?php echo HOTEL_CURRENCY;?> [[|remain_prepaid|]]</strong></em>
						</td>
					  </tr>
					  <!--/IF:check_prepaid-->
					</table>
                    <div align="right" style="margin-top:5px;">
                    <div style="margin-bottom:3px;">[[.type_of_currency.]]</div>
                    <table cellpadding="3" cellspacing="0" width="200px" border="1" bordercolor="#CCCCCC">
                    	<!--LIST:payment_detail-->
                    	<tr>
                        	<td align="right" width="50px" bgcolor="#FFFFCC">[[|payment_detail.currency_id|]]:</td>
                            <td align="right" nowrap="nowrap"><?php echo System::display_number_report([[=payment_detail.amount=]])?></td>
                        </tr>
                        <!--/LIST:payment_detail-->
                    	<tr>
                        	<td align="right" width="50px" bgcolor="#FFFFCC"><?php echo HOTEL_CURRENCY;?>:</td>
                            <td align="right" nowrap="nowrap"><?php echo System::display_number_report([[=pay_by_usd=]])?></td>
                        </tr>
                    </table>
                    </div>
					<br />
					<table width="100%" cellpadding="0">
					<tr>
						<td valign="top" width="100" nowrap="nowrap" style="padding-left: 10px;">&nbsp;</td>
						<td valign="top" width="90%">&nbsp;</td>
					</tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #000000">
					<tr valign="top" style="padding-top: 4px;">
						<td width="40%" align="center" style="border-right: 1px solid #000000;"><em>Ch&#7919; k&#253; kh&#225;ch h&#224;ng<br>Customer Signature </em></td>
						<td width="20%" align="center" style="border-right: 1px solid #000000;">
							<!--IF:check_right(isset([[=room_name=]]) and [[=room_name=]])-->
							<em>S&#7889; ph&#242;ng<br>Room Number </em>
							<!--ELSE-->
							&nbsp;
							<!--/IF:check_right-->
						</td>
						<td width="40%" align="center" nowrap="nowrap"><em>Thu ng&#226;n<br></em></td>
					</tr>
					<tr height="50px">
						<td align="center" style="border-right: 1px solid #000000;">&nbsp;</td>
						<td align="center" style="border-right: 1px solid #000000;">
							<!--IF:check_right(isset([[=room_name=]]) and [[=room_name=]])-->
							[[|room_name|]]
							<!--ELSE-->
							&nbsp;
							<!--/IF:check_right-->
						</td>
						<td align="center">&nbsp;</td>
					</tr>
					</table>
			    </td> 
			</tr> 
			</table>
		</td>
	</tr>
</table>
</div>