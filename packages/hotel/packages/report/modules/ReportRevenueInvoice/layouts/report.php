<style>
    .simple-layout-middle{
		width:100%;	
	}
    .simple-layout-center{
        overflow-x: auto;
    }
    #search table tr td{
        text-align: center;
    }
    #search table tr td:last-child{
        border-right: none;
    }
    #container table tr th{
        height: 30px; text-align: center;
    }
    #container table tr td{
        text-align: center;
    }
    @media print{
	    #search{
	       display: none;
	    }
        a{
            text-decoration: none;
            color: #000000;
        }
    }
    .invoce_1 {
display: inline-block;
width: 75px;
margin-left: 6px;
}
.invoce_2 {
display: inline-block;
width: 61px;
margin-left: 6px;
}
.invoce_div{
        margin-bottom: 12px;
    }
    .do_search{
        padding: 6px 8px 5px 8px;
      
        border-radius: 5px;
        border: none;
        margin-left: 10px;
    }
    .wrapper-invoice{
        margin-top: 16px;
        margin-bottom: 12px;
        text-align: left;
    }
    .invoce_3{
        display:inline-block;
        margin-right: 6px;
     
    }  
    .b_blur{background: #cdcdcd;}
	.clear{clear:both;}
	.k_2{text-align:left;float:left;margin-top: 6px;border: 1px solid #FF7F50;border-radius: 10px;}
	.k_2 label{font-weight:100}
   .hidden_col{white-space: nowrap;}
   .bg_header{background:#dddddd;}
</style>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table  id="tblExport"  width="100%">
<tr id="header">
<td>
    <table style="border: none; width: 100%;">
        <tr valign="top" stype="font-size:11px;">
			<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong>
            <br />
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
            </td>
		</tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <h2 class="report-title-new">[[.room_order_revenue_report.]]</h2>
             <?php if(isset([[=search_invoice=]]) AND [[=search_invoice=]]!=''){ ?>
             <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                    <?php if([[=from_code=]]!=''){
                        ?>
                     [[.from_code.]]
                     <?php echo [[=from_code=]]; } ?>
                        <?php if([[=to_code=]]!=''){ ?> 
                        [[.to_code.]]
                    <?php echo [[=to_code=]];
                     } 
                     }else{
                        ?>
                        [[.from.]]: [[|from_time|]] [[|from_date|]] - [[.to.]]: [[|to_time|]] [[|to_date|]]
                        <?php
                     }
                     ?> 
              
              </div>
                
            </td>
        </tr>
    </table>
</td>
</tr> 
<tr id="search" style="width: 99%; margin: 0px auto;">
<td>
    <form name="SearchFrom" method="post">
        <fieldset style="margin: 0 10%;text-align: center;margin-bottom: 15px;border: 1px solid #008B8B;">
            <legend style="margin-left: 3%;">[[.search.]]</legend>
            <div style="text-align: center;display:inline-block;">
            <table style="float:left">
                <tr>
                <td>
                 <fieldset style="width:177px;margin-right: 20px;">
                    <legend>
                    <input name="search_invoice" type="checkbox" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !='')echo 'checked="checked"';} ?>><label>[[.search_by_bill.]]</label></legend>
                    <div class="invoce_div">
                        <label class="invoce_1">[[.bill_id.]]</label> 
                        <input name="from_code" type="text" id="from_code" style="width: 70px;" class="b_blur input_number" readonly="read" /> 
                    </div>
                    <div class="invoce_div">
                        <label class="invoce_1">[[.to.]]:</label>
                         <input name="to_code" type="text" id="to_code" style="width: 70px;" class="b_blur input_number" readonly="read" />
                    </div>   
                    <div>
                        <label class="invoce_1">[[.recode.]]:</label>
                         <input name="recode" type="text" id="recode" style="width: 70px;" class="b_blur input_number" readonly="read" />
                    </div> 
                  </fieldset>
                </td>
				  </tr>
                
            </table>
                <div class="k_2">
					<div style="">
					<fieldset style="border:none;display: inline-block;float: left;margin-right: 10px;">
                    <legend>
                    <input name="search_time" type="checkbox" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_time']) && $_REQUEST['search_time'] !='')echo 'checked="checked"'; } ?>><label>[[.search_by_time.]]</label></legend>
                    <div class="invoce_div">
                        <table>
                            <tr>
                                <td><label class="invoce_2">[[.from_date.]]:</label></td>
                                <td><input name="from_date" type="text" id="from_date" style="width: 80px;" onchange="changevalue();" /></td>
                                <td><label class="invoce_2">[[.from_time.]]:</label></td>
                                <td><input name="from_time" type="text" id="from_time" class="input-short-text" /></td>
                                <td nowrap><label class="invoce_3">[[.hotel.]]:</label></td>
                                <td colspan="3"><select name="portal_id" id="portal_id" style="width: 100%;"></select></td>
                            </tr>
                            <tr>
                                <td><label class="invoce_2">[[.to_date.]]:</label></td>
                                <td><input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();" /> </td>
                                <td><label class="invoce_2">[[.to_time.]]:</label></td>
                                <td><input name="to_time" type="text" id="to_time" class="input-short-text" /></td>
                                <td  align="left" nowrap="nowrap">[[.user_status.]]</td>
                			    <td>
                                  <!-- 7211 -->  
                                  <select style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                                    <option value="1">Active</option>
                                    <option value="0">All</option>
                                  </select>
                                  <!-- 7211 end--> 
                                </td>
                                <td><label class="invoce_3">[[.user.]]:</label></td>
                                <td><select name="user_id" id="user_id"></select></td>
                            </tr>
                        </table>  
                    </div>
                  </fieldset>
                  <!--
				  <div style="padding-top:24px;">
                    <div class="invoce_div" style="margin-bottom: 15px;float:left">
                     
                     
                    </div>
                    
                    <div style="float:left">
                        <label class="invoce_3">[[.line_per_page.]]:</label> <input name="line_per_page" type="text" id="line_per_page" style="width: 35px;text-align:center" />
                         <label class="invoce_3">[[.no_of_page.]]:</label> <input name="no_of_page" type="text" id="no_of_page" style="width: 35px;text-align:center" />
                         <label class="invoce_3">[[.from_page.]]:</label> <input name="start_page" type="text" id="start_page" style="width: 35px;text-align:center"  />
                    </div>
                   </div> --> 
                    </div>
                </div>
				<div class="clear"></div>
                <div>
                    <!-- Oanh add -->
                    <button id="export">[[.export_file_excel.]]</button>
                    <!-- Edn oanh -->
                    <input name="do_search" type="submit" value="[[.search.]]" class="do_search" onclick="return check_search();"/>
                </div>
              
            </div>
            
        </fieldset>
    </form>
</td>
</tr>
<div style="text-align:left;margin-bottom: 15px;margin-left: 10%;">
                    <input type="checkbox" name="check_hidden_col" id="check_hidden_col"/>[[.hidden_col.]]
</div>
<tr id="container">
<td>
    <table cellpadding="2" cellspacing="0" border="1" bordercolor="#cccccc" id="container_table">
        <tr class="bg_header">
            <th rowspan="3" style="white-space: nowrap;">[[.stt.]]</th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;">[[.order_id.]]</th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;">[[.recode.]]</th>
            <th rowspan="3" style="width: 200px;">[[.guest_name.]]</th>
            <!--<th rowspan="3" style="width: 50px;white-space: nowrap;">[[.room.]]</th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;">[[.arrival_date.]]<br />[[.departure_date.]]</th>
            <th rowspan="3" style="width: 30px;white-space: nowrap;">[[.room_night.]]</th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;">[[.room_rate.]]</th>-->
            <th rowspan="3" style="width: 50px;" class="hidden_col">[[.room_price_total.]]</th>
            <th rowspan="3" style="width: 50px;" class="hidden_col">[[.extra_bed_1.]]</th>
            <th rowspan="3" style="width: 50px;" class="hidden_col">[[.baby_cot.]]</th>
			<th rowspan="3" class="hidden_col">[[.tour1.]]</th>
			<th rowspan="3" class="hidden_col">[[.extra_service.]]</th>
			<th rowspan="3" class="hidden_col">[[.telephone.]]</th>
			
            <th rowspan="3" class="hidden_col">[[.minibar.]]</th>
            <th rowspan="3" class="hidden_col">[[.laundry.]]</th>
			<th rowspan="3" class="hidden_col">[[.equipment.]]</th>
			
			<th rowspan="3" class="hidden_col">[[.restaurant.]]</th>
			<!---<th rowspan="3" class="hidden_col">[[.banquet.]]</th>
            <th rowspan="3" class="hidden_col">[[.karaoke.]]</th>--->
			
			<th rowspan="3" class="hidden_col">[[.spa.]]</th>
            <!---<th rowspan="3" class="hidden_col">[[.vending.]]</th>
            <th rowspan="3" class="hidden_col">[[.ticket.]]</th>--->
			
            <th rowspan="3" class="hidden_col">[[.deposit.]]</th>
            <th rowspan="3" style="width: 100px;">[[.total.]]</th>
			
            <th colspan="<?php echo ((sizeof([[=payment_type=]])-2)*sizeof([[=currency=]]))+2; ?>">[[.payment.]]</th>
           
            <th rowspan="3" style="white-space: nowrap;">[[.user_id.]]</th>
        </tr>
        <tr class="bg_header">
            <!--LIST:payment_type-->
            <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
            <th style="white-space: nowrap;">[[|payment_type.name|]]</th>
            <?php }else{ ?>
            <th colspan="<?php echo sizeof([[=currency=]]); ?>" style="white-space: nowrap;">[[|payment_type.name|]]</th>
            <?php } ?>
            <!--/LIST:payment_type-->
        </tr>
        <tr class="bg_header">
            <!--LIST:payment_type-->
                <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
                    <th>VND</th>
                <?php }else{ ?>
                <!--LIST:currency-->
                    <th>[[|currency.id|]]</th>
                <!--/LIST:currency-->
                <?php } ?>
            <!--/LIST:payment_type-->
           
        </tr>
        <?php 
        $total_money_folio=0; 
        $total_money_room=0;
        $total_money_extra_bed=0;
        $total_money_baby_cot=0;
        $total_money_tour=0;
        $total_money_extra_service=0;
        $total_money_telephone=0;
        $total_money_minibar=0;
        $total_money_laundry=0;
        $total_money_equip=0;
        $total_money_restaurant=0;
        $total_money_spa=0;
        $total_money_banquet=0;
        $total_money_karaoke=0;
        $total_money_vending=0;
        $total_money_ticket=0;
        $total_money_deposit = 0;
        ?>
        <!--LIST:invoice-->
            <?php $count=0; ?>
            <!--LIST:items-->
                <?php if([[=invoice.folio_id=]]==[[=items.folio_id=]])
                        {
                            
                            $count++; 
                            if($count==1){       
                ?>
                <tr>
                    <td rowspan="[[|invoice.num|]]">[[|invoice.id|]]</td>
                    <td rowspan="[[|invoice.num|]]"><a target="_blank" href="[[|invoice.link|]]"><!--[[|invoice.folio_id|]]-->
                    <!-- oanh add -->
                        <?php if(isset([[=invoice.folio_code=]]) and ([[=invoice.folio_code=]])){?>
                            <?php echo 'No.F'.str_pad([[=invoice.folio_code=]],6,"0",STR_PAD_LEFT);?>
                        <?php } else {?>
                            <?php echo 'Ref'.str_pad([[=invoice.folio_id=]],6,"0",STR_PAD_LEFT);?>
                        <?php }?>   
                    <!-- oanh add --> 
                    </a></td>
                    <td rowspan="[[|invoice.num|]]"><a target="_blank" href="?page=reservation&layout=list&cmd=edit&id=<?php echo [[=invoice.reservation_id=]]?>">[[|invoice.reservation_id|]]</a></td>
                    <td rowspan="[[|invoice.num|]]" style="text-align: left; padding-left: 5px; width: 200px;">
                        [[.customer_name.]]: [[|invoice.customer_name|]] 
                        [[.traveller_name.]]: <?php echo isset([[=invoice.traveller_name=]]) && trim([[=invoice.traveller_name=]])!=""? '<b>'.[[=invoice.traveller_name=]].'</b>':''?>
                    </td>
                    <!--<td rowspan="[[|invoice.num|]]">[[|invoice.room_name|]]</td>
                    <td rowspan="[[|invoice.num|]]"><?php //echo ([[=items.time_in=]]==0?'':date('d/m/Y',[[=items.time_in=]]));?><br/><?php echo ([[=items.time_out=]]==0?'':date('d/m/Y',[[=items.time_out=]]));?></td>
                    <td><?php //echo System::Display_number(round(([[=items.time_out=]]-[[=items.time_in=]])/(24*6400)));?></td>
                    <td style="text-align: right;"><?php //echo System::Display_number(round(([[=items.total_room=]])/([[=items.departure_time=]]-[[=items.arrival_time=]])));?></td>-->
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_room +=[[=items.total_room=]]; echo System::display_number(round([[=items.total_room=]])); ?></td>
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_extra_bed+=[[=items.extra_bed=]]; echo System::display_number(round([[=items.extra_bed=]])); ?></td>
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_baby_cot +=[[=items.baby_cot=]]; echo System::display_number(round([[=items.baby_cot=]])); ?></td>
            
					<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_tour +=[[=items.tour=]]; echo System::display_number(round([[=items.tour=]])); ?></td>
					<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_extra_service +=[[=items.extra_service=]]; echo System::display_number(round([[=items.extra_service=]])); ?></td>
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_telephone +=[[=items.telephone=]]; echo System::display_number(round([[=items.telephone=]])); ?></td>
					
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_minibar +=[[=items.minibar=]]; echo System::display_number(round([[=items.minibar=]])); ?></td>
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_laundry +=[[=items.laundry=]]; echo System::display_number(round([[=items.laundry=]])); ?></td>
					<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_equip +=[[=items.equip=]]; echo System::display_number(round([[=items.equip=]])); ?></td>
					
					<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_restaurant +=[[=items.restaurant=]]; echo System::display_number(round([[=items.restaurant=]])); ?></td>
                    <!---<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_banquet +=[[=items.banquet=]]; echo System::display_number(round([[=items.banquet=]])); ?></td>
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_karaoke +=[[=items.karaoke=]]; echo System::display_number(round([[=items.karaoke=]])); ?></td>--->
                    
					<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_spa +=[[=items.spa=]]; echo System::display_number(round([[=items.spa=]])); ?></td>
                    <!---<td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_vending +=[[=items.vending=]]; echo System::display_number(round([[=items.vending=]])); ?></td>
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php $total_money_ticket +=[[=items.ticket=]]; echo System::display_number(round([[=items.ticket=]])); ?></td>--->
					
                    <td rowspan="[[|invoice.num|]]" class="hidden_col" style="text-align: right;"><?php 
                    if(isset([[=items.deposit=]]))
                    {
                        $total_money_deposit +=[[=items.deposit=]];
                        echo System::display_number([[=items.deposit=]]);
                    }
                    else 
                    echo '';
                     ?>
                     </td>
                    <td rowspan="[[|invoice.num|]]" style="text-align: right;">
                    <?php
                    $total_money_folio +=[[=items.total=]];
                    echo System::display_number([[=items.total=]]);
                    ?>
                    </td>
                    <!--LIST:payment_type-->
                        <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
                        <!--LIST:currency-->
                        <?php if(([[=currency.id=]]=='VND')){ ?>
                        <th style="text-align: right;">
                            <?php
                                if(([[=currency.id=]]==[[=items.currency_id=]]) AND ([[=payment_type.id=]]==[[=items.payment_type_id=]]))
                                    echo System::display_number([[=items.amount=]]); 
                            ?>
                        </th>
                        <?php } ?>
                        <!--/LIST:currency-->
                        <?php }else{ ?>
                        <!--LIST:currency-->
                        <th style="text-align: right;">
                            <?php
                                if(([[=currency.id=]]==[[=items.currency_id=]]) AND ([[=payment_type.id=]]==[[=items.payment_type_id=]]))
                                    echo System::display_number([[=items.amount=]]); 
                            ?>
                        </th>
                        <!--/LIST:currency-->
                        <?php } ?>
                    <!--/LIST:payment_type-->
                  
                    <td>[[|items.user_id|]]</td>
                </tr>
                <?php
                                        }
                                        else
                                        {
                ?> 
                <tr>
                    <!--<td>[[|items.time|]]</td>-->
                    <!--LIST:payment_type-->
                        <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
                        <!--LIST:currency-->
                        <?php if(([[=currency.id=]]=='VND')){ ?>
                        <th style="text-align: right;"><?php
                            if(([[=currency.id=]]==[[=items.currency_id=]]) AND ([[=payment_type.id=]]==[[=items.payment_type_id=]]))
                                echo System::display_number([[=items.amount=]]); 
                            ?>
                        </th>
                        <?php } ?>
                        <!--/LIST:currency-->
                        <?php }else{ ?>
                        <!--LIST:currency-->
                        <th style="text-align: right;"><?php
                            if(([[=currency.id=]]==[[=items.currency_id=]]) AND ([[=payment_type.id=]]==[[=items.payment_type_id=]]))
                                echo System::display_number([[=items.amount=]]); 
                            ?>
                        </th>
                        <!--/LIST:currency-->
                        <?php } ?>
                    <!--/LIST:payment_type-->
                    <td>[[|items.user_id|]]</td>
                </tr>    
                <?php   
                                        }
                        } 
                ?>
            <!--/LIST:items-->
        <!--/LIST:invoice-->
        <tr style="background: #dddddd;">
             <th colspan="4" rowspan="3" id="col_total" style="text-align: right; text-transform: uppercase;">[[.total.]]: </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_room));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_extra_bed));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_baby_cot));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_tour));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_extra_service));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_telephone));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_minibar));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_laundry));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_equip));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_restaurant));?> </th>
             <!---<th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_banquet));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_karaoke));?> </th>--->
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_spa));?> </th>
             <!---<th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_vending));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_ticket));?> </th>--->
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_deposit));?> </th>
             <th rowspan="3"  style="text-align: right;"><?php echo System::display_number(round($total_money_folio));?> </th>
             
            <!--LIST:payment_type-->
            <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
            <th>[[|payment_type.name|]]</th>
            <?php }else{ ?>
            <th colspan="<?php echo sizeof([[=currency=]]); ?>">[[|payment_type.name|]]</th>
            <?php } ?>
            <!--/LIST:payment_type-->
            
            <th rowspan="3"></th>
        </tr>
        <tr style="background: #dddddd;">
            <!--LIST:payment_type-->
                <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
                    <th>VND</th>
                <?php }else{ ?>
                <!--LIST:currency-->
                    <th>[[|currency.id|]]</th>
                <!--/LIST:currency-->
                <?php } ?>
            <!--/LIST:payment_type-->
         
        </tr>
        <tr style="background: #dddddd;">
            <!--LIST:payment_type-->
                <?php if([[=payment_type.id=]]=='FOC' OR [[=payment_type.id=]]=='DEBIT'){ ?>
                <!--LIST:currency-->
                <?php if([[=currency.id=]]=='VND'){ ?>
                <th style="text-align: right;">
                    <?php
                        if($this->map[[[=payment_type.id=]]."_".[[=currency.id=]]]>0)
                        echo System::display_number($this->map[[[=payment_type.id=]]."_".[[=currency.id=]]]); 
                    ?>
                </th>
                <?php } ?>
                <!--/LIST:currency-->
                <?php }else{ ?>
                <!--LIST:currency-->
                <th style="text-align: right;">
                    <?php
                        if($this->map[[[=payment_type.id=]]."_".[[=currency.id=]]]>0)
                        echo System::display_number($this->map[[[=payment_type.id=]]."_".[[=currency.id=]]]); 
                    ?>
                </th>
                <!--/LIST:currency-->
                <?php } ?>
            <!--/LIST:payment_type-->
            
        </tr>
    </table>
