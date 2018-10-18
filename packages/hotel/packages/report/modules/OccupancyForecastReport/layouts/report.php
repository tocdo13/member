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
			  <td align="right">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
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
  <td colspan="3" style="padding-left:50px; padding-right:50px;">
  
<fieldset>
  <legend>[[.time_select.]]</legend>
    <table style="margin-left:60px;">
    <tr> <td nowrap="nowrap">[[.by_day.]] &nbsp;&nbsp;</td>
    <td><input type="text" name="from_day" id="from_day" class="date-input" onChange="changevalue();"/>
    <script>
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('d/m/Y');}?>';
			  
	</script>
    </td>
    <td> &nbsp;&nbsp;[[.to_day.]] &nbsp;&nbsp;</td><td><input type="text" name="to_day" id="to_day" class="date-input" onChange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{  echo date('d/m/Y',(Date_Time::to_time(date('d/m/Y')) + 7*24*3600));}?>';
			  
	</script>
    <?php $tong_foc = 0;  ?>
    </td>
    <td>[[.hotel.]]: <select name="portal_id" id="portal_id" <!--onChange="SearchForm.submit();"-->></select></td>
    <td>[[.status.]]: <select name="status" id="status" onchange="SearchForm.submit();" ></select></td>
	<td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"></td>
    <td><button id="export">[[.export.]]</button></td>
     </tr></table>
     </fieldset>
     </td>
    </tr>
     <tr>
        <td style="padding-left:10px;">
         		 <div style="width:100%; padding-bottom:10px; font-size:11px;">        
            <table id="tblExport" style="width:100%; border:1px solid #666; margin-top:10px; font-size:11px;" id="report">
               <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px;">
               		<th style="width:50px;">[[.Date.]]</th>
                    <th style="text-align:center; width:50px;">[[.total_room.]] (1)</th>
                    <th style="text-align:center; width:50px;">[[.total_occ.]] (2)</th>
                    <th style="text-align:center; width:50px;">[[.arr_room.]] (3)</th>
                    <th style="text-align:center; width:50px;">[[.li.]] (4)</th>
                    <th style="text-align:center; width:50px;">[[.ei_lo.]] (5)</th>
                    <th style="text-align:center; width:70px;">[[.total_occ.]] + [[.arr_room.]] (6)</th>
                    <th style="text-align:center; width:70px;">[[.available_room1.]](7)</th>                     
                    <th style="text-align:center; width:50px;">%[[.oc.]] (8)</th>          
                    <th style="text-align:center; width:50px;">[[.ooo.]] (9)</th>
                    <th style="text-align:center; width:50px;">[[.foc.]] (10)</th>
                    <th style="text-align:center; width:50px;">[[.dpt.]] (11)</th>
					<?php if([[=check_user=]]!=1){ ?>
                    <th style="text-align:center; width:100px;">[[.room_rev.]] (12)</th>
                    <th style="text-align:center; width:100px;">[[.extra_rev.]] (13)</th>
                    <th style="text-align:center; width:100px;">[[.total_room_rev.]] (14)</th>
                    <th style="text-align:center; width:100px;">[[.avg_rm.]] (15)</th>
				    <?php }?>
               </tr>
               <!--LIST:items-->
               <tr style="height:30px; border:1px solid #ccc;">
                   <td style="text-align:center;<?php if(Date('D',Date_time::to_time([[=items.id=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=items.id=]]))=='Sat'){ echo 'color: blue';} ?>">[[|items.id|]]</td>
                   <td style="text-align:center;">[[|items.avail_room|]]</td>
                   <td style="text-align:center;">[[|items.occ_room|]]</td>
                   <td style="text-align:center;">[[|items.arr_room|]]</td>
                   <td style="text-align:center;">[[|items.li_room|]]</td>
                   <td style="text-align:center;">[[|items.ei_lo_room|]]</td>
                   <td style="text-align:center;">[[|items.room_soild|]]</td>
                   <td style="text-align:center;"><?php echo ([[=items.avail_room=]]-[[=items.room_soild=]]-[[=items.repair_room=]]); ?></td>
                   <td style="text-align:center;">[[|items.oc|]] %</td>
                   <td style="text-align:center;">[[|items.repair_room|]]</td>
                   <td style="text-align:center;">[[|items.foc|]]</td>
                   <td style="text-align:center;">[[|items.dep_room|]]</td>
                   <?php if([[=check_user=]]!=1){ ?>
                   <td style="text-align:right; padding-right:10px;"><?php echo System::display_number(round([[=items.room_revenue=]]));?></td>
                   <td style="text-align:right; padding-right:10px;"><?php echo System::display_number(round([[=items.other_revenue=]]));?></td>
                   <td style="text-align:right; padding-right:10px;"><?php echo System::display_number(round([[=items.total_revenue=]]));?></td>
                   <td style="text-align:right; padding-right:10px;">[[|items.avg_price|]]</td>
				   <?php }?>
               </tr>
               <!--/LIST:items-->
               <tr height="26px;">
                <td style="text-align:centert;"><strong>[[.total.]]</strong></td>
                <td style="text-align:center;"><strong>[[|total_avail_room|]]</strong></td>
                <td style="text-align:center "><strong>[[|total_occ_room|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_arr_room|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_li_room|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_ei_lo_room|]]</strong></td>
                <!---<td style="border:1px solid #ccc; text-align:center;  width:30px;"></td>--->
                <td style="text-align:center "><strong>[[|total_room_soild|]]</strong></td>
                <td style="text-align:center; "><strong><?php echo [[=total_avail_room=]]-[[=total_room_soild=]]-[[=total_repair_room=]]; ?></strong></td>
                <td style="text-align:center;">[[|total_oc|]] %</td>
                <td style="text-align:center; "><strong>[[|total_repair_room|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_foc|]]</strong></td>
                <td style="text-align:center; "><strong>[[|total_dep_room|]]</strong></td>
				 <?php if([[=check_user=]]!=1){ ?>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo System::display_number(round([[=total_room_revenue=]]));?></td>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo System::display_number(round([[=total_other_revenue=]]));?></td>
                <td style="text-align:right; padding-right:10px;"><strong><?php echo System::display_number(round([[=total_total_revenue=]]));?></td>
                <td style="text-align:right; padding-right:10px;"><strong>[[|total_avg_price|]]</strong></td>
				<?php }?>
	        <!---<td style="border:1px solid #ccc; text-align:center;"></td>--->
    	       <!-- <td style="border:1px solid #ccc; text-align:center; width:40px;"><strong></strong></td>-->
         </tr>
            </table>
        </div>
        </td>
     </tr> 
