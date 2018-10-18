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
            <li class="f-left menuIconRight"><h3 class="w3-hide-small">[[.monthly_room_report.]]</h3></li>
            <li class="f-left">[[.form.]]: <input name="from_date" type="text" id="from_date" onchange="ValidateTime(false,this);" /></li>
            <li class="f-left">[[.to.]]: <input name="to_date" type="text" id="to_date" onchange="ValidateTime(false,this);" /></li>
            <li class="f-left menuIconHide">[[.order_by.]]: <select name="order_by" id="order_by"></select></li>
            <li class="f-left menuIconHide"><div class="icon-button">[[.view_with_floor_room.]] <i class="fa fa-fw fa-caret-down" title="[[.view_with_floor_room.]]"></i></div>
                <ul title="[[.view_with_floor_room.]]">
                    <li><label><input id="SelectAllFloor" type="checkbox" onclick="FunSelectAllFloor();" /> [[.select_all.]]</label></li>
                    <!--LIST:floor-->
                        <li><label><input name="floor[[[|floor.id|]]][id]" type="checkbox" id="[[|floor.id|]]" class="SelectFloor" value="[[|floor.id|]]" <?php if(isset($_REQUEST['floor'][[[=floor.id=]]])){ echo 'checked="checked"'; } ?> /> [[|floor.id|]]</label></li>
                    <!--/LIST:floor-->
                </ul>
            </li>
            <li class="f-left"><div class="icon-button" onclick="ValidateTime(true,this);"><i class="fa fa-fw fa-search" title="[[.search.]]"></i></div></li>
            
            <li class="f-right menuIconRight"><div class="icon-button" onclick="if($on_off_hightlight_hover==1){ $on_off_hightlight_hover=0; jQuery(this).css('background','#ff4d4d'); }else{ $on_off_hightlight_hover=1; jQuery(this).css('background','#FFFFFF'); }"><i class="fa fa-fw fa-hand-pointer-o" title="[[.on_off_hightlight_hover.]]"></i></div></li>
            <li class="f-right menuIconRight"><div class="icon-button"><i class="fa fa-fw fa-th" title="[[.status_rooms.]]"></i></div>
                <ul title="[[.status_rooms.]]">
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
            <li class="f-right menuIconRight"><div class="icon-button" onclick="location.href='<?php echo Url::build('room_map');?>';"><i class="fa fa-fw fa-home" title="[[.room_map.]]"></i></div></li>
            <li class="f-right menuIconRight"><div class="icon-button" onclick="window.print();"><i class="fa fa-fw fa-print" title="[[.print.]]"></i></div></li>
            <li class="f-right menuIconRight"><div class="icon-button" onclick="location.href='<?php echo Url::build('change_language',[[=param_build_change_lang=]]);?>';"><i class="fa fa-fw fa-language" title="[[.change_language.]]"></i><!--IF:l_cond(Portal::language()==1)-->EN<!--ELSE-->VN<!--/IF:l_cond--></div></li>
            <li class="f-right menuIconRight"><div class="icon-button"><i class="fa fa-fw fa-user" title="[[.account.]]"></i></div>
                <ul title="[[.account.]]">
                    <li><a href="<?php echo Url::build('sign_out');?>"><i class="fa fa-fw fa-sign-out"></i>[[.sign_out.]]</a></li>
                    <li><a href="<?php echo Url::build('personal');?>"><i class="fa fa-fw fa-cogs"></i>[[.edit_infomation.]]</a></li>
                </ul>
            </li>
        </ul>
        <input id="room_ids" type="hidden" value="" />
        <input id="room_id_last_select" type="hidden" value="" />
        <input id="reservation_id_right_click" type="hidden" value="" />
        <input id="start_offset_left" type="hidden" value="" />
        <input id="start_offset_top" type="hidden" value="" />
    </form>
</div>

