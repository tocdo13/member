
<link rel="stylesheet" href="skins/default/report.css"/>
<div class="product-report-bound">
	<div style="width:720px;padding:10px;text-align:center;font-size:14px;">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left">
					<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
				<td align="right"><strong>[[.Warehouse.]]: [[|warehouse|]]</strong><br /><br>
		</td>
			</tr>
		</table>
	</div>
	  <div style="width:720px;">
			<div style="text-align:center;">
				<div class="report_title">B&Aacute;O C&Aacute;O XU&#7844;T KHO CHO B&#7896; PH&#7852;N </div>
				<br>
				[[.date_from.]] [[|date_from|]] [[.to.]] [[|date_to|]]<br><br />
			</div>
			<div>
				<table cellpadding="2" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
					<tr valign="middle" bgcolor="#EFEFEF">
						<th colspan="2" class="report_table_header">[[.invoice.]]</th>
						<th rowspan="2" class="report_table_header">[[.note.]]</th>
						<th class="report_table_header">[[.number.]]</th>
					</tr>
					<tr><th width="100" rowspan="1" class="report_table_header">[[.invoice_date.]]</th>
					<th width="150" rowspan="1" class="report_table_header">[[.export_code.]]</th>
					<th width="150" rowspan="1" class="report_table_header">[[.export.]]</th>
					</tr>
					<tr><td align="center">1</td>
					  <td align="center">2</td>
					  <td align="center">3</td>
					  <td align="center">4</td>
					</tr>
					<!--LIST:products-->
					<tr bgcolor="white">
						<td align="left" nowrap class="report_table_column">
								[[|products.create_date|]]			</td>
						<td nowrap align="left" class="report_table_column" width="70">
								[[|products.export_invoice_code|]]					  </td>
							<td nowrap align="left" class="report_table_column" width="200">
								[[|products.note|]]			</td>
							<td align="right" nowrap class="report_table_column">
							[[|products.export_number|]]							</td>
					</tr>
					<!--/LIST:products-->
					<tr bgcolor="white">
					  <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
					  <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
						  <td align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><strong>T&#7893;ng</strong></td>
					  <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|export_total|]]</strong></td>
				  </tr>
				</table>
			</div>
			<div>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td colspan="2" align="left">&nbsp;</td>
			  </tr>
			  <tr>
				<td align="center" width="50%">&nbsp;</td>
				<td align="right"><em>Ng&#224;y&nbsp;[[|day|]]&nbsp;th&#225;ng&nbsp;[[|month|]]&nbsp;n&#259;m&nbsp;[[|year|]]&nbsp;</em></td>
			  </tr>
			  <tr>
				<td align="center"><strong>Ng&#432;&#7901;i l&#7853;p bi&#7875;u </strong></td>
				<td align="center"><strong>K&#7871; to&aacute;n tr&#432;&#7903;ng</strong> </td>
			  </tr>
			</table>
			</div>
		</div>	
</div>