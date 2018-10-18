<!---------HEADER----------->
<!--IF:first_page([[=page_no=]]==[[=start_page=]] or [[=page_no=]] == 0 )-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
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
                        <strong>[[.housekeeping.]]</strong> 
                        
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.guest_status_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.date.]]&nbsp;[[|date|]]
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
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                    <td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.date.]]</td>
                                	<td><input name="date" type="text" id="date" style="width: 80px;"/></td>
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
        jQuery("#date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    }
);
</script>
<!--/IF:first_page-->



<!--IF:check_data(!isset([[=has_no_data=]]))-->

<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="60px" class="report_table_header">[[.room.]]</th>
		<th width="100px" class="report_table_header">[[.room_level.]]</th>
		<th width="80px" class="report_table_header">[[.status.]]</th>
		<th width="150px" class="report_table_header">[[.guest_name.]]</th>
		<th width="60px" class="report_table_header">[[.gender.]]</th>
		<th align="center" width="100px" class="report_table_header">[[.country.]]</th>
		<th width="100px" class="report_table_header">[[.arrival_time.]]</th>
		<th width="100px" class="report_table_header">[[.departure_time.]]</th>
        <th width="100px" class="report_table_header">[[.note1.]]</th>
	</tr>
	<!--LIST:items-->
	<tr bgcolor="white">
		<td align="center"  >[[|items.room_name|]]</td>
		<td align="center" >[[|items.room_level_name|]]</td>
		<td align="center" >[[|items.status|]]</td>
        <td align="left">[[|items.fullname|]]</td>	
	    <td align="center">[[|items.gender|]]</td>
		<td align="center">[[|items.country_name|]]</td>
        <td align="center" ><?php echo [[=items.time_in=]]?date('h\h:i - d/m/y',[[=items.time_in=]]):'';?></td>
		<td align="center" ><?php echo  [[=items.time_out=]]?date('h\h:i - d/m/y',[[=items.time_out=]]):'';?></td>
        <td align="center" ><?php echo  [[=items.change_room_from_rr=]]?'Đổi từ phòng '.[[=items.change_room_from_rr=]]:'';?></td>        
    </tr>
    <!--/LIST:items-->
</table>

<!---------FOOTER----------->
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.creator.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<!---<div style="page-break-before:always;page-break-after:always;"></div>--->

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_data-->