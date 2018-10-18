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
                    <td align="left" width="60%">
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
                            <font class="report_title specific" >[[.service_revenue_report.]]<br /></font>
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
                                    <!--<td>|</td>
                                    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php //}?>
                                    -->
                                    <td>[[.service.]]</td>
                                	<td ><select name="product_id" id="product_id" style="width: 150px;"></select></td>
                                    
                                    <td>[[.customer.]]</td>
                                	<td><select name="guest_id" id="guest_id" style="width: 150px;"></select></td>
                                    <!--
                                    <td>[[.staff.]]</td>
                                	<td><select name="staff_id" id="staff_id"></select></td>
                                    -->
                                    <td>[[.from.]]<input name="from_hours" id="from_hours" style="width:50px;height:20px;" onblur="validate_time(this,this.value);"/></td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;" onchange="changevalue();"/></td>
                                    <td>[[.to.]]<input name="to_hours" id="to_hours" style="width:50px;height:20px;" onblur="validate_time(this,this.value);"/></td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();" /></td>
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
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:12px; border-collapse:collapse;">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="10px">[[.stt.]]</th>
        <th class="report_table_header" width="70px">[[.code.]]</th>
        <th class="report_table_header" width="300px">[[.product_service.]]</th>
        <th class="report_table_header" width="70px">[[.quantity.]]</th>
        <th class="report_table_header" width="150px">[[.total_amount.]]</th>
    </tr>
    
    
    <!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="3" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['total_amount']);?></strong></td>
	</tr>
	<!--/IF:first_page-->
    
    <?php 
	$i=0;
	$total_amount = 0;
	?>
	<!--LIST:items-->
    <tr bgcolor="white">
		<td align="center" class="report_table_column">[[|items.stt|]]</td>
        <td align="left" class="report_table_column">[[|items.code|]]</td>
        <td align="left" class="report_table_column">[[|items.name|]]</td>
        <td align="center" class="report_table_column">[[|items.quantity|]]</td>
        <td align="right" class="report_table_column">[[|items.total_amount|]]</td>
	</tr>
	<!--/LIST:items-->
    <tr>
        <td align="right" colspan="4" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_amount']);?></strong></td>
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
<script>
    var from_hours = '[[|from_hours|]]';
    var to_hours = '[[|to_hours|]]';
    jQuery('#from_hours').val(from_hours);
    jQuery('#to_hours').val(to_hours);
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
    function validate_time(obj,value)
    {
        if(value != "__:__")
        {
            var arr = value.split(":")
            var h = arr[0];
            var m = arr[1];
            if(is_numeric(h.toString()))
            {
                if(h>23)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }
            if(is_numeric(m.toString()))
            {
                if(m>59)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }  
        }
    }
</script>