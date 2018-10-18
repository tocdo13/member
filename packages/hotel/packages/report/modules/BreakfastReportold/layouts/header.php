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
				<td width="60%" align="center" valign="middle"><font class="report_title">[[.traveller_report.]]<br />
				  <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
				  	<!--IF:today([[=today=]] and [[=today=]]==1)-->
						[[.today.]]
					<!--ELSE-->
					[[.from.]]&nbsp;[[|from_day|]]/[[|from_month|]]/[[|from_year|]]&nbsp;[[.to.]]&nbsp;[[|to_day|]]/[[|to_month|]]/[[|to_year|]]
				  	<!--/IF:today-->
					<!--IF:cond([[=status=]]!="0")-->
						<br>
						[[.status.]]:&nbsp;[[|status|]]
					<!--/IF:cond-->
			      </span>
              </td>
				<td width="20%" align="center" valign="middle" nowrap>
				<strong>[[.template_code.]]</strong><br />
				<i><br />
				<br />
				</i>				</td>
			</tr>	
		</table>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"></div>
		<br />
		<!--/IF:first_page-->
