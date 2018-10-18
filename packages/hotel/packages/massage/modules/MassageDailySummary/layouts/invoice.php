<style type="text/css">
.title{
	font-size:20px;
	font-weight:bold;
    text-transform:uppercase;
}
a.report_link
{
	font-weight:bold;
	color:#000000;
	text-decoration:none;
}
a.report_link:hover
{
	font-weight:bold;
	color:#FF6600;
	text-decoration:underline;
}
.td_header
{
}
.data_title 	
{
	font-weight:bold;
}
.no_border
{
	font-weight:bold;
	border-bottom:1px #FFFFFF solid;
}
.total-amount{
	
	background:#EFEFEF;
	float:right;
	padding:2px;
	border:1px solid #CCCCCC;
	white-space:nowrap;
	width:300px;
}
.total-amount span{
	width:100px;
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
th{
	font-size:11px;
}
td{
	font-size:11px;
}
.margin-top{
    margin-top: 0px;
}
</style>
<table width="600px;" border="0" cellpadding="0" cellspacing="0" align="left">
	<tr>
		<td width="100%">
			<table cellSpacing=0 cellPadding=2 border=0 width="100%">
            <tr>
                <td>
                    <table width="100%" >
                        
                        
                        <tr>
                            <td width="18%" align="left" valign="top" class="logo" style="padding-bottom: 10px;"><img width="90" src="<?php echo HOTEL_LOGO;?>"/></td>
                            <td style="width: 82%;text-align: center; padding-top: 20px; " valign="top"> 
                                <span style="font-size: 20px; text-transform: uppercase; color: black;">[[.spa_invoice.]]</span><br />
                                <span style="font-size: 11px; font-weight: normal !important;"><?php echo HOTEL_ADDRESS;?><br/>[[.Tel.]]: <?php echo HOTEL_PHONE?> | [[.Email.]]:  <?php echo HOTEL_EMAIL?> | [[.Website.]]: <?php echo HOTEL_WEBSITE?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
			<tr>
				<td align="center">
				<table cellpadding="0" width="100%" border="0">
    				
                    <tr>
    					<td  style="text-align: left;width: 60%;">
    						[[.guest_name.]]:  [[|guest_name|]]
    					</td>
                        <td style="text-align: right;">No : <span style="font-weight:bold">[[|id|]]</span></td>
    				</tr>
                    <tr>
                        <td  align="left">
    						[[.room_no.]]:  [[|hotel_room|]]
    					</td>
                        <td style="text-align: right;">[[.date.]]: [[|time|]]</td>
                    </tr>
                    <tr>
                        <td  align="left">
    						[[.cashier.]]:  [[|user_checkout|]]
    					</td>
                        <td style="text-align: right;">[[.currency.]]: VND</td>
                    </tr>
				</table>
				<br />
				<div style="width:100%;">
					<!--IF:cond1([[=products=]])-->
					<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000" style="border-collapse: collapse;">
					  <tr bgcolor="#EFEFEF">
					    <th width="10%" nowrap="nowrap">R</th>
					    <th align="left" width="30%">[[.name.]]</th>
					    <th width="15%" align="right">[[.price.]]</th>
					    <th align="center" width="10%" nowrap="nowrap">[[.no_of_people.]]</th>
						<th width="20%" align="right">[[.amount.]]</th>
				      </tr>
					  <!--LIST:products-->
					  <tr valign="top">
					    <td align="center" nowrap="nowrap">[[|products.room_name|]]</td>
					    <td align="left">
							[[|products.name|]]
						</td>
					    <td align="right">[[|products.price|]]</td>
						<td align="center">[[|products.quantity|]]</td>
						<td align="right">[[|products.amount|]]</td>
					  </tr>
					 <!--/LIST:products-->						 
					 <tr>
					    <td colspan="3" align="right"><strong>[[.total.]]</strong>:</td>
					    <td align="center"><strong>[[|total_quantity|]]</strong></td>
						<td align="right"><strong>[[|total_amount_|]]</strong></td>
					  </tr>
					</table>
					<br/>
					<!--/IF:cond1-->
                    <table width="100%" border="0" cellspacing="0" cellpadding="2" bordercolor="#000000" style="border-collapse: collapse;">
                        <!--IF:cond([[=discount_amount=]]>0)-->
                        <tr>
                        <th width="100%" align="right">[[.discount_amount.]]:</th>
                        <th align="right"><?php echo(System::display_number([[=discount_amount=]]));?></th>
                        </tr>
                        <!--/IF:cond-->
                        <!--IF:cond([[=discount=]]>0)-->
                        <tr>
                            <th width="100%" align="right">[[.discount.]] ([[|discount|]]%):</th>
                            <th align="right">[[|discount_amount_persent|]]</th> 
                        </tr>
                        <!--/IF:cond-->
                       
<!-- KID THEM THUE PHI-->
                        <tr>
                            <th align="right">[[.service_rate_amount.]]:</th>
                            <th align="right"><?php echo System::display_number(round(([[=service_rate_amount=]])))?></th>
                        </tr>
                        <tr>
                            <th align="right">[[.tax_amount.]]:</th>
                            <th align="right"><?php echo System::display_number(round([[=tax_amount=]]))?></th>
                        </tr>
                        <tr>
                            <th align="right">[[.total_amount.]]:</th>
                            <th align="right"><?php echo System::display_number([[=total_amount=]])?></th>
                        </tr>
			
			<?php
                        	if(isset([[=package_amount=]]))
                        	{
                        		?>
                        		<tr>
                        			<th align="right">[[.package_amount.]]:</th>
                        			<th align="right"><?php echo System::display_number([[=package_amount=]])?></th>
                        		</tr>
                        		<?php 
                        	} 
                            
                            
                        ?>
                    </table>
                    
                    <?php if(sizeof([[=payment_list=]])>0){ ?>
                    <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" style="font-weight: bold; margin-top: 3px;border-collapse: collapse;">
                        <tr>
                            <th colspan="2" style="font-size: 15px;">[[.payment.]]</th>
                        </tr>
                       
                        <?php foreach($this->map['payment_list'] as $k => $v)
                        {
                        ?>
                        <tr>
                            <td style="font-size: 15px;"><?php echo $v['payment_type_name'] ?></td>
                            <td style="font-size: 15px;text-align: right;"><?php echo System::display_number($v['amount']); ?><?php if($v['currency_id']) echo $v['currency_id'] ?></td>
                        </tr>
                        <?php
                        }
                        ?> 
                    </table>
                    
                    
                    <?php } ?><br />
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: right; font-weight: bold; font-size: 15px;">
                                [[.tip_amount.]]: [[|total_tip|]]
                            </td>
                        </tr>
                    </table>
					<br clear="all"/>
				</div>
                
				<p>&nbsp;</p><p>&nbsp;</p>
				<table width="100%" border="0" cellpadding="0">
				  <tr>
					<td width="40%" nowrap="nowrap" align="center" style="border-top:1px solid #000000;">Ch&#7919; k&yacute; nh&#226;n vi&#234;n <br/> Receptionist Signature</td>
					<td width="20%">&nbsp;</td>
					<td width="40%" align="center" valign="middle" style="border-top:1px solid #000000;">Ch&#7919; k&yacute; c&#7911;a kh&aacute;ch <br />Guest Signature</td>
				  </tr>
				  <tr>
					<td colspan="3" align="center" valign="middle"></td>
				  </tr>
				</table>
</td>
</tr>
</table>
    </td>
    </tr>
</table>
<div style="page-break-inside:"></div>
