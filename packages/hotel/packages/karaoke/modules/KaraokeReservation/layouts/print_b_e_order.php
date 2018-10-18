<style>
	.sub-title{
		font-size:14px;
		font-weight:bold;
	}
</style>
<div style="width:800px;padding:5px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="1%"><img src="<?php echo HOTEL_LOGO;?>" width="60" align="middle"></td>
		<td align="center"><h1>[[.BANQUET_EVENT_ORDER.]]</h1></td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
				<tr valign="top">
					<td width="50%">Ng&#432;&#7901;i &#273;&#7863;t ti&#7879;c: [[|agent_name|]]<br />
					&#272;&#7883;a ch&#7881;: [[|agent_address|]]</td>
					<td>&#272;i&#7879;n tho&#7841;i di &#273;&#7897;ng: [[|agent_phone|]]<br /></td>
			  	</tr>
				<tr>
					<td colspan="2" align="center"><span class="sub-title">TH&Ocirc;NG TIN TI&#7878;C</span></td>
				</tr>
				<tr valign="top">
				  <td>Th&#7901;i gian t&#7893; ch&#7913;c ti&#7879;c: [[|time_begin|]]
					  <br />
				    S&#7889; l&#432;&#7907;ng kh&aacute;ch: [[|num_people|]] </td>
				  	<td>Lo&#7841;i ti&#7879;c: [[|banquet_order_type|]]<br />
				  	  V&#7883; tr&iacute; t&#7893; ch&#7913;c: [[|tables_name|]]<br />
				  	  S&#7889; l&#432;&#7907;ng b&agrave;n: [[|num_table|]] </td>
			  	</tr>
				<tr>
				  <td class="sub-title" align="center">TH&Ocirc;NG TIN LI&Ecirc;N QUAN </td>
				  <td class="sub-title" align="center">TH&#7920;C &#272;&#416;N </td>
			  </tr>
				<tr valign="top">
				  <td align="left" height="200">[[|note|]]</td>
				  <td align="left">
				  	 <?php $i=1;?>
					  <!--LIST:product_items-->
						<?php echo $i;?>.[[|product_items.product__name|]] ([[|product_items.product__quantity|]])<br>
                      <?php $i++;?>
					  <!--/LIST:product_items-->				  </td>
			  </tr>
				<tr>
                  <td class="sub-title" align="center">H&Igrave;NH TH&#7912;C THANH TO&Aacute;N </td>
				  <td class="sub-title" align="center">&#272;&#7862;T C&#7884;C </td>
			  </tr>
				<tr valign="top">
				  <td align="left" height="50">[[|payment_info|]]</td>
				  <td align="left">&#272;&#7863;t c&#7885;c: (Ng&agrave;y [[|deposit_date|]]) </td>
			  </tr>
				<tr>
				  	<td style="padding:0px;">
						<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000" rules="cols" frame="void">
						  <tr>
							<td width="33%" align="center" class="sub-title"><p>B&#7870;P</p>
						    <p>&nbsp;</p></td>
							<td width="33%" align="center" class="sub-title"><p>HOTESS</p>
						    <p>&nbsp;</p></td>
							<td width="33%" align="center" class="sub-title"><p>THU NG&Acirc;N </p>
						    <p>&nbsp;</p></td>
						  </tr>
					  </table>					</td>
					<td style="padding:0px;">
						<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000" rules="cols" frame="void">
						  <tr>
							<td width="33%" align="center" class="sub-title"><p>NH&Agrave; H&Agrave;NG </p>
						    <p>&nbsp;</p></td>
							<td width="33%" align="center" class="sub-title"><p>B&#7842;O TR&Igrave; </p>
						    <p>&nbsp;</p></td>
							<td width="33%" align="center" class="sub-title"><p>B&#7842;O V&#7878; </p>
						    <p>&nbsp;</p></td>
						  </tr>
					  </table>					</td>
				</tr>
			</table>
			<div style="padding:10px;text-align:right;">
				Ng&agrave;y <?php echo date('d');?> th&aacute;ng <?php echo date('m');?> n&#259;m <?php echo date('Y');?><br />
				Ng&#432;&#7901;i l&#7853;p th&ocirc;ng b&aacute;o&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
		</td>
	</tr>
</table>
</div>