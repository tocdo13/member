<style>
    @media print {
      #search {display:none}
    }
</style>
<table id="tblExport" cellSpacing=0 cellpadding=0 border=0 style="width: 100%;">
<tr><td>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
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
<!---------RECEIPT_PAYMENT----------->
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 border=1 style="margin: 10px  auto 10px;">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (9 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.receipt.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <th rowspan="2">[[.folio.]]</th>
        <th rowspan="2">[[.traveller_name.]]</th>
        <th rowspan="2">[[.room_name.]]</th>
        <th rowspan="2">[[.customer.]]</th>
        <th rowspan="2">[[.recode.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan = "2"';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
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
    <?php $folio_id = ''; ?>
    <!--LIST:receipt_payment-->
    <tr>
        <!--IF:folio([[=receipt_payment.folio_id=]]!=$folio_id)-->
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" ><?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo ([[=receipt_payment.customer_id=]] !=''?Url::build('view_traveller_folio',array('folio_id'=>[[=receipt_payment.folio_id=]],'id'=>[[=receipt_payment.recode=]],'cmd'=>'group_invoice','customer_id'=>[[=receipt_payment.customer_id=]])):Url::build('view_traveller_folio',array('traveller_id'=>[[=receipt_payment.reservation_traveller_id=]],'folio_id'=>[[=receipt_payment.folio_id=]])));?>">
            [[|receipt_payment.folio_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|receipt_payment.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" >[[|receipt_payment.room_name|]]</td>
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|receipt_payment.customer_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo "?page=reservation&layout=list&cmd=edit&id=".[[=receipt_payment.recode=]]; ?>">[[|receipt_payment.recode|]]</a>
        </td>
        <!--/IF:folio-->
        <td align="center"><?php echo date('d/m/Y',[[=receipt_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|receipt_payment.user_id|]]</td>
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
            <?php echo System::display_number($this->map['receipt_payment_count'][[[=receipt_payment.folio_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:folio-->
    </tr>
    <!--/LIST:receipt_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="8">[[.total.]]</th>
        <?php 
            foreach($this->map['receipt_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_payment_total']['total']); ?></th>
    </tr>
</table>
<!---------/RECEIPT_PAYMENT----------->

<!---------BAR_PAYMENT----------->
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
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan = "2"';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
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
        <!--/IF:bill-->
        <td align="center"><?php echo date('d/m/Y',[[=bar_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|bar_payment.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=bar_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=bar_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=bar_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=bar_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=bar_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=bar_payment.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=bar_payment.bill_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['bar_payment_count'][[[=bar_payment.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:bill-->
    </tr>
    <!--/LIST:bar_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['bar_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['bar_payment_total']['total']); ?></th>
    </tr>
</table>
<!---------/BAR_PAYMENT----------->

<!---------VEND_PAYMENT----------->
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
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
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan = "2"';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
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
    <!--LIST:vend_payment-->
    <tr>
        <!--IF:bill([[=vend_payment.bill_id=]]!=$bill_id)-->
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" ><?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_vend_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=vend_payment.bill_id=]],'vend_id')); ?>">
            [[|vend_payment.bill_id|]]
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" style="padding-left: 5px;">[[|vend_payment.traveller_name|]]</td>
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" >[[|vend_payment.department_name|]]</td>
        <!--/IF:bill-->
        <td align="center"><?php echo date('d/m/Y',[[=vend_payment.time=]]); ?></td>
        <td align="left" style="padding-left: 5px;">[[|vend_payment.user_id|]]</td>
        <!--LIST:payment_type-->
            <!--IF:true_cash([[=payment_type.def_code=]]=='CASH')-->
                <!--LIST:currency-->
                    <!--IF:true_currency([[=currency.id=]]==[[=vend_payment.currency_id=]] and [[=payment_type.def_code=]]==[[=vend_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=vend_payment.amount=]]); ?></td>
                    <!--ELSE-->
                        <td>&nbsp;</td>
                    <!--/IF:true_currency-->
                <!--/LIST:currency-->
            <!--ELSE-->
                <!--IF:true_payment_type([[=payment_type.def_code=]]==[[=vend_payment.payment_type_id=]])-->
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number([[=vend_payment.amount_vnd=]]); ?></td>
                <!--ELSE-->
                    <td>&nbsp;</td>
                <!--/IF:true_payment_type-->
            <!--/IF:true_cash-->
        <!--/LIST:payment_type-->
        <!--IF:bill([[=vend_payment.bill_id=]]!=$bill_id)-->
        <?php $bill_id = [[=vend_payment.bill_id=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['vend_payment_count'][[[=vend_payment.bill_id=]]]['total_payment']); ?>
        </td>
        <!--/IF:bill-->
    </tr>
    <!--/LIST:vend_payment-->
    <tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['vend_payment_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['vend_payment_total']['total']); ?></th>
    </tr>
</table>
<!---------/VEND_PAYMENT----------->

<!---------SPA_PAYMENT----------->
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
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan = "2"';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
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
        <td align="center"><?php echo date('d/m/Y',[[=spa_payment.time=]]); ?></td>
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
<!---------/SPA_PAYMENT----------->

<div style="float: left;"><h1 style="color: gray;"><?php echo strtoupper(Portal::language("receipt_deposit")); ?></h1></div>
<!---------RECEIPT_DEPOSIT----------->
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (8 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;">[[.receipt.]]</h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2">[[.stt.]]</th>
        <!--<th rowspan="2">[[.traveller_name.]]</th>-->
        <th rowspan="2">[[.room_name.]]</th>
        <th rowspan="2">[[.customer.]]</th>
        <th rowspan="2">[[.recode.]]</th>
        <th rowspan="2">[[.payment_time.]]</th>
        <th rowspan="2">[[.receipter.]]</th>
        <!--LIST:payment_type-->
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan = "2"';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
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
        <!--IF:rid_rrid([[=receipt_deposit.recode=]]."_".[[=receipt_deposit.reservation_room_id=]]!=$rid_rrid)-->
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" ><?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['stt']; ?></td>
        <!--
        <td align="left" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" style="padding-left: 5px;">
            [[|receipt_deposit.traveller_name|]]
        </td>
        -->
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" >
        <?php if([[=receipt_deposit.reservation_room_id=]]){ ?>
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=receipt_deposit.recode=]],'r_r_id'=>[[=receipt_deposit.reservation_room_id=]]));?>" target="_blank">
        [[|receipt_deposit.room_name|]]
        </a>
        <?php }else{ ?>
        &nbsp;
        <?php } ?>
        </td>
        <td align="left" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" style="padding-left: 5px;">
            [[|receipt_deposit.customer_name|]]
        </td>
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" >
            <a target="_blank" href="<?php echo "?page=reservation&layout=list&cmd=edit&id=".[[=receipt_deposit.recode=]]; ?>">[[|receipt_deposit.recode|]]</a>
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
        <?php $rid_rrid = [[=receipt_deposit.rid_rrid=]]; ?>
        <td align="right" rowspan="<?php echo $this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['receipt_deposit_count'][[[=receipt_deposit.rid_rrid=]]]['total_payment']); ?>
        </td>
        <!--/IF:folio-->
    </tr>
    <!--/LIST:receipt_deposit-->
    <tr style="background-color: #eeeeee;">
        <th colspan="6">[[.total.]]</th>
        <?php 
            foreach($this->map['receipt_deposit_total']['type_payment'] as $key => $value){
        ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($value); ?></th>
        <?php } ?>
        <th align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_deposit_total']['total']); ?></th>
    </tr>
</table>
<!---------/RECEIPT_DEPOSIT----------->

<!---------BAR_DEPOSIT----------->
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
        <th <?php if([[=payment_type.def_code=]]!='CASH'){echo 'rowspan="2"';} else{echo 'colspan = "2"';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
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
<!---------/BAR_DEPOSIT----------->

<!---------FOOTER----------->
<br /><br /><br />
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
<br /><br /><br /><br /><br />
<!---------/FOOTER----------->
</td></tr>
</table>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        jQuery('#from_time').mask('99:99');
        jQuery('#to_time').mask('99:99');
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);
</script>