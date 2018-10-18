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
                            <font class="report_title specific" >[[.ticket_list_cancel.]]<br /></font>
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
                                    
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.user.]]</td>
                                	<td><select name="user_id" id="user_id"></select></td>
                                    <?php }?>
                                    <td>[[.ticket.]]</td>
                                	<td><select name="ticket_id" id="ticket_id"></select></td>
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;"/></td>
                                    <td>[[.to.]]</td>	<td><input name="to_date" type="text" id="to_date" style="width: 80px;"/></td>
                                    <td>[[.from_time.]]</td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 50px;"/></td>
                                    <td>[[.to_time.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>
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
<div> 
<table  cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        
        <th class="report_table_header" width="30px">[[.invoice_code.]]</th>
        <th class="report_table_header" width="200px">[[.type_ticket.]]</th>
         <th class="report_table_header" width="110px">[[.serie.]]</th>
        <th class="report_table_header" width="150px">[[.Date.]]</th>
        <th class="report_table_header" width="300px">[[.reason_for_cancellation.]]</th>
        <th class="report_table_header" width="100px">[[.user.]]</th>
    </tr>
    
    
    <!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	        
  
	<!--/IF:first_page-->
    
    <?php 
	$i=0;
	$total_amount = 0;
	?>
  
    <tr>
     <?php 
    $i=1;
    $is_rowspan = false;
    ?>
         
        <!--LIST:items-->
        
         <tr bgcolor="white">
        
        <?php
            $k = $this->map['count_ticket_cancelation'][[[=items.ticket_reservation=]]]['num'];
            
            if($is_rowspan == false)
            {
        ?>
                
                <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
                <a href="<?php echo Url::build('ticket_invoice_group',array('cmd'=>'edit','id'=>[[=items.ticket_reservation=]]));?>" target="_blank">[[|items.ticket_reservation|]]</a>
                </td>
         <?php
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?> 
       
                <td align="left" class="report_table_column">[[|items.ticket_name|]]</td>
                
                <td align="center" class="report_table_column">[[|items.ticket_serie|]]</td>
                
                <td align="right" class="report_table_column">	<?php echo date('d/m/Y H:i',[[=items.time=]]);?></td>
                <td align="center" class="report_table_column">[[|items.note|]]</td>
                <td align="right" class="report_table_column">[[|items.user_id|]]</td>
             
        <?php   $i++; } ?>
        <?php 
            if($is_rowspan == false)
            {
        ?>
        <?php
            $is_rowspan = true;
            } 
        ?>

        <?php
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>
                
 	     </tr>
    	<!--/LIST:items-->
         <tr>
            
            <td align="right" colspan="2" class="report_sub_title" ><b>Số lượng vé hủy:</b></td>        
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_quantity']);?></strong></td>
            <td align="right" class="report_sub_title" align="right">&nbsp;</td>
            <td align="right" class="report_table_column"><strong>&nbsp;</strong></td>
            <td colspan="3" class="report_sub_title" align="right">&nbsp;</td>
        </tr>
    </tr>
  <!--/LIST:items-->
</table>
</div>

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