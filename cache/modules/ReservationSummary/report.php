<div>
    <?php echo $this->map['sort'];?>
</div>
<!-----------------RECORD-------------->

<table cellpadding="2" cellspacing="0" id="export" class="table1" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
    <tr class="title" align="center">
        <td><?php echo Portal::language('stt');?></td>
        <td><?php echo Portal::language('recode');?></td>
        <td><?php echo Portal::language('source');?></td>
        <td><?php echo Portal::language('Guest');?></td>
        <td><?php echo Portal::language('booking_code');?></td>
        <td><?php echo Portal::language('room_name');?></td>
        <td><?php echo Portal::language('arr_date');?></td>
        <td><?php echo Portal::language('dep_date');?></td>
        <td width="20px"><?php echo Portal::language('prs');?></td>
        <td><?php echo Portal::language('room_level');?></td>
        <td><?php echo Portal::language('extrabed');?></td>
        <td width="20px"><?php echo Portal::language('nts');?></td>
        <td><?php echo Portal::language('price');?></td>
        <td><?php echo Portal::language('total');?></td>
        <td><?php echo Portal::language('status');?></td>
        <td><?php echo Portal::language('confirm');?></td><!--oanh them trang thai xac nhan -->
        <td><?php echo Portal::language('user');?></td>
        <td><?php echo Portal::language('action_time');?></td>
        <td><?php echo Portal::language('user');?>/<?php echo Portal::language('time_cancel');?></td>
        <td><?php echo Portal::language('Cancel_note');?></td>
    </tr>
    <?php $stt_record = 0; $sum_recode = 0; $sum_adult = 0; $sum_night = 0; $sum_tong = 0;  $sum_exb = 0; $sum_room = 0; $sum_guest = 0; ?>
    <?php if(isset($this->map['count_res_room']) and is_array($this->map['count_res_room'])){ foreach($this->map['count_res_room'] as $key1=>&$item1){if($key1!='current'){$this->map['count_res_room']['current'] = &$item1;?>
        <?php $res_room_num = $this->map['count_res_room']['current']['num']; $res_room_id = $this->map['count_res_room']['current']['id']; ?> 
        <tr class="row">
            <td align="center"><?php $stt_record = $stt_record+1; echo $stt_record; ?></td>
            <td rowspan="<?php echo $res_room_num; ?>"><a href="?page=reservation&cmd=edit&id=<?php echo $res_room_id; ?>&portal=default" target="blank"><?php echo $res_room_id; ?></a></td>
            <?php $test_id = 0; $sum_recode = $sum_recode + 1; ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
                <?php
                    if($this->map['items']['current']['reservation_id']==$res_room_id){
                        if($test_id==0){
                            $test_id = 1;
                            ?>
                            <td align="left" rowspan="<?php echo $res_room_num; ?>"><?php echo $this->map['items']['current']['customer_name'];?></td>
                            <td align="left"><?php echo $this->map['items']['current']['first_name'];?> <?php echo $this->map['items']['current']['last_name'];?> <?php if($this->map['items']['current']['first_name'].$this->map['items']['current']['last_name']!=''){$sum_guest++;}?></td>
                            <td align="left"><?php echo $this->map['items']['current']['booking_code'];?></td>
                            <td align="center"><?php echo $this->map['items']['current']['room_name'];?> <?php if($this->map['items']['current']['room_name']!=''){$sum_room++;}?></td>
                            <td align="center"><?php echo $this->map['items']['current']['brief_arrival_time'];?></td>
                            <td align="center"><?php echo $this->map['items']['current']['brief_departure_time'];?></td>
                            <td align="center"><?php echo $this->map['items']['current']['adult'];?><?php $sum_adult = $sum_adult+$this->map['items']['current']['adult']; ?></td>
                            <td align="center"><?php echo $this->map['items']['current']['room_level'];?></td>
                            <!--oanh add  -->
                            <td align="center"><?php echo $this->map['items']['current']['extrabed'];?> <?php $sum_exb +=$this->map['items']['current']['extrabed']; ?></td>
                            <!-- end oanh -->
                            <td align="center"><?php echo $this->map['items']['current']['night'];?><?php $sum_night = $sum_night + $this->map['items']['current']['night'] + $this->map['items']['current']['day_used']; ?></td><!--KimTan: cộng thêm $this->map['items']['current']['day_used'] để đếm số đêm nếu dayuse-->
                            <td align="right" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['price']); if($this->map['items']['current']['foc']!='' || $this->map['items']['current']['foc_all']==1) echo'(FOC)';  ?></td>
                            <td align="right" class="change_numTr"><?php $tong = $this->map['items']['current']['price'] * ($this->map['items']['current']['night']+$this->map['items']['current']['day_used']); $sum_tong = $sum_tong+$tong; echo System::display_number($tong); if($this->map['items']['current']['foc']!='' || $this->map['items']['current']['foc_all']==1) echo'(FOC)'; ?></td>
                            <td align="center"><?php echo $this->map['items']['current']['status'];?></td>
                            <td align="center"><?php echo $this->map['items']['current']['confirm'];?></td><!--oanh them trang thai xac nhan -->
                            <td align="center"><?php echo $this->map['items']['current']['user_name'];?></td>
                            <td><?php echo $this->map['items']['current']['time'];?></td>
                            <td><?php echo $this->map['items']['current']['cancel_user_id'];?><br /><?php echo $this->map['items']['current']['time_cancel'];?></td>
                            <td><?php echo $this->map['items']['current']['cancel_note'];?></td>
                            <?php
                        }
                        else{
                            ?>
                            <tr bgcolor="white" class="row">
                                <td align="center"><?php $stt_record = $stt_record+1; echo $stt_record; ?></td>
                                <td align="left"><?php echo $this->map['items']['current']['first_name'];?> <?php echo $this->map['items']['current']['last_name'];?> <?php if($this->map['items']['current']['first_name'].$this->map['items']['current']['last_name']!=''){$sum_guest++;}?></td>
                                <td align="left"><?php echo $this->map['items']['current']['booking_code'];?></td>
                                <td align="center"><?php echo $this->map['items']['current']['room_name'];?> <?php if($this->map['items']['current']['room_name']!=''){$sum_room++;}?></td>
                                <td align="center"><?php echo $this->map['items']['current']['brief_arrival_time'];?></td>
                                <td align="center"><?php echo $this->map['items']['current']['brief_departure_time'];?></td>
                                <td align="center"><?php echo $this->map['items']['current']['adult'];?><?php $sum_adult = $sum_adult+$this->map['items']['current']['adult']; ?></td>
                                <td align="center"><?php echo $this->map['items']['current']['room_level'];?></td>
                                <td align="center"><?php echo $this->map['items']['current']['extrabed'];?> <?php $sum_exb +=$this->map['items']['current']['extrabed']; ?></td>
                                <td align="center"><?php echo $this->map['items']['current']['night'];?><?php $sum_night = $sum_night + $this->map['items']['current']['night'] + $this->map['items']['current']['day_used']; ?></td><!--KimTan: cộng thêm $this->map['items']['current']['day_used'] để đếm số đêm nếu dayuse-->
                                <td align="right" class="change_numTr" ><?php echo System::display_number($this->map['items']['current']['price']);if($this->map['items']['current']['foc']!='' || $this->map['items']['current']['foc_all']==1) echo'(FOC)';  ?></td>
                                <td align="right" class="change_numTr" ><?php $tong = $this->map['items']['current']['price'] * ($this->map['items']['current']['night']+$this->map['items']['current']['day_used']); $sum_tong = $sum_tong+$tong; echo System::display_number($tong);if($this->map['items']['current']['foc']!='' || $this->map['items']['current']['foc_all']==1) echo'(FOC)';  ?></td>
                                <td align="center"><?php echo $this->map['items']['current']['status'];?></td>
                                <td align="center"><?php echo $this->map['items']['current']['confirm'];?></td><!--oanh them trang thai xac nhan -->
                                <td align="center"><?php echo $this->map['items']['current']['user_name'];?></td>
                                <td><?php echo $this->map['items']['current']['time'];?></td>
                                <td><?php echo $this->map['items']['current']['cancel_user_id'];?><br /><?php echo $this->map['items']['current']['time_cancel'];?></td>
                                <td><?php echo $this->map['items']['current']['cancel_note'];?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
            <?php }}unset($this->map['items']['current']);} ?>
            </tr>
    <?php }}unset($this->map['count_res_room']['current']);} ?>
    <tr class="title" >
        <td><?php echo Portal::language('total');?></td>
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
        //System::debug($this->map['items']);
    }
?>