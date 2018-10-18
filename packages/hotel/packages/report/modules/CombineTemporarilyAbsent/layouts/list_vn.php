<style>
#count_national{
	margin: 10px auto;
	border:1px solid silver;
}
#count_national tr td{
	border:1px solid silver;
	line-height:20px;
	text-align:center;
}
</style>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top" style="font-size:11px;">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br /></td>
			</tr>	
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
			<td align="center" style="font-weight:bold;font-size:15px;text-transform:uppercase;">[[.cong_hoa_xa_hoi_chu_nghia_viet_name.]]</td>
		  </tr>
		   <tr>
			<td align="center" style="font-weight:bold;font-size:14px;">[[.doc_lap_tu_do_hanh_phuc.]]</td>
		  </tr>
		   <tr>
		     <td align="center">&nbsp;</td>
	      </tr>
		   <tr>
			<td align="center" style="font-weight:bold;text-transform:uppercase;font-size:15px;">[[.combine_temporaty_absent_list.]]</td>
		  </tr>
		   <tr>
		     <td align="center" style="font-size:13px;">[[.date.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?></td>
	      </tr>		  
		   <tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="font-size:10px; border-collapse:collapse;">
				  <tr>
					<th align="center" width="20px"><strong>[[.stt.]]</strong></th>
					<th align="center" width="150px"><strong>[[.full_name.]]</strong></th>
                    <th align="center" width="30px"><strong>[[.gender.]]</strong></th>
					<th align="center" width="50px"><strong>[[.birth_day.]]</strong></th>
					<th align="center" width="60px">[[.national.]]</th>
					<th align="center" width="70px"><strong>Số CMND/Hộ Chiếu</strong></th>
                    <th align="center" width="200px"><strong>Nơi đăng ký hộ khẩu thường trú</strong></th>
					<th align="center" width="50px">[[.arrival_time.]]</th>
					<th align="center" width="50px"><strong>[[.departure_time.]]</strong></th>
					<th align="center" width="30px">[[.room.]]</th>
                    <th align="center" width="50px">[[.Họ và tên người thông báo lưu trú.]]</th>
                    <th align="center" width="50px">[[.Họ và tên cán bộ công an tiếp nhận thông báo.]]</th>
					<th align="center" width="50px">[[.note.]]</th>
				  </tr>
				  <?php $i=0;?>
				  <!--LIST:items-->
				  <tr>
					<td><?php echo ++$i;?></td>
					<td>[[|items.first_name|]] &nbsp;[[|items.last_name|]]</td>
					<td align="center">[[|items.gender|]]</td>
					<td align="center"><?php echo [[=items.birth_date=]];?></td>
					<td align="center">[[|items.country_name|]]</td>
					<td align="center">[[|items.passport|]]</td>
                    <td align="center">[[|items.address|]]</td>
					<td align="center"><?php echo date('d/mY',[[=items.arrival_time=]]);?></td>
                    <td align="center"><?php echo date('d/mY',[[=items.departure_time=]]);?></td>
					<td align="center">[[|items.room_name|]]</td>
                    <td align="center"></td>
                    <td align="center"></td>
					<td align="center">[[|items.note|]]</td>
				  </tr>
  				  <!--/LIST:items-->				
			 </table>			 </td>
		  </tr>
		  <tr>
		  	<td align="right">[[|paging|]]</td>
		  </tr>
		  <tr>
		  	<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:10px;">
			  <tr>
				<td width="52%">[[.total_guest.]] : [[|total_guest|]]</td>
			    <td width="24%">[[.nguoilap.]]</td>
			    <td width="24%">[[.manager_hotel.]]</td>
			  </tr>
			 <tr>
             	<td>[[.guest_national.]] : [[|total_national|]]</td>
                <td></td>
                <td></td>
             </tr>
             <tr>
             	<td>[[.guest_vietnamese.]] : [[|total_vietnamese|]]</td>
                <td></td>
                <td></td>
             </tr>
             <tr>
             	<td></td>
                <td></td>
                <td></td>
             </tr>
			</table>
			</td>
		  </tr>
		</table>
	</td>
</tr>
<tr>
<td colspan="3" align="center">
 <?php $j=0;?>
	<table id="count_national" style="font-size:10px; border-collapse:collapse;">
    	<tr>
        	<td width="10"><b>[[.no.]]</b></td>
            <td width="200"><b>[[.national.]]</b></td>
            <td width="80"><b>[[.male.]]</b></td>
            <td width="80"><b>[[.female.]]</b></td>
            <td width="80"><b>[[.total.]]</b></td>
        </tr>
        <!--LIST:national-->
        <tr>
        	<td><?php echo ++$j;?></td>
            <td style="text-align:left !important;">[[|national.country_name|]]</td>
            <td><?php if([[=national.male=]] != 0) echo [[=national.male=]]; else echo '';?></td>
            <td><?php if([[=national.female=]] != 0) echo [[=national.female=]]; else echo '';?></td>
            <td>[[|national.total_guest|]]</td>
        </tr>
        <!--/LIST:national-->
    </table>
    </td>
</tr>
</table>