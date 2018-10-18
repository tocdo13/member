<link rel="stylesheet" href="skins/default/report.css"/>
<style>

.multiselect_hotel {
  width: 120px;
}

.selectBox_hotel {
  position: relative;
}

.selectBox_hotel select {
  width: 100%;
  font-weight: bold;
}

.overSelect_hotel {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

#checkboxes_hotel {
  display: none;
  border: 1px #1e90ff solid;
  overflow: auto;    
  padding: 2px 15px 2px 5px;
  position: absolute;
  background: white;  
}
#checkboxes_hotel{
    height: 100px;
}
#checkboxes_hotel label {
  display: block;
}

#checkboxes_hotel label:hover {
  background-color: #1e90ff;
}
@media print{
    .chart_month_current
    {
        border:none !important;
    }
}
</style>
<form name="SearchForm" method="post">
    <table style="width:100%;" id="report">
         <tr>
            <td >
        		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
        			<tr style="font-size:11px; font-weight:normal">
                        <td align="left" width="70%">
                            <img src="<?php echo HOTEL_LOGO;?>" style="width: 150px;height: auto;"/><br />
                            <?php echo HOTEL_NAME;?>
                            <br />
                            <?php echo HOTEL_ADDRESS;?>
                        </td>
                        <td align="right" style="padding-right:10px;" >
                            <strong>[[.template_code.]]</strong><br /> [[.print_by.]]: <?php echo Session::get('user_id');?> <br /> [[.print_time.]] : <?php echo date('H:i d/m/Y');?>
                        </td>
                    </tr>	
        		</table>
            </td>
        </tr>
        <tr>
            <td style="text-align:center; padding-top:30px;">
            <font class="report_title"><strong>[[.revenue_summary_report.]]</strong></font><br /><br />
            <label id="date_moth">[[.month.]]: <?php echo date('m/Y',Date_time::to_time([[=from_date=]])); ?></label>
            &nbsp;[[.hotel.]]:&nbsp;<!--LIST:hotel-->[[|hotel.hotel_name|]]&nbsp;<!--/LIST:hotel-->
            </td>
        </tr>
        <tr class="no_print">
            <td colspan="3" style="padding-left:50px; padding-right:50px;">
                <fieldset>
                    <legend>[[.time_select.]]</legend>
                    <table style="margin: auto;">
                        <tr> 
                            <td nowrap="nowrap">[[.date_view.]] &nbsp;&nbsp;</td>
                            <td><input type="text" name="from_day" id="from_day" class="date-input"/>
                                <script>
                            			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('d/m/Y');}?>';
                            			  
                            	</script>
                            </td>
                            <?php $tong_foc = 0;  ?>
                            <td>[[.hotel.]]:
                            <td>
                                <div class="multiselect_hotel">
                                    <div style="width: 80px;" class="selectBox_hotel" onclick="showCheckboxes('hotel');">
                                      <select>
                                        <option></option>
                                      </select>
                                      <div class="overSelect_hotel"></div>
                                    </div> 
                                    [[|list_hotel|]]
                                    <input name="hotel_ids" type="hidden" id="hotel_ids" />
                                    <input name="hotel_id_" type="hidden" id="hotel_id_" />
                                </div> 
                            </div>
                            </td>
                    	    <td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"></td>
                            <td><button id="export">[[.export_excel.]]</button></td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td style="padding-left:10px;">
                <div style="width:100%; padding-bottom:10px; font-size:11px;">        
                    <table id="tblExport" style="width:100%; border:1px solid #ccc; margin-top:10px; font-size:11px;border-collapse: collapse;" border="1">
                       <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px;">
                       		<th style="width:">[[.description_1.]]</th>
                            <th style="text-align:center; width:10%;">[[.this_month_current.]]</th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;">[[.budget_current.]]</th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;">[[.last_year_current.]]</th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;">[[.This_month_ytd.]]</th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;">[[.budget_YTD.]]</th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;">[[.last_year_YTD.]]</th>
                            <th style="text-align:center; width:3%;">%</th>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.units_built.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['units_built'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['units_built'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['total_room'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.room_repair.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['room_repair'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['room_repair'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['repair_room'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.rooms_available_for_sale.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['rooms_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['rooms_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['room_available_for_sale'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.rooms_sold.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['rooms_sold'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['rooms_sold'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['room_soild'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.complimentary_rooms.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['complimentary_rooms'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['complimentary_rooms'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['foc_room'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_rooms_occupied.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['total_rooms_occupied'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['total_rooms_occupied'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['total_room_occ'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.incidental_house_use_rooms.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['house_use_rooms'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['house_use_rooms'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['hu_room'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.occupancy_rate.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['occupancy_rate'];?>%</td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.average_room_rate.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['average_room_rate']);?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.rev_par.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['rev_par']);?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.no_of_guests.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['no_of_guests'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['no_of_guests'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['adult'];?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.spend_per_guest.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['spend_per_guest']);?></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_rooms.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['room_revenue_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;"onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_food_beverage.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['bar_revenue_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_telephone.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['telephone_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['telephone_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['total_telephone_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_laundry.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['laundry_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['laundry_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['laundry_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_minibar.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['minibar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['minibar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['minibar_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_transportation.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['transport_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['transport_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['transport_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_spa.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['spa_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['spa_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['spa_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_others_income.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['others_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['others_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['service_other_percent'];?>%</td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;">[[.total_shop.]]</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['month_current']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_current']['vending_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_current']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_current']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['this_month_ytd']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['this_month_ytd']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['budget_ytd']['vending_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_ytd']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo System::display_number($this->map['last_year_ytd']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year_ytd']['shop_percent'];?>%</td>
                       </tr> 
                       <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px;">
                            <td style="text-align: left; padding-left: 5px;"><strong>[[.gross_operating_revenue.]]</strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong><?php echo System::display_number($this->map['month_current']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong><?php echo System::display_number($this->map['budget_current']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong><?php echo System::display_number($this->map['last_year_current']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong><?php echo System::display_number($this->map['this_month_ytd']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong><?php echo System::display_number($this->map['budget_ytd']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong><?php echo System::display_number($this->map['last_year_ytd']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                       </tr>
                    </table>
                    
                </div>
            </td>
        </tr>  
    </table>
    <table style="width: 100%; margin-top:5px;">
        <tr>
            <td style="width: 50%;padding-left: 10px;">
                <div style=" border: 1px solid #ccc ;height: auto; " class="chart_month_current">
                    <div style=" height: 400px; " id="month_current_pie_chart" ></div>
                    <?php if(HOTEL_CURRENCY=="VND"){ ?>
                    <strong>Unit 1.000.000 VND</strong>
                    <?php }else{?>
                    <strong>Unit USD</strong>
                    <?php }?>
                    <br />&nbsp
                </div>
            </td>
            <td>
                <div style=" border: 1px solid #ccc ;height: auto; " class="chart_month_current">
                    <div style=" height: 400px; " id="month_ytd_pie_chart" >
                    </div>
                    <?php if(HOTEL_CURRENCY=="VND"){ ?>
                    <strong>Unit 1.000.000 VND</strong>
                    <?php }else{?>
                    <strong>Unit USD</strong>
                    <?php }?>
                    <br />&nbsp
                </div>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    var hotel = [[|hotel_js|]];
    jQuery(document).ready(function(){
    jQuery("#from_day").datepicker();  
    for(var i in hotel){                
        jQuery('.hotel').each(function(){                    
            if(jQuery('#'+this.id).attr('flag') == hotel[i])
            {
                jQuery('#'+this.id).attr('checked', true);                                                
            }
        })
    } 
    items_pie = [[|month_current_pie_js|]];
    var tong = 0;
    var data_date = [];
    for(i in items_pie)
    {
        data_date[i] = [];
        data_date[i][0] = items_pie[i]['name'];
        data_date[i][1] = items_pie[i]['y'];
        tong+=items_pie[i]['y'];
    }
    console.log(tong);
    //console.log(data_date);
    chart_indate = new Highcharts.Chart(
        {
            chart:{
                renderTo:'month_current_pie_chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                    text: "[[.month_current_pie_chart.]]"
            },
            colors: [
               '#AFC7C7', 
               '#ff0000', 
               '#0099ff', 
               '#99ff00', 
               '#307D7E', 
               '#ffff72',
               '#C68E17',
               '#F87217',
               '#4E387E',
               '#2B60DE',
               '#8D38C9',
               '#FF00FF',
               '#D2B9D3',
               '#ADDFFF',
               '#98AFC7'
            ],
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                            {
                                return this.point.name + '(' + roundNumber(this.percentage,2) + ' %) '+ number_format(this.y);
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    //tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,2) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.month_current_pie_chart.]]',
				data: data_date
			}]
        });
    items_ytd_pie = [[|month_ytd_pie_js|]];
    var tong2 = 0;
    var data_date_ytd = [];
    for(i in items_ytd_pie)
    {
        data_date_ytd[i] = [];
        data_date_ytd[i][0] = items_ytd_pie[i]['name'];
        data_date_ytd[i][1] = items_ytd_pie[i]['y'];
        tong2+=items_ytd_pie[i]['y'];
    }
    chart_indate = new Highcharts.Chart(
        {
            chart:{
                renderTo:'month_ytd_pie_chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                    text: "[[.month_ytd_pie_chart.]]"
            },
            colors: [
               '#AFC7C7', 
               '#ff0000', 
               '#0099ff', 
               '#99ff00', 
               '#307D7E', 
               '#ffff72',
               '#C68E17',
               '#F87217',
               '#4E387E',
               '#2B60DE',
               '#8D38C9',
               '#FF00FF',
               '#D2B9D3',
               '#ADDFFF',
               '#98AFC7'
            ],
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                            {
                                return this.point.name + '(' + roundNumber(this.percentage,2) + ' %) '+ number_format(this.y);
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    //tong2 = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,2) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong2)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.month_ytd_pie_chart.]]',
				data: data_date_ytd
			}]
        });
        jQuery("#export").click(function () {
                jQuery(".no_print").remove();
                jQuery("#report").battatech_excelexport({
                    containerid: "report"
                   , datatype: 'table'
                   , fileName: '[[.report_revenue_group_of_type.]]'
                });
                window.location.reload();
            });
   });
    var expanded_hotel = false;    
    function showCheckboxes(value) {
      if(value =='hotel'){
        var checkboxes_hotel = document.getElementById("checkboxes_hotel");
          if (!expanded_hotel) {
            checkboxes_hotel.style.display = "block";
            expanded_hotel = true;
          } else {
            checkboxes_hotel.style.display = "none";        
            expanded_hotel = false;
          }
      }           
    } 
    jQuery(document).on('click', function(e) {
    var $clicked = jQuery(e.target);
     if (!$clicked.parents().hasClass("multiselect_hotel")) 
     {
        jQuery('#checkboxes_hotel').hide();
     }
    });         
    function get_ids(value)
    {           
        var str_hotel = "";
        var hotel_id = "";
        var inputs = jQuery('.hotel:checkbox:checked');            
        for (var i=0;i<inputs.length;i++)
        {  
            if(inputs[i].id.indexOf('hotel_')==0)
            {
                str_hotel +=","+"'"+inputs[i].id.replace("hotel_","")+"'";
                hotel_id +=","+inputs[i].id.replace("hotel_","");                
            }
        }                
        str_hotel = str_hotel.replace(",","");
        hotel_id = hotel_id.replace(",","");             
        jQuery('#hotel_ids').val(str_hotel);
        jQuery('#hotel_id_').val(hotel_id);
    }
</script>
