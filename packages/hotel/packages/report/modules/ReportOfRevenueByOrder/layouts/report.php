<!---------HEADER----------->
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
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
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.report_of_revenue_by_order.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php echo [[=from_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=from_date=]] ?> - <?php echo [[=to_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>

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
                    <tr><td>
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto">
                                <tr>        
                                    <td>[[.line_per_page.]]</td>
                                    <td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right;width:30px"/></td>
                                    <td>[[.no_of_page.]]</td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right;width:30px"/></td>
                                    <td>[[.from_page.]]</td>
                                    <td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right;width:30px"/></td>
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue();" style="width: 80px;"/></td>
                                    <td>[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday();"  style="width: 80px;"/></td>
                    			    <td>[[.by_user.]]</td>
                    			    <td><select name="user_id" id="user_id"></select></td>
                                    <td>[[.area.]]</td>
                                    <td><select name="area_id" id="area_id"></select></td>
                                    <td>[[.from_time.]]</td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 50px;"/></td>
                                    <td>[[.to_time.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>
                                    
                                    <td><input type="submit" name="do_search" value="[[.report.]]" onclick="return check_search();" /></td>
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

<!--IF:check_product(isset([[=items=]]))-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
	    <th width="10px"  rowspan="2" class="report-table-header">[[.no.]]</th>
        <th width="120px" colspan="2" class="report-table-header">Order</th>
        <th width="200px" colspan="2" class="report-table-header">[[.product.]]</th>
        <th width="50px"  class="report-table-header">[[.unit.]]</th>
        <th width="50px" class="report-table-header">[[.quantity.]]</th>
        <th width="50px"  class="report-table-header" >[[.unit_price.]]</th>
        <th width="40px"  class="report-table-header" >[[.foc.]]</th>   
        <th width="60px" class="report-table-header">[[.agent_discount.]]</th>
        <th width="60px"  class="report-table-header">[[.promotion.]]</th>
        <th width="60px"  class="report-table-header">[[.net_unit_price.]]</th>
        <th width="60px"  class="report-table-header">[[.amount.]]</th>
        <th width="60px"  class="report-table-header">[[.invoice_revenue.]]</th>
        <th width="60px"  class="report-table-header">[[.vat.]](10%)</th>  
        <th width="60px" class="report-table-header">[[.net_revenue.]]</th>         
	</tr>
    <tr>
        
        <td width="120px" class="report-table-header">[[.date.]]</td>
        <td width="100px"  class="report-table-header">[[.number.]]</td>
        <td width="50px"  class="report-table-header">[[.code.]]</td>
        <td width="150px"  class="report-table-header">[[.name.]]</td>
        <td width="50px" class="report-table-header">&nbsp;</td>  
        <td width="50px"  class="report-table-header" >a</td>
        <td width="40px"  class="report-table-header" >b</td>   
        <td width="60px"  class="report-table-header">Y/N</td>
        <td width="60px" class="report-table-header">c</td>
        <td width="60px" class="report-table-header">d</td>
        <td width="60px"  class="report-table-header">e=b-c-d</td>
        <td width="60px" class="report-table-header">f=a*e</td>
        <td width="60px"  class="report-table-header">g</td>  
        <td width="60px" class="report-table-header">h=10%*i</td> 
        <td width="60px"  class="report-table-header">i=g/1.1</td>  
        
    </tr>
    <!--start: KID  1
    <!--IF:first_pages_1([[=page_no=]]!=1)-->
    <!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="6" class="report_table_column"><strong>[[.last_page_summary.]]</strong></td>
        <td align="center" class="report_table_column"><strong><?php echo ([[=last_group_function_params=]]['total_quantity']); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_unit_price=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong>&nbsp;</strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_agent_discount=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_promotion=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_net_unit_price=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_amount=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo (System::display_number([[=last_group_function_params=]]['total_invoice_revenue'])); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_vat=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo( System::display_number([[=last_group_function_params=]]['total_net_revenue'])); ?></strong></td>
    </tr>
    <!--/IF:first_pages_1-->
    <!--end:KID-->   
    <?php 
    $i=1;
    $stt=1;
    $is_rowspan = false;
    ?>
    <!--LIST:items-->
      <tr bgcolor="white">
        <?php
            $k = $this->map['count_product'][[[=items.ve_reservation_id=]]]['num'];
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <?php echo $stt; ?>
        </td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <?php echo date('H:i d/m/Y',[[=items.time=]]);?>    
        </td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <a href="<?php echo Url::build('vending_reservation');?>" target="_blank">[[|items.code|]]</a>
                
        </td>
        <?php
                $stt += 1;
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?> 
        <td class="report_table_column" style="text-align: left;">[[|items.product_id|]]</td>
        <td align="left" class="report_table_column">[[|items.name|]]</td>
        <td align="left" class="report_table_column">[[|items.name_1|]]</td>
        <td align="right" class="report_table_column">[[|items.quantity|]]</td>
        <td align="center" class="report_table_column"><?php echo System::display_number([[=items.unit_price=]]);?></td>
        <?php if([[=items.foc=]]==1){?>
		  <td align="center" class="report_table_column">Y</td>
        <?php }else{ ?>
            <td align="center" class="report_table_column">N</td>
        <?php } ?>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.agent_discount=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.promotion=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.net_unit_price=]]);?></td>
        <td align="right" class="report_table_column"><?php echo System::display_number([[=items.amount=]]);?></td>
        <?php
           $i++ ;}
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
        <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number($this->map['count_product'][[[=items.ve_reservation_id=]]]['invoice_revenue']);?></td>
        <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number($this->map['count_product'][[[=items.ve_reservation_id=]]]['vat']);?></td>
        <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number($this->map['count_product'][[[=items.ve_reservation_id=]]]['net_revenue']);?></td>
        
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
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>
        
	</tr>  
    <!--/LIST:items-->

	<tr bgcolor="white">
		<td colspan="6" class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_quantity']); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_unit_price=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong>&nbsp;</strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_agent_discount=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_promotion=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_net_unit_price=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_amount=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_invoice_revenue']); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php //echo System::display_number([[=total_vat=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_net_revenue']); ?></strong></td>
        
	</tr>
    
</table>


<center><div style="font-size:11px;">[[.page.]] [[|page_no|]]/[[|total_page|]]</div></center>
<br/>
<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_product-->
<!---------FOOTER----------->
<!--IF:end_page(([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<!--<tr>
	<td></td>
	<td  align="center"> <?php //echo date('H\h : i\p',time());?> [[.day.]] <?php //echo date('d');?> [[.month.]] <?php //echo date('m');?> [[.year.]] <?php //echo date('Y');?><br /></td>
</tr>-->
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
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
        
        if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
        {
            alert('[[.start_time_longer_than_end_time_try_again.]]');
            return false;
        }
        else
        {
            if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
            {
                alert('[[.the_max_time_is_2359_try_again.]]');
                return false;
            }
            else
            {    
                return true;       
            }
        }   
    }
    //end:KID them ham check dieu kien search
</script>