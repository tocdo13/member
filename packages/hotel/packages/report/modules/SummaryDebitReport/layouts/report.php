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
            <th colspan="3" style="text-align: center;"><h1 style="text-transform: uppercase;">[[.summary_debit_report.]]</h1></th>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <label>[[.customer.]]</label>
                <input name="customer_name" type="text" id="customer_name" onfocus="Autocomplete();" autocomplete="off" style="padding: 5px;"/>
                <input name="customer_id" type="text" id="customer_id" class="hidden" />
                <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onclick="$('customer_name').value='';$('customer_id').value='';" style="cursor:pointer;"/>
                <label>[[.from_date.]]</label>
                <input name="from_date" type="text" id="from_date" style="padding: 5px;" />
                <label>[[.to_date.]]</label>
                <input name="to_date" type="text" id="to_date" style="padding: 5px;" />
                <input value="[[.search.]]" type="submit" style="padding: 5px;" />
            </td>
        </tr>
    </table>
</form>
<table cellpadding="5" border="1" bordercolor="#CCCCCC" style="width: 100%; margin: 10px auto;">
    <tr style="text-align: center; background: #EEEEEE;">
        <th>[[.stt.]]</th>
        <th>[[.name.]]</th>
        <th>[[.lastest_debit.]]</th>
        <th>[[.total_debit.]]</th>
        <th>[[.paied.]]</th>
        <th>[[.outstanding_debt_last.]]</th>
        <th>[[.detail.]]</th>
    </tr>
    <!--LIST:items-->
    <tr style="text-align: center;">
        <td>[[|items.stt|]]</td>
        <td>[[|items.name|]]</td>
        <td style="text-align: right;">[[|items.debit_last_period_before|]]</td>
        <td style="text-align: right;">[[|items.debit_in_period|]]</td>
        <td style="text-align: right;">[[|items.review|]]</td>
        <td style="text-align: right;">[[|items.debit|]]</td>
        <td><a href="?page=summary_debit_report&cmd=detail&customer_id=[[|items.id|]]&from_date=[[|from_date|]]&to_date=[[|to_date|]]"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
    </tr>
    <!--/LIST:items-->
    <tr style="text-align: right; background: #EEEEEE;">
        <th colspan="2">[[.total.]]</th>
        <th style="text-align: right;">[[|debit_last_period_before|]]</th>
        <th style="text-align: right;">[[|debit_in_period|]]</th>
        <th style="text-align: right;">[[|review|]]</th>
        <th style="text-align: right;">[[|debit|]]</th>
        <th></th>
    </tr>
</table>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast_son.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
            }
        }) ;
    }
</script>