<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="35%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
    				<td> 
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" >[[.revenue_situation_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.date.]]&nbsp;[[|date|]]
                            </span> 
                        </div>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
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
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td>[[.date.]]</td>
                                    <td>
                                        <input name="date" type="text" id="date" style="width:100px;" class="by-year"/>
                                    </td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td><input type="submit" name="do_search" value="[[.report.]]"/></td>
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
.table-bound{margin: 0 auto !important;}
.desc{text-align: left !important;}
.main_title{text-align:left}
.sub_title_1{text-align:left; text-indent: 20px;}
.sub_title_2{text-align:left; text-indent: 40px;}
.sub_title_3{text-align:left; text-indent: 60px;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date').datepicker();
});
</script>



<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="150px" rowspan="2">[[.plan.]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.num_guest.]]</th>
		<th class="report_table_header" width="100px" rowspan="2">[[.in_date.]]</th>
        <th class="report_table_header" width="100px" colspan="2">[[.total_in_month.]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.same_period_last_year.]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.compare.]](%) [[|year|]]/[[|last_year|]]</th>
    </tr>
    
    <tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="100px" >[[.item.]]</th>
        <th class="report_table_header" width="100px" >[[.num_guest.]]</th>
    </tr>
    
    <tr>
		<th class="report_table_header main_title" >I. [[.guest_situation.]]</th>
        <th class="report_table_header" ></th>
		<th class="report_table_header" ></th>
        <th class="report_table_header" ></th>
        <th class="report_table_header" ></th>
        <th class="report_table_header" ></th>
        <th class="report_table_header" ></th>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.num_guest.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_MONTH']*100/([[=guest_situation=]]['GUEST_LAST_YEAR']?[[=guest_situation=]]['GUEST_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.num_room.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_IN_MONTH']*100/([[=room_situation=]]['ROOM_LAST_YEAR']?[[=room_situation=]]['ROOM_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.occupancy.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OCCUPANCY_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OCCUPANCY_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OCCUPANCY_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OCCUPANCY_IN_MONTH']*100/([[=room_situation=]]['OCCUPANCY_LAST_YEAR']?[[=room_situation=]]['OCCUPANCY_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.average_price.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['AVERAGE_ROOM_PRICE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['AVERAGE_ROOM_PRICE_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['AVERAGE_ROOM_PRICE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['AVERAGE_ROOM_PRICE_IN_MONTH']*100/([[=room_situation=]]['AVERAGE_ROOM_PRICE_LAST_YEAR']?[[=room_situation=]]['AVERAGE_ROOM_PRICE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.revpar.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['REVPAR_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['REVPAR_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['REVPAR_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['REVPAR_IN_MONTH']*100/([[=room_situation=]]['REVPAR_LAST_YEAR']?[[=room_situation=]]['REVPAR_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
        <th colspan="7">&nbsp;</th>
    </tr>
    
    <tr>
		<th class="report_table_header main_title" >II. [[.revenue.]]</th>
        <th class="report_table_header" align="right"></th>
		<th class="report_table_header" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_REVENUE_IN_DATE']); ?></th>
        <th class="report_table_header" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_REVENUE_IN_MONTH']); ?></th>
        <th class="report_table_header" align="right"></th>
        <th class="report_table_header" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_REVENUE_LAST_YEAR']); ?></th>
        <th class="report_table_header" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_REVENUE_IN_MONTH']*100/([[=summary=]]['TOTAL_REVENUE_LAST_YEAR']?[[=summary=]]['TOTAL_REVENUE_LAST_YEAR']:1)); ?></th>
    </tr>
    
    <tr style="font-weight: bold;">
		<td class="report_table_header sub_title_1" >1. [[.department.]] [[.room.]]</td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_DATE']); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_ROOM_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_ROOM_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_ROOM_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_ROOM_REVENUE_IN_MONTH']*100/([[=summary=]]['TOTAL_ROOM_REVENUE_LAST_YEAR']?[[=summary=]]['TOTAL_ROOM_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.room_revenue.]]</td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_DATE']); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=guest_situation=]]['GUEST_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['ROOM_REVENUE_IN_MONTH']*100/([[=room_situation=]]['ROOM_REVENUE_LAST_YEAR']?[[=room_situation=]]['ROOM_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.minibar.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['MINIBAR_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['MINIBAR_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['MINIBAR_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['MINIBAR_REVENUE_IN_MONTH']*100/([[=room_situation=]]['MINIBAR_REVENUE_LAST_YEAR']?[[=room_situation=]]['MINIBAR_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.laundy.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['LAUNDRY_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['LAUNDRY_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['LAUNDRY_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['LAUNDRY_REVENUE_IN_MONTH']*100/([[=room_situation=]]['LAUNDRY_REVENUE_LAST_YEAR']?[[=room_situation=]]['LAUNDRY_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.phone.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['PHONE_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['PHONE_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['PHONE_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['PHONE_REVENUE_IN_MONTH']*100/([[=room_situation=]]['PHONE_REVENUE_LAST_YEAR']?[[=room_situation=]]['PHONE_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.transport.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['TRANSPORT_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['TRANSPORT_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['TRANSPORT_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['TRANSPORT_REVENUE_IN_MONTH']*100/([[=room_situation=]]['TRANSPORT_REVENUE_LAST_YEAR']?[[=room_situation=]]['TRANSPORT_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
		<td class="report_table_column sub_title_2" > - [[.other.]]</td>
        <td class="report_table_column" ></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OTHER_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OTHER_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" ></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OTHER_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=room_situation=]]['OTHER_REVENUE_IN_MONTH']*100/([[=room_situation=]]['OTHER_REVENUE_LAST_YEAR']?[[=room_situation=]]['OTHER_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <tr>
        <th colspan="7"></th>
    </tr>
    
    <tr style="font-weight: bold;">
		<th class="report_table_header sub_title_1" >2. [[.department.]] [[.restaurant.]]</th>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_BAR_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_BAR_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_BAR_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_BAR_REVENUE_IN_MONTH']*100/([[=summary=]]['TOTAL_BAR_REVENUE_LAST_YEAR']?[[=summary=]]['TOTAL_BAR_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    
    <?php 
    //System::debug($this->map['bar']);
    foreach($this->map['bar'] as $k=>$v)
    {
        echo '<tr style="font-weight: bold;">';
        echo '
            <td class="report_table_column sub_title_2" > - '.$v['name'].'</td>
            <td class="report_table_column" ></td>
    		<td class="report_table_column" align="right">'.System::display_number($v['TOTAL_IN_DATE']).'</td>
            <td class="report_table_column" align="right">'.System::display_number($v['TOTAL_IN_MONTH']).'</td>
            <td class="report_table_column" ></td>
            <td class="report_table_column" align="right">'.System::display_number($v['TOTAL_LAST_YEAR']).'</td>
            <td class="report_table_column" align="right">'.System::display_number($v['TOTAL_IN_MONTH']*100/($v['TOTAL_LAST_YEAR']?$v['TOTAL_LAST_YEAR']:1)).'</td>
        ';
        echo '</tr>';
        
        echo '<tr>';
        echo '
            <td class="report_table_column sub_title_3"> + '.Portal::language('food').'</td>
            <td class="report_table_column" ></td>
    		<td class="report_table_column" align="right">'.System::display_number($v['FOOD']['BAR_REVENUE_IN_DATE']).'</td>
            <td class="report_table_column" align="right">'.System::display_number($v['FOOD']['BAR_REVENUE_IN_MONTH']).'</td>
            <td class="report_table_column" ></td>
            <td class="report_table_column" align="right">'.System::display_number($v['FOOD']['BAR_REVENUE_LAST_YEAR']).'</td>
            <td class="report_table_column" align="right">'.System::display_number($v['FOOD']['BAR_REVENUE_IN_MONTH']*100/($v['FOOD']['BAR_REVENUE_LAST_YEAR']?$v['FOOD']['BAR_REVENUE_LAST_YEAR']:1)).'</td>
        ';
        echo '</tr>';
        
        echo '<tr>';
        echo '
            <td class="report_table_column sub_title_3"> + '.Portal::language('drink').'</td>
            <td class="report_table_column" ></td>
    		<td class="report_table_column" align="right">'.System::display_number($v['DRINK']['BAR_REVENUE_IN_DATE']).'</td>
            <td class="report_table_column" align="right">'.System::display_number($v['DRINK']['BAR_REVENUE_IN_MONTH']).'</td>
            <td class="report_table_column" ></td>
            <td class="report_table_column" align="right">'.System::display_number($v['DRINK']['BAR_REVENUE_LAST_YEAR']).'</td>
            <td class="report_table_column" align="right">'.System::display_number($v['DRINK']['BAR_REVENUE_IN_MONTH']*100/($v['DRINK']['BAR_REVENUE_LAST_YEAR']?$v['DRINK']['BAR_REVENUE_LAST_YEAR']:1)).'</td>
        ';
        echo '</tr>'; 
        echo '<tr>
                <th colspan="7"></th>
            </tr>';   
    }
    
    ?>
    <tr style="font-weight: bold;">
		<th class="report_table_header sub_title_1" >3. [[.department.]] [[.spa.]]</th>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_SPA_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_SPA_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_SPA_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_SPA_REVENUE_IN_MONTH']*100/([[=summary=]]['TOTAL_SPA_REVENUE_LAST_YEAR']?[[=summary=]]['TOTAL_SPA_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    <tr style="font-weight: bold;">
		<th class="report_table_header sub_title_1" >3. [[.department.]] [[.vending.]]</th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
		<td class="report_table_column sub_title_2">&nbsp;- [[.product.]]( [[.product_in_vending.]])</td>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_IN_MONTH']*100/([[=summary=]]['TOTAL_VEND_REVENUE_LAST_YEAR']?[[=summary=]]['TOTAL_VEND_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
    <tr>
		<td class="report_table_column sub_title_2">&nbsp;- [[.drinking.]]( [[.drinking_in_vending.]])</td>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_IN_DATE_DRINK']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_IN_MONTH_DRINK']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_LAST_YEAR_DRINK']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_VEND_REVENUE_IN_MONTH_DRINK']*100/([[=summary=]]['TOTAL_VEND_REVENUE_LAST_YEAR_DRINK']?[[=summary=]]['TOTAL_VEND_REVENUE_LAST_YEAR_DRINK']:1)); ?></td>
    </tr>
    <tr style="font-weight: bold;">
		<th class="report_table_header sub_title_1" >5. [[.department.]] [[.ticket.]]</th>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_TICKET_REVENUE_IN_DATE']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_TICKET_REVENUE_IN_MONTH']); ?></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_TICKET_REVENUE_LAST_YEAR']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['TOTAL_TICKET_REVENUE_IN_MONTH']*100/([[=summary=]]['TOTAL_TICKET_REVENUE_LAST_YEAR']?[[=summary=]]['TOTAL_TICKET_REVENUE_LAST_YEAR']:1)); ?></td>
    </tr>
</table>

<br />
<br />




<br/>


<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td></td>
	<td>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >[[.general_accountant.]]</td>
	<td width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>