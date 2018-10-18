<style>
    #report_content table tr{
        background: #fff;
    }
    #report_content table tr:hover{
        background: #eee;
    }
</style>
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
                <h1 style="text-transform: uppercase;">[[.detail_customer_report.]]</h1>
                <i>[[.date_from.]] <?php echo $_REQUEST['date_from']; ?> [[.date_to.]] <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 255px; text-align: right;">
                <p> 
                    [[.saler.]]:<i> [[.all.]]</i>
                </p>
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
            </td>
        </tr>
    </table>
</div>
<button id="export">[[.export.]]</button>
<div id="report_content" style="width: 90%; margin: 10px auto;">
    <table id="tblExport" cellSpacing=0 border=1 style="width: 100%;">
        <tr style="background: #000;">
            <th rowspan="2" style="text-align: center; color: #fff;">[[.stt.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.company_name.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.date.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.saler.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.room_type.]]</th>
            <th colspan="4" style="text-align: center; color: #fff;">[[.quantity.]]</th>
            <th rowspan="2" style="text-align: center; color: #fff;">[[.revenue.]]</th>
        </tr>
        <tr style=" background: #000;">
            <th style="text-align: center; color: #fff;">[[.room.]]</th>
            <th style="text-align: center; color: #fff;">[[.adult.]]</th>
            <th style="text-align: center; color: #fff;">[[.child.]]</th>
            <th style="text-align: center; color: #fff;">[[.child_under_five.]]</th>
        </tr>
        <?php $company = ""; $a=0; $b=0; $sum_room = 0; $sum_adult = 0; $sum_child = 0; $sum_child_under_five = 0; $sum_price = 0; $company_date = ""; $company_date_room = "";
         ?>
        <!--LIST:count_customer-->
        <!--LIST:items-->
            <?php if([[=count_customer.company=]]==[[=items.company_name=]]){
                        if($a==0){ $a+=1; $sum_room += [[=items.room_count=]]; $sum_adult += [[=items.sum_adult=]]; $sum_child += [[=items.sum_child=]]; $sum_child_under_five+=[[=items.sum_child_under_five=]]; $sum_price += [[=items.price=]];
            ?>
            <tr>
                <td style="text-align: center;">[[|items.stt|]]</td>
                <td rowspan="<?php echo [[=count_customer.num=]]; ?>" style="text-align: left ; font-size: 15px; line-height: 20px; font-weight: bold;">[[|count_customer.company|]]</td>
                <?php if( $company_date != [[=items.company_name=]].'_'.[[=items.in_date=]] ){ $company_date=[[=items.company_name=]].'_'.[[=items.in_date=]];  ?>
                <td rowspan="[[|items.num|]]" style="text-align: center;">[[|items.in_date|]]</td>
                <td style="text-align: center;">[[|items.sale|]]</td>
                <td style="text-align: center;">[[|items.room_type|]]</td>
                <td style="text-align: center;">[[|items.room_count|]]</td>
                <td style="text-align: center;">[[|items.sum_adult|]]</td>
                <td style="text-align: center;">[[|items.sum_child|]]</td>
                <td style="text-align: center;">[[|items.sum_child_under_five|]]</td>
                <td style="text-align: right;"><?php echo System::display_number([[=items.price=]]); ?></td>
                <?php } ?>
            </tr>
                <?php if([[=count_customer.num=]]==1){ ?>
                  <tr style="background: #eee;">
                    <th></th>
                    <th colspan="4" style="text-align: right;">[[.sum.]]:</th>
                    <th style="text-align: center;"><?php echo $sum_room; ?></th>
                    <th style="text-align: center;"><?php echo $sum_adult; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child_under_five; ?></th>
                    <th style="text-align: right;"><?php echo System::display_number($sum_price); ?></th>
                </tr>  
                    
                <?php } ?>
            <?php }else{ $a+=1; $sum_room += [[=items.room_count=]]; $sum_adult += [[=items.sum_adult=]]; $sum_child += [[=items.sum_child=]]; $sum_child_under_five+= [[=items.sum_child_under_five=]]; $sum_price += [[=items.price=]];
            ?>
            <tr>
                <td style="text-align: center;">[[|items.stt|]]</td>
                <td rowspan="[[|items.num|]]" style="text-align: center;">[[|items.in_date|]]</td>
                <td style="text-align: center;">[[|items.sale|]]</td>
                <td style="text-align: center;">[[|items.room_type|]]</td>
                <td style="text-align: center;">[[|items.room_count|]]</td>
                <td style="text-align: center;">[[|items.sum_adult|]]</td>
                <td style="text-align: center;">[[|items.sum_child|]]</td>
                <td style="text-align: center;">[[|items.sum_child_under_five|]]</td>
                <td style="text-align: right;"><?php echo System::display_number([[=items.price=]]); ?></td>
               
            </tr>
            <?php if($a == [[=count_customer.num=]] ){ ?>
                <tr style="background: #eee;">
                    <th></th>
                    <th colspan="4" style="text-align: right;">[[.sum.]]:</th>
                    <th style="text-align: center;"><?php echo $sum_room; ?></th>
                    <th style="text-align: center;"><?php echo $sum_adult; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child_under_five; ?></th>
                    <th style="text-align: right;"><?php echo System::display_number($sum_price); ?></th>
                </tr>
            <?php } ?>
            <?php  
            }
            ?>
            <?php
            }else{ ?>   
            <?php $a=0; $a=0; $b=0; $sum_room = 0; $sum_adult = 0; $sum_child = 0; $sum_child_under_five=0; $sum_price = 0; } ?>
        <!--/LIST:items-->
        <!--/LIST:count_customer-->
        <tr style="background: #000; height: 25px;">
            <th colspan="5" style="text-align: right; color:#fff;" >[[.total.]]</th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_room']; ?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_adult']; ?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_child']; ?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_child_under_five']; ?></th>
            <th style="text-align: right; color:#fff;"><?php echo System::display_number($_REQUEST['total']['total_price']); ?></th>
        </tr>
    </table>

</div>
<?php //System::debug([[=count_customer=]]); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    });
</script>
