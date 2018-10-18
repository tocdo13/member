<?php 
System::set_page_title(HOTEL_NAME);
?>
<link rel="stylesheet" href="skins/default/report.css">
<div width="100%" style=" padding: 5px; ">
    <div width="100%" style=" border: 0px solid green ; height: 30px; padding: 3px; ">
        <table width="100%">
            <tr><td >
                <form name="SearchForm" method="post">
                    <table style="margin: 0 auto;" width="100%">
                        <tr>
                            <!--Start Luu Nguyen Giap add portal -->
                            
                            <td align="center">[[.hotel.]]
                            <select name="portal_id" id="portal_id"></select>
                            
                            <!--End Luu Nguyen Giap add portal -->
                            [[.date.]]
                            
                            <input name="date" type="text" id="date" style="width:80px; text-align: center;" class="by-year"/>
                            
                            <input type="submit" name="do_search" value="[[.report.]]"/>
                            <input name="export_repost" type="submit" id="export_repost" value="[[.export.]]" onclick="export_rp()" />
                            </td>
                              <td align="right" width="170px;">
                            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                            <br />
                            [[.user_print.]]:<?php echo ' '.User::id();?>
                            </td>
                        </tr>
                    </table>
                </form>
            </td></tr>
        </table>
    </div>
</div>

