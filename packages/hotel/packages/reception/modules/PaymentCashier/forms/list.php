<?php
class PaymentCashierForm extends Form{
	function PaymentCashierForm(){
		Form::Form('PaymentCashierForm');
//DB::query('delete from traveller_folio');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function draw(){
		require_once 'packages/core/includes/utils/lib/report.php';
		require_once 'packages/core/includes/utils/vn_code.php';
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
				.' and reservation_room.departure_time >= \''.$date_from.'\' and ( reservation_room.status=\'CHECKIN\') AND reservation_room.arrival_time<=\''.$date_from.'\' AND reservation_room.departure_time>=\''.$date_from.'\'';
		$rooms = DB::fetch_all('select reservation_room.room_id as id,room.name from reservation_room 
								INNER JOIN room ON reservation_room.room_id = room.id
								INNER JOIN reservation on reservation_room.reservation_id = reservation.id
								WHERE '.$cond.'
								ORDER BY room.name');
		$room_id_list[0] = '-------';
		$room_id_list = $room_id_list + String::get_list($rooms);
		//echo convert_utf8_to_latin(mb_strtolower(URL::get('guest_name'),'utf-8'));
        $cond .='' 
				.(URL::get('customer_name')?' and LOWER(FN_CONVERT_TO_VN(customer.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('customer_name'),'utf-8')).'%\'':'') 
				.(URL::get('code')?' and reservation.id = '.URL::iget('code').'':'') 
				.(URL::get('room_id')?' and reservation_room.room_id = '.URL::iget('room_id').'':'') 
				.(URL::get('guest_name')?' and LOWER(FN_CONVERT_TO_VN(CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('guest_name'),'utf-8')).'%\'':'')   
				.' AND reservation.portal_id = \''.PORTAL_ID.'\' '
		;
		$cond .= '';
 		$sql_rr = 'SELECT  
						reservation_room.id
						,TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_date
						,TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_date					 
						,room.name as room_name
						,room_type.brief_name as room_type
						,room_type.price
						,\'\' as name_traveller
                        ,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name_traveller_1
						,ROUND((reservation_room.time_out - reservation_room.time_in)/(24*3600)) as nights
						,reservation.id as reservation_id
						,reservation_room.note
						,reservation_room.time_in
						,reservation_room.time_out
						,reservation_type.name as reservation_type
						,payment_type.name_'.Portal::language().' as pmt_type
						,customer.id as customer_id 
						,CONCAT(tour.name,CONCAT(\'-\',customer.name)) as customer_name
						,reservation_traveller.id as rt_id
					FROM
						reservation_room
						INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
						LEFT OUTER JOIN customer on customer.id = reservation.customer_id
						LEFT OUTER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
						LEFT OUTER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
						LEFT OUTER JOIN tour ON tour.id = reservation.tour_id
						INNER JOIN room on reservation_room.room_id = room.id
						INNER JOIN room_type on room_type.id = room.room_type_id
						INNER JOIN reservation_type ON reservation_type.id = reservation_room.reservation_type_id
						LEFT OUTER JOIN payment_type ON payment_type.id = reservation_room.payment_type_id
					WHERE 
						'.$cond.' 
					ORDER BY reservation.id,customer_id,reservation_room.id ASC';			
		$items = DB::fetch_all($sql_rr);
        //System::debug($items);
		require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';		 	
		$customers = DB::fetch_all('select reservation.id as r_id,reservation_room.id as id
									FROM reservation_room
									INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
									INNER JOIN customer on customer.id = reservation.customer_id
									LEFT OUTER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id 
									LEFT OUTER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
									WHERE '.$cond.'
									');
		$arr = array();
		foreach($customers as $key=>$cus){
			if(isset($arr[$cus['r_id']])){
				$arr[$cus['r_id']]['count']++;	
			}else{
				$arr[$cus['r_id']]['count'] = 1;
				$arr[$cus['r_id']]['id'] = $cus['r_id'];	
			}
		}
		$guests = DB::fetch_all('select
									 CONCAT(reservation_room.id,CONCAT(\'_\',reservation_traveller.id)) as id, reservation_room.id as rr_id
									,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name_traveller
								FROM	reservation_room
									INNER JOIN reservation ON reservation.id = reservation_room.reservation_id 
									INNER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id 
									INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
								WHERE reservation_traveller.status=\'CHECKIN\'
								');
		foreach($items as $k=> $value){
			$rr_arr = get_reservation($value['reservation_id'],$value['id'],true);
			if(isset($rr_arr['items'][$k])){
				$items[$k]['total_amount'] = $rr_arr['items'][$k]['total_amount'];		
				$items[$k]['price'] = $rr_arr['items'][$k]['price'];		
			}else{
				$items[$k]['total_amount'] = 0;
				$items[$k]['price'] = 0;
			}
			if(isset($arr[$value['reservation_id']])){
				$items[$k]['countt'] = $arr[$value['reservation_id']]['count'];		
			}else{
				$items[$k]['countt'] = 0;
			}
			foreach($guests as $g=>$guest){
				if($guest['rr_id']==$value['id']){
					$items[$k]['name_traveller'] .= (($items[$k]['name_traveller']=='')?'':' - ').$guest['name_traveller'];	
				}
			}
		}
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
		$this->map['items'] = $items;
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
}
?>