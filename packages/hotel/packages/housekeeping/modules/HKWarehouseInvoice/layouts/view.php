<div style="width:720px;padding:10px;text-align:center;font-size:14px;float:left;">	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" valign="top">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<td align="right" valign="top">
        	<strong>B&#7897; ph&#7853;n Bu&#7891;ng</strong><br />
			S&#7889;: [[|bill_number|]]<br />
			Ng&#224;y:&nbsp;[[|day|]]/[[|month|]]/[[|year|]]
		</td>
	</tr>
</table>
</div><br clear="all">
<div style="text-align:left;">
	<div style="width:720px;padding:2px 2px 2px 2px;text-align:center;font-size:14px;">
		<div style="padding:2px 2px 2px 2px;">
		<div style="text-indent:0px;vertical-align:top;font-size:16px;text-transform:uppercase;font-weight:bold;">[[|title|]]</div>
		<div>
			<table width="100%">
				<tr valign="top">
				  <td width="70%" style="font-size:12px;text-align:left">
						&#272;&#417;n v&#7883;:
						<!--IF:cond(Url::get('type')=='IMPORT')-->
						[[|supplier_name|]]<br>
   						 <!--ELSE-->
						[[|warehouse_name|]] <br>
						<!--/IF:cond-->
    					Ng&#432;&#7901;i giao: [[|deliver_name|]] <br />
					  Ng&#432;&#7901;i nh&#7853;n: [[|receiver_name|]						</td>
					<td width="30%" align="right" nowrap="nowrap"  style="font-size:12px;">Nh&acirc;n vi&ecirc;n: [[|staff_name|]]<br />
					  <!--B&#7897; ph&#7853;n c&#244;ng t&#225;c: [[|department|]]--></td>
			  </tr>
				<tr valign="top">
				  <td style="font-size:12px;text-align:left">Di&#7877;n gi&#7843;i: 
			      <!--IF:cond([[=note=]])--><em>[[|note|]]</em><!--ELSE-->...<!--/IF:cond--></td>
				  <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
			  </tr>
			</table>
	    </div>
		<div style="padding:2px 2px 2px 2px;text-align:left;">
			&nbsp;
		</div>
	    <div style="text-align:left;">
			<table width="100%" border="1" cellspacing="0" cellpadding="2" style="border-collapse:collapse" bordercolor="#000000">
			  <tr>
				<th width="4%" scope="col">STT</th>
				<th width="30%" align="center" scope="col">T&ecirc;n SP, HH <br /></th>
				<th width="11%" align="center" scope="col">M&atilde; s&#7889; </th>
				<th width="15%" scope="col" align="center">s&#7889; l&#432;&#7907;ng  </th>
			  </tr>
			  <tr>
				<td align="center">A</td>
				<td align="center">B</td>
				<td align="center">C</td>
				<td align="center">D</td>
			  </tr>
			  <!--LIST:products-->
			  <tr>
				<td align="center">[[|products.i|]]</td>
				<td align="left" style="padding:0 0 0 10px;">[[|products.name|]]</td>
				<td align="center" nowrap="nowrap">[[|products.product_id|]]</td>
				<td align="right">[[|products.number|]]</td>
			  </tr>
  			  <!--/LIST:products-->
			  <?php for($i=0;$i<=20;$i++){?><tr>
			    <td>&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
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
			  <td align="center">&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td colspan="2" align="right"><em>Ng&#224;y&nbsp;[[|day|]]&nbsp;th&#225;ng&nbsp;[[|month|]]&nbsp;n&#259;m&nbsp;[[|year|]]&nbsp;</em></td>
		  </tr>
			<tr>
				<td align="center" width="25%">Th&#7911; tr&#432;&#7903;ng &#273;&#417;n v&#7883;<br />
              <em>(K&#253;, h&#7885; t&#234;n)</em></td>
				<td width="25%" align="center">Ng&#432;&#7901;i giao h&agrave;ng<br />
              <em>(K&#253;, h&#7885; t&#234;n)</em></td>
				<td width="25%" align="center"><span style="width:25%;text-align:center;">Th&#7911; kho<br />
                    <em>(K&#253;, h&#7885; t&#234;n)</em></span></td>
				<td width="25%" align="center"><span style="width:25%;text-align:center;">Ng&#432;&#7901;i nh&#7853;n h&agrave;ng<br />
                    <em>(K&#253;, h&#7885; t&#234;n)</em></span></td>
			</tr>
		</table>

	</div>
</div>
