<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px;">
                    <td align="left" width="75%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                    </td>
              </tr>
                 <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.quick_daily_revenue_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.day.]]&nbsp;[[|date|]]
                            </span> 
                        </div>
                    </td>
                    
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>




<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td>[[.date.]]</td>
                                    <td><input name="date" type="text" id="date" /></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td><input type="submit" name="do_search" value="[[.report.]]"/></td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
    #no_of_breakfast{display:none}
    #lbl_no_of_breakfast{color: black !important;}
    #no_of_trans{display:none}
    #lbl_no_of_trans{color: black !important;}
    #no_of_pickup{display:none}
    #lbl_no_of_pickup{color: black !important;}
    #no_of_seeoff{display:none}
    #lbl_no_of_seeoff{color: black !important;}
}
.table-bound{margin: 0 auto !important;}
.desc{text-align: left !important;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date').datepicker();
});
</script>



<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="80%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="350px">[[.description.]]</th>
		<th class="report_table_header" width="150px">[[.quantity.]] (A)</th>
        <th class="report_table_header" width="100px">[[.revenue.]] (B)</th>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(1) [[.no_of_room.]]</th>
        <td align="center" class="report_table_column">
            <?php echo [[=summary=]]['no_of_room']; ?> ([[.late_checkin.]]: <?php echo [[=summary=]]['no_of_room_late_checkin']; ?>)
        </td>
        <td class="report_table_column"></td>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(2) [[.no_of_pax.]]</th>
        <td class="report_table_column"  align="center"><?php echo [[=summary=]]['no_of_pax']; ?></td>
        <td class="report_table_column" align="right"></td>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(3) [[.revenue.]] (include BF, exclude tax and service charge)</th>
        <td align="center" class="report_table_column"></td>
        <td class="report_table_column" align="right">
            <?php echo System::display_number([[=summary=]]['revenue']); ?>
            <br />
            ([[.late_checkin.]]: <?php echo System::display_number([[=summary=]]['revenue_late_checkin']); ?>)
        </td>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(4) [[.no_of_breakfast.]]</th>
        <td align="center" class="report_table_column">
            <input name="no_of_breakfast" type="text" id="no_of_breakfast" style="width: 50px; text-align: center;" class="input_number" onkeyup="jQuery('#lbl_no_of_breakfast').html(this.value);" />
            <label id="lbl_no_of_breakfast" style="color: white;" ></label>
        </td>
        <td class="report_table_column" align="right"></td>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(5) [[.breakfast_revenue.]]</th>
        <td class="report_table_column"></td>
        <td class="report_table_column" align="right" id="bf_revenue"><?php echo System::display_number([[=summary=]]['bf_revenue']); ?></td>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(6) [[.no_of_transportation.]]</th>
        <td align="center" class="report_table_column">
        <input name="no_of_trans" type="text" id="no_of_trans" style="width: 20px; text-align: center;" class="input_number" onkeyup="jQuery('#lbl_no_of_trans').html(this.value);" />
        <label id="lbl_no_of_trans" style="color: white;" ></label>
        (
        <input name="no_of_pickup" type="text" id="no_of_pickup" style="width: 20px; text-align: center;" class="input_number" onkeyup="jQuery('#lbl_no_of_pickup').html(this.value);" />
        <label id="lbl_no_of_pickup" style="color: white;" ></label>
         PICKUP
        and
        <input name="no_of_seeoff" type="text" id="no_of_seeoff" style="width: 20px; text-align: center;" class="input_number" onkeyup="jQuery('#lbl_no_of_seeoff').html(this.value);" />
        <label id="lbl_no_of_seeoff" style="color: white;" ></label>
         SEE OFF
        )
        <?php //echo [[=summary=]]['no_of_trans'].' ('.[[=summary=]]['no_of_pickup'].' PICKUP & '.[[=summary=]]['no_of_seeoff'].' SEE OFF)'; ?></td>
        <td class="report_table_column" align="right"></td>
    </tr>
    
    <tr>
		<th class="report_table_header" align="left">(7) [[.transportaion_revenue.]]</th>
        <td class="report_table_column"></td>
        <td class="report_table_column" align="right" id="trans_revenue"><?php echo System::display_number([[=summary=]]['trans_revenue']); ?></td>
    </tr>
    
    <tr>
		<th class="report_table_header" style="text-transform: uppercase;" align="left">(8) [[.room_revenue.]]</th>
        <td class="report_table_column"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['room_revenue']); ?></td>
    </tr>
    
    <tr>
		<th class="report_table_header" style="text-transform: uppercase;" align="left">(9) [[.occupancy.]]</th>
        <td align="center" class="report_table_column"><?php echo [[=summary=]]['occupancy']; ?>%</td>
        <td class="report_table_column" align="right"></td>
    </tr>
</table>
<table width="80%" cellpadding="10">
<tr>
	<td width="60%"></td>

	<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?></td>
</tr>
<tr>
	<td align="center" width="33%" >[[.creator.]]</td>
	<td align="center" width="33%" >[[.general_accountant.]]</td>
</tr>
</table>
<br />
<br />
<br />
<p style="margin-left:10px;" align="left"><b>Diễn giải:</b><br />
- (A1) Số phòng khách ở khách sạn từ 0h:01 đến 24h:00 (Check-in sớm, Check-out trễ đếm = 1/2 tiền phòng; Phòng dayused tính = 1/2 đêm)<br />
- (A2) Số khách ở tương ứng với số phòng (A1), bao gồm cả khách ở dayused<br />
- (B3) Doanh thu phòng tương ứng với số phòng ở (A1), không bao gồm 5% phí phục vụ và 10% VAT<br />
- (A4) Số khách còn ở lại khách sạn đến thời điểm báo cáo<br />
- (B5) = (A4) x Giá một suất ăn sáng<br />
- (A6) Số phòng check-in có đánh dấu vào ô free pickup/see off<br />
- (B7) = (A6) x giá một lần vận chuyển<br />
- (B8) = (B3) - (B5) - (B7)<br />
- (A9) = (A1)/Tổng số phòng khách sạn có thể bán
</p>
<script>full_screen();</script>
<div style="page-break-before:always;page-break-after:always;"></div>
<script type="text/javascript">
jQuery(document).ready(function(){  
    jQuery('#no_of_breakfast').keyup(function(){
        var test = jQuery('#no_of_breakfast').val();
        var bf_revenue = to_numeric(jQuery('#no_of_breakfast').val()?jQuery('#no_of_breakfast').val():0) * 63000;
        jQuery('#bf_revenue').html(number_format(bf_revenue));
    })
    jQuery('#no_of_trans').keyup(function(){
        var trans_revenue = to_numeric(jQuery('#no_of_trans').val()?jQuery('#no_of_trans').val():0) * 220000;
        jQuery('#trans_revenue').html(number_format(trans_revenue));
    })
    
    jQuery('#lbl_no_of_breakfast').html(jQuery('#no_of_breakfast').val());
    jQuery('#lbl_no_of_trans').html(jQuery('#no_of_trans').val());
    jQuery('#lbl_no_of_pickup').html(jQuery('#no_of_pickup').val());
    jQuery('#lbl_no_of_seeoff').html(jQuery('#no_of_seeoff').val());
    
});
</script>