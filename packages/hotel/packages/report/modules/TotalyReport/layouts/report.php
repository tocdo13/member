<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="35%">
                        <strong>THANH TAN CORPORATION - THUA THIEN HUE</strong><br />12 NGUYEN VAN CU - TP HUE<BR>
                    </td>
    				<td> 
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" >[[.combination_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.from_date.]]&nbsp;[[|from_date|]] - [[.to_date.]]&nbsp;[[|to_date|]]
                            </span> 
                        </div>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        
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
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td>[[.from_date.]]</td>
                                    <td>
                                        <input name="from_date" type="text" id="from_date" style="width:100px;" class="by-year"/>
                                    </td>
                                    <td>[[.to_date.]]</td>
                                    <td>
                                        <input name="to_date" type="text" id="to_date" style="width:100px;" class="by-year"/>
                                    </td>
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
}
.table-bound{margin: 0 auto !important;}
.desc{text-align: left !important;}
.main_title{text-align:left}
.sub_title_1{text-align:left; text-indent: 20px;}
.sub_title_2{text-align:left; text-indent: 40px;}
.sub_title_3{text-align:left; text-indent: 60px;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#from_date').datepicker();
    jQuery('#to_date').datepicker();
});
</script>



<!---------REPORT----------->
<div>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="250px" rowspan="2"></th>
        <th class="report_table_header" width="100px" colspan="2">[[.alba_hotsprings.]]</th>
		<th class="report_table_header" width="100px" colspan="2">[[.hotels.]]</th>
        <!---<th class="report_table_header" width="100px" rowspan="2">[[.alba_water.]]</th>--->
        <th class="report_table_header" width="100px" rowspan="2">[[.total.]]</th>
        <th class="report_table_header" width="100px" colspan="2">[[.compared_to_previous_ss.]]</th>
        <th class="report_table_header" width="100px" colspan="2">[[.compared_to_previous_year.]]</th>
        <th class="report_table_header" width="100px" colspan="2">[[.current_budget.]]</th>
    </tr>
    <tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="75px" >[[.welness.]]</th>
        <th class="report_table_header" width="75px" >[[.fitness.]]</th>
        <th class="report_table_header" width="75px" >[[.alba_queen.]]</th>
        <th class="report_table_header" width="75px" >[[.alba.]]</th>
        <th class="report_table_header" width="75px" >[[.previous_ss.]]</th>
        <th class="report_table_header" width="30px" >[[.%.]]</th>
        <th class="report_table_header" width="75px" >[[.previous_year.]]</th>
        <th class="report_table_header" width="30px" >[[.%.]]</th>
        <th class="report_table_header" width="75px" >[[.budget.]]</th>
        <th class="report_table_header" width="30px" >[[.%.]]</th>
    </tr>
    <tr>
		<td align="left" width="250px"><strong><u>[[.total_traveller.]]</u></strong></td>
		<td align="right"><strong><u>[[|total_traveller_welness|]]</u></strong></td>
		<td align="right"><strong><u>[[|total_traveller_fitness|]]</u></strong></td>
        <td align="right"><strong><u>[[|total_traveller_queen1|]]</u></strong></td>
        <td align="right"><strong><u>[[|total_traveller_queen2|]]</u></strong></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><strong><u>[[|total_traveller|]]</u></strong></td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.euro.]]</td>
		<td align="right">[[|total_traveller_welness_euro|]]</td>
		<td align="right">[[|total_traveller_fitness_euro|]]</td>
        <td align="right">[[|total_traveller_queen1_euro|]]</td>
        <td align="right">[[|total_traveller_queen2_euro|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">[[|total_traveller_euro|]]</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.asia.]]</td>
		<td align="right">[[|total_traveller_welness_asia|]]</td>
		<td align="right">[[|total_traveller_fitness_asia|]]</td>
        <td align="right">[[|total_traveller_queen1_asia|]]</td>
        <td align="right">[[|total_traveller_queen2_asia|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">[[|total_traveller_asia|]]</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.other_foreigners.]]</td>
		<td align="right">[[|total_traveller_welness_other|]]</td>
		<td align="right">[[|total_traveller_fitness_other|]]</td>
        <td align="right">[[|total_traveller_queen1_other|]]</td>
        <td align="right">[[|total_traveller_queen2_other|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">[[|total_traveller_other|]]</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.hue.]]</td>
		<td align="right">[[|total_traveller_welness_hue|]]</td>
		<td align="right">[[|total_traveller_fitness_hue|]]</td>
        <td align="right">[[|total_traveller_queen1_hue|]]</td>
        <td align="right">[[|total_traveller_queen2_hue|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">[[|total_traveller_hue|]]</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.hanoi_hcm.]]</td>
		<td align="right">[[|total_traveller_welness_hnhcm|]]</td>
		<td align="right">[[|total_traveller_fitness_hnhcm|]]</td>
        <td align="right">[[|total_traveller_queen1_hnhcm|]]</td>
        <td align="right">[[|total_traveller_queen2_hnhcm|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">[[|total_traveller_hnhcm|]]</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.other_local.]]</td>
		<td align="right">[[|total_traveller_welness_khac|]]</td>
		<td align="right">[[|total_traveller_fitness_khac|]]</td>
        <td align="right">[[|total_traveller_queen1_khac|]]</td>
        <td align="right">[[|total_traveller_queen2_khac|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">[[|total_traveller_khac|]]</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    
    <tr>
		<td align="left" width="250px"><strong><u>[[.occupancy_rate.]]</u></strong></td>
		<td align="right"><strong><u><?php echo System::display_number([[=percent_welness=]], 2, '.', '')?>%</u></strong></td>
		<td align="right"><strong><u><?php echo System::display_number([[=percent_fitness=]], 2, '.', '')?>%</u></strong></td>
        <td align="center"><strong><u><?php echo System::display_number([[=percent_queen1=]], 2, '.', '')?>%</u></strong></td>
        <td align="right"><strong><u><?php echo System::display_number([[=percent_queen2=]], 2, '.', '')?>%</u></strong></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><strong></strong></td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.total_room.]]</td>
		<td align="right">[[|total_room_welness|]]</td>
		<td align="right">[[|total_room_fitness|]]</td>
        <td align="right">[[|total_room_queen1|]]</td>
        <td align="right">[[|total_room_queen2|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"></td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.rooms_available.]]</td>
		<td align="right">[[|total_room_available_welness|]]</td>
		<td align="right">[[|total_room_available_fitness|]]</td>
        <td align="right">[[|total_room_available_queen1|]]</td>
        <td align="right">[[|total_room_available_queen2|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"></td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.rooms_occupied.]]</td>
		<td align="right">[[|total_room_traveller_welness|]]</td>
		<td align="right">[[|total_room_traveller_fitness|]]</td>
        <td align="right">[[|total_room_traveller_queen1|]]</td>
        <td align="right">[[|total_room_traveller_queen2|]]</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"></td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <!---<tr>
		<td align="left" width="250px"><strong><u>[[.alba_water.]]-[[.quantity_volume.]] ([[.box.]])</u></strong></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_still_water.]] ([[.pet.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_still_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_sparkling_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_sparkling_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_glaver.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_19L.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.hibicus_tea.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>---> 
    <tr>
		<td align="left" width="250px"><strong><u>[[.average_unit_price.]]</u></strong></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.entrance_ticket.]]</td>
		<td align="right"><?php echo System::display_number([[=everage_entrance_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=everage_entrance_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=everage_entrance_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=everage_entrance_queen2=]])?></td>
       <!---<td align="right">&nbsp;</td>--->
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.everage_room.]]</td>
		<td align="right"><?php echo System::display_number([[=everage_room_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=everage_room_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=everage_room_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=everage_room_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <!---<tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_still_water.]] ([[.pet.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_still_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_sparkling_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_sparkling_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_glaver.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_19L.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.hibicus_tea.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>---> 
    <tr>
		<td align="left" width="250px"><strong><u>[[.net_revenue.]] ([[.excluded_VAT.]])</u></strong></td>
		<td align="right"><strong><u><?php echo System::display_number([[=total_welness=]])?></u></strong></td>
		<td align="right"><strong><u><?php echo System::display_number([[=total_fitness=]])?></u></strong></td>
        <td align="right"><strong><u><?php echo System::display_number([[=total_queen1=]])?></u></strong></td>
        <td align="right"><strong><u><?php echo System::display_number([[=total_queen2=]])?></u></strong></td>
       <!---<td align="right">&nbsp;</td>--->
        <td align="right"><strong><u><?php echo System::display_number([[=total_actuallly=]])?></u></strong></td>
        <td align="right"><strong><u><?php echo System::display_number([[=total_pre_actuallly=]])?></u></strong></td>
        <td align="right"><strong><u><?php echo System::display_number([[=total_pre_actuallly_percent=]])?></u></td>
        <td align="center"><strong><u>0</u></strong></td>
        <td align="center"><strong><u>100</u></strong></td>
        <td align="center"><strong><u><?php echo System::display_number([[=plan_total_actuallly=]])?></u></strong></td>
        <td align="center"><strong><u><?php echo System::display_number([[=plan_total_actuallly_percent=]])?></u></strong></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.entrance_ticket.]]</td>
		<td align="right"><?php echo System::display_number([[=total_entrance_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_entrance_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_entrance_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_entrance_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_entrance=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_entrance_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=entrance_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_entrance=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_entrance_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.room_revenue.]]</td>
		<td align="right"><?php echo System::display_number([[=total_room_price_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_room_price_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_room_price_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_room_price_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_room=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_pre_room=]])?></td>
        <td align="right"><?php echo System::display_number([[=pre_room_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_room=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_room_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.room_service_revenue.]]</td>
		<td align="right"><?php echo System::display_number([[=total_service_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_service_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_service_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_service_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_service=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_service_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=service_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_service=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_service_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.FB_Revenue.]]</td>
		<td align="right"><?php echo System::display_number([[=total_bar_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_bar_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_bar_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_bar_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_bar=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_bar_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=bar_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_bar=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_bar_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.highwire.]]</td>
		<td align="right"><?php echo System::display_number([[=total_highwire_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_highwire_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_highwire_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_highwire_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_highwire=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_highwire_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=highwire_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_highwire=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_highwire_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.zipline.]]</td>
		<td align="right"><?php echo System::display_number([[=total_zipline_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_zipline_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_zipline_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_zipline_queen2=]])?></td>
       <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_zipline=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_zipline_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=zipline_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_zipline=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_zipline_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.craft_village.]]</td>
		<td align="right"><?php echo System::display_number([[=total_village_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_village_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_village_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_village_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_village=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_village_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=village_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_village=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_village_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.souvenir.]]</td>
		<td align="right"><?php echo System::display_number([[=total_souvenir_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_souvenir_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_souvenir_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_souvenir_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_souvenir=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_souvenir_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=souvenir_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_souvenir=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_souvenir_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.spa_revenue.]]</td>
		<td align="right"><?php echo System::display_number([[=total_spa_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_spa_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_spa_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_spa_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_spa=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_spa_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=spa_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_spa=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_spa_percent=]])?></td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.meeting_room_service.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.other_service.]]</td>
		<td align="right"><?php echo System::display_number([[=total_other_service_welness=]])?></td>
		<td align="right"><?php echo System::display_number([[=total_other_service_fitness=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_other_service_queen1=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_other_service_queen2=]])?></td>
        <!---<td align="right">&nbsp;</td>--->
        <td align="right"><?php echo System::display_number([[=total_other_service=]])?></td>
        <td align="right"><?php echo System::display_number([[=total_other_service_pre=]])?></td>
        <td align="right"><?php echo System::display_number([[=other_service_pre_percent=]])?></td>
        <td align="center">0</td>
        <td align="center">100</td>
        <td align="center"><?php echo System::display_number([[=plan_total_other_service=]])?></td>
        <td align="center"><?php echo System::display_number([[=plan_total_other_service_percent=]])?></td>
    </tr>
    <!---<tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_still_water.]] ([[.pet.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_still_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.alba_sparkling_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_sparkling_water.]] ([[.glass.]])</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_glaver.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.thanhtan_19L.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="250px">&nbsp;&nbsp;[[.hibicus_tea.]]</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
    </tr>---> 
</table>
<br />
<table width="100%" >
    <tr>
        <td colspan="3" >
            <div style=" border: 1px solid green ; " align="center" >
                <div style=" height: 400px; " id="piechart_total" ></div>
                
            </div>
        </td>
    </tr>
    <tr>
        <td width="33%" >
            <div style=" border: 1px solid green ; " align="center" >
                <div style=" height: 350px; " id="piechart_hot" align="center" ></div>
                
            </div>
        </td>
        <td width="33%" >
            <div style=" border: 1px solid green ; " align="center" >
                <div style=" height: 350px; " id="piechart_queen" align="center" ></div>
                
            </div>
        </td>
        <td width="33%" >
            <div style=" border: 1px solid green ; " align="center" >
                <div style=" height: 350px; " id="piechart_hotel" align="center" ></div>
                
            </div>
        </td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="left" style="padding-left: 90px;" >
            <input type="button" style="background-color: #890d43; height: 15px; width: 25px; " /> : [[.entrance_ticket.]]<br />
            <input type="button" style="background-color: #04B0AD; height: 15px; width: 25px; " /> : [[.room_revenue.]]<br />
            <input type="button" style="background-color: #0099ff; height: 15px; width: 25px; " /> : [[.room_service_revenue.]]
        </td>
        <td align="left" style="padding-left: 90px;" >
            <input type="button" style="background-color: #036E09; height: 15px; width: 25px; " /> : [[.FB_Revenue.]]<br />
            <input type="button" style="background-color: #003322; height: 15px; width: 25px; " /> : [[.highwire.]]<br />
            <input type="button" style="background-color: #E4EB17; height: 15px; width: 25px; " /> : [[.zipline.]]
        </td>
        <td align="left" style="padding-left: 90px;" >
            <input type="button" style="background-color: #ff9999; height: 15px; width: 25px; " /> : [[.craft_village.]]<br />
            <input type="button" style="background-color: #ff6600; height: 15px; width: 25px; " /> : [[.souvenir.]]<br />
            <input type="button" style="background-color: #ff0099; height: 15px; width: 25px; " /> : [[.spa_revenue.]]
        </td>
    </tr>
</table>
</div>	
<br />
<script>
    full_screen();
    jQuery(document).ready(function(){
        //piechart_total
        var piechart_total;
        var data_total = [];
        var array_names = ['Alba Thanh Tan Hotsprings'
                        ,'Alba Queen Hotels'
                        ,'Alba Hotels'
                        ,'Alba waters'];
        var items = [[|data_total|]];
        j = 0;
        var tong = 0;
        
        for(i in items)
        {
            data_total[j] = [];
            data_total[j][0] = array_names[j];
            data_total[j][1] = to_numeric(items[i]);
            j++;
        }
        
        //console.log(data_total);
        
        piechart_total = new Highcharts.Chart(
        {
            chart:{
                renderTo:'piechart_total',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                    text: "Thanh Tan - Revenue by segment"
            },
            colors: [
               '#D514F7', 
               '#F71436', 
               '#060B99', 
               '#4AD40B', 
               '#003322', 
               '#E4EB17',
               '#ff9999',
               '#ff6600',
               '#ff0099'
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
                                return this.point.name + ':' + number_format(this.y)+ '(' + roundNumber(this.percentage,1) + ' %) ';
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '12px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: 'Thanh Tan - Revenue by segment',
				data:data_total
			}]
        });
        
        //piechart_hot
        var piechart_hot;
        var data_hot = [];
        var array_names = ['[[.entrance_ticket.]]'
                        ,'[[.room_revenue.]]'
                        ,'[[.room_service_revenue.]]'
                        ,'[[.FB_Revenue.]]'
                        ,'[[.highwire.]]'
                        ,'[[.zipline.]]'
                        ,'[[.craft_village.]]'
                        ,'[[.souvenir.]]'
                        ,'[[.spa_revenue.]]'];
        var items = [[|data_hot|]];
        j = 0;
        var tong = 0;
        
        for(i in items)
        {
            data_hot[j] = [];
            data_hot[j][0] = array_names[j];
            data_hot[j][1] = to_numeric(items[i]);
            j++;
        }
        
        //console.log(data_hot);
        
        piechart_hot = new Highcharts.Chart(
        {
            chart:{
                renderTo:'piechart_hot',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                margin: [70, 70, 70, 70]
            },
            title: {
                    text: "Alba Hotsprings - Revenue by services"
            },
            colors: [
               '#890d43', 
               '#04B0AD', 
               '#0099ff', 
               '#036E09', 
               '#003322', 
               '#E4EB17',
               '#ff9999',
               '#ff6600',
               '#ff0099'
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
                                return number_format(this.y)+'<br>(' + roundNumber(this.percentage,1) + ' %) ';
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: false,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_revenue.]]',
				data:data_hot
			}]
        });
        
        //piechart_queen
        var piechart_queen;
        var data_queen = [];
        var array_names = ['[[.entrance_ticket.]]'
                        ,'[[.room_revenue.]]'
                        ,'[[.room_service_revenue.]]'
                        ,'[[.FB_Revenue.]]'
                        ,'[[.highwire.]]'
                        ,'[[.zipline.]]'
                        ,'[[.craft_village.]]'
                        ,'[[.souvenir.]]'
                        ,'[[.spa_revenue.]]'];
        var items = [[|data_queen|]];
        j = 0;
        var tong = 0;
        
        for(i in items)
        {
            data_queen[j] = [];
            data_queen[j][0] = array_names[j];
            data_queen[j][1] = to_numeric(items[i]);
            j++;
        }
        
        //console.log(data_queen);
        
        piechart_queen = new Highcharts.Chart(
        {
            chart:{
                renderTo:'piechart_queen',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                margin: [70, 70, 70, 70]
            },
            title: {
                    text: "Alba Queen Hotel - Revenue by services"
            },
            colors: [
               '#890d43', 
               '#04B0AD', 
               '#0099ff', 
               '#036E09', 
               '#003322', 
               '#E4EB17',
               '#ff9999',
               '#ff6600',
               '#ff0099'
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
                                return number_format(this.y)+'<br>(' + roundNumber(this.percentage,1) + ' %) ';
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: false,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_revenue.]]',
				data:data_queen
			}]
        });
        
        //piechart_hotel
        var piechart_hotel;
        var data_hotel = [];
        var array_names = ['[[.entrance_ticket.]]'
                        ,'[[.room_revenue.]]'
                        ,'[[.room_service_revenue.]]'
                        ,'[[.FB_Revenue.]]'
                        ,'[[.highwire.]]'
                        ,'[[.zipline.]]'
                        ,'[[.craft_village.]]'
                        ,'[[.souvenir.]]'
                        ,'[[.spa_revenue.]]'];
        var items = [[|data_hotel|]];
        j = 0;
        var tong = 0;
        
        for(i in items)
        {
            data_hotel[j] = [];
            data_hotel[j][0] = array_names[j];
            data_hotel[j][1] = to_numeric(items[i]);
            j++;
        }
        
        //console.log(data_hotel);
        
        piechart_hotel = new Highcharts.Chart(
        {
            chart:{
                renderTo:'piechart_hotel',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                margin: [70, 70, 70, 70]
            },
            title: {
                    text: "Alba Hotel - Revenue by services"
            },
            colors: [
               '#890d43', 
               '#04B0AD', 
               '#0099ff', 
               '#99ff00', 
               '#003322', 
               '#E4EB17',
               '#ff9999',
               '#ff6600',
               '#ff0099'
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
                                return number_format(this.y)+'<br>(' + roundNumber(this.percentage,1) + ' %) ';
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: false,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_revenue.]]',
				data:data_hotel
			}]
        });
    });
</script>