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
                            <strong><?php echo Portal::language('template_code');?></strong><br /> <?php echo Portal::language('print_by');?>: <?php echo Session::get('user_id');?> <br /> <?php echo Portal::language('print_time');?> : <?php echo date('H:i d/m/Y');?>
                        </td>
                    </tr>	
        		</table>
            </td>
        </tr>
        <tr>
            <td style="text-align:center; padding-top:30px;">
            <font class="report_title"><strong><?php echo Portal::language('revenue_summary_by_day_report');?></strong></font><br /><br />
            <label id="date_moth"><?php echo Portal::language('date');?>: <?php echo date('d/m/Y',Date_time::to_time($this->map['from_date'])); ?></label>
            &nbsp;<?php echo Portal::language('hotel');?>:&nbsp;<?php if(isset($this->map['hotel']) and is_array($this->map['hotel'])){ foreach($this->map['hotel'] as $key1=>&$item1){if($key1!='current'){$this->map['hotel']['current'] = &$item1;?><?php echo $this->map['hotel']['current']['hotel_name'];?>&nbsp;<?php }}unset($this->map['hotel']['current']);} ?>
            </td>
        </tr>
        <tr class="no_print">
            <td colspan="3" style="padding-left:50px; padding-right:50px;">
                <fieldset>
                    <legend><?php echo Portal::language('time_select');?></legend>
                    <table style="margin: auto;">
                        <tr> 
                            <td nowrap="nowrap"><?php echo Portal::language('date_view');?> &nbsp;&nbsp;</td>
                            <td><input type="text" name="from_day" id="from_day" class="date-input"/>
                                <script>
                            			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('d/m/Y');}?>';
                            			  
                            	</script>
                            </td>
                            <?php $tong_foc = 0;  ?>
                            <td><?php echo Portal::language('hotel');?>:
                            <td>
                                <div class="multiselect_hotel">
                                    <div style="width: 80px;" class="selectBox_hotel" onclick="showCheckboxes('hotel');">
                                      <select>
                                        <option></option>
                                      </select>
                                      <div class="overSelect_hotel"></div>
                                    </div> 
                                    <?php echo $this->map['list_hotel'];?>
                                    <input  name="hotel_ids" id="hotel_ids" / type ="hidden" value="<?php echo String::html_normalize(URL::get('hotel_ids'));?>">
                                    <input  name="hotel_id_" id="hotel_id_" / type ="hidden" value="<?php echo String::html_normalize(URL::get('hotel_id_'));?>">
                                </div> 
                            </div>
                            </td>
                    	    <td>&nbsp;<input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" id="btnsubmit"></td>
                            <td><button id="export"><?php echo Portal::language('export_excel');?></button></td>
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
                       		<th style="width:"><?php echo Portal::language('description_1');?></th>
                            <th style="text-align:center; width:10%;"><?php echo Portal::language('to_day1');?></th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;"><?php echo Portal::language('last_month');?></th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;"><?php echo Portal::language('last_year');?></th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;"><?php echo Portal::language('this_month_current');?></th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;"><?php echo Portal::language('budget_current');?></th>
                            <th style="text-align:center; width:3%;">%</th>
                            <th style="text-align:center; width:10%;"><?php echo Portal::language('difference');?></th>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('units_built');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['total_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['units_built'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['units_built']<0)?'('.abs($this->map['difference']['units_built']).')':$this->map['difference']['units_built'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('room_repair');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['repair_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['room_repair'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['room_repair']<0)?'('.abs($this->map['difference']['room_repair']).')':$this->map['difference']['room_repair'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('rooms_available_for_sale');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['room_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['rooms_available_for_sale'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['rooms_available_for_sale']<0)?'('.abs($this->map['difference']['rooms_available_for_sale']).')':$this->map['difference']['rooms_available_for_sale'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('rooms_sold');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['room_soild'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['rooms_sold'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['rooms_sold']<0)?'('.abs($this->map['difference']['rooms_sold']).')':$this->map['difference']['rooms_sold'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('complimentary_rooms');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['foc_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['complimentary_rooms'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['complimentary_rooms']<0)?'('.abs($this->map['difference']['complimentary_rooms']).')':$this->map['difference']['complimentary_rooms'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_rooms_occupied');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['total_room_occ'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['total_rooms_occupied'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['total_rooms_occupied']<0)?'('.abs($this->map['difference']['total_rooms_occupied']).')':$this->map['difference']['total_rooms_occupied'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('incidental_house_use_rooms');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['hu_room'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['house_use_rooms'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['house_use_rooms']<0)?'('.abs($this->map['difference']['house_use_rooms']).')':$this->map['difference']['house_use_rooms'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('occupancy_rate');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo $this->map['to_day']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo $this->map['last_month']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo $this->map['last_year']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo $this->map['month_current']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo $this->map['budget_current']['occupancy_rate'];?>%</td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php //echo ($this->map['difference']['occupancy_rate']<0)?'('.abs($this->map['difference']['occupancy_rate']).')':$this->map['difference']['occupancy_rate'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('average_room_rate');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['average_room_rate']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['average_room_rate']<0)?'('.System::display_number(abs($this->map['difference']['average_room_rate'])).')':System::display_number($this->map['difference']['average_room_rate']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('rev_par');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['rev_par']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['rev_par']<0)?'('.System::display_number(abs($this->map['difference']['rev_par'])).')':System::display_number($this->map['difference']['rev_par']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('no_of_guests');?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['adult'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['no_of_guests'];?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo ($this->map['difference']['no_of_guests']<0)?'('.abs($this->map['difference']['no_of_guests']).')':$this->map['difference']['no_of_guests'];?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('spend_per_guest');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['spend_per_guest']);?></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['spend_per_guest']<0)?'('.System::display_number(abs($this->map['difference']['spend_per_guest'])).')':System::display_number($this->map['difference']['spend_per_guest']);?></td>
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
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_rooms');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['room_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['room_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['room_revenue']<0)?'('.System::display_number(abs($this->map['difference']['room_revenue'])).')':System::display_number($this->map['difference']['room_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;"onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_food_beverage');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['bar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['bar_revenue_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['bar_revenue']<0)?'('.System::display_number(abs($this->map['difference']['bar_revenue'])).')':System::display_number($this->map['difference']['bar_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_telephone');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['total_telephone']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['telephone_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['total_telephone_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['telephone_revenue']<0)?'('.System::display_number(abs($this->map['difference']['telephone_revenue'])).')':System::display_number($this->map['difference']['telephone_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_laundry');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['laundry']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['laundry_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['laundry_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['laundry_revenue']<0)?'('.System::display_number(abs($this->map['difference']['laundry_revenue'])).')':System::display_number($this->map['difference']['laundry_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_minibar');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['minibar']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['minibar_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['minibar_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['minibar_revenue']<0)?'('.System::display_number(abs($this->map['difference']['minibar_revenue'])).')':System::display_number($this->map['difference']['minibar_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_transportation');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['transport']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['transport_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['transport_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['transport_revenue']<0)?'('.System::display_number(abs($this->map['difference']['transport_revenue'])).')':System::display_number($this->map['difference']['transport_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_spa');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['spa']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['spa_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['spa_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['spa_revenue']<0)?'('.System::display_number(abs($this->map['difference']['spa_revenue'])).')':System::display_number($this->map['difference']['spa_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_others_income');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['service_other']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['others_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['service_other_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['others_revenue']<0)?'('.System::display_number(abs($this->map['difference']['others_revenue'])).')':System::display_number($this->map['difference']['others_revenue']);?></td>
                       </tr>
                       <tr style="height:25px; border:1px solid #ccc;" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
                            <td style="text-align: left; padding-left: 5px;"><?php echo Portal::language('total_shop');?></td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['to_day']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['to_day']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_month']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_month']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['last_year']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['last_year']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['month_current']['shop']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['month_current']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo System::display_number($this->map['budget_current']['vending_revenue']);?></td>
                            <td style="text-align: right; padding-right: 3px;"><?php echo $this->map['budget_current']['shop_percent'];?>%</td>
                            <td style="text-align: right; padding-right: 3px;" class="change_num"><?php echo ($this->map['difference']['vending_revenue']<0)?'('.System::display_number(abs($this->map['difference']['vending_revenue'])).')':System::display_number($this->map['difference']['vending_revenue']);?></td>
                       </tr> 
                       <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px;">
                            <td style="text-align: left; padding-left: 5px;"><strong><?php echo Portal::language('gross_operating_revenue');?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong class="change_num"><?php echo System::display_number($this->map['to_day']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;" ><strong class="change_num"><?php echo System::display_number($this->map['last_month']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;" ><strong class="change_num"><?php echo System::display_number($this->map['last_year']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;" ><strong class="change_num"><?php echo System::display_number($this->map['month_current']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;" ><strong class="change_num"><?php echo System::display_number($this->map['budget_current']['hotel_revenue_total']);?></strong></td>
                            <td style="text-align: right; padding-right: 3px;"><strong>100%</strong></td>
                            <td style="text-align: right; padding-right: 3px;" ><strong class="change_num"><?php echo ($this->map['difference']['hotel_revenue_total']<0)?'('.System::display_number(abs($this->map['difference']['hotel_revenue_total'])).')':System::display_number($this->map['difference']['hotel_revenue_total']);?></strong></td>
                       </tr>
                    </table>
                    
                </div>
            </td>
        </tr>  
    </table>
    <table style="width: 100%; margin-top:5px;">
        <tr>
            <td>
                <div style=" border: 1px solid #ccc ;height: auto; " class="chart_month_current">
                    <div style=" height: 400px; " id="to_day_pie_chart" >
                    </div>
                    <?php if(HOTEL_CURRENCY=="VND"){ ?>
                    <strong>Unit 1.000.000 VND</strong>
                    <?php }else{?>
                    <strong>Unit USD</strong>
                    <?php }?>
                    <br />&nbsp
                </div>
            </td>
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
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
    var hotel = <?php echo $this->map['hotel_js'];?>;
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
    items_pie = <?php echo $this->map['month_current_pie_js'];?>;
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
                    text: "<?php echo Portal::language('month_current_pie_chart');?>"
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
                            +'<?php echo Portal::language('revenue');?> :<b>'+number_format(this.y)+'</b><br/>'
                            +'<?php echo Portal::language('total');?> :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '<?php echo Portal::language('month_current_pie_chart');?>',
				data: data_date
			}]
        });
    to_day_pie = <?php echo $this->map['to_day_pie_js'];?>;
    var tong2 = 0;
    var data_date_ytd = [];
    for(i in to_day_pie)
    {
        data_date_ytd[i] = [];
        data_date_ytd[i][0] = to_day_pie[i]['name'];
        data_date_ytd[i][1] = to_day_pie[i]['y'];
        tong2+=to_day_pie[i]['y'];
    }
    chart_indate = new Highcharts.Chart(
        {
            chart:{
                renderTo:'to_day_pie_chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                    text: "<?php echo Portal::language('to_day_pie_chart');?>"
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
                            +'<?php echo Portal::language('revenue');?> :<b>'+number_format(this.y)+'</b><br/>'
                            +'<?php echo Portal::language('total');?> :<b>'+number_format(tong2)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '<?php echo Portal::language('to_day_pie_chart');?>',
				data: data_date_ytd
			}]
        });
        jQuery("#export").click(function () {
                jQuery(".no_print").remove();
                jQuery('.change_num').each(function(){
                var num = jQuery(this).html();
                var firt = num.substring(-1,1);
                if(firt=='(')
                {
                    num = num.substring(1);
                    num = num.substring(0,num.length - 1);
                    num = '('+to_numeric(num)+')';
                    jQuery(this).html(num);
                }
                else
                {
                    jQuery(this).html(to_numeric(jQuery(this).html()));
                }
                })
                jQuery("#report").battatech_excelexport({
                    containerid: "report"
                   , datatype: 'table'
                   , fileName: '<?php echo Portal::language('report_revenue_group_of_type');?>'
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
