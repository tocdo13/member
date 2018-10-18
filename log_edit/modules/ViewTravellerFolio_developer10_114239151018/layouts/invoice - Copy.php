<link rel="stylesheet" href="packages/hotel/skins/default/css/invoice.css" type="text/css"></link>
<style>
.description,.date,.amount{
	border-bottom:1px solid #f1f1f1;	
}
.description-payment{
	width:35%;
}
@media print
{

}
.nor tr,.nor tr,.nor div,.nor th{font-weight: normal; !important;}
</style>
<table cellSpacing=0 cellPadding=0 border=0 width="100%" id="invoice">
	<tr>
		<td align="center">
		<!--IF:cond(1==1)-->
		<table cellpadding="0" width="100%" border="0">
        <tr>
        	<td><br><br>
            </td>
        </tr>
		<tr>
			<td width="1%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO;?>" width="200"></td>
		  	<td align="center">
				<div class="invoice-title">GUEST'S FOLIO</div>
				<div class="invoice-sub-title"></div>
				<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
				<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
				<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
				<div>[[|description|]]</div>
		  	</td><td width="100px;"></td>
		</tr>
		</table>
		<!--ELSE-->
		<table cellpadding="0" width="100%" border="0">
		<tr>
			<td height="100">&nbsp;</td>
		</tr>
		</table>
		<!--/IF:cond-->
		<br />
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
			  <td width="50%"><div class="item-body">Booking code: [[|booking_code|]] / Ref_No:</font> <font style="font-size:18px;font-weight:200">[[|bill_number|]]</font></div></td>
			  <td>Folio No.<?php echo Url::get('folio_id'); ?>/  Re_code: [[|reservation_id|]]</td>
		  </tr>
		</table>
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
				<td width="50%"><div class="item-body">Guest's name:&nbsp;[[|full_name|]]</div></td>
				<td>Room No. [[|room_name|]]</td>
			</tr>
			<tr>
			  <td width="50%"><div class="item-body">Company name: [[|customer_name|]]</div></td>
              <td><div class="item-body">Exchange Rate : <?php echo number_format([[=exchange_rate=]]);?></div></td>
              <!--<td><div class="item-body">Room rate :[[|room_rate|]] </div></td>-->
		  </tr>
			<tr>
			  <td><div class="item-body">Address: [[|address|]] </div></td>
              <td>Print by : [[|account_name|]]<?php //echo Session::get('user_id');?> - <?php echo date('H:i d/m/y');?></td>
		  </tr>
			<tr>
              <td><div class="item-body">Arrival date: [[|arrival_time|]] Departure date: [[|departure_time|]]</div></td>
			  <td>Created by : [[|create_folio_user|]] [[|create_folio_time|]]</td>
          </tr>
          <?php if([[=member_code=]]!=''){ ?>
          <tr>
              <td colspan="2">[[.member_code.]]: [[|member_code|]] - [[.member_level.]]: [[|member_level|]]</td>  
          </tr>
          <?php } ?>
		</table>
		</td>
	</tr>
</table>
<div class="item-body">
	<div class="seperator-line">&nbsp;</div>
</div>
<table style="width: 83%; margin: 0px auto;" class ="nor">

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Descriptions</th>
    <th style="text-align: right;">Amount</th>
</tr>
<?php //if(URL::get('room_invoice')){?>  
    <!--IF:cond_room_price(isset([[=rooms=]]))-->
	<!--LIST:rooms-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|rooms.description|]]</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=rooms.amount=]]);?></th>
</tr>
	<!--/LIST:rooms-->
    <!--/IF:cond_room_price-->

<?php //}//end room invoice?>
<?php //if(URL::get('phone_invoice')){?>
	<!--IF:cond_phone(isset([[=total_phone=]]) and [[=total_phone=]]>0 and [[=total_phone=]]!="0.00")-->  

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Telephone Fee</th>
    <th style="text-align: right;"><?php echo System::display_number_report(System::calculate_number([[=total_phone=]]));?></th>
</tr>
	<!--/IF:cond_phone-->
<?php // }//end phone invoice?>
<?php //if(URL::get('other_invoice')){?>
<?php //}//end other invoice?>
<?php //if(URL::get('massage_invoice')){?>
	<!--IF:cond_total_massage(isset([[=total_massage_amount=]]) and [[=total_massage_amount=]])-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Massage service</th>
    <th style="text-align: right;"><?php echo System::display_number_report(System::calculate_number([[=total_massage_amount=]]));?></th>
</tr>
	<!--/IF:cond_total_massage-->
