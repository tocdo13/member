<div style="width:720px;padding:10px;text-align:center;font-size:14px;float:left;">	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<td align="right">
			[[.no1.]]: [[|bill_number|]]<br />
			[[.day.]]:&nbsp;[[|day|]]/[[|month|]]/[[|year|]]
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
                        [[.warehouse.]]:
                        <!--IF:cond(Url::get('type')=='IMPORT' or [[=supplier_id=]])-->
                        [[|supplier_name|]]<br/>
                        <!--ELSE-->
                        [[|warehouse_name|]] <br/>
                        <!--/IF:cond-->
                        [[.deliver.]]: [[|deliver_name|]] <br />
                        [[.receiver.]]: [[|receiver_name|]						
                    </td>
                    <td width="30%" align="right" nowrap="nowrap"  style="font-size:12px;">
                        Nh&acirc;n vi&ecirc;n: [[|staff_name|]]<br />
                        <!--IF:cond([[=type=]]=='EXPORT')-->
                        [[.from.]] <strong>[[|warehouse_name|]]</strong> [[.to.]] <strong>[[|to_warehouse_name|]]</strong><br />
                        <!--ELSE-->
                        <strong>[[|warehouse_name|]]</strong><br />
                        <!--/IF:cond-->
                    </td>
                </tr>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left">[[.description.]] 
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
				<th width="4%" scope="col">[[.no.]]</th>
                    <th width="25%" align="center" scope="col">[[.product_name.]] <br /></th>
                    <th width="11%" align="center" scope="col">[[.code.]]</th>
                    <th width="10%" scope="col" align="center">[[.unit.]]</th>
                    <th width="10%" scope="col" align="center">[[.quantity.]]  </th>
                    <th width="100" scope="col" align="center">[[.price.]] </th>
                    <th width="160" scope="col" align="center">[[.amount.]] </th>
			  </tr>
			  <tr>
				<td align="center">A</td>
				<td align="center">B</td>
				<td align="center">C</td>
				<td align="center">D</td>
				<td align="center">E</td>
				<td align="center">F</td>
				<td align="center">1</td>
			  </tr>
			  <!--LIST:products-->
			  <tr>
				<td align="center">[[|products.i|]]</td>
				<td align="left" style="padding:0 0 0 10px;">[[|products.name|]]</td>
				<td align="center" nowrap="nowrap">[[|products.product_id|]]</td>
				<td align="center">[[|products.unit_name|]]</td>
				<td align="right">[[|products.price|]]</td>
				<td align="right">[[|products.num|]]</td>
				<td align="right">[[|products.payment_price|]]</td>
			  </tr>
  			  <!--/LIST:products-->
			 <?php //for($i=0;$i<=20;$i++){?><tr>
			   <!-- <td>&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    <td align="center">&nbsp;</td>
			    </tr> -->
			  <?php 
			  /*if($i==1)
			  {
			  	echo '<div style="display:none;page-break-after:always;">';
			  }
			  } */?><tr>
                <td>&nbsp;</td>
				<td align="center">[[.total.]] </td>
				<td align="center">x</td>
				<td align="center">x</td>
				<td align="center">x</td>
				<td align="center">x</td>
				<td align="right">[[|total|]]</td>
				</tr>
		  </table>
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
                <td colspan="4" align="right">
                    [[.amount_in_words.]]:&nbsp;<em>[[|total_by_letter|]]</em> <br />
                </td>
            </tr>
			<tr>
			  <td align="center">&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td colspan="2" align="right"><em>[[|day|]]/[[|month|]]/[[|year|]]&nbsp;</em></td>
		  </tr>
			<tr>
				<td align="center" width="25%">[[.leader.]]</td>
				<td width="25%" align="center">[[.shipper.]]<br /></td>
				<td width="25%" align="center"><span style="width:25%;text-align:center;">[[.warehouseman.]]<br /></td>
				<td width="25%" align="center"><span style="width:25%;text-align:center;">[[.receiver.]]<br /></td>
			</tr>
		</table>

	</div>
</div>
