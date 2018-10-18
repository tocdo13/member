<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<script>full_screen();</script>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
			</tr>	
		</table>
		<font class="report_title">[[.restaurant_revenue_report.]]</font>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><?php Report::display_date_params();?><br />
