 <?php
class ReportGuestInHouse extends Form
{
    function ReportGuestInHouse()
    {
        Form::Form('ReportGuestInHouse');
    	$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');     
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');   
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
        
        /** oanh add **/
        $customers = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER');
		$this->map['customer_id_list'] = array(''=>'---') + String::get_list($customers);
        $cond .=  (Url::get('customer_id')?' AND reservation.customer_id=\''.Url::sget('customer_id').'\'':'').'';
        
        /** Oanh add **/
        
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
            //Lay ca phong out qua han
            $cond .= ' 
                        AND (reservation_traveller.status = \'CHECKIN\' OR reservation_traveller.status = \'CHECKOUT\')
                        AND traveller.first_name || \' \' || traveller.last_name != \' \'
						AND reservation_traveller.departure_time > \''.$time_view.'\'
                        AND reservation_traveller.arrival_time <= \''.$time_view.'\'  
                        AND 
                        (
                            reservation_room.status = \'CHECKIN\'
                            OR
                            reservation_room.status = \'CHECKOUT\'
                            )

                        AND reservation_traveller.status != \'CHANGE\'                 
                    ';
        }
        else
        {
           
            $cond .= ' 
						AND reservation_traveller.departure_time > \''.$time_view.'\'
                        AND reservation_traveller.arrival_time <= \''.$time_view.'\'
                        AND (reservation_traveller.status = \'CHECKIN\' OR reservation_traveller.status = \'CHECKOUT\')
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

            
        $foc =  " or reservation_room.FOC is not null";
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY room.name || \'-\' || reservation_room.id ) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    reservation.booking_code,
                    reservation_room.note as reservation_room_note, -- oanh add ghi chu phong
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
                    country.name_'.Portal::language().' as nationality,
                    country.code_1 as country_code_1,
                    room_level.name as room_level,
                    room_level.brief_name as brief_name,
                    room.name as room_name,
                     CASE
                        WHEN (reservation_room.FOC_ALL = 1 '.$foc.')
                        THEN 0
                        ELSE 
                        (
                          CASE
                            WHEN reservation_room.net_price = 1
                            THEN room_status.change_price
                            ELSE room_status.change_price + room_status.change_price * reservation_room.service_rate/100 + ( room_status.change_price + room_status.change_price * reservation_room.service_rate/100 ) * reservation_room.tax_rate/100  
                          END
                        )
                    END as price,
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_traveller.arrival_time as time_in,
                    reservation_traveller.departure_time as time_out,
                    reservation_traveller.departure_date - reservation_traveller.arrival_date as night,
                    reservation_room.status,
                    reservation_traveller.flight_code,
                    reservation_traveller.flight_arrival_time,
                    reservation_traveller.flight_departure_time,
                    reservation_traveller.status as reservation_traveller_status, 
                    reservation.note as reservation_note,
                    customer_group.name as customer_group_name,
                    DECODE(traveller.gender,0,\''.Portal::language('female').'\',\''.Portal::language('male').'\') as gender,
                    DECODE(
                    concat(tour.name, customer.name),           \'\',\'\',
                                                                customer.name, customer.name,
                                                                tour.name, \'(Tour)\' || \' \' || tour.name,
                                                                concat(tour.name, customer.name), \'(Tour)\' || \' \' || tour.name || \'-\' || customer.name,
                                                                \' \'
                    )  as note,
                    traveller.id as traveller_id,
                    traveller.note as traveller_note
                from 
                    reservation_room
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id=customer_group.id
                    inner join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
                where
                    '.$cond.'                 
                order by
                 length(room.name),
                 room.name || \'-\' || reservation_room.id ';
        //Luu Nguyen Giap comment:  reservation_room.departure_time - reservation_room.arrival_time as night
        //System::debug($sql);
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        
        $i = 1;
        $res_id = false;
        $dem =array();//dem so dong cho tung reservation_room_code va cung khoang thoi gian
        $night_id = false;
        $count =0;
        
        //Luu Nguyen Giap add edit cot dem cho bao cao
        
		foreach($report->items as $key=>$item)
		{
            if($report->items[$key]['reservation_room_code'] != $res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $report->items[$key]['reservation_room_code'];
                
                //Luu nguyen giap add 
                
                if($i!=2)
                {
                   array_push($dem,$count) ; 
                }  
                $count=1; 
                $night_id = $report->items[$key]['night'];
        
                //End Luu Nguyen Giap
            }
            else
            {
                if($report->items[$key]['night']!=$night_id)
                {
                    array_push($dem,$count) ;
                    $count=1;
                    $night_id = $report->items[$key]['night'] ;
                }
                else
                {
                    $count++;
                }
            }
		}
        array_push($dem,$count);
        $flag = false;
        $k=0; $i=0;
        //Luu Nguyen Giap add function xuat cot dem
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
                $report->items[$key]['row_night'] = $dem[$i];
                $i++;
             }
             else
             {
                 if($k>0)
                 {
                      $report->items[$key]['row_night'] =0;
                      $k--;
                      if($k==0)
                      {
                          $flag = false;
                      }
                 }
             }
             //$i++;
        }                                
        
        //End Luu Nguyen Giap
       // System::debug($report->items);
        //in
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
	   $result = array();
      if(count($items)>0){
      foreach($items as $k=>$val){
        $room_name =trim($val['room_name']);
        $result[$val['reservation_id']]['id']= $val['reservation_id'];
        $result[$val['reservation_id']]['reservation_id']= $val['reservation_id'];
        $result[$val['reservation_id']]['note']= $val['note'];
        $result[$val['reservation_id']]['customer_group_name']= $val['customer_group_name'];
        $result[$val['reservation_id']]['reservation_room_code']= $val['reservation_room_code'];
        /** oanh add **/
        $result[$val['reservation_id']]['reservation_note']= $val['reservation_note'];  
        //echo  $val['reservation_note'];
        /** End Oanh **/
        $result[$val['reservation_id']]['booking_code']= $val['booking_code'];
        $result[$val['reservation_id']]['child'][$room_name]['id']= $val['room_name'];
        $result[$val['reservation_id']]['child'][$room_name]['room_name']= $val['room_name'];
        $result[$val['reservation_id']]['child'][$room_name]['booking_code']= $val['booking_code'];
        /** oanh add ghi chu phong **/
        $result[$val['reservation_id']]['child'][$room_name]['reservation_room_note']= $val['reservation_room_note'];
        /** End Oanh **/
        if(isset($val['stt'])){
            $result[$val['reservation_id']]['child'][$room_name]['night']= ($val['night']==0)?'dayuse':$val['night'];
            $result[$val['reservation_id']]['child'][$room_name]['price']= $val['price'];
        }
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['id']= $val['id'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['fullname']= $val['fullname'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['birth_date']= $val['birth_date'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['gender']= $val['gender'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['traveller_id']= $val['traveller_id'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['country_code_1']= $val['country_code_1'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['time_in']= $val['time_in'];
        $result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['time_out']= $val['time_out'];
        //$result[$val['reservation_id']]['child'][$room_name]['childrend'][$val['id']]['traveller_level_name']= $val['traveller_level_name'];
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
       //System::debug($result);
        $this->map['status_list'] = array('ALL'=>Portal::language('all'),'CHECKIN'=>'CHECK IN','CHECKOUT'=>'CHECK OUT');
        $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$result,
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
