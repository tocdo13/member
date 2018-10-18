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
    			<tr>
                    <td align="left" width="100%" style="font-size:11px;">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
    				
    			</tr>
                <tr>
                    
                    <td>
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" style="font-size:16px;" >[[.guest_report_by_province.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.day.]]&nbsp;[[|date|]]
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
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.date.]]</td>
                                	<td><input name="date" type="text" id="date"/></td>
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
.total{background-color:#FFC; font-weight: bold;}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
    }
);
//style="background-color:#FFC"
</script>

<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" rowspan="2" width="200px">[[.statistic.]]</th>
        <th class="report_table_header" rowspan="2" width="250px">[[.total_occupied.]]</th>
        <th class="report_table_header" colspan="2" width="400px">[[.analysis.]]</th>
        <th class="report_table_header" rowspan="2" width="300px">[[.note.]]</th>
    </tr>
    <tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="200px">[[.old_day.]]</th>
        <th class="report_table_header" width="200px">[[.today_arrival.]]</th>                
    </tr>
    <tr>
        <td>[[.in_HCM.]]</td>
        <td align="center"><?php echo ([[=in_HCM=]]?[[=in_HCM=]]:''); ?></td>
        <td align="center"><?php echo ([[=in_HCM=]]-[[=in_HCM_today=]])?([[=in_HCM=]]-[[=in_HCM_today=]]):''; ?></td>
        <td align="center"><?php echo ([[=in_HCM_today=]]?[[=in_HCM_today=]]:''); ?></td>
        <td></td>
    </tr>
    <tr>
        <td>[[.not_in_HCM.]]</td>
        <td align="center"><?php echo ([[=not_in_HCM=]]?[[=not_in_HCM=]]:''); ?></td>
        <td align="center"><?php echo ([[=not_in_HCM=]]-[[=not_in_HCM_today=]])?([[=not_in_HCM=]]-[[=not_in_HCM_today=]]):''; ?></td>
        <td align="center"><?php echo ([[=not_in_HCM_today=]]?[[=not_in_HCM_today=]]:''); ?></td>
        <td></td>
    </tr>
    <tr class="total">
        <td><strong>[[.total.]]</strong></td>
        <td align="center"><?php echo ([[=total=]]?[[=total=]]:''); ?></td>
        <td align="center"><?php echo ([[=total=]]-[[=total_today=]])?([[=total=]]-[[=total_today=]]):''; ?></td>
        <td align="center"><?php echo ([[=total_today=]]?[[=total_today=]]:''); ?></td>
        <td></td>
    </tr>
</table>


<!---------FOOTER----------->

<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td ></td>
	<td > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >[[.creator.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>