<div id="TimeLine" style="display: none; position: relative;" oncontextmenu="return false;"> <!-- oncontextmenu="openmenu(); return false;" -->
    <div id="header" class="header_position">
        <div class="header_items_title content_hidden box-scroll-hidden">[[.room.]]/[[.date.]]</div>
        <!--LIST:day-->
            <!--IF:l_cond([[=day.show_report=]]==1)-->
                <div class="header_items content_hidden" style="background: [[|day.bg_color|]]; color: [[|day.font_color|]];" lang="[[|day.stt|]]">[[|day.day|]]<br />[[|day.weekday|]]<br /><span>[[|day.occupied|]]/<?php echo [[=day.available=]]-[[=total_virtual=]]; ?></span></div>
            <!--/IF:l_cond-->
        <!--/LIST:day-->
    </div>
    <div id="header-fixed" class="header_position-fixed" style="display: none; top: 0px;">
        <div class="header_items_title content_hidden box-scroll-hidden">[[.room.]]/[[.date.]]</div>
        <!--LIST:day-->
            <!--IF:l_cond([[=day.show_report=]]==1)-->
                <div class="header_items content_hidden" style="background: [[|day.bg_color|]]; color: [[|day.font_color|]];" lang="[[|day.stt|]]">[[|day.day|]]<br />[[|day.weekday|]]<br /><span>[[|day.occupied|]]/<?php echo [[=day.available=]]-[[=total_virtual=]]; ?></span></div>
            <!--/IF:l_cond-->
        <!--/LIST:day-->
    </div>
    <?php $row = 1; $position = 0; ?>
    <!--LIST:rooms-->
    <?php $row++; ?>
    <div class="rooms" id="rooms_[[|rooms.id|]]">
        <div class="rooms_items_title content_hidden" style="background: #ffffe6; color: [[|rooms.color|]];"><p>[[|rooms.name|]]</p><span style="color: [[|rooms.color|]];">[[|rooms.room_level_name|]] - [[|rooms.room_type_name|]]</span></div>
        <?php $col = 1; ?>
        <!--LIST:rooms.child-->
            <!--IF:l_cond([[=rooms.child.show_report=]]==1)-->
            <?php $col++; ?>
                <div 
                    id="<?php echo $row.'_'.$col; ?>"
                    room_id="[[|rooms.id|]]" 
                    room_name="[[|rooms.name|]]" 
                    day_prev="[[|rooms.child.date_prev|]]" 
                    day="[[|rooms.child.date|]]" 
                    day_next="[[|rooms.child.date_next|]]" 
                    time_prev="[[|rooms.child.time_prev|]]" 
                    time="[[|rooms.child.time|]]" 
                    time_next="[[|rooms.child.time_next|]]" 
                    location="<?php echo $row.','.$col; ?>"
                    type="ROOM_ITEMS"
                    position="<?php echo $position++; ?>"
                    use="[[|rooms.child.use|]]"
                    is_use="[[|rooms.child.is_use|]]"
                    onclick="SelectRooms(this);"
                    oncontextmenu="openmenu(this); return false;"
                    class="[[|rooms.child.bg_room|]] rooms_items_drop rooms_items content_hidden items_row_[[|rooms.id|]] items_col_[[|rooms.child.stt|]]" 
                    lang="[[|rooms.child.stt|]],[[|rooms.id|]],[[|rooms.child.stt|]],[[|rooms.child.use|]]"
                    >
                </div>
            <!--/IF:l_cond-->
        <!--/LIST:rooms.child-->
    </div>
    <!--LIST:rooms.reservations-->
        <div
             id="RESERVATION_[[|rooms.reservations.id|]]" 
             room_id="[[|rooms.id|]]"
             room_name="[[|rooms.name|]]" 
             type="ROOM_RESERVATIONS" 
             status="[[|rooms.reservations.status|]]" 
             reservation_id="[[|rooms.reservations.reservation_id|]]" 
             reservation_room_id="[[|rooms.reservations.reservation_room_id|]]" 
             time_in="[[|rooms.reservations.time_in|]]" 
             time_out="[[|rooms.reservations.time_out|]]" 
             arrival_time="[[|rooms.reservations.arrival_time|]]" 
             departure_time="[[|rooms.reservations.departure_time|]]" 
             night="[[|rooms.reservations.count_night|]]"
             row="<?php echo $row; ?>" 
             oncontextmenu="openmenu(this); return false;"
             onclick="resetSelectRooms();" 
             class="[[|rooms.reservations.status|]] rooms_reservations content_hidden <?php if([[=rooms.reservations.status=]]=='OCCUPIED' && ( (Date_Time::to_time([[=rooms.reservations.departure_time=]]))>Date_Time::to_time(date('d/m/Y')) ) ){ echo 'rooms_reservations_occupied_drag draggable'; }elseif([[=rooms.reservations.status=]]=='BOOKED' OR [[=rooms.reservations.status=]]=='BOOKED-NOT-ASIGN'){ echo 'rooms_reservations_booked_drag draggable'; } ?>" 
             lang="[[|rooms.reservations.stt|]],[[|rooms.id|]],[[|rooms.reservations.stt|]],[[|rooms.reservations.count_night|]]"
             title="
                 <!--IF:l_cond([[=rooms.reservations.type=]]=='BOOKING')-->
                     [[.customer_name.]]: [[|rooms.reservations.customer_name|]] &#13
                     [[.traveller_name.]]: [[|rooms.reservations.traveller_name|]] &#13
                     [[.price.]]: [[|rooms.reservations.price|]] &#13
                     [[.room_note.]]: [[|rooms.reservations.note|]] &#13
                     [[|rooms.reservations.arrival_time|]] - [[|rooms.reservations.departure_time|]] &#13
                     [[.group_note.]]: [[|rooms.reservations.group_note|]]
                 <!--ELSE-->
                     [[.room_note.]]: [[|rooms.reservations.note|]] &#13
                     [[|rooms.reservations.arrival_time|]] - [[|rooms.reservations.departure_time|]]
                 <!--/IF:l_cond-->
             ">
            <!--IF:l_cond([[=rooms.reservations.type=]]=='BOOKING')-->
            [[|rooms.reservations.customer_name|]]
            [[|rooms.reservations.booker|]]
            <!--ELSE-->
            [[|rooms.reservations.status|]]
            <!--/IF:l_cond-->
        </div>
    <!--/LIST:rooms.reservations-->
    <!--/LIST:rooms-->
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
        <div id="AjaxLightboxContentHeader">[[.confirmed_room_change.]]</div>
        <div id="AjaxLightboxContentMain">
            
        </div>
        <div id="AjaxLightboxContentFooter">
            <div id="AjaxLightboxContentButtonAction" class="AjaxLightboxContentButton f-left" onclick="ChangeRoomDrop();">[[.perform.]]</div>
            <div id="AjaxLightboxContentButtonCancel" class="AjaxLightboxContentButton f-right" onclick="jQuery('#AjaxLightboxContentMain').html('');jQuery('#AjaxLightboxContent').css('display','none');set_layout();">[[.cancel.]]</div>
        </div>
    </div>
