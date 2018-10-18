<script>
function recalculate_housekeeping_invoice_detail()
{
	var total=0;	
	for(var i=1;i<=[[|num_product|]];i++)
	{
		var amount = to_numeric(getElemValue('price_'+i))*to_numeric(getElemValue('quantity_'+i));
		$('amount_'+i).innerText=number_format(roundNumber(amount,2));
		total+=amount;
	}
	$total_discount = roundNumber(total*getElemValue('discount')/100,2);
	total = total - $total_discount;
	
	$('total_before_tax').innerHTML = number_format(roundNumber(total,2));
	$('total_fee').innerHTML = number_format(roundNumber(total*to_numeric($('fee_rate').innerHTML)/100,2));
	$('total_tax').innerHTML = number_format(roundNumber((total+to_numeric($('total_fee').innerHTML))*to_numeric($('tax_rate').innerHTML)/100,2));
	
	$('total_discount').innerHTML = number_format($total_discount);
	$('total').innerHTML = number_format(roundNumber(to_numeric($('total_before_tax').innerHTML)+to_numeric($('total_fee').innerHTML)+to_numeric($('total_tax').innerHTML),2));
}
</script> 
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?><form name="DetailMinibarInvoiceForm" method="post" >
<table width="450px"  cellpadding="10" cellspacing="0" style="border:1px solid #000000;">
<tr>
	<td width="100%">
		<table width="100%" border="0" style="border-bottom:1px solid black;">
		<tr>
		  <td width="19%"><div align="center"><img src="<?php echo HOTEL_LOGO; ?>" width="100" height="83" /></div></td>
			<td width="56%" align="center">
				<div style="text-transform:uppercase"><?php echo HOTEL_NAME;?></div>
				<div>Add:<?php echo HOTEL_ADDRESS;?></div>
				<div>Tel:<?php echo HOTEL_PHONE;?>* Fax:<?php echo HOTEL_FAX;?></div>
				<div>E-mail:<?php HOTEL_EMAIL;?></div>
			</td>
		</tr>
		</table>
		<div style="text-align:right;float:left;width:100%;padding:2px 0px 2px 0px;">[[.No.]]: <strong><?php echo URL::get('id','(auto)');?></strong></div>
		<table width="100%" border="1" bordercolor="#000000" style="border-collapse:collapse" cellspacing="0" cellpadding="3px" frame="hsides">
          <tr>
            <th colspan="3" scope="col"><div align="left">[[.room_no.]]: [[|room_id|]]
            </div></th>
            <th colspan="3" scope="col" align="right">Date: [[|time|]]</th>
          </tr>
          <tr>
            <td align="center">[[.no.]]</td>
            <td align="center">[[.par_stock.]]</td>
            <td align="center">[[.consumed.]]</td>
            <td align="center">[[.item.]]</td>
            <td align="center">[[.price.]]</td>
            <td align="center">[[.amount.]]</td>
          </tr>
  		  <!--LIST:items-->
		  <tr>
            <td width="25px" align="right">[[|items.no|]]</td>
            <td width="35px" align="right">[[|items.norm_quantity|]]</td>
            <td width="50px"><input readonly="true" type="text" value="[[|items.quantity|]]" name="items[[[|items.id|]]][quantity]" id="quantity_[[|items.no|]]" onkeyup="recalculate_housekeeping_invoice_detail()" style="width:40px;text-align:center"/></td>
            <td width="100px">[[|items.name|]]</td>
            <td width="70px" align="right"><input type="text" name="items[[[|items.id|]]][price]" id="price_[[|items.no|]]" style="width:60px;text-align:center" value="<?php echo System::display_number([[=items.price=]]); ?>" readonly="readonly"/></td>
            <td width="80px" align="right"><span id="amount_[[|items.no|]]">&nbsp;</span></td>
          </tr>
		  <!--/LIST:items-->
		  </table>
		  <table width="100%">
			  <tr>
				<td width="290px" align="right">
					[[.total_before_tax.]]
				</td>
				<td align="right">
					<span id="total_before_tax"></span>
				</td>
			  </tr>
			  <tr>
				<td width="290px" align="right">
					[[.discount.]]
					(<span id="discount">[[|discount|]]</span> %)
				</td>
				<td align="right" align="right">
					<span id="total_discount"></span>
				</td>
			  </tr>
			  <tr>
				<td width="290px" align="right">
					[[.fee.]]
					(<span id="fee_rate">[[|fee_rate|]]</span>%)
				</td>
				<td align="right" align="right">
					<span id="total_fee"></span>
				</td>
			  </tr>
			  <tr>
				<td width="290px" align="right">
					[[.tax_rate.]]
					(<span id="tax_rate">[[|tax_rate|]]</span> %)
				</td>
				<td align="right" align="right">
					<span id="total_tax"></span>
				</td>
			  </tr>
			  <tr>
				<td width="290" align="right">
					[[.total.]]
				</td>
				<td align="right">
					<span id="total"></span>
				</td>
			  </tr>
		  </table><br />
		  <table width="100%" style="border-top:1px solid #000000;">
		  <tr>
		  	<td width="30%" align="left" colspan="3">
				[[.guest_signature.]]<p>&nbsp;</p><p>&nbsp;</p>
			</td>
			<td>&nbsp;</td>
			<td width="30%" align="right" colspan="3">
				[[.inventory_taken.]]<p>&nbsp;</p><p>&nbsp;</p>
			</td>
		  </tr>
        </table>
	</td>
</tr>
</table>
</form>
<script>
	recalculate_housekeeping_invoice_detail();
</script>