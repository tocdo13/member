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
        <label><?php echo Portal::language('hide_amount');?> <input id="hide_amount" type="checkbox" onclick="CheckHideShow('AMOUNT');" /></label>
        <label><?php echo Portal::language('hide_service');?> <input id="hide_service" type="checkbox" onclick="CheckHideShow('SERVICE');" /></label>
        <label><?php echo Portal::language('hide_tax');?> <input id="hide_tax" type="checkbox" onclick="CheckHideShow('TAX');" /></label>
        <label><?php echo Portal::language('hide_total_amount');?> <input id="hide_total" type="checkbox" onclick="CheckHideShow('TOTAL');" /></label>
    </p>
</div>
<table id="dn_printer" width="100%">
    <tr>
        <td width="75%">&nbsp;</td>
        <td align="right"><input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 70px; height: 23px"/></td>
        <td align="right"><input  name="print" id="short" onclick="fun_check_option(1)" checked="" onchange="check_option();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('print'));?>"><?php echo Portal::language('print_short');?></td>
        <td align="right"><input  name="print" id="full" onclick="fun_check_option(2)"  onchange="check_option();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('print'));?>"><?php echo Portal::language('print_full');?></td>
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
		<?php 
				if((1==1))
				{?>
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
                    <?php if($this->map['check_payment']==0){ ?>
                    <?php echo Portal::language('list_of_goods_and_services');?>&nbsp;(DRAG)
                    <?php }else{ ?>
                    <?php echo Portal::language('bill_for_payment');?>
                    <?php } ?>
                </div>
				<div class="invoice-sub-title"></div>
				<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
				<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
				<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
				<div><?php echo $this->map['description'];?></div>
		  	</td> 
              <td colspan="2">&nbsp;</td>          
              <td width="100px;" style="float: right;">Folio: <?php //echo Url::get('folio_id'); ?>
                 <?php if(isset($this->map['folio_code'])){?>
                    <?php echo 'No.F'.str_pad($this->map['folio_code'],6,"0",STR_PAD_LEFT) ;?>
                    <br />
                    <?php echo 'Ref'.str_pad($this->map['id'],6,"0",STR_PAD_LEFT) ;?>
                  <?php }else{ ?>
                        <?php echo 'Ref'.str_pad($this->map['id'],6,"0",STR_PAD_LEFT) ;?>
                  <?php }?>
              /  Re_code: <?php echo $this->map['reservation_id'];?></td>
		</tr>
		</table>
		 <?php }else{ ?>
		<table cellpadding="0" width="100%" border="0">
		<tr>
			<td height="100">&nbsp;</td>
		</tr>
		</table>
		
				<?php
				}
				?>
		<br />
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
			  <td width="35%"><div class="item-body"><?php echo Portal::language('booking_code');?>: <?php echo $this->map['booking_code'];?></font> <font style="font-size:18px;font-weight:200"><?php echo $this->map['bill_number'];?></font></div></td>
              <td colspan="5">&nbsp;</td>
			  <td style="float: right;"><div class="item-body"><?php echo Portal::language('arrival_date_new');?>: <?php echo $this->map['arrival_time'];?> - <?php echo Portal::language('departure_date_new');?>: <?php echo $this->map['departure_time'];?></div></td>
            </tr>
		</table>      
		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
			<tr>
				<td width="35%"><div class="item-body"><?php echo Portal::language('guest_name');?>:&nbsp;<?php echo $this->map['full_name'];?></div></td>
                <td colspan="5">&nbsp;</td>
				<td style="float: right;"><div class="item-body"><?php echo Portal::language('room_number');?>: <?php echo $this->map['room_name'];?></td>
			</tr>
			<tr>
			  <td width="35%"><div class="item-body"><?php echo Portal::language('company_name');?>: <?php echo $this->map['customer_def_name']?$this->map['customer_name'].' - '.$this->map['customer_def_name']:$this->map['customer_name'];?></div></td>
              <td colspan="5">&nbsp;</td>
              <td style="float: right;"><div class="item-body"><?php echo Portal::language('created_by');?>: <?php echo $this->map['create_folio_user'];?> <?php echo $this->map['create_folio_time'];?></td>
		  </tr>
			<tr>
			  <td width="35%"><div class="item-body"><?php echo Portal::language('address');?>: <?php echo $this->map['address'];?> </div></td>
              <td colspan="5">&nbsp;</td>
              <td style="float: right;"><div class="item-body"><?php echo Portal::language('printed_by');?>: <?php echo $this->map['account_name'];?><?php //echo Session::get('user_id');?> - <?php echo date('H:i d/m/y');?></td>
		  </tr>
			<!---<tr>
              <td><div class="item-body">Arrival date: <?php echo $this->map['arrival_time'];?> Departure date: <?php echo $this->map['departure_time'];?></div></td>
			  <td>Created by : <?php echo $this->map['create_folio_user'];?> <?php echo $this->map['create_folio_time'];?></td>
          </tr>--->
          <?php if($this->map['member_code']!=''){ ?>
          <tr>
              <td colspan="2"><?php echo Portal::language('member_code');?>: <?php echo $this->map['member_code'];?> - <?php echo Portal::language('member_level');?>: <?php echo $this->map['member_level'];?></td>  
          </tr>
          <?php } ?>
		</table>