<div >
	<div>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" id="export">
	  <tr>
		<td>
			<table cellspacing=0 width="100%" class="report-background">
			  <!--<tr valign="top">
				<td align="left" width="20%" style="padding-left:5px;">[[.Prepaid_by.]]: <?php echo User::id();?></td>
				<td align="right" width="63%">&nbsp;</td>
				<td align="right" nowrap width="17%">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?></td>
			  </tr>-->
			  <tr  class="report-title">
				<td align="center" colspan="3" style="font-size:24px">[[.Report_of_management.]]</td>
              </tr>
              <tr>
              	<td>&nbsp;
                </td>
              </tr>
			</table>
			<table cellpadding="5" cellspacing="0" border="1" width="100%" style="font-size:14px;">
			  <tr style="background-color:#CCCCFF;">
				<th style="border-bottom:3px double black;" nowrap align="center">DESCRIPTION/DIỄN GIẢI</th>
				<th style="border-bottom:3px double black" width="100" align="right">DAY</th>
				<th style="border-bottom:3px double black" width="100" align="right">MTD</th>
				<th style="border-bottom:3px double black" width="100" align="right">YTD</th>
			  </tr>
			  <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>ROOM SUMMARY/TỔNG QUAN VỀ PHÒNG</strong></td>
				
			  </tr>
              <tr>
				<td align="left">1.Total Rooms in Hotel/Tổng số phòng khách sạn có</td>
				<td width="60" align="right">[[|trh_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|trh_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|trh_ytd|]]</td>
			  </tr>
			  <tr>
				<td  align="left">2.Out-of-Order Rooms/Phòng hỏng</td>
				<td width="60" align="right">[[|oor_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|oor_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|oor_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">3.Out-of-Inventory/Phòng chưa setup đồ</td>
				<td width="60" align="right">[[|osr_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|osr_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|osr_ytd|]]</td>
			  </tr>
			  <tr>
				<td  align="left">4.Total Rooms - OOO/Tổng số phòng có thể bán</td>
				<td width="60" align="right">[[|trooo_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|trooo_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|trooo_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">5.Expected Arrival Room/Phòng đến</td>
				<td width="60" align="right">[[|ra_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|ra_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|ra_ytd|]]</td>
			  </tr>
			  <tr>
				<td  align="left">6.Occupied Rooms/Phòng khách lưu</td>
				<td width="60" align="right">[[|ro_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|ro_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|ro_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left"><i>7.Day Used/Phòng ở theo giờ</i></td>
				<td width="60" align="right"><i>[[|du_d|]]</i></td>
				<td width="60" align="right" style="padding-right:6px;"><i>[[|du_mtd|]]</i></td>
				<td width="60" align="right" style="padding-right:6px;"><i>[[|du_ytd|]]</i></td>
			  </tr>
			  <tr>
				<td  align="left"><i>8.Complimentary Room/Phòng miễn phí</i></td>
				<td width="60" align="right"><i>[[|cr_d|]]</i></td>
				<td width="60" align="right" style="padding-right:6px;"><i>[[|cr_mtd|]]</i></td>
				<td width="60" align="right" style="padding-right:6px;"><i>[[|cr_ytd|]]</i></td>
			  </tr>
              <tr>
				<td  align="left"><i>9.House Used/Phòng nội bộ</i></td>
				<td width="60" align="right"><i>[[|hu_d|]]</i></td>
				<td width="60" align="right" style="padding-right:6px;"><i>[[|hu_mtd|]]</i></td>
				<td width="60" align="right" style="padding-right:6px;"><i>[[|hu_ytd|]]</i></td>
			  </tr>
              <tr>
				<td  align="left"><i>10.Sold Room=Occupied Rooms+Expected Arrival Room<br>Phòng bán=Phòng đến + Phòng khách lưu</i></td>
				<td width="60" align="right"><?php echo([[=ro_d=]]+[[=ra_d=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo([[=orhc_mtd=]]+[[=ra_mtd=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo([[=orhc_ytd=]]+[[=ra_ytd=]]);?></td>
			  </tr>
              <tr>
				<td  align="left">11.Expected Departure Room/Phòng Dự kiến đi</td>
				<td width="60" align="right">[[|rd_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|rd_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|rd_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">12.Extra Bed/Giường phụ</td>
				<td width="60" align="right">[[|eb_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|eb_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|eb_ytd|]]</td>
			  </tr>
              <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>STATISTIC/THỐNG KÊ</strong></td>
				
			  </tr>
              <!---<tr>
				<td  align="left">Guest arrival expected/Số lượng khách dự kiến đến</td>
				<td width="60" align="right">[[|gih_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|gih_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|gih_ytd|]]</td>
			  </tr>--->
              <tr>
				<td  align="left">13.Early Checked-in/Nhận phòng sớm</td>
				<td width="60" align="right"><?php if(isset([[=eci_d=]])){if([[=eci_d=]] < 1) echo '0'.[[=eci_d=]]; else echo [[=eci_d=]]; }?></td>
				<td width="60" align="right" style="padding-right:6px;">[[|eci_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|eci_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">14.Late Checked-in/Nhận phòng muộn</td>
				<td width="60" align="right"><?php if(isset([[=lci_d=]])){if([[=lci_d=]] < 1) echo '0'.[[=lci_d=]]; else echo [[=lci_d=]]; }?></td>
				<td width="60" align="right" style="padding-right:6px;">[[|lci_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|lci_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">15.Late Checked-out/Trả phòng muộn</td>
				<td width="60" align="right"><?php if(isset([[=lco_d=]])){if([[=lco_d=]] < 1) echo '0'.[[=lco_d=]]; else echo [[=lco_d=]]; }?></td>
				<td width="60" align="right" style="padding-right:6px;">[[|lco_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|lco_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">Noshow</td>
				<td width="60" align="right" style="padding-right:6px;">[[|noshow_td|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|noshow_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|noshow_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">16.Guest in House/Số lượng khách ở</td>
				<td width="60" align="right">[[|gih_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|gih_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|gih_ytd|]]</td>
			  </tr>
			  <tr>
				<td  align="left">17.% Occupied Room/Total Rooms in Hotel <br>Công suất phòng có khách/tổng số phòng khách sạn cóthể bán(6*100/4)</td>
				<td width="60" align="right">[[|pro_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|pro_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;">[[|pro_ytd|]]</td>
			  </tr>
			  <tr>
				<td  align="left">18.% Sold Room/Total Rooms in Hotel <br>Công suất phòng bán/tổng số phòng khach sạn cóthể bán(10+14)*100/4</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number(([[=ro_d=]]+[[=ra_d=]]+[[=lci_d=]])*100/[[=trooo_d=]])?>%</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number(([[=ro_mtd=]]+[[=ra_mtd=]]+[[=lci_mtd=]])*100/[[=trooo_mtd=]])?>%</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number(([[=ro_ytd=]]+[[=ra_ytd=]]+[[=lci_ytd=]])*100/[[=trooo_ytd=]])?>%</td>
			  </tr>
               <tr>
				<td  align="left">19.Average Room Rate/Giá phòng bình quân(21+22+23+24)/(10+14)</td>
				<td width="60" align="right" class="change_numTr"><?php if(([[=ro_d=]]+[[=ra_d=]]+[[=eci_d=]]+[[=lci_d=]]+[[=lco_d=]])!=0) echo System::display_number(([[=rr_d=]]+[[=exs_ei_d=]]+[[=exs_lo_d=]]+[[=exs_off_d=]])/([[=ro_d=]]+[[=ra_d=]]+[[=eci_d=]]+[[=lci_d=]]+[[=lco_d=]]));?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=arr_mtd=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=arr_ytd=]]);?></td>
			  </tr>
              <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>REVENUE/DOANH THU</strong></td>
				
			  </tr>
              <tr>
				<td align="left" style="background-color:#FFFFCC;"><strong>20.Room Revenue/Doanh thu lưu trú</strong></td>
				<td width="60" align="right" style="background-color:#FFFFCC;"><strong class="change_numTr">[[|rsm_d|]]</strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr">[[|rsm_mtd|]]</strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr">[[|rsm_ytd|]]</strong></td>
			  </tr>
			  <tr>
				<td  align="left">21.Sold Room Revenue/Doanh thu tiền phòng (chưa bao gồm doanh thu EI,LO)</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=rr_d=]]+[[=exs_off_d=]]); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=rr_mtd=]]+[[=exs_off_mtd=]]); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=rr_ytd=]]+[[=exs_off_ytd=]]); ?></td>
			  </tr>
              <tr>
				<td  align="left">22.Early Check-in Revenue/Doanh thu nhận phòng sớm </td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=exs_ei_d=]]); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_ei_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_ei_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">23.Late Check-out Revenue/Doanh thu trả phòng muộn </td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=exs_lo_d=]]); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_lo_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_lo_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">24.Extra-bed Revenue/Doanh thu giường phụ </td>
				<td width="60" align="right" class="change_numTr">[[|exs_eb_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_eb_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_eb_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">25.Minibar Revenue/Doanh thu Minibar </td>
				<td width="60" align="right" class="change_numTr">[[|hkrm_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|hkrm_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|hkrm_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">26.Laundry Revenue/Doanh thu Giặt ủi </td>
				<td width="60" align="right" class="change_numTr">[[|hkrl_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|hkrl_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|hkrl_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">27.Equipment Revenue/Thu tiền bền bù vật dụng</td>
				<td width="60" align="right" class="change_numTr">[[|hkre_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|hkre_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|hkre_ytd|]]</td>
			  </tr>
               <!---<tr>
				<td  align="left">Operator Revenue/Doanh thu Điện thoại </td>
				<td width="60" align="right">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>--->
              <tr>
				<td  align="left">28.Pick up - Drop off/Doanh thu xe đưa đón tiễn khách </td>
				<td width="60" align="right" class="change_numTr">[[|exs_tf_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_tf_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_tf_ytd|]]</td>
			  </tr>
             
              <tr>
				<td  align="left">29.Tour services Revenue/Doanh thu dịch vụ Tours </td>
				<td width="60" align="right" class="change_numTr">[[|exs_tour_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_tour_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|exs_tour_ytd|]]</td>
			  </tr>
              <tr>
				<td  align="left">30.Other services Revenue/Doanh thu dịch vụ khác </td>
				<td width="60" align="right" class="change_numTr">[[|extra_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|extra_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|extra_ytd|]]</td>
			  </tr>
              <tr>
				<td align="left" style="background-color:#FFFFCC;"><strong>32.POS Revenue/Doanh thu POS</strong></td>
				<td width="60" align="right" style="background-color:#FFFFCC;"><strong  class="change_numTr">[[|pos_d|]]</strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr">[[|pos_mtd|]]</strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr">[[|pos_ytd|]]</strong></td>
			  </tr>
              <?php if(BREAKFAST_SPLIT_PRICE ==1){ ?>
              <tr>
				<td  align="left">33.Breakfast Revenue/Doanh thu ăn sáng</td>
				<td width="60" align="right" class="change_numTr">[[|brf_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|brf_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|brf_ytd|]]</td>
			  </tr>
              <?php } ?>
			  <tr>
				<td  align="left">34.F&amp;B Revenue/Doanh thu nhà hàng</td>
				<td width="60" align="right" class="change_numTr">[[|fbr_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|fbr_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|fbr_ytd|]]</td>
			  </tr>
			  
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu spa</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=spa_d=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=spa_mtd=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=spa_ytd=]]);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu đặt tiệc</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=party_d=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=party_mtd=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=party_ytd=]]);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu karaoke</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=kara_d=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=kara_mtd=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=kara_ytd=]]);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu ticket</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number([[=ticket_d=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=ticket_mtd=]]);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number([[=ticket_ytd=]]);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">35.Shop/Doanh thu bán hàng shop</td>
				<td width="60" align="right" class="change_numTr">[[|shopr_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|shopr_mtd|]]</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr">[[|shopr_ytd|]]</td>
			  </tr>
			  <tr>
				<td  align="left" style="background-color:#99FFCC;"><strong>Hotel Revenue/Tổng cộng doanh thu</strong></td>
				<td width="60" align="right" style="background-color:#99FFCC;"><strong class="change_numTr">[[|hr_d|]]</strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#99FFCC;"><strong class="change_numTr">[[|hr_mtd|]]</strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#99FFCC;"><strong class="change_numTr">[[|hr_ytd|]]</strong></td>
			  </tr>
              <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>NEXT DAY EXPECTED/DỰ KIẾN NGÀY MAI</strong></td>
				
			  </tr>
			  <tr>
				<td  align="left">36.Expected Arrival Rooms/Phòng dự kiến đến</td>
				<td width="60" align="right" class="change_numTr">[[|at_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">37.Expected Departure Rooms/Phòng dự kiến trả</td>
				<td width="60" align="right" class="change_numTr">[[|dt_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              <tr>
				<td  align="left">38.Expected Occupied Rooms/Phòng dự kiến ở</td>
				<td width="60" align="right" class="change_numTr">[[|oot_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              <tr>
				<td  align="left">39.Expected Occupancy %/Công suất phòng dự kiến</td>
				<td width="60" align="right">[[|ot_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              <!---
			  <tr>
				<td  align="left">Occupancy Tomorrow </td>
				<td width="60" align="right">[[|ot_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">Occupancy Next 7 Days </td>
				<td width="60" align="right">[[|on7d_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">Occupancy Rest of Month </td>
				<td width="60" align="right">[[|orom_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">Occupancy Rest of Year </td>
				<td width="60" align="right">[[|oroy_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">YTD Days Open </td>
				<td width="60" align="right">[[|ytddo_d|]]</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              --->
		  </table></td>
	  </tr>
	</table>
    <p align="left"><b>Lưu ý: Doanh thu trong báo cáo này chỉ tính toán dữ liệu phát sinh đến ngày hiện tại, bao gồm:</b><br>
      - DAY: Doanh thu dự kiến trong ngày<br>- MTD (Month to Day): Doanh thu dự kiến từ đầu tháng đến ngày hiện tại<br>- YTD (Year to Day): Doanh thu dự kiến từ đầu năm đến ngày hiện tại<br><i>Đã bao gồm Thuế và Phí dịch vụ (nếu có)</i></p>
</div>
<br />
<br />
<br />
<br />
<div>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC">
	    <tr>
            <td align="center"><strong>MÔ TẢ CHI TIẾT</strong></td>
        </tr>
        <tr>
            <td align="left">
            <b>ROOM SUMMARY/TỔNG QUAN VỀ PHÒNG</b><br />
            5.Expected Arrival Room/Phòng đến: Những phòng trên sơ đồ phòng đang có trạng thái checkin hoặc booked và có ngày đến bằng ngày xem báo cáo(có cả dayused).trừ phòng ảo và houseuse<br />
            6.Occupied Rooms/Phong khách lưu: Những phòng trên sơ đồ phòng có trạng thái checkin, có ngày đến nhỏ hơn ngày xem báo cáo và ngày đi lớn hơn ngày xem báo cáo. Trừ phòng ảo và houseuse<br />
            7.Day Used/Phong ở theo giờ: Đếm những phòng có ngày đến bằng ngày đi và bằng ngày xem báo cáo, sẽ có cả trạng thái checkout vì những phòng này vẫn tính doanh thu vào ngày xem báo cáo<br />
            10. Tổng 5+6 <br/>
            <b>STATISTIC/THỐNG KÊ</b><br />
            17.% Occupied Room/Total Rooms in Hotel 
                Công suât phòng có khách/tổng số phòng khách sạn cóthể bán(6*100/4) : bằng Tổng số phòng lưu (6)*100 và chia cho tổng số phòng khách sạn có thể bán (4)<br/>
            18.% Sold Room/Total Rooms in Hotel 
                Công suất phòng bán/tổng số phòng khach sạn cóthể bán(10+14)/4*100 : bằng tổng của (phòng đến trong ngày+phòng khách lưu+lo+li+ei)*100 chia cho số phòng khách sạn có thể bán(4)<br />
            19.Average Room Rate/Giá phòng bình quân(21+22+23+24)/(10+14) : bằng tổng doanh thu của ( phòng + li) chia cho tổng số lượng phòng (phòng đến trong ngày+phòng đến+li)   
            </td>
        </tr>
        
      </tr>
    </table>  
</div>
<script>
    jQuery("#date").datepicker();
    function export_rp()
    {
        jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    }
</script>
