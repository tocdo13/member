<link rel="stylesheet" href="skins/default/report.css">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
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
				  <span style="font-weight:bold;"><?php echo Portal::language('customer');?>: <?php echo $this->map['name'];?></span><br />
				  <span><?php 
				if((Url::get('date_from')))
				{?><?php echo Portal::language('date_from');?>: <?php echo Url::get('date_from');?>
				<?php
				}
				?> <?php echo Portal::language('date_to');?>: <?php echo Url::get('date_to')?Url::get('date_to'):date('d/m/Y',time());?></span>
                  <br />
                  <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                    <br />
                    <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
			  </td>
			</tr>	
			<tr>
				<td colspan="3">
					<center>
					  <h3 class="report_title"><?php echo Portal::language('commission_report');?></h3>
				  </center>
				</td>
			</tr>
		</table>
		
				<?php
				}
				?>
