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
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.compensation_report.]]<br /></font>
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
<form  method="post"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]" style="width:50px" /></div></form>
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
                                	<td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <!--
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    -->
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.room_status.]]</td>
                                	<td><select name="room_status" id="room_status"></select></td>
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;" onchange="changevalue();" /></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();" /></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                </tr>
                            </table>
                                    <script>
                                        function check_validate_time(){
                                            vdate_from = jQuery('#from_date').val();
                                            vdate_to = jQuery('#to_date').val();
                                            if(vdate_to<vdate_from){
                                            alert("Date To must be greater Date From");
                                            jQuery('#to_date').css('border','1px solid red');
                                            jQuery('#to_date').val(vdate_from);
                                            
                                            return false;
                                            }
                                        }
                                    </script>
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
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" id="export">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="100px">[[.stt.]]</th>
        <th class="report_table_header" width="100px">[[.invoice.]]</th>
        <th class="report_table_header" width="100px">[[.room.]]</th>
        <th class="report_table_header" width="100px">[[.room_status.]]</th>
        <th class="report_table_header" width="150px">[[.total_before_tax.]]</th>
        <th class="report_table_header" width="150px">[[.tax.]]</th>                        
        <th class="report_table_header" width="150px">[[.total.]]</th>
    </tr>
    
    
    <!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
		<td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number([[=last_group_function_params=]]['total_before_tax']);?></strong></td>
        <td align="right" class="report_table_column">&nbsp;</td>
        <td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number([[=last_group_function_params=]]['total']);?></strong></td>
        
	</tr>
	<!--/IF:first_page-->
    
    
	<!--LIST:items-->
    <tr bgcolor="white">
		<td align="center" class="report_table_column">[[|items.row_num|]]</td>
        <td align="center" class="report_table_column" width="100">
            <a style="color: #039;" href="<?php echo Url::build('equipment_invoice&cmd=edit',array('id'=>[[=items.id=]],));?>" target="_blank">EQ_[[|items.position|]]</a>
        </td>
        <td align="center" class="report_table_column " >[[|items.room_name|]]</td>
        <td align="center" class="report_table_column" >[[|items.reservation_room_status|]]</td>
        <td align="right" class="report_table_column change_numTr" ><?php echo System::display_number([[=items.total_before_tax=]]); ?></td>
        <td align="center" class="report_table_column change_numTr" ><?php echo System::display_number([[=items.tax_rate=]]); ?>%</td>
        <td align="right" class="report_table_column change_numTr" ><?php echo System::display_number([[=items.total=]]); ?></td>
	</tr>
	<!--/LIST:items-->
    
    <tr>
        <td align="right" colspan="4" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number([[=group_function_params=]]['total_before_tax']);?></strong></td>
        <td align="right" class="report_table_column">&nbsp;</td>
        <td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number([[=group_function_params=]]['total']);?></strong></td>
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
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>