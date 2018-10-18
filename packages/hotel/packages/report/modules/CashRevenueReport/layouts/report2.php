<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
-->
</style>
<link rel="stylesheet" href="skins/default/report.css">
<div class="report-bound">
<div>
<table cellpadding="2" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
		<tr valign="top">
			<td align="left" width="60%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			<td align="right" nowrap width="40%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
    <!--IF:cond_customer([[=customer=]])-->
    <!--LIST:customer-->
	<div class="report_title" style="text-transform:uppercase"><?php if(Url::get('payment_type')=='CASH'){echo Portal::language('CASH_REVENUE_REPORT');}else{echo Portal::language('CREDIT_REVENUE_REPORT'); }?></div>
    <div style="font-weight:bold;">
    <?php Report::display_date_params();?>	
    	<div>[[.MST.]] : [[|customer.tax_code|]]</div>
    </div>
<?php
$cols = 0;
if(HAVE_KARAOKE)
{
	$cols++;
}
if(HAVE_MASSAGE)
{
	$cols++;
}
if(HAVE_TENNIS)
{
	$cols++;
}
if(HAVE_SWIMMING)
{
	$cols++;
}
?>
<div align="right"><em>&#272;&#417;n v&#7883; t&iacute;nh: <?php echo HOTEL_CURRENCY;?></em>&nbsp;</div>
<!---------REPORT----------->	
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th rowspan="2" width="1%" align="center"  class="report_table_header" >[[.stt.]]</th>
		<th rowspan="2" width="1%" align="center"  class="report_table_header" >[[.code.]]</th>
		<th rowspan="2" class="report_table_header" width="15%">[[.room_info.]]</th>
		<th rowspan="2" width="10%" class="report_table_header">[[.arrival_time.]] <br /> 
	    [[.departure_time.]]</th>
		<th  colspan="<?php echo (7+$cols);?>" align="center">[[.revenue.]]</th>
        <th rowspan="2" width="7%"  class="report_table_header">[[.total.]]</th>
        <th rowspan="2" width="7%"  class="report_table_header">[[.note.]]</th>
	</tr>
	<tr>
		<th width="7%" class="report_table_header" >[[.room_total.]]</th>		
		<th width="7%" class="report_table_header">[[.restaurant_total.]]</th>
		<th width="7%" class="report_table_header">[[.minibar_total.]]</th>
		<th width="7%" class="report_table_header">[[.laundry_total.]]</th>
		<th width="7%" class="report_table_header">[[.compensation_total.]]</th>
		<!--IF:cond(HAVE_KARAOKE)--><th width="7%"class="report_table_header">[[.total_karaoke.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><th width="7%"class="report_table_header">[[.total_massage.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><th width="7%"class="report_table_header">[[.total_tennis.]]</th><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><th width="7%"class="report_table_header">[[.swimming.]]</th><!--/IF:cond-->
		<th width="7%" class="report_table_header">[[.other_service.]]</th>
	  	<th width="7%" class="report_table_header">[[.extra_service.]]</th>		
	</tr>
<!---------GROUP----------->
<!--LIST:customer.items-->

<!---------CELLS----------->
	<tr bgcolor="white">
		<td align="center" valign="top"  class="report_table_column">[[|customer.items.stt|]]</td>
		<td align="center" valign="top"  class="report_table_column">[[|customer.items.id|]]</td>
		<td align="left" valign="top" class="report_table_column"><div><strong>[[|customer.items.room_name|]]</strong></div>
		  <div>[[.customer_stay.]]: [[|customer.items.customer_stay|]]</div>
          <!--IF:cond_minibar([[=customer.items.minibar_product=]])--><div><strong>[[.Minibar.]] :</strong><br />
          	<!--LIST:customer.items.minibar_product-->
            <div>[[|customer.items.minibar_product.name|]] (<strong>[[|customer.items.minibar_product.quantity|]]</strong>)</div>
          	<!--/LIST:customer.items.minibar_product-->            
          </div><!--/IF:cond_minibar-->
          <!--IF:cond_laundry([[=customer.items.laundry_product=]])--><div><strong>[[.Laundry.]] :</strong><br />
          	<!--LIST:customer.items.laundry_product-->
            <div>[[|customer.items.laundry_product.name|]] (<strong>[[|customer.items.laundry_product.quantity|]]</strong>)</div>
          	<!--/LIST:customer.items.laundry_product-->
          </div><!--/IF:cond_laundry-->
          </td>
		<td align="center" valign="top" nowrap class="report_table_column"><?php echo Date_Time::convert_orc_date_to_date([[=customer.items.arrival_time=]],'/');?><br />
	    <?php echo Date_Time::convert_orc_date_to_date([[=customer.items.departure_time=]],'/');?></td>
		<td align="right" valign="top"  class="report_table_column"><?php echo System::display_number(([[=customer.items.room_total=]]-[[=customer.items.extra_service_total=]]));?></td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.restaurant_total|]]</td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.minibar_total|]]</td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.laundry_total|]]</td>
		<td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.equip_total|]]</td>
		<!--IF:cond(HAVE_KARAOKE)--><td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.karaoke_total|]]</td><!--/IF:cond-->
		<!--IF:cond(HAVE_MASSAGE)--><td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.massage_total|]] </td><!--/IF:cond-->
		<!--IF:cond(HAVE_TENNIS)--><td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.tennis_total|]]</td><!--/IF:cond-->
		<!--IF:cond(HAVE_SWIMMING)--><td align="right" valign="top" nowrap class="report_table_column">[[|customer.items.swimming_total|]]</td><!--/IF:cond-->
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=customer.items.service_other_total=]]);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=customer.items.extra_service_total=]]);?></td>
		<td align="right" valign="top" nowrap class="report_table_column"><?php echo System::display_number([[=customer.items.total=]]);?></td>
        <td align="right" valign="top" nowrap class="report_table_column"><!--IF:cond([[=customer.items.note=]])--><div>[[.note.]]: [[|customer.items.note|]]</div><!--/IF:cond--></td>
	</tr>
