<div id="invoice">
	<div class="item-body"><br /></div>
	<!--IF:cond(1==1)-->
	<table cellpadding="0" width="100%" border="0">
	<tr>
		<td width="25%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO;?>" width="100"></td>
		<td width="50%" align="center">
			<div class="invoice-title">TOUR INVOICE</div>
			<div class="invoice-sub-title">HÓA ĐƠN TOUR</div>
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
			<div>Exchange Rate / T&#7927; gi&#225;: [[|exchange_rate|]]<br /></div></td>
	  </tr>
	<tr valign="top">
	  <td align="left">Room No / Số phòng: </td>
	  <td align="right">&nbsp;</td>
	  </tr>
	</table>
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <div style="width:100%">
		<div class="item-body" style="border-bottom:1px solid #333;float:left;font-weight:bold;">
        	<div style="float:left;width:5%;height:20px;">STT</div>
            <div style="float:left;width:40%;height:20px;margin-left:5px;">Description / Diễn giải</div>
            <div style="float:left;width:15%;height:20px;margin-left:5px;text-align:center;">Quantity / Số lượng</div>
            <div style="float:left;width:15%;height:20px;margin-left:5px;text-align:right">Price / Đơn giá</div>
            <div style="float:left;width:23%;height:20px;margin-left:5px;text-align:right">Amount / Thành tiền</div>
        </div>
        <!--LIST:items-->
        <div class="item-body" style="border-bottom:1px solid #333;float:left;">
        	<div style="float:left;width:5%;height:20px;">[[|items.id|]]</div>
            <div style="float:left;width:40%;height:20px;margin-left:5px;">[[|items.description|]]</div>
            <div style="float:left;width:15%;height:20px;margin-left:5px;text-align:center;">[[|items.quantity|]]</div>
            <div style="float:left;width:15%;height:20px;margin-left:5px;text-align:right;">[[|items.price|]]</div>
            <div style="float:left;width:23%;height:20px;margin-left:5px;text-align:right;">[[|items.amount|]]</div>
        </div>
        <!--/LIST:items-->
    </div>
	<div class="item-body total-group">
		<div class="item-body"><br></div>
		<div class="sub-item-body"><span class="label">Total / T&#7893;ng: </span><span><?php echo System::display_number([[=total=]])?></span></div>
		<div class="sub-item-body"><span><?php echo System::display_number(round(([[=total=]])*System::calculate_number([[=exchange_rate=]]),2));?> <?php echo HOTEL_EXCHANGE_CURRENCY;?></span></div>
        <div class="sub-item-body">
      	  	<div style="color:#F00;font-weight:bold;padding-top:10px;float:right;padding-right:30px;">[[|preview|]]</div>
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
	var maxLine = 30;
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
					jQuery(this).after('<div style="page-break-after:always;text-align:center;color:#666666;">-[[.page.]] '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}
			}
			i++;
		});
	}
</script>