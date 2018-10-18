<link rel="stylesheet" href="packages/hotel/skins/default/css/invoice_temp.css" type="text/css"></link>
<div class="item-body">
	<div class="seperator-line">&nbsp;</div>
</div>
<div class="item-body">
	<div class="item-header">
	   <div class="date">Date</div>
		 <div class="description">Descriptions</div>
	   <div class="amount">Amount</div>
	   <div class="service_charge">Ser.Charge</div>
	   <div class="tax">Tax</div>
	   <div class="total">Total</div>
	</div>
</div>
<?php if(URL::get('room_invoice')){?>
	<!--LIST:live_day-->
	<!--IF:cond_room_price(isset([[=live_day.room_price=]])) -->
<div class="item-body">
	<div class="date">[[|live_day.date|]]</div>
	<div class="description">Room charge [[|live_day.extra|]]</div>
	<div class="amount"><?php if(![[=foc_all=]] && [[=foc=]]!=''){echo 'FOC';}else{ echo [[=live_day.room_price=]];}?></div>
	<div class="service_charge">[[|live_day.room_price_service_charge|]]</div>
   	<div class="tax">[[|live_day.room_price_tax|]]</div>
   	<div class="total">[[|live_day.room_price_total|]]</div>
</div>
	<!--/IF:cond_room_price-->
    <?php //if(URL::get('phone_invoice')){?>
	<!--IF:cond_phone(isset([[=live_day.phones=]]) && !empty([[=live_day.phones=]]))-->
    <!--LIST:live_day.phones-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">Telephone Fee <a target="_blank" href="<?php echo Url::build('telephone_list',array('room_id'=>[[=live_day.phones.room_id=]]));?>">[[|live_day.phones.id|]]</a></div>
	<div class="amount"><?php echo System::display_number_report([[=live_day.phones.total_before_tax=]]);?></div>
	<div class="service_charge">0</div>
	<div class="tax"><?php echo System::display_number_report([[=live_day.phones.total_phone_tax=]]);?></div>
	<div class="total"><?php echo System::display_number_report([[=live_day.phones.total=]]);?></div>
</div>
	<!--/LIST:live_day.phones-->
	<!--/IF:cond_phone-->
<?php //}//end phone invoice?>
	<!--/LIST:live_day-->
	<!--IF:cond_d_p([[=discount_total=]]!="0.00" and [[=discount_total=]]>0)-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">Discount by percent / <em>Gi&#7843;m gi&aacute; theo phần trăm </em> ([[|reduce_balance|]]%)</div>
	<div class="amount">([[|discount_total|]])</div>
</div>
	<!--/IF:cond_d_p-->
	<!--IF:cond_d_a([[=reduce_amount=]]!="0.00" and [[=reduce_amount=]]>0)-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">Discount by <?php echo HOTEL_CURRENCY;?> / <em>Gi&#7843;m gi&aacute; theo</em> <?php echo HOTEL_CURRENCY;?></div>
	<div class="amount">([[|reduce_amount|]])</div>
<div class="item-body">
	<!--/IF:cond_d_a-->
	<!--LIST:services-->
	<!--IF:cond_service([[=services.type=]]=='ROOM')-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">[[|services.name|]]</div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=services.amount=]]));?></div>
</div>
	<!--/IF:cond_service-->
	<!--/LIST:services-->
<?php }//end room invoice?>
<?php if(URL::get('other_invoice')){?>
	<!--LIST:services-->
	<!--IF:cond_service([[=services.type=]]=='SERVICE')-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">[[|services.name|]]</div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=services.amount=]]));?></div>
</div>
	<!--/IF:cond_service-->
	<!--/LIST:services-->
<?php }//end other invoice?>
<!--KID ĐÃ COMMENT ĐOẠN MASS NÀY-->
<?php if(URL::get('massage_invoice')){?>
	<!--IF:cond_total_massage([[=total_massage_amount=]])-->

	<!--/IF:cond_total_massage-->
    <!--HẾT NHÁ -->
<?php }//end massage invoice?>
<?php if(URL::get('tennis_invoice')){?>
	<!--IF:cond([[=total_tennis_amount=]])-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">Tennis service / <em>D&#7883;ch v&#7909; tennis</em></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=total_tennis_amount=]]));?></div>
</div>
	<!--/IF:cond-->
<?php }//end tennis invoice?>
<?php if(URL::get('swimming_pool_invoice')){?>
	<!--IF:cond([[=total_swimming_pool_amount=]])-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">Swimming service / <em>D&#7883;ch v&#7909; b&#417;i</em></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=total_swimming_pool_amount=]]));?></div>
</div>
	<!--/IF:cond-->
<?php }//end swimming pool invoice?>

	<!--LIST:live_day-->
