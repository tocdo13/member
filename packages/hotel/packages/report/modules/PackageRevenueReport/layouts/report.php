<?php //System::debug([[=items=]]); ?>
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong>
        
       
         </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.package_revenue_report.]]<br />
        </font> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>

<!---------HEADER----------->

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
                                    <td>Gói sử dụng: </td>
                                    <td>
                                        <select name="package_sale_id" id="package_sale_id" style="width: 120px;">[[|package_options|]]</select>
                                    </td>   
                                    
                                    <td>Khách hàng: </td>
                                    <td><input name="customer_name" type="text" id="customer_name" /></td>
                                    
                                    <td>Mã MĐ:</td>
                                    <td><input name="recode" type="text" id="recode" style="width: 50px;" /></td>
                                    
                                    <td>[[.room.]]: </td>
                                    <td> <select name="room_id" id="room_id" style="width: 80px;">[[|room_options|]] </select></td>
                                       
                                    <td>Từ ngày: </td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 100px;"/></td>
                                    
                                    <td>Đến ngày: </td>
                                    <td><input name="to_date" type="text" id="to_date" style="width: 100px;" /></td>
                                    <td><input type="submit" name="do_search" value="[[.report.]]"/></td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>
<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
	    <th  class="report-table-header">[[.stt.]]</th>
        <th  class="report-table-header">[[.package.]]</th>
        <th  class="report-table-header">Khách hàng sử dụng</th>
        <th  class="report-table-header">Mã MĐ</th>
        <th  class="report-table-header">[[.room.]]</th>
        <th  class="report-table-header">[[.guest_name.]]</th>
        <th  class="report-table-header">[[.from_date.]]</th>
        <th  class="report-table-header">[[.to_date.]]</th>
        <th  class="report-table-header">[[.amount.]]</th>
        
	</tr>
    <!--LIST:items-->
        <tr>
            <?php
                if([[=items.row_span_package=]]!=0)
                {
                    ?>
                    <td rowspan="[[|items.row_span_package|]]">[[|items.index|]]</td>
                    <td rowspan="[[|items.row_span_package|]]" align="left">[[|items.package_name|]]</td>
                    <?php 
                }
                if([[=items.row_span_customer=]]!=0)
                {
                    ?>
                    <td rowspan="[[|items.row_span_customer|]]" align="left">[[|items.customer_name|]]</td>
                    <?php 
                } 
                if([[=items.row_span_recode=]]!=0)
                {
                    ?>
                    <td rowspan="[[|items.row_span_recode|]]"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]</a></td>
                    <?php 
                }
                if([[=items.row_span_room=]]!=0)
                {
                    ?>
                    <td rowspan="[[|items.row_span_room|]]" >[[|items.room_name|]]</td>
                    <?php 
                }
                
            ?>
            
            <td align="left">[[|items.traveller_name|]]</td>
            <?php
                if([[=items.row_span_room=]]!=0)
                {
                    ?>
                    <td rowspan="[[|items.row_span_room|]]" >[[|items.arrival_time|]]</td>
                    <td rowspan="[[|items.row_span_room|]]" >[[|items.departure_time|]]</td>
                    <td rowspan="[[|items.row_span_room|]]"  align="right">[[|items.total_amount|]]</td>
                    <?php 
                } 
            ?>
            
        </tr>
        
    <!--/LIST:items-->
        <?php
            if([[=total_amount=]]!=0)
            {
                ?>
                <tr>
                    <td align="right" colspan="8"><strong>Tổng tiền</strong></td>
                    <td align="right"><strong>[[|total_amount|]]</strong></td>
                    
                </tr>
                <?php 
            }
        ?>
        
</table>
 
<p>&nbsp;</p>

<table  cellpadding="5" cellspacing="0" width="80%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
    <tr bgcolor="#EFEFEF">
        <th class="report-table-header" colspan="5">THỐNG KÊ SỬ DỤNG GÓI PACKAGE</th>
        
    </tr>
	<tr bgcolor="#EFEFEF">
	    <th  class="report-table-header">[[.stt.]]</th>
        <th  class="report-table-header">Gói sử dụng</th>
        <th  class="report-table-header">Đơn giá</th>
        <th  class="report-table-header">Số lượng</th>
        <th  class="report-table-header">Thành tiền</th>
	</tr>
    <!--LIST:package_summary-->
        <tr>
        <td>[[|package_summary.index|]]</td>
        <td align="left">[[|package_summary.name|]]</td>
        <td align="right">[[|package_summary.price|]]</td>
        <td>[[|package_summary.num_rr_id|]]</td>
        <td align="right">[[|package_summary.total_amount|]]</td>
        </tr>
    <!--/LIST:package_summary-->
    <?php
        if([[=total_amount=]]!=0)
        {
            ?>
            <tr>
            <td colspan="4" align="right"><strong>Tổng tiền</strong></td>
            <td align="right"><strong>[[|total_amount|]]</strong></td>
            </tr>
            <?php 
        } 
    ?>
    
 </table>
<p>&nbsp;</p>
<script type="text/javascript">
full_screen();
</script>

<style type="text/css">
th,td{white-space:nowrap;}
input[id="from_date"]{width:70px;}
input[id="to_date"]{width:70px;}
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
    }
);

</script>
