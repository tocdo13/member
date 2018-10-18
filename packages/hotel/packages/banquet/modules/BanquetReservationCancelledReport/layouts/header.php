<!--IF:first_page([[=page_no=]]==1)-->
<div >
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('Bar_reservation_cancelled_report'));?>
<link rel="stylesheet" href="skins/default/report.css">
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td align="left" width="100%">
				<strong><?php echo HOTEL_NAME;?></strong><br /><?php echo HOTEL_ADDRESS;?>
				</td>
				<td align="center" nowrap>
				<strong>[[.template_code.]]</strong>
				</td>
			</tr>	
		</table>
		<div style="line-height:40px;"><font class="report_title">[[.Banquet_reservation_cancelled_report.]]</font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		[[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
		<br />
		<!--/IF:first_page-->
