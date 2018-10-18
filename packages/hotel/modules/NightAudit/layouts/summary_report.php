<form name="SummaryReportForm" method="post">
<div>
    <div>
      <h3>[[.night_audit_is_completed_for.]] [[.date.]] <?php echo $_SESSION['night_audit_date']?></h3>
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
      <!--<tr class="row-odd">
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
              <!--<tr>
                <td>[[|items.guest_name|]]</td>
                <td>[[|items.room_level_name|]]</td>
                <td>[[|items.room_name|]]</td>
                <td>[[|items.arrival_time|]] - [[|items.departure_time|]]</td>
                <td>[[|items.tour_name|]]</td>
                <td>[[|items.company_name|]]</td>
				<td align="right">[[|items.change_price|]]</td>
              </tr>
              <!--/LIST:items-->
			  <!--IF:cond(![[=items=]])-->
                <!--<tr>
                    <td colspan="8" class="notice" align="center">[[.no_room.]]</td>
                </tr>
              <!--/IF:cond-->
            <!--</table></td>
        </tr>-->
       <!--/IF:cond_can_admin-->
	   <tr class="row-even">
	        <td>[[.extra_service_revenue.]]</td>
	        <td align="right">[[|extra_service_revenue|]]</td>
      </tr>
      <!--<tr class="row-odd">
	        <td width="588">[[.booking_revenue.]]</td>
	        <td width="120" align="right">[[|booking_revenue|]]</td>
      </tr>-->
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
      <tr class="row-even">
        <td>[[.telephone_revenue.]]</td>
        <td align="right">[[|massage_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td>[[.massage_revenue.]]</td>
        <td align="right">[[|telephone_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td>[[.ve_revenue.]]</td>
        <td align="right">[[|ve_revenue|]]</td>
      </tr>
      <tr class="row-even">
        <td>[[.ticket_revenue.]]</td>
        <td align="right">[[|ticket_revenue|]]</td>
      </tr>
	  <!--IF:cond(HAVE_TENNIS)-->
      <!--<tr class="row-odd">
        <td>[[.tennis_revenue.]]</td>
        <td align="right">[[|tennis_revenue|]]</td>
      </tr>
	  <!--/IF:cond-->
	  <!--IF:cond(HAVE_SWIMMING)-->
      <!--<tr class="row-even">
        <td>[[.swimming_pool_revenue.]]</td>
        <td align="right">[[|swimming_pool_revenue|]]</td>
      </tr>
	  <!--/IF:cond-->
      <tr class="row-odd">
        <td><strong>[[.total_revenue.]]</strong></td>
        <td align="right"><strong>[[|total_revenue|]]</strong></td>
      </tr>
    </table>
    </fieldset><br />
    <!--IF:cond(intval(date('H'))>=8 or (intval(date('H'))>=0 and intval(date('H'))<=6))-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><p>&nbsp;</p>
			<input type="button" value="[[.close_and_do_later.]]" onclick="if(!confirm('[[.are_you_sure.]]')){return false;}else{window.location = '<?php echo Url::build('room_map')?>';}" class="button">
			<input type="submit" value="[[.finish_night_audit.]]" onclick="if(!confirm('[[.are_you_sure.]]')){return false;}" class="button big">
			<span>[[.current_time.]]: <?php echo date('H:i\' d/m/Y');?></span>
			<p>&nbsp;</p>
		</td>
      </tr>
    </table>
    <!--ELSE-->
    <div class="notice" style="padding:10px;text-align:center;font-weight:bold;">[[.can_not_finish_night_audit_at_this_time.]] <br />([[.from.]] 11h PM [[.to.]] 6h AM)</div>
    <!--/IF:cond-->
</div>
<input name="night_audit" type="hidden" id="night_audit" />
</form>