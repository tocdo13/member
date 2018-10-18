<div class="report-bound">
<div >
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css">

<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%">
			<tr>
           		 <td align="left" width="60%"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
            <td align="right">
            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
            </td>
            </tr>
            <tr valign="top">
			  
				<td width="100%" align="center" valign="middle" colspan="2"><span class="report_title">[[.group_tour_report.]]</span><br />
				  <span>
				  	<!--IF:today([[=today=]] and [[=today=]]==1)-->
						[[.today.]]
					<!--ELSE-->
					[[.from.]]&nbsp;[[|from_day|]]/[[|from_month|]]/[[|from_year|]]&nbsp;[[.to.]]&nbsp;[[|to_day|]]/[[|to_month|]]/[[|to_year|]]
				  	<!--/IF:today-->
					<!--IF:cond([[=status=]]!="0")-->
						
					<!--/IF:cond-->
			      </span>
              </td>
			</tr>	
		</table>
		<br />
		<!--/IF:first_page-->
