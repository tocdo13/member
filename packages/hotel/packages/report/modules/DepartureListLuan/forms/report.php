<?php
class ReportDepartureList extends Form
{
    function ReportDepartureList()
    {
        Form::Form('ReportDepartureList');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
                $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');       
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
		$cond ='';
        if(Url::get('date'))
        {
            $this->day = Url::get('date');
        }
        else
        {
            $this->day = date('d/m/Y');
            $_REQUEST['date'] = $this->day;     
        }
        $day_orc = Date_Time::to_orc_date($this->day);
        
        
        $cond .= ' and 
        (
            ( reservation_room.departure_time = \''.$day_orc.'\' and reservation_traveller.id is null)
            OR
            reservation_traveller.departure_date = \''.$day_orc.'\'
        )
        ';
        
        if(Url::get('recode') && trim(Url::get('recode'))!="")
        {
            $cond .= ' and (reservation_room.reservation_id='.Url::get('recode').')';
        }
        
        if (Url::get('traveller_status') == 'in')
        {
            $cond .= ' and (reservation_traveller.status = \'CHECKIN\' or reservation_room.status = \'CHECKIN\' or reservation_room.status = \'BOOKED\')';
        }
        if (Url::get('traveller_status') == 'out')
        {
            $cond .= ' and (reservation_traveller.status = \'CHECKOUT\' or reservation_room.status = \'CHECKOUT\')';
        }
        $this->map['traveller_status_list'] = array('ALL'=>Portal::language('all'),'in' => Portal::language('in'), 'out' => Portal::language('out'));
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
        $count_traveller = DB::fetch_all('
					SELECT 
						reservation_room.id
						,count(reservation_traveller.id) as num
					FROM
						reservation_room
						inner join reservation on reservation.id=reservation_room.reservation_id
						left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
						left outer join traveller on traveller.id=reservation_traveller.traveller_id
					WHERE
						reservation_room.status != \'CANCEL\'  '.$cond.' 
					GROUP BY reservation_room.id
					');
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id Desc) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    reservation.note as reservation_note,
                    room.name as room_name,
                    room_level.name as room_level,
                    reservation_room.price,
                    -- Start : Ninh thêm
                    reservation_room.room_level_id,
                    -- End : Ninh thêm
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.name_'.Portal::language().' as nationality,
                    reservation_room.adult, 
                    reservation_room.child, 
                    reservation_room.arrival_time,
                    reservation_room.departure_time, 
                    reservation_room.time_in as time_in_room,
                    reservation_room.time_out as time_out_room,
                    reservation_traveller.arrival_time as time_in, reservation_traveller.departure_time as time_out,
                    nvl(reservation_room.change_room_to_rr,0) as change_room_to_rr,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_room.status,
                    reservation_traveller.id as folio,
                    reservation_traveller.flight_code_departure,
                    reservation_traveller.flight_departure_time,
                    reservation_traveller.car_note_departure,
                    reservation.note as reservation_note,
                    reservation_traveller.note as traveller_note,
                    DECODE(
                    concat(tour.name, customer.name),           \'\',\'\',
                                                                customer.name, customer.name,
                                                                tour.name, \'(Tour)\' || \' \' || tour.name,
                                                                concat(tour.name, customer.name), \'(Tour)\' || \' \' || tour.name || \'-\' || customer.name,
                                                                \' \'
                    )  as note,
                    traveller.id as traveller_id
                from 
                    reservation_room inner join reservation on reservation.id = reservation_room.reservation_id
                    left join room on reservation_room.room_id = room.id
                    left join room_level on room.room_level_id = room_level.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                    left outer join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
                where 
                    reservation_room.status != \'CANCEL\'  '.$cond.'
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL ) 
                    and reservation_room.change_room_to_rr is null
                order by
                    reservation.id ASC,
                    room.id ASC';
        
        //echo $sql  ;          
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        if(User::is_admin())
        {
            //System::debug($sql);
            //System::debug($report->items);
        }
        $room_level = DB::fetch_all('
                                SELECT
                                    *
                                FROM
                                    room_level
        ');
        //System::debug($room_level);
        /** Start : Ninh thêm lấy những phòng booked mà chưa gán phòng **/
        foreach($report->items as $key=>$value)
        {
            if($value['room_level']=='')
            {
                foreach($room_level as $k=>$v)
                {
                    if($value['room_level_id']==$v['id'])
                    {
                        $report->items[$key]['room_level'] = $v['name'];
                    }
                }
            }
        }
        //System::debug($report->items);
       /** End : Ninh thêm lấy những phòng booked mà chưa gán phòng **/
        $i = 1;
        $res_id = false;
        $count_room = array();
		foreach($report->items as $key=>$item)
		{
		  if(!isset($count_room[$item['reservation_id']]))
          {
            $count_room[$item['reservation_id']]['id'] = $item['reservation_id'];
            $count_room[$item['reservation_id']]['num'] = 1;
          }
          else
          {
            $count_room[$item['reservation_id']]['num'] += 1;
          }
            if($report->items[$key]['reservation_room_code'] != $res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $report->items[$key]['reservation_room_code'];
            } 
            //Kimtan: xử lý nếu là phòng dayuse sẽ tính 1 đêm phòng và hiển thị là dayused
                $report->items[$key]['day_used'] = 0;
                if($item['arrival_time']==$item['departure_time'] and $item['change_room_to_rr'] == 0)
                {
                    $report->items[$key]['night']='dayused';
                    $report->items[$key]['day_used'] = 1;
                }  
			
		}
        //in
		$this->print_all_pages($report,$count_traveller,$count_room);
    }
    
    function print_all_pages(&$report,$count_traveller,$count_room)
	{   
        //Khởi tạo ban đầu 
        $summary = array(
        	'room_count'=>0,
            'real_room_count'=>0,
            'real_total_money'=>0,
            'real_night'=>0,
            'real_day_used'=>0,
            'real_num_people'=>0,
            'real_num_child'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>32,
            'no_of_page' =>50,
            'start_page' =>1,
        );
        $summary['line_per_page'] = URL::get('line_per_page',32);
        
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
                //Số trang thực sự sau khi lọc qua các yêu cầu
                $summary['real_total_page'] = sizeof($pages);
            	foreach($pages as $page_no=>$page)
            	{
                    //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   if(isset($page[$key]['stt']))
                       {
                            //Số khách thực sự sau khi lọc qua các yêu cầu
                            $summary['real_room_count']++;
                            $summary['real_total_money'] += $page[$key]['price'];
                            $summary['real_night'] += $page[$key]['night'];
                            $summary['real_day_used'] += $page[$key]['day_used'];
                            $summary['real_num_people'] += $count_traveller[$page[$key]['reservation_room_code']]['num']?$count_traveller[$page[$key]['reservation_room_code']]['num']:$page[$key]['adult'];
                            $summary['real_num_child'] += $page[$key]['child'];
                       }
                	} 
            	}
                //page that su
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary,$count_traveller,$count_room);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_traveller,$count_room);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_traveller,$count_room);
        }
	}
	
    function print_page($items, $page_no, $real_page_no, $summary,$count_traveller,$count_room)
	{
	   //System::debug($items);
//       System::debug($count_traveller);
//       System::debug($count_room);
       $count_room = array();
       $count_traveller = array();
       foreach($items as $key=>$item)
       {
            if(!isset($count_room[$item['reservation_id']]))
            {
            $count_room[$item['reservation_id']]['id'] = $item['reservation_id'];
            $count_room[$item['reservation_id']]['num'] = 1;
            }
            else
            {
            $count_room[$item['reservation_id']]['num'] += 1;
            }
            
            if(!isset($count_traveller[$item['reservation_room_code']]))
            {
            $count_traveller[$item['reservation_room_code']]['id'] = $item['reservation_room_code'];
            $count_traveller[$item['reservation_room_code']]['num'] = 1;
            }
            else
            {
            $count_traveller[$item['reservation_room_code']]['num'] += 1;
            }
       }
       //System::debug($items);
        /** Start : Ninh loại bỏ phòng BOOKED và không có tên phòng **/
        /*foreach($items as $key=>$value)
        {
            if($value['status']=='BOOKED' && empty($value['room_name']))
            {
                unset($items[$key]);
            }
        }*/
        /** End : Ninh loại bỏ phòng BOOKED và không có tên phòng **/
        $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_traveller'=>$count_traveller,
                'count_room'=>$count_room,
        	)+$this->map
		);
	}
    
    function prase_layout_default($summary,$count_traveller,$count_room)
    {
    	$this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_traveller'=>$count_traveller,
                'count_room'=>$count_room,
    		)+$this->map
    	);
    }
}

?>