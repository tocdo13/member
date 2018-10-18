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
			<strong><?php echo Portal::language('template_code');?></strong><br /></td>
			</tr>	
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
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
			<td align="center" style="font-weight:bold;text-transform:uppercase;font-size:15px;"><?php echo Portal::language('combine_temporaty_absent_list');?></td>
		  </tr>
		   <tr>
		     <td align="center" style="font-size:13px;"><?php echo Portal::language('date');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?></td>
	      </tr>		  
		   <tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="font-size:10px; border-collapse:collapse;">
				  <tr>
					<th align="center" width="20px"><strong><?php echo Portal::language('stt');?></strong></th>
					<th align="center" width="150px"><strong><?php echo Portal::language('full_name');?></strong></th>
                    <th align="center" width="30px"><strong><?php echo Portal::language('gender');?></strong></th>
					<th align="center" width="50px"><strong><?php echo Portal::language('birth_day');?></strong></th>
					<th align="center" width="60px"><?php echo Portal::language('national');?></th>
					<th align="center" width="70px"><strong>Số CMND/Hộ Chiếu</strong></th>
                    <th align="center" width="200px"><strong>Nơi đăng ký hộ khẩu thường trú</strong></th>
					<th align="center" width="50px"><?php echo Portal::language('arrival_time');?></th>
					<th align="center" width="50px"><strong><?php echo Portal::language('departure_time');?></strong></th>
					<th align="center" width="30px"><?php echo Portal::language('room');?></th>
                    <th align="center" width="50px"><?php echo Portal::language('Họ và tên người thông báo lưu trú');?></th>
                    <th align="center" width="50px"><?php echo Portal::language('Họ và tên cán bộ công an tiếp nhận thông báo');?></th>
					<th align="center" width="50px"><?php echo Portal::language('note');?></th>
				  </tr>
				  <?php $i=0;?>
				  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
				  <tr>
					<td><?php echo ++$i;?></td>
					<td><?php echo $this->map['items']['current']['first_name'];?> &nbsp;<?php echo $this->map['items']['current']['last_name'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['gender'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['birth_date'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['country_name'];?></td>
					<td align="center"><?php echo $this->map['items']['current']['passport'];?></td>
                    <td align="center"><?php echo $this->map['items']['current']['address'];?></td>
					<td align="center"><?php echo date('d/mY',$this->map['items']['current']['arrival_time']);?></td>
                    <td align="center"><?php echo date('d/mY',$this->map['items']['current']['departure_time']);?></td>
					<td align="center"><?php echo $this->map['items']['current']['room_name'];?></td>
                    <td align="center"></td>
                    <td align="center"></td>
					<td align="center"><?php echo $this->map['items']['current']['note'];?></td>
				  </tr>
  				  <?php }}unset($this->map['items']['current']);} ?>				
			 </table>			 </td>
		  </tr>
		  <tr>
		  	<td align="right"><?php echo $this->map['paging'];?></td>
		  </tr>
		  <tr>
		  	<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:10px;">
			  <tr>
				<td width="52%"><?php echo Portal::language('total_guest');?> : <?php echo $this->map['total_guest'];?></td>
			    <td width="24%"><?php echo Portal::language('nguoilap');?></td>
			    <td width="24%"><?php echo Portal::language('manager_hotel');?></td>
			  </tr>
			 <tr>
             	<td><?php echo Portal::language('guest_national');?> : <?php echo $this->map['total_national'];?></td>
                <td></td>
                <td></td>
             </tr>
             <tr>
             	<td><?php echo Portal::language('guest_vietnamese');?> : <?php echo $this->map['total_vietnamese'];?></td>
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
        	<td width="10"><b><?php echo Portal::language('no');?></b></td>
            <td width="200"><b><?php echo Portal::language('national');?></b></td>
            <td width="80"><b><?php echo Portal::language('male');?></b></td>
            <td width="80"><b><?php echo Portal::language('female');?></b></td>
            <td width="80"><b><?php echo Portal::language('total');?></b></td>
        </tr>
        <?php if(isset($this->map['national']) and is_array($this->map['national'])){ foreach($this->map['national'] as $key2=>&$item2){if($key2!='current'){$this->map['national']['current'] = &$item2;?>
        <tr>
        	<td><?php echo ++$j;?></td>
            <td style="text-align:left !important;"><?php echo $this->map['national']['current']['country_name'];?></td>
            <td><?php if($this->map['national']['current']['male'] != 0) echo $this->map['national']['current']['male']; else echo '';?></td>
            <td><?php if($this->map['national']['current']['female'] != 0) echo $this->map['national']['current']['female']; else echo '';?></td>
            <td><?php echo $this->map['national']['current']['total_guest'];?></td>
        </tr>
        <?php }}unset($this->map['national']['current']);} ?>
    </table>
    </td>
</tr>
</table>