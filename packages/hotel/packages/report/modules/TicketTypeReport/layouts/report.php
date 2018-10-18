<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="60%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" >
        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo ' '.User::id();?>
         </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.ticket_type_report.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;">[[.from_date.]]&nbsp;<?php echo [[=from_date=]] ?> - [[.to_date.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
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
                                    <td align="right">[[.from_date.]]</td>
                                	<td align="left"><input name="from_date" type="text" id="from_date"/></td>
                                    <td align="right">[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date"/></td>
                    			    <td align="right">[[.by_ticket.]]</td>
                    			    <td><select name="ticket_id" id="ticket_id"></select></td>
                                    <td align="right">[[.by_area.]]</td>
                    			    <td><select name="area_id" id="area_id"></select></td>
                                    <td><input type="submit" name="do_search" value="[[.report.]]" /></td>
                                </tr>
                                <tr>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td align="right">[[.hotel.]]</td>
                                	<td align="left"><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td align="right">[[.line_per_page.]]</td>
                                    <td align="left"><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td align="right">[[.no_of_page.]]</td>
                                    <td align="left"><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td align="right">[[.from_page.]]</td>
                                    <td align="left"><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
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

<!-------------------REPORT-------------->


<!--IF:check_room([[=real_ticket_count=]])-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
	    <th width="10px" class="report-table-header">[[.type_ticket.]]</th>
        <th width="50px" class="report-table-header">[[.quantity.]]</th>
        <th width="10px" class="report-table-header">[[.foc.]]</th>
        <th width="100px" class="report-table-header">[[.price.]]</th>
        <th width="120px" class="report-table-header">[[.total.]]</th>
        <th width="40px" class="report-table-header" >[[.discount_total.]]</th>
        <th width="60px" class="report-table-header">[[.tax_rate.]]</th>
        <th width="60px"  class="report-table-header">[[.total_before_tax.]]</th>   
	</tr>
    <?php
        $i=1;
        $is_rowspan = false;
        $t_res = false;
    ?>
    <!--LIST:items-->
    <!--IF:cond([[=items.quantity=]] >= 1)-->
    <tr bgcolor="white">
        <?php
            $k = $this->map['count_type'][[[=items.id=]]]['num'];
            if($is_rowspan == false)
            {
        ?>
        <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.name|]]</td>
        <?php 
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
        <td class="report_table_column" align="right">[[|items.quantity|]]</td>
        <td class="report_table_column" align="center"><?php echo([[=items.foc=]]==1?'Y':'N');?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.price=]]);?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=items.total_before_discount=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.total_discount=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number(round([[=items.tax_rate=]],2));?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=items.total_before_tax=]]);?></td>
        <?php
        $i++ ; 
            }
        ?> 
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
    <!--/IF:cond-->
    <!--/LIST:items-->
    <!--Nếu lọc dữ liệu thì ticket_count != real_ticket_count-->
    <!--IF:check(([[=ticket_count=]]!=[[=real_ticket_count=]]) && (([[=real_page_no=]]==[[=real_total_page=]])))-->
	<tr bgcolor="white">
		<td class="report_table_column"><strong>[[.total.]]</strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_quantity=]]); ?></strong></td>
        <td>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_ticket_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_discount_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_tax_rate=]]); ?></strong></td>
        
        <td align="right" class="report_table_column"><strong><?php echo ([[=total_ticket_total_before_tax=]]==0?'':System::display_number([[=total_ticket_total_before_tax=]])); ?></strong></td>
	</tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
    		<td class="report_table_column"><strong>[[.total.]]</strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_quantity=]]); ?></strong></td>
            <td>&nbsp;</td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_ticket_total=]]); ?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_discount_total=]]); ?></strong></td>
            <td align="right" class="report_table_column"><strong>&nbsp;</strong></td>
            <td align="right" colspan="1" class="report_table_column"><strong><?php echo System::display_number([[=total_tax_rate=]]); ?></strong></td>  
            <td align="right" class="report_table_column"><strong><?php echo ([[=total_ticket_total_before_tax=]]==0?'':System::Display_number([[=total_ticket_total_before_tax=]]));?></strong></td>

    	</tr>
    	<!--/IF:end_page-->
    <!--/IF:check-->
</table>
<!---------FOOTER----------->
<center><div style="font-size:11px;">[[.page.]] [[|page_no|]]/[[|total_page|]]</div></center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]) || ([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td  align="center"> <?php echo date('H\h : i\p',time());?>, [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
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
<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_room-->
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
    }
);
</script>