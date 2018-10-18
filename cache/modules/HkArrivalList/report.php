<style>
    
</style>
<table style="width: 99%; margin: 5px auto;">
    <tr>
        <td>
            <b><?php echo HOTEL_NAME; ?></b><br />
            <b><?php echo HOTEL_ADDRESS; ?></b>
        </td>
        <td style="text-align: right;">
            <b><?php echo Portal::language('template_code');?></b><br />
            <b>Date: <?php echo date('d/m/Y H:i');?></b><br />
            <b>Printer: <?php $user_data = Session::get('user_data'); echo $user_data['full_name']; ?></b>
        </td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: center;">
            <p><h1 style="text-transform: uppercase;"><?php echo Portal::language('arrival_customer_list');?></h1></p>
            <span><?php echo Portal::language('day');?> <?php echo $this->map['date'];?></span>
        </th>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            <form name="ReportArrivalList" method="POST">
                <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                    <?php echo Portal::language('hotel');?>: <select  name="portal_id" id="portal_id"><?php
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
	</select>
                <?php }?>
                <?php echo Portal::language('date');?>: <input  name="date" id="date"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>">
            	<?php echo Portal::language('status');?>: <select  name="status" id="status"><?php
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
	</select>
            	<input type="submit" name="do_search" value="<?php echo Portal::language('view_report');?>"/>
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table style="width: 100%;" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
                <tr style="text-align: center;">
                    <th><?php echo Portal::language('stt');?></th>
                    <th><?php echo Portal::language('reservation_room_code');?></th>
                    <th><?php echo Portal::language('tour');?>, <?php echo Portal::language('company');?></th>
                    <th><?php echo Portal::language('room');?></th>
                    <th><?php echo Portal::language('room_level');?></th>
                    <th><?php echo Portal::language('house_status');?></th>
                    <th><?php echo Portal::language('note');?></th>
                    <th><?php echo Portal::language('extra_bed');?></th>
                    <th><?php echo Portal::language('baby_cot');?></th>
                    <th><?php echo Portal::language('guest_name');?></th>
                    <th><?php echo Portal::language('countries');?></th>
                    <th><?php echo Portal::language('A/c');?></th>
                    <th><?php echo Portal::language('arrival_date');?></th>
                    <th><?php echo Portal::language('departure_date');?></th>
                    <th><?php echo Portal::language('night');?></th>
                </tr>
                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                    <tr>
                        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['stt'];?></td>
                        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><a href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['recode'];?>"><?php echo $this->map['items']['current']['recode'];?></a></td>
                        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['customer_name'];?></td>
                        <?php $items_child = ''; ?>
                        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
                            <?php $items_child = $this->map['items']['current']['child']['current']['id']; ?>
                            <?php if($this->map['items']['current']['child']['current']['count_child']==0){ $this->map['items']['current']['child']['current']['count_child']=1; } ?>
                            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
                            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['room_level_name'];?></td>
                            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['house_status'];?></td>
                            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['note'];?></td>
                            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php if($this->map['items']['current']['child']['current']['extrabed']!=0){echo 'yes';} ?></td>
                            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php if($this->map['items']['current']['child']['current']['baby_cot']!=0){echo 'yes';} ?></td>
                            <?php if(sizeof($this->map['items']['current']['child']['current']['child_child'])==0){ ?>
                                <td></td>
                                <td></td>
                                <td><?php //echo ($this->map['items']['current']['child']['current']['count_traveler']>0)?$this->map['items']['current']['child']['current']['count_traveler']:$this->map['items']['current']['child']['current']['adult'];?><?php echo $this->map['items']['current']['child']['current']['adult'];?>/<?php echo $this->map['items']['current']['child']['current']['child'];?></td>
                                <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                                <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                                <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                                </tr>
                            <?php }else{ ?>
                                <?php $items_child_childchild = '';  ?>
                                <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item3;?>
                                    <?php $items_child_childchild = $this->map['items']['current']['child']['current']['child_child']['current']['id']; ?>
                                    <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['traveller_name'];?></td>
                                    <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['country_name'];?></td>
                                    <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php //echo ($this->map['items']['current']['child']['current']['count_traveler']>0)?$this->map['items']['current']['child']['current']['count_traveler']:$this->map['items']['current']['child']['current']['adult'];?><?php echo $this->map['items']['current']['child']['current']['adult'];?>/<?php echo $this->map['items']['current']['child']['current']['child'];?></td>
                                    <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                                    <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                                    <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                                    </tr>
                                    <?php break; ?>
                                <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
                                <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item4;?>
                                    <?php if($items_child_childchild != $this->map['items']['current']['child']['current']['child_child']['current']['id']){ ?>
                                    <tr>
                                    <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['traveller_name'];?></td>
                                    <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['country_name'];?></td>
                                    </tr>
                                    <?php } ?>
                                <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
                            <?php } ?>
                            
                            <?php break; ?>
                        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
                        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current']['child']['current'] = &$item5;?>
                            <?php if($items_child != $this->map['items']['current']['child']['current']['id']){ ?>
                            <tr>
                            <?php if($this->map['items']['current']['child']['current']['count_child']==0){ $this->map['items']['current']['child']['current']['count_child']=1; } ?>
                                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
                                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['room_level_name'];?></td>
                                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['house_status'];?></td>
                                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['note'];?></td>
                                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php if($this->map['items']['current']['child']['current']['extrabed']!=0){echo 'yes';} ?></td>
                                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php if($this->map['items']['current']['child']['current']['baby_cot']!=0){echo 'yes';} ?></td>
                            <?php if(sizeof($this->map['items']['current']['child']['current']['child_child'])==0){ ?>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $this->map['items']['current']['child']['current']['adult'];?>/<?php echo $this->map['items']['current']['child']['current']['child'];?></td>
                                    <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                                    <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                                    <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                                </tr>
                            <?php }else{ ?>
                                <?php $items_child_childchild = '';  ?>
                                <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key6=>&$item6){if($key6!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item6;?>
                                    <?php $items_child_childchild = $this->map['items']['current']['child']['current']['child_child']['current']['id']; ?>
                                        <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['traveller_name'];?></td>
                                        <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['country_name'];?></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['adult'];?>/<?php echo $this->map['items']['current']['child']['current']['child'];?></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                                        <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                                    </tr>
                                    <?php break; ?>
                                <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
                                
                                <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key7=>&$item7){if($key7!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item7;?>
                                    <?php if($items_child_childchild != $this->map['items']['current']['child']['current']['child_child']['current']['id']){ ?>
                                    <tr>
                                        <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['traveller_name'];?></td>
                                        <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['country_name'];?></td>
                                    </tr>
                                    <?php } ?>
                                <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
                            <?php } ?>
                            <?php } ?>
                        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
                <?php }}unset($this->map['items']['current']);} ?>
            <tr>
                <td style="text-align: right;" colspan="3"><strong><?php echo Portal::language('total');?>: </strong></td>
                <td style="text-align: center;"><strong><?php echo $this->map['total_room'];?></strong></td>
                <td colspan="7"></td>
                <td style="text-align: center;"><strong><?php echo $this->map['total_adult'];?>/<?php echo $this->map['total_child'];?></strong></td>
                <td colspan="2"></td>
                <td style="text-align: center;"><strong><?php echo $this->map['total_night'];?></strong></td>
            </tr>
            </table>
        </td>
    </tr>
</table>
<script>
    jQuery("#date").datepicker();
    full_screen();
</script>