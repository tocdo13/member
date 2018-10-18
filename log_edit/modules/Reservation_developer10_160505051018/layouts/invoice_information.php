<style>
    @media print {
        
        #module_547 {
            display: none;
        }
        #export {
            display: none;
        }
        #print_invoice {
            display: none;
        }
    }
</style>
<input type="button" id="export" value="[[.export_excel.]]" style="padding: 5px;" />
<input type="button" id="print_invoice" value="[[.print.]]" style="padding: 5px;" />
<table id="tblExport" style="width: 960px; margin: 0px auto;">
    <tr>
        <td style="width: 150px; vertical-align: top;">
            <img src="<?php echo HOTEL_LOGO; ?>" style="width: 150px; height: auto;" />
        </td>
        <td style="text-align: center; vertical-align: top;">
            <h2 style="line-height: 20px; font-size: 15px; text-transform: uppercase;">[[.invoice_information.]]</h2>
            <p style="font-size: 11px; color: #555555;">
                <?php echo HOTEL_ADDRESS; ?>
                <br/>Tel: <?php echo HOTEL_PHONE; ?> | Fax: <?php echo HOTEL_FAX; ?>
                <br/>Email: <?php echo HOTEL_EMAIL; ?> | Website: <?php echo HOTEL_WEBSITE; ?>
            </p>
        </td>
        <td style="text-align: right; vertical-align: bottom; width: 150px;">
            [[.recode.]]: [[|id|]]
        </td>
    </tr>
    <tr style="border-top: 1px solid #555555; height: 30px;">
        <td>
            [[.booking_code.]]: [[|booking_code|]]
        </td>
        <td></td>
        <td>[[.time.]]: <?php echo date('H:i d/m/Y'); ?></td>
    </tr>
    <tr style="height: 30px;">
        <td>
            [[.customer_name.]]: [[|customer_full_name|]]
        </td>
        <td></td>
        <td>[[.user_name.]]: [[|user_name|]]</td>
    </tr>
    <tr style="height: 30px;">
        <td>
            [[.customer_address.]]: [[|customer_address|]]
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr style="height: 30px;">
        <td>
            [[.customer_tax_code.]]: [[|customer_tax_code|]]
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width: 100%; border-collapse: collapse;" border="1" bordercolor="#555555" cellpadding="5">
                <tr style="text-align: center;">
                    <th>[[.stt.]]</th>
                    <th>[[.folio_id.]]</th>
                    <th>[[.invoice.]]</th>
                    <th style="text-align: left;">[[.description.]]</th>
                    <th style="text-align: right;">[[.total_before_tax.]]</th>
                    <th style="text-align: right;">[[.service_amount.]]</th>
                    <th style="text-align: right;">[[.tax_amount.]]</th>
                    <th style="text-align: right;">[[.total_amount.]]</th>
                    <th style="text-align: left;">[[.note.]]</th>
                </tr>
                <?php $stt=1; ?>
                <!--LIST:items-->
                <tr>
                    <td style="text-align: center; width: 30px;"><?php echo $stt++; ?></td>
                    <td style="text-align: center; width: 100px;">[[|items.folio|]]</td>
                    <td style="text-align: center; width: 100px;">[[|items.link_invoice|]]</td>
                    <td>[[|items.description|]]</td>
                    <td style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=items.total_before_tax=]]) ?></td>
                    <td style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=items.service_amount=]]) ?></td>
                    <td style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=items.tax_amount=]]) ?></td>
                    <td style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=items.total_amount=]]) ?></td>
                    <td>[[|items.note|]]</td>
                </tr>
                <!--/LIST:items-->
                <tr>
                    <th colspan="7" style="text-align: right;">[[.total_before_tax.]]</th>
                    <th style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=total_before_tax=]]) ?></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: right;">[[.service_amount.]]</th>
                    <th style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=service_amount=]]) ?></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: right;">[[.tax_amount.]]</th>
                    <th style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=tax_amount=]]) ?></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: right;">[[.total_amount.]]</th>
                    <th style="text-align: right; width: 100px; font-weight: bold;"><?php echo System::display_number([[=total_amount=]]) ?></th>
                    <th></th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width: 100%; border-collapse: collapse;" border="1" bordercolor="#555555" cellpadding="5">
                <tr style="text-align: center;">
                    <th style="text-align: left;">[[.description.]]</th>
                    <th>[[.folio_id.]]</th>
                    <th>[[.time.]]</th>
                    <th>[[.payment_type.]]</th>
                    <th style="text-align: right;">[[.amount.]]</th>
                    <th>[[.user_name.]]</th>
                </tr>
                <!--LIST:payment-->
                <tr style="text-align: center;">
                    <td style="text-align: left;">[[|payment.description|]]</td>
                    <td>[[|payment.href_folio|]]</td>
                    <td>[[|payment.time|]]</td>
                    <td>[[|payment.payment_type_name|]]</td>
                    <td style="text-align: right;">[[|payment.amount|]]</td>
                    <td>[[|payment.user_name|]]</td>
                </tr>
                <!--/LIST:payment-->
            </table>
        </td>
    </tr>
</table>
<script>
jQuery(document).ready(function(){
    jQuery("#print_invoice").click(function () {
            var user_id = '<?php echo User::id(); ?>';
            printWebPart('printer',user_id);
        });
    jQuery("#export").click(function () {
            jQuery('.col_num').each(function(){
                jQuery(this).html(to_numeric(jQuery(this).html()));
            });
            jQuery('img').remove();
            jQuery('br').remove();
            jQuery('hr').remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
        }); 
});
</script>