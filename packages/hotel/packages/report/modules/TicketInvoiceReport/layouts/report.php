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
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.ticket_invoice_report.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php echo [[=from_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=from_date=]] ?> - <?php echo [[=to_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
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
                            <table style="margin: 0 auto">
                                <tr>        
                                    <td>[[.line_per_page.]]</td>
                                    <td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                    <td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date"/></td>
                                    <td>[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date"/></td>
                    			    <td>[[.by_user.]]</td>
                    			    <td><select name="user_id" id="user_id"></select></td>
                                    <td>[[.from_time.]]</td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 50px;"/></td>
                                    <td>[[.to_time.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>
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
<!--IF:check_room([[=real_ticket_count=]])-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
	    <th width="10px" rowspan="2" class="report-table-header">[[.order_id.]]</th>
        <th width="50px" rowspan="2" class="report-table-header">[[.vat_code.]]</th>
        <th width="100px" rowspan="2" class="report-table-header">[[.type_ticket.]]</th>
        <th width="120px" rowspan="2" class="report-table-header">[[.quantity.]]</th>
        <th width="40px" rowspan="2" class="report-table-header" >[[.foc.]]</th>  
        <th width="60px" rowspan="2" class="report-table-header">[[.ticket_total_before_tax.]]</th>
        <th width="60px" rowspan="2" class="report-table-header">[[.tax_rate.]]</th>
        <th width="60px" rowspan="2" class="report-table-header">[[.ticket_total.]]</th>
        <th width="60px" rowspan="2" class="report-table-header">[[.invoice_total.]]</th>
        <th colspan="<?php echo ($i);?>" class="report-table-header">[[.payment.]]</th>
        <th width="60px" rowspan="2" class="report-table-header">[[.with_room.]]</th>  
        <th width="60px" rowspan="2" class="report-table-header">[[.deposit.]]</th>   
           
	</tr>
    <tr>
    <!--LIST:payment_types-->
    
        <?php if([[=payment_types.def_code=]]!='FOC'){?>
        	<th width="60px" class="report-table-header">[[|payment_types.name|]]</th>
         <?php } ?>
    <!--/LIST:payment_types-->
    </tr>
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
    <!--LIST:items-->
	<tr bgcolor="white">
        <?php
            $k = $this->map['count_ticket'][[[=items.code=]]]['num'];
            
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <a href="<?php echo Url::build('ticket_invoice_group',array('cmd'=>'edit','id'=>[[=items.code=]]));?>" target="_blank">[[|items.code|]]</a>
        </td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            &nbsp;    
        </td>
        <?php
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?> 
        <td class="report_table_column" style="text-align: left;">[[|items.name|]]</td>
        <td align="center" class="report_table_column">[[|items.quantity|]]</td>
        <?php if([[=items.foc=]]==1){?>
		  <td align="center" class="report_table_column">Y</td>
        <?php }else{ ?>
            <td align="center" class="report_table_column">N</td>
        <?php } ?>
            <td align="center" class="report_table_column"><?php echo System::display_number([[=items.ticket_total_before_tax=]]);?></td>
            <td align="center" class="report_table_column"><?php echo System::display_number([[=items.tax_rate=]]);?></td>
            <td align="center" class="report_table_column"><?php echo System::display_number([[=items.ticket_total=]]);?></td>
        <?php
           $i++ ;
           }
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number([[=items.invoice_total=]]);?></td>
        <!--LIST:payment_types-->
         <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
					<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.credit_card=]]==0?'':System::Display_number([[=items.credit_card=]]));?></td>	
         <?php }else if([[=payment_types.def_code=]]=='CASH'){?>
                    <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.cash=]]==0?'':System::Display_number([[=items.cash=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
                    <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.debit=]]==0?'':System::Display_number([[=items.debit=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.bank=]]==0?'':System::Display_number([[=items.bank=]]));?></td>
        <?php } ?>       
        <!--/LIST:payment_types-->
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.room=]]==0?'':System::display_number([[=items.room=]]));?></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.deposit=]]==0?'':System::display_number([[=items.deposit=]]));?></td>
        
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
    <!--Nếu lọc dữ liệu thì ticket_count != real_ticket_count-->
    <!--IF:check(([[=ticket_count=]]!=[[=real_ticket_count=]]) && (([[=real_page_no=]]==[[=real_total_page=]])))-->
	<tr bgcolor="white">
		<td colspan="3" class="report_table_column"><strong>[[.total.]]</strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_quantity=]]); ?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_ticket_total_before_tax=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_tax_rate=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_ticket_total=]]); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_invoice_total=]]); ?></strong></td>
        <!--LIST:payment_types-->
        <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
					<td align="center" class="report_table_column"><strong><?php echo ([[=total_credit_total=]]==0?'':System::Display_number([[=total_credit_total=]]));?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='CASH'){?>
                    <td align="center" class="report_table_column"><strong><?php echo ([[=total_cash_total=]]==0?'':System::Display_number([[=total_cash_total=]]));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
                    <td align="center" class="report_table_column"><strong><?php echo ([[=total_debit_total=]]==0?'':System::Display_number([[=total_debit_total=]]));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="center" class="report_table_column"><strong><?php echo ([[=total_bank_total=]]==0?'':System::Display_number([[=total_bank_total=]]));?></strong></td>
        <?php } ?>    
        <!--/LIST:payment_types-->
        <td align="center" class="report_table_column"><strong><?php echo ([[=total_room_total=]]==0?'':System::display_number([[=total_room_total=]])); ?></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo ([[=total_deposit_total=]]==0?'':System::display_number([[=total_deposit_total=]])); ?></strong></td>
	</tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
    		<td colspan="3" class="report_table_column"><strong>[[.total.]]</strong></td>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_quantity=]]); ?></strong></td>
            <td>&nbsp;</td>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_ticket_total_before_tax=]]); ?></strong></td>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_tax_rate=]]); ?></strong></td>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_ticket_total=]]); ?></strong></td>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=total_invoice_total=]]); ?></strong></td>  
            <!--LIST:payment_types-->
            <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
    					<td align="center" class="report_table_column"><strong><?php echo ([[=total_credit_total=]]==0?'':System::Display_number([[=total_credit_total=]]));?></strong></td>	
             <?php }else if([[=payment_types.def_code=]]=='CASH'){?>
                        <td align="center" class="report_table_column"><strong><?php echo ([[=total_cash_total=]]==0?'':System::Display_number([[=total_cash_total=]]));?></strong></td>
             <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
                        <td align="center" class="report_table_column"><strong><?php echo ([[=total_debit_total=]]==0?'':System::Display_number([[=total_debit_total=]]));?></strong></td>
             <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                        <td align="center" class="report_table_column"><strong><?php echo ([[=total_bank_total=]]==0?'':System::Display_number([[=total_bank_total=]]));?></strong></td>
            <?php } ?>    
            <!--/LIST:payment_types-->
            <td align="center" class="report_table_column"><strong><?php echo ([[=total_room_total=]]==0?'':System::Display_number([[=total_room_total=]]));?></strong></td>
            <td align="center" class="report_table_column"><strong><?php echo ([[=total_deposit_total=]]==0?'':System::Display_number([[=total_deposit_total=]]));?></strong></td>
           

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
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
    }
);
</script>