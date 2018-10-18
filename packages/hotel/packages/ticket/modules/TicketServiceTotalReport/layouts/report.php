<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong> </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.ticket_service_total_report.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php //echo [[=from_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=from_date=]] ?> - <?php //echo [[=to_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page([[=real_page_no=]]==1)-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
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
                            <table style="margin: 0 auto" align="center">
                                <tr>        
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td align="right">[[.chose_hotel.]]</td>
                                	<td align="left"><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td align="right">[[.from_date.]]</td>
                                	<td align="left"><input name="from_date" type="text" id="from_date"/></td>
                                    <td align="right">[[.to_date.]]</td>
                                	<td align="left"><input name="to_date" type="text" id="to_date"/></td>
                    			    <td align="right">[[.by_service.]]</td>
                    			    <td align="left"><select name="ticket_service_id" id="ticket_service_id"></select></td>
                                    <!--<td>[[.from_time.]]</td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 50px;"/></td>
                                    <td>[[.to_time.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>-->
                                    
                                </tr>
                                <tr>
                                	<td align="right">[[.line_per_page.]]</td>
                                    <td align="left"><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td align="right">[[.no_of_page.]]</td>
                                    <td align="left"><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td align="right">[[.from_page.]]</td>
                                    <td align="left"><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
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


<!--/IF:first_page-->


<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
	    <!--<th width="10px" rowspan="2" class="report-table-header">[[.count.]]</th>-->
        <!--<th width="100px" rowspan="1" class="report-table-header">[[.ticket_service.]]</th>-->
        <th width="100px" rowspan="1" class="report-table-header">[[.ticket_name.]]</th>
        <th width="20px" rowspan="1" class="report-table-header">[[.quantity.]]</th>
        <th width="10px" rowspan="1" class="report-table-header" >[[.foc.]]</th>  
        <th width="100px" rowspan="1" class="report-table-header">[[.price.]]</th>
        <th width="120px" rowspan="1" class="report-table-header">[[.total_amount.]]</th>
        <th width="120px" rowspan="1" class="report-table-header">[[.discount.]]</th>
     <!--   <th width="120px" rowspan="1" class="report-table-header">[[.total_after_tax.]]</th> !-->
        <th width="120px" rowspan="1" class="report-table-header">[[.total_tax_fee.]]</th>
        <th width="12px" rowspan="1" class="report-table-header">[[.net_amount.]]</th>
	</tr>
    <?php $service = '';?>	
    <!--LIST:items-->
    <?php if($service != [[=items.name=]]){$service=[[=items.name=]];  ?>
	<tr style="background: #f5f5f5; font-weight: bold; font-size: 16px; text-transform: uppercase;">
        <td align="left">[[|items.name|]]</td>
        <!--LIST:total_items-->
         <?php if([[=items.name=]] == [[=total_items.id=]]){?> 
            <td align="right" >[[|total_items.total_quantity|]]</td>
            <td align="center" >&nbsp;</td>
            <td align="right" >&nbsp;</td>
            <td align="right" ><?php echo number_format([[=total_items.total_total_amount=]])?></td>
            <td align="right" ><?php echo number_format([[=total_items.total_total_discount=]])?></td>
          <!--  <td align="right" ><?php echo number_format([[=total_items.total_total_after_tax=]])?></td> !-->
            <td align="right" ><?php echo number_format([[=total_items.total_total_tax=]])?></td>
            <td align="right" ><?php echo number_format([[=total_items.total_net_amount=]])?></td>
         <?php } ?>
        <!--/LIST:total_items-->
	</tr>
	<?php }?>
     <tr bgcolor="white" id="ticket_tr_[[|items.id|]]">
		<!--<td align="left" class="report_table_column" rowspan="[[|items.row_num|]]">[[|items.name|]]</td>-->
        <td align="left" class="report_table_column" rowspan="[[|items.row_num|]]">[[|items.ticket_name|]]</td>
        <td align="right" class="report_table_column">[[|items.quantity|]]</td>
        <td align="center" class="report_table_column">N</td>
        <td align="right" class="report_table_column">[[|items.price|]]</td>
        <td align="right" class="report_table_column">[[|items.total_amount|]]</td>
        <td align="right" class="report_table_column">[[|items.total_discount|]]</td>
      <!--  <td align="right" class="report_table_column">[[|items.total_after_tax|]]</td> !-->
        <td align="right" class="report_table_column">[[|items.total_tax|]]</td>
        <td align="right" class="report_table_column">[[|items.net_amount|]]</td>
	</tr>
    <!--IF:cond([[=items.row_num=]] > 1)-->
    <tr bgcolor="white" id="ticket_tr_[[|items.id|]]">
        <td align="right" class="report_table_column">[[|items.discount_quantity|]]</td>
        <td align="center" class="report_table_column">Y</td>
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column">0</td>
      <!--  <td align="right" class="report_table_column">0</td> !-->
        <td align="right" class="report_table_column">0</td>
        <td align="right" class="report_table_column">0</td>
    </tr>
    <!--/IF:cond-->
	<!--/LIST:items-->
    <!--Nếu lọc dữ liệu thì ticket_count != real_ticket_count-->
	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
	<tr bgcolor="white" style="background: #f5f5f5; font-weight: bold; font-size: 16px; text-transform: uppercase;">
		<td colspan="1" ><strong>[[.total.]]</strong></td>
        <td align="right" ><strong><?php echo number_format([[=total_quantity=]]); ?></strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right"><strong><?php echo number_format([[=total_amount=]]); ?></strong></td>
        <td align="right" ><strong><?php echo number_format([[=total_discount=]]); ?></strong></td>
      <!--  <td align="right" ><strong><?php echo number_format([[=TOTAL_total_after_tax=]]); ?></strong></td> -->
        <td align="right" ><strong><?php echo number_format([[=total_tax=]]); ?></strong></td>
        <td align="right" ><strong><?php echo number_format([[=total_net_amount=]]); ?></strong></td>
	</tr>
	<!--/IF:end_page-->
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">[[.page.]] [[|page_no|]]/[[|total_page|]]</div></center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]) || ([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td  align="center"> <?php echo date('H\h : i\p',time());?> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td align="center" width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>
<!--IF:no_data([[=total_page=]] == 0)-->
<strong>[[.no_data.]]</strong>
<!--/IF:no_data-->
<style type="text/css">
th,td{white-space:nowrap;}
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
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        jQuery('#to_day').datepicker();
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
    }
);
</script>
