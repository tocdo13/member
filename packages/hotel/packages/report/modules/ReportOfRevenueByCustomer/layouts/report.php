<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong>
        <br />
        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo ' '.User::id();?>
         </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.report_of_revenue_by_customer.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;">&nbsp;[[.from_date.]]&nbsp;<?php echo [[=from_date=]] ?> [[.to_Date.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<style>
/*full m�n h�nh*/
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
                            <table style="margin: 0 auto">
                                <tr>        
                                    <td>[[.line_per_page.]]</td>
                                    <td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                    <td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.area.]]</td>
                                    <td><select name="area_id" id="area_id"></select></td>
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue();"/></td>
                                    <td>[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday();"/></td>
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
<?php $i=0; ?>
<!--LIST:payment_types--><?php $i++; ?><!--/LIST:payment_types-->
<!--IF:check_room([[=real_customer_count=]])-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:14px;">
	<tr bgcolor="#EFEFEF">
	       <th rowspan="2" >[[.group.]]</th>
            <th colspan="2" >[[.customer.]]</th>
            <th colspan="2" >[[.products.]]</th>
            <th rowspan="2">[[.unit.]]</th>
            <th rowspan="2" >[[.quantity.]]</th>
            <th rowspan="2" >[[.FOC.]]</th>
            <th rowspan="2" >[[.total_revenue.]]</th>
            <th rowspan="2" >[[.VAT.]](10%)</th>
            <th rowspan="2" >[[.net_revenue.]]</th>
            <th rowspan="2">[[.total_by_customer.]]</th>   
           
	</tr>
    <tr valign="middle" bgcolor="#EFEFEF">
        
            <th>[[.code.]]</th>
            <th>[[.customer_name.]]</th>
            <th>[[.code.]]</th>
            <th>[[.product_name.]]</th>
            
        </tr>
    <tr>
    <?php
    $i=1;
    $j=1;
    $is_rowspan = false;
    $is_rowspan_n = false;
    ?>
    <!--LIST:items-->
	<tr bgcolor="white">
        <?php
            $k = $this->map['count_product_by_group'][[[=items.group_id=]]]['num_product_by_group'];
            $n = $this->map['count_product_by_customer'][[[=items.customer_id=]]]['num_product_by_customer'];
            if($is_rowspan == false)
            {
                //echo //$k;
        ?>       
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.group_name|]]</td>
         <?php
            } 
        ?>
        <?php
            if($is_rowspan_n == false)
            {
                echo $n;
        ?>       
            <td align="right" class="report_table_column" rowspan="<?php echo $n; ?>" >[[|items.customer_code|]]</td> 
            <td class="report_table_column" style="text-align: left;" rowspan="<?php echo $n; ?>" >[[|items.customer_name|]]</td>
         <?php
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
        <td class="report_table_column" style="text-align: left;" align="right">[[|items.product_id|]] </td>
        <td class="report_table_column" style="text-align: left;">[[|items.product_name|]] </td>
        <td class="report_table_column" style="text-align: center;">[[|items.name_1|]] </td>        
        <td  class="report_table_column" align="right" >[[|items.quantity|]]</td>
        <?php if([[=items.foc=]]==1){?>
		  <td align="center" class="report_table_column">Y</td>
        <?php }else{ ?>
            <td align="center" class="report_table_column">N</td>
        <?php } ?>
        <td align="right"class="report_table_column"><?php echo System::display_number([[=items.total_before_tax=]]);?></td>
        <td  align="right" class="report_table_column"><?php echo System::display_number([[=items.vat=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.total_revenue=]]);?></td>

        <?php
          
           $i++ ;
           $j++ ;
           }
        ?>
        
        <?php 
            if($is_rowspan_n == false)
            {
        ?>
            <td align="right" class="report_table_column" rowspan="<?php echo $n; ?>"><?php echo System::display_number([[=items.total_customer=]]);?></td>
        
        <?php
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
            if($is_rowspan_n == false)
            {
        ?>
        <?php
                $is_rowspan_n = true;
            } 
        ?>
        <?php
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>
        <?php
            if($n ==0 || $n ==1 || $j>$n)
            {
                $j = 1;
                $is_rowspan_n = false;
            } 
        ?>
	</tr>

	<!--/LIST:items-->   

</table>
        <br />
        <br />
        <table align="right" cellpadding="5" cellspacing="0" width="20%" border="2" bordercolor="#CCCCCC"  style="font-size:11px;"  >
        <tr>
            <th style="font-size:14px;">[[.group.]]</th>
            <th style="font-size:14px;">[[.total.]]</th>
        </tr>
        <!--LIST:grand_total-->
            <tr>
                <td>[[|grand_total.name|]]</td>
                <td align="right" ><?php echo System::display_number([[=grand_total.total_gr=]]) ?></td>
            </tr>
        <!--/LIST:grand_total-->    
        </table>


<table width ="100%">
    <tr>
        <td><div style=" height: 400px; width: 100%; " id="pie_char_group"></div></td>
    </tr>
    <tr>
        <td><div style=" height: 400px; width: 100%; " id="pie_char_customer"></div></td>
    </tr>
</table>

<!---------FOOTER----------->
<center><div style="font-size:11px;">[[.page.]] [[|page_no|]]/[[|total_page|]]</div></center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]) || ([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td  align="center"> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
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

    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    jQuery('#from_date').datepicker();
    jQuery('#to_date').datepicker();
</script>