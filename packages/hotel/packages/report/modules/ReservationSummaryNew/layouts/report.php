<style>
    *{padding:2px; border:none; line-height: 12px; margin-bottom:2px}
    table.table1{ width: 100%; margin: 0px auto; border: 2px solid #999; }
    table.table1 tr.title{background: #ddd;}
    table.table1 tr.title td{padding: 10px; border: 1px solid #999;}
    table.table1 tr.row td{ border: 1px solid #ccc; font-size: 11px; }
    
</style>

<!-----------------RECORD-------------->
<table cellpadding="2" cellspacing="0" class="table1" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
    <tr class="title" align="center">
        <td>[[.stt.]]</td>
        <td>[[.recode.]]</td>
        <td>[[.source.]]</td>
        <td>[[.Guest.]]</td>
        <td>[[.booking_code.]]</td>
        <td>[[.arr_date.]]</td>
        <td>[[.dep_date.]]</td>
        <td width="20px">[[.prs.]]</td>
        <td>[[.room_level.]]</td>
        <td width="20px">[[.nts.]]</td>
        <td>[[.price.]]</td>
        <td>[[.total.]]</td>
        <td>[[.status.]]</td>
        <td>[[.user.]]</td>
        <td>[[.action_time.]]</td>
    </tr>
    <?php $stt_record = 0; $sum_recode = 0; $sum_adult = 0; $sum_night = 0; $sum_tong = 0; ?>
    
            
    <!--LIST:items-->
        <tr bgcolor="white" class="row">
            <td align="center"><?php $stt_record = $stt_record+1; echo $stt_record; ?></td>
            <td><a href="?page=reservation&cmd=edit&id=<?php echo [[=items.res_id=]]; ?>&portal=default" target="blank"><?php echo [[=items.res_id=]]; ?></a></td>
            <td align="center"><?php $stt_record = $stt_record+1; echo $stt_record; ?></td>
            <td align="left">[[|items.first_name|]] [[|items.last_name|]]</td>
            <td align="left">[[|items.booking_code|]]</td>
            <td align="center">[[|items.brief_arrival_time|]]</td>
            <td align="center">[[|items.brief_departure_time|]]</td>
            <td align="center">[[|items.adult|]]<?php $sum_adult = $sum_adult+[[=items.adult=]]; ?></td>
            <td align="center">[[|items.room_level|]]</td>
            <td align="center">[[|items.night|]]<?php $sum_night = $sum_night + [[=items.night=]] + [[=items.day_used=]]; ?></td><!--KimTan: cộng thêm [[=items.day_used=]] để đếm số đêm nếu dayuse-->
            <td align="right"><?php echo System::display_number([[=items.price=]]);if([[=items.foc=]]!='' || [[=items.foc_all=]]==1) echo'(FOC)';  ?></td>
            <td align="right"><?php $tong = [[=items.price=]] * ([[=items.night=]]+[[=items.day_used=]]); $sum_tong = $sum_tong+$tong; echo System::display_number($tong);if([[=items.foc=]]!='' || [[=items.foc_all=]]==1) echo'(FOC)';  ?></td>
            <td align="center">[[|items.status|]]</td>
            <td align="center">[[|items.user_name|]]</td>
            <td>[[|items.time|]]</td>
        </tr>                            
    <!--/LIST:items-->
    <tr class="title" >
        <td>[[.total.]]</td>
        <td><?php echo $sum_recode; ?></td>
        <td colspan="5"></td>
        <td align="center"><?php echo $sum_adult; ?></td>
        <td></td>
        <td align="center"><?php echo $sum_night; ?></td>
        <td></td>
        <td align="right"><?php echo System::display_number($sum_tong); ?></td>
        <td colspan="3"></td>
    </tr>
</table>
<?php 
    if(User::id()=='developer05'){
        //System::debug([[=items=]]);
    }
?>