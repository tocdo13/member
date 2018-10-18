<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top" style="font-size:11px;">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<strong><?php echo Portal::language('template_code');?></strong><br />
			<i><?php echo Portal::language('promulgation');?></i>
            <br />
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
			</td>
			</tr>	
		</table>
	</td>
</tr>
<tr>
	<td>
		<table class="table_border" width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
			<td align="center" style="font-weight:bold;font-size:15px;text-transform:uppercase;"><?php echo Portal::language('cong_hoa_xa_hoi_chu_nghia_viet_name');?></td>
		  </tr>
		   <tr>
			<td align="center" style="font-weight:bold;font-size:14px;"><?php echo Portal::language('doc_lap_tu_do_hanh_phuc');?></td>
		  </tr>
		   <tr>
		     <td align="center">&nbsp;</td>
	      </tr>
		   <tr>
			<td align="center" style="font-weight:bold;text-transform:uppercase;font-size:15px;"><?php echo Portal::language('temporaty_absent_list');?></td>
		  </tr>
		   <tr>
		     <td align="center" style="font-size:12px;"><?php echo Portal::language('date');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?></td>
	      </tr>		  
		   <tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="6" bordercolor="#CCCCCC" style="font-size:11px; border-collapse:collapse;">
				  <tr style="background-color:#CCCCCC">
					<td align="center" width="10px"><strong><?php echo Portal::language('stt');?></strong></td>
					<td align="center" width="150px"><strong><?php echo Portal::language('full_name');?></strong></td>
					<td align="center" width="20px"><strong><?php echo Portal::language('room');?></strong></td>                    
                    <td align="center" width="50px"><strong><?php echo Portal::language('birth_day');?></strong></td>
					<td align="center" width="50px"><strong><?php echo Portal::language('gender');?></strong></td>
					<td align="center" width="70px"><strong><?php echo Portal::language('country');?></strong></td>
					<td align="center" width="70px"><strong><?php echo Portal::language('passport');?></strong></td>
					<td align="center" width="70px"><strong><?php echo Portal::language('visa_number');?></strong></td>
                    <td align="center" width="50px"><strong><?php echo Portal::language('expire_date_of_visa');?></strong></td>
					<td align="center" width="50px"><strong><?php echo Portal::language('date_entry');?></strong></td>
					<td align="center" width="100px"><strong><?php echo Portal::language('port');?></strong></td>
					<td align="center" width="50px"><strong><?php echo Portal::language('arrive_date');?></strong></td>
					<td align="center" width="50px"><strong><?php echo Portal::language('depart_date');?></strong></td>
					<td align="center" width="100px"><strong><?php echo Portal::language('nguoidon');?></strong></td>
				  </tr>
				  <?php $i=0;?>
				  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
				  <tr>
					<td><?php echo ++$i;?></td>
					<td>
                    <a href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['id'],'cmd'=>'edit'));?>" target="_blank"><?php echo $this->map['items']['current']['first_name'];?> <?php echo $this->map['items']['current']['last_name'];?></a>
                    </td>
                    <td><?php echo $this->map['items']['current']['room_name'];?></td>
					<td align="center"><?php echo ($this->map['items']['current']['birth_date']);?></td>
                    <td align="center"><?php 
				if(($this->map['items']['current']['gender']==1))
				{?><?php echo Portal::language('male');?> <?php }else{ ?><?php echo Portal::language('female');?>
				<?php
				}
				?></td>
					<td align="center"><?php echo $this->map['items']['current']['country'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['passport'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['visa_number'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['expire_date_of_visa'];?></td>                    
					<td align="center"><?php echo $this->map['items']['current']['date_entry'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['port'];?></td>
					<td><?php echo (date('d/m/Y', $this->map['items']['current']['rt_arrival_time']));?></td>
					<td><?php echo (date('d/m/Y', $this->map['items']['current']['rt_departure_time']));?></td>
					<td align="center"><?php echo $this->map['items']['current']['go_to_office'];?></td>
				  </tr>
  				  <?php }}unset($this->map['items']['current']);} ?>				
			 </table>			 </td>
		  </tr>
		  <tr>
		  	<td align="right"><?php echo $this->map['paging'];?></td>
		  </tr>
		  <tr>
		  	<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:11px;">
			  <tr>
				<td width="52%"><?php echo Portal::language('total_guest_new');?> : <?php echo $this->map['total_guest_new'];?></td>
			    <td width="24%"><?php echo Portal::language('nguoilap');?></td>
			    <td width="24%"><?php echo Portal::language('manager_hotel');?></td>
			  </tr>
			  <tr>
				<td colspan="3">- <?php echo Portal::language('male');?> : <?php echo $this->map['total_guest_male'];?> </td>
			  </tr>
			  <tr>
				<td colspan="3">- <?php echo Portal::language('female');?> : <?php echo $this->map['total_guest_female'];?></td>
			  </tr>
			  <tr>
				<td colspan="3"><?php echo Portal::language('total_guest_old');?> : <?php echo  $this->map['total_guest_old'] ?></td>
			  </tr>
			  <tr>
				<td colspan="3">- <?php echo Portal::language('male');?> : <?php echo $this->map['total_guest_old_male'] ?> </td>
			  </tr>
			   <tr>
				<td colspan="3">- <?php echo Portal::language('female');?> <?php echo  $this->map['total_guest_old_female'] ?></td>
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
