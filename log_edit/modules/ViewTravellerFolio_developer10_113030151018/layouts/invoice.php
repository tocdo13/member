<style>
.description,.date,.amount{
	border-bottom:1px solid #f1f1f1;	
}
.description-payment{
	width:35%;
}
#ivoice tr th
{
    font-family:"Times New Roman", Times, serif !important;
    font-size:14px;
}
@media print
{

}
#invoice{
	width:100%;
}
#invoice a,#invoice table,#invoice td,#invoice div,#invoice span,#invoice h{
	font-family:"Times New Roman", Times, serif;
	font-size:14px;
}
#invoice .invoice-title{
	font-size:18px;
	font-weight:bold;
	color:#333333;
	margin:0px;
	padding:0px;
}
#invoice .invoice-sub-title{
	font-size:14px;
	font-weight:bold;	
	color:#333333;
	margin:0px;
	padding:0px;
}
#invoice .invoice-contact-info{
	color:#666666;
	font-size:12px;
}
a.report_link{
	font-weight:100;
	color:#000000;
	text-decoration:none;
}
a.report_link:hover{
	font-weight:100;
	color:#FF6600;
	text-decoration:underline;
}
.data_title {
	font-weight:500;
}
.genneral-info-table{
}
.seperator-line{
	border-bottom:1px solid #000000;
	float:left;
	width:100%;
	margin-bottom:10px;
}
/*----------------------------------------------------------------------*/
.item-header{
	float:left;
	width:100%;
	font-weight:bold;
	margin-bottom:10px;
}
.date{
	float:left;
	width:15%;
	text-align:left;
	padding:2px 2px 2px 5px;
}
.description{
	float:left;
	width:45%;
	text-align:left;
	padding:2px 2px 2px 5px;
}
.amount{
	float:left;
	width:30%;
	text-align:right;
	padding:2px 0px 2px 0px;
}
.item-body{
	float:left;
	width:100%;
}
.sub-item-body{
	float:left;
	width:100%;
}
.item-body.total-group{
	border-top:1px solid #000000;
}
.item-body.total-group .sub-item-body .date,
.item-body.total-group .sub-item-body .description,
.item-body.total-group .sub-item-body .amount{
	text-align:right;
}
.to-be-continued{
	float:left;
	width:100%;
	font-weight:bold;
	color:#999999;
	clear:both;
	text-align:center;
}
/*-------------------------------For tour invoice---------------------------------------------------------*/
.item-body.total-group{
	font-weight:bold;
}
.item-body.last-row{
	border-bottom:0px;
}
.item-body span{
	float:right;
	width:13%;
	height:18px;
	text-align:right;
}
.item-body span.label{
	float:left;
	width:86%;
	text-align:right;
}
.item-body .total-amount{
	font-size:12px;
	background:#EFEFEF;
	float:right;
	padding:2px;
	border:1px solid #CCCCCC;
	white-space:nowrap;
	width:300px;
}
.item-body .total-amount span{
	width:100px;
	font-size:14px;
	font-weight:bold;
}
.item-body .label.remain{
	font-style:italic;
}
.item-body .remain{
	font-style:italic;
}
@media print{
    #dn_printer{
        display: none;
    }
    #tool_show_hide{
        display:none;
    }
}
</style>
<div id="tool_show_hide" style="width: 100%;">
    <p>
        <label>[[.hide_amount.]] <input id="hide_amount" type="checkbox" onclick="CheckHideShow('AMOUNT');" /></label>
        <label>[[.hide_service.]] <input id="hide_service" type="checkbox" onclick="CheckHideShow('SERVICE');" /></label>
        <label>[[.hide_tax.]] <input id="hide_tax" type="checkbox" onclick="CheckHideShow('TAX');" /></label>
        <label>[[.hide_total_amount.]] <input id="hide_total" type="checkbox" onclick="CheckHideShow('TOTAL');" /></label>
    </p>
</div>
<table id="dn_printer" width="100%">
    <tr>
        <td width="75%">&nbsp;</td>
        <td align="right"><input name="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 70px; height: 23px"/></td>
        <td align="right"><input name="print" type="checkbox" id="short" onclick="fun_check_option(1)" onchange="check_option();"/>[[.print_short.]]</td>
        <td align="right"><input name="print" type="checkbox" id="full" onclick="fun_check_option(2)" checked="" onchange="check_option();"/>[[.print_full.]]</td>
        <td align="right" style="vertical-align: bottom;" >
            <a onclick="print_payment_invoice();" title="In">
                <img src="packages/core/skins/default/images/printer.png" height="40" />
            </a>
        </td>
    </tr>
