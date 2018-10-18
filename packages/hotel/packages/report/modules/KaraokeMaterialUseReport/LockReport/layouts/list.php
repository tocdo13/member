<?php System::set_page_title(HOTEL_NAME);?>
<div class="room-type-supplier-bound">
<form name="ListLockReportForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC">
		<tr>
        	<td align="center"><h2>[[.lock_report.]]</h2></td>
        </tr>
    </table>
	<div class="content">
		<fieldset ondblclick="this.style.display='none';">
			<legend class="legend-title">[[.search.]]</legend><br />
			[[.room_name.]]: <input name="room_name" type="text" id="room_name" size="8">
			[[.status.]]: <select name="status" id="status"></select>
			[[.arrival_time.]]: <input name="arrival_time" type="text" id="arrival_time" class="date-input" size="8">
			[[.departure_time.]]: <input name="departure_time" type="text" id="departure_time" class="date-input" size="8">
			<input name="submit" type="submit" value="[[.GO.]]">
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
			<tr bgcolor="#F1F1F1">
			  <th width="1%" rowspan="2">[[.order_number.]]</th>
              <th rowspan="2" align="left">[[.room_name.]]</th>
              <th width="10%" rowspan="2" align="left">[[.status.]]</th>
			  <th colspan="2" align="center">[[.time_in.]]</th>
			  <th colspan="2" align="center">[[.time_out.]]</th>
		  </tr>
			<tr bgcolor="#F1F1F1">
			  <th width="15%" align="center">[[.software.]]</th>
			  <th width="15%" align="center">[[.lock.]]</th>
			  <th width="15%" align="center">[[.software.]]</th>
			  <th width="15%" align="center">[[.lock.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr bgcolor="[[|items.color|]]">
			  <td>[[|items.i|]]</td>
				<td>[[|items.room_name|]]</td>
				<td>[[|items.status|]]</td>
				<td align="center">[[|items.time_in|]]</td>
				<td align="center">[[|items.lock_time_in|]]</td>
			    <td align="center">[[|items.time_out|]]</td>
		      <td align="center">[[|items.lock_time_out|]]</td>
            </tr>
		  <!--/LIST:items-->			
		</table>
		<br />
		<div class="paging">[[|paging|]]</div>
	</div>
</form>	
</div>
<script>
	jQuery("#arrival_time").datepicker();
	jQuery("#departure_time").datepicker();	
</script>