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
        <tr style="text-align: center;">
            <th style="width: 33%;">
                <img src="<?php echo HOTEL_LOGO; ?>" style="width: 200px; height: auto;" /><br />
                <?php echo HOTEL_NAME; ?>
            </th>
            <th></th>
            <th style="width: 33%;">
                [[.user_id.]]: <?php echo User::id(); ?><br />
                [[.time.]]: <?php echo date('H:i d/m/Y'); ?>
            </th>
        </tr>
        <tr style="text-align: center;">
            <th colspan="3">
                <h1 style="text-transform: uppercase;">
                    <?php if(Url::get('vat_type') and Url::get('vat_type')=='SAVE_NO_PRINT'){ ?>
                    [[.client_report_does_not_write_invoice.]]
                    <?php }else{ ?>
                    [[.vat_report_has_been_posted.]]
                    <?php } ?>
                </h1>
            </th>
        </tr>
        <tr id="No-Print" style="text-align: center;">
            <th colspan="3">
                [[.vat_code.]]: <input name="vat_code" type="text" id="vat_code" onchange="jQuery('#folio_code').val('');" style="padding: 5px; width: 100px; text-align: center;" />
                [[.folio_number.]] Folio: <input name="folio_code" type="text" id="folio_code" onchange="jQuery('#vat_code').val('');" style="padding: 5px; width: 100px; text-align: center;" />
                [[.start_date.]]: <input name="start_date" type="text" id="start_date" style="padding: 5px; width: 80px; text-align: center;" readonly="" />
                [[.end_date.]]: <input name="end_date" type="text" id="end_date" style="padding: 5px; width: 80px; text-align: center;" readonly="" />
                [[.type.]]: <select name="type" id="type" style="padding: 5px;"></select> 
                <!--IF:cond(!isset($_REQUEST['vat_type']) or $_REQUEST['vat_type']!='SAVE_NO_PRINT')-->
                [[.status.]]: <select name="vat_type" id="vat_type" style="padding: 5px;"></select>
                <!--/IF:cond-->
                <!--[[.user.]]: <select name="cashier" id="cashier" style="padding: 5px;"></select>-->
                
                <input type="submit" value="[[.search.]]" style="padding: 5px;" />
                <input type="button" id="export" value="[[.export_excel.]]" style="padding: 5px;" />
            </th>
        </tr>
        <tr id="view_print">
            <th colspan="3" style="text-align: center;">
                [[.start_date.]]: [[|start_date|]] - [[.end_date.]]: [[|end_date|]]
            </th>
        </tr>
    </table>
</form>
<table id="tblExport" style="width: 100%; margin: 10px auto 50px;" border="1" bordercolor="#CCCCCC" cellpadding="5" cellspacing="0">
    <tr style="text-align: center;">
        <th rowspan="2">[[.customer_code_report.]]</th>
        <th rowspan="2">[[.booking_number.]]</th>
        <th rowspan="2">[[.folio_number.]]</th>
        <th rowspan="2">[[.vat_code.]]</th>
        <th rowspan="2">[[.date.]], [[.month.]], [[.year.]] [[.create_bill_vat.]]</th>
        <th rowspan="2">[[.payment_time.]] Folio</th>
        <th rowspan="2">[[.buyer.]]</th>
        <th rowspan="2">[[.tax_code.]]</th>
        <th colspan="11">[[.revenue.]]</th>
        <th rowspan="2">[[.total_revenue.]]</th>
        <th rowspan="2">[[.tax_rate.]]</th>
        <th rowspan="2">[[.total_amount.]]</th>
        <th rowspan="2">[[.note.]]</th>
    </tr>
    <tr style="text-align: center;">
        <th>[[.room_charge.]]</th>
        <th>[[.extra_service_charge_room.]]</th>
        <th>[[.incurred_breakfast.]]</th>
        <th>[[.restaurant.]]</th>
        <th>[[.banquet.]]</th>
        <th>[[.minibar.]]</th>
        <th>[[.laundry.]]</th>
        <th>[[.equipment.]]</th>
        <th>[[.Trans.]]</th>
        <th>[[.others.]]</th>
        <th>[[.service_rate.]]</th>
    </tr>
    <!--LIST:items-->
    <tr style="text-align: center;">
        <td>[[|items.customer_code|]]</td>
        <td><?php echo substr([[=items.booking_number=]],0,-1); ?></td>
        <td><?php echo substr([[=items.folio_number=]],0,-1); ?></td>
        <td>[[|items.vat_code|]]</td>
        <td>[[|items.print_date|]]</td>
        <td>[[|items.payment_time|]]</td>
        <td>[[|items.buyer|]]</td>
        <td>[[|items.tax_code|]]</td>
        
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.room_charge=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.extra_service_charge_room=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.breakfast=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.restaurant=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.banquet=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.minibar=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.laundry=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.equipment=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.trans=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.others=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.service_rate=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.total_revenue=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.tax_rate=]]); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number([[=items.total_amount=]]); ?></td>
        
        <td>[[|items.note|]]</td>
    </tr>
    <!--/LIST:items-->
    <tr style="text-align: center; height: 40px;">
        <th colspan="8">[[.total.]]</th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=room_charge=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=extra_service_charge_room=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=breakfast=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=restaurant=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=banquet=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=minibar=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=laundry=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=equipment=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=trans=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=others=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=service_rate=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=total_revenue=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=tax_rate=]]); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number([[=total_amount=]]); ?></th>
        <th></th>
    </tr>
</table>
<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    
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