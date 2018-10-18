<style>
*{
    line-height: 25px;
}
</style>
<div id="content_report" style="width: 900px; margin: 5px auto;">
    <table cellSpacing=0 border=1 style="width: 100%; clear: both; margin: 0px auto;">
        <tr style="background: #ccc;">
            <th style="text-align: center;">[[.stt.]]</th>
            <th style="text-align: center;">[[.nationality.]]</th>
            <?php if($_REQUEST['date_from']==$_REQUEST['date_to']){ ?>
            <th style="text-align: center;">[[.sammary_traveller_in_date.]] <?php echo $_REQUEST['date_from']; ?> </th>
            <?php }else{ ?>
            <th style="text-align: center;">[[.sammary_traveller_in_date.]] <?php echo $_REQUEST['date_from']; ?> </th>
            <th style="text-align: center;">[[.sammary_traveller_from_date.]] <?php echo $_REQUEST['date_from']; ?> [[.date_to.]] <?php echo $_REQUEST['date_to']; ?></th>
            <?php } ?>
        </tr>
        <!--LIST:items-->
        <tr>
            <td style="text-align: center;">[[|items.id|]]</td>
            <td style="text-align: center;">[[|items.country|]]</td>
            <?php if($_REQUEST['date_from']==$_REQUEST['date_to']){ ?>
            <td style="text-align: center;">[[|items.count_traveller|]]</td>
            <?php }else{ ?>
            <td style="text-align: center;">[[|items.count_today|]]</td>
            <td style="text-align: center;">[[|items.count_traveller|]]</td>
            <?php } ?>
        </tr>
        <!--/LIST:items-->
    </table>
    <div style="width: 100%; text-align: center; clear: both; margin: 5px auto;">[[.page.]][[|num_page|]]/[[|total_page|]]</div>
</div>