<div>
<link href="skins/default/room.css" rel="stylesheet" type="text/css">
<table width="290px" border="0" cellpadding="0" cellspacing="0" style="border:1px dotted #999999;margin-left:5px;">
	<tr>
		<td> 
		<table cellSpacing=0 cellPadding=2 border=0 width="290px" style="margin-top:3px">
			<tr height="25">
				<td align="center">
					<table cellpadding="3" width="290px" border="0">
						<tr>
							<td colspan="2" style="padding-bottom:5px;width: 200px;">
						  <img src="<?php echo HOTEL_LOGO;?>" width="60" align="middle"><span style="font-size: 16px; font-weight:bold;font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ORDER CHO <?php if(Url::get('type')=='drink'){?>PHA CH&#7870;<?php }else{?>B&#7870;P<?php }?></span>
                          <div align="right" style="font-weight:bold;font-size:16px;float:right">No: [[|order_id|]]</div><br>
                          </td>
						 </tr>
						<tr>
							<td width="1%" valign="top" align="left" style="border-top:1px solid #CCCCCC;border-bottom:1px solid #CCCCCC;">
								<div id="main_div_class">
									<div id="sub_div_class" style="width:130px;float:left;">Gi&#7901; b&#7855;t &#273;&#7847;u/Time in: </div>
									<div id="sub_div_class">[[|time_begin|]] </div>
								</div>
							<td align="right" valign="bottom" width="50%" style="border-top:1px solid #CCCCCC;border-bottom:1px solid #CCCCCC;">
								Thu ng&acirc;n/Cashier: <?php echo Session::get('user_id');?><br />
								B&agrave;n/Table: <span style="font-weight:bold;font-size:12px;">[[|tables_name|]]</span><br /></td>
						</tr>
					</table>
					<!--IF:cond([[=agent_name=]])-->
					<div style="text-decoration:underline;text-align:right;">[[.order.]]: [[|agent_name|]]</div>
					<!--/IF:cond-->
					<!--IF:cond([[=note=]])--><div style="font-style:italic;text-align:right; font-size:10px;"><br />* [[.note.]]: [[|note|]]</div><!--/IF:cond-->
					<hr size="1" color="#CCCCCC">
					<table width="100%" cellpadding="2" cellspacing="0">
					  <tr>
					  	<th width="1%"  nowrap="nowrap">STT<br />No</th>                      
						<th width="40%" align="left">Di&#7877;n gi&#7843;i<br />Description </th>
                        <!--
						<th width="15%" align="center">&#272;V<br />Unit </th>
					  	<th width="15%" align="right">[[.order_quantity.]]<br />Order</th>-->
                        <th width="15%" align="right">[[.printed_quantity.]]<br />Printed</th>
                        <th width="15%" align="right">[[.note.]]<br /></th>
					  </tr>
					  <tr>
					  	<td colspan="6"><hr size="1" color="#CCCCCC"></td>
					  </tr>
                      <?php $i=1;?>
					  <!--LIST:product_items-->
					  <tr>
					  	<td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="center"><?php echo $i;?></td>
                        <td style="border-bottom: 1px solid #CCCCCC;font-size:13px; text-align:left">[[|product_items.product__name|]]</td><!--
						<td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="center">[[|product_items.product__unit|]]</td>
                        <td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="right">[[|product_items.product__quantity|]]</td>-->
					  	<td style="border-bottom: 1px solid #CCCCCC;font-size:13px;" align="right">[[|product_items.product__printed|]]</td>
                        <td style="border-bottom: 1px solid #CCCCCC;font-size:13px; text-align:left">[[|product_items.product__note|]]</td>
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