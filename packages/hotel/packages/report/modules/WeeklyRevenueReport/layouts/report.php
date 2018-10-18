<style>
#date_moth{
	display:none;
}
#report_td{
	border:1px solid #ccc;
	height:27px;
}
@media print{
.no_print{
	display:none;
}
#date_moth{
	display:block;
	padding-top:10px;
	font-size:14px;
}
}
</style>
<table style="width:100%;">
<tr>
  <td style="width:110px;"></td>
   <td>
   		<table style="width:100%;">
        <tr>
        <td style="text-align:left;"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?> </td>
            <td></td>
            <td style="text-align:right; padding-right:90px;">
            [[.template_code.]]
            </td>
        </tr>
        </table>
   </td>
    <td></td>
</tr>
<tr>
    <td colspan="3" style="text-align:center; padding-top:26px;">
    <font class="report_title" style="font-size:20px; font-weight:bold;">[[.weekly_revenue_report.]]</font><br />
     <label id="date_moth">[[.from_date.]] : [[|from_date|]] - [[.to_date.]] : [[|to_date|]]</label>
    </td>
</tr>
<tr class="no_print">
  <td colspan="3" style="padding-left:200px; padding-right:180px; padding-top:10px;">
  <form name="SearchForm" method="post"><fieldset>
  <legend style="font-weight:normal;">[[.time_select.]]</legend>
    <table style="margin-left:60px;">
    <tr> <td nowrap="nowrap">[[.by_day.]] &nbsp;&nbsp;</td>
    <td><input type="text" name="from_day" id="from_day" class="date-input" onchange="changevalue();"/>
    <script>
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('d/m/Y');}?>';
			  function changevalue(){
				  var myfromdate = $('from_day').value.split("/");
				  var mytodate = $('to_day').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					  $('to_day').value =$('from_day').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
						  if(to_nummeric(myfromdate[2]) == to_nummeric(mytodate[2])){
					  			$('to_day').value =$('from_day').value;
						  }
					  }else{
						  if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
							  if(to_nummeric(myfromdate[2]) == to_nummeric(mytodate[2])){
					  			$('to_day').value =$('from_day').value;
							  }
						  }
					  }
				  }
			  }
	</script>    </td>
    <td> &nbsp;&nbsp;[[.to_day.]] &nbsp;&nbsp;</td><td>
    <input type="text" name="to_day" id="to_day" class="date-input" onchange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{ echo date('d/m/Y');}?>';
			  function changefromday(){
				 var myfromdate = $('from_day').value.split("/");
				  var mytodate = $('to_day').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					 $('from_day').value= $('to_day').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
						  if(to_nummeric(myfromdate[2]) == to_nummeric(mytodate[2])){
					           $('from_day').value = $('to_day').value;
						  }
					  }else{
                              if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
								  if(to_nummeric(myfromdate[2]) == to_nummeric(mytodate[2])){
					  			     $('from_day').value =$('to_day').value;
								  }
						  }
					  }
				  }
			  }
	</script>
    </td>
	<td>[[.real_revenue.]]<input name="checkout_revenue" type="checkbox" id="checkout_revenue" value="1" /></td>
	<td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"></td>
      </tr>
    </table>
  </fieldset>
    </form>
  </td>
