<style>
    *{
        line-height: 20px;
    }
    #report_content table tr{
        background: #fff;
    }
    #report_content table tr:hover{
        background: #eee;
    }
</style>
<div style="text-align: center;width: 90%; margin: 10px auto;">
<form name="SummaryCustomerReportForm" method="post">
<button id="export">[[.export.]]</button>
</div>
<div id="report_content" style="width: 90%; margin: 10px auto;" >
    <table  border=1 cellSpacing=0 style="width: 100%;" >
        <tr style="background: #000;">
            <th rowspan="2" style="color: #fff; text-align: center;">[[.stt.]]</th>
            <th rowspan="2" style="color: #fff; text-align: center;">[[.company_name.]]</th>
            <th colspan="3" style="color: #fff; text-align: center;">[[.quantity.]]</th>
            <th rowspan="2" style="color: #fff; text-align: center;">[[.revenue.]]</th>
        </tr>
        <tr style="background: #000;">
            <th style="color: #fff; text-align: center;">[[.room.]]</th>
            <th style="color: #fff; text-align: center;">[[.adult.]]</th>
            <th style="color: #fff; text-align: center;">[[.child.]]</th>
        </tr>
        <!--LIST:items-->
        <tr>
            <td style="text-align: center;">[[|items.id|]]</td>
            <td style="text-align: left; font-weight: bold;">[[|items.company_name|]]</td>
            <td style="text-align: center;">[[|items.room_count|]]</td>
            <td style="text-align: center;">[[|items.sum_adult|]]</td>
            <td style="text-align: center;">[[|items.sum_child|]]</td>
            <td style="text-align: right;" class="change_numTr" ><?php echo System::display_number([[=items.price=]]); ?></td>
        </tr>
        <!--/LIST:items-->
        <?php if([[=num_page=]]==[[=total_page=]]){ ?>
        <tr style="background: #000;">
            <th colspan="2" style="text-align: right; color: #fff;">[[.total_amount.]]:</th>
            <th style="color: #fff; text-align: center;"><?php echo $_REQUEST['sammary']['total_room']; ?></th>
            <th style="color: #fff; text-align: center;"><?php echo $_REQUEST['sammary']['total_adult']; ?></th>
            <th style="color: #fff; text-align: center;"><?php echo $_REQUEST['sammary']['total_child']; ?></th>
            <th style="color: #fff; text-align: right;" class="change_numTr"><?php echo System::display_number($_REQUEST['sammary']['total_price']); ?></th>
        </tr>
        <?php } ?>
    </table>
<div style="width: 100px; text-align: center; margin: 0px auto;"><?php if(isset([[=num_page=]])){ ?>[[.page.]][[|num_page|]]/[[|total_page|]]<?php } ?></div>
</div><!-- end report --!>
</form>
</td>
    </tr>
</table>
<script>
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery('.change_numTr').each(function(){
            jQuery(this).html(to_numeric(jQuery(this).html()));
        });
            jQuery('.class_none').remove();
            jQuery('#export').remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);
</script>