<?php if(URL::get('hk_invoice')){?>
	<!--LIST:live_day.minibars-->
<div class="item-body">
	<div class="date"><?php echo date('d/m',[[=live_day.minibars.time=]]);?></div>
	<div class="description"><!--IF:cond_code([[=live_day.minibars.code=]])-->[[|live_day.minibars.code|]] - <!--/IF:cond_code-->Minibar <a target="_blank" href="<?php echo Url::build('minibar_invoice',array('id'=>[[=live_day.minibars.id=]],'cmd'=>'edit'));?>">#[[|live_day.minibars.position|]]</a></div>
	<div class="amount">[[|live_day.minibars.total_before_tax|]]</div>
	<div class="service_charge">[[|live_day.minibars.service_charge|]]</div>
	<div class="tax">[[|live_day.minibars.tax|]]</div>
	<div class="total">[[|live_day.minibars.total|]]</div>
</div>
	<!--/LIST:live_day.minibars-->
	<!--LIST:live_day.laundrys-->
<div class="item-body">
	<div class="date"><?php echo date('d/m',[[=live_day.laundrys.time=]]);?></div>
	<div class="description"><!--IF:cond_code([[=live_day.laundrys.code=]])-->[[|live_day.laundrys.code|]] - <!--/IF:cond_code-->Laundry <a target="_blank" href="<?php echo Url::build('laundry_invoice',array('id'=>[[=live_day.laundrys.id=]],'cmd'=>'edit'));?>">#[[|live_day.laundrys.position|]]</a></div>
	<div class="amount">[[|live_day.laundrys.total_before_tax|]]</div>
	<div class="service_charge">[[|live_day.laundrys.service_charge|]]</div>
	<div class="tax">[[|live_day.laundrys.tax|]]</div>
	<div class="total">[[|live_day.laundrys.total|]]</div>
</div>
	<!--/LIST:live_day.laundrys-->
	<!--LIST:live_day.compensated_items-->
<div class="item-body">
	<div class="date"><?php echo date('d/m',[[=live_day.compensated_items.time=]]);?></div>
	<div class="description"><!--IF:cond_code([[=live_day.compensated_items.code=]])-->[[|live_day.compensated_items.code|]] - <!--/IF:cond_code-->Compensation<a target="_blank" href="<?php echo Url::build('equipment_invoice',array('id'=>[[=live_day.compensated_items.id=]],'cmd'=>'edit'));?>">#[[|live_day.compensated_items.id|]]</a></div>
	<div class="amount">[[|live_day.compensated_items.total_before_tax|]]</div>
	<div class="service_charge">[[|live_day.compensated_items.service_charge|]]</div>
	<div class="tax">[[|live_day.compensated_items.tax|]]</div>
	<div class="amount">[[|live_day.compensated_items.total|]]</div>
</div>
	<!--/LIST:live_day.compensated_items-->
<?php }//end hk invoice?>
<?php if(URL::get('bar_invoice')){?>
	<!--IF:check_bar_charge(MAP['live_day']['current']['bar_charge']!=0)-->
	<!--LIST:live_day.bar_resers-->
<div class="item-body">
	<div class="date">[[|live_day.date|]]</div>
	<div class="description"><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&<?php echo md5('act'); ?>=<?php echo md5('print_bill'); ?>&<?php echo md5('preview'); ?>=1&id=[[|live_day.bar_resers.bar_reservation_id|]]">[[|live_day.bar_resers.bar_name|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.bar_resers.total_before_tax=]]));?></div>
	<div class="service_charge"><?php echo System::display_number([[=live_day.bar_resers.service_charge=]]);?></div>
	<div class="tax"><?php echo System::display_number([[=live_day.bar_resers.bar_chrg_tax=]]);?></div>
	<div class="amount"><?php echo System::display_number([[=live_day.bar_resers.total=]]);?></div>
</div>
	<!--/LIST:live_day.bar_resers-->
	<!--/IF:check_bar_charge-->
<?php }//end bar invoice?>

