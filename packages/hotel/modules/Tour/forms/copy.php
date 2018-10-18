<?php
class CopyTravellersForm extends Form
{
	function CopyTravellersForm()
	{
		Form::Form('CopyTravellersForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function on_submit(){
		if(empty($_REQUEST['item_check_box']) or empty($_REQUEST['tour_id'])){
			echo '<div style="font-size:14px;font-weight:bold;">Chua chon khach hoac tour/nhom de copy ... </div>';
			echo '
				<script>
					window.setTimeout("location=\''.URL::build_current(array('id','cmd','tour_id','tour_name','customer_id','customer_name')).'\'",1000);
				</script>';
			exit();
		}else{
			if($reservation = DB::fetch('SELECT reservation.* FROM reservation WHERE reservation.tour_id = '.Url::iget('tour_id').'')){
				$reservation_id = $reservation['id'];
			}else{
				$reservation_id = DB::insert('reservation', 
					array(
						'customer_id',
						'tour_id',
						'user_id'=>Session::get('user_id'),
						'time'=>time(),
						'portal_id'=>PORTAL_ID
					)
				);
			}
			if($reservation_id){
				foreach($_REQUEST['item_check_box'] as $value){
					$traveller_id  = $value;
					$reservation_room_id = 0;
					DB::insert('reservation_traveller',
						array(
						'traveller_id'=>$traveller_id,
						'reservation_room_id'=>$reservation_room_id,
						'reservation_id'=>$reservation_id
					));
				}
				Url::redirect('reservation',array('cmd'=>'edit','id'=>$reservation_id));
			}
		}
	}
	function draw()
	{
		$this->map = array();
		$item = Tour::$item;
		if($item){
			$item['total_amount'] = System::display_number_report($item['total_amount']);
			$item['extra_amount'] = System::display_number_report($item['extra_amount']);
			$item['arrival_time'] = Date_Time::convert_orc_date_to_date($item['arrival_time'],'/');
			$item['departure_time'] = Date_Time::convert_orc_date_to_date($item['departure_time'],'/');
			$item['customer_name'] = DB::fetch('SELECT id,name FROM customer WHERE id = '.$item['company_id'].'','name');
			$this->map += $item;
			$sql = '
				SELECT
					traveller.id,country.code_2 as nationality_code,
					to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
					traveller.gender,
					traveller.is_vn,
					DECODE(reservation_room.status,\'CHECKIN\',\'IN\',DECODE(reservation_room.status,\'CHECKOUT\',\'OUT\',DECODE(reservation_room.status,\'BOOKED\',\'B\',\'CANCEL\'))) AS status,
					traveller.first_name,
					traveller.last_name,
					traveller.passport,
					reservation_room.time_in,
					reservation_room.time_out,
					DECODE(room.name,NULL,reservation_traveller.temp_room,room.name) as room_name
				FROM
					traveller
					INNER JOIN reservation_traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation ON reservation.id = reservation_traveller.reservation_id
					LEFT OUTER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
					LEFT OUTER JOIN room ON room.id = reservation_room.room_id
					LEFT OUTER JOIN country On country.id = traveller.nationality_id
				WHERE
					reservation.tour_id = '.$item['id'].'
			';
			$items = DB::fetch_all($sql);
			$i = 1;
			foreach($items as $key=>$value){
				$items[$key]['i'] = $i++;
				$items[$key]['time_in'] = $value['time_in']?date('d/m/Y',$value['time_in']):'';
				$items[$key]['time_out'] = $value['time_out']?date('d/m/Y',$value['time_out']):'';
			}
			$this->map['items'] = $items;
		}
		$this->map['title'] = Portal::language('view_tour_detail');
		$this->parse_layout('copy',$this->map);
	}	
}
?>