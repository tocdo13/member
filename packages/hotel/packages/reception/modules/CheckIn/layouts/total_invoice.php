<div class="item-body">
	<div class="seperator-line">&nbsp;</div>
</div>
<div class="item-body">
	<div class="item-header">
	   <div class="date">Date /<em> Ng&agrave;y</em></div>
		 <div class="description">Descriptions /<em> Di&#7877;n gi&#7843;i</em></div>
	   <div class="amount">Amount /<em> Th&agrave;nh ti&#7873;n</em></div>
	</div>
</div>
<?php if(URL::get('room_invoice')){?>
	<!--LIST:live_day-->
	<!--IF:cond_room_price(isset([[=live_day.room_price=]])) -->
<div class="item-body">	
	<div class="date">[[|live_day.date|]]</div>
	<div class="description">Room charge [[|live_day.extra|]]/<em> Gi&aacute; ph&ograve;ng</em></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.room_price=]]));?></div>
</div>
	<!--/IF:cond_room_price-->
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
	<div class="description"><span style="font-size:13px;"> Disount by <?php echo HOTEL_CURRENCY;?></span> / <em>Gi&#7843;m gi&aacute; theo</em> <?php echo HOTEL_CURRENCY;?></div>
	<div class="amount">([[|reduce_amount|]])</div>
<div class="item-body">			 
	<!--/IF:cond_d_a-->
	<!--IF:cond_service_rate([[=service_rate_total=]] and [[=service_rate_total=]]>0 and [[=service_rate_total=]]!="0.00")-->
<div class="item-body">		
	<div class="date">&nbsp;</div>
	<div class="description">Service Charge / <em>Ph&#237; dịch vụ </em> ([[|service_rate|]]%)</div>
	<div class="amount">[[|service_rate_total|]]</div>
</div>
	<!--/IF:cond_service_rate-->
	<!--IF:cond_room_tax([[=room_tax_total=]]>0 and [[=room_tax_total=]]!="0.00")-->
<div class="item-body">	
	<div class="date">&nbsp;</div>
	<div class="description">Goverment tax / <em>Thuế VAT </em> ([[|tax_rate|]]%)</div>
	<div class="amount">[[|room_tax_total|]]</div>
</div>
	<!--/IF:cond_room_tax-->
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
<?php if(URL::get('phone_invoice')){?>
	<!--IF:cond_phone([[=total_phone=]]>0 and [[=total_phone=]]!="0.00")-->  
<div class="item-body">		
	<div class="date">&nbsp;</div>
	<div class="description">Telephone Fee / <em>Ti&#7873;n &#273;i&#7879;n tho&#7841;i</em></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=total_phone=]]));?></div>
</div>
	<!--/IF:cond_phone-->
<?php }//end phone invoice?>
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
<?php if(URL::get('massage_invoice')){?>
	<!--IF:cond_total_massage([[=total_massage_amount=]])-->
<div class="item-body">	
	<div class="date">&nbsp;</div>
	<div class="description">Massage service / <em>D&#7883;ch v&#7909; massage</em></div>
	<div class="amount"><span><?php echo System::display_number(System::calculate_number([[=total_massage_amount=]]));?></span></div>
</div>
	<!--/IF:cond_total_massage-->
<?php }//end massage invoice?>
<?php if(URL::get('tennis_invoice')){?>
	<!--IF:cond([[=total_tennis_amount=]])-->
<div class="item-body">
	<div class="date">&nbsp;</div>
	<div class="description">Tennis service / <em>D&#7883;ch v&#7909; tennis</em></div>
	<div class="amount"><span><?php echo System::display_number(System::calculate_number([[=total_tennis_amount=]]));?></span></div>
</div>
	<!--/IF:cond-->
<?php }//end tennis invoice?>	
<?php if(URL::get('swimming_pool_invoice')){?>	
	<!--IF:cond([[=total_swimming_pool_amount=]])-->
<div class="item-body">	
	<div class="date">&nbsp;</div>
	<div class="description">Swimming service / <em>D&#7883;ch v&#7909; b&#417;i</em></div>
	<div class="amount"><span><?php echo System::display_number(System::calculate_number([[=total_swimming_pool_amount=]]));?></span></div>
</div>
	<!--/IF:cond--> 
<?php }//end swimming pool invoice?>		
<?php if(URL::get('karaoke_invoice')){?>
	<!--IF:cond_total_karaoke_amount([[=total_karaoke_amount=]])-->		
<div class="item-body">		
	<div class="date">&nbsp;</div>
	<div class="description">Karaoke </div>
	<div class="amount"><span><?php echo System::display_number(System::calculate_number([[=total_karaoke_amount=]]));?></span></div>
</div>
	<!--/IF:cond_total_karaoke_amount-->
<?php }//end karaoke invoice?>
	<!--LIST:live_day-->
