<script>
	full_screen();
</script>
<link href="skins/default/court.css" rel="stylesheet" type="text/css">
<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<style type="text/css">
a.report_link
{
	font-weight:100;
	color:#000000;
	text-decoration:none;
}
a.report_link:hover
{
	font-weight:100;
	color:#FF6600;
	text-decoration:underline;
}
.td_header
{
	font-size:13px;
}
.data_title 
{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight:500;
}
.no_border
{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight:500;
	border-bottom:1px #FFFFFF solid;
}
.court-item-detail li{
	list-style:none;
	/*border-bottom:1px dotted #CCCCCC;*/
	line-height:20px;
	white-space:nowrap;	
	width:500px;
}
.court-item-detail li span{
	float:right;
	width:100px;
	height:25px;
}
.court-item-detail li span.label{
	float:left;
	width:400px;
	text-align:right;
}
.total-amount{
	font-size:12px;
	background:#EFEFEF;
	float:right;
	padding:2px;
	border:1px solid #CCCCCC;
	white-space:nowrap;
	width:300px;
}
.total-amount span{
	width:100px;
	font-size:14px;
	font-weight:bold;
}
.used_product{
	text-align:left;
	font-weight:bold;
	border-bottom:1px dashed #000000;
}
.total_amount{
	font-weight:bold;
	text-align:right;
	padding-right:5px;
}
</style>
<table width="700" border="0" cellpadding="0" cellspacing="0" align="left">
	<tr>
		<td width="100%" style="padding: 0px 15px 10px 15px">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;">
			<tr>
				<td align="center">
				<table cellpadding="0" width="100%" border="0">
				<tr>
					<td width="25%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO;?>" width="100"></td>
				  <td width="50%" align="center">
						<font style="font-size: 18px; font-weight:200">HO&Aacute; &#272;&#416;N THANH TO&Aacute;N</font><BR>
				    	<font style="font-size: 18px; font-weight:700">GUEST FOLIO </font><br>
						<!--IF:cond(Url::get('court'))-->
						<font style="font-size: 12px; font-weight:700">Ti&#7873;n ph&ograve;ng / Room Amount</font><br>
						<!--ELSE-->
						<!--IF:cond_(Url::get('service'))-->
						<font style="font-size: 12px; font-weight:700">Ti&#7873;n d&#7883;ch v&#7909; / Service Amount </font><br>
						<!--ELSE-->
						<!--/IF:cond_-->
						<!--/IF:cond-->
				  </td>
					<td width="25%" align="center"><font style="font-size: 16px; font-weight:700">No :</font> <font style="font-size: 18px; font-weight:200">[[|id|]]</font> </td>
				</tr>
				<tr>
					<td colspan="3" class="data_title" align="center">
						Hotel <?php echo date('d/m/Y H:i',time());?> PLAYING TENNIS 
						<p>&nbsp;</p></td>
				</tr>
				</table>
				<table cellpadding="0" width="100%" border="1" bordercolor="#000000" style="border:1px solid #000000; border-collapse:collapse" height="80px">
				<tr>
					<td align="left" width="50%" class="data_title" style="padding-left:10px">
						<!--IF:cond([[=hotel_court_number=]])-->
							Ph&ograve;ng kh&aacute;ch s&#7841;n / Hotel Room: [[|hotel_court_number|]]
						<!--/IF:cond-->
						<div>S&acirc;n / Court:&nbsp;[[|court_number|]]</div>
						<div>Gi&#7901; v&agrave;o / Time in: [[|time_in|]] - Gi&#7901; ra / Time out: [[|time_out|]] </div>
						
						<div></div>				  </td>
					<td align="center" width="50%" style="padding-left: 10px">
				  	  <div style="width:100%; white-space:nowrap">
						  <div style="width:60%; display:inline"><br>
						</div>
				  	  </div>
						
			  		  <div style="width:100%; white-space:nowrap">
						  <div style="width:60%; display:inline"></div>
			  		  </div>
			  		  <div style="width:100%; white-space:nowrap">
						  <div style="width:60%; display:inline"></div>
					  </div>
			  		  <div style="width:100%; white-space:nowrap">
							<div style="width:60%; display:inline">
							  <div style="width:40%; display:inline" class="data_title">Ti&#7873;n t&#7879; / Currency: <strong><?php echo HOTEL_CURRENCY;?></strong></div>
