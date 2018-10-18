<form name="SummaryReportForm" method="post">
<div>
    <div>
    	<h3>[[.summary_report.]] <?php echo Url::sget('date');?></h3>
    </div>
	<fieldset>
    <legend class="title">[[.room_details.]]</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr class="row-odd">
	        <td width="588">[[.today_ocuppied_rooms.]]</td>
	        <td width="300" align="right">[[|today_ocuppied_rooms|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.today_check_ins.]]</td><td align="right">[[|today_check_ins|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.today_check_outs.]]</td><td align="right">[[|today_check_outs|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.today_bookeds.]]</td><td align="right">[[|today_bookeds|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.today_no_shows.]]</td><td align="right">[[|today_no_shows|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.today_cancellations.]]</td><td align="right">[[|today_cancellations|]]</td>
      </tr>
    </table>
    </fieldset><br />
    <fieldset>
     <legend class="title">[[.housekeeping_details.]]</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr class="row-odd">
	        <td width="70%">[[.checked_out_rooms_marked_dirty.]]</td>
	        <td align="right">[[|checked_out_rooms_marked_dirty|]]</td>
        </tr>
        <tr class="row-even">
	        <td>[[.repairing_rooms.]]</td>
	        <td align="right">[[|repairing_rooms|]]</td>
        </tr>
        <tr class="row-odd">
	        <td>[[.occupied_rooms_marked_for_dirty.]]</td>
	        <td align="right">[[|occupied_rooms_marked_for_dirty|]]</td>
        </tr>
    </table>
    </fieldset><br />
     <fieldset>
    <legend class="title">[[.revenue.]]</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr class="row-even" <?php if(User::can_admin(false, ANY_CATEGORY)){?> title="Double click to show or hide Revenue detail" ondblclick="if(jQuery('#occupied_revenue_table').css('display')=='none'){jQuery('#occupied_revenue_table').show();}else{jQuery('#occupied_revenue_table').hide();} return false;" <?php }?>>
        <td>[[.occupied_revenue.]]</td>
        <td align="right"><strong>[[|total|]]</strong></td>
      </tr>
	  <!--IF:cond_can_admin(User::can_admin(false, ANY_CATEGORY))-->
      <tr class="row-odd">
        <td colspan="3">
        	<table id="occupied_revenue_table" width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" bgcolor="#FFFFCC">
              <tr class="table-header">
                <th width="190">[[.guest_name.]]</th>
                <th width="100">[[.room_level.]]</th>
                <th width="60">[[.room_name.]]</th>
                <th width="220">[[.arrival_time.]] - [[.departure_time.]]</th>
                <th>[[.group.]]/[[.tour.]]</th>
                <th>[[.company.]]</th>
				<th width="120">[[.room_rate.]] (<?php echo HOTEL_CURRENCY;?>)</th>
              </tr>
              <!--LIST:items-->
              <tr>
                <td>[[|items.guest_name|]]</td>
                <td>[[|items.room_level_name|]]</td>
                <td>[[|items.room_name|]]</td>
                <td>[[|items.arrival_time|]] - [[|items.departure_time|]]<!--IF:cond([[=items.verify_dayuse=]] and [[=items.brief_departure_time=]]==Url::sget('date'))--><strong>(V.D: <?php echo [[=items.verify_dayuse=]]/10;?>)</strong><!--/IF:cond--></td>
                <td>[[|items.tour_name|]]</td>
                <td>[[|items.company_name|]]</td>
				<td align="right">[[|items.change_price|]]</td>
              </tr>
              <!--/LIST:items-->
			  <!--IF:cond(![[=items=]])-->
                <tr>
                    <td colspan="8" class="notice" align="center">[[.no_room.]]</td>
                </tr>
              <!--/IF:cond-->
            </table></td>
        </tr>
       <!--/IF:cond_can_admin-->
      <tr class="row-odd">
	        <td width="588">[[.booking_revenue.]]</td>
	        <td width="120" align="right">[[|booking_revenue|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.no_show_revenue.]]</td>
	        <td align="right">[[|no_show_revenue|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.minibar_revenue.]]</td>
	        <td align="right">[[|minibar_revenue|]]</td>
      </tr>
      <tr class="row-even">
	        <td>[[.laundry_revenue.]]</td>
	        <td align="right">[[|laundry_revenue|]]</td>
      </tr>
      <tr class="row-odd">
	        <td>[[.compensation_revenue.]]</td>
	        <td align="right">[[|compensation_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td>[[.restaurant_revenue.]]</td>
        <td align="right">[[|restaurant_revenue|]]</td>
      </tr>
	  <!--IF:cond(HAVE_KARAOKE)-->
      <tr class="row-odd">
        <td>[[.karaoke_revenue.]]</td>
        <td align="right">[[|karaoke_revenue|]]</td>
      </tr>
	  <!--/IF:cond-->
	  <!--IF:cond(HAVE_MASSAGE)-->
      <tr class="row-even">
        <td>[[.massage_revenue.]]</td>
        <td align="right">[[|massage_revenue|]]</td>
      </tr>
	  <!--/IF:cond-->
	  <!--IF:cond(HAVE_TENNIS)-->
      <tr class="row-odd">
        <td>[[.tennis_revenue.]]</td>
        <td align="right">[[|tennis_revenue|]]</td>
      </tr>
	  <!--/IF:cond-->
	  <!--IF:cond(HAVE_SWIMMING)-->
      <tr class="row-even">
        <td>[[.swimming_pool_revenue.]]</td>
        <td align="right">[[|swimming_pool_revenue|]]</td>
      </tr>
	  <!--/IF:cond-->
      <tr class="row-odd">
	        <td>[[.extra_service_revenue.]]</td>
	        <td align="right">[[|extra_service_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td><strong>[[.total_revenue.]]</strong></td>
        <td align="right"><strong>[[|total_revenue|]]</strong></td>
      </tr>
    </table>
    </fieldset><br />
</div>
</form>