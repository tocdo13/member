<style>
.simple-layout-middle{width:100%;}
</style>
<!-----------------RECORD-------------->
<div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></div>
<table id="myTable" class="tablesorter" cellpadding="5" cellspacing="0" border="1" bordercolor="#555555" style="width: 98%; margin: 0px auto;" id="export">
    <thead>
    <tr style="text-align: center; font-weight: bold; height: 30px; background: #EFEFEF;">
        <td><?php echo Portal::language('stt');?></td>
        <td><?php echo Portal::language('recode');?></td>
        <td><?php echo Portal::language('source');?></td>
        <td><?php echo Portal::language('booking_code');?></td>
        <td><?php echo Portal::language('room');?></td>
        <td><?php echo Portal::language('Guest');?></td>
        <td><?php echo Portal::language('arr_date');?></td>
        <td><?php echo Portal::language('dep_date');?></td>
        <td style="width: 20px;"><?php echo Portal::language('prs');?></td>
        <td><?php echo Portal::language('room_level');?></td>
        <td style="width: 20px;"><?php echo Portal::language('nts');?></td>
        <td><?php echo Portal::language('price');?></td>
        <td><?php echo Portal::language('total');?></td>
        <td><?php echo Portal::language('status');?></td>
        <td><?php echo Portal::language('user');?></td>
        <td><?php echo Portal::language('action_time');?></td>
    </tr>
    </thead>
    <tbody>
    <?php $stt=0; $total_night=0; ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr>
            <td><?php echo ++$stt; ?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><a href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['id'];?></a></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['customer_name'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['booking_code'];?></td>
            <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
                <?php $child_id=$this->map['items']['current']['child']['current']['id']; ?>
                <td><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['traveller_name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['adult'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['room_level'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td><?php $total_night+=$this->map['items']['current']['child']['current']['night']; ?>
                <td style="text-align: right;"><?php echo $this->map['items']['current']['child']['current']['price'];?></td>
                <td style="text-align: right;"><?php echo $this->map['items']['current']['child']['current']['total'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['status'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['user_name'];?></td>
                <?php break; ?>
            <?php }}unset($this->map['items']['current']['child']['current']);} ?>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['create_date'];?></td>
        </tr>
        <?php if($this->map['items']['current']['count_child']>1){ ?>
            <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current'] = &$item3;?>
                <?php if($child_id!=$this->map['items']['current']['child']['current']['id']){ ?>
                <tr>
                <td><?php echo ++$stt; ?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['traveller_name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['adult'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['room_level'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td><?php $total_night+=$this->map['items']['current']['child']['current']['night']; ?>
                <td style="text-align: right;"><?php echo $this->map['items']['current']['child']['current']['price'];?></td>
                <td style="text-align: right;"><?php echo $this->map['items']['current']['child']['current']['total'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['status'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['user_name'];?></td>
                </tr>
                <?php } ?>
            <?php }}unset($this->map['items']['current']['child']['current']);} ?>
        <?php } ?>
    <?php }}unset($this->map['items']['current']);} ?>
    </tbody>
    <tr style="text-align: center; font-weight: bold; height: 30px; background: #eeeeee;">
        <td><?php echo Portal::language('total');?></td>
        <td><?php echo $this->map['summary']['total_recode']; ?></td>
        <td colspan="6"></td>
        <td><?php echo $this->map['summary']['total_adult']; ?></td>
        <td></td>
        <td><?php echo $total_night; ?></td>
        <td></td>
        <td style="text-align: right;"><?php echo number_format($this->map['summary']['total_amount']); ?></td>
        <td colspan="3"></td>
    </tr>
</table>
<script>
jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>>