</table>
<form id="ViewTravellerFolioForm" method="post">
<table id="Export" width="100%" style="position: relative; float:left;" cellSpacing=0 cellPadding=0 border=0 width="100%" id="invoice">
	<tr>
		<td align="center">
		<!--IF:cond(1==1)-->
		<table cellpadding="0" width="100%" border="0">
        <tr>
        	<td><br/><br/>
            </td>
        </tr>
		<tr>
			<td width="1px" align="center" valign="top" id="imgs"><img src="<?php echo HOTEL_LOGO;?>" width="200"/></td>
            <td colspan="3">&nbsp;</td>
		  	<td align="center">
        		<div class="invoice-title" style="text-transform: uppercase; font-size:18px;font-weight:bold;color:#333333;margin:0px;padding:0px;">
                    <?php if([[=check_payment=]]==0){ ?>
                    [[.list_of_goods_and_services.]]&nbsp;(DRAG)
                    <?php }else{ ?>
                    [[.bill_for_payment.]]
                    <?php } ?>
                </div>
				<div class="invoice-sub-title"></div>
				<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
				<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
				<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
				<div>[[|description|]]</div>
		  	</td> 
              <td colspan="2">&nbsp;</td>          
              <td width="100px;" style="float: right;">Folio: <?php //echo Url::get('folio_id'); ?>
                 <?php if(isset([[=folio_code=]])){?>
                    <?php echo 'No.F'.str_pad([[=folio_code=]],6,"0",STR_PAD_LEFT) ;?>
                    <br />
                    <?php echo 'Ref'.str_pad([[=id=]],6,"0",STR_PAD_LEFT) ;?>
                  <?php }else{ ?>
                        <?php echo 'Ref'.str_pad([[=id=]],6,"0",STR_PAD_LEFT) ;?>
                  <?php }?>
              /  Re_code: [[|reservation_id|]]</td>
		</tr>
		</table>
		<!--ELSE-->
		<table cellpadding="0" width="100%" border="0">
		<tr>
			<td height="100">&nbsp;</td>
		</tr>
		</table>
		<!--/IF:cond-->
		<br />
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
			  <td width="35%"><div class="item-body">[[.booking_code.]]: [[|booking_code|]]</font> <font style="font-size:18px;font-weight:200">[[|bill_number|]]</font></div></td>
              <td colspan="5">&nbsp;</td>
			  <td style="float: right;"><div class="item-body">[[.arrival_date_new.]]: [[|arrival_time|]] - [[.departure_date_new.]]: [[|departure_time|]]</div></td>
            </tr>
		</table>      
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
				<td width="35%"><div class="item-body">[[.guest_name.]]:&nbsp;[[|full_name|]]</div></td>
                <td colspan="5">&nbsp;</td>
				<td style="float: right;"><div class="item-body">[[.room_number.]]: [[|room_name|]]</td>
			</tr>
			<tr>
			  <td width="35%"><div class="item-body">[[.company_name.]]: <?php echo [[=customer_def_name=]]?[[=customer_name=]].' - '.[[=customer_def_name=]]:[[=customer_name=]];?></div></td>
              <td colspan="5">&nbsp;</td>
              <td style="float: right;"><div class="item-body">[[.created_by.]]: [[|create_folio_user|]] [[|create_folio_time|]]</td>
		  </tr>
			<tr>
			  <td width="35%"><div class="item-body">[[.address.]]: [[|address|]] </div></td>
              <td colspan="5">&nbsp;</td>
              <td style="float: right;"><div class="item-body">[[.printed_by.]]: [[|account_name|]]<?php //echo Session::get('user_id');?> - <?php echo date('H:i d/m/y');?></td>
		  </tr>
			<!---<tr>
              <td><div class="item-body">Arrival date: [[|arrival_time|]] Departure date: [[|departure_time|]]</div></td>
			  <td>Created by : [[|create_folio_user|]] [[|create_folio_time|]]</td>
          </tr>--->
          <?php if([[=member_code=]]!=''){ ?>
          <tr>
              <td colspan="2">[[.member_code.]]: [[|member_code|]] - [[.member_level.]]: [[|member_level|]]</td>  
          </tr>
          <?php } ?>
		</table>
<div class="item-body">
	<div class="seperator-line">&nbsp;</div>
