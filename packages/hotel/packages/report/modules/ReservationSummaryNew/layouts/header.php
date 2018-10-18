<div class="report-bound">
<div >
<link rel="stylesheet" href="skins/default/report.css">

<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr>
            	 <td align="left" width="60%"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
            
            <td align="right">
            Date:<?php echo date('d/m/Y H:i');?>
            <br />
            User:<?php echo User::id();?>
            </td>
            </tr>
            <tr valign="top">
			 
				<td width="100%" align="center" valign="middle" colspan="2">
                  <font class="report_title">[[.<font class="report-title"></font>.]][[.reservation_summary_by_seller.]]<br /><br />
				  <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
					[[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
		  		<br>
			      </span>
              </td>
			</tr>	
		</table>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"></div>
		<br />
