<style>
#timehidden{
		display:none;	
	}
	@media print{
		#hidden{
			display:none;
		}
		#timehidden{
			display:block;	
		}
	}
	#room_name{
		width:100px;
		float:left;	
	}
	#report tr td{
		height:40px;	
	}
</style>
<table width="85%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%" style="font-size:11px;">
			<tr valign="top">
				<td align="left" width="75%"><strong><?php echo HOTEL_NAME;?></strong><br />[[.address.]]: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="25%">[[.print_by.]] : <?php echo Session::get('user_id');?><br/>[[.print_date.]] : <?php echo date('H:i d/m/Y',time());?></td>
			</tr>
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.room_status_report.]]</b></font>
		<br/>
		<form name="RoomStatusReportForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		 <table style="margin: auto;">
                            <tbody>
                                <tr>
                                    <td>[[.date.]] : </td>
                                    <td><input name="in_date" id="in_date" value="[[|in_date|]]" style="width: 100px;" /></td>
                                    <td><label>[[.hotel.]]:<select name="portal_id" id="portal_id" onchange="SearchForm.submit();"></select></label></td>
                                    <td><input type="submit" class="print" value="[[.view_report.]]" /></td>
                                </tr>
                            </tbody>
                        </table>
		  </fieldset>
		  </td></tr></table>
			</form>
	</td></tr></table>
</td>
<div>
<?php $i=0; $j=0; $k=0; $l=0; $m=0; $n=0; $p=0; $q=0;?>
<table width="80%" cellpadding="2" style="border:1px solid #DFDFDF; line-height:20px; margin:auto;" id="report">
	<tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.occupied_rooms.]] (<?php echo sizeof($this->map['occupied_rooms']);?>)</i></b></div>
        	<!--LIST:occupied_rooms-->
        	<div id="room_name"> [[|occupied_rooms.name|]]<?php $i++;?></div>
            <!--/LIST:occupied_rooms-->
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.booked_rooms.]] (<?php echo sizeof($this->map['booked_rooms']);?>)</i></b></div>
        	<!--LIST:booked_rooms-->
        	<div id="room_name">[[|booked_rooms.name|]]<?php $j++;?></div>
            <!--/LIST:booked_rooms-->
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.room_day_used.]] (<?php echo sizeof($this->map['room_day_used']);?>)</i></b></div>
        	<!--LIST:room_day_used-->
        	<div id="room_name">[[|room_day_used.name|]]<?php $q++;?></div>
            <!--/LIST:room_day_used-->
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.repair_rooms.]] (<?php echo sizeof($this->map['suspence_rooms']);?>)</i></b></div>
        	<!--LIST:suspence_rooms-->
        	<div id="room_name"> [[|suspence_rooms.name|]]<?php $k++;?></div>
            <!--/LIST:suspence_rooms-->
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.show_rooms.]] (<?php echo count($this->map['show_rooms']);?>)</i></b></div>
        	<!--LIST:show_rooms-->
        	<div id="room_name"> [[|show_rooms.name|]]</div>
            <!--/LIST:show_rooms-->
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.clean_rooms.]] (<?php echo count($this->map['clean_rooms']);?>)</i></b></div>
        	<!--LIST:clean_rooms-->
        	<div id="room_name"> [[|clean_rooms.name|]]</div>
            <!--/LIST:clean_rooms-->
        </td>
    </tr>
    <!-- oanh add phong su dung dich vu extrabed -->
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.Extrabed_rooms.]] (<?php echo sizeof($this->map['extrabed_rooms']);?>)</i></b></div>
        	<!--LIST:extrabed_rooms-->
        	<div id="room_name"> [[|extrabed_rooms.name|]]</div>
            <!--/LIST:extrabed_rooms-->
        </td>
    </tr>
    <!-- oanh add phong su dung dich vu extrabed -->
    
     <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.vacant_rooms.]] (<?php echo sizeof($this->map['vacant_rooms']);?>)</i></b></div>
        	<!--LIST:vacant_rooms-->
        	<div id="room_name"> [[|vacant_rooms.name|]]<?php $l++;?></div>
            <!--/LIST:vacant_rooms-->
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.vacan_dirty_rooms.]] (<?php echo sizeof($this->map['vacan_dirty_rooms']);?>)</i></b></div>
        	<!--LIST:vacan_dirty_rooms-->
        	<div id="room_name">[[|vacan_dirty_rooms.name|]]<?php $m++;?></div>
            <!--/LIST:vacan_dirty_rooms-->
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.departure_rooms.]] (<?php echo sizeof($this->map['departure_rooms']);?>)</i></b></div>
        	<!--LIST:departure_rooms-->
        	<div id="room_name">[[|departure_rooms.name|]]<?php $n++;?></div>
            <!--/LIST:departure_rooms-->
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i>[[.checkout_rooms.]] (<?php echo sizeof($this->map['checkout_rooms']);?>)</i></b></div>
        	<!--LIST:checkout_rooms-->
        	<div id="room_name">[[|checkout_rooms.name|]]<?php $p++;?></div>
            <!--/LIST:checkout_rooms-->
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;">
                <table width="100%">
                    <tr>
                        <td style="height: 10px;"><b><i>[[.booked_without_room.]] (<?php echo ($this->map['booked_without_room']);?>)</i></b></td>
                        
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</div>
</tr>
</table>
<table width="80%" cellpadding="2" style="border:1px solid #DFDFDF; line-height:20px; margin:auto;">
<tr>

    <td><?php echo $l;?>&nbsp;&nbsp;&nbsp;&nbsp;[[.vacant_rooms.]] </td>
    <td><?php echo $j;?>&nbsp;&nbsp;&nbsp;&nbsp;[[.booked_rooms.]] &nbsp;&nbsp;&nbsp;&nbsp; [[|booked_without_room|]]&nbsp;&nbsp;&nbsp;&nbsp;[[.booked_without_room.]] </td>
    <td><?php echo $q;?>&nbsp;&nbsp;&nbsp;&nbsp;[[.room_day_used.]] </td>
</tr>
<tr>

	<td><?php echo $k;?>&nbsp;&nbsp;&nbsp;&nbsp;[[.repair_rooms.]] </td>
        <td></td>
</tr>
<tr>

	<td><?php echo $m;?>&nbsp;&nbsp;&nbsp;&nbsp;[[.vacant_dirty_rooms.]]</td>
    
    <td></td>
</tr>
<tr>

	<td><?php echo $p;?>&nbsp;&nbsp;&nbsp;&nbsp;[[.checkout_rooms.]] </td>
	<td colspan="2"><?php echo $i+$j+[[=booked_without_room=]];?>&nbsp;&nbsp;&nbsp;&nbsp;[[.booked_rooms.]] + [[.occupied_rooms.]] + [[.booked_without_room.]]</td>

</tr>
</table>
<script>
		$('in_date').value = '[[|in_date|]]';
		jQuery("#in_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
</script>