<!--REPORT-->
    <!---TITLE--->
	<tr valign="middle" bgcolor="#EFEFEF">
        <th width="10px" class="report-table-header">[[.stt.]]</th>
        <th width="50px" class="report-table-header">[[.recode.]]</th>
        <th width="50px" class="report-table-header">[[.customer_name.]]</th>
        <th width="50px" class="report-table-header">[[.room.]]</th>
        <th width="50px" class="report-table-header">[[.guest_name.]]</th>
        <th width="50px" class="report-table-header">[[.arrival_time.]]</th>
        <th width="50px" class="report-table-header">[[.departure_time.]]</th>
        <th width="50px" class="report-table-header">[[.number_room_night.]]</th>
        <th width="50px" class="report-table-header">[[.price.]]</th>
        <th width="50px" class="report-table-header">[[.price(usd).]]</th>
        <th width="50px" class="report-table-header">[[.room_price_total.]]</th>
        <th width="10px" class="report-table-header">[[.rest.]]</th>
        <th width="50px" class="report-table-header">[[.minibar.]]</th>
        <th width="50px" class="report-table-header">[[.laundry.]]</th>
        <th width="50px" class="report-table-header">[[.telephone.]]</th>
        <th width="50px" class="report-table-header">[[.spa.]]</th>
        <th width="50px" class="report-table-header">[[.tour.]]</th>
        <th width="50px" class="report-table-header">[[.other.]]</th>
        <th width="50px" class="report-table-header">[[.total.]]</th>
        <th width="50px" class="report-table-header">[[.discount.]]</th>
        <th width="50px" class="report-table-header">[[.deposit.]]</th>
        <th width="50px" class="report-table-header">[[.group_deposit.]]</th>
	</tr>
    <!---/TITLE--->
    
    <!---GROUP ABOVE--->
    <!--IF:page_no([[=page_no=]]>1)-->
    <tr>
        <th colspan="10">[[.last_page_summary.]]</th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_room']?System::display_number($this->map['last_group_function_params']['total_remain_room']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_bar']?System::display_number($this->map['last_group_function_params']['total_remain_bar']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_minibar']?System::display_number($this->map['last_group_function_params']['total_remain_minibar']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_laundry']?System::display_number($this->map['last_group_function_params']['total_remain_laundry']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_telephone']?System::display_number($this->map['last_group_function_params']['total_remain_telephone']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_massage']?System::display_number($this->map['last_group_function_params']['total_remain_massage']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_tour']?System::display_number($this->map['last_group_function_params']['total_remain_tour']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_other']?System::display_number($this->map['last_group_function_params']['total_remain_other']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain']?System::display_number($this->map['last_group_function_params']['total_remain']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_discount']?System::display_number($this->map['last_group_function_params']['total_remain_discount']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_deposit']?System::display_number($this->map['last_group_function_params']['total_remain_deposit']):0; ?></th>
        <th align="right"><?php echo $this->map['last_group_function_params']['total_remain_group_deposit']?System::display_number($this->map['last_group_function_params']['total_remain_group_deposit']):0; ?></th>
    </tr>
    <!--/IF:page_no-->
    <!---/GROUP ABOVE--->
        
    <!---CELL--->
    <?php $reservation_id = ''; ?>
    <!--LIST:items-->
    <tr>
        <!--LIST:items.reservation_rooms-->
        <?php if($reservation_id != [[=items.recode=]]){ ?>
        <td align="center" rowspan="<?php echo count([[=items.reservation_rooms=]])-1; ?>">[[|items.stt|]]</td>
        <td align="center" rowspan="<?php echo count([[=items.reservation_rooms=]])-1; ?>"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.recode=]]));?>" style="color:#F00;">[[|items.recode|]]</a></td>
        <td align="left" rowspan="<?php echo count([[=items.reservation_rooms=]])-1; ?>">[[|items.customer_name|]]</td>
        <?php } ?>
        <td align="center">[[|items.reservation_rooms.room_name|]]</td>
        <td align="left">[[|items.reservation_rooms.guest_name|]]</td>
        <td align="center">[[|items.reservation_rooms.arrival_time|]]</td>
        <td align="center">[[|items.reservation_rooms.departure_time|]]</td>
        <td align="center">[[|items.reservation_rooms.dem|]]</td>
        <td align="right"><?php echo [[=items.reservation_rooms.price_vn=]]?System::display_number([[=items.reservation_rooms.price_vn=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.price_usd=]]?System::display_number([[=items.reservation_rooms.price_usd=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_room=]]?System::display_number([[=items.reservation_rooms.remain_room=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_bar=]]?System::display_number([[=items.reservation_rooms.remain_bar=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_minibar=]]?System::display_number([[=items.reservation_rooms.remain_minibar=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_laundry=]]?System::display_number([[=items.reservation_rooms.remain_laundry=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_telephone=]]?System::display_number([[=items.reservation_rooms.remain_telephone=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_massage=]]?System::display_number([[=items.reservation_rooms.remain_massage=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_tour=]]?System::display_number([[=items.reservation_rooms.remain_tour=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_other=]]?System::display_number([[=items.reservation_rooms.remain_other=]]):0; ?></td>
        <td align="right" style="font-weight: bold;"><?php echo [[=items.reservation_rooms.remain_total=]]?System::display_number([[=items.reservation_rooms.remain_total=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_discount=]]?System::display_number([[=items.reservation_rooms.remain_discount=]]):0; ?></td>
        <td align="right"><?php echo [[=items.reservation_rooms.remain_deposit=]]?System::display_number([[=items.reservation_rooms.remain_deposit=]]):0; ?></td>
        <?php if($reservation_id != [[=items.recode=]]){ ?>
        <td align="right" rowspan="<?php echo count([[=items.reservation_rooms=]])-1; ?>"><?php echo [[=items.remain_group_deposit=]]?System::display_number([[=items.remain_group_deposit=]]):0; ?></td>
        <?php $reservation_id = [[=items.recode=]];} ?>
    </tr>
    <!--/LIST:items.reservation_rooms-->
    <!--/LIST:items-->
    <!---CELL--->
    
    <!--GROUP TOTAL-->
    <tr>
        <th colspan="10"><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_room']?System::display_number($this->map['group_function_params']['total_remain_room']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_bar']?System::display_number($this->map['group_function_params']['total_remain_bar']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_minibar']?System::display_number($this->map['group_function_params']['total_remain_minibar']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_laundry']?System::display_number($this->map['group_function_params']['total_remain_laundry']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_telephone']?System::display_number($this->map['group_function_params']['total_remain_telephone']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_massage']?System::display_number($this->map['group_function_params']['total_remain_massage']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_tour']?System::display_number($this->map['group_function_params']['total_remain_tour']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_other']?System::display_number($this->map['group_function_params']['total_remain_other']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain']?System::display_number($this->map['group_function_params']['total_remain']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_discount']?System::display_number($this->map['group_function_params']['total_remain_discount']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_deposit']?System::display_number($this->map['group_function_params']['total_remain_deposit']):0; ?></th>
        <th align="right"><?php echo $this->map['group_function_params']['total_remain_group_deposit']?System::display_number($this->map['group_function_params']['total_remain_group_deposit']):0; ?></th>
    </tr>
    <!--/GROUP TOTAL-->
    
    <!--IF:page_no([[=page_no=]])-->
    <tr>
        <th colspan="22">
        <center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center>
        <!--IF:br([[=page_no=]]<[[=total_page=]])-->
        <br />
        <br />
        <!--/IF:br-->
        </th>
    </tr>
    <!--/IF:page_no-->
<!--/REPORT-->
    
