<?php
class ReportDepartureList extends Form
{
    function ReportDepartureList()
    {
        Form::Form('ReportDepartureList');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');        
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
		$cond ='';
        $this->map['order_by_list']=array('room_name'=>Portal::language('room_name'),'recode'=>Portal::language('recode'));
        $order_by = isset($_REQUEST['order_by'])?$_REQUEST['order_by']:'room_name';
        $cond_order = '';
        if($order_by=='recode')
        {
            $cond_order='reservation.id desc';
        }
        else{
            $cond_order='room_level.is_virtual,REGEXP_REPLACE(room.name,\'([0-9])\',\'\') desc,to_number(REGEXP_REPLACE(room.name,\'([^0-9])\',\'\'))
            ';
        }
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
            reservation_room.departure_time = \''.$day_orc.'\'
            OR
            reservation_traveller.departure_date = \''.$day_orc.'\'
        )
        ';
        
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
        //--Luu Nguyen Giap comment
        $sql ='SELECT reservation.id,
                count(*) as num 
                FROM reservation_room inner join reservation on reservation.id = reservation_room.reservation_id 
                left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                left outer join traveller on traveller.id=reservation_traveller.traveller_id
                WHERE reservation_room.status != \'CANCEL\' '.$cond.'   
                GROUP BY reservation.id
                order by reservation.id desc
                ';
                
        $count_traveller = DB::fetch_all($sql);
                    
