<style>
.simple-layout-middle{width:100%;}
</style>
<form name="BanquetRoomMapMonthReportForm" method="post">
<div class="massage-daily-bound" style="margin:5px;">
	<div class="title"><?php echo Portal::language('monthy_summary');?>: <?php echo Url::get('month')?Url::get('month').'/'.Url::get('year'):date('m/Y');?></div>
	<div class="body">
		<div class="select-date">
			<!-- <?php echo Portal::language('month');?>: <select  name="month" id="month" onchange="BanquetRoomMapMonthReportForm.submit();"><?php
					if(isset($this->map['month_list']))
					{
						foreach($this->map['month_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('month',isset($this->map['month'])?$this->map['month']:''))
                    echo "<script>$('month').value = \"".addslashes(URL::get('month',isset($this->map['month'])?$this->map['month']:''))."\";</script>";
                    ?>
	</select> <?php echo Portal::language('year');?> <select  name="year" id="year" onchange="BanquetRoomMapMonthReportForm.submit();"><?php
					if(isset($this->map['year_list']))
					{
						foreach($this->map['year_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('year',isset($this->map['year'])?$this->map['year']:''))
                    echo "<script>$('year').value = \"".addslashes(URL::get('year',isset($this->map['year'])?$this->map['year']:''))."\";</script>";
                    ?>
	</select>-->
            <!-- trung:them nut search de load lai trang -->
            <?php echo Portal::language('month');?>: <select  name="month" id="month" ><?php
					if(isset($this->map['month_list']))
					{
						foreach($this->map['month_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('month',isset($this->map['month'])?$this->map['month']:''))
                    echo "<script>$('month').value = \"".addslashes(URL::get('month',isset($this->map['month'])?$this->map['month']:''))."\";</script>";
                    ?>
	</select> <?php echo Portal::language('year');?> <select  name="year" id="year" ><?php
					if(isset($this->map['year_list']))
					{
						foreach($this->map['year_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('year',isset($this->map['year'])?$this->map['year']:''))
                    echo "<script>$('year').value = \"".addslashes(URL::get('year',isset($this->map['year'])?$this->map['year']:''))."\";</script>";
                    ?>
	</select>
            <!-- trung: end them nut search de load lai trang -->
            <input name='btn_sub' type="submit" id="btn_sub" value="search"/>
			<!--<input type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php echo Url::build('banquet_order',array('cmd'=>'add'));?>&checkin_date='+$('date').value+'&room_ids='+$('room_ids').value;"/>-->
			<input  name="room_ids" id="room_ids"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('room_ids'));?>">
		</div>
		<div class="content"><br />
			<table border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" width="100%">
			  <tr bgcolor="#FFCC00">
			    <th rowspan="3" width="1%" nowrap="nowrap" style="text-align: center;"><?php echo Portal::language('banquet_room');?></th>
			    <th align="center" style="border-bottom:1px solid #990000;padding-top:2px;padding-bottom:2px;"><?php echo Portal::language('day');?></th>
		      </tr>
              <tr>
              </tr>
			  <tr>
			    <td width="<?php echo $this->map['aggregate_duration'];?>" align="center">
                	<table cellpadding="0" bgcolor="#F1F1F1" cellspacing="0" width="100%" border="1" bordercolor="#666666" style="border-collapse:collapse;">
                    <tr>
					<?php if(isset($this->map['days']) and is_array($this->map['days'])){ foreach($this->map['days'] as $key1=>&$item1){if($key1!='current'){$this->map['days']['current'] = &$item1;?>
						<td colspan="2" align="center" width="36px"><strong><?php echo $this->map['days']['current']['id'];?></strong></td>
					<?php }}unset($this->map['days']['current']);} ?>	
                    </tr>
                    <tr>
                        <?php if(isset($this->map['day_status']) and is_array($this->map['day_status'])){ foreach($this->map['day_status'] as $key2=>&$item2){if($key2!='current'){$this->map['day_status']['current'] = &$item2;?>
						<td colspan="2" align="center" width="36px"><strong style="font-size: 10px;"><?php echo $this->map['day_status']['current']['time2'];?></strong></td>
					<?php }}unset($this->map['day_status']['current']);} ?>
                    </tr>
                    <tr>
					<?php if(isset($this->map['room_status']) and is_array($this->map['room_status'])){ foreach($this->map['room_status'] as $key3=>&$item3){if($key3!='current'){$this->map['room_status']['current'] = &$item3;?>
						<td align="center" width="1%"><?php echo  (date('H',$this->map['room_status']['current']['time'])=='00')?'S':'C';?></td>
					<?php }}unset($this->map['room_status']['current']);} ?>	
                    </tr>
                    </table>
                </td>
		      </tr>
			  <tr valign="top">
				<td class="room-column">
					<ul>
                    <?php if(isset($this->map['floors']) and is_array($this->map['floors'])){ foreach($this->map['floors'] as $key4=>&$item4){if($key4!='current'){$this->map['floors']['current'] = &$item4;?>
					<?php if(isset($this->map['floors']['current']['rooms']) and is_array($this->map['floors']['current']['rooms'])){ foreach($this->map['floors']['current']['rooms'] as $key5=>&$item5){if($key5!='current'){$this->map['floors']['current']['rooms']['current'] = &$item5;?>
						<li style="cursor:pointer;" title="<?php echo Portal::language('reservation');?>"><label for="room_<?php echo $this->map['floors']['current']['rooms']['current']['id'];?>"><?php echo $this->map['floors']['current']['rooms']['current']['name'];?></label><input type="checkbox" id="room_<?php echo $this->map['floors']['current']['rooms']['current']['id'];?>" value="<?php echo $this->map['floors']['current']['rooms']['current']['id'];?>" onclick="updateRoomIds();" class="room-checkbox"></li>
					<?php }}unset($this->map['floors']['current']['rooms']['current']);} ?>
                    <?php }}unset($this->map['floors']['current']);} ?>
					</ul></td>
				<td class="room-column item">
					<ul>
                    <?php if(isset($this->map['floors']) and is_array($this->map['floors'])){ foreach($this->map['floors'] as $key6=>&$item6){if($key6!='current'){$this->map['floors']['current'] = &$item6;?>
					<?php if(isset($this->map['floors']['current']['rooms']) and is_array($this->map['floors']['current']['rooms'])){ foreach($this->map['floors']['current']['rooms'] as $key7=>&$item7){if($key7!='current'){$this->map['floors']['current']['rooms']['current'] = &$item7;?>
                    <li>
                	<table cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#666666" style="border-collapse:collapse;height:20px;">
                    <tr>
					<?php if(isset($this->map['room_status']) and is_array($this->map['room_status'])){ foreach($this->map['room_status'] as $key8=>&$item8){if($key8!='current'){$this->map['room_status']['current'] = &$item8;?>
						<td align="center" width="1%" bgcolor="<?php if( $this->map['room_status']['current']['D']=='Sun'  ){echo '#d5fca3';}else if($this->map['room_status']['current']['D']=='Sat'){echo '#a2fffe';}else {echo  (date('H',$this->map['room_status']['current']['time'])=='00')?'#eeeeee':'';} ?>">
                        <?php if(isset($this->map['room_status']['current']['rooms']) and is_array($this->map['room_status']['current']['rooms'])){ foreach($this->map['room_status']['current']['rooms'] as $key9=>&$item9){if($key9!='current'){$this->map['room_status']['current']['rooms']['current'] = &$item9;?><?php 
				if(($this->map['floors']['current']['rooms']['current']['id']==$this->map['room_status']['current']['rooms']['current']['party_room_id']))
				{?>
                        <?php if(User::can_view(false,ANY_CATEGORY)) {?><a 
                            class="<?php echo $this->map['room_status']['current']['rooms']['current']['status'];?>" 
                            title="<?php echo $this->map['room_status']['current']['rooms']['current']['party_name'];?> - <?php echo $this->map['room_status']['current']['rooms']['current']['full_name'];?>: <?php echo date('H:i',$this->map['room_status']['current']['rooms']['current']['time_in']).' - '.date('H:i',$this->map['room_status']['current']['rooms']['current']['time_out']);?>" 
                            href="<?php echo Url::build('banquet_reservation',array('cmd'=>'detail','id'=>$this->map['room_status']['current']['rooms']['current']['party_reservation_id']));?>" target="_blank">
                            <strong><?php echo $this->map['room_status']['current']['rooms']['current']['brief_status'];?></strong>
                        </a><?php } ?>
				<?php
				}
				?><?php }}unset($this->map['room_status']['current']['rooms']['current']);} ?>
                        </td>
					<?php }}unset($this->map['room_status']['current']);} ?>	
                    </tr>
                    </table>
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