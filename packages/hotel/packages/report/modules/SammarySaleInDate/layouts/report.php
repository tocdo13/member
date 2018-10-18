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
<style>
/*full m�n h?nh
.simple-layout-middle{width:100%;}*/
</style>
<button id="export">[[.export.]]</button>
<div id="report_content" style="width: 90%; margin: 10px auto;">
    <table id="tblExport" cellSpacing=0 border=1 style="width: 100%;">
        <tr style="background: #000;">
            <th rowspan="2" style="text-align: center; color: #fff; width: 40px;">[[.stt.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.date.]]</th>
            <th colspan="3" style="text-align: center; color: #fff;">[[.quantity.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.revenue.]]</th>
        </tr>
        <tr style=" background: #000;">
            <th style="text-align: center; color: #fff;">[[.room.]]</th>
            <th style="text-align: center; color: #fff;">[[.adult.]]</th>
            <th style="text-align: center; color: #fff;">[[.child.]]</th>
        </tr>
        <!--LIST:items-->
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: center;">[[|items.in_date|]]</td>
                <td style="text-align: center;">[[|items.room_count|]]</td>
                <td style="text-align: center;">[[|items.sum_adult|]]</td>
                <td style="text-align: center;">[[|items.sum_child|]]</td>
                <td style="text-align: right;"><?php echo System::display_number([[=items.price=]]); ?></td>
            </tr>
        <!--/LIST:items-->
        <?php if([[=num_page=]]==[[=total_page=]]){ ?>
        <tr style="background: #000;">
            <th colspan="2" style="text-align: right; color: #fff;">[[.total_amount.]]</th>
            <th style="text-align: center; color: #fff;"><?php echo $_REQUEST['sammary']['total_room']; ?></th>
            <th style="text-align: center; color: #fff;"><?php echo $_REQUEST['sammary']['total_adult']; ?></th>
            <th style="text-align: center; color: #fff;"><?php echo $_REQUEST['sammary']['total_child']; ?></th>
            <th style="text-align: right; color: #fff;"><?php echo System::display_number($_REQUEST['sammary']['total_price']); ?></th>
        </tr>
        <?php } ?>
    </table>
<div style="width: 100px; text-align: center; margin: 0px auto;"><?php if(isset([[=num_page=]])){ ?>[[.page.]][[|num_page|]]/[[|total_page|]]<?php } ?></div>
</div>
<?php //System::debug([[=count_customer=]]); ?>
<script>
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);
</script>
