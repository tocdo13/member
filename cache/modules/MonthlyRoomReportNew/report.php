<style>
    @media print {
        #MonthlyRoomReportHeader {
            display: none;
        }
    }
    body{
     -webkit-touch-callout: none;
     -webkit-user-select: none; 
     -moz-user-select: none;    
     -ms-user-select: none;     
     -o-user-select: none;
     user-select: none;
     }
     #ui-datepicker-div {
        z-index: 999999999;
     }
     .menuIconRight{
            display: block;
        }
     .menuIconHide{
            display: block;
        }
     @media screen and (max-width: 1100px) {
        .menuIconRight{
            display: none!important;
        }
    }
    @media screen and (max-width: 720px) {
        .menuIconHide{
            display: none!important;
        }
    }
</style>
<div id="MonthlyRoomReportHeader">
    <form name="MonthlyRoomReportNewForm" method="POST">
        <ul>
            <li class="f-left menuIconRight"><h3 class="w3-hide-small"><?php echo Portal::language('monthly_room_report');?></h3></li>
            <li class="f-left"><?php echo Portal::language('form');?>: <input  name="from_date" id="from_date" onchange="ValidateTime(false,this);" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></li>
            <li class="f-left"><?php echo Portal::language('to');?>: <input  name="to_date" id="to_date" onchange="ValidateTime(false,this);" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></li>
            <li class="f-left menuIconHide"><?php echo Portal::language('order_by');?>: <select  name="order_by" id="order_by"><?php
					if(isset($this->map['order_by_list']))
					{
						foreach($this->map['order_by_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('order_by',isset($this->map['order_by'])?$this->map['order_by']:''))
                    echo "<script>$('order_by').value = \"".addslashes(URL::get('order_by',isset($this->map['order_by'])?$this->map['order_by']:''))."\";</script>";
                    ?>
	</select></li>
            <li class="f-left menuIconHide"><div class="icon-button"><?php echo Portal::language('view_with_floor_room');?> <i class="fa fa-fw fa-caret-down" title="<?php echo Portal::language('view_with_floor_room');?>"></i></div>
                <ul title="<?php echo Portal::language('view_with_floor_room');?>">
                    <li><label><input id="SelectAllFloor" type="checkbox" onclick="FunSelectAllFloor();" /> <?php echo Portal::language('select_all');?></label></li>
                    <?php if(isset($this->map['floor']) and is_array($this->map['floor'])){ foreach($this->map['floor'] as $key1=>&$item1){if($key1!='current'){$this->map['floor']['current'] = &$item1;?>
                        <li><label><input name="floor[<?php echo $this->map['floor']['current']['id'];?>][id]" type="checkbox" id="<?php echo $this->map['floor']['current']['id'];?>" class="SelectFloor" value="<?php echo $this->map['floor']['current']['id'];?>" <?php if(isset($_REQUEST['floor'][$this->map['floor']['current']['id']])){ echo 'checked="checked"'; } ?> /> <?php echo $this->map['floor']['current']['id'];?></label></li>
                    <?php }}unset($this->map['floor']['current']);} ?>
                </ul>
            </li>
            <li class="f-left"><div class="icon-button" onclick="ValidateTime(true,this);"><i class="fa fa-fw fa-search" title="<?php echo Portal::language('search');?>"></i></div></li>
            
            <li class="f-right menuIconRight"><div class="icon-button" onclick="if($on_off_hightlight_hover==1){ $on_off_hightlight_hover=0; jQuery(this).css('background','#ff4d4d'); }else{ $on_off_hightlight_hover=1; jQuery(this).css('background','#FFFFFF'); }"><i class="fa fa-fw fa-hand-pointer-o" title="<?php echo Portal::language('on_off_hightlight_hover');?>"></i></div></li>
            <li class="f-right menuIconRight"><div class="icon-button"><i class="fa fa-fw fa-th" title="<?php echo Portal::language('status_rooms');?>"></i></div>
                <ul title="<?php echo Portal::language('status_rooms');?>">
                    <li>BOOKED NOT ASIGN <div class="BOOKED-NOT-ASIGN f-left"></div></li>
                    <li>BOOKED <div class="BOOKED f-left"></div></li>
                    <li>BOOKED DO NOT MOVE <div class="BOOKED-DO-NOT-MOVE f-left"></div></li>
                    <li>OCCUPIED <div class="OCCUPIED f-left"></div></li>
                    <li>CHECKOUT <div class="CHECKOUT f-left"></div></li>
                    <li>REPAIR <div class="REPAIR f-left"></div></li>
                    <li>HOUSEUSE <div class="HOUSEUSE f-left"></div></li>
                    <li>CLOSE <div class="CLOSE f-left"></div></li>
                </ul>
            </li>
            <li class="f-right menuIconRight"><div class="icon-button" onclick="location.href='<?php echo Url::build('room_map');?>';"><i class="fa fa-fw fa-home" title="<?php echo Portal::language('room_map');?>"></i></div></li>
            <li class="f-right menuIconRight"><div class="icon-button" onclick="window.print();"><i class="fa fa-fw fa-print" title="<?php echo Portal::language('print');?>"></i></div></li>
            <li class="f-right menuIconRight"><div class="icon-button" onclick="location.href='<?php echo Url::build('change_language',$this->map['param_build_change_lang']);?>';"><i class="fa fa-fw fa-language" title="<?php echo Portal::language('change_language');?>"></i><?php 
				if((Portal::language()==1))
				{?>EN <?php }else{ ?>VN
				<?php
				}
				?></div></li>
            <li class="f-right menuIconRight"><div class="icon-button"><i class="fa fa-fw fa-user" title="<?php echo Portal::language('account');?>"></i></div>
                <ul title="<?php echo Portal::language('account');?>">
                    <li><a href="<?php echo Url::build('sign_out');?>"><i class="fa fa-fw fa-sign-out"></i><?php echo Portal::language('sign_out');?></a></li>
                    <li><a href="<?php echo Url::build('personal');?>"><i class="fa fa-fw fa-cogs"></i><?php echo Portal::language('edit_infomation');?></a></li>
                </ul>
            </li>
        </ul>
        <input id="room_ids" type="hidden" value="" />
        <input id="room_id_last_select" type="hidden" value="" />
        <input id="reservation_id_right_click" type="hidden" value="" />
        <input id="start_offset_left" type="hidden" value="" />
        <input id="start_offset_top" type="hidden" value="" />
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>

<div id="TimeLine" style="display: none; position: relative;" oncontextmenu="return false;"> <!-- oncontextmenu="openmenu(); return false;" -->
    <div id="header" class="header_position">
        <div class="header_items_title content_hidden box-scroll-hidden"><?php echo Portal::language('room');?>/<?php echo Portal::language('date');?></div>
        <?php if(isset($this->map['day']) and is_array($this->map['day'])){ foreach($this->map['day'] as $key2=>&$item2){if($key2!='current'){$this->map['day']['current'] = &$item2;?>
            <?php 
				if(($this->map['day']['current']['show_report']==1))
				{?>
                <div class="header_items content_hidden" style="background: <?php echo $this->map['day']['current']['bg_color'];?>; color: <?php echo $this->map['day']['current']['font_color'];?>;" lang="<?php echo $this->map['day']['current']['stt'];?>"><?php echo $this->map['day']['current']['day'];?><br /><?php echo $this->map['day']['current']['weekday'];?><br /><span><?php echo $this->map['day']['current']['occupied'];?>/<?php echo $this->map['day']['current']['available']-$this->map['total_virtual']; ?></span></div>
            
				<?php
				}
				?>
        <?php }}unset($this->map['day']['current']);} ?>
    </div>
    <div id="header-fixed" class="header_position-fixed" style="display: none; top: 0px;">
        <div class="header_items_title content_hidden box-scroll-hidden"><?php echo Portal::language('room');?>/<?php echo Portal::language('date');?></div>
        <?php if(isset($this->map['day']) and is_array($this->map['day'])){ foreach($this->map['day'] as $key3=>&$item3){if($key3!='current'){$this->map['day']['current'] = &$item3;?>
            <?php 
				if(($this->map['day']['current']['show_report']==1))
				{?>
                <div class="header_items content_hidden" style="background: <?php echo $this->map['day']['current']['bg_color'];?>; color: <?php echo $this->map['day']['current']['font_color'];?>;" lang="<?php echo $this->map['day']['current']['stt'];?>"><?php echo $this->map['day']['current']['day'];?><br /><?php echo $this->map['day']['current']['weekday'];?><br /><span><?php echo $this->map['day']['current']['occupied'];?>/<?php echo $this->map['day']['current']['available']-$this->map['total_virtual']; ?></span></div>
            
				<?php
				}
				?>
        <?php }}unset($this->map['day']['current']);} ?>
    </div>
    <?php $row = 1; $position = 0; ?>
    <?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key4=>&$item4){if($key4!='current'){$this->map['rooms']['current'] = &$item4;?>
    <?php $row++; ?>
    <div class="rooms" id="rooms_<?php echo $this->map['rooms']['current']['id'];?>">
        <div class="rooms_items_title content_hidden" style="background: #ffffe6; color: <?php echo $this->map['rooms']['current']['color'];?>;"><p><?php echo $this->map['rooms']['current']['name'];?></p><span style="color: <?php echo $this->map['rooms']['current']['color'];?>;"><?php echo $this->map['rooms']['current']['room_level_name'];?> - <?php echo $this->map['rooms']['current']['room_type_name'];?></span></div>
        <?php $col = 1; ?>
        <?php if(isset($this->map['rooms']['current']['child']) and is_array($this->map['rooms']['current']['child'])){ foreach($this->map['rooms']['current']['child'] as $key5=>&$item5){if($key5!='current'){$this->map['rooms']['current']['child']['current'] = &$item5;?>
            <?php 
				if(($this->map['rooms']['current']['child']['current']['show_report']==1))
				{?>
            <?php $col++; ?>
                <div 
                    id="<?php echo $row.'_'.$col; ?>"
                    room_id="<?php echo $this->map['rooms']['current']['id'];?>" 
                    room_name="<?php echo $this->map['rooms']['current']['name'];?>" 
                    day_prev="<?php echo $this->map['rooms']['current']['child']['current']['date_prev'];?>" 
                    day="<?php echo $this->map['rooms']['current']['child']['current']['date'];?>" 
                    day_next="<?php echo $this->map['rooms']['current']['child']['current']['date_next'];?>" 
                    time_prev="<?php echo $this->map['rooms']['current']['child']['current']['time_prev'];?>" 
                    time="<?php echo $this->map['rooms']['current']['child']['current']['time'];?>" 
                    time_next="<?php echo $this->map['rooms']['current']['child']['current']['time_next'];?>" 
                    location="<?php echo $row.','.$col; ?>"
                    type="ROOM_ITEMS"
                    position="<?php echo $position++; ?>"
                    use="<?php echo $this->map['rooms']['current']['child']['current']['use'];?>"
                    is_use="<?php echo $this->map['rooms']['current']['child']['current']['is_use'];?>"
                    onclick="SelectRooms(this);"
                    oncontextmenu="openmenu(this); return false;"
                    class="<?php echo $this->map['rooms']['current']['child']['current']['bg_room'];?> rooms_items_drop rooms_items content_hidden items_row_<?php echo $this->map['rooms']['current']['id'];?> items_col_<?php echo $this->map['rooms']['current']['child']['current']['stt'];?>" 
                    lang="<?php echo $this->map['rooms']['current']['child']['current']['stt'];?>,<?php echo $this->map['rooms']['current']['id'];?>,<?php echo $this->map['rooms']['current']['child']['current']['stt'];?>,<?php echo $this->map['rooms']['current']['child']['current']['use'];?>"
                    >
                </div>
            
				<?php
				}
				?>
        <?php }}unset($this->map['rooms']['current']['child']['current']);} ?>
    </div>
    <?php if(isset($this->map['rooms']['current']['reservations']) and is_array($this->map['rooms']['current']['reservations'])){ foreach($this->map['rooms']['current']['reservations'] as $key6=>&$item6){if($key6!='current'){$this->map['rooms']['current']['reservations']['current'] = &$item6;?>
        <div
             id="RESERVATION_<?php echo $this->map['rooms']['current']['reservations']['current']['id'];?>" 
             room_id="<?php echo $this->map['rooms']['current']['id'];?>"
             room_name="<?php echo $this->map['rooms']['current']['name'];?>" 
             type="ROOM_RESERVATIONS" 
             status="<?php echo $this->map['rooms']['current']['reservations']['current']['status'];?>" 
             reservation_id="<?php echo $this->map['rooms']['current']['reservations']['current']['reservation_id'];?>" 
             reservation_room_id="<?php echo $this->map['rooms']['current']['reservations']['current']['reservation_room_id'];?>" 
             time_in="<?php echo $this->map['rooms']['current']['reservations']['current']['time_in'];?>" 
             time_out="<?php echo $this->map['rooms']['current']['reservations']['current']['time_out'];?>" 
             arrival_time="<?php echo $this->map['rooms']['current']['reservations']['current']['arrival_time'];?>" 
             departure_time="<?php echo $this->map['rooms']['current']['reservations']['current']['departure_time'];?>" 
             night="<?php echo $this->map['rooms']['current']['reservations']['current']['count_night'];?>"
             row="<?php echo $row; ?>" 
             oncontextmenu="openmenu(this); return false;"
             onclick="resetSelectRooms();" 
             class="<?php echo $this->map['rooms']['current']['reservations']['current']['status'];?> rooms_reservations content_hidden <?php if($this->map['rooms']['current']['reservations']['current']['status']=='OCCUPIED' && ( (Date_Time::to_time($this->map['rooms']['current']['reservations']['current']['departure_time']))>Date_Time::to_time(date('d/m/Y')) ) ){ echo 'rooms_reservations_occupied_drag draggable'; }elseif($this->map['rooms']['current']['reservations']['current']['status']=='BOOKED' OR $this->map['rooms']['current']['reservations']['current']['status']=='BOOKED-NOT-ASIGN'){ echo 'rooms_reservations_booked_drag draggable'; } ?>" 
             lang="<?php echo $this->map['rooms']['current']['reservations']['current']['stt'];?>,<?php echo $this->map['rooms']['current']['id'];?>,<?php echo $this->map['rooms']['current']['reservations']['current']['stt'];?>,<?php echo $this->map['rooms']['current']['reservations']['current']['count_night'];?>"
             title="
                 <?php 
				if(($this->map['rooms']['current']['reservations']['current']['type']=='BOOKING'))
				{?>
                     <?php echo Portal::language('customer_name');?>: <?php echo $this->map['rooms']['current']['reservations']['current']['customer_name'];?> &#13
                     <?php echo Portal::language('traveller_name');?>: <?php echo $this->map['rooms']['current']['reservations']['current']['traveller_name'];?> &#13
                     <?php echo Portal::language('price');?>: <?php echo $this->map['rooms']['current']['reservations']['current']['price'];?> &#13
                     <?php echo Portal::language('room_note');?>: <?php echo $this->map['rooms']['current']['reservations']['current']['note'];?> &#13
                     <?php echo $this->map['rooms']['current']['reservations']['current']['arrival_time'];?> - <?php echo $this->map['rooms']['current']['reservations']['current']['departure_time'];?> &#13
                     <?php echo Portal::language('group_note');?>: <?php echo $this->map['rooms']['current']['reservations']['current']['group_note'];?>
                  <?php }else{ ?>
                     <?php echo Portal::language('room_note');?>: <?php echo $this->map['rooms']['current']['reservations']['current']['note'];?> &#13
                     <?php echo $this->map['rooms']['current']['reservations']['current']['arrival_time'];?> - <?php echo $this->map['rooms']['current']['reservations']['current']['departure_time'];?>
                 
				<?php
				}
				?>
             ">
            <?php 
				if(($this->map['rooms']['current']['reservations']['current']['type']=='BOOKING'))
				{?>
            <?php echo $this->map['rooms']['current']['reservations']['current']['customer_name'];?>
            <?php echo $this->map['rooms']['current']['reservations']['current']['booker'];?>
             <?php }else{ ?>
            <?php echo $this->map['rooms']['current']['reservations']['current']['status'];?>
            
				<?php
				}
				?>
        </div>
    <?php }}unset($this->map['rooms']['current']['reservations']['current']);} ?>
    <?php }}unset($this->map['rooms']['current']);} ?>
</div>
<div id="ToolNav" style="display: none;">
    <ul>
        <li>Tool</li>
        <li id="ToolNav-AsignRoom" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-check-square-o"></i> AssignRoom</li>
        <li id="ToolNav-UnAsignRoom" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-check-square-o"></i> UnAssignRoom</li>
        <li id="ToolNav-FlatCheckIn" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-bolt"></i> Flat CheckIn</li>
        <li id="ToolNav-AddRec" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-plus"></i> Add Reservation</li>
        <li id="ToolNav-EditRec" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-edit"></i> Edit Reservation</li>
        <li id="ToolNav-AddExtra" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-taxi"></i> Add Extra-Service</li>
        <li id="ToolNav-AddExtraRoom" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-bed"></i> Add Extra-Room</li>
        <li id="ToolNav-AddMinibar" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-archive"></i> Add Minibar</li>
        <li id="ToolNav-AddLaundry" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-leaf"></i> Add Laundry</li>
        <li id="ToolNav-AddEquipment" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-television"></i> Add Equipment</li>
        <?php if(User::can_edit($this->get_module_id('GrandModeratorChangeRoomStatus'),ANY_CATEGORY)){ ?>
        <li id="ToolNav-RepairStatus" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-square"></i> Repair Status</li>
        <li id="ToolNav-UnRepairStatus" class="ToolNav" onclick="ToolAction(this);"><i class="fa fa-fw fa-square-o"></i> UnRepair Status</li>
        <?php } ?>
    </ul>
</div>
<div id="AjaxLightboxContent" style="display: none;">
    <div id="AjaxLightboxContentBody">
        <div id="AjaxLightboxContentHeader"><?php echo Portal::language('confirmed_room_change');?></div>
        <div id="AjaxLightboxContentMain">
            
        </div>
        <div id="AjaxLightboxContentFooter">
            <div id="AjaxLightboxContentButtonAction" class="AjaxLightboxContentButton f-left" onclick="ChangeRoomDrop();"><?php echo Portal::language('perform');?></div>
            <div id="AjaxLightboxContentButtonCancel" class="AjaxLightboxContentButton f-right" onclick="jQuery('#AjaxLightboxContentMain').html('');jQuery('#AjaxLightboxContent').css('display','none');set_layout();"><?php echo Portal::language('cancel');?></div>
        </div>
    </div>
</div>
<div id="AjaxLoadingData" style="display: none;">
    <img src="packages/hotel/packages/reception/modules/MonthlyRoomReportNew/loading.gif" />
</div>
<script>
    get_started();
    var $block_id = '<?php echo Module::block_id(); ?>';
    var $room_level_js = <?php echo $this->map['room_level_js'];?>;
    var default_checkin_time = '<?php echo CHECK_IN_TIME; ?>';
    var default_checkout_time = '<?php echo CHECK_OUT_TIME; ?>';
    var $on_off_hightlight_hover = 0; // off
    var $header_width = 100;
    var $header_height = 50;
    var $border_size = 1;
    var $total_date = <?php echo $this->map['total_date'];?>;
    var $total_room = <?php echo $this->map['total_room'];?>;
    var $items_width = 0;
    var $items_height = 0;
    var $TimeLine_width = 0;
    var $TimeNow = to_numeric(<?php echo Date_Time::to_time(date('d/m/Y')); ?>);
    var $last_time = <?php echo $this->map['last_time'];?>;
    //console.log($TimeNow);
    set_layout();
    jQuery("#TimeLine").css('display','');
    
    /** draggable UI -- */
    jQuery('#ToolNav').draggable({
        containment: '#TimeLine',
        cursor: 'move',
        snap: 'body'
    });
    /** change room **/
    
    jQuery('.rooms_reservations_occupied_drag').draggable({
        axis: "y", // constrain
        containment: '#TimeLine',
        cursor: 'move',
        snap: 'body',
        start: handleDragStart,
        stop: handleDragStop
    });
    jQuery('.rooms_reservations_booked_drag').draggable({
        containment: '#TimeLine',
        cursor: 'move',
        snap: 'body',
        start: handleDragStart,
        stop: handleDragStop
    });
    function handleDragStop( event, ui ) {
        if(jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED-NOT-ASIGN'){
            alert('Dat phong chua duoc gan so phong. Ban can chon phong!');
            set_layout();
            return false;
        }else if(jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED-DO-NOT-MOVE'){
            alert('Dat phong dang duoc giu phong, ban khong the doi phong nay!');
            set_layout();
            return false;
        }
        jQuery("#AjaxLoadingData").css('display','');
        //console.log( "Drag Stop! Offset: (Left: " + parseInt( ui.position.left ) + ", Top: " + parseInt( ui.position.top) + ")\n");
        $LocationRoomRow = 0;
        $LocationRoomCol = 0;
        /** set Y **/
        //if(jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED' || jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED-NOT-ASIGN'){
            $CheckDrop = false;
            $DropY = ui.position.top + ($items_height/2);
            for(var $i=2; $i<=($total_room+1); $i++){
                $SetTop = ($i-2)*$items_height + $header_height;
                $SetBottom = $SetTop + $items_height;
                if($DropY>=$SetTop && $DropY<=$SetBottom){
                    $CheckDrop = true;
                    jQuery(".rooms_items").each(function(){
                        $LocationRoom = jQuery("#"+this.id).attr('location').split(',');
                        if($LocationRoom[0]==$i){
                            $LocationRoomRow = $LocationRoom[0];
                            $LocationRoomCol = $LocationRoom[1];
                            $DropId = this;
                            jQuery("#"+ui.helper.context.id).css('top',$SetTop+'px');
                            return false;
                        }
                    });
                }
                if($CheckDrop)
                    break;
            }
            if(!$CheckDrop){
                set_layout();
                jQuery("#AjaxLoadingData").css('display','none');
                return false;
            }
        //}
        /** xet X **/
        //if(jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED' || jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED-NOT-ASIGN'){
            $CheckDrop = false;
            $DropX = ui.position.left + (($items_width+1)/2);
            for(var $j=1; $j<=($total_date+1); $j++){
                $SetLeft = ($j-2)*($items_width+1) + $header_width;
                $SetRight = $SetLeft + ($items_width+1);
                if($DropX>=$SetLeft && $DropX<=$SetRight){
                    $CheckDrop = true;
                    jQuery(".rooms_items").each(function(){
                        $LocationRoom = jQuery("#"+this.id).attr('location').split(',');
                        if($LocationRoom[1]==$j && $LocationRoomRow!=0 && $LocationRoomRow == $LocationRoom[0]){
                            $LocationRoomCol = $LocationRoom[1];
                            $DropId = this;
                            if(jQuery("#"+ui.helper.context.id).attr('status')=='BOOKED'){
                                jQuery("#"+ui.helper.context.id).css('left',$SetLeft+'px');
                            }
                            return false;
                        }
                    });
                }
                if($CheckDrop)
                    break;
            }
        //}
        
        if(!$CheckDrop){
            set_layout();
            jQuery("#AjaxLoadingData").css('display','none');
            return false;
        }else{
            //console.log($DropId);
            /** Check Night Use **/
            $CheckNightRoomDrop = true;
            $RoomDropId = $DropId.id.split('_');
            $NightReservation = jQuery("#"+ui.helper.context.id).attr('night');
            $StatusReservation = jQuery("#"+ui.helper.context.id).attr('status');
            for($n=1;$n<=$NightReservation;$n++){
                $RoomDropIdLeft = $RoomDropId[1]++;
                //console.log($RoomDropId[0]+'_'+$RoomDropIdLeft);
                if(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft) 
                && to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('use'))==1 
                && to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('is_use'))==1)
                {
                    //console.log('xxx');
                    if(to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('time'))>=to_numeric(jQuery("#"+ui.helper.context.id).attr('time_in_reset'))
                    && to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('time'))<=to_numeric(jQuery("#"+ui.helper.context.id).attr('time_out')) 
                    && jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('room_id')==jQuery("#"+ui.helper.context.id).attr('room_id')
                    && ($StatusReservation=='BOOKED')
                    ){
                        //ok
                    }else{
                        $CheckNightRoomDrop = false;
                        break;
                    }
                    
                } 
                if(($StatusReservation=='BOOKED' || $StatusReservation=='BOOKED-NOT-ASIGN') && to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('use'))==1){
                    if(to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('time'))>=to_numeric(jQuery("#"+ui.helper.context.id).attr('time_in_reset'))
                    && to_numeric(jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('time'))<=to_numeric(jQuery("#"+ui.helper.context.id).attr('time_out'))
                    && jQuery("#"+$RoomDropId[0]+'_'+$RoomDropIdLeft).attr('room_id')==jQuery("#"+ui.helper.context.id).attr('room_id')
                    ){
                        //ok
                    }else{
                        $CheckNightRoomDrop = false;
                        break;
                    }
                }
            }
            if(!$CheckNightRoomDrop){
                set_layout();
                jQuery("#AjaxLoadingData").css('display','none');
                return false;
            }else{
                // change Room
                var $content_change_room = '<table style="width: 100%; color: #1297FB;">'; 
                    $content_change_room += '<tr style="height: 30px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;">Recode: <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('reservation_id')+'</b></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 60px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;"><?php echo Portal::language('room');?> <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('room_name')+'</b> <?php echo Portal::language('from_date');?> <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('arrival_time')+'</b> <?php echo Portal::language('to_date');?> <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('departure_time')+'</b></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 60px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;"><?php echo Portal::language('change_room_to');?> <b style="color: #1297FB;">'+jQuery("#"+$DropId.id).attr('room_name')+'</b> <?php echo Portal::language('start_date');?> <b style="color: #1297FB;">'+jQuery("#"+$DropId.id).attr('day')+'</b></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 30px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;"><?php echo Portal::language('note');?></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 30px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;"><textarea id="note_change_room" style="width: 100%; height: 50px; background: none; border: 1px solid #555555; color: #1297FB;"></textarea></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 30px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;"><input type="checkbox" id="use_old_price" value="use_old_price" /> use old price</td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<input type="hidden" id="ChangeRoom_r_r_id" value="'+jQuery("#"+ui.helper.context.id).attr('reservation_room_id')+'" />'; 
                    $content_change_room += '<input type="hidden" id="ChangeRoom_room_id" value="'+jQuery("#"+$DropId.id).attr('room_id')+'" />';
                    $content_change_room += '<input type="hidden" id="ChangeRoom_status" value="'+$StatusReservation+'" />';
                    $content_change_room += '<input type="hidden" id="ChangeRoom_time_in" value="'+jQuery("#"+$DropId.id).attr('time')+'" />'; 
                $content_change_room += '</table>'; 
                
                document.getElementById('AjaxLightboxContentMain').innerHTML = $content_change_room;
                jQuery("#AjaxLoadingData").css('display','none');
                jQuery('#AjaxLightboxContent').css('display','');   
            }
        }
        jQuery("#AjaxLoadingData").css('display','none');
    }
    function ChangeRoomDrop(){
        var use_old_price = jQuery('#use_old_price').is(":checked")?1:0;
        if(jQuery("#ChangeRoom_status").val()!='BOOKED' && jQuery("#ChangeRoom_status").val()!='BOOKED-NOT-ASIGN' && jQuery("#note_change_room").val().length<3){
            alert('<?php echo Portal::language('note');?> <?php echo Portal::language('must_not_be_empty');?>');
            return false;
        }else{
            jQuery("#AjaxLoadingData").css('display','');
            jQuery.ajax({
						url:"form.php?block_id="+<?php echo Module::block_id(); ?>,
						type:"POST",
                        data:{ChangeRoomDrop:1,r_r_id:jQuery("#ChangeRoom_r_r_id").val(),room_id:jQuery("#ChangeRoom_room_id").val(),start_time:jQuery("#ChangeRoom_time_in").val(),use_old_price:use_old_price,last_time:$last_time,note_change_room:jQuery("#note_change_room").val()},
						success:function(html)
                        {
                            jQuery("#AjaxLoadingData").css('display','none');
                            console.log(html);                     
                            if(html != 'success')
                            {
                                alert(html);
                                set_layout();
                                jQuery('#AjaxLightboxContent').css('display','none');   
                            }
                            else 
                            {
                                window.location.reload();
                            }
						}
			});
        }
        
    }
    function handleDragStart( event, ui ) {
        jQuery("#"+ui.helper.context.id).css('z-index','999999');
        //console.log( "Drag started! Offset: (Left: " + parseInt( ui.position.left ) + ", Top: " + parseInt( ui.position.top) + ")\n");
    }
    /** end change room **/
    /** -- end draggable UI */
    
    jQuery(window).scroll(function(){
        var $TimeLineTop = jQuery("#TimeLine").offset().top;
        if(jQuery(this).scrollTop()>$TimeLineTop){ 
            //jQuery("#header").removeClass('header_position');
            //jQuery("#header").addClass('header_position-fixed');
            jQuery("#header-fixed").css('display','');
            //jQuery("#header").css('left',$TimeLineLeft+'px');
        }else{ 
            //jQuery("#header").removeClass('header_position-fixed');
            //jQuery("#header").addClass('header_position');
            jQuery("#header-fixed").css('display','none');
            //jQuery("#header").css('left','');
        }
    });
    
    jQuery(window).resize(function(){
        set_layout();
    });
    
    jQuery(".rooms_items").hover(function(){
            if($on_off_hightlight_hover==1)
            {
                $location = this.lang.split(',');
                $row_items = $location[1];
                $col_items = $location[2];
                jQuery(".items_row_"+$row_items).addClass('bg_rooms_day_hover');
                jQuery(".items_col_"+$col_items).addClass('bg_rooms_day_hover');
            }
            
        }, function(){
            $location = this.lang.split(',');
            $row_items = $location[1];
            $col_items = $location[2];
            jQuery(".items_row_"+$row_items).removeClass('bg_rooms_day_hover');
            jQuery(".items_col_"+$col_items).removeClass('bg_rooms_day_hover');
    });
    jQuery(".rooms_reservations").hover(function(){
            if($on_off_hightlight_hover==1)
            {
                $location = this.lang.split(',');
                $row_items = $location[1];
                $col_items = $location[2];
                jQuery(".items_row_"+$row_items).addClass('bg_rooms_day_hover');
                jQuery(".items_col_"+$col_items).addClass('bg_rooms_day_hover');
            }
        }, function(){
            $location = this.lang.split(',');
            $row_items = $location[1];
            $col_items = $location[2];
            jQuery(".items_row_"+$row_items).removeClass('bg_rooms_day_hover');
            jQuery(".items_col_"+$col_items).removeClass('bg_rooms_day_hover');
    });
</script>
