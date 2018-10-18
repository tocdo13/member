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
</style>
<form name="VatRevenueReportForm" method="POST">
    <table style="width: 100%;">
        <tr>
            <th colspan="4">
                <img src="<?php echo HOTEL_LOGO; ?>" style="width: 200px; height: auto;" /><br />
                <?php echo HOTEL_NAME; ?><br />
                <?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS; ?>
                <input type="button" id="export" value="<?php echo Portal::language('export_excel');?>" style="padding: 5px; float: right;" />
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-transform: uppercase; text-align: center;"><h1><?php echo Portal::language('general_statement');?></h1></th>
        </tr>
        <tr>
            <th style="width: 50%; text-align: center;">
                Tên đoàn / Group name: <?php echo $this->map['customer_name'];?><br />
                Người đặt / Booker: <?php echo $this->map['booker'];?><br />
                Số điện thoại / Tel: <?php echo $this->map['phone_booker'];?>
            </th>
            <th style="text-align: center;">
                Tên công ty: <?php echo $this->map['customer_full_name'];?><br />
                Mã đặt phòng: <?php echo $this->map['booking_code'];?><br />
                Số recode: <?php echo $this->map['id'];?>
            </th>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table id="tblExport" style=" margin: 10px auto 50px; border-collapse: collapse;" border="1" bordercolor="#CCCCCC" cellpadding="5" cellspacing="0">
    <tr>
        <th><?php echo Portal::language('stt');?></th>
        <th><?php echo Portal::language('traveller_name');?></th>
        <th><?php echo Portal::language('passport_report');?></th>
        <th><?php echo Portal::language('address');?></th>
        <th><?php echo Portal::language('competence');?></th>
        <th><?php echo Portal::language('room_name');?></th>
        <th><?php echo Portal::language('room_level');?></th>
        <th><?php echo Portal::language('checkin');?></th>
        <th><?php echo Portal::language('checkout');?></th>
        <th><?php echo Portal::language('night');?></th>
        <th><?php echo Portal::language('room_charge');?></th>
        <th><?php echo Portal::language('extra_service_room');?></th>
        <th><?php echo Portal::language('breakfast');?></th>
        <th><?php echo Portal::language('tranfer');?></th>
        <th><?php echo Portal::language('service_other');?></th>
        <th><?php echo Portal::language('minibar');?></th>
        <th><?php echo Portal::language('laundry');?></th>
        <th><?php echo Portal::language('equipment');?></th>
        <th><?php echo Portal::language('bar');?></th>
        <th><?php echo Portal::language('banquet');?></th>
        <th><?php echo Portal::language('service_amount');?></th>
        <th><?php echo Portal::language('amount_not_service');?></th>
        <th><?php echo Portal::language('tax_amount');?></th>
        <th><?php echo Portal::language('total_amount');?></th>
        <th><?php echo Portal::language('note');?></th>
    </tr>
    <?php $stt = 1; $i=0; ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <?php $child = ''; ?>
    <tr>
        <?php if(isset($this->map['items']['current']['child'])){ ?>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
            <?php $child=$this->map['items']['current']['child']['current']['id']; ?>
            <td><?php echo $stt++; ?></td>
            <td><?php echo $this->map['items']['current']['child']['current']['traveller_name'];?></td>
            <td><?php echo $this->map['items']['current']['child']['current']['passport'];?></td>
            <td><?php echo $this->map['items']['current']['child']['current']['address'];?></td>
            <td><?php echo $this->map['items']['current']['child']['current']['competence'];?></td>
            <?php break; ?>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
        <?php }else{ ?>
            <td><?php echo $stt++; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        <?php } ?>
        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['room_name'];?></td>
        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['room_level_name'];?></td>
        <?php if(isset($this->map['items']['current']['child'])){ ?>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current'] = &$item3;?>
            <?php $child=$this->map['items']['current']['child']['current']['id']; ?>
            <td><?php echo Date('H:i d/m/Y',$this->map['items']['current']['child']['current']['time_in']); ?></td>
            <td><?php echo Date('H:i d/m/Y',$this->map['items']['current']['child']['current']['time_out']); ?></td>
            <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
            <?php break; ?>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
        <?php }else{ ?>
            <td></td>
            <td></td>
            <td></td>
        <?php } ?>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['room_charge']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['extra_service_room']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['breakfast']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['tranfer']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['service_other']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['minibar']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['laundry']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['equipment']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['bar']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['banquet']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['service_amount']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['amount']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['tax_amount']); ?></td>
        <td style="text-align: right;" rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo System::display_number($this->map['items']['current']['total_amount']); ?></td>
        <?php if($i==0){ $i++; ?>
        <td rowspan="<?php echo $this->map['count_recode'];?>"><?php echo $this->map['note'];?></td>
        <?php } ?>
    </tr>
        <?php if($this->map['items']['current']['count_child']>1){ ?>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['child']['current'] = &$item4;?>
            <?php if($child!=$this->map['items']['current']['child']['current']['id']){ ?>
            <tr>
                <td><?php echo $stt++; ?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['traveller_name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['passport'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['address'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['competence'];?></td>
                <td><?php echo Date('H:i d/m/Y',$this->map['items']['current']['child']['current']['time_in']); ?></td>
                <td><?php echo Date('H:i d/m/Y',$this->map['items']['current']['child']['current']['time_out']); ?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
            </tr>
            <?php } ?>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
        <?php } ?>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <th style="text-align: center;" colspan="10"><?php echo Portal::language('total');?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['room_charge']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['extra_service_room']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['breakfast']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['tranfer']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['service_other']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['minibar']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['laundry']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['equipment']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['bar']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['banquet']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['service_amount']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['amount']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['tax_amount']); ?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['total_amount']); ?></th>
        <th></th>
    </tr>
</table>
<script>
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