<?php if(URL::get('hk_invoice')){?>
	<!--LIST:live_day.minibars-->
<div class="item-body">	
	<div class="date"><?php echo date('d/m',[[=live_day.minibars.time=]]);?></div>
	<div class="description">Minibar <a target="_blank" href="<?php echo Url::build('minibar_invoice',array('id'=>[[=live_day.minibars.id=]]));?>">#[[|live_day.minibars.id|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.minibars.total=]]));?></div>	
</div>
	<!--/LIST:live_day.minibars-->
	<!--LIST:live_day.laundrys-->
<div class="item-body">		
	<div class="date"><?php echo date('d/m',[[=live_day.laundrys.time=]]);?></div>
	<div class="description">Laundry / <em>Gi&#7863;t l&#224;</em> <a target="_blank" href="<?php echo Url::build('laundry_invoice',array('id'=>[[=live_day.laundrys.id=]]));?>">#[[|live_day.laundrys.id|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.laundrys.total=]]));?></div>
</div>
	<!--/LIST:live_day.laundrys-->
	<!--LIST:live_day.compensated_items-->
<div class="item-body">	
	<div class="date"><?php echo date('d/m',[[=live_day.compensated_items.time=]]);?></div>
	<div class="description">Compansation / <em>&#272;&#7873;n b&ugrave;</em> <a target="_blank" href="<?php echo Url::build('equipment_invoice',array('id'=>[[=live_day.compensated_items.id=]]));?>">#[[|live_day.compensated_items.id|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.compensated_items.total=]]));?></div>
</div>
	<!--/LIST:live_day.compensated_items-->
<?php }//end hk invoice?>	 
<?php if(URL::get('bar_invoice')){?>	 
	<!--IF:check_bar_charge(MAP['live_day']['current']['bar_charge']!=0)-->
	<!--LIST:live_day.bar_resers-->
<div class="item-body">	
	<div class="date">[[|live_day.date|]]</div>
	<div class="description"><a target="_blank" href="<?php echo Url::build('bar_reservation',array('cmd'=>'detail','id'=>[[=live_day.bar_resers.bar_reservation_id=]],'act'=>'print'));?>">[[|live_day.bar_resers.bar_name|]]</a></div>
	<div class="amount"><?php echo System::display_number(System::calculate_number([[=live_day.bar_resers.bar_chrg=]])+System::calculate_number([[=live_day.bar_resers.bar_chrg_tax=]]));?></div>
</div>
	<!--/LIST:live_day.bar_resers-->
	<!--/IF:check_bar_charge-->
<?php }//end bar invoice?>	
	<!--IF:cond_extra_service([[=live_day.extra_services=]])-->
	<!--LIST:live_day.extra_services-->
<div class="item-body">	
	<div class="date">[[|live_day.date|]]</div>
	<div class="description">[[|live_day.extra_services.name|]] <!--IF:cond_extra_service_note([[=live_day.extra_services.note=]])-->([[|live_day.extra_services.note|]])<!--/IF:cond_extra_service_note--></div>
	<div class="amount"><span><?php echo System::display_number(System::calculate_number([[=live_day.extra_services.amount=]]));?></span></div>
