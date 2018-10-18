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
                <td><input name="number_of_vote" type="text" id="number_of_vote" style="width: 170px;" /></td><!-- so phiue -->
                <td>[[.re_code.]]:</td>
                <td><input name="re_code" type="text" id="re_code" style="width: 40px;"  class="input_number"/></td><!-- ma mac dinh -->
                <td>[[.customer_name.]]:</td>
                <td><input name="customer_name" type="text" id="customer_name" onkeypress="Autocomplete();" oninput="delete_customer();"  autocomplete="off" style="width:215px;margin-bottom: 5px;" />
                    <input name="customer_id" type="text" id="customer_id" class="hidden" />
                </td>
                <td><input name="view_report" type="submit" value="[[.view_report.]]" class="button_sm" style="" onclick="return check_date();" /></td>
            </tr>
            <tr>
                <td>[[.category.]]:</td>
                <td><select name="list" id="list" style="width: 80px;"></select></td><!-- danh muc -->
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
            <th>[[.price.]]</th>
            <th>[[.note.]]</th>
        </tr>
        <?php $total_amount =0;  ?>
        <?php if(sizeof([[=reservation=]])>0){ 
            $total_reservation = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU PHÒNG</td>
        </tr>
        <!--LIST:reservation-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|reservation.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|reservation.link|]]" target="_blank">[[|reservation.number_of_vote|]]</a></td>
            <td>[[|reservation.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|reservation.link|]]" target="_blank">[[|reservation.number_of_vote|]]</a></td>
            <td>[[|reservation.user_name|]]</td>
            <td style="text-align: center;">[[|reservation.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=reservation.price=]])); $total_reservation += [[=reservation.price=]]; ?></td>
            <td>[[|reservation.note|]]</td>
        </tr>
        <!--/LIST:reservation-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_reservation)); $total_amount+= $total_reservation; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=list_extra_service=]])>0){ ?>
            <!--LIST:list_extra_service-->
            <tr>
                <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU [[|list_extra_service.id|]]</td>
            </tr>
            <?php $stt = 1; ?>
                <!--LIST:extra_service-->
                    <?php $arr = explode('_',[[=extra_service.id=]]); if($arr[0]==[[=list_extra_service.id=]]){ ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $stt++; ?></td>
                            <td style="text-align: center;">[[|extra_service.in_date|]]</td>
                            <td style="text-align: center;"><a href="[[|extra_service.link_recode|]]" target="_blank">[[|extra_service.reservation_id|]]</a></td>
                            <td>[[|extra_service.customer_name|]]</td>
                            <td style="text-align: center;"><a href="[[|extra_service.link|]]" target="_blank">[[|extra_service.number_of_vote|]]</a></td>
                            <td>[[|extra_service.user_name|]]</td>
                            <td style="text-align: center;">[[|extra_service.room|]]</td>
                            <td style="text-align: right;"><?php echo System::display_number(round([[=extra_service.price=]])) ?></td>
                            <td>[[|extra_service.note|]]</td>
                        </tr>
                    <?php } ?>
                <!--/LIST:extra_service-->
            <tr style=" font-weight: bold;">
                <td>...</td>
                <td colspan="6">[[.total.]]</td>
                <td style="text-align: right;"> <?php echo System::display_number(round([[=list_extra_service.total=]])); $total_amount+=[[=list_extra_service.total=]]; ?></td>
                <td></td>
            </tr>
            <!--/LIST:list_extra_service-->
        <?php } ?>
        <?php if(sizeof([[=minibar=]])>0){ 
            $total_minibar = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU MINIBAR</td>
        </tr>
        <!--LIST:minibar-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|minibar.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|minibar.link_recode|]]" target="_blank">[[|minibar.reservation_id|]]</a></td>
            <td>[[|minibar.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|minibar.link|]]" target="_blank">MN_[[|minibar.number_of_vote|]]</a></td>
            <td>[[|minibar.user_name|]]</td>
            <td style="text-align: center;">[[|minibar.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=minibar.price=]])); $total_minibar += [[=minibar.price=]]; ?></td>
            <td>[[|minibar.note|]]</td>
        </tr>
        <!--/LIST:minibar-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_minibar)); $total_amount+=$total_minibar; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=laundry=]])>0){ 
            $total_laundry = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU LAUNDRY</td>
        </tr>
        <!--LIST:laundry-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|laundry.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|laundry.link_recode|]]" target="_blank">[[|laundry.reservation_id|]]</a></td>
            <td>[[|laundry.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|laundry.link|]]" target="_blank">LD_[[|laundry.number_of_vote|]]</a></td>
            <td>[[|laundry.user_name|]]</td>
            <td style="text-align: center;">[[|laundry.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=laundry.price=]])); $total_laundry += [[=laundry.price=]]; ?></td>
            <td>[[|laundry.note|]]</td>
        </tr>
        <!--/LIST:laundry-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_laundry)); $total_amount+=$total_laundry; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=equip=]])>0){ 
            $total_equip = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5; font-weight: bold;">DOANH THU HÓA ĐƠN ĐỀN BÙ</td>
        </tr>
        <!--LIST:equip-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|equip.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|equip.link_recode|]]" target="_blank">[[|equip.reservation_id|]]</a></td>
            <td>[[|equip.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|equip.link|]]" target="_blank">[[|equip.number_of_vote|]]</a></td>
            <td>[[|equip.user_name|]]</td>
            <td style="text-align: center;">[[|equip.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=equip.price=]])); $total_equip += [[=equip.price=]]; ?></td>
            <td>[[|equip.note|]]</td>
        </tr>
        <!--/LIST:equip-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_equip)); $total_amount+= $total_equip; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=bar=]])>0){ 
            $total_bar = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU NHÀ HÀNG</td>
        </tr>
        <!--LIST:bar-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|bar.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|bar.link_recode|]]" target="_blank">[[|bar.reservation_id|]]</a></td>
            <td style="text-align: center;"><?php echo ([[=bar.customer_name_r=]]!='')?[[=bar.customer_name_r=]]:[[=bar.customer_name_b=]]?></td>
            <td style="text-align: center;"><a href="[[|bar.link|]]" target="_blank">[[|bar.code|]]</a></td>
            <td>[[|bar.user_name|]]</td>
            <td style="text-align: center;">[[|bar.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=bar.price=]])); $total_bar += [[=bar.price=]]; ?></td>
            <td>[[|bar.note|]]</td>
        </tr>
        <!--/LIST:bar-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_bar)); $total_amount+= $total_bar; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=spa=]])>0){ 
            $total_spa = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU SPA</td>
        </tr>
        <!--LIST:spa-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|spa.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|spa.link_recode|]]" target="_blank">[[|spa.reservation_id|]]</a></td>
            <td style="text-align: center;"><a href="[[|spa.link|]]" target="_blank">[[|spa.massage_reservation_room_id|]]</a></td>
            <td>[[|spa.user_name|]]</td>
            <td style="text-align: center;">[[|spa.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=spa.price=]])); $total_spa += [[=spa.price=]]; ?></td>
            <td>[[|spa.note|]]</td>
        </tr>
        <!--/LIST:spa-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_spa)); $total_amount+= $total_spa; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=party=]])>0){ 
            $total_party = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU Đặt Tiệc</td>
        </tr>
        <!--LIST:party-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|party.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|party.link_recode|]]" target="_blank"></a></td>
            <td style="text-align: center;"><a href="[[|party.link|]]" target="_blank">[[|party.party_reservation_id|]]</a></td>
            <td>[[|party.user_name|]]</td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=party.price=]])); $total_party += [[=party.price=]]; ?></td>
            <td>[[|party.note|]]</td>
        </tr>
        <!--/LIST:party-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_party)); $total_amount+= $total_party; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=vend=]])>0){ 
            $total_vend = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN HÀNG</td>
        </tr>
        <!--LIST:vend-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|vend.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|vend.link_recode|]]" target="_blank">[[|vend.reservation_id|]]</a></td>
            <td>[[|vend.customer_name|]]</td>
            <td style="text-align: center;"><a href="[[|vend.link|]]" target="_blank">[[|vend.ve_reservation_id|]]</a></td>
            <td>[[|vend.user_name|]]</td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=vend.price=]])); $total_vend += [[=vend.price=]]; ?></td>
            <td>[[|vend.note|]]</td>
        </tr>
        <!--/LIST:vend-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_vend)); $total_amount+= $total_vend; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=ticket=]])>0){ 
            $total_ticket = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="10" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU BÁN Vé</td>
        </tr>
        <!--LIST:ticket-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|ticket.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|ticket.link_recode|]]"></a></td>
            <td style="text-align: center;"><a href="[[|ticket.link|]]">[[|ticket.number_of_vote|]]</a></td>
            <td>[[|ticket.user_name|]]</td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=ticket.price=]])); $total_ticket += [[=ticket.price=]]; ?></td>
            <td>[[|ticket.note|]]</td>
        </tr>
        <!--/LIST:ticket-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_ticket)); $total_amount+= $total_ticket; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <?php if(sizeof([[=karaoke=]])>0){ 
            $total_karaoke = 0; $stt = 1;
        ?>
        <tr>
            <td colspan="9" style="text-transform: uppercase; background: #f5f5f5;font-weight: bold;">DOANH THU KARaoke</td>
        </tr>
        <!--LIST:karaoke-->
        <tr>
            <td style="text-align: center;"><?php echo $stt++; ?></td>
            <td style="text-align: center;">[[|karaoke.in_date|]]</td>
            <td style="text-align: center;"><a href="[[|karaoke.link_recode|]]" target="_blank">[[|karaoke.reservation_id|]]</a></td>
            <td style="text-align: center;"><a href="[[|karaoke.link|]]" target="_blank">[[|karaoke.code|]]</a></td>
            <td>[[|karaoke.user_name|]]</td>
            <td style="text-align: center;">[[|karaoke.room|]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round([[=karaoke.price=]])); $total_karaoke += [[=karaoke.price=]]; ?></td>
            <td>[[|karaoke.note|]]</td>
        </tr>
        <!--/LIST:karaoke-->
        <tr style=" font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total.]]</td>
            <td style="text-align: right;"><?php echo System::display_number(round($total_karaoke)); $total_amount+= $total_karaoke; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr style="background: #eeeeee; font-weight: bold;">
            <td>...</td>
            <td colspan="6">[[.total_amount.]]</td>
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