<?php if(URL::get('karaoke_invoice')){?>
	<!--IF:check_karaoke_charge(MAP['live_day']['current']['karaoke_charge']!=0)-->
	<!--LIST:live_day.karaoke_resers-->
<div class="item-body">
	<div class="date">[[|live_day.date|]]</div>
	<div class="description">[[|live_day.karaoke_resers.karaoke_name|]]</div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.karaoke_resers.total_before_tax=]]));?></div>
	<div class="service_charge"><?php echo System::display_number([[=live_day.karaoke_resers.service_charge=]]);?></div>
	<div class="tax"><?php echo System::display_number([[=live_day.karaoke_resers.karaoke_chrg_tax=]]);?></div>
	<div class="amount"><?php echo System::display_number([[=live_day.karaoke_resers.total=]]);?></div>
</div>
	<!--/LIST:live_day.karaoke_resers-->
	<!--/IF:check_karaoke_charge-->
<?php }//end karaoke invoice?>

<?php if(URL::get('vend_invoice')){?>
	<!--IF:check_vend_charge(MAP['live_day']['current']['ve_charge']!=0)-->
	<!--LIST:live_day.ve_resers-->
    
<div class="item-body">
	<div class="date">[[|live_day.date|]]</div>
	<div class="description">[[.vend.]]_[[|live_day.ve_resers.id|]]</div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.ve_resers.total_before_tax=]]));?></div>
	<div class="service_charge"><?php echo System::display_number([[=live_day.ve_resers.service_charge=]]);?></div>
	<div class="tax"><?php echo System::display_number([[=live_day.ve_resers.ve_chrg_tax=]]);?></div>
	<div class="amount"><?php echo System::display_number([[=live_day.ve_resers.total=]]);?></div>
</div>
	<!--/LIST:live_day.ve_resers-->
	<!--/IF:check_ve_charge-->
<?php }//end karaoke invoice?>

<?php if(URL::get('massage_invoice')){?>
	<!--IF:check_mass_charge(MAP['live_day']['current']['mass_charge']!=0)-->
	<!--LIST:live_day.massage-->
<div class="item-body">
	<div class="date">[[|live_day.date|]]</div>
	<div class="description">Massage_<a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=live_day.massage.massage_reservation_id=]]));?>">[[|live_day.massage.massage_name|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.massage.total_amount=]]));?></div>
	<div class="service_charge"><?php echo System::display_number([[=live_day.massage.mass_service_rate=]]);?></div>
	<div class="tax"><?php echo System::display_number([[=live_day.massage.mass_tax=]]);?></div>
	<div class="amount"><?php echo ([[=live_day.massage.mass_chrg=]]);?></div>
</div>
	<!--/LIST:live_day.massage-->
	<!--/IF:check_mass_charge-->
<?php }//end massage invoice?>


