<form name="MassageDailySummaryReportForm" method="post">
<div class="massage-daily-bound" style="margin:5px;">
    <div class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px; margin-bottom: 20px; font-weight: normal !important;"><?php echo Portal::language('daily_summary');?></div>
    <div class="body">
        <div class="select-date">
            <?php echo Portal::language('date');?>: <input  name="date" id="date" class="date" style="height: 24px; width: 100px; margin-right: 5px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"> <?php echo Portal::language('staff');?>: <select  name="staff_id" id="staff_id" style="height: 24px; width: 150px; margin-right: 5px;"><?php
					if(isset($this->map['staff_id_list']))
					{
						foreach($this->map['staff_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('staff_id',isset($this->map['staff_id'])?$this->map['staff_id']:''))
                    echo "<script>$('staff_id').value = \"".addslashes(URL::get('staff_id',isset($this->map['staff_id'])?$this->map['staff_id']:''))."\";</script>";
                    ?>
	</select><input type="submit" value="<?php echo Portal::language('search');?>" style="height: 24px; width: 100px; margin-right: 5px;"/>
            <?php
                if(Url::get('package_id'))
                {
                    ?>
                    <input style="height: 24px;" type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','package_id'=>Url::get('package_id'),'rr_id'=>Url::get('reservation_room_id')));?>&date='+$('date').value+'&room_ids='+$('room_ids').value;"/>
                    <?php 
                } 
                else
                {
                    ?>
                    <input style="height: 24px;" type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add'));?>&date='+$('date').value+'&room_ids='+$('room_ids').value;"/>
                    <?php 
                }
            ?>
            
            <input  name="room_ids" id="room_ids"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('room_ids'));?>">
        </div>
		<div class="content"><br />
            <table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="width: 1200px;">
                <tr bgcolor="#FFCC00">
                    <th rowspan="2" align="center"><?php echo Portal::language('room');?></th>
                    <th align="center" style="border-bottom:1px solid #990000;padding-top:2px;padding-bottom:2px;"><?php echo Portal::language('hour');?></th>
                </tr>
                <tr>
                    <td width="<?php echo $this->map['aggregate_duration'];?>" align="center">
                    	<?php
                    		$j=0;
                    		for($i=0;$i<=$this->map['aggregate_duration'];$i++){
                    			if($i%60==0 and $i>0){
                    				echo '<span class="time-block" style=\'width:44px;\'>'.(date('H:i',$this->map['begin_time']+$j)).'</span>';
                    				$j=$j+60*60;
                    			}
                    		}
                    	?>
                    </td>
                </tr>
                <tr valign="top">
                    <td class="room-column">
                    	<ul>
                    	<?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key1=>&$item1){if($key1!='current'){$this->map['rooms']['current'] = &$item1;?>
                    		<li style="width: 120px; height: 24px; cursor:pointer; <?php echo (strtoupper($this->map['rooms']['current']['category'])=='VIP')?'background-color:#FF0000;color:#FFFFFF;':'';?>" title="<?php echo Portal::language('reservation');?>"><?php echo $this->map['rooms']['current']['name'];?> <input type="checkbox" id="room_<?php echo $this->map['rooms']['current']['id'];?>" value="<?php echo $this->map['rooms']['current']['id'];?>" onclick="updateRoomIds();" class="room-checkbox"/></li>
                    	<?php }}unset($this->map['rooms']['current']);} ?>
                    	</ul>
                    </td>
                    <td class="room-column item">
                    	<ul>
                    	<?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key2=>&$item2){if($key2!='current'){$this->map['rooms']['current'] = &$item2;?>
                    		<li>
                    			<?php $i = 0;?>
                    			<?php if(isset($this->map['rooms']['current']['reservation_room']) and is_array($this->map['rooms']['current']['reservation_room'])){ foreach($this->map['rooms']['current']['reservation_room'] as $key3=>&$item3){if($key3!='current'){$this->map['rooms']['current']['reservation_room']['current'] = &$item3;?>
                    			<!--daund chinh lech cot<div title="<?php echo $this->map['rooms']['current']['reservation_room']['current']['tooltip'];?>" class="reservation <?php echo $this->map['rooms']['current']['reservation_room']['current']['status'];?>" style="z-index:<?php //echo $i++;?> ;position:absolute;width:<?php //echo (($this->map['rooms']['current']['reservation_room']['current']['time_out']-$this->map['rooms']['current']['reservation_room']['current']['time_in'])/60)-8;?>px;left:<?php //echo (($this->map['rooms']['current']['reservation_room']['current']['time_in']-$this->map['begin_time'])/60)-10;?>px;top:0px;">-->
                                <!--Start daund edit-->
                                <?php 
                                    $number_hour = ($this->map['rooms']['current']['reservation_room']['current']['time_out']-$this->map['rooms']['current']['reservation_room']['current']['time_in'])/3600;
                                    $width = $number_hour * 44;
                                    $past_hour = ($this->map['rooms']['current']['reservation_room']['current']['time_in']-$this->map['begin_time'])/3600;
                                    $left = ($past_hour*44)+$past_hour;
                                ?>
                                <div title="<?php echo $this->map['rooms']['current']['reservation_room']['current']['tooltip'];?>" id="reservation_<?php echo $this->map['rooms']['current']['reservation_room']['current']['status'];?>" class="reservation <?php echo $this->map['rooms']['current']['reservation_room']['current']['status'];?>" style="position: absolute; z-index: <?php echo $i++;?>;width:<?php echo $width;?>px;left:<?php echo $left;?>px; top: 0px;font-size: 7px; text-align: center; font-size: 3px; overflow: hidden;">
                                <!--End daund edit-->
                                <?php
                                    if($this->map['rooms']['current']['reservation_room']['current']['package_id']!='')
                                    {
                                        ?>
                                        <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['rooms']['current']['reservation_room']['current']['reservation_room_id'],'package_id'=>$this->map['rooms']['current']['reservation_room']['current']['package_id'],'rr_id'=>$this->map['rooms']['current']['reservation_room']['current']['ht_reservation_room_id'],'room_id'=>$this->map['rooms']['current']['reservation_room']['current']['room_id']));?>" style="margin-right: 2px;" title="<?php echo Portal::language('edit');?>"><i class="fa fa-pencil w3-text-red" style="font-size: 18px;"></i></a>
                                        [<a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'invoice','id'=>$this->map['rooms']['current']['reservation_room']['current']['reservation_room_id'],'package_id'=>$this->map['rooms']['current']['reservation_room']['current']['package_id'],'rr_id'=>$this->map['rooms']['current']['reservation_room']['current']['ht_reservation_room_id']));?>" title="<?php echo Portal::language('view_invoice');?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>]
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['rooms']['current']['reservation_room']['current']['reservation_room_id'],'room_id'=>$this->map['rooms']['current']['reservation_room']['current']['room_id']));?>" style="margin-right: 2px;" title="<?php echo Portal::language('edit');?>"><i class="fa fa-pencil w3-text-red" style="font-size: 18px;"></i></a>
                                        [<a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'invoice','id'=>$this->map['rooms']['current']['reservation_room']['current']['reservation_room_id']));?>" title="<?php echo Portal::language('view_invoice');?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a>]
                                        <?php 
                                    }
                                ?>
                                
                                </div>
                    			<?php }}unset($this->map['rooms']['current']['reservation_room']['current']);} ?>
                    		</li>	
                    	<?php }}unset($this->map['rooms']['current']);} ?>
                    	</ul>
                    </td>
                </tr>
			</table>
		</div>
	</div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
	function updateRoomIds(){
	    var room_ids = '';
		jQuery(".room-checkbox").each(function (){
		    if(jQuery(this).attr('checked')=='checked'){
                room_ids += ((room_ids!='')?',':'')+jQuery(this).val();
                
			}
		});
        jQuery('#room_ids').val(room_ids);
	}
	jQuery("#date").datepicker();
</script>
