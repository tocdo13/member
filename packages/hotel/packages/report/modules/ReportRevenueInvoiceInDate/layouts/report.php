<style>
    .simple-layout-middle{
		width:100%;	
	}
    #search table tr td{
        text-align: center;
        border-right: 1px solid #dddddd;
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
width: 65px;
margin-left: 6px;
}
.invoce_div{
    padding-top: 10px;
        margin-bottom: 12px;
    }
    .do_search{
        padding: 6px 8px 5px 8px;
      
        border-radius: 5px;
        border: none;
        margin-left: 10px;
    }
    .wrapper-items{
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
	.k_2{text-align:left;float:left;margin-top: 6px;border: 1px solid #cdcdcd;}
	.k_2 label{font-weight:100}
   .hidden_col{white-space: nowrap;}
   .bg_header{background:#dddddd;}
</style>
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
                <div class="report_title" style="text-transform:uppercase; font-size: 18px;">[[.room_order_revenue_report.]]</div>                
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
        <fieldset style="min-width: 80%;margin: 0 10%;text-align: center;margin-bottom: 15px;">
            <legend style="margin-left: 3%;">[[.search.]]</legend>
            <div style="text-align: center;display:inline-block;">
                <table style="float:left">
                    <tr>
                    <td>
                     <fieldset style="margin: 5px 0px;border: 1px solid #AEA8A8;width:177px;margin-right: 20px;">
                        <legend>
                        <input name="search_invoice" type="checkbox" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !='')echo 'checked="checked"';} ?>><label for="search_invoice">[[.search_by_bill.]]</label></legend>
                        <div class="invoce_div">
                            <label class="invoce_1">[[.bill_id.]]</label> 
                            <input name="from_code" type="text" id="from_code" style="width: 70px;" class="b_blur input_number" readonly="read" > 
                        </div>
                        <div>
                            <label class="invoce_1">[[.to.]]:</label>
                             <input name="to_code" type="text" id="to_code" style="width: 70px;" class="b_blur input_number" readonly="read" >
                        </div>    
                      </fieldset>
                    </td>
    				  </tr>
                    
                </table>
                    <div class="k_2">
    					<div style="">
    					<fieldset style="border:none;width:278px;display: inline-block;float: left;margin-right: 10px;">
                            <legend>
                            <input name="search_time" type="checkbox" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_time']) && $_REQUEST['search_time'] !='')echo 'checked="checked"'; } ?>><label for="search_time">[[.search_by_time.]]</label></legend>
                            <div class="invoce_div" style="width: 300px;">
                                <label class="invoce_2">[[.from_date.]]:</label>
                                 <input name="from_date" type="text" id="from_date" style="width: 80px;" onchange="changevalue();" />
                                  <label class="invoce_2">[[.from_time.]]:</label>
                                  <input name="from_time" type="text" id="from_time" style="width: 40px;" />
                            </div>
                            <div>
                                <label class="invoce_2">[[.to_date.]]:</label>
                                <input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();" /> 
                                 <label class="invoce_2">[[.to_time.]]:</label>
                                <input name="to_time" type="text" id="to_time" style="width: 40px;" />
                            </div> 
                          </fieldset>
                        </div>
                    </div>    
                <div class="clear"></div>              
                <div style="margin-top: 10px;">
                    <input name="do_search" style="margin-right: 20px; background: #80FF80; cursor: pointer;" type="submit" value="[[.search.]]" class="do_search" onclick="return check_search();"/>
                    <!-- Oanh add -->
                    <button class="do_search" style="background: #800040; color: white; cursor: pointer;" id="export">[[.export_file_excel.]]</button>
                    <!-- Edn oanh -->
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
    <div style="overflow: auto; width: inherit; display: block;">
        <table cellpadding="2" cellspacing="0" border="1" bordercolor="#cccccc" id="container_table" style="overflow: auto;">
            <tr class="bg_header">
                <th rowspan="3" style="white-space: nowrap;">[[.stt.]]</th>
                <th rowspan="3" style="width: 50px;white-space: nowrap;">[[.order_id.]]</th>
                <th rowspan="3" style="width: 50px;white-space: nowrap;">[[.Name_of_guests.]]</th>
                <th rowspan="3" style="width: 60px;white-space: nowrap;">[[.Room.]]</th>
                <th colspan="2">[[.Date.]]</th>
                <th rowspan="3" class="hidden_col">[[.R/N.]]</th>
                <th rowspan="3" style="width: 50px;" class="hidden_col">[[.room_rate.]]</th>
                <th rowspan="3" style="width: 50px;" class="hidden_col">[[.room_price_total.]]</th>
                <th rowspan="3" class="hidden_col">[[.minibar.]]</th>
                <th rowspan="3" class="hidden_col">[[.spa.]]</th>
                <th rowspan="3" class="hidden_col">[[.restaurant.]]</th>
                <th rowspan="3" class="hidden_col">[[.tour1.]]</th>
                <th rowspan="3" class="hidden_col">[[.laundry.]]</th>
                <th rowspan="3" class="hidden_col">[[.telephone.]]</th>
                <th rowspan="3" class="hidden_col">[[.extra_service.]]</th>
                <th rowspan="3" class="hidden_col">[[.EI.]]</th>
                <th rowspan="3" style="width: 50px;" class="hidden_col">[[.extra_bed_1.]]</th>
                <th rowspan="3" style="width: 50px;">[[.total.]]</th>
                <th rowspan="3">[[.deposit.]]</th>			
                <th colspan="<?php echo ((sizeof([[=payment_type=]])+1)*(sizeof([[=currency=]])))+1; ?>">[[.payment.]]</th>         
            </tr>
            <tr class="bg_header">
                <th rowspan="2">IN</th>
                <th rowspan="2">OUT</th>
                <!--LIST:payment_type-->
                <?php if([[=payment_type.id=]]=='FOC'){ ?>
                    <th style="white-space: nowrap;">[[|payment_type.name|]]</th>
                <?php }   
                else
                {
                ?>
                    <th colspan="<?php echo sizeof([[=currency=]]); ?>" style="white-space: nowrap;">[[|payment_type.name|]]</th>
                <?php 
                } 
                ?>
                <!--/LIST:payment_type-->
                <th colspan="<?php echo sizeof([[=currency=]]); ?>" style="white-space: nowrap;">LEDGER CITY</th>
            </tr>
            <tr class="bg_header">
                <!--LIST:payment_type-->
                    <?php 
                    if([[=payment_type.id=]]=='FOC')
                    { 
                    ?>
                        <th>VND</th>
                    <?php 
                    }else
                    { ?>
                    <!--LIST:currency-->
                        <th>[[|currency.id|]]</th>
                    <!--/LIST:currency-->
                    <?php } ?>
                <!--/LIST:payment_type-->
                <!--LIST:currency-->
                        <th>[[|currency.id|]]</th>
                <!--/LIST:currency-->
            </tr>
            <?php 
            $total_night = 0;
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
            $total_money_ei_lo=0;
            $total_money_extra_person=0;
            $total_money_deposit = 0;
            $total_rate = 0;
            $i=1;
                foreach($this->map['items'] as $key=>$value)
                {
            ?>
                <tr>
                        <td><?php echo $i; ?></td>
                        <td><a target="_blank" href="<?php echo $value['link']; ?>">
                        <!-- oanh add -->
                            <?php if(isset($value['folio_code'])){?>
                                <?php echo 'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);?>
                            <?php } else {?>
                                <?php echo 'Ref'.str_pad($value['folio_id'],6,"0",STR_PAD_LEFT);?>
                            <?php }?>   
                        <!-- oanh add --> 
                        </a></td>
                        <td style="text-align: left; padding-left: 5px; white-space: nowrap;">
                            <?php echo isset($value['traveller_name']) && trim($value['traveller_name'])!=""? '<b>'.$value['traveller_name'].'</b>'.'('.$value['recode'].')<br/>':'<br/>'?>
                        </td>
                        <td><?php echo $value['room_name']; ?></td>
                        <td><?php echo date('d/m/Y',$value['time_in']); ?></td>
                        <td><?php echo date('d/m/Y',$value['time_out']); ?></td>
                        <td class="hidden_col"><?php $total_night+=$value['night']; echo $value['night']; ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_rate +=$value['rate']; echo System::display_number(round($value['rate'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_room +=$value['total_room']; echo System::display_number(round($value['total_room'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_minibar +=$value['minibar']; echo System::display_number(round($value['minibar'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_spa +=$value['spa']; echo System::display_number(round($value['spa'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_restaurant +=$value['restaurant']; echo System::display_number(round($value['restaurant'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_tour +=$value['tour']; echo System::display_number(round($value['tour'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_laundry +=$value['laundry']; echo System::display_number(round($value['laundry'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_telephone +=$value['telephone']; echo System::display_number(round($value['telephone'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_extra_service +=$value['extra_service']; echo System::display_number(round($value['extra_service'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_ei_lo +=$value['ei_lo']; echo System::display_number(round($value['ei_lo'])); ?></td>
                        <td class="hidden_col" style="text-align: right;"><?php $total_money_extra_bed+=$value['extra_bed']; echo System::display_number(round($value['extra_bed'])); ?></td>
                        <td style="text-align: right;">
                            <?php
                            $total_money_folio +=$value['total'];
                            echo System::display_number($value['total']);
                            ?>
                        </td>
    					<td style="text-align: right;">
                            <?php 
                                if(isset($value['deposit']))
                                {
                                    $total_money_deposit +=$value['deposit'];
                                    echo System::display_number($value['deposit']);
                                }
                                else 
                                echo '';
                             ?>
                         </td>        
                         <?php
                            foreach($this->map['payment_type'] as $k=>$v){
                                foreach($this->map['currency'] as $k2=>$v2){
                                    if($k=='FOC' && $k2!='VND')
                                    {
                                        continue;
                                    }
                                    if($value['total_payment_'.$k."_".$k2]>0){
                          ?>
                                            <td style="text-align: right;"><?php echo System::display_number($value['total_payment_'.$k."_".$k2]); ?></td>
                          <?php                                                                      
                                    }
                                    else{   
                         ?>
                                    <td style="text-align: right;"></td>    
                         <?php               
                                   }
                                    
                                }     
                            }
                            foreach($this->map['currency'] as $k2=>$v2){
                                if($value['payment_type_id']=='DEBIT' || $value['payment_type_id']=='BANK')
                                {
                                    if($value['currency_id']==$k2){
                         ?> 
                                    <td style="text-align: right;"><?php echo System::display_number($value['total_payment_LEDGER_'.$k2]); ?></td>
                         <?php   
                                    }
                                    else{
                          ?>
                                    <td></td>
                          <?php     
                                    }
                                }
                                else{
                          ?>
                                    <td></td>
                          <?php          
                                }
                            }    
                         ?>                                                                                                             
                </tr>
            <?php    
                    $i++;    
                }
            ?>
            <tr style="background: #dddddd;">
                 <th colspan="6" rowspan="3" id="col_total" style="text-align: right; text-transform: uppercase;">[[.total.]]: </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_night));?> </th>
                 <th rowspan="3" class="hidden_col">&nbsp;</th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_room));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_minibar));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_spa));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_restaurant));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_tour));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_laundry));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_telephone));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_extra_service));?> </th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_ei_lo)); ?></th>
                 <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_extra_bed)); ?></th>
                 <th rowspan="3" style="text-align: right;"><?php echo System::display_number(round($total_money_folio));?> </th>        
                 <th rowspan="3" style="text-align: right;"><?php echo System::display_number(round($total_money_deposit));?> </th>
                <!--LIST:payment_type-->
                <?php if([[=payment_type.id=]]=='FOC'){ ?>
                    <th style="white-space: nowrap;">[[|payment_type.name|]]</th>
                <?php }   
                else
                {
                ?>
                    <th colspan="<?php echo sizeof([[=currency=]]); ?>" style="white-space: nowrap;">[[|payment_type.name|]]</th>
                <?php 
                } 
                ?>
                <!--/LIST:payment_type-->
                <th colspan="<?php echo sizeof([[=currency=]]); ?>" style="white-space: nowrap;">LEDGER CITY</th>
            </tr>
            <tr class="bg_header">
                <!--LIST:payment_type-->
                    <?php 
                    if([[=payment_type.id=]]=='FOC')
                    { 
                    ?>
                        <th>VND</th>
                    <?php 
                    }else
                    { ?>
                    <!--LIST:currency-->
                        <th>[[|currency.id|]]</th>
                    <!--/LIST:currency-->
                    <?php } ?>
                <!--/LIST:payment_type-->
                <!--LIST:currency-->
                        <th>[[|currency.id|]]</th>
                <!--/LIST:currency-->
            </tr>
            <tr style="background: #dddddd;">
                <!--LIST:payment_type-->
                    <?php if([[=payment_type.id=]]=='FOC'){ ?>
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
                    <?php }
                    else if([[=payment_type.id=]]!='DEBIT' && [[=payment_type.id=]]!='BANK')
                    { ?>
                    <!--LIST:currency-->
                    <th style="text-align: right;">
                        <?php
                            if($this->map[[[=payment_type.id=]]."_".[[=currency.id=]]]>0)
                            echo System::display_number($this->map[[[=payment_type.id=]]."_".[[=currency.id=]]]); 
                        ?>
                    </th>
                    <!--/LIST:currency-->
                    <?php 
                    }
                    ?>  
                <!--/LIST:payment_type-->   
                    <!--LIST:currency-->
                        <th style="text-align: right;">
                            <?php
                                if($this->map["total_LEDGER_".[[=currency.id=]]]>0)
                                echo System::display_number($this->map["total_LEDGER_".[[=currency.id=]]]); 
                            ?>
                        </th>
                      <!--/LIST:currency-->        
            </tr>
        </table>
    </div>
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

<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    jQuery('#from_time').mask("99:99");
    jQuery('#to_time').mask("99:99");
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
        var width = jQuery("table#tblExport").width();
        jQuery("div.simple-layout-bound").width(width);
        jQuery("#export").click(function () {
            jQuery("#export").remove();
            jQuery("#search").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
             location.reload();
        });
        
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
            }else if(jQuery("#search_time").is(":checked")){
    			jQuery("#from_date").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
    			jQuery("#to_date").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
    			jQuery("#from_time").removeClass('b_blur').attr('value','00:00');
    			jQuery("#to_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
                jQuery("#from_code").addClass('b_blur').attr('readonly',true).attr('value','');
                 jQuery("#to_code").addClass('b_blur').attr('readonly',true).attr('value','');
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
                jQuery("#from_code").removeClass();
                jQuery("#to_code").removeClass();
                 jQuery("#from_code").attr('readonly',false);
                jQuery("#to_code").attr('readonly',false);
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
                 jQuery("#to_code").addClass('b_blur').attr('readonly',true).attr('value','');
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
</script>