</td>
</tr>
<tr id="footer">
<td>
<!-- Oanhbtk add -->
    <table style="width:100%;height:120px;margin-top:15px;">
        <tr valign="top">
    		<td width="33%" align="center"><b>[[.creator.]]</b></td>
    		<td width="33%" align="center"><b>[[.cm_truongbophan.]]</b></td>
    		<td width="33%" align="center"><b>[[.cm_acountting.]]</b></td>
    	</tr>
	</table>
<!-- End Oanhbtk -->
</td>
</tr>
</table>
<script type="text/javascript">
jQuery(document).ready(function(){
    <?php
    if(isset($_REQUEST)){
        if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !=''){
            ?>
            document.getElementById("search_time").checked = false;
            jQuery("#from_date").addClass('b_blur').attr('value','');
                jQuery("#to_date").addClass('b_blur').attr('value','');
                 jQuery("#from_time").addClass('b_blur').attr('value','');
                jQuery("#to_time").addClass('b_blur').attr('value','');
            <?php
        }
    
    }
    ?>
        if(jQuery("#search_invoice").is(":checked")){
                  jQuery("#from_code").removeClass();
                  jQuery("#from_code").attr('readonly',false);
                  jQuery("#to_code").removeClass();
                  jQuery("#to_code").attr('readonly',false);
                  jQuery("#recode").removeClass();
                  jQuery("#recode").attr('readonly',false);
    };
    
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
		jQuery("#from_date").addClass('b_blur').attr('value','');
		jQuery("#to_date").addClass('b_blur').attr('value','');
		 jQuery("#from_time").addClass('b_blur').attr('value','');
		jQuery("#to_time").addClass('b_blur').attr('value','');
		  jQuery("#from_code").removeClass();
		  jQuery("#from_code").attr('readonly',false);
          jQuery("#recode").removeClass();
		  jQuery("#recode").attr('readonly',false);
		  jQuery("#to_code").removeClass();
		  jQuery("#to_code").attr('readonly',false);
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#to_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#from_code").addClass('b_blur').attr('readonly',true).attr('value','');
            jQuery("#recode").addClass('b_blur').attr('readonly',true).attr('value','');
             jQuery("#to_code").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#recode").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#recode").attr('readonly',true);
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
            jQuery("#from_code").removeClass();
            jQuery("#to_code").removeClass();
            jQuery("#recode").removeClass();
             jQuery("#from_code").attr('readonly',false);
            jQuery("#to_code").attr('readonly',false);
            jQuery("#recode").attr('readonly',false);
	   jQuery("#from_date").addClass('b_blur').attr('value','');
            jQuery("#to_date").addClass('b_blur').attr('value','');
            jQuery("#from_time").addClass('b_blur').attr('value','');
            jQuery("#to_time").addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").attr('value','00:00');
			jQuery("#to_time").attr('value','<?php echo date('H:i');?>');
            jQuery("#from_code").addClass('b_blur').attr('readonly',true).attr('value','');
            jQuery("#recode").addClass('b_blur').attr('readonly',true).attr('value','');
             jQuery("#to_code").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#recode").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#recode").attr('readonly',true);
            jQuery("#to_code").attr('readonly',true);
            jQuery("#from_code").attr('value','');
            jQuery("#recode").attr('value','');
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

    $('portal_id').value = '<?php echo PORTAL_ID;?>';
    jQuery('#from_time').mask("99:99");
    jQuery('#to_time').mask("99:99");
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1]-1,mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1]-1,mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
	    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
		var from_code = jQuery("#from_code").val();
        var to_code = jQuery("#to_code").val();
        var recode = jQuery("#recode").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code=='' && recode==''){
                   
                    alert('[[.empty_report.]]');
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
                if(jQuery("#from_code").val()!='' && jQuery("#to_code").val()!='')
                {
                    if( to_numeric(jQuery("#from_code").val())<=to_numeric(jQuery("#to_code").val()) )
                    {
                        return true;
                    }
                    else
                    {
                        
                        alert('[[.the_bill_number_is_not_valid_try_again.]]');
                        return false;
                    }
                }
                else
                {  
                    return true; 
                }
            }
        }
    }
    
 //binh add chuc nang an cot chi tiet
 jQuery('#check_hidden_col').click(function(){
    if (jQuery(this).is(":checked")){
        
        jQuery('.hidden_col').css('display','none');
        jQuery('#container_table').css({'width':'99%','margin':'10px auto'});
        ///jQuery('#col_total').attr('colspan','4');
    }else{
        jQuery('.hidden_col').css('display','');
         jQuery('#container_table').css({'width':'','margin':'0'});
         //jQuery('#col_total').attr('colspan','20');
    }
 }); 
 //giap.ln add truong hop in full ca trang  
 if(jQuery('#check_hidden_col').is(":checked")==false)
 {
    document.getElementById('container_table').style.fontSize='10px';
 }
 //end giap.ln
 
 
</script>

<script type="text/javascript"> 
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#export").remove();
            jQuery("#search").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
             location.reload();
        });
    });
    // 7211
    var users = <?php echo String::array2js([[=users=]]);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#user_id').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
</script>
