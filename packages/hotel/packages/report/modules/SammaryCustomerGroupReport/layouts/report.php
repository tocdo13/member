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
<form name="SammaryCustomerGroupReportForm" method="post" >
<table id="export">
    <tr>
        <td>
        <div id="header_report" style="margin: 5px auto; width: 90%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 55px; text-align: center;"><div style="border-radius: 50%; width: 50px; height: 50px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 50px; height: auto;" /></div></td>
            <td style="width: 200px; text-align: left;">
                <p style="text-transform: uppercase;"><span style="font-size: 13px; font-weight: bold;"><?php echo HOTEL_NAME; ?></span><br />
                    <i>[[.room_sale.]]</i>
                </p>
            </td>
            <td style="text-align: center;">
                <h1 style="text-transform: uppercase; font-size: 19px;">[[.summary_customer_group_report.]]</h1>
                <i>[[.date_from.]] <?php echo $_REQUEST['date_from']; ?> [[.date_to.]] <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 255px; text-align: right;">
                <p>[[.saler.]]: <i>[[.all.]]</i><br />
                    [[.customer_group.]]: <i><?php echo $_REQUEST['group_id']; ?></i>
                </p>
            </td>
        </tr>
    </table>
</div>
<!-- end header --!>

<div style="text-align: center;"> <input name="export_repost" type="submit" id="export_repost"  value="[[.export.]]"  /></div>
<div id="report_content" style="width: 90%; margin: 10px auto;" >
    <table border=1 cellSpacing=0 style="width: 100%;"  >
        <tr style="background: #000000;">
            <th rowspan="2" style="color: #fff; text-align: center;">[[.stt.]]</th>
            <th rowspan="2" style="color: #fff; text-align: center;">[[.company_name.]]</th>
            <th colspan="3" style="color: #fff; text-align: center;">[[.quantity.]]</th>
            <th rowspan="2" style="color: #fff; text-align: center;">[[.revenue.]]</th>
        </tr>
        <tr style="background: #000000;">
            <th style="color: #fff; text-align: center;">[[.number_night.]]</th>
            <th style="color: #fff; text-align: center;">[[.number_adult.]]</th>
            <th style="color: #fff; text-align: center;">[[.number_child.]]</th>
        </tr>
        <?php $stt = 0; $total_room = 0; $total_child=0; $total_adult=0; $total_amount = 0; ?> 
        <!--LIST:items-->
            <tr style="background: silver; font-weight: bold;">
                <td colspan="6">[[|items.name|]]</td>
            </tr>
            <!--LIST:items.child-->
                <tr>
                    <td><?php echo ++$stt; ?></td>
                    <td>[[|items.child.name|]]</td>
                    <td style="text-align: center;">[[|items.child.total_room|]]</td>
                    <td style="text-align: center;">[[|items.child.total_adult|]]</td>
                    <td style="text-align: center;">[[|items.child.total_child|]]</td>
                    <td style="text-align: right; font-weight: bold;" class="change_numTr"><?php echo System::display_number([[=items.child.total_amount=]]); ?></td>
                </tr>
            <!--/LIST:items.child-->
            <tr style="background: #dddddd; font-weight: bold;">
                <td colspan="2" style="text-align: right;">[[.total_amount.]] [[|items.name|]]</td>
                <td style="text-align: center;">[[|items.total_room|]]<?php $total_room += [[=items.total_room=]]; ?></td>
                <td style="text-align: center;">[[|items.total_adult|]]<?php $total_adult += [[=items.total_adult=]]; ?></td>
                <td style="text-align: center;">[[|items.total_child|]]<?php $total_child += [[=items.total_child=]]; ?></td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number([[=items.total_amount=]]); $total_amount += [[=items.total_amount=]]; ?></td>
            </tr>
        <!--/LIST:items-->
        <tr style="background: silver; height: 30px; font-weight: bold;">
            <td colspan="2" style="text-align: center;">[[.total.]]</td>
            <td style="text-align: center;"><?php echo $total_room; ?></td>
            <td style="text-align: center;"><?php echo $total_adult; ?></td>
            <td style="text-align: center;"><?php echo $total_child; ?></td>
            <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($total_amount); ?></td>
        </tr>
    </table>
</div><!-- end report --!>


<div style="margin-left: 50px;">
    [[.description.]]: <br />
    -(1) [[.description_summary_customer_report_1.]]<br />
    -(2) [[.description_summary_customer_report_2.]]<br />
   -(3) [[.description_summary_customer_report_3.]]<br />
</div>
</form>
</td>
    </tr>
</table>

<script>
    jQuery("#export_repost").click(function(){
        
        jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())); 
        });
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table' 
        });
    })

</script>
