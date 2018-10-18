<style>
    @media print {
        .bill_input {
            display: none;
        }
    } 
    table tr td {
        font-size: 12px;
    }
</style>
<div style="width: 90%; height: auto; margin:0px auto; padding: 0px;">
    <center><img src="<?php echo HOTEL_LOGO; ?>" width="200px" /></center>
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr>
            <td style="">
                [[.date_check_no.]] (<i>Check No</i>): <?php echo [[=payment_time=]]!=''?date('d/m/Y',[[=payment_time=]]):date('d/m/Y',[[=create_time=]]); ?>
            </td>
            <td style="text-align: right;">
                [[.customer_name.]] (<i>Customer</i>): [[|customer_name|]]
            </td>
        </tr>
        <tr>
            <td style="">
                [[.number_check_no.]] (<i>Check No</i>): <?php echo [[=bill_id=]]!=''?'BILL-'.[[=bill_id=]]:'#'.[[=id=]]; ?>
            </td>
            <td style="text-align: right;">
                [[.mice.]]: MICE+[[|mice_reservation_id|]]
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 10px;" border="1" bordercolor="#CCCCCC" cellSpacing="0" cellpadding="3">
        <tr style="text-align: center; ">
            <td>[[.location_pin.]] <br /> (<i>Location</i>)</td>
            <td>[[.table_res.]] <br /> (<i>Table</i>)</td>
            <td>[[.guest_name.]] <br /> (<i>Guest name</i>)</td>
            <td>[[.number_of_guests.]] <br /> (<i>No of Guests</i>)</td>
            <td>[[.time_in.]] <br /> (<i>In</i>)</td>
            <td>[[.time_out.]] <br /> (<i>Out</i>)</td>
            <td>[[.waiters.]] <br /> (<i>Waiters</i>)</td>
            <td>[[.cashier.]] <br /> (<i>Cashier</i>)</td>
        </tr>
        <tr style="text-align: center;">
            <td><br /></td>
            <td></td>
            <td>[[|traveller_name|]]</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 10px;" border="1" bordercolor="#CCCCCC" cellSpacing="0" cellpadding="3">
        <tr style="text-align: center; ">
            <td>[[.stt.]] <br /> (<i>No</i>)</td>
            <td>[[.puschases_and_service.]] <br /> (<i>Description</i>)</td>
            <td>[[.unit.]] <br /> (<i>Unit</i>)</td>
            <td>[[.quantity.]] <br /> (<i>Quantity</i>)</td>
            <td>[[.unit_price.]] <br /> (<i>Unit Price</i>)</td>
            <td>[[.amount.]] <br /> (<i>Amount</i>)</td>
        </tr>
        <!--LIST:items-->
        <tr>
            <td style="text-align: center;">[[|items.stt|]]</td>
            <td>[[|items.description|]]</td>
            <td></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=items.amount=]],0)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=items.amount=]],0)); ?></td>
        </tr>
        <!--/LIST:items-->
    </table>
    
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr style="">
            <td></td>
            <td></td>
            <td >[[.sub_total.]] (<i>Sub Total</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=sub_total=]],0)); ?></td>
        </tr>
        <tr style="">
            <td></td>
            <td></td>
            <td>[[.discount_1.]] (<i>Discount</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=discount=]],0)); ?></td>
        </tr>
        <tr style="">
            <td>[[.service_charge_rate.]] (<i>Service Charge Rate</i>)</td>
            <td></td>
            <td>[[.service_charge.]] (<i>Service Charge</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=service_charge=]],0)); ?></td>
        </tr>
        <tr style="">
            <td>[[.vat_rate.]] (<i>VAT Rate</i>)</td>
            <td></td>
            <td>[[.value_added_tax.]] (<i>Value Added Tax</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=vat_charge=]],0)); ?></td>
        </tr>
        <?php if(round([[=extra_amount=]],0)!=0 OR round([[=extra_vat=]],0)!=0){ ?>
        <tr style="">
            <td>[[.extra_amount.]] (<i></i>)</td>
            <td><?php echo System::display_number(round([[=extra_amount=]],0)); ?></td>
            <td>[[.extra_vat.]] (<i></i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=extra_vat=]],0)); ?></td>
        </tr>
        <?php } ?>
        <tr style="">
            <td></td>
            <td></td>
            <td>[[.deposit.]] (<i>Deposit</i>)</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=deposit=]],0)); ?></td>
        </tr>
        <tr style="">
            <td></td>
            <td></td>
            <td style="font-size: 11px;font-weight: bold;">[[.total_amount.]] (<i>Total</i>)</td>
            <td style="text-align: right;font-size: 11px;font-weight: bold;"><?php echo System::display_number(round([[=total=]]-[[=deposit=]],0)); ?></td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr style="">
            <td>[[.total_in_word.]]<br />(<i>In Word</i>)</td>
            <td style="border-bottom: 1px dashed #CCCCCC;">[[|total_amount_in_word|]]</td>
        </tr>
        <tr style="">
            <td>[[.payment_method.]]<br />(<i>Payment Method</i>)</td>
            <td>
                <table cellSpacing="0" cellpadding="3">
                    <!--LIST:payment-->
                    <tr style="border-bottom: 1px dashed #CCCCCC;">
                        <td>[[|payment.payment_type_name|]]</td>
                        <td><?php echo System::display_number([[=payment.amount=]]); ?></td>
                    </tr>
                    <!--/LIST:payment-->
                </table>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 10px;" cellSpacing="0" cellpadding="3">
        <tr style="border-top: 1px solid #CCCCCC; ">
            <td style="width: 50%;" colspan="2">[[.room_no.]] <br /> <i>Room No</i></td>
            
            <td style="width: 50%;" colspan="2">[[.membership_id.]] <br /> <i>Membership ID</i></td>
            
        </tr>
        <tr style="border-top: 1px solid #CCCCCC; ">
            <td>[[.guests_name.]] (<i>[[.block_letter.]]</i>) <br /> <i>Guest's Name (Block Letter)</i></td>
            <td style="text-transform: uppercase;"></td>
            <td>[[.guest_signature_1.]] <br /> <i>[[.guest_signature_and_full_name.]]</i></td>
            <td></td>
        </tr>
    </table>
</div>