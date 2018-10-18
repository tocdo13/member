<style>
    input[type='text'] 
    {
        width: 150px;
        height: 20px;
    }
</style>
<form method="POST" name="DebtDeductionForm">
    <table cellpadding="15" class="table-bound" style="margin: 0px auto;">
		<tr>
        	<td class="form-title">[[.debit_deduction.]]</td>
            <td style="text-align: right;">
                <a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.view_report.]]</a>
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.save_close.]]" onclick="return CheckTotal();" class="button-medium-save"  /><?php }?>
            </td>
        </tr>
    </table>
    <table cellpadding="15" style="margin: 10px auto;">
        <tr>
            <td>[[.folio_number.]] FOLIO:</td>
            <td>
                <input name="folio_number" type="text" id="folio_number" readonly="" style="padding: 5px; width: 250px;" />
                <input name="recode" type="text" id="recode" readonly="" style="padding: 5px; width: 250px; display: none;" />
            </td>
        </tr>
        <tr>
            <td>[[.bar_invoice.]]:</td>
            <td>
                <input name="bar_reservation_code" type="text" id="bar_reservation_code" readonly="" style="padding: 5px; width: 250px;" />
                <input name="bar_reservation_ids" type="text" id="bar_reservation_ids" readonly="" style="padding: 5px; width: 250px; display: none;" />
            </td>
        </tr>
        <tr>
            <td>[[.date_in.]]:</td>
            <td><input name="date_in" type="text" id="date_in" readonly="" style="padding: 5px; width: 250px;" /></td>
        </tr>
        <tr>
            <td>[[.customer.]]:</td>
            <td>
                <input name="customer_name" type="text" id="customer_name" readonly="" style="padding: 5px; width: 250px;" />
                <input name="customer_id" type="text" id="customer_id" readonly="" style="padding: 5px; width: 250px; display: none;" />
            </td>
        </tr>
        <tr>
            <td>[[.total_invoice.]]:</td>
            <td><input name="total_invoice" type="text" id="total_invoice" readonly="" style="padding: 5px; width: 250px; text-align: right;" /></td>
        </tr>
        <tr>
            <td>[[.debit_deduction_amount.]]:</td>
            <td><input name="price" type="text" id="price" class="input_number" onchange="CheckTotal();" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" style="padding: 5px; width: 250px; text-align: right;" /></td>
        </tr>
        <tr>
            <td>[[.payment_type.]]:</td>
            <td><select name="payment_type_id" id="payment_type_id" style="padding: 5px; width: 260px;"></select></td>
        </tr>
        <tr>
            <td>[[.description.]]:</td>
            <td><input name="description" type="text" id="description" style="padding: 5px; width: 250px;" /></td>
        </tr>
    </table>
</form>
<script>
    function CheckTotal()
    {
        $total = to_numeric(jQuery("#total_invoice").val());
        $price = to_numeric(jQuery("#price").val());
        
        if($price==0 || $price=='')
        {
            alert('bạn chưa nhập số tiền!');
            return false;
        }
        
        if( ($price-$total)>0 )
        {
            alert('Không được nhập số tiền lớn hơn số tiền nợ của hóa đơn!');
            jQuery("#price").val('');
            return false;
        }
        else if( ($price-$total)<0 )
        {
            alert('Không được nhập số tiền nhỏ hơn số tiền nợ của hóa đơn!');
            jQuery("#price").val('');
            return false;
        }
        else
            return true;
    }
</script>