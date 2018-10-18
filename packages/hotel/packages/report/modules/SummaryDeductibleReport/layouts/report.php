<style>
    #show_print {
        display: none;
    }
    @media print
    {
      #show_print {
            display: inline;
        }
      #hide_print {
            display: none;
        }
    }
</style>
<form name="SummaryDebitReportForm" method="POST">
    <table cellpadding="5" style="width: 100%;">
        <tr>
            <td style="width: 100px;">
                <img src="<?php echo HOTEL_LOGO;?>" style="width: 100px;"/>
            </td>
            <td>
                <b><?php echo HOTEL_NAME;?></b><br />
				ADD: <?php echo HOTEL_ADDRESS;?><br />
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
            </td>
            <td style="text-align: right;">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?><br />
                [[.user_print.]]:<?php echo User::id();?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;"><h1 style="text-transform: uppercase;">[[.summary_deductible_report.]]</h1></td>
        </tr>
        <tr id="hide_print">
            <td colspan="3" style="text-align: center;">
                <label>[[.customer.]]</label>
                <select name="customer_id" id="customer_id" style="padding: 5px; max-width: 250px;"></select>
                <label>[[.from_date.]]</label>
                <input name="from_date" type="text" id="from_date" style="padding: 5px;" />
                <label>[[.to_date.]]</label>
                <input name="to_date" type="text" id="to_date" style="padding: 5px;" />
                <label>[[.account.]]</label>
                <select name="user_id" id="user_id" style="padding: 5px; max-width: 150px;"></select>
                <label>[[.payment_type.]]</label>
                <select name="payment_type_id" id="payment_type_id" style="padding: 5px; max-width: 150px;"></select>
                <input value="[[.search.]]" type="submit" style="padding: 5px;" />
            </td>
        </tr>
    </table>
    <table id="show_print" cellpadding="5" style="width: 100%;">
        <tr>
            <td style="text-align: center;">
                [[.from_date.]]: [[|from_date|]] - [[.to_date.]]: [[|to_date|]]
            </td>
        </tr>
    </table>
</form>
<table cellpadding="5" border="1" bordercolor="#CCCCCC" style="width: 100%; margin: 10px auto;">
    <tr style="text-align: center; background: #EEEEEE;">
        <th>[[.stt.]]</th>
        <th>[[.date.]]</th>
        <th>[[.customer.]]</th>
        <th>[[.price.]]</th>
        <th>[[.description.]]</th>
        <th>[[.recode.]]</th>
        <th>[[.booking_code.]]</th>
        <th>[[.folio.]]</th>
        <th>[[.bar_reservation_code.]]</th>
        <th>[[.ve_reservation_code.]]</th>
        <th>[[.mice.]]</th>
        <th>[[.mice_invoice_code.]]</th>
        <th>[[.payment_type.]]</th>
        <th>[[.account.]]</th>
    </tr>
    <!--LIST:items-->
    <tr style="text-align: center;">
        <td>[[|items.stt|]]</td>
        <td>[[|items.in_date|]]</td>
        <td>[[|items.customer_name|]]</td>
        <td style="text-align: right;">[[|items.price|]]</td>
        <td>[[|items.description|]]</td>
        <td><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]">[[|items.reservation_id|]]</a></td>
        <td><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]">[[|items.booking_code|]]</a></td>
        <td><a target="_blank" href="[[|items.link_folio|]]">[[|items.folio_id|]]</a></td>
        <td><a target="_blank" href="[[|items.link_bar|]]">[[|items.bar_reservation_code|]]</a></td>
        <td><a target="_blank" href="[[|items.link_vend|]]">[[|items.ve_reservation_code|]]</a></td>
        <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.mice_reservation_id|]]">[[|items.mice_reservation_id|]]</a></td>
        <td><a target="_blank" href="[[|items.link_mice|]]">[[|items.mice_invoice_code|]]</a></td>
        <td>[[|items.payment_type_name|]]</td>
        <td>[[|items.user_name|]]</td>
    </tr>
    <!--/LIST:items-->
    <tr style="text-align: right; background: #EEEEEE;">
        <th colspan="3">[[.total.]]</th>
        <th style="text-align: right;"><?php echo System::display_number([[=total=]]); ?></th>
        <th colspan="10"></th>
    </tr>
</table>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
            }
        }) ;
    }
</script>