<script>full_screen();</script>
<div style="padding:5px;padding-left:25px;"><a style="text-decoration:underline;font-weight:bold;" href="<?php echo Url::build_current();?>">[[.back_to_shop_invoice_list.]]</a></div>
<div>
<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<table width="450" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="5%"></td>
		<td width="95%">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25">
				<td>
					<table cellpadding="0" width="100%" border="0">
					<tr>
					  <td colspan="2" align="center">
						<p style=" padding-bottom:10px;">
						  	<span style="font-size: 16px; font-weight:bold;">PHI&#7870;U THANH TO&Aacute;N / PROFOMA INVOICE </span>
						</p>
					  </td>
					</tr>
					<tr>
						<td align="left">[[.Date.]]: [[|date|]]</td>
						<td align="right" valign="bottom" nowrap="nowrap">
							<font style="font-size: 14px">No: [[|order_id|]]</font>
						</td>
					</tr>
					</table>
					<hr size="1" color="#000000">
					<table cellpadding="0" width="100%" height="40px">
					<tr>
					  <td width="50%" valign="top" align="left">[[.customer_name.]]:</td>
					  <td width="50%" style="padding-left:10px;" valign="top" align="right">[[|agent_name|]]</td>
					</tr>
					<tr>
					  <td width="50%" valign="top" align="left">[[.customer_address.]]:</td>
					  <td width="50%" style="padding-left:10px;" valign="top" align="right">[[|agent_address|]]</td>
					</tr>
					</table>
					<div><strong>[[.gift_list.]]</strong></div>
					<hr size="1" color="#000000">
					<table width="100%" cellpadding="2" cellspacing="0">
					  <tr>
					  	<td width="15%" align="center">S&#7889; l&#432;&#7907;ng<br />Quantity</td>
						<td width="15%" align="center">&#272;&#417;n v&#7883; <br />Unit</td>
						<td width="45%" align="left">Di&#7877;n gi&#7843;i<br />Description </td>
						<td width="25%" align="right" nowrap="nowrap">S&#7889; ti&#7873;n<br />Amount </td>
					  </tr>
					  <tr>
					  	<td colspan="4"><hr size="1" color="#000000"></td>
					  </tr>
					  <!--LIST:product_items-->
					  <!--IF:check_remain_quantity(MAP['product_items']['current']['product__quantity_remain']!=0)-->
					  <tr>
					  	<td style="border-bottom: 1px solid #CCCCCC" align="center">[[|product_items.product__quantity_remain|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="center">[[|product_items.product__unit|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC">[[|product_items.product__name|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="right">[[|product_items.product__total|]]</td>
					  </tr>
					   <!--/IF:check_remain_quantity-->
					  <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
					  <tr>
					  	<td style="border-bottom: 1px solid #CCCCCC" align="center">[[|product_items.product__quantity_discount|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="center">[[|product_items.product__unit|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC">[[|product_items.product__name|]] ([[.promotion.]])</td>
						<td style="border-bottom: 1px solid #CCCCCC" align="right">0.00</td>
					  </tr>
					  <!--/IF:check_discount_quantity-->
					  <!--/LIST:product_items-->
					  </table>
					  <table cellpadding="2" cellspacing="0" width="100%" border="0" style="margin-top:4px;">
					  <tr>
					  	<td width="20%">&nbsp;</td>
						<td width="45%" align="right"><strong><em>[[.sumary.]]/Amount</em></strong></td>
						<td width="35%" align="right">[[|sumary|]]</td>
					  </tr>
					  <!--IF:check_discount(MAP['total_discount']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><em><strong>[[.discounted.]]/Discounted  </strong></em></td>
						<td align="right">[[|total_discount|]]</td>
					  </tr>
                      <!--/IF:check_discount-->
					  <!--IF:check_tax(MAP['tax']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong><em>([[|tax_rate|]]%) [[.tax_rate.]]/Tax</em></strong></td>
						<td align="right">[[|tax|]]</td>
					  </tr>
					  <!--/IF:check_tax-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong><em>[[.sum_total.]]/Amount</em></strong></td>
						<td align="right"><?php echo HOTEL_CURRENCY?> [[|sum_total|]]</td>
					  </tr>
					</table>
					<br />
					<table width="100%" cellpadding="0">
					<tr>
						<td valign="top" width="100" nowrap="nowrap" style="padding-left: 10px;">&nbsp;</td>
						<td valign="top" width="90%">&nbsp;</td>
					</tr>
					<!--IF:check_tax(MAP['tax']=='0.00')-->
					<tr>
						<td valign="top" colspan="2" style="padding-left: 10px;">[[.tax_alert.]]</td>
					</tr>
					<!--/IF:check_tax-->
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #000000">
					<tr valign="top" style="padding-top: 4px;">
						<td width="40%" align="center" style="border-right: 1px solid #000000;"><em>Ch&#7919; k&#253; kh&#225;ch h&#224;ng<br>Customer Signature </em></td>
						<td width="20%" align="center" style="border-right: 1px solid #000000;">
							<!--IF:check_right(defined('HOTEL_STAFF'))-->
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
							<!--IF:check_right(defined('HOTEL_STAFF'))-->
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