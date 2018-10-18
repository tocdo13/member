<style>
    .simple-layout-middle{width:100%;}
    div {
        margin: 0px;
        padding: 0px;
    }
    #MiceReservationBody{
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
            <th style="text-align: center;"><h1 style="text-transform: uppercase;">[[.mice_revenue_reports.]]</h1></th>
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
                <th>[[.stt.]]</th>
                <th>[[.MICE.]]</th>
                <th>[[.room_charge.]]</th>
                <th>[[.service_room_charge.]]</th>
                <th>[[.bar.]]</th>
                <th>[[.hill_coffee.]]</th>
                <th>[[.karaoke.]]</th>
                <th>[[.event.]]</th>
                <th>[[.ticket.]]</th>
                <th>[[.service_other.]]</th>
                <th>[[.tax.]]</th>
                <th>[[.extra_vat.]]</th>
                <th>[[.total.]]</th>
                <th>[[.deposit.]]</th>
                <th>[[.total_payment.]]</th>
                <th>[[.paymented.]]</th>
                <th>[[.debit.]]</th>
                <th>[[.remain_traveller.]]</th>
            </tr>
            <?php 
                $stt=1; 
            ?>
            <!--LIST:items-->
            <tr style="text-align: right;">
                <td style="text-align: center;"><?php echo $stt++; ?></td>
                <td style="text-align: center;"><a href="?page=mice_reservation&cmd=edit&id=[[|items.id|]]" target="_blank">[[|items.id|]]</a></td>
                <td><?php echo ([[=items.room_charge=]]*1)>0?System::display_number([[=items.room_charge=]]*1):(System::display_number([[=items.room_charge=]]*(-1))>0?System::display_number([[=items.room_charge=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.service_charge_room=]]*1)>0?System::display_number([[=items.service_charge_room=]]*1):(System::display_number([[=items.service_charge_room=]]*(-1))>0?System::display_number([[=items.service_charge_room=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.bar=]]*1)>0?System::display_number([[=items.bar=]]*1):(System::display_number([[=items.bar=]]*(-1))>0?System::display_number([[=items.bar=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.hill_coffee=]]*1)>0?System::display_number([[=items.hill_coffee=]]*1):(System::display_number([[=items.hill_coffee=]]*(-1))>0?System::display_number([[=items.hill_coffee=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.karaoke=]]*1)>0?System::display_number([[=items.karaoke=]]*1):(System::display_number([[=items.karaoke=]]*(-1))>0?System::display_number([[=items.karaoke=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.event=]]*1)>0?System::display_number([[=items.event=]]*1):(System::display_number([[=items.event=]]*(-1))>0?System::display_number([[=items.event=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.ticket=]]*1)>0?System::display_number([[=items.ticket=]]*1):(System::display_number([[=items.ticket=]]*(-1))>0?System::display_number([[=items.ticket=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.service_other=]]*1)>0?System::display_number([[=items.service_other=]]*1):(System::display_number([[=items.service_other=]]*(-1))>0?System::display_number([[=items.service_other=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.vat=]]*1)>0?System::display_number([[=items.vat=]]*1):(System::display_number([[=items.vat=]]*(-1))>0?System::display_number([[=items.vat=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.extra_vat=]]*1)>0?System::display_number([[=items.extra_vat=]]*1):(System::display_number([[=items.extra_vat=]]*(-1))>0?System::display_number([[=items.extra_vat=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.total=]]*1)>0?System::display_number([[=items.total=]]*1):(System::display_number([[=items.total=]]*(-1))>0?System::display_number([[=items.total=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.deposit=]]*1)>0?System::display_number([[=items.deposit=]]*1):(System::display_number([[=items.deposit=]]*(-1))>0?System::display_number([[=items.deposit=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.total_payment=]]*1)>0?System::display_number([[=items.total_payment=]]*1):(System::display_number([[=items.total_payment=]]*(-1))>0?System::display_number([[=items.total_payment=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.payment=]]*1)>0?System::display_number([[=items.payment=]]*1):(System::display_number([[=items.payment=]]*(-1))>0?System::display_number([[=items.payment=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.debit=]]*1)>0?System::display_number([[=items.debit=]]*1):(System::display_number([[=items.debit=]]*(-1))>0?System::display_number([[=items.debit=]]*(-1)):''); ?></td>
                <td><?php echo ([[=items.remain_traveller=]]*1)>0?System::display_number([[=items.remain_traveller=]]*1):(System::display_number([[=items.remain_traveller=]]*(-1))>0?System::display_number([[=items.remain_traveller=]]*(-1)):''); ?></td>
            </tr>
            <!--/LIST:items-->
            <tr style="text-align: right; font-weight: bold;">
                <td colspan="2">[[.total.]]</td>
                <td><?php echo System::display_number([[=grand_total=]]['room_charge']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['service_charge_room']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['bar']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['hill_coffee']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['karaoke']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['event']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['ticket']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['service_other']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['vat']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['extra_vat']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['total']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['deposit']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['total_payment']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['payment']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['debit']); ?></td>
                <td><?php echo System::display_number([[=grand_total=]]['remain_traveller']); ?></td>
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