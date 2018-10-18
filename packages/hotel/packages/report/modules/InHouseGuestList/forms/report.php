<?php
class InHouseGuestListForm extends Form{
	function InHouseGuestListForm(){
		Form::Form('InHouseGuestListForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
    {
		/** mổ xẻ: đây là yêu cầu ban đầu đưa ra đối với báo cáo **/
        /**
         * Y/c:
         * IN HOUSE GUEST LIST là tất cả những phòng đang là trạng thái check in mặc dù phòng đó vẫn chưa có tên khách:
         * Định nghĩa: IN HOUSE GUEST LIST gồm: Danh sách phòng khách đang ở trạng thái check in trên hệ thống (Tại thời điểm xem giờ hiện tại), 
         * Danh sách này có kèm theo cột hiển thị tên khách đến ở (nếu có), 
         * nếu ko có tên khách đến ở thì vẫn phải thể hiện số phòng trạng thái check in.
         * Xem được trong quá khứ
         * Thêm cột extra bed vào báo cáo
         * Trạng thái khách lưu: lấy ra tất cả những phòng có ngày ở = ngày xem, trạng thái = checkin & day use (đã out).
         */
         require_once 'packages/core/includes/utils/time_select.php';
         require_once 'packages/core/includes/utils/lib/report.php';
         $this->map = array();
         
         /** portal **/
         $portal  = Url::get('portal_id')?Url::get('portal_id'):'ALL';
         $_REQUEST['portal_id'] = $this->map['portal_id'] = $portal;
         $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
         
         /** date **/
         $from_date = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
         $this->map['from_date'] = $from_date; $_REQUEST['from_date'] = $this->map['from_date'];
         $to_date = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
         $this->map['to_date'] = $to_date; $_REQUEST['to_date'] = $this->map['to_date'];
         
         /** status **/
         $status  = Url::get('status')?Url::get('status'):'CHECKIN';
         $_REQUEST['status'] = $this->map['status'] = $status;
         $this->map['status_list'] = array('CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT','BOOKED'=>'BOOKED','CANCEL'=>'CANCEL','IN_HOUSE'=>'IN HOUSE','CHECKIN_CHECKOUT'=>'CHECKIN AND CHECKOUT','CHECKIN_BOOKED'=>'CHECKIN AND BOOKED'); 
         
         /** page **/
         $line_per_page = Url::get('line_per_page')?Url::get('line_per_page'):32; $_REQUEST['line_per_page'] = $this->map['line_per_page'] = $line_per_page;
         $no_of_page = Url::get('no_of_page')?Url::get('no_of_page'):50; $_REQUEST['no_of_page'] = $this->map['no_of_page'] = $no_of_page;
         $start_page = Url::get('start_page')?Url::get('start_page'):1; $_REQUEST['start_page'] = $this->map['start_page'] = $start_page;
         
         
         /** conniction **/
         $cond = "1=1";
         if($portal != 'ALL')
            $cond .= " AND (reservation.portal_id='".$portal."')";
         
         if($status == 'CHECKIN_BOOKED'){
			$cond .= ' AND (
            
                            ( ( reservation_room.status=\'CHECKIN\') OR reservation_room.status=\'BOOKED\' )
                            OR
                            ( reservation_room.status=\'CHECKOUT\' AND reservation_room.arrival_time=reservation_room.departure_time and reservation_room.change_room_to_rr is null)
            
                            )';
		}
        elseif($status == 'CHECKIN_CHECKOUT')
			$cond .= ' AND ((reservation_room.status=\'CHECKIN\') OR (reservation_room.status=\'CHECKOUT\' and reservation_room.change_room_to_rr is null))';
        elseif($status == 'IN_HOUSE')
			$cond .= 'AND ((reservation_room.status=\'CHECKIN\') OR reservation_room.status=\'CHECKOUT\')';
        elseif($status == 'BOOKED')
			$cond .= ' AND (reservation_room.status=\'BOOKED\')';
        elseif($status == 'CHECKIN')
			$cond .= ' AND (reservation_room.status=\'CHECKIN\')';
        elseif($status == 'CHECKOUT')
			$cond .= ' AND (reservation_room.status=\'CHECKOUT\' and reservation_room.change_room_to_rr is null)';
        elseif($status == 'CANCEL')
			$cond .= ' AND (reservation_room.status=\'CANCEL\')';
        else
			$cond .=' AND reservation_room.status=\''.$status.'\'';
        
        if($status == 'IN_HOUSE')
        {
            $time_in_today = Date_Time::to_time($from_date) + 21600;//6h00 sang hom nay
            $time_in = Date_Time::to_time($from_date) + 86400;//00h00 sang hom sau
            $time_in_end = Date_Time::to_time($from_date) + 86400+21600;//6h00 sang hom sau
            $cond .= ' 
		     AND(
					((reservation_room.arrival_time <= \''.Date_Time::to_orc_date($from_date).'\' 
                    AND reservation_room.departure_time > \''.Date_Time::to_orc_date($from_date).'\'
                    and reservation_room.change_room_from_rr is null and reservation_room.change_room_to_rr is null
                        )
                    or
                        (
                        from_unixtime(reservation_room.old_arrival_time) <= \''.Date_Time::to_orc_date($from_date).'\' 
                        AND reservation_room.departure_time > \''.Date_Time::to_orc_date($from_date).'\'
                        and reservation_room.change_room_to_rr is null
                        )
                    )
				OR 	(reservation_room.arrival_time = \''.Date_Time::to_orc_date($from_date).'\'
                     and reservation_room.time_in >=\''.$time_in_today.'\' 
                     AND reservation_room.arrival_time = reservation_room.departure_time 
                     and reservation_room.change_room_to_rr is null 
                     )--KimTan: neu doi phong trong ngay thi chi lay phong duoc doi chu ko dc tin phong doi la phong day_use + phong day_used co gio checkin truoc 6h00					
				OR  (reservation_room.time_in >=\''.$time_in.'\' and reservation_room.time_in <\''.$time_in_end.'\' and reservation_room.change_room_from_rr is null)
                )
			';
		}
        else
        {
			$cond .= ' 
				AND ( 
                    ( reservation_room.arrival_time>=\''.Date_Time::to_orc_date($from_date).'\' 
				        AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_date).'\' 
                        AND reservation_traveller.id is null) 
                    OR
                    ( reservation_traveller.arrival_date>=\''.Date_Time::to_orc_date($from_date).'\' 
				        AND reservation_traveller.arrival_date<=\''.Date_Time::to_orc_date($to_date).'\' 
                        AND reservation_traveller.id is not null) 
                )
			';
		}
        
        /** database **/
        $sql='
			select
				concat(reservation_room.id,reservation_traveller.id) as id
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
                ,reservation_room.status as reservation_traveller_status
				,0 as colspan
				'.(($from_date==$to_date)?'
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
                ,reservation_room.extra_bed
                ,reservation_room.id as reservation_room_id
                ,nvl(reservation_room.adult,0) as adult
                ,nvl(reservation_room.child,0) as child
			from
				reservation_room
                inner join reservation on reservation.id=reservation_room.reservation_id
				left join reservation_traveller on reservation_room.id=reservation_traveller.reservation_room_id
                left join traveller on traveller.id=reservation_traveller.traveller_id
				left join customer on customer.id=reservation.customer_id 
				left join country on country.id=traveller.nationality_id 
				left join room on room.id=reservation_room.room_id
                left join room_level on room.room_level_id = room_level.id
			where
				'.$cond.'
                and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
			order by 
				reservation.id,room.name ASC
		';
         //echo $sql;
         $report = new Report;
         $report->items = DB::fetch_all($sql);
         $i=0;
         $room_name_check = '';
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
                //kimtan dem so lai so phong tren tung trang
                if($room_name_check == '')
                {
                    $room_name_check = $report->items[$key]['room_name'];
                    $report->items[$key]['num_room'] = 1;
                }
                else{
                    if($report->items[$key]['room_name'] != $room_name_check)
                    {
                        $room_name_check = $report->items[$key]['room_name'];
                        $report->items[$key]['num_room'] = 1;
                    }
                    else
                    {
                        $report->items[$key]['num_room'] = 0;
                    }
                }    
                //end kimtan dem so lai so phong tren tung trang
			}
        $this->print_all_pages($report);
	}
    function print_all_pages(&$report)
	{
		$price=0;
		$count = 0;
		$total_page = 1;
		$pages = array();
		$status="0";
		$from_day = URL::get('from_date');
        $to_day = URL::get('to_date');
		
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
			
            $this->parse_layout('report',
			get_time_parameters()+
				array(
                    'page_no'=>1,
					'total_page'=>1,
					'status'=>$status,
					'from_day'=>$from_day,
					'to_day'=>$to_day,
					'price'=>$price,
					'today'=>Url::get('today',0),
                    'no_recode'=>'',
                    'real_total_page'=>0,
                    'real_page_no'=>0
				)+$this->map
			);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
			$price=0;
			$from_day=Url::get('from_date');
			$to_day=Url::get('to_date');
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
    			if($room_name<>$item['room_name'])
    			{
    				$room_name=$item['room_name'];
    				 $this->group_function_params['room_count']+=$item['num_room'];
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
            if($page_no>=$this->map['start_page'])
            {
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
          }
	}
}
?>
