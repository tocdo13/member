<?php //echo date('H:i d/m/Y',1424060200); ?>
<style>
.simple-layout-center{
    overflow-x: auto;
}
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

/*-------- */
.multiselect,.multiselect_group_customer,.multiselect_customer {
  width: 120px;
}

.selectBox,.selectBox_group_customer,.selectBox_customer {
  position: relative;
}

.selectBox select,.selectBox_group_customer select,.selectBox_customer select {
  width: 100%;
  font-weight: bold;
}

.overSelect,.overSelect_group_customer,.overSelect_customer {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

#checkboxes,#checkboxes_group_customer,#checkboxes_customer {
  display: none;
  border: 1px #1e90ff solid;
  overflow: auto;    
  padding: 2px 15px 2px 5px;
  position: absolute;
  background: white;  
}
#checkboxes_customer{
    height: 300px;
}
#checkboxes label,#checkboxes_group_customer label,#checkboxes_customer label {
  display: block;
}

#checkboxes label:hover,#checkboxes_group_customer label:hover,#checkboxes_customer label:hover {
  background-color: #1e90ff;
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
                <td><input  name="number_of_vote" id="number_of_vote" style="width: 170px;" / type ="text" value="<?php echo String::html_normalize(URL::get('number_of_vote'));?>"></td><!-- so phieu -->
                <td><?php echo Portal::language('re_code');?>:</td>
                <td><input  name="re_code" id="re_code" style="width: 40px;"  class="input_number"/ type ="text" value="<?php echo String::html_normalize(URL::get('re_code'));?>"></td><!-- ma mac dinh -->
                <td><?php echo Portal::language('customer_name');?>:</td>
                <!--Tim kiem nguon khach bang checkbox
                <td><input  name="customer_name" id="customer_name" onkeypress="Autocomplete();" oninput="delete_customer();"  autocomplete="off" style="width:80px;margin-bottom: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                    <input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                </td>
                -->        
                <td>
                <div class="multiselect_customer">
                    <div style="width: 80px;" class="selectBox_customer" onclick="showCheckboxes('customer');">
                      <select >
                        <option></option>
                      </select>
                      <div class="overSelect_customer"></div>
                    </div> 
                    <?php echo $this->map['list_customer'];?>
                    <input  name="customer_ids" id="customer_ids" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_ids'));?>">
                    <input  name="customer_id_" id="customer_id_" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id_'));?>">
                </div>     
                </td>        
                <!-- nhom nguon khach  -->
                <td><?php echo Portal::language('group_customer');?>:</td>
                <!--<td><select  name="group_customer" id="group_customer" style="width: 80px;"><?php
					if(isset($this->map['group_customer_list']))
					{
						foreach($this->map['group_customer_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('group_customer',isset($this->map['group_customer'])?$this->map['group_customer']:''))
                    echo "<script>$('group_customer').value = \"".addslashes(URL::get('group_customer',isset($this->map['group_customer'])?$this->map['group_customer']:''))."\";</script>";
                    ?>
	</select></td>-->
                <td>
                <div class="multiselect_group_customer">
                    <div style="width: 80px;" class="selectBox_group_customer" onclick="showCheckboxes('group_customer');">
                      <select >
                        <option></option>
                      </select>
                      <div class="overSelect_group_customer"></div>
                    </div> 
                    <?php echo $this->map['list_group_customer'];?>
                    <input  name="group_customer_ids" id="group_customer_ids" / type ="hidden" value="<?php echo String::html_normalize(URL::get('group_customer_ids'));?>">
                    <input  name="group_customer_id_" id="group_customer_id_" / type ="hidden" value="<?php echo String::html_normalize(URL::get('group_customer_id_'));?>">
                </div>     
                </td> 
                <td><input name="view_report" type="submit" value="<?php echo Portal::language('view_report');?>" class="button_sm" style="" onclick="return check_date();" /></td>
            </tr>
            <tr>
                <!-- danh muc -->
                <td><?php echo Portal::language('category');?>:</td>
                <!--<td><select  name="list" id="list" style="width: 80px;"><?php
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
	</select></td>-->                   
                <td>
                <div class="multiselect">
                    <div style="width: 80px;" class="selectBox" onclick="showCheckboxes('category');">
                      <select >
                        <option></option>
                      </select>
                      <div class="overSelect"></div>
                    </div> 
                    <?php echo $this->map['list_category'];?>
                    <input  name="str_id" id="str_id" / type ="hidden" value="<?php echo String::html_normalize(URL::get('str_id'));?>">
                </div>     
                </td>                                         
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
                <td><?php echo Portal::language('compact');?></td>
                <td><input  name="check_compact" id="check_compact" onclick="CheckCompact()" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('check_compact'));?>"></td>
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
	    <th><?php echo Portal::language('adult');?></th>
	    <th><?php echo Portal::language('child');?></th>
            <th><?php echo Portal::language('child_5');?></th>	
            <th><?php echo Portal::language('price');?></th>
            <th><?php echo Portal::language('tax');?></th>
            <th><?php echo Portal::language('total_amount');?></th>
            <th><?php echo Portal::language('payment_amount');?></th>
            <th><?php echo Portal::language('total_not_payment');?></th>
            <th><?php echo Portal::language('note');?></th>
        </tr>
        <?php $total_adult =0; $total_child =0; $total_child_5 =0; $total_amount =0; $total_amount_before_tax = 0; $total_amount_tax=0; $total_amount_payment = 0; $total_amount_remain = 0;?>
        <?php if(sizeof($this->map['reservation'])>0){ 
            $total_reservation = 0; $stt = 1; $total_reservation_before_tax = 0; $total_reservation_tax = 0; $total_reservation_payment = 0; $total_reservation_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU PHÒNG</td>
        </tr>
        <?php if(isset($this->map['reservation']) and is_array($this->map['reservation'])){ foreach($this->map['reservation'] as $key1=>&$item1){if($key1!='current'){$this->map['reservation']['current'] = &$item1;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['reservation']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['reservation']['current']['link_detail'];?>" target="_blank"><?php echo $this->map['reservation']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['reservation']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['reservation']['current']['link'];?>" target="_blank">
            <?php
            /** Minh fix link sang hoa don **/
                if($this->map['reservation']['current']['folio_id'] != ''){
                    ?>Folio_<?php echo $this->map['reservation']['current']['folio_id'];?><?php
                }else echo ''; 
            ?>
            </a></td>
            <td><?php echo $this->map['reservation']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['reservation']['current']['room'];?></td>
	        <td style="text-align: right;"><?php echo $this->map['reservation']['current']['adult'];?><?php $total_adult += $this->map['reservation']['current']['adult']; ?></td>
	        <td style="text-align: right;"><?php echo $this->map['reservation']['current']['child'];?><?php $total_child += $this->map['reservation']['current']['child']; ?></td>
            <td style="text-align: right;"><?php echo $this->map['reservation']['current']['child_5'];?><?php $total_child_5 += $this->map['reservation']['current']['child_5']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['reservation']['current']['price_before_tax'])); $total_reservation_before_tax += $this->map['reservation']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $reservation_tax = $this->map['reservation']['current']['price']-$this->map['reservation']['current']['price_before_tax']; echo System::display_number(round($reservation_tax)); $total_reservation_tax += $reservation_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['reservation']['current']['price'])); $total_reservation += $this->map['reservation']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['reservation']['current']['payment_price'])); $total_reservation_payment += $this->map['reservation']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $reservation_remain = $this->map['reservation']['current']['price']-$this->map['reservation']['current']['payment_price']; echo System::display_number(round($reservation_remain)); $total_reservation_remain += $reservation_remain; ?></td>
            <td><?php echo $this->map['reservation']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['reservation']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
	    <td style="text-align: right;"><?php echo $total_adult; ?></td>
	    <td style="text-align: right;"><?php echo $total_child; ?></td>
	    <td style="text-align: right;"><?php echo $total_child_5; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation_before_tax)); $total_amount_before_tax+= $total_reservation_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation_tax)); $total_amount_tax+= $total_reservation_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation)); $total_amount+= $total_reservation; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation_payment)); $total_amount_payment+= $total_reservation_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation_remain)); $total_amount_remain+= $total_reservation_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['list_extra_service'])>0){ ?>
            <?php if(isset($this->map['list_extra_service']) and is_array($this->map['list_extra_service'])){ foreach($this->map['list_extra_service'] as $key2=>&$item2){if($key2!='current'){$this->map['list_extra_service']['current'] = &$item2;?>
            <tr>
                <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU <?php echo $this->map['list_extra_service']['current']['id'];?></td>
            </tr>
            <?php $total_extra_service = 0; $stt = 1; $total_extra_service_before_tax = 0; $total_extra_service_tax = 0; $total_extra_service_payment = 0; $total_extra_service_remain = 0; ?>
                <?php if(isset($this->map['extra_service']) and is_array($this->map['extra_service'])){ foreach($this->map['extra_service'] as $key3=>&$item3){if($key3!='current'){$this->map['extra_service']['current'] = &$item3;?>
                    <?php $arr = explode('_',$this->map['extra_service']['current']['id']); if($arr[0]==$this->map['list_extra_service']['current']['id']){ ?>
                        <tr class="compact_toggle">
                            <td style="text-align: center;"><?php echo $stt++; ?></td>
                            <td style="text-align: center;"><?php echo $this->map['extra_service']['current']['in_date'];?></td>
                            <td style="text-align: center;"><a href="<?php echo $this->map['extra_service']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['extra_service']['current']['reservation_id'];?></a></td>
                            <td><?php echo $this->map['extra_service']['current']['customer_name'];?></td>
                            <td style="text-align: center;"><a href="<?php echo $this->map['extra_service']['current']['link'];?>" target="_blank"><?php echo $this->map['extra_service']['current']['number_of_vote'];?></a></td>
                            <td><?php echo $this->map['extra_service']['current']['user_name'];?></td>
                            <td style="text-align: center;"><?php echo $this->map['extra_service']['current']['room'];?></td>
                            <td style="text-align: right;"></td>
	                        <td style="text-align: right;"></td>
                            <td style="text-align: right;"></td>
                            <td style="text-align: right;"><?php echo System::display_number(round($this->map['extra_service']['current']['price_before_tax']));$total_extra_service_before_tax += $this->map['extra_service']['current']['price_before_tax']; ?></td>
                            <td style="text-align: right;"><?php $service_tax = $this->map['extra_service']['current']['price']-$this->map['extra_service']['current']['price_before_tax']; echo System::display_number(round($service_tax));$total_extra_service_tax += $service_tax; ?></td>
                            <td style="text-align: right;"><?php echo System::display_number(round($this->map['extra_service']['current']['price']));$total_extra_service += $this->map['extra_service']['current']['price']; ?></td>
                            <td style="text-align: right;"><?php echo System::display_number(round($this->map['extra_service']['current']['payment_price']));$total_extra_service_payment += $this->map['extra_service']['current']['payment_price']; ?></td>
                            <td style="text-align: right;"><?php $extra_service_remain = $this->map['extra_service']['current']['price']-$this->map['extra_service']['current']['payment_price']; echo System::display_number(round($extra_service_remain)); $total_extra_service_remain+=$extra_service_remain;?></td>
                            <td><?php echo $this->map['extra_service']['current']['note'];?></td>
                        </tr>
                    <?php } ?>
                <?php }}unset($this->map['extra_service']['current']);} ?>
            <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_extra_service_before_tax)); $total_amount_before_tax+= $total_extra_service_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_extra_service_tax)); $total_amount_tax+= $total_extra_service_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_extra_service)); $total_amount+= $total_extra_service; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_extra_service_payment)); $total_amount_payment+= $total_extra_service_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_extra_service_remain)); $total_amount_remain+= $total_extra_service_remain; ?></td>
                <td></td>
            </tr>
            <?php }}unset($this->map['list_extra_service']['current']);} ?>
        <?php } ?>
        <?php if(sizeof($this->map['minibar'])>0){ 
            $total_minibar = 0; $stt = 1; $total_minibar_before_tax = 0; $total_minibar_tax = 0; $total_minibar_payment = 0; $total_minibar_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU MINIBAR</td>
        </tr>
        <?php if(isset($this->map['minibar']) and is_array($this->map['minibar'])){ foreach($this->map['minibar'] as $key4=>&$item4){if($key4!='current'){$this->map['minibar']['current'] = &$item4;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['minibar']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['minibar']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['minibar']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['minibar']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['minibar']['current']['link'];?>" target="_blank">MN_<?php echo $this->map['minibar']['current']['position'];?></a></td>
            <td><?php echo $this->map['minibar']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['minibar']['current']['room'];?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['minibar']['current']['price_before_tax'])); $total_minibar_before_tax += $this->map['minibar']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $minibar_tax = $this->map['minibar']['current']['price']-$this->map['minibar']['current']['price_before_tax']; echo System::display_number(round($minibar_tax)); $total_minibar_tax += $minibar_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['minibar']['current']['price'])); $total_minibar += $this->map['minibar']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['minibar']['current']['payment_price'])); $total_minibar_payment += $this->map['minibar']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $minibar_remain = $this->map['minibar']['current']['price']-$this->map['minibar']['current']['payment_price']; echo System::display_number(round($minibar_remain)); $total_minibar_remain += $minibar_remain; ?></td>
            <td><?php echo $this->map['minibar']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['minibar']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar_before_tax)); $total_amount_before_tax+=$total_minibar_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar_tax)); $total_amount_tax+=$total_minibar_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar)); $total_amount+=$total_minibar; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar_payment)); $total_amount_payment+= $total_minibar_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar_remain)); $total_amount_remain+= $total_minibar_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['laundry'])>0){ 
            $total_laundry = 0; $stt = 1; $total_laundry_before_tax=0; $total_laundry_tax=0; $total_laundry_payment = 0; $total_laundry_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU LAUNDRY</td>
        </tr>
        <?php if(isset($this->map['laundry']) and is_array($this->map['laundry'])){ foreach($this->map['laundry'] as $key5=>&$item5){if($key5!='current'){$this->map['laundry']['current'] = &$item5;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['laundry']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['laundry']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['laundry']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['laundry']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['laundry']['current']['link'];?>" target="_blank">LD_<?php echo $this->map['laundry']['current']['position'];?></a></td>
            <td><?php echo $this->map['laundry']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['laundry']['current']['room'];?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['laundry']['current']['price_before_tax'])); $total_laundry_before_tax += $this->map['laundry']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $laundry_tax = $this->map['laundry']['current']['price']-$this->map['laundry']['current']['price_before_tax']; echo System::display_number(round($laundry_tax)); $total_laundry_tax += $laundry_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['laundry']['current']['price'])); $total_laundry += $this->map['laundry']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['laundry']['current']['payment_price'])); $total_laundry_payment += $this->map['laundry']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $laundry_remain = $this->map['laundry']['current']['price']-$this->map['laundry']['current']['payment_price']; echo System::display_number(round($laundry_remain)); $total_laundry_remain += $laundry_remain; ?></td>
            <td><?php echo $this->map['laundry']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['laundry']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry_before_tax)); $total_amount_before_tax+=$total_laundry_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry_tax)); $total_amount_tax+=$total_laundry_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry)); $total_amount+=$total_laundry; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry_payment)); $total_amount_payment+= $total_laundry_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry_remain)); $total_amount_remain+= $total_laundry_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['equip'])>0){ 
            $total_equip = 0; $stt = 1; $total_equip_before_tax=0; $total_equip_tax=0; $total_equip_payment = 0; $total_equip_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU HÓA ĐƠN ĐỀN BÙ</td>
        </tr>
        <?php if(isset($this->map['equip']) and is_array($this->map['equip'])){ foreach($this->map['equip'] as $key6=>&$item6){if($key6!='current'){$this->map['equip']['current'] = &$item6;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['equip']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['equip']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['equip']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['equip']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['equip']['current']['link'];?>" target="_blank">EQ_<?php echo $this->map['equip']['current']['position'];?></a></td>
            <td><?php echo $this->map['equip']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['equip']['current']['room'];?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['equip']['current']['price_before_tax'])); $total_equip_before_tax += $this->map['equip']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $equip_tax = $this->map['equip']['current']['price']-$this->map['equip']['current']['price_before_tax']; echo System::display_number(round($equip_tax)); $total_equip_tax += $equip_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['equip']['current']['price'])); $total_equip += $this->map['equip']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['equip']['current']['payment_price'])); $total_equip_payment += $this->map['equip']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $equip_remain = $this->map['equip']['current']['price']-$this->map['equip']['current']['payment_price']; echo System::display_number(round($equip_remain)); $total_equip_remain += $equip_remain; ?></td>
            <td><?php echo $this->map['equip']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['equip']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip_before_tax)); $total_amount_before_tax+=$total_equip_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip_tax)); $total_amount_tax+=$total_equip_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip)); $total_amount+= $total_equip; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip_payment)); $total_amount_payment+= $total_equip_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip_remain)); $total_amount_remain+= $total_equip_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['bar'])>0){ 
            $total_bar = 0; $total_bar_before_tax=0; $total_bar_tax=0; $total_bar_payment = 0; $total_bar_remain = 0; $total_bar_payment = 0; $total_bar_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU NHÀ HÀNG</td>
        </tr>
        <?php if(isset($this->map['bar']) and is_array($this->map['bar'])){ foreach($this->map['bar'] as $key7=>&$item7){if($key7!='current'){$this->map['bar']['current'] = &$item7;?>
        <?php 
            $total_bar_clild = 0; $stt = 1; $total_bar_before_tax_clild=0; $total_bar_tax_clild=0; $total_bar_payment_child = 0; $total_bar_remain_child = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase;font-weight: bold; padding-left: 50px;"><?php echo $this->map['bar']['current']['name'];?></td>
        </tr>
        <?php if(isset($this->map['bar']['current']['child']) and is_array($this->map['bar']['current']['child'])){ foreach($this->map['bar']['current']['child'] as $key8=>&$item8){if($key8!='current'){$this->map['bar']['current']['child']['current'] = &$item8;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['bar']['current']['child']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['bar']['current']['child']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['bar']['current']['child']['current']['reservation_id'];?></a></td>
            <td style="text-align: center;"><?php echo ($this->map['bar']['current']['child']['current']['customer_name_r']!='')?$this->map['bar']['current']['child']['current']['customer_name_r']:$this->map['bar']['current']['child']['current']['customer_name_b']?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['bar']['current']['child']['current']['link'];?>" target="_blank"><?php echo $this->map['bar']['current']['child']['current']['code'];?></a></td>
            <td><?php echo $this->map['bar']['current']['child']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['bar']['current']['child']['current']['room'];?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['bar']['current']['child']['current']['price_before_tax'])); $total_bar_before_tax_clild += $this->map['bar']['current']['child']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $bar_tax = $this->map['bar']['current']['child']['current']['price']-$this->map['bar']['current']['child']['current']['price_before_tax']; echo System::display_number(round($bar_tax)); $total_bar_tax_clild += $bar_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['bar']['current']['child']['current']['price'])); $total_bar_clild += $this->map['bar']['current']['child']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['bar']['current']['child']['current']['payment_price'])); $total_bar_payment_child += $this->map['bar']['current']['child']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $bar_remain = $this->map['bar']['current']['child']['current']['price']-$this->map['bar']['current']['child']['current']['payment_price']; echo System::display_number(round($bar_remain)); $total_bar_remain_child += $bar_remain; ?></td>
            <td><?php echo $this->map['bar']['current']['child']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['bar']['current']['child']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td></td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_before_tax_clild)); $total_amount_before_tax+=$total_bar_before_tax_clild; $total_bar_before_tax+=$total_bar_before_tax_clild; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_tax_clild)); $total_amount_tax+=$total_bar_tax_clild; $total_bar_tax+=$total_bar_tax_clild;?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_clild)); $total_amount+= $total_bar_clild; $total_bar+=$total_bar_clild;?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_payment_child)); $total_amount_payment+= $total_bar_payment_child; $total_bar_payment+=$total_bar_payment_child;?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_remain_child)); $total_amount_remain+= $total_bar_remain_child; $total_bar_remain+=$total_bar_remain_child;?></td>
            <td></td>
        </tr>
        <?php }}unset($this->map['bar']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_before_tax)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_tax));?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar));?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_payment));?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar_remain));?></td>
            <td></td>                        
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['spa'])>0){ 
            $total_spa = 0; $stt = 1; $total_spa_before_tax=0; $total_spa_tax=0; $total_spa_payment = 0; $total_spa_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU SPA</td>
        </tr>
        <?php if(isset($this->map['spa']) and is_array($this->map['spa'])){ foreach($this->map['spa'] as $key9=>&$item9){if($key9!='current'){$this->map['spa']['current'] = &$item9;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['spa']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['spa']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['spa']['current']['reservation_id'];?></a></td>
            <td style="text-align: right;"><?php echo $this->map['spa']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['spa']['current']['link'];?>" target="_blank"><?php echo $this->map['spa']['current']['massage_reservation_room_id'];?></a></td>            
            <td><?php echo $this->map['spa']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['spa']['current']['room'];?></td>
            <td style="text-align: right;"></td>	        
            <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['spa']['current']['price_before_tax'])); $total_spa_before_tax += $this->map['spa']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $spa_tax = $this->map['spa']['current']['price']-$this->map['spa']['current']['price_before_tax']; echo System::display_number(round($spa_tax)); $total_spa_tax += $spa_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['spa']['current']['price'])); $total_spa += $this->map['spa']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['spa']['current']['payment_price'])); $total_spa_payment += $this->map['spa']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $spa_remain = $this->map['spa']['current']['price']-$this->map['spa']['current']['payment_price']; echo System::display_number(round($spa_remain)); $total_spa_remain += $spa_remain; ?></td>
            <td><?php echo $this->map['spa']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['spa']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa_before_tax)); $total_amount_before_tax+=$total_spa_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa_tax)); $total_amount_tax+=$total_spa_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa)); $total_amount+= $total_spa; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa_payment)); $total_amount_payment+= $total_spa_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa_remain)); $total_amount_remain+= $total_spa_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['party'])>0){ 
            $total_party = 0; $stt = 1; $total_party_before_tax=0; $total_party_tax=0; $total_party_payment = 0; $total_party_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU Đặt Tiệc</td>
        </tr>
        <?php if(isset($this->map['party']) and is_array($this->map['party'])){ foreach($this->map['party'] as $key10=>&$item10){if($key10!='current'){$this->map['party']['current'] = &$item10;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['party']['current']['in_date'];?></td>            
            <td style="text-align: center;"><a href="<?php echo $this->map['party']['current']['link_recode'];?>" target="_blank"></a></td>
            <td style="text-align: center;"><?php echo $this->map['party']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['party']['current']['link'];?>" target="_blank"><?php echo $this->map['party']['current']['party_reservation_id'];?></a></td>
            <td><?php echo $this->map['party']['current']['user_name'];?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['party']['current']['price_before_tax'])); $total_party_before_tax += $this->map['party']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $party_tax = $this->map['party']['current']['price']-$this->map['party']['current']['price_before_tax']; echo System::display_number(round($party_tax)); $total_party_tax += $party_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['party']['current']['price'])); $total_party += $this->map['party']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['party']['current']['payment_price'])); $total_party_payment += $this->map['party']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $party_remain = $this->map['party']['current']['price']-$this->map['party']['current']['payment_price']; echo System::display_number(round($party_remain)); $total_party_remain += $party_remain; ?></td>
            <td><?php echo $this->map['party']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['party']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party_before_tax)); $total_amount_before_tax+=$total_party_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party_tax)); $total_amount_tax+=$total_party_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party)); $total_amount+= $total_party; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party_payment)); $total_amount_payment+= $total_party_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party_remain)); $total_amount_remain+= $total_party_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['vend'])>0){ 
            $total_vend = 0; $stt = 1; $total_vend_before_tax=0; $total_vend_tax=0; $total_vend_payment = 0; $total_vend_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN HÀNG</td>
        </tr>
        <?php if(isset($this->map['vend']) and is_array($this->map['vend'])){ foreach($this->map['vend'] as $key11=>&$item11){if($key11!='current'){$this->map['vend']['current'] = &$item11;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['vend']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['vend']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['vend']['current']['reservation_id'];?></a></td>
            <td><?php echo $this->map['vend']['current']['customer_name'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['vend']['current']['link'];?>" target="_blank"><?php echo $this->map['vend']['current']['ve_reservation_id'];?></a></td>
            <td><?php echo $this->map['vend']['current']['user_name'];?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['vend']['current']['price_before_tax'])); $total_vend_before_tax += $this->map['vend']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $vend_tax = $this->map['vend']['current']['price']-$this->map['vend']['current']['price_before_tax']; echo System::display_number(round($vend_tax)); $total_vend_tax += $vend_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['vend']['current']['price'])); $total_vend += $this->map['vend']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['vend']['current']['payment_price'])); $total_vend_payment += $this->map['vend']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $vend_remain = $this->map['vend']['current']['price']-$this->map['vend']['current']['payment_price']; echo System::display_number(round($vend_remain)); $total_vend_remain += $vend_remain; ?></td>
            <td><?php echo $this->map['vend']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['vend']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend_before_tax)); $total_amount_before_tax+=$total_vend_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend_tax)); $total_amount_tax+=$total_vend_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend)); $total_amount+= $total_vend; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend_payment)); $total_amount_payment+= $total_vend_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend_remain)); $total_amount_remain+= $total_vend_remain; ?></td>
            <td></td>            
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['ticket'])>0){ 
            $total_ticket = 0; $stt = 1; $total_ticket_before_tax=0; $total_ticket_tax=0; $total_ticket_payment = 0; $total_ticket_remain = 0;
        ?>
        <tr>
            <td colspan="12" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN Vé</td>
        </tr>
        <?php if(isset($this->map['ticket']) and is_array($this->map['ticket'])){ foreach($this->map['ticket'] as $key12=>&$item12){if($key12!='current'){$this->map['ticket']['current'] = &$item12;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['ticket']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['ticket']['current']['link_recode'];?>"></a></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['ticket']['current']['link'];?>"><?php echo $this->map['ticket']['current']['number_of_vote'];?></a></td>
            <td><?php echo $this->map['ticket']['current']['user_name'];?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['ticket']['current']['price_before_tax'])); $total_ticket_before_tax += $this->map['ticket']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $ticket_tax = $this->map['ticket']['current']['price']-$this->map['ticket']['current']['price_before_tax']; echo System::display_number(round($ticket_tax)); $total_ticket_tax += $ticket_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['ticket']['current']['price'])); $total_ticket += $this->map['ticket']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['ticket']['current']['payment_price'])); $total_ticket_payment += $this->map['ticket']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $ticket_remain = $this->map['ticket']['current']['price']-$this->map['ticket']['current']['payment_price']; echo System::display_number(round($ticket_remain)); $total_ticket_remain += $ticket_remain; ?></td>
            <td><?php echo $this->map['ticket']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['ticket']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket_before_tax)); $total_amount_before_tax+=$total_ticket_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket_tax)); $total_amount_tax+=$total_ticket_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket)); $total_amount+= $total_ticket; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket_payment)); $total_amount_payment+= $total_ticket_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket_remain)); $total_amount_remain+= $total_ticket_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof($this->map['karaoke'])>0){ 
            $total_karaoke = 0; $stt = 1; $total_karaoke_before_tax=0; $total_karaoke_tax=0; $total_karaoke_payment = 0; $total_karaoke_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU KARaoke</td>
        </tr>
        <?php if(isset($this->map['karaoke']) and is_array($this->map['karaoke'])){ foreach($this->map['karaoke'] as $key13=>&$item13){if($key13!='current'){$this->map['karaoke']['current'] = &$item13;?>
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;"><?php echo $this->map['karaoke']['current']['in_date'];?></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['karaoke']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['karaoke']['current']['reservation_id'];?></a></td>
            <td style="text-align: center;"><a href="<?php echo $this->map['karaoke']['current']['link'];?>" target="_blank"><?php echo $this->map['karaoke']['current']['code'];?></a></td>
            <td><?php echo $this->map['karaoke']['current']['user_name'];?></td>
            <td style="text-align: center;"><?php echo $this->map['karaoke']['current']['room'];?></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['karaoke']['current']['price_before_tax'])); $total_karaoke_before_tax += $this->map['karaoke']['current']['price_before_tax']; ?></td>
            <td style="text-align: right;"><?php $karaoke_tax = $this->map['karaoke']['current']['price']-$this->map['karaoke']['current']['price_before_tax']; echo System::display_number(round($karaoke_tax)); $total_karaoke_tax += $karaoke_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['karaoke']['current']['price'])); $total_karaoke += $this->map['karaoke']['current']['price']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($this->map['karaoke']['current']['payment_price'])); $total_karaoke_payment += $this->map['karaoke']['current']['payment_price']; ?></td>
            <td style="text-align: right;"><?php $karaoke_remain = $this->map['karaoke']['current']['price']-$this->map['karaoke']['current']['payment_price']; echo System::display_number(round($karaoke_remain)); $total_karaoke_remain += $karaoke_remain; ?></td>
            <td><?php echo $this->map['karaoke']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['karaoke']['current']);} ?>
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke_before_tax)); $total_amount_before_tax+=$total_karaoke_before_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke_tax)); $total_amount_tax+=$total_karaoke_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke)); $total_amount+= $total_karaoke; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke_payment)); $total_amount_payment+= $total_karaoke_payment; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke_remain)); $total_amount_remain+= $total_karaoke_remain; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr style="background: #eeeeee; font-weight: bold;">
            <td>...</td>
            <td colspan="6"><?php echo Portal::language('total');?></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_amount_before_tax)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_amount-$total_amount_before_tax)); //$total_amount_tax?></td> 
            <td style="text-align: right;"><?php echo System::display_number(round($total_amount)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_amount_payment)); ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_amount_remain)); ?></td>
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
    var category = <?php echo $this->map['category'];?>;
    var customer_group = <?php echo $this->map['customer_group'];?>;
    var customer = <?php echo $this->map['customer_js'];?>; 
    console.log(customer);   
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery("#search").remove();
                jQuery("#report").battatech_excelexport({
                    containerid: "report"
                   , datatype: 'table'
                   , fileName: '<?php echo Portal::language('report_revenue_group_of_type');?>'
                });
                ReportRevenueGroupOfTypeForm.submit();
            });
            for(var i in category)
            {                               
                jQuery('.category').each(function(){
                    if(jQuery('#'+this.id).attr('flag') == category[i])
                    {
                        jQuery('#'+this.id).attr('checked', true);                        
                    }
                })
            }
            for(var i in customer_group){                
                jQuery('.group_customer').each(function(){                    
                    if(jQuery('#'+this.id).attr('flag') == customer_group[i])
                    {
                        jQuery('#'+this.id).attr('checked', true);                                                
                    }
                })
            }
            for(var i in customer){                
                jQuery('.customer').each(function(){                    
                    if(jQuery('#'+this.id).attr('flag') == customer[i])
                    {
                        jQuery('#'+this.id).attr('checked', true);                                                
                    }
                })
            }
        }
    );
    /** Minh xu li checkbox cho select **/    
    var expanded_group_customer = false;
    var expanded_category = false; 
    var expanded_customer = false;    
    function showCheckboxes(value) {
      if(value =='group_customer'){
        var checkboxes_group_customer = document.getElementById("checkboxes_group_customer");
          if (!expanded_group_customer) {
            checkboxes_group_customer.style.display = "block";
            expanded_group_customer = true;
          } else {
            checkboxes_group_customer.style.display = "none";        
            expanded_group_customer = false;
          }
      } 
      if(value =='customer'){
        var checkboxes_customer = document.getElementById("checkboxes_customer");
          if (!expanded_customer) {
            checkboxes_customer.style.display = "block";
            expanded_customer = true;
          } else {
            checkboxes_customer.style.display = "none";        
            expanded_customer = false;
          }
      } 
      if(value=='category'){
        var checkboxes = document.getElementById("checkboxes");
          if (!expanded_category) {
            checkboxes.style.display = "block";
            expanded_category = true;
          } else {
            checkboxes.style.display = "none";        
            expanded_category = false;
          }
      }           
    }      
    jQuery(document).on('click', function(e) {
      var $clicked = jQuery(e.target);
     if (!$clicked.parents().hasClass("multiselect")) jQuery('#checkboxes').hide();
     if (!$clicked.parents().hasClass("multiselect_group_customer")) jQuery('#checkboxes_group_customer').hide();
     if (!$clicked.parents().hasClass("multiselect_customer")) jQuery('#checkboxes_customer').hide();
    });         
    function get_ids(value)
    {           
        var strids = "";
        var str_group_customer = "";
        var group_customer_id = "";
        var str_customer = "";
        var customer_id = "";
        if(value=='category'){
           var inputs = jQuery('.category:checkbox:checked');                        
           for (var i=0;i<inputs.length;i++)
            {             
                if(inputs[i].id.indexOf('cateory_')==0)
                {
                    strids +=","+inputs[i].id.replace("cateory_","");                
                }                                       
            }
            strids = strids.replace(",","");
            jQuery('#str_id').val(strids);  
        }
        if(value=='group_customer'){
            var inputs = jQuery('.group_customer:checkbox:checked');            
            for (var i=0;i<inputs.length;i++)
            {  
                if(inputs[i].id.indexOf('group_customer_')==0)
                {
                    str_group_customer +=","+"'"+inputs[i].id.replace("group_customer_","")+"'";
                    group_customer_id +=","+inputs[i].id.replace("group_customer_","");                
                }
            }                
            str_group_customer = str_group_customer.replace(",","");
            group_customer_id = group_customer_id.replace(",","");             
            jQuery('#group_customer_ids').val(str_group_customer);
            jQuery('#group_customer_id_').val(group_customer_id);             
        }  
        if(value=='customer'){
            var inputs = jQuery('.customer:checkbox:checked');            
            for (var i=0;i<inputs.length;i++)
            {  
                if(inputs[i].id.indexOf('customer_')==0)
                {
                    str_customer +=","+"'"+inputs[i].id.replace("customer_","")+"'";
                    customer_id +=","+inputs[i].id.replace("customer_","");                
                }
            }                
            str_customer = str_customer.replace(",","");
            customer_id = customer_id.replace(",","");             
            jQuery('#customer_ids').val(str_customer);
            jQuery('#customer_id_').val(customer_id);             
        }                
    }
    /** Minh xu li checkbox cho select **/
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
                            alert("Giờ bắt đầu phải nhỏ hơn giờ kết thúc!");
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
   function CheckCompact()
   {
    if(jQuery("#check_compact").attr('checked') =='checked')
    {
        jQuery(".compact_toggle").css('display','none');
    }
    else
    {
        jQuery(".compact_toggle").css('display','');
    }
   } 
</script>