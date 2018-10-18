<style>
    @media print {
      #search {display:none}
    }
a.report_table_header
{
	background-color:#FFFFFF;
	font-weight:bold;
	font-size:12px;
	line-height:20px;
	color:#000000;
	text-align:center;
}
.report_table_header
{
	background-color:#FFFFFF;
	font-weight:bold;
	font-size:12px;
	padding:5px;
	color:#000000;
}
.report_table_column
{
	padding:3px;
}
.report_table_column1
{
	padding:0px;
}
a.td_title
{
	color:#000000;
}
.report_title
{
	font-size:20px;
	font-weight:bold;
	text-transform:uppercase;
	text-align:center;
}
.report_sub_title
{
	font-weight:normal;
	padding:5px;
}
.report-bound
{
}
@page port {size: portrait;}
@page land {size: landscape;}
.landscape {page: land;}
#printer
{
	width:99%;
	height:100%;
	text-align:center;
	vertical-align:middle;
	background-color:white;
}

</style>
<?php
    $total_bill = 0;
    $total_deposit = 0; 
?>
<table id="tblExport" cellSpacing=0 cellpadding=0 border=0 style="width: 100%;">
<tr><td>
<!---------HEADER----------->
<div class="report-bound"> 
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <td >
        		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
        			<tr style="font-size:11px; font-weight:normal">
                        <td align="left" width="80%">
                            <strong><?php echo HOTEL_NAME;?></strong>
                            <br />
                            <strong><?php echo HOTEL_ADDRESS;?></strong>
                        </td>
                        <td align="right" style="padding-right:10px;" >
                            <strong>[[.template_code.]]</strong>
                            <br />
                            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                            <br />
                            [[.user_print.]]:<?php echo ' '.User::id();?>
                        </td>
                    </tr>
                    <tr>
        				<td colspan="2"> 
                            <div style="width:100%; text-align:center;">
                                <font class="report_title specific" >[[.receipt_money.]]<br /></font>
                                <span style="font-family:'Times New Roman', Times, serif;">
                                    [[.from.]]  [[|from_time|]] - [[|from_date|]] [[.to.]]  [[|to_time|]] - [[|to_date|]]
                                </span> 
                            </div>
                        </td>
                     </tr>	
        		</table>
            </td>
        </tr>
    </table>		
</div>
<!---------/HEADER----------->
<style type="text/css">
.specific {font-size: 19px !important;}

</style>

<!---------SEARCH----------->
<form name="SearchForm" method="post">
    <table width="100%" style="margin: 10px  auto 10px;" id="search" border="1">
        <tr>
            <td>
                <strong>[[.from_date.]]</strong>
                <input name="from_date" type="text" id="from_date" onchange="changevalue();" size="8" style="text-align: center;" />
                <input name="from_time" type="text" id="from_time" onchange="checktime('from_time');changevalue();" style="text-align: center; width: 40px;" />
                <strong>[[.to_date.]]</strong>
                <input name="to_date" type="text" id="to_date" onchange="changevalue();" size="8" style="text-align: center;" />
                <input name="to_time" type="text" id="to_time" onchange="checktime('to_time');changevalue();" style="text-align: center; width: 40px;" />
                <!-- 7211 -->
                <strong>[[.user_status.]]</strong>  
                <select style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                    <option value="1">Active</option>
                    <option value="0">All</option>
                </select>
                <!-- 7211 end--> 
                <strong>[[.receipter.]]</strong> <select name="receipter" id="receipter" style="width:104px;"></select>
                <input type="submit" name="do_search" value="[[.report.]]"/>
                <button id="export">[[.export_excel.]]</button>
                <script>
                    function checktime(id){
                        var mytime=$(id).value.split(":");
                        var h = mytime[0]>23?"00":mytime[0];
                        var m = mytime[1]>59?"00":mytime[1];
                        $(id).value = h+":"+m;
                    }
                    
                    function changevalue(){
                        var myfromdate=$('from_date').value.split("/");
                        var myfromtime=$('from_time').value.split(":");
                        var newfromdate=myfromdate[1]+"/"+myfromdate[0]+"/"+myfromdate[2];
                        var mytodate=$('to_date').value.split("/");
                        var mytotime=$('to_time').value.split(":");
                        var newtodate=mytodate[1]+"/"+mytodate[0]+"/"+mytodate[2];
                        
                        if(((new Date(newfromdate).getTime()) + myfromtime[0]*3600 + (myfromtime[1]?myfromtime[1]:0)*60) > ((new Date(newtodate).getTime()) + mytotime[0]*3600 + (mytotime[1]?mytotime[1]:0)*60))
                        {
                            $('to_date').value =$('from_date').value;
                            $('to_time').value =$('from_time').value;
                        }
                    }
                </script>
            </td>
        </tr>
    </table>