<?php //}//end massage invoice?>
<?php //if(URL::get('tennis_invoice')){?>
	<!--IF:cond(isset([[=total_tennis_amount=]]) and [[=total_tennis_amount=]])-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Tennis service</th>
    <th style="text-align: right;"><?php echo System::display_number_report(System::calculate_number([[=total_tennis_amount=]]));?></th>
</tr>
	<!--/IF:cond-->
<?php //}//end tennis invoice?>	
<?php //if(URL::get('swimming_pool_invoice')){?>	
	<!--IF:cond(isset([[=total_swimming_pool_amount=]]) and [[=total_swimming_pool_amount=]])-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Swimming service</th>
    <th style="text-align: right;"><?php echo System::display_number_report(System::calculate_number([[=total_swimming_pool_amount=]]));?></th>
</tr>
	<!--/IF:cond--> 
<?php //}//end swimming pool invoice?>		
<?php //if(URL::get('karaoke_invoice')){?>
	<!--IF:cond_total_karaoke_amount(isset([[=total_karaoke_amount=]]) and [[=total_karaoke_amount=]])-->		

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Karaoke</th>
    <th style="text-align: right;"><?php echo System::display_number_report(System::calculate_number([[=total_karaoke_amount=]]));?></th>
</tr>
	<!--/IF:cond_total_karaoke_amount-->
<?php //}//end karaoke invoice?>
<?php //if(URL::get('hk_invoice')){?>
	<!--LIST:minibars-->
    
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|minibars.description|]] <?php echo [[=minibars.hk_code=]] != ''? [[=minibars.hk_code=]]:'';  ?><a target="_blank" href="?page=minibar_invoice&cmd=edit&id=[[|minibars.invoice_id|]]">#MN_[[|minibars.position|]]</a></th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=minibars.amount=]]);?></th>
</tr>
	<!--/LIST:minibars-->
   
	<!--LIST:laundrys-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|laundrys.description|]] <?php echo [[=laundrys.hk_code=]] != ''? '('.[[=laundrys.hk_code=]].')':'';  ?><a target="_blank" href="?page=laundry_invoice&cmd=edit&id=[[|laundrys.invoice_id|]]">#LD_[[|laundrys.position|]]</a></th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=laundrys.amount=]]);?></th>
</tr>
	<!--/LIST:laundrys-->
	<!--LIST:equipments-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|equipments.description|]]<a target="_blank" href="?page=equipment_invoice&id=[[|equipments.invoice_id|]]">#[[|equipments.invoice_id|]]</a></th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=equipments.amount=]]);?></th>
</tr>
	<!--/LIST:equipments-->
<?php //}//end hk invoice?>	 
  
	<!--LIST:bars-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;"><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=[[|bars.invoice_id|]]">#[[|bars.description|]]</a></th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=bars.amount=]]);?></th>
</tr>
	<!--/LIST:bars-->
    
    <!--LIST:karaokes-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|karaokes.description|]]</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=karaokes.amount=]]);?></th>
</tr>
	<!--/LIST:karaokes-->
    
    <!--LIST:ves-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|ves.description|]]</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=ves.amount=]]);?></th>
</tr>
	<!--/LIST:ves-->
    
    <!--LIST:tickets-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|tickets.description|]]</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=tickets.amount=]]);?></th>
</tr>
	<!--/LIST:tickets-->
    
    <!--LIST:massages-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|massages.description|]] <a target="_blank" href="?page=massage_daily_summary&cmd=invoice&id=[[|massages.invoice_id|]]">#[[|massages.invoice_id|]]</a></th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=massages.amount=]]);?></th>
</tr>
	<!--/LIST:massages-->
<?php //}//end bar invoice?>	
	<!--LIST:extra_services-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">[[|extra_services.description|]] <?php echo [[=extra_services.ex_code=]] != ''? '('.[[=extra_services.ex_code=]].')':'';  ?> <?php echo [[=extra_services.ex_note=]] != ''? '('.[[=extra_services.ex_note=]].')':'';  ?> <a target="_blank" href="?page=extra_service_invoice&cmd=view_receipt&id=[[|extra_services.ex_id|]]">#[[|extra_services.ex_bill|]]</a></th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=extra_services.amount=]]);?></th>
</tr>
	<!--/LIST:extra_services--> 
     <!--IF:cond_d_a(isset([[=telephones=]])  and [[=telephones=]])-->
    <!--LIST:telephones-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Telephone</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=telephones.amount=]]);?></th>
