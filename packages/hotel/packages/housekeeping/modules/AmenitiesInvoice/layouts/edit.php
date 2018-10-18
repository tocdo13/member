<script>
function calculate_housekeeping_invoice_detail()
{
	var total=0;	
	for(var i=1;i<=[[|num_product|]];i++)
	{
		var amount = to_numeric(getElemValue('price_'+i))*to_numeric(getElemValue('quantity_'+i));
		$('amount_'+i).innerText = number_format(roundNumber(amount,2));
		total += amount;
	}
 	$total_discount = roundNumber(total*getElemValue('discount')/100,2);
	total = total - $total_discount;
	
	$('total_before_tax').value = number_format(roundNumber(total + $total_discount,2));
	$('total_fee').value = number_format(roundNumber(total*getElemValue('fee_rate')/100,2));
	$('total_tax').value = number_format(roundNumber((total+to_numeric(getElemValue('total_fee')))*getElemValue('tax_rate')/100,2));
	
	$('total_discount').value = number_format($total_discount);
	
	$('total').value = number_format(roundNumber(to_numeric(getElemValue('total_before_tax')) - $total_discount +to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax')),2));
}
function recalculate_housekeeping_invoice_detail(obj){
	<!--IF:unlimited(![[=unlimited=]])-->
	if(parseInt(jQuery(obj).val()) > parseInt(jQuery(obj).parent().prev().html()))
	{
		alert('Số lượng nhập không được lớn hơn số lượng phòng hiện có.');
		jQuery(obj).val(jQuery(obj).parent().prev().html());
	}else <!--/IF:unlimited-->
	if(parseInt(jQuery(obj).val())<0)
	{
		alert('Số lượng nhập không dược nhỏ hơn 0');
		jQuery(obj).val(jQuery(obj).parent().prev().html());
	}
	calculate_housekeeping_invoice_detail();
}
</script> 
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?><form name="EditMinibarInvoiceForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="75%" class="form-title">[[.amenities_invoice.]]</td>
		<td><input type="submit" value="[[.Save.]]" class="button-medium-save" /></td>
		<td><input type="button" value="[[.list.]]" class="button-medium-add" onclick="location='<?php echo URL::build_current();?>';"/></td>
	</tr>
</table>
<table width="550px" border="1" cellpadding="10" cellspacing="0" style="font-family:'Times New Roman', Times, serif; margin:0 auto;font-size:12px;border-collapse:collapse" bordercolor="black">
<tr>
	<td width="100%">
        <table width="100%" border="0" style="border-bottom:1px solid black;">
            <tr>
                <td width="19%"><div align="center"><img src="<?php echo HOTEL_LOGO; ?>" width="100" height="83" /></div></td>
                <td width="56%" align="center">
                    <div style="text-transform:uppercase"><?php echo HOTEL_NAME;?></div>
                    <div>Add:<?php echo HOTEL_ADDRESS;?></div>
                    <div>Tel:<?php echo HOTEL_NAME;?>* Fax:<?php echo HOTEL_FAX;?></div>
                    <div>E-mail:<?php echo HOTEL_EMAIL;?></div>
                </td>
            </tr>
        </table>
		<?php if(Form::$current->is_error()) { echo Form::$current->error_messages(); }?>
        <table width="100%" border="1" bordercolor="#000000" style="border-collapse:collapse" cellspacing="0" cellpadding="5px">
            <tr>
                <th colspan="3" scope="col" width="70%"><div align="left">
                    [[.room_no.]]: &nbsp;[[|room_name|]]
                    <input name="room_id" type="hidden" id="room_id" value="[[|room_id|]]"/>
                    <br />
                    [[.bill_number.]]: &nbsp;&nbsp;<input name="code" type="text" id="code" style="width:80px;" />
                    <br />
                    [[.note.]]: &nbsp;&nbsp;&nbsp;&nbsp;<input name="note" type="text" id="note" style="width:280px;" maxlength="300" />
                    </div>
                </th>
                <th colspan="3" scope="col">Date: [[|time|]]<!--IF:cond([[=lastest_edited_time=]])--> | [[.lastest_edited_time.]] :[[|lastest_edited_time|]]<!--/IF:cond--></th>
            </tr>   
            <tr>
                <td align="center" width="10px">[[.no.]]</td>
                <td align="center" width="55px">[[.consumed.]]</td>
                <td align="center" width="200px">[[.item.]]</td>
                <td align="center" width="100px">[[.price.]]</td>
                <td align="center" width="100px">[[.amount.]]</td>
            </tr>
            <!--LIST:items-->
            <tr>
                <td align="right">[[|items.no|]]</td>
                <td align="center"><input type="text" value="[[|items.quantity|]]" name="items[[[|items.id|]]][quantity]" id="quantity_[[|items.no|]]" onkeyup="recalculate_housekeeping_invoice_detail(this)" style="width:40px;text-align:center" class="input_number"/></td>
                <td >[[|items.name|]]</td>
                <td align="center"><input type="text" name="items[[[|items.id|]]][price]" id="price_[[|items.no|]]" style="width:60px; text-align:center" value="<?php echo System::display_number([[=items.price=]]); ?>"/></td>
                <td align="center"><span id="amount_[[|items.no|]]">&nbsp;</span></td>
            </tr>
            <!--/LIST:items-->
        </table>
		  
        <table width="100%" style="border-bottom:1px solid black">
            <tr style="display: none;">
                <td width="290" align="right">[[.total_before_tax.]]</td>
                <td>
                    <input type="text" name="total_before_tax" id="total_before_tax" readonly="readonly" style="width:99%;border:0 solid white;text-align:right"/>			
                </td>
            </tr>
            <tr style="display: none;">
                <td align="right"> 
                    [[.discount.]]
                    (<input  name="discount" type="text" id="discount" value="[[|discount|]]" style="width:20px; text-align:center; border:0px #FFFFFF" onkeyup="recalculate_housekeeping_invoice_detail();"/>%) 
                </td>
                <td>
                    <input  name="total_discount" type="text" id="total_discount"  value="[[|total_discount|]]" readonly="readonly" style="width:99%;border:0 solid white;text-align:right"/>
                </td>
            </tr>
            <tr style="display: none;">
                <td width="290" align="right">
                    [[.fee.]]
                    (<input type="text" name="fee_rate" id="fee_rate" style="width:20px; text-align:center; border:0px #FFFFFF" value="[[|fee_rate|]]" onkeyup="recalculate_housekeeping_invoice_detail();" />%)			
                </td>
                <td>
                    <input type="text" name="total_fee" id="total_fee" readonly="readonly" style="width:99%;border:0 solid white;text-align:right"/>			
                </td>
            </tr>
            <tr style="display: none;">
                <td width="290" align="right">
                    [[.tax_rate.]]
                    (<input type="text" name="tax_rate" id="tax_rate" style="width:20px; text-align:center; border:0px #FFFFFF" value="[[|tax_rate|]]" onkeyup="recalculate_housekeeping_invoice_detail();" />%)			
                </td>
                <td align="center">
                    <input type="text" name="total_tax" id="total_tax" readonly="readonly" style="width:99%;border:0 solid white;text-align:right" />			
                </td>
            </tr>
            <tr>
                <td width="290" align="right">[[.total.]]</td>
                <td>
                    <input type="text" name="total" id="total" readonly="readonly" style="width:100%;border:0 solid white;text-align:right"/>			
                </td>
            </tr>
        </table>
          
        <input style="color:red;font-size:20;height:24px; text-align:center;border:0 solid white;width:100%" name="id" value="<?php echo URL::get('id','(auto)');?>"/>
        <table width="100%">
            <tr>
                <td width="30%" align="left" colspan="3" style="border-top:1px solid black;">[[.guest_signature.]]</td>
                <td>&nbsp;</td>
                <td width="30%" align="right" colspan="3" style="border-top:1px solid black;">[[.inventory_taken.]]</td>
            </tr>
        </table>
	</td>
</tr>
</table>
<br/>
<br/>
</form>
<script>
	calculate_housekeeping_invoice_detail(); 
</script>