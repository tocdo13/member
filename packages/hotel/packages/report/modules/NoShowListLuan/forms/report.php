<?php
class ReportNoShowList extends Form
{
    function ReportNoShowList()
    {
        Form::Form('ReportNoShowList');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
		$cond ='';
        $this->day = date('d/m/Y');
        $day_orc = Date_Time::to_orc_date($this->day);
        
        $cond = ' and reservation_room.arrival_time < \''.$day_orc.'\' ';
        
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
						reservation_room.status = \'BOOKED\'  '.$cond.'
					GROUP BY reservation_room.id
                    ORDER BY reservation_room.id DESC
					');
                    
        //System::debug($count_traveller);            
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id Desc) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    room.name as room_name,
                    room_type.brief_name as room_type_name,
                    room_type.price,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.name_'.Portal::language().' as nationality,
                    reservation_room.adult || \'/\' || reservation_room.child as pax, 
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_room.time_in, reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_traveller.id as folio,
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
                    inner join room on reservation_room.room_id = room.id
                    inner join room_type on room.room_type_id = room_type.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                where 
                    reservation_room.status = \'BOOKED\' '.$cond.'
                order by 
                    reservation_room.id DESC';
        
        //echo $sql  ;          
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
        //in
		$this->print_all_pages($report,$count_traveller);
    }
    
    function print_all_pages(&$report,$count_traveller)
	{   
        //Khởi tạo ban đầu 
        $summary = array(
        	'room_count'=>0,
            'real_room_count'=>0,
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
                $summary['real_total_page']=count($pages);
            	foreach($pages as $page_no=>$page)
            	{
                    //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   if(isset($page[$key]['stt']))
                       {
                            //Số khách thực sự sau khi lọc qua các yêu cầu
                            $summary['real_room_count']++;
                       }
                	} 
            	}
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
        $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_traveller'=>$count_traveller
        	)
		);
	}
    
    function prase_layout_default($summary,$count_traveller)
    {
    	$this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_traveller'=>$count_traveller
    		)
    	);
    }
}

?>