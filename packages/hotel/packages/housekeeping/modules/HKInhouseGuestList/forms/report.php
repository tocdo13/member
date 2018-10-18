 <?php
class ReportGuestInHouse extends Form
{
    function ReportGuestInHouse()
    {
        Form::Form('ReportGuestInHouse');
    	$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');     
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');   
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
        
        $cond =' 1 = 1 ';
        $this->day = date('d/m/Y');
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        
        $day_orc = Date_Time::to_orc_date($this->map['date']);
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
        if(Url::get('time'))
        {
            $time = $this->calc_time(Url::get('time'))+59;
            $this->map['time'] = Url::get('time');
        }
        else
        {
            $this->map['time'] = date('H').':'.date('i');            
            $time = $this->calc_time($this->map['time'])+59;
            $_REQUEST['time'] = $this->map['time'];
        }
        
        $time_view = Date_Time::to_time($this->map['date']) + $time;                                
        
                
        //Tìm kiếm trong ngày hnay
        if( Date_Time::to_time($this->map['date']) == Date_Time::to_time(date('d/m/Y')) )
        {
            
            $cond .= ' 
                        AND reservation_traveller.status is not null
                        AND traveller.first_name || \' \' || traveller.last_name != \' \'  
                        AND 
                        (
                            (reservation_room.status = \'CHECKIN\' OR reservation_room.status = \'CHECKOUT\') 
                            OR
                            (
                            
                                reservation_traveller.departure_time >= \''.$time_view.'\'
                                AND reservation_traveller.arrival_time <= \''.$time_view.'\'
                                AND (reservation_room.status = \'CHECKIN\' OR reservation_room.status = \'CHECKOUT\')
                            )
                        )
                        AND reservation_traveller.status != \'CHANGE\'                 
                    ';
        }
        else
        {
            $cond .= ' 
                        AND reservation_room.time_out >= '.$time_view.'
                        AND reservation_room.time_in <= '.$time_view.'
                        AND reservation_traveller.status is not null
                        AND traveller.first_name || \' \' || traveller.last_name != \' \'  
                        AND 
                        (
                            reservation_room.status = \'CHECKIN\'
                            OR
                            reservation_room.status = \'CHECKOUT\'
                        )
                        AND reservation_traveller.status != \'CHANGE\'                  
                    ';                                
        }
       
        $cond .= ' and (room_level.is_virtual is null or room_level.is_virtual <> 1) ';
        //System::debug($cond);
        $count_traveller = DB::fetch_all('
			SELECT 
				reservation_room.id
				,count(reservation_traveller.id) as num
			FROM
				reservation_room
				inner join reservation on reservation.id=reservation_room.reservation_id
                inner join room on reservation_room.room_id = room.id
                inner join room_level on room.room_level_id = room_level.id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller on traveller.id=reservation_traveller.traveller_id
                inner join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
			WHERE
				'.$cond.'              
			GROUP BY reservation_room.id
			');
        //System::debug($count_traveller); 

            
            
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY room.name || \'-\' || reservation_room.id ) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.name_'.Portal::language().' as nationality,
                    country.code_1 as country_code_1,
                    room_level.name as room_level,
                    room_level.brief_name as brief_name,
                    room.name as room_name,
                    CASE
                        WHEN reservation_room.net_price = 1
                        THEN room_status.change_price
                        ELSE room_status.change_price + room_status.change_price * reservation_room.service_rate/100 + ( room_status.change_price + room_status.change_price * reservation_room.service_rate/100 ) * reservation_room.tax_rate/100  
                    END as price,
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_traveller.arrival_time as time_in,
                    reservation_traveller.departure_time as time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_room.status,
                    reservation_traveller.flight_code,
                    reservation_traveller.flight_arrival_time,
                    reservation_traveller.flight_departure_time,
                    reservation_traveller.status as reservation_traveller_status, 
                    reservation.note as reservation_note,
                    DECODE(traveller.gender,0,\''.Portal::language('female').'\',\''.Portal::language('male').'\') as gender,
                    DECODE(
                    concat(tour.name, customer.name),           \'\',\'\',
                                                                customer.name, customer.name,
                                                                tour.name, \'(Tour)\' || \' \' || tour.name,
                                                                concat(tour.name, customer.name), \'(Tour)\' || \' \' || tour.name || \'-\' || customer.name,
                                                                \' \'
                    )  as note,
                    traveller.id as traveller_id,
                    traveller.note as traveller_note,
                    case 
                        when REGEXP_REPLACE(room.name,\'([0-9])\',\'\') is null
                        then to_number(room.name)
                    end as number_room_name
                from 
                    reservation_room inner join reservation on reservation_room.reservation_id = reservation.id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                    inner join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
                where
                    '.$cond.'                 
                order by number_room_name
                
                ';
        
        //System::debug($sql);
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        //System::debug($report->items);
        
        $i = 1;
        $res_id = false;
		foreach($report->items as $key=>$item)
		{
            if($report->items[$key]['reservation_room_code'] != $res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $report->items[$key]['reservation_room_code'];
            }   
			
		}
	
		$this->print_all_pages($report,$count_traveller);
    }
    
    function print_all_pages(&$report,$count_traveller)
	{   
        //Khởi tạo ban đầu 
        $summary = array(
        	'room_count'=>0,
            'real_room_count'=>0,
            'real_night'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'real_total_guest'=>0,
            'total_price'=>0,
        );
        $summary['line_per_page'] = URL::get('line_per_page',999);
        
        $count = 0;
        $pages = array();          
        
        //duyet qua tung ban ghi      
        foreach($report->items as $key=>$item)
        {
            if(isset($report->items[$key]['stt']))
            {
                //echo $report->items[$key]['stt'];
                //Đếm số khách
            	$summary['room_count']++;
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$summary['line_per_page'])
        	{
        		$count = 1;
        		$summary['total_page']++;
        	}
            //In ra bắt đầu từ trang số 1
            $pages[$summary['total_page']][$key] = $item;	
        }
        
        //Nếu có dữ liệu từ câu truy vấn
        if(sizeof($pages)>0)
        {
            $summary['total_page'] = sizeof($pages);
            //Neu muon xem tu trang bao nhieu
            if(Url::get('start_page')>1)
            {
                $summary['start_page'] = Url::get('start_page');
                //Xoa bo cac phan tu trong mang ma co key (o day la page < start page)
                for($i = 1; $i< $summary['start_page']; $i++)
                    unset($pages[$i]);
            }
            //Neu muon xem toi da bao nhieu trang
            if(Url::get('no_of_page'))
            {
                //muon xem bao nhieu trang ?
                $summary['no_of_page'] = Url::get('no_of_page');
                //lay ra trang bat dau dc in (neu muon xem tu trang 5 thì se tra ve 5)
                $arr = array_keys($pages);
                if(!empty($arr))
                {
                    $begin = $arr['0'];
                    //trang cuoi cung dc in
                    $end = $begin + $summary['no_of_page'] - 1;
                    //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
                    for($i = $summary['total_page']; $i> $end; $i--)
                        unset($pages[$i]);      
                }

            }
            //Sau khi lọc mà còn dữ liệu
            if(!empty($pages))
            {
                //System::debug($pages);
                $summary['real_total_page']=count($pages);
                //Số trang thực sự sau khi lọc qua các yêu cầu
            	foreach($pages as $page_no=>$page)
            	{
                    //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   if(isset($page[$key]['stt']))
                       {
                            //Số khách thực sự sau khi lọc qua các yêu cầu
                            $summary['real_room_count']++;
                            $summary['real_night'] += $page[$key]['night'];
                            $summary['total_price'] += $page[$key]['price'];
                       }
                       
                       if($page[$key]['fullname']!= ' ')
                       {
                            $summary['real_total_guest']++;
                       }
                	} 
            	}
                //System::debug($summary);
                //page that su
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary,$count_traveller);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_traveller);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_traveller);
        }
	}
	
    function print_page($items, $page_no, $real_page_no, $summary,$count_traveller)
	{
        $this->map['status_list'] = array('ALL'=>Portal::language('all'),'CHECKIN'=>'CHECK IN','CHECKOUT'=>'CHECK OUT');
        $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_traveller'=>$count_traveller
        	)+$this->map
		);
	}
    
    function prase_layout_default($summary,$count_traveller)
    {
        $this->map['status_list'] = array('ALL'=>Portal::language('all'),'CHECKIN'=>'CHECK IN','CHECKOUT'=>'CHECK OUT');
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_traveller'=>$count_traveller
    		)+$this->map
    	);
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}

?>