<?php
class KaraokeDiscountReportForm extends Form{
	function KaraokeDiscountReportForm(){
		Form::Form('KaraokeDiscountReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw(){
		require_once 'packages/core/includes/utils/lib/report.php';
		
		$total = array();
		$total_service_others = array();
		$items = array();
		$dautuan = $this->get_beginning_date_of_week();
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_week();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):$this->get_end_date_of_week();
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
		$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
		$month = date('m');
		//--------------------------Doanh thu karaoke---------------------------------------------------//
		//$this->line_per_page = URL::get('line_per_page',15);
		$cond = $this->cond = ' 1 >0 '
				.' and karaoke_reservation.departure_time>='.$time_from.' and karaoke_reservation.departure_time<'.$time_to.''
				.' and karaoke_reservation.status=\'CHECKOUT\' ';
		$cond .= (URL::get('portal')?' and karaoke_reservation.portal_id = \'#'.URL::get('portal').'\' ':'') ;
		//----------tìm kiếm theo khách--------------------------------//		
		$sql_guest = 'SELECT DISTINCT
						customer.name as customer_name
						,karaoke_reservation.customer_id as id
					FROM 
						karaoke_reservation 
						INNER JOIN karaoke_reservation_product ON karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
						INNER join customer on customer.id = customer_id
					WHERE 
						'.$cond.'  AND karaoke_reservation_product.discount_rate <>0';
		$guestes = DB::fetch_all($sql_guest);
		//System::debug($sql_guest);
        $guest_list = '<option value="">--'.Portal::language('customer').'--</option>'; 
		if($guestes){
			foreach($guestes as $id => $guest){
				$guest_list .= '<option value="'.$guest['id'].'">'.$guest['customer_name'].'</option> '; 
			}
		}
		//----------tìm kiếm theo người nhận-------------------------------//
		$sql_receiver = 'SELECT DISTINCT
						karaoke_reservation.receiver_name as id
						,karaoke_reservation.receiver_name
					FROM 
						karaoke_reservation 
						INNER JOIN karaoke_reservation_product ON karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
					WHERE 
						'.$cond.'  AND karaoke_reservation_product.discount_rate <>0';
		$receiveres = DB::fetch_all($sql_receiver);
		//System::Debug($receiveres);
		$receiver_list = '<option value="">--'.Portal::language('guest_name').'--</option>'; 
		if($receiveres){
			foreach($receiveres as $id => $recei){
				if($recei['receiver_name'] != ''){
					$receiver_list .= '<option value="'.$recei['receiver_name'].'">'.$recei['receiver_name'].'</option> '; 
				}
			}	
		}
        //echo Date_Time::convert_orc_date_to_date(12/7/1990);
		//----------tìm kiếm theo phòng--------------------------------//
		$sql_room = 'SELECT DISTINCT
						room.name as room_name
						,karaoke_reservation.reservation_room_id as id
					FROM 
						karaoke_reservation 
						INNER JOIN karaoke_reservation_product ON karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
						INNER JOIN reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
						INNER JOIN room ON room.id = reservation_room.room_id 
					WHERE 
						'.$cond.'  AND (karaoke_reservation_product.discount_rate >0 OR karaoke_reservation_product.discount_category >0)';
		$rooms = DB::fetch_all($sql_room);
		$rooms_list = '<option value="">--'.Portal::language('room').'--</option>'; 
		if($rooms){
			foreach($rooms as $id => $room){
				$rooms_list .= '<option value="'.$room['id'].'">'.$room['room_name'].'</option> '; 
			}
		}
		if(Url::get('customer')){
			$cond .= 'and karaoke_reservation.customer_id = '.Url::get('customer').'';	
		}
		if(Url::get('room')){
			$cond .= 'and karaoke_reservation.reservation_room_id = '.Url::get('room').'';	
		}
		if(Url::get('receiver')){
			$cond .= 'and karaoke_reservation.receiver_name = \''.Url::get('receiver').'\'';	
		}
		$sql = '
				SELECT 
					* 
				FROM
					(SELECT
						karaoke_reservation.id,karaoke_reservation.code,karaoke_reservation.departure_time
						,karaoke_reservation.arrival_time
						,karaoke_reservation.total,karaoke_reservation.payment_result
						,karaoke_reservation.user_id as receptionist_id
						,karaoke_reservation.exchange_rate
						,karaoke_reservation.receiver_name
						,karaoke_reservation.karaoke_fee_rate
						,karaoke_reservation.tax_rate
						,customer.name as customer_name
						,room.name as room_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,row_number() over (ORDER BY karaoke_reservation.id) as rownumber
					FROM 
						karaoke_reservation 
						inner join karaoke_reservation_product on karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
						left outer join customer on customer.id = customer_id
						left outer join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join traveller on traveller.id = reservation_room.traveller_id
					WHERE 
						'.$cond.' AND karaoke_reservation_product.discount_rate <>0
					ORDER BY
						karaoke_reservation.id
					)';
				//WHERE
					//rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' AND rownumber<='.(URL::get('no_of_page')*$this->line_per_page).'
				//';
		$items = DB::fetch_all($sql);
		//System::Debug($items);
		$sql = '
				SELECT
					karaoke_reservation_product.id, karaoke_reservation_product.karaoke_reservation_id,
					karaoke_reservation_product.price,karaoke_reservation_product.product_id,
					karaoke_reservation_product.quantity_discount,karaoke_reservation_product.discount_rate,
					karaoke_reservation_product.discount,karaoke_reservation_product.quantity,
					karaoke_reservation.exchange_rate,product.name_'.Portal::language().' as product_name
				FROM
					karaoke_reservation_product
					inner join karaoke_reservation on karaoke_reservation.id = karaoke_reservation_product.karaoke_reservation_id
					inner join product ON product.id = karaoke_reservation_product.product_id
					left outer join customer on customer.id = karaoke_reservation.customer_id
				WHERE 
					'.$cond.'
					AND (karaoke_reservation_product.discount_rate >0 OR karaoke_reservation_product.discount_category >0)
					ORDER BY karaoke_reservation_product.karaoke_reservation_id
					';
		$item_details = DB::fetch_all($sql);
		foreach($items as $key=>$value){
			$items[$key]['flag'] = 1;
			$items[$key]['arrival_time'] = date('d/m/Y h:m',$value['arrival_time']);
		}
		foreach($item_details as $k=>$v)
		{
			$item_details[$k]['flag'] = 1;
		}
		//System::Debug($item_details);
		$total_discount = 0;
		$total = 0;
		$flag =1;
		foreach($item_details as $k=>$v)
		{
			if($v['karaoke_reservation_id'] == $items[$v['karaoke_reservation_id']]['id']){
				if($flag == $items[$v['karaoke_reservation_id']]['id']){
					$items[$v['karaoke_reservation_id']]['flag'] ++;
					$item_details[$k]['flag']++;
				}
				//$item_details[$k]['arrival_time'] = date('d/m/Y h:m',$items[$v['karaoke_reservation_id']]['arrival_time']);
				$item_details[$k]['customer_name'] = $items[$v['karaoke_reservation_id']]['customer_name'];
				$item_details[$k]['room_name'] = $items[$v['karaoke_reservation_id']]['room_name'];
				$item_details[$k]['receiver_name'] = $items[$v['karaoke_reservation_id']]['receiver_name'];
				$item_details[$k]['receptionist_id'] = $items[$v['karaoke_reservation_id']]['receptionist_id'];
				$item_details[$k]['price'] = System::display_number(String::vnd_round($v['price']));
				$price = String::vnd_round($v['price']); //RES_EXCHANGE_RATE??
				$ttl = $price*($v['quantity'] - $v['quantity_discount']);
				$discnt = String::vnd_round($ttl*$v['discount_rate']/100);
				$item_details[$k]['discount_real'] = System::display_number($discnt);
				$total_price = $ttl-$discnt;
				$total_discount += $discnt;
				$total += $total_price;
				$item_details[$k]['total'] = System::display_number($ttl-$discnt);
				$flag = $items[$v['karaoke_reservation_id']]['id'];
				//$items[$v['karaoke_reservation_id']] = $item_details[$k];
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
		//$rows['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$rows['exchange_currency_id'].'\'','exchange');
		$this->map['item_details'] = $item_details;	
		//$this->map['categories'] = $categories;		
		$this->map['items'] = $items;
		$this->map['view_all'] = $view_all;
		$this->map['total_discount'] = System::display_number($total_discount);
		$this->map['total'] = System::display_number($total);
		$this->map['guest'] = $guest_list;
		$this->map['receiver'] = $receiver_list;
		$this->map['rooms'] = $rooms_list;
		$this->parse_layout('report',$this->map);		
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