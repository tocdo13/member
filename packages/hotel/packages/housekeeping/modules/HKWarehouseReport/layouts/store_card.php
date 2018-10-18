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
<!--				<td align="right">M&#7851;u s&#7889;:S12-SKT/DNN<br>
		Ban h&agrave;nh theo Q&#272; s&#7889; 1177/TC/Q&#272;/C&#272;KT<br>
		ng&agrave;y 23-12-1996 c&#7911;a B&#7897; T&agrave;i ch&iacute;nh<br>
		</td>
-->			</tr>
		</table>
	</div>
	  <div style="width:720px;">
			<div style="text-align:center;">
				<div class="report_title">[[.store_card.]]<br>([[.store_book.]])</div>
				[[.code.]]: [[|code|]], [[.name.]]: [[|name|]] <br>
				[[.date_from.]] [[|date_from|]] [[.to.]] [[|date_to|]]<br><br />
			</div>
			<div>
				<table cellpadding="2" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
					<tr valign="middle" bgcolor="#EFEFEF">
						<th colspan="3" class="report_table_header">[[.invoice.]]</th>
						<th rowspan="2" class="report_table_header">[[.note.]]</th>
						<th colspan="3" class="report_table_header">[[.number.]]</th>
					</tr>
					<tr><th rowspan="1" class="report_table_header">[[.invoice_date.]]</th>
					<th rowspan="1" class="report_table_header">[[.import_code.]]</th>
					<th rowspan="1" class="report_table_header">[[.export_code.]]</th>
					<th rowspan="1" class="report_table_header">[[.import.]]</th>
					<th rowspan="1" class="report_table_header">[[.export.]]</th>
					<th rowspan="1" class="report_table_header">[[.store_remain.]]</th>
					</tr>
					<tr><td align="center">1</td><td align="center">2</td><td align="center">3</td><td align="center">4</td><td align="center">5</td><td align="center">6</td><td align="center">7</td></tr>
					<tr bgcolor="white">
						<td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
						<td width="200" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">[[.start_period_remain.]]</td>
						<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
						<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
						<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><strong>[[|start_remain|]]</strong></td>
					</tr>
					<tr>
						<td colspan="7" class="report_sub_title" align="right"><b>&nbsp;</b></td>
					</tr>
					<!--LIST:products-->
					<tr bgcolor="white">
						<td nowrap align="left" class="report_table_column" width="70">
								[[|products.create_date|]]			</td>
						<td nowrap align="left" class="report_table_column" width="70">
								[[|products.import_invoice_code|]]				  </td><td nowrap align="left" class="report_table_column" width="70">
								[[|products.export_invoice_code|]]
					  </td>
							<td nowrap align="left" class="report_table_column" width="200">
								[[|products.note|]]			</td>
							<td nowrap align="right" class="report_table_column" width="100">
								[[|products.import_number|]]			</td><td nowrap align="right" class="report_table_column" width="100">
								[[|products.export_number|]]
							</td>
							<td nowrap align="right" class="report_table_column" width="100">[[|products.remain|]]						</td>
					</tr>
					<!--/LIST:products-->
					<tr>
						<td colspan="7" class="report_sub_title" align="right"><b>&nbsp;</b></td>
					</tr>
					<tr bgcolor="white">
					  <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
					  <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
					  <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
					  <td align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">T&#7893;ng</td>
					  <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|import_total|]]</strong></td>
					  <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|export_total|]]</strong></td>
					  <td align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
				  </tr>
					<tr bgcolor="white">
						<td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="200" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">[[.end_period_remain.]]</td>
						<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
						<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><strong>[[|end_remain|]]</strong></td>
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