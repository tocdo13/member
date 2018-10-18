<?php //System::debug([[=items=]]); ?>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page([[=page_no=]]==[[=start_page=]])-->
<div class="report-bound"> 
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        
                        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo User::id();?>

                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.arrival_day_used_room_list.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.from_date.]]&nbsp;[[|day|]]&nbsp; [[.to.]]&nbsp; <?php 
                                if(isset([[=to_date=]]))
                                {
                                    echo [[=to_date=]];
                                } 
                                else 
                                {
                                    echo date('d/m/Y');
                                } ?>
                            </span> 
                        </div>
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
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right; width: 50px;"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right;width: 30px;"/></td>
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right;width: 30px;"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.from_date.]] &nbsp;<input name="date" type="text" id="date" style="width: 80px;"/></td>
                                	<td>[[.to.]]&nbsp;<input name="to_date" type="text" id="to_date" style="width: 80px;"/></td>
                                    <td>[[.status.]]</td>
                                	<td><select name="status" id="status"></select></td>
                                    <td>[[.early_late.]]</td>
                                	<td><select name="early_late" id="early_late"></select></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
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
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
        
        jQuery('#to_date').datepicker();
    }
);
</script>
<!--/IF:first_page-->

<!---------REPORT----------->	
<!--IF:check_room([[=real_room_count=]])-->


