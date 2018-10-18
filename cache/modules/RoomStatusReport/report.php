<style>
#timehidden{
		display:none;	
	}
	@media print{
		#hidden{
			display:none;
		}
		#timehidden{
			display:block;	
		}
	}
	#room_name{
		width:100px;
		float:left;	
	}
	#report tr td{
		height:40px;	
	}
</style>
<table width="85%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%" style="font-size:11px;">
			<tr valign="top">
				<td align="left" width="75%"><strong><?php echo HOTEL_NAME;?></strong><br /><?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="25%"><?php echo Portal::language('print_by');?> : <?php echo Session::get('user_id');?><br/><?php echo Portal::language('print_date');?> : <?php echo date('H:i d/m/Y',time());?></td>
			</tr>
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<font class="report_title" style="font-size:20px; text-align:center;"><b><?php echo Portal::language('room_status_report');?></b></font>
		<br/>
		<form name="RoomStatusReportForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b><?php echo Portal::language('time_select');?></b></legend>
		 <table style="margin: auto;">
                            <tbody>
                                <tr>
                                    <td><?php echo Portal::language('date');?> : </td>
                                    <td><input name="in_date" id="in_date" value="<?php echo $this->map['in_date'];?>" style="width: 100px;" /></td>
                                    <td><label><?php echo Portal::language('hotel');?>:<select  name="portal_id" id="portal_id" onchange="SearchForm.submit();"><?php
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
	</select></label></td>
                                    <td><input type="submit" class="print" value="<?php echo Portal::language('view_report');?>" /></td>
                                </tr>
                            </tbody>
                        </table>
		  </fieldset>
		  </td></tr></table>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td></tr></table>
