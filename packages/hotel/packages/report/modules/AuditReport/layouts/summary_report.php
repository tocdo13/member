<form name="SummaryReportForm" method="post">
<div>
    <div>
      <h3>[[.night_audit_report_for.]] <select name="in_date" id="in_date" onchange="SummaryReportForm.submit();"></select></h3>
    </div>
	<fieldset>
    <legend class="title">[[.room_details.]]</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr class="row-odd">
	        <td width="70%">[[.today_ocuppied_rooms.]]</td><td>[[|today_ocuppied_rooms|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.today_available_rooms.]]</td><td>[[|today_available_rooms|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.today_check_ins.]]</td><td>[[|today_check_ins|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.today_check_outs.]]</td><td>[[|today_check_outs|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.today_bookeds.]]</td><td>[[|today_bookeds|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.today_no_shows.]]</td><td>[[|today_no_shows|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.today_cancellations.]]</td><td>[[|today_cancellations|]]</td>
      </tr>
    </table>
    </fieldset><br />
    <fieldset>
     <legend class="title">[[.housekeeping_details.]]</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr class="row-odd">
	        <td width="70%">[[.checked_out_rooms_marked_dirty.]]</td>
	        <td>[[|checked_out_rooms_marked_dirty|]]</td>
        </tr>
        <tr class="row-even">
	        <td>[[.repairing_rooms.]]</td>
	        <td>[[|repairing_rooms|]]</td>
        </tr>
        <tr class="row-odd">
	        <td>[[.occupied_rooms_marked_for_dirty.]]</td>
	        <td>[[|occupied_rooms_marked_for_dirty|]]</td>
        </tr>
    </table>
    </fieldset><br />
    <fieldset>
    <legend class="title">[[.revenue_list.]]</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr class="row-even">
        <td>[[.occupied_revenue.]]</td>
        <td>[[|occupied_revenue|]]</td>
      </tr>
      <tr class="row-odd">
	        <td width="70%">[[.booking_revenue.]]</td><td>[[|booking_revenue|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.no_show_revenue.]]</td><td>[[|no_show_revenue|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.cancellation_revenue.]]</td><td>[[|cancellation_revenue|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.minibar_revenue.]]</td>
	        <td>[[|minibar_revenue|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.laundry_revenue.]]</td>
	        <td>[[|laundry_revenue|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.compensation_revenue.]]</td>
	        <td>[[|compensation_revenue|]]</td>
      </tr>
      <tr class="row-odd">
        <td>[[.restaurant_revenue.]]</td>
        <td>[[|restaurant_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td>[[.karaoke_revenue.]]</td>
        <td>[[|karaoke_revenue|]]</td>
      </tr>
      <tr class="row-odd">
        <td>[[.massage_revenue.]]</td>
        <td>[[|massage_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td>[[.tennis_revenue.]]</td>
        <td>[[|tennis_revenue|]]</td>
      </tr>
      <tr class="row-odd">
        <td>[[.swimming_pool_revenue.]]</td>
        <td>[[|swimming_pool_revenue|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.extra_service_revenue.]]</td>
	        <td>[[|extra_service_revenue|]]</td>
      </tr>
      <tr class="row-odd">
        <td><strong>[[.total_revenue.]]</strong></td>
        <td><strong>[[|total_revenue|]]</strong></td>
      </tr>
    </table>
    </fieldset>
	<p>&nbsp;</p>
</div>
<input name="night_audit" type="hidden" id="night_audit" />
</form>
<script>
	$('in_date').value = '[[|date|]]';
</script>