<br />
						    T&#7927; gi&#225;/ Exchange Rate: </div>
							<div style="width:40%; display:inline" class="data_title">[[|exchange_rate|]]<br />
							</div>
					  </div>			  		  <div style="width:100%; white-space:nowrap">
						  <div style="width:60%; display:inline"></div>
						</div></td>
				  </tr>
	      		</table>
				<br />
				<div style="width:100%;">
					<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
					  <tr bgcolor="#EFEFEF">
					    <th width="40%">[[.price.]]</th>
					    <th width="30%">[[.discount.]]</th>
					    <th width="30%">[[.tax.]]</th>
				      </tr>
					  <tr>
					    <td align="center">[[|price|]]</td>
					    <td align="center"><!--IF:cond([[=discount=]])-->[[|discount|]]<!--ELSE-->0<!--/IF:cond-->%</td>
					    <td align="center">[[|tax|]]%</td>
					  </tr>
					</table><br>
					<!--IF:cond1([[=products=]])-->
					<div class="used_product">[[.used_product.]]:</div><br>
					<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
					  <tr bgcolor="#EFEFEF">
					    <th>[[.no.]]</th>
					    <th>[[.name.]]</th>
					    <th>[[.price.]]</th>
					    <th align="center">[[.quantity.]]</th>
						<th>[[.amount.]]</th>
				      </tr>
					  <!--LIST:products-->
					  <tr>
					    <td align="center">[[|products.no|]]</td>
					    <td>[[|products.name|]]</td>
					    <td align="right">[[|products.price|]]</td>
						<td align="center">[[|products.quantity|]]</td>
						<td align="right">[[|products.amount|]]</td>
					  <!--/LIST:products-->						
					  </tr>
					</table><br>
					<!--/IF:cond1-->
					<!--IF:cond1([[=hired_products=]])-->
					<div class="used_product">[[.hired_product.]]:</div><br>
					<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
					  <tr bgcolor="#EFEFEF">
					    <th>[[.no.]]</th>
					    <th>[[.name.]]</th>
					    <th>[[.price.]]</th>
					    <th align="center">[[.quantity.]]</th>
						<th>[[.amount.]]</th>
				      </tr>
					  <!--LIST:hired_products-->
					  <tr>
					    <td align="center">[[|hired_products.no|]]</td>
					    <td>[[|hired_products.name|]]</td>
					    <td align="right">[[|hired_products.price|]]</td>
						<td align="center">[[|hired_products.quantity|]]</td>
						<td align="right">[[|hired_products.amount|]]</td>
					  <!--/LIST:hired_products-->						
					  </tr>
					</table><br>
					<!--/IF:cond1-->
					<div class="total_amount">[[.total_amount.]]: <?php echo System::display_number_report(System::calculate_number([[=total_amount=]]));?></div><br>
				</div>
				<br>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td width="40%"><p><font style="font-size:15px; font-weight:700">SEND ACCOUNT TO :</font></p>
					<p><font style="font-size: 14px; font-weight: bold; height:24px">Thank you for staying with us</font><br />
					</p></td>
					<td width="1%">&nbsp;</td>
					<td width="59%" align="justify" valign="top"><?php echo Portal::get_setting('LOICAMDOAN');?></td>
				  </tr>
				  <tr>
					<td colspan="2" nowrap="nowrap"><div style="width:100%;" align="center">__________<br>
						<font style="font-size: 14px;">Ch&#7919; k&yacute; nh&#226;n vi&#234;n <br> 
						Receptionist Signature</font> <br />
						<br />
						<br>
					</div>
					  <br>
					  <div style=" line-height:24px;text-align:left;">
						<div style="width:80px; display:inline;text-align:right"></div>
					  </div>
				</td>
					<td align="center" valign="middle"><span style="width:70%;">__________________________________<br>
					<font style="font-size: 14px; line-height:30px">Ch&#7919; k&yacute; c&#7911;a kh&aacute;ch / Guest Signature<br />
					<br />
					<br />
					</font></td>
				  </tr>
				  <tr>
					<td colspan="3" align="center" valign="middle">
						<strong><?php echo HOTEL_NAME;?></strong><br />
						ADD: <?php echo HOTEL_ADDRESS;?><BR>
						Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
						Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
					</td>
				  </tr>
				</table>
</td>
</tr>
</table>