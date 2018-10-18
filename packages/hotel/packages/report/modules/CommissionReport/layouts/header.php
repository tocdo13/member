<link rel="stylesheet" href="skins/default/report.css">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
		<table border="0" cellSpacing=0 cellpadding="5" width="100%">
			<tr valign="middle">
			<td width="100"><img src="<?php echo HOTEL_LOGO;?>" width="100" /></td>
			  <td align="left"><br />
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
				ADD: <?php echo HOTEL_ADDRESS;?><BR>
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
			  </td>
			  <td align="right">
				  <span style="font-weight:bold;">[[.customer.]]: [[|name|]]</span><br />
				  <span><!--IF:cond(Url::get('date_from'))-->[[.date_from.]]: <?php echo Url::get('date_from');?><!--/IF:cond--> [[.date_to.]]: <?php echo Url::get('date_to')?Url::get('date_to'):date('d/m/Y',time());?></span>
                  <br />
                  [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                    <br />
                    [[.user_print.]]:<?php echo User::id();?>
			  </td>
			</tr>	
			<tr>
				<td colspan="3">
					<center>
					  <h3 class="report_title">[[.commission_report.]]</h3>
				  </center>
				</td>
			</tr>
		</table>
		<!--/IF:first_page-->