</td>
<div>
<?php $i=0; $j=0; $k=0; $l=0; $m=0; $n=0; $p=0; $q=0;?>
<table width="80%" cellpadding="2" style="border:1px solid #DFDFDF; line-height:20px; margin:auto;" id="report">
	<tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('occupied_rooms');?> (<?php echo sizeof($this->map['occupied_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['occupied_rooms']) and is_array($this->map['occupied_rooms'])){ foreach($this->map['occupied_rooms'] as $key1=>&$item1){if($key1!='current'){$this->map['occupied_rooms']['current'] = &$item1;?>
        	<div id="room_name"> <?php echo $this->map['occupied_rooms']['current']['name'];?><?php $i++;?></div>
            <?php }}unset($this->map['occupied_rooms']['current']);} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('booked_rooms');?> (<?php echo sizeof($this->map['booked_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['booked_rooms']) and is_array($this->map['booked_rooms'])){ foreach($this->map['booked_rooms'] as $key2=>&$item2){if($key2!='current'){$this->map['booked_rooms']['current'] = &$item2;?>
        	<div id="room_name"><?php echo $this->map['booked_rooms']['current']['name'];?><?php $j++;?></div>
            <?php }}unset($this->map['booked_rooms']['current']);} ?>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('room_day_used');?> (<?php echo sizeof($this->map['room_day_used']);?>)</i></b></div>
        	<?php if(isset($this->map['room_day_used']) and is_array($this->map['room_day_used'])){ foreach($this->map['room_day_used'] as $key3=>&$item3){if($key3!='current'){$this->map['room_day_used']['current'] = &$item3;?>
        	<div id="room_name"><?php echo $this->map['room_day_used']['current']['name'];?><?php $q++;?></div>
            <?php }}unset($this->map['room_day_used']['current']);} ?>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('repair_rooms');?> (<?php echo sizeof($this->map['suspence_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['suspence_rooms']) and is_array($this->map['suspence_rooms'])){ foreach($this->map['suspence_rooms'] as $key4=>&$item4){if($key4!='current'){$this->map['suspence_rooms']['current'] = &$item4;?>
        	<div id="room_name"> <?php echo $this->map['suspence_rooms']['current']['name'];?><?php $k++;?></div>
            <?php }}unset($this->map['suspence_rooms']['current']);} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('show_rooms');?> (<?php echo count($this->map['show_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['show_rooms']) and is_array($this->map['show_rooms'])){ foreach($this->map['show_rooms'] as $key5=>&$item5){if($key5!='current'){$this->map['show_rooms']['current'] = &$item5;?>
        	<div id="room_name"> <?php echo $this->map['show_rooms']['current']['name'];?></div>
            <?php }}unset($this->map['show_rooms']['current']);} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('clean_rooms');?> (<?php echo count($this->map['clean_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['clean_rooms']) and is_array($this->map['clean_rooms'])){ foreach($this->map['clean_rooms'] as $key6=>&$item6){if($key6!='current'){$this->map['clean_rooms']['current'] = &$item6;?>
        	<div id="room_name"> <?php echo $this->map['clean_rooms']['current']['name'];?></div>
            <?php }}unset($this->map['clean_rooms']['current']);} ?>
        </td>
    </tr>
    <!-- oanh add phong su dung dich vu extrabed -->
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('Extrabed_rooms');?> (<?php echo sizeof($this->map['extrabed_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['extrabed_rooms']) and is_array($this->map['extrabed_rooms'])){ foreach($this->map['extrabed_rooms'] as $key7=>&$item7){if($key7!='current'){$this->map['extrabed_rooms']['current'] = &$item7;?>
        	<div id="room_name"> <?php echo $this->map['extrabed_rooms']['current']['name'];?></div>
            <?php }}unset($this->map['extrabed_rooms']['current']);} ?>
        </td>
    </tr>
    <!-- oanh add phong su dung dich vu extrabed -->
    
     <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('vacant_rooms');?> (<?php echo sizeof($this->map['vacant_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['vacant_rooms']) and is_array($this->map['vacant_rooms'])){ foreach($this->map['vacant_rooms'] as $key8=>&$item8){if($key8!='current'){$this->map['vacant_rooms']['current'] = &$item8;?>
        	<div id="room_name"> <?php echo $this->map['vacant_rooms']['current']['name'];?><?php $l++;?></div>
            <?php }}unset($this->map['vacant_rooms']['current']);} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('vacan_dirty_rooms');?> (<?php echo sizeof($this->map['vacan_dirty_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['vacan_dirty_rooms']) and is_array($this->map['vacan_dirty_rooms'])){ foreach($this->map['vacan_dirty_rooms'] as $key9=>&$item9){if($key9!='current'){$this->map['vacan_dirty_rooms']['current'] = &$item9;?>
        	<div id="room_name"><?php echo $this->map['vacan_dirty_rooms']['current']['name'];?><?php $m++;?></div>
            <?php }}unset($this->map['vacan_dirty_rooms']['current']);} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('departure_rooms');?> (<?php echo sizeof($this->map['departure_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['departure_rooms']) and is_array($this->map['departure_rooms'])){ foreach($this->map['departure_rooms'] as $key10=>&$item10){if($key10!='current'){$this->map['departure_rooms']['current'] = &$item10;?>
        	<div id="room_name"><?php echo $this->map['departure_rooms']['current']['name'];?><?php $n++;?></div>
            <?php }}unset($this->map['departure_rooms']['current']);} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;"><b><i><?php echo Portal::language('checkout_rooms');?> (<?php echo sizeof($this->map['checkout_rooms']);?>)</i></b></div>
        	<?php if(isset($this->map['checkout_rooms']) and is_array($this->map['checkout_rooms'])){ foreach($this->map['checkout_rooms'] as $key11=>&$item11){if($key11!='current'){$this->map['checkout_rooms']['current'] = &$item11;?>
        	<div id="room_name"><?php echo $this->map['checkout_rooms']['current']['name'];?><?php $p++;?></div>
            <?php }}unset($this->map['checkout_rooms']['current']);} ?>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<div style="border-bottom:2px solid silver; width:750px; margin-bottom:10px;">
                <table width="100%">
                    <tr>
                        <td style="height: 10px;"><b><i><?php echo Portal::language('booked_without_room');?> (<?php echo ($this->map['booked_without_room']);?>)</i></b></td>
                        
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</div>
</tr>
</table>
<table width="80%" cellpadding="2" style="border:1px solid #DFDFDF; line-height:20px; margin:auto;">
<tr>

    <td><?php echo $l;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('vacant_rooms');?> </td>
    <td><?php echo $j;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('booked_rooms');?> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->map['booked_without_room'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('booked_without_room');?> </td>
    <td><?php echo $q;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('room_day_used');?> </td>
</tr>
<tr>

	<td><?php echo $k;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('repair_rooms');?> </td>
        <td></td>
</tr>
<tr>

	<td><?php echo $m;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('vacant_dirty_rooms');?></td>
    
    <td></td>
</tr>
<tr>

	<td><?php echo $p;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('checkout_rooms');?> </td>
	<td colspan="2"><?php echo $i+$j+$this->map['booked_without_room'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('booked_rooms');?> + <?php echo Portal::language('occupied_rooms');?> + <?php echo Portal::language('booked_without_room');?></td>

</tr>
</table>
<script>
		$('in_date').value = '<?php echo $this->map['in_date'];?>';
		jQuery("#in_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
</script>