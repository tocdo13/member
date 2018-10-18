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
        <label>[[.hide_amount.]] <input id="hide_amount" type="checkbox" onclick="CheckHideShow('AMOUNT');" /></label>
        <label>[[.hide_service.]] <input id="hide_service" type="checkbox" onclick="CheckHideShow('SERVICE');" /></label>
        <label>[[.hide_tax.]] <input id="hide_tax" type="checkbox" onclick="CheckHideShow('TAX');" /></label>
        <label>[[.hide_total_amount.]] <input id="hide_total" type="checkbox" onclick="CheckHideShow('TOTAL');" /></label>
    </p>
</div>
<div id="invoice">
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
	<div class="item-body"><br /></div>
	<!--IF:cond(1==1)-->

<form id="ViewGroupInvoiceForm" method="post">
<table id="Export" width="100%" style="position: relative; float:left;"><tr><td>
	<table cellpadding="0" width="100%" border="0">
    	<tr>
    		<td width="20%" align="center" valign="top" id="imgs"><img src="<?php echo HOTEL_LOGO;?>" width="200"></td>
            <td colspan="3">&nbsp;</td>
    		<td width="70%" align="center">
    			<div class="invoice-title" style="text-transform: uppercase;">
                <?php if([[=check_payment=]]==0){ ?>
            [[.list_of_goods_and_services.]] &nbsp;(DRAG)
            <?php }else{ ?>
            [[.bill_folio.]]
            <?php } ?></div>
    			<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
    			<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
    			<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
    		</td>
            <td colspan="2">&nbsp;</td>
    		<td width="10%" align="right">
    			<div>
    			   <p><font style="font-size: 14px;">Folio:<!--</font> <font style="font-size:15px;font-weight:200">[[|bill_number|]]</font><br /></p>-->
                      <?php if(isset([[=folio_code=]]) and [[=folio_code=]] != ''){?>
                        <?php echo 'No.F'. str_pad([[=folio_code=]],6,"0",STR_PAD_LEFT) ;?>
                        <br />
                        <?php echo 'Ref'.str_pad([[=bill_number=]],6,"0",STR_PAD_LEFT) ?>
                      <?php } else {?>
                        <?php echo 'Ref'.str_pad([[=bill_number=]],6,"0",STR_PAD_LEFT) ?>
                      <?php } ?>
                  
                   </font><br /></p>
                   <p><font style="font-size: 14px; ">Re.code:</font> <font style="font-size:15px;font-weight:200">[[|reservation_id|]]</font><br /></p>
                </div>
    		</td>
    	</tr>
	</table>
	<!--ELSE-->
	<table cellpadding="0" width="100%" border="0">
    	<tr>
    		<td height="100">&nbsp;</td>
    	</tr>
	</table>
	<!--/IF:cond-->
	<div class="item-body"><div class="seperator-line">&nbsp;</div></div>
	<table cellpadding="0" width="100%">
    	<tr valign="top">
    		<td align="left" width="50%">
    			<div class="item-body"><div>[[.company.]]: [[|guest|]]</div></div>
    			<div class="item-body"><div>[[.guest_name.]]: [[|traveller_name|]]</div></div>
    			<div class="item-body"><div>[[.address.]]: [[|address|]]</div></div>
                <div class="item-body"><div>[[.arrival_date_new.]]: [[|arrival_time|]]</div>
                <div>[[.departure_date_new.]]: [[|departure_time|]]</div></div>
            </td>
            <td colspan="5">&nbsp;</td>
    		<td align="right" width="50%">
    			<div>[[.currency.]]: <?php echo HOTEL_CURRENCY;?></div>
                <div>[[.print_by.]]: [[|account_name|]] <?php //echo Session::get('user_id');?></div>
                <div>[[.print_time.]]: <?php echo date('H:i d/m/y');?></div>
                <div>[[.created_by.]]: [[|create_folio_user|]]</div>
                <div>[[.created_time.]]: [[|create_folio_time|]]</div></div>
            </td>
        </tr>
        <?php if(isset([[=member_code=]]) AND [[=member_code=]]!=''){ ?>
          <tr>
              <td colspan="2">[[.member_code.]]: [[|member_code|]] - [[.member_level.]]: [[|member_level|]]</td>  
          </tr>
          <?php } ?>
	</table>
    
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <?php $total=0;?>
    <div class="item-body" id="Content_Full">
        <table width="100%" >
            <tr>
                <td align="left" valign="top">[[.num.]]</td>
                <td align="left" valign="top">[[.date.]]</td>
                <td align="left" valign="top">[[.description.]]</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;">[[.amount.]]</div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;">[[.ser_charge.]]</div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;">[[.tax.]]</div></td>
                <td align="right" valign="top" class="change_num auto_total">[[.total.]]</td>
            </tr>
            <?php $i=0; ?>
            <!--LIST:items-->
            <!--IF:cond0([[=items.type=]]!= 'DEPOSIT_GROUP' && [[=items.type=]]!= 'DEPOSIT' && [[=items.type=]]!= 'DISCOUNT')  -->
            <?php $stt = ++$i;?>
            <tr>
                
                <td align="left" valign="top"><?php echo $stt;?></td>
                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date([[=items.date_use=]]);?></td>
                <td align="left" valign="top"><?php if([[=items.type=]]=='BAR' OR [[=items.type=]]=='MASSAGE'){ $descr = explode('_',[[=items.description=]]); echo $descr[0]; }else{ ?>[[|items.description|]]<?php } ?>
                <?php echo [[=items.hk_code=]] != ''? '('.[[=items.hk_code=]].')' : ''?>
                <?php echo [[=items.ex_note=]] != ''? '('.[[=items.ex_note=]].')':'';  ?>
                <?php if([[=items.foc_all=]] == 1) echo '(FOC_ALL)';?>
                <?php if([[=items.type=]]=='LAUNDRY'){ ?><a target="_blank" href="?page=laundry_invoice&cmd=edit&id=[[|items.invoice_id|]]">#LD_[[|items.position|]]</a>
                    <table width=100%>
                        <!--LIST:items.product-->
                        <tr>
                            <td width=30% style="font-size: 11px; padding-left: 15px;">+<i>[[|items.product.name|]]</i></td>
                            <td width=30% style="font-size: 12px;"><em>[[.price.]]: </em><i class="change_num auto_amount"><?php echo System::display_number([[=items.product.price=]]);?></i></td>
                            <td width=30% style="font-size: 12px;"><em>[[.quantity.]]: </em><i class="change_num">[[|items.product.quantity|]]</i></td>
                        </tr>
                        <!--/LIST:items.product-->
                    </table>
                <?php } ?>
                <?php if([[=items.type=]]=='MINIBAR'){ ?>
                    <a target="_blank" href="?page=minibar_invoice&cmd=edit&id=[[|items.invoice_id|]]">#MN_[[|items.position|]]</a>
                    <table width=100%>
                        <!--LIST:items.product-->
                        <tr>
                            <td width=30% style="font-size: 11px; padding-left: 15px;">+<i>[[|items.product.name|]]</i></td>
                            <td width=30% style="font-size: 12px;"><em>[[.price.]]: </em><i class="change_num auto_amount"><?php echo System::display_number([[=items.product.price=]]);?></i></td>
                            <td width=30% style="font-size: 12px;"><em>[[.quantity.]]: </em><i class="change_num">[[|items.product.quantity|]]</i></td>
                        </tr>
                        <!--/LIST:items.product-->
                    </table>
                <?php } ?>
                <?php if([[=items.type=]]=='EQUIPMENT'){ ?>
                    <a target="_blank" href="?page=equipment_invoice&portal=default&cmd=detail&id=[[|items.invoice_id|]]">#EQ_[[|items.position|]]</a>
                    <table width=100%>
                        <!--LIST:items.product-->
                        <tr>
                            <td width=30% style="font-size: 11px; padding-left: 15px;">+<i>[[|items.product.name|]]</i></td>
                            <td width=30% style="font-size: 12px;"><em>[[.price.]]: </em><i class="change_num auto_amount"><?php echo System::display_number([[=items.product.price=]]);?></i></td>
                            <td width=30% style="font-size: 12px;"><em>[[.quantity.]]: </em><i class="change_num">[[|items.product.quantity|]]</i></td>
                        </tr>
                        <!--/LIST:items.product-->
                    </table>
                <?php } ?>
                    
                <?php if([[=items.type=]]=='EXTRA_SERVICE'){ ?><a target="_blank" href="?page=extra_service_invoice&cmd=view_receipt&id=[[|items.ex_id|]]">#[[|items.ex_bill|]]</a><?php } ?>
                <?php if([[=items.type=]]=='BAR'){ ?><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=[[|items.invoice_id|]]">#[[|items.code|]]</a><?php } ?>
                <?php if([[=items.type=]]=='MASSAGE'){ ?><a target="_blank" href="?page=massage_daily_summary&cmd=invoice&id=[[|items.invoice_id|]]">#[[|items.invoice_id|]]</a><?php } ?>
                </td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=items.amount=]]));?></div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=items.service_amount=]]));?></div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=items.tax_amount=]]));?></div></td>
                <td align="right" valign="top" class="change_num auto_total"><?php echo System::display_number(round([[=items.total_amount=]]));?></td>
                
            </tr>
            <!--/IF:cond0-->
            <!--/LIST:items-->
            <!--LIST:items-->
            <!--IF:cond0([[=items.type=]] == 'DISCOUNT')  -->
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
                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date([[=items.date_use=]]);?></td>
                <td align="left" valign="top">[[|items.description|]] 
                <?php echo [[=items.hk_code=]] != ''? '('.[[=items.hk_code=]].')' : ''?>
                <?php echo [[=items.ex_note=]] != ''? '('.[[=items.ex_note=]].')':'';  ?>
                <?php 
                    if([[=items.foc_all=]] == 1)
                    {
                        echo '(FOC_ALL)';
                    }
                 ?>
                </td>
                <td align="right" valign="top" colspan="3">&nbsp;</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;" class="change_num"><?php echo System::display_number([[=items.amount=]]);?></div></td>
            </tr>
            <!--/IF:cond0-->
            <!--/LIST:items-->
        </table>
    </div>
    <div class="item-body" id="Content_Short">
        <table width="100%" >
            <tr>
                <td align="left" valign="top">[[.num.]]</td>
                <td align="left" valign="top">[[.date.]]</td>
                <td align="left" valign="top">[[.description.]]</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;">[[.amount.]]</div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;">[[.ser_charge.]]</div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;">[[.tax.]]</div></td>
                <td align="right" valign="top"><div class="auto_total" style="display: ;">[[.total.]]</div></td>
            </tr>
            <?php $stt =1; ?>
            <!--LIST:items_short-->
            <tr>
                <td align="left" valign="top"><?php echo $stt++; ?></td>
                <td align="left" valign="top"></td>
                <td align="left" valign="top">[[|items_short.description|]]</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;"><?php echo System::display_number(round([[=items_short.amount=]])); ?></div></td>
                <td align="right" valign="top"><div class="auto_service" style="display: ;"><?php echo System::display_number(round([[=items_short.service_amount=]])); ?></div></td>
                <td align="right" valign="top"><div class="auto_tax" style="display: ;"><?php echo System::display_number(round([[=items_short.tax_amount=]])); ?></div></td>
                <td align="right" valign="top"><div class="auto_total" style="display: ;"><?php echo System::display_number(round([[=items_short.total_amount=]])); ?></div></td>
            </tr>
            <!--/LIST:items_short-->
            <!--LIST:items-->
            <!--IF:cond0([[=items.type=]] == 'DISCOUNT')  -->
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
                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date([[=items.date_use=]]);?></td>
                <td align="left" valign="top">[[|items.description|]] 
                <?php echo [[=items.hk_code=]] != ''? '('.[[=items.hk_code=]].')' : ''?>
                <?php echo [[=items.ex_note=]] != ''? '('.[[=items.ex_note=]].')':'';  ?>
                <?php 
                    if([[=items.foc_all=]] == 1)
                    {
                        echo '(FOC_ALL)';
                    }
                 ?>
                </td>
                <td align="right" valign="top">&nbsp;</td>
                <td align="right" valign="top">&nbsp;</td>
                <td align="right" valign="top">&nbsp;</td>
                <td align="right" valign="top"><div class="auto_amount" style="display: ;" class="change_num"><?php echo System::display_number([[=items.amount=]]);?></div></td>
            </tr>
            <!--/IF:cond0-->
            <!--/LIST:items-->
        </table>
    </div>
    <hr />
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <table width="100%" class="auto_total">
        <tr>
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em>[[.total_amount.]]: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round([[=total_amount=]]));?></span></em></b> 
            </td>
        </tr>            
            <!--IF:cond_foc([[=total_foc=]]>0)-->
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em>[[.total_foc.]]: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round([[=total_foc=]]));?></span></em></b> 
            </td>
            <!--/IF:cond_foc-->
        </tr>            
            <!--IF:cond7([[=total_group_deposit=]]>0)-->
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em>[[.deposit_group.]]: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round([[=total_group_deposit=]]));?></span></em></b> 
            </td>
            <!--/IF:cond7-->
        </tr>            
            <!--IF:cond7([[=total_deposit=]]>0)-->
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em>[[.deposit.]]: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round([[=total_deposit=]]));?></span></em></b> 
            </td>
            <!--/IF:cond7-->
        </tr>
        </tr>            
            <td width="10%" colspan="5">&nbsp;</td>
            <td style="float: left;"><b width="15%"><em>[[.total_payment.]]: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php echo System::display_number(round([[=total_payment=]]));?></span></em></b> 
            </td>
        </tr>
        <?php
            $total_debit =0;
            $total_refund = 0;
            foreach([[=payments=]] as $k=>$val)
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
            <td style="float: left;"><b width="15%"><em>[[.remain.]]: </em></b></td>
            <td width="14%" style="text-align: right;">
               <b><span style="text-align:right;" class="change_num"><?php if([[=total_payment=]]>=0){echo System::display_number(round( [[=total_payment=]] - [[=total_payment_vnd=]] +  $total_refund));}else{echo System::display_number(round( [[=total_payment=]] + [[=total_payment_vnd=]] ));}?></span></em></b> 
            </td>                                                            
        </tr>
    </table>
    <!--IF:cond(1==1)-->
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <div style="float:right; width: 100%;" class="auto_total"> 
        <table width="100%" style="float:right; border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px">
          	<tr>
          		<td align="center"><i>[[.description.]]</i></td>
                <td align="center"><i>[[.payment_type.]]</i></td>
                <td align="center"><i>[[.currency.]]</i></td>
                <td align="center"><i>[[.amount.]]</i></td>
                <td align="center"><i>[[.bank_account.]]</i></td>
                <td align="center"><i>[[.bank_fee.]]</i></td>
                <td align="center"><i>[[.total.]]</i></td>
          	</tr>
            <?php $si = 0; ?>
            <!--LIST:payments-->
            <?php $si++; ?>
            	<tr>
          		<td align="center">[[|payments.description|]]</td>
                <td align="center"><?php echo Portal::language(strtolower([[=payments.payment_type_id=]])); if([[=payments.credit_card_id=]]!=''){echo '('.[[=payments.credit_card_name=]].')';}?></td>
                <td align="center">[[|payments.currency_id|]]</td>
                <td align="right" class="change_num"><?php echo System::display_number([[=payments.total=]]);?></td>
                <td align="center">[[|payments.bank_acc|]]</td>
                <td align="right" class="change_num"><?php echo System::display_number([[=payments.bank_fee=]]);?></td>
                <td align="right" class="change_num"><?php echo System::display_number([[=payments.total=]] + [[=payments.bank_fee=]]);?></td>
          	</tr>
            <!--/LIST:payments-->
        </table>
        <?php if($si>0){ ?>
             <!-- N?u có thanh toán thì ki?m tra xem có member_code không -->
            <?php if(isset([[=member_code=]]) AND $this->map['member_code']!=''){ ?>
                <hr style="width: 100%; text-decoration: none; margin: 3px auto;" />
                <table width="100%" style="float:right;">
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
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    	<tr>
            <td>&nbsp;</td>
    		<td align="center"><br /><br />
    		[[.cashier_signature.]]
     	   </td>
            <td colspan="3">&nbsp;</td>
    		<td width="50%" align="center" valign="top">
    			<br /><br />
                [[.guest_signature.]]
    		</td>
    	</tr>
    	<tr>
    		<td colspan="2" align="center"><br /><br /></td>
    	</tr>
    </table>
    <!--ELSE-->
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
        	<td height="100">&nbsp;</td>
    	</tr>
    </table>	
<!--/IF:cond-->
</div>
</td></tr></table>
</form>
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
    ViewGroupInvoiceForm.submit();
});
</script>