<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="20px">[[.reservation_room_code.]]</th>
        <th class="report_table_header" width="50px">[[.room.]]</th>
        <th class="report_table_header" width="50px">[[.room_level.]]</th>
        <th class="report_table_header" width="50px">[[.price.]]</th>
        <th class="report_table_header" width="200px">[[.tour.]],[[.company.]]</th>                        
        <th class="report_table_header" width="150px">[[.guest_name.]]</th>
        <th class="report_table_header" width="20px">[[.countries.]]</th>
        <th class="report_table_header" width="20px">[[.A/c.]]</th>
        <th class="report_table_header" width="60px">[[.arrival_date.]]</th>
        <th class="report_table_header" width="60px">[[.departure_date.]]</th>
        <th class="report_table_header" width="20px">[[.eililodayuse_qty.]]</th>
        <th class="report_table_header" width="40px">[[.flight_code.]]</th>
        <th class="report_table_header" width="60px">[[.flight_arrival_time.]]</th>
        <th class="report_table_header" width="70px">[[.car_note_arrival.]]</th>
    </tr>
    
    <?php
        $i=1;
        $is_rowspan = false; 
        $total_money=0;
        $total_adult = 0;
        $total_child = 0;
        $total_night = 0;
        $total_room = 0;
        
        $total_money2=0;
        $total_adult2 = 0;
        $total_child2 = 0;
        $total_night2 = 0;
        $total_room2 = 0;
        $total_room_4 = 0;
        //System::debug($this->map['count_traveller'][[[=items.reservation_room_code=]]]['num']);
    ?>
    <?php
    if([[=page_no=]]!=1){
    ?>
        <tr>
           <th>[[.last_page_summary.]]</th>
           <th><?php echo System::display_number([[=last_total_sammary=]]['total_room']); ?></th>
           <th></th>
           <th><?php echo System::display_number([[=last_total_sammary=]]['total_price']); ?></th>
           <th colspan="3"></th>
           <th><?php echo System::display_number([[=last_total_sammary=]]['total_adult'])."/".System::display_number([[=last_total_sammary=]]['total_child']); ?></th>
           <th colspan="2"></th>
           <th><?php echo System::display_number([[=last_total_sammary=]]['total_night']); ?></th>
           <th colspan="3"></th>
        </tr>
    <?php } ?>
	<!--LIST:items-->
    <tr bgcolor="white">
		
        <?php 
            $k = $this->map['count_traveller'][[[=items.reservation_room_code=]]]['num'];
            //echo $k.'-'.[[=items.reservation_room_code=]].'-'.[[=items.reservation_id=]];
            if($is_rowspan == false)
            {
        ?>
        <td class="report_table_column" rowspan="<?php echo $k; $total_room += 1;?>"  align="center">
            <div style="font-size:11px;">
                <a href="<?php 
                $reservation_room_code = explode('_',[[=items.reservation_room_code=]]);
                $reservation_room_code = $reservation_room_code[0];
                echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>$reservation_room_code));
                ?>" target="_blank">[[|items.reservation_id|]]</a>
            </div>
        </td>
        <td class="report_table_column" rowspan="<?php echo $k; $total_room_4 += [[=items.room_num=]];?>" align="center"><div style="font-size:11px;">
            <strong>[[|items.room_name|]]</strong><!--IF:cond([[=items.early_checkin=]] != 0)--> - E.I<!--/IF:cond-->
                                <!--IF:cond([[=items.late_checkout=]] != 0)-->- L.O<!--/IF:cond-->
                                <!--IF:cond([[=items.late_checkin=]] != 0)-->- L.I<!--/IF:cond-->
                                <!--IF:cond([[=items.day_use=]] != 0)-->- D.U<!--/IF:cond-->
        </div></td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>"  align="center"><div style="font-size:11px;">[[|items.brief_name|]]</div></td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>" align="right"><div style="font-size:11px;"><?php echo System::display_number([[=items.price=]]); $total_money += [[=items.price=]];?></div></td>
        <td class="report_table_column" style="white-space: normal; text-align: left; font-size:10px;" rowspan="<?php echo $k; ?>"><div style="font-size:13px;"><b>[[|items.note|]]</b></div><i>[[|items.reservation_note|]]</i></td> 
        <?php
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>                           
		<td class="report_table_column" style="text-align: left;">
            <div style="font-size:11px;">
                <a href="<?php echo Url::build('traveller',array('id'=>[[=items.traveller_id=]]));?>" target="_blank">[[|items.fullname|]]</a>
            </div>        
        </td>
        <td class="report_table_column" style="white-space: normal;" align="center"><div style="font-size:11px;">[[|items.country_code|]]</div></td>
        
        <?php
            $i++;
            }
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" align="center"><div style="font-size:11px;">
            <!--Neu phong co khach thi cho ra so khach, neu khong thi cho ra so adult trong csdl-->
            <?php echo [[=items.adult=]]; $total_adult += [[=items.adult=]];?>/<?php echo ([[=items.child=]]?[[=items.child=]]:0); if ([[=items.child=]] > 0) $total_child += [[=items.child=]]; ?></div>        </td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" align="center"><div style="font-size:11px;">
			<?php echo date('d/m/Y H:i',[[=items.time_in=]]);?></div>        </td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" align="center"><div style="font-size:11px;">
			<?php echo date('d/m/Y H:i',[[=items.time_out=]]);?></div>        </td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>"  align="center"><div style="font-size:11px;">[[|items.room_num|]]<?php $total_night += [[=items.room_num=]];?></div></td>
        <?php
            }
        ?>
        <td class="report_table_column"><div style="font-size:11px;">[[|items.flight_code|]]</div></td>
        <td class="report_table_column"  align="center"><div style="font-size:11px;">
            <?php echo [[=items.flight_arrival_time=]]?date('d/m/Y H:i',[[=items.flight_arrival_time=]]):'';?></div>        </td>
        <td class="report_table_column"><div style="font-size:11px;">[[|items.car_note_arrival|]]</div></td>
        <?php 
            if($is_rowspan == false)
            {
        ?>

        <?php
            $is_rowspan = true;
            } 
        ?>
        <?php
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>
    </tr>
	<!--/LIST:items-->
    <!-- Manh them truong hop phan trang co tong trang truoc sau. -->
    <tr>
        <th><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
        <th><?php echo System::display_number([[=total_sammary=]]['total_room']); ?></th>
        <th></th>
        <th><?php echo System::display_number([[=total_sammary=]]['total_price']); ?></th>
        <th colspan="3"></th>
        <th><?php echo System::display_number([[=total_sammary=]]['total_adult'])."/".System::display_number([[=total_sammary=]]['total_child']); ?></th>
        <th colspan="2"></th>
        <th><?php echo System::display_number([[=total_sammary=]]['total_night']); ?></th>
        <th colspan="3"></th>
    </tr>
    
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <!--IF:check(([[=room_count=]]!=[[=real_room_count=]]) && (([[=real_page_no=]]==[[=real_total_page=]])))-->
	<!--<tr bgcolor="white">
		<td colspan="1" class="report_table_column"><strong><div style="font-size:10px;">[[.total.]]</div></strong></td>
		<td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room);?></div></strong></td>
        <td class="report_table_column">&nbsp;</td>
        <td class="report_table_column"  align="right"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_money) ?></div></strong></td>
		<td colspan="3" class="report_table_column">&nbsp;</td>
        <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_adult);?>/<?php //echo System::display_number($total_child);?></div></strong></td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room_4);?></div></strong></td>
        <td colspan="10" class="report_table_column">&nbsp;</td>
	</tr>-->
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<!--<tr bgcolor="white">
    		<td colspan="1" class="report_table_column"><strong><div style="font-size:10px;">[[.total.]]</div></strong></td>
    		<td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room);?></div></strong></td>
            <td class="report_table_column">&nbsp;</td>
            <td class="report_table_column"  align="right"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_money) ?></div></strong></td>
   		  <td colspan="3" class="report_table_column">&nbsp;</td>
            <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_adult);?>/<?php //echo System::display_number($total_child);?></div></strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room_4);?></div></strong></td>
            <td colspan="10" class="report_table_column">&nbsp;</td>
    	</tr>-->
    	<!--/IF:end_page-->
    <!--/IF:check-->
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]) || ([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
        <td></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_room-->