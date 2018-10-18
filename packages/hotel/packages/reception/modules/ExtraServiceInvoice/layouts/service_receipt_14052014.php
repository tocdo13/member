<?php
//System::debug($this->map);
?>
<style type="text/css">
	 @media print{
	   #export_excel{
	       display: none;
	   }
  }
  
</style>
<style>
    *{
        margin: 0px; padding: 0px; font-size: 12px; color: #171717; line-height: 20px;
    }
    #layout_sevice_receipt{
        border:1px solid #171717;
        width:750px;
        margin: 5px auto;
    }
</style>
<input name="export_excel" id="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 70px; height: 23px"/>
<table id="Export" width="100%" style="position: relative; float:left;"><tr><td>
<div id="layout_sevice_receipt">
    <table style="border-bottom: 1px solid #171717;  margin: 0px auto;">
        <tr>
            <td id="srcs" ><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 260px; height: auto; margin: 10px;" /></td>
            <td style="border-bottom: 0"></td>
            <td style="border-bottom: 0"></td>
            <td >
                <h1 style="text-transform: uppercase; font-size: 18px; line-height: 25px;"><?php echo HOTEL_NAME; ?></h1><br />
                ADD:<?php echo HOTEL_ADDRESS; ?><br />
                TEL:<?php echo HOTEL_PHONE; ?>FAX:<?php echo HOTEL_FAX; ?><br />
                EMAIL:<?php echo HOTEL_EMAIL; ?><br />
                WEBSITE:<?php echo HOTEL_WEBSITE; ?><br />
            </td>
        </tr>
    </table>
    <table style="width: 700px; margin: 0px auto;">
        <tr style="border-bottom: 1px solid #171717;">
            <td colspan="2" style="width: 50%;">
                <strong>Invoice/Hóa đơn: </strong><input name="bill_number" type="text" id="bill_number" style="width:40px; border: none;" readonly="readonly"/><span id="bill_number_1"></span>
                <strong> Receipt No/ [[.bill_number.]]: </strong><input name="code" type="text" id="code" style="width:40px; border: none;" readonly="" /><span id="code_1"></span>
            </td>
            
            <td >
                <strong>Reception note/Ghi chú:</strong><br />
                <input name="note" type="text" id="note" style="border: none; font-style: italic;" readonly="" /><span id="note_1"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p><strong>[[.room_name.]]: [[|room_name|]]</strong></p>
            </td>
            <td>
                <p><strong>Date/ [[.date.]]: [[|arrival_date|]] - [[|departure_date|]]</strong></p>
            </td>
        </tr>
    </table>
    <table style="width: 700px; margin: 0px auto;" border=1 CELLSPACING="0">
        <tr style="background: #eee;">
            <th style="width: 50px;">[[.STT.]]</th>
            <th style="width: 70px;">[[.quantity.]]</th>
            <th>[[.service.]]</th>
            <th style="width: 120px;">[[.price.]]</th>
            <th style="width: 120px;">[[.amount.]]</th>
        </tr>
        <!--LIST:items-->
            <tr>
                <td style="text-align: center;">[[|items.no|]]</td>
                <td style="text-align: center;">[[|items.quantity|]]</td>
                <td style="text-align: center;">[[|items.name|]]</td>
                <td class="numeric" style="text-align: right;">[[|items.price|]]</td>
                <td class="numeric" style="text-align: right;">[[|items.payment_price|]]</td>
            </tr>
        <!--/LIST:items-->
        <?php
            $n=sizeof([[=items=]]);
            if($n<5){
                for($n;$n<=5;$n++){
                    echo '<tr>';
                    echo '<td style="height: 25px;"></td>';
                    echo '<td style="height: 25px;"></td>';
                    echo '<td style="height: 25px;"></td>';
                    echo '<td style="height: 25px;"></td>';
                    echo '<td style="height: 25px;"></td>';
                    echo '</tr>';
                }
            }
        ?>
        
    </table>
    <table style="width: 700px; margin: 3px auto; border-bottom:1px solid #171717;" >
        <tr>
            <td colspan="2"></td>
            <td style="text-align: right; ">Total Amount ( [[.total_before_tax.]]):</td>
            <td></td>
            <td class="numeric" style="text-align: right; width: 120px;"><?php echo $_REQUEST['total_amount'];?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="text-align: right;">Discount ( [[.discount.]]) (%):</td>
            <td></td>
            <td class="numeric" style="text-align: right; width: 120px;"><?php echo $_REQUEST['total_discount'];?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="text-align: right; ">Charge ( [[.fee.]]) (<?php echo EXTRA_SERVICE_SERVICE_CHARGE;?>%):</td>
            <td></td>
            <td class="numeric" style="text-align: right; width: 120px;"><?php echo $_REQUEST['total_fee'];?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="text-align: right;">Tax VAT ( [[.tax_rate.]]) (<?php echo EXTRA_SERVICE_TAX_RATE;?>%):</td>
            <td></td>
            <td class="numeric" style="text-align: right; width: 120px;"><?php echo $_REQUEST['total_tax'];?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="text-align: right;font-size: 13px;font-weight: bold;" >Grant Total ([[.total.]]):</td>
            <td></td>
            <td class="numeric" style="text-align: right; width: 120px;font-size: 13px;font-weight: bold;"><?php echo $_REQUEST['grant_total'];?></td>
        </tr>
    </table>
    <table style="width: 700px; margin: 10px auto;" >
        <tr>
            <td colspan="3" style="width: 50%; text-align: center;">Chữ Ký khách hàng <br />(Guest's Signature)</td>
            
            <td colspan="3" style="width: 50%; text-align: center;">Chữ ký lễ tân <br /> (Receptionist's Signature)</td>
        </tr>
    </table>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
</div>
</td></tr></table>
<script>
	jQuery("#export_excel").click(function () {
    var note = jQuery('#note').val();
    var code = jQuery('#code').val();
    var bill_number = jQuery('#bill_number').val();
    console.log(bill_number);
    jQuery('#note').remove();
    jQuery('#code').remove();
    jQuery('#bill_number').remove();
    jQuery('#note_1').text(note);
    jQuery('#code_1').text(code);
    jQuery('#bill_number_1').text(bill_number);
    jQuery('#srcs').remove();
    jQuery('.numeric').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html())); 
    });
    jQuery("#Export").clone().find("img").remove();
    jQuery("#Export").battatech_excelexport({
        containerid: "Export"
       , datatype: 'table'
    }); 
});
</script>
