<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:12px;">
                    <td align="left" width="80%" >
                        <strong><?php echo HOTEL_NAME;?>: </strong>
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                    </td>
                 </tr>
                 <tr>
    				<td align="center" colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[|title|]]</font>
                        </div>
                    </td>
               	</tr>	
    		</table>
        </td></tr>
    </table>		
</div>
<style type="text/css">
.table-bound{margin: 0 auto !important;}
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>
<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="10px" rowspan="2">[[.stt.]]</th>
		<th class="report_table_header" width="200px" rowspan="2">[[.room_type.]]</th>
		<th class="report_table_header" width="290px" colspan="5">[[|header_1|]]</th>
        <th class="report_table_header" width="290px" colspan="5">[[|header_2|]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.note.]]</th>
    </tr>

    <tr>
		<th class="report_table_header" width="30px">[[.no_of_room.]]</th>
        <th class="report_table_header" width="50px">[[.occ.]] (%)</th>
        <th class="report_table_header" width="30px">[[.room_day.]]</th>
        <th class="report_table_header" width="100px">[[.price.]]</th>
        <th class="report_table_header" width="100px">[[.revenue.]]</th>
		<th class="report_table_header" width="30px">[[.no_of_room.]]</th>
        <th class="report_table_header" width="50px">[[.occ.]] (%)</th>
        <th class="report_table_header" width="30px">[[.room_day.]]</th>
        <th class="report_table_header" width="100px">[[.price.]]</th>
        <th class="report_table_header" width="100px">[[.revenue.]]</th>
    </tr>
    <!--LIST:room_level-->
    <tr>
        <td class="report_table_column" align="center">[[|room_level.stt|]]</td>
		<td class="report_table_column" align="left">[[|room_level.name|]]</td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.real_num_room_current=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.occupancy_current=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.num_night_current=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.average_price_current=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.revenue_current=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.real_num_room_next=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.occupancy_next=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.num_night_next=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.average_price_next=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_level.revenue_next=]]); ?></td>
        <td class="report_table_column" align="right"></td>
    </tr>
    <!--/LIST:room_level-->
    <tr style="font-weight: bold;">
        <td class="report_table_column" align="left" colspan="2">[[.average_price.]]</td>
		<td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['average_price_current']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['average_price_next']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
    </tr>
    <tr style="font-weight: bold;">
        <td class="report_table_column" align="left" colspan="2">[[.average_occupancy.]]</td>
		<td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['occupancy_current']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['occupancy_next']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
    </tr>
    <tr style="font-weight: bold;">
        <td class="report_table_column" align="left" colspan="2">[[.total.]]</td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['real_num_room_current']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['num_night_current']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['revenue_current']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['real_num_room_next']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['num_night_next']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['revenue_next']); ?></td>
        <td class="report_table_column" align="right"></td>
    </tr>
</table>



<br/>


<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td></td>
	<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td align="center" width="33%" >[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<div style="page-break-before:always;page-break-after:always;"></div>