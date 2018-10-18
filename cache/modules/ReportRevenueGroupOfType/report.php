<?php //echo date('H:i d/m/Y',1424060200); ?>
<style>
@media print
{
    #search{
        display: none;
    }
}
a{
    font-weight: bold;
    color: #00b2f9;
    transition: all 0.5s ease-out;
}
a:active{
    color: #00b2f9;
}
a:hover{
    text-decoration: none;
    color: red;
}
a:visited{
    color: #00b2f9;
}
.button_sm{
    background: #ffffff;
    border: none;
    box-shadow: 0px 0px 3px #555555; 
    padding: 5px 3px;
    color: #00b2f9;
    font-weight: bold;
    font-size: 12px;
    transition: all 0.5s ease-out;
    width: 100px;
    margin-left: 30px;
    cursor: pointer;
}
.button_sm:hover{
    background: #00b2f9;
    color: #ffffff;
}
</style>
<table id="report">
    <tr>
        <td>
        
<table class="report" id="header" style="width: 98%; margin: 0px auto;">
    <tr>
        <td style="font-weight: bold;"><?php echo HOTEL_NAME; ?><br /><?php echo HOTEL_ADDRESS; ?></td>
        <td style="font-weight: bold; text-align: right;"><?php echo Portal::language('template_code');?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; text-transform: uppercase;"><h3 style="font-size: 20px;"><?php echo Portal::language('report_revenue_group_of_type');?></h3><?php echo Portal::language('from_date');?>: <?php echo $this->map['date_from'];?> <?php echo Portal::language('to');?>: <?php echo $this->map['date_to'];?></td>
    </tr>
