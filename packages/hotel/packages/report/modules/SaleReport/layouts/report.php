<?php //System::debug([[=start_page=]].'---'.[[=page_no=]]); ?>
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<!---------HEADER----------->
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
</div>
<table id="tblExport">
<tr id="header_report">
<td>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td >
    <table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong>
               </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong> 
        <br />
        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo User::id();?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.sale_report.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php echo [[=from_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=from_date=]] ?> - <?php echo [[=to_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</td>
</tr>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
.left{float: left;}
.p1{text-align:left;margin-bottom: 12px;margin-top: 25px;}
.search_sale{padding:0 6%;margin-bottom:20px;}
.p2{
    margin: 0px;
 
}
.fls_1{margin-right:20px;min-height: 70px;}
.fls{margin-bottom:15px ;}
.fr_time{margin-bottom: 12px;}
.center{text-align:center;}
.tr_1{text-align: center;
display: inline-block;}
.k55px{width:55px;display:inline-block}
.k40px{width:40px;display:inline-block}
</style>

<tr id="tr_search">
    <td style="text-align: left;">
<!---------SEARCH----------->
<fieldset id="search_from" style="border:none;">
           
    <table width="100%"  style="margin: 0px  auto 0px;padding:20px 0;text-align: center;" id="search" > 
    <tr class="tr_1">
    <td>
        <link rel="stylesheet" href="skins/default/report.css"/>
        <div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
            <div>
                <table width="100%">
                    <tr><td>
                        <form name="SearchForm" method="post">
                            <table class="table_select left">
                                <tr>   
                                    <td>
                                        <fieldset class="fls_1">
                                            <legend class="fls">
                                              <input name="search_invoice" type="checkbox" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);"/>
											  <label>[[.search_for_invoice.]]</label>
                                            </legend>
                                            <label>[[.folio_id.]]</label>   
                                            <input name="folio_number" type="text" id="folio_number" class="input_number b_blur" style="width: 40px;" readonly />
                                        </fieldset>

                                    </td>  
                                    <td style="border: 1px solid #cdcdcd;padding: 5px 10px;">
                                         <fieldset class="fls_1" style="float:left">
                                            <legend>
                                                <input name="search_time" type="checkbox" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);">
												<label>[[.search_for_time.]]</label>
                                            </legend>
                                        <div class="fr_time">
                                            <label class="k55px">[[.from_date.]]</label>
                                            <input name="from_date" type="text" id="from_date" onchange="changevalue()"/>
                                            
                                            <label class="k40px">[[.from_time.]]</label>
                                            <input name="from_time" type="text" id="from_time" style="width: 50px;"/>
                                        </div>
                                        <div >
                                            <label class="k55px">[[.to_date.]]</label>
                                            <input name="to_date" type="text" id="to_date" onchange="changefromday()"/>
                                            <label class="k40px">[[.to_time.]]</label>
                                            <input name="to_time" type="text" id="to_time" style="width: 50px;"/>
                                        </div>
                                    </fieldset>
									
									<div class="public left">

                                    <div class="p1">
                                        <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                        <label>[[.hotel.]]</label>
                                        <select name="portal_id" id="portal_id" style="margin-left:5px"></select>
                                        <?php }?>

                                        <label>[[.by_user.]]</label>
                                        <select name="user_id" id="user_id" style="margin-left:5px"></select>
                                        <label>[[.payment_type.]]</label>
                                        <select name="payment_type" id="payment_type" style="width: 106px;margin-left:5px"></select>
                                    </div>
                                    <div class="p1 p2">
                                         <label>[[.line_per_page.]]</label>
                                        <input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right;margin-left:5px"/>
                                    <label>[[.no_of_page.]]</label>
                                        <input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right;margin-left:5px"/>
                                    <label>[[.from_page.]]</label>
                                    <input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right;margin-left:5px"/>
                                   
                                
                              
                                <label>[[.customer.]]</label>
                                <input name="customer" type="text" id="customer" style="width: 100px;margin-left:5px" onkeypress="Autocomplete();"  autocomplete="off"/>
                               <label>[[.room_name.]]</label>
                                <input name="room_name" type="text" id="room_name" style="width: 40px;margin-left:5px" />

                                    </div>

                                </div>
                                    </td>
                                     </tr>
                                </table>

                                
                            
                               
                        
                    </td>
                 
                    </tr>
                   
                </table>
            </div>
        </div>
    </td>
    </tr>
     <tr>
     <td colspan="2">
            <div class="public center" style="margin-top: 5px;">
                     <input type="submit" name="do_search" value="[[.report.]]" onclick=" return check_search();"/>
                        <button id="export">[[.export.]]</button>
                </div>
                </form>
     </td>
            
        </tr>