<div class="item-body">
	<div class="seperator-line">&nbsp;</div>
</div>
<div class="item-body" id="Content_Full">
<table style="width: 95%; margin: 0px auto;" id="ivoice">
    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 1px;"><?php echo Portal::language('num');?></th>
        <th style="text-align: center;width: 9%;"><?php echo Portal::language('date');?></th>
        <th style="text-align: left;width: 40%;"><?php echo Portal::language('description');?></th>
        <th style="text-align: right;"><div class="auto_amount" style="display: ;"><?php echo Portal::language('amount');?></div></th>
        <th style="text-align: right;"><div class="auto_service" style="display: ;"><?php echo Portal::language('ser_charge');?></div></th>
        <th style="text-align: right;"><div class="auto_tax" style="display: ;"><?php echo Portal::language('tax');?></div></th>
        <th style="text-align: right;"><div class="auto_total" style="display: ; text-align: right;"><?php echo Portal::language('total');?></div></th>
    </tr>
    <?php $i=1;?>
    <?php 
				if((isset($this->map['rooms'])))
				{?>
	<?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key1=>&$item1){if($key1!='current'){$this->map['rooms']['current'] = &$item1;?>
    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
        <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['rooms']['current']['date_use'];?></th>
        <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['rooms']['current']['room_name'];?> <?php echo Portal::language('room_charge');?></th>
        <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['rooms']['current']['amount']));?></div></th>
        <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['rooms']['current']['service_amount']));?></div></th>
        <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['rooms']['current']['tax_amount']));?></th>
        <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['rooms']['current']['total_amount']));?></div></th>
    </tr>
	<?php }}unset($this->map['rooms']['current']);} ?>
    
				<?php
				}
				?>
	<?php 
				if((isset($this->map['total_phone']) and $this->map['total_phone']>0 and $this->map['total_phone']!="0.00"))
				{?>  
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo Portal::language('telephone_fee');?></th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number($this->map['total_phone']));?></div></th>
</tr>
	
				<?php
				}
				?>
	<?php 
				if((isset($this->map['total_massage_amount']) and $this->map['total_massage_amount']))
				{?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo Portal::language('massage_service');?></th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number($this->map['total_massage_amount']));?></div></th>
</tr>
	
				<?php
				}
				?>
	<?php 
				if((isset($this->map['total_tennis_amount']) and $this->map['total_tennis_amount']))
				{?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Tennis service</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number($this->map['total_tennis_amount']));?></div></th>
</tr>
	
				<?php
				}
				?>