</div>
<div id="AjaxLoadingData" style="display: none;">
    <img src="packages/hotel/packages/reception/modules/MonthlyRoomReportNew/loading.gif" />
</div>
<script>
    get_started();
    var $block_id = '<?php echo Module::block_id(); ?>';
    var $room_level_js = [[|room_level_js|]];
    var default_checkin_time = '<?php echo CHECK_IN_TIME; ?>';
    var default_checkout_time = '<?php echo CHECK_OUT_TIME; ?>';
    var $on_off_hightlight_hover = 0; // off
    var $header_width = 100;
    var $header_height = 50;
    var $border_size = 1;
    var $total_date = [[|total_date|]];
    var $total_room = [[|total_room|]];
    var $items_width = 0;
    var $items_height = 0;
    var $TimeLine_width = 0;
    var $TimeNow = to_numeric(<?php echo Date_Time::to_time(date('d/m/Y')); ?>);
    var $last_time = [[|last_time|]];
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
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;">[[.room.]] <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('room_name')+'</b> [[.from_date.]] <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('arrival_time')+'</b> [[.to_date.]] <b style="color: #1297FB;">'+jQuery("#"+ui.helper.context.id).attr('departure_time')+'</b></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 60px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;">[[.change_room_to.]] <b style="color: #1297FB;">'+jQuery("#"+$DropId.id).attr('room_name')+'</b> [[.start_date.]] <b style="color: #1297FB;">'+jQuery("#"+$DropId.id).attr('day')+'</b></td>'; 
                    $content_change_room += '</tr>'; 
                    $content_change_room += '<tr style="height: 30px;">'; 
                        $content_change_room += '<td colspan="2" style="color: #EEEEEE;">[[.note.]]</td>'; 
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
            alert('[[.note.]] [[.must_not_be_empty.]]');
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
