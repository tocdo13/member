<?php
class TravellerReportForm extends Form{
	function TravellerReportForm(){
		Form::Form('TravellerReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw(){
		if(URL::get('do_search')){
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
            $price = 0;
			if(Url::get('price')){
				$price = 1;
			}
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			$this->map = array();
        
            $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
            
            $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
            
            $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
			
			$status='';
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            if($portal_id!="ALL")
            {
                $cond ="  reservation.portal_id ='".$portal_id."' ";
            }
            else
            {
                $cond=" 1=1 ";
            } 
            //End Luu Nguyen Giap add portal
			if(Url::get('status')!="0"){
				$status=Url::get('status');
				if($status == 'CHECKIN_BOOKED'){
					$cond .= ' AND (
                    
                                    ( ( reservation_room.status=\'CHECKIN\') OR reservation_room.status=\'BOOKED\' )
                                    OR
                                    ( reservation_room.status=\'CHECKOUT\' AND reservation_room.arrival_time=reservation_room.departure_time and reservation_room.change_room_to_rr is null)
                    
                                    )';
				}
                else
                if($status == 'CHECKIN_CHECKOUT')
                {
					$cond .= ' AND ((reservation_room.status=\'CHECKIN\') OR (reservation_room.status=\'CHECKOUT\' and reservation_room.change_room_to_rr is null))';
				}
                else
                if($status == 'IN_HOUSE')
                {
					$cond .= 'AND ((reservation_room.status=\'CHECKIN\') OR reservation_room.status=\'CHECKOUT\')';  
				}
                else
                if($status == 'BOOKED')
                {
					$cond .= ' AND (reservation_room.status=\'BOOKED\')';
				}
                else
                if($status == 'CHECKIN')
                {
					$cond .= ' AND (reservation_room.status=\'CHECKIN\')';
				}
                else
                if($status == 'CHECKOUT')
                {
					$cond .= ' AND (reservation_room.status=\'CHECKOUT\' and reservation_room.change_room_to_rr is null)';
				}
                else
                if($status == 'CANCEL')
                {
					$cond .= ' AND (reservation_room.status=\'CANCEL\')';
				}
                else
                {
					$cond .=' AND reservation_room.status=\''.$status.'\'';
				}
			}
			if($status == 'IN_HOUSE'){
                $time_in_today = Date_Time::to_time($from_day) + 21600;//6h00 sang hom nay
                $time_in = Date_Time::to_time($from_day) + 86400;//00h00 sang hom sau
                $time_in_end = Date_Time::to_time($from_day) + 86400+21600;//6h00 sang hom sau
                $cond .= ' 
			     AND(
						((reservation_room.arrival_time <= \''.Date_Time::to_orc_date($from_day).'\' 
                        AND reservation_room.departure_time > \''.Date_Time::to_orc_date($from_day).'\'
                        and reservation_room.change_room_from_rr is null and reservation_room.change_room_to_rr is null
                            )
                        or
                            (
                            from_unixtime(reservation_room.old_arrival_time) <= \''.Date_Time::to_orc_date($from_day).'\' 
                            AND reservation_room.departure_time > \''.Date_Time::to_orc_date($from_day).'\'
                            and reservation_room.change_room_to_rr is null
                            )
                        )
					OR 	(reservation_room.arrival_time = \''.Date_Time::to_orc_date($from_day).'\'
                         and reservation_room.time_in >=\''.$time_in_today.'\' 
                         AND reservation_room.arrival_time = reservation_room.departure_time 
                         and reservation_room.change_room_to_rr is null 
                         )--KimTan: neu doi phong trong ngay thi chi lay phong duoc doi chu ko dc tin phong doi la phong day_use + phong day_used co gio checkin truoc 6h00					
					OR  (reservation_room.time_in >=\''.$time_in.'\' and reservation_room.time_in <\''.$time_in_end.'\' and reservation_room.change_room_from_rr is null)
                    )
                  AND reservation_traveller.arrival_date<=\''.Date_Time::to_orc_date($from_day).'\' 
			      AND reservation_traveller.departure_date>\''.Date_Time::to_orc_date($from_day).'\' 
				';
			}else{
				$cond .= ' 
					AND reservation_room.arrival_time>=\''.Date_Time::to_orc_date($from_day).'\' 
					AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_day).'\'
				';
			}
			$report = new Report;
			$sql='
				select
					reservation_traveller.id
					,traveller.first_name 
					,traveller.last_name 
					,DECODE(traveller.gender,0,\''.Portal::language('female').'\',\''.Portal::language('male').'\') as gender
					,TO_CHAR(traveller.birth_date,\'DD/MM/YYYY\') AS birth_date
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,(reservation_room.arrival_time-current_date) as time_segment
					,reservation_room.time_in
					,reservation_room.time_out
					,traveller.passport ,traveller.visa 
					,CONCAT(CONCAT(CONCAT(
						DECODE(reservation.customer_id,0,\'\',CONCAT(customer.name,\'. \')),
						DECODE(reservation.note,\'\',\'\',CONCAT(reservation.note,\'. \'))),
						DECODE(reservation_room.note,\'\',\'\',CONCAT(reservation_room.note,\'. \'))),
						DECODE(traveller.note,\'\',\'\',traveller.note)
					) as note
					,traveller.phone ,traveller.fax ,traveller.address ,traveller.email 
					,traveller.nationality_id 
					,reservation_room.reservation_id
					,reservation_room.status as reservation_room_status
                    ,reservation_traveller.status as reservation_traveller_status
					,0 as colspan
					'.(($from_day==$to_day)?'
					,(reservation_room.price) as price':'
					,((reservation_room.total_amount*(1+reservation_room.service_rate/100))*(1+reservation_room.tax_rate/100)) as price').'
					,reservation_traveller.reservation_room_id
					,country.code_2 as nationality_code
					,country.name_'.Portal::language().' as nationality_name
					,room.name as room_name
					,customer.name as customer_name
                    ,reservation.booking_code
                    ,reservation.id as reservation_id 
                    ,reservation_room.price as room_price
                    ,reservation_room.id as reservation_room_id
                    ,nvl(reservation_room.adult,0) as adult
                    ,nvl(reservation_room.child,0) as child
				from
					reservation_traveller
					inner join traveller on traveller.id=reservation_traveller.traveller_id
					inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					inner join reservation on reservation.id=reservation_room.reservation_id
					left outer join customer on customer.id=reservation.customer_id 
					left outer join country on country.id=traveller.nationality_id 
					left outer join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id = room_level.id
				where
					'.$cond.'
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
				order by 
					reservation.id,room.name ASC,reservation_room.time_out DESC
			';	
			$report->items = DB::fetch_all($sql);
			$i = 1;
			foreach($report->items as $key=>$item){
				$report->items[$key]['price'] = System::display_number($item['price']);
				$report->items[$key]['stt'] = $i++;
                $report->items[$key]['num_traveller'] = $item['adult'];
                if($item['reservation_room_status']=='CHECKOUT')
                $item['reservation_room_status'] = 'OUT';
                if($item['reservation_room_status']=='BOOKED')
                $item['reservation_room_status'] = 'B';
                $report->items[$key]['status'] = $item['reservation_room_status'];
                if($item['reservation_room_status']=='CHECKIN')
                {
                    if($item['reservation_traveller_status']=='CHECKIN')
                    $item['reservation_traveller_status'] = 'IN';
                    if($item['reservation_traveller_status']=='CHECKOUT')
                    $item['reservation_traveller_status'] = 'OUT';
                    $report->items[$key]['status'] = $item['reservation_traveller_status'];
                }
			}
            $this->map['reservation_room_start'] = 0;
			$this->print_all_pages($report);
		}
        else
        {
            $_REQUEST['date_from'] =date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
            $this->map = array();
            //Start : Luu Nguyen GIap add portal
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
            //End   :Luu Nguyen GIap add portal
            
			$this->parse_layout('search',$this->map);	
		}			
	}
	function print_all_pages(&$report)
	{
		$price=0;
		if(Url::get('price'))
		{
			$price=1;
		}
		$count = 0;
		$total_page = 1;
		$pages = array();
		$status="0";
		$from_day = URL::get('from_day');
        $to_day = URL::get('to_day');
		
		if(Url::get('status')!="0")
		{
			$status=Url::get('status');
		}
		foreach($report->items as $key=>$item)
		{
			
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        $arr = array_keys($pages);
        if(!empty($arr))
        {
            $begin = $arr['0'];
            //trang cuoi cung dc in
            $end = $begin + $this->map['no_of_page'] - 1;
            //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
            for($i = $total_page; $i> $end; $i--)
                unset($pages[$i]);      
        }
        if(sizeof($pages)>0)
		{
		    $this->group_function_params = array(
        			'room_count'=>0,
                    'real_room_count'=>0,
                    'total_page'=>1,
                    'real_total_page'=>1,
                    'line_per_page' =>999,
                    'no_of_page' =>50,
                    'start_page' =>1,
                    'room_count'=>0,
        			'guest_count'=>0,
        			'total_price'=>0
				);
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;  
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;
			}
		}
		else
		{
			
            $this->parse_layout('header',
			get_time_parameters()+
				array(
                    'page_no'=>1,
					'total_page'=>1,
					'status'=>$status,
					'from_day'=>$from_day,
					'to_day'=>$to_day,
					'price'=>$price,
					'today'=>Url::get('today',0),
				)+$this->map
			);
            $this->parse_layout('no_record');
            $this->parse_layout('footer',array(
				'real_total_page'=>0,
                'real_page_no'=>0,
                'page_no'=>0,
				'total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
			$price=0;
			if(Url::get('price'))
			{
				$price=1;
			}
			
			$from_day=Url::get('date_from');
			$to_day=Url::get('date_to');
			$status="0";
			
			
			if(Url::get('status')!="0")
			{
				$status=Url::get('status');
			}
            $last_group_function_params = $this->group_function_params;
            $room_name = false;
            $reservation_id = false;
           	$count = array();
            foreach($items as $k => $item)
            {
                if($reservation_id!=$item['reservation_id'])
    			{
    				$reservation_id=$item['reservation_id'];
    			}
    			else
    			{
    				$item['price']='';
    			}
    			if($this->map['reservation_room_start']<>$item['reservation_room_id'])
    			{
    				$this->map['reservation_room_start']=$item['reservation_room_id'];
    				 $this->group_function_params['room_count']++;
    			}
                if(!isset($count[$item['reservation_id']]))
                {
                    $count[$item['reservation_id']] = array('count'=>0);
                    $count[$item['reservation_id']][$item['reservation_room_id']] = 0;
                }
                else
                {
                    if(!isset($count[$item['reservation_id']][$item['reservation_room_id']]))
                    {
                        $count[$item['reservation_id']][$item['reservation_room_id']] = 0;
                    }
                }
                $count[$item['reservation_id']]['count']++;
                $count[$item['reservation_id']][$item['reservation_room_id']]++; 
    			 $this->group_function_params['guest_count']++;
    			 $this->group_function_params['total_price']+=str_replace(',','',$item['price']);
            }
            $this->map['count'] = $count;
            //System::debug($count);
            if($page_no>=$this->map['start_page']){
        		$this->parse_layout('header',
        			array(
        				'page_no'=>$page_no,
        				'total_page'=>$total_page,
        				'status'=>$status,
        				'from_day'=>$from_day,
        				'to_day'=>$to_day,
        				'price'=>$price,
        				'today'=>Url::get('today',0),
        			)+$this->map
        		);	
                $this->parse_layout('report',
        			array(
        				'items'=>$items,
        				'page_no'=>$page_no,
        				'total_page'=>$total_page,
        				'price'=>$price,
                        'group_function_params'=>$this->group_function_params,
                        'last_group_function_params'=>$last_group_function_params
        			)+$this->map
        		);
        		$this->parse_layout('footer',array(				
        			'page_no'=>$page_no,
        			'total_page'=>$total_page,
        		)+$this->map);
          }
	}
}
?>