<!--ticket-------------->
<?php if(URL::get('ticket_invoice')){?>
	<!--IF:check_bar_charge(MAP['live_day']['current']['ticket_charge']!=0)-->
    
	<!--LIST:live_day.ticket_resers-->
<div class="item-body">
	<div class="date">[[|live_day.date|]]</div>
	<div class="description"><a target="_blank" href="<?php echo Url::build('ticket_invoice_group',array('cmd'=>'edit','id'=>[[=live_day.ticket_resers.ticket_reservation_id=]],md5('act')=>md5('print')));?>">[[|live_day.ticket_resers.ticket_name|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.ticket_resers.total_before_tax=]]));?></div>
	<div class="service_charge"><?php echo System::display_number([[=live_day.ticket_resers.service_charge=]]);?></div>
	<div class="tax"><?php echo System::display_number([[=live_day.ticket_resers.ticket_chrg_tax=]]);?></div>
	<div class="amount"><?php echo System::display_number([[=live_day.ticket_resers.total=]]);?></div>
</div>
	<!--/LIST:live_day.ticket_resers-->
	<!--/IF:check_bar_charge-->
<?php }//end bar invoice?>
	<!--IF:cond_extra_service([[=live_day.extra_services=]])-->
	<!--LIST:live_day.extra_services-->
<div class="item-body">
	<div class="date"> [[|live_day.date|]]</div>
	<div class="description"><!--IF:cond_code([[=live_day.extra_services.code=]])-->[[|live_day.extra_services.code|]] - <!--/IF:cond_code-->[[|live_day.extra_services.name|]] <a target="_blank" href="<?php echo Url::build('extra_service_invoice',array('cmd'=>'edit','id'=>[[=live_day.extra_services.invoice_id=]]))?>">#[[|live_day.extra_services.bill_number|]]</a>([[|live_day.extra_services.date_in|]])</div>
	<div class="amount">[[|live_day.extra_services.amount_net|]]</div>
	<div class="service_charge">[[|live_day.extra_services.service_charge|]]</div>
	<div class="tax">[[|live_day.extra_services.tax|]]</div>
	<div class="total">[[|live_day.extra_services.total|]]</div>
</div>
	<!--/LIST:live_day.extra_services-->
	<!--/IF:cond_extra_service-->
	<!--/LIST:live_day-->
<!-- ----------------------------------------------------TOTAL---------------------------------------------------- -->
<div class="item-body">
	<div class="date"></div>
	<div class="description">Total</div>
	<div class="amount">[[|total_before_tax|]]</div>
	<div class="service_charge">[[|service_charge_total|]]</div>
	<div class="tax">[[|tax_total|]]</div>
	<div class="total"><?php echo System::display_number_report(round(System::calculate_number([[=sum_total=]])));?></div>
