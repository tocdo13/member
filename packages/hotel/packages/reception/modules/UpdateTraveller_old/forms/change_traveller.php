<?php
class UpdateTravellerChangeForm extends Form{
	function UpdateTravellerChangeForm(){
		Form::Form('UpdateTravellerChangeForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");		
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->add('traveller_id',new IntType(true,'invalid_traveller_id','0','100000000000'));
		$this->add('reservation_room_id',new IntType(true,'invalid_payment_type',0,100000000000));    
	}
	function on_submit(){
		if(Url::get('action') == 1){
			if($this->check()){
				$traveller_id = Url::get('traveller_id');	
				$rr_id = Url::get('rr_id');	
				$portal_id = Url::get('portal_id');	
				$room_id = Url::get('room_id');
				$rooms_arr = DB::fetch('select room.*,room_level.price from room
									INNER JOIN room_level on room_level.id = room.room_level_id
								 where room.id='.$room_id.'');
				$rr_id_to = Url::get('reservation_room_id');
				$note = Url::get('note');
				DB::query('update reservation_room set reservation_room.adult=reservation_room.adult-1 where id='.Url::get('rr_id').'');
				$reservation_traveller = DB::fetch('select * from reservation_traveller where id='.$traveller_id.'');
				DB::update('reservation_traveller',array('departure_time'=>time(),'status'=>'CHANGE','departure_date'=>''.Date_Time::to_orc_date(date('d/m/y').'')),' id='.$traveller_id.'');
				$reservation_old = DB::fetch_all('select reservation.* 
										from reservation 
										inner join reservation_room ON reservation_room.reservation_id = reservation.id
										where reservation_room.id = '.$rr_id.'');
				$reservation_room_old = DB::fetch_all('select reservation_room.* 
										from reservation_room 
										where id = '.$rr_id.'');
				$r_t_arr = DB::fetch('SELECT reservation_traveller.* from reservation_traveller
									inner join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
									INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id AND traveller.id=reservation_room.traveller_id
									WHERE reservation_room.id='.$rr_id.'');
				if(!empty($r_t_arr)){
					$traveller_other = DB::fetch('select traveller.id as traveller_id from traveller 
							inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id
						WHERE reservation_traveller.reservation_room_id = '.$rr_id.' 
							AND reservation_traveller.id <>'.$traveller_id.' 
							AND reservation_traveller.status=\'CHECKIN\'');
						DB::update('reservation_room',array('traveller_id'=>$traveller_other['traveller_id']),' id='.$rr_id.'');
				}
				foreach($reservation_old as $k => $reservation)
                {
					unset($reservation['id']);
					$reservation['portal_id'] = $portal_id; 
					if($rr_id_to==0){
						$reservation_id = DB::insert('reservation',$reservation); 
					}
				}
				foreach($reservation_room_old as $key => $reservation_room){
					if($rr_id_to==0){
						unset($reservation_room['id']);
						$reservation_room['reservation_id'] = $reservation_id;
						$reservation_room['traveller_id'] = $reservation_traveller['traveller_id'];
						$reservation_room['room_id'] = $room_id;
						$reservation_room['status'] = 'CHECKIN';
						$reservation_room['price'] = $rooms_arr['price'];
						$reservation_room['room_level_id'] = $rooms_arr['room_level_id'];
						$reservation_room['adult'] = 1;
						$reservation_room['time_in'] = time();
						$reservation_room['time_out'] = $reservation_traveller['departure_time'];
						$reservation_room['arrival_time'] = Date_Time::to_orc_date(date('d/m/Y'));
						$reservation_room['departure_time'] = $reservation_traveller['departure_date'];
						$reservation_room['checked_in_user_id'] = Session::get('user_id');
						$rr_id = DB::insert('reservation_room',$reservation_room);
						for($j = Date_Time::to_time(date('d/m/Y')); $j<(Date_Time::to_time(date('d/m/Y',$reservation_traveller['departure_time']))+86400);$j=$j+86400){
							$room_status = array();
							$room_status['reservation_id'] = $reservation_id;
							$room_status['reservation_room_id'] = $rr_id; 
							$room_status['room_id'] = $room_id;
                            $room_status['change_price'] = $rooms_arr['price'];
                            if(date('d/m/Y',$j)==date('d/m/Y',$reservation_traveller['departure_time']))
							$room_status['change_price'] = 0;
							$room_status['in_date'] = Date_Time::to_orc_date(date('d/m/Y',$j));
							$room_status['status'] = 'OCCUPIED';	
							DB::insert('room_status',$room_status);	
						}
					}else if($rr_id_to!=0){
						$rr_id = $rr_id_to;
						$reservation_id = DB::fetch('select reservation_id from reservation_room where id='.$rr_id_to.'','reservation_id');
					}else{
						$reservation_id = $reservation_room['reservation_id'];
					}
				}
				$reservation_traveller['reservation_id'] = $reservation_id;
				$reservation_traveller['reservation_room_id'] = $rr_id;
				$reservation_traveller['arrival_time'] = time();
				$reservation_traveller['note'] = $note;
				$reservation_traveller['arrival_date'] = Date_Time::to_orc_date(date('d/m/y').'');
				$reservation_traveller['status'] = 'CHECKIN';
				unset($reservation_traveller['id']);
				DB::insert('reservation_traveller',$reservation_traveller);
				//$tt = 'form.php?block_id='.Module::block_id().'&cmd=change_guest&rr_id='.Url::get('rr_id').'&traveller_id='.Url::get('traveller_id').'&portal_id='.PORTAL_ID;
				echo '<script>window.parent.location.reload();</script>';    
			}
		}
	}
	function draw(){
		$travel_id='';
		if(!Url::get('traveller_id')){
			$travel_id = DB::fetch('select reservation_traveller.id as traveller_id from reservation_traveller INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id WHERE reservation_room.id='.$rr_id.' ','traveller_id');
		}else{
			$travel_id = Url::get('traveller_id');	
		}
		$_REQUEST['traveller_id'] = $travel_id;
		$rr_id = Url::get('rr_id');
		$row = DB::fetch('select reservation_room.*
							,room.name as room_name 
							,reservation_traveller.departure_time
							,reservation_traveller.arrival_time
							,(traveller.first_name || \' \' || traveller.last_name) as full_name
					from reservation_room 
						INNER JOIN room ON room.id = reservation_room.room_id
						INNER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
						INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
					where reservation_room.id='.$rr_id.' and reservation_traveller.id='.$travel_id);
		$_REQUEST['guest_name'] = $row['full_name'];
		$travellers = $this->get_reservation_traveller($rr_id);
		$travellers_ids = $travellers;
		$traveller_id[0] = '----Traveller----';
		$traveller_id = $traveller_id + String::get_list($travellers_ids);
		//System::Debug($report->items);
		$portals = Portal::get_portal_list();
		$portal_list = String::get_list($portals);
		$rooms_arr = $this->get_rooms($rr_id,$row['room_id']);
		$this->parse_layout('change_traveller',$row+array(
			'travellers_id'=>$traveller_id,
			'travellers'=>$travellers_ids,
			'rooms_list_js'=>String::array2js($rooms_arr),
			'portal_id_list'=>$portal_list
		));
	}
 	function get_reservation_traveller($rr_id){
		$sql_traveller = 'SELECT 
					reservation_traveller.id,CONCAT(traveller.first_name,traveller.last_name) as name
				FROM 	
					reservation_traveller
					INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation_room ON reservation_traveller.reservation_room_id = reservation_room.id
				WHERE 
					reservation_room.id = '.$rr_id.'';
		$travellers = DB::fetch_all($sql_traveller);			
		return $travellers;	
	}
	function get_reservation_other($rr_id){
		return $items = DB::fetch_all('select reservation_room.id,CONCAT(reservation_room.id,CONCAT(\'_\',CONCAT(\''.Portal::language('room').'\',room.name))) as name from reservation_room INNER JOIN room ON reservation_room.room_id = room.id where reservation_room.status=\'CHECKIN\' and reservation_room.id <>\''.$rr_id.'\'');	
	}
	function get_rooms($id,$room_id){
		//$portals = Portal::get_portal_list();
		$rooms = DB::fetch_all('select room.* from room where room.id<>'.$room_id.'');
		$sql = 'select reservation_room.id as id
					,CONCAT(\'Phòng \',CONCAT(room.name,CONCAT(\'_HĐ_\',reservation_room.id))) as name
					,room.name as room_name
					,room.id as room_id
					,reservation.portal_id
				FROM reservation_room
				     INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
					 INNER JOIN room ON reservation_room.room_id = room.id
				WHERE
					reservation_room.status=\'CHECKIN\'
					AND reservation_room.id<>'.$id.'
					AND reservation_room.time_in<='.time().' AND reservation_room.time_out>='.time().'
					';	
		$rrs = DB::fetch_all($sql);
		foreach($rooms as $k=>$room){
			$rooms[$k]['reservation_room_id'] = 0;
			foreach($rrs as $key => $rr){
				if($rr['room_id'] == $room['id']){
					$rooms[$k]['name'] = $room['name'].'- IN - HĐ:'.$rr['id'];
					$rooms[$k]['reservation_room_id'] = $rr['id'];	
				}
			}
		}
		return $rooms;
	}
}
?>