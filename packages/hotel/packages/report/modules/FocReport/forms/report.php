<?php
class FocReportForm extends Form
{
	function FocReportForm()
	{
		Form::Form('FocReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');     
	}
	function draw()
	{
       require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->day = date('d/m/Y');
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $day_orc = Date_Time::to_orc_date(date('d/m/Y'));
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       	/** 7211 */
			$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
            if(!$user_privigele or $user_privigele==3 or $user_privigele==4){
                
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
				WHERE
					party.type=\'USER\'
			');
            }else{
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
                    INNER JOIN account_privilege_group ON account_privilege_group.account_id=account.id
				WHERE
					party.type=\'USER\'
					AND account_privilege_group.group_privilege_id is not null and account_privilege_group.group_privilege_id !=3 and account_privilege_group.group_privilege_id !=4
			');
            }
        $this->map['users'] = $users;    
             /** 7211 end*/
        $this->map['user_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($users);
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        if(Url::get('from_time'))
        {
            $from_time = $this->calc_time(Url::get('from_time'));
            $this->map['from_time'] = Url::get('from_time');
        }
        else
        {
            $this->map['from_time'] = '00:00';            
            $from_time = $this->calc_time($this->map['from_time']);
            $_REQUEST['from_time'] = $this->map['from_time'];
        }
        if(Url::get('to_time'))
        {
            $to_time = $this->calc_time(Url::get('to_time'));
            $this->map['to_time'] = Url::get('to_time');
        }
        else
        {
            $this->map['to_time'] = date('H').':'.date('i');            
            $to_time = $this->calc_time($this->map['to_time'])+59;
            $_REQUEST['to_time'] = $this->map['to_time'];
        }
        $from_time_view = Date_Time::to_time($this->map['from_date']) + $from_time;                                
        $to_time_view = Date_Time::to_time($this->map['to_date']) + $to_time;
        $cond =' 1 = 1 AND payment.payment_type_id =\'FOC\' ';
        if($portal_id != 'ALL')
        {
            $cond.=' AND folio.portal_id = \''.$portal_id.'\' '; 
        }
        if(Url::get('user_id')!='ALL' && Url::get('user_id')!='' )
        {
            
             $cond.=' AND folio.user_id = \''.Url::get('user_id').'\'';
        }
        //Tìm kiếm trong ngày hnay
       
        $cond .= ' 
					AND folio.create_time >= \''.$from_time_view.'\'
                    AND folio.create_time <= \''.$to_time_view.'\'  
				';
        //ĐẾM SỐ phòng
        
        //voi moi folio dem so luong reservation_room voi hoa don nhom 
        
        $count_room1 = DB::fetch_all('
			SELECT 
				folio.id
				,count(distinct traveller_folio.reservation_room_id) as num
			FROM
				folio
				inner join traveller_folio on traveller_folio.folio_id = folio.id
                inner join reservation_room on reservation_room.id = traveller_folio.reservation_room_id and folio.reservation_room_id is null
                inner join room on room.id = reservation_room.room_id
                inner join payment on payment.folio_id = folio.id
            WHERE
				'.$cond.'             
			GROUP BY folio.id
			'); 
        
         
        $count_room2 = DB::fetch_all('
			SELECT 
				folio.id
				,count(reservation_room.id) as num
			FROM
				folio
                inner join reservation_room on folio.reservation_room_id = reservation_room.id
                inner join room on room.id = reservation_room.room_id
                inner join payment on payment.folio_id = folio.id
            WHERE
				'.$cond.'             
			GROUP BY folio.id
			');
        $count_room = $count_room1 + $count_room2;
        //System::debug($count_room);
        //exit();
        $bill_id = '0';
        foreach($count_room as $ke=>$val)
        {
            $bill_id .=','.$val['id'];
        }
        
        $sql1 = '
			SELECT
                folio.id || \'_\' || reservation_room.id as id, 
                reservation_room.id as reservation_room_id,
                reservation.id as reservation_id,
                folio.id as code,
                folio.total,
                folio.code as folio_code, --oanh add
                room.name as room_name,
                folio.customer_id as customer_id,
                reservation_traveller.id as traveller_id,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   customer.name	
					ELSE
						CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name))
                END guest_name,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   	reservation_room.time_in
					ELSE
						reservation_traveller.arrival_time
                END time_in,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   reservation_room.time_out	
					ELSE
						reservation_traveller.departure_time
                END time_out,
                reservation_room.price,
                reservation_room.deposit as de,
                reservation.deposit as de_gr
			FROM 
			    folio
                inner join reservation on reservation.id = folio.reservation_id
                inner join reservation_room on reservation_room.reservation_id = reservation.id and folio.reservation_room_id is null
                inner join room on room.id = reservation_room.room_id
                left join customer on customer.id = folio.customer_id
                left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                left join traveller on traveller.id = reservation_traveller.traveller_id
                inner join payment on payment.folio_id = folio.id
            WHERE 
			    '.$cond.' 
			ORDER BY
			     folio.id 
			'; // ROW_NUMBER() OVER (ORDER BY folio.id )     
              
        $sql2 = '
			SELECT
                folio.id || \'_\' || reservation_room.id as id, 
                reservation_room.id as reservation_room_id,
                reservation_room.id as id_r_r_lt,
                reservation.id as reservation_id,
                folio.id as code,
                folio.code as folio_code, --oanh add
                folio.total,
                room.name as room_name,
                folio.customer_id as customer_id,
                reservation_traveller.id as traveller_id,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   customer.name	
					ELSE
						CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name))
                END guest_name,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   	reservation_room.time_in
					ELSE
						reservation_traveller.arrival_time
                END time_in,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   reservation_room.time_out	
					ELSE
						reservation_traveller.departure_time
                END time_out,
                reservation_room.price,
                reservation_room.deposit as de,
                reservation.deposit as de_gr
			FROM 
			    folio
                inner join reservation on reservation.id = folio.reservation_id
                inner join reservation_room on folio.reservation_room_id = reservation_room.id
                inner join room on room.id = reservation_room.room_id
                left join customer on customer.id = folio.customer_id
                left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                left join traveller on traveller.id = reservation_traveller.traveller_id
                inner join payment on payment.folio_id = folio.id
            WHERE 
			    '.$cond.' 
			ORDER BY
			     folio.id ASC
			';                
        $report = new Report;
        $report->items1 = DB::fetch_all($sql1);
         