<?php 
				if((isset($this->map['total_swimming_pool_amount']) and $this->map['total_swimming_pool_amount']))
				{?>

<tr style="border-bottom: 1px solid #f1f1f1;">
<th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Swimming service</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(System::calculate_number($this->map['total_swimming_pool_amount']));?></div></th>
</tr>
	
				<?php
				}
				?> 
	<?php 
				if((isset($this->map['total_karaoke_amount']) and $this->map['total_karaoke_amount']))
				{?>		

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Karaoke</th>
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number_report(System::calculate_number($this->map['total_karaoke_amount']));?></div></th>
</tr>
	
				<?php
				}
				?>
	<?php if(isset($this->map['minibars']) and is_array($this->map['minibars'])){ foreach($this->map['minibars'] as $key2=>&$item2){if($key2!='current'){$this->map['minibars']['current'] = &$item2;?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['minibars']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['minibars']['current']['description'];?> <?php echo $this->map['minibars']['current']['hk_code'] != ''? $this->map['minibars']['current']['hk_code']:'';  ?><a target="_blank" href="?page=minibar_invoice&cmd=edit&id=<?php echo $this->map['minibars']['current']['invoice_id'];?>">#MN_<?php echo $this->map['minibars']['current']['position'];?></a>
        <table width=70%>
            <?php if(isset($this->map['minibars']['current']['product']) and is_array($this->map['minibars']['current']['product'])){ foreach($this->map['minibars']['current']['product'] as $key3=>&$item3){if($key3!='current'){$this->map['minibars']['current']['product']['current'] = &$item3;?>
            <tr >
                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;">+<em><?php echo $this->map['minibars']['current']['product']['current']['name'];?></em></td>
                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('price');?>: </em><em class="change_num"><div class="auto_amount" style="display: ;"><?php echo System::display_number($this->map['minibars']['current']['product']['current']['price']);?></div></em></td>
                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('quantity');?>: </em><em class="change_num"><?php echo $this->map['minibars']['current']['product']['current']['quantity'];?></em></td>
            </tr>
            <?php }}unset($this->map['minibars']['current']['product']['current']);} ?>
        </table>
    </th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['minibars']['current']['amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['minibars']['current']['service_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['minibars']['current']['tax_amount']));?></div></th>
</tr>
	<?php }}unset($this->map['minibars']['current']);} ?>
   
	<?php if(isset($this->map['laundrys']) and is_array($this->map['laundrys'])){ foreach($this->map['laundrys'] as $key4=>&$item4){if($key4!='current'){$this->map['laundrys']['current'] = &$item4;?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['laundrys']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['laundrys']['current']['room_name'];?> <?php echo Portal::language('laundry');?><?php echo $this->map['laundrys']['current']['hk_code'] != ''? '('.$this->map['laundrys']['current']['hk_code'].')':'';  ?><a target="_blank" href="?page=laundry_invoice&cmd=edit&id=<?php echo $this->map['laundrys']['current']['invoice_id'];?>">#LD_<?php echo $this->map['laundrys']['current']['position'];?></a>
        <table width=70%>
            <?php if(isset($this->map['laundrys']['current']['product']) and is_array($this->map['laundrys']['current']['product'])){ foreach($this->map['laundrys']['current']['product'] as $key5=>&$item5){if($key5!='current'){$this->map['laundrys']['current']['product']['current'] = &$item5;?>
            <tr >
                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em><?php echo $this->map['laundrys']['current']['product']['current']['name'];?></em></td>
                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('price');?>: </em><em class="change_num"><div class="auto_amount" style="display: ;"><?php echo System::display_number($this->map['laundrys']['current']['product']['current']['price']);?></div></em></td>
                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('quantity');?>: </em><em class="change_num"><?php echo $this->map['laundrys']['current']['product']['current']['quantity'];?></em></td>
            </tr>
            <?php }}unset($this->map['laundrys']['current']['product']['current']);} ?>
        </table>
    </th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['laundrys']['current']['amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['laundrys']['current']['service_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['laundrys']['current']['tax_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['laundrys']['current']['total_amount']));?></div></th>
</tr>
	<?php }}unset($this->map['laundrys']['current']);} ?>
	<?php if(isset($this->map['equipments']) and is_array($this->map['equipments'])){ foreach($this->map['equipments'] as $key6=>&$item6){if($key6!='current'){$this->map['equipments']['current'] = &$item6;?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['equipments']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['equipments']['current']['room_name'];?> <?php echo Portal::language('equipment');?><a target="_blank" href="?page=equipment_invoice&id=<?php echo $this->map['equipments']['current']['invoice_id'];?>">#EQ_<?php echo $this->map['equipments']['current']['position'];?></a>
    <table width=70%>
        <?php if(isset($this->map['equipments']['current']['product']) and is_array($this->map['equipments']['current']['product'])){ foreach($this->map['equipments']['current']['product'] as $key7=>&$item7){if($key7!='current'){$this->map['equipments']['current']['product']['current'] = &$item7;?>
        <tr >
            <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em><?php echo $this->map['equipments']['current']['product']['current']['name'];?></em></td>
            <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('price');?>: </em><em class="change_num"><div class="auto_amount" style="display: ;"><?php echo System::display_number($this->map['equipments']['current']['product']['current']['price']);?></div></em></td>
            <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('quantity');?>: </em><em class="change_num"><?php echo $this->map['equipments']['current']['product']['current']['quantity'];?></em></td>
        </tr>
        <?php }}unset($this->map['equipments']['current']['product']['current']);} ?>
    </table>
    </th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['equipments']['current']['amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['equipments']['current']['service_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['equipments']['current']['tax_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['equipments']['current']['total_amount']));?></div></th>
</tr>
	<?php }}unset($this->map['equipments']['current']);} ?>
  
	<?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key8=>&$item8){if($key8!='current'){$this->map['bars']['current'] = &$item8;?>
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['bars']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=<?php echo $this->map['bars']['current']['invoice_id'];?>">#<?php echo $this->map['bars']['current']['description'];?></a></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['bars']['current']['amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['bars']['current']['service_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['bars']['current']['tax_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['bars']['current']['total_amount']));?></div></th>
</tr>
	<?php }}unset($this->map['bars']['current']);} ?>
    
    <?php if(isset($this->map['karaokes']) and is_array($this->map['karaokes'])){ foreach($this->map['karaokes'] as $key9=>&$item9){if($key9!='current'){$this->map['karaokes']['current'] = &$item9;?>
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['karaokes']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['karaokes']['current']['room_name'];?> <?php echo Portal::language('karaoke');?></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number_report($this->map['karaokes']['current']['amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number_report($this->map['karaokes']['current']['service_amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number_report($this->map['karaokes']['current']['tax_amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number($this->map['karaokes']['current']['total_amount']);?></div></th>
</tr>  
	<?php }}unset($this->map['karaokes']['current']);} ?>
    
    <?php if(isset($this->map['ves']) and is_array($this->map['ves'])){ foreach($this->map['ves'] as $key10=>&$item10){if($key10!='current'){$this->map['ves']['current'] = &$item10;?>
    <tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['ves']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['ves']['current']['description'];?></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number_report($this->map['ves']['current']['amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number_report($this->map['ves']['current']['service_amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number_report($this->map['ves']['current']['tax_amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number($this->map['ves']['current']['total_amount']);?></div></th>
</tr>  
	<?php }}unset($this->map['ves']['current']);} ?>
    
    <?php if(isset($this->map['tickets']) and is_array($this->map['tickets'])){ foreach($this->map['tickets'] as $key11=>&$item11){if($key11!='current'){$this->map['tickets']['current'] = &$item11;?>
  <tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['tickets']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['tickets']['current']['description'];?></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number_report($this->map['tickets']['current']['amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number_report($this->map['tickets']['current']['service_amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number_report($this->map['tickets']['current']['tax_amount']);?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number($this->map['tickets']['current']['total_amount']);?></div></th>
</tr>    

	<?php }}unset($this->map['tickets']['current']);} ?>
    
    <?php if(isset($this->map['massages']) and is_array($this->map['massages'])){ foreach($this->map['massages'] as $key12=>&$item12){if($key12!='current'){$this->map['massages']['current'] = &$item12;?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['massages']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['massages']['current']['description'];?> <a target="_blank" href="?page=massage_daily_summary&cmd=invoice&id=<?php echo $this->map['massages']['current']['invoice_id'];?>">#<?php echo $this->map['massages']['current']['invoice_id'];?></a></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['massages']['current']['amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['massages']['current']['service_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['massages']['current']['tax_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['massages']['current']['total_amount']));?></div></th>
</tr>
	<?php }}unset($this->map['massages']['current']);} ?>
	
	<?php if(isset($this->map['extra_services']) and is_array($this->map['extra_services'])){ foreach($this->map['extra_services'] as $key13=>&$item13){if($key13!='current'){$this->map['extra_services']['current'] = &$item13;?>

<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;font-weight: normal;"><?php echo $this->map['extra_services']['current']['date_use'];?></th>
    <th style="text-align: left;width: 40%;font-weight: normal;"><?php echo $this->map['extra_services']['current']['room_name'];?> <?php echo Portal::language('extra_service');?> <?php echo $this->map['extra_services']['current']['ex_name'] != ''? '('.$this->map['extra_services']['current']['ex_name'].')':'';  ?> <?php echo $this->map['extra_services']['current']['ex_note'] != ''? '('.$this->map['extra_services']['current']['ex_note'].')':'';  ?> <a target="_blank" href="?page=extra_service_invoice&cmd=view_receipt&id=<?php echo $this->map['extra_services']['current']['ex_id'];?>">#<?php echo $this->map['extra_services']['current']['ex_bill'];?></a></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['extra_services']['current']['amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['extra_services']['current']['service_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['extra_services']['current']['tax_amount']));?></div></th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['extra_services']['current']['total_amount']));?></div></th>
</tr>
	<?php }}unset($this->map['extra_services']['current']);} ?> 
     <?php 
				if((isset($this->map['telephones'])  and $this->map['telephones']))
				{?>
    <?php if(isset($this->map['telephones']) and is_array($this->map['telephones'])){ foreach($this->map['telephones'] as $key14=>&$item14){if($key14!='current'){$this->map['telephones']['current'] = &$item14;?>
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 1px;font-weight: normal;"><?php echo $i++;?></th>
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 40%;font-weight: normal;">Telephone</th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;font-weight: normal;" class="change_num"><div class="auto_total" style="display: ;"><?php echo System::display_number_report($this->map['telephones']['current']['total_amount']);?></div></th>
</tr>		 
	<?php }}unset($this->map['telephones']['current']);} ?>
     
				<?php
				}
				?>
<?php 
				if(($this->map['service_total'] and $this->map['service_total']>0 and $this->map['service_total']!="0.00"))
				{?>
<!--
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Service Charge (<?php echo $this->map['service_rate'];?>%)</th>
    <th style="text-align: right;"><?php echo System::display_number_report($this->map['service_total']);?></th>
</tr>
-->
	
				<?php
				}
				?>
	<?php 
				if(($this->map['tax_total']>0 and $this->map['tax_total']!="0.00"))
				{?>
<!--
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;">&nbsp;</th>
    <th style="text-align: left;">Goverment tax (<?php echo $this->map['tax_rate'];?>%)</th>
    <th style="text-align: right;"><?php echo System::display_number_report($this->map['tax_total']);?></th>
</tr>
-->
	
				<?php
				}
				?>
<!-- cai nay la tong cua phan folo phong bt
<tr style="border-bottom: 1px solid #f1f1f1;">
    <th style="text-align: center;width: 9%;">&nbsp;</th>
    <th style="text-align: left;width: 48%;">&nbsp;</th>
    <th style="text-align: right;"><?php 
				if(($this->map['total_before_tax'] && $this->map['total_before_tax']>0))
				{?><?php echo System::display_number_report($this->map['total_before_tax']);?>
				<?php
				}
				?></th>
    <!--<th style="text-align: right;"><?php 
				if(($this->map['service_total'] && $this->map['service_total']>0))
				{?><?php echo System::display_number_report($this->map['service_total']);?>
				<?php
				}
				?></th>
    <!--<th style="text-align: right;"><?php 
				if(($this->map['tax_total'] && $this->map['tax_total']>0))
				{?><?php echo System::display_number_report($this->map['tax_total']);?>
				<?php
				}
				?></th>
    <!--<th style="text-align: right;"><?php 
				if(($this->map['total_amount'] && $this->map['total_amount']>0))
				{?><?php echo System::display_number_report($this->map['total_amount']);?>
				<?php
				}
				?></th>
<!--</tr>-->
    <?php if(isset($this->map['add_payments']) and is_array($this->map['add_payments'])){ foreach($this->map['add_payments'] as $key15=>&$item15){if($key15!='current'){$this->map['add_payments']['current'] = &$item15;?>

    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 1px;" colspan="2">&nbsp;</th>
        <th style="text-align: left;width: 40%;"><?php echo $this->map['add_payments']['current']['description'];?></th>
        <th style="text-align: center;" colspan="3">&nbsp;</th>
        <th style="text-align: right;"><?php //echo System::display_number_report(round($this->map['add_payments']['current']['total']));?></th>
    </tr>
        <?php if(isset($this->map['add_payment_items']) and is_array($this->map['add_payment_items'])){ foreach($this->map['add_payment_items'] as $key16=>&$item16){if($key16!='current'){$this->map['add_payment_items']['current'] = &$item16;?>
        <?php if($this->map['add_payment_items']['current']['id']==$this->map['add_payments']['current']['id']){?>
 		    <?php $i=1;?>
            <?php if(isset($this->map['add_payment_items']['current']['items']) and is_array($this->map['add_payment_items']['current']['items'])){ foreach($this->map['add_payment_items']['current']['items'] as $key17=>&$item17){if($key17!='current'){$this->map['add_payment_items']['current']['items']['current'] = &$item17;?>
                <?php 
				if(($this->map['add_payment_items']['current']['items']['current']['type'] == 'LAUNDRY'))
				{?>
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['date_use'];?></i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['description'];?></i> <a target="_blank" href="?page=laundry_invoice&cmd=detail&id=<?php echo $this->map['add_payment_items']['current']['items']['current']['invoice_id'];?>">#LD_<?php echo $this->map['add_payment_items']['current']['items']['current']['position'];?></a>
                        <table width=70%>
                            <?php if(isset($this->map['add_payment_items']['current']['items']['current']['product']) and is_array($this->map['add_payment_items']['current']['items']['current']['product'])){ foreach($this->map['add_payment_items']['current']['items']['current']['product'] as $key18=>&$item18){if($key18!='current'){$this->map['add_payment_items']['current']['items']['current']['product']['current'] = &$item18;?>
                            <tr >
                                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em><?php echo $this->map['add_payment_items']['current']['items']['current']['product']['current']['name'];?></em></td>
                                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('price');?>: </em><em class="change_num auto_amount"><?php echo System::display_number($this->map['add_payment_items']['current']['items']['current']['product']['current']['price']);?></em></td>
                                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('quantity');?>: </em><em class="change_num"><?php echo $this->map['add_payment_items']['current']['items']['current']['product']['current']['quantity'];?></em></td>
                            </tr>
                            <?php }}unset($this->map['add_payment_items']['current']['items']['current']['product']['current']);} ?>
                        </table>
                    </th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['service_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['tax_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['total_amount']));?></i></th>
                </tr>
                
				<?php
				}
				?>
                <?php 
				if(($this->map['add_payment_items']['current']['items']['current']['type'] == 'MINIBAR'))
				{?>
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['date_use'];?></i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['description'];?></i> <a target="_blank" href="?page=minibar_invoice&cmd=detail&id=<?php echo $this->map['add_payment_items']['current']['items']['current']['invoice_id'];?>">#MN_<?php echo $this->map['add_payment_items']['current']['items']['current']['position'];?></a>
                        <table width=70%>
                            <?php if(isset($this->map['add_payment_items']['current']['items']['current']['product']) and is_array($this->map['add_payment_items']['current']['items']['current']['product'])){ foreach($this->map['add_payment_items']['current']['items']['current']['product'] as $key19=>&$item19){if($key19!='current'){$this->map['add_payment_items']['current']['items']['current']['product']['current'] = &$item19;?>
                            <tr >
                                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em><?php echo $this->map['add_payment_items']['current']['items']['current']['product']['current']['name'];?></em></td>
                                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('price');?>: </em><em class="change_num auto_amount"><?php echo System::display_number($this->map['add_payment_items']['current']['items']['current']['product']['current']['price']);?></em></td>
                                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('quantity');?>: </em><em class="change_num"><?php echo $this->map['add_payment_items']['current']['items']['current']['product']['current']['quantity'];?></em></td>
                            </tr>
                            <?php }}unset($this->map['add_payment_items']['current']['items']['current']['product']['current']);} ?>
                        </table>
                    </th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['service_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['tax_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['total_amount']));?></i></th>
                </tr>
                
				<?php
				}
				?>
                <?php 
				if(($this->map['add_payment_items']['current']['items']['current']['type'] == 'EQUIPMENT'))
				{?>
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['date_use'];?></i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['description'];?></i> <a target="_blank" href="?page=equipment_invoice&cmd=detail&id=<?php echo $this->map['add_payment_items']['current']['items']['current']['invoice_id'];?>">#EQ_<?php echo $this->map['add_payment_items']['current']['items']['current']['position'];?></a>
                        <table width=70%>
                            <?php if(isset($this->map['add_payment_items']['current']['items']['current']['product']) and is_array($this->map['add_payment_items']['current']['items']['current']['product'])){ foreach($this->map['add_payment_items']['current']['items']['current']['product'] as $key20=>&$item20){if($key20!='current'){$this->map['add_payment_items']['current']['items']['current']['product']['current'] = &$item20;?>
                            <tr >
                                <td width=30% style="padding-left: 20px; size: 3; font-size: 11px;" class="change_num">+<em><?php echo $this->map['add_payment_items']['current']['items']['current']['product']['current']['name'];?></em></td>
                                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('price');?>: </em><em class="change_num auto_amount"><?php echo System::display_number($this->map['add_payment_items']['current']['items']['current']['product']['current']['price']);?></em></td>
                                <td width=20% style="font-size: 11px;"><em><?php echo Portal::language('quantity');?>: </em><em class="change_num"><?php echo $this->map['add_payment_items']['current']['items']['current']['product']['current']['quantity'];?></em></td>
                            </tr>
                            <?php }}unset($this->map['add_payment_items']['current']['items']['current']['product']['current']);} ?>
                        </table>
                    </th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['service_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['tax_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['total_amount']));?></i></th>
                </tr>
                
				<?php
				}
				?>
                <?php 
				if(($this->map['add_payment_items']['current']['items']['current']['type'] != 'LAUNDRY' && $this->map['add_payment_items']['current']['items']['current']['type'] != 'MINIBAR' && $this->map['add_payment_items']['current']['items']['current']['type'] != 'EQUIPMENT'))
				{?>
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <th style="text-align: center;width: 1px;font-weight: normal;"><i><?php echo $i++;?></i></th>
                    <th style="text-align: center;width: 9%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['date_use'];?></i></th>
                    <th style="text-align: left;width: 40%;font-weight: normal;"><i><?php echo $this->map['add_payment_items']['current']['items']['current']['description'];?></i></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_amount" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_service" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['service_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><div class="auto_tax" style="display: ;"><i class="change_num"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['tax_amount']));?></i></div></th>
                    <th style="text-align: right;font-weight: normal;"><i class="change_num auto_total"><?php echo System::display_number(round($this->map['add_payment_items']['current']['items']['current']['total_amount']));?></i></th>
                </tr>
                
				<?php
				}
				?>
            <?php }}unset($this->map['add_payment_items']['current']['items']['current']);} ?>
        <?php }}unset($this->map['add_payment_items']['current']);} ?> 
         <?php } ?>
	<?php }}unset($this->map['add_payments']['current']);} ?>  
     <?php 
				if((isset($this->map['discounts'])  and $this->map['discounts']))
				{?>
     <?php if(isset($this->map['discounts']) and is_array($this->map['discounts'])){ foreach($this->map['discounts'] as $key21=>&$item21){if($key21!='current'){$this->map['discounts']['current'] = &$item21;?>
<tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
    <th style="text-align: center;width: 1%;" colspan="2">&nbsp;</th>
    <th style="text-align: left;width: 40%;">Discount by <?php echo HOTEL_CURRENCY;?></th>
    <th style="text-align: center;" colspan="3">&nbsp;</th>
    <th style="text-align: right;" class="change_num"><?php echo System::display_number($this->map['discounts']['current']['amount']);?></th>
</tr>		 
	<?php }}unset($this->map['discounts']['current']);} ?>
	
				<?php
				}
				?>     
 </table>
</div> 
<div class="item-body" id="Content_Short">
    <table style="width: 95%; margin: 0px auto;" id="ivoice">
        <tr style="border-bottom: 1px solid #f1f1f1;">
            <th style="text-align: center;width: 1px;"><?php echo Portal::language('num');?></th>
            <th style="text-align: center;width: 9%;"><?php echo Portal::language('date');?></th>
            <th style="text-align: left;width: 40%;"><?php echo Portal::language('description');?></th>
            <th style="text-align: right;" class="auto_amount"><?php echo Portal::language('amount');?></th>
            <th style="text-align: right;" class="auto_service"><?php echo Portal::language('ser_charge');?></th>
            <th style="text-align: right;" class="auto_tax"><?php echo Portal::language('tax');?></th>
            <th style="text-align: right;"><?php echo Portal::language('total');?></th>
        </tr>
        <?php $stt =1; ?>
        <?php if(isset($this->map['items_short']) and is_array($this->map['items_short'])){ foreach($this->map['items_short'] as $key22=>&$item22){if($key22!='current'){$this->map['items_short']['current'] = &$item22;?>
        <tr>
            <td align="left" valign="top" style="font-size: 14px;"><?php echo $stt++; ?></td>
            <td align="left" valign="top" style="font-size: 14px;"></td>
            <td align="left" valign="top" style="font-size: 14px;"><?php echo $this->map['items_short']['current']['description'];?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_amount change_num"><?php echo System::display_number(round($this->map['items_short']['current']['amount'])); ?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_service change_num"><?php echo System::display_number(round($this->map['items_short']['current']['service_amount'])); ?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_tax change_num"><?php echo System::display_number(round($this->map['items_short']['current']['tax_amount'])); ?></td>
            <td align="right" valign="top" style="font-size: 14px;" class="auto_total change_num"><?php echo System::display_number(round($this->map['items_short']['current']['total_amount'])); ?></td>
        </tr>
        <?php }}unset($this->map['items_short']['current']);} ?>
    </table>
</div>    
<!-- ----------------------------------------------------TOTAL---------------------------------------------------- -->
<?php $total_deposit=0;?>
<div class="item-body total-group">
     <?php if(isset($this->map['deposits']) and is_array($this->map['deposits'])){ foreach($this->map['deposits'] as $key23=>&$item23){if($key23!='current'){$this->map['deposits']['current'] = &$item23;?>
        <?php $total_deposit += $this->map['deposits']['current']['amount'];?>
</div>
 <?php }}unset($this->map['deposits']['current']);} ?>
	
<table style="width: 95%; margin: 0px auto;" class="auto_total">
    <tr style="border-bottom: 1px solid #f1f1f1;">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>    
        <th style="text-align: left;width: 36%;"><strong><?php echo Portal::language('grand_total');?></strong> <?php 
				if(($this->map['foc_all']))
				{?>(FOC)
				<?php
				}
				?></th>
        <th style="text-align: right;width: 14%"><div class="auto_amount" style="display: ;"><strong class="change_num"><?php echo System::display_number(round($this->map['grand_total_before_tax']));?></strong></div></th>
        <th style="text-align: right;width: 12%;"><div class="auto_service" style="display: ;"><strong class="change_num"><?php echo System::display_number(round($this->map['grand_service_total']));?></strong></div></th>
        <th style="text-align: right;width: 10%"><div class="auto_tax" style="display: ;"><strong class="change_num"><?php echo System::display_number(round($this->map['grand_tax_total']));?></strong></div></th>
        <th style="text-align: right;"><strong class="change_num auto_total"><?php echo System::display_number($this->map['total']+$total_deposit);?></strong></th>
    </tr>

 <?php if(isset($this->map['deposits']) and is_array($this->map['deposits'])){ foreach($this->map['deposits'] as $key24=>&$item24){if($key24!='current'){$this->map['deposits']['current'] = &$item24;?>
 
    <tr style="border-bottom: 1px solid #f1f1f1;" >
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>       
        <th style="text-align: left;width: 30%;"><strong>Đặt cọc</strong> <?php 
				if(($this->map['foc_all']))
				{?>(FOC)
				<?php
				}
				?></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number($this->map['deposits']['current']['amount']);?></strong></th>
    </tr>

 <?php }}unset($this->map['deposits']['current']);} ?>
 <?php if($total_deposit>0){?>
    
    <tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>      
        <th style="text-align: left;width: 37%;"><strong>Phải thanh toán</strong> <?php 
				if(($this->map['foc_all']))
				{?>(FOC)
				<?php
				}
				?></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number($this->map['total']);?></strong></th>
    </tr>
    
  <?php } ?>
  </table>
  <hr />
  <table style="width: 95%; margin: 0px auto;">  
    <tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>       
        <th style="text-align: left;width: 37%;"><strong><?php if($this->map['total']>=0){echo Portal::language('total_billing');}else{echo Portal::language('return');}?> </strong><?php 
				if(($this->map['foc_all']))
				{?>(FOC)
				<?php
				}
				?></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php if($this->map['total']<0 && $this->map['total_refund_vnd']>0){ echo System::display_number(-$this->map['total_payment_vnd']); } else echo System::display_number($this->map['total_payment_vnd']);?></strong></th>
    </tr>
    
    <tr style="border-bottom: 1px solid #f1f1f1;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>       
        <th style="text-align: left;width: 37%;"><strong><?php echo Portal::language('total_remain');?></strong></i> <?php 
				if(($this->map['foc_all']))
				{?>(FOC)
				<?php
				}
				?></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php if($this->map['total']>=0){echo System::display_number($this->map['total'] - $this->map['total_payment_vnd']);}else{echo System::display_number_report($this->map['total'] - $this->map['total_payment_vnd']);}?></strong></th>
    </tr>
	<?php 
				if((isset($this->map['total_bank_fee']) and $this->map['total_bank_fee']>0))
				{?>
    
    <tr style="border-bottom: 1px solid #f1f1f1;display:none;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>     
        <th style="text-align: left;width: 37%;"><?php echo Portal::language('bank_fee');?> (<?php echo $this->map['bank_fee_percen'];?>%)</th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number($this->map['total_bank_fee']);?></strong></th>
    </tr>
    
    <tr style="border-bottom: 1px solid #f1f1f1;display:none;" class="auto_total">
        <th style="text-align: center;width: 18%;" colspan="2">&nbsp;</th>      
        <th style="text-align: left;width: 37%;"><?php echo Portal::language('total_with_bank_fee_1');?></th>
        <th style="text-align: right;" colspan="3"><strong>&nbsp;</strong></th>
        <th style="text-align: right;"><strong class="change_num"><?php echo System::display_number(round($this->map['total_with_bank_fee']));?></strong></th>
    </tr>
    
	
				<?php
				}
				?>
    </table>
	<div align="center"> 
    <?php $j=0;?>
    <?php if(isset($this->map['payments']) and is_array($this->map['payments'])){ foreach($this->map['payments'] as $key25=>&$item25){if($key25!='current'){$this->map['payments']['current'] = &$item25;?>
    	<?php $j++;?>
    <?php }}unset($this->map['payments']['current']);} ?>
    <?php if($j>0){?>
  <table width="800px" style="border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px" class="nor auto_total">
  	<tr>
  		<th align="center"><?php echo Portal::language('payment_type');?></th >
        <th align="center"><?php echo Portal::language('bank_account');?>/<?php echo Portal::language('card_number');?></th >
        <th align="center"><?php echo Portal::language('description');?></th >
        <th align="center"><?php echo Portal::language('currency');?></th >
        <th align="right"><?php echo Portal::language('total');?></th >
        <th align="right"><?php echo Portal::language('bank_fee');?></th >
        <th align="right"><?php echo Portal::language('total_with_bank_fee');?></th >
  	</tr>
    <?php if(isset($this->map['payments']) and is_array($this->map['payments'])){ foreach($this->map['payments'] as $key26=>&$item26){if($key26!='current'){$this->map['payments']['current'] = &$item26;?>
    	<tr>
  		<td align="center"><?php echo Portal::language(strtolower($this->map['payments']['current']['payment_type_id']));?></td>
        <td align="center"><?php echo $this->map['payments']['current']['bank_acc'];?></td>
        <td align="center"><?php echo $this->map['payments']['current']['description'];?></td>
        <td align="center"><?php echo $this->map['payments']['current']['currency_id'];?></td>
        <td align="right" class="change_num"><?php echo System::display_number($this->map['payments']['current']['total']);?></td>
        <td align="right" class="change_num"><?php echo System::display_number($this->map['payments']['current']['bank_fee']);?></td>
        <td align="right" class="change_num"><?php echo System::display_number(round($this->map['payments']['current']['total'] + $this->map['payments']['current']['bank_fee']));?></td>
  	</tr>
    <?php }}unset($this->map['payments']['current']);} ?>
  </table>
    <!-- N?u c? thanh to?n th? ki?m tra xem c? member_code kh?ng -->
    <?php if($this->map['member_code']!=''){ ?>
        <hr style="width: 100%; text-decoration: none; margin: 3px auto;" />
        <table width="800px" style="border-collapse:collapse;" border="0" bordercolor="#CCCCCC" cellpadding="3px">
            <tr>
                <th style="text-align: right; text-transform: uppercase;"><?php echo Portal::language('payment_point_member');?></th>
            </tr>
            <tr>
                <th style="text-align: right;"><?php echo Portal::language('point');?>: <?php if($this->map['point']>0) echo "+"; ?><?php echo $this->map['point'];?></th>
            </tr>
            <tr>
                <th style="text-align: right;"><?php echo Portal::language('point_user');?>: <?php if($this->map['point_user']>0) echo "+"; ?><?php echo $this->map['point_user'];?></th>
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
		<?php echo Portal::language('guest_signature');?>
 	   </td>
       <td colspan="3">&nbsp;</td>
		<td width="50%" align="center" valign="top">
			<br /><br /><br />
			<?php echo Portal::language('cashier_signature');?>
		</td>
	</tr>
</table>	
</div>
</td></tr></table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
        fun_check_option(1);
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
           , fileName: '<?php echo Portal::language('bill_folio');?>'
        });       
    }else
    {
        jQuery('#Content_Full').remove();
        jQuery("#Export").battatech_excelexport({
            containerid: "Export"
           , datatype: 'table'
           , fileName: '<?php echo Portal::language('bill_folio');?>'
        });    
    }
    ViewTravellerFolioForm.submit();
});
</script>