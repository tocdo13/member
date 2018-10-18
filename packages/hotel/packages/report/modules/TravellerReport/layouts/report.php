<!---------REPORT----------->	
<tr>
<td>
<style>
	.report_table_column{
		width:65px;	
	}
	.simple-layout-middle{
		width:100%;	
	}
</style>
<table  cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="20px" align="center" class="report_table_header">[[.stt.]]</th>
		<th class="report_table_header">[[.company_name.]]</th>
        <th class="report_table_header" width="15px">[[.booking_code.]]</th>
        <th class="report_table_header" width="15px">[[.re_code.]]</th>
		<th class="report_table_header">[[.traveller_name.]]</th>
		<th class="report_table_header">[[.status.]]</th>
		<th class="report_table_header">[[.gender.]]</th>
		<th class="report_table_header">[[.birth_date.]]</th>
		<th class="report_table_header">[[.nationality.]]</th>
		<th class="report_table_header">[[.passport.]]</th>
		<th class="report_table_header">[[.room.]]</th>
        <th class="report_table_header">[[.num_traveller.]]</th>
        <th class="report_table_header">[[.room_price.]]</th>
		<!--IF:status(!URL::get('status'))-->
		<th class="report_table_header">[[.status.]]</th>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
	  <th class="report_table_header">[[.price.]]<br/></th>
		<!--/IF:price-->
        <th class="report_table_header">[[.hour_in.]]</th>
		<th class="report_table_header">[[.arrival_date.]]</th>
        <th class="report_table_header">[[.hour_out.]]</th>
		<th class="report_table_header">[[.departure_date.]]</th>
    </tr>
<!--start: KID  1
<!--IF:first_pages([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
		<td align="center" class="report_table_column" width="130"><strong><?php echo System::display_number([[=last_group_function_params=]]['guest_count']);?></strong></td>
		<td colspan="5" align="center" class="report_table_column" width="30">&nbsp;</td>
		<td align="center" class="report_table_column" width="1%"><strong><?php echo System::display_number([[=last_group_function_params=]]['room_count']);?></strong></td>
        <td colspan="2" align="center" class="report_table_column" width="30">&nbsp;</td>
		<!--IF:status(!URL::get('status'))-->
		<td>&nbsp;</td>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
		<td align="center" class="report_table_column">
			<strong><?php echo System::display_number([[=last_group_function_params=]]['total_price']);?></strong>		</td>
		<!--/IF:price-->		
		<td colspan="4"></td>
        
    </tr>
<!--/IF:first_pages-->
<!--end:KID--> 
    <?php 
        $r_id = '';
        $rr_id = '';
        $i = 1;
        $total_traveller=0;
     ?>
	<!--LIST:items-->
	<tr bgcolor="white">
        
        <?php if($r_id!=[[=items.reservation_id=]])
        { 
            $r_id=[[=items.reservation_id=]]; 
            $rowspan = $this->map['count'][$r_id]['count'];
            //echo $rowspan;
        ?>
		<td align="center" valign="middle" class="report_table_column" rowspan="<?php echo $rowspan; ?>"><?php echo $i++;?></td>
		<td align="left" valign="middle" class="report_table_column" rowspan="<?php echo $rowspan; ?>"><div style="float:left;width:80px;">[[|items.customer_name|]]</div></td>
        <td align="left" class="report_table_column" style="width:15px !important;" rowspan="<?php echo $rowspan; ?>">[[|items.booking_code|]]</td>
        <td align="left" class="report_table_column" style="width:15px !important;" rowspan="<?php echo $rowspan; ?>"><a href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]&r_r_id=[[|items.reservation_room_id|]]" target="_blank">[[|items.reservation_id|]]</a></td>
        <?php } ?>
        <td align="left" class="report_table_column" width="130">
			[[|items.first_name|]]
		[[|items.last_name|]]</td>
		<td align="center" class="report_table_column" width="30">[[|items.status|]] </td>
		<td align="center" class="report_table_column" width="30">
			[[|items.gender|]]		</td>
		<td  align="center" class="report_table_column">[[|items.birth_date|]]</td>
		<td align="center" class="report_table_column" title="[[|items.nationality_name|]]">
			[[|items.nationality_code|]]</td>
		<td align="center" class="report_table_column">[[|items.passport|]]</td>
		<?php 
        if([[=items.reservation_id=]].'-'.[[=items.reservation_room_id=]]!=$r_id.'-'.$rr_id)
        {
            $rr_id = [[=items.reservation_room_id=]];
            $rowspan_1 = $this->map['count'][$r_id][$rr_id];
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $rowspan_1; ?>">[[|items.room_name|]]</td>
        <td align="right" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>"><?php echo System::display_number([[=items.num_traveller=]]);$total_traveller+=[[=items.num_traveller=]]; ?></td>
        <td align="right" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>"><?php echo System::display_number(round([[=items.room_price=]]));?></td>
		<!--IF:status(!URL::get('status'))-->
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">[[|items.status|]]</td>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
		<!--IF:cond([[=items.price=]])-->
			[[|items.price|]] 
		<!--/IF:cond-->		</td>
		<!--/IF:price-->	
        <td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('H:i',[[=items.time_in=]]);?></td>	
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('d/m/Y',[[=items.time_in=]]);?></td>
        <td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('H:i',[[=items.time_out=]]);?></td>    
		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
			<?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
   <?php }?>
    </tr>
	<!--/LIST:items-->
	<tr bgcolor="white">
		<td colspan="4" align="center" valign="middle" class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
		<td align="center" class="report_table_column" width="130"><strong><?php echo System::display_number([[=group_function_params=]]['guest_count']);?></strong></td>
		<td colspan="5" align="center" class="report_table_column" width="30">&nbsp;</td>
		<td align="center" class="report_table_column" width="1%"><strong><?php echo System::display_number([[=group_function_params=]]['room_count']);?></strong></td>
        <td align="right" class="report_table_column" width="30"><?php echo $total_traveller;?></td>
        <td colspan="2" align="center" class="report_table_column" width="30">&nbsp;</td>
		<!--IF:status(!URL::get('status'))-->
		<td>&nbsp;</td>
		<!--/IF:status-->
		<!--IF:price([[=price=]]==1)-->
		<td align="center" class="report_table_column">
			<strong><?php echo System::display_number([[=group_function_params=]]['total_price']);?></strong>		</td>
		<!--/IF:price-->		
		<td colspan="3"></td>
	</tr>
</table>
</td>
</tr>
