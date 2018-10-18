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
        <td style="font-weight: bold; text-align: right;">[[.template_code.]]</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; text-transform: uppercase;"><h3 style="font-size: 20px;">[[.report_revenue_group_of_type.]]</h3>[[.from_date.]]: [[|date_from|]] [[.to.]]: [[|date_to|]]</td>
    </tr>
</table>
<fieldset id="search" style="width: 98%; margin: 0px auto;">
    <legend>[[.option.]]</legend>
    <form name="ReportRevenueGroupOfTypeForm" method="post">
        <table style="margin: 0px auto; width: 100%;" cellpadding="5" cellspacing="0">
            <tr>
                <td>[[.hotel.]]:</td>
                <td><select name="portal_id" id="portal_id" style="width: 80px;"></select></td>
                <td>[[.date_from.]]:</td>
                <td><input name="date_from" type="text" id="date_from" style="width: 80px;" /></td><!-- ngay bat dau -->
                <td>[[.time_in.]]:</td>
                <td><input name="time_in" type="text" id="time_in" style="width: 40px;" /></td><!-- gio bat dau -->
                <td>[[.num_of_vote.]]:</td>
                <td><input name="number_of_vote" type="text" id="number_of_vote" style="width: 170px;" /></td><!-- so phieu -->
                <td>[[.re_code.]]:</td>
                <td><input name="re_code" type="text" id="re_code" style="width: 40px;"  class="input_number"/></td><!-- ma mac dinh -->
                <td>[[.customer_name.]]:</td>
                <!--Tim kiem nguon khach bang checkbox
                <td><input name="customer_name" type="text" id="customer_name" onkeypress="Autocomplete();" oninput="delete_customer();"  autocomplete="off" style="width:80px;margin-bottom: 5px;" />
                    <input name="customer_id" type="text" id="customer_id" class="hidden" />
                </td>
                -->        
                <td>
                <div class="multiselect_customer">
                    <div style="width: 80px;" class="selectBox_customer" onclick="showCheckboxes('customer');">
                      <select>
                        <option></option>
                      </select>
                      <div class="overSelect_customer"></div>
                    </div> 
                    [[|list_customer|]]
                    <input name="customer_ids" type="hidden" id="customer_ids" />
                    <input name="customer_id_" type="hidden" id="customer_id_" />
                </div>     
                </td>        
                <!-- nhom nguon khach  -->
                <td>[[.group_customer.]]:</td>
                <!--<td><select name="group_customer" id="group_customer" style="width: 80px;"></select></td>-->
                <td>
                <div class="multiselect_group_customer">
                    <div style="width: 80px;" class="selectBox_group_customer" onclick="showCheckboxes('group_customer');">
                      <select>
                        <option></option>
                      </select>
                      <div class="overSelect_group_customer"></div>
                    </div> 
                    [[|list_group_customer|]]
                    <input name="group_customer_ids" type="hidden" id="group_customer_ids" />
                    <input name="group_customer_id_" type="hidden" id="group_customer_id_" />
                </div>     
                </td> 
                <td><input name="view_report" type="submit" value="[[.view_report.]]" class="button_sm" style="" onclick="return check_date();" /></td>
            </tr>
            <tr>
                <!-- danh muc -->
                <td>[[.category.]]:</td>
                <!--<td><select name="list" id="list" style="width: 80px;"></select></td>-->                   
                <td>
                <div class="multiselect">
                    <div style="width: 80px;" class="selectBox" onclick="showCheckboxes('category');">
                      <select>
                        <option></option>
                      </select>
                      <div class="overSelect"></div>
                    </div> 
                    [[|list_category|]]
                    <input name="str_id" type="hidden" id="str_id" />
                </div>     
                </td>                                         
                <td>[[.date_to.]]:</td>
                <td><input name="date_to" type="text" id="date_to" style="width: 80px;" /></td><!-- ngay ket thuc -->
                <td>[[.time_out.]]:</td>
                <td><input name="time_out" type="text" id="time_out" style="width: 40px;" /></td><!-- gio bat dau -->
                <td>[[.create_user.]]:</td>
                <td><select name="create_user" id="create_user" style="width: 170px;"></select></td><!-- nguoi tao -->
                <td>[[.room_number.]]:</td>
                <td><input name="room_number" type="text" id="room_number" style="width: 40px;" /></td><!-- so phong -->
                <td>[[.status.]]:</td>
                <td><select name="status" id="status" style="width: 80px;"></select></td>
                <td>[[.compact.]]</td>
                <td><input name="check_compact" type="checkbox" id="check_compact" onclick="CheckCompact()" /></td>
                <td><button id="export" class="button_sm" style="">[[.export_excel.]]</button></td>
            </tr>
        </table>
    </form>
