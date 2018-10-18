<div>
<table width="100%"  cellspacing="0" cellpadding="5">
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
                <td>Tổng số phòng<i>/Total room: <?php echo count([[=arr_room_name=]]) ?></i></td>
                <td></td>
            </tr>
		</table>	  
    </td>
    </tr>
	<tr>
		<td>
			<!--LIST:items-->
            <?php $total_room=0;$total_adult=0;$total_child=0;$total_guest=0;$total_room_level=0;$total_room_night=0; ?>
			<!--IF:cond([[=items.rtrs=]])-->
			<table width="100%" border="1" cellspacing="0" cellpadding="5">
				<tr>
					<td align="center" bgcolor="#EFEFEF">[[.date_from.]]:[[|items.arrival_date|]] [[.date_to.]]:[[|items.departure_date|]]</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="1" cellspacing="0" cellpadding="2">
							<tr>
                                <th align="center" width="50">STT<br />NO.</th>
                                <th align="center" width="250">Loại phòng<br />Room type</th>
								<th align="center" width="150">Số phòng<br />Room number</th>
								<th align="center" width="100">Ng.L<br />Adult</th>
								<th align="center" width="100">Tr.E<br />Child</th>
                                <th align="center" width="100">Đêm phòng<br />Room night</th>
								<th align="center" width="250">Tên khách<br /> Guest name</th>
								<th align="center" width="250">Ngày đến<br />Arr.date</th>
								<th align="center" width="250">Ngày đi<br />Dept.date</th>
								<th align="center" width="300">Ghi chú<br />Remark</th>
							</tr>
							
                             <?php 
                             $i=1;
                             
                             foreach([[=arr_room_level_count=]] as $key=>$value)
                             {
                             	$res = false;
                             	foreach([[=arr_room_name=]] as $k=>$v)
                             	{
                             		$res_room = false;
                             ?>
							<!--LIST:items.rtrs-->
                            <?php 
                             	if($key==[[=items.rtrs.room_level_id=]] && $k==[[=items.rtrs.room_name=]])
                             	{
                            ?>
							<tr>
								<?php 
								if($res==false)
								{
									?>
									<td align="center" rowspan="<?php echo [[=arr_room_level_count=]][$key]['count'];?>"><?php echo $i++;?></td>
                                	<td align="center" rowspan="<?php echo [[=arr_room_level_count=]][$key]['count'];?>">[[|items.rtrs.room_level|]]-[[|items.rtrs.room_type|]]</td>
									<?php
									$res = true;
								}
								if($res_room==false)
								{
									$res_room = true;
									?>
									<td align="center" rowspan="<?php echo [[=arr_room_name=]][$k];?>">[[|items.rtrs.room_name|]]</td>
								
									<td align="center" rowspan="<?php echo [[=arr_room_name=]][$k];?>">[[|items.rtrs.adult|]]</td>
									<td align="center" rowspan="<?php echo [[=arr_room_name=]][$k];?>">[[|items.rtrs.child|]]</td>
                                    <td align="center" rowspan="<?php echo [[=arr_room_name=]][$k];?>">[[|items.rtrs.room_night|]]</td>
									<?php 
								}				
								?>
                                
								
										
								<td align="center" style="text-transform: capitalize;">[[|items.rtrs.first_name|]] [[|items.rtrs.last_name|]]</td>
								<td align="center"><?php echo date('d/m/Y',[[=items.rtrs.time_in=]]);?></td>
								<td align="center"><?php echo date('d/m/Y',[[=items.rtrs.time_out=]]);?></td>
								<td align="center">[[|items.rtrs.note|]]</td>
							</tr>
							<?php 
                             	}
                             ?>
							<!--/LIST:items.rtrs-->
							<?php 
                             	}
                             	?>
                                <?php 
                                $checkin_has_room_level=0;
                                foreach([[=items.rtrs=]] as $k_items=>$v_items){
                                        if($v_items['room_level_id']==$key){
                                            //echo $vv_items['room_level_id'].'--'.$key;
                                            $checkin_has_room_level=1;
                                            $total_room_level++;
                                            break;
                                        }
                                } 
                                if($checkin_has_room_level==1){
                                ?>
                             	<tr>
                             		<td colspan="2" align="right"><strong>[[.total.]]</strong></td>
                             		<td align="center"><strong><?php echo $value['room_num']; $total_room+=$value['room_num']; ?></strong></td>
                             		<td align="center"><strong><?php echo $value['adult_num']; $total_adult+=$value['adult_num'];?></strong></td>
                             		<td align="center"><strong><?php echo $value['child_num'];$total_child+=$value['child_num'] ?></strong></td>
                                    <td align="center"><strong><?php echo $value['room_night'];$total_room_night+=$value['room_night'] ?></strong></td>
                             		<td align="center"><strong><?php echo $value['guest_num']; $total_guest+=$value['guest_num']?></strong></td>
                             		<td colspan="6"></td>
                             	</tr>
                                <?php }?>
                             	<?php 
                             }
							?>
                             <tr>
                             		<td  align="right"><strong>[[.total.]]</strong></td>
                             		<td align="center" ><strong><?php echo $total_room_level; ?></strong></td>
                             		<td align="center" ><strong><?php echo $total_room; ?></strong></td>
                             		<td align="center" ><strong><?php echo $total_adult; ?></strong></td>
                             		<td align="center" ><strong><?php echo $total_child; ?></strong></td>
                                    <td align="center" ><strong><?php echo $total_room_night; ?></strong></td>
                             		<td align="center" ><strong><?php echo $total_guest; ?></strong></td>
                             		<td colspan="6"></td>
                             </tr>
						</table>					</td>
				</tr>
			</table><br />
			<!--/IF:cond-->
			<!--/LIST:items--></td>
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