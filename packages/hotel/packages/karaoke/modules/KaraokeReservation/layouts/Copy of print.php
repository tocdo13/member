<div >
<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<table width="450" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="5">&nbsp;</td>
		<td width="99%">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%">
			<tr height="25">
				<td align="center">
					<table cellpadding="0" width="100%" border="0">
					<tr valign="middle">
					  	<td align="center"></td>
					  	<td align="center">
							<img src="<?php echo HOTEL_LOGO;?>">
							<div class="title">HONG NGOC - RESTAURANT</div>
							<div><?php echo HOTEL_ADDRESS;?></div>
							<div>TEL: <?php echo HOTEL_PHONE;?> - FAX: <?php echo HOTEL_FAX;?></div>
						</td>
					  	<td></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" align="left">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
							  	<td align="left"><span style="font-size:11px;font-weight:bold;">No: [[|order_id|]] | [[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span></td>
								<td align="right"><span style="font-size:11px;font-weight:bold;">Thu ng&acirc;n/Cashier: <?php echo Session::get('user_id');?></span></td>
							  </tr>
							  <tr>
							  	<td align="left"><span style="font-size:11px;font-weight:bold;">Li&ecirc;n h&#7879;/Contact: <?php echo HOTEL_PHONE;?></span></td>
								<td align="right"><span style="font-size:11px;font-weight:bold;">T&yacute; gi&aacute;/Exchange rate: [[|exchange|]]</span></td>
							  </tr>
							</table></td>
					  </tr>
					</table>
					<hr size="1" color="#000000">
					<table cellpadding="2" width="100%">
					<tr>
					  <td valign="top" align="left">B&agrave;n s&#7889;/Table No: [[|tables_name|]]</td>
					  </tr>
					<tr>
					  <td valign="top" align="left">T&#234;n kh&#225;ch h&#224;ng/Agent name: [[|agent_name|]]</td>
					</tr>
					</table>
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" rules="rows" frame="hsides">
					  <tr bgcolor="#EFEFEF">
					  	<th width="1%" align="center"  nowrap="nowrap">STT<br />
				  	    No</th>
						<th width="50%" align="center">Di&#7877;n gi&#7843;i<br />
					    Description </th>
						<th width="10%" align="center">&#272;V<br />
					    Unit </th>
					  	<th width="10%" align="center">SL<br />
					  	Q</th>
						<th width="20%" align="center">&#272;&#417;n gi&#225;<br />
					    Price</th>
						<th width="10%" align="center" nowrap="nowrap">Gi&#7843;m(%)<br />
					    Disc(%)</th>
						<th width="25%" align="center" nowrap="nowrap">Th&agrave;nh ti&#7873;n<br />
					    Amount </th>
					  </tr>
                      <?php $i=1;?>
					  <!--LIST:product_items-->
					  <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0)-->
					  <tr valign="top" class="no-border">
					  	<td align="center" class="no-border"><?php echo $i;?></td>
						<td align="left" class="no-border">[[|product_items.product__name|]]</td>
						<td align="center" class="no-border">[[|product_items.product__unit|]]</td>
					  	<td align="center" class="no-border">[[|product_items.product__remain_quantity|]]</td>
						<td align="right" class="no-border">[[|product_items.product__price|]]</td>
						<td align="center" class="no-border">[[|product_items.product__discount|]]</td>
						<td align="right" class="no-border">[[|product_items.product__total|]]</td>
					  </tr>
                      <?php $i++;?>
					   <!--/IF:check_remain_quantity-->
					  <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
					  <tr valign="top">
					  	<td align="center" class="no-border"><?php echo $i;?></td>                      
						<td align="left" class="no-border">[[|product_items.product__name|]] <strong>([[.promotion.]])</strong></td>
                        <td align="center" class="no-border">[[|product_items.product__unit|]]</td>
					  	<td align="center" class="no-border">[[|product_items.product__quantity_discount|]]</td>
						<td align="right" class="no-border">[[|product_items.product__price|]]</td>
						<td align="right" class="no-border">&nbsp;</td>
						<td align="right" class="no-border">&nbsp;</td>
					  </tr>
                      <?php $i++;?>                      
					  <!--/IF:check_discount_quantity-->
					  <!--/LIST:product_items-->
				  </table>
					  <table cellpadding="2" cellspacing="0" width="100%" border="0" style="margin-top:4px;">
					  <tr>
					  	<td width="20%">&nbsp;</td>
						<td width="45%" align="right"><strong>[[.amount.]]/Amount</strong></td>
						<td width="35%" align="right">[[|amount|]]</td>
					  </tr>
					  <!--IF:check_discount(MAP['total_discount']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong>[[.discounted.]]/Discounted  </strong></td>
						<td align="right">[[|total_discount|]]</td>
					  </tr>
					  <!--/IF:check_discount-->
					  <!--IF:check_servicechrg(MAP['karaoke_fee']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right" nowrap="nowrap"><strong> (5%) [[.service_chrg.]]/Service charge </strong></td>
						<td align="right">[[|karaoke_fee|]]</td>
					  </tr>
					  <!--/IF:check_servicechrg-->
					  <!--IF:check_tax(MAP['tax']!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong>([[|tax_rate|]]%) [[.tax_rate.]]/Tax</strong></td>
						<td align="right">[[|tax|]]</td>
					  </tr>
					  <!--/IF:check_tax-->
					  <tr>
						<td colspan="2" align="right" nowrap="nowrap"><strong>[[.sum_total.]]/Grant Total</strong></td>
						<td align="right"><strong>[[|sum_total|]]</strong></td>
					  </tr>
					  	<!--IF:check_prepaid([[=prepaid=]]!='0.00')-->
					  <tr>
					  	<td>&nbsp;</td>
						<td align="right"><strong>[[.prepaid.]]/Deposit</strong></td>
						<td align="right">[[|prepaid|]]</td>
					  </tr>
					  <tr>
						<td nowrap="nowrap" colspan="2" align="right"><strong>[[.remain_paid.]]/ Remain</strong></td>
						<td align="right"><strong>[[|remain_prepaid|]]</strong></td>
					  </tr>
					  <!--/IF:check_prepaid-->
					</table>
					<table width="100%" cellpadding="0">
					<tr>
						<td valign="top" width="100" nowrap="nowrap" style="padding-left: 10px;">&nbsp;</td>
						<td valign="top" width="90%">&nbsp;</td>
					</tr>
					</table>
					<table width="100%" cellpadding="2" cellspacing="0" border="0" style="border-top: 1px solid #000000">
					<tr valign="top" style="padding-top: 4px;">
						<td width="40%" align="center">Ch&#7919; k&#253; kh&#225;ch h&#224;ng<br>Customer Signature </td>
						<td width="20%" align="center">
							<!--IF:check_right(isset([[=room_name=]]) and [[=room_name=]])-->
							S&#7889; ph&#242;ng<br>Room Number 
							<!--ELSE-->
							&nbsp;
							<!--/IF:check_right-->
						</td>
						<td width="40%" align="center" nowrap="nowrap">Thu ng&#226;n<br>
						Cashier</td>
					</tr>
					<tr height="50px">
						<td align="center">&nbsp;</td>
						<td align="center">
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
<script>
	//printWebPart('printer');
</script>