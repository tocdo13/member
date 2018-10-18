<script>
jQuery(document).ready(function(){
	jQuery('#date_from').datepicker();
	jQuery('#date_to').datepicker();
 });

</script>
<link rel="stylesheet" href="skins/default/report.css">
<table cellSpacing=0 width="99%">
<tr>
<td>
<form name="GuestTennisReportForm" method="post" id="GuestTennisReportForm">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td width="50%" align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		ADD: <?php echo HOTEL_ADDRESS;?><BR>
		Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
		Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>		</td>
		<td align="right" nowrap width="50%" style="padding-right:20px;">
		<strong>[[.template_code.]]</strong><br />
		<i>[[.promulgation.]]</i>		</td>
	</tr>	
	<tr>
		<td align="center" colspan="2"><p><font class="report_title">[[.guest_tennis_report.]]</font><br><br></td>
	</tr>
	<tr>
		<td align="right">
			[[.date_from.]]&nbsp;<input name="date_from" type="text" id="date_from" style="width:80px;height:15px;">
		</td>
		<td align="left">
			&nbsp;[[.date_to.]]&nbsp;<input name="date_to" type="text" id="date_to" style="width:80px;height:15px;">
			&nbsp;<input name="view" type="submit"  value="[[.view.]]" id="view" style="width:60px;height:23px;">
		</td>
	</tr>
</table>
</form>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th class="report_table_header">[[.stt.]]</th>
		<th class="report_table_header">[[.customer_name.]]</th>
		<th class="report_table_header">[[.invoice_date.]]</th>
		<th class="report_table_header">[[.minuties.]]</th>
		<th class="report_table_header">[[.status.]]</th>
		<th class="report_table_header">[[.total_price.]]</th>
	</tr>
	<?php 
	$i=0;
	$total_amount = 0;
	?>
	<!--LIST:items-->
	<tr>
	  <td><?php echo ++$i;?></td>	
		<td>[[|items.full_name|]]</td>
		<td><?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
		<td>[[|items.minutes|]]</td>
		<td>[[|items.status|]]</td>
		<td align="right">
		<?php 
			$total_amount+=System::display_number_report([[=items.total_amount=]]);
			echo System::display_number_report([[=items.total_amount=]]);
		?></td>
	</tr>
	<!--/LIST:items-->
	<tr>
		<td colspan="5" align="right"><strong>[[.total_amount.]]</strong></td>
		<td align="right"><b><?php echo System::display_number_report($total_amount);?></b></td>
	</tr>
	<tr>
		<td colspan="6">[[|paging|]]</td>
	</tr>
</table>
</td>
</tr>
</table>
<br>