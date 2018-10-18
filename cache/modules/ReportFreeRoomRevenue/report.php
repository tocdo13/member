<style>
.simple-layout-middle{
    width: 100%;
}
#SearchForm{
    width: 850px;
}
input:hover{
    border: 1px dashed #333;
}
select:hover{
    border: 1px dashed #333;    
}
table{
    border-collapse: collapse;  
}
#content tr td tr th{
    line-height: 20px;
    text-align: center;
    font-size: 12px;
}
#content tr td tr td{
    line-height: 25px;
    font-size: 12px;
}
.customer_datalist{
    height:50px !important;
    max-height:80px !important;
    overflow: scroll;
}
@media print{
    #SearchForm{
        display: none;
    }
}
</style>
<table cellspacing="0" width="90%" style="margin: 0 auto;">
    <tr style="font-size:12px; font-weight:normal">
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
</table>
<table width="90%" style="margin: 0 auto;">
    <tr>
        <td>
            <h2 style="text-align: center;"><?php echo Portal::language('report_free_room_revenue');?></h2>
            <form id="SearchForm" method="post">
                <fieldset style="border: 1px solid #333;">
                    <legend><?php echo Portal::language('search');?></legend>
                    <table style="margin: 0 auto;">
                        <tr>
                            <td><?php echo Portal::language('recode');?></td>
                            <td><input  name="reservation_id" id="reservation_id" style="width: 50px; height: 17px;" / type ="text" value="<?php echo String::html_normalize(URL::get('reservation_id'));?>"></td>
                            <td><?php echo Portal::language('customer_new');?></td>
                            <td><input  name="customer" id="customer" style="width: 100px; height: 17px" oninput="check_select();" list="data_customer" / type ="text" value="<?php echo String::html_normalize(URL::get('customer'));?>"><datalist id="data_customer" class="customer_datalist"><?php echo $this->map['customer_option'];?></datalist><input  name="customer_id" id="customer_id" style="width: 100px; height: 17px"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id'));?>"></td>
                            <td><?php echo Portal::language('customer_group_new');?></td>
                            <td><select  name="customer_group" id="customer_group" style="width: 100px; height: 23px"><?php
					if(isset($this->map['customer_group_list']))
					{
						foreach($this->map['customer_group_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_group',isset($this->map['customer_group'])?$this->map['customer_group']:''))
                    echo "<script>$('customer_group').value = \"".addslashes(URL::get('customer_group',isset($this->map['customer_group'])?$this->map['customer_group']:''))."\";</script>";
                    ?>
	</select></td>
                            <td><?php echo Portal::language('from_date');?></td>
                            <td><input  name="from_date" id="from_date" style="width: 70px; height: 17px"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                            <td><?php echo Portal::language('to_date');?></td>
                            <td><input  name="to_date" id="to_date" style="width: 70px; height: 17px"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                            <td><input name="seach" type="submit" id="search" value="<?php echo Portal::language('view_report');?>" style="width: 87px; height: 23px;"/></td>
                            <td><input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 70px; height: 23px"/></td>
                        </tr>
                    </table>
                </fieldset>
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        </td>
    </tr>
</table>
<br />
<?php  if(empty($this->map['items'])){ ?>
    <table style="margin: 0 auto;">
        <tr>
            <td style="text-align: center;"><strong><?php echo Portal::language('no_record');?></strong></td>
        </tr>
    </table>
<?php }else{ ?>
<table id="content" width="100%">
    <tr>
        <td><h2 id="title_page" style="text-align: center; display: none;"><?php echo Portal::language('report_free_room_revenue');?></h2></td>
    </tr>
    <tr>
        <td>
            <table border="1">
            <tr>
                <th width="5px"><?php echo Portal::language('stt');?></th>
                <th width="30px"><?php echo Portal::language('recode');?></th>
                <th width="120px"><?php echo Portal::language('customer_new');?></th>
                <th width="20px"><?php echo Portal::language('room');?></th>
                <th width="60px"><?php echo Portal::language('arrival_date');?></th>
                <th width="60px"><?php echo Portal::language('departure_date');?></th>
                <th width="5px"><?php echo Portal::language('night');?></th>
                <th width="100px"><?php echo Portal::language('price');?>(VND)</th>
                <th width="100px"><?php echo Portal::language('price');?>(USD)</th>
                <th width="120px"><?php echo Portal::language('amount_room');?></th>
                <th width="120px"><?php echo Portal::language('Extra_room_charge');?></th>
                <th width="120px"><?php echo Portal::language('service_other');?></th>
                <th width="120px"><?php echo Portal::language('telephone');?></th>
                <th width="120px"><?php echo Portal::language('bar');?></th>
                <th width="120px"><?php echo Portal::language('minibar');?></th>
                <th width="120px"><?php echo Portal::language('laundry');?></th>
                <th width="120px"><?php echo Portal::language('equip');?></th>
                <th width="120px"><?php echo Portal::language('spa');?></th>
                <th width="120px"><?php echo Portal::language('total');?></th>
                <th width="220px"><?php echo Portal::language('note');?></th>
            </tr>
            <?php $i =1;?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td width="30px" align="center"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['r_id'];?>&r_r_id=<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['r_id'];?></a></td>
                <td align="left"><?php echo $this->map['items']['current']['ctm_name'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['room_name'];?></td>
                <td><?php echo $this->map['items']['current']['arrival_time'];?></td>
                <td><?php echo $this->map['items']['current']['departure_time'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['nights'];?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['usd_price']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total']); ?></td>
                <td align="right"><?php echo System::display_number(round($this->map['items']['current']['total_extra_room_rates'])); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_extra_service_rates']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_telephone_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_bar_pay_with_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_minibar_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_laundry_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_equip_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['items']['current']['total_spa_room']); ?></td>
                <td align="right"><?php echo System::display_number(round($this->map['items']['current']['total_amount'])); ?></td>
                <td width="220px"><?php echo $this->map['items']['current']['foc'];?></td>
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>
            <tr style="font-weight: bold; font-size: 12px;">
                <td>Tổng</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right"><?php echo System::display_number($this->map['total']); ?></td>
                <td align="right"><?php echo System::display_number(round($this->map['total_extra_room_rates'])); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_extra_service_rates']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_telephone_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_bar_pay_with_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_minibar_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_laundry_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_equip_room']); ?></td>
                <td align="right"><?php echo System::display_number($this->map['total_spa_room']); ?></td>
                <td align="right"><?php echo System::display_number(round($this->map['total_amount'])); ?></td>
                <td></td>
            </tr>
        </table>
        <br />
        <br />
        <table border="1" width="450px">
            <tr>
                <th align="center"><?php echo Portal::language('stt');?></th>
                <th align="center"><?php echo Portal::language('customer_group_new');?></th>
                <th align="center"><?php echo Portal::language('quantity_room');?></th>
                <th align="center"><?php echo Portal::language('total');?></th>
            </tr>
            <?php $j = 1; ?>
            <?php if(isset($this->map['table_small']) and is_array($this->map['table_small'])){ foreach($this->map['table_small'] as $key2=>&$item2){if($key2!='current'){$this->map['table_small']['current'] = &$item2;?>
            <tr>
                <td align="center"><?php echo $j++; ?></td>
                <td><?php echo $this->map['table_small']['current']['name'];?></td>
                <td align="center"><?php echo $this->map['table_small']['current']['quantity'];?></td>
                <td align="right"><?php echo System::display_number($this->map['table_small']['current']['total_amount']); ?></td>
            </tr>
            <?php }}unset($this->map['table_small']['current']);} ?>
        </table>
        </td>
    </tr>
</table>
<?php }?>
<script>
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();

function check_select()
{
    var val = document.getElementById("customer").value;
    var opts = document.getElementById('data_customer').childNodes;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            jQuery('#customer_id').val(opts[i].id);
            break;
        }
    }
}

jQuery("#export_excel").click(function () {
    jQuery('#title_page').css('display', 'block');
    jQuery("#content").battatech_excelexport({
        containerid: "content"
       , datatype: 'table'
    });
});
</script>