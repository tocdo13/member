<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
 });
</script>
<table cellSpacing=0 width="100%" bgcolor="#FFFFFF">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		Địa chỉ: <?php echo HOTEL_ADDRESS;?><BR>
		Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
		Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>		</td>
		<td align="right" nowrap width="35%" style="padding-right:20px;">
		<strong>[[.template_code.]]</strong><br />
		<i>[[.promulgation.]]</i>		
        <br />
        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo User::id();?>
        </td>
	</tr>	
	<tr>
    
	<td align="center" colspan="2"><p><font class="report_title">[[.occupancy_holding_report.]]</font><br>
    <!--Start Luu Nguyen Giap add portal-->
    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
    [[.hotel.]]<select name="portal_id" id="portal_id"></select>
    <?php //}?>
    <!--End Luu Nguyen Giap add portal-->
	[[.from_date.]] : <input name="from_date" value="<?php echo Url::get('from_date',date('d/m/Y'));?>"  type="text" id="from_date" style="width:100px;">
	<input name="view_result"  value="[[.view.]]" type="submit" id="view_result">
	</p><br></td>
	</tr>
</table>
</form>
</p>
<!--LIST:months-->
<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="black" style="border-collapse:collapse">
<tr>
	<th width="25%">
		[[|months.name|]]</th>
	<!--LIST:months.days-->
	<td width="25" align="right" bgcolor="[[|months.days.title_bgcolor|]]">[[|months.days.week_day|]]</td>
	<!--/LIST:months.days-->
	<th align="right">Total</th>
</tr>
<tr>
	<td>Date</td>
	<!--LIST:months.days-->
	<td align="right">[[|months.days.id|]]</td>
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
<tr style="background-color: #CCCCBB;">
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
<?php
    if(User::id()=='developer07')
    {
       // System::debug([[=months=]]);
    } 
?>