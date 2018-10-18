<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
 });
</script>
<table cellSpacing=0 width="100%">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%" cellpadding="2">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		ADD: <?php echo HOTEL_ADDRESS;?><BR>
		Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
		Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>		</td>
		<td align="right" nowrap width="35%" style="padding-right:20px;">
		<strong>[[.template_code.]]</strong></td>
	</tr>	
	<tr>
	<td align="center" colspan="2"><span class="report_title">[[.room_focast.]]</span><br><br />
	[[.from_date.]] : <input name="from_date" value="<?php echo Url::get('from_date',date('d/m/Y'));?>"  type="text" id="from_date" style="width:100px;">
	[[.to_date.]] : <input name="to_date" value="<?php echo Url::get('to_date',date('d/m/Y'));?>"  type="text" id="to_date" style="width:100px;">
	<input name="view_result"  value="[[.view.]]" type="submit" id="view_result"><br></td>
	</tr>
</table>
</form>
</p>
<!--LIST:months-->
<table width="99%" cellpadding="2" cellspacing="0" border="1" bordercolor="black" style="border-collapse:collapse">
<tr bgcolor="#EFEFEF">
	<th width="200">
		[[|months.name|]]	</th>
	<!--LIST:months.days-->
	<td align="right">[[|months.days.id|]]</td>
	<!--/LIST:months.days-->
	<th align="right">Total</th>
</tr>
<tr>
	<td>Date</td>
	 <!--LIST:months.days-->
	<td width="25" align="right" bgcolor="[[|months.days.title_bgcolor|]]">[[|months.days.week_day|]]</td>
	<!--/LIST:months.days-->
	<th>&nbsp;</th>
</tr>
<tr>
	<td>Total</td>
	<!--LIST:months.days-->
	<td align="right">[[|months.days.total|]]</td>
	<!--/LIST:months.days-->
	<th align="right"><?php echo System::display_number([[=months.total=]]);?></th>
</tr>
<tr>
	<td>R.sold</td>
	<!--LIST:months.days-->
	<td align="right">[[|months.days.rsold|]]</td>
	<!--/LIST:months.days-->
	<th align="right">[[|months.rsold|]]</th>
</tr>
<tr>
  <td>Room not used </td>
  <!--LIST:months.days-->
  <td align="right"><?php echo [[=months.days.total=]]-[[=months.days.rsold=]];?></td>
<!--/LIST:months.days-->
  <th align="right"><?php echo System::display_number([[=months.total=]]-[[=months.rsold=]]);?></th>
</tr>
<tr>
	<td>Per Used %</td>
	<!--LIST:months.days-->
	<td align="right" bgcolor="[[|months.days.bgcolor|]]">[[|months.days.percent|]]</td>
	<!--/LIST:months.days-->
	<th align="right">[[|months.percent|]]</th>
</tr>
</table>
<p>&nbsp;</p>
<!--/LIST:months-->
</td>
</tr>
</table>