<style>
    div {
        margin: 0px;
        padding: 0px;
    }
    .show-print {
        display: none;
    }
    @media print {
        .show-print {
            display: block;
        }
        .no-print {
            display: none;
        }
    }
</style>

<div id="print_report">
    <table style="width: 98%; margin: 0px auto;" cellpadding="5" cellspacing="0">
        <tr>
            <th style="text-align: center;">
                <h3 style="text-transform: uppercase;">[[.history_change_room.]]</h3>
            </th>
        </tr>
        <tr class="no-print">
            <th>
                <form name="ReportHistoryChangeRoomForm" method="POST">
                    <table style="margin: 0px auto;" cellpadding="5" cellspacing="0">
                        <tr>
                            <td>[[.portal.]]: <select name="portal_id" id="portal_id" style="padding: 5px;"  class="no-print"></select><label class="show-print">[[|portal_name|]]</label></td>
                            <td>[[.area.]]: <select name="room_level_id" id="room_level_id" style="padding: 5px;"  class="no-print"></select><label class="show-print">[[|room_level_name|]]</label></td>
                            <td>[[.room.]]: <select name="room_id" id="room_id" style="padding: 5px;"  class="no-print"></select><label class="show-print">[[|room_name|]]</label></td>
                            <td>[[.from_date.]]: <input name="from_date" type="text" id="from_date" style="padding: 5px; width: 80px;"  class="no-print" /><label class="show-print">[[|from_date|]]</label></td>
                            <td>[[.to_date.]]: <input name="to_date" type="text" id="to_date" style="padding: 5px; width: 80px;"  class="no-print" /><label class="show-print">[[|to_date|]]</label></td>
                            <td>
                                <button onclick="ReportHistoryChangeRoomForm.submit();" style="padding: 5px;" class="no-print"><i class="fa fa-bar-chart fa-fw"></i>[[.view_report.]]</button>
                                <button onclick="var user ='<?php echo User::id(); ?>';printWebPart('printer',user);" style="padding: 5px;" class="no-print"><i class="fa fa-print fa-fw"></i>[[.print_report.]]</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </th>
        </tr>
        <tr>
            <td>
                <table style="margin: 0px auto;" cellpadding="15" cellspacing="0" border="1" bordercolor="#555555">
                    <tr>
                        <th>[[.in_date.]]</th>
                        <th>[[.room.]]</th>
                        <th>[[.description.]]</th>
                        <!--<th>[[.time_action.]]</th>-->
                    </tr>
                    <!--LIST:items-->
                    <tr>
                        <?php $rows = sizeof([[=items.log=]]); ?>
                        <td rowspan="<?php echo $rows; ?>">[[|items.in_date|]]</td>
                        <?php $child = ''; ?>
                        <!--LIST:items.log-->
                        <?php $child = [[=items.log.id=]]; ?>
                            <td>[[|items.log.id|]]</td>
                            <td>[[|items.log.des|]]</td>
                            <!--<td rowspan="<?php //echo $rows; ?>">[[|items.start_date|]] - <?php //echo ([[=items.end_date=]]!='')?[[=items.end_date=]]:Portal::language('now'); ?></td>-->
                        </tr>
                        <?php break; ?>
                        <!--/LIST:items.log-->
                        <!--LIST:items.log-->
                        <?php if($child != [[=items.log.id=]]){ ?>
                        <tr>
                            <td>[[|items.log.id|]]</td>
                            <td>[[|items.log.des|]]</td>
                        </tr>
                        <?php } ?>
                        <!--/LIST:items.log-->
                    <!--/LIST:items-->
                </table>
            </td>
        </tr>
    </table>
</div>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>