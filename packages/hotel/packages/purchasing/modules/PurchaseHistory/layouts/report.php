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
    #PurchaseHistoryForm{
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
            <h2 style="text-align: center; text-transform: uppercase;">[[.purchase_history.]]</h2>
            <form id="PurchaseHistoryForm" method="post">
                <fieldset style="border: 1px solid #069696;">
                    <legend style="font-weight: bold; color: #000;">[[.search.]]</legend>
                    <table style="margin: 0 auto;">
                        <tr>
                            <td>[[.from_date.]]</td>
                            <td><input name="from_date" type="text" id="from_date" style="width: 70px; height: 17px" readonly=""/></td>
                            <td>[[.to_date.]]</td>
                            <td><input name="to_date" type="text" id="to_date" style="width: 70px; height: 17px" readonly=""/></td>
                            <td>[[.product.]]</td>
                            <!--<td><select name="product_id" id="product_id" style="width: 100px; height: 23px"></select></td>-->
                            <td>
                            <input name="product_name" type="text" id="product_name" onfocus="Autocomplete_pr()" oninput="check_pr()" />
                            <input name="product_id" type="text" id="product_id" style="display: none;" />
                            </td>
                            <td>[[.supplier_name.]]</td>
                            <!--<td><select name="supplier_id" id="supplier_id" style="width: 100px; height: 23px"></select></td>-->
                            <td>
                            <input name="supplier_name" type="text" id="supplier_name" onfocus="Autocomplete_sp()" oninput="check_sp()" />
                            <input name="supplier_id" type="text" id="supplier_id" style="display: none;" />
                            </td>
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
<table id="PurchaseHistory" width="100%" border="1" style="border-collapse: collapse;">
    <tr style="line-height: 30px; background-color: #cccccc; text-align: center;">
        <th width="5px">[[.stt.]]</th>
        <th width="30px">[[.date.]]</th>
        <th width="50px">[[.product_id.]]</th>
        <th width="50px">[[.product_name.]]</th>
        <th width="50px">[[.purchase_code.]]</th>
        <th width="50px">[[.supplier_code_new.]]</th>
        <th width="100px">[[.supplier_name.]]</th>
        <th width="5px">[[.quantity.]]</th>
        <th width="30px">[[.price.]]</th>
        <th width="5px">[[.tax.]] (%)</th>
        <th width="40px">[[.total_amount.]]</th>
        <th width="50px">[[.user.]]</th>
    </tr>
    <?php $i =1;?>
    <!--LIST:items-->
    <tr style="line-height: 20px;"> 
        <td width="5px" align="center"><?php echo $i++; ?></td>
        <td width="30px"><?php echo date('d/m/Y', [[=items.time=]]); ?></td>
        <td width="50px">[[|items.product_id|]]</td>
        <td width="50px">[[|items.product_name|]]</td>
        <td width="50px" onclick="OpenLink([[|items.pc_order_id|]]);" style="color: #069696; cursor: pointer;" title="Mã đơn hàng [[|items.code|]]">[[|items.code|]]</td>
        <td width="50px">[[|items.supplier_code|]]</td>
        <td width="100px">[[|items.supplier_name|]]</td>
        <td width="5px" align="center">[[|items.quantity|]]</td>
        <td width="30px" align="right" class="change_num"><?php echo System::display_number([[=items.price=]]); ?></td>
        <td width="5px" align="center">[[|items.tax_percent|]]</td>
        <td width="40px" align="right" class="change_num"><?php echo System::display_number(round([[=items.total_amount=]])); ?></td>
        <td width="50px">[[|items.full_name|]]</td>
    </tr>
    <!--/LIST:items-->
</table>
<br />
<div class="paging">[[|paging|]]</div>
<?php }?>

<script>

var supplier_arr = <?php echo String::array2js([[=suppliers=]]); ?>;
//console.log(supplier_arr);
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();

function OpenLink(id)
{
    url = '?page=pc_import_warehouse_order&cmd=view_order&id='+id;
    window.open(url);
}

jQuery("#export_excel").click(function () {
    jQuery('#title_page').css('display', 'block');
    jQuery('.change_num').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
    })
    jQuery("#PurchaseHistory").battatech_excelexport({
        containerid: "PurchaseHistory"
       , datatype: 'table'
    });
});
function check_sp(){
    if(jQuery('#supplier_name').val()=='')
    {
        jQuery('#supplier_id').val('');
    }
}
function Autocomplete_sp()
{
    jQuery('#supplier_name').autocomplete({
        url:'get_customer1.php?supplier=1',
        onItemSelect:function(item){
        jQuery("#supplier_id").val(supplier_arr[item.data]['supplier_id']);
        
       }
    });
    
}

function check_pr()
{
    if(jQuery('#product_name').val() == '')
    {
        jQuery('#product_id').val('');
    }
}

function Autocomplete_pr()
{
    jQuery('#product_name').autocomplete({
       url:'get_product_all.php?product_all=1',
       onItemSelect:function(item){
        //console.log(item.data);
        jQuery('#product_id').val(item.data);
       } 
        
    });
}
</script>