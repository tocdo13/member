<link rel="stylesheet" href="skins/default/report.css"/>
<style>
.date_moth{
	display:none;
}
.report{
	border:1px solid #ccc;
	height:27px;
}
@media print{
.date_moth{
	display:block;
	padding-top:10px;
	font-size:14px;
}
.no_print{
	display:none;
}
</style>
<form name="SearchForm" method="post">
<table style="width:100%;">
<tr valign="top">
<td align="left" width="100%">
	<table border="0" cellSpacing=0 cellpadding="5" width="100%">
			<tr valign="middle">
			  <td align="left">
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?>
			  </td>
			  <td>
			  </td>
			</tr>	
		</table>
</td>
</tr>
<tr>
    <td style="text-align:center; padding-top:26px;">
    <font class="report_title">[[.occupancy_forecast_report.]]</font><br />
    <label id="date_moth">[[.from_date.]] : [[|from_date|]] - [[.to_date.]] : [[|to_date|]]</label> 
    </td>
</tr>
<tr class="no_print">
  <td colspan="3" style="padding-left:210px; padding-right:190px;">
  
<fieldset>
  <legend>[[.time_select.]]</legend>
    <table style="margin-left:60px;">
    <tr> <td nowrap="nowrap">[[.by_day.]] &nbsp;&nbsp;</td>
    <td><input type="text" name="from_day" id="from_day" class="date-input" onChange="changevalue();"/>
    <script>
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('d/m/Y');}?>';
			  function changevalue(){
				  var myfromdate = $('from_day').value.split("/");
				  var mytodate = $('to_day').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					  $('to_day').value =$('from_day').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
						  if(myfromdate[2] == mytodate[2]){
					           $('to_day').value =$('from_day').value;
						  }
					  }else{
						  if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
							  if(myfromdate[2] == mytodate[2]){
					  			$('to_day').value = $('from_day').value;
							  }
						  }
					  }
				  }
			  }
	</script>
    </td>
    <td> &nbsp;&nbsp;[[.to_day.]] &nbsp;&nbsp;</td><td><input type="text" name="to_day" id="to_day" class="date-input" onChange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{  echo date('d/m/Y',(Date_Time::to_time(date('d/m/Y')) + 7*24*3600));}?>';
			  function changefromday(){
				 var myfromdate = $('from_day').value.split("/");
				  var mytodate = $('to_day').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					 $('from_day').value= $('to_day').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
					   $('from_day').value = $('to_day').value;
					  }else{
                              if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
					  			$('from_day').value =$('to_day').value;
						  }
					  }
				  }
			  }
	</script>
    </td>
	<td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"></td>
     </tr></table>
     </fieldset>
     </td>
    </tr>
     <tr>
        <td style="padding-left:10px;">
         		 <div style="width:100%; padding-bottom:10px; font-size:11px;">        
            <table style="width:100%; border:1px solid #666; margin-top:10px;" id="report">
               <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px;">
               		<th style="width:50px;">[[.Date.]]</th>
                    <th style="text-align:center; width:50px;">[[.total_room.]] (1)</th>
                     <th style="text-align:center; width:50px;">[[.total_occ.]] (2)</th>
                     <th style="text-align:center; width:50px;">[[.arr_room.]] (3)</th>
                   <th style="text-align:center; width:70px;">[[.total_occ.]] + [[.arr_room.]] (4)</th>                    
                   <th style="text-align:center; width:50px;">%[[.oc.]] (5)</th>          
                    <th style="text-align:center; width:50px;">[[.ooo.]] (6)</th>
                    <!---<th style="border:1px solid #ccc; text-align:center; width:50px;">[[.pax.]]</th>--->
                   <th style="text-align:center; width:50px;">[[.dpt.]] (7)</th>

                   <th style="text-align:center; width:100px;"">[[.room_rev.]] (8)</th>
                   <th style="text-align:center; width:100px;"">[[.avg_rm.]] (9)</th>
                   <!---<th style="border:1px solid #ccc; text-align:center;">[[.rm_rev_par.]]</th>--->
                   <!--<th style="border:1px solid #ccc; text-align:center; padding-right:5px;">[[.cancel.]]</th>-->
               </tr>
               <!--LIST:items-->
               <tr style="height:30px; border:1px solid #ccc;">
                   <td style="text-align:center;">[[|items.id|]]</td>
                   <td style="text-align:center;">[[|items.total_avail_room|]]</td>
                   <td style="text-align:center;">[[|items.total_occ|]]</td>
                   <td style="text-align:center;">[[|items.arr_room|]]</td>
                   <td style="text-align:center;"><?php echo ([[=items.total_occ=]] + [[=items.arr_room=]]); ?></td>
                   <td style="text-align:center;">( [[|items.oc|]] %)</td>
                   <td style="text-align:center;">[[|items.ooo|]]</td>
                   <!---<td style="border:1px solid #ccc; text-align:center;  width:70px;">[[|items.pax|]]</td>--->
                   <td style="text-align:center;">[[|items.dpt|]]</td>
                   
                   <td style="text-align:right; padding-right:10px;">[[|items.room_rev|]]</td>
                   <td style="text-align:right; padding-right:10px;">[[|items.avg_rm|]]</td>
                   <!---<td style="border:1px solid #ccc; text-align:center;">[[|items.rm_rev_par|]]</td>--->
                  <!-- <td style="border:1px solid #ccc; text-align:center; width:40px;">[[|items.cancel|]]</td>-->
               </tr>
               <!--/LIST:items-->
               <tr height="26px;">
                <td style="text-align:centert;"><strong>[[.total.]]</strong></td>
                <td style="text-align:center;"><strong>[[|rooms_avrrial|]]</strong></td>
                <td style="text-align:center "><strong>[[|total_occ|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_arrival|]]</strong></td>
                <!---<td style="border:1px solid #ccc; text-align:center;  width:30px;"></td>--->
                <td style="text-align:center "><strong>[[|total_arrival_occ|]]</strong></td>
                <td style="text-align:center;">[[|percent_occ|]] %</td>
                <td style="text-align:center; "><strong>[[|rooms_repair|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_out|]]</strong></td>
                <td style="text-align:right; padding-right:10px;"><strong>[[|total_ammount_room|]]</td>
                <td style="text-align:right; padding-right:10px;"><strong>[[|avg_room|]]</strong></td>
	        <!---<td style="border:1px solid #ccc; text-align:center;"></td>--->
    	       <!-- <td style="border:1px solid #ccc; text-align:center; width:40px;"><strong></strong></td>-->
         </tr>
            </table>
        </div>
        </td>
     </tr> 
</table>
</form>
<p align="left"><b>Diễn giải:</b><br />
&nbsp;- (1) Tổng số phòng khách sạn có <br />
&nbsp;- (2) là những phòng  check-in trước đó và vẫn tiếp tục ở <br />
&nbsp;- (3) là những phòng đến và sẽ đến trong ngày (bao gồm cả những phòng dayuse)<br />
&nbsp;- (4) = (2) + (3)<br />
&nbsp;- (5) = (4)*100/((1) - (6)) <br />
&nbsp;- (6) số lượng phòng hỏng không sử dụng được <br />
&nbsp;- (7) số lượng phòng đi (check out) trong ngày <br />
&nbsp;- (8) doanh thu dự kiến của (4) <br />
&nbsp;- (9) = (8)/(4)</p>
<script>
jQuery("#from_day").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#to_day").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR ?>,1, 1) });
</script>