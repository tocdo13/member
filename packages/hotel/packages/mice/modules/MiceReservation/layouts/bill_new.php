<style>
#invoice tr,#invoice tr,#invoice div,#invoice th{font-weight: normal 1important;}
#invoice{
	width:100%;
}
#invoice a,#invoice table,#invoice td,#invoice div,#invoice span,#invoice h{
	font-family:"Times New Roman", Times, serif;
	font-size:14px;
}
#invoice .invoice-title{
	font-size:18px;
	font-weight:bold;
	color:#333333;
	margin:0px;
	padding:0px;
}
#invoice .invoice-sub-title{
	font-size:14px;
	font-weight:bold;	
	color:#333333;
	margin:0px;
	padding:0px;
}
#invoice .invoice-contact-info{
	color:#666666;
	font-size:12px;
}
.seperator-line{
	border-bottom:1px solid #888;
	float:left;
	width:100%;
	margin-bottom:10px;
}
</style>
<div id="invoice">
    <table cellpadding="0" width="100%" border="0">
        <tr>
            <td width="20%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO;?>" width="200"></td>
            <td width="70%" align="center">
                <div class="invoice-title" style="text-transform: uppercase; font-weight: bold;"><?php echo [[=bill_id=]]?Portal::language('bill_folio'):Portal::language('list_of_goods_and_services'); ?></div>
                <div class="invoice-contact-info">Add: <?php echo HOTEL_ADDRESS;?></div>
                <div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
                <div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
            </td>
            <td width="10%" align="left">
                <div>
                    <p><font style="font-size: 14px;"><strong>Folio:</strong> <?php echo [[=bill_id=]]?'BILL - '.[[=bill_id=]]:''; ?></font></p>
                    <p><font style="font-size: 14px;"><strong>Mice:</strong> MICE+[[|mice_reservation_id|]]</font></p>
                </div>
            </td>
        </tr>
    </table>
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <table cellpadding="0" width="100%" border="0">
        <tr style="font-size: 12px;">
            <td style="text-align: left;">[[.unit.]]: [[|customer_name|]]</td>
            <td style="text-align: right; padding-right: 5px;">[[.currency.]]: <?php echo HOTEL_CURRENCY;?></td>
        </tr>
        <tr style="font-size: 12px;">
            <td style="text-align: left;">[[.traveller_name.]]: [[|traveller_name|]]</td>
            <td style="text-align: right; padding-right: 5px;">[[.print_person.]]: [[|print_person|]]</td>
        </tr>
        <tr style="font-size: 12px;">
            <td style="text-align: left;">[[.address.]]: [[|customer_address|]]</td>
            <td style="text-align: right; padding-right: 5px;">[[.print_time.]]: <?php echo date('H:i d/m/Y');?></td>
        </tr>
        <tr style="font-size: 12px;">
            <td style="text-align: left;">[[.start_date.]]: [[|start_date|]]</td>
            <td style="text-align: right; padding-right: 5px;">[[.create_person.]]: [[|create_mice_user|]]</td>
        </tr>
        <tr style="font-size: 12px;">
            <td style="text-align: left;">[[.end_date.]]: [[|end_date|]]</td>
            <td style="text-align: right; padding-right: 5px;">[[.create_time.]]: <?php echo date('H:i d/m/Y', [[=create_time=]]); ?></td>
        </tr>
    </table>
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    
    <div class="item-body">
        <table width="100%" style="float:right; border-collapse:collapse;" cellpadding="3px">
            <tr style="font-size: 12px; font-weight: bold;">
                <td align="center">STT</td>
                <td align="center">Diễn giải</td>
                <td align="center">DVT</td>
                <td align="center">Số lượng</td>
                <td align="right">Đơn giá</td>
                <td align="right">Thuế</td>
                <td align="right">Phí</td>
                <td align="right">Thành tiền</td>
            </tr>
            <?php $i =1; ?>
            <!--LIST:items-->
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td align="left: ;">[[|items.description|]]</td>
                <td align="center">[[|items.unit|]]</td>
                <td align="center">[[|items.quantity|]]</td>
                <td align="right"><?php echo System::display_number(round([[=items.price_before_tax=]])); ?></td>
                <td align="right"><?php echo System::display_number(round([[=items.tax_amount=]])); ?></td>
                <td align="right"><?php echo System::display_number(round([[=items.service_amount=]])); ?></td>
                <td align="right"><?php echo System::display_number(round([[=items.total_before_tax=]])); ?></td>
            </tr>
            <!--/LIST:items-->
        </table>   
    </div>
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    
        <table width="100%" style="float:right; border-collapse:collapse;" cellpadding="3px">
            <tr style="font-size: 12px;">
                <td align="right"><strong>Thành Tiền:</strong></td>
                <td align="right" width="100px"> <strong><?php echo System::display_number(round([[=sub_total=]])); ?></strong></td>
            </tr>
            <tr>
                <td align="right">Giá trị chiết khấu:</td>
                <td align="right" width="100px"> <?php echo System::display_number(round([[=discount=]])); ?></td>
            </tr>
            <tr>
                <td align="right">Tổng tiền:</td>
                <td align="right" width="100px"> <?php echo System::display_number(round([[=total=]])); ?></td>
            </tr>
            <tr>
                <td align="right">Đã đặt cọc:</td>
                <td align="right" width="100px"> <?php echo System::display_number(round([[=deposit=]])); ?></td>
            </tr>
            <tr>
                <td align="right"><strong>Còn phải thanh toán:</strong></td>
                <td align="right" width="100px"> <strong><?php echo System::display_number(round([[=have_to_pay=]])); ?></strong></td>
            </tr>
        </table>
        
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <div style="float:right; width: 100%;"> 
        <table width="100%" style="float:right; border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px">
          	<tr>
          		<td align="center"><i>[[.description.]]</i></td>
                <td align="center"><i>[[.payment_type.]]</i></td>
                <td align="center"><i>[[.currency.]]</i></td>
                <td align="center"><i>[[.amount.]]</i></td>
                <td align="center"><i>[[.bank_account.]]</i></td>
                <td align="center"><i>[[.bank_fee.]]</i></td>
                <td align="center"><i>[[.total.]]</i></td>
          	</tr>
            <?php $si = 0; ?>
            <!--LIST:payments-->
            <?php $si++; ?>
            	<tr>
          		<td align="center">[[|payments.description|]]</td>
                <td align="center"><?php echo Portal::language(strtolower([[=payments.payment_type_id=]])); if([[=payments.credit_card_id=]]!=''){echo '('.[[=payments.credit_card_name=]].')';}?></td>
                <td align="center">[[|payments.currency_id|]]</td>
                <td align="right"><?php echo System::display_number([[=payments.total=]]);?>
                <td align="center">[[|payments.bank_acc|]]</td>
                <td align="right"><?php echo System::display_number([[=payments.bank_fee=]]);?>
                <td align="right"><?php echo System::display_number([[=payments.total=]] + [[=payments.bank_fee=]]);?>
          	</tr>
            <!--/LIST:payments-->
        </table>
    </div>
    <div style="float:right; width: 100%;"> 
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
        		<td align="center"><br /><br />
        		<strong>[[.cashier_signature.]]</strong>
         	  </td>
        		<td width="50%" align="center" valign="top">
        			<br /><br />
                    <strong>[[.guest_signature.]]</strong>
        		</td>
        	</tr>
        	<tr>
        		<td colspan="2" align="center"><br /><br /></td>
        	</tr>
        </table>
    </div>
</div>
<script>
jQuery(document).ready(function(){
    var $check_bill_id = '[[|bill_id|]]';
    if($check_bill_id == '')
    {
        jQuery('.invoice-title').html('[[.list_of_goods_and_services.]]');        
    }
})
jQuery(document).disableSelection();
document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
</script>
