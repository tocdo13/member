<style>
@media print{
    .bill-code{
        display: none;
    }
}
    table tr td {
        font-size: 10px;
    }
    input[type=text]{
        width:80px;
        border:none;
        outline: none;
        color: red;
    }
</style>

<div style="width: 500px; height: auto; margin:0px auto; padding: 0px; margin: 100px auto;">
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr>
            <td style="">
                [[.date_check_no.]] (<i>Check No</i>): <?php echo date('d/m/Y H:i\'');?>
            </td>
            <td style="">
            </td>
        </tr>
        <tr>
            <td style="">
                [[.number_check_no.]] (<i>Check No</i>): #00[[|id|]]
            </td>
            <td style=""></td>
        </tr>
    </table>
    <form name="BillcodeForm" method="post">
    <table style="width: 100%; margin-top: 10px;" border="1" bordercolor="#CCCCCC" cellSpacing="0" cellpadding="3">
        <tr style="text-align: center; ">
            <td>[[.location_pin.]] <br /> (<i>Location</i>)</td>
                <td class="bill-code">[[.bill_code.]] <br /> (<i>Bill code</i>)</td>
            <td>[[.table_res.]] <br /> (<i>Table</i>)</td>
            <td>[[.number_of_guests.]] <br /> (<i>No of Guests</i>)</td>
            <td>[[.time_in.]] <br /> (<i>In</i>)</td>
            <td>[[.time_out.]] <br /> (<i>Out</i>)</td>
            <td>[[.waiters.]] <br /> (<i>Waiters</i>)</td>
            <td>[[.cashier.]] <br /> (<i>Cashier</i>)</td>
        </tr>
        <tr style="text-align: center; ">
            <td>[[|party_type_name|]]</td>
            <td class="bill-code"><input name="bill_code" id="bill_code" value="<?php if(isset([[=bill_code=]])){echo [[=bill_code=]];}?>" type="text" onkeypress="handle(event)" style="text-align: center;"/></td>
            <td>[[|name_party_room|]]</td>
            <td>[[|num_people|]]</td>
            <td><?php echo date('H:i',[[=checkin_time=]]); ?></td>
            <td><?php echo date('H:i',[[=checkout_time=]]); ?></td>
            <td></td>
            <td><?php echo User::id(); ?></td>
        </tr>
    </table>
    </form>
    <table style="width: 100%; margin-top: 10px;" border="1" bordercolor="#CCCCCC" cellSpacing="0" cellpadding="3">
        <tr style="text-align: center; ">
            <td>[[.stt.]] <br /> (<i>No</i>)</td>
            <td>[[.puschases_and_service.]] <br /> (<i>Description</i>)</td>
            <td>[[.unit.]] <br /> (<i>Unit</i>)</td>
            <td>[[.quantity.]] <br /> (<i>Quantity</i>)</td>
            <td>[[.unit_price.]] <br /> (<i>Unit Price</i>)</td>
            <td>[[.amount.]] <br /> (<i>Amount</i>)</td>
        </tr>
        <?php $i=1; ?>
        <!--LIST:items-->
        <tr>
            <td><?php echo $i++; ?></td>
            <td>[[|items.product_name|]]</td>
            <td>[[|items.unit_name|]]</td>
        	<td>[[|items.quantity|]]</td>   
            <td style="text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=items.price=]]),0)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=total_product_price=]]),0)); ?></td>
        </tr>
        <!--/LIST:items-->
        <!--LIST:rooms-->
        <tr>
            <td><?php echo $i++; ?></td>
            <td>[[|rooms.name|]]</td>
            <td>[[.room.]]</td>
        	<td>1</td>
            <td style="text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=rooms.price=]]),0)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=total_room_price=]]),0)); ?></td>
        </tr>
        <!--/LIST:rooms-->
    </table>
    
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr style="">
            <td></td>
            <td></td>
            <td>[[.sub_total.]] (<i>Sub Total</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=total_before_tax=]],0)); ?></td>
        </tr>
        <tr style="">
            <td>[[.service_charge_rate.]] (<i>Service Charge Rate</i>)</td>
            <td>[[|extra_service_rate|]]%</td>
            <td>[[.service_charge.]] (<i>Service Charge</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=extra_service_rate=]]*[[=total_before_tax=]]/100,0)); ?></td>
        </tr>
        <tr style="">
            <td>[[.vat_rate.]] (<i>VAT Rate</i>)</td>
            <td>[[|vat|]]%</td>
            <td>[[.value_added_tax.]] (<i>Value Added Tax</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round((([[=extra_service_rate=]]*[[=total_before_tax=]]/100)+[[=total_before_tax=]])*[[=vat=]]/100,0)); ?></td>
        </tr>
        <tr style="">
            <td></td>
            <td></td>
            <td>[[.advance_payment.]] (<i>Advance Payment</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=advance_payment=]]),0)); ?></td>
        </tr>
        <tr style="">
            <td></td>
            <td></td>
            <td>[[.deposit.]] (<i>Deposit</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round(System::calculate_number([[=deposit_1=]])+System::calculate_number([[=deposit_2=]])+System::calculate_number([[=deposit_3=]])+System::calculate_number([[=deposit_4=]]),0)); ?></td>
        </tr>
        <tr style="">
            <td></td>
            <td></td>
            <td style="font-size: 11px;font-weight: bold;">[[.total_amount.]] (<i>Total</i>)</td>
            <td style="text-align: right;font-size: 11px;font-weight: bold;"><?php echo System::display_number(round([[=total=]],0)); ?></td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr style="">
            <td>[[.total_in_word.]]<br />(<i>In Word</i>)</td>
            <td style="border-bottom: 1px dashed #CCCCCC;">[[|sum_total_in_word|]]</td>
        </tr>
        <tr style="">
            <td>[[.payment_method.]]<br />(<i>Payment Method</i>)</td>
            <td>
                <table cellSpacing="0" cellpadding="3">
                    <!--LIST:payment_list-->
                    <tr style="border-bottom: 1px dashed #CCCCCC;">
                        <td>[[|payment_list.payment_type_name|]]</td>
                        <td><?php echo System::display_number([[=payment_list.amount=]]); ?></td>
                    </tr>
                    <!--/LIST:payment_list-->
                </table>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr style="border-top: 1px solid #CCCCCC; ">
            <td style="width: 170px;">[[.room_no.]] <br /> <i>Room No</i></td>
            <td>
                
            </td>
            <td style="width: 120px;">[[.membership_id.]] <br /> <i>Membership ID</i></td>
            <td></td>
        </tr>
        <tr style="border-top: 1px solid #CCCCCC; ">
            <td>[[.guests_name.]] (<i>[[.block_letter.]]</i>) <br /> <i>Guest's Name (Block Letter)</i></td>
            <td style="text-transform: uppercase;"></td>
            <td>[[.guest_signature_1.]] <br /> <i>[[.guest_signature_and_full_name.]]</i></td>
            <td></td>
        </tr>
    </table>
</div>