       // System::debug($count_traveller);
       //End Luu Nguyen Giap            
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id Desc) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    room.name as room_name,
                    room_level.name as room_level,
                    reservation_room.price,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.name_'.Portal::language().' as nationality,
                    reservation_room.adult, 
                    reservation_room.child, 
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_room.time_in, reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night, reservation_room.status,
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
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                    left outer join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
                where 
                    reservation_room.status != \'CANCEL\'  '.$cond.' 
                order by '.$cond_order.'
                    ';
        
        //echo $sql  ;          
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        //System::debug($report->items);
        /** Daund add */
        $record = DB::fetch_all('
                            SELECT
                                reservation_room.id
                                ,NVL(reservation_room.adult,0) as adult 
                                ,NVL(reservation_room.child,0) + NVL(reservation_room.child_5,0) as child
                                ,TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time
                                ,TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time
                                ,reservation_room.departure_time - reservation_room.arrival_time as night
                                ,reservation_room.time_in
                                ,reservation_room.time_out
                                ,reservation_room.note                                        
                                ,reservation.id as reservation_id
                                ,reservation.note as reservation_note
                                ,customer.name as customer_name
                                ,room.name as room_name
                                ,room_level.name as room_level_name
                            FROM
                                reservation_room
                                inner join reservation on reservation.id=reservation_room.reservation_id
                                inner join customer on customer.id=reservation.customer_id
                                left join room on room.id=reservation_room.room_id
                                left join room_level on room_level.id=room.room_level_id
                                left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                            WHERE
                                reservation_room.status != \'CANCEL\'
                                '.$cond.' 
                            ORDER BY
                                '.$cond_order.'
        ');
        //System::debug($record);
        $items = array();
        $stt = 1;
        $this->map['total_room'] = 0;
        $this->map['total_traveller'] = 0;
        foreach($record as $key => $value)
        {
            if(!isset($items[$value['reservation_id']]))
            {
                $items[$value['reservation_id']]['stt'] = $stt++;
                $items[$value['reservation_id']]['recode'] = $value['reservation_id'];
                $items[$value['reservation_id']]['customer_name'] = $value['customer_name'];
                $items[$value['reservation_id']]['reservation_note'] = $value['reservation_note'];
                $items[$value['reservation_id']]['count_child'] = 0;
                $items[$value['reservation_id']]['child'] = array();
            }
            $items[$value['reservation_id']]['count_child']++;
            $items[$value['reservation_id']]['child'][$key] = $value;
            $items[$value['reservation_id']]['child'][$key]['count_child'] = 0;
            $items[$value['reservation_id']]['child'][$key]['child_child'] = array();
            $this->map['total_room']++;
        }
        //System::debug($items);
        foreach($report->items as $key=>$value)
        {
            if(isset($items[$value['reservation_id']]['child'][$value['reservation_room_code']]))
            {
                $items[$value['reservation_id']]['child'][$value['reservation_room_code']]['count_child']++;
                $items[$value['reservation_id']]['child'][$value['reservation_room_code']]['child_child'][$key]['id'] = $key;
                $items[$value['reservation_id']]['child'][$value['reservation_room_code']]['child_child'][$key]['fullname'] = $value['fullname'];
                $items[$value['reservation_id']]['child'][$value['reservation_room_code']]['child_child'][$key]['nationality'] = $value['nationality'];
                if($value['reservation_traveller_id'] != '')
                {
                    $this->map['total_traveller']++;
                }
                if($items[$value['reservation_id']]['child'][$value['reservation_room_code']]['count_child']>1)
                {
                    $items[$value['reservation_id']]['count_child']++;
                }
            }
            
        }
        //System::debug($this->map['total_traveller']);
        //System::debug($items);
        $this->map['items'] = $items;
        $this->map['day'] = $this->day;
        $this->parse_layout('report', $this->map);
        //System::debug($items);
        /*$i = 1;
        $res_id = false;
        //Luu Nguyen Giap add column stt and gom nhom ma dat, ten cong ty 
        $dem =array();//dem so dong cho tung reservation_room_code va cung khoang thoi gian
        $company_id = false;
        $count =0;
		foreach($report->items as $key=>$item)
		{
            if($report->items[$key]['reservation_id'] != $res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $report->items[$key]['reservation_id'];
                if($i!=2)
                {
                   array_push($dem,$count) ; 
                }  
                $count=1; 
                $company_id = $report->items[$key]['note'];
            }
            else
            {
                if($report->items[$key]['note']!=$company_id)
                {
                    array_push($dem,$count) ;
                    $count=1;
                    $night_id = $report->items[$key]['note'] ;
                }
                else
                {
                    $count++;
                }
            }   
			
		}
        //in
        array_push($dem,$count);
        $flag = false;
        $k=0; $i=0;
        foreach($report->items as $key=>$item)
        {
             if($flag == false)
             {
                $k = $dem[$i];
                $k--;
                if($k>0)
                {
                    $flag = true;
                } 
                $report->items[$key]['row_note'] = $dem[$i];
                $i++;
             }
             else
             {
                 if($k>0)
                 {
                      $report->items[$key]['row_note'] =0;
                      $k--;
                      if($k==0)
                      {
                          $flag = false;
                      }
                 }
             }
        }*/
       // System::debug($report->items);
        //End Luu Nguyen Giap
		//$this->print_all_pages($report,$count_traveller,$order_by);
    }
    
    /*function print_all_pages(&$report,$count_traveller,$order_by)
	{   
        //Khởi tạo ban đầu 
        $summary = array(
        	'room_count'=>0,
            'real_room_count'=>0,
            'real_total_money'=>0,
            'real_night'=>0,
            'real_num_people'=>0,
            'real_num_child'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>999,
            'start_page' =>1,
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
                //Số trang thực sự sau khi lọc qua các yêu cầu
                $summary['real_total_page'] = sizeof($pages);
            	foreach($pages as $page_no=>$page)
            	{
                    //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   if(isset($page[$key]['reservation_id']))
                       {
                            //Số khách thực sự sau khi lọc qua các yêu cầu
                            $summary['real_room_count']++;
                            $summary['real_total_money'] += $page[$key]['price'];
                            $summary['real_night'] += $page[$key]['night'];
                           // $summary['real_num_people'] += $count_traveller[$page[$key]['reservation_room_code']]['num']?$count_traveller[$page[$key]['reservation_room_code']]['num']:$page[$key]['adult'];
                            $summary['real_num_child'] += $page[$key]['child'];
                       }
                	} 
            	}
                //page that su
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary,$count_traveller,$order_by);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_traveller,$order_by);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_traveller,$order_by);
        }
	}
	
    function print_page($items, $page_no, $real_page_no, $summary,$count_traveller,$order_by)
	{
        if($order_by=='recode')
        {
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
        else
        {
            $this->parse_layout('report_room',
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
        
	}
    function prase_layout_default($summary,$count_traveller,$order_by)
    {
    	$this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_traveller'=>$count_traveller
    		)+$this->map
    	);
    }*/
}

?>