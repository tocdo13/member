<div class="report-bound">
<div >
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css">

<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
			  <td align="left" width="20%"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
				<td width="60%" align="center" valign="middle"><font class="report_title">[[.breakfast_report.]]<br />
				  <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
				  	<!--IF:today([[=today=]] and [[=today=]]==1)-->
						[[.today.]]
					<!--ELSE-->
					[[.from.]]&nbsp;<?php echo Url::get('date_from') ?>&nbsp;[[.to.]]&nbsp;<?php echo Url::get('date_to') ?>
				  	<!--/IF:today-->
			      </span>
              </td>
				<td width="20%" align="right" valign="middle" nowrap>
				<strong>[[.template_code.]]</strong><br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
				<i><br />
				<br />
				</i>				</td>
			</tr>	
		</table>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"></div>
		<br />
		<!--/IF:first_page-->
