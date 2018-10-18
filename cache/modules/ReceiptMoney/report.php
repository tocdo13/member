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
                            <strong><?php echo Portal::language('template_code');?></strong>
                            <br />
                            <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                            <br />
                            <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                        </td>
                    </tr>
                    <tr>
        				<td colspan="2"> 
                            <div style="width:100%; text-align:center;">
                                <font class="report_title specific" ><?php echo Portal::language('receipt_money');?><br /></font>
                                <span style="font-family:'Times New Roman', Times, serif;">
                                    <?php echo Portal::language('from');?>  <?php echo $this->map['from_time'];?> - <?php echo $this->map['from_date'];?> <?php echo Portal::language('to');?>  <?php echo $this->map['to_time'];?> - <?php echo $this->map['to_date'];?>
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
                <strong><?php echo Portal::language('from_date');?></strong>
                <input  name="from_date" id="from_date" onchange="changevalue();" size="8" style="text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                <input  name="from_time" id="from_time" onchange="checktime('from_time');changevalue();" style="text-align: center; width: 40px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>">
                <strong><?php echo Portal::language('to_date');?></strong>
                <input  name="to_date" id="to_date" onchange="changevalue();" size="8" style="text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                <input  name="to_time" id="to_time" onchange="checktime('to_time');changevalue();" style="text-align: center; width: 40px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>">
                <!-- 7211 -->
                <strong><?php echo Portal::language('user_status');?></strong>  
                <select  style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                    <option value="1">Active</option>
                    <option value="0">All</option>
                </select>
                <!-- 7211 end--> 
                <strong><?php echo Portal::language('receipter');?></strong> <select  name="receipter" id="receipter" style="width:104px;"><?php
					if(isset($this->map['receipter_list']))
					{
						foreach($this->map['receipter_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('receipter',isset($this->map['receipter'])?$this->map['receipter']:''))
                    echo "<script>$('receipter').value = \"".addslashes(URL::get('receipter',isset($this->map['receipter'])?$this->map['receipter']:''))."\";</script>";
                    ?>
	</select>
                <input type="submit" name="do_search" value="<?php echo Portal::language('report');?>"/>
                <button id="export"><?php echo Portal::language('export_excel');?></button>
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
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<!---------/SEARCH----------->
<div style="float: left;"><h1 style="color: gray;"><?php echo strtoupper(Portal::language("receipt_bill")); ?></h1></div>
<!---------MICE_PAYMENT----------->
<?php if(strpos($this->map['dept'],'mice')!='' or strpos($this->map['dept'],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (10 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('mice');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('mice');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('customer');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <th rowspan="2"><?php echo Portal::language('total_bill');?></th>
        <th rowspan="2"><?php echo Portal::language('total_deposit');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key1=>&$item1){if($key1!='current'){$this->map['payment_type']['current'] = &$item1;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key2=>&$item2){if($key2!='current'){$this->map['payment_type']['current'] = &$item2;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key3=>&$item3){if($key3!='current'){$this->map['currency']['current'] = &$item3;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $bill_id = ''; $mice_total_bill =0; $mice_total_deposit = 0; ?>
    <?php if(isset($this->map['mice_payment']) and is_array($this->map['mice_payment'])){ foreach($this->map['mice_payment'] as $key4=>&$item4){if($key4!='current'){$this->map['mice_payment']['current'] = &$item4;?>
    <tr>
        <?php 
				if(($this->map['mice_payment']['current']['invoice_id']!=$bill_id))
				{?>
        <td align="center" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" ><?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('mice_reservation',array('cmd'=>'bill','invoice_id'=>$this->map['mice_payment']['current']['invoice_id'])); ?>">
            <?php echo $this->map['mice_payment']['current']['bill_id'];?>
            </a>
        </td>
        <td align="center" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" style="word-break: break-all;" ><?php echo $this->map['mice_payment']['current']['mice_reservation_id'];?></td>
        <td align="left" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['mice_payment']['current']['traveller_name'];?></td>
        <td align="left" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['mice_payment']['current']['customer_name'];?></td>
        
				<?php
				}
				?>
        <td align="center"><?php echo date('d/m/Y',$this->map['mice_payment']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['mice_payment']['current']['user_id'];?></td>
        <?php 
				if(($this->map['mice_payment']['current']['invoice_id']!=$bill_id))
				{?>
        <td align="right" class="change_num" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo System::display_number($this->map['mice_payment']['current']['total_bill']);$mice_total_bill += $this->map['mice_payment']['current']['total_bill']; ?></td>
        <td align="right" class="change_num" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo System::display_number($this->map['mice_payment']['current']['total_deposit']); $mice_total_deposit += $this->map['mice_payment']['current']['total_deposit']; ?></td>
        
				<?php
				}
				?>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key5=>&$item5){if($key5!='current'){$this->map['payment_type']['current'] = &$item5;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key6=>&$item6){if($key6!='current'){$this->map['currency']['current'] = &$item6;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['mice_payment']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['mice_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['mice_payment']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['mice_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['mice_payment']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['mice_payment']['current']['invoice_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['mice_payment']['current']['invoice_id']; ?>
        <td align="right" rowspan="<?php echo $this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['mice_payment_count'][$this->map['mice_payment']['current']['invoice_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['mice_payment']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="7"><?php echo Portal::language('total');?></th>
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


<?php if(strpos($this->map['dept'],'receipt')!='' or strpos($this->map['dept'],'all')!=''){?>
<!---------RECEIPT_PAYMENT----------->

<table width="100%" height="100%" cellSpacing=0 cellpadding=0 border=1 style="margin: 10px  auto 10px;">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (11 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('receipt');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('folio');?></th>
        <th rowspan="2"><?php echo Portal::language('recode');?></th>
        <th rowspan="2"><?php echo Portal::language('room_name');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <!--<th rowspan="2"><?php echo Portal::language('room_name');?></th>-->
        <th rowspan="2"><?php echo Portal::language('customer');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <th rowspan="2"><?php echo Portal::language('total_bill');?></th>
        <th rowspan="2"><?php echo Portal::language('total_deposit');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key7=>&$item7){if($key7!='current'){$this->map['payment_type']['current'] = &$item7;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key8=>&$item8){if($key8!='current'){$this->map['payment_type']['current'] = &$item8;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key9=>&$item9){if($key9!='current'){$this->map['currency']['current'] = &$item9;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $folio_id = '';$receipt_not_payment_total_bill =0;$receipt_not_payment_total_deposit =0; ?>
    <?php if(isset($this->map['receipt_payment']) and is_array($this->map['receipt_payment'])){ foreach($this->map['receipt_payment'] as $key10=>&$item10){if($key10!='current'){$this->map['receipt_payment']['current'] = &$item10;?>
    <tr>
        <?php 
				if(($this->map['receipt_payment']['current']['folio_id']!=$folio_id))
				{?>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" ><?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo ($this->map['receipt_payment']['current']['customer_id'] !=''?Url::build('view_traveller_folio',array('cmd'=>'group_invoice','folio_id'=>$this->map['receipt_payment']['current']['folio_id'],'id'=>$this->map['receipt_payment']['current']['recode'],'customer_id'=>$this->map['receipt_payment']['current']['customer_id'])):Url::build('view_traveller_folio',array('cmd'=>'invoice','traveller_id'=>$this->map['receipt_payment']['current']['reservation_traveller_id'],'folio_id'=>$this->map['receipt_payment']['current']['folio_id'])));?>">
            <?php if(isset($this->map['receipt_payment']['current']['folio_code'])){?>
                <?php echo "No.F".str_pad($this->map['receipt_payment']['current']['folio_code'],6,"0",STR_PAD_LEFT) ; ?>
            <?php }else{?>
                <?php echo "Ref".str_pad($this->map['receipt_payment']['current']['folio_id'],6,"0",STR_PAD_LEFT) ; ?>
            <?php }?>
            </a>
        </td>
        <td align="center" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo "?page=reservation&cmd=edit&layout=list&id=".$this->map['receipt_payment']['current']['recode']; ?>"><?php echo $this->map['receipt_payment']['current']['recode'];?></a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['receipt_payment']['current']['room_name'];?></td>
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['receipt_payment']['current']['traveller_name'];?></td>
        <!--<td align="center" style="word-break: break-word;" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" >
        <?php $room_name = ''; ?>
        <?php if(isset($this->map['receipt_payment']['current']['arr_room']) and is_array($this->map['receipt_payment']['current']['arr_room'])){ foreach($this->map['receipt_payment']['current']['arr_room'] as $key11=>&$item11){if($key11!='current'){$this->map['receipt_payment']['current']['arr_room']['current'] = &$item11;?>
        <?php //if($room_name==''){ $room_name = $this->map['receipt_payment']['current']['arr_room']['current']['name']; }else{ $room_name .= ", ".$this->map['receipt_payment']['current']['arr_room']['current']['name']; } ?> 
        <?php }}unset($this->map['receipt_payment']['current']['arr_room']['current']);} ?>
        <?php echo $room_name; ?>
        <!--</td>-->
        <td align="left" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['receipt_payment']['current']['customer_name'];?></td>
        
				<?php
				}
				?>
        <td align="center"><?php echo date('d/m/Y',$this->map['receipt_payment']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['receipt_payment']['current']['user_id'];?></td>
        <?php 
				if(($this->map['receipt_payment']['current']['folio_id']!=$folio_id))
				{?>
        <td align="right" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>"><?php echo System::display_number($this->map['receipt_payment']['current']['total_bill']);$receipt_not_payment_total_bill += $this->map['receipt_payment']['current']['total_bill']; ?></td>
        <td align="right" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>"><?php echo System::display_number($this->map['receipt_payment']['current']['total_deposit']);$receipt_not_payment_total_deposit += $this->map['receipt_payment']['current']['total_deposit'];  ?></td>
        
				<?php
				}
				?>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key12=>&$item12){if($key12!='current'){$this->map['payment_type']['current'] = &$item12;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key13=>&$item13){if($key13!='current'){$this->map['currency']['current'] = &$item13;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['receipt_payment']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['receipt_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_payment']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['receipt_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_payment']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['receipt_payment']['current']['folio_id']!=$folio_id))
				{?>
        <?php $folio_id = $this->map['receipt_payment']['current']['folio_id']; ?>
        <td align="right" rowspan="<?php echo $this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number(round($this->map['receipt_payment_count'][$this->map['receipt_payment']['current']['folio_id']]['total_payment'])); ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['receipt_payment']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="8" style="text-align: right;"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'bar')!='' or strpos($this->map['dept'],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (10 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('bar');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('table_name');?></th>
        <th rowspan="2"><?php echo Portal::language('customer');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <th rowspan="2"><?php echo Portal::language('total_bill');?></th>
        <th rowspan="2"><?php echo Portal::language('total_deposit');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key14=>&$item14){if($key14!='current'){$this->map['payment_type']['current'] = &$item14;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key15=>&$item15){if($key15!='current'){$this->map['payment_type']['current'] = &$item15;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key16=>&$item16){if($key16!='current'){$this->map['currency']['current'] = &$item16;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $bill_id = ''; $bar_total_bill =0; $bar_total_deposit =0; ?>
    <?php if(isset($this->map['bar_payment']) and is_array($this->map['bar_payment'])){ foreach($this->map['bar_payment'] as $key17=>&$item17){if($key17!='current'){$this->map['bar_payment']['current'] = &$item17;?>
    <tr>
        <?php 
				if(($this->map['bar_payment']['current']['bill_id']!=$bill_id))
				{?>
        <td align="center" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$this->map['bar_payment']['current']['bill_id'],'bar_id')); ?>">
            <?php echo $this->map['bar_payment']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['bar_payment']['current']['traveller_name'];?></td>
        <td align="center" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['bar_payment']['current']['table_name'];?></td>
        <td align="left" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['bar_payment']['current']['customer_name'];?></td>
        
				<?php
				}
				?>
        <td align="center"><?php echo date('d/m/Y',$this->map['bar_payment']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['bar_payment']['current']['user_id'];?></td>
        <?php 
				if(($this->map['bar_payment']['current']['bill_id']!=$bill_id))
				{?>
        <td align="right" class="change_num" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo System::display_number($this->map['bar_payment']['current']['total_bill']); $bar_total_bill +=$this->map['bar_payment']['current']['total_bill']; ?></td>
        <td align="right" class="change_num" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo System::display_number($this->map['bar_payment']['current']['total_deposit']); $bar_total_deposit +=$this->map['bar_payment']['current']['total_deposit']; ?></td>
        
				<?php
				}
				?>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key18=>&$item18){if($key18!='current'){$this->map['payment_type']['current'] = &$item18;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key19=>&$item19){if($key19!='current'){$this->map['currency']['current'] = &$item19;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['bar_payment']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['bar_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['bar_payment']['current']['amount']);?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['bar_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['bar_payment']['current']['amount_vnd']);?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['bar_payment']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['bar_payment']['current']['bill_id']; ?>
        <td align="right" rowspan="<?php echo $this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo '<span class="change_num">'.System::display_number($this->map['bar_payment_count'][$this->map['bar_payment']['current']['bill_id']]['total_payment']).'</span>'; ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['bar_payment']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="7"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'karaoke')!='' or strpos($this->map['dept'],'all')!=''){?>
<!--<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('karaoke');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('table_name');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key20=>&$item20){if($key20!='current'){$this->map['payment_type']['current'] = &$item20;?>
        <!--<th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <!--<th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key21=>&$item21){if($key21!='current'){$this->map['payment_type']['current'] = &$item21;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key22=>&$item22){if($key22!='current'){$this->map['currency']['current'] = &$item22;?>
                    <!--<th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    <!--</tr>
    <?php $bill_id = ''; ?>
    <?php if(isset($this->map['karaoke_payment']) and is_array($this->map['karaoke_payment'])){ foreach($this->map['karaoke_payment'] as $key23=>&$item23){if($key23!='current'){$this->map['karaoke_payment']['current'] = &$item23;?>
    <!--<tr>
        <?php 
				if(($this->map['karaoke_payment']['current']['bill_id']!=$bill_id))
				{?>
        <!--<td align="center" rowspan="<?php echo $this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('karaoke_touch',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$this->map['karaoke_payment']['current']['bill_id'],'karaoke_id')); ?>">
            <?php echo $this->map['karaoke_payment']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['karaoke_payment']['current']['traveller_name'];?></td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['karaoke_payment']['current']['table_name'];?></td>
        
				<?php
				}
				?>
        <!--<td align="center"><?php echo date('d/m/Y',$this->map['karaoke_payment']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['karaoke_payment']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key24=>&$item24){if($key24!='current'){$this->map['payment_type']['current'] = &$item24;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key25=>&$item25){if($key25!='current'){$this->map['currency']['current'] = &$item25;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['karaoke_payment']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['karaoke_payment']['current']['payment_type_id']))
				{?>
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['karaoke_payment']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['karaoke_payment']['current']['payment_type_id']))
				{?>
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['karaoke_payment']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <!--<td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['karaoke_payment']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['karaoke_payment']['current']['bill_id']; ?>
        <!--<td align="right" rowspan="<?php echo $this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['karaoke_payment_count'][$this->map['karaoke_payment']['current']['bill_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    <!--</tr>
    <?php }}unset($this->map['karaoke_payment']['current']);} ?>
    <!--<tr style="background-color: #eeeeee;">
        <th colspan="6"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'vend')!='' or strpos($this->map['dept'],'all')!=''){?>
<!--<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('vend');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('department_name');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key26=>&$item26){if($key26!='current'){$this->map['payment_type']['current'] = &$item26;?>
        <!--<th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <!--<th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key27=>&$item27){if($key27!='current'){$this->map['payment_type']['current'] = &$item27;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key28=>&$item28){if($key28!='current'){$this->map['currency']['current'] = &$item28;?>
                    <!--<th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    <!--</tr>
    <?php $bill_id = ''; ?>
    <?php if(isset($this->map['vend_payment']) and is_array($this->map['vend_payment'])){ foreach($this->map['vend_payment'] as $key29=>&$item29){if($key29!='current'){$this->map['vend_payment']['current'] = &$item29;?>
    <!--<tr>
        <?php 
				if(($this->map['vend_payment']['current']['bill_id']!=$bill_id))
				{?>
        <!--<td align="center" rowspan="<?php echo $this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_vend_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$this->map['vend_payment']['current']['bill_id'],'vend_id')); ?>">
            <?php echo $this->map['vend_payment']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['vend_payment']['current']['traveller_name'];?></td>
        <td align="center" rowspan="<?php echo $this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['vend_payment']['current']['department_name'];?></td>
        
				<?php
				}
				?>
        <!--<td align="center"><?php echo date('d/m/Y',$this->map['vend_payment']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['vend_payment']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key30=>&$item30){if($key30!='current'){$this->map['payment_type']['current'] = &$item30;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key31=>&$item31){if($key31!='current'){$this->map['currency']['current'] = &$item31;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['vend_payment']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['vend_payment']['current']['payment_type_id']))
				{?>
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['vend_payment']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <!--<td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['vend_payment']['current']['payment_type_id']))
				{?>
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['vend_payment']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <!--<td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['vend_payment']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['vend_payment']['current']['bill_id']; ?>
        <!--<td align="right" rowspan="<?php echo $this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['vend_payment_count'][$this->map['vend_payment']['current']['bill_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    <!--</tr>
    <?php }}unset($this->map['vend_payment']['current']);} ?>
    <!--<tr style="background-color: #eeeeee;">
        <th colspan="6"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'spa')!='' or strpos($this->map['dept'],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('spa');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('room_name');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key32=>&$item32){if($key32!='current'){$this->map['payment_type']['current'] = &$item32;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key33=>&$item33){if($key33!='current'){$this->map['payment_type']['current'] = &$item33;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key34=>&$item34){if($key34!='current'){$this->map['currency']['current'] = &$item34;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $bill_id = ''; ?>
    <?php if(isset($this->map['spa_payment']) and is_array($this->map['spa_payment'])){ foreach($this->map['spa_payment'] as $key35=>&$item35){if($key35!='current'){$this->map['spa_payment']['current'] = &$item35;?>
    <tr>
        <?php 
				if(($this->map['spa_payment']['current']['bill_id']!=$bill_id))
				{?>
        <td align="center" rowspan="<?php echo $this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['spa_payment']['current']['bill_id']));?>">
            <?php echo $this->map['spa_payment']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['spa_payment']['current']['traveller_name'];?></td>
        <td align="center" rowspan="<?php echo $this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['spa_payment']['current']['room_name'];?></td>
        
				<?php
				}
				?>
        <td align="center"><?php echo date('H:i',$this->map['spa_payment']['current']['time']).'  '.Portal::language('date').': '.date('d/m/Y',$this->map['spa_payment']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['spa_payment']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key36=>&$item36){if($key36!='current'){$this->map['payment_type']['current'] = &$item36;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key37=>&$item37){if($key37!='current'){$this->map['currency']['current'] = &$item37;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['spa_payment']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['spa_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['spa_payment']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['spa_payment']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['spa_payment']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['spa_payment']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['spa_payment']['current']['bill_id']; ?>
        <td align="right" rowspan="<?php echo $this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['spa_payment_count'][$this->map['spa_payment']['current']['bill_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['spa_payment']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="6"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'mice')!='' or strpos($this->map['dept'],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (6 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('mice');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('mice');?></th>
        <th rowspan="2"><?php echo Portal::language('customer_name');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key38=>&$item38){if($key38!='current'){$this->map['payment_type']['current'] = &$item38;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key39=>&$item39){if($key39!='current'){$this->map['payment_type']['current'] = &$item39;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key40=>&$item40){if($key40!='current'){$this->map['currency']['current'] = &$item40;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $bill_id = ''; ?>
    <?php if(isset($this->map['mice_deposit']) and is_array($this->map['mice_deposit'])){ foreach($this->map['mice_deposit'] as $key41=>&$item41){if($key41!='current'){$this->map['mice_deposit']['current'] = &$item41;?>
    <tr>
        <?php 
				if(($this->map['mice_deposit']['current']['bill_id']!=$bill_id))
				{?>
        <td align="center" rowspan="<?php echo $this->map['mice_deposit_count'][$this->map['mice_deposit']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['mice_deposit_count'][$this->map['mice_deposit']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['mice_deposit_count'][$this->map['mice_deposit']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('mice_reservation',array('cmd'=>'edit','id'=>$this->map['mice_deposit']['current']['bill_id'])); ?>">
            <?php echo $this->map['mice_deposit']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['mice_deposit_count'][$this->map['mice_deposit']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['mice_deposit']['current']['customer_name'];?></td>
        
				<?php
				}
				?>
        <td align="center" ><?php echo date('d/m/Y',$this->map['mice_deposit']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['mice_deposit']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key42=>&$item42){if($key42!='current'){$this->map['payment_type']['current'] = &$item42;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key43=>&$item43){if($key43!='current'){$this->map['currency']['current'] = &$item43;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['mice_deposit']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['mice_deposit']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['mice_deposit']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['mice_deposit']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['mice_deposit']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['mice_deposit']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['mice_deposit']['current']['bill_id']; ?>
        <td align="right" rowspan="<?php echo $this->map['mice_deposit_count'][$this->map['mice_deposit']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['mice_deposit_count'][$this->map['mice_deposit']['current']['bill_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['mice_deposit']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="5"><?php echo Portal::language('total');?></th>
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

<?php if(strpos($this->map['dept'],'receipt')!='' or strpos($this->map['dept'],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (8 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('receipt');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <!--<th rowspan="2"><?php echo Portal::language('traveller_name');?></th>-->
        <!--<th rowspan="2"><?php echo Portal::language('room_name');?></th>-->
        <th rowspan="2"><?php echo Portal::language('customer');?></th>
        <th rowspan="2"><?php echo Portal::language('recode');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key44=>&$item44){if($key44!='current'){$this->map['payment_type']['current'] = &$item44;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    
    
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key45=>&$item45){if($key45!='current'){$this->map['payment_type']['current'] = &$item45;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key46=>&$item46){if($key46!='current'){$this->map['currency']['current'] = &$item46;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $rid_rrid = ''; ?>
    <?php if(isset($this->map['receipt_deposit']) and is_array($this->map['receipt_deposit'])){ foreach($this->map['receipt_deposit'] as $key47=>&$item47){if($key47!='current'){$this->map['receipt_deposit']['current'] = &$item47;?>
    <tr>
       
        <?php 
				if((trim($this->map['receipt_deposit']['current']['rid_rrid'])!=$rid_rrid))
				{?>
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['num_payment'];?>" ><?php echo $this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['stt']; ?></td>
       
        <!--<td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['num_payment'];?>" >
        <?php if($this->map['receipt_deposit']['current']['reservation_room_id']){ ?>
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['receipt_deposit']['current']['recode'],'r_r_id'=>$this->map['receipt_deposit']['current']['reservation_room_id']));?>" target="_blank">
        <?php echo $this->map['receipt_deposit']['current']['room_name'];?>
        </a>
        <?php }else{ ?>
        &nbsp;
        <?php } ?>
        </td>-->
        <td align="left" rowspan="<?php echo $this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['num_payment'];?>" style="padding-left: 5px;">
            <?php echo $this->map['receipt_deposit']['current']['customer_name'];?>
        </td>
        <td align="center" rowspan="<?php echo $this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo "?page=reservation&cmd=edit&layout=list&id=".$this->map['receipt_deposit']['current']['recode']; ?>"><?php echo $this->map['receipt_deposit']['current']['recode'];?></a>
        </td>
        
				<?php
				}
				?>
        <td align="center"><?php echo date('d/m/Y',$this->map['receipt_deposit']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['receipt_deposit']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key48=>&$item48){if($key48!='current'){$this->map['payment_type']['current'] = &$item48;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key49=>&$item49){if($key49!='current'){$this->map['currency']['current'] = &$item49;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['receipt_deposit']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['receipt_deposit']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_deposit']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['receipt_deposit']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['receipt_deposit']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['receipt_deposit']['current']['rid_rrid']!=$rid_rrid))
				{?>
        <?php $rid_rrid = trim($this->map['receipt_deposit']['current']['rid_rrid']); 
       
        ?>
        <td align="right" rowspan="<?php echo $this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['receipt_deposit_count'][$this->map['receipt_deposit']['current']['rid_rrid']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['receipt_deposit']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="5"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'bar')!='' or strpos($this->map['dept'],'all')!=''){?>
<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('bar');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('table_name');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key50=>&$item50){if($key50!='current'){$this->map['payment_type']['current'] = &$item50;?>
        <th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key51=>&$item51){if($key51!='current'){$this->map['payment_type']['current'] = &$item51;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key52=>&$item52){if($key52!='current'){$this->map['currency']['current'] = &$item52;?>
                    <th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    </tr>
    <?php $bill_id = ''; ?>
    <?php if(isset($this->map['bar_deposit']) and is_array($this->map['bar_deposit'])){ foreach($this->map['bar_deposit'] as $key53=>&$item53){if($key53!='current'){$this->map['bar_deposit']['current'] = &$item53;?>
    <tr>
        <?php 
				if(($this->map['bar_deposit']['current']['bill_id']!=$bill_id))
				{?>
        <td align="center" rowspan="<?php echo $this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$this->map['bar_deposit']['current']['bill_id'],'bar_id')); ?>">
            <?php echo $this->map['bar_deposit']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['bar_deposit']['current']['traveller_name'];?></td>
        <td align="center" rowspan="<?php echo $this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['bar_deposit']['current']['table_name'];?></td>
        
				<?php
				}
				?>
        <td align="center" ><?php echo date('d/m/Y',$this->map['bar_deposit']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['bar_deposit']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key54=>&$item54){if($key54!='current'){$this->map['payment_type']['current'] = &$item54;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key55=>&$item55){if($key55!='current'){$this->map['currency']['current'] = &$item55;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['bar_deposit']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['bar_deposit']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['bar_deposit']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['bar_deposit']['current']['payment_type_id']))
				{?>
                    <td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['bar_deposit']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['bar_deposit']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['bar_deposit']['current']['bill_id']; ?>
        <td align="right" rowspan="<?php echo $this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['bar_deposit_count'][$this->map['bar_deposit']['current']['bill_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    </tr>
    <?php }}unset($this->map['bar_deposit']['current']);} ?>
    <tr style="background-color: #eeeeee;">
        <th colspan="6"><?php echo Portal::language('total');?></th>
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
<?php if(strpos($this->map['dept'],'karaoke')!='' or strpos($this->map['dept'],'all')!=''){?>
<!--<table width="100%" height="100%" cellSpacing=0 cellpadding=0 style="margin: 10px  auto 10px;" border="1">
    <tr style="background-color: #eeeeee;">
        <td width='100%' align="center" colspan="<?php echo (7 + count($this->map['payment_type']) + count($this->map['currency']) - 1) ?>"><h1 style="color: gray;"><?php echo Portal::language('karaoke');?></h1></td>
    </tr>
    <tr style="background-color: #eeeeee;">
        <th rowspan="2"><?php echo Portal::language('stt');?></th>
        <th rowspan="2"><?php echo Portal::language('bill_number');?></th>
        <th rowspan="2"><?php echo Portal::language('traveller_name');?></th>
        <th rowspan="2"><?php echo Portal::language('table_name');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?></th>
        <th rowspan="2"><?php echo Portal::language('receipter');?></th>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key56=>&$item56){if($key56!='current'){$this->map['payment_type']['current'] = &$item56;?>
        <!--<th <?php if($this->map['payment_type']['current']['def_code']!='CASH'){echo 'rowspan="2"';} else{echo 'colspan =\''.count($this->map['currency']).'\'';} ?>><?php echo $this->map['payment_type']['current']['name_'.Portal::language()]; ?></th>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <!--<th rowspan="2"><?php echo Portal::language('total');?></th>
    </tr>
    <tr style="background-color: #eeeeee;">
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key57=>&$item57){if($key57!='current'){$this->map['payment_type']['current'] = &$item57;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key58=>&$item58){if($key58!='current'){$this->map['currency']['current'] = &$item58;?>
                    <!--<th><?php echo $this->map['currency']['current']['symbol'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
    <!--</tr>
    <?php $bill_id = ''; ?>
    <?php if(isset($this->map['karaoke_deposit']) and is_array($this->map['karaoke_deposit'])){ foreach($this->map['karaoke_deposit'] as $key59=>&$item59){if($key59!='current'){$this->map['karaoke_deposit']['current'] = &$item59;?>
    <!--<tr>
        <?php 
				if(($this->map['karaoke_deposit']['current']['bill_id']!=$bill_id))
				{?>
        <!--<td align="center" rowspan="<?php echo $this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['stt']; ?></td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['num_payment'];?>" >
            <a target="_blank" href="<?php echo Url::build('touch_karaoke_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$this->map['karaoke_deposit']['current']['bill_id'],'karaoke_id')); ?>">
            <?php echo $this->map['karaoke_deposit']['current']['bill_id'];?>
            </a>
        </td>
        <td align="left" rowspan="<?php echo $this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['num_payment'];?>" style="padding-left: 5px;"><?php echo $this->map['karaoke_deposit']['current']['traveller_name'];?></td>
        <td align="center" rowspan="<?php echo $this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['num_payment'];?>" ><?php echo $this->map['karaoke_deposit']['current']['table_name'];?></td>
        
				<?php
				}
				?>
        <!--<td align="center" ><?php echo date('d/m/Y',$this->map['karaoke_deposit']['current']['time']); ?></td>
        <td align="left" style="padding-left: 5px;"><?php echo $this->map['karaoke_deposit']['current']['user_id'];?></td>
        <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key60=>&$item60){if($key60!='current'){$this->map['payment_type']['current'] = &$item60;?>
            <?php 
				if(($this->map['payment_type']['current']['def_code']=='CASH'))
				{?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key61=>&$item61){if($key61!='current'){$this->map['currency']['current'] = &$item61;?>
                    <?php 
				if(($this->map['currency']['current']['id']==$this->map['karaoke_deposit']['current']['currency_id'] and $this->map['payment_type']['current']['def_code']==$this->map['karaoke_deposit']['current']['payment_type_id']))
				{?>
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['karaoke_deposit']['current']['amount']); ?></td>
                     <?php }else{ ?>
                        <!--<td>&nbsp;</td>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['currency']['current']);} ?>
             <?php }else{ ?>
                <?php 
				if(($this->map['payment_type']['current']['def_code']==$this->map['karaoke_deposit']['current']['payment_type_id']))
				{?>
                    <!--<td align="right" style="padding-right: 5px;"><?php echo System::display_number($this->map['karaoke_deposit']['current']['amount_vnd']); ?></td>
                 <?php }else{ ?>
                    <!--<td>&nbsp;</td>
                
				<?php
				}
				?>
            
				<?php
				}
				?>
        <?php }}unset($this->map['payment_type']['current']);} ?>
        <?php 
				if(($this->map['karaoke_deposit']['current']['bill_id']!=$bill_id))
				{?>
        <?php $bill_id = $this->map['karaoke_deposit']['current']['bill_id']; ?>
        <!--<td align="right" rowspan="<?php echo $this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['num_payment'];?>" style="padding-right: 5px; font-weight: bold;">
            <?php echo System::display_number($this->map['karaoke_deposit_count'][$this->map['karaoke_deposit']['current']['bill_id']]['total_payment']); ?>
        </td>
        
				<?php
				}
				?>
    <!--</tr>
    <?php }}unset($this->map['karaoke_deposit']['current']);} ?>
    <!--<tr style="background-color: #eeeeee;">
        <th colspan="6"><?php echo Portal::language('total');?></th>
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
		<td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
	</tr>
	<tr valign="top">
		<td width="33%" align="center">&nbsp;</td>
		<td width="33%" align="center">&nbsp;</td>
		<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
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
                    <td align="left"><?php echo Portal::language('cash');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right"><?php echo System::display_number($cash_usd); ?></td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($cash_vnd); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('credit_card');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($credit_card); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('tranfer_bank');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($bank); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('debit');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($debit); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('refund');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($refund); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('foc');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($foc); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('total');?></td>
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
                    <td align="left"><?php echo Portal::language('cash');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right"><?php echo System::display_number($cash_usd); ?></td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($cash_vnd); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('credit_card');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($credit_card); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('tranfer_bank');?></td>
                    <?php if(isset($this->map['currency']['USD'])){ ?>
                    <td align="right">&nbsp;</td>
                    <?php }?>
                    <td align="right"><?php echo System::display_number($bank); ?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('total');?></td>
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
    var users = <?php echo String::array2js($this->map['users']);?>;
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