<style>
#invoice tr,#invoice tr,#invoice div,#invoice th{font-weight: normal 1important;}
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
<div id="invoice">
    <table id="dn_printer" width="100%">
        <tr>
            <td width="75%">&nbsp;</td>
            <td align="right"><input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 70px; height: 23px"/></td>
            <td align="right"><input  name="print" id="short" onclick="fun_check_option(1)"  onchange="check_option();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('print'));?>"><?php echo Portal::language('print_short');?></td>
            <td align="right"><input  name="print" id="full" onclick="fun_check_option(2)" checked="" onchange="check_option();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('print'));?>"><?php echo Portal::language('print_full');?></td>
            <td align="right" style="vertical-align: bottom;" >
                <a onclick="print_payment_invoice();" title="In">
                    <img src="packages/core/skins/default/images/printer.png" height="40" />
                </a>
            </td>
        </tr>
    </table>
	<div class="item-body"><br /></div>
	<?php 
				if((1==1))
				{?>

<form id="ViewGroupInvoiceForm" method="post">
<table id="Export" width="100%" style="position: relative; float:left;"><tr><td>
	<table cellpadding="0" width="100%" border="0">
    	<tr>
    		<td width="20%" align="center" valign="top" id="imgs"><img src="<?php echo HOTEL_LOGO;?>" width="200"></td>
            <td colspan="3">&nbsp;</td>
    		<td width="70%" align="center">
    			<div class="invoice-title" style="text-transform: uppercase;">
                <?php if($this->map['check_payment']==0){ ?>
            <?php echo Portal::language('list_of_goods_and_services');?> &nbsp;(DRAG)
            <?php }else{ ?>
            <?php echo Portal::language('bill_folio');?>
            <?php } ?></div>
    			<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
    			<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
    			<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
    		</td>
            <td colspan="2">&nbsp;</td>
    		<td width="10%" align="right">
    			<div>
    			   <p><font style="font-size: 14px;">Folio:<!--</font> <font style="font-size:15px;font-weight:200"><?php echo $this->map['bill_number'];?></font><br /></p>-->
                      <?php if(isset($this->map['folio_code']) and $this->map['folio_code'] != ''){?>
                        <?php echo 'No.F'. str_pad($this->map['folio_code'],6,"0",STR_PAD_LEFT) ;?>
                        <br />
                        <?php echo 'Ref'.str_pad($this->map['bill_number'],6,"0",STR_PAD_LEFT) ?>
                      <?php } else {?>
                        <?php echo 'Ref'.str_pad($this->map['bill_number'],6,"0",STR_PAD_LEFT) ?>
                      <?php } ?>
                  
                   </font><br /></p>
                   <p><font style="font-size: 14px; ">Re.code:</font> <font style="font-size:15px;font-weight:200"><?php echo $this->map['reservation_id'];?></font><br /></p>
                </div>
    		</td>
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
	<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
	<table cellpadding="0" width="100%">
    	<tr valign="top">
    		<td align="left" width="50%">
    			<div class="item-body"><div><?php echo Portal::language('company');?>: <?php echo $this->map['guest'];?></div></div>
    			<div class="item-body"><div><?php echo Portal::language('guest_name');?>: <?php echo $this->map['traveller_name'];?></div></div>
    			<div class="item-body"><div><?php echo Portal::language('address');?>: <?php echo $this->map['address'];?></div></div>
                <div class="item-body"><div><?php echo Portal::language('arrival_date_new');?>: <?php echo $this->map['arrival_time'];?></div>
                <div><?php echo Portal::language('departure_date_new');?>: <?php echo $this->map['departure_time'];?></div></div>
            </td>
            <td colspan="5">&nbsp;</td>
    		<td align="right" width="50%">
    			<div><?php echo Portal::language('currency');?>: <?php echo HOTEL_CURRENCY;?></div>
                <div><?php echo Portal::language('print_by');?>: <?php echo $this->map['account_name'];?> <?php //echo Session::get('user_id');?></div>
                <div><?php echo Portal::language('print_time');?>: <?php echo date('H:i d/m/y');?></div>
                <div><?php echo Portal::language('created_by');?>: <?php echo $this->map['create_folio_user'];?></div>
                <div><?php echo Portal::language('created_time');?>: <?php echo $this->map['create_folio_time'];?></div></div>
            </td>
        </tr>
        <?php if(isset($this->map['member_code']) AND $this->map['member_code']!=''){ ?>
          <tr>
              <td colspan="2"><?php echo Portal::language('member_code');?>: <?php echo $this->map['member_code'];?> - <?php echo Portal::language('member_level');?>: <?php echo $this->map['member_level'];?></td>  
          </tr>
          <?php } ?>
	</table>
    
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <?php $total=0;?>
    <div class="item-body" id="Content_Full">
        <table width="100%" >
            <tr>
                <td align="left" valign="top"><?php echo Portal::language('num');?></td>
                <td align="left" valign="top"><?php echo Portal::language('date');?></td>
                <td align="left" valign="top"><?php echo Portal::language('description');?></td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;"><?php echo Portal::language('amount');?></div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;"><?php echo Portal::language('ser_charge');?></div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;"><?php echo Portal::language('tax');?></div></td>
                <td align="right" valign="top" class="change_num auto_total"><?php echo Portal::language('total');?></td>
            </tr>
            <?php $i=0; ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <?php 
				if(($this->map['items']['current']['type']!= 'DEPOSIT_GROUP' && $this->map['items']['current']['type']!= 'DEPOSIT' && $this->map['items']['current']['type']!= 'DISCOUNT')  )
				{?>
            <?php $stt = ++$i;?>
            <tr>
                
                <td align="left" valign="top"><?php echo $stt;?></td>
                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['date_use']);?></td>
                <td align="left" valign="top"><?php if($this->map['items']['current']['type']=='BAR' OR $this->map['items']['current']['type']=='MASSAGE'){ $descr = explode('_',$this->map['items']['current']['description']); echo $descr[0]; }else{ ?><?php echo $this->map['items']['current']['description'];?><?php } ?>
                <?php echo $this->map['items']['current']['hk_code'] != ''? '('.$this->map['items']['current']['hk_code'].')' : ''?>
                <?php echo $this->map['items']['current']['ex_note'] != ''? '('.$this->map['items']['current']['ex_note'].')':'';  ?>
                <?php if($this->map['items']['current']['foc_all'] == 1) echo '(FOC_ALL)';?>
                <?php if($this->map['items']['current']['type']=='LAUNDRY'){ ?><a target="_blank" href="?page=laundry_invoice&cmd=edit&id=<?php echo $this->map['items']['current']['invoice_id'];?>">#LD_<?php echo $this->map['items']['current']['position'];?></a>
                    <table width=100%>
                        <?php if(isset($this->map['items']['current']['product']) and is_array($this->map['items']['current']['product'])){ foreach($this->map['items']['current']['product'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['product']['current'] = &$item2;?>
                        <tr>
                            <td width=30% style="font-size: 11px; padding-left: 15px;">+<i><?php echo $this->map['items']['current']['product']['current']['name'];?></i></td>
                            <td width=30% style="font-size: 12px;"><em><?php echo Portal::language('price');?>: </em><i class="change_num auto_amount"><?php echo System::display_number($this->map['items']['current']['product']['current']['price']);?></i></td>
                            <td width=30% style="font-size: 12px;"><em><?php echo Portal::language('quantity');?>: </em><i class="change_num"><?php echo $this->map['items']['current']['product']['current']['quantity'];?></i></td>
                        </tr>
                        <?php }}unset($this->map['items']['current']['product']['current']);} ?>
                    </table>
                <?php } ?>
                <?php if($this->map['items']['current']['type']=='MINIBAR'){ ?>
                    <a target="_blank" href="?page=minibar_invoice&cmd=edit&id=<?php echo $this->map['items']['current']['invoice_id'];?>">#MN_<?php echo $this->map['items']['current']['position'];?></a>
                    <table width=100%>
                        <?php if(isset($this->map['items']['current']['product']) and is_array($this->map['items']['current']['product'])){ foreach($this->map['items']['current']['product'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['product']['current'] = &$item3;?>
                        <tr>
                            <td width=30% style="font-size: 11px; padding-left: 15px;">+<i><?php echo $this->map['items']['current']['product']['current']['name'];?></i></td>
                            <td width=30% style="font-size: 12px;"><em><?php echo Portal::language('price');?>: </em><i class="change_num auto_amount"><?php echo System::display_number($this->map['items']['current']['product']['current']['price']);?></i></td>
                            <td width=30% style="font-size: 12px;"><em><?php echo Portal::language('quantity');?>: </em><i class="change_num"><?php echo $this->map['items']['current']['product']['current']['quantity'];?></i></td>
                        </tr>
                        <?php }}unset($this->map['items']['current']['product']['current']);} ?>
                    </table>
                <?php } ?>
                <?php if($this->map['items']['current']['type']=='EQUIPMENT'){ ?>
                    <a target="_blank" href="?page=equipment_invoice&portal=default&cmd=detail&id=<?php echo $this->map['items']['current']['invoice_id'];?>">#EQ_<?php echo $this->map['items']['current']['position'];?></a>
                    <table width=100%>
                        <?php if(isset($this->map['items']['current']['product']) and is_array($this->map['items']['current']['product'])){ foreach($this->map['items']['current']['product'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['product']['current'] = &$item4;?>
                        <tr>
                            <td width=30% style="font-size: 11px; padding-left: 15px;">+<i><?php echo $this->map['items']['current']['product']['current']['name'];?></i></td>
                            <td width=30% style="font-size: 12px;"><em><?php echo Portal::language('price');?>: </em><i class="change_num auto_amount"><?php echo System::display_number($this->map['items']['current']['product']['current']['price']);?></i></td>
                            <td width=30% style="font-size: 12px;"><em><?php echo Portal::language('quantity');?>: </em><i class="change_num"><?php echo $this->map['items']['current']['product']['current']['quantity'];?></i></td>
                        </tr>
                        <?php }}unset($this->map['items']['current']['product']['current']);} ?>
                    </table>
                <?php } ?>
                    
                <?php if($this->map['items']['current']['type']=='EXTRA_SERVICE'){ ?><a target="_blank" href="?page=extra_service_invoice&cmd=view_receipt&id=<?php echo $this->map['items']['current']['ex_id'];?>">#<?php echo $this->map['items']['current']['ex_bill'];?></a><?php } ?>
                <?php if($this->map['items']['current']['type']=='BAR'){ ?><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=<?php echo $this->map['items']['current']['invoice_id'];?>">#<?php echo $this->map['items']['current']['code'];?></a><?php } ?>
                <?php if($this->map['items']['current']['type']=='MASSAGE'){ ?><a target="_blank" href="?page=massage_daily_summary&cmd=invoice&id=<?php echo $this->map['items']['current']['invoice_id'];?>">#<?php echo $this->map['items']['current']['invoice_id'];?></a><?php } ?>
                </td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['items']['current']['amount']));?></div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['items']['current']['service_amount']));?></div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['items']['current']['tax_amount']));?></div></td>
                <td align="right" valign="top" class="change_num auto_total"><?php echo System::display_number(round($this->map['items']['current']['total_amount']));?></td>
                
            </tr>
            
				<?php
				}
				?>
            <?php }}unset($this->map['items']['current']);} ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current'] = &$item5;?>
            <?php 
				if(($this->map['items']['current']['type'] == 'DISCOUNT')  )
				{?>
            <?php $stt = ++$i; $ount_dis = 0;?>
            <?php if($ount_dis == 0){ ?>
            <tr>
                <td colspan="7">
                    <hr width="100%" />
                </td>
            </tr>
            <?php } ?>
            <tr>
                
                <td align="left" valign="top"><?php echo $stt;?></td>
                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['date_use']);?></td>
                <td align="left" valign="top"><?php echo $this->map['items']['current']['description'];?> 
                <?php echo $this->map['items']['current']['hk_code'] != ''? '('.$this->map['items']['current']['hk_code'].')' : ''?>
                <?php echo $this->map['items']['current']['ex_note'] != ''? '('.$this->map['items']['current']['ex_note'].')':'';  ?>
                <?php 
                    if($this->map['items']['current']['foc_all'] == 1)
                    {
                        echo '(FOC_ALL)';
                    }
                 ?>
                </td>
                <td align="right" valign="top" colspan="3">&nbsp;</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;" class="change_num"><?php echo System::display_number($this->map['items']['current']['amount']);?></div></td>
            </tr>
            
				<?php
				}
				?>
            <?php }}unset($this->map['items']['current']);} ?>
        </table>
    </div>
    <div class="item-body" id="Content_Short">
        <table width="100%" >
            <tr>
                <td align="left" valign="top"><?php echo Portal::language('num');?></td>
                <td align="left" valign="top"><?php echo Portal::language('date');?></td>
                <td align="left" valign="top"><?php echo Portal::language('description');?></td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;"><?php echo Portal::language('amount');?></div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;"><?php echo Portal::language('ser_charge');?></div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;"><?php echo Portal::language('tax');?></div></td>
                <td align="right" valign="top"><div class="auto_total" style="display: ;"><?php echo Portal::language('total');?></div></td>
            </tr>
            <?php $stt =1; ?>
            <?php if(isset($this->map['items_short']) and is_array($this->map['items_short'])){ foreach($this->map['items_short'] as $key6=>&$item6){if($key6!='current'){$this->map['items_short']['current'] = &$item6;?>
            <tr>
                <td align="left" valign="top"><?php echo $stt++; ?></td>
                <td align="left" valign="top"></td>
                <td align="left" valign="top"><?php echo $this->map['items_short']['current']['description'];?></td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round($this->map['items_short']['current']['amount'])); ?></div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;"><?php echo System::display_number(round($this->map['items_short']['current']['service_amount'])); ?></div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round($this->map['items_short']['current']['tax_amount'])); ?></div></td>
                <td align="right" valign="top"><div class="auto_total" style="display: ;"><?php echo System::display_number(round($this->map['items_short']['current']['total_amount'])); ?></div></td>
            </tr>
            <?php }}unset($this->map['items_short']['current']);} ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key7=>&$item7){if($key7!='current'){$this->map['items']['current'] = &$item7;?>
            <?php 
				if(($this->map['items']['current']['type'] == 'DISCOUNT')  )
				{?>
            <?php $ount_dis = 0;?>
            <?php if($ount_dis == 0){ ?>
            <tr>
                <td colspan="7">
                    <hr width="100%" />
                </td>
            </tr>
            <?php } ?>
            <tr>
                
                <td align="left" valign="top"><?php echo $stt;?></td>
                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date($this->map['items']['current']['date_use']);?></td>
                <td align="left" valign="top"><?php echo $this->map['items']['current']['description'];?> 
                <?php echo $this->map['items']['current']['hk_code'] != ''? '('.$this->map['items']['current']['hk_code'].')' : ''?>
                <?php echo $this->map['items']['current']['ex_note'] != ''? '('.$this->map['items']['current']['ex_note'].')':'';  ?>
                <?php 
                    if($this->map['items']['current']['foc_all'] == 1)
                    {
                        echo '(FOC_ALL)';
                    }
                 ?>
                </td>
                <td align="right" valign="top">&nbsp;</td>
                <td align="right" valign="top">&nbsp;</td>
                <td align="right" valign="top">&nbsp;</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;" class="change_num"><?php echo System::display_number($this->map['items']['current']['amount']);?></div></td>
            </tr>
            
				<?php
				}
				?>
            <?php }}unset($this->map['items']['current']);} ?>
        </table>
    </div>
    <hr />
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <table width="100%" class="auto_total">
        <tr>
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em><?php echo Portal::language('total_amount');?>: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round($this->map['total_amount']));?></span></em></b> 
            </td>
        </tr>            
            <?php 
				if(($this->map['total_foc']>0))
				{?>
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em><?php echo Portal::language('total_foc');?>: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round($this->map['total_foc']));?></span></em></b> 
            </td>
            
				<?php
				}
				?>
        </tr>            
            <?php 
				if(($this->map['total_group_deposit']>0))
				{?>
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em><?php echo Portal::language('deposit_group');?>: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round($this->map['total_group_deposit']));?></span></em></b> 
            </td>
            
				<?php
				}
				?>
        </tr>            
            <?php 
				if(($this->map['total_deposit']>0))
				{?>
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em><?php echo Portal::language('deposit');?>: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round($this->map['total_deposit']));?></span></em></b> 
            </td>
            
				<?php
				}
				?>
        </tr>
        </tr>            
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em><?php echo Portal::language('total_payment');?>: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round($this->map['total_payment']));?></span></em></b> 
            </td>
        </tr>
        <?php
            $total_debit =0;
            $total_refund = 0;
            foreach($this->map['payments'] as $k=>$val)
            {
                if($val['payment_type_id'] =='DEBIT' || $val['payment_type_id'] =='BANK')
                {
                    $total_debit +=$val['total_vnd'];
                    continue;  
                }else{
                    if($val['payment_type_id'] =='REFUND')
                    {
                        $val['payment_type_id'] = 'Refund';
                        $total_refund += $val['total_vnd'];
                    }
                    else
                    {
                        $val['payment_type_id'] = 'total_payment_1';
                    }
                    
                } 
                echo '<tr><td colspan="5"></td>';  
                echo '<td width="40%" style="text-align: left;"><b><span  style="text-align: left;"><em>'.Portal::language(strtolower($val['payment_type_id'])).': </em></span></b></td>';
                echo '<td width="14%" style="text-align: right;"><b><span class="change_num"  style="text-align: right;">'.System::display_number(round($val['total_vnd'])).'</span></em></span></b></td></tr>';
            }
            if($total_debit !=0)
            {
                echo '<tr><td colspan="5"></td>';
                echo '<td width="40%" style="text-align: left;"><b><span  style="text-align: left;"><em>'.Portal::language(strtolower('total_payment_debit')).': </em></span></b></td>';
                echo '<td width="14%" style="text-align: right;"><b><span class="change_num"  style="text-align: right;">'.System::display_number(round($total_debit)).'</span></em></span></b></td></tr>';
            }
        ?>          
        <tr>            
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em><?php echo Portal::language('remain');?>: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php if($this->map['total_payment']>=0){echo System::display_number(round( $this->map['total_payment'] - $this->map['total_payment_vnd'] +  $total_refund));}else{echo System::display_number(round( -$this->map['total_payment'] + $this->map['total_payment_vnd'] -  $total_refund));}?></span></em></b> 
            </td>                                                            
        </tr>
    </table>
    <?php 
				if((1==1))
				{?>
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <div style="float:right; width: 100%;" class="auto_total"> 
        <table width="100%" style="float:right; border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px">
          	<tr>
          		<td align="center"><i><?php echo Portal::language('description');?></i></td>
                <td align="center"><i><?php echo Portal::language('payment_type');?></i></td>
                <td align="center"><i><?php echo Portal::language('currency');?></i></td>
                <td align="center"><i><?php echo Portal::language('amount');?></i></td>
                <td align="center"><i><?php echo Portal::language('bank_account');?></i></td>
                <td align="center"><i><?php echo Portal::language('bank_fee');?></i></td>
                <td align="center"><i><?php echo Portal::language('total');?></i></td>
          	</tr>
            <?php $si = 0; ?>
            <?php if(isset($this->map['payments']) and is_array($this->map['payments'])){ foreach($this->map['payments'] as $key8=>&$item8){if($key8!='current'){$this->map['payments']['current'] = &$item8;?>
            <?php $si++; ?>
            	<tr>
          		<td align="center"><?php echo $this->map['payments']['current']['description'];?></td>
                <td align="center"><?php echo Portal::language(strtolower($this->map['payments']['current']['payment_type_id'])); if($this->map['payments']['current']['credit_card_id']!=''){echo '('.$this->map['payments']['current']['credit_card_name'].')';}?></td>
                <td align="center"><?php echo $this->map['payments']['current']['currency_id'];?></td>
                <td align="right" class="change_num"><?php echo System::display_number($this->map['payments']['current']['total']);?></td>
                <td align="center"><?php echo $this->map['payments']['current']['bank_acc'];?></td>
                <td align="right" class="change_num"><?php echo System::display_number($this->map['payments']['current']['bank_fee']);?></td>
                <td align="right" class="change_num"><?php echo System::display_number($this->map['payments']['current']['total'] + $this->map['payments']['current']['bank_fee']);?></td>
          	</tr>
            <?php }}unset($this->map['payments']['current']);} ?>
        </table>
        <?php if($si>0){ ?>
             <!-- N?u có thanh toán thì ki?m tra xem có member_code không -->
            <?php if(isset($this->map['member_code']) AND $this->map['member_code']!=''){ ?>
                <hr style="width: 100%; text-decoration: none; margin: 3px auto;" />
                <table width="100%" style="float:right;">
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
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    	<tr>
            <td>&nbsp;</td>
    		<td align="center"><br /><br />
    		<?php echo Portal::language('cashier_signature');?>
     	   </td>
            <td colspan="3">&nbsp;</td>
    		<td width="50%" align="center" valign="top">
    			<br /><br />
                <?php echo Portal::language('guest_signature');?>
    		</td>
    	</tr>
    	<tr>
    		<td colspan="2" align="center"><br /><br /></td>
    	</tr>
    </table>
     <?php }else{ ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
        	<td height="100">&nbsp;</td>
    	</tr>
    </table>	

				<?php
				}
				?>
</div>
</td></tr></table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
    jQuery("#export_excel").css('width','100px');
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
    jQuery('#cmt_dn').remove();
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
    ViewGroupInvoiceForm.submit();
});
</script>