</table>
</form>
<p align="left" style="font-size:11px;"><b>[[.interpretation.]]:</b><br />
&nbsp;- (1) [[.total_room_in_hotel.]] <br />
&nbsp;- (2) [[.total_occupancy_in_hotel.]] <br />
&nbsp;- (3) [[.arrival_room.]] ([[.inlucde_dayuse_room.]])<br />
&nbsp;- (4) [[.room.]] LI<br />
&nbsp;- (5) [[.room.]] EI, LO<br />
&nbsp;- (6) = (2) + (3) + (4)<br />
&nbsp;- (7) = (1-6-9)<br />
&nbsp;- (8) = ((6)*100)/(1-9)<br />
&nbsp;- (9) [[.out_of_order_room.]] <br />
&nbsp;- (10) [[.FOC.]] (Phòng miễn phí) <br />
&nbsp;- (11) [[.depature_room.]] <br />
&nbsp;- (12) [[.room_revenue.]] (không tính phòng miễn phí) <br />
&nbsp;- (13) [[.extra_revenue.]] (Doanh thu của phụ trội tiền phòng - Li) <br />
&nbsp;- (14) [[.total_room_rev.]] = (12) + (13) <br />
&nbsp;- (15) [[.average_room_rate.]] =  (12)/(06)</p>
<script>
jQuery("#from_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#to_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);
function changevalue()
    {
        var myfromdate = $('from_day').value.split("/");
        var mytodate = $('to_day').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_day").val(jQuery("#from_day").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_day').value.split("/");
        var mytodate = $('to_day').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_day").val(jQuery("#to_day").val());
        }
    }
</script>
