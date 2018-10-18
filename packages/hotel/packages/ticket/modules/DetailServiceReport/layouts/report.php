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
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.detail_service_report.]]<br /></font>
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
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.user.]]</td>
                                	<td><select name="user_id" id="user_id"></select></td>
                                    <?php }?>
                                    <td>[[.service.]]</td>
                                	<td><select name="ticket_service_id" id="ticket_service_id"></select></td>
                                    <!--
                                    <td>[[.ticket.]]</td>
                                	<td><select name="ticket_id" id="ticket_id"></select></td>
                                    <td>[[.ticket_area.]]</td>
                                	<td><select name="ticket_area_id" id="ticket_area_id"></select></td>
                                    -->
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;"/></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;"/></td>
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
        jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="20px">[[.stt.]]</th>
        <th class="report_table_header" width="100px">[[.date.]]</th>
        <th class="report_table_header" width="150px">[[.service_name.]]</th>
        <th class="report_table_header" width="100px">[[.foc.]]</th>
        <th class="report_table_header" width="50px">[[.quantity.]]</th>
        <th class="report_table_header" width="100px">[[.price.]]</th>
        <th class="report_table_header" width="100px">[[.total_amount.]]</th>
        <th class="report_table_header" width="80px">[[.discount_money.]]</th>
        <th class="report_table_header" width="50px">[[.discount_percent.]]</th>
        <th class="report_table_header" width="100px">[[.balance.]]</th>
        <th class="report_table_header" width="100px">[[.Tax.]]</th>
        <th class="report_table_header" width="100px">[[.net_amount.]]</th>
        
        
    </tr>
    
    
    <!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td align="right" colspan="7" class="report_sub_title"><b>[[.last_page_summary.]]</b></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['total_amount']);?></strong></td>        
        <td colspan="2" class="report_sub_title" align="right">&nbsp;</td>
	</tr>
	<!--/IF:first_page-->
    
    <?php 
	$i=0;
	$total_amount = 0;
	?>
	<!--LIST:items-->
    <tr bgcolor="white">
        <td align="center" class="report_table_column" rowspan="[[|items.row_span|]]">[[|items.stt|]]</td>
        <td align="center" class="report_table_column" rowspan="[[|items.row_span|]]">[[|items.create_date|]]</td>
        <td align="left" class="report_table_column" rowspan="[[|items.row_span|]]">[[|items.service_name|]]</td>
        <td class="report_table_column" width="100px"  >N</td>
        <td align="right" class="report_table_column">[[|items.quantity|]]</td>
        <td align="right" class="report_table_column">[[|items.price|]]</td>
        <td align="right" class="report_table_column">[[|items.total|]]</td>
        <td align="right" class="report_table_column">[[|items.discount_money|]]</td>
        <td align="right" class="report_table_column"  >[[|items.discount_percent|]]</td>
        <?php //echo([[=items.foc=]]==1?'Y':'N');?>
        <td align="right" class="report_table_column">[[|items.balance|]]</td>
        <td align="right" class="report_table_column">[[|items.tax|]]</td>
         <td align="right" class="report_table_column">[[|items.net_amount|]]</td>
        
	</tr>
    <!--IF:cond([[=items.row_span=]] > 1)-->
    <tr>
        <td class="report_table_column" width="100px">Y</td>
        <td align="right" class="report_table_column">[[|items.discount_quantity|]]</td>
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column"  >0</td>
        <?php //echo([[=items.foc=]]==1?'Y':'N');?>
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column">0</td>
         <td align="right" class="report_table_column">0</td>
    </tr>
    <!--/IF:cond-->
	<!--/LIST:items-->
    <tr>
        
        <td align="center" colspan="6" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_amount']);?></strong></td>
        <td colspan="3" class="report_sub_title" align="right">&nbsp;</td>
        <td class="report_sub_title" align="right"><strong><?php echo System::display_number([[=group_function_params=]]['total_balance']);?></strong></td>
        <td class="report_sub_title" align="right"><strong><?php echo System::display_number([[=group_function_params=]]['total_tax']);?></strong></td>
        <td class="report_sub_title" align="right"><strong><?php echo System::display_number([[=group_function_params=]]['total_net_amount']);?></strong></td>
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