</fieldset>
<table class="report"  border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" style="width: 99%; margin: 10px auto; border-collapse: collapse;">
        <tr style="text-align: center; background: #eeeeee; height: 30px; text-transform: uppercase;">
            <th style="width: 30px;">[[.stt.]]</th>
            <th style="width: 80px;">[[.date.]]</th>
            <th style="width: 50px;">[[.re_code.]]</th>
            <th style="width: 50px;">[[.customer.]]</th>
            <th>[[.num_of_vote.]]</th>
            <th>[[.create_user.]]</th>
            <th>[[.room.]]</th>
	    <th>[[.adult.]]</th>
	    <th>[[.child.]]</th>
            <th>[[.child_5.]]</th>	
            <th>[[.price.]]</th>
            <th>[[.tax.]]</th>
            <th>[[.total_amount.]]</th>
            <th>[[.payment_amount.]]</th>
            <th>[[.total_not_payment.]]</th>
            <th>[[.note.]]</th>
        </tr>
        <?php $total_adult =0; $total_child =0; $total_child_5 =0; $total_amount =0; $total_amount_before_tax = 0; $total_amount_tax=0; $total_amount_payment = 0; $total_amount_remain = 0;?>
        <?php if(sizeof([[=reservation=]])>0){ 
            $total_reservation = 0; $stt = 1; $total_reservation_before_tax = 0; $total_reservation_tax = 0; $total_reservation_payment = 0; $total_reservation_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU PHÒNG</td>
        </tr>
        <!--LIST:reservation-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|reservation.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|reservation.link_detail|]]" target="_blank">[[|reservation.number_of_vote|]]</a></td>
            <td>[[|reservation.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|reservation.link|]]" target="_blank">
            <?php
            /** Minh fix link sang hoa don **/
                if([[=reservation.folio_id=]] != ''){
                    ?>Folio_[[|reservation.folio_id|]]<?php
                }else echo ''; 
            ?>
            </a></td>
            <td>[[|reservation.user_name|]]</td>
            <td style="text-align: center;">[[|reservation.room|]]</td>
	        <td style="text-align: right;">[[|reservation.adult|]]<?php $total_adult += [[=reservation.adult=]]; ?></td>
	        <td style="text-align: right;">[[|reservation.child|]]<?php $total_child += [[=reservation.child=]]; ?></td>
            <td style="text-align: right;">[[|reservation.child_5|]]<?php $total_child_5 += [[=reservation.child_5=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=reservation.price_before_tax=]])); $total_reservation_before_tax += [[=reservation.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $reservation_tax = [[=reservation.price=]]-[[=reservation.price_before_tax=]]; echo System::display_number(round($reservation_tax)); $total_reservation_tax += $reservation_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=reservation.price=]])); $total_reservation += [[=reservation.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=reservation.payment_price=]])); $total_reservation_payment += [[=reservation.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $reservation_remain = [[=reservation.price=]]-[[=reservation.payment_price=]]; echo System::display_number(round($reservation_remain)); $total_reservation_remain += $reservation_remain; ?></td>
            <td>[[|reservation.note|]]</td>
        </tr>
        <!--/LIST:reservation-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=list_extra_service=]])>0){ ?>
            <!--LIST:list_extra_service-->
            <tr>
                <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU [[|list_extra_service.id|]]</td>
            </tr>
            <?php $total_extra_service = 0; $stt = 1; $total_extra_service_before_tax = 0; $total_extra_service_tax = 0; $total_extra_service_payment = 0; $total_extra_service_remain = 0; ?>
                <!--LIST:extra_service-->
                    <?php $arr = explode('_',[[=extra_service.id=]]); if($arr[0]==[[=list_extra_service.id=]]){ ?>
                        <tr class="compact_toggle">
                            <td style="text-align: center;"><?php echo $stt++; ?></td>
                            <td style="text-align: center;">[[|extra_service.in_date|]]</td>
                            <td style="text-align: center;"><a href="[[|extra_service.link_recode|]]" target="_blank">[[|extra_service.reservation_id|]]</a></td>
                            <td>[[|extra_service.customer_name|]]</td>
                            <td style="text-align: center;"><a href="[[|extra_service.link|]]" target="_blank">[[|extra_service.number_of_vote|]]</a></td>
                            <td>[[|extra_service.user_name|]]</td>
                            <td style="text-align: center;">[[|extra_service.room|]]</td>
                            <td style="text-align: right;"></td>
	                        <td style="text-align: right;"></td>
                            <td style="text-align: right;"></td>
                            <td style="text-align: right;"><?php echo System::display_number(round([[=extra_service.price_before_tax=]]));$total_extra_service_before_tax += [[=extra_service.price_before_tax=]]; ?></td>
                            <td style="text-align: right;"><?php $service_tax = [[=extra_service.price=]]-[[=extra_service.price_before_tax=]]; echo System::display_number(round($service_tax));$total_extra_service_tax += $service_tax; ?></td>
                            <td style="text-align: right;"><?php echo System::display_number(round([[=extra_service.price=]]));$total_extra_service += [[=extra_service.price=]]; ?></td>
                            <td style="text-align: right;"><?php echo System::display_number(round([[=extra_service.payment_price=]]));$total_extra_service_payment += [[=extra_service.payment_price=]]; ?></td>
                            <td style="text-align: right;"><?php $extra_service_remain = [[=extra_service.price=]]-[[=extra_service.payment_price=]]; echo System::display_number(round($extra_service_remain)); $total_extra_service_remain+=$extra_service_remain;?></td>
                            <td>[[|extra_service.note|]]</td>
                        </tr>
                    <?php } ?>
                <!--/LIST:extra_service-->
            <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
            <!--/LIST:list_extra_service-->
        <?php } ?>
        <?php if(sizeof([[=minibar=]])>0){ 
            $total_minibar = 0; $stt = 1; $total_minibar_before_tax = 0; $total_minibar_tax = 0; $total_minibar_payment = 0; $total_minibar_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU MINIBAR</td>
        </tr>
        <!--LIST:minibar-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|minibar.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|minibar.link_recode|]]" target="_blank">[[|minibar.reservation_id|]]</a></td>
            <td>[[|minibar.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|minibar.link|]]" target="_blank">MN_[[|minibar.position|]]</a></td>
            <td>[[|minibar.user_name|]]</td>
            <td style="text-align: center;">[[|minibar.room|]]</td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=minibar.price_before_tax=]])); $total_minibar_before_tax += [[=minibar.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $minibar_tax = [[=minibar.price=]]-[[=minibar.price_before_tax=]]; echo System::display_number(round($minibar_tax)); $total_minibar_tax += $minibar_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=minibar.price=]])); $total_minibar += [[=minibar.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=minibar.payment_price=]])); $total_minibar_payment += [[=minibar.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $minibar_remain = [[=minibar.price=]]-[[=minibar.payment_price=]]; echo System::display_number(round($minibar_remain)); $total_minibar_remain += $minibar_remain; ?></td>
            <td>[[|minibar.note|]]</td>
        </tr>
        <!--/LIST:minibar-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=laundry=]])>0){ 
            $total_laundry = 0; $stt = 1; $total_laundry_before_tax=0; $total_laundry_tax=0; $total_laundry_payment = 0; $total_laundry_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU LAUNDRY</td>
        </tr>
        <!--LIST:laundry-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|laundry.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|laundry.link_recode|]]" target="_blank">[[|laundry.reservation_id|]]</a></td>
            <td>[[|laundry.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|laundry.link|]]" target="_blank">LD_[[|laundry.position|]]</a></td>
            <td>[[|laundry.user_name|]]</td>
            <td style="text-align: center;">[[|laundry.room|]]</td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=laundry.price_before_tax=]])); $total_laundry_before_tax += [[=laundry.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $laundry_tax = [[=laundry.price=]]-[[=laundry.price_before_tax=]]; echo System::display_number(round($laundry_tax)); $total_laundry_tax += $laundry_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=laundry.price=]])); $total_laundry += [[=laundry.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=laundry.payment_price=]])); $total_laundry_payment += [[=laundry.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $laundry_remain = [[=laundry.price=]]-[[=laundry.payment_price=]]; echo System::display_number(round($laundry_remain)); $total_laundry_remain += $laundry_remain; ?></td>
            <td>[[|laundry.note|]]</td>
        </tr>
        <!--/LIST:laundry-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=equip=]])>0){ 
            $total_equip = 0; $stt = 1; $total_equip_before_tax=0; $total_equip_tax=0; $total_equip_payment = 0; $total_equip_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU HÓA ĐƠN ĐỀN BÙ</td>
        </tr>
        <!--LIST:equip-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|equip.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|equip.link_recode|]]" target="_blank">[[|equip.reservation_id|]]</a></td>
            <td>[[|equip.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|equip.link|]]" target="_blank">EQ_[[|equip.position|]]</a></td>
            <td>[[|equip.user_name|]]</td>
            <td style="text-align: center;">[[|equip.room|]]</td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=equip.price_before_tax=]])); $total_equip_before_tax += [[=equip.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $equip_tax = [[=equip.price=]]-[[=equip.price_before_tax=]]; echo System::display_number(round($equip_tax)); $total_equip_tax += $equip_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=equip.price=]])); $total_equip += [[=equip.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=equip.payment_price=]])); $total_equip_payment += [[=equip.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $equip_remain = [[=equip.price=]]-[[=equip.payment_price=]]; echo System::display_number(round($equip_remain)); $total_equip_remain += $equip_remain; ?></td>
            <td>[[|equip.note|]]</td>
        </tr>
        <!--/LIST:equip-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=bar=]])>0){ 
            $total_bar = 0; $total_bar_before_tax=0; $total_bar_tax=0; $total_bar_payment = 0; $total_bar_remain = 0; $total_bar_payment = 0; $total_bar_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU NHÀ HÀNG</td>
        </tr>
        <!--LIST:bar-->
        <?php 
            $total_bar_clild = 0; $stt = 1; $total_bar_before_tax_clild=0; $total_bar_tax_clild=0; $total_bar_payment_child = 0; $total_bar_remain_child = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase;font-weight: bold; padding-left: 50px;">[[|bar.name|]]</td>
        </tr>
        <!--LIST:bar.child-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|bar.child.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|bar.child.link_recode|]]" target="_blank">[[|bar.child.reservation_id|]]</a></td>
            <td style="text-align: center;"><?php echo ([[=bar.child.customer_name_r=]]!='')?[[=bar.child.customer_name_r=]]:[[=bar.child.customer_name_b=]]?></td>
            <td style="text-align: center;"><a href="[[|bar.child.link|]]" target="_blank">[[|bar.child.code|]]</a></td>
            <td>[[|bar.child.user_name|]]</td>
            <td style="text-align: center;">[[|bar.child.room|]]</td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=bar.child.price_before_tax=]])); $total_bar_before_tax_clild += [[=bar.child.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $bar_tax = [[=bar.child.price=]]-[[=bar.child.price_before_tax=]]; echo System::display_number(round($bar_tax)); $total_bar_tax_clild += $bar_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=bar.child.price=]])); $total_bar_clild += [[=bar.child.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=bar.child.payment_price=]])); $total_bar_payment_child += [[=bar.child.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $bar_remain = [[=bar.child.price=]]-[[=bar.child.payment_price=]]; echo System::display_number(round($bar_remain)); $total_bar_remain_child += $bar_remain; ?></td>
            <td>[[|bar.child.note|]]</td>
        </tr>
        <!--/LIST:bar.child-->
        <tr style=" font-weight: bold;">
            <td></td>
            <td colspan="6">[[.total.]]</td>
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
        <!--/LIST:bar-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=spa=]])>0){ 
            $total_spa = 0; $stt = 1; $total_spa_before_tax=0; $total_spa_tax=0; $total_spa_payment = 0; $total_spa_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU SPA</td>
        </tr>
        <!--LIST:spa-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|spa.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|spa.link_recode|]]" target="_blank">[[|spa.reservation_id|]]</a></td>
            <td style="text-align: right;">[[|spa.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|spa.link|]]" target="_blank">[[|spa.massage_reservation_room_id|]]</a></td>            
            <td>[[|spa.user_name|]]</td>
            <td style="text-align: center;">[[|spa.room|]]</td>
            <td style="text-align: right;"></td>	        
            <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=spa.price_before_tax=]])); $total_spa_before_tax += [[=spa.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $spa_tax = [[=spa.price=]]-[[=spa.price_before_tax=]]; echo System::display_number(round($spa_tax)); $total_spa_tax += $spa_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=spa.price=]])); $total_spa += [[=spa.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=spa.payment_price=]])); $total_spa_payment += [[=spa.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $spa_remain = [[=spa.price=]]-[[=spa.payment_price=]]; echo System::display_number(round($spa_remain)); $total_spa_remain += $spa_remain; ?></td>
            <td>[[|spa.note|]]</td>
        </tr>
        <!--/LIST:spa-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=party=]])>0){ 
            $total_party = 0; $stt = 1; $total_party_before_tax=0; $total_party_tax=0; $total_party_payment = 0; $total_party_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU Đặt Tiệc</td>
        </tr>
        <!--LIST:party-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|party.in_date|]]</td>            
            <td style="text-align: center;"><a href="[[|party.link_recode|]]" target="_blank"></a></td>
            <td style="text-align: center;">[[|party.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|party.link|]]" target="_blank">[[|party.party_reservation_id|]]</a></td>
            <td>[[|party.user_name|]]</td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=party.price_before_tax=]])); $total_party_before_tax += [[=party.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $party_tax = [[=party.price=]]-[[=party.price_before_tax=]]; echo System::display_number(round($party_tax)); $total_party_tax += $party_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=party.price=]])); $total_party += [[=party.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=party.payment_price=]])); $total_party_payment += [[=party.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $party_remain = [[=party.price=]]-[[=party.payment_price=]]; echo System::display_number(round($party_remain)); $total_party_remain += $party_remain; ?></td>
            <td>[[|party.note|]]</td>
        </tr>
        <!--/LIST:party-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=vend=]])>0){ 
            $total_vend = 0; $stt = 1; $total_vend_before_tax=0; $total_vend_tax=0; $total_vend_payment = 0; $total_vend_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN HÀNG</td>
        </tr>
        <!--LIST:vend-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|vend.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|vend.link_recode|]]" target="_blank">[[|vend.reservation_id|]]</a></td>
            <td>[[|vend.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|vend.link|]]" target="_blank">[[|vend.ve_reservation_id|]]</a></td>
            <td>[[|vend.user_name|]]</td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=vend.price_before_tax=]])); $total_vend_before_tax += [[=vend.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $vend_tax = [[=vend.price=]]-[[=vend.price_before_tax=]]; echo System::display_number(round($vend_tax)); $total_vend_tax += $vend_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=vend.price=]])); $total_vend += [[=vend.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=vend.payment_price=]])); $total_vend_payment += [[=vend.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $vend_remain = [[=vend.price=]]-[[=vend.payment_price=]]; echo System::display_number(round($vend_remain)); $total_vend_remain += $vend_remain; ?></td>
            <td>[[|vend.note|]]</td>
        </tr>
        <!--/LIST:vend-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=ticket=]])>0){ 
            $total_ticket = 0; $stt = 1; $total_ticket_before_tax=0; $total_ticket_tax=0; $total_ticket_payment = 0; $total_ticket_remain = 0;
        ?>
        <tr>
            <td colspan="12" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN Vé</td>
        </tr>
        <!--LIST:ticket-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|ticket.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|ticket.link_recode|]]"></a></td>
            <td style="text-align: center;"><a href="[[|ticket.link|]]">[[|ticket.number_of_vote|]]</a></td>
            <td>[[|ticket.user_name|]]</td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=ticket.price_before_tax=]])); $total_ticket_before_tax += [[=ticket.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $ticket_tax = [[=ticket.price=]]-[[=ticket.price_before_tax=]]; echo System::display_number(round($ticket_tax)); $total_ticket_tax += $ticket_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=ticket.price=]])); $total_ticket += [[=ticket.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=ticket.payment_price=]])); $total_ticket_payment += [[=ticket.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $ticket_remain = [[=ticket.price=]]-[[=ticket.payment_price=]]; echo System::display_number(round($ticket_remain)); $total_ticket_remain += $ticket_remain; ?></td>
            <td>[[|ticket.note|]]</td>
        </tr>
        <!--/LIST:ticket-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
        <?php if(sizeof([[=karaoke=]])>0){ 
            $total_karaoke = 0; $stt = 1; $total_karaoke_before_tax=0; $total_karaoke_tax=0; $total_karaoke_payment = 0; $total_karaoke_remain = 0;
        ?>
        <tr>
            <td colspan="17" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU KARaoke</td>
        </tr>
        <!--LIST:karaoke-->
        <tr class="compact_toggle">
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|karaoke.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|karaoke.link_recode|]]" target="_blank">[[|karaoke.reservation_id|]]</a></td>
            <td style="text-align: center;"><a href="[[|karaoke.link|]]" target="_blank">[[|karaoke.code|]]</a></td>
            <td>[[|karaoke.user_name|]]</td>
            <td style="text-align: center;">[[|karaoke.room|]]</td>
            <td style="text-align: right;"></td>
	        <td style="text-align: right;"></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=karaoke.price_before_tax=]])); $total_karaoke_before_tax += [[=karaoke.price_before_tax=]]; ?></td>
            <td style="text-align: right;"><?php $karaoke_tax = [[=karaoke.price=]]-[[=karaoke.price_before_tax=]]; echo System::display_number(round($karaoke_tax)); $total_karaoke_tax += $karaoke_tax; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=karaoke.price=]])); $total_karaoke += [[=karaoke.price=]]; ?></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=karaoke.payment_price=]])); $total_karaoke_payment += [[=karaoke.payment_price=]]; ?></td>
            <td style="text-align: right;"><?php $karaoke_remain = [[=karaoke.price=]]-[[=karaoke.payment_price=]]; echo System::display_number(round($karaoke_remain)); $total_karaoke_remain += $karaoke_remain; ?></td>
            <td>[[|karaoke.note|]]</td>
        </tr>
        <!--/LIST:karaoke-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
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
            <td colspan="6">[[.total.]]</td>
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
    var category = [[|category|]];
    var customer_group = [[|customer_group|]];
    var customer = [[|customer_js|]]; 
    console.log(customer);   
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery("#search").remove();
                jQuery("#report").battatech_excelexport({
                    containerid: "report"
                   , datatype: 'table'
                   , fileName: '[[.report_revenue_group_of_type.]]'
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