</div>
<div class="item-body" id="Content_Full">
<table style="width: 95%; margin: 0px auto;" id="ivoice">
    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 1px;">[[.num.]]</th>
        <th style="text-align: center;width: 9%;">[[.date.]]</th>
        <th style="text-align: left;width: 40%;">[[.description.]]</th>
        <th style="text-align: right;"><div class="auto_amount" style="display: ;">[[.amount.]]</div></th>
        <th style="text-align: right;"><div class="auto_service" style="display: ;">[[.ser_charge.]]</div></th>
        <th style="text-align: right;"><div class="auto_tax" style="display: ;">[[.tax.]]</div></th>
        <th style="text-align: right;"><div class="auto_total" style="display: ; text-align: right;">[[.total.]]</div></th>
    </tr>
    <?php $i=1;?>
    <!--IF:cond_room_price(isset([[=rooms=]]))-->
	<!--LIST:rooms-->
    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
        <th style="text-align: center;width: 9%;font-weight: normal;">[[|rooms.date_use|]]</th>
        <th style="text-align: left;width: 40%;font-weight: normal;">[[|rooms.room_name|]] [[.room_charge.]]</th>
        <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=rooms.amount=]]));?></div></th>
        <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=rooms.service_amount=]]));?></div></th>
        <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=rooms.tax_amount=]]));?></th>
        <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=rooms.total_amount=]]));?></div></th>
    </tr>
	<!--/LIST:rooms-->
    <!--/IF:cond_room_price-->
	<!--IF:cond_phone(isset([[=total_phone=]]) and [[=total_phone=]]>0 and [[=total_phone=]]!="0.00")-->  
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[.telephone_fee.]]</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number([[=total_phone=]]));?></div></th>
</tr>
	<!--/IF:cond_phone-->
	<!--IF:cond_total_massage(isset([[=total_massage_amount=]]) and [[=total_massage_amount=]])-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[.massage_service.]]</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number([[=total_massage_amount=]]));?></div></th>
</tr>
	<!--/IF:cond_total_massage-->
	<!--IF:cond(isset([[=total_tennis_amount=]]) and [[=total_tennis_amount=]])-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Tennis service</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number([[=total_tennis_amount=]]));?></div></th>
</tr>
	<!--/IF:cond-->
<!--IF:cond(isset([[=total_swimming_pool_amount=]]) and [[=total_swimming_pool_amount=]])-->

<tr style="border-bottom: 1px solid #f1f1f1;">
<th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Swimming service</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number([[=total_swimming_pool_amount=]]));?></div></th>
</tr>
	<!--/IF:cond--> 
	<!--IF:cond_total_karaoke_amount(isset([[=total_karaoke_amount=]]) and [[=total_karaoke_amount=]])-->		

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Karaoke</th>
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number_report(System::calculate_number([[=total_karaoke_amount=]]));?></div></th>
</tr>
	<!--/IF:cond_total_karaoke_amount-->
	<!--LIST:minibars-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|minibars.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|minibars.description|]] <?php echo [[=minibars.hk_code=]] != ''? [[=minibars.hk_code=]]:'';  ?><a target="_blank" href="?page=minibar_invoice&cmd=edit&id=[[|minibars.invoice_id|]]">#MN_[[|minibars.position|]]</a>
        <table width=70%>
            <!--LIST:minibars.product-->
            <tr >
                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;">+<em>[[|minibars.product.name|]]</em></td>
                <td width=20% style="font-size: 11px;"><em>[[.price.]]: </em><em class="change_num"><div class="auto_amount" style="display: ;"><?php echo System::display_number([[=minibars.product.price=]]);?></div></em></td>
                <td width=20% style="font-size: 11px;"><em>[[.quantity.]]: </em><em class="change_num">[[|minibars.product.quantity|]]</em></td>
            </tr>
            <!--/LIST:minibars.product-->
        </table>
    </th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=minibars.amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=minibars.service_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=minibars.tax_amount=]]));?></div></th>
</tr>
	<!--/LIST:minibars-->
   
	<!--LIST:laundrys-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|laundrys.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|laundrys.room_name|]] [[.laundry.]]<?php echo [[=laundrys.hk_code=]] != ''? '('.[[=laundrys.hk_code=]].')':'';  ?><a target="_blank" href="?page=laundry_invoice&cmd=edit&id=[[|laundrys.invoice_id|]]">#LD_[[|laundrys.position|]]</a>
        <table width=70%>
            <!--LIST:laundrys.product-->
            <tr >
                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em>[[|laundrys.product.name|]]</em></td>
                <td width=20% style="font-size: 11px;"><em>[[.price.]]: </em><em class="change_num"><div class="auto_amount" style="display: ;"><?php echo System::display_number([[=laundrys.product.price=]]);?></div></em></td>
                <td width=20% style="font-size: 11px;"><em>[[.quantity.]]: </em><em class="change_num">[[|laundrys.product.quantity|]]</em></td>
            </tr>
            <!--/LIST:laundrys.product-->
        </table>
    </th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=laundrys.amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=laundrys.service_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=laundrys.tax_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=laundrys.total_amount=]]));?></div></th>
