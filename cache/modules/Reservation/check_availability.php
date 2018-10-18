<style type="text/css">
.simple-layout-middle{width:100%;}
</style>
<form name="CheckAvailabilityForm" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bound">
    <tr height="40">
	    <td width="90%" class="w3-text-blue" style="text-transform: uppercase; font-weight: bold; padding-left: 30px; font-size: 24px;"><i class="fa fa-pencil-square-o fa-2x w3-text-orange"></i><?php echo Portal::language('room_availability');?></td>
        <td width="10%" style="padding-right: 30px;"><input name="book" type="submit" value="<?php echo Portal::language('book_now');?>" onclick="if(!check_validate()){return false;}"  class="w3-btn w3-indigo" style="text-transform: uppercase; text-decoration: none;"/></td>
    </tr>
</table>
<div><br />
<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><br clear="all"/><?php }?>
<fieldset style="background-color:#EFEFEF; margin: 0px 20px 0px 20px;">
<!---<legend class="legend-title"><?php echo Portal::language('options');?></legend>--->
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
	  <td>
    	<?php echo Portal::language('from');?>: 
        <input  name="time_in_hour" id="time_in_hour" style="width:50px; display: none;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_in_hour'));?>"><input style="width:40px; name="arrival_time_hour" type="text" id="arrival_time_hour" onchange="changevalue();"  />
        <?php echo Portal::language('hour');?>&nbsp;&nbsp;&nbsp;
        <?php echo Portal::language('day');?>: 
        <input  name="time_in" id="time_in" class="date-input" style="width:50px; display: none;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_in'));?>"><input  name="arrival_time" id="arrival_time" onchange="changevalue();" class="date-input" / type ="text" value="<?php echo String::html_normalize(URL::get('arrival_time'));?>">
		&nbsp;&nbsp;&nbsp;<?php echo Portal::language('to');?>:
        <input  name="time_out_hour" id="time_out_hour"  style="width:50px;  display: none;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_out_hour'));?>"><input style="width:40px; name="departure_time_hour" type="text" id="departure_time_hour"  onchange="changefromday();" />
        <?php echo Portal::language('hour');?>&nbsp;&nbsp;&nbsp;
        <?php echo Portal::language('day');?> 
        <input  name="time_out" id="time_out" class="date-input" style="width:50px;  display: none;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time_out'));?>"><input  name="departure_time" id="departure_time" class="date-input" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('departure_time'));?>">
    </td>
	<!--<td><?php echo Portal::language('reservation_type');?>: <select  name="reservation_type_id" id="reservation_type_id"><?php
					if(isset($this->map['reservation_type_id_list']))
					{
						foreach($this->map['reservation_type_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_type_id',isset($this->map['reservation_type_id'])?$this->map['reservation_type_id']:''))
                    echo "<script>$('reservation_type_id').value = \"".addslashes(URL::get('reservation_type_id',isset($this->map['reservation_type_id'])?$this->map['reservation_type_id']:''))."\";</script>";
                    ?>
	</select></td>-->
	<td style="padding-left: 25px;"><?php echo Portal::language('confirm');?>: <input  name="confirm" id="confirm" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('confirm'));?>"></td>
    <td style="padding-left: 35px;"><input name="search" type="submit" value="<?php echo Portal::language('check_availability');?>" class="w3-btn w3-orange w3-text-white" /></td>
    
  </tr>
</table>
</fieldset><br />
<fieldset style="background-color:#EFEFEF;display: none;">
<legend class="legend-title"><?php echo Portal::language('group_info');?></legend>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
	  <td><?php echo Portal::language('booking_code');?>: <input  name="booking_code" id="booking_code" style="width:150px;" tabindex="7" type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
        <!--<td><?php echo Portal::language('tour');?>: <input  name="tour_name" id="tour_name" style="width:215px;" readonly="" class="readonly" type ="text" value="<?php echo String::html_normalize(URL::get('tour_name'));?>">
                          <input  name="tour_id" id="tour_id" value="0" / type ="hidden" value="<?php echo String::html_normalize(URL::get('tour_id'));?>">
                          <a href="#" onclick="window.open('?page=tour&amp;action=select_tour','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"></td>-->
        <td  colspan="3"><?php echo Portal::language('company');?>: <input  name="customer_name" id="customer_name" style="width:215px;"  readonly="" class="readonly"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                          <input  name="customer_id" id="customer_id" style="display:none;"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                          <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;"/></td>
	</tr>
