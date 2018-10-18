<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top" style="font-size:11px;">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
            <br />
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
			</td>
			</tr>	
		</table>
	</td>
</tr>
<tr>
	<td>
		<table class="table_border" width="100%" border="0" cellspacing="0" cellpadding="2">
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
			<td align="center" style="font-weight:bold;text-transform:uppercase;font-size:15px;">[[.temporaty_absent_list.]]</td>
		  </tr>
		   <tr>
		     <td align="center" style="font-size:12px;">[[.date.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?></td>
	      </tr>		  
		   <tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="6" bordercolor="#CCCCCC" style="font-size:11px; border-collapse:collapse;">
				  <tr style="background-color:#CCCCCC">
					<td align="center" width="10px"><strong>[[.stt.]]</strong></td>
					<td align="center" width="150px"><strong>[[.full_name.]]</strong></td>
					<td align="center" width="20px"><strong>[[.room.]]</strong></td>                    
                    <td align="center" width="50px"><strong>[[.birth_day.]]</strong></td>
					<td align="center" width="50px"><strong>[[.gender.]]</strong></td>
					<td align="center" width="70px"><strong>[[.country.]]</strong></td>
					<td align="center" width="70px"><strong>[[.passport.]]</strong></td>
					<td align="center" width="70px"><strong>[[.visa_number.]]</strong></td>
                    <td align="center" width="50px"><strong>[[.expire_date_of_visa.]]</strong></td>
					<td align="center" width="50px"><strong>[[.date_entry.]]</strong></td>
					<td align="center" width="100px"><strong>[[.port.]]</strong></td>
					<td align="center" width="50px"><strong>[[.arrive_date.]]</strong></td>
					<td align="center" width="50px"><strong>[[.depart_date.]]</strong></td>
					<td align="center" width="100px"><strong>[[.nguoidon.]]</strong></td>
				  </tr>
				  <?php $i=0;?>
				  <!--LIST:items-->
				  <tr>
					<td><?php echo ++$i;?></td>
					<td>
                    <a href="<?php echo Url::build('traveller',array('id'=>[[=items.id=]],'cmd'=>'edit'));?>" target="_blank">[[|items.first_name|]] [[|items.last_name|]]</a>
                    </td>
                    <td>[[|items.room_name|]]</td>
					<td align="center"><?php echo ([[=items.birth_date=]]);?></td>
                    <td align="center"><!--IF:cond([[=items.gender=]]==1)-->[[.male.]]<!--ELSE-->[[.female.]]<!--/IF:cond--></td>
					<td align="center">[[|items.country|]]</td>
					<td align="center">[[|items.passport|]]</td>
					<td align="center">[[|items.visa_number|]]</td>
					<td align="center">[[|items.expire_date_of_visa|]]</td>                    
					<td align="center">[[|items.date_entry|]]</td>
					<td align="center">[[|items.port|]]</td>
					<td><?php echo (date('d/m/Y', [[=items.rt_arrival_time=]]));?></td>
					<td><?php echo (date('d/m/Y', [[=items.rt_departure_time=]]));?></td>
					<td align="center">[[|items.go_to_office|]]</td>
				  </tr>
  				  <!--/LIST:items-->				
			 </table>			 </td>
		  </tr>
		  <tr>
		  	<td align="right">[[|paging|]]</td>
		  </tr>
		  <tr>
		  	<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:11px;">
			  <tr>
				<td width="52%">[[.total_guest_new.]] : [[|total_guest_new|]]</td>
			    <td width="24%">[[.nguoilap.]]</td>
			    <td width="24%">[[.manager_hotel.]]</td>
			  </tr>
			  <tr>
				<td colspan="3">- [[.male.]] : [[|total_guest_male|]] </td>
			  </tr>
			  <tr>
				<td colspan="3">- [[.female.]] : [[|total_guest_female|]]</td>
			  </tr>
			  <tr>
				<td colspan="3">[[.total_guest_old.]] : <?php echo  [[=total_guest_old=]] ?></td>
			  </tr>
			  <tr>
				<td colspan="3">- [[.male.]] : <?php echo [[=total_guest_old_male=]] ?> </td>
			  </tr>
			   <tr>
				<td colspan="3">- [[.female.]] <?php echo  [[=total_guest_old_female=]] ?></td>
			  </tr>
			</table>

			</td>
		  </tr>
		</table>
	</td>
</tr>
</table>
<style>
{
	border-collapse:collapse !important;
	border:1px solid #999;
</style>