</table>
</fieldset>
</td>
</tr>

<!--/IF:first_page-->

                <!---------REPORT----------->	
                <!--IF:check_room(isset([[=items=]]))-->
<tr>
<td>
                <table cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
                	<tr bgcolor="#EFEFEF">
                	    <th rowspan="3" class="report-table-header" style="width: 50px !important;">[[.no.]]</th>
                        <th rowspan="3" class="report-table-header">[[.recode.]]</th>
                        <th rowspan="3" class="report-table-header">[[.room.]]</th>
                        <th rowspan="3" class="report-table-header">[[.folio_id.]]</th>
                        <th rowspan="3" class="report-table-header">[[.guest_name.]]</th>
                        <th rowspan="3" class="report-table-header">[[.arrival_date.]]</th>
                        <th rowspan="3" class="report-table-header">[[.departure_date.]]</th>
                        <th rowspan="3" class="report-table-header">[[.night.]]</th>  
                        <th rowspan="3" class="report-table-header">[[.room_rate.]](VND)</th>
                        <th rowspan="3" class="report-table-header">[[.room_rate.]]<br />(USD)</th>
                        <th rowspan="3" class="report-table-header">[[.room_price_total.]]</th>
                        <th rowspan="3" class="report-table-header">[[.extra_bed.]]</th>
                        <th rowspan="3" class="report-table-header">[[.telephone.]]</th>
                        <!--LIST:portal_department-->
                         <?php if([[=portal_department.department_code=]]=='RES'){?>
                	       <th  class="report-table-header">[[.rest.]]</th>	
                         <?php }else if([[=portal_department.department_code=]]=='HK'){?>
                            <th rowspan="3" class="report-table-header">[[.minibar.]]</th>
                            <th rowspan="3" class="report-table-header">[[.laundry.]]</th>
                            <th rowspan="3" class="report-table-header">[[.compensation.]]</th>
                         <?php }else if([[=portal_department.department_code=]]=='SPA'){?>
                            <th rowspan="3" class="report-table-header">[[.spa.]]</th>
                        <?php }else if([[=portal_department.department_code=]]=='KARAOKE'){?>
                            <th rowspan="3" class="report-table-header">[[.karaoke.]]</th>
                        <?php }else if([[=portal_department.department_code=]]=='BANQUET'){?>
                            <th rowspan="3" class="report-table-header">[[.banquet.]]</th>
                        <?php }else if([[=portal_department.department_code=]]=='VENDING'){?>
                            <th rowspan="3" class="report-table-header">[[.vending.]]</th>
                        <?php }else if([[=portal_department.department_code=]]=='TICKET'){?>
                            <th rowspan="3" class="report-table-header">[[.ticket.]]</th>
                        <?php } ?>       
                        <!--/LIST:portal_department--> 
                        <th rowspan="3" class="report-table-header">[[.tour1.]]</th>
                        <th rowspan="3" class="report-table-header">[[.other.]]</th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFFF99;">[[.total.]]</th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFCCFF;">[[.discount.]]</th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFCCFF;">[[.deposit_room.]]</th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFCCFF;">[[.deposit_group.]]</th>
                        <th colspan="6" class="report-table-header" style="background-color:#99CCCC;">[[.payment.]]</th>
                        <th rowspan="3" class="report-table-header" style="background-color:#99CCCC;">[[.debit.]]</th>
                        <th rowspan="1" colspan="2" class="report-table-header" style="background-color:#99CCCC;">[[.refund.]]</th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FF66FF;">[[.total_amount.]]</th>   
                           
                	</tr>
                    <tr>
                        <!--LIST:portal_department-->
                         <?php if([[=portal_department.department_code=]]=='RES'){?>
                            <!--<th width="60px" class="report-table-header">[[.break_fast.]]</th>-->
                            <th width="60px" rowspan="2" class="report-table-header">[[.F&B.]]</th>	
                        <?php } ?>       
                        <!--/LIST:portal_department--> 
                        <th width="60px" colspan="2" class="report-table-header" style="background-color:#99CCCC;">[[.cash.]]</th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;">[[.bank_transfer.]]</th>
                        <th width="60px" colspan="2" class="report-table-header" style="background-color:#99CCCC;">[[.credit_card.]]</th>
                        <th width="60px" colspan="1" class="report-table-header" style="background-color:#99CCCC;">[[.foc.]]</th>
                        <th width="60px" rowspan="2" colspan="1" class="report-table-header" style="background-color:#99CCCC;">[[.VND.]]</th>
                        <th width="60px" rowspan="2" colspan="1" class="report-table-header" style="background-color:#99CCCC;">[[.USD.]]</th>
                    </tr>
                    <tr>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;">[[.VND.]]</th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;">[[.USD.]]</th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"></th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;">[[.VND.]]</th>
                        <th width="40px"  class="report-table-header" style="background-color:#99CCCC;">[[.USD.]]</th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"></th>
                    </tr>
                <!--start: KID  1
                <!--IF:first_pages_1([[=page_no=]]!=1)-->
                <!---------LAST GROUP VALUE----------->	        
                    <tr>
                        <td colspan="10" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
                    	<td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_room_total']?System::display_number([[=last_group_function_params=]]['total_room_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_extra_bed_total']?System::display_number([[=last_group_function_params=]]['total_extra_bed_total']):'';?></strong></td>
                        <!--<td align="right" class="report_table_column"><strong></strong></td>-->
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_telephone_total']?System::display_number([[=last_group_function_params=]]['total_telephone_total']):'';?></strong></td>
                        <!--LIST:portal_department-->
                         <?php if([[=portal_department.department_code=]]=='RES'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_restaurant_total']?System::display_number([[=last_group_function_params=]]['total_restaurant_total']):'';?></strong></td>	
                         <?php }else if([[=portal_department.department_code=]]=='HK'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_minibar_total']?System::display_number([[=last_group_function_params=]]['total_minibar_total']):'';?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_laundry_total']?System::display_number([[=last_group_function_params=]]['total_laundry_total']):'';?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_equip_total']?System::display_number([[=last_group_function_params=]]['total_equip_total']):'';?></strong></td>
                         <?php }else if([[=portal_department.department_code=]]=='SPA'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_spa_total']?System::display_number([[=last_group_function_params=]]['total_spa_total']):'';?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='KARAOKE'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_karaoke_total']?System::display_number([[=last_group_function_params=]]['total_karaoke_total']):'';?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='BANQUET'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_banquet_total']?System::display_number([[=last_group_function_params=]]['total_banquet_total']):'';?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='VENDING'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_vending_total']?System::display_number([[=last_group_function_params=]]['total_vending_total']):'';?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='TICKET'){?>
                            <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_ticket_total']?System::display_number([[=last_group_function_params=]]['total_ticket_total']):'';?></strong></td>
                        <?php } ?>       
                        <!--/LIST:portal_department--> 
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_tour_total']?System::display_number([[=last_group_function_params=]]['total_tour_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_extra_service_total']?System::display_number([[=last_group_function_params=]]['total_extra_service_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=last_group_function_params=]]['total_extra_service_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_room_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_minibar_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_restaurant_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_laundry_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_telephone_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_spa_total'])
                							 																											+round([[=last_group_function_params=]]['total_break_fast_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_equip_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_extra_bed_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_tour_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_karaoke_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_banquet_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_vending_total'])
                                                                                                                                                        +round([[=last_group_function_params=]]['total_ticket_total']));?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_reduce_amount_total']?System::display_number([[=last_group_function_params=]]['total_reduce_amount_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_deposit_total']?System::display_number([[=last_group_function_params=]]['total_deposit_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo [[=last_group_function_params=]]['total_group_deposit_total']?System::display_number([[=last_group_function_params=]]['total_group_deposit_total']):'';?></strong></td>
                <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_cash_vnd_total']==0?'':System::Display_number(round([[=last_group_function_params=]]['total_cash_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_cash_usd_total']==0?'':System::Display_number(([[=last_group_function_params=]]['total_cash_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_bank_total']==0?'':System::Display_number(round([[=last_group_function_params=]]['total_bank_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_credit_vnd_total']==0?'':System::Display_number(round([[=last_group_function_params=]]['total_credit_vnd_total'])));?></strong></td>
                	    <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_credit_usd_total']==0?'':System::Display_number(([[=last_group_function_params=]]['total_credit_usd_total'])));?></strong></td>  
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_foc_total']==0?'':System::Display_number(round([[=last_group_function_params=]]['total_foc_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_debit_total']==0?'':System::Display_number(round([[=last_group_function_params=]]['total_debit_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_refund_vnd_total']==0?'':System::Display_number(round([[=last_group_function_params=]]['total_refund_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=last_group_function_params=]]['total_refund_usd_total']==0?'':System::Display_number(([[=last_group_function_params=]]['total_refund_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::Display_number(round([[=last_group_function_params=]]['total_cash_total'])+round([[=last_group_function_params=]]['total_credit_vnd_total'])+round([[=last_group_function_params=]]['total_debit_total'])+round([[=last_group_function_params=]]['total_bank_total'])-round([[=last_group_function_params=]]['total_refund_total']));?></strong></td>
                    </tr>
                <!--/IF:first_pages_1-->
                <!--end:KID-->   
                    <?php 
                    $i=1;
                    $is_rowspan = false;
                    ?>
                    <!--LIST:items-->
                	<tr bgcolor="white">
                        <?php
                            $k = $this->map['count_room'][[[=items.code=]]]['num'];
                            if($is_rowspan == false)
                            {
                        ?>
                            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.stt|]]</td>
                            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
                            <a target="_blank" href="<?php echo "?page=reservation&layout=list&cmd=edit&id=".[[=items.reservation_id=]]; ?>">[[|items.reservation_id|]]</a>
                            </td>
                        <?php
                            } 
                        ?>
                        <?php 
                            if($k ==0 || $k ==1 || $i<=$k)
                            {
                        ?>
                            <td class="report_table_column" style="text-align: center;">[[|items.room_name|]]</td>
                        <?php
                           }
                        ?>
                         <?php 
                            if($is_rowspan == false)
                            {
                        ?>
                            <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>">
                            <a target="_blank" href="<?php echo ([[=items.customer_id=]] !=''?Url::build('view_traveller_folio',array('folio_id'=>[[=items.code=]],'id'=>[[=items.reservation_id=]],'cmd'=>'group_invoice','customer_id'=>[[=items.customer_id=]])):Url::build('view_traveller_folio',array('traveller_id'=>[[=items.traveller_id=]],'folio_id'=>[[=items.code=]])));?>">
                            <?php if(isset([[=items.folio_code=]])){?>
                                <?php  echo 'No.F'.str_pad([[=items.folio_code=]],6,"0",STR_PAD_LEFT); ?>
                            <?php } else {?>
                                 <?php  echo 'Ref'.str_pad([[=items.code=]],6,"0",STR_PAD_LEFT); ?>
                            <?php }?>
                            
                            </a>
                            <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.guest_name|]]</td>
                           
                        <?php
                            }
                        ?>
                        <?php 
                            if($k ==0 || $k ==1 || $i<=$k)
                            {
                        ?>
                             <td align="center" class="report_table_column"><?php echo ([[=items.time_in=]]==0?'':date('d/m/Y',[[=items.time_in=]]));?></td>
                            <td align="center" class="report_table_column" ><?php echo ([[=items.time_out=]]==0?'':date('d/m/Y',[[=items.time_out=]]));?></td>
                            <td align="center" class="report_table_column" ><?php echo System::Display_number((Date_Time::to_time(date('d/m/Y',[[=items.time_out=]]))-Date_Time::to_time(date('d/m/Y',[[=items.time_in=]])))/(24*3600));?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number([[=items.price=]]);?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number([[=items.price=]]/RES_EXCHANGE_RATE);?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.room=]]==0?'':System::Display_number(round([[=items.room=]]))); ?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.extra_bed=]]==0?'':System::Display_number(round([[=items.extra_bed=]])));?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.telephone=]]==0?'':System::Display_number(round([[=items.telephone=]])));?></td>
                            <!--LIST:portal_department-->
                             <?php if([[=portal_department.department_code=]]=='RES'){?>
                                <!--<td class="report_table_column" style="text-align: right;"></?php echo ([[=items.break_fast=]]==0?'':System::Display_number(round([[=items.break_fast=]])));?></td>-->
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.restaurant=]]==0?'':System::Display_number(round([[=items.restaurant=]])));?></td>	
                             <?php }else if([[=portal_department.department_code=]]=='HK'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.minibar=]]==0?'':System::Display_number(round([[=items.minibar=]]))); ?></td>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.laundry=]]==0?'':System::Display_number(round([[=items.laundry=]])));?></td>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.equip=]]==0?'':System::Display_number(round([[=items.equip=]])));?></td>
                             <?php }else if([[=portal_department.department_code=]]=='SPA'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.spa=]]==0?'':System::Display_number(round([[=items.spa=]])));?></td>
                            <?php }else if([[=portal_department.department_code=]]=='KARAOKE'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.karaoke=]]==0?'':System::Display_number(round([[=items.karaoke=]])));?></td></td>
                            <?php }else if([[=portal_department.department_code=]]=='BANQUET'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.banquet=]]==0?'':System::Display_number(round([[=items.banquet=]])));?></td></td>
                            <?php }else if([[=portal_department.department_code=]]=='VENDING'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.vending=]]==0?'':System::Display_number(round([[=items.vending=]])));?></td></td>
                            <?php }else if([[=portal_department.department_code=]]=='TICKET'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.ticket=]]==0?'':System::Display_number(round([[=items.ticket=]])));?></td></td>
                            <?php } ?>       
                            <!--/LIST:portal_department--> 
                            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.tour=]]==0?'':System::Display_number(round([[=items.tour=]])));?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.extra_service=]]==0?'':System::Display_number(round([[=items.extra_service=]])));?></td>
                            <td style="background-color:#FFFF99; text-align: right;" class="report_table_column"><?php echo System::Display_number(round([[=items.extra_service=]]
                                                                                                                                                        +[[=items.minibar=]]
                                                                                                                                                        +[[=items.restaurant=]]
                                                                                                                                                        +[[=items.laundry=]]
                                                                                                                                                        +[[=items.telephone=]]
                
                                                                                                                                                        +[[=items.equip=]]
                                                                                                                                                        +[[=items.spa=]]
                                                                                                                                                        +[[=items.room=]]
                                                                                                                                                        +[[=items.extra_bed=]]
                																																		+[[=items.break_fast=]]
                                                                                                                                                        +[[=items.tour=]]
                                                                                                                                                        +[[=items.karaoke=]]
                                                                                                                                                        +[[=items.vending=]]
                                                                                                                                                        +[[=items.banquet=]]
                                                                                                                                                        +[[=items.ticket=]]));?></td>
                                 <td style="background-color:#FFCCFF;"><?php echo ([[=items.reduce_amount=]]==0?'':System::Display_number(round([[=items.reduce_amount=]])));?></td>
                            <td class="report_table_column" style="text-align: right; background-color:#FFCCFF;"><?php echo ([[=items.deposit=]]==0?'':System::Display_number(round([[=items.deposit=]])));?></td>
                        <?php
                           $i++ ;}
                        ?>
                        <?php 
                            if($is_rowspan == false)
                            {
                        ?>
                            <td class="report_table_column" style="text-align: right; background-color:#FFCCFF;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.deposit_group=]]==0?'':System::Display_number(round([[=items.deposit_group=]])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.cash_vnd=]]==0?'':System::Display_number(round([[=items.cash_vnd=]])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.cash_usd=]]==0?'':System::Display_number(([[=items.cash_usd=]])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.bank=]]==0?'':System::Display_number(round([[=items.bank=]])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.credit_card_vnd=]]==0?'':System::Display_number([[=items.credit_card_vnd=]]));?></td> 
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.credit_card_usd=]]==0?'':System::Display_number([[=items.credit_card_usd=]]));?></td> 
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.foc=]]==0?'':System::Display_number([[=items.foc=]]));?></td>  
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.debit=]]<999?'':System::Display_number([[=items.debit=]]));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.refund_vnd=]]<999?'':System::Display_number([[=items.refund_vnd=]]));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ([[=items.refund_usd=]]<0.0001?'':System::Display_number([[=items.refund_usd=]]));?></td>
                            <td align="right" class="report_table_column" style="background-color:#FF66FF;" rowspan="<?php echo $k; ?>"><?php echo System::Display_number(round(([[=items.cash=]])+([[=items.credit_card=]])+([[=items.debit=]])+([[=items.bank=]])-([[=items.refund=]])));?></td>
                        <?php
                            }
                                if($is_rowspan == false)
                            {
                                $is_rowspan = true;
                            } 
                            if($k ==0 || $k ==1 || $i>$k)
                            {
                                $i = 1;
                                $is_rowspan = false;
                            } 
                        ?>
                    </tr>
                	<!--/LIST:items-->
                    
                    	<tr bgcolor="white">
                		<td class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['real_room_count']); ?></strong></td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_room_total'])); ?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_extra_bed_total'])); ?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_telephone_total'])); ?></strong></td>
                        <!--LIST:portal_department-->
                         <?php if([[=portal_department.department_code=]]=='RES'){?>
                            <!--<td align="right" class="report_table_column"><strong></?php echo System::display_number(round([[=group_function_params=]]['total_break_fast_total'])); ?></strong></td>-->
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_restaurant_total'])); ?></strong></td>	
                         <?php }else if([[=portal_department.department_code=]]=='HK'){?>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_minibar_total'])); ?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_laundry_total'])); ?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_equip_total'])); ?></strong></td>
                         <?php }else if([[=portal_department.department_code=]]=='SPA'){?>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_spa_total'])); ?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='KARAOKE'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_karaoke_total'])); ?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='BANQUET'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_banquet_total'])); ?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='VENDING'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_vending_total'])); ?></strong></td>
                        <?php }else if([[=portal_department.department_code=]]=='TICKET'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_ticket_total'])); ?></strong></td>
                        <?php } ?>       
                        <!--/LIST:portal_department--> 
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_tour_total'])); ?>&nbsp;</strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_extra_service_total'])); ?></strong></td>
                        <td style="background-color:#FFFF99;" align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_extra_service_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_room_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_minibar_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_restaurant_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_laundry_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_telephone_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_spa_total'])
                							 																											+round([[=group_function_params=]]['total_break_fast_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_equip_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_extra_bed_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_tour_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_karaoke_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_banquet_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_vending_total'])
                                                                                                                                                        +round([[=group_function_params=]]['total_ticket_total'])); ?></strong></td>
                        <td style="background-color:#FFCCFF;"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_reduce_amount_total'])); ?></strong></td>                                                                                                                                
                        <td align="right" class="report_table_column" style="background-color:#FFCCFF;"><?php echo System::display_number(round([[=group_function_params=]]['total_deposit_total'])); ?></td>
                        <td align="right" class="report_table_column" style="background-color:#FFCCFF;"><?php echo System::display_number(round([[=group_function_params=]]['total_group_deposit_total'])); ?></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_cash_vnd_total']==0?'':System::Display_number(round([[=group_function_params=]]['total_cash_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_cash_usd_total']==0?'':System::Display_number(([[=group_function_params=]]['total_cash_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_bank_total']==0?'':System::Display_number(round([[=group_function_params=]]['total_bank_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_credit_vnd_total']==0?'':System::Display_number(round([[=group_function_params=]]['total_credit_vnd_total'])));?></strong></td>
                	    <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_credit_usd_total']==0?'':System::Display_number(([[=group_function_params=]]['total_credit_usd_total'])));?></strong></td>  
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_foc_total']==0?'':System::Display_number(round([[=group_function_params=]]['total_foc_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_debit_total']==0?'':System::Display_number(round([[=group_function_params=]]['total_debit_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_refund_vnd_total']==0?'':System::Display_number(round([[=group_function_params=]]['total_refund_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ([[=group_function_params=]]['total_refund_usd_total']==0?'':System::Display_number(([[=group_function_params=]]['total_refund_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#FF66FF;"><strong><?php echo System::Display_number(round([[=group_function_params=]]['total_cash_total']+[[=group_function_params=]]['total_credit_total']+[[=group_function_params=]]['total_debit_total']+[[=group_function_params=]]['total_bank_total']-[[=group_function_params=]]['total_refund_total']));?></strong></td>
                    </tr>
                </table>
</td>
</tr>
<tr><td><center><div style="font-size:11px;">[[.page.]] [[|page_no|]]/[[|total_page|]]</div></center></td></tr>
<!--ELSE-->
<tr><td><strong>[[.no_data.]]</strong></td></tr>
<!--/IF:check_room-->

<!---------FOOTER----------->

<!--IF:end_page(([[=real_page_no=]]==[[=real_total_page=]]))-->
<tr><td>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>

	<td colspan="2" align="left"></td>
	<td  align="center"> <?php //echo date('H\h : i\p',time());?> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td align="center" width="33%" >[[.director.]]</td>
</tr>
</table>
</table>
</td></tr>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>


<style type="text/css">
th,td{white-space:nowrap;}
input[id="from_date"]{width:70px;}
input[id="start_page"]{width:30px;}
input[id="no_of_page"]{width:30px;}
input[id="line_per_page"]{width:30px;}
input[id="to_date"]{width:70px;}
input[type="submit"]{width:100px;}
input[id="from_time"]{width:40px;}
input[id="to_time"]{width:40px;}
selcet[id="user_id"]{width:70px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
  .b_blur{
    background: #cdcdcd;
    }
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
      <?php
    if(isset($_REQUEST)){
        if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !=''){
            ?>
            document.getElementById("search_time").checked = false;
            document.getElementById("search_invoice").checked = true;
            jQuery("#from_date").addClass('b_blur').attr('value','');
                jQuery("#to_date").addClass('b_blur').attr('value','');
                 jQuery("#from_time").addClass('b_blur').attr('value','');
                jQuery("#to_time").addClass('b_blur').attr('value','');
             jQuery("#folio_number").removeClass('b_blur');
            <?php
        }
    
    }
    ?>
        if(jQuery("#search_invoice").is(":checked")){
                  jQuery("#from_code").removeClass();
                  jQuery("#from_code").attr('readonly',false);
                  jQuery("#to_code").removeClass();
                  jQuery("#to_code").attr('readonly',false);
    };
    
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
		jQuery("#from_date").addClass('b_blur').attr('value','');
		jQuery("#to_date").addClass('b_blur').attr('value','');
		 jQuery("#from_time").addClass('b_blur').attr('value','');
		jQuery("#to_time").addClass('b_blur').attr('value','');
		  jQuery("#from_code").removeClass();
		  jQuery("#from_code").attr('readonly',false);
		  jQuery("#to_code").removeClass();
		  jQuery("#to_code").attr('readonly',false);
                jQuery("#folio_number").removeClass('b_blur').attr('readonly',false);
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#to_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#folio_number").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#to_code").attr('readonly',true);
            jQuery("#from_date").removeClass('b_blur');
            jQuery("#to_date").removeClass('b_blur');
            jQuery("#from_time").removeClass('b_blur');
            jQuery("#to_time").removeClass('b_blur');
        }
    });
    
        jQuery("#search_time").click(function(){
			jQuery("#from_date").removeClass('b_blur');
            jQuery("#to_date").removeClass('b_blur');
            jQuery("#from_time").removeClass('b_blur');
            jQuery("#to_time").removeClass('b_blur');
        if(jQuery("#search_invoice").is(":checked")){
            jQuery("#folio_number").attr('readonly',false).removeClass();
            
             //jQuery("#from_code").attr('readonly',false);
          
			jQuery("#from_date").addClass('b_blur').attr('value','');
            jQuery("#to_date").addClass('b_blur').attr('value','');
            jQuery("#from_time").addClass('b_blur').attr('value','');
            jQuery("#to_time").addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").attr('value','00:00');
			jQuery("#to_time").attr('value','<?php echo date('H:i');?>');
            jQuery("#folio_number").addClass('b_blur').attr('value','').attr('readonly',true);
            
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#to_code").attr('readonly',true);
            jQuery("#from_code").attr('value','');
            jQuery("#to_code").attr('value','');
        }     
    });
});
</script>
<script>
    function check_bar()
    {    var from_code = jQuery("#from_code").val();
        var to_code = jQuery("#to_code").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code==''){
                   
                    alert('[[.empty_report.]]');
                    return false;
                }
                
            }
    }   
    function folio_number()
    { 
	var folio_number = jQuery("#folio_number").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(folio_number==''){
                    alert('[[.empty_folio.]]');
                    return false;
                }else{
					return true;
				}
                
            }
    } 
    function fun_check_option(id)
    {
        if(id==1)
        {
            if(document.getElementById("search_time").checked==true)
            {
                document.getElementById("search_invoice").checked=false;
            }
            else
            {
                document.getElementById("search_invoice").checked=true;
            }
        }
        else
        {
            if(document.getElementById("search_invoice").checked==true)
            {
                document.getElementById("search_time").checked=false;
            }
            else
            {
                document.getElementById("search_time").checked=true;
            }
        }
    }
</script>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        
        jQuery('#to_day').datepicker();
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
        jQuery("#export").click(function () {
            <?php if([[=real_page_no=]]==[[=real_total_page=]]){ ?>
            jQuery("#tr_search").remove();
            jQuery("#header_report").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
            <?php } ?>
        });
    }
);
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
function Autocomplete()
{
    jQuery("#customer").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
            document.getElementById('customer_id').value = item.data[0];
           // console.log(document.getElementById('customer_id').value);
        }
    }) ;
}
//start:KID them ham check dieu kien search
function check_search()
{
    var hour_from = (jQuery("#from_time").val().split(':'));
    var hour_to = (jQuery("#to_time").val().split(':'));
    var date_from_arr = jQuery("#from_date").val();
    var date_to_arr = jQuery("#to_date").val();
	var folio_number = jQuery("#folio_number").val();
    var search_invoice =jQuery("#search_invoice").is(":checked");
    if(search_invoice){
                if(folio_number==''){
                    alert('[[.empty_folio.]]');
                    return false;
                } 
            }
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
