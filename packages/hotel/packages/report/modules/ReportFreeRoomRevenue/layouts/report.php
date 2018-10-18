<style>
.simple-layout-middle{
    width: 100%;
}
#SearchForm{
    width: 850px;
}
input:hover{
    border: 1px dashed #333;
}
select:hover{
    border: 1px dashed #333;    
}
table{
    border-collapse: collapse;  
}
#content tr td tr th{
    line-height: 20px;
    text-align: center;
    font-size: 12px;
}
#content tr td tr td{
    line-height: 25px;
    font-size: 12px;
}
.customer_datalist{
    height:50px !important;
    max-height:80px !important;
    overflow: scroll;
}
@media print{
    #SearchForm{
        display: none;
    }
}
</style>
<table cellspacing="0" width="90%" style="margin: 0 auto;">
    <tr style="font-size:12px; font-weight:normal">
        <td align="left" width="80%">
            <strong><?php echo HOTEL_NAME;?></strong>
            <br />
            <strong><?php echo HOTEL_ADDRESS;?></strong>
        </td>
        <td align="right" style="padding-right:10px;" >
            <strong>[[.template_code.]]</strong>
            <br />
            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
        </td>
    </tr>
</table>
<table width="90%" style="margin: 0 auto;">
    <tr>
        <td>
            <h2 style="text-align: center;">[[.report_free_room_revenue.]]</h2>
            <form id="SearchForm" method="post">
                <fieldset style="border: 1px solid #333;">
                    <legend>[[.search.]]</legend>
                    <table style="margin: 0 auto;">
                        <tr>
                            <td>[[.recode.]]</td>
                            <td><input name="reservation_id" type="text" id="reservation_id" style="width: 50px; height: 17px;" /></td>
                            <td>[[.customer_new.]]</td>
                            <td><input name="customer" type="text" id="customer" style="width: 100px; height: 17px" oninput="check_select();" list="data_customer" /><datalist id="data_customer" class="customer_datalist">[[|customer_option|]]</datalist><input name="customer_id" type="hidden" id="customer_id" style="width: 100px; height: 17px"/></td>
                            <td>[[.customer_group_new.]]</td>
                            <td><select name="customer_group" id="customer_group" style="width: 100px; height: 23px"></select></td>
                            <td>[[.from_date.]]</td>
                            <td><input name="from_date" type="text" id="from_date" style="width: 70px; height: 17px"/></td>
                            <td>[[.to_date.]]</td>
                            <td><input name="to_date" type="text" id="to_date" style="width: 70px; height: 17px"/></td>
                            <td><input name="seach" type="submit" id="search" value="[[.view_report.]]" style="width: 87px; height: 23px;"/></td>
                            <td><input name="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 70px; height: 23px"/></td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </td>
    </tr>
</table>
<br />
<?php  if(empty($this->map['items'])){ ?>
    <table style="margin: 0 auto;">
        <tr>
            <td style="text-align: center;"><strong>[[.no_record.]]</strong></td>
        </tr>
    </table>
<?php }else{ ?>
<table id="content" width="100%">
    <tr>
        <td><h2 id="title_page" style="text-align: center; display: none;">[[.report_free_room_revenue.]]</h2></td>
    </tr>
    <tr>
        <td>
            <table border="1">
            <tr>
                <th width="5px">[[.stt.]]</th>
                <th width="30px">[[.recode.]]</th>
                <th width="120px">[[.customer_new.]]</th>
                <th width="20px">[[.room.]]</th>
                <th width="60px">[[.arrival_date.]]</th>
                <th width="60px">[[.departure_date.]]</th>
                <th width="5px">[[.night.]]</th>
                <th width="100px">[[.price.]](VND)</th>
                <th width="100px">[[.price.]](USD)</th>
                <th width="120px">[[.amount_room.]]</th>
                <th width="120px">[[.Extra_room_charge.]]</th>
                <th width="120px">[[.service_other.]]</th>
                <th width="120px">[[.telephone.]]</th>
                <th width="120px">[[.bar.]]</th>
                <th width="120px">[[.minibar.]]</th>
                <th width="120px">[[.laundry.]]</th>
                <th width="120px">[[.equip.]]</th>
                <th width="120px">[[.spa.]]</th>
                <th width="120px">[[.total.]]</th>
                <th width="220px">[[.note.]]</th>
            </tr>
            <?php $i =1;?>
            <!--LIST:items-->
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td width="30px" align="center"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.r_id|]]&r_r_id=[[|items.id|]]">[[|items.r_id|]]</a></td>
                <td align="left">[[|items.ctm_name|]]</td>
                <td align="center">[[|items.room_name|]]</td>
                <td>[[|items.arrival_time|]]</td>
                <td>[[|items.departure_time|]]</td>
                <td align="center">[[|items.nights|]]</td>
                <td align="right"><?php echo System::display_number([[=items.price=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.usd_price=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total=]]); ?></td>
                <td align="right"><?php echo System::display_number(round([[=items.total_extra_room_rates=]])); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_extra_service_rates=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_telephone_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_bar_pay_with_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_minibar_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_laundry_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_equip_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=items.total_spa_room=]]); ?></td>
                <td align="right"><?php echo System::display_number(round([[=items.total_amount=]])); ?></td>
                <td width="220px">[[|items.foc|]]</td>
            </tr>
            <!--/LIST:items-->
            <tr style="font-weight: bold; font-size: 12px;">
                <td>Tổng</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right"><?php echo System::display_number([[=total=]]); ?></td>
                <td align="right"><?php echo System::display_number(round([[=total_extra_room_rates=]])); ?></td>
                <td align="right"><?php echo System::display_number([[=total_extra_service_rates=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=total_telephone_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=total_bar_pay_with_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=total_minibar_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=total_laundry_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=total_equip_room=]]); ?></td>
                <td align="right"><?php echo System::display_number([[=total_spa_room=]]); ?></td>
                <td align="right"><?php echo System::display_number(round([[=total_amount=]])); ?></td>
                <td></td>
            </tr>
        </table>
        <br />
        <br />
        <table border="1" width="450px">
            <tr>
                <th align="center">[[.stt.]]</th>
                <th align="center">[[.customer_group_new.]]</th>
                <th align="center">[[.quantity_room.]]</th>
                <th align="center">[[.total.]]</th>
            </tr>
            <?php $j = 1; ?>
            <!--LIST:table_small-->
            <tr>
                <td align="center"><?php echo $j++; ?></td>
                <td>[[|table_small.name|]]</td>
                <td align="center">[[|table_small.quantity|]]</td>
                <td align="right"><?php echo System::display_number([[=table_small.total_amount=]]); ?></td>
            </tr>
            <!--/LIST:table_small-->
        </table>
        </td>
    </tr>
</table>
<?php }?>
<script>
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();

function check_select()
{
    var val = document.getElementById("customer").value;
    var opts = document.getElementById('data_customer').childNodes;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            jQuery('#customer_id').val(opts[i].id);
            break;
        }
    }
}

jQuery("#export_excel").click(function () {
    jQuery('#title_page').css('display', 'block');
    jQuery("#content").battatech_excelexport({
        containerid: "content"
       , datatype: 'table'
    });
});
</script>