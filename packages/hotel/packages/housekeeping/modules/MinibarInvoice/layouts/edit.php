<script>
function calculate_housekeeping_invoice_detail()
{
    var change_quan=0;
    for(var i=1;i<=[[|num_product|]];i++)
    {
        $('span_total_consumed_'+i).innerText=to_numeric(getElemValue('quantity_'+i)) + to_numeric(getElemValue('change_quantity_'+i));
        change_quan += Math.abs(to_numeric(getElemValue('change_quantity_'+i)));
    }
    $('change_quan').value = change_quan;
	<?php //new NET_PRICE_SERVICE
	 if([[=net_price_minibar=]] == 1) {?>
        var total_before_tax=0;	
        for(var i=1;i<=[[|num_product|]];i++)
        {
        	var amount = to_numeric(getElemValue('price_'+i))*(to_numeric(getElemValue('quantity_'+i))+to_numeric(getElemValue('change_quantity_'+i)));
        	$('amount_'+i).innerText=number_format(roundNumber(amount,2));
        	total_before_tax+=amount;
        }
        var tax = total_before_tax - total_before_tax*100/(to_numeric(getElemValue('tax_rate'))+100);
        total_before_tax -= tax;
        var service_change = total_before_tax - total_before_tax*100/(to_numeric(getElemValue('fee_rate'))+100);
        total_before_tax -= service_change;
        $('span_total_before_tax').innerText = number_format(roundNumber(total_before_tax,2));
        $('total_before_tax').value = number_format(roundNumber(total_before_tax,2));
        $total_discount = roundNumber(total_before_tax*getElemValue('discount')/100,2);
        $('total_discount').value = number_format($total_discount);
        total_before_tax -= $total_discount;
        $('total_fee').innerText = number_format(roundNumber(total_before_tax*getElemValue('fee_rate')/100,2));
        $('total_tax').innerText = number_format(roundNumber((total_before_tax+to_numeric(getElemValue('total_fee')))*getElemValue('tax_rate')/100,2));
        $('span_total').innerText = number_format(Math.round(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax'))-$total_discount),2); //daund lam tron
        $('total').value = number_format(Math.round(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax'))-$total_discount),2); //daund lam tron
    <?php  
    }
    else 
    {
    ?>
        var total_before_tax=0;	
        for(var i=1;i<=[[|num_product|]];i++)
        {
        	var amount = to_numeric(getElemValue('price_'+i))*(to_numeric(getElemValue('quantity_'+i))+to_numeric(getElemValue('change_quantity_'+i)));
        	$('amount_'+i).innerText=number_format(roundNumber(amount,2));
        	total_before_tax+=amount;
        }
        $total_discount = roundNumber(total_before_tax*getElemValue('discount')/100,2);
        total_before_tax = total_before_tax - $total_discount;
        $('span_total_before_tax').innerText = number_format(roundNumber(total_before_tax + $total_discount,2));
        $('total_before_tax').value = number_format(roundNumber(total_before_tax + $total_discount,2));
        $('total_fee').innerText = number_format(roundNumber(total_before_tax*getElemValue('fee_rate')/100,2));
        $('total_tax').innerText = number_format(roundNumber((total_before_tax+to_numeric(getElemValue('total_fee')))*getElemValue('tax_rate')/100,2));
        
        $('total_discount').value = number_format($total_discount);
        
        $('span_total').innerText = number_format(Math.round(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax')),2)-$total_discount); //daund lam tron
        $('total').value = number_format(Math.round(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('total_fee'))+to_numeric(getElemValue('total_tax')),2)-$total_discount); // daund lam tron
    <?php } ?>
}
function recalculate_housekeeping_invoice_detail(obj,norm_quantity)
{
	//start: KID đổi (1) => (2), (3) => (4) va them tham so norm_quantity
    //chả hiểu tại sao lại viết thế này :D
    //if(parseInt(jQuery(obj).val()) > parseInt(jQuery(obj).parent().prev().html()))(1)
    if(parseInt(jQuery(obj).val()) > norm_quantity)//(2))
	{
		<!--IF:unlimited(![[=unlimited=]])-->
		alert('Số lượng nhập không được lớn hon số lượng hiện có trong minibar.');
		//jQuery(obj).val(jQuery(obj).parent().prev().html());(3)
        jQuery(obj).val(norm_quantity);//(4)
		<!--/IF:unlimited-->
	}else if(parseInt(jQuery(obj).val())<0)
	{
		alert('Số lượng nhập không được nhỏ hơn không');
		jQuery(obj).val(0);
	}
    //end: KID đổi (1) => (2), (3) => (4) va them tham so norm_quantity
	calculate_housekeeping_invoice_detail();
}
function recalculate_housekeeping_invoice_detail_for_change(obj,norm_quantity,quantity)
{
    if(parseInt(jQuery(obj).val()) > norm_quantity-quantity)
	{
		<!--IF:unlimited(![[=unlimited=]])-->
		alert('Số lượng nhập không được lớn hon số lượng hiện có trong minibar.');
        jQuery(obj).val(0);
		<!--/IF:unlimited-->
	}else if((parseInt(jQuery(obj).val()) + quantity) < 0)
	{
		alert('Số lượng nhập không hợp lệ. tổng số lượng và số lượng thay đổi không được nhỏ hơn 0');
		jQuery(obj).val(0);
	}
	calculate_housekeeping_invoice_detail();
}

</script>
<style>
    .span_total_consumed{ display: none;}
    .td_total_consumed{ display: none;}
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?><form id="EditMinibarInvoiceForm" name="EditMinibarInvoiceForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="75%" class="" style="font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 24px;"></i> [[.minibars_invoice.]]</td>
		<td><input type="button" value="[[.Save.]]" class="w3-btn w3-blue w3-text-white" onclick=" return check_save();" />
		<input type="button" value="[[.list.]]" class="w3-btn w3-green w3-text-white" onclick="location='<?php echo URL::build_current();?>';"/></td>
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
                <th  scope="col" width="70%"><div align="left">
                <table>
                	<tr>
                    	<td>[[.room_no.]]:
                        </td>
                        <td>[[|room_name|]]<input name="minibar_id" type="hidden" id="minibar_id" value="[[|minibar_id|]]"/>
                        </td>
                    </tr>
                    <tr>
                    	<td>[[.code_hand.]]
                        </td>
                        <td><input name="code" type="text" id="code" style="width:80px;" />
                        </td>
                    </tr>
                    <tr>
                    	<td>[[.note.]]
                        </td>
                        <td><input name="note" type="text" id="note" style="width:280px;" maxlength="300" />
                        </td>
                    </tr>
            
            </table>
                   
                </th>
                <th scope="col">Date: [[|time|]]<!--IF:cond([[=lastest_edited_time=]])--> | [[.lastest_edited_time.]] :[[|lastest_edited_time|]]<!--/IF:cond--></th>
            </tr>
            <tr>
                <td colspan="2">
                    <table border="1" bordercolor="#000000" style="border-collapse:collapse" cellspacing="0" cellpadding="5px">
                         <tr>
                            <td align="center" width="10px">[[.no.]]</td>
                            <td class="td_quantity" align="center" width="50px">[[.consumed.]]</td>
                            <td class="td_quantity" align="center" width="50px">[[.edit_consumed.]]</td>
                            <td class="td_total_consumed" align="center" width="50px">[[.total_consumed.]]</td>
                            <td align="center" width="200px">[[.item.]]</td>
                            <td align="center" width="100px">[[.price.]]</td>
                            <td align="center" width="100px">[[.amount.]]</td>
                        </tr>
                        <!--LIST:items-->
                        <tr>
                            <td align="right">[[|items.no|]]</td>
                            <td class="td_quantity" align="center"><input type="text" value="[[|items.quantity|]]" name="items[[[|items.id|]]][quantity]" id="quantity_[[|items.no|]]" onkeyup="recalculate_housekeeping_invoice_detail(this,[[|items.norm_quantity|]])" <?php if([[=items.quantity=]]>0){ ?>readonly="readonly" <?php }?> style="width:40px;text-align:center" class="input_number" autocomplete="OFF"/></td>
                            <td class="td_quantity" align="center"><input type="text" value="[[|items.change_quantity|]]" name="items[[[|items.id|]]][change_quantity]" id="change_quantity_[[|items.no|]]" onkeypress="return isNumberKey(event)" onkeyup="recalculate_housekeeping_invoice_detail_for_change(this,[[|items.norm_quantity|]],[[|items.quantity|]])" style="width:40px;text-align:center" autocomplete="OFF"/></td>
                            <td class="td_total_consumed" align="center"><span id="span_total_consumed_[[|items.no|]]" class="span_total_consumed"></span></td>
                            <td >[[|items.name|]]</td>
                            <td align="center"><input type="text" name="items[[[|items.id|]]][price]" id="price_[[|items.no|]]" onkeyup="calculate_housekeeping_invoice_detail();" style="width:60px; text-align:center" value="<?php echo System::display_number([[=items.price=]]); ?>"/></td>
                            <td align="center"><span id="amount_[[|items.no|]]">&nbsp;</span></td>
                        </tr>
                        <!--/LIST:items-->
                    </table>
                </td>
            </tr>   
        </table>
		<input type="hidden" value="" name="change_quan" id="change_quan" style="width:40px;text-align:center"/>  
        <table width="100%" style="border-bottom:1px solid black">
            <tr>
                <td width="290" align="right">[[.total_before_tax.]]</td>
                <td align="right">
                    <input type="hidden" name="total_before_tax" id="total_before_tax" readonly="readonly" style="width:99%;border:0 solid white;text-align:right"/>		
                    <span id="span_total_before_tax" style="width:99%;text-align:right"></span>	
                </td>
            </tr>
            <tr>
                <td align="right"> 
                    [[.discount.]]
                    (<input  name="discount" type="text" id="discount" class="input_number"  value="[[|discount|]]" style="width:20px; text-align:center; border:0px #FFFFFF" onkeyup="recalculate_housekeeping_invoice_detail();"/>%) 
                </td>
                <td>
                    <input  name="total_discount" type="text" id="total_discount"   value="[[|total_discount|]]" readonly="readonly" style="width:99%;border:0 solid white;text-align:right"/>
                </td>
            </tr>
            <tr>
                <td width="290" align="right">
                    [[.fee.]]
                    (<input type="text" name="fee_rate" id="fee_rate" class="input_number"  style="width:20px; text-align:center; border:0px #FFFFFF" value="[[|fee_rate|]]" onkeyup="recalculate_housekeeping_invoice_detail();" />%)			
                </td>
                <td align="right">
                    <!--<input type="text" name="total_fee" id="total_fee" readonly="readonly" style="width:99%;border:0 solid white;text-align:right"/>-->
                    <span id="total_fee" style="width:99%;text-align:right"></span>			
                </td>
            </tr>
            <tr>
                <td width="290" align="right">
                    [[.tax_rate.]]
                    (<input type="text" name="tax_rate" id="tax_rate" class="input_number"  style="width:20px; text-align:center; border:0px #FFFFFF" value="[[|tax_rate|]]" onkeyup="recalculate_housekeeping_invoice_detail();" />%)			
                </td>
                <td align="right">
                    <!--<input type="text" name="total_tax" id="total_tax" readonly="readonly" style="width:99%;border:0 solid white;text-align:right;" />-->		
                    <span id="total_tax" style="width:99%;text-align:right"></span>
                </td>
            </tr>
            <tr>
                <td width="290" align="right">[[.total.]]</td>
                <td align="right">
                    <input type="hidden" name="total" id="total" readonly="readonly" style="width:100%;border:0 solid white;text-align:right"/>			
                    <span id="span_total" style="width:99%;text-align:right"></span>
                </td>
            </tr>
        </table>
          
        <div style="color:red;font-size:16px;height:20px; text-align:center;border:0 solid white;width:100%">[[|position|]]</div>
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
<style>
    @media print
    {
        .td_quantity{display:none}
        .total_consumed{display:none}
        .button-medium-add{display:none}
        .button-medium-save{display:none}
        .span_total_consumed{display: inline}
        .td_total_consumed{ display: inline}
    }
</style>
<script>
	calculate_housekeeping_invoice_detail();
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && ((charCode != 45 && charCode < 48) || charCode > 57))
            return false;
        return true;
    }
    var last_time = [[|last_time|]];
    function check_save()
    {
        <?php echo 'var id_check = '.Url::get("id").';';?> 
        <?php echo 'var block_id = '.Module::block_id().';';?>
        jQuery.ajax({
    				url:"form.php?block_id="+block_id,
    				type:"POST",
                    dataType: "json",
    				data:{check_last_time:1,id:id_check,last_time:last_time},
    				success:function(html)
                    {
                        a = 2;
                        if(html['status']=='error')
                        {
                            alert('RealTime:\n Lưu ý, Hóa đơn Minibar này đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                            return false;
                        }
                        else
                        {
                            if(jQuery('#change_quan').val()>0 && jQuery('#change_quan').val() !=''){
                                if(jQuery('#note').val().trim()==''){
                                    alert('bạn chưa nhập ghi chú');
                                    return false;
                                }else
                                    EditMinibarInvoiceForm.submit();
                            }else
                                EditMinibarInvoiceForm.submit();
                        }
    				}
    	});
    } 
</script>