</tr>
	<!--/LIST:laundrys-->
	<!--LIST:equipments-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|equipments.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|equipments.room_name|]] [[.equipment.]]<a target="_blank" href="?page=equipment_invoice&id=[[|equipments.invoice_id|]]">#EQ_[[|equipments.position|]]</a>
    <table width=70%>
        <!--LIST:equipments.product-->
        <tr >
            <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em>[[|equipments.product.name|]]</em></td>
            <td width=20% style="font-size: 11px;"><em>[[.price.]]: </em><em class="change_num"><div class="auto_amount" style="display: ;"><?php echo System::display_number([[=equipments.product.price=]]);?></div></em></td>
            <td width=20% style="font-size: 11px;"><em>[[.quantity.]]: </em><em class="change_num">[[|equipments.product.quantity|]]</em></td>
        </tr>
        <!--/LIST:equipments.product-->
    </table>
    </th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=equipments.amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=equipments.service_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=equipments.tax_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=equipments.total_amount=]]));?></div></th>
</tr>
	<!--/LIST:equipments-->
  
	<!--LIST:bars-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|bars.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=[[|bars.invoice_id|]]">#[[|bars.description|]]</a></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=bars.amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=bars.service_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=bars.tax_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=bars.total_amount=]]));?></div></th>
</tr>
	<!--/LIST:bars-->
    
    <!--LIST:karaokes-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|karaokes.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|karaokes.room_name|]] [[.karaoke.]]</th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number_report([[=karaokes.amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number_report([[=karaokes.service_amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number_report([[=karaokes.tax_amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number([[=karaokes.total_amount=]]);?></div></th>
</tr>  
	<!--/LIST:karaokes-->
    
    <!--LIST:ves-->
    <tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|ves.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|ves.description|]]</th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number_report([[=ves.amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number_report([[=ves.service_amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number_report([[=ves.tax_amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number([[=ves.total_amount=]]);?></div></th>
</tr>  
	<!--/LIST:ves-->
    
    <!--LIST:tickets-->
  <tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|tickets.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|tickets.description|]]</th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number_report([[=tickets.amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number_report([[=tickets.service_amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number_report([[=tickets.tax_amount=]]);?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number([[=tickets.total_amount=]]);?></div></th>
</tr>    

	<!--/LIST:tickets-->
    
    <!--LIST:massages-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|massages.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|massages.description|]] <a target="_blank" href="?page=massage_daily_summary&cmd=invoice&id=[[|massages.invoice_id|]]">#[[|massages.invoice_id|]]</a></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=massages.amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=massages.service_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=massages.tax_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=massages.total_amount=]]));?></div></th>
</tr>
	<!--/LIST:massages-->
	
	<!--LIST:extra_services-->

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;">[[|extra_services.date_use|]]</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">[[|extra_services.room_name|]] [[.extra_service.]] <?php echo [[=extra_services.ex_name=]] != ''? '('.[[=extra_services.ex_name=]].')':'';  ?> <?php echo [[=extra_services.ex_note=]] != ''? '('.[[=extra_services.ex_note=]].')':'';  ?> <a target="_blank" href="?page=extra_service_invoice&cmd=view_receipt&id=[[|extra_services.ex_id|]]">#[[|extra_services.ex_bill|]]</a></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=extra_services.amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=extra_services.service_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=extra_services.tax_amount=]]));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=extra_services.total_amount=]]));?></div></th>
</tr>
	<!--/LIST:extra_services--> 
     <!--IF:cond_d_a(isset([[=telephones=]])  and [[=telephones=]])-->
    <!--LIST:telephones-->
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Telephone</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number_report([[=telephones.total_amount=]]);?></div></th>
</tr>		 
	<!--/LIST:telephones-->
     <!--/IF:cond_d_a-->
<!--IF:cond_service_rate([[=service_total=]] and [[=service_total=]]>0 and [[=service_total=]]!="0.00")-->
<!--
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Service Charge ([[|service_rate|]]%)</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=service_total=]]);?></th>
</tr>
-->
	<!--/IF:cond_service_rate-->
	<!--IF:cond_room_tax([[=tax_total=]]>0 and [[=tax_total=]]!="0.00")-->
<!--
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Goverment tax ([[|tax_rate|]]%)</th>
    <th style="text-align: right;"><?php echo System::display_number_report([[=tax_total=]]);?></th>
</tr>
-->
	<!--/IF:cond_room_tax-->
<!-- cai nay la tong cua phan folo phong bt
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 48%;">&nbsp;</th>
    <th style="text-align: right;"><!--IF:cond_total_before_tax([[=total_before_tax=]] && [[=total_before_tax=]]>0)--><?php echo System::display_number_report([[=total_before_tax=]]);?><!--/IF:cond_total_before_tax--></th>
    <!--<th style="text-align: right;"><!--IF:cond_service([[=service_total=]] && [[=service_total=]]>0)--><?php echo System::display_number_report([[=service_total=]]);?><!--/IF:cond_service--></th>
    <!--<th style="text-align: right;"><!--IF:cond_tax([[=tax_total=]] && [[=tax_total=]]>0)--><?php echo System::display_number_report([[=tax_total=]]);?><!--/IF:cond_tax--></th>
    <!--<th style="text-align: right;"><!--IF:cond_total_amount([[=total_amount=]] && [[=total_amount=]]>0)--><?php echo System::display_number_report([[=total_amount=]]);?><!--/IF:cond_total_amount--></th>
<!--</tr>-->
    <!--LIST:add_payments-->

    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 1px;" colspan="2">&nbsp;</th>
        <th style="text-align: left;width: 40%;">[[|add_payments.description|]]</th>
        <th style="text-align: center;" colspan="3">&nbsp;</th>
        <th style="text-align: right;"><?php //echo System::display_number_report(round([[=add_payments.total=]]));?></th>
    </tr>
        <!--LIST:add_payment_items-->
        <?php if([[=add_payment_items.id=]]==[[=add_payments.id=]]){?>
 		    <?php $i=1;?>
            <!--LIST:add_payment_items.items-->
                <!--IF:check_type([[=add_payment_items.items.type=]] == 'LAUNDRY')-->
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i>[[|add_payment_items.items.date_use|]]</i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i>[[|add_payment_items.items.description|]]</i> <a target="_blank" href="?page=laundry_invoice&cmd=detail&id=[[|add_payment_items.items.invoice_id|]]">#LD_[[|add_payment_items.items.position|]]</a>
                        <table width=70%>
                            <!--LIST:add_payment_items.items.product-->
                            <tr >
                                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em>[[|add_payment_items.items.product.name|]]</em></td>
                                <td width=20% style="font-size: 11px;"><em>[[.price.]]: </em><em class="change_num auto_amount"><?php echo System::display_number([[=add_payment_items.items.product.price=]]);?></em></td>
                                <td width=20% style="font-size: 11px;"><em>[[.quantity.]]: </em><em class="change_num">[[|add_payment_items.items.product.quantity|]]</em></td>
                            </tr>
                            <!--/LIST:add_payment_items.items.product-->
                        </table>
                    </th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.service_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.tax_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round([[=add_payment_items.items.total_amount=]]));?></i></th>
                </tr>
                <!--/IF:check_type-->
                <!--IF:check_type([[=add_payment_items.items.type=]] == 'MINIBAR')-->
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i>[[|add_payment_items.items.date_use|]]</i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i>[[|add_payment_items.items.description|]]</i> <a target="_blank" href="?page=minibar_invoice&cmd=detail&id=[[|add_payment_items.items.invoice_id|]]">#MN_[[|add_payment_items.items.position|]]</a>
                        <table width=70%>
                            <!--LIST:add_payment_items.items.product-->
                            <tr >
                                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em>[[|add_payment_items.items.product.name|]]</em></td>
                                <td width=20% style="font-size: 11px;"><em>[[.price.]]: </em><em class="change_num auto_amount"><?php echo System::display_number([[=add_payment_items.items.product.price=]]);?></em></td>
                                <td width=20% style="font-size: 11px;"><em>[[.quantity.]]: </em><em class="change_num">[[|add_payment_items.items.product.quantity|]]</em></td>
                            </tr>
                            <!--/LIST:add_payment_items.items.product-->
                        </table>
                    </th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.service_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.tax_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round([[=add_payment_items.items.total_amount=]]));?></i></th>
                </tr>
                <!--/IF:check_type-->
                <!--IF:check_type([[=add_payment_items.items.type=]] == 'EQUIPMENT')-->
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i>[[|add_payment_items.items.date_use|]]</i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i>[[|add_payment_items.items.description|]]</i> <a target="_blank" href="?page=equipment_invoice&cmd=detail&id=[[|add_payment_items.items.invoice_id|]]">#EQ_[[|add_payment_items.items.position|]]</a>
                        <table width=70%>
                            <!--LIST:add_payment_items.items.product-->
                            <tr >
                                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em>[[|add_payment_items.items.product.name|]]</em></td>
                                <td width=20% style="font-size: 11px;"><em>[[.price.]]: </em><em class="change_num auto_amount"><?php echo System::display_number([[=add_payment_items.items.product.price=]]);?></em></td>
                                <td width=20% style="font-size: 11px;"><em>[[.quantity.]]: </em><em class="change_num">[[|add_payment_items.items.product.quantity|]]</em></td>
                            </tr>
                            <!--/LIST:add_payment_items.items.product-->
                        </table>
                    </th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.service_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.tax_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round([[=add_payment_items.items.total_amount=]]));?></i></th>
                </tr>
                <!--/IF:check_type-->
                <!--IF:check_type([[=add_payment_items.items.type=]] != 'LAUNDRY' && [[=add_payment_items.items.type=]] != 'MINIBAR' && [[=add_payment_items.items.type=]] != 'EQUIPMENT')-->
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i>[[|add_payment_items.items.date_use|]]</i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i>[[|add_payment_items.items.description|]]</i></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.service_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round([[=add_payment_items.items.tax_amount=]]));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round([[=add_payment_items.items.total_amount=]]));?></i></th>
                </tr>
                <!--/IF:check_type-->
            <!--/LIST:add_payment_items.items-->
        <!--/LIST:add_payment_items--> 
         <?php } ?>
	<!--/LIST:add_payments-->  
     <!--IF:cond_d_a(isset([[=discounts=]])  and [[=discounts=]])-->
     <!--LIST:discounts-->
<tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
    <th style="text-align: center;width: 1%;" colspan="2">&nbsp;</th>
    <th style="text-align: left;width: 40%;">Discount by <?php echo HOTEL_CURRENCY;?></th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;" class="change_num"><?php echo System::display_number([[=discounts.amount=]]);?></th>
</tr>		 
	<!--/LIST:discounts-->
	<!--/IF:cond_d_a-->     
 </table>
</div> 
<div class="item-body" id="Content_Short">
    <table style="width: 95%; margin: 0px auto;" id="ivoice">
        <tr style="border-bottom: 1px solid #f1f1f1;">
            <th style="text-align: center;width: 1px;">[[.num.]]</th>
            <th style="text-align: center;width: 9%;">[[.date.]]</th>
            <th style="text-align: left;width: 40%;">[[.description.]]</th>
            <th style="text-align: right;" class="auto_amount">[[.amount.]]</th>
            <th style="text-align: right;" class="auto_service">[[.ser_charge.]]</th>
            <th style="text-align: right;" class="auto_tax">[[.tax.]]</th>
            <th style="text-align: right;">[[.total.]]</th>
        </tr>
        <?php $stt =1; ?>
        <!--LIST:items_short-->
        <tr>
            <td align="left" valign="top" style="font-size: 14px;"><?php echo $stt++; ?></td>
            <td align="left" valign="top" style="font-size: 14px;"></td>
            <td align="left" valign="top" style="font-size: 14px;">[[|items_short.description|]]</td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_amount change_num"><?php echo System::display_number(round([[=items_short.amount=]])); ?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_service change_num"><?php echo System::display_number(round([[=items_short.service_amount=]])); ?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_tax change_num"><?php echo System::display_number(round([[=items_short.tax_amount=]])); ?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_total change_num"><?php echo System::display_number(round([[=items_short.total_amount=]])); ?></td>
        </tr>
        <!--/LIST:items_short-->
    </table>
</div>    
<!-- ----------------------------------------------------TOTAL---------------------------------------------------- -->
<?php $total_deposit=0;?>
<div class="item-body total-group">
     <!--LIST:deposits-->
        <?php $total_deposit += [[=deposits.amount=]];?>
</div>
 <!--/LIST:deposits-->
	
<table style="width: 95%; margin: 0px auto;" class="auto_total">
    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>    
        <th style="text-align: left;width: 36%;"><strong>[[.grand_total.]]</strong> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></th>
        <th style="text-align: right;width: 14%"><div class="auto_amount" style="display: ;"><strong class="change_num"><?php echo System::display_number(round([[=grand_total_before_tax=]]));?></strong></div></th>
        <th style="text-align: right;width: 12%;"><div class="auto_service" style="display: ;"><strong class="change_num"><?php echo System::display_number(round([[=grand_service_total=]]));?></strong></div></th>
        <th style="text-align: right;width: 10%"><div class="auto_tax" style="display: ;"><strong class="change_num"><?php echo System::display_number(round([[=grand_tax_total=]]));?></strong></div></th>
        <th style="text-align: right;"><strong class="change_num auto_total"><?php echo System::display_number([[=total=]]+$total_deposit);?></strong></th>
    </tr>

 <!--LIST:deposits-->
 
    <tr style="border-bottom: 1px solid #f1f1f1;" >
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>       
        <th style="text-align: left;width: 30%;"><strong>Đặt cọc</strong> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number([[=deposits.amount=]]);?></strong></th>
    </tr>

 <!--/LIST:deposits-->
 <?php if($total_deposit>0){?>
    
    <tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>      
        <th style="text-align: left;width: 37%;"><strong>Phải thanh toán</strong> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number([[=total=]]);?></strong></th>
    </tr>
    
  <?php } ?>
  </table>
  <hr />
  <table style="width: 95%; margin: 0px auto;">  
    <tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>       
        <th style="text-align: left;width: 37%;"><strong><?php if([[=total=]]>=0){echo Portal::language('total_billing');}else{echo Portal::language('return');}?> </strong><!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php if([[=total=]]<0 && [[=total_refund_vnd=]]>0){ echo System::display_number(-[[=total_payment_vnd=]]); } else echo System::display_number([[=total_payment_vnd=]]);?></strong></th>
    </tr>
    
    <tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>       
        <th style="text-align: left;width: 37%;"><strong>[[.total_remain.]]</strong></i> <!--IF:cond_foc([[=foc_all=]])-->(FOC)<!--/IF:cond_foc--></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php if([[=total=]]>=0){echo System::display_number([[=total=]] - [[=total_payment_vnd=]]);}else{echo System::display_number_report([[=total=]] - [[=total_payment_vnd=]]);}?></strong></th>
    </tr>
	<!--IF:bank_fee(isset([[=total_bank_fee=]]) and [[=total_bank_fee=]]>0)-->
    
    <tr style="border-bottom: 1px solid #f1f1f1;display:none;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>     
        <th style="text-align: left;width: 37%;">[[.bank_fee.]] ([[|bank_fee_percen|]]%)</th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number([[=total_bank_fee=]]);?></strong></th>
    </tr>
    
    <tr style="border-bottom: 1px solid #f1f1f1;display:none;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>      
        <th style="text-align: left;width: 37%;">[[.total_with_bank_fee_1.]]</th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number(round([[=total_with_bank_fee=]]));?></strong></th>
    </tr>
    
	<!--/IF:bank_fee-->
    </table>
	<div align="center"> 
    <?php $j=0;?>
    <!--LIST:payments-->
    	<?php $j++;?>
    <!--/LIST:payments-->
    <?php if($j>0){?>
  <table width="800px" style="border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px" class="nor auto_total">
  	<tr>
  		<th align="center">[[.payment_type.]]</th >
        <th align="center">[[.bank_account.]]/[[.card_number.]]</th >
        <th align="center">[[.description.]]</th >
        <th align="center">[[.currency.]]</th >
        <th align="right">[[.total.]]</th >
        <th align="right">[[.bank_fee.]]</th >
        <th align="right">[[.total_with_bank_fee.]]</th >
  	</tr>
    <!--LIST:payments-->
    	<tr>
  		<td align="center"><?php echo Portal::language(strtolower([[=payments.payment_type_id=]]));?></td>
        <td align="center">[[|payments.bank_acc|]]</td>
        <td align="center">[[|payments.description|]]</td>
        <td align="center">[[|payments.currency_id|]]</td>
        <td align="right" class="change_num"><?php echo System::display_number([[=payments.total=]]);?></td>
        <td align="right" class="change_num"><?php echo System::display_number([[=payments.bank_fee=]]);?></td>
        <td align="right" class="change_num"><?php echo System::display_number(round([[=payments.total=]] + [[=payments.bank_fee=]]));?></td>
  	</tr>
    <!--/LIST:payments-->
  </table>
    <!-- N?u c? thanh to?n th? ki?m tra xem c? member_code kh?ng -->
    <?php if($this->map['member_code']!=''){ ?>
        <hr style="width: 100%; text-decoration: none; margin: 3px auto;" />
        <table width="800px" style="border-collapse:collapse;" border="0" bordercolor="#CCCCCC" cellpadding="3px">
            <tr>
                <th style="text-align: right; text-transform: uppercase;">[[.payment_point_member.]]</th>
            </tr>
            <tr>
                <th style="text-align: right;">[[.point.]]: <?php if([[=point=]]>0) echo "+"; ?>[[|point|]]</th>
            </tr>
            <tr>
                <th style="text-align: right;">[[.point_user.]]: <?php if([[=point_user=]]>0) echo "+"; ?>[[|point_user|]]</th>
            </tr>
        </table>
    <?php } ?>
    <!-- end member -->  
  <?php } ?>
  </div>
<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>&nbsp;</td>
		<td align="justify" valign="top">&nbsp;</td>
	</tr>
	<tr>
        <td>&nbsp;</td>
		<td align="center"><br /><br /><br />
		[[.guest_signature.]]
 	   </td>
       <td colspan="3">&nbsp;</td>
		<td width="50%" align="center" valign="top">
			<br /><br /><br />
			[[.cashier_signature.]]
		</td>
	</tr>
</table>	
</div>
</td></tr></table>
</form>
<!-- ---------------------------------------------------/TOTAL---------------------------------------------------- -->
<script>
    jQuery(document).ready(function(){
        if(document.getElementById('hide_amount').checked==true){
                jQuery(".auto_amount").css('display','none');
        }else{
            console.log(22);
            jQuery(".auto_amount").css('display','');
        }
        if(document.getElementById('hide_service').checked==true){
            jQuery(".auto_service").css('display','none');
        }else{
            jQuery(".auto_service").css('display','');
        }
        if(document.getElementById('hide_tax').checked==true){
            jQuery(".auto_tax").css('display','none');
        }else{
            jQuery(".auto_tax").css('display','');
        }
        if(document.getElementById('hide_total').checked==true){
            jQuery(".auto_total").css('display','none');
        }else{
            jQuery(".auto_total").css('display','');
        }
    })
    
    function CheckHideShow($key){
        if($key=='AMOUNT'){
            
            if(document.getElementById('hide_amount').checked==true){
                console.log(11);
                jQuery(".auto_amount").css('display','none');
            }else{
                console.log(22);
                jQuery(".auto_amount").css('display','');
            }
        }else if($key=='SERVICE') {
            if(document.getElementById('hide_service').checked==true){
                jQuery(".auto_service").css('display','none');
            }else{
                jQuery(".auto_service").css('display','');
            }
        }else if($key=='TAX') {
            if(document.getElementById('hide_tax').checked==true){
                jQuery(".auto_tax").css('display','none');
            }else{
                jQuery(".auto_tax").css('display','');
            }
        }else if($key=='TOTAL') {
            if(document.getElementById('hide_total').checked==true){
                jQuery(".auto_total").css('display','none');
            }else{
                jQuery(".auto_total").css('display','');
            }
        }
    }
//jQuery("#chang_language").css('display','none');
jQuery(document).ready(function(){
    jQuery('#Content_Short').css('display', 'none');
})
function print_payment_invoice()
{
    var user ='<?php echo User::id(); ?>';
    if(jQuery("#full").is(':checked'))
    {
        printWebPart('printer',user); 
    }
    if(jQuery("#short").is(':checked'))
    {
        printWebPart('printer',user); 
    }   
}
function fun_check_option(id)
{
    if(id==1)
    {
        if(document.getElementById("short").checked==true)
        {
            document.getElementById("full").checked=false;
        }
        else
        {
            document.getElementById("full").checked=true;
        }
    }
    else
    {
        if(document.getElementById("full").checked==true)
        {
            document.getElementById("short").checked=false;
        }
        else
        {
            document.getElementById("short").checked=true;
        }
    }
}
function check_option()
{
    if(jQuery("#full").is(':checked'))
    {
        jQuery('#Content_Full').css('display','block');
        jQuery('#Content_Short').css('display', 'none');         
    }else
    {
        jQuery('#Content_Full').css('display','none');
        jQuery('#Content_Short').css('display', 'block');        
    }    
}
jQuery("#export_excel").click(function () {
    jQuery('#imgs').remove();
    jQuery('.invoice-title').css('font-size', '24px');
    jQuery('.change_num').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
    })
    jQuery('.auto_amount').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
    })
    jQuery('.auto_service').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
    })
    jQuery('.auto_tax').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
    })
    if(jQuery("#full").is(':checked'))
    {
        jQuery('#Content_Short').remove();
        jQuery("#Export").battatech_excelexport({
            containerid: "Export"
           , datatype: 'table'
           , fileName: '[[.bill_folio.]]'
        });       
    }else
    {
        jQuery('#Content_Full').remove();
        jQuery("#Export").battatech_excelexport({
            containerid: "Export"
           , datatype: 'table'
           , fileName: '[[.bill_folio.]]'
        });    
    }
    ViewTravellerFolioForm.submit();
});
</script>