</div>
<div class="item-body total-group" style="margin-top: 10px;">

		<!--LIST:related_rooms_arr-->
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Amount from room / <em>Ti&#7873;n chuy&#7875;n t&#7915; ph&ograve;ng</em> [[|related_rooms_arr.name|]]</div>
		<div class="amount">[[|related_rooms_arr.total_amount|]]</div>
	</div>
		<!--/LIST:related_rooms_arr-->
		<!--IF:related_rooms_arr([[=related_rooms_arr=]])-->
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Amount of / T&#7893;ng thanh to&aacute;n ph&ograve;ng [[|room_name|]]</div>
		<div class="amount">[[|current_total|]]</div>
	</div>
		<!--/IF:related_rooms_arr-->
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description"><strong>Grand Total / <em>T&#7893;ng c&#7897;ng</em></strong> </div>
		<div class="amount"><strong><!--IF:cond([[=show_price=]]==0)-->[[|reservation_type_name|]] + <!--/IF:cond--><?php if([[=foc_all=]]){echo 'FOC';}else{ echo [[=sum_total=]];}?></strong></div>
	</div>
	<?php if(System::calculate_number([[=deposit=]])>0 and Url::get('type')!='SERVICE'){ ?>
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Deposit / <em>Đặt cọc</em> </div>
		<div class="amount"><?php echo System::display_number([[=deposit=]]);?></div>
	</div>
	<?php }?>
	<?php if(System::calculate_number([[=pay_amount=]])>0 and Url::get('type')!='SERVICE'){ ?>
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Pay before check out / <em>Thanh toán trước khi check out</em> </div>
		<div class="amount"><?php echo System::display_number([[=pay_amount=]]);?></div>
	</div>
	<?php }?>
	<?php if(System::calculate_number([[=deposit=]])>0 or System::calculate_number([[=pay_amount=]])>0){
		$sum_total = System::calculate_number([[=sum_total=]]);
		if([[=foc_all=]]){$sum_total = 0;}
		?>
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description"><strong>Remain pay / <em>S&#7889; c&#242;n ph&#7843;i thanh to&#225;n</em></strong></div>
		<div class="amount"><strong><!--IF:cond([[=show_price=]]==0)-->[[|reservation_type_name|]] + <!--/IF:cond--><?php echo System::display_number($sum_total - System::calculate_number([[=deposit=]]) - System::calculate_number([[=pay_amount=]]));?></strong></div>
	</div>
	<?php }?>
	<!--IF:bank_fee([[=total_bank_fee=]]>0)-->
	<div class="sub-item-body" style="display:none;">
		<div class="date">&nbsp;</div>
		<div class="description">Bank fees / <em>Phí ngân hàng</em> ([[|bank_fee_percen|]]%)</div>
		<div class="amount"><?php echo System::display_number([[=total_bank_fee=]]);?></div>
	</div>
	<div class="sub-item-body" style="display:none;">
		<div class="date">&nbsp;</div>
		<div class="description"> <strong>Remain total with Bank fees / <em>Tiền phải thanh toán gồm phí ngân hàng</em></strong></div>
		<div class="amount"><strong><?php echo System::display_number(System::calculate_number([[=sum_total=]]) - System::calculate_number([[=deposit=]]) - System::calculate_number([[=pay_amount=]]));?></strong></div>
		<!--/IF:bank_fee-->
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Exchange Rate  / <em>Tỷ giá </em> </div>
		<div class="amount"><?php echo number_format([[=exchange_rate=]]);?></div>
	</div>
    <?php if(![[=foc_all=]]){?>
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Total / <em>Tổng cộng</em> ([[|exchange_currency_id|]]) </div>
		<div class="amount"><?php echo System::display_number([[=total_in_other_currency=]]);?></div>
	</div>
    <?php }?>
    <div class="sub-item-body">
    <div style="color:#F00;font-weight:bold;padding-top:10px;float:right;padding-right:30px;">[[|preview|]]</div>
    </div>
</div>
<!-- ---------------------------------------------------/TOTAL---------------------------------------------------- -->
<script>
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 25;
	var i = 1;
	var j = 0;
	var page = 1;
	/*if((itemBodySize + subItemBodySize) < maxLine){
		jQuery(".item-body").each(function(){
			if(j == itemBodySize -2 ){
				for(var c = 0;c <= (maxLine - itemBodySize);c++){
					jQuery(this).after('<div class="item-body"><div class="date">&nbsp;</div><div class="description">&nbsp;</div><div class="amount">&nbsp;</div></div>');
				}
			}
			j++;
		});
	}else */
    //giap.ln comment 13-1-2015 for 1 page 
	/*{
		jQuery(".item-body").each(function(){
			if(i<(itemBodySize + subItemBodySize)){
				var mode = maxLine;
				if(jQuery(this).attr('class') == 'item-body total-group'){
					if(i + subItemBodySize < maxLine){
						for(var c = 0;c <= (maxLine - (i + subItemBodySize));c++){
							jQuery(this).before('<div class="item-body"><div class="date">&nbsp;</div><div class="description">&nbsp;</div><div class="amount">&nbsp;</div></div>');
						}
					}
					mode = maxLine - subItemBodySize;
				}
				if(i%(mode) == 0){
					jQuery(this).after('<div style="page-break-after:always;text-align:center;color:#666666;">-[[.page.]] '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}
			}
			i++;
		});
	}*/
    //end giapln
</script>