</table>
</fieldset>
</div><br />
<div>
<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="margin: 0px 20px 0px 20px;">
  <tr>
  	<td width="400px" valign="top">
     <div style="float:left;width:100%;">
     	<?php if(isset($this->map['ebs']) and is_array($this->map['ebs'])){ foreach($this->map['ebs'] as $key1=>&$item1){if($key1!='current'){$this->map['ebs']['current'] = &$item1;?>
        <div class="check-availability-item" style="padding-left: 10px;"><?php echo $this->map['ebs']['current']['name'];?></div>
     	<?php }}unset($this->map['ebs']['current']);} ?>
     </div>
    </td>
    <td style="width: 60%;">
        <div style="float:left;width:40%;overflow:scroll;">
        <div style="width:2500px;float:left;">
        
        <?php
            $k =0; 
        ?>
		<?php if(isset($this->map['ebs']) and is_array($this->map['ebs'])){ foreach($this->map['ebs'] as $key2=>&$item2){if($key2!='current'){$this->map['ebs']['current'] = &$item2;?>
            <div class="check-availability-item">
            <?php
                if($k==0)
                {
                    ?>
                        
                        <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;position: absolute;"><strong><?php echo Portal::language('total');?></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"></span>
                    
                    <?php 
                }
                else
                {
                    if(isset($this->map['ebs']['current']['id']))
                    {
                    ?>
                    
                     <span class="check-availability-day" style="background-color: #FFFFFF; color: black;position: absolute;">
            		<?php echo $this->map['ebs']['current']['total_quantity'];?></span>
                    <span class="check-availability-day" style="background-color: #FFFFFF; color: black;">
            		&nbsp;</span>
                    
                    <?php
                    }
                     
                } 
                $k++;
            ?>
            
			<?php if(isset($this->map['ebs']['current']['day_items']) and is_array($this->map['ebs']['current']['day_items'])){ foreach($this->map['ebs']['current']['day_items'] as $key3=>&$item3){if($key3!='current'){$this->map['ebs']['current']['day_items']['current'] = &$item3;?>
            <?php echo $this->map['ebs']['current']['day_items']['current']['quantity'];?>
            <?php }}unset($this->map['ebs']['current']['day_items']['current']);} ?>
            </div>
		<?php }}unset($this->map['ebs']['current']);} ?>
        </div>
        </div>
    </td>
  </tr>
  <tr>
  	<td colspan="3">&nbsp;<br /></td>
  </tr>
  <tr>
    <td width="400px" style="vertical-align: top;"><?php $i=1;$j=0;?>
    <div style="float:left;padding-top: 0px; margin-top: 0px; height: 465px; padding-left: 10px;">
		<?php if(isset($this->map['room_levels']) and is_array($this->map['room_levels'])){ foreach($this->map['room_levels'] as $key4=>&$item4){if($key4!='current'){$this->map['room_levels']['current'] = &$item4;?>
        	<div class="check-availability-item" style="float:left;white-space:nowrap">
			<?php if($i>1){?>
            <input  name="adult_<?php echo $this->map['room_levels']['current']['id'];?>" type="text" id="adult_<?php echo $this->map['room_levels']['current']['id'];?>" style="width:30px;height: 30px !important;" tabindex="<?php echo $j+9;$j++;?>">
            <input  name="child_<?php echo $this->map['room_levels']['current']['id'];?>" type="text" id="child_<?php echo $this->map['room_levels']['current']['id'];?>" style="width:30px; height: 30px !important;" tabindex="<?php echo $j+9;$j++;?>">
            <input  name="price_<?php echo $this->map['room_levels']['current']['id'];?>" type="text" id="price_<?php echo $this->map['room_levels']['current']['id'];?>" class="price" style="width:70px;position:relative; height: 30px !important;"  tabindex="<?php echo $j+9;$j++;?>" onfocus="//buildRateList('<?php echo $this->map['room_levels']['current']['id'];?>');" onclick="//buildRateList('<?php echo $this->map['room_levels']['current']['id'];?>');" onKeyUp="this.value=number_format(to_numeric(this.value));" oninput="jQuery('#usd_price_<?php echo $this->map['room_levels']['current']['id'];?>').val(number_format(to_numeric(jQuery('#price_<?php echo $this->map['room_levels']['current']['id'];?>').val())/to_numeric(jQuery('#exchange_rate').val())));">
            <input  name="usd_price_<?php echo $this->map['room_levels']['current']['id'];?>" type="text" id="usd_price_<?php echo $this->map['room_levels']['current']['id'];?>" class="price" style="width:60px;position:relative; height: 30px !important;"  tabindex="<?php echo $j+9;$j++;?>"   oninput="jQuery('#price_<?php echo $this->map['room_levels']['current']['id'];?>').val(number_format(to_numeric(jQuery('#usd_price_<?php echo $this->map['room_levels']['current']['id'];?>').val())*to_numeric(jQuery('#exchange_rate').val())));jQuery('#usd_price_<?php echo $this->map['room_levels']['current']['id'];?>').ForceNumericOnly().FormatNumber();">
            <input  
                    name="room_quantity_<?php echo $this->map['room_levels']['current']['id'];?>" 
                    type="text" id="room_quantity_<?php echo $this->map['room_levels']['current']['id'];?>" 
                    onkeyup="
                        <?php 
                            if (!OVER_BOOK)
                            { 
                        ?>
                        if(to_numeric(this.value)>to_numeric($('min_item_<?php echo $this->map['room_levels']['current']['id'];?>').value))
                        {
                            alert('<?php echo Portal::language('quantity');?> <?php echo $this->map['room_levels']['current']['name'];?> <?php echo Portal::language('must_smaller');?> '+$('min_item_<?php echo $this->map['room_levels']['current']['id'];?>').value);
                            this.focus();
                        }
                        <?php 
                            }
                        ?>
                        check_quantity();"
                    style="width:30px; height: 30px !important;background-color:#FF9;border:1px solid #F90;"  tabindex="<?php echo $j+9;$j++;?>" class="room-quantity-by-date QuantityRoom" lang="<?php echo $this->map['room_levels']['current']['name'];?>" title="<?php echo Portal::language('room_quantity');?>">
			<!--<input  name="note_<?php echo $this->map['room_levels']['current']['id'];?>" type="text" id="note_<?php echo $this->map['room_levels']['current']['id'];?>" style="width:55px;" tabindex="<?php echo $j+9;$j++;?>">-->
            <?php }else{?>
            <span class="room-quantity-by-date"><img src="packages/core/skins/default/images/buttons/adult.png" style="padding-left: 10px;" align="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date"><img src="packages/core/skins/default/images/buttons/child.png" align="top">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date"><?php echo Portal::language('vnd_price');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date"><?php echo Portal::language('usd_price');?>&nbsp;&nbsp;&nbsp;</span>
            <span class="room-quantity-by-date"><?php echo Portal::language('r_q');?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<!--<span class="room-quantity-by-date"><?php echo Portal::language('note');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
            <?php }?>
            <?php echo $this->map['room_levels']['current']['name'];?></div>
            <?php $i++;?>
        <?php }}unset($this->map['room_levels']['current']);} ?>
        <span style="padding-left: 166px; "><b><?php echo Portal::language('total');?>:</b>
        </span>
        <input  name="sum_t" id="sum_t" style="width:30px;height: 30px !important;"/ type ="text" value="<?php echo String::html_normalize(URL::get('sum_t'));?>">&nbsp;&nbsp;<b><?php echo Portal::language('room');?></b>
        <span class="room-quantity-by-date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </div>
    </td>
    
    <?php
        $h =0; 
    ?>
	<td style="width: 100%;">
    	<div style="float:left;width:40%;overflow-y:hidden;">
            <div style="width:2500px;float:left;height: 480px;">
        		<?php if(isset($this->map['room_levels']) and is_array($this->map['room_levels'])){ foreach($this->map['room_levels'] as $key5=>&$item5){if($key5!='current'){$this->map['room_levels']['current'] = &$item5;?>
        			<div class="check-availability-item">
                    <?php
                        if($h==0)
                        {
                    ?>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;position: absolute;"><strong><?php echo Portal::language('total');?></strong></span>
                            <span class="check-availability-day header" style="border-right:1px solid #EFEFEF;"><strong><?php echo Portal::language('real');?></strong></span>
                            <?php 
                        } 
                        else
                        {
                            ?>
                            <?php 
				if(($this->map['room_levels']['current']['id']))
				{?>
                    		 <span class="check-availability-day" style="background-color: #FFFFFF; color: black;"><?php echo $this->map['room_levels']['current']['total_room_quantity'];?></span>
                             <input  name="min_item_<?php echo $this->map['room_levels']['current']['id'];?>" type="hidden" id="min_item_<?php echo $this->map['room_levels']['current']['id'];?>" value="<?php echo $this->map['room_levels']['current']['min_room_quantity'];?>" />  
                             <input  name="min_item_<?php echo $this->map['room_levels']['current']['id'];?>" type="hidden" id="min_item_<?php echo $this->map['room_levels']['current']['id'];?>" value="<?php echo $this->map['room_levels']['current']['min_room_quantity'];?>" />                         
                            
				<?php
				}
				?>
                            <?php 
                        }
                        $h++;
                    ?>
                    <!--onclick="jQuery('#room_list').css({'display':'block','top':200,'left':500});"-->
        			<?php if(isset($this->map['room_levels']['current']['day_items']) and is_array($this->map['room_levels']['current']['day_items'])){ foreach($this->map['room_levels']['current']['day_items'] as $key6=>&$item6){if($key6!='current'){$this->map['room_levels']['current']['day_items']['current'] = &$item6;?><?php echo $this->map['room_levels']['current']['day_items']['current']['room_quantity'];?><?php }}unset($this->map['room_levels']['current']['day_items']['current']);} ?>
        			</div>
        		<?php }}unset($this->map['room_levels']['current']);} ?>
                <div class="check-availability-item">
                    <span class="check-availability-day" style="background-color: #FFFFFF; color: black; position: absolute;"><strong><?php echo $this->map['total_room'];?></strong></span>
                </div>
        		<div style="float: left; margin-top: 3px; width: 29px;height:15px;border: 1px solid white; margin-left: 2px; text-align: center;">
                <table style="border: 1px solid white;">
                    <tr class="check-availability-item"><td style="width: 29px;height:32px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 0px; "><strong>AV</strong></td></tr>
                    <!-- check inventory -->
                    <?php 
				if((SITEMINDER_TWO_WAY or USE_HLS))
				{?>
                    <tr class="check-availability-item"><td style="width: 29px;height:32px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 0px; "><strong>ALM</strong></td></tr>
                    
				<?php
				}
				?>
                    <!-- end check inventory -->
                    <tr class="check-availability-item"><td style="width: 29px;height:26px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 10px;"><strong>OC</strong></td></tr>
                    <tr class="check-availability-item"><td style="width: 29px;height:26px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 10px; "> <em>CF</em></td></tr>
                    <tr class="check-availability-item"><td style="width: 29px;height:26px;margin-left: 2px; text-align: center;background: white;position: absolute;margin-top: 10px; "><em>TE</em></td></tr>
                    <tr class="check-availability-item"><td style="width: 29px;height:26px;margin-left: 2px; text-align: center;background: white;"><strong>RE</strong></td></tr>
                </table>
                </div>
                <?php if(isset($this->map['total_by_day']) and is_array($this->map['total_by_day'])){ foreach($this->map['total_by_day'] as $key7=>&$item7){if($key7!='current'){$this->map['total_by_day']['current'] = &$item7;?>
                    <div style="float: left; margin-top: 3px; width: 36px;height:15px;border: 1px solid white; text-align: center;">
                    <table style="border: 1px solid white;">
                        <tr class="check-availability-item"><td style="width: 350px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;<?php if($this->map['total_by_day']['current']['total_avai_room']<0){echo "background:red;color:white;";} ?>"><strong><?php echo $this->map['total_by_day']['current']['total_avai_room'];?></strong></td></tr>
                        <!-- check inventory -->
                        <?php 
				if((SITEMINDER_TWO_WAY))
				{?>
                        <tr class="check-availability-item"><td style="width: 350px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;<?php if($this->map['total_by_day']['current']['total_inventory']<0){echo "background:red;color:white;";} ?>"><strong><?php echo $this->map['total_by_day']['current']['total_inventory'];?></strong></td></tr>
                        
				<?php
				}
				?>
                        <!-- end check inventory -->
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><strong><?php echo($this->map['total_by_day']['current']['total_occ_room']-$this->map['total_by_day']['current']['total_repair_room']);?></strong></td></tr>
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><em><?php echo $this->map['total_by_day']['current']['total_confirm'];?></em></td></tr>
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><em><?php echo $this->map['total_by_day']['current']['total_not_confirm'];?></em></td></tr>
                        <tr class="check-availability-item"><td style="width: 30px;height:15px;margin-left: 2px; text-align: center; margin-top: 10px;"><strong><?php echo $this->map['total_by_day']['current']['total_repair_room'];?></strong></td></tr>
                    </table>
                    </div>
        		<?php }}unset($this->map['total_by_day']['current']);} ?>
                
            </div>
        </div>
	</td>
  </tr>
