<style>
    .simple-layout-middle{width:100%;}
    div {
        margin: 0px;
        padding: 0px;
    }
    #MiceReservationBody {
        width: auto;
        height: auto;
        padding: 10px;
        margin: 5px auto;
        background: #FFFFFF;
        box-shadow: 0px 1px 4px 0px rgba(0,0,0,0.2);
    }
</style>
<div id="setup_report">
    <table style="width: 100%;">
        <tr>
            <th style="text-align: center;"><h1 style="text-transform: uppercase;">[[.collecting_reports_mice.]]</h1></th>
        </tr>
        <tr>
            <th style="text-align: center;">
                <form name="MiceReportSetupForm" method="POST">
                    [[.from_date.]]:
                        <input name="from_date" type="text" id="from_date" class="datainput" style="text-align: center; padding: 5px; font-weight: bold; width: 80px;" />
                        <label class="datalabel" style="display: none;">[[|from_date|]]</label>
                    [[.to_date.]]:
                        <input name="to_date" type="text" id="to_date" class="datainput" style="text-align: center; padding: 5px; font-weight: bold; width: 80px;" />
                        <label class="datalabel" style="display: none;">[[|to_date|]]</label>
                    <button onclick="MiceReportSetupForm.submit();" style="padding: 5px;" class="datainput"><i class="fa fa-bar-chart fa-fw"></i>[[.view_report.]]</button>
                    <button onclick="print_report();" style="padding: 5px;" class="datainput"><i class="fa fa-print fa-fw"></i>[[.print_report.]]</button>
                </form>
            </th>
        </tr>
    </table>
    <div id="MiceReservationBody">
        <table style="width: 100%; margin: 3px auto;" cellpadding="5" cellSpacing="0" border="1" bordercolor="#EEEEEE">
            <tr style="text-align: center;">
                <th rowspan="2">[[.stt.]]</th>
                <th rowspan="2">[[.invoice_number.]]</th>
                <th rowspan="2">[[.in_date.]]</th>
                <th rowspan="2">[[.MICE.]]</th>
                <th rowspan="2">[[.room_charge.]]</th>
                <th rowspan="2">[[.service_room_charge.]]</th>
                <th rowspan="2">[[.bar.]]</th>
                <th rowspan="2">[[.hill_coffee.]]</th>
                <th rowspan="2">[[.karaoke.]]</th>
                <th rowspan="2">[[.event.]]</th>
                <th rowspan="2">[[.ticket.]]</th>
                <th rowspan="2">[[.service_other.]]</th>
                <th rowspan="2">[[.tax.]]</th>
                <th rowspan="2">[[.extra_vat.]]</th>
                <th rowspan="2">[[.total.]]</th>
                <th rowspan="2">[[.deposit.]]</th>
                <th rowspan="2">[[.total_paymented.]]</th>
                <th colspan="7">[[.payment_type.]]</th>
                <th rowspan="2">[[.cashier.]]</th>
            </tr>
            <tr style="text-align: center;">
                <th>[[.refund.]]</th>
                <th>[[.foc.]]</th>
                <th>[[.bank_tranfer.]]</th>
                <th>[[.credit_card.]]</th>
                <th>[[.cash.]]</th>
                <th>[[.debit.]]</th>
                <th>[[.debit_refund.]]</th>
            </tr>
            <!--LIST:items-->
            <tr style="text-align: right;">
                <td style="text-align: center;">[[|items.stt|]]</td>
                <td style="text-align: center; width: 50px;"><a target="_blank" href="?page=mice_reservation&cmd=bill&invoice_id=[[|items.mice_invoice_id|]]">BILL-[[|items.id|]]</a></td>
                <td style="text-align: center;">[[|items.in_date|]]</td>
                <td style="text-align: center;"><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.mice_reservation_id|]]">[[|items.mice_reservation_id|]]</a></td>
                <td><?php echo [[=items.room_charge=]]!=0?System::display_number([[=items.room_charge=]]):''; ?></td>
                <td><?php echo [[=items.service_charge_room=]]!=0?System::display_number([[=items.service_charge_room=]]):''; ?></td>
                <td><?php echo [[=items.bar=]]!=0?System::display_number([[=items.bar=]]):''; ?></td>
                <td><?php echo [[=items.hill_coffee=]]!=0?System::display_number([[=items.hill_coffee=]]):''; ?></td>
                <td><?php echo [[=items.karaoke=]]!=0?System::display_number([[=items.karaoke=]]):''; ?></td>
                <td><?php echo [[=items.event=]]!=0?System::display_number([[=items.event=]]):''; ?></td>
                <td><?php echo [[=items.ticket=]]!=0?System::display_number([[=items.ticket=]]):''; ?></td>
                <td><?php echo [[=items.service_other=]]!=0?System::display_number([[=items.service_other=]]):''; ?></td>
                
                <td><?php echo [[=items.vat=]]!=0?System::display_number([[=items.vat=]]):''; ?></td>
                <td><?php echo [[=items.extra_vat=]]!=0?System::display_number([[=items.extra_vat=]]):''; ?></td>
                <td><?php echo [[=items.total_amount=]]!=0?System::display_number([[=items.total_amount=]]):''; ?></td>
                <td><?php echo [[=items.deposit=]]!=0?System::display_number([[=items.deposit=]]):''; ?></td>
                <td><?php echo System::display_number([[=items.total_payment=]]); ?></td>
                
                <td><?php echo [[=items.refund=]]!=0?System::display_number([[=items.refund=]]):''; ?></td>
                <td><?php echo [[=items.foc=]]!=0?System::display_number([[=items.foc=]]):''; ?></td>
                <td><?php echo [[=items.bank=]]!=0?System::display_number([[=items.bank=]]):''; ?></td>
                <td><?php echo [[=items.credit_card=]]!=0?System::display_number([[=items.credit_card=]]):''; ?></td>
                <td><?php echo [[=items.cash=]]!=0?System::display_number([[=items.cash=]]):''; ?></td>
                <td><?php echo [[=items.debit=]]!=0?System::display_number([[=items.debit=]]):''; ?></td>
                <td><?php echo [[=items.debit_refund=]]!=0?System::display_number([[=items.debit_refund=]]):''; ?></td>
                
                <td style="text-align: center;">[[|items.sales|]]</td>
            </tr>
            <!--/LIST:items-->
            <tr style="text-align: right; font-weight: bold;">
                <td colspan="4">[[.total.]]:</td>
                <td><?php echo [[=grand_total=]]['room_charge']!=0?System::display_number([[=grand_total=]]['room_charge']):''; ?></td>
                <td><?php echo [[=grand_total=]]['service_charge_room']!=0?System::display_number([[=grand_total=]]['service_charge_room']):''; ?></td>
                <td><?php echo [[=grand_total=]]['bar']!=0?System::display_number([[=grand_total=]]['bar']):''; ?></td>
                <td><?php echo [[=grand_total=]]['hill_coffee']!=0?System::display_number([[=grand_total=]]['hill_coffee']):''; ?></td>
                <td><?php echo [[=grand_total=]]['karaoke']!=0?System::display_number([[=grand_total=]]['karaoke']):''; ?></td>
                <td><?php echo [[=grand_total=]]['event']!=0?System::display_number([[=grand_total=]]['event']):''; ?></td>
                <td><?php echo [[=grand_total=]]['ticket']!=0?System::display_number([[=grand_total=]]['ticket']):''; ?></td>
                <td><?php echo [[=grand_total=]]['service_other']!=0?System::display_number([[=grand_total=]]['service_other']):''; ?></td>
                
                <td><?php echo [[=grand_total=]]['vat']!=0?System::display_number([[=grand_total=]]['vat']):''; ?></td>
                <td><?php echo [[=grand_total=]]['extra_vat']!=0?System::display_number([[=grand_total=]]['extra_vat']):''; ?></td>
                <td><?php echo [[=grand_total=]]['total_amount']!=0?System::display_number([[=grand_total=]]['total_amount']):''; ?></td>
                <td><?php echo [[=grand_total=]]['deposit']!=0?System::display_number([[=grand_total=]]['deposit']):''; ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['total_amount']-[[=grand_total=]]['deposit']); ?></td>
                
                <td><?php echo [[=grand_total=]]['refund']!=0?System::display_number([[=grand_total=]]['refund']):''; ?></td>
                <td><?php echo [[=grand_total=]]['foc']!=0?System::display_number([[=grand_total=]]['foc']):''; ?></td>
                <td><?php echo [[=grand_total=]]['bank']!=0?System::display_number([[=grand_total=]]['bank']):''; ?></td>
                <td><?php echo [[=grand_total=]]['credit_card']!=0?System::display_number([[=grand_total=]]['credit_card']):''; ?></td>
                <td><?php echo [[=grand_total=]]['cash']!=0?System::display_number([[=grand_total=]]['cash']):''; ?></td>
                <td><?php echo [[=grand_total=]]['debit']!=0?System::display_number([[=grand_total=]]['debit']):''; ?></td>
                <td><?php echo [[=grand_total=]]['debit_refund']!=0?System::display_number([[=grand_total=]]['debit_refund']):''; ?></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<script>
    jQuery("#chang_language").css('display','none');
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function print_report()
    {
        jQuery(".datalabel").css('display','');
        jQuery(".datainput").css('display','none');
        var user ='<?php echo User::id(); ?>';
        printWebPart('setup_report',user);
        jQuery(".datalabel").css('display','none');
        jQuery(".datainput").css('display','');
    }
</script>