</table>
<fieldset id="search" style="width: 98%; margin: 0px auto;">
    <legend><?php echo Portal::language('option');?></legend>
    <form name="ReportRevenueGroupOfTypeForm" method="post">
        <table style="margin: 0px auto; width: 100%;" cellpadding="5" cellspacing="0">
            <tr>
                <td><?php echo Portal::language('hotel');?>:</td>
                <td><select  name="portal_id" id="portal_id" style="width: 80px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                <td><?php echo Portal::language('date_from');?>:</td>
                <td><input  name="date_from" id="date_from" style="width: 80px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td><!-- ngay bat dau -->
                <td><?php echo Portal::language('time_in');?>:</td>
                <td><input  name="time_in" id="time_in" style="width: 40px;" / type ="text" value="<?php echo String::html_normalize(URL::get('time_in'));?>"></td><!-- gio bat dau -->
                <td><?php echo Portal::language('num_of_vote');?>:</td>
                <td><input  name="number_of_vote" id="number_of_vote" style="width: 170px;" / type ="text" value="<?php echo String::html_normalize(URL::get('number_of_vote'));?>"></td><!-- so phiue -->
                <td><?php echo Portal::language('re_code');?>:</td>
                <td><input  name="re_code" id="re_code" style="width: 40px;"  class="input_number"/ type ="text" value="<?php echo String::html_normalize(URL::get('re_code'));?>"></td><!-- ma mac dinh -->
                <td><?php echo Portal::language('customer_name');?>:</td>
                <td><input  name="customer_name" id="customer_name" onkeypress="Autocomplete();" oninput="delete_customer();"  autocomplete="off" style="width:215px;margin-bottom: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                    <input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                </td>
                <td><input name="view_report" type="submit" value="<?php echo Portal::language('view_report');?>" class="button_sm" style="" onclick="return check_date();" /></td>
            </tr>
            <tr>
                <td><?php echo Portal::language('category');?>:</td>
                <td><select  name="list" id="list" style="width: 80px;"><?php
					if(isset($this->map['list_list']))
					{
						foreach($this->map['list_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('list',isset($this->map['list'])?$this->map['list']:''))
                    echo "<script>$('list').value = \"".addslashes(URL::get('list',isset($this->map['list'])?$this->map['list']:''))."\";</script>";
                    ?>
	</select></td><!-- danh muc -->
                <td><?php echo Portal::language('date_to');?>:</td>
                <td><input  name="date_to" id="date_to" style="width: 80px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td><!-- ngay ket thuc -->
                <td><?php echo Portal::language('time_out');?>:</td>
                <td><input  name="time_out" id="time_out" style="width: 40px;" / type ="text" value="<?php echo String::html_normalize(URL::get('time_out'));?>"></td><!-- gio bat dau -->
                <td><?php echo Portal::language('create_user');?>:</td>
                <td><select  name="create_user" id="create_user" style="width: 170px;"><?php
					if(isset($this->map['create_user_list']))
					{
						foreach($this->map['create_user_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('create_user',isset($this->map['create_user'])?$this->map['create_user']:''))
                    echo "<script>$('create_user').value = \"".addslashes(URL::get('create_user',isset($this->map['create_user'])?$this->map['create_user']:''))."\";</script>";
                    ?>
	</select></td><!-- nguoi tao -->
                <td><?php echo Portal::language('room_number');?>:</td>
                <td><input  name="room_number" id="room_number" style="width: 40px;" / type ="text" value="<?php echo String::html_normalize(URL::get('room_number'));?>"></td><!-- so phong -->
                <td><?php echo Portal::language('status');?>:</td>
                <td><select  name="status" id="status" style="width: 80px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
                <td><button id="export" class="button_sm" style=""><?php echo Portal::language('export_excel');?></button></td>
            </tr>
        </table>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</fieldset>
<table class="report"  border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" style="width: 99%; margin: 10px auto; border-collapse: collapse;">
        <tr style="text-align: center; background: #eeeeee; height: 30px; text-transform: uppercase;">
            <th style="width: 30px;"><?php echo Portal::language('stt');?></th>
            <th style="width: 80px;"><?php echo Portal::language('date');?></th>
            <th style="width: 50px;"><?php echo Portal::language('re_code');?></th>
            <th style="width: 50px;"><?php echo Portal::language('customer');?></th>
            <th><?php echo Portal::language('num_of_vote');?></th>
            <th><?php echo Portal::language('create_user');?></th>
            <th><?php echo Portal::language('room');?></th>
            <th><?php echo Portal::language('price');?></th>
            <th><?php echo Portal::language('note');?></th>
        </tr>
        <?php $total_amount =0;  ?>
        <?php if(sizeof($this->map['reservation'])>0){ 
            $total_reservation = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU PHÒNG</td>
        </tr>
        <?php if(isset($this->map['reservation']) and is_array($this->map['reservation'])){ foreach($this->map['reservation'] as $key1=>&$item1){if($key1!='current'){$this->map['reservation']['current'] = &$item1;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['reservation']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['reservation']['current']['link'];?>" target="_blank"><?php echo $this->map['reservation']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['reservation']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['reservation']['current']['link'];?>" target="_blank"><?php echo $this->map['reservation']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['reservation']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['reservation']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['reservation']['current']['price'])); $total_reservation += $this->map['reservation']['current']['price']; ?></td>
            <td><?php echo $this->map['reservation']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['reservation']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation)); $total_amount+= $total_reservation; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['list_extra_service'])>0){ ?>
            <?php if(isset($this->map['list_extra_service']) and is_array($this->map['list_extra_service'])){ foreach($this->map['list_extra_service'] as $key2=>&$item2){if($key2!='current'){$this->map['list_extra_service']['current'] = &$item2;?>
            <tr>
                <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU <?php echo $this->map['list_extra_service']['current']['id'];?></td>
            </tr>
            <?php $stt = 1; ?>
                <?php if(isset($this->map['extra_service']) and is_array($this->map['extra_service'])){ foreach($this->map['extra_service'] as $key3=>&$item3){if($key3!='current'){$this->map['extra_service']['current'] = &$item3;?>
                    <?php $arr = explode('_',$this->map['extra_service']['current']['id']); if($arr[0]==$this->map['list_extra_service']['current']['id']){ ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $stt++; ?></td>
                            <td style="text-align: center;"><?php echo $this->map['extra_service']['current']['in_date'];?></td>
                            <td style="text-align: center;"><a href="<?php echo $this->map['extra_service']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['extra_service']['current']['reservation_id'];?></a></td>
                            <td><?php echo $this->map['extra_service']['current']['customer_name'];?></td>
                            <td style="text-align: center;"><a href="<?php echo $this->map['extra_service']['current']['link'];?>" target="_blank"><?php echo $this->map['extra_service']['current']['number_of_vote'];?></a></td>
                            <td><?php echo $this->map['extra_service']['current']['user_name'];?></td>
                            <td style="text-align: center;"><?php echo $this->map['extra_service']['current']['room'];?></td>
                            <td style="text-align: right;"><?php echo System::display_number(round($this->map['extra_service']['current']['price'])) ?></td>
                            <td><?php echo $this->map['extra_service']['current']['note'];?></td>
                        </tr>
                    <?php } ?>
                <?php }}unset($this->map['extra_service']['current']);} ?>
            <tr style=" font-weight: bold;">
                <td>...</td>
                <td colspan="6"><?php echo Portal::language('total');?></td>
                <td style="text-align: right;"> <?php echo System::display_number(round($this->map['list_extra_service']['current']['total'])); $total_amount+=$this->map['list_extra_service']['current']['total']; ?></td>
                <td></td>
            </tr>
            <?php }}unset($this->map['list_extra_service']['current']);} ?>
        <?php } ?>
        <?php if(sizeof($this->map['minibar'])>0){ 
            $total_minibar = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU MINIBAR</td>
        </tr>
        <?php if(isset($this->map['minibar']) and is_array($this->map['minibar'])){ foreach($this->map['minibar'] as $key4=>&$item4){if($key4!='current'){$this->map['minibar']['current'] = &$item4;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['minibar']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['minibar']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['minibar']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['minibar']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['minibar']['current']['link'];?>" target="_blank">MN_<?php echo $this->map['minibar']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['minibar']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['minibar']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['minibar']['current']['price'])); $total_minibar += $this->map['minibar']['current']['price']; ?></td>
            <td><?php echo $this->map['minibar']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['minibar']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar)); $total_amount+=$total_minibar; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['laundry'])>0){ 
            $total_laundry = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU LAUNDRY</td>
        </tr>
        <?php if(isset($this->map['laundry']) and is_array($this->map['laundry'])){ foreach($this->map['laundry'] as $key5=>&$item5){if($key5!='current'){$this->map['laundry']['current'] = &$item5;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['laundry']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['laundry']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['laundry']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['laundry']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['laundry']['current']['link'];?>" target="_blank">LD_<?php echo $this->map['laundry']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['laundry']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['laundry']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['laundry']['current']['price'])); $total_laundry += $this->map['laundry']['current']['price']; ?></td>
            <td><?php echo $this->map['laundry']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['laundry']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry)); $total_amount+=$total_laundry; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['equip'])>0){ 
            $total_equip = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU HÓA ĐƠN ĐỀN BÙ</td>
        </tr>
        <?php if(isset($this->map['equip']) and is_array($this->map['equip'])){ foreach($this->map['equip'] as $key6=>&$item6){if($key6!='current'){$this->map['equip']['current'] = &$item6;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['equip']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['equip']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['equip']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['equip']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['equip']['current']['link'];?>" target="_blank"><?php echo $this->map['equip']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['equip']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['equip']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['equip']['current']['price'])); $total_equip += $this->map['equip']['current']['price']; ?></td>
            <td><?php echo $this->map['equip']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['equip']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip)); $total_amount+= $total_equip; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['bar'])>0){ 
            $total_bar = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU NHÀ HÀNG</td>
        </tr>
        <?php if(isset($this->map['bar']) and is_array($this->map['bar'])){ foreach($this->map['bar'] as $key7=>&$item7){if($key7!='current'){$this->map['bar']['current'] = &$item7;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['bar']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['bar']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['bar']['current']['reservation_id'];?></a></td>
            <td style="text-align: center;"><?php echo ($this->map['bar']['current']['customer_name_r']!='')?$this->map['bar']['current']['customer_name_r']:$this->map['bar']['current']['customer_name_b']?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['bar']['current']['link'];?>" target="_blank"><?php echo $this->map['bar']['current']['code'];?></a></td>
            <td><?php echo $this->map['bar']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['bar']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['bar']['current']['price'])); $total_bar += $this->map['bar']['current']['price']; ?></td>
            <td><?php echo $this->map['bar']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['bar']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar)); $total_amount+= $total_bar; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['spa'])>0){ 
            $total_spa = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU SPA</td>
        </tr>
        <?php if(isset($this->map['spa']) and is_array($this->map['spa'])){ foreach($this->map['spa'] as $key8=>&$item8){if($key8!='current'){$this->map['spa']['current'] = &$item8;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['spa']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['spa']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['spa']['current']['reservation_id'];?></a></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['spa']['current']['link'];?>" target="_blank"><?php echo $this->map['spa']['current']['massage_reservation_room_id'];?></a></td>
            <td><?php echo $this->map['spa']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['spa']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['spa']['current']['price'])); $total_spa += $this->map['spa']['current']['price']; ?></td>
            <td><?php echo $this->map['spa']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['spa']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa)); $total_amount+= $total_spa; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['party'])>0){ 
            $total_party = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU Đặt Tiệc</td>
        </tr>
        <?php if(isset($this->map['party']) and is_array($this->map['party'])){ foreach($this->map['party'] as $key9=>&$item9){if($key9!='current'){$this->map['party']['current'] = &$item9;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['party']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['party']['current']['link_recode'];?>" target="_blank"></a></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['party']['current']['link'];?>" target="_blank"><?php echo $this->map['party']['current']['party_reservation_id'];?></a></td>
            <td><?php echo $this->map['party']['current']['user_name'];?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['party']['current']['price'])); $total_party += $this->map['party']['current']['price']; ?></td>
            <td><?php echo $this->map['party']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['party']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party)); $total_amount+= $total_party; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['vend'])>0){ 
            $total_vend = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN HÀNG</td>
        </tr>
        <?php if(isset($this->map['vend']) and is_array($this->map['vend'])){ foreach($this->map['vend'] as $key10=>&$item10){if($key10!='current'){$this->map['vend']['current'] = &$item10;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['vend']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['vend']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['vend']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['vend']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['vend']['current']['link'];?>" target="_blank"><?php echo $this->map['vend']['current']['ve_reservation_id'];?></a></td>
            <td><?php echo $this->map['vend']['current']['user_name'];?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['vend']['current']['price'])); $total_vend += $this->map['vend']['current']['price']; ?></td>
            <td><?php echo $this->map['vend']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['vend']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend)); $total_amount+= $total_vend; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['ticket'])>0){ 
            $total_ticket = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="10" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN Vé</td>
        </tr>
        <?php if(isset($this->map['ticket']) and is_array($this->map['ticket'])){ foreach($this->map['ticket'] as $key11=>&$item11){if($key11!='current'){$this->map['ticket']['current'] = &$item11;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['ticket']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['ticket']['current']['link_recode'];?>"></a></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['ticket']['current']['link'];?>"><?php echo $this->map['ticket']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['ticket']['current']['user_name'];?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['ticket']['current']['price'])); $total_ticket += $this->map['ticket']['current']['price']; ?></td>
            <td><?php echo $this->map['ticket']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['ticket']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket)); $total_amount+= $total_ticket; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['karaoke'])>0){ 
            $total_karaoke = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU KARaoke</td>
        </tr>
        <?php if(isset($this->map['karaoke']) and is_array($this->map['karaoke'])){ foreach($this->map['karaoke'] as $key12=>&$item12){if($key12!='current'){$this->map['karaoke']['current'] = &$item12;?>
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['karaoke']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['karaoke']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['karaoke']['current']['reservation_id'];?></a></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['karaoke']['current']['link'];?>" target="_blank"><?php echo $this->map['karaoke']['current']['code'];?></a></td>
            <td><?php echo $this->map['karaoke']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['karaoke']['current']['room'];?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['karaoke']['current']['price'])); $total_karaoke += $this->map['karaoke']['current']['price']; ?></td>
            <td><?php echo $this->map['karaoke']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['karaoke']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke)); $total_amount+= $total_karaoke; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr style="background: #eeeeee; font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total_amount');?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_amount)); ?></td>
            <td></td>
        </tr>
