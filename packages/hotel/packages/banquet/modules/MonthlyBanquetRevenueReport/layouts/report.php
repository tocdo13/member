<style>
/*full m�n h�nh*/
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
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.statisfy_guest_and_revenue.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
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
                                    <!--
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    -->
                                    <td>|</td>
                                    <td>[[.status.]]</td>
                                	<td><select name="status" id="status"></select></td>
                                    <td>[[.party_type.]]</td>
                                	<td><select name="party_type_id" id="party_type_id"></select></td>
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue();" style="width: 80px;"/></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday();" style="width: 80px;"/></td>
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
        jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px" rowspan="2">[[.stt.]]</th>
        <th class="report_table_header" width="250px" rowspan="2">[[.place.]]</th>
        <th class="report_table_header" width="250px" colspan="2">[[.num_party.]]</th>
        <th class="report_table_header" width="250px" colspan="2">[[.num_table.]]</th>
        <th class="report_table_header" width="250px" colspan="2">[[.num_people.]]</th>
        <th class="report_table_header" width="250px" colspan="4">[[.meeting_revenue.]]</th>
        <th class="report_table_header" width="250px" colspan="4">[[.Party_revenue.]]</th>
    </tr>
    <tr>
        <th class="report_table_header" width="100px">[[.meeting.]]</th>
        <th class="report_table_header" width="100px">[[.party.]]</th>
        <th class="report_table_header" width="100px">[[.meeting.]]</th>
        <th class="report_table_header" width="150px">[[.party.]]</th>
        <th class="report_table_header" width="100px">[[.meeting.]]</th>
        <th class="report_table_header" width="300px">[[.party.]]</th>
        <th class="report_table_header" width="300px">[[.eating.]]</th>
        <th class="report_table_header" width="300px">[[.drinking.]]</th>
        <th class="report_table_header" width="300px">[[.other.]]</th>
        <th class="report_table_header" width="300px">[[.total.]]</th>
        <th class="report_table_header" width="300px">[[.eating.]]</th>
        <th class="report_table_header" width="300px">[[.drinking.]]</th>
        <th class="report_table_header" width="300px">[[.other.]]</th>
        <th class="report_table_header" width="300px">[[.total.]]</th>
    </tr>
    
    
    <!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->
    <tr>
        <td align="right" colspan="5" class="report_sub_title"><b>[[.last_page_summary.]]</b></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['total_num_meeting']);?></strong></td>
        <td colspan="2" class="report_sub_title" align="right">&nbsp;</td>
	</tr>
	<!--/IF:first_page-->
    
    <?php 
	$i=0;
	$total_amount = 0;
	?>
	<!--LIST:items-->
    <tr bgcolor="white">
        <td align="center" class="report_table_column">[[|items.id|]]</td>
        <td align="left" class="report_table_column">[[|items.name|]]</td>
        <td align="center" class="report_table_column"><?php echo System::display_number([[=items.num_meeting=]]);?></td>
        <td align="center" class="report_table_column"><?php echo System::display_number([[=items.num_party=]]);?></td>
        <td align="center" class="report_table_column"></td>
        <td align="center" class="report_table_column"><?php echo System::display_number([[=items.num_party_table=]]);?></td>
        <td align="center" class="report_table_column"><?php echo System::display_number([[=items.meeting_num_people=]]);?></td>
        <td align="center" class="report_table_column"><?php echo System::display_number([[=items.num_people=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.meeting_eating=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.meeting_drinking=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.meeting_service=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.meeting_eating=]]+[[=items.meeting_drinking=]]+[[=items.meeting_service=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.eating=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.drinking=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.service=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.eating=]]+[[=items.drinking=]]+[[=items.service=]]);?></td>
	</tr>
	<!--/LIST:items-->
    <tr>
        
        <td align="right" colspan="2" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_num_meeting']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_num_party']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_num_meeting_table']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_num_party_table']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_meeting_num_people']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_num_people']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_meeting_eating']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_meeting_drinking']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_meeting_service']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_meeting']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_eating']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_drinking']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_service']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_party']);?></strong></td>
    </tr>

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
<script>
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
</script>