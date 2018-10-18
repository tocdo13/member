    
    function get_started(){
        jQuery("#from_date").datepicker();
        jQuery("#to_date").datepicker();
    }
    
    function FunSelectAllFloor(){
        if(document.getElementById('SelectAllFloor').checked==true){
            jQuery(".SelectFloor").attr('checked',true);
        }else{
            jQuery(".SelectFloor").attr('checked',false);
        }
    }
    
    function set_layout()
    {
        $windown_width = jQuery(window).width();
        
        $items_width = Math.floor(($windown_width-$header_width-$border_size-20) / $total_date) - $border_size;
        $items_height = $items_width;
        
        $TimeLine_width = (($items_width+$border_size)*$total_date)+($header_width+$border_size);
        
        /** body **/
        jQuery("#TimeLine").css('width',$TimeLine_width+'px');
        jQuery("#TimeLine").css('height','auto');
        
        /** header **/
        jQuery("#header").css('width',$TimeLine_width+'px');
        jQuery("#header").css('height',$header_height+'px');
        
        jQuery(".header_items_title").css('width',$header_width+'px');
        jQuery(".header_items_title").css('height',$header_height+'px');
        jQuery(".header_items_title").css('line-height',$header_height+'px');
        
        jQuery(".header_items").css('width',$items_width+'px');
        jQuery(".header_items").css('height',$header_height+'px');
        jQuery(".header_items").css('line-height',($header_height/3)+'px');
        jQuery(".header_items").each(function(){
            $stt = this.lang;
            $left = $stt*($items_width+$border_size) + ($header_width+$border_size);
            jQuery(this).css('left',$left+'px');
        });
        
        /** items rooms **/
        jQuery(".rooms").css('width',$TimeLine_width+'px');
        jQuery(".rooms").css('height',$items_height+'px');
        jQuery(".rooms_items_title").css('width',$header_width+'px');
        jQuery(".rooms_items_title").css('height',$items_height+'px');
        jQuery(".rooms_items_title p").css('line-height',(($items_height/2)+5)+'px');
        jQuery(".rooms_items_title span").css('line-height',(($items_height/2)-5)+'px');
        
        jQuery(".rooms_items").css('width',$items_width+'px');
        jQuery(".rooms_items").css('height',$items_height+'px');
        jQuery(".rooms_items").css('line-height',($items_height)+'px');
        jQuery(".rooms_items").each(function(){
            $stt_arr = this.lang.split(',');
            $stt = $stt_arr[0];
            $left = $stt*($items_width+$border_size) + ($header_width+$border_size);
            jQuery(this).css('left',$left+'px');
        });
        
        jQuery(".rooms_reservations").css('height',$items_height+'px');
        jQuery(".rooms_reservations").css('line-height',($items_height/2)+'px');
        jQuery(".rooms_reservations").each(function(){
            $stt_arr = this.lang.split(',');
            $stt = $stt_arr[0];
            $left = $stt*($items_width+$border_size) + ($header_width+$border_size);
            jQuery(this).css('left',$left+'px');
            
            $top = (to_numeric(jQuery("#"+this.id).attr('row'))-2)*$items_height + $header_height;
            
            jQuery(this).css('top',$top+'px');
            jQuery(this).css('width',((($items_width+$border_size)*$stt_arr[3])-$border_size)+'px');
            jQuery("#"+this.id).css('z-index','999');
        });
    }
    
    function openmenu( obj ) {
        ResetTool();
        $type = jQuery('#'+obj.id).attr('type');
        if($type=='ROOM_RESERVATIONS') {
            resetSelectRooms();
            jQuery("#reservation_id_right_click").val(obj.id);
            $reservation_status = jQuery('#'+obj.id).attr('status');
            $arrival_time_arr = jQuery('#'+obj.id).attr('arrival_time').split('/');
            $departure_time_arr = jQuery('#'+obj.id).attr('departure_time').split('/');
            var d = new Date();
            $in_date = d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
            $arrival_time = to_numeric($arrival_time_arr[0])+'/'+to_numeric($arrival_time_arr[1])+'/'+to_numeric($arrival_time_arr[2]);
            $departure_time = to_numeric($departure_time_arr[0])+'/'+to_numeric($departure_time_arr[1])+'/'+to_numeric($departure_time_arr[2]);
            if($reservation_status=='BOOKED-NOT-ASIGN' || $reservation_status=='BOOKED-DO-NOT-MOVE' || $reservation_status=='BOOKED' || $reservation_status=='OCCUPIED' || $reservation_status=='CHECKOUT') {
                $menu_array = ["ToolNav-EditRec"];
                if($reservation_status=='BOOKED-NOT-ASIGN') {
                    if(count_date($in_date,$departure_time)>=1 || (count_date($in_date,$departure_time)>=0 && $arrival_time==$departure_time))
                        $menu_array.push("ToolNav-AsignRoom");
                }
                else if($reservation_status=='BOOKED') {
                    $menu_array.push("ToolNav-UnAsignRoom");
                }
                else if($reservation_status=='BOOKED-DO-NOT-MOVE' || $reservation_status=='BOOKED') {
                    if($in_date==$arrival_time)
                        $menu_array.push("ToolNav-FlatCheckIn");
                    if(count_date($in_date,$departure_time)>=1 || (count_date($in_date,$departure_time)>=0 && $arrival_time==$departure_time)) {
                        $menu_array.push("ToolNav-AddExtra");
                        $menu_array.push("ToolNav-AddExtraRoom");
                    }
                }
                else if($reservation_status=='OCCUPIED') {
                    if(count_date($in_date,$departure_time)>=1 || (count_date($in_date,$departure_time)>=0 && $arrival_time==$departure_time)) {
                        $menu_array.push("ToolNav-AddExtra");
                        $menu_array.push("ToolNav-AddExtraRoom");
                        $menu_array.push("ToolNav-AddMinibar");
                        $menu_array.push("ToolNav-AddLaundry");
                        $menu_array.push("ToolNav-AddEquipment");
                    }
                }
                LoadTool($menu_array,obj);
            }
            else if($reservation_status=='REPAIR' || $reservation_status=='HOUSEUSE' || $reservation_status=='CLOSE') {
                
                if($reservation_status=='REPAIR') {
                    if(count_date($in_date,$departure_time)>=1 || (count_date($in_date,$departure_time)>=0 && $arrival_time==$departure_time)) {
                        $menu_array = ["ToolNav-UnRepairStatus"];
                        LoadTool($menu_array,obj);
                    }
                } 
            }
        } 
        else {
            if(jQuery("#room_ids").val()!='')
            {
                $menu_array = ["ToolNav-AddRec","ToolNav-RepairStatus"];
                LoadTool($menu_array,obj);
            }
            else
            {
                $status_room_arr = jQuery('#'+obj.id).attr('lang').split(',');
                $status_room = to_numeric($status_room_arr[3]);
                if($status_room==0)
                {
                    $room_id = obj.id;
                    jQuery("#room_ids").val($room_id);
                    jQuery("#room_id_last_select").val($room_id);
                    jQuery("#"+$room_id).addClass('room_items_select');
                    $menu_array = ["ToolNav-AddRec","ToolNav-RepairStatus"];
                    LoadTool($menu_array,obj);
                }
            }
        }
    }
    
    function LoadTool($menu_array,obj)
    {
        jQuery(".ToolNav").css('display','none');
        for($i=0;$i<=$menu_array.length;$i++)
        {
            if(jQuery("#"+$menu_array[$i]))
                jQuery("#"+$menu_array[$i]).css('display','');
        }
        var $tool_width = to_numeric(jQuery("#ToolNav").width());
        var $tool_height = to_numeric(jQuery("#ToolNav").height());
        var $ToolNavLeft = to_numeric(event.pageX);
        var $ToolNavTop = to_numeric(event.pageY);
        var $windown_width = to_numeric(jQuery(window).width());
        var $windown_height = to_numeric(jQuery(window).height());
        
        $ToolNavTop += 20;
        if(($windown_width-$ToolNavLeft)<300)
            $ToolNavLeft -= $tool_width+10;
        else
            $ToolNavLeft += 10;
        
        jQuery("#ToolNav").css('display','');
        jQuery("#ToolNav").css('top',$ToolNavTop+'px');
        jQuery("#ToolNav").css('left',$ToolNavLeft+'px');
    }
    
    function ResetTool()
    {
        jQuery("#ToolNav").css('display','none');
    }
    
    function ToolAction(obj) {
        ResetTool();
        if(obj.id=='ToolNav-AsignRoom') {
            OpenLoadingData();
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            $room_id = jQuery('#'+$element_id).attr('room_id');
            $time_in = jQuery('#'+$element_id).attr('time_in');
            $time_out = jQuery('#'+$element_id).attr('time_out');
            jQuery.ajax({
    					url:"form.php?block_id="+$block_id,
    					type:"POST",
    					data:{toolaction:'AsignRoom',reservation_room_id:$reservation_room_id,room_id:$room_id,time_in:$time_in,time_out:$time_out,last_time:$last_time},
    					success:function(html) {
                            if(html){
                                var objs = jQuery.parseJSON(html);
                                if(to_numeric(objs['status'])==404){
                                    alert('RealTime:\n Lưu ý, Phòng đã được tài khoản '+objs['user']+' chỉnh sửa trước đó, vào lúc :'+objs['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !');
                                }
                                else if(to_numeric(objs['status'])==0) { // asign success
                                    jQuery('#'+$element_id).removeClass('BOOKED-NOT-ASIGN');
                                    jQuery('#'+$element_id).addClass('BOOKED');
                                    jQuery('#'+$element_id).attr('status','BOOKED');
                                    alert('Gán phòng thành công !');
                                    location.reload();
                                }
                                else if(to_numeric(objs['status'])==1) { // unsucess - phong da duoc gan
                                    alert('không thành công - phòng đã được gán - thao tác này không thể thực hiện !');
                                    location.reload();
                                }
                                else if(to_numeric(objs['status'])==2) { // unsucess - phong dang duoc su dung hoac dang CLOSE
                                    
                                    if(objs['recode']['status']=='CLOSE')
                                        alert('không thành công - phòng đang '+objs['recode']['status']+' !');
                                    else
                                        alert('Không thành công - xung đột với recode#'+objs['recode']['reservation_id']+' !');
                                }
                                else if(to_numeric(objs['status'])==3) { // unsucess - phong dang repair - houseuse - close
                                    alert('không thành công - phòng đang '+objs['recode']['house_status']+' !');
                                }
                                CloseLoadingData();
                            }
    					}
    		});
            
        }
        if(obj.id=='ToolNav-UnAsignRoom') {
            OpenLoadingData();
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            $room_id = jQuery('#'+$element_id).attr('room_id');
            $time_in = jQuery('#'+$element_id).attr('time_in');
            $time_out = jQuery('#'+$element_id).attr('time_out');
            jQuery.ajax({
    					url:"form.php?block_id="+$block_id,
    					type:"POST",
    					data:{toolaction:'UnAsignRoom',reservation_room_id:$reservation_room_id,room_id:$room_id,time_in:$time_in,time_out:$time_out,last_time:$last_time},
    					success:function(html) {
                            if(html){
                                var objs = jQuery.parseJSON(html);
                                if(to_numeric(objs['status'])==404){
                                    alert('RealTime:\n Lưu ý, Phòng đã được tài khoản '+objs['user']+' chỉnh sửa trước đó, vào lúc :'+objs['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !');
                                }
                                else if(to_numeric(objs['status'])==0) { // asign success
                                    jQuery('#'+$element_id).removeClass('BOOKED');
                                    jQuery('#'+$element_id).addClass('BOOKED-NOT-ASIGN');
                                    jQuery('#'+$element_id).attr('status','BOOKED-NOT-ASIGN');
                                    alert('Bỏ Gán phòng thành công !');
                                }
                                CloseLoadingData();
                            }
    					}
    		});
            
        }
        else if(obj.id=='ToolNav-FlatCheckIn') {
            OpenLoadingData();
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            $room_id = jQuery('#'+$element_id).attr('room_id');
            $time_in = jQuery('#'+$element_id).attr('time_in');
            $time_out = jQuery('#'+$element_id).attr('time_out');
            jQuery.ajax({
    					url:"form.php?block_id="+$block_id,
    					type:"POST",
    					data:{toolaction:'FlatCheckIn',reservation_room_id:$reservation_room_id,room_id:$room_id,time_in:$time_in,time_out:$time_out,last_time:$last_time},
    					success:function(html) {
                            if(html){
                                var objs = jQuery.parseJSON(html);
                                if(to_numeric(objs['status'])==404){
                                    alert('RealTime:\n Lưu ý, Phòng đã được tài khoản '+objs['user']+' chỉnh sửa trước đó, vào lúc :'+objs['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !');
                                }
                                else if(to_numeric(objs['status'])==0) { // asign success
                                    jQuery('#'+$element_id).removeClass('BOOKED');
                                    jQuery('#'+$element_id).addClass('OCCUPIED');
                                    jQuery('#'+$element_id).attr('status','OCCUPIED');
                                    alert('CheckIn phòng thành công !');
                                }
                                else if(to_numeric(objs['status'])==1) { // unsucess - phong da duoc gan
                                    alert('CheckIn Không thanh công - Trạng thái phòng đang '+bjs['recode']['status']+' !');
                                    location.reload();
                                }
                                else if(to_numeric(objs['status'])==2) { // unsucess - phong co ngay bat dau khac ngay hien tai
                                    alert('CheckIn Không thanh công - Ngày bắt đầu khác ngày hiện tại '+bjs['recode']['date']+' !');
                                }
                                else if(to_numeric(objs['status'])==3) { // unsucess - phong dang duoc su dung hoac dang CLOSE
                                    if(objs['recode']['status']=='CLOSE')
                                        alert('CheckIn Không thanh công - phòng đang '+objs['recode']['status']+' !');
                                    else
                                        alert('CheckIn Không thanh công - xung đột với Recode #'+objs['recode']['reservation_id']+' !');
                                }
                                else if(to_numeric(objs['status'])==4) { // unsucess - phong dang repair - houseuse - close
                                    alert('CheckIn Không thanh công - phòng đang '+objs['recode']['house_status']+' !');
                                }
                                CloseLoadingData();
                            }
    					}
    		});
        }
        else if(obj.id=='ToolNav-AddRec') {
            $group_room = GetRoomArray('true');
            $string_rooms = '';
            for($j in $group_room) {
                if($string_rooms=='')
                    $string_rooms = '&rooms='+$group_room[$j]['room_id']+','+$group_room[$j]['start_date']+','+$group_room[$j]['end_date'];
                else
                    $string_rooms += '|'+$group_room[$j]['room_id']+','+$group_room[$j]['start_date']+','+$group_room[$j]['end_date'];
            }
            rooms_prices = '';
            for(var s in $room_level_js) {
				rooms_prices += '&room_prices['+$room_level_js[s]['id']+']='+$room_level_js[s]['price'];
			}
            window.open('?page=reservation&cmd=add&time_in='+default_checkin_time+'&time_out='+default_checkout_time+$string_rooms+rooms_prices+'&from_room_using_status=true');
        }
        else if(obj.id=='ToolNav-EditRec') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            $reservation_id = jQuery('#'+$element_id).attr('reservation_id');
            window.open('?page=reservation&cmd=edit&id='+$reservation_id+'&r_r_id='+$reservation_room_id+'&from_room_using_status=true');
        }
        else if(obj.id=='ToolNav-AddExtra') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            window.open('?page=extra_service_invoice&type=SERVICE&cmd=add&reservation_room_id='+$reservation_room_id);
        }
        else if(obj.id=='ToolNav-AddExtraRoom') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            window.open('?page=extra_service_invoice&type=ROOM&cmd=add&reservation_room_id='+$reservation_room_id);
        }
        else if(obj.id=='ToolNav-AddMinibar') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            window.open('?page=minibar_invoice&cmd=add&reservation_room_id='+$reservation_room_id);
        }
        else if(obj.id=='ToolNav-AddLaundry') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            window.open('?page=laundry_invoice&cmd=add&reservation_room_id='+$reservation_room_id);
        }
        else if(obj.id=='ToolNav-AddEquipment') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $reservation_room_id = jQuery('#'+$element_id).attr('reservation_room_id');
            window.open('?page=equipment_invoice&cmd=add&reservation_room_id='+$reservation_room_id);
        }
        else if(obj.id=='ToolNav-RepairStatus') {
            $group_room = GetRoomArray('false');
            $string_rooms = '';
            for($j in $group_room) {
                if($string_rooms=='')
                    $string_rooms = '&rooms='+$group_room[$j]['room_id']+','+$group_room[$j]['start_date']+','+$group_room[$j]['end_date'];
                else
                    $string_rooms += '|'+$group_room[$j]['room_id']+','+$group_room[$j]['start_date']+','+$group_room[$j]['end_date'];
            }
            window.open('?page=monthly_room_report_new&cmd=repair'+$string_rooms);
        }
        else if(obj.id=='ToolNav-UnRepairStatus') {
            $element_id = jQuery("#reservation_id_right_click").val();
            $room_id = jQuery('#'+$element_id).attr('room_id');
            $arrival_time = jQuery('#'+$element_id).attr('arrival_time');
            $departure_time = jQuery('#'+$element_id).attr('departure_time');
            $string_rooms = '&rooms='+$room_id+','+$arrival_time+','+$departure_time;
            window.open('?page=monthly_room_report_new&cmd=un_repair'+$string_rooms);
        }
    }
    
    function GetRoomArray($key) {
        if($key=='true')
            $key = true;
        else
            $key = false;
        $room_ids_arr = jQuery("#room_ids").val().split(',');
        $flag_rooms = {}; 
        $group_room = {};
        $flag=0;
        $rooms = {}; 
        for(var $i=0;$i<to_numeric($room_ids_arr.length);$i++) {
            $position = jQuery("#"+$room_ids_arr[$i]).attr('position');
            $rooms[$position] =  {};
            $rooms[$position]['id'] = $position;
            $rooms[$position]['room_id'] = jQuery("#"+$room_ids_arr[$i]).attr('room_id');
            $rooms[$position]['time_prev'] = jQuery("#"+$room_ids_arr[$i]).attr('time_prev');
            $rooms[$position]['time'] = jQuery("#"+$room_ids_arr[$i]).attr('time');
            $rooms[$position]['time_next'] = jQuery("#"+$room_ids_arr[$i]).attr('time_next');
            $rooms[$position]['day_prev'] = jQuery("#"+$room_ids_arr[$i]).attr('day_prev');
            $rooms[$position]['day'] = jQuery("#"+$room_ids_arr[$i]).attr('day');
            $rooms[$position]['day_next'] = jQuery("#"+$room_ids_arr[$i]).attr('day_next');
        }
        for(var $j in $rooms) {
            $time_prev = $rooms[$j]['time_prev'];
            $time = $rooms[$j]['time'];
            $time_next = $rooms[$j]['time_next'];
            $day = $rooms[$j]['day'];
            $day_next = $rooms[$j]['day_next'];
            $room_id = $rooms[$j]['room_id'];
            
            $flag_rooms[$room_id+'_'+$time] = {
                room_id: $room_id,
                time: $time,
                day: $day,
                day_next: $day_next
            };
            
            if($flag_rooms[$room_id+'_'+$time_prev]!=undefined)
                $flag_rooms[$room_id+'_'+$time]['flag'] = $flag_rooms[$room_id+'_'+$time_prev]['flag'];
            else if($flag_rooms[$room_id+'_'+$time_next]!=undefined)
                $flag_rooms[$room_id+'_'+$time]['flag'] = $flag_rooms[$room_id+'_'+$time_next]['flag'];
            else {
                $flag++;
                $flag_rooms[$room_id+'_'+$time]['flag'] = $flag;
            }
        }
        for(var $ij in $flag_rooms)
        {
            $flag = $flag_rooms[$ij]['flag'];
            if($group_room[$flag]!=undefined) {
                if(to_numeric($flag_rooms[$ij]['time'])<to_numeric($group_room[$flag]['start_time'])) {
                    $group_room[$flag]['start_time'] = $flag_rooms[$ij]['time'];
                    $group_room[$flag]['start_date'] = $flag_rooms[$ij]['day'];
                }
                if(to_numeric($flag_rooms[$ij]['time'])>to_numeric($group_room[$flag]['end_time'])) {
                    $group_room[$flag]['end_time'] = $flag_rooms[$ij]['time'];
                    if(!$key)
                        $group_room[$flag]['end_date'] = $flag_rooms[$ij]['day'];
                    else
                        $group_room[$flag]['end_date'] = $flag_rooms[$ij]['day_next'];
                }
                
            }
            else {
                $group_room[$flag] = {
                    room_id: $flag_rooms[$ij]['room_id'],
                    start_time: $flag_rooms[$ij]['time'],
                    end_time: $flag_rooms[$ij]['time'],
                    start_date: $flag_rooms[$ij]['day']
                }
                if(!$key)
                    $group_room[$flag]['end_date'] = $flag_rooms[$ij]['day'];
                else
                    $group_room[$flag]['end_date'] = $flag_rooms[$ij]['day_next'];
            }
        }
        console.log($flag_rooms);
        return $group_room;
    }
    
    function OpenLoadingData() {
        jQuery("#AjaxLoadingData").css('display','');
    }
    
    function CloseLoadingData() {
        jQuery("#AjaxLoadingData").css('display','none');
    }
    
    function resetSelectRooms()
    {
        jQuery("#room_ids").val('');
        jQuery("#room_id_last_select").val('');
        jQuery(".rooms_items").each(function(){
            jQuery(this).removeClass('room_items_select');
        });
    }
    
    function SelectRooms( obj ) {
        ResetTool();
        $status_room_arr = jQuery('#'+obj.id).attr('lang').split(',');
        $status_room = to_numeric($status_room_arr[3]);
        if($status_room==0)
        {
            $room_id = obj.id;
            $room_ids = jQuery("#room_ids").val().split(',');
            $room_id_last_select = jQuery("#room_id_last_select").val();
            // event shift key down
            if(event.shiftKey) {
                if($room_id_last_select=='') {
                    resetSelectRooms();
                    jQuery("#room_ids").val($room_id);
                    jQuery("#room_id_last_select").val($room_id);
                    jQuery("#"+$room_id).addClass('room_items_select');
                }
                else {
                    $location_end = jQuery('#'+obj.id).attr('location').split(',');
                    $location_start = jQuery('#'+$room_id_last_select).attr('location').split(',');
                    resetSelectRooms();
                    jQuery("#room_id_last_select").val($room_id_last_select);
                    if(to_numeric($location_start[0])<to_numeric($location_end[0])) {
                        $row_start = to_numeric($location_start[0]);
                        $row_end = to_numeric($location_end[0]);
                    } else {
                        $row_start = to_numeric($location_end[0]);
                        $row_end = to_numeric($location_start[0]);
                    }
                    if(to_numeric($location_start[1])<to_numeric($location_end[1])) {
                        $col_start = to_numeric($location_start[1]);
                        $col_end = to_numeric($location_end[1]);
                    } else {
                        $col_start = to_numeric($location_end[1]);
                        $col_end = to_numeric($location_start[1]);
                    }
                    $room_ids_new = '';
                    for($i=$row_start;$i<=$row_end;$i++) {
                        for($j=$col_start;$j<=$col_end;$j++) {
                            $status_room_arr = jQuery('#'+$i+'_'+$j).attr('lang').split(',');
                            $status_room = to_numeric($status_room_arr[3]);
                            if($status_room==0) {
                                if($room_ids_new=='')
                                    $room_ids_new = $i+'_'+$j;
                                else
                                    $room_ids_new += ','+$i+'_'+$j;
                                jQuery("#"+$i+'_'+$j).addClass('room_items_select');
                            }
                            
                        }
                    }
                    jQuery("#room_ids").val($room_ids_new);
                }
                
            }
            // event ctrl key down
            else if(event.ctrlKey || event.metaKey) {
                $room_ids_new = '';
                $check = false;
                for($i=0;$i<to_numeric($room_ids.length);$i++)
                {
                    if($room_id == $room_ids[$i]) {
                        $check = true;
                        jQuery("#"+$room_id).removeClass('room_items_select');
                    }
                    else {
                        if($room_ids_new=='')
                            $room_ids_new = $room_ids[$i];
                        else
                            $room_ids_new += ','+$room_ids[$i];
                        
                        jQuery("#room_id_last_select").val($room_ids[$i]);
                    }
                }
                if(!$check) {
                    if($room_ids_new=='')
                        $room_ids_new = $room_id;
                    else
                        $room_ids_new += ','+$room_id;
                    
                    jQuery("#room_id_last_select").val($room_id);
                    jQuery("#"+$room_id).addClass('room_items_select');
                }
                jQuery("#room_ids").val($room_ids_new);
            }
            // event onthing shift and ctrl key down
            else {
                if(to_numeric($room_ids.length)==1) {
                    if($room_ids[0]==$room_id) {
                        resetSelectRooms();
                    }
                    else {
                        resetSelectRooms();
                        jQuery("#room_ids").val($room_id);
                        jQuery("#room_id_last_select").val($room_id);
                        jQuery("#"+$room_id).addClass('room_items_select');
                    }
                }
                else {
                    resetSelectRooms();
                    jQuery("#room_ids").val($room_id);
                    jQuery("#room_id_last_select").val($room_id);
                    jQuery("#"+$room_id).addClass('room_items_select');
                }
            }
        }
        else
        {
            if(!event.shiftKey && !event.ctrlKey && !event.metaKey) {
                resetSelectRooms();
            }
        }
        
    }
    
    function ValidateTime( $key,obj ) {
        var htmlid = obj.id;
        console.log(htmlid);
        if(count_date( jQuery('#from_date').val() , jQuery('#to_date').val() )<0) {
            if(htmlid!=undefined){
                if(htmlid=='from_date'){
                    var from_date = jQuery('#from_date').val().split("/");
                    from_date = from_date[1]+"/"+from_date[0]+"/"+from_date[2]; 
                    var from_time = Date.parse(from_date.toString());
                    //Cong 1 tuan le (ms nen * 1000)
                    var to_time = to_numeric(from_time) + 2592000000;
                    var to_date = new Date(to_time);
                    to_date = to_date.getDate()+"/"+(to_date.getMonth()+1)+"/"+to_date.getFullYear();
                    jQuery('#to_date').val(to_date);
                }else if(htmlid=='to_date') {
                    var from_date = jQuery('#from_date').val().split("/");
                	var to_date = jQuery('#to_date').val().split("/");
                	if((to_date[1] < from_date[1] && to_date[2] <= from_date[2]) || ( to_date[2] < from_date[2]))
                    {
                		jQuery('#from_date').val(jQuery('#to_date').val());
                	}
                    else
                    {
                		if((to_date[0] < from_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2]))
                        {
                            jQuery('#from_date').val(jQuery('#to_date').val());
                		}
                	}
                }
            }else
                alert('the start time must be less than the end time !');
        }
        else {
            if($key==true)
                MonthlyRoomReportNewForm.submit();
        }
    }
    
    function count_date( start_day , end_day ) {
        var std =start_day.split("/");
        var ed =end_day.split("/");
        return (Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2])-Date.parse(std[1]+"/"+std[0]+"/"+std[2]))/86400000;
    }
    
    function OpenLightBox($header,$content,$action_function,$cancel_function) {
        jQuery("#AjaxLightboxContentHeader").html($header);
        document.getElementById("AjaxLightboxContentMain").innerHTML = $content;
        jQuery("#AjaxLightboxContentButtonAction").attr('onclick',$action_function+'();');
        jQuery("#AjaxLightboxContentButtonCancel").attr('onclick',$cancel_function+'();');
        jQuery("#AjaxLightboxContentHeader").css('display','');
    }
    function closeLightBox() {
        jQuery("#AjaxLightboxContentHeader").html('');
        document.getElementById("AjaxLightboxContentMain").innerHTML = '';
        jQuery("#AjaxLightboxContentButtonAction").removeAttr('onclick');
        jQuery("#AjaxLightboxContentButtonCancel").removeAttr('onclick');
        jQuery("#AjaxLightboxContentHeader").css('display','none');
    }