</table>
<table id="footer">
</table>
</td>
    </tr>
</table>
<script>
    jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
    jQuery("#time_in").mask('99:99');
    jQuery("#time_out").mask('99:99');
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery("#search").remove();
                jQuery("#report").battatech_excelexport({
                    containerid: "report"
                   , datatype: 'table'
                });
                ReportRevenueGroupOfTypeForm.submit();
            });
        }
    );
    function check_date()
    {
        if((jQuery("#date_from").val()=='') || (jQuery("#date_to").val()==''))
        {
            alert("Bạn chưa nhập đủ ngày bắt đầu hoặc ngày kết thúc!");
            return false;
        }
        else
        {
            if((jQuery("#time_in").val()=='') || (jQuery("#time_out").val()==''))
            {
                alert("Bạn chưa nhập đủ giờ bắt đầu hoặc giờ kết thúc!");
                return false;
            }
            else
            {
                var date_from_arr = jQuery("#date_from").val().split("/");
                var date_to_arr = jQuery("#date_to").val().split("/");
                var date_from = new Date(date_from_arr[2],date_from_arr[1],date_from_arr[0]);
                var date_to = new Date(date_to_arr[2],date_to_arr[1],date_to_arr[0]);
                if(date_from>date_to)
                {
                    alert("Ngày bắt đầu phải nhỏ hơn ngày kết thúc!");
                    jQuery("#date_from").val(jQuery("#date_to").val());
                    return false;
                }
                else
                {
                    if(date_from==date_to)
                    {
                        var time_in = jQuery("#time_in").val().split(':');
                        var time_out = jQuery("#time_out").val().split(':');
                        var t_in = to_numeric(time_in[0])*3600 + to_numeric(time_in[1])*60;
                        var t_out = to_numeric(time_out[0])*3600 + to_numeric(time_out[1])*60;
                        if(t_in>t_out)
                        {
                            alert("giờ bắt đầu phải nhỏ hơn giờ kết thúc!");
                            jQuery("#time_in").val('00:00');
                            jQuery("#time_out").val('23:59');
                            return false;
                        }
                    }
                    else
                    {
                        return true;
                    }
                }
            }
        }
    }
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0]; 
            }
        }) ;
    }
    function delete_customer()
    {
        if(jQuery("#customer_name").val()=='')
        {
            jQuery("#customer_id").val('');
        }
    }
</script>