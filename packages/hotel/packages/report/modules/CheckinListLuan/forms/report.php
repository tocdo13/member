<?php
class ReportCheckinList extends Form
{
    function ReportCheckinList()
    {
        Form::Form('ReportCheckinList');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('skins/default/report.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');    
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
		$cond ='';
        if(Url::get('date'))
            $this->date = Url::get('date');
        else
        {
            $this->date = date('d/m/Y');
            $_REQUEST['date'] = date('d/m/Y');
        }
        $date_orc = Date_Time::to_orc_date($this->date);
        $cond .= ' and reservation_room.arrival_time = \''.$date_orc.'\' ' ;
        $time = Date_Time::to_time($this->date);//+(6*3600);
        
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
        
        //Đếm số khách trong phòng theo res_id, phòng ko có khách -> = 0 
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
						1 >0 '.$cond.' 
                        and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                        and reservation_room.time_in >= \''.$time.'\'  and reservation_room.time_in <= \''.($time+86399).'\'
                        and 
                        (
                            --th phong bt
                            (
                                (
                                    --th phong binh thuong
                                    (
                                        reservation_room.change_room_from_rr is null 
                                        and reservation_room.change_room_to_rr is null 
                                    )
                                    or
                                    --th chang dau
                                    (
                                        reservation_room.change_room_from_rr is null 
                                        and reservation_room.change_room_to_rr is not null 
                                        and reservation_room.departure_time!=\''.$date_orc.'\'
                                    )
                                )
                            )
                            or
                            (
                                reservation_room.old_arrival_time >= \''.$time.'\'  and reservation_room.old_arrival_time <= \''.($time+86399).'\'
                                and 
                                (
                                    --th chang giua
                                    (
                                        reservation_room.change_room_from_rr is not null 
                                        and reservation_room.change_room_to_rr is not null 
                                        and reservation_room.departure_time!=\''.$date_orc.'\'
                                    )
                                    or
                                    --th chang cuoi
                                    (
                                        reservation_room.change_room_from_rr is not null 
                                        and reservation_room.change_room_to_rr is null 
                                    )
                                )
                            )
                        )
					GROUP BY reservation_room.id
					');
        //SYSTEM::debug($count_traveller);
        
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id desc ) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    --room.name as room_name,
                    case 
                      when room.name is NULL then reservation_room.temp_room
                    else  
                      room.name
                    end as room_name ,
                    room_level.name as room_level,
                    room_level.brief_name as brief_name,
					CASE
						WHEN reservation_room.net_price=1
						THEN reservation_room.price
						ELSE
							(reservation_room.price + (reservation_room.price*reservation_room.service_rate*0.01) + (reservation_room.price + (reservation_room.price*reservation_room.service_rate*0.01))*reservation_room.tax_rate*0.01)
                    END as price,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.name_'.Portal::language().' as nationality,
                    country.code_1 as country_code_1,
                    reservation_room.adult, 
                    NVL(reservation_room.child , 0) + NVL(reservation_room.child_5,0) as child,  
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_room.time_in, reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_traveller.id as folio,
                    reservation_traveller.flight_code,
                    reservation_traveller.flight_arrival_time,
                    reservation.note as reservation_note,
                    reservation_room.note as note_room,
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
                    inner join room_level on room.room_level_id = room_level.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                    
                Where 
                    (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                    and reservation_room.time_in >= \''.$time.'\'  and reservation_room.time_in <= \''.($time+86399).'\'
                    and 
                    (
                        --th phong bt
                        (
                            (
                                --th phong binh thuong
                                (
                                    reservation_room.change_room_from_rr is null 
                                    and reservation_room.change_room_to_rr is null 
                                )
                                or
                                --th chang dau
                                (
                                    reservation_room.change_room_from_rr is null 
                                    and reservation_room.change_room_to_rr is not null 
                                    and reservation_room.departure_time!=\''.$date_orc.'\'
                                )
                            )
                        )
                        or
                        (
                            reservation_room.old_arrival_time >= \''.$time.'\'  and reservation_room.old_arrival_time <= \''.($time+86399).'\'
                            and 
                            (
                                --th chang giua
                                (
                                    reservation_room.change_room_from_rr is not null 
                                    and reservation_room.change_room_to_rr is not null 
                                    and reservation_room.departure_time!=\''.$date_orc.'\'
                                )
                                or
                                --th chang cuoi
                                (
                                    reservation_room.change_room_from_rr is not null 
                                    and reservation_room.change_room_to_rr is null 
                                )
                            )
                        )
                    )
                    '.$cond.' 
                    and (room_level.is_virtual=0 or room_level.is_virtual IS NULL)
                order by reservation_room.id desc ';
        
        //echo $sql;
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        
        /*System::debug($report->items);*/
        //Đánh số tt theo mã res_id
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
        //System::debug($report->items);
        //in
		$this->print_all_pages($report,$count_traveller);
    }
    
