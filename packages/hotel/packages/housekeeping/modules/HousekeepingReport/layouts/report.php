<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
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
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.housekeeping_report.]]<br /></font><br />
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[|from_time|]]&nbsp;[[.date.]]&nbsp;[[|from_date|]]<!--&nbsp;[[.to.]]&nbsp;[[|to_date|]]-->
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
                        <form name="HousekeepingReportForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td style="width:80px;">[[.line_per_page.]]</td>
                                	<td ><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right;width:72px;"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right; width: 100px;"/></td>
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right; width: 80px;"/></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.floor.]]</td>
                                	<td><select name="floor_id" id="floor_id"></select></td>
                                    <td>[[.room.]]</td>
                                	<td><select name="room_id" id="room_id"></select></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  " onclick="return check_search();"/></td>
                                </tr>
                                <tr>    
                                    <td style="width:85px;">[[.HK_status.]]</td>
                                	<td><select name="house_status" id="house_status" style="width:70px;"></select></td>
                                    <td>[[.fo_status.]]</td>
                                	<td><select name="fo_status" id="fo_status"></select></td>
                                    <td>[[.date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;" /></td>
                                    <!--<td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();" /></td>-->
                                    <td>[[.time.]]</td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 85px;"/></td>
                                    <!--<td>[[.to_time.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>-->
                                   
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

<!--/IF:first_page-->
<!---------REPORT----------->	
<!--IF:check_data(isset([[=items=]]))-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px">[[.room.]]</th>
        <th class="report_table_header" width="200px">[[.guest_name.]]</th>
        <th class="report_table_header" width="100px">[[.nationality.]]</th>
        <th class="report_table_header" width="100px">[[.HK_status.]]</th>
        <th class="report_table_header" width="100px">[[.FO_status.]]</th>
        <th class="report_table_header" width="100px">[[.arrival_date.]]</th>                        
        <th class="report_table_header" width="100px">[[.departure_date.]]</th>
        <th class="report_table_header" width="200px">[[.special_request.]]</th>
    </tr>   
    <!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <!--<tr>
        <td colspan="4" class="report_sub_title" align="center"><b>[[.last_page_summary.]]</b></td>
		<td align="center" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['total_before_tax']);?></strong></td>
        <td align="center" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['total']);?></strong></td>
        
	</tr>-->
	<!--/IF:first_page-->
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
	<!--LIST:items-->
    <tr bgcolor="white">
        <?php
            $k = $this->map['count_traveller'][[[=items.code=]]]['num'];
            if($is_rowspan == false)
            {
        ?>
		<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.room_name|]]</td>
        <?php
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
        <td align="center" class="report_table_column" >[[|items.guest_name|]]</td>
        <td align="center" class="report_table_column" >[[|items.nationality|]]</td>
        <td align="center" class="report_table_column" >[[|items.hk_status|]]</td>
        <td align="center" class="report_table_column" >[[|items.fo_status|]]</td>
        <td align="center" class="report_table_column" ><?php echo([[=items.time_in=]]>0?(Date('d/m/Y',[[=items.time_in=]])):'') ?></td>
        <td align="center" class="report_table_column" ><?php echo([[=items.time_out=]]>0?(Date('d/m/Y',[[=items.time_out=]])):'') ?></td>
	    <td align="center" class="report_table_column" >[[|items.special_request|]]</td>
        <?php
           $i++ ;}
        ?>
        <?php
            if($is_rowspan == false)
            {
                $is_rowspan = true;
            } 
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>  
    </tr>
	<!--/LIST:items-->
    <!--<tr>
        <td align="center" colspan="4" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_before_tax']);?></strong></td>
        <td align="center" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total']);?></strong></td>
    </tr>-->
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>

<!--IF:end_page(([[=real_page_no=]]==[[=real_total_page=]]))-->
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
<!--/IF:end_page-->

<div style="page-break-before:always;page-break-after:always;"></div>

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_data-->
<script>
    jQuery(document).ready(function(){
        jQuery("#from_date").datepicker();
    	jQuery("#to_date").datepicker();
    });
    full_screen();
    function changevalue(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('to_date').value =$('from_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('to_date').value =$('from_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('to_date').value =$('from_date').value;
                }
            }
        }
    }
    function changefromday(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('from_date').value= $('to_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('from_date').value = $('to_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('from_date').value =$('to_date').value;
                }
            }
        }
    }
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));  
        if(to_numeric(hour_from[0]) >23 ||  to_numeric(hour_from[1]) >59)
        {
            alert('[[.the_max_time_is_2359_try_again.]]');
            return false;
        }
        else
        { 
            return true;       
        }   
    }
    //end:KID them ham check dieu kien search
</script>