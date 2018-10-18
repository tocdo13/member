<style>
    .simple-layout-middle{
        width:100%;
    }
    .simple-layout-content{
        padding: 0px; border: none;
    }
    #print_date:hover{
        border: 1px dashed #333;
    }
    #view_print { display: none; }
    @media print
    {
      #No-Print { display: none; }
      #view_print { display: block; }
    }
</style>
<form name="VatRevenueReportForm" method="POST">
    <table style="width: 100%;">
        <tr>
            <th colspan="4">
                <img src="<?php echo HOTEL_LOGO; ?>" style="width: 200px; height: auto;" /><br />
                <?php echo HOTEL_NAME; ?><br />
                [[.address.]]: <?php echo HOTEL_ADDRESS; ?>
                <input type="button" id="export" value="[[.export_excel.]]" style="padding: 5px; float: right;" />
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-transform: uppercase; text-align: center;"><h1>[[.general_statement.]]</h1></th>
        </tr>
        <tr>
            <th style="width: 50%; text-align: center;">
                Tên đoàn / Group name: [[|customer_name|]]<br />
                Người đặt / Booker: [[|booker|]]<br />
                Số điện thoại / Tel: [[|phone_booker|]]
            </th>
            <th style="text-align: center;">
                Tên công ty: [[|customer_full_name|]]<br />
                Mã đặt phòng: [[|booking_code|]]<br />
                Số recode: [[|id|]]
            </th>
        </tr>
    </table>
</form>
<table id="tblExport" style=" margin: 10px auto 50px; border-collapse: collapse;" border="1" bordercolor="#CCCCCC" cellpadding="5" cellspacing="0">
    <tr>
        <th>[[.stt.]]</th>
        <th>[[.traveller_name.]]</th>
        <th>[[.passport_report.]]</th>
        <th>[[.address.]]</th>
        <th>[[.competence.]]</th>
        <th>[[.room_name.]]</th>
        <th>[[.room_level.]]</th>
        <th>[[.checkin.]]</th>
        <th>[[.checkout.]]</th>
        <th>[[.night.]]</th>
        <th>[[.room_charge.]]</th>
        <th>[[.extra_service_room.]]</th>
        <th>[[.breakfast.]]</th>
        <th>[[.tranfer.]]</th>
        <th>[[.service_other.]]</th>
        <th>[[.minibar.]]</th>
        <th>[[.laundry.]]</th>
        <th>[[.equipment.]]</th>
        <th>[[.bar.]]</th>
        <th>[[.banquet.]]</th>
        <th>[[.service_amount.]]</th>
        <th>[[.amount_not_service.]]</th>
        <th>[[.tax_amount.]]</th>
        <th>[[.total_amount.]]</th>
        <th>[[.note.]]</th>
    </tr>
    <?php $stt = 1; $i=0; ?>
    <!--LIST:items-->
    <?php $child = ''; ?>
    <tr>
        <?php if(isset([[=items.child=]])){ ?>
        <!--LIST:items.child-->
            <?php $child=[[=items.child.id=]]; ?>
            <td><?php echo $stt++; ?></td>
            <td>[[|items.child.traveller_name|]]</td>
            <td>[[|items.child.passport|]]</td>
            <td>[[|items.child.address|]]</td>
            <td>[[|items.child.competence|]]</td>
            <?php break; ?>
        <!--/LIST:items.child-->
        <?php }else{ ?>
            <td><?php echo $stt++; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        <?php } ?>
        <td rowspan="[[|items.count_child|]]">[[|items.room_name|]]</td>
        <td rowspan="[[|items.count_child|]]">[[|items.room_level_name|]]</td>
        <?php if(isset([[=items.child=]])){ ?>
        <!--LIST:items.child-->
            <?php $child=[[=items.child.id=]]; ?>
            <td><?php echo Date('H:i d/m/Y',[[=items.child.time_in=]]); ?></td>
            <td><?php echo Date('H:i d/m/Y',[[=items.child.time_out=]]); ?></td>
            <td>[[|items.child.night|]]</td>
            <?php break; ?>
        <!--/LIST:items.child-->
        <?php }else{ ?>
            <td></td>
            <td></td>
            <td></td>
        <?php } ?>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.room_charge=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.extra_service_room=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.breakfast=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.tranfer=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.service_other=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.minibar=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.laundry=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.equipment=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.bar=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.banquet=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.service_amount=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.amount=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.tax_amount=]]); ?></td>
        <td style="text-align: right;" rowspan="[[|items.count_child|]]"><?php echo System::display_number([[=items.total_amount=]]); ?></td>
        <?php if($i==0){ $i++; ?>
        <td rowspan="[[|count_recode|]]">[[|note|]]</td>
        <?php } ?>
    </tr>
        <?php if([[=items.count_child=]]>1){ ?>
        <!--LIST:items.child-->
            <?php if($child!=[[=items.child.id=]]){ ?>
            <tr>
                <td><?php echo $stt++; ?></td>
                <td>[[|items.child.traveller_name|]]</td>
                <td>[[|items.child.passport|]]</td>
                <td>[[|items.child.address|]]</td>
                <td>[[|items.child.competence|]]</td>
                <td><?php echo Date('H:i d/m/Y',[[=items.child.time_in=]]); ?></td>
                <td><?php echo Date('H:i d/m/Y',[[=items.child.time_out=]]); ?></td>
                <td>[[|items.child.night|]]</td>
            </tr>
            <?php } ?>
        <!--/LIST:items.child-->
        <?php } ?>
    <!--/LIST:items-->
    <tr>
        <th style="text-align: center;" colspan="10">[[.total.]]</th>
        <th style="text-align: right;"><?php echo System::display_number([[=room_charge=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=extra_service_room=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=breakfast=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=tranfer=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=service_other=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=minibar=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=laundry=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=equipment=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=bar=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=banquet=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=service_amount=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=amount=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=tax_amount=]]); ?></th>
        <th style="text-align: right;"><?php echo System::display_number([[=total_amount=]]); ?></th>
        <th></th>
    </tr>
</table>
<script>
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery('.col_num').each(function(){
                    jQuery(this).html(to_numeric(jQuery(this).html()));
                });
                jQuery("#tblExport").battatech_excelexport({
                    containerid: "tblExport"
                   , datatype: 'table'
                });
                location.reload();
            });
        }
    );
</script>