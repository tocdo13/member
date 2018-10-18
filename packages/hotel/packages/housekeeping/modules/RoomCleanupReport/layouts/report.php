<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page([[=page_no=]]<=1)-->
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
                        <strong>[[.template_code.]]</strong>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.room_cleanup_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.date.]]&nbsp;[[|date|]]&nbsp;
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
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.user.]]</td>
                                	<td><select name="user_id" id="user_id"></select></td>
                                    <?php }?>
                                    <td>[[.room.]]</td>
                                	<td><select name="room_id" id="room_id"></select></td>
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
        jQuery("#date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    }
);
</script>
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px" rowspan="2">[[.stt.]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.room.]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.total.]]</th>
        <th class="report_table_header" width="100px" colspan="5">[[.cleanup.]]</th>
    </tr>
    <tr>
        <th class="report_table_header" width="100px">[[.status.]]</th>
        <th class="report_table_header" width="100px">[[.start_time.]]</th>
        <th class="report_table_header" width="100px">[[.end_time.]]</th>
        <th class="report_table_header" width="100px">[[.user.]]</th>
        <th class="report_table_header" width="200px">[[.note.]]</th>
    </tr>
    
    
    
	<!--LIST:items-->
    <tr bgcolor="white">
        <td align="center" class="report_table_column"  rowspan="[[|items.total_clean|]]">[[|items.stt|]]</td>
        <td align="center" class="report_table_column"  rowspan="[[|items.total_clean|]]">[[|items.room_name|]]</td>
        <td align="center" class="report_table_column"  rowspan="[[|items.total_clean|]]"><?php echo [[=items.total_clean=]] -1 ; ?></td>
    </tr>
        <!--LIST:items.cleanup-->
        <tr>
            <td align="left" class="report_table_column">[[|items.cleanup.status|]]</td>
            <td align="left" class="report_table_column">
                <?php echo [[=items.cleanup.start_time=]]? date('H\h:i d/m/Y',[[=items.cleanup.start_time=]]):'' ?>
            </td>
            <td align="left" class="report_table_column">
                <?php echo [[=items.cleanup.end_time=]]? date('H\h:i d/m/Y',[[=items.cleanup.end_time=]]):'' ?>
            </td>
            <td align="left" class="report_table_column">[[|items.cleanup.user_id|]]</td>
            <td align="left" class="report_table_column">[[|items.cleanup.note|]]</td>
        </tr>
        <!--/LIST:items.cleanup-->
	
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
<!--/IF:check_data-->