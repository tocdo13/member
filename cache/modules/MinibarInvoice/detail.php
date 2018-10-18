<style type="text/css">
	 @media print{
	   #export_excel{
	       display: none;
	   }
  }
</style>
<script>
function recalculate_housekeeping_invoice_detail()
{
	//var total=0;	
//	for(var i=1;i<=<?php echo $this->map['num_product'];?>;i++)
//	{
//		var amount = to_numeric(getElemValue('price_'+i))*to_numeric(getElemValue('quantity_'+i));
//		$('amount_'+i).innerText=number_format(roundNumber(amount,2));
//		total+=amount;
//	}
//	$total_discount = roundNumber(total*getElemValue('discount')/100,2);
//	total = total - $total_discount;
//	
//	$('total_before_tax').innerHTML = number_format(roundNumber(total+$total_discount,2));
//	$('total_fee').innerHTML = number_format(roundNumber(total*to_numeric($('fee_rate').innerHTML)/100,2));
//	$('total_tax').innerHTML = number_format(roundNumber((total+to_numeric($('total_fee').innerHTML))*to_numeric($('tax_rate').innerHTML)/100,2));
//	
//	$('total_discount').innerHTML = number_format($total_discount);
//	$('total').innerHTML = number_format(roundNumber(to_numeric($('total_before_tax').innerHTML)- $total_discount+to_numeric($('total_fee').innerHTML)+to_numeric($('total_tax').innerHTML),2));
    
    <?php if($this->map['net_price_minibar'] == 1) {?>
        var total_before_tax=0;	
        for(var i=1;i<=<?php echo $this->map['num_product'];?>;i++)
        {
        	var amount = to_numeric(getElemValue('price_'+i))*to_numeric(getElemValue('quantity_'+i));
        	$('amount_'+i).innerText=number_format(roundNumber(amount,2));
        	total_before_tax+=amount;
        }
        var tax = total_before_tax - total_before_tax*100/(to_numeric(getElemValue('tax_rate'))+100);
        total_before_tax -= tax;
        var service_change = total_before_tax - total_before_tax*100/(to_numeric(getElemValue('fee_rate'))+100);
        total_before_tax -= service_change;
        $('total_before_tax').innerText = number_format(roundNumber(total_before_tax,2));
        $total_discount = roundNumber(total_before_tax*getElemValue('discount')/100,2);
        $('total_discount').innerText = number_format($total_discount);
        total_before_tax -= $total_discount;
        $('total_fee').innerText = number_format(roundNumber(total_before_tax*getElemValue('fee_rate')/100,2));
        $('total_tax').innerText = number_format(roundNumber((total_before_tax+to_numeric(getElemValue('total_fee')))*getElemValue('tax_rate')/100,2));
        $('total').innerText = number_format(Math.round(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax'))-$total_discount),2); //daund edit lam tron
        console.log('1111');
    <?php  
    }
    else 
    {
    ?>
        console.log('2222');
        var total_before_tax=0;	
        for(var i=1;i<=<?php echo $this->map['num_product'];?>;i++)
        {
        	var amount = to_numeric(getElemValue('price_'+i))*to_numeric(getElemValue('quantity_'+i));
        	$('amount_'+i).innerText=number_format(roundNumber(amount,2));
        	total_before_tax+=amount;
        }
        $total_discount = roundNumber(total_before_tax*getElemValue('discount')/100,2);
        total_before_tax = total_before_tax - $total_discount;
        $('total_before_tax').innerText = number_format(roundNumber(total_before_tax + $total_discount,2));
        $('total_fee').innerText = number_format(roundNumber(total_before_tax*getElemValue('fee_rate')/100,2));
        $('total_tax').innerText = number_format(roundNumber((total_before_tax+to_numeric(getElemValue('total_fee')))*getElemValue('tax_rate')/100,2));
        
        $('total_discount').innerText = number_format($total_discount);
        
        $('total').innerText = number_format(Math.round(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax')),2)-$total_discount); //daund edit lam tron
    <?php } ?>

}
</script> 
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('minibar_invoice_detail'));?><form name="DetailMinibarInvoiceForm" method="post" >
<input name="export_excel" id="export_excel" type="button" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 100px; height: 23px"/>
<table id="Export" width="450px"  cellpadding="10" cellspacing="0" style="border:1px solid #000000;">
<tr>
	<td width="100%">
		<table width="100%" border="0" style="border-bottom:1px solid black;">
		<tr>
		  <td width="19%" id="srcs"><div align="center"><img id="imgs" src="<?php echo HOTEL_LOGO; ?>" width="100" height="83" /></div></td>
			<td colspan="4"></td>
            <td width="56%" align="left">
				<div style="text-transform:uppercase"><?php echo HOTEL_NAME;?></div>
				<div>Add:<?php echo HOTEL_ADDRESS;?></div>
				<div>Tel:<?php echo HOTEL_PHONE;?>* Fax:<?php echo HOTEL_FAX;?></div>
				<div>E-mail:<?php HOTEL_EMAIL;?></div>
			</td>
		</tr>
		</table>
		<div style="text-align:left;float:left;width:50%;padding:2px 0px 2px 0px;"><?php echo Portal::language('invoice');?>: <strong>MN_<?php echo $this->map['position'];?><?php //echo URL::get('id','(auto)');?></strong>
      <br /><?php echo Portal::language('code_hand');?>: <strong><?php echo $this->map['code'];?></strong>
        </div>
         <div style="text-align:right;float:right;width:50%;padding:2px 0px 2px 0px;"><?php echo Portal::language('note');?>: <strong><?php echo $this->map['note'];?></strong></div>
		<table width="100%" border="1" bordercolor="#000000" style="border-collapse:collapse" cellspacing="0" cellpadding="3px" frame="hsides">
          <tr>
            <th colspan="3" scope="col"><div align="left"><?php echo Portal::language('room_no');?>: <?php echo $this->map['room_id'];?></div></th>
            <th colspan="3" scope="col" align="right">Date: <?php echo $this->map['time'];?></th>
          </tr>
          <tr>
            <td align="center"><?php echo Portal::language('no');?></td>
            <td align="center"><?php echo Portal::language('par_stock');?></td>
            <td align="center"><?php echo Portal::language('consumed');?></td>
            <td align="center"><?php echo Portal::language('item');?></td>
            <td align="center"><?php echo Portal::language('price');?></td>
            <td align="center"><?php echo Portal::language('amount');?></td>
          </tr>
  		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		  <tr>
            <td width="25px" align="center"><?php echo $this->map['items']['current']['no'];?></td>
            <td width="35px" align="center"><?php echo $this->map['items']['current']['norm_quantity'];?></td>
            <td width="50px"><span id="quantity_span_<?php echo $this->map['items']['current']['no'];?>"></span><input readonly="true" type="text" value="<?php echo $this->map['items']['current']['quantity'];?>" name="items[<?php echo $this->map['items']['current']['id'];?>][quantity]" id="quantity_<?php echo $this->map['items']['current']['no'];?>" onkeyup="recalculate_housekeeping_invoice_detail()" style="width:40px;text-align:center"/></td>
            <td width="100px"><?php echo $this->map['items']['current']['name'];?></td>
            <td width="70px" align="right"><span id="price_span_<?php echo $this->map['items']['current']['no'];?>"></span><input type="text" class="input" name="items[<?php echo $this->map['items']['current']['id'];?>][price]" id="price_<?php echo $this->map['items']['current']['no'];?>" style="width:60px;text-align:center" value="<?php echo System::display_number($this->map['items']['current']['price']); ?>" readonly="readonly"/></td>
            <td width="80px" align="right"><span id="amount_<?php echo $this->map['items']['current']['no'];?>">&nbsp;</span></td>
          </tr>
		  <?php }}unset($this->map['items']['current']);} ?>
		  </table>
		  <table id="tb_tonumeric" width="100%">
			  <tr>
				<td colspan="4"></td>
                <td width="290px" align="right">
					<?php echo Portal::language('total_before_tax');?>
				</td>
				<td align="right">
					<span id="total_before_tax"></span>
				</td>
			  </tr>
			  <tr>
                <td colspan="4"></td>
				<td width="290px" align="right">
					<?php echo Portal::language('discount');?>
					(<span id="discount"><?php echo $this->map['discount'];?></span> %)
				</td>
				<td align="right">
					<span id="total_discount"></span>
				</td>
			  </tr>
			  <tr>
                <td colspan="4"></td>
				<td width="290px" align="right">
					<?php echo Portal::language('fee');?>
					(<span id="fee_rate"><?php echo $this->map['fee_rate'];?></span>%)
				</td>
				<td align="right" >
					<span id="total_fee"></span>
				</td>
			  </tr>
			  <tr>
                <td colspan="4"></td>
				<td width="290px" align="right">
					<?php echo Portal::language('tax_rate');?>
					(<span id="tax_rate"><?php echo $this->map['tax_rate'];?></span> %)
				</td>
				<td align="right">
					<span id="total_tax"></span>
				</td>
			  </tr>
			  <tr>
                <td colspan="4"></td>
				<td width="290" align="right">
					<?php echo Portal::language('total');?>
				</td>
				<td align="right">
					<span id="total"></span>
				</td>
			  </tr>
		  </table><br />
		  <table width="100%" style="border-top:1px solid #000000;">
		  <tr>
		  	<td width="30%" align="left" colspan="3">
				<?php echo Portal::language('guest_signature');?><p>&nbsp;</p><p>&nbsp;</p>
			</td>
			<td>&nbsp;</td>
			<td width="30%" align="right" colspan="2">
				<?php echo Portal::language('inventory_taken');?><p>&nbsp;</p><p>&nbsp;</p>
			</td>
		  </tr>
        </table>
	</td>
</tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
	recalculate_housekeeping_invoice_detail();
    jQuery("#export_excel").click(function () {
    jQuery('#imgs').remove();
    for(var i=1;i<=<?php echo $this->map['num_product'];?>;i++)
    {
        var quantity = jQuery('#quantity_'+i).val();
        //console.log(quantity);
        var price = to_numeric(jQuery('#price_'+i).val());
        jQuery('#quantity_'+i).remove();
        jQuery('#quantity_span_'+i).text(quantity);
        jQuery('#price_'+i).remove();
        jQuery('#price_span_'+i).text(price);
        var amount = to_numeric(jQuery('#amount_'+i).html());
        jQuery('#amount_'+i).html(amount);
    }
    jQuery('table #tb_tonumeric td span').each(function(){
        var content = jQuery(this).html();
        if(content.trim()!="")
        {
            jQuery(this).html(to_numeric(jQuery(this).html())); 
        } 
    });
    jQuery("#Export").battatech_excelexport({
        containerid: "Export"
       , datatype: 'table'
       , fileName: '<?php echo Portal::language('minibar');?>'
    });       
    
});
</script>
