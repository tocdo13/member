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
                            
                            <td align="center"><?php echo Portal::language('hotel');?>
                            <select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select>
                            
                            <!--End Luu Nguyen Giap add portal -->
                            <?php echo Portal::language('date');?>
                            
                            <input  name="date" id="date" style="width:80px; text-align: center;" class="by-year"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>">
                            
                            <input type="submit" name="do_search" value="<?php echo Portal::language('report');?>"/>
                            <input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>" onclick="export_rp()" />
                            </td>
                              <td align="right" width="170px;">
                            <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                            <br />
                            <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                            </td>
                        </tr>
                    </table>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
				<td align="left" width="20%" style="padding-left:5px;"><?php echo Portal::language('Prepaid_by');?>: <?php echo User::id();?></td>
				<td align="right" width="63%">&nbsp;</td>
				<td align="right" nowrap width="17%"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?></td>
			  </tr>-->
			  <tr  class="report-title">
				<td align="center" colspan="3" style="font-size:24px"><?php echo Portal::language('Report_of_management');?></td>
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
				<td width="60" align="right"><?php echo $this->map['trh_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['trh_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['trh_ytd'];?></td>
			  </tr>
			  <tr>
				<td  align="left">2.Out-of-Order Rooms/Phòng hỏng</td>
				<td width="60" align="right"><?php echo $this->map['oor_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['oor_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['oor_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">3.Out-of-Inventory/Phòng chưa setup đồ</td>
				<td width="60" align="right"><?php echo $this->map['osr_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['osr_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['osr_ytd'];?></td>
			  </tr>
			  <tr>
				<td  align="left">4.Total Rooms - OOO/Tổng số phòng có thể bán</td>
				<td width="60" align="right"><?php echo $this->map['trooo_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['trooo_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['trooo_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">5.Expected Arrival Room/Phòng đến</td>
				<td width="60" align="right"><?php echo $this->map['ra_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['ra_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['ra_ytd'];?></td>
			  </tr>
			  <tr>
				<td  align="left">6.Occupied Rooms/Phòng khách lưu</td>
				<td width="60" align="right"><?php echo $this->map['ro_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['ro_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['ro_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left"><i>7.Day Used/Phòng ở theo giờ</i></td>
				<td width="60" align="right"><i><?php echo $this->map['du_d'];?></i></td>
				<td width="60" align="right" style="padding-right:6px;"><i><?php echo $this->map['du_mtd'];?></i></td>
				<td width="60" align="right" style="padding-right:6px;"><i><?php echo $this->map['du_ytd'];?></i></td>
			  </tr>
			  <tr>
				<td  align="left"><i>8.Complimentary Room/Phòng miễn phí</i></td>
				<td width="60" align="right"><i><?php echo $this->map['cr_d'];?></i></td>
				<td width="60" align="right" style="padding-right:6px;"><i><?php echo $this->map['cr_mtd'];?></i></td>
				<td width="60" align="right" style="padding-right:6px;"><i><?php echo $this->map['cr_ytd'];?></i></td>
			  </tr>
              <tr>
				<td  align="left"><i>9.House Used/Phòng nội bộ</i></td>
				<td width="60" align="right"><i><?php echo $this->map['hu_d'];?></i></td>
				<td width="60" align="right" style="padding-right:6px;"><i><?php echo $this->map['hu_mtd'];?></i></td>
				<td width="60" align="right" style="padding-right:6px;"><i><?php echo $this->map['hu_ytd'];?></i></td>
			  </tr>
              <tr>
				<td  align="left"><i>10.Sold Room=Occupied Rooms+Expected Arrival Room<br>Phòng bán=Phòng đến + Phòng khách lưu</i></td>
				<td width="60" align="right"><?php echo($this->map['ro_d']+$this->map['ra_d']);?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo($this->map['orhc_mtd']+$this->map['ra_mtd']);?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo($this->map['orhc_ytd']+$this->map['ra_ytd']);?></td>
			  </tr>
              <tr>
				<td  align="left">11.Expected Departure Room/Phòng Dự kiến đi</td>
				<td width="60" align="right"><?php echo $this->map['rd_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['rd_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['rd_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">12.Extra Bed/Giường phụ</td>
				<td width="60" align="right"><?php echo $this->map['eb_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['eb_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['eb_ytd'];?></td>
			  </tr>
              <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>STATISTIC/THỐNG KÊ</strong></td>
				
			  </tr>
              <!---<tr>
				<td  align="left">Guest arrival expected/Số lượng khách dự kiến đến</td>
				<td width="60" align="right"><?php echo $this->map['gih_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['gih_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['gih_ytd'];?></td>
			  </tr>--->
              <tr>
				<td  align="left">13.Early Checked-in/Nhận phòng sớm</td>
				<td width="60" align="right"><?php if(isset($this->map['eci_d'])){if($this->map['eci_d'] < 1) echo '0'.$this->map['eci_d']; else echo $this->map['eci_d']; }?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['eci_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['eci_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">14.Late Checked-in/Nhận phòng muộn</td>
				<td width="60" align="right"><?php if(isset($this->map['lci_d'])){if($this->map['lci_d'] < 1) echo '0'.$this->map['lci_d']; else echo $this->map['lci_d']; }?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['lci_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['lci_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">15.Late Checked-out/Trả phòng muộn</td>
				<td width="60" align="right"><?php if(isset($this->map['lco_d'])){if($this->map['lco_d'] < 1) echo '0'.$this->map['lco_d']; else echo $this->map['lco_d']; }?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['lco_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['lco_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">Noshow</td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['noshow_td'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['noshow_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['noshow_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">16.Guest in House/Số lượng khách ở</td>
				<td width="60" align="right"><?php echo $this->map['gih_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['gih_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['gih_ytd'];?></td>
			  </tr>
			  <tr>
				<td  align="left">17.% Occupied Room/Total Rooms in Hotel <br>Công suất phòng có khách/tổng số phòng khách sạn cóthể bán(6*100/4)</td>
				<td width="60" align="right"><?php echo $this->map['pro_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['pro_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;"><?php echo $this->map['pro_ytd'];?></td>
			  </tr>
			  <tr>
				<td  align="left">18.% Sold Room/Total Rooms in Hotel <br>Công suất phòng bán/tổng số phòng khach sạn cóthể bán(10+14)*100/4</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number(($this->map['ro_d']+$this->map['ra_d']+$this->map['lci_d'])*100/$this->map['trooo_d'])?>%</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number(($this->map['ro_mtd']+$this->map['ra_mtd']+$this->map['lci_mtd'])*100/$this->map['trooo_mtd'])?>%</td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number(($this->map['ro_ytd']+$this->map['ra_ytd']+$this->map['lci_ytd'])*100/$this->map['trooo_ytd'])?>%</td>
			  </tr>
               <tr>
				<td  align="left">19.Average Room Rate/Giá phòng bình quân(21+22+23+24)/(10+14)</td>
				<td width="60" align="right" class="change_numTr"><?php if(($this->map['ro_d']+$this->map['ra_d']+$this->map['eci_d']+$this->map['lci_d']+$this->map['lco_d'])!=0) echo System::display_number(($this->map['rr_d']+$this->map['exs_ei_d']+$this->map['exs_lo_d']+$this->map['exs_off_d'])/($this->map['ro_d']+$this->map['ra_d']+$this->map['eci_d']+$this->map['lci_d']+$this->map['lco_d']));?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['arr_mtd']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['arr_ytd']);?></td>
			  </tr>
              <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>REVENUE/DOANH THU</strong></td>
				
			  </tr>
              <tr>
				<td align="left" style="background-color:#FFFFCC;"><strong>20.Room Revenue/Doanh thu lưu trú</strong></td>
				<td width="60" align="right" style="background-color:#FFFFCC;"><strong class="change_numTr"><?php echo $this->map['rsm_d'];?></strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr"><?php echo $this->map['rsm_mtd'];?></strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr"><?php echo $this->map['rsm_ytd'];?></strong></td>
			  </tr>
			  <tr>
				<td  align="left">21.Sold Room Revenue/Doanh thu tiền phòng (chưa bao gồm doanh thu EI,LO)</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['rr_d']+$this->map['exs_off_d']); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['rr_mtd']+$this->map['exs_off_mtd']); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['rr_ytd']+$this->map['exs_off_ytd']); ?></td>
			  </tr>
              <tr>
				<td  align="left">22.Early Check-in Revenue/Doanh thu nhận phòng sớm </td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['exs_ei_d']); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_ei_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_ei_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">23.Late Check-out Revenue/Doanh thu trả phòng muộn </td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['exs_lo_d']); ?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_lo_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_lo_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">24.Extra-bed Revenue/Doanh thu giường phụ </td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['exs_eb_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_eb_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_eb_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">25.Minibar Revenue/Doanh thu Minibar </td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['hkrm_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['hkrm_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['hkrm_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">26.Laundry Revenue/Doanh thu Giặt ủi </td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['hkrl_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['hkrl_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['hkrl_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">27.Equipment Revenue/Thu tiền bền bù vật dụng</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['hkre_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['hkre_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['hkre_ytd'];?></td>
			  </tr>
               <!---<tr>
				<td  align="left">Operator Revenue/Doanh thu Điện thoại </td>
				<td width="60" align="right">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>--->
              <tr>
				<td  align="left">28.Pick up - Drop off/Doanh thu xe đưa đón tiễn khách </td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['exs_tf_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_tf_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_tf_ytd'];?></td>
			  </tr>
             
              <tr>
				<td  align="left">29.Tour services Revenue/Doanh thu dịch vụ Tours </td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['exs_tour_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_tour_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['exs_tour_ytd'];?></td>
			  </tr>
              <tr>
				<td  align="left">30.Other services Revenue/Doanh thu dịch vụ khác </td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['extra_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['extra_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['extra_ytd'];?></td>
			  </tr>
              <tr>
				<td align="left" style="background-color:#FFFFCC;"><strong>32.POS Revenue/Doanh thu POS</strong></td>
				<td width="60" align="right" style="background-color:#FFFFCC;"><strong  class="change_numTr"><?php echo $this->map['pos_d'];?></strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr"><?php echo $this->map['pos_mtd'];?></strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#FFFFCC;"><strong class="change_numTr"><?php echo $this->map['pos_ytd'];?></strong></td>
			  </tr>
              <?php if(BREAKFAST_SPLIT_PRICE ==1){ ?>
              <tr>
				<td  align="left">33.Breakfast Revenue/Doanh thu ăn sáng</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['brf_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['brf_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['brf_ytd'];?></td>
			  </tr>
              <?php } ?>
			  <tr>
				<td  align="left">34.F&amp;B Revenue/Doanh thu nhà hàng</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['fbr_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['fbr_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['fbr_ytd'];?></td>
			  </tr>
			  
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu spa</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['spa_d']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['spa_mtd']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['spa_ytd']);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu đặt tiệc</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['party_d']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['party_mtd']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['party_ytd']);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu karaoke</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['kara_d']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['kara_mtd']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['kara_ytd']);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">Revenue/Doanh thu ticket</td>
				<td width="60" align="right" class="change_numTr"><?php echo System::display_number($this->map['ticket_d']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['ticket_mtd']);?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo System::display_number($this->map['ticket_ytd']);?></td>
			  </tr>
              <tr>
				<td nowrap  align="left">35.Shop/Doanh thu bán hàng shop</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['shopr_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['shopr_mtd'];?></td>
				<td width="60" align="right" style="padding-right:6px;" class="change_numTr"><?php echo $this->map['shopr_ytd'];?></td>
			  </tr>
			  <tr>
				<td  align="left" style="background-color:#99FFCC;"><strong>Hotel Revenue/Tổng cộng doanh thu</strong></td>
				<td width="60" align="right" style="background-color:#99FFCC;"><strong class="change_numTr"><?php echo $this->map['hr_d'];?></strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#99FFCC;"><strong class="change_numTr"><?php echo $this->map['hr_mtd'];?></strong></td>
				<td width="60" align="right" style="padding-right:6px; background-color:#99FFCC;"><strong class="change_numTr"><?php echo $this->map['hr_ytd'];?></strong></td>
			  </tr>
              <tr>
				<td colspan="4" align="left" style="background-color:#FFCCFF;"><strong>NEXT DAY EXPECTED/DỰ KIẾN NGÀY MAI</strong></td>
				
			  </tr>
			  <tr>
				<td  align="left">36.Expected Arrival Rooms/Phòng dự kiến đến</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['at_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">37.Expected Departure Rooms/Phòng dự kiến trả</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['dt_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              <tr>
				<td  align="left">38.Expected Occupied Rooms/Phòng dự kiến ở</td>
				<td width="60" align="right" class="change_numTr"><?php echo $this->map['oot_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              <tr>
				<td  align="left">39.Expected Occupancy %/Công suất phòng dự kiến</td>
				<td width="60" align="right"><?php echo $this->map['ot_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
              <!---
			  <tr>
				<td  align="left">Occupancy Tomorrow </td>
				<td width="60" align="right"><?php echo $this->map['ot_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">Occupancy Next 7 Days </td>
				<td width="60" align="right"><?php echo $this->map['on7d_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">Occupancy Rest of Month </td>
				<td width="60" align="right"><?php echo $this->map['orom_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">Occupancy Rest of Year </td>
				<td width="60" align="right"><?php echo $this->map['oroy_d'];?></td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
				<td width="60" align="right" style="padding-right:6px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td  align="left">YTD Days Open </td>
				<td width="60" align="right"><?php echo $this->map['ytddo_d'];?></td>
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
