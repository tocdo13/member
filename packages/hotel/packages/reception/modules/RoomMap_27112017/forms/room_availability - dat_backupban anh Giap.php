<?php
class RoomAvailabilityForm extends Form
{
	function RoomAvailabilityForm()
	{
		Form::Form('RoomAvailabilityForm');
		$this->add('room_ids',new TextType(true,'invalid_room_ids',0,255));
		$this->link_css(Portal::template('hotel').'/css/room.css');//Important
		$this->link_css('skins/default/datetime.css');	
		$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->link_js('packages/core/includes/js/jquery/cookie.js');
		$this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');		
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');		
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.corner.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
	function on_submit()
	{
		if($this->check())
		{
			$room_ids = explode(',',URL::get('room_ids'));
			if(User::can_edit(false,ANY_CATEGORY))
			{
				$room_id = reset($room_ids);
				if(URL::get('change_room_note_'.$room_id) and Url::get('in_date'))
				{
					$sql = '
						select 
							room_status.reservation_id 
						from 
							room_status
                            inner join reservation_room on reservation_room.id = room_status.reservation_room_id
						where 
							room_status.room_id=\''.$room_id.'\' and room_status.in_date=\''.Date_Time::to_orc_date(Url::sget('in_date')).'\' and reservation_room.status<>\'CHECKOUT\' AND room_status.status<>\'CANCEL\' order by room_status.id desc';
					if($reservation_id = DB::fetch($sql,'reservation_id'))
					{
						DB::update('reservation_room',array(
								'note'=>str_replace('"',' ',Url::get('room_note_'.$reservation_id))
							),'reservation_id=\''.$reservation_id.'\' and room_id=\''.$room_id.'\''
						);
					}
				}
				if(Url::sget('in_date'))
                {
					foreach($room_ids as $room_id)
                    {
						$start_time = Date_Time::to_time(Url::sget('in_date'));
						$end_time = Date_Time::to_time(Url::sget('in_date'));
						$time = $start_time;
						if(Url::get('house_status'))
                        {
							if(Url::sget('repair_to'))
                            {
								$end_time = Date_Time::to_time(Url::sget('repair_to'));	
							}
						}
                        else if(!Url::get('house_status'))
                        {
							if(Url::sget('repair_to'))
                            {
								$end_time = Date_Time::to_time(Url::sget('repair_to'));	
							}
						}
						while($time<=$end_time)
                        {
							$cond = 'status<>\'CANCEL\'
								AND in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
								AND room_id=\''.$room_id.'\'';
							$sql = 'SELECT * FROM room_status WHERE '.$cond.'';
							if(DB::exists($sql))
							{
								DB::update('room_status',
									array(
									'note'=>str_replace('"',' ',Url::get('note')),
									'house_status'=>Url::get('house_status'),
                                    'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$start_time)),
									'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$end_time))
									),
									$cond
								);
                                 $title = 'Update Room house_status and note room_id : '.$room_id.', Status: ' .$status.'';
        						$description = ''
                                .Portal::language('house_status_note').':'.Url::get('house_status').'<br>  '
        						.Portal::language('in_date').':'.date('d/m/Y',$time).'<br>  '
                                .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                                .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
        						System::log('edit',$title,$description,$room_id);
							}
                            else
                            {
								DB::insert('room_status',
									array(
									'note'=>str_replace('"',' ',Url::get('note')),
									'room_id'=>$room_id,
									'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),
									'status'=>'AVAILABLE',
									'house_status'=>Url::get('house_status'),
									'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$start_time)),
									'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$end_time)),
									)
								);
                                $title = 'Insert Room house_status and note room_id: '.$room_id.', Status: ' .$status.'';
        						$description = ''
                                .Portal::language('note').':'.Url::get('note').'<br> '
                                .Portal::language('house_status_note').':'.Url::get('house_status').'<br> '
        						.Portal::language('in_date').':'.date('d/m/Y',$time).'<br> ' 
                                .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                                .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
        						System::log('edit',$title,$description,$room_id);
							}
							$time += 3600*24;
						}
					}	
				}
			}
			Url::redirect_current(array('in_date'));
		}
	}
	function draw()
	{
		//$this->fix_room_map();
		//$this->cancel_booking_expired();

		DB::query('delete from room_status where reservation_id = 0 and house_status is null and note is null');
		if(!isset($_REQUEST['from_date']))
        {
			$_REQUEST['from_date'] = date('d/m/Y');
			$from_date = date('d/m/Y');
		}
		if(!isset($_REQUEST['to_date']))
        {
			
            $_REQUEST['to_date'] = date('d/m/Y',time()+86400);
            $to_date = date('d/m/Y');
		}
		if(Url::get('from_date'))
        {
			$from_date = Date_Time::to_time(Url::get('from_date'));
		}
		if(Url::get('to_date'))
        {
			$to_date = Date_Time::to_time(Url::get('to_date'));
		}
		$current_time = $from_date;
		$this->year = date('Y',$current_time);
		$this->month = date('m',$current_time); 
		$this->day = date('d',$current_time);
		$this->end_year = date('Y',$to_date);
		$this->end_month = date('m',$to_date);
		$this->end_day = date('d',($to_date)); //$current_time+86400-1
		$rooms = $this->get_rooms();
		$rooms_array = array();
		$arr = array();
		$this->map = array();
		$room_statuses = $this->get_room_statuses_new();
        
		$this->map['tours'] = $this->get_tour();
		$room_levels = $this->get_room_level();
		$this->map['total_booked_room'] = 0;
		$this->map['total_checkin_room'] = 0;
		$this->map['total_available_room'] = 0;
		$this->map['total_repaire_room'] = 0;
		$this->map['total_checkout_room'] = 0;
		$this->map['total_resover_room'] = 0;
		$this->map['total_overdue_room'] = 0;
		$this->map['total_available_room'] = 0;
		$this->map['total_dayused_room'] = 0;
		$this->map['total_checkin_today_room'] = 0;
		$this->map['total_occupied_today'] = 0;
		$this->map['total_checkout_today_room'] = 0;
		$this->map['total_dayuse_today'] = 0;		
		$room_overdues = $this->get_room_overdues();
		$this->map['books_without_room'] = $this->get_books_without_room();
		//$books_without_room_arr = $this->get_books_without_room_return_arr();
		$total_books_without_room = 0;
		$total_occ_without_room = 0;
		/*foreach($books_without_room_arr as $tt => $book){
			if(Date_Time::to_time($book['arrival']) == Date_Time::to_time($this->day.'/'.$this->month.'/'.$this->year)){
				$total_books_without_room++;	
			}else if(Date_Time::to_time($book['departure']) > Date_Time::to_time($this->day.'/'.$this->month.'/'.$this->year) && Date_Time::to_time($this->day.'/'.$this->month.'/'.$this->year) > Date_Time::to_time($book['arrival'])){
				$total_occ_without_room++;
			}
		}*/
		$room_id = 0;
		$status_arr = array();
		$day_use_arr = array();
		$arrr = array();
       
		foreach($room_statuses as $key=>$room_status)
		{	
			if($room_status['departure_time']==$this->day.'/'.$this->month.'/'.$this->year){
				$this->map['total_checkout_today_room']++;
			}
			if($room_status['house_status']=='REPAIR'){
				$this->map['total_available_room'] --;


				$this->map['total_repaire_room']++;
			}
			// && ($room_status['house_status'] != '')
			if(isset($rooms[$room_status['room_id']]) && ($room_status['status'] == 'AVAIABLE') && $room_status['house_status'] != 'REPAIR')
			{	
				$rooms_array[$room_status['room_id']] = $rooms[$room_status['room_id']];
				$rooms_array[$room_status['room_id']]['note'] = '';
				$rooms_array[$room_status['room_id']]['room_note'] = '';	
				$rooms_array[$room_status['room_id']]['note'] = '';	
				if(isset($room_status['room_note']) and $room_status['room_note']){
					$rooms_array[$room_status['room_id']]['note'] .= Portal::language('room_note').': '.$room_status['room_note']."\n";
				}
				$rooms_array[$room_status['room_id']]['note'] .= Portal::language('hk_note').': '.$room_status['hk_note']."\n";
				$rooms_array[$room_status['room_id']]['hk_note'] = ''.$room_status['hk_note'].""; // Portal::language('hk_note').': 
                
				if((isset($room_status['note'])&& $room_status['note'])){
					$rooms_array[$room_status['room_id']]['note'] .= $room_status['note'];
				}
				//$status_arr[$room_status['room_id']]['note'] = $rooms[$room_status['room_id']]['hk_note']."\n";
				//$status_arr[$room_status['room_id']]['hk_note'] = $rooms[$room_status['room_id']]['hk_note'];
				if(isset($room_overdues[$room_status['room_id']]) and $room_status['in_date'] == Date_Time::to_orc_date($this->day.'/'.$this->month.'/'.$this->year)){
					$rooms_array[$room_status['room_id']] = $rooms[$room_status['room_id']];
				}
			}else if(isset($rooms[$room_status['room_id']]) && (($room_status['status'] != 'AVAILABLE') || ($room_status['status'] == 'AVAILABLE' && $room_status['house_status'] == 'REPAIR')) && $room_status['status'] != 'CHECKOUT'){
				$arr[$room_status['room_id']] = $rooms[$room_status['room_id']];
				$arr[$room_status['room_id']]['status'] = $room_status['status'];
			}
            //luu nguyen giap
            if(isset($rooms[$room_status['room_id']]))
            {
                $rooms[$room_status['room_id']]['house_status'] = $room_status['house_status'];
            } 
            //end
		}
        
        
		$arrr = array();
		require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
		$extra_cond = ' 1>0';
		$arrival_time = Date_Time::to_time(($this->day.'/'.$this->month.'/'.$this->year));
		$departure_time = Date_Time::to_time(($this->end_day.'/'.$this->end_month.'/'.$this->end_year));
		$r_r_id = '';
		if(Url::get('r_r_id')){
			$r_r_id = Url::get('r_r_id');	
		}
		$min_room_levels = check_availability($r_r_id,$extra_cond,$arrival_time,$departure_time);
		foreach($min_room_levels as $m => $min){
			if(isset($room_levels[$min['id']])){
				$room_levels[$min['id']]['vacant_room'] = $min['min_room_quantity'];
			}
			
		}
		//System::Debug($rooms_array); 
		$overdue= array();
		foreach($room_overdues as $room_overdue)
		{
			if(!isset($day_use_arr[$room_overdue['room_id']]) and isset($rooms[$room_overdue['room_id']]))
			{
				$overdue[$room_overdue['room_id']] = $rooms[$room_overdue['room_id']];
			}
		}
		foreach($rooms as $r =>$room)
        {
			if(!isset($arr[$r]))
            {
				$rooms_array[$r] = $room;
				$rooms_array[$r]['status'] ='AVAILABLE';
			}
			if(isset($overdue[$r]))
            {
				//$rooms_array[$r] = $room;	
				$rooms_array[$r]['status'] = 'EXPECTED_CHECKOUT';
			}
		}
		
		$this->map['total_books_without_room'] = $total_books_without_room;
		$this->map['total_occupied_today'] += $total_occ_without_room;
		$floors = array();
		$last = false;
		$i=0;
        
		foreach($rooms_array as $key=>$room)
		{	
			$room['reservations'] = array();
			$room['old_reservations'] = array();
			$i++;
			if(isset($room['floor']) and $last != $room['floor'])
			{
				$last = $room['floor'];
				$floors[$room['floor']]=
					array(
						'id'=>str_replace(' ','_',strtolower($room['floor'])),
						'name'=>$room['floor'],
						'rooms'=>array()
					);
			}
			$floors[$last]['rooms'][$key] = $room;
		}
		
		$ebs = DB::fetch_all('
			SELECT
				extra_service.*
			FROM
				extra_service
			WHERE
				extra_service.code=\'EXTRA_BED\' or extra_service.code=\'BABY_COT\'
		');
		$cond_extra = 'extra_service_detail.in_date';
		foreach($ebs as $key=>$value)
		{
			$extra_bed_status = DB::fetch('
				SELECT
					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity
				FROM
					extra_service_invoice_detail
					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
				WHERE
					extra_service_invoice_detail.in_date=\''.Date_Time::to_orc_date(Url::get('in_date')).'\' AND extra_service.code=\''.$value['code'].'\'
				GROUP BY
					extra_service.code
			');
			$quantity = (($value['code']=='EXTRA_BED')?EXTRA_BED_QUANTITY-$extra_bed_status['quantity']:BABY_COT_QUANTITY-$extra_bed_status['quantity']);
			$ebs[$key]['quantity'] = $quantity;
			$ebs[$key]['total_quantity'] = (($value['code']=='EXTRA_BED')?EXTRA_BED_QUANTITY:BABY_COT_QUANTITY);
		}
		$this->map['ebs'] = $ebs;
        
		$this->parse_layout('room_availability', $this->map+
			get_time_parameters()+
			array(
				'floors'=>$floors,
				'year'=>$this->year,
				'month'=>$this->month,
				'day'=>$this->day,
				'end_month'=>$this->end_month,
				'room_levels'=>$room_levels,
				'room_level_id_list'=>array(''=>Portal::language('All')) + String::get_list($room_levels),
				'end_day'=>$this->end_day,
				'rooms_info' => String::array2js($rooms_array),
				'waiting_lists'=>$this->get_waiting_list(),
                'over_book'=>OVER_BOOK
			)
		);
	}
	function get_tour()
    {
		$sql = '
			SELECT TR.* FROM 
				(
					SELECT 
						RESERVATION.ID,
						DECODE(reservation.booking_code,null,tour.name,reservation.booking_code) as name,
						RR1.RESERVATION_ID,
						TO_CHAR(TOUR.ARRIVAL_TIME,\'DD/MM/YYYY\') AS ARRIVAL_TIME,
						TO_CHAR(TOUR.DEPARTURE_TIME,\'DD/MM/YYYY\') AS DEPARTURE_TIME,
						RR1.STATUS,
						customer.name as customer_name,
						TOUR.name as tour_name,
						row_number() over (order by ABS(RR1.time_in - '.time().') ASC) as rownumber,
						(SELECT COUNT(*) FROM RESERVATION_ROOM RR2 WHERE RR2.STATUS=\'BOOKED\' AND RR2.RESERVATION_ID = RESERVATION.ID) AS room_booked,
						(SELECT COUNT(*) FROM RESERVATION_ROOM RR3 WHERE RR3.STATUS=\'CHECKIN\' AND RR3.RESERVATION_ID = RESERVATION.ID) AS room_checkin,
						(SELECT COUNT(*) FROM RESERVATION_ROOM RR4 WHERE RR4.STATUS=\'CHECKOUT\' AND RR4.RESERVATION_ID = RESERVATION.ID) AS room_checkout
					FROM
						RESERVATION
						INNER JOIN RESERVATION_ROOM RR1 ON RR1.RESERVATION_ID = RESERVATION.ID
						LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID
						LEFT OUTER JOIN customer on customer.id = RESERVATION.customer_id
					WHERE
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND (RESERVATION.customer_id<>0 OR reservation.booking_code is not null OR RESERVATION.TOUR_ID <>0 )
					ORDER BY 
						ABS(RR1.time_in - '.time().') ASC
				) TR
			WHERE 
				rownumber<=20	
		';//AND reservation.booking_code is not null
		$tours = DB::fetch_all($sql);
		$i=0;		
		foreach($tours as $key=>$value){
			$tours[$key]['i'] = ++$i;
		}
		return $tours;
	}
	function get_books_without_room_return_arr()
    {
		$cond = '
			reservation.portal_id = \''.PORTAL_ID.'\'
			AND reservation_room.room_id is null
			and reservation_room.status = \'BOOKED\' AND reservation_room.arrival_time <= \''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->year)).'\'
			AND reservation_room.departure_time > \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
		';
		if(Url::get('r_r_id') && Url::get('r_r_id')!=''){
			$cond .= ' AND reservation_room.id<>'.Url::get('r_r_id').'';
		}
		//AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
		$sql = '
			SELECT * FROM
			(
				select 
					reservation_room.id as id
					,reservation_room.reservation_id
					,TO_CHAR(reservation_room.ARRIVAL_TIME,\'DD/MM/YYYY\') AS arrival
					,TO_CHAR(reservation_room.DEPARTURE_TIME,\'DD/MM/YYYY\') AS departure
					,TO_CHAR(reservation.cut_of_date,\'DD/MM/YYYY\') AS cut_of_date
					,reservation.booking_code
					,reservation_room.adult AS adult
					,reservation_room.child AS child
					,reservation_room.room_level_id as acount
					,reservation_room.room_level_id
					,room_level.brief_name as room_level
					,customer.name as customer_name
					,tour.name as tour_name
					,row_number() over (order by reservation_room.reservation_id DESC) as rownumber
				from 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					left outer join party on party.user_id = reservation.portal_id
					
					left outer join room_level on room_level.id = reservation_room.room_level_id 
					left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
					left outer join traveller on reservation_traveller.traveller_id = traveller.id
					left outer join tour on reservation.tour_id = tour.id
					left outer join customer on reservation.customer_id = customer.id
				where 
					'.$cond.'
				order by 
					reservation_room.reservation_id DESC
			)
			WHERE
			 	rownumber > 0 and rownumber<= 30000
		';//left outer join room on room.id = reservation_room.room_id
		$reservations = DB::fetch_all($sql);
		return $reservations;
	}
	function get_books_without_room()
    {
		$cond = '
			reservation.portal_id = \''.PORTAL_ID.'\'
			AND reservation_room.room_id is null
			and reservation_room.status = \'BOOKED\' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
			
		';
		//AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
		$sql = '
			SELECT * FROM
			(
				select 
					reservation_room.id as id
					,reservation_room.reservation_id
					,TO_CHAR(reservation_room.ARRIVAL_TIME,\'DD/MM/YYYY\') AS arrival
					,TO_CHAR(reservation.cut_of_date,\'DD/MM/YYYY\') AS cut_of_date
					,reservation.booking_code
					,reservation_room.adult AS adult
					,reservation_room.child AS child
					,reservation_room.room_level_id as acount
					,room_level.brief_name as room_level
					,customer.name as customer_name
					,tour.name as tour_name
					,row_number() over (order by reservation_room.reservation_id DESC) as rownumber
				from 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join party on party.user_id = reservation.portal_id
					left outer join room on room.id = reservation_room.room_id
					left outer join room_level on room_level.id = reservation_room.room_level_id 
					left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
					left outer join traveller on reservation_traveller.traveller_id = traveller.id
					left outer join tour on reservation.tour_id = tour.id
					left outer join customer on reservation.customer_id = customer.id
				where 
					'.$cond.'
				order by 
					reservation_room.reservation_id DESC
			)
			WHERE
			 	rownumber > 0 and rownumber<= 3000
		';
		$reservations = DB::fetch_all($sql);
		$arr = array();
		foreach($reservations as $key=>$reser)
        {
			if(isset($arr[$reser['reservation_id']]))
            {
				if(isset($arr[$reser['reservation_id']][$reser['room_level']]))
                {
					$arr[$reser['reservation_id']][$reser['room_level']]++;	
					$arr[$reser['reservation_id']]['total_room']++;
					$arr[$reser['reservation_id']]['room_level'] = str_replace(',('.($arr[$reser['reservation_id']][$reser['room_level']]-1).')'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'],$arr[$reser['reservation_id']]['room_level']);
					$arr[$reser['reservation_id']]['room_level'] = str_replace('('.($arr[$reser['reservation_id']][$reser['room_level']]-1).')'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'],$arr[$reser['reservation_id']]['room_level']);
					$arr[$reser['reservation_id']]['room_level'] = str_replace('(1)'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'].'',$arr[$reser['reservation_id']]['room_level']);
					$arr[$reser['reservation_id']]['room_level'] = str_replace(',(1)'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'],$arr[$reser['reservation_id']]['room_level']);	
				}
                else
                {
					$arr[$reser['reservation_id']]['total_room']++;
					$arr[$reser['reservation_id']][$reser['room_level']] = 1;
					$arr[$reser['reservation_id']]['room_level'] .= ',(1)'.$reser['room_level'];	
				}
				$arr[$reser['reservation_id']]['adult'] += $reser['adult'];
				$arr[$reser['reservation_id']]['child'] += $reser['child'];
			}
            else
            {
				$arr[$reser['reservation_id']] = $reser;
				$arr[$reser['reservation_id']]['room_level'] = '(1)'.$reser['room_level'];	
				$arr[$reser['reservation_id']][$reser['room_level']] = 1;
				$arr[$reser['reservation_id']]['total_room'] = 1;	
			}
		}

		/*if(User::is_admin(false,ANY_CATEGORY)){
			System::Debug($arr);
		}*/
		return $arr;
	}
	function get_rooms()
	{
		$sql = '
			select 
				distinct room.id,
				room.name,
				room.floor,
				room_level.price,
				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
				0 AS overdue_reservation_id,
				\'\' as house_status,
				\'AVAILABLE\' as status,
				\'\' AS note,
				\'\' AS hk_note,
				minibar.id as minibar_id,
				0 as confirm,
				0 as extra_bed,
				0 as out_of_service,
				1 as can_book,
				room.position,
				room.room_level_id,
				room.room_type_id,
				room_level.is_virtual,
				room_level.brief_name as room_level_name,
				\'\' as note
			from
				room
				inner join room_level on room_level.id = room.room_level_id
				inner join room_type on room_type.id = room_type_id 
				left outer join minibar on room.id = minibar.room_id 
			where
				room.portal_id = \''.PORTAL_ID.'\'
			order by 
				floor, 
				room.position
		';
		//'.(Url::get('room_level_id')?' AND room.room_level_id = '.Url::iget('room_level_id').'':'').'
		$rooms = DB::fetch_all($sql);
		foreach($rooms as $key=>$room)
		{
			$rooms[$key]['price'] = System::display_number($room['price']);
		}
		return $rooms;
	}
    
    function get_room_statuses_new()
    {
      
        //CASE WHEN reservation_room.status = \'CHECKOUT\' THEN (CASE WHEN room_status.house_status = \'DIRTY\' THEN \'CHECKOUT\' ELSE \'AVAILABLE\' END) ELSE room_status.status END status,
        //CASE WHEN reservation_room.status = \'CHECKOUT\' THEN \'CHECKOUT\' ELSE (CASE WHEN room_status.house_status=\'REPAIR\' THEN room_status.house_status ELSE room_status.status END) END status,
        $sql = '
        select
            room_status.id,
            DECODE(reservation.customer_id,0,\'\',concat(customer.name,concat(\' \',customer.address))) as company_name,
            room_status.room_id,
            room_status.reservation_id,
            TO_CHAR(room_status.start_date,\'dd/mm/yyyy\') as start_date,
            TO_CHAR(room_status.end_date,\'dd/mm/yyyy\') as end_date,
            CASE 
                WHEN 
                    reservation_room.status = \'CHECKOUT\'
                THEN 
                    CASE
                        WHEN
                            reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                        THEN
                            \'CHECKOUT\'
                        ELSE
                            room_status.status
                    END
                ELSE
                    CASE
                        WHEN
                            reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' 
                            AND reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                        THEN
                            \'DAYUSED\'
                        ELSE
                            CASE
                                WHEN
                                    reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' AND reservation_room.time_out < '.time().'
                                    AND reservation_room.arrival_time != \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                                THEN
                                    \'OVERDUE\'
                                ELSE
                                    CASE
                                        WHEN 
                                            reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' AND reservation_room.time_out > '.time().'
                                            AND reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                                        THEN
                                            \'EXPECTED_CHECKOUT\'
                                        ELSE
                                            CASE
                                                WHEN
                                                    reservation_room.status = \'BOOKED\'
                                                    AND 1 = '.DISPLAY_BOOK_OVERDUE.' 
                                                    AND (reservation_room.time_in + '.(TIME_BOOK_OVERDUE * 60).') <= '.time().'
                                                THEN
                                                    \'OVERDUE_BOOKED\'
                                                ELSE
                                                    room_status.status
                                             END
                                        END
                            END
                    END
            END status,
            house_status,
            reservation.id as reservation_code,
            reservation_room.status as reservation_status,
            reservation_room.price,
            reservation_room.note as room_note,
            reservation_room.adult,
            reservation_room.child,
            reservation_room.confirm,
            reservation_room.net_price,
            reservation_room.note_change_room,
            room_status.extra_bed,
            room_status.out_of_service,
            reservation.tour_id,
            DECODE(reservation.booking_code,null,tour.name,reservation.booking_code) as tour_name,
            reservation_room.tax_rate,
            reservation_room.service_rate,
            reservation_room.id as reservation_room_id,
            reservation_room.time_in,
            reservation_room.time_out,
            reservation_room.departure_time,
            reservation_room.arrival_time,
            CASE WHEN reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' THEN reservation_room.time_out - reservation_room.time_in ELSE reservation_room.departure_time - reservation_room.arrival_time END AS duration,
            room_status.in_date,
            DECODE(reservation_room.status,\'CHECKIN\',1,(DECODE(reservation_room.status,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)))) AS status_order,
            reservation.color,
            reservation_room.foc,
            reservation_room.foc_all,
            customer.name as customer_name,
            reservation_room.user_id,
            room_status.note as hk_note,
            reservation_room.note as room_note,
            reservation.note as group_note,
            \'\' as note,
            room_level.is_virtual,
            room_type.id as room_type,
            --start: KID them de loai truong hop doi phong checkin
            reservation_room.change_room_from_rr,
            reservation_room.change_room_to_rr,
            reservation_room.old_arrival_time
            --end
        from
            room_status
            left outer join reservation_room on reservation_room.id = room_status.reservation_room_id
            left outer join room_level on room_level.id = reservation_room.room_level_id
            left outer join reservation on reservation.id = room_status.reservation_id
            left outer join traveller on traveller.id = reservation_room.traveller_id
            left outer join customer on reservation.customer_id = customer.id
            left outer join tour on tour.id = reservation.tour_id
            left outer join room on room.room_level_id = room_level.id
            left outer join room_type on room.room_type_id = room_type.id
        where 
            reservation.portal_id = \''.PORTAL_ID.'\'
            --and reservation_room.note_change_room
            and room_status.status <> \'CANCEL\' AND reservation_room.status<>\'CANCEL\' AND room_status.status <> \'AVAILABLE\'
            AND room_status.in_date = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
  order by 
            status_order DESC,ABS(reservation_room.time_in - '.time().') DESC
        ';
        //AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
        //AND room_status.in_date<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
        $room_statuses = DB::fetch_all($sql);
        
        //System::debug($room_statuses);
        //System::debug($room_statuses);
        $sql = '
            SELECT
                room_status.*,\'\' as full_name,room_status.note as hk_note
                ,TO_CHAR(room_status.start_date,\'dd/mm/yyyy\') as start_date
                ,TO_CHAR(room_status.end_date,\'dd/mm/yyyy\') as end_date
            FROM
                room_status
                INNER JOIN room ON room.id = room_status.room_id
                LEFT OUTER JOIN reservation ON reservation.id = room_status.reservation_id
                LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
            WHERE
                room.portal_id = \''.PORTAL_ID.'\'
                and (room_status.status = \'AVAILABLE\' OR reservation_room.status = \'CHECKOUT\') AND (room_status.note is not null OR room_status.house_status is not null)
                AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                AND room_status.in_date<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
            ORDER BY 
                room_status.id DESC
        ';
        //echo 'thuy'.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year));
        //echo 'le'.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year));
        $available_rooms = DB::fetch_all($sql);
        //System::Debug($available_rooms);
        $house_status_arr = array();
        foreach($available_rooms as $k=>$v){
            $available_rooms[$k]['tour_id'] = '';
            $available_rooms[$k]['tour_name'] = '';

            $available_rooms[$k]['customer_name'] = '';
            $available_rooms[$k]['foc'] = '';
            $available_rooms[$k]['foc_all'] = '';
            $available_rooms[$k]['time_in'] = '';
            $available_rooms[$k]['time_out'] = '';
            $available_rooms[$k]['arrival_time'] = '';
            $available_rooms[$k]['departure_time'] = '';
            $available_rooms[$k]['price'] = '';
            $available_rooms[$k]['color'] = '';
            $available_rooms[$k]['full_name'] = '';
            if($v['house_status']){
                $house_status_arr[$v['room_id']] = $v['house_status'];
            }
        }
        $reservation_ids = '(0,0';
        foreach($room_statuses as $id=>$room_status)
        {
            $k_ = '';
            foreach($available_rooms as $k=>$v){
                if($v['room_id'] == $room_status['room_id'] and $v['in_date'] == $room_status['in_date']){
                    $room_statuses[$id]['hk_note'] = $v['note']?$v['note']:'';
                    $room_statuses[$id]['house_status'] = $v['house_status']?$v['house_status']:'';
                    unset($available_rooms[$k]);
                }
            }
            if(isset($house_status_arr[$room_status['room_id']])){
                $room_statuses[$id]['house_status'] = $house_status_arr[$room_status['room_id']];
            }
            if(isset($available_rooms[$k_])){
                unset($available_rooms[$k_]);
            }
            if($room_status['status']=='BOOKED'){
                $room_statuses[$id]['duration'] = $room_status['duration']?$room_status['duration'].' '.Portal::language('night'):Portal::language('in_day');
            }else{
                $duration = 0;//.' '.Portal::language('hour');
                if(round($room_status['duration']/3600,1)<12){
                    $duration = round($room_status['duration']/3600,1).' '.Portal::language('hour');
                }if(round($room_status['duration']/3600,1)>=12 and round($room_status['duration']/3600,1)<24){
                    $duration = round($room_status['duration']/3600,1).' '.Portal::language('hour');
                }elseif(round($room_status['duration']/3600,1)>=24){
                    $duration = round($room_status['duration']/3600/24).' '.Portal::language('night');
                }
                $room_statuses[$id]['duration'] = $duration;    
            }
            $room_statuses[$id]['arrival_time'] = $room_status['arrival_time']?Date_Time::convert_orc_date_to_date($room_status['arrival_time'],'/'):'';
            $room_statuses[$id]['departure_time'] = $room_status['departure_time']?Date_Time::convert_orc_date_to_date($room_status['departure_time'],'/'):'';                        
            if($room_status['reservation_id']){
                $reservation_ids .= ','.$room_status['reservation_id'];
            }
        }
        $room_statuses += $available_rooms;
        
        $reservation_ids .= ')';
        $all_travellers = DB::fetch_all('select 
                reservation_traveller.id,
                reservation_traveller.traveller_id,
                concat(DECODE(traveller.gender,1,\'Mr. \',\'Ms. \'),concat(traveller.first_name,concat(\' \',traveller.last_name))) as customer_name,
                reservation_room.room_id,
                reservation_room.reservation_id,
                reservation_room.id as reservation_room_id,
                room.name as room_name,
                to_char(traveller.birth_date,\'DD/MM\') as birth_date,
                country.name_1 as country_name,
                to_char(traveller.birth_date,\'dd/mm/yyyy\') as age,
                reservation_traveller.arrival_time,
                reservation_traveller.departure_time
            from
                reservation_traveller
                inner join traveller on traveller.id = reservation_traveller.traveller_id
                inner join reservation_room on reservation_room_id=reservation_room.id
                inner join reservation on reservation.id=reservation_room.reservation_id
                inner join room on room_id=room.id
                left outer join country on traveller.nationality_id = country.id
            where
                reservation.portal_id = \''.PORTAL_ID.'\' and reservation_room.reservation_id IN '.$reservation_ids.'
                AND reservation_traveller.status=\'CHECKIN\' AND reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'CHECKOUT\'
            '
        );//AND reservation_traveller.departure_time >= reservation_room.time_out
    
        $travellers = array();
        $birth_date_arr = array();
        $i=0;$j=1;
        foreach($all_travellers as $traveller_id=>$traveller)
        {
            $i++;
            unset($traveller['reservation_id']);
            unset($traveller['room_id']);
            $traveller['time_in'] = date('H:i',$traveller['arrival_time']);
            $traveller['time_out'] = date('H:i',$traveller['departure_time']);
            $traveller['date_in'] = date('d/m/Y',$traveller['arrival_time']);
            $traveller['date_out'] = date('d/m/Y',$traveller['departure_time']);
            //unset($traveller['id']);
            if($traveller['birth_date'] == date('d/m') || $traveller['birth_date'] == date('d/m',time()+86400)){
                $birth_date_arr[$traveller['traveller_id']]['id'] = $traveller['traveller_id'];
                $birth_date_arr[$traveller['traveller_id']]['birth_date'] = $traveller['birth_date'];
                $birth_date_arr[$traveller['traveller_id']]['name'] = $traveller['customer_name'];
                $birth_date_arr[$traveller['traveller_id']]['room_name'] = $traveller['room_name'];
                $birth_date_arr[$traveller['traveller_id']]['i'] = $j;
                if($traveller['birth_date'] == date('d/m')){
                    $birth_date_arr[$traveller['traveller_id']]['count'] = 0;    
                }
                if($traveller['birth_date'] == date('d/m',time()+86400)){
                    $birth_date_arr[$traveller['traveller_id']]['count'] = 1;    
                }
                $j++;
            }
            $travellers[$all_travellers[$traveller_id]['reservation_room_id']][$all_travellers[$traveller_id]['room_id']][$traveller['id']] = $traveller;
        }
        $sort_arr = array();
        $jj=1;
        if(!empty($birth_date_arr)){
            foreach($birth_date_arr as $kt=>$birth){
                if($birth['count']==0){
                    $sort_arr[$jj]=$birth;
                    $sort_arr[$jj]['i']=$jj;    
                    $jj++;
                }
            }
            foreach($birth_date_arr as $t=>$bir){
                if($bir['count']==1){
                    $sort_arr[$jj]=$bir;    
                    $sort_arr[$jj]['i']=$jj;    
                    $jj++;
                }
            }
        }
        $this->map['birth_date_arr'] = $sort_arr;
        foreach($room_statuses as $id=>$room_status)
        {
            if($room_statuses[$id]['reservation_room_id'])

            {
                if(isset($travellers[$room_status['reservation_room_id']][$room_status['room_id']]))
                {
                    $room_statuses[$id]['travellers'] = $travellers[$room_status['reservation_room_id']][$room_status['room_id']];
                }
                else
                {
                    $room_statuses[$id]['travellers'] = array();
                }
            }
        }
        //System::debug($room_statuses);
        return $room_statuses;
    }
	function get_room_statuses()
	{
		//CASE WHEN reservation_room.status = \'CHECKOUT\' THEN (CASE WHEN room_status.house_status = \'DIRTY\' THEN \'CHECKOUT\' ELSE \'AVAILABLE\' END) ELSE room_status.status END status,
		//CASE WHEN reservation_room.status = \'CHECKOUT\' THEN \'CHECKOUT\' ELSE (CASE WHEN room_status.house_status=\'REPAIR\' THEN room_status.house_status ELSE room_status.status END) END status,
		$cond = '';
		if(Url::get('r_r_id') && Url::get('r_r_id')!='')
        {
			$cond = ' AND reservation_room.id<>'.Url::get('r_r_id').'';
		}
		$sql = '
		select
			room_status.id,
			DECODE(reservation.customer_id,0,\'\',concat(customer.name,concat(\' \',customer.address))) as company_name,
			room_status.room_id,
			room_status.reservation_id,
			CASE 
				WHEN 
					reservation_room.status = \'CHECKOUT\'
				THEN 
					CASE
						WHEN
							reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
						THEN
							\'CHECKOUT\'
						ELSE
							room_status.status
					END
				ELSE
					CASE
						WHEN
							reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
						THEN
							\'DAYUSED\'
						ELSE
							CASE
								WHEN
									reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' AND reservation_room.time_out < '.time().'
								THEN
									\'OVERDUE\'
								ELSE
									room_status.status
							END
					END
			END status,
			house_status,
			reservation.id as reservation_code,
			reservation_room.status as reservation_status,
			reservation_room.price,
			reservation_room.note as room_note,
			reservation_room.adult,
			reservation_room.child,
			reservation_room.confirm,
			reservation_room.net_price,
			room_status.extra_bed,
			room_status.out_of_service,
			reservation.tour_id,
			DECODE(reservation.booking_code,null,tour.name,reservation.booking_code) as tour_name,
			reservation_room.tax_rate,
			reservation_room.service_rate,
			reservation_room.id as reservation_room_id,
			reservation_room.time_in,
			reservation_room.time_out,
			reservation_room.departure_time,
			reservation_room.arrival_time,
			CASE WHEN reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' THEN reservation_room.time_out - reservation_room.time_in ELSE reservation_room.departure_time - reservation_room.arrival_time END AS duration,
			room_status.in_date,
			DECODE(reservation_room.status,\'CHECKIN\',1,(DECODE(reservation_room.status,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)))) AS status_order,
			reservation.color,
			reservation_room.foc,
			reservation_room.foc_all,
			customer.name as customer_name,
			reservation_room.user_id,
			room_status.note as hk_note,
			reservation_room.note as room_note,
			reservation.note as group_note,
			reservation_room.status as r_status,
			reservation_room.room_level_id,
			\'\' as note
		from
			room_status
			left outer join reservation_room on reservation_room.id = room_status.reservation_room_id 
			left outer join reservation on reservation.id = room_status.reservation_id
			left outer join traveller on traveller.id = reservation_room.traveller_id
			left outer join customer on reservation.customer_id = customer.id
			left outer join tour on tour.id = reservation.tour_id
		where
			reservation.portal_id = \''.PORTAL_ID.'\'
			and room_status.status <> \'CANCEL\' AND room_status.status <> \'AVAILABLE\' 
			AND reservation_room.status <> \'CHECKOUT\' AND room_status.change_price<>0
			AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
			AND room_status.in_date<\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
			'.$cond.'
		order by 
			status_order DESC,ABS(reservation_room.time_in - '.time().') DESC
		';
		//reservation_room.arrival_time <> \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\')
		//AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
		//AND room_status.in_date<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
		$room_statuses = DB::fetch_all($sql);
       
		//System::debug($room_statuses);
		$sql = '
			SELECT
				room_status.*,\'\' as full_name,room_status.note as hk_note
				,TO_CHAR(room_status.start_date,\'dd/mm/yyyy\') as start_date
				,TO_CHAR(room_status.end_date,\'dd/mm/yyyy\') as end_date
			FROM
				room_status
				INNER JOIN room ON room.id = room_status.room_id
				LEFT OUTER JOIN reservation ON reservation.id = room_status.reservation_id
				LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
			WHERE
				room.portal_id = \''.PORTAL_ID.'\'
				and (room_status.status = \'AVAILABLE\') AND (room_status.note is not null OR room_status.house_status is not null)
				AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
				AND room_status.in_date<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
			ORDER BY 
				room_status.id DESC
		';
		// OR reservation_room.status = \'CHECKOUT\'
		//echo 'thuy'.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year));
		//echo 'le'.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year));
		$available_rooms = DB::fetch_all($sql);
		//System::Debug($available_rooms);
		$house_status_arr = array();
		foreach($available_rooms as $k=>$v){
			$available_rooms[$k]['tour_id'] = '';
			$available_rooms[$k]['tour_name'] = '';

			$available_rooms[$k]['customer_name'] = '';
			$available_rooms[$k]['foc'] = '';
			$available_rooms[$k]['foc_all'] = '';
			$available_rooms[$k]['time_in'] = '';
			$available_rooms[$k]['time_out'] = '';
			$available_rooms[$k]['arrival_time'] = '';
			$available_rooms[$k]['departure_time'] = '';
			$available_rooms[$k]['price'] = '';
			$available_rooms[$k]['color'] = '';
			$available_rooms[$k]['full_name'] = '';
			if($v['house_status'])
            {
				$house_status_arr[$v['room_id']] = $v['house_status'];
			}
		}
		$reservation_ids = '(0,0';
        
		foreach($room_statuses as $id=>$room_status)
		{
			$k_ = '';
			foreach($available_rooms as $k=>$v)
            {
				if($v['room_id'] == $room_status['room_id'] and $v['in_date'] == $room_status['in_date'])
                {
					$room_statuses[$id]['hk_note'] = $v['note']?$v['note']:'';
					$room_statuses[$id]['house_status'] = $v['house_status']?$v['house_status']:'';
					unset($available_rooms[$k]);
				}
			}
			if(isset($house_status_arr[$room_status['room_id']]))
            {
				$room_statuses[$id]['house_status'] = $house_status_arr[$room_status['room_id']];
			}
			if(isset($available_rooms[$k_]))
            {
				unset($available_rooms[$k_]);
			}
			if($room_status['status']=='BOOKED')
            {
				$room_statuses[$id]['duration'] = $room_status['duration']?$room_status['duration'].' '.Portal::language('night'):Portal::language('in_day');
			}
            else
            {
				$duration = 0;//.' '.Portal::language('hour');
				if(round($room_status['duration']/3600,1)<12)
                {
					$duration = round($room_status['duration']/3600,1).' '.Portal::language('hour');
				}
                if(round($room_status['duration']/3600,1)>=12 and round($room_status['duration']/3600,1)<24)
                {
					$duration = round($room_status['duration']/3600,1).' '.Portal::language('hour');
				}
                elseif(round($room_status['duration']/3600,1)>=24)
                {
					$duration = round($room_status['duration']/3600/24).' '.Portal::language('night');
				}
				$room_statuses[$id]['duration'] = $duration;	
			}
			$room_statuses[$id]['arrival_time'] = $room_status['arrival_time']?Date_Time::convert_orc_date_to_date($room_status['arrival_time'],'/'):'';
			$room_statuses[$id]['departure_time'] = $room_status['departure_time']?Date_Time::convert_orc_date_to_date($room_status['departure_time'],'/'):'';						
			if($room_status['reservation_id'])
            {
				$reservation_ids .= ','.$room_status['reservation_id'];
			}
		}
		$room_statuses += $available_rooms;
		$reservation_ids .= ')';
		$travellers = array();
		foreach($room_statuses as $id=>$room_status)
		{
			if($room_statuses[$id]['reservation_room_id'])
			{
				if(isset($travellers[$room_status['reservation_room_id']][$room_status['room_id']]))
				{
					$room_statuses[$id]['travellers'] = $travellers[$room_status['reservation_room_id']][$room_status['room_id']];
				}
				else
				{
					$room_statuses[$id]['travellers'] = array();
				}
			}
		}
        
		return $room_statuses;
	}
	function get_room_overdues()
	{
		//Danh sach khach o qua han
		//(strtotime($this->month.'/'.$this->day.'/'.$this->year)<time() and strtotime($this->end_month.'/'.$this->end_day.'/'.$this->end_year)>time()-24*3600)
		{
			$sql = '
				select 
					reservation_room.id,
					reservation_room.room_id,
					reservation_room.reservation_id,					
					reservation_room.status,
					reservation_room.time_in,
					reservation_room.time_out,
					reservation_room.arrival_time,
					reservation_room.departure_time,
					reservation_room.tax_rate,
					reservation_room.service_rate,
					reservation_room.price,
					DECODE(reservation_room.status,\'CHECKIN\',1,(DECODE(reservation_room.status,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)))) AS status_order
				from 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
				where
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND reservation_room.room_id is not null
					AND reservation_room.status <> \'CANCEL\' AND reservation_room.status <> \'CHECKOUT\'
					AND reservation_room.departure_time=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
				ORDER BY reservation_room.id DESC
			';
			
			/* AND ((reservation_room.status=\'CHECKIN\' AND FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\')
					OR 	(reservation_room.status=\'BOOKED\' AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'  
						AND (DECODE(reservation_room.time_in,\'\',DATE_TO_UNIX(reservation_room.arrival_time),reservation_room.time_in)<='.time().')
						AND reservation_room.departure_time>=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\')
					)
						*/
			/*AND ((reservation_room.status=\'CHECKIN\' and FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\')
					OR  (reservation_room.status=\'BOOKED\' and DECODE(reservation_room.time_out,\'\',DATE_TO_UNIX(reservation_room.departure_time),reservation_room.time_out)<='.strtotime($this->month.'/'.$this->day.'/'.$this->year).'))*/					
			$items = DB::fetch_all($sql);
			//System::Debug($items);
			return $items;
		}
		//return array();
	}
	function fix_room_map()
    {
		$sql = '
			select
				rr.id,rr.room_id,rr.price,rr.reservation_id,rr.status,rr.time_in,rr.time_out
			from
				reservation_room rr
				left outer join room_status rs on rs.reservation_room_id = rr.id
			where
				rs.id is null
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$value){
			$from_time = $value['time_in'];
			$to_time = $value['time_out'];
			$status = $value['status'];
			switch($value['status'])
            {
				case 'CHECKIN': 
				case 'CHECKOUT': 
				$status='OCCUPIED';break;
			}
			for($i=$from_time;$i<=$to_time;$i=$i+(24*3600))
            {
				if(User::is_admin())
                {
                    echo 'Fix1...';
                }
				DB::insert('room_status',
					array(
						'room_id'=>$value['room_id'],
						'status'=>$status,
						'reservation_id'=>$value['reservation_id'],
						'change_price'=>$value['price'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),
						'reservation_room_id'=>$value['id']
					)
				);
			}
		}
		//---------------------------
		$sql = '
			select
				rs.*,rr.status as rr_status
			from
				room_status rs
				inner join reservation_room rr on rr.id = rs.reservation_room_id
			where
				rs.status is null
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$value)
        {
			if(User::is_admin())
            {
                echo 'Fix2...';
            }
			if($value['rr_status'] == 'CHECKIN' or $value['rr_status'] == 'CHECKIN')
            {
				DB::update('room_status',array('status'=>'OCCUPIED'),'id = '.$key);
			}
            else
            {
				DB::update('room_status',array('status'=>$value['rr_status']),'id = '.$key);
			}
		}
	}
	function get_waiting_list()
    {
		$sql = 'SELECT 
					reservation_room.*
					,TO_CHAR(reservation_room.ARRIVAL_TIME,\'DD/MM/YYYY\') AS arrival,
				FROM reservation_room

					INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				WHERE 
					(reservation_room.room_id is null OR reservation_room.room_id =\'\')
			';
		$waiting_list = DB::fetch_all();	
	}
	function get_room_level()
    {
		return $room_level = DB::fetch_all('
		SELECT
			rl.*,
			(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id) as room_quantity,
			0 as vacant_room 
		FROM	
			room_level rl
		WHERE 1>0
			AND rl.portal_id = \''.PORTAL_ID.'\' 
            --AND (rl.is_virtual IS NULL OR rl.is_virtual = 0)
		
	');	
	}
	function cancel_booking_expired()
    {
		$sql = ' SELECT reservation_room.id,reservation_room.reservation_id
						,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END name
				 FROM reservation_room
						inner join reservation ON reservation_room.reservation_id = reservation.id
						left outer join room on room.id = reservation_room.room_id
				WHERE 
						reservation.cut_of_date = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\'
						AND reservation_room.status = \'BOOKED\'
						AND reservation_room.confirm=0';//-86400
		$rrs = DB::fetch_all($sql);
		$id = 0;
		$check_in_time = explode(':',CHECK_IN_TIME);
		if(time() > ($check_in_time[0]*3600 + $check_in_time[1] * 60 + Date_Time::to_time(date('d/m/Y')) + 18000))
        {// 5h sau giờ checkin mặc định thì mới xóa
			foreach($rrs as $k => $rr)
            {
				if($id==0){ $id=$rr['reservation_id'];$description = '';}
				if($id != $rr['reservation_id'])
                {
					$title = 'Edit reservation Cancel automatic <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'>#'.$id.'</a>';
					System::log('edit',$title,$description,$id);
					$id = $rr['reservation_id'];
					$description = '';	
				}
				$description .= 'Update status room '.$rr['name'].'( Code: <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'&r_r_id='.$rr['id'].'>#'.$rr['id'].'</a>) from BOOKED to CANCEL <br>';
				DB::update('room_status',array('status'=>'CANCEL'),' reservation_room_id='.$rr['id'].'');	
				DB::update('reservation_room',array('status'=>'CANCEL','LASTEST_EDITED_TIME'=>time()),' id='.$rr['id'].'');
			}
		}
		if($id != 0)
        {
			$title = 'Edit reservation Cancel automatic <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'>#'.$id.'</a>';
			System::log('edit',$title,$description,$id);
		}
	}
}
?>