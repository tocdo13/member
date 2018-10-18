<form name="BanquetRoomMapReportForm" method="post">
<div class="massage-daily-bound" style="margin:5px;">
	<div class="" style="text-transform: uppercase;"><?php echo Portal::language('daily_summary');?> 
    <input  name="date" id="date" class="date" onchange="BanquetRoomMapReportForm.submit()" style="height: 29px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>">
    <input class="w3-btn w3-cyan w3-text-white" type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='?page=banquet_reservation&cmd='+1+'&portal=<?php echo PORTAL_ID;?>';"/>
    </div>
	<div class="body">
		<div class="select-date">
			
			<!--trung coment link layot select dat tiec <input type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php //echo Url::build('banquet_reservation',array('cmd'=>'add'));?>&checkin_date='+$('date').value+'&room_ids='+$('room_ids').value;">-->
			
            <input  name="room_ids" id="room_ids" type ="hidden" value="<?php echo String::html_normalize(URL::get('room_ids'));?>">
		</div>
		<div class="content"><br />
			<table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
			  <tr bgcolor="#FFCC00" style="text-transform: uppercase;">
			    <th rowspan="2" style="text-align: center;"><?php echo Portal::language('banquet_room');?></th>
			    <th align="center" style="border-bottom:1px solid #990000;padding-top:2px;padding-bottom:2px;"><?php echo Portal::language('hour');?></th>
		      </tr>
			  <tr>
			    <td width="<?php echo $this->map['aggregate_duration'];?>" align="center">
					<?php
						$j=0;
						for($i=0;$i<=$this->map['aggregate_duration'];$i++){
							if($i%60==0 and $i>0){
								echo '<span class="time-block" style=\'width:54px;\'>'.(date('H:i',$this->map['begin_time']+$j)).'</span>';
								$j=$j+60*60;
							}
						}
					?></td>
		      </tr>
			  <tr valign="top">
				<td class="room-column">
					<ul>
                    <?php if(isset($this->map['floors']) and is_array($this->map['floors'])){ foreach($this->map['floors'] as $key1=>&$item1){if($key1!='current'){$this->map['floors']['current'] = &$item1;?>
                    <div class="banquet-floor-name"><?php echo $this->map['floors']['current']['name'];?></div>
					<?php if(isset($this->map['floors']['current']['rooms']) and is_array($this->map['floors']['current']['rooms'])){ foreach($this->map['floors']['current']['rooms'] as $key2=>&$item2){if($key2!='current'){$this->map['floors']['current']['rooms']['current'] = &$item2;?>
						<li style="cursor:pointer;" title="<?php echo Portal::language('reservation');?>"><label style="height: 24px;" for="room_<?php echo $this->map['floors']['current']['rooms']['current']['id'];?>"><?php echo $this->map['floors']['current']['rooms']['current']['name'];?></label><input type="checkbox" id="room_<?php echo $this->map['floors']['current']['rooms']['current']['id'];?>" value="<?php echo $this->map['floors']['current']['rooms']['current']['id'];?>" onclick="updateRoomIds();" class="room-checkbox" style="display: none; height: 24px;" /></li>
					<?php }}unset($this->map['floors']['current']['rooms']['current']);} ?>
                    <?php }}unset($this->map['floors']['current']);} ?>
					</ul></td>
				<td class="room-column item">
					<ul>
                    <?php if(isset($this->map['floors']) and is_array($this->map['floors'])){ foreach($this->map['floors'] as $key3=>&$item3){if($key3!='current'){$this->map['floors']['current'] = &$item3;?>
                    <div class="banquet-floor-name">&nbsp;</div>
					<?php if(isset($this->map['floors']['current']['rooms']) and is_array($this->map['floors']['current']['rooms'])){ foreach($this->map['floors']['current']['rooms'] as $key4=>&$item4){if($key4!='current'){$this->map['floors']['current']['rooms']['current'] = &$item4;?>
						<li>
							<?php $i = 0;?>
							<?php if(isset($this->map['floors']['current']['rooms']['current']['reservation_room']) and is_array($this->map['floors']['current']['rooms']['current']['reservation_room'])){ foreach($this->map['floors']['current']['rooms']['current']['reservation_room'] as $key5=>&$item5){if($key5!='current'){$this->map['floors']['current']['rooms']['current']['reservation_room']['current'] = &$item5;?>
							<div title="<?php echo $this->map['floors']['current']['rooms']['current']['reservation_room']['current']['tooltip'];?>" class="reservation <?php echo $this->map['floors']['current']['rooms']['current']['reservation_room']['current']['status'];?>" style="z-index:<?php echo $i++;?> ;position:absolute;width:<?php echo (($this->map['floors']['current']['rooms']['current']['reservation_room']['current']['time_out']-$this->map['floors']['current']['rooms']['current']['reservation_room']['current']['time_in'])*(54/60)/60+($this->map['floors']['current']['rooms']['current']['reservation_room']['current']['time_out']-$this->map['floors']['current']['rooms']['current']['reservation_room']['current']['time_in'])/3600);?>px;left:<?php echo (($this->map['floors']['current']['rooms']['current']['reservation_room']['current']['time_in']-$this->map['begin_time'])*(54/60)/60)+($this->map['floors']['current']['rooms']['current']['reservation_room']['current']['time_in']-$this->map['begin_time'])/3600;?>px;top: 0px; font-size: 7px; text-align: center; height: 24px; overflow: hidden;">
	                        <span style="width:80px; height: 24px; overflow:hidden;"><?php echo $this->map['floors']['current']['rooms']['current']['reservation_room']['current']['id'];?></span>
                            <a target="_blank" href="<?php echo Url::build('banquet_reservation',array('action'=>'edit','id'=>$this->map['floors']['current']['rooms']['current']['reservation_room']['current']['party_reservation_id'],'cmd'=>$this->map['floors']['current']['rooms']['current']['reservation_room']['current']['party_type_id']));?>"><?php echo Portal::language('edit');?></a> [<a target="_blank" href="<?php echo Url::build('banquet_reservation',array('cmd'=>'detail','id'=>$this->map['floors']['current']['rooms']['current']['reservation_room']['current']['party_reservation_id']));?>"><img src="packages/core/skins/default/images/cmd_Tim.gif" width="12"></a>]
                            </div>
							<?php }}unset($this->map['floors']['current']['rooms']['current']['reservation_room']['current']);} ?>
						</li>	
					<?php }}unset($this->map['floors']['current']['rooms']['current']);} ?>
                    <?php }}unset($this->map['floors']['current']);} ?>
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
		$('room_ids').value = '';
		jQuery(".room-checkbox").each(function (){
			if(jQuery(this).attr('checked')==true){
				$('room_ids').value += (($('room_ids').value!='')?',':'')+jQuery(this).val();
			}
		});
	}
	jQuery("#date").datepicker();
</script>