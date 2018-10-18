<form id="from_print" method="POST" name="AccessControlHistoryForm">
    <table style="width: 100%;">
        <tr>
            <td style="width: 250px;">
                <p><?php echo HOTEL_NAME; ?></p>
                <p><?php echo HOTEL_ADDRESS; ?></p>
                <p><?php echo HOTEL_PHONE; ?> - <?php echo HOTEL_FAX; ?></p>
                <p><?php echo HOTEL_WEBSITE; ?></p>
            </td>
            <td style="text-align: center;"><h1>[[.member_level_discount_report.]]</h1></td>
            <td style="width: 250px;">
            </td>
        </tr>
        <tr id="tr_search">
            <td colspan="3" style="text-align: center;">
                <table>
                    <tr>
                        <td>[[.from_date.]]: <input name="from_date" type="text" id="from_date" style="padding: 5px; width: 80px;" /></td>
                        <td>[[.to_date.]]: <input name="to_date" type="text" id="to_date" style="padding: 5px; width: 80px;" /></td>
                        <td>[[.access_pin_service.]]: <select name="access_pin_service" id="access_pin_service" style="padding: 5px;"></select></td>
                        <td>[[.member_level.]]: <select name="member_level" id="member_level" style="padding: 5px;"></select></td>
                        <td>[[.is_parent.]]: <select name="is_parent" id="is_parent" style="padding: 5px;"></select></td>
                        <td><input name="do_search" value="[[.search.]]" type="submit" style="padding: 5px;" /></td>
                        <td><button id="export" >[[.export_excel.]]</button></td>
                        <td><button id="print_report" >[[.print.]]</button></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php $stt = 1; ?>
    <table id="tblExport" cellpadding="5" cellspacing="5" border="1" bordercolor="#DDDDDD" style="width: 100%; margin: 10px auto; border-collapse: collapse;">
        <tr style="text-align: center; background: #EEEEEE;">
            <th>[[.stt.]]</th>
            <th>[[.member_level.]]</th>
            <th>[[.access_pin_service.]]</th>
            <th>[[.card_type.]]</th>
            <th>[[.code.]]</th>
            <th>[[.title.]]</th>
            <th>[[.description.]]</th>
            <th>[[.num_people.]]</th>
            <th>[[.start_date.]]</th>
            <th>[[.end_date.]]</th>
        </tr>
        <!--LIST:items-->
            <tr>
                <td style="text-align: center;"><?php echo $stt++; ?></td>
                <td style="text-align: center;">[[|items.member_level_name|]]</td>
                <td style="text-align: center;">[[|items.access_control_name|]]</td>
                <td style="text-align: center;"><?php if([[=items.member_discount_is_parent=]]==''){ echo Portal::language('all'); }elseif([[=items.member_discount_is_parent=]]=='SON'){ echo Portal::language('son_card'); }else{ echo Portal::language('parent_card'); } ?></td>
                <td style="text-align: center;">[[|items.member_discount_code|]]</td>
                <td style="text-align: center;">[[|items.member_discount_title|]]</td>
                <td style="text-align: center;">[[|items.member_discount_description|]]</td>
                <td style="text-align: center;">[[|items.member_discount_operator|]] [[|items.member_discount_num_people|]]</td>
                <td style="text-align: center;">[[|items.start_date|]]</td>
                <td style="text-align: center;">[[|items.end_date|]]</td>
            </tr>
        <!--/LIST:items-->
    </table>
</form>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery("#tr_search").remove();
                jQuery("#tblExport").battatech_excelexport({
                    containerid: "tblExport"
                   , datatype: 'table'
                });
                location.reload();
            });
            jQuery("#print_report").click(function(){
                var user ='<?php echo User::id(); ?>';
                jQuery("#tr_search").remove();
                printWebPart('from_print',user);
                location.reload();
            });
        }
    );
</script>