</div>
	<!--/LIST:live_day.extra_services-->         
	<!--/IF:cond_extra_service-->	  
	<!--/LIST:live_day-->
	
<!-- ----------------------------------------------------TOTAL---------------------------------------------------- -->
<div class="item-body">&nbsp;</div>
<div class="item-body total-group">
<div class="item-body">&nbsp;</div>
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
		<div class="description"><strong>Grand Total / <em>T&#7893;ng c&#7897;ng</em></strong> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></div>
		<div class="amount"><strong>[[|sum_total|]]</strong></div>
	</div>
	<?php if([[=deposit=]]!="0.00" and [[=deposit=]]>0 and Url::get('type')!='SERVICE'){ ?>
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description">Deposit / <em>Đặt cọc</em> </div>
		<div class="amount"><?php echo System::display_number([[=deposit=]]);?></div>
	</div>
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description"><strong>Remain pay / <em>S&#7889; c&#242;n ph&#7843;i thanh to&#225;n</em></strong></div>
		<div class="amount"><strong><?php echo System::display_number(System::calculate_number([[=sum_total=]]) - System::calculate_number([[=deposit=]]));?></strong></div>
	</div>
	<!--IF:bank_fee([[=total_bank_fee=]]>0)-->
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description">Bank fees / <em>Phí ngân hàng</em> ([[|bank_fee_percen|]]%)</div>
		<div class="amount"><?php echo System::display_number([[=total_bank_fee=]]);?></div>
	</div>
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description"> <strong>Remain total with Bank fees / <em>Tiền phải thanh toán gồm phí ngân hàng</em></strong></div>
		<div class="amount"><strong><?php echo System::display_number([[=total_with_bank_fee=]]);?></strong></div>
		<!--/IF:bank_fee-->
	<?php }else{?>
		<!--IF:bank_fee([[=total_bank_fee=]]>0)-->
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description">Bank fees / <em>Phí ngân hàng</em> ([[|bank_fee_percen|]]%)</div>
		<div class="amount"><?php echo System::display_number([[=total_bank_fee=]]);?></div>
	</div>
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description"><strong>Remain total with Bank fees / <em>Tiền phải thanh toán gồm phí ngân hàng</em></strong></div>
		<div class="amount"><strong><?php echo System::display_number([[=total_with_bank_fee=]]);?></strong></div>
	</div>
		<!--/IF:bank_fee-->
	<?php }?>
	<!--IF:currency_cond([[=currencies=]] and Url::get('type')!='SERVICE' and Url::get('type')!='ROOM')-->
	<div  class="sub-item-body" style="display:none;">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
			<div width="90%">Payment by / Tr&#7843; b&#7857;ng:</div>
			<?php $i=0;?>
			<!--LIST:currencies-->
			<?php $i++;?>
			<div nowrap="nowrap">[[|currencies.name|]] <?php echo System::display_number(System::calculate_number([[=currencies.amount=]]));?></div>
			<!--/LIST:currencies-->
		  </tr>
		</table>		
	</div>
	<!--/IF:currency_cond-->
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Method of Payment / <em>H&igrave;nh th&#7913;c thanh to&aacute;n</em> </div>
		<div class="amount">[[|payment_type_name_2|]]</div>
	</div>
	<div class="sub-item-body">
		<div class="date">&nbsp;</div>
		<div class="description">Exchange Rate  / <em>Tỷ giá </em> </div>
		<div class="amount"><?php echo number_format([[=exchange_rate=]]);?></div>
	</div>
	<div class="sub-item-body">	
		<div class="date">&nbsp;</div>
		<div class="description">Total / <em>Tổng cộng</em> ([[|exchange_currency_id|]]) </div>
		<div class="amount"><?php echo System::display_number([[=total_in_other_currency=]]);?></div>
	</div>
</div>
<!-- ---------------------------------------------------/TOTAL---------------------------------------------------- -->
<script>
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 30;
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
	{
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
	}
</script>