<!--/LIST:customer.items-->  
	<tr>
		<td colspan="4" align="right" style="font-weight:bold;">[[.Total.]]</td>
        <td align="right"><?php echo System::display_number([[=customer.room_total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=customer.restaurant_total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=customer.minibar_total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=customer.laundry_total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=customer.equip_total=]]);?></td>
       	<!--IF:cond(HAVE_KARAOKE)--><td align="right"><?php echo System::display_number([[=customer.karaoke_total=]]);?></td><!--/IF:cond-->
        <!--IF:cond(HAVE_MASSAGE)--><td align="right"><?php echo System::display_number([[=customer.massage_total=]]);?></td><!--/IF:cond-->
        <!--IF:cond(HAVE_TENNIS)--><td align="right"><?php echo System::display_number([[=customer.tennis_total=]]);?></td><!--/IF:cond-->
        <!--IF:cond(HAVE_SWIMMING)--><td align="right"><?php echo System::display_number([[=customer.swimming_total=]]);?></td><!--/IF:cond--> 
        <td align="right"><?php echo System::display_number([[=customer.service_other_total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=customer.extra_service_total=]]);?></td>
        <td align="right"><?php echo System::display_number([[=customer.total=]]);?></td>
        <td></td>                                                            
    </tr>  			
</table>
    <table width="100%" style="font-family:'Times New Roman', Times, serif">
    <tr>
        <td></td>
        <td></td>
        <td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
    </tr>
    <tr valign="top">
        <td width="33%" align="center">[[.creator.]]</td>
        <td width="33%" align="center">[[.general_accountant.]]</td>
        <td width="33%" align="center">[[.director.]]</td>
    </tr>
    </table>
    <p>&nbsp;</p>
 </td>
</tr>
</table>
<!--/LIST:customer-->
<!--ELSE-->
<div style="padding:20px;">
	<h3>[[.no_result_matchs.]]</h3>
	<a href="<?php echo Url::build_current(array('type'));?>">[[.back.]]</a>
</div>
<!--/IF:cond_customer-->
</div></div>
<DIV style="page-break-before:always;page-break-after:always;"></DIV>
