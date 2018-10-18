<?php
/**
 * phòng bán: là số phòng  ở các  trạng thái book/ book not assign/ ci. co
 * phòng trống: là phòng chưa bán, trừ repair/ trừ close sale
**/
class MonthlyRoomReportNewForm extends Form
{
	function MonthlyRoomReportNewForm()
    {
		Form::Form('MonthlyRoomReportNewForm');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/font-awesome/css/font-awesome.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/monthly_room_report_reset.css');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/monthly_room_report.css');
        $this->link_js('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/jquery-ui.min.js');
        $this->link_js('packages/core/includes/js/jquery.ui.touch-punch.min.js');
        $this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/monthly_room_report.js');
        
	}
	function draw()
    {
        $this->map = array();
        $this->map['current_portal'] = DB::fetch('select account.id,party.name_1 from account inner join party on party.user_id = account.id where account.id=\''.PORTAL_ID.'\'','name_1');
        
        /** get time **/
        $from_date = Url::get('from_date')?Url::get('from_date'):date('d/m/Y',(Date_Time::to_time(date('d/m/Y'))-(1*86400)));
        $this->map['from_date'] = $from_date; $_REQUEST['from_date'] = $from_date;
        $from_time = Date_Time::to_time($from_date);
        
        $to_date = Url::get('to_date')?Url::get('to_date'):date('d/m/Y',($from_time+(30*86400)));
        $this->map['to_date'] = $to_date; $_REQUEST['to_date'] = $to_date;
        $to_time = Date_Time::to_time($to_date)+86400;
        /** order by **/
        $this->map['order_by_list'] = array('1'=>Portal::language('room'),'2'=>Portal::language('room_level'));
        $cond_order_by = Url::get('order_by')==1?'room.id,room.floor,room.position':' room_level.is_virtual,room_level.id asc,room_level.position,room_level.brief_name,room.name';
        
        /** get_list_room_level **/
        $this->map['room_level_js'] = String::array2js(MonthlyRoomReportDBNew::get_room_level());
        
        /** get_list_floor **/
        $this->map['floor'] = MonthlyRoomReportDBNew::get_floor();
        
        $cond = '1=1';
        if(isset($_REQUEST['floor'])){
            foreach($_REQUEST['floor'] as $key=>$value) {
                $cond .= $cond=='1=1'?' and (room.floor=\''.$key.'\'':' or room.floor=\''.$key.'\'';
            }
        }
        $cond .= $cond=='1=1'?'':')';
        /** get list room **/
        $room = MonthlyRoomReportDBNew::get_all_room($from_date,$to_date,$cond_order_by,$cond);
        //System::debug($room);
        
        /** lay ra tat ca booked not asign  
         * de gan duoc cac ban ghi booked not asign nhanh nhat va chinh xac
         * thuat toan:
         * lay tat ca cac phong book not asign trong khoang thoi gian xem
         * lay ngay lon nhat va ngay nho nhat trong truong hop co ca book not sign
         * muc dich: lay ra cac dat phong trong khaong thoi gian do de khong gan nham
         * 
        **/
        
        $booked_not_asign = MonthlyRoomReportDBNew::get_booked_not_asign($from_date,$to_date);
        
        //System::debug($booked_not_asign);
        /** set mix, max date **/
        $max_date = $to_date;
        $min_date = $from_date;
        
        if(sizeof($booked_not_asign)>0)
        {
            $max_date_booked_not_asign = MonthlyRoomReportDBNew::get_booked_not_asign_max_date($from_date,$to_date);
            $min_date_booked_not_asign = MonthlyRoomReportDBNew::get_booked_not_asign_min_date($from_date,$to_date);
            
            if(Date_time::to_time($max_date_booked_not_asign)>Date_Time::to_time($max_date))
                $max_date = $max_date_booked_not_asign;
            
            if(Date_time::to_time($min_date_booked_not_asign)<Date_Time::to_time($min_date))
                $min_date = $min_date_booked_not_asign;
        }
        
        /** get dat phong trong khoang thoi gian min, max **/
        $get_booking = MonthlyRoomReportDBNew::get_booking_room($min_date,$max_date);
        //System::debug($get_booking);
        /** get trang thai phong trong khoang thoi gian min, max **/
        $get_room_status = MonthlyRoomReportDBNew::get_room_status($min_date,$max_date);
        //System::debug($get_room_status);
        
        /** tinh toan cac thong so va dua ra mang default **/
        $total_room = sizeof($room);
        $this->map['total_room'] = $total_room;
        $this->map['total_date'] = (($to_time-$from_time)/86400);
        $day = array();
        $stt=0;
        /** lay mang ngay thang theo mix, max **/
        for($time = Date_Time::to_time($min_date);$time<=Date_Time::to_time($max_date);$time+=86400)
        {
            /** get content default **/
            $day[$time]['id'] = date('d/m/Y',$time);
            $day[$time]['time_prev'] = $time-86400;
            $day[$time]['time'] = $time;
            $day[$time]['time_next'] = $time+86400;
            $day[$time]['weekday'] = $this->sw_get_current_weekday($time);
            $day[$time]['day'] = date('d/m',$time);
            $day[$time]['date_prev'] = date('d/m/Y',($time-86400));
            $day[$time]['date'] = date('d/m/Y',$time);
            $day[$time]['date_next'] = date('d/m/Y',($time+86400));
            /** set color **/
            $day[$time]['font_color'] = '#171717';
            $day[$time]['bg_color'] = '#ffffe6'; // hong phai
            $day[$time]['bg_room'] = 'day-new'; // e6fff3
            $day[$time]['use'] = 0;
            $day[$time]['is_use'] = 0;
            /** set last weekend **/
            if($day[$time]['weekday']=='Sat')
                $day[$time]['font_color'] = '#3385ff'; // xanh lam
            elseif($day[$time]['weekday']=='Sun')
                $day[$time]['font_color'] = '#ff1a1a'; // do
            /** set backgruond today **/
            if(date('d/m/Y',$time)==date('d/m/Y'))
                $day[$time]['bg_color'] = '#d580ff'; // tim nhat
            if($time<Date_Time::to_time(date('d/m/Y')))
            {
                $day[$time]['use'] = 1;
                $day[$time]['bg_room'] = 'day-old'; // FFFFFF
            }
            /** set status view report **/
            $day[$time]['show_report'] = 0;
            $day[$time]['stt'] = 0;
            
            if($time>=$from_time and $time<$to_time)
            {
                $day[$time]['show_report'] = 1;
                $day[$time]['stt'] = $stt++;
            }
            
            $day[$time]['occupied'] = 0;
            $day[$time]['available'] = $total_room;
        }
        /** tao mang default cho danh sach phong **/
        $this->map['total_virtual'] = 0;
        foreach($room as $key=>$value)
        {
            $room[$key]['child'] = $day;
            $room[$key]['reservations'] = array();
            if($value['is_virtual']==1)
                $this->map['total_virtual']++;
        }
        
        /** chen du lieu trang thai buong phong vao mang **/
        if(User::id()=='developer05'){
            //System::debug($get_room_status); die;
        }
        //System::debug($get_room_status); die;
        foreach($get_room_status as $key=>$value)
        {
            if($value['from_date']==$value['to_date']) // in-date
            {
                if(isset($room[$value['room_id']]['child'][Date_Time::to_time($value['from_date'])]) ) //and Date_Time::to_time($value['from_date'])<Date_Time::to_time(date('d/m/Y'))
                {
                    $room[$value['room_id']]['child'][Date_Time::to_time($value['from_date'])]['use'] = 1;
                    $room[$value['room_id']]['child'][Date_Time::to_time($value['from_date'])]['is_use'] = 1;
                    if($room[$value['room_id']]['child'][Date_Time::to_time($value['from_date'])]['show_report']==1)
                    {
                        //$day[Date_Time::to_time($value['arrival_time'])]['occupied'] ++;
                        if(($value['house_status']=='REPAIR' OR $value['house_status']=='CLOSE') and $value['is_virtual']!=1)
                            $day[Date_Time::to_time($value['from_date'])]['available']--;
                        
                        $room[$value['room_id']]['reservations'][''.$key]['id'] = $key;
                        $room[$value['room_id']]['reservations'][''.$key]['type'] = 'HOUSE_STATUS';
                        $room[$value['room_id']]['reservations'][''.$key]['reservation_room_id'] = '';
                        $room[$value['room_id']]['reservations'][''.$key]['reservation_id'] = '';
                        $room[$value['room_id']]['reservations'][''.$key]['price'] = '';
                        $room[$value['room_id']]['reservations'][''.$key]['arrival_time'] = $value['from_date'];
                        $room[$value['room_id']]['reservations'][''.$key]['departure_time'] = $value['to_date'];
                        $room[$value['room_id']]['reservations'][''.$key]['time_in'] = $value['start_date'];
                        $room[$value['room_id']]['reservations'][''.$key]['time_out'] = $value['end_date'];
                        $room[$value['room_id']]['reservations'][''.$key]['customer_name'] = '';
                        $room[$value['room_id']]['reservations'][''.$key]['traveller_name'] = '';
                        $room[$value['room_id']]['reservations'][''.$key]['booker'] = '';
                        $room[$value['room_id']]['reservations'][''.$key]['note'] = $value['note'];
                        $room[$value['room_id']]['reservations'][''.$key]['group_note'] = '';
                        
                        $room[$value['room_id']]['reservations'][''.$key]['stt'] = $room[$value['room_id']]['child'][Date_Time::to_time($value['from_date'])]['stt'];
                        $room[$value['room_id']]['reservations'][''.$key]['count_night'] = 1;
                        $room[$value['room_id']]['reservations'][''.$key]['status'] = $value['house_status'];
                    }
                }
            }
            else
            {
                $start_time = Date_Time::to_time($value['from_date']); $end_time = Date_Time::to_time($value['to_date']);
                for($i=$start_time;$i<=$end_time;$i+=86400)
                {
                    if(isset($room[$value['room_id']]['child'][$i]))
                    {
                        $room[$value['room_id']]['child'][$i]['use'] = 1;
                        $room[$value['room_id']]['child'][$i]['is_use'] = 1;
                        if($room[$value['room_id']]['child'][$i]['show_report']==1)
                        {
                            //$day[$i]['occupied'] ++;
                            if(($value['house_status']=='REPAIR' OR $value['house_status']=='CLOSE') and $value['is_virtual']!=1)
                                $day[$i]['available']--;
                            
                            if(!isset($room[$value['room_id']]['reservations']['OCC_'.$key]))
                            {
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['id'] = $key;
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['type'] = 'HOUSE_STATUS';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['reservation_room_id'] = '';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['reservation_id'] = '';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['price'] = '';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['arrival_time'] = $value['from_date'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['departure_time'] = $value['to_date'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['time_in'] = $value['start_date'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['time_out'] = $value['end_date'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['customer_name'] = '';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['traveller_name'] = '';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['booker'] = '';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['note'] = $value['note'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['group_note'] = '';
                                
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['stt'] = $room[$value['room_id']]['child'][$i]['stt'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['count_night'] = 0;
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = $value['house_status'];
                            }
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['count_night']++;
                        }
                    }
                }
            }
        }
        if(User::id()=='developer05'){
            //System::debug($room); die;
        }
        /** chen du lieu dat phong vao mang **/
        //System::debug($get_booking);
        
        foreach($get_booking as $key=>$value)
        {
            if($value['arrival_time']==$value['departure_time']) // dayuse
            {
                if(isset($room[$value['room_id']]['child'][Date_Time::to_time($value['arrival_time'])]) )//and Date_Time::to_time($value['arrival_time'])<Date_Time::to_time(date('d/m/Y'))
                {
                    if($room[$value['room_id']]['child'][Date_Time::to_time($value['arrival_time'])]['show_report']==1)
                    {
                        if($value['is_virtual']!=1)
                        {
                            $day[Date_Time::to_time($value['arrival_time'])]['occupied'] ++;
                            $day[Date_Time::to_time($value['arrival_time'])]['available']--;
                        }
                        if($value['status']!='CHECKIN' and $value['status']!='CHECKOUT' and $room[$value['room_id']]['child'][Date_Time::to_time($value['arrival_time'])]['is_use']==0){
                            $room[$value['room_id']]['child'][Date_Time::to_time($value['arrival_time'])]['use'] = 1;
                            $room[$value['room_id']]['child'][Date_Time::to_time($value['arrival_time'])]['is_use'] = 1;
                    
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['id'] = $key;
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['type'] = 'BOOKING';
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['reservation_room_id'] = $key;
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['reservation_id'] = $value['reservation_id'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['price'] = System::display_number($value['price']);
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['arrival_time'] = $value['arrival_time'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['departure_time'] = $value['departure_time'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['time_in'] = $value['time_in'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['time_out'] = $value['time_out'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['customer_name'] = $value['customer_name'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['traveller_name'] = $value['traveller_name'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['booker'] = $value['booker'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['note'] = $value['note'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['group_note'] = $value['group_note'];
                            
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['stt'] = $room[$value['room_id']]['child'][Date_Time::to_time($value['arrival_time'])]['stt'];
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['count_night'] = 1;
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'BOOKED';
                            if($value['status']=='BOOKED')
                            {
                                if($value['do_not_move']==1)
                                    $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'BOOKED-DO-NOT-MOVE';
                            }
                            elseif($value['status']=='CHECKIN')
                            {
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'OCCUPIED';
                            }
                            elseif($value['status']=='CHECKOUT')
                            {
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'CHECKOUT';
                            }
                        }
                    }
                }
            }
            else
            {
                $start_time = Date_Time::to_time($value['arrival_time']); $end_time = Date_Time::to_time($value['departure_time']);
                for($i=$start_time;$i<$end_time;$i+=86400)
                {
                    if(isset($room[$value['room_id']]['child'][$i]))
                    {
                        $room[$value['room_id']]['child'][$i]['use'] = 1;
                        $room[$value['room_id']]['child'][$i]['is_use'] = 1;
                        if($room[$value['room_id']]['child'][$i]['show_report']==1)
                        {
                            if($value['is_virtual']!=1)
                            {
                                $day[$i]['occupied'] ++;
                                $day[$i]['available']--;
                            }
                            if(!isset($room[$value['room_id']]['reservations']['OCC_'.$key]))
                            {
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['id'] = $key;
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['type'] = 'BOOKING';
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['reservation_room_id'] = $key;
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['reservation_id'] = $value['reservation_id'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['price'] = System::display_number($value['price']);
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['arrival_time'] = $value['arrival_time'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['departure_time'] = $value['departure_time'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['time_in'] = $value['time_in'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['time_out'] = $value['time_out'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['customer_name'] = $value['customer_name'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['traveller_name'] = $value['traveller_name'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['booker'] = $value['booker'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['note'] = $value['note'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['group_note'] = $value['group_note'];
                                
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['stt'] = $room[$value['room_id']]['child'][$i]['stt'];
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['count_night'] = 0;
                                $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'BOOKED';
                                if($value['status']=='BOOKED')
                                {
                                    if($value['do_not_move']==1)
                                        $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'BOOKED-DO-NOT-MOVE';
                                }
                                elseif($value['status']=='CHECKIN')
                                {
                                    $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'OCCUPIED';
                                }
                                elseif($value['status']=='CHECKOUT')
                                {
                                    $room[$value['room_id']]['reservations']['OCC_'.$key]['status'] = 'CHECKOUT';
                                }
                            }
                            $room[$value['room_id']]['reservations']['OCC_'.$key]['count_night']++;
                        }
                    }
                }
            }
        }
        
        /** gan booked not asign **/
        
        //System::debug($booked_not_asign);
        foreach($booked_not_asign as $key=>$value)
        {
            foreach($room as $k=>$v)
            {
                if($v['room_level_id']==$value['room_level_id'] and isset($v['child'][Date_Time::to_time($value['arrival_time'])]) and $v['child'][Date_Time::to_time($value['arrival_time'])]['is_use']!=1)
                {
                    $start_time = Date_Time::to_time($value['arrival_time']); $end_time = Date_Time::to_time($value['departure_time']);
                    $check = true;
                    for($i=$start_time;$i<$end_time;$i+=86400)
                    {
                        if($v['child'][$i]['is_use']==1)
                            $check = false;
                    }
                    
                    if($check)
                    {
                        if($value['arrival_time']==$value['departure_time'])
                        {
                            if(Date_Time::to_time($value['arrival_time'])<Date_Time::to_time(date('d/m/Y')))
                            {
                                //$room[$k]['child'][Date_Time::to_time($value['arrival_time'])]['use'] = 0;
                                $room[$k]['child'][Date_Time::to_time($value['arrival_time'])]['is_use'] = 1;
                                if($room[$k]['child'][Date_Time::to_time($value['arrival_time'])]['show_report']==1)
                                {
                                    if($value['is_virtual']!=1)
                                    {
                                        $day[Date_Time::to_time($value['arrival_time'])]['occupied'] ++;
                                        $day[Date_Time::to_time($value['arrival_time'])]['available']--;
                                    }
                                    
                                    $room[$k]['reservations']['OCC_'.$key]['id'] = $key;
                                    $room[$k]['reservations']['OCC_'.$key]['type'] = 'BOOKING';
                                    $room[$k]['reservations']['OCC_'.$key]['reservation_room_id'] = $key;
                                    $room[$k]['reservations']['OCC_'.$key]['reservation_id'] = $value['reservation_id'];
                                    $room[$k]['reservations']['OCC_'.$key]['price'] = System::display_number($value['price']);
                                    $room[$k]['reservations']['OCC_'.$key]['arrival_time'] = $value['arrival_time'];
                                    $room[$k]['reservations']['OCC_'.$key]['departure_time'] = $value['departure_time'];
                                    $room[$k]['reservations']['OCC_'.$key]['time_in'] = $value['time_in'];
                                    $room[$k]['reservations']['OCC_'.$key]['time_out'] = $value['time_out'];
                                    $room[$k]['reservations']['OCC_'.$key]['customer_name'] = $value['customer_name'];
                                    $room[$k]['reservations']['OCC_'.$key]['traveller_name'] = $value['traveller_name'];
                                    $room[$k]['reservations']['OCC_'.$key]['note'] = $value['note'];
                                    $room[$k]['reservations']['OCC_'.$key]['group_note'] = $value['group_note'];
                                    
                                    $room[$k]['reservations']['OCC_'.$key]['stt'] = $room[$k]['child'][Date_Time::to_time($value['arrival_time'])]['stt'];
                                    $room[$k]['reservations']['OCC_'.$key]['count_night'] = 1;
                                    $room[$k]['reservations']['OCC_'.$key]['status'] = 'BOOKED-NOT-ASIGN';
                                }
                            }
                        }
                        else
                        {
                            for($i=$start_time;$i<$end_time;$i+=86400)
                            {
                                if(isset($room[$k]['child'][$i]))
                                {
                                    //$room[$k]['child'][$i]['use'] = 1;
                                    $room[$k]['child'][$i]['is_use'] = 1;
                                    if($room[$k]['child'][$i]['show_report']==1)
                                    {
                                        if($value['is_virtual']!=1)
                                        {
                                            $day[$i]['occupied'] ++;
                                            $day[$i]['available']--;
                                        }
                                        if(!isset($room[$k]['reservations']['OCC_'.$key]))
                                        {
                                            $room[$k]['reservations']['OCC_'.$key]['id'] = $key;
                                            $room[$k]['reservations']['OCC_'.$key]['type'] = 'BOOKING';
                                            $room[$k]['reservations']['OCC_'.$key]['reservation_room_id'] = $key;
                                            $room[$k]['reservations']['OCC_'.$key]['reservation_id'] = $value['reservation_id'];
                                            $room[$k]['reservations']['OCC_'.$key]['price'] = System::display_number($value['price']);
                                            $room[$k]['reservations']['OCC_'.$key]['arrival_time'] = $value['arrival_time'];
                                            $room[$k]['reservations']['OCC_'.$key]['departure_time'] = $value['departure_time'];
                                            $room[$k]['reservations']['OCC_'.$key]['time_in'] = $value['time_in'];
                                            $room[$k]['reservations']['OCC_'.$key]['time_out'] = $value['time_out'];
                                            $room[$k]['reservations']['OCC_'.$key]['customer_name'] = $value['customer_name'];
                                            $room[$k]['reservations']['OCC_'.$key]['traveller_name'] = $value['traveller_name'];
                                            $room[$k]['reservations']['OCC_'.$key]['booker'] = $value['booker'];
                                            $room[$k]['reservations']['OCC_'.$key]['note'] = $value['note'];
                                            $room[$k]['reservations']['OCC_'.$key]['group_note'] = $value['group_note'];
                                            
                                            $room[$k]['reservations']['OCC_'.$key]['stt'] = $room[$k]['child'][$i]['stt'];
                                            $room[$k]['reservations']['OCC_'.$key]['count_night'] = 0;
                                            $room[$k]['reservations']['OCC_'.$key]['status'] = 'BOOKED-NOT-ASIGN';
                                        }
                                        $room[$k]['reservations']['OCC_'.$key]['count_night']++;
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }
        //System::debug($room);
        $this->map['day'] = $day;
        $this->map['rooms'] = $room;
        //$this->map['rooms_js'] = String::array2js($room);
        
        /** change language **/
        $param_build_change_lang= array('href','language_id'=>((Portal::language()==1)?2:1),'ldd'=>Portal::$page['name']);
        $path = $_SERVER['REQUEST_URI'];
        $pos = strpos($path,'cmd');
        if($pos!=false)
        {
           $str = substr($path,$pos);
           $str_cmd = explode("&",$str);
           for($i=0;$i<count($str_cmd);$i++)
           {
               $s =explode("=",$str_cmd[$i]);
               $param_build_change_lang = $param_build_change_lang + array($s[0]=>$s[1]);
           }
           
           
        }
        $this->map['param_build_change_lang'] = $param_build_change_lang;
        $this->map['last_time'] = time();
		$this->parse_layout('report',$this->map);		
	}
    
    function sw_get_current_weekday($time) 
    {
        return ucfirst(substr(strtolower(date("l",$time)),0,3));
    }
    
}
?>