</tr>		 
	<!--/LIST:telephones-->
     <!--/IF:cond_d_a-->
 <!--IF:cond_service_rate([[=service_total=]] and [[=service_total=]]>0 and [[=service_total=]]!="0.00")-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Service Charge ([[|service_rate|]]%)</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=service_total=]]);?></th>
</tr>
	<!--/IF:cond_service_rate-->
	<!--IF:cond_room_tax([[=tax_total=]]>0 and [[=tax_total=]]!="0.00")-->


<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Goverment tax ([[|tax_rate|]]%)</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=tax_total=]]);?></th>
</tr>
	<!--/IF:cond_room_tax-->
    <!--LIST:add_payments-->

    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;">&nbsp;</th>
        <th style="text-align: left;">[[|add_payments.description|]]</th>
        <th style="text-align: right;"><?php echo System::display_number_report(round([[=add_payments.total=]]));?></th>
    </tr>
        <!--LIST:add_payment_items-->
        <?php if([[=add_payment_items.id=]]==[[=add_payments.id=]]){?>
       		 <!--LIST:add_payment_items.items-->
            
            <tr style="border-bottom: 1px solid #f1f1f1;">
                <th style="text-align: center;">&nbsp;</th>
                <th style="text-align: left;"><i>[[|add_payment_items.items.description|]]
                <?php if([[=add_payment_items.items.type=]]=='LAUNDRY'){ ?><a target="_blank" href="?page=laundry_invoice&cmd=edit&id=[[|add_payment_items.items.invoice_id|]]">#[[|add_payment_items.items.position|]]</a><?php } ?>
                <?php if([[=add_payment_items.items.type=]]=='MINIBAR'){ ?><a target="_blank" href="?page=minibar_invoice&cmd=edit&id=[[|add_payment_items.items.invoice_id|]]">#[[|add_payment_items.items.position|]]</a><?php } ?></i></th>
                <th style="text-align: right;"><i><?php echo System::display_number_report([[=add_payment_items.items.amount=]]);?></i></th>
            </tr>
             <!--/LIST:add_payment_items.items-->
             <!--IF:cond_service([[=add_payment_items.service_amount=]] && [[=add_payment_items.service_amount=]]>0)-->
            
            <tr style="border-bottom: 1px solid #f1f1f1;">
                <th style="text-align: center;">&nbsp;</th>
                <th style="text-align: left;"><i>Service amount</i></th>
                <th style="text-align: right;"><i><?php echo System::display_number_report([[=add_payment_items.service_amount=]]);?></i></th>
            </tr>
            <!--/IF:cond_service-->
            <!--IF:cond_tax([[=add_payment_items.tax_amount=]] && [[=add_payment_items.tax_amount=]]>0)-->
            
            
            <tr style="border-bottom: 1px solid #f1f1f1;">
                <th style="text-align: center;">&nbsp;</th>
                <th style="text-align: left;"><i>Tax amount</i></th>
                <th style="text-align: right;"><i><?php echo System::display_number_report([[=add_payment_items.tax_amount=]]);?></i></th>
            </tr> 
            <!--/IF:cond_tax-->
        <!--/LIST:add_payment_items--> 
         <?php } ?>
	<!--/LIST:add_payments-->  
     <!--IF:cond_d_a(isset([[=discounts=]])  and [[=discounts=]])-->
     <!--LIST:discounts-->


<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Discount by <?php echo HOTEL_CURRENCY;?><?php echo HOTEL_CURRENCY;?></th>
    <th style="text-align: right;">-<?php echo System::display_number_report([[=discounts.amount=]]);?></th>
</tr>		 
	<!--/LIST:discounts-->
	<!--/IF:cond_d_a-->     
 </table>    
<!-- ----------------------------------------------------TOTAL---------------------------------------------------- -->
<?php $total_deposit=0;?>
<div class="item-body total-group">
     <!--LIST:deposits-->
        <?php $total_deposit += [[=deposits.amount=]];?></div>
        </div>
     <!--/LIST:deposits-->
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description"><strong>Grand Total</strong> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></div>
		<div class="amount"><strong><?php echo System::display_number_report([[=total=]]+$total_deposit);?></strong></div>
	</div>
 <!--LIST:deposits-->
    <div class="sub-item-body">	
        <div class="date">&nbsp;</div>
        <div class="description">Deposit</div>
        <div class="amount"><?php echo System::display_number_report([[=deposits.amount=]]);?></div>
    </div>
 <!--/LIST:deposits-->
 <?php if($total_deposit>0){?>
     <div class="sub-item-body">	
            <div class="date">&nbsp;</div>
            <div class="description"><strong>Remain pay</strong> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></div>
            <div class="amount"><strong><?php echo System::display_number_report([[=total=]]);?></strong></div>
    </div>
  <?php } ?>
  <hr />
  <div class="sub-item-body">	
            <div class="date">&nbsp;</div>
            <div class="description"><strong><?php if([[=total=]]>=0){echo Portal::language('total_billing');}else{echo Portal::language('return');}?> </strong><!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></div>
            <div class="amount"><strong><?php echo System::display_number_report([[=total_payment_vnd=]]);?></strong></div>
    </div>
    <div class="sub-item-body">	
            <div class="date">&nbsp;</div>
            <div class="description"><i><strong>[[.total_remain.]]</strong></i> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></div>
            <div class="amount"><strong><?php if([[=total=]]>=0){echo System::display_number_report([[=total=]] - [[=total_payment_vnd=]]);}else{echo System::display_number_report([[=total=]] + [[=total_payment_vnd=]]);}?></strong></div>
    </div>
	<!--IF:bank_fee(isset([[=total_bank_fee=]]) and [[=total_bank_fee=]]>0)-->
	<div class="sub-item-body" style="display:none;">	
		<div class="date">&nbsp;</div>
		<div class="description">[[.bank_fee.]] ([[|bank_fee_percen|]]%)</div>
		<div class="amount"><?php echo System::display_number_report([[=total_bank_fee=]]);?></div>
	</div>
	<div class="sub-item-body" style="display:none;">	
		<div class="date">&nbsp;</div>
		<div class="description"> <strong>[[.total_with_bank_fee_1.]]</strong></div>
		<div class="amount"><strong><?php echo System::display_number_report([[=total_with_bank_fee=]]);?></strong></div>
		<!--/IF:bank_fee-->
	<div align="center"> 
    <?php $j=0;?>
    <!--LIST:payments-->
    	<?php $j++;?>
    <!--/LIST:payments-->
    <?php if($j>0){?>
  <table width="800px" style="border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px" class="nor">
  	<tr>
  		<th align="center">[[.payment_type.]]</th >
        <th align="center">[[.bank_account.]]/[[.card_number.]]</th >
        <th align="center">[[.description.]]</th >
        <th align="center">[[.currency.]]</th >
        <th align="right">[[.total.]]</th >
        <th align="right">[[.bank_fee.]]</th >
        <th align="right">[[.total_with_bank_fee.]]</th >
  	</tr>
    <!--LIST:payments-->
    	<tr>
  		<td align="center"><?php echo Portal::language(strtolower([[=payments.payment_type_id=]])); if([[=payments.credit_card_id=]]!=''){echo '('.[[=payments.credit_card_name=]].')';}?></td>
        <td align="center">[[|payments.bank_acc|]]</td>
        <td align="center">[[|payments.description|]]</td>
        <td align="center">[[|payments.currency_id|]]</td>
        <td align="right"><?php echo System::display_number([[=payments.total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=payments.bank_fee=]]);?></td>
        <td align="right"><?php echo System::display_number([[=payments.total=]] + [[=payments.bank_fee=]]);?></td>
  	</tr>
    <!--/LIST:payments-->
  </table>
    <!-- N?u c� thanh to�n th� ki?m tra xem c� member_code kh�ng -->
    <?php if($this->map['member_code']!=''){ ?>
        <hr style="width: 100%; text-decoration: none; margin: 3px auto;" />
        <table width="800px" style="border-collapse:collapse;" border="0" bordercolor="#CCCCCC" cellpadding="3px">
            <tr>
                <th style="text-align: right; text-transform: uppercase;">[[.payment_point_member.]]</th>
            </tr>
            <tr>
                <th style="text-align: right;">[[.point.]]: <?php if([[=point=]]>0) echo "+"; ?>[[|point|]]</th>
            </tr>
            <tr>
                <th style="text-align: right;">[[.point_user.]]: <?php if([[=point_user=]]>0) echo "+"; ?>[[|point_user|]]</th>
            </tr>
        </table>
    <?php } ?>
    <!-- end member -->  
  <?php } ?>
  </div>
<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>&nbsp;</td>
		<td align="justify" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><br /><br /><br />
		Guest's Signature
 	  </td>
		<td width="50%" align="center" valign="top">
			<br /><br /><br />
			Cashier's Signature
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><div style=" line-height:24px;">Date: <?php echo date('d/m/Y',time());?></div><br /><br /></td>
	</tr>
</table>	
</div>
<!-- ---------------------------------------------------/TOTAL---------------------------------------------------- -->
