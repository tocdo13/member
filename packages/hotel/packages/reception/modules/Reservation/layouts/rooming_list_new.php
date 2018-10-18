<div>
<button id="export">[[.export.]]</button>
<table id="tblExport" width="100%"  cellspacing="0" cellpadding="5">
	<tr>
	   <td align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr valign="top">
				<td width="75%">[[.hotel.]]: [[|hotel_name|]]</td>
				<td width="25%"></td>
			</tr>
		</table>
	  </td>
    </tr>
	<tr>
		<td align="center"><h2><strong>DANH SÁCH KHÁCH PHÒNG</strong></h2></td>
	</tr>
	<tr>
	  <td >
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
              <td width="60%">Tên đoàn<i>/Group name:</i> [[|booking_code|]]</td>
			  <td>Tên Công ty<i>/Company name: </i> [[|customer_name|]]</td>
		  </tr>
			<tr>
				<td>Trưởng đoàn<i>/Tour Leader name:</i><span contenteditable="true"></span></td>
                <td>Ngày đến<i>/Checkin date: [[|arrival_date|]]</i></td>
			</tr>
            <tr>
                <td>Số điện thoại<i>/Tour Leader phone:</i><span contenteditable="true"></span></td>
                <td>Ngày đi<i>/Checkout date: [[|departure_date|]]</i></td>
            </tr>
            <tr>
                <td>Tổng số phòng<i>/Total room: [[|total_room|]]</i></td>
                <td>Recode: <?php Url::get('id'); ?></td>
            </tr>
		</table>	  
    </td>
    </tr>
	<tr>
		<td>
        <!--LIST:items-->
         <table border="1" width="100%" cellpadding="5">
            <tr><td bgcolor="#EFEFEF" align="center">Từ ngày <?php echo Date_Time::convert_orc_date_to_date([[=items.arrival_time=]],'/'); ?> Đến ngày <?php echo Date_Time::convert_orc_date_to_date([[=items.departure_time=]],'/'); ?></td></tr>
            <tr>
                <td>
                    <table border="1">
                        <tr>
                            <th align="center" width="50">STT<br />NO.</th>
                            <th align="center" width="250">Loại phòng<br />Room type</th>
							<th align="center" width="150">Số phòng<br />Room number</th>
							<th align="center" width="100">Ng.L<br />Adult</th>
							<th align="center" width="100">Tr.E<br />Child</th>
							<th align="center" width="250">Tên khách<br /> Guest name</th>
							<th align="center" width="250">Ngày đến<br />Arr.date</th>
							<th align="center" width="250">Ngày đi<br />Dept.date</th>
							<th align="center" width="300">Ghi chú<br />Remark</th>
						</tr>
                        <?php $i=1; ?>
                        <!--LIST:items.room_level-->
                        <!--LIST:items.room_level.travellers-->
                            <tr>
                                <!--IF:cond([[=items.room_level.travellers.first_traveller_of_room_level=]])-->
                                <td align="center" rowspan="[[|items.room_level.count_room_level_traveller|]]"><?php echo $i;$i++; ?></td>  
                                <td rowspan="[[|items.room_level.count_room_level_traveller|]]">[[|items.room_level.travellers.room_level|]]-[[|items.room_level.travellers.room_type|]]</td>  
                                <!--/IF:cond-->
                                <!--IF:cond1([[=items.room_level.travellers.first_traveller_of_room=]])-->
                                <td align="center" rowspan="[[|items.room_level.travellers.count_traveller|]]">[[|items.room_level.travellers.room_name|]]</td>
                                <td align="center" rowspan="[[|items.room_level.travellers.count_traveller|]]">[[|items.room_level.travellers.adult|]]</td> 
                                <td align="center" rowspan="[[|items.room_level.travellers.count_traveller|]]">[[|items.room_level.travellers.child|]]</td>      
                                <!--/IF:cond1-->
                                <td style="text-transform: capitalize;">[[|items.room_level.travellers.first_name|]] [[|items.room_level.travellers.last_name|]]</td>
                                <td align="center"><?php echo Date_Time::convert_orc_date_to_date([[=items.room_level.travellers.arrival_time=]],'/'); ?></td>
                                <td align="center"><?php echo Date_Time::convert_orc_date_to_date([[=items.room_level.travellers.departure_time=]],'/'); ?></td>
                                <td>[[|items.room_level.travellers.note|]]</td>
                            </tr> 
                            <?php  ?>
                        <!--/LIST:items.room_level.travellers-->
                         <tr style="font-weight: bold;">
                            <td colspan="2" align="right">[[.total.]]</td>
                            <td align="center">[[|items.room_level.count_room|]]</td>
                            <td align="center">[[|items.room_level.count_adult|]]</td>
                            <td align="center">[[|items.room_level.count_child|]]</td>
                            <td ></td>
                            <td ></td>
                            <td ></td>
                            <td></td>
                         </tr>   
                        <!--/LIST:items.room_level-->
                        <tr style="font-weight: bold;">
                            <td>[[.total.]]</td>
                            <td align="center"><?php echo count([[=items.room_level=]]); ?></td>
                            <td align="center">[[|items.count_room|]]</td>
                            <td align="center">[[|items.count_adult|]]</td>
                            <td align="center">[[|items.count_child|]]</td>
                            <td ></td>
                            <td ></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
         </table>
        <!--/LIST:items--> 
        </td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" >
    <tr style="line-height: 30px;"><td></td><td style="text-align: center;">Ngày/Date<?php echo date('d/m/Y'); ?></td></tr>
	<tr>
		<td width="50%" align="center"><b>Chữ ký Lễ tân <br /> Hotel's Front Office's Signature</b></td>
		<td width="50%" align="center"><b>Chữ ký Trưởng đoàn <br /> Tour Leader's Signature</b></td>
	</tr>
</table>
</div>
<script>
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery("#tr_search").remove();
            jQuery("#header_report").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
        });
    }
);
</script>