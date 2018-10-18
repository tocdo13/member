<link rel="stylesheet" href="packages/hotel/skins/default/css/invoice.css" type="text/css"></link>
<div id="invoice">
	<div class="item-body"><br /></div>
	<!--IF:cond(1==1)-->
	<table cellpadding="0" width="100%" border="0">
	<tr>
		<td width="25%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO;?>" width="100"></td>
		<td width="50%" align="center">
			<div class="invoice-title">GROUP INVOICE</div>
			<div class="invoice-sub-title">HÓA ĐƠN NHÓM</div>
			<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
			<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
			<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
		</td>
		<td width="25%" align="left">
			<div>
			  <p><font style="font-size: 16px; font-weight:700">No:</font> <font style="font-size: 18px; font-weight:200">[[|checkout_id|]]<br />
			  </font><font style="font-size: 16px; font-weight:700">[[.booking_code.]]:</font> <font style="font-size: 18px; font-weight:200">[[|booking_code|]]</font><br />
		      </p>
</div>
		</td>
	</tr>
	</table>
	<!--ELSE-->
	<table cellpadding="0" width="100%" border="0">
	<tr>
		<td height="100">&nbsp;</td>
	</tr>
	</table>
	<!--/IF:cond-->
	<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
	<table cellpadding="0" width="100%">
	<tr valign="top">
		<td align="left" width="50%">
			<div class="item-body"><div>Group / Tour / Nh&#243;m: [[|tour_name|]]</div></div>
			<div class="item-body"><div>Customer / Kh&#225;ch h&#224;ng: [[|customer_name|]]</div></div>
			<div class="item-body"><div>Address / &#272;&#7883;a ch&#7881;: [[|address|]]</div></div></td>
		<td align="right" width="50%">
			<div>Currency / Ti&#7873;n t&#7879;: <?php echo HOTEL_CURRENCY;?></div>
			</td>
	  </tr>
	</table>
	<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
	<!--LIST:items-->
	<!--IF:cond([[=items.total_amount=]]>0 and [[=items.total_amount=]]!="0.00")-->
	<div class="item-body">
			<div style="text-align:left;background-color:#EFEFEF;">Code/M&atilde; l&#7867;: [[|items.bill_number|]] | Room/Ph&ograve;ng: [[|items.room_number|]] (Date/Ng&agrave;y: [[|items.arrival_time|]] - [[|items.departure_time|]]) | Customer / KH: [[|items.traveller_name|]]</div>
	</div>
	<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
	<!--IF:cond1(isset([[=items.room_amount=]]) and [[=items.room_amount=]]!="0.00" and ([[=items.room_amount=]]>0 || [[=items.room_amount=]]=='FOC'))-->
	<div class="item-body"><span class="label">Room Amount / Ti&#7873;n ph&ograve;ng:</span>
	<span><!--IF:condfoc([[=items.foc=]] and ![[=items.foc_all=]])--> (FOC)<!--ELSE--> <?php echo System::display_number([[=items.room_amount=]]);?> <!--/IF:condfoc--></span></div>
	<!--/IF:cond1-->
	<!--IF:cond2(isset([[=items.service_rate_amount=]]) and [[=items.service_rate_amount=]]!="0.00" and [[=items.service_rate_amount=]]>0)-->
	<div class="item-body"><span class="label">Room Service Charge / Ph&iacute; d&#7883;ch v&#7909; ph&ograve;ng ([[|items.service_rate|]]%):</span> <span><?php echo System::display_number([[=items.service_rate_amount=]])?></span></div>
	<!--/IF:cond2-->
	<!--IF:cond3(isset([[=items.tax_rate_amount=]]) and [[=items.tax_rate_amount=]]!="0.00" and [[=items.tax_rate_amount=]]>0)-->
	<div class="item-body"><span class="label">Tax / Ti&#7873;n thu&#7871; ([[|items.tax_rate|]]%):</span> <span><?php echo System::display_number([[=items.tax_rate_amount=]])?></span></div>
	<!--/IF:cond3-->
	<!--LIST:items.services-->
	<!--IF:cond_servicex(Url::get('other_invoice') and [[=items.services.type=]]=='SERVICE')-->
	<div class="item-body"><span class="label">[[|items.services.name|]]:</span> <span><?php echo System::display_number([[=items.services.amount=]])?></span></div>
	<!--/IF:cond_servicex-->
	<!--IF:cond_roomx(Url::get('room_invoice') and [[=items.services.type=]]=='ROOM')-->
	<div class="item-body"><span class="label">[[|items.services.name|]]:</span> <span><?php echo System::display_number([[=items.services.amount=]])?></span></div>
	<!--/IF:cond_roomx-->
	<!--/LIST:items.services-->
	<!--IF:cond7(isset([[=items.bar_service=]]) and [[=items.bar_service=]]!="0.00" and [[=items.bar_service=]]>0)-->
	<div class="item-body"><span class="label">Bar Amount / Ti&#7873;n nh&agrave; h&agrave;ng:</span> <span><?php echo System::display_number([[=items.bar_service=]])?></span></div><!--/IF:cond7-->
    <!--IF:cond7kara(isset([[=items.karaoke_service=]]) and [[=items.karaoke_service=]]!="0.00" and [[=items.karaoke_service=]]>0)-->
	<div class="item-body"><span class="label">karaoke Amount / Tiền karaoke:</span> <span><?php echo System::display_number([[=items.karaoke_service=]])?></span></div><!--/IF:cond7kara-->
    <!--IF:cond7vend(isset([[=items.vend_service=]]) and [[=items.vend_service=]]!="0.00" and [[=items.vend_service=]]>0)-->
	<div class="item-body"><span class="label">Vend Amount / Tiền Bán hàng:</span> <span><?php echo System::display_number([[=items.vend_service=]])?></span></div><!--/IF:cond7vend-->
    <!--IF:cond7(isset([[=items.ticket_service=]]) and [[=items.ticket_service=]]!="0.00" and [[=items.ticket_service=]]>0)-->
	<div class="item-body"><span class="label">Ticket Amount / [[.ticket_amount.]]:</span> <span><?php echo System::display_number([[=items.ticket_service=]])?></span></div><!--/IF:cond7-->
	<!--IF:cond8m(isset([[=items.minibar=]]) and [[=items.minibar=]]!="0.00" and [[=items.minibar=]]>0)-->
	<div class="item-body"><span class="label">Minibar Charge / Ti&#7873;n minibar:</span> <span><?php echo System::display_number([[=items.minibar=]])?></span></div><!--/IF:cond8m-->
	<!--IF:cond8l(isset([[=items.laundry=]]) and [[=items.laundry=]]!="0.00" and [[=items.laundry=]]>0)-->
	<div class="item-body"><span class="label">Laundry Charge / Ti&#7873;n Gi&#7863;t l&agrave;:</span> <span><?php echo System::display_number([[=items.laundry=]])?></span></div><!--/IF:cond8l-->
	<!--IF:cond8e(isset([[=items.equipment=]]) and [[=items.equipment=]]!="0.00" and [[=items.equipment=]]>0)-->
	<div class="item-body"><span class="label">Compensation Charge / Ti&#7873;n &#273;&#7873;n b&ugrave;:</span> <span><?php echo System::display_number([[=items.equipment=]])?></span></div><!--/IF:cond8e-->
	<!--IF:cond10(isset([[=items.total_massage_amount=]]) and [[=items.total_massage_amount=]]!="0.00" and [[=items.total_massage_amount=]]>0)-->
	<div class="item-body"><span class="label">Massage Service / D&#7883;ch v&#7909; massage:</span> <span><?php echo System::display_number([[=items.total_massage_amount=]])?></span></div>
	<!--/IF:cond10-->
	<!--IF:cond11(isset([[=items.total_tennis_amount=]]) and [[=items.total_tennis_amount=]]!="0.00" and [[=items.total_tennis_amount=]]>0)-->
	<div class="item-body"><span class="label">Tennis Service / D&#7883;ch v&#7909; tennis:</span> <span><?php echo System::display_number([[=items.total_tennis_amount=]])?></span></div>
	<!--/IF:cond11-->
	<!--IF:cond12(isset([[=items.total_swimming_pool_amount=]]) and [[=items.total_swimming_pool_amount=]]!="0.00" and [[=items.total_swimming_pool_amount=]]>0)-->
	<div class="item-body"><span class="label">Swimming Service / D&#7883;ch v&#7909; b&#7875; b&#417;i:</span> <span><?php echo System::display_number([[=items.total_swimming_pool_amount=]])?></span></div>
	<!--/IF:cond12-->
	<!--IF:condk(isset([[=items.total_karaoke_amount=]]) and [[=items.total_karaoke_amount=]]!="0.00" and [[=items.total_karaoke_amount=]]>0)--><div><span class="label">Karaoke:</span> <span><?php echo System::display_number([[=items.total_karaoke_amount=]])?></span></div><!--/IF:condk-->
	<!--IF:cond4(isset([[=items.phone=]]) and [[=items.phone=]]!="0.00" and [[=items.phone=]]>0)--><div><span class="label">Ti&#7873;n &#273;i&#7879;n tho&#7841;i/ Telephone Fee:</span> <span><?php echo System::display_number([[=items.phone=]])?></span></div><!--/IF:cond4-->
	<!--LIST:items.extra_services-->
	<div class="item-body"><span class="label">[[|items.extra_services.name|]] ([[|items.extra_services.date_in|]]) :</span> <span><?php echo System::display_number([[=items.extra_services.amount=]])?></span></div>
	<!--/LIST:items.extra_services-->
	<!--IF:cond_(Url::get('room') or !Url::get('service'))-->
	<!--IF:cond13(isset([[=items.discount=]]) and [[=items.discount=]]!="0.00" and [[=items.discount=]]>0)-->
	<div class="item-body"><span class="label">Discount by % / Gi&#7843;m gi&aacute; theo % ([[|items.reduce_balance|]]%):</span> <span>(<?php echo System::display_number([[=items.discount=]])?>)</span></div>
	<!--/IF:cond13-->
	<!--IF:cond131(isset([[=items.reduce_amount=]]) and [[=items.reduce_amount=]]!="0.00" and [[=items.reduce_amount=]]>0)-->
	<div class="item-body"><span class="label">Discount by <?php echo HOTEL_CURRENCY;?> / Gi&#7843;m gi&aacute; theo <?php echo HOTEL_CURRENCY;?>:</span> <span>(<?php echo System::display_number([[=items.reduce_amount=]]);?>)</span></div>
	<!--/IF:cond131-->
    <!--IF:cond14(isset([[=items.deposit=]]) and [[=items.deposit=]]!="0.00" and [[=items.deposit=]]>0)-->
	<div class="item-body"><span class="label">Deposit / &#272;&#7863;t c&#7885;c:</span> <span><?php echo System::display_number([[=items.deposit=]])?></span></div>
	<!--/IF:cond14-->
	<!--/IF:cond_-->
	<!--IF:cond15(isset([[=items.total_amount=]]) and ([[=items.total_amount=]]>0 || [[=items.foc_all=]]))-->
	<div class="item-body"><span class="label remain">Remain Pay / C&ograve;n ph&#7843;i tr&#7843;
	<!--IF:condfoc([[=items.foc_all=]])--> (FOC)<!--/IF:condfoc-->:</span> <span class="remain"><?php echo System::display_number([[=items.total_amount=]])?></span></div>
	<!--IF:cond_bank_fee([[=items.total_bank_fee=]] and [[=total_with_bank_fee=]])-->
	<div class="item-body" style="display:none;"><span class="label remain">Bank fees / Ph&iacute; ng&acirc;n h&agrave;ng ([[|items.bank_fee_percen|]]%):</span> <span class="remain"><?php echo System::display_number([[=items.total_bank_fee=]])?></span></div>
	<div class="item-body" style="display:none;"><span class="label remain">Total with bank fees / T&#7893;ng thanh to&aacute;n g&#7891;m ph&iacute; ng&acirc;n h&agrave;ng:</span> <span class="remain"><?php echo System::display_number([[=items.total_with_bank_fee=]])?></span></div>
	<!--/IF:cond_bank_fee-->
	<!--/IF:cond15-->
	<!--IF:cond16(isset([[=items.total_amount=]]) and [[=items.total_amount=]]!="0.00" and [[=items.total_amount=]]>0)-->
	<div class="item-body last-row"><span class="label"></span></div>
	<!--/IF:cond16-->
	<!--/LIST:items-->
	<div class="item-body total-group">
		<div class="item-body"><br></div>
		<div class="sub-item-body"><span class="label">Total / T&#7893;ng: </span><span><?php echo System::display_number([[=total=]]-[[=total_deposit=]])?></span></div>
        <div class="sub-item-body"><span class="label">Deposit / &#272;&#7863;t c&#7885;c : </span><span><?php echo System::display_number([[=deposit=]])?></span></div>
        <div class="sub-item-body"><span class="label">Total paymenr / Số còn phải trả: </span><span><?php echo System::display_number([[=total=]]-[[=total_deposit=]]-[[=deposit=]])?></span></div>
		<!--IF:cond_bank_fee([[=bank_fee_percen=]] and [[=total=]]!=[[=total_with_bank_fee=]])-->
		<div class="sub-item-body" style="display:none;"><span class="label" style="display:none;">Bank fees / Ph&iacute; ng&acirc;n h&agrave;ng ([[|bank_fee_percen|]]%): </span><span><?php echo System::display_number([[=total_bank_fee=]]);?></span></div>
		<div class="sub-item-body" style="display:none;"><span class="label">Total with bank fees / T&#7893;ng thanh to&aacute;n g&#7891;m ph&iacute; ng&acirc;n h&agrave;ng: </span><span><?php echo System::display_number([[=total_with_bank_fee=]]-[[=deposit=]]);?></span></div>
		<div class="sub-item-body"><span><?php echo HOTEL_EXCHANGE_CURRENCY=='USD'?System::display_number(round(([[=total=]]-[[=total_deposit=]])/System::calculate_number([[=exchange_rate=]]),2)):System::display_number(round(([[=total=]])*System::calculate_number([[=exchange_rate=]]),2));?> <?php echo HOTEL_EXCHANGE_CURRENCY;?></span></div>
		<!--ELSE-->
		<div class="sub-item-body"><span><?php echo HOTEL_EXCHANGE_CURRENCY=='USD'?System::display_number(round(([[=total=]]-[[=total_deposit=]])/System::calculate_number([[=exchange_rate=]]),2)):System::display_number(round(([[=total=]])*System::calculate_number([[=exchange_rate=]]),2));?> <?php echo HOTEL_EXCHANGE_CURRENCY;?></span></div>
		<!--/IF:cond_bank_fee-->
        <div class="sub-item-body">
      	  	<div style="color:#F00;font-weight:bold;padding-top:10px;float:right;padding-right:0px;">[[|preview|]]</div>
        </div>
	</div>
	<!--IF:cond_footer(1==1)-->
	<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
	<table width="100%" border="0" cellpadding="10" cellspacing="0">
	  <tr valign="middle">
		<td width="50%" align="center">Guest's Signature / Ch&#7919; k&yacute; c&#7911;a kh&aacute;ch</td>
		<td width="50%" align="center">Receptionist's Signature / Ch&#7919; k&yacute; nh&#226;n vi&#234;n</td>
	  </tr>
	  <tr>
		<td colspan="2" align="center" valign="middle"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></td>
	  </tr>
	</table>
	<p>&nbsp;</p>
	<!--ELSE-->
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td height="100">&nbsp;</td>
		</tr>
	</table>
	<!--/IF:cond_footer-->
</div>
<script>
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 41;
	var i = 1;
	var j = 0;
	var page = 1;
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
					jQuery(this).after('<div style="text-align:center;color:#666666;">-[[.page.]] '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}
			}
			i++;
		});
	}
</script>