<style>

input:hover{
    border: 1px dashed #333;
}
select:hover{
    border: 1px dashed #333;    
}
table{
    border-collapse: collapse;  
}

table tr td{
    line-height: 20px;
}

@media print{
    #SearchForm{
        display: none;
    }
    #printer_logo{
        display: none;
    }
}
</style>

<table width="100%" id="printer_logo">
    <tr>
        <td width="85%">&nbsp;</td>
        <td align="right" style="vertical-align: bottom;" >
            <a onclick="print_customer_care();" title="In">
                <img src="packages/core/skins/default/images/printer.png" height="40" />
            </a>
        </td>
    </tr>
</table>
<table width="100%" style="margin: 0 auto;">
    <tr style="font-size: 12px;">
        <td style="text-align: left; width: 20%;"><img src="<?php echo HOTEL_LOGO; ?>" alt="Logo" style="width: 220px; height: auto;" /></td>
        <td style="text-align: center; width: 60%; font-size: 12px; font-weight: bold;"><?php echo HOTEL_NAME .'<br />Địa chỉ: '. HOTEL_ADDRESS .'<br />Email: '. HOTEL_EMAIL .'&nbsp; Website: '. HOTEL_WEBSITE; ?></td>
        <td style="text-align: right; width: 20%;">[[.print_times.]]: <span id="print_time"><?php echo date('H:i d/m/Y', time()); ?></span><br />[[.print_person.]]: [[|print_person|]]</td>
    </tr>
    <tr style="font-size: 12px;">
        <td colspan="3"><h2 style="text-transform: uppercase; font-size: 20px; text-align: center;">[[.history_customer_care.]]</h2></td>
    </tr>
</table>
<form id="SearchForm" method="post">
    <fieldset style="border: 1px solid #333;">
    <legend>[[.search.]]</legend>
        <table width="100%" style="margin: 0 auto;">
            <tr style="font-size: 12px;">
                <td>[[.from_date.]]:</td>
                <td><input name="from_date" type="text" id="from_date" style="width: 70px; height: 17px"/></td>
                <td>[[.to_date.]]:</td>
                <td><input name="to_date" type="text" id="to_date" style="width: 70px; height: 17px"/></td>
                <td>[[.name_sale.]]:</td>
                <td><select name="name_sale" id="name_sale" style="height: 23px;"></select></td>
                <td>[[.company.]]:</td>
                <td><input name="customer_name" type="text" id="customer_name" style="width: 100px; height: 17px" onfocus="Autocomplete();" autocomplete="off"  /><input name="customer_id" type="hidden" id="customer_id" style="width: 100px; height: 17px"/></td>
                <td><input name="seach" type="submit" id="search" value="[[.search.]]" onclick="ViewCustomerCareForm.submit();" style="width: 87px; height: 23px;"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<br />

<table width="100%" border="1" style="margin: 0 auto; border-collapse: collapse">
    <tr style="font-size: 12px; font-weight: bold; text-align: center;">
        <td>[[.stt.]]</td>
        <td width="120px">[[.name_sale.]]</td>
        <td>[[.date_contact.]]</td>
        <td>[[.company_name.]]</td>
        <td>[[.attorney_customer.]]</td>
        <td width="500px">[[.content.]]</td>
        <td>[[.type_contact.]]</td>
        <td>[[.crate_time.]]</td>
    </tr>
    <?php $i = 1;?>
    <!--LIST:items-->
    <tr style="font-size: 12px;">
        <td align="center"><?php echo $i++; ?></td>
        <td width="120px" style="padding-left: 5px;">[[|items.sale_code|]]</td>
        <td style="padding-left: 5px;"><?php echo date('d/m/Y H:i', [[=items.time_contact=]]); ?></td>
        <td style="padding-left: 5px;">[[|items.name|]]</td>
        <td style="padding-left: 5px;">[[|items.attorney_customer|]] &nbsp;- [[|items.attorney_hotel|]]</td>
        <td width="500px" style="padding-left: 5px;">[[|items.content_contact|]]</td>
        <td style="padding-left: 5px;">[[|items.expedeency|]]</td>
        <td style="padding-left: 5px;"><?php echo [[=items.create_time=]]?date('d/m/Y H:i', [[=items.create_time=]]):''; ?></td>
    </tr>
    <!--/LIST:items-->
</table>
<script>
jQuery("#chang_language").css('display','none');
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();
function Autocomplete()
{
    document.getElementById('customer_id').value = '';
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item){
            document.getElementById('customer_id').value = item.data[0]; 
        }
    }) ;
}

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

function print_customer_care()
{
    var print_time = '<?php echo date('d/m/Y H:i', time()); ?>';
    var user ='<?php echo User::id(); ?>';
    jQuery('#print_time').html(print_time);
    printWebPart('printer',user);
}
</script>