</tr>
<tr>
    <td colspan="3" style="text-align:left; padding-left:120px;">
    <div style="width:780px;" id="rp">        
            <table style=" width:100%; border:1px solid #ccc; margin-top:30px; padding-top:10px; padding-left:20px;" id="report" >
              <tr  id="report_td">
                <td style="width:450px; padding-left:10px" id="report_td"> Công suất phòng / Room Occupay </td>
                <td style="text-align:right; padding-right:10px">
                <!--IF:cond0(isset([[=room_ocuupancy=]]) and [[=room_ocuupancy=]]!="0.00" and [[=room_ocuupancy=]]>0)-->
                <?php $total_room_ocuupancy = [[=room_ocuupancy=]]; $room_occupice =[[=room_occupice=]]; echo($room_occupice?$room_occupice:''); echo ($total_room_ocuupancy ?'('.System::display_number($total_room_ocuupancy ).'%)':'');?>
                <!--/IF:cond0-->
                </td>
              </tr>
              <tr  id="report_td">
                <td style="width:450px; padding-left:10px" id="report_td">[[.revenue_room.]]</td>
                <td style="text-align:right; padding-right:10px"> 
                <!--IF:cond1(isset([[=rooms=]]) and [[=rooms=]]!="0.00" and [[=rooms=]]>0)-->
                <?php $total_room = [[=rooms=]]; echo ($total_room ?System::display_number($total_room):'');?>
                <!--/IF:cond1-->
                </td>
                </tr>
               <tr id="report_td">
                    <td style="width:450px; padding-left:10px" id="report_td">[[.revenue_minibar.]]</td>
                    <td style="text-align:right; padding-right:10px"> 
                     <!--IF:cond2(isset([[=mibibar=]]) and [[=mibibar=]]!="0.00" and [[=mibibar=]]>0)-->
                    <?php $total_mibibar = [[=mibibar=]]; echo ($total_mibibar ?System::display_number($total_mibibar):'');?>
                    <!--/IF:cond2-->
                    </td>
               </tr>   
                <tr id="report_td">
                    <td style="width:450px; padding-left:10px" id="report_td">[[.revenuelaundy.]]</td>
                    <td style="text-align:right; padding-right:10px"> 
                    <!--IF:cond3(isset([[=laundry=]]) and [[=laundry=]]!="0.00" and [[=laundry=]]>0)-->
                    <?php $total_laundry = [[=laundry=]]; echo ($total_laundry ?System::display_number($total_laundry):'');?>
                    <!--/IF:cond3-->
                    </td>
                  </tr>
                  <tr id="report_td">  
                    <td style="width:450px; padding-left:10px" id="report_td">[[.revenue_bar.]]</td>
                    <td style="text-align:right; padding-right:10px"> 
                    <!--IF:cond4(isset([[=bar=]]) and [[=bar=]]!="0.00" and [[=bar=]]>0)-->
                    <?php $total_bar = [[=bar=]]; echo ($total_bar ?System::display_number($total_bar):'');?>
                    <!--/IF:cond4-->
                    </td>
                  </tr>
                  <tr id="report_td">  
                        <td style="width:450px; padding-left:10px" id="report_td">[[.revenue_spa.]]</td>
                        <td style="text-align:right; padding-right:10px">
                         <!--IF:cond5(isset([[=spa=]]) and [[=spa=]]!="0.00" and [[=spa=]]>0)--> 
                        <?php $total_spa = [[=spa=]]; echo ($total_spa ?System::display_number($total_spa):'');?>
                        <!--/IF:cond5-->
                        </td>
                       </tr>
                  <tr id="report_td">
                    <td style="width:450px; padding-left:10px" id="report_td">[[.extension_service.]]</td>
                    <td style="text-align:right; padding-right:10px"> 
                     <!--IF:cond6(isset([[=extra_service=]]) and [[=extra_service=]]!="0.00" and [[=extra_service=]]>0)--> 
                    <?php $total_extra_service = [[=extra_service=]]; echo ($total_extra_service ?System::display_number($total_extra_service):'');?>
                     <!--/IF:cond6-->
                    </td>
                   </tr> 
                   <tr id="report_td"> 
                    <td style="width:450px; padding-left:10px" id="report_td">[[.revenue_phone.]]</td>
                    <td style="text-align:right; padding-right:10px"> 
                    <!--IF:cond8(isset([[=phone=]]) and [[=phone=]]!="0.00" and [[=phone=]]>0)-->
                    <?php $total_phone = [[=phone=]]; echo ($total_phone ?System::display_number($total_phone):'');?>
                    <!--/IF:cond8-->
                    </td>
                   </tr> 
                   <tr id="report_td">
                     <td id="report_td" style="width:450px; padding-left:10px">
                     [[.orther_service.]] :<br/>
                     <!--LIST:orther_service-->
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - <i>[[|orther_service.name|]]:</i><br />
                      <!--/LIST:orther_service-->
                      </td>
                      <td style="text-align:right; padding-right:10px">
                      <br />
                        <!--LIST:orther_service-->
                            <?php $total_orther_service =[[=orther_service.service=]]; echo ($total_orther_service?System::display_number($total_orther_service):'');?><br />
                        <!--/LIST:orther_service-->
                      </td>
                   </tr>
                  
                   <tr id="report_td">
                    <td style="width:450px; padding-left:10px"><b><i>[[.total.]]</i></b></td>
                    <td style="text-align:right; padding-right:10px; font-weight:bold;"> 
                     <!--IF:cond9(isset([[=ammount=]]) and [[=ammount=]]!="0.00" and [[=ammount=]]>0)-->
                    <?php $total_ammount = [[=ammount=]]; echo ($total_ammount ?System::display_number($total_ammount):'');?>
                    <!--/IF:cond9-->
                    </td>
					</tr>
            </table>
        </div>
    </td>
</tr>

</table>
<script>
	jQuery("#from_day").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1, 1) });
	jQuery("#to_day").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR; ?>,1, 1) });
	<!--IF:cond(Url::check('checkout_revenue'))-->
	$('checkout_revenue').checked = true;
	<!--/IF:cond-->
</script>

