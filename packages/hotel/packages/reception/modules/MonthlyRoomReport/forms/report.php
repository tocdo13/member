<?php
class MonthlyRoomReportForm extends Form
{
	function MonthlyRoomReportForm()
    {
		Form::Form('MonthlyRoomReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/contextmenu/jquery.contextMenu.js');
		$this->link_css(Portal::template('core').'/css/jquery/contextMenu.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');		
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
        $this->link_js('packages/hotel/packages/reception/modules/MonthlyRoomReport/monthly_room_report.js');
	//	$this->link_css('packages/hotel/packages/reception/skins/default/css/monthy_room_report.css');
		$this->link_js('cache/data/'.str_replace('#','',PORTAL_ID).'/list_items_room.js?v='.time());
		$this->link_js('cache/data/'.str_replace('#','',PORTAL_ID).'/list_room_array.js?v='.time());
	}
	function draw()
    {
		require_once 'packages/core/includes/utils/lib/report.php';
		$total = array();
		$total_service_others = array();
		$items = array();
		if(!isset($_REQUEST['from_date']))
		{
			$_REQUEST['from_date'] = date('d/m/Y',(Date_Time::to_time(date('d/m/Y'))-86400*3)) ;	
		}
		if(!isset($_REQUEST['to_date']))
		{
			$times = Date_Time::to_time(date('d/m/Y'))+(28*86400);
			$_REQUEST['to_date'] = date('d/m/Y',$times);
		}		
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):Date_Time::to_orc_date(date('d/m/Y',Date_Time::to_time(date('d/m/Y'))-86400*3));
		$times = Date_Time::to_time(date('d/m/Y'))+(28*86400);
		//Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_month();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):Date_Time::to_orc_date(date('d/m/Y',$times));
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
		$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
		$this->map['from_time'] = $time_from;
		$this->map['to_time'] = $time_to;
		$month = date('m');
		$room_statuses = array();
		//--------------------------Tinh trang phong---------------------------------------------------//
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		//$num_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$rooms = MonthlyRoomReportDB::get_rooms(true);
		//String::array2js($rooms);
		$days = array();
        $room_types = DB::fetch_all('select 
                                        id,
                                        price,
                                        name,color, 
                                        0 as remain 
                                    from room_level 
                                    WHERE room_level.portal_id = \''.PORTAL_ID.'\' 
                                    order by name');
		$i = 0;
		foreach($rooms as $id=>$room)
		{
			for($j = $time_from; $j<$time_to ; $j+=24*3600)
            {
				if(!isset($rooms[$id]['days']))
                {
					$rooms[$id]['days'][$j] = array();
				}
				$rooms[$id]['days'][$j]['day'] = $j;
			}
			$rooms[$id]['stt'] = $i++;
			$room_types[$room['room_level_id']]['remain'] ++;
		}

		foreach($room_types as $id=>$room_type)
		{
			$room_types[$id]['total'] = $room_type['remain'] * 31;
			//$room_types[$id]['bgcolor'] = $room_type['color'];
		}
        $this->map['room_order_list'] = array(1=>portal::language('room_name'),2=>portal::language('room_level'));
        
        $order_room_sl = Url::get('room_order');
        $this->map['room_order'] = Url::get('room_order')?Url::get('room_order'):2;
        $_REQUEST['room_order'] = $this->map['room_order'];
        //$order_room = Url::get('')
        //system::debug($_REQUEST);
        /*
        
        if(!Url::get('order_room',0))
        {
            //$_REQUEST['order_room']=2;
        }
        $order_room = Url::get('order_room');
        system::debug($order_room);
        */
        
        
        ;
        if($order_room_sl==1)
        {
            $order_room = ' ORDER BY number_room_name,room.name';    
        }
        else
        {
            $order_room = ' ORDER BY room_level.is_virtual,room_level.position,room_level.name,number_room_name,room.name';
        }
        
        
        $rooms = MonthlyRoomReportDB::get_items($date_from,$date_to,true,$order_room);
		$j= 0;
		for($i = $time_from; $i<$time_to ; $i+=24*3600)
        {
			$j++;		
		}
		$this->map['time_from'] = $time_from;
		$this->map['time_to'] = $time_to;
		$this->map['num_days'] = $j;
		$this->map['to_day'] = Date_Time::to_time(date('d/m/Y'));
		$view_all = true;
		$users = DB::fetch_all('
			SELECT
				party.user_id as id,
                party.user_id as name
			FROM
				party
				INNER JOIN account ON party.user_id = account.id
			WHERE
				party.type=\'USER\'
				AND account.is_active = 1
		');
        
        $items_org = array();
        $i = 1;
        foreach($rooms as $key => $value)
        {
            $items_org[$i++] = $value;
        }
        
        $total_rooms = MonthlyRoomReportDB::get_total_rooms($date_from,$date_to);
        //Kim tan them lây dữ liệu giống check phòng trống
        $extra_cond = ' 1>0';
		require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
		$r_r_id = '';
		$room_levels = check_availability($r_r_id,$extra_cond,$time_from,$time_to);
        $total_by_day = array();
        foreach($room_levels as $k=>$v)
        {
            if($v['is_virtual'] != 1)
            {
                if(isset($v['day_items']))
                {
                    foreach($v['day_items'] as $day=>$quantity)
                    {
                        if(isset( $total_by_day[$day]) )
                        {
                            $total_by_day[$day]['total_avai_room'] += $quantity['number_room_quantity'];
                        }
                        else
                        {
                            $total_by_day[$day] = array('total_avai_room'=>$quantity['number_room_quantity']);
                        }
                    }
                }
            }
        }
        foreach($total_rooms as $k=>$v)
        {
            $total_rooms[$k]['total_avai_room'] = 0;
            foreach ($total_by_day as $k2=>$v2)
            {
                if($total_rooms[$k]['date']==$k2)
                {
                    $total_rooms[$k]['total_avai_room'] = $v2['total_avai_room'];
                }
            }
        }
        
        //end Kim tiêm lây dữ liệu giống check phòng trống
        //System::debug($rooms);
		$this->map['room_types'] = $room_types;
		$this->map['room_types_js'] = String::array2js($room_types);
		$this->map['items'] = $rooms;
        $this->map['items_org'] = $items_org;
        $this->map['total_rooms'] = $total_rooms;
		$this->map['view_all'] = $view_all;
		$this->parse_layout('report',$this->map);		
	}
	function get_beginning_date_of_month()
    {
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_month = date('m',$time_today);
		return (Date_Time::to_orc_date('01/'.$day_of_month.'/'.date('Y',time())));
	}
	function get_end_date_of_month()
    {
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_month = date('m',$time_today);
		$num_day = cal_days_in_month(CAL_GREGORIAN,date('m',time()),date('Y',time()));
		return (Date_Time::to_orc_date($num_day.'/'.$day_of_month.'/'.date('Y',time())));
	}
}
?>
