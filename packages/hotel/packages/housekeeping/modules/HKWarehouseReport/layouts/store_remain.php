<link rel="stylesheet" href="skins/default/report.css">
<div class="report-bound">
<div style="text-align:left;" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" valign="top">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<td align="right" valign="top"><strong>[[.Warehouse.]]: B&#7897; ph&#7853;n Bu&#7891;ng</strong><br />
</td>
	</tr>
</table>
	<div style="width:99%;" >
		<div style="padding:2px 2px 2px 2px;">
		<div class="report_title" align="center">[[|title|]]</div>
		<div>
			<table width="100%">
				<tr valign="top">
					<td style="font-size:12px;text-align:center;">T&#7915; ng&agrave;y [[|date_from|]] &#273;&#7871;n ng&agrave;y [[|date_to|]]</td>
				</tr>
			</table>
	    </div>
		<div style="padding:2px 2px 2px 2px;text-align:left;">
			&nbsp;
		</div>
	    <div style="text-align:left;">
			<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
			  <tr>
				<th class="report_table_header" width="10%" align="left" scope="col">M&atilde; h&agrave;ng <br /></th>
				<th class="report_table_header" width="40%" align="left" scope="col">T&ecirc;n h&agrave;ng </th>
				<th class="report_table_header" width="10%" align="center" scope="col">&#272;VT</th>
				<th class="report_table_header" align="center" scope="col">T&#7891;n &#273;&#7847;u k&#7923; </th>
				<th class="report_table_header" align="center" scope="col">Nh&#7853;p trong k&#7923; </th>
				<th class="report_table_header" align="center" scope="col">Xu&#7845;t trong k&#7923; </th>
				<th class="report_table_header" scope="col" align="center">T&#7891;n cu&#7889;i k&#7923; </th>
			  </tr>
			  <!--LIST:products-->
			  <tr>
			    <td align="left">[[|products.product_id|]]</td>
			    <td align="left">[[|products.name|]]</td>
			    <td align="center">[[|products.unit|]]</td>
			    <td align="right">[[|products.start_term_quantity|]]</td>
			    <td align="right">[[|products.import_number|]]</td>
			    <td align="right">[[|products.export_number|]]</td>
			    <td align="right">[[|products.remain_number|]]</td>
		      </tr>
  			  <!--/LIST:products-->
			  <?php for($i=0;$i<=20;$i++){?><tr>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
		      </tr>
			  <?php 
			  if($i==1)
			  {
			  	echo '<div style="display:none;page-break-after:always;">';
			  }
			  }?>
		  </table>
		</div>
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
</div>
