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
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.customer_debit_report.]]<br />
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
                                    <td>[[.date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 100px;"/></td>
                                    
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
        <th  class="report-table-header">Recode</th>
        <th  class="report-table-header">[[.room.]]</th>
        <th  class="report-table-header">[[.guest_name.]]</th>
        <th  class="report-table-header">[[.customer.]]</th>
        <th  class="report-table-header">[[.arrival_time.]]</th>
        <th  class="report-table-header">[[.departure_time.]]</th>
        <th  class="report-table-header">[[.night.]]</th>
        <th  class="report-table-header">[[.total_amount.]]</th>
        <th  class="report-table-header">[[.deposit.]]</th>
        <th  class="report-table-header">[[.customer_payed.]]</th>
        <th  class="report-table-header">[[.remain.]]</th>
	</tr>
    <?php
    $recode = false;
    $res_room = false; 
    ?>
    <!--LIST:items-->
        <!--LIST:items.rooms-->
            <!--LIST:items.rooms.travellers-->
            <?php
                if($recode!=[[=items.recode=]])
                {
                    $rowspan_recode = [[=items.rowspan_recode=]];
                    if($res_room!=[[=items.rooms.id=]])
                    {
                        $rowspan_room = [[=items.rooms.rowspan_room=]];
                        ?>
                        <tr>
                            <td rowspan="<?php echo $rowspan_recode; ?>">[[|items.index|]]</td>
                            <td rowspan="<?php echo $rowspan_recode; ?>">
                            <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.recode=]]));?>" target="_blank">
                            [[|items.recode|]]
                            </a></td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.name|]]<?php echo isset([[=items.rooms.note_change_room=]])? '<br/>'.[[=items.rooms.note_change_room=]]:'';?>
                                                                    </td>
                            <td>[[|items.rooms.travellers.name|]]</td>
                            <td rowspan="<?php echo $rowspan_recode; ?>">[[|items.customer_name|]]</td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.arrival_time|]]</td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.departure_time|]]</td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.night|]]</td>
                            <?php
                            if([[=items.is_deposit_folio_group=]]==0)//khong co dat coc nhom or thanh toan nhom
                            {
                                ?>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.total_money_room|]]</td>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.total_money_deposit_room|]]</td>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.total_money_payed_room|]]</td>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.remain_room|]]</td>
                                <?php 
                            }
                            else
                            {
                                ?>
                                <td rowspan="<?php echo $rowspan_recode; ?>" align="right">[[|items.total_money|]]</td>
                                <td rowspan="<?php echo $rowspan_recode; ?>" align="right">[[|items.total_money_deposit|]]</td>
                                <td rowspan="<?php echo $rowspan_recode; ?>" align="right">[[|items.total_money_payed|]]</td>
                                <td rowspan="<?php echo $rowspan_recode; ?>" align="right">[[|items.remain|]]</td>
                                <?php 
                            } 
                            ?>  
                            
                    	   </tr>
                        <?php 
                        $res_room=[[=items.rooms.id=]];
                    }
                    else
                    {
                        ?>
                        <tr>
                            <td>[[|items.rooms.travellers.name|]]</td>
                        </tr>
                        <?php 
                    }
                    ?>
                    
                    <?php 
                    $recode=[[=items.recode=]];
                } 
                else
                {
                    if($res_room!=[[=items.rooms.id=]])
                    {
                        $rowspan_room = [[=items.rooms.rowspan_room=]];
                        ?>
                        <tr>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.name|]]<?php echo isset([[=items.rooms.note_change_room=]])? '<br/>'.[[=items.rooms.note_change_room=]]:''?>
                                                                        </td>
                            <td>[[|items.rooms.travellers.name|]]</td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.arrival_time|]]</td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.departure_time|]]</td>
                            <td rowspan="<?php echo $rowspan_room; ?>">[[|items.rooms.night|]]</td>
                            
                            <?php
                            if([[=items.is_deposit_folio_group=]]==0)//khong co dat coc nhom or thanh toan nhom
                            {
                                ?>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.total_money_room|]]</td>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.total_money_deposit_room|]]</td>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.total_money_payed_room|]]</td>
                                <td rowspan="<?php echo $rowspan_room; ?>" align="right">[[|items.rooms.remain_room|]]</td>
                                <?php 
                            }
                            ?> 
                        </tr>
                        <?php 
                        $res_room=[[=items.rooms.id=]];
                    }
                    else
                    {
                        ?>
                        <tr>
                            <td>[[|items.rooms.travellers.name|]]</td>
                        </tr>
                        <?php 
                    }
                }
            ?>
            <!--/LIST:items.rooms.travellers-->
        <!--/LIST:items.rooms-->
    <!--/LIST:items-->
    <?php
    if($recode!=false)
    {
        ?>
        <tr>
            <td colspan="8" align="right" style="font-weight: bold;">[[.total.]]
            </td>
            <td align="right">[[|total_money|]]</td>
            <td align="right">[[|total_deposit|]]</td>
            <td align="right">[[|total_payed|]]</td>
            <td align="right">[[|total_remain|]]</td>
        </tr>
        <?php 
    } 
    ?>
</table>
<table style="margin-top: 10px; " width="100%" border="1" class="table-bound" style="font-size:11px;">
    <tr><td align="left" style="padding-left: 10px;">
    <strong>Chi Chú:</strong>
    <br />
    <strong>Tổng số tiền:</strong> Là tổng số tiền tính từ ngày xem trở về ngày đến(arrival) của phòng bao gồm: Tiền phòng tính đến ngày xem, tiền minibar & laundry, các dịch vụ mở rộng,hóa đơn đề bù<br /> tiền nhà hàng, spa, karaoke có chuyển về phòng, tiền bán vé(ticket), tiền bán hàng(vending) có chuyển về phòng, tiền điện thoại khách đã sử dụng
    <br />
    <strong>Tổng tiền đặt cọc:</strong> Là tổng số tiền đã đặt cọc tính tới ngày xem gồm: đặt cọc cho phòng hoặc nhóm phòng, đặt cọc nhà hàng, karaoke, bán vé, bán hàng có chuyển về phòng
    <br />
    <strong>Số tiền đã thanh toán: </strong>Là tổng số tiền đã thanh toán tính tới ngày xem gồm: thanh toán cho phòng, cho nhóm phòng, thanh toán nhà hàng, spa, karaoke, bán vé, bán hàng có chuyển về phòng
    <br />
    <strong>Tiền còn lại: </strong>Là tổng số tiền còn lại tính tới ngày xem được tính bằng  Tổng số tiền - Tổng số đặt cọc - Tổng số đã thanh toán
    </td></tr>
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
    }
);

</script>
