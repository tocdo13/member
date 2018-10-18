<link rel="stylesheet" href="skins/default/report.css"/>
<link rel="stylesheet" href="packages/core/skins/default/css/jquery/datepicker.css" />
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
    <td align="center">
    	<table border="0" cellSpacing="0" cellpadding="5" width="100%">
    		<tr valign="middle">
                <td width="100"><img src="<?php echo HOTEL_LOGO;?>" width="100" /></td>
                <td align="left">
                    <br />
                    <strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                    ADD: <?php echo HOTEL_ADDRESS;?>
                    <br />
                    Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?>
                    <br />
                    Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
                </td>
    		</tr>
            <tr><td>&nbsp;</td></tr>	
    		<tr>
    			<td colspan="2" style="text-align:center;">
                        <font class="report_title specific" >[[.agent_or_company_statistic_report.]]<br /><br /></font>
                        <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                            [[.date_from.]]&nbsp;[[|date_from|]]&nbsp;[[.date_to.]]&nbsp;[[|date_to|]]
                        </span> 
    			</td>
    		</tr>
    	</table>
    </td>
</tr>
</table>
<!--/IF:first_page-->