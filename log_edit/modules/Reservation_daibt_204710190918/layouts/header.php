<div id="invoice">
<table cellSpacing=0 cellPadding=0 border=0 width="100%">
	<tr>
		<td align="center">
		<!--IF:cond(1==1)-->
		<table cellpadding="0" width="100%" border="0">
		<tr>
			<td width="25%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO;?>" width="100"></td>
		  	<td width="50%" align="center">
				<div class="invoice-title">GUEST'S FOLIO</div>
				<div class="invoice-sub-title">PHI&#7870;U THANH TOÁN</div>
				<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
				<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
				<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
				<div>[[|description|]]</div>
		  	</td>
			<td width="25%" align="left">
				<div></div>
		 	  <div><br />
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
		<br />
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
			  <td><div class="item-body">Ref No: [[|bill_number|]]</div></td>
			  <td>Print by/ <em>In b&#7903;i</em>: <?php echo Session::get('user_id');?></td>
		  </tr>
			<tr>
				<td width="70%"><!--IF:cond_([[=vip_card_code=]])-->
				<div>VIP code/ <em>M&atilde; VIP</em>: [[|vip_card_code|]]</div>
				<!--/IF:cond_--></td>
				<td><div class="item-body">Print time/ <em>Th&#7901;i gian in</em> : <?php echo date('H:i\'');?></div></td>
			</tr>
		</table><div class="item-body"><br /></div>
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
				<td width="70%"><div class="item-body">Guest's name/ <em>T&ecirc;n kh&aacute;ch</em>:&nbsp;[[|first_name|]] [[|last_name|]]</div></td>
				<td>Room No./ <em>S&#7889; ph&ograve;ng:</em> [[|room_name|]]</td>
			</tr>
			<tr>
			  <td colspan="2"><div class="item-body">Company name/ <em>T&ecirc;n c&ocirc;ng ty</em>: [[|customer_name|]]</div></td>
		  </tr>
			<tr>
			  <td colspan="2"><div class="item-body">Address/ <em>&#272;&#7883;a ch&#7881;</em>: [[|address|]] </div></td>
		  </tr>
			<!--<tr>
			  <td colspan="2"><div class="item-body">&nbsp;</div></td>
		  </tr>-->
		</table>
		</td>
	</tr>
</table>