        $report->items2 = DB::fetch_all($sql2);
       
        $report->items = $report->items1 + $report->items2;
        $traveller_folios = $this->get_traveller_folios($bill_id);
        
        $i=1;
        $res_id = false;
        foreach($report->items as $key=>$value)
		{
            $total_paid = 0; $total_debit = 0;
			$report->items[$key]['reduce_amount'] = 0;
            $report->items[$key]['minibar'] = 0;
            $report->items[$key]['laundry'] = 0;
            $report->items[$key]['equip'] = 0;
            $report->items[$key]['room'] = 0;
            $report->items[$key]['telephone'] = 0;
            $report->items[$key]['restaurant'] = 0;
            $report->items[$key]['extra_service'] = 0;
            $report->items[$key]['tour'] = 0;
            $report->items[$key]['extra_bed'] = 0;
			$report->items[$key]['break_fast'] = 0;
            $report->items[$key]['transport'] = 0;
            $report->items[$key]['spa'] = 0;
            $report->items[$key]['exchange_rate'] = 0;
            foreach($traveller_folios as $k=>$traveller_folio)
            {
               
                if($traveller_folio['folio_id'] == $value['code']) 
                {
                    if (isset($value['id_r_r_lt']) and $traveller_folio['add_payment'] == 1)
                    {
                
                        if (!isset($report->items[$key + 1]))
                        {
                            $report->items[$key + 1] = $report->items[$key];
                            $add_sql = 'select
                                            rr.id,
                                            rr.time_in,
                                            rr.time_out,
                                            room.name as room_name
                                        from
                                            reservation_room rr
                                            inner join room on rr.room_id = room.id
                                        where
                                            rr.id = '. $traveller_folio['reservation_room_id'];
                            $add_info = DB::fetch($add_sql);
                            if (!empty($add_info))
                            {
                                $report->items[$key + 1]['time_in'] = $add_info['time_in'];
                                $report->items[$key + 1]['time_out'] = $add_info['time_out'];
                                $report->items[$key + 1]['room_name'] = $add_info['room_name'];
                            }
                            $count_room[$value['code']]['num'] += 1;
                            $report->items[$key + 1]['minibar'] = 0;
                            $report->items[$key + 1]['laundry'] = 0;
                            $report->items[$key + 1]['equip'] = 0;
                            $report->items[$key + 1]['room'] = 0;
                            $report->items[$key + 1]['telephone'] = 0;
                            $report->items[$key + 1]['restaurant'] = 0;
                            $report->items[$key + 1]['extra_service'] = 0;
                            $report->items[$key + 1]['tour'] = 0;
                            $report->items[$key + 1]['extra_bed'] = 0;
                            $report->items[$key + 1]['spa'] = 0;
                            $report->items[$key + 1]['exchange_rate'] = 0;
                        }
                        if($traveller_folio['type'] == 'MINIBAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['minibar'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'LAUNDRY')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['laundry'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EQUIPMENT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['equip'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'ROOM' || ($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='ROOM'))
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['room'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'TELEPHONE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['telephone'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'BAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['restaurant'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='SERVICE')
                        {
                            if($traveller_folio['service_id'] != 40 && $traveller_folio['service_id'] != 52 )
                            {
                                if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                $report->items[$key + 1]['extra_service'] += $traveller_folio['amount'];
                            }
                            else
                            {
                                if($traveller_folio['service_id'] == 52)
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key + 1]['tour'] += $traveller_folio['amount'];
                                }
                                if($traveller_folio['service_id'] == 40)
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key + 1]['extra_bed'] += $traveller_folio['amount'];
                                } 
                            }
                        }
                        else if($traveller_folio['type'] == 'MASSAGE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['spa'] += $traveller_folio['amount'];
                        }
                    }
                    else
                    //if ($traveller_folio['add_payment'] == 0)
                    {
                        if($traveller_folio['type'] == 'MINIBAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['minibar'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'LAUNDRY')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['laundry'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EQUIPMENT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['equip'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'ROOM' || ($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='ROOM'))
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['room'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'TELEPHONE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['telephone'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'BAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['restaurant'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='SERVICE')
                        {
                            if($traveller_folio['service_id'] != 40 && $traveller_folio['service_id'] != 52)
                            {
                                if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                $report->items[$key]['extra_service'] += $traveller_folio['amount'];
                            }
                            else
                            {
                                if($traveller_folio['service_id'] == 52)
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key]['tour'] += $traveller_folio['amount'];
                                }
                                if($traveller_folio['service_id'] == 40)
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key]['extra_bed'] += $traveller_folio['amount'];
                                } 
                            } 
                                
                            
                        }
                        else if($traveller_folio['type'] == 'MASSAGE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['spa'] += $traveller_folio['amount'];
                        }   
                    }
                }
                
            }   
            if (isset($report->items[$key + 1]))
            {
                $report->items[$key + 1]['exchange_rate'] = $report->items[$key]['exchange_rate'];
            }
            
        }  
		
        foreach($report->items as $key=>$value)
        {
            $total = $value['extra_service'] 
                     + $value['minibar']
                     + $value['restaurant']
                     + $value['laundry']
                     + $value['telephone']
                     + $value['equip']
                     + $value['spa']
                     + $value['room']
                     + $value['extra_bed']
                     + $value['break_fast']
                     + $value['tour']
                     ;
            if($total == 0)
            {
                $count_room[$value['code']]['num'];
                unset($report->items[$key]);
            }
        }
        
        $res_id = false;
        foreach($report->items as $key=>$value)
        {
            if($report->items[$key]['code'] != $res_id)
            {
                
                $report->items[$key]['stt'] = $i++;
                $res_id = $report->items[$key]['code'];
            }
        }
		//ksort($report->items);
        //System::debug($report->items);	
        $this->print_all_pages($report,$count_room);
	}
	function print_all_pages(&$report,$count_room)
	{
		$summary = array(
	        'room_count'=>0,
            'real_room_count'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'real_total_guest'=>0,
            'total_room_total'=>0,
            'total_minibar_total'=>0,
            'total_laundry_total'=>0,
            'total_equip_total'=>0,
            'total_restaurant_total'=>0,
            'total_telephone_total'=>0,
            'total_extra_service_total'=>0,
            'total_extra_bed_total'=>0,
            'total_spa_total'=>0,
            'total_tour_total'=>0,
			'total_break_fast_total'=>0,
			'total_reduce_amount_total'=>0,
            'exchange_rate'=>0,
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
                $summary['real_total_page']=count($pages);
                //Số trang thực sự sau khi lọc qua các yêu cầu
            	foreach($pages as $page_no=>$page)
            	{
            	   //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                            //Số phòng thực sự sau khi lọc qua các yêu cầu
                            $summary['real_room_count']++;
                            $summary['total_room_total'] += round($page[$key]['room']);
                            $summary['total_minibar_total'] += round($page[$key]['minibar']);
                            $summary['total_laundry_total'] += round($page[$key]['laundry']);
                            $summary['total_equip_total'] += round($page[$key]['equip']);
                            $summary['total_telephone_total'] += round($page[$key]['telephone']);
                            $summary['total_restaurant_total'] += round($page[$key]['restaurant']);
                            $summary['total_spa_total'] += round($page[$key]['spa']);
                            $summary['total_extra_service_total'] += round($page[$key]['extra_service']);
                            $summary['total_extra_bed_total'] += round($page[$key]['extra_bed']);
							$summary['total_break_fast_total'] += round($page[$key]['break_fast']);
                            $summary['total_tour_total'] += round($page[$key]['tour']);
                            $summary['exchange_rate'] = isset($page[$key]['exchange_rate'])?round($page[$key]['exchange_rate']):0;
                            if(isset($page[$key]['stt']))
                            {
								$summary['total_reduce_amount_total'] += $page[$key]['reduce_amount'];
                            }
                	} 
            	}
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{           	   
            		$this->print_page($page, $page_no , $real_page_no, $summary, $count_room);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_room);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_room);
        }
	}
	function print_page($items, $page_no,$real_page_no,$summary,$count_room)
	{  
         $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_room'=>$count_room
        	)+$this->map
		);
	}
	function prase_layout_default($summary,$count_room)
    {
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_room'=>$count_room
    		)+$this->map
    	);
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function get_traveller_folios($bill_id)
    {
		return $traveller_folios = DB::fetch_all('
						SELECT 
				            CONCAT(traveller_folio.type,CONCAT(\'_\',CONCAT(traveller_folio.reservation_room_id,CONCAT(\'_\',CONCAT(traveller_folio.folio_id,CONCAT(\'_\',traveller_folio.invoice_id)))))) as id
                            ,sum(traveller_folio.total_amount) as amount
                            ,traveller_folio.folio_id
                            ,traveller_folio.add_payment
                            ,traveller_folio.reservation_room_id
                            ,traveller_folio.type
                            ,extra_service_invoice_detail.service_id
                            ,extra_service_invoice.payment_type
			             FROM 
				            traveller_folio 
                            left join extra_service_invoice_detail on extra_service_invoice_detail.id = traveller_folio.invoice_id
			                left join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                         WHERE
				            traveller_folio.folio_id in ('.$bill_id.')
                         
                         GROUP BY 
                            traveller_folio.type
                            ,traveller_folio.reservation_room_id
                            ,traveller_folio.folio_id
                            ,traveller_folio.add_payment
                            ,traveller_folio.invoice_id
                            ,extra_service_invoice_detail.service_id
                            ,extra_service_invoice.payment_type
                         ORDER BY
                            traveller_folio.folio_id ASC');	
	}
}
?>
