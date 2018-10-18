<?php
class FolioCreatedForm extends Form{
	function FolioCreatedForm(){
		Form::Form('FolioCreatedForm');
//DB::query('delete from traveller_folio');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
                $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
	function draw(){
		require_once 'packages/core/includes/utils/lib/report.php';
		$total = array();
		$total_service_others = array();
		$items = array();
		$dautuan = $this->get_beginning_date_of_week();
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):Date_Time::to_orc_date(date('d/m/Y'));
		//$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):Date_Time::to_orc_date(date('d/m/Y'));
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		//$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
		$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		//$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
		$month = date('m');
		$reservation = array();
		//--------------------------Doanh thu bar---------------------------------------------------//
		//$this->line_per_page = URL::get('line_per_page',15);
		$cond = $this->cond = ' 1 >0 '
				.' and reservation_room.departure_time >= \''.$date_from.'\' AND reservation_room.arrival_time<=\''.$date_from.'\' AND reservation_room.departure_time>=\''.$date_from.'\'';
		$rooms = DB::fetch_all('select reservation_room.room_id as id,room.name from reservation_room 
								INNER JOIN room ON reservation_room.room_id = room.id
								INNER JOIN reservation on reservation_room.reservation_id = reservation.id
								WHERE '.$cond.'
								ORDER BY room.name');
		$room_id_list[0] = '-------';
		$room_id_list = $room_id_list + String::get_list($rooms);
		$cond .='' 
				.(URL::get('customer_name')?' and UPPER(customer.name) like \'%'.mb_strtoupper(URL::get('customer_name'),'utf-8').'%\'':'') 
				.(URL::get('code')?' and reservation_room.id = '.URL::iget('code').'':'') 
				.(URL::get('room_id')?' and reservation_room.room_id = '.URL::iget('room_id').'':'') 
				.(URL::get('guest_name')?' and (UPPER(traveller.first_name) LIKE \'%'.mb_strtoupper(URL::get('guest_name'),'utf-8').'%\' or UPPER(traveller.last_name) LIKE \'%'.mb_strtoupper(URL::get('guest_name'),'utf-8').'%\')':'')   
				.' AND reservation.portal_id = \''.PORTAL_ID.'\' '
		;
		$cond .= '';			
		//require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';	
		$folios = $this->get_foios($cond);	 	
		//System::Debug($folios);
		$customer_arr = '0';
		$guest = '0';
		$rr_id = '0';
		$r_id = '0';
		foreach($folios as $k=> $folio){
			if($folio['rt_id']=='' && $folio['rr_id']=='' && $folio['customer_id']!=''){
				$customer_arr .= ','.$folio['customer_id'];	
				$r_id .= ','.$folio['r_id'];	
			}else{
				$guest .= ','.$folio['rt_id'];
				$rr_id .= ','.$folio['rr_id'];	
			}
		}
		if($r_id!='0'){
			$reservations = $this->get_reservation($r_id,$customer_arr);
			//System::Debug($reservations);
		}
		if($rr_id!='0'){
			$reservation_rooms = $this->get_reservation_room($rr_id,$guest);
			//System::Debug($reservation_rooms);
		}
		foreach($folios as $k=> $folio){
			if($folio['rt_id']=='' && $folio['rr_id']=='' && $folio['customer_id']!=''){
				if(isset($reservations[$folio['r_id']])){
					$folios[$k] += $reservations[$folio['r_id']];
					$folios[$k]['status'] = '';
					$folios[$k]['price'] = '';
				}
			}else{
				if(isset($reservation_rooms[$folio['rr_id']])){
					$folios[$k] += $reservation_rooms[$folio['rr_id']];
				}
			}
		}
		//System::Debug($folios);
		$view_all = true;
		$users = DB::fetch_all('
			SELECT
				party.user_id as id,party.user_id as name
			FROM
				party
				INNER JOIN account ON party.user_id = account.id
			WHERE
				party.type=\'USER\'
				AND account.is_active = 1
		');
		$this->map['room_id_list'] = $room_id_list;
		$this->map['items'] = $folios;
		$this->map['view_all'] = $view_all;
		$this->parse_layout('list',$this->map);		
	}
	function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * $day_of_week);
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * (6 - $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
	function get_foios($cond=' 1>0'){
		$sql = '    SELECT
						folio.id as id
						,folio.total as total_amount
						,folio.reservation_id as r_id
						,folio.reservation_room_id as rr_id
						,folio.reservation_traveller_id as rt_id
						,folio.customer_id
						,0 as foc
						,0 as deposit  
						,customer.name as customer_name
						,traveller.id as traveller_id
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name
						,room.name as room_name
						,folio.create_time as time
						,folio.user_id
					FROM
						folio
						INNER JOIN reservation ON reservation.id = folio.reservation_id 
						LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
						LEFT OUTER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
						LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
					WHERE 
						'.$cond.'
					ORDER BY
						folio.id DESC
			';
			return DB::fetch_all($sql);
	}
	function get_reservation($cond1,$cond2){
		return $reservations = DB::fetch_all('SELECT
					reservation.id,
					to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
					to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
					(reservation_room.departure_time-reservation_room.arrival_time) as nights, 
					reservation_room.time_in, reservation_room.time_out,
					reservation_room.room_id,
					room.name as room_name,
					customer.address, customer.name as customer_name, 
					reservation_room.foc,
					reservation_room.foc_all,
                                        reservation.note
				FROM reservation_room
					INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
					INNER JOIN room ON room.id = reservation_room.room_id
					INNER JOIN customer ON customer.id = reservation.customer_id
				WHERE reservation.id in ('.$cond1.') and customer_id in ('.$cond2.')
		');	
	}
	function get_reservation_room($cond1,$cond2){
		return $reservation_rooms = DB::fetch_all('SELECT
					reservation_room.id,
					to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
					to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
					(reservation_room.departure_time-reservation_room.arrival_time) as nights, 
					reservation_room.time_in, reservation_room.time_out,
					reservation_room.room_id,
					room.name as room_name,
					concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name, 
					reservation_room.foc,
					reservation_room.foc_all,
					reservation_room.status,
                                        reservation_room.note,
reservation_room.price,
reservation_room.reservation_id
				FROM reservation_room
					INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
					INNER JOIN room ON room.id = reservation_room.room_id
					INNER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
					INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
				WHERE reservation_room.id in ('.$cond1.') and reservation_traveller.id in ('.$cond2.')
		');	
	}
}
?>