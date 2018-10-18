<div>
    [[|sort|]]
</div>
<!-----------------RECORD-------------->

<table cellpadding="2" cellspacing="0" id="export" class="table1" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
    <tr class="title" align="center">
        <td>[[.stt.]]</td>
        <td>[[.recode.]]</td>
        <td>[[.source.]]</td>
        <td>[[.Guest.]]</td>
        <td>[[.booking_code.]]</td>
        <td>[[.room_name.]]</td>
        <td>[[.arr_date.]]</td>
        <td>[[.dep_date.]]</td>
        <td width="20px">[[.prs.]]</td>
        <td>[[.room_level.]]</td>
        <td>[[.extrabed.]]</td>
        <td width="20px">[[.nts.]]</td>
        <td>[[.price.]]</td>
        <td>[[.total.]]</td>
        <td>[[.status.]]</td>
        <td>[[.confirm.]]</td><!--oanh them trang thai xac nhan -->
        <td>[[.user.]]</td>
        <td>[[.action_time.]]</td>
        <td>[[.user.]]/[[.time_cancel.]]</td>
        <td>[[.Cancel_note.]]</td>
    </tr>
    <?php $stt_record = 0; $sum_recode = 0; $sum_adult = 0; $sum_night = 0; $sum_tong = 0;  $sum_exb = 0; $sum_room = 0; $sum_guest = 0; ?>
    <!--LIST:count_res_room-->
        <?php $res_room_num = [[=count_res_room.num=]]; $res_room_id = [[=count_res_room.id=]]; ?> 
        <tr class="row">
            <td align="center"><?php $stt_record = $stt_record+1; echo $stt_record; ?></td>
            <td rowspan="<?php echo $res_room_num; ?>"><a href="?page=reservation&cmd=edit&id=<?php echo $res_room_id; ?>&portal=default" target="blank"><?php echo $res_room_id; ?></a></td>
            <?php $test_id = 0; $sum_recode = $sum_recode + 1; ?>
            <!--LIST:items-->
                <?php
                    if([[=items.reservation_id=]]==$res_room_id){
                        if($test_id==0){
                            $test_id = 1;
                            ?>
                            <td align="left" rowspan="<?php echo $res_room_num; ?>">[[|items.customer_name|]]</td>
                            <td align="left">[[|items.first_name|]] [[|items.last_name|]] <?php if([[=items.first_name=]].[[=items.last_name=]]!=''){$sum_guest++;}?></td>
                            <td align="left">[[|items.booking_code|]]</td>
                            <td align="center">[[|items.room_name|]] <?php if([[=items.room_name=]]!=''){$sum_room++;}?></td>
                            <td align="center">[[|items.brief_arrival_time|]]</td>
                            <td align="center">[[|items.brief_departure_time|]]</td>
                            <td align="center">[[|items.adult|]]<?php $sum_adult = $sum_adult+[[=items.adult=]]; ?></td>
                            <td align="center">[[|items.room_level|]]</td>
                            <!--oanh add  -->
                            <td align="center">[[|items.extrabed|]] <?php $sum_exb +=[[=items.extrabed=]]; ?></td>
                            <!-- end oanh -->
                            <td align="center">[[|items.night|]]<?php $sum_night = $sum_night + [[=items.night=]] + [[=items.day_used=]]; ?></td><!--KimTan: cộng thêm [[=items.day_used=]] để đếm số đêm nếu dayuse-->
                            <td align="right" class="change_numTr"><?php echo System::display_number([[=items.price=]]); if([[=items.foc=]]!='' || [[=items.foc_all=]]==1) echo'(FOC)';  ?></td>
                            <td align="right" class="change_numTr"><?php $tong = [[=items.price=]] * ([[=items.night=]]+[[=items.day_used=]]); $sum_tong = $sum_tong+$tong; echo System::display_number($tong); if([[=items.foc=]]!='' || [[=items.foc_all=]]==1) echo'(FOC)'; ?></td>
                            <td align="center">[[|items.status|]]</td>
                            <td align="center">[[|items.confirm|]]</td><!--oanh them trang thai xac nhan -->
                            <td align="center">[[|items.user_name|]]</td>
                            <td>[[|items.time|]]</td>
                            <td>[[|items.cancel_user_id|]]<br />[[|items.time_cancel|]]</td>
                            <td>[[|items.cancel_note|]]</td>
                            <?php
                        }
                        else{
                            ?>
                            <tr bgcolor="white" class="row">
                                <td align="center"><?php $stt_record = $stt_record+1; echo $stt_record; ?></td>
                                <td align="left">[[|items.first_name|]] [[|items.last_name|]] <?php if([[=items.first_name=]].[[=items.last_name=]]!=''){$sum_guest++;}?></td>
                                <td align="left">[[|items.booking_code|]]</td>
                                <td align="center">[[|items.room_name|]] <?php if([[=items.room_name=]]!=''){$sum_room++;}?></td>
                                <td align="center">[[|items.brief_arrival_time|]]</td>
                                <td align="center">[[|items.brief_departure_time|]]</td>
                                <td align="center">[[|items.adult|]]<?php $sum_adult = $sum_adult+[[=items.adult=]]; ?></td>
                                <td align="center">[[|items.room_level|]]</td>
                                <td align="center">[[|items.extrabed|]] <?php $sum_exb +=[[=items.extrabed=]]; ?></td>
                                <td align="center">[[|items.night|]]<?php $sum_night = $sum_night + [[=items.night=]] + [[=items.day_used=]]; ?></td><!--KimTan: cộng thêm [[=items.day_used=]] để đếm số đêm nếu dayuse-->
                                <td align="right" class="change_numTr" ><?php echo System::display_number([[=items.price=]]);if([[=items.foc=]]!='' || [[=items.foc_all=]]==1) echo'(FOC)';  ?></td>
                                <td align="right" class="change_numTr" ><?php $tong = [[=items.price=]] * ([[=items.night=]]+[[=items.day_used=]]); $sum_tong = $sum_tong+$tong; echo System::display_number($tong);if([[=items.foc=]]!='' || [[=items.foc_all=]]==1) echo'(FOC)';  ?></td>
                                <td align="center">[[|items.status|]]</td>
                                <td align="center">[[|items.confirm|]]</td><!--oanh them trang thai xac nhan -->
                                <td align="center">[[|items.user_name|]]</td>
                                <td>[[|items.time|]]</td>
                                <td>[[|items.cancel_user_id|]]<br />[[|items.time_cancel|]]</td>
                                <td>[[|items.cancel_note|]]</td>
                            </tr>
                            <?php
                        }
                    }
                ?>
            <!--/LIST:items-->
            </tr>
    <!--/LIST:count_res_room-->
    <tr class="title" >
        <td>[[.total.]]</td>
        <td><?php echo $sum_recode; ?></td>
        <td></td>
        <td align="center"><?php echo $sum_guest; ?></td>
        <td></td>
        <td align="center"><?php echo $sum_room; ?></td>
        <td colspan="2"></td>
        <td align="center"><?php echo $sum_adult; ?></td>
        <td></td>
        <td align="center"><?php echo $sum_exb; ?></td>
        <td align="center"><?php echo $sum_night; ?></td>
        <td></td>
        <td align="right" class="change_numTr"><?php echo System::display_number($sum_tong); ?></td>
        <td></td> 
        <td align="center"><?php echo $stt_record; ?></td>       
        <td colspan="5"></td>
    </tr>
</table>
</table>
<script>
//trung add xuat excel
    jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
    jQuery('#button_n').remove();
    jQuery('#container_sort').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })
//trung add xuat excel
</script>
<?php 
    if(User::id()=='developer05'){
        //System::debug([[=items=]]);
    }
?>