    function print_all_pages(&$report,$count_traveller)
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
            //Nếu có stt tức là có 1 phòng mới :
            if(isset($report->items[$key]['stt']))
            {
                //echo $report->items[$key]['stt'];
                //Đếm số phòng
            	$summary['room_count']++;
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 1, nếu đếm theo khách thì reset về 0 va tang so trang len 1
            if($count>$summary['line_per_page'])
        	{
        		$count = 1;
        		$summary['total_page']++;
        	}
            //In ra bắt đầu từ trang số 1
            $pages[$summary['total_page']][$key] = $item;	
        }
        //System::debug($summary);
        //System::debug($pages);
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
                //page_no : so trang, $page: cac ban ghi trong trang do
            	foreach($pages as $page_no=>$page)
            	{
                    //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   if(isset($page[$key]['stt']))
                       {
                            //Số phòng thực sự sau khi lọc qua các yêu cầu
                            $summary['real_room_count']++;
                            $summary['real_total_money'] += $page[$key]['price'];
                            $summary['real_night'] += $page[$key]['night'];
                            $summary['real_num_people'] += $page[$key]['adult'];
                            $summary['real_num_child'] += $page[$key]['child'];
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
	   //tieubinh edit
      $result = array();
      if(count($items)>0){
      foreach($items as $k=>$val){
        $room_name =trim($val['room_name']);
        $result[$val['reservation_id']]['id']= $val['reservation_id'];
        $result[$val['reservation_id']]['reservation_id']= $val['reservation_id'];
        $result[$val['reservation_id']]['note']= $val['note'];
        $result[$val['reservation_id']]['reservation_room_code']= $val['reservation_room_code'];
        $result[$val['reservation_id']]['child'][$room_name]['id']= $val['room_name'];
        $result[$val['reservation_id']]['child'][$room_name]['room_name']= $val['room_name'];
        $result[$val['reservation_id']]['child'][$room_name]['brief_name']= $val['brief_name'];
        if(isset($val['stt'])){
            $result[$val['reservation_id']]['child'][$room_name]['price']= $val['price'];
            $result[$val['reservation_id']]['child'][$room_name]['adult']= $val['adult'];
            $result[$val['reservation_id']]['child'][$room_name]['night']= $val['night'];
            $result[$val['reservation_id']]['child'][$room_name]['child']= $val['child']?$val['child']:0;
        }
        $result[$val['reservation_id']]['child'][$room_name]['note_room']= $val['note_room'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['id']= $val['id'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['fullname']= $val['fullname'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['traveller_id']= $val['traveller_id'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['country_code_1']= $val['country_code_1'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['time_in']= $val['time_in'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['time_out']= $val['time_out'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['flight_code']= $val['flight_code'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['flight_arrival_time']= $val['flight_arrival_time'];
        }
      }
      foreach($result as $k=>$val){
            $j=0;$i=0;
            foreach($val['child'] as $key=>$value){
                $c = count($value['childrend']);
                $i += $c ;
                $j = $i;
                $result[$k]['child'][$key]['count']= $c;
            }
            $result[$k]['child']['count']= $i;
            $i=0;
            $result[$k]['count'] = $j ;             
      }
      //System::debug($result);die;
       //end tieubinbnh
        $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$result,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'date'=>$this->date,
                'count_traveller'=>$count_traveller
        	)+$this->map
		);
	}
    
    function prase_layout_default($summary,$count_traveller)
    {
    	$this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'date'=>$this->date,
                'count_traveller'=>$count_traveller
    		)+$this->map
    	);
    }
}

?>
