<style>
    *{
        margin: 0px;
        padding: 0px;
    }
    .simple-layout-middle{
		width:100%;	
	}
    table#report tr td
    {
        text-align: center;
    }
    #chang_language
{
display: none;
}
</style>
<div id="button_print" style="text-align: right;">
    <a onclick="jQuery('#button_print').css('display','none');jQuery('#search_option').css('display','none'); printWebPart('printer');jQuery('#button_print').css('display','');jQuery('#search_option').css('display','');" title="In">
        <img src="packages/core/skins/default/images/printer.png" height="40" />
    </a>
</div>
<form name="RoomBillVatRevenueReportForm" method="post">
    <div style="width: 98%; height: auto; margin: 0px auto;">
        <table style="width: 100%; margin: 10px auto;">
            <tr>
                <td><?php echo HOTEL_NAME."<br />".HOTEL_ADDRESS; ?></td>
                <td style="text-align: right;">[[.sales_1.]]</td>
            </tr>
            <tr>
                <td colspan="2" class="report_title" style="text-align: center; text-transform: uppercase; line-height: 30px; font-size: 20px;">
                    [[.room_bill_vat_revenue_report.]]<br /><span style="line-height: 25px; text-transform: none; font-size: 14px;">[[.date.]][[|from_date|]]</span>
                </td>
            </tr>
        </table>
        <fieldset id="search_option" style="border: 1px solid #00b2f9;">
            <legend style="border: 1px solid #00b2f9; border-left: 3px solid #00b2f9; border-right: 3px solid #00b2f9; padding: 5px; width: 100px; text-align: center;">[[.option.]]</legend>
            <table>
                <tr>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.hotel.]]: <select name="portal_id" id="portal_id" style="width: 80px; border-bottom: 1px dashed #171717;"></select></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.user.]]: <select name="user_id" id="user_id" style="width: 80px; border-bottom: 1px dashed #171717;"></select></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.from_date.]]: <input name="from_time" type="text" id="from_time" style="width: 40px; border-bottom: 1px dashed #171717;" /> <input name="from_date" type="text" id="from_date" style="width: 80px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.to.]]: <input name="to_time" type="text" id="to_time" style="width: 40px; border-bottom: 1px dashed #171717;" /> <input name="to_date" type="text" id="to_date" style="width: 80px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.from_bill.]]: <input name="from_bill" type="text" id="from_bill" style="width: 40px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.to.]]: <input name="to_bill" type="text" id="to_bill" style="width: 40px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.line_per_page.]]: <input name="line_per_page" type="text" id="line_per_page" style="width: 20px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 3px; border-right: 1px solid #dddddd;">[[.no_of_page.]]: <input name="no_of_page" type="text" id="no_of_page" style="width: 20px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 3px;">[[.start_page.]]: <input name="start_page" type="text" id="start_page" style="width: 20px; border-bottom: 1px dashed #171717;" /></td>
                    <td style="padding: 0px 10px;"><input name="search" type="submit" value="[[.search.]]" style="background: #ffffff; padding: 5px; border: 1px solid #555555;" /></td>
                    <td style="padding: 0px 3px;"><input name="to_day" type="submit" value="[[.view_to_day.]]" style="background: #ffffff; padding: 5px; border: 1px solid #555555;" /></td>
                    
                </tr>
            </table>
        </fieldset>
        <table id="report" style="width: 100%; margin: 10px auto; border-collapse: 0;" cellpadding="0" cellspacing="0" border="1" bordercolor="#bbbbbb">
            <tr style="text-align: center;">
                <th style="width: 20px;" rowspan="2">[[.stt.]]</th>
                <th style="width: 80px;" rowspan="2">[[.invoice.]] VAT</th>
                <th style="width: 150px;" rowspan="2">[[.customer_name.]]</th>
                <!--<th rowspan="3">[[.time_print.]] VAT</th>
                <th style="width: 20px;"  rowspan="3">[[.invoice.]] FOLIO</th>
                <th rowspan="3">[[.information.]] FOLIO</th>-->
                <th colspan="<?php echo (sizeof([[=payment_type=]])-1); ?>">[[.payment.]]</th>
                <!--<th rowspan="3">[[.amount.]] FOLIO</th>-->
                <th rowspan="2">[[.amount_1.]] VAT</th>
                <th rowspan="2">[[.user_print.]]</th>
                <!--<th rowspan="3">[[.last_time_print.]]</th>
                <th rowspan="3">[[.last_user_print.]]</th>-->
            </tr>
            <tr style="text-align: center;">
                <!--LIST:payment_type-->
                    <?php if([[=payment_type.id=]]!='REFUND'){  ?>
                    <?php if([[=payment_type.id=]]!='CREDIT_CARD' AND [[=payment_type.id=]]!='DEBIT' AND [[=payment_type.id=]]!='FOC'){ ?>
                            <th colspan="<?php echo sizeof([[=currency=]]); ?>">[[|payment_type.name|]]</th>
                    <?php }else{ ?>
                            <th>[[|payment_type.name|]]</th>
                    <?php } ?>
                <!--/LIST:payment_type-->
            </tr>
            
                <?php } ?>
                <!--/LIST:payment_type-->
            </tr>
            <!-- report -->
            <?php $ij=0; ?>
            <!--LIST:items-->
                <?php $key=''; $key1 = ''; $content1=array(); $ij++; ?>
                <tr>
                    <td rowspan="[[|items.count_folio|]]">[[|items.stt|]]</td>
                    <td rowspan="[[|items.count_folio|]]">[[|items.code|]]</td>
                    <td style="text-align: center;" rowspan="[[|items.count_folio|]]">
                         [[|items.customer_name|]]
                    </td>
                    <!--<td rowspan="[[|items.count_folio|]]">[[|items.time_print|]]</td>
                    <?php foreach([[=items.child=]] as $key1=>$content1){ ?> 
                        <?php if($key1==1){ ?>
                        <td><?php echo $content1['id']; ?></td>
                        <td style="text-align: left;">
                             - [[.traveller_name.]]: <?php echo $content1['traveller_name']; ?> <br />
                             - [[.customer_name.]]: <?php echo $content1['customer_name']; ?> <br />
                             - [[.room_name.]]: <?php echo $content1['room_name']; ?> <br />
                             - [[.recode.]]: <?php echo $content1['reservation_id']; ?> <br />
                             - [[.create_date.]]: <?php echo date('H:i d/m/Y',$content1['create_time']); ?> 
                        </td>-->
                        <!--LIST:payment_type-->
                        <?php if([[=payment_type.id=]]!='REFUND'){  ?>
                            <?php if([[=payment_type.id=]]!='CREDIT_CARD' AND [[=payment_type.id=]]!='DEBIT' AND [[=payment_type.id=]]!='FOC'){ ?>
                                    <!--LIST:currency-->
                                    <td style="text-align: right; font-weight: bold;"><?php if($content1[[[=payment_type.id=]]."_".[[=currency.id=]]]!=0) echo System::display_number($content1[[[=payment_type.id=]]."_".[[=currency.id=]]]); ?></td>
                                    <!--/LIST:currency-->
                            <?php }else{ ?>
                                    <td style="text-align: right; font-weight: bold;"><?php if($content1[[[=payment_type.id=]]."_VND"]!=0) echo System::display_number($content1[[[=payment_type.id=]]."_VND"]); ?></td>
                            <?php } ?>
                        <?php } ?>
                        <!--/LIST:payment_type-->
                        <!--<td style="text-align: right; font-weight: bold;"><?php echo System::display_number($content1['total']); ?></td>-->
                        <?php } ?>
                    <?php } ?>
                    <td style="font-weight: bold; text-align: right;" rowspan="[[|items.count_folio|]]"><?php echo System::display_number([[=items.total_amount=]]); ?></td>
                    <td rowspan="[[|items.count_folio|]]">[[|items.user_print|]]</td>
                    <!--<td rowspan="[[|items.count_folio|]]">[[|items.last_time_print|]]</td>
                    <td rowspan="[[|items.count_folio|]]">[[|items.last_user_print|]]</td>-->
                </tr>
                <?php $key=''; $key1 = ''; $content1=array(); $ij++; ?>
                <?php if([[=items.count_folio=]]>1){ ?>
                    <?php foreach([[=items.child=]] as $key1=>$content1){ ?> 
                        <?php if($key1>1){ ?>
                        <tr>
                        <!--<td><?php echo $content1['id']; ?></td>
                        <td style="text-align: left;">
                             - [[.traveller_name.]]: <?php echo $content1['traveller_name']; ?> <br />
                             - [[.customer_name.]]: <?php echo $content1['customer_name']; ?> <br />
                             - [[.room_name.]]: <?php echo $content1['room_name']; ?> <br />
                             - [[.recode.]]: <?php echo $content1['reservation_id']; ?> <br />
                             - [[.create_date.]]: <?php echo date('H:i d/m/Y',$content1['create_time']); ?> 
                        </td>-->
                        <!--LIST:payment_type-->
                        <?php if([[=payment_type.id=]]!='REFUND'){  ?>
                            <?php if([[=payment_type.id=]]!='CREDIT_CARD' AND [[=payment_type.id=]]!='DEBIT' AND [[=payment_type.id=]]!='FOC'){ ?>
                                    <!--LIST:currency-->
                                    <td style="text-align: right; font-weight: bold;"><?php if($content1[[[=payment_type.id=]]."_".[[=currency.id=]]]!=0) echo System::display_number($content1[[[=payment_type.id=]]."_".[[=currency.id=]]]); ?></td>
                                    <!--/LIST:currency-->
                            <?php }else{ ?>
                                    <td style="text-align: right; font-weight: bold;"><?php if($content1[[[=payment_type.id=]]."_VND"]) echo System::display_number($content1[[[=payment_type.id=]]."_VND"]); ?></td>
                            <?php } ?>
                        <!--/LIST:payment_type-->
                        <?php } ?>
                        <!--<td style="text-align: right; font-weight: bold;"><?php echo System::display_number($content1['total']); ?></td>-->
                        </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                <?php //if($ij==4) break; ?>
            <!--/LIST:items-->
            <tr>
                <td colspan="3">[[.total.]]</td>
                <!--LIST:payment_type-->
                <?php if([[=payment_type.id=]]!='REFUND'){  ?>
                    <?php if([[=payment_type.id=]]!='CREDIT_CARD' AND [[=payment_type.id=]]!='DEBIT' AND [[=payment_type.id=]]!='FOC'){ ?>
                            <!--LIST:currency-->
                            <td style="text-align: right; font-weight: bold;"><?php if([[=group_function_params=]][[[=payment_type.id=]]."_".[[=currency.id=]]]!=0) echo System::display_number([[=group_function_params=]][[[=payment_type.id=]]."_".[[=currency.id=]]]); ?></td>
                            <!--/LIST:currency-->
                    <?php }else{ ?>
                            <td style="text-align: right; font-weight: bold;"><?php if([[=group_function_params=]][[[=payment_type.id=]]."_VND"]) echo System::display_number([[=group_function_params=]][[[=payment_type.id=]]."_VND"]); ?></td>
                    <?php } ?>
                <?php } ?>
                <!--/LIST:payment_type-->
                <td style="text-align: right; font-weight: bold;"> <?php echo System::display_number([[=group_function_params=]]['total_amout']); ?></td>
                <td colspan="1"></td>
            </tr>
        </table>
    </div>
</form>
<script>
    jQuery('#from_time').mask("99:99");
    jQuery('#to_time').mask("99:99");
    jQuery('#from_date').datepicker();
    jQuery('#to_date').datepicker();
</script>