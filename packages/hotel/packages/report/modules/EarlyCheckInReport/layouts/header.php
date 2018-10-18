<div class="report-bound">
<div >
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<link rel="stylesheet" href="skins/default/report.css">

<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;">
			<tr>
            	<td align="left" width="60%"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
            
            <td align="right">
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
            </td>
            </tr>
            <tr valign="top">
			  
				<td width="100%" align="center" valign="middle" colspan="2">
				<!--IF:cond(Url::get('lately_checkout'))-->
				<font class="report_title" style="font-size:20px;">[[.lately_check_out_report.]]</font>
				<!--ELSE-->
				<font class="report_title" style="font-size:20px;">[[.early_check_in_report.]]</font>
				<!--/IF:cond--><br>
				  <span style="font-family:Arial, Helvetica, sans-serif;font-weight:bold;">
				  
					[[.from.]]&nbsp;[[|from_day|]]&nbsp;[[.to.]]&nbsp;[[|to_day|]]
			      </span>
              </td>

			</tr>	
		</table>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"></div>
		<br />
		<!--/IF:first_page-->