</form>
<!---------/SEARCH----------->
<div style="float: left;"><h1 style="color: gray;"><?php echo strtoupper(Portal::language("receipt_bill")); ?></h1></div>
<!---------MICE_PAYMENT----------->
<?php if(strpos([[=dept=]],'mice')!='' or strpos([[=dept=]],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (10 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.mice.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.mice.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.customer.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <th rowspan="2">[[.total_bill.]]</th>
        <th rowspan="2">[[.total_deposit.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $bill_id = ''; $mice_total_bill =0; $mice_total_deposit = 0; ?>
    <!--LIST:mice_payment-->
    <tr>
        <!--IF:bill([[=mice_payment.invoice_id=]]!=$bill_id)-->
        <td align="center" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" ><?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('mice_reservation',array('cmd'=>'bill','invoice_id'=>[[=mice_payment.invoice_id=]])); ?>">
            [[|mice_payment.bill_id|]]
            </a>
        </td>
        <td align="center" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" style="word-break: break-all;" >[[|mice_payment.mice_reservation_id|]]</td>
        <td align="left" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|mice_payment.traveller_name|]]</td>
        <td align="left" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|mice_payment.customer_name|]]</td>
        <!--/IF:bill-->
        <td align="center"><?php echo date('d/m/Y',[[=mice_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|mice_payment.user_id|]]</td>
        <!--IF:bill([[=mice_payment.invoice_id=]]!=$bill_id)-->
        <td align="right" class="change_num" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" style="padding-left: 5px;"><?php echo System::display_number([[=mice_payment.total_bill=]]);$mice_total_bill += [[=mice_payment.total_bill=]]; ?></td>
        <td align="right" class="change_num" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" style="padding-left: 5px;"><?php echo System::display_number([[=mice_payment.total_deposit=]]); $mice_total_deposit += [[=mice_payment.total_deposit=]]; ?></td>
        <!--/IF:bill-->
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=mice_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=mice_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=mice_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=mice_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=mice_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=mice_payment.invoice_id=]]!=$bill_id)-->
        <?php $bill_id = [[=mice_payment.invoice_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['mice_payment_count'][[[=mice_payment.invoice_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:bill-->
    </tr>
    <!--/LIST:mice_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="7">[[.total.]]</th>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($mice_total_bill); ?></th>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($mice_total_deposit); ?></th>
        <?php 
            foreach($this->map['mice_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['mice_payment_total']['total']); ?></th>
    </tr>
</table>
<?php } ?>
<!---------/MICE_PAYMENT----------->


<?php if(strpos([[=dept=]],'receipt')!='' or strpos([[=dept=]],'all')!=''){?>
<!---------RECEIPT_PAYMENT----------->

<table width="100%" height="100%" cellSpacing=0 cellpadding=0 border=1 style="margin: 10px  auto 10px;">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (11 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.receipt.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.folio.]]</th>
        <th rowspan="2">[[.recode.]]</th>
        <th rowspan="2">[[.room_name.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <!--<th rowspan="2">[[.room_name.]]</th>-->
        <th rowspan="2">[[.customer.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <th rowspan="2">[[.total_bill.]]</th>
        <th rowspan="2">[[.total_deposit.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $folio_id = '';$receipt_not_payment_total_bill =0;$receipt_not_payment_total_deposit =0; ?>
    <!--LIST:receipt_payment-->
    <tr>
        <!--IF:folio([[=receipt_payment.folio_id=]]!=$folio_id)-->
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" ><?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo ([[=receipt_payment.customer_id=]] !=''?Url::build('view_traveller_folio',array('cmd'=>'group_invoice','folio_id'=>[[=receipt_payment.folio_id=]],'id'=>[[=receipt_payment.recode=]],'customer_id'=>[[=receipt_payment.customer_id=]])):Url::build('view_traveller_folio',array('cmd'=>'invoice','traveller_id'=>[[=receipt_payment.reservation_traveller_id=]],'folio_id'=>[[=receipt_payment.folio_id=]])));?>">
            <?php if(isset([[=receipt_payment.folio_code=]])){?>
                <?php echo "No.F".str_pad([[=receipt_payment.folio_code=]],6,"0",STR_PAD_LEFT) ; ?>
            <?php }else{?>
                <?php echo "Ref".str_pad([[=receipt_payment.folio_id=]],6,"0",STR_PAD_LEFT) ; ?>
            <?php }?>
            </a>
        </td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo "?page=reservation&cmd=edit&layout=list&id=".[[=receipt_payment.recode=]]; ?>">[[|receipt_payment.recode|]]</a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|receipt_payment.room_name|]]</td>
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|receipt_payment.traveller_name|]]</td>
        <!--<td align="center" style="word-break: break-word;" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" >
        <?php $room_name = ''; ?>
        <!--LIST:receipt_payment.arr_room-->
        <?php //if($room_name==''){ $room_name = [[=receipt_payment.arr_room.name=]]; }else{ $room_name .= ", ".[[=receipt_payment.arr_room.name=]]; } ?> 
        <!--/LIST:receipt_payment.arr_room-->
        <?php echo $room_name; ?>
        <!--</td>-->
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|receipt_payment.customer_name|]]</td>
        <!--/IF:folio-->
        <td align="center"><?php echo date('d/m/Y',[[=receipt_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|receipt_payment.user_id|]]</td>
        <!--IF:folio([[=receipt_payment.folio_id=]]!=$folio_id)-->
        <td align="right" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>"><?php echo System::display_number([[=receipt_payment.total_bill=]]);$receipt_not_payment_total_bill += [[=receipt_payment.total_bill=]]; ?></td>
        <td align="right" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>"><?php echo System::display_number([[=receipt_payment.total_deposit=]]);$receipt_not_payment_total_deposit += [[=receipt_payment.total_deposit=]];  ?></td>
        <!--/IF:folio-->
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=receipt_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=receipt_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=receipt_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=receipt_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=receipt_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:folio([[=receipt_payment.folio_id=]]!=$folio_id)-->
        <?php $folio_id = [[=receipt_payment.folio_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number(round($this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['total_payment'])); ?>
        </td>
        <!--/IF:folio-->
    </tr>
    <!--/LIST:receipt_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="8" style="text-align: right;">[[.total.]]</th>
        <th style="text-align: right;"><?php echo System::display_number($receipt_not_payment_total_bill); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($receipt_not_payment_total_deposit); ?></th>
        <?php 
            foreach($this->map['receipt_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number(round($this->map['receipt_payment_total']['total'])); ?></th>
    </tr>
</table>
<?php }?>
<!---------/RECEIPT_PAYMENT----------->

<!---------BAR_PAYMENT----------->
<?php if(strpos([[=dept=]],'bar')!='' or strpos([[=dept=]],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (10 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.bar.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.table_name.]]</th>
        <th rowspan="2">[[.customer.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <th rowspan="2">[[.total_bill.]]</th>
        <th rowspan="2">[[.total_deposit.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $bill_id = ''; $bar_total_bill =0; $bar_total_deposit =0; ?>
    <!--LIST:bar_payment-->
    <tr>
        <!--IF:bill([[=bar_payment.bill_id=]]!=$bill_id)-->
        <td align="center" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=bar_payment.bill_id=]],'bar_id')); ?>">
            [[|bar_payment.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|bar_payment.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" >[[|bar_payment.table_name|]]</td>
        <td align="left" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" >[[|bar_payment.customer_name|]]</td>
        <!--/IF:bill-->
        <td align="center"><?php echo date('d/m/Y',[[=bar_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|bar_payment.user_id|]]</td>
        <!--IF:bill([[=bar_payment.bill_id=]]!=$bill_id)-->
        <td align="right" class="change_num" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" ><?php echo System::display_number([[=bar_payment.total_bill=]]); $bar_total_bill +=[[=bar_payment.total_bill=]]; ?></td>
        <td align="right" class="change_num" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" ><?php echo System::display_number([[=bar_payment.total_deposit=]]); $bar_total_deposit +=[[=bar_payment.total_deposit=]]; ?></td>
        <!--/IF:bill-->
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=bar_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=bar_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=bar_payment.amount=]]);?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=bar_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=bar_payment.amount_vnd=]]);?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=bar_payment.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=bar_payment.bill_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo '<span class="change_num">'.System::display_number($this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['total_payment']).'</span>'; ?>
        </td>
        <!--/IF:bill-->
    </tr>
    <!--/LIST:bar_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="7">[[.total.]]</th>
        <th><?php echo System::display_number($bar_total_bill); ?></th>
        <th><?php echo System::display_number($bar_total_deposit); ?></th>
        <?php 
            foreach($this->map['bar_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['bar_payment_total']['total']); ?></th>
    </tr>
</table>
<?php } ?>
<!---------/BAR_PAYMENT----------->

<!---------KARAOKE_PAYMENT----------->
<?php if(strpos([[=dept=]],'karaoke')!='' or strpos([[=dept=]],'all')!=''){?>
<!--<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.karaoke.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.table_name.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <!--<th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <!--<th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--<th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    <!--</tr>
    <?php $bill_id = ''; ?>
    <!--LIST:karaoke_payment-->
    <!--<tr>
        <!--IF:bill([[=karaoke_payment.bill_id=]]!=$bill_id)-->
        <!--<td align="center" rowspan="<?php echo $this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('karaoke_touch',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=karaoke_payment.bill_id=]],'karaoke_id')); ?>">
            [[|karaoke_payment.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|karaoke_payment.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['num_payment'];?>" >[[|karaoke_payment.table_name|]]</td>
        <!--/IF:bill-->
        <!--<td align="center"><?php echo date('d/m/Y',[[=karaoke_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|karaoke_payment.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=karaoke_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=karaoke_payment.payment_type_id=]])-->
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=karaoke_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=karaoke_payment.payment_type_id=]])-->
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=karaoke_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <!--<td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=karaoke_payment.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=karaoke_payment.bill_id=]]; ?>
        <!--<td align="right" rowspan="<?php echo $this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['karaoke_payment_count'][[[=karaoke_payment.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:bill-->
    <!--</tr>
    <!--/LIST:karaoke_payment-->
    <!--<tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['karaoke_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['karaoke_payment_total']['total']); ?></th>
    </tr>
</table>
<?php }?>
<!---------/KARAOKE_PAYMENT----------->

<!---------VEND_PAYMENT----------->
<?php if(strpos([[=dept=]],'vend')!='' or strpos([[=dept=]],'all')!=''){?>
<!--<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.vend.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.department_name.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <!--<th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <!--<th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--<th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    <!--</tr>
    <?php $bill_id = ''; ?>
    <!--LIST:vend_payment-->
    <!--<tr>
        <!--IF:bill([[=vend_payment.bill_id=]]!=$bill_id)-->
        <!--<td align="center" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_vend_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=vend_payment.bill_id=]],'vend_id')); ?>">
            [[|vend_payment.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|vend_payment.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" >[[|vend_payment.department_name|]]</td>
        <!--/IF:bill-->
        <!--<td align="center"><?php echo date('d/m/Y',[[=vend_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|vend_payment.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=vend_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=vend_payment.payment_type_id=]])-->
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=vend_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <!--<td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=vend_payment.payment_type_id=]])-->
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=vend_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <!--<td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=vend_payment.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=vend_payment.bill_id=]]; ?>
        <!--<td align="right" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:bill-->
    <!--</tr>
    <!--/LIST:vend_payment-->
    <!--<tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['vend_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['vend_payment_total']['total']); ?></th>
    </tr>
</table>
<?php }?>
<!---------/VEND_PAYMENT----------->

<!---------SPA_PAYMENT----------->
<?php if(strpos([[=dept=]],'spa')!='' or strpos([[=dept=]],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.spa.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.room_name.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $bill_id = ''; ?>
    <!--LIST:spa_payment-->
    <tr>
        <!--IF:bill([[=spa_payment.bill_id=]]!=$bill_id)-->
        <td align="center" rowspan="<?php echo $this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=spa_payment.bill_id=]]));?>">
            [[|spa_payment.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|spa_payment.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['num_payment'];?>" >[[|spa_payment.room_name|]]</td>
        <!--/IF:bill-->
        <td align="center"><?php echo date('H:i',[[=spa_payment.time=]]).'  '.Portal::language('date').': '.date('d/m/Y',[[=spa_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|spa_payment.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=spa_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=spa_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=spa_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=spa_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=spa_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=spa_payment.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=spa_payment.bill_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['spa_payment_count'][[[=spa_payment.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:bill-->
    </tr>
    <!--/LIST:spa_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['spa_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['spa_payment_total']['total']); ?></th>
    </tr>
</table>
<?php }?>
<!---------/SPA_PAYMENT----------->

<div style="float: left;"><h1 style="color: gray;"><?php echo strtoupper(Portal::language("receipt_deposit")); ?></h1></div>

<!---------MICE_DEPOSIT----------->
<?php if(strpos([[=dept=]],'mice')!='' or strpos([[=dept=]],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (6 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.mice.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.mice.]]</th>
        <th rowspan="2">[[.customer_name.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $bill_id = ''; ?>
    <!--LIST:mice_deposit-->
    <tr>
        <!--IF:bill_id([[=mice_deposit.bill_id=]]!=$bill_id)-->
        <td align="center" rowspan="<?php echo $this->map['mice_deposit_count'][[[=mice_deposit.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['mice_deposit_count'][[[=mice_deposit.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['mice_deposit_count'][[[=mice_deposit.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('mice_reservation',array('cmd'=>'edit','id'=>[[=mice_deposit.bill_id=]])); ?>">
            [[|mice_deposit.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['mice_deposit_count'][[[=mice_deposit.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|mice_deposit.customer_name|]]</td>
        <!--/IF:bill_id-->
        <td align="center" ><?php echo date('d/m/Y',[[=mice_deposit.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|mice_deposit.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=mice_deposit.currency_id=]] and [[=payment_type.def_code=]]==[[=mice_deposit.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=mice_deposit.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=mice_deposit.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=mice_deposit.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:folio([[=mice_deposit.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=mice_deposit.bill_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['mice_deposit_count'][[[=mice_deposit.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['mice_deposit_count'][[[=mice_deposit.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:folio-->
    </tr>
    <!--/LIST:mice_deposit-->
    <tr style="background-color: #eeeeee;">
        <th colspan="5">[[.total.]]</th>
        <?php 
            foreach($this->map['mice_deposit_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 10px;"><?php echo System::display_number($this->map['mice_deposit_total']['total']); ?></th>
    </tr>
</table>
<?php }?>
<!---------/MICE_DEPOSIT----------->

<!---------RECEIPT_DEPOSIT----------->

<?php if(strpos([[=dept=]],'receipt')!='' or strpos([[=dept=]],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (8 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.receipt.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <!--<th rowspan="2">[[.traveller_name.]]</th>-->
        <!--<th rowspan="2">[[.room_name.]]</th>-->
        <th rowspan="2">[[.customer.]]</th>
        <th rowspan="2">[[.recode.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    
    
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $rid_rrid = ''; ?>
    <!--LIST:receipt_deposit-->
    <tr>
       
        <!--IF:rid_rrid(trim([[=receipt_deposit.rid_rrid=]])!=$rid_rrid)-->
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" ><?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['stt']; ?></td>
       
        <!--<td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" >
        <?php if([[=receipt_deposit.reservation_room_id=]]){ ?>
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=receipt_deposit.recode=]],'r_r_id'=>[[=receipt_deposit.reservation_room_id=]]));?>" target="_blank">
        [[|receipt_deposit.room_name|]]
        </a>
        <?php }else{ ?>
        &nbsp;
        <?php } ?>
        </td>-->
        <td align="left" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" style="padding-left: 5px;">
            [[|receipt_deposit.customer_name|]]
        </td>
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo "?page=reservation&cmd=edit&layout=list&id=".[[=receipt_deposit.recode=]]; ?>">[[|receipt_deposit.recode|]]</a>
        </td>
        <!--/IF:rid_rrid-->
        <td align="center"><?php echo date('d/m/Y',[[=receipt_deposit.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|receipt_deposit.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=receipt_deposit.currency_id=]] and [[=payment_type.def_code=]]==[[=receipt_deposit.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=receipt_deposit.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=receipt_deposit.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=receipt_deposit.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:folio([[=receipt_deposit.rid_rrid=]]!=$rid_rrid)-->
        <?php $rid_rrid = trim([[=receipt_deposit.rid_rrid=]]); 
       
        ?>
        <td align="right" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['total_payment']); ?>
        </td>
        <!--/IF:folio-->
    </tr>
    <!--/LIST:receipt_deposit-->
    <tr style="background-color: #eeeeee;">
        <th colspan="5">[[.total.]]</th>
        <?php 
            foreach($this->map['receipt_deposit_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_deposit_total']['total']); ?></th>
    </tr>
</table>
<?php }?>



<!---------/RECEIPT_DEPOSIT----------->

<!---------BAR_DEPOSIT----------->
<?php if(strpos([[=dept=]],'bar')!='' or strpos([[=dept=]],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.bar.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.table_name.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    </tr>
    <?php $bill_id = ''; ?>
    <!--LIST:bar_deposit-->
    <tr>
        <!--IF:bill_id([[=bar_deposit.bill_id=]]!=$bill_id)-->
        <td align="center" rowspan="<?php echo $this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=bar_deposit.bill_id=]],'bar_id')); ?>">
            [[|bar_deposit.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|bar_deposit.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['num_payment'];?>" >[[|bar_deposit.table_name|]]</td>
        <!--/IF:bill_id-->
        <td align="center" ><?php echo date('d/m/Y',[[=bar_deposit.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|bar_deposit.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=bar_deposit.currency_id=]] and [[=payment_type.def_code=]]==[[=bar_deposit.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=bar_deposit.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=bar_deposit.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=bar_deposit.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:folio([[=bar_deposit.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=bar_deposit.bill_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['bar_deposit_count'][[[=bar_deposit.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:folio-->
    </tr>
    <!--/LIST:bar_deposit-->
    <tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['bar_deposit_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 10px;"><?php echo System::display_number($this->map['bar_deposit_total']['total']); ?></th>
    </tr>
</table>
<?php }?>
<!---------/BAR_DEPOSIT----------->

<!---------KARAOKE_DEPOSIT----------->
<?php if(strpos([[=dept=]],'karaoke')!='' or strpos([[=dept=]],'all')!=''){?>
<!--<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.karaoke.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.bill_number.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.table_name.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <!--<th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <!--/LIST:payment_type-->
        <!--<th rowspan="2">[[.total.]]</th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <!--LIST:payment_type-->
            <!--IF:typecash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--<th>[[|currency.symbol|]]</th>
                <!--/LIST:currency-->
            <!--/IF:typecash-->
        <!--/LIST:payment_type-->
    <!--</tr>
    <?php $bill_id = ''; ?>
    <!--LIST:karaoke_deposit-->
    <!--<tr>
        <!--IF:bill_id([[=karaoke_deposit.bill_id=]]!=$bill_id)-->
        <!--<td align="center" rowspan="<?php echo $this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_karaoke_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=karaoke_deposit.bill_id=]],'karaoke_id')); ?>">
            [[|karaoke_deposit.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|karaoke_deposit.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['num_payment'];?>" >[[|karaoke_deposit.table_name|]]</td>
        <!--/IF:bill_id-->
        <!--<td align="center" ><?php echo date('d/m/Y',[[=karaoke_deposit.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|karaoke_deposit.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=karaoke_deposit.currency_id=]] and [[=payment_type.def_code=]]==[[=karaoke_deposit.payment_type_id=]])-->
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=karaoke_deposit.amount=]]); ?></td>
                    <!--ELSE-->
                        <!--<td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=karaoke_deposit.payment_type_id=]])-->
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=karaoke_deposit.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <!--<td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:folio([[=karaoke_deposit.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=karaoke_deposit.bill_id=]]; ?>
        <!--<td align="right" rowspan="<?php echo $this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['karaoke_deposit_count'][[[=karaoke_deposit.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:folio-->
    <!--</tr>
    <!--/LIST:karaoke_deposit-->
    <!--<tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['karaoke_deposit_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 10px;"><?php echo System::display_number($this->map['karaoke_deposit_total']['total']); ?></th>
    </tr>
</table>
<?php }?>
<!---------/KARAOKE_DEPOSIT----------->

<!---------FOOTER----------->
<!--<br /><br /><br />
<table width="100%" style="font-family:'Times New Roman', Times, serif">
	<tr>
		<td></td>
		<td></td>
		<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
	</tr>
	<tr valign="top">
		<td width="33%" align="center">&nbsp;</td>
		<td width="33%" align="center">&nbsp;</td>
		<td width="33%" align="center">[[.creator.]]</td>
	</tr>
</table>
<br /><br /><br /><br /><br />-->
<!---------/FOOTER----------->
<table style="<?php if(Url::get('dept') != 'all'){echo 'display: none';} ?>">
    <tr>
        <td>
            <table width="350px" border="1">
                <tr>
                    <th colspan="<?php echo (count($this->map['currency'])+1); ?>">Tổng hợp tiền thu bill trong ngày</th>
                </tr>    
                <tr>
                    <th>Chỉ tiêu</th>
                    <?php foreach($this->map['currency'] as $key => $value){?>
                    <th><?php echo $value['symbol']; ?></th>
                    <?php }?>
                </tr>
                <?php 
                $cash_vnd = 0;
                $cash_usd = 0;
                $credit_card = 0;
                $bank = 0;
                $debit = 0;
                $refund = 0;
                $foc = 0;
                foreach($this->map['payment_total_bill'] as $key => $value)
                {
                    $cash_vnd += $value['CASH_VND'];
                    if(isset($this->map['currency']['USD']))
                    {
                        $cash_usd += $value['CASH_USD'];    
                    }
                    $credit_card += $value['CREDIT_CARD'];
                    $bank += $value['BANK'];
                    $debit += $value['DEBIT'];
                    $refund += $value['REFUND'];
                    $foc += $value['FOC'];
                }
                ?>
                <tr>
                    <td align="left">[[.cash.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right"><?php echo System::display_number($cash_usd); ?></td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($cash_vnd); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.credit_card.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($credit_card); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.tranfer_bank.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($bank); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.debit.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($debit); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.refund.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($refund); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.foc.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($foc); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.total.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right"><?php echo System::display_number($cash_usd); ?></td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($cash_vnd+$credit_card+$bank+$debit+$foc); ?></td>
                </tr>
            </table>
        </td>
        <td>&nbsp;</td>
        <td>
            <table width="350px" border="1">
                <tr>
                    <th colspan="<?php echo (count($this->map['currency'])+1); ?>">Tiền đặt cọc trong ngày</th>
                </tr>    
                <tr>
                    <th>Chỉ tiêu</th>
                    <?php foreach($this->map['currency'] as $key => $value){?>
                    <th><?php echo $value['symbol']; ?></th>
                    <?php }?>
                </tr>
                <?php 
                $cash_vnd = 0;
                $cash_usd = 0;
                $credit_card = 0;
                $bank = 0;
                foreach($this->map['payment_total_deposit'] as $key => $value)
                {
                    $cash_vnd += $value['CASH_VND'];
                    if(isset($this->map['currency']['USD']))
                    {
                        $cash_usd += $value['CASH_USD'];    
                    }
                    $credit_card += $value['CREDIT_CARD'];
                    $bank += $value['BANK'];
                }
                ?>
                <tr>
                    <td align="left">[[.cash.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right"><?php echo System::display_number($cash_usd); ?></td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($cash_vnd); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.credit_card.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($credit_card); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.tranfer_bank.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($bank); ?></td>
                </tr>
                <tr>
                    <td align="left">[[.total.]]</td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right"><?php echo System::display_number($cash_usd); ?></td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($cash_vnd+$credit_card+$bank); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</td></tr>
</table>

<script type="text/javascript">
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
                  jQuery('#receipter').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
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
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        jQuery('#from_time').mask('99:99');
        jQuery('#to_time').mask('99:99');                
        jQuery("#export").click(function () {
            jQuery('.change_num').each(function(){
                jQuery(this).html(to_numeric(jQuery(this).html()));
            })
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
    //7211 end
);
</script>