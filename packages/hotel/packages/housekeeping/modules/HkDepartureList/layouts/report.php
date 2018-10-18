<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px;">
                    <td align="left" width="85%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                     <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                    </td>
                 </tr>
                 <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title" >[[.departure_customer_list.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.day.]]&nbsp;[[|day|]]
                            </span> 
                        </div>
                    </td>
                   
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<!---------SEARCH----------->

<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search"> 
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto">
                                <tr>        
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.date.]]</td>
                                	<td><input name="date" type="text" id="date"/></td>
                                    <td>[[.order_by_list.]]</td>
                                    <td><select name="order_by" id="order_by"></select></td>
                                    <td><input type="submit" name="do_search" value="[[.report.]]" /></td>
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
    }
);
</script>
<!--/IF:first_page-->
<!---------REPORT----------->	
<table class="table_border" cellpadding="5" cellspacing="0" width="100%" border="1px">
	<tr>
		<th class="report_table_header" width="20px">[[.stt.]]</th>
		<th class="report_table_header" width="30px">[[.reservation_room_code.]]</th>
        <th class="report_table_header" width="150px">[[.tour.]], [[.company.]]</th>
        <th class="report_table_header" width="200px">[[.note_group.]]</th>
        <th class="report_table_header" width="40px">[[.room.]]</th>
        <th class="report_table_header" width="150px">[[.room_level.]]</th>
        <th class="report_table_header" width="200px">[[.note_guest.]]</th>
		<th class="report_table_header" width="150px">[[.guest_name.]]</th>
		<th class="report_table_header" width="70px">[[.country.]]</th>
		<th class="report_table_header" width="100px">[[.arrival_date.]]</th>
		<th class="report_table_header" width="100px">[[.departure_date.]]</th>
        <th class="report_table_header" width="30px">[[.night.]]</th>
	</tr>
    <!--LIST:items-->
    <tr>
        <td rowspan="[[|items.count_child|]]">[[|items.stt|]]</td>
        <td rowspan="[[|items.count_child|]]"><a href="?page=reservation&cmd=edit&id=[[|items.recode|]]">[[|items.recode|]]</a></td>
        <td rowspan="[[|items.count_child|]]" align="left">[[|items.customer_name|]]</td>
        <td rowspan="[[|items.count_child|]]" align="left">[[|items.reservation_note|]]</td>
        <?php $items_child = ''; ?>
        <!--LIST:items.child-->
            <?php $items_child = [[=items.child.id=]]; ?>
            <?php if([[=items.child.count_child=]]==0){ [[=items.child.count_child=]]=1; } ?>
            <td rowspan="[[|items.child.count_child|]]">[[|items.child.room_name|]]</td>
            <td rowspan="[[|items.child.count_child|]]" align="left">[[|items.child.room_level_name|]]</td>
            <td rowspan="[[|items.child.count_child|]]" align="left">[[|items.child.note|]]</td>
            <?php if(sizeof([[=items.child.child_child=]])==0){ ?>
                <td></td>
                <td></td>
                <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                <td>[[|items.child.night|]]</td>
            <?php }else{ ?>
                <?php $items_child_childchild = '';  ?>
                <!--LIST:items.child.child_child-->
                    <?php $items_child_childchild = [[=items.child.child_child.id=]]; ?>
                    <td align="left">[[|items.child.child_child.fullname|]]</td>
                    <td>[[|items.child.child_child.nationality|]]</td>
                    <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                    <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                    <td>[[|items.child.night|]]</td>
                    <?php break; ?>
                <!--/LIST:items.child.child_child-->
                <!--LIST:items.child.child_child-->
                    <?php if($items_child_childchild != [[=items.child.child_child.id=]]){ ?>
                    <tr>
                        <td align="left">[[|items.child.child_child.fullname|]]</td>
                        <td>[[|items.child.child_child.nationality|]]</td>
                        <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                        <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                        <td>[[|items.child.night|]]</td>
                    </tr>
                    <?php } ?>
                <!--/LIST:items.child.child_child-->
            <?php } ?>
            <?php break; ?>
        <!--/LIST:items.child-->
        </tr>
        <!--LIST:items.child-->
            <?php if($items_child != [[=items.child.id=]]){ ?>
            <tr>
            <?php if([[=items.child.count_child=]]==0){ [[=items.child.count_child=]]=1; } ?>
                <td rowspan="[[|items.child.count_child|]]">[[|items.child.room_name|]]</td>
                <td rowspan="[[|items.child.count_child|]]" align="left">[[|items.child.room_level_name|]]</td>
                <td rowspan="[[|items.child.count_child|]]" align="left">[[|items.child.note|]]</td>
                <?php if(sizeof([[=items.child.child_child=]])==0){ ?>
                    <td></td>
                    <td></td>
                    <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                    <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                    <td>[[|items.child.night|]]</td>
                <?php }else{ ?>
                    <?php $items_child_childchild = '';  ?>
                    <!--LIST:items.child.child_child-->
                        <?php $items_child_childchild = [[=items.child.child_child.id=]]; ?>
                        <td align="left">[[|items.child.child_child.fullname|]]</td>
                        <td>[[|items.child.child_child.nationality|]]</td>
                        <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                        <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                        <td>[[|items.child.night|]]</td>
                        <?php break; ?>
                    <!--/LIST:items.child.child_child-->
                    <!--LIST:items.child.child_child-->
                        <?php if($items_child_childchild != [[=items.child.child_child.id=]]){ ?>
                        <tr>
                            <td align="left">[[|items.child.child_child.fullname|]]</td>
                            <td>[[|items.child.child_child.nationality|]]</td>
                            <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                            <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                            <td>[[|items.child.night|]]</td>
                        </tr>
                        <?php } ?>
                    <!--/LIST:items.child.child_child-->
            <?php } ?>
            <?php } ?>
        <!--/LIST:items.child-->
    </tr>
<!--/LIST:items-->
    <tr>
        <th colspan="4" align="right">[[.total.]]</th>
        <th><?php echo System::display_number([[=total_room=]]); ?></th>
        <th colspan="2"></th>
        <th><?php echo System::display_number([[=total_traveller=]]); ?></th>
        <th colspan="4"></th>
    </tr>
</table>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td >&nbsp;</td>
	<td > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >[[.general_accountant.]]</td>
</tr>
</table>
<br /><br /><br />
<script>full_screen();</script>
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
#printer{
    height: auto;
}
</style>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_day').datepicker();
        jQuery('#to_day').datepicker();
    }
);
</script>
<style>
</style>