</table>
</div>
<div id="rate_list" class="room-rate-list" style="display:none;">
    <div>
        <?php echo Portal::language('rate_list');?>&nbsp;&nbsp;
        <a onclick="$('rate_list').style.display='none';"><img src="skins/default/images/close.JPG" title="<?php echo Portal::language('close');?>"></a>
    </div>
    <ul id="rate_list_result">
    </ul>
</div>
<!--start:KID them de chuyen doi gia-->
<input type="hidden" id="exchange_rate" value="<?php echo $this->map['exchange_rate'];?>" />
<!--end:KID them de chuyen doi gia-->

<div id="room_list" class="room-list" style="width:700px;height:500px;overflow:scroll;display:none;position: fixed; top: 100px; left: 300px;">
     <div class="w3-text-black" style="text-transform: uppercase; text-align: center; height; 20px;">
        <?php echo Portal::language('room_list_aval');?>:&nbsp;&nbsp;<span id="date_list"></span>
        <a onclick="$('room_list').style.display='none';"><img src="skins/default/images/close.JPG" title="<?php echo Portal::language('close');?>" style="float:right;"></a>
    </div>
    <div id="content_table"></div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery(document).ready(function(){
        var roomlevelid = <?php echo $this->map['room_levels_js'];?>;
        for(var j in roomlevelid)
        {
            jQuery("#adult_"+roomlevelid[j]['id']).val(roomlevelid[j]['num_people']);
            jQuery("#price_"+roomlevelid[j]['id']).val(number_format(roomlevelid[j]['price']));
            jQuery("#usd_price_"+roomlevelid[j]['id']).val(number_format((roomlevelid[j]['price'])/to_numeric(jQuery('#exchange_rate').val())));
        }
                   
    });
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    jQuery("#time_in").mask("99:99");
	jQuery("#time_out").mask("99:99");
    jQuery("#arrival_time_hour").mask("99:99");
    jQuery("#departure_time_hour").mask("99:99");
    jQuery("#arrival_time").datepicker();
    jQuery("#departure_time").datepicker();
	jQuery("#scroll_1").scroll(function(){
           scroll_1 = jQuery("#scroll_1").scrollLeft(); 
           jQuery("#scroll_3").scrollLeft(scroll_1); 
        });
    jQuery("#scroll_3").scroll(function(){
           scroll_3 = jQuery("#scroll_3").scrollLeft(); 
           jQuery("#scroll_1").scrollLeft(scroll_3); 
        });
    
    
	function selectAllLevel(levelId,minRoomQuantity){
		jQuery(".room-quantity-by-date").each(function(){
			idString = this.id;
			var re =  new RegExp("room_quantity_"+levelId,"g");
			if(idString.match(re)){
				if(jQuery(this).val()==''){
					jQuery(this).val(minRoomQuantity);
				}else{
					jQuery(this).val('');
				}
			}
		});
	}
	function buildRateList(roomLevelId){
		if(jQuery('#price_'+roomLevelId)){
			var customerId = jQuery('#customer_id').val();
			var adult = jQuery("#adult_"+roomLevelId).val();
			var child = jQuery("#child_"+roomLevelId).val();
			var startDate = jQuery('#arrival_time').val();
			var endDate = jQuery('#departure_time').val();
			getRateList(jQuery('#price_'+roomLevelId).attr('id'),roomLevelId,customerId,adult,child,startDate,endDate);
		}
	}
	function getRateList(id,roomLevelId,customerId,adult,child,startDate,endDate){
		if(adult<=0){
			alert('Chưa nhập số lượng người lớn / Miss adult quantity');
			jQuery('#adult_'+roomLevelId).focus();
			return false;
		}
		if(roomLevelId){
			obj = $(id);
			if($('rate_list').style.display=="none"){
				$('rate_list').style.display="";
				$('rate_list').style.top=obj.offsetTop-20+'px';
				$('rate_list').style.left=obj.offsetLeft+60+'px';
				jQuery('#rate_list_result').html('Loading...');
				ajax.get_text('r_get_rate_list.php?room_level_id='+roomLevelId+'&customer_id='+customerId+'&adult='+adult+'&child='+child+'&start_date='+startDate+'&end_date='+endDate, setRateList);
			}
		}else{
			//alert('You did not select room');
		}
	}
	function setRateList(text){
		jQuery('#rate_list_result').html(text);
	}
	function setRate(roomLevelId,rate){
		$('price_'+roomLevelId).value = rate;
		jQuery('#rate_list').hide();
	}
	function check_validate()
	{
		<?php if(isset($this->map['room_levels']) and is_array($this->map['room_levels'])){ foreach($this->map['room_levels'] as $key8=>&$item8){if($key8!='current'){$this->map['room_levels']['current'] = &$item8;?><?php 
				if(($this->map['room_levels']['current']['id'] and !OVER_BOOK))
				{?>
		if(to_numeric($('room_quantity_<?php echo $this->map['room_levels']['current']['id'];?>').value)>0 && to_numeric($('room_quantity_<?php echo $this->map['room_levels']['current']['id'];?>').value)>to_numeric($('min_item_<?php echo $this->map['room_levels']['current']['id'];?>').value))
		{
			alert('<?php echo Portal::language('quantity');?> <?php echo $this->map['room_levels']['current']['name'];?> <?php echo Portal::language('must_smaller');?> '+$('min_item_<?php echo $this->map['room_levels']['current']['id'];?>').value);
			return false;
		}
		
				<?php
				}
				?>
		<?php }}unset($this->map['room_levels']['current']);} ?>
        var myfromdate = $('arrival_time').value.split("/");
        //var now = new Date(getFullYear()+'-'+getMonth()+'-'+getDate());
        date_now = new Date(); 
       
        if(new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0],23,59,59)< date_now)
        {
            alert('<?php echo Portal::language('no_booking_past');?>');
            return false;
        }
        
		return true;
	}
	function check_from_date(){
	  
        var from_date = $('arrival_time').value.split("/");
        from_date = from_date[1]+"/"+from_date[0]+"/"+from_date[2]; 
        var from_time = Date.parse(from_date.toString());
        //Cong 1 tuan le (ms nen * 1000)
        var to_time = to_numeric(from_time) + 2592000000;
        var to_date = new Date(to_time);
        to_date = to_date.getDate()+"/"+(to_date.getMonth()+1)+"/"+to_date.getFullYear();
        $('departure_time').value = to_date;
	}
    
    function changevalue()
    {
        var myfromdate = $('arrival_time').value.split("/");
        var mytodate = $('departure_time').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#departure_time").val(jQuery("#arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('arrival_time').value.split("/");
        var mytodate = $('departure_time').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#arrival_time").val(jQuery("#departure_time").val());
        }
    }
    function check_quantity()
    {
        var sum = 0;
        jQuery(".QuantityRoom").each(function(){
            $sumi = to_numeric(jQuery("#"+this.id).val());
            sum += $sumi;            
          ;   
        jQuery('#sum_t').val(sum);               
        })
    }
    function parseDate(str) 
    {
        var mdy = str.split('/');
        return new Date(mdy[2], mdy[1], mdy[0]);
    }
    //phan tich chon bat ra cua so cac phong
    function list_avalible(room_levels_id,in_date,num)
    {
        if (window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var objs = jQuery.parseJSON(text_reponse);
                obj = objs['floor'];
                document.getElementById("content_table").innerHTML = '';
                var text_html = '<table cellpadding="3" width="100%" id="table_room_type">';
                var number_room = 0;
                for( var i in obj)
                {
                    text_html += '<tr> <td width="50" style="border-bottom:1px solid white;">'+obj[i]['name']+'</td>';
                    text_html += '<td style="border-bottom:1px solid white;">';
            	    text_html += '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
                    for(var j in obj[i]['rooms'])
                    {
                         text_html += '<div class="room-bound" style="width:50px; height:50px; float:left;" onclick="var obj=jQuery(\'#room_select\').val(); jQuery(\'#room_level_name_\'+obj).val(\''+obj[i]['rooms'][j]['room_level_name']+'\');jQuery(\'#room_level_id_\'+obj).val('+obj[i]['rooms'][j]['room_level_id']+');jQuery(\'#room_id_\'+obj).val('+obj[i]['rooms'][j]['id']+');jQuery(\'#room_name_\'+obj).val(\''+obj[i]['rooms'][j]['room_name']+'\');$(\'room_list\').style.display=\'none\';checkRoom('+obj[i]['rooms'][j]['id']+',obj);">';
                               text_html += '<a href="#" style="width:45px;height:40px; text-decoration:none;" class="room AVAILABLE" lang="'+obj[i]['rooms'][j]['room_level_id']+'" id="'+obj[i]['rooms'][j]['id']+'" >';
                         text_html += '<span style="font-size:9px;font-weight:bold;color:#039" >'+obj[i]['rooms'][j]['room_name']+'<br />'+obj[i]['rooms'][j]['brief_name']+'</span><br />';
                   			   text_html += '</a>';
                         text_html += '</div>';
                         number_room++;
                    }
                    text_html += '</td></tr></table></td></tr>';
                }
                text_html += '</table>';
                document.getElementById("content_table").innerHTML = '<div style="width: 100%; text-align:center; height: 20px; padding-top: 2px; background: #00b2f9; color: #ffffff;">Có '+(number_room-num)+' Đặt phòng chưa gán phòng! </div>';
                document.getElementById("content_table").innerHTML += text_html;
                jQuery("#date_list").html(objs['in_date']);
                jQuery("#room_list").css('display','');
                //alert("có "+(number_room-num)+" Đặt phòng chưa gán phòng!");
            }
        }
        xmlhttp.open("GET","get_avalible_room.php?data=xxx&room_level_id="+room_levels_id+"&in_date="+in_date+"",true);
        xmlhttp.send();
    }
    //end---
</script>
