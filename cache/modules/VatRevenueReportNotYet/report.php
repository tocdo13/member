<style>
    .simple-layout-middle{
        width:100%;
    }
    .simple-layout-content{
        padding: 0px; border: none;
    }
    #print_date:hover{
        border: 1px dashed #333;
    }
    #view_print { display: none; }
    @media print
    {
      #No-Print { display: none; }
      #view_print { display: block; }
    }
     .scroll{
            display:block;
            border: 1px solid white;
            padding:5px;
            margin-top:5px;
            width:100%;
            height:100%;
            overflow:scroll;
         }
         .auto{
            display:block;
            border: 1px solid red;
            padding:5px;
            margin-top:5px;
            width:100%;
            height:50px;
            overflow:auto;
         }
</style>
<div  class="scroll">
<form name="VatRevenueReportForm" method="POST" >
    <table style="width: 100%;">
        <tr style="text-align: center;">
            <th style="width: 33%;">
                <img src="<?php echo HOTEL_LOGO; ?>" style="width: 200px; height: auto;" /><br />
                <?php echo HOTEL_NAME; ?>
            </th>
            <th></th>
            <th style="width: 33%;">
                <?php echo Portal::language('user_id');?>: <?php echo User::id(); ?><br />
                <?php echo Portal::language('time');?>: <?php echo date('H:i d/m/Y'); ?>
            </th>
        </tr>
        <tr style="text-align: center;">
            <th colspan="3">
                <h1 style="text-transform: uppercase;">
                    <?php echo Portal::language('vat_revenue_report_not_yet');?>
                </h1>
            </th>
        </tr>
        <tr id="No-Print" style="text-align: center;">
            <th colspan="3">
                <?php echo Portal::language('start_date');?>: <input  name="start_date" id="start_date" style="padding: 5px; width: 80px; text-align: center;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>">
                <?php echo Portal::language('end_date');?>: <input  name="end_date" id="end_date" style="padding: 5px; width: 80px; text-align: center;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>">
                <?php echo Portal::language('type');?>: <select  name="type" id="type" style="padding: 5px;"><?php
					if(isset($this->map['type_list']))
					{
						foreach($this->map['type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('type',isset($this->map['type'])?$this->map['type']:''))
                    echo "<script>$('type').value = \"".addslashes(URL::get('type',isset($this->map['type'])?$this->map['type']:''))."\";</script>";
                    ?>
	</select> 
                
                <input type="submit" value="<?php echo Portal::language('search');?>" style="padding: 5px;" />
                <input type="button" id="export" value="<?php echo Portal::language('export_excel');?>" style="padding: 5px;" />
            </th>
        </tr>
        <tr id="view_print">
            <th colspan="3" style="text-align: center;">
                <?php echo Portal::language('start_date');?>: <?php echo $this->map['start_date'];?> - <?php echo Portal::language('end_date');?>: <?php echo $this->map['end_date'];?>
            </th>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table id="tblExport" style="width: 100%; margin: 10px auto 50px;" border="1" bordercolor="#CCCCCC" cellpadding="5" cellspacing="0"  >
    <tr style="text-align: center;">
        <th rowspan="2"><?php echo Portal::language('customer_code_report');?></th>
        <th rowspan="2"><?php echo Portal::language('booking_number');?></th>
        <th rowspan="2"><?php echo Portal::language('folio_number');?></th>
        <th rowspan="2"><?php echo Portal::language('vat_code');?></th>
        <th rowspan="2"><?php echo Portal::language('date');?>, <?php echo Portal::language('month');?>, <?php echo Portal::language('year');?> <?php echo Portal::language('create_bill_vat');?></th>
        <th rowspan="2"><?php echo Portal::language('payment_time');?> Folio</th>
        <th rowspan="2"><?php echo Portal::language('buyer');?></th>
        <th rowspan="2"><?php echo Portal::language('tax_code');?></th>
        <th colspan="11"><?php echo Portal::language('revenue');?></th>
        <th rowspan="2"><?php echo Portal::language('total_revenue');?></th>
        <th rowspan="2"><?php echo Portal::language('tax_rate');?></th>
        <th rowspan="2"><?php echo Portal::language('total_amount');?></th>
        <th rowspan="2"><?php echo Portal::language('note');?></th>
    </tr>
    <tr style="text-align: center;">
        <th><?php echo Portal::language('room_charge');?></th>
        <th><?php echo Portal::language('extra_service_charge_room');?></th>
        <th><?php echo Portal::language('incurred_breakfast');?></th>
        <th><?php echo Portal::language('restaurant');?></th>
        <th><?php echo Portal::language('banquet');?></th>
        <th><?php echo Portal::language('minibar');?></th>
        <th><?php echo Portal::language('laundry');?></th>
        <th><?php echo Portal::language('equipment');?></th>
        <th><?php echo Portal::language('Trans');?></th>
        <th><?php echo Portal::language('others');?></th>
        <th><?php echo Portal::language('service_rate');?></th>
    </tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr style="text-align: center;">
        <td><?php echo $this->map['items']['current']['customer_code'];?></td>
        <td><?php $count_booking = 1; ?><?php if(isset($this->map['items']['current']['booking_number']) and is_array($this->map['items']['current']['booking_number'])){ foreach($this->map['items']['current']['booking_number'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['booking_number']['current'] = &$item2;?> <?php if($count_booking!=1){echo',';} $count_booking++;  ?> <?php echo $this->map['items']['current']['booking_number']['current']['recode'];?> <?php }}unset($this->map['items']['current']['booking_number']['current']);} ?></td>
        <td><?php echo substr($this->map['items']['current']['folio_number'],0,-1); ?></td>
        <td><?php echo $this->map['items']['current']['vat_code'];?></td>
        <td><?php echo $this->map['items']['current']['create_time'];?></td>
        <td><?php echo $this->map['items']['current']['payment_time'];?></td>
        <td><?php echo $this->map['items']['current']['buyer'];?></td>
        <td><?php echo $this->map['items']['current']['tax_code'];?></td>
        
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['room_charge'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['extra_service_charge_room'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['breakfast'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['restaurant'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['banquet'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['minibar'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['laundry'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['equipment'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['trans'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['others'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['service_rate'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['total_revenue'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['tax_rate'])); ?></td>
        <td class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['total_amount'])); ?></td>
        
        <td><?php echo $this->map['items']['current']['note'];?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr style="text-align: center; height: 40px;">
        <th colspan="8"><?php echo Portal::language('total');?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['room_charge'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['extra_service_charge_room'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['breakfast'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['restaurant'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['banquet'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['minibar'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['laundry'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['equipment'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['trans'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['others'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['service_rate'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['total_revenue'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['tax_rate'])); ?></th>
        <th class="col_num" style="text-align: right;"><?php echo System::display_number(round($this->map['total_amount'])); ?></th>
        <th></th>
    </tr>
</table>
</div>

<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery('.col_num').each(function(){
                    jQuery(this).html(to_numeric(jQuery(this).html()));
                });
                jQuery("#tblExport").battatech_excelexport({
                    containerid: "tblExport"
                   , datatype: 'table'
                });
                location.reload();
            });
        }
    );
</script>