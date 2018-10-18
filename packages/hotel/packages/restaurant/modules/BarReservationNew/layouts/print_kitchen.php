<div>
<link href="skins/default/room.css" rel="stylesheet" type="text/css">
<table width="250" border="0" cellpadding="0" cellspacing="0" style="border:1px dotted #999999;">
	<tr>
		<td> 
			<table cellSpacing=0 cellPadding=2 border=0 width="100%" style="margin-top:3px">
			<tr height="25">
				<td align="center">
					<table cellpadding="0" width="100%" border="0">
						<tr>
						<td colspan="2" nowrap="nowrap">
						  <p style=" padding-bottom:10px;"><img src="<?php echo HOTEL_LOGO;?>" width="60" align="middle"><span style="font-size: 16px; font-weight:bold;font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ORDER CHO B&#7870;P</span></p></td>
						  </tr>
						<tr>
							<td width="1%" align="left">
								<div id="main_div_class">
									<div id="sub_div_class" style="width:130px;float:left;">Gi&#7901; b&#7855;t &#273;&#7847;u/Time in: </div>
									<div id="sub_div_class">[[|time_begin|]] </div>
								</div>
								<div id="main_div_class">
									<div id="sub_div_class" style="width:130px;float:left;">Gi&#7901; k&#7871;t th&#250;c/Time out: </div>
									<div id="sub_div_class">[[|time_end|]] </div>
								</div>						</td>
							<td align="right" valign="bottom" width="50%">
								Thu ng&acirc;n/Cashier: <?php echo Session::get('user_id');?><br />
								B&agrave;n/Table: <span style="font-weight:bold;font-size:14px;">[[|tables_name|]]</span><br />
								<font style="font-size: 12px">No: [[|order_id|]]</font></td>
						</tr>
					</table>
					<!--IF:cond([[=agent_name=]])-->
					<div style="text-decoration:underline;text-align:right;">[[.order.]]: [[|agent_name|]]</div>
					<!--/IF:cond-->
					<!--IF:cond([[=note=]])--><div style="font-style:italic;text-align:right;"><br />* [[.note.]]: [[|note|]]</div><!--/IF:cond-->
					<hr size="1" color="#CCCCCC">
					<table width="100%" cellpadding="2" cellspacing="0">
					  <tr>
					  	<th width="1%"  nowrap="nowrap">STT<br />No</th>                      
						<th width="40%" align="left">Di&#7877;n gi&#7843;i<br />Description </th>
						<th width="15%" align="center">&#272;V<br />
					    Unit </td>                        
					  	<th width="15%" align="right">SL<br />Q</th>
					  </tr>
					  <tr>
					  	<td colspan="6"><hr size="1" color="#CCCCCC"></td>
					  </tr>
                      <?php $i=1;?>
					  <!--LIST:product_items-->
					  <tr>
					  	<td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="center"><?php echo $i;?></td>
						<td style="border-bottom: 1px solid #CCCCCC;font-size:13px; text-align:left">[[|product_items.product__name|]]</td>
						<td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="center">[[|product_items.product__unit|]]</td>
					  	<td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="right">[[|product_items.product__quantity|]]</td>
					  </tr>
                      <?php $i++;?>
					  <!--/LIST:product_items-->
				  	</table>
					  <!--/IF:check_prepaid-->
					<p>&nbsp;</p>
			  </td>
			</tr> 
			</table>
		</td>
	</tr>
</table>
</div>
<!--IF:cond(Url::get('act')=='print_kitchen')-->
	<script>
		if(window.opener){
			window.opener.location.reload();
		}
	</script>
<!--/IF:cond-->