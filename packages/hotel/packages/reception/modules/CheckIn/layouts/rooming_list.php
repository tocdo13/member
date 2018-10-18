<form name="RoomingListForm" method="post">
<div class="report-wrapper">
<div class="rooming-list-wrapper">
    <div class="header">
    	<div class="report-title">[[.rooming_list.]]</div>
        <div class="intruction"></div>
        <div class="group-info">
            <table width="100%" border="0" cellspacing="0" cellpadding="10">
                <tr>
                	<td width="50%">[[.group_name.]]: [[|group_name|]] | [[.booking_code.]]: [[|booking_code|]]</td>
                    <td>[[.phone.]]: [[|phone|]]</td>
                </tr>
                  <tr>
                	<td>[[.email_address.]]: [[|email|]]</td>
                    <td>[[.hotel.]]: [[|hotel_name|]]</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="body">
    <!--LIST:items-->
        <table width="100%" border="1" cellspacing="0" cellpadding="10" bordercolor="#000000" rules="rows">
            <tr bgcolor="#EFEFEF">
	            <th width="50%" align="left">[[.room.]]: [[|items.room_name|]] | [[.room_level.]]: [[|items.room_level_name|]] </th>
                <th align="right">[[.check_in.]]: [[|items.time_in|]] [[.check_out.]]:[[|items.time_out|]] </th>
            </tr>
            <tr>
	            <td colspan="2">
                	<!--LIST:items.travellers-->
                   	<div class="traveller"><strong>[[|items.travellers.i|]].</strong>[[|items.travellers.gender|]] [[|items.travellers.full_name|]]</div>
                    <div class="time">[[.time_in.]]: [[|items.travellers.time_in|]] [[.time_out.]]:[[|items.travellers.time_out|]]</div>
                    <div class="special-request">[[.special_requests.]]: [[|items.travellers.special_request|]]</div>
                    <br clear="all">
                    <!--/LIST:items.travellers-->
                </td>
            </tr>
        </table><br />
    <!--/LIST:items-->
    </div>
</div>
</div>
</form>