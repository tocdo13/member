<?php
class RoomMapForm extends Form
{
	function RoomMapForm()
	{
		Form::Form('RoomMapForm');
		$this->add('room_ids',new TextType(true,'invalid_room_ids',0,255));
		$this->link_css(Portal::template('hotel').'/css/room.css');//Important
		$this->link_css('skins/default/datetime.css');		
		$this->link_js('packages/hotel/packages/reception/modules/RoomMap/room_map.js');
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
							reservation_id 
						from 
							room_status 
						where 
							room_id=\''.$room_id.'\' and in_date=\''.Date_Time::to_orc_date(Url::sget('in_date')).'\'';
					if($reservation_id = DB::fetch($sql,'reservation_id'))
					{
						DB::update('reservation_room',array(
								'note'=>Url::get('room_note_'.$reservation_id)
							),'reservation_id=\''.$reservation_id.'\' and room_id=\''.$room_id.'\''
						);
					}
				}
				if(Url::sget('in_date')){
					foreach($room_ids as $room_id){
						$start_time = Date_Time::to_time(Url::sget('in_date'));
						$end_time = Date_Time::to_time(Url::sget('in_date'));
						$time = $start_time;
						while($time<=$end_time){
							$cond = 'status<>\'CANCEL\'
								AND in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
								AND room_id=\''.$room_id.'\'';
							$sql = 'SELECT * FROM room_status WHERE '.$cond.'';
							if(DB::exists($sql))
							{
								DB::update('room_status',
									array(
									'note'=>Url::get('note'),
									'house_status'=>Url::get('house_status')
									),
									$cond
								);
							}else{
								DB::insert('room_status',
									array(
									'note'=>Url::get('note'),
									'room_id'=>$room_id,
									'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),
									'status'=>'AVAILABLE',
									'house_status'=>Url::get('house_status')
									)
								);
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
		$this->cancel_booking_expired();
		DB::query('delete from room_status where reservation_id = 0 and house_status is null and note is null');
		if(!isset($_REQUEST['in_date'])){
			$_REQUEST['in_date'] = date('d/m/Y');
		}
		$current_time = Date_Time::to_time(Url::get('in_date'));
		$this->year = date('Y',$current_time);
		$this->month = date('m',$current_time); 
		$this->day = date('d',$current_time);
		$this->end_year = date('Y',$current_time);
		$this->end_month = date('m',$current_time);
		$this->end_day = date('d',($current_time+86400-1));
		$rooms = $this->get_rooms();
		$this->map = array();
		$room_statuses = $this->get_room_statuses();
		$this->map['tours'] = $this->get_tour();
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
		$room_overdues = $this->get_room_overdues();
		$room_id = 0;
		$status_arr = array();
		$day_use_arr = array();
		//System::Debug($room_statuses);
		foreach($room_statuses as $key=>$room_status)
		{	
			if($room_status['departure_time']==$this->day.'/'.$this->month.'/'.$this->year){
				$this->map['total_checkout_today_room']++;
			}
			if($room_status['house_status']=='REPAIR'){
				$this->map['total_available_room'] --;
				$this->map['total_repaire_room']++;
			}
			if(isset($rooms[$room_status['room_id']]))
			{	
				if(isset($room_status['group_note']) and $room_status['group_note'] and $room_status['status']!='CHECKOUT'){
					$rooms[$room_status['room_id']]['note'] = Portal::language('group_note').': '.$room_status['group_note']."\n";	
				}
				if(isset($room_status['room_note']) and $room_status['room_note'] and $room_status['status']!='CHECKOUT'){
					$rooms[$room_status['room_id']]['note'] .= Portal::language('room_note').': '.$room_status['room_note']."\n";
				}
				if((isset($room_status['hk_note']) and $room_status['hk_note'] and $room_status['status']!='CHECKOUT')){
					$rooms[$room_status['room_id']]['note']= Portal::language('hk_note').': '.$room_status['hk_note']."\n";
					$rooms[$room_status['room_id']]['hk_note'] = Portal::language('hk_note').': '.$room_status['hk_note']."";
				}else if((isset($room_status['note'])&& $room_status['note'])){
					$rooms[$room_status['room_id']]['note'] .= $room_status['note'];
				}else{
					$rooms[$room_status['room_id']]['hk_note'] = '';
				}
				$status_arr[$room_status['room_id']]['note'] = $rooms[$room_status['room_id']]['hk_note']."\n";
				$status_arr[$room_status['room_id']]['hk_note'] = $rooms[$room_status['room_id']]['hk_note'];
				if(isset($room_overdues[$room_status['room_id']]) and $room_status['in_date'] == Date_Time::to_orc_date($this->day.'/'.$this->month.'/'.$this->year)){
					//unset($room_statuses[$room_status['id']]);
				}
				{
					$status_arr[$room_status['room_id']] = $room_status;
					if(isset($room_status['travellers'])){
						$rooms[$room_status['room_id']]['travellers'] = $room_status['travellers'];
					}
					//if(!isset($rooms[$room_status['room_id']]['customer_name']))
					{
						$rooms[$room_status['room_id']]['customer_name'] = $room_status['customer_name']?$room_status['customer_name']:'&nbsp;';
					}
					//if(!isset($rooms[$room_id]['tour_name']))
					{
						$rooms[$room_status['room_id']]['tour_name'] = $room_status['tour_name']?$room_status['tour_name']:'&nbsp;';
					}
					if($room_status['room_id']!=$room_id)
					{
						$room_id = $room_status['room_id'];
						unset($room_status['room_id']);						
						$rooms[$room_id]['tour_id'] = $room_status['tour_id'];
						$rooms[$room_id]['color'] = $room_status['color'];
						$rooms[$room_id]['foc'] = $room_status['foc'];
						$rooms[$room_id]['time_in'] = $room_status['time_in'];
						$rooms[$room_id]['time_out'] = $room_status['time_out'];
						$rooms[$room_id]['price'] = System::display_number($room_status['price']);
						if($room_status['arrival_time']==$this->day.'/'.$this->month.'/'.$this->year){
							$this->map['total_checkin_today_room']++;
							if($room_status['departure_time']!==$this->day.'/'.$this->month.'/'.$this->year){
							$this->map['total_occupied_today']++;
							}
						}
						$rooms[$room_id]['can_book'] = 1;
						switch($room_status['status']){
							case 'BOOKED':
								$rooms[$room_id]['status'] = 'BOOKED';
								$this->map['total_booked_room']++;
								$rooms[$room_id]['can_book'] = 0;
								break;
							case 'DAYUSED':
								$rooms[$room_id]['status'] = 'OCCUPIED';
								$rooms[$room_id]['status'] = 'DAYUSED';
								$this->map['total_checkin_room']++;
								$this->map['total_dayused_room']++;
								$rooms[$room_id]['can_book'] = 0;
								$day_use_arr[$room_id] = $room_status['time_in'];
								break;				
							case 'OCCUPIED':
								$rooms[$room_id]['status'] = 'OCCUPIED';
								$this->map['total_checkin_room']++;
								$this->map['total_occupied_today']++;
								$rooms[$room_id]['can_book'] = 0;
								break;
							case 'OVERDUE':
								$rooms[$room_id]['status'] = 'OVERDUE';
								$this->map['total_overdue_room']++;	
								$rooms[$room_id]['can_book'] = 0;
								$rooms[$room_id]['tax_rate']= $room_status['tax_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
								$rooms[$room_id]['service_rate']= $room_status['service_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
								break;
							case 'CHECKOUT':	
								$rooms[$room_id]['status'] = 'CHECKOUT';
								$this->map['total_checkout_room']++;
								break;
							default:
								$rooms[$room_id]['status'] = $room_status['status'];
								break;
						}
						$room_status['status'] = $rooms[$room_id]['status'];
						$rooms[$room_id]['house_status'] = $room_status['house_status'];
						$rooms[$room_id]['extra_bed'] = $rooms[$room_id]['extra_bed'] or $room_status['extra_bed'];
						$rooms[$room_id]['out_of_service'] = $rooms[$room_id]['out_of_service'] or $room_status['out_of_service'];
						if($room_status['departure_time']<strtotime($this->month.'/'.$this->day.'/'.$this->year)+24*3600)
						{
							$rooms[$room_id]['can_book'] = 0;
						}
						$room_status['end_time'] = $room_status['departure_time'];
						if($room_status['reservation_id']==0){
							$room_status['price'] = System::display_number($rooms[$room_id]['price']);
						}
						if((isset($rooms[$room_id]['tax_rate']) and !$rooms[$room_id]['tax_rate']) or !isset($rooms[$room_id]['tax_rate'])){
							$rooms[$room_id]['tax_rate'] = '';
						}
						if((isset($rooms[$room_id]['service_rate']) and !$rooms[$room_id]['service_rate']) or !isset($rooms[$room_id]['service_rate'])){
							$rooms[$room_id]['service_rate'] = '';
						}
						if(isset($room_status['tax_rate'])){
							$rooms[$room_id]['tax_rate'] = $room_status['tax_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
						}
						if(isset($room_status['service_rate'])){
							$rooms[$room_id]['service_rate'] = $room_status['service_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
						}
					}else{
						if(isset($status_arr[$room_status['room_id']]) and $status_arr[$room_status['room_id']]!='AVAILABLE'){
							$rooms[$room_status['room_id']]['status'] = $status_arr[$room_status['room_id']]['status'];
							$rooms[$room_status['room_id']]['time_in'] = $status_arr[$room_status['room_id']]['time_in'];
							$rooms[$room_status['room_id']]['time_out'] = $status_arr[$room_status['room_id']]['time_out'];
							$rooms[$room_status['room_id']]['note'] = $status_arr[$room_status['room_id']]['note'];
							$rooms[$room_status['room_id']]['hk_note'] = $status_arr[$room_status['room_id']]['hk_note'];
						}
					}
					if(isset($room_status['tax_rate'])){
						$room_status['tax_rate'] = $room_status['tax_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
					}
					if(isset($room_status['service_rate'])){
						$room_status['service_rate'] = $room_status['service_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
					}
					$room_status['time_in'] = $room_status['time_in']?' ('.date('H:i\'',$room_status['time_in']).')':'';
					$room_status['time_out'] = $room_status['time_out']?' ('.date('H:i\'',$room_status['time_out']).')':'';
					$room_status['price'] = System::display_number($room_status['price']);
					$rooms[$room_id]['reservations'][$room_status['id']] = $room_status;/////////////sua id//////////////
					//System::Debug($rooms[$room_id]['reservations'][$room_status['id']]);
				}
			}
		}
		//System::debug($rooms);
		//System::Debug($room_overdues);
		foreach($room_overdues as $room_overdue)
		{
			if(!isset($day_use_arr[$room_overdue['id']]) and isset($rooms[$room_overdue['id']]))
			{
				if($room_overdue['status']=='CHECKIN')//if(($room_overdue['status']=='CHECKIN') or ($rooms[$room_overdue['id']]['status']=='AVAILABLE' and $room_overdue['status']=='BOOKED'))
				{
					if(Date_Time::convert_orc_date_to_date($room_overdue['departure_time'],'/')==$this->end_month.'/'.$this->end_day.'/'.$this->end_year and $room_overdue['arrival_time']==$room_overdue['departure_time']){

						//$room_status = 'OVERDUE';
						//$this->map['total_overdue_room']++;
						//$rooms[$room_overdue['id']]['status'] = $room_status;
						//$rooms[$room_overdue['id']]['can_book'] = 0;
						//$rooms[$room_overdue['id']]['price'] = $room_overdue['price'];
					}
					{
						$rooms[$room_overdue['id']]['overdue_status'] = 'OVERDUE';
						$rooms[$room_overdue['id']]['overdue_reservation_id'] = $room_overdue['reservation_id'];
						$rooms[$room_overdue['id']]['overdue_reservation_code'] = $room_overdue['reservation_id'];
					}
				}else{
					if($room_overdue['time_in']<=time() and strtotime($this->month.'/'.$this->day.'/'.$this->year)<=time()){
						$room_status = 'RESOVER';
						$this->map['total_resover_room']++;
						$this->map['total_booked_room']--;
						$rooms[$room_overdue['id']]['status'] = $room_status;
						$rooms[$room_overdue['id']]['can_book'] = 0;
						$rooms[$room_overdue['id']]['price'] = System::display_number($room_overdue['price']);
						$rooms[$room_overdue['id']]['tax_rate']= $room_overdue['tax_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
						$rooms[$room_overdue['id']]['service_rate']= $room_overdue['service_rate']?'<span style="color:#FF0000;font-weight:bold;">+</span>':'';
					}
					{
						$rooms[$room_overdue['id']]['overdue_reservation_id'] = $room_overdue['reservation_id'];
						$rooms[$room_overdue['id']]['overdue_reservation_code'] = $room_overdue['reservation_id'];
					}
				}
			}
		}
		$floors = array();
		$last = false;
		$i=0;
		foreach($rooms as $key=>$room)
		{	
			if(isset($room['reservations'])){
				$rooms[$key]['old_reservations'] = $room['reservations'];
				$room['old_reservations'] = $room['reservations'];
				$arr = $room['reservations'];
				$rooms[$key]['reservations'] = array_reverse($room['reservations']);
				$room['old_reservations'] = array_reverse($arr);
				foreach($room['old_reservations'] as $k=> $value){
					if($k == 0 && $value['status']=='CHECKOUT'){
						$room['old_reservations'][$value['id']] = $value;	
						//unset($room['old_reservations'][$k]);
					}
				}
			}else{  
				$room['reservations'] = array();
				$room['old_reservations'] = array();
			}
			if(!isset($room['tax_rate'])){
				$rooms[$key]['tax_rate'] = '';
			}
			if(!isset($room['service_rate'])){
				$rooms[$key]['service_rate'] = '';
			}
			if($room['status']=='AVAILABLE' and !$room['is_virtual']){
				$this->map['total_available_room']++;
			}
			$i++;
			if($last!=$room['floor'])
			{
				$last = $room['floor'];
				$floors[$room['floor']]=
					array(
						'name'=>$room['floor'],
						'rooms'=>array()
					);
			}
			$floors[$last]['rooms'][$i] = $room;
		}
		//System::debug($floors);
		$room_levels = DB::select_all('room_level','portal_id=\''.PORTAL_ID.'\'');
		$this->map['books_without_room'] = $this->get_books_without_room();
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
		$this->parse_layout('room_map', $this->map+
			get_time_parameters()+
			array(
				'floors'=>$floors,
				'year'=>$this->year,
				'month'=>$this->month,
				'day'=>$this->day,
				'end_month'=>date('m',$current_time+86400),
				'room_levels'=>$room_levels,
				'room_level_id_list'=>array(''=>Portal::language('All')) + String::get_list($room_levels),
				'end_day'=>date('d',$current_time+86400),
				'rooms_info' => String::array2js($rooms),
				'waiting_lists'=>$this->get_waiting_list()
			)
		);
	}
	function get_tour(){
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
	function get_books_without_room(){
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
			 	rownumber > 0 and rownumber<= 30
		';
		$reservations = DB::fetch_all($sql);
		$arr = array();
		foreach($reservations as $key=>$reser){
			if(isset($arr[$reser['reservation_id']])){
				if(isset($arr[$reser['reservation_id']][$reser['room_level']])){
					$arr[$reser['reservation_id']][$reser['room_level']]++;	
					$arr[$reser['reservation_id']]['room_level'] = str_replace(',('.($arr[$reser['reservation_id']][$reser['room_level']]-1).')'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'],$arr[$reser['reservation_id']]['room_level']);
					$arr[$reser['reservation_id']]['room_level'] = str_replace('('.($arr[$reser['reservation_id']][$reser['room_level']]-1).')'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'],$arr[$reser['reservation_id']]['room_level']);
					$arr[$reser['reservation_id']]['room_level'] = str_replace('(1)'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'].'',$arr[$reser['reservation_id']]['room_level']);
					$arr[$reser['reservation_id']]['room_level'] = str_replace(',(1)'.$reser['room_level'].'','('.$arr[$reser['reservation_id']][$reser['room_level']].')'.$reser['room_level'],$arr[$reser['reservation_id']]['room_level']);	
				}else{
					$arr[$reser['reservation_id']][$reser['room_level']] = 1;
					$arr[$reser['reservation_id']]['room_level'] .= ',(1)'.$reser['room_level'];	
				}
			}else{
				$arr[$reser['reservation_id']] = $reser;
				$arr[$reser['reservation_id']]['room_level'] = '(1)'.$reser['room_level'];	
				$arr[$reser['reservation_id']][$reser['room_level']] = 1;	
			}
		}
		//System::Debug($arr);
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
				'.(Url::get('room_level_id')?' AND room.room_level_id = '.Url::iget('room_level_id').'':'').'
			order by 
				floor, 
				room.position
		';
		$rooms = DB::fetch_all($sql);
		foreach($rooms as $key=>$room)
		{
			$rooms[$key]['price'] = System::display_number($room['price']);
		}
		return $rooms;
	}
	function get_room_statuses()
	{
		//CASE WHEN reservation_room.status = \'CHECKOUT\' THEN (CASE WHEN room_status.house_status = \'DIRTY\' THEN \'CHECKOUT\' ELSE \'AVAILABLE\' END) ELSE room_status.status END status,
		//CASE WHEN reservation_room.status = \'CHECKOUT\' THEN \'CHECKOUT\' ELSE (CASE WHEN room_status.house_status=\'REPAIR\' THEN room_status.house_status ELSE room_status.status END) END status,
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
			room_status.extra_bed,
reservation_room.net_price,
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
			customer.name as customer_name,
			reservation_room.user_id,
			room_status.note as hk_note,
			reservation_room.note as room_note,
			reservation.note as group_note,
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
			and room_status.status <> \'CANCEL\' AND reservation_room.status<>\'CANCEL\' AND room_status.status <> \'AVAILABLE\'
			AND room_status.in_date = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
		order by 
			status_order DESC,ABS(reservation_room.time_in - '.time().') DESC
		';
		//AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
		//AND room_status.in_date<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
		$room_statuses = DB::fetch_all($sql);
		//System::debug($room_statuses);
		$sql = '
			SELECT
				room_status.*,\'\' as full_name
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
					$room_statuses[$id]['note'] = $v['note']?$v['note']:'';
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
				room.name as room_name,
				to_char(traveller.birth_date,\'DD/MM\') as birth_date,
				country.name_1 as country_name,
				to_char(traveller.birth_date,\'dd/mm/yyyy\') as age
			from
				reservation_traveller
				inner join traveller on traveller.id = reservation_traveller.traveller_id
				inner join reservation_room on reservation_room_id=reservation_room.id
				inner join reservation on reservation.id=reservation_room.reservation_id
				inner join room on room_id=room.id
				left outer join country on traveller.nationality_id = country.id
			where
				reservation.portal_id = \''.PORTAL_ID.'\' and reservation_room.reservation_id IN '.$reservation_ids.'
				AND reservation_traveller.status=\'CHECKIN\'
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
			$travellers[$all_travellers[$traveller_id]['reservation_id']][$all_travellers[$traveller_id]['room_id']][$traveller['id']] = $traveller;
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
			if($room_statuses[$id]['reservation_id'])
			{
				if(isset($travellers[$room_status['reservation_id']][$room_status['room_id']]))
				{
					$room_statuses[$id]['travellers'] = $travellers[$room_status['reservation_id']][$room_status['room_id']];
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
					reservation_room.room_id as id,
					reservation_room.reservation_id,
					reservation_room.status,
					reservation_room.time_in,
					reservation_room.time_out,
					reservation_room.arrival_time,
					reservation_room.departure_time,
					reservation_room.tax_rate,
					reservation_room.service_rate,
					reservation_room.price
				from 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
				where
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND reservation_room.status <> \'CANCEL\' AND reservation_room.status <> \'CHECKOUT\'
					AND ((reservation_room.status=\'CHECKIN\' AND FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\')
					OR 	(reservation_room.status=\'BOOKED\' AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'  
						AND DECODE(reservation_room.time_in,\'\',DATE_TO_UNIX(reservation_room.arrival_time),reservation_room.time_in)<='.time().'
						AND reservation_room.departure_time>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\')
					)
				ORDER BY reservation_room.id DESC
			';
			/*AND ((reservation_room.status=\'CHECKIN\' and FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\')
					OR  (reservation_room.status=\'BOOKED\' and DECODE(reservation_room.time_out,\'\',DATE_TO_UNIX(reservation_room.departure_time),reservation_room.time_out)<='.strtotime($this->month.'/'.$this->day.'/'.$this->year).'))*/					
			$items = DB::fetch_all($sql);
			return $items;
		}
		//return array();
	}
	function fix_room_map(){
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
			switch($value['status']){
				case 'CHECKIN': 
				case 'CHECKOUT': 
				$status='OCCUPIED';break;
			}
			for($i=$from_time;$i<=$to_time;$i=$i+(24*3600)){
				if(User::is_admin()){echo 'Fix1...';}
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
		foreach($items as $key=>$value){
			if(User::is_admin()){echo 'Fix2...';}
			if($value['rr_status'] == 'CHECKIN' or $value['rr_status'] == 'CHECKIN'){
				DB::update('room_status',array('status'=>'OCCUPIED'),'id = '.$key);
			}else{
				DB::update('room_status',array('status'=>$value['rr_status']),'id = '.$key);
			}
		}
	}
	function get_waiting_list(){
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
	function cancel_booking_expired(){
		$sql = ' SELECT reservation_room.id 
				 FROM reservation_room
						inner join reservation ON reservation_room.reservation_id = reservation.id
				WHERE 
						reservation_room.time_in <'.(Date_Time::to_time(date('d/m/Y'))-86400).'
						AND reservation_room.status = \'BOOKED\'
						AND reservation_room.confirm=0';//-86400
		$rrs = DB::fetch_all($sql);
		foreach($rrs as $k => $rr){
			DB::delete('room_status',' reservation_room_id='.$rr['id'].'');	
			DB::update('reservation_room',array('status'=>'CANCEL','LASTEST_EDITED_TIME'=>time()),' id='.$rr['id'].'');
		}
	}
}
?>