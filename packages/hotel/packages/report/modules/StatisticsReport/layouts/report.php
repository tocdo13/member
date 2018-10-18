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
    <!--- HEADER --->
    <tr valign="top">
    <td align="left" width="100%">
    	<table border="0" cellSpacing=0 cellpadding="5" width="100%">
    			<tr valign="middle">
    			  <td align="left">
    			  	<strong style="text-transform: uppercase;"><?php echo HOTEL_NAME;?></strong><br />
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
        <font class="report_title">[[.statistics_report.]]</font><br />
        <label id="date_moth">[[.from_date.]] : [[|from_date|]] - [[.to_date.]] : [[|to_date|]]</label> 
        </td>
    </tr>
    <tr class="no_print">
      <td colspan="3" style="padding-left:210px; padding-right:190px;">
    <!--- END HEADER --->
    <!--- SEARCH --->   
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
        </td>
        <td>[[.hotel.]]: <select name="portal_id" id="portal_id" onChange="SearchForm.submit();"></select></td>
    	<td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"></td>
         </tr></table>
         </fieldset>
         </td>
        </tr>
        </table>
        <!--- END SEARCH ---> 
        <!-- REPORT -->
        <table style="width: 100%; margin: 10px auto;" border="1" cellSpacing="0" cellpadding="5" >
            <tr style="background: #eeeeee;">
                <th>[[.criterion.]]</th>
                <!--LIST:list_reservation_type-->
                <th>[[|list_reservation_type.name|]]</th>
                <!--/LIST:list_reservation_type-->
                <th>[[.sumary.]]</th>
            </tr>
            <tr>
                <th>Giá trung bình</th>
                <?php $total_avg = 0; ?>
                <!--LIST:list_reservation_type-->
                <?php if(isset([[=items=]][[[=list_reservation_type.id=]]])){ ?>
                    <th style="text-align: right;">
                        <?php if([[=items=]][[[=list_reservation_type.id=]]]['total_room']!=0){ ?>
                        <?php echo number_format([[=items=]][[[=list_reservation_type.id=]]]['total_amount']/([[=items=]][[[=list_reservation_type.id=]]]['total_room'])); $total_avg+=System::calculate_number(number_format([[=items=]][[[=list_reservation_type.id=]]]['total_amount']/([[=items=]][[[=list_reservation_type.id=]]]['total_room']))); ?>
                        <?php }else{ echo 0;} ?>
                    </th>
                <?php }else{ ?>
                    <th style="text-align: right;">0</th>
                <?php } ?>
                <!--/LIST:list_reservation_type-->
                <th style="text-align: right;"><?php if([[=total_rooms=]] !=0){echo number_format([[=totals=]]/[[=total_rooms=]]);} else {echo 0;} ?></th><!--</?php echo number_format($total_avg); ?>-->
            </tr>
            <tr>
                <th>Công suất phòng</th>
                <?php $total_capacity_of_room = 0; $total_room = 0; ?>
                <!--LIST:list_reservation_type-->
                <?php if(isset([[=items=]][[[=list_reservation_type.id=]]])){ ?>
                    <th style="text-align: right;">
                        <?php if(([[=items=]][[[=list_reservation_type.id=]]]['total_room'])!=0){ ?>
                        <?php echo round(([[=items=]][[[=list_reservation_type.id=]]]['total_room'])*100/[[=total_room=]],1); $total_capacity_of_room+=round(([[=items=]][[[=list_reservation_type.id=]]]['total_room']  )*100/[[=total_room=]],1); $total_room+=[[=items=]][[[=list_reservation_type.id=]]]['total_room']; ?>
                        <?php }else{ echo 0;} ?>
                        %
                    </th>
                <?php }else{ ?>
                    <th style="text-align: right;">0%</th>
                <?php } ?>
                <!--/LIST:list_reservation_type-->
                <th style="text-align: right;"><?php echo round(($total_room)*100/[[=total_room=]],1); //echo ($total_capacity_of_room); ?>%</th>
            </tr>
            <tr>
                <th>Tổng số phòng</th>
                <?php $total_room = 0; ?>
                <!--LIST:list_reservation_type-->
                <?php if(isset([[=items=]][[[=list_reservation_type.id=]]])){ ?>
                    <th style="text-align: right;">
                        <?php if([[=items=]][[[=list_reservation_type.id=]]]['total_room']!=0){ ?>
                        <?php echo [[=items=]][[[=list_reservation_type.id=]]]['total_room']; $total_room+=[[=items=]][[[=list_reservation_type.id=]]]['total_room']; ?>
                        <?php }else{ echo 0;} ?>
                    </th>
                <?php }else{ ?>
                    <th style="text-align: right;">0</th>
                <?php } ?>
                <!--/LIST:list_reservation_type-->
                <th style="text-align: right;"><?php echo ($total_room); ?></th>
            </tr>
            <tr>
                <th>Tổng tiền</th>
                <?php $total = 0; ?>
                <!--LIST:list_reservation_type-->
                <?php if(isset([[=items=]][[[=list_reservation_type.id=]]])){ ?>
                    <th style="text-align: right;">
                        <?php echo number_format([[=items=]][[[=list_reservation_type.id=]]]['total_amount']); $total+=[[=items=]][[[=list_reservation_type.id=]]]['total_amount']; ?>
                    </th>
                <?php }else{ ?>
                    <th style="text-align: right;">0</th>
                <?php } ?>
                <!--/LIST:list_reservation_type-->
                <th style="text-align: right;"><?php echo number_format($total); ?></th>
            </tr>
        </table>
        <!-- /REPORT -->
    </form>
    <div style="width: 90%; margin: 10px auto; text-align: left;">
        <h3>[[.note.]]:</h3>
        <ul style="padding-left: 15px;">
            <li style="font-weight: normal;">
                <b style="text-transform: uppercase;">Giá trung bình: </b>là giá bình quân của từng loại đặt phòng, được tính bằng tổng tiền doanh thu phòng của loại đó chia cho số phòng bán của loại đó
            </li>
            <li style="font-weight: normal;">
                <b style="text-transform: uppercase;">Công suất: </b> tổng số đêm phòng của hạng phòng đó bán được chia cho tổng số đêm phòng khách sạn có trừ phòng repair
            </li>
            <li style="font-weight: normal;">
                <b style="text-transform: uppercase;">Tổng số phòng: </b>thống kê ra tổng số phòng của loại đó bán được trong khoảng thời gian cần xem, có bao gồm phòng đã bán và đã đặt trước, tính theo từng ngày cộng lại
            </li>
            <li style="font-weight: normal;">
                <b style="text-transform: uppercase;">Tổng tiền: </b>Tổng số doanh thu của loại phòng đó bao gồm cả các dịch vụ phòng
            </li>
        </ul>
    </div>
    <script>
    jQuery("#from_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
    jQuery("#to_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
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
